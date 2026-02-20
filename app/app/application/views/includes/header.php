<?php

//var_dump($user_data['permissions']);
//var_dump($user_data);
?>
<header>
    <nav class="navbar navbar-default navbar-static" id="navbar-example">
        <div class="max-width-1200">
            <div class="navbar-header">
                <button data-target=".navbar-collapse" data-toggle="collapse" type="button"
                    class="navbar-toggle collapsed">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?php echo base_url('welcome/dashboard'); ?>" class="navbar-brand logo_container"><img
                        src="<?php echo base_url('images/boost_logo.png'); ?>" alt="BOOST" /></a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right pull-right-xs">
                    <!--<li class="dropdown">
              <a  style="background-image:url(<?php echo base_url('images/chat-icon.png'); ?>);" aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle chat-icon-link" href="#">
               Financial Advice               
              </a>
            </li>
             <li class="dropdown">
              <a  style="background-image:url(<?php echo base_url('images/help-icon.png'); ?>);" aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle help-icon-link" href="#">
               Help                
              </a>
            </li>-->
                    <li class="dropdown">
                        <!--<a style="background-image:url(<?php echo base_url('images/temp-profile-pic.png'); ?>);" aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle profile-icon-link" href="#">
                Hello, <?php echo $user_data['first_name']; ?>  <span class="caret"></span>  
              </a> -->
                        <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown"
                            class="dropdown-toggle profile-icon-link" href="#">
                            Hello,
                            <?php
//print_r($user_data);
if (isset($user_data['first_name']) && @$user_data['first_name'] != '') {
    echo ucwords($user_data['first_name']);
}
else {
    echo ucwords($user_data['company_name']);
}


?> &nbsp;<span class="caret"></span>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <!--<li role="presentation"><a href="#" role="menuitem">My Profile</a></li>-->
                            <?php if (in_array('account_settings', $user_data['permissions'])) { ?>
                            <li role="presentation"><a href="<?php echo base_url('settings'); ?>"
                                    role="menuitem">Account Settings</a></li>
                            <li role="separator" class="divider"></li>
                            <?php
}?>


                            <li role="presentation"><a href="javascript:boostLogout();" class="logout_button"
                                    role="menuitem">Logout</a></li>
                        </ul>

                    </li>
                </ul>
                <ul class="nav navbar-nav main_menu_navigation">
                    <?php
$is_super_admin = (isset($user_data['email']) && ($user_data['email'] == 'babu313136@gmail.com' || $user_data['email'] == 'admin@boostaccounting.com'));
?>

                    <?php if (!$is_super_admin): ?>
                    <li class="dropdown">
                        <a href="<?php echo base_url('welcome/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                    <?php
else: ?>
                    <li class="dropdown">
                        <a href="<?php echo base_url('admin/workspaces'); ?>">
                            Workspace
                        </a>
                    </li>
                    <?php
endif; ?>

                    <?php
//additional modules must be added to this array as they are developed.
//This so that accounts menu item does not show if its child permissions are not available
if (array_intersect(array('invoices', 'credit_notes'), $user_data['permissions']) && !$is_super_admin) {
?>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown"
                            class="dropdown-toggle" href="#">
                            Accounts
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <?php if (in_array('invoices', $user_data['permissions'])) { ?>
                            <li role="presentation"><a href="<?php echo base_url('invoices'); ?>"
                                    role="menuitem">Invoicing</a></li>
                            <?php
    }?>
                            <?php if (in_array('credit_notes', $user_data['permissions'])) { ?>
                            <li role="presentation"><a href="<?php echo base_url('credit_notes'); ?>"
                                    role="menuitem">Credit Notes</a></li>
                            <?php
    }?>
                            <!--
                <?php if (in_array('supplier_invoices', $user_data['permissions'])) { ?>          	 
                 	<li role="presentation"><a href="#" role="menuitem">Supplier Invoice</a></li>
                <?php
    }?>
                -->
                            <?php if (in_array('expenses', $user_data['permissions'])) { ?>
                            <li role="presentation"><a href="<?php echo base_url('expenses'); ?>"
                                    role="menuitem">Expenses</a></li>
                            <?php
    }?>
                            <!--
                <?php if (in_array('travel_tracker', $user_data['permissions'])) { ?>
                	<li role="presentation"><a href="#" role="menuitem">Travel Tracker</a></li>                 
                <?php
    }?>
                -->
                        </ul>
                    </li>
                    <?php
}?>
                    <?php if (in_array('estimates', $user_data['permissions']) && !$is_super_admin) { ?>
                    <li>
                        <a href="<?php echo base_url('estimates'); ?>">
                            Estimates
                        </a>
                    </li>
                    <?php
}?>
                    <?php if (in_array('time_tracking', $user_data['permissions'])) { ?>
                    <!-- <li class="dropdown">
              <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle" href="#">
                Time Tracking                  
              </a>
              <ul  role="menu" class="dropdown-menu">
                <li role="presentation"><a href="#" role="menuitem">Projects</a></li>
                <li role="presentation"><a href="#" role="menuitem">Time Sheets</a></li>
                <li role="presentation"><a href="#" role="menuitem">Tasks</a></li>
              </ul>
            </li>-->
                    <?php
}?>
                    <?php if (in_array('reports', $user_data['permissions']) && !$is_super_admin) { ?>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown"
                            class="dropdown-toggle" href="#">
                            Reports
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <!-- <li role="presentation"><a href="#" role="menuitem">Account Transactions</a></li>
                            <li role="presentation"><a href="#" role="menuitem">Age analysis</a></li>
                            <li role="presentation"><a href="#" role="menuitem">Balance Sheet</a></li>
                            <li role="presentation"><a href="#" role="menuitem">Proffit and Loss</a></li> -->
                            <li role="presentation"><a href="<?php echo base_url('reports/business_report'); ?>"
                                    role="menuitem">Business Report</a></li>
                            <!-- <li role="presentation"><a href="#" role="menuitem">Tax Summary</a></li> -->
                        </ul>
                    </li>
                    <?php
}?>
                    <?php if (in_array('contacts', $user_data['permissions']) && !$is_super_admin) { ?>
                    <li>
                        <a href="<?php echo base_url('contacts'); ?>">
                            Contacts
                        </a>
                    </li>
                    <?php
}?>
                </ul>

            </div><!-- /.nav-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="sub_header">
        <div class="sub_header_inner max-width-1200">
            <h1><?php echo $page['heading']; ?></h1>
            <span style="position:relative;">
                <input id="headerSearchInput" class="headerSearchInput hidden-xs" onclick="event.stopPropagation()"
                    data-toggle="dropdown" type="text" placeholder="Search" />
                <ul role="menu" class="dropdown-menu search-arrow" id="search_overlay">
                </ul>
            </span>

            <?php
if (isset($page['header_button_view'])) {
    $this->load->view($page['header_button_view']); //include modal HTML 
}
?>

        </div>
    </div>
</header>
<!-- header END -->