<?php
class Generic_model extends CI_Model
{
    public $result_message;
    public $error_fields;
    public $validation_results;

    public function __construct()
    {
        parent::__construct();
    }

    /*-------------------------------------------------------------------------------------
     * CREATE
     * -----------------------------------------------------------------------------------*/
    public function create($params, $post)
    {
        
        $result = array('bool'=>false);

        if(!empty($post)) :
            # validation
            $field_validation = $this->validate_fields($params, $post);

            if (isset($field_validation['message']) && $field_validation['message'] != '') {
                foreach ($field_validation['message'] as $error_msg) :
                    $this->result_message[] = $error_msg;
                endforeach;

                $result['validation_results'] = $this->validation_results;
            }

            # post after validation
            //$post = $field_validation['post'];

            if($field_validation['bool']) :

                if(isset($post[0]) && is_array($post[0])) :

                    # updates date_modified column if it exists within the table
                    if($this->describe($params['table'], 'date_modified')) :
                        foreach($post as $key => $pos) :
                            $post[$key]['date_modified'] = current_datetime();
                        endforeach;
                    endif;

                    $create = $this->db->insert_batch($params['table'], $post);
                else :

                    # remove unknown fields
                    if (isset($field_validation['unknown_fields'])) :
                        foreach ($field_validation['unknown_fields'] as $unknown_field => $value) :
                            unset($post[$unknown_field]);
                        endforeach;
                    endif;

                    # updates date_modified column if it exists within the table
                    if($this->describe($params['table'], 'date_modified')) :
                        $post['date_modified'] = current_datetime();
                    endif;

                    $create = $this->db->insert($params['table'], $post);

                endif;

                if (isset($field_validation['unknown_fields'])) :
                    $result['validation_results'] = $field_validation;
                endif;
            else :
                $create = false;

                //$this->result_message[] = $field_validation['message'];

                unset($field_validation['bool']);
                unset($field_validation['message']);

                //$result['validation_results'] = $field_validation['field_errors'];
            endif;
            #------------------------------------------------------------------------

            if($create) :
                $result['bool'] = true;
                $result['record_id'] = $this->db->insert_id();
                $this->result_message[] = $params['entity'] . ' successfully created';
            else :
                $this->result_message[] = 'could not create ' . $params['entity'];
            endif;
        else :
            $this->result_message[] = 'empty post';
        endif;

        if (isset($field_validation['field_errors'])) {
            foreach ($field_validation['field_errors'] as $field => $error_msg) :
                $this->validation_results[$field] = $error_msg;
            endforeach;

            $result['validation_results'] = $this->validation_results;
        }

        $result['message'] = $this->result_message;
        return $result;
    }

