<style>
.content {
    padding: 20px 20px 60px !important;
}

.test {
    padding-left: 12px !important;
}

.test1 {
    padding-left: 43px !important;
}

.fc-scroller {
    height: 500px ! important;
}

.widgetmydiv header p a {
    color: #1f567c !important;
}

.widgetmydiv .widget-logo {
    display: none !important;
}

.widgetmydiv header p:last-child {
    font-size: 1.2em !important;
}

.alleditbtn {
    float: left;
    padding-left: 5px;
}

.tmpspan {
    float: left;
    margin: 8px 15px 8px 0;
}

.dataTables_filter input {
    margin-left: 10px;
}

.btn-group {

    margin-left: 5px !important;
    margin-top: 1px !important;

}
</style>
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

@media only screen and (max-width: 600px) {
    .calederview {
        display: none;
    }

    .togglebutton {
        display: none;
    }

    .sheduletable {
        display: block;
    }
}

@media only screen and (min-width: 600px) {
    .calederview {
        display: block;
    }

    .sheduletable {
        display: none;
    }
}

@media only screen and (min-width: 1300px) {
    .table-responsive {
        height: 500px;
    }
}

.btndivdelete {
    float: left;
    /*padding: 0px 2px 0px 14px;*/
    /*display: none;  */
}

.toolbar {
    float: right;
    width: 57%;
}

.toolbar td {
    padding-left: 4px;
}

#unassigntbl_filter input {
    width: 150px !important;
}

tfoot {
    display: table-header-group;
}

td.fc-day.fc-past {
    background-color: #EEEEEE;
}

.label-row {
    margin-bottom: 6px;
}

.noSpacingInput {
    display: none;
}

.dtatableInput::placeholder {
    font-size: 10px;
    font-weight: 400;
}
</style>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/responsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/bootstrap-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/github.min.css">
<!-- Primary modal -->
<div id="modal_reschedule_reason_bulk" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-light">Reschedule Reasons</h6>
                <button type="button" class="close text-light modal-skip-dismiss close-modal-reschedule-reason">&times;</button>
            </div>
            <div class="modal-body ">
                <div class="form-group ">
                    <select class="form-control" name="reschedule_reason_id_bulk" id="reschedule_reason_id_bulk" >
                        <option value="">Select Reschedule Reason</option>

                        <?php
                        if (!empty($reschedule_reasons)) {
                            foreach ($reschedule_reasons as $reschedule_reason) {
                                echo '<option value="'.$reschedule_reason->reschedule_id.'" >'.$reschedule_reason->reschedule_name.'</option>';
                            }
                        }
                        ?>
                        <option value="-1">Other</option>
                    </select>
                </div>
                <div class="form-group" id="reschedule_reason_other_bulk" hidden>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="reason_other">Add more details</label>
                            <input type="text" class="form-control" name="reason_other_bulk" id="reason_other_bulk">

                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="reason_other">Hold Service Until</label>
                            <input type="date"
                                   id="hold_until_date_bulk"
                                   name="hold_until_date_bulk"
                                   value=""
                                   class="form-control pickadate note-filter"
                                   placeholder="YYYY-MM-DD"
                                   >
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <label>Send Rescheduled Email</label>
                    <input type="checkbox" name="send_email_bulk" id="send_email_bulk" >
                </div>
            </div>
            <div class="modal-footer">
                <button id="button-reschedule-reason-bulk" onclick="handleModalRescheduleBulk()" type="button" class="btn btn-primary modal-reschedule-dismiss">Save</button>
                <button type="button" class="btn btn-secondary modal-reschedule-dismiss close-modal-reschedule-reason" >Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Primary modal -->
