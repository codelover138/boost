<?php

class Registrations
{
    public $params;
    public $model;
    public $table_prefix;
    public $activities_params;
	public $disallowed_account_names;

    public function __construct($params = null)
    {
        $this->CI =& get_instance();
        $this->params = $params;
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
        $this->activities_params = array('table' => $this->table_prefix . 'activites', 'entity' => 'activity');
		$this->disallowed_account_names = $this->CI->config->item('disallowed_account_names');

        if (isset($this->params['model'])) :
            $this->CI->load->model($this->params['model']);
            $this->model = $this->params['model'];
        else :
            $this->model = 'generic_model';
        endif;

        $this->CI->load->library('encrypt');
    }

    public function entry($email = null)
    {
		
		# allow cross origin and set allowed HTTP methods
        $this->CI->regular->set_response_headers();

        # set content type
        $this->CI->regular->header_('json');
        $response = array();

        $entry_params = array(
            'resource' => calling_function()
        );
		
		# get sent data        
		$sent_data = $this->CI->regular->decode();
		
		if(isset($sent_data['signup_token'])){
			$post_decrypt = json_decode($this->CI->encrypt->decode(base64_decode($sent_data['signup_token'])));
			$post['email'] = $post_decrypt->email;
			$post['company_name'] = $post_decrypt->company_name;
			$this->params['post']['email'] = $post_decrypt->email;
			$this->params['post']['company_name'] = $post_decrypt->company_name;
		}else{
			$post = $sent_data;
		}
		
		
		//print_r('this is random');
		//var_dump($this->CI->regular->decode());
		//exit;


        # check if the request method is valid
        if ($method = $this->CI->regular->valid_method()) {
            $entry_params['method'] = $method;
            $method_function = '_' . strtolower($method);

            /*
             * IS ALLOWED:
             * Checking if the request method is permitted
             * -------------------------------------------------------------------------------------------------------*/
            $is_allowed = $this->CI->regular->is_allowed($method, $entry_params['resource']);
            if (!$is_allowed) {
                $this->CI->regular->header_(405);
                $this->CI->regular->respond(
                    array(
                        'status' => 'ERROR',
                        'message' => array($method . ' method is not allowed on ' . $entry_params['resource'])
                    ));
                return;
            }
            /*--------------------------------------------------------------------------------------------------------*/

            /*
             * VALIDATING ENCRYPTED EMAIL
             * 
             * -------------------------------------------------------------------------------------------------------*/
            
			$email = $post['email'];

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                # if the email exists
                $exists = $this->CI->generic_model->exists(array(
                    'table' => $this->table_prefix . 'organisations',
                    'where' => array('email' => $email)
                ), true);

                if ($exists) {
                    $post_vars = $post;

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
            } else {
                # Email is invalid
                $response['message'][] = 'Invalid email supplied';
            }


            # check if theres post set within user defined params
            if (isset($this->params['post'])) :
                $post_vars = $this->params['post'];
                unset($this->params['post']);
            else :
                $post_vars = $sent_data;
            endif;

            # input to be sent to one of the four request methods
            $inputs = array();
            $inputs['post'] = $post_vars;
			

            if ($this->CI->regular->request_method() != 'GET') {
                # unsets piggyback post in method used is not GET
                if (isset($inputs['post']['piggyback'])) :
                    unset($inputs['post']['piggyback']);
                endif;

                # unsets multiple post in method used is not GET
                if (isset($inputs['post']['multiple'])) :
                    unset($inputs['post']['multiple']);
                endif;
            }

            $response = $this->$method_function($inputs);
        } else {
            $response['status'] = 'ERROR';
            $response['message'][] = 'Bad request';
            $this->CI->regular->header_(400);
        }

        $this->CI->regular->respond($response);
    }

    public function _post($inputs)
    {
        $this->CI->load->library('accounts');

        $inputs['post']['password'] = $this->random_password();

        $create = $this->CI->accounts->create_account($inputs['post']);

        if ($create['status'] == 'OK') :
            $inputs['post']['new_account_link'] = get_protocol() . $create['data']['account_name'] . '.' . $this->CI->config->item('domain') .'/settings';//$_SERVER['SERVER_NAME'];
            $this->send_email($inputs['post']);
			
			$create['data']['tmppass'] = $inputs['post']['password'];
			$create['data']['email'] = $inputs['post']['email'];
			
        endif;

        return $create;
    }
	
	 /*---------------------------------------------------------------------------------------------------------
     * SEND 
	 * this is the functionality for
	 * -> the initial verification link sent to the email address provided
     * -------------------------------------------------------------------------------------------------------*/
	
