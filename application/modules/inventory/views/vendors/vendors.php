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

    <div id="vendortablediv">
      <div  class="table-responsive table-spraye">
        <table class="table" id="vendors">
          <thead>
            <tr>
				<th>Name</th>
				<th>Vendor #</th>
				<th>Internal Name</th>
				<th>Company Name</th>
				<th>Email Address</th>
				<th>Phone Number</th>
				<th>Action</th>
            </tr>
          </thead>

        </table>
      </div>
    </div>
  </div>
</div>


<!-- Start of new vendor modal -->
<div class="modal fade" id="new_vendor">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">New Vendor</h6>
				</div>
				
				<form action="<?= base_url('inventory/Backend/Vendors/create') ?>"  id="vendor_form"  name='addnewvendor' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Vendor Name</label>
									<input type="text" class="form-control" name="vendor_name" value = ""
										placeholder="Enter Vendor Name">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Vendor #</label>
									<input type="text" class="form-control" name="vendor_number" value = ""
										placeholder="Enter Vendor Number">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Company Name</label>
									<input type="text" class="form-control" name="company_name" value = ""
										placeholder="Enter Company Name">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Internal Name</label>
									<input type="text" class="form-control" name="internal_name" value = ""
										placeholder="Enter Internal Name">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Email Address</label>
									<input type="text" class="form-control" name="vendor_email_address" value = ""
										placeholder="Enter Email">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Phone Number</label>
									<input type="text" class="form-control" name="vendor_phone_number" value = ""
										placeholder="Enter Phone Number">
								</div>
							</div>
						</div>

						<hr />
						<div >
							<h6 class="modal-title">Physical Location</h6>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Address</label>
									<input type="text" class="form-control" name="vendor_street_address" value = ""
										placeholder="Enter Address">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>City</label>
									<input type="text" class="form-control" name="vendor_city" value = ""
										placeholder="Enter City">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>State</label>
									<input type="text" class="form-control" name="vendor_state" value = ""
										placeholder="Enter State">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Zip Code</label>
									<input type="text" class="form-control" name="vendor_zip_code" value = ""
										placeholder="Enter Zip Code">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Country</label>
									<input type="text" class="form-control" name="vendor_country" value = ""
										placeholder="Enter Country">
								</div>
							</div>
						</div>
						
					
						<hr  />
						
						<div>
							<h6 class="modal-title">Custom Information</h6>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<label>Custom Field 1</label>
									<input type="text" class="form-control" name="custom_field1" value=""
										placeholder="Enter Custom Field 1">
								</div>
								<div class="col-md-6 col-sm-6">
									<label>Custom Field 2</label>
									<input type="text" class="form-control" name="custom_field2" value=""
										placeholder="Enter Custom Field 2">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<label>Custom Field 3</label>
									<input type="text" class="form-control" name="custom_field3" value=""
										placeholder="Enter Custom Field 3">
								</div>
								<div class="col-md-6 col-sm-6">
									<label>Notes</label>
									<textarea class="form-control" name="notes" placeholder="Notes"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit"  class="btn btn-success">Save</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
