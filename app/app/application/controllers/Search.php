<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller{ 
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('curl');
		$this->load->helper('url');	
		$this->load->helper('api_base');
    }

	
	public function all()
    {
		
		if($input = $this->uri->segment(3)){	
			
			//piggyback 
			$send_data['piggyback'] = array('contacts','currencies');
			
			//invoices
			$send_data['tables']['invoices']['search'] = array('invoice_number','reference');
			$send_data['tables']['invoices']['return'] = array('id','invoice_number','reference','total_amount','contact_id','currency_id');
			
			//credit notes
			$send_data['tables']['credit_notes']['search'] = array('credit_note_number','reference');
			$send_data['tables']['credit_notes']['return'] = array('id','credit_note_number','reference','total_amount','contact_id');
			
			//estimates
			$send_data['tables']['estimates']['search'] = array('estimate_number','reference');
			$send_data['tables']['estimates']['return'] = array('id','estimate_number','reference','total_amount','contact_id');
			
			//contacts
			$send_data['tables']['contacts']['search'] = array('organisation');
			$send_data['tables']['contacts']['return'] = array('id','organisation');
			
						
			$curl_request = $this->curl->api_call('GET', 'search/'.$input, $send_data);
			
			//print_r($curl_request);
			
			if(isset($curl_request['data'])){
				$request['input'] = $input;
				$request['data'] = $curl_request['data'];
				$request['piggyback'] = $curl_request['piggyback'];
			}else{
				$request['heading'] = 'No Results Found';
			}
			
			
			$this->load->view('global_snippets/all_search',$request);
		}else{
			$request['heading'] = 'Type to Search';
			$this->load->view('global_snippets/all_search',$request);
		}
    }
	
	public function items()
    {						
		//if the 3rd secment is available use this as the search sting... if not display instruction
		if($this->uri->segment(3)){
			
			$input = urldecode($this->uri->segment(3));
			
			$send_data['tables']['items']['search'] = array('item_name');
			$send_data['tables']['items']['return'] = array('id','item_name','description','quantity','tax','rate');
			
						
			$curl_request = $this->curl->api_call('GET', 'search/'.$input, $send_data);
			
			if(isset($curl_request['data'])){			
				foreach($curl_request['data']['items'] as $array_key => $array_data){
	
					$found_string = false;
	
					if(preg_match("/".$input."/i",$array_data['item_name']) || preg_match("/".$input."/i",$array_data['id'])){
						$found_string = true;
					}
			
					if($found_string === true){	
						$request['data'][] = $array_data;
					}
					
				}
			
			}
			
			
			if(isset($request['data'])){
				$request['input'] = $input;
				$request['heading'] = 'Items';
			}else{
				$request['input'] = $input;
				$request['heading'] = 'No Results';
				$request['add']['text'] = 'Add New Item +';
			}
			
			$this->load->view('global_snippets/item_search',$request);
			
			
		}else{
			$request['heading'] = 'Type to Search';
			$this->load->view('global_snippets/item_search',$request);
		}
		
    }
	

}
