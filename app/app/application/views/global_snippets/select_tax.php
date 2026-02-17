<label for="items[<?php echo $data['id']; ?>]['tax']" class="control-label pull-left-sm visible-xs-block">Tax:</label>
<select onchange="calculateInvoiceItemAmounts()" name="items[<?php echo $data['id']; ?>]['tax']" id="items[<?php echo $data['id']; ?>]['tax']" class="selectpicker full-width Tax">
     <?php
		if(!$current_tax_id || !isset($current_tax_id) || $current_tax_id == '' || $current_tax_id == '0' ){
			echo '<option disabled="disabled" value="0" selected="selected">Choose Tax</option>';
		}else{
			echo '<option disabled="disabled" value="0">Choose Tax</option>';
		}
	?>
    <option value="0" data-tokens="<?php echo base_url('modal/tax/add/'.$data['id']); ?>" class="open-modal">Add a Tax Item</option>
    <?php						
        foreach($taxes as $tax_data){
            if($tax_data['id'] == $current_tax_id){
                echo '<option  selected="selected" data-tax-percentage="'.$tax_data['percentage'].'" value="'.$tax_data['id'].'">'.$tax_data['tax_name'].'</option>';
            }else{
                echo '<option data-tax-percentage="'.$tax_data['percentage'].'" value="'.$tax_data['id'].'">'.$tax_data['tax_name'].'</option>';
            }
        }
    ?>
</select>