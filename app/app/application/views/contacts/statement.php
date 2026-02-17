<?php
$start_date = date("Y-m-d",strtotime($request['filters']['formatted_start_date']));
$end_date = date("Y-m-d",strtotime($request['filters']['formatted_end_date']));
$totals = $request['statements']['totals'];
//var_dump($totals);
?>

<div class="container-fluid bg-white doc-spaced">   
    <div class="mainFilterContainer">
        <div class="container-fluid filters">
            <div class="filter_selection_container">
            	<form method="get" action="" autocomplete="off">
                    <div class="form-group col-xs-12 col-sm-3  pull-left-sm">
                        <label for="date" class="control-label pull-left-sm">Start Date</label>
                        <div class="col-sm-12 pull-left-sm no-gutter-xs  clear-left-sm">
                            <input type="text" class="form-control datepicker" name="start_date" id="start_date" value="<?php echo $start_date; ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-3  pull-left-sm">
                        <label for="date" class="control-label pull-left-sm">End Date</label>
                        <div class="col-sm-12 pull-left-sm no-gutter-xs  clear-left-sm">
                            <input type="text" class="form-control datepicker" name="end_date" id="end_date" value="<?php echo $end_date; ?>">
                        </div>
                    </div>
                    <div style="text-align:right;" class="form_section container-fluid filter_buttons_right">
                    	 <!--<a class="print_icon" href="#">Print as PDF</a>
                         <a class="export_icon" href="#">Send</a>-->
                         <button class="btn btn-success saveButton statementFilter" type="button" data-redirect-url="<?php echo base_url('contacts/statements/'.$request['contact']['id']); ?>">Update</button>
                    </div>
                    <div class="clearfix"></div> 
                </form>
            </div>                                            
        </div>
    </div>   
    
    <div class="container-fluid">
    	<h2><?php echo $request['contact']['organisation']; ?></h2>
        <h4>
		<?php echo $start_date.' - '.$end_date; ?>
        </h4>
        <div class="clearfix formSpacer"></div> 
    </div>
    
    
    <!-- list area -->
    <div class="container-fluid">
        
       <!-- <div class="table-responsive">-->
            <table class="table listInvoiceTable altTable" style="font-weight:bold;">
              <tr class="table_header">
                <td>Date</td>
                <td>Document No.</td>
                <td>Amount</td>
                <td>Paid</td> 
                <td>Balance</td>                   
              </tr>
              <tr class="table_spacer">
                <td colspan="5"></td>                 
              </tr>
               <tr>
                <td>Balance brought forward</td>
                <td></td>
                <td></td>
                <td></td>  
                <td>
					<?php 
					if(isset($totals['balance_brought_forward'])){					
						if($totals['balance_brought_forward'] >=0){
							echo $currency_symbol.number_format($totals['balance_brought_forward'],2,'.',','); 
						}else{
							echo '('.$currency_symbol.number_format($totals['balance_brought_forward']*-1,2,'.',',').')'; 
						}
					}else{
						echo '-';
					}
					?>
                </td>                  
              </tr>
              <tr class="table_spacer">
                <td colspan="5"></td>                 
              </tr>
              <tr class="table_break">
                <td colspan="5"></td>                 
              </tr>
              <tr class="table_spacer">
                <td colspan="5"></td>                 
              </tr>
              <?php 
				  
				  if(isset($totals['balance_brought_forward'])){					
						$running_balance = $totals['balance_brought_forward'];
					}else{
						$running_balance = 0;
					}
				  
				  $view_data = array();
				
                  foreach($request['statements']['documents'] as $item_key => $item_data)
				  {
					
					foreach($view_data as $k => $v) {
						  $view_data[$k] = NULL;
					}
					
					$format_array = date("d/m/Y",strtotime($item_data['date_created']));
					
					
					
					$view_data['date_created'] = $format_array;
					$view_data['id'] = $item_data['id'];
					
					if($item_data['type'] == 'invoice'){
						$view_data['document_number'] = $item_data['number'];
						$view_data['debt'] = $currency_symbol.number_format($item_data['total_amount'],2,'.',',');
						$view_data['paid'] = '';
						$view_data['link_section'] = 'invoices';
						
						$running_balance += $item_data['total_amount'];
						
						if($running_balance >=0){
							$view_data['running_balance'] = $currency_symbol.number_format($running_balance,2,'.',','); 
						}else{
							$view_data['running_balance'] = '('.$currency_symbol.number_format($running_balance*-1,2,'.',',').')'; 
						}
						
					}elseif($item_data['type'] == 'credit_note'){
						$view_data['document_number'] = $item_data['number'];
						$view_data['debt'] = '('.$currency_symbol.number_format($item_data['total_amount'],2,'.',',').')';
						$view_data['paid'] = '';
						$view_data['link_section'] = 'credit_notes';
						
						$running_balance -= $item_data['total_amount'];
						
						if($running_balance >=0){
							$view_data['running_balance'] = $currency_symbol.number_format($running_balance,2,'.',','); 
						}else{
							$view_data['running_balance'] = '('.$currency_symbol.number_format($running_balance*-1,2,'.',',').')'; 
						}
						
					}elseif($item_data['type'] == 'payment'){
						$view_data['document_number'] = $item_data['reference'].' - Payment';		
						$view_data['debt'] = '';
						$view_data['paid'] = $currency_symbol.number_format($item_data['amount'],2,'.',',');
						$view_data['link_section'] = false;
						
						$running_balance -= $item_data['amount'];
						
						if($running_balance >=0){
							$view_data['running_balance'] = $currency_symbol.number_format($running_balance,2,'.',','); 
						}else{
							$view_data['running_balance'] = '('.$currency_symbol.number_format($running_balance*-1,2,'.',',').')'; 
						}
						
					}else{
						$view_data['document_number'] = 'unrecognised';		
						$view_data['debt'] = '';
						$view_data['paid'] = $currency_symbol.number_format($item_data['amount'],2,'.',',');
						$view_data['link_section'] = false;
						
						if($running_balance >=0){
							$view_data['running_balance'] = $currency_symbol.number_format($running_balance,2,'.',','); 
						}else{
							$view_data['running_balance'] = '('.$currency_symbol.number_format($running_balance*-1,2,'.',',').')'; 
						}
						 
					}

                      $this->load->view('contacts/snippets/statement_list_row',$view_data); 

                  }
				  
				  
				  
              ?>
              <tr class="table_spacer">
                <td colspan="5"></td>                 
              </tr>
              <tr class="table_break">
                <td colspan="5"></td>                 
              </tr>
              <tr class="table_spacer">
                <td colspan="5"></td>                 
              </tr>
              <tr class="table_totals">
                <td>Invoiced Total</td>
                <td></td>
                <td><?php 
						if(($totals['invoices'] - $totals['credit_note']) >= 0){
							echo $currency_symbol.number_format($totals['invoices'] - $totals['credit_note'],2,'.',',');
						}else{
							echo '('.$currency_symbol.number_format($totals['credit_note'] - $totals['invoices'],2,'.',',').')';
						}
						
					?>
                    </td>
                <td></td> 
                <td></td>                  
              </tr>
               <tr class="table_totals">
                <td>Paid Total</td>
                <td></td>
                <td></td>
                <td><?php echo $currency_symbol.number_format( $totals['payments'],2,'.',','); ?></td>                   
             	<td></td>
              </tr>
              <!-- <tr class="table_totals">
                <td>Credit Notes Total</td>
                <td></td>
                <td></td>
                <td><?php echo $currency_symbol.number_format($totals['credit_note'],2,'.',','); ?></td>                   
              </tr>
              <tr class="table_totals">
                <td>Credit</td>
                <td></td>
                <td></td>
                <td><?php echo $currency_symbol.number_format($totals['credit'],2,'.',','); ?></td>                   
              </tr>-->
              <tr class="table_spacer">
                <td colspan="5"></td>                 
              </tr>
              <tr class="table_break">
                <td colspan="5"></td>                 
              </tr>
               <tr class="table_totals">
                <td>Account Balance</td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php 
					
					if(($totals['invoices'] - $totals['payments'] - $totals['credit_note']) >=0){
						echo $currency_symbol.number_format(($totals['invoices'] - $totals['payments'] - $totals['credit_note']),2,'.',','); 
					}else{
						echo '('.$currency_symbol.number_format(($totals['invoices'] - $totals['payments'] - $totals['credit_note'])*-1,2,'.',',').')'; 
					}
					//var_dump($totals);
					?>
                    
                </td>                   
              </tr>
              <tr class="table_break">
                <td colspan="5"></td>                 
              </tr>
            </table>
            <?php // var_dump($totals); ?>
       <!--  </div> -->
    </div>
</div>
<!-- END list area -->  
