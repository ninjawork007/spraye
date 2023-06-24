
<style>

   .togglebutton{
      font-size:13px;
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
   height: 500px! important;
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
   padding-left:5px;
   }
   .tmpspan {
   float: left;
   margin: 8px 15px 8px 0;
   }
   table {
    border-collapse: inherit;
  }
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
	@media only screen and (min-width: 1240px) {
		 .toolbar {
         	width: 70%!important; 
		}
	}
   @media only screen and (max-width: 1240px) {
      #multiple-delete-id {
         margin: 0;
      }
      .unassigned-services-element {
         float: unset !important;
         margin-top: 20px;
      }
      .toolbar {
         width: 90%!important; /* toolbar (filters) */
         padding-bottom: 20px;
         /* margin-left: -100px; */
         display: block;
         float: left !important;
      }
      #unassigntbl_length { /* show: */
         /* float: left; */
         display: block;
      }
      #unassigntbl_filter > label { /* search box */
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
      #unassigntbl_length { /* show: */
         float: left !important;
      }
      #unassigntbl_filter > label { /* search box */
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
   width :150px !important;
   }
   tfoot {
   display: table-header-group;
   }
   td.fc-day.fc-past {
   background-color: #EEEEEE;
   }
   .label-row {
   margin-bottom:6px;
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
   .asap_row {
       background: #FBE9E7 !important;
       border: 1px solid #FF5722;
   }
	tr.row_in_hold td, tr.row_in_hold td a {	
	color: #8080804f !important;	
	}

</style>


<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/responsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/bootstrap-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/clock/css/github.min.css">
<div class="content">
   <div class="">
      <div class="mymessage"></div>
      <b><?php if ($this->session->flashdata()): echo $this->session->flashdata('message');
         endif
         ?></b>
      <div id="loading" >
         <img id="loading-image" src="<?= base_url() ?>assets/loader.gif"  /> <!-- Loading Image -->
      </div>

      <div class="panel-heading"style="padding-left: 0px;">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin')?>" id="save" class="btn btn-success"><i class=" icon-arrow-left7"> </i> Back to Dashboard</a>

                             <a href="<?= base_url('admin/manageJobs')?>" id="save" class="btn btn-primary"></i>Manage Scheduled Services</a>

                             <button disabled="disabled" id="multiple-delete-id" class="ml-5 btn btn-danger unassigned-services-element">Delete Services</button>


                             <button id="multiple-restore-id" disabled="disabled" class=" hidden btn btn-primary archived-services-element">Restore Services</button>
                             <div class="pull-right">
                                 <label class="togglebutton">
                                 Map View&nbsp;<input id="change-view-type" type="checkbox" onclick="window.location='<?= base_url('admin/assignJobsMap') ?>'" class="switchery-primary" checked = "">
                                 Table View
                                 </label>
                    </div>
                     </div>                             
                        </div>
                   </h5>
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
                                            <div class="row">
                                                <div class="col-md-3" style="text-align:right;">
                                                    <label for="appllicationCost">Application Cost</label>
                                                </div>
                                                <div class="col-md-3"style="text-align:right;">
                                                    <input placeholder="" id="totalApplicationCost" type="text"  size="15">
                                                </div>
                                            </div>
                                        
                                    </div>
                                    <button type="submit" disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage" style="margin-left:15px;">
                                        Assign Technician</button>
                                </div>
                            </div>
                            
                        </div>


<!--                  <div class="row" style="display: none;">-->
<!---->
<!--                     <div class="col-md-3">-->
<!---->
<!--                        <div class="multi-select-full col-md-12" id="service_ids_filter_parent" style="padding-left: 4px; margin-top: 10px; margin-bottom: 10px;">-->
<!--                           <label for="service_ids_filter">Filter Services-->
<!--                               <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="Choose the service(s) below you would like to schedule."><i class=" icon-info22 tooltip-icon"></i></span>-->
<!--                           </label>-->
<!--                           <select class="multiselect-select-all-filtering form-control" name="services_multi_filter[]" id="services_multi_filter" multiple="multiple">-->
<!--                              --><?php //foreach ($service_list as $value): ?>
<!---->
<!--                                 <option value="--><?php //= $value['job_name'] ?><!--"> --><?php //= $value['job_name'] ?><!-- </option>-->
<!---->
<!--                              --><?php //endforeach ?>
<!--                           </select>-->
<!--                        </div>-->
<!---->
<!--                     </div>-->
<!---->
<!--                      <div class="col-md-3">-->
<!---->
<!--                          <div class="multi-select-full col-md-12" id="service_ids_filter_parent" style="padding-left: 4px; margin-top: 10px; margin-bottom: 10px;">-->
<!--                              <label for="programs_service_ids_filter">Filter Properties by Outstanding Services (require all)-->
<!--                                  <span data-popup="tooltip-custom" title="" data-placement="right" data-original-title="By choosing any of the services below, Spraye will show only properties that have all of the chosen services outstanding in their account."><i class=" icon-info22 tooltip-icon"></i></span>-->
<!--                              </label>-->
<!--                              <select class="multiselect-select-all-filtering form-control" name="programs_service_filter[]" id="programs_service_filter" multiple="multiple">-->
<!--                                  --><?php //foreach ($service_list as $value): ?>
<!---->
<!--                                      <option value="--><?php //= $value['job_name'] ?><!--"> --><?php //= $value['job_name'] ?><!-- </option>-->
<!---->
<!--                                  --><?php //endforeach ?>
<!--                              </select>-->
<!--                          </div>-->
<!---->
<!--                      </div>-->
<!---->
<!---->
<!--                      <div class="col-md-3">-->
<!---->
<!--                          <div class="multi-select-full col-md-12" id="service_ids_filter_parent" style="padding-left: 4px; margin-top: 10px; margin-bottom: 10px;">-->
<!--                              <label for="programs_service_ids_filter">Service Area</label>-->
<!--                              <select class="multiselect-select-all-filtering form-control" name="service_area_filter[]" id="service_area_filter" multiple="multiple">-->
<!--                                  --><?php //foreach ($service_area_list as $value): ?>
<!---->
<!--                                      <option value="--><?php //= $value->category_area_name ?><!--"> --><?php //= $value->category_area_name ?><!-- </option>-->
<!---->
<!--                                  --><?php //endforeach ?>
<!--                              </select>-->
<!--                          </div>-->
<!---->
<!--                      </div>-->
<!---->
<!--                  </div>-->

                  <div class="panel-body"  style="padding: 20px 0px;">

                     <div  class="table-responsive table-spraye dash-tbl" style="height: unset;">
                        <table  class="table" id="unassigntbl" >
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
                                 <th>Last Service Type Date</th>
                                 <th>Service Added Date</th>
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
                                 <th>Hold Until Date</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                 <td></td>
                                 <td id="priority_filter"></td>
                                 <td id="service_name_filter"></td>
                                 <td id="notify_filter"></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td id="service_due_filter" style="width:300px;"></td>
                                 <td></td>
                                 <td id="property_type_filter"></td>
								 <td></td>
                                 <td id="service_area_filter"></td>
                                 <td></td>
                                 <td></td>
								 <td id="tag_filter"></td>
								 <td></td>
								 <td></td>
                                 <td></td>
                                 <td></td>
                              </tr>
                           </tfoot>

                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
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
                    <div class="row">
                        <div class="col-md-6" style="display: none">
                        </div>
                        <div class="col-md-6" style="float: right">
                            <div class="col-md-12" id="update_filter" style="padding-left: 4px; margin-top: 10px; margin-bottom: 10px;">
                                <button id="update_filter_btn" class="btn btn-success" style="display:block; width: 295px">Update</button>
                            </div>
                        </div>
                    </div>
                    <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
                    <div  id="update-map-div" class= "col-md-12" style="padding-top:10px">

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
         <form action="<?= base_url('admin/tecnicianJobAssign') ?>" name= "tecnicianjobassign" method="post" >
            <div class="modal-body">
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6 col-md-6">
                        <label>Select Technician</label>
                        <div class="multi-select-full">
                           <select class="form-control" name="technician_id" id="technician_id" >
                              <option value="" >Select Any Technician</option>

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
                     <div class="col-sm-6 col-md-6" id="assigModalDate" >
                        <label>Select Date</label>
                        <input type="date"  name="job_assign_date" class="form-control  pickadate" id="jobAssignDate" placeholder="MM-DD-YYYY">
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-7 col-md-7">
                        <div class="row label-row" >
                           <div class="col-sm-12 col-md-12">
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview"  class="primary-assign styled" checked="checked" value="1">
                              Existing route
                              </label>
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview" class="primary-assign styled" value="2" id="create-new-route" >
                              Create a new route
                              </label>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-12 col-md-12">
                              <select name="route_select" class="form-control" id="route_select" >
                              </select>
                              <input type="text" name="route_input" class="form-control" placeholder="Route Name" id="route_input" style="display: none;" >
                              <div class="route_error" >
                              </div>
                           </div>
                        </div>
                     </div>
               </div>
	           <div class="form-group">
                  <div class="row">
                     <div class="col-sm-12 col-md-12">
                        <label>Route Notes</label>
                        <textarea name="job_assign_notes" class="form-control" rows="3" placeholder="Leave a note for the technician"></textarea>
                        <div class="route_error" >
                     </div>
                  </div>
               </div>
               <div class="specificTimeDivision form-group">
               </div>
               <input type="hidden" name="group_id" id="group_id" >
               <input type="hidden" name="group_id_new" id="group_id_new">
               <div class="modal-footer">
                   <button type="button" class="btn btn-info" id="mileage" style="display: none;">Get mileage & drive time</button>
                  <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Save</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- /primary modal -->
<!-- Primary modal -->
<div id="modal_mileage" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title text-light">Mileage Information</h6>
                <button type="button" class="close text-light modal-mileage-dismiss">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mileageInfo">Mileage</label>
                            <p id="mileageInfo" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="driveTimeInfo">Drive Time</label>
                            <p id="driveTimeInfo" class="form-control-static"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-mileage-dismiss" >Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->
<!---  Add Filter Criteria template --->

<!-------------------------------------------->
<!--begin edit assign job  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<script>
   let serviceList = <?= json_encode($service_list) ?>;
</script>

<script language="javascript" type="text/javascript">
   $(".modal-mileage-dismiss").click(function() {
       $("#modal_mileage").modal('hide');
   })
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

   var costTotal = 0;
   $('.myCheckBox').each(function() { //iterate all listed checkbox items
       this.checked = status;
       if ($(this).is(':checked')) {
            let cost = $(this).data('cost');
            if (cost)
                costTotal = costTotal + parseFloat(cost);
        }
   });
   $('#totalApplicationCost').val(costTotal.toFixed(2));

	var sqftTotal = 0;	
	$('.myCheckBox').each(function(){ //iterate all listed checkbox items	
		var has_customer_in_hold=$(this).hasClass("customer_in_hold");	
		if(!has_customer_in_hold){	
			this.checked = status; //change ".checkbox" checked status	
		}	
		if ($(this).is(':checked')) {	
			// console.log( $(this).parent().parent().find('td').eq(5).html() );	
			sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());	
		}	
	});

	   $('#totalSqFt').val(sqftTotal);

      let applicationSqft = 0;
      let tmpAddressArray = [];
      $('#unassigntbl tbody input:checked').each(function() {
         let currentAddress = $(this).parent().parent().find('td').eq(12).text();
         if(!tmpAddressArray.includes(currentAddress)) {
            tmpAddressArray.push(currentAddress);
            applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
         }
         console.log(applicationSqft);
         console.log(tmpAddressArray);
      });
      $('#applicationSqFt').val(applicationSqft);

   });

