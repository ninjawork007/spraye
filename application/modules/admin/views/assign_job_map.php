<style>
    .togglebutton {
        font-size: 13px;
    }

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

    table {
        border-collapse: inherit;
    }
    #unassigntbl_length {
            /* show: */
            /* float: left !important; */
            display: none;
    }

    #loading {
        width: 50%;
        height: 50%;
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
        /* position: absolute; */
        /* left: 50%; */
        padding-left: 10px;
        /* top: 50%; */
        width: 35px;
        display: inline-block;
        
        /* z-index: 100; */
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

    @media only screen and (max-width: 1240px) {
        #multiple-delete-id {
            margin: 0;
        }

        .unassigned-services-element {
            /* float: unset !important; */
            margin-top: 20px;
        }

        .toolbar {
            width: 90% !important;
            /* toolbar (filters) */
            padding-bottom: 20px;
            /* margin-left: -100px; */
            display: block;
            float: left !important;
        }

        /* KT make filters stack */
        /* .toolbar td {
            display: block;
        } */

        #unassigntbl_length {
            /* show: */
            /* float: left; */
            display: block;
        }

        #unassigntbl_filter>label {
            /* search box */
            margin-bottom: 20px;
            margin-left: -10px;
        }
    }

    @media only screen and (max-width: 769px) {
        .toolbar {
            float: left !important;
        }

        .toolbar td {
            display: block;
        }

        #unassigntbl_length {
            /* show: */
            float: left !important;
        }

        #unassigntbl_filter>label {
            /* search box */
            float: left !important;
        }

        .form-group a {
            display: block;
            width: 200px;
            margin-bottom: 6px;
        }

        .form-group button {
            display: block;
            width: 200px;
        }

        #filter-criteria-id {
            float: left;
        }
    }

    .btndivdelete {
        float: left;
        /*padding: 0px 2px 0px 14px;*/
        /*display: none;  */
    }

    .toolbar {
        /* float: right; */
        /* width: 88%; */
        padding-bottom: 10px;
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

    .rescheduled_row {
        background: #b8d1f3 !important;
    }

    tr.row_in_hold td, tr.row_in_hold td a {	
        color: #8080804f !important;	
    }

    #resetMap {
        display: none;
    }

    #update-map-div {
        min-height:65px;
    }

    /* #update-map-note { 
        vertical-align:middle;
    } */

    #notify_filter {
        vertical-align: top;
    }
    
    #service_due_filter {
        vertical-align: top;
    }

    /* #multiple-delete-id {
        float: right;
    } */

    #filter-criteria-id {
        margin-left: 20px;
    }

    #unassigntbl {
        margin-top:10px;
    }
    #unassigntbl_processing {
        margin-top: 40px;
        background-color: white;
        background: white;
        height: 100%;
        text-align: center;
        vertical-align: top;
        position: absolute;
        height: 100%;
        top:130px;
    }
    .asap_row {
        background: #FBE9E7 !important;
        border: 1px solid #FF5722;
    }
</style>
<script>
    var global_r = '';
    $priority_filter_input = '';
    var initial_load = true;
    var set_initial_center = true;
    var remove_initial_map_button = true;
    var inital_table_hide = true;
</script>


<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/responsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/bootstrap-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/github.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<div class="content">
    <div class="">
        <div class="mymessage"></div>
        <b><?php if ($this->session->flashdata()) : echo $this->session->flashdata('message');
            endif
            ?></b>
        <div id="loading">
            <img id="loading-image" src="<?= base_url() ?>assets/loader.gif" /> <!-- Loading Image -->
        </div>

        <div class="panel-heading" style="padding-left: 0px;">
            <h5 class="panel-title">
                <div class="form-group">
                    <a href="<?= base_url('admin') ?>" id="save" class="btn btn-success"><i class=" icon-arrow-left7"> </i> Back to Dashboard</a>

                    <a href="<?= base_url('admin/manageJobs') ?>" id="save" class="btn btn-primary"></i>Manage Scheduled Services</a>

                    

                    <!-- <button disabled="disabled" id="multiple-delete-id" class="btn btn-danger unassigned-services-element">Delete Services</button>
                    <button disabled="disabled" id="filter-criteria-id" class="btn btn-danger ">Filters</button> -->

                    <!-- switch to table view -->
                    <!-- <a href="<?= base_url('admin/assignJobs') ?>" id="change-view-type" class="btn btn-primary" ​​​​​>Click for Table View</a> -->


                    <div class="pull-right">
                                 <label class="togglebutton">
                                 Map View&nbsp;<input id="change-view-type" type="checkbox" onclick="window.location='<?= base_url('admin/assignJobs') ?>'" class="switchery-primary">
                                 Table View
                                 </label>
                    </div>
                    



                    <button id="multiple-restore-id" disabled="disabled" class=" hidden btn btn-primary archived-services-element">Restore Services</button>
                    <!-- <div class="pull-right"> 
                                 <label class="togglebutton">
                                 Archived Services&nbsp;<input name="changeview" type="checkbox" class="switchery-primary"  checked="">
                                 Unassigned Services
                                 </label>
                     </div>                              -->
                     
                </div>
            </h5>
        </div>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">
                    <div class="form-group">

                        <div class="row">

                            <div class="col-md-6">

                            </div>

                            <div class="col-md-6 toggle-btn">
                                <div style="float: right;">
                                    <label hidden>
                                        Map view&nbsp;<input id= "changeview" name="changeview" type="checkbox" class="switchery-primary">
                                        Table view
                                    </label>
                                    <!-- <a href="<?php //echo base_url('admin/assignJobs/') ?>">Click for Table View</a> -->

                                    


                                </div>
                                <div>
                            
                            </div>
                            </div>

                        </div>


                    </div>

                </h5>
            </div>
        </div>

        <!-- <div class="panel-body">
         <h5 class="panel-title">Users Details</h5>
         </div>-->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="panel panel-flat">
                        <div style="background: #fafafa;padding-top: 10px;padding-bottom: 20px;color: #333;padding-left: 12px;">
                            <span class="unassigned-services-element text-semibold" style="font-size:20px;margin-right:18px;display:flex;align-items: center;justify-content: center;">Unassigned Services</span>
                            <span class="archived-services-element text-semibold hidden" style="font-size:15px;">Archived Services</span>
                            <span class="unassigned-services-element" style="margin-right:18px;display:flex;align-items: center;justify-content: center;">Highlighted jobs indicate this job has been skipped and needs to be rescheduled</span>
                            <div class="row "  style="margin-top: 10px;align-items:center;">

                                <div class="unassigned-services-element " style="margin-right:18px;display:flex;align-items:center;justify-content: center;">
                                <!-- <div class="unassigned-services-element " style="display:flex;"> -->
                                    <div data-toggle="tooltip" data-placement="top" title="Property Sq Feet combines service applications sqft that are applied on a single property, for example if 2 service applications are applied to one property the sum of the sqft will only be equivalent to one of those applications.  Application Sq Feet is the sum of all service applications sqft, no matter if being applied to multiple properties or on a single property.">
                                        
                                            <div class="row">
                                                <div class="col-md-3" style="text-align:right;">
                                                    <label for="appllicationSqFt" >Property Sq Feet</label>
                                                </div>
                                                <div class="col-md-3"style="text-align:right;">
                                                    <input placeholder="" id="applicationSqFt" type="text" size="15">
                                                </div>
                                                <div class="col-md-3"style="text-align:right;">
                                                    <label for="totalSqFt" >Application Sq Feet</label>
                                                </div>
                                                <div class="col-md-3"style="text-align:right;">
                                                    <input placeholder="" id="totalSqFt" type="text"  size="15">
                                                </div>
                                            </div>
                                        
                                    </div>
                                    <button type="submit" disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage" style="margin-left:15px;">
                                        Assign Technician</button>
                                </div>
                            </div>
                            <div class="unassigned-services-element" style="float: right;margin-right: 18px;">
                                <button type="button" class="btn btn-success" id="resetMap">
                                    Update Map</button>
                            </div>
                            
                        </div>

                        <!-- <div class="container">
                        <div class="row"> -->

                            

                            

                        <!-- </div>
                        </div> -->

                        <div class="panel-body" style="padding: 20px 0px;">

                            <!-- <div  class="table-responsive table-spraye dash-tbl" style="height: unset;">
                        <table  class="table" id="unassigntbl" >
                           <thead>
                              <tr>
                                 <th><input type="checkbox" id="select_all" /></th>
                                 <th>Priority</th>
                                 <th>Service Name</th>
                                 <th>Customer Name</th>
                                 <th>Property Name</th>
								 <th>Square Feet</th>
                                 <th>Last Service Date</th>
								 <th>Last Program Service Date</th>
                                 <th>Address</th>
                                 <th>Property Type</th>                                 
                                 <th>Service Area</th>
                                 <th>Program</th>
                                 <th>Note</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                 <td></td>
                                 <td id="priority_filter">PRIORITY</td>
                                 <td id="service_name_filter">SERVICE NAME</td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
								  <td></td>
								  <td></td>
                                 <td id="property_type_filter">PROPERTY TYPE</td>
                                 <td id="service_area_filter">SERVICE AREA</td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                              </tr>
                           </tfoot> 

                        </table>
                     </div>  -->

                            <div class="row">
                                <div class="col-md-4">
                                <div  id="update-map-div-two" style="padding-left: 4px; margin-bottom: 10px;">
                                    <!-- <span class="update-map-note text-semibold" style="color:red;background-color:yellow;">Update Map View</span> -->
                                
                                
                                </div>

                                <div>
                                    
                                </div>
                                    
                                </div>
                    
                                
                            </div>

                            <div class="row">
                                

                                <div class="col-md-4" id="mapdiv">
                                    <div id="dvMap" style="height:57vh;">map div area</div>
                                    <div style="display:flex;align-items:center;justify-content:center;padding-top:10px;">
                                        <span class="chtn" ><a href="" data-toggle="modal" data-target="#help_message" >This map feature is currently in beta. Please send us your feedback here</a></span>
                                    </div>                                
                                </div>

                                

                                <div class="col-md-8" id="tablediv">
									<p id="filter_message" style="width: 100%; background-color: #F44336; padding:5px;  text-align: center; display: block; color:white">Limited locations loaded.  Please enter Filter Criteria</p>
                                    
                                    <div class="table-responsive table-spraye dash-tbl" style="height: unset;">
                                        <table class="table" id="unassigntbl">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select_all" /></th>
                                                    <th>Priority</th>
                                                    <th>Service Name</th>
                                                    <th>Notify Customer</th>
                                                    <th>Customer Name</th>
                                                    <th>Property Name</th>
                                                    <th>Square Feet</th>
                                                    <th>Last Service Date</th>
                                                    <th>Last Program Service Date</th>
                                                    <th>Last Program Service Type Date</th>
                                                    <th>Service Due</th>
                                                    <th>Address</th>
                                                    <th>Property Type</th>
                                                    <th>Property Info</th>
                                                    <th>Service Area</th>
                                                    <th>Program</th>
                                                    <th>Rescheduled Reason</th>
                                                    <th>Tags</th>
                                                    <th>ASAP Reason</th>
                                                    <th>Available Days</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <!-- <td></td>
                                                    <td id="priority_filter">PRIORITY</td>
                                                    <td id="service_name_filter">SERVICE NAME</td>
                                                    <td id="notify_filter">NOTIFY CUSTOMER</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td id="service_due_filter">SERVICE DUE</td>
                                                    <td></td>
                                                    <td id="property_type_filter">PROPERTY TYPE</td>
                                                    <td></td>
                                                    <td id="service_area_filter">SERVICE AREA</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td id="tag_filter">TAG</td>
                                                    <td></td>
                                                    <td></td> -->
                                                </tr>
                                                <!-- <tr>
                                       
                                    </tr> -->
                                            </tfoot>

                                        </table>
                                        
                                    </div>
                                    
                                    <input type="hidden" name="checkbox_realvalues_array" id="checkbox_realvalues_array" value"">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Primary modal -->
