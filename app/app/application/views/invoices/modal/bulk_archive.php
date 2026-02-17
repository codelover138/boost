<?php
	//$invoice_data = array_values($request['data'])[0];
?>

<div class="modal-dialog" role="document">
    <form action="<?php echo base_api_url('bulk/invoices/content_status/archived'); ?>" method="put">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Archive</h4>
            </div>
            
            <div class="modal-body">
            	<div class="col-sm-12 form-group">
                	<strong>Are you sure you want to archive the following invoices?</strong>
                </div>
                <div class="clearfix"></div>
           		<div class="col-sm-12 form-group">
                	<ul>
						<?php
                            $count = 0;
                            $invoice_ids = array();
                            foreach($request['data'] as $invoice_key => $invoice_data){
                                $invoice_ids[] = $invoice_data['id'];                       
								echo '<li>#'.$invoice_data['invoice_number'].'</li>'; 
								echo '<input name="data['.$count.'][\'id\']" type="hidden" value="'.$invoice_data['id'].'" />';
                                $count++;
                            }
                        ?>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="modal-buttons">
            <button data-redirect-url="<?php echo $_SERVER["HTTP_REFERER"]; ?>" data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="Items were archived successfully. " data-related-section="invoices" data-related-ids="<?php echo implode(',',$invoice_ids); ?>" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Archive</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
    </form>        
</div>
