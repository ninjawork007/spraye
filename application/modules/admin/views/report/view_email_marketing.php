<style type="text/css">
  .toolbar {
    float: left;
    padding-left: 5px;
}
.form-control[readonly] {
  background-color: #ededed;
}
#invoice-age-list .dropdown-menu {
    min-width: 80px !important;
}
</style>
<div class="loading" style="display:none;">
	<center><img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/></center>
</div>

<div class="p-15 pull-right">
	<a href="<?php echo base_url() ?>/admin/reports/marketingCustomerDataReport" target="_blank"><button type="button" class="btn btn-info"><i class="icon-envelop3 position-left"></i> Send New Email</button></a>
</div>
<div class="clearfix"></div>

<div id="invoice-age-list">
<div class="post-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
	<div class="table-responsive table-spraye p-15">
		<table class="table datatable-button-print-basic">
			<thead>
				<tr>
					<th>Subject</th>
					<th>Sent Date</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($EmailList as $Index => $Data) {
				?>
				<tr>
					<td><?php echo $Data->email_subject?></td>
					<td>
						<?php 
						if($Data->send_date != "0000-00-00"){
							echo $Data->send_date;
						}
					?>
					</td>
					<td><?php echo $Data->status == 0 ? "Draft" : "Sent" ?></td>
					<td>
						<ul style="list-style-type: none; padding-left: 0px;">
							<?php
							if($Data->status == 0){
							?>
							<li style="display: inline; padding-right: 10px;">
								<a href="<?php echo base_url() ?>/admin/MassEmail/ResendEmail?id=<?php echo $Data->id ?>" class="confirm_delete button-next"><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a>
							</li>
								<?php
							}
							?>

							<li style="display: inline; padding-right: 10px;">
								<a href="<?php echo base_url() ?>/admin/MassEmail/DeleteEmails?id=<?php echo $Data->id ?>" class="confirm_delete button-next"><i class="fa fa-trash   position-center" style="color: #9a9797;"></i></a>
							</li>
						</ul>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>


<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>
<script type="text/javascript">
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

<script>
$(document).ready(function() {
	tableInitialize();
});
function tableInitialize(argument) {
	$.extend( $.fn.dataTable.defaults, {
		autoWidth: false,
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: '<span>Filter:</span> _INPUT_',
			lengthMenu: '<span>Show:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
		},
	});

	$('.datatable-button-print-basic').DataTable({
		"iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
		"aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
		buttons: [
		{
			extend: 'print',
			text: '<i class="icon-printer position-left"></i> Print table',
			className: 'btn bg-blue'
		},
		]
	});
}
</script>