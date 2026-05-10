<?php

// Instantiate the CPANEL object.
require_once "/usr/local/cpanel/php/cpanel.php";

// Connect to cPanel - only do this once.
$cpanel = new CPANEL; 
  
var_dump($cpanel);
die();
  
// Create a new database.
$create_db = $cpanel->uapi(
    'Mysql', 'create_database',
    array(
        'name'    => 'new_test_db',
    )
);

print_r($create_db);