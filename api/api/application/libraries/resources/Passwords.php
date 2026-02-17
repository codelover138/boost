<?php

class Passwords
{
    public function __construct($params = null)
    {
        $this->CI =& get_instance();
        $this->params = $params;
        $this->table_prefix = $this->CI->config->item('db_table_prefix');

        if (isset($this->params['model'])) :
            $this->CI->load->model($this->params['model']);
            $this->model = $this->params['model'];
        else :
            $this->model = 'generic_model';
        endif;

        $this->CI->load->library('encrypt');

        $this->headers = $headers = $this->CI->regular->get_request_headers();
    }

    public function entry($email)
    {
        # allow cross origin and set allowed HTTP methods
        $this->CI->regular->set_response_headers();

        # initial declarations
        $this->CI->regular->header_('json');
        $response = array('status' => 'ERROR');
        $valid_methods = array('GET', 'PUT', 'SEND');

        # check if the request method is valid
        if ($this->CI->regular->valid_method($valid_methods) && !is_null($email))
        {
            $method = $this->CI->regular->valid_method($valid_methods);
            $method_function = '_' . $method;

            # decode and decrypt if string is not an email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $email_ = $this->CI->encrypt->decode(base64_decode($email));
                if (filter_var($email_, FILTER_VALIDATE_EMAIL)) $email = $email_;
            }

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                # if the email exists
                $exists = $this->CI->generic_model->exists(array(
                    'table' => $this->table_prefix . 'users',
                    'where' => array('email' => $email)
                ), true);

                if ($exists) {
                    $post_vars = $this->CI->regular->decode();

                    if (isset($post_vars['piggyback'])) unset($post_vars['piggyback']);

                    $inputs = array(
                        'post' => $post_vars,
                        'email' => $email,
                        'user_id' => $exists->id
                    );

                    $response = $this->$method_function($inputs);
                } else {
                    # Email does not exist
                    $response['message'][] = 'Supplied email address does not exist';
                }
            }
            else
            {
                # Email is invalid
                $response['message'][] = 'Invalid email supplied';
            }
        }
        else
        {
            $method = $this->CI->regular->valid_method($valid_methods);

            if (!$method) :
                $response['message'][] = 'Bad request';
            endif;

            if(strtolower($method) == 'send')
            {
                $post_vars = $this->CI->regular->decode();
                $response = $this->_send($post_vars);
            }
            else
            {
                if (is_null($email)) :
                    $response['message'][] = 'Please supply an email address';
                endif;
            }
        }

        $this->CI->regular->respond($response);
    }

    /*---------------------------------------------------------------------------------------------------------
     * GET
     * -------------------------------------------------------------------------------------------------------*/
    public function _get($inputs)
    {
        $return = array('status' => 'ERROR');

        if(isset($inputs['email']) && isset($inputs['user_id'])) {
            $return['status'] = 'OK';
            $return['email'] = $inputs['email'];
            $return['user_id'] = $inputs['user_id'];
        }
        else
        {
            $return['message'][] = 'Email not found';
        }

        return $return;
    }

    /*---------------------------------------------------------------------------------------------------------
     * PUT
     * Params:
     * password, confirm_password
     * -------------------------------------------------------------------------------------------------------*/
    public function _put($inputs)
    {
        $return = array('status' => 'ERROR');

        $this->CI->load->library('password');

        $post = $this->CI->password->check_password($inputs['post']);

        # Remove reset link if set
        if(isset($post['reset_link'])) unset($post['reset_link']);

        # Checks if password validation was a success
        if (!empty($post) && isset($post['bool']) && !$post['bool']) {
            $return['message'] = $post['message'];
            $return['validation_results'] = $post['validation_results'];
        }
        else
        {
            # Update the password
            $update_params = array(
                'table' => $this->table_prefix . 'users',
                'entity' => 'user'
            );

            $update_post['password'] = $post['password'];

            $result = $this->CI->generic_model->update($update_params, $inputs['user_id'], $update_post);

            if ($result['bool']) :
                $return['status'] = 'OK';
            else :
                $return['status'] = 'ERROR';
            endif;

            if (isset($result['record_id'])) :
                $return['record_id'] = $result['record_id'];
            endif;

            if (isset($result['validation_results'])) :
                $return['validation_results'] = $result['validation_results'];
            endif;

            $return['message'] = $result['message'];
        }

        return $return;
    }

    /*---------------------------------------------------------------------------------------------------------
     * SEND
     * -------------------------------------------------------------------------------------------------------*/
    public function _send($post)
    {
        $return = array('status' => 'ERROR');

        $email = $post['email'];

        $exists_params = array('table' => $this->table_prefix . 'users');
        $exists_params['where']['email'] = $email;
        $email_exists = $this->CI->generic_model->exists($exists_params);

        if(!$email_exists)
        {
            $return['validation_results']['email'] = 'Email does not exist';
            $return['message'][] = 'Email does not exist';
            return $return;
        }

        if(!isset($post_vars['reset_link']))
        {
            $post_vars['reset_link'] = get_protocol().$this->headers['Account-Name'].'.'.$this->CI->config->item('domain').'/login/reset ';
        }

        if (isset($post_vars['reset_link']) && $post_vars['reset_link'] != '') {
            $reset_password_link = rtrim($post_vars['reset_link']) . '/';
            $reset_password_link .= str_replace('=', '', base64_encode($this->CI->encrypt->encode($email)));
        } else {
            $return['message'][] = 'Please specify the password reset page link';
            $this->CI->regular->respond($return);
            return;
        }
		$message = '<p>You are receiving this email because a password reset was requested form your account.</p>';
		$message .= '<p>If you did not request a password reset then please ignore this email.</p>';
        $message .= '<p>To reset your password please click on the following link: <a href="'.$reset_password_link.'"> Reset your Password</a></p>';

        $subject = 'Password Reset';

        $data = array(
            'subject' => $subject,
            'heading' => $subject,
            'message' => $message
        );

        $message_to_send = $this->CI->load->view('templates/mailer', $data, true);

        # prepping the email for sending
        $this->CI->load->library('email');

        $this->CI->email->from($this->CI->config->item('from_email'), 'Boost Cloud Accounting');
        $this->CI->email->to($email);

        $this->CI->email->subject($subject);
        $this->CI->email->message($message_to_send);

        if ($this->CI->email->send()) {
            $return['status'] = 'OK';
            $return['message'][] = 'Email successfully sent';
        }
        else {
            $return['message'][] = 'Email sending failed: ' . $this->CI->email->print_debugger();
        }

        return $return;
    }
}