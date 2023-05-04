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
	font-size: 1rem;
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

	.add-invoice{
		float:right;
		margin-left: -50%;
	}

	.table-responsive {
		overflow-x: auto;
		/* min-height: .01%; */
		min-height: 101px;
	}

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
							<!-- Purchase Order # <?= $new_purchase[0]->purchase_order_number ?> -->
							Purchase Order # <span name="title"></span>
						</div>
						<div class="desc">
						Submit this form when you (will) purchase merchandise from one of your vendors
						</div>
					</div>

					<div class="content-item">
					<form  action="<?= base_url('inventory/Backend/Purchases/updatePO/').$purchase_id ?>" method="post" id="view_purchase" name="viewpurchase" enctype="multipart/form-data" >

							<div class="row mt-n3">
								<!-- Left -->
								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="location" class="d-block">Location*</label>
										<select name="location" id="location" class="custom-select" onchange="showDiv('hidden_sub', this)">
											<option value="<?= $new_purchase[0]->location_id ?>"  disabled selected><?= $new_purchase[0]->location_name ?></option>
										</select>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="estimated_delivery_date" class="d-block">Estimated Delivery Date</label>
											<input type="date" id="estimated_delivery_date" name="estimated_delivery_date" class="form-control" value="<?= $new_purchase[0]->estimated_delivery_date ?>"  />
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="ordered_date" class="d-block">Ordered Date</label>
										<input type="date" id="ordered_date" name="ordered_date" class="form-control" value="<?= $new_purchase[0]->ordered_date ?>" />
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="shipping_method_1" class="d-block">Shipping Method</label>
										<input type="text" id="shipping_method_1" name="shipping_method_1" class="form-control" value="<?= $new_purchase[0]->shipping_method_1 ?>"/>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="fob" class="d-block">FOB - Freight on Board</label>
										<select id="fob" name="fob" class="form-control">
											<option <?php if($new_purchase[0]->fob == "Place of Origin") { echo 'selected'; } ?>>Place of Origin</option>
											<option <?php if($new_purchase[0]->fob == "Place of Destination") { echo 'selected'; } ?>>Place of Destination</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row mt-n3">
								
								<!-- Separator -->
								<div class="columns-separator"></div>

								<!-- Right -->
								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="vendor" class="d-block">Vendor*</label>
										<select name="vendor" id="vendor" class="custom-select">
											<option value="<?= $new_purchase[0]->vendor_id ?>" selected><?= $new_purchase[0]->vendor_name ?></option>
										</select>
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>

							<div class="row mt-4">
								<div class="col-md-12 text-break pl-2 pr-2">
									<h6 class="h6-5 text-secondary">Add Items</h6>
									<span class="autocomplete-desc mb-3">
									To add items, type item name, and select the item you'd like to add. To start adding, select a sub-location and vendor.
									</span>

									<div class="autocomplete-container">
										<input type="text" id="item_search" name="item_search" placeholder="Add Items" autocomplete="off" class="form-control" />
										<ul class="dropdown-menu" id="itemSuggestions"></ul>
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
													<th>Unit</th>
													<th>Unit price</th>
													<th>Quantity</th>
													<th>Total</th>
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
										<input type="text" name="freight" id="freight" class="form-control" value="<?= number_format($new_purchase[0]->freight,2) ?>"  />
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-4 text-break pl-2 pr-2">
									<div class="form-group">
										<label for="discount" class="d-block">Discount (<?= $settings->currency_symbol ?>)</label>
										<input type="text" name="discount" id="discount" class="form-control" value="<?= number_format($new_purchase[0]->discount,2) ?>"  />
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-4 text-break pl-2 pr-2">
									<div class="form-group">
										<label for="tax" class="d-block">Tax  (%)</label>
										<input type="text" name="tax" id="tax" class="form-control" value="<?= $new_purchase[0]->tax ?>"  />
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>

							<div class="row mt-n3">
								<div class="col-md-6 text-break pl-2 pr-2 mt-3">
									<div class="form-group">
										<label for="new_purchase_order_notes" class="d-block">Notes</label>
										<textarea name="new_purchase_order_notes" id="new_purchase_order_notes" class="form-control" rows="6"><?= $new_purchase[0]->notes ?></textarea>
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
												<td width="60" data-summary-field="tax"> %</td>
											</tr>
											<tr>
												<th width="40" class="font-weight-bold">Grand Total</th>
												<td width="60" data-summary-field="total" class="font-weight-bold"><?= $settings->currency_symbol ?> </td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<div>
								<div class="form-group">
									<label for="payment_terms" class="d-block">Payment Terms</label>
									<textarea name="payment_terms" id="payment_terms" class="form-control" rows="6"><?= $new_purchase[0]->payment_terms ?></textarea>
								</div>
							</div>

							<hr class="mt-4" />

							<div class="row">
								<input name="purchase_sent_status" id="purchase_sent_status" style="display: none;">
								<div class="form-group col-lg-4">
									<div class="row">
										<div class="col-lg-6 text-right">
											<button type="submit" class="btn btn-success" id="save_draft">Save as Draft <i class="icon-arrow-right14 position-right"></i></button>
										</div>
										<div class="col-lg-6 text-left">
											<button type="submit" class="btn btn-success" id="submit_purchase">Submit & Send<i class="icon-arrow-right14 position-right"></i></button>                
										</div>
									</div>
								</div>          
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



