<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_settings
{
    protected $CI;
    protected $table = 'boost_payment_settings';
    protected $cached_settings = null;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function get()
    {
        if ($this->cached_settings !== null) {
            return $this->cached_settings;
        }

        $fallback = $this->fallback_settings();
        $current_db = $this->current_database();

        $this->CI->db->query('USE boost_api');
        $this->ensure_table();

        $row = $this->CI->db->get_where($this->table, array('id' => 1))->row_array();

        if ($current_db) {
            $this->CI->db->query('USE ' . $current_db);
        }

        if (!$row) {
            $this->cached_settings = $fallback;
            return $this->cached_settings;
        }

        $stored_plans = json_decode(isset($row['plans_json']) ? $row['plans_json'] : '[]', true);
        $stored_hosts = json_decode(isset($row['itn_valid_hosts_json']) ? $row['itn_valid_hosts_json'] : '[]', true);

        $settings = array(
            'merchant_id' => isset($row['merchant_id']) && $row['merchant_id'] !== '' ? $row['merchant_id'] : $fallback['merchant_id'],
            'merchant_key' => isset($row['merchant_key']) && $row['merchant_key'] !== '' ? $row['merchant_key'] : $fallback['merchant_key'],
            'passphrase' => isset($row['passphrase']) ? $row['passphrase'] : $fallback['passphrase'],
            'test_mode' => isset($row['test_mode']) ? (bool)$row['test_mode'] : $fallback['test_mode'],
            'debug_email' => isset($row['debug_email']) ? $row['debug_email'] : $fallback['debug_email'],
            'trial_days' => isset($row['trial_days']) ? (int)$row['trial_days'] : $fallback['trial_days'],
            'grace_period_days' => isset($row['grace_period_days']) ? (int)$row['grace_period_days'] : $fallback['grace_period_days'],
            'default_plan_code' => !empty($row['default_plan_code']) ? $row['default_plan_code'] : $fallback['default_plan_code'],
            'plans' => is_array($stored_plans) && !empty($stored_plans) ? $stored_plans : $fallback['plans'],
            'itn_valid_hosts' => is_array($stored_hosts) && !empty($stored_hosts) ? $stored_hosts : $fallback['itn_valid_hosts']
        );

        $this->cached_settings = $settings;
        return $this->cached_settings;
    }

    public function save($post)
    {
        $settings = $this->normalise_input($post);
        $current_db = $this->current_database();

        $this->CI->db->query('USE boost_api');
        $this->ensure_table();

        $record = array(
            'id' => 1,
            'merchant_id' => $settings['merchant_id'],
            'merchant_key' => $settings['merchant_key'],
            'passphrase' => $settings['passphrase'],
            'test_mode' => $settings['test_mode'] ? 1 : 0,
            'debug_email' => $settings['debug_email'],
            'trial_days' => $settings['trial_days'],
            'grace_period_days' => $settings['grace_period_days'],
            'default_plan_code' => $settings['default_plan_code'],
            'plans_json' => json_encode($settings['plans']),
            'itn_valid_hosts_json' => json_encode($settings['itn_valid_hosts']),
            'date_modified' => date('Y-m-d H:i:s')
        );

        $exists = $this->CI->db->get_where($this->table, array('id' => 1))->row_array();
        if ($exists) {
            $this->CI->db->where('id', 1)->update($this->table, $record);
        } else {
            $record['date_created'] = date('Y-m-d H:i:s');
            $this->CI->db->insert($this->table, $record);
        }

        if ($current_db) {
            $this->CI->db->query('USE ' . $current_db);
        }

        $this->cached_settings = $settings;
        return $settings;
    }

    public function get_grace_period_days()
    {
        $settings = $this->get();
        return isset($settings['grace_period_days']) ? (int)$settings['grace_period_days'] : 7;
    }

    public function get_trial_days()
    {
        $settings = $this->get();
        return isset($settings['trial_days']) ? (int)$settings['trial_days'] : 45;
    }

    protected function ensure_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` INT(11) NOT NULL,
            `merchant_id` VARCHAR(100) DEFAULT NULL,
            `merchant_key` VARCHAR(100) DEFAULT NULL,
            `passphrase` VARCHAR(255) DEFAULT NULL,
            `test_mode` TINYINT(1) NOT NULL DEFAULT 1,
            `debug_email` VARCHAR(150) DEFAULT NULL,
            `trial_days` INT(11) NOT NULL DEFAULT 45,
            `grace_period_days` INT(11) NOT NULL DEFAULT 7,
            `default_plan_code` VARCHAR(50) DEFAULT NULL,
            `plans_json` LONGTEXT NULL,
            `itn_valid_hosts_json` LONGTEXT NULL,
            `date_created` DATETIME NULL,
            `date_modified` DATETIME NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $this->CI->db->query($sql);

        if (!$this->CI->db->field_exists('trial_days', $this->table)) {
            $this->CI->db->query("ALTER TABLE `{$this->table}` ADD `trial_days` INT(11) NOT NULL DEFAULT 45 AFTER `debug_email`");
        }
    }

    protected function fallback_settings()
    {
        $plans = (array)$this->CI->config->item('payfast_plans');
        $default_plan = (array)$this->CI->config->item('payfast_plan');
        $default_plan_code = isset($default_plan['code']) ? $default_plan['code'] : '';

        if (!empty($default_plan) && $default_plan_code !== '' && !isset($plans[$default_plan_code])) {
            $plans[$default_plan_code] = $default_plan;
        }

        return array(
            'merchant_id' => (string)$this->CI->config->item('payfast_merchant_id'),
            'merchant_key' => (string)$this->CI->config->item('payfast_merchant_key'),
            'passphrase' => (string)$this->CI->config->item('payfast_passphrase'),
            'test_mode' => (bool)$this->CI->config->item('payfast_test_mode'),
            'debug_email' => (string)$this->CI->config->item('payfast_debug_email'),
            'trial_days' => (int)$this->CI->config->item('payfast_trial_days'),
            'grace_period_days' => (int)$this->CI->config->item('payfast_grace_period_days'),
            'default_plan_code' => $default_plan_code,
            'plans' => $plans,
            'itn_valid_hosts' => (array)$this->CI->config->item('payfast_itn_valid_hosts')
        );
    }

    protected function normalise_input($post)
    {
        $fallback = $this->fallback_settings();

        $plans = array(
            'boost-monthly' => array(
                'code' => 'boost-monthly',
                'name' => trim((string)(isset($post['monthly_name']) ? $post['monthly_name'] : (isset($fallback['plans']['boost-monthly']['name']) ? $fallback['plans']['boost-monthly']['name'] : 'Boost Monthly Subscription'))),
                'description' => trim((string)(isset($post['monthly_description']) ? $post['monthly_description'] : (isset($fallback['plans']['boost-monthly']['description']) ? $fallback['plans']['boost-monthly']['description'] : 'Monthly access to Boost Cloud Accounting'))),
                'amount' => number_format((float)(isset($post['monthly_amount']) ? $post['monthly_amount'] : (isset($fallback['plans']['boost-monthly']['amount']) ? $fallback['plans']['boost-monthly']['amount'] : 0)), 2, '.', ''),
                'currency' => trim((string)(isset($post['monthly_currency']) ? $post['monthly_currency'] : (isset($fallback['plans']['boost-monthly']['currency']) ? $fallback['plans']['boost-monthly']['currency'] : 'ZAR'))),
                'cycle_days' => (int)(isset($post['monthly_cycle_days']) ? $post['monthly_cycle_days'] : (isset($fallback['plans']['boost-monthly']['cycle_days']) ? $fallback['plans']['boost-monthly']['cycle_days'] : 30))
            ),
            'boost-yearly' => array(
                'code' => 'boost-yearly',
                'name' => trim((string)(isset($post['yearly_name']) ? $post['yearly_name'] : (isset($fallback['plans']['boost-yearly']['name']) ? $fallback['plans']['boost-yearly']['name'] : 'Boost Yearly Subscription'))),
                'description' => trim((string)(isset($post['yearly_description']) ? $post['yearly_description'] : (isset($fallback['plans']['boost-yearly']['description']) ? $fallback['plans']['boost-yearly']['description'] : 'Yearly access to Boost Cloud Accounting'))),
                'amount' => number_format((float)(isset($post['yearly_amount']) ? $post['yearly_amount'] : (isset($fallback['plans']['boost-yearly']['amount']) ? $fallback['plans']['boost-yearly']['amount'] : 0)), 2, '.', ''),
                'currency' => trim((string)(isset($post['yearly_currency']) ? $post['yearly_currency'] : (isset($fallback['plans']['boost-yearly']['currency']) ? $fallback['plans']['boost-yearly']['currency'] : 'ZAR'))),
                'cycle_days' => (int)(isset($post['yearly_cycle_days']) ? $post['yearly_cycle_days'] : (isset($fallback['plans']['boost-yearly']['cycle_days']) ? $fallback['plans']['boost-yearly']['cycle_days'] : 365))
            )
        );

        $hosts_input = isset($post['itn_valid_hosts']) ? preg_split('/\r\n|\r|\n/', trim((string)$post['itn_valid_hosts'])) : $fallback['itn_valid_hosts'];
        $hosts = array();
        foreach ((array)$hosts_input as $host) {
            $host = trim((string)$host);
            if ($host !== '') {
                $hosts[] = $host;
            }
        }

        return array(
            'merchant_id' => trim((string)(isset($post['merchant_id']) ? $post['merchant_id'] : $fallback['merchant_id'])),
            'merchant_key' => trim((string)(isset($post['merchant_key']) ? $post['merchant_key'] : $fallback['merchant_key'])),
            'passphrase' => trim((string)(isset($post['passphrase']) ? $post['passphrase'] : $fallback['passphrase'])),
            'test_mode' => !empty($post['test_mode']),
            'debug_email' => trim((string)(isset($post['debug_email']) ? $post['debug_email'] : $fallback['debug_email'])),
            'trial_days' => max(0, (int)(isset($post['trial_days']) ? $post['trial_days'] : $fallback['trial_days'])),
            'grace_period_days' => max(0, (int)(isset($post['grace_period_days']) ? $post['grace_period_days'] : $fallback['grace_period_days'])),
            'default_plan_code' => isset($post['default_plan_code']) && isset($plans[$post['default_plan_code']]) ? $post['default_plan_code'] : $fallback['default_plan_code'],
            'plans' => $plans,
            'itn_valid_hosts' => !empty($hosts) ? $hosts : $fallback['itn_valid_hosts']
        );
    }

    protected function current_database()
    {
        $query = $this->CI->db->query('SELECT DATABASE() AS db_name');
        $row = $query->row_array();
        return isset($row['db_name']) ? $row['db_name'] : null;
    }
}
