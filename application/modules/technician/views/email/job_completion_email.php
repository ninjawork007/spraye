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
    .files-thumbnail {
        max-width: 250px !important;
        margin-left: auto;
        margin-right: auto;
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
        //var_dump($email_data_details);
         $html = str_replace("{CUSTOMER_NAME}",$contactData['first_name'].' '.$contactData['last_name'],$company_email_details->job_completion);

         $html2  = str_replace("{SERVICE_NAME}",'<b>Service</b>: '.$email_data_details->job_name.'<br>',$html);

         $html3  = str_replace("{PROGRAM_NAME}",'<b>Program</b>: '.$email_data_details->program_name.'<br>',$html2);

         $html4  = str_replace("{SCHEDULE_DATE}",'<b>Date</b>: '.$email_data_details->job_assign_date.'<br>',$html3);

         if (strcmp($email_data_details->technician_message,'') != 0) {

           $html5  = str_replace("{TECHNICIAN_MESSAGE}",'<b>Technician Message</b>: '.$email_data_details->technician_message.'<br>',$html4);

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
            $additional_info.=   'Property Address: '.$email_data_details->property_address.'<br>';
         }

         if ($company_email_details->is_property_size==1) {
            $additional_info.=   'Property Size: '.$email_data_details->yard_square_feet.'<br>';
         }

         if ($company_email_details->is_date==1) {
            $additional_info.=   'Date: '.date("m/d/Y" ,strtotime( $report_details['job_completed_date'])).'<br>';
         }

         if ($company_email_details->is_time==1) {
            $additional_info.=   'Time: '.date("g:i A" ,strtotime( $report_details['job_completed_time'])).'<br>';
         }
//        $invoice_details = $email_data_details->invoice_details;
//        var_dump($invoice_details);
//        $report_id = array();
//        $products = array();
//        if (isset($invoice_details) && is_array($invoice_details) && !empty($invoice_details) ) {
//            foreach($invoice_details as $job){
//                $job_report_id = isset($job['job_report']->report_id) ? $job['job_report']->report_id : '';
//                echo $job_report_id;
//                if ($job->report_id != '') {
//                    array_push($report_id, $job->report_id);
//                    //echo print_r($job->report_id);//AQUI
//                    $products[]= array(
//                        'job_id'=>$job['job_id'],
//                        'job_name'=>$job['job_name'],
//                        'report'=>$job['job_report'],
//                    );
//                }
//            }
//        } else {
//            array_push($report_id, $invoice_details->report_id);
//            $products[]= array(
//                'job_id'=>$invoice_details->job_id,
//                'report'=>isset($invoice_details->report_details) ? $invoice_details->report_details : '',
//            );
//        }


         $product_details =  getProductByReport(array('report_id'=>$email_data_details->report_id));
            //var_dump($product_details);
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

                  $estimated_chemical_used =estimateOfPesticideUsed($product_details_value,$email_data_details->yard_square_feet);

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
        if(isset($email_data_details->tech_customer_note)){
           $additional_info.= '<br>Technician Notes: '.$email_data_details->tech_customer_note->note_contents.'<br><br>';
           if(isset($email_data_details->tech_customer_note_files))
           {
              foreach($email_data_details->tech_customer_note_files as $file)
              {
                 $ext = pathinfo( CLOUDFRONT_URL.$file->file_key, PATHINFO_EXTENSION);
                 if($ext != 'pdf')
                 {
                    $additional_info.= '<img src="'.CLOUDFRONT_URL.$file->file_key.'" class="files-thumbnail" width="200"><br>';
                 }
              }
           }
        }

         $html6  = str_replace("{ADDITIONAL_INFO}",$additional_info,$html5);
         $propertyConditions= "";
         if(is_array($property_conditions) && !empty($property_conditions)){
              foreach($property_conditions as $condition){
                  $propertyConditions.=  $condition.'<br>';
              }
         }
         $html7  = str_replace("{PROPERTY_CONDITIONS}",$propertyConditions,$html6);

         $html8  = str_replace("{PROPERTY_ADDRESS}",'<b>Property Address</b>: '.$email_data_details->property_address.'<br>',$html7);




        if(isset($email_data_details->billing_type) && $email_data_details->billing_type ==1){
            $html8 .='<a href="'.base_url('welcome/unsubscribePropertyEmail/').$contactData['contact_id'].'" target="_blank" >Unsubscribe</a>';
        }else{
            $html8 .='<a href="'.base_url('welcome/unSubscibeEmail/').$contactData['contact_id'].'" target="_blank" >Unsubscribe</a>';
        }

         echo $html8;

        ?>
    </div>
  </body>
</html>