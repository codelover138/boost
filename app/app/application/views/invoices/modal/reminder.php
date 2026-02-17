

<div class="modal-dialog" role="document">
    <div class="modal-content  modal-sm">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Payment Reminder</h4>
        </div>
        <div class="modal-body">
            <div class="col-sm-12 form-group">
                <label for="reminder_selection" class="control-label">Choose a date</label>
                 <select id="reminder_selection" name="reminder_selection" class="selectpicker full-width">
                    <option <?php if($reminder == 0){ echo 'selected="selected"'; } ?> value="0">No Reminder</option>
                    <option <?php if($reminder == 7){ echo 'selected="selected"'; } ?> value="7">7 Days</option>
                    <option <?php if($reminder == 15){ echo 'selected="selected"'; } ?> value="15">15 Days</option>
                    <option <?php if($reminder == 30){ echo 'selected="selected"'; } ?> value="30">30 Days</option>
                </select>          
            </div>            
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="modal-buttons">
        <button id="saveReminder" <?php  if(isset($id)){ echo 'data-invoice-id="'.$id.'"'; }else{echo 'data-invoice-id="new"';} ?>  data-dismiss="modal" type="button" class="btn btn-success saveButton padded">Save</button> or <a href="#" data-dismiss="modal">Cancel</a>
    </div>           
</div>