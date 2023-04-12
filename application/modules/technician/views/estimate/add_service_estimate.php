

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

  th , td {
  text-align: center;
  }
  .pre-scrollable {
   min-height: 0px;
  }

  .radio-inline {
    color: #333 !important;
  }

  @media (min-width: 769px){
.form-horizontal .control-label[class*=col-sm-] {
    padding-top: 0;
}}

</style>


<!-- Content area -->
<div class="content form-pg ">
   <!-- Form horizontal -->
   <div class="panel panel-flat">
      <div class="panel-heading">
         <h5 class="panel-title">
            <div class="form-group">
               <a href="<?= base_url('technician/Estimates/addEstimate/').$propertyID ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to Add Estimates</a>
            </div>
         </h5>
      </div>
      <br>
      <div class="panel-body">

         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
         <form class="form-horizontal" action="<?= base_url('technician/Estimates/addServiceEstimateData')  ?>" method="post" name="addestimate" enctype="multipart/form-data" id="main_add_service_estimate_data_form" >
            <fieldset class="content-group">
               <div class="row invoice-form">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-md-3 ">Select Customer</label>
                        <div class="col-md-8   search_sel">
                           <select class="bootstrap-select form-control" data-live-search="true" name="customer_id" id="customer_id">
                              <option value="">Select any customer</option>
                              <?php if (!empty($customer_details)) {
                                 foreach ($customer_details as $key => $value) {
                                  if ($selectedProperty[0]->customer_id==$value->customer_id) {
                                    $selected ='selected';
                                  } else {
                                    $selected = '';
                                  }
                                  // echo '<option value="'.$value->customer_id.'" selected >'.$value->first_name.' '.$value->last_name.'</option>';
                                   echo '<option value="'.$value->customer_id.'" '.$selected.'>'.$value->first_name.' '.$value->last_name.'</option>';
                                 }
                                 } ?>
                           </select>
                        </div>

                         <div class="col-md-1  addbuttonmanage">
                      <div class="form-group">
                      <center>
                         
                       <a href="#" data-toggle="modal" data-target="#modal_add_customer"><i class="icon-add text-success" style="padding-top:6px;font-size:25px;" ></i></a>
                        
                      </center>
                        </div>
                    </div>

                    </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group row">
                        <label class="control-label col-md-3 ">Select Property Address</label>
                        <div class="col-md-8 search_sel">
                           <select class="bootstrap-select form-control" data-live-search="true" name="property_id" id="property_id">
                           <?php 

                            if (!empty($propertylist)) {
                              foreach ($propertylist as $value) {

                                if ($selectedProperty[0]->property_id==$value->property_id) {
                                  $selected ='selected';
                                } else {
                                  $selected = '';
                                }                            
                                echo '<option value="'.$value->property_id.'" '.$selected.' >'.$value->property_title.'</option>';
                              }

                            }

                            ?>

                           </select>       
                        </div>
                          <div class="col-md-1 addbuttonmanage">
                          <div class="form-group">
                          <center>
                                         
                             <a href="#" data-toggle="modal" data-target="#modal_add_property"><i class="icon-add text-success" style="padding-top:6px;font-size:25px;" ></i></a>
                            
                          </center>
                            </div>
                        </div>
                     



                     </div>
                  </div>
               </div>
               <div class="row invoice-form">

                   <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Customer Email</label>
                        <div class="col-lg-9">
                           <input type="text" name="customer_email" class="form-control" value="" readonly="" id="customer_email" placeholder="Customer Email" >
                        </div>
                     </div>
                  </div>

                 
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Estimate Date</label>
                        <div class="col-lg-9">
                           <input type="text" name="estimate_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD" >
                        </div>
                     </div>
                  </div>

               </div>
              
               <div class="row invoice-form">
                   <div class="col-md-6">
					  <div class="form-group" id="service_select">
                        <label class="control-label col-lg-3">Service List</label>
                        <div class="col-lg-9 search_sel">
                           <select class="bootstrap-select form-control" data-live-search="true" name="standalone_job_id" id="select_service_id" >
                              <option value="" >Select any Service </option>
                              <?php 
                                 if (!empty($service_details)) {
                                   foreach ($service_details as $key => $value) {
                                     echo '<option value="'.$value->job_id.'" >'.$value->job_name.'</option>';
                                   }
                                 }
                                 
                                  ?>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mr-bt">
                    <label class="control-label col-lg-3">Status</label>
                     <div class="form-group"> 
                      <div class="col-lg-4">
                          <label class="radio-inline">
                               <input name="status" value="0" type="radio" checked="checked">Save as Draft
                           </label>
                      </div>
                      <div class="col-lg-4">
                           <label class="radio-inline">
                                <input name="status" value="1" type="radio">Send Estimate
                           </label>
                       </div>
                      </div>            
                    </div>
                  </div>
               </div>
               <div class="row" class="invoice-form">
				 <div class="col-md-6">
					 <div class="form-group">
						<label class="control-label col-lg-3">Price Override</label>
                        <div class="col-lg-9">
                           <input type="number" class="form-control"  min=0 name="price_override" value="" placeholder="(Optional) Enter Price Override Here" id="price_override_input">
                           <input type="hidden" name="price_override_error" value="0" id="price_override_error_inpt" />
                           <span style="display: none; color: rgb(221, 51, 51); font-size: 16px;" id="price_override_input_err">Please input a non-zero positive price</span>
                        </div>

                        <script>
                            document.querySelector("#price_override_input").addEventListener("keyup", function() {
                                if (this.value == "") {
                                    document.querySelector("#price_override_input_err").style.display = "none";
                                    document.querySelector("#price_override_error_inpt").value = "0";
                                } else if (this.value <= 0) {
                                    document.querySelector("#price_override_input_err").style.display = "unset";
                                    document.querySelector("#price_override_error_inpt").value = "1";
                                } else {
                                    document.querySelector("#price_override_input_err").style.display = "none";
                                    document.querySelector("#price_override_error_inpt").value = "0";
                                }
                            })
                        </script>
					 </div>
                  </div>
                 <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label col-lg-3">Message to Customer&nbsp;<span data-popup="tooltip-custom" title="This message will also be included in the email to the customer." data-placement="top"> <i class=" icon-info22 tooltip-icon"></i> </span></label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="notes" placeholder="Enter Message">
							<p>* this message will be sent to the customer</p>
                        </div>
                     </div>
                  </div>
               </div>
			   <div class="row" class="invoice-form">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-lg-3">Pricing</label>
              <div class="col-lg-9">
                <select class="form-control" name="program_price" id="program_price">
                  <option value="">Select Any Pricing</option>
                  <option value=1>One-Time Service Invoicing</option>
                  <option value=2>Invoiced at Service Completion</option>
                  <option value=3>Manual Billing</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-lg-3 col-sm-12 col-xs-12">Apply Coupons</label>
              <div class="multi-select-full col-lg-9" style="padding-left: 4px;">
                <select class="multiselect-select-all-filtering form-control" name="assign_onetime_coupons[]" id="" multiple="multiple">
                  <?php foreach ($customer_one_time_discounts as $value): ?>

                        <?php
                        // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                        $expiration_pass = true;
                        if ($value->expiration_date != "0000-00-00 00:00:00") {
                            $coupon_expiration_date = strtotime( $value->expiration_date );

                            $now = time();
                            if($coupon_expiration_date < $now) {
                                  $expiration_pass = false;
                                  $expiration_pass_global = false;
                            }
                        }

                        if ($expiration_pass == true) {?>
                       <option value="<?= $value->coupon_id ?>"> <?= $value->code ?> </option>
                      <?php } ?>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="row invoice-form">
          <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-lg-3">Property Status</label>
                <div class="col-lg-9">
                  <select class="form-control" name="property_status" id="property_status" onchange="showDiv('hidden_source', this)">
                    <option value="">Select Any Status</option>
                    <option value="1">Prospect</option>
                    <option value="0">Active</option>
                  </select>
                </div>
            </div>
          </div>

            <!-- <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Sales Rep</label>
                <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
                <select class=" form-control" name="sales_rep"  id="sales_rep"  value="<?php echo set_value('sales_rep') ?>">
                    <option value="">Select Sales Rep</option>
                    <?php foreach ($users as $value) : ?>
                    <option value="<?= $value->id ?>"><?= $value->user_first_name.' '.$value->user_last_name.' : '.$value->id ?></option>
                    <?php endforeach ?>
                  </select>
                    <span style="color:red;"><?php echo form_error('assign_program'); ?></span>
                </div>
              </div>
            </div> -->
        </div>
               
            </fieldset>
            <div class="text-center">
              <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i>
              </button>
            </div>
          </form>
      </div>
   </div>
