<?php

	//format date into correct format
	$doc_date = date("d/m/Y",strtotime($date));
	//format amount and add currency symbol
	$amount = $currency_symbol.number_format($total_amount,2,'.',',');


	//client info
	$client_company_name = $contact['organisation'];
	
?>


 <tr class="list_item_row">
   <!-- <td><input type="checkbox" name="bulk_action_CB[<?php echo $id; ?>]" id="bulk_action_CB[<?php echo $id; ?>]" class="bulkItemRow_cb"  /></td> -->
    <td><a class="greyLink" href="<?php echo base_url('expenses/'.$id); ?>"><?php echo $doc_date; ?></a>
     <div class="listHoverOptions hidden-xs"><a href="<?php echo base_url('expenses/edit/'.$id); ?>">Edit</a><?php
			#disabled untl archived section is built
			if(false){
			?> |<a class="single_archive" href="<?php echo base_url('expenses/bulk/archive/'.$id); ?>">Archive</a><?php
			// end disabled
			}
			?> |<a href="<?php echo base_url('expenses/'.$id); ?>">View</a></div>
    </td>
    <td><a class="greyLink" href="<?php echo base_url('contacts/'.$id); ?>"><?php echo $vendour_name; ?></a>
   
    <span class="listDateItalic"><?php echo $category_name; ?></span>
    </td>
    <td class="hidden-xs"><?php echo $amount; ?></td>
    <td class="hidden-xs">
    	<?php if(isset($file_name) && $file_name != ''){ ?>
        	<a class="openImageModal" target="_blank" href="<?php echo $file_name; ?>"><img class="doc-attachment" src="<?php echo base_url('images/clip.png'); ?>" /></a><a class="openImageModal" target="_blank" href="<?php echo $file_name; ?>">view</a></td>
        <?php } ?>
   <td class="text-right">
        <div style="position:relative;" class="nav nav-pills pull-right">
            <button  role="button" aria-haspopup="true" data-toggle="dropdown" type="button" class="btn btn-info btn-xs"> <span class="caret"></span> </button>
            <ul class="dropdown-menu">
              <li><a href="<?php echo base_url('expenses/'.$id); ?>">View</a></li>
              <li><a href="<?php echo base_url('expenses/edit/'.$id); ?>">Edit</a></li>
              <li><a href="<?php echo base_url('expenses/duplicate/'.$id); ?>">Duplicate</a></li>
              <li><a href="<?php echo base_api_url('export/expenses/'.$id); ?>"  class="exportToPDF" data-modal-body="PDF successfully created." data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>">Download as PDF</a></li>
            </ul>
        </div>
    </td>
  </tr>
