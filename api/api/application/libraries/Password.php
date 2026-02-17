<?php

class Password
{
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function check_password($post)
    {
        $result = array('bool' => false);

        if (isset($post['password']) && $post['password'] != '') {

            $evaluate_password = $this->evaluate_password($post['password']);

            if (isset($post['confirm_password']) && $post['password'] == $post['confirm_password']) {
                unset($post['confirm_password']); # remove confirm password field
                $post['password'] = md5($post['password']);

                if($evaluate_password['bool'])return $post; # returns post if password evaluated with no errors
            }
            else {
                $result['message'][] = 'Passwords do not match';
                //$result['validation_results']['password'] = 'Please add password';
                $result['validation_results']['confirm_password'] = 'Password confirmation does not match password';
            }

            if(!$evaluate_password['bool']) :
                $result['message'][] = $evaluate_password['message'];
                $result['validation_results']['password'] = $evaluate_password['message'];
            endif;

            return $result;
        }
        else
        {
            if ((!isset($post['password']) || $post['password'] == '') && (!isset($post['confirm_password']) || $post['confirm_password'] == '')) {
                if (isset($post['confirm_password'])) :
                    unset($post['confirm_password']);
                endif;
                return $post;
            }

            # password not given
            if (!isset($post['password']) || $post['password'] == '') :
                $result['message'][] = 'Please add a password';
                $result['validation_results']['password'] = 'Please add a password';
            endif;

            # password not confirmed
            if (!isset($post['confirm_password']) || $post['confirm_password'] == '') :
                $result['message'][] = 'Please confirm password';
                $result['validation_results']['confirm_password'] = 'Please confirm password';
            endif;

            return $result;
        }
    }

    public function evaluate_password($password, $extra_params = array())
    {
        /* *
            Between Start -> ^
            And End -> $
            of the string there has to be at least one number -> (?=.*\d)
            and at least one letter -> (?=.*[A-Za-z])
            and it has to be a number, a letter or one of the following: !@#$% -> [0-9A-Za-z!@#$%]
            and there have to be 8-12 characters -> {8,12}
         * */

        $min_chars = $this->CI->config->item('pass_min_length');
        $max_chars = $this->CI->config->item('pass_max_length');

        if(!empty($extra_params))
        {
            if(isset($extra_params['min_chars'])) $min_chars = $extra_params['min_chars'];
            if(isset($extra_params['max_chars'])) $max_chars = $extra_params['max_chars'];
        }

        $return = array('bool'=>false);

        if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#.%]{'.$min_chars.','.$max_chars.'}$/', $password)) {
            $return['message'] = 'Please ensure that the password has a minimum of '.$min_chars.' characters, has at least 1 letter and 1 number';
        }
        else {
            $return['bool'] = true;
            $return['message'] = 'Password is acceptable';
        }

        return $return;
    }

    public function random_password($password_length = 8)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $password_length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}