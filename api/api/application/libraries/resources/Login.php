<?php

class Login
{
    public $table = 'boost_users';
    public $table_prefix;
    public $user_tokens_params;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->table_prefix = $this->CI->config->item('db_table_prefix');

        $this->user_tokens_params = array(
            'table' => $this->table_prefix . 'users_tokens',
            'entity' => 'user token'
        );
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * ENTRY: This is where requests get designated to a process
     --------------------------------------------------------------------------------------------------------------------------*/
    public function entry($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        # allow cross origin and set allowed HTTP methods
        $this->CI->regular->set_response_headers();

        # set content type
        $this->CI->regular->header_('json');
        $response = array();

        if (!is_null($order)) :
            $order = strtoupper($order);
        endif;

        # check if the request method is valid
        if ($method = $this->CI->regular->valid_method())
        {
            $method_function = '_' . $method;
            # check if theres post set within user defined params
            if (isset($this->params['post'])) :
                $post_vars = $this->params['post'];
                unset($this->params['post']);
            else :
                $post_vars = $this->CI->regular->decode();
            endif;

            # input to be sent to one of the four request methods
            $inputs = array();
            $inputs['url']['id'] = $id;
            $inputs['post'] = $post_vars;
            $inputs['order'] = $order;

            if (isset($inputs['post']['field'])) :
                $this->params['field'] = $inputs['post']['field'];
            endif;

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

            if (!is_null($offset)) :
                $inputs['offset'] = $offset;
            endif;

            if (!is_null($limit)) :
                $inputs['limit'] = $limit;
            endif;

            $response = $this->$method_function($inputs);
        } else
        {
            $response['status'] = 'Bad request';
            $response['message'] = 'Bad request';
        }

        $this->CI->regular->respond($response);
    }

    public function login($email, $password)
    {
        # return array for holding results to be issued
        $return = array(
            'status' => 'ERROR',
            'message' => array('invalid login details')
        );

        # default http code is set to 401 Authentication Error
        $http_code = 401;

        # user data params
        $params = array(
            'table' => 'boost_users',
            'entity' => 'user',
            'where' => array(
                'email' => $email
            )
        );

        $result = $this->CI->generic_model->read($params);

        # if there were results
        if (!empty($result))
        {
            # determine the user's ID
            $user_id = '';
            foreach ($result as $key => $res) :
                $user_id = $res->id;
            endforeach;

            # update post array for updating user record
            $update_post = array(
                'last_attempt_datetime' => current_datetime()
            );

            # check if given user password matches the password saved in the database
            if ($result[$user_id]->password == md5($password))
            {
                $http_code = 200;

                # generate token if password is correct
                $token_data = $this->generate_token($user_id);

                # added values to be updated to update post array
                $update_post['token'] = $token_data['token'];
                $update_post['token_expire'] = $token_data['token_expire'];
                $update_post['failed_attempts'] = 0;

                # set response status to OK
                $return['status'] = 'OK';

                # add retun values to return array
                $return['token'] = $token_data['token'];
                $return['token_expire'] = $token_data['token_expire'];
                $return['message'][0] = 'login successful';
            }
            else
            {
                $update_post['failed_attempts'] = $result[$user_id]->failed_attempts + 1;
            }

            unset($params['where']);
            $this->CI->generic_model->update($params, $user_id, $update_post);
        }

        # http header response code
        $this->CI->regular->header_($http_code);
        log_message('error', 'testtst');

        //return $return;
        $this->CI->regular->respond($return);
    }

    public function logout($token)
    {
        $return = array(
            'status' => 'ERROR',
            'message' => array('user not logged in')
        );

        # user data params
        $params = array(
            'table' => 'boost_users',
            'entity' => 'user',
            'where' => array(
                'token' => $token
            )
        );

        $result = $this->CI->generic_model->read($params);

        if (!empty($result))
        {
            # determine the user's ID
            $user_id = '';
            foreach ($result as $key => $res) :
                $user_id = $res->id;
            endforeach;

            $logout_token = bin2hex(openssl_random_pseudo_bytes(16));
            $update_post = array();
            $update_post['token'] = $logout_token;
            $update_post['token_expire'] = '0000-00-00';

            unset($params['where']);

            $update = $this->CI->generic_model->update($params, $user_id, $update_post);

            if ($update['bool']) :
                $return['status'] = 'OK';
                $return['message'] = array('user has been logged out');
            endif;

        }

        $this->CI->regular->respond($return);
    }

    public function validate_token($token)
    {
        $return = array(
            'status' => 'ERROR',
            'message' => array('invalid token')
        );

        $http_code = 401;

        # user data params
        $params = array(
            'table' => 'boost_users',
            'entity' => 'user',
            'where' => array(
                'token' => $token,
                'token_expire >' => current_datetime()
            )
        );

        $result = $this->CI->generic_model->read($params, null, 'zero');

        if (!empty($result)) :
            $http_code = 200;
            $return['status'] = 'OK';
            $return['message'][0] = 'valid token';
        endif;

        $this->CI->regular->header_($http_code);

        return $return;
    }

    public function valid_token()
    {
        $headers = $this->CI->regular->get_request_headers();

        $token = $headers['Authorization'];
        $result = $this->validate_token($token);

        if ($result['status'] == 'OK') :
            return true;
        else :
            redirect('error');
        endif;
    }

    public function generate_token($user_id)
    {
        $token_data = array();

        # generate token
        $token_data['token'] = bin2hex(openssl_random_pseudo_bytes(16));
        $token_data['token_expire'] = date('Y-m-d H:i:s', strtotime('+8 hours'));//the expiration date will be in eight hours from the current moment

        $save_token = $this->save_token_data($user_id);

        $return = array_merge($token_data, $save_token);

        return $return;
    }

    public function save_token_data($token_data)
    {
        $result = $this->CI->generic_model->create($this->user_tokens_params, $token_data);
        return $result;
    }

    public function update_token_data($token_data, $user_id)
    {
        $result = $this->CI->generic_model->update($this->user_tokens_params, $user_id, $token_data);
        return $result;
    }
}