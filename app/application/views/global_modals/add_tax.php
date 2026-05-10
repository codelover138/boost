<div class="modal-dialog" role="document">
	<form data-success-function="updateTaxList(<?php if(isset($activeElementId)){ echo $activeElementId; }else{ echo 'null';} ?>,id)" <?php if(isset($form_dataset) && $form_dataset != ''){ echo $form_dataset;} ?> action="<?php echo base_api_url('taxes'); ?>" data-validation-placement="below" method="post">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Tax Option</h4>
        </div>
        <div class="modal-body">
        	            
            <div class="col-sm-12">
                <label for="tax_name" class="control-label">Tax Option Details</label>         
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control" name="tax_name" id="tax_name" placeholder="Tax Description">
            </div>
            
             <div class="col-sm-6 form-group">
                <input type="text" class="form-control" name="percentage" id="percentage" placeholder="Tax Percentage">
            </div>
            
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="modal-buttons">
       <button  data-related-section="taxes" data-modal-body="Tax added successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
    </div> 
    </form>          
</div>