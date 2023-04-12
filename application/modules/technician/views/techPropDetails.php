<style>
  /* Disabled Menu Items */

  button.multiselect.dropdown-toggle.btn.btn-default {
    margin-left: 4px;
} 
li.dropdown-menu-item.text-muted.dropdown-menu-item-icon.disabled > a {
    cursor: not-allowed;
    pointer-events: none;
}  
  /* End */    
.btn-technician {
    position: relative;
    color: #fff;
    background-color: #36c9c9;
    border-color: #36c9c9;
    display: inline-block;
    margin-bottom: 0;
    font-weight: 500;
    text-align: center;
    vertical-align: middle;
    touch-action: manipulation;
    cursor: pointer;
    border: 1px solid transparent;
    border-top-color: transparent;
    border-right-color: transparent;
    border-bottom-color: transparent;
    border-left-color: transparent;
    white-space: nowrap;
    padding: 15px 12px;
    font-size: 14px;
    line-height: 1.5384616;
    border-radius: 5px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    width: 100%;
   
}

.btn-technician:hover{
    color:#fff;
}

.btn-complete {    
    color: #fff;
    background-color: #FFBE2C;
    border-color: #FFBE2C;   
}
.btn-start-time {    
  background-color: #36d290;
}

.btn-complete:hover{
    color:#fff;
}

.btn-reschedule {    
    color: #333333;
    background-color: #fff;
    border-color: gray;   
}

.btn-reschedule:hover{
    color:#333333;
}
.btn-addservice {    
    color: #fff;
    background-color: #36c9c9;
    border-color: #36c9c9;   
}

.btn-property {    
    color: #fff;
    background-color: #198754;
    border-color: #198754;   
}

.btn-estimate {    
    color: #fff;
    background-color: #FFBE2C;
    border-color: #FFBE2C;   
}

.btn-pay {    
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;   
}


.tecnician-btn{
    padding-top: 10px;
}

.finish_btn_color{
 color: #fff;
background-color: #dfdedc !important;
border-color: #a09f9d;
}

body{
    overflow-x: hidden;
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
   z-index: 99;
   text-align: center;
}
div.well.note-element {
  margin-bottom: 20px !important;
  border: 1px solid #ddd !important;
  border-radius: 3px !important;
  color: #333 !important;
  background-color: #fafafa !important;
  font-family: 'Roboto' !important;
}
ul.dropdown-menu li.dropdown-header {
    color: black;
    text-align: center;
    font-size: 1.5rem;
}
#createnoteform {
  padding: 2rem;
}
.note-element {
  /* font-size: 2rem; */
}
.mobile-secondary-header {
  padding: 2em 1em;
  font-weight: bold;
}
.note-files {
  font-size: initial;
}
.icon-cloud-upload {
  padding: 1rem;
}
#loading-image {
  position: absolute;
  left: 50%;
  top: 50%;
  width: 10%;
  z-index: 100;
}
  #routeMap {
      height: 100%;  /* The height is 400 pixels */
      /*width: 100%;*/
      padding-top: 100%;  /* The width is the width of the web page */
      margin: 30px;
            }
.btn-ico {
   font-size: 3rem;
}
.note-ico-btns {
  padding: 3rem;
}
.techmessage {
      padding-top:  5px;
      padding-left:  19px;
      padding-right:   20px;
      }
.panel-grey {
  background-color: #F5F5F5;
}
#modal_add_service .row {
  margin-bottom: 5px;
}
  /* The Modal (background) */
.modal-files,.modal-comments {
  /* display: none; */
  /* position: fixed;  */
  /* z-index: 1;  */
  /* padding-top: 100px;  */
  /* left: 0; */
  /* top: 0; */
  /* width: 100%;  */
  /* overflow: auto;  */
  /* background-color: rgb(0,0,0); */
  /* background-color: rgba(0,0,0,0.9);  */
}
.files-thumbnail {
  max-width: 150px;
  margin-left: auto;
  margin-right: auto;
}
.file-attach-icon {
  font-size: 5em;
}

/* Modal Content (image) */
.modal-content {
  margin: auto; 
  /* display: block; */
  width: 100%;
  max-width: fit-content;
}
#file-display-modal {
  width: 100vw;

}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close:not(.removePropertyConditionClose){
  position: absolute;
  top: 15px;
  right: 35px;
}
.close {
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

#file-display-modal {
  /* z-index: 999; */
}
.property-condition-button button, .property-condition-button button:hover{
color: #FFF;
font-size: 12px;
font-weight: bold;
padding-left: 10px;
}
.property-condition-button{
margin:5px;
}

	  
</style>




<?php

  $alldata =  array();
  $Locations =  array();

  $statLocation = array(
    'Name' => $currentaddress,
    'Latitude' => $currentlat,
    'Longitude' => $currentlong 

  );

   $property_address_details =    array(
              'Name' => $property_details->property_address,
              'Latitude' => $property_details->property_latitude,
              'Longitude' => $property_details->property_longitude,
    );

    if($this->session->userdata['spraye_technician_login']->end_location != "") {
        $endLocation = array(
            'Name' => $this->session->userdata['spraye_technician_login']->end_location,
            'Latitude' => $this->session->userdata['spraye_technician_login']->end_location_lat,
            'Longitude' => $this->session->userdata['spraye_technician_login']->end_location_long,
        );
    } else {
        $endLocation = array(
        'Name' => $setting_details->end_location,
        'Latitude' => $setting_details->end_location_lat,
        'Longitude' => $setting_details->end_location_long 

        );
    }
    
$Locations = array($statLocation,$property_address_details,$endLocation);

//print_r($job_assign_details);


$OptimizeParameters = array(
        "AppId" => RootAppId,
        "OptimizeType" => "distance",
        "RouteType" => "realroadcar",
        "Avoid" => "none",
        "Departure" => "2020-05-23T17:00:00"
);

$alldata['Locations'] = $Locations;
$alldata['OptimizeParameters'] = $OptimizeParameters;


?>
<div class="content">   

<div id="loading" > 
    <img id="loading-image" src="<?= base_url('') ?>assets/loader.gif"  /> <!-- Loading Image -->
