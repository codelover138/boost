<?php

class Error extends CI_Controller
{
    public function index()
    {
        $this->regular->set_response_headers();
        $this->regular->header_(401);
        $result = array('status' => 'ERROR', 'message' => array('invalid token'));
        $this->regular->respond($result);
    }
}