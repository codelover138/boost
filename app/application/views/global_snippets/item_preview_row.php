<?php
$display_quantity = isset($quantity) ? $quantity : (isset($qty) ? $qty : null);
$display_rate = isset($rate) ? (float)$rate : 0.0;
$display_total = isset($total_amount) ? (float)$total_amount : 0.0;

if (($display_quantity === null || $display_quantity === '') && $display_rate > 0 && $display_total > 0) {
    $display_quantity = $display_total / $display_rate;
}

if (($display_total <= 0) && is_numeric($display_quantity) && $display_rate > 0) {
    $display_total = (float)$display_quantity * $display_rate;
}

if ($display_quantity === null || $display_quantity === '') {
    $display_quantity = 0;
}
?>
<div class="row previewRow">
    <div class="col-md-6">
        <?php 
			if(isset($item_name)){
				echo '<div class="previewItem">'.$item_name.'</div>';
			}
		?>
         <?php 
			if(isset($description)){
				echo '<div class="text-light">'.$description.'</div>';
			}
		?>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-2 text-light text-left-xs text-center-sm">
        <span class="visible-xs-inline visible-sm-inline">Qty:</span> <?php echo $display_quantity; ?>
    </div>
     <div class="col-xs-4 col-sm-4 col-md-2 text-right-xs">
        <span class="visible-xs-inline visible-sm-inline">Rate:</span> <strong><?php echo number_format($display_rate,2,'.',',');?></strong>
    </div>
     <div class="col-xs-4 col-sm-4 col-md-2 text-right-xs">
       <span class="visible-xs-inline visible-sm-inline">Total:</span>  <strong><?php echo number_format($display_total,2,'.',',');?></strong>
    </div>
</div>
