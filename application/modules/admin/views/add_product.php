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
  .required {
      color: red;
  }
</style>
<!-- Content area -->
<div class="content form-pg">
  <div class="panel panel-flat">
    <!-- Form horizontal -->
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">
          <div class="form-group">
            <a href="<?= base_url('admin/productList') ?>" id="save" class="btn btn-success"><i class=" icon-arrow-left7"> </i> Back to All Products</a>
          </div>
        </h5>
      </div>
    </div>
    <br>


    <div id="loading">
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>" /> <!-- Loading Image -->
    </div>

    <div class="panel-body">
      <b><?php if ($this->session->flashdata()) : echo $this->session->flashdata('message');
          endif; ?></b>
      <div id="error">

      </div>
      <form class="form-horizontal" action="<?php echo base_url('admin/addProductData') ?>" method="post" name="addproduct" enctype="multipart/form-data">
        <fieldset class="content-group">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-lg-3">Product Name<span class="required"> *</span></label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="product_name" value="<?php echo set_value('product_name') ?>" placeholder="Product Name">
                  <span style="color:red;"><?php echo form_error('product_name'); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <div class="form-group">
                <label class="control-label col-lg-2">EPA Reg Number</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control" name="epa_reg_nunber" value="<?php echo set_value('epa_reg_nunber') ?>" placeholder="EPA Reg Number">
                  <span style="color:red;"><?php echo form_error('epa_reg_nunber'); ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-lg-3">Product Cost<span class="required"> *</span></label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" name="product_cost" value="<?php echo set_value('product_cost') ?>" placeholder="Enter Cost">
                  <span style="color:red;"><?php echo form_error('product_cost'); ?></span>
                </div>

                <div class="col-lg-3">
                  <input type="text" class="form-control" name="product_cost_per" value="<?php echo set_value('product_cost_per') ?>" placeholder="Per Unit Value ">
                  <span style="color:red;"><?php echo form_error('product_cost_per'); ?></span>
                </div>

                <div class="col-lg-3">
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

            <div class="col-md-7">
              <div class="form-group">
                <label class="control-label col-lg-2">Max Wind Speed</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control" name="max_wind_speed" value="<?php echo set_value('max_wind_speed') ?>" placeholder="Max Wind Speed in MPH">
                  <span style="color:red;"><?php echo form_error('max_wind_speed'); ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">


            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-lg-3 col-sm-12 col-xs-12">Assign to Service</label>
                <div class="multi-select-full col-lg-8  col-sm-10 col-xs-10">
                  <select class="multiselect-select-all-filtering form-control" name="assign_job[]" multiple="multiple" id="job_list">
                    <?php foreach ($joblist as $value) : ?>
                      <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>
                    <?php endforeach ?>
                  </select>
                  <span style="color:red;"><?php echo form_error('assign_job'); ?></span>
                </div>

                <div class="col-lg-1 col-sm-2 col-xs-2 addbuttonmanage">
                  <div class="form-group">
                    <center>
                      <a href="#" data-toggle="modal" data-target="#modal_add_job"><i class="icon-add text-success" style="padding-top:6px;font-size:25px;"></i></a>
                    </center>
                  </div>
                </div>
              </div>
            </div>



            <div class="col-md-7">
              <div class="form-group">
                <label class="control-label col-lg-2">Application Rate</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" name="application_rate" value="<?php echo set_value('application_rate') ?>" placeholder="Application Rate">
                  <span style="color:red;"><?php echo form_error('application_rate'); ?></span>
                </div>

                <div class="col-lg-2">
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


                <div class="col-lg-1"></div>
                <label class="control-label col-lg-1">Per</label>


                <div class="col-lg-3">
                  <select class="form-control" name="application_per" value="<?php echo set_value('application_per') ?>" placeholder="Per Unit">
                    <option value="1 Acre">1 Acre</option>
                    <option value="1,000 Square Ft.">1,000 Square Ft.</option>
                  </select>
                  <span style="color:red;"><?php echo form_error('application_per'); ?></span>
                </div>

              </div>
            </div>


          </div>

          <div class="row">

            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-lg-3 col-sm-12 col-xs-12">Active Ingredients</label>

                <div class="col-lg-4 col-sm-5 col-xs-5">
                  <input type="text" class="form-control" name="active_ingredient[]" placeholder="Active Ingredient" id="numai0">
                </div>

                <div class="col-lg-4  col-sm-5 col-xs-5 addbuttonmanage">
                  <div class="input-group">

                    <input type="text" class="form-control" name="percent_active_ingredient[]" placeholder="Percentage of Active Ingredient" id="numpai0">
                    <span class="input-group-btn">
                      <span class="btn btn-success">%</span>
                    </span>
                  </div>
                </div>

                <div class="col-lg-1  col-sm-2 col-xs-2 addbuttonmanage">
                  <div class="form-group">
                    <center>
                      <a id="addmore"><i class="icon-add text-success" style="padding-top:6px;font-size:25px;"></i></a>
                    </center>
                  </div>
                </div>
              </div>
            </div>





            <div class="col-md-7">
              <div class="form-group">
                <label class="control-label col-lg-2">Temp Information</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control" name="temperature_information" value="<?php echo set_value('temperature_information') ?>" placeholder="Temperature Information">
                  <span style="color:red;"><?php echo form_error('temperature_information'); ?></span>
                </div>
                <div class="col-lg-3">
                  <select class="form-control" name="temperature_unit">
                    <option value="Fahrenheit">Fahrenheit</option>
                    <option value="Celsius">Celsius</option>
                  </select>
                  <span style="color:red;"><?php echo form_error('temperature_unit'); ?></span>
                </div>

              </div>
            </div>

          </div>

          <div id="apenddiv">

          </div>

          <div class="row">

            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-lg-3">Product Notes</label>
                <div class="col-lg-9">
                  <textarea class="form-control" name="product_notes" rows="1" value="<?php echo set_value('product_notes') ?>" placeholder="Product Note"></textarea>
                  <span style="color:red;"><?php echo form_error('product_notes'); ?></span>
                </div>
              </div>
            </div>


            <div class="col-md-7">
              <div class="form-group">
                <label class="control-label col-lg-2">Mixture Application Rate</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" name="mixture_application_rate" value="<?php echo set_value('mixture_application_rate') ?>" placeholder="Mixture Application Rate">
                  <span style="color:red;"><?php echo form_error('mixture_application_rate'); ?></span>
                </div>

                <div class="col-lg-2">
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


                <div class="col-lg-1"></div>
                <label class="control-label col-lg-1">Per</label>


                <div class="col-lg-3">
                  <select class="form-control" name="mixture_application_per" value="<?php echo set_value('mixture_application_per') ?>" placeholder="Per Unit">
                    <option value="1 Acre">1 Acre</option>
                    <option value="1,000 Square Ft.">1,000 Square Ft.</option>
                  </select>
                  <span style="color:red;"><?php echo form_error('mixture_application_per'); ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">

            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-lg-3">Chemical Type</label>
                <div class="col-lg-9">
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
              </div>

            </div>

            <div class="col-md-7">
              <div class="form-group">
                <label class="control-label col-lg-2">Restricted Product</label>
                <div class="col-lg-3">
                  <select name="restricted_product" class="form-control">
                    <option value="">Choose an Option</option>
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>

                  </select>

                </div>
              </div>
            </div>
          </div>

          <div class="row">





            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-lg-3">Product Type</label>
                <div class="col-lg-9">
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
              </div>
            </div>


            <div class="col-md-7">
              <div class="form-group">
                <label class="control-label col-lg-2">Application Type</label>
                <div class="col-lg-10">
                  <select name="application_type" class="form-control">
                    <option value="">Select any Application Type</option>
                    <option value="1">Broadcast</option>
                    <option value="2">Spot Spray</option>
                    <option value="3">Granular</option>
                  </select>

                </div>
              </div>
            </div>
          </div>

          <div class="row">





            <div class="col-md-5">
                <div class="form-group">
                    <label class="control-label col-lg-3">Application Method</label>
                    <div class="col-lg-9">
                      <select  name="application_method" class="form-control" >
                        <option value="" >Select any Application Method</option>
                        <option value="1" >Ride On</option>
                        <option value="2" >Skid Spray</option>
                        <option value="3" >Backback</option>
                        <option value="4" >Walk Behind Spreader</option>
                      </select>
                     
                    </div>
                </div>
            </div>


            <div class="col-md-7">
                <div class="form-group">
                    <label class="control-label col-lg-2">Area of Property Treated<!--<span class="required"> *</span>--></label>
                    <div class="multi-select-full col-lg-10">
                        <select class="multiselect-select-all-filtering form-control"  name="area_of_property_treated[]" multiple="multiple" id="area_of_property_treated_list1" >
                            <option value="1" >Lawn</option>
                            <option value="2" >Front Lawn</option>
                            <option value="3" >Back Lawn</option>
                            <option value="4" >Shrubs</option>
                            <option value="5" >Trees</option>
                            <option value="6" >Flower Beds</option>
                            <option value="7" >Bare Ground</option>
                            <option value="8" >Driveway/Sidewalk</option>
                            <option value="9" >Perimeter</option> 
                        </select>
                        <span style="color:red;"><?php echo form_error('area_of_property_treated[]'); ?></span>
                   </div>
                </div>
            </div>
          </div>

          <div class="row">


            <div class="col-md-5">
              <div class="form-group">
                <label class="control-label col-lg-3">Re-Entry Time</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="re_entry_time" value="<?php echo set_value('re_entry_time') ?>" placeholder="Re-Entry Time">
                  <span style="color:red;"><?php echo form_error('re_entry_time'); ?></span>
                </div>
              </div>
            </div>



            <div class="col-md-7">
              <div class="form-group">
                <label class="control-label col-lg-2">Weed/ Pest Prevented</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control" name="weed_pest_prevented" value="<?php echo set_value('weed_pest_prevented') ?>" placeholder="Weed/Pest Prevented">
                  <span style="color:red;"><?php echo form_error('weed_pest_prevented'); ?></span>
                </div>
              </div>
            </div>




          </div>






        </fieldset>

        <div class="text-right">
          <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
      </form>
    </div>
  </div>
  <!-- /form horizontal -->

