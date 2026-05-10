<?php
$settings_data = array_values($request['data'])[0];
//var_dump($settings_data);

?>

<!-- content area -->
    <form method="put" action="<?php echo base_api_url('templates'); ?>"  autocomplete="off"> 
    <div class="container-fluid">
       <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="<?php echo base_url('settings/organization'); ?>">Business Setup</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/taxes'); ?>">Taxes</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/theme'); ?>">Theme and Logo</a></li>
            <li role="presentation" class="active"><a href="<?php echo base_url('settings/templates'); ?>">Templates</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/users'); ?>">Users</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/roles'); ?>">Roles</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/items'); ?>">Items</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/emails'); ?>">Emails</a></li>
        </ul>
    </div>
    <div class="container-fluid bg-white doc-spaced">
         <div class="col-xs-12">
                <h3>Set Up Your Templates</h3>
         </div>                       
         <div class="form_section">
              <div class="container-fluid">
                  <h4>Invoices</h4>
                  <div class="clearfix grey-border-bottom form-group formSpacer"></div> 
              </div> 
               <div class="form-group col-xs-12 col-sm-6  pull-left-sm clear-both-sm">
                    <label for="invoice_name" class="control-label pull-left-sm">Invoice Name</label>
                    <div class="col-sm-9 pull-right-sm no-gutter-xs">
                        <input value="<?php echo $settings_data['invoice_name']; ?>" type="text" class="form-control" name="invoice_name" id="invoice_name" placeholder="Invoice Name">
                    </div>
              </div>                             
              <div class="form_section">
                 <div class="form-group col-xs-12 col-sm-6   pull-left-sm clear-left-sm">
                      <div class="form-group col-xs-12  no-gutter-xs">
                          <label for="invoice_terms"  class="control-label pull-left-sm">Terms:</label>  
                          <div class="col-sm-9 pull-right-sm no-gutter-xs">                     
                             <textarea name="invoice_terms" id="invoice_terms" rows="6" class="form-control" placeholder="Add terms & conditions or Banking Details"><?php echo $settings_data['invoice_terms']; ?></textarea> 
                          </div>                       
                      </div>
                 </div>
                 <div class="form-group col-xs-12 col-sm-6 pull-right-sm clear-right-sm">
                       <div class="form-group col-xs-12 no-gutter-xs">
                           <label for="invoice_closing_note" class="control-label pull-left-sm">Closing Note:</label> 
                           <div class="col-sm-9 pull-right-sm no-gutter-xs">                      
                                <textarea name="invoice_closing_note" id="invoice_closing_note" rows="6" class="form-control" placeholder="Add notes visible to client"><?php echo $settings_data['invoice_closing_note']; ?></textarea>  
                           </div>                      
                      </div>
                 </div>
             </div>
              <div class="container-fluid">
                  <div class="clearfix"></div>
                  <h4>Estimates</h4>
                  <div class="clearfix grey-border-bottom form-group formSpacer"></div> 
              </div>
               <div class="form-group col-xs-12 col-sm-6  pull-left-sm clear-both-sm">
                    <label for="estimate_name" class="control-label pull-left-sm">Estimate Name</label>
                    <div class="col-sm-9 pull-right-sm no-gutter-xs">
                        <input value="<?php echo $settings_data['estimate_name']; ?>" type="text" class="form-control" name="estimate_name" id="estimate_name" placeholder="Estimate Name">
                    </div>
              </div>                             
              <div class="form_section">
                 <div class="form-group col-xs-12 col-sm-6   pull-left-sm clear-left-sm">
                      <div class="form-group col-xs-12  no-gutter-xs">
                          <label for="estimate_terms"  class="control-label pull-left-sm">Terms:</label>  
                          <div class="col-sm-9 pull-right-sm no-gutter-xs">                     
                             <textarea rows="6" name="estimate_terms" id="estimate_terms" class="form-control" placeholder="Add terms & conditions or Banking Details"><?php echo $settings_data['estimate_terms']; ?></textarea> 
                          </div>                       
                      </div>
                 </div>
                 <div class="form-group col-xs-12 col-sm-6 pull-right-sm clear-right-sm">
                       <div class="form-group col-xs-12 no-gutter-xs">
                           <label for="estimate_closing_note" class="control-label pull-left-sm">Closing Note:</label> 
                           <div class="col-sm-9 pull-right-sm no-gutter-xs">                      
                                <textarea name="estimate_closing_note" id="estimate_closing_note" rows="6" class="form-control" placeholder="Add notes visible to client"><?php echo $settings_data['estimate_closing_note']; ?></textarea>  
                           </div>                      
                      </div>
                 </div>
             </div> 
               <div class="container-fluid">
                  <div class="clearfix"></div>
                  <h4>Credit Notes</h4>
                  <div class="clearfix grey-border-bottom form-group formSpacer"></div> 
              </div>
               <div class="form-group col-xs-12 col-sm-6  pull-left-sm clear-both-sm">
                    <label for="credit_note_name" class="control-label pull-left-sm">Credit Note Name</label>
                    <div class="col-sm-9 pull-right-sm no-gutter-xs">
                        <input value="<?php echo $settings_data['credit_note_name']; ?>" type="text" class="form-control" name="credit_note_name" id="credit_note_name" placeholder="Estimate Name">
                    </div>
              </div>                             
              <div class="form_section">
                 <div class="form-group col-xs-12 col-sm-6   pull-left-sm clear-left-sm">
                      <div class="form-group col-xs-12  no-gutter-xs">
                          <label for="credit_note_terms"  class="control-label pull-left-sm">Terms:</label>  
                          <div class="col-sm-9 pull-right-sm no-gutter-xs">                     
                             <textarea rows="6" name="credit_note_terms" id="credit_note_terms" class="form-control" placeholder="Add terms & conditions or Banking Details"><?php echo $settings_data['credit_note_terms']; ?></textarea> 
                          </div>                       
                      </div>
                 </div>
                 <div class="form-group col-xs-12 col-sm-6 pull-right-sm clear-right-sm">
                       <div class="form-group col-xs-12 no-gutter-xs">
                           <label for="credit_note_closing_note" class="control-label pull-left-sm">Closing Note:</label> 
                           <div class="col-sm-9 pull-right-sm no-gutter-xs">                      
                                <textarea name="credit_note_closing_note" id="estimate_closing_note" rows="6" class="form-control" placeholder="Add notes visible to client"><?php echo $settings_data['credit_note_closing_note']; ?></textarea>  
                           </div>                      
                      </div>
                 </div>
             </div>
                                 
         </div>
        <div class="clearfix formSpacer"></div>
   
       <div class="form_section container-fluid" style="text-align:right;">
             <button data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-default saveButton padded saveFormData">Save</button>
        	 <button data-redirect-url="<?php echo base_url('settings/users'); ?>" data-redirect-without-id="true" data-modal-url="<?php echo base_url('modal/notice'); ?>"  data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-success saveButton saveFormData">Save & Continue</button>
        </div>                 
    </div>
    
   
     </form> 
    
<!-- END content area -->   