	 public function _send($sent_data)
    {
       	
		$inputs =  $sent_data['post'];  
	    
		# allow cross origin and set allowed HTTP methods
        $this->CI->regular->set_response_headers();

        # set content type
        $this->CI->regular->header_('json');
        $response = array();
		
	    $response['status'] = 'OK';

        if (isset($inputs['email']) && filter_var($inputs['email'], FILTER_VALIDATE_EMAIL) ) {
			
			$email = $inputs['email'];
			
			# if the email exists
			$exists = $this->CI->generic_model->exists(array(
				'table' => $this->table_prefix . 'organisations',
				'where' => array('email' => $email)
			), true);
			
			if($exists){
				$response['status'] = 'ERROR';
				$response['message'][] = 'This email address is associated with another account.';
				$response['validation_results']['email'] = 'Please specify a different email address.';
			}else{
				$encode_array['email'] = $email;
			}
			
		}else{
			# Email is invalid
			$response['status'] = 'ERROR';
			$response['message'][] = 'Please enter a valid email address';
			$response['validation_results']['email'] = 'Email address required';
		}
		
		if(isset($inputs['company_name'])){
			
			#if the companu name exits then create a inique name and check it is allowed
			$company_name  = $this->CI->regular->unique_name($inputs['company_name'], 'account_name', $this->table_prefix.'organisations');
			
			# if the nameis not allowed (returend false) then create the response errors
			if($company_name == false){
				$response['status'] = 'ERROR';
				$return['message'][] = 'This account name can not be used.';
				$return['validation_results']['company_name'] = 'Please enter a different company name.';				
			}else{
				$encode_array['company_name'] = $inputs['company_name'];
			}
			
		}else{
			$response['status'] = 'ERROR';
            $response['message'][] = 'Please enter a company Name';
			$response['validation_results']['company_name'] = 'Company name required';
		}
	   
	   
		if ($response['status'] == 'OK') :
			$encode_array['date_time'] = date("H:i:s");
			$url_string = str_replace('==','',base64_encode($this->CI->encrypt->encode(json_encode($encode_array))));
			$response['url_string'] = $url_string;
            $inputs['new_account_link'] = get_protocol() . 'app.'.$this->CI->config->item('domain').'/signup/verify/'.$url_string;//$_SERVER['SERVER_NAME'];
            $this->send_verification_email($inputs);
        endif;

        return $response;
    }
	
	/*---------------------------------------------------------------------------------------------------------
     * SEND VERIFICATION EMAIL
     * -------------------------------------------------------------------------------------------------------*/
    public function send_verification_email($post)
    {
        $return = array('status' => 'ERROR');

        $email = $post['email'];

        if (isset($post['new_account_link']) && $post['new_account_link'] != '') {
            $new_account_link = rtrim($post['new_account_link']);
        } else {
            $return['message'][] = 'An error occurred. Please try again';
            return $return;
        }
			
		$email_data['subject'] = 'Boost Account Verification';
		$email_data['heading'] = 'Boost Account Verification';
        $email_data['message'] = '<p> To complete the creation of your new account, please verify your details by clicking the following link:</p>';		
		$email_data['link'] = $new_account_link;
		$email_data['link_entity_text'] = 'Verify Your Account';
		$email_data['link_pretext'] = false;
		
		$message = $this->CI->load->view('templates/mailer', $email_data, true);
		
        # prepping the email for sending
        $this->CI->load->library('email');

        $this->CI->email->from($this->CI->config->item('from_email'), 'Boost Cloud Accounting');
        $this->CI->email->to($email);

        $this->CI->email->subject($email_data['subject']);
        $this->CI->email->message($message);

        if ($this->CI->email->send()) {
            $return['status'] = 'OK';
            $return['message'][] = 'Email successfully sent';
        } else {
            $return['message'][] = 'Email sending failed: ' . $this->CI->email->print_debugger();
        }

        return $return;
    }

    /*---------------------------------------------------------------------------------------------------------
     * SEND ACCOUNT CREATED EMAIL
     * -------------------------------------------------------------------------------------------------------*/
    public function send_email($post)
    {
        $return = array('status' => 'ERROR');

        $email = $post['email'];

        if (isset($post['new_account_link']) && $post['new_account_link'] != '') {
            $new_account_link = rtrim($post['new_account_link']) . '/';
            //$new_account_link .= str_replace('=', '', base64_encode($this->CI->encrypt->encode($email)));
        } else {
            $return['message'][] = 'Please specify the new account page link';
            //$this->CI->regular->respond($return);
            return $return;
        }
		
		$email_data['subject'] = 'New Account Created';
		$email_data['heading'] = 'Congratulations! Your account has been created.';       		
		$email_data['link'] = $new_account_link;
		$email_data['link_entity_text'] = 'Login to Boost';
		$email_data['link_pretext'] = false;
		
		$email_data['message'] = '<p>To login to your account please go to: <a href="' . $new_account_link . '">' . $new_account_link . '</a></p>';
		$email_data['message'] .= '<p>Your temporary password is ' . $post['password'] . '</p><br />Please change your password once logged in successfully.';
        
        $message = $this->CI->load->view('templates/mailer', $email_data, true);

        # prepping the email for sending
        $this->CI->load->library('email');

        $this->CI->email->from($this->CI->config->item('from_email'), 'Boost Cloud Accounting');
        $this->CI->email->to($email);

        $this->CI->email->subject($email_data['subject']);
        $this->CI->email->message($message);

        if ($this->CI->email->send()) {
            $return['status'] = 'OK';
            $return['message'][] = 'Email successfully sent';
        } else {
            $return['message'][] = 'Email sending failed: ' . $this->CI->email->print_debugger();
        }

        //$this->CI->regular->respond($return);
        return $return;
    }

    public function random_password($password_length = 8)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $password_length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}