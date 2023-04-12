<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SPRAYE</title>
<style type="text/css">
* {
  font-family: Helvetica,Verdana, Arial, sans-serif;
}
table {
  font-size: 14px;
  
}
font-family: Helvetica,Verdana, Arial, sans-serif;

.table>tbody>tr>.no-line {
  border-top: none;
}
.table>thead>tr>.no-line {
  border-bottom: none;
}
.table>tbody>tr>.thick-line { 
  border-top: 1px dotted;
}
.table-borderless td,
.table-borderless tr {
  border: 0;
}
.border-bottom>td {
  border-bottom: 1px solid <?=$setting_details->invoice_color;
  ?>;
}
.border-bottom-blank-td>td,
.border-bottom-blank-td {
  border-bottom: 1px solid #ccc !important;

}
.border-bottom-blank-last>td,
.border-bottom-blank-last {
  border-bottom: 1px solid #999 !important;
}
.blank_tr td {
  padding: 9px !important;
}
.button-tr>td {
  padding-top: 20px !important;
}
.default-font-color {
  color: <?=$setting_details->invoice_color;
  ?>;
}
	table{
		margin:1px 0px!important;
		padding:2px!important;
	}
	.main-container{
		margin-top:0px;
		margin-bottom:0px!important;
		padding-bottom:0px!important;
	}
