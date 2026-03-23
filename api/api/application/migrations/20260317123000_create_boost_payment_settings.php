<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_boost_payment_settings extends CI_Migration
{
    protected $table = 'boost_payment_settings';

    public function up()
    {
        $this->load->dbforge();

        if (!$this->db->table_exists($this->table)) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'merchant_id' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true
                ),
                'merchant_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true
                ),
                'passphrase' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true
                ),
                'test_mode' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ),
                'debug_email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                    'null' => true
                ),
                'trial_days' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 45
                ),
                'grace_period_days' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 7
                ),
                'default_plan_code' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true
                ),
                'plans_json' => array(
                    'type' => 'LONGTEXT',
                    'null' => true
                ),
                'itn_valid_hosts_json' => array(
                    'type' => 'LONGTEXT',
                    'null' => true
                ),
                'date_created' => array(
                    'type' => 'DATETIME',
                    'null' => true
                ),
                'date_modified' => array(
                    'type' => 'DATETIME',
                    'null' => true
                )
            ));

            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table($this->table, true);
        }

        if (!$this->db->field_exists('trial_days', $this->table)) {
            $this->db->query("ALTER TABLE `{$this->table}` ADD `trial_days` INT(11) NOT NULL DEFAULT 45 AFTER `debug_email`");
        }

        $seed = array(
            'merchant_id' => (string)$this->config->item('payfast_merchant_id'),
            'merchant_key' => (string)$this->config->item('payfast_merchant_key'),
            'passphrase' => (string)$this->config->item('payfast_passphrase'),
            'test_mode' => $this->config->item('payfast_test_mode') ? 1 : 0,
            'debug_email' => (string)$this->config->item('payfast_debug_email'),
            'trial_days' => (int)$this->config->item('payfast_trial_days'),
            'grace_period_days' => (int)$this->config->item('payfast_grace_period_days'),
            'default_plan_code' => isset($this->config->item('payfast_plan')['code'])
                ? $this->config->item('payfast_plan')['code']
                : null,
            'plans_json' => json_encode((array)$this->config->item('payfast_plans')),
            'itn_valid_hosts_json' => json_encode((array)$this->config->item('payfast_itn_valid_hosts')),
            'date_modified' => date('Y-m-d H:i:s')
        );

        $existing = $this->db->get_where($this->table, array('id' => 1))->row_array();
        if ($existing) {
            $this->db->where('id', 1)->update($this->table, $seed);
        } else {
            $seed['id'] = 1;
            $seed['date_created'] = date('Y-m-d H:i:s');
            $this->db->insert($this->table, $seed);
        }
    }

    public function down()
    {
        $this->load->dbforge();
        $this->dbforge->drop_table($this->table, true);
    }
}
