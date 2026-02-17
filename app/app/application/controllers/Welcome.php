<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		$this->load->library('curl');
		$this->load->helper('url');	
		$this->load->helper('api_base');
		
		// check if a dataset has been sent and if so build a string of datasets to add to the form element
		if($this->input->get('form_dataset')){
			foreach($this->input->get('form_dataset') as $form_dataset_key => $form_dataset_string){
				$this->dataset_string .=  $form_dataset_key.'="'.$form_dataset_string.'" ';
			}
		}
    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function dashboard()
    {	$order_by =  'id';
		$direction =  'desc';
		$results_per_page =  $this->config->item('pagination')['results_per_page'];
		$starting_record =  '0';
		$send_data = array();
		$data['request'] = $this->curl->api_call('GET', 'invoices/'.$order_by.'/'.$direction.'/'.$results_per_page.'/'.$starting_record, $send_data);

		$result = $this->curl->rest_api_call('GET', 'api_rest/dashboard');
		
		$currentYear = date('Y'); 
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $months = [];
        foreach ($monthNames as $key=>$val) {
        $months[] = $val. ' ' . $currentYear; 
        }
		$data['currency']= $result['currency'];
		$data['total_sales']= 0;
		$data['month_wise_sales']= $result['total_sales'];
		$data['get_current_month_revenue']= $result['get_current_month_revenue'];
		$data['total_revenue']= $result['total_revenue'];
		$data['total_invoice']= $result['total_invoice'];
		$data['months']= json_encode($months);
		$data['get_unpaid_invoices_count']= $result['get_unpaid_invoices_count'];
		$data['get_top_organization']= $result['get_top_organization'];
		$data['active_client']= $result['active_client'];
		$data['get_monthwise_sales']= $result['get_monthwise_sales'];
		$data['get_monthwise_expenses']= $result['get_monthwise_expenses'];
		$data['get_monthwise_profit']= $this->subtract_arrays( $result['get_monthwise_sales'],$result['get_monthwise_expenses']);
		$data['get_average_payment_days']= $result['get_average_payment_days'];
		$data['get_current_year_sales_growth']= $result['get_current_year_sales_growth'];
	//	$data['activity']['heading'] = 'Dashboard';
//		$data['page']['header_button_view'] = 'expenses/sub_header_buttons/list';
		$data['page']['title'] = 'Dashboard';
		$data['page']['heading'] = 'Dashboard';
		$data['page']['main_view'] = 'reports/dashboard';
		$this->load->view('content',$data);
    }
	

	function subtract_arrays($array1, $array2)
{
    // Ensure both arrays are the same length
    $length = count($array1);
    $result = [];

    for ($i = 0; $i < $length; $i++) {
        $result[] = $array1[$i] - $array2[$i];
    }

    return $result;
}

	
}