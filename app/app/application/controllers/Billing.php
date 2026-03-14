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
        $response = $this->curl->rest_api_call('POST', 'billing/initiate', array());

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
}
