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
							<a href="" data-toggle="modal" data-target="#add_invoice"><button data-pnum="<?= $new_purchase[0]->purchase_order_number ?>" data-podate="<?= $new_purchase[0]->created_at ?>" type="button"  class="btn btn-primary add-invoice modal_trigger_purchase_order" id="addinvoicebtn">View/Add Invoice </button></a>
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

								<!-- Right -->
								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="estimated_delivery_date" class="d-block">Estimated Delivery Date</label>
											<input type="date" id="estimated_delivery_date" name="estimated_delivery_date" class="form-control" value="<?= $new_purchase[0]->estimated_delivery_date ?>" />
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="created_date" class="d-block">Created Date</label>
										<input type="date" id="created_date" name="created_date" class="form-control" />
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="ordered_date" class="d-block">Ordered Date</label>
										<input type="date" id="ordered_date" name="ordered_date" class="form-control" value="<?= $new_purchase[0]->ordered_date ?>"/>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="expected_date" class="d-block">Expected Date</label>
										<input type="date" id="expected_date" name="expected_date" class="form-control" value="<?= $new_purchase[0]->expected_date ?>"/>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="unit_measrement" class="d-block">Unit of Measure</label>
										<input type="text" id="unit_measrement" name="unit_measrement" class="form-control" value="<?= $new_purchase[0]->unit_measrement ?>"/>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="shipping_point" class="d-block">Shipping Point</label>
										<input type="text" id="shipping_point" name="shipping_point" class="form-control" value="<?= $new_purchase[0]->shipping_point ?>"/>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="destination" class="d-block">Destination</label>
										<input type="text" id="destination" name="destination" class="form-control" value="<?= $new_purchase[0]->destination ?>"/>
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
										<label for="place_of_origin" class="d-block">Place of Origin</label>
										<input type="text" id="place_of_origin" name="place_of_origin" class="form-control" value="<?= $new_purchase[0]->place_of_origin ?>"/>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="place_of_destination" class="d-block">Place of Destination</label>
										<input type="text" id="place_of_destination" name="place_of_destination" class="form-control" value="<?= $new_purchase[0]->place_of_destination ?>"/>
									</div>
								</div>

								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="place_of_destination" class="d-block">Paid File</label>
										<?php
										if($new_purchase[0]->paid_attachment != ""){
										?>
											<a href="<?php echo base_url() ?>uploads/po_attachments/<?= $new_purchase[0]->paid_attachment ?>">View</a>
										<?php
										}else{
											echo "No file uploaded yet";
										}
										?>
									</div>
								</div>

							</div>
							<div class="row mt-n3">
								<!-- Left -->
								<div class="column text-break pl-2 pr-2" id="hidden_sub">
									<div class="form-group sublocation-container">
										<label for="sub_location" class="d-block">Sub Location*</label>
										<select name="sub_location" id="sub_location" class="custom-select">
											<option value="" ></option>
										</select>
									</div>
								</div>
								
								<!-- Separator -->
								<div class="columns-separator"></div>

								<!-- Right -->
								<div class="column text-break pl-2 pr-2">
									<div class="form-group">
										<label for="vendor" class="d-block">Vendor*</label>
										<select name="vendor" id="vendor" class="custom-select">
											<option value="<?= $new_purchase[0]->vendor_id ?>" disabled selected><?= $new_purchase[0]->vendor_name ?></option>
										</select>
										<div class="invalid-feedback"></div>
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
													<th>Qty Received</th>
													<th>Amount Received</th>
													<th>Total</th>
													<th>Qty Returned</th>
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
											<tr>
												<th width="40" class="font-weight-bold">Total Received</th>
												<td width="60" data-summary-field="total_received" class="font-weight-bold"><?= $settings->currency_symbol ?> 0.00 </td>
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

							<div class="text-right mt-2 mb-2">
								<button type="submit" class="btn btn-primary  ">
									Update PO
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

