<?php
	//var_dump($request);
?>

<div class="mainFilterContainer">
    <div class="container-fluid filters">
        <a class="boost_infobox" href="#">
            <div role="alert" class="alert alert-info alert-dismissible fade in pull-right-sm pull-none-xs">
              <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times;</span></button>
              <strong>Did you know</strong> you can set a Recurring Invoice
            </div>
        </a>
        
        <select class="selectpicker" id="bulkActions">
            <option disabled="disabled" value="Bulk Actions" selected="selected">Bulk Actions</option>
            <option value="<?php echo base_url('estimates/bulk/payment'); ?>">Add Payment</option>
            <option value="<?php echo base_url('estimates/bulk/mark_sent'); ?>">Mark as Sent</option>
            <option value="<?php echo base_url('estimates/bulk/export_pdf'); ?>">Export as PDF</option>
           <?php
			#disabled untl archived section is built
			if(false){
			?> 
            <option value="<?php echo base_url('estimates/bulk/archive'); ?>">Archive</option>
       		<?php
			// end disabled
			}
			?> 
        </select>
        
        <button type="button" class="btn btn-default">Apply</button>
        
        <select class="selectpicker sort_by_dd">
            <option disabled="disabled" value="Sort By"  selected="selected">Sort By</option>
            <?php
				foreach($sort_by as $sort_key => $sort_data){
					if($sort_data['active'] === true){
						echo '<option value="'.$sort_data['link'].'" selected="selected">'.$sort_data['display'].'</option>';
					}else{
						echo '<option value="'.$sort_data['link'].'">'.$sort_data['display'].'</option>';
					}
				}
			?>
        </select>
                                                
    </div>
</div>   
<!-- list area -->
<div class="container-fluid">
    
   <!-- <div class="table-responsive">-->
        <table class="table listInvoiceTable" style="font-weight:bold;">
          <tr class="table_header">
            <td><input type="checkbox" name="bulk_select_all_cb" id="selectUnselect_all_cb" /></td>
            <td>Estimate</td>
            <td>Client</td>
            <td class="hidden-xs">Amount</td>
            <td class="hidden-xs">Status</td>
            <td class="hidden-xs">Last Updates</td>                    
            <td>&nbsp;</td>
          </tr>
          <?php 
		  	//var_dump($request['data']);
			  foreach($request['data'] as $item_key => $item_data){
				 // var_dump($item_data);
				  $this->load->view('estimates/snippets/list_row',$item_data); 
			  }
		  ?>
        </table>
   <!--  </div> -->
</div>

<!-- END list area -->  

<div class="text-center-xs">
	<?php
		if(count($request['pagination']['pages_links'])>1){
			$this->load->view('global_snippets/pagination',$pagination_data);
		}
	?>
</div> 