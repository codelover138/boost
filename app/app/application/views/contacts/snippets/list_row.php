<?php

	//print_r(strtotime($due_date));
	//format date into correct format
	$date_created = date("M j, g:ma",strtotime($date_created));
	
	//format and concatentate Name and Surename
	$contact_name = ucwords($first_name.' '.$last_name);

	//create html for different Statuses
	$status = strtoupper($status);

	//client info
	$contact_email = $email;
	
	//var_dump($account);
?>


 <tr class="list_item_row">
    <td><input type="checkbox" name="bulk_action_CB[<?php echo $id; ?>]" id="bulk_action_CB[<?php echo $id; ?>]" class="bulkItemRow_cb"  /></td>
    <td class="hidden-xs"><a class="greyLink" href="<?php echo base_url('contacts/'.$id); ?>"><?php  echo $contact_type; ?></a>
    <span class="listDateItalic"><?php echo $date_created; ?></span>
    </td>
    <td><a class="greyLink" href="<?php echo base_url('contacts/'.$id); ?>"><?php echo $organisation; ?></a>
    <div class="listHoverOptions hidden-xs"><a href="<?php echo base_url('contacts/edit/'.$id); ?>">Edit</a>|<a href="<?php echo base_url('contacts/'.$id); ?>">View</a><?php
			#disabled untl archived section is built
			if(false){
		?>| <a class="single_archive" href="<?php echo base_url('contacts/bulk/archive/'.$id); ?>">Archive</a><?php 
		} 
	    ?>| <a href="<?php echo base_url('contacts/statements/'.$id); ?>">Statements</a></div>
    
    </td>
    <td class="hidden-xs"><?php echo $contact_name; ?></td>
    <td><a class="greyLink" href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a>
    	<span class="listDateItalic visible-xs-block"><?php echo $contact_name; ?></span>
    </td>
    <td class="hidden-xs"><?php echo $land_line; ?></td>
   <td>
        <div style="position:relative;" class="nav nav-pills">
            <button  role="button" aria-haspopup="true" data-toggle="dropdown" type="button" class="btn btn-info btn-xs"> <span class="caret"></span> </button>
            <ul class="dropdown-menu">
            	<li><a href="<?php echo base_url('contacts/edit/'.$id); ?>">Edit</a></li>
            	<li><a href="<?php echo base_url('contacts/'.$id); ?>">View</a></li>
            	<?php
					#disabled untl archived section is built
					if(false){
				?>
            		<li><a class="single_archive" href="<?php echo base_url('contacts/bulk/archive/'.$id); ?>">Archive</a></li>
                <?php
				// end disabled
				}
				?> 
                <li><a href="<?php echo base_url('contacts/statements/'.$id); ?>">Statements</a></li>
           		<li><a href="<?php echo base_api_url('export/contacts/'.$id); ?>"  class="exportToPDF" data-modal-body="PDF successfully created." data-close-delay-seconds="1" data-modal-heading="Success" data-modal-url="<?php echo base_url('modal/notice'); ?>">Download as PDF</a></li>
            </ul>
        </div>
    </td>
  </tr>