<div id="modal_theme_primary" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Assign Service to Technician</h6>
            </div>
            <form action="<?= base_url('admin/tecnicianJobAssign') ?>" name="tecnicianjobassign" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <label>Select Technician</label>
                                <div class="multi-select-full">
                                    <select class="form-control" name="technician_id" id="technician_id">
                                        <option value="">Select Any Technician</option>

                                        <?php
                                        if (!empty($tecnician_details)) {
                                            foreach ($tecnician_details as $value) {
                                                echo '<option value="' . $value->user_id . '" >' . $value->user_first_name . ' ' . $value->user_last_name . '</option>';
                                            }
                                        }


                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6" id="assigModalDate">
                                <label>Select Date</label>
                                <input type="date" name="job_assign_date" class="form-control  pickadate" id="jobAssignDate" placeholder="YYYYY-MM-DD">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-7 col-md-7">
                                <div class="row label-row">
                                    <div class="col-sm-12 col-md-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview" class="primary-assign styled" checked="checked" value="1">
                                            Existing route
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="changerouteview" class="primary-assign styled" value="2">
                                            Create a new route
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <select name="route_select" class="form-control" id="route_select">
                                        </select>
                                        <input type="text" name="route_input" class="form-control" placeholder="Route Name" id="route_input" style="display: none;">
                                        <div class="route_error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 col-md-5">
                                <label>Service Notes</label>
                                <input type="text" name="job_assign_notes" placeholder="Service Assign Notes" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="specificTimeDivision form-group">
                    </div>
                    <input type="hidden" name="group_id" id="group_id">
                    <input type="hidden" name="group_id_new" id="group_id_new">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="job_assign_bt">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /primary modal -->

<!---  Add Filter Criteria template --->
<div id="modal_add_filters" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Filter Criteria</h6>
                </div>

              

                <div class="modal-body ">
                    <div class="form-group ">

                    <div class="container-mt-2">
                            <!-- <div class="row"> -->
                                <div class="col-md-6 ">
                                        <div class="priority-filter">                   
                                            <label>Priority</label>
                                            <input type="text" id = "pfilter" name = "pfilter" class="form-control dtatableInput" placeholder="PRIORITY">
                                        </div>

                                        <div class="property-type-filter">                   
                                            <label>Property Type</label>
                                            <input type="text" id = "ptfilter" name = "ptfilter" class="form-control dtatableInput" placeholder="PROPERTY TYPE">
                                        </div>

                                        <div class="service-name-filter">                   
                                            <label>Service Name</label>
                                            <input type="text" id = "snfilter" name = "snfilter" class="form-control dtatableInput" placeholder="SERVICE NAME">
                                        </div>

                                       <!-- <div class="service-area-filter">
                                            <label>Service Area</label>
                                            <input type="text" id = "safilter" name = "safilter" class="form-control dtatableInput" placeholder="SERVICE AREA">
                                        </div>-->
                                    <div class="multi-select-full" id="service-area-filter_parent" >
                                        <label for="service-area-filter">Service Area
                                        </label>
                                        <select class="multiselect-select-all-filtering form-control" name="service-area-filter[]" id="service-area-filter" multiple="multiple">

                                            <?php foreach ($service_area_list as $value): ?>
                                                <option value="<?= $value->category_area_name ?>"> <?= $value->category_area_name ?> </option>
                                            <?php endforeach ?>

                                        </select>

                                    </div>
                                    <div class="service-name-filter">
                                        <label>Show Only ASAP?</label>
                                        <select class="form-control" name="asap_reason" id="asap-reason">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                        
                    

                    
                                    <div class="col-md-6">
                                        <div class="notify-customer-filter">    
                                            <label>Notify</label>
                                            <select class="form-control dtatableInput" id = "ncfilter" name = "ncfilter" placeholder="NOTIFY CUSTOMER" ><option value="0" class="default-option">-- NOTIFY</option><option value="1">CALL AHEAD</option><option value="2">TEXT ETA</option><option value="3">PRE-NOTIFIED</option></select>
                                        </div>

                                        <div class="tags-filter">    
                                            <label>Tags</label>
                                                <?php echo $filter_tags; ?>
                                        </div>
                                        <div class="multi-select-full" id="service_ids_filter_parent" >
                                            <label for="service_ids_filter">Due
                                                <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="Choose status below you would like to filter."><i class=" icon-info22 tooltip-icon"></i></span>
                                            </label>
                                            <div class="service-due-filter">
                                                <select name="services_statuses_filter[]" id="sdfilter" multiple style="width: 100%;" class="multiselect-select-all-filtering form-control><option value="0" class="default-option" style="width: 100%;">-- DUE</option><option value="1">Due</option><option value="2">Overdue</option><option value="3">Not Due</option></select>
    <!--                                            <select class="form-control dtatableInput" id = "sdfilter" name = "sdfilter" placeholder="SERVICE DUE" ><option value="0" class="default-option">-- DUE</option><option value="1">Due</option><option    value="2">Overdue</option><option value="3">Not Due</option></select>-->
                                            </div>
                                        </div>

                                        <div class="multi-select-full" id="service_ids_filter_parent" >
                                            <label for="service_ids_filter">Filter Services
                                                <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="Choose the service(s) below you would like to schedule."><i class=" icon-info22 tooltip-icon"></i></span>
                                            </label>
                                            <select class="multiselect-select-all-filtering form-control" name="services_multi_filter[]" id="services_multi_filter" multiple="multiple">

                                                <?php foreach ($service_list as $value): ?>
                                                <option value="<?= $value['job_name'] ?>"> <?= $value['job_name'] ?> </option>
                                                <?php endforeach ?>

                                            </select>

                                        </div>
                                        <div class="multi-select-full " id="program_service_ids_filter_parent" >
                                            <label for="programs_service_ids_filter">Filter Properties by Outstanding Services (require all)
                                                <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="By choosing any of the services below, Spraye will show only properties that have all of the chosen services outstanding in their account."><i class=" icon-info22 tooltip-icon"></i></span>
                                            </label>
                                            <select class="multiselect-select-all-filtering form-control" name="program_services_multi_filter[]" id="program_services_multi_filter" multiple="multiple">
                                                <?php foreach ($service_list as $value): ?>

                                                    <option value="<?= $value['job_name'] ?>"> <?= $value['job_name'] ?> </option>

                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="multi-select-full col-md-12" id="service_ids_filter_parent" style="padding-left: 4px; margin-top: 10px; margin-bottom: 10px;">
                                            <label for="programs_service_ids_filter">Available Days
                                                <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="By choosing the days below, Spraye will show only properties that are available on all of the chosen days."><i class=" icon-info22 tooltip-icon"></i></span>
                                            </label>
                                            <select class="multiselect-select-all-filtering form-control" name="available_days_filter[]" id="available_days_filter" multiple="multiple">
                                                <?php foreach ($available_days_list as $key => $value): ?>
                                                    <option value="<?= $value ?>" > <?= $key ?> </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>

                                        
                                </div>
                        <!-- </div> -->
                      </div>
				  </div>
				  
                        
                     <div class="row modal-footer">
                      <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
                            <div  id="update-map-div" class= "col-md-12" style="padding-top:10px">
                                
                            </div>
                     </div>
                </div>
               
        </div>
    </div>
