<?php

class Template_model extends CI_Model
{
    public $table = '';
    public $table_prefix;
    public $result_message;
    public $error_fields;
    public $validation_results;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model');
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function read($local_params, $identifier = null, $indices = 'id')
    {
        # array to be sent through carrying sql query information
        $params = array();

        $fields = array(
            'DISTINCT(bi.id)',
			'(SELECT ip.payment_amount FROM boost_invoice_payments ip WHERE bi.id = ip.invoice_id ORDER BY ip.date_created DESC LIMIT 1) AS last_payment',
            'bi.date_created',
            'bi.date_modified',
            'bi.'.$local_params['entity'].'_number',
            'bi.status',
            'bi.discount_percentage',
            'bi.sub_total',
            'bi.discount_total',
            'CAST(bi.vat_amount AS DECIMAL(9,2)) "vat_amount"',
            'CAST(bi.total_amount AS DECIMAL(9,2)) "total_amount"',
            'bi.contact_id',
            'bc.organisation "contact_organisation"',
            'bcur.id "currency_id"',
            'bcur.currency_symbol',
            'bcur.currency_name',
            'bcur.short_code "currency_short_code"',
            sql_date_format('bi.date').' "date"',
            sql_date_format('bi.due_date').' "due_date"',
            'bi.`reference`',
            'bi.`terms`',
            'bi.`closing_note`',
            'bi.`reminder`',
            'bi.`content_status`'
        );

        if(isset($local_params['fields'])) :
            $params['select'] = $local_params['fields'];
        else :
            $params['select'] = $fields;
        endif;

        if ($local_params['table']) :
            $params['from'] = $local_params['table'] . ' bi';
        else :
            $params['from'] = $this->table_prefix . 'invoices bi';
        endif;

        $joins = array();
        $joins[0]['table1'] = $local_params['items_table'].' bii';
        $joins[0]['table2'] = 'bii.'.$local_params['entity'].'_id = bi.id';
        $joins[0]['type'] = 'left';

        $joins[1]['table1'] = 'boost_currencies bcur';
        $joins[1]['table2'] = 'bcur.id = bi.currency_id';
        $joins[1]['type'] = 'left';

        $joins[2]['table1'] = 'boost_contacts bc';
        $joins[2]['table2'] = 'bc.id = bi.contact_id';
        $joins[2]['type'] = 'left';

        /*$joins[3]['table1'] = 'boost_invoice_payments ip';
        $joins[3]['table2'] = 'bi.id = ip.invoice_id';
        $joins[3]['type'] = 'left';*/

        # checks if there is need of a join to the invoice table and that the join is not on the invoices table
        if(isset($local_params['join_invoice_table']) && $local_params['entity'] != 'invoices') :
            # checks that a invoice_id field exists in the table to join from
            if($this->generic_model->describe($local_params['table'], 'invoice_id')) :
                $joins[3]['table1'] = 'boost_invoices inv';
                $joins[3]['table2'] = 'inv.id = bi.invoice_id';
                $joins[3]['type'] = 'left';

                # updating fields array and overwriting the current params select array
                $fields[] = 'inv.id "invoice_id"';
                $params['select'] = $fields;
            endif;
        endif;

        # adding joins to params array
        $params['join'] = $joins;

        #where
        $params['where']['bi.content_status'] = 'active';
        /*if(!is_null($identifier) && is_numeric($identifier)) :
            $params['where']['bi.id'] = $identifier;
        endif;*/

        # where in
        if (isset($local_params['where_in'])) :
            $params['where_in']['field'] = 'bi.' . $local_params['where_in']['field'];
            $params['where_in']['values'] = $local_params['where_in']['values'];
        endif;

        # order by
        if(isset($local_params['order_by'])) :
            $params['order_by'] = $local_params['order_by'];

            if (strpos($params['order_by'], 'status') !== false) :
                $params['order_by'] .= ', bi.due_date ' . str_replace('status ', '', $params['order_by']);
            endif;
        else :
            $params['order_by'] = 'bi.id';
        endif;

        # limit
        if(isset($local_params['limit'])) :
            $params['limit'] = $local_params['limit'];
            if(isset($local_params['offset'])) :
                $params['offset'] = $local_params['offset'];
            endif;
        endif;

        # alternate main id field
        if (isset($local_params['main_id_field'])) :
            $params['main_id_field'] = $local_params['main_id_field'];
        else :
            $params['main_id_field'] = 'bi.id';
        endif;

        # group by
        /*if (isset($local_params['group_by'])) :
            $local_params['group_by'] = $params['group_by'];
        elseif (!isset($local_params['fields'])) :
            $params['group_by'] = 'bc.id';
        endif;*/

        $result = $this->generic_model->read($params, $identifier, $indices);
		

        $original_result = $result;

        $this->load->library('finance');

        if(!is_array($original_result))
        {
            $result = array();
            $result[] = $original_result;
        }

        # append items and contact data to entity record
        foreach ($result as $key => $res) :

            $result[$key]->items = $this->entity_items($local_params, $res->id);
            $result[$key]->contact = $this->contact_data($res->contact_id, $res->id);

            # adding amount totals
            if ($local_params['entity'] == 'invoice') :
                $finance = $this->finance->contact_finances($res->contact_id, $res->id);
                $result[$key]->amount_paid = $finance['paid'];
                $result[$key]->credit_notes_total = $finance['total_credit_notes'];
                $result[$key]->amount_outstanding = $finance['outstanding'];
            elseif ($local_params['entity'] == 'estimate') :
                $finance = $this->finance->contact_finances($res->contact_id, $res->id);
                $result[$key]->amount_outstanding = $finance['estimates'];
            endif;

        endforeach;

        if(!is_array($original_result))
        {
            $result = $result[0];
        }

        return $result;
    }

