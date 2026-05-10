<?php

class Messaging
{
    public $table_prefix;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('generic_model');
        $this->CI->load->model('template_model');
        $this->CI->load->library('encryption');
        $this->table_prefix = $this->CI->config->item('db_table_prefix');
    }

    # actual sending of an email
    public function send_email($params)
    {
        $return = array('bool' => false, 'record_id' => $params['entity_id']);

        $message = $this->email_message($params);

        if ($message && !empty($message)) {
			

            $this->CI->load->helper('email');
            if (valid_email($message['contact_emails'][$params['entity_id']])) {
                # prepping the email for sending
                $this->CI->load->library('email');

                $this->CI->email->from($this->CI->config->item('from_email'), 'Boost Accounting');
                $this->CI->email->to($message['contact_emails']);

                $this->CI->email->subject($params['subject']);
                $this->CI->email->message($message['email_messages'][$params['entity_id']]);

                if ($this->CI->email->send()) {
                    $return['bool'] = true;
                    $return['message'] = 'email sent successfully';
                } else {
                    $return['message'] = 'email sending failed: ' . $this->CI->email->print_debugger();
                }
            } else {
                $return['message'] = 'invalid contact email address';
            }

            $return['contact_email'] = $message['contact_emails'][$params['entity_id']];
        } else {
            $return['message'] = 'no message found';
        }

        return $return;
    }

    /*
     * SEND EMAIL2 PARAMS:
     * entity, entity_id, contact_email, message_body, subject,
     * */
    public function send_email2($params)
    {
        
		$return['bool'] = false;
		
        $this->CI->load->helper('email');
		
        if (valid_email($params['contact_email']))
        {
            # load the organisation model and get the data
			if(!class_exists('organisations_model')){
				$this->CI->load->model('organisations_model');
			}
			
			$organisation_data = array_values($this->CI->organisations_model->read())[0];
			
			#load the theme data and get the data
			if(!class_exists('theme_settings_model')){
				$this->CI->load->model('theme_settings_model');
			}
			
			$theme_data = array_values($this->CI->theme_settings_model->read())[0];
			
			# prepping the email for sending
            $this->CI->load->library('email');

            $this->CI->email->from($this->CI->config->item('from_email'), $organisation_data->company_name);
			$this->CI->email->reply_to($organisation_data->email, $organisation_data->company_name);
            $this->CI->email->to($params['contact_email']);
            $this->CI->email->subject($params['subject']);
			
            # Attach pdf document if checked | Not allowed for statements as they don't yet have a pdf generator
            $entity = $params['entity'].'s';
			
			if(isset($params['attach_pdf']))
			{
				if($params['attach_pdf'] == true && $entity != 'statements'&& $entity != 'payments')
				{
					$this->CI->load->library('pdf/'.$entity);
					$pdf = $this->CI->$entity->generate_pdf($params['entity_id'], 'F', '', false);
	
					$this->CI->email->attach($pdf['file_path']);
				}
			}
			
			if($params['entity'] == 'payment'){
				$params['entity'] = 'invoice';
			}

            # Prepare message
            $data = array(
                'subject' => $params['subject'],
                'heading' => $organisation_data->company_name,
                'message' => $params['message_body'],
                'link' => $this->CI->config->item('document_url').$this->encrypt($params)
            );

            # Adds email signature to mailer
            if(isset($params['email_signature'])) {
                $data['email_signature'] = $params['email_signature'];
            }
            else {
                $email_settings_params = array(
                    'table' => $this->table_prefix . 'email_settings',
                    'entity' => 'email setting',
                    'fields' => 'email_signature'
                );

                $data['email_signature'] = $this->CI->generic_model->read($email_settings_params, 1, 'single')->email_signature;
            }

            if(isset($params['account_name']))
            {
                $account_name = $params['account_name'];
            }
            else
            {
                $headers = $this->CI->regular->get_request_headers();
                $account_name = $headers['Account-Name'];
                $params['account_name'] = $account_name;
            }

            $data['link'] = get_protocol().$account_name.'.'.$data['link'];
			
			$data['organisation'] = $organisation_data;	
			$data['theme_data'] = $theme_data; 
			$data['link_entity_text'] = $params['entity'];
								
            $message = $this->CI->load->view('templates/mailer', $data, true);
			
            $this->CI->email->message($message);

            if ($this->CI->email->send()) {

                if($entity != 'statements')
                {
                    # change invoice status to sent if the invoice status is "draft" ------------------
                    $entity_params = array('table' => $params['table'], 'entity' => $params['entity']);
                    if ($params['entity'] == 'invoice') :
                        $entity_params['where'] = array('status' => 'draft');
                    endif;
					
					$entity_post = array('status' => 'sent', 'single_update' => true);
					
					if($params['entity'] == 'credit_note'){
						# load the organisation model and get the data
						if(!class_exists('credit_notes_model')){
							$this->CI->load->model('credit_notes_model');
						}
						
						$entity_params['items_table'] = $this->table_prefix.'credit_note_items';
						
						$this->CI->credit_notes_model->update($entity_params, $params['entity_id'], $entity_post);
						
					}else{                    	
						 # change invoice status to "sent"						 
                    	$this->CI->generic_model->update($entity_params, $params['entity_id'], $entity_post);
					}

                   
                }

                $return['bool'] = true;
                $return['message'] = 'email sent successfully';

                /*-- LOG ACTIVITY -------------------------------------------------------------------------*/
                /* $a_post = array(
                    //'label' => ucwords($params['entity_id']).' #' . $invoice_data->invoice_number,
                    //'link' => $entity . $params['entity_id'],
                    //'entity_id' => $params['entity_id'],
                    //'short_message' => currency($invoice_data->currency_id, 'currency_symbol') . $post['payment_amount'] . ' emailed to ' . $log_msg,
                    //'entity' => create_slug($params['entity_id'] . 's') 
                );	*/			
				
                //$this->activities_model->create($a_post);
                /*------------------------------------------------------------------------------------------*/

            } else {
                $return['message'] = 'email sending failed: ' . $this->CI->email->print_debugger();
            }
        }
        else
        {
            $return['message'] = 'invalid contact email address';
        }

        $return['contact_email'] = $params['contact_email'];
		
		
		

        return $return;
    }

    public function decrypt($string)
    {
        $string = base64_decode($string);
        $decrypted_string = $this->CI->encryption->decrypt($string);
        $decoded = json_decode($decrypted_string, true);

        return $decoded;
    }

    public function encrypt($params)
    {
        $encrypted_string = base64_encode($this->CI->encryption->encrypt(json_encode($params)));
        $encrypted_string = str_replace('=', '', $encrypted_string);

        return $encrypted_string;
    }

    /*
     * EMAIL MESSAGE PARAMS:
     * entity, entity_id, resource
     * */
    public function email_message($params)
    {
		

	  $post = $this->CI->regular->decode();

	   if(!empty($post))
	   {		   
		   if(isset($post['filters'])){
			   $params['filters'] = $post['filters'];
		   }		   
	   }
	   
	   
	   if(isset($params['account_name']))
        {
            $account_name = $params['account_name'];
        }
        else
        {
            $headers = $this->CI->regular->get_request_headers();
            $account_name = $headers['Account-Name'];
            $params['account_name'] = $account_name;
        }
        $this->CI->load->library('db/switcher', array('account_name' => $account_name));
        $this->CI->switcher->account_db();
		
		# switch to statement if contact is is the entity
		if($params['entity'] == 'contact'){
			$params['entity'] = 'statement';
		}

        # email template with replaced tokens
        $email_template = $this->get_email_template($params);

        $return = array();
        $return['email_signature'] = $email_template->email_signature;

        if ($email_template && !empty($email_template))
        {
            $tokens = $this->get_tokens($params);

            if($tokens['tokens'] && !empty($tokens['tokens']))
            {
                
				$msg = $params['entity'] . '_message';
                $email_message = $email_template->$msg;

                foreach ($tokens['tokens'] as $key => $token)
                {
                    $message_source = $email_message;

                    foreach ($token as $entity_token_key => $entity_token)
                    {
                        $params['entity_id'] = $key;
                        $message_source = str_replace($entity_token_key, $entity_token, $message_source);

                        $return['email_messages'][$key] = $message_source;

                        if (isset($tokens['statement_data'][$key]->contact->email)) {
                            $return['contact_emails'][$key] = $tokens['statement_data'][$key]->contact->email;
                        }
						
						# set the link to hold preview param 
						# the param will ensure the document is not marked at "viewed" when the document loads
						$params['preview'] = true;
						
						//print_r($params);
						
                        # Encrypted link
                        $link = $this->CI->config->item('document_url').$this->encrypt($params);
                        $link = get_protocol().$account_name.'.'.$link;
                        $return['url_strings'][$key] = $link;
						

                        //$return['url_strings'][$key] = $this->encrypt($params);
                        //$return['decrypted'][$key] = $this->decrypt($return['url_strings'][$key]);
                    }
                }

                if (!empty($tokens['tokens'])) :
                    $return['message'] = 'messages generated';
                else :
                    $return['message'] = 'no tokens to replace';
                endif;

                return $return;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function get_email_template($params)
    {
        if($params['entity'] == 'contact'){
			$params['entity'] = 'statement';
		}
		
		//echo $params['entity'].'-test';
		
		$params['select'] = 'email_signature, ' . $params['entity'] . '_message';
        $params['table'] = $this->table_prefix . 'email_settings';
        //$params['where'] = array('organisation_id' => $params['organisation_id']);

        $email_template = $this->CI->generic_model->read($params);

        if (empty($email_template)) :
            return false;
        else :
            return $email_template[0];
        endif;
    }

    /*
     * GET TOKENS PARAMS:
     * table (of entity), entity, entity_id, resource if table parameter is not given
     * */
    public function get_tokens($params)
    {
        if (!isset($params['table'])) :
            $table = $this->table_prefix . $params['resource'];
        else :
            $table = $params['table'];
        endif;

        # Change entity value to invoice if entity is payment.
        # This is so placeholders within the payment message can be replaced
		# UPDATE: Use flag to determine if it was payment then use proper payment amount to replace placeholder
		$was_payement = false;
        if($params['entity'] == 'payment' || $params['entity'] == 'payments')
        {
            $params['entity'] = 'invoice';
			$was_payement = true;
        }

        # get entity data
        $entity_params = array();
        $entity_params['table'] = $table;
        $entity_params['entity'] = $params['entity'];

        if($params['entity'] != 'statement')
        {
            $entity_params['items_table'] = $this->table_prefix . $params['entity'] . '_items';
            $query_result = $this->CI->template_model->read($entity_params, $params['entity_id']);
        }
        else
        {
            $query_result = $this->CI->generic_model->read($entity_params, $params['entity_id']);
        }

        if ($query_result && !empty($query_result))
        {
            $entity_data = $query_result;

            # get sending organisation name
            $org_params = array('table' => $this->table_prefix . 'organisations', 'entity' => 'organisation', 'fields' => 'company_name');
            $org_name = $this->CI->generic_model->read($org_params, null, 'zero')[0]->company_name;

            # get the actual tokens
            $params['table'] = $this->table_prefix . 'email_tokens';
            $tokens = $this->CI->generic_model->read($params);

            $return_tokens = array();
            $return_tokens['statement_data'] = $entity_data;
			
		

            # adding additional proprties for easy access and replacing tokens
            foreach ($query_result as $key => $result)
            {
                if($params['entity'] != 'statement')
                {
                    $entity_data[$key]->amount = $result->total_amount;
					
					if($was_payement && isset($result->last_payment))
						$entity_data[$key]->amount = $result->last_payment;

                    # copying values out from contact object
                    if(isset($result->contact->first_name)) {
                        $entity_data[$key]->contact_first_name = $result->contact->first_name;
                        $entity_data[$key]->client_first_name = $result->contact->first_name;
                    }

                    if(isset($result->contact->last_name)) {
                        $entity_data[$key]->contact_last_name = $result->contact->last_name;
                        $entity_data[$key]->client_last_name = $result->contact->last_name;
                    }

                    if(isset($result->contact->organisation)) {
                        $entity_data[$key]->contact_company_name = $result->contact->organisation;
                        $entity_data[$key]->client_company_name = $result->contact->organisation;
                    }
                }
                else
                {
                    # copying values out from contact object
                    if(isset($result->first_name)) {
                        $entity_data[$key]->contact_first_name = $result->first_name;
                        $entity_data[$key]->client_first_name = $result->first_name;
                    }

                    if(isset($result->last_name)) {
                        $entity_data[$key]->contact_last_name = $result->last_name;
                        $entity_data[$key]->client_last_name = $result->last_name;
                    }

                    if(isset($result->organisation)) {
                        $entity_data[$key]->contact_company_name = $result->organisation;
                        $entity_data[$key]->client_company_name = $result->organisation;
                    }
                }

                # adding organisation name of the sending organisation
                if (!empty($org_name)) :
                    $entity_data[$key]->company_name = $org_name;
                endif;

                # replace tokens in message
                foreach ($tokens as $token_key => $token) {
                    if (property_exists($entity_data[$key], $token->short_name)) {
                        $short_name = $token->short_name;
                        //$tokens[$token_key]->replacement_value = $entity_data->$short_name;
                        $return_tokens['tokens'][$key][$token->token] = $entity_data[$key]->$short_name;
                    }
                }
            }

            return $return_tokens;
        }
        else
        {
            return false;
        }
    }
}