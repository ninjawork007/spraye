<style>
.btn-technician {
    position: relative;
    color: #fff;
    background-color: #3379b740;
    border-color: #3379b740;
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

.tecnician-btn{
    padding-top: 10px;
}

.finish_btn_color{
 color: #fff;
background-color: #dfdedc !important;
border-color: #a09f9d;
}


.activeRoute {
  background: #29c1a8; 
}

/*a:focus, a:hover {

    color: #f2ffff;;
    text-decoration: none;

}*/

body{
    overflow-x: hidden;
}

</style>

<style>
       /* Set the size of the div element that contains the map */
      /* Set the size of the div element that contains the map */
      #routeMap {
        height: 100%;  /* The height is 400 pixels */
        /*width: 100%;*/
        padding-top: 100%;  /* The width is the width of the web page */
        margin: 20px;

       }

       .techmessage {
        padding-top:  5px;
        padding-left:  5px;
        padding-right:   5px;
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

  if (!empty($job_assign_details)) {
    
    foreach ($job_assign_details as $key => $value) {
     
              $Locations[$key]['Name'] = $value['property_address'];
              $Locations[$key]['Latitude'] = $value['property_latitude'];
              $Locations[$key]['Longitude'] = $value['property_longitude'];
              $technician_id = $value['technician_id'];
              $job_assign_date = $value['job_assign_date'];

    }

    array_unshift($Locations,$statLocation);
    array_push($Locations,$endLocation);
  }
     


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


<div class="techmessage">
 <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

</div>

 <div style="background:#f6f7f9;padding-left:10px;padding-right: 10px;">
    <div class="row">


          <?php 
            if (!empty($routeDetails)) {
 


               foreach ($routeDetails as $key => $value) {
                if ($value['route_id']==$current_route) {
                  $activeclass = 'activeRoute';
                } else {
                  $activeclass = '';
                }
echo                  '<div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="tecnician-btn">
                   <a class="btn-technician '.$activeclass.'" href="'. base_url().'admin/technicianMapView/'.$technician_id.'/'.$job_assign_date.'/'.$value['route_id'].'" >'.$value['route_name'].'</a>
                   </div>
                 </div>';
               }
            }

           ?>         


        </div>    
        </div>



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



<script type="text/javascript">

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
var resturl = 'https://optimizer.routesavvy.com/RSAPI.svc/';

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



$('#finishday').click(function(e){

  $.ajax({
    url : '<?= base_url("technician/finishDay") ?>',
    type: 'get',
  }).done(function(response){ 

    if (response==1) {
      swal({ type: 'error',title: 'Please complete all Jobs for finish day',text: ''})
    } else if(response==2) {
           swal('All Jobs are completed ','','success')

    } else {
      swal({ type: 'error',title: 'Oops... No Jobs available for finish day',text: ''})
    }

  });



});


</script>
<script type="text/javascript">
  
function getDetailsByAddress(property_address) {
// /  alert(property_address.toString());
//alert (JSON.stringify(property_address));  
console.log(JSON.stringify(property_address));

}


</script>

  <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=<?= BingMAp ?>&callback=loadMap' async defer></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
            

