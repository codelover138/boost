<?php
	$data = array_values($request['data'])[0];
	$org_settings_data = array_values($request['piggyback']['organizations'])[0];
	$account_data = $data['account'];
	$currency_data = $request['piggyback']['currencies'][$org_settings_data['currency_id']];
	//var_dump($data);	
?>

<!-- content area -->
                
    <div class="container-fluid bg-white doc-spaced">           			
        <div class="container-fluid visible-sm-table full-width">
            <h3>Account Standing</h3>
            <div class="clearfix grey-border-bottom"></div>
        </div>
        
        <div class="col-xs-12 col-sm-4">
        	<?php
				if($account_data['account_standing'] < 0){
					echo '<h2 class="danger">'.$currency_data['currency_symbol'].number_format(($account_data['account_standing']*-1),2,'.',',').'</h2>';
				}else{
					echo '<h2 class="success">'.$currency_data['currency_symbol'].number_format($account_data['account_standing'],2,'.',',').'</h2>';					
				}
			
			?>
            <h4>Outstanding</h4>
        </div>
        <div class="col-xs-12 col-sm-4">
            <h2 class="success"><?php echo $currency_data['currency_symbol'].number_format($account_data['paid'],2,'.',','); ?></h2>
            <h4>Paid</h4>
        </div>
        <div class="col-xs-12 col-sm-4">
            <h2 class="primary"><?php echo $currency_data['currency_symbol'].number_format($account_data['estimates'],2,'.',','); ?></h2>
            <h4>Estimates sent</h4>
        </div>                                           
    </div>
    
    <div class="container-fluid bg-white doc-spaced">
        
       <div class="container-fluid full-width">
            <!--<div class="visible-xs-block visible-sm-table-cell" style="vertical-align:bottom;">
                <img class="preview-logo" src="images/temp_logo.png" />
            </div>-->
    
            <h3>
                <?php echo $data['organisation']; ?> (<?php echo ucwords($data['contact_type']); ?>)
            </h3>
                                                  
       </div>
        
       <div class="container-fluid full-width">
            <h5>Address</h5>
            <div class="clearfix grey-border-bottom form-group"></div>   
            <div>
                <?php echo str_replace(array("\r\n","\n","\r"),'<br />',$data['address']); ?>
            </div>                                    
       </div>
    
        <div class="clearfix formSpacer"></div>
    
       <div class="container-fluid full-width">
            <h5>Primary Contact</h5>
            <div class="clearfix grey-border-bottom form-group"></div>   
            <div class="visible-sm-table">
                <div class="tableRow">
                    <div class="visible-sm-table-cell left-details-col">First Name:</div> 
                    <div class="visible-sm-table-cell right-details-col"><?php echo ucwords($data['first_name']); ?></div>
                </div>
                 <div class="tableRow">
                    <div class="visible-sm-table-cell left-details-col">Last Name:</div> 
                    <div class="visible-sm-table-cell right-details-col"><?php echo ucwords($data['last_name']); ?></div>
                </div>
                <div class="tableRow">
                    <div class="visible-sm-table-cell left-details-col">Email:</div> 
                    <div class="visible-sm-table-cell right-details-col"><a href="mailto:<?php echo $data['email']; ?>"><?php echo $data['email']; ?></a></div>
                </div>
                <div class="tableRow">
                    <div class="visible-sm-table-cell left-details-col">Business Phone:</div> 
                    <div class="visible-sm-table-cell right-details-col"><?php echo $data['land_line']; ?></div>
                </div>
                <div class="tableRow">
                    <div class="visible-sm-table-cell left-details-col">Mobile Phone:</div> 
                    <div class="visible-sm-table-cell right-details-col"><?php echo $data['mobile']; ?></div>
                </div> 
            </div>                           
       </div>
        
      
                      
    </div>               
    
<!-- END content area -->   