<div id="modal_sales_visit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Complete Service</h6>
            </div>
            <form action="<?= base_url('technician/completeJobMultiple/') . $tech_assign_ids ?>"
                  name="completejobformsales" method="post">
                <input type="hidden" name="prog_price" id="prog_price"
                       value=<?= $job_assign_details[0]['program_price'] ?>>
                <?php if (isset($job_assign_details[0]['basys_autocharge']) && $job_assign_details[0]['basys_autocharge'] == 1) { ?>
                    <input type="hidden" name="basys_autocharge" id="basys_autocharge" value=1>
                <?php } else { ?>
                    <input type="hidden" name="basys_autocharge" id="basys_autocharge" value=0>
                <?php } ?>
                <?php if (!empty($job_assign_details[0]['email']) && $job_assign_details[0]['is_email'] == 1) { ?>
                    <input type="hidden" name="customer_email" id="customer_email" value=1>
                <?php } elseif (!empty($job_assign_details[0]['secondary_email']) && $job_assign_details[0]['is_email'] == 1) { ?>
                    <input type="hidden" name="customer_email" id="customer_email" value=1>
                <?php } else { ?>
                    <input type="hidden" name="customer_email" id="customer_email" value=0>
                <?php } ?>
                <div class="modal-body">


                    <?php
                    if ($job_assign_details[0]['is_email'] == 1) { ?>

                        <h6 class="text-semibold">Type a message to the customer below to be included with the Service
                            completion email.</h6>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea class="form-control" name="message"></textarea>
                                </div>

                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="message">
                    <?php } ?>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="job_assign_bt">Continue</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>