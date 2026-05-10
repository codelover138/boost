<?php	
	$instance_data = $request['contact'];
	$id = $instance_data['id'];
?>

<ul class="nav nav-pills pull-right">     
    <li class="dropdown padded" role="presentation">
        <button  aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="btn btn-default btn-lg pull-right" type="button" href="#">
          Actions &nbsp; &nbsp; +
        </button>
        <ul class="dropdown-menu right-aligned-arrow" role="menu">       	
           <li><a href="<?php echo base_url('contacts/modal/send/'.$id.$filters['filter_string']); ?>" class="open-modal" data-redirect-url="<?php echo base_url('invoices/'.$id); ?>"  data-redirect-without-id="true">Send Statement</a></li> 
           <li><a href="<?php echo base_url('contacts/edit/'.$id); ?>">Edit Contact</a></li> 
           <li><a href="<?php echo base_url('contacts/'.$id); ?>">View Contact</a></li>          
        </ul>        
    </li>
   <?php 
		//additional modules must be added to this array as they are developed.
		//This so that accounts menu item does not show if its child permissions are not available
		if(array_intersect(array('invoices','estimates','recurring_invoices','credit_notes','contacts'),$user_data['permissions'])){ 
	?>
    <li class="dropdown" role="presentation">
        <button  aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="btn btn-success btn-lg pull-right" type="button" href="#">
          Create New &nbsp; &nbsp; +
        </button>
        <ul  role="menu" class="dropdown-menu right-aligned-arrow">
             <?php if(in_array('invoices',$user_data['permissions'])){ ?>
                <li><a href="<?php echo base_url("invoices/create"); ?>">Invoice</a></li>
             <?php } ?>
             <?php if(in_array('estimates',$user_data['permissions'])){ ?>
                <li role="presentation"><a href="<?php echo base_url("estimates/create"); ?>" role="menuitem">Estimate</a></li>
             <?php } ?>
             <?php if(in_array('recurring_invoices',$user_data['permissions'])){ ?>
                <!--<li role="presentation"><a href="#" role="menuitem">Recurring Invoice</a></li> -->
             <?php } ?>
             <?php if(in_array('credit_notes',$user_data['permissions'])){ ?>
                <li role="presentation"><a href="<?php echo base_url("credit_notes/create"); ?>" role="menuitem">Credit note</a></li>
             <?php } ?>
             <?php if(in_array('contacts',$user_data['permissions']) && in_array('create_contacts',$user_data['permissions'])){ ?>
                <li role="presentation"><a href="<?php echo base_url('contacts/create'); ?>" role="menuitem">Contact</a></li>
             <?php } ?>
			 <?php if(in_array('expenses',$user_data['permissions']) && in_array('create_expenses',$user_data['permissions'])){ ?>
				<li role="presentation"><a href="<?php echo base_url('expenses/create'); ?>" role="menuitem">Expense</a></li>
			 <?php } ?>
        </ul>        
    </li>
    <?php } ?>
</ul>