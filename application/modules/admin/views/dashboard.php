
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
      <!-- <div class="panel-body">
         <h5 class="panel-title">Users Details</h5>
         </div>-->
      <div class="row">
         <div class="col-lg-9 col-md-9">
            <div class="col-md-12" style="padding: 0px;">
               <ul class="flex-container">
                  <li id="flx-div" class="flex-item full-wt" style="background-color: #F5A6A5;box-shadow: none;">
                     <h3 class="ser-head" style=" color:#fff">Unassigned Jobs</h3>
                     <p class="text-danger ser-num " style="font-size: 17.3035;">328 <i class="fas fa-exclamation-circle"></i></p>
                  </li>
                  <li id="flx-div2" class="flex-item full-wt ">
                     <h3 class="ser-head" style="">Scheduled</h3>
                     <p class=" ser-num text-warning" style="font-size: 17.3035;"><?= count($assign_data); ?><span class="text-muted"> This month</span></p>
                  </li>
                  <li id="flx-div3" class="flex-item full-wt ">
                     <h3 class="ser-head" style="">To be rescheduled </h3>
                     <p class="text-danger ser-num" style="font-size: 17.3035;"><?= $need_to_reschedule ?><span class="text-muted "> This month</span></p>
                  </li>
                  <li id="flx-div4" class="flex-item full-wt ">
                     <h3 class="ser-head" style="">Gross revenue</h3>
                     <p class="text-success ser-num" style="font-size: 17.3035;"><?= (!empty($result_revenue->cost)) ? '$ '.number_format($result_revenue->cost,2)    : 0 ?></p>
                  </li>
                  <li id="flx-div5" class="flex-item full-wt ">
                     <h3 class="ser-head" style="">Completed jobs</h3>
                     <p class="text-success ser-num" style="font-size: 17.3035;">112</p>
                  </li>
               </ul>
            </div>
            <div class="col-lg-12">
               <div class="panel panel-flat" style="margin-top:20px;">
                  <div style="background: #fafafa;padding-top: 10px;padding-bottom: 20px;color: #333;padding-left: 20px;padding-right:20px;">
                     <span class="text-semibold" style="font-size:15px;">Scheduled Jobs</span>
                     <div style="float: right;"> 
                        <label class="togglebutton">
                        Table view&nbsp;<input name="changeview" type="checkbox" class="switchery-primary" checked="checked">
                        Calendar View
                        </label>
                     </div>
                  </div>
                  <div class="panel-body">
                     <div class="calederview">
                        <div class="fullcalendar-basic"></div>
                     </div>
                     <div class="sheduletable" >
                        <div  class="table-responsive table-spraye">
                           <table  class="table" id="assigntbl">
                              <thead>
                                 <tr>
                                    <!-- <th>S. NO</th> -->                        
                                    <th><input type="checkbox"  id="select_all-delete" <?php if (empty($assign_data)) { echo 'disabled'; }  ?>    /></th>
                                    <th>TECHNICIAN NAME</th>
                                    <th>JOB NAME</th>
                                    <th>ASSIGN DATE</th>
                                    <th>CUSTOMER NAME</th>
                                    <th>PROPERTY NAME</th>
                                    <th>ADDRESS</th>
                                    <th>SERVICE AREA</th>
                                    <th>PROGRAM</th>
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
                                       <input type="checkbox" class="myCheckBoxDelete"  technician_job_assign_ids="<?= $value->technician_job_assign_id ?>" name="selectcheckbox"  >
                                    </td>
                                    <td><?= $value->user_first_name.' '.$value->user_last_name; ?></td>
                                    <td><?=$value->job_name; ?></td>
                                    <td><?=$value->job_assign_date; ?></td>
                                    <td><?=$value->first_name.' '.$value->last_name ?></td>
                                    <td><?= $value->property_title ?></td>
                                    <td><?= $value->property_address ?></td>
                                    <td><?= $value->category_area_name ?></td>
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
                                             echo "Default";
                                              break;
                                          
                                          }
                                          
                                          ?>
                                    </td>
                                    <td><a href="<?= base_url('admin/technicianMapView/').$value->technician_id.'/'.$value->job_assign_date  ?>" target="_blank"><button type="button" class="btn btn-success">Map View</button></a></td>
                                    <td>
                                       <ul style="list-style-type: none; padding-left: 0px;">
                                          <li style="display: inline; padding-right:10px;">
                                             <a  data-toggle="modal" data-target="#modal_edit_assign_job" onclick="editAssignJob(<?= $value->technician_job_assign_id ?>)" ><i class="icon-pencil   position-center" style="color: #9a9797;"></i></a>
                                          </li>
                                          <li style="display: inline; padding-right: 10px;">
                                             <a href="<?=base_url("admin/ScheduledJobDetete/").$value->technician_job_assign_id ?>" class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
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
            <div class="col-lg-12 col-md-12 col-sm-12">
               <div class="panel panel-flat" style="margin-top:20px;">
                  <div style="background: #fafafa;padding-top: 10px;padding-bottom: 20px;color: #333;padding-left: 20px;">
                     <span class="text-semibold" style="font-size:15px;">Unassigned Jobs</span>              
                     <div style="float: right;margin-right: 18px;">
                        <button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">
                        Assign Technician</button>                  
                     </div>
                  </div>
                  <div class="panel-body">
                     <div  class="table-responsive table-spraye dash-tbl">
                        <table  class="table" id="unassigntbl" >
                           <thead>
                              <tr>
                                 <th><input type="checkbox" id="select_all" /></th>
                                 <th>PRIORITY</th>
                                 <th>JOB NAME</th>
                                 <th>CUSTOMER NAME</th>
                                 <th>PROPERTY NAME</th>
                                 <th>ADDRESS</th>
                                 <th>PROPERTY TYPE</th>
                                 <th>SERVICE AREA</th>
                                 <th>PROGRAM</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                 <td></td>
                                 <td>PRIORITY</td>
                                 <td>JOB NAME</td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td>PROPERTY TYPE</td>
                                 <td>SERVICE AREA</td>
                                 <td></td>
                                 <td></td>
                              </tr>
                           </tfoot>
                           <tbody>
                              <?php
                                 foreach ($table_data as $value) {
                                 
                                 
                                 ?>
                              <tr>
                                 <td><input  name="group_id" type="checkbox"  value="<?=$value->customer_id.':'.$value->job_id.':'.$value->program_id.':'.$value->property_id ?>" class="myCheckBox" /></td>
                                 <td><?=$value->priority; ?></td>
                                 <td><?=$value->job_name ?></td>
                                 <td><?=$value->first_name.' '.$value->last_name ?></td>
                                 <td><?= $value->property_title ?></td>
                                 <td><?= $value->property_address ?></td>
                                 <td><?php
                                    switch ($value->property_type) {
                                      case 'Commercial':
                                      echo 'Commercial';  
                                        break;
                                      case 'Residential':
                                      echo 'Residential';   
                                        break;
                                      
                                      default:
                                        echo 'Commercial';  
                                      break;
                                    }
                                    
                                    $value->property_address
                                    ?></td>
                                 <td><?= $value->category_area_name ?></td>
                                 <td><?=$value->program_name ?></td>
                                 <td>
                                    <ul style="list-style-type: none; padding-left: 0px;">
                                       <li style="display: inline; padding-right: 10px;">
                                          <a  class="confirm_delete_unassign_job button-next" grd_ids="<?=$value->customer_id.':'.$value->job_id.':'.$value->program_id.':'.$value->property_id ?>"  ><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                       </li>
                                    </ul>
                                 </td>
                              </tr>
                              <?php     
                                 }
                                 
                                 ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3">
            <div class="col-lg-12 col-md-12 col-sm-12">

               <!-- Basic pie -->
               <!--   <div class="panel panel-flat" style="margin-top:10px;">
                  <div style="background: #c7dded;padding-top: 10px;padding-bottom: 10px;color: #2d669a;">
                     <center>Scheduled </center>
                  </div>
                  <div class="panel-body">
                     <div class="chart-container">
                        <center><span class="text-semibold" style="font-size:20px; font-weight: 800; color:#016699"><?= count($assign_data); ?></span></center>
                     </div>
                  </div>
                  </div> -->
               <!-- /basic pie -->
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
               <!-- Basic pie -->
               <!-- <div class="panel panel-flat" style="margin-top:20px;">
                  <div style="background: #c7dded;padding-top: 10px;padding-bottom: 10px;color: #2d669a;">
                     <center>Need to Reschedule</center>
                  </div>
                  <div class="panel-body">
                     <div class="chart-container">
                        <center><span class="text-semibold" style="font-size:20px; font-weight: 800; color:#016699"><?= $need_to_reschedule ?></span></center>
                     </div>
                  </div>
                  </div> -->
               <!-- /basic pie -->
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
               <!-- Basic pie -->
               <!--   <div class="panel panel-flat" style="margin-top:20px;">
                  <div style="background: #c7dded;padding-top: 10px;padding-bottom: 10px;color: #2d669a;">
                     <center>Gross Revenue</center>
                  </div>
                  <div class="panel-body">
                     <div class="chart-container">
                        <center><span class="text-semibold" style="font-size:20px; font-weight: 800; color:#016699"><?= (!empty($result_revenue->cost)) ? '$ '.number_format($result_revenue->cost,2)    : 0 ?></span></center>
                     </div>
                  </div>
                  </div> -->
               <!-- /basic pie -->
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
               <!-- Basic pie -->
               <div class="panel panel-flat">
                  <div style="padding-top: 10px;padding-bottom: 10px;color: #333;background: #fafafa;">
                     <center>Weather Forecast for Today </center>
                  </div>
                  <?php 
                     $address_array = explode(",", $company_details->company_address);
                     array_pop($address_array);
                     
                     ?>
                  <div class="panel-body" style="padding: 5px !important;background-color:rgb(31, 86, 124)">
                     <!-- //  widget code -->
                     <?php
                        if (!empty($widget_data)) { ?>
                     <div class="col-widget-3">
                        <div class="currentWeather">
                           <h2>
                              <strong><?php  echo implode(",", $address_array); ?></strong>
                              <span></span>
                           </h2>
                           <div class="row align-items-center">
                              <div class="col-lg-3">
                                 <div class="icon-c">
                                    <img src="<?php echo base_url('assets/weather/images/').$widget_data->currently->icon.'.png'; ?>" alt="" />
                                 </div>
                              </div>
                              <div class="col-lg-3">
                                 <div class="temp-desc">
                                    <h5><?php
                                       echo round($widget_data->currently->temperature);
                                       ?>°<br>
                                    </h5>
                                    <em>Wind: <?php echo round($widget_data->currently->windSpeed).' mph' ?>
                                    </em>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="temp-desc">
                                    <h6>
                                       <?php echo $widget_data->currently->summary;?>
                                    </h6>
                                    <em><?php echo $widget_data->daily->summary; ?></em>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="weatherforecast">
                           <ul>
                              <?php
                                 $rows = $widget_data->daily->data;
                                 foreach ($rows as $key => $value) {  
                                 ?>
                              <li class="day old-in">
                                 <div class="dayname">
                                    <span><strong>
                                    <?php 
                                       $date = $value->time;
                                       
                                       if ($key==0) {
                                        echo "Today";
                                       } else {
                                        echo date('D', $date);  
                                       } 
                                       
                                       ?>
                                    </strong></span>
                                 </div>
                                 <div class="sm-day-icon">
                                    <img src="<?php echo base_url('assets/weather/images/').$value->icon.'.png'; ?>" alt="" />
                                 </div>
                                 <div class="temps-day">
                                    <div class="temp-day a"><strong><?php echo round($value->temperatureHigh);  ?>°</strong></div>
                                    <div class="temp-m-day b"><strong><?php echo round($value->temperatureLow); ?>°</strong></div>
                                 </div>
                              </li>
                              <?php  }  ?>
                           </ul>
                        </div>
                     </div>
                     <?php  } else {   ?>
                     <div class="col-widget-3">
                        <div class="currentWeather">
                           <h2>
                              <strong><?php  echo 'There was problem to fetch weather information Please try after some time';  ?></strong>
                              <span></span>
                           </h2>
                        </div>
                     </div>
                     <?php } ?>
                     <!-- ///  widget code  -->
                  </div>
               </div>
               <!-- /basic pie -->
                   <div class="score-bd">
                             <h5 class="main-head">Technician Scoreboard</h5>
                             <div class="progress-tbl panel panel-default table-responsive">
                             <table class="table progress-table ">
                              <thead class="thead-dark">
                                <tr class="head-tr">
                                  <th scope="col">Name</th>
                                  <th scope="col" class="ft">Filter by date</th>
                                  <th scope="col" class=""></th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Dwight</td>
                                  <td>
                                  <div class="progress">
                                  <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                  </div>
                                 </td>
                                  <td class="text-muted ft-txt">80-sq.-ft-/-day</td>
                                </tr>
                                <tr>
                                  <td>Logan</td>
                                  <td><div class="progress">
                                  <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div></td>
                                 <td class="text-muted ft-txt">60-sq.-ft-/-day</td>
                                </tr>
                                <tr>
                                  <td>David</td>
                                  <td><div class="progress">
                                  <div class="progress-bar" role="progressbar" style="width: 98%" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"></div>
                                </div></td>
                                <td class="text-muted ft-txt">98-sq.-ft-/-day</td>
                                </tr>
                                <tr>
                                  <td>Brian</td>
                                  <td><div class="progress">
                                  <div class="progress-bar" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                </div></td>
                                 <td class="text-muted ft-txt">95-sq.-ft-/-day</td>
                                </tr>
                            </tbody>
                            </table>
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
            <h6 class="modal-title">Assign Job to Technician</h6>
         </div>
         <form action="<?= base_url('admin/tecnicianJobAssign') ?>" name= "tecnicianjobassign" method="post" >
            <div class="modal-body">
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6">
                        <label>Select Technician</label>
                        <div class="multi-select-full">
                           <select class="form-control" name="technician_id" id="technician_id" >
                              <option value="" >Select Any Technician</option>
                              <option value="<?= $this->session->userdata['user_id'] ?>" ><?= $this->session->userdata['user_first_name'].' '.$this->session->userdata['user_last_name'] ?></option>
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
                     <div class="col-sm-6" id="assigModalDate" >
                        <label>Select Date</label>
                        <input type="date" min="<?= date('Y-m-d'); ?>" name="job_assign_date" class="form-control" id="jobAssignDate" placeholder="Try me&hellip;">
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6">
                        <div class="row label-row" >
                           <div class="col-sm-12">
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview"  class="primary-assign styled" checked="checked" value="1">
                              Existing route
                              </label>
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview" class="primary-assign styled" value="2" >
                              Create a new route
                              </label>    
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-12">
                              <select name="route_select" class="form-control" id="route_select" >
                              </select>
                              <input type="text" name="route_input" class="form-control" placeholder="Route Name" id="route_input" style="display: none;" >
                              <div class="route_error" >
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <label>Job Notes</label>
                        <input type="text" name="job_assign_notes" placeholder="Job Assign Notes" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="specificTimeDivision form-group">
               </div>
               <input type="hidden" name="group_id" id="group_id" >
               <div class="modal-footer">
                  <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Save</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- /primary modal -->
