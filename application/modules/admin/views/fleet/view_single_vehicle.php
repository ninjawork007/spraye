<style type="text/css">

    #loading {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: fixed;
        display: none;
        opacity: 0.7;
        background-color: #fff;
        z-index: 9999;
        text-align: center;
    }

    #loading-image {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 10%;
        z-index: 100;
    }

    th, td {
        text-align: center;
    }

    .pre-scrollable {
        min-height: 0px;
    }

    .radio-inline {
        color: #333 !important;
    }

    .text-caps {
        text-transform: uppercase;
    }

    /** Notes **/
    .properties-tab-btn {
        background-color: #01669a;
        color: #ffffff;
    }

    .properties-tab-active {
        background-color: #f9f9f9;
        color: #000000;
    }

    div.well.note-element {
        margin-bottom: 20px !important;
        border: 1px solid #ddd !important;
        border-radius: 3px !important;
        color: #333 !important;
        background-color: #fafafa !important;
        font-family: 'Roboto' !important;
    }

    div.well.note-element[is_urgent="1"] {
        border-radius: 3px !important;
        background-color: #FBE9E7 !important;
        border: 1px solid #FF5722 !important;
    }

    .row.note-body {
        font-size: 1.5rem;
        margin-bottom: 2rem;
    }

    div.note-footer {
        font-size: 1.5rem;
        font-weight: bold;
        color: #4a4a4a;
    }

    div.user-info {
        display: -webkit-box;
    }

    #note-header-right {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        align-items: center;
        font-size: 1.5rem;
    }

    div.user-image {
        margin-right: 1em;
    }

    div.note-details {
        font-size: 1.25rem;
        font-weight: bold;
    }

    ul.dropdown-menu li.dropdown-header {
        color: black;
        text-align: center;
        font-size: 1.5rem;
    }

    #note-header-right i.fa {
        margin: 0 1em;
        font-size: 2rem;
    }

    li.dropdown-menu-item {
        border-bottom: 1px solid #acacac80;
        font-size: 1rem;
    }

    li.dropdown-menu-item.text-muted.dropdown-menu-item-icon a {
        padding-left: 0;
    }

    @media (min-width: 769px) {
        .form-horizontal .control-label[class*=col-sm-] {
            padding-top: 0;
        }
    }

    .wrapper_pagination {
        padding-bottom: 50px;
    }

    li.dropdown-menu-item.text-muted.dropdown-menu-item-icon.disabled {
        pointer-events: none;
    }
</style>


