<div class="modal-dialog" role="document">
    <div class="modal-content  modal-sm">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"> 
				<?php if(isset($heading)){ echo $heading; } ?>              
            </h4>
        </div>
        <div class="modal-body">
        	 <div class="col-sm-12 form-group">
               <strong>Please attend to the below issues before proceeding:</strong>     
            </div>            
            <div class="clearfix"></div>
            <div class="col-sm-12 form-group">
               <?php 
			   		if(isset($body)){ 
						if(is_array($body)){
							echo '<ul>';
							foreach($body as $item => $item_val){
								echo '<li>'.$item_val.'</li>';
							}
							echo '</ul>';
						}else{
							echo '<ul>';
							echo '<li>'.$body.'</li>'; 
							echo '</ul>';
						}					
					
					} 
			   ?>      
            </div>            
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="modal-buttons">
        <a href="#" data-dismiss="modal">Close</a>
    </div>           
</div>