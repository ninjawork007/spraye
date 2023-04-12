<style>
	/* .section:not(.variant-1):not(.variant-2) .header {
    padding: 14px 18px;
    border-bottom: 1px solid #F5F5F5;
    font-size: 20px;
    font-weight: 700;
    color: #727272;
	} */
	/* .section.variant-3 .header .title {
    font-size: 20px;
    font-weight: 700;
    color: #727272;
	} */
	.title {
    font-size: 20px;
	}
	h6.h6-5 {
    font-size: 20px;
	}
	.text-break {
	word-break: break-word !important;
	word-wrap: break-word !important;
	}
	.pl-2,
	.px-2 {
	padding-left: 0.5rem !important;
	}
	.pr-2,
	.px-2 {
	padding-right: 0.5rem !important;
	}
	.mt-0,
	.my-0 {
	margin-top: 0 !important;
	}
	.row {
	display: -ms-flexbox;
	display: flex;
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
	margin-right: -15px;
	margin-left: -15px;
	}
	.mt-n1,
	.my-n1 {
	margin-top: -0.25rem !important;
	}
	.col {
	-ms-flex-preferred-size: 0;
	flex-basis: 0;
	-ms-flex-positive: 1;
	flex-grow: 1;
	max-width: 100%;
	}
	.form-row > .col,
	.form-row > [class*="col-"] {
	padding-right: 5px;
	padding-left: 5px;
	}
	.content-item {
		padding: 20px;
	}
	.section .content .columns-separator {
		width: 10px;
	}
	.form-row {
	/* display: -ms-flexbox; */
	display: flex;
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
	margin-right: -5px;
	margin-left: -5px;
	}
	.form-group {
	margin-bottom: 1rem;
	}
	.form-inline .form-group {
		display: -ms-flexbox;
		display: flex;
		-ms-flex: 0 0 auto;
		flex: 0 0 auto;
		-ms-flex-flow: row wrap;
		flex-flow: row wrap;
		-ms-flex-align: center;
		align-items: center;
		margin-bottom: 0;
	}
	.form-text {
	display: block;
	margin-top: 0.25rem;
	}
	.d-block {
	display: block !important;
	}
	.mb-3,
	.my-3 {
	margin-bottom: 1rem !important;
	}
	.column {
	float: left;
	width: 50%;
	padding: 5px;
	}
	.d-block {
	display: block !important;
	}
	input,
	button,
	select,
	optgroup,
	textarea {
	margin: 0;
	font-family: inherit;
	font-size: inherit;
	line-height: inherit;
	}
	/* .form-control {
	display: block;
	width: 100%;
	height: calc(1.5em + 0.75rem + 2px);
	padding: 0.375rem 0.75rem;
	font-size: 1;
	font-weight: 400;
	line-height: 1.5;
	color: #495057;
	background-color: #fff;
	background-clip: padding-box;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
	} */
	.form-inline .input-group,
	.form-inline .custom-select {
		width: auto;
	}
	.custom-select {
	display: inline-block;
	width: 100%;
	height: calc(1.5em + 0.75rem + 2px);
	padding: 0.375rem 1.75rem 0.375rem 0.75rem;
	font-size: 14px;
	font-weight: 400;
	line-height: 1.5;
	color: #495057;
	vertical-align: middle;
	background: #fff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") right 0.75rem center/8px 10px no-repeat;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	}
	.custom-select:focus {
	border-color: #80bdff;
	outline: 0;
	box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
	}
	.custom-select:focus::-ms-value {
	color: #495057;
	background-color: #fff;
	}

	.custom-select[multiple], .custom-select[size]:not([size="1"]) {
	height: auto;
	padding-right: 0.75rem;
	background-image: none;
	}

	.custom-select:disabled {
	color: #6c757d;
	background-color: #e9ecef;
	}

	.custom-select::-ms-expand {
	display: none;
	}

	.custom-select:-moz-focusring {
	color: transparent;
	text-shadow: 0 0 0 #495057;
	}

	.custom-select-sm {
	height: calc(1.5em + 0.5rem + 2px);
	padding-top: 0.25rem;
	padding-bottom: 0.25rem;
	padding-left: 0.5rem;
	font-size: 0.875rem;
	}

	.custom-select-lg {
	height: calc(1.5em + 1rem + 2px);
	padding-top: 0.5rem;
	padding-bottom: 0.5rem;
	padding-left: 1rem;
	font-size: 1.25rem;
	}
	.form-inline .input-group,
	.form-inline .custom-select {
		width: auto;
	}
	.input-group {
	position: relative;
	display: -ms-flexbox;
	display: flex;
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
	-ms-flex-align: stretch;
	align-items: stretch;
	width: 100%;
	}
	.input-group > .form-control,
	.input-group > .form-control-plaintext,
	.input-group > .custom-select,
	.input-group > .custom-file {
	position: relative;
	-ms-flex: 1 1 auto;
	flex: 1 1 auto;
	width: 1%;
	min-width: 0;
	margin-bottom: 0;
	}
	.input-group > .form-control + .form-control,
	.input-group > .form-control + .custom-select,
	.input-group > .form-control + .custom-file,
	.input-group > .form-control-plaintext + .form-control,
	.input-group > .form-control-plaintext + .custom-select,
	.input-group > .form-control-plaintext + .custom-file,
	.input-group > .custom-select + .form-control,
	.input-group > .custom-select + .custom-select,
	.input-group > .custom-select + .custom-file,
	.input-group > .custom-file + .form-control,
	.input-group > .custom-file + .custom-select,
	.input-group > .custom-file + .custom-file {
	margin-left: -1px;
	}

	.input-group-prepend,
	.input-group-append {
	display: -ms-flexbox;
	display: flex;
	}
	.input-group-prepend .btn,
	.input-group-append .btn {
	position: relative;
	z-index: 2;
	}
	.input-group-prepend .btn + .btn,
	.input-group-prepend .btn + .input-group-text,
	.input-group-prepend .input-group-text + .input-group-text,
	.input-group-prepend .input-group-text + .btn,
	.input-group-append .btn + .btn,
	.input-group-append .btn + .input-group-text,
	.input-group-append .input-group-text + .input-group-text,
	.input-group-append .input-group-text + .btn {
	margin-left: -1px;
	}
	.input-group-append {
	margin-left: -1px;
	}
	.input-group-prepend {
	margin-right: -1px;
	}
	.input-group-text {
	display: -ms-flexbox;
	display: flex;
	-ms-flex-align: center;
	align-items: center;
	padding: 0.375rem 0.75rem;
	margin-bottom: 0;
	font-size: 13px;
	font-weight: 400;
	line-height: 1.5;
	color: #495057;
	text-align: center;
	white-space: nowrap;
	background-color: #e9ecef;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	}
	.mt-4,
	.my-4 {
	margin-top: 1.5rem !important;
	}

	.table-responsive {
		overflow-x: auto;
		/* min-height: .01%; */
		min-height: 101px;
	}

	.add-invoice{
		float:right;
		margin-left: -50%;
	}

	/* .receive-all{
		float:right;
		margin-left: -50%;
	} */

	/* #hidden_sub {
    display: none;
  	} */

</style>

<div class="content invoicessss">
	<div class="panel-body">
		<div class="row">
			<div class="px-2 mt-n1 col">
				<div class="section variant-3">
					<div class="header">
						<div class="title">
							Purchase Order # <span name="title"></span>
						</div>
						<div class="desc">
							Submit this form when you (will) receive purchased merchandise from one of your vendors
							<button  onclick="receiveAll()" class="btn btn-success receive-all ">
								Receive All Item(s)
							</button>
							<!-- <a href="" data-toggle="modal" data-target="#add_invoice"><button  type="button"  class="btn btn-primary add-invoice modal_trigger_purchase_order " id="addinvoicebtn">Add Invoice </button></a> -->
						</div>
					</div>

					<div class="content-item">
					<form  action="<?= base_url('inventory/Backend/Purchases/receivingOrder') ?>" method="post" id="receiving_purchase" name="receivingpurchase" enctype="multipart/form-data" >

							<div class="row mt-n3">
								<!-- Left -->
								<div class="column text-break pl-2 pr-2">
									<div class="form-group" >
									<label for="location_name" class="d-block">Location*</label>
										<input type="text" name="location_name" id="location_name" class="form-control" readonly>
										<input type="hidden" name="location_id" id="location_id">   
									</div>
								</div>

								<!-- Separator -->
								<div class="columns-separator"></div>

								<!-- Right -->
								<div class="column text-break pl-2 pr-2">
										<div class="form-group">
											<label for="vendor_name" class="d-block">Vendor*</label>
											<input type="text" name="vendor_name" id="vendor_name" class="form-control" readonly>
											<input type="hidden" name="vendor_id" id="vendor_id">	
											<div class="invalid-feedback"></div>
										</div>
								</div>
							</div>
							<div class="row mt-n3">
								<!-- Left -->
								<div class="column text-break pl-2 pr-2" id="hidden_sub">
									<div class="form-group sublocation-container">
										<label for="sub_location" class="d-block">Sub Location*</label>
										<select name="sub_location" id="sub_location" class="custom-select">
										
										</select>
									</div>
								</div>


								
							</div>

							<div class="row mt-4">
								<div class="col-md-12 text-break pl-2 pr-2">
									<div class="table-responsive">
										<table id="items" class="table table-bordered">
											<thead style="background: #36c9c9;border-color: #36c9c9;">
												<tr>
													<th>Item name</th>
													<th>Unit price</th>
													<th>Quantity</th>
													<th>Quantity Received</th>
													<th>Receiving</th>
													<th>Receiving Amount</th>
													<!-- <th>PO Total</th> -->
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<div class="row mt-4">
								<div class="col-md-4 text-break pl-2 pr-2">
									<div class="form-group">
										<label for="freight" class="d-block">Shipping cost  (<?= $settings->currency_symbol ?>)</label>
										<input type="text" name="freight" id="freight" class="form-control" min="0" value="0"  />
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-4 text-break pl-2 pr-2">
									<div class="form-group">
										<label for="discount" class="d-block">Discount (<?= $settings->currency_symbol ?>)</label>
										<input type="text" name="discount" id="discount" class="form-control" value="0"  />
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-4 text-break pl-2 pr-2">
									<div class="form-group">
										<label for="tax" class="d-block">Tax  (%)</label>
										<input type="text" name="tax" id="tax" class="form-control" value="<?= $purchase_order[0]->tax ?>"  />
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>

							<div class="row mt-n3">
								<div class="col-md-6 text-break pl-2 pr-2 mt-3">
									<div class="form-group">
										<label for="purchase_order_order_notes" class="d-block">Notes</label>
										<textarea name="purchase_order_order_notes" id="purchase_order_order_notes" class="form-control" rows="6"><?= $purchase_order[0]->notes ?></textarea>
									</div>
								</div>

								<div class="mt-3 col-md-2">
									
								</div>

								<div class="col-md-4 text-break pl-2 pr-2 mt-3">
									<table id="summary" class="table stacked">
										<tbody>
											<tr>
												<th width="40" class="font-weight-normal">Subtotal</th>
												<td width="60" data-summary-field="subtotal"><?= $settings->currency_symbol ?> 0.00</td>
											</tr>
											<tr>
												<th width="40" class="font-weight-normal">Discount</th>
												<td width="60" data-summary-field="discount"><?= $settings->currency_symbol ?> 0.00</td>
											</tr>
											<tr>
												<th width="40" class="font-weight-normal">Shipping cost</th>
												<td width="60" data-summary-field="shipping"><?= $settings->currency_symbol ?> 0.00</td>
											</tr>
											<tr>
												<th width="40" class="font-weight-normal">Tax</th>
												<td width="60" data-summary-field="tax"><?= $purchase_order[0]->tax ?>%</td>
											</tr>
											<tr>
												<th width="40" class="font-weight-bold">Total Received</th>
												<td width="60" data-summary-field="total_received" class="font-weight-bold"><?= $settings->currency_symbol ?> 0.00 </td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<hr class="mt-4" />

							<div class="text-right mt-2 mb-2">
								<button type="submit" class="btn btn-primary ">
									Receive Item(s)
								</button>
							</div>
							<div class="row">
								<input name="status" id="status" style="display: none;">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Start of purchase order modal -->
	<div class="modal fade" id="add_invoice">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Purchase Order #</h6>
				</div>
					
				<form action="<?= base_url('inventory/Frontend/Purchases/addInvoice') ?>"  id="po_invoice_form"  name='addpoinvoice' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 col-sm-4">
									<label>Invoice #</label>
									<input type="text" class="form-control" name="invoice_number" id="invoice_number" value = "" placeholder="Invoice #" >
								</div>
								<div class="col-md-6 col-sm-4">
									<label>Total Amount of Invoice</label>
									<input type="text" class="form-control" name="invoice_total_amt" id="invoice_total_amt" value = "" placeholder="Total Amount of Invoice" >
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>Shipping Cost</label>
									<input type="text" class="form-control" name="freight" id="freight" value = ""
										placeholder="Enter Shipping Cost">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Discount</label>
									<input type="text" class="form-control" name="discount" id="discount" value = ""
										placeholder="Enter Discount">
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Tax</label>
									<input type="text" class="form-control" name="tax" id="tax" value = ""
										placeholder="Enter Tax">
								</div>
							</div>
						</div>
					
						<hr  />
						
						<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
							<h6 class="modal-title">Received Invoice Info</h6>
						</div>
						
						<div class="content">
							<div class="table-responsive">
								<table id="invoice_received" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>Invoice #</th>
											<th>Sub Total </th>
											<th>Shipping Cost</th>
											<th>Discount</th>
											<th>Tax</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit" id="po_invoice" class="btn btn-success">Save</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
<!-- End of purchse order modal -->

<script type="text/javascript">
	
let currency = "<?= addslashes($settings->currency_symbol) ?>";
let purchase = {};
let selectedLocation = 0;
let selectedSubLocation = 0;
let selectedVendor = 0;

// Show sub location after selecting location
function showDiv(divId, element){
	  document.getElementById(divId).style.display = element.value == "" ? 'none' : 'block';
	}

	$('document').ready(function() {


		// Listen for changes on the Location select
		
				

			// Listen for changes on the estimated delivery date
		// $('input[name=estimated_delivery_date]').on('change', e => {
		// 	updateEstimatedDeliveryDate();
		// });

		// Listen for changes on to shipping cost
		$('input[name=freight]').on('change', e => {
			updateTotals();
		});
	// Listen for changes on to discount cost
		$('input[name=discount]').on('change', e => {
			updateTotals();
		});
	// Listen for changes on to tax
		$('input[name=tax]').on('change', e => {
			updateTotals();
		});

		// When changing received quantity of an item
		$(document).on('input', '.receivedqty', function() {
			var qty = $(this).val();
			updateTotals();
		})

		// When changing shipping cost, discount or tax...
		// $('input[name=shipping_cost], input[name=discount], input[name=tax]').on('input', e => {
		// 	updateTotals()
		// })

		$('form#receiving_purchase').on('submit', e => {
			e.preventDefault()
			updateTotals();
			receiveItems()
		});
		
		$('form#po_invoice_form').on('submit', e => {
			e.preventDefault()
			addInvoice();
		});

		receivedInvoice();
		purchaseOrder();
		
	});

function purchaseOrder(){
    
	var purchase_order_id = <?= $purchase_order_id ?>;
	var url = '<?= base_url('inventory/Backend/Purchases/receivingOrder/') ?>'+purchase_order_id;
  	var request_method = "GET"; //get form GET/POST method
	let total_units = 0;
	let subtotal = 0;
	let received_qty = 0;
	$.ajax({
		type: request_method,
		url: url,
		data: {purchase_order_id: purchase_order_id},
		dataType:'JSON', 
		success: function(res){
			purchase = res.data[0];
			console.log(purchase);
			var purchase_order_id = purchase.purchase_order_id;
			var purchase_order_number = purchase.purchase_order_number;
			// var estimated_delivery_date = purchase.estimated_delivery_date;
			var subLocation = purchase.sub_location_name;
			var vendor = purchase.vendor_name;
			// console.log('location = ' + location);

			$('span[name=title]').html(purchase_order_number);
			// $('input[name=estimated_delivery_date]').val(estimated_delivery_date);
			$('#location_name').val(purchase.location_name);
			$('#location_id').val(purchase.location_id);
			$('#sub_location option:selected').text(subLocation);


			var location = $('input[name=location_id]').val()
			var url = '<?= base_url('inventory/Backend/Locations/subLocationlist') ?>';
			var request_method = "GET"; //get form GET/POST method
        // console.log('location = ' + location);
			$.ajax({
				type: request_method,
				url: url,
				data: {location: location},
				dataType:'JSON', 
				success: function(response){
					// console.log(response);
					// put on console what server sent back...
					$('select#sub_location').empty()
					response.result.forEach(sublocation => {
						if(sublocation.sub_location_id == purchase.sub_location_id){
						let elem = `<option value="${sublocation.sub_location_id}" selected>`
						+ `${sublocation.sub_location_name}`
						+ '</option>'
						$('select#sub_location').append(elem);
					} else {
						let elem = `<option value="${sublocation.sub_location_id}">`
						+ `${sublocation.sub_location_name}`
						+ '</option>'
						$('select#sub_location').append(elem);
					}
					})
				}
			});

			$('#vendor_name').val(purchase.vendor_name);
			$('#vendor_id').val(purchase.vendor_id);
			$('input[name=freight]').val();


			$('table#items tbody').html('');

			purchase.items = JSON.parse(purchase.items);
			
			Object.values(purchase.items).forEach((item, i) => {
				
				total_units += Number(item.quantity);
				received_qty += Number(item.received_qty);
				let unit_price = item.unit_price;
				let received = item.received_qty;
				let quantity = item.quantity;
				let available = quantity - received;

				let td1 = '<div class="d-flex">'
					+ '<div>'
					+ `<strong>${item.name}</strong>`
					+ '<br />'
					+ item.item_number
					+ '</div>'
					+ '</div>'
				let td2 = unit_price
				console.log('total received = ' +item.received_qty );
				let td3 = quantity
				let td4= received
				let td5 = '<div class="input-group input-group-sm">'
					+ `<input type="number" class="form-control form-control-sm receivedqty" min="0" max="${available}" value="0" />`
					+ '</div>'
				let td6 = 0
				let td7 = 0

				let elem = `<tr data-item-id="${item.item_id}">`
					+ `<td>${td1}</td>`
					+ `<td data-item-td="unit_price">$ ${td2}</td>`
					+ `<td data-item-td="quantity">${td3}</td>`
					+ `<td data-item-td="received_qty">${td4}</td>`
					+ `<td data-item-td="receiving_qty">${td5}</td>`
					+ `<td data-item-td="received_amt">${td6}</td>`
					// + `<td data-item-td="total">$ ${td7}</td>`
					+ '</tr>'

				$('table#items').append(elem);

				let item_subtotal = received * Number(unit_price);

				let qty_subtotal = quantity * Number(unit_price);
	
				subtotal += item_subtotal;

			})
			purchase.total_units = total_units;
			purchase.receiving_total = 0;
			// purchase.status = '';
			console.log('total amount received = ' +purchase.total_received_amount );
			console.log('total_units = ' +purchase.total_units );

			let freight = $('input[name=freight]').val();
			let discount = $('input[name=discount]').val();
			let tax = $('input[name=tax]').val();
			freight = parseFloat(freight).toFixed(2);
			discount = parseFloat(discount).toFixed(2);
			tax = parseFloat(tax).toFixed(2);
			
			if(discount > subtotal){

				discount = subtotal;
			};

			let grand_total = subtotal;
			grand_total = parseFloat(grand_total - discount).toFixed(2);
			grand_total =(Number(grand_total) + Number(freight));
			let tax_amount = Number(tax * grand_total / 100);
			grand_total = parseFloat(grand_total  + tax_amount).toFixed(2);

			// GETTING THE SUBTOTAL $ AMOUNT OF PURCHASE ORDER
			purchase['subtotal_received'] = subtotal;
			// GETTING THE TOTAL $ AMOUNT OF PURCHASE ORDER
			purchase['total_received_amount'] = grand_total;

			if(purchase.total_received == undefined){
				purchase.total_received = 0;
			} else {
				purchase.total_received = purchase.total_received;
			}
			
		}	
	})
}

function addInvoice() {
	let data = {
		purchase_order_id : <?= $purchase_order_id ?>,
		invoice_id: $('input[name=invoice_number]').val(),
		invoice_total_amt: $('input[name=invoice_total_amt]').val(),
		freight: $('input[name=freight]').val(),
		discount: $('input[name=discount]').val(),
		tax: $('input[name=tax]').val()
	}
	var url = '<?= base_url('inventory/Frontend/Purchases/addInvoice') ?>';
	var request_method = "POST"; //get form GET/POST method
	var formDAta = data;
    
	$.ajax({
		type: request_method,
		url: url,
		data: formDAta,
		dataType:'JSON', 
		success: function(response){
			console.log(response);
			$("#loading").css("display","none");
			swal(
				'Purchase Order!',
				'Invoiced Successfully ',
				'success'
				).then(function() {
				// location.reload();
				});
		}
	});
}



function updateTotals() {
	let subtotal = 0;
	let total_qty = 0;
	let receiving_total = 0;
	let final = 0;
	
	Object.values(purchase.items).forEach((item, i) => {
		let received_qty = item.received_qty;
		let receiving_qty = $(`table#items tbody tr[data-item-id=${item.item_id}] input`).val();

		let quantity = item.quantity;
		let item_price = item.unit_price;
		console.log('received = '+ received_qty);
		console.log('receiving = '+ receiving_qty);

		// If quantity is greater than originally purchased, rewrite user input
		if(Number(received_qty) > Number(item.quantity)) {
			received_qty = item.quantity
			$(`table#items tbody tr[data-item-id=${item.item_id}] input`).val(received_qty);
		}
		// Update quantity in the original array
		purchase.items[i]['received_qty'] = Number(received_qty);
		purchase.items[i]['receiving_qty'] = Number(receiving_qty);

		let item_subtotal = receiving_qty * Number(item_price);
		let item_total = Number(item_subtotal);
		// let qty_subtotal = quantity * Number(item_price);
		// let qty_total = Number(qty_subtotal);

		$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="received_amt"]`).html(`${currency} ${parseFloat(item_subtotal).toFixed(2)}`);
		// $(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="total"]`).html(`${currency} ${parseFloat(qty_total).toFixed(2)}`);

		subtotal += item_total;
		total_qty += Number(item.quantity);
		receiving_total += Number(receiving_qty);
		rt = Number(received_qty) + Number(receiving_qty);
		final += rt;

		console.log('final = ' +final);
	});

	console.log('receiving total = '+ receiving_total);

	if(final == total_qty){
		purchase.purchase_order_status = 3;
		purchase.is_complete = 1;
	} else {
		purchase.purchase_order_status = 2;
		purchase.is_complete = 0;
	}
	purchase['receiving_total'] = receiving_total;
	
	console.log('subtotal = '+subtotal);
	console.log('receiving = '+receiving_total);
	console.log('total = '+total_qty);
	console.log(purchase);

	let freight = $('input[name=freight]').val();
	let discount = $('input[name=discount]').val();
	let tax = $('input[name=tax]').val();
	if(freight == ''){
		freight = 0;
	}
	console.log('freight = '+freight);
	if(discount == ''){
		discount = 0;
	}
	console.log('discount = '+discount);
	if(tax == ''){
		tax = 0;
	}
	console.log('tax = '+tax);
	freight = parseFloat(freight).toFixed(2);
	discount = parseFloat(discount).toFixed(2);
	tax = parseFloat(tax).toFixed(2);
	
	if(discount > subtotal){
		discount = subtotal;
	};

	let grand_total = subtotal;
	grand_total = parseFloat(grand_total - discount).toFixed(2);
	grand_total =(Number(grand_total) + Number(freight));
	let tax_amount = Number(tax * grand_total / 100);
	grand_total = parseFloat(grand_total  + tax_amount).toFixed(2);

	// GETTING THE SUBTOTAL $ AMOUNT OF PURCHASE ORDER
	purchase['subtotal_received'] = subtotal;
	// GETTING THE TOTAL $ AMOUNT OF PURCHASE ORDER
	purchase['total_received_amount'] = grand_total;
	
	$('table#summary tr td[data-summary-field="subtotal"]').html(`${currency} ${parseFloat(subtotal).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="discount"]').html(`${currency} ${parseFloat(discount).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="shipping"]').html(`${currency} ${parseFloat(freight).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="tax"]').html(`${tax}%`)
	$('table#summary tr td[data-summary-field="total_received"]').html(`${currency} ${parseFloat(grand_total).toFixed(2)}`)
}

