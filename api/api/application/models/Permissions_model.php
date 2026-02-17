<?php

class Permissions_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function read($params, $identifier = null)
    {
        $result = array();
        $permissions = $this->generic_model->read($params, $identifier);

        if (!empty($permissions)) {
            foreach ($permissions as $id => $permission) {
                $result[$permission->type][$id] = $permission;
            }
        }

        return $result;
    }
}