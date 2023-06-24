<div id="modal_hold_until_services" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title">Hold Service</h6>
			</div>
			<form action="" method="post" id="apply_hold_until_date">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group hold_service_text">
								<div style="color: red;" id="apply_hold_until_date_errors"></div>
								<label>Hold this service until the following date:</label>
								<input type="date"
									   id="hold_until_date"
									   name="hold_until_date"
									   value=""
									   class="form-control pickaalldate"
									   placeholder="YYYY-MM-DD">
								<input type="hidden" name="job_data" id="hold_date_job_data_csv" value="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group pull-right">
								<button type="button" class="btn btn-danger" data-dismiss="modal">
									Cancel
								</button>
								<button type="submit" id="hold_services_to_date" class="btn btn-success">Hold</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>

</script>