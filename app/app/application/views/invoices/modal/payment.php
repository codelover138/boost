<?php
	$invoice_data = array_values($request['data'])[0];
	
?>

<div class="modal-dialog" role="document">
    <form action="<?php echo base_api_url('payments/'); ?>" data-validation-placement="below" <?php if(isset($form_dataset)){ echo $form_dataset;} ?> method="post">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add a payment</h4>
            </div>
            <div class="modal-body">
                    <?php // echo var_dump($form_dataset); ?>
                    <input type="hidden" name="invoice_id" value="<?php echo $invoice_data['id']; ?>" />
                    <div class="clearfix padded-top "></div> 
                    <div class="col-sm-12 form-group">
                      <strong> Invoice <span class="text-primary">#<?php echo $invoice_data['invoice_number']; ?></span> for <a href="mailto:client@abcsoft.com"><?php echo $invoice_data['contact']['email']; ?></a> </strong>                     
                    </div>
                    
                    <div class="col-sm-12 form-group">
                         <div class="clearfix grey-border-bottom padded-top"></div> 
                    </div>
                    <div class="col-sm-3 form-group">
                        <label for="payment_amount" class="control-label">Payment <span class="text-light">(<?php echo $invoice_data['currency_short_code']; ?>)</span></label>
                    </div>
                    <div class="col-sm-9 form-group">
                        <input class="payment_amount form-control" name="payment_amount" data-outstanding-amount="<?php echo $invoice_data['amount_outstanding']; ?>" data-credit-amount="<?php echo $invoice_data['contact']['account']['credit']; ?>" data-original-amount="0.00" id="payment_amount" value="0.00">           
                    </div>
                     <div class="col-sm-3 form-group">
                       
                    </div>
                    <div class="col-sm-3 form-group">
                         <label class="checkbox-inline" style="margin-top:0;">
                            <input class="payInFullCheckbox" type="checkbox" autocomplete="off"> Pay in Full
                        </label>          
                    </div>

                    <?php
						if($invoice_data['contact']['account']['credit']>0){
                    ?>
                            <div class="col-sm-6 form-group">
                                 <label class="checkbox-inline" style="margin-top:0;">
                                    <input class="useCreditCheckbox" name="use_credit" value="yes" type="checkbox" autocomplete="off"> Use account credit (<?php echo $invoice_data['currency_symbol'].number_format($invoice_data['contact']['account']['credit'],2,'.',','); ?>)
                                </label>          
                            </div>
                    <?php
						}
					?>
                    
                    <div class="clearfix grey-border-bottom padded-top formSpacer"></div> 
                    
                    <div class="col-sm-3 form-group">
                        <strong>Outstanding Amount</strong>
                    </div>
                    <div class="col-sm-9 form-group">
                        <strong>
							<?php 
								if($invoice_data['amount_outstanding'] < 0){
									echo $invoice_data['currency_symbol'].'0 ('.$invoice_data['currency_symbol'].number_format(($invoice_data['amount_outstanding']*-1),2,'.',',').' credited to account)'; 
								}else{
									echo $invoice_data['currency_symbol'].number_format($invoice_data['amount_outstanding'],2,'.',','); 
								}
							?>
                        </strong>          
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-sm-3 form-group">
                        <label for="payment_method_id" class="control-label">Method</label>
                    </div>
                    <div class="col-sm-9 form-group">
                         <select id="payment_method_id" name="payment_method_id" class="selectpicker full-width">
                         	<option value="">Choose a payment method</option>
                            <?php						
                                foreach($request['piggyback']['payment_methods'] as $payment_methodt_data){						
                                    if($payment_methodt_data['id'] != 4){
										echo '<option value="'.$payment_methodt_data['id'].'">'.$payment_methodt_data['payment_method'].'</option>';									
									}
							    }
                            ?>
                        </select>          
                    </div>
                    
                    <div class="col-sm-3 form-group">
                        <label for="payment_date" class="control-label">Date:</label>
                    </div>
                    <div class="col-sm-9 form-group">
                       <input name="payment_date" class="form-control datepicker" id="payment_date" value="<?php echo date("Y-m-d"); ?>">        
                    </div>
                    
                    <div class="col-sm-3 form-group">
                        <label for="reference" class="control-label">Reference</label>
                    </div>
                    <div class="col-sm-9 form-group">
                        <input name="reference" class="form-control" id="reference" value="<?php echo $invoice_data['invoice_number']; ?>">           
                    </div>
                         
                   
                    <div class="clearfix"></div>
                
            </div>
            <div class="modal-footer">
                <div class="col-sm-12">
                    <label class="checkbox-inline" style="margin-top:0;">
                        <input name="notifiction" value="yes" name="notifiction" type="checkbox" autocomplete="off" checked="checked">Send a payment notification email
                    </label>
                </div>
            </div>
            
        </div>
        <div class="modal-buttons">
            <button  data-related-section="invoices" data-related-id="<?php echo $invoice_data['id']; ?>" data-modal-body="Payment added successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Add Payment</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
    </form>        
</div>
