<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$table_prefix = $this->config['db_table_prefix'];

# organisations
$config['resources']['delete'][] = 'organisations';
$config['resources']['delete'][] = 'organizations';

# countries
$config['resources']['post'][] = 'countries';
$config['resources']['put'][] = 'countries';
$config['resources']['delete'][] = 'countries';

# timezones
$config['resources']['post'][] = 'timezones';
$config['resources']['put'][] = 'timezones';
$config['resources']['delete'][] = 'timezones';

# user permissions
$config['resources']['post'][] = 'permissions';
$config['resources']['put'][] = 'permissions';
$config['resources']['delete'][] = 'permissions';

# activities
$config['resources']['post'][] = 'activities';
$config['resources']['put'][] = 'activities';
$config['resources']['delete'][] = 'activities';

# statements
$config['resources']['post'][] = 'statements';
$config['resources']['put'][] = 'statements';
$config['resources']['delete'][] = 'statements';

# registrations
$config['resources']['get'][] = 'registrations';
$config['resources']['put'][] = 'registrations';
$config['resources']['delete'][] = 'registrations';