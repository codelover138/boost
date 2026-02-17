<?php

class Db_update
{
    public $table_prefix;

    public function __construct()
    {
        $this->CI =& get_instance();

        //$this->CI->load->dbforge();
        //$this->CI->load->dbutil();
        $this->CI->load->library('db/db_setup');

        $this->table_prefix = $this->CI->config->item('db_table_prefix');
    }

    public function setup_db($db_name)
    {
        $create = $this->CI->db_setup->create_db($db_name);
        if ($create['bool']) :
            $this->update_tables($db_name);
        endif;

        return $create;
    }

    public function update_tables($db_name)
    {
        $this->CI->db->query('use ' . $db_name);

        $this->user_roles();
        $this->email_settings();
        $this->email_tokens($db_name);
        $this->user_permissions($db_name);
        $this->role_permissions();
        $this->taxes();
        $this->themes();
        $this->theme_settings();
        $this->company_sizes();
        $this->industries();
		$this->expense_categories();
        $this->invoice_payment_methods();
        $this->contact_types();
        $this->templates();
        $this->currencies($db_name);
        $this->countries($db_name);
        $this->timezones($db_name);
    }

    public function currencies($new_db)
    {
        $main_db = $this->CI->db->database;
        $this->CI->db->query('use ' . $main_db);

        $params = array(
            'table' => $this->table_prefix . 'currencies',
            'entity' => 'currency'
        );

        $currency_list = $this->CI->generic_model->read($params);

        # switch over to account db
        $this->CI->db->query('use ' . $new_db);

        # create a final array of currencies
        $final_currency_list = array();
        foreach ($currency_list as $currency) {
            $final_currency_list[] = (array)$currency;
        }

        # save currencies array to account db
        $create = $this->CI->generic_model->create($params, $final_currency_list);

        return $create;
    }

    public function email_tokens($new_db)
    {
        $main_db = $this->CI->db->database;
        $this->CI->db->query('use ' . $main_db);

        $params = array(
            'table' => $this->table_prefix . 'email_tokens',
            'entity' => 'token'
        );

        $tokens = $this->CI->generic_model->read($params);

        # switch over to account db
        $this->CI->db->query('use ' . $new_db);

        # create a final array of currencies
        $final_token_list = array();
        foreach ($tokens as $token) {
            $final_token_list[] = (array)$token;
        }

        # save currencies array to account db
        $create = $this->CI->generic_model->create($params, $final_token_list);

        return $create;
    }

    public function company_sizes()
    {
        $params = array(
            'table' => $this->table_prefix . 'company_sizes',
            'entity' => 'size'
        );

        $sizes = array();
        $sizes[0]['size'] = '1-10';
        $sizes[1]['size'] = '11-50';
        $sizes[2]['size'] = '51-100';
		$sizes[3]['size'] = '101-500';
		$sizes[4]['size'] = '501+';

        $create = $this->CI->generic_model->create($params, $sizes);

        return $create;
    }

    public function email_settings()
    {
        $params = array(
            'table' => $this->table_prefix . 'email_settings',
            'entity' => 'email settings'
        );

        $insert = array(
			"email_signature"		=> "Thank you for your business.",
			"invoice_message"		=> "To view your invoice from {{company_name}} for {{amount}}, or to download a PDF copy for your records, click the link below:",
			"payment_message"		=> "Thank you for your payment {{client_first_name}}. \r\n \r\n A payment of {{amount}} for invoice {{invoice_number}} has been marked as paid. \r\n \r\n To view the paid invoice or download a PDF copy for your records, click the link below:",
			"estimate_message"		=> "To view your estimate from {{company_name}} for {{amount}}, or to download a PDF copy for your records, click the link below:",
			"credit_note_message"	=> "To view your credit note from {{company_name}} for {{amount}}, or to download a PDF copy for your records, click the link below:",
			"statement_message"		=> "To view your statement from {{company_name}} or download a PDF copy for your records, click the link below:"
		);

        $create = $this->CI->generic_model->create($params, $insert);

        return $create;
    }

