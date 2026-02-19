<?php

class Db_setup
{
    public $table_prefix;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->dbforge();
        $this->CI->load->dbutil();

        $this->table_prefix = $this->CI->config->item('db_table_prefix');
    }

    public function create_db($db_name)
    {
        $result = array('bool' => false);

        if (!$this->CI->dbutil->database_exists($db_name)) {

            $create_database = $this->CI->dbforge->create_database($db_name);

            if ($create_database) {
                $this->create_tables($db_name);
                $result['bool'] = true;
                $result['message'][] = 'new database successfully created';
            } else {
                $result['message'][] = 'error while creating database';
            }
        } else {
            $result['message'][] = 'database "' . $db_name . '" already exists';
        }

        return $result;
    }

    public function drop_db($db_name)
    {
        if ($this->CI->dbutil->database_exists($db_name)) {
            $this->CI->dbforge->drop_database($db_name);
            return true;
        } else {
            return false;
        }
    }

    public function create_tables($db = null)
    {
        $table_prefix = $this->table_prefix;

        if (!is_null($db)) {
            $this->CI->db->query('use ' . $db);
        }

        /* ACTIVITIES
         **************************************************************************************************/
        $activities = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'category' => array('type' => 'VARCHAR', 'constraint' => 20),
            'item_id' => array('type' => 'INT', 'constraint' => 11),
			'type' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'standard'),
            'short_message' => array('type' => 'VARCHAR', 'constraint' => 100),
            'label' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'link' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($activities);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'activities', TRUE);
        /**************************************************************************************************/

        /* COMPANY SIZES
         **************************************************************************************************/
        $company_sizes = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'size' => array('type' => 'VARCHAR', 'constraint' => 30)
        );

        $this->CI->dbforge->add_field($company_sizes);

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'company_sizes', TRUE);
        /**************************************************************************************************/

        /* CONTACT TYPES
         **************************************************************************************************/
        $contact_types = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'type' => array('type' => 'VARCHAR', 'constraint' => 30)
        );

        $this->CI->dbforge->add_field($contact_types);

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'contact_types', TRUE);
        /**************************************************************************************************/

        /* CONTACTS
         **************************************************************************************************/
        $contacts = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'contact_type_id' => array('type' => 'INT', 'constraint' => 11),
            'organisation' => array('type' => 'VARCHAR', 'constraint' => 50),
            'vat_number' => array('type' => 'VARCHAR', 'constraint' => 15, 'null' => TRUE),
            'industry_id' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'company_size_id' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'first_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'last_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'email' => array('type' => 'VARCHAR', 'constraint' => 150),
            'land_line' => array('type' => 'VARCHAR', 'constraint' => 25, 'null' => TRUE),
            'mobile' => array('type' => 'VARCHAR', 'constraint' => 25, 'null' => TRUE),
            'address' => array('type' => 'VARCHAR', 'constraint' => 500, 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($contacts);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'contacts', TRUE);
        /**************************************************************************************************/

        /* COUNTRIES
         **************************************************************************************************/
        $countries = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'country' => array('type' => 'VARCHAR', 'constraint' => 100),
            'active' => array('type' => 'TINYINT', 'constraint' => 1)
        );

        $this->CI->dbforge->add_field($countries);

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'countries', TRUE);
        /**************************************************************************************************/

        /* CREDIT LOG
         **************************************************************************************************/
        $credit_log = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'contact_id' => array('type' => 'INT', 'constraint' => 11),
            'payment_id' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
            'invoice_id' => array('type' => 'INT', 'constraint' => 11),
            'credit_note_id' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
            'credit' => array('type' => 'DOUBLE')
        );

        $this->CI->dbforge->add_field($credit_log);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'credit_log', TRUE);
        /**************************************************************************************************/

        /* CURRENCIES
         **************************************************************************************************/
        $currencies = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'currency_name' => array('type' => 'VARCHAR', 'constraint' => 50),
            'currency_symbol' => array('type' => 'VARCHAR', 'constraint' => 5),
            'short_code' => array('type' => 'VARCHAR', 'constraint' => 5)
        );

        $this->CI->dbforge->add_field($currencies);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'currencies', TRUE);
        /**************************************************************************************************/

        /* CREDIT NOTES
         **************************************************************************************************/
        $credit_notes = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'credit_note_number' => array('type' => 'VARCHAR', 'constraint' => 15),
            'contact_id' => array('type' => 'INT', 'constraint' => 11),
            'invoice_id' => array('type' => 'INT', 'constraint' => 11),
            'currency_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 1),
            /*'date' => array('type' => 'TIMESTAMP'),
            'due_date' => array('type' => 'TIMESTAMP'),*/
            'discount_percentage' => array('type' => 'DOUBLE', 'null' => TRUE),
            'reference' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
            'status' => array('type' => 'VARCHAR', 'constraint' => 15, 'default' => 'draft', 'null' => TRUE),
            'content_status' => array('type' => 'VARCHAR', 'constraint' => 15, 'default' => 'active'),
            'sub_total' => array('type' => 'DOUBLE', 'null' => TRUE),
            'discount_total' => array('type' => 'DOUBLE', 'null' => TRUE),
            'vat_amount' => array('type' => 'DOUBLE', 'null' => TRUE),
            'total_amount' => array('type' => 'DOUBLE', 'null' => TRUE),
            'terms' => array('type' => 'TEXT', 'null' => TRUE),
            'closing_note' => array('type' => 'TEXT', 'null' => TRUE),
            'reminder' => array('type' => 'INT', 'constraint' => 3, 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($credit_notes);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->CI->dbforge->add_field('date TIMESTAMP NULL');
        $this->CI->dbforge->add_field('due_date TIMESTAMP NULL');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'credit_notes', TRUE);
        /**************************************************************************************************/

        /* CREDIT NOTE ITEMS
         **************************************************************************************************/
        $credit_note_items = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'credit_note_id' => array('type' => 'INT', 'constraint' => 11),
            'item_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'description' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'quantity' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'tax' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'rate' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'total_amount' => array('type' => 'DOUBLE', 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($credit_note_items);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'credit_note_items', TRUE);
		
		/**************************************************************************************************/

        /* EXPENSES
         **************************************************************************************************/
        
		//$this->CI->dbforge->add_field("id int(11) NOT NULL AUTO_INCREMENT");
		$this->CI->dbforge->add_field("id int(11) AUTO_INCREMENT primary key NOT NULL");		
		$this->CI->dbforge->add_field("usr_id int(11) NOT NULL");
		$this->CI->dbforge->add_field("contact_id int(11) DEFAULT NULL");
		$this->CI->dbforge->add_field("date timestamp NULL DEFAULT NULL");
		$this->CI->dbforge->add_field("currency_id int(11) NOT NULL DEFAULT '1'");
		$this->CI->dbforge->add_field("notes varchar(255) DEFAULT NULL");
		$this->CI->dbforge->add_field("status varchar(15) DEFAULT 'draft'");
		$this->CI->dbforge->add_field("content_status varchar(15) NOT NULL DEFAULT 'active'");
		$this->CI->dbforge->add_field("supplier_id int(11) DEFAULT NULL");
		$this->CI->dbforge->add_field("tax_1 int(11) DEFAULT NULL");
		$this->CI->dbforge->add_field("tax_2 int(11) DEFAULT NULL");
		$this->CI->dbforge->add_field("category_id int(11) NOT NULL");
		$this->CI->dbforge->add_field("file_name varchar(255) DEFAULT NULL");
		$this->CI->dbforge->add_field("sub_total double DEFAULT NULL");
		$this->CI->dbforge->add_field("tax_amount double DEFAULT NULL");
		$this->CI->dbforge->add_field("total_amount double DEFAULT NULL");
		$this->CI->dbforge->add_field("date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
		$this->CI->dbforge->add_field("date_modified timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'");
        
       // $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'expenses', TRUE);
		
		
		/**************************************************************************************************/

        /* EXPENSES CATEGORIES
         **************************************************************************************************/
        
		$this->CI->dbforge->add_field("id int(11) AUTO_INCREMENT primary key NOT NULL");
		$this->CI->dbforge->add_field("category_name varchar(255) NOT NULL");
		$this->CI->dbforge->add_field("type varchar(50) NOT NULL DEFAULT 'default'");
		$this->CI->dbforge->add_field("date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
		$this->CI->dbforge->add_field("date_modified timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'");

        //$this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'expenses_categories', TRUE);
		
        /**************************************************************************************************/

        /* EMAIL SETTINGS
         **************************************************************************************************/
        $email_settings = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'email_signature' => array('type' => 'VARCHAR', 'constraint' => 150),
            'invoice_message' => array('type' => 'TEXT', 'null' => TRUE),
            'estimate_message' => array('type' => 'TEXT', 'null' => TRUE),
            'credit_note_message' => array('type' => 'TEXT', 'null' => TRUE),
            'payment_message' => array('type' => 'TEXT', 'null' => TRUE),
            'statement_message' => array('type' => 'TEXT', 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($email_settings);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'email_settings', TRUE);
        /**************************************************************************************************/

        /* EMAIL TOKENS
         **************************************************************************************************/
        $email_tokens = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'token' => array('type' => 'VARCHAR', 'constraint' => 50),
            'short_name' => array('type' => 'VARCHAR', 'constraint' => 50)
        );

        $this->CI->dbforge->add_field($email_tokens);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'email_tokens', TRUE);
        /**************************************************************************************************/

        /* ESTIMATES
         **************************************************************************************************/
        $estimates = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'estimate_number' => array('type' => 'VARCHAR', 'constraint' => 15),
            'contact_id' => array('type' => 'INT', 'constraint' => 11),
            'currency_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 1),
            /*'date' => array('type' => 'TIMESTAMP'),
            'due_date' => array('type' => 'TIMESTAMP'),*/
            'discount_percentage' => array('type' => 'DOUBLE', 'null' => TRUE),
            'reference' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
            'status' => array('type' => 'VARCHAR', 'constraint' => 15, 'default' => 'draft', 'null' => TRUE),
            'content_status' => array('type' => 'VARCHAR', 'constraint' => 15, 'default' => 'active'),
            'sub_total' => array('type' => 'DOUBLE', 'null' => TRUE),
            'discount_total' => array('type' => 'DOUBLE', 'null' => TRUE),
            'vat_amount' => array('type' => 'DOUBLE', 'null' => TRUE),
            'total_amount' => array('type' => 'DOUBLE', 'null' => TRUE),
            'terms' => array('type' => 'TEXT', 'null' => TRUE),
            'closing_note' => array('type' => 'TEXT', 'null' => TRUE),
            'reminder' => array('type' => 'INT', 'constraint' => 3, 'null' => TRUE),
        );

        $this->CI->dbforge->add_field($estimates);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->CI->dbforge->add_field('date TIMESTAMP NULL');
        $this->CI->dbforge->add_field('due_date TIMESTAMP NULL');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'estimates', TRUE);
        /**************************************************************************************************/

        /* ESTIMATE ITEMS
         **************************************************************************************************/
        $estimates_items = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'estimate_id' => array('type' => 'INT', 'constraint' => 11),
            'item_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'description' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'quantity' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'tax' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'rate' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'total_amount' => array('type' => 'DOUBLE', 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($estimates_items);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'estimate_items', TRUE);
        /**************************************************************************************************/

        /* INDUSTRIES
         **************************************************************************************************/
        $industries = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'industry_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($industries);

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'industries', TRUE);
        /**************************************************************************************************/

        /* INVOICES
         **************************************************************************************************/
        $invoices = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'invoice_number' => array('type' => 'VARCHAR', 'constraint' => 15),
            'contact_id' => array('type' => 'INT', 'constraint' => 11),
            'currency_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 1),
            /*'date' => array('type' => 'TIMESTAMP'),
            'due_date' => array('type' => 'TIMESTAMP'),*/
            'discount_percentage' => array('type' => 'DOUBLE', 'null' => TRUE),
            'reference' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
            'status' => array('type' => 'VARCHAR', 'constraint' => 15, 'default' => 'draft', 'null' => TRUE),
            'content_status' => array('type' => 'VARCHAR', 'constraint' => 15, 'default' => 'active'),
            'sub_total' => array('type' => 'DOUBLE', 'null' => TRUE),
            'discount_total' => array('type' => 'DOUBLE', 'null' => TRUE),
            'vat_amount' => array('type' => 'DOUBLE', 'null' => TRUE),
            'total_amount' => array('type' => 'DOUBLE', 'null' => TRUE),
            'terms' => array('type' => 'TEXT', 'null' => TRUE),
            'closing_note' => array('type' => 'TEXT', 'null' => TRUE),
            'reminder' => array('type' => 'INT', 'constraint' => 3, 'null' => TRUE),
        );

        $this->CI->dbforge->add_field($invoices);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->CI->dbforge->add_field('date TIMESTAMP NULL');
        $this->CI->dbforge->add_field('due_date TIMESTAMP NULL');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'invoices', TRUE);
        /**************************************************************************************************/

        /* INVOICE ITEMS
         **************************************************************************************************/
        $invoices_items = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'invoice_id' => array('type' => 'INT', 'constraint' => 11),
            'item_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'description' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'quantity' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'tax' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'rate' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'total_amount' => array('type' => 'DOUBLE', 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($invoices_items);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'invoice_items', TRUE);
        /**************************************************************************************************/

        /* INVOICE PAYMENT METHODS
         **************************************************************************************************/
        $invoice_payment_methods = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'payment_method' => array('type' => 'VARCHAR', 'constraint' => 30)
        );

        $this->CI->dbforge->add_field($invoice_payment_methods);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'invoice_payment_methods', TRUE);
        /**************************************************************************************************/

        /* INVOICE PAYMENTS
         **************************************************************************************************/
        $invoice_payments = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'invoice_id' => array('type' => 'INT', 'constraint' => 11),
            'contact_id' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'payment_amount' => array('type' => 'DOUBLE'),
            'payment_method_id' => array('type' => 'INT', 'constraint' => 11),
            'reference' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'credit_applied' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            //'payment_date' => array('type' => 'TIMESTAMP'),
            'notification' => array('type' => 'VARCHAR', 'constraint' => 10, 'null' => TRUE),
            'use_credit' => array('type' => 'VARCHAR', 'constraint' => 5, 'default' => 'no', 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($invoice_payments);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->CI->dbforge->add_field('payment_date TIMESTAMP NULL');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'invoice_payments', TRUE);
        /**************************************************************************************************/

        /* ITEMS
         **************************************************************************************************/
        $items = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'item_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'description' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'quantity' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'tax' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'rate' => array('type' => 'INT', 'constraint' => 11)
        );

        $this->CI->dbforge->add_field($items);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'items', TRUE);
        /**************************************************************************************************/

        /* LOGOS
         **************************************************************************************************/
        $logos = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'image_string' => array('type' => 'MEDIUMTEXT'),
            'logo_name' => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($logos);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'logos', TRUE);
        /**************************************************************************************************/

        /* ORGRANISATIONS
         **************************************************************************************************/
        $organisations = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'company_name' => array('type' => 'VARCHAR', 'constraint' => 50),
            'vat_number' => array('type' => 'VARCHAR', 'constraint' => 35, 'null' => TRUE),
            'industry_id' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
            'address_line_1' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'address_line_2' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'country_id' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE),
            'region_state' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'city' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'zip' => array('type' => 'VARCHAR', 'constraint' => 10, 'null' => TRUE),
            'email' => array('type' => 'VARCHAR', 'constraint' => 150),
            'mobile' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'telephone' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'fax' => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => TRUE),
            'currency_id' => array('type' => 'VARCHAR', 'constraint' => 11, 'null' => TRUE),
            'time_zone_id' => array('type' => 'VARCHAR', 'constraint' => 11, 'null' => TRUE),
            'account_id' => array('type' => 'INT', 'constraint' => 11),
            'account_url' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
            'account_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'day_light_savings' => array('type' => 'VARCHAR', 'constraint' => 5, 'default' => 'no', 'null' => TRUE),
            'postal_code' => array('type' => 'VARCHAR', 'constraint' => 10, 'null' => TRUE),
            'subdomain' => array('type' => 'VARCHAR', 'constraint' => 45, 'null' => TRUE),
            'trial_ends_at' => array('type' => 'DATETIME', 'null' => TRUE),
            'subscription_status' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'trial'),
            'paid_until' => array('type' => 'DATETIME', 'null' => TRUE),
            'manual_block_reason' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
            'is_manual_blocked' => array('type' => 'TINYINT', 'constraint' => 1, 'default' => 0),
            'grace_period_ends_at' => array('type' => 'DATETIME', 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($organisations);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'organisations', TRUE);
        /**************************************************************************************************/

        /* ROLE PERMISSIONS
         **************************************************************************************************/
        $role_permissions = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'role_id' => array('type' => 'INT', 'constraint' => 11),
            'permission_id' => array('type' => 'INT', 'constraint' => 11)
        );

        $this->CI->dbforge->add_field($role_permissions);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'role_permissions', TRUE);
        /**************************************************************************************************/

        /* TAXES
         **************************************************************************************************/
        $tax = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'tax_name' => array('type' => 'VARCHAR', 'constraint' => 30),
            'percentage' => array('type' => 'DOUBLE')
        );

        $this->CI->dbforge->add_field($tax);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'taxes', TRUE);
        /**************************************************************************************************/

        /* TEMPLATES
         **************************************************************************************************/

        $templates = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'invoice_name' => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => TRUE),
            'invoice_terms' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'invoice_closing_note' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'invoice_number_prefix' => array('type' => 'VARCHAR', 'constraint' => 10, 'null' => TRUE),
            'estimate_name' => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => TRUE),
            'estimate_terms' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'estimate_closing_note' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'estimate_number_prefix' => array('type' => 'VARCHAR', 'constraint' => 10, 'null' => TRUE),
            'credit_note_name' => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => TRUE),
            'credit_note_terms' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'credit_note_closing_note' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE),
            'credit_note_number_prefix' => array('type' => 'VARCHAR', 'constraint' => 10, 'null' => TRUE),
        );

        $this->CI->dbforge->add_field($templates);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'templates', TRUE);
        /**************************************************************************************************/

        /* THEMES
         **************************************************************************************************/
        $themes = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'theme_name' => array('type' => 'VARCHAR', 'constraint' => 100),
            'theme_image' => array('type' => 'VARCHAR', 'constraint' => 100)
        );

        $this->CI->dbforge->add_field($themes);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'themes', TRUE);
        /**************************************************************************************************/

        /* THEME SETTINGS
         **************************************************************************************************/
        $theme_settings = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'theme_id' => array('type' => 'INT', 'constraint' => 11),
            'image_string' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($theme_settings);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'theme_settings', TRUE);
        /**************************************************************************************************/

        /* TIME ZONES
         **************************************************************************************************/
        $time_zones = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'timezone' => array('type' => 'VARCHAR', 'constraint' => 100),
            'time' => array('type' => 'FLOAT', 'default' => 0),
            'daylight_saving' => array('type' => 'INT', 'constraint' => 2, 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($time_zones);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        //$this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'timezones', TRUE);
        /**************************************************************************************************/

        /* USER PERMISSIONS
         **************************************************************************************************/
        $user_permissions = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'permission' => array('type' => 'VARCHAR', 'constraint' => 50),
            'short_name' => array('type' => 'VARCHAR', 'constraint' => 50),
            'type' => array('type' => 'VARCHAR', 'constraint' => 20, 'default' => 'default'),
            'description' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE)
        );

        $this->CI->dbforge->add_field($user_permissions);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'user_permissions', TRUE);
        /**************************************************************************************************/

        /* USER ROLES
         **************************************************************************************************/
        $user_roles = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'role_name' => array('type' => 'VARCHAR', 'constraint' => 50),
            'short_name' => array('type' => 'VARCHAR', 'constraint' => 50)
        );

        $this->CI->dbforge->add_field($user_roles);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'user_roles', TRUE);
        /**************************************************************************************************/

        /* USERS
         **************************************************************************************************/
        $users = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'first_name' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE),
            'last_name' => array('type' => 'VARCHAR', 'constraint' => 20, 'null' => TRUE),
            'email' => array('type' => 'VARCHAR', 'constraint' => 30),
            'contact_number' => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => TRUE),
            'user_role_id' => array('type' => 'INT', 'constraint' => 11),
            'owner' => array('type' => 'INT', 'constraint' => 2, 'default' => 0, 'null' => TRUE),
            'password' => array('type' => 'VARCHAR', 'constraint' => 33),
            'failed_attempts' => array('type' => 'INT', 'constraint' => 2, 'null' => TRUE, 'default' => 0),
            'is_active' => array('type' => 'TINYINT', 'constraint' => 1, 'default' => 1)
        );

        $this->CI->dbforge->add_field($users);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->CI->dbforge->add_field('last_attempt_datetime TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->CI->dbforge->add_field('token_expire DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->CI->dbforge->add_field('last_activity DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'users', TRUE);
        /**************************************************************************************************/

        /* USER TOKENS
         **************************************************************************************************/
        $user_tokens = array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true),
            'user_id' => array('type' => 'INT', 'constraint' => 11),
            'session_id' => array('type' => 'VARCHAR', 'constraint' => 150),
            'token' => array('type' => 'VARCHAR', 'constraint' => 255)
        );

        $this->CI->dbforge->add_field($user_tokens);
        $this->CI->dbforge->add_field('date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->CI->dbforge->add_field('date_modified TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->CI->dbforge->add_field('token_expire TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table($table_prefix . 'user_tokens', TRUE);
        /**************************************************************************************************/
    }
}