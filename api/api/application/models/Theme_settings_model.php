<?php

class Theme_settings_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function init_params($params = array())
    {
        $params['table'] = $this->table_prefix . 'theme_settings ts';
        $params['entity'] = 'theme settings';

        return $params;
    }

    public function create($params = array(), $post)
    {
        $results = $this->create_update('create', $params, null, $post);

        return $results;
    }

    public function read($params = array(), $identifier = null, $indices = 'id')
    {
        $params = $this->init_params($params);

        $params['join'] = array();
        $params['join'][0]['table1'] = $this->table_prefix . 'themes th';
        $params['join'][0]['table2'] = 'th.id = ts.theme_id';

        $params['fields'] = 'ts.image_string, ts.theme_id, th.theme_name';

        $results = $this->generic_model->read($params, $identifier, $indices);

        return $results;
    }

    public function update($params = array(), $id, $post)
    {
        $results = $this->create_update('update', $params, $id, $post);

        return $results;
    }

    public function create_update($method, $params = array(), $id = null, $post)
    {
        $params = $this->init_params($params);

        $save_image = null;

        if (isset($post['image_string'])) {
            if ($post['image_string'] != '') :
                $post = $this->process_image($post);
                $save_image = $post['save_image'];
                unset($post['save_image']);
            endif;
        }

        # checks if it was a single update
        if (isset($post['single_update']) && $post['single_update'] && isset($save_image['bool'])) {
            if (isset($save_image['bool']) && !$save_image['bool']) :
                $results['bool'] = false;
                $results['message'][] = $save_image['message'][0];
                $results['error_code'] = 403;
            endif;
        } else {
            switch ($method) {
                case 'update':
                    $results = $this->generic_model->update($params, $id, $post);
                    break;
                case 'create':
                    $results = $this->generic_model->create($params, $post);
                    break;
            }

            if (isset($save_image['bool']) && !$save_image['bool']) :
                $results['message'][] = $save_image['message'][0];
            endif;
        }

        return $results;
    }

    public function process_image($post)
    {
        $this->load->library('images');

        $logo = $post['image_string'];
        $logo_name = null;
        if (isset($post['logo_name'])) :
            $logo_name = $post['logo_name'];
            unset($post['logo_name']);
        endif;

        $dimensions = array();
        if (isset($post['dimensions'])) :
            $dimensions = $post['dimensions'];
            unset($post['dimensions']);
        else :
            $dimensions['width'] = 500;
        endif;

        # save image to file system and its location to the post array that will be saved in the database
        $save_image = $this->images->save_image($logo, $logo_name, $dimensions);
        $post['save_image'] = $save_image;

        if ($save_image['bool']) :
            $post['image_string'] = base_url($save_image['output_file']) . '?' . rand(100, 999);
        endif;

        return $post;
    }
}