    public function taxes()
    {
        $params = array(
            'table' => $this->table_prefix . 'taxes',
            'entity' => 'tax'
        );

        $taxes = array(
            'tax_name' => 'VAT (South Africa)',
            'percentage' => 14
        );

        $create = $this->CI->generic_model->create($params, $taxes);

        return $create;
    }

    public function industries()
    {
        $params = array(
            'table' => $this->table_prefix . 'industries',
            'entity' => 'industry'
        );

        $industries = array();
        $industries[]['industry_name'] = 'Accommodation';
        $industries[]['industry_name'] = 'Accounting';
        $industries[]['industry_name'] = 'Advertising';
        $industries[]['industry_name'] = 'Aerospace';
        $industries[]['industry_name'] = 'Agriculture';
        $industries[]['industry_name'] = 'Air Transportation';
        $industries[]['industry_name'] = 'Apparel & Accessories';
        $industries[]['industry_name'] = 'Banking';
        $industries[]['industry_name'] = 'Beauty & Cosmetics';
        $industries[]['industry_name'] = 'Chemical';
        $industries[]['industry_name'] = 'Communications';
        $industries[]['industry_name'] = 'Construction';
        $industries[]['industry_name'] = 'Consulting';
        $industries[]['industry_name'] = 'Education';
        $industries[]['industry_name'] = 'Electronics';
        $industries[]['industry_name'] = 'Employment';
        $industries[]['industry_name'] = 'Energy';
        $industries[]['industry_name'] = 'Entertainment & Recreation';
        $industries[]['industry_name'] = 'Fashion';
        $industries[]['industry_name'] = 'Financial Services';
        $industries[]['industry_name'] = 'Food & Beverage';
        $industries[]['industry_name'] = 'Health';
        $industries[]['industry_name'] = 'Information Technology';
        $industries[]['industry_name'] = 'Journalism & News';
        $industries[]['industry_name'] = 'Legal Services';
        $industries[]['industry_name'] = 'Marketing';
        $industries[]['industry_name'] = 'Manufacturing';
        $industries[]['industry_name'] = 'Media & Broadcasting';
        $industries[]['industry_name'] = 'Medical Devices & Supplies';
        $industries[]['industry_name'] = 'Motion Pictures & Video';
        $industries[]['industry_name'] = 'Music ';
        $industries[]['industry_name'] = 'Pharmaceutical';
        $industries[]['industry_name'] = 'Public Administration';
        $industries[]['industry_name'] = 'Publishing';
        $industries[]['industry_name'] = 'Real Estate';
        $industries[]['industry_name'] = 'Retail';
        $industries[]['industry_name'] = 'Sports';
        $industries[]['industry_name'] = 'Technology';
        $industries[]['industry_name'] = 'Telecommunications';
        $industries[]['industry_name'] = 'Transportation';
        $industries[]['industry_name'] = 'Travel';
        $industries[]['industry_name'] = 'Video Game';
        $industries[]['industry_name'] = 'Web Services';

        $create = $this->CI->generic_model->create($params, $industries);

        return $create;
    }
	
