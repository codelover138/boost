<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends CI_Controller{ 
	
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
				
		//sort by selection options		
		$sort_by_data = array(
			array(
				'element'=>'date', 
				'display'=>'Date'
			),
			array(
				'element'=>'supplier_id', 
				'display'=>'Supplier'
			),
			array(
				'element'=>'total_amount', 
				'display'=>'Amount'
			),
			array(
				'element'=>'file_name', 
				'display'=>'Document'
			)
		);
		
		
		$send_data = array(
            'piggyback'=> array(
				'currencies',
				'organizations'
			)
        );
		/* this will be moved to reports
		if(isset($_GET['start_date']) && isset($_GET['end_date'])){		
			$send_data['filters']['start_date'] = $_GET['start_date'];
			$send_data['filters']['end_date'] = $_GET['end_date'];
		}elseif(isset($_GET['start_date'])){
			$send_data['filters']['start_date'] = $_GET['start_date'];
		}elseif(isset($_GET['end_date'])){
			$send_data['filters']['end_date'] = $_GET['end_date'];
		}*/
		
		//vars required for the api request
		$order_by = ($this->uri->segment(2) ? $this->uri->segment(2) : 'date');
		$direction = ($this->uri->segment(3) ? $this->uri->segment(3) : 'desc');
		//$results_per_page = ($this->uri->segment(4) ? $this->uri->segment(4) : $this->config->item('pagination')['results_per_page']);
		$results_per_page = ($this->uri->segment(4) ? $this->uri->segment(4) : 10);
		$starting_record = ($this->uri->segment(5) ? $this->uri->segment(5) : '0');
		
		
		// load the pagination and sort lib
		$this->load->library('pagination_custom');
		
		$data['sort_by'] = $this->pagination_custom->sort_format($sort_by_data,$order_by,$direction,$results_per_page,$starting_record);
				
		$data['request'] = $this->curl->api_call('GET', 'expenses/'.$order_by.'/'.$direction.'/'.$results_per_page.'/'.$starting_record, $send_data);
		
		
		//reformat pagination data		
		$data['pagination_data'] = $this->pagination_custom->reformat($data['request']['pagination']);
		
		//this defines what is shown in the activity bar
		$data['activity']['heading'] = 'Expense Activity';
		$data['activity']['category'] = 'expenses';
		
		# add formatted dates to the filters
		$data['request']['filters']['formatted_start_date'] = date("j M Y",strtotime($data['request']['filters']['start_date']));
		$data['request']['filters']['formatted_end_date'] = date("j M Y",strtotime($data['request']['filters']['end_date']));
		
		# this define page data
		$data['page']['title'] = 'Expenses';
		$data['page']['heading'] = 'Expenses';
		$data['page']['main_view'] = 'expenses/list';
		$data['page']['header_button_view'] = 'expenses/sub_header_buttons/list';
		//var_dump($data['request']['data']);
		$this->load->view('content',$data);
    }
	
	public function create()
    {	
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

		//this define page data
		$data['page']['title'] = 'New Expense';
		$data['page']['heading'] = 'New Expense';
		$data['page']['main_view'] = 'expenses/create';
		
		//this defines what is shown in the activity bar
		$data['activity']['heading'] = 'Expense Activity';
		$data['activity']['category'] = 'expenses';
		
		//load content into view
		$this->load->view('content',$data);
    }
	
	
	public function edit()
    {	
	
		if(!$this->uri->segment(3)){
			 show_404();
		 }else{
			$document_id =  $this->uri->segment(3);
		 }
		 
		// requested piggy back which gets data
		$send_data = array(
			'piggyback'=> array('taxes','contact_types','expenses_categories','organisations','currencies','contacts/organisation')
		);
		
		//request data from api using CURL
		$data['request'] = $this->curl->api_call('GET', 'expenses/'.$document_id,$send_data);

		//reformat contacts into their types		
		foreach($data['request']['piggyback']['contacts/organisation'] as $contact_id => $contact_data){
			$contact_type_id = $contact_data['contact_type_id'];
			$data['request']['contact_types'][$contact_type_id][] = $contact_data;
		}

		//this define page data
		$data['page']['title'] = 'Edit Expense';
		$data['page']['heading'] = 'Edit Expense';
		$data['page']['main_view'] = 'expenses/edit';
		
		//this defines what is shown in the activity bar
		$data['activity']['heading'] = 'Expense Activity';
		$data['activity']['category'] = 'expenses';
		$data['activity']['document_id'] = $document_id;
		
		//load content into view
		$this->load->view('content',$data);
    }
	
	
	public function preview()
    {	
		
		$send_data = array(
            'piggyback'=> array('currencies','organizations')
        );
		
		//pick up and use segment 2 as the id
		//this would be segment 3 but the config/routes.php changes it so "preview" is not needed in the url.
		//if you wanted to revert back to using "preview" in the url then all links to prieview pages would need to have contacts/preview/id as the url and the search links in views/global_snippets/all_search.php would need to show the same
		 if(!$this->uri->segment(2)){
			 show_404();
		 }else{
			$document_id =  $this->uri->segment(2);
			
		 }
				
		$data['request'] = $this->curl->api_call('GET', 'contacts/'.$document_id, $send_data);
		
		//this define page data
		$data['page']['title'] = 'Preview Contact';
		$data['page']['heading'] = 'Preview Contact';
		$data['page']['main_view'] = 'contacts/preview';
		$data['page']['header_button_view'] = 'contacts/sub_header_buttons/preview';
		
		$this->load->view('content',$data);
    }
	
	public function snippets(){
		if($this->uri->segment(3)=='list_item'){
			
			//$send_data['piggyback'] = array('contacts/organisation','currencies','taxes','contacts/next_reference');
        	
			if(!$this->uri->segment(4)){
				 show_404();
			 }else{
				$document_id =  $this->uri->segment(4);
			 }
					
			$request = $this->curl->api_call('GET', 'contacts/'.$document_id);
			//print_r($data);
			$this->load->view('contacts/snippets/list_row',$request['data'][$document_id]);
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
				$this->load->view('contacts/modal/reminder',$data);
			}else{
				$data['heading'] = 'Error...';
				$data['body'] = 'An Error occured... Please try again later ';
				$this->load->view('global_modals/error',$data);
			}
		}elseif($this->uri->segment(3)=='payment'){// if last segment is payment load the payment modal
			
			if($contact_id = $this->uri->segment(4)){//if contact id is found then get the data and continue
				
				$send_data = array(
					'piggyback'=> array('payment_methods')
				);
				
				// get dataset which was already created in the constructor id the dataset was sent via get
				$data['form_dataset'] =  $this->dataset_string;
				
				$data['request'] = $this->curl->api_call('GET', 'contacts/'.$contact_id, $send_data);
				$this->load->view('contacts/modal/payment',$data);				
			}else{//else display an error	
				$data['heading'] = 'Error...';
				$data['body'] = 'An Error occured... Please try again later ';
				$this->load->view('global_modals/error',$data);
			}
			
			
		}elseif($this->uri->segment(3)=='send'){ // if last segment is send load the send modal
			
			if($doc_id = $this->uri->segment(4)){//if contact id is found then get the data and continue
				$send_data = array(
					'piggyback'=> array(
							'messages/statements/'.$doc_id,
							'organizations',
							'email_settings',
							'contacts/'.$doc_id
					)
				);
								
				$data = $this->curl->api_call('GET', 'statements/'.$doc_id, $send_data);
				$data['id'] = $doc_id;
							
				$data['filters']['formatted_start_date'] = date("j M Y",strtotime($data['filters']['start_date']));
				$data['filters']['formatted_end_date'] = date("j M Y",strtotime($data['filters']['end_date']));
				
				// get dataset which was already created in the constructor id the dataset was sent via get
				$data['form_dataset'] =  $this->dataset_string;
				
				//print_r($data);	
				
				$this->load->view('contacts/modal/send_statement',$data);
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
			
			$data['request'] = $this->curl->api_call('GET', 'contacts/', $send_data);	

			$this->load->view('contacts/modal/bulk_payment',$data);			
			
		}elseif($this->uri->segment(3)=='mark_sent'){
			// if segment 3 is payment load the bulk payment modal	
			//print_r($this->input->get('applicableIds'));
			$send_data = array(
				'multiple' => $this->input->get('applicableIds')
			);
			
			$data['request'] = $this->curl->api_call('GET', 'contacts/', $send_data);	

			$this->load->view('contacts/modal/bulk_mark_sent',$data);			
			
		}elseif($this->uri->segment(3)=='archive'){
			// if segment 3 is payment load the bulk payment modal	
			//print_r($this->input->get('applicableIds'));
			$send_data = array(
				'multiple' => $this->input->get('applicableIds')
			);
			
			$data['request'] = $this->curl->api_call('GET', 'contacts/', $send_data);	

			$this->load->view('contacts/modal/bulk_archive',$data);			
			
		}elseif($this->uri->segment(3)=='export_pdf'){
			// if segment 3 is payment load the bulk payment modal	
			//print_r($this->input->get('applicableIds'));
			$send_data = array(
				'multiple' => $this->input->get('applicableIds')
			);
			
			$data['request'] = $this->curl->api_call('GET', 'contacts/', $send_data);	

			$this->load->view('contacts/modal/bulk_export_pdf',$data);			
			
		}else{
			show_404();	
		}
	}
	
	
	

}
