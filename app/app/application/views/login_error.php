<?php $this->load->view('includes/html_pre_content'); //start HTML and include css,js,metatags etc ?>

<?php $this->load->view('includes/offline_error_modal'); //include offline error modal HTML ?>


<!-- START content area -->

<div class="outerContainer_table login_table">
    <div class="tableRow">
        <div class="tableCell"> 
            <!-- START login content area -->
          
                <div class="login_container">
                	<div class="login_logo_container  col-sm-12">
                    	<img src="<?php echo base_url('images/boost_medium_logo.png'); ?>" >
                    </div>
                    <div class="login_form_container">
                        <div class="col-sm-12 form-group">
                            <div class="login_alert_container">
                              <div class="alert alert-danger" role="alert">
                                 <strong>
                                 <?php 							 
									 if($request['message']){
										 foreach($request['message'] as $message_key => $message_string){
											 echo $message_string.'<br />';
										 }
									 }else{
										 echo 'You have entered the incorrect details';
									 }							 
								 ?>
                                 </strong>
                              </div>
                            </div>
                        </div>  
                    </div>
                   
                </div>

            <!-- END login content area -->
        </div>            
    </div>
</div>

<!-- END content area -->

<?php $this->load->view('includes/footer'); //include footer ?>

<?php $this->load->view('includes/html_post_content'); //End HTML ?>