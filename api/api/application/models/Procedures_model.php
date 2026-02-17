<?php

class Procedures_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
		
		$this->db_configs = array(
			'dsn'	=> '',
			'hostname' => $this->db->hostname,
			'username' => $this->db->username,
			'password' => $this->db->password,
			'dbdriver' => $this->db->dbdriver
		);
		
        $this->db_prefix = $this->config->item('db_account_prefix');
		$this->table_prefix = $this->config->item('db_table_prefix');
		
		//$this->load->dbforge();
    }
	
	/* --------------------- list_account_dbs() -------------------------------
	***** checks if the database is related to an account and is not the api database or any other
	// checks that the database prefix is of an account type
	// returns an array of account database names
	*/

    public function list_account_dbs()
    {
		$query = $this->db->query("SHOW DATABASES");
		$db_prefix = $this->db_prefix.'acc';
		$prefix_length = strlen($db_prefix);
		$db_list = array();
		
		foreach ($query->result() as $row)
		{
			if(substr($row->Database,0,$prefix_length) == $db_prefix){
				$db_list[] = $row->Database;
			}
		}
		
		return $db_list;
    }
	
	/* --------------------- db_table_exists() -------------------------------
	***** checks if the database table exists for a specified database
	// gets the general config details and inserts the specified db name
	// connects to the specified database
	// checks if thenamed table ecists in the specified database 
	// returns true or false according to result
	*/
	
	public function db_table_exists($db_name, $table_name)
    {
		$db = $this->db_configs;
		$db['database'] = $db_name;
		$this->load->database($db, FALSE,TRUE);
		$result = $this->db->table_exists($table_name);

		return $result;
    }
	
	/* --------------------- db_run_query() -------------------------------
	***** runs a query on the specified database
	// gets the general config details and inserts the specified db na,e
	// connects to the specified database
	// runs the specified query
	// returns the result
	*/
	
	public function db_run_query($db_name, $query)
    {
		$db = $this->db_configs;
		$db['database'] = $db_name;
		$this->load->database($db, FALSE,TRUE);
		
		$result = $this->db->query($query);

		return $result;
    }
	
}