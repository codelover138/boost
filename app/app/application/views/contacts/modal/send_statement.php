<?php
	//print_r($piggyback);
	$contact_data = $piggyback['contacts/'.$id][$id];
	$id = $contact_data['id'];
	$message_data = $piggyback['messages/statements/'.$id];
	$organizations_details = array_values($piggyback['organizations'])[0];
	$email_signature = $piggyback['email_settings']['1']['email_signature'];

?>


<div class="modal-dialog" role="document">
	<form action="<?php echo base_api_url('statements/'.$id); ?>" data-validation-placement="below" method="send">
    	<input name="filters[start_date]" type="hidden" value="<?php echo $filters['start_date']; ?>" />
        <input name="filters[end_date]" type="hidden" value="<?php echo $filters['end_date']; ?>" />
        <input name="test" type="hidden" value="test" />
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Send Statement by Email</h4>
            </div>
            <div class="modal-body">
            <?php //var_dump($contact_data); ?>
                <div class="col-sm-2 form-group">
                    <label for="contact_email" class="control-label">To</label>
                </div>
                <div class="col-sm-10 form-group">
                    <input name="contact_email" class="form-control" id="contact_email" value="<?php echo $contact_data['email'] ; ?>">           
                </div>
                
                <div class="clearfix"></div>
                
                <div class="col-sm-2 form-group">
                    <label for="subject" class="control-label">Subject</label>
                </div>
                <div class="col-sm-10 form-group">
                    <input name="subject" class="form-control" id="Subject" value="New statement <?php echo $filters['formatted_start_date'].' - '.$filters['formatted_end_date'] ; ?> from <?php echo $organizations_details['company_name'] ; ?>">           
                </div>
                
                <div class="clearfix"></div>
                
                <div class="col-sm-12 form-group">
                    <textarea name="message_body" rows="4" class="form-control"><?php echo $message_data['email_messages'][$id] ; ?></textarea>                 
                </div>
                
                <div class="clearfix"></div>
                
                <div class="col-sm-12 form-group text-center">
                   <!--<a target="_blank" href="<?php echo $message_data['url_strings'][$id]; ?>"><h4>Click here to view your statement</h4></a> -->  
                            
                </div>
                
                <div class="col-sm-12 form-group text-center">
                     <div class="clearfix" style="border-bottom:1px dashed #b5d8ff"></div>
                </div>
                
                         
                <div class="col-sm-12">
                      <?php echo $email_signature; ?>                  
                </div>        
               
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-12">
                   <!-- <label class="checkbox-inline" style="margin-top:0;">
                        <input name="attach_pdf" value="yes" type="checkbox" autocomplete="off" checked>Attach PDF to mail
                    </label> -->
                </div>
            </div>
        </div>
        <div class="modal-buttons">
            <button data-modal-body="Statement successfully sent. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button"  class="btn btn-success saveButton saveFormData padded">Send</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
	</form>          
</div>