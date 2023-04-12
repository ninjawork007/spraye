<html>
<head>

	<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
	<title>SPRAYE</title>


<style type="text/css">
<!--
span.cls_002{font-family:Arial,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
div.cls_002{font-family:Arial,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_003{font-family:Arial,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
div.cls_003{font-family:Arial,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_004{font-family:Arial,serif;font-size:20.1px;color:<?= $setting_details->invoice_color  ?>;font-weight:normal;font-style:normal;text-decoration: none}
div.cls_004{font-family:Arial,serif;font-size:20.1px;color:rgb(0,0,255);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_006{font-family:Arial,serif;font-size:9.1px;color:<?= $setting_details->invoice_color  ?>;font-weight:normal;font-style:normal;text-decoration: none}
div.cls_006{font-family:Arial,serif;font-size:9.1px;color:<?= $setting_details->invoice_color  ?>;font-weight:normal;font-style:normal;text-decoration: none}
span.cls_008{font-family:Arial,serif;font-size:8.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
div.cls_008{font-family:Arial,serif;font-size:8.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_009{font-family:Arial,serif;font-size:16.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
div.cls_009{font-family:Arial,serif;font-size:16.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
-->
</style>
<script type="text/javascript" src="3859ce4c-eede-11e8-8f58-0cc47a792c0a_id_1236-test_files/wz_jsgraphics.js"></script>
</head>
<body>
<?php 


$setting_address_array = explode(',', $setting_details->company_address);
$setting_address_first = array_shift($setting_address_array);
$setting_address_last = implode(',',$setting_address_array);


 ?>



<div style="position:absolute;left:50%;margin-left:-306px;top:0px;width:612px;height:792px;border-style:outset;overflow:hidden">
<div style="position:absolute;left:0px;top:0px">
<img src="<?= base_url('assets/admin/image/pdfbackground.png') ?>" width=612 height=792></div>
<div style="position:absolute;left:28.25px;top:52.15px" class="cls_002"><span class="cls_002"><?= $setting_details->company_name ?></span></div>
<img style="position:absolute;left:438.25px;top:26.94px;width:100px;height:100px;"  src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$setting_details->company_logo ?>" >

<div style="position:absolute;left:28.25px;top:67.38px" class="cls_003"><span class="cls_003"><?= $setting_address_first ?></span></div>
<div style="position:absolute;left:28.25px;top:81.16px" class="cls_003"><span class="cls_003"><?= $setting_address_last ?></span></div>
<div style="position:absolute;left:28.25px;top:94.94px" class="cls_003"><span class="cls_003"><?= $setting_details->company_email ?></span></div>
<div style="position:absolute;left:28.25px;top:108.72px" class="cls_003"><span class="cls_003"> </span><A HREF="http://propertyprofm.com/"><?= $setting_details->web_address ?></A> </div>
<div style="position:absolute;left:30.60px;top:132.39px" class="cls_004"><span class="cls_004">INVOICE</span></div>
<div style="position:absolute;left:30.60px;top:168.92px" class="cls_002"><span class="cls_002">BILL TO</span></div>
<div style="position:absolute;left:422.67px;top:168.92px" class="cls_002"><span class="cls_002">INVOICE #</span><span class="cls_003"> <?= $invoice_details->invoice_id ?></span></div>
<div style="position:absolute;left:30.60px;top:184.22px" class="cls_003"><span class="cls_003"><?= $invoice_details->first_name.' '.$invoice_details->last_name ?></span></div>
<div style="position:absolute;left:445.44px;top:182.70px" class="cls_002"><span class="cls_002">DATE</span><span class="cls_003"> <?= $invoice_details->invoice_date ?></span></div>
<div style="position:absolute;left:30.60px;top:198.00px" class="cls_003"><span class="cls_003"><?= $invoice_details->property_city.' , '.$invoice_details->property_state.' , '.$invoice_details->property_zip ?></span></div>
<div style="position:absolute;left:421.51px;top:196.48px" class="cls_002"><span class="cls_002">DUE DATE</span><span class="cls_003"> <?= $invoice_details->invoice_date ?></span></div>
<!-- <div style="position:absolute;left:30.60px;top:236.79px" class="cls_002"><span class="cls_002">BUSINESS NAME</span></div>
<div style="position:absolute;left:30.60px;top:250.57px" class="cls_003"><span class="cls_003">Roger Greer</span></div>
 -->
 <div style="position:absolute;background-color:<?= $setting_details->invoice_color  ?>;top: 278.24px;width:100%;height: 20px;padding-top: 3px;">
	 <span class="cls_003" style="color:#fff;padding-left: 32px">ACTIVITY</span>
	 <span  class="cls_003" style="float: right;color:#fff;padding-right: 65px;padding-top: 5px;" >AMOUNT</span>
 	
 </div>

<div style="position:absolute;left:31.50px;top:304.64px" class="cls_002"><span class="cls_002"><?=$invoice_details->description  ?></span></div>
<div style="position:absolute;left:489.79px;top:304.64px" class="cls_003"><span class="cls_003"><?= number_format($invoice_details->cost,2)  ?></span></div>
<div style="position:absolute;left:31.50px;top:316.64px" class="cls_003"><span class="cls_003"></span></div>
<div style="position:absolute;left:425.74px;top:332.24px" class="cls_003"><span class="cls_003">Subtotal:</span></div>


<div style="position:absolute;left:489.79px;top:332.24px" class="cls_003"><span class="cls_003"><?= number_format($invoice_details->cost,2) ?></span></div>
<div style="position:absolute;left:30.60px;top:375.16px" class="cls_008"><span class="cls_008"></span></div>
<div style="position:absolute;left:312.50px;top:373.17px" class="cls_003"><span class="cls_003">PAYMENT</span></div>
<div style="position:absolute;left:489.79px;top:373.17px" class="cls_003"><span class="cls_003"><?= number_format($invoice_details->cost,2)  ?></span></div>
<div style="position:absolute;left:30.60px;top:387.16px" class="cls_008"><span class="cls_008"></span></div>
<div style="position:absolute;left:312.50px;top:386.95px" class="cls_003"><span class="cls_003">BALANCE DUE</span></div>
<div style="position:absolute;left:538.31px;top:386.72px" class="cls_009"><span class="cls_009">$0.00</span></div>
</div>

</body>
</html>
