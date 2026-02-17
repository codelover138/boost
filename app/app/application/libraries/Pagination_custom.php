<?php

class Pagination_custom{
	
	public $pagination_configs = array();
	
	public function __construct()
    {
        $this->CI =& get_instance();        
		$this->pagination_configs = $this->CI->config->item('pagination');
    }
	
	public function sort_format($sort_by_array,$order_by,$direction,$results_per_page,$starting_record)
    {

		foreach($sort_by_array as $sort_key => $sort_data){
			$sort_by_data[$sort_key]['element'] = $sort_data['element'];
			$sort_by_data[$sort_key]['display'] = $sort_data['display'];
			$sort_by_data[$sort_key]['active'] = false;
			$sort_by_data[$sort_key]['direction'] = 'desc';
			
			if($sort_data['element'] == $order_by){
				
				$active_data['element'] = $sort_data['element'] ;
				$active_data['active'] = true;	
				$active_data['display'] = $sort_data['display'] ;
								
				if($direction == 'desc'){
					$sort_by_data[$sort_key]['direction'] = 'asc';
					$sort_by_data[$sort_key]['display'] .= ' (asc)';
					$active_data['direction'] = 'desc';
					$active_data['display'] .= ' (desc)';
				}else{
					$sort_by_data[$sort_key]['direction'] = 'desc';
					$sort_by_data[$sort_key]['display'] .= ' (desc)';
					$active_data['direction'] = 'asc';
					$active_data['display'] .= ' (asc)';
				}
				
				$active_data['link'] = base_url($this->CI->uri->segment(1).'/'.$active_data['element'].'/'.$active_data['direction'].'/'.$results_per_page.'/'.$starting_record);
			}
			
			$sort_by_data[$sort_key]['link'] = base_url($this->CI->uri->segment(1).'/'.$sort_by_data[$sort_key]['element'].'/'.$sort_by_data[$sort_key]['direction'].'/'.$results_per_page.'/'.$starting_record);
		}
		
		if(isset($active_data)){
			//array_unshift($sort_by_data, $active_data);
		}

		return $sort_by_data;
    }
	
	public function reformat($data)
    {		
		
		if(isset($data['current_page_link'])){
			$reformatted_array['current_page_link'] = $data['current_page_link'];
		}
		
		if(isset($data['current_page_link_index'])){
			$reformatted_array['current_page_link_index'] = $data['current_page_link_index'];
		}else{
			$reformatted_array['current_page_link_index'] = 0;
		}
		
		if(isset($data['next'])){
			$reformatted_array['next'] = $data['next'];	
		}
		
		if(isset($data['previous'])){
			$reformatted_array['previous'] = $data['previous'];	
		}
		
		$start_offset = $reformatted_array['current_page_link_index']+1 - ceil($this->pagination_configs['max_page_links']/2);
		
		if($start_offset <= 0){
			$starting_key = 0;
		}else{
			$starting_key = $start_offset;
		}
		
		if(count($data['pages_links']) - $starting_key > $this->pagination_configs['max_page_links']){
			$end_key = $starting_key + $this->pagination_configs['max_page_links'] -1;
		}else{
			$end_key = key( array_slice( $data['pages_links'], -1, 1, TRUE ) );
			if(count($data['pages_links'])>$this->pagination_configs['max_page_links']){
				$starting_key = $end_key - $this->pagination_configs['max_page_links']+1;
			}
		}
		
		
		for($i=$starting_key; $i<=$end_key; $i++){
			$reformatted_array['pages_links'][$i] = $data['pages_links'][$i];
		}
        
		return $reformatted_array;
    }
	
}




?>