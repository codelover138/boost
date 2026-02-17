<?php

class Switcher
{
    public $table_prefix;
    public $main_db;
    public $account_db;
    public $account_name;
	public $account_id;

    public function __construct($params)
    {
        $this->CI =& get_instance();
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
        $this->account_name = $params['account_name'];
        $this->main_db = $this->CI->db->database;
        $this->account_db = $this->determine_account_db($params['account_name'])['account_db'];
    }

    public function main_db()
    {
        return $this->CI->db->query('use ' . $this->main_db);
    }

    public function account_db()
    {
        if ($this->account_db) :
            return $this->CI->db->query('use ' . $this->account_db);
        else :
            return false;
        endif;
    }

    public function specific_db($db_name)
    {
        return $this->CI->db->query('use ' . $db_name);
    }

    public function determine_account_db($account_name)
    {
        $result = array('bool' => false, 'account_db' => false);

        $org_params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'organisation',
            'where' => array('account_name' => $account_name)
        );

        $exists = $this->CI->generic_model->exists($org_params, true);

        if ($exists) :
            $this->CI->load->dbutil();
            $db_exists = $this->CI->dbutil->database_exists($exists->account_db);
			$this->account_id = $exists->id;

            if ($db_exists) :
                $result['bool'] = true;
                $result['account_db'] = $exists->account_db;
            else :
                $result['message'][] = 'Account database does not exists';
            endif;
        else :
            $result['message'][] = 'Account does not exist';
        endif;

        return $result;
    }
}