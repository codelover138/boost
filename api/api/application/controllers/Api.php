<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->regular->set_response_headers();

        set_time_limit(0);
		ini_set('memory_limit', '256M');

        $requested_resource = $this->regular->requested_resource();

        $dont_validate_account = array('registrations', 'login', 'export', 'download', 'checks', 'documents', 'statuses','statement_url');
        $dont_validate_token = array('login', 'registrations', 'export', 'download', 'checks', 'documents', 'statuses', 'passwords','statement_url');

        if (!in_array($requested_resource, $dont_validate_account)) :
            $this->userhandler->confirm_account();
        endif;

        if (!in_array($requested_resource, $dont_validate_token)) :
            $this->userhandler->valid_token();
			//$this->userhandler->valid_permission();
        endif;
		
		
		
		
    }

    public function index($string = null)
    {
        var_dump('This is the api controller');

        if (!is_null($string)) {
            $this->load->library('messaging');

            $string .= '==';
            $params = $this->messaging->decrypt($string);

            var_dump($string);
            var_dump($params);
        }

        $x = $this->userhandler->determine_user();

        var_dump($x);
    }

    public function statuses($entity, $encrypted_link)
    {
        $this->load->library('messaging');
        $params = $this->messaging->decrypt($encrypted_link);

        $id = $params['id'];
        $status = $params['status'];
        $account_name = $params['account_name'];

        $this->load->library('resources/statuses');
        $this->statuses->entry($entity, $id, $status, $account_name);
    }

    public function statuses1($entity, $id, $status)
    {
        $this->load->library('resources/statuses');
        $this->statuses->entry($entity, $id, $status);
    }

    public function login()
    {
        
        $this->load->library('resources/generic');
        $this->generic->entry('login');
    }

    public function passwords($email = null)
    {
        $this->load->library('resources/passwords');
        $this->passwords->entry($email);
    }

    public function taxes($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table'=>'boost_taxes',
            'entity'=>'tax'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function contacts($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table'=>'boost_contacts',
            'entity'=>'contact',
            'model'=>'contacts_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function contact_types($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_contact_types',
            'entity' => 'contact type',
            'model' => 'generic_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function company_sizes($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_company_sizes',
            'entity' => 'company size',
            'model' => 'generic_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function industries($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_industries',
            'entity' => 'industry',
            'model' => 'generic_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function items($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table'=>'boost_items',
            'entity'=>'item'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function countries($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table'=>'boost_countries',
            'entity'=>'country'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function currencies($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table'=>'boost_currencies',
            'entity'=>'currency'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function invoices($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table'=>'boost_invoices',
            'entity'=>'invoice',
            'model'=>'template_model',
            'items_table'=>'boost_invoice_items'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function estimates($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table'=>'boost_estimates',
            'entity' => 'estimate',
            'model'=>'template_model',
            'items_table'=>'boost_estimate_items'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function credit_notes($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table'=>'boost_credit_notes',
            'entity' => 'credit_note',
            'model'=>'credit_notes_model',
            'items_table'=>'boost_credit_note_items',
            'join_invoice_table'=>true
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function users($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_users',
            'entity' => 'user',
            'model' => 'users_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function organisations($field = null, $value = null)
    {
        $params = array(
            'table'=>'boost_organisations',
            'entity' => 'organisation',
            'model' => 'organisations_model'
        );

        $id = null;
        if ($this->regular->request_method() == 'PUT' || $this->regular->request_method() == 'DELETE') :
            $id = 1;
        endif;
        $order = 'ASC';
        $limit = null;
        $offset = null;

        if(!is_null($field)) :
            $order = $field;
        endif;

        if(!is_null($value)) :
            $limit = $value;
        endif;

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function organizations($field = null, $value = null)
    {
        $this->organisations($field, $value);
    }

    public function timezones($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_timezones',
            'entity' => 'timezone'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function themes($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_themes',
            'entity' => 'theme'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function theme_settings($field = null, $value = null)
    {
        $params = array(
            'table' => 'boost_theme_settings',
            'entity' => 'theme settings',
            'model' => 'Theme_settings_model'
        );

        $id = null;
        if ($this->regular->request_method() == 'PUT' || $this->regular->request_method() == 'DELETE') :
            $id = 1;
        endif;
        $order = 'ASC';
        $limit = null;
        $offset = null;

        if (!is_null($field)) :
            $order = $field;
        endif;

        if (!is_null($value)) :
            $limit = $value;
        endif;

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function payments($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        /*$post = $this->regular->decode();

        $db_table_prefix = $this->config->item('db_table_prefix');

        $invoice_params = array(
            'table'=>$db_table_prefix.'invoices',
            'fields'=>'bi.id, contact_id',
            'entity'=>'invoice',
            'items_table'=>$db_table_prefix.'invoice_items'
        );

        $this->load->model('template_model');
        $invoice_data = $this->template_model->read($invoice_params, $post['invoice_id'])[0];

        $this->load->library('finance');
        $finance = $this->finance->contact_finances($invoice_data->contact_id);

        $post['paid'] = $finance['paid'];
        $post['outstanding'] = $finance['outstanding'];
        $post['contact_id'] = $invoice_data->contact_id;*/

        $params = array(
            'table'=>'boost_invoice_payments',
            'entity'=>'payment',
            'model'=>'payments_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function payment_methods($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_invoice_payment_methods',
            'entity' => 'payment method'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function search($string = '', $table = null, $field = null)
    {
        $params = array(
            'table'=>$table,
            'entity'=>'search'
        );

        $this->load->library('resources/search', $params);
        $this->search->entry($string, $field);
    }

    public function templates($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_templates',
            'entity' => 'template'
        );

        if ($this->regular->request_method() == 'PUT' || $this->regular->request_method() == 'DELETE') :
            $id = 1;
        endif;

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function email_settings($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_email_settings',
            'entity' => 'email settings'
        );

        if ($this->regular->request_method() == 'PUT' || $this->regular->request_method() == 'DELETE') :
            $id = 1;
        endif;

        /*$id = null;
        $order = 'ASC';
        $limit = null;
        $offset = null;*/

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function role_permissions($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_role_permissions',
            'entity' => 'role permissions',
            'model' => 'role_permissions_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function roles($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_user_roles',
            'entity' => 'user role'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function permissions($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_user_permissions',
            'entity' => 'permission',
            'model' => 'permissions_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }

    public function activities($category = NULL, $id = NULL)
    {
        $params = array(
            'table' => 'boost_activities',
            'entity' => 'activity'
        );

        $this->load->library('resources/activities', $params);
        $this->activities->entry($category, $id);
    }

    public function messages($resource = null, $id = null)
    {
        $this->load->library('resources/generic');
        $this->generic->messages($resource, $id);
    }

    public function bulk($resource = null, $field = null, $value = null)
    {
        $this->load->library('resources/generic');
        $this->generic->_bulk($resource, $field, $value);
    }

    public function pdf_test($resource, $ids, $output = null)
    {
        $this->load->library('pdf/' . $resource);

        $this->invoices->generate_pdf($ids, $output);
    }

    public function pdf($resource, $id = null)
    {
        $this->load->library('pdf/'.$resource);

        $post_vars = $this->regular->decode();

        if(is_null($id))
        {
            if(isset($post_vars['data']) && !empty($post_vars['data'])) :
                $id = $post_vars['data'];
            endif;
        }

        $this->$resource->generate_pdf($id);
    }

    public function export($resource, $id = null)
    {
        $this->load->library('resources/generic');
        $this->generic->entry('export', $resource, $id);
    }

    public function download($file)
    {
        $this->load->library('download');
        $this->download->get_file($file);
    }

    public function statements($contact_id)
    {
        $this->load->library('resources/statements');
        $this->statements->entry($contact_id);
    }
	
	public function statement_url($contact_id)
    {
        $this->load->library('resources/statement_url');
        $this->statement_url->entry($contact_id);
    }

    public function registrations($encrypted_email = null)
    {
        $this->load->library('resources/registrations');
        $this->registrations->entry($encrypted_email);
    }

    public function checks($what, $string)
    {
        $this->load->library('resources/checks');
        $this->checks->entry($what, $string);
    }

    public function documents($string)
    {
        $this->load->library('resources/documents');
        $this->documents->entry($string);
    }
	
	public function expenses($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table'=>'boost_expenses',
            'entity'=>'expense',
            'model'=>'expenses_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }
	
	public function expenses_categories($id = null, $order = 'ASC', $limit = null, $offset = null)
    {
        $params = array(
            'table' => 'boost_expenses_categories',
            'entity' => 'expense category',
            'model' => 'generic_model'
        );

        $this->load->library('resources/generic', $params);
        $this->generic->entry($id, $order, $limit, $offset);
    }
		
}