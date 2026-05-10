<?php

class Permissions
{
    public $table_prefix;
    public $resources = array(
        'dashboard',
        'invoices',
        'supplier_invoices',
        'expenses',
        'reports',
        'travel_tracker',
        'estimates',
        'contacts',
        'credit_notes',
        'account_settings',
        'send_invoices',
        'send_estimates',
        'send_credit_notes',
        'create_contact',
    );

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
    }

    /*--------------------------------------------------------------------------------------------------------------------------
     * VALIDATE PERMISSIONS :
     * Checks if a user has permission to perform their desired action
     --------------------------------------------------------------------------------------------------------------------------*/
    public function validate_permissions($method, $resource, $user_permissions)
    {
        if(in_array($resource, $this->resources))
        {
            $actions = array(
                'GET' => 'read_',
                'POST' => 'create_',
                'PUT' => 'edit_',
                'DELETE' => 'delete_',
                'SEND' => 'send_',
            );

            $permission = $actions[$method].$resource;
            $table = $this->table_prefix.'user_permissions';

            $permission_exists = $this->CI->generic_model->exists(array('table'=>$table, 'where'=>array('short_name'=>$permission)));
            $resource_exists = $this->CI->generic_model->exists(array('table'=>$table, 'where'=>array('short_name'=>$resource)));

            if(!$permission_exists && $resource_exists) :
                $permitted = true;
            else :
                $permitted = in_array($permission, $user_permissions);
            endif;
        }
        else
        {
            $permitted = true;
        }

        return $permitted;
    }
}