<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Expenses
{   
    private $CI;
   
    public function __construct()
    {
        $this->CI =& get_instance();
    }
	
	 public function add_filters($params, $identifier, $indices)
    {
	
		$post_vars = $this->CI->regular->sent_data;

		# Check for filters array
        if(isset($post_vars['filters']))
        {
            $filters = $post_vars['filters'];
        }
        else
        {
            $filters = $this->CI->generic_model->period_filter();

            $filters['start_date'] = date('Y-m-d', strtotime($filters['start_date']));
            $filters['end_date'] = date('Y-m-d', strtotime($filters['end_date']));
        }
		
		# add the filters data to the result
		$this->CI->generic->response_extra['filters'] = $filters;
		
		# modify the parameters to include filters in the query array
		$params = $this->CI->generic_model->resolve_filters($params, $filters, 'exp', 'date');

		return $params;
    }

    public function format_by_category($request_data)
    {

		foreach($request_data as $key => $data){
			
			if(!isset($result[$data->category_id]['total_amount'])){
				$result[$data->category_id]['total_amount'] = 0;
			}
			
			$result[$data->category_id]['category_name'] = $data->category_name;
			$result[$data->category_id]['total_amount'] = $result[$data->category_id]['total_amount'] + $data->total_amount;
			$result[$data->category_id]['expenses'][] = $data;
			
		}

		return $result;
    }
}

?>