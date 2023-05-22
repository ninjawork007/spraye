<!-- New Notes Modal -->
<div id="modal_new_note" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">New Note</h6>
            </div>
            <form class="form-horizontal" style="height: 500px;overflow-y:scroll; padding: 5%"
                  action="<?= base_url('admin/createNote') ?>" method="post" name="createnoteform"
                  enctype="multipart/form-data" id="createnoteform" onSubmit="formFileSizeValidate(this)">
                <fieldset class="content-group">
                    <input type="hidden" name="note_customer_id" class="form-control"
                           value="<?= $customerData['customer_id']; ?>">
                    <input type="hidden" name="note_category" class="form-control" value="1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Assign Property</label>
                                <div class="col-md-7">
                                    <select class="form-control" name="note_property_id" id="note_property_id">
                                        <?php
                                        if (!empty($customer_properties)) {
                                            foreach ($customer_properties as $value) {
                                                if (in_array($value->property_id, $selectedpropertylist)) { ?>
                                                    <option
                                                        value="<?= $value->property_id; ?>" <?= (isset($uriSegments[5]) && $uriSegments[5] == $value->property_id) ? 'selected' : '' ?>><?= $value->property_title; ?></option>
                                                <?php }
                                            }
                                        } ?>
                                    </select>
                                    <span style="color:red;"><?php echo form_error('note_property_id'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Note Type</label>
                                <div class="col-md-7">
                                    <select class="form-control" name="note_type" id="note_type_modal" required>
                                        <option value="" disabled selected></option>
                                        <option value="1">Task</option>
                                        <?php foreach ($note_types as $type) : ?>
                                            <option value="<?= $type->type_id; ?>"><?= $type->type_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span style="color:red;"><?php echo form_error('note_type'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 with_note_type_service_specific" style="display: none;">
                            <div class="form-group">
                                <label class="control-label col-lg-5">Assign Services</label>
                                <div class="col-lg-7">
                                    <select class="form-control" name="note_assigned_services">
                                        <!-- Add Users available within company with Value = user_id / option shown user_name -->
                                        <option value="">None</option>
                                        <?php
                                        foreach ($servicelist as $service) {
                                            ?>
                                            <option value="<?= $service->job_id; ?>"><?= $service->job_name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <span style="color:red;"><?php echo form_error('note_assigned_services'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 with_note_type_service_specific" style="display: none;">
                            <div class="form-group">
                                <label class="control-label col-lg-5">Note Duration</label>
                                <div class="col-lg-7">
                                    <select class="form-control" name="assigned_service_note_duration">
                                        <option value="">None</option>
                                        <option value=1>Permanent</option>
                                        <option value=2>Next Service Only</option>
                                    </select>
                                    <span style="color:red;"><?php echo form_error('assigned_service_note_duration'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Assign User</label>
                                <div class="col-md-7">
                                    <select class="form-control" name="note_assigned_user" id="note_assigned_user">
                                        <option value="">None</option>
                                        <?php
                                        foreach ($userdata as $user) {
                                            ?>
                                            <option
                                                value="<?= $user->id; ?>"><?= $user->user_first_name . ' ' . $user->user_last_name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <span style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Due Date</label>
                                <div class="col-md-7">
                                    <input id="note_due_date" type="text" name="note_due_date"
                                           class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Attach Documents</label>
                                <div class="col-md-7 text-left">
                                    <input id="files" type="file" name="files[]" class="form-control-file" multiple
                                           onChange="fileValidationCheck(this)">
                                    <span style="color:red;"><?php echo form_error('files'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Include in Technician View?</label>
                                <div class="col-md-7">
                                    <input id="include_in_tech_view_modal" type="checkbox" name="include_in_tech_view"
                                           class="checkbox text-left switchery_technician_view_modal" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Include in Customer View?</label>
                                <div class="col-md-7">
                                    <input id="include_in_customer_view" type="checkbox" name="include_in_customer_view"
                                           class="checkbox text-left switchery_customer_view_modal" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Is Urgent</label>
                                <div class="col-md-7">
                                    <input id="is_urgent" type="checkbox" name="is_urgent"
                                           class="checkbox text-left switchery_urgent_note_modal" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Notify Me</label>
                                <div class="col-md-7">
                                    <input id="notify_me" type="checkbox" name="notify_me"
                                           class="checkbox text-left switchery_notify_me_modal" checked>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Enable Notifications</label>
                                <div class="col-md-7">
                                    <input id="is_enable_notifications_modal" type="checkbox" name="is_enable_notifications" onchange="toggle_notification_to('_modal');"
                                           class="checkbox text-left switchery_enable_notification_modal">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group toggle_notification_to_modal" style="display: none;">
                                <label class="control-label col-md-5">Notification To</label>
                                <div class="multi-select-full col-lg-7">
                                    <select class="multiselect-select-all-filtering form-control note-filter" name="notification_to[]" id="notification_to" multiple="multiple">
                                        <?php
                                        foreach ($userdata as $user) {
                                            ?>
                                            <option value="<?= $user->id; ?>">
                                                <?= $user->user_first_name . ' ' . $user->user_last_name; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-5">Note Contents</label>
                                <div class="col-md-7">
                                    <textarea class="form-control" name="note_contents" id="note_contents"
                                              rows="5"></textarea>
                                    <span style="color:red;"><?php echo form_error('note_contents'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="text-right btn-space">
                    <button type="submit" id="savenote" class="btn btn-success">Save <i
                            class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>