<?php
$settings_data = $request['data'];
//var_dump($settings_data);

?>

<!-- content area -->
    <form action="testForm.php"  autocomplete="off"> 
    <div class="container-fluid">
        <?php
$is_super_admin = (isset($user_data['email']) && ($user_data['email'] == 'babu313136@gmail.com' || $user_data['email'] == 'admin@boostaccounting.com'));
?>
        <ul class="nav nav-tabs" role="tablist">
            <?php if (!$is_super_admin): ?>
            <li role="presentation"><a href="<?php echo base_url('settings/organization'); ?>">Business Setup</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/taxes'); ?>">Taxes</a></li>
            <?php
endif; ?>
            <li role="presentation"><a href="<?php echo base_url('settings/theme'); ?>">Theme and Logo</a></li>
            <?php if (!$is_super_admin): ?>
            <li role="presentation"><a href="<?php echo base_url('settings/templates'); ?>">Templates</a></li>
            <?php
endif; ?>
            <li role="presentation" class="active"><a href="<?php echo base_url('settings/users'); ?>">Users</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/roles'); ?>">Roles</a></li>
            <?php if (!$is_super_admin): ?>
            <li role="presentation"><a href="<?php echo base_url('settings/items'); ?>">Items</a></li>
            <li role="presentation"><a href="<?php echo base_url('settings/emails'); ?>">Emails</a></li>
            <?php
endif; ?>
        </ul>
    </div>
    <div class="container-fluid bg-white doc-spaced">
       <div class="col-xs-12">
            <h3 class="pull-left-xs">Users</h3>
             <button data-redirect-url="<?php echo base_url('settings/users'); ?>" class="btn btn-default pull-right-xs open-modal" href="<?php echo base_url('settings/modal/users/add'); ?>" type="button">Add New User &nbsp; &nbsp; +</button>
       </div>
       
      <div class="container-fluid">
        
       <!-- <div class="table-responsive">-->
            <table class="table listInvoiceTable" style="font-weight:bold;">
              <tr class="table_header">
                <td>No.</td>
                <td>Name</td>
                <td class="hidden-xs">Last Active</td>
                <td class="hidden-xs">Role</td>                 
                <td>Contact</td>
                <td></td>
              </tr>
             <?php

$count = 1;
foreach ($settings_data as $user_key => $user_data) {

?>
             <tr>                         	
                <td><?php echo $count; ?>.</td>
                <td><?php echo ucwords($user_data['first_name'] . ' ' . $user_data['last_name']); ?>
                     <div class="listHoverOptions hidden-xs"><a class="open-modal" href="<?php echo base_url('settings/modal/users/edit/' . $user_data['id']); ?>">Edit</a>|<a  class="open-modal" href="<?php echo base_url('settings/modal/users/delete/' . $user_data['id']); ?>">Trash</a></div>
                     <div class="visible-xs-block list-small-top-margin"><a class="greyLink smallGreyLightened" href="mailto:<?php echo $user_data['email']; ?>"><?php echo $user_data['email']; ?></a></div>
                </td>
                <td class="hidden-xs">
                    <span class="listDateItalic">
					<?php
    $dt = new DateTime($user_data['last_activity']);
    echo $dt->format("M d, g:i a");
?>
                    </span>
                </td>
                <td>
                   <?php echo $user_data['user_role']; ?>
                </td>
                <td class="hidden-xs">
                    <div><a class="greyLink" href="mailto:<?php echo $user_data['email']; ?>"><?php echo $user_data['email']; ?></a></div>
                    <div class="smallGreyLightened list-small-top-margin"><?php echo $user_data['contact_number']; ?></div>
                    </td>  
                                            
               <td>
                    <div style="position:relative;" class="nav nav-pills pull-right">
                        <button  role="button" aria-haspopup="true" data-toggle="dropdown" type="button" class="btn btn-info btn-xs"> <span class="caret"></span> </button>
                       <ul class="dropdown-menu">
                          <li><a class="open-modal" href="<?php echo base_url('settings/modal/users/edit/' . $user_data['id']); ?>">Edit</a></li>
                          <li><a class="open-modal" href="<?php echo base_url('settings/modal/users/delete/' . $user_data['id']); ?>">Trash</a></li>
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
        <a href="<?php echo base_url('settings/roles'); ?>" type="button" class="btn btn-success saveButton">Continue</a>
    </div>
       
    </div>
    
   
     </form> 
    
<!-- END content area --> 