function updateEstimatedDeliveryDate() {
	var purchase_order_id = <?= $purchase_order_id ?>;
	var estimated_delivery_date = $('input[name=estimated_delivery_date]').val()
	var url = '<?= base_url('inventory/Frontend/Purchases/updateEstimatedDeliveryDatePO') ?>';
    var request_method = "POST"; //get form GET/POST method
	console.log('estimated_delivery_date = ' + estimated_delivery_date);
    
	$.ajax({
		type: request_method,
		url: url,
		data: {purchase_order_id:purchase_order_id, estimated_delivery_date: estimated_delivery_date},
		dataType:'JSON', 
		success: function(response){
			console.log(response);
				location.reload();
		}
	});
}

function receivedInvoice() {

	var purchase_order_id = <?= $purchase_order_id ?>;
	
	console.log('received invoice');
	var url = '<?= base_url('inventory/Backend/Purchases/receivedInvoice/') ?>'+purchase_order_id;
  	var request_method = "GET"; //get form GET/POST method
	
	$.ajax({
		type: request_method,
		url: url,
		data: {purchase_order_id:purchase_order_id},
		dataType:'JSON', 
		success: function(res){
			inv_received = res.data;
			// console.log(received);
			console.log(inv_received);
			inv_received.forEach(inv => {
				baseUrl = '<?=base_url("inventory/Frontend/Purchases/deletePurchaseInvoice/") ?>'+inv.invoice_id;

				let td1 = inv.invoice_id
				let td2 = Number(inv.invoice_total_amt);
				console.log('subtotal= '+td2 );
				let td3 = Number(inv.freight);
				console.log('freight= '+td3 );
				let td4 = Number(inv.discount);
				console.log('discount= '+td4 );
				let td5 = inv.tax;
				console.log('tax= '+td5);
				let td6 = ((td2 * td5)/100)+td2+td3-td4;
				console.log('total= '+td6 );
				let td7 = `<ul style="list-style-type: none; padding-left: 0px;"> <li style="display: inline; padding-right: 10px;"> <a href="" onclick="deletePOInvoice() class="confirm_delete_modal button-next" title="Delete"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></li></ul>`;
											

				//table#items
				let elem = `<tr data-item-id="${inv.po_invoice_id}">`
					+ `<td>${td1}</td>`
					+ `<td data-item-td="invoice_amt">${currency} ${td2.toFixed(2)}</td>`
					+ `<td data-item-td="freight">${currency} ${td3.toFixed(2)}</td>`
					+ `<td data-item-td="discount">${currency} ${td4.toFixed(2)}</td>`
					+ `<td data-item-td="tax">${td5}%</td>`
					+ `<td data-item-td="total">${currency} ${td6.toFixed(2)}</td>`
					// + `<td data-item-td="action">${td3}</td>`
					+ '</tr>'

				$('table#invoice_received').append(elem)
			})
		}	
	})
}

