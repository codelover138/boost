<?php

class User_tokens_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function delete()
    {
        $this->load->dbutil();
        $dbs = $this->dbutil->list_databases();

        $databases = array();

        foreach ($dbs as $db) :
            if (strpos($db, 'acc') !== false) $databases[] = $db;
        endforeach;

        $params = array(
            'table' => $this->table_prefix . 'user_tokens',
            'entity' => 'tokens',
            'where' => array(
                'token_expire <' => current_datetime()
            )
        );

        $results = array('bool' => false);

        if (!empty($databases)) {
            $results['bool'] = true;
            foreach ($databases as $database) {
                $this->db->query('use ' . $database);
                $results[$database] = $this->generic_model->u_delete($params);
            }
        } else {
            $results['message'][0] = 'No databases found';
        }

        return $results;
    }
}