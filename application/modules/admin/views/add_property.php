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

  #hidden_source {
    display: none;
  }

  th,
  td {
    text-align: center;
  }

  .pre-scrollable {
    min-height: 0px;
  }
label.error {	
	color: rgb(221, 51, 51) !important;	
	font-size: 13px;	
	font-family: sans-serif;	
	font-weight: 600;	
}	
span.requesecolo {	
	color: red;	
}	
</style>


<!-- Content area -->
<div class="content">
  <?php if($customer_id): ?>
    <ul id="progressbar">
        <li class="active" id="customer"><strong>Add Customer</strong></li>
        <li class="active" id="property"><strong>Add Property</strong></li>
        <li id="confirm"><strong>Finish</strong></li>
    </ul>
  <?php endif; ?>
  <!-- Form horizontal -->
  <div class="panel panel-flat">

    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">
          <div class="form-group">
            <a href="<?= base_url('admin/propertyList') ?>" id="save" class="btn btn-success"><i class=" icon-arrow-left7"> </i> Back to All Properties</a>
          </div>
        </h5>
      </div>
    </div>
    <div id="loading">
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>" /> <!-- Loading Image -->
    </div>


    <br>
    <div class="panel-body">
      
      <b><?php if ($this->session->flashdata()) : echo $this->session->flashdata('message');
          endif; ?></b>
      <form class="form-horizontal" action="<?= base_url('admin/addPropertyData') ?>" method="post" name="addproperty" id="addproperty" enctype="multipart/form-data">
        <fieldset class="content-group">
            <input type="hidden" class="form-control" name="confirmation" id="confirmation" value="<?=(isset($opt)?$opt:"")?>">
      <div class="row">
        <div class="col-md-6">

          <div class="form-group">



            <label class="control-label col-lg-3">Property Name</label>

            <div class="col-lg-9">

              <input type="text" class="form-control" name="property_title" value="<?= $customer_id ? $customer_name."-".$customerData['billing_street'] : set_value('property_title') ?>" placeholder="Property Name">

              <span style="color:red;"><?php echo form_error('property_title'); ?></span>

            </div>

          </div>
        </div>
        <div class="col-md-6">

          <div class="form-group" style="margin-bottom: 4px;">

            <label class="control-label col-lg-3 col-sm-12 col-xs-12">Assign Customer</label>

            <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10" style="padding-left: 14px;">

              <select class="multiselect-select-all-filtering form-control" name="assign_customer[]" multiple="multiple" id="customer_list" value="<?php echo set_value('assign_customer[]') ?>">

                <?php foreach ($customerlist as $value) : ?>

                  <option value="<?= $value->customer_id ?>" title="<?= $value->billing_street ?>" data-billingtype="<?= $value->billing_type ?>" <?=$value->customer_id == $customer_id ? "selected":""; ?>><?= $value->first_name ?> <?= $value->last_name  ?></option>

                <?php endforeach ?>

              </select>

              <span style="color:red;"><?php echo form_error('assign_customer'); ?></span>

            </div>

          </div>
        </div>
      </div>
      <!-- <?php if($customer_id): ?> -->
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
              <div class="checkbox" style="margin-top:35px;">
                  <label class="checkbox-inline checkbox-right">
                      <input type="checkbox" name="use_billing_address" id="use_billing_address" />&nbsp;Same as Billing Address
                      <input type="hidden" name="customer_id" value="<?=$customer_id?>"/>
                  </label>
              </div>
          </div>
        </div>
      </div>
    <!-- <?php endif; ?> -->
			<div class="row group_billing_contact_info" style="display:none;">
				<input type="hidden" name="is_group_billing" vallue=0/>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-lg-3">Property First Name</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" name="property_first_name" value="<?php echo set_value('property_first_name') ?>" placeholder=" Property First Name" required>
							<span style="color:red;"><?php echo form_error('property_first_name'); ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-lg-3">Property Last Name</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" name="property_last_name" value="<?php echo set_value('property_last_name') ?>" placeholder="Property Last Name" required>
							<span style="color:red;"><?php echo form_error('property_last_name'); ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row group_billing_contact_info" style="display:none;">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-lg-3">Property Phone</label>
						<div class="col-lg-9">
							<div class="row">
								<div class="col-md-9">
									<input type="text" class="form-control" name="property_phone" value=<?php echo set_value('property_phone') ?>"" placeholder="Property Phone" required>
									<span style="color:red;"><?php echo form_error('property_phone'); ?></span>
									<span>Please do not use dashes</span>
								</div>
								<div class="col-md-3">
									<div class="checkbox" style="padding-top:0px;">
										<label class="checkbox-inline checkbox-right ">
											<input type="checkbox" name="property_is_text" class="switchery-is-text" />&nbsp;Opt-In
										</label>
									</div>
								</div>
							</div>
						</div>
					</div> 
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-lg-3">Property Email</label>
						<div class="col-lg-9">
							<div class="row">
								<div class="col-md-9">
									<input type="text" class="form-control" name="property_email" value="<?php echo set_value('property_email') ?>" placeholder="Property Email" required>
									<span style="color:red;"><?php echo form_error('property_email'); ?></span>
								</div>
								<div class="col-md-3">
									<div class="checkbox" style="padding-top:0px;">
										<label class="checkbox-inline checkbox-right">
											<input type="checkbox" name="property_is_email" class="switchery-property-is-email" />&nbsp;Opt-In
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Address</label>
						<div class="col-lg-8">
					  		<input type="text" class="form-control" name="property_address" id="autocomplete" onFocus="geolocate()" placeholder="Address" onchange="reset_confirmation()" value=" <?php echo set_value('property_address') ?>">
						</div>
					  	<div class="col-lg-1">
						  <a href="#" data-toggle="modal" data-target="#modal_lat_long" title="You can get address by using coordinates(Latitude,Longitude)">
							  <i class="icon-location4 text-success" style="padding-top:6px;font-size:25px;"></i>
						  </a>
                  		</div>
				  	</div>
            	</div>
            	<div id="map"></div>
            	<input type="hidden" name="property_latitude" id="latitude" />
            	<input type="hidden" name="property_longitude" id="longitude" />
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Address 2</label>
					<div class="col-lg-9">
					  <input type="text" class="form-control" name="property_address_2" value="<?php echo set_value('property_address_2') ?>" placeholder="Address 2">
					  <span style="color:red;"><?php echo form_error('property_address_2'); ?></span>
					</div>
				  </div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">City</label>
					<div class="col-lg-9">
					  <input type="text" class="form-control" name="property_city" value="<?php echo set_value('property_city') ?>" placeholder="City" id="locality">
					  <span style="color:red;"><?php echo form_error('property_city'); ?></span>
					</div>
				  </div>
				</div>
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">State / Territory</label>
					<div class="col-lg-9">
					  <select class="form-control" name="property_state" value="<?php echo set_value('property_state') ?>" id="region">

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
					  <span style="color:red;"><?php echo form_error('property_state'); ?></span>
					</div>
				  </div>
				</div>
			</div>
			<div class="row">
			   	<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Postal Code</label>
					<div class="col-lg-9">
					  <input type="text" class="form-control" name="property_zip" value="<?php echo set_value('property_zip') ?>" placeholder="Postal Code" id="postal-code">
					  <span style="color:red;"><?php echo form_error('property_zip'); ?></span>
					</div>
				  </div>
				</div>
				<div class="col-md-6">
					<div class="form-group mr-bt">
						<label class="control-label col-lg-3">Property Type</label>
						<div class="form-group">
							<div class="col-sm-3">
								<label class="radio-inline">
									<input name="property_type" value="Commercial" type="radio"  />Commercial
								</label>
							</div>
							<div class="col-sm-3">
								<label class="radio-inline">
									<input name="property_type" value="Residential" type="radio" checked="checked" />Residential
								</label>
							</div>
						</div>
						<span style="color:red;"><?php echo form_error('property_type'); ?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-lg-3 col-sm-12 col-xs-12">Service Area</label>
						<div class="col-lg-8  col-sm-10 col-xs-10">
							<select class="form-control" id="serviceareaoption" name="property_area" value="<?php echo set_value('property_area') ?>">
								<option value="">Select Any Service Area</option>
								<?php if (!empty($propertyarealist)) {
									foreach ($propertyarealist as $value) { ?>
								<option value="<?= $value->property_area_cat_id ?>"><?= $value->category_area_name ?></option>
								<?php }} ?>
							</select>
							<span style="color:red;"><?php echo form_error('property_area'); ?></span>
                            <br />
                            <div class="btn btn-success m-y-1" id="auto-assign-service-area">Auto Assign Service Area</div>
						</div>
						<div class="col-md-1 col-lg-1 col-sm-2 col-xs-2 addbuttonmanage">
							<a href="#" data-toggle="modal" data-target="#modal_add_service_area"><i class="icon-add text-success" style="padding-top:6px;font-size:25px;"></i></a>
						</div>
					</div>
				</div>
				<div class="col-md-6" style="margin-bottom: 4px;" style="display:<?= $setting_details->is_sales_tax == 1 ? 'block' : 'none' ?> ">
					<div class="form-group">
						<label class="control-label col-lg-3 col-sm-12 col-xs-12">Sales Tax Area</label>
						<div class="multi-select-full col-lg-9  col-sm-10 col-xs-10" style="padding-left: 6px;">
							<select class="multiselect-select-all-filtering form-control" name="sale_tax_area_id[]" multiple="multiple" id="sales_tax">
								<?php if (!empty($sales_tax_details)) {
									foreach ($sales_tax_details as $key => $value) {?>
								<option value="<?= $value->sale_tax_area_id ?>" <?= ( in_array($value->sale_tax_area_id,  json_decode($setting_details->default_sales_tax_area)))?'selected':'' ?>><?= $value->tax_name  ?> </option>
								<?php  } } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Total Yard Square Feet</label>
					<div class="col-lg-9" style="padding-left: 11px;">
					  <input type="text" class="form-control" name="yard_square_feet" id="yard_square_feet" value="<?php echo set_value('yard_square_feet') ?>" placeholder="Total Yard Square Feet">
					  <span style="color:red;"><?php echo form_error('yard_square_feet'); ?></span>
					</div>
				  </div>
				</div>
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Total Yard Grass Type</label>
					<div class="col-lg-9">
					  <select class="form-control" name="total_yard_grass" id="total_yard_grass">
						<option value="">Select Yard Grass Type</option>
						<option value="Bent">Bent</option>
						<option value="Bermuda">Bermuda</option>
						<option value="Dichondra">Dichondra</option>
						<option value="Fine Fescue">Fine Fescue</option>
						<option value="Kentucky Bluegrass">Kentucky Bluegrass</option>
						<option value="Ryegrass">Ryegrass</option>
						<option value="St. Augustine/Floratam">St. Augustine/Floratam</option>
						<option value="Tall Fescue">Tall Fescue</option>
						<option value="Zoysia">Zoysia</option>
						<option value="Centipede">Centipede</option>
						<option value="Bluegrass/Rye/Fescue">Bluegrass/Rye/Fescue</option>
						<option value="Warm Season">Warm Season</option>
						<option value="Cool Season">Cool Season</option>
                        <option value="Mixed Grass">Mixed Grass</option>
					  </select>
					</div>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Front Yard Square Feet</label>
					<div class="col-lg-9" style="padding-left: 11px;">
					  <input type="text" class="form-control yard_squre_feet" name="front_yard_square_feet" id="front_yard_square_feet" value=0 placeholder="Front Yard Square Feet">
					  <span style="color:red;"><?php echo form_error('front_yard_square_feet'); ?></span>
					</div>
				  </div>
				</div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label col-lg-3">Front Yard Grass Type</label>
                          <div class="col-lg-9">
                              <select class="form-control" name="front_yard_grass" id="front_yard_grass">
                                  <option value="">Select Front Yard Grass Type</option>
                                  <option value="Bent">Bent</option>
                                  <option value="Bermuda">Bermuda</option>
                                  <option value="Dichondra">Dichondra</option>
                                  <option value="Fine Fescue">Fine Fescue</option>
                                  <option value="Kentucky Bluegrass">Kentucky Bluegrass</option>
                                  <option value="Ryegrass">Ryegrass</option>
                                  <option value="St. Augustine/Floratam">St. Augustine/Floratam</option>
                                  <option value="Tall Fescue">Tall Fescue</option>
                                  <option value="Zoysia">Zoysia</option>
                                  <option value="Centipede">Centipede</option>
                                  <option value="Bluegrass/Rye/Fescue">Bluegrass/Rye/Fescue</option>
                                  <option value="Warm Season">Warm Season</option>
                                  <option value="Cool Season">Cool Season</option>
                              </select>
                          </div>
                      </div>
                  </div>

			  </div>
			  <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label col-lg-3">Back Yard Square Feet</label>
                          <div class="col-lg-9" style="padding-left: 11px;">
                              <input type="text" class="form-control yard_squre_feet" name="back_yard_square_feet" id="back_yard_square_feet" value=0 placeholder="Back Yard Square Feet">
                              <span style="color:red;"><?php echo form_error('back_yard_square_feet'); ?></span>
                          </div>
                      </div>
                  </div>
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Back Yard Grass Type</label>
					<div class="col-lg-9">
					  <select class="form-control" name="back_yard_grass" id="back_yard_grass">
						<option value="">Select Back Yard Grass Type</option>
						<option value="Bent">Bent</option>
						<option value="Bermuda">Bermuda</option>
						<option value="Dichondra">Dichondra</option>
						<option value="Fine Fescue">Fine Fescue</option>
						<option value="Kentucky Bluegrass">Kentucky Bluegrass</option>
						<option value="Ryegrass">Ryegrass</option>
						<option value="St. Augustine/Floratam">St. Augustine/Floratam</option>
						<option value="Tall Fescue">Tall Fescue</option>
						<option value="Zoysia">Zoysia</option>
						<option value="Centipede">Centipede</option>
						<option value="Bluegrass/Rye/Fescue">Bluegrass/Rye/Fescue</option>
						<option value="Warm Season">Warm Season</option>
						<option value="Cool Season">Cool Season</option>
					  </select>
					</div>
				  </div>
				</div>
			  </div>
			 <div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Property Difficulty Level</label>
					<div class="col-lg-9">
					  <select class="form-control" name="difficulty_level">
						<option value="">Select Difficulty Level</option>
						<option value="1">Level 1</option>
						<option value="2">Level 2</option>
						<option value="3">Level 3</option>
					  </select>
					</div>
				  </div>
				</div>
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Property Conditions</label>
					<div class="multi-select-full col-lg-9">
						<select class="multiselect-select-all-filtering form-control"
							name="property_conditions[]" multiple="multiple" id="property_conditions_list">
							<?php if(is_array($propertyconditionslist)){foreach($propertyconditionslist as $condition){?>
							<option value=<?= $condition->property_condition_id ?>><?= $condition->condition_name ?></option>
							<?php }} ?>
						</select>
					</div>
				  </div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3">Property Status</label>
					<div class="col-lg-9">
					  <select class="form-control" name="property_status" id="prospect_status" onchange="showDiv('hidden_source', this)">
						<option value="" >Select Any Status</option>
						<option value="2" <?= (set_value('property_status', 1) == 2)?'selected': '';?>>Prospect</option>
						<option value="1" <?= (set_value('property_status', 1) == 1)?'selected': '';?>>Active</option>
						<option value="0" <?= (set_value('property_status', 1) == 0)?'selected': '';?>>Non-Active</option>
                        <option value="3" <?= (set_value('property_status', 1) == 3)?'selected': '';?>>Estimate</option>
                		<option value="4" <?= (set_value('property_status', 1) == 4)?'selected': '';?>>Sales Call Scheduled</option>
                		<option value="5" <?= (set_value('property_status', 1) == 5)?'selected': '';?>>Estimate Sent</option>
                		<option value="6" <?= (set_value('property_status', 1) == 6)?'selected': '';?>>Estimate Declined</option>
					  </select>
					</div>
				  </div>
				</div>
				<div class="col-md-6">
				   <div class="form-group" style="margin-bottom: 4px;">
					 <label class="control-label col-lg-3 col-sm-12 col-xs-12">Assign Tags</label>
					 <div class="multi-select-full col-lg-9 col-sm-10 col-xs-10" style="padding-left: 14px;">
					   <select class="multiselect-select-all-filtering4 form-control" name="tags[]" multiple="multiple" id="tags_list" value="<?php echo set_value('tags[]') ?>">
						 <?php foreach ($taglist as $value) :  ?>
						   <option value="<?= $value->id ?>" <?php if($value->id == 1){ echo "selected"; }?> > <?= $value->tags_title ?></option>
						 <?php endforeach ?>
					   </select>
					   <span style="color:red;"><?php echo form_error('tags'); ?></span>
					 </div>

				   </div>
				 </div>
			<div class="row">
				<div class="col-lg-12 col-sm-12 col-xs-12 addbuttonmanage">
					   <div class="form-group">
					   </div>
			   </div>
			</div>
         	 </div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group" id="hidden_source">
						<label class="control-label col-lg-3">Source</label>
						<div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
						<select class="form-control" name="source" id="source"  value="" >
						<option value="">Select Source</option>
						<?php foreach ($source_list as $value) : ?>
							<option value="<?= $value->source_id ?>"><?= $value->source_name ?></option>
						<?php endforeach ?>
						</select>
							<span style="color:red;"><?php echo form_error('source'); ?></span>
						</div>
				  	</div>
				</div>
				<script>
				function showDiv(divId, element){
				  document.getElementById(divId).style.display = element.value == 2 ? 'block' : 'none';
				}
				</script>

			</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Property Info</label>
                        <div class="col-lg-9" style="padding-left: 5px;border: 1px solid #12689b;">
                            <textarea class="summernote_property" name="property_notes"> </textarea>
                        </div>
                    </div>

                </div>
				<div class="col-md-6">
				  <div class="form-group">
					<label class="control-label col-lg-3 col-sm-12 col-xs-12">Assign Program</label>
					<div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
					  <select class="multiselect-select-all-filtering2 form-control" name="assign_program_tmp[]" multiple="multiple" id="program_list" value="<?php echo set_value('assign_program') ?>">
						<?php foreach ($programlist as $value) : ?>
						  <option value="<?= $value->program_id ?>"><?= $value->program_name ?></option>
						<?php endforeach ?>
					  </select>
					  <span style="color:red;"><?php echo form_error('assign_program'); ?></span>
					</div>
				  </div>
				  <div class="program-price-over-ride-container" style="display: none;">
					<div class="table-responsive  pre-scrollable">
					  <table class="table table-bordered">
						<thead>
						  <tr>

							<th>Program Name</th>
							<th>Price Override</th>

						  </tr>
						</thead>
						<tbody class="priceoverridetbody">

						</tbody>
					  </table>
					</div>
				  </div>
				</div>
				<textarea name="assign_program" id="assign_program_ids2" style="display:none;">[]</textarea>
			  </div>

			<!-- Property Available Days -->
			<div class="row">
				<div class="col-md-6" style="margin: 16px auto">
					<div class="form-group">
						<label class="control-label col-lg-3">Property Available Days</label>
						<div class="col-lg-9" style="padding-left: 11px;">
                                <span style="color:red;">
                                    <?php echo form_error('checkbox_available_monday') ?>
									<?php echo form_error('checkbox_available_tuesday') ?>
									<?php echo form_error('checkbox_available_wednesday') ?>
									<?php echo form_error('checkbox_available_thursday') ?>
									<?php echo form_error('checkbox_available_friday') ?>
									<?php echo form_error('checkbox_available_saturday') ?>
									<?php echo form_error('checkbox_available_sunday') ?>
                                </span>

							<?php $daysOfWeek = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']; ?>

							<?php foreach($daysOfWeek as $day): ?>
								<?php $checkboxid = 'available_'.$day; $formattedDay=ucfirst($day); ?>
								<div class="col-md-6">
									<div id="subscribeTooltip" class="checkbox" data-popup="tooltip-custom"
										 title="Turning this option off will indicate this property is not available on this day"
										 data-placement="left">
										<label class="checkbox-inline checkbox-right">
											<input id="checkbox_<?= $checkboxid ?>" type="checkbox" name="checkbox_<?= $checkboxid ?>" class="switchery_checkbox_<?= $checkboxid ?>" checked>
											<?= $formattedDay ?></label>
									</div>
								</div>
							<?php endforeach; ?>

							<script>
								<?php foreach($daysOfWeek as $day): ?>
								<?php $checkboxid = 'available_'.$day; ?>
								var checkbox_<?= $checkboxid ?> = document.querySelector('.switchery_checkbox_<?= $checkboxid ?>');
								var switchery = new Switchery(checkbox_<?= $checkboxid ?>, {
									color: '#36c9c9', secondaryColor: "#dfdfdf",
								});
								<?php endforeach; ?>
							</script>

						</div>
					</div>
				</div>
			</div>
			<!-- \Property Available Days -->

		<!-- Start Measure Map Scaffolding -->
          <div class="row">
            <div class="col-md-6" style="margin: 16px auto">
              <div class="col-lg-5"></div>
              <div class="form-group">
                <a href="https://app.measuremaponline.com/" target="_blank" rel="noopener noreferrer" class="btn btn-info"><i class="icon-plus2"></i>Add Measure Map Online Lawn Measurement</a>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Measure Map ID</label>
                <div class="col-lg-9" style="padding-left: 11px;">
                  <input type="text" data-toggle="tooltip" title="You can copy this by simply opening your Measure Map project and clicking on the project name at the top of the screen. If you are accessing Measuremap Online from your mobile phone, you will need to turn your phone to landscape view to see the project name at the top of your screen. You can then tap to copy the project ID." class="form-control" name="measure_map_project_id" id="measure_map_project_id" value="<?php echo set_value('measure_map_project_id') ? set_value('measure_map_project_id') : ""; ?>" placeholder="Please enter the Measure Map Online Project ID" />
                  <span style="color:red;"><?php echo form_error('measure_map_project_id') ?></span>
                </div>
              </div>
            </div>
          </div>
          <!-- End Measure Map Scaffolding -->

		  </fieldset>
		  <div class="text-right btn-space">
			  <button type="submit" id="saveaddress" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
		  </div>
		</form>
	  </div>
	</div>
	<!-- /form horizontal -->
