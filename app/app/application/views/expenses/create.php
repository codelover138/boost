<?php
$currencies = $request['piggyback']['currencies'];
$organisation_data = array_values($request['piggyback']['organisations'])[0];
$currency_id = $organisation_data['currency_id'];
$currency_data = $currencies[$currency_id];

//var_dump($request);
?>

 <!-- content area -->
    <form method="post" action="<?php echo base_api_url('expenses/'); ?>"  autocomplete="off">
    <input type="hidden" name="usr_id" value="<?php echo $request['user_data']['id']; ?>" /> 
    <div class="container-fluid bg-white doc-spaced">
       <div class="col-xs-12">
            <h3 class="minimise-margin-bottom">Expense Details</h3>
            <div class="clearfix grey-border-bottom form-group"></div> 
       </div>
       
      <div class="form_section">
          <div class="form-group col-xs-12 col-sm-6">
            <label for="supplier_id" class="control-label pull-left-sm clear-left-sm">Vendor</label>
            <div class="col-sm-9 pull-right-sm no-gutter-xs">            
				<?php 
                 	$contact_data['contacts'] = $request['contact_types'][2];
					$contact_data['type'] = 'supplier';
					//$contact_data['current_id'] = @$invoice_data['contact']['id'];
                    $this->load->view('global_snippets/select_contact',$contact_data);  
                ?>
            </div>
          </div>
          
          <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
            <label for="date" class="control-label pull-left-sm">Date</label>
            <div class="col-sm-9  pull-right-sm no-gutter-xs">
                <input type="text" class="form-control datepicker" name="date" id="date" value="<?php echo date("Y-m-d"); ?>">
            </div>
          </div>  
                            
          
          <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
            <label for="total_amount" class="control-label">Amount (<?php echo $currency_data['short_code']; ?>)</label>
            <div class="col-sm-9  pull-right-sm no-gutter-xs">
               <input name="total_amount" class="form-control" id="total_amount" placeholder="0.00">
            </div>
          </div>
                  
         
          <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
            <label for="tax_1" class="control-label">Tax 1</label>
            <div class="col-sm-9  pull-right-sm  no-gutter-xs">
                <select id="tax_1" name="tax_1" class="selectpicker full-width"> 
                     <option disabled="disabled" value="0" selected="selected">Choose Tax</option>
                     <option value="0" data-tokens="<?php echo base_url('modal/tax/add/'); ?>" class="open-modal">Add a Tax Item</option>                	
                     <?php
                        foreach($request['piggyback']['taxes'] as $tax_data){
                            echo '<option value="'.$tax_data['id'].'">'.$tax_data['tax_name'].'</option>';
                        }
                     ?>   
                </select>
            </div>
          </div> 
          
          <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
            <label for="tax_2" class="control-label">Tax 2</label>
            <div class="col-sm-9  pull-right-sm  no-gutter-xs">
                <select id="tax_2" name="tax_2" class="selectpicker full-width"> 
                     <option disabled="disabled" value="0" selected="selected">Choose Tax</option>
                     <option value="0" data-tokens="<?php echo base_url('modal/tax/add/'); ?>" class="open-modal">Add a Tax Item</option>                	
                     <?php
                        foreach($request['piggyback']['taxes'] as $tax_data){
                            echo '<option value="'.$tax_data['id'].'">'.$tax_data['tax_name'].'</option>';
                        }
                     ?>   
                </select>
            </div>
          </div>
          
     </div> 
     
     <div class="clearfix formSpacer"></div>
     
     <div class="col-xs-12">
            <h3 class="minimise-margin-bottom">Client Details</h3>
            <div class="clearfix grey-border-bottom form-group"></div> 
       </div>
     
     <div class="form_section">
          <div class="form-group col-xs-12 col-sm-6">
            <label for="contact_id" class="control-label pull-left-sm clear-left-sm">Client</label>
            <div class="col-sm-9 pull-right-sm no-gutter-xs">            
				<?php 
                 	$contact_data['contacts'] = $request['contact_types'][1];
					$contact_data['type'] = 'client';
                    $this->load->view('global_snippets/select_contact',$contact_data);  
                ?>
            </div>
          </div>
          
          <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
            <label for="notes" class="control-label">Note</label>
            <div class="col-sm-9  pull-right-sm no-gutter-xs">
                <input type="text" name="notes" id="notes" class="form-control">
            </div>
          </div>  
                            
          
          <div class="form-group col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
            <label for="category_id" class="control-label">Category</label>
            <div class="col-sm-9  pull-right-sm no-gutter-xs">
               <select id="category_id" name="category_id" class="selectpicker full-width"> 
                   <option value="">Choose a Category</option>                	
                   <?php
                       foreach($request['piggyback']['expenses_categories'] as $category_data){
                           echo '<option value="'.$category_data['id'].'">'.$category_data['category_name'].'</option>';
                       }
                   ?>   
               </select>
            </div>
          </div>
                  
         
          <div class="form-group col-xs-12 col-sm-6  pull-right-sm clear-right-sm">
            <label for="recieptFile" class="control-label">Reciept</label>
            <div class="col-sm-9  pull-right-sm  no-gutter-xs">
                <div class="upload_button_container pull-right">
                    <a class='btn btn-default'>Select File &nbsp;+</a>
                    <input class="upload_button" id="recieptFile" type="file" name="file_name" size="40" >
                </div>
               <div class="reciept_preview_container pull-left">
                	<?php if($document_data['file_name']){  ?>
                	 <a id="view_reciept" class="action_links openImageModal" href="<?php echo $document_data['file_name']; ?>" target="_blank">View</a> <span class="action_links" >|</span> <a id="remove_reciept" class="action_links" href="#">Remove</a>
                	 <?php }  ?>
                </div>
            </div>
          </div> 
                    
     </div>
     
    <div class="clearfix formSpacer"></div>
    <div class="form_section container-fluid" style="text-align:right;">
         <button data-redirect-url="<?php echo base_url('expenses'); ?>" data-redirect-without-id="true" data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Saved Successfully. " type="button" class="btn btn-success saveButton saveFormData">Save</button>
    </div>
       
    </div>
    
   
     </form> 
    
<!-- END content area -->