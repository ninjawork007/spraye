<script src="https://code.jquery.com/ui/1.8.24/jquery-ui.min.js" ></script>
<style type="text/css">
    .btndiv > * {
        margin-bottom: 4px;
    }
    
  #dvMap {
       height: 865px;
       margin-left: -20px;
       margin-top: -20px;
      }
   .datatable-footer, .datatable-header{
    padding: 0
   }   

   .panel-flat > .panel-heading {
      padding-top: 0;
      padding-bottom: 0;
   }
   .toolbar {
    float: left;
    padding-left: 5px;
}
.label-prospect {
    background-color: #f98800;
}
#modal_add_service .row {margin-bottom: 5px;}
</style>

<div class="content">
    <div class="panel panel-flat">

        <div class="panel panel-flat">
             <div class="panel-heading">
                   <h5 class="panel-title">
                     <div class="form-group">

                        <div class="row">
                          
                           <div class="col-md-6 btndiv">
                                <button class="btn btn-warning" id="CoordsButton">Save Service Area</button>
                                <button class="btn btn-info" id="delete-button">Delete Selected Area</button>
                                <button class="btn btn-info" id="delete-all-button">Delete All</button>
                           </div>

                           <div class="col-md-6 toggle-btn">
                             <div style="float: right;"> 
                               <label>
                               Map view&nbsp;<input name="changeview" type="checkbox" class="switchery-primary" checked="checked">
                                  Table view
                               </label>
                              </div>                             
                           </div>

                        </div>


                      </div>
                         
                   </h5>
              </div>
          </div> 
      
  

      <div class="panel-body">
        
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; 
         ?></b>


       <div class="row">


        <div class="col-md-4" id="mapdiv" >
            <div id="dvMap"><span style="
  position: absolute;
  top: 50%;
  left: 50%;
  margin: -50px 0 0 -50px;">No data found!</span></div>
        </div>

    

        <div class="col-md-8" id="tablediv" >


                    <div  class="table-responsive table-spraye">
                       <table  class="table" id="property_datatable">    
                            <thead>  
                                <tr>
                                    <th><input type="checkbox"  id="select_all" /></th>  
                                    <th >Property Name</th>                        
                                    <th>Address</th>
                                    <!-- <th>Address 2</th>
                                    <th>City, State, Zip</th>
                                    <th>Area</th>
                                    <th>Type</th>
                                    <th>Yard Square Feet</th>
                                    <th>Notes</th> -->
                                    <th>Program</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>  
                            </thead>
                            
                        </table>
                     </div>

     
         
         
        </div>         
       </div>
      </div>
      </div>
