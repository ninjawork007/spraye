<?php
	// $this->load->view('templates/master')
	// $this->section('content')
	// $this->load->view('components/error_modal'); 
?>

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
	.form-control {
	display: block;
	width: 100%;
	height: calc(1.5em + 0.75rem + 2px);
	padding: 0.375rem 0.75rem;
	font-size: 14px;
	font-weight: 400;
	line-height: 1.5;
	color: #495057;
	background-color: #fff;
	background-clip: padding-box;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
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

</style>

<div class="content invoicessss">
	<div class="panel-body">
		<div class="row">
			<div class="px-2 mt-n1 col">
				<div class="section variant-3">
					<div class="header">
						<div class="title">
							New transfer
						</div>
						<div class="desc">
						Fill this when you would like to transfer stock from one warehouse to another. This will update your inventory in both warehouses, without affecting your accounting
						</div>
					</div>

					<div class="content-item">
							<form  action="<?= base_url('inventory/Backend/Transfers/create') ?>" method="post" id="add_new_transfer" name="addnewtransfer" enctype="multipart/form-data" >
							<h6 class="h6-5 text-secondary mb-3">
								Basic information
							</h6>

							<div class="row mt-0">
								<!-- Left -->
								<div class="col-md-6 text-break pl-2 pr-2">
									<div class="form-group">
										<label for="from_sub_location_id" class="d-block">Source location (A)*</label>
										<select name="from_sub_location_id" id="from_sub_location_id" class="custom-select">
											<option value="" disabled selected>Select Location</option>
											<?php foreach($list_sub_locations as $sub) { ?>
											<option value="<?= $sub->sub_location_id ?>"><?= $sub->sub_location_name ?></option>
											<?php } ?>
										</select>
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<!-- Separator -->
								<div class="columns-separator"></div>

								<!-- Right -->
								<div class="col-md-6 text-break pl-2 pr-2">
									<div class="form-group">
										<label for="to_sub_location_id" class="d-block">Target location (B)*</label>
										<select name="to_sub_location_id" id="to_sub_location_id" class="custom-select">
											<option value="" disabled selected>Select Location</option>
											<?php foreach($list_sub_locations as $sub) { ?>
											<option value="<?= $sub->sub_location_id ?>"><?= $sub->sub_location_name ?></option>
											<?php } ?>
										</select>
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>

							<div class="row mt-4">
								<div class="col-md-12 text-break pl-2 pr-2">
									<h6 class="h6-5 text-secondary">Add items</h6>
									<span class="autocomplete-desc mb-3">
									To add items, type item name, and select the item you'd like to add. To start adding, select source and target location.
									</span>

									<div class="autocomplete-container">
										<input type="text" id="item_search" name="item_search" placeholder="Scan item, type item name..." autocomplete="off" class="form-control" disabled />
										<ul class="dropdown-menu" id="itemSuggestions"></ul>
									</div>
								</div>
							</div>

							<div class="row mt-4">
								<div class="col-md-12 text-break pl-2 pr-2">
									<div class="table-responsive">
										<table id="items" class="table table-bordered">
											<thead>
												<tr>
													<th>Item name</th>
													<th>Current stock in location A</th>
													<th>Transfer quantity</th>
													<th>Stock change in location A</th>
													<th>Stock change in location B</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
							</div>

							<div class="row mt-n3">
								<div class="col-md-12 text-break pl-2 pr-2 mt-3">
									<div class="form-group">
										<label for="notes" class="d-block">Notes</label>
										<textarea name="notes" id="notes" class="form-control" rows="6"></textarea>
									</div>
								</div>
							</div>

							<hr class="mt-4" />

							<div class="text-right mt-2 mb-2">
								<button type="submit" class="btn px-3 btn-outline-primary">
									Create Transfer
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
'use strict';

let itemsAdded = [];
let selectedFromSub = 0;
let selectedToSub = 0;

let warehouses = [];

let arrowRight = `<i class="fas fa-long-arrow-alt-right"></i>`;

	$('document').ready(function() {

		// Populate selects
		// loadWarehouses()

		// Once both warehouses are selected, enable items section
		$('select[name=from_sub_location_id], select[name=to_sub_location_id]').on('change', function(e) {
			let t = $(this)
			let from_sub_location_id = $('select[name=from_sub_location_id]').val()
			let to_sub_location_id = $('select[name=to_sub_location_id]').val()
			console.log(from_sub_location_id);

			if(from_sub_location_id != '' && from_sub_location_id != null && to_sub_location_id != '' && to_sub_location_id != null) {
				// Same warehouse selected twice? Show error
				if(from_sub_location_id == to_sub_location_id) {
					t.prop('selectedIndex', 0)
					showError("Errors.error", "frontend.same_warehouse_id")
					return
				}

				selectedFromSub = from_sub_location_id
				selectedToSub = to_sub_location_id
				$('select[name=from_sub_location_id]').prop('disabled', true)
				$('select[name=to_sub_location_id]').prop('disabled', true)
				$('input[name=item_search]').prop('disabled', false)
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
			autocomplete()
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
			addItem(id)
		})

		// To remove item
		$('table#items').on('click', 'tr td button', e => {
			let parent = $(e.currentTarget).parent().parent().parent().parent()
			let itemId = parent.data('item-id')

			parent.remove()

			let indexToRemove = -1
			itemsAdded.forEach((item, i) => {
				if(itemId == item.id)
					indexToRemove = i
			})
			itemsAdded.splice(indexToRemove, 1)
		})

		// When changing transfer quantity of an item
		$('body').on('input', 'table#items tr td input', e => {
			updateTotals()
		})

		$('form').on('submit', e => {
			e.preventDefault()
			createTransfer()
		})
	})

function onSearchItemCode() {
	let itemCode = $('input[name=item_search]').val()

	$('input[name=item_search]').blur().val('')

	// axios.get(`api/items/code`, {
	// 	params: {
	// 		code: itemCode
	// 	}
	// }).then(response => {
	// 	addItem(response.data.id)
	// })
}

function autocomplete() {
	let search = $('input[name=item_search]').val()
	var url = '<?= base_url('inventory/Backend/Items/list') ?>';
	var request_method = "GET"; //get form GET/POST method

	$.ajax({
		type: request_method,
		url: url,
		data: {search: search},
		dataType:'JSON', 
		success: function(response){
			console.log(response);
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

// function loadWarehouses() {
// 	axios.get(`api/warehouses/list`).then(response => {
// 		let elems = []

// 		response.data.forEach(warehouse => {
// 			elems += `<option value="${warehouse.id}">${warehouse.name}</option>`
// 		})

// 		$('select#from_warehouse').append(elems)
// 		$('select#to_warehouse').append(elems)
// 	})
// }

function addItem(itemId) {
	// Item added already? Let the user know
	if($(`table#items tr[data-item-id="${itemId}"]`).length) {
		showError("Errors.error", "frontend.item_already_added")
		return
	}

	var url = '<?= base_url('inventory/Backend/Items/list/') ?>'+itemId;
  var request_method = "GET"; //get form GET/POST method

	$.ajax({
		type: request_method,
		url: url,
		data: {itemId: itemId},
		dataType:'JSON', 
		success: function(response){
			console.log(response.result);
		let item = response.result

		// Get from and to warehouse details of this item
		let quantities = {}
		item.quantities.forEach(o => {
			if(o.warehouse.id == selectedFromWarehouse)
				quantities.from = o
			else if(o.warehouse.id == selectedToWarehouse)
				quantities.to = o
		})

		item.quantities = quantities
		item.transfer_quantity = 0

		itemsAdded.push(item)

		let td1 = '<div class="d-flex">'
			+ '<div>'
			+ '<button type="button" class="btn item-delete btn-secondary"><i class="fas fa-trash-alt"></i></button>'
			+ '</div>'
			+ '<div>'
			+ `<strong>${item.name}</strong>`
			+ '<br />'
			+ item.code
			+ '</div>'
			+ '</div>'
		let td2 = item.quantities.from.quantity
		let td3 = '<div class="input-group input-group-sm">'
				+ '<input type="text" class="form-control form-control-sm" value="0" />'
				+ '</div>'
		let td4 = `${item.quantities.from.quantity} ${arrowRight} ${item.quantities.from.quantity}`
		let td5 = `${item.quantities.to.quantity} ${arrowRight} ${item.quantities.to.quantity}`

		//table#items
		let elem = `<tr data-item-id="${itemId}">`
			+ `<td>${td1}</td>`
			+ `<td data-item-td="current_stock_from">${td2}</td>`
			+ `<td data-item-td="transfer_quantity">${td3}</td>`
			+ `<td data-item-td="source_warehouse_change">${td4}</td>`
			+ `<td data-item-td="target_warehouse_change">${td5}</td>`
			+ '</tr>'

		$('table#items').append(elem)
		}
	})
}

function updateTotals() {
	itemsAdded.forEach((item, i) => {
		let input = $(`table#items tbody tr[data-item-id=${item.id}] input`)

		let transferQuantity = Utils.getInt(input.val())

		// If transfer quantity is greater than stock available, rewrite user input
		if(transferQuantity > item.quantities.from.quantity) {
			transferQuantity = Utils.getInt(item.quantities.from.quantity)
			input.prop('value', transferQuantity)
			input.val(transferQuantity)
		}

		// Update original array
		itemsAdded[i].transfer_quantity = transferQuantity

		let fromQuantity = Utils.getInt(item.quantities.from.quantity)
		let toQuantity = Utils.getInt(item.quantities.to.quantity)

		let currentStockFrom = Utils.getInt(item.quantities.from.quantity)
		let sourceWarehouseChange = `${fromQuantity} ${arrowRight} ${fromQuantity - transferQuantity}`
		let targetWarehouseChange = `${toQuantity} ${arrowRight} ${toQuantity + transferQuantity}`

		$(`table#items tbody tr[data-item-id=${item.id}] td[data-item-td="current_stock_from"]`).html(currentStockFrom)
		$(`table#items tbody tr[data-item-id=${item.id}] td[data-item-td="source_warehouse_change"]`).html(sourceWarehouseChange)
		$(`table#items tbody tr[data-item-id=${item.id}] td[data-item-td="target_warehouse_change"]`).html(targetWarehouseChange)
	})
}

function createTransfer() {
	// Perform initial validation
	let validator = new Validator()
	validator.addSelect('from_warehouse', 'selected', "frontend.from_warehouse_not_selected")
	validator.addSelect('to_warehouse', 'selected', "frontend.to_warehouse_not_selected")

	if(!validator.validate())
		return

	// Now make sure we have at least one item
	if($('table#items tbody tr').length == 0) {
		showError('error', "frontend.item_not_added")
		return
	}

	// Build data object!
	let data = {
		from_warehouse_id: $('select[name=from_warehouse]').val(),
		to_warehouse_id: $('select[name=to_warehouse]').val(),
		notes: $('textarea[name=notes]').val(),
		items: []
	}

	itemsAdded.forEach(item => {
		data.items.push({
			id: item.id,
			name: item.name,
			code: item.code,
			original_from_quantity: item.quantities.from.quantity,
			original_to_quantity: item.quantities.to.quantity,
			transfer_quantity: item.transfer_quantity
		})
	})

	axios.post(`api/transfers`, data).then(response => {
		if(response && response.data && response.data.id)
			location.href = `<?= base_url() ?>/transfers/${response.data.id}`
	})
}
</script>