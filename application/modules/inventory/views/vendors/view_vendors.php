<?php
// $this->load->view('templates/master');
// <?= $this->section('content') 
// $this->load->view('vendors/modals/vendor_modal');
// $this->load->view('vendors/modals/edit_vendor_modal');
// $this->load->view('components/error_modal');
// $this->load->view('components/confirmation_modal');
?>
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
				
				<form action="<?= base_url('inventory/Frontend/Vendors/newVendor') ?>"  id="vendor_form"  name='addnewvendor' method="post" enctype="multipart/form-data" form_ajax="ajax">
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
<div class="modal fade" id="modal_edit_vendor">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Update Vendor</h6>
				</div>
				
				<form action="<?= base_url('inventory/Frontend/Vendors/editVendor') ?>"  id="edit_vendor_form"  name='editvendor' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
						<input type="hidden" name ="edit_vendor_id" id="edit_vendor_id" >
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Vendor Name</label>
									<input type="text" class="form-control" name="edit_vendor_name" id="edit_vendor_name"
										placeholder="Enter Vendor Name">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Vendor #</label>
									<input type="text" class="form-control" name="edit_vendor_number" id="edit_vendor_number" 
										placeholder="Enter Vendor Number">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Company Name</label>
									<input type="text" class="form-control" name="edit_company_name" id="edit_company_name" 
										placeholder="Enter Company Name">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Internal Name</label>
									<input type="text" class="form-control" name="edit_internal_name" id="edit_internal_name" 
										placeholder="Enter Internal Name">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Email Address</label>
									<input type="text" class="form-control" name="edit_vendor_email_address" id="edit_vendor_email_address" 
										placeholder="Enter Email">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Phone Number</label>
									<input type="text" class="form-control" name="edit_vendor_phone_number" id="edit_vendor_phone_number" 
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
									<input type="text" class="form-control" name="edit_vendor_street_address" id="edit_vendor_street_address" 
										placeholder="Enter Address">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>City</label>
									<input type="text" class="form-control" name="edit_vendor_city" id="edit_vendor_city" 
										placeholder="Enter City">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>State</label>
									<input type="text" class="form-control" name="edit_vendor_state" id="edit_vendor_state" 
										placeholder="Enter State">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Zip Code</label>
									<input type="text" class="form-control" name="edit_vendor_zip_code" id="edit_vendor_zip_code" 
										placeholder="Enter Zip Code">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Country</label>
									<input type="text" class="form-control" name="edit_vendor_country" id="edit_vendor_country" 
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
									<input type="text" class="form-control" name="edit_custom_field1" id="edit_custom_field1" 
										placeholder="Enter Custom Field 1">
								</div>
								<div class="col-md-6 col-sm-6">
									<label>Custom Field 2</label>
									<input type="text" class="form-control" name="edit_custom_field2" id="edit_custom_field2" 
										placeholder="Enter Custom Field 2">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<label>Custom Field 3</label>
									<input type="text" class="form-control" name="edit_custom_field3" id="edit_custom_field3" 
										placeholder="Enter Custom Field 3">
								</div>
								<div class="col-md-6 col-sm-6">
									<label>Notes</label>
									<textarea class="form-control" name="edit_notes" id="edit_notes" placeholder="Notes"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit" id="saveEditVendor" class="btn btn-success">Save</button>
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

		var table =  $('#vendors').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[2,'desc']],
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
					.html('<a href="<?php echo base_url('inventory/Frontend/Vendors/exportVendorsCSV'); ?>"><button type="button" class="btn btn-success">Export CSV</button></a><a href="" data-toggle="modal" data-target="#new_vendor"><button type="button"  class="btn btn-primary" id="newvendorsbtn">New Vendor</button></a>');
			},

		});

	$(document).on('click', '.modal_trigger', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var vname = $(this).data('v_name');
    var vnum = $(this).data('num');
    var company = $(this).data('company');
    var internal = $(this).data('int_name');
    var email = $(this).data('email');
    var phone = $(this).data('phone');
    var street = $(this).data('street');
    var city = $(this).data('city');
    var state = $(this).data('state');
    var zip = $(this).data('zip');
    var country = $(this).data('country');
    var field1 = $(this).data('cust1');
    var field2 = $(this).data('cust2');
    var field3 = $(this).data('cust3');
    var notes = $(this).data('notes');
    $('#edit_vendor_id').val(id);
    $('#edit_vendor_name').val(vname);
    $('#edit_vendor_number').val(vnum);
    $('#edit_company_name').val(company);
    $('#edit_internal_name').val(internal);
    $('#edit_vendor_email_address').val(email);
    $('#edit_vendor_phone_number').val(phone);
    $('#edit_vendor_street_address').val(street);
    $('#edit_vendor_city').val(city);
    $('#edit_vendor_state').val(state);
    $('#edit_vendor_zip_code').val(zip);
    $('#edit_vendor_country').val(country);
    $('#edit_custom_field1').val(field1);
    $('#edit_custom_field2').val(field2);
    $('#edit_custom_field3').val(field3);
    $('#edit_notes').val(notes);

	});


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