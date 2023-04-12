<style>
.modal{
    overflow-y: scroll;
}
body.modal-open {
    overflow: hidden;
}
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
.techmessage {
    padding-top:  5px;
    padding-left:  19px;
    padding-right:   20px;
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
              'Name' => $job_assign_details->property_address,
              'Latitude' => $job_assign_details->property_latitude,
              'Longitude' => $job_assign_details->property_longitude,
    );


  $endLocation = array(
    'Name' => $setting_details->end_location,
    'Latitude' => $setting_details->end_location_lat,
    'Longitude' => $setting_details->end_location_long
  );

 
$Locations = array($statLocation,$property_address_details,$endLocation);



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

    <div style="background:#f6f7f9;padding: 10px;">
      <table cellspacing="5" cellpadding="5" width="100%">
        <tr>
          <td>
            <table width="100%">
              <tr>
                <td style="padding-left:5px"><h5 class="text-semibold"><?=  $job_assign_details->first_name.' '.$job_assign_details->last_name ?></h5></td>           
              </tr>
              <tr> 
                <td style="padding-left:5px"> <h7 class="text-muted" ><?php if(isset($job_assign_details->phone) && !empty($job_assign_details->phone)){ echo "<a href='tel:'".$job_assign_details->phone."'>".preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $job_assign_details->phone)."</a>" ;} ?></h7></td>
              </tr>
              <tr> 
                <td style="padding-left:5px"> <h7 class="text-muted" ><?= $job_assign_details->property_address ?></h7></td>
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


      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right" style="padding-top: 10px !important;">
            <div class="panel panel-white">
              <div class="panel-heading">
                <h6 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group1" aria-expanded="false" class="collapsed">Service Details</a>
                </h6>
              </div>
              <div id="accordion-control-right-group1" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                  

              <table class="table" style="border: 0 !important">
                <tr>
                  <td  ><h5 class="text-semibold">Service Name</h5></td>
                  <td><?= $job_details->job_name ?></td>
                </tr>
                <tr>
                  <td  ><h5 class="text-semibold">Service Price</h5></td>
                  <td><?= $job_details->job_price ?></td>
                </tr>
              </table>
            </div>
          </div>

          <div class="panel panel-white">
            <div class="panel-heading">
              <h6 class="panel-title">
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group2" aria-expanded="false">Product Details</a>
              </h6>
            </div>
            <div id="accordion-control-right-group2" class="panel-collapse collapse" aria-expanded="false">

              <?php  if (!empty($product_details)) { ?>
            <div class="table-responsive" >
                  
              <table class="table" style="border: 0 !important">
                <tr style="background-color: #F5F5F5;">
                  <td ><h6 class="text-semibold">Name</h6></td>
                  <td ><h6 class="text-semibold">Application Rate</h6></td>
                  <td ><h6 class="text-semibold">Mixture Application Rate</h6></td>
                  <td ><h6 class="text-semibold">Wind Speed</h6></td>
                  <td ><h6 class="text-semibold">Temperature</h6></td>
                  <td ><h6 class="text-semibold">Notes</h6></td>
                </tr>

                  <?php  foreach ($product_details as $key => $value) {

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
                        } 
                    ?>
                      
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
              <?php  
                } else { 
              ?>
            <div class="panel-body">
              No record found
            </div>
              <?php  
                } 
              ?>  
          </div>
        </div>

          <div class="panel panel-white">
            <div class="panel-heading">
              <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group3" aria-expanded="false">Program Details</a>
              </h6>
            </div>
            <div id="accordion-control-right-group3" class="panel-collapse collapse" aria-expanded="false">
              <table class="table" style="border: 0 !important">
                <tr>
                  <td ><h5 class="text-semibold">Program Name</h5></td>
                  <td ><?= $job_assign_details->program_name ?></td>
                </tr>
                <tr>
                  <td colspan="1" ><h5 class="text-semibold">General Notes</h5></td>
                  <td><?= $job_assign_details->program_notes ?></td>
                </tr>                                               
              </table>
            </div>
          </div>

          <div class="panel panel-white">
            <div class="panel-heading">
              <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group4" aria-expanded="false">Yard Information/Notes</a>
              </h6>
            </div>
            <div id="accordion-control-right-group4" class="panel-collapse collapse" aria-expanded="false">
              <?php if (!empty($property_details)) { ?>
              <table class="table" style="border: 0 !important">
                <tr>
                  <td ><h5 class="text-semibold">Yard Square Feet</h5></td>
                  <td ><?= $property_details->yard_square_feet ?></td>
                </tr>
                <tr>
                  <td ><h5 class="text-semibold">Property Notes</h5></td>
                  <td ><code style="color: black; background: none;"><?= $property_details->property_notes ?></code></td>
                </tr>   
              </table>

                <?php } else { ?>
                <div class="panel-body">
                    No record found
                </div>
                <?php }  ?>     

                    
            </div>
          </div>
          <?php if ( !empty($job_assign_details->email) && $job_assign_details->is_email==1 ) { ?>

          <div class="panel panel-white">
            <div class="panel-heading">
              <h6 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion-control-right" href="#accordion-control-right-group5" aria-expanded="false">Message to Customer</a>
              </h6>
            </div>
            <div id="accordion-control-right-group5" class="panel-collapse collapse" aria-expanded="false">
                                
              <form action="<?= base_url('technician/SendCustomerEmail') ?>"  id="sendemail" method="post" name='customeremail'>
                <table class="table" style="border: 0 !important">
                  <tr>
                    <td ><h5 class="text-semibold"><input type="text" name="email" class="form-control" readonly="" value="<?= $job_assign_details->email ?>"></h5></td>
                  </tr>
                  <tr>
                    <td><textarea class="form-control" placeholder="Enter Your Message"  rows="5" name="message" ></textarea> </td>
                  </tr>
                  <tr> 
                    <td> <button type="submit" id="sendmsgbt"  class="btn btn-success text-right">Send</button></td>
                  </tr>
                </table>
                <input type="hidden" name="customer_id" value="<?= $job_assign_details->customer_id ?>">
              </form>
          </div>
        </div>
        <?php } ?>

      </div>
    </div>
  </div> 

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: #e6e6e6 !important">
      <div class="col-lg-4 col-md-4 col-sm-12">           
        <div class="tecnician-btn" style="padding-bottom: 10px !important;">

          <button   id='startTime' class="btn btn-technician btn-start-time" >Start Service</button>

        </div>          
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12">           
        <div class="tecnician-btn" style="padding-bottom: 10px !important;">
          <button disabled=""  id='completejob' class="btn btn-technician btn-complete" data-toggle="modal" data-target="#modal_mixture_application"   >Complete Service </button>
        </div>          

      <div class="techmessage">
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
      </div>

      <div class="col-lg-4 col-md-4 col-sm-12">           
        <div class="tecnician-btn" style="padding-bottom: 10px !important;">
          <a class="btn-technician btn-reschedule" href="<?=  base_url('technician/rescheduleJob/').$job_assign_details->technician_job_assign_id ?>" id='reschedulejob' >Skip Service/Reschedule</a>
        </div>          
      </div>
    </div>
  </div>