function receiveItems() {
	var purchase_order_id = <?= $purchase_order_id ?>;
	// Now make sure we have at least one item
	if($('table#items tbody tr').length == 0) {
		showError('error', "purchases.frontend.item_not_added")
		return
	}
	console.log(purchase);
	// Build data object!
	let data = {
		purchase_order_id: purchase_order_id,
		purchase_order_number: $('span[name=title]').html(),
		location_id: $('input[name=location_id]').val(),
		sub_location_id: $('select[name=sub_location]').val(),
		freight: $('input[name=freight]').val(),
		discount: $('input[name=discount]').val(),
		discount_type: 'amount',
		tax: $('input[name=tax]').val(),
		subtotal_received: parseFloat(purchase.subtotal_received).toFixed(2),
		total_received_amount: purchase.total_received_amount,
		notes: $('textarea[name=purchase_order_order_notes]').val(),
		status: purchase.purchase_order_status,
		completed: purchase.is_complete,
		total_received: purchase.receiving_total,
		total_units: purchase.total_units,
		items: []
	}
	console.log('total_received_amount = ' + data.total_received_amount);
	
	Object.values(purchase.items).forEach(item => {
		data.items.push({
			item_id: item.item_id,
			item_number: item.item_number,
			name: item.name,
            receiving_qty: item.receiving_qty,
			received_qty: Number(item.received_qty) + Number(item.receiving_qty),
			unit_price: item.unit_price,
			quantity: item.quantity
		})
	})

	console.log('received = ' + JSON.stringify(data));
	var url = '<?= base_url('inventory/Backend/Purchases/receivingItemsOrder/') ?>'+purchase_order_id;
	var formData = data;

	$.ajax({
		type: 'POST',
		url: url,
		data: formData,
		success: function (data){
			$("#loading").css("display","none");
			swal(
				'Purchase Order Items!',
				'Received Successfully ',
				'success'
				).then(function() {
				location.replace('<?= base_url('inventory/Frontend/Purchases') ?>')
				});
			
		}
	});
}

