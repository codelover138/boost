<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	/**
	 * Base API URL
	 *
	 * Returns base_api_url [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	function base_api_url($uri = '', $protocol = NULL)
	{
		$ci=& get_instance();
		
		$base_url = $ci->config->item('api_base_url');

		if (isset($protocol))
		{
			$base_url = $protocol.substr($base_url, strpos($base_url, '://'));
		}
		
		if ($ci->config->item('enable_query_strings') === FALSE){
			if (is_array($uri)){
				$uri = implode('/', $uri);
			}
			$modded_uri = trim($uri, '/');
		}
		elseif (is_array($uri)){
			$modded_uri = http_build_query($uri);
		}

		return $base_url.ltrim($modded_uri, '/');
	}

	// -------------------------------------------------------------

?>