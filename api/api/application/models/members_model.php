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

        if (!empty($post['email'])) {
            $check_params = array(
                'table'  => $this->table,
                'fields' => 'id',
                'where'  => array('email' => $post['email'])
            );
            $exists = $this->generic_model->read($check_params);
            if (!empty($exists)) {
                return array(
                    'bool'               => false,
                    'message'            => array('A user with email ' . $post['email'] . ' already exists'),
                    'validation_results' => array('email' => 'Email already exists')
                );
            }
        }

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