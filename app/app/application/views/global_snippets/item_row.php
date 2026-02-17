<?php
	if(!isset($data['id'])){
		$data['id'] = $current_item_count;
		$data['item_name'] = '';
		$data['description'] = '';
		$data['quantity'] = 0;
		$data['rate'] = 0;
		$data['tax'] = 0;
	}	
?>

<div id="item_<?php echo $data['id']; ?>" class="row dragRow itemRow" ondrop="drop(event,this)"   ondragover="drag_over_test(event,this)" ondragleave="drag_out_test(event,this)">
    <img class="dragArrow" src="<?php echo base_url() ?>/images/drag-arrows.png" draggable="true" ondragstart="drag_start(event,this)"/>
    <div class="col-md-2 nav-pills">
        <label for="items[<?php echo $data['id']; ?>]['item_name']" class="control-label pull-left-sm visible-xs-block">Item:</label>
        <input  data-toggle="dropdown" onclick="event.stopPropagation()"  name="items[<?php echo $data['id']; ?>]['item_name']" id="items[<?php echo $data['id']; ?>]['item_name']" class="form-control ajax_ListItemSearch Item"  value="<?php echo $data['item_name']; ?>" placeholder="Item">
        <ul role="menu" class="dropdown-menu autoSearch">                
        </ul>
    </div>
    <div class="col-md-4">
        <label for="items[<?php echo $data['id']; ?>]['description']" class="control-label pull-left-sm visible-xs-block">Description:</label>
        <input name="items[<?php echo $data['id']; ?>]['description']" id="items[<?php echo $data['id']; ?>]['description']" class="form-control Description" placeholder="Description" value="<?php echo $data['description']; ?>">
    </div>
    <div class="col-md-1">
        <label for="items[<?php echo $data['id']; ?>]['quantity']" class="control-label pull-left-sm visible-xs-block">Qty:</label>
        <input onkeyup="calculateInvoiceItemAmounts()" name="items[<?php echo $data['id']; ?>]['quantity']" id="items[<?php echo $data['id']; ?>]['quantity']" class="form-control centered_nopad Qty" value="<?php echo $data['quantity']; ?>">
    </div>
     <div class="col-md-1">
        <label for="items[<?php echo $data['id']; ?>]['rate']" class="control-label pull-left-sm visible-xs-block">Price:</label>
        <input onkeyup="calculateInvoiceItemAmounts()" name="items[<?php echo $data['id']; ?>]['rate']" id="items[<?php echo $data['id']; ?>]['rate']" class="form-control centered_nopad Price" placeholder="0" value="<?php echo $data['rate']; ?>">
    </div>
     <div class="col-md-2">
     	<?php
			$tax_data['taxes'] = $request['piggyback']['taxes'];
			$tax_data['current_tax_id'] = @$data['tax'];
			$tax_data['data'] = @$data;
			$this->load->view('global_snippets/select_tax',$tax_data);  
		?>     	
    </div>
     <div class="col-md-2 amount">
       <span class="amountVal">0.00</span>
        <a onclick="removeInvoiceItem(this);" class="remove_item"></a>
    </div>           
</div>