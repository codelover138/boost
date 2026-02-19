<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('curl');
        $this->load->helper('url');
        $this->load->helper('api_base');
    }

    public function workspaces()
    {
        // 1. Get User Data (for Header and Auth)
        $user_response = $this->curl->api_call('GET', 'me');

        if(isset($user_response['bool']) && $user_response['bool'] == true){
            $user_data = (array)$user_response['data'];
            // Re-structure permissions to match what header.php expects (array of strings)
            // The API might return permissions as objects or array of strings. 
            // Generic model `get_role_permissions` returns array of strings.
            // But `determine_user` returns `$user_data` which comes from `users_model->read`.
            
            // Let's assume user_data has permissions.
            // If not, we might need to fetch them. 
            // Header.php uses `$user_data['permissions']`.

            // Security Check
            if($user_data['email'] !== 'babu313137@gmail.com' && $user_data['email'] !== 'babu313136@gmail.com' && $user_data['email'] !== 'admin@boostaccounting.com') {
                 show_error('Unauthorized', 403);
            }

            // 2. Fetch Workspaces
            $workspaces_response = $this->curl->api_call('GET', 'admin/workspaces');

            $data['page']['title'] = 'Super Admin | Workspaces';
            $data['page']['heading'] = 'Workspaces';
            $data['page']['main_view'] = 'admin/workspaces';
            
            // Pass user_data to view (implicitly available to header if passed in $data?)
            // If header.php uses $user_data variable directly check if we can pass it as $data['user_data']
            // UPDATE: In CI, $this->load->view('header', $data) extracts $data. 
            // So $data['user_data'] becomes $user_data in the view.
            
            // However, previous investigation showed specific format needed.
            // Let's coerce it.
            $data['user_data'] = $user_data;
            // Also header expects permissions as array
            if(isset($user_data['permissions']) && !is_array($user_data['permissions'])){
                 // Convert if needed.
            }
            // Just in case permissions are missing from 'me' endpoint
            if(!isset($user_data['permissions'])){
                 $data['user_data']['permissions'] = [];
            }

            $data['workspaces'] = isset($workspaces_response['data']) ? $workspaces_response['data'] : [];
            
            $this->load->view('content', $data);

        } else {
            redirect('login');
        }
    }

    public function block($id)
    {
        $reason = $this->input->get('reason');
        $data = ['reason' => $reason];
        // Call API
        $this->curl->api_call('POST', 'admin/block_workspace/' . $id, $data);
        redirect('admin/workspaces');
    }

    public function unblock($id)
    {
        $this->curl->api_call('POST', 'admin/unblock_workspace/' . $id);
        redirect('admin/workspaces');
    }

    public function users($org_id)
    {
        if(!$this->check_auth()) return;

        $users_response = $this->curl->api_call('GET', 'admin/workspace_users/' . $org_id);

        $org = isset($users_response['org']) ? (array)$users_response['org'] : [];
        $org_name = !empty($org['company_name']) ? $org['company_name'] : 'Workspace #' . $org_id;

        $data['page']['title']    = 'Super Admin | ' . $org_name . ' Users';
        $data['page']['heading']  = $org_name . ' — Users';
        $data['page']['main_view']= 'admin/users';
        $data['org_id']           = $org_id;
        $data['org']              = $org;
        $data['users']            = isset($users_response['data']) ? $users_response['data'] : [];

        $user_response = $this->curl->api_call('GET', 'me');
        if(isset($user_response['bool']) && $user_response['bool'] == true){
             $data['user_data'] = (array)$user_response['data'];
             if(!isset($data['user_data']['permissions'])) $data['user_data']['permissions'] = [];
             $this->load->view('content', $data);
        } else {
            redirect('login');
        }
    }

    public function block_user($org_id, $user_id)
    {
        if(!$this->check_auth()) return;
        $this->curl->api_call('POST', "admin/toggle_user_status/$org_id/$user_id/0");
        redirect("admin/users/$org_id");
    }

    public function unblock_user($org_id, $user_id)
    {
        if(!$this->check_auth()) return;
        $this->curl->api_call('POST', "admin/toggle_user_status/$org_id/$user_id/1");
        redirect("admin/users/$org_id");
    }

    private function check_auth()
    {
        $user_response = $this->curl->api_call('GET', 'me');
        if(isset($user_response['bool']) && $user_response['bool'] == true){
            $user_data = (array)$user_response['data'];
            if($user_data['email'] !== 'babu313137@gmail.com' && $user_data['email'] !== 'babu313136@gmail.com' && $user_data['email'] !== 'admin@boostaccounting.com') {
                 show_error('Unauthorized', 403);
                 return false;
            }
            return true;
        }
        redirect('login');
        return false;
    }
}