<div id="modal_reschedule_reason" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-light">Reschedule Reasons</h6>
                <button type="button" class="close text-light modal-skip-dismiss close-modal-reschedule-reason">&times;</button>
            </div>
            <div class="modal-body ">
                <div class="form-group ">
                    <select class="form-control" name="reschedule_reason_id" id="reschedule_reason_id" >
                        <option value="">Select Reschedule Reason</option>

                        <?php
                        if (!empty($reschedule_reasons)) {
                            foreach ($reschedule_reasons as $reschedule_reason) {
                                echo '<option value="'.$reschedule_reason->reschedule_id.'" >'.$reschedule_reason->reschedule_name.'</option>';
                            }
                        }
                        ?>
                        <option value="-1">Other</option>
                    </select>
                </div>
                <div class="form-group" id="reschedule_reason_other_handle" hidden>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="reason_other">Add more details</label>
                            <input type="text" class="form-control" name="reason_other_handle" id="reason_other_handle">

                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="reason_other">Hold Service Until</label>
                            <input type="date"
                                   id="hold_until_date_handle"
                                   name="hold_until_date_handle"
                                   value=""
                                   class="form-control pickadate note-filter"
                                   placeholder="YYYY-MM-DD"
                                   >
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <label>Send Rescheduled Email</label>
                    <input type="checkbox" name="send_email" id="send_email_handle" >
                </div>
            </div>
            <div class="modal-footer">
                <button id="button-reschedule-reason" onclick="handleModalReschedule()" type="button" class="btn btn-primary modal-reschedule-dismiss">Save</button>
                <button type="button" class="btn btn-secondary modal-reschedule-dismiss close-modal-reschedule-reason" >Close</button>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="">
        <div class="mymessage"></div>
        <b><?php if ($this->session->flashdata()): echo $this->session->flashdata('message');
         endif
         ?></b>
        <div id="loading">
            <img id="loading-image" src="<?= base_url() ?>assets/loader.gif" /> <!-- Loading Image -->
        </div>
        <div class="panel-heading" style="padding-left: 0px;">
            <h5 class="panel-title">
                <div class="form-group">
                    <a href="<?= base_url('admin')?>" id="save" class="btn btn-success"><i class=" icon-arrow-left7">
                        </i> Back to Dashboard</a>
                </div>
            </h5>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-flat">
                    <div
                        style="background: #fafafa;padding-top: 10px;padding-bottom: 20px;color: #333;padding-left: 12px;padding-right:20px;">
                        <span class="text-semibold" style="font-size:15px;">Scheduled Services</span>
                        <div style="float: right;">
                            <label class="togglebutton">
                                Table view&nbsp;<input name="changeview" type="checkbox" class="switchery-primary"
                                    checked="">
                                Calendar view
                            </label>
                        </div>
                    </div>
                    <div class="panel-body" style="padding: 20px 0px;">
                        <div class="calederview">
                            <div class="fullcalendar-basic"></div>
                        </div>
                        <div class="sheduletable">
                            <div class="table-responsive table-spraye">
                                <table class="table" id="assigntbl">
                                    <thead>
                                        <tr>
                                            <!-- <th>S. NO</th> -->
                                            <th><input type="checkbox" id="select_all-delete"
                                                    <?php if (empty($assign_data)) { echo 'disabled'; }  ?> /></th>
                                            <th>Technician Name</th>
                                            <th>Service Name</th>
                                            <th>Assign Date</th>
                                            <th>Customer Name</th>
                                            <th>Property Name</th>
                                            <th>Address</th>
                                            <th>Service Area</th>
                                            <th>Program</th>
                                            <th>Status</th>
                                            <th>Map</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if (!empty($assign_data)) {
                                                         
                                    foreach ($assign_data as $value) { 
                                    ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="myCheckBoxDelete"
                                                    technician_job_assign_ids="<?= $value->technician_job_assign_id ?>"
                                                    name="selectcheckbox"
                                                    data-invoice="<?php if(isset($value->invoice_id)){echo $value->invoice_id;}?>">
                                            </td>
                                            <td><?= $value->user_first_name.' '.$value->user_last_name; ?></td>
                                            <td><?=$value->job_name; ?></td>
                                            <td><?php echo date('m-d-Y', strtotime($value->job_assign_date)); ?></td>
                                            <td><a href="<?=base_url("admin/editCustomer/").$value->customer_id ?>"
                                                    style="color:#3379b7;"><?=$value->first_name.' '.$value->last_name ?></a>
                                            </td>
                                            <td><?= $value->property_title ?></td>
                                            <td><?= $value->property_address ?></td>
                                            <td><?php if(isset($value->category_area_name)){
                                                echo $value->category_area_name;
                                            } else {
                                                echo 'None';
                                            } ?></td>
                                            <td><?=$value->program_name ?></td>
                                            <td>
                                                <?php 
                                          switch ($value->is_job_mode) {
                                            case 0:
                                            echo 'Pending';
                                              break;
                                            
                                            case 1:
                                              echo "Complete";
                                              break;
                                            default:
                                              break;
                                          
                                          }
                                          
                                          ?>
                                            </td>
                                            <td><a href="<?= base_url('admin/technicianMapView/').$value->technician_id.'/'.$value->job_assign_date  ?>"
                                                    target="_blank"><button type="button" class="btn btn-success">Map
                                                        View</button></a></td>
                                            <td>
                                                <ul style="list-style-type: none; padding-left: 0px;">

                                                    <li style="display: inline; padding-right:10px;">
                                                        <a data-toggle="modal" data-target="#modal_edit_assign_job"
                                                            onclick="editAssignJob(<?= $value->technician_job_assign_id ?>)"><i
                                                                class="icon-pencil   position-center"
                                                                style="color: #9a9797;"></i></a>
                                                    </li>

                                                    <li style="display: inline; padding-right: 10px;">
                                                        <?php if(isset($value->invoice_id)){?>
                                                        <a href="<?php echo base_url('admin/Invoices/pdfInvoicescheduled/').$value->invoice_id; ?>"
                                                            title="invoice" target="_blank">
                                                            <?php }else{ ?>
                                                            <a href="<?=base_url("admin/invoices/pendingjobinvoicescheduled/").$value->technician_job_assign_id ?>"
                                                                title="invoice" target="_blank">
                                                                <?php }?>
                                                                <i class="icon-printer2  position-center"
                                                                    style="color: #9a9797;"></i></a>
                                                    </li>

                                                    <li style="display: inline; padding-right: 10px;">
                                                        <a href="<?=base_url("admin/ScheduledJobDetete/").$value->technician_job_assign_id ?>" title="Remove from schedule"
                                                            class="confirm_delete button-next"><i
                                                                class="icon-trash   position-center"
                                                                style="color: #9a9797;"></i></a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php      
                                    }
                                     }
                                    
                                     ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Primary modal -->

