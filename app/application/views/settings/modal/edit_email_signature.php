<?php
	$email_data = array_values($request['data'])[0];
?>

<div class="modal-dialog" role="document">
	<form action="<?php echo base_api_url('email_settings'); ?>" method="put">
        <div class="modal-content  modal-sm">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Email Signature</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">			
                    <div class="col-sm-12">
                        <label for="email_signature" class="control-label">Signature</label>      
                    </div>            
                    <div class="col-sm-12">
                       <textarea name="email_signature" rows="6" class="form-control"><?php  echo $email_data['email_signature']; ?></textarea>        
                    </div>
                </div>
                <div class="clearfix formSpacer"></div>          
            </div>
        </div>
        <div class="modal-buttons">
            <button data-modal-body="Email signature updated successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
    </form>          
</div>