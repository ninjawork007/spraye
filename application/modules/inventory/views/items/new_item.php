<?php
$this->load->view('templates/master') 
//$this->load->view('components/error_modal'); 
?>
<style>
	.section:not(.variant-1):not(.variant-2) .header {
    padding: 14px 18px;
    border-bottom: 1px solid #F5F5F5;
    font-size: 20px;
    font-weight: 700;
    color: #727272;
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
	.form-group > label, .form-group > legend {
	font-size: 16px;
	color: #666666;
	font-weight: 500;
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
	font-size: 14px;
	font-weight: 400;
	line-height: 1.5;
	color: #495057;
	text-align: center;
	white-space: nowrap;
	background-color: #e9ecef;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	}

</style>

<div class="content invoicessss">
	<div class="panel-body">
		<div class="row">
			<div class="px-2 mt-n1 col">
				<div class="section">
					<div class="header">
						New item
					</div>

					<div class="content-item">
					<form  action="<?= base_url('inventory/Backend/Items/create') ?>" method="post" id="add_item_type" name="additem" enctype="multipart/form-data" >
							<div class="row mt-0">
								<!-- Left -->
								<div class="column text-break pl-2 pr-2">
									<h6 class="h6-5 text-secondary mb-3">
										Basic information
									</h6>

									<div class="form-group">
										<label for="name" class="d-block">Item name*</label>
										<input type="text" name="item_name" id="item_name" class="form-control" />
									</div>

									<!-- <div class="form-group">
										<label for="code_type" class="d-block">Barcode type</label>
										<select name="code_type" id="code_type" class="custom-select">
											<option value="none">None </option>
											<option value="code39">Code39</option>
											<option value="code128">Code128</option>
											<option value="ean-8">EAN-8</option>
											<option value="ean-13">EAN-13</option>
											<option value="upc-a">UPC-A</option>
											<option value="qr">QR</option>
										</select>

										<small class="form-text text-muted">
										If you select a barcode type, the item code will have to meet the barcode's requirements
										</small>
									</div> -->

									<div class="form-group">
										<label for="item_number" class="d-block">Item #*</label>
										<!-- <div class="input-group"> -->
											<input type="text" id="item_number" name="item_number" class="form-control" />
											<!-- <div class="input-group-append">
												<button type="button" onclick="generateCode()" class="btn btn-primary">
													<i class="fas fa-sync-alt"></i>
												</button>
											</div> -->
											<!-- </div> -->
											
											<small class="form-text text-muted">
											Can be any combination of letters and numbers can have symbols.
											</small>
									</div>

									<div class="form-group">
										<label for="brand" class="d-block">Brand</label>
										<select name="brand_id" id="brand_id" class="custom-select">
											<option value="">none</option>
											<?php foreach($brands as $brand) { ?>
											<option value="<?= $brand->brand_id ?>"><?= $brand->brand_name ?></option>
											<?php } ?>
										</select>
									</div>

									<div class="form-group">
										<label for="item_type" class="d-block">Item Types</label>
										<select name="item_type_id" id="item_type_id" class="custom-select">
											<option value="">none</option>
											<?php foreach($item_types as $itemType) { ?>
											<option value="<?= $itemType->item_type_id ?>"><?= $itemType->item_type_name ?></option>
											<?php } ?>
										</select>
									</div>

									<!-- <div class="form-row">
										<div class="column">
											<div class="form-group">
												<label for="sale_price" class="d-block">Sale price*</label>
												<input type="text" id="sale_price" name="sale_price" class="form-control" />
											</div>
										</div>

										<div class="column">
											<div class="form-group">
												<label for="sale_tax" class="d-block">Sale tax (%)</label>
												<input type="text" id="sale_tax" name="sale_tax" class="form-control" value="0" />
											</div>
										</div>
									</div> -->

									<div class="form-group">
										<label for="item_description" class="d-block">Description</label>
										<textarea id="item_description" name="item_description" rows="5" wrap="soft" class="form-control"></textarea>
									</div>
								</div>

								<!-- Separator -->
								<div class="columns-separator"></div>

								<!-- Right -->
								<div class="column text-break pl-2 pr-2">
									<!-- <h6 class="h6-5 text-secondary mb-3">
										Dimensions
									</h6> -->

									<!-- <div class="form-row">
										<div class="column">
											<div class="form-group">
												<label for="weight" class="d-block">Weight (kg)</label>
												<input type="text" id="weight" name="weight" class="form-control" />
											</div>
										</div>

										<div class="column">
											<div class="form-group">
												<label for="width" class="d-block">Width (m)</label>
												<input type="text" id="width" name="width" class="form-control" />
											</div>
										</div>
									</div>

									<div class="form-row">
										<div class="column">
											<div class="form-group">
												<label for="height" class="d-block">Height (m)</label>
												<input type="text" id="height" name="height" class="form-control" />
											</div>
										</div>

										<div class="column">
											<div class="form-group">
												<label for="depth" class="d-block">Depth (m)</label>
												<input type="text" id="depth" name="depth" class="form-control" />
											</div>
										</div>
									</div> -->

									<!-- <hr class="mt-3 mb-4" /> -->

									<h6 class="h6-5 text-secondary mb-3">
										Alerts
									</h6>

									<div class="form-row">
										<div class="column">
											<div class="form-group">
												<label for="min_alert" class="d-block">Minimum quantity alert</label>
												<input type="text" id="min_alert" name="min_alert" class="form-control" />
												<small class="form-text text-muted">
												If you set this value, you'll receive an alert when the item stock quantity in any of your warehouses reaches this number.
												</small>
											</div>
										</div>

										<div class="column">
											<div class="form-group">
												<label for="max_alert" class="d-block">Maximum quantity alert</label>
												<input type="text" id="max_alert" name="max_alert" class="form-control" />
												<small class="form-text text-muted">
												If you set this value, you'll receive an alert when the item stock quantity in any of your warehouses reaches this number.
												</small>
											</div>
										</div>
									</div>

									<hr class="mt-3 mb-4" />

									<!-- <h6 class="h6-5 text-secondary mb-3">
										Notes
									</h6> -->

									<div class="form-group">
										<label for="notes" class="d-block">Notes</label>
										<textarea id="notes" name="notes" rows="5" wrap="soft" class="form-control"></textarea>
									</div>

								</div>
							</div>

							<hr class="mt-4" />

							<div class="text-right mt-2 mb-2">
								<button type="submit" class="btn px-3 btn-outline-primary"  >
									Create Item
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

(function($) {
	'use strict';
	
	let code_requirements = [
		{ type: 'none', text: "code_help.none" },
		{ type: 'code39', text: "code_help.code39" },
		{ type: 'code128', text: "code_help.code128" },
		{ type: 'ean-8', text: "code_help.ean8" },
		{ type: 'ean-13', text: "code_help.ean13" },
		{ type: 'upc-a', text: "code_help.upca" },
		{ type: 'qr', text: "code_help.qr" }
	]

	$('document').ready(function() {
		$('.main-loader').fadeOut(100)

		$('select[name=code_type]').on('change', e => {
			let val = $(e.currentTarget).val()

			code_requirements.forEach(requirement => {
				if(requirement.type == val)
					$('input[name=code]').parent().parent().children('.text-muted').text(requirement.text)
			})
		})

		// Prevent form submission when hitting enter in the "code" input
		$('input[name=code]').on('keypress', e => {
			if(e.which == 13)
				e.preventDefault()
		})

		$('form').on('submit', e => {
			e.preventDefault()
		})
			
	})
})(jQuery)

function generateCode() {
	let codeType = $('select[name=code_type]').val()

	axios.get(`inventory/controllers/backend/items/generate-code/${codeType}`).then(response => {
		$('input[name=code]').val(response.data.code)
	})
}
</script>
