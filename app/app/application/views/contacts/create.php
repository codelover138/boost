<?php
//var_dump($request['data']);
?>


 <!-- content area -->
<form method="post" action="<?php echo base_api_url('contacts/'); ?>"  autocomplete="off">

	<div class="container-fluid bg-white doc-spaced">
           <div class="col-xs-12">
                <h3>Contact Details</h3>
           </div>  
                
          <div class="form_section">
          
             <div class="col-sm-12 form-group">
            	<?php
					//var_dump($request['piggyback']);
					foreach($request['piggyback']['contact_types'] as $contact_info){
						echo '<label class="radio-inline">';
						if($contact_info['type'] == @$type){
							echo '<input checked="checked" type="radio" name="contact_type_id" value="'.$contact_info['id'].'"> '.ucwords($contact_info['type']);
						}else{
							echo '<input type="radio" name="contact_type_id" value="'.$contact_info['id'].'"> '.ucwords($contact_info['type']);
						}
						echo '</label>';
					}
				?> 
                <div class="clearfix form-group formSpacer"></div>                    
            </div>
               
            <div class="container-fluid">
                <h4>Business Details</h4>
                <div class="clearfix grey-border-bottom form-group formSpacer"></div> 
            </div>
                              
              <div class="form-group col-xs-12 col-sm-6">
                <label for="organisation" class="control-label pull-left-sm clear-left-sm">Company Name</label>
                <div class="col-sm-9 pull-right-sm no-gutter-xs">
                     <input type="text" class="form-control" name="organisation" id="organisation" placeholder="Company Name">
                </div>
              </div>
              
              <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="vat_number" class="control-label pull-left-sm">Vat Number</label>
                <div class="col-sm-9  pull-right-sm no-gutter-xs">
                    <input type="text" class="form-control" name="vat_number" id="vat_number" placeholder="Vat Number">
                </div>
              </div>  
              
               <div class="form-group col-xs-12 col-sm-6">
                <label for="industry_id" class="control-label pull-left-sm clear-left-sm">Industry</label>
                <div class="col-sm-9 pull-right-sm no-gutter-xs">
                    <select id="industry_id" name="industry_id" class="selectpicker full-width">
                     <?php
                        foreach($request['piggyback']['industries'] as $industry_data){
                              echo '<option value="'.$industry_data['id'].'">'.$industry_data['industry_name'].'</option>';                          
                        }
                    ?>   
                    </select>
                </div>
              </div>
              
              <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="company_size_id" class="control-label pull-left-sm">Company Size</label>
                <div class="col-sm-9  pull-right-sm no-gutter-xs">
                     <select id="company_size_id" name="company_size_id" class="selectpicker full-width">
                     <option value="">Choose an option</option>
                     <?php
                        foreach($request['piggyback']['company_sizes'] as $company_sizes_data){
                             echo '<option value="'.$company_sizes_data['id'].'">'.$company_sizes_data['size'].' Employees</option>';
                        }
                    ?>   
                    </select>
                </div>
              </div>                   
                    
         </div> 
         
         <div class="clearfix formSpacer"></div>
         
         <div class="form_section">
            <div class="container-fluid">
                <div class="clearfix"></div>
                <h4>Contact Person Details</h4>
                <div class="clearfix grey-border-bottom form-group formSpacer"></div> 
            </div>
            <div class="form-group col-xs-12 col-sm-6">
                <label for="first_name" class="control-label pull-left-sm clear-left-sm">First Name</label>
                <div class="col-sm-9 pull-right-sm no-gutter-xs">
                     <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                </div>
            </div>
              
            <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="last_name" class="control-label pull-left-sm">Last Name</label>
                <div class="col-sm-9  pull-right-sm no-gutter-xs">
                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                </div>
            </div>  
            
            <div class="form-group col-xs-12 col-sm-6">
                <label for="email" class="control-label pull-left-sm clear-left-sm">Email Address</label>
                <div class="col-sm-9 pull-right-sm no-gutter-xs">
                     <input type="text" class="form-control" name="email" id="email" placeholder="Email Address">
                </div>
            </div>
              
            <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="land_line" class="control-label pull-left-sm">Land Line</label>
                <div class="col-sm-9  pull-right-sm no-gutter-xs">
                    <input type="text" class="form-control" name="land_line" id="land_line" placeholder="Last Name" >
                </div>
            </div>
            
             <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
                <label for="mobile" class="control-label pull-left-sm">Mobile</label>
                <div class="col-sm-9  pull-right-sm no-gutter-xs">
                     <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile">
                </div>
            </div>
             
         </div>
          
         <div class="clearfix formSpacer"></div>
         
         <div class="form_section">
            <div class="container-fluid">
                <div class="clearfix"></div>
                <h4>Physical Details</h4>
                <div class="clearfix grey-border-bottom  formSpacer"></div> 
            </div>
            <div class="form-group col-xs-12 col-sm-6   pull-left-sm clear-left-sm">
                  <div class="form-group col-xs-12 no-gutter-xs">
                      <label for="address"  class="control-label pull-left-sm">Business Address:</label>  
                      <div class="col-sm-9 pull-right-sm no-gutter-xs">                     
                         <textarea rows="6" name="address" id="address" class="form-control" placeholder="Business Address"></textarea> 
                      </div>                       
                  </div>
             </div>      
         </div>
         
        <div class="clearfix"></div>
        <div class="form_section container-fluid" style="text-align:right;">
             <button data-redirect-url="<?php echo base_url('contacts'); ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-default saveButton saveFormData">Save</button>      
        </div>
       
    </div>

</form> 
    
<!-- END content area -->