</div>
<!-- /content area -->



<!-- Primary modal -->
<div id="modal_add_job" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Add Service</h6>
      </div>

      <form name="addjob" id="my_form" action="<?= base_url('admin/job/addJobData') ?>" method="post" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">

          <div class="form-group">
            <div class="row">
              <div class="col-md-6 col-sm-6">
                <label>Service Name</label>
                <input type="text" class="form-control" name="job_name" placeholder="Service Name">
              </div>
              <div class="col-md-6 col-sm-6">
                <label>Price</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="job_price" placeholder="Service Price">
                  <span class="input-group-btn">
                    <span class="btn btn-success">per 1,000 sq ft.</span>
                  </span>
                </div>
              </div>
            </div>
          </div>


          <div class="form-group">
            <div class="row">


              <div class="col-md-6 col-sm-6">
                <label>Service Description</label>
                <textarea type="text" class="form-control" name="job_description" placeholder="Service Description"></textarea>
              </div>
              <div class="col-md-6 col-sm-6">
                <label>Service Note</label>
                <textarea type="text" class="form-control" name="job_notes" placeholder="Service Notes"></textarea>
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

<script type="text/javascript">
  var numai = 1; // Declaring and defining global increment variable.
  var numpai = 1; // Declaring and defining global increment variable.
  $(document).ready(function() {

    $('#addmore').click(function() {
      var a = $("#numai" + (numai - 1)).val();
      var b = $("#numpai" + (numpai - 1)).val();
      // alert(a);
      // alert(b); 
      if (a != "" && b != "") {


        $("#apenddiv").append('<div class="row" id="deleletediv' + numai + '" ><div class="col-md-5"><div class="form-group"><label class="control-label col-lg-3"></label><div class="col-lg-4"><input type="text" class="form-control" name="active_ingredient[]" required placeholder="Active Ingredient" id="numai' + numai + '"></div><div class="col-lg-4"><div class="input-group"><input type="text"  class="form-control" name="percent_active_ingredient[]" required placeholder="Percentage of Active Ingredient" id="numpai' + numpai + '"><span class="input-group-btn"><span class="btn btn-success">%</span></span></div></div><div class="col-lg-1"><div class="form-group"><center><a href="#"  onclick="myDeleteFunction(' + numai + ')" ><i class="icon-cross3 text-dannger" style="padding-top:6px;font-size:25px;" ></i></a></center></div></div></div></div></div>');
        numai++;
        numpai++;
      } else {
        $('#error').html('');
        $("#error").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please! </strong> fill Active Ingredient field for add another field</div>');
        $('.alert-danger').fadeTo(5000, 500).slideUp(500, function() {
          $('.alert-danger').slideUp(500);
        });;


      }


    });



  });
</script>

<script type="text/javascript">
  function myDeleteFunction(id) {
    $("#deleletediv" + (id)).remove();

  }

  

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