</div>
<!-- /content area -->
<div class="mydiv" style="display: none;"></div>

<!-- Primary modal -->
<div id="modal_add_service_area" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Add Service Area</h6>
      </div>

      <form action="<?= base_url('admin/setting/addServicrAreaData') ?>" method="post" name="addservicearea" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">


          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Service Area</label>
                <input type="text" class="form-control" name="category_area_name" placeholder="Service Area">
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="savearea" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /primary modal -->

<!-- Primary modal -->
<div id="modal_lat_long" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Add Coordinates</h6>
      </div>

        <div class="modal-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Latitude</label>
                        <input type="number" class="form-control" id="lat" placeholder="Latitude">
                    </div>
        
                    <div class="col-sm-6">
                        <label>Longitude</label>
                        <input type="number" class="form-control" id="long" placeholder="Longitude">
                    </div>
                    <div class="col-sm-1" style="margin-top: 30px;">
                        <a href="#" onclick="getCoordinates()" title="Get your coordinates(Latitude,Longitude)">
                            <i class="icon-reset text-success" style="padding-top:6px;font-size:25px;"></i>
                        </a>
                    </div>
                    <div id="coordinate-div"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" id="add_coordinates" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- /primary modal -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>&libraries=geometry,drawing,places&callback=initAutocomplete" async defer></script>
