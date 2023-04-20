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
	.table-responsive {
    overflow-x: auto;
    /* min-height: .01%; */
    min-height: 101px;
	}

</style>

<!-- Content area -->
<div class="content invoicessss">
	<div class="panel-body">
		<!-- Start of Purchases returns -->
		<div class="row">
			<div class="px-2 py-1 col">
				<div class="section variant-2">
					<!-- <div class="content">
						<div class="table-responsive">
							<table id="returns" class="table table-striped table-bordered table-hover"> -->
					<div class="content" id="purchaseordertablediv">
         				<div  class="table-responsive table-spraye">
            				<table  class="table datatable-filter-custom">
								<thead>
									<tr>
										<th><input type="checkbox" id="select_all" <?php if (empty($all_returns)) { echo 'disabled'; }  ?> /></th>
										<th>Return Order ID #</th>
										<th>Return Items</th>
										<th>Created At</th>
										<th>Updated At</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
										if($all_returns){
											foreach($all_returns as $return){
									?>
									<tr><td><input  name="group_id" type="checkbox"  value="<?=$return->return_id ?>" return_id="<?=$return->return_id ?>" class="myCheckBox" /></td>

										<td><a href="<?= base_url('inventory/Frontend/purchases/viewReturn/').$return->return_id ?>"><?= $return->return_id ?></a></td>

											<?php 
												$items = json_decode($return->items, true);
												if (sizeof($items) > 1) {
											?>
										<td>Multiple</td>
											<?php	} else {	?>
										<td ><?= $items[0]['name'] .'<br>'. $items[0]['item_number'] ?></td>
											<?php	}	?>
										<td><?= date('m-d-Y', strtotime($return->created_at)) ?></td>
										<td><?= $return->updated_at != 0000-00-00  ? date('m-d-Y', strtotime($return->updated_at)) : '' ?></td>
										<td class="table-action">
											<ul style="list-style-type: none; padding-left: 0px;">
												<li style="display: inline; padding-right: 10px;">
													<a href="<?= base_url('inventory/Frontend/purchases/pdfPurchaseOrderReturn/').$return->return_id ?>" target="_blank" class=" button-next"><i class=" icon-file-pdf position-center" style="color: #9a9797;"></i></a>
												</li>
												<li style="display: inline; padding-right: 10px;">
													<a href="<?= base_url('inventory/Frontend/purchases/printPurchaseOrderReturn/').$return->return_id ?>" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a>
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
		<!-- End of Purchases returns -->
	</div>
</div>

<!-- Start of purchase order modal -->
	<div class="modal fade" id="view_purchase_return">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Purchase Return</h6>
				</div>
				<form action="<?= base_url('inventory/Backend/Locations/create') ?>"  id="location_form"  name='addnewlocation' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
					<input type="hidden" name ="location_id" id="id" value = "">
						<div class="form-group">
							<div class="row">
								<div class="col-md-3 col-sm-4">
									<label>Return ID</label>
									<input type="text" class="form-control" name="return_id" id="ro_id" value = ""
										placeholder="Enter Purchase ID">
								</div>
								<div class="col-md-3 col-sm-4">
									<label>Purchase ID</label>
									<input type="text" class="form-control" name="purchase_order_id" id="po_id" value = ""
										placeholder="Enter Purchase ID">
								</div>
								<div class="col-md-3 col-sm-4">
									<label>Purchase Order #</label>
									<input type="text" class="form-control" name="purchase_order_number" id="po_number" value = ""
										placeholder="Purchase Order #">
								</div>
								<div class="col-md-3 col-sm-4">
									<label>Created at</label>
									<input type="text" class="form-control" name="created_at" id="created_at" value = ""
										placeholder="Created at">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Location ID</label>
									<input type="text" class="form-control" name="location_id" id="location_id" value = ""
										placeholder="Location ID">
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Location Name</label>
									<input type="text" class="form-control" name="location_name" id="location_name" value = ""
										placeholder="Location Name">
								</div>
							</div>
						</div>
						<hr  />
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>Vendor Name</label>
									<input type="text" class="form-control" name="vendor_name" id="vendor_name" value = ""
										placeholder="Enter Name">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Vendor Address</label>
									<input type="text" class="form-control" name="vendor_street_address" id="vendor_street" value = ""
										placeholder="Enter Street">
								</div>
								
								</div> -->
								<div class="col-md-4 col-sm-4">
									<label>Vendor City</label>
									<input type="text" class="form-control" name="vendor_city" id="vendor_city" value = ""
										placeholder="Enter City">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>Vendor State</label>
									<input type="text" class="form-control" name="vendor_state" id="vendor_state" value = ""
										placeholder="Enter State">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Vendor Zip Code</label>
									<input type="text" class="form-control" name="vendor_zip_code" id="vendor_zip" value = ""
										placeholder="Enter Zip Code">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Vendor Country</label>
									<input type="text" class="form-control" name="vendor_country" id="vendor_country" value = ""
										placeholder="Enter Country">
								</div>
							</div>
						</div>
						
					
						<hr  />
						
						
					
						<div class="content">
							<div class="table-responsive">
								<table id="sub_location" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>

											<th>Item Name</th>
											<th>Unit Price</th>
											<th>Quantity</th>
											<th>Subtotal</th>
											<th>Tax</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											
											<td id="sub_name" value="" ></td>
											<td id="inventory" value="" ></td>
											<td id="fleet" value="" ></td>
											<td id="fleet" value="" ></td>
											<td id="fleet" value="" ></td>
											<td class="table-action" width="20%">
												<ul style="list-style-type: none; padding-left: 0px;">
													<li style="display: inline; padding-right: 10px;">
														<a  data-target="#locationModal" 
														title="Edit"><i
															class="icon-pencil   position-center" style="color: #9a9797;"></i></a>
													</li>
													
													<li style="display: inline; padding-right: 10px;">
														<a href="<?=base_url("admin/customerDelete/") ?>"
														class="confirm_delete_modal button-next" title="Delete"><i class="icon-trash   position-center"
															style="color: #9a9797;"></i></a>
													</li>
												</ul>
											</td>
										</tr>
									</tbody>
								</table>
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
<!-- End of purchse order modal -->

<script type="text/javascript">
function  filterSearch(status) {
     
     // alert(status);
     $.ajax({
		type: "GET",
		url: "<?= base_url('inventory/Frontend/Purchases/getAllReturnOrdersBySearch/')?>"+status,
     }).done(function(data){
       $('#purchaseordertablediv').html(data);
       
       $('#allMessage').prop('disabled', true);
       $('#allPrint').prop('disabled', true);
   
   bilidDataTable();    
     });
   
   }

bilidDataTable();

function bilidDataTable(argument) {
	$('.datatable-filter-custom').DataTable({
		 

	   language: {
		   search: '<span></span> _INPUT_',
		   lengthMenu: '<span>Show:</span> _MENU_',
		   paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
	   },
	 

		 dom: 'l<"toolbar">frtip',
		 initComplete: function(){
  
		$("div.toolbar")
		   .html('<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>');      
	 }       
  });

}
	
var currency = "";
var openReturn = {};
var table = {};

(function($) {
	'use strict';

	$('document').ready(function() {
		
		// Load table
		var table =  $('#purchase_returns').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[0,'asc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Purchases/ajaxGetReturns')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,

		   "select":"multi",
		   "columns": [
		            {"data": "return_id", "name":"Return ID", "searchable":true, "orderable": true },
		            {"data": "purchase_order_number", "name":"Purchase Order #", "searchable":true, "orderable": true },
		            {"data": "purchase_order_id", "name":"Purchase Order ID", "searchable":true, "orderable": true },
					{"data": "return_items", "name":"Return Item", "searchable":true, "orderable": true },
			   	    {"data": "created_at", "name":"Created At", "searchable":true, "orderable": true },
			   	    {"data": "updated_at", "name":"Updated At", "searchable":true, "orderable": true },
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
					.html('<button type="button" class="btn btn-success">Export CSV</button><a href="" data-toggle="modal" data-target="#new_purchase_return"><button type="button"  class="btn btn-primary" id="purchasesreturnsbtn">New Return</button></a>');
			},

		});

		$('table#returns tbody').on('click', 'tr', function() {
			let id = table.row(this).data().DT_RowId
			loadReturn(id)
		})

		$('#returnModal').on('hide.bs.modal', e => {
			window.history.pushState(null, '', `<?= base_url() ?>/purchases/returns`)
		})

		
	})
})(jQuery)

