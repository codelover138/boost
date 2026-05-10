<?php
	//$credit_note_data = array_values($request['data'])[0];
?>

<div class="modal-dialog" role="document">
    <form action="<?php echo base_api_url('bulk/export/credit_notes/'); ?>" method="put">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Export as PDF</h4>
            </div>
            
            <div class="modal-body">
            	<div class="col-sm-12 form-group">
                	<strong>Save the following credit notes in PDF format?</strong>
                </div>
                <div class="clearfix"></div>
           		<div class="col-sm-12 form-group">
                	<ul>
						<?php
                            $count = 0;
                            $credit_note_ids = array();
                            foreach($request['data'] as $credit_note_key => $credit_note_data){
                                $credit_note_ids[] = $credit_note_data['id'];                       
								echo '<li>#'.$credit_note_data['credit_note_number'].'</li>'; 
								echo '<input name="data['.$count.'][\'id\']" type="hidden" value="'.$credit_note_data['id'].'" />';
                                $count++;
                            }
                        ?>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="modal-buttons">
            <button data-close-delay-seconds="1" data-modal-heading="Success" data-modal-body="PDF successfully created. " data-related-section="credit_notes" data-modal-url="<?php echo base_url('modal/notice'); ?>" type="button" class="btn btn-success saveButton saveFormData padded">Continue</button> or <a href="#" data-dismiss="modal">Cancel</a>
        </div> 
    </form>        
</div>
