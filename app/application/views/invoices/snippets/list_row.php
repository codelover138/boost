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
	}elseif($status == 'PAID'){
		$status_html = '<button type="button" class="btn btn-success btn-xs listStatusBtn">'.$status.'</button>';
	}elseif(($status == 'SENT' || $status == 'VIEWED' || $status == 'PARTIAL') && (int)strtotime($due_date) < (int)(time()-(60*60*24))){		
		$status_html = '<button type="button" class="btn btn-danger btn-xs listStatusBtn">OVERDUE</button>';
	}elseif($status == 'PARTIAL'){		
		$status_html = '<button type="button" class="btn btn-warning btn-xs listStatusBtn">PARTIAL</button>';
	}else{
		$status_html = '<span class="smallGreyLightened list-small-top-margin">'.$status.'</span>';
	}

	//client info
	$client_company_name = $contact['organisation'];
	
?>


 <tr class="list_item_row">
    <td><input type="checkbox" name="bulk_action_CB[<?php echo $id; ?>]" id="bulk_action_CB[<?php echo $id; ?>]" class="bulkItemRow_cb"  /></td>
    <td><a class="greyLink" href="<?php echo base_url('invoices/'.$id); ?>">#<?php echo $invoice_number; ?></a>
    <span class="listDateItalic"><?php echo $date_created; ?></span>
    </td>
    <td><a class="greyLink" href="<?php echo base_url('invoices/'.$id); ?>"><?php echo $client_company_name; ?></a>
    <div class="listHoverOptions hidden-xs"><a href="<?php echo base_url('invoices/edit/'.$id); ?>">Edit</a><?php
			#disabled untl archived section is built
			if(false){
			?> |<a class="single_archive" href="<?php echo base_url('invoices/bulk/archive/'.$id); ?>">Archive</a><?php
			// end disabled
			}
			?> |<a href="<?php echo base_url('invoices/'.$id); ?>">View</a>|<a href="<?php echo base_url('invoices/duplicate/'.$id); ?>">Duplicate</a></div>
    <span class="listDateItalic visible-xs-block"><?php echo $amount; ?></span>
    </td>
    <td class="hidden-xs"><?php echo $amount; ?></td>
    <td class="hidden-xs"><span class="smallGreyLightened list-small-top-margin status_display"><?php echo $status_html; ?></span></td>
    <td class="hidden-xs"><span class="smallGreyLightened list-small-top-margin"><?php echo $last_modified; ?></span></td>
   <td>
        <div style="position:relative;" class="nav nav-pills">
            <button  role="button" aria-haspopup="true" data-toggle="dropdown" type="button" class="btn btn-info btn-xs"> <span class="caret"></span> </button>
            <ul class="dropdown-menu">
              <?php if($status != 'PAID'){ ?>
             	 <li><a href="<?php echo base_url('invoices/modal/payment/'.$id); ?>" class="open-modal">Add Payment</a></li>
              <?php } ?>
              <?php if(in_array('send_invoices',$user_data['permissions'])){ ?>
              	<li><a href="<?php echo base_url('invoices/modal/send/'.$id); ?>" data-redirect-url="" class="open-modal" role="menuitem">Send</a></li>              
              <?php } ?>
			  <?php if($status == 'DRAFT'){ ?>
              	<li><a href="<?php echo base_url('invoices/modal/mark/'.$id.'/sent'); ?>"  data-redirect-url="" class="open-modal">Mark as Sent</a></li>
              <?php } ?>
              <li><a href="<?php echo base_url('invoices/edit/'.$id); ?>">Edit</a></li>
              <?php if(in_array('credit_notes',$user_data['permissions'])){ ?>
              	<li><a href="<?php echo base_url('credit_notes/create/'.$id); ?>">Apply Credit note</a></li>
              <?php } ?>
              <?php if($status != 'PAID'){ ?>
             	<li><a href="<?php echo base_url('invoices/modal/reminder/'.$reminder.'/'.$id); ?>" data-reminder-item-id="<?php echo $id; ?>" class="open-reminder-modal">Set Payment Reminder</a></li>
              <?php } ?>
              <li><a href="<?php echo base_url('invoices/duplicate/'.$id); ?>">Duplicate Invoice</a></li>
              <li><a href="<?php echo base_api_url('export/invoices/'.$id); ?>"  class="exportToPDF" data-modal-body="PDF successfully created." data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>">Download as PDF</a></li>
            </ul>
        </div>
    </td>
  </tr>
