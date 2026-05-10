<?php
	$document_data = array_values($request['data'])[0];
?>

<div class="modal-dialog" role="document">
    <form  <?php if(isset($form_dataset) && $form_dataset != ''){ echo $form_dataset;}else{echo 'data-redirect-without-id="true" data-redirect-url="'.base_url('credit_notes/'.$document_data['id']).'"'; } ?> action="<?php echo base_api_url('credit_notes/'.$document_data['id'].'/status/sent'); ?>" method="put">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mark as Sent</h4>
            </div>
            
            <div class="modal-body">
            	<div class="col-sm-12 form-group">
                	<strong>Are you sure you want to save the following credit note as sent?</strong>
                </div>
                <div class="clearfix"></div>
           		<div class="col-sm-12 form-group">
                	<ul>
						<?php                        
							echo '<li>#'.$document_data['credit_note_number'].'</li>'; 				
                        ?>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="modal-buttons">
            <button data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Credit Note was successfully marked as sent. " data-related-section="credit_notes" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
    </form>        
</div>