$(document).on('click', '.modal_trigger_purchase_return', function(e){
		e.preventDefault();
		// var types = $(this).data('types').split('::');
		var vendors = $(this).data('vendors');
		var purchaseReturnId = $(this).data('rid');
		var purchaseOrderId = $(this).data('rid');
		var purchaseOrderNum = $(this).data('pnum');
		var created = $(this).data('created');
		var location = $(this).data('location');
		var locationName = $(this).data('location-name');
		var vendorID = $(this).data('vendor');
		var vendorName = $(this).data('vname');
		var vendorAddress = $(this).data('vaddress');
		var vendorCity = $(this).data('vcity');
		var vendorState = $(this).data('vstate');
		var vendorZip = $(this).data('vzip');
		var vendorCountry = $(this).data('vcountry');
		var total = $(this).data('total');
		$('#ro_id').val(purchaseReturnId);
		$('#po_id').val(purchaseOrderId);
		$('#po_number').val(purchaseOrderNum);
		$('#created_at').val(created);
		$('#location_id').val(location);
		$('#location_name').val(locationName);
		$('#vendor_name').val(vendorID);
		$('#vendor_name').val(vendorName);
		$('#vendor_street').val(vendorAddress);
		$('#vendor_city').val(vendorCity);
		$('#vendor_state').val(vendorState);
		$('#vendor_zip').val(vendorZip);
		$('#vendor_country').val(vendorCountry);
		$('#total').val(total);

	});

	$(document).on("change","#select_all", function () {
   
   // $("#select_all").change(function(){  //"select all" change 
	   var status = this.checked; // "select all" checked status
	   if (status) {
	   $('#allPrint').prop('disabled', false);
   
	   } else {
	   $('#allPrint').prop('disabled', true);
	   
	   }
   
	   $('input:checkbox').not(this).prop('checked', this.checked);
   
   });

   $(document).on("change",".myCheckBox", function () {
  
	   // checkBoxes.change(function () {
	   // alert(checkBoxes);
	   if($('.myCheckBox').filter(':checked').length < 1) {
	   //  alert("if");
	   $('#allPrint').prop('disabled', true);
	   } else {
	   $('#allPrint').prop('disabled', false);
   
	   //  alert('else');
	   }
   
	   if(this.checked == false){ //if this item is unchecked
		   $("#select_all")[0].checked = false; //change "select all" checked status to false
	   }
	   
	   //check "select all" if all checkbox items are checked
	   if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){ 
		   $("#select_all")[0].checked = true; //change "select all" checked status to true
	   }
   
   });

   $(document).on("click","#allPrint", function () {
   
		var return_ids = $("input:checkbox[name=group_id]:checked").map(function(){
			return $(this).attr('return_id');
		}).get(); // <----
	
		var href ="<?= base_url('inventory/Frontend/Purchases/printPurchaseOrderReturn/') ?>"+return_ids;

		var win = window.open(href, '_blank');
		win.focus();

	});

