<?php

class Login extends CI_Controller
{
    public function index()
    {
        
        $data = array(
            'main_content'=>'login',
            'page_title'=>'Login'
        );

        $this->load->view('templates/default', $data);
    }

    public function validate_credentials()
    {
        $this->load->model('membership_model');
        $query = $this->membership_model->validate();

        if($query) // if the user's credentials validated...
        {
            $data = array(
                'username'=> $this->input->post('username'),
                'is_logged_in' => true
            );
            $this->session->set_userdata($data);
            redirect('home/member_area');
        }
        else
        {
            $this->index();
        }
    }

    public function signup()
    {
        $data['main_content'] = 'signup_form';
        $this->load->view('includes/template', $data);
    }

    public function create_member()
    {
        $this->load->library('form_validation');
        // field name, error message, validation rules

        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');

        if($this->form_validation->run() == false)
        {
            //$this->load->view('signup_form');
            $this->signup();
        }
        else
        {
            $this->load->model('membership_model');
            $query = $this->membership_model->create_member();

            //var_dump($query);

            if(!$query)
            {
                $this->load->view('signup_form');
            }
            elseif($query == 'user_exists')
            {
                $this->load->view('user_exists');
            }
            else
            {
                $data['main_content'] = 'signup_successful';
                $this->load->view('includes/template', $data);
            }
        }
    }
}