<style>
	.section.variant-1:not(.variant-2) .header {
		font-size: 1.2rem;
	}
	.section.variant-2 .header .title {
		font-size: 1.5rem;
	}
	.timeframe ul li a {
		font-size: 1rem;
	}
	.navigation li a {
		font-size: 14px;
	}
	.modal-sub-button {
		margin-top: 30px;
    	text-align: center;
	}
	.table-responsive {
		overflow-x: auto;
		/* min-height: .01%; */
		min-height: 100px;
	}
	#items_processing{
		top:85%!important;
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
   .btn-group {
   margin-left: -4px !important;
   margin-top: -1px !important;
   padding: 2px 2px;
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
   .label-refunded, .bg-refunded {
      background-color : #fd7e14;
      border-color : #fd7e14;
   }
   .toolbar {
   float: left;
   padding-left: 5px;
	margin-bottom: 5px;
   }
   .toolbar-1 {
   float: left;
   padding-left: 5px;
	margin-bottom: 5px;
   }
   .dataTables_filter {
   margin-left: 60px !important;
   }
   #itemtablediv{
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
	#mytbl {
		border: 1px solid
		#6eb1fd;
		border-radius: 4px;
	}
	.dt-buttons {
		display: inline-block;
		margin: 0 10px 20px 10px;
	}

</style>

<!-- Content area -->
<div class="content invoicessss">
  <div id="loading" >
    <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
  </div>
  <div class="panel-body">
    <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

    <div id="locationtablediv">
      <div  class="table-responsive table-spraye">
        <table class="table" id="locations">
          <thead>
            <tr>
				<th>Name</th>
				<th>Street</th>
				<th>City</th>
				<th>State</th>
				<th>Zip</th>
				<th>Country</th>
				<th>Phone Number</th>
				<!-- <th>Sub-Location(s)</th> -->
				<th>Action</th>
            </tr>
          </thead>

        </table>
      </div>
    </div>
  </div>
</div>
<div class="content invoicessss">
  <div id="loading" >
    <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
  </div>
  <div class="panel-body">

    <div id="sublocationtablediv">
      <div  class="table-responsive table-spraye">
        <table class="table" id="sub_locations">
          <thead>
            <tr>
				<th>Sub Location Name</th>
				<th>Location</th>
				<th>Total Inventory Value</th>
				<th>Fleet Number</th>
				<th>Action</th>
            </tr>
          </thead>

        </table>
      </div>
    </div>
  </div>
</div>