<script type="text/javascript">
// 'use strict';

	$('#save_draft').on('click', function(e) {
		// e.preventDefault();
		$('#purchase_sent_status').val('0');
  	});
	$('#submit_purchase').on('click', function(e) {
		// e.preventDefault();
		$('#purchase_sent_status').val('1');
	});
	
	let currency = "<?= addslashes($settings->currency_symbol) ?>";
	let itemsAdded = [];
	let itemsSelected = [];
	let selectedLocation = 0;
	let selectedVendor = 0;

	// When changing quantity of an item
	$(document).on('input', '.itemqty', function() {
				var qty = $(this).val();
				console.log('input value =' + $(this).val());
				updateTotals();
			})

		// When changing quantity of an item
	$(document).on('input', '.quantity', function() {
				var qty = $(this).val();
				console.log('input value =' + $(this).val());
				updateTotals();
			})


	// Show sub location after selecting location
	function showDiv(divId, element){
	  document.getElementById(divId).style.display = element.value == "" ? 'none' : 'block';
	}

	$('document').ready(function() {

			// Listen for changes on the estimated delivery date
		$('input[name=estimated_delivery_date]').on('change', e => {
			updateEstimatedDeliveryDate();
		});

		// Once a location and vendor are selected, enable items section
		$('select[name=location], select[name=vendor]').on('change', e => {
			let location = $('select[name=location]').val()
			let vendor = $('select[name=vendor]').val()
			
			if(location != '' && location != null && vendor != '' && vendor != null) {
				selectedLocation = location
				selectedVendor = vendor
				$('select[name=location]').prop('disabled', true)
				$('select[name=vendor]').prop('disabled', true)
				$('input[name=item_search]').prop('disabled', false)
			}
		})

		// Listen for changes on the Location select
		$('select[name=location]').on('change', e => {
			subLocation();
		})

		// When focusing on the autocomplete, show list
		$('input[name=item_search]').on('focus', e => {
			$('.autocomplete-container').addClass('open')
		})
		$('input[name=item_search]').on('blur', e => {
			// Timeout so that the item clicked listener can fire
			setTimeout(() => {
				$('.autocomplete-container').removeClass('open')
			}, 200)
		})

		// Listen for changes on the autocomplete input
		$('input[name=item_search]').on('input', e => {
			autocomplete();
		})

		// When hitting enter in the autocomplete, it's because user entered
		// an item code.. Search for it, and it if exists, load the info
		$('input[name=item_search]').on('keypress', e => {
			if(e.which == 13) {
				e.preventDefault();
				onSearchItemCode();
			}
		})

		// When selecting an item to add
		$('ul#itemSuggestions').on('click', 'li', e => {
			let id = $(e.currentTarget).data('item-id')
			addItem(id);
		})

		// To remove item
		$('table#items').on('click', 'tr td button', e => {
			let parent = $(e.currentTarget).parent().parent().parent().parent()
			let itemId = parent.data('item-id')

			parent.remove()

			let indexToRemove = -1
			invoicesAdded.forEach((item, i) => {
				if(itemId == item.id){

					indexToRemove = i;
				}
			})
			invoicesAdded.splice(indexToRemove, 1)

			updateTotals();
		})

		// When changing quantity of an item
		$(document).on('input', '.itemqty', function() {
			var qty = $(this).val();
			updateTotals();
		})

		$(document).on('input', '.itemunit', function() {
			updateTotals();
		})

		// When changing quantity of an item
		$(document).on('input', '.quantity', function() {
			var qty = $(this).val();
			updateTotals();
		})


		// When changing shipping cost, discount or tax...
		$('input[name=freight], input[name=discount], input[name=tax]').on('input', e => {
			updateTotals();
		})

		$('form#view_purchase').on('submit', e => {
			e.preventDefault()
			updateTotals();
			updatePO();
		});
		
		purchaseOrder();

	});

	$(document).on('click', '.modal_trigger_purchase_order', function(e){
		e.preventDefault();
		var purchaseOrderNum = $(this).data('pnum');
		var created = $(this).data('podate');
		
		$('#po_number').val(purchaseOrderNum);
		$('#po_date').val(created);
	});