function receiveAll() {

let subtotal = 0;
let total_qty = 0;
let receiving_total = 0;

Object.values(purchase.items).forEach((item, i) => {
    let received_qty = Number(item.received_qty);
    let receiving_qty = Number(item.quantity) - Number(item.received_qty);
    let item_price = item.unit_price;
    console.log('received_qty = ' + received_qty);
    console.log('receiving_qty = ' + receiving_qty);
    // Update quantity in the original array
    purchase.items[i]['received_qty'] = received_qty;
    purchase.items[i]['receiving_qty'] = receiving_qty;

    let item_subtotal = receiving_qty * Number(item_price);
    let item_total = Number(item_subtotal);
    $(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="subtotal"]`).html(`${currency} ${parseFloat(item_subtotal).toFixed(2)}`);
    $(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="total"]`).html(`${currency} ${parseFloat(item_total).toFixed(2)}`);

    subtotal += item_total;
    total_qty += Number(item.quantity);
    receiving_total += Number(receiving_qty);
    console.log('receiving = '+ receiving_total);
});

// if(receiving_total == total_qty){
    purchase.purchase_order_status = 3;
		purchase.is_complete = 1;
    purchase['receiving_total'] = receiving_total;
// } else {

//     purchase.purchase_order_status = 2;
//     purchase['receiving_total'] = receiving_total;
// }