<script>
    var place;
    $(document).on("click", "#auto-assign-service-area", () => {
        var polygon_array = <?php print $polygon_bounds ? json_encode($polygon_bounds) : "";?>;
        console.log(polygon_array);
        console.log(place);

        polygon_array.forEach(elm => {

            var poly_draw = new google.maps.Polygon({ 
                paths: [JSON.parse(elm.latlng)]

            });

            if(typeof place.geometry['location'].lat === "function"){
                var lattitude = place.geometry['location'].lat();
                var longitude = place.geometry['location'].lng();
            }else{
                var lattitude = place.geometry['location'].lat;
                var longitude = place.geometry['location'].lng;
            }
            const propertyInPolygon = google.maps.geometry.poly.containsLocation({lat: lattitude, lng: longitude},poly_draw) ? 1 : 0;
            if(propertyInPolygon){
                $("select[name='property_area']").val(elm.property_area_cat_id);
            }
        });
    });
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
    autocomplete = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */
      (document.getElementById('autocomplete')), {
        types: ['geocode']
      });

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', function() {
      fillInAddress(autocomplete, "");
    });


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
    place = autocomplete.getPlace();

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

        //  alert(val);
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

        //alert(position.coords.latitude);
        autocomplete.setBounds(circle.getBounds());
      });
    }
  }
