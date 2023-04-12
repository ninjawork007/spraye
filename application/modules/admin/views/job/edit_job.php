      <style type="text/css">
        button.multiselect.dropdown-toggle.btn.btn-default {
          margin-left: 4px;
        }

        .service-fee.table-responsive {
          min-height: 0;
        }
      </style>
      <!-- Content area -->
      <div class="content">

        <!-- Form horizontal -->
        <div class="panel panel-flat">
          <div class="panel-heading">
            <h5 class="panel-title">
              <div class="form-group">
                <a href="<?= base_url('admin/job') ?>" id="save" class="btn btn-success"><i class=" icon-arrow-left7"> </i> Back to All Services</a>
              </div>
            </h5>
          </div>

          <br>
          <div class="panel-body">

            <form class="form-horizontal" action="<?= base_url('admin/job/updateJob') ?>" method="post" name="addjob" enctype="multipart/form-data">
              <fieldset class="content-group">

                <input type="hidden" name="job_id" class="form-control" value="<?= $job_details->job_id; ?>">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Service Name</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" name="job_name" value="<?php echo set_value('job_name') ? set_value('job_name') : $job_details->job_name ?>" placeholder="Service Name">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Service Price</label>
                      <div class="col-lg-9">
                        <div class="input-group">
                          <input type="text" class="form-control" name="job_price" value="<?php echo set_value('job_price') ? set_value('job_price') : $job_details->job_price ?>" placeholder="Service Price">
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
                      <label class="control-label col-lg-3">Assign Products</label>
                      <div class="multi-select-full col-lg-9">
                        <select class="multiselect-select-all-filtering" multiple="multiple" name="product_id_array[]" id="product_list">
                          <?php
                          if (!empty($product_details)) {
                            foreach ($product_details as $value) { ?>
                              <!--  <option value="$value->product_id"> $value->product_name</option> -->

                              <option value="<?= $value->product_id ?>" <?php if (in_array($value->product_id, $selectedproductlist)) { ?>selected <?php  } ?>   > <?= $value->product_name ?> </option>

                          <?php    }
                          } ?>

                        </select>
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
                              <td>$
                                <?= $company_details->base_service_fee ?>
                              </td>
                            </tr>
                            <tr>
                              <td>Base Service Fee Override</td>
                              <td>
                                <div class="input-group">
                                  <span class="input-group-btn">
                                    <span class="btn btn-success">$</span>
                                  </span>
                                  <input type="text" name="base_fee_override" class="form-control" value="<?= $job_details->base_fee_override ?>">
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td>Minimum Service Fee</td>
                              <td>$ <?= $company_details->minimum_service_fee ?></td>
                            </tr>
                            <tr>
                              <td>Minimum Service Fee Override</td>
                              <td>
                                <div class="input-group">
                                  <span class="input-group-btn">
                                    <span class="btn btn-success">$</span>
                                  </span>
                                  <input type="text" name="min_fee_override" class="form-control" value="<?= $job_details->min_fee_override ?>">
                                </div>
                              </td>
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
                      <label class="control-label col-lg-3">Assign Program</label>
                      <div class="multi-select-full col-lg-9">
                        <select class="multiselect-select-all-filtering" multiple="multiple" name="program_id_array[]" id="program_list">
                          <?php
                          if (!empty($program_details)) {
                            foreach ($program_details as $value) { ?>
                              <!--  <option value="$value->product_id"> $value->product_name</option> -->

                              <option value="<?= $value->program_id ?>" <?php if (in_array($value->program_id, $selectedprogramlist)) { ?>selected <?php  } ?>   > <?= $value->program_name ?> </option>

                          <?php    }
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
                        <select class="form-control"  name="service_type_id" id="service_type" >
                          <option value="">None selected</option>
                          <?php 
                            if (!empty($service_types)) {
                                foreach ($service_types as $value) {
                                  if ($value->service_type_id == $job_details->service_type_id) {
                                    $selected = 'selected';
                                  }else{
                                    $selected ='';
                                  }
                                  // switch ($value->service_type) {
                                  //     case "1":
                                  //     echo  '<option value="'.$value->service_type.'" '.$selected.' >Primary</option>';
                                  //     break;
                                  //     case "2":
                                  //     echo '<option value="'.$value->service_type.'" '.$selected.'>Secondary</option>';
                                  //     break;
                                  //     default:
                                  //     echo 'Other';
                                  // }
                                  echo  '<option value="'.$value->service_type_id.'" '.$selected.' >'.$value->service_type_name.'</option>';
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
                                   if ($value->commission_type == $job_details->commission_type) {
                                    $selected = 'selected';
                                  }else{
                                    $selected ='';
                                  }
                                  switch ($value->commission_type) {
                                    case "1":
                                    echo  '<option value="'.$value->commission_type.'" '.$selected.'>Primary</option>';
                                    break;
                                    case "2":
                                    echo '<option value="'.$value->commission_type.'" '.$selected.'>Secondary</option>';
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
                                  if ($value->bonus_type == $job_details->bonus_type) {
                                    $selected = 'selected';
                                  }else{
                                    $selected ='';
                                  }
                                 switch ($value->bonus_type) {
                                    case "1":
                                    echo  '<option value="'.$value->bonus_type.'" '.$selected.'>Primary</option>';
                                    break;
                                    case "2":
                                    echo '<option value="'.$value->bonus_type.'" '.$selected.'>Secondary</option>';
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
                        <textarea type="text" class="form-control" name="job_description" placeholder="Service Description"><?php echo set_value('job_description') ? set_value('job_description') : $job_details->job_description ?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Service Notes</label>
                      <div class="col-lg-9">
                        <textarea type="text" class="form-control" name="job_notes" placeholder="Service Notes"><?php echo set_value('job_notes') ? set_value('job_notes') : $job_details->job_notes ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="text-center col-md-12 col-lg-12 col-sm-12">
                    <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                  </div>
                </div>

              </fieldset>

            </form>
          </div>
        </div>
        <!-- /form horizontal -->

      </div>
      <!-- /content area -->

      <script>
        $(document).ready(function(){
          var service_type = $('#service_type').val();
          var commission_type = $('#commission_type').val();
          var bonus_type = $('#bonus_type').val();
          alert("You have selected the country - " + selectedCountry);

        });
      </script>