</div>
<!-- /form horizontal -->


 <!-- Primary modal -->
          <div id="modal_add_customer" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">

                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Add Customer</h6>
                </div>

                <form  name="addcustomer" id="my_form"  action="<?= base_url('technician/addCustomerData') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax" class="redirection" >

                 <div class="modal-body">
                          
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6 col-sm-6">                 
                           <label>First Name</label>
                           <input type="text" class="form-control" name="first_name" placeholder="First Name">        
                        </div>
                        <div class="col-md-6 col-sm-6">                   
                          <label>Last Name</label>
                          <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6 col-sm-6"> 
                          <label>Company Name</label>
                            <input type="text" class="form-control" name="customer_company_name" placeholder="Company Name">
                        </div>
                       
                        <div class="col-md-6 col-sm-6"> 
                          <label>Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Email">
                        </div>

                      </div>
                    </div>

                      

                     <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6"> 
                           <label class="">Mobile</label>
                             <input type="text" class="form-control" name="phone" value="" placeholder="Mobile">
                               <span>Please do not use dashes</span>
                          </div>
                         
                          <div class="col-md-6 col-sm-6"> 
                           <label class="">Home</label>
                             <input type="text" class="form-control" name="home_phone" value="" placeholder="Home">
                              <span>Please do not use dashes</span>

                          </div>          
                        
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6"> 
                           <label class="">Work</label>
                             <input type="text" class="form-control" name="work_phone" value="" placeholder="Work">
                               <span>Please do not use dashes</span>
                             
                          </div>
                         
                          <div class="col-md-6 col-sm-6"> 
                            <label>Billing Address</label>
                           
                            <input type="text" class="form-control" name="billing_street"  value="" placeholder="Address" id="autocomplete2" onFocus="geolocate()" >
                          </div>

                        </div>
                    </div>




                            




                     <div class="form-group">
                        <div class="row">
                          
             
                          <div class="col-md-6 col-sm-6">
                            <label>Billing Address 2</label>
                              <input type="text" class="form-control" name="billing_street_2" placeholder="Address 2">
                          </div>
                         <div class="col-md-6 col-sm-6"> 
                           <label>City</label>
                            <input type="text" class="form-control" name="billing_city" placeholder="City" id="locality2" > 
                        </div>

                        </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                     
                        <div class="col-md-6 col-sm-6">
                         
                            <label>Billing State</label>
                             <select class="form-control" name="billing_state" id="region2">
                              <option value="">Select State</option>
                            
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

                              </select>                      
                        </div>

                        <div class="col-md-6 col-sm-6"> 
                            <label>Zip Code</label>
                             <input type="text" class="form-control" name="billing_zipcode" placeholder="Zip Code" id="postal-code2" >
                        </div>
                          




                      </div>
                    </div>


                    <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                              <label class="">Assign Properties</label>
                              <div class="multi-select-full">
                               <select class="multiselect-select-all-filtering form-control" name="assign_property[]" multiple="multiple" id="property_list" value="<?php echo set_value('assign_property[]')?>">
                                    
                                <?php if ($propertylist) {
                                  
                                 foreach ($propertylist as $value) { ?>
                                    <option value="<?= $value->property_id ?>"><?= $value->property_title ?></option>  
                                <?php }} ?>
                                </select>
                               
                             </div>
                            </div>
                          </div>


                          
                          <div class="col-sm-6 col-md-6"> 
                            <label>Customer Status</label>
                              <select  class="form-control" name="customer_status">
                                <option value="" >Select Any Status</option>
                                <option value="1">Active</option>
                                <option value="0">Non-Active</option>
                              </select>
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
          <!-- /primary modal -->


              <!-- Primary modal -->
          <div id="modal_add_property" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Add Property</h6>
                </div> 

              <form  name="addproperty" id="my_form"  action="<?= base_url('technician/addPropertyDataJson') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax" class="redirection" >

                  <div class="modal-body">
                    
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6 col-sm-6">                 
                     <label>Property Name</label>
                     <input type="text" class="form-control" name="property_title" placeholder="Property Name">                      
                  </div>
                  <div class="col-md-6 col-sm-6">                   
                    <label>Address</label>
                  <input type="text" class="form-control" name="property_address" id="autocomplete" onFocus="geolocate()" placeholder="Address">
                  </div>
                </div>
                 <div id="map" ></div>
                   <input type="hidden" name="property_latitude" id="latitude" />
                   <input type="hidden" name="property_longitude" id="longitude" />
              </div>
 
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6"> 
                            <label>Address 2</label>
                              <input type="text" class="form-control" name="property_address_2" placeholder="Address 2">
                          </div>
                          <div class="col-md-6 col-sm-6">
                            <label>City</label>
                              <input type="text" class="form-control" name="property_city" placeholder="City" id="locality" >
                          </div>
                        </div>
                      </div>

                       <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6"> 
                            <label>State</label>
                              <select class="form-control" name="property_state" id="region" >
                                <option value="">Select State</option>
                              
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

                              </select>
                          </div>
                          <div class="col-md-6 col-sm-6">
                            <label>Zip Code</label>
                              <input type="text" class="form-control" name="property_zip" placeholder="Zip Code" id="postal-code" >
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-6 col-sm-6"> 
                            <label>Service Area</label>
                              <select class="form-control" name="property_area" value="<?php echo set_value('property_area')?>">
                                <option value="">Select Any Service Area</option>
                              <?php if (!empty($propertyarealist)) {
                                
                               foreach ($propertyarealist as $value){ ?>
                                  <option value="<?= $value->property_area_cat_id ?>"><?= $value->category_area_name ?></option>  
                              <?php } }?>
                              </select>
                          </div>
                          <div class="col-md-6 col-sm-6">
                            <div class="col-md-12 col-sm-12">
                              <label>Property Type</label>
                            </div>
                              <div class="col-md-6 col-sm-6">
                                  <label class="radio-inline">
                                       <input name="property_type" value="Commercial" type="radio" checked="checked" />Commercial
                                   </label>
                              </div>
                              <div class="col-md-6 col-sm-6">
                                   <label class="radio-inline">
                                        <input name="property_type" value="Residential" type="radio" />Residential
                                   </label>
                               </div>
                          </div>
                        </div>
                      </div>
						<div class="form-group">
						<div class="row">
						  <div class="col-md-6 col-sm-6">
							<label class="control-label">Property Difficulty Level</label>
							<div class="multi-select-full">
							  <select class="form-control" name="difficulty_level">
								<option value="">Select Difficulty Level</option>
								<option value="1">Level 1</option>
								<option value="2">Level 2</option>
								<option value="3">Level 3</option>
							  </select>
							</div>
						  </div>
						 <div class="col-sm-6 col-md-6" style="display:<?= $setting_details->is_sales_tax==1 ? 'block' : 'none' ?> " >
                              <label>Sales Tax Area</label>


                              <div class="multi-select-full" >
                                 <select class="multiselect-select-all-filtering form-control" name="sale_tax_area_id[]" multiple="multiple" id="sales_tax" >

                                    <?php if (!empty($sales_tax_details)) { 
                                      foreach ($sales_tax_details as $key => $value) {
                                    ?>    
                                    <option value="<?= $value->sale_tax_area_id ?>"  ><?= $value->tax_name  ?>  </option>
                                    <?php  } } ?>
                                   
                                 </select>
                                
                              </div>


                                
                            </div>

						</div>
					  </div>
					  <div class="form-group">
						<div class="row">
						  <div class="col-md-6 col-sm-6">
							<label>Total Yard Square Feet</label>
							<input type="text" class="form-control" name="yard_square_feet" id="yard_square_feet" placeholder="Yard Square Feet">
						  </div>
						  <div class="col-md-6 col-sm-6">
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

					  <div class="form-group">
						<div class="row">
						  <div class="col-md-6 col-sm-6"> 
							<label>Front Yard Square Feet</label>
							 <input type="text" class="form-control" name="front_yard_square_feet" id="front_yard_square_feet" placeholder="Front Yard Square Feet">
						  </div>
						  <div class="col-md-6 col-sm-6">
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
					  <div class="form-group">
						<div class="row">
						  <div class="col-md-6 col-sm-6"> 
							<label>Back Yard Square Feet</label>
							 <input type="text" class="form-control" name="back_yard_square_feet" id="back_yard_square_feet" placeholder="Back Yard Square Feet">
						  </div>
						  <div class="col-md-6 col-sm-6">
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
                
                      <div class="form-group">
                        <div class="row">
                         <div class="col-md-6 col-sm-6">

                          <label class="">Assign Customer</label>
                              <div class="multi-select-full">
                                <select class="multiselect-select-all-filtering form-control" name="assign_customer[]" multiple="multiple" id="customer_list">
                                  
                                <?php if (!empty($customerlist)) {
                                
                                 foreach ($customerlist as $value) { ?>
                                    <option value="<?= $value->customer_id ?>"><?= $value->first_name ?> <?= $value->last_name ?></option>  
                                
                                <?php }} ?>
                                
                                </select>

                             </div>                           
                         </div> 
							 <div class="col-md-6 col-sm-6">
                            <label>Property Status</label>
                            <select class="form-control" name="property_status">
                                <option value="">Select Any Status</option>
                                <option value="1">Active</option>
                                <option value="0">Non-Active</option>
                              </select>                    
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="row" >
                          <div class="col-md-12 col-sm-12">
                              <label>Property Info</label>
                                <textarea class="form-control" name="property_notes" rows="3"></textarea>
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
          <!-- /primary modal -->

      <div class="mydiv" style="display: none;">
            
          </div>



