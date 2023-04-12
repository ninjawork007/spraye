<style type="text/css">
button.multiselect.dropdown-toggle.btn.btn-default {
    margin-left: 4px;
}

.service-fee.table-responsive {
    min-height: 0;
}
</style>
<!-- Content area -->
<div class="content form-pg">
    <!-- Form horizontal -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                <div class="form-group">
                    <a href="<?= base_url('admin/job') ?>" id="save" class="btn btn-success"><i
                            class=" icon-arrow-left7"> </i> Back to All Services</a>
                </div>
            </h5>
        </div>
        <br>
        <div class="panel-body">
            <form class="form-horizontal" action="<?= base_url('admin/job/addJobData') ?>" method="post" name="addjob"
                enctype="multipart/form-data">
                <fieldset class="content-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Service Name</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="job_name" placeholder="Service Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Service Price</label>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="job_price"
                                            placeholder="Service Price">
                                        <span class="input-group-btn">
                                            <span class="btn btn-success">per 1,000 sq ft.</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3 col-sm-12 col-xs-12">Assign Products</label>
                                <div class="multi-select-full col-lg-8  col-sm-10 col-xs-10">
                                    <select class="multiselect-select-all-filtering" multiple="multiple"
                                        name="product_id_array[]" id="product_list">
                                        <?php
                                        if (!empty($product_details)) {
                                            foreach ($product_details as $value) {
                                                echo '<option value="' . $value->product_id . '">' . $value->product_name . '</option>';
                                            }
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-1 col-sm-2 col-xs-2 addbuttonmanage">
                                    <div class="form-group">
                                        <center>
                                            <a href="#" data-toggle="modal" data-target="#modal_add_product"><i
                                                    class="icon-add text-success"
                                                    style="padding-top:6px;font-size:25px;"></i></a>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Service Fees</label>

                                <div class="col-lg-9 service-fee table-responsive">
                                    <table class="table table-bordered">
                                        <tbody class="service_fee_override">
                                            <tr>
                                                <td>Base Service Fee</td>
                                                <td><?= $company_details->base_service_fee ?></td>
                                            </tr>
                                            <tr>
                                                <td>Base Service Fee Override</td>
                                                <td><input type="text" name="base_fee_override" class="form-control"
                                                        value=""></td>
                                            </tr>
                                            <tr>
                                                <td>Minimum Service Fee</td>
                                                <td><?= $company_details->minimum_service_fee ?></td>
                                            </tr>
                                            <tr>
                                                <td>Minimum Service Fee Override</td>
                                                <td><input type="text" name="min_fee_override" class="form-control"
                                                        value=""></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
				</div>
				<div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label col-lg-3 col-sm-12 col-xs-12">Assign Programs</label>
                     <div class="multi-select-full col-lg-9  col-sm-12 col-xs-12">
                        <select class="multiselect-select-all-filtering" multiple="multiple" name="program_id_array[]" id="program_list">
                        <?php 
                           if (!empty($program_details)) {
                              foreach ($program_details as $value) {
                                 echo '<option value="'.$value->program_id.'">'.$value->program_name.'</option>';
                              }
                           }
                           
                              ?>
                        </select>
                     </div>
                     
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="control-label col-lg-3 col-sm-12 col-xs-12">Service Name Type</label>
                     <div class="multi-select-full col-lg-9  col-sm-12 col-xs-12">
                        <select class="form-control"  name="service_type_id" id="service_type">
                        <option value="">None selected</option>
                        <?php 
                           if (!empty($service_types)) {
                              foreach ($service_types as $value) {
                                //  switch ($value->service_type) {
                                //     case "1":
                                //     echo  '<option value="'.$value->service_type.'">Primary</option>';
                                //     break;
                                //     case "2":
                                //     echo '<option value="'.$value->service_type.'">Secondary</option>';
                                //     break;
                                //     default:
                                //     echo 'Other';
                                //  }
                                echo  '<option value="'.$value->service_type_id.'">'.$value->service_type_name.'</option>';
                              }
                           }
                        ?>
                        </select>
                     </div>
                    
                  </div>
               </div>
            </div>
            <div class="row">
                 <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3 col-sm-12 col-xs-12">Commission Type</label>
                        <div class="multi-select-full col-lg-9  col-sm-12 col-xs-12">
                           <select class="form-control"  name="commission_type" id="commission_type">
                           <option value="">None selected</option>
                           <?php 
                              if (!empty($commission_types)) {
                                foreach ($commission_types as $value) {
                                 switch ($value->commission_type) {
                                    case "1":
                                    echo  '<option value="'.$value->commission_type.'">Primary</option>';
                                    break;
                                    case "2":
                                    echo '<option value="'.$value->commission_type.'">Secondary</option>';
                                    break;
                                    default:
                                    echo 'Other';
                                 }
                                }
                              }
                              
                               ?>
                           </select>
                        </div>
                        
                     </div>
                  </div>
                 <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3 col-sm-12 col-xs-12">Bonus Type</label>
                        <div class="multi-select-full col-lg-9  col-sm-12 col-xs-12">
                           <select class="form-control"  name="bonus_type" id="bonus_type">
                           <option value="">None selected</option>
                           <?php 
                              if (!empty($bonus_types)) {
                                foreach ($bonus_types as $value) {
                                  switch ($value->bonus_type) {
                                    case "1":
                                    echo  '<option value="'.$value->bonus_type.'">Primary</option>';
                                    break;
                                    case "2":
                                    echo '<option value="'.$value->bonus_type.'">Secondary</option>';
                                    break;
                                    default:
                                    echo 'Other';
                                 }
                                }
                              }
                              
                               ?>
                           </select>
                        </div>
                        
                     </div>
                  </div>
               </div>
                       
                  
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Service Description</label>
                                <div class="col-lg-9">
                                    <textarea type="text" class="form-control" name="job_description"
                                        placeholder="Service Description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Service Notes</label>
                                <div class="col-lg-9">
                                    <textarea type="text" class="form-control" name="job_notes"
                                        placeholder="Service Notes"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="text-center col-md-12 col-lg-12 col-sm-12">
                            <button type="submit" class="btn btn-success">Submit <i
                                    class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <!-- /form horizontal -->
    <!-- /content area -->
    <!-- Start Product Modal -->
    <div id="modal_add_product" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">Add Product</h6>
                </div>
                <form name="addproduct" id="my_form" action="<?= base_url('admin/addProductData') ?>" method="post"
                    enctype="multipart/form-data" form_ajax="ajax">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <label>Product Name</label>
                                    <input type="text" class="form-control" name="product_name"
                                        placeholder="Product Name">
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label>EPA Reg Number</label>
                                    <input type="text" class="form-control" name="epa_reg_nunber"
                                        placeholder="EPA Reg Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <label>Product Cost</label>
                                    <input type="text" class="form-control" name="product_cost"
                                        placeholder="Enter Cost">
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <label>Cost Per Unit</label>
                                    <input type="text" class="form-control" name="product_cost_per"
                                        placeholder="Per Unit Value">
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <label>Cost Unit</label>
                                    <select class="form-control" name="product_cost_unit" id="product_cost_unit">
                                        <option value="Gallon">Gallon(s)</option>
                                        <option value="Ounce">Ounce(s)</option>
                                        <option value="Liter">Liter(s)</option>
                                        <option value="Pound">Pound(s)</option>
                                        <option value="Kilogram">Kilogram(s)</option>
                                        <option value="Ton">Ton(s)</option>
                                        <option value="Pint">Pint(s)</option>
                                        <option value="Quart">Quart(s)</option>
                                        <option value="Gram">Gram(s)</option>
                                        <option value="Fluid Ounce">Fluid Ounce(s)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <label>Temp Information</label>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="temperature_information"
                                                placeholder="Temperature Information">
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control" name="temperature_unit">
                                                <option value="Celsius">Celsius</option>
                                                <option value="Farenheit">Fahrenheit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <label>Application Rate</label>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="application_rate"
                                                placeholder="Application Rate">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                        <select class="form-control" name="application_unit" id="application_unit">
                                            <option value="Gallon">Gallon(s)</option>
                                            <option value="Ounce">Ounce(s)</option>
                                            <option value="Liter">Liter(s)</option>
                                            <option value="Pound">Pound(s)</option>
                                            <option value="Kilogram">Kilogram(s)</option>
                                            <option value="Ton">Ton(s)</option>
                                            <option value="Pint">Pint(s)</option>
                                            <option value="Quart">Quart(s)</option>
                                            <option value="Gram">Gram(s)</option>
                                            <option value="Fluid Ounce">Fluid Ounce(s)</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label>Per</label>
                                    <div class="row">

                                        <div class="col-lg-12 col-md-6 col-sm-12">
                                            <select class="form-control" name="application_per" placeholder="Unit">
                                                <option value="1 Acre" default="true">1 Acre</option>
                                                <option value="1,000 Square Ft.">1,000 Square Ft.</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <label>Max Wind Speed</label>
                                    <input type="text" class="form-control" name="max_wind_speed"
                                        placeholder="Max Wind Speed in MPH">
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label>Active Ingredients</label>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="active_ingredient[]"
                                                placeholder="Active Ingredient">
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="number" class="form-control" name="percent_active_ingredient[]"
                                                placeholder="Percentage of Active Ingredient" maxlength="3">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                <select class="form-control" name="application_per" placeholder="Unit">
                                                    <option value="1 Acre" default="true">1 Acre</option>
                                                    <option value="1,000 Square Ft.">1,000 Square Ft.</option>
                                                </select>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <label>Mixture Application Rate</label>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="mixture_application_rate"
                                                placeholder="Mixture Application Rate">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                        <select class="form-control" name="mixture_application_unit" id="mixture_application_unit">
                                            <option value="Gallon">Gallon(s)</option>
                                            <option value="Ounce">Ounce(s)</option>
                                            <option value="Liter">Liter(s)</option>
                                            <option value="Pound">Pound(s)</option>
                                            <option value="Kilogram">Kilogram(s)</option>
                                            <option value="Ton">Ton(s)</option>
                                            <option value="Pint">Pint(s)</option>
                                            <option value="Quart">Quart(s)</option>
                                            <option value="Gram">Gram(s)</option>
                                            <option value="Fluid Ounce">Fluid Ounce(s)</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label>Per</label>
                                    <div class="row">

                                        <div class="col-lg-12 col-md-6 col-sm-12">
                                            <select class="form-control" name="mixture_application_per"
                                                placeholder="Unit">
                                                <option value="1 Acre" default="true">1 Acre</option>
                                                <option value="1,000 Square Ft.">1,000 Square Ft.</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <label>Product Type</label>
                                    <select name="product_type" class="form-control">
                                        <option value="">Select any product type</option>
                                        <option value="1">Powder</option>
                                        <option value="2">Wettable powder or W or WP</option>
                                        <option value="3">Water-soluble powder</option>
                                        <option value="4">Liquid</option>
                                        <option value="5">Emulsifiable concentrate or E or EC</option>
                                        <option value="6">Flowable or F</option>
                                        <option value="7">Aqueous suspension</option>
                                        <option value="8">Water-soluble liquid</option>
                                        <option value="9">Liquefied gas</option>
                                        <option value="10">Gel</option>
                                        <option value="11">Granular or G</option>
                                        <option value="12">Water-dispersible granules or WDG</option>
                                        <option value="13">Dry flowable or DF</option>
                                        <option value="14">Pellets</option>
                                        <option value="15">Tablets</option>
                                        <option value="16">Bait blocks</option>

                                    </select>

                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label>Application Type</label>
                                    <select name="application_type" class="form-control">
                                        <option value="">Select any Application Type</option>
                                        <option value="1">Broadcast</option>
                                        <option value="2">Spot Spray</option>
                                        <option value="3">Granular</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <label class="">Re-Entry Time</label>
                                    <input type="text" class="form-control" name="re_entry_time"
                                        placeholder="Re-Entry Time">
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label class="">Weed/Pest Prevented</label>
                                    <input type="text" class="form-control" name="weed_pest_prevented"
                                        placeholder="Weed/Pest Prevented">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <label>Chemical Type</label>
                                    <select name="chemical_type" class="form-control">
                                        <option value="">Select any chemical type</option>
                                        <option value="1">Herbicide</option>
                                        <option value="2">Fungicide</option>
                                        <option value="3">Insecticide</option>
                                        <option value="4">Fertilizer</option>
                                        <option value="5">Wetting Agent</option>
                                        <option value="6">Surfactant/Tank Additive</option>
                                        <option value="7">Aquatics</option>
                                        <option value="8">Growth Regulator</option>
                                        <option value="9">Biostimulants</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-sm-6">
                                    <label class="control-label">Restricted Product</label>
                                    <select name="restricted_product" class="form-control">
                                        <option value="">Choose an Option</option>
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>

                                    </select>

                                </div>
                            </div>
                        </div>
                     

                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-12 col-sm-12">
                                    <label>Product Note</label>
                                    <textarea class="form-control" name="product_notes" rows="2"
                                        placeholder="Product Note"></textarea>
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
    </div>
    <!-- End Product Modal-->
</div>

<script>
    $(document).on('change', '#product_cost_unit', function(){
    var appHTML = '';
    if($(this).val() == 'Gallon' || $(this).val() == 'Quart' || $(this).val() == 'Liter' || $(this).val() == 'Pint' || $(this).val() == 'Fluid Ounce'){
        appHTML += '<option value="Gallon">Gallon(s)</option>';
        appHTML += '<option value="Quart">Quart(s)</option>';
        appHTML += '<option value="Liter">Liter(s)</option>';
        appHTML += '<option value="Pint">Pint(s)</option>';
        appHTML += '<option value="Fluid Ounce">Fluid Ounce(s)</option>';
    } else {
        appHTML += '<option value="Gram">Gram(s)</option>';
        appHTML += '<option value="Kilogram">Kilogram(s)</option>';
        appHTML += '<option value="Pound">Pound(s)</option>';
        appHTML += '<option value="Ton">Ton(s)</option>';
        appHTML += '<option value="Ounce">Ounce(s)</option>';
    }

    $('#application_unit').html(appHTML);
    $('#mixture_application_unit').html(appHTML);
  });
</script>