    /*-------------------------------------------------------------------------------------
     *  READ
     * -----------------------------------------------------------------------------------*/
    public function read($params, $identifier = null, $indices = 'id')
    {
        # select
        if(isset($params['select'])) :
            $this->db->select($params['select']);
        elseif(isset($params['fields'])) :
            $this->db->select($params['fields']);
        elseif(isset($params['field'])) :
            $this->db->select($params['field']);
        endif;

        # table / from
        $table = null;
        if(isset($params['table'])) :
            $table = $params['table'];
        elseif(isset($params['from'])) :
            $this->db->from($params['from']);
        endif;

        # join
        if(isset($params['join'])) :
            foreach($params['join'] as $join) :
                if(isset($join['type'])) {
                    $this->db->join($join['table1'], $join['table2'], $join['type']);
                }
                else {
                    $this->db->join($join['table1'], $join['table2']);
                }
            endforeach;
        endif;

        # like / where / where in
        if(isset($params['where'])) :
            $this->db->where($params['where']);
        endif;

        # determine which position the wildcard% is supposed to be in
        if(isset($params['wildcard_position'])) :
            $params['wildcard_position'] = $params['wildcard_position'];
        else :
            $params['wildcard_position'] = 'both';
        endif;

        if(isset($params['like'])) :
            $this->db->like($params['like'], null, $params['wildcard_position']);
        endif;

        if(isset($params['where_in'])) :
            $this->db->where_in($params['where_in']['field'], $params['where_in']['values']);
        endif;

        if(isset($params['where_not_in'])) :
            $this->db->where_not_in($params['where_not_in']['field'], $params['where_not_in']['value']);
        endif;

        # or where / or like variants
        if(isset($params['where']) || isset($params['like']) || isset($params['where_in']) || isset($params['where_not_in'])) :

            # where
            if(isset($params['or_where'])):
                $this->db->or_where($params['or_where']);
            endif;

            if(isset($params['or_where_in'])):
                $this->db->or_where_in($params['or_where_in']['field'], $params['or_where_in']['value']);
            endif;

            if(isset($params['or_where_not_in'])):
                $this->db->or_where_not_in($params['or_where_not_in']['field'], $params['or_where_not_in']['value']);
            endif;

            # like
            if(isset($params['or_like'])):
                $this->db->or_like($params['or_like']);
            endif;

            if(isset($params['not_like'])):
                $this->db->not_like($params['not_like']['field'], $params['not_like']['value']);
            endif;

            if(isset($params['or_not_like'])):
                $this->db->or_not_like($params['or_not_like']['field'], $params['or_not_like']['value']);
            endif;

        endif;

        if((!is_null($identifier) && is_numeric($identifier))) :
            if(isset($params['main_id_field'])) :
                $this->db->where($params['main_id_field'], $identifier);
            else :
                $this->db->where('id', $identifier);
            endif;
        endif;

        # order
        if(isset($params['order_by'])) :
            $this->db->order_by($params['order_by']);
        endif;
		
        # limit
        if(isset($params['limit'])) :
            if(isset($params['offset'])) :
                $offset = $params['offset'];
            else :
                $offset = null;
            endif;
            $this->db->limit($params['limit'], $offset);
        endif;

        # group by
        if (isset($params['group_by'])) :
            $this->db->group_by($params['group_by']);
        endif;

        # optimise for last id if that parameter has been set
        if(isset($params['last_id'])) :
            $this->db->select('id "last_id"');
            $this->db->limit(1);
            $this->db->order_by('id DESC');
        endif;

        # result / output
        $result_original = $this->db->get($table);
        $temp_result = $result_original->result();

        $result = array();

        if ($result_original->num_rows() > 0) {
            switch ($indices) {
                case 'id':
                    foreach ($temp_result as $key => $res) :
                        if (isset($res->id)) :
                            $result[$res->id] = $res;
                        else :
                            $result[] = $res;
                        endif;
                    endforeach;
                    break;

                case 'zero':
                    $result = $temp_result;
                    break;

                case 'single':
                    $result = $temp_result[0];
                    break;

                default:
                    $result = $temp_result;
                    break;
            }
        }

        if(isset($params['get_num_rows'])) :
            return $result_original->num_rows();
        endif;
		
		

        return $result;
    }

    /*-------------------------------------------------------------------------------------
     *   UPDATE
     * -----------------------------------------------------------------------------------*/
    public function update($params, $id, $post, $strict = true)
    {
        $result = array('bool'=>false);

        if(!empty($post) && !empty($id)) :

            $this->db->where('id', $id);

            # check if there is a custom where clause
            if (isset($params['where'])) :
                $this->db->where($params['where']);
            endif;

            # updates date_modified column if it exists within the table
            if($this->describe($params['table'], 'date_modified')) :
                $post['date_modified'] = current_datetime();
            endif;

            # checking if update was only a single record via an api link
            if (isset($post['single_update'])) :
                # removes single_update update link
                unset($post['single_update']);
            endif;

            # validation
            if ($strict) :
                $validation = $this->validate_fields($params, $post);
            else:
                $validation = array('bool' => true);
            endif;

            $run = false;
            if($validation['bool']) :
                $this->db->update($params['table'], $post);
                if ($this->db->affected_rows() > 0) :					
                    $run = true;
                endif;
            else :
                //$run = false;
                $result['message'][] = $validation['message'];

                unset($validation['bool']);
                unset($validation['message']);

                $result['validation_results'] = $validation['field_errors'];
            endif;
            #--------------------------------------------------------------------------------

            if($run) :
                $result['bool'] = true;
                $result['record_id'] = $id;
                $result['message'][] = $params['entity'] . ' information successfully updated';
            else :
                $result['message'][] = 'could not update ' . $params['entity'] . ' information';
            endif;
        else :
            $result['message'][] = 'empty post or id';
        endif;

        return $result;
    }