<!-- /content area -->
<script type="text/javascript">


     var keyIds = [];

  $(document).ready(function(argument) {
  //  var pr_id =  '<?=  isset($_GET['pr_id']) ? $_GET['pr_id'] : ""  ?>';
   var pr_id =  '<?=  $this->uri->segment(4)  ?>';
    if (pr_id!='') {
 
      $.ajax({
        type: "GET",
        url: "<?= base_url('technician/Estimates/GetAllCustomerByProperty/')  ?>"+pr_id,
        dataType: "JSON"  
      }).done(function(data){ 
        // console.log(data);
          var items = [];
        if (data.status==200) {
              $.each( data.result, function( key, val ) {
              console.log(key);
              if (key==0) {
                getemail(val.customer_id)
              }
              items.push( "<option value='" + val.customer_id + "'>" + val.first_name + ' ' + val.last_name +"</option>" );
            }); 
        } else {
            items.push( "<option value=''>No customer assign</option>" );
        }

        console.log(items);
        $('#customer_id').html(items.join( "" ));
        $('#property_id').html("<option value='"+data.property_details.property_id+"'>"+data.property_details.property_address+"</option>");
        reassign();
      }); 
    }   
  })
 
  
    $("#customer_id").change(function(){
      var customer_id = $(this).val();
      // alert(customer_id);
      $.ajax({
          type: "POST",
          url: "<?= base_url('technician/Estimates/getPropertyByCustomerID')  ?>",
          data: {customer_id : customer_id } 
      }).done(function(data){
        //alert(data);
        $("#property_id").html(data);
          reassign();
          getemail(customer_id)
      });
    });

    function reassign() {
      $(".bootstrap-select").selectpicker('destroy');
      $('.bootstrap-select').selectpicker();
    }

    function getemail(customer_id) {
      $.ajax({
        type: "POST",
        url: "<?= base_url('technician/getcutomerEmail')  ?>",
        data: {customer_id : customer_id } 
      }).done(function(data){             
        $("#customer_email").val($.trim(data));              
      });

    }
    