<!-- /primary modal -->
<!--begin edit assign job  -->
<div id="modal_edit_assign_job" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close close-modal-edit-assign-job" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Assign Service to Technician</h6>
            </div>
            <form action="<?= base_url('admin/editTecnicianJobAssign') ?>" name="tecnicianjobassignedit" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Select Technician</label>
                                <div class="multi-select-full">
                                    <select class="form-control" name="technician_id" id="technician_id_edit">
                                        <option value="">Select Any Technician</option>
                                        <?php 
                                 if (!empty($tecnician_details)) {
                                  foreach ($tecnician_details as $value) {
                                  echo '<option value="'.$value->user_id.'" >'.$value->user_first_name.' '.$value->user_last_name.'</option>';  
                                  }
                                 }
                                 
                                 
                                 ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Select Date</label>
                                <input type="date" name="job_assign_date" class="form-control pickadate2"
                                    id="jobAssignDateEdit" placeholder="MM-DD-YYYY">
                                <input type="hidden" name="old_job_assign_date" id="jobAssignDateEditOld">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row label-row">
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview" class="primary-edit styled"
                                                checked="checked" value="1">
                                            Existing route
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview" class="primary-edit styled"
                                                value="2">
                                            Create a new route
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select name="route_select" class="form-control" id="route_select_edit">
                                        </select>
                                        <input type="text" name="route_input" class="form-control"
                                            style="display: none;" id="route_input_edit" placeholder="Route Name">
                                        <div class="route_edit_error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Service Notes</label>
                                <input type="text" name="job_assign_notes" placeholder="Job Assign Notes"
                                    class="form-control" id="assign_notes_edit">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label>Select Reschedule Reason</label>
                        <select class="form-control" name="reschedule_reason_id_edit" id="reschedule_reason_id_edit" >
                            <option value="">Select Reschedule Reason</option>

                            <?php
                            if (!empty($reschedule_reasons)) {
                                foreach ($reschedule_reasons as $reschedule_reason) {
                                    echo '<option value="'.$reschedule_reason->reschedule_id.'" >'.$reschedule_reason->reschedule_name.'</option>';
                                }
                            }
                            ?>
                            <option value="-1">Other</option>
                        </select>
                    </div>
                    <div class="form-group" id="reschedule_reason_other_id_edit" hidden>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="reason_other">Add more details</label>
                                <input type="text" class="form-control" name="reason_other_id_edit" id="reason_other_id_edit">

                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="reason_other">Hold Service Until</label>
                                <input type="date"
                                       id="hold_until_date_id_edit"
                                       name="hold_until_date_id_edit"
                                       value=""
                                       class="form-control pickadate note-filter"
                                       placeholder="YYYY-MM-DD"
                                       >
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Send Rescheduled Email</label>
                        <input type="checkbox" name="send_email_edit" id="send_email_edit" >
                    </div>
                    <div class="specificTimeDivisionEdit form-group">
                    </div>
                    <input type="hidden" name="technician_job_assign_id" id="technician_job_assign_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link close-modal-edit-assign-job" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="job_assign_bt">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end edit assign job  -->
