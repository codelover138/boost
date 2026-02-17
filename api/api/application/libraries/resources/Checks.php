<?php

class Checks
{
    public $table_prefix;
    public $table;
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('db/db_update');
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
        $this->table = $this->table_prefix . 'organisations';

        $this->org_params = array(
            'table' => $this->table_prefix . 'organisations',
            'entity' => 'organisation'
        );
    }

    public function entry($what, $string)
    {
        $post_vars = $this->CI->regular->decode();
        $return = array('status'=>'ERROR');
        $string = urldecode($string);

        if(!is_null($string) && $string != '')
        {
            if($post_vars)
            {
                /* Piggyback:
                 * additional requested data
                 ----------------------------------------------------------------------------------------------*/
                if (isset($post_vars['piggyback'])) :
                    $return['piggyback'] = $this->CI->regular->piggyback($post_vars);
                endif;
            }

            $check = $this->$what($string);

            if($check['bool']) $return['status'] = 'OK';

            if(isset($check['message'])) $return['message'] = $check['message'];
        }
        else
        {
            $return['message'] = 'please specify a string to check against';
        }

        $this->CI->regular->respond($return);
    }

    public function account_url($string)
    {
        $return = array('bool'=>false);

        $this->org_params['where']['account_url'] = $string;

        $exists = $this->CI->generic_model->exists($this->org_params);

        if(!$exists) :
            $return['bool'] = true;
            $return['message'][] = 'This account name is available';
        else :
            $return['message'][] = 'This account name is already in use';
        endif;

        return $return;
    }

    public function account_exists($string)
    {
        $return = array('bool'=>false);

        $this->CI->load->library('db/switcher', array('account_name'=>$string));
        $this->CI->switcher->main_db();

        $this->org_params['where']['account_url'] = $string;
        $account_url_exists = $this->CI->generic_model->exists($this->org_params);

        unset($this->org_params['where']['account_url']);
        $this->org_params['where']['account_name'] = $string;
        $account_name_exists = $this->CI->generic_model->exists($this->org_params);

        if($account_url_exists || $account_name_exists) :
            $return['bool'] = true;
            $return['message'][] = 'This account exists';
        else :
            $return['message'][] = 'This account does not exist';
        endif;

        return $return;
    }

    public function password($string)
    {
        $this->CI->load->library('password');
        $evaluate = $this->CI->password->evaluate_password($string);

        return $evaluate;
    }
}