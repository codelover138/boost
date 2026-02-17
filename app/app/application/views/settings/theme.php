<?php
$settings_data = array_values($request['data'])[0];
//var_dump($request['piggyback']['timezones']);
//var_dump($settings_data);
//var_dump($request['piggyback']['themes']);

?>

<!-- content area -->
    <form method="put" action="<?php echo base_api_url('theme_settings'); ?>" data-redirect-without-id="true" autocomplete="off"> 
    <div class="container-fluid">
       <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="<?php echo base_url('settings/organization'); ?>">Business Setup</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/taxes'); ?>">Taxes</a></li>
            <li role="presentation" class="active"><a href="<?php echo base_url('settings/theme'); ?>">Theme and Logo</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/templates'); ?>">Templates</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/users'); ?>">Users</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/roles'); ?>">Roles</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/items'); ?>">Items</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/emails'); ?>">Emails</a></li>
        </ul>
    </div>
    <div class="container-fluid bg-white doc-spaced">
         <div class="col-xs-12">
                <h3>Theme and Logo</h3>
         </div>                       
         <div class="form_section">
              <div class="container-fluid">
                  <h4>Logo</h4>
                  <div class="clearfix grey-border-bottom form-group formSpacer"></div> 
              </div>                             
              <div class="form-group col-xs-12 logo_preview_container" <?php echo ($settings_data['image_string'] ? '' : 'style="display:none;"'); ?>>
                 <?php if($settings_data['image_string']){  ?>
                	 <img src="<?php echo $settings_data['image_string']; ?>" id="preview-logo" class="preview-logo">
                	 <input name="image_string" id="logo_image_data" type="hidden" value="<?php echo $settings_data['logo_base64']; ?>" />
                 <?php }  ?>
                 <div class="clearfix formSpacer"></div> 
              </div> 
              <div class="form-group col-xs-12 ">
                 <div class="upload_button_container">
                    <a class='btn btn-default' href='javascript:;'>Upload Logo &nbsp;+</a>
                    <input class="upload_button" id="logoFile" type="file" name="file_source" size="40" >
                    <span class='label label-info' id="upload-file-info"></span>
                </div>
                <div class="remove_logo_button_container" <?php echo ($settings_data['image_string'] ? '' : 'style="display:none;"'); ?>>
                	<a class="remove_logo_button" href="#" >Remove Logo</a>
                </div>
              </div> 
              <div class="container-fluid">
                  <div class="clearfix formSpacer"></div>
                  <h4>Theme</h4>
                  <div class="clearfix grey-border-bottom form-group formSpacer"></div> 
              </div> 
              <div class="row">
              <?php
			  	foreach($request['piggyback']['themes'] as $theme_key => $theme_data){
			  ?>
              	  <div class="col-sm-3 form-group formSpacer theme_instance_container">
                      <div data-theme-id="<?php echo $theme_data['id']; ?>" class="theme_instance <?php echo ($theme_data['id'] == $settings_data['theme_id'] ? 'active' : ''); ?>" style="background-image:url('<?php echo $theme_data['theme_image'] ?>')"></div>
                      <h4><?php echo $theme_data['theme_name']; ?></h4>
                  </div>
              <?php		
				}			  
			  ?> 
              	<input id="theme_id" name="theme_id" type="hidden" value="<?php echo $settings_data['theme_id']; ?>" />             
              </div>
                                 
         </div>
        <div class="clearfix formSpacer"></div>
   
        <div class="form_section container-fluid" style="text-align:right;">
             <button data-redirect-url="<?php echo base_url('settings/theme'); ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-default saveButton padded saveFormData">Save</button>
        	 <button data-redirect-url="<?php echo base_url('settings/templates'); ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>"  data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-success saveButton saveFormData">Save & Continue</button>
        </div>                  
    </div>
    
   
     </form> 
    
<!-- END content area -->  