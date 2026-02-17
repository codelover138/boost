<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller{ 
	
	public $dataset_string;
	
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
	
	public function _remap($method)
	{
	  
		if (in_array($method,get_class_methods($this))){
			$this->$method();
		}else{
			$this->organisation();
		}
	}

	public function organisation()
    {
          // var_dump('tes1');
       // die();
		//this define page data
		$data['page']['title'] = 'Settings | Business Details';
		$data['page']['heading'] = 'Business Details';
		$data['page']['main_view'] = 'settings/organisation';
		$data['page']['header_button_view'] = 'settings/sub_header_buttons/list';
		
		$send_data = array(
			'piggyback'=> array('countries','industries','currencies','timezones')
		);

		$data['request'] = $this->curl->api_call('GET', 'organisations',$send_data);

		//load content into view
		$this->load->view('content',$data);
	}
	
	public function taxes()
    {
		//this define page data
		$data['page']['title'] = 'Settings | Taxes';
		$data['page']['heading'] = 'Taxes';
		$data['page']['main_view'] = 'settings/taxes';
		$data['page']['header_button_view'] = 'settings/sub_header_buttons/list';
		
		$data['request'] = $this->curl->api_call('GET', 'taxes/tax_name');
		
		//load content into view
		$this->load->view('content',$data);
	}
	
	public function theme()
    {
		//this define page data
		$data['page']['title'] = 'Settings | Theme and Logo';
		$data['page']['heading'] = 'Theme and Logo';
		$data['page']['main_view'] = 'settings/theme';
		$data['page']['header_button_view'] = 'settings/sub_header_buttons/list';
		
		$send_data['piggyback'] = array('themes');

		$data['request'] = $this->curl->api_call('GET', 'theme_settings',$send_data);
		
		$array_data = array_values($data['request']['data'])[0];
		$image_path = $array_data['image_string'];
		$image_type = pathinfo($image_path, PATHINFO_EXTENSION);
		
		if(isset($image_path) && !empty($image_path)){
			$image_data = file_get_contents($image_path);
			$data['request']['data'][0]['logo_base64'] = 'data:image/' . $image_type . ';base64,' . base64_encode($image_data);
		}
		//load content into view
		$this->load->view('content',$data);
	}
	
	public function templates()
    {
		//this define page data
		$data['page']['title'] = 'Settings | Templates';
		$data['page']['heading'] = 'Templates';
		$data['page']['main_view'] = 'settings/templates';
		$data['page']['header_button_view'] = 'settings/sub_header_buttons/list';
		
		$send_data = array();

		$data['request'] = $this->curl->api_call('GET', 'templates');
		
		//load content into view
		$this->load->view('content',$data);
	}
	
	public function users()
    {
		//this define page data
		$data['page']['title'] = 'Settings | Users';
		$data['page']['heading'] = 'Users';
		$data['page']['main_view'] = 'settings/users';
		$data['page']['header_button_view'] = 'settings/sub_header_buttons/list';
		
		$send_data = array();

		$data['request'] = $this->curl->api_call('GET', 'users/first_name');
		
		//load content into view
		$this->load->view('content',$data);
	}
	
	public function roles()
    {
		//this define page data
		$data['page']['title'] = 'Settings | Security Roles';
		$data['page']['heading'] = 'Security Roles';
		$data['page']['main_view'] = 'settings/roles';
		$data['page']['header_button_view'] = 'settings/sub_header_buttons/list';
		
		$send_data['piggyback'] = array('roles','permissions');

		$data['request'] = $this->curl->api_call('GET', 'role_permissions',$send_data);
		
		//load content into view
		$this->load->view('content',$data);
	}
	
	public function items()
    {
		//this define page data
		$data['page']['title'] = 'Settings | Items';
		$data['page']['heading'] = 'Items';
		$data['page']['main_view'] = 'settings/items';
		$data['page']['header_button_view'] = 'settings/sub_header_buttons/list';
		
		$send_data = array();

		$data['request'] = $this->curl->api_call('GET', 'items');
		
		//load content into view
		$this->load->view('content',$data);
	}
	
	public function emails()
    {
		//this define page data
		$data['page']['title'] = 'Settings | Emails';
		$data['page']['heading'] = 'Emails';
		$data['page']['main_view'] = 'settings/emails';
		$data['page']['header_button_view'] = 'settings/sub_header_buttons/list';
		
		$send_data = array();

		$data['request'] = $this->curl->api_call('GET', 'organisations/');
		
		//load content into view
		$this->load->view('content',$data);
	}
	
	public function modal()
    {
		$data['form_dataset'] =  $this->dataset_string;
		
		if($this->uri->segment(3) == 'taxes'){
			if($this->uri->segment(4) == 'delete' && $this->uri->segment(5)){
				
				$data['id'] = $this->uri->segment(5);
				
				$data['request'] = $this->curl->api_call('GET', 'taxes/'.$data['id']);
				
				$this->load->view('settings/modal/delete_tax',$data);
			}elseif($this->uri->segment(4) == 'edit' && $this->uri->segment(5)){
				
				$data['id'] = $this->uri->segment(5);
				
				$data['request'] = $this->curl->api_call('GET', 'taxes/'.$data['id']);
				
				$this->load->view('settings/modal/edit_tax',$data);
			}else{
				show_404();
			}
		}elseif($this->uri->segment(3) == 'emails'){
			if($this->uri->segment(4) == 'signature'){
				
				$data['request'] = $this->curl->api_call('GET', 'email_settings');
				$this->load->view('settings/modal/edit_email_signature',$data);
				
			}elseif($this->uri->segment(4) == 'invoice'){
				
				$data['request'] = $this->curl->api_call('GET', 'email_settings');
				$this->load->view('settings/modal/edit_invoice_message',$data);
				
			}elseif($this->uri->segment(4) == 'estimate'){
				
				$data['request'] = $this->curl->api_call('GET', 'email_settings');
				$this->load->view('settings/modal/edit_estimate_message',$data);
			
			}elseif($this->uri->segment(4) == 'credit_note'){
				
				$data['request'] = $this->curl->api_call('GET', 'email_settings');
				$this->load->view('settings/modal/edit_credit_note_message',$data);
				
			}elseif($this->uri->segment(4) == 'payment'){
				
				$data['request'] = $this->curl->api_call('GET', 'email_settings');
				$this->load->view('settings/modal/edit_payment_message',$data);
				
			}elseif($this->uri->segment(4) == 'statement'){
				
				$data['request'] = $this->curl->api_call('GET', 'email_settings');
				$this->load->view('settings/modal/edit_statement_message',$data);
				
			}else{
				show_404();
			}
		}elseif($this->uri->segment(3) == 'users'){
			
			if($this->uri->segment(4) == 'add'){
				
				$data['request'] = $this->curl->api_call('GET', 'roles');
				
				$this->load->view('settings/modal/add_user',$data);
				
			}elseif($this->uri->segment(4) == 'delete' && $this->uri->segment(5)){
				
				$data['id'] = $this->uri->segment(5);
				
				$data['request'] = $this->curl->api_call('GET', 'users/'.$data['id']);
				
				$this->load->view('settings/modal/delete_user',$data);
				
			}elseif($this->uri->segment(4) == 'edit' && $this->uri->segment(5)){
				
				$data['id'] = $this->uri->segment(5);
				
				$send_data = array(
					'piggyback' => array('roles')				   
				);
				
				$data['request'] = $this->curl->api_call('GET', 'users/'.$data['id'],$send_data);
				
				$this->load->view('settings/modal/edit_user',$data);
				
			}else{
				show_404();
			}
		}elseif($this->uri->segment(3) == 'items'){
			
			if($this->uri->segment(4) == 'add'){
				
				$this->load->view('settings/modal/add_item',$data);
				
			}elseif($this->uri->segment(4) == 'delete' && $this->uri->segment(5)){
				
				$data['id'] = $this->uri->segment(5);
				
				$data['request'] = $this->curl->api_call('GET', 'items/'.$data['id']);
				
				$this->load->view('settings/modal/delete_item',$data);
				
			}elseif($this->uri->segment(4) == 'edit' && $this->uri->segment(5)){
				
				$data['id'] = $this->uri->segment(5);
				
				$data['request'] = $this->curl->api_call('GET', 'items/'.$data['id']);
				
				$this->load->view('settings/modal/edit_item',$data);
				
			}else{
				show_404();
			}
		}
		
		//$data['id'] =  $this->uri->segment(3);
		//$data['form_dataset'] =  $this->dataset_string;
		
		//load content into view
		//$this->load->view('settings/modal/delete_tax',$data);
	}

}