    public function contact_data($contact_id, $entity_id)
    {
        $this->load->model('contacts_model');
        $params = array();
        $params['entity_id'] = $entity_id;
        $result = $this->contacts_model->read($params, $contact_id);

        if(!empty($result)) :
            return $result[$contact_id];
        else :
            return $result;
        endif;
    }

    public function entity_items($local_params, $id)
    {
        $params = array();
        $params['table'] = $local_params['items_table'];
        $params['where'] = $local_params['entity'].'_id = '.$id;

        $query_result = $this->generic_model->read($params);

        return $query_result;
    }

    public function create($params, $post)
    {
        		
		$return = array('bool'=>false);

        $this->load->library('finance');
        $calculated = $this->finance->calculate_amounts($post);

        # adding calculated amount to post array before saving post content to db ----------------
        if ($calculated && !is_null($calculated)) :
            $post['sub_total'] = $calculated['sub_total'];
            $post['vat_amount'] = $calculated['vat_amount'];
            $post['total_amount'] = $calculated['total_amount'];

            if (isset($calculated['discount_percentage'])) :
                $post['discount_percentage'] = $calculated['discount_percentage'];
                $post['discount_total'] = $calculated['discount_total'];
            endif;

            $post['items'] = $calculated['items'];
        endif;
        #------------------------------------------------------------------------------------------

        $field_validation = $this->generic_model->validate_fields($params, $post);

        # checking if entity number already exists -----------------------------------------------------------------------------------------------
        $params['fields'] = $params['entity'] . '_number';
        $params['where'] = array(
            $params['fields'] => $post[$params['entity'] . '_number']
        );
        $number_exists = $this->generic_model->exists($params);
        //$number_exists = $this->entity_number_exists($params, $id);

        if ($number_exists == true) :
            $this->validation_results[$params['entity'] . '_number'] = $params['entity'] . ' "' . $post[$params['entity'] . '_number'] . '" already exists';
            $this->result_message[] = $params['entity'] . ' "' . $post[$params['entity'] . '_number'] . '" already exists';
        endif;
        #--------------------------------------------------------------------------------------------------------------------------------------------

        # remove items from missing fields
        if (isset($field_validation['field_errors']['items'])) :
            unset($field_validation['field_errors']['items']);
        endif;

        if ($field_validation['bool']) {
            $id = $this->create_entity($params, $post);
        } else {
            $id['bool'] = false;
        }

        if($id['bool']) :
            $add_items = $this->create_entity_items($params['entity'], $id['id'], $post['items']);

            if ($add_items['bool'] && $add_items['data']) {
                $return['bool'] = true;
                $return['insert_id'] = $id;
                $return['record_id'] = $id['id'];
                $this->result_message[] = $params['entity'] . ' successfully created';
                $currency = $this->get_currency($post['currency_id']);
				
				//if the entitty is a allowed document
				if($params['entity'] == 'invoice' || $params['entity'] == 'estimate' || $params['entity'] == 'credit_note'){
					//update activity
					$a_post = array(
						'label' => ucwords(str_replace('_',' ',$params['entity'])) . ' #' . $post[$params['entity'] . '_number'],
						'category' => $params['entity'] . 's',
						'link' => $params['entity'] . 's/' . $id['id'],
						'item_id' => $id['id'],
						'type' => 'standard',
						'short_message' => $currency->currency_symbol . $post['total_amount'] . ' created'
					);
					$this->activities_model->create($a_post);
				}
				
            } elseif (!$add_items['bool']) {
                $field_validation['field_errors']['items'] = $add_items['errors'];
                $this->result_message[] = 'Please ensure that all ' . $params['entity'] . ' items have a description.';

                $this->delete_entity($id['id'], $params);
            } else {
                $this->result_message[] = 'error while adding items to ' . $params['entity'];
            }

        else :
            if(isset($id['message'])) :
                $this->result_message[] = $id['message'];
            else :
                //$this->result_message[] = 'error while creating ' . $params['entity'] . ' ';
            endif;

        endif;

        if (!$field_validation['bool']) {

            foreach ($field_validation['message'] as $message) :
                $this->result_message[] = $message;
            endforeach;

            unset($field_validation['bool']);
            unset($field_validation['message']);

            //$return['validation_results'] = $field_validation['field_errors'];

            foreach ($field_validation['field_errors'] as $field => $error) :
                $this->validation_results[$field] = $error;
            endforeach;
        }

        $return['message'] = $this->result_message;
        $return['validation_results'] = $this->validation_results;

        return $return;
    }

