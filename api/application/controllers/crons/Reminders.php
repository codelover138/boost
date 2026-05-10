<?php

class Reminders extends CI_Controller
{
    public function index()
    {
        $this->help();
    }

    public function help()
    {
        if ($this->input->is_cli_request()) :
            echo 'This file sends out payment reminders';
        else :
            echo 'Cannot access this file from a web browser';
        endif;
    }

    public function send()
    {
        if (!$this->input->is_cli_request())
        {
            $this->load->library('reminders2');
            $results = $this->reminders2->send();

            if(!empty($results)) :
                foreach($results as $email => $result) echo $email . ': ' . $result['message'][0];
            else :
                echo 'No reminders found to be sent today';
            endif;
        }
        else
        {
            echo 'Cannot access this file from a web browser';
        }
    }
}