</div>

  <!-- Primary modal -->
          <div id="modal_add_csv" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Add Property</h6>
                </div>

              <form  name="csvfileimport"   action="<?= base_url('admin/addPropertyCsv') ?>" method="post" enctype="multipart/form-data" >

              <div class="modal-body">
                    
               
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">                   
                          <label>Select csv file</label>
                          <input type="file"  name="csv_file">
                        </div>
                      </div>
                    </div>
                        
                     <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" id="assignjob" class="btn btn-success">Save</button>
                     </div>
                   </div>
                </form>
              </div>
            </div>
          </div> 
          <!-- /primary modal -->

        <div id="modal_service_area_polygon" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Save Service Area</h6>
                </div>
                <form  name="service-polygon" id="service-polygon-form" action="<?= base_url('admin/setting/addServicrAreaData') ?>" method="post" enctype="multipart/form-data" >
                  <div class="modal-body">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">
                          <span data-popup="tooltip-custom" title="" data-placement="top" data-original-title="Either type a new Serivce Area Name, or an existing Service Area Name and select from the dropdown list."> <i class=" icon-info22 tooltip-icon"></i> </span>
                          <label>Service Area</label>
                          <input type="text" class="form-control" id="category_area_name" name="category_area_name" placeholder="Type a new or existing Service Area Name">
                          <input type="hidden" class="form-control" name="service_area_polygon" >
                          <input type="hidden" class="form-control" name="property_area_cat_id" >
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" id="service-polygon" class="btn btn-success">Save</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div> 

          <div id="modal_assign_program" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Assign Program</h6>
                </div>

              <form  name="assignProgram" action="<?= base_url('admin/assignProgramToProperies') ?>" method="post" enctype="multipart/form-data" >

              <div class="modal-body">
                    
               
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">                   
                          <label>Assign Program</label>

                            <select class="form-control" name="program_id" id= "selected_program_id">
                              <option value="">Select Any Program</option>
                            <?php if($programlist) {  foreach ($programlist as $value) { ?>
                                <option value="<?= $value->program_id ?>"><?= $value->program_name ?></option>
                            <?php } } ?>
                            </select>

                        </div>
                      </div>
                    </div>
                        
                     <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" id="assignjob" class="btn btn-success">Save</button>
                     </div>
                   </div>
                </form>
              </div>
            </div>
          </div> 
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
                        <div class="col-sm-12">                   
                          <label>Add Service</label>
                            <select class="form-control" name="job_id" id="selected_job_id" required>
                              <option value="">Select Any Service</option>
                            <?php if($servicelist) {  foreach ($servicelist as $value) { ?>
                                <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>
                            <?php } } ?>
                            </select>
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
				  </div>
				  <div class="form-group">
						<div class="row" id="price_override_modal">
							
							<div class="col-sm-12" id="addServicePOLabel">                   
                   			<label><b>Price Override Per Service</b></label>
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
<!-------------------------------------------->
<!-- Cancel Property Modal -->
<div class="modal fade" id="modal_cancel_property">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
    		<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h6 class="modal-title">Cancel Property</h6>
		</div>
      	<div class="modal-body">
			<form action="admin/cancelProperty/" method="POST" enctype="multipart/form-data" id="cancel-property-form">
				<input type="hidden" name="property_id" id="property_id" value="">
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">                   
						  <label>What is the reason for canceling?</label>
							<select class="form-control" name="cancel_reasons" id="cancel_reasons" required>
							  <option value="">Select an option</option>
							<?php if($cancel_reasons){foreach ($cancel_reasons as $reason){?>
								<option value="<?=$reason->cancel_name?>"><?=$reason->cancel_name?></option>
							<?php } } ?>
								<option value="other">Other</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row" id="other-reason-div" style="display:none; margin-top: 10px;">
					<div class="form-group">
						<div class="col-md-12">                   
						  <label>Other Reason:</label>
						  <input type="text" class="form-control" name="other_reason">
						</div>
					</div>
				</div>
				<div class="row" style="margin-top: 10px;">
					<div class="form-group">
						<div class="col-md-12 checkbox-inline"> 
							<label class="control-label col-md-11">Send cancellation email to customer?</label>
							<input class="checkbox col-md-1" type="checkbox" name="customer_email" id="customer_email" />
						</div>
					</div>
				</div>
			</form>
      </div>
      <div class="modal-footer">
		  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
		  <button id="submit-cancel-property" type="submit" class="btn btn-success">Submit</button>                   
      </div>
    </div>
  </div>
