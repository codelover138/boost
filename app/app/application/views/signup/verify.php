<?php $this->load->view('includes/html_pre_content'); //start HTML and include css,js,metatags etc ?>

<?php $this->load->view('includes/offline_error_modal'); //include offline error modal HTML ?>


<!-- START content area -->

<div class="outerContainer_table login_table">
    <div class="tableRow">
        <div class="tableCell"> 
            <!-- START login content area -->
            <form id="verification_form" class="autologin" method="post" action="<?php echo base_api_url('registrations'); ?>" autocomplete="on">
                <div class="login_container">
                	<div class="login_logo_container  col-sm-12">
                    	<img src="<?php echo base_url('images/boost_medium_logo.png'); ?>" >
                    </div>
                    <div class="col-sm-12 form-group loading_container input_group">
                        	<img alt="Loading ..." src="<?php echo base_url('images/account_create_loader.gif'); ?>"  />
                    </div>
                    <div class="login_form_container">
                    	<input id="signup_token" name="signup_token" type="hidden" value="<?php echo @$signup_token; ?>" />
                       
                        <div class="col-sm-12 form-group input_group">
                        	<p class="logging_in_text">
                            	Creating your account ...<br />
								Please wait a moment.<br />
                            </p>
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
										 echo 'There was an error.<br /> please attempt to register again';
									 }							 
								 ?>
                                 </strong>
                              </div>
                            </div>
                            <div class="register_success_container hidden_alert">
                              <div class="alert alert-success" role="alert">          
                                 <strong>
                                 <h2>Congratulations!</h2> 
								 Your registration was successful.<br />
								 You will now be logged in.<br />
                                 </strong>
                              </div>
                            </div>
                        </div>  
                    </div>
                    <div class="modal-buttons" style="display:none;">
           				 <a style="color:#fff;" href="https://app.boostaccounting.com" class="btn btn-success saveButton padded">Register</a> or &nbsp;&nbsp; <a href="http://boostaccounting.com">Go to Website</a>
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