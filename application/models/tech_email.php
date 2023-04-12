  <!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  </head>
  <body class="" style="text-align: center;">

  <div>

    <img style="width:25%" src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$company_details->company_logo ?>"><br>
                        <h1><?= $company_details->company_name ?></h1>

                        <?php
     
                        foreach ($email_data_details as $key => $value) {
                            $customer_name[] = $value->first_name.''.$value->last_name;
                            $job_name[] = $value->job_name;
                            $program_name[] = $value->program_name;
                        }
     

                         $html = str_replace("{TECHNICIAN_NAME}",$email_tech_details->user_first_name.' '.$email_tech_details->user_last_name,$company_email_details->job_sheduled);

                         
                         $html2  = str_replace("{JOB_NAME}",'Jobs :'.implode(", ",$job_name).'<br>',$html);

                         $html3  = str_replace("{PROGRAM_NAME}",'Progrmas : '.implode(", ",$program_name).'<br>',$html2);

                         $html4  = str_replace("{SCHEDULE_DATE}",'Date :'.$assign_date.'<br>',$html3);

                         echo $html4;

                        ?>
                
</div>                    

  </body>
</html>
