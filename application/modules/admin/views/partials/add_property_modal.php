<style type="text/css">
	#hidden_source {
		display: none;
	}
</style>
<div id="modal_add_property" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title">Add Property</h6>
			</div>
			<div class="modal-body">
				<form id="addPropertyModal" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="confirmation" name="confirmation" value="0">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Property Name</label>
								<input type="text" class="form-control" name="property_title" placeholder="Property Name" required/>
							</div>
						</div>
					</div>
					<?php if(isset($customerData['billing_type']) && $customerData['billing_type'] == 1){ ?>
					<div class="row group_billing_contact_info">
                        <input type="hidden" name="is_group_billing" value="1"/>
						<div class="col-md-6">
							<div class="form-group">
								<label>Property First Name</label>
								<input type="text" class="form-control" name="property_first_name" value="" placeholder="First Name" required>
                                <span style="color:red;"><?php echo form_error('property_first_name'); ?></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Property Last Name</label>
								<input type="text" class="form-control" name="property_last_name" value="" placeholder="Last Name" required>
                                <span style="color:red;"><?php echo form_error('property_last_name'); ?></span>
							</div>
						</div>
					</div>
					<div class="row group_billing_contact_info">
						<div class="col-md-9">
							<div class="form-group">
								<label>Property Phone</label>
								<input type="text" class="form-control" name="property_phone" value="" placeholder="Phone" required>
								<span style="color:red;"><?php echo form_error('property_phone'); ?></span>
                                <span>Please do not use dashes</span>
							</div> 
						</div>
                        <div class="col-md-3">
							<div class="form-group">
                                <div class="checkbox" style="margin-top:35px;">
                                    <label class="checkbox-inline checkbox-right">
                                        <input type="checkbox" name="property_is_text" class="switchery-is-text" />&nbsp;Opt-In
                                    </label>
                                </div>
							</div>
						</div>
					</div>
					<div class="row group_billing_contact_info">
						<div class="col-md-9">
							<div class="form-group">
								<label>Property Email</label>
								<input type="text" class="form-control" name="property_email" value="" placeholder="Email" required>
                                <span style="color:red;"><?php echo form_error('property_email'); ?></span>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
                                <div class="checkbox" style="margin-top:35px;">
                                    <label class="checkbox-inline checkbox-right">
                                        <input type="checkbox" name="property_is_email" class="switchery-property-is-email" />&nbsp;Opt-In
                                    </label>
                                </div>
							</div>
						</div>
					</div>
					<?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox" style="margin-top:35px;">
                                    <label class="checkbox-inline checkbox-right">
                                        <input type="checkbox" name="use_billing_address" id="autofill" class="switchery-same-as-billing-address" />&nbsp;Same as Billing Address
                                    </label>
                                </div>
							</div>
                        </div>
                    </div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Address</label>
								<input type="text" class="form-control" name="property_address" id="autocomplete2" onFocus="geolocate()"  placeholder="Address" onkeydown="keydownAddress2()" required/>
							</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address 2</label>
                                <input type="text" class="form-control" name="property_address_2" id="property_address_2" placeholder="Address 2">
                                <div id="map"></div>
							    <input type="hidden" name="property_latitude" id="latitude" />
                   			    <input type="hidden" name="property_longitude" id="longitude" />
                            </div>
                        </div>
					</div>         
					<div class="row">
                        <div class="col-md-6">
							<div class="form-group">
								<label>City</label>
								<input type="text" class="form-control" name="property_city" id="locality2" placeholder="City" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>State / Territory</label>
									<select class="form-control" id="region2" name="property_state" required>
										<option value="">Select State</option>
										<optgroup label="Canadian Provinces">
											<option value="AB">Alberta</option>
											<option value="BC">British Columbia</option>
											<option value="MB">Manitoba</option>
											<option value="NB">New Brunswick</option>
											<option value="NF">Newfoundland</option>
											<option value="NT">Northwest Territories</option>
											<option value="NS">Nova Scotia</option>
											<option value="NU">Nunavut</option>
											<option value="ON">Ontario</option>
											<option value="PE">Prince Edward Island</option>
											<option value="QC">Quebec</option>
											<option value="SK">Saskatchewan</option>
											<option value="YT">Yukon Territory</option>
										</optgroup>										
										<optgroup label="U.S. States/Territories">
											<option value="AL">Alabama</option>
											<option value="AK">Alaska</option>
											<option value="AZ">Arizona</option>
											<option value="AR">Arkansas</option>
											<option value="CA">California</option>
											<option value="CO">Colorado</option>
											<option value="CT">Connecticut</option>
											<option value="DE">Delaware</option>
											<option value="DC">District Of Columbia</option>
											<option value="FL">Florida</option>
											<option value="GA">Georgia</option>
											<option value="HI">Hawaii</option>
											<option value="ID">Idaho</option>
											<option value="IL">Illinois</option>
											<option value="IN">Indiana</option>
											<option value="IA">Iowa</option>
											<option value="KS">Kansas</option>
											<option value="KY">Kentucky</option>
											<option value="LA">Louisiana</option>
											<option value="ME">Maine</option>
											<option value="MD">Maryland</option>
											<option value="MA">Massachusetts</option>
											<option value="MI">Michigan</option>
											<option value="MN">Minnesota</option>
											<option value="MS">Mississippi</option>
											<option value="MO">Missouri</option>
											<option value="MT">Montana</option>
											<option value="NE">Nebraska</option>
											<option value="NV">Nevada</option>
											<option value="NH">New Hampshire</option>
											<option value="NJ">New Jersey</option>
											<option value="NM">New Mexico</option>
											<option value="NY">New York</option>
											<option value="NC">North Carolina</option>
											<option value="ND">North Dakota</option>
											<option value="OH">Ohio</option>
											<option value="OK">Oklahoma</option>
											<option value="OR">Oregon</option>
											<option value="PA">Pennsylvania</option>
											<option value="RI">Rhode Island</option>
											<option value="SC">South Carolina</option>
											<option value="SD">South Dakota</option>
											<option value="TN">Tennessee</option>
											<option value="TX">Texas</option>
											<option value="UT">Utah</option>
											<option value="VT">Vermont</option>
											<option value="VA">Virginia</option>
											<option value="WA">Washington</option>
											<option value="WV">West Virginia</option>
											<option value="WI">Wisconsin</option>
											<option value="WY">Wyoming</option>
										</optgroup>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
                        <div class="col-md-6">
							<div class="form-group">
								<label>Postal Code</label>
								<input type="text" class="form-control" id="postal-code2" name="property_zip" placeholder="Postal Code" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Service Area</label>
								<select class="form-control" name="property_area" value="<?php echo set_value('property_area')?>">
									<option value="">Select Area</option>
									<?php if (!empty($propertyarealist)) { foreach ($propertyarealist as $value){ ?>
										  <option value="<?= $value->property_area_cat_id ?>"><?= $value->category_area_name ?></option>  
									<?php } }?>
								</select>
                                <br />
                                <div class="btn btn-success m-y-1" id="auto-assign-service-area">Auto Assign Service Area</div>
							</div>
						</div>
                    </div>
                    <div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Property Type</label>
								<div class="row">
									<div class="col-md-6">
										<label class="radio-inline"><input name="property_type" value="Commercial" type="radio" checked="checked" />Commercial</label>
									</div>
									<div class="col-md-6">
										<label class="radio-inline"><input name="property_type" value="Residential" type="radio" />Residential</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group multi-select-full">
								<label>Property Difficulty Level</label>
								<select class="form-control" name="difficulty_level">
									<option value="">Select Difficulty Level</option>
									<option value="1">Level 1</option>
									<option value="2">Level 2</option>
									<option value="3">Level 3</option>
								 </select>
							</div>
						</div>
						<div class="col-md-6" style="display:<?= $setting_details->is_sales_tax==1 ? 'block' : 'none' ?>">
							<div class="form-group multi-select-full">
								<label>Sales Tax Area</label>
								<select class="multiselect-select-all-filtering form-control" name="sale_tax_area_id[]" multiple="multiple" id="sales_tax_modal" >
								<?php if (!empty($sales_tax_details)) { foreach ($sales_tax_details as $key => $value) { ?>    
                        			<option value="<?= $value->sale_tax_area_id ?>" <?= ( isset($setting_details->default_sales_tax_area) && in_array($value->sale_tax_area_id,  json_decode($setting_details->default_sales_tax_area)))?'selected':'' ?> ><?= $value->tax_name  ?>  </option>
                        		<?php  } } ?>
                      			</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Total Yard Square Feet</label>
								<input type="text" class="form-control" name="yard_square_feet" id="yard_square_feet" placeholder="Yard Square Feet" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Total Yard Grass Type</label>
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
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Front Yard Square Feet</label>
								<input type="text" class="form-control" name="front_yard_square_feet" id="front_yard_square_feet" placeholder="Front Yard Square Feet">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Front Yard Grass Type</label>
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
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Back Yard Square Feet</label>
								<input type="text" class="form-control" name="back_yard_square_feet" id="back_yard_square_feet" placeholder="Back Yard Square Feet">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Back Yard Grass Type</label>
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
					<div class="row">
						<div class="col-md-6">
							<div class="form-group multi-select-full">
                               <label>Assign Tags</label>
                               <select class="multiselect-select-all-filtering form-control" name="tags[]" multiple="multiple" id="tags_list" value="<?php echo set_value('tags[]') ?>">
                                 <?php foreach ($taglist as $value) :  ?>
                                   <option value="<?= $value->id ?>" <?php if($value->id == 1){ echo "selected"; }?> > <?= $value->tags_title ?></option>
                                 <?php endforeach ?>
                               </select>  
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group multi-select-full">
								<label>Property Conditions</label>
								<select class="multiselect-select-all-filtering form-control" name="property_conditions[]" multiple="multiple" id="property_conditions_list">
									<?php if(is_array($propertyconditionslist)){foreach($propertyconditionslist as $condition){?>
									<option value=<?= $condition->property_condition_id ?>><?= $condition->condition_name ?></option>
									<?php }} ?>
								</select>
							
							</div>
						</div>
					</div>
                    <div class="row">
                        <div class="col-md-6">
							<div class="form-group">
								<label>Property Status</label>
								<select class="form-control" name="property_status" onchange="showDiv('hidden_source', this)">
									<option value="">Select Any Status</option>
									<option value="2">Prospect</option>
									<option value="1" selected>Active</option>
									<option value="0">Non-Active</option>
								</select>  
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group multi-select-full">
								<label>Assign Program</label>
								<select class="multiselect-select-all-filtering form-control" name="assign_program[]" multiple="multiple" id="program_list" value="<?php echo set_value('assign_program') ?>">
								<?php foreach($programlist as $value){ ?>
									<option value="<?= $value->program_id ?>"><?= $value->program_name ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
					</div>
                    <div class="row">
                        <div class="col-md-6">
							<div class="form-group" id="hidden_source">
								<label>Source</label>
								<div class="multi-select-full ">
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
                    </div>
					<script>
						function showDiv(divId, element){
							document.getElementById(divId).style.display = element.value == 2 ? 'block' : 'none';
						}
					</script>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Property Info</label>
								<div style="border: 1px solid #12689b;"><textarea class="summernote_property" name="property_notes"></textarea></div>
							</div>
						</div>
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

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">

							</div>
						</div>
					</div>

					<div class="addcustomeridinmodal"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      			<button type="submit" id="add-property-submit" class="btn btn-success">Save</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
// Validate Postal Code
$("form[id='addPropertyModal']").validate({
	// Specify validation rules
	rules: {
		property_zip: {
		required: true,
		validationUSzipcodeCApostcode: true,
		},
	},
	messages: {
		property_zip: {
		required: 'Please enter a Postal Code',
		},      
	},
});

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
$('form#addPropertyModal #add-property-submit').click(function(e){
    e.preventDefault();
    let data = $('form#addPropertyModal').serialize();
    $.ajax({
        type: 'POST',
        url: "<?=base_url('admin/addPropertyDataJson')?>",
        data: data,
        dataType: "JSON",
        success: function(data){
            if(data.status == 200){
                swal({title: "Success!", text: "Property Added Successfully!", type: 
                    "success"}).then(function(){ 
                    location.reload();
                    }
                )
                $('#modal_add_property').modal('hide');
            }else if(data.status == 400){
                if(data.msg){

                    var msg = data.msg;
                }else{
                    msg = "Something went wrong. Please try again.";
                }
                swal({
                    confirmButtonColor: '#d9534f',
                    type: 'error',
                    title: 'Oops...',
                    html: msg
                });
            }else  if(data.status == 401 ){
                if(data.msg){
                    var msg = data.msg;
                }else{
                    msg = "Something went wrong. Please try again.";
                }
                swal({
                    confirmButtonColor: '#28a745',
                    type: 'warning',
                    title: 'Oops...',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    cancelButtonColor: '#d9534f',
                    html: msg
                }).then(function(result) {
                    console.log(result);
                    if (result['value']){
                        $('#confirmation').val(1);
                        $('form#addPropertyModal #add-property-submit').click();
                    }

                });
            } else{
                swal({
                    confirmButtonColor: '#d9534f',
                    type: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.'
                });
            } 
        }
    });
});
</script>