</script>
<script type="text/javascript">
  var selectedValues = [];
  var selectedTexts = [];
  var keyIds = [];
  var optionValue = '';
  var optionText = '';
  $n = 1;

  $(function() {

    reintlizeMultiselectprogramPriceOver();
	//tags	
	$('.multiselect-select-all-filtering4').multiselect({	
		includeSelectAllOption: true,	
		enableFiltering: true,	
		enableCaseInsensitiveFiltering: true,	
		includeSelectAllOption: false,	
		onInitialized: function(select, container) {	
			$(".styled, .multiselect-container input").uniform({	
				radioClass: 'checker'	
			});	
		}	
	});
	
  });


  function reintlizeMultiselectprogramPriceOver() {

    // alert();

    $(".multiselect-select-all-filtering2").multiselect('destroy');

    $('.multiselect-select-all-filtering2').multiselect({
      includeSelectAllOption: true,
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
      includeSelectAllOption: false,


      templates: {
        filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
      },

      onInitialized: function(select, container) {

        $(".styled, .multiselect-container input").uniform({
          radioClass: 'checker'
        });

      },


      onSelectAll: function() {

        $.uniform.update();
      },

      onChange: function(option, checked, select) {


        if (checked) {


          optionValue = $(option).val();

          if (optionValue != '') {


            if ($.inArray(optionValue, selectedValues) != '-1') {
              // alert('already');

            } else {

              $('.program-price-over-ride-container').css("display", "block");

              optionText = $(option).text();
              // alert(optionValue);
              //   alert(optionText);

              selectedValues.push(optionValue);

              keyIds.push({
                'program_id': optionValue,
                'price_override': 0,
                'is_price_override_set': null
              });


              selectedTexts.push(optionText);

              inputID = 'inpid' + $n;
              var $row = $('<tr id="trid' + optionValue + '">' +
                '<td>' + optionText + '</td>' +
                '<td> <input type="number" name="tmp' + $n + '" min="0"  class="inpcl form-control" optval="' + optionValue + '"  ></td>' +
                '</tr>');


              $('.priceoverridetbody:last').append($row);
              $n = $n + 1;
              // $('#assign_program_ids').val(selectedValues);


              $('#assign_program_ids2').val(JSON.stringify(keyIds));
            }
          }
        } else {

          var id = $(option).val();
          var optionValueRemove = $(option).val();
          var optionTextRemove = $(option).text();

          selectedValues.splice($.inArray(optionValueRemove, selectedValues), 1);

          selectedTexts.splice($.inArray(optionTextRemove, selectedTexts), 1);

          keyIds = $.grep(keyIds, function(e) {
            return e.program_id != optionValueRemove;
          });

          $("#trid" + id).remove();

          // $('#assign_program_ids').val(selectedValues);


          $('#assign_program_ids2').val(JSON.stringify(keyIds));

        }
      }
    });
  }

  $(document).on("input", ".inpcl", function() {

    inputvalue = $(this).val();
    program_id = $(this).attr('optval');

    $.each(keyIds, function(key, value) {
      if (program_id == value.program_id) {
        keyIds[key].price_override = inputvalue;
        if (inputvalue != "") {
          keyIds[key].is_price_override_set = 1;
        } else {
          keyIds[key].is_price_override_set = null;
        }
      }
      // alert( key + ": " + value.program_id );
    });

    $('#assign_program_ids2').val(JSON.stringify(keyIds));

  });

  $('#confirmation-button').click(function() {
      $('#addproperty').submit();
  });

  $(document).on('click','#add_coordinates', function (e) {
      e.preventDefault();
      var lat = $('#lat').val();
      var long = $('#long').val();
      if(!lat || !long){
          alert("Latitude & Longitude are required!");
          return false;
      }
      getReverseGeocodingData(lat,long);
      $('#modal_lat_long').modal('toggle');
  });
  function getReverseGeocodingData(lat, lng) {
      var latlng = new google.maps.LatLng(lat, lng);
      // This is making the Geocode request
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({ 'latLng': latlng }, function (results, status) {
          if (status !== google.maps.GeocoderStatus.OK) {
              alert(status);
          }
          // This is checking to see if the Geoeode Status is OK before proceeding
          if (status == google.maps.GeocoderStatus.OK) {
              console.log(results);
              var address = (results[0].formatted_address);
              // console.log(address);
              document.getElementById("autocomplete").value = address;
          }
      });
  }

  function getCoordinates() {
      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition);
      } else {
          $('#coordinate-div').html("Geolocation is not supported by this browser.");
      }
  }
  function showPosition(position) {
      $('#lat').val(position.coords.latitude);
      $('#long').val(position.coords.longitude);
  }

