<?php
	$tax_data = array_values($request['data'])[0];
?>

<div class="modal-dialog" role="document">
	<form data-redirect-without-id="true" <?php if(isset($form_dataset) && $form_dataset != ''){ echo $form_dataset;} ?> action="<?php echo base_api_url('taxes/'.$id); ?>" data-validation-placement="below" method="put">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Tax Option</h4>
        </div>
        <div class="modal-body">
        	            
            <div class="col-sm-12">
                <label for="tax_name" class="control-label">Tax Option Details</label>         
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-sm-6 form-group">
                <input value="<?php echo $tax_data['tax_name']; ?>" type="text" class="form-control" name="tax_name" id="tax_name" placeholder="Tax Description">
            </div>
            
             <div class="col-sm-6 form-group">
                <input value="<?php echo $tax_data['percentage']; ?>" type="text" class="form-control" name="percentage" id="percentage" placeholder="Tax Percentage">
            </div>
            
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="modal-buttons">
       <button data-redirect-url="<?php echo base_url('settings/taxes'); ?>" data-related-section="taxes" data-modal-body="Tax added successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
    </div> 
    </form>          
</div>