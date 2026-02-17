<?php
	if(isset($add)){
		echo '<li class="heading greyed-bg"><a href="'.base_url('modal/item/add').'" style="" class="open_add_sales_item_modal">'.$add['text'].'</a></li>';
	}
	
	if(isset($heading)){
		echo '<li class="heading">'.$heading.'</li>';
	}

	if(isset($data)){
		foreach($data as $item_key => $item_data){
			echo '<li role="presentation"><a href="#" onclick="setInvoiceItem(event,this)" data-item="'.$item_data['item_name'].'" data-description="'.$item_data['description'].'" data-amount="'.$item_data['rate'].'" tabindex="-1" role="menuitem"><strong>'.preg_replace("/".$input."/i", '<span class="searchHilight">$0</span>', $item_data['id']).'</strong> - '.preg_replace("/".$input."/i", '<span class="searchHilight">$0</span>', $item_data['item_name']).'</a></li>';
		}
	}
?>