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

      <h1><?=$company_details->company_name ?></h1>

      <?php
        if(isset($is_admin_email) && $is_admin_email == 1){
        echo 'A Purchase Order 3-Way Match failed!<br><br> <b>Purchase Order #</b> : '.$purchase_order_id.'<br> <b>Location</b> : '.$location_name.'<br><b>Sub-Location</b> : '.$purchase_data_details->sub_location_name.'<br><b>Location Address</b> : '.$purchase_data_details->location_street.' '.$purchase_data_details->location_city.', '.$purchase_data_details->location_state.' '.$purchase_data_details->location_zip;
    }else{
                
      $html = str_replace("{VENDOR_NAME}",$vendorData->vendor_name.'<br>',$company_email_details->purchase_order_accepted);

      // $html2  = str_replace("{ITEMS}",'<b>Items</b> :'.$purchase_data_details->items.'<br>',$html);

      $html3  = str_replace("{ITEMS}",'<b>Items</b> : '.$purchase_data_details->items.'<br>',$html);

      $html4  = str_replace("{LOCATION_ADDRESS}",'<b>Location Address</b> : '.$purchase_data_details->location_street.' '.$purchase_data_details->location_city.', '.$purchase_data_details->location_state.' '.$purchase_data_details->location_zip.'<br>',$html3);

      $html5  = str_replace("{SCHEDULE_DATE}",'<b>Date</b> :'.$accepted_date.'<br>',$html4);

      $html5 .='<a href="'.base_url('welcome/unSubscibeEmail/').$vendorData->vendor_id.'" target="_blank" >Unsubscribe</a>';

      echo $html5;
    }

      ?>
                
</div>                    

  </body>
</html>
