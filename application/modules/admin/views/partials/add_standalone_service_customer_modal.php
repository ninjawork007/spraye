<div id="modal_add_service" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Service</h6>
            </div>
            <form name="addService" method="post" enctype="multipart/form-data" onsubmit="event.preventDefault(); addStandaloneService(this)">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Choose Property</label>
                                <select class="form-control" id="property_id" name="property_id">
                                    <?php foreach ($all_customer_properties as $property) {
                                        if (isset($property->property_status) && $property->property_status != 0) { ?>
                                            <option
                                                value="<?= $property->property_id; ?>"><?= $property->property_title; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Choose Service</label>
                                <select class="form-control" name="service_id" id="service_id">
                                    <option value="">Select Any Service</option>
                                    <?php if ($servicelist) {
                                        foreach ($servicelist as $value) { ?>
                                            <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Pricing</label>
                                <select class="form-control" name="program_price" id="program_price">
                                    <option value="">Select Any Pricing</option>
                                    <option value=1>One-Time Service Invoicing</option>
                                    <option value=2>Invoiced at Service Completion</option>
                                    <option value=3>Manual Billing</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" id="price_override_modal">
                            <div class="col-sm-12" id="addServicePOLabel">
                                <label>Price Override Per Service</label>
                                <input type="number" class="form-control" min=0
                                       name="price_override"
                                       id="price_override"
                                       placeholder="(Optional) Enter Price Override Here">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group hold_service_text">
                                    <label>Hold this service until the following date (leave empty for unset):</label>
                                    <input type="date"
                                           id="hold_until_date"
                                           name="hold_until_date"
                                           value=""
                                           class="form-control pickadate"
                                           placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Add to service-specific note</label>
                                    <input type="checkbox"
                                           onchange="$('#modal_add_service #note_contents_visible').toggleClass('hidden')"
                                           name="add_to_service_specific_note"
                                           id="add_to_service_specific_note"
                                           class="checkbox checkbox-inline text-right switchery_add_to_service_specific_note">
                                </div>
                            </div>
                        </div>
                        <div class="row hidden" id="note_contents_visible">
                            <div class="col-sm-12" id="addServicePOLabel">
                                <label>Note Contents</label>
                                <textarea class="form-control"
                                          name="note_contents"
                                          id="note_contents"
                                          rows="5"></textarea>
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