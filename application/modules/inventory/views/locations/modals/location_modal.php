<!-- Start of item modal -->
<div class="modal fade" id="warehouseModal">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<header class="modal-header">
				<h5><?= lang('Main.warehouses.warehouse') ?></h5>

				<div>
					<?php if($logged_user->role == 'admin') { ?>
					<button type="button" onclick="editWarehouse()" class="btn mr-2 btn-outline-primary btn-sm">
						Edit
					</button>
					<button type="button" onclick="deleteWarehouse()" class="btn btn-outline-danger btn-sm">
						Delete
					</button>
					<?php } ?>
				</div>
			</header>

			<div class="modal-body">
				<div class="row mt-0">
					<div class="col-sm text-break pl-2 pr-2">
						<strong class="d-block pb-2">Basic Information</strong>
						<table id="warehouseBasicInformation" class="table stacked">
							<tbody>
								<tr>
									<th width="40"><?= location.id ?></th>
									<td width="60" data-item-field="id"></td>
								</tr>
								<tr>
									<th width="40"><?= name ?></th>
									<td width="60" data-item-field="name"></td>
								</tr>
								<tr>
									<th width="40"><?= phone_number ?></th>
									<td width="60" data-item-field="phone_number"></td>
								</tr>
								<tr>
									<th width="40"><?= created_by ?></th>
									<td width="60" data-item-field="created_by"></td>
								</tr>
								<tr>
									<th width="40"><?= created_at ?></th>
									<td width="60" data-item-field="created_at"></td>
								</tr>
							</tbody>
						</table>

						<hr class="mb-4" />

						<strong class="d-block pb-2"><?= Physical Information ?></strong>
						<table id="warehousePhysicalInformation" class="table stacked">
							<tbody>
								<tr>
									<th width="40"><?= address ?></th>
									<td width="60" data-item-field="address"></td>
								</tr>
								<tr>
									<th width="40"><?= city ?></th>
									<td width="60" data-item-field="city"></td>
								</tr>
								<tr>
									<th width="40"><?= state ?></th>
									<td width="60" data-item-field="state"></td>
								</tr>
								<tr>
									<th width="40"><?= zip_code ?></th>
									<td width="60" data-item-field="zip_code"></td>
								</tr>
								<tr>
									<th width="40"><?= country ?></th>
									<td width="60" data-item-field="country"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End of item modal -->