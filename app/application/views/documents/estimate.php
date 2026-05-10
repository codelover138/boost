<?php
	$data = array_values($request['data'])[0];
	$organizations_details = array_values($request['piggyback']['organizations'])[0];
	$theme_data = array_values($request['piggyback']['theme_settings'])[0];
	$template_data = array_values($request['piggyback']['templates'])[0];
	$client_data = $data['contact'];
	$items = $data['items'];
	
	$temp_status = strtolower($data['status']);
	
	if(($temp_status == 'sent' || $temp_status == 'viewed') && (int)strtotime($data['due_date']) < (int)(time()-(60*60*24))){
		$status = 'expired';
	}else{
		$status = $temp_status;
	}

	
?>

<div class="sub_header">
	<div class="sub_header_inner max-width-1200" style="opacity: 1;text-align:right;">
		 <?php if($status != 'declined' && $status != 'draft'){  ?>
			<button href="<?php echo $request['decline']; ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Estimate has been Declined." type="button" class="btn btn-default saveButton padded docMarkStatus">Decline</button>
		 <?php } ?> 
		 <?php if($status != 'accepted' && $status != 'draft'){  ?>  
		 <button href="<?php echo $request['accept']; ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Estimate has been Accepted."  type="button" class="btn btn-success saveButton docMarkStatus">Accept</button>
		 <?php } ?> 
	</div>
</div>

<div class=" contentMainLeft" style="max-width:960px; margin:auto"> 
	<!-- START main content area -->
