
<div id="modal_add_service" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Service</h6>
            </div>

            <form name="addService" method="post" enctype="multipart/form-data">

                <div class="modal-body">


                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input name="add_to" value="today" type="radio" checked="checked" id="addToToday">Add
                                    to Today's Stop
                                </label>
                            </div>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input name="add_to" value="future" type="radio" id="addToFuture">Schedule in Future
                                </label>
                            </div>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input name="add_to" value="hold_until" type="radio" id="addToHoldUntil">Hold Until
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Add Service</label>

                                <select class="form-control" name="job_id" id="selected_job_id" required>
                                    <option value="">Select Any Service</option>
                                    <?php if ($allservicelist) {
                                        foreach ($allservicelist as $value) { ?>
                                            <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <input type="hidden" name="add_service_property_id"
                                       value="<?= $property_details->property_id; ?>">
                                <input type="hidden" name="add_service_technician_id"
                                       value="<?= $job_assign_details[0]['technician_id']; ?>">
                                <input type="hidden" name="add_service_route_id"
                                       value="<?= $job_assign_details[0]['route_id']; ?>">
                                <input type="hidden" name="add_service_customer_id"
                                       value="<?= $job_assign_details[0]['customer_id']; ?>">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Pricing</label>
                                <select class="form-control" name="program_price" id="add_service_program_price"
                                        required>
                                    <option value="">Select Any Pricing</option>
                                    <option value=1>One-Time Service Invoicing</option>
                                    <option value=2>Invoiced at Service Completion</option>
                                    <option value=3>Manual Billing</option>
                                </select>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Price Override</label>
                                <input type="number" class="form-control" min=0 name="add_job_price_override" value=""
                                       placeholder="(Optional) Enter Price Override Here">
                            </div>
                        </div>
                        <div class="row" id="hold_date_for_future_service" style="display: none;">
                            <div class="col-sm-12">
                                <label>Hold Service Until</label>
                                <input type="date"
                                       id="add_service_hold_until_date"
                                       name="add_service_hold_until_date"
                                       value=""
                                       class="form-control pickadate note-filter"
                                       placeholder="YYYY-MM-DD">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="addServiceSubmit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>