    /*------------------------------------------------------------------------------------
     *  DELETE
     * -----------------------------------------------------------------------------------*/
    public function delete($params, $id)
    {
        $result = array('bool'=>false);

        if(!empty($id)) :

            $field = 'id';

            if (isset($params['field'])) {
                $field = $params['field'];
            }
            if (isset($params['main_id_field'])) {
                $field = $params['main_id_field'];
            }

            if (is_array($id)) :
                $this->db->where_in($field, $id);
            else :
                $this->db->where($field, $id);
            endif;

            $run = $this->db->delete($params['table']);

            if($run) :
                $result['bool'] = true;
                $result['message'][] = $params['entity'] . ' successfully deleted';
            else :
                $result['message'][] = 'could not delete ' . $params['entity'];
            endif;
        else :
            $result['message'] = 'empty id';
        endif;

        return $result;
    }

    public function u_delete($params)
    {
        $result = array('bool' => false);

        # check if there is a custom where clause
        if (isset($params['where'])) :
            $this->db->where($params['where']);
        endif;

        $run = $this->db->delete($params['table']);
        $result['affected_rows'] = $this->db->affected_rows();

        if ($run) :
            $result['bool'] = true;
            $result['message'][] = $params['entity'] . ' successfully deleted';

        else :
            $result['message'][] = 'could not delete ' . $params['entity'];
        endif;

        return $result;
    }

    /*------------------------------------------------------------------------------------
     *  EXTRA FUNCTIONS
     * -----------------------------------------------------------------------------------*/
    public function describe($table, $field = null)
    {
        $table_prefix = $this->config->item('db_table_prefix');
        $table = str_replace($table_prefix, '', $table);
        $table = $table_prefix.$table;

        if($this->show_tables($table))
        {
            $sql = 'DESCRIBE ' . $this->db->escape_str($table);
            $result = $this->db->query($sql)->result();

            $description = array();
            foreach($result as $res)
            {
                $description[$res->Field] = $res;
                unset($res->Field);
            }

            if(!is_null($field)) :
                if(isset($description[$field])) :
                    return $description[$field];
                else :
                    return false;
                endif;
            else :
                return $description;
            endif;
        }
        else
        {
            return false;
        }
    }

    public function show_tables($table = null)
    {
        $table_prefix = $this->config->item('db_table_prefix');
        $table = str_replace($table_prefix, '', $table);
        $table = $table_prefix.$table;

        if(isset($table)) :
            $sql = 'SHOW TABLES LIKE "' . $this->db->escape_str($table) .  '"';
        else :
            $db_name = $this->db->database;
            $sql = 'SHOW TABLES FROM ' . $db_name;
        endif;

        $result  = $this->db->query($sql);

        if($result->num_rows() > 0) :
            return $result->result();
        else :
            return false;
        endif;
    }

    public function exists($params, $return_data = false)
    {
        $result = $this->db->get_where($params['table'], $params['where']);

        if ($result->num_rows() > 0) :
            if ($return_data):
                return $result->result()[0];
            endif;
            return true;
        else :
            return false;
        endif;
    }

