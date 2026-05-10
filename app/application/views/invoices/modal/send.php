<?php
	$message_data = $piggyback['messages/invoices/'.$id];
	$organizations_details = array_values($piggyback['organizations'])[0];
	$email_signature = $piggyback['email_settings']['1']['email_signature'];
	$invoice_data = array_values($data)[0];
	
?>


<div class="modal-dialog" role="document">
	<form action="<?php echo base_api_url('invoices/'.$id); ?>" data-validation-placement="below" <?php if(isset($form_dataset) && $form_dataset != ''){ echo $form_dataset;}else{echo 'data-redirect-without-id="true" data-redirect-url="'.base_url('invoices/'.$id).'"'; } ?> method="send">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Send Invoice by Email</h4>
            </div>
            <div class="modal-body">
            <?php // print_r($data); ?>
                <div class="col-sm-2 form-group">
                    <label for="contact_email" class="control-label">To</label>
                </div>
                <div class="col-sm-10 form-group">
                    <input name="contact_email" class="form-control" id="contact_email" value="<?php echo $message_data['contact_emails'][$id] ; ?>">           
                </div>
                
                <div class="clearfix"></div>
                
                <div class="col-sm-2 form-group">
                    <label for="subject" class="control-label">Subject</label>
                </div>
                <div class="col-sm-10 form-group">
                    <input name="subject" class="form-control" id="Subject" value="New Invoice #<?php echo $invoice_data['invoice_number'] ; ?> from <?php echo $organizations_details['company_name'] ; ?>">           
                </div>
                
                <div class="clearfix"></div>
                
                <div class="col-sm-12 form-group">
                    <textarea name="message_body" rows="4" class="form-control"><?php echo $message_data['email_messages'][$id] ; ?></textarea>                 
                </div>
                
                <div class="clearfix"></div>
                
                <div class="col-sm-12 form-group text-center">
                   <a target="_blank" href="<?php echo $message_data['url_strings'][$id] ; ?>"><h4>View your Invoice</h4></a>               
                </div>
                
                <div class="col-sm-12 form-group text-center">
                     <div class="clearfix" style="border-bottom:1px dashed #b5d8ff"></div>
                </div>
                
                         
                <div class="col-sm-12">
                      <?php echo str_replace(array("\r\n","\n","\r"),'<br />',$email_signature); ?>                  
                </div>        
               
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-12">
                    <label class="checkbox-inline" style="margin-top:0;">
                        <input name="attach_pdf" value="yes" type="checkbox" autocomplete="off">Attach PDF to mail
                    </label>
                </div>
            </div>
        </div>
        <div class="modal-buttons">
            <button data-modal-body="Invoice successfully sent. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button"  class="btn btn-success saveButton saveFormData padded">Send</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
	</form>          
</div>