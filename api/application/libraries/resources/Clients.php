<?php

class Clients
{
    public $model;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('clients_model');
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
        $result = $this->CI->clients_model->create($inputs['post']);

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
        $result = $this->CI->clients_model->get_clients($inputs['url']['id']);

        $return = array(
            'status'=>'OK',
            'inputs'=>$inputs,
            'data'=>$result
        );

        return $return;
    }

    public function _put($inputs)
    {
        $result = $this->CI->clients_model->update($inputs['url']['id'], $inputs['post']);

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
        $result = $this->CI->clients_model->delete($inputs['url']['id']);

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