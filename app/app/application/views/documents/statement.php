<?php

$organization_data = array_values($request['piggyback']['organizations'])[0];
$currency_id = $organization_data['currency_id'];
$currencies_data = $request['piggyback']['currencies'];
$currency_symbol = $currencies_data[$currency_id]['currency_symbol'];
$request = $request['data'];
$filters = $request['filters'];
$end_date = date("Y-m-d", strtotime($filters['end_date']));
$start_date = date("Y-m-d", strtotime($filters['start_date']));
$totals = $request['statements']['totals'];

?>
<div class=" contentMainLeft" style="max-width:960px; margin:auto"> 
	<!-- START main content area -->
<div class="container-fluid bg-white doc-spaced">      
	<div class="mainFilterContainer">
	
        <div class="container-fluid filters">
            <div class="filter_selection_container">
            	<form id="dateFilter" method="post" action="<?php echo base_url(array_keys($_REQUEST)[0])."/"; ?>" autocomplete="off">
                    <div class="form-group col-xs-12 col-sm-3  pull-left-sm">
                        <label for="date" class="control-label pull-left-sm">Start Date</label>
                        <div class="col-sm-12 pull-left-sm no-gutter-xs  clear-left-sm">
                            <input type="text" class="form-control datepicker" name="filters[start_date]" id="start_date" value="<?php echo $start_date ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-3  pull-left-sm">
                        <label for="date" class="control-label pull-left-sm">End Date</label>
                        <div class="col-sm-12 pull-left-sm no-gutter-xs  clear-left-sm">
                            <input type="text" class="form-control datepicker" name="filters[end_date]" id="end_date" value="<?php echo $end_date ?>">
                        </div>
                    </div>
                    <div style="text-align:right;" class="form_section container-fluid filter_buttons_right">
                    	 <!--<a class="print_icon" href="#">Print as PDF</a>
                         <a class="export_icon" href="#">Send</a>-->
                         <input type="submit" onclick="" class="btn btn-success" id="filterUpdate" value="Update" />
                    </div>
                    <div class="clearfix"></div> 
                </form>
				<script>
				</script>
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
              </tr>
              <tr class="table_spacer">
                <td colspan="4"></td>                 
              </tr>
               <tr>
                <td>Balance brought forward</td>
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
                <td></td>                   
              </tr>
              <tr class="table_spacer">
                <td colspan="4"></td>                 
              </tr>
              <tr class="table_break">
                <td colspan="4"></td>                 
              </tr>
              <tr class="table_spacer">
                <td colspan="4"></td>                 
              </tr>
              <?php 

				  $view_data = array();
				
                  foreach($request['statements']['documents'] as $item_key => $item_data){
					  
					 // var_dump($item_data);
					  
					  foreach($view_data as $k => $v) {
						  $view_data[$k] = NULL;
					  }
					  
					  $item_data['currency_symbol'] = $currency_symbol;
					  
					  
					$view_data['date_created'] = $item_data['date_created'];
					$view_data['id'] = $item_data['id'];
					
					if($item_data['type'] == 'invoice'){
						$view_data['document_number'] = $item_data['number'];
						$view_data['debt'] = $currency_symbol.number_format($item_data['total_amount'],2,'.',',');
						$view_data['paid'] = '';
						$view_data['link_section'] = 'invoices';
					}elseif($item_data['type'] == 'credit_note'){
						$view_data['document_number'] = $item_data['number'];
						$view_data['debt'] = '('.$currency_symbol.number_format($item_data['total_amount'],2,'.',',').')';
						$view_data['paid'] = '';
						$view_data['link_section'] = 'credit_notes';
					}elseif($item_data['type'] == 'payment'){
						$view_data['document_number'] = $item_data['reference'].' - Payment';		
						$view_data['debt'] = '';
						$view_data['paid'] = $currency_symbol.number_format($item_data['amount'],2,'.',',');
						$view_data['link_section'] = false;
					}else{
						$view_data['document_number'] = 'unrecognised';		
						$view_data['debt'] = '';
						$view_data['paid'] = $currency_symbol.number_format($item_data['amount'],2,'.',',');
						$view_data['link_section'] = false;
					}
					  
					 // $view_data =  array_replace($view_data,$item_data);

                      $this->load->view('contacts/snippets/statement_list_row',$view_data); 

                  }
				  
				  
				  
              ?>
              <tr class="table_spacer">
                <td colspan="4"></td>                 
              </tr>
              <tr class="table_break">
                <td colspan="4"></td>                 
              </tr>
              <tr class="table_spacer">
                <td colspan="4"></td>                 
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
              </tr>
               <tr class="table_totals">
                <td>Paid Total</td>
                <td></td>
                <td></td>
                <td><?php echo $currency_symbol.number_format( $totals['payments'],2,'.',','); ?></td>                   
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
                <td colspan="4"></td>                 
              </tr>
              <tr class="table_break">
                <td colspan="4"></td>                 
              </tr>
               <tr class="table_totals">
                <td>Account Balance</td>
                <td></td>
                <td></td>
                <td><?php 
					
					if(($totals['invoices'] - $totals['payments'] - $totals['credit_note']) >=0){
						echo $currency_symbol.number_format(($totals['invoices'] - $totals['payments'] - $totals['credit_note']),2,'.',','); 
					}else{
						echo '('.$currency_symbol.number_format(($totals['invoices'] - $totals['payments'] - $totals['credit_note'])*-1,2,'.',',').')'; 
					}
					?>
                    
                </td>                   
              </tr>
              <tr class="table_break">
                <td colspan="4"></td>                 
              </tr>
            </table>
            <?php // var_dump($totals); ?>
       <!--  </div> -->
    </div>
</div>
<!-- END list area -->  
	<!-- END main content area -->
</div> 