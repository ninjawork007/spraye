<style type="text/css">
  #loading {
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    position: fixed;
    display: none;
    opacity: 0.7;
    background-color: #fff;
    z-index: 9999;
    text-align: center;
  }

  #loading-image {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 10%;
    z-index: 100;
  }

  th,
  td {
    text-align: center;
  }

  .pre-scrollable {
    min-height: 0px;
  }

  @media (min-width: 769px) {
    .form-horizontal .control-label[class*=col-sm-] {
      padding-top: 0;
    }
  }
/*custom radio for auto-send invoices*/
.btn-group-custom .btn {
  border: 1px solid #ccc;
  border-radius: 6px;
  color: #333333;
  font-size: 14px;
  line-height: 1;
  padding: 6px 18px;
}
.btn-group-custom{
	margin-left:50px!important;
}
.btn-group-custom .btn:not(:last-child) {
  border-right: none;
}
.btn-group-custom .btn.active,
.btn-group-custom .btn:hover {
  background: #36C9C9;
  border-color: ##36C9C9!important;
  color: #fff;
}
.required{
    color: #c90000;
}
.bit-bolder{
    font-weight: 500;
}
</style>
<!-- Content area -->
<div class="content form-pg">
    <ul id="progressbar">
        <li class="active" id="customer"><strong>Add Customer</strong></li>
        <li id="property"><strong>Add Property</strong></li>
        <li id="confirm"><strong>Finish</strong></li>
    </ul>
  <!-- Form horizontal -->
  <div class="panel panel-flat">

    <div class="panel-heading">
      <h5 class="panel-title">
        <div class="form-group">
          <a href="<?= base_url('admin/customerList') ?>" id="save" class="btn btn-success"><i class=" icon-arrow-left7"> </i> Back to All Customers</a>
        </div>
      </h5>
    </div>
    <div id="loading">
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>" /> <!-- Loading Image -->
    </div>

    <br>

    <div class="panel-body">

      <b><?php if ($this->session->flashdata()) : echo $this->session->flashdata('message');
          endif; ?></b>


      <form class="form-horizontal" action="<?= base_url('admin/addCustomerData') ?>" method="post" name="addcustomer" id="addcustomer"  enctype="multipart/form-data">
        <fieldset class="content-group">
            <input type="hidden" class="form-control" name="confirmation" value="<?=$opt?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3 bit-bolder">First Name<span class="required"> *</span></label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="first_name" value="<?php echo set_value('first_name') ?>" placeholder="First Name">
                  <span style="color:red;"><?php echo form_error('first_name'); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3 bit-bolder">Last Name<span class="required"> *</span></label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="last_name" value="<?php echo set_value('last_name') ?>" placeholder="Last Name">
                  <span style="color:red;"><?php echo form_error('last_name'); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Company Name</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" placeholder="Company Name" name="customer_company_name">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3 bit-bolder">Email<span class="required"> *</span></label>
                <div class="col-lg-9">
                  <div class="row">
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="email" value="<?php echo set_value('email') ?>" placeholder="Email">
                      <span style="color:red;"><?php echo form_error('email'); ?></span>
                    </div>
                    <div class="col-md-6">
                      <div class="checkbox">
                        <label class="checkbox-inline checkbox-right">
                          <input type="checkbox" name="is_email" class="switchery-is-email" value="1" checked>
                          Subscribe
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3 col-sm-12 col-xs-12">Secondary Email(s)</label>
                <div class="multi-select-full col-lg-8  col-sm-10 col-xs-10 pl-15">
                  <textarea cols="60" disabled="disabled" id="secondary_email_list"></textarea>
                  <input type="hidden" id="secondary_email_list_hid" name="secondary_email_list_hid" style="max-width: 100%;" value="" />
                </div>
                <div class="col-lg-1 col-sm-2 col-xs-2 addbuttonmanage">
                  <div class="form-group mb-5">
                    <center>
                      <a href="#" id="add_secondary_email_link" data-toggle="modal" data-target="#modal_add_secondary_emails"><i class="icon-add text-success pt-5" style="font-size:25px;"></i></a>
                    </center>
                  </div>
                  <div class="form-group ">
                    <center>
                      <a id="reset_secondary_email_link" href="#"><i class="icon-reset text-success pt-6" style="font-size:25px;"></i></a>
                    </center>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3 bit-bolder">Mobile<span class="required"> *</span></label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="phone" value="<?php echo set_value('phone') ?>" placeholder="Mobile">
                  <span style="color:red;"><?php echo form_error('phone'); ?></span>
                  <span>Please do not use dashes</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Home</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="home_phone" value="<?php echo set_value('phone') ?>" placeholder="Home">
                  <span style="color:red;"><?php echo form_error('phone'); ?></span>
                  <span>Please do not use dashes</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Work</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="work_phone" value="<?php echo set_value('phone') ?>" placeholder="Work">
                  <span style="color:red;"><?php echo form_error('phone'); ?></span>
                  <span>Please do not use dashes</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3 bit-bolder">Billing Address<span class="required"> *</span></label>
                <div class="col-lg-9">
                  <input type="text" id="autocomplete" class="form-control" name="billing_street" value="<?php echo set_value('billing_street') ?>" placeholder="Address">
                  <span style="color:red;"><?php echo form_error('billing_street'); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Billing Address 2</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="billing_street_2" value="<?php echo set_value('billing_street_2') ?>" placeholder="Address 2" id="billing_street_2">
                  <span style="color:red;"><?php echo form_error('billing_street_2'); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3 bit-bolder">City<span class="required"> *</span></label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="billing_city" value="<?php echo set_value('billing_city') ?>" placeholder="City" id="locality">
                  <span style="color:red;"><?php echo form_error('billing_city'); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3 bit-bolder">Billing State / Territory<span class="required"> *</span></label>
                <div class="col-lg-9">
                  <select class="form-control" id="region" name="billing_state" value="<?php echo set_value('billing_state') ?>">
                    <option value="">Select State</option>
                    <optgroup label="Canadian Provinces">
                      <option value="AB"<?=(set_value('billing_state') == 'AB')? 'selected': ''; ?>>Alberta</option>
                      <option value="BC"<?=(set_value('billing_state') == 'BC')? 'selected': ''; ?>>British Columbia</option>
                      <option value="MB"<?=(set_value('billing_state') == 'MB')? 'selected': ''; ?>>Manitoba</option>
                      <option value="NB"<?=(set_value('billing_state') == 'NB')? 'selected': ''; ?>>New Brunswick</option>
                      <option value="NF"<?=(set_value('billing_state') == 'NF')? 'selected': ''; ?>>Newfoundland</option>
                      <option value="NT"<?=(set_value('billing_state') == 'NT')? 'selected': ''; ?>>Northwest Territories</option>
                      <option value="NS"<?=(set_value('billing_state') == 'NS')? 'selected': ''; ?>>Nova Scotia</option>
                      <option value="NU"<?=(set_value('billing_state') == 'NU')? 'selected': ''; ?>>Nunavut</option>
                      <option value="ON"<?=(set_value('billing_state') == 'ON')? 'selected': ''; ?>>Ontario</option>
                      <option value="PE"<?=(set_value('billing_state') == 'PE')? 'selected': ''; ?>>Prince Edward Island</option>
                      <option value="QC"<?=(set_value('billing_state') == 'QC')? 'selected': ''; ?>>Quebec</option>
                      <option value="SK"<?=(set_value('billing_state') == 'SK')? 'selected': ''; ?>>Saskatchewan</option>
                      <option value="YT"<?=(set_value('billing_state') == 'YT')? 'selected': ''; ?>>Yukon Territory</option>
                    </optgroup>                    
                    <optgroup label="U.S. States/Territories">
                      <option value="AL"<?=(set_value('billing_state') == 'AL')? 'selected': ''; ?>>Alabama</option>
                      <option value="AK"<?=(set_value('billing_state') == 'AK')? 'selected': ''; ?>>Alaska</option>
                      <option value="AZ"<?=(set_value('billing_state') == 'AZ')? 'selected': ''; ?>>Arizona</option>
                      <option value="AR"<?=(set_value('billing_state') == 'AR')? 'selected': ''; ?>>Arkansas</option>
                      <option value="CA"<?=(set_value('billing_state') == 'CA')? 'selected': ''; ?>>California</option>
                      <option value="CO"<?=(set_value('billing_state') == 'CO')? 'selected': ''; ?>>Colorado</option>
                      <option value="CT"<?=(set_value('billing_state') == 'CT')? 'selected': ''; ?>>Connecticut</option>
                      <option value="DE"<?=(set_value('billing_state') == 'DE')? 'selected': ''; ?>>Delaware</option>
                      <option value="DC"<?=(set_value('billing_state') == 'DC')? 'selected': ''; ?>>District Of Columbia</option>
                      <option value="FL"<?=(set_value('billing_state') == 'FL')? 'selected': ''; ?>>Florida</option>
                      <option value="GA"<?=(set_value('billing_state') == 'GA')? 'selected': ''; ?>>Georgia</option>
                      <option value="HI"<?=(set_value('billing_state') == 'HI')? 'selected': ''; ?>>Hawaii</option>
                      <option value="ID"<?=(set_value('billing_state') == 'ID')? 'selected': ''; ?>>Idaho</option>
                      <option value="IL"<?=(set_value('billing_state') == 'IL')? 'selected': ''; ?>>Illinois</option>
                      <option value="IN"<?=(set_value('billing_state') == 'IN')? 'selected': ''; ?>>Indiana</option>
                      <option value="IA"<?=(set_value('billing_state') == 'IA')? 'selected': ''; ?>>Iowa</option>
                      <option value="KS"<?=(set_value('billing_state') == 'KS')? 'selected': ''; ?>>Kansas</option>
                      <option value="KY"<?=(set_value('billing_state') == 'KY')? 'selected': ''; ?>>Kentucky</option>
                      <option value="LA"<?=(set_value('billing_state') == 'LA')? 'selected': ''; ?>>Louisiana</option>
                      <option value="ME"<?=(set_value('billing_state') == 'ME')? 'selected': ''; ?>>Maine</option>
                      <option value="MD"<?=(set_value('billing_state') == 'MD')? 'selected': ''; ?>>Maryland</option>
                      <option value="MA"<?=(set_value('billing_state') == 'MA')? 'selected': ''; ?>>Massachusetts</option>
                      <option value="MI"<?=(set_value('billing_state') == 'MI')? 'selected': ''; ?>>Michigan</option>
                      <option value="MN"<?=(set_value('billing_state') == 'MN')? 'selected': ''; ?>>Minnesota</option>
                      <option value="MS"<?=(set_value('billing_state') == 'MS')? 'selected': ''; ?>>Mississippi</option>
                      <option value="MO"<?=(set_value('billing_state') == 'MO')? 'selected': ''; ?>>Missouri</option>
                      <option value="MT"<?=(set_value('billing_state') == 'MT')? 'selected': ''; ?>>Montana</option>
                      <option value="NE"<?=(set_value('billing_state') == 'NE')? 'selected': ''; ?>>Nebraska</option>
                      <option value="NV"<?=(set_value('billing_state') == 'NV')? 'selected': ''; ?>>Nevada</option>
                      <option value="NH"<?=(set_value('billing_state') == 'NH')? 'selected': ''; ?>>New Hampshire</option>
                      <option value="NJ"<?=(set_value('billing_state') == 'NJ')? 'selected': ''; ?>>New Jersey</option>
                      <option value="NM"<?=(set_value('billing_state') == 'NM')? 'selected': ''; ?>>New Mexico</option>
                      <option value="NY"<?=(set_value('billing_state') == 'NY')? 'selected': ''; ?>>New York</option>
                      <option value="NC"<?=(set_value('billing_state') == 'NC')? 'selected': ''; ?>>North Carolina</option>
                      <option value="ND"<?=(set_value('billing_state') == 'ND')? 'selected': ''; ?>>North Dakota</option>
                      <option value="OH"<?=(set_value('billing_state') == 'OH')? 'selected': ''; ?>>Ohio</option>
                      <option value="OK"<?=(set_value('billing_state') == 'OK')? 'selected': ''; ?>>Oklahoma</option>
                      <option value="OR"<?=(set_value('billing_state') == 'OR')? 'selected': ''; ?>>Oregon</option>
                      <option value="PA"<?=(set_value('billing_state') == 'PA')? 'selected': ''; ?>>Pennsylvania</option>
                      <option value="RI"<?=(set_value('billing_state') == 'RI')? 'selected': ''; ?>>Rhode Island</option>
                      <option value="SC"<?=(set_value('billing_state') == 'SC')? 'selected': ''; ?>>South Carolina</option>
                      <option value="SD"<?=(set_value('billing_state') == 'SD')? 'selected': ''; ?>>South Dakota</option>
                      <option value="TN"<?=(set_value('billing_state') == 'TN')? 'selected': ''; ?>>Tennessee</option>
                      <option value="TX"<?=(set_value('billing_state') == 'TX')? 'selected': ''; ?>>Texas</option>
                      <option value="UT"<?=(set_value('billing_state') == 'UT')? 'selected': ''; ?>>Utah</option>
                      <option value="VT"<?=(set_value('billing_state') == 'VT')? 'selected': ''; ?>>Vermont</option>
                      <option value="VA"<?=(set_value('billing_state') == 'VA')? 'selected': ''; ?>>Virginia</option>
                      <option value="WA"<?=(set_value('billing_state') == 'WA')? 'selected': ''; ?>>Washington</option>
                      <option value="WV"<?=(set_value('billing_state') == 'WV')? 'selected': ''; ?>>West Virginia</option>
                      <option value="WI"<?=(set_value('billing_state') == 'WI')? 'selected': ''; ?>>Wisconsin</option>
                      <option value="WY"<?=(set_value('billing_state') == 'WY')? 'selected': ''; ?>>Wyoming</option>
                    </optgroup>
                  </select>
                  <span style="color:red;"><?php echo form_error('billing_state'); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3 bit-bolder">Postal Code<span class="required"> *</span></label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="billing_zipcode" value="<?php echo set_value('billing_zipcode') ?>" placeholder="Postal Code" id="postal-code">
                  <span style="color:red;"><?php echo form_error('billing_zipcode'); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Billing Type</label>
                <div class="col-lg-9">
                  <select class="form-control" name="billing_type">
                    <option value=0 selected>Standard</option>
                    <option value=1>Group Billing</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Customer Status</label>
                <div class="col-lg-9">
                  <select class="form-control" name="customer_status">
                    <option value="">Select Any Status</option>
                    <option value="1" selected>Active</option>
                    <option value="0">Non-Active</option>
                    <option value="3">Estimate</option>
                    <option value="2">Hold</option>
                    <option value="4">Sales Call Scheduled</option>
                    <option value="5">Estimate Sent</option>
                    <option value="6">Estimate Declined</option>
                    <option value="7">Prospect</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Pre-Service Notification</label>
                <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
                  <select class="multiselect-select-all-filtering form-control" name="pre_service_notification[]" multiple="multiple" value="">
                    <option value="1">Phone Call</option>
                    <option value="2" >Automated Email(s)</option>
                    <option value="3" >Automated Text message(s)</option>
                    <option value="4">Text when En route</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
		<?php if(isset($send_daily_invoice_mail) && $send_daily_invoice_mail == 1){
				if(isset($customerData['autosend_invoices']) && $customerData['autosend_invoices'] != 1){
					$autoSend = 0;
				}else{
					$autoSend = 1;
				}

				if(isset($customerData['autosend_frequency']) && $customerData['autosend_frequency'] == 'monthly'){
					$sendDaily = 0;
				}else{
					$sendDaily = 1;
				}
		?>
		<div class="row" id="auto-send-invoices-div">
			<div class="col-md-6">
				<div class="form-group">
					<input type="hidden" name="send_daily_invoice_mail" value=1 />
					<label class="control-label col-lg-3">Auto Send Invoices?</label>
					<label class="togglebutton" style="font-size:16px">Off</label>
					<input name="autosend_invoices" type="checkbox" class="switchery-is-autosend-invoices" <?php if($autoSend == 1){echo "checked";} ?> />
					<label class="togglebutton" style="font-size:16px">On</label>
					<div class="btn-group btn-group-custom" data-toggle="buttons" <?php if($autoSend != 1){echo "style='display:none;'";} ?> id="autosend-freq-div">
						<label class="btn btn-default" for="autosend_frequency1" id="autosend_frequency1-label">
							<input type="radio" class="form-check-input" name="autosend_frequency" id="autosend_frequency1" value="daily" <?php if($sendDaily == 1){echo "checked";} ?>/>Daily
						</label>
						<label class="btn btn-default" for="autosend_frequency2" id="autosend_frequency2-label">
							<input type="radio" class="form-check-input" name="autosend_frequency" id="autosend_frequency2" value="monthly" <?php if($sendDaily != 1){echo "checked";} ?>/>Monthly
						</label>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
        </fieldset>
        <div class="text-right">
          <button type="submit" class="btn btn-success">Next <i class="icon-arrow-right14 position-right"></i></button>
        </div>
      </form>
    </div>
  </div>
  <!-- /form horizontal -->



</div>
<!-- /content area -->
<!-- Secondary email modal -->
<div id="modal_add_secondary_emails" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Add Secondary Email</h6>
      </div>

      <form name="add_secondary_email" id="my_form" action="<?= base_url('admin/addSecondaryEmailDataJson') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax">
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <label>Email</label>
                <input type="email" class="form-control" name="secondary_email" placeholder="Email">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="assignjob" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!--end modal-->


<div class="mydiv" style="display: none;">

</div>

<!-- Primary modal -->
<div id="modal_add_program" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Add Program</h6>
      </div>

      <form name="addprogram" id="my_form" action="<?= base_url('admin/addProgramData') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">

          <div class="form-group">
            <div class="row">
              <div class="col-sm-6">
                <label>Program Name</label>
                <input type="text" class="form-control" name="program_name" placeholder="Program Name">
              </div>
              <div class="col-sm-6">
                <label>Pricing</label>
                <select class="form-control" name="program_price">
                  <option value="Monthly">Monthly</option>
                  <option value="One Time">One Time</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Program Note</label>
                <textarea class="form-control" name="program_notes" rows="5"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" id="assignjob" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- /primary modal -->



<script src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>&libraries=places&callback=initAutocomplete" async defer></script>

<script>
  $(document).ready(function() {
    var front_yard = $('#front_yard_square_feet').val();
    front_yard = Number.isInteger(Number.parseInt(front_yard)) ? Number.parseInt(front_yard) : 0;

    if (front_yard == 0) {
      $("#front_yard_grass").prop('disabled', true);
    }

    var back_yard = $('#back_yard_square_feet').val();
    back_yard = Number.isInteger(Number.parseInt(back_yard)) ? Number.parseInt(back_yard) : 0;

    if (back_yard == 0) {
      $("#back_yard_grass").prop('disabled', true);
    }

    $("#front_yard_square_feet").keyup(function() {
      var first_yard = $('#front_yard_square_feet').val();
      first_yard = Number.isInteger(Number.parseInt(first_yard)) ? Number.parseInt(first_yard) : 0;
      var second_yard = 0;

      second_yard = $('#back_yard_square_feet').val();
      second_yard = Number.isInteger(Number.parseInt(second_yard)) ? Number.parseInt(second_yard) : 0;

      var total_yard = first_yard + second_yard;
      total_yard = Number.isInteger(Number.parseInt(total_yard)) ? total_yard : 0;

      $('#yard_square_feet').val(total_yard);

      if (first_yard == 0) {
        $("#front_yard_grass").prop('disabled', true);
      } else {
        $("#front_yard_grass").prop('disabled', false);
      }
    });

    $("#back_yard_square_feet").keyup(function() {
      var first_yard = $('#back_yard_square_feet').val();
      first_yard = Number.isInteger(Number.parseInt(first_yard)) ? Number.parseInt(first_yard) : 0;
      var second_yard = 0;

      second_yard = $('#front_yard_square_feet').val();
      second_yard = Number.isInteger(Number.parseInt(second_yard)) ? Number.parseInt(second_yard) : 0;

      var total_yard = first_yard + second_yard;
      total_yard = Number.isInteger(Number.parseInt(total_yard)) ? total_yard : 0;

      $('#yard_square_feet').val(total_yard);

      if (first_yard == 0) {
        $("#back_yard_grass").prop('disabled', true);
      } else {
        $("#back_yard_grass").prop('disabled', false);
      }
    });
  });
</script>

<script>
  // This example displays an address form, using the autocomplete feature
  // of the Google Places API to help users fill in the information.

  var placeSearch, autocomplete, autocomplete2;
  var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
  };

  function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
 

    autocomplete2 = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */
      (document.getElementById('autocomplete2')), {
        types: ['geocode']
      });
    autocomplete2.addListener('place_changed', function() {
      fillInAddress(autocomplete2, "2");
    });




  }

  function fillInAddress(autocomplete, unique) {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();
    // alert(JSON.stringify(place.adr_address));
    //console.log(JSON.stringify(place));
    $('.mydiv').html(place.adr_address);
    return_locality = $('.locality').text();
    return_region = $('.region').text();
    return_postal_code = $('.postal-code').text();
    res = return_postal_code.split("-");

    $('#locality' + unique).val(return_locality);
    $('#region' + unique).val(return_region);
    $('#postal-code' + unique).val(res[0]);



    for (var component in componentForm) {
      if (!!document.getElementById(component + unique)) {
        document.getElementById(component + unique).value = '';
        document.getElementById(component + unique).disabled = false;
      }
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType] && document.getElementById(addressType + unique)) {
        var val = place.address_components[i][componentForm[addressType]];
        document.getElementById(addressType + unique).value = val;

        //alert(val);
      }
    }


  }
  google.maps.event.addDomListener(window, "load", initAutocomplete);

  function geolocate() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        var circle = new google.maps.Circle({
          center: geolocation,
          radius: position.coords.accuracy
        });

        // alert(position.coords.latitude);
        // alert(position.coords.longitude);
        autocomplete.setBounds(circle.getBounds());
      });
    }
  }
