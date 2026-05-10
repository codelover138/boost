<?php
include("xmlapi.php"); //https://github.com/CpanelInc/xmlapi-php/blob/master/xmlapi.php

$cp_host = "api.boostaccounting.com"; 
$cpuser = "boost"; 
$cppass = "~;kuFc?{Hd@b"; 

$new_db_name = 'boost_acc00';
$db_user = 'boost_api';
$db_password = 'DWe4Kk0gV7&y';
$db_host = 'localhost';

$xmlapi = new xmlapi($cp_host);   
$xmlapi->set_port( 2083 );   
$xmlapi->password_auth($cpuser, $cppass);    
$xmlapi->set_debug(0);//output actions in the error log 1 for true and 0 false 


//create database    
$createdb = $xmlapi->api1_query($cpaneluser, "Mysql", "adddb", array($new_db_name));

print_r($createdb);

 
//create user 
//$usr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduser", array($db_user, $databasepass));   
//add user 
//$addusr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduserdb", array("".$cpuser."_".$new_db_name."", "".$cpuser."_".$db_user."", 'all'));