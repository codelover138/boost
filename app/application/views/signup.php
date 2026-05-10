<?php $this->load->view('includes/html_pre_content'); //start HTML and include css,js,metatags etc ?>

<?php $this->load->view('includes/offline_error_modal'); //include offline error modal HTML ?>


<!-- START content area -->

<div class="outerContainer_table login_table">
    <div class="tableRow">
        <div class="tableCell"> 
            <!-- START login content area -->
            <form id="signup_form" method="post" action="<?php echo base_api_url('registrations'); ?>" autocomplete="on">
                <div class="login_container">
                	<div class="login_logo_container  col-sm-12">
                    	<img src="<?php echo base_url('images/boost_logo_full.svg'); ?>" >
                    </div>
                    <div class="login_form_container">
                    	<div class="col-sm-12 form-group input_group">
                        	<input type="text" placeholder="Business name / Username" id="company_name" name="company_name" class="form-control">
                        </div>
                        <div class="col-sm-12 form-group input_group">
                        	<input type="email" placeholder="Email address" id="email" name="email" class="form-control">
                        </div>
                        <div class="col-sm-12 form-group">
                            <div class="login_alert_container <?php echo ($this->input->get('error') ? '' : 'hidden_alert'); ?>">
                              <div class="alert alert-danger" role="alert">
                                 <button type="button" class="close login_alert_close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                 <strong>
                                 <?php
									 if($this->input->get('error')){
										 echo urldecode($this->input->get('error'));
									 }else{
										 echo 'You have entered the incorrect details';
									 }
								 ?>
                                 </strong>
                              </div>
                            </div>
                            <div class="register_success_container hidden_alert">
                              <div class="alert alert-success" role="alert">
                                 <strong>
                                 <h2>You're in.</h2>
								 We've sent you an email to get started.<br />
								 Check your spam folder if it hasn't arrived.<br /><br />
								 Follow the steps to finish setting up your account.
                                 </strong>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-buttons">
           				 <button class="btn btn-success saveButton padded" type="submit">Continue</button>
       				 </div>
                    <div class="col-sm-12 text-center" style="margin-top:10px; font-size:12px; color:#888;">
                        Takes less than a minute. No credit card required.
                    </div>
                    <div class="col-sm-12 text-center" style="margin-top:8px; font-size:11px; color:#aaa;">
                        By continuing, you agree to our <a href="https://boostaccounting.com/terms-of-service.html" target="_blank">Terms of Service</a> and have read our <a href="https://boostaccounting.com/privacy-policy.html" target="_blank">Privacy Policy</a>.
                    </div>
                </div>
            </form>
            <!-- END login content area -->
        </div>            
    </div>
</div>

<!-- END content area -->

<?php $this->load->view('includes/footer'); //include footer ?>

<?php $this->load->view('includes/html_post_content'); //End HTML ?>