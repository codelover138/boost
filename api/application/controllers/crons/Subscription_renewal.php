<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Subscription_renewal cron — safety net for missed PayFast recurring ITN.
 *
 * Run daily:
 *   php-cli /path/to/index.php crons/subscription_renewal run
 *
 * What it does:
 *   1. Finds orgs that PayFast should have charged (paid_until is in the past,
 *      status is still active, not scheduled to cancel).
 *   2. Calls GET /subscriptions/{token}/fetch on PayFast to confirm the
 *      subscription is still live on their side.
 *   3. If PayFast says active → advances paid_until by one billing period and
 *      inserts a completed payment record.
 *   4. If PayFast says cancelled/inactive → moves org to cancelled/expired.
 *
 * This catches any renewal that the ITN webhook failed to deliver.
 */
class Subscription_renewal extends CI_Controller
{
    protected $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('payfast_service');
        $this->load->library('payment_settings');
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function index()
    {
        $this->help();
    }

    public function help()
    {
        if ($this->input->is_cli_request()) {
            echo "Subscription_renewal cron\n";
            echo "Usage: php-cli index.php crons/subscription_renewal run\n";
        } else {
            echo 'Cannot access this file from a web browser';
        }
    }

    public function run()
    {
        if (!$this->input->is_cli_request()) {
            echo 'Cannot access this file from a web browser';
            return;
        }

        $this->ensure_payfast_token_column();

        $orgs = $this->get_overdue_active_orgs();

        if (empty($orgs)) {
            echo "No overdue active subscriptions found.\n";
            return;
        }

        echo count($orgs) . " overdue subscription(s) to check.\n";

        foreach ($orgs as $org) {
            $this->process_org($org);
        }

        echo "Done.\n";
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Adds payfast_token column to organisations if it does not already exist.
     * Safe to call on every run — it is a no-op when the column is present.
     */
    protected function ensure_payfast_token_column()
    {
        $table = $this->table_prefix . 'organisations';

        $check = $this->db->query(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME   = ?
               AND COLUMN_NAME  = 'payfast_token'",
            array($table)
        );

        if ($check && $check->num_rows() === 0) {
            $this->db->query("ALTER TABLE `{$table}` ADD COLUMN `payfast_token` VARCHAR(100) NULL DEFAULT NULL");
            echo "Migration: added payfast_token column to {$table}.\n";
        }
    }

    /**
     * Returns orgs whose subscription should have been renewed but hasn't been.
     */
    protected function get_overdue_active_orgs()
    {
        $table = $this->table_prefix . 'organisations';

        $query = $this->db
            ->select('id, account_name, email, paid_until, current_plan_code, payfast_token, currency_id, cancel_at_period_end')
            ->from($table)
            ->where('subscription_status', 'active')
            ->where('cancel_at_period_end', 0)
            ->where('paid_until <', date('Y-m-d H:i:s'))
            ->where('paid_until IS NOT NULL', null, false)
            ->get();

        return $query ? $query->result() : array();
    }

    protected function process_org($org)
    {
        $token = $this->resolve_token($org);

        if (empty($token)) {
            echo "[SKIP] org #{$org->id} ({$org->account_name}): no PayFast token stored — cannot verify.\n";
            $this->log_renewal_event($org->id, null, 'renewal_skipped_no_token',
                'Cron skipped: no PayFast token found', array('org_id' => $org->id));
            return;
        }

        $fetch = $this->payfast_service->fetch_subscription($token);

        echo "[CHECK] org #{$org->id} ({$org->account_name}): PayFast status = " . ($fetch['status'] ?: 'unknown') . "\n";

        $this->log_renewal_event($org->id, null, 'cron_payfast_fetch', 'PayFast subscription fetch result', array(
            'token'    => $token,
            'status'   => $fetch['status'],
            'run_date' => $fetch['run_date'],
            'bool'     => $fetch['bool']
        ));

        if (!$fetch['bool']) {
            echo "[WARN] org #{$org->id}: PayFast API call failed — skipping.\n";
            return;
        }

        $pf_status = strtolower(trim((string)$fetch['status']));

        // 'success' is PayFast's top-level API response status (the call worked).
        // When bool=true and status='success' the subscription token is valid and active.
        $is_active    = in_array($pf_status, array('active', '1', 'success'), true) && $fetch['bool'];
        $is_cancelled = in_array($pf_status, array('cancelled', 'cancel', 'inactive', 'paused', '0'), true);

        if ($is_active) {
            $this->handle_active_renewal($org, $token, $fetch);
        } elseif ($is_cancelled) {
            $this->handle_cancelled($org, $pf_status);
        } else {
            echo "[WARN] org #{$org->id}: unrecognised PayFast status '{$pf_status}' — skipping.\n";
        }
    }

    protected function handle_active_renewal($org, $token, $fetch)
    {
        $original_payment = $this->get_latest_completed_payment($org->id);

        if (!$original_payment) {
            echo "[SKIP] org #{$org->id}: no completed payment on record — cannot create renewal.\n";
            return;
        }

        $plan = $this->payfast_service->get_plan($org->current_plan_code ?: $original_payment->plan_code);

        // Advance from the recorded paid_until, not from now(), so renewals stack correctly.
        $billing_start = $org->paid_until;
        $billing_end   = $this->payfast_service->calculate_period_end($billing_start, $plan);

        // Guard: do not create a duplicate if a completed payment already covers this period.
        if ($this->period_already_covered($org->id, $billing_start, $billing_end)) {
            echo "[SKIP] org #{$org->id}: period {$billing_start} → {$billing_end} already has a completed payment.\n";
            return;
        }

        // Prefer the amount PayFast has on file (from fetch response) so the record
        // reflects what was actually charged. Fall back to the last completed payment.
        $amount_gross = (!empty($fetch['amount']) && is_numeric($fetch['amount']))
            ? number_format((float)$fetch['amount'], 2, '.', '')
            : number_format((float)$original_payment->amount_gross, 2, '.', '');

        $reference    = 'CRON-' . $org->id . '-' . strtoupper(substr(md5(uniqid((string)$org->id, true)), 0, 8));
        $renewal_data = array(
            'organisation_id'  => $org->id,
            'user_id'          => $original_payment->user_id,
            'account_name'     => $org->account_name,
            'plan_code'        => $plan['code'],
            'item_name'        => $original_payment->item_name,
            'merchant_reference' => $reference,
            'payment_status'   => 'complete',
            'amount_gross'     => $amount_gross,
            'billing_date_from' => $billing_start,
            'billing_date_to'  => $billing_end,
            'payfast_token'    => $token,
            'itn_verified'     => 0,
            'raw_itn_data'     => json_encode(array('source' => 'cron_renewal', 'payfast_fetch' => $fetch['decoded'])),
            'itn_received_at'  => date('Y-m-d H:i:s'),
            'confirmed_at'     => date('Y-m-d H:i:s')
        );

        $this->db->insert($this->table_prefix . 'subscription_payments', $renewal_data);
        $renewal_id = (int)$this->db->insert_id();

        if ($renewal_id <= 0) {
            echo "[ERROR] org #{$org->id}: failed to insert renewal payment record.\n";
            return;
        }

        // Advance the organisation's subscription dates.
        $this->db->where('id', $org->id)
            ->update($this->table_prefix . 'organisations', array(
                'subscription_status'    => 'active',
                'paid_until'             => $billing_end,
                'grace_period_ends_at'   => null,
                'current_plan_code'      => $plan['code'],
                'pending_plan_code'      => null,
                'cancel_at_period_end'   => 0,
                'cancellation_requested_at' => null,
                'payfast_token'          => $token
            ));

        $this->log_renewal_event($org->id, $renewal_id, 'cron_renewal_processed',
            'Subscription renewed by cron after missed ITN', array(
                'billing_start'       => $billing_start,
                'billing_end'         => $billing_end,
                'amount_gross'        => $amount_gross,
                'amount_from_payfast' => !empty($fetch['amount']),
                'token'               => $token,
                'reference'           => $reference
            ));

        echo "[OK] org #{$org->id} ({$org->account_name}): renewed → paid until {$billing_end}, amount R{$amount_gross} (payment #{$renewal_id}).\n";
    }

    protected function handle_cancelled($org, $pf_status)
    {
        $this->db->where('id', $org->id)
            ->update($this->table_prefix . 'organisations', array(
                'subscription_status'  => 'cancelled',
                'cancel_at_period_end' => 0
            ));

        $this->log_renewal_event($org->id, null, 'cron_subscription_cancelled',
            'PayFast reports subscription as ' . $pf_status . '; marked cancelled', array(
                'payfast_status' => $pf_status
            ));

        echo "[CANCEL] org #{$org->id} ({$org->account_name}): PayFast status = {$pf_status}, marked cancelled.\n";
    }

    /**
     * Returns the payfast_token stored on the org row or falls back to the
     * most recent completed payment (mirrors Billing::resolve_payfast_token).
     */
    protected function resolve_token($org)
    {
        if (!empty($org->payfast_token)) {
            return trim((string)$org->payfast_token);
        }

        $payment = $this->get_latest_completed_payment($org->id);
        if ($payment && !empty($payment->payfast_token)) {
            return trim((string)$payment->payfast_token);
        }

        return '';
    }

    protected function get_latest_completed_payment($organisation_id)
    {
        $query = $this->db
            ->select('*')
            ->from($this->table_prefix . 'subscription_payments')
            ->where('organisation_id', $organisation_id)
            ->where('payment_status', 'complete')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get();

        return ($query && $query->num_rows() > 0) ? $query->row() : null;
    }

    /**
     * Returns true if there is already a completed payment whose billing window
     * overlaps the given period, so we never double-charge a period.
     */
    protected function period_already_covered($organisation_id, $billing_start, $billing_end)
    {
        $query = $this->db
            ->select('id')
            ->from($this->table_prefix . 'subscription_payments')
            ->where('organisation_id', $organisation_id)
            ->where('payment_status', 'complete')
            ->where('billing_date_from >=', $billing_start)
            ->where('billing_date_to <=', $billing_end)
            ->limit(1)
            ->get();

        return ($query && $query->num_rows() > 0);
    }

    protected function log_renewal_event($organisation_id, $payment_id, $event_type, $message, $payload = null)
    {
        $this->db->insert($this->table_prefix . 'subscription_events', array(
            'organisation_id' => $organisation_id,
            'payment_id'      => $payment_id,
            'event_type'      => $event_type,
            'message'         => $message,
            'payload'         => $payload ? json_encode($payload) : null
        ));
    }
}
