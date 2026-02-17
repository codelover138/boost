<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Snippets extends CI_Controller{ 
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('curl');
		$this->load->helper('url');	
		$this->load->helper('api_base');
    }

    public function index()
    {

    }
	
	public function taxes_select(){
				
		//request data from api using CURL
		$data = $this->curl->api_call('GET', 'taxes');	
		
		if($this->input->get('current_id')){
			$tax_data['current_tax_id'] = $this->input->get('current_id');
		}else{
			$tax_data['current_tax_id'] = 0;
		}
		
		if($this->input->get('item_id')){
			$tax_data['data']['id'] = $this->input->get('item_id');
		}else{
			$tax_data['data']['id'] = 0;
		}
		
		$tax_data['taxes'] = $data['data'];

		$this->load->view('global_snippets/select_tax',$tax_data);  
	}
	
	public function contacts_select(){
		
		if($this->input->get('current_id')){
			$contact_data['current_id'] = $this->input->get('current_id');
		}else{
			$contact_data['current_id'] = 0;
		}
		
		//echo 'seg='.$this->uri->segment(3);
		//exit;
		
		if($this->uri->segment(3)){
			$this_contact_type = $this->uri->segment(3);
		}else{
			$this_contact_type = 1;
		}
		
		$send_data = array(
            'piggyback'=> array(
				array(
					'resource'=>'contacts/organisation',
					'where'=>array('contact_type_id'=>$this_contact_type)
				 )
			)
        );
		
		//request data from api using CURL
		$data = $this->curl->api_call('GET', 'contact_types',$send_data);		
		//$contact_data['contacts'] = $data['data'];
		$contact_data['contacts'] = $data['piggyback']['contacts/organisation'];
		
		//var_dump($contact_data);

		$this->load->view('global_snippets/select_contact',$contact_data);  
	}
	
	public function item()
    {
		//if the 3rd secment is "ADD" then fetch the html to be added and display it
		if($this->uri->segment(3)=='add'){
			
			$curl_data = $this->curl->api_call('GET', 'taxes');
			$data['request']['piggyback']['taxes'] = $curl_data['data'];
			
			//get the  item count and send this as data or send zero if none
			if($this->uri->segment(4)){
				$data['current_item_count'] = $this->uri->segment(4);
			}else{
				$data['current_item_count'] = 0;
			}
			
			$this->load->view('global_snippets/item_row',$data);
		}
    }
	
	public function activities($category = NULL, $id = NULL)
    {
		
		$uri_data = array('activities');
		array_push($uri_data,$category,$id);
		$api_uri = implode('/',$uri_data);
		
		$send_data = $this->input->get();
		
		$curl_data = $this->curl->api_call('GET', $api_uri,$send_data);
			
		$this->load->view('global_snippets/activities',$curl_data['request']);

    }
	

}
