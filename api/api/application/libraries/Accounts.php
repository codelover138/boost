<?php

class Accounts
{
    public $table_prefix;
    public $table;
    public $org_params = array();

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('db/db_update');
		$this->account_prefix = $this->CI->config->item('db_account_prefix');
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
        $this->table = $this->table_prefix . 'organisations';

        $this->org_params = array(
            'table' => $this->table,
            'entity' => 'organisation'
        );
    }

    public function create_account($post, $strict = false)
    {
        $return = array('status' => 'ERROR');

        $valid_input = $this->validate_input($post, $strict);

        if (!$valid_input['bool']) :
            unset($valid_input['bool']);
            $valid_input['status'] = 'ERROR';
            return $valid_input;
        endif;

        //$subdomain = $this->extract_subdomain($post['account_url']);
        $post['account_name'] = preg_replace('/[^A-Za-z0-9]+/', '', strtolower($post['company_name']));
        $post['account_name'] = $this->CI->regular->unique_name($post['account_name'], 'account_name', $this->table);
        $post['account_url'] = $post['account_name'];
		
		if($post['account_name'] == false){
			$return['message'][] = 'This account name can not be used.';
			$return['validation_results']['company_name'] = 'Please enter a different company name.';
			$org_details['bool'] = false;
		}else{
			# save the organisations details
        	$org_details = $this->save_account_details($post);	
		}

        

        /*
         * BEGIN PROCESS OF CREATING NEW DB AND FINALIZING ACCOUNT
         * -----------------------------------------------------------------------------------------------------------*/
        if ($org_details['bool'])
        {
            $db_name = 'acc' . $org_details['record_id'];
           // if (strpos(base_url(), 'boostaccounting.com') !== false) {
                $db_name = $this->account_prefix . $db_name;
            //}

            # save db name to the main db
            $update_post['account_db'] = $db_name;
            $this->CI->generic_model->update($this->org_params, $org_details['record_id'], $update_post, false);

            # create new account db - this function has to be after adding the new db name to the organisations table
            if (strpos(base_url(), 'boostaccounting.com') !== false) {
                $this->CI->load->library('cpanel/new_db');
            //     $create_db = $this->CI->new_db->create($db_name);

            // } else {
            //     $create_db = $this->CI->db_update->setup_db($db_name);
            // }

            $create_db = $this->CI->new_db->create($db_name);
           // $this->CI->new_db->createSubdomain($post['account_name']);
            log_message('debug', "Database creation response (new_db->create): " . json_encode($create_db));
            } else {
            $create_db = $this->CI->db_update->setup_db($db_name);
            log_message('debug', "Database creation response (db_update->setup_db): " . json_encode($create_db));
            }
            if ($create_db) {
                # save account details to the account holder db
                $post['account_id'] = $org_details['record_id'];
                $this->save_account_details($post, $db_name);

                # create new user to the newly created db
                $this->save_new_user($post, $db_name);

                $return['status'] = 'OK';
                $return['data'] = array(
                    'account_db' => $db_name,
                    'account_name' => $post['account_name'],
                    'record_id' => $org_details['record_id']
                );
                $return['message'][] = 'New account successfully created';
            } else {
                $return['message'][] = 'Could not create new account';
            }
        } else {
            $return['message'][] = 'Could not save organisation details';
        }

        return $return;
    }

    public function save_account_details($post, $db2 = null)
    {
        $return = array('bool' => false);
        unset($post['password']);

        if (!is_null($db2)) $this->CI->db->query('use ' . $db2);

        $params = $this->org_params;

        # add organisation info to main db
        $add_organisation = $this->CI->generic_model->create($params, $post);

        if ($add_organisation['bool']) {
            $return['bool'] = true;
            $return['record_id'] = $add_organisation['record_id'];
        }

        return $return;
    }

    public function save_new_user($post, $db2)
    {
        $this->CI->db->query('use ' . $db2);

        # removed unrequired fields
        unset($post['company_name']);
        unset($post['account_name']);

        $post['password'] = md5($post['password']);
        $post['user_role_id'] = 1;
        $post['owner'] = 1;

        $params = array(
            'table' => $this->table_prefix . 'users',
            'entity' => 'user'
        );

        $result = $this->CI->generic_model->create($params, $post);

        return $result;
    }

    public function validate_input($post, $strict = true)
    {
     //   print_r($post);
      //  die();
        $return = array('bool' => false);
        $mandatory = array();
        $missing_fields = array();

        # the following values are mandatory and need to be unique:
        if ($strict) {
            if (!isset($post['account_url']) || $post['account_url'] == '') :
                $mandatory['account_url'] = null;
                $missing_fields['account_url'] = 'Please supply the above field';
            else :
                $mandatory['account_url'] = $post['account_url'];
            endif;
        }

        if (!isset($post['email']) || $post['email'] == '') :
            $mandatory['email'] = null;
            $missing_fields['email'] = 'Please supply the above field';
        else :
            $mandatory['email'] = $post['email'];
        endif;

        if (!isset($post['company_name']) || $post['company_name'] == '') :
            $mandatory['company_name'] = null;
            $missing_fields['company_name'] = 'Please supply the above field';
        else :
            $mandatory['company_name'] = $post['company_name'];
        endif;

        if (!empty($missing_fields)) {
            $return['message'][] = 'There are missing fields that are required';
            $return['validation_results'] = $missing_fields;
            return $return;
        }

        # validate email:
        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $return['message'][] = 'Invalid email';
            $return['validation_results']['email'] = 'Invalid email';
            return $return;
        }

        $table = $this->table;

        $exists_arr = array();

        foreach ($mandatory as $field => $value)
        {
            if($field != 'company_name')
            {
                $params = array(
                    'table' => $table,
                    'field' => $field,
                    'value' => $value
                );
                $value_exists = $this->CI->generic_model->value_exists($params);

                if ($value_exists) :
                    $exists_arr[$field] = $value;
                endif;
            }
        }

        if (!empty($exists_arr)) {
            /*if (isset($exists_arr['company_name'])) :
                $return['message'][] = 'This company name is already used for another account';
            endif;*/

            if (isset($exists_arr['email'])) :
                $return['message'][] = 'This email address is already used for another account';
                //$return['message'][] = json_encode($post);
            endif;

           // $return['validation_results'] = $exists_arr;
            $return['bool'] = true;
            return $return;
        }
        else {
            $return['bool'] = true;
            return $return;
        }
    }
}