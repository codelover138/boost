<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Trial_reminders extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load required libraries/models
        $this->load->database();
        $this->load->library('email');
    }

    public function index()
    {
        $this->help();
    }

    public function help()
    {
        if ($this->input->is_cli_request()) {
            echo 'This file sends out trial expiration reminders.';
        }
        else {
            echo 'Cannot access this file from a web browser';
        }
    }

    public function auto_send()
    {
        if (!$this->input->is_cli_request()) {
            echo 'Cannot access this file from a web browser';
            return;
        }

        $table_prefix = $this->config->item('db_table_prefix');
        $main_org_table = $table_prefix . 'organisations';

        // Find trials ending in 5 days or fewer (every day for the last 5 days)
        // DATEDIFF(trial_ends_at, CURDATE()) calculates the difference in days
        $this->db->select('id, account_name, account_db, trial_ends_at, DATEDIFF(DATE(trial_ends_at), CURDATE()) as days_left');
        $this->db->from($main_org_table);
        $this->db->where('subscription_status', 'trial');
        $this->db->where('DATEDIFF(DATE(trial_ends_at), CURDATE()) <=', 5);
        $this->db->where('DATEDIFF(DATE(trial_ends_at), CURDATE()) >=', 1);

        $orgs = $this->db->get()->result();

        if (empty($orgs)) {
            echo "No trials ending in 5 days or fewer found today.\n";
            return;
        }

        foreach ($orgs as $org) {
            if (empty($org->account_db)) {
                continue; // Skip if no DB is assigned
            }

            // Connect to the specific account database to get the owner's email
            // Use query to switch context in the same connection (assuming same DB user has access to both)
            $this->db->query('USE ' . $org->account_db);

            $this->db->select('first_name, last_name, email');
            $this->db->from($table_prefix . 'users');
            $this->db->where('owner', 1); // Or user_role_id = 1
            $this->db->limit(1);
            $user_query = $this->db->get();

            if ($user_query && $user_query->num_rows() > 0) {
                $user = $user_query->row();

                $this->send_trial_email($user, $org);
                echo "Sent reminder to {$user->email} for org {$org->account_name} (Ends in {$org->days_left} days)\n";
            }

            // Switch back to the main database just in case for other queries
            $main_db = $this->db->database; // wait, CodeIgniter doesn't expose database like this easily, but `USE $main_db` would be needed if we make another query on main DB.
            // Since we use full query with the CI connection, we should switch back to main DB.
            $this->db->db_select($this->db->database); // This selects back the configured CI default database
        }

        echo "Trial reminder process completed.\n";
    }

    private function send_trial_email($user, $org)
    {
        $subject = 'Boost Accounting - Trial Expiration Reminder';

        $days_text = $org->days_left == 1 ? '1 day' : $org->days_left . ' days';

        $message = '<p>Dear ' . ucwords($user->first_name) . ',</p>';
        $message .= '<p>This is a polite reminder that your free trial for <strong>' . ucwords(str_replace('_', ' ', $org->account_name)) . '</strong> ';
        $message .= 'is ending in exactly <strong>' . $days_text . '</strong>.</p>';
        $message .= '<p>To ensure uninterrupted access to your account and data, please upgrade your subscription before the trial expires.</p>';
        $message .= '<p><a href="' . get_protocol() . 'boostaccounting.com/login' . '">Login to Upgrade</a></p>';
        $message .= '<p>Best regards,<br>The Boost Accounting Team</p>';

        $data = array(
            'subject' => $subject,
            'heading' => $subject,
            'message' => $message
        );

        $message_to_send = $this->load->view('templates/mailer', $data, true);

        $this->email->clear();
        $this->email->from($this->config->item('from_email'), 'Boost Accounting');
        $this->email->to($user->email);
        $this->email->subject($subject);
        $this->email->message($message_to_send);

        $this->email->send();
    }
}
