<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents
{
    public $params;
    public $model;
    public $table_prefix;
    public $activities_params;

    public function __construct($params = null)
    {
        $this->CI =& get_instance();
        $this->params = $params;
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
	//	$this->CI->load->helper('form');

        $this->CI->load->model('template_model');

        if (isset($this->params['model'])) :
            $this->CI->load->model($this->params['model']);
            $this->model = $this->params['model'];
        else :
            $this->model = 'generic_model';
        endif;
    }

    public function entry($string)
    {
        # allow cross origin and set allowed HTTP methods
        $this->CI->regular->set_response_headers();

        # set content type
        $this->CI->regular->header_('json');
        $response = array();

        # check if the request method is valid
        if ($method = $this->CI->regular->valid_method())
        {
            $entry_params['method'] = $method;
            $method_function = '_' . strtolower($method);

            $is_allowed = $this->CI->regular->is_allowed($method, calling_function());

            if (!$is_allowed) {
                $this->CI->regular->header_(405);
                $this->CI->regular->respond(
                    array(
                        'status' => 'ERROR',
                        'message' => array($method . ' method is not allowed on ' . calling_function())
                    ));
                return;
            }

            $post_vars = $this->CI->regular->decode();

            $inputs = array(
                'string' => $string,
                'post'=>$post_vars
            );

            //$response = $this->$method_function($inputs);
            $response = $this->_get($inputs);
        }
        else
        {
            $response['status'] = 'ERROR';
            $response['message'][] = 'Bad request';
            $this->CI->regular->header_(400);
        }

        # Add user data to response
        $user_data = $this->CI->userhandler->determine_user();
        if($user_data['bool']) $response['user_data'] = $user_data['data'];

        $this->CI->regular->respond($response);
    }

    public function _get($inputs)
    {
        $return = array('status' => 'OK');

        $string = $inputs['string'];

        if (!is_null($string))
        {
            $this->CI->load->library('messaging');

            /*
             * REQUIRED PARAMS:
             * entity, entity_id, account_name
             * */

            $params = $this->CI->messaging->decrypt($string);
			
			//$return['input_params'] = $params;
			//$return['inputs'] = $inputs['post']['filters2'];
			
			//echo $string;			
			//json_encode($params);
			//exit;

            $result = array();

            //$return['debug'] = $params;

            if(isset($params['entity']))
            {
                $this->CI->load->library('db/switcher', array('account_name' => $params['account_name']));
                $this->CI->switcher->account_db();

                $return['type'] = $params['entity'].'s';

                if($params['entity'] != 'statement')
                {
                    $result = $this->CI->template_model->read($params, $params['entity_id']);
                }
                else
                {
                    $this->CI->load->library('resources/statements');

                    $statement_inputs = array('id' => $params['entity_id']);

					//From decoded url string
                    if(isset($params['filters']))
                    {
                        $statement_inputs['post']['filters'] = $params['filters'];
                    }
					
					//Date filter for statements viewed via sent mail
					if(isset($inputs['post']['filters2'])){
						$statement_inputs['post']['filters'] = $inputs['post']['filters2'];
						
						$statement_inputs['post']['filters']['start_date'] = date('Y-m-d', strtotime($statement_inputs['post']['filters']['start_date']));
                        $statement_inputs['post']['filters']['end_date'] = date('Y-m-d', strtotime($statement_inputs['post']['filters']['end_date']));
					}
					
					

                    $result = $this->CI->statements->_get($statement_inputs);


					//Determine new frontend default date filter
                    if(isset($params['filters'])) {
                        $result['filters'] = $params['filters'];
                    }
                    else {
						$this->CI->load->model('generic_model');
                        $result['filters'] = $this->CI->generic_model->period_filter();

                        $result['start_date'] = date('Y-m-d', strtotime($result['start_date']));
                        $result['end_date'] = date('Y-m-d', strtotime($result['end_date']));
                    }
					if(isset($inputs['post']['filters2'])) {
						
						$result['filters'] = $inputs['post']['filters2'];		//Set as new frontend filter default
					}
					
					
					
                }

                $return['id'] = $params['entity_id'];
				
				if($return['type'] == 'invoices' || $return['type'] == 'credit_notes')
                {

					if(@$params['preview'] !== true){
						$params['where']['status'] = 'sent';				
						$this->CI->template_model->update($params, $params['entity_id'], array('status'=>'viewed','single_update'=>true));
						unset($params['where']);
						
						//update activity
						$document_data = array_values($result)[0];
						$a_post = array(
							'label' => ucwords(str_replace('_',' ',$params['entity'])) . ' #' . $document_data->{$params['entity'].'_number'},
							'category' => $params['entity'] . 's',
							'link' => $params['entity'] . 's/' . $document_data->id,
							'item_id' => $document_data->id,
							'type' => 'standard',
							'short_message' => $document_data->currency_symbol . $document_data->total_amount . ' viewed by '.$document_data->contact->organisation
						);								
						$this->CI->activities_model->create($a_post);
					}
				}
				
                if($return['type'] == 'estimates')
                {
                   
				    if(@$params['preview'] !== true){
						$params['where']['status'] = 'sent';					
						$this->CI->generic_model->update($params, $params['entity_id'], array('status'=>'viewed'));
						unset($params['where']);

						//update activity
						$document_data = array_values($result)[0];
						$a_post = array(
							'label' => ucwords(str_replace('_',' ',$params['entity'])) . ' #' . $document_data->{$params['entity'].'_number'},
							'category' => $params['entity'] . 's',
							'link' => $params['entity'] . 's/' . $document_data->id,
							'item_id' => $document_data->id,
							'type' => 'standard',
							'short_message' => $document_data->currency_symbol . $document_data->total_amount . ' viewed by '.$document_data->contact->organisation
						);								
						$this->CI->activities_model->create($a_post);
						
					}
					
                    $accepted_link_params_arr = array(
                        'entity' => $params['entity'],
                        'id' => $params['entity_id'],
                        'status' => 'accepted',
                        'account_name' => $params['account_name']
                    );

                    $declined_link_params_arr = array(
                        'entity' => $params['entity'],
                        'id' => $params['entity_id'],
                        'status' => 'declined',
                        'account_name' => $params['account_name']
                    );

                    $base_link = $this->CI->config->item('api_url').'statuses/'.$return['type'].'/';

                    $this->CI->load->library('messaging');

                    $return['accept'] = $base_link.$this->CI->messaging->encrypt($accepted_link_params_arr);
                    $return['decline'] = $base_link.$this->CI->messaging->encrypt($declined_link_params_arr);
                }
            }

            $return['data'] = $result;
        }

        /* Piggyback:
         * additional requested data
         ----------------------------------------------------------------------------------------------*/
        if (isset($inputs['post']['piggyback'])) {
            $return['piggyback'] = $this->CI->regular->piggyback($inputs['post']);
        }
		
		if ( isset($inputs['post']['piggyback']) && array_search("statement_url",$inputs['post']['piggyback']) && $params['entity'] != 'statement' ) {
			
			$headers = $this->CI->regular->get_request_headers();
			$this->params['account_name'] = $headers['Account-Name'];
			
			$post_vars = $this->CI->messaging->decrypt($string);
			
			$statement_params = array();
			$statement_params['entity'] = 'statement';
			$table_results = current($result)->contact;
			$statement_params['entity_id'] = $table_results->id;
			$statement_params['account_name'] = $params['account_name'];
			$statement_params['subject'] = "Statement";
			$statement_params['contact_email'] = "";
			$statement_params['message_body'] = "";
			
			
			//$return['post_vars'] = $params;
			//$return['table_results'] = $table_results;
			
			 $return["statement_url"] = get_protocol().$this->params['account_name'].'.'.$this->CI->config->item('document_url').$this->CI->messaging->encrypt($statement_params);
			 //$return["statement_url"] = $params;
			 //return $return;
		}

        return $return;
    }
}