	public function expense_categories()
    {
        $params = array(
            'table' => $this->table_prefix . 'expenses_categories',
            'entity' => 'expenses_category'
        );

        $expenses_categories = array();
        $expenses_categories[]['category_name'] = 'Accommodation';
		$expenses_categories[]['category_name'] = 'Advertising and Promotion';
		$expenses_categories[]['category_name'] = 'Auto Expenses';
		$expenses_categories[]['category_name'] = 'Bank/Finance Charges';
		$expenses_categories[]['category_name'] = 'Bank charges on company accounts are claimable.';
		$expenses_categories[]['category_name'] = 'Books and Journals';
		$expenses_categories[]['category_name'] = 'Trade magazines, periodicals and books relevant to the business are allowable.';
		$expenses_categories[]['category_name'] = 'Entertainment';
		$expenses_categories[]['category_name'] = 'Cell Phone';
		$expenses_categories[]['category_name'] = 'Charitable Donations';
		$expenses_categories[]['category_name'] = 'Computer Hardware';
		$expenses_categories[]['category_name'] = 'Computer Software';
		$expenses_categories[]['category_name'] = 'Employee Benefits';
		$expenses_categories[]['category_name'] = 'Insurance';
		$expenses_categories[]['category_name'] = 'Interest Payable';
		$expenses_categories[]['category_name'] = 'Internet & Telephone';
		$expenses_categories[]['category_name'] = 'Leasing Payments';
		$expenses_categories[]['category_name'] = 'Legal and Professional Feee';
		$expenses_categories[]['category_name'] = 'Licenses and Permits';
		$expenses_categories[]['category_name'] = 'Meals';
		$expenses_categories[]['category_name'] = 'Miscellaneous';
		$expenses_categories[]['category_name'] = 'Office Costs';
		$expenses_categories[]['category_name'] = 'Office Equipment';
		$expenses_categories[]['category_name'] = 'Other Computer Costs';
		$expenses_categories[]['category_name'] = 'Pension/Retirement Plan';
		$expenses_categories[]['category_name'] = 'Postage';
		$expenses_categories[]['category_name'] = 'Printing';
		$expenses_categories[]['category_name'] = 'Staff Training';
		$expenses_categories[]['category_name'] = 'Stationery';
		$expenses_categories[]['category_name'] = 'Subscriptions';
		$expenses_categories[]['category_name'] = 'Travel';
		$expenses_categories[]['category_name'] = 'Use Of Home';
		$expenses_categories[]['category_name'] = 'Web Hosting';        

        $create = $this->CI->generic_model->create($params, $expenses_categories);

        return $create;
    }

    public function invoice_payment_methods()
    {
        $table = $this->table_prefix . 'invoice_payment_methods';

        $params = array('table' => $table, 'entity' => 'method');

        $methods = array();
        $methods[0]['payment_method'] = 'Bank Transfer';
        $methods[1]['payment_method'] = 'Cash';
        $methods[2]['payment_method'] = 'Cheque';
        $methods[3]['payment_method'] = 'Credit';

        $create = $this->CI->generic_model->create($params, $methods);

        return $create;
    }

    public function contact_types()
    {
        $params = array(
            'table' => $this->table_prefix . 'contact_types',
            'entity' => 'type'
        );

        $types = array();
        $types[0]['type'] = 'client';
        $types[1]['type'] = 'supplier';

        $create = $this->CI->generic_model->create($params, $types);

        return $create;
    }

    public function templates()
    {
        $table = $this->table_prefix . 'templates';

        $params = array('table' => $table, 'entity' => 'templates');

        $templates = array(
            'invoice_name' => 'INVOICE',
            'estimate_name' => 'ESTIMATE',
            'credit_note_name' => 'CREDIT NOTE'
        );

        $create = $this->CI->generic_model->create($params, $templates);

        return $create;
    }

    public function themes()
    {
        $table = $this->table_prefix . 'themes';

        $params = array('table' => $table, 'entity' => 'theme');

        $themes = array(
            'theme_name' => 'Default',
            'theme_image' => 'https://api.boostaccounting.com/assets/themes/default_template_prieview.png'
        );

        $create = $this->CI->generic_model->create($params, $themes);

        return $create;
    }

    public function theme_settings()
    {
        $table = $this->table_prefix . 'theme_settings';

        $params = array('table' => $table, 'entity' => 'theme');

        $theme_settings = array(
            'theme_id' => 1
        );

        $create = $this->CI->generic_model->create($params, $theme_settings);

        return $create;
    }

    public function user_roles()
    {
        $table = $this->table_prefix . 'user_roles';

        $params = array('table' => $table);

        $roles = array();

        $roles[0]['role_name'] = 'Admin';
        $roles[0]['short_name'] = 'admin';

        $roles[1]['role_name'] = 'Staff';
        $roles[1]['short_name'] = 'staff';

        foreach ($roles as $key => $role) {
            $params['field'] = 'role_name';
            $params['value'] = $role['role_name'];

            $value_exists = $this->value_exists($params);

            $params['value'] = $role['short_name'];

            $value_exists2 = $this->value_exists($params);

            if ($value_exists || $value_exists2) :
                unset($roles[$key]);
            endif;
        }

        if (!empty($roles)) {
            return $this->CI->db->insert_batch($table, $roles);
        } else {
            return false;
        }
    }

