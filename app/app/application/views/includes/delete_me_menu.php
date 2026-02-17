<nav class="navbar navbar-default navbar-static" id="navbar-example">
      <div class="max-width-1200">
        <div class="navbar-header">
          <button data-target=".navbar-collapse" data-toggle="collapse" type="button" class="navbar-toggle collapsed">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="#" class="navbar-brand logo_container"><img src="images/boost_logo.png" alt="BOOST" /></a>
        </div>
        <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right pull-right-xs">
            <li class="dropdown">
              <a  style="background-image:url(images/chat-icon.png);" aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle chat-icon-link" href="#">
               Financial Advice               
              </a>
            </li>
             <li class="dropdown">
              <a  style="background-image:url(images/help-icon.png);" aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle help-icon-link" href="#">
               Help                
              </a>
            </li>
             <li class="dropdown">
              <a style="background-image:url(images/temp-profile-pic.png);" aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle profile-icon-link" href="#">
                Hello, Simon  <span class="caret"></span>  
              </a>  
                 <ul role="menu" class="dropdown-menu">
                     <li role="presentation"><a href="#" role="menuitem">My Profile</a></li>
                     <li role="presentation"><a href="<?php echo base_url('settings'); ?>" role="menuitem">Account Settings</a></li>
                     <li role="separator" class="divider"></li>
                     <li role="presentation"><a class="logout_button" href="<?php echo api_base_url('login'); ?>" role="menuitem">Logout</a></li>                    
              	 </ul>       
             
            </li>
          </ul>
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a class="dropdown-toggle" href="#1">
                Dashboard
              </a>
            </li>
            <li class="dropdown">
              <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle" href="#">
                Accounts                
              </a>
              <ul  role="menu" class="dropdown-menu">
                
                <li role="presentation"><a href="/invoices/" role="menuitem">Invoicing</a></li>
                <li role="presentation"><a href="#" role="menuitem">Supplier Invoice</a></li>
                <li role="presentation"><a href="#"role="menuitem">Expenses</a></li>
                <li role="presentation"><a href="#" role="menuitem">Travel Tracker</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a class="dropdown-toggle" href="<?php echo base_url('estimates'); ?>">
                Estimates                 
              </a>
            </li>
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
             <li class="dropdown">
              <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle" href="#">
                Reports                 
              </a>
              <ul  role="menu" class="dropdown-menu">
                <li role="presentation"><a href="#" role="menuitem">Account Transactions</a></li>
                <li role="presentation"><a href="#" role="menuitem">Age analysis</a></li>
                <li role="presentation"><a href="#" role="menuitem">Balance Sheet</a></li>
                <li role="presentation"><a href="#" role="menuitem">Proffit and Loss</a></li>
                <li role="presentation"><a href="#" role="menuitem">Tax Summary</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a class="dropdown-toggle" href="<?php echo base_url('contacts'); ?>">
                Contacts                 
              </a>
       
            </li>
          </ul>
          
        </div><!-- /.nav-collapse -->
      </div><!-- /.container-fluid -->
    </nav>