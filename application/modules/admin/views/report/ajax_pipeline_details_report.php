      <div  class="table-responsive table-spraye">
             <table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe;
    border-radius: 12px;">    
                  <thead>  
                        <tr>
                <th>Customer</th>
                <th>Property</th>
                <th>Estimate Program/Service</th>
                <th>Estimate $</th>	
                <th>Estimate Date</th>	
                <th>Property Status</th>
              </tr>  
            </thead>
            <tbody>
              <?php 
              if (!empty($pipeline_details)) { 
                foreach ($pipeline_details as $value) { ?>
              <tr>         
                <td style="text-transform: capitalize;"><?= $value->first_name.' '.$value->last_name ?></td>
                <td><?= $value->property_address ?></td>
                <td><?= $value->program_name ?></td>
                <td>
                  <?php 
                    $line_total = 0; 
                    $job_details =  GetOneEstimatAllJobPrice(array('estimate_id'=>$value->estimate_id));
                
                    if ($job_details) {

                      foreach ($job_details as $key2 => $value2) {
                        if ($value2['price_override'] != '' && $value2['price_override']!=0 && $value2['is_price_override_set'] == 1) {
                            $cost =  $value2['price_override'];
                            
                        } else if ($value2['price_override'] != '' && $value2['price_override'] == 0 && $value2['is_price_override_set'] == 1){
                            $cost = number_format(0, 2);
                            // die(print_r($job_details));
                        } else {

                          $priceOverrideData = getOnePriceOverrideProgramProperty(array('property_id'=>$value->property_id,'program_id'=>$value->program_id)); 

                          if ($priceOverrideData && $priceOverrideData->price_override!=0 && $priceOverrideData->is_price_override_set == 1) {
                            // $price = $priceOverrideData->price_override;
                            $cost =  $priceOverrideData->price_override;
                              
                          } else if ($priceOverrideData && $priceOverrideData->price_override == 0 && $priceOverrideData->is_price_override_set == 1){
                                $cost = number_format(0, 2);
                          } else {
                            //else no price overrides, then calculate job cost
                            $lawn_sqf = $value->yard_square_feet;
                            $job_price = $value2['job_price'];
                                              
                            //get property difficulty level
                            if(isset($value->difficulty_level) && $value->difficulty_level == 2){
                              $difficulty_multiplier = $setting_details->dlmult_2;
                            }elseif(isset($value->difficulty_level) && $value->difficulty_level == 3){
                              $difficulty_multiplier = $setting_details->dlmult_3;
                            }else{
                              $difficulty_multiplier = $setting_details->dlmult_1;
                            }
                                        
                            //get base fee 
                            if(isset($value2['base_fee_override'])){
                              $base_fee = $value2['base_fee_override'];
                            }else{
                              $base_fee = $setting_details->base_service_fee;
                            }

                            $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier)/1000;

                            //get min. service fee
                            if(isset($value2['min_fee_override'])){
                              $min_fee = $value2['min_fee_override'];
                            }else{
                              $min_fee = $setting_details->minimum_service_fee;
                            }

                            // Compare cost per sf with min service fee
                            if($cost_per_sqf > $min_fee){
                              $cost = $cost_per_sqf;
                            }else{
                              $cost = $min_fee;
                            }
                                
                          } 
                        }

                        //  $line_total += $cost;
                        $line_total += round($cost, 2);
                      }
                      
                    }

                    // apply coupons if exists
                    $total_cost = $line_total;
                    if (isset($value->coupon_details) && !empty($value->coupon_details)){
                      foreach($value->coupon_details as $coupon) {
                        if ($coupon->coupon_amount_calculation == 0) { // flat
                              $coupon_amm = $coupon->coupon_amount;
                        } else { // perc
                              $coupon_amm = ($coupon->coupon_amount / 100) * $total_cost;
                        }
                        $total_cost -= $coupon_amm;
                        if ($total_cost < 0) {
                            $total_cost = 0;
                        }
                      }
                    }
                    $line_total = $total_cost;

                    // apply sales tax
                    $line_tax_amount = 0;
                    if ($setting_details->is_sales_tax==1) {
                        $sales_tax_details =  getAllSalesTaxByProperty($value->property_id);

                        if ($sales_tax_details) {
                            foreach ($sales_tax_details as  $property_sales_tax) {
                              $line_tax_amount += $line_total * $property_sales_tax->tax_value /100;
                            }           
                        }
                        $line_total += $line_tax_amount;
                    }

                    echo '$ '.number_format(($line_total) ,2); 
                    
                  ?>  
                </td>
                <td><?= date('m-d-Y', strtotime($value->estimate_created_date)) ?></td>
                <td width="13%">
                  <?php switch ($value->property_status) {
                    case 0:
                      echo '<span  class="label label-danger myspan">Non-Active</span>';
                      $bg= 'bg-warning';
                      break;
                    case 1:
                      echo '<span  class="label label-success myspan">Active</span>';
                      $bg= 'bg-danger';
                      break;
                    case 2:
                      echo '<span  class="label label-gray myspan">Prospect</span>';
                      $bg= 'bg-gray';
                    break;
                    } 
                  ?>
                  <!-- <?= $value->property_status ?> -->
                </td>
              </tr> 
              <?php 
                  }
                }
              ?>

                  </tbody>
              </table>
           </div>

          
