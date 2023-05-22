
<?php

$isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;

$currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();

?>

<div class="col-md-12">

    <?php if (!empty($property_notes)) {

        foreach ($property_notes as $note) { ?>

            <div class="well property-note" data-note-id="<?= $note->note_id; ?>"
                 data-note-status="<?= (!empty($note->note_assigned_user)) ? $note->note_status : '0'; ?>"
                 is_urgent="<?= $note->is_urgent; ?>"
            >

                <div class="row note-header">

                    <div class="col-md-8 user-info">
                        <div class="note-details">
                            <?php if ($note->note_customer_id) : ?>
                                <a href="<?= base_url() . 'admin/editCustomer/' . $note->note_customer_id ?>">
                                    <h3 class="text-bold media-heading box-inline text-primary"><?= $note->customer_full_name; ?></h3>
                                </a>
                            <?php endif ?>
                            <p class="text-muted"><?= date_format(date_create($note->note_created_at), "H:i A | F j, Y"); ?></p>

                        </div>

                    </div>

                    <div id="note-header-right" class="col-md-4 pull-right text-right">

                        <?php if (!empty($note->note_assigned_user)) : ?>

                            <span id="note-assigned-user-wrap-<?= $note->note_id; ?>"><span>Assigned to&nbsp;</span><span
                                    class="text-success text-bold">
                                                        <?= $note->user_assigned_full_name; ?>

                                            </span></span>

                        <?php endif; ?>

                        <div class="form-group hidden"
                             id="update-assignuser-<?= $note->note_id; ?>">

                            <label class="control-label col-lg-3">Assign User</label>

                            <div class="col-lg-12">

                                <select class="form-control" name="note_assigned_user"
                                        id="note_assigned_user_<?= $note->note_id; ?>"
                                        data-note-id="<?= $note->note_id; ?>"
                                        data-note-userid="<?= $note->note_user_id; ?>"
                                        onchange="getNoteAssignUserUpdateVars(this)">

                                    <!-- Add Users available within company with Value = user_id / option shown user_name -->

                                    <option value="" <?= (empty($note->note_assigned_user)) ? 'selected' : ''; ?>>
                                        None
                                    </option>

                                    <?php

                                    foreach ($userdata as $user) {

                                        if ($note->note_user_id) { ?>

                                            <option value="<?= $user->id; ?>" <?= ($user->id == $note->note_assigned_user) ? 'selected' : ''; ?>><?= $user->user_first_name . ' ' . $user->user_last_name; ?></option>

                                        <?php }

                                    }

                                    ?>

                                </select>

                                <span style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>

                            </div>

                        </div>

                        <div class="dropdown">

                              <span class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="true">

                                <i class="fa fa-ellipsis-h fa-xl" aria-hidden="true"></i>

                              </span>

                            <ul class="dropdown-menu dropdown-menu-right"
                                aria-labelledby="dropdownMenu1">

                                <li class="dropdown-header text-bold text-uppercase">Actions</li>

                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>"
                                    id="note-assign-btn-<?= $note->note_id; ?>"><a
                                        href="javascript:showAssignUserSelect(<?= $note->note_id; ?>)"><i
                                            class="fa fa-user-circle-o" aria-hidden="true"></i>Assign
                                        Specific User</a></li>

                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>">
                                    <a href="javascript:showDueDateSelect(<?= $note->note_id; ?>)"><i
                                            class="fa fa-calendar" aria-hidden="true"></i>Edit
                                        Due Date</a></li>

                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>">
                                    <a href="javascript:showNoteTypeSelect(<?= $note->note_id; ?>)"><i
                                            class="fa fa-pencil-square-o"
                                            aria-hidden="true"></i>Change Note Type</a></li>

                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2') ? ' disabled' : ''; ?>">
                                    <a href="javascript:$('.note_edit_notification_to_<?= $note->note_id; ?>').toggleClass('hidden')"><i
                                            class="fa fa-pencil-square-o"
                                            aria-hidden="true"></i>Change Note Notification To</a>
                                </li>

                                <?php

                                if (isset($currentUser->role_id) && $currentUser->role_id < 3) {

                                    ?>

                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon">
                                        <a href="<?= base_url('admin/markNoteComplete/') . $note->note_id; ?>"><i
                                                class="fa fa-check-square-o"
                                                aria-hidden="true"></i>Mark Complete</a></li>
                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon">
                                        <a href="<?= base_url('admin/toggleUrgentMarker/') . $note->note_id . '/' . ($note->is_urgent ? 0 : 1); ?>"><i
                                                class="fa fa-check-square-o" aria-hidden="true"></i><?= $note->is_urgent ? 'Remove Urgent Status' : 'Mark Urgent'; ?></a></li>

                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon">
                                        <a href="<?= base_url('admin/deleteNote/') . $note->note_id; ?>"><i
                                                class="fa fa-trash-o" aria-hidden="true"></i>Delete
                                            Note</a></li>

                                    <?php

                                } else {
                                    $not_owner_note = ($currentUser->id !== $note->note_assigned_user && $currentUser->id !== $note->note_user_id);
                                    ?>
                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2' || $not_owner_note) ? ' disabled' : ''; ?>">
                                        <a href="<?= !($note->note_status == '2' || $not_owner_note) ? base_url('admin/markNoteComplete/') . $note->note_id : 'javascript:void(0)'; ?>"><i
                                                class="fa fa-check-square-o" aria-hidden="true"></i>Mark
                                            Complete</a></li>
                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2' || $not_owner_note) ? ' disabled' : ''; ?>">
                                        <a href="<?= !($note->note_status == '2' || $not_owner_note) ? base_url('admin/toggleUrgentMarker/') . $note->note_id . '/' . ($note->is_urgent ? 0 : 1) : 'javascript:void(0)'; ?>"><i
                                                class="fa fa-check-square-o" aria-hidden="true"></i><?= $note->is_urgent ? 'Remove Urgent Status' : 'Mark Urgent'; ?></a>
                                    </li>
                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= $not_owner_note ? ' disabled' : ''; ?>">
                                        <a href="<?= base_url('admin/deleteNote/') . $note->note_id; ?>"><i
                                                class="fa fa-trash-o" aria-hidden="true"></i>Delete Note</a>
                                    </li>
                                    <?php

                                }

                                ?>

                            </ul>

                        </div>

                    </div>

                </div>

                <div class="row note-body">

                    <div class="col-md-12">

                        <p><?= $note->note_contents; ?></p>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6"
                         id="note-assigned-type-wrapper-div-<?= $note->note_id; ?>">


                        <?php

                        if ($note->note_type == 1) {

                            ?>

                            <span id="note-assigned-type-wrap-<?= $note->note_id; ?>"
                                  class="text-bold text-success"
                                  style="font-size: 1.2em">Task</span>

                            <?php

                        } else {

                            $type_name = null;

                            foreach ($note_types as $type) {

                                if ($note->note_type == $type->type_id) {

                                    $type_name = $type->type_name;

                                }

                            }

                            if (isset($type_name)) {

                                ?>
                                <span id="note-assigned-type-wrap-<?= $note->note_id; ?>"
                                      class="text-bold text-success"
                                      style="font-size: 1.2em"><?= (isset($type_name)) ? $type_name : ''; ?></span>

                                <?php if ($type_name == "Service-Specific") {
                                    $duration = "";
                                    if (isset($note->assigned_service_note_duration) && $note->assigned_service_note_duration == 1) {
                                        $duration = " (Permanent)";
                                    } elseif (isset($note->assigned_service_note_duration) && $note->assigned_service_note_duration == 2) {
                                        $duration = " (Next Service Only)";
                                    }
                                    if (isset($note->job_name) && !empty($note->job_name)) { ?>
                                        <br><span
                                            id="note-assigned-service-wrap-<?= $note->note_id; ?>"
                                            class="text-success"
                                            style="font-size: 1.2em"><?= $note->job_name ?><?= $duration ?></span>
                                    <?php }
                                }
                            }
                        }

                        ?>

                    </div>

                    <div class="col-md-3 pull-right text-right">

                        Tech Visible<i class="fa fa-question" aria-hidden="true"
                                       style="margin-right: 5px;"></i> <input type="checkbox"
                                                                              onclick="techVisibleSwtch(this,<?= $note->note_id; ?>)"<?= ($note->include_in_tech_view == 1) ? 'checked' : ''; ?>>

                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group hidden" id="update-notetype-<?= $note->note_id; ?>">
                            <label class="control-label">Note Type</label>
                            <select class="form-control" name="note_edit_type"
                                    id="note_edit_type_<?= $note->note_id; ?>"
                                    data-note-id="<?= $note->note_id; ?>"
                                    data-note-typeid="<?= $note->note_type; ?>"
                                    onchange="getNoteTypeUpdateVars(this)">
                                <!-- Add types available within company with Value = type_id / option shown type_name -->
                                <option value="" disabled selected>None</option>
                                <?php
                                $service_specific = 'false';
                                foreach ($note_types as $type) :
                                    if ($type->type_id == $service_specific_note_type_id) {
                                        $service_specific = 'true';
                                    }
                                    ?>
                                    <option value="<?= $type->type_id; ?>"
                                            data-servicespecific=<?= $service_specific ?>><?= $type->type_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span style="color:red;"><?php echo form_error('note_type'); ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group hidden"
                             id="update-notetype-services<?= $note->note_id; ?>">
                            <label class="control-label">Assign Services</label>
                            <select class="form-control" name="note_edit_assigned_services"
                                    id="note_edit_assigned_services<?= $note->note_id; ?>"
                                    data-note-id="<?= $note->note_id; ?>">
                                <option value="">None</option>
                                <?php
                                foreach ($servicelist as $service) {
                                    ?>
                                    <option value="<?= $service->job_id; ?>"><?= $service->job_name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <span style="color:red;"><?php echo form_error('note_edit_assigned_user'); ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group hidden"
                             id="update-notetype-duration<?= $note->note_id; ?>">
                            <label class="control-label">Note Duration</label>
                            <select class="form-control" name="edit_assigned_service_note_duration"
                                    id="edit_assigned_service_note_duration<?= $note->note_id; ?>"
                                    data-note-typeid="<?= $note->note_type; ?>"
                                    data-note-id="<?= $note->note_id; ?>"
                                    onchange="getNoteTypeUpdateServiceSpecificVars(this)">
                                <option value="">None</option>
                                <option value=1>Permanent</option>
                                <option value=2>Next Service Only</option>
                            </select>
                            <span style="color:red;"><?php echo form_error('edit_assigned_service_note_duration'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row hidden note_edit_notification_to_<?= $note->note_id;?>">
                    <label class="control-label col-lg-12" style="font-size: 16px;">
                        Notification To</label>
                    <div class="multi-select-full col-lg-3">
                        <select class="multiselect-select-all-filtering form-control note-filter"
                                name="note_edit_notification_to[]"
                                id="note_edit_notification_to"
                                multiple="multiple">
                            <?php
                            foreach ($userdata as $user) {
                                ?>
                                <option value="<?= $user->id; ?>" <?= !empty($note->notification_to) && in_array($user->id, explode(',', $note->notification_to)) ? 'selected' : '' ?>>
                                    <?= $user->user_first_name . ' ' . $user->user_last_name; ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 text-white">
                        <button type="button" class="btn btn-success" onclick="changeNoteNotificationTo(this, <?= $note->note_id; ?>)">
                            Save Changes
                        </button>
                    </div>
                </div>

                <hr>

                <div class="row note-footer note-footer-flex">

                    <div class="col-md-7 note-footer-left">

                        <div class="row">

                            <div class="status col-sm-12 col-md-4 col-lg-3 text-bold">

                                <?php

                                if (!empty($note->note_status)) {

                                    ?>

                                    Status: <?= ($note->note_status == 1) ? '<span class="text-success">OPEN</span>' : '<span class="text-warning">CLOSED</span>'; ?>

                                    <?php

                                } else {

                                    ?>

                                    Status: <span class="text-muted">None</span>

                                    <?php

                                }

                                ?>

                            </div>

                            <?php

                            if (isset($note->note_user_id)) {
                                ?>
                                <div class="creator-name col-sm-12 col-md-4 col-lg-3 ">
                                    <span>Created by&nbsp;</span>
                                    <span class="text-bold">
                                                                <?= $note->user_first_name; ?> <?= $note->user_last_name; ?>
                                                            </span>
                                </div>
                                <?php
                            }
                            ?>

                            <?php

                            if (isset($note->property_address) && isset($note->property_city)) {

                                ?>

                                <div class="customer-address col-sm-12 col-md-8 col-lg-6 text-bold">

                                    <i class="fa fa-map-marker"
                                       aria-hidden="true"></i> <?= $note->property_address; ?>
                                    , <?= $note->property_city; ?>

                                </div>

                                <?php

                            }

                            ?>

                        </div>

                    </div>

                    <div class="col-md-4 note-footer-right pull-right">

                        <div class="row">

                            <div class="col-md-8 note-due-date text-warning text-uppercase text-bold text-center">

                                <i class="fa fa-flag" aria-hidden="true"></i> DUE: <span
                                    id="note-duedate-<?= $note->note_id; ?>"><?= ($note->note_due_date != '0000-00-00' && !empty($note->note_due_date)) ? $note->note_due_date : 'None Set'; ?></span><input
                                    id="note_due_date_<?= $note->note_id; ?>" type="text"
                                    name="note_due_date"
                                    class="form-control pickaalldate hidden"
                                    placeholder="YYYY-MM-DD"
                                    data-noteid="<?= $note->note_id; ?>"
                                    onchange="updateNoteDueDate(this)">

                            </div>

                            <div class="col-md-3 pull-right">

                                <div class="row">

                                    <div class="col-md-6 note-comments">

                                        <i class="fa fa-comment-o" aria-hidden="true"
                                           data-toggle="collapse"
                                           data-target="#note-comments-<?= $note->note_id; ?>"
                                           aria-expanded="false"
                                           aria-controls="note-comments-<?= $note->note_id; ?>"></i>
                                        <span id="comment-count-value-<?= $note->note_id; ?>"><?= count($note->comments); ?></span>

                                    </div>

                                    <div class="col-md-6 note-attachments">

                                        <i class="fa fa-paperclip" aria-hidden="true"
                                           data-toggle="collapse"
                                           data-target="#note-files-<?= $note->note_id; ?>"
                                           aria-expanded="false"
                                           aria-controls="note-files-<?= $note->note_id; ?>"></i> <?= count($note->files); ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="note-comments collapse" id="note-comments-<?= $note->note_id; ?>">

                    <hr>

                    <div class="row">

                        <div class="col-md-12">

                            <h4><strong>Comments</strong></h4>

                            <ul class="list-group comments-list-group">

                                <?php foreach ($note->comments as $comment) { ?>

                                    <li class="list-group-item comment-list-item">

                                        <small class="text-muted"><?= $comment->comment_created_at ?></small>
                                        <strong><?= $comment->user_first_name . ' ' . $comment->user_last_name; ?>
                                            : </strong><?= $comment->comment_body; ?>

                                    </li>

                                <?php } ?>

                                <li class="list-group-item comment-list-item">

                                    <form action="<?= base_url('admin/addNoteComment') ?>"
                                          method="post" name="add-note-comment-form"
                                          enctype="multipart/form-data"
                                          id="add-note-comment-form-<?= $note->note_id; ?>"
                                          onsubmit="addCommentAjax('<?= $note->note_id; ?>')">

                                        <input type="hidden"
                                               value="<?= $this->session->userdata('id'); ?>"
                                               name="comment-userid">

                                        <input type="hidden" value="<?= $note->note_id; ?>"
                                               name="comment-noteid">

                                        <div class="input-group">

                                            <input class="form-control" name="add-comment-input"
                                                   id="" placeholder="Add Comment">

                                            <div class="input-group-btn">

                                                <!-- Buttons -->

                                                <button type="submit"
                                                        class="btn btn-primary pull-right">Post
                                                    Comment
                                                </button>

                                            </div>

                                        </div>

                                    </form>

                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

                <div class="note-files collapse" id="note-files-<?= $note->note_id; ?>">

                    <hr>

                    <div class="row">

                        <div class="col-md-12">

                            <div class="row">

                                <div class="col-md-6">

                                    <h4><strong>Attachments</strong></h4>

                                </div>

                                <div class="col-md-6 text-right add-files-right">

                                    <strong>Attach Additional Files</strong> <i
                                        class="icon-cloud-upload btn-ico" aria-hidden="true"
                                        data-toggle="collapse"
                                        data-target="#note-fileupload-<?= $note->note_id; ?>"
                                        aria-expanded="false"
                                        aria-controls="note-fileupload-<?= $note->note_id; ?>"
                                        style="font-size: 2em; padding-left: 10px;"></i>

                                </div>

                            </div>

                            <div class="col-md-4 col-lg-3 pull-right collapse"
                                 id="note-fileupload-<?= $note->note_id; ?>">

                                <form action="<?= base_url('admin/addToNoteFiles'); ?>"
                                      method="post" enctype="multipart/form-data"
                                      onsubmit="formFileSizeValidate(this)">

                                    <input type="hidden"
                                           value="<?= $this->session->userdata('id'); ?>"
                                           name="user_id">

                                    <input type="hidden" value="<?= $note->note_id; ?>"
                                           name="note_id">

                                    <div class="row row-extra-space">

                                        <div class="col-xs-12">

                                            <div class="form-group">

                                                <label class="control-label col-lg-4 text-right">Attach
                                                    Documents</label>

                                                <div class="col-lg-8 text-left">

                                                    <input id="files" type="file" name="files[]"
                                                           class="form-control-file" multiple
                                                           onchange="fileValidationCheck(this)">
                                                    <span style="color:red;"><?php echo form_error('files'); ?></span>

                                                </div>

                                            </div>

                                        </div>

                                        <button type="submit" class="btn btn-primary pull-right">
                                            Save
                                        </button>

                                    </div>

                                </form>

                            </div>

                            <div class="row">

                                <?php foreach ($note->files as $file) {

                                    $ext = pathinfo(CLOUDFRONT_URL . $file->file_key, PATHINFO_EXTENSION);

                                    if ($ext == 'pdf') { ?>

                                        <div class="col-xs-4 col-md-2 text-center">

                                            <label><?= $file->file_name; ?></label><br>

                                            <a href="<?= CLOUDFRONT_URL . $file->file_key; ?>"
                                               target="_blank">

                                                <i class="fa fa-file-code-o file-attach-icon"
                                                   aria-hidden="true"></i>

                                            </a>

                                        </div>

                                    <?php } else { ?>

                                        <div class="col-xs-4 col-md-2 text-center">

                                            <label><?= $file->file_name; ?></label><br>

                                            <img src="<?= CLOUDFRONT_URL . $file->file_key; ?>"
                                                 alt="<?= $file->file_name; ?>"
                                                 class="img-responsive thumbnail files-thumbnail"
                                                 onclick="displayFileModal(this)">

                                        </div>

                                    <?php }

                                } ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php }

    } else { ?>

        <div class="well property-note">

            <p>No Notes to Display Yet...</p>

        </div>

    <?php } ?>

</div>
<div class="col-md-12">
    <?= $pagination_links; ?>
    <div class="dataTables_length">
        <label><span>Show:</span>
            <select name="per_page" onchange="property_notes_filter()">
                <?php foreach ($per_page_arr as $value) { ?>
                    <option value="<?= $value ?>" <?= isset($filter['per_page']) && $filter['per_page'] == $value ? 'selected' : '' ?>><?= $value ?></option>
                    <?php
                } ?>
            </select>
        </label>
    </div>
</div>


<script>
    $(document).ready(function () {
        reinitMultiselect();
        $('.page-link').off('click');
        $('.page-link').on('click', function (e) {
            e.preventDefault();
            let page = parseInt($(this).text()) ? $(this).text() : $(this).attr('data-ci-pagination-page');
            property_notes_filter(page);
        });
    })
</script>