<?php

class Invoices
{
    public $model;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('invoices_model');
    }

    public function entry($id = null)
    {
        # set content type
        $this->CI->regular->header_('json');
        $response = array();

        # check if the request method is valid
        if($method_function = $this->CI->regular->valid_method())
        {
            $post_vars = $this->CI->regular->decode();

            $inputs = array();
            $inputs['url']['id'] = $id;
            $inputs['post'] = $post_vars;

            $response = $this->$method_function($inputs);
            //$this->load->library('resources/generic', $inputs);
            //$response = $this->generic->$method_function($inputs);
        }
        else
        {
            $response['status'] = 'Bad request';
            $response['message'] = 'Bad request';
        }

        $this->CI->regular->respond($response);
    }

    public function _post($inputs)
    {
        if(isset($inputs['url']['id']))
        {
            $return = array('bool'=>false, 'message'=>'cannot use the post method to update an existing record');
            return $return;
        }

        $result = $this->CI->invoices_model->create($inputs['post']);

        $return = array();

        if($result['bool']) :
            $return['status'] = 'OK';
        else :
            $return['status'] = 'not OK';
        endif;

        $return['message'] = $result['message'];

        return $return;
    }

    public function _get($inputs = null)
    {
        if(!is_null($inputs))
        {
            # checks if the id is not a numeric value and recognises it as a order_by field if true
            if(!is_numeric($inputs['url']['id'])) :
                $order_by = $inputs['url']['id'];
                $inputs['url']['id'] = null;
            else :
                $this->CI->regular->check_id($inputs);
                $order_by = null;
            endif;
        }

        $params = array(
            'id'=>$inputs['url']['id'],
            'order_by'=>$order_by
        );

        $result = $this->CI->invoices_model->read($params);

        $return = array(
            'status'=>'OK',
            'data'=>$result
        );

        return $return;
    }

    public function _put($inputs)
    {
        $this->CI->regular->check_id($inputs);

        if(!isset($inputs['url']['id']))
        {
            $return = array('bool'=>false, 'message'=>'record id not specified');
            return $return;
        }

        $result = $this->CI->invoices_model->update($inputs['url']['id'], $inputs['post']);

        $return = array();

        if($result['bool']) :
            $return['status'] = 'OK';
        else :
            $return['status'] = 'not OK';
        endif;

        $return['message'] = $result['message'];

        return $return;
    }

    public function _delete($inputs)
    {
        $this->CI->regular->check_id($inputs);

        $result = $this->CI->invoices_model->delete($inputs['url']['id']);

        $return = array();

        if($result['bool']) :
            $return['status'] = 'OK';
        else :
            $return['status'] = 'not OK';
        endif;

        $return['message'] = $result['message'];

        return $return;
    }
}