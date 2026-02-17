<?php
class Finance
{
    public $table_prefix;
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('generic_model');
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
    }

    public function calculate_amounts($statement)
    {
        if(!isset($statement['items'])) :
            return false;
        endif;

        # get taxes
        $all_taxes_raw = $this->CI->generic_model->read(array('table'=>'boost_taxes'));
        $all_taxes = array();

        # make taxes indices equal to their respective ids
        foreach($all_taxes_raw as $value) :
            $all_taxes[$value->id] = $value;
        endforeach;

        # initialise
        $sub_total = 0;
        $vat_amount = 0;
        $amounts = array();
        $invalid_numerics = array();

        # calculate
        foreach($statement['items'] as $item) :

            $tax_rate = 0;
            if (isset($item['tax']) && $item['tax'] != 0) :
                $tax_rate = $all_taxes[$item['tax']]->percentage;
            endif;

            $rate = $item['rate'];
            $quantity = $item['quantity'];

            if(is_numeric($rate) && is_numeric($quantity)) :
                $amount_excl = $rate * $quantity; # save this to items array

                if($tax_rate != 0) :
                    $tax_charged = ($rate * $quantity) * ($tax_rate / 100);
                    $vat_amount += $tax_charged;
                endif;

                $sub_total += $amount_excl;

                # saving total_amount to item and saving that to item array
                $item['total_amount'] = $amount_excl;
                $amounts['items'][] = $item;
            else :
                if(!is_numeric($rate)) : $invalid_numerics[] = $rate; endif;
                if(!is_numeric($quantity)) : $invalid_numerics[] = $quantity; endif;
            endif;

        endforeach;

        if(!empty($invalid_numerics)) :
            echo json_encode(array(
                'status'=>'ERROR',
                'message' => array('please ensure that rate and quantity values are numeric'),
                'validation_results' => $invalid_numerics
            ));
            die();
        endif;

        # determine overall amount
        $overall_amount = $sub_total + $vat_amount;

        # check if there's a discount percentage and calculate accordingly
        if(isset($statement['discount_percentage'])) :
            $discount_percentage = $statement['discount_percentage'];
            $amounts['discount_percentage'] = $discount_percentage;
            $discount_price = $overall_amount * ($discount_percentage / 100);
            $amounts['discount_total'] = $discount_price;
            $overall_amount -= $discount_price;
        endif;

        $amounts['sub_total'] = $sub_total;
        $amounts['vat_amount'] = $vat_amount;
        $amounts['total_amount'] = $overall_amount;

        return $amounts;
    }

    public function unique_reference($local_params)
    {
        $local_params['table'];
        $field = $local_params['fields'];

        $local_params['where'] = array(
            $field . ' != ' => '0'
        );

        $current_references = $this->CI->generic_model->read($local_params);
        $numbers = array();

        foreach($current_references as $current_reference)
        {
            $number = (int)str_replace('-', '', filter_var($current_reference->$field, FILTER_SANITIZE_NUMBER_INT));
            if(!in_array($number, $numbers)) :
                $numbers[] = $number;
            endif;
        }

        if (!empty($numbers)) :
            sort($numbers);
            $next_ref = end($numbers) + 1;
        else :
            $next_ref = 1;
        endif;


        return $next_ref;
    }

    public function calculate_invoices($contact_id, $invoice_id = NULL, $exclude_entity_id = null)
    {
        $db_table_prefix = $this->CI->config->item('db_table_prefix');
        $params = array();
        $params['table'] = $db_table_prefix.'invoices';
        $params['fields'] = 'SUM(total_amount) "total_amount"';
        $params['where']['contact_id'] = $contact_id;

        $params['where']['content_status'] = 'active';

        if (is_null($invoice_id)) :
            $params['where']['status !='] = 'draft';
        endif;
        if (!is_null($exclude_entity_id)) :
            $params['where']['id !='] = $exclude_entity_id;
        endif;

        $total = $this->CI->generic_model->read($params, $invoice_id)[0]->total_amount;
        /*$invoice_amounts = $this->CI->generic_model->read($params);

        $total = 0;
        foreach($invoice_amounts as $invoice_amount) :
            $total += $invoice_amount->total_amount;
        endforeach;*/


        return $total;
    }

    public function calculate_estimates($contact_id, $estimate_id = NULL, $exclude_entity_id = null)
    {
        $db_table_prefix = $this->CI->config->item('db_table_prefix');
        $params = array();
        $params['table'] = $db_table_prefix . 'estimates';
        $params['fields'] = 'SUM(total_amount) "total_amount"';
        $params['where']['contact_id'] = $contact_id;

        $params['where']['content_status'] = 'active';

        if (!is_null($estimate_id)) :
            $params['where']['id'] = $estimate_id;
        else :
            $params['where']['status !='] = 'draft';
        endif;
        if (!is_null($exclude_entity_id)) :
            $params['where']['id !='] = $exclude_entity_id;
        endif;

        $total = $this->CI->generic_model->read($params)[0]->total_amount;

        return $total;
    }

    public function calculate_payments($contact_id, $invoice_id = NULL, $exclude_entity_id = null)
    {
        $db_table_prefix = $this->table_prefix;
        $params = array();

        $params['fields'] = 'IFNULL(payment_amount, "0") "payment_amount"';
        $params['main_id_field'] = 'ip.id';
        $params['table'] = $db_table_prefix.'invoice_payments ip';
        $params['where']['i.content_status'] = 'active';

        if (!is_null($invoice_id)) :
            $params['where']['invoice_id'] = $invoice_id;
        endif;

        if (!is_null($exclude_entity_id)) :
            $params['where']['invoice_id !='] = $exclude_entity_id;
        endif;

        $joins = array();
        $joins[0]['table1'] = $db_table_prefix.'invoices i';
        $joins[0]['table2'] = 'i.id = ip.invoice_id';

        $params['join'] = $joins;

        $params['where']['i.contact_id'] = $contact_id;

        $payment_amounts = $this->CI->generic_model->read($params);

        $total = 0;
        foreach($payment_amounts as $payment_amount) :
            $total += $payment_amount->payment_amount;
        endforeach;

        return $total;
    }

    public function calculate_credit($contact_id, $invoice_id = NULL)
    {
        $params = array();
        $params['where']['contact_id'] = $contact_id;

        if (!is_null($invoice_id)) :
            $params['where']['invoice_id'] = $invoice_id;
        endif;

        $params['fields'] = 'SUM(credit) "credit"';

        $this->CI->load->model('credit_model');
        $credit_amount = $this->CI->credit_model->read($params);

        $total = 0;
        foreach ($credit_amount as $credit) :
            $total += $credit->credit;
        endforeach;

        return $total;
    }

    public function calculate_credit_notes($contact_id, $invoice_id = null, $exclude_entity_id = null)
    {
        $db_table_prefix = $this->table_prefix;
        $params = array();

        $params['fields'] = 'SUM(cn.total_amount) "total_amount"';
        $params['main_id_field'] = 'cn.id';
        $params['table'] = $db_table_prefix . 'credit_notes cn';
        //$params['where']['i.content_status'] = 'active';
        $params['where']['cn.status !='] = 'draft';

        if (!is_null($invoice_id)) :
            $params['where']['invoice_id'] = $invoice_id;
        endif;

        if (!is_null($exclude_entity_id)) :
            $params['where']['invoice_id !='] = $exclude_entity_id;
        endif;

        $joins = array();
        $joins[0]['table1'] = $db_table_prefix . 'invoices i';
        $joins[0]['table2'] = 'i.id = cn.invoice_id';

        $params['join'] = $joins;

        $params['where']['i.contact_id'] = $contact_id;

        $total = (float)$this->CI->generic_model->read($params)[0]->total_amount;

        /*$credit_notes_amounts = $this->CI->generic_model->read($params);

        $total = 0;

        if (!empty($credit_notes_amounts)) :
            foreach ($credit_notes_amounts as $credit_notes_amount) :
                $total += $credit_notes_amount->total_amount;
            endforeach;
        endif;*/

        return $total;
    }

    public function credit_used($contact_id, $invoice_id = NULL, $credit_state = null)
    {
        $db_table_prefix = $this->table_prefix;
        $params = array();

        $params['fields'] = 'IFNULL(credit_applied, "0") "credit_applied"';
        $params['main_id_field'] = 'ip.id';
        $params['table'] = $db_table_prefix . 'invoice_payments ip';
        $params['where']['i.content_status'] = 'active';

        if (!is_null($invoice_id)) :
            $params['where']['invoice_id'] = $invoice_id;
        endif;

        if (!is_null($credit_state)) :
            switch ($credit_state) {
                case 'neg':
                    $params['where']['credit <'] = 0;
                    break;
                case 'pos':
                    $params['where']['credit >'] = 0;
                    break;
                default:
                    break;
            }
        endif;

        $joins = array();
        $joins[0]['table1'] = $db_table_prefix . 'invoices i';
        $joins[0]['table2'] = 'i.id = ip.invoice_id';

        $params['join'] = $joins;

        $params['where']['i.contact_id'] = $contact_id;

        $credit_applied = $this->CI->generic_model->read($params);

        $total = 0;
        foreach ($credit_applied as $credit_amount) :
            $total += $credit_amount->credit_applied;
        endforeach;

        return $total;
    }

    public function contact_finances($contact_id, $invoice_id = NULL, $exclude_entity_id = NULL, $estimate_id = null)
    {
        $overall_invoices = $this->calculate_invoices($contact_id, $invoice_id);
        $overall_estimates = $this->calculate_estimates($contact_id, $estimate_id);
        $overall_payments = $this->calculate_payments($contact_id, $invoice_id);
        $overall_credit_notes = $this->calculate_credit_notes($contact_id, $invoice_id);

        $return['total_invoices_amount'] = $overall_invoices;
        $return['paid'] = $overall_payments;
        $return['total_credit_notes'] = $overall_credit_notes;
        $return['credit_used'] = $this->credit_used($contact_id, $invoice_id);
        $return['account_standing'] = ($overall_payments + $overall_credit_notes - $overall_invoices) - $return['credit_used'];
        $return['credit'] = $this->calculate_credit($contact_id, $invoice_id);

        if (is_null($overall_estimates)) {
            $return['estimates'] = 0;
        } else {
            $return['estimates'] = $overall_estimates;
        }

        $credit_used_on_invoice = 0;
        if (!is_null($invoice_id)) {
            $credit_used_on_invoice = $this->calculate_credit($contact_id, $invoice_id, 'neg');
        }

        $return['outstanding'] = ($overall_invoices - $overall_payments) - $overall_credit_notes;
		//echo 'outstanding1='.$return['outstanding'];
        # subtracts credit from oustanding amount only if the outastanding amount is positive
        if ($return['outstanding'] >= 0) :
            $return['outstanding'] += $credit_used_on_invoice;
        endif;
		
		//echo 'outstanding2='.$return['outstanding'];
		//echo 'credit_used_on_invoice='.$credit_used_on_invoice;
		//exit;

        foreach ($return as $key => $value) :
            $return[$key] = round($value, 2);
        endforeach;

        return $return;
    }

    public function invoices_totals($invoice_id)
    {
        $params = array('table' => $this->table_prefix . 'invoices i');

        $params['fields'] = array(
            'IFNULL(i.total_amount, 0) "total_invoice_amount"',
            'IFNULL(SUM(ip.payment_amount), 0) "total_amount_paid"',
            'IFNULL(i.total_amount, 0) - IFNULL(SUM(ip.payment_amount), 0) - IFNULL(SUM(cn.total_amount), 0) "total_amount_outstanding"'
        );

        $params['join'][0]['table1'] = $this->table_prefix . 'invoice_payments ip';
        $params['join'][0]['table2'] = 'ip.invoice_id = i.id';
        $params['join'][0]['type'] = 'left';

        $params['join'][1]['table1'] = $this->table_prefix . 'credit_notes cn';
        $params['join'][1]['table2'] = 'cn.invoice_id = i.id';
        $params['join'][1]['type'] = 'left';

        $params['main_id_field'] = 'i.id';

        $result = $this->CI->generic_model->read($params, $invoice_id)[0];

        return $result;
    }
}