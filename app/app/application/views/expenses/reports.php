<?php
$start_date = $request['filters']['formatted_start_date'];
$end_date = $request['filters']['formatted_end_date'];
$company_data = array_values($request['piggyback']['organizations'])[0];
?>

<div class="container-fluid bg-white doc-spaced">   
    <div class="mainFilterContainer">
        <div class="container-fluid filters">
            <div class="filter_selection_container">
            	<form method="get" action="" autocomplete="off">
                    <div class="form-group col-xs-12 col-sm-3  pull-left-sm">
                        <label for="date" class="control-label pull-left-sm">Starting</label>
                        <div class="col-sm-12 pull-left-sm no-gutter-xs  clear-left-sm">
                            <input type="text" class="form-control datepicker" name="start_date" id="start_date" value="<?php echo $start_date; ?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-3  pull-left-sm">
                        <label for="date" class="control-label pull-left-sm">Ending</label>
                        <div class="col-sm-12 pull-left-sm no-gutter-xs  clear-left-sm">
                            <input type="text" class="form-control datepicker" name="end_date" id="end_date" value="<?php echo $end_date; ?>">
                        </div>
                    </div>
                    <div style="text-align:right;" class="form_section container-fluid filter_buttons_right">
                    	 <!--<a class="print_icon" href="#">Print as PDF</a>
                         <a class="export_icon" href="#">Send</a>-->
                         <button class="btn btn-success saveButton statementFilter" type="button" data-redirect-url="<?php echo base_url('expenses'); ?>">Update</button>
                    </div>
                    <div class="clearfix"></div> 
                </form>
            </div>                                            
        </div>
    </div>   
    
    <div class="container-fluid reportsHeadingsContainer">
    	<h2>Expenses by Category</h2>
        <h3><?php echo $company_data['company_name']; ?></h3>
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
                <td></td>
                <td>Vendor</td>
                <td>Client</td>
                <td>Author</td> 
                <td>Notes</td>  
                <td class="text-right">Amount</td>                   
              </tr>
              
              <?php			  
				  $view_data = array();				  
				  foreach($request['data'] as $cat_key => $cat_data){
						$this->load->view('expenses/snippets/list_row',$cat_data); 																
				  }			  
			  ?>
             

            </table>
            <?php // var_dump($totals); ?>
       <!--  </div> -->
    </div>
</div>
<!-- END list area -->  
