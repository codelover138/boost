<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('userhandler');
        
        // Security Check: Only allow "Super Admin"
        // For Proof of Concept, we check a specific email or use a hardcoded token mechanism
        // In production, this should be a specific permission/role check
        
        $user_data = $this->userhandler->determine_user();
        if(!$user_data['bool']) {
            $this->regular->header_(401);
            $this->regular->respond(['status' => 'ERROR', 'message' => ['Unauthorized']]);
            die();
        }

        // Hardcoded Owner Email check for safety in this demo
        // Replace 'owner@boostaccounting.com' with the actual owner email from the DB or a config
        if($user_data['data']->email !== 'babu313136@gmail.com' && $user_data['data']->email !== 'admin@boostaccounting.com') {
             $this->regular->header_(403);
             $this->regular->respond(['status' => 'ERROR', 'message' => ['Forbidden: Super Admin Access Only']]);
             die();
        }
    }

    public function workspaces($id = null)
    {
        $this->load->model('generic_model');

        // Organisations table lives in the main DB (boost_api), switch to it explicitly
        $this->db->query('USE boost_api');

        $params = array(
            'table' => 'boost_organisations',
            'entity' => 'organisations'
        );

        if($id) {
            $result = $this->generic_model->read($params, $id, 'single');
        } else {
             $result = $this->generic_model->read($params);
        }

        $this->regular->respond(['status' => 'OK', 'data' => $result]);
    }

    public function block_workspace($id)
    {
        $this->modify_block_status($id, 1);
    }

    public function unblock_workspace($id)
    {
        $this->modify_block_status($id, 0);
    }

    private function modify_block_status($id, $status)
    {
        $this->load->model('generic_model');

        // Curl library sends data as a raw JSON body, not form-encoded POST
        // so we must read it from php://input and decode it
        $post = json_decode(file_get_contents('php://input'), true);
        if (!is_array($post)) $post = [];

        // Organisations table lives in the main DB (boost_api)
        $this->db->query('USE boost_api');

        $update_data = [
            'is_manual_blocked' => $status,
            'manual_block_reason' => isset($post['reason']) ? $post['reason'] : null
        ];

        $params = array(
            'table' => 'boost_organisations',
            'entity' => 'organisation'
        );

        $result = $this->generic_model->update($params, $id, $update_data);
        
        if($result['bool']) {
             $this->regular->respond(['status' => 'OK', 'message' => ['Workspace updated']]);
        } else {
             $this->regular->header_(500);
             $this->regular->respond(['status' => 'ERROR', 'message' => ['Update failed']]);
        }
    }

    public function workspace_users($org_id)
    {
        $this->load->model('generic_model');

        // 1. Get org from main DB (boost_api)
        $this->db->query('USE boost_api');
        $org_params = array('table' => 'boost_organisations', 'entity' => 'organisation');
        $org = $this->generic_model->read($org_params, $org_id, 'single');

        if (!$org) {
            $this->regular->header_(404);
            $this->regular->respond(['status' => 'ERROR', 'message' => ['Organization not found']]);
            die();
        }

        if (empty($org->account_db)) {
            $this->regular->header_(400);
            $this->regular->respond(['status' => 'ERROR', 'message' => ['Organization DB not found']]);
            die();
        }

        // 2. Switch to the tenant's own DB and fetch users
        $this->db->query('USE ' . $org->account_db);
        $user_params = array(
            'table'  => $this->config->item('db_table_prefix') . 'users',
            'entity' => 'user'
        );
        $users = $this->generic_model->read($user_params);

        // 3. Respond with org info + users
        $this->regular->respond([
            'status' => 'OK',
            'org'    => $org,
            'data'   => $users
        ]);
    }

    public function toggle_user_status($org_id, $user_id, $status)
    {
        // 1. Get Org to switch DB — organisations live in boost_api
        $this->load->model('generic_model');
        $this->db->query('USE boost_api');

        $org_params = array('table' => 'boost_organisations', 'entity' => 'organisation');
        $org = $this->generic_model->read($org_params, $org_id, 'single');

        if (!$org || empty($org->account_db)) {
            $this->regular->header_(404);
            $this->regular->respond(['status' => 'ERROR', 'message' => ['Organization not found']]);
            die();
        }

        // 2. Switch to the tenant's own DB
        $this->db->query('use ' . $org->account_db);

        // 3. Update User is_active
        $update_data = ['is_active' => (int)$status];
        $user_params = array(
            'table' => $this->config->item('db_table_prefix') . 'users',
            'entity' => 'user'
        );

        $result = $this->generic_model->update($user_params, $user_id, $update_data);

        if($result['bool']) {
             $this->regular->respond(['status' => 'OK', 'message' => ['User status updated']]);
        } else {
             $this->regular->header_(500);
             $this->regular->respond(['status' => 'ERROR', 'message' => ['Update failed']]);
        }
    }
}