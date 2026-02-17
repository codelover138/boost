<?php

class Statements_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function read($contact_id, $filters = array())
    {
        if(empty($filters)) $filters['period'] = 30;

        $invoices = $this->invoices($contact_id, $filters);
        $credit_notes = $this->credit_notes($contact_id, $filters);
        $payments = $this->invoice_payments($contact_id, $filters);

        $return = array_merge($invoices, $credit_notes, $payments);

        return $return;
    }

    public function invoices($contact_id, $filters = array())
    {
        $alias = 'inv';
        $params = array();

        $params['table'] = $this->table_prefix . 'invoices inv';
        $params['fields'] = array();

        $params['fields'][] = 'inv.id "invoice_id"';
        $params['fields'][] = 'inv.invoice_number';
        $params['fields'][] = 'inv.date_created "invoice_date_created"';
        $params['fields'][] = 'inv.date_modified "invoice_date_modified"';
        $params['fields'][] = 'inv.date "invoice_date"';
        $params['fields'][] = 'inv.due_date "invoice_due_date"';
        $params['fields'][] = 'inv.discount_percentage "invoice_discount_percentage"';
        $params['fields'][] = 'inv.discount_total "invoice_discount_total"';
        $params['fields'][] = 'inv.sub_total "invoice_sub_total"';
        $params['fields'][] = 'inv.total_amount "invoice_total_amount"';

        $params['main_id_field'] = 'inv.contact_id';

        $params['where']['status !='] = 'draft';

        $params['order_by'] = 'inv.date_created';
		
		//change was made to make this generic
        //$resolve_filters = $this->resolve_filters($params, $filters, $alias, 'date');
		$resolve_filters = $this->generic_model->resolve_filters($params, $filters, $alias, 'date');
        $params = $resolve_filters;

        $results = $this->generic_model->read($params, $contact_id, 'zero');

        return $results;
    }

    public function credit_notes($contact_id, $filters = array())
    {
        # Credit Notes
        $alias = 'cn';
        $params['table'] = $this->table_prefix . 'credit_notes cn';
        $params['fields'] = array();

        $params['fields'][] = 'cn.id "credit_note_id"';
        $params['fields'][] = 'cn.credit_note_number';
        $params['fields'][] = 'cn.date_created "credit_note_date_created"';
        $params['fields'][] = 'cn.date_modified "credit_note_date_modified"';
        $params['fields'][] = 'cn.date "credit_note_date"';
        $params['fields'][] = 'cn.due_date "credit_note_due_date"';
        $params['fields'][] = 'cn.discount_percentage "credit_note_discount_percentage"';
        $params['fields'][] = 'cn.discount_total "credit_note_discount_total"';
        $params['fields'][] = 'cn.sub_total "credit_note_sub_total"';
        $params['fields'][] = 'cn.total_amount "credit_note_total_amount"';
        $params['fields'][] = 'cn.reference "credit_note_reference"';

        $params['main_id_field'] = 'cn.contact_id';

        //$params['where']['status !='] = 'draft';

        $params['order_by'] = 'cn.date_created';

        $resolve_filters = $this->generic_model->resolve_filters($params, $filters, $alias, 'date');
        $params = $resolve_filters;

        $results = $this->generic_model->read($params, $contact_id, 'zero');

        return $results;
    }

    public function invoice_payments($contact_id, $filters = array())
    {
        # Invoice Payments
        $alias = 'pay';

        $params['table'] = $this->table_prefix . 'invoice_payments '.$alias;
        $params['fields'] = array();

        $params['fields'][] = 'pay.id "payment_id"';
        $params['fields'][] = 'pay.date_created "payment_date_created"';
        $params['fields'][] = 'pay.date_modified "payment_date_modified"';
        $params['fields'][] = 'pay.payment_date';
        $params['fields'][] = 'pay.payment_amount';
        $params['fields'][] = 'pay.payment_method_id';
        $params['fields'][] = 'pay.reference "payment_reference"';
        $params['fields'][] = 'pay.credit_applied "payment_credit_applied"';
        $params['fields'][] = 'pay.notification "payment_notification"';
        $params['fields'][] = 'pay.use_credit "payment_use_credit"';

        $params['main_id_field'] = 'pay.contact_id';

        $params['order_by'] = 'pay.date_created';

        $resolve_filters = $this->generic_model->resolve_filters($params, $filters, $alias, 'date_created');
        $params = $resolve_filters;

        $results = $this->generic_model->read($params, $contact_id, 'zero');

        return $results;
    }

    public function credit_log($contact_id, $filters = array())
    {
        # Credit log
        $alias = 'cl';

        $params['table'] = $this->table_prefix . 'credit_log '.$alias;
        $params['fields'] = array();

        $params['fields'][] = 'cl.id "credit_log_id"';
        $params['fields'][] = 'cl.date_created "credit_log_date_created"';
        $params['fields'][] = 'cl.credit "credit_log_credit"';

        $params['main_id_field'] = 'cl.contact_id';

        $params['order_by'] = 'cl.date_created';

        $resolve_filters = $this->generic_model->resolve_filters($params, $filters, $alias, 'date_created');
        $params = $resolve_filters;

        $results = $this->generic_model->read($params, $contact_id, 'zero');

        return $results;
    }
	
	/*
	// this was moved to generic model and $db_col was added as a parameter to make the function dynamic
	// this code has been commented out until statements can be tested to make sure there are no complications
	// if you are seeing this then test the statements section and remove the commented code if it is working correctly
	// if a complication is found then remove the last parameter from all resolve_filters() calls in this file and change $this->generic_model->resolve_filters() to $this->resolve_filters(
    public function resolve_filters($params, $filters, $alias)
    {
        if((isset($filters['start_date']) || isset($filters['end_date'])))
        {
            $end_date = current_datetime();

            $date_param_index = "$alias.date_created ";

            if(isset($filters['start_date'])) {
                $start_date = $filters['start_date'];
                $date_param_index .= "BETWEEN '". $start_date . "' AND";
            }
            else {
                $date_param_index .= "<=";
            }

            if(isset($filters['end_date'])) {
                $end_date = date('Y-m-d', strtotime($filters['end_date'])) . ' 23:59:59';
            }

            $params['where'][$date_param_index] = $end_date;
        }
        elseif(isset($filters['period']))
        {
            $period = $filters['period'];
            $period_filter = $this->period_filter($period);

            $start_date = $period_filter['start_date'];
            $end_date = $period_filter['end_date'];

            $date_param_index = "$alias.date_created BETWEEN '" . $start_date . "' AND";
            $params['where'][$date_param_index] = $end_date;
        }
        else
        {
            $period_filter = $this->period_filter();

            $start_date = $period_filter['start_date'];
            $end_date = $period_filter['end_date'];

            $date_param_index = "$alias.date_created BETWEEN '" . $start_date . "' AND";
            $params['where'][$date_param_index] = $end_date;
        }

        if(isset($filters['order_by'])) $params['order_by'] = $filters['order_by'];

        return $params;
    }

    public function period_filter($period = 30)
    {
        $period -= 1; # subtract 1 from period

        $return['end_date'] = current_datetime();

        $start_date_obj = new DateTime(date("Y-m-d") . " 23:59:59");
        $start_date_obj->sub(new DateInterval('P'.$period.'D'));
        $return['start_date'] = $start_date_obj->format('Y-m-d');

        return $return;
    }
	
	*/

    public function totals($contact_id, $filters = array())
    {
        # All
        $totals_to_date = $this->calculate_totals($contact_id, array('end_date'=>$filters['end_date']));
        $total_to_date = $totals_to_date['invoices'] - $totals_to_date['payments'] - $totals_to_date['credit_note'];

        $totals = $totals_to_date;

        /*-------------------------------------------------------------------*/

        //if(isset($filters['start_date'])) unset($filters['start_date']);
        if(!isset($filters['end_date'])) $filters['end_date'] = current_datetime();

        $filtered_totals = $this->calculate_totals($contact_id, $filters);

        $ft = $filtered_totals['invoices'] - $filtered_totals['payments'] - $filtered_totals['credit_note'];

        $totals['balance_brought_forward'] = $total_to_date - $ft;

        return $totals;
    }

    public function calculate_totals($contact_id, $filters = array())
    {
        $totals = array();

        /*----------------------------------------------------------------------------
         * INVOICES
         * --------------------------------------------------------------------------*/
        $alias = 'inv';
        $inv_params = array(
            'table' => $this->table_prefix . 'invoices '.$alias,
            'entity' => 'invoice',
            'fields' => 'ROUND(SUM('.$alias.'.total_amount), "2") "sum_invoices"',
            'where' => array(
                $alias.'.contact_id' => $contact_id,
                $alias.'.status !=' => 'draft'
            )
        );

        if(!empty($filters))
        {
            $resolve_filters = $this->generic_model->resolve_filters($inv_params, $filters, $alias, 'date');
            $inv_params = $resolve_filters;
        }

        $totals['invoices'] = $this->generic_model->read($inv_params, null, 'single')->sum_invoices;


        /*----------------------------------------------------------------------------
         * CREDIT NOTES
         * --------------------------------------------------------------------------*/
        $alias = 'cn';
        $credit_note_params = array(
            'table' => $this->table_prefix . 'credit_notes '. $alias,
            'entity' => 'credit_note',
            'fields' => 'SUM('.$alias.'.total_amount) "sum_credit_notes"',
            'where' => array(
                $alias.'.contact_id' => $contact_id,
                //$alias.'.status !=' => 'draft'
            )
        );

        if(!empty($filters)) {
            $resolve_filters = $this->generic_model->resolve_filters($credit_note_params, $filters, $alias, 'date');
            $credit_note_params = $resolve_filters;
        }

        $totals['credit_note'] = $this->generic_model->read($credit_note_params, null, 'single')->sum_credit_notes;


        /*----------------------------------------------------------------------------
         * PAYMENTS
         * --------------------------------------------------------------------------*/
        $alias = 'pay';
        $payments_params = array(
            'table' => $this->table_prefix . 'invoice_payments '.$alias,
            'entity' => 'payment',
            'fields' => 'SUM('.$alias.'.payment_amount) "sum_payments_amount"',
            'where' => array(
                $alias.'.contact_id' => $contact_id
            )
        );

        if(!empty($filters)) {
            $resolve_filters = $this->generic_model->resolve_filters($payments_params, $filters, $alias, 'date_created');
            $payments_params = $resolve_filters;
        }

        $totals['payments'] = $this->generic_model->read($payments_params, null, 'single')->sum_payments_amount;

        /*----------------------------------------------------------------------------
         * CREDIT
         * --------------------------------------------------------------------------*/
        $alias = 'cl';
        $credit_params = array(
            'table' => $this->table_prefix . 'credit_log '.$alias,
            'entity' => 'credit',
            'fields' => 'SUM('.$alias.'.credit) "sum_credit"',
            'where' => array(
                $alias.'.contact_id' => $contact_id
            )
        );

        if(!empty($filters)) {
            $resolve_filters = $this->generic_model->resolve_filters($credit_params, $filters, $alias, 'date_created');
            $credit_params = $resolve_filters;
        }

        $totals['credit'] = $this->generic_model->read($credit_params, null, 'single')->sum_credit;


        return $totals;
    }
}