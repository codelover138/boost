<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statement_url
{
    public $params;
    public $model;
    public $table_prefix;
    public $activities_params;
    public $invoice_params;

    public function __construct($params = null)
    {
        $this->CI =& get_instance();
        $this->params = $params;
        $this->table_prefix = $this->CI->config->item('db_table_prefix');

        $this->CI->load->model('template_model');

        $this->CI->load->model('statements_model');

        if (isset($this->params['model'])) :
            $this->CI->load->model($this->params['model']);
            $this->model = $this->params['model'];
        else :
            $this->model = 'generic_model';
        endif;

        $this->invoice_params = array(
            'table' => $this->table_prefix . 'invoices',
            'entity' => 'invoice',
            'items_table' => $this->table_prefix . 'invoice_items',
        );
    }
	
	public function entry($contact_id)
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

            $inputs = array(
                'id' => $contact_id,
                'post'=>$post_vars
            );

            $response = $this->$method_function($inputs);
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

    /*
     * PARAMS:
     * inputs(id, post(filters))
     * */
    public function _get($inputs)
    {
        $contact_id = $inputs['id'];

        # contact info
        $this->CI->load->model('contacts_model');
        $contact_params = array(
            'table' => $this->table_prefix . 'contacts',
            'entity' => 'contact'
        );
        $contact = $this->CI->contacts_model->read($contact_params, $contact_id, 'single');

        # Check for filters array
        if(isset($inputs['post']['filters']))
        {
            $filters = $inputs['post']['filters'];
        }
        else
        {
            $filters = $this->CI->statements_model->period_filter();

            $filters['start_date'] = date('Y-m-d', strtotime($filters['start_date']));
            $filters['end_date'] = date('Y-m-d', strtotime($filters['end_date']));
        }

        # documents
        $documents = $this->documents($contact_id, $filters);

        # final output
        $return = array('status' => 'OK');
        $return['contact'] = $contact;
        $return['statements'] = $documents;
        $return['filters'] = $filters;

        /* Piggyback:
         * additional requested data
         ----------------------------------------------------------------------------------------------*/
        if (isset($inputs['post']['piggyback'])) {
            $return['piggyback'] = $this->CI->regular->piggyback($inputs['post']);
        }

        return $return;
    }
	
}

?>