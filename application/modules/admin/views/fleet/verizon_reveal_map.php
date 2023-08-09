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
   z-index: 99;
   text-align: center;
   }
  .btn-group>.btn:first-child {
    margin-left: 7px;
}
   #loading-image {
   position: absolute;
   left: 50%;
   top: 50%;
   width: 10%;
   z-index: 100;
   }
   .btn-group {
   margin-left: -7px !important;
   margin-top: -1px !important;
   }
   .dropdown-menu {
   min-width: 80px !important;
   }
   .myspan {
   width: 55px;
   }
   .label-warning, .bg-warning {
   background-color :#A9A9A9;
   background-color: #A9A9AA;
   border-color: #A9A9A9;
   }
   .toolbar {
   float: left;
   padding-left: 5px;
   }
   .dataTables_filter {
   /*text-align: center !important;*/
   margin-left: 60px !important;
   }
   #invoicetablediv{
   padding-top: 20px;
   }
   .Invoices .dataTables_filter input {

    margin-left: 11px !important;
    margin-top: 8px !important;
    margin-bottom: 5px !important;
}
.tablemodal > tbody > tr > td, .tablemodal > tbody > tr > th, .tablemodal > tfoot > tr > td, .tablemodal > tfoot > tr > th, .tablemodal > thead > tr > td, .tablemodal > thead > tr > th {
  border-top: 1px solid #ddd;
}


.label-till , .bg-till  {
    background-color: #36c9c9;
    background-color: #36c9c9;
    border-color: #36c9c9;
}

#fleet_table > thead > tr > th:last-of-type, #fleet_table > tbody > tr > td:last-of-type{
    text-align: center;
}

button#addVehicleBtn {
    background-color: #1c86d9;
    color: #fff;
    margin-top: 15px;
    margin-bottom: 15px;
}
.content {
    height: 1100px;
}

</style>
<!-- Content area -->
<div class="content invoicessss">
    <div id="dvMap" style="height:100vh;">map div area</div>
</div>

<!-- /content area -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo GoogleMapKey; ?>"></script>
<script>
    var initial_load = true;
    var set_initial_center = true;
    $(document).ready(function() {
        var MapMarkers = [];

        var map;
        var marker;
        var markers = [];
        var filteredMarkers = [];


        var tableData = [];
        var filteredData = [];
        var mapFilteredData = [];
        var furtherFilteredData = [];

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
            google.maps.event.addListener(map, 'dragend', function boundsChanged() {
                $.ajax({
                    url: "<?= base_url('admin/ajaxGetRoutingFORMAPSVERIZONONLY/') ?>",
                    dataType: "json",
                    type: "POST",
                    data: {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    success: function (data) {
                        
                            
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
                
                                    
                        var markers = MapMarkers;
                        var markerarray = [];
                        var count = 0;
                        var count2 = 0;
                        
                        // $('#loading').show();                

                        filteredMarkers = [];

                        MapMarkers.forEach(item => {
                            item.setMap(null);
                        });


                        var latlngbounds = new google.maps.LatLngBounds();
                        if(data.possible_errors != "") {
                            if(data.possible_errors.Message != "") {
                                swal("Verizon Connect Error", data.possible_errors.Message, "error");
                            } else {
                                swal("Verizon Connect Error", data.possible_errors, "error");
                            }
                        }
                        var full_vehicle_data = data.full_vehicle_data
                        full_vehicle_data.forEach(function(vehicle) {
                            marker = new google.maps.Marker({
                                icon: '<?= base_url("assets/img/driver.png") ?>',
                                position: new google.maps.LatLng(vehicle.Latitude, vehicle.Longitude),
                                lat: vehicle.Latitude,
                                lng: vehicle.Longitude,
                                map: map,
                                title: vehicle.DriverName
                            });
                            latlngbounds.extend(marker.position);
                            MapMarkers.push(marker);
                        });

                        if (set_initial_center == true) {
                            latlngbounds.extend(marker.position);
                            map.fitBounds(latlngbounds);
                            map.setZoom(4);
                            set_initial_center = false;
                        }
                    }
                });
            });

            
            console.log("Initial load entering idle listener");
            google.maps.event.trigger(map, 'dragend');
            
        } //end LoadMap()

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

        LoadMap();

    });
</script>