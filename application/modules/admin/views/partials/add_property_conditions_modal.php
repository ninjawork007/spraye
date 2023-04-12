<div id="modal_add_property_conditions" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title">Add Property Conditions</h6>
			</div>
			<div class="modal-body">
				<form id="addProperty" action="<?= base_url('admin/setting/addPropertyConditions') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Property Condition Name</label>
								<input type="text" class="form-control" name="condition_name" placeholder="Condition Name" required/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Message</label>
								<textarea class="form-control" name="message" rows=4 placeholder="Property Condition Message" required></textarea>
								<span style="color:red;"><?php echo form_error('message'); ?></span>
							</div> 
						</div>
					</div>
					<div class="row">
                        <div class="col-md-12">
							<div class="form-group">
								<label>Include Message in Service Completion Email?</label>
								<label class="checkbox-inline checkbox-right">
									<input type="checkbox" name="in_email" class="switchery-in-email" />&nbsp;Include
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group pull-right">
                      			<button type="submit" id="add-property-condition-submit" class="btn btn-success">Save</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$(function(){
    var in_email = document.querySelector('.switchery-in-email');
	  var switchery = new Switchery(in_email, {
		color: '#36c9c9',
		secondaryColor: "#dfdfdf",
	  });
});
</script>