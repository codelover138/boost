<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$table_prefix = $this->config['db_table_prefix'];

# financial records
$config['payment_methods']['table'] = $table_prefix.'invoice_payment_methods';
$config['payments']['table'] = $table_prefix.'invoice_payments';

# User roles and permissions
$config['roles']['table'] = $table_prefix . 'user_roles';
$config['permissions']['table'] = $table_prefix . 'user_permissions';
