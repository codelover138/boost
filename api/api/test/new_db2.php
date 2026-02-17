<?php
include("xmlapi.php.inc"); //https://github.com/CpanelInc/xmlapi-php/blob/master/xmlapi.php
$db_host = "myDomainName.com"; 
$cpuser = "myCpanelUser"; 
$cppass = "myCpanelPwd"; 

$xmlapi = new xmlapi($db_host); 
$xmlapi->set_port(2083); 
$xmlapi->password_auth($cpuser,$cppass); 
$xmlapi->set_debug(1); 
//create database 
print $xmlapi->api1_query($cpuser, "Mysql", "adddb", 'myDatabaseName'); 
//create user 
print $xmlapi->api1_query($cpuser, "Mysql", "adduser", array('user' => 'myDBUser','pass'=>'myDBPwd')); 
//add user to database 
$xmlapi->api1_query($cpuser, "Mysql", "adduserdb", array( 
                    'db' => 'myDatabaseName', 
                    'user'=>'myDBUser', 
                    'SELECT INSERT UPDATE CREATE DELETE ALTER DROP')); 