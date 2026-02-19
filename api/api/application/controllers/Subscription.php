<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subscription extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('userhandler');
        // Standard user check
        $this->userhandler->confirm_account(); 
    }

    public function status()
    {
        $headers = $this->regular->get_request_headers();
        $account_name = $headers['Account-Name'];
        
        $this->load->library('db/switcher', array('account_name' => $account_name));
        $status_data = $this->switcher->check_sub_status($account_name);

        if($status_data) {
             # Calculate details
             $trial_ends = strtotime($status_data->trial_ends_at);
             $is_trial = $status_data->subscription_status == 'trial';
             $days_left = ceil(($trial_ends - time()) / 86400);

             $response = [
                 'status' => $status_data->subscription_status,
                 'trial_ends_at' => $status_data->trial_ends_at,
                 'trial_days_left' => $days_left > 0 ? $days_left : 0,
                 'is_blocked' => $status_data->is_manual_blocked,
                 'paid_until' => $status_data->paid_until
             ];

             $this->regular->respond(['status' => 'OK', 'data' => $response]);
        } else {
             $this->regular->header_(404);
             $this->regular->respond(['status' => 'ERROR', 'message' => ['Account not found']]);
        }
    }

    public function reactivate()
    {
        // Mock method to reactivate subscription (e.g. after successful payment)
        $headers = $this->regular->get_request_headers();
        $account_name = $headers['Account-Name'];
        
        // In a real app, this would verify payment token from Stripe/PayPal
        
        $this->load->model('generic_model');
        // We need to find the ID of the organisation. check_sub_status returns the object
        $this->load->library('db/switcher', array('account_name' => $account_name));
        $org = $this->switcher->check_sub_status($account_name);

        if($org) {
            $update_data = [
                'subscription_status' => 'active',
                'paid_until' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'grace_period_ends_at' => null
            ];

            $params = array(
                'table' => 'boost_organisations',
                'entity' => 'organisation'
            );

            $this->generic_model->update($params, $org->id, $update_data);
            
            $this->regular->respond(['status' => 'OK', 'message' => ['Subscription reactivated successfully']]);
        } else {
             $this->regular->header_(404);
             $this->regular->respond(['status' => 'ERROR', 'message' => ['Organisation not found']]);
        }
    }
    
    public function cancel()
    {
         $headers = $this->regular->get_request_headers();
        $account_name = $headers['Account-Name'];
        
        $this->load->library('db/switcher', array('account_name' => $account_name));
        $org = $this->switcher->check_sub_status($account_name);

        if($org) {
            $update_data = [
                'subscription_status' => 'cancelled'
                // We do NOT clear paid_until, so they keep access until then
            ];

            $params = array(
                'table' => 'boost_organisations',
                'entity' => 'organisation'
            );

            $this->generic_model->update($params, $org->id, $update_data);
            
            $this->regular->respond(['status' => 'OK', 'message' => ['Subscription cancelled. access remains until paid period ends.']]);
        } else {
             $this->regular->header_(404);
             $this->regular->respond(['status' => 'ERROR', 'message' => ['Organisation not found']]);
        }
    }
}
