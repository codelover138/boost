<?php
$currencies = $request['piggyback']['currencies'];
$organisation_data = array_values($request['piggyback']['organisations'])[0];
$currency_id = $organisation_data['currency_id'];
$currency_data = $currencies[$currency_id];
?>
<div class="modal-dialog" role="document">
	<form data-success-function="updateContactsList(ids)" action="<?php echo base_api_url('contacts'); ?>" data-validation-placement="below" method="post">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Expense</h4>
        </div>
        <div class="modal-body">
       		 <?php
				//var_dump($currency_data['short_code']);
			 ?> 
            
            <div class="col-sm-12 form-group">
                <label for="organisation" class="control-label">Expense Details</label>
                <div class="clearfix grey-border-bottom form-group"></div>          
            </div>
            
            <div class="clearfix"></div>

            <div class="col-sm-6 form-group">
             	<div class="col-sm-4">
                	<label for="supplier_id" class="control-label">Vendor</label>
                </div>
                <div class="col-sm-8">
                    <select id="supplier_id" name="supplier_id" class="selectpicker full-width"> 
                    	<option value="">Choose a Vendor</option>                 	
                         <?php
                            foreach($request['contact_types'][2] as $vendor_data){
                                echo '<option value="'.$vendor_data['id'].'">'.$vendor_data['organisation'].'</option>';
                            }
                         ?>   
                    </select>
                </div>
            </div>
           
            <div class="col-sm-6 form-group pull-right-sm clear-right-sm">
             	<div class="col-sm-4">
                	<label for="expense_date" class="control-label">Date</label>
                </div>
                <div class="col-sm-8">
                     <input name="date" class="form-control datepicker" id="expense_date" value="<?php echo date("d M Y"); ?>">
                </div>
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-sm-6 form-group  pull-left-sm clear-left-sm">
             	<div class="col-sm-5">
                	<label for="expense_date" class="control-label">Amount (<?php echo $currency_data['short_code']; ?>)</label>
                </div>
                <div class="col-sm-7">
                     <input name="amount" class="form-control" id="amount" placeholder="0.00">
                </div>
            </div>
            
            <div class="col-sm-6 form-group pull-right-sm clear-right-sm">
             	<div class="col-sm-4">
                	<label for="tax_1" class="control-label">Tax 1</label>
                </div>
                <div class="col-sm-8">       	
                    <select id="tax_1" name="tax_1" class="selectpicker full-width"> 
                    	<option value="">Choose a Tax</option>                 	
                         <?php
                            foreach($request['piggyback']['taxes'] as $tax_data){
                                echo '<option value="'.$tax_data['id'].'">'.$tax_data['tax_name'].'</option>';
                            }
                         ?>   
                    </select>
                </div>
            </div>
            
            <div class="col-sm-6 form-group pull-right-sm clear-right-sm">
             	<div class="col-sm-4">
                	<label for="tax_2" class="control-label">Tax 2</label>
                </div>
                <div class="col-sm-8">
                    <select id="tax_2" name="tax_2" class="selectpicker full-width"> 
                    	<option value="">Choose a Tax</option>                 	
                         <?php
                            foreach($request['piggyback']['taxes'] as $tax_data){
                                echo '<option value="'.$tax_data['id'].'">'.$tax_data['tax_name'].'</option>';
                            }
                         ?>   
                    </select>
                </div>
            </div>
            
        
            
            
            
            <div class="clearfix"></div>   
                     
            <div class="col-sm-12">
                <label for="discount" class="control-label">Client Details</label> 
                <div class="clearfix grey-border-bottom form-group"></div>                    
            </div>
              
            <div class="clearfix"></div>  
               
            <div class="col-sm-6 form-group">
             	<div class="col-sm-4">
                	<label for="client_id" class="control-label">Client</label>
                </div>
                <div class="col-sm-8">
                    <select id="client_id" name="client_id" class="selectpicker full-width">  
                    	<option value="">Choose a Client</option>                	
                         <?php
                            foreach($request['contact_types'][1] as $client_data){
                                echo '<option value="'.$client_data['id'].'">'.$client_data['organisation'].'</option>';
                            }
                         ?>   
                    </select>
                </div>
            </div>
            
            <div class="col-sm-6 form-group">
             	<div class="col-sm-4">
                	<label for="notes" class="control-label">Note</label>
                </div>
                <div class="col-sm-8">
                     <input type="text" name="notes" id="notes" class="form-control">
                </div>
            </div>
            
            <div class="clearfix"></div>  
            
            <div class="col-sm-6 form-group">
             	<div class="col-sm-4">
                	<label for="category_id" class="control-label">Category</label>
                </div>
                <div class="col-sm-8">
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
            
            <div class="col-sm-6 form-group">
             	<div class="col-sm-4">
                	<label for="category_id" class="control-label">Reciept</label>
                </div>
                <div class="col-sm-8">
                    <div class="upload_button_container pull-right">
                   		<a class='btn btn-default'>Select File &nbsp;+</a>
                    	<input class="upload_button" id="logoFile" type="file" name="file_source" size="40" >
               		</div>
                </div>
            </div>
            
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="modal-buttons">
       <button  data-related-section="contacts" data-modal-body="Contact added successfully. " data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
    </div> 
    </form>          
</div>