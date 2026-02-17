<?php
function createDb($cPanelUser,$cPanelPass,$dbName) {

    $buildRequest = "/frontend/x3/sql/addb.html?db=".$dbName;

    $openSocket = fsockopen('localhost',2082);
    if(!$openSocket) {
        return "Socket error";
        exit();
    }

    $authString = $cPanelUser . ":" . $cPanelPass;
    $authPass = base64_encode($authString);
    $buildHeaders  = "GET " . $buildRequest ."\r\n";
    $buildHeaders .= "HTTP/1.0\r\n";
    $buildHeaders .= "Host:localhost\r\n";
    $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
    $buildHeaders .= "\r\n";

    fputs($openSocket, $buildHeaders);
    while(!feof($openSocket)) {
        fgets($openSocket,128);
    }
    fclose($openSocket);
}


function createUser($cPanelUser,$cPanelPass,$userName,$userPass) {

    $buildRequest = "/frontend/x3/sql/adduser.html?user=".$userName."&pass=".$userPass;

    $openSocket = fsockopen('localhost',2082);
    if(!$openSocket) {
        return "Socket error";
        exit();
    }

    $authString = $cPanelUser . ":" . $cPanelPass;
    $authPass = base64_encode($authString);
    $buildHeaders  = "GET " . $buildRequest ."\r\n";
    $buildHeaders .= "HTTP/1.0\r\n";
    $buildHeaders .= "Host:localhost\r\n";
    $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
    $buildHeaders .= "\r\n";

    fputs($openSocket, $buildHeaders);
    while(!feof($openSocket)) {
        fgets($openSocket,128);
    }
    fclose($openSocket);
}

function addUserToDb($cPanelUser,$cPanelPass,$userName,$dbName,$privileges) {

    $buildRequest = "/frontend/x3/sql/addusertodb.html?user=".$userName."&db=".$dbName.$privileges;

    $openSocket = fsockopen('localhost',2082);
    if(!$openSocket) {
        return "Socket error";
        exit();
    }

    $authString = $cPanelUser . ":" . $cPanelPass;
    $authPass = base64_encode($authString);
    $buildHeaders  = "GET " . $buildRequest ."\r\n";
    $buildHeaders .= "HTTP/1.0\r\n";
    $buildHeaders .= "Host:localhost\r\n";
    $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
    $buildHeaders .= "\r\n";

    fputs($openSocket, $buildHeaders);
    while(!feof($openSocket)) {
        fgets($openSocket,128);
    }
    fclose($openSocket);
}

$cp_host = "api.boostaccounting.com"; 
$cpuser = "boost"; 
$cppass = "~;kuFc?{Hd@b"; 

$new_db_name = 'new_db_2';
$db_user = 'boost_api';
$db_password = 'DWe4Kk0gV7&y';
$db_host = 'localhost';
$db_prefix = 'boost_';

//Create Db
createDb($cpuser, $cppass, $new_db_name);

//Create User
//createUser($cpuser, $cppass, $db_user, $db_password);

//Add user to DB - ALL Privileges
addUserToDb($cpuser, $cppass, $db_user, $db_prefix . $new_db_name, '&ALL=ALL');

//Add user to DB - SELECTED PRIVILEGES
addUserToDb($cpuser, $cppass, $db_user, $db_prefix . $new_db_name, '&CREATE=CREATE&ALTER=ALTER');