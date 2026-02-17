<?php

class Reminders
{
    public $table_prefix;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
    }

    public function get_databases()
    {
        $this->CI->load->dbutil();
        $dbs = $this->CI->dbutil->list_databases();

        $databases = array();

        foreach ($dbs as $db) :
            if (strpos($db, 'acc') !== false) $databases[] = $db;
        endforeach;

        return $databases;
    }

    public function reminders_to_send()
    {
        $databases = $this->get_databases();
        $reminders_data = array();
        if(!empty($databases))
        {
            foreach($databases as $database) :
                $this->CI->db->query('use ' . $database);
                $reminders_data[$database] = $this->contact_data();
            endforeach;
        }

        return $reminders_data;
    }

    public function contact_data()
    {
        $invoices_params = array(
            'table' => $this->table_prefix . 'invoices inv',
            'entity' => 'invoice',
            'fields' => array(
                'inv.date_created',
                'inv.contact_id',
                'inv.id "invoice_id"',
                'inv.invoice_number',
                'inv.date "invoice_date"',
                'inv.due_date "invoice_due_date"',
                /*'inv.status',
                'inv.total_amount',
                'inv.currency_id',
                'cur.currency_name',
                'cur.currency_symbol',
                'cur.short_code',
                'IFNULL(SUM(pay.payment_amount),0) "payment_amount"',
                'IFNULL(SUM(cn.total_amount),0) "credit_note_amount"',
                'IFNULL(SUM(cl.credit), 0) "credit"',*/
                //'(IFNULL(SUM(pay.payment_amount),0) + IFNULL(SUM(cn.total_amount),0) + IFNULL(SUM(cl.credit), 0)) - IFNULL(SUM(inv.total_amount),0) "account_standing"'
            ),
            'where' => array(
                'inv.reminder !=' => 0,
                'inv.status !=' => 'draft',
                'inv.status !=' => 'paid',
                'inv.content_status' => 'active',
                'MOD(DATEDIFF(CURDATE(), DATE(inv.due_date)), inv.reminder) =' => 0
            ),
            'group_by' => 'inv.id'
        );

        $invoices_params['join'][0]['table1'] = $this->table_prefix.'currencies cur';
        $invoices_params['join'][0]['table2'] = 'cur.id = inv.currency_id';

        $invoices_params['join'][1]['table1'] = $this->table_prefix.'invoice_payments pay';
        $invoices_params['join'][1]['table2'] = 'pay.invoice_id = inv.id';
        $invoices_params['join'][1]['type'] = 'left';

        $invoices_params['join'][2]['table1'] = $this->table_prefix.'credit_notes cn';
        $invoices_params['join'][2]['table2'] = 'cn.invoice_id = inv.id';
        $invoices_params['join'][2]['type'] = 'left';

        $invoices_params['join'][3]['table1'] = $this->table_prefix.'credit_log cl';
        $invoices_params['join'][3]['table2'] = 'cl.invoice_id = inv.id';
        $invoices_params['join'][3]['type'] = 'left';

        $invoices = $this->CI->generic_model->read($invoices_params, null, 'zero');

        $contact_params = array(
            'table' => $this->table_prefix . 'contacts c',
            'entity' => 'contact',
            'fields' => array(
                'c.id',
                'c.first_name',
                'c.last_name',
                'c.email',
                'c.organisation'
            )
        );

        $org_params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'entity',
            'fields' => 'account_name'
        );

        $email_settings_params = array(
            'table' => $this->table_prefix . 'email_settings',
            'entity' => 'email setting',
            'fields' => 'email_signature'
        );

        $contacts = array();

        if(!empty($invoices))
        {
            foreach($invoices as $invoice)
            {
                # Makes sure that it only gets one iteration of each contact
                if(!array_key_exists($invoice->contact_id, $contacts))
                {
                    # Actual contact data
                    $contacts[$invoice->contact_id] = $this->CI->generic_model->read($contact_params, $invoice->contact_id, 'single');

                    # Add account name to contact data
                    $contacts[$invoice->contact_id]->account_name = $this->CI->generic_model->read($org_params, 1, 'single')->account_name;

                    # Add emial signature
                    $contacts[$invoice->contact_id]->email_signature = $this->CI->generic_model->read($email_settings_params, 1, 'single')->email_signature;
                }

                $contacts[$invoice->contact_id]->invoice_data[] = $invoice;
            }
        }

        return $contacts;
    }

    public function send()
    {
        $return = array();
        $reminders_to_send = $this->reminders_to_send();

        if(!empty($reminders_to_send))
        {
            foreach($reminders_to_send as $db => $reminder_data_per_db)
            {
                foreach($reminder_data_per_db as $reminder_data)
                {
                    # Set parameters for the sake of creating an encrypted link for the documents resource
                    $params = array();
                    $params['account_name'] = $reminder_data->account_name;
					$params['table'] = $this->table_prefix.'invoices';
                    $params['entity'] = 'invoice';

                    $message = '';
                    foreach($reminder_data->invoice_data as $invoice)
                    {
                        # Add invoice ID to params as entity_id
                        $params['entity_id'] = $invoice->invoice_id;

                        # Load messaging library to make use of it's encrypt() function
                        $this->CI->load->library('messaging');

                        # Link to view document online
                        $link = get_protocol().$reminder_data->account_name.'.'.$this->CI->config->item('document_url').$this->CI->messaging->encrypt($params);

                        # Finalise message
                        $message .= '<p>Dear Client</p>';
                        $message .= '<p>This is a reminder that your invoice #'.$invoice->invoice_number .' is overdue. Please make payment soonest.</p>' ;

                        $message .= '<p><a href="'.$link.'">View invoice online</a></p>';
                        $message .= '<p>'.$reminder_data->email_signature.'</p>';

                        $return[$db][$reminder_data->email]['date_of_doc_creation'] = $invoice->date_created;
                        $return[$db][$reminder_data->email]['due_date'] = $invoice->invoice_due_date;
                        $return[$db][$reminder_data->email]['invoice_id'] = $invoice->invoice_id;
                        $return[$db][$reminder_data->email]['invoice_number'] = $invoice->invoice_number;
                    }

                    # Send the remider email
                    $return[$db][$reminder_data->email]['send_result'] = $this->send_email($reminder_data->email, $message);
                }
            }
        }

        return $return;
    }

    public function send_email($email, $message)
    {
        $return = array('status' => 'ERROR');

        # Basic mailer parameters
        $subject = 'BOOST ACCOUNTING - PAYMENT REMINDER';

        $data = array(
            'subject' => $subject,
            'heading' => $subject,
            'message' => $message
        );

        $message_to_send = $this->CI->load->view('templates/mailer', $data, true);

        # prepping the email for sending
        $this->CI->load->library('email');
        $this->CI->email->from($this->CI->config->item('from_email'), 'Boost Accounting');
        $this->CI->email->to($email);
        $this->CI->email->subject($subject);

        $this->CI->email->message($message_to_send);

        if ($this->CI->email->send()) {
            $return['status'] = 'OK';
            $return['message'][] = 'Email successfully sent';
        } else {
            $return['message'][] = 'Email sending failed: ' . $this->CI->email->print_debugger();
        }

        return $return;
    }
}