<!-- content area -->
    <div class="container-fluid bg-white doc-spaced status-preview-corner status-value-<?php echo $status; ?>">
                
        <div class="container-fluid visible-sm-table full-width">
           <div class="visible-xs-block visible-sm-table-cell preview_logo_container">
           		<?php
					if(isset($theme_data['image_string']) && !empty($theme_data['image_string'])){
						echo '<img class="preview-logo" src="'.$theme_data['image_string'].'" />';
					}else{
						echo '<div class="upload_button_container">';
						echo '	<img class="non_settings_add_logo" src="'.base_url('images/add_logo_button.png').'" />';
						echo '	<input type="file" size="40" name="file_source" id="previewLogoFile" class="upload_button" alt="Add your logo" title="Add your logo">';
						echo '</div>';
					}				
				?>
           </div>
                   
        <div class="clearfix formSpacer"></div>
                    
       <div class="visible-xs-block visible-sm-table-cell text-left-xs text-right-sm preview-details">
            <h3><?php echo $template_data['estimate_name']; ?> <span class="text-primary">#<?php echo $data['estimate_number']; ?></span></h3>                       
            <div><strong><?php echo $organizations_details['company_name']; ?></strong></div>            	
            <?php 
			 	if(isset($organizations_details['address_line_1'])){
					echo '<div>'.$organizations_details['address_line_1'].'</div>'; 
				}
			?>
            <?php 
			 	if(isset($organizations_details['address_line_2'])){
					echo '<div>'.$organizations_details['address_line_2'].'</div>'; 
				}
			?>
            <div><?php echo $organizations_details['city']; ?>, <?php echo $organizations_details['region_state']; ?>, ZIP code</div>
            <div>Country Name</div>
        </div>
                </div>
                
                <div class="clearfix formSpacer"></div>
                
        <div class="col-sm-6 col-xs-12 pull-left-xs text-left preview-details">
                    <div><strong>Bill to: </strong></div>
                    <div><?php echo $client_data['organisation']; ?></div>
                    <div>Client Address</div>
                    <div>City, Province, ZIP code</div>
                </div>
                <div class="clearfix visible-xs-block formSpacer"></div>
        <div class="col-sm-6 col-xs-12  pull-left-xs pull-right-sm text-left-xs text-right-sm preview-details">
                    <div><strong>Estimate date: </strong><?php echo $data['date']; ?></div>
                    <div><strong>Due date: </strong><?php echo $data['due_date']; ?></div>
                    <div><strong>Ref No: </strong><?php echo $data['reference']; ?></div>
                    <div><strong>Amount due: </strong> <?php echo $data['currency_symbol']; ?><?php echo number_format($data['total_amount'],2,'.',','); ?></div>
               </div>
       
      <div class="clearfix formSpacer"></div>
     
     <div class="form_section ">
         <div class="container-fluid itemList">
            <div class="row heaaderColTable  hidden-xs hidden-sm">
                <div class="col-md-6">
                    Item:
                </div>
                <div class="col-md-2 text-center-sm">
                    Qty:
                </div>
                 <div class="col-md-2 text-right-sm">
                    Rate:
                </div>
                 <div class="col-md-2 text-right-sm">
                    Total (ZAR):
                </div>
            </div>
            
            <?php
				//var_dump($items);
				foreach($items as $item_key => $item_data){
					$this->load->view('global_snippets/item_preview_row',$item_data); 
				}
			
			?>
             
           
            <div class="row listSubTotal">
                <div class="row pull-right-xs  col-xs-12 col-sm-8 col-md-4 clear-right-xs no-gutter-xs">                        	 
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg">
                        <strong>Subtotal:</strong>
                    </div>
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg text-light">
                        <strong><?php echo number_format($data['sub_total'],2,'.',','); ?></strong>
                    </div>
                </div> 
                <div class="row pull-right-xs  col-xs-12 col-sm-8 col-md-4 clear-right-xs  no-gutter-xs">                       	 
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg">
                        <strong>Discount:</strong>
                    </div>
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg no-gutter-lg text-light">
                        <strong><?php echo number_format($data['discount_total'],2,'.',','); ?></strong>
                    </div>
                </div>
                <div class="row pull-right-xs  col-xs-12 col-sm-8 col-md-4 clear-right-xs no-gutter-xs">                        	 
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg">
                        <strong>Vat Amount:</strong>
                    </div>
                    <div class="col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg text-light">
                        <strong><?php echo number_format($data['vat_amount'],2,'.',','); ?></strong>
                    </div>
                </div> 
                <div class="listTotal row pull-right-xs  col-xs-10 col-sm-8 col-md-4 no-gutter-xs clear-right-xs">                        	 
                    <div class="listTotalLabel col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg">
                        <h3>Total:</h3>
                    </div>
                    <div class="listTotalAmount col-xs-6 text-right-xs no-gutter-xs no-gutter-xs no-gutter-sm no-gutter-md  no-gutter-lg text-light">
                        <h3 class="text-primary"><?php echo $data['currency_symbol'].number_format($data['total_amount'],2,'.',','); ?></h3>
                    </div>
                </div>
            </div>
         </div> 
     </div> 
     <div class="clearfix formSpacer"></div>
     <div class="form_section">
         <div class="col-xs-12 col-sm-6 pull-left-sm clear-left-sm">
              <div class="form-group col-xs-12">
               <strong>Terms (or Banking Details): </strong>                    
               <p><?php echo $data['terms']; ?></p>                      
              </div>
         </div>
          <div class="col-xs-12 col-sm-6   pull-right-sm clear-right-sm">
              <div class="form-group col-xs-12">
               <strong>Closing Note:</strong>                    
               <p><?php echo $data['closing_note']; ?></p>                      
              </div>
         </div>
     </div>
     
      <div class="clearfix"></div>
         <div class="form_section container-fluid" style="text-align:right;">
             <?php if($status != 'declined' && $status != 'draft'){  ?>
             	<button href="<?php echo $request['decline']; ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Estimate has been Declined." type="button" class="btn btn-default saveButton padded docMarkStatus">Decline</button>
             <?php } ?> 
             <?php if($status != 'accepted' && $status != 'draft'){  ?>  
             <button href="<?php echo $request['accept']; ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Estimate has been Accepted."  type="button" class="btn btn-success saveButton docMarkStatus">Accept</button>
             <?php } ?> 
         </div>
         
          <div class="clearfix"></div>

       
    </div>
 
	<!-- END main content area -->
</div>               
   
      
    
    <!-- END content area -->   