<div  class="table-responsive table-spraye">
            <table  class="table datatable-filter-custom">
               <thead>
                  <tr>
                     <th><input type="checkbox" id="select_all" <?php if (empty($estimate_details)) { echo 'disabled'; }  ?>    /></th>
                     <th>Estimate #</th>
                     <th>Customer Name</th>
                     <th>Property</th>
                     <th>Total Estimate Cost</th>
                     <th>Status</th>
                     <th>Program</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($estimate_details)) { 
                     foreach ($estimate_details as $value) { ?>      
                  <tr>
                     <td><input  name="group_id" type="checkbox"  value="<?=$value->estimate_id.':'.$value->customer_id ?>" estimate_id="<?=$value->estimate_id ?>" class="myCheckBox" /></td>
                     <td><a href="<?= base_url('admin/Estimates/editEstimate/').$value->estimate_id ?>"><?= $value->estimate_id; ?></a></td> 
                     <td style="text-transform: capitalize;"><?= $value->first_name.' '.$value->last_name ?></td>
                     <td><?= $value->property_address ?></td>                     
                     <td>
    
                        <?php 
                        $line_total = 0; 
                        $job_details =  GetOneEstimatAllJobPrice(array('estimate_id'=>$value->estimate_id));
                    
                        $sales_tax_details =  getAllSalesTaxByProperty($value->property_id);



                    


                          if ($job_details) {

                              foreach ($job_details as $key2 => $value2) {

                              if ($value2['price_override']!=0) {
                                 $cost =  $value2['price_override'];
                              } else {

                                  $priceOverrideData = getOnePriceOverrideProgramProperty(array('property_id'=>$value->property_id,'program_id'=>$value->program_id)); 

                                  if ($priceOverrideData && $priceOverrideData->price_override!=0 ) {
                                        // $price = $priceOverrideData->price_override;
                                        $cost =  $priceOverrideData->price_override;
                          
                                  } else {
                                    
                                     $cost =  $value2['job_price'] * $value->yard_square_feet/1000;
                                 
                                  } 
                              }


                        $line_tax_amount = 0;
                        if ($setting_details->is_sales_tax==1) {     

                           if ($sales_tax_details) {
                              foreach ($sales_tax_details as  $property_sales_tax) {
                             //   echo $property_sales_tax->tax_name. ' ('.$property_sales_tax->tax_value.'%)<br>';
                              // echo $cost * $property_sales_tax->tax_value /100 . '<br>';
                                $line_tax_amount += $cost * $property_sales_tax->tax_value /100;
                                
                             }           
                           
                           } 

                        } 
  
                            $line_total += $cost+$line_tax_amount;              
                              }
                           
                          }
                          echo '$ '.number_format(($line_total) ,2); 
                         
                         ?>

                           
                        </td>
                     <td width="13%">
                        <?php switch ($value->status) {
                           case 0:
                             echo '<span  class="label label-warning myspan">Draft</span>';
                             $bg= 'bg-warning';
                             break;
                           case 1:
                             echo '<span  class="label label-danger myspan">Sent</span>';
                             $bg= 'bg-danger';
                             break;
                           
                           case 2:
                             echo '<span  class="label label-success myspan">Accepted</span>';
                            $bg= 'bg-success';
                            break;

                            
                           } ?>
                        <div class="btn-group">
                           <a href="#" class="label <?= $bg  ?> dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                           <ul class="dropdown-menu dropdown-menu-right" >
                              <li class="changestatus"  estimate_id="<?= $value->estimate_id ?>" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Draft</a></li>
                              <li class="changestatus" estimate_id="<?= $value->estimate_id ?>" value="1" ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>
                              <li class="changestatus" estimate_id="<?= $value->estimate_id ?>" value="2" ><a href="#"><span class="status-mark bg-success position-left"></span> Accepted</a></li>
 
                           </ul>
                        </div>
                     </td>
                     <td><?= $value->program_name ?></td>
                     <td class="table-action">
                        <ul style="list-style-type: none; padding-left: 0px;">

                           <li style="display: inline; padding-right: 10px;">
                              <a  class="email button-next" id="<?= $value->estimate_id ?>"  customer_id="<?= $value->customer_id ?>"    ><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a>
                           </li>


                           <li style="display: inline; padding-right: 10px;">
                              <a href="<?= base_url('admin/Estimates/pdfEstimate/').$value->estimate_id ?>" target="_blank" class=" button-next"><i class=" icon-file-pdf position-center" style="color: #9a9797;"></i></a>
                           </li>
                           <li style="display: inline; padding-right: 10px;">
                              <a href="<?= base_url('admin/Estimates/printEstimate/').$value->estimate_id ?>" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a>
                           </li>
                        </ul>
                     </td>
                  </tr>
                  <?php  }  } ?>
               </tbody>
            </table>
         </div>