console.log('subtotal = '+subtotal);
console.log('receiving = '+receiving_total);
console.log('total = '+total_qty);
console.log(purchase);

let freight = $('input[name=freight]').val();
let discount = $('input[name=discount]').val();
let tax = $('input[name=tax]').val();
freight = parseFloat(freight).toFixed(2);
discount = parseFloat(discount).toFixed(2);
tax = parseFloat(tax).toFixed(2);

if(discount > subtotal){
    discount = subtotal;
};

// GETTING THE SUBTOTAL $ AMOUNT OF PURCHASE ORDER
purchase['subtotal_received'] = subtotal;

let grand_total = subtotal;
grand_total = parseFloat(grand_total - discount).toFixed(2);
grand_total =(Number(grand_total) + Number(freight));
let tax_amount = Number(tax * grand_total / 100);
grand_total = parseFloat(grand_total  + tax_amount).toFixed(2);

// GETTING THE SUBTOTAL $ AMOUNT OF PURCHASE ORDER
	purchase['subtotal_received'] = subtotal;
// GETTING THE TOTAL $ AMOUNT OF PURCHASE ORDER
	purchase['total_received_amount'] = grand_total;

$('table#summary tr td[data-summary-field="subtotal"]').html(`${currency} ${parseFloat(subtotal).toFixed(2)}`)
$('table#summary tr td[data-summary-field="discount"]').html(`${currency} ${parseFloat(discount).toFixed(2)}`)
$('table#summary tr td[data-summary-field="shipping"]').html(`${currency} ${parseFloat(freight).toFixed(2)}`)
$('table#summary tr td[data-summary-field="tax"]').html(`${tax}%`)
$('table#summary tr td[data-summary-field="total_received"]').html(`${currency} ${parseFloat(grand_total).toFixed(2)}`)