<!-- Start of new location modal -->
	<div class="modal fade" id="new_location">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Physical Location</h6>
				</div>
					
				
				<form action="<?= base_url('inventory/Backend/Locations/create') ?>"  id="location_form"  name='addnewlocation' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
					<input type="hidden" name ="location_id" id="id" value = "">
						<div class="form-group">
							
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Location Name</label>
									<input type="text" class="form-control" name="location_name" id="name" value = ""
										placeholder="Enter Location Name">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Phone Number</label>
									<input type="text" class="form-control" name="location_phone" id="phone" value = ""
										placeholder="Enter Phone Number">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Street</label>
									<input type="text" class="form-control" name="location_street" id="street" value = ""
										placeholder="Enter Street">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>City</label>
									<input type="text" class="form-control" name="location_city" id="city" value = ""
										placeholder="Enter City">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>State</label>
									<input type="text" class="form-control" name="location_state" id="state" value = ""
										placeholder="Enter State">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Zip Code</label>
									<input type="text" class="form-control" name="location_zip" id="zip" value = ""
										placeholder="Enter Zip Code">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Country</label>
									<input type="text" class="form-control" name="location_country" id="country" value = ""
										placeholder="Enter Country">
								</div>
							</div>
						</div>
						
					
						<hr  />
						
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit" id="assignjob" class="btn btn-success">Save</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
<!-- End of location modal -->
<!-- Start of update location modal -->
	<div class="modal fade" id="edit_location">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Update Location</h6>
				</div>
				<form action="<?= base_url('inventory/Backend/Locations/updateLocation') ?>" name="editlocation" id="edit_location"  method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
					<input type="hidden" name ="location_id" id="location_id">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Name</label>
									<input type="text" class="form-control" name="location_name" id="name" value = ""
										placeholder="Enter Name">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Phone Number</label>
									<input type="text" class="form-control" name="location_phone" id="phone" value = ""
										placeholder="Enter Phone Number">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Street</label>
									<input type="text" class="form-control" name="location_street" id="street" value = ""
										placeholder="Enter Street">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>City</label>
									<input type="text" class="form-control" name="location_city" id="city" value = ""
										placeholder="Enter City">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>State</label>
									<input type="text" class="form-control" name="location_state" id="state" value = ""
										placeholder="Enter State">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Zip Code</label>
									<input type="text" class="form-control" name="location_zip" id="zip" value = ""
										placeholder="Enter Zip Code">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Country</label>
									<input type="text" class="form-control" name="location_country" id="country" value = ""
										placeholder="Enter Country">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Created By</label>
									<input type="text" class="form-control" name="created_by" id="by"
									value=""
										placeholder="Enter Creator">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Created At</label>
									<input type="text" class="form-control" name="created_at" id="date" value = ""
										placeholder="Enter Created Date">
								</div>
							</div>
						</div>
					
						<hr  />
						
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-success">Save</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
<!-- End of location modal -->
<!-- Start of sub location modal -->
	<div class="modal fade" id="new_sub_location">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Sub Location</h6>
				</div>
				<form action="<?= base_url('inventory/Backend/Locations/createSub') ?>" id="sub_location_form" name='addsublocation' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Location Name</label>
									<select class="form-control" name="location_id"  >
										<option value="">Choose a location</option>
										<?php foreach($all_locations as $location){?>
										<option value="<?php echo $location->location_id; ?>"><?php echo $location->location_name; ?></option>
										<?php } ?>
									</select>
									
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Sub Location Name</label>
									<input type="text" class="form-control" name="sub_location_name" value=""
										placeholder="Enter Sub Location Name">
								</div>
								
							</div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                <label>Fleet Number</label>
                                    <select class="form-control" name="sub_location_fleet_no">
                                        <option value="">Choose a Fleet Number</option>
                                        <?php if(!empty($all_fleets)){
                                            foreach($all_fleets as $fleet){?>
                                            <option value="<?php echo $fleet->fleet_number; ?>"><?php echo $fleet->fleet_number . ' - ' . $fleet->v_name ?></option>
                                        <?php } } ?>
                                    </select>										
                                </div>
                            </div>
						</div>
					
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit" id="assignjob" class="btn btn-success">Save</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
<!-- End of location modal -->
<!-- Start of edit sub location modal -->
	<div class="modal fade" id="edit_sub_location">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Update Sub Location</h6>
				</div>
				
				<form action="<?= base_url('inventory/Backend/Locations/updateSub') ?>" id="update_sub_location_form" name='editsublocation' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
					<input type="hidden" name ="sub_location_id" id="sub_id" value = "">
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Location Name</label>
									<select class="form-control" name="location_id" id="sub_loc_id" >
										<option value="0">Choose a Location</option>
									</select>
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Sub Location Name</label>
									<input type="text" class="form-control" name="sub_location_name" id="sub_name" value=""
										placeholder="Enter Sub Location Name">
								</div>
							</div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                <label>Fleet Number</label>
									<select class="form-control" name="sub_location_fleet_no" id="fleet_number">

                                    </select>
                                </div>
                            </div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit" id="assignjob" class="btn btn-success">Save</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
<!-- End of location modal -->

<script type="text/javascript">
	
	$('document').ready(function() {
		console.log( "ready!" );

		var table =  $('#locations').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[2,'desc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Locations/ajaxGetLocations')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,

		   "select":"multi",
		   "columns": [
		            {"data": "location_name", "name":"Name", "searchable":true, "orderable": true },
			   	    {"data": "location_street", "name":"Street", "searchable":true, "orderable": true },
			   	    {"data": "location_city", "name":"City", "searchable":true, "orderable": true },
			   	    {"data": "location_state", "name":"State", "searchable":true, "orderable": true },
			   	    {"data": "location_zip", "name":"Zip", "searchable":true, "orderable": true },
			   	    {"data": "location_country", "name":"Coutry", "searchable":true, "orderable": true },
		            {"data": "location_phone", "name":"Phone Number", "searchable":true, "orderable": true },
			   	    {"data": "actions", "name":"Actions","class":"table-action", "searchable":false, "orderable":false}
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
		   dom: '<"toolbar-1">frtip',
		    buttons:[
				{
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon',
				columns: [0,1,2,3,4,5,6,7],


            },
				],
		   initComplete: function(){

				$("div.toolbar-1")
					.html('<a href="<?php echo base_url('inventory/Frontend/Locations/exportLocationsCSV'); ?>"><button type="button" class="btn btn-success">Export CSV</button></a><a href="" data-toggle="modal" data-target="#new_location"><button type="button"  class="btn btn-primary" id="newlocationsbtn">New Location</button></a>');
			},

		});
		
		var table =  $('#sub_locations').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[2,'desc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Locations/ajaxGetSubLocations')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,

		   "select":"multi",
		   "columns": [
		            {"data": "sub_location_name", "name":"Sub Location Name", "searchable":true, "orderable": true },
		            {"data": "location_id", "name":"Location", "searchable":true, "orderable": true },
			   	    {"data": "total_inventory_value", "name":"Total Inventory Value", "searchable":true, "orderable": true },
			   	    {"data": "sub_location_fleet_no", "name":"Fleet Number", "searchable":true, "orderable": true },
			   	    {"data": "actions", "name":"Actions","class":"table-action", "searchable":false, "orderable":false}
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
		   dom: '<"toolbar">frtip',
		    buttons:[
				{
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon',
				columns: [0,1,2,3,4],


            },
				],
		   initComplete: function(){

				$("div.toolbar")
					.html('<a href="<?php echo base_url('inventory/Frontend/Locations/exportSubLocationsCSV'); ?>"><button type="button" class="btn btn-success">Export CSV</button></a><a href="" data-toggle="modal" data-target="#new_sub_location"><button type="button"  class="btn btn-primary" id="newsublocationbtn">New Sub Location</button></a>');
			},

		});

	});

