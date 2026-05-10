<?php
$user = "user";
$password = "password";
$api_id = "xxxxxxx";
$baseurl ="http://api.clickatell.com";

$text = urlencode("This is an example message");
$to = "00123456789";

// auth call
$url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";

// do auth call
$ret = file($url);

// explode our response. return string is on first line of the data returned
$sess = explode(":",$ret[0]);
if ($sess[0] == "OK") {

    $sess_id = trim($sess[1]); // remove any whitespace
    $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text";

    // do sendmsg call
    $ret = file($url);
    $send = explode(":",$ret[0]);

    if ($send[0] == "ID") {
        echo "successnmessage ID: ". $send[1];
    } else {
        echo "send message failed";
    }
} else {
    echo "Authentication failure: ". $ret[0];
}
?>