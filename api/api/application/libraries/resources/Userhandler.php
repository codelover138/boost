<?php

class Userhandler
{
    public $user_tokens_params;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->table_prefix = $this->CI->config->item('db_table_prefix');

        $this->user_tokens_params = array(
            'table' => $this->table_prefix . 'user_tokens',
            'entity' => 'user token'
        );
    }

    public function confirm_account()
    {
        $headers = $this->CI->regular->get_request_headers();
        $requested_resource = $this->CI->regular->requested_resource();
        
        $response = array('status' => 'ERROR');

        if (!isset($headers['Account-Name'])) :
            if($requested_resource === 'admin' || $requested_resource === 'me') {
                // Allow bypass for these resources if no account name is provided (Super Admin flow)
                return true;
            }
            $response['message'][] = 'Account-Name header not found';
            $this->CI->regular->header_(401);
            $this->CI->regular->respond($response);
            die();
        endif;

        $this->CI->load->library('db/switcher', array('account_name' => $headers['Account-Name']));
        $switch = $this->CI->switcher->account_db();

        if (!$switch) :
            $response['message'][0] = 'Account does not exist';
            $this->CI->regular->header_(401);
            $this->CI->regular->respond($response);
            die();
        endif;
    }

    public function validate_login_input($input)
    {
       
        $compuslory_items = array('account_name', 'email', 'password');

        $response = array('status' => 'ERROR');

        foreach ($compuslory_items as $compuslory_item) :
            if (!array_key_exists($compuslory_item, $input)) :
                $response['validation_results'][$compuslory_item] = 'Please add ' . $compuslory_item;
            endif;
        endforeach;

        if (!empty($response['validation_results'])) {
            $response['message'][] = 'Error logging in as there were some missing values';

            $this->CI->regular->respond($response);
            die();
        } else {
            return true;
        }
    }

    public function login($input)
    {
        $this->validate_login_input($input);

        $this->CI->load->library('db/switcher', array('account_name' => $input['account_name']));
        $this->CI->switcher->account_db();

        
        $email = $input['email'];
        $password = $input['password'];
        
        $session_id = null;
        if (isset($input['session_id'])) $session_id = $input['session_id'];

        # return array for holding results to be issued
        $return = array(
            'status' => 'ERROR',
            'message' => array('Invalid login details')
        );

        if (is_null($session_id)) :
            $session_id = $this->generate_session_id();
        endif;

        # default http code is set to 401 Authentication Error
        $http_code = 401;

        # user data params
        $params = array(
            'table' => $this->table_prefix . 'users',
            'entity' => 'user',
            'where' => array(
                'email' => $email
            )
        );

        $result = $this->CI->generic_model->read($params);
        
        # Diagnostic: Log email being searched and result count
        if (!empty($result)) {
            $first = $result[0];
            $expected_hash = md5($password);
        }

        if (!empty($result)) {
            # Success indicator and user data holder
            $user_id = null;
            $user_data = null;
            
            # loop through results to find matching password
            foreach ($result as $res) {
                if ($res->password == md5($password)) {
                    $user_id = $res->id;
                    $user_data = $res;
                    break;
                }
            }

            if ($user_id) {
                # update post array for updating user record
                $update_post = array(
                    'last_attempt_datetime' => current_datetime(),
                    'last_activity' => current_datetime(),
                    'failed_attempts' => 0
                );

                $http_code = 200;

                # generate token
                $token_data = $this->generate_token($user_id, $session_id);

                # set response status to OK
                $return['status'] = 'OK';
                $return['token'] = $token_data['token'];
                $return['token_expire'] = $token_data['token_expire'];
                $return['session_id'] = $token_data['session_id'];
                $return['message'][0] = 'login successful';
                $return['user_data'] = $user_data;

                unset($params['where']);
                $this->CI->generic_model->update($params, $user_id, $update_post);
            } else {
                # Update failed attempts for first user found with this email
                $first_user = $result[0];
                $this->CI->generic_model->update($params, $first_user->id, array('failed_attempts' => $first_user->failed_attempts + 1));
            }
        }

        # http header response code
        $this->CI->regular->header_($http_code);
        $this->CI->regular->respond($return);
    }

    public function logout($token)
    {
        $return = array(
            'status' => 'ERROR',
            'message' => array('user not logged in')
        );

        $headers = $this->CI->regular->get_request_headers();

        if(!isset($headers['Account-Name']))
        {
            $return['message'][0] = 'Account-Name header not found';
        }
        else
        {
            $this->CI->load->library('db/switcher', array('account_name' => $headers['Account-Name']));
            $this->CI->switcher->account_db();


            $params = $this->user_tokens_params;
            $params['where']['token'] = $token;

            $result = $this->CI->generic_model->exists($params, true);

            if (!empty($result)) {
                # determine the user's ID
                $token_id = $result->id;

                unset($params['where']);

                $delete = $this->CI->generic_model->delete($this->user_tokens_params, $token_id);

                if ($delete['bool']) :
                    $return['status'] = 'OK';
                    $return['message'] = array('user has been logged out');
                endif;
            }
        }

        $this->CI->regular->respond($return);
    }

    public function validate_token($token, $session_id)
    {
        if (is_null($session_id)) :
            $session_id = $this->generate_session_id();
        endif;

        $return = array(
            'status' => 'ERROR',
            'message' => array('Invalid token or session id')
        );

        $http_code = 498;

        # user data params
        $params = $this->user_tokens_params;
        $params['where'] = array(
            'session_id' => $session_id,
            'token' => $token,
            'token_expire >' => current_datetime()
        );

        $result = $this->CI->generic_model->read($params, null, 'single');

        if (!empty($result))
        {
            $http_code = 200;
            $return['status'] = 'OK';
            $return['message'][0] = 'Valid token';

            # set token expiry to one hour from current time
            $result->token_expire = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $token_data = (array)$result;

            $this->update_token_data($token_data, $session_id);
        }

        $this->CI->regular->header_($http_code);

        return $return;
    }

    public function valid_token()
    {
        $headers = $this->CI->regular->get_request_headers();

        $errors = array();

        if (!isset($headers['Auth'])) {
            $errors['status'] = 'ERROR';
            $errors['message'][] = 'Auth header not found';

            $this->CI->regular->header_(401);
        }

        if (!isset($headers['Account-Name'])) {
            $errors['status'] = 'ERROR';
            $errors['message'][] = 'Account-Name header not found';

            $this->CI->regular->header_(401);
        }

        if (!isset($headers['Session'])) {
            $errors['status'] = 'ERROR';
            $errors['message'][] = 'Session header not found';
        }

        if (!empty($errors)) {
            $this->CI->regular->respond($errors);
            die();
        }

        /*
         * If all is well
         * -----------------------------------------------------------------------------------------------------------*/
        if (isset($headers['Auth'])) {
            $token = $headers['Auth'];

            $this->CI->load->library('db/switcher', array('account_name' => $headers['Account-Name']));
            $this->CI->switcher->account_db();

            if (isset($headers['Session'])) {
                $session_id = $headers['Session'];
                $result = $this->validate_token($token, $session_id);

                if ($result['status'] == 'OK') :
                    return true;
                else :
                    //redirect('error');
                    $this->CI->regular->respond(array(
                        'status' => 'ERROR',
                        'message' => array('Invalid token')
                    ));
                    $this->CI->regular->header_(498);
                    die();
                endif;
            } else {
                $this->CI->regular->respond(array(
                    'status' => 'ERROR',
                    'message' => array('Session header not found')
                ));
                $this->CI->regular->header_(401);
                die();
            }
        } else {
            $this->CI->regular->respond(array(
                'status' => 'ERROR',
                'message' => array('Auth header not found')
            ));
            $this->CI->regular->header_(401);
            die();
        }
    }

    public function generate_token($user_id, $session_id = null)
    {
        $token_data = array('user_id' => $user_id);

        # generate token
        $token_data['token'] = bin2hex(openssl_random_pseudo_bytes(16));
        $token_data['token_expire'] = date('Y-m-d H:i:s', strtotime('+1 hour'));//the expiration date will be in eight hours from the current moment

        if (!is_null($session_id)) :
            $token_data['session_id'] = $session_id;
        else :
            $token_data['session_id'] = $this->generate_session_id(); # adding session id as one of the things to be saved
        endif;

        $save_token = $this->save_token_data($token_data, $session_id);
        

        $return = array_merge($token_data, $save_token);

        return $return;
    }

    public function generate_session_id()
    {
        session_start(); # start new session
        $session_id = session_id(); # save the current session id
        session_destroy(); # destroy the current session and all info associated with it

        return $session_id;
    }

    public function save_token_data($token_data, $session_id = null)
    {
        $params = $this->user_tokens_params;
        $params['where']['session_id'] = $session_id;

        $user_token_data = $this->CI->generic_model->exists($params, true);

        if (!is_null($session_id) && $user_token_data) :
            $result = $this->update_token_data($token_data, $session_id);
        else :
            $result = $this->CI->generic_model->create($this->user_tokens_params, $token_data);
        endif;

        return $result;
    }

    public function update_token_data($token_data, $session_id)
    {
        $params = $this->user_tokens_params;
        $params['where']['session_id'] = $session_id;

        $user_token_data = $this->CI->generic_model->exists($params, true);

        # update relevant user tokens row
        if ($user_token_data) :
            $update = $this->CI->generic_model->update($params, $user_token_data->id, $token_data);
        else :
            $update = false;
        endif;
        return $update;
    }

    public function valid_permission($uri = null)
    {
        $this->CI->load->model('generic_model');
        $permissions = $this->CI->generic_model->get_role_permissions();
        $uri_string = ltrim(uri_string(), '/');

        if(isset($uri)) :
            $uri_string = $uri;
        endif;

        $alt = $this->CI->uri->segment(1) . '/' . $this->CI->uri->segment(2);

        if (in_array($uri_string, $permissions) || in_array($alt, $permissions)) :
            return true;
        else :
            return false;
        endif;
    }

    public function determine_user()
    {
        $headers = $this->CI->regular->get_request_headers();

        $return = array('bool'=>false);

        if(!isset($headers['Auth']))
        {
            $return['message'][] = 'Auth header not found';
        }
        else
        {
            if (isset($headers['Account-Name'])) {
                $this->CI->load->library('db/switcher', array('account_name' => $headers['Account-Name']));
                $this->CI->switcher->account_db();
            }

            $token = $headers['Auth'];

            $user_tokens_params = array(
                'table' => $this->table_prefix . 'user_tokens',
                'entity' => 'tokens',
                'where' => array('token' => $token)
            );

            $token_data = $this->CI->generic_model->read($user_tokens_params, null, 'single');

            if(!empty($token_data))
            {
                # Get relevant user data
                $user_params = array(
                    'table' => $this->table_prefix . 'users',
                    'entity' => 'user'
                );

                $this->CI->load->model('users_model');
                $user_data = $this->CI->users_model->read($user_params, $token_data->user_id, 'single');

                if(!empty($user_data))
                {
                    $user_data->token = $token_data;

                    $return['bool'] = true;
                    $return['data'] = $user_data;

                    $org_params = array(
                        'table' => $this->table_prefix . 'organisations',
                        'entity' => 'organisation',
                        'fields' => 'company_name'
                    );
                    $org_data = $this->CI->generic_model->read($org_params, 1, 'single');
                    $return['data']->company_name = $org_data->company_name;
                }
                else
                {
                    $return['message'][] = 'User data not found';
                }
            }
            else
            {
                $return['message'][] = 'Token data not found';
            }
        }

        return $return;
    }
}