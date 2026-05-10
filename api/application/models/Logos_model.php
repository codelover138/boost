<?php

class Logos_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function init_params($params)
    {
        $params['table'] = $this->table_prefix . 'logos';
        $params['entity'] = 'logo';

        return $params;
    }

    public function create($params = array(), $post)
    {
        $params = $this->init_params($params);

        $this->load->library('images');

        if (!isset($post['image_string']) || $post['image_string'] == '') {
            return array(
                'status' => 'ERROR',
                'message' => array('Please provide a base64 encoded image string'),
                'validation' => array('image_string' => 'Please provide a base64 encoded image string')
            );
        }

        $logo = $post['image_string'];

        $logo_name = null;
        if (isset($post['logo_name'])) :
            $logo_name = $post['logo_name'];
        endif;

        $dimensions = array();
        if (isset($post['dimensions'])) $dimensions = $post['dimensions'];

        $save_image = $this->images->save_image($logo, $logo_name, $dimensions);
        $post['image_string'] = base_url($save_image);

        $result = $this->generic_model->create($params, $post);

        return $result;
    }

    public function update($params = array(), $id, $post)
    {
        $params = $this->init_params($params);

        $this->load->library('images');

        if (isset($post['image_string'])) {
            $logo_name = null;
            if (isset($post['logo_name'])) :
                $logo_name = $post['logo_name'];
            endif;
            $save_image = $this->images->save_image($post['image_string'], $logo_name);

            $post['image_string'] = base_url($save_image);
        }

        $result = $this->generic_model->update($params, $id, $post);

        return $result;
    }
}