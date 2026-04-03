<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Expire_trials extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $this->help();
    }

    public function help()
    {
        if ($this->input->is_cli_request()) {
            echo 'This file marks expired trials as expired in boost_organisations.';
        } else {
            echo 'Cannot access this file from a web browser';
        }
    }

    public function run()
    {
        if (!$this->input->is_cli_request()) {
            echo 'Cannot access this file from a web browser';
            return;
        }

        $table_prefix = $this->config->item('db_table_prefix');
        $main_org_table = $table_prefix . 'organisations';

        // Find all orgs still marked as trial but whose trial_ends_at is in the past
        $this->db->select('id, account_name, trial_ends_at');
        $this->db->from($main_org_table);
        $this->db->where('subscription_status', 'trial');
        $this->db->where('trial_ends_at <', date('Y-m-d H:i:s'));

        $orgs = $this->db->get()->result();

        if (empty($orgs)) {
            echo "No expired trials found.\n";
            return;
        }

        $count = 0;
        foreach ($orgs as $org) {
            $this->db->where('id', $org->id);
            $this->db->update($main_org_table, array('subscription_status' => 'expired'));
            echo "Expired trial for org: {$org->account_name} (trial ended {$org->trial_ends_at})\n";
            $count++;
        }

        echo "Done. {$count} trial(s) marked as expired.\n";
    }
}
