<style type="text/css">
  th,
  td {
    text-align: center;
  }

  .pre-scrollable {
    min-height: 0px;
  }

  #modal_add_service .row {
    margin-bottom: 5px;
  }

  @media(min-width: 520px) {

#desktop-frame,
.desktop-col {
    display: block !important;
}

#mobile-frame {
    display: none !important;
}
}

@media(max-width: 519px) {

#desktop-frame,
.desktop-col {
    display: none !important;
}

#mobile-frame {
    display: block !important;
}
}
</style>

<!-- Content area -->
<div class="content">

  <!-- Form horizontal -->
  <div class="panel panel-flat">

    <div class="panel panel-flat">
      <div class="panel-heading">

        <h5 class="panel-title">
          <div class="form-group">
            <div class="row">
              <div class="col-md-12">

                <div class="btndiv">


                  <a href="<?= base_url('admin/propertyList') ?>" id="save" class="btn btn-success"><i class="icon-arrow-left7"></i> Back to All Properties</a>

                  <a href="<?= base_url('admin/Estimates/addEstimate?pr_id=') . $propertyData['property_id'] ?>" id="" class="btn btn-warning"><i class="icon-plus2"> </i> Create Estimate</a>

                  <button type="button" class="btn btn-info" id="addServiceButton" data-target="#modal_add_service" data-toggle="modal"> <i class=" icon-plus22"></i> Add Standalone Service</button>

                </div>

              </div>

            </div>
          </div>
        </h5>






      </div>
    </div>

    <br>
    <div class="panel-body">
      <?php //echo validation_errors(); 
      ?>
      <b><?php if ($this->session->flashdata()) : echo $this->session->flashdata('message');
          endif; ?></b>
      <form class="form-horizontal" action="<?= base_url('admin/updateProperty') ?>" method="post" name="addproperty" enctype="multipart/form-data">
        <fieldset class="content-group">

          <input type="hidden" name="property_id" class="form-control" value="<?= $propertyData['property_id']; ?>">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Property Name</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="property_title" value="<?php echo set_value('property_title') ? set_value('property_title') : $propertyData['property_title'] ?>" placeholder="Property Name">

                  <span style="color:red;"><?php echo form_error('property_title'); ?></span>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Address</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="property_address" id="autocomplete" onFocus="geolocate()" value="<?= $propertyData['property_address'] ?>" placeholder="Address">

                </div>
              </div>
            </div>
            <div id="map"></div>
            <input type="hidden" name="property_latitude" id="latitude" value="<?= $propertyData['property_latitude'] ?>" />
            <input type="hidden" name="property_longitude" id="longitude" value="<?= $propertyData['property_longitude'] ?>" />
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Address 2</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="property_address_2" value="<?php echo set_value('property_address_2') ? set_value('property_address_2') : $propertyData['property_address_2'] ?>" placeholder="Address 2">
                  <span style="color:red;"><?php echo form_error('property_address_2'); ?></span>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">City</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="property_city" value="<?php echo set_value('property_city') ? set_value('property_city') : $propertyData['property_city'] ?>" placeholder="City" id="locality">
                  <span style="color:red;"><?php echo form_error('property_city'); ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">State</label>
                <div class="col-lg-9">

                  <select class="form-control" name="property_state" id="region">

                    <option value="">Select State</option>
                    <option value="AL" <?php if ($propertyData['property_state'] == 'AL') {
                                          echo "selected";
                                        } ?>>Alabama</option>
                    <option value="AK" <?php if ($propertyData['property_state'] == 'AK') {
                                          echo "selected";
                                        } ?>>Alaska</option>
                    <option value="AZ" <?php if ($propertyData['property_state'] == 'AZ') {
                                          echo "selected";
                                        } ?>>Arizona</option>
                    <option value="AR" <?php if ($propertyData['property_state'] == 'AR') {
                                          echo "selected";
                                        } ?>>Arkansas</option>
                    <option value="CA" <?php if ($propertyData['property_state'] == 'CA') {
                                          echo "selected";
                                        } ?>>California</option>
                    <option value="CO" <?php if ($propertyData['property_state'] == 'CO') {
                                          echo "selected";
                                        } ?>>Colorado</option>
                    <option value="CT" <?php if ($propertyData['property_state'] == 'CT') {
                                          echo "selected";
                                        } ?>>Connecticut</option>
                    <option value="DE" <?php if ($propertyData['property_state'] == 'DE') {
                                          echo "selected";
                                        } ?>>Delaware</option>
                    <option value="DC" <?php if ($propertyData['property_state'] == 'DC') {
                                          echo "selected";
                                        } ?>>District Of Columbia</option>
                    <option value="FL" <?php if ($propertyData['property_state'] == 'FL') {
                                          echo "selected";
                                        } ?>>Florida</option>
                    <option value="GA" <?php if ($propertyData['property_state'] == 'GA') {
                                          echo "selected";
                                        } ?>>Georgia</option>
                    <option value="HI" <?php if ($propertyData['property_state'] == 'HI') {
                                          echo "selected";
                                        } ?>>Hawaii</option>
                    <option value="ID" <?php if ($propertyData['property_state'] == 'ID') {
                                          echo "selected";
                                        } ?>>Idaho</option>
                    <option value="IL" <?php if ($propertyData['property_state'] == 'IL') {
                                          echo "selected";
                                        } ?>>Illinois</option>
                    <option value="IN" <?php if ($propertyData['property_state'] == 'IN') {
                                          echo "selected";
                                        } ?>>Indiana</option>
                    <option value="IA" <?php if ($propertyData['property_state'] == 'IA') {
                                          echo "selected";
                                        } ?>>Iowa</option>
                    <option value="KS" <?php if ($propertyData['property_state'] == 'KS') {
                                          echo "selected";
                                        } ?>>Kansas</option>
                    <option value="KY" <?php if ($propertyData['property_state'] == 'KY') {
                                          echo "selected";
                                        } ?>>Kentucky</option>
                    <option value="LA" <?php if ($propertyData['property_state'] == 'LA') {
                                          echo "selected";
                                        } ?>>Louisiana</option>
                    <option value="ME" <?php if ($propertyData['property_state'] == 'ME') {
                                          echo "selected";
                                        } ?>>Maine</option>
                    <option value="MD" <?php if ($propertyData['property_state'] == 'MD') {
                                          echo "selected";
                                        } ?>>Maryland</option>
                    <option value="MA" <?php if ($propertyData['property_state'] == 'MA') {
                                          echo "selected";
                                        } ?>>Massachusetts</option>
                    <option value="MI" <?php if ($propertyData['property_state'] == 'MI') {
                                          echo "selected";
                                        } ?>>Michigan</option>
                    <option value="MN" <?php if ($propertyData['property_state'] == 'MN') {
                                          echo "selected";
                                        } ?>>Minnesota</option>
                    <option value="MS" <?php if ($propertyData['property_state'] == 'MS') {
                                          echo "selected";
                                        } ?>>Mississippi</option>
                    <option value="MO" <?php if ($propertyData['property_state'] == 'MO') {
                                          echo "selected";
                                        } ?>>Missouri</option>
                    <option value="MT" <?php if ($propertyData['property_state'] == 'MT') {
                                          echo "selected";
                                        } ?>>Montana</option>
                    <option value="NE" <?php if ($propertyData['property_state'] == 'NE') {
                                          echo "selected";
                                        } ?>>Nebraska</option>
                    <option value="NV" <?php if ($propertyData['property_state'] == 'NV') {
                                          echo "selected";
                                        } ?>>Nevada</option>
                    <option value="NH" <?php if ($propertyData['property_state'] == 'NH') {
                                          echo "selected";
                                        } ?>>New Hampshire</option>
                    <option value="NJ" <?php if ($propertyData['property_state'] == 'NJ') {
                                          echo "selected";
                                        } ?>>New Jersey</option>
                    <option value="NM" <?php if ($propertyData['property_state'] == 'NM') {
                                          echo "selected";
                                        } ?>>New Mexico</option>
                    <option value="NY" <?php if ($propertyData['property_state'] == 'NY') {
                                          echo "selected";
                                        } ?>>New York</option>
                    <option value="NC" <?php if ($propertyData['property_state'] == 'NC') {
                                          echo "selected";
                                        } ?>>North Carolina</option>
                    <option value="ND" <?php if ($propertyData['property_state'] == 'ND') {
                                          echo "selected";
                                        } ?>>North Dakota</option>
                    <option value="OH" <?php if ($propertyData['property_state'] == 'OH') {
                                          echo "selected";
                                        } ?>>Ohio</option>
                    <option value="OK" <?php if ( $propertyData['property_state'] == 'OK') {
                                          echo "selected";
                                        } ?>>Oklahoma</option>
                    <option value="OR" <?php if ($propertyData['property_state'] == 'OR') {
                                          echo "selected";
                                        } ?>>Oregon</option>
                    <option value="PA" <?php if ($propertyData['property_state'] == 'PA') {
                                          echo "selected";
                                        } ?>>Pennsylvania</option>
                    <option value="RI" <?php if ($propertyData['property_state'] == 'RI') {
                                          echo "selected";
                                        } ?>>Rhode Island</option>
                    <option value="SC" <?php if ($propertyData['property_state'] == 'SC') {
                                          echo "selected";
                                        } ?>>South Carolina</option>
                    <option value="SD" <?php if ($propertyData['property_state'] == 'SD') {
                                          echo "selected";
                                        } ?>>South Dakota</option>
                    <option value="TN" <?php if ($propertyData['property_state'] == 'TN') {
                                          echo "selected";
                                        } ?>>Tennessee</option>
                    <option value="TX" <?php if ($propertyData['property_state'] == 'TX') {
                                          echo "selected";
                                        } ?>>Texas</option>
                    <option value="UT" <?php if ($propertyData['property_state'] == 'UT') {
                                          echo "selected";
                                        } ?>>Utah</option>
                    <option value="VT" <?php if ($propertyData['property_state'] == 'VT') {
                                          echo "selected";
                                        } ?>>Vermont</option>
                    <option value="VA" <?php if ($propertyData['property_state'] == 'VA') {
                                          echo "selected";
                                        } ?>>Virginia</option>
                    <option value="WA" <?php if ($propertyData['property_state'] == 'WA') {
                                          echo "selected";
                                        } ?>>Washington</option>
                    <option value="WV" <?php if ($propertyData['property_state'] == 'WV') {
                                          echo "selected";
                                        } ?>>West Virginia</option>
                    <option value="WI" <?php if ($propertyData['property_state'] == 'WI') {
                                          echo "selected";
                                        } ?>>Wisconsin</option>
                    <option value="WY" <?php if ($propertyData['property_state'] == 'WY') {
                                          echo "selected";
                                        } ?>>Wyoming</option>
                  </select>
                  <span style="color:red;"><?php echo form_error('property_state'); ?></span>

                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Zip Code</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="property_zip" value="<?php echo set_value('property_zip') ? set_value('property_zip') : $propertyData['property_zip'] ?>" placeholder="Zip Code" id="postal-code">
                  <span style="color:red;"><?php echo form_error('property_zip'); ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Service Area</label>
                <div class="col-lg-9">
                  <select class="form-control" name="property_area" value="<?php echo set_value('property_area') ?>">
                    <option value="">Select Any Service Area</option>

                    <?php if (!empty($propertyarealist)) {

                      foreach ($propertyarealist as $value) {
                        if ($propertyData['property_area'] == $value->property_area_cat_id) {
                          $selected = 'selected';
                        } else {
                          $selected = '';
                        }

                    ?>

                        <option value="<?= $value->property_area_cat_id ?>" <?= $selected; ?>><?= $value->category_area_name ?></option>
                    <?php }
                    } ?>
                  </select>
                  <span style="color:red;"><?php echo form_error('property_area'); ?></span>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Property Type</label>
                <div class="form-group">
                  <div class="col-sm-3">
                    <label class="radio-inline">
                      <input name="property_type" value="Commercial" type="radio" <?php if ($propertyData['property_type'] == "Commercial") {
                                                                                    echo 'checked';
                                                                                  } else {
                                                                                  } ?> />Commercial
                    </label>
                  </div>
                  <div class="col-sm-3">
                    <label class="radio-inline">
                      <input name="property_type" value="Residential" type="radio" <?php if ($propertyData['property_type'] == "Residential") {
                                                                                      echo 'checked';
                                                                                    } else {
                                                                                    } ?> />Residential
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
                <label class="control-label col-lg-3">Total Yard Square Feet</label>
                <div class="col-lg-9" style="padding-left: 8px;">
                  <input type="text" class="form-control" name="yard_square_feet" id="yard_square_feet" value="<?php echo set_value('yard_square_feet') ? set_value('yard_square_feet') : $propertyData['yard_square_feet'] ?>" placeholder="Total Yard Square Feet">
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
                    <option value="Bent" <?php if ($propertyData['total_yard_grass'] == 'Bent') {
                                            echo "selected";
                                          } ?>>Bent</option>
                    <option value="Bermuda" <?php if ($propertyData['total_yard_grass'] == 'Bermuda') {
                                              echo "selected";
                                            } ?>>Bermuda</option>
                    <option value="Dichondra" <?php if ($propertyData['total_yard_grass'] == 'Dichondra') {
                                                echo "selected";
                                              } ?>>Dichondra</option>
                    <option value="Fine Fescue" <?php if ($propertyData['total_yard_grass'] == 'Fine Fescue') {
                                                  echo "selected";
                                                } ?>>Fine Fescue</option>
                    <option value="Kentucky Bluegrass" <?php if ($propertyData['total_yard_grass'] == 'Kentucky BluegrassAL') {
                                                          echo "selected";
                                                        } ?>>Kentucky Bluegrass</option>
                    <option value="Ryegrass" <?php if ($propertyData['total_yard_grass'] == 'Ryegrass') {
                                                echo "selected";
                                              } ?>>Ryegrass</option>
                    <option value="St. Augustine/Floratam" <?php if ($propertyData['total_yard_grass'] == 'St. Augustine/Floratam') {
                                                              echo "selected";
                                                            } ?>>St. Augustine/Floratam</option>
                    <option value="Tall Fescue" <?php if ($propertyData['total_yard_grass'] == 'Tall Fescue') {
                                                  echo "selected";
                                                } ?>>Tall Fescue</option>
                    <option value="Zoysia" <?php if ($propertyData['total_yard_grass'] == 'Zoysia') {
                                              echo "selected";
                                            } ?>>Zoysia</option>
                    <option value="Centipede" <?php if ($propertyData['total_yard_grass'] == 'Centipede') {
                                                echo "selected";
                                              } ?>>Centipede</option>
                    <option value="Bluegrass/Rye/Fescue" <?php if ($propertyData['total_yard_grass'] == 'Bluegrass/Rye/Fescue') {
                                                            echo "selected";
                                                          } ?>>Bluegrass/Rye/Fescue</option>
                    <option value="Warm Season" <?php if ($propertyData['total_yard_grass'] == 'Warm Season') {
                                                  echo "selected";
                                                } ?>>Warm Season</option>
                    <option value="Cool Season" <?php if ($propertyData['total_yard_grass'] == 'Cool Season') {
                                                  echo "selected";
                                                } ?>>Cool Season</option>
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
                  <input type="text" class="form-control" name="front_yard_square_feet" id="front_yard_square_feet" value="<?php echo set_value('front_yard_square_feet') ? set_value('front_yard_square_feet') : $propertyData['front_yard_square_feet'] ?>" placeholder="Front Yard Square Feet">
                  <span style="color:red;"><?php echo form_error('front_yard_square_feet'); ?></span>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Back Yard Square Feet</label>
                <div class="col-lg-9" style="padding-left: 11px;">
                  <input type="text" class="form-control" name="back_yard_square_feet" id="back_yard_square_feet" value="<?php echo set_value('back_yard_square_feet') ? set_value('back_yard_square_feet') : $propertyData['back_yard_square_feet'] ?>" placeholder="Back Yard Square Feet">
                  <span style="color:red;"><?php echo form_error('back_yard_square_feet'); ?></span>
                </div>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Front Yard Grass Type</label>
                <div class="col-lg-9">
                  <select class="form-control" name="front_yard_grass" id="front_yard_grass">
                    <option value="">Select Front Yard Grass Type</option>
                    <option value="Bent" <?php if ($propertyData['front_yard_grass'] == 'Bent') {
                                            echo "selected";
                                          } ?>>Bent</option>
                    <option value="Bermuda" <?php if ($propertyData['front_yard_grass'] == 'Bermuda') {
                                              echo "selected";
                                            } ?>>Bermuda</option>
                    <option value="Dichondra" <?php if ($propertyData['front_yard_grass'] == 'Dichondra') {
                                                echo "selected";
                                              } ?>>Dichondra</option>
                    <option value="Fine Fescue" <?php if ($propertyData['front_yard_grass'] == 'Fine Fescue') {
                                                  echo "selected";
                                                } ?>>Fine Fescue</option>
                    <option value="Kentucky Bluegrass" <?php if ($propertyData['front_yard_grass'] == 'Kentucky BluegrassAL') {
                                                          echo "selected";
                                                        } ?>>Kentucky Bluegrass</option>
                    <option value="Ryegrass" <?php if ($propertyData['front_yard_grass'] == 'Ryegrass') {
                                                echo "selected";
                                              } ?>>Ryegrass</option>
                    <option value="St. Augustine/Floratam" <?php if ($propertyData['front_yard_grass'] == 'St. Augustine/Floratam') {
                                                              echo "selected";
                                                            } ?>>St. Augustine/Floratam</option>
                    <option value="Tall Fescue" <?php if ($propertyData['front_yard_grass'] == 'Tall Fescue') {
                                                  echo "selected";
                                                } ?>>Tall Fescue</option>
                    <option value="Zoysia" <?php if ($propertyData['front_yard_grass'] == 'Zoysia') {
                                              echo "selected";
                                            } ?>>Zoysia</option>
                    <option value="Centipede" <?php if ($propertyData['front_yard_grass'] == 'Centipede') {
                                                echo "selected";
                                              } ?>>Centipede</option>
                    <option value="Bluegrass/Rye/Fescue" <?php if ($propertyData['front_yard_grass'] == 'Bluegrass/Rye/Fescue') {
                                                            echo "selected";
                                                          } ?>>Bluegrass/Rye/Fescue</option>
                    <option value="Warm Season" <?php if ($propertyData['front_yard_grass'] == 'Warm Season') {
                                                  echo "selected";
                                                } ?>>Warm Season</option>
                    <option value="Cool Season" <?php if ($propertyData['front_yard_grass'] == 'Cool Season') {
                                                  echo "selected";
                                                } ?>>Cool Season</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Back Yard Grass Type</label>

                <div class="col-lg-9">
                  <select class="form-control" name="back_yard_grass" id="back_yard_grass">
                    <option value="">Select Back Yard Grass Type</option>
                    <option value="Bent" <?php if ($propertyData['back_yard_grass'] == 'Bent') {
                                            echo "selected";
                                          } ?>>Bent</option>
                    <option value="Bermuda" <?php if ($propertyData['back_yard_grass'] == 'Bermuda') {
                                              echo "selected";
                                            } ?>>Bermuda</option>
                    <option value="Dichondra" <?php if ($propertyData['back_yard_grass'] == 'Dichondra') {
                                                echo "selected";
                                              } ?>>Dichondra</option>
                    <option value="Fine Fescue" <?php if ($propertyData['back_yard_grass'] == 'Fine Fescue') {
                                                  echo "selected";
                                                } ?>>Fine Fescue</option>
                    <option value="Kentucky Bluegrass" <?php if ($propertyData['back_yard_grass'] == 'Kentucky BluegrassAL') {
                                                          echo "selected";
                                                        } ?>>Kentucky Bluegrass</option>
                    <option value="Ryegrass" <?php if ($propertyData['back_yard_grass'] == 'Ryegrass') {
                                                echo "selected";
                                              } ?>>Ryegrass</option>
                    <option value="St. Augustine/Floratam" <?php if ($propertyData['back_yard_grass'] == 'St. Augustine/Floratam') {
                                                              echo "selected";
                                                            } ?>>St. Augustine/Floratam</option>
                    <option value="Tall Fescue" <?php if ($propertyData['total_yard_grass'] == 'Tall Fescue') {
                                                  echo "selected";
                                                } ?>>Tall Fescue</option>
                    <option value="Zoysia" <?php if ($propertyData['total_yard_grass'] == 'Zoysia') {
                                              echo "selected";
                                            } ?>>Zoysia</option>
                    <option value="Centipede" <?php if ($propertyData['back_yard_grass'] == 'Centipede') {
                                                echo "selected";
                                              } ?>>Centipede</option>
                    <option value="Bluegrass/Rye/Fescue" <?php if ($propertyData['back_yard_grass'] == 'Bluegrass/Rye/Fescue') {
                                                            echo "selected";
                                                          } ?>>Bluegrass/Rye/Fescue</option>
                    <option value="Warm Season" <?php if ($propertyData['back_yard_grass'] == 'Warm Season') {
                                                  echo "selected";
                                                } ?>>Warm Season</option>
                    <option value="Cool Season" <?php if ($propertyData['back_yard_grass'] == 'Cool Season') {
                                                  echo "selected";
                                                } ?>>Cool Season</option>
                  </select>
                </div>

              </div>
            </div>
          </div>

          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Assign Customer</label>
                <div class="multi-select-full col-lg-9">
                  <select class="multiselect-select-all-filtering form-control" name="assign_customer[]" multiple="multiple" id="customer_list">

                    <?php foreach ($customerlist as $value) : ?>
                      <!-- <option value="<?= $value->customer_id ?>"><?= $value->first_name ?> <?= $value->last_name ?></option> -->

                      <option value="<?= $value->customer_id ?>" <?php if (in_array($value->customer_id, $selectedcustomerlist)) { ?>selected <?php  } ?> title="<?= $value->billing_street ?>"   > <?= $value->first_name ?> <?= $value->last_name ?></option>
                    <?php endforeach ?>
                  </select>
                  <span style="color:red;"><?php echo form_error('assign_customer'); ?></span>
                </div>

              </div>
            </div>


            <div class="col-md-6" style="display:<?= $setting_details->is_sales_tax == 1 ? 'block' : 'none' ?> ">
              <div class="form-group">
                <label class="control-label col-lg-3">Sales Tax Area</label>
                <div class="multi-select-full col-lg-9" style="padding-left: 6px;">
                  <select class="multiselect-select-all-filtering form-control" name="sale_tax_area_id[]" multiple="multiple" id="sales_tax">

                    <?php if (!empty($sales_tax_details)) {
                      foreach ($sales_tax_details as $key => $value) {
                    ?>

                        <option value="<?= $value->sale_tax_area_id ?>" <?php if (in_array($value->sale_tax_area_id, $assign_sales_tax)) { ?>selected <?php  } ?>   > <?= $value->tax_name ?> </option>

                    <?php  }
                    } ?>

                  </select>
                </div>
              </div>

            </div>



          </div>

          <div class="row">

            <div class="col-md-6">

              <div class="form-group">
                <label class="control-label col-lg-3">Property Status</label>
                <div class="col-lg-9" style="    padding-left: 6px;">
                  <select class="form-control" name="property_status">
                    <option value="">Select Any Status</option>
                    <option value="1" <?php echo  $propertyData['property_status'] == 1 ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?php echo  $propertyData['property_status'] == 0 ? 'selected' : '' ?>>Non-Active</option>
                  </select>

                </div>
              </div>

            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-lg-3">Assign Program</label>
                <div class="multi-select-full col-lg-9">
                  <!-- <select class="multiselect-select-all-filtering form-control" name="assign_program[]"multiple="multiple" id="program_list"> -->
                  <select class="multiselect-select-all-filtering2 form-control" name="assign_program_tmp[]" multiple="multiple" id="program_list">

                    <option value="">Select any program</option>


                    <?php foreach ($programlist as $value) : ?>

                      <option value="<?= $value->program_id ?>" <?php if (in_array($value->program_id, $selected_program_ids)) { ?>selected <?php  } ?>> <?= $value->program_name ?> </option>

                    <?php endforeach ?>
                  </select>
                  <span style="color:red;"><?php echo form_error('assign_program'); ?></span>
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
                    <option value="1" <?php echo  $propertyData['difficulty_level'] == 1 ? 'selected' : '' ?>>Level 1</option>
                    <option value="2" <?php echo  $propertyData['difficulty_level'] == 2 ? 'selected' : '' ?>>Level 2</option>
                    <option value="3" <?php echo  $propertyData['difficulty_level'] == 3 ? 'selected' : '' ?>>Level 3</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-6">

              <div class="form-group">
                <label class="control-label col-lg-3">Property Info</label>
                <div class="col-lg-9" style="    padding-left: 6px;border: 1px solid #12689b;">


                  <textarea class="summernote_property" name="property_notes"> <?= $propertyData['property_notes']  ?> </textarea>

                  <span style="color:red;"><?php echo form_error('property_notes'); ?></span>
                </div>
              </div>
            </div>

            <div class="col-md-6">


              <div class="program-price-over-ride-container" style="display: <?php echo !empty($selectedprogramlist) ? 'block' : 'none'; ?>;">
                <div class="table-responsive  pre-scrollable">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Program Name</th>
                        <th>Price Override Per Service</th>

                      </tr>
                    </thead>
                    <tbody class="priceoverridetbody">


                      <?php $n = 1;
                      if (!empty($selectedprogramlist)) {


                        foreach ($selectedprogramlist as $value) {


                          $price_override = (isset($value->is_price_override_set) && $value->is_price_override_set == 1) ? floatval($value->price_override) : '';

                          echo '<tr id="trid' . $value->program_id . '" >
                                      <td>' . $value->program_name . '</td>                                                 
                                      <td><input type="number" name="tmp' . $n . '" min="0" value="' . $price_override . '"  class="inpcl form-control" optval="' . $value->program_id . '"  ></td>                                                 
                                                                                
                                   </tr>';

                          $selectedValues[] = $value->program_id;
                          $selectedTexts[] =  $value->program_name;


                          $keyIds[] = array(
                            'program_id' => $value->program_id,
                            'price_override' => $value->price_override,
                            'is_price_override_set' => $value->is_price_override_set,
                          );


                          $n++;
                        }
                      } else {

                        $keyIds = array();
                        $selectedValues = array();
                        $selectedTexts = array();
                      } ?>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <textarea name="assign_program" id="assign_program_ids2" style="display: none;"><?php echo json_encode($keyIds); ?></textarea>
          </div>

          <!-- Start Measure Map Scaffolding -->

          <?php if ($propertyData['measure_map_project_id'] != NULL) {
            $mmpid = $propertyData['measure_map_project_id'];
          } else {
            $mmpid = '';
          } ?>

          <div class="row">
            <div class="col-md-6" style="margin: 16px auto">
              <div class="col-lg-5"></div>
              <div class="form-group">
                <a href="https://app.measuremaponline.com/" target="_blank" rel="noopener noreferrer" class="btn btn-info"><i class="icon-plus2"></i>Add
                  Measure Map Online Lawn
                  Measurement</a>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6" style="margin: 16px auto">
              <div class="form-group">
                <label class="control-label col-lg-3">Measure Map ID</label>
                <div class="col-lg-9" style="padding-left: 11px;">
                  <input type="text" data-toggle="tooltip" title="You can copy this by simply opening your Measure Map project and clicking on the project name at the top of the screen. If you are accessing Measuremap Online from your mobile phone, you will need to turn your phone to landscape view to see the project name at the top of your screen. You can then tap to copy the project ID." class="form-control" name="measure_map_project_id" id="measure_map_project_id" value="<?php echo set_value('measure_map_project_id') ? set_value('measure_map_project_id') : $propertyData['measure_map_project_id']; ?>" placeholder="Please enter the Measure Map Online Project ID" />
                  <span style="color:red;"><?php echo form_error('measure_map_project_id') ?></span>
                </div>
              </div>
            </div>
          </div>
          <div id="desktop-frame">
            <div class="row" style="display: <?php echo $propertyData['measure_map_project_id'] == NULL ? 'none' : 'block'; ?>">
              <div class="col-lg-1 desktop-col" style="margin-left: 40px;"></div>
              <div class="col-md-6" >
                <iframe type="text/html" src="https://app.measuremaponline.com/iframe_api/?pid=<?= $mmpid ?>&key=0txedklOpKxvyalu0leSUODuIZkvfPIW_LCjbk4axk2_DKLw3_v0fc5cjwKWZXAH&ms=imperial&maptype=satellite" width="520" height="520" frameborder="0" crossorigin="anonymous">
                </iframe>
              </div>
            </div>
          </div>
          <div id="mobile-frame">
            <div class="row" style="display: <?php echo $propertyData['measure_map_project_id'] == NULL ? 'none' : 'block'; ?>">
              <div class="col-md-6">
                <iframe type="text/html" src="https://app.measuremaponline.com/iframe_api/?pid=<?= $mmpid ?>&key=0txedklOpKxvyalu0leSUODuIZkvfPIW_LCjbk4axk2_DKLw3_v0fc5cjwKWZXAH&ms=imperial&maptype=satellite" width="375" height="375" frameborder="0" crossorigin="anonymous">
                </iframe>
              </div>
            </div>
          </div>

          <!-- End Measure Map Scaffolding -->

        </fieldset>

        <div class="text-right btn-space">
          <button type="submit" id="saveaddress" class="btn btn-success">Save <i class="icon-arrow-right14 position-right"></i></button>
        </div>
      </form>
    </div>
  </div>
  <!-- /form horizontal -->

