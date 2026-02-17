<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller{ 
	
	public $dataset_string;
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('curl');
		$this->load->library('excel');
		$this->load->helper('url');	
		$this->load->helper('api_base');
		
		// check if a dataset has been sent and if so build a string of datasets to add to the form element
		if($this->input->get('form_dataset')){
			foreach($this->input->get('form_dataset') as $form_dataset_key => $form_dataset_string){
				$this->dataset_string .=  $form_dataset_key.'="'.$form_dataset_string.'" ';
			}
		}
    }
	
	public function _remap($method)
	{
		if (in_array($method,get_class_methods($this))){
			$this->$method();
		}else{
			$this->index();
		}
	}

    public function business_report($month='',$year='')
    {		
				
		$order_by =  'id';
		$direction =  'desc';
		$results_per_page =  $this->config->item('pagination')['results_per_page'];
		$starting_record =  '0';
		$send_data = array();
		$data['request'] = $this->curl->api_call('GET', 'invoices/'.$order_by.'/'.$direction.'/'.$results_per_page.'/'.$starting_record, $send_data);
       
		$data['activity']['heading'] = 'Business Report';
		if(empty($month)) $month = $this->input->get('month');
       if(empty($year))  $year = $this->input->get('year');
		$query=!empty($month) ? $month :$year;
		$result = $this->curl->rest_api_call('GET', 'api_rest/reports/'.$query);
		
		# this define page data
		$data['currency']= $result['currency'];
		$data['month']= $month;
		$data['year']= $year;
		$data['get_month_year_revenue']= $result['get_month_year_revenue'];
		$data['get_month_year_expense']= $result['get_month_year_expense'];
		$data['get_month_year_client_revenue']= $result['get_month_year_client_revenue'];
		$data['get_month_year_client_expense']= $result['get_month_year_client_expense'];
		$data['page']['title'] = 'Reports';
		$data['page']['heading'] = 'Reports';
		$data['page']['main_view'] = 'reports/business_report';
	//	$data['page']['header_button_view'] = 'expenses/sub_header_buttons/list';
		$this->load->view('content',$data);
    }
    
     public function download_excel($month=null,$year=null) {
        if(empty($month)) $month = $this->input->get('month');
        if(empty($year))  $year = $this->input->get('year');
		$query=!empty($month) ? $month :$year;
		
		$result = $this->curl->rest_api_call('GET', 'api_rest/reports/'.$query);
       	$data['currency']= $result['currency'];
		$data['get_month_year_revenue']= $result['get_month_year_revenue'];
		$data['get_month_year_expense']= $result['get_month_year_expense'];
		$data['get_month_year_client_revenue']= $result['get_month_year_client_revenue'];
		$data['get_month_year_client_expense']= $result['get_month_year_client_expense'];
	
        $this->excel->generate_excel($data);
    }
	
	
	 public function download_pdf() {
        if(empty($month)) $month = $this->input->get('month');
        if(empty($year))  $year = $this->input->get('year');
		$query=!empty($month) ? $month :$year;
		
		$result = $this->curl->rest_api_call('GET', 'api_rest/reports/'.$query);
       	$data['currency']= $result['currency'];
		$data['get_month_year_revenue']= $result['get_month_year_revenue'];
		$data['get_month_year_expense']= $result['get_month_year_expense'];
		$data['get_month_year_client_revenue']= $result['get_month_year_client_revenue'];
		$data['get_month_year_client_expense']=$result['get_month_year_client_expense'];
		
        // Load the view and save HTML content
        $html = $this->load->view('reports/report_pdf_view', $data, TRUE);

        // Load the PDF library and create PDF
        $this->load->library('pdf');
        $this->pdf->create($html, 'report.pdf');
    }


}