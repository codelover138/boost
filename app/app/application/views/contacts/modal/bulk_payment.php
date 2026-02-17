<?php
	//$contact_data = array_values($request['data'])[0];
?>

<div class="modal-dialog" role="document">
    <form action="<?php echo base_api_url('bulk/payments'); ?>" method="post">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add payments</h4>
            </div>
            <?php
				$count = 0;
				$contact_ids = array();
				foreach($request['data'] as $contact_key => $contact_data){
					$contact_ids[] = $contact_data['id'];
			?>
                    <div class="modal-body">
                        
                            <input type="hidden" name="data[<?php echo $count; ?>]['contact_id']" value="<?php echo $contact_data['id']; ?>" />
                            <div class="clearfix padded-top "></div> 
                            <div class="col-sm-12 form-group">
                            <?php // var_dump($contact_data['contact']); ?>
                              <strong> Contact <span class="text-primary">#<?php echo $contact_data['contact_number']; ?></span> for <a href="mailto:client@abcsoft.com"><?php echo $contact_data['contact']['email']; ?></a> </strong>                     
                            </div>
                            
                            <div class="col-sm-12 form-group">
                                 <div class="clearfix grey-border-bottom padded-top"></div> 
                            </div>
                            <div class="col-sm-3 form-group">
                                <label for="data[<?php echo $count; ?>]['payment_amount']" class="control-label">Payment <span class="text-light">(<?php echo $contact_data['currency_short_code']; ?>)</span></label>
                            </div>
                            <div class="col-sm-9 form-group">
                                <input class="payment_amount form-control" name="data[<?php echo $count; ?>]['payment_amount']" id="data[<?php echo $count; ?>]['payment_amount']" data-outstanding-amount="<?php echo $contact_data['amount_outstanding']; ?>" data-original-amount="0.00" value="0.00">           
                            </div>
                             <div class="col-sm-3 form-group">
                               
                            </div>
                            <div class="col-sm-3 form-group">
                                 <label class="checkbox-inline" style="margin-top:0;">
                                    <input class="payInFullCheckbox" type="checkbox" autocomplete="off"> Pay in Full
                                </label>          
                            </div>
        
                            <?php
                                if($contact_data['contact']['account']['account_standing']>0){
                            ?>
                                    <div class="col-sm-6 form-group">
                                         <label class="checkbox-inline" style="margin-top:0;">
                                            <input name="data[<?php echo $count; ?>]['use_credit']" value="yes" type="checkbox" autocomplete="off" checked> Use account credit (<?php echo $contact_data['currency_symbol'].number_format($contact_data['contact']['account']['account_standing'],2,'.',','); ?>)
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
                                        if($contact_data['amount_outstanding'] < 0){
                                            echo $contact_data['currency_symbol'].'0 ('.$contact_data['currency_symbol'].number_format(($contact_data['amount_outstanding']*-1),2,'.',',').' credited to account)'; 
                                        }else{
                                            echo $contact_data['currency_symbol'].number_format($contact_data['amount_outstanding'],2,'.',','); 
                                        }
                                    ?>
                                </strong>          
                            </div>
                            
                            <div class="clearfix"></div>
                            
                            <div class="col-sm-3 form-group">
                                <label for="data[<?php echo $count; ?>]['payment_method_id']" class="control-label">Method</label>
                            </div>
                            <div class="col-sm-9 form-group">
                                 <select id="data[<?php echo $count; ?>]['payment_method_id']" name="data[<?php echo $count; ?>]['payment_method_id']" class="selectpicker full-width">
                                    <?php						
                                        foreach($request['piggyback']['payment_methods'] as $payment_methodt_data){						
                                            echo '<option value="'.$payment_methodt_data['id'].'">'.$payment_methodt_data['payment_method'].'</option>';									
                                        }
                                    ?>
                                </select>          
                            </div>
                            
                            <div class="col-sm-3 form-group">
                                <label for="data[<?php echo $count; ?>]['payment_date']" class="control-label">Date:</label>
                            </div>
                            <div class="col-sm-9 form-group">
                               <input name="data[<?php echo $count; ?>]['payment_date']" class="form-control datepicker" id="data[<?php echo $count; ?>]['payment_date']" value="<?php echo date("Y-m-d"); ?>">        
                            </div>
                            
                            <div class="col-sm-3 form-group">
                                <label for="data[<?php echo $count; ?>]['reference']" class="control-label">Reference</label>
                            </div>
                            <div class="col-sm-9 form-group">
                                <input name="data[<?php echo $count; ?>]['reference']" class="form-control" id="data[<?php echo $count; ?>]['reference']" value="<?php echo $contact_data['contact_number']; ?>">           
                            </div>
                                 
                           
                            <div class="clearfix"></div>
                        
                    </div>
            
                    <div class="modal-footer">
                        <div class="col-sm-12">
                            <label class="checkbox-inline" style="margin-top:0;">
                                <input name="data[<?php echo $count; ?>]['notifiction']" type="checkbox" autocomplete="off" checked>Send a payment notification email
                            </label>
                        </div>
                    </div>
            <?php
					$count++;
				}
			?>
        </div>
        <div class="modal-buttons">
            <button data-modal-body="Payments added successfully. " data-related-section="contacts" data-related-ids="<?php echo implode(',',$contact_ids); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
    </form>        
</div>
