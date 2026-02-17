<?php
$settings_data = array_values($request['data'])[0];
//var_dump($request['piggyback']['timezones']);
//var_dump($settings_data);

?>


 <!-- content area -->
    <form method="put" action="<?php echo base_api_url('organisations'); ?>"  autocomplete="off"> 
        <div class="container-fluid">
           <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="<?php echo base_url('settings/organization'); ?>">Business Setup</a></li>
                <li role="presentation"><a href="<?php echo base_url('settings/taxes'); ?>">Taxes</a></li>
                <li role="presentation"><a href="<?php echo base_url('settings/theme'); ?>">Theme and Logo</a></li>
                <li role="presentation"><a href="<?php echo base_url('settings/templates'); ?>">Templates</a></li>
                <li role="presentation"><a href="<?php echo base_url('settings/users'); ?>">Users</a></li>
                <li role="presentation"><a href="<?php echo base_url('settings/roles'); ?>">Roles</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/items'); ?>">Items</a></li>
                <li role="presentation"><a href="<?php echo base_url('settings/emails'); ?>">Emails</a></li>
            </ul>
        </div>
        <div class="container-fluid bg-white doc-spaced">
           <div class="col-xs-12">
                <h3>Set Up Your Business</h3>
           </div>
           
          <div class="form_section">
              <div class="container-fluid">
                  <h4>Details</h4>
                  <div class="clearfix grey-border-bottom form-group"></div> 
              </div> 
              
              <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
                <label for="company_name" class="control-label">Company Name</label>
                <div class="col-sm-8  pull-right-sm no-gutter-xs">
                    <input type="text" value="<?php echo $settings_data['company_name']; ?>" name="company_name" id="company_name" placeholder="Company Name" class="form-control">
                </div>
              </div>
              
              <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="industry_id" class="control-label pull-left-sm clear-left-sm">Industry</label>
                <div class="col-sm-8  pull-right-sm no-gutter-xs">
                    <select id="industry_id" name="industry_id" class="selectpicker full-width">
                        <?php
							
							$industry_found = false;
							echo '<option disabled="disabled" selected="selected" value="0">Choose an Industry</option>';
							foreach($piggyback['industries'] as $industry_key => $industry_data){
								if($industry_data['id'] == $settings_data['industry_id']){
									echo '<option selected="selected" value="'.$industry_data['id'].'">'.$industry_data['industry_name'].'</option>';
									$industry_found = true;
								}else{
									echo '<option value="'.$industry_data['id'].'">'.$industry_data['industry_name'].'</option>';
								}
							}
							/*
							if($industry_found === false){
								echo '<option disabled="disabled" selected="selected" value="0">Choose an Industry</option>';
							}else{
								echo '<option disabled="disabled" value="0">Choose an Industry</option>';
							}*/
						?>
                    </select>
                </div> 
               </div>
               
               <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
                <label for="vat_number" class="control-label">VAT Number</label>
                <div class="col-sm-8  pull-right-sm no-gutter-xs">
                    <input type="text" value="<?php echo $settings_data['vat_number']; ?>" name="vat_number" id="vat_number" placeholder="VAT Number" class="form-control">
                </div>
              </div>
              
              <div class="container-fluid">
                  <div class="clearfix formSpacer"></div>
                  <h4>Location</h4>
                  <div class="clearfix grey-border-bottom form-group"></div> 
              </div>
              
               <div class="col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
               
                  <div class="clearfix form-group">
                    <label for="address_line_1" class="control-label">Address line 1</label>
                    <div class="col-sm-8  pull-right-sm no-gutter-xs">
                        <input type="text" value="<?php echo $settings_data['address_line_1']; ?>" name="address_line_1" id="address_line_1" placeholder="Address line 1" class="form-control">
                    </div>
                  </div>
                  
                  <div class="clearfix form-group">
                    <label for="address_line_2" class="control-label">Address line 2</label>
                    <div class="col-sm-8  pull-right-sm no-gutter-xs">
                        <input type="text" value="<?php echo $settings_data['address_line_2']; ?>" name="address_line_2" id="address_line_2" placeholder="Address line 2" class="form-control">
                    </div>
                 </div>
                 
                 <div class="clearfix form-group">
                    <label for="city" class="control-label pull-left-sm">City</label>
                    <div class="col-sm-8 pull-right-sm no-gutter-xs">
                        <input type="text" value="<?php echo $settings_data['city']; ?>" name="city" id="city" placeholder="City" class="form-control">
                    </div>
                  </div>
                  
              </div>
              
              
              
               <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="region_state" class="control-label">Region/State</label>
                <div class="col-sm-8  pull-right-sm no-gutter-xs">
                    <input type="text" value="<?php echo $settings_data['region_state']; ?>" name="region_state" id="region_state" placeholder="Region / State / Province" class="form-control">
                </div>
              </div>
                            
              <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="country_id" class="control-label pull-left-sm clear-left-sm">Country</label>
                <div class="col-sm-8 pull-right-sm no-gutter-xs">
                    <select id="country_id" name="country_id" class="selectpicker full-width">
                    	<?php
							
							$country_found = false;
							echo '<option disabled="disabled" selected="selected" value="0">Choose a Country</option>';
							foreach($piggyback['countries'] as $country_key => $country_data){
								if($country_data['id'] == $settings_data['country_id']){
									echo '<option selected="selected" value="'.$country_data['id'].'">'.$country_data['country'].'</option>';
									$country_found = true;
								}else{
									echo '<option value="'.$country_data['id'].'">'.$country_data['country'].'</option>';
								}
							}
							
							/*if($country_found === false){
								echo '<option disabled="disabled" selected="selected" value="0">Choose a Country</option>';
							}else{
								echo '<option disabled="disabled" value="0">Choose a Country</option>';
							}*/
						?>
                    </select>
                </div>
              </div>
              
              <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="zip" class="control-label pull-left-sm">Postal/Zip code</label>
                <div class="col-sm-8 pull-right-sm no-gutter-xs">
                    <input type="text" value="<?php echo $settings_data['zip']; ?>" name="zip" id="zip" placeholder="Postal/Zip code" class="form-control">
                </div>
             </div> 
              
             
             
              <div class="container-fluid">
                    <div class="clearfix formSpacer"></div>
                    <h4>Contact Information</h4>
                    <div class="clearfix grey-border-bottom form-group"></div> 
              </div> 
              
               <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
                <label for="email" class="control-label">Primary Email</label>
                <div class="col-sm-8  pull-right-sm no-gutter-xs">
                    <input type="text" value="<?php echo $settings_data['email']; ?>" name="email" id="email" placeholder="Primary Email Address" class="form-control">
                </div>
              </div>
              
              <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="telephone" class="control-label pull-left-sm">Business Tel</label>
                <div class="col-sm-8 pull-right-sm no-gutter-xs">
                    <input maxlength="20" type="text" value="<?php echo $settings_data['telephone']; ?>" name="telephone" id="telephone" placeholder="+27 11 555 0000" class="form-control">
                </div>
              </div>
              
               <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
                <label for="mobile" class="control-label">Mobile</label>
                <div class="col-sm-8  pull-right-sm no-gutter-xs">
                    <input maxlength="20" type="text" value="<?php echo $settings_data['mobile']; ?>" name="mobile"  id="mobile" placeholder="+27 82 555 0000" class="form-control">
                </div>
              </div>
              
              <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="fax" class="control-label pull-left-sm">Fax</label>
                <div class="col-sm-8 pull-right-sm no-gutter-xs">
                    <input maxlength="20" type="text" value="<?php echo $settings_data['fax']; ?>" name="fax" id="fax" placeholder="+27 11 555 0000" class="form-control">
                </div>
              </div>
              
              <div class="container-fluid">
                    <div class="clearfix formSpacer"></div>
                    <h4>Time and Currency</h4>
                    <div class="clearfix grey-border-bottom form-group"></div> 
              </div> 
              
              
               <div class="form-group col-xs-12 col-sm-6">
                <label for="currency_id" class="control-label pull-left-sm clear-left-sm">Base Currency</label>
                <div class="col-sm-8 pull-right-sm no-gutter-xs">
                    <select id="currency_id" name="currency_id" class="selectpicker full-width">
                        <option>Choose a Currency</option>
                        <?php
							
							$curreny_found = false;
						
							foreach($piggyback['currencies'] as $currencies_key => $currencies_data){
								if($currencies_data['id'] == $settings_data['currency_id']){
									echo '<option selected="selected" value="'.$currencies_data['id'].'">'.$currencies_data['currency_name'].'</option>';
									$currency_found = true;
								}else{
									echo '<option value="'.$currencies_data['id'].'">'.$currencies_data['currency_name'].'</option>';
								}
							}
							
							if($currencies_found === false){
								echo '<option disabled="disabled" selected="selected" value="0">Choose a Currency</option>';
							}else{
								echo '<option disabled="disabled" value="0">Choose a Currency</option>';
							}
						?>
                    </select>
                </div>
              </div>
              <div class="clearfix"></div>
              <!-- disabled until implimented
               <div class="form-group col-xs-12 col-sm-6">
                <label for="time_zone_id" class="control-label pull-left-sm clear-left-sm">Time Zone</label>
                <div class="col-sm-8 pull-right-sm no-gutter-xs">
                    <select id="time_zone_id" name="time_zone_id" class="selectpicker full-width">
                        <?php
							
							$time_zone_found = false;
							
							foreach($piggyback['timezones'] as $time_zone_key => $time_zone_data){
								if($time_zone_data['id'] == $settings_data['time_zone_id']){
									echo '<option selected="selected" value="'.$time_zone_data['id'].'">'.$time_zone_data['timezone'].'</option>';
									$time_zone_found = true;
								}else{
									echo '<option value="'.$time_zone_data['id'].'">'.$time_zone_data['timezone'].'</option>';
								}
							}
							
							if($time_zone_found === false){
								echo '<option disabled="disabled" selected="selected" value="0">Choose a Time Zone</option>';
							}else{
								echo '<option disabled="disabled" value="0">Choose a Time Zone</option>';
							}
							
						?>                       
                    </select>
                </div>
              </div>
              
               <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="day_light_savings" class="control-label pull-left-sm">daylight saving &nbsp;&nbsp; <input <?php echo ($settings_data['day_light_savings'] == 'yes' ? 'checked="checked"' : ''); ?> name="day_light_savings" type="checkbox" value="yes" /></label>                
              </div>
              -->
               <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
                <label for="account_url" class="control-label pull-left-sm">Account URL</label>
                <div class="col-sm-8  pull-right-sm no-gutter-xs">
                    <input type="text" value="<?php echo $settings_data['account_url']; ?>" name="account_url" id="account_url" placeholder="accountname" class="form-control">
                </div>
              </div>
              
              <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label style="font-weight:normal;" for="account_url" class="control-label pull-left-sm"><span id="acc_url_preview"><?php echo ($settings_data['account_url'] != '' ? $settings_data['account_url'] : 'accountname'); ?></span>.boostaccounting.com<span id="acc_url_preview_status">&#10004;</span></label>
              </div>
              
              
             
         </div> 
         
        <div class="clearfix formSpacer"></div>
         
        <div class="clearfix"></div>
        <div class="form_section container-fluid" style="text-align:right;">
             <button data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-default saveButton padded saveFormData">Save</button>
        	 <button data-redirect-url="<?php echo base_url('settings/taxes'); ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>"  data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-success saveButton saveFormData">Save & Continue</button>
        </div>
           
        </div>
    
   
     </form> 
    
<!-- END content area -->  