<?php

class Invoices extends CI_Controller
{
    private $defined_methods;

    public function __construct()
    {
        parent::__construct();
        $this->defined_methods = $this->regular->methods;
    }

    public function index()
    {
        $method = $this->regular->request_method();

        $result = array();
        $result['method'] = $method;

        $this->regular->header_('json');

        if(in_array($method, $this->defined_methods))
        {
            $post_vars = $this->regular->decode($method);

            $method_function = '_'.strtolower($method);
            $function_result = $this->$method_function($post_vars);

            $this->regular->header_message(200);

            $result['status'] = 'OK';
            $result['message'] = 'feedback message';
            $result['function'] = $method_function;
            $result['result'] = $function_result;
            /*$result['POST'] = $_POST;
            $result['GET'] = $_GET;*/
        }
        else
        {
            $this->regular->header_message(400);

            $result['status'] = 'BAD';
            $result['message'] = 'invalid request method';
        }

        echo json_encode($result);
    }

    public function _post($post_vars)
    {
        # save data
        return $post_vars;
    }

    public function _get($post_vars)
    {
        # get data
        return $post_vars;
    }

    public function _put($post_vars)
    {
        # update data
        return $post_vars;
    }

    public function _delete($post_vars)
    {
        # delete data
        return $post_vars;
    }
}