<!-- Start of purchase order invoice modal -->
	<div class="modal fade" id="add_invoice">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h6 class="modal-title">Purchase Order #</h6>
				</div>
					
				<form id="po_invoice_form"  name='addpoinvoice' method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-4">
									<label>Invoice #</label>
									<input type="text" class="form-control" name="invoice_number" id="invoice_number" value = "" placeholder="Invoice #" >
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Total Amount of Invoice</label>
									<input type="text" class="form-control" name="invoice_total_amt" id="invoice_total_amt" value = "" placeholder="Sub Total Amount of Invoice" >
								</div>
								<div class="col-md-4 col-sm-4">
									<label>Pay By Date</label>
									<input type="date" class="form-control" name="pay_by_date" id="pay_by_date" required >
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
						<button type="submit" id="po_invoice" class="btn btn-primary">Save</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
<!-- End of purchse order invoice modal -->

<script type="text/javascript">
// 'use strict';
	
let currency = "<?= addslashes($settings->currency_symbol) ?>";
let invoicesAdded = [];
let selectedLocation = 0;
let selectedSubLocation = 0;
let selectedVendor = 0;


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
		$('select[name=location], select[name=sub_location], select[name=vendor]').on('change', e => {
			let location = $('select[name=location]').val()
			let sublocation = $('select[name=sub_location]').val()
			let vendor = $('select[name=vendor]').val()

			getVendorDetails();
			
			if(location != '' && location != null && sublocation != '' && sublocation != null && vendor != '' && vendor != null) {
				selectedLocation = location
				selectedSubLocation = sublocation
				selectedVendor = vendor
				$('select[name=location]').prop('disabled', true)
				$('select[name=sub_location]').prop('disabled', true)
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

		// When changing price of an item
		$(document).on('input', '.itemPrice', function() {
			var qty = $(this).val();
			updateTotals();
		})

		// When changing received quantity of an item
		$(document).on('input', '.receivedqty', function() {
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
		$('form#po_invoice_form').on('submit', e => {
			e.preventDefault()
			addInvoice();
		});

		receivedInvoice();
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

function addInvoice() {
	let data = {
		purchase_order_id : <?= $purchase_id ?>,
		invoice_id: $('input[name=invoice_number]').val(),
		invoice_total_amt: $('input[name=invoice_total_amt]').val(),
		pay_by_date: $('input[name=pay_by_date]').val(),
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
			
			$("#loading").css("display","none");
			swal(
				'Purchase Order!',
				'Invoiced Successfully ',
				'success'
				).then(function() {
				location.reload();
				});
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

function subLocation() {
	var location = $('select[name=location]').val()
	var url = '<?= base_url('inventory/Backend/Locations/subLocationlist') ?>';
    var request_method = "GET"; //get form GET/POST method
    
	$.ajax({
		type: request_method,
		url: url,
		data: {location: location},
		dataType:'JSON', 
		success: function(response){
			
			// put on console what server sent back...
			$('select#sub_location').empty()
			response.result.forEach(sublocation => {
			let elem = `<option value="${sublocation.sub_location_id}">`
				+ `${sublocation.sub_location_name}`
				+ '</option>'
			$('select#sub_location').append(elem)
			})
		}
	});
}

function receivedInvoice() {
	var purchase_order_id = <?= $purchase_id ?>;
	var url = '<?= base_url('inventory/Backend/Purchases/receivedInvoice/') ?>'+purchase_order_id;
  	var request_method = "GET"; //get form GET/POST method
	
	$.ajax({
		type: request_method,
		url: url,
		data: {purchase_order_id:purchase_order_id},
		dataType:'JSON', 
		success: function(res){
			inv_received = res.data;
			inv_received.forEach(inv => {
				baseUrl = '<?=base_url("inventory/Frontend/Purchases/deletePurchaseInvoice/") ?>'+inv.invoice_id;
			
				let td1 = inv.invoice_id
				let td2 = Number(inv.invoice_total_amt);
				let td3 = Number(inv.freight);
				let td4 = Number(inv.discount);
				let td5 = inv.tax;
				let td6 = ((td2 * td5)/100)+td2+td3-td4;
				let td7 = `<ul style="list-style-type: none; padding-left: 0px;"> <li style="display: inline; padding-right: 10px;"> <a href="" onclick="deletePOInvoice() class="confirm_delete_modal button-next" title="Delete"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></li></ul>`;
											
				//table#items
				let elem = `<tr data-item-id="${inv.po_invoice_id}">`
					+ `<td>${td1}</td>`
					+ `<td data-item-td="invoice_amt">${currency} ${td2.toFixed(2)}</td>`
					+ `<td data-item-td="freight">${currency} ${td3.toFixed(2)}</td>`
					+ `<td data-item-td="discount">${currency} ${td4.toFixed(2)}</td>`
					+ `<td data-item-td="tax">${td5}%</td>`
					+ `<td data-item-td="total">${currency} ${td6.toFixed(2)}</td>`
					+ '</tr>'

				$('table#invoice_received').append(elem)
			})
		}	
	})
}

function purchaseOrder(){
	var purchase_order_id = <?= $purchase_id ?>;
	var url = '<?= base_url('inventory/Backend/Purchases/purchaseOrder/') ?>'+purchase_order_id;
  	var request_method = "GET"; //get form GET/POST method
	let subtotal = 0;
	let ptotal = 0;
	let total_units = 0;
	let received_qty = 0;
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
			var subLocation = purchase.sub_location_name;
			var vendor = purchase.vendor_name;

			$('span[name=title]').html(purchase_order_number);
			$('#location option:selected').text(location);
			$('#sub_location option:selected').text(subLocation);
			$('#vendor option').html(vendor);
			$('input[name=freight]').val();
			$('table#items tbody').html('');

			purchase.items = JSON.parse(purchase.items);
			
			Object.values(purchase.items).forEach((item, i) => {
				
				if(item.received_qty == undefined){
					item.received_qty = 0;
				} else {
					item.received_qty = item.received_qty;
				}
				
				if(item.return_qty == undefined){
					item.return_qty = 0;
				} else {
					item.return_qty = item.return_qty;
				}
				total_units += Number(item.quantity);
				received_qty += Number(item.received_qty);
				
				let unit_price = item.unit_price;
				let received = item.received_qty;
				let returned = item.return_qty;
				let quantity = item.quantity;
				let td1 = '<div class="d-flex">'
					+ '<div>'
					+ `<strong>${item.name}</strong>`
					+ '<br />'
					+ item.item_number
					+ '</div>'
					+ '</div>'
				let td2 = `<input type="number" class="form-control form-control-sm itemPrice" name="itemPrice" step="any" min="0" value="`+unit_price+`" />`;
				let td3 = quantity 
				let td4 = received - returned
				let td5= 0
				let td6 = 0
				let td7 = returned

				let elem = `<tr data-item-id="${item.item_id}">`
					+ `<td>${td1}</td>`
					+ `<td data-item-td="unit_price">${td2}</td>`
					+ `<td data-item-td="quantity">${td3}</td>`
					+ `<td data-item-td="received_qty" >${td4}</td>`
					+ `<td data-item-td="received_amt">${td5}</td>`
					+ `<td data-item-td="total">$ ${td6}</td>`
					+ `<td data-item-td="return_qty"> ${td7}</td>`
					+ '</tr>'

				$('table#items').append(elem)

				let item_subtotal = received * Number(unit_price);

				$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="received_amt"]`).html(`${currency} ${parseFloat(item_subtotal).toFixed(2)}`);

				let qty_subtotal = quantity * Number(unit_price);
	
				$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="total"]`).html(`${currency} ${parseFloat(qty_subtotal).toFixed(2)}`);

				subtotal += item_subtotal;
				ptotal += qty_subtotal;
			})
			purchase.total_units = total_units;
			purchase.receiving_total = received_qty;

			let freight = $('input[name=freight]').val();
			let discount = $('input[name=discount]').val();
			let tax = $('input[name=tax]').val();
			freight = parseFloat(freight).toFixed(2);
			discount = parseFloat(discount).toFixed(2);
			tax = parseFloat(tax).toFixed(2);
			
			if(discount > subtotal){

				discount = subtotal;
			};

			let received_total = subtotal;
			received_total = parseFloat(received_total - discount).toFixed(2);
			received_total =(Number(received_total) + Number(freight));
			let tax_amount_received = Number(tax * received_total / 100);
			received_total = parseFloat(received_total  + tax_amount_received).toFixed(2);

			let grand_total = ptotal;
			grand_total = parseFloat(grand_total - discount).toFixed(2);
			grand_total =(Number(grand_total) + Number(freight));
			let tax_amount = Number(tax * grand_total / 100);
			grand_total = parseFloat(grand_total  + tax_amount).toFixed(2);

			$('table#summary tr td[data-summary-field="subtotal"]').html(`${currency} ${parseFloat(subtotal).toFixed(2)}`)
			$('table#summary tr td[data-summary-field="discount"]').html(`${currency} ${parseFloat(discount).toFixed(2)}`)
			$('table#summary tr td[data-summary-field="shipping"]').html(`${currency} ${parseFloat(freight).toFixed(2)}`)
			$('table#summary tr td[data-summary-field="tax"]').html(`${tax}%`)
			$('table#summary tr td[data-summary-field="total_received"]').html(`${currency} ${parseFloat(received_total).toFixed(2)}`)
			$('table#summary tr td[data-summary-field="total"]').html(`${currency} ${parseFloat(grand_total).toFixed(2)}`)
			
		}	
	})
}

function updateTotals() {
	let subtotal = 0;
	let ptotal = 0;
	let total_qty = 0;
	let receiving_total = 0;
	
	Object.values(purchase.items).forEach((item, i) => {
		let received_qty = $(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td=received_qty]`).html();
		let quantity = item.quantity;
		let item_price = $(`table#items tbody tr[data-item-id=${item.item_id}] .itemPrice`).val();
		console.log(item_price);

		// If quantity is greater than originally purchased, rewrite user input
		if(Number(received_qty) > Number(item.quantity)) {
			received_qty = item.quantity
			$(`table#items tbody tr[data-item-id=${item.item_id}] input`).val(received_qty);
		}
		// Update quantity in the original array
		purchase.items[i]['received_qty'] = received_qty;
		purchase.items[i]['unit_price'] = item_price;

		let item_subtotal = received_qty * Number(item_price);
		let item_total = Number(item_subtotal);
		let qty_subtotal = quantity * Number(item_price);
		let qty_total = Number(qty_subtotal);
		$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="received_amt"]`).html(`${currency} ${parseFloat(item_subtotal).toFixed(2)}`);
		$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="total"]`).html(`${currency} ${parseFloat(qty_total).toFixed(2)}`);

		subtotal += Number(item_total);
		ptotal += Number(qty_subtotal);
		total_qty += Number(item.quantity);
		receiving_total += Number(received_qty);
	});

	if(receiving_total == total_qty){
		purchase.purchase_order_status = 3;
		purchase['receiving_total'] = receiving_total;
	} else {
		purchase.purchase_order_status = 2;
		purchase['receiving_total'] = receiving_total;
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

	let received_total = subtotal;
	received_total = parseFloat(received_total - discount).toFixed(2);
	received_total =(Number(received_total) + Number(freight));
	let tax_amount_received = Number(tax * received_total / 100);
	received_total = parseFloat(received_total  + tax_amount_received).toFixed(2);

	let grand_total = ptotal;
	grand_total = parseFloat(grand_total - discount).toFixed(2);
	grand_total =(Number(grand_total) + Number(freight));
	let tax_amount = Number(tax * grand_total / 100);
	grand_total = parseFloat(grand_total  + tax_amount).toFixed(2);

	$('table#summary tr td[data-summary-field="subtotal"]').html(`${currency} ${parseFloat(subtotal).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="discount"]').html(`${currency} ${parseFloat(discount).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="shipping"]').html(`${currency} ${parseFloat(freight).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="tax"]').html(`${tax}%`)
	$('table#summary tr td[data-summary-field="total_received"]').html(`${currency} ${parseFloat(received_total).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="total"]').html(`${currency} ${parseFloat(grand_total).toFixed(2)}`)
}

function updateInvoices() {
	let subtotal = 0;

	invoicesAdded.forEach((item, i) => {
		let qty = $(`table#items tbody tr[data-item-id=${item.item_id}] input`).val();
		
		// Update quantity in the original array
		invoicesAdded[i].qty = qty;

		let item_subtotal = qty * Number(item.price_per_unit);
		let item_total = Number(item_subtotal);
		
		$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="subtotal"]`).html(`${currency} ${parseFloat(item_subtotal).toFixed(2)}`);
		$(`table#items tbody tr[data-item-id=${item.item_id}] td[data-item-td="total"]`).html(`${currency} ${parseFloat(item_total).toFixed(2)}`);
		
		subtotal += item_total;
	});

	let freight = $('input[name=freight]').val();
	let discount = $('input[name=discount]').val();
	let tax = $('input[name=tax]').val();
	freight = parseFloat(freight).toFixed(2);
	discount = parseFloat(discount).toFixed(2);
	tax = parseFloat(tax).toFixed(2);

	// Cap discount to order's subtotal
	if(discount > subtotal){
		discount = subtotal;
	};

	let grand_total = subtotal;
	grand_total = parseFloat(grand_total - discount).toFixed(2);
	grand_total =(Number(grand_total) + Number(freight));
	let tax_amount = tax * grand_total / 100;
	grand_total = parseFloat(grand_total  + tax_amount).toFixed(2);

	$('table#summary tr td[data-summary-field="subtotal"]').html(`${currency} ${parseFloat(subtotal).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="discount"]').html(`${currency} ${parseFloat(discount).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="shipping"]').html(`${currency} ${parseFloat(freight).toFixed(2)}`)
	$('table#summary tr td[data-summary-field="tax"]').html(`${tax}%`)
	$('table#summary tr td[data-summary-field="total"]').html(`${currency} ${parseFloat(grand_total).toFixed(2)}`)
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
		payment_terms: $('textarea[name=payment_terms]').val(),
		destination: $('input[name=destination]').val(),
		place_of_origin: $('input[name=place_of_origin]').val(),
		place_of_destination: $('input[name=place_of_destination]').val(),
		location_id: $('select[name=location]').val(),
		sub_location_id: $('select[name=sub_location]').val(),
		freight: $('input[name=freight]').val(),
		discount: $('input[name=discount]').val(),
		discount_type: 'amount',
		tax: $('input[name=tax]').val(),
		total_received_amount: $('table#summary tr td[data-summary-field="total_received"]').text(),
		notes: $('textarea[name=purchase_order_order_notes]').val(),
		status: purchase.purchase_order_status,
		total_received: purchase.receiving_total,
		total_units: purchase.total_units,
		items: []
	}
	
	Object.values(purchase.items).forEach(item => {
		data.items.push({
			item_id: item.item_id,
			item_number: item.item_number,
			name: item.name,
			received_qty: item.received_qty,
			return_qty: item.return_qty,
			unit_price: item.unit_price,
			unit_type: item.unit_type,
			quantity: item.quantity
		})
	})
	
	var url = '<?= base_url('inventory/Backend/Purchases/updatePO/') ?>'+purchase_order_id;
	var formData = data;
	$.ajax({
		type: 'POST',
		url: url,
		data: formData,
		success: function (data){

			$("#loading").css("display","none");
			swal(
				'Purchase Order!',
				'Updated Successfully ',
				'success'
				).then(function() {
				location.reload();
				});
			
		}
	});
}

function deletePOInvoice() {
   
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
		   url: "<?= base_url('')  ?>inventory/Frontend/Purchases/deletePOInvoice",
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

function getVendorDetails(){
	var vendor = $('select[name=vendor]').val()
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
		}
	});
}

</script>