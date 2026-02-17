<div class="modal-dialog" role="document">
	<form <?php if(isset($form_dataset)){ echo $form_dataset;} ?> action="<?php echo base_api_url('items'); ?>" data-validation-placement="below" method="post">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Item</h4>
        </div>
        <div class="modal-body">            
            <div class="col-sm-12">
                <label for="discount" class="control-label">Item Details</label>                    
            </div>        
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Item Name">
            </div>
             <div class="col-sm-6 form-group">
                <input type="text" class="form-control" name="description" id="description" placeholder="Item Description">
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-sm-12">
                <label for="discount" class="control-label">Item Price / Rate</label>                    
            </div>        
            <div class="col-sm-12 form-group">
                <input type="text" class="form-control" name="rate" id="rate" value="0.00">
            </div>
            
            <div class="clearfix"></div>
            
        </div>
    </div>
    <div class="modal-buttons">
       <button data-related-section="items" data-modal-body="Item added successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
    </div> 
    </form>          
</div>