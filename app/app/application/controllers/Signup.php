<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {
	
	
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
		$data['page']['title'] = 'Boost Cloud Accounting | Register';
		$data['page']['html_class'] = 'login_html';
		$data['page']['body_class'] = 'login_body';

		$this->load->view('signup/signup',$data);
	}
	
	public function verify($token = NULL)
	{
		$data['page']['title'] = 'Boost Cloud Accounting | Account Creation';
		$data['page']['html_class'] = 'login_html';
		$data['page']['body_class'] = 'login_body';
		
		$data['signup_token'] = $token;

		$this->load->view('signup/verify',$data);
	}
	
}
