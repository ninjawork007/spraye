  <!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css">
        p{
            font-size: 16px !important;
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
                           

                         $html = str_replace("{CUSTOMER_NAME}",$contactData['first_name'].' '.$contactData['last_name'],$company_email_details->job_sheduled);

                         
                         $html2  = str_replace("{SERVICE_NAME}",'<b>Service</b> :'.$job_name.'<br>',$html);

                         $html3  = str_replace("{PROGRAM_NAME}",'<b>Program</b> : '.$program_name.'<br>',$html2);

                         $html4  = str_replace("{PROPERTY_ADDRESS}",'<b>Property Address</b> : '.$propertyData->property_address.'<br>',$html3);

                         $html5  = str_replace("{SCHEDULE_DATE}",'<b>Date</b> :'.$assign_date.'<br>',$html4);

                         $html6  = str_replace("{PROPERTY_NAME}",'<b>Property Name</b>: '.$propertyData->property_title.'<br>',$html5);

                         $html7  = str_replace("{SERVICE_DESCRIPTION}",'<b>Service Description</b>: '.$job_details->job_description.'<br>',$html6);

                         $html8  = str_replace("{SERVICE_NOTES}",'<b>Service Notes</b>: '.$job_details->job_notes.'<br>',$html7);

                         $html8 .='<a href="'.base_url('welcome/unSubscibeEmail/').$customerData->customer_id.'" target="_blank" >Unsubscribe</a>';

                         echo $html8;

                        ?>
                
</div>                    

  </body>
</html>