</div>

 

<div id="modal_mixture_application" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Complete Service</h6>
         </div>
         <form action="<?=  base_url('technician/completeJob/').$job_assign_details->technician_job_assign_id ?>" name= "completejobform" method="post">
            <input type="hidden" name="program_price" value="<?php echo $job_assign_details->program_price ?>" />
            <div class="modal-body">
              <h6 class="text-semibold">How many gallons of mixture did you use?</h6>
                    <?php  if (!empty($product_details_for_cal)) { ?>
                     <div class="form-group">
                        <div class="row">

                             <?php 


                              $numOfCols = 2;
                              $rowCount = 0;
                              foreach ($product_details_for_cal as $key => $value) {

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
                            ?>
                            <div class="col-sm-6 col-md-6">
                              <label><?= $value->product_name ?></label>
                              <div class="input-group">
                                    <input type="number"  name="<?= $value->product_id  ?>"  class="form-control" value="<?= $used_mixture  ?>" placeholder="" >
                                    <span class="input-group-btn">
                                       <span class="btn btn-success"><?= $value->mixture_application_unit ?></span>
                                    </span>                      
                              </div>                          
                           </div>
                           <?php
                            $rowCount++;
                            if($rowCount % $numOfCols == 0) echo '</div></div><div class="form-group"><div class="row">';
                          
                           ?>           
                           <?php  } ?>
                      </div>
                   </div>

                   <?php }  else { ?>
                      <p>Products are not available</p>
                   <?php  } ?>      


              <?php 
                  if ($job_assign_details->is_email==1) { ?>
              <hr>
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
  map.setView({ bounds: bounds });
}

function mapsSelector() {
    if /* if we're on iOS, open in Apple Maps */
      ((navigator.platform.indexOf("iPhone") != -1) || 
      (navigator.platform.indexOf("iPad") != -1) || 
      (navigator.platform.indexOf("iPod") != -1))
      window.open("https://maps.google.com/maps?saddr=<?= $currentaddress ?>&daddr=<?= $job_assign_details->property_address ?>&amp;ll=");

    else /* else use Google */
      window.open("https://maps.google.com/maps?saddr=<?= $currentaddress ?>&daddr=<?= $job_assign_details->property_address ?>&amp;ll=");
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


$(document).on("click","#startTime", function (e) {
      e.preventDefault();     
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
          title: 'Wind Speed Over Limit',
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
            url: "<?=base_url('technician/jobStart/').$job_assign_details->technician_job_assign_id ?>",
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




// $(document).on("click","#completejob", function (e) {

//     $('#modal_mixture_application').modal('toggle');
       
        
// });



 $('#reschedulejob').click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');
   
     swal.mixin({
       input: 'text',
       type: 'warning',          
       confirmButtonText: 'Yes',
       cancelButtonText: 'No',
       showCancelButton: true,
       cancelButtonColor: '#d33',
       progressSteps: 1
     }).queue([
       {
         title: 'Are you sure you want to reschedule this service?',
         text: 'Custom note to Admin',
          inputValidator: (value) => {

           return new Promise((resolve) => {

            if (value == '' ) {
              resolve('Please enter note');
            }  else {                      
                resolve();
            }
          })
        }
       },
     ]).then((result) => {
       if (result.value) {

        $("#loading").css("display","block");
         $.ajax({               
            type: 'POST',
            url: url,
            data: {reschedule_message: result.value[0]},

            success: function (response){
            $("#loading").css("display","none");
              window.location = '<?= base_url('technician/dashboard') ?>'
            }           
         });


       }
     })

  })




</script>   
 


  <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=<?= BingMAp ?>&callback=loadMap' async defer></script>
  
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
