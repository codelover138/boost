<?php
	$estimate_data = array_values($request['data'])[0];
	
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
                    <input type="hidden" name="estimate_id" value="<?php echo $estimate_data['id']; ?>" />
                    <div class="clearfix padded-top "></div> 
                    <div class="col-sm-12 form-group">
                      <strong> Estimate <span class="text-primary">#<?php echo $estimate_data['estimate_number']; ?></span> for <a href="mailto:client@abcsoft.com"><?php echo $estimate_data['contact']['email']; ?></a> </strong>                     
                    </div>
                    
                    <div class="col-sm-12 form-group">
                         <div class="clearfix grey-border-bottom padded-top"></div> 
                    </div>
                    <div class="col-sm-3 form-group">
                        <label for="payment_amount" class="control-label">Payment <span class="text-light">(<?php echo $estimate_data['currency_short_code']; ?>)</span></label>
                    </div>
                    <div class="col-sm-9 form-group">
                        <input class="payment_amount form-control" name="payment_amount" data-outstanding-amount="<?php echo $estimate_data['amount_outstanding']; ?>" data-original-amount="0.00" id="payment_amount" value="0.00">           
                    </div>
                     <div class="col-sm-3 form-group">
                       
                    </div>
                    <div class="col-sm-3 form-group">
                         <label class="checkbox-inline" style="margin-top:0;">
                            <input class="payInFullCheckbox" type="checkbox" autocomplete="off"> Pay in Full
                        </label>          
                    </div>

                    <?php
						if($estimate_data['contact']['account']['credit']>0){
                    ?>
                            <div class="col-sm-6 form-group">
                                 <label class="checkbox-inline" style="margin-top:0;">
                                    <input name="use_credit" value="yes" type="checkbox" autocomplete="off" checked> Use account credit (<?php echo $estimate_data['currency_symbol'].number_format($estimate_data['contact']['account']['credit'],2,'.',','); ?>)
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
								if($estimate_data['amount_outstanding'] < 0){
									echo $estimate_data['currency_symbol'].'0 ('.$estimate_data['currency_symbol'].number_format(($estimate_data['amount_outstanding']*-1),2,'.',',').' credited to account)'; 
								}else{
									echo $estimate_data['currency_symbol'].number_format($estimate_data['amount_outstanding'],2,'.',','); 
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
                            <?php						
                                foreach($request['piggyback']['payment_methods'] as $payment_methodt_data){						
                                    echo '<option value="'.$payment_methodt_data['id'].'">'.$payment_methodt_data['payment_method'].'</option>';									
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
                        <input name="reference" class="form-control" id="reference" value="<?php echo $estimate_data['estimate_number']; ?>">           
                    </div>
                         
                   
                    <div class="clearfix"></div>
                
            </div>
            <div class="modal-footer">
                <div class="col-sm-12">
                    <label class="checkbox-inline" style="margin-top:0;">
                        <input name="notifiction" type="checkbox" autocomplete="off" checked>Send a payment notification email
                    </label>
                </div>
            </div>
            
        </div>
        <div class="modal-buttons">
            <button  data-related-section="estimates" data-related-id="<?php echo $estimate_data['id']; ?>" data-modal-body="Payment added successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Add Payment</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
    </form>        
</div>
