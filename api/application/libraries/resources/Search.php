<?php
class Search
{
    public $params;
    public $model;

    public function __construct($params = null)
    {
        $this->CI =& get_instance();
        $this->params = $params;
        $this->CI->load->model('generic_model');

        if(isset($this->params['model'])) :
            $this->CI->load->model($this->params['model']);
            $this->model = $this->params['model'];
        else :
            $this->model = 'generic_model';
        endif;
    }

    public function entry($string, $field)
    {
	   
	    $post_vars = $this->CI->regular->decode();
        $return = array('status'=>'ERROR');
        $string = urldecode($string);

        if(!is_null($string) && $string != '')
        {
            if($post_vars) :
                $return = $this->go_fish($string, $post_vars);

                /* Piggyback:
                 * additional requested data
                 ----------------------------------------------------------------------------------------------*/
                if (isset($post_vars['piggyback'])) :
                    $return['piggyback'] = $this->CI->regular->piggyback($post_vars);
                endif;

            else :
                if(!is_null($field) && $field != '') :
                    $return = $this->go_fish($string, array('fields'=>array($field)));
                else :
                    $return = $this->go_fish($string);
                endif;
            endif;
        }
        else
        {
            $return['message'] = 'please specify a string to search against';
        }

        $this->CI->regular->respond($return);
    }

    public function go_fish($string, $post = array())
    {
        $db_name = $this->CI->db->database;
        $table_prefix = $this->CI->config->item('db_table_prefix');

        # array to contain tables that will be searched
        $tables_to_search = array();

        # defining tables that should not be searched
        $restricted_tables = array(
            $table_prefix.'members',
            $table_prefix . 'sessions',
            $table_prefix . 'user_roles',
            $table_prefix . 'user_permissions',
            $table_prefix . 'logos'
        );

        $result = array('status'=>'OK');

        $valid_resource = true;
        $invalid_fields = array();

        # specified resource/table array
        if(isset($this->params['table']) || !is_null($this->params['table']))
        {
            if($this->params['table'] == 'all')
            {
                $specified_resources = 'all';
            }
            else
            {
                if($this->CI->generic_model->describe($this->params['table'])) :
                    $specified_resources = array($this->params['table']);
                else :
                    $result['status'] = 'ERROR';
                    $valid_resource = false;
                    $result['message'] = 'resource "'.$this->params['table'].'" not found';
                endif;
            }
        }
        elseif(isset($post['tables']))
        {
            $specified_resources = (array)$post['tables'];
        }
        elseif(isset($post['resources']))
        {
            $specified_resources = $post['resources'];
        }
        else
        {
            $specified_resources = array();
        }

        //var_dump($specified_resources);

        if($valid_resource) {
            # checking $specified_resources array / search through specified tables
            if (is_array($specified_resources) && !empty($specified_resources) && !is_null($specified_resources)) {
                foreach ($specified_resources as $table => $field) :
                    if (!is_numeric($table)) {
                        $tables_to_search[$table_prefix . $table] = (array)$field;
                    } else {
                        $tables_to_search[$table_prefix . $field] = '';
                    }

                endforeach;

                # sort tables array by index/key
                krsort($tables_to_search);
            } else {
                # if there is no specified resource/table name, search through all tables in the db
                $show_tables = $this->CI->db->query('SHOW TABLES FROM ' . $db_name)->result();
                foreach ($show_tables as $table_result) :
                    $table_key = 'Tables_in_' . $db_name;
                    $tables_to_search[$table_result->$table_key] = '';
                endforeach;
            }

            # matches array to contain all matches yet to be found
            $all_matches = array();

            # going through all tables in the array, seeking a string match
            foreach ($tables_to_search as $table => $table_field) {
                if (!in_array($table, $restricted_tables)) {

                    # describe: gets all defined table fields
                    $describe = $this->CI->generic_model->describe($table);

                    if ($describe) {
                        # get resource name to add to result array later
                        $resource = str_replace($table_prefix, '', $table);

                        $params = array();
                        $params['from'] = $table;

                        # building string of search fields -------------------------------------------------------*/
                        $fields = array();
                        if (is_array($table_field['search']) && !empty($table_field['search'])) {
                            foreach ($table_field['search'] as $field) :
                                if (isset($describe[$field])) :
                                    $fields[$field] = $describe[$field];
                                else :
                                    $invalid_fields[$resource][] = $field;
                                endif;
                            endforeach;
                        } elseif (isset($post['fields'])) {
                            foreach ($post['fields'] as $specified_field) :
                                $fields[$specified_field] = $describe[$specified_field];
                            endforeach;
                        } else {
                            $fields = $describe;
                        }
                        /*-----------------------------------------------------------------------------------------*/

                        # getting the data ------------------------------------------------------------------------------*/
                        if (!empty($fields)) {
                            $search_fields = '';
                            $search_fields_count = 1;
                            foreach ($fields as $key => $description) {
                                if ($search_fields_count < count($fields)) :
                                    $search_fields .= 'COALESCE(' . $key . ', ""), " ", ';
                                else :
                                    $search_fields .= 'COALESCE(' . $key . ', "")';
                                endif;

                                $search_fields_count++;
                            }

                            # check if there are any specific fields to return
                            if (isset($table_field['return']) && !empty($table_field['return'])) :
                                $params['select'] = $table_field['return'];
                            endif;

                            $params['like'] = array('CONCAT(' . $search_fields . ')' => $string); # MySQL LIKE clause

                            # checks for matching data
                            $matches = $this->CI->generic_model->read($params);

                            # adds matching data (if found) to the main matching data array
                            if (!empty($matches)) :
                                $all_matches[$resource] = $matches;
                            endif;
                        }
                        /*-----------------------------------------------------------------------------------------------*/
                    }
                }
            }

            # checks if the all_matches array has value and adds them to the return data array
            if (!empty($all_matches) && isset($all_matches)) :
                $result['data'] = $all_matches;
            else :
                $result['message'] = 'no matches found for the following string: ' . $string;
            endif;

            if (!empty($invalid_fields)) :
                $result['unidentified_fields'] = $invalid_fields;
            endif;
        }

        return $result;
    }
}