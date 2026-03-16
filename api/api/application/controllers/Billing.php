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

        $response = array(
            'plan' => $this->payfast_service->get_plan(),
            'plans' => $this->payfast_service->get_plans(),
            'subscription' => array(
                'status' => $org->subscription_status,
                'trial_ends_at' => $org->trial_ends_at,
                'paid_until' => $org->paid_until,
                'grace_period_ends_at' => $org->grace_period_ends_at,
                'is_manual_blocked' => (int)$org->is_manual_blocked
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
        $user_data = $this->userhandler->determine_user();
        $requested_plan_code = isset($request['plan_code']) ? trim((string)$request['plan_code']) : '';

        if ($requested_plan_code !== '' && !$this->payfast_service->has_plan($requested_plan_code)) {
            $this->regular->header_(400);
            $this->regular->respond(array('status' => 'ERROR', 'message' => array('Invalid subscription plan selected')));
            return;
        }

        $plan = $this->payfast_service->get_plan($requested_plan_code);

        if (!$this->config->item('payfast_merchant_id') || !$this->config->item('payfast_merchant_key')) {
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
        $billing_end = date('Y-m-d H:i:s', strtotime('+' . (int)$plan['cycle_days'] . ' days', strtotime($billing_start)));

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

    public function itn()
    {
        $post = $_POST;
        if (empty($post)) {
            $post = $this->regular->decode('POST');
        }

        if (empty($post) || !isset($post['m_payment_id'])) {
            $this->output_itn_response(400, 'Invalid ITN payload');
            return;
        }

        $payment = $this->get_payment_by_id((int)$post['m_payment_id']);
        if (!$payment) {
            $this->output_itn_response(404, 'Payment not found');
            return;
        }

        $org = $this->get_organisation_by_id($payment->organisation_id);
        if (!$org) {
            $this->output_itn_response(404, 'Organisation not found');
            return;
        }

        $existing_status = $payment->payment_status;

        $remote_ip = $this->input->ip_address();
        $ip_valid = $this->payfast_service->validate_source_ip($remote_ip);
        $signature_valid = $this->payfast_service->validate_signature($post);
        $amount_valid = isset($post['amount_gross']) &&
            number_format((float)$post['amount_gross'], 2, '.', '') === number_format((float)$payment->amount_gross, 2, '.', '');
        $merchant_valid = isset($post['merchant_id']) &&
            (string)$post['merchant_id'] === (string)$this->config->item('payfast_merchant_id');
        $reference_valid = isset($post['custom_str1']) &&
            (string)$post['custom_str1'] === (string)$payment->merchant_reference;

        $confirm = $this->payfast_service->confirm_itn($post);
        $normalised_status = $this->payfast_service->normalise_payment_status(isset($post['payment_status']) ? $post['payment_status'] : 'pending');

        $validation_errors = array();
        if (!$ip_valid) {
            $validation_errors[] = 'Invalid source IP';
        }
        if (!$signature_valid && !$this->config->item('payfast_test_mode')) {
            $validation_errors[] = 'Invalid signature';
        }
        if (!$amount_valid) {
            $validation_errors[] = 'Amount mismatch';
        }
        if (!$merchant_valid) {
            $validation_errors[] = 'Merchant mismatch';
        }
        if (!$reference_valid) {
            $validation_errors[] = 'Reference mismatch';
        }
        if (!$confirm['bool']) {
            $validation_errors[] = 'PayFast confirmation failed: ' . $confirm['message'];
        }

        $update_post = array(
            'payment_status' => !empty($validation_errors) ? 'invalid' : $normalised_status,
            'payfast_payment_id' => isset($post['pf_payment_id']) ? $post['pf_payment_id'] : null,
            'payfast_reference' => isset($post['payment_id']) ? $post['payment_id'] : null,
            'payment_method' => isset($post['payment_method']) ? $post['payment_method'] : null,
            'amount_fee' => isset($post['amount_fee']) ? $post['amount_fee'] : null,
            'amount_net' => isset($post['amount_net']) ? $post['amount_net'] : null,
            'itn_verified' => empty($validation_errors) ? 1 : 0,
            'raw_itn_data' => json_encode($post),
            'failure_reason' => empty($validation_errors) ? null : implode('; ', $validation_errors),
            'itn_received_at' => date('Y-m-d H:i:s')
        );

        if (empty($validation_errors) && $normalised_status === 'complete') {
            $update_post['confirmed_at'] = date('Y-m-d H:i:s');
        }

        $params = array(
            'table' => $this->table_prefix . 'subscription_payments',
            'entity' => 'subscription payment'
        );
        $this->generic_model->update($params, $payment->id, $update_post, false);

        if (!empty($validation_errors)) {
            $this->create_event($org->id, $payment->id, 'payment_invalid', 'PayFast ITN rejected', array(
                'errors' => $validation_errors,
                'payload' => $post
            ));
            $this->send_payment_failure_email($org, $payment, implode('; ', $validation_errors));
            $this->output_itn_response(200, 'ITN rejected');
            return;
        }

        if ($normalised_status === 'complete') {
            if ($existing_status !== 'complete') {
                $this->activate_subscription($org, $payment);
                $this->create_event($org->id, $payment->id, 'payment_completed', 'PayFast payment confirmed', $post);
                $this->send_payment_success_email($org, $payment);
            }
            $this->output_itn_response(200, 'Payment processed');
            return;
        }

        $this->mark_payment_failed($org, $payment, $normalised_status, $post);
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

    protected function activate_subscription($org, $payment)
    {
        $this->switch_to_main_db($org->account_name);
        $params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'organisation'
        );

        $this->generic_model->update($params, $org->id, array(
            'subscription_status' => 'active',
            'paid_until' => $payment->billing_date_to,
            'grace_period_ends_at' => null
        ), false);
    }

    protected function mark_payment_failed($org, $payment, $status, $payload)
    {
        $grace_days = (int)$this->config->item('payfast_grace_period_days');
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
}
