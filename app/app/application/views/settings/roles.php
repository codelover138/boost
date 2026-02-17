 <?php
 
 $roles = $request['piggyback']['roles'];
 $permissions = $request['piggyback']['permissions'];
 $role_permissions = $request['data'];
 // var_dump($permissions);
 ?>
 
 <!-- content area -->
    <form method="post" action="<?php echo base_api_url('role_permissions'); ?>"  autocomplete="off"> 
    <div class="container-fluid">
       <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="<?php echo base_url('settings/organization'); ?>">Business Setup</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/taxes'); ?>">Taxes</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/theme'); ?>">Theme and Logo</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/templates'); ?>">Templates</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/users'); ?>">Users</a></li>
            <li role="presentation" class="active"><a href="<?php echo base_url('settings/roles'); ?>">Roles</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/items'); ?>">Items</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/emails'); ?>">Emails</a></li>
        </ul>
    </div>
    <div class="container-fluid bg-white doc-spaced">
         <div class="col-xs-12">
                <h3>Security Roles</h3>
         </div>                       
         <div class="form_section">
         
         	<?php 
				foreach($roles as $role_data){
					$role_id = $role_data['id'];					
			?>
               <div class="container-fluid">
                  <h4><?php echo $role_data['role_name']; ?></h4>
                  <div class="clearfix grey-border-bottom form-group formSpacer"></div> 
               </div> 
               <div class="form-group container-fluid">
               		<?php 
						foreach($permissions['default'] as $permission_data){
							$current_role_permissions = @$role_permissions[$role_id]['permissions'];
							if(@array_key_exists(@$permission_data['id'] ,@$current_role_permissions)){
								$checked_html = 'checked="checked"';
							}else{
								$checked_html = '';
							}
					?>
                        <div class="security-role-item">
                            <input <?php echo $checked_html; ?> name="role_data['<?php echo $role_data['id']; ?>']['<?php echo $permission_data['id']; ?>']" id="<?php echo $role_data['role_name'].'_'.$permission_data['id']; ?>" type="checkbox" value="yes">
                            <label for="<?php echo $role_data['role_name'].'_'.$permission_data['id']; ?>" class="control-label"><?php echo $permission_data['permission']; ?></label>                                   
                        </div>
                    <?php 
						}
					?>                                     
              </div> 
              
              <div class="form-group container-fluid">
					<?php 
                        foreach($permissions['alternative'] as $permission_data){
                            $current_role_permissions = @$role_permissions[$role_id]['permissions'];
                            if(@array_key_exists(@$permission_data['id'] ,@$current_role_permissions)){
                                $checked_html = 'checked="checked"';
                            }else{
                                $checked_html = '';
                            }
                    ?>
                            <div class="col-xs-12 form-group large-settings-item">
                                <input <?php echo $checked_html; ?> name="role_data['<?php echo $role_data['id']; ?>']['<?php echo $permission_data['id']; ?>']" id="<?php echo $role_data['role_name'].'_'.$permission_data['id']; ?>" type="checkbox" value="yes">
                                 <label for="<?php echo $role_data['role_name'].'_'.$permission_data['id']; ?>" class="control-label"><?php echo $permission_data['permission']; ?></label>
                                <p><?php echo $permission_data['description']; ?></p>
                            </div>                          
                    <?php 
						}
				   ?>
              </div>  
              <?php 
				}
			   ?>
              
              
              
                                 
         </div>
        <div class="clearfix formSpacer"></div>
   
        <div class="form_section container-fluid" style="text-align:right;">
             <button data-redirect-url="<?php echo base_url('settings/roles'); ?>" data-redirect-without-id="true" data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-default saveButton padded saveFormData">Save</button>
        	 <button data-redirect-url="<?php echo base_url('settings/items'); ?>" data-redirect-without-id="true" data-modal-url="<?php echo base_url('modal/notice'); ?>"  data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-success saveButton saveFormData">Save & Continue</button>
        </div>                  
    </div>
    
   
     </form> 
    
<!-- END content area -->  