<!-- Content area -->
<div class="content form-pg ">
    <!-- Form horizontal -->

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <div class="form-group">
                    <a href="<?= base_url('admin/allVehicles') ?>" id="backToVehicles" class="btn btn-success"><i
                            class=" icon-arrow-left7"> </i> Fleet Vehicles</a>
                    <div class="btn-group pull-right">
                        <a href="<?= base_url('admin/editVehiclePage/' . $vehicle->fleet_id) ?>"
                           class="btn btn-primary"><i class="icon-pencil position-left"></i> Edit Vehicle</a>
                        <a href="<?= base_url('admin/deleteVehicle/' . $vehicle->fleet_id) ?>"
                           class="btn btn-warning"><i class="icon-bin2 position-left"></i> Delete Vehicle</a>
                    </div>
                </div>
            </h5>
        </div>
        <br>
        <div class="panel-body">
            <b><?php if ($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
            <form class="form-horizontal" name="addVehicle">
                <input type="hidden" name="fleet_id" id="fleet_id" value="<?= $vehicle->fleet_id ?>">
                <fieldset class="content-group">
                    <div class="row invoice-form">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Vehicle VIN</label>
                                <div class="col-lg-9">
                                    <input type="text" name="v_vin" class="form-control text-caps"
                                           value="<?= $vehicle->v_vin ?>" id="v_vin" maxlength="17"
                                           placeholder="1FUPDXYB3PP469921" readonly>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">License Plate</label>
                                <div class="col-lg-9">
                                    <input type="text" name="v_plate" class="form-control text-caps" id="v_plate"
                                           maxlength="7" placeholder="AJFL70" value="<?= $vehicle->v_plate ?>" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row invoice-form">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Vehicle Type</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="v_type" id="v_type"
                                           value="<?= $vehicle->v_type ?>" disabled>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Vehicle Make</label>
                                <div class="col-lg-9">
                                    <input type="text" name="v_make" id="v_make" class="form-control" placeholder="Ford"
                                           value="<?= $vehicle->v_make ?>" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row invoice-form">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Vehicle Model</label>
                                <div class="col-lg-9">
                                    <input type="text" name="v_model" class="form-control" id="v_model"
                                           placeholder="F-150" value="<?= $vehicle->v_model ?>" readonly>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Vehicle Year</label>
                                <div class="col-lg-9">
                                    <input type="number" name="v_year" class="form-control" id="v_year" minlength="4"
                                           maxlength="4" min="1970" max="<?php echo date("Y"); ?>"
                                           placeholder="<?php echo date("Y"); ?>" value="<?= $vehicle->v_year ?>"
                                           readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row invoice-form">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Vehicle Name</label>
                                <div class="col-lg-9">
                                    <input type="text" name="v_name" class="form-control"
                                           value="<?= $vehicle->v_name ?? '' ?>" id="v_name" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Fleet Number</label>
                                <div class="col-lg-9">
                                    <input type="text" name="fleet_number" class="form-control"
                                           value="<?= $vehicle->fleet_number ?>" id="fleet_number" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row invoice-form">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Assigned Technician</label>
                                <div class="col-lg-9">
                                    <select class="bootstrap-select form-control" data-live-search="true"
                                            name="v_assigned_user" id="v_assigned_user" disabled>
                                        <?php
                                        if (!isset($vehicle->v_assigned_user)) { ?>
                                            <option value="" selected>None</option>
                                        <?php } else {
                                            foreach ($userdata as $user) {
                                                if ($user->id == $vehicle->v_assigned_user) {
                                                    ?>
                                                    <option value="<?= $user->id; ?>" selected>
                                                        <?= $user->user_first_name . ' ' . $user->user_last_name; ?>
                                                    </option>
                                                    <?php

                                                }
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>


                    <br>
                </fieldset>
                <!-- <div class="text-center">
                  <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i>
                  </button>
                </div> -->
            </form>

            <?php
            $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;
            $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();
            ?>
            <!-- Notes Tab Start -->
            <div id="note-form-wrap" class="collapse">
                <hr>
                <form class="form-horizontal" action="<?= base_url('admin/createVehicleNote') ?>" method="post"
                      name="createnoteform" enctype="multipart/form-data" id="createnoteform">
                    <fieldset class="content-group">
                        <input type="hidden" name="note_truck_id" class="form-control"
                               value="<?= $vehicle->fleet_id ?>">
                        <input type="hidden" name="note_user_id" class="form-control" value="<?= $currentUser->id ?>">
                        <input type="hidden" name="note_category" class="form-control" value="3">
                        <div class="row invoice-form">
<!--                            <div class="col-md-6">-->
<!--                                <div class="form-group">-->
<!--                                    <label class="control-label col-lg-3">Note Customer</label>-->
<!--                                    <div class="col-lg-9">-->
<!--                                        <select class="bootstrap-select form-control" data-live-search="true"-->
<!--                                                name="note_customer_id" id="note_customer_id" required>-->
<!--                                            --><?php //foreach ($customerlist as $value) : ?>
<!--                                                <option-->
<!--                                                    value="--><?php //= $value->customer_id; ?><!--">--><?php //= $value->first_name . ' ' . $value->last_name; ?><!--</option>-->
<!--                                            --><?php //endforeach; ?>
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Assign User</label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="note_assigned_user">
                                            <!-- Add Users available within company with Value = user_id / option shown user_name -->
                                            <option value="">None</option>
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
                                        <span style="color:red;">
                                <?php echo form_error('note_assigned_user'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row invoice-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Due Date</label>
                                    <div class="col-lg-9">
                                        <input id="note_due_date" type="text" name="note_due_date"
                                               class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Note Type</label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="note_type">
                                            <?php
                                            foreach ($note_types as $type) {
                                                ?>
                                                <option value="<?= $type->type_id; ?>">
                                                    <?= $type->type_name; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span style="color:red;">
                                <?php echo form_error('note_type'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row invoice-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-3">Attach Documents</label>
                                    <div class="col-lg-6">
                                        <input id="files" type="file" name="files[]" class="form-control-file" multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group checkbox">
                                    <label class="control-label col-lg-11" style="padding-left: 10px;">Include in Technician View?</label>
                                    <input id="include_in_tech_view" type="checkbox" name="include_in_tech_view"
                                           class="col-lg-1 checkbox switchery_technician_view" value="1">
                                </div>
                            </div>
                        </div>

                        <div class="row invoice-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-11">
                                        Urgent Note
                                    </label>
                                    <input type="checkbox"
                                           name="is_urgent"
                                           id="is_urgent"
                                           class="col-lg-1 checkbox checkbox-inline text-right switchery_urgent_note">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-11">
                                        Notify Me
                                    </label>
                                    <input type="checkbox"
                                           name="notify_me"
                                           id="notify_me"
                                           checked
                                           class="col-lg-1 checkbox checkbox-inline text-right switchery_notify_me">
                                </div>
                            </div>
                        </div>
                        <div class="row invoice-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-11">
                                        Enable Notification
                                    </label>
                                    <input type="checkbox" onchange="toggle_notification_to();"
                                           name="is_enable_notifications"
                                           id="is_enable_notifications"
                                           class="col-lg-3 checkbox checkbox-inline text-right switchery_enable_notification">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group toggle_notification_to" style="display: none;">
                                    <label class="control-label col-lg-3">
                                        Notification To</label>
                                    <div class="multi-select-full col-lg-9">
                                        <select class="multiselect-select-all-filtering form-control note-filter"
                                                name="notification_to[]" id="notification_to" multiple="multiple">
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
                                    <label class="control-label col-lg-3">Note Contents</label>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" name="note_contents" id="note_contents" rows="5"
                                                  required></textarea>
                                        <span style="color:red;">
                                <?php echo form_error('note_contents'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right btn-space">
                        <button type="button" class="btn btn-primary"
                                data-target="#note-form-wrap" data-toggle="collapse" aria-expanded="false"
                                aria-controls="note-form-wrap" style="margin-left=15px;">
                            Cancel
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="submit" id="savenote" class="btn btn-success">Save <i
                                class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>
            </div>
            <hr>
            <div id="note-type-filter">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group btn-group-justified" role="group" aria-label="tab-select">
                                <div class="btn-group properties-tab-parent" role="group">
                                    <button type="button"
                                            class="btn btn-default properties-tab-btn properties-tab-active"
                                            id="note-filter-all">All Notes
                                    </button>
                                </div>
                                <div class="btn-group properties-tab-parent" role="group">
                                    <button type="button" class="btn btn-default properties-tab-btn"
                                            id="note-filter-general">General Notes
                                    </button>
                                </div>
                                <div class="btn-group properties-tab-parent" role="group">
                                    <button type="button" class="btn btn-default properties-tab-btn"
                                            id="note-filter-maintenance">Maintenance Notes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="row">
                                <div class="col-sm-3 pull-right text-right text-white">
                                    <button type="button" class="btn btn-default" id="addNoteBtn"
                                            data-target="#note-form-wrap" data-toggle="collapse" aria-expanded="false"
                                            aria-controls="note-form-wrap" style="margin-left=15px;"><i
                                            id="addNoteBtnIco" class="icon-plus22"></i> Add New Note
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;
            $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();
            // die(print_r(json_encode($vehicle_notes)));
            ?>

            <div class="row" id="note_tab_contents">
                <div class="wrapper_pagination">
                    <?= $pagination_links; ?>
                    <div class="dataTables_length">
                        <label><span>Show:</span>
                            <select name="per_page" onchange="fleet_notes_filter()">
                                <?php foreach ($per_page_arr as $value) { ?>
                                    <option
                                        value="<?= $value ?>" <?= isset($filter['per_page']) && $filter['per_page'] == $value ? 'selected' : '' ?>><?= $value ?></option>
                                    <?php
                                } ?>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <?php if (!empty($vehicle_notes)) {
                        foreach ($vehicle_notes as $note) { ?>
                            <div
                                class="well note-element <?= ($note->note_type == '2') ? 'vehicle-general' : 'vehicle-maintenance'; ?>-note"
                                data-note-id="<?= $note->note_id; ?>" is_urgent="<?= $note->is_urgent; ?>">
                                <div class="row note-header">
                                    <div class="col-md-8 user-info">
                                        <div class="note-details">
                                            <?php
                                            if (!empty($note->note_customer_id)) {
                                                ?>
                                                <a href="<?= base_url() . 'admin/editCustomer/' . $note->note_customer_id ?>">
                                                    <h3 class="text-bold media-heading box-inline text-primary"><?= $note->customer_full_name; ?></h3>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            <p class="text-muted"><?= date_format(date_create($note->note_created_at), "H:i A | F j, Y"); ?></p>
                                        </div>
                                    </div>
                                    <div id="note-header-right" class="col-md-4 pull-right text-right">
                                        <?php if (!empty($note->note_assigned_user)) : ?>
                                            <span id="note-assigned-user-wrap-<?= $note->note_id; ?>"><span>Assigned to&nbsp;</span><span
                                                    class="text-success text-bold">
                              <?php
                              echo $note->user_assigned_full_name;
                              ?>
                              </span></span>
                                        <?php endif; ?>
                                        <div class="form-group hidden" id="update-assignuser-<?= $note->note_id; ?>">
                                            <label class="control-label col-lg-3">Assign User</label>
                                            <div class="col-lg-12">
                                                <select class="form-control" name="note_assigned_user"
                                                        id="note_assigned_user_<?= $note->note_id; ?>"
                                                        data-note-id="<?= $note->note_id; ?>"
                                                        data-note-userid="<?= $note->note_user_id; ?>"
                                                        onchange="getNoteAssignUserUpdateVars(this)">
                                                    <!-- Add Users available within company with Value = user_id / option shown user_name -->
                                                    <option
                                                        value="" <?= (empty($note->note_assigned_user)) ? 'selected' : ''; ?>>
                                                        None
                                                    </option>
                                                    <?php
                                                    foreach ($userdata as $user) {
                                                        if ($note->note_user_id) { ?>
                                                            <option
                                                                value="<?= $user->id; ?>" <?= ($user->id == $note->note_assigned_user) ? 'selected' : ''; ?>><?= $user->user_first_name . ' ' . $user->user_last_name; ?></option>
                                                        <?php }
                                                    }
                                                    ?>
                                                </select>
                                                <span
                                                    style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>
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
                                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2') ? ' disabled' : ''; ?>"
                                                    id="note-assign-btn-<?= $note->note_id; ?>"><a
                                                        href="javascript:showAssignUserSelect(<?= $note->note_id; ?>)"><i
                                                            class="fa fa-user-circle-o" aria-hidden="true"></i>Assign
                                                        Specific User</a></li>
                                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2') ? ' disabled' : ''; ?>">
                                                    <a href="javascript:showDueDateSelect(<?= $note->note_id; ?>)"><i
                                                            class="fa fa-calendar" aria-hidden="true"></i>Edit Due Date</a>
                                                </li>
                                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2') ? ' disabled' : ''; ?>">
                                                    <a href="javascript:showNoteTypeSelect(<?= $note->note_id; ?>)"><i
                                                            class="fa fa-pencil-square-o" aria-hidden="true"></i>Change
                                                        Note Type</a></li>


                                                <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == '2') ? ' disabled' : ''; ?>">
                                                    <a href="javascript:$('.note_edit_notification_to_<?= $note->note_id; ?>').toggleClass('hidden')"><i
                                                            class="fa fa-pencil-square-o"
                                                            aria-hidden="true"></i>Change Note Notification To</a>
                                                </li>

                                                <?php
                                                if (isset($currentUser->role_id) && $currentUser->role_id < 3) {
                                                    ?>
                                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon"><a
                                                            href="<?= base_url('admin/markNoteComplete/') . $note->note_id; ?>"><i
                                                                class="fa fa-check-square-o" aria-hidden="true"></i>Mark
                                                            Complete</a></li>
                                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon">
                                                        <a href="<?= base_url('admin/toggleUrgentMarker/') . $note->note_id . '/' . ($note->is_urgent ? 0 : 1); ?>"><i
                                                                class="fa fa-check-square-o" aria-hidden="true"></i><?= $note->is_urgent ? 'Remove Urgent Status' : 'Mark Urgent'; ?></a>
                                                    </li>
                                                    <li class="dropdown-menu-item text-muted dropdown-menu-item-icon"><a
                                                            href="<?= base_url('admin/deleteNote/') . $note->note_id; ?>"><i
                                                                class="fa fa-trash-o" aria-hidden="true"></i>Delete Note</a>
                                                    </li>
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

                                <div class="row">
                                    <div class="col-md-6">

                                        <?php
                                        if ($note->note_type == 1) {
                                            ?>
                                            <span id="note-assigned-type-wrap-<?= $note->note_id; ?>"
                                                  class="text-bold text-success" style="font-size: 1.2em">Task</span>
                                            <?php
                                        } else {
                                            $type_name = null;
                                            foreach ($note_types as $type) {
                                                if ($note->note_type == $type->type_id) {
                                                    $type_name = $type->type_name;
                                                }
                                            }
                                            ?>
                                            <span id="note-assigned-type-wrap-<?= $note->note_id; ?>"
                                                  class="text-bold text-success"
                                                  style="font-size: 1.2em"><?= $type_name; ?></span>
                                            <?php
                                        }
                                        ?>

                                        <div class="form-group hidden" id="update-notetype-<?= $note->note_id; ?>">
                                            <label class="control-label col-lg-3">Note Type</label>
                                            <div class="col-lg-12">
                                                <select class="form-control" name="note_edit_type"
                                                        id="note_edit_type_<?= $note->note_id; ?>"
                                                        data-note-id="<?= $note->note_id; ?>"
                                                        data-note-typeid="<?= $note->note_type; ?>"
                                                        onchange="getNoteTypeUpdateVars(this)">
                                                    <!-- Add types available within company with Value = type_id / option shown type_name -->
                                                    <option value="" disabled selected></option>
                                                    <option value="0">Task</option>
                                                    <?php foreach ($note_types as $type) : ?>
                                                        <option
                                                            value="<?= $type->type_id; ?>"><?= $type->type_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span style="color:red;"><?php echo form_error('note_type'); ?></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-3 pull-right text-right">
                                        Tech Visible<i class="fa fa-low-vision" aria-hidden="true"
                                                       style="margin-left: 5px; margin-right: 5px;"></i> <input
                                            type="checkbox"
                                            onclick="techVisibleSwtch(this,<?= $note->note_id; ?>)"<?= ($note->include_in_tech_view == 1) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                                <?php if (isset($note->inspection)) { ?>
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <a href="<?= base_url('admin/viewVehicleInspection/' . $note->inspection->v_insp_id) ?>"
                                               class="btn btn-info">View Inspection Report</a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <hr>
                                <div class="row note-footer note-footer-flex">
                                    <div class="col-md-7 note-footer-left">
                                        <div class="row">
                                            <div class="status col-sm-12 col-md-4 col-lg-3 text-bold">
                                                <?php
                                                if ($note->note_type == 1 && !empty($note->note_assigned_user)) {
                                                    ?>
                                                    Status: <?= ($note->note_status == '1') ? '<span class="text-success">OPEN</span>' : '<span class="text-warning">CLOSED</span>'; ?>
                                                    <?php
                                                } elseif ($note->note_type == 3 && !empty($note->maintenance_entry)) {
                                                    ?>
                                                    Status: <?= ($note->maintenance_entry->mnt_status) ? '<span class="text-success">OPEN</span>' : '<span class="text-warning">CLOSED</span>'; ?>
                                                    <?php
                                                } else {
                                                    ?>
                                                    Status: <span class="text-muted">None</span>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if (isset($note->user_first_name) && isset($note->user_last_name)) {
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
                                            <div
                                                class="col-md-8 note-due-date text-warning text-uppercase text-bold text-center">
                                                <i class="fa fa-flag" aria-hidden="true"></i> DUE: <span
                                                    id="note-duedate-<?= $note->note_id; ?>"><?= ($note->note_due_date != '0000-00-00' && !empty($note->note_due_date)) ? $note->note_due_date : 'None Set'; ?></span><input
                                                    id="note_due_date_<?= $note->note_id; ?>" type="text"
                                                    name="note_due_date" class="form-control pickaalldate hidden"
                                                    placeholder="YYYY-MM-DD" data-noteid="<?= $note->note_id; ?>"
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
                                                        <span
                                                            id="comment-count-value-<?= $note->note_id; ?>"><?= count($note->comments); ?></span>
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
                                                        <strong><?= $comment->user_first_name . ' ' . $comment->user_last_name; ?>
                                                            : </strong><?= $comment->comment_body; ?>
                                                    </li>
                                                <?php } ?>
                                                <li class="list-group-item comment-list-item">
                                                    <form action="<?= base_url('admin/addNoteComment') ?>" method="post"
                                                          name="add-note-comment-form" enctype="multipart/form-data"
                                                          id="add-note-comment-form-<?= $note->note_id; ?>"
                                                          onsubmit="addCommentAjax('<?= $note->note_id; ?>')">
                                                        <input type="hidden"
                                                               value="<?= $this->session->userdata('id'); ?>"
                                                               name="comment-userid">
                                                        <input type="hidden" value="<?= $note->note_id; ?>"
                                                               name="comment-noteid">
                                                        <div class="input-group">
                                                            <input class="form-control" name="add-comment-input" id=""
                                                                   placeholder="Add Comment">
                                                            <div class="input-group-btn">
                                                                <!-- Buttons -->
                                                                <button type="submit"
                                                                        class="btn btn-primary pull-right">Post Comment
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
                                                <form action="<?= base_url('admin/addToNoteFiles'); ?>" method="post"
                                                      enctype="multipart/form-data">
                                                    <input type="hidden" value="<?= $this->session->userdata('id'); ?>"
                                                           name="user_id">
                                                    <input type="hidden" value="<?= $note->note_id; ?>" name="note_id">
                                                    <div class="row row-extra-space">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label col-lg-4 text-right">Attach
                                                                    Documents</label>
                                                                <div class="col-lg-8 text-left">
                                                                    <input id="files" type="file" name="files[]"
                                                                           class="form-control-file" multiple>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary pull-right">Save
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
                        <div class="well general-vehicle-note">
                            <p>No Notes to Display Yet...</p>
                        </div>
                    <?php } ?>
                </div>
            </div>


            <!-- Notes Tab End -->


        </div>
    </div>
</div>
<!-- /form horizontal -->


<!-- Files Modal -->
<div id="file-display-modal" class="modal-files">
    <span class="close" id="close-file-display">&times;</span>
    <img class="modal-content" id="modal-file-image">
    <div id="caption"></div>
</div>

<script>
    $(function () {
        $('#note-filter-all, #note-filter-general, #note-filter-maintenance').click(function (e) {
            Array.from($('.properties-tab-btn')).forEach((btn) => $(btn).removeClass('properties-tab-active'));
            $(this).addClass('properties-tab-active');
            fleet_notes_filter();
        });

        $('#note-form-wrap').on('show.bs.collapse', () => {
            $('#addNoteBtnIco').removeClass('icon-plus22')
                .addClass('fa fa-minus');
        });
        $('#note-form-wrap').on('hide.bs.collapse', () => {
            $('#addNoteBtnIco').removeClass('fa fa-minus')
                .addClass('icon-plus22');
        });
    });

    // Note Files
    function displayFileModal(imgEl) {
        let src = imgEl.src;
        let fileName = imgEl.alt;
        let modal = document.getElementById('file-display-modal');
        let modalImg = document.getElementById('modal-file-image');
        let captionText = document.getElementById('caption');
        modal.style.display = "block";
        modalImg.src = src;
        captionText.innerHTML = fileName;
        var span = document.getElementById('close-file-display');

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }
    }
</script>
<script>
    function getNoteAssignUserUpdateVars(el) {
        let userId = $(el).val();
        let noteId = $(el).data('note-id');
        let noteOwnerId = $(el).data('note-userid');
        let userName = $(el.options[el.options.selectedIndex]).text();
        updateAssignUser(userId, noteId, userName);
    }

    function getNoteTypeUpdateVars(el) {
        let typeId = $(el).val();
        let noteId = $(el).data('note-id');
        let currentTypeId = $(el).data('note-typeid');
        let typeName = $(el.options[el.options.selectedIndex]).text();
        let idMatch = (typeId == currentTypeId);
        updateAssignType(typeId, noteId, typeName, idMatch);
    }

    function showAssignUserSelect(noteId) {
        $(`#update-assignuser-${noteId}`).removeClass('hidden');
    }

    function showNoteTypeSelect(noteId) {
        $(`#update-notetype-${noteId}`).removeClass('hidden');
    }

    function updateAssignUser(userId, noteId, userName) {
        $.post("<?= base_url('admin/updateAssignUser'); ?>", {'noteId': noteId, 'userId': userId}, function (result) {
            $(`#note-assigned-user-wrap-${noteId}`).remove();
            if (userId != '') {
                $(`<span id="note-assigned-user-wrap-${noteId}"><span>Assigned to&nbsp;</span><span class="text-success text-bold"> ${userName}</span></span>`).insertBefore(`#update-assignuser-${noteId}`);
            }
            $(`#update-assignuser-${noteId}`).addClass('hidden');
        });
    }

    function updateAssignType(typeId, noteId, typeName, match) {
        $.post("<?= base_url('admin/updateAssignType'); ?>", {'noteId': noteId, 'typeId': typeId}, function (result) {
            $(`#note-assigned-type-wrap-${noteId}`).remove();
            if (!match) {
                $(`<span id="#note-assigned-type-wrap-${noteId}" class="text-bold text-success" style="font-size: 1.2em">${typeName}</span>`).insertBefore(`#update-notetype-${noteId}`);
            }
            $(`#update-notetype-${noteId}`).addClass('hidden');

        });
    }

    function showDueDateSelect(noteId) {
        $(`#note_due_date_${noteId}`).removeClass('hidden');
    }

    function updateNoteDueDate(el) {
        let noteId = $(el).data('noteid');
        let dueDate = $(el).val();
        if (dueDate != '') {
            $.post("<?= base_url('admin/updateNoteDueDate'); ?>", {
                'noteId': noteId,
                'dueDate': dueDate
            }, function (result) {
                $(`#note-duedate-${noteId}`).text(dueDate);
            });
        }
        $(el).val('').addClass('hidden');
    }

    function addCommentAjax(noteId) {
        event.preventDefault();
        let form = $(`#add-note-comment-form-${noteId}`)[0];
        let data = new FormData(form);
        $(`#add-note-comment-form-${noteId} input[name="add-comment-input"]`).val('');
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '<?= base_url('admin/addNoteCommentAjax'); ?>',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                console.log("Success : ", data);
                let result = JSON.parse(data);
                console.log("Success : ", result);
                let comment = `<li class="list-group-item comment-list-item">
                          <strong>${result.user_first_name} ${result.user_last_name}: </strong>${result.comment_body}
                        </li>`;
                $(form).parent().before(comment);
                $(`#comment-count-value-${result.note_id}`).text(`${result.comment_count}`);
            },
            error: function (e) {
                console.log("ERROR : ", e);
            }
        });

    }

    $(document).ready(function () {

        var config = {
            color: '#36c9c9',
            secondaryColor: "#dfdfdf",
        };
        var urgent_note = document.querySelector('.switchery_urgent_note');
        var switchery_urgent_note = new Switchery(urgent_note, config);

        var notify_me = document.querySelector('.switchery_notify_me');
        var switchery_notify_me = new Switchery(notify_me, config);
        var enable_notification = document.querySelector('.switchery_enable_notification');
        var switchery_enable_notification = new Switchery(enable_notification, config);
        var technician_view = document.querySelector('.switchery_technician_view');
        var switchery_technician_view = new Switchery(technician_view, config);

        $('.page-link').on('click', function (e) {
            e.preventDefault();
            let page = parseInt($(this).text()) ? $(this).text() : $(this).attr('data-ci-pagination-page');
            fleet_notes_filter(page);
        });
    });

    function toggle_notification_to() {
        if ($('#is_enable_notifications').is(':checked')) {
            $('.toggle_notification_to').show();
        } else {
            $('.toggle_notification_to').hide();
        }
    }

    function changeNoteNotificationTo(e, id) {

        $.ajax({
            url: "<?= base_url('admin/updateNoteNotificationTo') ?>",
            data: {
                note_id: id,
                notification_to: $(e).parent().parent().find('select').val()
            },
            type: "POST",
            dataType: 'json',
            beforeSend: function () {
                $('#loading').css("display", "block");
            },
            success: function (response) {
                $('#loading').css("display", "none");
                if (!response.error) {
                    Swal(
                        'Notification Updated',
                        response.message,
                        'success'
                    )
                } else {
                    Swal(
                        'Notification Fail',
                        response.message,
                        'error'
                    )
                }
                $('.note_edit_notification_to_' + id).toggleClass('hidden');
            }

        });
    }

    function fleet_notes_filter(page = 1) {
        let note_type = $('.properties-tab-active')[0].id.split('-')[2];
        $.ajax({
            type: 'POST',
            url: '<?= base_url(); ?>admin/ajaxViewSingleVehicle?page=' + page,
            data: {
                note_type: note_type,
                fleet_id: $('#fleet_id').val(),
                page: page,
                per_page: $('[name="per_page"]').val()
            },
            beforeSend: function () {
                $('#note_tab_contents').html('');
                $('.loading').css("display", "block");
            },
            success: function (html) {
                $(".loading").css("display", "none");
                $('#note_tab_contents').html(html);
            }
        });
    }

    function techVisibleSwtch(el, id) {
        let num = (el.checked) ? 1 : 0;
        $.post("<?= base_url('admin/updateNoteTechView'); ?>", {'noteId': id, 'tech_view': num}, function (result) {
            console.log(result);
        });
    }
</script>
<!-- Debug Var Dumps -->
<script>
    var currentUser = <?= print_r(json_encode($currentUser), TRUE); ?>;
    var notes = <?= print_r(json_encode($vehicle_notes), TRUE); ?>;

</script>