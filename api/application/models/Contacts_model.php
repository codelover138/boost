<?php

class Contacts_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model');
    }

    public function read($params, $identifier = null, $indices = 'id')
    {
        $fields = array(
            'c.*',
            'ct.type "contact_type"',
            'cs.size "company_size"',
            'i.industry_name "industry_name"'
        );

        $table_prefix = $this->config->item('db_table_prefix');

        if (!isset($params['select'])) :
            if (isset($params['fields'])) :
                $params['select'] = $params['fields'];
            else :
                $params['select'] = $fields;
            endif;
        endif;

        if (isset($params['table'])) :
            $params['from'] = $params['table'] . ' c';
            $params['table'] = $params['from'];
        else :
            $params['table'] = $table_prefix . 'contacts c';
        endif;

        $joins = array();
        $joins[0]['table1'] = 'boost_contact_types ct';
        $joins[0]['table2'] = 'ct.id = c.contact_type_id';

        $joins[2]['table1'] = 'boost_company_sizes cs';
        $joins[2]['table2'] = 'cs.id = c.company_size_id';
        $joins[2]['type'] = 'left';

        $joins[3]['table1'] = 'boost_industries i';
        $joins[3]['table2'] = 'i.id = c.industry_id';
        $joins[3]['type'] = 'left';

        # adding joins to params array
        $params['join'] = $joins;

        # where
        if(!is_null($identifier) && is_numeric($identifier)) :
            $params['where'] = array('c.id'=>$identifier);
            $params['main_id_field'] = 'c.id';
        endif;

        $result = $this->generic_model->read($params, $identifier, $indices);

        $entity_id = null;
        if (isset($params['entity_id'])) :
            $entity_id = $params['entity_id'];
        endif;

        if (!empty($result)) :
            $this->load->library('finance');

            if ($indices != 'single') {
                foreach ($result as $key => $res) :
                    $finances = $this->finance->contact_finances($res->id, NULL, $entity_id);
                    unset($finances['outstanding']);
                    $result[$key]->account = $finances;
                endforeach;
            } else {
                $finances = $this->finance->contact_finances($result->id, NULL, $entity_id);
                unset($finances['outstanding']);
                $result->account = $finances;
            }

        endif;

        return $result;
    }
}