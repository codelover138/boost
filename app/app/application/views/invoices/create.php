<?php

$template_data = array_values($request['piggyback']['templates'])[0];
$organization_data =array_values($request['piggyback']['organisations'])[0];
$base_currency_id = $organization_data['currency_id'];
$base_currency_data = $request['data'][$base_currency_id];

?>


 <!-- content area -->
     <form method="post" action="<?php echo base_api_url('invoices/'); ?>"  autocomplete="off">
     <input type="hidden" name="status" value="draft" /> 
    <div class="container-fluid bg-white doc-spaced">
       <div class="col-xs-12">
            <h3>Invoice Details</h3>
       </div>
       
      <div class="form_section">
          <div class="form-group col-xs-12 col-sm-6">
            <label for="contact_id" class="control-label pull-left-sm clear-left-sm">Client</label>
            <div class="col-sm-9 pull-right-sm no-gutter-xs">            
				<?php 
                 	$contact_data['contacts'] = $request['piggyback']['contacts/organisation'];
					$contact_data['current_id'] = @$invoice_data['contact']['id'];
                    $this->load->view('global_snippets/select_contact',$contact_data);  
                ?>
            </div>
          </div>
          
          <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
            <label for="date" class="control-label pull-left-sm">Date</label>
            <div class="col-sm-9  pull-right-sm no-gutter-xs">
                <input type="text" class="form-control datepicker" name="date" id="date" value="<?php echo date("d M Y"); ?>">
            </div>
          </div>  
                            
         <?php 
		// var_dump($base_currency_data);
			 ######################### KEEP FOR FUTURE ###########################
			 // 
			 //the below has been modified because currencies was disabled until a converter is put in place
			 //When the converter is in place remove this php as well as the php that closes the if statement that will always be false
			 //the correct selection of currencies will then work
			 //
			  echo '<input id="currency_id" name="currency_id" type="hidden" data-text="'.$base_currency_data['short_code'].'" data-symbol="'.$base_currency_data['currency_symbol'].'" value="'.$base_currency_data['id'].'" />';
			  
			  if(false){ 
		  
		  ?>
          <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
            <label for="currency_id" class="control-label">Currency</label>
            <div class="col-sm-9 pull-right-sm  no-gutter-xs">
                <select id="currency_id" name="currency_id" class="selectpicker full-width">
                	<?php						
						foreach($request['data'] as $currency_data){
							echo '<option data-text="'.$currency_data['short_code'].'" data-symbol="'.$currency_data['currency_symbol'].'" value="'.$currency_data['id'].'">'.$currency_data['short_code'].' - '.$currency_data['currency_name'].' ('.$currency_data['currency_symbol'].')</option>';
						}
					?>
                </select>
            </div>
          </div>
          <?php 
			  } 
			  ######################### END --- KEEP FOR FUTURE ###########################
		  ?>
          
          <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
            <label for="due_date" class="control-label pull-left-sm">Due Date</label>
            <div class="col-sm-9  pull-right-sm  no-gutter-xs">
                <input type="text" class="form-control datepicker" name="due_date" id="due_date"  value="<?php echo date("d M Y",strtotime("+7 day")); ?>">
            </div>
          </div>  
          
          <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
            <label for="discount_percentage" class="control-label">Discount %</label>
            <div class="col-sm-9  pull-right-sm no-gutter-xs">
                <input name="discount_percentage" class="form-control" id="discount_percentage" value="0" placeholder="%">
            </div>
          </div>
          
          <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
            <label for="invoice_number" class="control-label pull-left-sm">Invoice No</label>
            <div class="col-sm-9 pull-right-sm no-gutter-xs">
                <input type="text" class="form-control" name="invoice_number" id="invoice_number" value="INV-<?php echo $request['piggyback']['invoices/next_reference']; ?>">
            </div>
          </div> 
          
         
          <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
            <label for="reference" class="control-label pull-left-sm">Reference</label>
            <div class="col-sm-9  pull-right-sm  no-gutter-xs">
                <input type="text" class="form-control" name="reference" id="reference">
            </div>
          </div> 
          
           <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
             <div class="col-sm-9  pull-right-sm no-gutter-xs">
               <input type="hidden" id="reminder" name="reminder" value="0" />
                <a id="reminder_cta" href="<?php echo base_url('invoices/modal/reminder/0') ; ?>" class="open-modal">
                	<button type="button" class="btn btn-default">Add Payment Reminder <img style="padding-left:15px;" src="<?php echo base_url('images/reminder_icon.png'); ?>" /></button>
                </a>
             </div>
          </div>
          
     </div> 
     
     <div class="clearfix formSpacer"></div>
     <div class="form_section ">
         <div class="container-fluid itemList">
            <div class="headerColContainer" ondragover="drag_over_test(event,this)" ondragleave="drag_out_test(event,this)"  ondrop="drop(event,this)">
                    <div class="row heaaderColTable hidden-xs">
                        <div class="col-md-2">
                            Item:
                        </div>
                        <div class="col-md-4">
                            Description:
                        </div>
                        <div class="col-md-1">
                            Qty:
                        </div>
                         <div class="col-md-1">
                            Rate:
                        </div>
                         <div class="col-md-2">
                            Tax:
                        </div>
                         <div class="col-md-2">
                            Amount (<span class="currencyTextReplacement">ZAR</span>):
                        </div>
                    </div>
              </div>
                
            <?php $this->load->view('global_snippets/item_row',NULL);  ?>
            
            <div class="row listSubTotal">
             <button type="button" class="btn btn-default pull-left-xs addItemRow">Add Item Row &nbsp; &nbsp; +</button>
                <div class="row pull-right-xs  col-xs-12 col-sm-8 col-md-4 clear-right-xs no-gutter-xs">                        	 
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg">
                        <strong>Subtotal:</strong>
                    </div>
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg text-light">
                        <strong id="subTotal">0.00</strong>
                    </div>
                </div> 
                <div class="row pull-right-xs  col-xs-12 col-sm-8 col-md-4 clear-right-xs  no-gutter-xs">                       	 
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg">
                        <strong>Discount:</strong>
                    </div>
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg no-gutter-lg text-light">
                        <strong id="subTotalDiscount">0.00</strong>
                    </div>
                </div>
                <div class="row pull-right-xs  col-xs-12 col-sm-8 col-md-4 clear-right-xs no-gutter-xs">                        	 
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg">
                        <strong>Vat Amount:</strong>
                    </div>
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg text-light">
                        <strong id="subTotalTax">0.00</strong>
                    </div>
                </div> 
                <div class="listTotal row pull-right-xs  col-xs-10 col-sm-8 col-md-4 no-gutter-xs clear-right-xs">                        	 
                    <div class="listTotalLabel col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg">
                        <h3>Total:</h3>
                    </div>
                    <div class="listTotalAmount col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg text-light">
                        <h3 class="text-primary"><span class="currencySymbolReplacement"></span> <span  id="EndTotal">0.00</span></h3>
                    </div>
                </div>
            </div>
           
         </div> 
     </div> 
     <div class="clearfix formSpacer"></div>
     <div class="form_section">
         <div class="form-group col-xs-12 col-sm-6   pull-left-sm clear-left-sm">
              <div class="form-group col-xs-12  no-gutter-xs">
               <label for="terms" class="control-label">Terms (or Banking Details):</label>                       
               <textarea name="terms" rows="6" class="form-control" placeholder="Terms (or Banking Details)"><?php echo $template_data['invoice_terms']; ?></textarea>                        
              </div>
         </div>
         <div class="form-group col-xs-12 col-sm-6 pull-right-sm clear-right-sm">
               <div class="form-group col-xs-12 no-gutter-xs">
               <label for="closing_note" class="control-label">Closing Note:</label>                       
               <textarea name="closing_note" rows="6" class="form-control" placeholder="Add notes visible to client"><?php echo $template_data['invoice_closing_note']; ?></textarea>                        
              </div>
         </div>
     </div>
     
      <div class="clearfix"></div>
    <div class="form_section container-fluid" style="text-align:right;">
         <button data-redirect-url="<?php echo base_url('invoices'); ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-default saveButton padded saveFormData">Save</button>
         <button data-redirect-url="<?php echo base_url('invoices'); ?>" data-modal-url="<?php echo base_url('invoices/modal/send'); ?>" type="button" class="btn btn-success saveButton saveFormData">Send</button>
    </div>
       
    </div>
    
   
     </form> 
    
<!-- END content area -->