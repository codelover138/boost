<?php

class Call_test extends CI_Controller
{
    public $api_link = 'http://localhost/boost_dev/api/api/';
    public $api_link2 = 'http://localhost/boost/api/api/';

    public function index()
    {
		$this->load->library('messaging');
		$test = $this->messaging->decrypt('MjNjOWYwMWIwZGZjM2Q5MGI4OTY0NzNhZGYyNWE1NDIwNTQ4MjdkMWM4ZmI4YzIwM2Q0MjZlZDg4OGEyZWM0M2MyNDA0MTc2MjYzMjFkNWM5MGJmM2I0MmUzZGI1YTA3OTBhMTlkYmVhYzIwMjc1MWMwYzYwMzFhZDIwOTc0NGZ4b2lGWGdIbjV0UEFjT3p1ZGRxdmpTSkhHOVc3YzZmTkRHb3hubEpQSzFuZitoVUptdGt1SGw3cWpQU09idEFKZzBQeVVIUXUxWU1hOTBEd3MyTHMxb3VLMW9jL0F2UHM1TWwraVVhZ3NGdGdVc3Z3WkphSjVQMjIxU3ZFaWxFZXZ4THZlaHprUWRwNk1DZXA1dnhrekZQUTFxc2xoQ2loNE84YmJDZ05iSDg9');
		var_dump($test);
    }

    public static function createApiCall($url, $method, $headers, $data = array())
    {
        if ($method == 'PUT') {
            $headers[] = 'X-HTTP-Method-Override: PUT';
        }
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'PUT':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'DELETE':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        $response = curl_exec($handle);
        return $response;
    }

    public function login($email, $password)
    {
        $login = array(
            'email' => $email,
            'password' => $password
        );

        $data_string = array(
            'login' => base64_encode(json_encode($login))
        );

        $login = $this->api_call2(array(
            'method' => 'POST',
            'resource' => 'login',
            'request_url' => $this->api_link,
            'data' => $data_string
        ));

        return $login;
    }

    public function api_call($method, $resource, $data = null, $request_url = null)
    {
        return $this->regular->curl_request($method, $resource, $data, $request_url);
    }

    public function api_call2($params)
    {
        return $this->regular->curl_request2($params);
    }

    public function create_db($db_name)
    {
        $this->load->dbforge();
        $this->load->dbutil();

        if ($this->dbutil->database_exists($db_name)) {
            $this->dbforge->drop_database($db_name);
        }

        if (!$this->dbutil->database_exists($db_name)) {
            if ($this->dbforge->create_database($db_name)) {
                echo 'Database created!';
                $this->add_tables();
            } else {
                echo 'could not create database';
            }
        } else {
            echo 'database already exists';
        }
    }

    public function add_tables($db = null)
    {
        $this->load->dbforge();

        var_dump('adding tables...');

        if (!is_null($db)) {
            $this->db->query('use ' . $db);
        }

        /*ADMIN USERS
         --------------------------------------------------------------------------------------------*/
        $admin_users = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'first_name' => array('type' => 'VARCHAR', 'constraint' => 30),
            'last_name' => array('type' => 'VARCHAR', 'constraint' => 30),
            'email' => array('type' => 'VARCHAR', 'constraint' => 30),
            'company_id' => array('type' => 'INT', 'constraint' => 11),
            'user_role' => array('type' => 'INT', 'constraint' => 3),
            'password' => array('type' => 'VARCHAR', 'constraint' => 100),
            'failed_attempts' => array('type' => 'INT', 'constraint' => 11),
            'last_attempt_datetime' => array('type' => 'TIMESTAMP', 'default' => '0000-00-00 00:00:00'),
            'is_active' => array('type' => 'BOOL', 'default' => 1)
        );

        $this->dbforge->add_field($admin_users);
        $this->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->dbforge->add_key('id', TRUE);

        $x = $this->dbforge->create_table('db_admin_users', TRUE);

        var_dump($x);

        /*-------------------------------------------------------------------------------------------*/
    }

    public function create_subdomain($subDomain, $cPanelUser, $cPanelPass, $rootDomain)
    {

        // $buildRequest = "/frontend/x3/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain;

        $buildRequest = "/frontend/x3/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain . "&dir=public_html/app"; // . $subDomain;

        $openSocket = fsockopen('localhost', 2082);
        if (!$openSocket) {
            return "Socket error";
            exit();
        }

        $authString = $cPanelUser . ":" . $cPanelPass;
        $authPass = base64_encode($authString);
        $buildHeaders = "GET " . $buildRequest . "\r\n";
        $buildHeaders .= "HTTP/1.0\r\n";
        $buildHeaders .= "Host:localhost\r\n";
        $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
        $buildHeaders .= "\r\n";

        fputs($openSocket, $buildHeaders);
        while (!feof($openSocket)) {
            fgets($openSocket, 128);
        }
        fclose($openSocket);

        //$newDomain = "http://" . $subDomain . "." . $rootDomain . "/";

        //  return "Created subdomain $newDomain";
    }

    public function delete_subdomain($subDomain, $cPanelUser, $cPanelPass, $rootDomain)
    {
        $buildRequest = "/frontend/x3/subdomain/dodeldomain.html?domain=" . $subDomain . "_" . $rootDomain;

        $openSocket = fsockopen('localhost', 2082);
        if (!$openSocket) {
            return "Socket error";
            exit();
        }

        $authString = $cPanelUser . ":" . $cPanelPass;
        $authPass = base64_encode($authString);
        $buildHeaders = "GET " . $buildRequest . "\r\n";
        $buildHeaders .= "HTTP/1.0\r\n";
        $buildHeaders .= "Host:localhost\r\n";
        $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
        $buildHeaders .= "\r\n";

        fputs($openSocket, $buildHeaders);
        while (!feof($openSocket)) {
            fgets($openSocket, 128);
        }
        fclose($openSocket);

//        $passToShell = "rm -rf /home/" . $cPanelUser . "/public_html/subdomains/" . $subDomain;
//        system($passToShell);
    }
}