<!-- End of vendor modal -->
<!-- Start of update vendor modal -->
<div class="modal fade" id="edit_vendor">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Update Vendor</h6>
				</div>
				
				<form action="<?= base_url('inventory/Backend/Vendors/create') ?>"  id="vendor_form"  name='editvendor' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
						<input type="hidden" name ="vendor_id" id="id" value = "">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Vendor Name</label>
									<input type="text" class="form-control" name="vendor_name" id="vendor_name" value = ""
										placeholder="Enter Vendor Name">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Vendor #</label>
									<input type="text" class="form-control" name="vendor_number" id="vendor_number" value = ""
										placeholder="Enter Vendor Number">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Company Name</label>
									<input type="text" class="form-control" name="company_name" id="company_name" value = ""
										placeholder="Enter Company Name">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Internal Name</label>
									<input type="text" class="form-control" name="interal_name" id="interal_name" value = ""
										placeholder="Enter Internal Name">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Email Address</label>
									<input type="text" class="form-control" name="vendor_email_address" id="email" value = ""
										placeholder="Enter Email">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Phone Number</label>
									<input type="text" class="form-control" name="vendor_phone_number" id="phone" value = ""
										placeholder="Enter Phone Number">
								</div>
							</div>
						</div>

						<hr />
						<div >
							<h6 class="modal-title">Physical Location</h6>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Address</label>
									<input type="text" class="form-control" name="vendor_street_address" id="street" value = ""
										placeholder="Enter Address">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>City</label>
									<input type="text" class="form-control" name="vendor_city" id="city" value = ""
										placeholder="Enter City">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>State</label>
									<input type="text" class="form-control" name="vendor_state" id="state" value = ""
										placeholder="Enter State">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Zip Code</label>
									<input type="text" class="form-control" name="vendor_zip_code" id="zip" value = ""
										placeholder="Enter Zip Code">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Country</label>
									<input type="text" class="form-control" name="vendor_country" id="country" value = ""
										placeholder="Enter Country">
								</div>
							</div>
						</div>
						
					
						<hr  />
						
						<div>
							<h6 class="modal-title">Custom Information</h6>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<label>Custom Field 1</label>
									<input type="text" class="form-control" name="custom_field1" id="custom_field1" value=""
										placeholder="Enter Custom Field 1">
								</div>
								<div class="col-md-6 col-sm-6">
									<label>Custom Field 2</label>
									<input type="text" class="form-control" name="custom_field2" id="custom_field2" value=""
										placeholder="Enter Custom Field 2">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<label>Custom Field 3</label>
									<input type="text" class="form-control" name="custom_field3" id="custom_field3" value=""
										placeholder="Enter Custom Field 3">
								</div>
								<div class="col-md-6 col-sm-6">
									<label>Notes</label>
									<textarea class="form-control" name="notes" id="notes" placeholder="Notes"></textarea>
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
'use strict';
	
var openVendor = {};
var table = {};

	$('document').ready(function() {
		// $('.main-loader').fadeOut(100)

		// // Link table to the loader
		// $('table#vendors').on('processing.dt', (e, settings, processing) => {
		// 	if(processing)
		// 		$('.main-loader').fadeIn(100)
		// 	else
		// 		$('.main-loader').fadeOut(100)
		// })

		// Load table
		// table = $('table#vendors').DataTable({
		// 	serverSide: true,
		// 	ajax: "<?= base_url('api/vendors') ?>",
		// 	columns: [
		// 		{ data: "name" },
		// 		{ data: "internal_name" },
		// 		{ data: "company_name" },
		// 		{ data: "email_address" },
		// 		{ data: "phone_number" },
		// 		{ data: "vat" }
		// 	]
		// })

		var table =  $('#vendors').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[0,'asc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Vendors/ajaxGetVendors')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,

		   "select":"multi",
		   "columns": [
		            {"data": "vendor_name", "name":"Name", "searchable":true, "orderable": true },
					{"data": "vendor_number", "name":"Vendor #", "searchable":true, "orderable": true },
			   	    {"data": "internal_name", "name":"Internal Name", "searchable":true, "orderable": true },
			   	    {"data": "company_name", "name":"Company Name", "searchable":true, "orderable": true },
			   	    {"data": "vendor_email_address", "name":"Email Address", "searchable":true, "orderable": true },
			   	    {"data": "vendor_phone_number", "name":"Phone Number", "searchable":true, "orderable": true },
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
				columns: [0,1,2,3,4,5,6],


            },
				],
		   initComplete: function(){

				$("div.toolbar")
					.html('<button type="button" class="btn btn-success">Export CSV</button><a href="" data-toggle="modal" data-target="#new_vendor"><button type="button"  class="btn btn-primary" id="newvendorsbtn">New Vendor</button></a>');
			},

		});

		// $('table#vendors tbody').on('click', 'tr', function() {
		// 	let id = table.row(this).data().DT_RowId
		// 	loadVendor(id)
		// })

		// $('#supplierModal').on('hide.bs.modal', e => {
		// 	window.history.pushState(null, '', `<?= base_url() ?>/vendors`)
		// })

		// $('#editVendorModal').on('hide.bs.modal', e => {
		// 	loadVendor(openVendor.id)
		// })

		// $('#editVendorModal').on('show.bs.modal', e => {
		// 	$('#supplierModal').modal('hide')
		// })

		
		
		// $('#editVendorModal form').on('submit', e => {
		// 	e.preventDefault()
		// 	editVendorSubmit()
		// })
	})

	$(document).on('click', '.modal_trigger_vendor', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var vname = $(this).data('vname');
    var vnum = $(this).data('vnum');
    var company = $(this).data('company');
    var internal = $(this).data('internal');
    var email = $(this).data('email');
    var phone = $(this).data('phone');
    var street = $(this).data('street');
    var city = $(this).data('city');
    var state = $(this).data('state');
    var zip = $(this).data('zip');
    var country = $(this).data('country');
    var field1 = $(this).data('field1');
    var field2 = $(this).data('field2');
    var field3 = $(this).data('field3');
    var notes = $(this).data('notes');
    $('#vendor_id').val(id);
    $('#vendor_name').val(vname);
    $('#vendor_number').val(vnum);
    $('#company_name').val(company);
    $('#interal_name').val(internal);
    $('#email').val(email);
    $('#phone').val(phone);
    $('#street').val(street);
    $('#city').val(city);
    $('#state').val(state);
    $('#zip').val(zip);
    $('#country').val(country);
    $('#custom_field1').val(field1);
    $('#custom_field2').val(field2);
    $('#custom_field3').val(field3);
    $('#notes').val(notes);

	});


