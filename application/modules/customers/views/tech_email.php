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
        if (!empty($email_send_details->company_logo)) { ?>
           <img style="width:25%" src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$email_send_details->company_logo ?>">
           <br>
       <?php }?>

                        <h1><?= $email_send_details->company_name ?></h1>

                        <?php
     

                         $html = str_replace("{CUSTOMER_NAME}",$email_send_details->first_name.' '.$email_send_details->last_name,$email_send_details->one_day_prior);

                         
                         $html2  = str_replace("{SERVICE_NAME}",'<b>Jobs</b> :'.$email_send_details->job_name.'<br>',$html);

                         $html3  = str_replace("{PROGRAM_NAME}",'<b>Progrmas</b> : '.$email_send_details->program_name.'<br>',$html2);

                         $html4  = str_replace("{SCHEDULE_DATE}",'<b>Date</b> :'.$email_send_details->job_assign_date.'<br>',$html3);
                         
                         $html5  = str_replace("{PROPERTY_ADDRESS}",'<b>Property Address</b> :'.constructPropertyAddress($email_data_details).'<br>',$html4);

                         $html5 .='<a href="'.base_url('welcome/unSubscibeEmail/').$email_send_details->customer_id.'" target="_blank" >Unsubscribe</a>';


                         echo $html5;

                        ?>
                
</div>                    

  </body>
</html>