</div>
<!-- /content area -->

<div class="mydiv" style="display: none;">

</div>
<!---  Add Service Modal --->
<div id="modal_add_service" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Add Service</h6>
      </div>

      <form name="addService" method="post" enctype="multipart/form-data">

        <div class="modal-body">


          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Add Service</label>

                <select class="form-control" name="job_id" id="selected_job_id" required>
                  <option value="">Select Any Service</option>
                  <?php if ($servicelist) {
                    foreach ($servicelist as $value) { ?>
                      <option value="<?= $value->job_id ?>"><?= $value->job_name ?></option>
                  <?php }
                  } ?>
                </select>
                <input type="hidden" name="add_service_property_id" value="<?= $propertyData['property_id']; ?>">

              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Pricing</label>
                <select class="form-control" name="program_price" id="add_service_program_price" required>
                  <option value="">Select Any Pricing</option>
                  <option value=1>One-Time Service Invoicing</option>
                  <option value=2>Invoiced at Service Completion</option>
                  <option value=3>Manual Billing</option>
                </select>

              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Price Override Per Service</label>
                <input type="number" class="form-control" min=0 name="add_job_price_override" value="0.00">
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="addServiceSubmit" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-------------------------------------------->




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

  var placeSearch, autocomplete;
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


  }

  function fillInAddress(autocomplete, unique) {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

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

        //   alert(val);
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
  var selectedValues = <?php echo  json_encode($selectedValues) ?>;
  var selectedTexts = <?php echo json_encode($selectedTexts) ?>;
  var keyIds = <?php echo json_encode($keyIds) ?>;
  var optionValue = '';
  var optionText = '';
  $n = <?php echo  $n; ?>;




  $(function() {

    reintlizeMultiselectprogramPriceOver();

  });


  function reintlizeMultiselectprogramPriceOver() {

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
  $('form[name="addService"] button[type="submit"]').on('click', function(e) {
    e.preventDefault();

    var serviceId = $('#selected_job_id').val();
    var propertyId = $('input[name="add_service_property_id"]').val();
    var serviceName = $('#selected_job_id option:selected').text();
    var propertyName = $('input[name="property_title"]').val();
    var programName = serviceName + "- Standalone";
    var programPrice = $('select#add_service_program_price').val();
    var priceOverride = $('input[name="add_job_price_override"]').val();

    if (priceOverride > 0) {
      var price_override_set = 1;
    } else {
      var price_override_set = 0;
    }
    var post = [];
    var property = {
      service_id: serviceId,
      property_id: propertyId,
      program_name: programName,
      program_price: programPrice,
      price_override: priceOverride,
      is_price_override_set: price_override_set
    }
    post.push(property);

    $.ajax({

      type: 'POST',
      url: "<?= base_url('admin/job/addJobToProperty') ?>",
      data: {
        post
      },
      dataType: "JSON",
      success: function(data) {


      }

    }).done(function(data) {
      $('#modal_add_service').modal('hide');
      if (data.status == "success") {

        swal(
          'Success!',
          'Service Added Successfully',
          'success'
        )



      } else {
        swal({
          type: 'error',
          title: 'Oops...',
          text: 'Something went wrong!'
        })
      }
    });

  });
</script> <!-- /content area -->