</script>
<script>
$(function() {
  $('#edit_location').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
	// Location inputs
    var id = button.data('id'); // Extract info from data-* attributes
    $('#location_id').val(id);
	var location = button.data('name');
    var street = button.data('street');
    var city = button.data('city');
    var state = button.data('state');
    var zip = button.data('zip');
    var country = button.data('country');
    var phone = button.data('phone');
    var createBy = button.data('created');
    var createAt = button.data('date');
	// Sub-Location inputs
	var subID = button.data('subid');
    var sublocation = button.data('sublocation');
    var fleet = button.data('fleet');
    var inventory = button.data('inventory');


    var modal = $(this);
    modal.find('#location_id').val(id);
    modal.find('#name').val(location);
    modal.find('#phone').val(phone);
    modal.find('#street').val(street);
    modal.find('#city').val(city);
    modal.find('#state').val(state);
    modal.find('#zip').val(zip);
    modal.find('#country').val(country);
    modal.find('#by').val(createBy);
    modal.find('#date').val(createAt);
	//Sub Location
    modal.find('#subid').val(subID);
    modal.find('#sub_name').val(sublocation);
    modal.find('#inventory').val(inventory);
    modal.find('#fleet').val(fleet);
	// console.log(id);
  });
});

$(document).on('click', '.sub_modal_trigger', function(e){
	// e.preventDefault();
    var locations = $(this).data('locs').split('::');
    var fleets = $(this).data('fleets').split('::');
    // console.log(locations);
    var id = $(this).data('id');
    var name = $(this).data('name');
	var locationid = $(this).data('locid');
    var locationname = $(this).data('location');
    var fleet_curr = $(this).data('fleet');
    // console.log(name);
   
    $('#sub_id').val(id);
    $('#sub_name').val(name);
    $('#fleet_no').val(fleet_curr);
	
    // $('#item_description').val(desc);

    var selectLocationsHTML = "";

    var locationStr = locationid + ':' + locationname;
	console.log(locationStr);

	locations.forEach(loc => {
       if(loc == locationStr){
		selectLocationsHTML += "<option value='" + loc.split(':')[0] + "' selected>" + loc.split(':')[1] + "</option>";
       } else {
		selectLocationsHTML += "<option value='" + loc.split(':')[0] + "'>" + loc.split(':')[1] + "</option>";
       }
    });

    var fleetHTML = '<option value="">Choose a fleet number</option>';

    

    fleets.forEach(fleet => {
        if(fleet.split(':')[0] == fleet_curr){
            fleetHTML += '<option value="'+ fleet.split(':')[0] +'" selected>'+ fleet.split(':')[0]  + ' - ' + fleet.split(':')[1] + '</option>';
        } else {
            fleetHTML += '<option value="'+ fleet.split(':')[0] +'">'+ fleet.split(':')[0]  + ' - ' + fleet.split(':')[1] + '</option>';
        }
    });

    $('#fleet_number').html(fleetHTML);

    
    $('#sub_loc_id').html(selectLocationsHTML);
});
// $(function() {
//   $('#edit_sub_location').on('show.bs.modal', function(event) {
//     var button = $(event.relatedTarget) // Button that triggered the modal
// 	// Location inputs
//     var id = button.data('id'); // Extract info from data-* attributes
// 	var sublocation = button.data('name');
    
//     var modal = $(this);
//     modal.find('#id').val(id);
//     modal.find('#sub_name').val(sublocation);
// 	// console.log(id);
//   });
// });

$('#locationModal').submit(function(e) {
	e.preventDefault();

	var id = $("#id").val();
	var form = $('#location_form');
	var post_url = "<?= base_url('inventory/Backend/Locations/update/'); ?>"+id;
	console.log(id);
	$.ajax({
		type: "POST",
		url: post_url,
		data: form.serialize(),
		dataType: "json",
	}).done(function(response){
		// console.log(response);
		$("#loading").css("display","none");
		$('#locationModal').css('display', 'none');
		if (response.status==200) {
			swal(
			'Location Updated',
			'Updated Successfully',
			'success'
			).then(function() {
			location.reload();
			});

		} else {
			swal({
			type: 'error',
			title: 'Oops...',
			text: 'Something went wrong!'
			}).then(function() {
			location.reload();
			});

		}
	});


})

$(document).on('click', '.confirm_delete', function(e){
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


$(document).on('click', '.confirm_delete_sub', function(e){
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
});


});

</script>