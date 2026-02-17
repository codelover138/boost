<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller{ 

	public function __construct()
    {
        parent::__construct();
		$this->load->library('curl');
		$this->load->helper('url');	
		$this->load->helper('api_base');
    }

   public function index()
	{
	   
		if(!$this->uri->segment(2)){
			show_404();
		}else{
			$encrypted_link = $this->uri->segment(2);
		}
		
		$send_data = array(
            'piggyback'=> array('organizations','templates','theme_settings','currencies','statement_url',
				array(
					'resource'=>'contacts/organisation',
					'where'=>array('contact_type_id'=>1)
				 )
			)
        );
		
		//Sent statement date filter
		if(isset($_REQUEST['filters'])){
			$send_data['filters2'] = $_REQUEST['filters'];
		}
				
		$data['request'] = $this->curl->insecure_api_call('GET', 'documents/'.$encrypted_link,$send_data);

		//print_r($data);
		
		if($data['request']['type'] == 'invoices'){
			$data['page']['title'] = 'View Invoice';
			$data['page']['heading'] = 'View Invoice';
			$data['page']['main_view'] = 'documents/invoice';
			
		}elseif($data['request']['type'] == 'credit_notes'){
			$data['page']['title'] = 'View Credit Note';
			$data['page']['heading'] = 'View Credit Note';
			$data['page']['main_view'] = 'documents/credit_note';
			
		}elseif($data['request']['type'] == 'estimates'){
			$data['page']['title'] = 'View Estimate';
			$data['page']['heading'] = 'View Estimate';
			$data['page']['main_view'] = 'documents/estimate';
			
		}elseif($data['request']['type'] == 'statements'){
			$data['page']['title'] = 'View Statement';
			$data['page']['heading'] = 'View Statement';
			$data['page']['main_view'] = 'documents/statement';
			
		}

		//var_dump($data);
		$this->load->view('document_content',$data);
	}

}