// function loadVendor(id) {
// 	axios.get(`api/vendors/${id}`).then(response => {
// 		let supplier = response.data

// 		openVendor = supplier

// 		window.history.pushState(null, '', `<?= base_url() ?>/vendors/${id}`)

// 		$('#supplierModal').modal('show')

// 		$('#supplierModal td[data-item-field=id]').text(supplier.id)
// 		$('#supplierModal td[data-item-field=name]').text(supplier.name)
// 		$('#supplierModal td[data-item-field=internal_name]').text(supplier.internal_name)
// 		$('#supplierModal td[data-item-field=company_name]').text(supplier.company_name)
// 		$('#supplierModal td[data-item-field=vat]').text(supplier.vat)
// 		$('#supplierModal td[data-item-field=email_address]').text(supplier.email_address)
// 		$('#supplierModal td[data-item-field=phone_number]').text(supplier.phone_number)
// 		$('#supplierModal td[data-item-field=address]').text(supplier.address)
// 		$('#supplierModal td[data-item-field=city]').text(supplier.city)
// 		$('#supplierModal td[data-item-field=country]').text(supplier.country)
// 		$('#supplierModal td[data-item-field=state]').text(supplier.state)
// 		$('#supplierModal td[data-item-field=zip_code]').text(supplier.zip_code)
// 		$('#supplierModal td[data-item-field=created_by]').text(supplier.created_by.name)
// 		$('#supplierModal td[data-item-field=created_at]').text(supplier.created_at)
// 		$('#supplierModal td[data-item-field=custom_field_1]').text(supplier.custom_field1)
// 		$('#supplierModal td[data-item-field=custom_field_2]').text(supplier.custom_field2)
// 		$('#supplierModal td[data-item-field=custom_field_3]').text(supplier.custom_field3)
// 		$('#supplierModal td[data-item-field=notes]').text(supplier.notes)
// 	})
// }

// function editVendor() {
// 	$('#editVendorModal input[name=id]').val(openVendor.id)
// 	$('#editVendorModal input[name=name]').val(openVendor.name)
// 	$('#editVendorModal input[name=internal_name]').val(openVendor.internal_name)
// 	$('#editVendorModal input[name=company_name]').val(openVendor.company_name)
// 	$('#editVendorModal input[name=vat]').val(openVendor.vat)
// 	$('#editVendorModal input[name=email_address]').val(openVendor.email_address)
// 	$('#editVendorModal input[name=phone_number]').val(openVendor.phone_number)
// 	$('#editVendorModal input[name=address]').val(openVendor.address)
// 	$('#editVendorModal input[name=city]').val(openVendor.city)
// 	$('#editVendorModal input[name=country]').val(openVendor.country)
// 	$('#editVendorModal input[name=state]').val(openVendor.state)
// 	$('#editVendorModal input[name=zip_code]').val(openVendor.zip_code)
// 	$('#editVendorModal input[name=created_by]').val(openVendor.created_by.name)
// 	$('#editVendorModal input[name=created_at]').val(openVendor.created_at)
// 	$('#editVendorModal input[name=custom_field_1]').val(openVendor.custom_field1)
// 	$('#editVendorModal input[name=custom_field_2]').val(openVendor.custom_field2)
// 	$('#editVendorModal input[name=custom_field_3]').val(openVendor.custom_field3)
// 	$('#editVendorModal input[name=notes]').val(openVendor.notes)
	
