<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Api_rest extends REST_Controller {

    private $api_keys = [
        '6cc9ca29d7ef010a6164f0c5e8fe3768' => ['level' => 1, 'ignore_limits' => TRUE]
    ];

    function __construct()
    {
        
        parent::__construct();
        $this->CI =& get_instance(); 
    }

    private function validate_api_key()
    {
        $headers = $this->input->request_headers();
        $api_key = $headers['X-Api-Key'] ?? $headers['X-API-KEY'] ?? null;
        if (isset($this->api_keys[$api_key]) && isset($headers['Account-Name'])) {
            $this->CI->load->library('db/switcher', array('account_name' => $headers['Account-Name']));
            $switch = $this->CI->switcher->account_db();
            if ($switch){
                return $this->api_keys[$api_key];
            } else{
        $this->response(['status' => FALSE, 'error' => 'Database not present'], REST_Controller::HTTP_NOT_FOUND);
        exit; 
            }
        }
        $this->response(['status' => FALSE, 'error' => 'Invalid API key'], REST_Controller::HTTP_UNAUTHORIZED);
        exit;
    }


    public function dashboard_info_get()
    {
        // Hardcoded user data

        $this->validate_api_key();
        $this->CI->load->model('Dashboard_model');
        $currency = $this->CI->Dashboard_model->get_currency_details();
        $total_revenue = $this->CI->Dashboard_model->get_total_revenue();
        $total_sales = $this->CI->Dashboard_model->get_monthwise_revenue();
        $total_invoice = $this->CI->Dashboard_model->get_invoice_count();
        $get_current_month_revenue = $this->CI->Dashboard_model->get_current_month_revenue();
        $get_unpaid_invoices_count = $this->CI->Dashboard_model->get_unpaid_invoices_count();
        $get_top_organization = $this->CI->Dashboard_model->get_top_organization();
        $get_average_payment_days = $this->CI->Dashboard_model->get_average_payment_days();
        $get_current_year_sales_growth = $this->CI->Dashboard_model->get_current_year_sales_growth();
        $get_monthwise_sales = $this->CI->Dashboard_model->get_monthwise_sales();
        $get_monthwise_expenses = $this->CI->Dashboard_model->get_monthwise_expenses();
        $active_client = $this->CI->Dashboard_model->count_contacts_by_type(1);
       
        $response_data = [
            'currency' => $currency,
            'total_revenue' => $total_revenue,
            'get_top_organization' => $get_top_organization,
            'total_invoice' => $total_invoice,
            'get_unpaid_invoices_count' => $get_unpaid_invoices_count,
            'get_current_month_revenue' => $get_current_month_revenue,
            'get_average_payment_days' => $get_average_payment_days,
            'get_monthwise_sales' => $get_monthwise_sales,
            'get_monthwise_expenses' => $get_monthwise_expenses,
            'get_current_year_sales_growth' => $get_current_year_sales_growth,
            'total_sales' => !empty($total_sales) && is_array($total_sales)  ? $total_sales: array('month'=>'[""]','value'=>'[0]') ,
            'active_client' => $active_client,
        ];
        
        if (!empty($response_data)) {
            $this->response($response_data, REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => FALSE, 'message' => 'Data not found'], REST_Controller::HTTP_NOT_FOUND);
        }
     }
    public function report_info_get( $date_input=null)
    {
        $this->validate_api_key();
        $this->CI->load->model('Dashboard_model');
        if(empty( $date_input)) $date_input = $this->get('date_input');
        $currency = $this->CI->Dashboard_model->get_currency_details();
        $get_month_year_revenue = $this->CI->Dashboard_model->get_month_year_revenue($date_input);
        $get_month_year_expense = $this->CI->Dashboard_model->get_month_year_expense($date_input);
        $get_month_year_client_revenue = $this->CI->Dashboard_model->get_month_year_client_revenue($date_input);
        $get_month_year_client_expense = $this->CI->Dashboard_model->get_month_year_client_expense($date_input);
        log('error',$date_input);
        log('error','test');
       
        $response_data = [
            'currency' => $currency,
            'get_month_year_revenue' => $get_month_year_revenue,
            'get_month_year_expense' => $get_month_year_expense,
            'get_month_year_client_revenue' => $get_month_year_client_revenue,
            'get_month_year_client_expense' => $get_month_year_client_expense,
        ];
        
        if (!empty($response_data)) {
            $this->response($response_data, REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => FALSE, 'message' => 'Data not found'], REST_Controller::HTTP_NOT_FOUND);
        }
     }

}