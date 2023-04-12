 <?php
// $this->load->view('templates/master');
// $this->load->view('brands/modals/brand_modal');
// $this->load->view('brands/modals/edit_brand_modal');
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
		<!-- Start of Brand -->
		<div class="row">
			<div class="px-2 py-1 col">
				<div class="section variant-2">
					<div class="header d-flex align-items-center justify-content-between">
						<div class="title">
							Brands
						</div>

						<div class="buttons d-flex">
							
							<a href="<?= base_url('api/brands/export') ?>" class="btn px-3 btn-outline-primary mr-2">
								Export CSV
							</a>
							

							<a href="<?= base_url('inventory/Frontend/brands/new') ?>" class="btn px-3 btn-outline-primary ">
								New Brand
							</a>
						</div>
					</div>

					<div class="content">
						<div class="table-responsive">
							<table id="brands" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Brand Name</th>
										<th>Brand Description</th>
										<th>Created By</th>
										<th>Created At</th>
										<th>Items registered</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
										if($all_brands){
											foreach($all_brands as $brand){
									?>
									<tr>
										<td><?= $brand->brand_name ?></td>
										<td><?= $brand->brand_description ?></td>
										<td><?= $brand->created_by ?></td>
										<td><?= $brand->created_at ?></td>
										<td><?= $brand->items_registered ?></td>
										<td 
											class="table-action" 
											width="10%" 
											>
											<ul style="list-style-type: none; padding-left: 0px;" >
												<li style="display: inline; padding-right: 10px;" >
													<a href="#" data-toggle="modal"  data-target="#editBrandModal" data-id="<?= $brand->brand_id ?>" data-brand="<?= $brand->brand_name ?>" data-description="<?= $brand->brand_description ?>" data-created="<?= $brand->created_by ?>" data-date="<?= $brand->created_at ?>" class="button-next"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
												</li>
											<!-- <ul style="list-style-type: none; padding-left: 0px;" >
												<li style="display: inline; padding-right: 10px;" >
													<a href="<?= base_url("inventory/Frontend/Brands/editBrand/").$brand->brand_id ?>" class="button-next"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
												</li> -->
                  
												<li style="display: inline; padding-right: 10px;">
													<a href="<?=base_url("inventory/Backend/Brands/deleteBrand/").$brand->brand_id ?>"
													class="confirm_delete button-next" title="Delete"><i class="icon-trash   position-center"
														style="color: #9a9797;" ></i></a>
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
				</div>
			</div>
		</div>
		<!-- End of Brand -->
	</div>
</div>
<!-- Start of item modal -->
<div class="modal fade" id="editBrandModal">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title">Update Brand</h6>
			</div>
			
			<form  id="location_form"  method="post" 
                    enctype="multipart/form-data" form_ajax="ajax">
				<div class="modal-body">
				<input type="hidden" name ="brand_id" id="id" value = "">
					<div class="form-group">
						
						<div class="row">
							<div class="col-md-6 col-sm-4">
								<label>Brand Name</label>
								<input type="text" class="form-control" name="brand_name" id="brand_name" value = ""
									placeholder="Enter Brand">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-12 col-sm-4">
								<label for="brand_description" class="d-block">Description</label>
									<textarea id="brand_description" name="brand_description" rows="5" class="form-control"></textarea>
							</div>
							
						</div>
					</div>
					<!-- <div class="form-group">
						<div class="row">
							<div class="col-md-6 col-sm-4">
								<label>Created By</label>
								<input type="text" class="form-control" name="created_by" id="by"
									placeholder="Enter Creator">
							</div>
							<div class="col-md-6 col-sm-4">
								<label>Created At</label>
								<input type="text" class="form-control" name="created_at" id="date" value = ""
									placeholder="Enter Created Date">
							</div>
						</div>
					</div> -->
				
					<hr  />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" id="assignjob" class="btn btn-success">Save</button>
				</div>
			</form>	
		</div>
	</div>
</div>
<!-- End of item modal -->


<script type="text/javascript">
'use strict';

let openBrand = {};
let table = {};

(function($) {
	'use strict';

	$('document').ready(function() {
		$('.main-loader').fadeOut(100)

		// Link table to the loader
		$('table#brands').on('processing.dt', (e, settings, processing) => {
			if(processing)
				$('.main-loader').fadeIn(100)
			else
				$('.main-loader').fadeOut(100)
		})

		// Load table
		// table = $('table#brands').DataTable({
		// 	serverSide: true,
		// 	ajax: "<?= base_url('api/brands') ?>",
		// 	columns: [
		// 		{ data: "name" },
		// 		{ data: "created_by_name" },
		// 		{ data: "created_at" },
		// 		{ data: "items" }
		// 	]
		// })

		// $('table#brands tbody').on('click', 'tr', function() {
		// 	let id = table.row(this).data().DT_RowId
		// 	loadBrand(id)
		// })

		// $('#brandModal').on('hide.bs.modal', e => {
		// 	window.history.pushState(null, '', `<?= base_url() ?>/brands`)
		// })

		// $('#editBrandModal').on('hide.bs.modal', e => {
		// 	loadBrand(openBrand.id)
		// })

		// $('#editBrandModal').on('show.bs.modal', e => {
		// 	$('#brandModal').modal('hide')
		// })

		
		
		// $('#editBrandModal form').on('submit', e => {
		// 	e.preventDefault()
		// 	editBrandSubmit()
		// })
	})
})(jQuery)

// $("#editBrandBtn").click(function(){
//     $("#editBrandModal").modal();
// });
$(function() {
  $('#editBrandModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
	// Location inputs
    var id = button.data('id'); // Extract info from data-* attributes
	var brandName = button.data('brand');
    var brandDescription = button.data('description');
    // var createBy = button.data('created');
    // var createAt = button.data('date');

    var modal = $(this);
    modal.find('#id').val(id);
    modal.find('#brand_name').val(brandName);
    modal.find('#brand_description').val(brandDescription);
    // modal.find('#by').val(createBy);
    // modal.find('#date').val(createAt);
	// console.log(id);
  });
});

function loadBrand(id) {
	axios.get(`api/brands/${id}`).then(response => {
		let brand = response.data

		openBrand = brand

		window.history.pushState(null, '', `<?= base_url() ?>/brands/${id}`)

		$('#brandModal').modal('show')

		$('#brandModal td[data-item-field=id]').text(brand.id)
		$('#brandModal td[data-item-field=name]').text(brand.name)
		$('#brandModal td[data-item-field=description]').text(brand.description)
		$('#brandModal td[data-item-field=created_by]').text(brand.created_by.name)
		$('#brandModal td[data-item-field=created_at]').text(brand.created_at)
	})
}

// function editBrand() {
// 	$('#editBrandModal input[name=name]').val(openBrand.name)
// 	$('#editBrandModal textarea[name=description]').val(openBrand.description)
	
// 	$('#editBrandModal').modal('show')
// }



$('.confirm_delete').click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');
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
   window.location = url;
  }
})


});

</script>