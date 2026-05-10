<?php

	//var_dump($data['invoices'][0]);
	
	if(isset($add)){
		echo '<li class="heading greyed-bg">'.$add['text'].'</li>';
	}
	
	if(isset($heading)){
		echo '<li class="heading">'.$heading.'</li>';
	}

	if(isset($data)){
		//var_dump($data);
		
		foreach($data as $item_group_key => $item_group_data){
			//var_dump($piggyback);
			echo '<li class="heading">'.ucwords($item_group_key).'</li>';
			foreach($item_group_data as $item_key => $item_data){
				//var_dump($item_data);
				echo '<li><a onclick="searchRedirect(event,this.getAttribute(\'href\'))" class="searchRedirect" href="'.base_url($item_group_key.'/'.$item_data['id']).'">';
				foreach($item_data as $item_element_key => $item_element_value){
					
					if(isset($item_element_value) && $item_element_value != ''){
						
						if($item_element_key != 'id' && $item_element_key != 'currency_id'){
							
							if($item_element_key == 'invoice_number'){
								$item_element_value = '<b>#'.$item_element_value.'</b>';
							}elseif($item_element_key == 'reference'){
								$item_element_value = '(Ref: '.$item_element_value.')';
							}elseif($item_element_key == 'total_amount'){
								$item_element_value = ', '.$piggyback['currencies'][1]['currency_symbol'].' '.number_format($item_element_value,2,'.',',').'';
								//echo 'cid'.$item_data['currency_id'];
							}elseif($item_element_key == 'contact_id'){				
								$item_element_value = '- '.$piggyback['contacts'][$item_element_value]['organisation'].'';
							}
									
							echo ' '.preg_replace("/".$input."/i", '<span class="searchHilight">$0</span>', $item_element_value).' ';
						}
						
					}
					
				}
				echo '</a></li>';
			}
			echo '<li class="divider" role="separator"></li>';
		}
	}
?>