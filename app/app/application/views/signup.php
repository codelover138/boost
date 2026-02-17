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
                    	<img src="<?php echo base_url('images/boost_medium_logo.png'); ?>" >
                    </div>
                    <div class="login_form_container">
                    	<div class="col-sm-12 form-group input_group">
                        	<input type="text" placeholder="Company name" id="company_name" name="company_name" class="form-control"> 
                        </div>
                        <div class="col-sm-12 form-group input_group">
                        	<input type="email" placeholder="Email Address" id="email" name="email" class="form-control"> 
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
                                 <h2>Congratulations!</h2> 
								 Your registration was successful.<br /><br />
								 Please check your email and follow the instructions provided.<br /><br />

                                 </strong>
                              </div>
                            </div>
                        </div>  
                    </div>
                    <div class="modal-buttons">
           				 <button class="btn btn-success saveButton padded" type="submit">Register</button> or &nbsp;&nbsp; <a href="http://boostaccounting.com">Go to Website</a>
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