<!--begin multiple edit assign job  -->
<div id="modal_multiple_edit_assign_job" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Assign Services to Technician</h6>
            </div>
            <form action="<?= base_url('admin/updateMultipleAssignJob') ?>" name="tecnicianjobassignmultipleedit"
                method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <label>Select Technician</label>
                                <div class="multi-select-full">
                                    <select class="form-control" name="technician_id" id="technician_id_edit_multiple">
                                        <option value="">Select Any Technician</option>
                                        <?php 
                                 if (!empty($tecnician_details)) {
                                  foreach ($tecnician_details as $value) {
                                  echo '<option value="'.$value->user_id.'" >'.$value->user_first_name.' '.$value->user_last_name.'</option>';  
                                  }
                                 }
                                 
                                 
                                 ?> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <label>Select Date</label>
                                <input type="date" name="job_assign_date" class="form-control pickadate"
                                    id="jobAssignDateEditMultiple" placeholder="MM-DD-YYYY">
                                <input type="hidden" name="old_job_assign_date" id="jobAssignDateEditMultipleOld">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-7 col-md-7">
                                <div class="row label-row">
                                    <div class="col-sm-12 col-md-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview"
                                                class="primary-edit-multiple styled" checked="checked" value="1">
                                            Existing route
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview"
                                                class="primary-edit-multiple styled" value="2">
                                            Create a new route
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <select name="route_select" class="form-control" id="route_select_edit_multiple"
                                            style="">
                                        </select>
                                        <input type="text" name="route_input" placeholder="Route Name"
                                            class="form-control" id="route_input_edit_multiple" style="display: none;">
                                        <div class="route_edit_multiple_error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 col-md-5">
                                <label>Service Notes</label>
                                <input type="text" name="job_assign_notes" placeholder="Service Assign Notes"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label>Select Reschedule Reason</label>
                        <select class="form-control" name="reschedule_reason_id_bulk_edit" id="reschedule_reason_id_bulk_edit" >
                            <option value="">Select Reschedule Reason</option>

                            <?php
                            if (!empty($reschedule_reasons)) {
                                foreach ($reschedule_reasons as $reschedule_reason) {
                                    echo '<option value="'.$reschedule_reason->reschedule_id.'" >'.$reschedule_reason->reschedule_name.'</option>';
                                }
                            }
                            ?>
                            <option value="-1">Other</option>
                        </select>
                    </div>
                    <div class="form-group" id="reschedule_reason_other_bulk_edit" hidden>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="reason_other">Add more details</label>
                                <input type="text" class="form-control" name="reason_other_bulk_edit" id="reason_other_bulk_edit">

                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="reason_other">Hold Service Until</label>
                                <input type="date"
                                       id="hold_until_date_bulk_edit"
                                       name="hold_until_date_bulk_edit"
                                       value=""
                                       class="form-control pickadate note-filter"
                                       placeholder="YYYY-MM-DD"
                                       >
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Send Rescheduled Email</label>
                        <input type="checkbox" name="send_email_bulk_edit" id="send_email_bulk_edit" >
                    </div>
                    <div class="specificTimeDivisionEditMultiple form-group">
                    </div>
                    <input type="hidden" name="multiple_technician_job_assign_id"
                        id="multiple_technician_job_assign_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="job_assign_bt">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end edit assign job  -->
<!-- Basic modal -->
<div id="modal_default" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" style="float: left;">Scheduled Service Details</h5>
                <ul style="list-style-type: none; padding-left: 10px;float:left" id="modalactionbtn">
                </ul>
            </div>
            <div class="modal-body" id="assigndetails">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /basic modal -->
