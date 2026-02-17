<?php
	if(!isset($type) || $type == 'client'){
		$type = 'client';
		$type_name = 'contact';
	}else{
		$type_name = $type;
	}

?>

<select id="<?php echo $type_name; ?>_id" name="<?php echo $type_name; ?>_id" class="selectpicker full-width">
    <?php
		if(!$current_id || !isset($current_id) || $current_id == '' || $current_id == '0' ){
			echo '<option disabled="disabled" value="0" selected="selected">Choose a '.ucwords($type).'</option>';
		}else{
			echo '<option disabled="disabled" value="0">Choose a '.ucwords($type).'</option>';
		}
	?>
    <option data-tokens="<?php echo base_url('modal/contact/add/'.$type); ?>" class="open-modal" value="0">Add a <?php echo ucwords($type); ?></option>
    <?php						
        foreach($contacts as $contact_data){
            if($contact_data['id'] == @$current_id){
                echo '<option selected="selected" value="'.$contact_data['id'].'">'.$contact_data['organisation'].'</option>';
            }else{
                echo '<option value="'.$contact_data['id'].'">'.$contact_data['organisation'].'</option>';
            }
        }
    ?>
</select>