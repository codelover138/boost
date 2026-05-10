<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller{ 
	
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
			$this->index();
		}
	}

    public function index()
    {
        
		//vars required for the api request
		$order_by = ($this->uri->segment(2) ? $this->uri->segment(2) : 'id');
		$direction = ($this->uri->segment(3) ? $this->uri->segment(3) : 'desc');
		$results_per_page = ($this->uri->segment(4) ? $this->uri->segment(4) : $this->config->item('pagination')['results_per_page']);
		$starting_record = ($this->uri->segment(5) ? $this->uri->segment(5) : '0');
		
		//sort by selection options		
		$sort_by_data = array(
			array(
				'element'=>'invoice_number', 
				'display'=>'Invoice'
			),
			array(
				'element'=>'date', 
				'display'=>'Date'
			),
			array(
				'element'=>'contact_organisation', 
				'display'=>'Client'
			),
			array(
				'element'=>'total_amount', 
				'display'=>'Amount'
			),
			array(
				'element'=>'status', 
				'display'=>'Status'
			),
			array(
				'element'=>'date_modified', 
				'display'=>'Last Updated'
			)
		);
		
		// load the pagination and sort lib
		$this->load->library('pagination_custom');
		
		$data['sort_by'] = $this->pagination_custom->sort_format($sort_by_data,$order_by,$direction,$results_per_page,$starting_record);

		// get config vars for pagination
		$results_per_page =  $this->config->item('pagination')['results_per_page'];
		
		$send_data = array();

		$data['request'] = $this->curl->api_call('GET', 'invoices/'.$order_by.'/'.$direction.'/'.$results_per_page.'/'.$starting_record, $send_data);

		//reformat pagination data		
		$data['pagination_data'] = $this->pagination_custom->reformat($data['request']['pagination']);
		
		//this define page data
		$data['page']['title'] = 'Invoices';
		$data['page']['heading'] = 'Invoices';
		$data['page']['main_view'] = 'invoices/list';
		$data['page']['header_button_view'] = 'invoices/sub_header_buttons/list';
		
		//this defines what is shown in the activity bar
		$data['activity']['heading'] = 'Invoice Activity';
		$data['activity']['category'] = 'invoices';
		
		//var_dump($data['request']);
		//print_r($data);
		$this->load->view('content',$data);

    }
	
	public function create()
    {	
		// requested piggy back which gets data
		$send_data = array(
            'piggyback'=> array('organisations','taxes','invoices/next_reference','templates',
				array(
					'resource'=>'contacts/organisation',
					'where'=>array('contact_type_id'=>1)
				)
			)
        );
		
		//request data from api using CURL
		$data['request'] = $this->curl->api_call('GET', 'currencies/', $send_data);
		
		//we need to print 1 item... so give this item a count of zero
		$data['current_item_count'] = 0;
		
		//this define page data
		$data['page']['title'] = 'New Invoice';
		$data['page']['heading'] = 'New Invoice';
		$data['page']['main_view'] = 'invoices/create';
		
		//this defines what is shown in the activity bar
		$data['activity']['heading'] = 'Invoice Activity';
		$data['activity']['category'] = 'invoices';
		
		//load content into view
		$this->load->view('content',$data);
    }
	
	public function edit()
    {	
         $send_data['piggyback'] = array('organisations','currencies','taxes','invoices/next_reference',
			 array(
						'resource'=>'contacts/organisation',
						'where'=>array('contact_type_id'=>1)
			 )
		 );
        		 
		 if(!$this->uri->segment(3)){
			 show_404();
		 }else{
			$document_id =  $this->uri->segment(3);
		 }
				
		$data['request'] = $this->curl->api_call('GET', 'invoices/'.$document_id, $send_data);
		
		//this define page data
		$data['page']['title'] = 'Edit Invoice';
		$data['page']['heading'] = 'Edit Invoice';
		$data['page']['main_view'] = 'invoices/edit';
		
		//this defines what is shown in the activity bar
		$data['activity']['heading'] = 'Invoice Activity';
		$data['activity']['category'] = 'invoices';
		$data['activity']['document_id'] = $document_id;

		$this->load->view('content',$data);
    }
	
	public function duplicate()
    {	
         $send_data['piggyback'] = array('organisations','currencies','taxes','invoices/next_reference',
			 array(
				'resource'=>'contacts/organisation',
				'where'=>array('contact_type_id'=>1)
			 )
		 );
        		 
		 if(!$this->uri->segment(3)){
			 show_404();
		 }else{
			$document_id =  $this->uri->segment(3);
		 }
				
		$data['request'] = $this->curl->api_call('GET', 'invoices/'.$document_id, $send_data);

		//this define page data
		$data['page']['title'] = 'New Invoice';
		$data['page']['heading'] = 'New Invoice';
		$data['page']['main_view'] = 'invoices/duplicate';
		
		//this defines what is shown in the activity bar
		$data['activity']['heading'] = 'Invoice Activity';
		$data['activity']['category'] = 'invoices';
		
		$this->load->view('content',$data);
    }
	
	public function convert()
    {	
         $send_data['piggyback'] = array('organisations','currencies','taxes','invoices/next_reference',
			 array(
						'resource'=>'contacts/organisation',
						'where'=>array('contact_type_id'=>1)
			 )
		 );
        		 
		 if(!$this->uri->segment(3)){
			 show_404();
		 }else{
			$document_id =  $this->uri->segment(3);
		 }
				
		$data['request'] = $this->curl->api_call('GET', 'estimates/'.$document_id, $send_data);
				
		//change estimate number to reference
		$data['request']['data'][$document_id]['reference'] = $data['request']['data'][$document_id]['estimate_number'];
		
		//this define page data
		$data['page']['title'] = 'New Invoice';
		$data['page']['heading'] = 'New Invoice';
		$data['page']['main_view'] = 'invoices/convert';
		
		//this defines what is shown in the activity bar
		$data['activity']['heading'] = 'Invoice Activity';
		$data['activity']['category'] = 'invoices';
		
		$this->load->view('content',$data);
    }
	
	public function preview()
    {	
		
		$send_data = array(
            'piggyback'=> array('organizations','templates','theme_settings',
				array(
					'resource'=>'contacts/organisation',
					'where'=>array('contact_type_id'=>1)
				 )
			)
        );
		
		//pick up and use segment 2 as the id
		//this would be segment 3 but the config/routes.php changes it so "preview" is not needed in the url.
		//if you wanted to revert back to using "preview" in the url then all links to prieview pages would need to have invoices/preview/id as the url and the search links in views/global_snippets/all_search.php would need to show the same
		 if(!$this->uri->segment(2)){
			 show_404();
		 }else{
			$document_id =  $this->uri->segment(2);
			
		 }
				
		$data['request'] = $this->curl->api_call('GET', 'invoices/'.$document_id, $send_data);
		
		//this define page data
		$data['page']['title'] = 'Preview Invoice';
		$data['page']['heading'] = 'Preview Invoice';
		$data['page']['main_view'] = 'invoices/preview';
		$data['page']['header_button_view'] = 'invoices/sub_header_buttons/preview';
		
		//this defines what is shown in the activity bar
		$data['activity']['heading'] = 'Invoice Activity';
		$data['activity']['category'] = 'invoices';
		$data['activity']['document_id'] = $document_id;
		
		$this->load->view('content',$data);
    }
	
	public function snippets(){
		if($this->uri->segment(3)=='list_item'){

			if(!$this->uri->segment(4)){
				 show_404();
			 }else{
				$document_id =  $this->uri->segment(4);
			 }
					
			$request = $this->curl->api_call('GET', 'invoices/'.$document_id);
			
			$data = $request['data'][$document_id];
			$data['user_data'] = $request['user_data'];

			$this->load->view('invoices/snippets/list_row',$data);
		}
	}
	
	public function modal()
    {	
		
		if($this->uri->segment(3)=='reminder'){// if segment 3 is reminder load the reminder modal	
			if($this->uri->segment(4) !== NULL){
				if($this->uri->segment(5)){
					$data['id'] = $this->uri->segment(5);
				}
				$data['reminder'] = ($this->uri->segment(4) ? $this->uri->segment(4) : 0);			
				$this->load->view('invoices/modal/reminder',$data);
			}else{
				$data['heading'] = 'Error...';
				$data['body'] = 'An Error occured... Please try again later ';
				$this->load->view('global_modals/error',$data);
			}
		}elseif($this->uri->segment(3)=='payment'){// if last segment is payment load the payment modal
			
			if($invoice_id = $this->uri->segment(4)){//if invoice id is found then get the data and continue
				
				$send_data = array(
					'piggyback'=> array('payment_methods')
				);
				
				// get dataset which was already created in the constructor id the dataset was sent via get
				$data['form_dataset'] =  $this->dataset_string;
				
				$data['request'] = $this->curl->api_call('GET', 'invoices/'.$invoice_id, $send_data);
				$this->load->view('invoices/modal/payment',$data);				
			}else{//else display an error	
				$data['heading'] = 'Error...';
				$data['body'] = 'An Error occured... Please try again later ';
				$this->load->view('global_modals/error',$data);
			}
			
			
		}elseif($this->uri->segment(3)=='mark'){// if last segment is payment load the payment modal
			
			if($this->uri->segment(4) && $this->uri->segment(5)){//if invoice id is found then get the data and continue
				
				$invoice_id = $this->uri->segment(4);
				$status = $this->uri->segment(5);
				$data['request'] = $this->curl->api_call('GET', 'invoices/'.$invoice_id);
				
				// get dataset which was already created in the constructor id the dataset was sent via get
				$data['form_dataset'] =  $this->dataset_string;

				$this->load->view('invoices/modal/mark_as_sent',$data);				
			}else{//else display an error	
				$data['heading'] = 'Error...';
				$data['body'] = 'An Error occured... Please try again later ';
				$this->load->view('global_modals/error',$data);
			}
			
			
		}elseif($this->uri->segment(3)=='send'){ // if last segment is send load the send modal
			
			if($invoice_id = $this->uri->segment(4)){//if invoice id is found then get the data and continue
				$send_data = array(
					'piggyback'=> array(
							'messages/invoices/'.$invoice_id,
							'organizations',
							'email_settings'
					)
				);
				
				$data = $this->curl->api_call('GET', 'invoices/'.$invoice_id, $send_data);
				$data['id'] = $invoice_id;
				
				// get dataset which was already created in the constructor id the dataset was sent via get
				$data['form_dataset'] =  $this->dataset_string;
				
				$this->load->view('invoices/modal/send',$data);
			}else{//else display an error	
				$data['heading'] = 'Error...';
				$data['body'] = 'An Error occured... Please try again later ';
				$this->load->view('global_modals/error',$data);
			}
		}
    }
	
	public function bulk(){

		if($this->uri->segment(3)=='payment'){
			// if segment 3 is payment load the bulk payment modal	
			//print_r($this->input->get('applicableIds'));
			$send_data = array(
				'piggyback'=> array('payment_methods'),
				'multiple' => $this->input->get('applicableIds')
			);
			
			$data['request'] = $this->curl->api_call('GET', 'invoices/', $send_data);	

			$this->load->view('invoices/modal/bulk_payment',$data);			
			
		}elseif($this->uri->segment(3)=='mark_sent'){
			// if segment 3 is payment load the bulk payment modal	
			//print_r($this->input->get('applicableIds'));
			$send_data = array(
				'multiple' => $this->input->get('applicableIds')
			);
			
			$data['request'] = $this->curl->api_call('GET', 'invoices/', $send_data);	

			$this->load->view('invoices/modal/bulk_mark_sent',$data);			
			
		}elseif($this->uri->segment(3)=='archive'){
			// if segment 3 is payment load the bulk payment modal	
			//print_r($this->input->get('applicableIds'));
			$send_data = array(
				'multiple' => $this->input->get('applicableIds')
			);
			
			$data['request'] = $this->curl->api_call('GET', 'invoices/', $send_data);	

			$this->load->view('invoices/modal/bulk_archive',$data);			
			
		}elseif($this->uri->segment(3)=='export_pdf'){
			// if segment 3 is payment load the bulk payment modal	
			//print_r($this->input->get('applicableIds'));
			$send_data = array(
				'multiple' => $this->input->get('applicableIds')
			);
			
			$data['request'] = $this->curl->api_call('GET', 'invoices/', $send_data);	

			$this->load->view('invoices/modal/bulk_export_pdf',$data);			
			
		}else{
			show_404();	
		}
	}

}