</div>

   
       <!-- <div class="panel-body">
            <h5 class="panel-title">Users Details</h5>
        </div>-->

    <div class="row">
      <div class="techmessage">
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
      </div>
    <?php if(!empty($job_assign_details[0]['pre_service_notification'])): ?>
      <div class="techmessage">
        <div class="alert alert-warning">
           <strong>Notify Customer By:</strong> <?=$job_assign_details[0]['pre_service_notification'];?>
        </div>
      </div>
    <?php endif; ?>


            <div style="background:#f6f7f9;padding: 10px;">
            <table cellspacing="5" cellpadding="5" width="100%">
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td style="padding-left:10px"><h5 class="text-semibold" style="margin:0px;"><?=  $job_assign_details[0]['first_name'].' '.$job_assign_details[0]['last_name'] ?></h5></td>    
                        </tr>
						 <tr> 
                            <td style="padding-left:10px"> <h7 class="text-muted" ><?php if(isset($job_assign_details[0]['phone']) && !empty($job_assign_details[0]['phone'])){ echo "<a href='tel:'".$job_assign_details[0]['phone']."'>".preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $job_assign_details[0]['phone'])."</a>" ;} ?></h7></td>
                        </tr>
                        <tr> 
                            <td style="padding-left:10px"> <h7 class="text-muted" ><?= $property_details->property_address ?></h7></td>
                        </tr>
						<tr> 
                         <td style="padding-top:5px"> 
                             <?php
                            
                                 $_tags=$job_assign_details_tag[0]['tags'];
                                 $iparr = explode(",",$_tags); 
                                 foreach ($iparr as $key => $value) {  
                                   if(isset($value) && $value=="New Customer"){?>
                                     &nbsp;&nbsp;<span class="badge badge-success"><?php echo $value;?> </span>
                                  <?php }elseif(isset($value) && $value != ""){   
                                   ?>
                                     &nbsp;&nbsp;<span class="badge badge-primary"><?php echo $value;?> </span>
                                   <?php 
                                    }  }

                             ?>
                           
                           </td>
                         </tr>
                    </table>
                 </td>           
            </tr>          
          </table>
        </div>    
      </div>

        <div class="row">
          
           <textarea id="postTestRequest" style="display: none;" ><?php echo  json_encode($alldata) ?></textarea>
           
            <div id="geturl" class="row element" style="display: none;" >
              <h5></h5>
              <div id="get_url"></div>
            </div>
            
            <div id="jsonresult" class="row element" style="display: none;" >
              <h5></h5>
              <pre id="result"></pre>
            </div>
            <div class="row">
              <div id='routeMap' style=''></div>
            </div>

        </div>

        <div class="row">      
          <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: #e6e6e6 !important"> 
            <div class="col-lg-12 col-md-12 col-sm-12">           
              <div class="tecnician-btn" style="padding-bottom: 10px !important;">
                <!-- <a class="btn-technician" onclick="initMap()">Get Directions</a>-->
                <a class="btn-technician" onclick="mapsSelector()">Get Directions</a>
              </div>          
            </div>           
          </div>
        </div>

        <?php
        if($job_assign_details[0]['job_name'] == 'Sales Visit'){
          ?>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: #e6e6e6 !important"> 
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="tecnician-btn" style="padding-bottom: 10px !important;">            
                <a class="btn btn-technician btn-property" href="<?= base_url('technician/editProperty/').$job_assign_details[0]['property_id'] ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Edit Property Details</a>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="tecnician-btn" style="padding-bottom: 10px !important;">
                <a class="btn btn-technician btn-estimate " href="<?= base_url('technician/Estimates/addEstimate/').$job_assign_details[0]['property_id'] ?>"  id="" class="btn btn-warning" ><i class=" icon-file-pdf"> </i> Create Estimate</a>
              </div>
            </div>
            <!-- <button type="button"  class="btn btn-warning" id="generate_statement_btn" data-target="#modal_generate_statement" data-toggle="modal"> <i class=" icon-file-pdf"> </i> Create Estimate</button> -->
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="tecnician-btn" style="padding-bottom: 10px !important;">
                <button type="button"  class="btn btn-primary btn-technician btn-pay "id="updatePayment" data-target="<?php if($cardconnect_details && $cardconnect_details->status != 0){?>  #clover_payment_method <?php }else if($basys_details && $basys_details->status != 0){?> #basys_payment_method <?php }  ?>" data-toggle="modal" <?php if(!$job_assign_details){ ?> disabled <?php }else if(!$cardconnect_details && !$basys_details){?> disabled <?php } else if($customerData['clover_autocharge'] == 1 ||$customerData['basys_autocharge'] == 1){?> disabled <?php } ?> > <i class=" icon-plus22"></i> Setup Autopay</button>
                  <div class="col-lg-4">
                    
                    <input type="hidden" name="clover_status"
                        value="<?php echo $cardconnect_details && $cardconnect_details->status == 1 ? 1 : 0 ?>">
                    <input type="hidden" name="basys_status"
                        value="<?php echo $basys_details && $basys_details->status == 1 ? 1 : 0 ?>">
                    <?php
                    if ($cardconnect_details && $cardconnect_details->status != 0){ ?>
                    <input type="hidden" name="customer_clover_token"
                        value="<?php echo isset($customerData['customer_clover_token']) ? $customerData['customer_clover_token'] : '' ?>">
                    <input type="hidden" name="clover_acct_id"
                        value="<?php echo isset($customerData['clover_acct_id']) ? $customerData['clover_acct_id'] : '' ?>">
                    <?php }
                    else if ($basys_details && $basys_details->status != 0){ ?>
                    <input type="hidden" name="basys_customer_id"
                        value="<?php echo isset($customerData['basys_customer_id']) ? $customerData['basys_customer_id'] : '' ?>">
                    <?php } ?>
                  </div>
              </div>
            </div>
          </div>
        </div>
          <?php
        }
        ?>
                

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right-top" style="padding-top: 10px !important;">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion-control-right-top" href="#accordion-control-right-group" aria-expanded="false" class="collapsed" style="font-size:18px;">Service Details</a>
                        </h6>
                    </div>
                    <div id="accordion-control-right-group" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <!-- Start Foreach Service -->
                        <?php foreach($services as $i=>$service) { ?>
                        <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title text-semibold">
                                        <a data-toggle="collapse" data-parent="#accordion-control-right-group" href="#accordion-control-right-group1-<?=$i?>" aria-expanded="false" class="collapsed"><?= $service['job_details']->job_name ?></a>
                                    </h5>
                                </div>
                                <div id="accordion-control-right-group1-<?=$i?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                   <table class="table" style="border: 0 !important">
                                     <tr>
                                           <td><h6>Service Price</h6></td>
                                          <td><?php echo "$".$service['job_details']->job_price ?></td>
                                     </tr>
                                </table>
                                <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right-group1-<?=$i?>" href="#accordion-control-right-group2-<?=$i?>" aria-expanded="false">Product Details</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group2-<?=$i?>" class="panel-collapse collapse" aria-expanded="false">

                                <?php  if (!empty($service['product_details'])) { ?>
                                  <div class="table-responsive" style="min-height:inherit;">

                                      <table class="table" style="border: 0 !important">
                                          <tr style="background-color: #F5F5F5;">

                                            <td ><h6 class="text-semibold">Name</h6></td>
                                            <td ><h6 class="text-semibold">Application Rate</h6></td>
                                            <td ><h6 class="text-semibold">Mixture Application Rate</h6></td>
                                            <td ><h6 class="text-semibold">Wind Speed</h6></td>
                                            <td ><h6 class="text-semibold">Temperature</h6></td>
                                            <td ><h6 class="text-semibold">Notes</h6></td>
                                          </tr>

                                         <?php  foreach ($service['product_details'] as $key => $value) {

                                             if (!empty($value->application_rate) && $value->application_rate !=0) {

                                                  $application_rate = $value->application_rate.' '.$value->application_unit.' / '.$value->application_per;

                                                }else  {
                                                  $application_rate = '';
                                                }

                                                if (!empty($value->mixture_application_rate) && $value->mixture_application_rate !=0) {

                                                  $mixture_application_rate = $value->mixture_application_rate.' '.$value->mixture_application_unit.' / '.$value->mixture_application_per;

                                                }else  {
                                                  $mixture_application_rate = '';
                                                }


                                          ?>
                                          <tr>

                                            <td><?= $value->product_name ?></td>

                                            <td> <?= $application_rate ?> </td>
                                            <td> <?= $mixture_application_rate ?> </td>
                                            <td>

                                                <?php if (!empty($value->max_wind_speed)) {
                                                  echo $value->max_wind_speed.' mph';
                                                 } ?>

                                            </td>
                                            <td>

                                                <?php if (!empty($value->temperature_information)) {
                                                  echo  $value->temperature_information.' degree '.$value->temperature_unit;
                                                 } ?>

                                            </td>
                                            <td>
                                              <?= $value->product_notes  ?>
                                            </td>


                                          </tr>

                                  <?php  } ?>


                                      </table>
                                    </div>
                                  <?php  } else { ?>
                                        <div class="panel-body">
                                            No record found
                                        </div>


                                  <?php  } ?>
                                </div>
                         </div>
                        <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right-group1" href="#accordion-control-right-group3-<?=$i?>" aria-expanded="false">Program Details</a>
                                    </h6>
                                </div>
                                <div id="accordion-control-right-group3-<?=$i?>" class="panel-collapse collapse" aria-expanded="false">

                                     <table class="table" style="border: 0 !important">
                                             <tr>
                                                  <td><h6 class="text-semibold">Program Name</h6></td>
                                                  <td><?= $service['program_details']['program_name'] ?></td>
                                             </tr>

                                             <tr>
                                               <td colspan="1"><h6 class="text-semibold">Program Notes</h6></td>
                                               <td><?= $service['program_details']['program_notes'] ?></td>

                                             </tr>

                                     </table>

                                </div>

                            </div>

                           </div>
                         </div>


                        <?php } ?>
                    <!-- End Foreach Service -->
                    </div>
                  </div>
            </div>
        </div>
	</div> <!--end row -->
	<div class="row">
		   <div class="col-lg-12 col-md-12 col-sm-12">
			   <div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right-top2" style="padding-top: 10px !important;">
					<div class="panel panel-white">
						<div class="panel-heading">
							<h6 class="panel-title">
								<a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right-top2" href="#accordion-control-right-group2" aria-expanded="false" style="font-size:18px;">Yard Information/Notes</a>
							</h6>
						</div>
						<div id="accordion-control-right-group2" class="panel-collapse collapse" aria-expanded="false">
						<?php if (!empty($property_details)) {?>
							 <table class="table" style="border: 0 !important">
								 <tr>
									 <td ><h6 class="text-semibold">Total Yard Square Feet: </h6><?= $property_details->yard_square_feet/1000 ?> K sq. ft.</td>
									 <?php if(isset($property_details->front_yard_square_feet)) { ?>
									   		<td><h6 class="text-semibold">Front Yard Square Feet: </h6><?= $property_details->front_yard_square_feet/1000 ?> K sq. ft.</td>
									 <?php }?>
									 <?php if(isset($property_details->back_yard_square_feet)) { ?>
									   		<td><h6 class="text-semibold">Back Yard Square Feet: </h6><?= $property_details->back_yard_square_feet/1000 ?> K sq. ft.</td>
									 <?php }?>
								 </tr>
								 <tr>
									 <?php if(isset($property_details->total_yard_grass)) { ?>
									   		<td><h6 class="text-semibold">Total Yard Grass Type: </h6><?= $property_details->total_yard_grass ?></td>
									 <?php }?>
									 <?php if(isset($property_details->front_yard_grass)) { ?>
									   		<td><h6 class="text-semibold">Front Yard Grass Type: </h6><?= $property_details->front_yard_grass ?></td>
									 <?php }?>
									 <?php if(isset($property_details->back_yard_grass)) { ?>
									   		<td><h6 class="text-semibold">Back Yard Grass Type: </h6><?= $property_details->back_yard_grass ?></td>
									 <?php }?>
								 </tr>
								 <tr>
									 <td><h6 class="text-semibold">Property Info: </h6><?= $property_details->property_notes ?></td>
								 </tr>

							 </table>

						<?php } else { ?>
                             <div class="panel-body">
                               No record found
                             </div>
                        <?php }  ?>     
						</div>
                  </div>
			</div>                            
		</div>
	</div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right-top3" style="padding-top: 10px !important;">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion-control-right-top3" href="#accordion-control-right-group3" aria-expanded="false" class="collapsed" style="font-size:18px;">Service History</a>
                        </h6>
                    </div>
                    <div id="accordion-control-right-group3" class="panel-collapse collapse" aria-expanded="false" style="max-height: 300px;overflow-y: scroll ">
                        <!-- Start Foreach Service -->

                      <div  class="table-responsive ">
                                    <table  class="table " style=" overflow-y: scroll">
                                        <thead>
                                        <tr>

                                            <th>Service Name</th>
                                            <th>User</th>
                                            <th>Date of Completion</th>

                                        </tr>
                                        </thead>
                                        <tbody>


                                        <?php if (!empty($report_details)) {   foreach ($report_details as $value) { ?>

                                            <tr>

                                                <td><?= $value->job_name ?></td>
                                                <td style="text-transform: capitalize;"><?= $value->user_first_name.' '.$value->user_last_name ?></td>
                                                <td><?= date('m-d-Y', strtotime($value->job_completed_date))  ?></td>

                                            </tr>

                                        <?php  } }else { ?>

                                            <tr>
                                                <td colspan="3"> No record found </td>

                                            </tr>

                                        <?php }  ?>

                                        </tbody>
                                    </table>
                                </div>



                        <!-- End Foreach Service -->
                    </div>
                </div>
            </div>
        </div>
    </div> <!--end row -->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right-top4" style="padding-top: 10px !important;">
				<div class="panel panel-white">
					<div class="panel-heading">
						<h6 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right-top4" href="#accordion-control-right-group4" aria-expanded="false" style="font-size:18px;">Property Conditions</a>
						</h6>
					</div>
					<div id="accordion-control-right-group4" class="panel-collapse collapse" aria-expanded="false">
						<div class="panel-body" style="border:none;">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<h6 class="text-semibold">Active Conditions: </h6>
									<?php if (!empty($property_conditions)) {?>
									<?php foreach($property_conditions as $condition){?>
									<div class="btn btn-primary property-condition-button" id="prop_cond_<?=$condition->property_condition_assign_id?>">
										<?= $condition->condition_name?>
										<button type="button" class="close removePropertyConditionClose" aria-label="Close" onclick="removePropertyCondition(<?=$condition->property_condition_assign_id ?>)"><span aria-hidden="true">&times;</span></button>
									</div>
									<?php } ?>
									<?php } else { ?>
									<p>Observed Conditions have not been added for this Property</p>
									<?php }  ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	   <?php if ( !empty($job_assign_details[0]['email']) && $job_assign_details[0]['is_email'] == 1 ) { ?>
       <div class="row">
		   <div class="col-lg-12 col-md-12 col-sm-12">
			   <div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right-top5" style="padding-top: 10px !important;">
				  <div class="panel panel-white">
					<div class="panel-heading">
						<h6 class="panel-title">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right-top5" href="#accordion-control-right-group5" aria-expanded="false" style="font-size:18px;">Message to Customer</a>
						</h6>
					</div>
					<div id="accordion-control-right-group5" class="panel-collapse collapse" aria-expanded="false">

						  <form action="<?= base_url('technician/SendCustomerEmail') ?>"  id="sendemail" method="post" name='customeremail'> 

							  <table class="table" style="border: 0 !important">
								 <tr>
									  <td ><h5 class="text-semibold"><input type="text" name="email" class="form-control" readonly="" value="<?= $job_assign_details[0]['email'] ?>"></h5></td>
								 </tr>

								 <tr>
									  <td><textarea class="form-control" placeholder="Enter Your Message"  rows="5" name="message" ></textarea> </td>
								 </tr>
								 <tr> <td> <button type="submit" id="sendmsgbt"  class="btn btn-success text-right">Send</button></td></tr>
							  </table>
							  <input type="hidden" name="customer_id" value="<?= $job_assign_details[0]['customer_id'] ?>">
					   </form>
					</div>
				</div>
			   </div>
		   </div>
		</div>
   <?php } ?>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right-top6" style="padding-top: 10px !important;">
          <div class="panel panel-white">
            <div class="panel-heading">
              <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right-top6" href="#accordion-control-right-group6" aria-expanded="false" style="font-size:18px;">Internal Customer/Property Notes</a>
              </h6>
            </div>
            <div id="accordion-control-right-group6" class="panel-collapse collapse" aria-expanded="false">

              <!-- Nested Note Create Form -->
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right-top6-1" style="padding-top: 10px !important;">
                    <div class="panel panel-white">
                      <div class="panel-heading" style="background-color:#e6e6e6!important;padding-left:10px;padding-right:10px;">
                        <h6 class="panel-title">
                          <button class="collapsed btn btn-technician" data-toggle="collapse" data-parent="#accordion-control-right-top6-1" href="#accordion-control-right-group6-2" aria-expanded="false" style="background: #0190d9; border: none;">Create New Note</button>
                        </h6>
                      </div>
                      <div id="accordion-control-right-group6-2" class="panel-collapse collapse" aria-expanded="false"> 

                        <!-- Form Start -->
                        <form class="form-horizontal" action="<?= base_url('technician/createNote'); ?>" method="post" name="createnoteform" enctype="multipart/form-data" id="createnoteform" onsubmit="formFileSizeValidate(this)">
                            <!-- <fieldset class="content-group"> -->
                              <input type="hidden" name="note_property_id" class="form-control" value="<?= $property_details->property_id; ?>" >
                              <input type="hidden" name="note_category" class="form-control" value="0" >
                              <div class="row">
                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <label class="control-label col-lg-3">Note Type</label>
                                    <div class="col-lg-9">
                                      <select class="form-control" name="note_type" placeholder="">
                                        <option value="" disabled selected></option>
                                        <?php foreach($note_types as $type) : ?>
                                          <option value="<?= $type->type_id; ?>"><?= $type->type_name; ?></option>
                                        <?php endforeach; ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>
							  <div class="col-xs-12 assignservicesedicustumer" id="assignservicesedicustumer">
								 <div class="form-group">
								   <label class="control-label col-lg-3">Assign Services</label>
								   <div class="col-lg-9">
									 <select class="form-control" name="note_assigned_services">
									   <option value="">None</option>
									   <?php 
										 foreach($allservicelist as $service)
										 {
										 ?>
										   <option value="<?= $service->job_id; ?>"><?= $service->job_name; ?></option>
										 <?php
										 }
									   ?>
									 </select>
									 <span style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>
								   </div>
								 </div>
							   </div>
							   <div class="col-xs-12 assignservicesedicustumer">
								 <div class="form-group">
								   <label class="control-label col-lg-3">Note Duration</label>
								   <div class="col-lg-9">
									 <select class="form-control" name="assigned_service_note_duration">
									   <option value="">None</option>
									   <option value=1>Permanent</option>
									   <option value=2>Next Service Only</option>
									 </select>
									 <span style="color:red;"><?php echo form_error('assigned_service_note_duration'); ?></span>
								   </div>
								 </div>
							   </div>
                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <label class="control-label col-lg-3">Assign User</label>
                                    <div class="col-lg-9">
                                      <select class="form-control" name="note_assigned_user">
                                        <!-- Add Users available within company with Value = user_id / option shown user_name -->
                                        <option value="">None</option>
                                        <?php 
                                          foreach($userdata as $user)
                                          {
                                          ?>
                                            <option value="<?= $user->id; ?>"><?= $user->user_first_name.' '.$user->user_last_name; ?></option>
                                          <?php
                                          }
                                        ?>
                                      </select>
                                      <span style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <label class="control-label col-lg-3">Due Date</label>
                                    <div class="col-lg-9">
                                      <input id="note_due_date" type="date" name="note_due_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                                    </div>
                                  </div>
                                </div> 

                              </div>

                              <input type="hidden" name="include_in_tech_view" value="1">

                              <div class="row row-extra-space">
                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <label class="control-label col-lg-4 text-right">Attach Documents</label>
                                    <div class="col-lg-8 text-left">
                                      <input id="files" type="file" name="files[]" class="form-control-file" multiple onchange="fileValidationCheck(this)">
                                      <span style="color:red;"><?php echo form_error('files'); ?></span>
                                    </div>
                                  </div>
                                </div>
                                                          
                                
                              </div>
                              <div class="row">

                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <label class="control-label col-lg-1">Note Contents</label>
                                    <div class="col-lg-11">
                                      <textarea class="form-control" name="note_contents" id="note_contents" rows="5"></textarea>
                                      <span style="color:red;"><?php echo form_error('note_contents'); ?></span>
                                    </div>
                                  </div>
                                </div>         
                              </div>
                            <!-- </fieldset> -->
                            <div class="text-right btn-space">
                              <button type="submit" id="savenote" class="btn btn-success">Save <i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </form>
                        <!-- Form End -->

                      </div>
                    </div>
                  </div>
                </div>
              </div>   
              <!-- Form Container End -->
      <?php 
      $isTech = (isset($this->session->userdata['spraye_technician_login'])) ? 1 : 0;
      $currentUser = ($isTech) ? $this->session->userdata['spraye_technician_login'] : $this->session->all_userdata();
                      if(!empty($enhanced_notes))
                      { 
                        foreach($enhanced_notes as $note)
                        { 
                          if($note->include_in_tech_view)
                          {
                            ?>                    
                    <div class="well note-element<?= ($note->note_category == 0) ? ' property-note' : ' customer-note' ?>" data-note-id="<?= $note->note_id; ?>">
                      <div class="row note-header">
                        <div class="col-xs-9 col-md-8 user-info">
                          <!-- <div class="user-image">
                            <i class="fa fa-user-circle-o text-primary fa-4x" aria-hidden="true"></i>
                          </div> -->
                          <div class="note-details">
                            <h3 class="text-bold media-heading box-inline text-primary"><?= $note->user_first_name.' '.$note->user_last_name; ?></h3>
                            <p class="text-muted"><?= $note->note_created_at; ?></p>
                          </div>                          
                        </div>
                        <div id="note-header-right" class="col-xs-3 col-md-4 pull-right text-right">
                          <div class="dropdown">
                            <span class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                              <i class="icon-menu" aria-hidden="true"></i>
                            </span>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                              <li class="dropdown-header text-bold text-uppercase">Actions</li>
                              <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>" id="note-assign-btn-<?= $note->note_id; ?>"><a href="javascript:showAssignUserSelect(<?= $note->note_id; ?>)"><i class="fa fa-user-circle-o" aria-hidden="true"></i>Assign Specific User</a></li>
                              <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>"><a href="javascript:showDueDateSelect(<?= $note->note_id; ?>)"><i class="fa fa-calendar" aria-hidden="true"></i>Edit Due Date</a></li>
                              <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2) ? ' disabled' : ''; ?>"><a href="javascript:showNoteTypeSelect(<?= $note->note_id; ?>)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Change Note Type</a></li>
                              <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($note->note_status == 2 || $currentUser->id !== $note->note_assigned_user && $currentUser->id !== $note->note_user_id) ? ' disabled' : ''; ?>"><a href="<?= base_url('technician/markNoteComplete/').$note->note_id; ?>"><i class="fa fa-check-square-o" aria-hidden="true"></i>Mark Complete</a></li>
                              <li class="dropdown-menu-item text-muted dropdown-menu-item-icon<?= ($currentUser->id !== $note->note_assigned_user && $currentUser->id !== $note->note_user_id) ? ' disabled' : ''; ?>"><a href="<?= base_url('technician/deleteNote/').$note->note_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete Note</a></li>
                              <!-- <li role="separator" class="divider"></li> -->
                            </ul>
                          </div>
                        </div>

                        <div class="row note-header mobile-secondary-header">
                          <div class="col-xs-12">
                          <?php if(!empty($note->note_assigned_user)) : ?>
                            <span id="note-assigned-user-wrap-<?= $note->note_id; ?>"><span>Assigned to&nbsp;</span><span class="text-success text-bold">
                              <?php 
                              for($i=0; $i<count($userdata); $i++)
                              {
                                if($note->note_assigned_user == $userdata[$i]->id)
                                {
                                  echo $userdata[$i]->user_first_name.' '.$userdata[$i]->user_last_name;
                                  break;
                                }
                              }
                              ?>
                              </span></span>
                          <?php endif; ?>
                            <div class="form-group hidden" id="update-assignuser-<?= $note->note_id; ?>">
                              <label class="control-labe col-xs-12 col-lg-3">Assign User</label>
                              <div class="col-xs-12 col-lg-12">
                                <select class="form-control" name="note_assigned_user" id="note_assigned_user_<?= $note->note_id; ?>" data-note-id="<?= $note->note_id; ?>" data-note-userid="<?= $note->note_user_id; ?>" onchange="getNoteAssignUserUpdateVars(this)">
                                  <!-- Add Users available within company with Value = user_id / option shown user_name -->
                                  <option value="" <?= (empty($note->note_assigned_user)) ? 'selected':''; ?>>None</option>
                                  <?php 
                                    foreach($userdata as $user)
                                    {
                                      if($note->note_user_id)
                                      { ?>
                                      <option value="<?= $user->id; ?>" <?= ($user->id == $note->note_assigned_user) ? 'selected':''; ?>><?= $user->user_first_name.' '.$user->user_last_name; ?></option>
                                    <?php }
                                    }
                                  ?>
                                </select>
                                <span style="color:red;"><?php echo form_error('note_assigned_user'); ?></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row note-body">
                        <div class="col-xs-12 col-md-12">
                          <p><?= $note->note_contents; ?></p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                        
                        <?php
                        if($note->note_type == '1')
                        {
                        ?>
                          
                          <span id="note-assigned-type-wrap-<?= $note->note_id; ?>" class="text-bold text-success" style="font-size: 1.2em">Task</span>
                        <?php
                        } else
                        {
                          foreach($note_types as $type) 
                          {
                            if($note->note_type == $type->type_id)
                            {
                              $type_name = $type->type_name;
							  	
                            }   
                          } 
                          ?>
                         <?php if(isset($type_name)) { ?>
                          <span id="note-assigned-type-wrap-<?= $note->note_id; ?>" class="text-bold text-success" style="font-size: 1.2em"><?= $type_name; ?></span>
                         <?php if($type_name == "Service-Specific" && $service_specific_note_type_id == $note->note_type){
									$duration = "";
									if(isset($note->assigned_service_note_duration) && $note->assigned_service_note_duration == 1){
										$duration = " (Permanent)";
									}elseif(isset($note->assigned_service_note_duration) && $note->assigned_service_note_duration == 2){
										$duration = " (Next Service Only)";
									}
									if(isset($note->job_name) && !empty($note->job_name)){ ?>
										<br><span id="note-assigned-service-wrap-<?= $note->note_id; ?>" class="text-success" style="font-size: 1.2em"><?= $note->job_name ?><?= $duration ?></span>
						  <?php 	} 
						  		}
							 }
                          } 
                        ?>
						</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group hidden" id="update-notetype-<?= $note->note_id; ?>">
								<label class="control-label">Note Type</label>
								  <select class="form-control col-sm-12" name="note_edit_type" id="note_edit_type_<?= $note->note_id; ?>" data-note-id="<?= $note->note_id; ?>" data-note-typeid="<?= $note->note_type; ?>" onchange="getNoteTypeUpdateVars(this)">
									<!-- Add types available within company with Value = type_id / option shown type_name -->
									<option value="" disabled selected></option>
									<?php foreach($note_types as $type) : ?>
									  <option value="<?= $type->type_id; ?>"><?= $type->type_name; ?></option>
									<?php endforeach; ?>
								  </select>
								  <span style="color:red;"><?php echo form_error('note_type'); ?></span>
							  </div>
							</div>                        
                      </div>
                      <hr>
                      <div class="row note-footer note-footer-flex">
                        <div class="col-xs-12 col-md-7 note-footer-left">
                          <div class="row">
                            <div class="status col-xs-6 col-md-4 text-left text-bold">
                              <?php
                              if(!empty($note->note_status))
                              {
                              ?>
                              Status: <?= ($note->note_status == 1) ? '<span class="text-warning">OPEN</span>' : '<span class="text-success">CLOSED</span>'; ?>
                              <?php
                              }
                              else 
                              {
                              ?>
                                Status: <span class="text-muted">None</span>
                              <?php 
                              }
                              ?>
                            </div>
                            <div class="col-xs-6 col-md-8 note-due-date text-warning text-uppercase text-bold text-right">
                              DUE: <i id="note-duedate-<?= $note->note_id; ?>"><?= ($note->note_due_date != '0000-00-00' && !empty($note->note_due_date)) ? $note->note_due_date : 'None Set'; ?></i><input id="note_due_date_<?= $note->note_id; ?>" type="date" name="note_due_date" class="form-control pickaalldate hidden" placeholder="YYYY-MM-DD" data-noteid="<?= $note->note_id; ?>" onchange="updateNoteDueDate(this)">
                            </div>                            
                            <?php 
                            if(isset($note->first_name) && isset($note->last_name))
                            { 
                            ?>                   
                              <div class="customer-name col-xs-6 col-md-4 col-lg-3 text-bold">
                                <?= $note->first_name; ?> <?= $note->last_name; ?>
                              </div>
                            <?php
                            }
                            ?>
                            <?php
                            if(isset($note->property_address) && isset($note->property_city))
                            { 
                            ?>
                              <div class="customer-address col-xs-12 col-md-8 col-lg-6 text-bold text-muted">
                                <?= $note->property_address; ?>, <?= $note->property_city; ?>
                              </div>
                            <?php
                            }
                            ?>                            

                          </div>
                        </div>
                        <div class="col-xs-12 col-md-4 note-footer-right">
                          <div class="row">
                            <div class="col-xs-6 col-md-6 note-comments text-left note-ico-btns">
                              <i class="icon-bubble btn-ico" aria-hidden="true" data-toggle="collapse" data-target="#note-comments-<?= $note->note_id; ?>" aria-expanded="false" aria-controls="note-comments-<?= $note->note_id; ?>"></i> <span id="comment-count-value-<?= $note->note_id; ?>"><?= count($note->comments); ?></span>
                            </div>
                            <div class="col-xs-6 col-md-6 note-attachments text-right note-ico-btns">
                              <i class="icon-attachment btn-ico" aria-hidden="true" data-toggle="collapse" data-target="#note-files-<?= $note->note_id; ?>" aria-expanded="false" aria-controls="note-files-<?= $note->note_id; ?>"></i> <?= count($note->files); ?>
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
                                  <small class="text-muted"><?= $comment->comment_created_at ?></small> <strong><?= $comment->user_first_name.' '.$comment->user_last_name; ?>: </strong><?= $comment->comment_body; ?>
                                </li>
                              <?php } ?>
                              <li class="list-group-item comment-list-item">
                                <form action="<?= base_url('technician/addNoteComment') ?>" method="post" name="add-note-comment-form" enctype="multipart/form-data" id="add-note-comment-form-<?= $note->note_id; ?>" onsubmit="addCommentAjax('<?= $note->note_id; ?>')">
                                  <input type="hidden" value="<?= $currentUser->id; ?>" name="comment-userid">
                                  <input type="hidden" value="<?= $note->note_id; ?>" name="comment-noteid">
                                  <input type="hidden" value="<?= $note->note_type; ?>" name="comment-notetype">
                                  <div class="input-group">
                                    <input class="form-control" name="add-comment-input" id="add-comment-input" placeholder="Add Comment">
                                    <div class="input-group-btn">
                                      <!-- Buttons -->
                                      <button type="submit" class="btn btn-primary pull-right">Post Comment</button>
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
                          <div class="col-xs-9">
                            <h4><strong>ATTACHMENTS</strong></h4>
                          </div>
                          <div class="col-xs-3">
                            <i class="icon-cloud-upload btn-ico" aria-hidden="true" data-toggle="collapse" data-target="#note-fileupload-<?= $note->note_id; ?>" aria-expanded="false" aria-controls="note-fileupload-<?= $note->note_id; ?>"></i>
                          </div>
                          <div class="col-xs-12 collapse" id="note-fileupload-<?= $note->note_id; ?>">
                            <form action="<?=  base_url('technician/addToNoteFiles'); ?>" method="post" enctype="multipart/form-data" onsubmit="formFileSizeValidate(this)">
                              <input type="hidden" value="<?= $currentUser->id; ?>" name="user_id">
                              <input type="hidden" value="<?= $note->note_id; ?>" name="note_id">
                              <input type="hidden" value="<?= $note->note_type; ?>" name="note_type">   
                              <input type="hidden" value="<?= $property_details->property_id; ?>" name="property_id">                         
                              <div class="row row-extra-space">
                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <label class="control-label col-lg-4 text-right">Attach Documents</label>
                                    <div class="col-lg-8 text-left">
                                      <input id="files" type="file" name="files[]" class="form-control-file" multiple onchange="fileValidationCheck(this)">
                                      <span style="color:red;"><?php echo form_error('files'); ?></span>
                                    </div>
                                  </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                              </div>                                
                            </form>
                          </div>
                          <div class="row">
                            <div class="col-xs-12 col-md-12">
                              <hr>
                              <div class="row">
                                <?php foreach ($note->files as $file) 
                                { 
                                  $ext = pathinfo( CLOUDFRONT_URL.$file->file_key, PATHINFO_EXTENSION);
                                  if($ext == 'pdf') 
                                  { ?>
                                  <div class="col-xs-6 col-md-2 text-center">
                                    <label><?= $file->file_name; ?></label><br>
                                    <a href="<?= CLOUDFRONT_URL.$file->file_key; ?>" target="_blank">
                                      <i class="icon-file-text2 file-attach-icon" aria-hidden="true"></i>
                                    </a>
                                  </div>
                                  <?php } else { ?>
                                  <div class="col-xs-6 col-md-2 text-center">
                                    <label><?= $file->file_name; ?></label>
                                    <img src="<?= CLOUDFRONT_URL.$file->file_key; ?>" alt="<?= $file->file_name; ?>" alt="<?= $file->file_name; ?>" class="img-responsive thumbnail files-thumbnail" onclick="imgDisplay(this)">
                                    <!-- <img src="<?= CLOUDFRONT_URL.$file->file_key; ?>" alt="<?= $file->file_name; ?>" class="img-responsive thumbnail files-thumbnail" onclick="displayFileModal(this)"> -->
                                  </div>
                                <?php } 
                                } ?>
                              </div>                            
                            </div>
                          </div>
                        </div>
                      </div>                         
                    </div>
                    <?php 
                          }
                        }
                      } else 
                      { ?>
                    <div class="well property-note">
                      <p>No Notes to Display Yet...</p>
                    </div>
                    <?php } ?>
                  </div>              
            </div>
          </div>
        </div>
      </div>
    </div>         

    <?php if ( !empty($setting_details->tech_add_standalone_service) && $setting_details->tech_add_standalone_service == 1 &&
    isset($job_assign_details[0]['job_name']) && $job_assign_details[0]['job_name'] != 'Sales Visit' ) { ?>
<!---- START, COMPLETE, SKIP, ADD SERVICE BUTTONS --->		
	 <div class="row">
		 <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: #e6e6e6 !important"> 
			 <div class="col-lg-3 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					 <button id='startTime' class="btn btn-technician btn-start-time" >Start Service</button>
					</div>          
			  </div>
			  <div class="col-lg-3 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					   <!-- <button disabled=""  id='completejob' class="btn btn-technician btn-complete" data-toggle="modal" data-target="#modal_mixture_application"   >Complete Service </button> -->
             <button disabled=""  id='completejob' class="btn btn-technician btn-complete" data-toggle="modal" data-target="#<?= ($is_tech_customer_note_required == 1) ? 'modal_required_customer_note' : 'modal_mixture_application'; ?>"   >Complete Service </button>
				  </div>          
			  </div>
			  <div class="col-lg-3 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					<a class="btn-technician btn-reschedule" data-toggle="modal" data-target="#modal_reschedule"id='reschedulejob' >Skip Service/Reschedule</a>
					</div>          
			  </div>
			  <div class="col-lg-3 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					  <button id='addjob' class="btn btn-technician btn-addservice" data-toggle="modal" data-target="#modal_add_service" >Add Service</button>
					      
					</div>          
			  </div> 
		  </div>
	 </div>
     <?php } else if(isset($job_assign_details[0]['job_name']) && $job_assign_details[0]['job_name'] != 'Sales Visit' ) { ?>
        <div class="row">
		 <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: #e6e6e6 !important"> 
			 <div class="col-lg-4 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					 <button id='startTime' class="btn btn-technician btn-start-time" >Start Service</button>
					</div>          
			  </div>
			  <div class="col-lg-4 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					   <!-- <button disabled=""  id='completejob' class="btn btn-technician btn-complete" data-toggle="modal" data-target="#modal_mixture_application"   >Complete Service </button> -->
             <button disabled=""  id='completejob' class="btn btn-technician btn-complete" data-toggle="modal" data-target="#<?= ($is_tech_customer_note_required == 1) ? 'modal_required_customer_note' : 'modal_mixture_application'; ?>"   >Complete Service </button>
				  </div>          
			  </div>
			  <div class="col-lg-4 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					<a class="btn-technician btn-reschedule" href="<?=  base_url('technician/rescheduleJobMultiple/').$tech_assign_ids ?>" id='reschedulejob' >Skip Service/Reschedule</a>      
					</div>          
			  </div>
		  </div>
	 </div>
	<?php } else { ?>
	<div class="row">
		 <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: #e6e6e6 !important"> 
			 <div class="col-lg-4 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					 <button id='startTimeSales' class="btn btn-technician btn-start-time" >Start Service</button>
					</div>          
			  </div>
			  <div class="col-lg-4 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					   <button disabled=""  id='completejobsales' class="btn btn-technician btn-complete" data-toggle="modal" data-target="#modal_sales_visit"   >Complete Service </button>
				  </div>          
			  </div>
			  <div class="col-lg-4 col-md-3 col-sm-12">           
				  <div class="tecnician-btn" style="padding-bottom: 10px !important;">
					<a class="btn-technician btn-reschedule" href="<?=  base_url('technician/rescheduleJobMultiple/').$tech_assign_ids ?>" id='reschedulejob' >Skip Service/Reschedule</a>      
					</div>          
			  </div>
		  </div>
	 </div>
	<?php } ?>