function loadReturn(id) {
	axios.get(`api/purchases/returns/${id}`).then(response => {
		let purchaseReturn = response.data

		openReturn = purchaseReturn

		window.history.pushState(null, '', `<?= base_url() ?>/purchases/returns/${id}`)

		$('#returnModal').modal('show')

		$('#returnModal table#returnInformation td[data-item-field="created_at"]').text(purchaseReturn.created_at)
		$('#returnModal table#returnInformation td[data-item-field="reference"]').text(purchaseReturn.reference)
		$('#returnModal table#returnInformation td[data-item-field="purchase_reference"]').text(purchaseReturn.purchase.reference)
		$('#returnModal table#returnInformation td[data-item-field="warehouse"]').text(purchaseReturn.warehouse.name)

		$('#returnModal table#supplierInformation td[data-item-field="id"]').text(purchaseReturn.supplier.id)
		$('#returnModal table#supplierInformation td[data-item-field="name"]').text(purchaseReturn.supplier.name)
		$('#returnModal table#supplierInformation td[data-item-field="address"]').text(purchaseReturn.supplier.address)
		$('#returnModal table#supplierInformation td[data-item-field="city"]').text(purchaseReturn.supplier.city)
		$('#returnModal table#supplierInformation td[data-item-field="state"]').text(purchaseReturn.supplier.state)
		$('#returnModal table#supplierInformation td[data-item-field="zip_code"]').text(purchaseReturn.supplier.zip_code)
		$('#returnModal table#supplierInformation td[data-item-field="country"]').text(purchaseReturn.supplier.country)

		$('#returnModal table#items tbody').html('')

		purchaseReturn.items.forEach(item => {
			let unit_price = Utils.getFloat(item.unit_price)
			let quantity = Utils.getInt(item.qty_to_return)
			let tax = Utils.getFloat(item.tax)

			let subtotal = quantity * unit_price
			let total = Utils.applyTax(subtotal, tax)

			let elem = '<tr>'
				+ `<td>`
				+ `<strong>${item.name}</strong><br />${item.code}`
				+ `</td>`
				+ `<td>${currency} ${Utils.twoDecimals(unit_price)}</td>`
				+ `<td>${quantity}</td>`
				+ `<td>${currency} ${Utils.twoDecimals(subtotal)}</td>`
				+ `<td>${Utils.twoDecimals(tax)}%</td>`
				+ `<td>${currency} ${Utils.twoDecimals(total)}</td>`
				+ '</tr>'

			$('#returnModal table#items tbody').append(elem)
		})

		$('#returnModal #notes').html(purchaseReturn.notes)

		$('#returnModal table#summary td[data-summary-field="subtotal"]').html(`${currency} ${Utils.twoDecimals(purchaseReturn.subtotal)}`)
		$('#returnModal table#summary td[data-summary-field="discount"]').html(`${currency} ${Utils.twoDecimals(purchaseReturn.discount)}`)
		$('#returnModal table#summary td[data-summary-field="shipping"]').html(`${currency} ${Utils.twoDecimals(purchaseReturn.shipping_cost)}`)
		$('#returnModal table#summary td[data-summary-field="tax"]').html(`${Utils.twoDecimals(purchaseReturn.tax)}%`)
		$('#returnModal table#summary td[data-summary-field="total"]').html(`${currency} ${Utils.twoDecimals(purchaseReturn.grand_total)}`)
	})
}
</script>