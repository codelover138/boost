<?php
	$email_data = array_values($request['data'])[0];
?>

<div class="modal-dialog" role="document">
	<form action="<?php echo base_api_url('email_settings'); ?>" method="put">
        <div class="modal-content  modal-sm">    
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Send Payment Message</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">			
                    <div class="col-sm-12">
                        <label for="tax_name" class="control-label">Tokens</label>      
                    </div>            
                    <div class="col-sm-12">
                        <select class="selectpicker tokenSelector full-width" disabled="disabled">
                            <option data-tokens="default" class="tokenSelection" disabled="disabled" value="default" selected="selected">Choose a token to insert</option>
                            <option data-tokens="{{amount}}" class="tokenSelection">Amount</option>
                            <option data-tokens="{{company_name}}" class="tokenSelection">Company Name</option>
                            <option data-tokens="{{client_company_name}}" class="tokenSelection">Client Company Name</option>
                            <option data-tokens="{{client_first_name}}" class="tokenSelection">Client First Name</option>
                            <option data-tokens="{{client_last_name}}" class="tokenSelection">Client Last Name</option>
                            <option data-tokens="{{invoice_number}}" class="tokenSelection">Invoice Number</option>
                            <option data-tokens="{{reference}}" class="tokenSelection">Reference</option>
                        </select>       
                    </div>
                </div>
                <div class="form-group">			
                    <div class="col-sm-12">
                        <label for="payment_message" class="control-label">Message</label>      
                    </div>            
                    <div class="col-sm-12">
                       <textarea id="payment_message" name="payment_message" rows="6" class="form-control tokenIntertArea" placeholder="Type the default message that will display in an email when your payment notification is sent. Then insert tokens from above within your message. These tokens will be replaced with values and can be edited before the email is sent."><?php  echo $email_data['payment_message']; ?></textarea>                      
                    </div>
                </div>
                <div class="clearfix"></div>            
            </div>
        </div>
        <div class="modal-buttons">
           <button data-modal-body="Payment notification message updated successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div>
    </form>           
</div>