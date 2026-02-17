<?php
$settings_data = $request['data'];
//var_dump($settings_data);

?>

<!-- content area -->
    <form action="testForm.php"  autocomplete="off"> 
    <div class="container-fluid">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="<?php echo base_url('settings/organization'); ?>">Business Setup</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/taxes'); ?>">Taxes</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/theme'); ?>">Theme and Logo</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/templates'); ?>">Templates</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/users'); ?>">Users</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/roles'); ?>">Roles</a></li>
            <li role="presentation" class="active"><a href="<?php echo base_url('settings/items'); ?>">Items</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/emails'); ?>">Emails</a></li>
        </ul>
    </div>
    <div class="container-fluid bg-white doc-spaced">
       <div class="col-xs-12">
            <h3 class="pull-left-xs">Items</h3>
             <button data-redirect-url="<?php echo base_url('settings/items'); ?>" class="btn btn-default pull-right-xs open-modal" href="<?php echo base_url('settings/modal/items/add'); ?>" type="button">Add New Item &nbsp; &nbsp; +</button>
       </div>
       
      <div class="container-fluid">
        
       <!-- <div class="table-responsive">-->
            <table class="table listInvoiceTable" style="font-weight:bold;">
              <tr class="table_header">
                <td>No.</td>
                <td>Name</td>              
                <td>Description</td>
                <td>Rate</td>
                <td></td>
              </tr>
             <?php
			  
			  $count = 1;
			  foreach($settings_data as $item_key => $item_data ){
				  
			  ?>
             <tr>                         	
                <td><?php echo $count; ?>.</td>
                <td><?php echo ucwords($item_data['item_name'].' '.$item_data['last_name']); ?>
                     <div class="listHoverOptions hidden-xs"><a class="open-modal" href="<?php echo base_url('settings/modal/items/edit/'.$item_data['id']); ?>">Edit</a>|<a  class="open-modal" href="<?php echo base_url('settings/modal/items/delete/'.$item_data['id']); ?>">Trash</a></div>
                     <div class="visible-xs-block list-small-top-margin"><a class="greyLink smallGreyLightened" href="mailto:<?php echo $item_data['email']; ?>"><?php echo $item_data['email']; ?></a></div>
                </td>
                <td>
                    <span class="listDateItalic">
					<?php 
						echo $item_data['description'];					
					?>
                    </span>
                </td>
                <td>
                   <?php echo $item_data['rate']; ?>
                </td>
				<td>
                    <div style="position:relative;" class="nav nav-pills pull-right">
                        <button  role="button" aria-haspopup="true" data-toggle="dropdown" type="button" class="btn btn-info btn-xs"> <span class="caret"></span> </button>
                       <ul class="dropdown-menu">
                          <li><a class="open-modal" href="<?php echo base_url('settings/modal/items/edit/'.$item_data['id']); ?>">Edit</a></li>
                          <li><a class="open-modal" href="<?php echo base_url('settings/modal/items/delete/'.$item_data['id']); ?>">Trash</a></li>
                       </ul>
                    </div>
                </td>
              </tr> 
              <?php
			  		$count++;
			  }
			  ?>                    
            </table>

       <!--  </div> -->
    </div>
    
    <!-- END list area -->   
     
     <div class="clearfix formSpacer"></div>

     
    <div class="form_section container-fluid" style="text-align:right;">
        <a href="<?php echo base_url('settings/emails'); ?>" type="button" class="btn btn-success saveButton">Continue</a>
    </div>
       
    </div>
    
   
     </form> 
    
<!-- END content area --> 