    public function create_entity($params, $post)
    {
        $result = array('bool'=>false);

        $post['date_modified'] = current_datetime();

        $try = false;
        if (!isset($post[$params['entity'] . '_number'])) {
            $result['message'][] = 'Please provide ' . $params['entity'] . ' number.';
        } else {
            $params['fields'] = $params['entity'] . '_number';

            $params['where'] = array(
                $params['fields'] => $post[$params['entity'] . '_number']
            );

            $exists = $this->generic_model->exists($params);

            if ($exists == true) :
                $this->validation_results[$params['entity'] . '_number'] = $params['entity'] . ' "' . $post[$params['entity'] . '_number'] . '" already exists';
                $this->result_message[] = $params['entity'] . ' "' . $post[$params['entity'] . '_number'] . '" already exists';
            endif;

            $try = true;
        }

        if ($try && !$exists)
        {
            $entity_id = false;

            if (isset($post['items'])) {
                $table = $params['table'];
                unset($post['items']);

                if (isset($post['date'])) {
                    $post['date'] = change_to_timestamp($post['date']);
                }
                if (isset($post['due_date'])) {
                    $post['due_date'] = change_to_timestamp($post['due_date']);
                }

                if (isset($post['contact_id']) && $post['contact_id'] > 0) :
                    $this->db->insert($table, $post);
                    $entity_id = $this->db->insert_id();
                    $result['record_id'] = $this->db->insert_id();
                else :
                    $entity_id = false;
                    $result['message'][] = 'contact id is missing';
                endif;
            } else {
                $result['message'][] = 'there are no items in the ' . $params['entity'];
            }

            if($entity_id) :
                $result['bool'] = true;
                $result['id'] = $entity_id;
            endif;
        }
        else
        {
            $this->validation_results[$params['entity'] . '_number'] = $params['entity'] . ' "' . $post[$params['entity'] . '_number'] . '" already exists';
            //$result['message'][] = $params['entity'] . ' "' . $post[$params['entity'] . '_number'] . '" already exists';
        }

        return $result;
    }

    public function create_entity_items($entity, $id, $post)
    {
        $table = 'boost_'.$entity.'_items';
        $results = array();
        $errors = array();

        if (!empty($post)) :

            # add entity id to all invoice item rows
            for ($i = 0; $i < count($post); $i++) :

                $post = $this->remove_empty_items($post);

                if (!isset($post[$i]['description']) || $post[$i]['description'] == '' || is_null($post[$i]['description'])) :
                    $errors[$i] = 'Please add description in empty description field(s).';
                    $this->validation_results['items'][$i]['description'] = 'Please add a description.';
                endif;

                $post[$i][$entity . '_id'] = $id;
            endfor;
        else :
            $this->validation_results['items'] = 'Please add ' . $entity . ' items.';
            $this->result_message[] = 'Please add ' . $entity . ' items.';
        endif;

        if (!empty($errors)) :
            $results['bool'] = false;
            $results['errors'] = $errors;
        else :
            $results['bool'] = true;
            //$results['data'] = $this->db->insert_batch($table, $post);

            foreach ($post as $entry) {
                $results['data'][] = $this->db->insert($table, $entry);
            }

        endif;

        return $results;
    }

