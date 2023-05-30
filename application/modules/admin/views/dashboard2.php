<style> 
   .picker__box {
   padding-right: 1px;
   padding: 0
   }
   .content {
   padding: 20px !important;
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
   /*widget css*/
   li.list-group-item.head-month {
   color: rgb(73, 103, 120);
   background-color: #3379b740;
   border-bottom: 1px solid #6eb0fe !important;
   border-radius: 4px;
   border-bottom-left-radius: 0;
   border-bottom-right-radius: 0;
   text-align: left;
   padding: 8px;
   font-size: 14px;
   }
   .list-group-item {
   position: relative;
   display: block;
   padding-top: 10px;
   margin-bottom: -1px;
   background-color: #fff;
   border: 1px solid #ddd !important;
   text-align: center;
   padding: 11px 3px;
   border: none;
   margin: 12px;
   }
   span.Property-text {
   color: #303030;
   }
   .cx-txt { 
   font-size: 12px;
   }
   .cx-txt , .cx-txt a:hover, .cx-txt a:focus {
   color: #989898 !important;
   }
   .list-group {
   list-style: none;
   border: 1px solid #6eb1fd;
   padding: 0;
   border-radius: 4px;
   /*min-height: 456px;
   max-height: 456px;*/
   min-height: 565px;
   max-height: 565px;
   overflow-y: auto;
   margin-bottom: 10px;
   }
   .list-group::-webkit-scrollbar { 
   display: none; 
   }
   /* .list-group {
   overflow-y: auto;
   }*/
   .list-group:hover{
   overflow-y: auto;
   }
   .row > .testimonial-group  {
   overflow-x: auto;
   white-space: nowrap;
   display: flex;
   }
   .row >  .testimonial-group > .col-md-6 {
   display: inline-block;
   float: none;
   }
   /*calendar css mhk*/
   .picker__holder {
   max-width : 100%;
   }
   .clndr-cnt {
   padding: 0 0px 19px 11px;
   }
   .picker__month {
   cursor: pointer;
   color: #016699;
   font-size: 14px;
   font-weight: 400;
   }
   .clndr thead {
   background-color: #fff;
   border-bottom: 1px solid #ccc;
   }
   .picker--opened .picker__holder{
   height: 477px;
   }
   .picker__day {
   /*   padding: 13px;
   */   padding: 20px 0;
   }
   .picker__nav--next:before, .picker__nav--prev:before
   {
   color: #016699;
   }
   /*   .picker__day--infocus:hover, .picker__day--outfocus:hover {
   background-color: none !important;
   }*/
   .picker{
   z-index: 0;
   position: inherit !important;
   top : 0;
   }
   input[type=text] {
   width: 100%;
   padding: 12px 20px;
   margin: 8px 0;
   box-sizing: border-box;
   }
   /*scroll color css*/
   /* width */
   /* ::-webkit-scrollbar {
   width: 7px;
   height: 7px;
   border-radius: 5px;
   }*/
   /* ::-webkit-scrollbar-track {
   background: #f5f5f5; 
   }*/
   /*  ::-webkit-scrollbar-thumb {
   background: #b3d4fc; 
   }*/
   .picker, .picker__holder{
   position: inherit !important;
   border: none;
   background: transparent;
   }
   .customdiv {
   border:1px solid #6eb1fd;
   border-radius: 4px;
   }
   .select2-selection.select2-selection--multiple{
   background: transparent;
   }
   .picker--opened .picker__holder {
   max-height: 496px;
   border-top-width: 1px;
   border-bottom-width: 1px;
   display: block;
   min-height: 496px;
   }
   .manage-jobs{
   background: #fff;
   font-size: 12px;
   border: 1px solid rgb(1, 144, 217);
   }
   li.select2-selection__choice:nth-child(n) {
   background: #f0b051;
   /*yellow*/
   }
   li.select2-selection__choice:nth-child(2n+0) {
   background: #e48774;
   }
   li.select2-selection__choice:nth-child(3n+0) {
   background: #5ba38e;
   }
   li.select2-selection__choice i.icon-undefined {
   display: none;
   }
   li.select2-selection__choice {
   margin: 3px 0;
   }
   .fa-exclamation-circle:before {
   content: "\f1ce" !important;
   font-size: 40px;
   position: absolute;
   left: 101px;
   bottom: 5px;
   }
   .picker__table td{
   font-size: 15px;
   }
   .score-bd td {
   text-align: center;
   }
   .score-bd th {
   font-size: 11px !important;
   }
   .form-control.scoreboard_date {
   font-size: 11px !important;
   }
   .score-bd .form-control {
   padding: 0px 0px;
   }
   .icon-exclamation {
   float: right;
   padding-top: 5px;
   font-size: 25px;
   }
   .panel_row .panel {
   margin-bottom: 20px !important;
   border-radius: 5px !important;
   box-shadow: 1px 1px 1px 1px #cccccc8a;
   }
   .panel_row .text-size-small {
   font-size: 17px;
   }

   .picker--focused .picker__day--highlighted,
.picker__day--highlighted,
.picker__day--highlighted:hover {
    line-height: 0px !important;
}
</style>
<link rel="stylesheet" type="text/css" href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/weather/css/responsive.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" />
<div class="content">
   <div class="">
      <div class="mymessage"></div>
      <b><?php if ($this->session->flashdata()): echo $this->session->flashdata('message');
         endif
         ?></b>
      <div id="loading" >
         <img id="loading-image" src="<?= base_url() ?>assets/loader.gif"  /> <!-- Loading Image -->
      </div>
      <div class="row" style="padding: 0 0 17px 0;">
         <div class="col-lg-9 col-md-9">
            <div class="col-md-12" style="padding: 0px;">
               <div class="row panel_row"  >
                  <!-- <div class="col-lg-4"> -->
                     <!-- Members online -->
                     <!-- <a href="<?php //echo base_url('admin/assignJobs'); ?>">
                        <div class="panel" style="background-color: #F5A6A5;box-shadow:none;  " >
                           <div class="panel-body">
                              <h5 class="no-margin" style="color: #fff" >Unassigned Services</h5>
                              <div class="text-muted text-size-small text-danger"><span id="unassigned_count"><i class="fa fa-spinner fa-spin" style="font-size:15px"></i></span><span><i class="icon-exclamation"></i></span></div>
                           </div>
                        </div>
                     </a> -->
                     <!-- /members online -->
                  <!-- </div> -->
                  <div class="col-lg-4">
                     <!-- Current server load -->
                     <a href="<?= base_url('admin/manageJobs') ?>">
                        <div class="panel">
                           <div class="panel-body">
                              <h5 class="no-margin" >Scheduled Services</h5>
                              <div class="text-muted text-size-small text-warning">
                                  <span id='scheduled-services-value'> -- </span>
                              </div>
                           </div>
                        </div>
                     </a>
                     <!-- /current server load -->
                  </div>
                  <div class="col-lg-4">
                     <!-- Today's revenue -->
                     <a href="<?= base_url('admin/assignJobs') ?>">
                        <div class="panel">
                           <div class="panel-body">
                              <h5 class="no-margin" >To Be Rescheduled </h5>
                              <div class="text-muted text-size-small text-danger">
                                  <span id='rescheduled-value'> -- </span>
                              </div>
                           </div>
                        </div>
                     </a>
                     <!-- /today's revenue -->
                  </div>
               </div>
               <div class="row panel_row">
                  <div class="col-lg-4">
                     <!-- Members online -->
                     <a href="<?= base_url('admin/Invoices') ?>">
                        <div class="panel">
                           <div class="panel-body">
                              <h5 class="no-margin" >Gross Revenue</h5>
                              <div class="text-muted text-size-small text-success"><?= (!empty($result_revenue->cost)) ? '$ '.number_format($result_revenue->cost,2)    : "$ 0" ?> <span class="text-muted month-txt" > This month</span></div>
                           </div>
                        </div>
                     </a>
                     <!-- /members online -->
                  </div>
                  <div class="col-lg-4">
                     <!-- Current server load -->
                     <a href="<?= base_url('admin/reports') ?>">
                        <div class="panel">
                           <div class="panel-body">
                              <h5 class="no-margin" >Completed Services</h5>
                              <div class="text-muted text-size-small text-success">
                                  <span id='completed-services-value'> -- </span>
                                  <span class="text-muted month-txt" >This month</span>
                              </div>
                           </div>
                        </div>
                     </a>
                     <!-- /current server load -->
                  </div>
                  <div class="col-lg-4">
                     <!-- Today's revenue -->
                    
                        <div class="panel">
                           <div class="panel-body">
                              <h5 class="no-margin" >Outstanding Invoices</h5>
                              <div class="text-muted text-size-small text-success">$<?= $OutstandingInvoiceCost ?> </div>
                           </div>
                        </div>
                     
                 
                     <!-- /today's revenue -->
                  </div>
               </div>
            </div>
            <div class="col-md-12" style="padding: 0px;">
               <ul class="flex-container">
               </ul>
            </div>
            <div class="col-lg-12">
               <div class="panel panel-flat" style="margin-top:20px;background-color: transparent;">
                  <div class="head-section"  style="padding-top: 10px;padding-bottom: 20px;color: #333;">
                     <div class="row">
                        <div class="col-md-4 col-sm-12">  
                           <span class="text-semibold" style="">Scheduled Services</span>
                        </div>
                        <div class="col-md-4 col-sm-12">  
                           <button type="submit" class="btn btn-success" style="background: #0190d9; border: none;"><a style="color: #fff;" class="fas fa-plus"href="<?= base_url('admin/assignJobs') ?>">Assign Service</a>
                           </button>   
                        </div>
                        <div class="col-md-4 col-sm-12">
                           <div class="managebtn" style="float: right;margin-right: 18px;">
                              <button type="submit"class="btn  manage-jobs" >
                              <a style="color: #000;" href="<?= base_url('admin/manageJobs') ?>"> Manage Services</a></button>                  
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="panel-body" style="padding: 20px 0;">
                     <div class="row">
                        <div class="col-md-4 col-sm-12 col-12 clndr">
                           <div class="row">
                              <div class="col-md-12 col-sm-12 col-12 cal-sec" >
                                 <div class="customdiv">
                                    <div id="calendar"></div>
                                    <div class="filterselect" >
                                       <select multiple="multiple" data-placeholder="Filter by Technician :" class="select-icons">
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
                              </div>
                           </div>
                           <input type="hidden" id="datesting" >
                           <textarea id="tecnician_id_array" style="display: none;" >[]</textarea>
                        </div>
                        <div class="col-md-8  col-sm-12 data-sec">
                           <div class="row over-flow-row">
                              <div class="testimonial-group">
                                 <?php if (!empty($assign_data)) {
                                    $assign_data_count = count($assign_data);
                                    
                                    foreach ($assign_data as $key => $value) {
                                    ?>                                
                                 <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                    <ul class="list-group">
                                       <!-- <li class="list-group-item head-month">01st Thursday</li> -->
                                       <li class="list-group-item head-month"><?= date('F jS', strtotime($value->job_assign_date)); ?></li>
                                       <?php foreach ($value->assign_data_result as $key2 => $value2) { 
                                          ?>
                                       <li class="list-group-item li-cx li-data">
                                          <p  class="userdata-txt">
                                             <a href="" class="get_assign_details cx-txt" id="<?= $value2->technician_job_assign_id ?>" > <?= $value2->customer_first_name .' '.$value2->customer_last_name  ?> | <span class="Property-text"><?=  wordwrap($value2->property_title,12,"<br>");   ?> </span></a>
                                          </p>
                                       </li>
                                       <?php } ?>
                                    </ul>
                                 </div>
                                 <?php 
                                    if($assign_data_count==1) { ?>
                                 <div class="col-lg-6 col-md-6  col-sm-12 col-12  cal-data">
                                    <ul class="list-group">
                                       <!-- <li class="list-group-item head-month">01st Thursday</li> -->
                                       <li class="list-group-item head-month"><?= date('F jS', strtotime("+1 day",strtotime($value->job_assign_date)  )); ?></li>
                                       <li class="list-group-item li-cx">
                                          No Data Found                                           
                                       </li>
                                    </ul>
                                 </div>
                                 <?php } ?>
                                 <?php  } } else { ?> 
                                 <div class="col-lg-6 col-md-6  col-sm-12 col-12  cal-data ">
                                    <ul class="list-group">
                                       <!-- <li class="list-group-item head-month">01st Thursday</li> -->
                                       <li class="list-group-item head-month"><?= date('F jS'); ?></li>
                                       <li class="list-group-item li-cx">
                                          No Data Found                                           
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="col-lg-6 col-md-6  col-sm-12 col-12  cal-data">
                                    <ul class="list-group">
                                       <!-- <li class="list-group-item head-month">01st Thursday</li> -->
                                       <li class="list-group-item head-month"><?= date('F jS', strtotime("+1 day",strtotime(date("Y-m-d"))  )); ?></li>
                                       <li class="list-group-item li-cx">
                                          No Data Found                                           
                                       </li>
                                    </ul>
                                 </div>
                                 <?php  } ?>   
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3">
            <div class="col-lg-12 col-md-12 col-sm-12">
               <!-- Basic pie -->
               <div class="panel panel-flat">
                  <!-- <div style="padding-top: 10px;padding-bottom: 10px;color: #333;background: #fafafa;">
                     <center>Weather Forecast for Today </center>
                     </div> -->
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
                              <div class="col-lg-3 pull-left">
                                 <div class="icon-c pull-right">
                                    <?php
                                        $icon_name = "";
                                        switch($widget_data->currentWeather->conditionCode) {
                                         case "Clear":
                                             $icon_name = "clear-day";
                                         break;
                                         case "MostlyClear":
                                             $icon_name = "clear-day";
                                         break;
                                         case "PartlyCloudy":
                                             $icon_name = "partly-cloudy-day";
                                         break;
                                         case "MostlyCloudy":
                                             $icon_name = "partly-cloudy-day";
                                         break;
                                         case "Cloudy":
                                             $icon_name = "cloudy";
                                         break;
                                         case "Hazy":
                                             $icon_name = "fog";
                                         break;
                                         case "Thunderstorms":
                                             $icon_name = "thunderstorm";
                                         break;
                                         case "ScatteredThunderstorms":
                                             $icon_name = "thunderstorm";
                                         break;
                                         case "Drizzle":
                                             $icon_name = "rain";
                                         break;
                                         case "rain":
                                         case "Rain":
                                             $icon_name = "rain";
                                         break;
                                         case "HeavyRain":
                                             $icon_name = "rain";
                                         break;
                                         case "Windy":
                                            $icon_name = 'wind';
                                         break;
                                        }
                                    ?>
                                    <img src="<?php echo base_url('assets/weather/images/').$icon_name.'.png'; ?>" style='max-width:40px;' alt="" />
                                 </div>
                              </div>
                              <div class="col-lg-9 pull-right">
                                 <div class="temp-desc pull-left">
                                    <h5><?php
                                        $temp_in_f = ($widget_data->currentWeather->temperature * 9/5) + 32;
                                       echo round($temp_in_f);
                                       ?>°
                                       <em>Wind: <?php 
                                            echo round(($widget_data->currentWeather->windSpeed / 1.609344),2).' mph' 
                                            ?>
                                        </em>
                                    </h5>
                                    
                                 </div>
                              </div>
                              
                           </div>
                        </div>
                        <div class="weatherforecast">
                           <ul>
                              <?php
                                //var_dump($widget_data->forecastDaily->days);
                                 $rows = $widget_data->forecastDaily->days;
                                 //var_dump($rows);
                                 foreach ($rows as $key => $value) {  
                                 ?>
                              <li class="day old-in" style='max-height:22px;'>
                                 <div class="dayname">
                                    <span><strong>
                                    <?php 
                                       $date = $value->daytimeForecast->forecastStart;
                                       $icon_name = "";
                                       switch($value->conditionCode) {
                                        case "Clear":
                                            $icon_name = "clear-day";
                                        break;
                                        case "MostlyClear":
                                            $icon_name = "clear-day";
                                        break;
                                        case "PartlyCloudy":
                                            $icon_name = "partly-cloudy-day";
                                        break;
                                        case "MostlyCloudy":
                                            $icon_name = "partly-cloudy-day";
                                        break;
                                        case "Cloudy":
                                            $icon_name = "cloudy";
                                        break;
                                        case "Hazy":
                                            $icon_name = "fog";
                                        break;
                                        case "Thunderstorms":
                                            $icon_name = "thunderstorm";
                                        break;
                                        case "ScatteredThunderstorms":
                                            $icon_name = "thunderstorm";
                                        break;
                                        case "Drizzle":
                                            $icon_name = "rain";
                                        break;
                                        case "rain":
                                        case "Rain":
                                            $icon_name = "rain";
                                        break;
                                        case "HeavyRain":
                                            $icon_name = "rain";
                                        break;
                                        case "Windy":
                                            $icon_name = 'wind';
                                         break;
                                       }
                                       if ($key==0) {
                                        echo "<span style='font-size: smaller;'>Today</span>";
                                       } else {
                                        echo "<span style='font-size: smaller;'>".date('D', strtotime($date))."</span>";  
                                       } 
                                       
                                       ?>
                                    </strong></span>
                                 </div>
                                 <div class="sm-day-icon">
                                    <img src="<?php echo base_url('assets/weather/images/').$icon_name.'.png'; ?>" style='max-width:20px;' alt="" />
                                 </div>
                                 <div class="temps-day">
                                    <?php
                                        $max_temp_in_f = ($value->temperatureMax * 9/5) + 32;
                                        $min_temp_in_f = ($value->temperatureMin * 9/5) + 32;

                                    ?>
                                    <div class="temp-day a" style='font-size: smaller;'><strong><?php echo round($max_temp_in_f);  ?>°</strong></div>
                                    <div class="temp-m-day b" style='font-size: smaller;'><strong><?php echo round($min_temp_in_f); ?>°</strong></div>
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
                              <th scope="col" colspan="2" class="ft">
                                 <table style="width: 100%">
                                    <tr>
                                       <td>Filter by date</td>
                                    </tr>
                                    <tr>
                                       <td><input type="text" class="form-control daterange-input" value="<?= date("m-d-Y")  ?> - <?= date("m-d-Y")  ?>" style="font-size:12px; text-align:center; " > </td>
                                    </tr>
                                 </table>
                              </th>
                           </tr>
                        </thead>
                        <tbody class="scorebody" >
                           <?php if (!empty($technician_scoreboard)) {
                              // print_r($technician_scoreboard);
                              
                              
                              foreach ($technician_scoreboard as $key => $value) { ?>
                           <tr>
                              <td><?= $value->user_first_name.' '.$value->user_last_name ?></td>
                              <td>
                                 <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?= ($value->total*100)/$value->total_area   ?>% " aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                 </div>
                              </td>
                              <td class=" ft-txt"><?= ($value->total) ?> / <?= ($value->total_area) ?> -sq.-ft</td>
                           </tr>
                           <?php } } else { ?>
                           <tr>
                              <td colspan="3">No data Found</td>
                           </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="modal_default" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title" style="float: left;">Scheduled Service Details</h5>
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
<!--   <div class="cht-opn">
   <h5 class="qun">Got a question ?
   <span class="chtn"><a href="">Chat to SprayeBot</a></span></h5>
   </div> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src="http://35.177.163.6/assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<script type="text/javascript">
   var tech_ids = [];
   
      $('#calendar').pickadate({ 
         today: '',
         clear: '',
         close: '',
         klass: {
           picker: "picker picker--opened",
           opened: "__always-open__",  
             highlighted: 'picker__day--highlighted',   
         },
         onStart: function() {
            var date = new Date();
            var actuall_datedate =  date.getFullYear()+'-'+(date.getMonth() + 1)+'-'+ date.getDate();
            $('#datesting').val(actuall_datedate);       
         },
          onSet: function(context) {
            
            console.log('Just set stuff:', context);
   
            if (context.select!==undefined) {
                 stringdate = context.select;
            } else if(context.highlight!==undefined) {
                stringdate = context.highlight.pick;
            }
   
            var date = new Date(parseInt(stringdate));
            var actuall_datedate =  date.getFullYear()+'-'+(date.getMonth() + 1)+'-'+ date.getDate();
            $('#datesting').val(actuall_datedate);       
            getCalenderDataContainer();
                
         }
   
   
      });
   
   $('.select-icons').on('select2:select', function (e) {
         var data = e.params.data;
         tech_ids.push(data.id); 
         $('#tecnician_id_array').val(JSON.stringify(tech_ids));
         getCalenderDataContainer();
   
   
   });
   $('.select-icons').on('select2:unselect', function (e) {
            var data = e.params.data;
            tech_ids = $.grep(tech_ids, function(e){ 
                       return e != data.id; 
            });
           $('#tecnician_id_array').val(JSON.stringify(tech_ids));
           getCalenderDataContainer();
          
   });
   
   function getCalenderDataContainer(){
   
     var tecnician_id_array =   $('#tecnician_id_array').val();
     var datesting =   $('#datesting').val();
   
   
    $.ajax({
            type: 'POST',
            url: '<?= base_url('admin') ?>'+'/getCalederbyYnassignJobs',
            data : {tecnician_id_array : tecnician_id_array , datesting :  datesting },
            success: function (response) {
             $('.testimonial-group').html(response);
            }
          });
   }
   
   
   
   
   
   $('.daterange-input').daterangepicker({
   
    "opens": "left",
    "drops": "up",
    "locale": {
        "format": "MM-DD-YYYY",
     },
   }, function(start, end, label) {
   var  from_date = start.format('YYYY-MM-DD');
   var  to_date = end.format('YYYY-MM-DD'); 
   
    $.ajax({
            type: 'POST',
            url: '<?= base_url('admin') ?>'+'/getTechnicianScoreboard',
            data : {from_date : from_date , to_date :  to_date },
         dataType : "JSON",
            success: function (response) {            
           $('.scorebody').html('');
   
           if (response.status==200) {          
   
           jQuery.each( response.result, function( i, val ) {
   
               percent =  (parseInt(val.total)*100)/parseInt(val.total_area);  
   
               $('.scorebody').append('<tr>  <td> '+val.user_first_name+' '+val.user_last_name+' <td> <div class="progress"> <div class="progress-bar" role="progressbar" style="width: '+percent+'%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>   </div>  </td> <td class=" ft-txt">'+parseInt(val.total)+' / '+parseInt(val.total_area)+' -sq.-ft</td>  </tr>')
                 
              });
           } else {
            
             $('.scorebody').html(' <tr>  <td colspan="3">No data Found</td>  </tr>');
   
           }
     
   
            }
          });
   
   });
   
   
   
   
   
   
   
   
   $(document).on("click",".get_assign_details", function (e) {
   e.preventDefault();
   var id =   $(this).attr('id');
   
   $("#loading").css("display","block");  
   $.ajax({
      type: "GET",
      url: "<?= base_url('admin/getOneAssignData/') ?>"+id,
      dataType:'JSON',
   }).done(function(data){
      $("#loading").css("display","none");
      $('#assigndetails').html(data['html']);
      $('#modal_default').modal('toggle');        
   });
   });         
   
   
   
   
   
   
   
   
</script>

<!-- <script type="text/javascript">
  
    $(function () {
        $.ajax({
            type: "GET",
            url: "<?php //echo base_url('admin/ajaxGetTotalUnassignedServices/'); ?>",
            success: function (response) {
                $('#unassigned_count').text(JSON.parse(response).unassigned_count);
            }
        });
    });
</script> -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/clock/js/highlight.min.js"></script>
<script type="text/javascript">
   hljs.configure({tabReplace: '    '});
   hljs.initHighlightingOnLoad();
</script>


<script type="text/javascript">
// delay load summary statistics on dashboard until page load
$( document ).ready(function() {

    updateDashboardValue(
        "<?= base_url('admin/summarystatistics/getJobCount/month') ?>"
        ,"#scheduled-services-value");

    updateDashboardValue(
        "<?= base_url('admin/summarystatistics/getCountOfRescheduledJobs') ?>"
        ,"#rescheduled-value");

    updateDashboardValue(
        "<?= base_url('admin/summarystatistics/getCompletedJobCount/month') ?>"
        ,"#completed-services-value");

});

function updateDashboardValue(url,elementid)
{
    const data = {};
    $.getJSON(url, data, (data, status) => {
        console.log(data);
        if (data.status === 200) {
            $(elementid).text(data.result);
        }
    });
}
</script>