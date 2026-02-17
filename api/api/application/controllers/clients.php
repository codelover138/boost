<?php
class Clients extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->regular->check_content_type();
    }

    public function index($id = null)
    {
        # set content type
        $this->regular->header_('json');
        $response = array();

        if(!empty(apache_request_headers()['Content-Type']))
        {
            # check if the request method is valid
            if($method_function = $this->regular->valid_method())
            {
                $post_vars = $this->regular->decode();

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
        }
        else
        {
            $response['feedback'] = 'not the way to go';
        }

        $this->regular->respond($response);
    }

    public function _post()
    {

    }

    public function _get($inputs = null)
    {
        $this->load->model('clients_model');
        $result = $this->clients_model->get_clients($inputs['url']['id']);

        $return = array(
            'status'=>'OK',
            'inputs'=>$inputs,
            'data'=>$result
        );

        return $return;
    }

    public function _put()
    {

    }

    public function _delete()
    {

    }
}