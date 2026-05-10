<div class="modal-dialog" role="document">
	<form  data-redirect-without-id="true" action="<?php echo base_api_url('users'); ?>" data-validation-placement="below" method="post" autocomplete="off">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add New User</h4>
        </div>
        <div class="modal-body">
        	<div class="col-sm-12 form-group">
                <label for="First_name" class="control-label">User Details</label>          
            </div>          
            <div class="col-sm-6 form-group">
                <input name="first_name" id="first_name" placeholder="First Name" class="form-control">           
            </div>
            <div class="col-sm-6 form-group">
                <input name="last_name" id="last_name" placeholder="Last Name" class="form-control">           
            </div>
            
            <div class="clearfix"></div>
             
            
            <div class="col-sm-6 form-group">
                <input name="email" id="email" placeholder="Email Address" class="form-control">           
            </div>
            <div class="col-sm-6 form-group">
                <input type="text" value="" name="contact_number" id="contact_number" placeholder="Contact Number" class="form-control">           
            </div>
            
            
            <div class="clearfix"></div>   
            <div class="col-sm-12 form-group">
                <label for="password" class="control-label">User Password</label>          
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
                 <option selected="selected" disabled="disabled" value="0">Choose a user role</option>
                 <?php
					foreach($request['data'] as $role_data){
						echo '<option value="'.$role_data['id'].'">'.$role_data['role_name'].'</option>';
					}
				?>   
                </select>
            </div>
            
            <div class="clearfix"></div>  
        </div>
    </div>
    <div class="modal-buttons">
       <button data-redirect-url="<?php echo base_url('settings/users'); ?>" data-related-section="users" data-modal-body="User added successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Add User</button> or <a href="#" data-dismiss="modal">Cancel</a>
    </div> 
    </form>          
</div>