<!--begin edit assign job  -->
<div id="modal_edit_assign_job" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Update Assign Job to Technician</h6>
         </div>
         <form action="<?= base_url('admin/editTecnicianJobAssign') ?>" name= "tecnicianjobassignedit" method="post" >
            <div class="modal-body">
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6">
                        <label>Select Technician</label>
                        <div class="multi-select-full">
                           <select class="form-control" name="technician_id" id="technician_id_edit">
                              <option value="" >Select Any Technician</option>
                              <option value="<?= $this->session->userdata['user_id'] ?>" ><?= $this->session->userdata['user_first_name'].' '.$this->session->userdata['user_last_name'] ?></option>
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
                        <input type="date" min="<?= date('Y-m-d'); ?>" name="job_assign_date" class="form-control" id="jobAssignDateEdit" placeholder="Try me&hellip;">
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6">
                        <div class="row label-row" >
                           <div class="col-sm-12">
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview"  class="primary-edit styled" checked="checked" value="1">
                              Existing route
                              </label>
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview" class="primary-edit styled" value="2" >
                              Create a new route
                              </label>    
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-12">
                              <select name="route_select" class="form-control" id="route_select_edit"   >
                              </select>
                              <input type="text" name="route_input" class="form-control" style="display: none;" id="route_input_edit" placeholder="Route Name" >
                              <div class="route_edit_error" >
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <label>Job Notes</label>
                        <input type="text" name="job_assign_notes" placeholder="Job Assign Notes" class="form-control" id="assign_notes_edit">
                     </div>
                  </div>
               </div>
               <div class="specificTimeDivisionEdit form-group">
               </div>
               <input type="hidden" name="technician_job_assign_id" id="technician_job_assign_id">
               <div class="modal-footer">
                  <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Save</button>
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
            <h6 class="modal-title">Assign Jobs to Technician</h6>
         </div>
         <form action="<?= base_url('admin/updateMultipleAssignJob') ?>" name= "tecnicianjobassignmultipleedit" method="post" >
            <div class="modal-body">
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6">
                        <label>Select Technician</label>
                        <div class="multi-select-full">
                           <select class="form-control"  name="technician_id" id="technician_id_edit_multiple" >
                              <option value="" >Select Any Technician</option>
                              <option value="<?= $this->session->userdata['user_id'] ?>" ><?= $this->session->userdata['user_first_name'].' '.$this->session->userdata['user_last_name'] ?></option>
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
                        <input type="date" min="<?= date('Y-m-d'); ?>"  name="job_assign_date" class="form-control" id="jobAssignDateEditMultiple" placeholder="Try me&hellip;">
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6">
                        <div class="row label-row" >
                           <div class="col-sm-12">
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview"  class="primary-edit-multiple styled" checked="checked" value="1">
                              Existing route
                              </label>
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview" class="primary-edit-multiple styled" value="2" >
                              Create a new route
                              </label>    
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-12">
                              <select name="route_select" class="form-control" id="route_select_edit_multiple" style=""  >
                              </select>
                              <input type="text" name="route_input" placeholder="Route Name" class="form-control" id="route_input_edit_multiple" style="display: none;"  >
                              <div class="route_edit_multiple_error" >
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <label>Job Notes</label>
                        <input type="text" name="job_assign_notes"  placeholder="Job Assign Notes" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="specificTimeDivisionEditMultiple form-group">
               </div>
               <input type="hidden" name="multiple_technician_job_assign_id" id="multiple_technician_job_assign_id">
               <div class="modal-footer">
                  <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Save</button>
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
            <h5 class="modal-title" style="float: left;">Scheduled Job Details</h5>
            <ul style="list-style-type: none; padding-left: 10px;float:left" id="modalactionbtn" >
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
         <form action="<?= base_url('admin/editTecnicianJobAssignCalender') ?>" name= "dropEventForm" method="post" >
            <div class="modal-body">
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-12">
                        <div class="row label-row" >
                           <div class="col-sm-12">
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview"  class="primary-edit-drop styled" checked="checked" value="1" id="primary-edit-drop-id" >
                              Existing route
                              </label>
                              <label class="radio-inline">
                              <input type="radio" name="changerouteview" class="primary-edit-drop styled" value="2" >
                              Create a new route
                              </label>    
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-12">
                              <select name="route_select" class="form-control" id="route_select_drop_edit" >
                              </select>
                              <input type="text" name="route_input" placeholder="Route Name" class="form-control" style="display: none;" id="route_input_drop_edit" >
                              <div class="route_drop_edit_error" >
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="specificTimeDivisionEditDrop form-group">
               </div>
               <input type="hidden" name="technician_job_assign_id" id="technician_job_assign_id_drop">
               <input type="hidden" name="technician_id" id="technician_id_drop">
               <input type="hidden" name="job_assign_date" id="job_assign_date_drop">
               <div class="modal-footer">
                  <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Save</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<!--end edit assign job  -->  
