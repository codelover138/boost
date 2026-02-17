<?php

class Map extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model');
        $this->load->library('regular');
        $this->regular->header_('json');
    }

    public function index()
    {
        # current class methods
        $class_methods = get_class_methods($this);

        $defined_resources = array(

        );

        # excluded methods
        $excluded_methods = array();
        $excluded_methods[] = '__construct';
        $excluded_methods[] = 'index';
        $excluded_methods[] = 'get_instance';

        # resources
        $resources = array();

        foreach($class_methods as $class_method)
        {
            if(!in_array($class_method, $excluded_methods))
            {
                $resources[$class_method] = $this->$class_method($class_method);
                //$resources[$class_method] = $this->regular->basic_resource_map($class_method);
            }
        }

        $this->regular->respond($resources);
    }

    public function clients($name = null)
    {
        $resources = array();
        $resources['get'][] = 'clients/order_by';

        $representation = $this->regular->basic_resource_map($name);

        if(!is_null($name)) :
            return $representation;
        else :
            $this->regular->respond($representation);
        endif;
    }

    public function contacts($name = null)
    {
        $representation = $this->regular->basic_resource_map($name);

        if(!is_null($name)) :
            return $representation;
        else :
            $this->regular->respond($representation);
        endif;
    }

    public function credit_notes($name = null)
    {
        $representation = $this->regular->basic_resource_map($name);

        if(!is_null($name)) :
            return $representation;
        else :
            $this->regular->respond($representation);
        endif;
    }

    public function currencies($name = null)
    {
        $representation = $this->regular->basic_resource_map($name);

        if(!is_null($name)) :
            return $representation;
        else :
            $this->regular->respond($representation);
        endif;
    }

    public function estimates($name = null)
    {
        $representation = $this->regular->basic_resource_map($name);

        if(!is_null($name)) :
            return $representation;
        else :
            $this->regular->respond($representation);
        endif;
    }

    public function invoices($name = null)
    {
        $representation = $this->regular->basic_resource_map($name);

        if(!is_null($name)) :
            return $representation;
        else :
            $this->regular->respond($representation);
        endif;
    }

    public function items($name = null)
    {
        $representation = $this->regular->basic_resource_map($name);

        if(!is_null($name)) :
            return $representation;
        else :
            $this->regular->respond($representation);
        endif;
    }

    public function login($name = null)
    {
        $table = 'boost_members';
        $representation = array();

        $representation['request_methods']['post'] = 'login';
        $representation['fields']['email'] = $this->generic_model->describe($table, 'email');
        $representation['fields']['password'] = $this->generic_model->describe($table, 'password');

        if(!is_null($name)) :
            return $representation;
        else :
            $this->regular->respond($representation);
        endif;
    }

    public function members($name = null)
    {
        $representation = $this->regular->basic_resource_map($name);

        if(!is_null($name)) :
            return $representation;
        else :
            $this->regular->respond($representation);
        endif;
    }
}