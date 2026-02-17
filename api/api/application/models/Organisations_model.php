<?php

class Organisations_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function init_params($params = array())
    {
        $params['table'] = $this->table_prefix . 'organisations org';
        $params['entity'] = 'organisation';

        return $params;
    }

    public function read($params = array(), $identifier = null, $indices = 'id')
    {
        $params = $this->init_params($params);

        $params['join'] = array();
        $params['join'][0]['table1'] = $this->table_prefix . 'countries c';
        $params['join'][0]['table2'] = 'c.id = org.country_id';
        $params['join'][0]['type'] = 'left';

        $params['join'][1]['table1'] = $this->table_prefix . 'industries ind';
        $params['join'][1]['table2'] = 'ind.id = org.industry_id';
        $params['join'][1]['type'] = 'left';

        $params['fields'] = array(
            'org.*',
            'c.country',
            'ind.industry_name "industry"'
        );

        $results = $this->generic_model->read($params, $identifier, $indices);

        return $results;
    }

    public function update($params, $id, $post = null)
    {
        $return = array('bool' => false);

        if(isset($post['account_url']) && $post['account_url'] !='') $post['account_name'] = $post['account_url'];

        # get data from account db
        $read = $this->generic_model->read($params, 1, 'single');

        # switch params
        $switch_params = array('account_name' => $read->account_name);

        # switch to main db
        $this->load->library('db/switcher', $switch_params);
        $this->switcher->main_db();


        $exists_params = array('table' => $params['table']);
        $exists_params['where']['id'] = $read->account_id;
        $exists_params['where']['account_name'] = $post['account_name'];

        # check is account name given is different from the saved one
        if($this->generic_model->exists($exists_params))
        {
            unset($post['account_name']);
            unset($post['account_url']);

            # update in account db
            $this->switcher->account_db();
            $this->generic_model->update($params, $id, $post);

            # update data in main db
            $this->switcher->main_db();
            $return = $this->generic_model->update($params, $read->account_id, $post);
        }
        else
        {
            $exists_where = array('table' => $params['table']);
            $exists_where['where']['id <>'] = $read->account_id;
            $exists_where['where']['account_name'] = $post['account_name'];

            if($this->generic_model->exists($exists_where)) # checks to see if account name is already used by someone else
            {
                $return['message'][] = 'Account name "'.$post['account_name'].'" is not available.';
            }
            else
            {
                # update in account db
                $this->switcher->account_db();
                $this->generic_model->update($params, $id, $post);

                # update data in main db
                $this->switcher->main_db();
                $return = $this->generic_model->update($params, $read->account_id, $post);

                if($return['bool']) :
                    $return['action'] = array('account_name' => $post['account_name']);
                endif;
            }
        }

        return $return;
    }
}