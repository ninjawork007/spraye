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
	.table-responsive {
		overflow-x: auto;
		/* min-height: .01%; */
		min-height: 100px;
	}

</style>

<div class="content invoicessss">
	<div class="panel-body">
		<div class="row">
			<div class="px-2 mt-n1 col">
				<div class="section">
					<div class="header">
						Update location
					</div>

					<div class="content-item">
							<form  action="<?= base_url('inventory/Backend/Locations/updateLocation') ?>" method="post" id="add_new_location" name="addnewlocation" enctype="multipart/form-data" >
						<fieldset class="content-group">
							<input type="hidden" name="location_id" class="form-control" value="<?= $location->location_id; ?>">
							<div class="row mt-0">
								<!-- Left -->
								<div class="col-md-6 text-break pl-2 pr-2">
									<h6 class="h6-5 text-secondary mb-3">
										Basic information
									</h6>

									<div class="form-group">
										<label for="location_name" class="d-block">Name*</label>
										<input type="text" name="location_name" id="location_name" value="<?php echo set_value('location_name') ? set_value('location_name') : $location->location_name ?>" class="form-control" />
									</div>
								</div>

								<!-- Separator -->
								<div class="columns-separator"></div>

								<!-- Right -->
								<div class="col-md-6 text-break pl-2 pr-2">
									<h6 class="h6-5 text-secondary mb-3">
										Physical information
									</h6>

									<div class="form-group">
										<label for="location_street" class="d-block">Address</label>
										<input type="text" name="location_street" id="location_street" value="<?php echo set_value('location_street') ? set_value('location_street') : $location->location_street ?>" class="form-control" />
									</div>

									<div class="form-row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="location_city" class="d-block">City</label>
												<input type="text" id="location_city" name="location_city" value="<?php echo set_value('location_city') ? set_value('location_city') : $location->location_city ?>"  class="form-control" />
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="location_state" class="d-block">State</label>
												<input type="text" id="location_state" value="<?php echo set_value('location_state') ? set_value('location_state') : $location->location_state ?>"  name="location_state" class="form-control" />
											</div>
										</div>
									</div>

									<div class="form-row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="location_zip" class="d-block">Zip code</label>
												<input type="text" id="location_zip" name="location_zip" value="<?php echo set_value('location_zip') ? set_value('location_zip') : $location->location_zip ?>"  class="form-control" />
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="location_country" class="d-block">Country</label>
												<input type="text" id="location_country" name="location_country" value="<?php echo set_value('location_country') ? set_value('location_country') : $location->location_country ?>"  class="form-control" />
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="location_phone" class="d-block">Phone number</label>
										<input type="text" name="location_phone" id="location_phone" value="<?php echo set_value('location_phone') ? set_value('location_phone') : $location->location_phone ?>" class="form-control" />
									</div>
								</div>
							</div>
							<hr class="mt-4" />
							<!-- <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;"> -->
					
						<h6 class="modal-title">Sub-Location</h6>
					<!-- </div> -->
					<input type="hidden" name ="sub_location_name_id" id="subid" value = "">
					<input type="hidden" name ="total_inventory_value" id="total_inventory_value" value = "0">
					<input type="hidden" name ="sub_location_fleet_no" id="sub_location_fleet_no" value = "">
					<div class="form-group">
						<div class="row">
							<div class="col-md-6 col-sm-4">
								
								<input type="text" class="form-control" name="sub_location_name" id="sub_location_name" value=""
									placeholder="Enter Sub Location Name">
							</div>
							<div class="col-md-6 col-sm-4">
								<div class="modal-sub-button">
									<button type="button" onclick="subUpdate()" class="btn btn-primary " >
										Add Sub Location
									</button>
					</div>
							</div>
						</div>
					</div>
				
					<div class="content">
						<div class="table-responsive">
							<table id="sub_location" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>

										<th>Sub Name</th>
										<th>Inventory</th>
										<th>Fleet Number</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id='sublocation'>
									<?php
										if($sub_locations){
											foreach($sub_locations as $sub){
									?>
									
									<tr>
										<td id="sub_name" name ="sub_location_name_id[]" value="<?= $sub->sub_location_name ?>" ><?= $sub->sub_location_name ?></td>
										<td id="inventory" value="<?= $sub->total_inventory_value ?>" ><?= $sub->total_inventory_value ?></td>
										<td id="fleet" value="<?= $sub->sub_location_fleet_no ?>" ><?= $sub->sub_location_fleet_no ?></td>
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
									<?php
										}
									} 
									?>
								</tbody>
							</table>
						</div>
					</div>

							<hr class="mt-4" />

							<div class="text-right mt-2 mb-2">
								<button type="submit" class="btn px-3 btn-outline-primary ">
									Create location
								</button>
							</div>
							
							</fieldset>
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

// function createWarehouse() {
// 	axios.post('api/warehouses', {
// 		name: $('input[name=name]').val(),
// 		address: $('input[name=address]').val(),
// 		city: $('input[name=city]').val(),
// 		state: $('input[name=state]').val(),
// 		zip_code: $('input[name=zip_code]').val(),
// 		country: $('input[name=country]').val(),
// 		phone_number: $('input[name=phone_number]').val()
// 	}).then(response => {
// 		if(response && response.data && response.data.id)
// 			location.href = `<?= base_url() ?>/warehouses/${response.data.id}`

// 	})
// }

function subUpdate() {
    if ($("#sub_location_name").val() != null && $("#sub_location_name").val() != '') {
        // Add product to Table
        addSubLocation();

        // Clear form fields
        formClear();

        // Focus to product name field
        $("#sub_location_name").focus();
    }
}

function addSubLocation(){
	// First check if a <tbody> tag exists, add one if not
    if ($("#sub_location tbody").length == 0) {
        $("#sub_location").append("<tbody></tbody>");
    }

    // Append sub location to the table
    $("#sub_location tbody").append("<tr>" +
        "<td>" + $("#sub_location_name").val() + "</td>" +
        "<td>" + $("#total_inventory_value").val() + "</td>" +
        "<td>" + $("#sub_location_fleet_no").val() + "</td>" +
		"<td>" +
        +
        "</td>" +
        "</tr>");
}

function formClear() {
    $("#sub_location_name").val("");
    $("#total_inventory_value").val("");
    $("#sub_location_fleet_no").val("");
}
function subDelete(ctl) {
    $(ctl).parents("tr").remove();
}
</script>