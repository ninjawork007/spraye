      <div  class="table-responsive table-spraye">
             <table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe;
    border-radius: 12px;">    
                  <thead>  
                       <tr>
                          <th>User</th>
                          <th>Applicator Number</th>
                          <th>Customer</th>
                          <th>Invoice Amount</th>
                          <th>Property Name</th>
                          <th>City</th>
                          <th>Sq. Ft.</th>
                          <th>State</th>
                          <th>Zip</th>

                          <th>Property Address</th>

                          <th>Product</th>
                          <th>Service Name</th>

                          <th>EPA #</th>
                         
                          <th>Application Rate</th>
                          <th>Estimate of Chemical Used</th>
                          <th>Amount of Mixture Applied</th>                          
                          <th>Weed/Pest Prevented</th>

                          <th>Active Ingredients</th>
                          <th>Application Type</th>
                          <th>Re-Entry Time</th>
                          <th>Chemical Type</th>
                          <th>Restricted Product</th>
                          <th>Application Method</th>
                          <th>Area of Property Treated</th>

                          <th>Wind Speed</th>
                          <th>Wind Direction</th>
                          <th>Temperature</th>
                          <th>Date</th>
                          <th>Time Started</th>
                          <th>Time Completed</th>

                      </tr>  
                  </thead>
                  <tbody>


                  <?php if (!empty($report_details)) {  foreach ($report_details as $value) { ?>
                      <tr>
                          <td><?= $value->user_first_name.' '.$value->user_last_name ?></td>
                          <td><?= $value->applicator_number ?></td>
                          <td style="text-transform: capitalize;"><a href="<?= base_url('admin/editCustomer/').$value->customer_id ?>"><?= $value->first_name.' '.$value->last_name  ?> </a></td>
                          <td><?= $value->cost ?></td>
                          <td><?= $value->property_title ?></td>
                          <td><?= $value->property_city ?></td>
                          <td><?= $value->yard_square_feet ?></td>
                          <td><?= $value->property_state ?></td>
                          <td><?= $value->property_zip ?></td>
                          
                          <td><?= $value->property_address  ?></td>

                              <?php 

                             $product_details = reportProductDetails($value->thereportid);
                           
                           ?>
 
                          <td>
                              <?php

                                if ($product_details) {
                                    $product_name =   array_column($product_details, 'product_name');
                                    $numItems = count($product_name);
                                    $i = 0;
                                    foreach ($product_name as $key2 => $value2) {
                                        if(++$i === $numItems) {
                                           echo  '<span>'.$value2.'</span><br>';

                                          } else {
      
                                           echo  '<span>'.$value2.',</span><br>';

                                          }
                                    }
                                }
                             ?>                            
                          </td>
                          <td><?= $value->job_name ?></td>
                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $epa_reg_nunber =   array_column($product_details, 'epa_reg_nunber');
                                    $numItems = count($epa_reg_nunber);
                                    $i = 0;
                                    foreach ($epa_reg_nunber as $key2 => $value2) {
                                       if(++$i === $numItems) {
                                         echo  '<span>'.$value2.'</span><br>';
                                       } else {  
                                          echo  '<span>'.$value2.',</span><br>';
                                       }

                                    }
                                }
                             ?>
                          </td>
                          
                          <td>
                          <?php
                                 
                                if ($product_details) {
                                    $application_rate =   array_column($product_details, 'application_rate');
                                    $numItems = count($application_rate);
                                    $i = 0;
                                    foreach ($application_rate as $key2 => $value2) {
                                         if(++$i === $numItems) { 
                                           echo  '<span>'.$value2.'</span><br>';

                                         } else {
                                            echo  '<span>'.$value2.',</span><br>';

                                         } 
                                    }
                                }
                             ?>                      
                          </td>

                           <td>

                           <?php
                                 
                                if ($product_details) {
                                    $estimate_of_pesticide_used =   array_column($product_details, 'estimate_of_pesticide_used');
                                    $numItems = count($estimate_of_pesticide_used);
                                    $i = 0;
                                    foreach ($estimate_of_pesticide_used as $key2 => $value2) {
                                      if(++$i === $numItems) { 
                                        echo  '<span>'.$value2.'</span><br>';
                                      } else {
                                        echo  '<span>'.$value2.',</span><br>';
                                      } 

                                    }
                                }
                             ?>
                            
                          </td> 

                          
                          <td>
                          <?php
                                 
                                if ($product_details) {
                                    $amount_of_mixture_applied =   array_column($product_details, 'amount_of_mixture_applied');
                                    $numItems = count($amount_of_mixture_applied);
                                    $i = 0;
                                    foreach ($amount_of_mixture_applied as $key2 => $value2) {
                                         if(++$i === $numItems) { 
                                           echo  '<span>'.$value2.'</span><br>';

                                         } else {
                                            echo  '<span>'.$value2.',</span><br>';

                                         } 
                                    }
                                }
                             ?>                      
                          </td>

                           <td>
                            <?php
                                 
                                if ($product_details) {
                                    $weed_pest_prevented =   array_column($product_details, 'weed_pest_prevented');
                                    $numItems = count($weed_pest_prevented);
                                    $i = 0;
                                    foreach ($weed_pest_prevented as $key2 => $value2) {
                                      if(++$i === $numItems) { 
                                        echo  '<span>'.$value2.'</span><br>';
                                      } else {
                                        echo  '<span>'.$value2.',</span><br>';
                                      } 

                                    }
                                }
                             ?>
                          </td>

                          
                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $active_ingredients =   array_column($product_details, 'active_ingredients');
                                    $numItems = count($active_ingredients);
                                    $i = 0;
                                    foreach ($active_ingredients as $key2 => $value2) {
                                      if(++$i === $numItems) { 
                                        echo  '<span>'.$value2.'</span><br>';
                                      } else {
                                        echo  '<span>'.$value2.',</span><br>';
                                      } 

                                    }
                                }
                             ?>
                          </td>

                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $application_type =   array_column($product_details, 'application_type');
                                    $numItems = count($application_type);
                                    $i = 0;
                                    foreach ($application_type as $key2 => $value2) {
                                      if(++$i === $numItems) { 
                                        echo  '<span>'.$value2.'</span><br>';
                                      } else {
                                        echo  '<span>'.$value2.',</span><br>';
                                      } 

                                    }
                                }
                             ?>
                          </td>

                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $re_entry_time =   array_column($product_details, 're_entry_time');
                                    $numItems = count($re_entry_time);
                                    $i = 0;
                                    foreach ($re_entry_time as $key2 => $value2) {
                                      if(++$i === $numItems) { 
                                        echo  '<span>'.$value2.'</span><br>';
                                      } else {
                                        echo  '<span>'.$value2.',</span><br>';
                                      } 

                                    }
                                }
                             ?>
                          </td>

                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $chemical_type =   array_column($product_details, 'chemical_type');
                                    $numItems = count($chemical_type);
                                    $i = 0;
                                    foreach ($chemical_type as $key2 => $value2) {
                                      if(++$i === $numItems) { 
                                        echo  '<span>'.$value2.'</span><br>';
                                      } else {
                                        echo  '<span>'.$value2.',</span><br>';
                                      } 

                                    }
                                }
                             ?>
                          </td>

                          <td>
                                  <?php

                                    if ($product_details) {
                                        $restricted_product =   array_column($product_details, 'restricted_product');
                                        $numItems = count($restricted_product);
                                        $i = 0;
                                        foreach ($restricted_product as $key2 => $value2) {
                                            if (++$i === $numItems) {
                                                echo  '<span>' . $value2 . '</span><br>';
                                            } else {
                                                echo  '<span>' . $value2 . ',</span><br>';
                                            }
                                        }
                                    }
                                    ?>
                              </td>

                              <td>
                                <?php
                                    if ($product_details) {
                                        $application_method =   array_column($product_details, 'application_method');
                                        $numItems = count($application_method);
                                        $i = 0;
                                        foreach ($application_method as $key2 => $value2) {
                                            if (++$i === $numItems) {
                                                echo  '<span>' . $value2 . '</span><br>';
                                            } else {
                                                echo  '<span>' . $value2 . ',</span><br>';
                                            }
                                        }
                                    }
                                ?>
                            </td>

                            <td>
                                <?php
                                    if ($product_details) {
                                        $area_of_property_treated =   array_column($product_details, 'area_of_property_treated');
                                        $numItems = count($area_of_property_treated);
                                        $i = 0;
                                        foreach ($area_of_property_treated as $key2 => $value2) {
                                            if (++$i === $numItems) {
                                                echo  '<span>' . $value2 . '</span><br>';
                                            } else {
                                                echo  '<span>' . $value2 . ',</span><br>';
                                            }
                                        }
                                    }
                                ?>
                            </td>
                          

                          <td><?= round($value->wind_speed,2) .' MPH'  ?></td>
                          <td><?= $value->direction  ?></td>
                          <td><?= $value->temp.' &#8457;'  ?></td>
                          <td><?= date('m-d-Y', strtotime($value->job_completed_date))  ?></td>
                          <td><?= $value->job_start_time  ?></td>
                          <td><?= $value->job_completed_time  ?></td>
                      </tr>
                  
                  <?php } } else {?>

                      <tr>
                        <td>No record found</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                   <?php }  ?>

                  </tbody>
              </table>
           </div>

          