</style> 
  <link href="<?= base_url('assets/admin/assets/css/bootstrap.min.pdf.css') ?>" rel="stylesheet" id="bootstrap-css">
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</head>
<body>	
<!--get unique route count -->
	<?php 
	$route_ids = array();
	foreach($route_results as $k=>$route){
    	if(isset($route['route_id'])){
		  if(is_array($route_ids) && !in_array($route['route_id'],$route_ids)){
			$route_ids[$route['route_id']][]=$route;
		  }
		}
	}
	foreach($route_ids as $id => $route_details){ 
		if(is_array($route_details)){
			$footerflag = count($route_details)-1;
			foreach($route_details as $key=>$route){
				//echo '#'. $route['route_id'];
				$property_address_array = explode(',', $route['property_address']);
				$property_address_first = array_shift($property_address_array);
				$property_address_last = implode(',',$property_address_array);
				$billing_street_array = Get_Address_From_Google_Maps($route['billing_street']);
				$property_street_array = Get_Address_From_Google_Maps($route['property_address']);
				
				if($key == 0){?>
				<!-- page header  -->
				<div class="container main-container" style="page-break-after:always;">
							<table width="100%" class="table table-borderless" style="margin: 0px!important; padding:0px!important;">
							  <tr>
								<td colspan="2"  ><span class='default-font-color'>Technician Name: </span><strong><?php echo $route_details[0]['user_first_name'].' '.$route_details[0]['user_last_name'] ?></strong></td>
								<td colspan="2"  text-align="left"><span class='default-font-color'>Route Name: </span> <?php echo 
								  isset($route_details[0]['route_name']) && !empty($route_details[0]['route_name']) ? $route_details[0]['route_name'] : ""
								  ?> </td>
								<td colspan="2"  text-align="left"><span class='default-font-color' >Date: </span><?=  
								isset($route_details[0]['date']) && !empty($route_details[0]['date']) ? date("m/d/Y" ,strtotime( $route_details[0]['date'])) : "" ?></td>
							  </tr>
							  <tr>
									<td colspan="6" ><span class='default-font-color' >Route Notes: </span> <?=  
									  isset($route_details[0]['route_note']) && !empty($route_details[0]['route_note']) ? $route_details[0]['route_note'] : ""
									  ?>  </td>
								</tr>
							</table>
					<!--end page header-->
				<?php }?>
				    <!--route data-->
						  <table width="100%" class="table table-bordered border-primary" style="border-spacing: 5px 0; page-break-inside:avoid;">
							<tbody style=" border: 2px solid;">   
								<tr style="border-top: 2px solid;" >
								  <td colspan="2" style="padding:5px;font-size: 10px; ">
									<strong><?php echo $route['first_name'].' '.$route['last_name'] ?></strong>
								  </td>
								  <td colspan="2" text-align="left" style="font-size: 10px;">
									<strong>Time: <?= isset($route['Departure']) && !empty($route['Departure']) ? date("h:i a" ,strtotime( $route['Departure'])) : "" ?></strong>
								  </td>
								  <td colspan="2" style="padding:5px; font-size: 10px;" valign="top"><strong>
								  <?= isset($route['property_title']) && !empty($route['property_title']) ? $route['property_title'] : ""?> </strong>
								  </td>
								</tr>
								<tr style="padding:0px!important;">
              						<td colspan="2" valign="top" style="font-size: 10px;">
								 
									<?php if ($route['billing_street']) {
										  $customer_billing_address = explode(',',$route['billing_street']);
										  $customer_address_first = array_shift($customer_billing_address);
										  $customer_address_last = implode(',',$customer_billing_address);

										  echo $customer_address_first.'<br>';

										  // support for both kinds of address input

										  if ($customer_address_last) {
											echo $customer_address_last;
										  } else {
											echo $route['billing_city'].', '.$route['billing_state'].', '.$route['billing_zipcode'];
										  }
										}
									  ?>
                      					<br>
                					</td>
									<td colspan="2"  valign="top" text-align="left" style="font-size: 10px;">
										<strong><span>Mobile: </span></strong> <?= $route['phone'] ?>
										<br>
									  	<strong><span>Home: </span></strong><?=$route['home_phone'] > 0 ? $route['home_phone'] : '' ?>  
									    <br>
									    <strong><span>Work: </span></strong><?=$route['work_phone'] > 0 ? $route['work_phone'] : '' ?>  
									</td>
              						<td colspan="2" valign="top" style="font-size: 10px;">
									<?php if ($property_street_array && is_array($property_street_array) && !empty($property_street_array) ) {
									  if (trim($property_street_array['street'])!='') {
										echo $property_street_array['street'].'<br>';
									  }
									  echo $route['property_city'].', '.$route['property_state'].', '.$route['property_zip'];
									} ?>
									</td>
								</tr>
								<tr style="border-top: 2px solid; border-bottom: 2px solid;" >
								  <td colspan="6" style="padding:0px 5px; font-size: 20px; color: red; text-transform: uppercase; text-align: left"><?= $route['notes'] ?></td>
								</tr>
								<tr style="">
								  <td style="font-size: 10px; color:#000;background-color:#D5DBDB" ><strong >SERVICE NAME(S)</strong></td>
								  <td style="font-size: 10px; text-align:center"  colspan="2" ><?= isset($route['service_name']) && !empty($route['service_name']) ? $route['service_name'] : "" ?></td>

								  <td style="font-size: 10px; color:#000;background-color:#D5DBDB" ><strong>SERVICE NOTES</strong></td>
								  <td colspan="2" style="font-size: 10px; text-align:center"><?= isset($route['service_notes']) && !empty($route['service_notes']) ? $route['service_notes'] : "" ?></td>
								</tr>
								<tr>
								  <td style="font-size: 10px; color:#000;background-color:#D5DBDB" ><strong>PROGRAM NAME</strong></td>
								  <td style="font-size: 10px; text-align:center"  colspan="2" ><?= isset($route['program_name']) && !empty($route['program_name']) ? $route['program_name']  : "" ?></td>

								  <td style="font-size: 10px; color:#000;background-color:#D5DBDB" ><strong>PROGRAM NOTES</strong></td>
								  <td colspan="2" style="font-size: 10px; text-align:center" ><?= isset($route['program_notes']) && !empty($route['program_notes']) ? $route['program_notes'] : "" ?></td>
								</tr>
								<tr>
								  <td style="font-size: 10px; color:#000;background-color:#D5DBDB"><strong>GRASS TYPE (FRONT/BACK)</strong></td>
								  <td style="font-size: 10px;text-align:center"  colspan="2">
								  <?= isset($route['front_yard_grass'])  && !empty($route['front_yard_grass']) ? $route['front_yard_grass'] : "" ?> / <?= isset($route['back_yard_grass']) && !empty($route['back_yard_grass']) ? $route['back_yard_grass'] : "" ?>
								  </td>

								  <td style="font-size: 10px; color:#000;background-color:#D5DBDB" ><strong>LAWN SIZE (FRONT/BACK)</strong></td>
								  <td style="text-align:center;font-size: 10px;" colspan="2">
								  <?= isset($route['yard_square_feet']) && !empty($route['yard_square_feet']) ? $route['yard_square_feet'] : "" ?> <span>sq.ft</span>
								</td>
								</tr>
								<tr class="border-bottom" style="border" >
								  <td style="padding:5px; font-size: 10px; text-align:right font-size: 10px; color:#000;background-color:#EBF5FB;" ><strong>START/COMPLETE TIME:</strong></td>
								  <td style="text-align:center color:#000;background-color:#F4F6F6;">  </td>
								  <td style="padding:5px; font-size: 10px; text-align:right color:#000;background-color:#EBF5FB;"><strong>WIND SPEED: </strong></td>
								  <td style="text-align:center color:#000;background-color:#F4F6F6;"></td>
								  <td style="padding:5px; font-size: 10px; text-align:right color:#000;background-color:#EBF5FB;" ><strong>TEMPERATURE:</strong></td>
								  <td style="text-align:center color:#000;background-color:#F4F6F6;"> </td>
								</tr>
								<tr>
								  <td style="padding:5px; font-size: 10px; color:#000;background-color:#EBF5FB;" ><strong>PRODUCTS USED</strong></td>
								  <td text-align="left" colspan="5" style="padding:5px; font-size: 10px; color:#000;background-color:#F4F6F6" >
								  <?php foreach($route['product_used'] as $p => $product){ ?>
									  <?= isset($product->product_name) && !empty($product->product_name) ? $product->product_name : "" ?> 
									  <!-- (Estimated Mixture Used:)______________<strong> </strong></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    (Amount Applied:)______________ <br> -->
									  <!--USE FOR ESTIMATED USED WHEN FUNCTION IS CORRECTED AND WORKING RG12/13/21-->
									  <span style="font-size: 10px "> (Estimated Mixture Used:)<strong> <?= isset($product->estimate_use) && !empty($product->estimate_use) ? $product->estimate_use : "" ?> </strong></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    (Amount Applied:)______________ <br>
									  <!-- END OF ESTIMATED USED-->
									  <!--<span> (Estimated Mixture Used: &nbsp; <?= $route['amount_used'] ?> &nbsp;)</span> -->
									  <?php } ?> 
								  </td>
								  <!-- <td style="text-align=left color:#000;background-color:#F4F6F6" colspan="2">(Amount Applied:)______________</td> -->
								</tr>
								<tr>
								  <td style="padding:5px; font-size: 10px; color:#000;background-color:#EBF5FB;" ><strong>PROPERTY INFO</strong>
								  </td>
								  <td colspan="5" style="font-size: 10px "><?= isset($route['property_notes']) &&!empty($route['property_notes']) ? $route['property_notes'] : "" ?>
								  </td>
								</tr>
								<tr>
								  <td style="padding:5px; font-size: 10px; color:#000;background-color:#EBF5FB;" ><strong>ADD NOTE/LAWN<br>CONDITION/SKIP REASON</strong>
								  </td>
								  <td colspan="5" style="color:#000;background-color:#F4F6F6"></td>   
								</tr>
							</tbody>
						  </table>
					<!--end route data--> 
				 	<!--page footer-->
				<?php if($key == $footerflag){?>
						<table width="100%" class="table table-bordered border-primary" style="background-color: <?=$setting_details->invoice_color; ?> !important; color: #fff;">
						  <tr>
							<td style="text-align:left; text-transform: uppercase;" ><strong><?php echo $route['user_first_name'].' '.$route['user_last_name'] ?></strong>
							</td>
							<td style="text-align:center; text-transform:uppercase;">
							  <strong>
								<?=$setting_details->company_name; ?>
							  </strong>
							</td>
							<td style="text-align:right;">
							<?= isset($route['date']) && !empty($route['date']) ? date("m/d/Y" ,strtotime( $route['date'])) : "" ?>
							</td>
						  </tr>
						</table>
					<!--end page footer-->
</div><?php }}}}?></body></html>