    public function exists2($params, $return_data = false, $num_rows = false)
    {
        if(isset($params['where']))
        {
            $result = $this->db->get_where($params['table'], $params['where']);
        }
        elseif(isset($params['like']))
        {
            $result = $this->db->like($params['like'])->get($params['table']);
        }
        else
        {
            $result = array();
        }

        if ($result->num_rows() > 0)
        {
            $result_to_send = $result->result();
            if($result->num_rows() == 1) $result_to_send = $result_to_send[0];

            if(!$return_data && !$num_rows)
            {
                $return = true;
            }
            elseif($return_data && $num_rows)
            {
                $return['data'] = $result_to_send;
                $return['num_rows'] = $result->num_rows();
            }
            elseif($return_data)
            {
                $return = $result_to_send;
            }
            else
            {
                $return = $result->num_rows();
            }
        }
        else
        {
            $return = false;
        }

        return $return;
    }

    public function field_exists($field, $table)
    {
        return $this->db->field_exists($field, $table);
    }

    public function get_last_row($params)
    {
        $params['last_id'] = true;
        $result = $this->read($params);
        return $result[0];
    }

    public function value_exists($params)
    {
        $table = $params['table'];
        $field = $params['field'];
        $value = $params['value'];

        if (!isset($params['where'])) {
            $this->db->where($field, $value);
        } else {
            $this->db->where($params['where']);
        }

        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function get_required_fields($table)
    {
        $table_descriptions = $this->describe($table);

        $required_fields = array();
        if ($table_descriptions)
        {
            foreach ($table_descriptions as $key => $table_description)
            {
                if ($table_description->Null == 'NO' && $table_description->Key != 'PRI' && $table_description->Default == null && $key != 'id') {
                    $required_fields[] = $key;
                }
            }
        }

        return $required_fields;
    }

    public function validate_fields($params, $post)
    {
        $return = array('bool'=>false, 'message'=>'');
        $errors = array();
        $field_errors = array();

        /* MISSING FIELDS:
         * Determine if there are any missing fields
         *--------------------------------------------------------------------------------------------------------------------*/
        $required_fields = $this->get_required_fields($params['table']); # fields required by db table
		//print_r($required_fields);
		//print_r($post);
        foreach ($required_fields as $required_field) {

            if (isset($post[0]) && is_array($post[0])) {

                foreach ($post as $key => $post_arr) :
                    if (calling_function() == 'update') :
                        $missing_field_condition = isset($post[$key][$required_field]) && (is_null($post[$key][$required_field]) || empty($post[$key][$required_field]) || $post[$key][$required_field] == '');
                    else :
                        $missing_field_condition = !isset($post[$key][$required_field]);
                    endif;
					
                    if ($missing_field_condition) :
                        $errors['missing_fields'][] = $required_field;
                        $field_errors[$required_field] = 'required field';						
                    endif;

                endforeach;

            } else {

                if (calling_function() == 'update') :
                    $missing_field_condition = isset($post[$required_field]) && (is_null($post[$required_field]) || empty($post[$required_field]) || $post[$required_field] == '');
                else :
                    $missing_field_condition = !isset($post[$required_field]) || $post[$required_field] === '';
                endif;

                if ($missing_field_condition) :
                    $errors['missing_fields'][] = $required_field;
                    $field_errors[$required_field] = 'required field';					
                endif;

            }
        }
        /*END OF MISSING FIELDS-----------------------------------------------------------------------------------------------------*/


        /* UNKNOWN FIELDS:
         * filtering post data for columns that are not found within the db table
         *--------------------------------------------------------------------------------------------------------------------*/
        if ($post)
        {
            foreach ($post as $field => $value) {

                $explode_table = explode(' ', $params['table']);
                $table = $explode_table[0];

                if (!is_numeric($field) && !$this->db->field_exists($field, $table)) {
                    $errors['unknown_fields'][$field] = $value;
                    if (array_key_exists($field, $field_errors)) {
                        $field_errors[$field] .= ', unknown field';
                    } else {
                        $field_errors[$field] = 'unknown field';
                    }
                }
            }
        }
        /*END OF UNKNOWN FIELDS-----------------------------------------------------------------------------------------------------*/


        /* EMAIL VALIDATION
         * checking for invalid email and saving to an array
         * --------------------------------------------------------------------------------------------------------------------------*/
        if ($post)
        {
            $explode_table = explode(' ', $params['table']);
            $table = $explode_table[0];

            foreach ($post as $field => $value)
            {
                if ($this->db->field_exists($field, $table)) {
                    //if (strpos($field, 'email') !== false) :
                    if ($field == 'email' && is_string($value) && strpos($value, '@') !== false) :
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) :
                            # invalid email address
                            $errors['invalid_emails'][] = $value;
                            if (array_key_exists($field, $field_errors)) {
                                $field_errors[$field] .= ', valid email address required';
                            } else {
                                $field_errors[$field] = 'valid email address required';
                            }

                        endif;
                    endif;
                }
            }
        }
        /*END OF EMAIL VALIDATION-----------------------------------------------------------------------------------------------------*/

        if(empty($errors)) :
            $return['bool'] = true;
        else :
            if(!empty($errors['missing_fields'])) :
                $return['message'][] = 'Please enter all required form information.';
                $return['missing_fields'] = $errors['missing_fields'];
            endif;

            if(!empty($errors['invalid_emails'])) :
                $return['message'][] = 'Please provide a valid email address.';
                $return['invalid_emails'] = $errors['invalid_emails'];
            endif;

            if(!empty($errors['unknown_fields'])) :
                //$return['message'] = 'unknown fields found. ';
                $return['unknown_fields'] = $errors['unknown_fields'];
            endif;

            if (!empty($errors['unknown_fields']) && empty($errors['missing_fields']) && empty($errors['invalid_emails'])) :
                $return['bool'] = true;
            endif;

            if (!empty($field_errors)) :
                $return['field_errors'] = $field_errors;
            endif;
        endif;

        return $return;
    }
	
