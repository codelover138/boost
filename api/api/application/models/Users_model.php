<?php

class Users_model extends CI_Model
{
    public $result_message;
    public $error_fields;
    public $validation_results;
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->table_prefix = $this->config->item('db_table_prefix');
        $this->load->model('role_permissions_model');
        $this->load->library('password');
    }

    public function create($params, $post)
    {
        $result = array('bool' => false, 'message' => array(), 'validation_results' => array());

        // Validate required fields first so errors are never overwritten
        if (empty($post['first_name'])) {
            $result['message'][] = 'Please enter a first name';
            $result['validation_results']['first_name'] = 'First name is required';
        }

        if (empty($post['last_name'])) {
            $result['message'][] = 'Please enter a last name';
            $result['validation_results']['last_name'] = 'Last name is required';
        }

        if (empty($post['email'])) {
            $result['message'][] = 'Please enter an email address';
            $result['validation_results']['email'] = 'Email address is required';
        } elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $result['message'][] = 'Please enter a valid email address';
            $result['validation_results']['email'] = 'Please enter a valid email address';
        }

        if (empty($post['user_role_id']) || $post['user_role_id'] == '0') {
            $result['message'][] = 'Please select a role for this user';
            $result['validation_results']['user_role_id'] = 'Please select a role for this user';
        } else {
            $user_roles = $this->generic_model->read(array(
                'table' => $this->table_prefix . 'user_roles',
                'entity' => 'user role'
            ));

            $valid_role_ids = array_map(function($role) { return (string)$role->id; }, (array)$user_roles);
            if (!in_array((string)$post['user_role_id'], $valid_role_ids)) {
                $result['message'][] = 'Invalid user role';
                $result['validation_results']['user_role_id'] = 'Invalid user role';
            }
        }

        // Validate password separately and merge any errors
        $password_checked = $this->password->check_password($post);
        if (isset($password_checked['bool']) && !$password_checked['bool']) {
            if (!empty($password_checked['message'])) {
                $result['message'] = array_merge($result['message'], $password_checked['message']);
            }
            if (!empty($password_checked['validation_results'])) {
                $result['validation_results'] = array_merge($result['validation_results'], $password_checked['validation_results']);
            }
        } else {
            $post = $password_checked;
        }

        // Return all validation errors before touching the database
        if (!empty($result['message'])) {
            return $result;
        }

        $post['last_activity'] = current_datetime();

        // Check if email already exists
        $check_params = $params;
        $check_params['fields'] = 'email';
        $check_params['where'] = array('email' => $post['email']);
        $exists = $this->generic_model->read($check_params);

        if (!empty($exists)) {
            $result['message'][] = 'A user with email ' . $post['email'] . ' already exists';
            $result['validation_results']['email'] = 'Email already exists';
            return $result;
        }

        $result = $this->generic_model->create($params, $post);

        // Ensure message key always exists so Generic._post() never reads an undefined index
        if (!isset($result['message'])) {
            $result['message'] = array();
        }

        return $result;
    }

    public function read($params, $identifier = null, $indices = 'id')
    {
        if (!isset($params['fields'])) {
            $params['fields'] = array(
                'u.id',
                'u.first_name',
                'u.last_name',
                'u.email',
                'u.user_role_id',
                'u.contact_number',
                'u.last_activity',
                'roles.role_name "user_role"'
            );
        }

        if (!is_null($identifier)) {
            $params['main_id_field'] = 'u.id';
        }

        $params['table'] = $this->table_prefix . 'users u';

        $params['join'] = array();
        $params['join'][1]['table1'] = $this->table_prefix . 'user_roles roles';
        $params['join'][1]['table2'] = 'roles.id = u.user_role_id';
        $params['join'][1]['type'] = 'left';

        $results = $this->generic_model->read($params, $identifier, $indices);

        if(is_array($results))
        {
            foreach($results as $key => $result)
            {
                $role_permissions = $this->role_permissions_model->read();
                if(!empty($role_permissions)) $results[$key]->permissions = $role_permissions;
            }
        }
        else
        {
            $role_permissions = $this->role_permissions_model->read2(array(), $results->user_role_id);
            $results->permissions = $role_permissions;
        }

        return $results;
    }

    public function update($params, $id, $post)
    {
        $result = array('bool' => false);

        if(isset($post['password']) && $post['password'] !='')
        {
            $post = $this->password->check_password($post);
        }
        else
        {
            $post['password'] = $this->generic_model->read($params, $id, 'single')->password;
            if(isset($post['confirm_password'])) unset($post['confirm_password']);
        }

        if (isset($post['bool']) && !$post['bool']) return $post;

        $post['last_activity'] = current_datetime();

        $params['fields'] = 'email';
        $params['where'] = array('email' => $post['email']);

        # Check if current user is the account owner
        $owner_params = $params;
        $owner_params['fields'] = 'owner';
        $current_user = $this->generic_model->read($owner_params, $id, 'single');

        if($current_user->owner == 1 && $post['user_role_id'] != 1)
        {
            $result['validation_results']['user_role_id'] = 'This user is a site owner and cannot be saved as anything other than an admin.';
            $result['message'][] = 'This user is a site owner and cannot be saved as anything other than an admin.';
        }
        else
        {
            #validate user role ID in post
            if (isset($post['user_role_id'])) {
                $user_roles = $this->generic_model->read(array(
                        'table' => $this->table_prefix . 'user_roles',
                        'entity' => 'user role'
                    )
                );

                $valid_role_ids = array_map(function($role) { return (string)$role->id; }, (array)$user_roles);
                if (!in_array((string)$post['user_role_id'], $valid_role_ids)) :
                    $result['message'][] = 'Invalid user role';
                    $result['validation_results']['user_role_id'] = 'Invalid user role';
                endif;
            }
            else {
                $result['message'][] = 'Please select a role for this user';
                $result['validation_results']['user_role_id'] = 'Please select a role for this user';
            }

            unset($params['where']);

            $result = $this->generic_model->update($params, $id, $post);
        }

        return $result;
    }
}