<?php
	$user_data = array_values($request['data'])[0];
?>

<div class="modal-dialog" role="document">
	<form data-redirect-without-id="true" action="<?php echo base_api_url('users/'.$user_data['id']); ?>" data-validation-placement="below" method="put">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Details</h4>
        </div>
        <div class="modal-body">
        	<div class="col-sm-12 form-group">
                <label for="First_name" class="control-label">User Details</label>          
            </div>          
            <div class="col-sm-6 form-group">
                <input value="<?php echo $user_data['first_name']; ?>" name="first_name" id="first_name" placeholder="First Name" class="form-control">           
            </div>
            <div class="col-sm-6 form-group">
                <input value="<?php echo $user_data['last_name']; ?>" name="last_name" id="last_name" placeholder="Last Name" class="form-control">           
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-sm-6 form-group">
                <input value="<?php echo $user_data['email']; ?>" name="email" id="email" placeholder="Email Address" class="form-control">           
            </div>
            <div class="col-sm-6 form-group">
                <input value="<?php echo $user_data['contact_number']; ?>" name="contact_number" id="contact_number" placeholder="Contact Number" class="form-control">           
            </div>
            
            
            <div class="clearfix"></div>  
            
            <div class="col-sm-12 form-group">
                <label for="password" class="control-label">Change Password</label>          
            </div>          
            <div class="col-sm-6 form-group">
                <input type="password" name="password" id="password" placeholder="Password" class="form-control">           
            </div>
            <div class="col-sm-6 form-group">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter Password" class="form-control">        
            </div> 
            
             <div class="clearfix"></div>      
                     
            <div class="col-sm-12 form-group">
                <label for="user_role_id" class="control-label">User Security Role</label>                    
            </div>        
             <div class="col-sm-12 form-group">
                 <select id="user_role_id" name="user_role_id" class="selectpicker full-width">
                 <option disabled="disabled" value="0">Choose a user role</option>
                 <?php
				 
					foreach($request['piggyback']['roles'] as $role_data){
						if($user_data['user_role_id'] == $role_data['id']){
							echo '<option selected="selected" value="'.$role_data['id'].'">'.$role_data['role_name'].'</option>';
							$role_found = true;
						}else{
							echo '<option value="'.$role_data['id'].'">'.$role_data['role_name'].'</option>';
						}
					}
					
				?>   
                </select>
            </div>
            
            <div class="clearfix"></div>  
        </div>
    </div>
    <div class="modal-buttons">
       <button data-redirect-url="<?php echo base_url('settings/users'); ?>" data-related-section="users" data-modal-body="User Updated successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
    </div> 
    </form>          
</div>