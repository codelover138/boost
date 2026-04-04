<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subscription extends CI_Controller
{
    protected $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('userhandler');
        $this->load->library('payfast_service');
        $this->load->model('generic_model');
        $this->table_prefix = $this->config->item('db_table_prefix');
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
        if ($this->regular->request_method() !== 'POST') {
            $this->regular->header_(405);
            $this->regular->respond(['status' => 'ERROR', 'message' => ['POST required']]);
            return;
        }

        $headers = $this->regular->get_request_headers();
        $account_name = $headers['Account-Name'];
        
        $this->load->library('db/switcher', array('account_name' => $account_name));
        $org = $this->switcher->check_sub_status($account_name);

        if($org) {
            $has_paid_access = !empty($org->paid_until) && strtotime($org->paid_until) > time();
            if ($has_paid_access && !empty($org->cancel_at_period_end)) {
                $this->regular->header_(409);
                $this->regular->respond(['status' => 'ERROR', 'message' => ['Cancellation is already scheduled for the end of the current billing period.']]);
                return;
            }

            $token = !empty($org->payfast_token) ? trim((string)$org->payfast_token) : '';
            if ($token === '') {
                $this->switcher->main_db();
                $params = array(
                    'table' => $this->table_prefix . 'subscription_payments',
                    'entity' => 'subscription payment',
                    'where' => array(
                        'organisation_id' => $org->id,
                        'payment_status' => 'complete'
                    ),
                    'order_by' => 'id DESC',
                    'limit' => 1
                );
                $history = array_values((array)$this->generic_model->read($params));
                if (!empty($history) && !empty($history[0]->payfast_token)) {
                    $token = trim((string)$history[0]->payfast_token);
                }
            }

            if ($token === '') {
                $this->regular->header_(409);
                $this->regular->respond(['status' => 'ERROR', 'message' => ['No PayFast subscription token is stored for this workspace. Please use the billing page after a successful payment has saved the recurring token.']]);
                return;
            }

            $cancel_result = $this->payfast_service->cancel_subscription($token);
            if (empty($cancel_result['bool'])) {
                $this->regular->header_(502);
                $this->regular->respond(['status' => 'ERROR', 'message' => ['PayFast cancellation failed. Your local subscription was not changed.']]);
                return;
            }

            $update_data = [
                'pending_plan_code' => null,
                'payfast_token' => $token,
                'cancel_at_period_end' => $has_paid_access ? 1 : 0,
                'cancellation_requested_at' => date('Y-m-d H:i:s')
            ];

            if ($has_paid_access) {
                $update_data['subscription_status'] = 'active';
                $message = 'Your PayFast subscription has been cancelled. Access will continue until the current paid period ends.';
            } else {
                $update_data['subscription_status'] = 'cancelled';
                $message = 'Your PayFast subscription has been cancelled and the workspace is now inactive except for billing access.';
            }

            $params = array(
                'table' => $this->table_prefix . 'organisations',
                'entity' => 'organisation'
            );

            $this->generic_model->update($params, $org->id, $update_data);
            
            $this->regular->respond(['status' => 'OK', 'message' => [$message]]);
        } else {
             $this->regular->header_(404);
             $this->regular->respond(['status' => 'ERROR', 'message' => ['Organisation not found']]);
        }
    }
}
