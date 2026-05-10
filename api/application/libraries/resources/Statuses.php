<?php

class Statuses
{
    public $table_prefix;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
    }

    public function entry($entity, $id, $status, $account_name)
    {
        # allow cross origin and set allowed HTTP methods
        $this->CI->regular->set_response_headers();

        # set content type
        $this->CI->regular->header_('json');
        $response = array();

        # check if the request method is valid
        if ($method = $this->CI->regular->valid_method()) {
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

            //$post_vars = $this->CI->regular->decode();

            $response = $this->_put($entity, $id, $status, $account_name);
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

    public function _put($entity, $id, $status, $account_name)
    {
        $return = array('status'=>'ERROR');
        if($entity == 'estimates' || $entity == 'estimate')
        {
            $this->CI->load->library('db/switcher', array('account_name' => $account_name));
            $this->CI->switcher->account_db();

            $params = array(
                'table' => $this->table_prefix . 'estimates',
                'entity' => 'estimate'
            );

            $update = array('status'=>$status);

            $result = $this->CI->generic_model->update($params, $id, $update);
			
            if($result['bool']){
					
				//get document data
				$doc_params = array(
					'table' => $params['table'],
					'entity' => $params['entity'],
					'items_table' => $this->table_prefix.'estimate_items'
				);				
				$document_data = $this->CI->template_model->read($doc_params, $id,'single');
				
				if($status == 'accepted'){
					$a_type = 'info';
				}elseif($status == 'declined'){
					$a_type = 'warning';
				}else{
					$a_type = 'standard';
				}
							
				//update activity
				$a_post = array(
					'label' => ucwords(str_replace('_',' ',$doc_params['entity'])) . ' #' . $document_data->{$doc_params['entity'].'_number'},
					'category' => $doc_params['entity'] . 's',
					'link' => $doc_params['entity'] . 's/' . $document_data->id,
					'item_id' => $document_data->id,
					'type' => $a_type,
					'short_message' => $document_data->currency_symbol . $document_data->total_amount . ' '.$status.' by contact'
				);								
				$this->CI->activities_model->create($a_post);

				$return['status'] = 'OK';
			}
            $return['record_id'] = $result['record_id'];
            $return['message'] = $result['message'];
        }

        return $return;
    }
}