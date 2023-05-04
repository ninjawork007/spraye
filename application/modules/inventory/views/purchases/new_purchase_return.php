<style>
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

	.return-all{
		float:right;
		margin-left: -50%;
	}

</style>

<div class="content invoicessss">
	<div class="panel-body">
		<div class="row">
			<div class="px-2 mt-n1 col">
				<div class="section variant-3">
					<div class="header">
						<div class="title">
							New Purchase Return
						</div>
						<div class="desc">
							Submit this form when you (will) return purchased merchandise from one of your vendors
						</div>
					</div>

					<div class="content-item">
					<form  action="<?= base_url('inventory/Backend/Purchases/returnOrder') ?>" method="post" id="receiving_purchase" name="receivingpurchase" enctype="multipart/form-data" >

							<div class="row mt-n3">
								<!-- Left -->
								<div class="column text-break pl-2 pr-2">
									<label for="location" class="d-block">Location*</label>
									<select name="location_id" id="location_id" class="custom-select" >
										<option value="" disabled selected>Select Location</option>
										<?php foreach($list_locations as $location) { ?>
										<option value="<?= $location->location_id ?>"><?= $location->location_name ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="vendor" class="d-block">Vendor*</label>
										<select name="vendor_id" id="vendor_id" class="custom-select">
											<option value="" disabled selected>Select vendor</option>
											<?php foreach($list_vendors as $vendor) { ?>
											<option value="<?= $vendor->vendor_id ?>"><?= $vendor->vendor_name ?></option>
											<?php } ?>
										</select>
										<div class="invalid-feedback"></div>
									</div>
								</div>

							</div>
							<div class="row mt-n3">
								<!-- Left -->
								<div class="column text-break pl-2 pr-2" id="hidden_sub">
									<div class="form-group sublocation-container">
										<label for="sub_location" class="d-block">Sub Location*</label>

										<select name="sub_location_id" id="sub_location_id" class="custom-select" >
                                        <option value="" disabled selected>Select Location</option>
											
										</select>
									</div>
								</div>

								<!-- Separator -->
								<div class="columns-separator"></div>
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
													<th>Unit price</th>
													<th>Return Qty </th>
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
										<input type="text" name="freight" id="freight" class="form-control" value="0"  />
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
										<input type="text" name="tax" id="tax" class="form-control" value=""/>
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>

							<div class="row mt-n3">
								<div class="col-md-6 text-break pl-2 pr-2 mt-3">
									<div class="form-group">
										<label for="purchase_order_order_notes" class="d-block">Notes</label>
										<textarea name="purchase_order_order_notes" id="purchase_order_order_notes" class="form-control" rows="6"></textarea>
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
												<td width="60" data-summary-field="tax">0%</td>
											</tr>
											<tr>
												<th width="40" class="font-weight-bold">Total Return</th>
												<td width="60" data-summary-field="total_return" class="font-weight-bold"><?= $settings->currency_symbol ?> 0.00 </td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<div>
								<div class="form-group">
									<label for="payment_term" class="d-block">Payment Term</label>
									<textarea name="payment_term" id="payment_terms" class="form-control" rows="6"></textarea>
								</div>
							</div>

							<hr class="mt-4" />

							<div class="text-right mt-2 mb-2">
								<button type="submit" class="btn btn-primary ">
								Return Item(s)
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

<script type="text/javascript">
	
let currency = "<?= addslashes($settings->currency_symbol) ?>";
let purchase = {};
let selectedLocation = 0;
let selectedSubLocation = 0;
let selectedVendor = 0;
let itemsAdded = [];

	// Show sub location after selecting location
	function showDiv(divId, element){
	  document.getElementById(divId).style.display = element.value == "" ? 'none' : 'block';
	}

	$('document').ready(function() {

		// Once a location and vendor are selected, enable items section
		$('select[name=location_id], select[name=sub_location_id], select[name=vendor_id]').on('change', e => {
            subLocation();
            getVendorDetails();
			let location = $('select[name=location_id]').val()
			let sublocation = $('select[name=sub_location_id]').val()
			let vendor = $('select[name=vendor_id]').val()
			
			if(location != '' && location != null && sublocation != '' && sublocation != null && vendor != '' && vendor != null) {
				selectedLocation = location
				selectedSubLocation = sublocation
				selectedVendor = vendor
			}
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
			itemsAdded.forEach((item, i) => {
				if(itemId == item.id){

					indexToRemove = i;
				}
			})
			itemsAdded.splice(indexToRemove, 1)

			updateTotals();
		})

		// When changing quantity of an item
		$(document).on('input', '.itemqty', function() {
			updateTotals();
		})

		// When changing price of an item
		$(document).on('input', '.itemprice', function() {
			updateTotals();
		})

		// When changing shipping cost, discount or tax...
		$('input[name=freight], input[name=discount], input[name=tax]').on('input', e => {
			updateTotals();
		})

		$('form').on('submit', e => {
			e.preventDefault()
			createPurchase()
		})
	})



	function subLocation() {
		var location = $('select[name=location_id]').val()
		var url = '<?= base_url('inventory/Backend/Locations/subLocationlist') ?>';
		var request_method = "GET";
		
		$.ajax({
			type: request_method,
			url: url,
			data: {location: location},
			dataType:'JSON', 
			success: function(response){
				$('select#sub_location_id').empty()
				response.result.forEach(sublocation => {
					let elem = `<option value="${sublocation.sub_location_id}">`+ `${sublocation.sub_location_name}`+ '</option>'
					$('select#sub_location_id').append(elem)
				})
			}
		});
	}

function updateTotals() {
	let subtotal = 0;
	let total_qty = 0;
	let return_total = 0;
	
	itemsAdded.forEach((item, i) => {
		let return_qty = $(`table#items tbody tr[data-item-id=${item.item_id}] .itemqty`).val();
		let item_price = $(`table#items tbody tr[data-item-id=${item.item_id}] .itemprice`).val();

		itemsAdded[i].return_qty = return_qty;
		itemsAdded[i].unit_price = item_price;

		let item_subtotal = return_qty * Number(item_price);
		let item_total = Number(item_subtotal);
		$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="subtotal"]`).html(`${currency} ${parseFloat(item_subtotal).toFixed(2)}`);
		$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="total"]`).html(`${currency} ${parseFloat(item_total).toFixed(2)}`);

		subtotal += item_total;
		total_qty += Number(item.received_qty);
		return_total += Number(return_qty);
	});

	if(return_total == total_qty){
		purchase.purchase_order_status = 4;
		purchase['return_total'] = return_total;
	} else {
		purchase.purchase_order_status = 2;
		purchase['return_total'] = return_total;
	}

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

	let grand_total = subtotal;
	grand_total = parseFloat(grand_total - discount).toFixed(2);
	grand_total =(Number(grand_total) + Number(freight));
	let tax_amount = Number(tax * grand_total / 100);
	grand_total = parseFloat(grand_total  + tax_amount).toFixed(2);

	$('table#summary tr td[data-summary-field="subtotal"]').html(`${currency} ${parseFloat(subtotal).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="discount"]').html(`${currency} ${parseFloat(discount).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="shipping"]').html(`${currency} ${parseFloat(freight).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="tax"]').html(`${tax}%`)
	$('table#summary tr td[data-summary-field="total_return"]').html(`${currency} ${parseFloat(grand_total).toFixed(2)}`)
}

