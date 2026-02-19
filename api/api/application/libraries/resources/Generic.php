<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Generic
{
    public $params;
    public $model;
    public $table_prefix;
    public $activities_params;
	public $response_extra = array();

    public function __construct($params = null)
    {
        $this->CI =& get_instance();
        $this->params = $params;
        $this->CI->load->model('generic_model');
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
        $this->activities_params = array('table' => $this->table_prefix . 'activites', 'entity' => 'activity');

        if(isset($this->params['model'])) :
            $this->CI->load->model($this->params['model']);
            $this->model = $this->params['model'];
        else :
            $this->model = 'generic_model';
        endif;
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * ENTRY: This is where requests get designated to a process
     --------------------------------------------------------------------------------------------------------------------------*/
    public function entry($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        # allow cross origin and set allowed HTTP methods
        $this->CI->regular->set_response_headers();

        # set content type
        $this->CI->regular->header_('json');
        $response = array();

        $entry_params = array(
            'resource' => calling_function(),
            'id' => $id,
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset
        );

        if (!is_null($order) && !is_array($order)) :
            $order = strtoupper($order);
        endif;

        # Add user data to response
        $user_data = $this->CI->userhandler->determine_user();
        if($user_data['bool'])
        {
            $response['user_data'] = $user_data['data'];
        }

        # check if the request method is valid
        if ($method = $this->CI->regular->valid_method())
        {
            $entry_params['method'] = $method;
            $method_function = '_' . strtolower($method);

            $is_allowed = $this->CI->regular->is_allowed($method, $entry_params['resource']);

            if (!$is_allowed) {
                $this->CI->regular->header_(405);
                $this->CI->regular->respond(
                    array(
                        'status' => 'ERROR',
                        'message' => array($method . ' method is not allowed on ' . $entry_params['resource'])
                    )
                );
                return;
            }

            # Check if user is allowed to perform the requested action
            if($user_data['bool'])
            {
                $this->CI->load->library('permissions');
                $permitted = $this->CI->permissions->validate_permissions($method, $entry_params['resource'], $user_data['data']->permissions);

                if(!$permitted)
                {
                    $this->CI->regular->header_(405);
                    $this->CI->regular->respond(
                        array(
                            'status' => 'ERROR',
                            'message' => array('User is not permitted to perform this action')
                        ));
                    return;
                }
            }

            # check if theres post set within user defined params
            if(isset($this->params['post'])) :
                $post_vars = $this->params['post'];
                unset($this->params['post']);
            else :
                $post_vars = $this->CI->regular->decode();
            endif;

            # if login
            if ($id == 'login') {
                $this->login($post_vars, $this->CI->regular->request_method());
                return;
            }

            switch ($id) {
                case 'login':
                    $this->login($post_vars, $this->CI->regular->request_method());
                    return;
                    break;

                case 'export':
                    $this->export($order, $post_vars, $limit);
                    return;
                    break;
            }

            # input to be sent to one of the request methods
            $inputs = array();
            $inputs['url']['id'] = $id;
            $inputs['url']['string'] = trim(implode('/', $entry_params), '/');
            $inputs['post'] = $post_vars;
            $inputs['order'] = $order;

            if(isset($inputs['post']['field'])) :
                $this->params['field'] = $inputs['post']['field'];
            endif;

            if ($this->CI->regular->request_method() != 'GET') {
                # unsets piggyback post in method used is not GET
                if (isset($inputs['post']['piggyback'])) :
                    unset($inputs['post']['piggyback']);
                endif;

                # unsets multiple post in method used is not GET
                if (isset($inputs['post']['multiple'])) :
                    unset($inputs['post']['multiple']);
                endif;
            }

            if(!is_null($offset)) :
                $inputs['offset'] = $offset;
            endif;

            if(!is_null($limit)) :
                $inputs['limit'] = $limit;
            endif;

            $response = $this->$method_function($inputs);
        }
        else
        {
            $response['status'] = 'ERROR';
            $response['message'][] = 'Bad request';
            $this->CI->regular->header_(400);
        }

        if($user_data['bool']) $response['user_data'] = $user_data['data'];
		
		#if any extra output arrays have been added then add them to the response array
		if(!empty($this->response_extra)){
			$response = array_merge($response,$this->response_extra);
		}

        $this->CI->regular->respond($response);
    }

    /*--------------------------------------------------------------------------------------------------------------------------
    * LOGIN
    --------------------------------------------------------------------------------------------------------------------------*/
    public function login($post, $method)
    {
        $auth = $this->CI->regular->get_request_headers();

        if (isset($post['login']) || (isset($auth['Authorization']) && $auth['Authorization'] != 'undefined') || (isset($auth['Auth']) && $auth['Auth'] != 'undefined')) {
            switch ($method) {
                case 'POST':
                    if (isset($post['login'])) {
                        $raw_login = $post['login'];
                        $decoded_authorization = json_decode(base64_decode($raw_login), true);
                        log_message('error', 'if condition');
                        return;
                    }
                    break;

                case 'DELETE':
                    if (isset($auth['Authorization'])) {
                        $token = $this->CI->regular->get_request_headers()['Authorization'];
                        $this->CI->userhandler->logout($token);
                        return;
                    } elseif (isset($auth['Auth'])) {
                        $token = $this->CI->regular->get_request_headers()['Auth'];
                        $this->CI->userhandler->logout($token);
                        return;
                    } else {
                        $return = array(
                            'status' => 'ERROR',
                            'message' => array('Authorization header not found')
                        );
                        $this->CI->regular->header_(401);
                    }
                    break;

                default:
                    $return = array(
                        'status' => 'ERROR',
                        'message' => array('Invalid method: ' . $method)
                    );
                    $this->CI->regular->header_(405);
                    break;
            }
        }
        else {
            log_message('error', 'else condition');
            $return = array(
                'status' => 'ERROR',
                'message' => array('Login data or auth token not found')
            );
            $this->CI->regular->header_(401);
        }
        log_message('error', json_encode($return));
        $this->CI->regular->respond($return);
    }

    /*-----------------------------------------------------------------------------------------------------------------------
     * EXPORT
     ------------------------------------------------------------------------------------------------------------------------ */
    public function export($resource, $post_vars, $id = null)
    {
        $resource = strtolower($resource);
        $this->CI->load->library('pdf/' . $resource);

        //$post_vars = $this->regular->decode();

        if (is_null($id)) {
            if (isset($post_vars['data']) && !empty($post_vars['data'])) :
                $id = $post_vars['data'];
            endif;
        }

        if (!isset($post_vars['base_url'])) :
            $post_vars['base_url'] = base_url('api') . '/';
        endif;

        $this->CI->$resource->generate_pdf($id, 'F', $post_vars['base_url']);
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * POST: Creates a new record in the database
     --------------------------------------------------------------------------------------------------------------------------*/
    public function _post($inputs)
    {
        $model = $this->model;

        # checks if the resource model (if defined) has a create method

        if($model != 'generic_model' && !method_exists($model, 'create')) :
            $model = 'generic_model';
        endif;

        $result = $this->CI->$model->create($this->params, $inputs['post']);

        $return = array();

        if ($result['bool']) {
            $return['status'] = 'OK';
            $return['record_id'] = $result['record_id'];
        } else {
            $return['status'] = 'ERROR';
        }

        if(isset($result['validation_results'])) :
            $return['validation_results'] = $result['validation_results'];
        endif;

        $return['message'] = $result['message'];

        return $return;
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * GET: Retrieves data from database / queries data / reads data
     --------------------------------------------------------------------------------------------------------------------------*/
    public function _get($inputs)
    {
        $model = $this->model;

        # checks if the resource model (if defined) has a read method
        if($model != 'generic_model' && !method_exists($model, 'read')) :
            $model = 'generic_model';
        endif;

        $return = array('status' => 'OK');

        /*
          LIMIT AND PAGINATION
         ---------------------------------------------------------------------------------------------------------------------*/
        if (isset($inputs['limit'])) {
            $request_uri = trim($this->CI->regular->request_uri(), '/');

            $this->params['limit'] = $inputs['limit'];

            # check offset ----------------------------------------------------------------------------------------------------*/
            if (isset($inputs['offset'])) :
                $this->params['offset'] = $inputs['offset'];
            else :
                $this->params['offset'] = 0;
                $request_uri .= '/0'; # adds offest to request uri if it isn't already there
            endif;

            # URI arrays --------------------------------------------------------------------------------------------------------*/
            $uri_arr = explode('/', $request_uri);
            $uri_arr_1 = $uri_arr;
            $uri_arr_2 = $uri_arr;
            $uri_arr_buttons = $uri_arr;

            # set new offset for next: current offset + limit -------------------------------------------------------------------*/
            $uri_arr_1[count($uri_arr_1) - 1] = $uri_arr_1[count($uri_arr_1) - 1] + $uri_arr_1[count($uri_arr_1) - 2];

            # set new offset for previous: current offset + limit ---------------------------------------------------------------*/
            $uri_arr_2[count($uri_arr_2) - 1] = $uri_arr_2[count($uri_arr_2) - 1] - $uri_arr_2[count($uri_arr_2) - 2];

            # in between page buttons -------------------------------------------------------------------------------------------*/
            $total_rows = $this->CI->generic_model->read(array('get_num_rows' => true, 'table' => $this->params['table']));

            # page button numbers = total table row / limit----------------------------------------------------------------------*/
            $total_page_buttons = $total_rows / $uri_arr_1[count($uri_arr_1) - 2];

            if ($total_page_buttons > 0) {
                if (is_float($total_page_buttons)) :
                    $total_page_buttons = ceil($total_page_buttons);
                endif;

                # turning uri arrays into strings and assigning them to result array-------------------------------------------------*/
                $return['pagination']['current_page_link'] = $request_uri;

                # pages
                $uri_arr_buttons[count($uri_arr_buttons) - 1] = 0;
                for ($i = 0; $i < $total_page_buttons; $i++) {
                    if ($i > 0) :
                        $uri_arr_buttons[count($uri_arr_buttons) - 1] = $uri_arr_buttons[count($uri_arr_buttons) - 1] + $uri_arr_buttons[count($uri_arr_buttons) - 2];
                    endif;
                    $link = implode('/', $uri_arr_buttons);
                    $return['pagination']['pages_links'][$i] = $link;

                    if ($return['pagination']['current_page_link'] == $link) :
                        $return['pagination']['current_page_link_index'] = $i;
                    endif;
                }

                if (isset($return['pagination']['pages_links']) && !is_null($return['pagination']['pages_links'])) {
                    $last_page_link = end($return['pagination']['pages_links']);
                    $last_page_link_arr = explode('/', $last_page_link);
                    $last_page_offset = end($last_page_link_arr);

                    # next
                    if ($uri_arr_1[count($uri_arr_1) - 1] <= $last_page_offset) :
                        $next = implode('/', $uri_arr_1);
                        $return['pagination']['next'] = $next;
                    endif;

                    # previous
                    if ($uri_arr_2[count($uri_arr_2) - 1] >= 0) :
                        $previous = implode('/', $uri_arr_2);
                        $return['pagination']['previous'] = $previous;
                    endif;
                }
            }
        }

        $allowed_clauses = array(
            'where', 'like', 'where_in',
            'where_not_in', 'or_where', 'or_where_in',
            'or_where_not_in', 'or_like', 'not_like',
            'or_not_like'
        );

        if($inputs['post'])
        {
            foreach($inputs['post'] as $key => $post)
            {
                if(in_array($key, $allowed_clauses))
                {
                    $this->params[$key] = (array)$inputs['post'][$key];
                }
            }
        }

        /*  Identifier:
         *  checking whether to get data by id or sort by field name
         * -------------------------------------------------------------------------*/
        if(!is_null($inputs['url']['id'])) {
            # checks if the id is not a numeric value and recognises it as a order_by field if true
            if (!is_numeric($inputs['url']['id'])) :
                if($inputs['url']['id'] == 'last_id') :

                    $this->params['last_id'] = true;
                    $result = $this->CI->generic_model->read($this->params)[0]->last_id;

                elseif($inputs['url']['id'] == 'num_rows') :

                    $this->params['get_num_rows'] = true;
                    $result = $this->CI->generic_model->read($this->params);

                elseif($inputs['url']['id'] == 'next_reference') :

                    $this->CI->load->library('finance');
                    $this->params['fields'] = $this->params['entity'] . '_number';
                    $result = str_pad($this->CI->finance->unique_reference($this->params), 6, '0', STR_PAD_LEFT);

                elseif($inputs['url']['id'] == 'like') :

                    $last_segment = $this->CI->regular->uri_segment('last');
                    $field = $this->CI->regular->uri_segment(-1);

                    $this->params['like'] = array($field => $last_segment);
                    $result = $this->CI->generic_model->read($this->params);

                else :

                    $this->params['order_by'] = $inputs['url']['id'] . ' ' . $inputs['order'];
                    $inputs['url']['id'] = null;
                    $result = $this->CI->$model->read($this->params, $inputs['url']['id']);

                endif;

            else :
                # check if the id exists
                $id_exists = $this->CI->regular->id_exists($this->params, $inputs['url']['id']);
                if (!$id_exists) :
                    $return['message'][] = 'id not found';
                endif;

                $result = $this->CI->$model->read($this->params, $inputs['url']['id']);

            endif;
        } elseif (isset($inputs['post']['multiple'])) # get data relating to mutiple values
        {
            if (isset($inputs['post']['multiple']['field']) && $inputs['post']['multiple']['values']) {
                if ($this->CI->generic_model->field_exists($inputs['post']['multiple']['field'], $this->params['table'])) {
                    $this->params['where_in']['field'] = $inputs['post']['multiple']['field'];
                    $this->params['where_in']['values'] = $inputs['post']['multiple']['values'];

                    $result = $this->CI->$model->read($this->params, $inputs['url']['id']);

                    if (empty($result)) :
                        $result = null;
                        $return['message'][] = 'the given multiple values rendered no results';
                    endif;

                } else {
                    $result = null;
                    $return['message'][] = 'multiple field not found';
                }
            } else {
                /* If the multiple array only contains numbers, set "id" as field
                 * and multiple array as values
                 * */
                if (isset($inputs['post']['multiple'][0])) :
                    $this->params['where_in']['field'] = 'id';
                    $this->params['where_in']['values'] = $inputs['post']['multiple'];

                    $result = $this->CI->$model->read($this->params, $inputs['url']['id']);
                else :
                    $result = null;
                    $return['message'][] = 'please ensure that "field" and "values" are indices that have values assigned to them in the multiple array';
                endif;
            }
        }
        else
        {
            $result = $this->CI->$model->read($this->params, $inputs['url']['id']);
        }

        /* Piggyback:
         * additional requested data
         ----------------------------------------------------------------------------------------------*/
        if (isset($inputs['post']['piggyback'])) {
            $return['piggyback'] = $this->CI->regular->piggyback($inputs['post']);
        }

        /* Final result:
         *
         ----------------------------------------------------------------------------------------------*/
        $return['data'] = $result;

        return $return;
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * PUT: Updates data within database
     --------------------------------------------------------------------------------------------------------------------------*/
    public function _put($inputs)
    {
        $model = $this->model;

        # checks if the resource model (if defined) has an update method
        if($model != 'generic_model' && !method_exists($model, 'update')) :
            $model = 'generic_model';
        endif;

        $this->CI->regular->check_id($inputs);

        $return['status'] = 'ERROR';

        # check if the id exists
        $id_exists = $this->CI->regular->id_exists($this->params, $inputs['url']['id']);
        if (!$id_exists) :
            $return['message'][] = 'id not found';
        else :

            if (isset($inputs['order']) && $inputs['order'] != 'ASC' && $inputs['order'] != 'DESC') :

                if (!empty($inputs['post'])) :
                    unset($inputs['post']);
                endif;

                # treats order value as field name
                $field = strtolower($inputs['order']);
                if (isset($inputs['limit'])) :
                    # treats limit as value
                    $value = urldecode($inputs['limit']);
                    $inputs['post'][$field] = $value;
                else :
                    $inputs['post'][$field] = '';
                endif;

                $inputs['post']['single_update'] = true;
            endif;

            $result = $this->CI->$model->update($this->params, $inputs['url']['id'], $inputs['post']);

            $return = array();

            if($result['bool']) :
				
				//if the entitly is a valid document update the docs activity
				if($this->params['entity'] == 'invoice' || $this->params['entity'] == 'estimate' || $this->params['entity'] == 'credit_note'){
					//get document data
					$doc_params = array(
						'table'=>$this->params['table'],
						'entity'=>$this->params['entity'],
						'items_table'=>$this->params['items_table']
					);				
					$document_data = $this->CI->template_model->read($doc_params, $inputs['url']['id'],'single');
					
					// create message based on if status was changed or the document edited
					if($inputs['post']['single_update'] == true && array_key_exists ( 'status' ,$inputs['post'])){
						$activity_message = 'marked as '.$inputs['post']['status'];
					}else{
						$activity_message = 'edited';
					}
	
					//update activity
					$a_post = array(
						'label' => ucwords(str_replace('_',' ',$this->params['entity'])) . ' #' . $document_data->{$this->params['entity'].'_number'},
						'category' => $this->params['entity'] . 's',
						'link' => $this->params['entity'] . 's/' . $document_data->id,
						'item_id' => $document_data->id,
						'type' => 'standard',
						'short_message' => $document_data->currency_symbol . $document_data->total_amount . ' '.$activity_message
					);								
					$this->CI->activities_model->create($a_post);
				}
				
				
				
                $return['status'] = 'OK';
            else :
                $return['status'] = 'ERROR';
                $error_code = 200;
                if(isset($result['error_code'])) $error_code = $result['error_code'];
                $this->CI->regular->header_($error_code);
            endif;

            if (isset($result['record_id'])) :
                $return['record_id'] = $result['record_id'];
            endif;

            if (isset($result['action'])) :
                $return['action'] = (array)$result['action'];
            endif;

            if(isset($result['validation_results'])) :
                $return['validation_results'] = $result['validation_results'];
            endif;

            $return['message'][] = $result['message'];

        endif;

        return $return;
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * DELETE: Deletes data from within database
     --------------------------------------------------------------------------------------------------------------------------*/
    public function _delete($inputs)
    {
        $model = $this->model;

        # checks if the resource model (if defined) has a delete method
        if($model != 'generic_model' && !method_exists($model, 'delete')) :
            $model = 'generic_model';
        endif;

        $return['status'] = 'ERROR';

        # check if the id exists
        $id_exists = $this->CI->regular->id_exists($this->params, $inputs['url']['id']);
        if (!$id_exists) :
            $return['message'][] = 'id not found';
        else :
            $result = $this->CI->$model->delete($this->params, $inputs['url']['id']);

            $return = array();

            if($result['bool']) :
                $return['status'] = 'OK';
            else :
                $this->CI->regular->header_(204);
            endif;

            $return['message'][] = $result['message'];
        endif;

        return $return;
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * SEND:
     --------------------------------------------------------------------------------------------------------------------------*/
    public function _send($inputs)
    {
        $return = array('status' => 'ERROR');

        # Add account name to parameters if it is set in the headers
        $headers = $this->CI->regular->get_request_headers();
        if(isset($headers['Auth'])) $this->params['account_name'] = $headers['Account-Name'];

        $this->CI->load->library('messaging');

        $this->params['entity_id'] = $inputs['url']['id'];
        $this->params['subject'] = $inputs['post']['subject'];
        $this->params['contact_email'] = $inputs['post']['contact_email'];
        $this->params['message_body'] = $inputs['post']['message_body'];
		
		if(isset($inputs['post']['attach_pdf']) && $inputs['post']['attach_pdf'] == 'yes'){
			$this->params['attach_pdf'] = true;
		}else{
			$this->params['attach_pdf'] = false;
		}
		
        $result = $this->CI->messaging->send_email2($this->params);

        if ($result['bool']) :
			//if the entitly is a valid document update the docs activity
			if($this->params['entity'] == 'invoice' || $this->params['entity'] == 'estimate' || $this->params['entity'] == 'credit_note'){
				// get doc data
				$doc_params = array(
					'table'=>$this->params['table'],
					'entity'=>$this->params['entity'],
					'items_table'=>$this->params['items_table']
				);			
				$document_data = $this->CI->template_model->read($doc_params, $this->params['entity_id'],'single');
				
				//update activity
				$a_post = array(
					'label' => ucwords(str_replace('_',' ',$this->params['entity'])) . ' #' . $document_data->{$this->params['entity'].'_number'},
					'category' => $this->params['entity'] . 's',
					'link' => $this->params['entity'] . 's/' . $document_data->id,
					'item_id' => $document_data->id,
					'type' => 'standard',
					'short_message' => $document_data->currency_symbol . $document_data->total_amount . ' emailed to '.$document_data->contact->organisation
				);								
				$this->CI->activities_model->create($a_post);
			}
			
            $return['status'] = 'OK';
        else :
            //$this->CI->regular->header_();
        endif;

        $return['contact_email'] = $result['contact_email'];
        $return['message'][] = $result['message'];

        return $return;
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * MESSAGES:
     --------------------------------------------------------------------------------------------------------------------------*/
    public function messages($resource, $id, $echo_out = true)
    {
        # allow cross origin and set allowed HTTP methods
        $this->CI->regular->set_response_headers();

        $return['status'] = 'ERROR';

        if ($this->CI->regular->request_method() == 'GET') {

            $original_resource = $resource;
            if($original_resource == 'statements') $resource = 'contacts';

            # check if the id exists
            $this->params['table'] = $this->table_prefix . $resource;

            $headers = $this->CI->regular->get_request_headers();
            $this->CI->load->library('db/switcher', array('account_name' => $headers['Account-Name']));
            $switch = $this->CI->switcher->account_db();

            $id_exists = true;
            if (!is_null($id)) :
                $this->params['where']['id'] = $id;
                $id_exists = $this->CI->generic_model->exists($this->params);
            endif;

            if (!$id_exists) :
                $return['message'][] = 'id not found';
            else :
                $this->CI->load->library('messaging');

                $params = array('resource' => $resource, 'entity_id' => $id);

                $entity = $resource;

                if (substr($resource, -1) == 's') :
                    $params['entity'] = rtrim($entity, 's');
                else :
                    $params['entity'] = $entity;
                endif;
				
				if($original_resource == 'statements') :
					 $entity = 'statement';
				else :
					$params['table'] = $this->table_prefix . $resource;
					$params['items_table'] = $this->table_prefix . $params['entity'].'_items';
				endif;
				

                $email_message = $this->CI->messaging->email_message($params);

                if ($email_message && !empty($email_message)) :
                    $return['status'] = 'OK';
                    $return['message'][] = $email_message['message'];
                    unset($email_message['message']);
                    $return['data'] = $email_message;
                else :
                    $return['message'][] = 'could not retrieve ' . $params['entity'] . ' message, please ensure resource name and id are correct';
                endif;
            endif;

        } else {
            $return['message'][] = 'only GET request method can be used along with the messages resource';
        }

        if ($echo_out == true) :
            $this->CI->regular->respond($return);
        else :
            return $return;
        endif;
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * BULK ACTION:
     --------------------------------------------------------------------------------------------------------------------------*/
    public function _bulk($resource, $field, $value)
    {
        # allow cross origin and set allowed HTTP methods
        $this->CI->regular->set_response_headers();
        $results = array('status' => 'ERROR');

        if ($this->CI->regular->valid_method()) :

            $results['status'] = 'ERROR';
            $post_vars = $this->CI->regular->decode();
            $method = $this->CI->regular->request_method();
            $response_data = array();
            $perform_action = array();
            $with_errors = array();

            # ids or post data that will  be used to determine which records will be affected in the bulk action
            $ids = null;
            $post_data = null;
            if (isset($post_vars['ids'])) :
                $ids = $post_vars['ids'];
                unset($post_vars['ids']);
            elseif (isset($post_vars['data'])) :
                $post_data = $post_vars['data'];
            endif;

            # post tp be sent with the bulk request
            $post = null;
            if (!empty($post_vars)) :
                $post = $post_vars;
            endif;

            /* Piggyback:
             * additional requested data
             ----------------------------------------------------------------------------------------------*/
            if (isset($post['piggyback']) && $method == 'GET') :
                $results['piggyback'] = $this->CI->regular->piggyback($post);
                unset($post['piggyback']);
            else :
                unset($post['piggyback']);
            endif;

            if (!is_null($ids)) {
                $data_by_id = $this->get_data_by_ids($post_data, $resource, $field, $value, $method, $post);
                $perform_action = $data_by_id['perform_action'];
                $with_errors = $data_by_id['with_errors'];
                $response_data = $data_by_id['response_data'];
            } elseif (!is_null($post_data)) {
                if (is_array($post['data'][0])) {

                    if ($resource == 'export') {
                        $post_vars['base_url'] = base_url('api') . '/';

                        // $response = $this->CI->regular->curl_request($method, 'export/' . $field, $post_vars);

                        /*------------------------------------------------------------------------*/

                        $resource = $field;
                        $this->CI->load->library('pdf/' . $resource);

                        if (isset($post_vars['data']) && !empty($post_vars['data'])) :
                            $id = $post_vars['data'];
                        endif;

                        $response = $this->CI->$resource->generate_pdf($id, 'F', $post_vars['base_url'], false);

                        /*-------------------------------------------------------------------------*/

                        $results['status'] = $response['status'];
                        $results['message'] = $response['message'];
                        if (isset($response['download'])) :
                            //$results['download'] = base_url('api/' . $response['download']);
                            $results['download'] = $response['download'];
                        endif;
                    } else {
                        foreach ($post_data as $key => $the_data) {

                            if ($this->CI->regular->request_method() == 'POST') :
                                $request_uri = $resource . '/';
                            else :
                                $rq = str_replace('bulk/' . $resource . '/', '', $this->CI->regular->request_uri()) . '';
                                $request_uri = $resource . '/' . $the_data['id'] . '/' . $rq;
                            endif;

                            $response = $this->CI->regular->curl_request($method, $request_uri, $the_data);

                            if ($response['status'] == 'OK') :
                                $perform_action[$key] = $response;
                            else :
                                $with_errors[] = $response;
                            endif;
                        }
                    }
                }
            } else {
                $results['status'] = 'ERROR';
                $results['message'] = 'no ids or post data given';
            }

            if (!empty($response_data)) :
                $results['status'] = 'OK';
                $results['data'] = $response_data;
            endif;

            if (!empty($perform_action)) :
                $results['status'] = 'OK';
                $results['results'] = $perform_action;
                $results['message'] = 'bulk action completed';
            endif;

            if (!empty($with_errors)) :
                $results['status'] = 'OK';
                $results['results_with_errors'] = $with_errors;
            endif;

        else :
            $results['message'] = 'invalid request method';
        endif;

        $this->CI->regular->respond($results);
    }

    public function get_data_by_ids($ids, $resource, $field, $value, $method, $post)
    {
        $return = array();

        foreach ($ids as $key => $id) :

            $request_uri = $resource . '/' . $id['id'] . '/';
            if (!is_null($field)) :
                $request_uri .= $field . '/';

                if (!is_null($value)) :
                    $request_uri .= $value;
                endif;
            endif;
            $response = $this->CI->regular->curl_request($method, $request_uri, $post);

            if ($response['status'] == 'OK') :
                $perform_action[$id['id']] = $response;

                if (isset($response['data'][$id['id']])) :
                    $return['response_data'][$id['id']] = $response['data'][$id['id']];
                    $return['perform_action'][$id['id']]['data'] = $response['data'][$id['id']];
                endif;

            else :
                $return['with_errors'][$id['id']] = $response;
            endif;

        endforeach;

        return $return;
    }
}