<?php

class Protectdir{	
	public function __construct(){	
		
	   // parent::__construct();
		if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != 'dgb' || $_SERVER['PHP_AUTH_PW'] != 'test@dgb') {
		  header('WWW-Authenticate: Basic realm="MyProject"');
		  header('HTTP/1.0 401 Unauthorized');
		  die('Access Denied');
		}

		
    }
	

}




?>