<script language="javascript" type="text/javascript">
   $("#select_all").change(function(){  //"select all" change 
     var status = this.checked; // "select all" checked status
    if (status) {
     $('#allMessage').prop('disabled', false);
   
    }
    else
    {
      $('#allMessage').prop('disabled', true);
      
    }
     $('.myCheckBox').each(function(){ //iterate all listed checkbox items
         this.checked = status; //change ".checkbox" checked status
   
     });
   });
   
   $('.myCheckBox').change(function(){ //".checkbox" change 
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
   var checkBoxes = $('table .myCheckBox');
   checkBoxes.change(function () {
      $('#allMessage').prop('disabled', checkBoxes.filter(':checked').length < 1);
   });
   checkBoxes.change();  
   
</script>
<script type="text/javascript">
   $(document).on("click",".confirm_delete", function (e) {
           e.preventDefault();
           var url = $(this).attr('href');
           swal({
               title: 'Are you sure?',
               text: "You won't be able to recover this !",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#009402',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes',
               cancelButtonText: 'No'
           }).then((result) => {
   
               if (result.value) {
                   window.location = url;
               }
           })
   
   
       });
</script>   
<script type="text/javascript">
   $(document).on("click",".confirm_delete_unassign_job", function (e) {
           e.preventDefault();
           var grd_ids = $(this).attr('grd_ids');
           swal({
               title: 'Are you sure?',
               text: "You won't be able to recover this !",
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
                    url: "<?= base_url('admin/UnassignJobDetete') ?>",
                    data: {grd_ids : grd_ids },
                    dataType: 'json'
                 }).done(function(data){
                     
                     $("#loading").css("display","none");
   
                    if (data.status==200) {
   
                           swal(
                              'Unassigned Job !',
                              'Deleted Successfully ',
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
</script>   
<script type="text/javascript">
   $(function() {
   
   
       // Add events
       // ------------------------------
     
    checkModalSituation = 1;
     
     $.ajax({
           url: "<?= base_url('admin/scheduledJobsData') ?>",
           method: "GET",
           dataType:'JSON',
           success: function(data) {
             
             var events =  data;
   
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
                   events    : events,
                    eventConstraint: {
                         start: moment().format('YYYY-MM-DD'),
                         end: '2100-01-01' // hard coded goodness unfortunately
                      },
                    eventClick: function(event) {
                       //alert(event.id);
                        $("#loading").css("display","block");  
                        
                        $.ajax({
                               type: "GET",
                               url: "<?= base_url('admin/getOneAssignData/') ?>"+event.id,
                               dataType:'JSON',
                           }).done(function(data){
                            // alert(data);
                                  $("#loading").css("display","none");
   
                                  $('#modalactionbtn').html(data['btn']);
                                  $('#assigndetails').html(data['html']);
                                  $('#modal_default').modal('toggle');
                     // $.sweetModal({
                     //  title: 'Scheduled Job Details',
                     //  content: data
                   // });
                           });
          
   
                  },
   
                  eventDrop : function (event, delta, revertFunc){
                               // $("#loading").css("display","block");
                               // console.log(event);
   
                               // console.log(event);
                                 $('#modal_drop_event').modal('toggle');
                                                  
                         $('#technician_job_assign_id_drop').val(event.id);
                         $('#technician_id_drop').val(event.technician_id);
   
                       
                         if (event.is_time_check==1) {
                         checked = 'checked';
                           display = 'block';
                           value = event.specific_time;  
   
                         } else {
                         checked = '';
                           display = 'none';
                           value = '';
                         }
   
   
                           $('.specificTimeDivisionEditDrop').html('<div class="row"><div class="col-sm-12"><label>Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" '+checked+' class="form-control styled" name="specific_time_check" value="1" id="changespecifictimeeditdrop" ></label>  <div id="specific_time_input_edit_drop" style="display:'+display+'" >         <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" value="'+value+'" readonly name="specific_time" placeholder="Specific Time"  >           <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>');
                         reassignCheckboxAnTimePicker();
   
                         $('#job_assign_date_drop').val(event.start.format());
                         routeMange(event.technician_id,event.start.format(),'route_select_drop_edit');
   
                          $('#modal_drop_event').on('hidden.bs.modal', function (e) {
                           if (checkModalSituation==1) {
                       revertFunc();                       
                           } else if (checkModalSituation==2) {
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
   
      "lengthMenu": [[10, 25, 50, 100, 200, 500], [10, 25, 50, 100, 200, 500]], 
     
      dom: 'l<"btndivdelete">frtip',
        initComplete: function(){
         $("div.btndivdelete")
            .html('<div class="btn-group"><button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled ><i class="icon-trash"></i> Delete</button><div class="alleditbtn"><a data-toggle="modal" data-target="#modal_multiple_edit_assign_job"><button type="submit"  class="btn btn-success" id="editallbutton"  disabled ><i class="icon-pencil"></i> Edit</button></a></div></div>');           
      }       
   });  
   
   // $('#unassigntbl').DataTable({
   
   //    dom: 'l<"toolbar">frtip',
   //      initComplete: function(){
   //       $("div.toolbar")
   //          .html(' <button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">Assign Technician</button>');           
   //    }       
   // });
   
    $(document).ready(function() {
   
      // Setup - add a text input to each footer cell
   $('#unassigntbl tfoot td').each( function () {
       var title = $(this).text();
       if (title=='PRIORITY' || title=='JOB NAME' || title=='PROPERTY TYPE' || title=='SERVICE AREA' ) {
         $(this).html( '<input type="text" class="form-control dtatableInput" placeholder="'+title+'" />' );
       } else {
         $(this).addClass('noSpacingInput');
       }
   } );
   
   // DataTable
   var table = $('#unassigntbl').DataTable({ 
   
   "lengthMenu": [[10, 25, 50, 100, 200, 500], [10, 25, 50, 100, 200, 500]], 
   
      dom: 'l<"toolbar">frtip',
        initComplete: function(){
   
        $("div.toolbar")
          .html(''); 
   
    var r = $('#unassigntbl tfoot td');
   
    $("div.toolbar")
            .append('<span class="tmpspan" >Filter: </span>');   
     $("div.toolbar")
            .append(r);           
      }       
   });
   
   // Apply the search
   table.columns().every( function () {
       var that = this;
   
       $( 'input', this.footer() ).on( 'keyup change', function () {
           if ( that.search() !== this.value ) {
               that
                   .search( this.value )
                   .draw();
           }
       } );
   } );
   
   
   
   } );
   
   
   
   
   
   
   
   $('input[name=changeview]').click(function () {
         
           if($(this).prop("checked") == true){
   
             $('.calederview').css('display','block');
             $('.sheduletable').css('display','none');   
             // $('.btndivdelete').css('display','none');    
   
           }
   
           else if($(this).prop("checked") == false){
             $('.calederview').css('display','none');
             $('.sheduletable').css('display','block');
             // $('.btndivdelete').css('display','block');
   
           }   
   
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
   
   
   
    $('.primary-edit').click(function () {
           if($(this).val() == 1){
   
             $('#route_input_edit').css('display','none');
             $('#route_select_edit').css('display','block');
             
           }
   
           else if($(this).val() == 2){
             $('#route_input_edit').css('display','block');
             $('#route_select_edit').css('display','none');
                           
           }   
   
   });
   
   $(document).on("click","#changespecifictimeedit", function (e) {
           if($(this).prop("checked") == true){
             $('#specific_time_input_edit').css('display','block');          
           }
           else if($(this).prop("checked") == false){
             $('#specific_time_input_edit').css('display','none');
           }   
   
   });
   
   $('.primary-edit-drop').click(function () {
           if($(this).val() == 1){
             $('#route_input_drop_edit').css('display','none');
             $('#route_select_drop_edit').css('display','block');
           }
   
           else if($(this).val() == 2){
             $('#route_input_drop_edit').css('display','block');
             $('#route_select_drop_edit').css('display','none');
           }   
   
   });
   
   
   function rearraangedropModalForm(argument) {
     
     $("#primary-edit-drop-id").prop("checked", true);
           $('#route_input_drop_edit').css('display','none');
           $('#route_select_drop_edit').css('display','block');
   
   }
   
   
   $(document).on("click","#changespecifictimeeditdrop", function (e) {
   
           if($(this).prop("checked") == true){
             $('#specific_time_input_edit_drop').css('display','block');           
           }
           else if($(this).prop("checked") == false){
             $('#specific_time_input_edit_drop').css('display','none');
           }   
   
   });
   
   $('.primary-edit-multiple').click(function () {
             if($(this).val() == 1){
               $('#route_input_edit_multiple').css('display','none');
               $('#route_select_edit_multiple').css('display','block');
             }
             else if($(this).val() == 2){
               $('#route_input_edit_multiple').css('display','block');
               $('#route_select_edit_multiple').css('display','none');
       }   
   
   });
   $(document).on("click","#changespecifictimeeditmultiple", function (e) {
           if($(this).prop("checked") == true){
             $('#specific_time_input_edit_multiple').css('display','block');           
           }
           else if($(this).prop("checked") == false){
             $('#specific_time_input_edit_multiple').css('display','none');
           }   
   
   });
   
   
   
   function editAssignJob($technicianJob) {
   
   $('#modal_default').modal('hide');
   
   $("#loading").css("display","block");     
   
   url = '<?= base_url('admin/getOneAssignJsonbData/') ?>'+$technicianJob
   $.getJSON( url, function( data ) {
       $("#loading").css("display","none");  
     $('#technician_id_edit option[value="'+data['technician_id']+'"]').prop('selected', true)
     $('#jobAssignDateEdit').val(data['job_assign_date']);
     $('#assign_notes_edit').val(data['job_assign_notes']);    
     $('#technician_job_assign_id').val(data['technician_job_assign_id']);
     routeMange(data['technician_id'],data['job_assign_date'],'route_select_edit',data['route_id']);
     if (data['is_time_check']==1) {
       checked = 'checked';
       display = 'block';
       value = data['specific_time'];
     } else {
       checked = '';
       display = 'none';
       value = '';
     }
     $('.specificTimeDivisionEdit').html('<div class="row"><div class="col-sm-6"><label> Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" '+checked+'  class="form-control styled" name="specific_time_check" value="1" id="changespecifictimeedit" ></label><div id="specific_time_input_edit" style="display:'+display+'" >         <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" value="'+value+'" readonly name="specific_time" placeholder="Specific Time"  >           <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div>      </div></div></div>');
      reassignCheckboxAnTimePicker();
   });
   
   }
   
</script>     
<!-- /////  for multiple delete  -->
<script type="text/javascript">
   $("#select_all-delete").change(function(){  //"select all" change 
     var status = this.checked; // "select all" checked status
    if (status) {
     $('#deletebutton').prop('disabled', false);
     $('#editallbutton').prop('disabled', false);
   
   
   
    }
    else
    {
      $('#deletebutton').prop('disabled', true);
      $('#editallbutton').prop('disabled', true);
   
    }
     $('.myCheckBoxDelete').each(function(){ //iterate all listed checkbox items
         this.checked = status; //change ".checkbox" checked status
   
     });
   });
   
   $('.myCheckBoxDelete').change(function(){ //".checkbox" change 
     //uncheck "select all", if one of the listed checkbox item is unchecked
     if(this.checked == false){ //if this item is unchecked
         $("#select_all-delete")[0].checked = false; //change "select all" checked status to false
   
   
     }
     
     //check "select all" if all checkbox items are checked
     if ($('.myCheckBoxDelete:checked').length == $('.myCheckBoxDelete').length ){ 
         $("#select_all-delete")[0].checked = true; //change "select all" checked status to true
     }
   });
   
   
   
   $(document).on("change","table .myCheckBoxDelete", function (e) {
   
   var checkBoxes2 = $('table .myCheckBoxDelete');
   // checkBoxes2.change(function () {
   
   // alert();
     $('#deletebutton').prop('disabled', checkBoxes2.filter(':checked').length < 1);
     $('#editallbutton').prop('disabled', checkBoxes2.filter(':checked').length < 1);
   });
   
   // checkBoxes2.change();  
   
   
   
   
   function deletemultiple() {
   
      swal({
       title: 'Are you sure?',
       text: "You won't be able to recover this !",
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#009402',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Yes',
       cancelButtonText: 'No'
     }).then((result) => {
   
       if (result.value) {
   
        var selectcheckbox = [];
        $("input:checkbox[name=selectcheckbox]:checked").each(function(){
              selectcheckbox.push($(this).attr('technician_job_assign_ids'));
         }); 
        
      // alert(selectcheckbox);
   
      
           $.ajax({
              type: "POST",
              url: "<?= base_url('admin/deletemultipleJobAssign') ?>",
              data: {job_assign_ids : selectcheckbox }
           }).done(function(data){
   
             // alert(data);
   
                   if (data==1) {
                     swal(
                        'Scheduled Jobs !',
                        'Deleted Successfully ',
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
   
   }
   
   
   
     $('#allMessage').click(function(){ //iterate all listed checkbox items    
     
       var numberOfChecked = $('input:checkbox[name=group_id]:checked').length;
         if (numberOfChecked==1) {
           $('.specificTimeDivision').html('<div class="row"><div class="col-sm-6"><label>Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-control styled" name="specific_time_check" value="1" id="changespecifictime" ></label><div id="specific_time_input" style="display:none;" ><div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" readonly name="specific_time" placeholder="Specific Time"  >        <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>');
   
           reassignCheckboxAnTimePicker();
   
   
         } else {
           $('.specificTimeDivision').html('');
   
         }
   
     });
   
   
    
     $('#editallbutton').click(function(){ //iterate all listed checkbox items
   
   
    
   
       var numberOfChecked = $('.myCheckBoxDelete:checked').length;
       //alert(numberOfChecked);
         if (numberOfChecked==1) {
           $('.specificTimeDivisionEditMultiple').html('<div class="row"><div class="col-sm-6"><label>       Schedule at a Specific Time ?&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-control styled" name="specific_time_check" value="1" id="changespecifictimeeditmultiple" >          </label> <div id="specific_time_input_edit_multiple" style="display:none;" > <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true"  > <input type="text" class="form-control" readonly name="specific_time" placeholder="Specific Time"  >           <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div></div></div></div>');
   
         reassignCheckboxAnTimePicker();
   
   
         } else {
           $('.specificTimeDivisionEditMultiple').html('');
   
         }
   
     });
   
   
   
   
   
   // for assign job 
   
   $( "#technician_id" ).change(function() {
        technician_id = $(this).val();
      jobAssignDate =$('#jobAssignDate').val();
      route_select_id = 'route_select';
      routeMange(technician_id,jobAssignDate,route_select_id);
   });
   
     $( "#jobAssignDate" ).change(function() {
   
        $("#technician_id").trigger("change");
   });
   
   
   
   
   $( "#technician_id_edit" ).change(function() {
        technician_id = $(this).val();
      jobAssignDate =$('#jobAssignDateEdit').val();
      route_select_id = 'route_select_edit';
      routeMange(technician_id,jobAssignDate,route_select_id);
   });
   
     $( "#jobAssignDateEdit" ).change(function() {
   
        $("#technician_id_edit").trigger("change");
   });
   
   //  for multiple edit assign job  
   
   
   $( "#technician_id_edit_multiple" ).change(function() {
        technician_id = $(this).val();
      jobAssignDate =$('#jobAssignDateEditMultiple').val();
      route_select_id = 'route_select_edit_multiple';
      routeMange(technician_id,jobAssignDate,route_select_id);
   });
   
     $( "#jobAssignDateEditMultiple" ).change(function() {
   
        $("#technician_id_edit_multiple").trigger("change");
   });
   
   
   
   
   function routeMange(technician_id,jobAssignDate,route_select_id,selected_id='') {
      
      $('#'+route_select_id).html('');
      if (technician_id!='' && jobAssignDate!='' ){
   
           $.ajax({
              type: "POST",
              url: "<?= base_url('admin/getTexhnicianRoute') ?>",
              data: {technician_id : technician_id , job_assign_date : jobAssignDate },
              dataType : "json",
           }).done(function(data){
   
           if (data.length===0) {
             $('#'+route_select_id).append('<option value="">No route found</option>');
           } else {
           $.each(data, function( index, value ){  
   
                if (value.route_id==selected_id) {
                   selected = 'selected';
                  }else {
           selected = '';            
                  }
   
   
                 $('#'+route_select_id).append('<option value="'+value.route_id+'" '+selected+' >'+value.route_name+'</option>');
               });
           } 
           });
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
