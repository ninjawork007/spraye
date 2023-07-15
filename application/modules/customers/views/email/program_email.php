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
        .button {
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
      .button2 {
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
                           

                         $html = str_replace("{CUSTOMER_NAME}",$customerData->first_name.' '.$customerData->last_name,$company_email_details->program_assigned);

                         $html3  = str_replace("{PROGRAM_NAME}",'<b>Program</b> : '.$email_data_details['program_name'].'<br>',$html);

                         $html4  = str_replace("{PROPERTY_ADDRESS}",'<b>Property Address</b> : '.constructPropertyAddress((object)($email_data_details)).'<br>',$html3);

                         $html5  = str_replace("{SCHEDULE_DATE}",'<b>Date</b> :'.$assign_date.'<br>',$html4);

                         if ($customerData->billing_type != 1){
                            $html5 .='<a href="'.base_url("/welcome/").$company_details->slug.'" target="_blank" ><button class="button2">Access Your Account</button></a>';
                         }

                         $html5 .='<a href="'.base_url('welcome/unSubscibeEmail/').$customerData->customer_id.'" target="_blank" >Unsubscribe</a>';

                         echo $html5;

                        ?>
                <a href="<?=base_url("/welcome/").$setting_details->slug; ?>" target="_blank">Access Your Account</a> 
</div>                    

  </body>
</html>