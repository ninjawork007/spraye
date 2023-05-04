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
	.label-return, .bg-return {
		background-color: #fd7e14;
		border-color: #fd7e14;
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
		<!-- Start of Purchases -->
		<div class="row">
			<div class="px-2 py-1 col">
				<div class="section variant-2">
					<div class="content" id="purchaseordertablediv">
         				<div  class="table-responsive table-spraye">
            				<table  class="table datatable-filter-custom">
								<thead>
									<tr>
										<th><input type="checkbox" id="select_all" <?php if (empty($all_purchases)) { echo 'disabled'; }  ?>    /></th>
										<th>Purchase Order #</th>
										<th>Purchase Order Date</th>
										<th>Sent Status</th>
										<th>PO Status</th>
										<th>Sent Date</th>
										<th>Open Date</th>
										<th>Paid Status</th>
										<th>Estimated Delivery Date</th>
										<th>Location</th>
										<th>Sub Location</th>
										<th>Vendor</th>
										<th>Item #</th>
										<th>Total PO $</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
										if($all_purchases){
											foreach($all_purchases as $purchase){
									?>
									<tr>
										<td><input  name="group_id" type="checkbox"  value="<?=$purchase->purchase_order_id.':'.$purchase->purchase_order_number ?>" purchase_order_id="<?=$purchase->purchase_order_id ?>" class="myCheckBox" /></td>
										<td><a href="<?= base_url('inventory/Frontend/purchases/viewOrder/').$purchase->purchase_order_id ?>"><?= $purchase->purchase_order_number; ?></a></td> 
										<td><?= date('m-d-Y', strtotime($purchase->purchase_order_date)) ?></td>
										<td width="13%">
                                            <div class="dropdown">
											<?php switch ($purchase->purchase_sent_status) {
											case 0:
                                                echo '<button class="btn btn-default dropdown-toggle label-warning statusCol" type="button" data-toggle="dropdown">Draft
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-warning';
                                                break;
                                             case 1:
                                                echo '<button class="btn btn-default dropdown-toggle label-danger statusCol" type="button" data-toggle="dropdown">Sent
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-danger';
                                                break;
                                             
                                             case 2:
                                                echo '<button class="btn btn-default dropdown-toggle label-till statusCol" type="button" data-toggle="dropdown">Opened
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-till';
                                                break;
                                                
                                             } ?>
                                                <ul class="dropdown-menu dropdown-menu-right" >
                                                   <li class="changestatusSent"  purchase_order_id="<?= $purchase->purchase_order_id ?>" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Draft</a></li>
                              
                                                   <li class="changestatusSent" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="1" ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>
                              
                                                   <li class="changestatusSent" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="2" ><a href="#"><span class="status-mark bg-till position-left"></span> Opened</a></li>
                                                </ul>
                                            </div>
										</td>
										<td width="13%">
                                        <div class="dropdown">
                                            <?php switch ($purchase->purchase_order_status) {
                                            case 0:
                                                echo '<button class="btn btn-default dropdown-toggle label-warning statusCol" type="button" data-toggle="dropdown">Pending Vendor Approval
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-warning';
                                                break;
                                            case 1:
                                                echo '<button class="btn btn-default dropdown-toggle label-till statusCol" type="button" data-toggle="dropdown">Approved By Vendor
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-till';
                                                break;
                                            case 2:
                                                echo '<button class="btn btn-default dropdown-toggle label-danger statusCol" type="button" data-toggle="dropdown">Partial Received
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-danger';
                                                break;
                                            case 3:
                                                echo '<button class="btn btn-default dropdown-toggle label-success statusCol" type="button" data-toggle="dropdown">Received
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-success';
                                                break;
                                            case 4:
                                                echo '<button class="btn btn-default dropdown-toggle label-return statusCol" type="button" data-toggle="dropdown">Returned
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-return';
                                                break;
                                            } ?>
                                            <ul class="dropdown-menu dropdown-menu-right" >
                                                <li class="changestatusPO"  purchase_order_id="<?= $purchase->purchase_order_id ?>" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Pending Vendor Approval</a></li>
                                                <li class="changestatusPO" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="1" ><a href="#"><span class="status-mark bg-till position-left"></span> Approved By Vendor</a></li>
                                            </ul>
                                        </div>
										</td>
										<td><?= $purchase->sent_date != 0000-00-00  ? date('m-d-Y', strtotime($purchase->sent_date)) : '' ?></td>
										<td><?= $purchase->open_date != 0000-00-00  ? date('m-d-Y', strtotime($purchase->open_date)) : '' ?></td>
										<td width="13%">
                                        <div class="dropdown">
											<?php switch ($purchase->purchase_paid_status) {
											case 0:
												echo '<button class="btn btn-default dropdown-toggle label-warning statusCol" type="button" data-toggle="dropdown">Open
                                                <span class="caret"></span></button>';
												$bg= 'bg-warning';
												break;
											case 1:
												echo '<button class="btn btn-default dropdown-toggle label-till statusCol" type="button" data-toggle="dropdown">Ready For Payment
                                                <span class="caret"></span></button>';
												$bg= 'bg-till';
												break;
											
											case 2:
												echo '<button class="btn btn-default dropdown-toggle label-success statusCol" type="button" data-toggle="dropdown">Paid
                                                <span class="caret"></span></button>';
												$bg= 'bg-success';
												break;

											case 3:
												echo '<button class="btn btn-default dropdown-toggle label-danger statusCol" type="button" data-toggle="dropdown">Unmatched
                                                <span class="caret"></span></button>';
												$bg= 'bg-danger';
												break;

												
											} ?>
											<ul class="dropdown-menu dropdown-menu-right" >
												<li class="changestatusPaid"  purchase_order_id="<?= $purchase->purchase_order_id ?>" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Open</a></li>
												<li class="changestatusPaid" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="1" ><a href="#"><span class="status-mark bg-till position-left"></span> Ready For Payment</a></li>
												<li data-toggle="modal" data-target="#po_paid_status" class="changestatusPaid" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="2" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>
												<li class="changestatusPaid" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="3" ><a href="#"><span class="status-mark bg-danger position-left"></span> Unmatched</a></li>
											</ul>
										</div>
										</td>
										<td><?= $purchase->estimated_delivery_date != 0000-00-00  ? date('m-d-Y', strtotime($purchase->estimated_delivery_date)) : '' ?></td>
										<td><?= $purchase->location_name ?></td>
										<td><?= $purchase->sub_location_name ?></td>
										<td><?= $purchase->vendor_name ?></td>
											<?php 
												$items = json_decode($purchase->items, true);
											
												if (sizeof($items) > 1) {
											?>
										<td>Multiple</td>
											<?php	} else {	?>
										<td ><?= $items[0]['name'] .'<br>'. $items[0]['item_number'] ?></td>
											<?php	}	?>
										<td>$<?= $purchase->grand_total?></td>
										<td class="table-action">
											<ul style="list-style-type: none; padding-left: 0px;">

											<li style="display: inline; padding-right: 10px;">
												<a  class="email button-next" id="<?= $purchase->purchase_order_id ?>"  purchase_order_number="<?= $purchase->purchase_order_number ?>"    ><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a>
											</li>
											<li style="display: inline; padding-right: 10px;">
												<a href="<?= base_url('inventory/Frontend/purchases/pdfPurchaseOrder/').$purchase->purchase_order_id ?>" target="_blank" class=" button-next"><i class=" icon-file-pdf position-center" style="color: #9a9797;"></i></a>
											</li>
											<li style="display: inline; padding-right: 10px;">
												<a href="<?= base_url('inventory/Frontend/purchases/printPurchaseOrder/').$purchase->purchase_order_id ?>" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a>
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
		<!-- End of Purchases -->
	</div>
</div>

<!-- Start of purchase order modal -->
	<div class="modal fade" id="view_purchase_order">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Purchase Order #</h6>
				</div>
					
				<form action="<?= base_url('inventory/Backend/Locations/create') ?>"  id="location_form"  name='addnewlocation' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
					<input type="hidden" name ="location_id" id="id" value = "">
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>Purchase ID</label>
									<input type="text" class="form-control" name="purchase_order_id" id="po_id" value = "" placeholder="Enter Purchase ID" readonly>
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Purchase Order #</label>
									<input type="text" class="form-control" name="purchase_order_number" id="po_number" value = "" placeholder="Purchase Order #" readonly>
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Purchase Order Date</label>
									<input type="text" class="form-control" name="purchase_order_date" id="po_date" value = "" placeholder="Purchase Order Date" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Location Name</label>
									<input type="text" class="form-control" name="location_name" id="location_name" value = "" placeholder="Location Name" readonly>
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Sub Location Name</label>
									<input type="text" class="form-control" name="location_id" id="location_id" value = "" placeholder="Location ID" readonly>
								</div>
							</div>
						</div>
						<hr  />
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>Vendor Name</label>
									<input type="text" class="form-control" name="vendor_name" id="vendor_name" value = "" placeholder="Enter Name" readonly>
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Vendor Address</label>
									<input type="text" class="form-control" name="vendor_street_address" id="vendor_street" value = "" placeholder="Enter Street" readonly>
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Vendor City</label>
									<input type="text" class="form-control" name="vendor_city" id="vendor_city" value = "" placeholder="Enter City" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>Vendor State</label>
									<input type="text" class="form-control" name="vendor_state" id="vendor_state" value = "" placeholder="Enter State" readonly>
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Vendor Zip Code</label>
									<input type="text" class="form-control" name="vendor_zip_code" id="vendor_zip" value = "" placeholder="Enter Zip Code" readonly>
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Vendor Country</label>
									<input type="text" class="form-control" name="vendor_country" id="vendor_country" value = "" placeholder="Enter Country" readonly>
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






<!-- Start of Invoice Paid Status modal -->
<div class="modal fade" id="po_paid_status">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Paid Purchase Order</h6>
            </div>
            
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="<?= base_url('inventory/Frontend/Purchases/save_paid_po')?>">
                    <input type="hidden" id="PaidPOId" name="po_id" value="">

                    <div class="form-group">
                        <label>Payment Method</label>
                        <select class="form-control" required name="paid_payment_method">
                            <option value="">Select</option>
                            <option>Check</option>
                            <option>Charge</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea class="form-control" required name="paid_notes"></textarea>
                    </div>

                    <div class="form-group">
                        <label>File</label>
                        <input type="file" class="form-control" name="paid_attachment" required />
                    </div>

                    <button class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End of Invoice Paid Status modal -->

<script type="text/javascript">

function  filterSearch(status) {
     
	// alert(status);
	$.ajax({
	type: "GET",
	url: "<?= base_url('inventory/Frontend/Purchases/getAllPurchaseOrdersBySearch/')?>"+status,
	}).done(function(data){
	$('#purchaseordertablediv').html(data);
	
	$('#allPurchase').prop('disabled', true);
	$('#allPrint').prop('disabled', true);

	buildDataTable();    
	});

}


function  filterLocation(status) {
     
	// alert(status);
	$.ajax({
	type: "GET",
	url: "<?= base_url('inventory/Frontend/Purchases/getAllPurchaseOrdersByLocartion/')?>"+status,
	}).done(function(data){
	$('#purchaseordertablediv').html(data);
	
	$('#allPurchase').prop('disabled', true);
	$('#allPrint').prop('disabled', true);

	buildDataTable();    
	});

}


function  filterPO(status) {
     
	// alert(status);
	$.ajax({
	type: "GET",
	url: "<?= base_url('inventory/Frontend/Purchases/getAllPurchaseOrdersByPO/')?>"+status,
	}).done(function(data){
	$('#purchaseordertablediv').html(data);
	
	$('#allPurchase').prop('disabled', true);
	$('#allPrint').prop('disabled', true);

	buildDataTable();    
	});

}

function  filterPayment(status) {
     
	// alert(status);
	$.ajax({
	type: "GET",
	url: "<?= base_url('inventory/Frontend/Purchases/getAllPurchaseOrdersByPayment/')?>"+status,
	}).done(function(data){
	$('#purchaseordertablediv').html(data);
	
	$('#allPurchase').prop('disabled', true);
	$('#allPrint').prop('disabled', true);

	buildDataTable();    
	});

}

buildDataTable();

function buildDataTable(argument) {
	var LocationFilterHTML = "";

	LocationFilterHTML += '<div class="btn-group">';
	LocationFilterHTML += '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Filter Location<span class="caret"></span></button>';
	LocationFilterHTML += '<ul class="dropdown-menu dropdown-menu-right">';
	<?php
	foreach($list_locations as $LL){
	?>
	LocationFilterHTML += '<li class="filter-status" onclick="filterLocation(<?php echo $LL->location_id ?>)"><a href="#"><span class="status-mark bg-primary position-left"></span> <?php echo $LL->location_name ?></a></li>';
	<?php
	}
	?>
	LocationFilterHTML += '</ul></div> &nbsp;&nbsp;';


	$('.datatable-filter-custom').DataTable({
		 

	   language: {
		   search: '<span></span> _INPUT_',
		   lengthMenu: '<span>Show:</span> _MENU_',
		   paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
	   },

		 dom: 'l<"toolbar">frtip',
		 initComplete: function(){
  
		$("div.toolbar")
		   .html(LocationFilterHTML + '<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Sent <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-status" onclick="filterSearch(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-status" onclick="filterSearch(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Draft </a></li><li class="filter-status" onclick="filterSearch(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li class="filter-status" onclick="filterSearch(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Opened</a></li></ul></div>&nbsp;&nbsp;<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter PO Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-payment" onclick="filterPO(5)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-payment" onclick="filterPO(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Pending Vendor Approval </a></li><li class="filter-payment" onclick="filterPO(1)"  ><a href="#"><span class="status-mark bg-till position-left"></span> Approved By Vendor </a></li>  <li class="filter-payment" onclick="filterPO(2)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Partial Status</a></li> <li class="filter-payment" onclick="filterPO(3)"  ><a href="#"><span class="status-mark bg-success position-left"></span> Received </a></li> <li class="filter-payment" onclick="filterPO(4)"  ><a href="#"><span class="status-mark bg-return position-left"></span> Returned </a></li> </li> </ul></div>&nbsp;&nbsp;<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Paid Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-payment" onclick="filterPayment(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-payment" onclick="filterPayment(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Open </a></li><li class="filter-payment" onclick="filterPayment(1)"  ><a href="#"><span class="status-mark bg-till position-left"></span> Ready For Payment </a></li><li class="filter-payment" onclick="filterPayment(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid </a></li>  <li class="filter-payment" onclick="filterPayment(3)" ><a href="#"><span class="status-mark bg-danger position-left"></span> Unmatched </a></li>  </ul></div><button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allPurchase">Send By Email</button>&nbsp;&nbsp;<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>');      
	 }       
  });

}

	$(document).on("click",".changestatusSent", function () {

		var purchase_order_id = $(this).attr('purchase_order_id');
		var status = $(this).val();
		console.log(status);
		$("#loading").css("display","block");  
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/changeStatusSent',
			data: {purchase_order_id: purchase_order_id, status: status},
			success: function (data) {
			$("#loading").css("display","none");
			location.reload();      
			}
		});

	});
	$(document).on("click",".changestatusPO", function () {

		var purchase_order_id = $(this).attr('purchase_order_id');
		var status = $(this).val();
		$("#loading").css("display","block");  
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/changeStatusPO',
			data: {purchase_order_id: purchase_order_id, status: status},
			success: function (data) {
			$("#loading").css("display","none");
			location.reload();      
			}
		});

	});
	$(document).on("click",".changestatusPaid", function () {

		var purchase_order_id = $(this).attr('purchase_order_id');
		var status = $(this).val();
		$("#loading").css("display","block");

		if(status != 2){
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/changeStatusPaid',
				data: {purchase_order_id: purchase_order_id, status: status},
				success: function (data) {
				$("#loading").css("display","none");
				location.reload();      
				}
			});
		}else{
			$("#PaidPOId").val(purchase_order_id);
		}
	});

	$(document).on("click",".email", function () {
   
		var purchase_order_id = $(this).attr('id');
		var purchase_order_number = $(this).attr('purchase_order_number');
		
		swal.mixin({
		input: 'text',
		confirmButtonText: 'Send',
		showCancelButton: true,
		progressSteps: 1
		}).queue([
			{
			title: 'Additional Purchase Order Message',
			text: 'Type a message to the vendor below to be included with the purchase order. Then click "Send" to email the purchase order to the vendor.'
			},
		]).then((result) => {
			if (result.value) {
			var message  = result.value;

				$("#loading").css("display","block");

					$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/sendPdfMail',
					data: {purchase_order_id: purchase_order_id, purchase_order_number: purchase_order_number,message : message},
					success: function (data)
					{

					$("#loading").css("display","none");
						swal(
							'Purchase Order !',
							'Sent Successfully ',
							'success'
							).then(function() {
								location.reload();
							});
							
					}
				});
			}
		})
	});

	$(document).on("change","#select_all", function () {
   
		var status = this.checked; // "select all" checked status
		if (status) {
		$('#allPurchase').prop('disabled', false);
		$('#allPrint').prop('disabled', false);
		$('#bulk_status_change').prop('disabled', false);
		$('#deletebutton').prop('disabled', false);
	
		}
		else
		{
		$('#allPurchase').prop('disabled', true);
		$('#allPrint').prop('disabled', true);
    	$('#bulk_status_change').prop('disabled', true);
		$('#deletebutton').prop('disabled', true);
		
		}
	
		$('input:checkbox').not(this).prop('checked', this.checked);
	
	});

	$(document).on("change",".myCheckBox", function () {
   
		// alert(checkBoxes);
		if($('.myCheckBox').filter(':checked').length < 1) {
		//  alert("if");
		$('#allPurchase').prop('disabled', true);
		$('#allPrint').prop('disabled', true);
		$('#deletebutton').prop('disabled', true);
    	$('#bulk_status_change').prop('disabled', true);
		}
		else {
		$('#allPurchase').prop('disabled', false);
		$('#allPrint').prop('disabled', false);
		$('#deletebutton').prop('disabled', false);
		$('#bulk_status_change').prop('disabled', false);
	
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

	$(document).on("click","#allPurchase", function () {
		swal.mixin({
			input: 'text',
			confirmButtonText: 'Send',
			showCancelButton: true,
			progressSteps: 1
		}).queue([
			{
			title: 'Purchase Message',
			text: 'Type a message to the Vendor below to be included with the purchase order. Then click "Send" to email the purchase order to the vendor.'
			},
		]).then((result) => {
			
			if (result.value) {
				var message  = result.value;
				
				var group_id_array = $("input:checkbox[name=group_id]:checked").map(function(){
					return $(this).val();
				}).get(); // <----

				$("#loading").css("display","block");
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/sendPdfMailToSelected',
					data: {group_id_array,message : message},
					success: function (data)
					{

					$("#loading").css("display","none");
						swal(
							'Purchase Order !',
							'Sent Successfully ',
							'success'
							).then(function() {
							location.reload();
							});
						
					}
				});
			}
		})
	});

	$(document).on("click","#allPrint", function () {
   
     
		var purchase_order_ids = $("input:checkbox[name=group_id]:checked").map(function(){
			return $(this).attr('purchase_order_id');
		}).get(); // <----
		
		var href ="<?= base_url('inventory/Frontend/Purchases/printPurchaseOrder/') ?>"+purchase_order_ids;

		var win = window.open(href, '_blank');
			win.focus();

	});
    
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
				$("input:checkbox[name=group_id]:checked").each(function(){
					selectcheckbox.push($(this).attr('purchase_order_id'));
				}); 

				$.ajax({
				type: "POST",
				url: "<?= base_url('')  ?>inventory/Frontend/Purchases/deleteMultiplePurchaseOrders",
				data: {purchase_order_ids : selectcheckbox }
				}).done(function(data){

					if (data==1) {
						swal(
							'Purchase Order !',
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

var currency = "";
var openPurchase = {};
var table = {};
var url = '<?= base_url('inventory/Frontend/purchases/new') ?>'

	$('document').ready(function() {
		
		// Load table
		var table =  $('#purchase_orders').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[0,'asc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Purchases/ajaxGetPurchases')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,

		   "select":"multi",
		   "columns": [
		            {"data": "purchase_order_date", "name":"Purchase Order Date", "searchable":true, "orderable": true },
		            {"data": "purchase_sent_status", "name":"Sent Status", "searchable":true, "orderable": true },
		            {"data": "purchase_order_status", "name":"Order Status", "searchable":true, "orderable": true },
		            {"data": "purchase_paid_status", "name":"Paid Status", "searchable":true, "orderable": true },
		            {"data": "estimated_delivery_date", "name":"Estimated Delivery Date", "searchable":true, "orderable": true },
					{"data": "location_id", "name":"Location", "searchable":true, "orderable": true },
					{"data": "vendor_id", "name":"Vendor", "searchable":true, "orderable": true },
					{"data": "items", "name":"Item #", "searchable":true, "orderable": true },
			   	    {"data": "grand_total", "name":"Total PO $ Amount", "searchable":true, "orderable": true },
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
				columns: [0,1,2,3,4,5],
            	},
			],
		   initComplete: function(){

				$("div.toolbar-1")
					.html('<button type="button" class="btn btn-success">Export CSV</button><a href="" data-toggle="modal" data-target="#new_purchase_order"><button type="button"  class="btn btn-primary" id="newvendorsbtn">New Purchase Order</button></a>');
			},

		});

		$('table#purchases tbody').on('click', 'tr', function() {
			let id = table.row(this).data().DT_RowId
			loadPurchase(id)
		})

		$('#purchaseModal').on('hide.bs.modal', e => {
			window.history.pushState(null, '', `<?= base_url() ?>/users`)
		})

		
	})

	$(document).on('click', '.modal_trigger_purchase_order', function(e){
		e.preventDefault();
		var vendors = $(this).data('vendors');
		var purchaseOrderId = $(this).data('poid');
		var purchaseOrderNum = $(this).data('pnum');
		var created = $(this).data('podate');
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
		$('#po_id').val(purchaseOrderId);
		$('#po_number').val(purchaseOrderNum);
		$('#po_date').val(created);
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

</script>