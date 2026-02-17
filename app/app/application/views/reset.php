<?php $this->load->view('includes/html_pre_content'); //start HTML and include css,js,metatags etc ?>

<?php // $this->load->view('includes/modal'); //include modal HTML ?>

<?php // $this->load->view('includes/header',$request); //include header with menu and subheader ?>

<!-- START content area -->

<div class="outerContainer_table login_table">
    <div class="tableRow">
        <div class="tableCell"> 
            <!-- START form area -->
            	<form class="forgot_pass_form landing" method="put" action="<?php echo base_api_url('passwords/'.$reset_link); ?>">
                    <div class="login_container">
                        <div class="login_logo_container  col-sm-12">
                            <img src="<?php echo base_url('images/boost_medium_logo.png'); ?>" >
                        </div>
                        <div class="login_form_container">
                       		<div class="col-sm-12 form-group">
                                <input type="password" placeholder="New Password" id="password" name="password" class="form-control"> 
                            </div>
                            <div class="col-sm-12 form-group">
                                <input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" class="form-control"> 
                            </div>
                            <div class="col-sm-12 form-group">
                                  <div class="login_alert_container hidden_alert">
                                     <div class="alert alert-danger " role="alert">
                                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                         <strong>Email address does not exist for this account</strong>
                                     </div>
                                  </div>
                                  <div class="register_success_container hidden_alert">
                                      <div class="alert alert-success" role="alert">          
                                         <strong>
                                         <h2>Success!</h2> 
                                         Password has been updated.<br /><br />
                                         </strong>
                                      </div>
                                  </div>
                            </div>
                        </div>
                        <div class="modal-buttons">
                             <button type="submit" class="btn btn-success saveButton padded">Reset Password</button> <span class="orContainer">or &nbsp;&nbsp; </span><a href="<?php echo base_url('login'); ?>">Login</a>
                         </div>
                    </div>
                </form>
            <!-- END form area -->
        </div>            
    </div>
</div>

<!-- END content area -->

<?php $this->load->view('includes/footer'); //include footer ?>

<?php $this->load->view('includes/html_post_content'); //End HTML ?>