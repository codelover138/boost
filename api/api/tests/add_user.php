<?php
include("xmlapi.php"); //https://github.com/CpanelInc/xmlapi-php/blob/master/xmlapi.php

$cp_host = "api.boostaccounting.com"; 
$cpuser = "boost"; 
$cppass = "~;kuFc?{Hd@b"; 

$new_db_name = 'boost_acc74';
$db_user = 'boost_api';
$db_password = 'DWe4Kk0gV7&y';
$db_host = 'localhost';

$xmlapi = new xmlapi($cp_host);   
$xmlapi->set_port( 2083 );   
$xmlapi->password_auth($cpuser, $cppass);    
$xmlapi->set_debug(0);//output actions in the error log 1 for true and 0 false

//add user to database 
/*$x = $xmlapi->api1_query($cpuser, "Mysql", "adduserdb", array( 
                    'db' => 'boost_acc74', 
                    'user'=>$db_user, 
                    'SELECT INSERT UPDATE CREATE DELETE ALTER DROP')); */
					
$x = $xmlapi->api1_query($cpuser, "Mysql", "adduserdb", array($new_db_name, $db_user, 'all')); 
							
print_r($x);