<div id="modal_drop_event" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Assign Route</h6>
            </div>
            <form action="<?= base_url('admin/editTecnicianJobAssignCalender') ?>" name="dropEventForm" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row label-row">
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview" class="primary-edit-drop styled"
                                                checked="checked" value="1" id="primary-edit-drop-id">
                                            Existing route
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview" class="primary-edit-drop styled"
                                                value="2">
                                            Create a new route
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select name="route_select" class="form-control" id="route_select_drop_edit">
                                        </select>
                                        <input type="text" name="route_input" placeholder="Route Name"
                                            class="form-control" style="display: none;" id="route_input_drop_edit">
                                        <div class="route_drop_edit_error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label>Select Reschedule Reason</label>
                        <select class="form-control" name="reschedule_reason_id" id="reschedule_reason_id" >
                            <option value="">Select Reschedule Reason</option>

                            <?php
                            if (!empty($reschedule_reasons)) {
                                foreach ($reschedule_reasons as $reschedule_reason) {
                                    echo '<option value="'.$reschedule_reason->reschedule_id.'" >'.$reschedule_reason->reschedule_name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Send Rescheduled Email</label>
                        <input type="checkbox" name="send_email" id="send_email" >
                    </div>
                    <div class="specificTimeDivisionEditDrop form-group">
                    </div>
                    <input type="hidden" name="technician_job_assign_id" id="technician_job_assign_id_drop">
                    <input type="hidden" name="technician_id" id="technician_id_drop">
                    <input type="hidden" name="job_assign_date" id="job_assign_date_drop">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="job_assign_bt">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end edit assign job  -->

<script type="text/javascript">

var $input = $('.pickadate2').pickadate({

    min: new Date(),
    format: 'yyyy-mm-dd',
    formatSubmit: 'yyyy-mm-dd',
});
var picker = $input.pickadate('picker')
$(document).on("click", ".confirm_delete", function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $('#modal_reschedule_reason').modal('show');
    $('#modal_default').modal('hide');
    $('#modal_default').modal('hide');
    $('#button-reschedule-reason').attr('onclick', 'handleModalReschedule("'+url+'")');
});

$('#reschedule_reason_id_bulk').on('change', function (e) {
    if ($('#reschedule_reason_id_bulk').val() == "-1") {
        $('#reschedule_reason_other_bulk').show();
        $('#reason_other_bulk').attr("required","required");
    } else {
        $('#reschedule_reason_other_bulk').hide();
        $('#reason_other_bulk').attr("required", "");
    }
});
$('#reschedule_reason_id_bulk_edit').on('change', function (e) {
    if ($('#reschedule_reason_id_bulk_edit').val() == "-1") {
        $('#reschedule_reason_other_bulk_edit').show();
        $('#reason_other_bulk_edit').attr("required","required");
    } else {
        $('#reschedule_reason_other_bulk_edit').hide();
        $('#reason_other_bulk_edit').attr("required", "");
    }
});
$('#reschedule_reason_id_edit').on('change', function (e) {
    if ($('#reschedule_reason_id_edit').val() == "-1") {
        $('#reschedule_reason_other_id_edit').show();
        $('#reason_other_id_edit').attr("required","required");
    } else {
        $('#reschedule_reason_other_id_edit').hide();
        $('#reason_other_id_edit').attr("required", "");
    }
});
$('#reschedule_reason_id').on('change', function (e) {
    if ($('#reschedule_reason_id').val() == "-1") {
        $('#reschedule_reason_other_handle').show();
        $('#reason_other_handle').attr("required","required");
    } else {
        $('#reschedule_reason_other_handle').hide();
        $('#reason_other_handle').attr("required", "");
    }
});

function handleModalReschedule(url) {
    $('#modal_default').modal('hide');
    let reschedule_reason_id = $("#reschedule_reason_id").val();
    let otherReason = '';
    if (reschedule_reason_id == '-1') {
        otherReason = $("#reason_other_handle").val();
    }
    let holdUntilDate = $("#hold_until_date_handle").val();
    let send_reschedule_email = $("#send_email_handle").is(':checked');

    if (reschedule_reason_id || send_reschedule_email || holdUntilDate || send_reschedule_email)
    {
        url = url + '?';
    }
    if (reschedule_reason_id)
        url = url + '&reschedule_reason='+reschedule_reason_id;

    if (send_reschedule_email)
        url = url + '&send_email='+send_reschedule_email;

    if (holdUntilDate)
        url = url + '&holdUntilDate='+holdUntilDate;

    if (otherReason)
        url = url + '&otherReason='+otherReason;

    let checked = $('input[name=changeview]').prop("checked");
    let page = '';
    if (checked === true) {
        page = 'calendar';
    } else {
        page = 'schedule';
    }
    url = url + '&page='+page;

    window.location = url;
    // swal({
    //     title: 'Are you sure?',
    //     text: "You won't be able to recover this !",
    //     type: 'warning',
    //     showCancelButton: true,
    //     confirmButtonColor: '#009402',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Yes',
    //     cancelButtonText: 'No'
    // }).then((result) => {
    //
    //     if (result.value) {
    //         window.location = url;
    //     }
    // })
}
</script>

<script type="text/javascript">
$(function() {


    // Add events
    // ------------------------------

    checkModalSituation = 1;

    $.ajax({
        url: "<?= base_url('admin/scheduledJobsData') ?>",
        method: "GET",
        dataType: 'JSON',
        success: function(data) {

            var events = data;

            // Initialization
            // ------------------------------

            // Basic view
            $('.fullcalendar-basic').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                defaultDate: '<?= date("Y-m-d") ?>',
                editable: true,
                events: events,
                eventConstraint: {
                    start: moment().format('YYYY-MM-DD'),
                    end: '2100-01-01' // hard coded goodness unfortunately
                },
                eventClick: function(event) {
                    //alert(event.id);
                    $("#loading").css("display", "block");

                    $.ajax({
                        type: "GET",
                        url: "<?= base_url('admin/getOneAssignData/') ?>" +
                            event.id,
                        dataType: 'JSON',
                    }).done(function(data) {
                        // alert(data);
                        $("#loading").css("display", "none");

                        $('#modalactionbtn').html(data['btn']);
                        $('#assigndetails').html(data['html']);
                        $('#modal_default').modal('toggle');
                        // $.sweetModal({
                        //  title: 'Scheduled Job Details',
                        //  content: data
                        // });
                    });


                },

                eventDrop: function(event, delta, revertFunc) {
                    // $("#loading").css("display","block");
                    // console.log(event);

                    // console.log(event);
                    $('#modal_drop_event').modal('toggle');

                    $('#technician_job_assign_id_drop').val(event.id);
                    $('#technician_id_drop').val(event.technician_id);


                    if (event.is_time_check == 1) {
                        checked = 'checked';
                        display = 'block';
                        value = event.specific_time;

                    } else {
                        checked = '';
                        display = 'none';
                        value = '';
                    }


                    $('.specificTimeDivisionEditDrop').html(
                        '<div class="row"><div class="col-sm-12"><label>Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" ' +
                        checked +
                        ' class="form-control styled" name="specific_time_check" value="1" id="changespecifictimeeditdrop" ></label>  <div id="specific_time_input_edit_drop" style="display:' +
                        display +
                        '" >         <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" value="' +
                        value +
                        '" readonly name="specific_time" placeholder="Specific Time"  >           <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>'
                    );
                    reassignCheckboxAnTimePicker();

                    $('#job_assign_date_drop').val(event.start.format());
                    routeMange(event.technician_id, event.start.format(),
                        'route_select_drop_edit');

                    $('#modal_drop_event').on('hidden.bs.modal', function(e) {
                        if (checkModalSituation == 1) {
                            revertFunc();
                        } else if (checkModalSituation == 2) {
                            checkModalSituation = 1;
                        }
                        // alert(checkModalSituation);

                        $(this).off('hidden.bs.modal');
                    })


                }

            });


        }
    });


});
</script>
<script>
// Custom button
$('#assigntbl').DataTable({


    language: {
        search: '<span></span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {
            'first': 'First',
            'last': 'Last',
            'next': '&rarr;',
            'previous': '&larr;'
        }
    },

    // "lengthMenu": [[10, 25, 50, 100, 200, 500], [10, 25, 50, 100, 200, 500]], 

    dom: 'l<"btndivdelete">frtip',
    initComplete: function() {
        $("div.btndivdelete")
            .html(
                '<div class="btn-group"><button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultipleLoader()" disabled ><i class="icon-trash"></i> Remove from schedule</button><div class="alleditbtn"><a data-toggle="modal" data-target="#modal_multiple_edit_assign_job"><button type="submit"  class="btn btn-success" id="editallbutton"  disabled ><i class="icon-pencil"></i> Edit</button></a></div>  <div class="alleditbtn" ><button type="submit"  class="btn btn-success" id="allPrintPS"  disabled ><i class="icon-printer2"></i> Print w/ Pay Stub</button></div><div class="alleditbtn" ><button type="submit"  class="btn btn-success" id="allPrintBCD"  disabled ><i class="icon-printer2"></i> Print w/ Blank Compliance Data</button></div>   </div>'
            );
    }
});






