<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billing extends CI_Controller
{
    protected $table_prefix;

    public function __construct()
    {
        parent::__construct();

        $this->regular->set_response_headers();
        $this->load->library('payfast_service');
        $this->load->library('payment_settings');
        $this->load->library('userhandler');
        $this->load->model('generic_model');
        $this->load->library('email');

        $this->table_prefix = $this->config->item('db_table_prefix');

        $action = strtolower((string)$this->router->fetch_method());
        if (!in_array($action, array('itn'), true)) {
            $this->require_billing_access();
        }
    }

    public function index()
    {
        $this->status();
    }

    public function plan()
    {
        $this->regular->respond(array(
            'status' => 'OK',
            'data' => array(
                'plan' => $this->payfast_service->get_plan(),
                'plans' => $this->payfast_service->get_plans()
            )
        ));
    }

    public function status()
    {
        $org = $this->get_current_organisation();
        $latest_payment = $this->get_latest_payment($org->id);
        $latest_completed_payment = $this->get_latest_completed_payment($org->id);
        $current_plan_code = $this->resolve_current_plan_code($org, $latest_completed_payment);
        $summary = $this->build_subscription_summary($org);

        $response = array(
            'plan' => $this->payfast_service->get_plan($current_plan_code),
            'plans' => $this->payfast_service->get_plans(),
            'subscription' => array(
                'status' => $summary['status'],
                'trial_ends_at' => $org->trial_ends_at,
                'paid_until' => $org->paid_until,
                'grace_period_ends_at' => $summary['grace_period_ends_at'],
                'is_manual_blocked' => (int)$org->is_manual_blocked,
                'can_pay' => $summary['can_pay'],
                'can_cancel' => $summary['can_cancel'],
                'can_change_plan' => $summary['can_change_plan'],
                'has_paid_access' => $summary['has_paid_access'],
                'access_message' => $summary['access_message'],
                'current_plan_code' => $current_plan_code,
                'current_plan' => $this->payfast_service->get_plan($current_plan_code),
                'pending_plan_code' => !empty($org->pending_plan_code) ? $org->pending_plan_code : null,
                'pending_plan' => !empty($org->pending_plan_code) ? $this->payfast_service->get_plan($org->pending_plan_code) : null,
                'cancel_at_period_end' => !empty($org->cancel_at_period_end) ? 1 : 0,
                'cancellation_requested_at' => !empty($org->cancellation_requested_at) ? $org->cancellation_requested_at : null,
                'plan_change_effective_at' => $summary['plan_change_effective_at']
            ),
            'latest_payment' => $latest_payment,
            'history' => $this->get_payment_history($org->id, 10)
        );

        $this->regular->respond(array('status' => 'OK', 'data' => $response));
    }

    public function history()
    {
        $org = $this->get_current_organisation();
        $history = $this->get_payment_history($org->id, 25);

        $this->regular->respond(array('status' => 'OK', 'data' => $history));
    }

    public function invoice($payment_id = null)
    {
        if (empty($payment_id) || !is_numeric($payment_id)) {
            $this->regular->header_(400);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Invalid payment selected')));
            return;
        }

        $org = $this->get_current_organisation();
        $payment = $this->get_payment_by_id((int)$payment_id);

        if (!$payment || (int)$payment->organisation_id !== (int)$org->id) {
            $this->regular->header_(404);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Payment invoice not found')));
            return;
        }

        $this->regular->respond(array(
            'status' => 'OK',
            'data' => array(
                'payment' => $payment,
                'organisation' => $org,
                'plan' => $this->payfast_service->get_plan(isset($payment->plan_code) ? $payment->plan_code : null)
            )
        ));
    }

    public function initiate()
    {
        if ($this->regular->request_method() !== 'POST') {
            $this->regular->header_(405);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('POST required')));
            return;
        }

        $request = $this->regular->decode('POST');
        if (!is_array($request)) {
            $request = array();
        }

        $org = $this->get_current_organisation();
        $summary = $this->build_subscription_summary($org);
        $user_data = $this->userhandler->determine_user();
        $requested_plan_code = isset($request['plan_code']) ? trim((string)$request['plan_code']) : '';

        if ($summary['has_paid_access']) {
            $this->regular->header_(409);
            $this->regular->respond(array(
                'status' => 'ERROR',
                'message' => array('Your subscription is still active. Use the plan change action to switch billing cycles for the next renewal.')
            ));
            return;
        }

        if ($requested_plan_code !== '' && !$this->payfast_service->has_plan($requested_plan_code)) {
            $this->regular->header_(400);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Invalid subscription plan selected')));
            return;
        }

        $plan = $this->payfast_service->get_plan($requested_plan_code);

        if (!$this->payfast_service->get_setting('merchant_id') || !$this->payfast_service->get_setting('merchant_key')) {
            $this->regular->header_(500);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('PayFast merchant credentials are not configured')));
            return;
        }

        if (!$user_data['bool']) {
            $this->regular->header_(401);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Unable to determine current user')));
            return;
        }

        $user = $user_data['data'];
        $billing_start = $this->resolve_billing_start($org);
        $billing_end = $this->payfast_service->calculate_period_end($billing_start, $plan);

        $payment_post = array(
            'organisation_id' => $org->id,
            'user_id' => $user->id,
            'account_name' => $org->account_name,
            'plan_code' => $plan['code'],
            'item_name' => $plan['name'],
            'merchant_reference' => $this->generate_reference($org->id),
            'payment_status' => 'pending',
            'amount_gross' => $plan['amount'],
            'billing_date_from' => $billing_start,
            'billing_date_to' => $billing_end
        );

        $params = array(
            'table' => $this->table_prefix . 'subscription_payments',
            'entity' => 'subscription payment'
        );

        $this->switch_to_main_db($org->account_name);
        $create = $this->generic_model->create($params, $payment_post);
        if (!$create['bool']) {
            $this->regular->header_(500);
            $this->regular->respond(array('status' => 'ERROR', 'message' => $create['message']));
            return;
        }

        $payment = $this->generic_model->read($params, $create['record_id'], 'single');
        $fields = $this->payfast_service->build_payment_fields($payment, $org, $user, $plan);

        $this->generic_model->update($params, $payment->id, array(
            'signature' => $fields['signature'],
            'raw_request_data' => json_encode($fields)
        ), false);

        $this->create_event($org->id, $payment->id, 'payment_initiated', 'PayFast payment initiated', $fields);

        $this->regular->respond(array(
            'status' => 'OK',
            'data' => array(
                'payment_id' => $payment->id,
                'payment_url' => $this->payfast_service->get_payfast_url(),
                'fields' => $fields
            )
        ));
    }

    public function change_plan()
    {
        if ($this->regular->request_method() !== 'POST') {
            $this->regular->header_(405);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('POST required')));
            return;
        }

        $request = $this->regular->decode('POST');
        if (!is_array($request)) {
            $request = array();
        }

        $requested_plan_code = isset($request['plan_code']) ? trim((string)$request['plan_code']) : '';
        if ($requested_plan_code === '' || !$this->payfast_service->has_plan($requested_plan_code)) {
            $this->regular->header_(400);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Invalid subscription plan selected')));
            return;
        }

        $org = $this->get_current_organisation();
        $summary = $this->build_subscription_summary($org);
        if (!$summary['has_paid_access']) {
            $this->regular->header_(409);
            $this->regular->respond(array(
                'status' => 'ERROR',
                'message' => array('There is no active paid subscription to change. Start a payment instead.')
            ));
            return;
        }

        $current_plan_code = $this->resolve_current_plan_code($org);
        if ($requested_plan_code === $current_plan_code && empty($org->pending_plan_code)) {
            $this->regular->header_(409);
            $this->regular->respond(array(
                'status' => 'ERROR',
                'message' => array('This billing cycle is already active for your workspace.')
            ));
            return;
        }

        if (!empty($org->pending_plan_code) && $requested_plan_code === $org->pending_plan_code) {
            $this->regular->header_(409);
            $this->regular->respond(array(
                'status' => 'ERROR',
                'message' => array('This billing cycle is already scheduled for your next renewal.')
            ));
            return;
        }

        $this->switch_to_main_db($org->account_name);
        $params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'organisation'
        );

        $revert_to_current_plan = $requested_plan_code === $current_plan_code;

        $update = array(
            'pending_plan_code' => $revert_to_current_plan ? null : $requested_plan_code,
            'cancel_at_period_end' => 0,
            'cancellation_requested_at' => null
        );

        if (isset($org->subscription_status) && $org->subscription_status === 'cancelled') {
            $update['subscription_status'] = 'active';
        }

        $this->generic_model->update($params, $org->id, $update, false);
        if ($revert_to_current_plan) {
            $this->create_event($org->id, null, 'plan_change_reverted', 'Scheduled plan change removed', array(
                'plan_code' => $current_plan_code
            ));

            $current_plan = $this->payfast_service->get_plan($current_plan_code);
            $this->regular->respond(array(
                'status' => 'OK',
                'message' => array(
                    'Your subscription will stay on ' . $current_plan['name'] . ' for the next renewal.'
                )
            ));
            return;
        }

        $this->create_event($org->id, null, 'plan_change_scheduled', 'Subscription plan change scheduled', array(
            'from_plan_code' => $current_plan_code,
            'to_plan_code' => $requested_plan_code,
            'effective_at' => $org->paid_until
        ));

        $next_plan = $this->payfast_service->get_plan($requested_plan_code);
        $this->regular->respond(array(
            'status' => 'OK',
            'message' => array(
                'Your subscription will switch to ' . $next_plan['name'] . ' when the current paid term ends.'
            )
        ));
    }

    public function cancel()
    {
        if ($this->regular->request_method() !== 'POST') {
            $this->regular->header_(405);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('POST required')));
            return;
        }

        $org = $this->get_current_organisation();
        $summary = $this->build_subscription_summary($org);

        if ($summary['has_paid_access'] && !empty($org->cancel_at_period_end)) {
            $this->regular->header_(409);
            $this->regular->respond(array(
                'status' => 'ERROR',
                'message' => array('Cancellation is already scheduled for the end of the current billing period.')
            ));
            return;
        }

        $this->switch_to_main_db($org->account_name);
        $params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'organisation'
        );

        $payfast_token = $this->resolve_payfast_token($org);
        if ($payfast_token === '') {
            $this->regular->header_(409);
            $this->regular->respond(array(
                'status' => 'ERROR',
                'message' => array('No PayFast subscription token is stored for this workspace, so the recurring subscription cannot be cancelled safely.')
            ));
            return;
        }

        $cancel_result = $this->payfast_service->cancel_subscription($payfast_token);
        if (empty($cancel_result['bool'])) {
            $this->log_itn_audit('subscription_cancel_failed', array(
                'organisation_id' => $org->id,
                'payfast_token' => $payfast_token,
                'payfast_response' => $cancel_result
            ));
            $this->regular->header_(502);
            $this->regular->respond(array(
                'status' => 'ERROR',
                'message' => array('PayFast cancellation failed. Your subscription has not been changed locally yet.')
            ));
            return;
        }

        $update = array(
            'pending_plan_code' => null,
            'payfast_token' => $payfast_token
        );

        if ($summary['has_paid_access']) {
            $update['subscription_status'] = 'active';
            $update['cancel_at_period_end'] = 1;
            $update['cancellation_requested_at'] = date('Y-m-d H:i:s');
            $message = 'Your PayFast subscription has been cancelled. Your workspace will stay active until the current paid term ends.';
        } else {
            $update['subscription_status'] = 'cancelled';
            $update['cancel_at_period_end'] = 0;
            $update['cancellation_requested_at'] = date('Y-m-d H:i:s');
            $message = 'Your PayFast subscription has been cancelled and your workspace is now inactive except for billing access.';
        }

        $this->generic_model->update($params, $org->id, $update, false);
        $this->create_event($org->id, null, 'subscription_cancelled', 'Subscription cancellation requested', array(
            'paid_until' => !empty($org->paid_until) ? $org->paid_until : null,
            'cancel_at_period_end' => $summary['has_paid_access'] ? 1 : 0,
            'payfast_token' => $payfast_token,
            'payfast_cancel_status' => isset($cancel_result['status_code']) ? $cancel_result['status_code'] : null
        ));
        $this->send_cancellation_notice_email($org, $summary['has_paid_access'] ? $org->paid_until : null);

        $this->regular->respond(array('status' => 'OK', 'message' => array($message)));
    }

    public function simulate_recurring($payment_id = null)
    {
        if ($this->regular->request_method() !== 'POST') {
            $this->regular->header_(405);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('POST required')));
            return;
        }

        if (!$this->payfast_service->get_setting('test_mode')) {
            $this->regular->header_(403);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Recurring simulation is only available in sandbox mode.')));
            return;
        }

        $org = $this->get_current_organisation();
        $request = $this->regular->decode('POST');
        if (!is_array($request)) {
            $request = array();
        }

        if ($payment_id === null && isset($request['payment_id']) && $request['payment_id'] !== '') {
            $payment_id = $request['payment_id'];
        }

        if ($payment_id !== null && (!is_numeric($payment_id) || (int)$payment_id <= 0)) {
            $this->regular->header_(400);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Invalid payment selected for recurring simulation.')));
            return;
        }

        $source_payment = $payment_id !== null
            ? $this->get_payment_by_id((int)$payment_id)
            : $this->get_latest_completed_payment($org->id);

        if (!$source_payment || (int)$source_payment->organisation_id !== (int)$org->id) {
            $this->regular->header_(404);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Completed subscription payment not found for simulation.')));
            return;
        }

        if ((string)$source_payment->payment_status !== 'complete') {
            $this->regular->header_(409);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Recurring simulation requires a completed payment.')));
            return;
        }

        $payfast_token = !empty($source_payment->payfast_token)
            ? trim((string)$source_payment->payfast_token)
            : (!empty($org->payfast_token) ? trim((string)$org->payfast_token) : '');

        if ($payfast_token === '') {
            $this->regular->header_(409);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('No PayFast token is stored for this subscription yet.')));
            return;
        }

        $simulated_post = array(
            'm_payment_id' => (string)$source_payment->id,
            'pf_payment_id' => 'SIM-' . $source_payment->id . '-' . time(),
            'payment_status' => 'COMPLETE',
            'item_name' => $source_payment->item_name,
            'amount_gross' => number_format((float)$source_payment->amount_gross, 2, '.', ''),
            'amount_fee' => isset($source_payment->amount_fee) && $source_payment->amount_fee !== null
                ? number_format((float)$source_payment->amount_fee, 2, '.', '')
                : '0.00',
            'amount_net' => isset($source_payment->amount_net) && $source_payment->amount_net !== null
                ? number_format((float)$source_payment->amount_net, 2, '.', '')
                : number_format((float)$source_payment->amount_gross, 2, '.', ''),
            'custom_str1' => $source_payment->merchant_reference,
            'custom_str2' => $source_payment->account_name,
            'merchant_id' => (string)$this->payfast_service->get_setting('merchant_id'),
            'token' => $payfast_token,
            'billing_date' => !empty($source_payment->billing_date_to)
                ? date('Y-m-d', strtotime($source_payment->billing_date_to))
                : date('Y-m-d')
        );

        $before_latest = $this->get_latest_payment($org->id);
        $this->process_recurring_renewal($org, $source_payment, $simulated_post, $payfast_token);
        $after_latest = $this->get_latest_payment($org->id);

        if (!$after_latest || ($before_latest && (int)$after_latest->id === (int)$before_latest->id)) {
            $this->regular->header_(500);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Recurring simulation did not create a new renewal payment. Check the API logs for details.')));
            return;
        }

        $this->regular->respond(array(
            'status' => 'OK',
            'message' => array('Sandbox recurring renewal simulated successfully.'),
            'data' => array(
                'payment' => $after_latest,
                'source_payment_id' => (int)$source_payment->id
            )
        ));
    }

    public function itn()
    {
        $raw_body = file_get_contents('php://input');
        $post = $_POST;
        if (empty($post)) {
            $post = $this->regular->decode('POST');
        }

        $this->log_itn_audit('received', array(
            'method' => $this->regular->request_method(),
            'ip' => $this->input->ip_address(),
            'raw_body' => $raw_body,
            'payload' => $post
        ));

        if (empty($post) || !isset($post['m_payment_id'])) {
            $this->log_itn_audit('rejected_invalid_payload', array('payload' => $post));
            $this->output_itn_response(400, 'Invalid ITN payload');
            return;
        }

        $payment = $this->get_payment_by_id((int)$post['m_payment_id']);
        if (!$payment) {
            $this->log_itn_audit('rejected_payment_not_found', array(
                'payment_id' => isset($post['m_payment_id']) ? $post['m_payment_id'] : null
            ));
            $this->output_itn_response(404, 'Payment not found');
            return;
        }

        $org = $this->get_organisation_by_id($payment->organisation_id);
        if (!$org) {
            $this->log_itn_audit('rejected_organisation_not_found', array(
                'payment_id' => $payment->id,
                'organisation_id' => $payment->organisation_id
            ));
            $this->output_itn_response(404, 'Organisation not found');
            return;
        }

        $existing_status = $payment->payment_status;

        $remote_ip = $this->input->ip_address();
        $ip_valid = $this->payfast_service->validate_source_ip($remote_ip);
        $signature_valid = $this->payfast_service->validate_signature($post, $raw_body);
        $amount_valid = isset($post['amount_gross']) &&
            number_format((float)$post['amount_gross'], 2, '.', '') === number_format((float)$payment->amount_gross, 2, '.', '');
        $merchant_valid = isset($post['merchant_id']) &&
            (string)$post['merchant_id'] === (string)$this->payfast_service->get_setting('merchant_id');
        $reference_valid = isset($post['custom_str1']) &&
            (string)$post['custom_str1'] === (string)$payment->merchant_reference;

        $confirm = $this->payfast_service->confirm_itn($post);
        $normalised_status = $this->payfast_service->normalise_payment_status(isset($post['payment_status']) ? $post['payment_status'] : 'pending');

        $hard_validation_errors = array();
        $warning_messages = array();
        if (!$ip_valid) {
            $warning_messages[] = 'Invalid source IP';
        }
        if (!$signature_valid) {
            $hard_validation_errors[] = 'Invalid signature';
        }
        if (!$amount_valid && $normalised_status !== 'cancelled') {
            $hard_validation_errors[] = 'Amount mismatch';
        }
        if (!$merchant_valid) {
            $hard_validation_errors[] = 'Merchant mismatch';
        }
        if (!$reference_valid) {
            $hard_validation_errors[] = 'Reference mismatch';
        }
        if (!$confirm['bool']) {
            $warning_messages[] = 'PayFast confirmation failed: ' . $confirm['message'];
        }

        $this->log_itn_audit('validation', array(
            'payment_id' => $payment->id,
            'organisation_id' => $org->id,
            'status_from_payfast' => isset($post['payment_status']) ? $post['payment_status'] : null,
            'normalised_status' => $normalised_status,
            'checks' => array(
                'ip_valid' => $ip_valid,
                'signature_valid' => $signature_valid,
                'amount_valid' => $amount_valid,
                'merchant_valid' => $merchant_valid,
                'reference_valid' => $reference_valid,
                'confirm_valid' => $confirm['bool']
            ),
            'confirm_message' => $confirm['message'],
            'hard_validation_errors' => $hard_validation_errors,
            'warning_messages' => $warning_messages
        ));

        $payfast_token = isset($post['token']) ? trim((string)$post['token']) : null;

        $update_post = array(
            'payment_status'    => !empty($hard_validation_errors) ? 'invalid' : $normalised_status,
            'payfast_payment_id' => isset($post['pf_payment_id']) ? $post['pf_payment_id'] : null,
            'payfast_reference' => isset($post['payment_id']) ? $post['payment_id'] : null,
            'payfast_token'     => $payfast_token,
            'payment_method'    => isset($post['payment_method']) ? $post['payment_method'] : null,
            'amount_fee'        => isset($post['amount_fee']) ? $post['amount_fee'] : null,
            'amount_net'        => isset($post['amount_net']) ? $post['amount_net'] : null,
            'itn_verified'      => empty($hard_validation_errors) ? 1 : 0,
            'raw_itn_data'      => json_encode($post),
            'failure_reason'    => empty($hard_validation_errors)
                ? (!empty($warning_messages) ? implode('; ', $warning_messages) : null)
                : implode('; ', $hard_validation_errors),
            'itn_received_at'   => date('Y-m-d H:i:s')
        );

        if (empty($hard_validation_errors) && $normalised_status === 'complete') {
            $update_post['confirmed_at'] = date('Y-m-d H:i:s');
        }

        $params = array(
            'table' => $this->table_prefix . 'subscription_payments',
            'entity' => 'subscription payment'
        );
        $this->generic_model->update($params, $payment->id, $update_post, false);

        $this->log_itn_audit('payment_updated', array(
            'payment_id'      => $payment->id,
            'organisation_id' => $org->id,
            'update'          => $update_post
        ));

        if (!empty($hard_validation_errors)) {
            $this->create_event($org->id, $payment->id, 'payment_invalid', 'PayFast ITN rejected', array(
                'errors'  => $hard_validation_errors,
                'warnings' => $warning_messages,
                'payload' => $post
            ));
            $this->send_payment_failure_email($org, $payment, implode('; ', $hard_validation_errors));
            $this->log_itn_audit('rejected_validation', array(
                'payment_id'        => $payment->id,
                'organisation_id'   => $org->id,
                'validation_errors' => $hard_validation_errors,
                'warning_messages' => $warning_messages
            ));
            $this->output_itn_response(200, 'ITN rejected');
            return;
        }

        if ($normalised_status === 'complete') {
            if ($existing_status !== 'complete') {
                // Initial payment confirmation
                $this->activate_subscription($org, $payment, $payfast_token);
                $invoice_id = $this->ensure_subscription_invoice($org, $payment);
                $this->create_event($org->id, $payment->id, 'payment_completed', 'PayFast payment confirmed', $post);
                $this->send_payment_success_email($org, $payment);
                $this->log_itn_audit('subscription_activated', array(
                    'payment_id'      => $payment->id,
                    'organisation_id' => $org->id,
                    'paid_until'      => $payment->billing_date_to,
                    'payfast_token'   => $payfast_token,
                    'invoice_id'      => $invoice_id
                ));
            } elseif (!empty($payfast_token)) {
                // Recurring auto-renewal from PayFast subscription
                $this->process_recurring_renewal($org, $payment, $post, $payfast_token);
            }
            $this->output_itn_response(200, 'Payment processed');
            return;
        }

        if ($normalised_status === 'cancelled') {
            $this->create_event($org->id, $payment->id, 'subscription_cancelled_itn', 'PayFast subscription cancellation notification received', $post);
            $this->send_subscription_cancelled_email($org, $payment);
            $this->log_itn_audit('subscription_cancelled_itn', array(
                'payment_id'      => $payment->id,
                'organisation_id' => $org->id,
            ));
            $this->output_itn_response(200, 'Cancellation recorded');
            return;
        }

        $this->mark_payment_failed($org, $payment, $normalised_status, $post);
        $this->log_itn_audit('payment_not_complete', array(
            'payment_id'      => $payment->id,
            'organisation_id' => $org->id,
            'normalised_status' => $normalised_status
        ));
        $this->output_itn_response(200, 'Payment state recorded');
    }

    protected function require_billing_access()
    {
        $headers = $this->regular->get_request_headers();
        $account_name = isset($headers['Account-Name']) ? $headers['Account-Name'] : null;

        if (!$account_name) {
            $this->regular->header_(401);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Account-Name header not found')));
            exit;
        }

        $this->load->library('db/switcher', array('account_name' => $account_name));
        $org = $this->switcher->check_sub_status($account_name);
        if (!$org) {
            $this->regular->header_(404);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Account not found')));
            exit;
        }

        if ((int)$org->is_manual_blocked === 1) {
            $this->regular->header_(403);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Account is manually blocked')));
            exit;
        }

        $this->userhandler->valid_token();
    }

    protected function get_current_organisation()
    {
        $headers = $this->regular->get_request_headers();
        $account_name = isset($headers['Account-Name']) ? $headers['Account-Name'] : null;

        $this->load->library('db/switcher', array('account_name' => $account_name));
        $org = $this->switcher->check_sub_status($account_name);

        if (!$org) {
            $this->regular->header_(404);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Organisation not found')));
            exit;
        }

        return $org;
    }

    protected function get_payment_history($organisation_id, $limit = 10)
    {
        $account_name = $this->current_account_name();
        $this->switch_to_main_db($account_name);
        $params = array(
            'table' => $this->table_prefix . 'subscription_payments',
            'entity' => 'subscription payment',
            'where' => array('organisation_id' => $organisation_id),
            'order_by' => 'id DESC',
            'limit' => $limit
        );

        return array_values((array)$this->generic_model->read($params));
    }

    protected function get_latest_payment($organisation_id)
    {
        $history = $this->get_payment_history($organisation_id, 1);
        return !empty($history) ? $history[0] : null;
    }

    protected function get_latest_completed_payment($organisation_id)
    {
        $account_name = $this->current_account_name();
        $this->switch_to_main_db($account_name);
        $params = array(
            'table' => $this->table_prefix . 'subscription_payments',
            'entity' => 'subscription payment',
            'where' => array(
                'organisation_id' => $organisation_id,
                'payment_status' => 'complete'
            ),
            'order_by' => 'id DESC',
            'limit' => 1
        );

        $history = array_values((array)$this->generic_model->read($params));
        return !empty($history) ? $history[0] : null;
    }

    protected function resolve_payfast_token($org)
    {
        if (!empty($org->payfast_token)) {
            return trim((string)$org->payfast_token);
        }

        $latest_completed_payment = $this->get_latest_completed_payment($org->id);
        if ($latest_completed_payment && !empty($latest_completed_payment->payfast_token)) {
            return trim((string)$latest_completed_payment->payfast_token);
        }

        return '';
    }

    protected function resolve_billing_start($org)
    {
        if (!empty($org->paid_until) && strtotime($org->paid_until) > time()) {
            return $org->paid_until;
        }

        return date('Y-m-d H:i:s');
    }

    protected function generate_reference($organisation_id)
    {
        return 'SUB-' . $organisation_id . '-' . strtoupper(substr(md5(uniqid((string)$organisation_id, true)), 0, 10));
    }

    protected function get_payment_by_id($payment_id)
    {
        $account_name = $this->current_account_name();
        if ($account_name) {
            $this->switch_to_main_db($account_name);
        }
        $params = array(
            'table' => $this->table_prefix . 'subscription_payments',
            'entity' => 'subscription payment'
        );

        return $this->generic_model->read($params, $payment_id, 'single');
    }

    protected function get_organisation_by_id($organisation_id)
    {
        $account_name = $this->current_account_name();
        if ($account_name) {
            $this->switch_to_main_db($account_name);
        }
        $params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'organisation'
        );

        return $this->generic_model->read($params, $organisation_id, 'single');
    }

    protected function activate_subscription($org, $payment, $payfast_token = null)
    {
        $this->switch_to_main_db($org->account_name);
        $params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'organisation'
        );

        $update = array(
            'subscription_status'    => 'active',
            'paid_until'             => $payment->billing_date_to,
            'grace_period_ends_at'   => null,
            'current_plan_code'      => $payment->plan_code,
            'pending_plan_code'      => null,
            'cancel_at_period_end'   => 0,
            'cancellation_requested_at' => null
        );

        if ($payfast_token !== null && $payfast_token !== '') {
            $update['payfast_token'] = $payfast_token;
        }

        $this->generic_model->update($params, $org->id, $update, false);
    }

    protected function process_recurring_renewal($org, $original_payment, $post, $payfast_token)
    {
        $plan = $this->payfast_service->get_plan($original_payment->plan_code);
        // Always start from org's current paid_until so the 2nd, 3rd... renewal
        // advances correctly instead of repeating the same period.
        $billing_start = !empty($org->paid_until) ? $org->paid_until : $original_payment->billing_date_to;
        $billing_end   = $this->payfast_service->calculate_period_end($billing_start, $plan);

        $renewal_data = array(
            'organisation_id'  => $org->id,
            'user_id'          => $original_payment->user_id,
            'account_name'     => $original_payment->account_name,
            'plan_code'        => $original_payment->plan_code,
            'item_name'        => $original_payment->item_name,
            'merchant_reference' => $this->generate_reference($org->id),
            'payment_status'   => 'complete',
            'amount_gross'     => $original_payment->amount_gross,
            'billing_date_from' => $billing_start,
            'billing_date_to'  => $billing_end,
            'payfast_payment_id' => isset($post['pf_payment_id']) ? $post['pf_payment_id'] : null,
            'payfast_token'    => $payfast_token,
            'payment_method'   => isset($post['payment_method']) ? $post['payment_method'] : null,
            'amount_fee'       => isset($post['amount_fee']) ? $post['amount_fee'] : null,
            'amount_net'       => isset($post['amount_net']) ? $post['amount_net'] : null,
            'itn_verified'     => 1,
            'raw_itn_data'     => json_encode($post),
            'itn_received_at'  => date('Y-m-d H:i:s'),
            'confirmed_at'     => date('Y-m-d H:i:s')
        );

        $params = array(
            'table'  => $this->table_prefix . 'subscription_payments',
            'entity' => 'subscription payment'
        );

        $this->switch_to_main_db($org->account_name);

        // Guard against duplicate ITN retries for the same PayFast transaction
        if (!empty($renewal_data['payfast_payment_id'])) {
            $existing = $this->db->select('id')
                ->from($this->table_prefix . 'subscription_payments')
                ->where('payfast_payment_id', $renewal_data['payfast_payment_id'])
                ->where('organisation_id', $org->id)
                ->limit(1)
                ->get()->row();
            if ($existing) {
                $this->log_itn_audit('renewal_duplicate_skipped', array(
                    'organisation_id'    => $org->id,
                    'payfast_payment_id' => $renewal_data['payfast_payment_id']
                ));
                return;
            }
        }

        $create = $this->generic_model->create($params, $renewal_data);

        if (!$create['bool']) {
            $this->log_itn_audit('renewal_create_failed', array(
                'organisation_id' => $org->id,
                'payfast_token'   => $payfast_token,
                'error'           => $create['message']
            ));
            return;
        }

        $renewal_payment = $this->generic_model->read($params, $create['record_id'], 'single');
        $this->activate_subscription($org, $renewal_payment, $payfast_token);
        $invoice_id = $this->ensure_subscription_invoice($org, $renewal_payment);
        $this->create_event($org->id, $renewal_payment->id, 'payment_completed', 'PayFast recurring auto-renewal confirmed', $post);
        $this->send_payment_success_email($org, $renewal_payment);
        $this->log_itn_audit('recurring_renewal_processed', array(
            'organisation_id'  => $org->id,
            'renewal_payment_id' => $renewal_payment->id,
            'paid_until'       => $billing_end,
            'payfast_token'    => $payfast_token,
            'invoice_id'       => $invoice_id
        ));
    }

    protected function mark_payment_failed($org, $payment, $status, $payload)
    {
        $grace_days = $this->payment_settings->get_grace_period_days();
        $grace_ends = date('Y-m-d H:i:s', strtotime('+' . $grace_days . ' days'));
        $sub_status = strtotime((string)$org->paid_until) > time() ? $org->subscription_status : 'past_due';

        $this->switch_to_main_db($org->account_name);
        $org_params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'organisation'
        );

        $this->generic_model->update($org_params, $org->id, array(
            'subscription_status' => $sub_status,
            'grace_period_ends_at' => $sub_status === 'past_due' ? $grace_ends : $org->grace_period_ends_at
        ), false);

        $this->create_event($org->id, $payment->id, 'payment_' . $status, 'PayFast payment was not completed', $payload);
        $this->send_payment_failure_email($org, $payment, 'Payment status: ' . $status);
    }

    protected function create_event($organisation_id, $payment_id, $event_type, $message, $payload = null)
    {
        $org = $this->get_organisation_by_id($organisation_id);
        if ($org && !empty($org->account_name)) {
            $this->switch_to_main_db($org->account_name);
        }

        $params = array(
            'table' => $this->table_prefix . 'subscription_events',
            'entity' => 'subscription event'
        );

        $this->generic_model->create($params, array(
            'organisation_id' => $organisation_id,
            'payment_id' => $payment_id,
            'event_type' => $event_type,
            'message' => $message,
            'payload' => $payload ? json_encode($payload) : null
        ));
    }

    protected function send_payment_success_email($org, $payment)
    {
        $subject = 'Boost Accounting payment confirmed';
        $message = '<p>Your subscription payment has been confirmed.</p>';
        $message .= '<p>Access is active until <strong>' . $payment->billing_date_to . '</strong>.</p>';
        $message .= '<p>Reference: <strong>' . $payment->merchant_reference . '</strong></p>';

        $this->send_email_message($org->email, $subject, $message);
    }

    protected function send_payment_failure_email($org, $payment, $reason)
    {
        $subject = 'Boost Accounting payment failed';
        $message = '<p>We could not confirm your subscription payment.</p>';
        $message .= '<p>Reference: <strong>' . $payment->merchant_reference . '</strong></p>';
        $message .= '<p>Reason: ' . htmlspecialchars($reason, ENT_QUOTES, 'UTF-8') . '</p>';
        $message .= '<p>Please log in and try the payment again.</p>';

        $this->send_email_message($org->email, $subject, $message);
    }

    protected function send_cancellation_notice_email($org, $paid_until = null)
    {
        $subject = 'Boost Accounting subscription cancelled';
        $message = '<p>Your Boost Accounting subscription has been cancelled.</p>';
        if ($paid_until) {
            $message .= '<p>Your workspace will remain active until <strong>' . htmlspecialchars($paid_until, ENT_QUOTES, 'UTF-8') . '</strong>.</p>';
        }
        $message .= '<p>If you did not request this cancellation or wish to resubscribe, please log in and renew your subscription.</p>';
        $this->send_email_message($org->email, $subject, $message);
    }

    protected function send_subscription_cancelled_email($org, $payment)
    {
        $subject = 'Boost Accounting subscription cancelled';
        $message = '<p>Your Boost Accounting subscription has been cancelled.</p>';
        $message .= '<p>Reference: <strong>' . htmlspecialchars($payment->merchant_reference, ENT_QUOTES, 'UTF-8') . '</strong></p>';
        $message .= '<p>If you did not request this cancellation or wish to resubscribe, please log in and renew your subscription.</p>';
        $this->send_email_message($org->email, $subject, $message);
    }

    protected function send_email_message($email, $subject, $message)
    {
        if (!$email) {
            return;
        }

        $data = array(
            'subject' => $subject,
            'heading' => $subject,
            'message' => $message
        );

        $body = $this->load->view('templates/mailer', $data, true);

        $this->email->clear();
        $this->email->from($this->config->item('from_email'), 'Boost Accounting');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($body);
        $this->email->send();
    }

    protected function output_itn_response($status_code, $message)
    {
        http_response_code($status_code);
        header('Content-Type: text/plain');
        echo $message;
    }

    protected function current_account_name()
    {
        $headers = $this->regular->get_request_headers();
        return isset($headers['Account-Name']) ? $headers['Account-Name'] : null;
    }

    protected function switch_to_main_db($account_name)
    {
        if (!$account_name) {
            return;
        }

        $this->load->library('db/switcher', array('account_name' => $account_name));
        $this->switcher->main_db();
    }

    protected function switch_to_account_db($account_name)
    {
        if (!$account_name) {
            return false;
        }

        $this->load->library('db/switcher', array('account_name' => $account_name));
        return (bool)$this->switcher->account_db();
    }

    protected function ensure_subscription_invoice($org, $payment)
    {
        if (!$this->switch_to_account_db($org->account_name)) {
            $this->log_itn_audit('invoice_skipped_account_db_missing', array(
                'organisation_id' => $org->id,
                'payment_id' => $payment->id
            ));
            return null;
        }

        $existing_invoice_id = $this->find_invoice_id_by_reference($payment->merchant_reference);
        if ($existing_invoice_id) {
            $this->switch_to_main_db($org->account_name);
            return $existing_invoice_id;
        }

        $contact_id = $this->get_or_create_subscription_contact($org);
        if (!$contact_id) {
            $this->log_itn_audit('invoice_skipped_contact_missing', array(
                'organisation_id' => $org->id,
                'payment_id' => $payment->id
            ));
            $this->switch_to_main_db($org->account_name);
            return null;
        }

        $invoice_number = $this->next_invoice_number();
        $currency_id = !empty($org->currency_id) ? (int)$org->currency_id : 1;
        $invoice_date = !empty($payment->confirmed_at) ? $payment->confirmed_at : date('Y-m-d H:i:s');
        $payment_amount = number_format((float)$payment->amount_gross, 2, '.', '');
        $period_description = 'Subscription period: ' . date('Y-m-d', strtotime($payment->billing_date_from))
            . ' to ' . date('Y-m-d', strtotime($payment->billing_date_to));

        $invoice_post = array(
            'invoice_number' => $invoice_number,
            'contact_id' => $contact_id,
            'currency_id' => $currency_id,
            'discount_percentage' => 0,
            'reference' => $payment->merchant_reference,
            'status' => 'paid',
            'content_status' => 'active',
            'sub_total' => $payment_amount,
            'discount_total' => 0,
            'vat_amount' => 0,
            'total_amount' => $payment_amount,
            'terms' => $period_description,
            'closing_note' => 'Paid automatically via PayFast subscription.',
            'reminder' => 0,
            'date' => $invoice_date,
            'due_date' => $invoice_date,
            'date_modified' => current_datetime()
        );

        $this->db->insert($this->table_prefix . 'invoices', $invoice_post);
        $invoice_id = (int)$this->db->insert_id();

        if ($invoice_id <= 0) {
            $this->switch_to_main_db($org->account_name);
            return null;
        }

        $item_post = array(
            'invoice_id' => $invoice_id,
            'item_name' => $payment->item_name,
            'description' => $period_description,
            'quantity' => 1,
            'tax' => null,
            'rate' => (float)$payment->amount_gross,
            'total_amount' => (float)$payment->amount_gross
        );
        $this->db->insert($this->table_prefix . 'invoice_items', $item_post);

        $payment_method_id = $this->get_or_create_invoice_payment_method('PayFast');
        $invoice_payment_post = array(
            'invoice_id' => $invoice_id,
            'contact_id' => $contact_id,
            'payment_amount' => (float)$payment->amount_gross,
            'payment_method_id' => $payment_method_id,
            'reference' => $payment->merchant_reference,
            'credit_applied' => null,
            'notification' => 'no',
            'use_credit' => 'no',
            'payment_date' => $invoice_date,
            'date_modified' => current_datetime()
        );
        $this->db->insert($this->table_prefix . 'invoice_payments', $invoice_payment_post);

        $this->switch_to_main_db($org->account_name);

        return $invoice_id;
    }

    protected function find_invoice_id_by_reference($reference)
    {
        $reference = trim((string)$reference);
        if ($reference === '') {
            return null;
        }

        $query = $this->db->select('id')
            ->from($this->table_prefix . 'invoices')
            ->where('reference', $reference)
            ->limit(1)
            ->get();

        $row = $query->row();
        return $row ? (int)$row->id : null;
    }

    protected function get_or_create_subscription_contact($org)
    {
        $email = trim((string)$org->email);
        $company_name = trim((string)$org->company_name);

        if ($email !== '') {
            $query = $this->db->select('id')
                ->from($this->table_prefix . 'contacts')
                ->where('email', $email)
                ->order_by('id', 'ASC')
                ->limit(1)
                ->get();

            $row = $query->row();
            if ($row) {
                return (int)$row->id;
            }
        }

        $contact_type_id = $this->get_contact_type_id('client');
        $contact_post = array(
            'contact_type_id' => $contact_type_id,
            'organisation' => $company_name !== '' ? $company_name : $org->account_name,
            'vat_number' => !empty($org->vat_number) ? $org->vat_number : null,
            'industry_id' => !empty($org->industry_id) ? $org->industry_id : null,
            'company_size_id' => null,
            'first_name' => null,
            'last_name' => null,
            'email' => $email !== '' ? $email : ('billing+' . $org->id . '@' . $this->config->item('domain')),
            'land_line' => !empty($org->telephone) ? $org->telephone : null,
            'mobile' => !empty($org->mobile) ? $org->mobile : null,
            'address' => trim(implode(', ', array_filter(array(
                isset($org->address_line_1) ? $org->address_line_1 : null,
                isset($org->address_line_2) ? $org->address_line_2 : null,
                isset($org->city) ? $org->city : null,
                isset($org->region_state) ? $org->region_state : null,
                isset($org->zip) ? $org->zip : null
            ))))
        );

        $this->db->insert($this->table_prefix . 'contacts', $contact_post);
        return (int)$this->db->insert_id();
    }

    protected function get_contact_type_id($type)
    {
        $query = $this->db->select('id')
            ->from($this->table_prefix . 'contact_types')
            ->where('type', $type)
            ->limit(1)
            ->get();

        $row = $query->row();
        if ($row) {
            return (int)$row->id;
        }

        $this->db->insert($this->table_prefix . 'contact_types', array('type' => $type));
        return (int)$this->db->insert_id();
    }

    protected function get_or_create_invoice_payment_method($method_name)
    {
        $query = $this->db->select('id')
            ->from($this->table_prefix . 'invoice_payment_methods')
            ->where('payment_method', $method_name)
            ->limit(1)
            ->get();

        $row = $query->row();
        if ($row) {
            return (int)$row->id;
        }

        $this->db->insert($this->table_prefix . 'invoice_payment_methods', array(
            'payment_method' => $method_name
        ));

        return (int)$this->db->insert_id();
    }

    protected function next_invoice_number()
    {
        $prefix = 'INV';
        $template = $this->db->select('invoice_number_prefix')
            ->from($this->table_prefix . 'templates')
            ->limit(1)
            ->get()
            ->row();

        if ($template && !empty($template->invoice_number_prefix)) {
            $prefix = trim((string)$template->invoice_number_prefix);
        }

        $row = $this->db->select('invoice_number')
            ->from($this->table_prefix . 'invoices')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $next = 1;
        if ($row && !empty($row->invoice_number) && preg_match('/(\d+)$/', (string)$row->invoice_number, $matches)) {
            $next = ((int)$matches[1]) + 1;
        }

        return strtoupper($prefix) . '-' . str_pad((string)$next, 6, '0', STR_PAD_LEFT);
    }

    protected function build_subscription_summary($org)
    {
        $now = time();
        $paid_until_ts = !empty($org->paid_until) ? strtotime($org->paid_until) : false;
        $grace_end_ts = !empty($org->grace_period_ends_at) ? strtotime($org->grace_period_ends_at) : false;
        $grace_days = $this->payment_settings->get_grace_period_days();
        $status = isset($org->subscription_status) ? $org->subscription_status : 'trial';
        $cancel_at_period_end = !empty($org->cancel_at_period_end);
        $pending_plan_code = !empty($org->pending_plan_code) ? $org->pending_plan_code : null;

        if ($paid_until_ts && !$grace_end_ts) {
            $grace_end_ts = strtotime('+' . $grace_days . ' days', $paid_until_ts);
        }

        $has_paid_access = $paid_until_ts && $paid_until_ts > $now;
        $in_grace_period = !$has_paid_access && $grace_end_ts && $grace_end_ts > $now;

        if ($has_paid_access) {
            $status = $cancel_at_period_end ? 'cancellation_scheduled' : 'active';
        } elseif ($in_grace_period) {
            $status = 'grace_period';
        } elseif ($paid_until_ts && $paid_until_ts <= $now) {
            $status = 'expired';
        }

        $message = 'Subscription payment is available.';
        if ($has_paid_access && $cancel_at_period_end) {
            $message = 'Cancellation is scheduled. Your workspace will keep paid access until the current term ends.';
        } elseif ($has_paid_access && $pending_plan_code) {
            $next_plan = $this->payfast_service->get_plan($pending_plan_code);
            $message = 'Your subscription is active. ' . $next_plan['name'] . ' is scheduled for your next renewal.';
        } elseif ($has_paid_access) {
            $message = 'Your subscription is active. Payment will be available again after the current paid period ends.';
        } elseif ($in_grace_period) {
            $message = 'Your subscription has ended. You are currently in the grace period and can renew at any time.';
        } elseif ($status === 'expired') {
            $message = 'Your subscription and grace period have ended. Renew now to restore full access.';
        }

        return array(
            'status' => $status,
            'grace_period_ends_at' => $grace_end_ts ? date('Y-m-d H:i:s', $grace_end_ts) : null,
            'can_pay' => !$has_paid_access,
            'can_cancel' => (bool)$has_paid_access,
            'can_change_plan' => (bool)$has_paid_access,
            'has_paid_access' => (bool)$has_paid_access,
            'access_message' => $message,
            'plan_change_effective_at' => $has_paid_access && $paid_until_ts ? date('Y-m-d H:i:s', $paid_until_ts) : null
        );
    }

    protected function resolve_current_plan_code($org, $latest_completed_payment = null)
    {
        if (!empty($org->current_plan_code) && $this->payfast_service->has_plan($org->current_plan_code)) {
            return $org->current_plan_code;
        }

        if ($latest_completed_payment && !empty($latest_completed_payment->plan_code) && $this->payfast_service->has_plan($latest_completed_payment->plan_code)) {
            return $latest_completed_payment->plan_code;
        }

        $default_plan = $this->payfast_service->get_plan();
        return isset($default_plan['code']) ? $default_plan['code'] : null;
    }

    protected function log_itn_audit($stage, $context = array())
    {
        $prefix = $this->payfast_service->get_setting('test_mode') ? 'PAYFAST_SANDBOX_ITN' : 'PAYFAST_PROD_ITN';
        log_message('error', $prefix . ' ' . $stage . ' ' . json_encode($context));
    }
}
