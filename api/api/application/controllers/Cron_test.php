<?php

class Cron_test extends CI_Controller
{
    public function index($to = 'World')
    {
        if($this->input->is_cli_request())
        {
            echo 'Hello '. $to .'!';
        }
        else
        {
            echo 'This is not a cli request';
        }
    }
}