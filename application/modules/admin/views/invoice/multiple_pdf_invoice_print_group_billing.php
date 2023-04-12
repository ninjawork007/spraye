<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>SPRAYE</title>
        <style type="text/css">
            * {
                font-family: Helvetica, Verdana, Arial, sans-serif;
            }
            table {
                font-size: 13px;
            }
            .invoice-title h2,
            .invoice-title h3 {
                display: inline-block;
            }
            .table>tbody>tr>.no-line {
                border-top: none;
            }
            .table>thead>tr>.no-line {
                border-bottom: none;
            }
            .table>tbody>tr>.thick-line {
                border-top: 1px dotted;
            }
            .logo {
                width: 150px;
                height: auto !important;
                max-height: 100px !important;
            }
            .first_tr {
                background-color: <?=$setting_details->invoice_color;?> !important;
                color: #fff;
            }
            .border-bottom>td {
                border-bottom: 1px solid <?=$setting_details->invoice_color; ?>;
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
            .default-msg>td {
                padding-top: 30px !important;
            }
            .inside-tabel {
                margin-top: 15px;
            }
            .default-font-color {
                color: <?=$setting_details->invoice_color;?>;
            }
            .btn a {
                color: #fff;
                text-decoration: none;
            }
            .paid_logo {
                vertical-align: middle !important;
                text-align: center;
            }
            .table-product-box>tbody>tr>td {
                padding: 1px 5px !important;
            }
            .mannual>tbody>tr>td {
                padding: 0 5px !important;
                line-height: 1.5 !important;
            }
            .application_tbl {
                background: #e5e2e3 0% 0% no-repeat padding-box !important;
                /*opacity: 0.2!important;*/
            }
            .application_tbl thead tr {
                padding: 5px 5px !important;
            }
            .cl_50 {
                width: 50%;
            }
            address {
                margin-bottom: 0 !important;
            }
            .page_break {
                page-break-before: always;
            }
        </style>
        <link href="<?= base_url('assets/admin/assets/css/bootstrap.min.pdf.css') ?>" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    </head>
	<body>
		<?php $setting_address_array = explode(',',$setting_details->company_address,2);
			  foreach ($invoice_details as $index=>$invoice_detail) {  
				  $property_address_array = explode(',', $invoice_detail->property_address);
				  $property_address_first = array_shift($property_address_array);
				  $property_address_last = implode(',',$property_address_array);
				  $billing_street_array = Get_Address_From_Google_Maps($invoice_detail->billing_street);
				  $property_street_array = Get_Address_From_Google_Maps($invoice_detail->property_address);
				  $page_break_class = "";
				  if($index > 0) {
					  $page_break_class = "page_break";
				  }
		?>
		<div class="container <?php echo $page_break_class ?>">
			<table width="100%" style="margin-bottom: 20px;">
				<tr id="top-fold"><!-- START TOP FOLD -->
					<td valign="top">
						<address>
							<strong><?= $setting_details->company_name ?></strong>
							<br>
							<?php if(isset($setting_address_array)){
									if(isset($setting_address_array[0])){echo $setting_address_array[0];}
									if(isset($setting_address_array[1])){ echo '<br/>'.$setting_address_array[1];}
							} ?>
							<br>
							<?php if(isset($setting_details->company_phone_number)){ echo formatPhoneNum($setting_details->company_phone_number);}?>
							<br>
							<?php echo $setting_details->company_email;?>
							<br>
							<?php if($setting_details->web_address!=''){ ?>
								<a href="<?= $setting_details->web_address ?>"><?= $setting_details->web_address ?></a>
							<?php } ?>
						</address>
					</td>
					<td align="right" valign="top">
						<br>
						<img class="logo" src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$setting_details->company_logo ?>">
					</td>
				</tr>
			</table>
			<table width="100%" class="table table-condensed">
				<tr class="first_tr">
					<td><strong>WORK ORDER NO : #<?= $invoice_detail->invoice_id ?></strong></td>
					<td align="right"><strong><?= date("m/d/Y",strtotime($invoice_detail->invoice_date)) ?></strong></td>
				</tr>
			</table>
			<table width="100%" class="table table-condensed">
				<tr class="border-bottom default-font-color">
					<td align="left">CONTACT</td>
					<td align="left">NOTES/INSTRUCTIONS</td>
				</tr>
				<tr>
					<td align="left">
						<?= $invoice_detail->group_billing_details['first_name'].' '.$invoice_detail->group_billing_details['last_name'] ?>
						<br>
							<?php if($property_street_array && is_array($property_street_array) && !empty($property_street_array)){
						  		if(trim($property_street_array['street'])!=''){
									echo $property_street_array['street'].'<br>';
								}
						  		echo $invoice_detail->property_city.', '.$invoice_detail->property_state.', '.$invoice_detail->property_zip;
							} ?>
						<br>
					</td>
					<td align="left"><?= $invoice_detail->notes ?></td>
				</tr>
			</table><!-- END TOP FOLD -->
			<table width="100%" class="table table-condensed" cellspacing="0"><!-- START PROPERTY PROGRAM SERVICE DETAILS -->
				<thead>
					<tr class="first_tr">
						<td class="text-left" width="30%">PROPERTY</td>
						<td class="text-left" width="20%">SERVICE</td>
						<td class="text-left" width="30%">DESCRIPTION</td>
						<td class="text-left" width="10%">DATE</td>
					</tr>
				</thead>
				<tbody>
				<?php if(isset($invoice_detail->jobs) && is_array($invoice_detail->jobs) && !empty($invoice_detail->jobs)){
					foreach($invoice_detail->jobs as $job){ ?>
					<tr class="border-bottom-blank-td">
						<td class="text-left" width="30%">
							<?php if($property_street_array && is_array($property_street_array) && !empty($property_street_array)){
						  		if(trim($property_street_array['street'])!=''){
									echo $property_street_array['street'].'<br>';
								}
						  		echo $invoice_detail->property_city.', '.$invoice_detail->property_state.', '.$invoice_detail->property_zip;
							} ?>
						</td>
						<td class="text-left" width="20%"><?= $job['job_name'] ?></td>
						<td class="text-left" width="20%"><?= $job['job_description'] ?></td>
						<td class="text-left" width="10%">
							<?php if(isset($job['job_assign_date'])){
						  		echo date('m/d/Y', strtotime($job['job_assign_date'])); 
					  		} ?>
						</td>
					</tr>
					<?php }
					}else{ ?>
					<tr class="border-bottom-blank-td">
						<td class="text-left" width="30%">
						<?php if ($property_street_array && is_array($property_street_array) && !empty($property_street_array)){
								if(trim($property_street_array['street'])!=''){
									echo $property_street_array['street'].'<br>';
								}
								echo $invoice_detail->property_city.', '.$invoice_detail->property_state.', '.$invoice_detail->property_zip;
							} ?>
						</td>
						<td class="text-left" width="20%"><?= $invoice_detail->job_name ?></td>
						<td class="text-left" width="30%"><?= $invoice_detail->job_description ?></td>
						<td class="text-left" width="10%">
						<?php if(isset($invoice_detail->job_assign_date)){
								echo date('m/d/Y', strtotime($invoice_detail->job_assign_date));
							}elseif(isset($invoice_detail->job_completed)){
								echo date('m/d/Y', strtotime($invoice_detail->job_completed));
							}elseif(isset($invoice_detail->invoice_created)){
								echo date('m/d/Y', strtotime($invoice_detail->invoice_created));
							}else if(isset($invoice_detail->invoice_date)){ 
								echo date('m/d/Y', strtotime($invoice_detail->invoice_date)); 
							} ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table><!-- END PROPERTY PROGRAM SERVICES SECTION -->
			<table width="100%" class="main table table-condensed"><!-- START APPLICATION & PRODUCTS SECTION -->
			<?php $products = array();
			  if(isset($invoice_detail->jobs) && is_array($invoice_detail->jobs) && !empty($invoice_detail->jobs)){
				  foreach($invoice_detail->jobs as $job){
					  $job_report_id = isset($job['job_report']->report_id) ? $job['job_report']->report_id : '';
					  if($job_report_id != ''){
						  $products[]= array(
							  'job_id'=>$job['job_id'],
							  'job_name'=>$job['job_name'],
							  'report'=>$job['job_report'],
						); 
					  }
				  }
			  }else{
				  $products[]= array(
					  'job_id'=>$invoice_detail->job_id,
					  'report'=>isset($invoice_detail->report_details) ? $invoice_detail->report_details : '',
				  );
			  }
			  foreach($products as $k=>$v){
				  $i=0;
				  $product_details = getProductByJob(array('job_id'=>$v['job_id']));
				  $invoice_report_details =  $v['report'];
				  if($invoice_report_details && ($setting_details->is_wind_speed || $setting_details->is_wind_direction || $setting_details->is_temperature || $setting_details->is_applicator_name || $setting_details->is_applicator_number || $setting_details->is_applicator_phone || $setting_details->is_property_address || $setting_details->is_property_size || $setting_details->is_date || $setting_details->is_time)){
					  if($setting_details->is_wind_speed==1 || $setting_details->is_wind_direction==1 || ($setting_details->is_temperature==1) || ($setting_details->is_applicator_name==1) || ($setting_details->is_applicator_number==1 && $invoice_report_details->applicator_number!='' ) ||  ($setting_details->is_applicator_phone==1 && $invoice_report_details->applicator_phone_number!='' ) || ($setting_details->is_property_address==1) || ($setting_details->is_property_size==1) || ($setting_details->is_date==1) || ($setting_details->is_time==1)){?>
				<tr>
					<td width="100%">
						<table width="100%" class="table table-condensed mannual application_tbl" style="margin-bottom: 20px; margin-top:20px;">
							<tr>
								<td><!-- START SINGLE APPLICATION PART -->
								<table width="100%" class="table table-condensed inside-tabel mannual application_tbl" style="font-size:10px;" cellspacing="0">
									<thead>
										<tr class="first_tr" style="text-transform:uppercase;">
											<td colspan="4" valign="middle">&nbsp;&nbsp;APPLICATION & PRODUCT DETAILS</td>
											<td colspan="3" align="right" valign="middle"><?php if(isset($v['job_name'])) echo $v['job_name']; ?>&nbsp;&nbsp;</td>
										</tr>
										<tr class="border-bottom default-font-color">
											<td class="default-font-color">DATE & TIME</td>
											<td class="default-font-color">PROPERTY ADDRESS</td>
											<td class="default-font-color">PROPERTY SIZE</td>
											<td class="default-font-color">APPLICATOR'S NAME</td>
											<td class="default-font-color">APPLICATOR'S NUMBER</td>
											<td class="default-font-color" align="center">WIND SPEED</td>
											<td class="default-font-color" align="center">TEMP</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td align="left">
												<?php if($setting_details->is_date==1){ 
													echo date("m/d/Y",strtotime( $invoice_report_details->job_completed_date)); 
												} ?>
													<br>
												<?php if($setting_details->is_time==1){
													echo date("g:i A",strtotime( $invoice_report_details->job_completed_time));
												}?>
											</td>
											<td align="left">
												<?php if($setting_details->is_property_address==1){ 
													echo $property_address_first.', '.$invoice_detail->property_city.', '.$invoice_detail->property_state.', '.$invoice_detail->property_zip; 
												} ?>
											</td>
											<td align="left">
												<?php if ($setting_details->is_property_size==1) {
						  							echo $invoice_detail->yard_square_feet;
					  							} ?>
											</td>
											<td align="left">
												<?php if($setting_details->is_applicator_name==1){ 
						  							echo $invoice_report_details->user_first_name.' '.$invoice_report_details->user_last_name;
					  							} ?>
											</td>
											<td align="left">
												<?php if($setting_details->is_applicator_number==1){ 
						  							echo $invoice_report_details->applicator_number;
					  							} ?>
											</td>
											<td align="left">
												<?php if($setting_details->is_wind_speed==1){
						  							echo $invoice_report_details->wind_speed;
						  							if($setting_details->is_wind_direction==1){
														echo $invoice_report_details->direction;
													} } ?>
											</td>
											<td align="left">
												<?php if($setting_details->is_temperature==1){ 
						  							echo $invoice_report_details->temp; 
					  							} ?>
											</td>
										</tr>
									</tbody>
								</table>
								</td><!-- END SINGLE APPLICATION PART -->
							</tr>
							<!-- START PRODUCT DETAILS SECTION -->
						<?php if($product_details){
							if($setting_details->is_product_name || $setting_details->is_epa || $setting_details->is_active_ingredients || $setting_details->is_application_rate || $setting_details->is_estimated_chemical_used || $setting_details->is_chemical_type || $setting_details->is_re_entry_time || $setting_details->is_weed_pest_prevented || $setting_details->is_application_type){ ?>
							<tr>
								<td><!-- START SINGLE APPLICATION PART -->
									<table width="100%" class="table table-condensed inside-tabel mannual application_tbl" style="font-size:10px;" cellspacing="0">
										<thead>
											<tr class="default-font-color">
												<td></td>
												<td class="border-bottom-blank-td default-font-color" align="center">PRODUCT NAME</td>
												<td class="border-bottom-blank-td default-font-color" align="center">EPA #</td>
												<td class="border-bottom-blank-td default-font-color" align="center">ACTIVE INGREDIENTS</td>
												<td class="border-bottom-blank-td default-font-color" align="center">APPLICATION RATE</td>
												<td class="border-bottom-blank-td default-font-color" align="center">Application Type</td>
												<td class="border-bottom-blank-td default-font-color" align="center">CHEMICAL TYPE</td>
												<td class="border-bottom-blank-td default-font-color" align="center">RE-ENTRY TIME</td>
												<td class="border-bottom-blank-td default-font-color" align="center">EST. CHEMICAL USED</td>
												<td class="border-bottom-blank-td default-font-color" align="center">WEED/PEST PREVENTED</td>
												<td></td>
											</tr>
										</thead>
										<tbody>
									<?php foreach($product_details as $key => $product_details_value){
											$ingredientDatails = getActiveIngredient(array('product_id'=>$product_details_value->product_id));
											$ingredientarr = array();
											if($ingredientDatails){
												foreach($ingredientDatails as $key2 => $value2){
													$ingredientarr[] = $value2->active_ingredient.' : '.$value2->percent_active_ingredient.' % ';
												}
											}
											$estimated_chemical_used =estimateOfPesticideUsed($product_details_value,$invoice_detail->yard_square_feet);
											if($setting_details->is_product_name==1 || ($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber) || ($setting_details->is_active_ingredients==1 && $ingredientDatails ) || ($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0 ) || ($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!='') || ($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0 ) || ($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='') || ($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!='') ||  ($setting_details->is_application_type==1 && $product_details_value->application_type!=0)){ ?>
											<tr>
												<td></td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_product_name==1){ 
														echo $product_details_value->product_name;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber){
														echo $product_details_value->epa_reg_nunber;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_active_ingredients==1 && $ingredientDatails){
														echo implode(', ',$ingredientarr);
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0){
														$application_rate = '';
														if(!empty($product_details_value->application_rate) && $product_details_value->application_rate !=0){
															$application_rate = $product_details_value->application_rate.' '.$product_details_value->application_unit.' / '.$product_details_value->application_per;
														}
														echo $application_rate;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
												 <?php if($setting_details->is_application_type==1 && $product_details_value->application_type!=0){
														$application_type ='';
														if($product_details_value->application_type==1){
															$application_type = 'Broadcast';
														}elseif($product_details_value->application_type==2){
															$application_type = 'Spot Spray';
														}elseif($product_details_value->application_type==3){
															$application_type = 'Granular';
														}
														echo $application_type;
												} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
												<?php if($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0){
													$chemical_type = '';
													if($product_details_value->chemical_type==1){
														$chemical_type = 'Herbicide';
													}elseif($product_details_value->chemical_type==2){
														$chemical_type = 'Fungicide';
													}elseif($product_details_value->chemical_type==3){
														$chemical_type = 'Insecticide';
													}elseif($product_details_value->chemical_type==4){
														$chemical_type = 'Fertilizer';
													}elseif($product_details_value->chemical_type==5){
														$chemical_type = 'Wetting Agent';
													}elseif($product_details_value->chemical_type==6){
														$chemical_type = 'Surfactant/Tank Additive';
													}elseif($product_details_value->chemical_type==7){
														$chemical_type = 'Aquatics';
													}elseif($product_details_value->chemical_type==8){
														$chemical_type = 'Growth Regulator';
													}elseif($product_details_value->chemical_type==9){
														$chemical_type = 'Biostimulants';
													}
													echo $chemical_type;
												} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!=''){
														echo $product_details_value->re_entry_time;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!=''){
														echo $estimated_chemical_used;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
												<?php if($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!=''){
													echo $product_details_value->weed_pest_prevented;
												} ?>
												</td>
												<td></td>
											</tr>
										<?php } } ?>
										</tbody>
									</table>
								</td>
							</tr>
					  		<?php } }else{ ?>
						  	<!-- IF NO DETAILS HERE, make sure to close out tags to avoid formatting bugs -->
							</table>
						</td>
					</tr>
					<?php } //end if product details ?>
						</table>
					</td>
				</tr>
				<?php } }else{
				if($product_details){ ?>
				<tr>
					<td width="100%">
						<table width="100%" class="table table-condensed mannual application_tbl" style="margin-bottom: 20px; margin-top:20px;">
							<tr>
								<td>
									<table width="100%" class="table table-condensed inside-tabel mannual application_tbl" style="font-size:12px;" cellspacing="0">
										<thead>
											<tr class="first_tr" style="text-transform:uppercase;">
												<td colspan="4" valign="middle">&nbsp;&nbsp;APPLICATION & PRODUCT DETAILS</td>
												<td colspan="4" align="right" valign="middle"><?php if(isset($v['job_name'])) echo $v['job_name']; ?>&nbsp;&nbsp;</td>
											</tr>
										</thead>
										<tbody>
										<?php if($setting_details->is_product_name || $setting_details->is_epa || $setting_details->is_active_ingredients || $setting_details->is_application_rate || $setting_details->is_estimated_chemical_used || $setting_details->is_chemical_type || $setting_details->is_re_entry_time || $setting_details->is_weed_pest_prevented || $setting_details->is_application_type){?>
											<tr class="default-font-color">
												<td class="border-bottom-blank-td default-font-color" align="center">PRODUCT NAME</td>
												<td class="border-bottom-blank-td default-font-color" align="center" width="30">EPA #</td>
												<td class="border-bottom-blank-td default-font-color" align="center">ACTIVE INGREDIENTS</td>
												<td class="border-bottom-blank-td default-font-color" align="center">APPLICATION RATE</td>
												<td class="border-bottom-blank-td default-font-color" align="center">Application Type</td>
												<td class="border-bottom-blank-td default-font-color" align="center">CHEMICAL TYPE</td>
												<td class="border-bottom-blank-td default-font-color" align="center">RE-ENTRY TIME</td>
												<td class="border-bottom-blank-td default-font-color" align="center">EST. CHEMICAL USED</td>
												<td class="border-bottom-blank-td default-font-color" align="center">WEED/PEST PREVENTED</td>
											</tr>
											<?php foreach($product_details as $key => $product_details_value){
												$ingredientDatails = getActiveIngredient(array('product_id'=>$product_details_value->product_id));
												$ingredientarr = array();
												if($ingredientDatails){
													foreach($ingredientDatails as $key2 => $value2){
														$ingredientarr[] = $value2->active_ingredient.' : '.$value2->percent_active_ingredient.' % ';
													}
												}
												$estimated_chemical_used =estimateOfPesticideUsed($product_details_value,$invoice_detail->yard_square_feet);  
												if($setting_details->is_product_name==1 || ($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber )  || ($setting_details->is_active_ingredients==1 && $ingredientDatails ) || ($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0 ) || ($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!='') || ($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0 ) ||  ($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='' ) || ($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!='') || ($setting_details->is_application_type==1 && $product_details_value->application_type!=0 )) {?>
											<tr>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_product_name==1){
														echo $product_details_value->product_name;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber){ 
														echo $product_details_value->epa_reg_nunber;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_active_ingredients==1 && $ingredientDatails){
														echo implode(', ',$ingredientarr);
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0) {
														$application_rate = '';
														if(!empty($product_details_value->application_rate) && $product_details_value->application_rate !=0){
															$application_rate = $product_details_value->application_rate.' '.$product_details_value->application_unit.' / '.$product_details_value->application_per;
														}
														echo $application_rate;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_application_type==1 && $product_details_value->application_type!=0){
														$application_type ='';
														if($product_details_value->application_type==1){
															$application_type = 'Broadcast';
														}elseif($product_details_value->application_type==2){
															$application_type = 'Spot Spray';
														}elseif($product_details_value->application_type==3){
															$application_type = 'Granular';
														}
														echo $application_type;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0){
														$chemical_type = '';
														if($product_details_value->chemical_type==1){
															$chemical_type = 'Herbicide';
														}elseif($product_details_value->chemical_type==2){
															$chemical_type = 'Fungicide';
														}elseif($product_details_value->chemical_type==3){
															$chemical_type = 'Insecticide';
														}elseif($product_details_value->chemical_type==4){
															$chemical_type = 'Fertilizer';
														}elseif($product_details_value->chemical_type==5){
															$chemical_type = 'Wetting Agent';
														}elseif($product_details_value->chemical_type==6){
															$chemical_type = 'Surfactant/Tank Additive';
														}elseif($product_details_value->chemical_type==7){
															$chemical_type = 'Aquatics';
														}elseif($product_details_value->chemical_type==8){
															$chemical_type = 'Growth Regulator';
														}elseif($product_details_value->chemical_type==9){
															$chemical_type = 'Biostimulants';
														}
														echo $chemical_type;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='' ){
														echo $product_details_value->re_entry_time;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!=''){
														echo $estimated_chemical_used;
													} ?>
												</td>
												<td class="border-bottom-blank-td" align="center">
													<?php if($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!=''){
														echo $product_details_value->weed_pest_prevented;
													} ?>
												</td>
											</tr>
										<?php } } } ?>
										</tbody>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php }	}//end if invoice_report_details
			  $i++; }//end foreach products ?>
			</table><!-- END APPLICATION & PRODUCTS SECTION -->
		</div> <!-- end of main container -->
    <?php } //end foreach invoice_details loop ?>
	</body>
</html>