</div>

 
<!--- COMPLETE JOB FORM --->
<div id="modal_mixture_application" class="modal fade" data-backdrop="static" style="overflow-y: scroll; -webkit-overflow-scrolling: touch">
   <div class="modal-dialog">
      <div class="modal-content" >
         <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Complete Service</h6>
         </div>
         <form action="<?=  base_url('technician/completeJobMultiple/').$tech_assign_ids ?>" name="completejobform" method="post" enctype='multipart/form-data' id="job_completion_form">
			 <input type="hidden" name="prog_price" id="prog_price" value=<?= $job_assign_details[0]['program_price']?> >
			 <input type="hidden" name="is_group_billing" id="is_group_billing" value=<?= $job_assign_details[0]['billing_type']?> >
			 <input type="hidden" name="next_service_only_note_ids" id="next_service_only_note_ids" value="<?= $next_service_only_note_ids?>" >
             <?php if(isset($job_assign_details[0]['clover_autocharge']) && $job_assign_details[0]['clover_autocharge'] == 1){ ?>
			 	<input type="hidden" name="clover_autocharge" id="clover_autocharge" value=1>
			<?php }else{ ?> 
			 	<input type="hidden" name="clover_autocharge" id="clover_autocharge" value=0>
			<?php } ?>
           <?php if(isset($job_assign_details[0]['basys_autocharge']) && $job_assign_details[0]['basys_autocharge'] == 1){ ?>
			 	<input type="hidden" name="basys_autocharge" id="basys_autocharge" value=1>
			<?php }else{ ?> 
			 	<input type="hidden" name="basys_autocharge" id="basys_autocharge" value=0>
			<?php } ?>
			<?php if (!empty($job_assign_details[0]['email']) && $job_assign_details[0]['is_email'] == 1/* && strpos($job_assign_details[0]['pre_service_notification'],'Pre-Notified') != 0 */) { ?>
			 		<input type="hidden" name="customer_email" id="customer_email" value="1">
			 <?php }elseif(!empty($job_assign_details[0]['secondary_email']) && $job_assign_details[0]['is_email'] == 1 && strpos($job_assign_details[0]['pre_service_notification'],'Pre-Notified') != 0 ){?>
			 		<input type="hidden" name="customer_email" id="customer_email" value="1">
			 <?php }else{?>
			 		<input type="hidden" name="customer_email" id="customer_email" value="0">
			 <?php }?>
            <div class="modal-body">
				<h5 class="text-semibold">How many gallons of mixture did you use?</h5>

				<?php foreach($services as $service){
                    ?>
                    <h6 class="text-semibold"><?= $service['job_details']->job_name ?></h6>
                    <div class="form-group" >
                        <div class="row">
                            <?php
                            if (!empty($service['product_details_for_cal'])) {
                                $listed_products = [];
                                $hidden = '';
                                foreach ($service['product_details_for_cal'] as $key => $value) {
                                    array_push($listed_products, $value->product_id);
                                }
                                if (empty( $listed_products))  {
                                    $hidden = 'style="display: none"';
                                }



                            $numOfCols = 2;
                            $count_visible_products = 0;

                            $rowCount = 0;



                            foreach ($service['product_details_dif'] as $key => $value) {

                                if ($value->mixture_application_per != '') {
                                  $re = 0;
                                  $reduced = reduceToOneAcre($value->mixture_application_rate, $value->mixture_application_rate_per);
                                  if ($value->mixture_application_per == '1 Acre') {
                                    $re = $reduced / 43560;
                                  } else {
                                    $re = ($value->mixture_application_rate) / 1000;
                                  }
                                  $used_mixture =  $re * $property_details->yard_square_feet;
                                  $used_mixture =  number_format($used_mixture, 2);
                                  $used_mixture =  floatval($used_mixture);
                                } else {
                                  $used_mixture = 0;
                                }

                                $hidden = '';
                                $disable = '';
                                 if (!in_array($value->product_id, $listed_products))  {
                                     $hidden = 'display: none';
                                     $disable = 'disabled';
                                 }
                                 else
                                 $count_visible_products =  $count_visible_products + 1;
                                ?>

                                    <div id="<?=$service['job_details']->job_id.'_'.$value->product_id?>" class="col-sm-6 col-md-6" style="margin: 7px 0; <?= $hidden ?>" >
                                      <label><?= $value->product_name ?></label>
                                      <div class="input-group" id="productList" >
                                            <input type="number" id="<?='input_'.$service['job_details']->job_id.'_'.$value->product_id?>"  name="<?php echo $service['technician_job_assign_id'].'['.$value->product_id.']' ?>"  class="form-control" value="<?= $used_mixture  ?>" placeholder="" <?= $disable?>>
                                            <span class="input-group-btn">
                                               <span class="btn btn-success"><?= $value->mixture_application_unit ?></span>
                                            </span>
                                      </div>
                                    </div>
                                   <?php  } ?>
                              </div>

                           </div>
                            <div class="form-group" id="add_product_div_<?=$service['job_details']->job_id?>" style="display: none">
                                <div class="row" style="margin-left: 2%">
                                    <div class="col-4">Select Product:</div>
                                    <div class="col-8">
                                        <div class="multi-select-full col-lg-8  col-sm-10 col-xs-10">
                                            <select class="multiselect-select-all form-control" id="add_product_select_<?=$service['job_details']->job_id?>" class="multiselect-select-all product-select" multiple="multiple"
                                                    name="product_id_arra" id="product_lists">
                                                <?php
                                                $included_products_id_list = [];
                                                $not_included_products_id_list = [];
                                                foreach ($service['product_details_dif'] as $value){
                                                    //echo $value->product_id;
                                                    array_push($included_products_id_list, $value->product_id);
                                                }
                                                foreach ($service['product_details_dif'] as $value){
                                                    //echo $value->product_id;
                                                    if (!in_array($value->product_id, $listed_products)) {
                                                        array_push($not_included_products_id_list, $value->product_id);
                                                    }
                                                }
                                                if (!empty($product_details) ) {
                                                    foreach ($product_details as $value) {
                                                        if (in_array($value->product_id, $not_included_products_id_list)){
                                                            echo '<option value="' . $value->product_id . '">' . $value->product_name . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <a id="add_product_link_<?=$service['job_details']->job_id?>" onclick='show_selec_products("<?=$service['job_details']->job_id?>")' style="margin-left: 2%"> + Add product</a>
                                </div>
                            </div>

                           <?php }  else { ?>
                <p>Products are not available</p></div></div>
                           <?php  } ?>

                        <?php } ?>

              <?php 
            //   print_r($propertyconditionslist);
            //   print_r($job_assign_details[0]['is_email']);
                  if ($job_assign_details[0]['is_email']==1) { ?>
                  
              <hr>
              <h6 class="text-semibold">Add Property Conditions</h6>

				<div class="form-group">
					<div class="row">
                    <input type="hidden" name="property_id" value="<?= $property_details->property_id; ?>" />
						<div class="multi-select-full col-md-12">
							
							<select class="multiselect-select-all form-control" name="property_conditions[]" multiple="multiple" id="property_conditions_list">
								<?php if(isset($propertyconditionslist)){ 
                                    
                                    foreach($propertyconditionslist as $condition){
					  				if(in_array($condition->property_condition_id,$selectedpropertyconditions)){ ?>
										<option value="<?php echo $condition->property_condition_id; ?>" selected><?php echo $condition->condition_name; ?></option>
									<?php } else { ?>
										$<option value="<?php echo $condition->property_condition_id; ?>"><?php echo $condition->condition_name; ?></option>
									<?php } ?>
								
							<?php }
                        } ?>
							</select>
						</div>
					</div>
				</div>
				<hr>
              <h6 class="text-semibold">Type a message to the customer below to be included with the Service completion email.</h6>
              
               <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                   <textarea class="form-control" name="message"></textarea>
                  </div>
                    
                </div>
               </div>
          
               <hr>   
             <?php } else { ?>
                  <input type="hidden" name="message">
             <?php }
              if($is_tech_customer_note_required == 1)
                {
              ?>
               <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                   <input type="hidden" name="is_tech_customer_note_required" value="1">
                   <input type="hidden" name="requiredNoteId" id="requiredNoteId" value="">
                  </div>
                </div>
               </div>     
                <?php
                }
                ?>               
               <div class="modal-footer">
                  <button type="button" class="btn btn-link" data-dismiss="modal" id="cancel_completejobform">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Continue</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<!---- END COMPLETE JOB MODAL ---->
<!--- COMPLETE JOB FORM Sales --->
<div id="modal_sales_visit" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Complete Service</h6>
         </div>
         <form action="<?=  base_url('technician/completeJobMultiple/').$tech_assign_ids ?>" name="completejobformsales" method="post">
			 <input type="hidden" name="prog_price" id="prog_price" value=<?= $job_assign_details[0]['program_price']?> >
           <?php if(isset($job_assign_details[0]['basys_autocharge']) && $job_assign_details[0]['basys_autocharge'] == 1){ ?>
			 	<input type="hidden" name="basys_autocharge" id="basys_autocharge" value=1>
			<?php }else{ ?> 
			 	<input type="hidden" name="basys_autocharge" id="basys_autocharge" value=0>
			<?php } ?>
			<?php if (!empty($job_assign_details[0]['email']) && $job_assign_details[0]['is_email'] == 1 ) { ?>
			 		<input type="hidden" name="customer_email" id="customer_email" value=1>
			 <?php }elseif(!empty($job_assign_details[0]['secondary_email']) && $job_assign_details[0]['is_email'] == 1){?>
			 		<input type="hidden" name="customer_email" id="customer_email" value=1>
			 <?php }else{?>
			 		<input type="hidden" name="customer_email" id="customer_email" value=0>
			 <?php }?>
            <div class="modal-body">
				

              <?php 
                  if ($job_assign_details[0]['is_email']==1) { ?>
            
              <h6 class="text-semibold">Type a message to the customer below to be included with the Service completion email.</h6>
              
               <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                   <textarea class="form-control" name="message"></textarea>                    
                  </div>
                    
                </div>
               </div>   
             <?php } else { ?>
                  <input type="hidden" name="message">
             <?php } ?>
				          
               <div class="modal-footer">
                  <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Continue</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<!---- END COMPLETE JOB MODAL Sales ---->
<!--- Reschedule FORM  --->
<div id="modal_reschedule" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Reschedule</h6>
            </div>
            <form action="<?=  base_url('technician/rescheduleJobMultiple/').$tech_assign_ids ?>" name="reschedulejobform" method="post">

                <input type="hidden" name="prog_price" id="prog_price" value=<?= $job_assign_details[0]['program_price']?> >

                <div class="modal-body">


                    <?php
                    if ($job_assign_details[0]['is_email']==1) { ?>

                        <h6 class="text-semibold">Reschedule Reason</h6>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <select class="form-control" name="reason_id" id="reason_id"  required>
                                        <option value="">Select Any Reason</option>
                                        <?php

                                        if(isset($reschedule_reasons)) {  foreach ($reschedule_reasons as $value) { ?>
                                            <option value="<?= $value->reschedule_id.'/'.$value->reschedule_name ?>"><?= $value->reschedule_name ?></option>
                                        <?php } } ?>
                                        <option value="-1">Other </option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" id="reschedule_reason_other" hidden>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="reason_other">Add more details</label>
                                    <input type="text" class="form-control" name="reason_other" id="reason_other"  >

                                </div>

                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="other">
                    <?php } ?>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-primary" id="reschedule_bt">Reschedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!---- Reschedule FORM ---->
<!---  Add Service Modal --->
<div id="modal_add_service" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h6 class="modal-title">Add Service</h6>
        </div>

      <form  name="addService" method="post" enctype="multipart/form-data" >

      <div class="modal-body">


            <div class="form-group">
               <div class="row">
                <div class="col-lg-4">
                  <label class="radio-inline">
                       <input name="add_to" value="today" type="radio" checked="checked" id="addToToday">Add to Today's Stop
                   </label>
              </div>
              <div class="col-lg-4">
                   <label class="radio-inline">
                        <input name="add_to" value="future" type="radio" id="addToFuture">Schedule in Future
                   </label>
               </div>
                </div>
              <div class="row">
                <div class="col-sm-12">
                  <label>Add Service</label>

                    <select class="form-control" name="job_id" id="selected_job_id" required>
                      <option value="">Select Any Service</option>
                    <?php if($allservicelist) {  foreach ($allservicelist as $value) { ?>
                        <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>
                    <?php } } ?>
                    </select>
                    <input type="hidden" name="add_service_property_id" value="<?=$property_details->property_id; ?>" >
                    <input type="hidden" name="add_service_technician_id" value="<?=$job_assign_details[0]['technician_id']; ?>" >
                    <input type="hidden" name="add_service_route_id" value="<?=$job_assign_details[0]['route_id']; ?>" >
                    <input type="hidden" name="add_service_customer_id" value="<?=$job_assign_details[0]['customer_id']; ?>" >

                </div>
                </div>
                <div class="row">
                <div class="col-sm-12">
                  <label>Pricing</label>
                    <select class="form-control" name="program_price" id="add_service_program_price" required>
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
                  <input type="number" class="form-control" min=0 name="add_job_price_override" value="" placeholder="(Optional) Enter Price Override Here">
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

<!-- Required Customer Notes Modal Start -->
<div id="modal_required_customer_note" class="modal fade" data-backdrop="static">
<div class="modal-dialog">
  <div class="modal-content">
    <!-- <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;"> -->
    <div class="modal-header bg-warning">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h6 class="modal-title">Complete Service</h6>
    </div>

    <form name="form_required_customer_note" method="post" enctype="multipart/form-data" id="form_required_customer_note" onsubmit="formFileSizeValidate(this)">
      <div class="modal-body">
        <h6 class="text-semibold">Please Submit a Customer Completion Note.</h6>
        <hr>

        <div class="row">
          <div class="form-group">
            <label for="customer_note_saw">What I Saw:</label><br>
            <input type="text" class="form-control" id="customer_note_saw" name="customer_note_saw" required>
          </div>
          <div class="form-group">
            <label for="customer_note_did">What I Did:</label><br>
            <input type="text" class="form-control" id="customer_note_did" name="customer_note_did" required>
          </div>
          <div class="form-group">
            <label for="customer_note_expect">What To Expect:</label><br>
            <input type="text" class="form-control" id="customer_note_expect" name="customer_note_expect" required>
            <input type="hidden" id="note_property_id" name="note_property_id" value="<?= $property_details->property_id; ?>">
            <input type="hidden" name="note_customer_id" value="<?= $job_assign_details[0]['customer_id']; ?>">
          </div>
          <div class="form-group">
            <label class="control-label">Attach Documents/Images</label>
            <input id="files" type="file" name="files[]" class="form-control-file" multiple onchange="fileValidationCheck(this)">
            <span style="color:red;"><?php echo form_error('files'); ?></span>
          </div>
        </div>

        <hr>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-dismiss="modal" id="cancel_required_customer_note">Close</button>
          <button type="submit"  class="btn btn-primary" id="submit_required_customer_note">Continue</button>
        </div>
      </div>
    </form>

  </div>
</div>
</div>
<!-- Required Customer Notes Modal End -->

<!-- Files Modal -->
<div id="file-display-modal" class="modal fade" data-backdrop="">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <!-- <h6 class="modal-title">Complete Service</h6> -->
        </div>
        <div class="modal-body">
          <img class="modal-content" id="modal-file-image">
          <div id="caption"></div>
        </div>
      </div>
    </div>
  </div>
<!-- clover create payment modal -->
<div id="clover_payment_method" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Add Payment Method</h6>
            </div>

            <form name="add_clover_payment" id="add_clover_payment" method="POST" enctype="multipart/form-data"
                form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-9">
                                <label>Card Number</label>
                                <input type="text" class="form-control" name="clover_card_number"
                                    placeholder="Card Number"
                                    required>
                            </div>
                            <div class="col-sm-6 col-md-3" width="50%">
                                <label>Expiration Month</label>
                                <select class="form-control" name="clover_card_exp_m"
                                    required>
                                    <option value="">Select Month</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label>Expiration Year</label>
                                <select class="form-control" name="clover_card_exp_y"
                                    required>
                                    <option value="">Select Year</option>
                                    <?php $cur_year = date('Y');
                                    for($i = 0; $i <= 10; $i++) {?>
                                    <option value="<?php echo $cur_year + $i; ?>"><?php echo $cur_year + $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label>CVV</label>
                                <input type="text" class="form-control number-only" name="clover_card_cvv"
                                    placeholder="CVV"
                                    maxlength="4" pattern="\d{4}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="submitCloverPaymentMethod" class="btn btn-success"
                            data-customer="<?php echo $customerData['customer_id']; ?>">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /clover create payment modal -->

<!-- basys create payment modal -->
      <div id="basys_payment_method" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-primary modal_head" >
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h6 class="modal-title">Add Payment Method</h6>
            </div>

            <form name="add_basys_payment" id="add_basys_payment" method="POST" enctype="multipart/form-data" form_ajax="ajax">
              <div class="modal-body">
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-12 col-md-9">
                      <label>Card Number</label>
                      <input type="text" class="form-control" name="card_number" placeholder="Card Number" required>
                    </div>
              <div class="col-sm-12 col-md-3">
                      <label>Card Exp</label>
                      <input type="text" class="form-control" name="card_exp" placeholder="MM/YY" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <button type="submit" id="submitPaymentMethod" class="btn btn-success" data-customer="<?= $customerData['customer_id']?>">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
<!-- /basys create payment modal -->

<div id="clover_update_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Update Payment Method</h6>
            </div>

            <form name="update_clover_payment" id="update_clover_payment" method="POST" enctype="multipart/form-data"
                form_ajax="ajax">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-md-9">
                                <label>Card Number</label>
                                <input type="text" class="form-control" name="clover_card_number"
                                    placeholder="Card Number"
                                    required>
                            </div>
                            <div class="col-sm-3 col-md-3" width="50%">
                                <label>Expiration Month</label>
                                <select class="form-control" name="clover_card_exp_m"
                                    required>
                                    <option value="">Select Month</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label>Expiration Year</label>
                                <select class="form-control" name="clover_card_exp_y"
                                    required>
                                    <option value="">Select Year</option>
                                    <?php $cur_year = date('Y');
                                    for($i = 0; $i <= 10; $i++) {?>
                                    <option value="<?php echo $cur_year + $i; ?>"><?php echo $cur_year + $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label>CVV</label>
                                <input type="text" class="form-control number-only" name="clover_card_cvv"
                                    placeholder="CVV"
                                    maxlength="4" pattern="\d{4}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" id="submitCloverUpdatePayment" class="btn btn-success"
                            data-customer="<?= $customerData['customer_id']?>">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /clover update payment modal -->

<!-- basys update payment modal -->
    <div id="modal_update_payment" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary modal_head">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Update Payment Method</h6>
          </div>

          <form name="update_basys_payment" id="update_basys_payment" method="POST" enctype="multipart/form-data" form_ajax="ajax">
            <div class="modal-body">
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-12 col-md-9">
                    <label>Card Number</label>
                    <input type="text" class="form-control" name="card_number" placeholder="Card Number" required>
                  </div>
            <div class="col-sm-12 col-md-3">
                    <label>Card Exp</label>
                    <input type="text" class="form-control" name="card_exp" placeholder="MM/YY" required>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" id="submitUpdatePayment" class="btn btn-success" data-customer="<?= $customerData['customer_id']?>">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
<!-- /basys payment modal -->  


<script type="text/javascript" src="<?= base_url('assets/technician') ?>/assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/technician') ?>/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
<script type="text/javascript" src="<?= base_url('assets/technician') ?>/assets/js/pages/form_multiselect.js"></script>
<script>
  
const is_tech_customer_note_required = <?= ($is_tech_customer_note_required == 1) ? 1 : 0; ?>;

function show_selec_products(id) {

    if($('#add_product_div_'+id+':visible').length) {
        $('#add_product_link_' + id).html(' - Add product');
        $('#add_product_div_' + id).hide("slide", {direction: "down"}, 300);
    }
    else {
        $('#add_product_link_'+id).html(' - Add product');
        $('#add_product_div_'+id).show("slide", {  direction: "down" }, 300);
    }

   // $('#add_product_div_'+id).show("slide", { direction: "top" }, 1000);

}


$('#cancel_required_customer_note').on('click', function(e) {
    $('#modal_required_customer_note').modal('hide');
});

$('#reason_id').on('change', function(e) {
    if ($('#reason_id').val() == "-1"){
        $('#reschedule_reason_other').show();
        $('#reason_other').attributes["required"] = "requires";
    } else {
        $('#reschedule_reason_other').hide();
        $('#reason_other').attributes["required"] = "";
    }
});
$('#form_required_customer_note').on('submit', function(e) {
  e.preventDefault();
  let form = $('#form_required_customer_note')[0];
  let data = new FormData(form);
  $.ajax({
    url: '<?= base_url('technician/addTechNoteAjax'); ?>',
    type: 'POST',
    enctype: 'multipart/form-data',
    data: data,
    processData: false,
    contentType: false,
    cache: false,
    success: function(data) {
      console.log(data);
      let responseData = JSON.parse(data);
      console.log(responseData);
      let status = responseData.status;
      console.log(status);
      if(status == 'success') {
        if(is_tech_customer_note_required == 1) {
          let noteId = responseData.note_id;
          $('#requiredNoteId').val(noteId);
        }
        
        $('#modal_mixture_application').modal('toggle');
        $('#modal_required_customer_note').modal('hide');
      } else if(status == 'error') {
        $('#modal_required_customer_note').modal('hide');
      } else {
        $('#modal_required_customer_note').modal('hide');
      }
    },
    error: function(e) {
      console.log("ERROR : ", e);
      $('#modal_required_customer_note').modal('hide');
    }
  });
});
</script>
<script>

  //notes files
function formFileSizeValidate(form) {
	let fileEl = $(form).find('input[type="file"]').get(0);
	console.log(fileEl);
	let totalMbSize = 0;
	if(fileEl.files.length > 0) {
		for (let i = 0; i <= fileEl.files.length - 1; i++) {
			let mbSize = bytesToMb(fileEl.files[i].size);
			console.log(mbSize);
			totalMbSize += mbSize;
		}
		console.log(totalMbSize);
		if(totalMbSize > 5) {
			event.preventDefault();
			console.log('ERROR! File Upload Limit Exceeded!');
		} else {
			console.log('File Size Good!');
		}
	}
}
function fileValidationCheck(el) {
	let totalMbSize = 0;
	if (el.files.length > 0) {
		for (let i = 0; i <= el.files.length - 1; i++) {
			let mbSize = bytesToMb(el.files[i].size);
			console.log(mbSize);
			totalMbSize += mbSize;
		}
		console.log(totalMbSize);
		if(totalMbSize > 5) {
			$(el).next().text('file(s) exceed the max 5MB limit');
		} else {
			$(el).next().text('');
		}
	} else {
		$(el).next().text('');
	}
}
function bytesToMb(bytes) {
	if(bytes === 0) return 0;
	let mb = (bytes / (1024*1024));
	return mb;
}

function displayNoteComments(id) {
  console.log('display comments fired');
  let modal = document.getElementById('comment-display-modal');
  let dataContent = docuemnt.getElementById('note-comment-data-'+id).innerHTML;
  document.getElementById('comment-content').innerHTML = dataContent;
  modal.style.display = "block"
  var span = document.getElementById('close-comment-display');
  span.onclick = function() { 
    modal.style.display = "none";
  }      
}
function getNoteAssignUserUpdateVars(el) {
  let userId = $(el).val();
  let noteId = $(el).data('note-id');
  let noteOwnerId = $(el).data('note-userid');
  let userName = $(el.options[el.options.selectedIndex]).text();
  updateAssignUser(userId,noteId,userName);
}
  function getNoteTypeUpdateVars(el) {
    let typeId = $(el).val();
    let noteId = $(el).data('note-id');
    let currentTypeId = $(el).data('note-typeid');
    let typeName = $(el.options[el.options.selectedIndex]).text();
    let idMatch = (typeId == currentTypeId);
    updateAssignType(typeId,noteId,typeName,idMatch);
	window.location.reload();
  }  
function showAssignUserSelect(noteId) {
  $(`#update-assignuser-${noteId}`).removeClass('hidden');
}
  function showNoteTypeSelect(noteId) {
    $(`#update-notetype-${noteId}`).removeClass('hidden');
  }  
function updateAssignUser(userId, noteId, userName) {
  $.post("<?= base_url('technician/updateAssignUser'); ?>", {'noteId': noteId, 'userId': userId}, function(result){
    $(`#note-assigned-user-wrap-${noteId}`).remove();
    if(userId != '') {
      $(`<span id="note-assigned-user-wrap-${noteId}"><span>Assigned to&nbsp;</span><span class="text-success text-bold"> ${userName}</span></span>`).insertBefore(`#update-assignuser-${noteId}`);
    }
    $(`#update-assignuser-${noteId}`).addClass('hidden');
  });
}
  function updateAssignType(typeId, noteId, typeName, match) {
    $.post("<?= base_url('technician/updateAssignType'); ?>", {'noteId': noteId, 'typeId': typeId}, function(result){
      $(`#note-assigned-type-wrap-${noteId}`).remove();
      if(!match) {
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
  if(dueDate != '') {
    $.post("<?= base_url('technician/updateNoteDueDate'); ?>", {'noteId': noteId, 'dueDate': dueDate}, function(result){
      $(`#note-duedate-${noteId}`).text(dueDate);
    });      
  }
  $(el).val('').addClass('hidden');
}
  function addCommentAjax(noteId) {
    event.preventDefault();
    let comtVal = $(`#add-note-comment-form-${noteId} input[name="add-comment-input"]`).val().trim();
    if( comtVal != '') {    
      let form = $(`#add-note-comment-form-${noteId}`)[0];
      let data = new FormData(form);
      $(`#add-note-comment-form-${noteId} input[name="add-comment-input"]`).val('');
      $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '<?= base_url('technician/addNoteCommentAjax'); ?>',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
          console.log("Success : ", data);
          let result = JSON.parse(data);
          console.log("Success : ", result);
          let comment = `<li class="list-group-item comment-list-item">
                            <small class="text-muted">${result.timestamp}</small> <strong>${result.user_first_name} ${result.user_last_name}: </strong>${result.comment_body}
                          </li>`;
          $(form).parent().before(comment);
          $(`#comment-count-value-${result.note_id}`).text(`${result.comment_count}`);
        },
        error: function(e) {
          console.log("ERROR : ", e);
        }
      });
    }
  }
  function imgDisplay(imgEl) {
    if($(imgEl).hasClass('files-thumbnail')) {
      $(imgEl.parentElement).removeClass('col-xs-6 ');
      $(imgEl).removeClass('files-thumbnail');
    } else {
      $(imgEl.parentElement).addClass('col-xs-6 ');
      $(imgEl).addClass('files-thumbnail');
    }
  }
  // Note File Size Validation
  function formFileSizeValidate(form) {
      let fileEl = $(form).find('input[type="file"]').get(0);
      console.log(fileEl);
      let totalMbSize = 0;
      if(fileEl.files.length > 0) {
          for (let i = 0; i <= fileEl.files.length - 1; i++) {
              let mbSize = bytesToMb(fileEl.files[i].size);
              console.log(mbSize);
              totalMbSize += mbSize;
          }
          console.log(totalMbSize);
          if(totalMbSize > 5) {
              event.preventDefault();
              console.log('ERROR! File Upload Limit Exceeded!');
          } else {
              console.log('File Size Good!');
          }
      }
  }
  function fileValidationCheck(el) {
      let totalMbSize = 0;
      if (el.files.length > 0) {
          for (let i = 0; i <= el.files.length - 1; i++) {
              let mbSize = bytesToMb(el.files[i].size);
              console.log(mbSize);
              totalMbSize += mbSize;
          }
          console.log(totalMbSize);
          if(totalMbSize > 5) {
              $(el).next().text('file(s) exceed the max 5MB limit');
          } else {
              $(el).next().text('');
          }
      } else {
          $(el).next().text('');
      }
  }
  function bytesToMb(bytes) {
      if(bytes === 0) return 0;
      let mb = (bytes / (1024*1024));
      return mb;
  }
</script>
<!-- Note End -->
<script>

  document.onreadystatechange = function () {
    if (document.readyState == "complete") {
     post_RealRoadOptimizeStops();
    }
}

  var map;
    function loadMap() {
      map = new Microsoft.Maps.Map(document.getElementById('routeMap'), {});
    }

var showmap = true;
$('#routeMap').show();
var resturl = 'https://optimizer3.routesavvy.com/RSAPI.svc/';

function post_BasicOptimizeStops() {

  var requestStr = $('#postTestRequest').val();
  var request = JSON.parse(requestStr);
  
  //clear
  $('#result').text('');
  $('#get_url').text('');
  $('#geturl').hide();
  map.entities.clear();

  postit(resturl +'POSTOptimize', {
    data: JSON.stringify(request),
    success: function (data) {
      $('#jsonresult').show();
      var resp = JSON.stringify(data, null, '\t');
      //process results
      if (showmap) {
        renderMap(data);
      }
      else {
        //show json results
        $('#result').text(resp);
      }
    },
    error: function (err) {
      $('#result').text(JSON.stringify(err, null, '\t'));
    }
  });
}

function post_RealRoadOptimizeStops() {

  var requestStr = $('#postTestRequest').val();
  requestStr = requestStr.replace("basic","realroadcar");
  var request = JSON.parse(requestStr);
  //clear
  $('#result').text('');
  $('#get_url').text('');
  $('#geturl').hide();

  map.entities.clear();
  postit(resturl +'POSTOptimize', {
    data: JSON.stringify(request),
    success: function (data) {
      var resp = JSON.stringify(data, null, '\t');
        renderMap(data);

        sample= jQuery.map(data.OptimizedStops, function(n, i){
          return n.Name;
          });

    },
    error: function (err) {
      $('#result').text(JSON.stringify(err, null, '\t'));
    }
  });
}

function get_BasicOptimizeStops() {
  var requestStr = $('#postTestRequest').val();
  //clear
  $('#result').text('');
  map.entities.clear();
  $('#geturl').show();

  var url = resturl + 'GETOptimize?query=' + requestStr;
  $('#get_url').text( url);
  $.getJSON(url, function (data) {
    if (showmap) {
      renderMap(data);
    }
    else {
      //show json results
      $('#result').text(JSON.stringify(data, null, '\t'));
    }
    
  });
}

function get_RealRoadOptimizeStops() {
  
  var requestStr = $('#postTestRequest').val();
  requestStr = requestStr.replace("basic", "realroadcar");
  //clear
  $('#result').text('');
  map.entities.clear();
  $('#geturl').show();
  var url = resturl + 'GETOptimize?query=' + requestStr;
  $('#get_url').text(url);
  
  $.getJSON(url, function (data) {
    if (showmap) {
      renderMap(data);
    }
    else {
      //show json results
      $('#result').text(JSON.stringify(data, null, '\t'));
    }

  });
}

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

function renderMap(data) {

  var points = new Array();
  for (var i in data.Route.RoutePath) {
    points[i] = new Microsoft.Maps.Location(data.Route.RoutePath[i][0], data.Route.RoutePath[i][1]);
  }
  var path = new Microsoft.Maps.Polyline(points, { strokeColor: 'blue', strokeThickness: 2 });
  map.entities.push(path);

  for (var i in data.OptimizedStops) {
    var location = new Microsoft.Maps.Location(data.OptimizedStops[i].RouteLocation.Latitude, data.OptimizedStops[i].RouteLocation.Longitude);
    var c = 'orange';
    if (i == 0) c = 'green';
    else if (i == (data.OptimizedStops.length - 1)) c = 'red';
    var label = parseInt(i) + 1;
    map.entities.push(new Microsoft.Maps.Pushpin(location, { color: c, text: label.toString(), title: data.OptimizedStops[i].Name  }));
  }


  var bounds = new Microsoft.Maps.LocationRect.fromLocations(points);
  map.setView({ bounds: bounds })
}

function mapsSelector() {
    if /* if we're on iOS, open in Apple Maps */
      ((navigator.platform.indexOf("iPhone") != -1) || 
      (navigator.platform.indexOf("iPad") != -1) || 
      (navigator.platform.indexOf("iPod") != -1))
      window.open("https://maps.google.com/maps?saddr=<?= $currentaddress ?>&daddr=<?= $property_details->property_address ?>&amp;ll=");

    else /* else use Google */
      window.open("https://maps.google.com/maps?saddr=<?= $currentaddress ?>&daddr=<?= $property_details->property_address ?>&amp;ll=");
}



    $('input[name=user_status]').change(function () {
        var mode = $(this).prop('checked');
        var user_id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>admin/userUpdate',
            data: {mode: mode, user_id: user_id},
            success: function (data)
            {
                // alert(data);
            }
        });

    });
</script>
<script type="text/javascript">
    const propertyAlertsArray = '<?= addslashes($property_alerts_json) ?>' ? JSON.parse('<?= addslashes($property_alerts_json) ?>') : []
    const customerAlertsArray = '<?= addslashes($customer_alerts_json) ?>' ? JSON.parse('<?= addslashes($customer_alerts_json) ?>') : []
    const alertsArray = [];

    for (let i = 0; i < customerAlertsArray.length; i++) {
        if (customerAlertsArray[i]['show_tech'] === true) {
            alertsArray.push(customerAlertsArray[i]);
        }
    }

    for (let i = 0; i < propertyAlertsArray.length; i++) {
        if (propertyAlertsArray[i]['show_tech'] === true) {
            alertsArray.push(propertyAlertsArray[i])
        }
    }


    const Alerts = Swal.mixin({
        type: 'warning',
        confirmButtonColor: '#009402',
        confirmButtonText: 'Proceed',
        progressSteps: alertsArray.length
    })

    $(document).on("click","#startTime", async function (e) {
        e.preventDefault();
        for (let i = 0; i < alertsArray.length; i++) {
            const title = alertsArray[i].text.split(":")[0]
            const text = alertsArray[i].text.split(":")[1]
            await Alerts.fire({ title, text })
        }  
     swal.mixin({
            input: 'text',
          type: 'warning',
          confirmButtonColor: '#009402',
          cancelButtonColor: '#36c9c9',
          confirmButtonText: 'Submit',
          cancelButtonText: 'Skip Service',
          showCancelButton: true,
          progressSteps: 1,
     }).queue([
    
      {
        title: 'Wind Speed',
        text: 'Type a hand wind speed in MPH',
        inputValue : '<?= $current_wind_speed ?>',
        inputValidator: (value) => {

        return new Promise((resolve) => {
          if ($.isNumeric( value) ) {
            resolve()
          } else {
            resolve('Please enter valid number')
          }
        })
      }

     },

      ]).then((result) => {

 
        if (result.value) {
           var wind_speed  = result.value[0];
          $.ajax({
               
              type: 'POST',
              url: "<?=base_url('technician/jobStartMultiple/').$tech_assign_ids ?>",
              data: {wind_speed: wind_speed},
              dataType: "JSON",
              success: function (data){
                console.log(data)
                // alert(data.status);
                    if (data.status==200) {
                         $("#startTime").prop('disabled', true);
                         $("#completejob").prop('disabled', false);

                    } else {
                      console.log("somthing went wrong")
                    }
              }
             
             });
          } else {
            $("#reschedulejob").trigger("click");  
          }
      })

 });

 $(document).on("click","#startTimeSales", function (e) {
   
  $("#completejobsales").prop('disabled', false);

 });




// $(document).on("click","#completejob", function (e) {

//     $('#modal_mixture_application').modal('toggle');
       
        
// });




$('form[name="addService"] button[type="submit"]').on('click', function(e){
	e.preventDefault();  
	var when = $('input[name="add_to"]:checked').val();
	var serviceId = $('#selected_job_id').val();
	var propertyId = $('input[name="add_service_property_id"]').val();
	var serviceName = $('#selected_job_id option:selected').text();
	var propertyName = $('input[name="property_title"]').val();
	var programName = serviceName + "- Standalone";
	var programPrice = $('select#add_service_program_price').val();
	var priceOverride = $('input[name="add_job_price_override"]').val();
	var tech_id = $('input[name="add_service_technician_id"]').val();
	var customerId = $('input[name="add_service_customer_id"]').val();
	var routeId = $('input[name="add_service_route_id"]').val();
	
  if (priceOverride == '') {
    var price_override_set = 0;
  } else {
      var price_override_set = 1;
  }
	
	//console.log(when);
	
	if(when == 'today'){
		var post = [];
		var property = {
			service_id: serviceId, 
			property_id: propertyId, 
			program_name: programName, 
			program_price: programPrice, 
			price_override:priceOverride,  
			is_price_override_set:price_override_set,
			technician_id:tech_id,
			route_id: routeId,
			customer_id:customerId
		}
		post.push(property);
		
		$.ajax({

		  type: 'POST',
		  url: "<?=base_url('technician/addJobToPropertyToday')?>",
		  data: {post},
		  dataType: "JSON",
		  success: function (data){
			

		  }

		 }).done(function(data){
			$('#modal_add_service').modal('hide');
                  if (data.status=="success") {
					 
                    swal(
                       'Success!',
                       'Service Added Successfully',
                       'success'
                   )
                   location.reload();
                     

                  } else {
                    swal({
                         type: 'error',
                         title: 'Oops...',
                         text: 'Something went wrong!'
                     })
                  }
          });
	}else if(when == 'future'){
		var post = [];
		var property = {
			service_id: serviceId, 
			property_id: propertyId, 
			program_name: programName, 
			program_price: programPrice, 
			price_override:priceOverride,  
			is_price_override_set:price_override_set 
		}
		post.push(property);
		
		$.ajax({

		  type: 'POST',
		  url: "<?=base_url('technician/addJobToPropertyFuture')?>",
		  data: {post},
		  dataType: "JSON",
		  success: function (data){
			

		  }

		 }).done(function(data){
			$('#modal_add_service').modal('hide');
                  if (data.status=="success") {
					 
                    swal(
                       'Success!',
                       'Service Added Successfully',
                       'success'
                   )
                   
                     

                  } else {
                    swal({
                         type: 'error',
                         title: 'Oops...',
                         text: 'Something went wrong!'
                     })
                  }
          });
	
	}
	
});

function removePropertyCondition(property_condition_assign_id){
		swal({
			title: 'Are you sure?',
			text: "You won't be able to recover this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#009402',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes',
			cancelButtonText: 'No'
		}).then((result)=>{
			if(result.value){
				$("#loading").css("display", "block");
				$.ajax({
					type: 'POST',
					url: "<?=base_url('technician/deleteAssignedPropertyCondition')?>",
					dataType: "JSON",
					data:{property_condition_assign_id:property_condition_assign_id},
					success: function(data){
						$("#loading").css("display", "none");
						if(data.status == 'success'){
							window.location.reload();
						}
					},
					error:function(error){
						$("#loading").css("display", "none");
						alert("Something went wrong");
					}
				});
			}
		});
	}
	
    $(document).ready(function(){
        $(".multiselect-select-all").multiselect('destroy');
		$('.multiselect-select-all').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
      includeSelectAllOption: true,
      templates: {
        filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i><input class="form-control" type="text"/></li>'
      },
      onInitialized: function(select, container) {
        $(".styled, .multiselect-container input").uniform({
          radioClass: 'checker'
        });
      },
      onSelectAll: function() {
        $.uniform.update();
       }
      // onChange: function() {
      //   alert(1);
      //   console.log(this.value);
      //     var selected = this.value;
      //     console.dir(selected);
      // }
	});

        $('.multiselect-select-all').change( function(){
            var id = $(this).attr('id').replace('add_product_select_', '');
            //console.log(id);
            var selected = $('#add_product_select_'+id).val();

            var allProducts = $('#add_product_select_'+id+' option');
            for (var i= 0; i< allProducts.length; i++){
                console.log(allProducts[i].selected);
                if (allProducts[i].selected == true){
                    $('#input_'+id+'_'+allProducts[i].value).removeAttr('disabled');
                    $('#'+id+'_'+allProducts[i].value).show("slide", {direction: "down"}, 300);

                } else {
                    $('#input_'+id+'_'+allProducts[i].value).attr('disabled','disabled');
                    $('#'+id+'_'+allProducts[i].value).hide("slide", {direction: "down"}, 300);
                }
            }



            // console.log(aux[0].value);


        });
    });
</script>
 <script>
$(document).ready(function(){
    $(".assignservicesedicustumer").hide();
});
$("#createnoteform select[name='note_type']").change(function () {
    var selected = $("#createnoteform select[name='note_type'] option:selected").text();
    //alert(selected);
    if (selected == "Service-Specific") {
        $(".assignservicesedicustumer").show();
		$(".assignservicesedicustumer select").attr('required',true);
    } else {
        $(".assignservicesedicustumer").hide();
		$(".assignservicesedicustumer select").attr('required',false);
    }
});
 </script>


  <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=<?= BingMAp ?>&callback=loadMap' async defer></script>
  
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<!-- added by sean -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
<!-- Debug Var Dumps -->
<script>
var currentUser = <?= print_r(json_encode($currentUser), TRUE); ?>;
var notes = <?= print_r(json_encode($enhanced_notes), TRUE); ?>;

</script>
<!-- Autopay -->
<script>

  $('button#submitCloverPaymentMethod').on('click', function(e) {
      e.preventDefault();
      var month = $('form#add_clover_payment select[name="clover_card_exp_m"]').val();
      var year = $('form#add_clover_payment select[name="clover_card_exp_y"]').val();
      var customer_id = $('#submitCloverPaymentMethod').data('customer');
      var card_number = $('form#add_clover_payment input[name="clover_card_number"]').val();
      var card_exp = Number(year + month);
      var card_cvv = $('form#add_clover_payment input[name="clover_card_cvv"]').val();
  
      //card_exp = card_exp.replace("/","");
  
      $.ajax({
  
          type: 'POST',
          url: "<?=base_url('admin/cloverAddCustomer')?>",
          data: {
              customer_id: customer_id,
              tokenData: {
                  account: card_number,
                  expiry: card_exp,
                  cvv: card_cvv
              }
          },
          dataType: "JSON",
          success: function(data) {
              console.log(data)
              // alert(data.status);
              if (data.status == 200) {
                  swal(
                      'Success!',
                      data.msg,
                      'success'
                  );
                  $('#clover_payment_method').modal('hide');
              } else if (data.status == "failed") {
                  if (data.msg) {
                      var msg = data.msg;
                      msg = msg.toUpperCase();
                      $('div#swal2-content').css('text-transform', 'capitalize');
                  } else {
                      msg = "Something went wrong. Please try again.";
                  }


                  swal({
                      confirmButtonColor: '#d9534f',
                      type: 'error',
                      title: 'Oops...',
                      text: msg
                  });
              } else {
                  swal({
                      confirmButtonColor: '#d9534f',
                      type: 'error',
                      title: 'Oops...',
                      text: 'Something went wrong. Please try again.'
                  });
              }
          }

      });

  });

  
  $('button#submitPaymentMethod').on('click', function(e){
    e.preventDefault();
    var customer_id = $('#submitPaymentMethod').data('customer');
    var card_number = $('form#add_basys_payment input[name="card_number"]').val();
    var card_exp = $('form#add_basys_payment input[name="card_exp"]').val();

    //card_exp = card_exp.replace("/","");

      $.ajax({

        type: 'POST',
        url: "<?=base_url('admin/basysAddCustomer')?>",
        data: {customer_id: customer_id, card_number: card_number, card_exp:card_exp},
        dataType: "JSON",
        success: function (data){
        console.log(data)
        // alert(data.status);
          if (data.status=="success") {
            swal(
                         'Success!',
                         'Payment Method Added Successfully ',
                         'success'
                     );
             $('#basys_payment_method').modal('hide');
          }else if (data.status=="failed"){
            if(data.msg){
              var msg = data.msg;
              msg = msg.toUpperCase();
            $('div#swal2-content').css('text-transform', 'capitalize');
            }else{
              msg = "Something went wrong. Please try again.";
            }
            
            
             swal({
               confirmButtonColor: '#d9534f',
                           type: 'error',
                           title: 'Oops...',
                           text: msg
                       });
          } else {
              swal({
               confirmButtonColor: '#d9534f',
                           type: 'error',
                           title: 'Oops...',
                           text: 'Something went wrong. Please try again.'
                       });
          }
        }
  
       });
    
  });

  $('button#addProduct').on('click', function(e) {
      alert(1);
  });

</script>
<!-- /AutoPay -->
<!-- -->