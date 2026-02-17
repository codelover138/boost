<?php

class Members_model extends CI_Model
{
    public $table = 'boost_members';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model');
    }

    public function create($params, $post)
    {
        $params['table'] = $this->table;
        $result = $this->generic_model->create($params, $post);

        return $result;
    }

    public function read($params)
    {
        $params['table'] = $this->table;

        $result = $this->generic_model->read($params);

        if(isset($result['password'])) :
            unset($result['password']);
        endif;

        return $result;
    }
}