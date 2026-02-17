<?php
	$document_data = array_values($request['data'])[0];
?>

<div class="modal-dialog" role="document">
    <form  <?php if(isset($form_dataset) && $form_dataset != ''){ echo $form_dataset;}else{echo 'data-redirect-without-id="true" data-redirect-url="'.base_url('estimates/'.$document_data['id']).'"'; } ?> action="<?php echo base_api_url('estimates/'.$document_data['id'].'/status/'.$doc_status); ?>" method="put">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mark as <?php echo $doc_status; ?></h4>
            </div>
            
            <div class="modal-body">
            	<div class="col-sm-12 form-group">
                	<strong>Are you sure you want to save the following estimate as <?php echo $doc_status; ?>?</strong>
                </div>
                <div class="clearfix"></div>
           		<div class="col-sm-12 form-group">
                	<ul>
						<?php                        
							echo '<li>#'.$document_data['estimate_number'].'</li>'; 				
                        ?>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="modal-buttons">
            <button data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Estimate was successfully marked as <?php echo $doc_status; ?>. " data-related-section="estimates" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
    </form>        
</div>
