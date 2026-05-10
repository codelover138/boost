<?php

class Customers extends CI_Controller
{
    private $methods = array('POST', 'GET', 'PUT', 'DELETE');

    public function index()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        $result = array();

        $result['method'] = $method;

        header('Content-Type: application/json');

        if(in_array($method, $this->methods))
        {
            $post_vars = $this->_decode($method);

            $method_function = '_'.strtolower($method);
            $function_result = $this->$method_function($post_vars);

            header("HTTP/1.1 200 OK");

            $result['status'] = 200;
            $result['message'] = 'OK';
            $result['function'] = $method_function;
            $result['result'] = $function_result;
            $result['POST'] = $_POST;
            $result['GET'] = $_GET;
            $result['QUERY_STRING'] = $_SERVER['QUERY_STRING'];
        }
        else {
            header("HTTP/1.1 400 Bad Request");

            $result['status'] = 400;
            $result['message'] = 'Bad Request';
        }

        echo json_encode($result);
    }

    private function _decode($method)
    {
        if(empty($_SERVER['QUERY_STRING'])) :
            # get inputs and attach them to variable $post_vars
            //parse_str(http_build_query(json_decode(base64_decode(file_get_contents("php://input")), true)),$post_vars);
            parse_str(http_build_query(json_decode(file_get_contents("php://input"), true)),$post_vars);

            if($method == 'GET') :
                $_GET = $post_vars;
            elseif($method == 'POST') :
                $_POST = $post_vars;
            endif;

        else :
            //$inputs = json_decode(base64_decode($_SERVER['QUERY_STRING']));
            $inputs = json_decode(urldecode($_SERVER['QUERY_STRING']));
            $_GET = $inputs;
            $post_vars = $inputs;
        endif;

        return $post_vars;
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