$('#use_billing_address').on('change',function(){
    var billing_adress = <?=json_encode($customerData)?>;

    if($(this).prop("checked") == true){

      var geocode_url = 'https://maps.googleapis.com/maps/api/geocode/json?key=<?=GoogleMapKey?>&address='+billing_adress.billing_street+billing_adress.billing_city+billing_adress.billing_state+billing_adress.billing_zipcode;

      fetch(geocode_url)
      .then(response => response.json())
      .then(data => {
        // console.log(data)
            place = data.results[0];
            if(!data.results[0].formatted_address.substring(0,6).toUpperCase().match(billing_adress.billing_street.substring(0,6).toUpperCase())){
              alert('Please enter a valid property address');
            }else{
              $("input[name=property_address]").val(data.results[0].formatted_address);
              $("input[name=property_address_2]").val(billing_adress.billing_street_2);
              $("input[name=property_city]").val(billing_adress.billing_city);
              $("select[name=property_state]").val(billing_adress.billing_state);
              $("input[name=property_zip]").val(billing_adress.billing_zipcode);
              
            }
      })

    }else{
      $("input[name=property_address]").val("");
      $("input[name=property_address_2]").val("");
      $("input[name=property_city]").val("");
      $("select[name=property_state]").val("");
      $("input[name=property_zip]").val("");
    }
  });
