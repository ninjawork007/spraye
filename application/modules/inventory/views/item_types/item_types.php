<?php
$this->load->view('templates/master');
// $this->load->view('categories/modals/category_modal');
// $this->load->view('categories/modals/edit_category_modal');
// $this->load->view('components/error_modal') 
// $this->load->view('components/confirmation_modal');

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
							Item Types
						</div>

						<div class="buttons d-flex">
							
							<a href="<?= base_url('api/categories/export') ?>" class="btn px-3 btn-outline-primary  mr-2">
								Export CSV
							</a>
							

							<a href="<?= base_url('inventory/Frontend/ItemTypes/new') ?>" class="btn px-3 btn-outline-primary ">
								New Item Types
							</a>
						</div>
					</div>

					<div class="content">
						<div class="table-responsive">
							<table id="categories" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Name</th>
										<th>Created By</th>
										<th>Created At</th>
										<th>Items registered</th>
									</tr>
								</thead>
								<tbody>
									<?php
										if($all_item_types){
											foreach($all_item_types as $itemType){
									?>
									<tr>
										<td><?= $itemType->item_type_name ?></td>
										<td><?= $itemType->created_by ?></td>
										<td><?= $itemType->created_at ?></td>
										<td><?= $itemType->item_type_description?></td>
										
										
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

<script type="text/javascript">
'use strict';

var openCategory = {};
var table = {};

(function($) {
	'use strict';

	$('document').ready(function() {
		$('.main-loader').fadeOut(100)

		// Link table to the loader
		$('table#categories').on('processing.dt', (e, settings, processing) => {
			if(processing)
				$('.main-loader').fadeIn(100)
			else
				$('.main-loader').fadeOut(100)
		})

		// Load table
		// table = $('table#categories').DataTable({
		// 	serverSide: true,
		// 	ajax: "<?= base_url('api/categories') ?>",
		// 	columns: [
		// 		{ data: "name" },
		// 		{ data: "created_by_name" },
		// 		{ data: "created_at" },
		// 		{ data: "items" }
		// 	]
		// })

		$('table#categories tbody').on('click', 'tr', function() {
			let id = table.row(this).data().DT_RowId
			loadCategory(id)
		})

		$('#categoryModal').on('hide.bs.modal', e => {
			window.history.pushState(null, '', `<?= base_url() ?>/categories`)
		})

		$('#editCategoryModal').on('hide.bs.modal', e => {
			loadCategory(openCategory.id)
		})

		$('#editCategoryModal').on('show.bs.modal', e => {
			$('#categoryModal').modal('hide')
		})

		
		
		$('#editCategoryModal form').on('submit', e => {
			e.preventDefault()
			editCategorySubmit()
		})
	})
})(jQuery)

function loadCategory(id) {
	axios.get(`api/categories/${id}`).then(response => {
		let category = response.data

		openCategory = category

		window.history.pushState(null, '', `<?= base_url() ?>/categories/${id}`)

		$('#categoryModal').modal('show')

		$('#categoryModal td[data-item-field=id]').text(category.id)
		$('#categoryModal td[data-item-field=name]').text(category.name)
		$('#categoryModal td[data-item-field=description]').text(category.description)
		$('#categoryModal td[data-item-field=created_by]').text(category.created_by.name)
		$('#categoryModal td[data-item-field=created_at]').text(category.created_at)
	})
}

function editCategory() {
	$('#editCategoryModal input[name=name]').val(openCategory.name)
	$('#editCategoryModal textarea[name=description]').val(openCategory.description)
	
	$('#editCategoryModal').modal('show')
}

function editCategorySubmit() {
	let validator = new Validator()
	validator.addInputTextVal('name', 'minLength', 1, "<?= 'categories.name_min_length' ?>")
	validator.addInputTextVal('name', 'maxLength', 100, "<?= 'categories.name_max_length' ?>")

	if(!validator.validate())
		return

	axios.put(`api/categories/${openCategory.id}`, {
		name: $('input[name=name]').val(),
		description: $('textarea[name=description]').val()
	}).then(response => {
		$('#editCategoryModal').modal('hide')
		table.ajax.reload()
		
	})
}

function deleteCategory() {
	showConfirmation('<?= 'delete_confirmation.title' ?>',
		'<?= 'delete_confirmation.msg' ?>',
		'<?= 'delete_confirmation.yes' ?>',
		'<?= 'delete_confirmation.no' ?>',
		() => {
			deleteCategorySubmit()
			return true
		},
		() => {
			return true
		})
}

function deleteCategorySubmit() {
	axios.delete(`api/categories/${openCategory.id}`).then(response => {
		$('#categoryModal').modal('hide')
		table.ajax.reload()
		
	})
}
</script>