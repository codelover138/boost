<?php
	if(is_array($request['data']))
		$item_data = array_values($request['data'])[0];
	else
		$item_data = $request['data'];
?>

<div class="modal-dialog" role="document">
    <form data-redirect-without-id="true" action="<?php echo base_api_url('items/'.$id); ?>" method="delete">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            
            <div class="modal-body">
            	<div class="col-sm-12 form-group">
                	<strong>Are you sure you want to delete the following item?</strong>
                </div>           		
                <div class="clearfix"></div>
                <div class="col-sm-12 form-group">
                	<ul>						
						<?php 							
								echo '<li>'.ucwords($item_data['item_name']).' (Rate: '.$item_data['rate'].') </li>';
												
						?>                        
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="modal-buttons">
            <button data-redirect-url="<?php echo base_url('settings/items'); ?>" data-close-delay-seconds="1" data-modal-heading="Deleted" data-modal-body="The item was successfully deleted" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Delete</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
    </form>        
</div>
