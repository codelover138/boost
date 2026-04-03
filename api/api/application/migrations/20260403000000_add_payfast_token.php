<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_payfast_token extends CI_Migration
{
    public function up()
    {
        // Add payfast_token to subscription_payments to track recurring subscription token
        if (!$this->db->field_exists('payfast_token', 'boost_subscription_payments')) {
            $this->dbforge->add_column('boost_subscription_payments', array(
                'payfast_token' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'payfast_reference'
                )
            ));
        }

        // Add payfast_token to organisations to track the active recurring subscription
        if (!$this->db->field_exists('payfast_token', 'boost_organisations')) {
            $this->dbforge->add_column('boost_organisations', array(
                'payfast_token' => array(
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'cancellation_requested_at'
                )
            ));
        }
    }

    public function down()
    {
        if ($this->db->field_exists('payfast_token', 'boost_subscription_payments')) {
            $this->dbforge->drop_column('boost_subscription_payments', 'payfast_token');
        }

        if ($this->db->field_exists('payfast_token', 'boost_organisations')) {
            $this->dbforge->drop_column('boost_organisations', 'payfast_token');
        }
    }
}
