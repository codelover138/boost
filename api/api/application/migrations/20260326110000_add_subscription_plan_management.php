<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_subscription_plan_management extends CI_Migration
{
    protected $table = 'boost_organisations';

    public function up()
    {
        $fields = array();

        if (!$this->db->field_exists('current_plan_code', $this->table)) {
            $fields['current_plan_code'] = array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'paid_until'
            );
        }

        if (!$this->db->field_exists('pending_plan_code', $this->table)) {
            $fields['pending_plan_code'] = array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'current_plan_code'
            );
        }

        if (!$this->db->field_exists('cancel_at_period_end', $this->table)) {
            $fields['cancel_at_period_end'] = array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'pending_plan_code'
            );
        }

        if (!$this->db->field_exists('cancellation_requested_at', $this->table)) {
            $fields['cancellation_requested_at'] = array(
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'cancel_at_period_end'
            );
        }

        if (!empty($fields)) {
            $this->dbforge->add_column($this->table, $fields);
        }
    }

    public function down()
    {
        $columns = array(
            'cancellation_requested_at',
            'cancel_at_period_end',
            'pending_plan_code',
            'current_plan_code'
        );

        foreach ($columns as $column) {
            if ($this->db->field_exists($column, $this->table)) {
                $this->dbforge->drop_column($this->table, $column);
            }
        }
    }
}