	public function resolve_filters($params, $filters, $alias, $db_col)
    {
        if((isset($filters['start_date']) || isset($filters['end_date'])))
        {
            $end_date = current_datetime();

            $date_param_index = "$alias.$db_col ";

            if(isset($filters['start_date'])) {
                $start_date = $filters['start_date'];
                $date_param_index .= "BETWEEN '". $start_date . "' AND";
            }
            else {
                $date_param_index .= "<=";
            }

            if(isset($filters['end_date'])) {
                $end_date = date('Y-m-d', strtotime($filters['end_date'])) . ' 23:59:59';
            }

            $params['where'][$date_param_index] = $end_date;
        }
        elseif(isset($filters['period']))
        {
            $period = $filters['period'];
            $period_filter = $this->period_filter($period);

            $start_date = $period_filter['start_date'];
            $end_date = $period_filter['end_date'];

            $date_param_index = "$alias.$db_col BETWEEN '" . $start_date . "' AND";
            $params['where'][$date_param_index] = $end_date;
        }
        else
        {
            $period_filter = $this->period_filter();

            $start_date = $period_filter['start_date'];
            $end_date = $period_filter['end_date'];

            $date_param_index = "$alias.$db_col BETWEEN '" . $start_date . "' AND";
            $params['where'][$date_param_index] = $end_date;
        }

        if(isset($filters['order_by'])) $params['order_by'] = $filters['order_by'];

        return $params;
    }
	
	public function period_filter($period = 30)
    {
        $period -= 1; # subtract 1 from period

        $return['end_date'] = current_datetime();

        $start_date_obj = new DateTime(date("Y-m-d") . " 23:59:59");
        $start_date_obj->sub(new DateInterval('P'.$period.'D'));
        $return['start_date'] = $start_date_obj->format('Y-m-d');

        return $return;
    }
}