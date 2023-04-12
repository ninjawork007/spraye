

        <!-- Content area -->
        <div class="content">
          <div class="panel panel-flat">
          <!-- Form horizontal -->
          <div class="panel panel-flat">
           <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/productList') ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to All Products</a>
                        </div>
                   </h5>
              </div>
        </div>
              <br>

            
            <div class="panel-body">
        <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
              <div id="error">
                
              </div>
                <form class="form-horizontal" action="<?php echo base_url('admin/updateProduct')?>" method="post" name="addproduct" enctype="multipart/form-data" >

                  <input type="hidden" name="product_id" class="form-control" value="<?= $productData['product_id'];?>" >

                <fieldset class="content-group">
                  
                <div class="row">
                  <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Product Name</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="product_name" value="<?php echo set_value('product_name')?set_value('product_name'):$productData['product_name']?>" placeholder="Product Name">
                      <span style="color:red;"><?php echo form_error('product_name'); ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="form-group">
                    <label class="control-label col-lg-2">EPA Reg Number</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" name="epa_reg_nunber" value="<?php echo set_value('epa_reg_nunber')?set_value('epa_reg_nunber'):$productData['epa_reg_nunber']?>" placeholder="EPA Reg Number">
                       <span style="color:red;"><?php echo form_error('epa_reg_nunber'); ?></span>
                    </div>
                  </div>
                </div>

              
              </div>

              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Product Cost</label>
                    <div class="col-lg-3">
                      <input type="text" class="form-control" name="product_cost" value="<?php echo set_value('product_cost')?set_value('product_cost'):  floatval($productData['product_cost']) ?>" placeholder="Enter Cost">
                      <span style="color:red;"><?php echo form_error('product_cost'); ?></span>
                    </div>

                    <div class="col-lg-3">
                      <input type="text" class="form-control" name="product_cost_per" value="<?php echo set_value('product_cost_per')?set_value('product_cost_per'):floatval($productData['product_cost_per']) ?>" placeholder="Per Unit Value">
                      <span style="color:red;"><?php echo form_error('product_cost_per'); ?></span>
                    </div>

                    <div class="col-lg-3">
                                    <select class="form-control" name="product_cost_unit">
                                        <option value="Gallon"
                                            <?php if ($productData['product_cost_unit']=="Gallon" || $productData['product_cost_unit'] == "Gallons") { echo "Selected"; } ?>>
                                            Gallon</option>
                                        <option value="Fluid Ounce"
                                            <?php if ($productData['product_cost_unit']=="Fluid Ounce" || ($productData['product_cost_unit']=="Ounce" && ($productData['product_type'] == 4 || $productData['product_type'] == 8 || $productData['product_type'] == 9 || $productData['product_type'] == 10))  || ($productData['product_cost_unit']=="Ounces" && ($productData['product_type'] == 4 || $productData['product_type'] == 8 || $productData['product_type'] == 9 || $productData['product_type'] == 10))) { echo "Selected"; } ?>>
                                            Fluid Ounce</option>
                                        <option value="Liter"
                                            <?php if ($productData['product_cost_unit']=="Litre" || $productData['product_cost_unit']=="Liter") { echo "Selected"; } ?>>
                                            Liter</option>
                                        <option value="Pound"
                                            <?php if ($productData['product_cost_unit']=="Pound" || $productData['product_cost_unit']=="Lb" || $productData['product_cost_unit']=="Pounds") { echo "Selected"; } ?>>
                                            Pound</option>
                                        <option value="Kilogram"
                                            <?php if ($productData['product_cost_unit']=="Kg" || $productData['product_cost_unit']=="Kilogram") { echo "Selected"; } ?>>
                                            Kilogram</option>
                                        <option value="Ton"
                                            <?php if ($productData['product_cost_unit']=="Ton") { echo "Selected"; } ?>>
                                            Ton</option>
                                        <option value="Pint"
                                            <?php if ($productData['product_cost_unit']=="Pint") { echo "Selected"; } ?>>
                                            Pint</option>
                                        <option value="Quart"
                                            <?php if ($productData['product_cost_unit']=="Quart") { echo "Selected"; } ?>>
                                            Quart</option>
                                        <option value="Ounce"
                                            <?php if ($productData['product_cost_unit']=="Ounce" || $productData['product_cost_unit']=="Ounces") { echo "Selected"; } ?>>
                                            Ounce</option>
                                    </select>
                                </div>


                  </div>
                </div>

                  <div class="col-md-7">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Max Wind Speed</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" name="max_wind_speed" value="<?php echo set_value('max_wind_speed')?set_value('max_wind_speed'):$productData['max_wind_speed']?>" placeholder="Max Wind Speed in MPH">
                      <span style="color:red;"><?php echo form_error('max_wind_speed'); ?></span>
                    </div>
                  </div>
                </div>
                  </div>

                  <div class="row">

                 <div class="col-md-5">  
                  <div class="form-group">
                    <label class="control-label col-lg-3">Assign to Service</label>
                    <div class="multi-select-full col-lg-9">
                      <select class="multiselect-select-all-filtering form-control" name="assign_job[]" multiple="multiple" id="job_list">
                      <?php foreach ($joblist as $value): ?>
                          <!-- <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option> -->

                           <option value="<?= $value->job_id ?>" <?php if(in_array($value->job_id, $selectedjoblist )) { ?>selected <?php  } ?>   > <?= $value->job_name ?> </option>  
                      <?php endforeach ?>
                      </select>
                      <span style="color:red;"><?php echo form_error('assign_job'); ?></span>
                   </div>

                 </div>
                </div>

                
                <div class="col-md-7">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Application Rate</label>
                    <div class="col-lg-2">
                      <input type="text" class="form-control" name="application_rate" value="<?php echo set_value('application_rate')?set_value('application_rate'):floatval($productData['application_rate']) ?>" placeholder="Application Rate">
                      <span style="color:red;"><?php echo form_error('application_rate'); ?></span>
                     </div>

                     <div class="col-lg-2">
                                    <select class="form-control" name="application_unit">
                                    <option value="Gallons"
                                            <?php if ($productData['application_unit']=="Gallon" || $productData['application_unit'] == "Gallons") { echo "Selected"; } ?>>
                                            Gallons</option>
                                        <option value="Ounces"
                                            <?php if ($productData['application_unit']=="Ounces") { echo "Selected"; } ?>>
                                            Ounces</option>
                                        <option value="Liters"
                                            <?php if ($productData['application_unit']=="Litre" || $productData['application_unit']=="Liters") { echo "Selected"; } ?>>
                                            Liters</option>
                                        <option value="Pounds"
                                            <?php if ($productData['application_unit']=="Pounds" || $productData['application_unit']=="Lb") { echo "Selected"; } ?>>
                                            Pounds</option>
                                        <option value="Kilograms"
                                            <?php if ($productData['application_unit']=="Kg" || $productData['application_unit']=="Kilograms") { echo "Selected"; } ?>>
                                            Kilograms</option>
                                        <option value="Tons"
                                            <?php if ($productData['application_unit']=="Ton" || $productData['application_unit']=="Tons") { echo "Selected"; } ?>>
                                            Tons</option>
                                        <option value="Pints"
                                            <?php if ($productData['application_unit']=="Pint" || $productData['application_unit']=="Pints") { echo "Selected"; } ?>>
                                            Pints</option>
                                        <option value="Quarts"
                                            <?php if ($productData['application_unit']=="Quarts" || $productData['application_unit']=="Quarts") { echo "Selected"; } ?>>
                                            Quarts</option>
                                    </select>
                                </div>
                      
                                <div class="col-lg-1"></div>
                                <label class="control-label col-lg-1">Per</label>

                                <div class="col-lg-4">
                                    <select class="form-control" name="application_per">

                                        <option value="1 Acre"
                                            <?php if ($productData['application_per']=="1 Acre") { echo "Selected"; } ?>>
                                            1 Acre</option>
                                        <option value="1,000 Square Ft."
                                            <?php if ($productData['application_per']=="1,000 Square Ft.") { echo "Selected"; } ?>>
                                            1,000 Square Ft.</option>

                                    </select>
                                </div>


                    </div>
                  </div>

                 
                  </div>     

               

               <div class="row">

                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label col-lg-3  col-sm-12 col-xs-12">Active Ingredients</label>
                  <?php 
                  if (!empty($ingredients_details)) { ?>
                    <div class="col-lg-4  col-sm-5 col-xs-5">
                      <input type="text" class="form-control" name="active_ingredient[]" placeholder="Active Ingredient" id="numai0" value="<?= $ingredients_details[0]->active_ingredient ?>" >
                    </div>
                  
                   <div class="col-lg-4  col-sm-5 col-xs-5 addbuttonmanage">
                    <div class="input-group">
                        <input type="text" class="form-control" name="percent_active_ingredient[]" placeholder="Percentage of Active Ingredient" id="numpai0" value="<?=  number_format($ingredients_details[0]->percent_active_ingredient,2); ?>" >
                        <span class="input-group-btn">
                          <span class="btn btn-success">%</span>
                        </span>
                    </div>  
                   </div>
                      
                  <?php  } else { ?> 

                    <div class="col-lg-4  col-sm-5 col-xs-5 addbuttonmanage">
                      <input type="text" class="form-control" name="active_ingredient[]" placeholder="Active Ingredient" id="numai0" >
                    </div>
                  
                   <div class="col-lg-4  col-sm-5 col-xs-5 addbuttonmanage">
                    <div class="input-group">
                      <input type="text" class="form-control" name="percent_active_ingredient[]" placeholder="Percentage of Active Ingredient" id="numpai0"  >
                       <span class="input-group-btn">
                          <span class="btn btn-success">%</span>
                        </span>
                    </div>  

                   </div>

                  <?php } ?>

                     
                    <div class="col-lg-1  col-sm-2 col-xs-2 addbuttonmanage" >
                      <div class="form-group">
                        <center>
                          <a  id="addmore" ><i class="icon-add text-success" style="padding-top:6px;font-size:25px;" ></i></a>
                        </center>
                      </div>
                    </div>
                  </div>
                </div>




                <div class="col-md-7">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Temp Information</label>
                    <div class="col-lg-7">
                      <input type="text" class="form-control" name="temperature_information" value="<?php echo set_value('temperature_information')?set_value('temperature_information'):$productData['temperature_information']?>" placeholder="Temperature Information">
                      <span style="color:red;"><?php echo form_error('temperature_information'); ?></span>
                    </div>
                    <div class="col-lg-3">
                       <select class="form-control" name="temperature_unit" >
                        <option value="Celsius" <?php if ($productData['temperature_unit']=="Celsius") { echo "Selected"; } ?> >Celsius</option>
                        <option value="Fahrenheit" <?php if ($productData['temperature_unit']=="Fahrenheit") { echo "Selected"; } ?> >Fahrenheit</option>
                       
                      </select>
                       <span style="color:red;"><?php echo form_error('temperature_unit'); ?></span>
                    </div> 

                  </div>
                </div>

         
              
              </div>

                   <div id="apenddiv">
               <?php

               if (!empty($ingredients_details)) {
     
                   array_shift($ingredients_details);

                   $count=1;
                   foreach ($ingredients_details as $key => $value) { ?>

                    <div class="row" id="deleletediv<?= $count; ?>" >
                        <div class="col-md-5">
                          <div class="form-group">
                   
                            <label class="control-label col-lg-3"></label>
                   
                            <div class="col-lg-4">
                              <input type="text" class="form-control" name="active_ingredient[]" required placeholder="Active Ingredient" id="numai<?= $count; ?>" value="<?= $value->active_ingredient  ?>" >
                            </div>
                   
                            <div class="col-lg-4">
                              <div class="input-group">
                                <input type="text" class="form-control" name="percent_active_ingredient[]" required placeholder="Percentage of Active Ingredient" id="numpai<?= $count; ?>" value="<?= number_format($value->percent_active_ingredient,2)  ?>"   >
                                <span class="input-group-btn">
                                  <span class="btn btn-success">%</span>
                               </span>
                              </div>    

                            </div>

                            <div class="col-lg-1">
                              <div class="form-group">
                                <center>
                                  <a href="#"  onclick="myDeleteFunction(<?= $count; ?>)" ><i class="icon-cross3 text-dannger" style="padding-top:6px;font-size:25px;" ></i></a>
                                </center>
                              </div>
                            </div>
                          </div>
                        </div>
                     </div>

                   
                   <?php $count++; }  } ?>
                              
              </div>


               <div class="row">
              

                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Product Notes</label>
                    <div class="col-lg-9">
                       <textarea class="form-control" name="product_notes" rows="1" placeholder="Product Note"> <?php echo set_value('product_notes')?set_value('product_notes'):$productData['product_notes']?></textarea>
                      <span style="color:red;"><?php echo form_error('product_notes'); ?></span>
                    </div>
                  </div>
                </div>


                            <div class="col-md-7">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Mixture Application Rate</label>
                    <div class="col-lg-2">
                      <input type="text" class="form-control" name="mixture_application_rate" value="<?php echo set_value('mixture_application_rate')?set_value('mixture_application_rate'):floatval($productData['mixture_application_rate']) ?>" placeholder="Mixture mixture_Application Rate">
                      <span style="color:red;"><?php echo form_error('mixture_application_rate'); ?></span>
                     </div>

                     <div class="col-lg-2">
                                    <select class="form-control" name="mixture_application_unit">
                                    <option value="Gallons"
                                            <?php if ($productData['mixture_application_unit']=="Gallon" || $productData['mixture_application_unit'] == "Gallons") { echo "Selected"; } ?>>
                                            Gallons</option>
                                        <option value="Ounces"
                                            <?php if ($productData['mixture_application_unit']=="Ounces") { echo "Selected"; } ?>>
                                            Ounces</option>
                                        <option value="Liters"
                                            <?php if ($productData['mixture_application_unit']=="Litre" || $productData['mixture_application_unit']=="Liters") { echo "Selected"; } ?>>
                                            Liters</option>
                                        <option value="Pounds"
                                            <?php if ($productData['mixture_application_unit']=="Pounds" || $productData['mixture_application_unit']=="Lb") { echo "Selected"; } ?>>
                                            Pounds</option>
                                        <option value="Kilograms"
                                            <?php if ($productData['mixture_application_unit']=="Kg" || $productData['mixture_application_unit']=="Kilograms") { echo "Selected"; } ?>>
                                            Kilograms</option>
                                        <option value="Tons"
                                            <?php if ($productData['mixture_application_unit']=="Ton" || $productData['mixture_application_unit']=="Tons") { echo "Selected"; } ?>>
                                            Tons</option>
                                        <option value="Pints"
                                            <?php if ($productData['mixture_application_unit']=="Pint" || $productData['mixture_application_unit']=="Pints") { echo "Selected"; } ?>>
                                            Pints</option>
                                        <option value="Quarts"
                                            <?php if ($productData['mixture_application_unit']=="Quarts" || $productData['mixture_application_unit']=="Quarts") { echo "Selected"; } ?>>
                                            Quarts</option>
                                    </select>
                                </div>
                      
                                <div class="col-lg-1"></div>
                                <label class="control-label col-lg-1">Per</label>

                                <div class="col-lg-4">
                                    <select class="form-control" name="mixture_application_per">

                                        <option value="1 Acre"
                                            <?php if ($productData['mixture_application_per']=="1 Acre") { echo "Selected"; } ?>>
                                            1 Acre</option>
                                        <option value="1,000 Square Ft."
                                            <?php if ($productData['mixture_application_per']=="1,000 Square Ft.") { echo "Selected"; } ?>>
                                            1,000 Square Ft.</option>

                                    </select>
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
                                        <option value="1"
                                            <?= $productData['chemical_type'] == 1 ? 'selected' : '';   ?>>Herbicide
                                        </option>
                                        <option value="2"
                                            <?= $productData['chemical_type'] == 2 ? 'selected' : '';   ?>>Fungicide
                                        </option>
                                        <option value="3"
                                            <?= $productData['chemical_type'] == 3 ? 'selected' : '';   ?>>Insecticide
                                        </option>
                                        <option value="4"
                                            <?= $productData['chemical_type'] == 4 ? 'selected' : '';   ?>>Fertilizer
                                        </option>
                                        <option value="5"
                                            <?= $productData['chemical_type'] == 5 ? 'selected' : '';   ?>>Wetting Agent
                                        </option>
                                        <option value="6"
                                            <?= $productData['chemical_type'] == 6 ? 'selected' : '';   ?>>
                                            Surfactant/Tank Additive</option>
                                        <option value="7"
                                            <?= $productData['chemical_type'] == 7 ? 'selected' : '';   ?>>Aquatics
                                        </option>
                                        <option value="8"
                                            <?= $productData['chemical_type'] == 8 ? 'selected' : '';   ?>>Growth
                                            Regulator</option>
                                        <option value="9"
                                            <?= $productData['chemical_type'] == 9 ? 'selected' : '';   ?>>Biostimulants
                                        </option>

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
                                        <option value="No"
                                            <?= $productData['restricted_product'] == 'No' ? 'selected' : ''; ?>>No
                                        </option>
                                        <option value="Yes"
                                            <?= $productData['restricted_product'] == 'Yes' ? 'selected' : ''; ?>>Yes
                                        </option>

                                    </select>

                                </div>
                            </div>

                        </div>


              <div class="row">
           

                 <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Product Type</label>
                    <div class="col-lg-9">
                      <select  name="product_type" class="form-control" >
                        <option value="" >Select any product type</option>
                        <option value="1" <?= $productData['product_type']==1 ? 'selected': '';   ?> >Powder</option>
                        <option value="2" <?= $productData['product_type']==2 ? 'selected': '';   ?> >Wettable powder or W or WP</option>
                        <option value="3" <?= $productData['product_type']==3 ? 'selected': '';   ?> >Water-soluble powder</option>
                        <option value="4" <?= $productData['product_type']==4 ? 'selected': '';   ?> >Liquid</option>
                        <option value="5" <?= $productData['product_type']==5 ? 'selected': '';   ?> >Emulsifiable concentrate or E or EC</option>
                        <option value="6" <?= $productData['product_type']==6 ? 'selected': '';   ?> >Flowable or F</option>
                        <option value="7" <?= $productData['product_type']==7 ? 'selected': '';   ?> >Aqueous suspension</option>
                        <option value="8" <?= $productData['product_type']==8 ? 'selected': '';   ?> >Water-soluble liquid</option>
                        <option value="9" <?= $productData['product_type']==9 ? 'selected': '';   ?> >Liquefied gas</option>
                        <option value="10" <?= $productData['product_type']==10 ? 'selected': '';   ?> >Gel</option>
                        <option value="11" <?= $productData['product_type']==11 ? 'selected': '';   ?> >Granular or G</option>
                        <option value="12" <?= $productData['product_type']==12 ? 'selected': '';   ?> >Water-dispersible granules or WDG</option>
                        <option value="13" <?= $productData['product_type']==13 ? 'selected': '';   ?> >Dry flowable or DF</option>
                        <option value="14" <?= $productData['product_type']==14 ? 'selected': '';   ?> >Pellets</option>
                        <option value="15" <?= $productData['product_type']==15 ? 'selected': '';   ?> >Tablets</option>
                        <option value="16" <?= $productData['product_type']==16 ? 'selected': '';   ?> >Bait blocks</option>

                      </select>
                     
                    </div>
                  </div>

                </div>    


               <div class="col-md-7">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Application Type</label>
                    <div class="col-lg-10">
                        <select  name="application_type" class="form-control" >
                          <option value="" >Select any Application Type</option>
                          <option value="1" <?= $productData['application_type']==1 ? 'selected': '';   ?> >Broadcast</option>
                          <option value="2" <?= $productData['application_type']==2 ? 'selected': '';   ?> >Spot Spray</option>
                          <option value="3" <?= $productData['application_type']==3 ? 'selected': '';   ?> >Granular</option>
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
                        <option value="1" <?= $productData['application_method']==1 ? 'selected': '';   ?> >Ride On</option>
                        <option value="2" <?= $productData['application_method']==2 ? 'selected': '';   ?> >Skid Spray</option>
                        <option value="3" <?= $productData['application_method']==3 ? 'selected': '';   ?> >Backback</option>
                        <option value="4" <?= $productData['application_method']==4 ? 'selected': '';   ?> >Walk Behind Spreader</option>
                      </select>
                     
                    </div>
                  </div>

                </div>    

               <div class="col-md-7">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Area of Property Treated</label>
                    <div class="multi-select-full col-lg-10">
                        <select class="multiselect-select-all-filtering form-control" name="area_of_property_treated[]" multiple="multiple" id="area_of_property_treated_list">
                            <option value="1" <?= strpos($productData["area_of_property_treated"], '1') !== false?'selected':''; ?>>Lawn</option>
                            <option value="2" <?= strpos($productData["area_of_property_treated"], '2') !== false?'selected':''; ?>>Front Lawn</option>
                            <option value="3" <?= strpos($productData["area_of_property_treated"], '3') !== false?'selected':''; ?>>Back Lawn</option>
                            <option value="4" <?= strpos($productData["area_of_property_treated"], '4') !== false?'selected':''; ?>>Shrubs</option>
                            <option value="5" <?= strpos($productData["area_of_property_treated"], '5') !== false?'selected':''; ?>>Trees</option>
                            <option value="6" <?= strpos($productData["area_of_property_treated"], '6') !== false?'selected':''; ?>>Flower Beds</option>
                            <option value="7" <?= strpos($productData["area_of_property_treated"], '7') !== false?'selected':''; ?>>Bare Ground</option>
                            <option value="8" <?= strpos($productData["area_of_property_treated"], '8') !== false?'selected':''; ?>>Driveway/Sidewalk</option>
                            <option value="9" <?= strpos($productData["area_of_property_treated"], '9') !== false?'selected':''; ?>>Perimeter</option> 
                        </select>
                   </div>
                  </div>
                </div>                       
              </div>


              <div class="row">

               
                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Re-Entry Time</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="re_entry_time" placeholder="Re-Entry Time" value="<?php echo set_value('re_entry_time')?set_value('re_entry_time'):$productData['re_entry_time']?>" >
                       <span style="color:red;"><?php echo form_error('re_entry_time'); ?></span>
                    </div>
                  </div>
                </div> 



                <div class="col-md-7">
                  <div class="form-group">
                    <label class="control-label col-lg-2">Weed/ Pest Prevented</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" name="weed_pest_prevented" placeholder="Weed/Pest Prevented" value="<?php echo set_value('weed_pest_prevented')?set_value('weed_pest_prevented'):$productData['weed_pest_prevented']?>" >
                       <span style="color:red;"><?php echo form_error('weed_pest_prevented'); ?></span>
                    </div>
                  </div>
                </div>

                 
              </div>



            

                </fieldset>

               

                <div class="text-right">
                  <button type="submit" class="btn btn-success">Save <i class="icon-arrow-right14 position-right"></i></button>
                </div>
              </form>
            </div>
          </div>
          <!-- /form horizontal -->

        </div>
        <!-- /content area -->

