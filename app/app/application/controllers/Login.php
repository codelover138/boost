<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	
	/**
	 * constructor.
	 *
	 * loads the requred libs and helpers
	 * 		curl - the functions to request data from the API
	 *		url - helps give url abilities in the view eg base_url()
	 * 		api_base - requred to use api_base_url() which gets the api url and makes an absolute link to the passed location
	 *	- or -
	 *
	 * this will show the view Login.php
	*
	 */
	 
	public function __construct()
    {
        parent::__construct();
		$this->load->library('curl');
		$this->load->helper('url');	
		$this->load->helper('api_base');
    }
	
	
	/**
	 * Login actions.
	 *
	 * Maps to the following URL
	 * 		*.boostaccounting.com/login/
	 *	- or -
	 * 		*.www.boostaccounting.com/login/
	 *	- or -
	 *
	 * this will show the view Login.php
	*
	 */
	 
	public function index()
	{
		$data['page']['title'] = 'Boost Cloud Accounting | Login';
		$data['page']['html_class'] = 'login_html';
		$data['page']['body_class'] = 'login_body';
		
		$domain_pos = strpos($_SERVER['HTTP_HOST'], '.'.$this->config->item('domain_name_string'));				
		$subdomain = substr($_SERVER['HTTP_HOST'],0,$domain_pos);
		
		$data['request'] = $this->curl->insecure_api_call('GET', 'checks/account_exists/'.$subdomain);
		if($data['request']['status'] == 'OK'){
			$this->load->view('login',$data);
		}else{
			$this->load->view('login_error',$data);
		}
	}
	
	 
	public function forgot()
	{
		$data['page']['title'] = 'Boost Cloud Accounting | Forgot Password';
		$data['page']['html_class'] = 'login_html';
		$data['page']['body_class'] = 'login_body';

		$this->load->view('forgot',$data);
	}
	
	public function reset()
	{
		
		$data['page']['title'] = 'Boost Cloud Accounting | Reset Password';
		$data['page']['html_class'] = 'login_html';
		$data['page']['body_class'] = 'login_body';
		$data['reset_link'] = $this->uri->segment(3);

		$this->load->view('reset',$data);
	}
}