 <!-- content area -->
    <form action="testForm.php"  autocomplete="off"> 
    <div class="container-fluid">
       <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="<?php echo base_url('settings/organization'); ?>">Business Setup</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/taxes'); ?>">Taxes</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/theme'); ?>">Theme and Logo</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/templates'); ?>">Templates</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/users'); ?>">Users</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/roles'); ?>">Roles</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/items'); ?>">Items</a></li>
            <li role="presentation" class="active"><a href="<?php echo base_url('settings/emails'); ?>">Emails</a></li>
        </ul>
    </div>
    <div class="container-fluid bg-white doc-spaced">
       <div class="col-xs-12">
            <h3>Emails</h3>
       </div>
       
      <div class="form_section">
          <div class="form-group">
              <div class="container-fluid">
                  <h4>Signatures</h4>
                  <div class="clearfix grey-border-bottom form-group"></div> 
              </div> 
              <div class="container-fluid">
                    <div class="col-xs-12 form-group large-settings-item">
                        <a href="<?php echo base_url('settings/modal/emails/signature'); ?>" class="large-settings-item-link open-modal">Edit</a>
                        <label for="Send_Invoices" class="control-label">Email Signature</label>
                        <p>This will show at the bottom of all emails sent to clients</p>                               
                    </div>                          
              </div> 
          </div>
          <div class="form-group">
              <div class="container-fluid">
                  <h4>Templates</h4>
                  <div class="clearfix grey-border-bottom form-group"></div> 
              </div> 
              <div class="container-fluid">                               
                    <div class="col-xs-12 form-group large-settings-item">
                        <a href="<?php echo base_url('settings/modal/emails/invoice'); ?>" class="large-settings-item-link open-modal">Edit</a>
                        <label class="control-label">Send Invoice Message</label>
                        <p>This will be a message sent to clients when you send an invoice.</p>                               
                    </div> 
                     <div class="col-xs-12 form-group large-settings-item">
                        <a href="<?php echo base_url('settings/modal/emails/estimate'); ?>" class="large-settings-item-link open-modal">Edit</a>
                        <label class="control-label">Send Estimate Message</label>
                        <p>This will be a message sent to clients when you send an estimate.</p>                               
                    </div> 
                    <div class="col-xs-12 form-group large-settings-item">
                        <a href="<?php echo base_url('settings/modal/emails/credit_note'); ?>" class="large-settings-item-link open-modal">Edit</a>
                        <label class="control-label">Send Credit Note Message</label>
                        <p>This will be a message sent to clients when you send a credit note</p>                               
                    </div>
                     <div class="col-xs-12 form-group large-settings-item">
                        <a href="<?php echo base_url('settings/modal/emails/payment'); ?>" class="large-settings-item-link open-modal">Edit</a>
                        <label class="control-label">Send Payment Message</label>
                        <p>This will be a message sent to clients when you send a payment notification</p>                               
                    </div>  
                     <div class="col-xs-12 form-group large-settings-item">
                        <a href="<?php echo base_url('settings/modal/emails/statement'); ?>" class="large-settings-item-link open-modal">Edit</a>
                        <label class="control-label">Send Statement Message</label>
                        <p>This will be a message sent to clients when you send a statement</p>                               
                    </div>                         
              </div> 
          </div>
     </div> 
     
     <div class="clearfix"></div>

       
    </div>
    
   
     </form> 
    
<!-- END content area -->  