</div> 
<!-------------------------------------------->

<!-- print modal -->
<div id="modal_theme_primary_print" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Do you wish to print this route’s worksheets?</h6>
            </div>
            <div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary" id="print_job_assign_bt">Print</button>
            </div>
        </div>
    </div>

</div>

<!--begin edit assign job  -->

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsn6KGL3R5IaZBQnVr5LowBTG9s19cRrc"></script>

<script>
    	//console.log('loading job names');	
        let serviceList = <?= json_encode($service_list) ?>


    // $('input[name=changeview]').change(function() {
    //     //$('#changeview').on('change', function() {
    //         //console.log("entered changeview listener");

    //     var mode = $(this).prop('checked');

    //     //var checkedValue = document.querySelector('input[name=changeview]:checked').value;
    //       //alert(mode);
    //       //alert($('input[name=changeview]').val());
    //       //alert(checkedValue);
    //     //if (mode == false) {
    //         switch(mode) {
    //             case false:
    //                 //$('#changeview').attr( "checked", false );
    //                 //console.log("False etay");
    //                 $('#tablediv').css('display', 'block');
    //                 $('#mapdiv').css('display', 'block');

    //                 //  $("#mapdiv").removeClass('col-md-4');
    //                 //  $("#mapdiv").addClass('col-md-12');

    //                 //  $("#tablediv").removeClass('col-md-8');
    //                 $("#tablediv").addClass('col-md-8');

    //                 break;

    //             case true:
    //                 //console.log("Beya");
    //                 //$('#changeview').attr( "checked", true );

    //                 $('#mapdiv').css('display', 'none');
    //                 $('#tablediv').css('display', 'block');
    //                 $("#tablediv").removeClass('col-md-8');
    //                 $("#tablediv").addClass('col-md-12');

    //                 //show the red update map button on left side
    //                 if (!$("#update-map-note").length > 0) {
    //                     //console.log("HEYYYYYYYYYYYYYYYYYYYYY");
    //                     $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-primary ml-5 btn btn-danger unassigned-services-element'text-semibold' style='font-size:18px;color:#FFFFFF;background-color:#F44336;'>Please click here to update the map</button");
    //                 }

    //                 break;

    //     }

    // });

    // if ($('input[name=changeview]:checked')) {

    //     var mode = $(this).prop('checked');
    //     //  alert(mode);
    //     if (mode == false) {
    //            //console.log("False etay2")
    //         $('#tablediv').css('display', 'block');
    //         $('#mapdiv').css('display', 'block');

    //         //  $("#mapdiv").removeClass('col-md-4');
    //         //  $("#mapdiv").addClass('col-md-12');

    //         //  $("#tablediv").removeClass('col-md-8');
    //         $("#tablediv").addClass('col-md-8');


    //     } else {
    //            //console.log("Beya2")

    //         $('#mapdiv').css('display', 'none');
    //         $('#tablediv').css('display', 'block');
    //         $("#tablediv").removeClass('col-md-8');
    //         $("#tablediv").addClass('col-md-12');


    //     }
    // }
</script>

<!-- KT -->
<script language="javascript" type="text/javascript">
   $("#select_all").change(function(){  //"select all" change 

      var status = this.checked; // "select all" checked status

      if (status) {
        $('#allMessage').prop('disabled', false);
          $('#multiple-delete-id,#multiple-restore-id').prop('disabled', false);
      }
      else
      {
         $('#allMessage').prop('disabled', true);
         $('#multiple-delete-id,#multiple-restore-id').prop('disabled', true);

      }

	   var sqftTotal = 0;

       /*$('.myCheckBox.map').each(function(){ //iterate all listed checkbox items
            //this.checked = status; //change ".checkbox" checked status

           var has_customer_in_hold=$(this).hasClass("customer_in_hold");
           console.log(has_customer_in_hold);
           if(!has_customer_in_hold){
               console.log(this)
               $(this).checked = status; //change ".checkbox" checked status
           }
           if ($(this).is(':checked')) {
               // //console.log( $(this).parent().parent().find('td').eq(5).html() );
               sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
            }
      });*/

	   $('#totalSqFt').val(sqftTotal);

      let applicationSqft = 0;
      let tmpAddressArray = [];
      $('#unassigntbl tbody input:checked').each(function() {
         let currentAddress = $(this).parent().parent().find('td').eq(10).text();
         if(!tmpAddressArray.includes(currentAddress)) {
            tmpAddressArray.push(currentAddress);
            applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
         }
         //console.log(applicationSqft);
         //console.log(tmpAddressArray);
      });
      $('#applicationSqFt').val(applicationSqft);

   });

</script>
<!-- KT -->

<script type="text/javascript">
    $(document).on("change", "table .myCheckBox", function() {
        if ($(".table .myCheckBox").filter(':checked').length < 1) {
            $('#allMessage').prop('disabled', true);
            $('#multiple-delete-id,#multiple-restore-id').prop('disabled', true);
        } else {
            $('#allMessage').prop('disabled', false);
            $('#multiple-delete-id,#multiple-restore-id').prop('disabled', false);
        }
    });
</script>

<script type="text/javascript">
    $(document).on("click", "#multiple-delete-id,#multiple-restore-id", function(e) {
        var group_id = [];
        var button_id = this.id;
        var url = "";

        // $("input:checkbox[name=group_id]:checked").each(function(){
        //    group_id.push($(this).val());
        // });

        var all_checked_boxes = $('input:checkbox[name=group_id]:checked');
        for (let i = 0; i < all_checked_boxes.length; i++) {
            var a_checked_box_val = all_checked_boxes[i].getAttribute('data-realvalue');
            group_id.push(a_checked_box_val);
        }

        var post_data = {};
        var success_message = "";
        post_data.group_id = group_id;
        if (button_id == "multiple-delete-id") {
            post_data.action = 'delete';
            success_message = "Deleted Successfully";
        } else {
            post_data.action = 'restore';
            success_message = "Restored Successfully";
        }
        ////console.log(post_data);
        swal({
            title: 'Are you sure?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $("#loading").css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('admin/deleteRestoreMultiUnassignedJobs') ?>",
                    data: post_data,
                    dataType: 'json'
                }).done(function(data) {
                    $("#loading").css("display", "none");
                    if (data.status == 200) {
                        swal(
                            'Unassigned Service(s) !',
                            success_message,
                            'success'
                        ).then(function() {
                            location.reload();
                        });
                    } else {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        })
                    }
                });
            }
        })
    });
    $(document).on("click", ".confirm_delete_unassign_job,.confirm_restore_unassign_job", function(e) {
        e.preventDefault();
        var action = "";
        var success_message = "";
        if ($(this).hasClass('confirm_delete_unassign_job')) {
            action = "delete";
            success_message = "Deleted Successfully";
        } else {
            action = "restore";
            success_message = "Restored Successfully";
        }
        var group_id = $(this).attr('grd_ids');
        swal({
            title: 'Are you sure?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $("#loading").css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('admin/deleteRestoreUnassignedJob') ?>",
                    data: {
                        group_id: group_id,
                        action: action
                    },
                    dataType: 'json'
                }).done(function(data) {

                    $("#loading").css("display", "none");

                    if (data.status == 200) {

                        swal(
                            'Unassigned Service !',
                            success_message,
                            'success'
                        ).then(function() {
                            location.reload();
                        })


                    } else {

                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        })
                    }


                });
            }
        })


    });



 // Setup - add a text input to each footer cell