    public function is_empty_item($post)
    {
        if ($post['description'] == '' && $post['item_name'] == '' && $post['quantity'] == '0' && $post['rate'] == '0') :
            return true;
        else :
            return false;
        endif;
    }

    public function remove_empty_items($post)
    {
        $return_post = array();

        foreach ($post as $item) {
            $empty = $this->is_empty_item($item);
            if (!$empty) :
                $return_post[] = $item;
            endif;
        }

        return $return_post;
    }

    public function update($params, $id, $post)
    {
        $table = $params['table'];
        $result = array('bool' => false, 'record_id' => $id);
        //$result['message'] = '';

        if(!empty($post) && !empty($id)) :

            if (isset($post['invoice_number'])) :
                # check if entity is marked as sent ---------------------------------------------
                $sent_sql = 'SELECT ' . $this->db->escape_str('status, invoice_number');
                $sent_sql .= ' FROM ' . $this->db->escape_str($params['table']);
                $sent_sql .= ' WHERE status IN("' . strtolower('sent') . '", "' . strtolower('paid') . '")';
                $sent_sql .= ' AND id = ' . $this->db->escape_str($id);

                $query = $this->db->query($sent_sql);

                if ($query->num_rows() > 0 && $post['invoice_number'] != $query->result()[0]->invoice_number) :
                    $result['message'][] = 'this ' . $params['entity'] . ' has been marked as sent therefore, the ' . $params['entity'] . ' number can no longer be modified. ' . "\n";
                    $post['invoice_number'] = $query->result()[0]->invoice_number;
                endif;
            endif;

            /*------------------------------------------------------------------------------*/

            # Check if this is an update on single field
            $single_update = false;
            if (isset($post['single_update']))
            {
                $single_update = $post['single_update'];
                unset($post['single_update']);
            }

            $calculated = false;
            if (!$single_update) :
                $this->load->library('finance');
                $calculated = $this->finance->calculate_amounts($post);
            endif;

            # adding calculated content to post array ---------------------------------------------
            if ($calculated) :
                $post['sub_total'] = $calculated['sub_total'];
                $post['vat_amount'] = $calculated['vat_amount'];
                $post['total_amount'] = $calculated['total_amount'];

                if (isset($calculated['discount_percentage'])) :
                    $post['discount_percentage'] = $calculated['discount_percentage'];
                    $post['discount_total'] = $calculated['discount_total'];
                endif;

                $items = $calculated['items'];
                unset($post['items']);
            endif;

            #---------------------------------------------------------------------------------------

            # Check if the total amount value is the same as the previous and adjust invoice status accordingly if it isn't
            if($params['entity'] == 'invoice')
            {
                $i_params = $params;
                $invoice_before_update = $this->generic_model->read($i_params, $id, 'single');

                if(!empty($invoice_before_update) && isset($post['total_amount']) && $invoice_before_update->total_amount != $post['total_amount'])
                {
                    if($post['total_amount'] > $invoice_before_update->total_amount)
                    {
                        if($post['status'] == 'paid') $post['status'] = 'partial';
                    }
                }
            }

            $this->db->where('id', $id);

            $post['date_modified'] = current_datetime();
            if (isset($post['date'])) :
                $post['date'] = change_to_timestamp($post['date']);
            endif;
            if (isset($post['due_date'])) :
                $post['due_date'] = change_to_timestamp($post['due_date']);
            endif;

            # update entity information

            $validation = $this->generic_model->validate_fields($params, $post);

            # remove items from missing fields
            if (isset($validation['field_errors']['items'])) :
                unset($validation['field_errors']['items']);
            endif;

            $exists = false;
            if (isset($post[$params['entity'] . '_number'])) :
                $entity_number = $post[$params['entity'] . '_number'];

                $sql = 'SELECT ' . $this->db->escape_str($params['entity'] . '_number');
                $sql .= ' FROM ' . $this->db->escape_str($table);
                $sql .= ' WHERE ' . $this->db->escape_str($params['entity'] . '_number') . ' = "' . $this->db->escape_str($entity_number) . '"';
                $sql .= ' AND id != ' . $this->db->escape_str($id);

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0) :
                    $exists = true;
                endif;
            endif;