$('input[name=changeview]').click(function() {

    if ($(this).prop("checked") == true) {

        $('.calederview').css('display', 'block');
        $('.sheduletable').css('display', 'none');
        // $('.btndivdelete').css('display','none');
        $('.fullcalendar-basic').find('.fc-month-button').click()

    } else if ($(this).prop("checked") == false) {
        $('.calederview').css('display', 'none');
        $('.sheduletable').css('display', 'block');
        // $('.btndivdelete').css('display','block');

    }

});




$('.primary-edit').click(function() {
    if ($(this).val() == 1) {

        $('#route_input_edit').css('display', 'none');
        $('#route_select_edit').css('display', 'block');

    } else if ($(this).val() == 2) {
        $('#route_input_edit').css('display', 'block');
        $('#route_select_edit').css('display', 'none');

    }

});

$(document).on("click", "#changespecifictimeedit", function(e) {
    if ($(this).prop("checked") == true) {
        $('#specific_time_input_edit').css('display', 'block');
    } else if ($(this).prop("checked") == false) {
        $('#specific_time_input_edit').css('display', 'none');
    }

});

$('.primary-edit-drop').click(function() {
    if ($(this).val() == 1) {
        $('#route_input_drop_edit').css('display', 'none');
        $('#route_select_drop_edit').css('display', 'block');
    } else if ($(this).val() == 2) {
        $('#route_input_drop_edit').css('display', 'block');
        $('#route_select_drop_edit').css('display', 'none');
    }

});


function rearraangedropModalForm(argument) {

    $("#primary-edit-drop-id").prop("checked", true);
    $('#route_input_drop_edit').css('display', 'none');
    $('#route_select_drop_edit').css('display', 'block');

}


$(document).on("click", "#changespecifictimeeditdrop", function(e) {

    if ($(this).prop("checked") == true) {
        $('#specific_time_input_edit_drop').css('display', 'block');
    } else if ($(this).prop("checked") == false) {
        $('#specific_time_input_edit_drop').css('display', 'none');
    }

});

$('.primary-edit-multiple').click(function() {
    if ($(this).val() == 1) {
        $('#route_input_edit_multiple').css('display', 'none');
        $('#route_select_edit_multiple').css('display', 'block');
    } else if ($(this).val() == 2) {
        $('#route_input_edit_multiple').css('display', 'block');
        $('#route_select_edit_multiple').css('display', 'none');
    }

});
$(document).on("click", "#changespecifictimeeditmultiple", function(e) {
    if ($(this).prop("checked") == true) {
        $('#specific_time_input_edit_multiple').css('display', 'block');
    } else if ($(this).prop("checked") == false) {
        $('#specific_time_input_edit_multiple').css('display', 'none');
    }

});



function editAssignJob($technicianJob) {

    picker.stop();

    $('#modal_default').modal('hide');

    $("#loading").css("display", "block");

    url = '<?= base_url('admin/getOneAssignJsonbData/') ?>' + $technicianJob
    $.getJSON(url, function(data) {

        $("#loading").css("display", "none");
        $('#technician_id_edit option[value="' + data['technician_id'] + '"]').prop('selected', true)
        $('#jobAssignDateEdit').val(data['job_assign_date']);
        $('#jobAssignDateEditOld').val(data['job_assign_date']);
        $('#jobAssignDateEditMultipleOld').val(data['job_assign_date']);

        picker.start();

        $('#assign_notes_edit').val(data['job_assign_notes']);
        $('#technician_job_assign_id').val(data['technician_job_assign_id']);
        routeMange(data['technician_id'], data['job_assign_date'], 'route_select_edit', data['route_id']);
        if (data['is_time_check'] == 1) {
            checked = 'checked';
            display = 'block';
            value = data['specific_time'];
        } else {
            checked = '';
            display = 'none';
            value = '';
        }
        $('.specificTimeDivisionEdit').html(
            '<div class="row"><div class="col-sm-6"><label> Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" ' +
            checked +
            '  class="form-control styled" name="specific_time_check" value="1" id="changespecifictimeedit" ></label><div id="specific_time_input_edit" style="display:' +
            display +
            '" >         <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" value="' +
            value +
            '" readonly name="specific_time" placeholder="Specific Time"  >           <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div>      </div></div></div>'
        );
        reassignCheckboxAnTimePicker();
    });

}
</script>
<!-- /////  for multiple delete  -->
<script type="text/javascript">
$("#select_all-delete").change(function() { //"select all" change 
    var status = this.checked; // "select all" checked status
    if (status) {
        $('#deletebutton').prop('disabled', false);
        $('#editallbutton').prop('disabled', false);
        $('#allPrintPS').prop('disabled', false);
        $('#allPrintBCD').prop('disabled', false);



    } else {
        $('#deletebutton').prop('disabled', true);
        $('#editallbutton').prop('disabled', true);
        $('#allPrintPS').prop('disabled', true);
        $('#allPrintBCD').prop('disabled', true);

    }
    $('.myCheckBoxDelete').each(function() { //iterate all listed checkbox items
        this.checked = status; //change ".checkbox" checked status

    });
});

