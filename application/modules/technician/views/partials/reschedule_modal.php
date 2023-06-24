<div id="modal_reschedule" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Reschedule</h6>
            </div>
            <form action="<?= base_url('technician/rescheduleJobMultiple/') . $tech_assign_ids ?>"
                  name="reschedulejobform" method="post">

                <input type="hidden" name="prog_price" id="prog_price"
                       value=<?= $job_assign_details[0]['program_price'] ?>>

                <div class="modal-body">
                    <?php
                    if ($job_assign_details[0]['is_email'] == 1) { ?>

                        <h6 class="text-semibold">Reschedule Reason</h6>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <select class="form-control" name="reason_id" id="reason_id" required>
                                        <option value="">Select Any Reason</option>
                                        <?php

                                        if (isset($reschedule_reasons)) {
                                            foreach ($reschedule_reasons as $value) { ?>
                                                <option
                                                    value="<?= $value->reschedule_id . '/' . $value->reschedule_name ?>"><?= $value->reschedule_name ?></option>
                                            <?php }
                                        } ?>
                                        <option value="-1">Other</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" id="reschedule_reason_other" hidden>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="reason_other">Add more details</label>
                                    <input type="text" class="form-control" name="reason_other" id="reason_other">

                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="reason_other">Hold Service Until</label>
                                    <input type="date"
                                           id="hold_until_date"
                                           name="hold_until_date"
                                           value=""
                                           class="form-control pickaalldate note-filter"
                                           placeholder="YYYY-MM-DD"
                                           required>
                                </div>

                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="other">
                    <?php } ?>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="reschedule_bt">Reschedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>