</script>

<script type="text/javascript">
  $("#reset_secondary_email_link").addClass("hidden");

  $('#confirmation-button').click(function() {
      $('#addcustomer').submit();
  });

  $('#reset_secondary_email_link').click(function() {
    swal({
      title: 'Email',
      text: "Do you want to reset field?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#009402',
      cancelButtonColor: '#FFBE2C',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then((result) => {
      if (result.value) {
        $('#secondary_email_list').html("");
        $("#secondary_email_list_hid").val("");
        $("#add_secondary_email_link i").addClass("pt-5");
        $("#reset_secondary_email_link").addClass("hidden");
      }

    });
    
  });
  $('#autofill').click(function() {
    if ($(this).prop("checked") == true) {
      //alert($('#locality').val());
      var result = $('#autocomplete').val();
      $('#autocomplete2').val(result);
      $('#property_address_2').val($('#billing_street_2').val());
      $('#locality2').val($('#locality').val());
      $('#region2').val($('#region').val());
      $('#postal-code2').val($('#postal-code').val());



    } else if ($(this).prop("checked") == false) {
      $('#autocomplete2').val('');
      $('#property_address_2').val('');
      $('#locality2').val('');
      $('#region2').val('');
      $('#postal-code2').val('');

    }
  });


  function keydownAddress2() {
    $("#autofill")[0].checked = false;
    $("#uniform-autofill").find("span").removeClass("checked");
  }
$(function() {
	var is_email = document.querySelector('.switchery-is-email');
	var switchery = new Switchery(is_email, {
		color: '#36c9c9',
		secondaryColor: "#dfdfdf",
	  });
	var is_autosend = document.querySelector('.switchery-is-autosend-invoices');
    var switchery = new Switchery(is_autosend, {
        color: '#36c9c9',
        secondaryColor: "#dfdfdf",
    });
});
$(document).ready(function(){
	if($('input#autosend_frequency1').prop('checked') == true){
		$('label#autosend_frequency1-label').addClass('active');
	}
	if($('input#autosend_frequency2').prop('checked') == true){
		$('label#autosend_frequency2-label').addClass('active');
	}
});
$('input[name="autosend_invoices"]').change(function(){
	if($(this).prop("checked") == true){
		var checked = true;
	}else{
		var checked = false;
	}
	if(checked != true){
		$('#auto-send-invoices-div .btn-group-custom').css('display','none');
	}else{
		$('#auto-send-invoices-div .btn-group-custom').css('display','inline-block');
	}
});
</script>