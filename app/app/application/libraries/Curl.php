<?php

class Curl{
	
	public $api_base_url;
	public $CI;
	public $domain_name_string;
	
	public function __construct()
    {
        $this->CI = get_instance();        
		$this->api_base_url = $this->CI->config->item('api_base_url');
		$this->domain_name_string = $this->CI->config->item('domain_name_string');		
    }

	private function get_request_headers()
{
    $headers = [];
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) === 'HTTP_') {
            $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
            $headers[$header] = $value;
        }
    }
    return $headers;
}
	
	public function api_call($method, $resource, $data = NULL)
    {
		if($data !== NULL){
        	$data_string = json_encode($data);
		}else{
			$data_string = NULL;
		}
		
		//$header_data = getallheaders();
		$header_data = function_exists('getallheaders') ? getallheaders() : $this->get_request_headers();

		
		if(isset($header_data['Auth']) && isset($header_data['Session']) && isset($header_data['Account-Name'])){
			$auth_token = $header_data['Auth'];
			$session_id = $header_data['Session'];
			$subdomain = $header_data['Account-Name'];
		}elseif(isset($_COOKIE['auth']) && isset($_COOKIE['session_id'])){
			$auth_token = $_COOKIE['auth'];
			$session_id = $_COOKIE['session_id'];
			$pos = strpos($_SERVER['HTTP_HOST'], '.'.$this->domain_name_string);				
			$subdomain = substr($_SERVER['HTTP_HOST'],0,$pos);
		}else{
			$pos = strpos($_SERVER['HTTP_HOST'], '.'.$this->domain_name_string);				
			$subdomain = substr($_SERVER['HTTP_HOST'],0,$pos);	
			
			//echo 'isset='.isset($_SERVER['HTTP_X_REQUESTED_WITH']).'<br />';
					
			//if(empty(@$_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH'])){
			if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH'])){
				//header("location:".base_url('login').'?error='.urlencode('Please login.').'&redirAddress='.urlencode(uri_string()));
				header("location:".base_url('login').'?&redirAddress='.urlencode(uri_string()));
				exit;
			}
		}
		
        $http_header = array(
            'Content-Type: application/json',
            'Content-Length: '	. strlen($data_string),
			'Auth:'				. @$auth_token,
			'Account-Name:' 	. $subdomain,
			'Session:' 			. @$session_id
        );

        // set up the curl resources
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->api_base_url.$resource);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); // note the method here
        if($data !== NULL){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		}
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        #execute the request
        $output = curl_exec($ch);
		
		//get the response info with status code and redirect to login if the code is not 200(success)
		$response_info = curl_getinfo($ch);
		
        // Closing curl handler
        curl_close($ch);
		
		//print_r(json_decode($output, true));
		//exit;

		if($response_info['http_code'] == 200){
			$decoded = json_decode($output, true);
			http_response_code($response_info['http_code']);
			//print_r($decoded);
			//exit;			
			return $decoded;
		}elseif($response_info['http_code'] == 0){
			http_response_code(408);			
			exit;
		}elseif(in_array($response_info['http_code'], array(401,498,499), true )){
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])){
				http_response_code(401);
				$decoded = json_decode($output, true);
				
				if(isset($decoded['message'])){
					echo json_encode($decoded);
				}else{
					$decoded['message'][0] = 'An unknown error occurred';
					echo json_encode($decoded);
				}
				
				exit;
			}else{	
				$decoded = json_decode($output, true);	
				header("location:".base_url('login').'?error='.urlencode(implode('. ',$decoded['message']).'. Please login.').'&redirAddress='.urlencode(uri_string()));
				exit;
			}
		}else{		
			http_response_code($response_info['http_code']);
			print_r($output);
			exit;
		}

    }
	
	public function insecure_api_call($method, $resource, $data = NULL)
    {
		if($data !== NULL){
        	$data_string = json_encode($data);
		}else{
			$data_string = NULL;
		}
		
		//get the subdomain to be used as the account name
		$pos = strpos($_SERVER['HTTP_HOST'], '.'.$this->domain_name_string);				
		$subdomain = substr($_SERVER['HTTP_HOST'],0,$pos);
		
        $http_header = array(
            'Content-Type: application/json',
            'Content-Length: '	. strlen($data_string),
			'Auth:'				. '',
			'Account-Name:' 	. $subdomain,
			'Session:' 			. ''
        );

        // set up the curl resources
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->api_base_url.$resource);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
		
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); // note the method here
        if($data !== NULL){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		}
		
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        #execute the request
        $output = curl_exec($ch);
		
		//get the response info with status code and redirect to login if the code is not 200(success)
		$response_info = curl_getinfo($ch);
		
        // Closing curl handler
        curl_close($ch);
		
		
		$decoded = json_decode($output, true);
		http_response_code($response_info['http_code']);
		/*
		var_dump($decoded);
		exit;
		*/	
				
		return $decoded;
		
    }


	 public function call_post() {
        $method = $this->post('method'); // e.g., 'GET', 'POST', 'PUT', 'DELETE'
        $resource = $this->post('resource'); // e.g., '/users'
        $data = $this->post('data'); // Payload data as JSON

        $response = $this->api_call($method, $resource, $data);
        $this->response($response, $response['http_code'] ?? 200);
    }

    public function rest_api_call($method, $resource, $data = NULL) {
        $api_key = '6cc9ca29d7ef010a6164f0c5e8fe3768'; // Add this to your config file
        $data_string = $data !== NULL ? json_encode($data) : NULL;

        // Fetch headers
        $header_data = function_exists('getallheaders') ? getallheaders() : $this->get_request_headers();

        if (isset($header_data['Auth']) && isset($header_data['Session']) && isset($header_data['Account-Name'])) {
            $auth_token = $header_data['Auth'];
            $session_id = $header_data['Session'];
            $subdomain = $header_data['Account-Name'];
        } elseif (isset($_COOKIE['auth']) && isset($_COOKIE['session_id'])) {
            $auth_token = $_COOKIE['auth'];
            $session_id = $_COOKIE['session_id'];
            $pos = strpos($_SERVER['HTTP_HOST'], '.' . $this->domain_name_string);
            $subdomain = substr($_SERVER['HTTP_HOST'], 0, $pos);
        } else {
            $pos = strpos($_SERVER['HTTP_HOST'], '.' . $this->domain_name_string);
            $subdomain = substr($_SERVER['HTTP_HOST'], 0, $pos);

            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH'])) {
                $this->response(['error' => 'Please log in to access acurate API'],403);
                exit;
            }
        }

      log_message('error',$this->api_base_url . $resource);

    // Prepare headers
    $http_header = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string),
        'Auth: ' . @$auth_token,
        'Account-Name: ' . $subdomain,
        'Session: ' . @$session_id,
        'X-API-KEY: ' . $api_key // Add X-API-KEY here
    );

        // Initialize CURL
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->api_base_url . $resource);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($data !== NULL) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute CURL
        $output = curl_exec($ch);

        // Response Info
        $response_info = curl_getinfo($ch);
       

        curl_close($ch);

        if ($response_info['http_code'] == 200) {
            return json_decode($output, true);
        } elseif ($response_info['http_code'] == 0) {
            return ['error' => 'Request timeout', 'http_code' => 408];
        } elseif (in_array($response_info['http_code'], [401, 498, 499], true)) {
            return [
                'error' => 'Unauthorized or session expired',
                'message' => json_decode($output, true),
                'http_code' =>401
            ];
        } else {
            return [
                'error' => 'Unexpected error occurred',
                'response' => $output,
                'http_code' => $response_info['http_code']
            ];
        }
    }

   
	
}




?>