    public function user_permissions($new_db)
    {
        $main_db = $this->CI->db->database;
        $this->CI->db->query('use ' . $main_db);

        $params = array(
            'table' => $this->table_prefix . 'user_permissions',
            'entity' => 'user permissions'
        );

        $permissions_list = $this->CI->generic_model->read($params);

        # switch over to account db
        $this->CI->db->query('use ' . $new_db);

        # create a final array of permissions
        $final_permissions_list = array();
        foreach ($permissions_list as $permission) {
            $final_permissions_list[] = (array)$permission;
        }

        # save permissions array to account db
        $create = $this->CI->generic_model->create($params, $final_permissions_list);

        return $create;
    }

    public function role_permissions()
    {
        $params = array(
            'table' => $this->table_prefix . 'role_permissions',
            'entity' => 'role permissions'
        );

        $role_permissions = array();
        $role_permissions[0]['role_id'] = 1;
        $role_permissions[0]['permission_id'] = 1;
        $role_permissions[1]['role_id'] = 1;
        $role_permissions[1]['permission_id'] = 2;
        $role_permissions[2]['role_id'] = 1;
        $role_permissions[2]['permission_id'] = 3;
        $role_permissions[3]['role_id'] = 1;
        $role_permissions[3]['permission_id'] = 4;
        $role_permissions[4]['role_id'] = 1;
        $role_permissions[4]['permission_id'] = 5;
        $role_permissions[5]['role_id'] = 1;
        $role_permissions[5]['permission_id'] = 6;
        $role_permissions[6]['role_id'] = 1;
        $role_permissions[6]['permission_id'] = 7;
        $role_permissions[7]['role_id'] = 1;
        $role_permissions[7]['permission_id'] = 8;
        $role_permissions[8]['role_id'] = 1;
        $role_permissions[8]['permission_id'] = 9;
        $role_permissions[9]['role_id'] = 1;
        $role_permissions[9]['permission_id'] = 10;
        $role_permissions[10]['role_id'] = 1;
        $role_permissions[10]['permission_id'] = 11;
        $role_permissions[11]['role_id'] = 1;
        $role_permissions[11]['permission_id'] = 12;
        $role_permissions[12]['role_id'] = 1;
        $role_permissions[12]['permission_id'] = 13;
        $role_permissions[13]['role_id'] = 1;
        $role_permissions[13]['permission_id'] = 14;
		$role_permissions[14]['role_id'] = 1;
        $role_permissions[14]['permission_id'] = 15;
		$role_permissions[15]['role_id'] = 1;
        $role_permissions[15]['permission_id'] = 16;

        $create = $this->CI->generic_model->create($params, $role_permissions);

        return $create;
    }

    public function countries($new_db)
    {
        $main_db = $this->CI->db->database;
        $this->CI->db->query('use ' . $main_db);

        $params = array(
            'table' => $this->table_prefix . 'countries',
            'entity' => 'country'
        );

        $country_list = $this->CI->generic_model->read($params);

        $this->CI->db->query('use ' . $new_db);

        $final_country_list = array();
        foreach ($country_list as $country) {
            $final_country_list[] = (array)$country;
        }

        $create = $this->CI->generic_model->create($params, $final_country_list);

        return $create;
    }

    public function timezones($new_db)
    {
        $main_db = $this->CI->db->database;
        $this->CI->db->query('use ' . $main_db);

        $params = array(
            'table' => $this->table_prefix . 'timezones',
            'entity' => 'timezone'
        );

        $time_zone_list = $this->CI->generic_model->read($params);

        $this->CI->db->query('use ' . $new_db);

        $final_time_zone_list = array();
        foreach ($time_zone_list as $time_zone) {
            $final_time_zone_list[] = (array)$time_zone;
        }

        $create = $this->CI->generic_model->create($params, $final_time_zone_list);

        return $create;
    }

    public function value_exists($params)
    {
        $table = $params['table'];
        $field = $params['field'];
        $value = $params['value'];

        if (!isset($params['where'])) {
            $this->CI->db->where($field, $value);
        } else {
            $this->CI->db->where($params['where']);
        }

        $query = $this->CI->db->get($table);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}