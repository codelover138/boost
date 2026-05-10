<?php

class Payment_reminders extends CI_Controller
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
        $this->load->library('user_agent');

        if (!$this->agent->is_browser())
        {
            $this->load->library('reminders');
            $results = $this->reminders->send();

            if(!empty($results)) :
                foreach($results as $db => $result) :
                    foreach($result as $email => $data)
                        echo $db .', '. $email . ', ' . $data['date_of_doc_creation'] .', ' .$data['invoice_id'].', '.$data['invoice_number']. ': ' . $data['send_result']['message'][0] . "\n\r";
                endforeach;
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