function onSearchItemCode() {
	let itemCode = $('input[name=item_search]').val()

	$('input[name=item_search]').blur().val('')
}

function autocomplete() {
	var search = $('input[name=item_search]').val()
	var vendor = $('select[name=vendor]').val()
	var url = '<?= base_url('inventory/Backend/Items/list') ?>';
    var request_method = "GET"; //get form GET/POST method

	$.ajax({
		type: request_method,
		url: url,
		data: {search: search, vendor: vendor},
		dataType:'JSON', 
		success: function(response){
			
			// put on console what server sent back...
			$('ul#itemSuggestions').empty()
			response.result.forEach(item => {
			let elem = `<li data-item-id="${item.item_id}">`
				+ `<span class="item-name">${item.item_name}</span>`
				+ '</li>'

			$('ul#itemSuggestions').append(elem)
			})
		}
	});
}



function updateEstimatedDeliveryDate() {
	var purchase_order_id = <?= $purchase_id ?>;
	var estimated_delivery_date = $('input[name=estimated_delivery_date]').val()
	var url = '<?= base_url('inventory/Frontend/Purchases/updateEstimatedDeliveryDatePO') ?>';
    var request_method = "POST"; //get form GET/POST method
    
	$.ajax({
		type: request_method,
		url: url,
		data: {purchase_order_id:purchase_order_id, estimated_delivery_date: estimated_delivery_date},
		dataType:'JSON', 
		success: function(response){
			location.reload();
		}
	});
}