</script>

  <script src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>&libraries=places&callback=initAutocomplete"
        async defer></script>

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
  var place = autocomplete.getPlace();

    $('.mydiv').html(place.adr_address);
    return_locality = $('.locality').text();
    return_region = $('.region').text();
    return_postal_code = $('.postal-code').text();
    res = return_postal_code.split("-");
        
    $('#locality'+unique).val(return_locality);
    $('#region'+unique).val(return_region);
    $('#postal-code'+unique).val(res[0]);




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
 <script>

$(document).ready(function(){  
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

    $("#front_yard_square_feet").keyup(function(){  
        var first_yard = $('#front_yard_square_feet').val();
        first_yard = Number.isInteger(Number.parseInt(first_yard)) ? Number.parseInt(first_yard) : 0;
        var second_yard = 0;

        second_yard = $('#back_yard_square_feet').val();
        second_yard = Number.isInteger(Number.parseInt(second_yard)) ? Number.parseInt(second_yard) : 0;

        var total_yard = first_yard+second_yard;
        total_yard = Number.isInteger(Number.parseInt(total_yard)) ? total_yard : 0;

        $('#yard_square_feet').val(total_yard);

        if (first_yard == 0) {
            $("#front_yard_grass").prop('disabled', true);
        } else {
            $("#front_yard_grass").prop('disabled', false);
        }
    });

    $("#back_yard_square_feet").keyup(function(){  
        var first_yard = $('#back_yard_square_feet').val();
        first_yard = Number.isInteger(Number.parseInt(first_yard)) ? Number.parseInt(first_yard) : 0;
        var second_yard = 0;

        second_yard = $('#front_yard_square_feet').val();
        second_yard = Number.isInteger(Number.parseInt(second_yard)) ? Number.parseInt(second_yard) : 0;

        var total_yard = first_yard+second_yard;
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