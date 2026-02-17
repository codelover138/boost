<?php

class New_db
{
    public $cp_host = "api.boostaccounting.com";
    public $cpuser = "boost";
    //public $cppass = '~;kuFc?{Hd@b';
    public $cppass = '}BpDz$miH09~';

    public $new_db_name;
    public $new_db_priv = '&ALL=ALL';
    public $db_user;
    public $db_password;
    public $db_host;
    public $table_prefix;
    public $account_prefix;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->table_prefix = $this->CI->config->item('db_table_prefix');
		$this->account_prefix = $this->CI->config->item('db_account_prefix');

        $this->db_host = $this->CI->db->hostname;
        $this->db_user = $this->CI->db->username;
        $this->db_password = $this->CI->db->password;
        //$this->CI->db->database;
    }

    public function create($db_name)
    {
        $this->createDb(str_ireplace($this->account_prefix,"",$db_name));
        $new_db_name = $db_name;
        $this->add_user($new_db_name);
        

        $this->CI->load->dbutil();
        $retries = 3;
        $db_exists = false;
    
        // Check database existence
        for ($i = 0; $i < $retries; $i++) {
            $db_exists = $this->CI->dbutil->database_exists($new_db_name);
            if ($db_exists) {
                break;
            }
            sleep(1); 
        }


        if ($db_exists) {
            $this->CI->load->library('db/db_setup');
            $this->CI->load->library('db/db_update');

            $this->CI->db_setup->create_tables($db_name);
            $this->CI->db_update->update_tables($db_name);
        }

        return $db_exists;
     }

    // public function createDb($dbName)
    // {
    //     $cPanelUser = $this->cpuser;
    //     $cPanelPass = $this->cppass;

    //     $buildRequest = "/frontend/paper_lantern/sql/addb.html?db=" . $dbName;

    //     $openSocket = fsockopen('localhost', 2082);
    //     if (!$openSocket) {
    //         return "Socket error";
    //         exit();
    //     }

    //     $authString = $cPanelUser . ":" . $cPanelPass;
    //     $authPass = base64_encode($authString);
    //     $buildHeaders = "GET " . $buildRequest . "\r\n";
    //     $buildHeaders .= "HTTP/1.0\r\n";
    //     $buildHeaders .= "Host:localhost\r\n";
    //     $buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
    //     $buildHeaders .= "\r\n";

    //     fputs($openSocket, $buildHeaders);
    //     while (!feof($openSocket)) {
    //         fgets($openSocket, 128);
    //     }
    //     fclose($openSocket);

    //}

    public function createDb($dbName)
    {
        $cPanelUser = $this->cpuser;
        $cPanelPass = $this->cppass;
        $server = "localhost"; // Change if needed
    
        // Ensure database name has cPanel prefix and meets length requirements
        $dbName = $cPanelUser . '_' . $dbName;
        if (strlen($dbName) > 16) {
            log_message('error', "Database name $dbName exceeds 16-character limit.");
            return "Database name exceeds 16-character limit.";
        }
    
        $url = "https://$server:2083/execute/Mysql/create_database?name=$dbName";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
        $headers = [
            "Authorization: Basic " . base64_encode("$cPanelUser:$cPanelPass")
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            log_message('error', 'cURL error: ' . curl_error($ch));
            return "cURL error: " . curl_error($ch);
        }
        curl_close($ch);
    
        // Log raw response to see all details
        log_message('debug', "Raw API response: " . print_r($response, true));
    
        $result = json_decode($response, true);
        if ($result) {
            log_message('debug', "API Response decoded: " . print_r($result, true));
        } else {
            log_message('error', "Failed to decode JSON response: " . $response);
        }
    
        if (isset($result['status']) && $result['status'] == 1) {
            log_message('debug', "Database $dbName created successfully.");
            return true;
        } else {
            $error = isset($result['errors']) ? implode(', ', $result['errors']) : 'Unknown error';
            log_message('error', "Failed to create database $dbName: " . $error);
            return "Error creating database: " . $error;
        }
    }

    /*public function createUser($cPanelUser,$cPanelPass,$userName,$userPass) {

        $buildRequest = "/frontend/x3/sql/adduser.html?user=".$userName."&pass=".$userPass;

        $openSocket = fsockopen('localhost',2083);
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
    }*/

    /*public function addUserToDb($dbName, $privileges = '&ALL=ALL')
    {
        $cPanelUser = $this->cpuser;
        $cPanelPass = $this->cppass;
        $userName = $this->db_user;

        $buildRequest = "/frontend/x3/sql/addusertodb.html?user=" . $userName . "&db=" . $dbName . $privileges;

        $openSocket = fsockopen('localhost', 2083);
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
    }*/



    public function add_user($new_db_name, $db_user = 'boost_api') {
        $cp_host = "boostaccounting.com";
        $cpuser = "boost";
        $cppass = '}BpDz$miH09~'; 
    
        $this->CI->load->library('cpanel/xmlapi', array('host' => $cp_host));
    
        $this->CI->xmlapi->set_port(2083);
        $this->CI->xmlapi->password_auth($cpuser, $cppass);
        $this->CI->xmlapi->set_output('json');
        $this->CI->xmlapi->set_debug(1); 
    
    
        try {
            $test_user = "boost_api";
            log_message('info', "Attempting to add user '$test_user' to database '$new_db_name' with all privileges.");
            $addUserResponse = $this->CI->xmlapi->api1_query($cpuser, "Mysql", "adduserdb", [$new_db_name, $test_user, 'all']);
            
            if (isset($addUserResponse->data->result) && $addUserResponse->data->result == 1) {
                log_message('info', "User $test_user successfully added to database $new_db_name.");
            } else {
                $errorMessage = isset($addUserResponse->data->reason) ? $addUserResponse->data->reason : 'Unknown error';
                log_message('error', "Failed to add user $test_user to database $new_db_name: $errorMessage");
                return false;
            }
            return true;
    
        } catch (Exception $e) {
            log_message('error', "Exception occurred: " . $e->getMessage());
            return false;
        }
    }

    public function createSubdomain($subdomain, $root_directory = '/public_html', $domain = 'boostaccounting.com')
{
    $cPanelUser = $this->cpuser;
    $cPanelPass = $this->cppass;
    $server = "localhost";

    // Define full root directory for the subdomain
    $full_root_directory = "$root_directory/$subdomain";

    // Construct the API URL
    $url = "https://$server:2083/json-api/cpanel?cpanel_jsonapi_user=$cPanelUser&cpanel_jsonapi_module=SubDomain&cpanel_jsonapi_func=addsubdomain&cpanel_jsonapi_version=2&subdomain=$subdomain&root=$full_root_directory&domain=$domain";

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // Set authorization headers
    $headers = [
        "Authorization: Basic " . base64_encode("$cPanelUser:$cPanelPass")
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute and retrieve response
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        log_message('error', 'cURL error: ' . curl_error($ch));
        return "cURL error: " . curl_error($ch);
    }
    curl_close($ch);

    // Log and decode the response
    log_message('debug', "Raw API response: " . print_r($response, true));
    $result = json_decode($response, true);

    if ($result) {
        log_message('debug', "API Response decoded: " . print_r($result, true));
    } else {
        log_message('error', "Failed to decode JSON response: " . $response);
    }

    // Check for success and return result
    if (isset($result['cpanelresult']['data'][0]['result']) && $result['cpanelresult']['data'][0]['result'] == 1) {
        log_message('debug', "Subdomain $subdomain.$domain created successfully.");
        return true;
    } else {
        $error = isset($result['cpanelresult']['error']) ? $result['cpanelresult']['error'] : 'Unknown error';
        log_message('error', "Failed to create subdomain $subdomain.$domain: " . $error);
        return "Error creating subdomain: " . $error;
    }
}

    

    

    
}