<div id="modal_edit_property_condition" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title">Edit Property Conditions</h6>
			</div>
			<div class="modal-body">
				<form id="editProperty" action="<?= base_url('admin/setting/updatePropertyConditions') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax">
                    <input type="hidden" id="update_property_condition_id" name="property_condition_id" value=""/>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Property Condition Name</label>
								<input type="text" class="form-control" id="update_condition_name" name="condition_name" placeholder="Condition Name" required/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Message</label>
								<textarea class="form-control" id="update_message" name="message" maxlength=255 rows=4 placeholder="Property Condition Message" required></textarea>
								<span style="color:red;"><?php echo form_error('message'); ?></span>
							</div> 
						</div>
					</div>
					<div class="row">
                        <div class="col-md-12">
							<div class="form-group">
								<label>Include Message in Service Completion Email?</label>
								<label class="checkbox-inline checkbox-right">
									<input type="checkbox" id="update_in_email" name="in_email" class="switchery-update-in-email" />&nbsp;Include
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group pull-right">
                      			<button type="submit" id="update-property-condition-submit" class="btn btn-success">Update</button>
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
//    var in_email = document.querySelector('.switchery-update-in-email');
//	  var switchery = new Switchery(in_email,{
//		color: '#36c9c9',
//		secondaryColor: "#dfdfdf",
//	  });
});
</script>