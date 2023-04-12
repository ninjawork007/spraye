<?php
$this->load->view('templates/master');
// $this->load->view('components/error_modal') 
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
		<!-- Start of Warehouses -->
		<div class="row">
			<div class="px-2 py-1 col">
				<div class="section variant-3">
					<div class="header">
						<div class="title">
							Alerts
						</div>

						<div class="desc">
						These are items that you set an alert for, and it was triggered. For example: If you set a minimum quantity of 10 to Item A, and your current quantity becomes 5, an alert will be generated. Click a row to view the item's details.
						</div>
					</div>

					<div class="content">
						<div class="table-responsive">
							<table id="alerts" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Item</th>
										<th>Location</th>
										<th>Alert Type</th>
										<th>Quantity Set</th>
										<th>Current Quantity</th>
										<th>Created At</th>
									</tr>
								</thead>
								<tbody>
									<?php
										if($all_alerts){
											foreach($all_alerts as $alert){
									?>
									<tr>
										<td><?= $alert->alert_item_id ?></td>
										<td><?= $alert->alert_location_id ?></td>
										<td><?= $alert->alert_type ?></td>
										<td><?= $alert->alert_qty ?></td>
										<td></td>
										<td><?= $alert->created_at ?></td>
										
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
		<!-- End of Warehouses -->
	</div>
</div>

<script type="text/javascript">
'use strict';

var table = {};

(function($) {
	'use strict';

	$('document').ready(function() {
		$('.main-loader').fadeOut(100)

		// Link table to the loader
		$('table#alerts').on('processing.dt', (e, settings, processing) => {
			if(processing)
				$('.main-loader').fadeIn(100)
			else
				$('.main-loader').fadeOut(100)
		})

		// Load table
		// table = $('table#alerts').DataTable({
		// 	serverSide: true,
		// 	ajax: "<?= base_url('api/alerts') ?>",
		// 	columns: [
		// 		{ data: "item_name" },
		// 		{ data: "warehouse_name" },
		// 		{
		// 			data: 'type',
		// 			render: (data, type) => {
		// 				let dataMin = "<?= 'Alerts.min' ?>"
		// 				let dataMax = "<?= 'Alerts.max' ?>"

		// 				if(data == 'min')
		// 					return dataMin
		// 				return dataMax
		// 			}
		// 		},
		// 		{ data: "alert_qty" },
		// 		{ data: "current_qty" },
		// 		{ data: "created_at" }
		// 	],
		// 	order: [[5, 'desc']]
		// })

		$('table#alerts tbody').on('click', 'tr', function() {
			let id = table.row(this).data().DT_RowId
			location.href = `<?= base_url('items') ?>/${id}`
		})
	})
})(jQuery)
</script>