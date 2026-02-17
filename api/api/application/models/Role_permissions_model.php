<?php

class Role_permissions_model extends CI_Model
{
    public $table_prefix;
    public $read_params;

    public function __construct()
    {
        parent::__construct();
        $this->table_prefix = $this->config->item('db_table_prefix');
        $this->read_params = array(
            'table' => $this->table_prefix . 'user_roles ur',
            'entity' => 'role permissions'
        );
        $this->read_params['main_id_field'] = 'ur.id';
        $this->read_params['fields'] = array(
            'ur.id "role_id"',
            'ur.role_name',
            'ur.short_name "role_short_name"',
            'up.permission "permission_name"',
            'up.short_name "permission_short_name"',
            'up.id "permission_id"'
        );

    }

    public function create($params = array(), $post)
    {
        $params['main_id_field'] = 'role_id';

        if (isset($post['role_data'])) {
            $final_post = array();
            $role_ids = array();
            $count = 0;
            foreach ($post['role_data'] as $role_id => $permissions) {

                //$user_role_id = settype($role_id, "integer");
                $user_role_id = $role_id;

                foreach ($permissions as $permission_id => $value) {
                    $final_post[$count]['role_id'] = $user_role_id;
                    $final_post[$count]['permission_id'] = $permission_id;
                    $count++;
                }
            }

            # deleting old role permission records
            $this->generic_model->delete($params, array(0, 1, 2));

            # save new role permissions
            $result = $this->generic_model->create($params, $final_post);
        } else {
            $result = array(
                'bool' => false,
                'message' => array('role data not found')
            );
        }

        return $result;
    }

    public function read()
    {
        $this->read_params['join'][0]['table1'] = $this->table_prefix . 'role_permissions rp';
        $this->read_params['join'][0]['table2'] = 'ur.id = rp.role_id';

        $this->read_params['join'][1]['table1'] = $this->table_prefix . 'user_permissions up';
        $this->read_params['join'][1]['table2'] = 'up.id = rp.permission_id';

        $result = $this->generic_model->read($this->read_params, null, 'zero');

        $final_result = array();

        foreach ($result as $res) {
            $final_result[$res->role_id]['role_id'] = $res->role_id;
            $final_result[$res->role_id]['role_short_name'] = $res->role_short_name;
            $final_result[$res->role_id]['role_name'] = $res->role_name;

            $final_result[$res->role_id]['permissions'][$res->permission_id]['permission_id'] = $res->permission_id;
            $final_result[$res->role_id]['permissions'][$res->permission_id]['permission_id'] = $res->permission_id;
            $final_result[$res->role_id]['permissions'][$res->permission_id]['permission_name'] = $res->permission_name;
            $final_result[$res->role_id]['permissions'][$res->permission_id]['permission_short_name'] = $res->permission_short_name;
        }

        return $final_result;
    }

    # build only for returning permission values(permission_short_name)
    public function read2($params = array(), $identifier = null, $indices = 'id')
    {
        $params = $this->read_params;

        $params['join'][0]['table1'] = $this->table_prefix . 'role_permissions rp';
        $params['join'][0]['table2'] = 'ur.id = rp.role_id';

        $params['join'][1]['table1'] = $this->table_prefix . 'user_permissions up';
        $params['join'][1]['table2'] = 'up.id = rp.permission_id';

        $results = $this->generic_model->read($params, $identifier, $indices);

        $permissions = array();

        foreach($results as $result)
        {
            $permissions[$result->permission_id] = $result->permission_short_name;
        }

        return $permissions;
    }

    public function update($post)
    {
        return $this->create($post);
    }
}