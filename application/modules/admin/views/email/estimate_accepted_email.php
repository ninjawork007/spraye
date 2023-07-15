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
                           
					if(isset($is_admin_email) && $is_admin_email == 1){
						echo 'An Estimate has been accepted!<br><br> <b>Estimate #</b> : '.$estimate_id.'<br> <b>Property</b> : '.$property_title.'<br><b>Property Address</b> : '.$email_data_details->property_address;
					}else{
                         $html = str_replace("{CUSTOMER_NAME}",$customerData->first_name.' '.$customerData->last_name,$company_email_details->estimate_accepted);

                         
                         //$html2  = str_replace("{SERVICE_NAME}",'<b>Service</b> :'.$email_data_details->job_name.'<br>',$html);

                         if(empty($program_names)){
                            $program_names = "None";
                        }
                        if(empty($service_names)){
                            $service_names = "None";
                        }

                         $html3  = str_replace("{PROGRAM_NAME}",'<b>Program</b> : '.$program_names.'<br><br>' . '<p><b>Service</b> : '.$service_names.'</p><br>',$html);

                         //$html6  = str_replace("{PROGRAM_NAME}",'<b>Program</b> : '.$program_names.'<br>',$html);
                         //$html3  = str_replace("{SERVICE_NAME}",'<b>Service</b> : '. $service_names .'<br>',$html6);
                        
                         $html4  = str_replace("{PROPERTY_ADDRESS}",'<b>Property Address</b> : '.constructPropertyAddress($email_data_details).'<br>',$html3);

                         $html5  = str_replace("{SCHEDULE_DATE}",'<b>Date</b> :'.$accepted_date.'<br>',$html4);

                         $html5 .='<a href="'.base_url('welcome/unSubscibeEmail/').$customerData->customer_id.'" target="_blank" >Unsubscribe</a>';

                         echo $html5;
					}
                        ?>
                
</div>                    

  </body>
</html>
