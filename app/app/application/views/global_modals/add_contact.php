<div class="modal-dialog" role="document">
	<form data-success-function="updateContactsList(ids,'<?php echo $input; ?>')" action="<?php echo base_api_url('contacts'); ?>" data-validation-placement="below" method="post">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Contact</h4>
        </div>
        <div class="modal-body">
        
        <?php
			###################### DISABLED ######################
			// disabled until suppliers invoice is added
			//to restore remove the hidden field above and remove the false if statement
			//if(false){ 
		?>
        	<div class="col-sm-12 form-group">
            	<?php									
					foreach($request['data'] as $contact_info){
						echo '<label class="radio-inline">';
						if($contact_info['type'] == @$type){
							echo '<input checked="checked" type="radio" name="contact_type_id" value="'.$contact_info['id'].'"> '.ucwords($contact_info['type']);
						}else{
							echo '<input type="radio" name="contact_type_id" value="'.$contact_info['id'].'"> '.ucwords($contact_info['type']);
						}
						echo '</label>';
					}
					
				?>                    
            </div>  
            <div class="col-sm-12 form-group">
           		 <div class="clearfix grey-border-bottom padded-top"></div> 
            </div>
            <?php
			######## END false if statement
			//}
			?>
            
            <div class="col-sm-12 form-group">
                <label for="organisation" class="control-label">Business Details</label>
                <input name="organisation" class="form-control" id="organisation" placeholder="Organisation Name">           
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-sm-4 form-group">
                <input type="text" class="form-control" name="vat_number" id="vat_number" placeholder="Company Vat Number">
            </div>
            
             <div class="col-sm-4 form-group">
                 <select id="industry_id" name="industry_id" class="selectpicker full-width">                 	
					 <?php
                        foreach($request['piggyback']['industries'] as $industry_data){
                            echo '<option value="'.$industry_data['id'].'">'.$industry_data['industry_name'].'</option>';
                        }
                     ?>   
                </select>
            </div>
             <div class="col-sm-4 form-group">
                 <select id="company_size_id" name="company_size_id" class="selectpicker full-width">
                    <option value="0" selected="selected">Company Size</option>
                     <?php
						foreach($request['piggyback']['company_sizes'] as $company_size_data){
							echo '<option value="'.$company_size_data['id'].'">'.$company_size_data['size'].'</option>';
						}
					 ?> 
                </select>
            </div>
            
            <div class="clearfix"></div>   
                     
            <div class="col-sm-12">
                <label for="discount" class="control-label">Contact Person Details</label>                    
            </div>        
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
            </div>
            <div class="col-sm-6 form-group">
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
            </div>
            
            <div class="clearfix"></div>  
                      
            <div class="col-sm-12 form-group">                       
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address">           
            </div>
                   
             <div class="col-sm-6 form-group">
                <input type="text" class="form-control" name="land_line" id="land_line" placeholder="Land Line">
            </div>
             <div class="col-sm-6 form-group">
                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile">
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-sm-12 form-group">
                <label for="address" class="control-label">Address</label>
                <textarea name="address" id="address" rows="6" class="form-control" placeholder="Business Address"></textarea>                 
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="modal-buttons">
       <button  data-related-section="contacts" data-modal-body="Contact added successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
    </div> 
    </form>          
</div>