$('.myCheckBoxDelete').change(function() { //".checkbox" change 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if (this.checked == false) { //if this item is unchecked
        $("#select_all-delete")[0].checked = false; //change "select all" checked status to false


    }

    //check "select all" if all checkbox items are checked
    if ($('.myCheckBoxDelete:checked').length == $('.myCheckBoxDelete').length) {
        $("#select_all-delete")[0].checked = true; //change "select all" checked status to true
    }
});



$(document).on("change", "table .myCheckBoxDelete", function(e) {

    var checkBoxes2 = $('table .myCheckBoxDelete');
    // checkBoxes2.change(function () {

    // alert();
    $('#deletebutton').prop('disabled', checkBoxes2.filter(':checked').length < 1);
    $('#editallbutton').prop('disabled', checkBoxes2.filter(':checked').length < 1);
    $('#allPrintPS').prop('disabled', checkBoxes2.filter(':checked').length < 1);
    $('#allPrintBCD').prop('disabled', checkBoxes2.filter(':checked').length < 1);

});

// checkBoxes2.change();  


$(".close-modal-reschedule-reason").click(function () {
    $("#reschedule_reason_id").val($("#reschedule_reason_id option:first").val());
    $("#send_email").prop("checked", false);
    $("#modal_reschedule_reason").modal('hide');
})
$(".close-modal-edit-assign-job").click(function () {
    $("#reschedule_reason_id").val($("#reschedule_reason_id option:first").val());
    $("#reschedule_reason_id_edit").val($("#reschedule_reason_id_edit option:first").val());
    $("#send_email").prop("checked", false);
    $("#send_email_edit").prop("checked", false);
})

function deletemultipleLoader() {
    $('#modal_reschedule_reason_bulk').modal('show');
    $('#modal_default').modal('hide');
    $('#modal_default').modal('hide');
}
function handleModalRescheduleBulk() {
    $('#modal_default').modal('hide');
    let reschedule_reason_id = $("#reschedule_reason_id_bulk").val();
    let send_reschedule_email = $("#send_email_bulk").is(":checked");
    let otherReason = '';
    if (reschedule_reason_id == '-1') {
        otherReason = $("#reason_other_bulk").val();
    }
    let holdUntilDate = $("#hold_until_date_bulk").val();

    deletemultiple(reschedule_reason_id, send_reschedule_email, otherReason, holdUntilDate);
}

function deletemultiple(reschedule_reason_id, send_reschedule_email, otherReason, holdUntilDate) {
    debugger
    debugger
    var selectcheckbox = [];
    $("input:checkbox[name=selectcheckbox]:checked").each(function() {
        selectcheckbox.push($(this).attr('technician_job_assign_ids'));
    });

    // alert(selectcheckbox);


    $.ajax({
        type: "POST",
        url: "<?= base_url('admin/deletemultipleJobAssign') ?>",
        data: {
            job_assign_ids: selectcheckbox,
            reschedule_reason_id: reschedule_reason_id,
            send_reschedule_email: send_reschedule_email,
            otherReason: otherReason,
            holdUntilDate: holdUntilDate
        }
    }).done(function(data) {

        // alert(data);

        if (data == 1) {
            swal(
                'Scheduled Services !',
                'Service rescheduled successfully ',
                'success'
            ).then(function() {
                let checked = $('input[name=changeview]').prop("checked");
                let page = '';
                if (checked === true) {
                    page = 'calendar';
                } else {
                    page = 'schedule';
                }
                var url = window.location.href;

                var newParameter = 'page='+page;
                var newUrl = url + (url.indexOf('?') === -1 ? '?' : '&') + newParameter;

                window.location.href = newUrl;
                // location.reload();
            });


        } else {
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'Something went wrong!'
            })
        }


    });
    // swal({
    //     title: 'Are you sure?',
    //     text: "You won't be able to recover this !",
    //     type: 'warning',
    //     showCancelButton: true,
    //     confirmButtonColor: '#009402',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Yes',
    //     cancelButtonText: 'No'
    // }).then((result) => {
    //
    //     if (result.value) {
    //     }
    // })

}

