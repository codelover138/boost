<?php
class Payments_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model');
        $this->table_prefix = $this->config->item('db_table_prefix');
        $this->load->library('finance');
    }

    public function read($params = array(), $identifier = null)
    {
        if (!isset($params['table'])) :
            $params['table'] = $this->table_prefix . 'invoice_payments p';
        else :
            $params['table'] .= ' p';
        endif;

        $params['main_id_field'] = 'p.id';

        if (!isset($params['fields'])) :
            $params['fields'] = array(
                'p.*',
                'i.invoice_number',
                'i.contact_id',
                'c.email "contact_email"',
                'ipm.payment_method'
            );
        endif;

        $joins = array();
        $joins[0]['table1'] = 'boost_invoices i';
        $joins[0]['table2'] = 'i.id = p.invoice_id';

        $joins[1]['table1'] = 'boost_contacts c';
        $joins[1]['table2'] = 'c.id = i.contact_id';

        $joins[2]['table1'] = 'boost_invoice_payment_methods ipm';
        $joins[2]['table2'] = 'ipm.id = p.payment_method_id';

        $params['join'] = $joins;

        $result = $this->generic_model->read($params, $identifier);

        # compiling financial record
        foreach ($result as $key => $record) :

            $finance = $this->finance->contact_finances($record->contact_id, $record->invoice_id);
            $result[$key]->invoice_total_paid = $finance['paid'];
            $result[$key]->invoice_total_outstanding = $finance['outstanding'];

        endforeach;

        return $result;
    }

    public function create($params, $post)
    {
        $invoice_params = array('table' => $this->table_prefix . 'invoices', 'entity' => 'invoice');
        $invoice_data = $this->generic_model->read($invoice_params, $post['invoice_id'])[$post['invoice_id']];
        $contact_id = $invoice_data->contact_id;
        $contact_invoice_account = $this->finance->contact_finances($contact_id, $post['invoice_id']);
        $outstanding_amount = $contact_invoice_account['outstanding'];

        $post['contact_id'] = $contact_id;
        $post['payment_amount'] = monify($post['payment_amount']);

        $log_msg = null;

        if ($invoice_data->status != 'paid')
        {
            $credit_used = 0;

            # APPLY CREDIT ---------------------------------------------------------------------------------------------------
            if ((isset($post['use_credit']) && $post['use_credit'] == 'yes') && (isset($post['payment_amount']) && $post['payment_amount'] < $outstanding_amount))
            {
                $contact_credit = $this->finance->contact_finances($contact_id)['credit'];

                # Save payment method of credit
                if(!isset($post['payment_method_id']) || $post['payment_method_id'] == '')
                {
                    $post['payment_method_id'] = 4;
                }

                if($post['payment_amount'] == '') $post['payment_amount'] = 0.00;

                $payment_amount = $post['payment_amount'];

                $revised_outstanding_amount = $outstanding_amount - $payment_amount;

                if ($contact_credit >= $revised_outstanding_amount) :
                    $credit_used = $revised_outstanding_amount;
                elseif ($contact_credit < $revised_outstanding_amount) :
                    $credit_used = $contact_credit;
                endif;

                $post['credit_applied'] = $credit_used;
            }
            elseif(!is_numeric($post['payment_amount']) || $post['payment_amount'] <= 0)
            {
                //print_r('I got here 2');

                $result = array(
                    'bool' => false,
                    'message' => array('Payment amount must be a number greater than 0 if no credit is applied.'),
                    'validation_results' => array('payment_amount' => 'Please enter an amount.')
                );
                return $result;
            }
            /*-----------------------------------------------------------------------------------------------------------------*/

            #unset the notification data so it is not validated
            if(isset($post['notifiction'])){
                $send_notification = $post['notifiction'];
                unset($post['notifiction']);
            }

            # ADD PAYMENT
            $result = $this->generic_model->create($params, $post);

            # Send payment notification
            if(isset($send_notification) && $result['bool'] != false)
            {
                # log activity variable
           		$log_msg = 'Payment added';
				
				if($send_notification == 'yes')
                {
                    $this->load->library('messaging');

                    $invoice_id = $post['invoice_id'];

                    $payment_message_params = array(
                        'entity' => 'payment',
                        'entity_id' => $invoice_id,
                        'table' => $this->table_prefix. 'invoices',
                        'items_table' => $this->table_prefix . 'invoice_items',
                        'model' => 'template_model',
                        'account_name' => $this->regular->get_request_headers()['Account-Name']
                    );

                    $payment_message = $this->messaging->email_message($payment_message_params);

                    $payment_message_params['email_signature'] = $payment_message['email_signature'];
                    $payment_message_params['message_body'] = $payment_message['email_messages'][$invoice_id];
                    $payment_message_params['contact_email'] = $payment_message['contact_emails'][$invoice_id];
                    $payment_message_params['subject'] = 'BOOST ACCOUNTING - Thank You for Your Payment';

                    $this->messaging->send_email2($payment_message_params);
                }
            }

            

            # change invoice status to paid if the payment amount is greater or equal to outstanding amount-----------------
            $invoice_post = array('single_update' => true);
            if (isset($result['record_id']) && ($post['payment_amount'] >= $outstanding_amount || $credit_used >= $outstanding_amount || $post['payment_amount'] + $credit_used == $outstanding_amount))
            {
                # change invoice status to "paid"
                $invoice_post['status'] = 'paid';
                $this->generic_model->update($invoice_params, $post['invoice_id'], $invoice_post);

                # log activity variable
                $log_msg = 'Payment added. Paid in full';
            }
            elseif ($post['payment_amount'] > 0 && $post['payment_amount'] < $outstanding_amount && $credit_used < $outstanding_amount)
            {
                $invoice_post['status'] = 'partial';
                # change invoice status to "partial"
                $this->generic_model->update($invoice_params, $post['invoice_id'], $invoice_post);

            }
            elseif ($invoice_data->status == 'draft' && $invoice_data->status != 'paid')
            {
                $invoice_post['status'] = 'sent';
                # change invoice status to "sent"
                $this->generic_model->update($invoice_params, $post['invoice_id'], $invoice_post);
            }
            /*------------------------------------------------------------------------------------------------------------*/

            # LOG CREDIT amount if any ---------------------------------------------------------------------------
            if (($post['payment_amount'] > $contact_invoice_account['outstanding'] || $credit_used > 0) && isset($result['record_id']))
            {
                if ($credit_used > 0) :
                    $credit = -($credit_used);

                    # for the sake of activity log to log an amount that was used to pay an invoice
                    $post['payment_amount'] = $credit_used;
                else :
                    $credit = $post['payment_amount'] - $contact_invoice_account['outstanding'];

                    # for the sake of activity log to log an amount that was used to pay an invoice
                    $post['payment_amount'] = $credit;
                endif;

                $credit_post = array(
                    'credit' => $credit,
                    'contact_id' => $contact_id,
                    'invoice_id' => $post['invoice_id'],
                    'payment_id' => $result['record_id'],
                );

                $credit_params = array();

                if($credit_used == 0) $credit_params['apply_credit'] = false;

                $this->credit_model->create($credit_params, $credit_post);
            }
        }
        else
        {
            $result = array(
                'bool' => false,
                'message' => array('Invoice ' . $invoice_data->invoice_number . ' has been marked as "paid".')
            );
        }

        /*------------------------------------------------------------------------------------------------------------*/


        /*-- LOG ACTIVITY -------------------------------------------------------------------------*/
        if(!is_null($log_msg))
        {		   	    
			//print_r($invoice_data);
			
			$a_post = array(
                'label' => 'Invoice #' . $invoice_data->invoice_number,
				'category' => 'invoices',
                'link' => 'invoices/' . $post['invoice_id'],
                'item_id' => $post['invoice_id'],
				'type' => 'success',
                'short_message' => currency($invoice_data->currency_id, 'currency_symbol') . $post['payment_amount'] . ' ' . $log_msg
            );
			//$this->load->model('activities_model');
            $this->activities_model->create($a_post);
        }
        /*------------------------------------------------------------------------------------------*/

        return $result;
    }
}