<?php
$this->load->view('templates/master');
// $this->load->view('items/modals/item_modal');
// $this->load->view('items/modals/add_supplier_modal');
// $this->load->view('items/modals/edit_supplier_modal') 
// $this->load->view('items/modals/edit_item_modal') 
// $this->load->view('components/error_modal') 
// $this->load->view('components/confirmation_modal') 

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

</style>


<!-- Content area -->
<div class="content invoicessss">
	<div class="panel-body">
		<!-- Start of Items -->
		<div class="row">
			<div class="px-2 py-1 col">
				<div class="section variant-2">
					<div class="header d-flex align-items-center justify-content-between">
						<div class="title">
							Items
						</div>
						<div class="buttons d-flex">
					
					<a href="<?= base_url('api/items/export') ?>" class="btn px-3 btn-outline-primary  mr-2">
						Export CSV
					</a>

					<a href="<?= base_url('inventory/Frontend/Items/new') ?>" class="btn px-3 btn-outline-primary ">
						New Items
					</a>
				
				</div>
						

					
					</div>

					<div class="content">
						<div class="table-responsive">
							<table id="items" class="table table-striped table-bordered table-hover">
								<thead>
									
									
									<tr>
										<th>Item Name</th>
										<th>Item #</th>
										<th>Item Description</th>
										<th>Item Type</th>
										<th>Unit Definition</th>
										<!-- <th># of Units on Hand</th>
										<th>Average cost per unit</th>
										<th>Available Vendors</th>
										<th>Preferred Vendor</th>
										<th>Ideal Ordering Timeframe</th> -->
									</tr>
								</thead>
								<tbody>
									<?php
										if($all_items){
											foreach($all_items as $item){
									?>
									<tr>
										<td><?= $item->item_name ?></td>
										<td><?= $item->item_number ?></td>
										<td><?= $item->item_description ?></td>
										<td><?= $item->item_type_id ?></td>
										<td><?= $item->unit_definition ?></td>
										
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
		<!-- End of Items -->
	</div>
</div>
<!-- End of content area -->

<script type="text/javascript">
'use strict';
	
var openItem = {};
var openSupplier = {};
var table = {};
let currency = "";

(function($) {
	'use strict';

	$('document').ready(function() {
		$('.main-loader').fadeOut(100)

		// Link table to the loader
		$('table#items').on('processing.dt', (e, settings, processing) => {
			if(processing)
				$('.main-loader').fadeIn(100)
			else
				$('.main-loader').fadeOut(100)
		})

		// Load table
		table = $('table#items').DataTable({
			// serverSide: true,
			// ajax: "<?= base_url('inventory/controllers/frontend/items') ?>",
			// columns: [
			// 	{ data: "item_name" },
			// 	{ data: "item_number" },
			// 	{ data: "item_description" },
			// 	{ data: "item_type_id" },
			// 	{ data: "unit_definition" },
				
			// ]
		})

		// When clicking an item from the table
		$('table#items tbody').on('click', 'tr', function() {
			let id = table.row(this).data().DT_RowId
			loadItem(id)
		})

		// Update URL when hiding item modal
		$('#itemModal').on('hide.bs.modal', e => {
			window.history.pushState(null, '', `<?= base_url() ?>/items`)
		})

		// Update (and show) item details when adding/editing suppliers,
		// and when editing item
		$('#addSupplierModal, #editSupplierModal, #editItemModal').on('hide.bs.modal', e => {
			loadItem(openItem.id)
		})

		// Close item modal when opening another one
		$('#addSupplierModal, #editSupplierModal, #editItemModal').on('show.bs.modal', e => {
			$('#itemModal').modal('hide')
		})

		// If we have an item we need to show...
		<?php
		//  if($itemId != false) { ?>
		// loadItem( 
		// $itemId 
		
		// )
		<?php 
			// } 
			?>

		// Listener for submit -- To add new supplier
		$('#addSupplierModal form').on('submit', e => {
			e.preventDefault();
			addSupplierRelationSubmit()
		})

		// Listener for submit -- To edit a supplier
		$('#editSupplierModal form').on('submit', e => {
			e.preventDefault()
			editSupplierRelationSubmit()
		})

		// Listener for submit -- To edit an item
		$('#editItemModal form').on('submit', e => {
			e.preventDefault()
			editItemSubmit()
		})

		// When selecting a "code type" in the edit item modal
		$('select[name=edit_item_code_type]').on('change', e => {
			let val = $(e.currentTarget).val()

			code_requirements.forEach(requirement => {
				if(requirement.type == val)
					$('input[name=edit_item_code]').parent().parent().children('.text-muted').text(requirement.text)
			})
		})
	})
})(jQuery)

// To load an item and open the modal
function loadItem(id) {
	axios.get(`api/items/${id}`).then(response => {
		let item = response.data

		openItem = item

		window.history.pushState(null, '', `<?= base_url() ?>/items/${id}`)

		$('#itemModal').modal('show')

		let min_alert = item.min_alert == null ? '' : item.min_alert
		let max_alert = item.max_alert == null ? '' : item.max_alert

		$('#itemModal td[data-item-field="item_name"]').text(item.item_name)
		$('#itemModal td[data-item-field="item_number"]').text(item.item_number)
		$('#itemModal td[data-item-field="item_type_id"]').text(item.item_type_id)
		// $('#itemModal td[data-item-field="brand"]').text(item.brand.name)
		// $('#itemModal td[data-item-field="category"]').text(item.category.name)
		// $('#itemModal td[data-item-field="min_max_alert"]').text(`${min_alert} / ${max_alert}`)
		$('#itemModal td[data-item-field="item_description"]').text(`${item.item_description}`)
		$('#itemModal td[data-item-field="unit_definition"]').text(`${item.unit_definition}`)
		// $('#itemModal td[data-item-field="notes"]').text(`${item.notes}`)

		$('#itemModal table#itemStock tbody').html('')

		item.quantities.forEach(quantity => {
			$('#itemModal table#itemStock').append(`<tr><th width="40">${quantity.warehouse.name}</th><td width="60">${quantity.quantity}</td></tr>`)
		})

		if(item.suppliers.length == 0)
			$('#itemModal #itemSuppliers').html("<?= 'suppliers.no_suppliers' ?>")
		else{
			$('#itemModal #itemSuppliers').html('')

			item.suppliers.forEach(supplier => {
				let table = '<table class="table stacked mt-2">'
					+ '<tbody>'
					+ '<tr>'
					+ '<th width="40"><?= 'suppliers.supplier.supplier' ?></th>'
					+ `<td width="60">${supplier.name}</td>`
					+ '</tr>'
					+ '<tr>'
					+ '<th width="40"><?= 'suppliers.supplier.part_number' ?></th>'
					+ `<td width="60">${supplier.part_number}</td>`
					+ '</tr>'
					+ '<tr>'
					+ '<th width="40"><?= 'suppliers.supplier.price' ?></th>'
					+ `<td width="60">${supplier.price}</td>`
					+ '</tr>'
					+ '<tr>'
					+ '<th width="40"><?= 'suppliers.supplier.tax' ?></th>'
					+ `<td width="60">${supplier.tax}%</td>`
					+ '</tr>'
					<?php 
					// if($logged_user->role == 'admin' { 
						?>
					+ '<tr>'
					+ '<th width="40"><?= 'suppliers.actions' ?></th>'
					+ '<td width="60">'
					+ `<button type="button" class="btn mr-2 btn-outline-primary btn-sm" onclick="editSupplierRelation(${item.id}, ${supplier.id})">Edit</button>`
					+ `<button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteSupplierRelation(${item.id}, ${supplier.id})">Delete</button>`
					+ '</td>'
					+ '</tr>'
					<?php 
						// }
						?>
					+ '</tbody>'
					+ '</table>'

				$('#itemModal #itemSuppliers').append(table)
			})
		}

		$('#itemModal #qr').html('')

		if(item.code_type == 'none') {
			$('#itemModal #barcode').css('display', 'none')
			$('#itemModal #qr').css('display', 'none')
		}else if(item.code_type == 'qr') {
			$('#itemModal #barcode').css('display', 'none')
			$('#itemModal #qr').css('display', 'block')

			new QRCode($('#itemModal #qr')[0], {
				text: item.code,
				width: 120,
				height: 120
			})
		}else{
			$('#itemModal #barcode').css('display', 'block')
			$('#itemModal #qr').css('display', 'none')

			let code_type = item.code_type

			if(code_type == 'ean-8')
				code_type = 'ean8'
			else if(code_type == 'ean-13')
				code_type = 'ean13'
			else if(code_type == 'upc-a')
				code_type = 'upc'

			JsBarcode('#itemModal #barcode svg', item.code, { format: code_type })
		}
	})
}


// To open the modal to add a new supplier to an item
function addSupplierRelation() {
	$('#addSupplierModal').modal('show')

	$('#addSupplierModal select[name=add_supplier]').val('')
	$('#addSupplierModal input[name=add_price]').val('0')
	$('#addSupplierModal input[name=add_part_number]').val('')
	$('#addSupplierModal input[name=add_tax]').val('0')
}

function addSupplierRelationSubmit() {
	let validator = new Validator()
	validator.addSelect('add_supplier', 'selected', "<?= 'Validation.items.suppliers.supplier_id_numeric' ?>")
	validator.addInputText('add_price', 'decimal', "<?= 'Validation.items.suppliers.price_decimal' ?>")
	validator.addInputTextVal('add_part_number', 'maxLength', 45, "<?= 'Validation.items.suppliers.part_number_max_length' ?>")
	validator.addInputText('add_tax', 'decimal', "<?= 'Validation.items.suppliers.tax_decimal' ?>")

	if(!validator.validate())
		return

	axios.post(`api/items/${openItem.id}/add-supplier`, {
		supplier_id: $('select[name=add_supplier]').val(),
		part_number: $('input[name=add_part_number]').val(),
		price: $('input[name=add_price]').val(),
		tax: $('input[name=add_tax]').val()
	}).then(response => {
		$('#addSupplierModal').modal('hide')
	})
}


// When clicking on edit item
function editItem() {
	$('input[name=edit_item_name]').val(openItem.name)
	$('input[name=edit_item_code]').val(openItem.code)
	$('select[name=edit_item_code_type]').val(openItem.code_type).change()
	$('select[name=edit_item_brand]').val(openItem.brand.id).change()
	$('select[name=edit_item_category]').val(openItem.category.id).change()
	$('input[name=edit_item_sale_price]').val(openItem.sale_price).change()
	$('input[name=edit_item_sale_tax]').val(openItem.sale_tax).change()
	$('input[name=edit_item_weight]').val(openItem.weight).change()
	$('input[name=edit_item_width]').val(openItem.width).change()
	$('input[name=edit_item_height]').val(openItem.height).change()
	$('input[name=edit_item_depth]').val(openItem.depth).change()
	$('input[name=edit_item_min_alert]').val(openItem.min_alert).change()
	$('input[name=edit_item_max_alert]').val(openItem.max_alert).change()
	$('textarea[name=edit_item_notes]').val(openItem.notes).change()

	$('#editItemModal').modal('show')
}

function editItemSubmit() {
	let validator = new Validator()
	validator.addInputTextVal('edit_item_name', 'minLength', 1, "<?= 'Validation.items.name_min_length' ?>")
	validator.addInputTextVal('edit_item_name', 'maxLength', 45, "<?= 'Validation.items.name_max_length' ?>")
	validator.addInputTextVal('edit_item_code', 'minLength', 1, "<?= 'Validation.items.code_min_length' ?>")
	validator.addInputTextCustom('edit_item_code', value => {
		let barcode_type = $('select[name=edit_item_code_type]').val()

		let barcode_rules = {
			'none': /^[ -~]{1,500}$/,
			'code39': /^[A-Z0-9\s-.$/+%]+$/,
			'code128': /^[ -~]{1,128}$/,
			'ean-8': /^\d{8}$/,
			'ean-13': /^\d{13}$/,
			'upc-a': /^\d{12}$/,
			'qr': /^[ -~]{1,500}$/
		}
		if(barcode_rules[barcode_type].test(value))
			return true
		return false
	}, "<?= 'Validation.items.code_invalid_frontend' ?>")
	validator.addInputText('edit_item_sale_price', 'decimal', "<?= 'Validation.items.sale_price_greater_than' ?>")
	validator.addInputTextVal('edit_item_sale_price', 'minValue', 0.01, "<?= 'Validation.items.sale_price_greater_than' ?>")
	validator.addInputText('edit_item_sale_tax', 'decimal', "<?= 'Validation.items.sale_tax_greater_than_equal_to' ?>")
	validator.addInputTextVal('edit_item_sale_tax', 'minValue', 0, "<?= 'Validation.items.sale_tax_greater_than_equal_to' ?>")
	validator.addInputText('edit_item_weight', 'optional-decimal', "<?= 'Validation.items.weight_decimal' ?>")
	validator.addInputText('edit_item_width', 'optional-decimal', "<?= 'Validation.items.width_decimal' ?>")
	validator.addInputText('edit_item_height', 'optional-decimal', "<?= 'Validation.items.height_decimal' ?>")
	validator.addInputText('edit_item_depth', 'optional-decimal', "<?= 'Validation.items.depth_decimal' ?>")
	validator.addInputText('edit_item_min_alert', 'optional-integer', "<?= 'Validation.items.min_alert_greater_than_equal_to' ?>")
	validator.addInputText('edit_item_max_alert', 'optional-integer', "<?= 'Validation.items.max_alert_greater_than_equal_to' ?>")
	validator.addInputTextCustom('edit_item_max_alert', value => {
		if(value != '' && value != null) {
			if(value < 1)
				return false
		}
		return true
	}, "<?= 'Validation.items.max_alert_greater_than_equal_to' ?>")

	if(!validator.validate())
		return

	axios.put(`api/items/${openItem.id}`, {
		name: $('input[name=edit_item_name]').val(),
		code: $('input[name=edit_item_code]').val(),
		code_type: $('select[name=edit_item_code_type]').val(),
		sale_price: $('input[name=edit_item_sale_price]').val(),
		sale_tax: $('input[name=edit_item_sale_tax]').val(),
		description: $('textarea[name=edit_item_description]').val(),
		weight: $('input[name=edit_item_weight]').val(),
		width: $('input[name=edit_item_width]').val(),
		height: $('input[name=edit_item_height]').val(),
		depth: $('input[name=edit_item_depth]').val(),
		min_alert: $('input[name=edit_item_min_alert]').val(),
		max_alert: $('input[name=edit_item_max_alert]').val(),
		notes: $('textarea[name=edit_item_notes]').val(),
		category_id: $('select[name=edit_item_category]').val(),
		brand_id: $('select[name=edit_item_brand]').val()
	}).then(response => {
		$('#editItemModal').modal('hide')
		table.ajax.reload()
	})
}

// To open the modal to edit a supplier relation
function editSupplierRelation(itemId, supplierId) {
	axios.get(`api/items/${itemId}/supplier/${supplierId}`).then(response => {
		let data = response.data

		openSupplier = data

		$('#editSupplierModal select[name=edit_supplier]').html(`<option value="1">${data.name}</option>`)
		$('#editSupplierModal select[name=edit_supplier]').val(1)
		$('#editSupplierModal input[name=edit_price]').val(data.price)
		$('#editSupplierModal input[name=edit_part_number]').val(data.part_number)
		$('#editSupplierModal input[name=edit_tax]').val(data.tax)

		$('#editSupplierModal').modal('show')
	})
}

function editSupplierRelationSubmit() {
	let validator = new Validator()
	validator.addInputText('edit_price', 'decimal', "<?= 'Validation.items.suppliers.price_decimal' ?>")
	validator.addInputTextVal('edit_part_number', 'maxLength', 45, "<?= 'Validation.items.suppliers.part_number_max_length' ?>")
	validator.addInputText('edit_tax', 'decimal', "<?= 'Validation.items.suppliers.tax_decimal' ?>")

	if(!validator.validate())
		return

	axios.put(`api/items/${openItem.id}/supplier/${openSupplier.id}`, {
		part_number: $('input[name=edit_part_number]').val(),
		price: $('input[name=edit_price]').val(),
		tax: $('input[name=edit_tax]').val()
	}).then(response => {
		$('#editSupplierModal').modal('hide')

	})
}

// To delete a supplier relation
function deleteSupplierRelation(itemId, supplierId) {
	showConfirmation('<?= 'delete_relation_confirmation.title' ?>',
		'<?= 'delete_relation_confirmation.msg' ?>',
		'<?= 'delete_relation_confirmation.yes' ?>',
		'<?= 'delete_relation_confirmation.no' ?>',
		() => {
			deleteSupplierRelationSubmit(itemId, supplierId)
			return true // True to close
		},
		() => {
			return true // True to close
		}
	)
}

function deleteSupplierRelationSubmit(itemId, supplierId) {
	axios.delete(`api/items/${itemId}/supplier/${supplierId}`).then(response => {
		loadItem(itemId)
	})
}

function deleteItem() {
	showConfirmation('<?= 'delete_confirmation.title' ?>',
		'<?= 'delete_confirmation.msg' ?>',
		'<?= 'delete_confirmation.yes' ?>',
		'<?= 'delete_confirmation.no' ?>',
		() => {
			deleteItemSubmit()
			return true // True to close
		},
		() => {
			return true // True to close
		}
	)
}

function deleteItemSubmit() {
	axios.delete(`api/items/${openItem.id}`).then(response => {
		$('#itemModal').modal('hide')
		table.ajax.reload()

	})
}
</script>