            if (!$exists) {
                if ($validation['bool']) :

                    # Only change status to "sent" if current invoice status is "draft,"
                    if($single_update)
                    {
                        if ($params['entity'] == 'invoice' || $params['entity'] == 'credit_note')
                        {
                            if (isset($post['status']))
                            {
                                $this->db->where_in('status',array('draft','sent'));
                            }
                        }
                    }

                    $update = $this->db->update($params['table'], $post);

                    $affected_rows = $this->db->affected_rows();

                    if ($single_update && $affected_rows > 0) :
					
						
					
                        $result['message'][] = 'single record successfully updated. ';
                        $result['bool'] = true;
                        return $result;
                    elseif ($single_update && $affected_rows == 0) :
                        if (isset($post['status'])) :
                            $result['message'][] = 'could not update status to ' . $post['status'] . '.';
                        else :
                            $result['message'][] = 'could not update record.';
                        endif;

                        $result['bool'] = false;
                        return $result;
                    endif;

                else :
                    $update = false;
                    //$result['message'] = $validation['message'];
                    //print_r($validation['message']);

                    unset($validation['bool']);
                    unset($validation['message']);

                    $result['validation_results'] = $validation['field_errors'];
                endif;
            } else {
                $result['message'] = 'could not update ' . $params['entity'] . '. ' . $params['entity'] . ' number: "' . $entity_number;
                $result['message'] .= '" already exists';

                return $result;
            }

            #--------------------------------------------------------------------------------

            if ($update && !$single_update) :
                $result['bool'] = true;
                # delete already saved enitity items
                $delete = $this->delete($params, $id, false);
                if($delete['bool']) :
                    # add new enitity items to items
                    $add_entity_items = $this->create_entity_items($params['entity'], $id, $items);

                    if ($add_entity_items['bool']) :
                        $result['message'][] = $params['entity'] . ' successfully updated, new items added';
                    else :
                        $result['message'][] = $params['entity'] . ' information successfully updated, could not add entity items';

                        if (isset($add_entity_items['errors'])) {
                            $result['bool'] = false;
                            $result['validation_results'] = $this->validation_results;
                            $result['message'] = $add_entity_items['errors'];
                        }

                    endif;
                else :
                    $result['message'][] = $params['entity'] . ' information successfully updated, could not remove previous ' . $params['entity'] . ' items';
                endif;
            elseif (!$single_update) :
                $result['message'] = ' could not update ' . $params['entity'] . ' information';
            endif;

        else :
            $result['message'][] = 'empty post or id';
        endif;

        return $result;
    }

    public function delete($params, $id, $delete_entity = true)
    {
        $result = array('bool'=>false);

        if(!empty($id)) :

            if($delete_entity) :
                $run = $this->delete_entity($id, $params);
                $entity = $params['entity'];
            else :
                $run = $this->delete_entity_items($id, $params);
                $entity = $params['entity'].' items';
            endif;

            if($run) :
                $result['bool'] = true;
                $result['message'][] = $entity.' successfully deleted';
            else :
                $result['message'][] = 'could not delete '.$entity;
            endif;
        else :
            $result['message'][] = 'empty id';
        endif;

        return $result;
    }

    public function delete_entity($id, $params)
    {
        $sql = 'DELETE bi, bii ';
        $sql.= 'FROM '.$params['table'].' bi ';
        $sql.= 'LEFT JOIN boost_'.$params['entity'].'_items bii ON bii.'.$params['entity'].'_id = bi.id ';
        $sql.= 'WHERE bi.id = ' . $this->db->escape_str($id);

        $result = $this->db->query($sql);

        return $result;
    }

    public function delete_entity_items($id, $params)
    {
        $this->db->where($params['entity'].'_id', $id);
        $result = $this->db->delete($params['items_table']);

        return $result;
    }

    /*--EXTRAS--------------------------------------------------------------------------------------------------------------------------------*/

    public function get_currency($id)
    {
        $params = array(
            'table' => $this->table_prefix . 'currencies',
            'entity' => 'currency'
        );

        $result = $this->generic_model->read($params, $id, 'single');

        return $result;
    }

    public function entity_number_exists($params, $id)
    {
        $exists = false;
        if (isset($post[$params['entity'] . '_number'])) :
            $entity_number = $post[$params['entity'] . '_number'];

            $sql = 'SELECT ' . $this->db->escape_str($params['entity'] . '_number');
            $sql .= ' FROM ' . $this->db->escape_str($params['table']);
            $sql .= ' WHERE ' . $this->db->escape_str($params['entity'] . '_number') . ' = "' . $this->db->escape_str($entity_number) . '"';
            $sql .= ' AND id != ' . $this->db->escape_str($id);

            $query = $this->db->query($sql);

            if ($query->num_rows() > 0) :
                $exists = true;
            endif;
        endif;

        return $exists;
    }
}