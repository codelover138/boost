<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('currency')) {

    function currency($id, $return_value = null)
    {
        $CI =& get_instance();
        $table_prefix = $CI->config->item('db_table_prefix');

        $params = array(
            'table' => $table_prefix . 'currencies',
            'entity' => 'currency'
        );

        $result = $CI->generic_model->read($params, $id, 'single');

        if (is_null($return_value)) {
            return $result;
        } else {
            if (property_exists($result, $return_value)) {
                return $result->$return_value;
            } else {
                return false;
            }
        }
    }
}