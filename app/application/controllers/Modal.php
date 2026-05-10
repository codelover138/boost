<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modal extends CI_Controller{
	
	public $dataset_string;
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('curl');
		$this->load->helper('url');	
		$this->load->helper('api_base');
		
		// check if a dataset has been sent and if so build a string of datasets to add to the form element
		// this enables redirection after form submission to be specified from the link that oprnrd the modal
		if($this->input->get('form_dataset')){
			foreach($this->input->get('form_dataset') as $form_dataset_key => $form_dataset_string){
				$this->dataset_string .=  $form_dataset_key.'="'.$form_dataset_string.'" ';
			}
		}
    }
	
    public function index()
    {
       
    }
	
	public function tax()
    {
		
		$data['form_dataset'] =  $this->dataset_string;
		
		if($this->uri->segment(3)=='add'){

			if(is_numeric($this->uri->segment(4))){
				$data['activeElementId'] = $this->uri->segment(4);
			}
			
			$this->load->view('global_modals/add_tax',$data);
		}
		
	}
	
	public function item()
    {
		
		$data['form_dataset'] =  $this->dataset_string;
		
		if($this->uri->segment(3)=='add'){
			$this->load->view('global_modals/add_sales_item',$data);
		}
		
	}
	
	public function contact()
    {	
			
		//if last segment is payment load the payment modal
		if($this->uri->segment(3)=='add'){
			
			/// requested piggy back which gets data
			$send_data = array(
				'piggyback'=> array('industries','company_sizes')
			);
			
			//request data from api using CURL
			$data['request'] = $this->curl->api_call('GET', 'contact_types',$send_data);
			
			if($this->uri->segment(4)){
				$data['type'] = $this->uri->segment(4);
			}else{
				$data['type'] = 'client';
			}
			
			if($data['type'] == 'client'){
				$data['input'] = 'contact_id'; 
			}else{
				$data['input'] = $data['type'].'_id';
			}
		
			$this->load->view('global_modals/add_contact',$data);
		} 

    }
	/* change to have expense addition as its own page and no longer a modal
	public function expense()
    {	
			
		//if last segment is payment load the payment modal
		if($this->uri->segment(3)=='add'){
			
			/// requested piggy back which gets data
			$send_data = array(
				'piggyback'=> array('taxes','contact_types','expenses_categories','organisations','currencies')
			);
			
			//request data from api using CURL
			$data['request'] = $this->curl->api_call('GET', 'contacts/organisation',$send_data);
			
			//reformat contacts into their types
			
			foreach($data['request']['data'] as $contact_id => $contact_data){
				$contact_type_id = $contact_data['contact_type_id'];
				$data['request']['contact_types'][$contact_type_id][] = $contact_data;
			}
			
			if($this->uri->segment(4)){
				$data['type'] = $this->uri->segment(4);
			}
		
			$this->load->view('global_modals/add_expense',$data);
		} 

    }*/
	
	public function notice()
    {	
		if($this->input->get('modalHeading')){
			$data['heading'] = $this->input->get('modalHeading');
		}
		
		if($this->input->get('modalBody')){
			$data['body'] = $this->input->get('modalBody');
		}
		
		$this->load->view('global_modals/notice',$data);
    }
	
	public function beta_notice()
    {			
		$this->load->view('global_modals/beta_notice');
    }
	
	public function error()
    {	
		if($this->input->get('modalHeading')){
			$data['heading'] = $this->input->get('modalHeading');
		}
		
		if($this->input->get('modalBody')){
			$data['body'] = $this->input->get('modalBody');
		}
		
		$this->load->view('global_modals/error',$data);
    }
	
	public function login()
    {	
				
		$this->load->view('global_modals/login');
    }
	

}
