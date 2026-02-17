<?php

class Credit_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model');
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function params_init($params)
    {
        $params['table'] = $this->table_prefix . 'credit_log';

        $params['entity'] = 'credit';

        return $params;
    }

    public function is_paid($params, $post)
    {
        $result = false;

        $inv_params = array(
            'table' => $this->table_prefix . 'invoices',
            'entity' => 'invoice',
            'fields' => 'status'
        );

        $invoice = $this->generic_model->read($inv_params, $post['invoice_id'], 'single');

        if(isset($params['apply_credit']) && !$params['apply_credit'])
        {
            $result = false;
        }
        elseif($invoice->status == 'paid')
        {
            $result = true;
        }

        return $result;
    }

    public function create($params, $post)
    {
        $params = $this->params_init($params);

        $is_paid = $this->is_paid($params, $post);

        if(!$is_paid)
        {
            $post['organisation_id'] = 1;
            $params['table'] = $this->table_prefix . 'credit_log';

            $result = $this->generic_model->create($params, $post);
        }
        else
        {
            $result = array('bool' => false);
            $result['message'][] = 'Cannot apply credit to an invoice that is already paid';
        }

        return $result;
    }

    public function read($params, $identifier = null, $indices = 'id')
    {
        $fields = array('c.*');

        $params = $this->params_init($params);
        $params['table'] .= ' c';

        if (!isset($params['select'])) :
            if (isset($params['fields'])) :
                $params['select'] = $params['fields'];
            else :
                $params['select'] = $fields;
            endif;
        endif;

        $result = $this->generic_model->read($params, $identifier, $indices);

        return $result;
    }

    public function update($params, $id, $post)
    {
        $is_paid = $this->is_paid($params, $post);

        if(!$is_paid)
        {
            $params = $this->params_init($params);
            $result = $this->generic_model->update($params, $id, $post);
        }
        else
        {
            $result = array('bool' => false);
            $result['message'][] = 'Cannot apply credit to an invoice that is already paid';
        }

        return $result;
    }

    public function delete($params, $id)
    {
        $params = $this->params_init($params);
        $result = $this->generic_model->delete($params, $id);
        return $result;
    }
}