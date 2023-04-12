<?php
	$this->load->view('templates/master')
	// $this->section('content')
	// $this->load->view('components/error_modal'); 
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
	font-size: 1rem;
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
				<div class="section">
					<div class="header">
						New vendor
					</div>

					<div class="content-item">
							<form  action="<?= base_url('inventory/Backend/Vendors/create') ?>" method="post" id="add_new_vendor" name="addnewvendor" enctype="multipart/form-data" >
							<div class="row mt-0">
								<!-- Left -->
								<div class="col-md-6 text-break pl-2 pr-2">
									<h6 class="h6-5 text-secondary mb-3">
										Basic information
									</h6>

									<div class="form-group">
										<label for="vendor_name" class="d-block">Name*</label>
										<input type="text" name="vendor_name" id="vendor_name" class="form-control" />
									</div>

									<div class="form-group">
										<label for="internal_name" class="d-block">Internal name</label>
										<input type="text" name="internal_name" id="internal_name" class="form-control" />
									</div>

									<div class="form-group">
										<label for="company_name" class="d-block">Company name</label>
										<input type="text" name="company_name" id="company_name" class="form-control" />
									</div>

									<div class="form-group">
										<label for="vat_number" class="d-block">VAT number</label>
										<input type="text" name="vat_number" id="vat_number" class="form-control" />
									</div>

									<div class="form-group">
										<label for="vendor_email_address" class="d-block">Email address</label>
										<input type="text" name="vendor_email_address" id="vendor_email_address" class="form-control" />
									</div>

									<div class="form-group">
										<label for="vendor_phone_number" class="d-block">Phone number</label>
										<input type="text" name="vendor_phone_number" id="vendor_phone_number" class="form-control" />
									</div>

									<hr class="mt-4 mb-4" />

									<h6 class="h6-5 text-secondary mb-3">
										Physical information
									</h6>

									<div class="form-group">
										<label for="vendor_street_address" class="d-block">Address</label>
										<input type="text" name="vendor_street_address" id="vendor_street_address" class="form-control" />
									</div>

									<div class="form-row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="vendor_city" class="d-block">City</label>
												<input type="text" id="vendor_city" name="vendor_city" class="form-control" />
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="vendor_state" class="d-block">State</label>
												<input type="text" id="vendor_state" name="vendor_state" class="form-control" />
											</div>
										</div>
									</div>

									<div class="form-row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="vendor_zip_code" class="d-block">Zip code</label>
												<input type="text" id="vendor_zip_code" name="vendor_zip_code" class="form-control" />
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="vendor_country" class="d-block">Country</label>
												<input type="text" id="vendor_country" name="vendor_country" class="form-control" />
											</div>
										</div>
									</div>
								</div>

								<!-- Separator -->
								<div class="columns-separator"></div>

								<!-- Right -->
								<div class="col-md-6 text-break pl-2 pr-2">
									<h6 class="h6-5 text-secondary mb-3">
										Custom information
									</h6>

									<div class="form-group">
										<label for="custom_field1" class="d-block">Custom field 1</label>
										<input type="text" id="custom_field1" name="custom_field1" class="form-control" />
									</div>

									<div class="form-group">
										<label for="custom_field2" class="d-block">Custom field 2</label>
										<input type="text" id="custom_field2" name="custom_field2" class="form-control" />
									</div>

									<div class="form-group">
										<label for="custom_field3" class="d-block">Custom field 3</label>
										<input type="text" id="custom_field3" name="custom_field3" class="form-control" />
									</div>

									<hr class="mt-4 mb-4" />

									<h6 class="h6-5 text-secondary mb-3">
										Notes
									</h6>

									<div class="form-group">
										<label for="notes" class="d-block">Notes</label>
										<textarea id="notes" name="notes" rows="5" wrap="soft" class="form-control"></textarea>
									</div>

								</div>
							</div>

							<hr class="mt-4" />

							<div class="text-right mt-2 mb-2">
								<button type="submit" class="btn px-3 btn-outline-primary">
									Create Vendor
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
	
	$('document').ready(function() {
		$('.main-loader').fadeOut(100)

		// $('form').on('submit', e => {
		// 	e.preventDefault()
		// })
	})
})(jQuery)

</script>