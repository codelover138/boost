<?php

	//print_r(strtotime($due_date));
	//format date into correct format
	$date_created = date("M j, g:ma",strtotime($date_created));
	//format amount and add currency symbol
	$amount = $currency_symbol.number_format($total_amount,2,'.',',');
	//get days from last modification
	$last_modified_days = ((strtotime("now") - strtotime($date_modified)) / (60*60*24)) ;
	if($last_modified_days<1){
		$last_modified = 'TODAY';
	}elseif($last_modified_days<2){
		$last_modified = 'YESTERDAY';
	}else{
		$last_modified = floor($last_modified_days).' DAYS AGO';
	}
	//create html for different Statuses
	$status = strtoupper($status);
 	//echo $amount_outstanding.'<'.$total_amount;
	if($content_status != 'active'){
		$status_html = '<button type="button" class="btn btn-warning btn-xs listStatusBtn">'.strtoupper($content_status).'</button>';
	}elseif($status == 'ACCEPTED'){
		$status_html = '<button type="button" class="btn btn-success btn-xs listStatusBtn">'.$status.'</button>';
	}elseif($status == 'INVOICED'){
		$status_html = '<button type="button" class="btn btn-success btn-xs listStatusBtn">'.$status.'</button>';
	}elseif(($status == 'SENT' || $status == 'PARTIAL') && (int)strtotime($due_date) < (int)(time()-(60*60*24))){		
		$status_html = '<button type="button" class="btn btn-danger btn-xs listStatusBtn">EXPIRED</button>';
	}elseif($status == 'DECLINED'){		
		$status_html = '<button type="button" class="btn btn-warning btn-xs listStatusBtn">DECLINED</button>';
	}else{
		$status_html = '<span class="smallGreyLightened list-small-top-margin">'.$status.'</span>';
	}

	//client info
	$client_company_name = $contact['organisation'];
	
?>


 <tr class="list_item_row">
    <td><input type="checkbox" name="bulk_action_CB[<?php echo $id; ?>]" id="bulk_action_CB[<?php echo $id; ?>]" class="bulkItemRow_cb"  /></td>
    <td><a class="greyLink" href="<?php echo base_url('estimates/'.$id); ?>">#<?php echo $estimate_number; ?></a>
    <span class="listDateItalic"><?php echo $date_created; ?></span>
    </td>
    <td><a class="greyLink" href="<?php echo base_url('estimates/'.$id); ?>"><?php echo $client_company_name; ?></a>
    <div class="listHoverOptions hidden-xs"><a href="<?php echo base_url('estimates/edit/'.$id); ?>">Edit</a><?php
			#disabled untl archived section is built
			if(false){
			?>| <a class="single_archive" href="<?php echo base_url('estimates/bulk/archive/'.$id); ?>">Archive</a><?php
			// end disabled
			}
			?>| <a href="<?php echo base_url('estimates/'.$id); ?>">View</a>|<a href="<?php echo base_url('estimates/duplicate/'.$id); ?>">Duplicate</a></div>
    <span class="listDateItalic visible-xs-block"><?php echo $amount; ?></span>
    </td>
    <td class="hidden-xs"><?php echo $amount; ?></td>
    <td class="hidden-xs"><span class="smallGreyLightened list-small-top-margin status_display"><?php echo $status_html; ?></span></td>
    <td class="hidden-xs"><span class="smallGreyLightened list-small-top-margin"><?php echo $last_modified; ?></span></td>
   <td>
        <div style="position:relative;" class="nav nav-pills">
            <button  role="button" aria-haspopup="true" data-toggle="dropdown" type="button" class="btn btn-info btn-xs"> <span class="caret"></span> </button>
            <ul class="dropdown-menu">
           	  <li><a href="<?php echo base_url('estimates/edit/'.$id); ?>">Edit</a></li> 
              <?php if(in_array('send_estimates',$user_data['permissions'])){ ?>
             		<li><a href="<?php echo base_url('estimates/modal/send/'.$id); ?>" data-redirect-url="" class="open-modal" role="menuitem">Send</a></li>
              <?php } ?>
              <?php if($status != 'INVOICED'){ ?>
              		<li><a href="<?php echo base_url('invoices/convert/'.$id); ?>">Convert to Invoice</a></li>
              <?php } ?>
			  <?php if($status == 'DRAFT'){ ?>
              		<li><a href="<?php echo base_url('estimates/modal/mark/'.$id.'/sent'); ?>" data-redirect-url="" class="open-modal">Mark as Sent</a></li>
              <?php } ?>                            
              <?php if($status != 'ACCEPTED'){ ?>
                   <li><a href="<?php echo base_url('estimates/modal/mark/'.$id.'/accepted'); ?>" data-redirect-url="" class="open-modal">Mark as Accepted</a></li>
              <?php } ?>
              <?php if($status != 'DECLINED'){ ?>
                   <li><a href="<?php echo base_url('estimates/modal/mark/'.$id.'/declined'); ?>" data-redirect-url="" class="open-modal">Mark as Declined</a></li>
              <?php } ?>
              <?php if($status != 'INVOICED'){ ?>
                   <li><a href="<?php echo base_url('estimates/modal/mark/'.$id.'/invoiced'); ?>" data-redirect-url="" class="open-modal">Mark as Invoiced</a></li>
              <?php } ?>
                                        
              <li><a href="<?php echo base_url('estimates/duplicate/'.$id); ?>">Duplicate Estimate</a></li>
              <li><a href="<?php echo base_api_url('export/estimates/'.$id); ?>"  class="exportToPDF" data-modal-body="PDF successfully created." data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>">Download as PDF</a></li>
            </ul>
        </div>
    </td>
  </tr>