function purchaseOrder(){
	var purchase_order_id = <?= $purchase_id ?>;
	var url = '<?= base_url('inventory/Backend/Purchases/purchaseOrder/') ?>'+purchase_order_id;
  	var request_method = "GET"; //get form GET/POST method
	let subtotal = 0;
	let ptotal = 0;
	let total_units = 0;
	$.ajax({
		type: request_method,
		url: url,
		data: {purchase_order_id: purchase_order_id},
		dataType:'JSON', 
		success: function(res){
			purchase = res.data[0];
		
			var purchase_purchase_order_id = purchase.purchase_purchase_order_id;
			var purchase_order_number = purchase.purchase_order_number;
			var location = purchase.location_name;
			var vendor = purchase.vendor_name;

			$('span[name=title]').html(purchase_order_number);
			$('#location option:selected').text(location);
			$('#vendor option').html(vendor);
			$('input[name=freight]').val();
			$('table#items tbody').html('');

			purchase.items = JSON.parse(purchase.items);
			
			Object.values(purchase.items).forEach((item, i) => {
				
				total_units += Number(item.quantity);
				
				let unit_price = item.unit_price;
				let quantity = item.quantity;
				let td1 = '<div class="d-flex">'
					+ '<div>'
					+ `<strong>${item.name}</strong>`
					+ '<br />'
					+ item.item_number
					+ '</div>'
					+ '</div>'
				let td2 = unit_price
				let td3 = quantity 
				let td6 = 0
				let td7 = `<input type="text" class="form-control form-control-sm itemunit" name="itemunit" value="`+item.unit_type+`" />`;

				let elem = `<tr data-item-id="${item.item_id}">`
					+ `<td>${td1}</td>`
					+ `<td data-item-td="unit_price">${td7}</td>`
					+ `<td data-item-td="unit_price">$ ${td2}</td>`
					+ `<td data-item-td="quantity"><input name="quantity" type="number" step="1" min="0" class="form-control col-lg-2 quantity" value="${td3}"></td>`
					+ `<td data-item-td="total">$ ${td6}</td>`
					+ '</tr>'

				$('table#items').append(elem)

				let item_subtotal = quantity * Number(unit_price);

				let qty_subtotal = quantity * Number(unit_price);
	
				$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="total"]`).html(`${currency} ${parseFloat(qty_subtotal).toFixed(2)}`);

				subtotal += item_subtotal;
				ptotal += qty_subtotal;
			})
			purchase.total_units = total_units;

			let freight = $('input[name=freight]').val();
			let discount = $('input[name=discount]').val();
			let tax = $('input[name=tax]').val();
			freight = parseFloat(freight).toFixed(2);
			discount = parseFloat(discount).toFixed(2);
			tax = parseFloat(tax).toFixed(2);
			
			if(discount > subtotal){

				discount = subtotal;
			};

			let grand_total = ptotal;
			grand_total = parseFloat(grand_total - discount).toFixed(2);
			grand_total =(Number(grand_total) + Number(freight));
			let tax_amount = Number(tax * grand_total / 100);
			grand_total = parseFloat(grand_total  + tax_amount).toFixed(2);

			$('table#summary tr td[data-summary-field="subtotal"]').html(`${currency} ${parseFloat(subtotal).toFixed(2)}`)
			$('table#summary tr td[data-summary-field="discount"]').html(`${currency} ${parseFloat(discount).toFixed(2)}`)
			$('table#summary tr td[data-summary-field="shipping"]').html(`${currency} ${parseFloat(freight).toFixed(2)}`)
			$('table#summary tr td[data-summary-field="tax"]').html(`${tax}%`)
			$('table#summary tr td[data-summary-field="total"]').html(`${currency} ${parseFloat(grand_total).toFixed(2)}`)
			
		}	
	})
}

function updateTotals() {
	let subtotal = 0;
	let ptotal = 0;
	let total_qty = 0;

	itemsAdded.forEach((item, i) => {
		console.log('Item has: ' + JSON.stringify(item));
			let qty = $(`table#items tbody tr[data-item-id=${item.item_id}] .quantity`).val();
			let unit = $(`table#items tbody tr[data-item-id=${item.item_id}] .itemunit`).val();
			
			// Update quantity in the original array
			itemsAdded[i].qty = qty;
			itemsAdded[i].unit_type = unit;

			let item_subtotal = qty * Number(item.price_per_unit);
			
			let item_total = Number(item_subtotal);

			let qty_subtotal = qty * Number(item.price_per_unit);
			let qty_total = Number(qty_subtotal);
			
			$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="subtotal"]`).html(`${currency} ${parseFloat(item_subtotal).toFixed(2)}`);
			$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="total"]`).html(`${currency} ${parseFloat(qty_total).toFixed(2)}`);

			subtotal += item_total;

			ptotal += Number(qty_subtotal);
			total_qty += Number(item.qty);
			
		});
	
	Object.values(purchase.items).forEach((item, i) => {
		
		let qty = $(`table#items tbody tr[data-item-id=${item.item_id}] .quantity`).val();
		let unit = $(`table#items tbody tr[data-item-id=${item.item_id}] .itemunit`).val();

		Object.values(purchase.items)[i].qty = qty;
		Object.values(purchase.items)[i].unit_type = unit;

		let quantity = item.qty;
		let item_price = item.unit_price;

		
		let item_subtotal = quantity * Number(item_price);
		let item_total = Number(item_subtotal);
		let qty_subtotal = quantity * Number(item_price);
		let qty_total = Number(qty_subtotal);
		$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="total"]`).html(`${currency} ${parseFloat(qty_total).toFixed(2)}`);

		subtotal += Number(item_total);
		ptotal += Number(qty_subtotal);
		total_qty += Number(item.quantity);
	});

	let freight = $('input[name=freight]').val();
	let discount = $('input[name=discount]').val();
	let tax = $('input[name=tax]').val();
	if(freight == ''){
		freight = 0;
	}
	
	if(discount == ''){
		discount = 0;
	}
	
	if(tax == ''){
		tax = 0;
	}
	
	freight = parseFloat(freight).toFixed(2);
	discount = parseFloat(discount).toFixed(2);
	tax = parseFloat(tax).toFixed(2);
	
	if(discount > subtotal){

		discount = subtotal;
	};

	let grand_total = ptotal;
	grand_total = parseFloat(grand_total - discount).toFixed(2);
	grand_total =(Number(grand_total) + Number(freight));
	let tax_amount = Number(tax * grand_total / 100);
	grand_total = parseFloat(grand_total  + tax_amount).toFixed(2);

	$('table#summary tr td[data-summary-field="subtotal"]').html(`${currency} ${parseFloat(subtotal).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="discount"]').html(`${currency} ${parseFloat(discount).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="shipping"]').html(`${currency} ${parseFloat(freight).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="tax"]').html(`${tax}%`)
	$('table#summary tr td[data-summary-field="total"]').html(`${currency} ${parseFloat(grand_total).toFixed(2)}`)
}



	function addItem(itemId) {
		// Item added already? Let the user know
		if($(`table#items tr[data-item-id="${itemId}"]`).length) {
			showError("error", "purchases.frontend.item_already_added")
			return
		}

		// Enable rest of fields
		$('input[name=freight]').prop('disabled', false);
		$('input[name=discount]').prop('disabled', false);
		$('input[name=tax]').prop('disabled', false);

		var vendor = $('select[name=vendor]').val()
		var itemId = itemId;
		console.log('vendor = '+vendor);
		console.log('item = '+itemId);
		var url = '<?= base_url('inventory/Backend/Items/show/') ?>';
		var request_method = "POST"; //get form GET/POST method

		$.ajax({
			type: request_method,
			url: url,
			data: {itemId: itemId, vendor: vendor},
			dataType:'JSON', 
			success: function(response){
				console.log(response.data[0]);
			
			let item = response.data;
			var itemObj = {};
			
			item.forEach(maker => {
				itemObj.item_id = maker.item_id;
				itemObj.item_name = maker.item_name;
				itemObj.item_number = maker.item_number;
				itemObj.price_per_unit = maker.price_per_unit;
				itemObj.unit_type = maker.unit_type;
				itemObj.unit_amount = maker.unit_amount;
			})
			
			itemObj.qty = 0 

			itemsAdded.push(itemObj)
			
			let td1 = '<div class="d-flex">'
				+ '<div>'
				+ '<button type="button" class="btn item-delete btn-secondary"><i class="fa fa-trash"></i></button>'
				+ '</div>'
				+ '<div>'
				+ `<strong>${itemObj.item_name}</strong>`
				+ '<br />'
				+ itemObj.item_number
				+ '<br />'
				+ itemObj.unit_type
				+ '</div>'
				+ '</div>'
			let td2 = itemObj.price_per_unit;
			let td3 = `<input type="number" class="form-control form-control-sm quantity" name="quantity" min="0" value="0" />`;
			let td4 = parseFloat(0).toFixed(2);
			let td5 = itemObj.item_vendor_tax;
			let td6 = parseFloat(0).toFixed(2);
			let td7 = `<input type="text" class="form-control form-control-sm itemunit" name="itemunit" value="`+itemObj.unit_amount+` `+itemObj.unit_type+`" />`;

			//table#items
			let elem = `<tr data-item-id="${itemObj.item_id}">`
				+ `<td>${td1}</td>`
				+ `<td data-item-td="unit_price">${td7}</td>`
				+ `<td data-item-td="unit_price">${currency} ${td2}</td>`
				+ `<td data-item-td="quantity">${td3}</td>`
				+ `<td data-item-td="total">${currency} ${td6}</td>`
				+ '</tr>'

			$('table#items').append(elem)
			}	
		})
	}

	function updatePO() {
		var purchase_order_id = <?= $purchase_id ?>;
		// Now make sure we have at least one item
		if($('table#items tbody tr').length == 0) {
			showError('error', "purchases.frontend.item_not_added")
			return
		}
		
		// Build data object!
		let data = {
			purchase_order_id: purchase_order_id,
			purchase_order_number: $('span[name=title]').html(),
			created_date: $('input[name=created_date]').val(),
			ordered_date: $('input[name=ordered_date]').val(),
			expected_date: $('input[name=expected_date]').val(),
			unit_measrement: $('input[name=unit_measrement]').val(),
			shipping_point: $('input[name=shipping_point]').val(),
			shipping_method_1: $('input[name=shipping_method_1]').val(),
			fob: $('select[name=fob]').val(),
			destination: $('input[name=destination]').val(),
			place_of_origin: $('input[name=place_of_origin]').val(),
			place_of_destination: $('input[name=place_of_destination]').val(),
			location_id: $('select[name=location]').val(),
			vendor_id: $('select[name=vendor]').val(),
			freight: $('input[name=freight]').val(),
			discount: $('input[name=discount]').val(),
			discount_type: 'amount',
			tax: $('input[name=tax]').val(),
			notes: $('textarea[name=purchase_order_order_notes]').val(),
			payment_terms: $('textarea[name=payment_terms]').val(),
			status: purchase.purchase_order_status,
			purchase_sent_status: $('#purchase_sent_status').val(),
			total_units: purchase.total_units,
			items: []
		}
		
		Object.values(purchase.items).forEach(item => {
			data.items.push({
				item_id: item.item_id,
				item_number: item.item_number,
				name: item.name,
				received_qty: 0,
				unit_price: item.unit_price,
				quantity: item.qty,
				unit_type: item.unit_type,
			})
		})

		itemsAdded.forEach(item => {
			data.items.push({
				item_id: item.item_id,
				item_number: item.item_number,
				name: item.item_name,
				received_qty: 0,
				unit_price: item.price_per_unit,
				unit_type: item.unit_type,
				quantity: item.qty
			})
		})

		console.log(data.items);
		
		var url = '<?= base_url('inventory/Backend/Purchases/updateDraft/') ?>'+purchase_order_id;
		var formData = data;
		$.ajax({
			type: 'POST',
			url: url,
			data: formData,
			success: function (data){

				$("#loading").css("display","none");
				swal(
					'Purchase Order Draft!',
					'Updated Successfully ',
					'success'
					).then(function() {
					window.location.href = '<?= base_url('inventory/Frontend/Purchases') ?>'
					});
				
			}
		});
	}


</script>