</div> 
<!-- End Cancel Property Modal -->
<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>
<script type="text/javascript">
  $('.confirm_delete').click(function(e){
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

$("#service-polygon-form").submit(function() {
    $("#loading").css("display", "block");
    $.ajax({
        url: "<?= base_url('admin/setting/addServicrAreaData') ?>",
        data: $("#service-polygon-form").serialize(),
        type: "POST",
        dataType: 'json',
        success: function(e) {
            $("#loading").css("display", "none");
            if (e != 0 && e != 1) {
                document.querySelector('#service-polygon').innerHTML = e;
            } else {
                $('.modal-backdrop').css("display", "none");
                swal(
                    'Service Area',
                    'Added Successfully',
                    'success'
                ).then(function() {
                    location.reload(); 
                   })
            }
        },
        error: function(e) {
            $("#loading").css("display", "none");
            alert("Something went wrong");
        }
    });
    return false;
});
</script>   

<script>

    $('input[name=changeview]').change(function () {
        var mode = $(this).prop('checked');
      //  alert(mode);
        if(mode==false) {
          $('#tablediv').css('display','none');
          $('#mapdiv').css('display','block');

          $("#mapdiv").removeClass('col-md-4');
          $("#mapdiv").addClass('col-md-12');

        } else {
          
          $('#mapdiv').css('display','none');
          $('#tablediv').css('display','block');
          $("#tablediv").removeClass('col-md-8');
          $("#tablediv").addClass('col-md-12');

         
        }
        // $.ajax({
        //     type: 'POST',
        //     url: 'http://125.99.173.44/daddy_pocket/admin/userUpdate',
        //     data: {mode: mode, user_id: user_id},
        //     success: function (data)
        //     {
        //         // alert(data);
        //     }
        // });

    });
</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing&key=<?php echo GoogleMapKey; ?>&sensor=false"></script>
<script type="text/javascript">
var markers_array = [];
   // DataTable
   var table =  $('#property_datatable').DataTable({
        "aLengthMenu": [[10,20,50,100,200,500,-1],[10,20,50,100,200,500,"All"]],
        "processing": true,
        "serverSide": true,
        "paging":true,
        "pageLength":<?= $this ->session->userdata('compny_details')-> default_display_length?>,
        "order":[[1,"asc"]],
        "ajax":{
            "url": "<?= base_url('admin/ajaxGetProperty/')?>",
            "dataType": "json",
            "type": "POST",
            "data":{
                
            }
        },
        "deferRender":false,
        "columnDefs": [
            {"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}},
        ],
        "select":"multi",
        "columns": [
            {"data": "checkbox", "checkboxes":true, "stateSave":true, "searchable":false, "orderable":false},
            {"data": "property_name", "name":"Property Name", "orderable": true, "searchable": true },
            {"data": "property_address", "name":"Affress", "searchable":true, "orderable": true },
            {"data": "program_text_for_display", "name":"Program", "orderable": true, "searchable": true },
            {"data": "customer_name", "name":"Customer", "searchable":true, "orderable": true },
            {"data": "property_status", "name":"Status", "searchable":true, "orderable": true },
            {"data": "action", "name":"Action", "orderable": false }
        ],
        language: {
            search: '<span></span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        dom: 'Bl<"toolbar">frtip',
        drawCallback: function(settings, json){
            $("div.toolbar").html('<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled ><i class=" icon-trash btn-del"></i> Delete</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-info" id="assignbutton" onclick="assignmultiple()" disabled > <i class=" icon-plus22"></i> Assign Program</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-info" id="addServiceButton" onclick="addSingleService()" disabled > <i class=" icon-plus22"></i> Add Standalone Service</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-info" id="addServiceArea" onclick="assignServiceArea()" disabled > <i class=" icon-plus22"></i> Auto Assign Service Area</button>');
            $("#select_all").change(function(){  //"select all" change 
                var status = this.checked; // "select all" checked status
                if (status) {
                    $('#deletebutton').prop('disabled', false);
                    $('#assignbutton').prop('disabled', false);
                    $('#addServiceButton').prop('disabled', false);
                    $('#addServiceArea').prop('disabled', false);
                    allchecked();


                } else {
                    $('#deletebutton').prop('disabled', true);
                    $('#assignbutton').prop('disabled', true);
                    $('#addServiceButton').prop('disabled', true);
                    $('#addServiceArea').prop('disabled', true);
                    allunchecked();

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


            var checkBoxes = $('table .myCheckBox');
            checkBoxes.change(function () {
                $('#deletebutton').prop('disabled', checkBoxes.filter(':checked').length < 1);
                $('#assignbutton').prop('disabled', checkBoxes.filter(':checked').length < 1);
                $('#addServiceButton').prop('disabled', checkBoxes.filter(':checked').length < 1);
                $('#addServiceArea').prop('disabled', checkBoxes.filter(':checked').length < 1);
            });
            checkBoxes.change();  

            
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
                            selectcheckbox.push($(this).attr('property_id'));
                        }); 
                    
                        //  alert(selectcheckbox);

                    
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('admin/deletemultipleProperties') ?>",
                            data: {properties : selectcheckbox }
                        }).done(function(data){
                                if (data==1) {
                                    swal(
                                    'properties !',
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

            $("input:checkbox.map").click(function() {
                position =  $(this).val();
                
                if(!$(this).is(":checked")){

                    var data = markers[position];
                    // alert(JSON.stringify(data));
                    var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                    marker = new google.maps.Marker({
                        icon:'<?= base_url("assets/img/default.png") ?>',
                        position: myLatlng,
                        map: map,
                        title: data.name
                    });
                } else {

                    var data = markers[position];
                    var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                    marker = new google.maps.Marker({
                        icon:'<?= base_url("assets/img/till.png") ?>',
                        position: myLatlng,
                        map: map,
                        title: data.name
                    });
                }
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
    });
    markers = [];
    table.on('xhr.dt',
        function (e, settings, data, xhr) {
            markers_array = [];
            // data = null;
            if (data && data.data && data.data.length > 0) {
                data.data.forEach(function (data) {
                    markers_array.push(data.marker[0]);
                });
            }


            markers = markers_array;
            if (markers.length > 0) {
                LoadMap();
                map;


                var marker;

                function LoadMap() {
                    var mapOptions = {

                        center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
                        zoom: 10,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
                    $("#dvMap").find('span').remove()
                    SetMarker(0);
                };

                var all_overlays = [];
                var selectedShape;
                var selectedId;
                var selectedName;
                var drawingManager;
                var coordinates;
                var coordinates_array = [];

                function clearSelection() {
                    if (selectedShape) {
                        selectedShape.setEditable(false);
                        selectedShape = null;
                    }
                }

                function setSelection(shape, elm) {
                    clearSelection();
                    selectedShape = shape;
                    selectedId = elm !== undefined ? elm.property_area_cat_id : null;
                    selectedName = elm !== undefined ? elm.marker : null;
                    shape.setEditable(true);
                }

                function deleteSelectedShape() {
                    if (selectedShape) {
                        selectedShape.setMap(null);
                        if (selectedId) {
                            $.post("/admin/setting/addServicrAreaData", {
                                "service_area_polygon": null,
                                "property_area_cat_id": selectedId,
                                "category_area_name": selectedName,
                            }, (d) => {
                                if (d) {
                                    swal(
                                        'Property Updated!',
                                        'Property Service Area Polygon Deleted',
                                        'success'
                                    ).then(function () {
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
                    }
                }

                function deleteAllShape() {
                    for (var i = 0; i < all_overlays.length; i++) {
                        all_overlays[i].overlay.setMap(null);
                    }
                    all_overlays = [];
                }

                function SetMarker(position) {
                    // Remove previous Marker.
                    if (marker != null) {
                        marker.setMap(null);
                    }
                    // Set Marker on Map.
                    if (position) {

                    } else {
                        allunchecked();
                    }
                };

                drawingManager = new google.maps.drawing.DrawingManager({
                    drawingMode: google.maps.drawing.OverlayType.POLYGON,
                    drawingControl: true,
                    drawingControlOptions: {
                        position: google.maps.ControlPosition.TOP_CENTER,
                        drawingModes: [
                            google.maps.drawing.OverlayType.POLYGON,
                        ]
                    },
                    polygonOptions: {
                        editable: true
                    }
                });
                drawingManager.setMap(map);
                google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
                    all_overlays.push(e);
                    if (e.type != google.maps.drawing.OverlayType.MARKER) {
                        // Switch back to non-drawing mode after drawing a shape.
                        drawingManager.setDrawingMode(null);
                        // Add an event listener that selects the newly-drawn shape when the user
                        // mouses down on it.
                        var newShape = e.overlay;
                        newShape.type = e.type;
                        google.maps.event.addListener(newShape, 'click', function () {
                            setSelection(newShape);
                        });
                        setSelection(newShape);
                    }
                });
                google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
                    coordinates = (polygon.getPath().getArray());
                });
                google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
                google.maps.event.addListener(map, 'click', clearSelection);
                google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
                google.maps.event.addDomListener(document.getElementById('delete-all-button'), 'click', deleteAllShape);

                function getCoordinates() {

                    for (var i = 0; i < coordinates.length; i++) {
                        lat = coordinates[i].lat();
                        lng = coordinates[i].lng();
                        coordinates_array.push({"lat": lat, "lng": lng});
                    }
                    $("#modal_service_area_polygon").modal("show");
                    $("input[name=service_area_polygon]").val(JSON.stringify(coordinates_array));

                }

                google.maps.event.addDomListener(document.getElementById('CoordsButton'), 'click', getCoordinates);
                var polygon_array = <?= (isset($polygon_bounds) && $polygon_bounds != "" ? json_encode($polygon_bounds) : "");?>
                // console.log(polygon_array);
                // polygon_array = [JSON.parse(polygon_array)];
                // var service_poly = [{lat:47.50744955379592, lng: -82.74775243632813},{lat:45.07884209539964, lng: -78.00165868632813},{lat:49.028402784002296, lng: -74.48603368632813}];
                if (typeof polygon_array !== 'undefined') {
                    polygon_array.forEach(elm => {
                        // console.log(elm)
                        var poly_draw = new google.maps.Polygon({
                            paths: [JSON.parse(elm.latlng)],
                            strokeColor: '#FF0000',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#FF0000',
                            fillOpacity: 0.35,
                            name: elm.marker
                        });
                        poly_draw.setMap(map);
                        attachPolygonInfoWindow(poly_draw, "Service area: " + elm.marker, elm)
                    })
                }

                function attachPolygonInfoWindow(polygon, label, elm) {
                    var infoWindow = new google.maps.InfoWindow();
                    google.maps.event.addListener(polygon, 'mouseover', function (e) {
                        infoWindow.setContent(label);
                        var latLng = e.latLng;
                        infoWindow.setPosition(latLng);
                        infoWindow.open(map);
                    });
                    google.maps.event.addListener(polygon, 'click', function (e) {
                        this.setOptions({strokeColor: "#000000", fillColor: '#333333'});
                        setSelection(polygon, elm);
                        infoWindow.close(map);
                        // this.setEditable(true);
                    });
                }
            }
        }
    ); 
    
    function allchecked() {

        console.log("All checked");

        var infoWindow = new google.maps.InfoWindow();
        var lat_lng = new Array();
        var latlngbounds = new google.maps.LatLngBounds();
        for (i = 0; i < markers.length; i++) {
            var data = markers[i]
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            lat_lng.push(myLatlng);
            var marker = new google.maps.Marker({
                icon:'<?= base_url("assets/img/till.png") ?>',
                position: myLatlng,
                map: map,
                title: data.title
            });

            console.log("Before attaching PA", map)

            latlngbounds.extend(marker.position);
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent(data.address);
                    infoWindow.open(map, marker);
                });
            })(marker, data);

            (function (marker, data) {
                marker.addListener('mouseover', function() {
                    console.log("Mousein");
                    // infowindow.open(map, this);
                    infoWindow.setContent(data.address);
                    infoWindow.open(map, marker);
                });
            })(marker, data);

            (function (marker, data) {
                // assuming you also want to hide the infowindow when user mouses-out
                marker.addListener('mouseout', function() {
                    // infowindow.close();
                    console.log("Mouse out");
                    infoWindow.setContent('data.address');
                    infoWindow.close(map, marker);
                });
            })(marker, data);
            // console.log("After attaching even", marker)
        }
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);

        }
        function allunchecked() {

        var infoWindow = new google.maps.InfoWindow();
        var lat_lng = new Array();
        var latlngbounds = new google.maps.LatLngBounds();
        for (i = 0; i < markers.length; i++) {
            var data = markers[i]
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            lat_lng.push(myLatlng);
            var marker = new google.maps.Marker({
                icon:'<?= base_url("assets/img/default.png") ?>',
                position: myLatlng,
                map: map,
                title: data.name
            });
            latlngbounds.extend(marker.position);
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent(data.address);
                    infoWindow.open(map, marker);
                });
            })(marker, data);
        }
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
        }
  
  /*
    $('.datatable-basic').DataTable({
       "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
       "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],


       language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
        
   
            dom: 'l<"toolbar">frtip',
            initComplete: function(){
     
           $("div.toolbar")
              .html('<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled ><i class=" icon-trash btn-del"></i> Delete</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-info" id="assignbutton" onclick="assignmultiple()" disabled > <i class=" icon-plus22"></i> Assign Program</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-info" id="addServiceButton" onclick="addSingleService()" disabled > <i class=" icon-plus22"></i> Add Standalone Service</button>');           
        }       
     });
    */
    function assignmultiple() {


        var selected = [];

        $("input:checkbox[name=selectcheckbox]:checked").each(function(){
            selected.push($(this).attr('property_id'));
        }); 

        if (selected.length > 0) {
            $('#modal_assign_program').modal('show');

        } else {

        swal({
                type: 'error',
                title: 'Oops...',
                text: 'Something went wrong!'
            })
        }
    }
    
    function assignServiceArea() {
        var selected = [];
            $("input:checkbox[name=selectcheckbox]:checked").each(function(){
                selected.push($(this).attr('property_id'));
            }); 
        if(selected.length > 0) {
            //get each property_id's lat and lng
            var property_list = markers;
            var service_area_ids = [];
            var counter=0;
            property_list.forEach(elm => {
                //check if propery_id is selected
                if(selected.includes(elm.property_id)){     
                    //then get polygon bounds
                    var polygon_array = <?php print $polygon_bounds ? json_encode($polygon_bounds) : "''";?>;
                    polygon_array.forEach(polygon => {
                        var poly_draw = new google.maps.Polygon({ 
                            paths: [JSON.parse(polygon.latlng)]
                        });
                        const propertyInPolygon = google.maps.geometry.poly.containsLocation({lat: parseFloat(elm.lat), lng: parseFloat(elm.lng)},poly_draw) ? 1 : 0;
                        if(propertyInPolygon){
                            service_area_ids.push({"property_area": polygon.property_area_cat_id, "property_id": elm.property_id});
                        }
                    });
                    counter++;
                }
            });
            //if match found, then update property_tbl.property_area
            if(selected.length == counter){
                $.post("/admin/updatePropertyData", {"data": service_area_ids}, (d) => {
                    if (d) {
                        swal(
                            'Property Updated!',
                            'Property Service Area Updated',
                            'success'
                        ).then(function() {
                            location.reload(); 
                        });
                    }else{
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        })
                    }
                });
            }
            // done
        }else{
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'Something went wrong!'
            })
        }
    }

    $('#modal_add_service').on('hidden.bs.modal', function (e) {
        $('div#price_override_modal').not('div#addServicePOLabel').empty();
    });		    
    function addSingleService() {
        var selected = [];
        var property_names = [];

            $("input:checkbox[name=selectcheckbox]:checked").each(function(){
                selected.push($(this).attr('property_id'));
                property_names.push($(this).data('propname'));
            }); 


        if (selected.length > 0) {
            $(selected).each(function(i){
                $('div#price_override_modal').append('<div class="col-sm-12"><label>'+property_names[i]+'</label><input type="number" class="form-control" min=0 name="add_job_price_override['+i+']" value="" placeholder="(Optional) Enter Price Override Here" data-propname="'+property_names[i]+'" data-propid="'+this+'"></div>');
                
            });
            $('#modal_add_service').modal('show');

        } else {
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'Something went wrong!'
            })
        }
    }

    $('form[name="addService"] button[type="submit"]').on('click', function(e){
        e.preventDefault();  
        //// NEED TO FORMAT FOR MULTIPLE PROPERTIES ////
        var serviceId = $('#selected_job_id').val();
        var serviceName = $('#selected_job_id option:selected').text();

        var post = [];
        $('div#price_override_modal input').each(function(n){
            var propertyId = $(this).data('propid');
            var propertyName = $(this).data('propname');
            var programName = serviceName + "- Standalone";
            var priceOverride = $(this).val();
            var programPrice = $('select#add_service_program_price').val();
            
            if (priceOverride == '') {
                var price_override_set = 0;
            } else {
                var price_override_set = 1;
            }
                
            var property = {
                service_id:serviceId,
                service_name:serviceName,
                property_id: propertyId,
                property_name:propertyName,
                program_name: programName,
                price_override:priceOverride,
                is_price_override_set:price_override_set,
                program_price:programPrice
            };	
            
            post.push(property);
            // console.log(post);
        });
        //post = JSON.stringify(post);
        //post = JSON.parse(post);
        //console.log(post);
        $.ajax({

            type: 'POST',
            url: "<?=base_url('admin/job/addJobToProperty')?>",
            data: {post},
            dataType: "JSON",
            success: function (data){
                console.log(data)

            }

            }).done(function(data){
                $('#modal_add_service').modal('hide');
                $('div#price_override_modal').empty();
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
    });

    var cache = {};
    $( "#category_area_name" ).autocomplete({
        minLength: 2,
        source: function( request, response ) {
            var term = request.term;
            $("input[name='property_area_cat_id']").val("");
            if ( term in cache ) {
                response( cache[ term ] );
                return;
            }
            $.post( "/admin/setting/searchServiceArea", request, function( data, status, xhr ) {
                cache[ term ] = JSON.parse(data);
                response( JSON.parse(data) );
            });
        },
        select: function( event, ui, response ) {
            this.value = ui.item.value;
            $("input[name='property_area_cat_id']").val(ui.item.id);
            return false;
        },
    });

    // handle cancel property
    function cancelProperty(propertyId){
        $('input#property_id').val(propertyId);
        $('#modal_cancel_property').modal('show');
    }
    $('select#cancel_reasons').change(function(){
        let selected = $(this).val();
        if(selected == 'other'){
            $('div#other-reason-div').show();
        }else{
            $('div#other-reason-div').hide();
        }
    });	
    $('#submit-cancel-property').click(function(e){
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover the jobs or programs associated with this property!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009402',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if(result.value){
                $('form#cancel-property-form').submit();
            }else{
                $('#modal_cancel_property').modal('hide');
            }
        });
    });
</script>