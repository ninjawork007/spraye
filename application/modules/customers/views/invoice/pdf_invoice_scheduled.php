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
    height: auto!important;
	max-height:100px!important;
  }
  .first_tr {
    background-color: <?=$setting_details->invoice_color;
    ?> !important;
    color: #fff;
  }
  .first_tr td, td{
	  padding:5px;
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
  .default-msg>td {
    padding-top: 30px !important;
  }
  .inside-tabel {
    margin-top: 15px;
  }
  .default-font-color {
    color: <?=$setting_details->invoice_color;
    ?>;
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
	opacity: 0.2!important;
  }

  .application_tbl thead tr{
    padding: 5px 5px!important;
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
  .page-break-avoid {
	page-break-inside: avoid;
  }
  div#paystub-container {
	max-height:30%!important;
  }
  #paystub-table td {
	  padding-top: 0px!important;
	  padding-bottom: 0px!important;
  }

  </style>
  <link href="<?= base_url('assets/admin/assets/css/bootstrap.min.pdf.css') ?>" rel="stylesheet" id="bootstrap-css">
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</head>
<body>
  <?php
    $property_address_array = explode(',', $invoice_details->property_address);
    $property_address_first = array_shift($property_address_array);
    $property_address_last = implode(',',$property_address_array);
    $setting_address_array =  explode(',',$setting_details->company_address,2);    
    $property_street_array = Get_Address_From_Google_Maps($invoice_details->property_address);
 ?>
  <div class="container">
	<table width="100%" style="margin-bottom: 0px;">
	<!-- START TOP FOLD -->
      <tr id="top-fold">
        <td valign="top">
       		<address>
				<strong><?= $setting_details->company_name ?></strong><br>
					<?php
						if( isset($setting_address_array) ) {

							if( isset($setting_address_array[0]) ) {
								echo $setting_address_array[0];
							}
							if( isset($setting_address_array[1]) ) {
								echo '<br/>'.$setting_address_array[1];
							}

						}
					?>
		
				<br>
				<?php
				if(isset($setting_details->company_phone_number)) { ?>   
				<?= $setting_details->company_phone_number ?><br>
				<?php }  
				
				echo $setting_details->company_email ?><br>

				<?php if ($setting_details->web_address!='') { ?>
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

    <table width="100%" class="table table-condensed" style="margin-bottom: 0px !important;">
      <tr class="first_tr">
		<td><strong>INVOICE NO : #<?php if(isset($invoice_details->invoice_id)){ echo $invoice_details->invoice_id;}else{ echo "pending";} ?></strong> </td>
        <td align="right"> <strong> <?=  date("m/d/Y" ,strtotime( $invoice_details->invoice_date)) ?></strong></td>
      </tr>
    </table>

    <table width="100%" class="table table-condensed">

      <tr class="border-bottom default-font-color">
        <td align="left">
          BILL TO
        </td>
        <td align="left">
          PAYMENT TERMS
        </td>
        <td align="left">
          NOTES/INSTRUCTIONS
        </td>
      </tr>
      <tr>
        <td align="left">

          <?= $invoice_details->first_name.' '.$invoice_details->last_name ?><br>
			<?php

				if ($invoice_details->billing_street) {
					$customer_billing_address = explode(',',$invoice_details->billing_street);
					$customer_address_first = array_shift($customer_billing_address);
					$customer_address_last = implode(',',$customer_billing_address);

					echo $customer_address_first.'<br>';

					// support for both kinds of address input
					if ($customer_address_last) {
						echo $customer_address_last;
					} else {
						echo $invoice_details->billing_city.', '.$invoice_details->billing_state.', '.$invoice_details->billing_zipcode;
					}
				}
			?>

          <br>


        </td>
        <td align="left">
          <?php 
            switch ($setting_details->payment_terms) {
              case 1 :
               echo  "Due Upon Receipt";
               break; 
              case 2 :
               echo  "Net 7";
               break; 
              case 3 :
               echo  "Net 10";
               break; 
              case 4 :
               echo  "Net 14";
               break; 
              case 5 :
               echo  "Net 15";
               break; 
              case 6 :
               echo  "Net 20";
               break; 
              case 7 :
               echo  "Net 30";
               break; 
              case 8 :
               echo  "Net 45";
               break; 
              case 9 :
               echo  "Net 60";
               break;
              case 10 :
               echo  "Net 90";
               break; 
              default:
             
               break;
            }
         ?>

        </td>
        <td align="left">
          <?= $invoice_details->notes  ?>
        </td>

      </tr>

    </table>
<!-- END TOP FOLD -->
<!-- START PAY STUB -->
<div id="paystub-container" style="position:absolute; bottom:0; margin-bottom: 0px;">
    <div>
		<table width="100%" class="table table-condensed" style="margin: -10px 0 20px 0;" id="paystub-table">
            <tr class="first_tr">
              <td align="left" width="50%" style="padding: 5px 20px 5px 5px!important;">
                <b>CUSTOMER</b> <p style="float: right;margin:0px;"><?= $invoice_details->first_name.' '.$invoice_details->last_name ?></p>
              </td>
              <td align="left" width="50%" style="padding: 5px 5px 5px 20px!important;">
                <b>PAYMENT STUB</b>
              </td>
            </tr>
			<tr>
				<td align="left" width="50%" style="padding-right:20px;">
					<div style="height: 1px; width: 100%; margin-top: 10px;"></div>
					<b>For</b>
					<p style="float:right;text-align:right; margin:0px; display:inline-block;">
						<?php
							  if ($property_street_array && is_array($property_street_array) && !empty($property_street_array) ) {
							  if (trim($property_street_array['street'])!='') {
								echo $property_street_array['street'].'<br>';
							  }
							  echo $invoice_details->property_city.', '.$invoice_details->property_state.', '.$invoice_details->property_zip;
							}
							//echo $invoice_details->billing_street.'&nbsp;<br>';
							//echo $invoice_details->billing_city.'&nbsp;';
							//echo $invoice_details->billing_state.', '.$invoice_details->billing_zipcode.'&nbsp;';
						  ?>
					</p>
					<div style="height: 1px; width: 100%; margin-top: 20px;"></div>
				</td>
			</tr>
			<tr>
				<td align="left" width="50%" style="padding-right: 20px;">
					<b>Invoice #</b>
					<p style="float:right; margin:0px;"><?= $invoice_details->invoice_id ?></p>
					<div style="height: 1px; width: 100%; background-color: #E3E3E3; margin-top: 10px;"></div>
				</td>
			</tr>
			<tr>
				<td align="left" width="50%" style="padding-right: 20px;">
					<b>Invoice Date</b>
					<p style="float:right; margin:0px;"><?php echo  date("m/d/Y" ,strtotime( $invoice_details->invoice_date)) ?></p>
					<div style="height: 1px; width: 100%; background-color: #E3E3E3; margin-top: 10px;"></div>
				</td>
			</tr>
			<tr>
				<td align="left" width="50%" style="padding-right: 20px;">
					<b>Invoice Amount</b>
					<p style="float:right; margin:0px;">$
						<?php echo number_format($invoice_details->total_invoice_cost_calc, 2);?>
              		</p>
					<div style="height: 1px; width: 100%; background-color: #E3E3E3; margin-top: 10px;"></div>
				</td>
			</tr>
			<tr>
				<td align="left" width="50%" style="padding-right: 20px;">
					<b>Amount Enclosed</b>
					<div style="height: 1px; width: 100%; background-color: #E3E3E3; margin-top: 10px;"></div>
				</td>
				  <td align="left" width="50%" style="padding-left: 20px;">
					  <div style="position: absolute; padding-bottom: 25px; margin-top: -40px;">
						  <strong><?= $setting_details->company_name ?></strong><br>
							<?php
							  if( isset($setting_address_array) ) {
								if( isset($setting_address_array[0]) ) {
								  echo $setting_address_array[0];
								}
								if( isset($setting_address_array[1]) ) {
								  echo '<br/>'.$setting_address_array[1];
								}
							  }
							?>
					  </div>
				</td>
			</tr>
		</table>
		<table width="100%" class="table table-condensed" style="color: #B11212; margin-top: -15px;">
            <tr>
              <td align="left" width="100%">
              To pay by credit card, visit our website <?php if ($setting_details->web_address!='') { echo 'at '.$setting_details->web_address;}; ?> OR pay directly by the invoice we sent to your email address after work is completed (check your spam folder). If you would like to receive email invoices and we don't have your email address on file, contact us at <?= $setting_details->company_email ?>
              </td>
            </tr>
        </table>



  </div>
<!-- <div class="page-break-avoid" style="position: absolute; display: block; bottom: 0;"> -->
<!-- <div class="page-break-avoid" style="display: block; bottom: 0; position: relative; top: 100%; transform: translateY(-100%);"> -->

  </div>
<!-- END PAY STUB -->
<!-- START PROPERTY PROGRAM SERVICE DETAILS --> 
<br>
    <table width="100%" class="table table-condensed" cellspacing="0" id="main_table">
      <thead>
        <tr class="first_tr">
          <td class="text-left" width="30%">
            PROPERTY
          </td>  

          <td class="text-left" width="20%">
            SERVICE
          </td>
          <td class="text-left" width="30%">
            DESCRIPTION
          </td>
          <td class="text-left" width="10%">
            DATE
          </td>
		
          <td class="text-left" width="10%">
            TOTAL
          </td>
        </tr>
      </thead>

      <tbody>
		  <?php 
                $total_inv_line_costs = 0;
            	if (isset($invoice_details->jobs) && is_array($invoice_details->jobs) && !empty($invoice_details->jobs) ) {
					foreach($invoice_details->jobs as $job){
						
						?>
					<tr class="border-bottom-blank-td">
					<td class="text-left" width="30%">
					  <?php 
					          if ($property_street_array && is_array($property_street_array) && !empty($property_street_array) ) {
              if (trim($property_street_array['street'])!='') {
                echo $property_street_array['street'].'<br>';
              }
              echo $invoice_details->property_city.', '.$invoice_details->property_state.', '.$invoice_details->property_zip;
            }
					  ?>
					</td>  

						 <td class="text-left" width="20%">
							<?php echo $job['job_name']; ?>
						  </td>
						  <td class="text-left" width="20%">
							  <?php echo $job['job_description']; ?>
						  </td>
						  <td class="text-left" width="10%">
							<?php if(isset($job['job_assign_date'])){
						  echo $job['job_assign_date']; 
					  		}	?>
						  </td>

						  <td class="text-left" width="20%">
							  <?php if($job['job_cost'] != ''){
								echo "$". number_format($job['job_cost'],2);  
							   } ?>
						  </td>

					</tr>
						<?php 
                        // INSERT SERVICE COUPON IF APPLICABLE
                        $total_job_cost = 0;
                        $total_job_cost += (float) $job['job_cost'];
                        if ($job['coupon_job_amm'] != 0) {
                            ?>
                                <tr class="border-bottom-blank-td">
                                    <td class="text-left" width="30%"></td>  

                                        <?php
                                            if ($job['coupon_job_amm_calc'] == 0) {
                                                ?>
                                                    <td class="text-left" width="20%">DISCOUNT</td>
                                                    <td class="text-left" width="30%"><?= $job['coupon_job_code'] ?></td>
                                                    <td class="text-left" width="10%"></td>
                                                    <td class="text-left" width="10%">
                                                <?php
                                                $discount_amm = (float) $job['coupon_job_amm'];
                                                echo "- $" . (string) number_format($discount_amm,2);

                                                if (($total_job_cost - $discount_amm) < 0 ) {
                                                    $total_job_cost = 0;
                                                } else {
                                                    $total_job_cost -= $discount_amm;
                                                }

                                            } else if ($job['coupon_job_amm_calc'] == 1) {
                                                ?>
                                                    <td class="text-left" width="20%">DISCOUNT</td>
                                                    <td class="text-left" width="30%"><?= $job['coupon_job_code'] ?> (-<?= $job['coupon_job_amm'] ?>%)</td>
                                                    <td class="text-left" width="10%"></td>
                                                    <td class="text-left" width="10%">
                                                <?php
                                                $percentage = (float) $job['coupon_job_amm'];
                                                $discount_amm = $total_job_cost*($percentage / 100);
                                                echo "- $" . $discount_amm;

                                                if (($total_job_cost - $discount_amm) < 0 ) {
                                                    $total_job_cost = 0;
                                                } else {
                                                    $total_job_cost -= $discount_amm;
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                        }
                        $total_inv_line_costs += (float) $total_job_cost;
					}
				} else {
			?>
		<tr class="border-bottom-blank-td">
        	<td class="text-left" width="30%">
			  <?php 
		          if ($property_street_array && is_array($property_street_array) && !empty($property_street_array) ) {
              if (trim($property_street_array['street'])!='') {
                echo $property_street_array['street'].'<br>';
              }
              echo $invoice_details->property_city.', '.$invoice_details->property_state.', '.$invoice_details->property_zip;
            }
			  ?>
        	</td>  

			  <td class="text-left" width="20%">
				<?= $invoice_details->job_name ?>
			  </td>
			  <td class="text-left" width="30%">
				<?= $invoice_details->job_description ?>
			  </td>
			  <td class="text-left" width="10%">
				<? //if(isset($invoice_detail->job_assign_date)){
				  //echo $invoice_detail->job_assign_date;
			  //}   ?>
				<?php if(isset($invoice_details->job_assign_date)){
					echo date('m/d/Y', strtotime($invoice_details->job_assign_date));
				} else if(isset($invoice_details->job_completed)) {
					echo date('m/d/Y', strtotime($invoice_details->job_completed)); 
				} else if(isset($invoice_details->invoice_created)) {
					echo date('m/d/Y', strtotime($invoice_details->invoice_created)); 
				} ?>
			  </td>

			  <td class="text-left" width="10%">
				 <?php echo "$". number_format($invoice_details->cost,2);  ?>
				 <?php $total_inv_line_costs = $invoice_details->cost ?>
			  </td>
			</tr>
			<?php } ?>

        <tr>
          <td colspan="2" class="paid_logo cl_50">
            <?php 
              if($invoice_details->payment_status==2)  {
             ?>
            <img class="logo" src="<?= base_url('assets/img/paid.png') ?>">
			 
            <?php }   ?>
          </td>
          <td colspan="2" class="cl_50">
			  <br>
			  <!-- START TOTALS SECTION -->
            <table class="table table-condensed" >
              <tr>
                <td></td>
                <td></td>
                <td class="border-bottom-blank-td text-left default-font-color">SUBTOTAL</td>
                <td class="border-bottom-blank-td text-right" style="text-align:right;">$ <?= number_format($total_inv_line_costs,2); ?></td>

              </tr>

              <?php 

									// $invoice_total_cost = (float) $invoice_details->cost;
									$invoice_total_cost = (float) $total_inv_line_costs;
							
									// COUPON_INVOICE
                                    $coupon_invoice = $invoice_details->coupon_details;
									foreach ( $coupon_invoice as $coupon_details ) {
										?>
											<tr>
												<td></td>
												<td></td>
													<?php
														if ($coupon_details->coupon_amount_calculation == 0) {
															echo '<td class="border-bottom-blank-td text-left default-font-color">'.$coupon_details->coupon_code.'</td><td class="border-bottom-blank-td text-right" style="text-align:right;">';
															$discount_amm = (float) $coupon_details->coupon_amount;
															echo "- $ " . (string) number_format($discount_amm,2);

															if (($invoice_total_cost - $discount_amm) < 0 ) {
																$invoice_total_cost = 0;
															} else {
																$invoice_total_cost -= $discount_amm;
															}

														} else {
															$percentage = (float) $coupon_details->coupon_amount;
															$discount_amm = (float) $invoice_total_cost * ($percentage / 100);
															echo '<td class="border-bottom-blank-td text-left default-font-color">'.$coupon_details->coupon_code.' (-'.$percentage.'%)</td><td class="border-bottom-blank-td text-right" style="text-align:right;">';
															echo "- $ " . number_format($discount_amm,2);

															if (($invoice_total_cost - $discount_amm) < 0 ) {
																$invoice_total_cost = 0;
															} else {
																$invoice_total_cost -= $discount_amm;
															}

														}
														
													?>
												</td>
											</tr>
										<?php
									}
								
								?>
					<?php 

                          $total_tax_amount = 0;
                          

                          if ($invoice_details->all_sales_tax ) {
                              foreach ($invoice_details->all_sales_tax  as  $invoice_sales_tax_details) {
                                 $total_tax_amount +=  $invoice_sales_tax_details['tax_amount'];

                                  ?>
								  <tr>
										<td></td>
										<td></td>
										<td class="border-bottom-blank-td text-left default-font-color">
										  <?= 'Sales Tax: '.$invoice_sales_tax_details['tax_name'].' ('.floatval($invoice_sales_tax_details['tax_value']).'%) '  ?>
										</td>
										<td class="border-bottom-blank-td text-right" style="text-align:right;">$
                                        <td class="border-bottom-blank-td text-right">$
                                        <?= number_format($invoice_sales_tax_details['tax_amount'],2);  ?>
                                    </td>
										<?php $invoice_total_cost += $invoice_sales_tax_details['tax_amount']; ?>
								  </tr>
              <?php } } ?>


              <?php 
                    if ($invoice_details->all_sales_tax && $invoice_details->payment_status==2 ) {
                  ?>


              <tr>
                <td></td>
                <td></td>
                <td class="border-bottom-blank-last text-left default-font-color">PAYMENT
					<?= isset($invoice_detail->payment_created) ? ($invoice_detail->payment_created == '0000-00-00 00:00:00' ? ($invoice_detail->last_modify != '0000-00-00 00:00:00' ? date('m/d/Y', strtotime($invoice_detail->last_modify)) : "" ) : date('m/d/Y', strtotime($invoice_detail->payment_created)))  : "";  ?>
                </td>
                <td class="border-bottom-blank-last text-right" style="text-align:right;">$
                  <?= number_format($invoice_total_cost,2);  ?></td>
              </tr>

              <?php } ?>
				





              
 
       <?php  if ( $invoice_details->partial_payment > 0 && $invoice_details->payment_status != 2) { ?>

              <tr>
                <td></td>
                <td></td>
                <td class="border-bottom-blank-last text-left default-font-color">PARTIAL PAYMENT
                  	<?= isset($invoice_detail->payment_created) ? ($invoice_detail->payment_created == '0000-00-00 00:00:00' ? ($invoice_detail->last_modify != '0000-00-00 00:00:00' ? date('m/d/Y', strtotime($invoice_detail->last_modify)) : "" ) : date('m/d/Y', strtotime($invoice_detail->payment_created)))  : "";  ?>
                </td>
                <td class="border-bottom-blank-last text-right" style="text-align:right;">- $
                  <?php
                        echo number_format($invoice_details->partial_payment,2);
                        $invoice_total_cost = $invoice_total_cost-$invoice_details->partial_payment;
                  ?></td>
              </tr>


              <?php  } ?>
              

              <tr>
                <td></td>
                <td></td>
                <td class="border-bottom-blank-last text-left default-font-color"><strong>TOTAL DUE BY DATE</strong>
                </td>
                <td class="border-bottom-blank-last text-right" style="text-align:right;">$<?php
                                              if($invoice_details->payment_status == 2)  {
                                                echo number_format(0,2);
                                              } else {                        
                                                echo number_format($invoice_total_cost,2);
                                                // echo number_format($invoice_details->cost+$total_tax_amount-$invoice_details->partial_payment,2);
                                              }?></td>
              </tr>
              
            </table>
			<!-- END TOTALS SECTION -->
  </div> 
</body>
</html>