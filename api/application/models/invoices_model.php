<?php

class Invoices_model extends CI_Model
{
    public $table = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model');
    }

    public function read($local_params)
    {
        $params = array();

        $fields = array(
            'DISTINCT(bi.id)',
            'bi.date_created',
            'bc.client_name',
            'bcur.currency_symbol',
            'bcur.currency_name',
            'bcur.short_code "currency_short_code"',
            sql_date_format('bi.date').' "date"',
            sql_date_format('bi.due_date').' "due_date"',
            'bi.`reference`'
        );

        $params['select'] = $fields;
        $params['from'] = 'boost_invoices bi';

        $joins = array();
        $joins[0]['table1'] = 'boost_invoice_items bii';
        $joins[0]['table2'] = 'bii.invoice_id = bi.id';
        $joins[0]['type'] = 'left';

        $joins[1]['table1'] = 'boost_clients bc';
        $joins[1]['table2'] = 'bc.id = bi.client_id';

        $joins[2]['table1'] = 'boost_currencies bcur';
        $joins[2]['table2'] = 'bcur.id = bi.currency_id';

        $params['join'] = $joins;

        if(isset($local_params['id'])) :
            $id = $local_params['id'];
        else :
            $id = null;
        endif;

        if($local_params['order_by']) :
            $params['order_by'] = $local_params['order_by'];
        else :
            $params['order_by'] = 'bi.id';
        endif;


        $result = $this->generic_model->read($params, $id);

        # append items to invoices
        for($i=0; $i < count($result); $i++) :
            $result[$i]->items = $this->invoice_items($result[$i]->id);
        endfor;

        return $result;
    }

    public function invoice_items($id)
    {
        $params = array();
        $params['table'] = 'boost_invoice_items';
        $params['where'] = 'invoice_id = '.$id;

        $query_result = $this->generic_model->read($params);

        return $query_result;
    }

    public function create($params, $post)
    {
        $return = array('bool'=>false);
        $invoice_id = $this->create_invoice($params, $post);

        if($invoice_id) :
            $add_items = $this->create_invoice_items($invoice_id, $post);
            if($add_items) :
                $return['bool'] = true;
                $return['message'] = 'invoice successfully created';
            else :
                $return['message'] = 'error while adding items to invoice';
            endif;
        else :
            $return['message'] = 'error while creating invoice';
        endif;

        return $return;
    }

    public function create_invoice($params, $post)
    {
        $table = $params['table'];
        unset($post['items']);

        if(isset($post['date'])) {$post['date'] = change_to_timestamp($post['date']);}
        if(isset($post['due_date'])) {$post['due_date'] = change_to_timestamp($post['due_date']);}

        $this->db->insert($table, $post);
        $invoice_id = $this->db->insert_id();

        if($invoice_id) :
            return $invoice_id;
        else :
            return false;
        endif;
    }

    public function create_invoice_items($invoice_id, $post)
    {
        $table = 'boost_invoice_items';
        $items = $post;

        # add invoice id to all rows
        for($i = 0; $i < count($items); $i ++) :
            $items[$i]['invoice_id'] = $invoice_id;
        endfor;

        return $this->db->insert_batch($table, $items);
    }

    public function update($params, $id, $post)
    {
        $table = $params['table'];
        $result = array('bool'=>false);

        if(!empty($post) && !empty($id)) :

            $items = $post['items'];
            unset($post['items']);

            $this->db->where('id', $id);

            $post['date_modified'] = current_datetime();
            $post['date'] = change_to_timestamp($post['date']);
            $post['due_date'] = change_to_timestamp($post['due_date']);

            # update invoice information
            $update = $this->db->update($table, $post);
            if($update) :
                $result['bool'] = true;
                # delete already saved invoice items
                $delete = $this->delete($id, 'boost_invoice_items');
                if($delete['bool']) :
                    # add new invoice items to items
                    $add_invoice_items = $this->create_invoice_items($id, $items);
                    if($add_invoice_items) :
                        $result['message'] = 'invoice successfully updated, new items added';
                    else :
                        $result['message'] = 'invoice information successfully updated, could not add invoice items';
                    endif;
                else :
                    $result['message'] = 'invoice information successfully updated, could not remove previous invoice items';
                endif;
            else :
                $result['message'] = 'could not update invoice information';
            endif;

        else :
            $result['message'] = 'empty post or id';
        endif;

        return $result;
    }

    public function delete($params, $id)
    {
        $table = $params['table'];

        $result = array('bool'=>false);

        if(!empty($id)) :

            if($table == 'boost_invoices') :
                $run = $this->delete_invoice($id);
                $entity = 'invoice';
            else :
                $run = $this->delete_invoice_items($id, $table);
                $entity = 'invoice items';
            endif;

            if($run) :
                $result['bool'] = true;
                $result['message'] = $entity.' successfully deleted';
            else :
                $result['message'] = 'could not delete '.$entity;
            endif;
        else :
            $result['message'] = 'empty id';
        endif;

        return $result;
    }

    public function delete_invoice($id)
    {
        $sql = 'DELETE bi, bii ';
        $sql.= 'FROM boost_invoices bi ';
        $sql.= 'LEFT JOIN boost_invoice_items bii ON bii.invoice_id = bi.id ';
        $sql.= 'WHERE bi.id = ' . $this->db->escape_str($id);

        $result = $this->db->query($sql);

        return $result;
    }

    public function delete_invoice_items($id, $table)
    {
        $this->db->where('invoice_id', $id);
        $result = $this->db->delete($table);

        return $result;
    }

}