$(document).ready(function (){

    var url = new URL(window.location.href);

    var params = new URLSearchParams(url.search);
    $('input[name=changeview]').prop("checked", "");

    if (params.has('page')) {
        // Get the value of the parameter
        var value = params.get('page');
        if (value === 'schedule') {
            $('input[name=changeview]').click();
            $('input[name=changeview]').click();
        }
    }
})


$('#allMessage').click(function() { //iterate all listed checkbox items    

    var numberOfChecked = $('input:checkbox[name=group_id]:checked').length;
    if (numberOfChecked == 1) {
        $('.specificTimeDivision').html(
            '<div class="row"><div class="col-sm-6"><label>Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-control styled" name="specific_time_check" value="1" id="changespecifictime" ></label><div id="specific_time_input" style="display:none;" ><div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" readonly name="specific_time" placeholder="Specific Time"  >        <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>'
        );

        reassignCheckboxAnTimePicker();


    } else {
        $('.specificTimeDivision').html('');

    }

});



$('#editallbutton').click(function() { //iterate all listed checkbox items




    var numberOfChecked = $('.myCheckBoxDelete:checked').length;
    //alert(numberOfChecked);
    if (numberOfChecked == 1) {
        $('.specificTimeDivisionEditMultiple').html(
            '<div class="row"><div class="col-sm-6"><label>       Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-control styled" name="specific_time_check" value="1" id="changespecifictimeeditmultiple" >          </label> <div id="specific_time_input_edit_multiple" style="display:none;" > <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" readonly name="specific_time" placeholder="Specific Time"  >           <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>'
        );

        reassignCheckboxAnTimePicker();


    } else {
        $('.specificTimeDivisionEditMultiple').html('');

    }

});





// for assign job 

$("#technician_id").change(function() {
    technician_id = $(this).val();
    jobAssignDate = $('#jobAssignDate').val();
    route_select_id = 'route_select';
    routeMange(technician_id, jobAssignDate, route_select_id);
});

$("#jobAssignDate").change(function() {

    $("#technician_id").trigger("change");
});




$("#technician_id_edit").change(function() {
    technician_id = $(this).val();
    jobAssignDate = $('#jobAssignDateEdit').val();
    route_select_id = 'route_select_edit';
    routeMange(technician_id, jobAssignDate, route_select_id);
});

$("#jobAssignDateEdit").change(function() {

    $("#technician_id_edit").trigger("change");
});

//  for multiple edit assign job  


$("#technician_id_edit_multiple").change(function() {
    technician_id = $(this).val();
    jobAssignDate = $('#jobAssignDateEditMultiple').val();
    route_select_id = 'route_select_edit_multiple';
    routeMange(technician_id, jobAssignDate, route_select_id);
});

$("#jobAssignDateEditMultiple").change(function() {

    $("#technician_id_edit_multiple").trigger("change");
});




function routeMange(technician_id, jobAssignDate, route_select_id, selected_id = '') {

    $('#' + route_select_id).html('');
    if (technician_id != '' && jobAssignDate != '') {

        $.ajax({
            type: "POST",
            url: "<?= base_url('admin/getTexhnicianRoute') ?>",
            data: {
                technician_id: technician_id,
                job_assign_date: jobAssignDate
            },
            dataType: "json",
        }).done(function(data) {

            if (data.length === 0) {
                $('#' + route_select_id).append('<option value="">No route found</option>');
            } else {
                $.each(data, function(index, value) {

                    if (value.route_id == selected_id) {
                        selected = 'selected';
                    } else {
                        selected = '';
                    }


                    $('#' + route_select_id).append('<option value="' + value.route_id + '" ' +
                        selected + ' >' + value.route_name + '</option>');
                });
            }
        });
    }

}
</script>

<script type="text/javascript">
$(document).on("click", "#allPrintPS", function() {

    var selectcheckbox = $("input:checkbox[name=selectcheckbox]:checked").map(function() {

        return $(this).attr('technician_job_assign_ids');

    }).get(); // <----


    var href = "<?= base_url('admin/invoices/pendingJobInvoicescheduled/') ?>" + selectcheckbox;
    var win = window.open(href, '_blank');
    win.focus();

});
</script>

<script type="text/javascript">
$(document).on("click", "#allPrintBCD", function() {

    var selectcheckbox = $("input:checkbox[name=selectcheckbox]:checked").map(function() {

        return $(this).attr('technician_job_assign_ids');

    }).get(); // <----


    var href = "<?= base_url('admin/invoices/pendingJobInvoiceBlankData/') ?>" + selectcheckbox;
    var win = window.open(href, '_blank');
    win.focus();

});
</script>

<!-- /////  -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
$('.clockpicker').clockpicker();
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/highlight.min.js"></script>
<script type="text/javascript">
hljs.configure({
    tabReplace: '    '
});
hljs.initHighlightingOnLoad();
</script>