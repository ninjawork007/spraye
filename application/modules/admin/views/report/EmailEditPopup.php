<form method="post" action="<?php echo  base_url().'/admin/reports/UpdateEmailData'?>">
	<input type="hidden" name="email_id" value="<?php echo $EmailList[0]->id ?>">
	<div class="form-group multi-select-full">
	    <label>Email Name</label>
	    <input type="text" name="email_name" class="form-control" value="<?php echo $EmailList[0]->email_name ?>">
	</div>

	<div class="form-group multi-select-full">
	    <label>Email Subject</label>
	    <input type="text" name="email_subject" class="form-control" value="<?php echo $EmailList[0]->email_subject ?>">
	</div>

	<div class="form-group multi-select-full">
	    <label>Email Text</label>
	    <textarea id="editor1" name="mailText"><?php echo $EmailList[0]->mail_text ?></textarea>
	</div>

	<?php
	$SelectedProgrammes = explode(",", $EmailList[0]->programmes_id);
	?>
	<div class="form-group multi-select-full">
	    <label>
	        Programs
	        <span data-popup="tooltip-custom" title="" data-placement="top" data-original-title='Please select the program(s) that you would like to be dynamically inserted into the email for the "Program Name" dynamic field which will then list those programs in the email for anyone on the list that was enrolled in that program.'> <i class=" icon-info22 tooltip-icon"></i></span>
	    </label>
	    <select class="multiselect-select-all-filtering form-control" name="MassProgramms[]" multiple="multiple" style='white-space: break-spaces;'>
	        <?php foreach ($program_details as $value): ?>
	            <option <?php if(in_array($value->program_id, $SelectedProgrammes)) { echo 'selected'; } ?> value="<?= $value->program_id ?>"> <?= $value->program_name ?> </option>
	        <?php endforeach ?>
	    </select>
	</div>

	<h3>Dynamic values for email :</h3>
	<p>
	    Copy and paste any of the fields below into the body of your email to dynamically insert personalized information for each customer on your list. If you use the "Program Name" dynamic field, then you will need to specify which programs you want included (it will only be listed in the email if the customer is currently enrolled in that program).
	</p>

	<span>
	    <b>Customer First Name : </b> {CUSTOMER_FIRST_NAME}<br>
	    <b>Customer Last Name : </b> {CUSTOMER_LAST_NAME}<br>
	    <b>Property Name : </b> {PROPERTY_NAME}<br>
	    <b>Property Address : </b> {PROPERTY_ADDRESS}<br>
	    <b>Program Name : </b> {PROGRAM_NAME}<br>
	</span>

	<div class="modal-footer">
	    <button class="btn btn-primary" type="submit">Save</button>
	</div>
</form>