<script type="text/javascript">
var numai = <?php if(!empty($ingredients_details)) { echo count($ingredients_details)+1; } else { echo 1; } ?>;      // Declaring and defining global increment variable.
var numpai = <?php if(!empty($ingredients_details)) { echo count($ingredients_details)+1; } else { echo 1; } ?>;      // Declaring and defining global increment variable.



$(document).ready(function() {
  
$('#addmore').click(function() {
var a   = $("#numai"+(numai-1)).val();
var b   = $("#numpai"+(numpai-1)).val();
    if(a!="" && b!="")
  {

    
     $("#apenddiv").append('<div class="row" id="deleletediv'+numai+'" ><div class="col-md-5"><div class="form-group"><label class="control-label col-lg-3"></label><div class="col-lg-4"><input type="text" class="form-control" name="active_ingredient[]" required placeholder="Active Ingredient" id="numai'+numai+'"></div><div class="col-lg-4"><div class="input-group"><input type="text"  class="form-control" name="percent_active_ingredient[]" required placeholder="Percentage of Active Ingredient" id="numpai'+numpai+'"><span class="input-group-btn"><span class="btn btn-success">%</span></span></div></div><div class="col-lg-1"><div class="form-group"><center><a href="#"  onclick="myDeleteFunction('+numai+')" ><i class="icon-cross3 text-dannger" style="padding-top:6px;font-size:25px;" ></i></a></center></div></div></div></div></div>');
       numai++;
       numpai++;
     }

     else
     {
          $('#error').html('');
          $("#error").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Please! </strong> fill Active Ingredient field for add another  field</div>');
           $('.alert-danger').fadeTo(5000, 500).slideUp(500, function(){
        $('.alert-danger').slideUp(500);
      });;


     }
    
    
});



});
</script>
 
 <script type="text/javascript">
   function myDeleteFunction(id) {
    $("#deleletediv"+(id)).remove();  
  
}
 </script> 

	
