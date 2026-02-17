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
        $result = array('bool' => false);

        $post = $this->password->check_password($post);
        if (isset($post['bool']) && !$post['bool']) return $post;

        $post['last_activity'] = current_datetime();

        $params['fields'] = 'email';
        $params['where'] = array('email' => $post['email']);

        #validate user role ID in post
        if (isset($post['user_role_id'])) {
            $user_roles = $this->generic_model->read(array(
                    'table' => $this->table_prefix . 'user_roles',
                    'entity' => 'user role'
                )
            );

            if (!in_array($post['user_role_id'], $user_roles)) :
                $result['message'][] = 'Invalid user role';
                $result['validation_results']['user_role_id'] = 'Invalid user role';
            endif;
        } else {
            $result['message'][] = 'Please select a role for this user';
            $result['validation_results']['user_role_id'] = 'Please select a role for this user';
        }

        # check if user already exists
        $exists = $this->generic_model->read($params);

        if (empty($exists)) {
            $result = $this->generic_model->create($params, $post);
        } else {
            $result['validation_results']['email'] = 'Email already exists';
            $result['message'] = array('User with email: ' . $post['email'] . ' already exists.');
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

                if (!in_array($post['user_role_id'], $user_roles)) :
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