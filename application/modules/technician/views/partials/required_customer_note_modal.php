
<div id="modal_required_customer_note" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;"> -->
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Complete Service</h6>
            </div>

            <form name="form_required_customer_note" method="post" enctype="multipart/form-data"
                  id="form_required_customer_note" onsubmit="formFileSizeValidate(this)">
                <div class="modal-body">
                    <h6 class="text-semibold">Please Submit a Customer Completion Note.</h6>
                    <hr>

                    <div class="row">
                        <div class="form-group">
                            <label for="customer_note_saw">What I Saw:</label><br>
                            <input type="text" class="form-control" id="customer_note_saw" name="customer_note_saw"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="customer_note_did">What I Did:</label><br>
                            <input type="text" class="form-control" id="customer_note_did" name="customer_note_did"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="customer_note_expect">What To Expect:</label><br>
                            <input type="text" class="form-control" id="customer_note_expect"
                                   name="customer_note_expect" required>
                            <input type="hidden" id="note_property_id" name="note_property_id"
                                   value="<?= $property_details->property_id; ?>">
                            <input type="hidden" name="note_customer_id"
                                   value="<?= $job_assign_details[0]['customer_id']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Attach Documents/Images</label>
                            <input id="files" type="file" name="files[]" class="form-control-file" multiple
                                   onchange="fileValidationCheck(this)">
                            <span style="color:red;"><?php echo form_error('files'); ?></span>
                        </div>
                    </div>

                    <hr>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal"
                                id="cancel_required_customer_note">Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="submit_required_customer_note">Continue
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>