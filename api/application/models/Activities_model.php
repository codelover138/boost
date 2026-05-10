<?php

class Activities_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function create($post)
    {

		$params = array(
            'table' => $this->table_prefix . 'activities',
            'entity' => 'activity'
        );

        $result = $this->generic_model->create($params, $post);

        return $result;
    }
}