$('#customer_list').on('change',function(){
	var is_group_billing = 0;
	let customers = $(this).val();
	let selected_customers = JSON.stringify(customers);
	selected_customers = JSON.parse(selected_customers);
	$.each(selected_customers, function(key,customer){
		var getOption = $('#customer_list option[value='+customer+']').data('billingtype');
		if(getOption == 1){
			is_group_billing = 1;
		}
	});
	if(is_group_billing == 1){
		$('div.group_billing_contact_info').show();
	}else{
		$('div.group_billing_contact_info').hide();
	}
	$('input[name="is_group_billing"]').val(is_group_billing);

  });
</script> <!-- /content area -->
<script type="text/javascript">
$(function(){
    var property_is_email = document.querySelector('.switchery-property-is-email');
	  var switchery = new Switchery(property_is_email, {
		color: '#36c9c9',
		secondaryColor: "#dfdfdf",
	  });
	var property_is_text = document.querySelector('.switchery-is-text');
	  var switchery = new Switchery(property_is_text, {
		color: '#36c9c9',
		secondaryColor: "#dfdfdf",
	  });
    var use_billing_address = document.querySelector('.switchery-same-as-billing-address');
	  var switchery = new Switchery(use_billing_address, {
		color: '#36c9c9',
		secondaryColor: "#dfdfdf",
	  });
	var prospect_status = document.querySelector('.switchery-prospect-status');
    // console.log(is_mobile);
	  var switcheryIsMobile = new Switchery(prospect_status, {
      color: '#36c9c9',
      secondaryColor: "#dfdfdf",
	  });
});
function reset_confirmation(){
    $('.alert-dismissible').hide();
    $('#confirmation').val(0);


}
</script>
