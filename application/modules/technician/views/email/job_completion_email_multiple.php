  <!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css">
        p{
            font-size: 16px;
        }
        .button_old {
        background-color: #f44336;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
      }
    </style>
  </head>

  <body class="" style="">

  <div>
   <?php 
   if (!empty($company_details->company_logo)) { ?>
        <img style="width:25%" src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$company_details->company_logo ?>">
    <br>
  <?php }?>
 
                        <h1><?= $company_details->company_name ?></h1>

                        <?php
     
						
                         $html = str_replace("{CUSTOMER_NAME}",$customer_data['customer_name'],$company_email_details->job_completion);
						 $body = "";
						foreach($reports_data as $report_details){
						
                         $html2  = str_replace("{SERVICE_NAME}",'<b>Service</b> :'.$report_details['job_name'].'<br>',$html);

                         $html3  = str_replace("{PROGRAM_NAME}",'<b>Program</b> : '.$report_details['program_name'].'<br>',$html2);

                         $html4  = str_replace("{SCHEDULE_DATE}",'<b>Date</b> :'.$report_details['job_assign_date'].'<br>',$html3);

                         if (!empty($customer_data['technician_message'])) {
                           
                           $html5  = str_replace("{TECHNICIAN_MESSAGE}",'<b>Technician Message</b> :'.$customer_data['technician_message'].'<br>',$html4);
                         
                         } else {

                            $html5  = str_replace("{TECHNICIAN_MESSAGE}",'',$html4);

                         }

                              $additional_info = '';



                         if ($company_email_details->is_wind_speed==1) {
                             $additional_info.=   'Wind Speed: '.$report_details['wind_speed'].'<br>'; 
                         }
                         if ($company_email_details->is_wind_direction==1) {
                             $additional_info.=   'Wind Direction: '.$report_details['direction'].'<br>'; 

                         }
                         if ($company_email_details->is_temperature==1) {
                            $additional_info.=   'Temperature: '.$report_details['temp'].'<br>'; 

                         }
                         if ($company_email_details->is_applicator_name==1) {
                            $additional_info.=   "Applicator's Name: ".$report_details['user_first_name']." ".$report_details['user_last_name']."<br>"; 

                         }
                         if ($company_email_details->is_applicator_number==1 & $report_details['applicator_number']!='' ) {
                            $additional_info.=   "Applicator's #: ".$report_details['applicator_number']."<br>"; 

                         }
                         if ($company_email_details->is_applicator_phone==1 && $report_details['applicator_phone_number']!='' ) {
                            $additional_info.=   "Applicator's Contact: ".$report_details['applicator_phone_number']."<br>"; 

                         }
                         if ($company_email_details->is_property_address==1) {
                            $additional_info.=   'Property Address: '.$customer_data['property_address'].'<br>'; 

                         }
                         if ($company_email_details->is_property_size==1) {
                            $additional_info.=   'Property Size: '.$customer_data['yard_square_feet'].'<br>'; 

                         }
                         if ($company_email_details->is_date==1) {
                            $additional_info.=   'Date: '.date("m/d/Y" ,strtotime( $report_details['job_completed_date'])).'<br>'; 

                         }
                         if ($company_email_details->is_time==1) {
                            $additional_info.=   'Time: '.date("g:i A" ,strtotime( $report_details['job_completed_time'])).'<br>';  
                         }

                         $product_details =  getProductByJob(array('job_id'=>$report_details['job_id']));
                     

                            if ($product_details) {

                              foreach ($product_details as $key => $product_details_value) {

                                 if ($company_email_details->is_product_name || $company_email_details->is_epa || $company_email_details->is_active_ingredients || $company_email_details->is_application_rate || $company_email_details->is_estimated_chemical_used || $company_email_details->is_chemical_type || $company_email_details->is_re_entry_time || $company_email_details->is_weed_pest_prevented || $company_email_details->is_application_type   ) {
                                    $additional_info.= '<br>';
                                  }



                                 if ($company_email_details->is_product_name==1) {
                                   $additional_info.=   'Product name: '.$product_details_value->product_name.'<br>'; 

                                 }
                                 if ($company_email_details->is_epa==1 && $product_details_value->epa_reg_nunber ) {
                                    $additional_info.=   'EPA # '.$product_details_value->epa_reg_nunber.'<br>'; 

                                 }

                                   $ingredientDatails = getActiveIngredient(array('product_id'=>$product_details_value->product_id));
                                        $ingredientarr = array();
                                          if ($ingredientDatails) { foreach ($ingredientDatails as $key2 => $value2) { 
                                           $ingredientarr[] =  $value2->active_ingredient.' : '.$value2->percent_active_ingredient.' % ';
                                          } }


                                 if ($company_email_details->is_active_ingredients==1 && $ingredientDatails ) {
                                   
                                 
                                      $additional_info .=   'Active Ingredients: '.implode(', ',$ingredientarr).'<br>'; 

                                 }
                                 if ($company_email_details->is_application_rate==1  && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0 ) {
                                
                                   $application_rate = '';
                                    if (!empty($product_details_value->application_rate) && $product_details_value->application_rate !=0) {
                                           $application_rate = $product_details_value->application_rate.' '.$product_details_value->application_unit.' / '.$product_details_value->application_per;
                                      }
                                    $additional_info.=   'Application Rate: '.$application_rate.'<br>'; 

                                 }

                                  
                                  $estimated_chemical_used =estimateOfPesticideUsed($product_details_value,$customer_data['yard_square_feet']);  


                                 if ($company_email_details->is_estimated_chemical_used==1 && $estimated_chemical_used!='' ) {
                                    $additional_info.=   'Estimated Chemical Used: '.$estimated_chemical_used.'<br>'; 

                                 }
                                 if ($company_email_details->is_chemical_type==1 && $product_details_value->chemical_type!=0 ) {
                                
                                     $chemical_type = '';                    
                                      if($product_details_value->chemical_type==1) {
                                          $chemical_type = 'Herbicide';
                                      }
                                       else if($product_details_value->chemical_type==2) {
                                          $chemical_type = 'Fungicide';
                                      }
                                       else if($product_details_value->chemical_type==3) {
                                          $chemical_type = 'Insecticide';
                                      }
                                       else if($product_details_value->chemical_type==4) {
                                          $chemical_type = 'Fertilizer';
                                      }
                                       else if($product_details_value->chemical_type==5) {
                                          $chemical_type = 'Wetting Agent';
                                      }
                                       else if($product_details_value->chemical_type==6) {
                                          $chemical_type = 'Surfactant/Tank Additive';
                                      }
                                       else if($product_details_value->chemical_type==7) {
                                          $chemical_type = 'Aquatics';
                                      }
                                       else if($product_details_value->chemical_type==8) {
                                          $chemical_type = 'Growth Regulator';
                                      } else if($product_details_value->chemical_type==9) {
                                          $chemical_type = 'Biostimulants';
                                      }
                                     $additional_info.=   'Chemical Type: '.$chemical_type.'<br>'; 

                                 }
                                 if ($company_email_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='' ) {
                                    $additional_info.=   'Re-Entry Time: '.$product_details_value->re_entry_time.'<br>'; 

                                 }
                                 if ($company_email_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!='' ) {
                                    $additional_info.=   'Weed/Pest Prevented: '.$product_details_value->weed_pest_prevented.'<br>'; 

                                 }
                                 if ($company_email_details->is_application_type==1 && $product_details_value->application_type!=0 ) {
                                      $application_type ='';
                                 
                                      if ($product_details_value->application_type==1) {
                                          $application_type = 'Broadcast';
                                      } else if($product_details_value->application_type==2) {
                                          $application_type = 'Spot Spray';
                                      } elseif ($product_details_value->application_type==3) {
                                          $application_type = 'Granular';          
                                      }
                                      $additional_info.=   'Application Type: '.$application_type.'<br>'; 

                                 }                              
                              
                             }
                          }
							                     
                          $html6  = str_replace("{ADDITIONAL_INFO}",$additional_info,$html5);
                 
						

						
                         $html7  = str_replace("{PROPERTY_ADDRESS}",'<b>Property Address</b> :'.$customer_data['property_address'].'<br>',$html6);

							
                         $body .= $html7.'<br><br>';
							
						}
							$body .= '<a href="'.base_url('welcome/unSubscibeEmail/').$customer_data['customer_id'].'" target="_blank" >Unsubscribe</a>';
							
							echo $body;
                        ?>
                
</div>                    

  </body>
</html>