</script>

<script type="text/javascript">
   $(document).on("change","table .myCheckBox", function() {
      if($(".table .myCheckBox").filter(':checked') .length < 1) {
         $('#allMessage').prop('disabled', true);
		 $('.myCheckBox customer_in_hold').prop('disabled', true);	
         $('#multiple-delete-id,#multiple-restore-id').prop('disabled', true);
      } else {
         $('#allMessage').prop('disabled', false);
         $('#multiple-delete-id,#multiple-restore-id').prop('disabled', false);
      }
       var sqftTotal = 0;
       $('#unassigntbl tbody input:checked').each(function() {
           sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
       });
       $('#totalSqFt').val(sqftTotal);
       var costTotal = 0;
       $('#unassigntbl tbody input:checked').each(function() { //iterate all listed checkbox items
           if ($(this).is(':checked')) {
               let cost = $(this).data('cost');
               if (cost)
                   costTotal = costTotal + parseFloat(cost);
           }
       });
       $('#totalApplicationCost').val(costTotal.toFixed(2));
       let applicationSqft = 0;
       let tmpAddressArray = [];
       $('#unassigntbl tbody input:checked').each(function() {
           let currentAddress = $(this).parent().parent().find('td').eq(12).text();
           if(!tmpAddressArray.includes(currentAddress)) {
               tmpAddressArray.push(currentAddress);
               applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
           }
           console.log(applicationSqft);
           console.log(tmpAddressArray);
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

</script>

<script type="text/javascript">
   $(document).on("click", "#multiple-delete-id,#multiple-restore-id", function(e) {
      var group_id = [];
      var button_id = this.id;
      var url = "";

    //   $("input:checkbox[name=group_id]:checked").each(function(){
    //      group_id.push($(this).val());
    //   });

    var all_checked_boxes = $('input:checkbox[name=group_id]:checked');
        for (let i = 0; i < all_checked_boxes.length; i++) {
            var a_checked_box_val = all_checked_boxes[i].getAttribute('data-realvalue');
            group_id.push(a_checked_box_val);
        }
    
      var post_data =  {};
      var success_message = "";
      post_data.group_id = group_id;
      if(button_id == "multiple-delete-id") {
         post_data.action = 'delete';
         success_message = "Deleted Successfully";
      } else {
         post_data.action = 'restore';
         success_message = "Restored Successfully";
      }
      console.log(post_data);
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
               $("#loading").css("display","block");
               $.ajax({
               type: "POST",
               url: "<?= base_url('admin/deleteRestoreMultiUnassignedJobs') ?>",
               data: post_data,
               dataType: 'json'
            }).done(function(data){
               $("#loading").css("display","none");
               if (data.status==200) {
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
   $(document).on("click",".confirm_delete_unassign_job,.confirm_restore_unassign_job", function (e) {
      e.preventDefault();
      var action = "";
      var success_message = "";
      if($(this).hasClass('confirm_delete_unassign_job')) {
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
                   $("#loading").css("display","block");
                   $.ajax({
                    type: "POST",
                    url: "<?= base_url('admin/deleteRestoreUnassignedJob') ?>",
                    data: {group_id : group_id, action : action },
                    dataType: 'json'
                 }).done(function(data){

                     $("#loading").css("display","none");

                    if (data.status==200) {

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
</script>

<style>
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
</style>

<script>


$(document).ready(function() {

    $( "#mileage" ).click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        let route_id = $("#route_select").val();
        let strLocations = $("#route_select option").filter(":selected").data('row-locations');
        let locations = [];
        if (strLocations) {
            let arrLocations = strLocations.split(';');

            for (i = 0; i < arrLocations.length; i++)
            {
                if (arrLocations[i] !== '')
                {
                    let address = arrLocations[i].split(':');

                    locations.push({
                        property_address: address[0],
                        property_latitude: address[1],
                        property_longitude: address[2]
                    });
                }
            }
        }

        $('#unassigntbl tbody input:checked').each(function() {
            let address = $(this).data('address').split(':');
            locations.push({
                property_address: address[0],
                property_latitude: address[1],
                property_longitude: address[2]
            });
        });

        let technician_id = $("#technician_id").val();
        Swal.fire({
            title: 'Please Wait !',
            html: 'Getting mileage information for you...',// add html attribute if you want or remove
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        });
        $.ajax({
            type: "POST",
            url: "<?= base_url('admin/getMileageAndDriveTimeForRoute') ?>",
            data: {technician_id : technician_id , locations :  JSON.stringify(locations) },
            dataType : "json",
        }).done(function(data){

            if (data.length===0) {
                $('#'+route_select_id).append('<option value="">No route found</option>');
            } else {
                post_BasicOptimizeStops(data);
            }
            // Swal.close();
        });
    });

    // post json data and get a json response
    function postit(url, options) {
        // extend options
        var poptions = jQuery.extend({}, {
            url: url,
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status === 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                $('#postProduct').html(msg);
            },

        }, options);

        // send it along
        return $.ajax(poptions);
    }

    function post_BasicOptimizeStops(request) {
        var resturl = 'https://optimizer3.routesavvy.com/RSAPI.svc/';
        postit(resturl + 'POSTOptimize', {
            data: JSON.stringify(request),
            success: function(data) {
                let mileage = kmToMiles(data.Route.DriveDistance).toFixed(2);
                let driveTime =  formatTime(data.Route.DriveTime);
                $('#mileageInfo').text(mileage + ' miles');
                $('#driveTimeInfo').text(driveTime);
                $('#modal_mileage').modal('show');
                console.log(data)
                var resp = JSON.stringify(data, null, '\t');
                console.log(resp);
                Swal.close();
            },
            error: function(err) {
                Swal.close();
                $('#loading').css('display', 'none');
                $('#result').text(JSON.stringify(err, null, '\t'));
            }
        });
    }

    function kmToMiles(km) {
        return km / 1.60934;
    }

    function formatTime(seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds % 3600) / 60);
        var remainingSeconds = seconds % 60;

        var formattedTime = hours.toString().padStart(2, '0') + ':' +
            minutes.toString().padStart(2, '0') + ':' +
            remainingSeconds.toString().padStart(2, '0');

        return formattedTime;
    }

    $(".service-due-filter").find(".btn-group").css("width", "100%");
    //$("#loading").css("display","block");
      // Setup - add a text input to each footer cell
   //$('#unassigntbl tfoot td').each( function () {
   //    var title = $(this).text();
   //    if (title=='PRIORITY' || title=='SERVICE NAME' || title=='PROPERTY TYPE' ) {
   //      $(this).html( '<input type="text" class="form-control dtatableInput" placeholder="'+title+'" />' );
   //    } else if(title=='SERVICE DUE' ){ //Adding select option for service due filter
   //        let html =
   //            "<select name=\"services_statuses_filter[]\" id=\"service_statuses_filter_filter\" multiple style=\"width: 100%;\" class='form-control'><option value=\"0\" class=\"default-option\" style=\"width: 100%;\">-- DUE</option><option value=\"1\">Due</option><option value=\"2\">Overdue</option><option value=\"3\">Not Due</option></select>";
   //        $(this).html(html);
   //      // $(this).html( '<select class="multiselect-select-all-filtering form-control dtatableInput" name="statuses_multi_filter[]" multiple="multiple" placeholder="'+title+'" ><option value="0" class="default-option">-- DUE</option><option value="1">Due</option><option    value="2">Overdue</option><option value="3">Not Due</option></select>' );
   //    } else if(title=='NOTIFY CUSTOMER' ){ //Adding select option for service due filter
   //        $(this).html( '<select class="form-control dtatableInput" id="teste" placeholder="'+title+'" ><option value="0" class="default-option">-- NOTIFY</option><option value="1">CALL AHEAD</option><option value="2">TEXT ETA</option><option value="3">PRE-NOTIFIED</option></select>' );
   //    } else /*if(title=='SERVICE AREA' ){ //Adding select option for service due filter
   //        var serviceArea = '<?php //echo $filter_service_area_list; ?>//';
   //        $(this).html(serviceArea);
   //    } else*/ if(title=='TAG' ){ //Adding select option for service due filter
	//		var filterTags = '<?php //echo $filter_tags; ?>//';
	//		$(this).html(filterTags);
   //    } else {
   //      $(this).addClass('noSpacingInput');
   //    }
   //} );
    $('#service_statuses_filter_filter').select2({
        allowClear: true,
        placeholder: "-- DUE",
    });
   // DataTable
   var table =  $('#unassigntbl').DataTable({
       "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
       "processing": true,
		   "serverSide": true,
		   "paging":true,
		   "pageLength":<?= $this ->session->userdata('compny_details')->default_display_length ?>,
		   "order":[[1,"asc"]],
           "deferLoading": 0,
		   "ajax":{
		     "url": "<?= base_url('admin/ajaxGetRoutingFORTABLE/')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
		   },
		   "deferRender":false,
        	"columnDefs": [
				{"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}},
			],
		   "select":"multi",
           "searchDelay": 2000,
		   "columns": [
            {"data": "checkbox", "checkboxes":true, "stateSave":true, "searchable":false, "orderable":false},
            {"data": "priority", "name":"Priority", "orderable": true, "searchable": true },
            {"data": "job_name", "name":"Service Name", "searchable":true, "orderable": true },
            {"data": "pre_service_notification", "name":"Notify Customer", "orderable": true, "searchable": true },
            {"data": "customer_name", "name":"Customer Name", "searchable":true, "orderable": true },
            {"data": "property_name", "name":"Property Name", "searchable":true, "orderable": true },
            {"data": "square_feet", "name":"Square Feet", "orderable": true },
            {"data": "last_service_date", "name":"Last Service Date", "orderable": true },
            {"data": "last_program_service_date", "name":"Last Program Service Date", "orderable": true },
            {"data": "completed_date_last_service_by_type", "name":"Last Service Type Date", "orderable": true },
            {"data": "property_program_date", "name":"Service Added Date", "orderable": true },
            {"data": "service_due", "name":"Service Due", "searchable":true, "orderable": true },
            {"data": "address", "name":"Address", "searchable":true, "orderable": true },
            {"data": "property_type", "name":"Property Type", "orderable": true },
            {"data": "property_notes", "name":"Property Info", "orderable": true },
            {"data": "category_area_name", "name":"Service Area", "orderable": true },
            {"data": "program", "name":"Program", "orderable": true },
            {"data": "reschedule_message", "name":"Note", "orderable": true},
			{"data": "tags", "name":"Tags", "orderable": true},
			{"data": "asap_reason", "name":"Asap Reason", "orderable": true},
            {"data": "available_days", "name":"Available Days", "orderable": false},
            {"data": "hold_until_date", "name":"Hold Until Date", "orderable": false},
            {"data": "action", "name":"Action", "orderable": false},
            {"data": "program_services", "name":"Tags", "orderable": false, "visible": false},
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' },
              emptyTable: "To see unassigned services use a filter or sort by a column."
          },
         dom: 'Bl<"toolbar">frtip',
           init: function(){
               // $("#loading").css("display","none");
           },
         initComplete: function(oSettings, json){
             //$("#loading").css("display","none");
            $("div.toolbar")
               .html('');
            // var r = $('#unassigntbl tfoot td');
            // $("div.toolbar")
            //       .append('<span class="tmpspan" >Filter: </span>');
            // $("div.toolbar")
            //       .append(r);

            // $("#unassigntbl_filter label").after('<button disabled="disabled" id="multiple-delete-id" class="ml-5 btn btn-danger unassigned-services-element">Delete Services</button>');


            // CALCULATE total square feet
            // $('.myCheckBox').change(function(){
            //
            //       var sqftTotal = 0;
            //       $('#unassigntbl tbody input:checked').each(function() {
            //          sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
            //       });
            //       $('#totalSqFt').val(sqftTotal);
            //
            //       let applicationSqft = 0;
            //       let tmpAddressArray = [];
            //       $('#unassigntbl tbody input:checked').each(function() {
            //          let currentAddress = $(this).parent().parent().find('td').eq(10).text();
            //          if(!tmpAddressArray.includes(currentAddress)) {
            //             tmpAddressArray.push(currentAddress);
            //             applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
            //          }
            //          console.log(applicationSqft);
            //          console.log(tmpAddressArray);
            //       });
            //       $('#applicationSqFt').val(applicationSqft);
            //
            //       //uncheck "select all", if one of the listed checkbox item is unchecked
            //       if(this.checked == false){ //if this item is unchecked
            //             $("#select_all")[0].checked = false; //change "select all" checked status to false
            //       }
            //
            //       //check "select all" if all checkbox items are checked
            //       if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
            //             $("#select_all")[0].checked = true; //change "select all" checked status to true
            //       }
            // });

            // CALCULATE application square feet

            // if (table && table !== undefined)
            // {
            //     // FIRE EVERYTIME AFTER TABLE HAS RENDERED
            //     table.on( 'draw', function () {
            //         // $("#loading").css("display","none");
            //         // BLUE ROWS for rescheduled on ajax table refresh
            //         $('.myCheckBox').each(function() {
            //             var row_job_mode = $(this).data('row-job-mode');
            //             if (row_job_mode == 2) {
            //                 $(this).parent().parent().addClass('rescheduled_row');
            //             }
            //         });
            //
            //         // CALCULATE total square feet on ajax table refresh
            //         $('.myCheckBox').change(function(){
            //
            //             var sqftTotal = 0;
            //             $('#unassigntbl tbody input:checked').each(function() {
            //                 sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
            //             });
            //             $('#totalSqFt').val(sqftTotal);
            //
            //             let applicationSqft = 0;
            //             let tmpAddressArray = [];
            //             $('#unassigntbl tbody input:checked').each(function() {
            //                 let currentAddress = $(this).parent().parent().find('td').eq(10).text();
            //                 if(!tmpAddressArray.includes(currentAddress)) {
            //                     tmpAddressArray.push(currentAddress);
            //                     applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
            //                 }
            //                 console.log(applicationSqft);
            //                 console.log(tmpAddressArray);
            //             });
            //             $('#applicationSqFt').val(applicationSqft);
            //
            //             //uncheck "select all", if one of the listed checkbox item is unchecked
            //             if(this.checked == false){ //if this item is unchecked
            //                 $("#select_all")[0].checked = false; //change "select all" checked status to false
            //             }
            //
            //             //check "select all" if all checkbox items are checked
            //             if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){
            //                 $("#select_all")[0].checked = true; //change "select all" checked status to true
            //             }
            //         });
            //         //after search
            //         $('.customer_in_hold').each(function() {
            //             var _col=$(this).parent();
            //             var _row=$(_col).parent();
            //             $(_row).addClass('row_in_hold');
            //         });
            //     } );
            // }


			 //on draw
				$('.customer_in_hold').each(function() {
					var _col=$(this).parent();
					var _row=$(_col).parent();
					$(_row).addClass('row_in_hold');
			   });
         },
          buttons:[
            {
               extend: 'colvis',
               text: '<i class="icon-grid3"></i> <span class="caret"></span>',
               className: 'btn bg-indigo-400 btn-icon',
               // columns: [1,2,3,4,5,6,7],  <<--- This was commented out in merge code
                },
            ],
	   }
       );
    // FIRE EVERYTIME AFTER TABLE HAS RENDERED
    table.on( 'draw', function () {
        // $("#loading").css("display","none");
        // BLUE ROWS for rescheduled on ajax table refresh
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

        // CALCULATE total square feet on ajax table refresh
        $('.myCheckBox').change(function(){

            var sqftTotal = 0;
            $('#unassigntbl tbody input:checked').each(function() {
                sqftTotal = sqftTotal + parseInt($(this).parent().parent().find('td').eq(6).html());
            });
            $('#totalSqFt').val(sqftTotal);

            var costTotal = 0;

            $('#unassigntbl tbody input:checked').each(function() { //iterate all listed checkbox items
                if ($(this).is(':checked')) {
                    let cost = $(this).data('cost');
                    if (cost)
                        costTotal = costTotal + parseFloat(cost);
                }
            });
            $('#totalApplicationCost').val(costTotal.toFixed(2));

            let applicationSqft = 0;
            let tmpAddressArray = [];
            $('#unassigntbl tbody input:checked').each(function() {
                let currentAddress = $(this).parent().parent().find('td').eq(12).text();
                if(!tmpAddressArray.includes(currentAddress)) {
                    tmpAddressArray.push(currentAddress);
                    applicationSqft += parseInt($(this).parent().parent().find('td').eq(6).html());
                }
                console.log(applicationSqft);
                console.log(tmpAddressArray);
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
    } );
    $('#filter-criteria-id').remove();
    $('#multiple-delete-id').remove();

    $(".dataTables_filter")
        .append('<button id="filter-criteria-id" class="btn btn-primary " style="margin-left: 10px;">Filters</button>');
    $(".dataTables_filter")
        .append('<button disabled="disabled" id="multiple-delete-id" class="ml-5 btn btn-danger unassigned-services-element">Delete Services</button>');

    $("#filter-criteria-id").unbind('click');
    $("#filter-criteria-id").click(function() {
        $('#modal_add_filters').modal('show');
    });
    $("#update_filter_btn").click(function () {

        // PRIORITY
        var filter_input_val = $("#pfilter").val();
        table.columns( 1 ).search( filter_input_val );

        // SERVICE NAME
        var service_name = $("#snfilter").val();
        if (service_name != '')
            table.columns( 2 ).search( service_name );

        // NOTIIFY CUSTOMER
        var filter_input_val = $("#ncfilter").val();
        table.columns( 3 ).search( filter_input_val );


        // SERVICE DUE
        var filter_input_val = $("#sdfilter").val();
        table.columns( 11 ).search( filter_input_val );

        // PROPERTY TYPE
        var filter_input_val = $("#ptfilter").val();
        table.columns( 13 ).search( filter_input_val );

        // TAG FILTER
        var filter_input_val = $(".tags-filter").find('select').val();
        table.columns( 18 ).search( filter_input_val );

        $('#applicationSqFt').val('');
        $('#totalSqFt').val('');
        $('#totalApplicationCost').val('');

        let val = $("#services_multi_filter").val();
        table.columns( 2 ).search( val );
        val = $("#service-area-filter").val();
        table.columns( 15 ).search( val );
        let multi_service_val = $("#program_services_multi_filter").val();
        table.columns( 23 ).search( multi_service_val.toString() );

        let service_statuses_filter_filter = $("#sdfilter").val();
        table.columns( 11 ).search( service_statuses_filter_filter.toString() );

        val = $("#asap-reason").val();
        table.columns( 19 ).search( val );
        // Available Days filter
        let available_days_val = $("#available_days_filter").val();
        table.columns( 20 ).search( available_days_val );


        table.draw();
        // Service Name

        // Connect the filter inputs to filter data
        // $('#priority_filter').on('input', function() { // PRIORITY
        //       var filter_input_val = this.querySelector('input').value;
        //       table.columns( 1 ).search( filter_input_val ).draw();
        // });



        // Connect the filter inputs to filter data
        // $('#service_name_filter').on('input', function() { // SERVICE NAME
        //       var filter_input_val = this.querySelector('input').value;
        //       table.columns( 2 ).search( filter_input_val ).draw();
        // });

        // Connect the filter inputs to filter data
        // $('#notify_filter').on('input', function() { // NOTIIFY CUSTOMER
        //       var filter_input_val = this.querySelector('select').value;
        //       table.columns( 3 ).search( filter_input_val ).draw();
        // });
        // Connect the filter inputs to filter data
        // $('#service_due_filter').on('change', function() { // PROPERTY TYPE
        //       var filter_input_val = this.querySelector('select').value;
        //       table.columns( 11 ).search( filter_input_val ).draw();
        // });

        // Connect the filter inputs to filter data
        // $('#property_type_filter').on('input', function() { // PROPERTY TYPE
        //       var filter_input_val = this.querySelector('input').value;
        //       table.columns( 13 ).search( filter_input_val ).draw();
        // });

        // Connect the filter inputs to filter data
        // $('#service_area_filter').on('change', function() { // SERVICE AREA
        //       var filter_input_val = this.querySelector('select').value;
        //       table.columns( 13 ).search( filter_input_val ).draw();
        // });

        // Connect the filter inputs to filter data
        // $('#tag_filter').on('change', function() { // PROPERTY TYPE
        //       var filter_input_val = this.querySelector('select').value;
        //       table.columns( 18 ).search( filter_input_val ).draw();
        // });
        //			// Connect the filter inputs to filter data
        //             $('#tag_filter').on('input', function() { // TAG
        //                   var filter_input_val = this.querySelector('input').value;
        //                   table.columns(15).search(filter_input_val).draw();
        //             });

        // BLUE ROWS for rescheduled on page load
        $('.myCheckBox').each(function() {
            var row_job_mode = $(this).data('row-job-mode');
            if (row_job_mode == 2) {
                $(this).parent().parent().addClass('rescheduled_row');
            }
        });

        // Connect Multi-Select input to filter data
        // $('#services_multi_filter').on('change', function() { // Service Name
        //     $('#applicationSqFt').val('');
        //     $('#totalSqFt').val('');
        //     let val = $(this).val();
        //     table.columns( 2 ).search( val ).draw();
        //
        // });
        // // Connect Multi-Select input to filter data
        // $('#service_area_filter').on('change', function() { // Service Name
        //     $('#applicationSqFt').val('');
        //     $('#totalSqFt').val('');
        //     let val = $(this).val();
        //     table.columns( 15 ).search( val ).draw();
        //
        // });
        // // Connect Multi-Select input to filter data
        // $('#programs_service_filter').on('change', function() { // Service Name
        //     $('#applicationSqFt').val('');
        //     $('#totalSqFt').val('');
        //     let multi_service_val = $(this).val();
        //     table.columns( 20 ).search( multi_service_val.toString() ).draw();
        //
        // });
        $('#modal_add_filters').modal('hide');
    }) ;
   });

   $('input[name=changeview]').click(function () {
      $("#loading").css("display","block");
      var url = '';
      var sectionType = "";
      if($(this).prop("checked") == true){
         $('.archived-services-element').addClass('hidden');
         $('.unassigned-services-element').removeClass('hidden');
         url = "<?= base_url('admin/assignJobs?ajax-call=true') ?>";
         sectionType = "unassigned_services";
      }
      else if($(this).prop("checked") == false){
         $('.archived-services-element').removeClass('hidden');
         $('.unassigned-services-element').addClass('hidden');
         url = "<?= base_url('admin/archivedJobs') ?>";
         sectionType = "archived_services";
      }
      table.clear().draw();
      $.ajax({
         type: "GET",
         url: url,
         dataType: 'json'
      }).done(function(data){
         $("#loading").css("display","none");
         if (data.status==200) {
            console.log(data.records);
            if(data.records.length > 0) {
               $.each(data.records, function(i,datarecord){
                  var rowNode = [];
                  rowNode.push("<input  name='group_id' type='checkbox'  value='"+datarecord.customer_id+":"+datarecord.job_id+":"+datarecord.program_id+":"+datarecord.property_id+"' class='myCheckBox' />");
                  rowNode.push(datarecord.priority);
                  rowNode.push(datarecord.job_name);
                  rowNode.push(datarecord.first_name+' '+datarecord.last_name);
                  rowNode.push(datarecord.property_title);
                  rowNode.push(datarecord.job_completed_date);
                  rowNode.push(datarecord.property_address);
                  var property_type = "";
                  switch (datarecord.property_type) {
                     case 'Commercial':
                        property_type = 'Commercial';
                        break;
                     case 'Residential':
                        property_type = 'Residential';
                        break;
                     default:
                     property_type = 'Commercial';
                     break;
                  }
                  rowNode.push(property_notes);
                  rowNode.push(property_type);
                  rowNode.push(datarecord.category_area_name);
                  rowNode.push(datarecord.program_name);
                  rowNode.push(datarecord.reschedule_message);
                  if(sectionType == "unassigned_services") {
                     rowNode.push("<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='confirm_delete_unassign_job button-next' grd_ids='"+datarecord.customer_id+":"+datarecord.job_id+":"+datarecord.program_id+":"+datarecord.property_id+"'><i class='icon-trash position-center' style='color: #9a9797;'></i></a></li></ul>");
                  } else {
                     rowNode.push("<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='confirm_restore_unassign_job button-next' grd_ids='"+datarecord.customer_id+":"+datarecord.job_id+":"+datarecord.program_id+":"+datarecord.property_id+"'><i class='icon-undo position-center' style='color: #9a9797;'></i></a></li></ul>");
                  }
                  table.row.add(rowNode).draw();
               });
            } else {
               $('.dataTables_empty').html("You do not have any archived services.");
            }

         } else {
            swal({
               type: 'error',
               title: 'Oops...',
               text: 'Something went wrong!'
            })
         }
      });
   });






    $('.primary-assign').click(function () {

           if($(this).val() == 1){
             $('#route_input').css('display','none');
             $('#route_select').css('display','block');
           }

           else if($(this).val() == 2){
             $('#route_input').css('display','block');
             $('#route_select').css('display','none');
           }

   });


   $(document).on("click","#changespecifictime", function (e) {
        if($(this).prop("checked") == true){
               $('#specific_time_input').css('display','block');
            }
           else if($(this).prop("checked") == false){
             $('#specific_time_input').css('display','none');
           }

   });


</script>
<!-- /////  for multiple delete  -->
<script type="text/javascript">

   $('#allMessage').click(function(){ //iterate all listed checkbox items

      var numberOfChecked = $('input:checkbox[name=group_id]:checked').length;
      if (numberOfChecked==1) {
         $('.specificTimeDivision').html('<div class="row"><div class="col-sm-6"><label>Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-control styled" name="specific_time_check" value="1" id="changespecifictime" ></label><div id="specific_time_input" style="display:none;" ><div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" readonly name="specific_time" placeholder="Specific Time"  >        <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>');

         reassignCheckboxAnTimePicker();

      } else {
         $('.specificTimeDivision').html('');

      }

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
   $( "#technician_id" ).change(function() {
        technician_id = $(this).val();
      jobAssignDate =$('#jobAssignDate').val();
      route_select_id = 'route_select';
      routeMange(technician_id,jobAssignDate,route_select_id);
   });

   // for assign job
   $( "#create-new-route" ).click(function() {
       technician_id = $( "#technician_id").val();
       jobAssignDate =$('#jobAssignDate').val();
       route_select_id = 'route_select';
       routeMangeNewRoute(technician_id,jobAssignDate,route_select_id);
   });

     $( "#jobAssignDate" ).change(function() {

        $("#technician_id").trigger("change");
   });

   //  for multiple edit assign job
   function routeMange(technician_id, jobAssignDate, route_select_id, selected_id = '') {
       $('#' + route_select_id).html('');
       if (technician_id != '' && jobAssignDate != '') {
           Swal.fire({
               title: 'Please Wait !',
               html: "Checking technician's active routes...",// add html attribute if you want or remove
               allowOutsideClick: false,
               onBeforeOpen: () => {
                   Swal.showLoading()
               },
           });
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
                   $('#mileage').hide();
               } else {
                   $.each(data, function(index, value) {
                       if (value.route_id == selected_id) {
                           selected = 'selected';
                       } else {
                           selected = '';
                       }
                       let locations = '';
                       for(i = 0; i < value.locations.length; i++)
                       {
                           locations += value.locations[i].property_address+':'+value.locations[i].property_latitude+':'+value.locations[i].property_longitude+';';
                       }
                       $('#'+route_select_id).append('<option data-row-locations="'+locations+'" value="'+value.route_id+'" '+selected+' >'+value.route_name+'</option>');
                       $('#mileage').show();
                   });
               }
               Swal.close();
           });

           if($("#create-new-route").is(":checked"))
               $('#mileage').show();
       }
   }

   function routeMangeNewRoute(technician_id,jobAssignDate,route_select_id)
   {
       $('#' + route_select_id).html('');
       if (technician_id != '' && jobAssignDate != '') {
           $('#mileage').show();
       }
   }


</script>
<!-- /////  -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
   $('.clockpicker').clockpicker();
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/highlight.min.js"></script>
<script type="text/javascript">
   hljs.configure({tabReplace: '    '});
   hljs.initHighlightingOnLoad();
</script>
<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url("assets/admin")?>/assets/js/pages/datatables_extension_colvis.js"></script>

