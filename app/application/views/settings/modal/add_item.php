<div class="modal-dialog" role="document">
	<form  data-redirect-without-id="true" action="<?php echo base_api_url('items'); ?>" data-validation-placement="below" method="post" autocomplete="off">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add New Item</h4>
        </div>
        <div class="modal-body">
        	<div class="col-sm-12 form-group">
                <label for="First_name" class="control-label">Item Details</label>          
            </div>          
            <div class="col-sm-6 form-group">
                <input name="item_name" id="item_name" placeholder="Item Name" class="form-control">           
            </div>
			
            <div class="col-sm-6 form-group">
                <input name="rate" id="rate" type="number" placeholder="Rate" class="form-control">           
            </div>
			
			
            <div class="clearfix"></div>
			
            <div class="col-sm-12 form-group">
                <textarea name="description" id="description" placeholder="Description" class="form-control"></textarea>
            </div>

            
            <div class="clearfix"></div>  
        </div>
    </div>
    <div class="modal-buttons">
       <button data-redirect-url="<?php echo base_url('settings/items'); ?>" data-related-section="items" data-modal-body="Item added successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Add Item</button> or <a href="#" data-dismiss="modal">Cancel</a>
    </div> 
    </form>          
</div>