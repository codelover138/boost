<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billing extends CI_Controller
{
    protected $user_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->library('curl');
        $this->load->helper('url');
        $this->load->helper('api_base');

        $user_response = $this->curl->api_call('GET', 'me');
        if (isset($user_response['bool']) && $user_response['bool'] == true) {
            $this->user_data = (array)$user_response['data'];
        }
    }

    public function index()
    {
        $billing_response = $this->curl->rest_api_call('GET', 'billing/status');
        if (!isset($billing_response['status']) || $billing_response['status'] !== 'OK') {
            $subscription_response = $this->curl->rest_api_call('GET', 'subscription/status');
            $billing_response = array(
                'status' => isset($subscription_response['status']) ? $subscription_response['status'] : 'ERROR',
                'data' => array(
                    'plan' => array(
                        'name' => 'Boost Monthly Subscription',
                        'description' => 'Pay securely with PayFast and restore access after payment confirmation.',
                        'amount' => '0.00'
                    ),
                    'subscription' => isset($subscription_response['data']) ? $subscription_response['data'] : array(),
                    'latest_payment' => null,
                    'history' => array()
                ),
                'message' => array('Billing API endpoint is not available yet. Showing subscription status only.')
            );
        }

        $data['page']['title'] = 'Billing';
        $data['page']['heading'] = 'Billing';
        $data['page']['main_view'] = 'billing/index';
        $data['request'] = array(
            'user_data' => $this->user_data,
            'billing' => $billing_response
        );
        $data['billing'] = $billing_response;
        $data['flash_type'] = $this->session->flashdata('billing_flash_type');
        $data['flash_message'] = $this->session->flashdata('billing_flash_message');

        $this->load->view('content', $data);
    }

    public function pay()
    {
        $plan_code = trim((string)$this->input->post('plan_code', true));
        if ($plan_code === '') {
            $plan_code = trim((string)$this->input->get('plan_code', true));
        }

        $payload = array();
        if ($plan_code !== '') {
            $payload['plan_code'] = $plan_code;
        }

        $response = $this->curl->rest_api_call('POST', 'billing/initiate', $payload);

        if (!isset($response['status']) || $response['status'] !== 'OK') {
            $message = 'Unable to start PayFast payment.';
            if (isset($response['message']) && is_array($response['message'])) {
                $message = implode(' ', $response['message']);
            } elseif (isset($response['response'])) {
                $message = 'Billing API returned an unexpected response.';
            }

            $this->session->set_flashdata('billing_flash_type', 'danger');
            $this->session->set_flashdata('billing_flash_message', $message);
            redirect('billing');
            return;
        }

        $data['payment_url'] = $response['data']['payment_url'];
        $data['fields'] = $response['data']['fields'];
        $this->load->view('billing/redirect', $data);
    }

    public function complete($result = 'success', $payment_id = null)
    {
        if ($result === 'success') {
            $this->session->set_flashdata(
                'billing_flash_message',
                'PayFast returned successfully. We are confirming the payment and will restore access as soon as PayFast ITN is validated.'
            );
            $this->session->set_flashdata('billing_flash_type', 'success');
        }
        elseif ($result === 'cancel') {
            $this->session->set_flashdata('billing_flash_message', 'The PayFast payment was cancelled before completion.');
            $this->session->set_flashdata('billing_flash_type', 'warning');
        }
        else {
            $this->session->set_flashdata('billing_flash_message', 'Payment status updated.');
            $this->session->set_flashdata('billing_flash_type', 'info');
        }

        redirect('billing');
    }

    public function change_plan()
    {
        $plan_code = trim((string)$this->input->post('plan_code', true));
        if ($plan_code === '') {
            $this->session->set_flashdata('billing_flash_type', 'danger');
            $this->session->set_flashdata('billing_flash_message', 'Please choose a billing cycle first.');
            redirect('billing');
            return;
        }

        $response = $this->curl->rest_api_call('POST', 'billing/change_plan', array(
            'plan_code' => $plan_code
        ));

        $flash_type = isset($response['status']) && $response['status'] === 'OK' ? 'success' : 'danger';
        $message = isset($response['status']) && $response['status'] === 'OK'
            ? 'Your plan change has been scheduled.'
            : 'Unable to update your billing cycle.';

        if (isset($response['message']) && is_array($response['message']) && !empty($response['message'])) {
            $message = implode(' ', $response['message']);
        }

        $this->session->set_flashdata('billing_flash_type', $flash_type);
        $this->session->set_flashdata('billing_flash_message', $message);
        redirect('billing');
    }

    public function cancel_subscription()
    {
        $response = $this->curl->rest_api_call('POST', 'billing/cancel');

        $flash_type = isset($response['status']) && $response['status'] === 'OK' ? 'success' : 'danger';
        $message = isset($response['status']) && $response['status'] === 'OK'
            ? 'Your subscription has been updated.'
            : 'Unable to cancel your subscription.';

        if (isset($response['message']) && is_array($response['message']) && !empty($response['message'])) {
            $message = implode(' ', $response['message']);
        }

        $this->session->set_flashdata('billing_flash_type', $flash_type);
        $this->session->set_flashdata('billing_flash_message', $message);
        redirect('billing');
    }

    public function invoice($payment_id = null)
    {
        if (empty($payment_id) || !is_numeric($payment_id)) {
            $this->session->set_flashdata('billing_flash_type', 'danger');
            $this->session->set_flashdata('billing_flash_message', 'Invalid payment invoice requested.');
            redirect('billing');
            return;
        }

        $response = $this->curl->rest_api_call('GET', 'billing/invoice/' . (int)$payment_id);
        if (!isset($response['status']) || $response['status'] !== 'OK' || empty($response['data']['payment'])) {
            $message = 'Unable to download payment invoice.';
            if (isset($response['message']) && is_array($response['message'])) {
                $message = implode(' ', $response['message']);
            }

            $this->session->set_flashdata('billing_flash_type', 'danger');
            $this->session->set_flashdata('billing_flash_message', $message);
            redirect('billing');
            return;
        }

        $data = $response['data'];
        $html = $this->load->view('billing/invoice_pdf', $data, true);

        $filename = 'payment-invoice-' . (!empty($data['payment']['merchant_reference']) ? $data['payment']['merchant_reference'] : $payment_id) . '.pdf';
        $this->load->library('pdf');
        $this->pdf->create($html, $filename);
    }
}