function autocomplete() {
	var search = $('input[name=item_search]').val()
	var vendor = $('select[name=vendor_id]').val()
	var url = '<?= base_url('inventory/Backend/Items/list') ?>';
	var request_method = "GET"; //get form GET/POST method
	$.ajax({
		type: request_method,
		url: url,
		data: {search: search, vendor: vendor},
		dataType:'JSON', 
		success: function(response){
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

function getVendorDetails(){
	var vendor = $('select[name=vendor_id]').val()
	var url = '<?= base_url('inventory/Backend/Vendors/Details') ?>';
	var request_method = "GET";
	
	$.ajax({
		type: request_method,
		url: url,
		data: {vendor: vendor},
		dataType:'JSON', 
		success: function(response){
			console.log(response);
			$("#payment_terms").val(response.terms);
			$("#discount").val(response.po_discount);
		}
	});
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

		var vendor = $('select[name=vendor_id]').val()
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
			let td2 = `<input type="number" class="form-control form-control-sm itemprice" name="return_price" min="0" value="`+itemObj.price_per_unit+`" />`;
			let td3 = `<input type="number" class="form-control form-control-sm itemqty" name="itemqty" min="0" value="0" />`;

			//table#items
			let elem = `<tr data-item-id="${itemObj.item_id}">`
				+ `<td>${td1}</td>`
				+ `<td data-item-td="unit_price">${td2}</td>`
				+ `<td data-item-td="quantity">${td3}</td>`
				+ `<td data-item-td="total">0.0</td>`
				+ '</tr>'

			$('table#items').append(elem)
			}	
		})
	}

function createPurchase() {
	var purchase_order_id = '';
	// Now make sure we have at least one item
	if($('table#items tbody tr').length == 0) {
		showError('error', "purchases.frontend.item_not_added")
		return
	}
	console.log(purchase);
	// Build data object!
	let data = {
		purchase_order_id: purchase_order_id,
		vendor_id: $('select[name=vendor_id]').val(),
		location_id: $('select[name=location_id]').val(),
		sub_location_id: $('select[name=sub_location_id]').val(),
		freight: $('input[name=freight]').val(),
		discount: $('input[name=discount]').val(),
		discount_type: 'amount',
		payment_term: $("#payment_terms").val(),
		tax: $('input[name=tax]').val(),
		notes: $('textarea[name=purchase_order_order_notes]').val(),
		status: purchase.purchase_order_status,
		total_return: purchase.return_total,
		total_units: purchase.total_units,
		items: []
	}
	
	itemsAdded.forEach(item => {
		data.items.push({
			item_id: item.item_id,
			item_number: item.item_number,
			name: item.item_name,
			unit_type: item.unit_type,
			received_qty: item.received_qty,
			return_qty: item.return_qty,
			unit_price: item.unit_price,
			quantity: item.quantity
		})
	})
	
	var url = '<?= base_url('inventory/Backend/Purchases/returningItemsOrder/') ?>';
	var formData = data;
	
	$.ajax({
		type: 'POST',
		url: url,
		data: formData,
		success: function (data){

			$("#loading").css("display","none");
			swal(
				'Purchase Order Items!',
				'Returned Successfully ',
				'success'
				).then(function() {
				location.reload();
				});
			
		}
	});
}
</script>