//  $('#unassigntbl tfoot td').each(function() {
//             var title = $(this).text();
//             if (title == 'PRIORITY' ) {
//                 $(this).html('<input type="text" id = "pfilter" name = "pfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
//             } else if (title == 'SERVICE NAME' ) {
//                 $(this).html('<input type="text" id = "snfilter" name = "snfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
//             } else if (title == 'PROPERTY TYPE') {
//                 $(this).html('<input type="text" id = "ptfilter" name = "ptfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
//             } else if (title == 'SERVICE AREA') {
//                 $(this).html('<input type="text" id = "safilter" name = "safilter" class="form-control dtatableInput" placeholder="' + title + '" />');
//             }  else if (title == 'SERVICE DUE') { //Adding select option for service due filter
//                 $(this).html('<select class="form-control dtatableInput" id = "sdfilter" name = "sdfilter" placeholder="' + title + '" ><option value="0" class="default-option">-- DUE</option><option value="1">Due</option><option    value="2">Overdue</option><option value="3">Not Due</option></select>');
//             }  else if (title == 'NOTIFY CUSTOMER') { //Adding select option for notify customer filter
//                 $(this).html('<select class="form-control dtatableInput" id = "ncfilter" name = "ncfilter" placeholder="' + title + '" ><option value="0" class="default-option">-- NOTIFY</option><option value="1,4">CALL AHEAD</option></select>' );
//             } else if(title=='TAG' ){ //Adding select option for service due filter	
//                 var filterTags = '<?php echo $filter_tags; ?>';	
//                 $(this).html(filterTags);	
//             } else {
//                 $(this).addClass('noSpacingInput');
//             }
//         });




    $("#filter-criteria-id").unbind('click');
    $(document).on("click", "#filter-criteria-id", function(e) {
        // set it
        // var detached_filter = $("#pfilter").detach();
        // console.log("Detached Filter:");
        // console.log(detached_filter);

        // // back it
        // //$('#modal_add_filters').append( $("#pfilter"));
        // detached_filter.appendTo('div.priority-filter');
        //$("#pfilter").show();

            //    $("#modal_add_filters").append(global_r);
                $('#modal_add_filters').modal('show');
    });
</script>



