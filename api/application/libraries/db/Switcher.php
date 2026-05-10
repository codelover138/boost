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
        
        $this->account_name = isset($params['account_name']) ? $params['account_name'] : null;
        if(empty($this->account_name)) {
            // Fallback: try to detect from the host if header is missing
            $this->account_name = $this->CI->regular->extract_subdomain($_SERVER['HTTP_HOST']);
        }

        $this->main_db = $this->CI->db->database;
        $db_info = $this->determine_account_db($this->account_name);
        $this->account_db = $db_info['account_db'];
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

        if (in_array(strtolower($account_name), ['app', 'api', 'www', 'boostaccounting', 'admin'])) {
            $result['bool'] = true;
            // admin has its own dedicated database
            $result['account_db'] = (strtolower($account_name) === 'admin') ? 'boost_admin' : $this->main_db;
            
            // Still try to find if there is an organisation record to set account_id
            $org_params = array(
                'table' => $this->table_prefix . 'organisations',
                'entity' => 'organisation',
                'where' => array('account_name' => $account_name)
            );
            $exists = $this->CI->generic_model->exists($org_params, true);
            if ($exists) {
                $this->account_id = $exists->id;
            }
            return $result;
        }

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
    public function check_sub_status($account_name)
    {
        // Switch to main DB to read organisations table
        $this->main_db();

        $org_params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'organisation',
            'where' => array('account_name' => $account_name)
        );

        $result = $this->CI->generic_model->exists($org_params, true);

        if (!$result && in_array(strtolower($account_name), ['app', 'api', 'www', 'boostaccounting', 'admin'])) {
            // Bypass subscription check for system accounts
            $result = (object)['status' => 1]; 
        }
        
        // Switch back to account DB to ensure subsequent requests work
        $this->account_db();

        return $result;
    }
}