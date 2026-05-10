<?php

class Credit_notes_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('template_model');
        $this->table_prefix = $this->config->item('db_table_prefix');
        $this->load->library('finance');
    }

    public function create($params, $post)
    {
        $result = $this->template_model->create($params, $post);
        //$this->verify_credit($post, $result['record_id']);
        return $result;
    }

    public function read($params, $identifier = null, $indices = 'id')
    {
        $result = $this->template_model->read($params, $identifier, $indices);
        return $result;
    }

    public function update($params, $id, $post)
    {
        $original_post = $post;

        # Checks if this is just a status change
        if(isset($post['single_update']) && $post['single_update'])
        {
          
		    # Get credit note data
            $credit_note = $this->read($params, $id, 'single');
			
            # If the credit note exists add the invoice id to the post array
            if(!empty($credit_note)) {
                $post = (array)$credit_note;
                $post['status'] = $original_post['status'];

                $post['contact'] = (array)$post['contact'];
                foreach($post['items'] as $key => $item)
                {
                    $post['items'][$key] = (array)$item;
                }
            }
        }

        # Validates the invoice content and changes the relating invoice's status accordingly
        $this->verify_credit($post, $id, true);

        $result = $this->template_model->update($params, $id, $original_post);
        return $result;
    }

    public function delete($params, $id, $delete_entity = true)
    {
        $result = $this->template_model->delete($params, $id, $delete_entity);
        return $result;
    }

    public function verify_credit($post, $id, $update = false)
    {
        if(isset($post['invoice_id']) && !is_null($post['invoice_id']))
        {
            # check if invoice id exists
            $inv_params = array(
                'table'=>$this->table_prefix . 'invoices',
                'where'=>array('id'=>$post['invoice_id'])
            );
            $invoice = $this->generic_model->exists($inv_params, true);

            # begin to make updates around the invoice if it exists
            if($invoice)
            {
                # get credit note details
                $credit_note_params = array(
                    'table' => $this->table_prefix . 'credit_notes',
                    'where' => array(
                        'id' => $id,
                        'invoice_id' => $invoice->id
                    )
                );
                $credit_note = $this->generic_model->exists($credit_note_params, true);

                # if credit note exists
                if ($credit_note)
                {
                    # load credit model
                    $this->load->model('credit_model');

                    $credit_log_params = array(
                        'table' => $this->table_prefix . 'credit_log',
                        'where' => array(
                            'credit_note_id' => $credit_note->id,
                            'invoice_id' => $invoice->id
                        )
                    );
                    $credit_log = $this->generic_model->exists($credit_log_params, true);

                    # credit model params
                    $cm_params = array(
                        'table' => $this->table_prefix . 'credit_log',
                        'entity' => 'credit',
                        'main_id_field' => 'credit_note_id'
                    );

                    # getting contact's invoice amounts
                    $contact_invoice_account = $this->finance->contact_finances($invoice->contact_id, $post['invoice_id']);

                    # getting overall credit note amount and the invoice account details
                    if (!$update) :
                        $credit_note_amount = $credit_note->total_amount; //$this->finance->calculate_amounts($post)['total_amount'];
                    else :
                        $credit_note_amount = $this->finance->calculate_amounts($post)['total_amount'];

                        # if there is a difference between the current and former credit note amount
                        $credit_note_amount_diff = num_difference($credit_note_amount, $credit_note->total_amount);
                        if ($credit_note_amount_diff > 0) :
                            # revert outsanding invoice total amount before the former credit note amount was applied
                            $contact_invoice_account['outstanding'] += $credit_note->total_amount;
                        endif;


                        # change outstanding amount to what it was before the credit note was initially applied to the invoice
                        if ($credit_log) :
                            $contact_invoice_account['outstanding'] = $credit_note->total_amount - $credit_log->credit;
                        endif;

                    endif;

                    # modify invoice status to paid if credit note amount is greater or equal to the outstanding invoice amount
                    $invoice_params = array('table' => $this->table_prefix . 'invoices', 'entity' => 'invoice');

                    if ($credit_note_amount > 0 && $credit_note_amount >= $contact_invoice_account['outstanding'])
                    {
                       // print_r('I\'m not supposed to be here   ');

                        # add to credit if credit note amount is greater than oustanding invoice amount
                        if ($credit_note_amount > $contact_invoice_account['outstanding'] && $credit_note_amount - $contact_invoice_account['outstanding'] != $credit_note_amount) {
                            if (!$update) {
                                $credit_amount = $contact_invoice_account['outstanding'];

                                $credit_post = array(
                                    'contact_id' => $invoice->contact_id,
                                    'invoice_id' => $invoice->id,
                                    'payment_id' => 0,
                                    'credit_note_id' => $credit_note->id,
                                    'credit' => $credit_amount
                                );

                                $this->credit_model->create(array(), $credit_post);
                            } else {
                                //$credit_amount = $credit_note_amount - $contact_invoice_account['outstanding'];
                                $credit_amount = num_difference($credit_note_amount, $contact_invoice_account['outstanding']);

                                $credit_update_post = array(
                                    'credit' => $credit_amount,
                                    'invoice_id' => $invoice->id,
                                    'credit_note_id' => $credit_note->id
                                );

                                if ($credit_log) :
                                    $this->credit_model->update(array(), $credit_log->id, $credit_update_post);
                                else :
                                    $credit_update_post['contact_id'] = $invoice->contact_id;
                                    $this->credit_model->create(array(), $credit_update_post);
                                endif;
                            }
                        }

                        # change invoice status to "paid"
                        if($post['status'] != 'draft') $invoice_post['status'] = 'paid';
                    }
                    elseif($credit_note_amount > 0 && $credit_note_amount < $contact_invoice_account['outstanding'])
                    {
                        # change invoice status to "partial"
                        if($post['status'] != 'draft') $invoice_post['status'] = 'partial';
                    }
                    else {
                        # change invoice status to what it used to be / keep invoice status as it is
                        if($post['status'] != 'draft') $invoice_post['status'] = $invoice->status;

                        # remove credit note record from credit log
                        if ($credit_log) :
                            $this->credit_model->delete($cm_params, $credit_note->id);
                        endif;
                    }

                    if(!empty($invoice_post)) $this->generic_model->update($invoice_params, $invoice->id, $invoice_post);
                }
            }
        }
    }
}