<script>
    $(document).ready(function() {
        // $('#service_statuses_filter_filter').select2({
        //     allowClear: true,
        //     placeholder: "-- DUE",
        // });
        $(".service-due-filter").find(".btn-group").css("width", "100%");
        function getRowNum() {
            let e = new Error();
            e = e.stack.split("\n")[2].split(":");
            e.pop();
            return e.pop();
        }

        

        var MapMarkers = [];

        var map;
        var marker;
        var markers = [];
        var filteredMarkers = [];


        var tableData = [];
        var filteredData = [];
        var mapFilteredData = [];
        var furtherFilteredData = [];

        var allChecked = false;
        var priority = $('#priority_filter'),
            name = $('#service_name_filter'),
            property = $('#property_type_filter'),
            area = $('#service_area_filter');



        // // Setup - add a text input to each footer cell
        // $('#unassigntbl tfoot td').each(function() {
        //     var title = $(this).text();
        //     if (title == 'PRIORITY' ) {
        //         $(this).html('<input type="text" id = "pfilter" name = "pfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
        //     } else if (title == 'SERVICE NAME' ) {
        //         $(this).html('<input type="text" id = "snfilter" name = "snfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
        //     } else if (title == 'PROPERTY TYPE') {
        //         $(this).html('<input type="text" id = "ptfilter" name = "ptfilter" class="form-control dtatableInput" placeholder="' + title + '" />');
        //     } else if (title == 'SERVICE AREA') {
        //         $(this).html('<input type="text" id = "safilter" name = "safilter" class="form-control dtatableInput" placeholder="' + title + '" />');
        //     }  else if (title == 'SERVICE DUE') { //Adding select option for service due filter
        //         $(this).html('<select class="form-control dtatableInput" id = "sdfilter" name = "sdfilter" placeholder="' + title + '" ><option value="0" class="default-option">-- DUE</option><option value="1">Due</option><option    value="2">Overdue</option><option value="3">Not Due</option></select>');
        //     }  else if (title == 'NOTIFY CUSTOMER') { //Adding select option for notify customer filter
        //         $(this).html('<select class="form-control dtatableInput" id = "ncfilter" name = "ncfilter" placeholder="' + title + '" ><option value="0" class="default-option">-- NOTIFY</option><option value="1,4">CALL AHEAD</option></select>' );
        //     } else if(title=='TAG' ){ //Adding select option for service due filter	
        //         var filterTags = '<?php echo $filter_tags; ?>';	
        //         $(this).html(filterTags);	
        //     } else {
        //         $(this).addClass('noSpacingInput');
        //     }
        // });

        //$('input[name=changeview]').change(function() {  
        $(document).on("input paste", "input[name=changeview]", function() {
            ////console.log("entered changeview listener");
        
            //var mode = $(this).prop('checked');
            //alert(mode);

            //if (mode == false) {
            if (!$(this).is(':checked')) {
            
                //console.log("False etay");

                $('#tablediv').css('display', 'block');
                $('#mapdiv').css('display', 'block');

                //  $("#mapdiv").removeClass('col-md-4');
                //  $("#mapdiv").addClass('col-md-12');
                //  $("#tablediv").removeClass('col-md-8');

                $("#tablediv").addClass('col-md-8');
                


                //show the red update map button on left side
                // if (!$("#update-map-note").length > 0) {
                //     $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-primary ml-5 btn btn-danger unassigned-services-element'text-semibold' style='font-size:18px;color:#FFFFFF;background-color:#F44336;'>Update Map View</button");
                // }


            } else {
               //console.log("Beya");
            //    $('#unassigntbl').DataTable( {
            //     dom: 'Bl<"toolbar">frtip',
            //     } );


                $('#mapdiv').css('display', 'none');
                $('#tablediv').css('display', 'block');
                $("#tablediv").removeClass('col-md-8');
                $("#tablediv").addClass('col-md-12');

                $("#update-map-note").remove();

            }
        
        });

        
        //Should add a clear filters button
        //This function breaks pagination and server-side loading
        function LoadMap() {

            //render the filter searches "footer"
            global_r = $('#unassigntbl tfoot td');
            

            
            var mapOptions = {
                //center: new google.maps.LatLng(41.881832, -87.623177), //coordinates for Chicago
                zoom: 25,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableAutoPan: true,
				maxZoom:25
            };


            map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);

           

        
            
           var markers = MapMarkers;

           //console.log(MapMarkers);

           

            google.maps.event.addListener(map, 'dragend', function boundsChanged() {

                //console.log("This is initial load: " + initial_load);
				//console.log(filteredMarkers);
				//console.log(markers);
                  
                var validmarkers = [];
                var passmarkers = [];
                var count3 = 0;

                var latArray = [];
                var lngArray = [];
                var gData = '';
                // Construct an empty list to fill with onscreen markers.
                var inBounds = [],
                    // Get the map bounds - the top-left and bottom-right locations.
                    bounds = map.getBounds();
                
                /////
				if(typeof table !== 'undefined')
					{
						console.log("this is our filteredMarkers");
                       

                        filteredMarkers = [];

                        MapMarkers.forEach(item => {
                            item.setMap(null);
                        });


                        var tableData = table.data();
                        var latlngbounds = new google.maps.LatLngBounds();


                        // Get current map bounds
                        var bounds = map.getBounds();
                        var southWest = bounds.getSouthWest();
                        var northEast = bounds.getNorthEast();
                        var northEastLng = northEast.lng();
                        var northEastLat = northEast.lat();
                        var southWestLng = southWest.lng();
                        var southWestLat = southWest.lat();
                        var northWestLng = northEastLng;
                        var northWestLat = southWestLat;
                        var southEastLng = southWestLng;
                        var southEastLat = northEastLat;

                        for (let i = 0; i < tableData.length; i++) {

                            var data = tableData[i];
                            filteredMarkers.push(data);
                            
                            var lancedata = JSON.parse(JSON.stringify(tableData[i]));
                            
                            var splitstring = lancedata.action.split("grd_ids='");
                            var split2 = splitstring[1].split("'");
                            var gudid = split2[0];


                            var marker = new google.maps.Marker({
                                icon: '<?= base_url("assets/img/default.png") ?>',
                                position: new google.maps.LatLng(tableData[i].property_latitude, tableData[i].property_longitude),
                                lat: tableData[i].property_latitude,
                                lng: tableData[i].property_longitude,
                                map: map,
                                title: tableData[i].address,
                                realval: gudid,
                                index: i

                            });

                            latlngbounds.extend(marker.position);
							//console.log("latlngbounds: ");
							

                            MapMarkers.push(marker);
							//console.log('placing marker '+marker.position);


						}
				
						}
				
				//////
							
							var markers = MapMarkers;

                            //console.log(MapMarkers);

				
				
				for (var i = 0; i < markers.length; i++) {
                    if (map.getBounds().contains(markers[i].getPosition())) {
                        
                        passmarkers[i] = markers[i];
                        
                        //need to get an array of valid markers here
                        validmarkers[i] = markers[i].realval;
                         
                         count3++;
                    } else {
						//console.log("property out of bounds "+markers[i].realval);
                    }
                }

              

                var markerarray = [];
                var count = 0;
                var count2 = 0;
				
				//console.log('validmarker length '+validmarkers.length);

                for (var i = 0; i < validmarkers.length; i++) {

                    if (typeof validmarkers[i] !== 'undefined') {
                        
                        markerarray.push(validmarkers[i]);
                       
                        count++;

                        //console.log("VALID MARKER "+validmarkers[i]);
						
                        

                    } else {
                        //console.log("BAD ADDRESS");
                    }

                    
                    
                }
				//console.log("VALID MARKER COUNT "+count);

                //console.log("THIS IS COUNT OF VALID MARKERS " + count);
               // //console.log(count);
                // for (var i = 0; i < validmarkers.length; i++) {

                //     if (typeof validmarkers[i] !== 'undefined') {
                       
                //         count2++;
                        
                //     } 
                    
                // }

               
                    //console.log(markerarray);
           

                    
				//console.log("inside load map table init");

                
                table = $('#unassigntbl').on( 'draw.dt', function () {
                   
                } ).DataTable({
                    destroy: true,
                    serverSide: true,
                    ajax: {
                        url: "<?= base_url('admin/ajaxGetRoutingFORMAPS/') ?>",
                        dataType: "json",
                        type: "POST",
                        data: {
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                            ,
                            'markerarray': JSON.stringify(markerarray),
                            northEastLng: northEastLng,
                            northEastLat: northEastLat,
                            northWestLng: northWestLng,
                            northWestLat: northWestLat,
                            southWestLng: southWestLng,
                            southWestLat: southWestLat,
                            southEastLng: southEastLng,
                            southEastLat: southEastLat
                        }
                    },

                    stateSave: true, //keeps data table from removing filters. breaks updating based on map movement
                    deferRender: true, //false
                    columnDefs: [{
                        "targets": [0],
                        "checkboxes": {
                            "selectRow": true,
                            "stateSave": true
                        }
                    }, ],
                    select: "multi",
                    columns: [{
                            "data": "checkbox",
                            "checkboxes": true,
                            "stateSave": true,
                            "searchable": false,
                            "orderable": false
                        },
                        {
                            "data": "priority",
                            "name": "Priority",
                            "orderable": true,
                            "searchable": true
                        },
                        {
                            "data": "job_name",
                            "name": "Service Name",
                            "searchable": true,
                            "orderable": true
                        },
                        {   "data": "pre_service_notification", 
                            "name":"Notify Customer", 
                            "orderable": true, 
                            "searchable": true 
                        },
                        {
                            "data": "customer_name",
                            "name": "Customer Name",
                            "searchable": true,
                            "orderable": true
                        },
                        {
                            "data": "property_name",
                            "name": "Property Name",
                            "searchable": true,
                            "orderable": true
                        },
                        {
                            "data": "square_feet",
                            "name": "Square Feet",
                            "orderable": true
                        },
                        {
                            "data": "last_service_date",
                            "name": "Last Service Date",
                            "orderable": true
                        },
                        {
                            "data": "last_program_service_date",
                            "name": "Last Program Service Date",
                            "orderable": true
                        },
                        {
                            "data": "completed_date_last_service_by_type",
                            "name": "Last Service Type Date",
                            "orderable": true
                        },
                        {
                            "data": "service_due",
                            "name": "Service Due",
                            "orderable": true
                        },
                        {
                            "data": "address",
                            "name": "Address",
                            "searchable": true,
                            "orderable": true
                        },
                        {
                            "data": "property_type",
                            "name": "Property Type",
                            "orderable": true
                        },
                        {
                            "data": "property_notes",
                            "name": "Property Info",
                            "searchable": true,
                            "orderable": true
                        },
                        {
                            "data": "category_area_name",
                            "name": "Service Area",
                            "orderable": true
                        },
                        {
                            "data": "program",
                            "name": "Program",
                            "orderable": true
                        },
                        {
                            "data": "reschedule_message",
                            "name": "Note",
                            "orderable": true
                        },
                        {
                            "data": "tags",	
                            "name":"Tags",	
                            "orderable": true	
                        },
                        {
                            "data": "asap_reason",
                            "name":"Asap Reason",
                            "orderable": true
                        },
                        {
                            "data": "available_days",
                            "name":"Available Days",
                            "orderable": false
                        },
                        {
                            "data": "action",
                            "name": "Action",
                            "searchable": false,
                            "orderable": false
                        },
                        {
                            "data": "program_services", //
                            "name": "Program Service Name",
                            "searchable": true,
                            "visible": false
                        }
                    ],
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
                    pagingType: "simple_numbers",

                    paging: true,
                    pageLength: 10000, //not pulling all data. Only the first 100 (because client side)
                    order: [
                        [1, 'asc'],
                        [2, 'asc']
                    ], 
                    processing: true,
                     
                    dom: 'Bl<"toolbar">frti',
                    initComplete: function() {

                        // $('#loading').show();

                        console.log("this is our filteredMarkers2");
                       

                        filteredMarkers = [];

                        MapMarkers.forEach(item => {
                            item.setMap(null);
                        });


                        var tableData = table.data();
                        var latlngbounds = new google.maps.LatLngBounds();

                        //console.log("This is tableData: ");
                        //console.log(tableData);

                        //console.log("This is tableData.length: ");
                        //console.log(tableData.length);

                        for (let i = 0; i < tableData.length; i++) {

                            var data = tableData[i];
                            filteredMarkers.push(data);
                            
                            var lancedata = JSON.parse(JSON.stringify(tableData[i]));
                            
                            var splitstring = lancedata.action.split("grd_ids='");
                            var split2 = splitstring[1].split("'");
                            var gudid = split2[0];

                            var infoWindow = new google.maps.InfoWindow({
                                content: tableData[i].address
                            });

                            var marker = new google.maps.Marker({
                                icon: '<?= base_url("assets/img/default.png") ?>',
                                position: new google.maps.LatLng(tableData[i].property_latitude, tableData[i].property_longitude),
                                lat: tableData[i].property_latitude,
                                lng: tableData[i].property_longitude,
                                map: map,
                                title: tableData[i].address,
                                realval: gudid,
                                index: i

                            });


                            (function(marker, data) {
                            google.maps.event.addListener(marker, "click", function(e) {
                                infoWindow.setContent(tableData[i].address);

                                infoWindow.open(map, marker);
                            });
                            })(marker, data);

                            latlngbounds.extend(marker.position);
							//console.log("latlngbounds: ");
							

                            MapMarkers.push(marker);
							//console.log('placing marker '+marker.position);

                        }



                        
                        if (set_initial_center == true) {
                        latlngbounds.extend(marker.position);
            			map.fitBounds(latlngbounds);
                        map.setZoom(4);
                        
                        set_initial_center = false;
                        
                        }
                       
						//console.log("zoom level "+map.getZoom());
                     
                        // $("div.toolbar").html('');

                        $('#filter-criteria-id').remove();
                        $('#multiple-delete-id').remove();
                        
                        $(".dataTables_filter")
                            .append('<button id="filter-criteria-id" class="btn btn-primary ">Filters</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button disabled="disabled" id="multiple-delete-id" class="btn btn-danger unassigned-services-element">Delete Services</button>');
                        
                        // $("#maintable_filter")
                        //     .append('<button id="filter-criteria-id" class="btn btn-primary ">Filters</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button disabled="disabled" id="multiple-delete-id" class="btn btn-danger unassigned-services-element">Delete Services</button>');
                        // $("div.toolbar")
                        //     .append($('#multiple-delete-id'));

                        //     $("div.toolbar")
                        //     .append($('#filter-criteria-id'));

                        // $("div.toolbar")
                        //     .append(global_r);

                        //$(global_r).hide();
            
                        var sqftTotal = 0;
                        var applicationSqft = 0;
                        $('#totalSqFt').val(sqftTotal);
                        $('#applicationSqFt').val(applicationSqft);

                        //console.log("This is application square feet:");
                        //console.log(applicationSqft);

                        //remove update map button on drag because map updates on drag.
                        $("#update-map-note").remove();
                        $("#update-map-note-two").remove();
                        

                        //Get priority input value
                        if (sessionStorage.getItem("prio_input")) {
                        //console.log("This is prio input");
                        //console.log(sessionStorage.getItem("prio_input"));
                        document.getElementById("pfilter").value = sessionStorage.getItem("prio_input");
                        }

                        //Get service name input value
                        if (sessionStorage.getItem("serv_name_input")) {
                        //console.log(sessionStorage.getItem("serv_name_input"));
                        document.getElementById("snfilter").value = sessionStorage.getItem("serv_name_input");
                        }

                        //Get service due input value
                        if (sessionStorage.getItem("serv_due_input")) {
                        //console.log(sessionStorage.getItem("serv_due_input"));
                        document.getElementById("sdfilter").selectedIndex = sessionStorage.getItem("serv_due_input");
                        }

                        //Get property type input value
                        if (sessionStorage.getItem("prop_type_input")) {
                        //console.log(sessionStorage.getItem("prop_type_input"));
                        document.getElementById("ptfilter").value = sessionStorage.getItem("prop_type_input");
                        }

                        //Get service area input value
                        if (sessionStorage.getItem("serv_area_input")) {
                        //console.log(sessionStorage.getItem("serv_area_input"));
                        document.getElementById("safilter").value = sessionStorage.getItem("serv_area_input");
                        }

                        // Get "Notify" input value
                        if (sessionStorage.getItem("notify_input")) {
                        //console.log(sessionStorage.getItem("notify_input"));
                        
                        }

                        //Get 'Search" input value
                        if (sessionStorage.getItem("search_input")) {
                        //console.log(sessionStorage.getItem("search_input"));
                        
                        }

                        //Get 'Search" input value
                        if (sessionStorage.getItem("serv_multi_filter_input")) {
                        //console.log(sessionStorage.getItem("serv_multi_filter_input"));
                        
                        }

                        // $("#unassigntbl").DataTable().columns(1).search("6").draw();
                        // Connect the filter inputs to filter data

                        // 'Search' filter input
                        $('.dataTables_filter input').on('input', function(e) {
                           
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("search_input", filter_input_val);
                            
                            $('#filter_message').hide();
                            $("#update-map-note").remove();
                            $("#update-map-note-two").remove();
                            if (!$('input[name=changeview]').is(':checked')) {

                                //modal note
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger 'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                                //above map note
                                if (!$("#update-map-note-two").length > 0) {
                                    $("#update-map-div-two").append("<button type='button' id='update-map-note-two' class ='btn btn-danger 'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div-two").append($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note-two").prop("disabled",true);
                                }
                            }
                        });

                        // red click to update map button on "Show:" dropdown change
                        $('select[name="unassigntbl_length"]').on('change', function() { // SERVICE DUE
                            var filter_input_val = this.querySelector('input').value;
                            sessionStorage.setItem("serv_due_input", filter_input_val);
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });
                        
                        $('#pfilter').on('blur', function priority() { // PRIORITY
                            //console.log("priority input changed");
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            //console.log(filter_input_val);
                            sessionStorage.setItem("prio_input", filter_input_val);
                            
                                table.columns(1).search(filter_input_val).draw();
                                $('#unassigntbl_info').show();
                                $('#unassigntbl').show();
                                $('#filter_message').hide();
                                
                           
                            
                            $("#update-map-note").remove();
                            //$("#update-map-note").hide();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    //$("#update-map-note").show();
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                            //console.log("this is priority filter val");
                            //console.log( $('#priority_filter').val());
                        });

                        // Connect the filter inputs to filter data
                        $('#snfilter').on('blur', function() { // SERVICE NAME
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("serv_name_input", filter_input_val);
                            table.columns(2).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        // Connect the filter inputs to filter data
                        $('#ncfilter').on('blur', function() { // NOTIFY
                            //var filter_input_val = this.querySelector('select').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("notify_input", filter_input_val);
                            table.columns( 3 ).search( filter_input_val ).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        // Connect the filter inputs to filter data
                        $('#sdfilter').on('change', function() { // SERVICE DUE
                            //var filter_input_val = $('#service_due_filter option:selected').val();
                            var filter_input_val = $('#sdfilter').val();
                            let filter_input_val_arr = filter_input_val.join('|');

                            //console.log(filter_input_val);
                            sessionStorage.setItem("serv_due_input", filter_input_val);
                            table.columns(10).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });


                        // Connect the filter inputs to filter data
                        $('#ptfilter').on('blur', function() { // PROPERTY TYPE
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("prop_type_input", filter_input_val);
                            table.columns(11).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        // Connect the filter inputs to filter data
                        // $('#safilter').on('blur', function() { // SERVICE AREA
                        //     //var filter_input_val = this.querySelector('input').value;
                        //     var filter_input_val = $(this).val();
                        //     sessionStorage.setItem("serv_area_input", filter_input_val);
                        //     table.columns(13).search(filter_input_val).draw();
                        //     $("#update-map-note").remove();
                        //     if (!$('input[name=changeview]').is(':checked')) {
                        //         if (!$("#update-map-note").length > 0) {
                        //             $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                        //             $("#update-map-div").prepend($("#loading-image"));
                        //             $("#loading-image").show();
                        //             $("#update-map-note").prop("disabled",true);
                        //         }
                        //     }
                        // });

                        // Connect the filter inputs to filter data
                        $('#tag_filter').on('change', function() { // PROPERTY TYPE
                            //var filter_input_val = this.querySelector('select').value;
                            var filter_input_val = $('#tag_filter option:selected').val();
                            // sessionStorage.setItem("tags_filter_input", filter_input_val);
                            table.columns( 16 ).search( filter_input_val ).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        //Connect Multi-Select input to filter data
                        $('#services_multi_filter').on('change', function() { // Service Name
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("serv_multi_filter_input", filter_input_val);
                            $('#applicationSqFt').val('');
                            $('#totalSqFt').val('');
                            let multi_service_val = $(this).val();
                            
                            table.columns( 2 ).search( multi_service_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });
                        //Connect Multi-Select input to filter data
                        $('#service-area-filter').on('change', function() { // Service Name
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("service-area-filter", filter_input_val);
                            $('#applicationSqFt').val('');
                            $('#totalSqFt').val('');
                            let multi_service_val = $(this).val();

                            table.columns( 14 ).search( multi_service_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });
                        $('#program_services_multi_filter').on('change', function() { // Service Name
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("program_services_multi_filter", filter_input_val);
                            $('#applicationSqFt').val('');
                            $('#totalSqFt').val('');
                            table.columns( 18 ).search( filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });
                        $('#asap-reason').on('blur', function() { // PROPERTY TYPE
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("asap-reason", filter_input_val);
                            table.columns(18).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        $('#available_days_filter').on('change', function() { // PROPERTY TYPE
                            //var filter_input_val = this.querySelector('input').value;
                            var filter_input_val = $(this).val();
                            sessionStorage.setItem("available_days_filter", filter_input_val);
                            table.columns(19).search(filter_input_val).draw();
                            $("#update-map-note").remove();
                            if (!$('input[name=changeview]').is(':checked')) {
                                if (!$("#update-map-note").length > 0) {
                                    $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger ml-5'text-semibold' style='color:#FFFFFF;background-color:#fccecb;'>Update Map View</button>");
                                    $("#update-map-div").prepend($("#loading-image"));
                                    $("#loading-image").show();
                                    $("#update-map-note").prop("disabled",true);
                                }
                            }
                        });

                        // BLUE ROWS for rescheduled on page load
                        $('.myCheckBox').each(function() {
                              var row_job_mode = $(this).data('row-job-mode');
                              if (row_job_mode == 2) {
                                 $(this).parent().parent().addClass('rescheduled_row');
                              }
                              let asap_job_mode = $(this).data('row-asap');

                              if (asap_job_mode == 1){
                                $(this).parent().parent().addClass('asap_row');
                              }
                        });

                        
                        // CALCULATE total square feet
                        //KT
                        $('.myCheckBox').change(function(){

                            var sqftTotal = 0;
                            $('#unassigntbl tbody input:checked').each(function() {
                                sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
                            });
                            $('#totalSqFt').val(sqftTotal);

                            let applicationSqft = 0;
                            let tmpAddressArray = [];
                            $('#unassigntbl tbody input:checked').each(function() {
                                let currentAddress = $(this).parent().parent().find('td').eq(10).text();
                                if(!tmpAddressArray.includes(currentAddress)) {
                                    tmpAddressArray.push(currentAddress);
                                    applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
                                }
                                //console.log(applicationSqft);
                                //console.log(tmpAddressArray);
                            });
                            $('#applicationSqFt').val(applicationSqft);

                           
                            if(this.checked == false){ //if this item is unchecked
                                    $("#select_all")[0].checked = false; //change "select all" checked status to false
                            }

                            //check "select all" if all checkbox items are checked
                            if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
                                    $("#select_all")[0].checked = true; //change "select all" checked status to true
                            }
                        });
                        //KT

                        //addCheckBoxEvent();
						
						

                        // FIRE EVERYTIME AFTER TABLE HAS RENDERED
                        table.on('draw', function() {

                            

                            $('#loading-image').hide();

                            // BLUE ROWS for rescheduled on page load
                            $('.myCheckBox').each(function() {
                                var row_job_mode = $(this).data('row-job-mode');
                                if (row_job_mode == 2) {
                                    $(this).parent().parent().addClass('rescheduled_row');
                                }
                                let asap_job_mode = $(this).data('row-asap');

                                if (asap_job_mode == 1){
                                    $(this).parent().parent().addClass('asap_row');
                                }
                            });

                            // Modal Update Map button dark red
                            if ($("#update-map-note").length > 0) {
                                $("#update-map-note").remove();
                                $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-danger'text-semibold' style='color:#FFFFFF;background-color:#F44336;'>Update Map View</button>");
                            }

                            //above map Update Map button dark red
                            if ($("#update-map-note-two").length > 0) {
                                $("#update-map-note-two").remove();
                                $("#update-map-div-two").append("<button type='button' id='update-map-note-two' class ='btn btn-danger'text-semibold' style='color:#FFFFFF;background-color:#F44336;'>Update Map View</button>");
                            }


                            // CALCULATE total square feet on ajax table refresh
                            $('.myCheckBox').change(function() {

                                var sqftTotal = 0;
                                $('#unassigntbl tbody input:checked').each(function() {
                                sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
                                });
                                $('#totalSqFt').val(sqftTotal);

                                let applicationSqft = 0;
                                let tmpAddressArray = [];
                                $('#unassigntbl tbody input:checked').each(function() {
                                    let currentAddress = $(this).parent().parent().find('td').eq(10).text();
                                    if(!tmpAddressArray.includes(currentAddress)) {
                                        tmpAddressArray.push(currentAddress);
                                        applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
                                    }
                                    //console.log(applicationSqft);
                                    
                                });
                                $('#applicationSqFt').val(applicationSqft);

                                //uncheck "select all", if one of the listed checkbox item is unchecked
                                if(this.checked == false){ //if this item is unchecked
                                    $("#select_all")[0].checked = false; //change "select all" checked status to false
                                }

                                //check "select all" if all checkbox items are checked
                                if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
                                    $("#select_all")[0].checked = true; //change "select all" checked status to true
                                }
                            });

                            //after search
                            $('.customer_in_hold').each(function() {
                                    var _col=$(this).parent();
                                    var _row=$(_col).parent();
                                    $(_row).addClass('row_in_hold');
                            });
                        });

                        //on draw
                        $('.customer_in_hold').each(function() {
                            var _col=$(this).parent();
                            var _row=$(_col).parent();
                            $(_row).addClass('row_in_hold');
                        });

                    } // end InitComplete


                });

                /////////////////////////////////////////



                ////////////////////////////////////////////////////////////////


            });

            google.maps.event.addListener(map, 'idle', function() {
            //console.log("Initial load entering idle listener");
                $("#select_all")[0].checked = false;
                 //google.maps.event.trigger(this, 'dragend');
                 
            });


            //table.state.clear();
            var switched = true; //false;
            var filterInput = false;
            var filterArray = [false, false, false, false, false];
            var filterArray2 = [false, false, false, false, false];
            var triggers = [
                [false, false],
                [false, false],
                [false, false],
                [false, false],
                [false, false],
            ];







        } //end LoadMap()
		

		$( "#resetMap" ).click(function() {
			//console.log('in resetMap');

			setTimeout(() => {
  				//console.log("Delayed for 1 second.");
				google.maps.event.trigger(map, 'dragend');
			}, 1000);
			//map.setZoom(10);
            $("#update-map-note").remove();
            $("#update-map-note-two").remove();
            $('#unassigntbl_info').show();
            $('#unassigntbl tbody').show();
            $('#filter_message').hide();
            $(".close").click();
		});

        //Reset the map within modal
        $('body').on("click", "#update-map-note", function(){
            $( "#resetMap" ).trigger('click');
        });

        //Reset the map outside modal
        $('body').on("click", "#update-map-note-two", function(){
            $( "#resetMap" ).trigger('click');
        });

        //not being used
        function styleNote() {
            const updateMapNote = document.querySelector('#update-map-note');
            updateMapNote.classList.toggle('show');
        }

            
        

        function addCheckBoxEvent() {
            ////console.log("entered addCheckBoxEvent");



            $(document).on("input paste", "input:checkbox.map", function() {
                //$(document).on("click", "input:checkbox.map", function(){
                //$(document).on("click", ".myCheckBox", function(){


                ////console.log("entered checkbox input click inside addCheckBoxEvent");

                // LF - shouldnt we get values of all checked checkboxes each time an event occurs and store
                var checked_realvalue = [];
                $('input:checkbox.map').each(function(index, element) {
                    ////console.log("REACHED INPUT CHECKBOX MAP");
                    if ($(this).is(":checked")) {
                       // //console.log("if input checkbox map .each is checked");
                       // //console.log("Index = " + index);
                        var checked_value = $(this).data('realvalue');
                        checked_realvalue.push(checked_value);
                        ////console.log('checked: '+ $(this).val());

                    }

                });
                ////console.log('array of checked indexes = ' + checked_realvalue);
                $('#checkbox_realvalues_array').val(checked_realvalue);

                /// END LF		 
                position = $(this).val();

                // //console.log($(this).val());

                ////console.log("checked value = " + position);
                ////console.log("filteredMarkers.length = " + filteredMarkers.length);


                ////console.log(filteredMarkers);

                for (let i = 0; i < filteredMarkers.length; i++) {
                    // //console.log("position: " + position);
                    // //console.log("filteredMarkers.index = " + filteredMarkers[i].index);
                    if (position == filteredMarkers[i].index) {
                        // //console.log(filteredMarkers[i]);
                        var data = filteredMarkers[i];
                        ////console.log(data);
                    }
                }

                //  let data = filteredMarkers[position];

                // //console.log(JSON.stringify(data));
                //console.log('data.lat: ' + data.lat);
                //console.log('data.lng: ' + data.lng);

                //  //console.log(MapMarkers);

                const image = "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-poi.png";

                let mapMarker = MapMarkers[position];
                let myLatlng = new google.maps.LatLng(data.lat, data.lng);
                let title = data.title;

                ////console.log(MapMarkers);
                if (mapMarker != null) {
                    mapMarker.setMap(null);
                }

                if (!$(this).is(":checked")) {
                    //console.log("in inactive marker");

                    marker = new google.maps.Marker({
                        icon: '<?= base_url("assets/img/default.png") ?>',
                        position: myLatlng,
                        map: map,
                        title: data.address
                    });

                    MapMarkers[position] = marker;
                } else {
                    //console.log("in active marker");
                    //var data = markers[position];
                    //var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                    marker = new google.maps.Marker({
                        // icon: '<?= base_url("assets/img/free-8-red.png") ?>',
                        icon: image,
                        position: myLatlng,
                        map: map,
                        title: data.address
                    });

                    MapMarkers[position] = marker;

                }

            });

            // CALCULATE total square feet
            $('.myCheckBox').change(function() {
                //console.log(".myCheckBox changed");

                var sqftTotal = 0;
                $('#unassigntbl tbody input:checked').each(function() {
                    sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
                });
                $('#totalSqFt').val(sqftTotal);

                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (this.checked == false) { //if this item is unchecked
                    $("#select_all")[0].checked = false; //change "select all" checked status to false
                }

                //check "select all" if all checkbox items are checked
                if ($('.myCheckBox:checked').length == $('.myCheckBox').length) {
                    $("#select_all")[0].checked = true; //change "select all" checked status to true
                }
            });
        }

       
        //sets starting map position
        function SetMarker(position) {
            //Remove previous Marker.
            if (marker != null) {
                marker.setMap(null);
            }
            //Set Marker on Map.
            if (position) {

            } else {
                if (markers.length > 0) {
                    allunchecked();
                }
            }
        }

        //brings select all checkbox functionality
        $("#select_all").change(function() { //"select all" change 

            var status = this.checked; // "select all" checked status
            //console.log("Select all status is" + JSON.stringify(status));
            if (status) {
                $('#allMessage').prop('disabled', false);
                $('#multiple-delete-id,#multiple-restore-id').prop('disabled', false);
                checkAll();
            } else {
                $('#allMessage').prop('disabled', true);
                $('#multiple-delete-id,#multiple-restore-id').prop('disabled', true);
                unCheckAll();
            }

            var sqftTotal = 0;
            $('.myCheckBox.map').each(function() { //iterate all listed checkbox items
                this.checked = status; //change ".checkbox" checked status
                if ($(this).is(':checked')) {

                    sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());

                }
            });

            $('#totalSqFt').val(sqftTotal);

        });

        //changes map marker highlights back to original
        function unCheckAll() {
            allchecked = false;
            var infoWindow = new google.maps.InfoWindow();

            MapMarkers.forEach(item => {
                item.setMap(null);
            });

            MapMarkers = [];

            for (i = 0; i < filteredMarkers.length; i++) {
                var data = filteredMarkers[i]
                var myLatlng = new google.maps.LatLng(data.lat, data.lng);


                marker = new google.maps.Marker({
                    icon: '<?= base_url("assets/img/default.png") ?>',
                    position: myLatlng,
                    map: map,
                    title: data.address
                });

                MapMarkers.push(marker);
            }

            // disable buttons that need checkboxes clicked
            $('#allMessage').prop('disabled', true);
            $('#multiple-delete-id').prop('disabled', true);

        }


        function allunchecked() {
            //console.log("entered allunchecked");
            allchecked = false;

            var infoWindow = new google.maps.InfoWindow();
            var lat_lng = new Array();

            var latlngbounds = new google.maps.LatLngBounds();
            

            filteredMarkers = [];

            //console.log("allunchecked markers.length " + markers.length);
			//console.log(markers);

            for (i = 0; i < markers.length; i++) {
                var data = markers[i];
                
                filteredMarkers.push(data);
                var myLatlng = new google.maps.LatLng(data.lat, data.lng);

                var lancedata = JSON.parse(data.lancedata)
                var splitstring = lancedata.action.split("grd_ids='");
                var split2 = splitstring[1].split("'");
                var gudid = split2[0];


                var marker = new google.maps.Marker({
                    icon: '<?= base_url("assets/img/default.png") ?>',
                    position: myLatlng,
                    map: map,
                    title: data.address,
                    realval: gudid

                });

                    
                latlngbounds.extend(marker.position);

                MapMarkers.push(marker);
            }
           
            map.fitBounds(latlngbounds);
        }

        function checkAll() {
            allchecked = true;
            var infoWindow = new google.maps.InfoWindow();

           
            MapMarkers.forEach(item => {
                item.setMap(null);
            });

            MapMarkers = [];

            const image = "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-poi.png";

            for (i = 0; i < filteredMarkers.length; i++) {
                var data = filteredMarkers[i];
                var myLatlng = new google.maps.LatLng(data.lat, data.lng);

                var marker = new google.maps.Marker({
                    //icon: '<?= base_url("assets/img/free-8-red.png") ?>',
                    icon: image,
                    position: myLatlng,
                    map: map,
                    title: data.address
                });

                
                MapMarkers.push(marker);
            }
        }

        $('#unassigntbl').DataTable().state.clear();
        $('#unassigntbl').DataTable().destroy();
        $('#unassigntbl').DataTable().search('').columns().search('').draw();
        
		
		LoadMap();
        
        
        $(document).on('click', '.myCheckBox', function() {
            //console.log("checkbox document on click");
            addCheckBoxEvent();
        });

        //render the table view on page load
        if (!$('input[name=changeview]').is(':checked')) {
            
            //console.log("False etay");

            $('#tablediv').css('display', 'block');
            $('#mapdiv').css('display', 'block');

            //  $("#mapdiv").removeClass('col-md-4');
            //  $("#mapdiv").addClass('col-md-12');
            //  $("#tablediv").removeClass('col-md-8');

            $("#tablediv").addClass('col-md-8');
           

            //show the red update map button on left side
            // if (!$("#update-map-note").length > 0) {
            //     $("#update-map-div").append("<button type='button' id='update-map-note' class ='btn btn-primary ml-5 btn btn-danger unassigned-services-element'text-semibold' style='font-size:18px;color:#FFFFFF;background-color:#F44336;'>Please click here to update the map</button");
            // }


        } else {
            //console.log("Beya");
           
            $('#mapdiv').css('display', 'none');
            $('#tablediv').css('display', 'block');
            $("#tablediv").removeClass('col-md-8');
            $("#tablediv").addClass('col-md-12');

            $("#update-map-note").remove();

        }

        //map is in beta note
        //$(".qun").append('<span class="chtn" style="padding-left: 100px; padding-right:400px;"><a href="" data-toggle="modal" data-target="#help_message" >This map feature is currently in beta. Please send us your feedback here</a></span>');
		
        // google.maps.event.trigger(map, 'dragend');

        // $('#unassigntbl').DataTable().state.clear();
        // $('#unassigntbl').DataTable().destroy();
        // $('#unassigntbl').DataTable().search('').columns().search('').draw();\notifications.html


        // //setTimeout(() => {
        
         google.maps.event.trigger(map, 'dragend');
        //  
                   

        if (sessionStorage) {
            sessionStorage.clear();
        }
        
        sessionStorage.setItem("serv_name_input", "");
        sessionStorage.removeItem("serv_name_input");
	});



   

    $('.primary-assign').click(function() {

        if ($(this).val() == 1) {
            $('#route_input').css('display', 'none');
            $('#route_select').css('display', 'block');
        } else if ($(this).val() == 2) {
            $('#route_input').css('display', 'block');
            $('#route_select').css('display', 'none');
        }

    });


    $(document).on("click", "#changespecifictime", function(e) {
        if ($(this).prop("checked") == true) {
            $('#specific_time_input').css('display', 'block');
        } else if ($(this).prop("checked") == false) {
            $('#specific_time_input').css('display', 'none');
        }

    });

    
</script>

<script language="javascript" type="text/javascript">

</script>
<!-- /////  for multiple delete  -->
<script type="text/javascript">
    $('#allMessage').click(function() { //iterate all listed checkbox items    

        var numberOfChecked = $('input:checkbox[name=group_id]:checked').length;
        if (numberOfChecked == 1) {
            $('.specificTimeDivision').html('<div class="row"><div class="col-sm-6"><label>Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-control styled" name="specific_time_check" value="1" id="changespecifictime" ></label><div id="specific_time_input" style="display:none;" ><div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" readonly name="specific_time" placeholder="Specific Time"  >        <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>');

            reassignCheckboxAnTimePicker();


        } else {
            $('.specificTimeDivision').html('');

        }

        // insert group id
        // var group_id_new = [];
        var group_id_new = '';
        var all_checked_boxes = $('input:checkbox[name=group_id]:checked');
        for (let i = 0; i < all_checked_boxes.length; i++) {
            var a_checked_box_val = all_checked_boxes[i].getAttribute('data-realvalue');
            // group_id_new.push($(this).val());
            if (i == 0) {
                group_id_new = a_checked_box_val;
            } else {
                group_id_new += ',' + a_checked_box_val;
            }
        }

        $("#group_id").val(group_id_new);
        $("#group_id_new").val(group_id_new);

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


    //  for multiple edit assign job
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
                        $('#' + route_select_id).append('<option value="' + value.route_id + '" ' + selected + ' >' + value.route_name + '</option>');
                    });
                }
            });
        }
    }

    // $('.clockpicker').clockpicker();
    hljs.configure({
        tabReplace: '    '
    });
    hljs.initHighlightingOnLoad();
</script>
<!-- /////  -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/highlight.min.js"></script>