var purchase_order_id = <?= $purchase_order_id ?>;
// Now make sure we have at least one item
if($('table#items tbody tr').length == 0) {
    showError('error', "purchases.frontend.item_not_added")
    return
}
console.log(purchase);
// Build data object!
let data = {
	purchase_order_id: purchase_order_id,
	purchase_order_number: $('span[name=title]').html(),
	location_id: $('input[name=location_id]').val(),
	sub_location_id: $('select[name=sub_location]').val(),
	freight: $('input[name=freight]').val(),
	discount: $('input[name=discount]').val(),
	discount_type: 'amount',
	tax: $('input[name=tax]').val(),
	subtotal_received: parseFloat(purchase.subtotal_received).toFixed(2),
	total_received_amount: purchase.total_received_amount,
	notes: $('textarea[name=purchase_order_order_notes]').val(),
	status: purchase.purchase_order_status,
	completed: purchase.is_complete,
	total_received: purchase.total_units - purchase.total_received,
	total_units: purchase.total_units,
	items: []
}

Object.values(purchase.items).forEach(item => {
	data.items.push({
		item_id: item.item_id,
		item_number: item.item_number,
		name: item.name,
		receiving_qty: Number(item.quantity) - Number(item.received_qty),
		received_qty: Number(item.received_qty) + Number(item.receiving_qty),
		unit_price: item.unit_price,
		quantity: item.quantity
	})
})
console.log('received = ' + JSON.stringify(data));
	var url = '<?= base_url('inventory/Backend/Purchases/receivingItemsOrder/') ?>'+purchase_order_id;
	var formData = data;

	$.ajax({
		type: 'POST',
		url: url,
		data: formData,
		success: function (data){

			$("#loading").css("display","none");
			swal(
				'Purchase Order Items!',
				'All Received Successfully ',
				'success'
				).then(function() {
				location.replace('<?= base_url('inventory/Frontend/Purchases') ?>');
				});
			
		}
	});
}
</script>