// 	$('#editVendorModal').modal('show')
// }

// function editVendorSubmit() {
// 	let validator = new Validator()
// 	validator.addInputTextVal('name', 'minLength', 1, "<?= 'name_min_length' ?>")
// 	validator.addInputTextVal('name', 'maxLength', 45, "<?= 'name_max_length' ?>")
// 	validator.addInputTextVal('internal_name', 'maxLength', 45, "<?= 'internal_name_max_length' ?>")
// 	validator.addInputTextVal('company_name', 'maxLength', 100, "<?= 'company_name_max_length' ?>")
// 	validator.addInputTextVal('vat', 'maxLength', 45, "<?= 'vat_max_length' ?>")
// 	validator.addInputText('email_address', 'optional-email-address', "<?= 'email_address_invalid' ?>")
// 	validator.addInputTextVal('phone_number', 'maxLength', 20, "<?= 'phone_number_max_length' ?>")
// 	validator.addInputTextVal('address', 'maxLength', 80, "<?= 'address_max_length' ?>")
// 	validator.addInputTextVal('city', 'maxLength', 80, "<?= 'city_max_length' ?>")
// 	validator.addInputTextVal('country', 'maxLength', 30, "<?= 'country_max_length' ?>")
// 	validator.addInputTextVal('state', 'maxLength', 30, "<?= 'state_max_length' ?>")
// 	validator.addInputText('zip_code', 'optional-integer', "<?= 'zip_code_invalid' ?>")
// 	validator.addInputTextVal('zip_code', 'maxLength', 12, "<?= 'zip_code_max_length' ?>")

// 	if(!validator.validate())
// 		return

// 	axios.put(`api/vendors/${openVendor.id}`, {
// 		id: $('input[name=id]').val(),
// 		name: $('input[name=name]').val(),
// 		internal_name: $('input[name=internal_name]').val(),
// 		company_name: $('input[name=company_name]').val(),
// 		vat: $('input[name=vat]').val(),
// 		email_address: $('input[name=email_address]').val(),
// 		phone_number: $('input[name=phone_number]').val(),
// 		address: $('input[name=address]').val(),
// 		city: $('input[name=city]').val(),
// 		country: $('input[name=country]').val(),
// 		state: $('input[name=state]').val(),
// 		zip_code: $('input[name=zip_code]').val(),
// 		created_by: $('input[name=created_by]').val(),
// 		created_at: $('input[name=created_at]').val(),
// 		custom_field1: $('input[name=custom_field_1]').val(),
// 		custom_field2: $('input[name=custom_field_2]').val(),
// 		custom_field3: $('input[name=custom_field_3]').val(),
// 		notes: $('input[name=notes]').val()
// 	}).then(response => {
// 		$('#editVendorModal').modal('hide')
// 		table.ajax.reload()
// 	})
// }

// function deleteVendor() {
// 	showConfirmation('<?= 'delete_confirmation.title' ?>',
// 		'<?= 'delete_confirmation.msg' ?>',
// 		'<?= 'delete_confirmation.yes' ?>',
// 		'<?= 'delete_confirmation.no' ?>',
// 		() => {
// 			deleteVendorSubmit()
// 			return true
// 		},
// 		() => {
// 			return true
// 		})
// }

// function deleteVendorSubmit() {
// 	axios.delete(`api/vendors/${openVendor.id}`).then(response => {
// 		$('#supplierModal').modal('hide')
// 		table.ajax.reload()

// 	})
// }

$(document).on('click', '.confirm_delete_vendor', function(e){
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