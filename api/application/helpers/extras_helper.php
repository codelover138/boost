<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * script_tag
 *
 * Generates link to a JS file
 *
 * @access    public
 * @param    mixed    javascript srcs or an array
 * @param    string    type
 * @param    boolean    should index_page be added to the js path
 * @return    string
 */
if ( ! function_exists('script_tag'))
{
    function script_tag($src = '', $type = 'text/javascript', $index_page = FALSE)
    {
        $CI =& get_instance();

        $link = '';
        if (is_array($src))
        {
            foreach ($src as $v)
            {
                $link .= script_tag($v,$type,$index_page);
            }

        }
        else
        {
            $link = '<script ';
            if ( strpos($src, '://') !== FALSE)
            {
                $link .= 'src="'.$src.'" ';
            }
            elseif ($index_page === TRUE)
            {
                $link .= 'src="'.$CI->config->site_url($src).'" ';
            }
            else
            {
                $link .= 'src="'.$CI->config->slash_item('base_url').$src.'" ';
            }

            $link .= " type='{$type}'></script>";
        }
        return $link;
    }
}

/*
 * create_slug
 * */
if ( ! function_exists('create_slug'))
{
    function create_slug($string, $seperator = '_'){
        $slug = preg_replace('/[^A-Za-z0-9]+/', $seperator, strtolower($string));
        return $slug;
    }
}

/*
 * deslug
 * */
if ( ! function_exists('deslug'))
{
    function deslug($string, $seperator = '_', $capitalise = 1)
    {
        $string = str_replace($seperator, ' ', $string);

        switch($capitalise)
        {
            case 1:
                $string = ucwords($string);
                break;
            case 2:
                $string = strtoupper($string);
                break;
            default:
                $string = strtolower($string);
        }
        return $string;
    }
}

if ( ! function_exists('current_datetime'))
{
    function current_datetime()
    {
        date_default_timezone_set('Africa/Johannesburg');
        return date('Y-m-d H:i:s');
    }
}

if ( ! function_exists('current_date'))
{
    function current_date()
    {
        date_default_timezone_set('Africa/Johannesburg');
        return date('Y-m-d');
    }
}

if ( ! function_exists('current_time'))
{
    function current_time()
    {
        date_default_timezone_set('Africa/Johannesburg');
        return date('H:i:s');
    }
}

if ( ! function_exists('change_to_timestamp'))
{
    function change_to_timestamp($date)
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }
}

if ( ! function_exists('change_to_time'))
{
    function change_to_time($timestamp)
    {
        return date('H:i:s', strtotime($timestamp));
    }
}

if ( ! function_exists('change_to_date'))
{
    function change_to_date($timestamp)
    {
        return date('Y-m-d', strtotime($timestamp));
    }
}

if ( ! function_exists('dmy_date'))
{
    function dmy_date($timestamp)
    {
        return date('j M Y', strtotime($timestamp));
    }
}

if ( ! function_exists('sql_date_format'))
{
    function sql_date_format($timestamp)
    {
        $string = 'CONCAT(DATE_FORMAT('.$timestamp.', "%e"), " ", ';
        $string .= 'SUBSTR(DATE_FORMAT('.$timestamp.', "%M"), 1, 3), " ", ';
        $string .= 'DATE_FORMAT('.$timestamp.', "%Y"))';

        return $string;
    }
}

if (! function_exists('in_array_r'))
{
    function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }
}

if (! function_exists('in_object'))
{
    function in_object($value, $object){
        if($value == ""){
            trigger_error("in_object expects parameter 1 must not empty", E_USER_WARNING);
            return false;
        }
        if(!is_object($object)){
            $object = (object)$object;
        }
        foreach($object as $key => $val){
            if(!is_object($val) && !is_array($val)){
                if($val == $value){
                    return true;
                }
            }else{
                return in_object($value, $val);
            }
        }
        return false;
    }
}

if (! function_exists('cast_to_array'))
{
    function cast_to_array($object, $dimension = 1) {

        $arr = array();

        if($dimension > 1)
        {
            foreach($object as $obj) $arr[] = (array)$obj;
        }
        else
        {
            $arr = (array)$object;
        }

        return $arr;
    }
}

if (! function_exists('calling_function'))
{
    function calling_function() {

        return debug_backtrace()[2]['function'];
    }
}

if (!function_exists('full_url')) {
    function full_url()
    {
        return $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}

if (!function_exists('model_exists')) {
    function model_exists($model)
    {
        $model = ucwords($model);

        if (file_exists(APPPATH . 'models/' . $model . '.php')) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('make_positive')) {
    function make_positive($number)
    {
        if ($number < 0) :
            return -($number);
        endif;

        return $number;
    }
}

if (!function_exists('num_difference')) {
    function num_difference($num1, $num2)
    {
        $result = make_positive($num1) - make_positive($num2);
        return make_positive($result);
    }
}

if (!function_exists('get_protocol')) {
    function get_protocol()
    {
        //$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, strpos($_SERVER["SERVER_PROTOCOL"], '/'))) . '://';
		$protocol = isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https://' : 'http://';
        return $protocol;
    }
}

if (!function_exists('monify')) {
    function monify($value)
    {
        $value = preg_replace('/[^0-9-\.]/', '', $value);
        return doubleval($value);
    }
}