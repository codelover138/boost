<?php

class Activities
{
    public $table_prefix;
	public $params;

    public function __construct($params = null)
    {
        $this->CI =& get_instance();
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
		$this->params = $params;
		$this->CI->load->model('activities_model');
    }

    public function entry($category, $id)
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
			
			$post_vars = $this->CI->regular->decode();

            $response['request']['data'] = $this->_get($post_vars, $category, $id);
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

    public function _get($post_vars, $category, $id)
    {
        
		$params = $this->params;

		if(isset($category)){			
			$params['where']['category'] = $category;
		}
		
		if(isset($id)){			
			$params['where']['item_id'] = $id;
		}
		
		if(isset($post_vars['offset']) /*&& is_numeric($post_vars['offset'])*/){			
			$params['offset'] = $post_vars['offset'];			
		}
		
		if(isset($post_vars['limit']) /*&& is_numeric($post_vars['limit'])*/){			
			$params['limit'] = $post_vars['limit'];
		}else{
			$params['limit'] = $this->CI->config->item('activities_limit');
		}
		
		$params['order_by'] = 'id DESC';

        $result = $this->CI->generic_model->read($params);
		//unset($result['user_data']);
		
		rsort($result);
		
		return $result;
    }
}