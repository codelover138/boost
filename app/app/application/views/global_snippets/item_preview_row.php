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
        <span class="visible-xs-inline visible-sm-inline">Qty:</span> <?php echo $quantity ;?>
    </div>
     <div class="col-xs-4 col-sm-4 col-md-2 text-right-xs">
        <span class="visible-xs-inline visible-sm-inline">Rate:</span> <strong><?php echo number_format($rate,2,'.',',');?></strong>
    </div>
     <div class="col-xs-4 col-sm-4 col-md-2 text-right-xs">
       <span class="visible-xs-inline visible-sm-inline">Total:</span>  <strong><?php echo number_format($total_amount,2,'.',',');?></strong>
    </div>
</div>