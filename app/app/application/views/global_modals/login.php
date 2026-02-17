<div class="modal-dialog" role="document">
    <form class="login_form" method="post" action="<?php echo base_api_url('login'); ?>">
        <div class="modal-content  modal-sm">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"> 
                    Login            
                </h4>
            </div>
            <div class="modal-body">
                 <div class="col-sm-12 form-group">
                        <div class="login_alert_container">
                          <div class="alert alert-danger login_alert_close" style="cursor:default;" role="alert">
                          		<strong>
                                
                                       <?php 							 
										 if($this->input->get('alertText')){
											 echo urldecode(implode('. ',$this->input->get('alertText')).'. Please login.');
										 }else{
											 echo 'Your security token has expired. Please login.';
										 }							 
									 ?>
								</strong>
                          </div>
                        </div>
                    </div>             
                <div class="clearfix"></div>
                
                <div class="login_form_container">
                    <div class="col-sm-12 form-group">
                        <input type="text" placeholder="Email Address" id="email" name="email" class="form-control"> 
                    </div>
                    <div class="col-sm-12 form-group">
                        <input type="password" placeholder="Password" id="password" name="password" class="form-control"> 
                    </div>
                   
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="modal-buttons">
            <button class="btn btn-success saveButton padded" type="submit" >Login</button> or &nbsp;&nbsp; <a href="<?php echo base_url('login/forgot'); ?>">Forgot Password?</a>
        </div>           
    </form>
</div>