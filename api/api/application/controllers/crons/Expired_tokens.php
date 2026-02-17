<?php

class Expired_tokens extends CI_Controller
{
    public function index()
    {
        $this->help();
    }

    public function help()
    {
        if ($this->input->is_cli_request()) :
            echo 'This file clears out expired tokens';
        else :
            echo 'Cannot access this file from a web browser';
        endif;
    }

    public function remove()
    {
        $this->load->model('user_tokens_model');

        $result = $this->user_tokens_model->delete();

        if ($this->input->is_cli_request()) {
            if ($result['bool']) {
                unset($result['bool']);
                foreach ($result as $db => $info) {
                    $affected_rows = $info['affected_rows'];
                    if ($affected_rows > 0) :
                        echo $db . ': ' . $info['message'][0] . '. ' . $affected_rows . ' rows affected.' . "\n\r";
                    else :
                        echo $db . ': ' . 'No records were deleted' . "\n\r";
                    endif;
                }
            } else {
                echo $result['message'][0];
            }
        } else {
            echo 'Cannot access this file from a web browser';
        }
    }
}