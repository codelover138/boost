<?php

class Expenses_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model');
		$this->load->model('activities_model');		
    }

    public function read($params, $identifier = null, $indices = 'id')
    {
		
		$table_prefix = $this->config->item('db_table_prefix');
		
		///echo json_encode($params);
		//exit;
		
		$fields = array(
            'exp.*',
            'cat.category_name',
			'cur.currency_name',
			'cur.currency_symbol',
			'cur.short_code "currency_short_code"',
			'con.organisation "client_name"',
			'ven.organisation "vendour_name"',
			'usr.first_name "author_first_name"',
			'usr.last_name "author_last_name"'
			
        );
		
        if (!isset($params['select'])) :
            if (isset($params['fields'])) :
                $params['select'] = $params['fields'];
            else :
                $params['select'] = $fields;
            endif;
        endif;

        if (isset($params['table'])) :
            $params['from'] = $params['table'] . ' exp';
            $params['table'] = $params['from'];
        else :
            $params['table'] = $table_prefix . 'expenses exp'; 
        endif;
		
        $params['join'][0]['table1'] = $table_prefix . 'expenses_categories cat';
        $params['join'][0]['table2'] = 'cat.id = exp.category_id';
		
		$params['join'][1]['table1'] = $table_prefix . 'currencies cur';
        $params['join'][1]['table2'] = 'cur.id = exp.currency_id';
		$params['join'][1]['type'] = 'left outer';
		
		$params['join'][2]['table1'] = $table_prefix . 'contacts con';
        $params['join'][2]['table2'] = 'con.id = exp.contact_id';
		$params['join'][2]['type'] = 'left outer';
		
		$params['join'][3]['table1'] = $table_prefix . 'contacts ven';
        $params['join'][3]['table2'] = 'ven.id = exp.supplier_id';
		$params['join'][3]['type'] = 'left outer';
		
		$params['join'][4]['table1'] = $table_prefix . 'users usr';
        $params['join'][4]['table2'] = 'usr.id = exp.usr_id';
		$params['join'][4]['type'] = 'left outer';
		
		$this->load->library('expenses');
		
		$params['main_id_field'] = 'exp.id';
		
		/* this will be used for reports but is commented for now
		
		# modify the params by adding the nessesare or sent filters 
		if(!isset($identifier)){
			$params = $this->expenses->add_filters($params, $identifier, $indices);
		}
		
		//echo json_encode($params);
		//exit;
		#get the data from the database
		$request_data = $this->generic_model->read($params, $identifier, $indices);
		
		
		#format the returned data relative to categories
		if(!isset($identifier)){
			$result = $this->expenses->format_by_category($request_data);
		}else{
			$result = $request_data;
		}*/
		
		$result = $this->generic_model->read($params, $identifier, $indices);

        return $result;
	    //return $request_data;
    }
	
	public function update($params = array(), $id, $post)
    {
       
	   $post['date'] = date('Y-m-d 00:00:00',strtotime($post['date']));
	   	   
	   if (isset($post['image_string'])) {
            if ($post['image_string'] != ''){
                $post = $this->process_image($post,$id);
			}else{
				$post['file_name'] = '';	
			}
			unset($post['image_string']);
       }
	   
	   
	   
	   $results = $this->generic_model->update($params, $id, $post);
	   
	   //update activity
		$a_post = array(
			'label' => 'Expense',
			'category' => 'expenses',
			'link' => 'expenses/' . $id,
			'item_id' => $id,
			'type' => 'standard',
			'short_message' => ' edited'
		);								
		$this->activities_model->create($a_post);

       return $results;
    }
	
	public function create($params = array(), $post)
    {
      
	  $post['date'] = date('Y-m-d 00:00:00',strtotime($post['date']));

	  $results = $this->generic_model->create($params, $post);
	  
	  $new_record_id = $results['record_id'];
	  
	  if (isset($post['image_string'])) {
            if ($post['image_string'] != '') :
                $post = $this->process_image($post,$new_record_id);
            endif;
			unset($post['image_string']);
      }

	  $results = $this->generic_model->update($params, $new_record_id, $post);
	   
	   //update activity
		$a_post = array(
			'label' => 'Expense',
			'category' => 'expenses',
			'link' => 'expenses/' . $new_record_id,
			'item_id' => $new_record_id,
			'type' => 'standard',
			'short_message' => ' created'
		);								
		$this->activities_model->create($a_post);

       return $results;
    }
	
	
    public function process_image($post,$id = NULL)
    {
        $this->load->library('images');
		
		$params['table'] = $this->config->item('db_table_prefix') . 'organisations'; 
		$result = $this->generic_model->read($params, NULL, 'id');
		
		$org_details = array_values($result)[0];

		$file_hash = md5($org_details->account_id.'-'.$id);
		
		if (!is_dir('assets/images/reciepts/'.$org_details->account_id)) {
			mkdir('assets/images/reciepts/'.$org_details->account_id, 0777, TRUE);		
		}

        $file = $post['image_string'];
		$file_name = 'reciepts/'.$org_details->account_id.'/'.$file_hash;
        $dimensions['width'] = 1024;
        
        # save image to file system and its location to the post array that will be saved in the database
        $save_image = $this->images->save_image($file, $file_name, $dimensions);

        if ($save_image['bool']) :
            $post['file_name'] = base_url($save_image['output_file']) . '?' . rand(100, 999);
        endif;

        return $post;
    }

}