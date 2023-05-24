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
            background-color: <?=$setting_details->invoice_color;
            ?> !important;
            color: #fff;
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
            opacity: 0.2 !important;
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

        .application_tbl {
            opacity: 1 !important;
        }
        </style>
        <link href="<?= base_url('assets/admin/assets/css/bootstrap.min.pdf.css') ?>" rel="stylesheet"
            id="bootstrap-css">
            <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
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
    <table width="100%" style="margin-bottom: 20px;">
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
    <table width="100%" class="table table-condensed">
      <tr class="first_tr">
        <td><strong>INVOICE NO : #<?= $invoice_details->invoice_id ?></strong>

	</td>
    <td align="left">
        <strong>PAYMENT TERMS: <?php
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
         ?></strong>

        </td>
        <td align="right"> <strong> <?=  date("m/d/Y" ,strtotime( $invoice_details->invoice_date)) ?></strong></td>
      </tr>
    </table>
    <table width="100%" class="table table-condensed" style="margin-bottom: 20px;">
      <tr class="border-bottom default-font-color">
        <td align="left" width="40%">
          BILL TO
        </td>

        <td align="center" width="40%">
          NOTES/INSTRUCTIONS
        </td>
      </tr>
      <tr>
        <td align="left" width="40%">
          <?= $invoice_details->first_name.' '.$invoice_details->last_name ?><br>


          <?php
            if (trim($invoice_details->billing_street) != '') {
              echo $invoice_details->billing_street.'<br>';
              echo $invoice_details->billing_city.', '.$invoice_details->billing_state.', '.$invoice_details->billing_zipcode;
            }
           ?>

          <br>


        </td>
        <td align="left" width="40%">
          <?= $invoice_details->notes  ?>
        </td>
      </tr>
    </table>
<!-- END TOP FOLD -->
<!-- START PROPERTY PROGRAM SERVICE DETAILS -->
    <table width="100%" class="table table-condensed" cellspacing="0">
		  <thead>
				<tr class="first_tr">
					  <td class="text-left" width="10%">
						PROPERTY
					  </td>
					  <td class="text-left" width="15%">
						SERVICE
					  </td>
					  <td class="text-left" width="50%" align="center">
						DESCRIPTION
					  </td>
					  <td class="text-left" width="5%" align="center">
						DATE
					  </td>
					  <td class="text-left" width="20%">
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
								<td class="text-left" width="10%">
								<?php
									if ($property_street_array && is_array($property_street_array) && !empty($property_street_array) ) {
										if (trim($property_street_array['street'])!='') {
											echo $property_street_array['street'].'<br>';
										}
										echo $invoice_details->property_city.', '.$invoice_details->property_state.', '.$invoice_details->property_zip;
									}
								?>
                        </td>

                        <td class="text-left" width="15%">
                            <?php echo $job['job_name']; ?>
                        </td>
                        <td class="text-left" width="50%">
                            <?php echo $job['job_description']; ?>
                        </td>
                        <td class="text-left" width="5%">
                            <?php if(isset($job['job_assign_date']) && $job['job_assign_date'] != ''){
								echo $job['job_assign_date'];
                            } else {
                                echo 'Pending';
									}	?>
								</td>

								<td class="text-left" width="20%">
									<?php if($job['job_cost'] != ''){?>
										$ <?= number_format($job['job_cost'],2);  ?>
									<?php } ?>
								</td>

							</tr>
							<?php
							// INSERT SERVICE COUPON IF APPLICABLE
                            $total_job_cost = 0;
							$total_job_cost += (float) $job['job_cost'];
							foreach($coupon_job as $potential_job_coupon) {
								if ($potential_job_coupon->job_id == $job['job_id']) {
									?>
										<tr class="border-bottom-blank-td">
											<td class="text-left" width="10%"></td>

												<?php
													if ($potential_job_coupon->coupon_amount_calculation == 0) {
														?>
															<td class="text-left" width="15%">DISCOUNT</td>
															<td class="text-left" width="60%"><?= $potential_job_coupon->coupon_code ?></td>
															<td class="text-left" width="5%"></td>
															<td class="text-left" width="20%">
														<?php
														$discount_amm = (float) $potential_job_coupon->coupon_amount;
														echo "- $" . (string) number_format($discount_amm,2);

														if (($total_job_cost - $discount_amm) < 0 ) {
															$total_job_cost = 0;
														} else {
															$total_job_cost -= $discount_amm;
														}

													} else {
														?>
															<td class="text-left" width="15%">DISCOUNT</td>
															<td class="text-left" width="50%"><?= $potential_job_coupon->coupon_code ?> (-<?= $potential_job_coupon->coupon_amount ?>%)</td>
															<td class="text-left" width="5%"></td>
															<td class="text-left" width="20%">
														<?php
														$percentage = (float) $potential_job_coupon->coupon_amount;
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
							<?php if(isset($invoice_details->job_assign_date)){
							  	echo date('m/d/Y', strtotime($invoice_details->job_assign_date));
						  	} else if(isset($invoice_details->job_completed)) {
								echo date('m/d/Y', strtotime($invoice_details->job_completed));
							} else if(isset($invoice_details->invoice_created)) {
								echo date('m/d/Y', strtotime($invoice_details->invoice_created));
							} else echo 'Pending'; ?>
                        </td>

                        <td class="text-left" width="10%">
                            $ <?= number_format($invoice_details->cost,2);  ?>
                            <?php $total_inv_line_costs = $invoice_details->cost ?>
                        </td>
                    </tr>

                    <?php
						if (isset($invoice_details->coupon_job) && !empty($invoice_details->coupon_job)) {
							foreach ($invoice_details->coupon_job as $coupon_job_details) {
								?>
									<tr class="border-bottom-blank-td">
										<td class="text-left" width="30%"></td>
											<?php
												if ($coupon_job_details->coupon_amount_calculation == 0) {
													?>
														<td class="text-left" width="20%">DISCOUNT</td>
														<td class="text-left" width="30%"><?= $coupon_job_details->coupon_code ?></td>
														<td class="text-left" width="10%"></td>
														<td class="text-left" width="10%">
													<?php
													$discount_amm = (float) $coupon_job_details->coupon_amount;
													echo "- $" . (string) number_format($discount_amm,2);

													if (($total_inv_line_costs - $discount_amm) < 0 ) {
														$total_inv_line_costs = 0;
													} else {
														$total_inv_line_costs -= $discount_amm;
													}

												} else {
													?>
														<td class="text-left" width="20%">DISCOUNT</td>
														<td class="text-left" width="30%"><?= $coupon_job_details->coupon_code ?> (-<?= $coupon_job_details->coupon_amount ?>%)</td>
														<td class="text-left" width="10%"></td>
														<td class="text-left" width="10%">
													<?php
													$percentage = (float) $coupon_job_details->coupon_amount;
													$discount_amm = $total_inv_line_costs*($percentage / 100);
													echo "- $" . $discount_amm;

													if (($total_inv_line_costs - $discount_amm) < 0 ) {
														$total_inv_line_costs = 0;
													} else {
														$total_inv_line_costs -= $discount_amm;
													}
												}
											?>
										</td>
									</tr>
								<?php
							}
						}
                    ?>

				<?php } ?>
					<tr>
						  <td colspan="2" class="paid_logo" width="50%">
							<?php
							  if($invoice_details->payment_status==2)  {
							 ?>
							<img class="logo" src="<?php echo getcwd(); ?>/assets/customers/assets/images/paid.png">
							<?php }   ?>
						  <td colspan="3" width="50%">
							 <!-- START TOTALS SECTION-->
							  <br>
							<table class="table table-condensed">
								  <tr>
									<td></td>
									<td></td>
									<td></td>
									<td class="border-bottom-blank-td text-left default-font-color">SUBTOTAL</td>
									<td class="border-bottom-blank-td text-right" style="text-align:right;">$ <?= number_format($total_inv_line_costs,2) ?></td>
								  </tr>

								<?php

									// $invoice_total_cost = (float) $invoice_details->cost;
									$invoice_total_cost = (float) $total_inv_line_costs;

									// COUPON_CUSTOMER
									foreach ( $coupon_customer as $coupon_details ) {
										?>
											<tr>
												<td></td>
												<td></td>
												<td></td>
													<?php
														if ($coupon_details->amount_calculation == 0) {
															echo '<td class="border-bottom-blank-td text-left default-font-color">'.$coupon_details->code.'</td><td class="border-bottom-blank-td text-right" style="text-align:right;">';
															$discount_amm = (float) $coupon_details->amount;
															echo "- $ " . (string) number_format($discount_amm,2);

															if (($invoice_total_cost - $discount_amm) < 0 ) {
																$invoice_total_cost = 0;
															} else {
																$invoice_total_cost -= $discount_amm;
															}

														} else {
															$percentage = (float) $coupon_details->amount;
															$discount_amm = (float) $invoice_total_cost * ($percentage / 100);
															echo '<td class="border-bottom-blank-td text-left default-font-color">'.$coupon_details->code.' (-'.$percentage.'%)</td><td class="border-bottom-blank-td text-right" style="text-align:right;">';
															echo "- $ " . number_format($discount_amm, 2);

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

									// COUPON_INVOICE
									foreach ( $coupon_invoice as $coupon_details ) {
										?>
											<tr>
												<td></td>
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
															echo "- $ " . number_format($discount_amm, 2);

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

						if ($all_sales_tax) {
							foreach ($all_sales_tax as  $invoice_sales_tax_details) {
									 $total_tax_amount +=  ($invoice_total_cost *($invoice_sales_tax_details['tax_value']/100));
						?>
								  <tr>
									  	<td></td>
										<td></td>
										<td></td>
										<td class="border-bottom-blank-td text-left default-font-color">
										  <?= 'Sales Tax: '.$invoice_sales_tax_details['tax_name'].' ('.floatval($invoice_sales_tax_details['tax_value']).'%) '  ?>
										</td>
										<td class="border-bottom-blank-td text-right" style="text-align:right;">$
											<?= number_format(($invoice_total_cost *($invoice_sales_tax_details['tax_value']/100)),2);  ?></td>
										<?php $invoice_total_cost += ($invoice_total_cost *($invoice_sales_tax_details['tax_value']/100)); ?>
								  </tr>

					<?php } } ?>
					<?php if ($all_sales_tax && $invoice_details->payment_status==2 ) { ?>
								  <tr>
									  	<td></td>
										<td></td>
										<td></td>
										<td class="border-bottom-blank-last text-left default-font-color">PAYMENT
                                        <?= isset($invoice_detail->payment_created) ? ($invoice_detail->payment_created == '0000-00-00 00:00:00' ? ($invoice_detail->last_modify != '0000-00-00 00:00:00' ? date('m/d/Y', strtotime($invoice_detail->last_modify)) : "" ) : date('m/d/Y', strtotime($invoice_detail->payment_created)))  : "";  ?>
										</td>
										<td class="border-bottom-blank-last text-right" style="text-align:right;">$
										  <?= number_format($invoice_total_cost,2);  ?></td>
								  </tr>
					<?php } ?>

					<?php if($invoice_details->partial_payment > 0 && $invoice_details->payment_status != 2) { ?>
								  <tr>
										<td></td>
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

					<?php if($invoice_details->refund_amount_total > 0 ) { ?>
								  <tr>
										<td></td>
									  	<td></td>
										<td></td>
										<td class="border-bottom-blank-last text-left default-font-color">REFUND ISSUED
										</td>
										<td class="border-bottom-blank-last text-right" style="text-align:right;">+ $
										  <?php
											echo number_format($invoice_details->refund_amount_total,2);
											// $invoice_total_cost = $invoice_total_cost-$invoice_details->refund_amount_total;
										  ?>
										</td>
								  </tr>
					<?php  } ?>
					<?php if($invoice_details->refund_amount_total > 0 ) { ?>
								  <tr>
										<td></td>
									  	<td></td>
										<td></td>
										<td class="border-bottom-blank-last text-left default-font-color">REFUND PAID
										</td>
										<td class="border-bottom-blank-last text-right" style="text-align:right;">- $
										  <?php
											echo number_format($invoice_details->refund_amount_total,2);
											// $invoice_total_cost = $invoice_total_cost-$invoice_details->refund_amount_total;
										  ?>
										</td>
								  </tr>
					<?php  } ?>
					<?php if($invoice_late_fee > 0 ) { ?>
								  <tr>
										<td></td>
									  	<td></td>
										<td></td>
										<td class="border-bottom-blank-last text-left default-font-color">Late Fee
										</td>
										<td class="border-bottom-blank-last text-right" style="text-align:right;"> $
										  <?php
											echo number_format($invoice_late_fee,2);
											$invoice_total_cost = $invoice_total_cost + $invoice_late_fee;
										  ?>
										</td>
								  </tr>
					<?php  } ?>
								  <tr>
									    <td></td>
										<td></td>
										<td class="border-bottom-blank-last text-left default-font-color"><strong>TOTAL DUE BY DATE</strong>
										</td>
										<td class="border-bottom-blank-last"></td>
										<td class="border-bottom-blank-last text-right" style="text-align:right;">$
										  <?php
											  if($invoice_details->payment_status == 2)  {
												echo number_format(0,2);
											  } else {
												echo number_format($invoice_total_cost,2);
												// echo number_format($invoice_details->cost+$total_tax_amount-$invoice_details->partial_payment,2);
											  } ?>
										</td>
								  </tr>
                                  <?php if ($cardconnect_details && $invoice_details->payment_status != 2) { ?>
                    <tr class="button-tr">
                        <td></td>
                        <td></td>
						<td></td>
                        <td class="text-left" colspan="4"><button class="btn btn-success"><a
                                    href="<?= base_url('Welcome/cardConnectPayment/').base64_encode($invoice_details->invoice_id)  ?>"
                                    target="_blank">Pay Now</a></button></td>
                        <td class="text-right"></td>
                    </tr>
                    <?php if(!isset($group_billing_info)){ ?>
                        <tr>
						    <td></td>
                            <td></td>
						    <td></td>

                            <?php if(!empty($setting_details->slug)){?>

                            <td class="text-left" colspan="4"><p class="button_tr"><a href="<?=base_url("/welcome/").$setting_details->slug; ?>" target="_blank"><strong><?php echo 'Access Your Account' ?></strong></a></p></td>

                            <?php } ?>

                            <td class="text-right"></td>
					    </tr>
                    <?php } ?>
                    <?php } else if ($basys_details && $invoice_details->payment_status!=2) { ?>
                        <tr class="button-tr">
                        <td></td>
                        <td></td>
						<td></td>
                        <td class="text-left" colspan="4"><button class="btn btn-success"><a
                                    href="<?= base_url('Welcome/payment/').base64_encode($invoice_details->invoice_id)  ?>"
                                    target="_blank">Pay Now</a></button></td>
                        <td class="text-right"></td>

                        </tr>
                    <?php if(!isset($group_billing_info)){ ?>
                        <tr>
						<td></td>
                        <td></td>
						<td></td>

                        <?php if(!empty($setting_details->slug)){?>

                        <td class="text-left" colspan="4"><p class="button_tr"><a href="<?=base_url("/welcome/").$setting_details->slug; ?>" target="_blank"><strong><?php echo 'Access Your Account' ?></strong></a></p></td>

                        <?php } ?>

                        <td class="text-right"></td>
					</tr>
                    <?php } ?>

                    <?php } elseif ($setting_details->pay_now_btn==1 && $setting_details->pay_now_btn_link!='' && $invoice_details->payment_status!=2) { ?>
                        <tr class="button-tr">
										<td></td>
										<td></td>
										<td class="text-left" colspan="4"><button class="btn btn-success"><a
											  href="<?= $setting_details->pay_now_btn_link  ?>" target="_blank">Pay Now</a></button></td>
										<td class="text-right"></td>
								  </tr>
                    <?php if(!$group_billing_info){ ?>
                        <tr>
						<td></td>
                        <td></td>
						<td></td>

                        <?php if(!empty($setting_details->slug)){?>

                        <td class="text-left" colspan="4"><p class="button_tr"><a href="<?=base_url("/welcome/").$setting_details->slug; ?>" target="_blank"><strong><?php echo 'Access Your Account' ?></strong></a></p></td>

                        <?php } ?>

                        <td class="text-right"></td>
					</tr>
                    <?php } } ?>
                    <tr class="default-msg">
										<td></td>
										<td></td>
										<td class="text-left"><?= $setting_details->default_invoice_message ?></td>
										<td class="text-right"></td>
								  </tr>
							</table>
						<!-- END TOTALS SECTION -->
						  </td>
					</tr>
		  </tbody>
    </table>
	<!-- END PROPERTY PROGRAM SERVICES SECTION -->

	<!-- START APPLICATION & PRODUCTS SECTION -->
    <table width="100%" class="main table table-condensed" cellspacing="0">

        <?php
        $products = array();
        $report_id = [];

        if (isset($invoice_details) && is_array($invoice_details) && !empty($invoice_details) ) {
            foreach($invoice_details as $job){

                $job_report_id = $job['job_report']->report_id;
                if ($job_report_id != '') {
                    array_push($report_id, $job->report_id);
                    $products[]= array(
                        'job_id'=>$job['job_id'],
                        'job_name'=>$job['job_name'],
                        'report'=>$job['job_report'],
                    );
                }
            }
        } else {
            //die(print_r($invoice_details->jobs));
            $job = $invoice_details->jobs[0];
            if ($invoice_details->report_id != 0) {
                array_push($report_id, $invoice_details->report_id);
                $products[]= array(
                    'job_id'=>$invoice_details->job_id,
                    'report'=>isset($invoice_details->jobs[0]['job_report']) ? $invoice_details->jobs[0]['job_report'] : '',
                );
            } else {
                $job = $invoice_details->jobs[0];
                $invoice_details->report_id = $job['job_report']->report_id;
                array_push($report_id, $job['job_report']->report_id);
                $products[] = array(
                    'job_id' => $job['job_id'],
                    'report' => isset($job['job_report']) ? $job['job_report'] : '',
                );
            }
        }
        // Check if the invoice has a report, if it does, print last section.
        if ($invoice_details->report_id != 0 ){

            //echo print_r($products);
            foreach($products as $k=>$v){
                //die(print_r($v));
                $i=0;

                $product_details =  getProductByReport(array('report_id'=>$v['report']->report_id));
                //die($this->db->last_query());

                $invoice_report_details =  $v['report'];

                    if ( $invoice_report_details && ($setting_details->is_wind_speed || $setting_details->is_wind_direction || $setting_details->is_temperature || $setting_details->is_applicator_name || $setting_details->is_applicator_number || $setting_details->is_applicator_phone || $setting_details->is_property_address || $setting_details->is_property_size || $setting_details->is_date || $setting_details->is_time )) {

                        if ($setting_details->is_wind_speed==1 || $setting_details->is_wind_direction==1 || ($setting_details->is_temperature==1) || ($setting_details->is_applicator_name==1) || ($setting_details->is_applicator_number==1 && $invoice_report_details->applicator_number!='' ) ||  ($setting_details->is_applicator_phone==1 && $invoice_report_details->applicator_phone_number!='' ) || ($setting_details->is_property_address==1) || ($setting_details->is_property_size==1) || ($setting_details->is_date==1) || ($setting_details->is_time==1)    ) { ?>
                            <!-- START APPLICATIONS SECTION -->
                            <tr>
                                <td width="100%">
                                    <table width="100%" class="table table-condensed mannual application_tbl" style="margin-bottom: 20px; margin-top:20px;">
                                        <tr>
                                          <td>
                                             <!-- START SINGLE APPLICATION PART -->
                                            <table width="100%" class="table table-condensed inside-tabel mannual application_tbl" style="font-size:10px;" cellspacing="0">
                                                  <thead>
                                                    <tr class="first_tr" style="text-transform:uppercase;">
                                                        <td colspan="4" valign="middle">&nbsp;&nbsp;APPLICATION & PRODUCT DETAILS</td>
                                                        <td colspan="3" align="right" valign="middle"><?php if(isset($v['job_name'])) echo $v['job_name']; ?>&nbsp;&nbsp;</td>
                                                    </tr>
                                                    <tr class="border-bottom default-font-color">
                                                      <td class="default-font-color">DATE & TIME</td>
                                                      <td class="default-font-color" align="center">PROPERTY ADDRESS</td>
                                                      <td class="default-font-color" align="center">PROPERTY SIZE</td>
                                                      <td class="default-font-color" align="center">APPLICATOR'S NAME</td>
                                                      <td class="default-font-color" align="center">APPLICATOR'S NUMBER</td>
                                                      <td class="default-font-color" align="center">WIND SPEED</td>
                                                      <td class="default-font-color" align="center">TEMP</td>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                        <tr>
                                                            <td align="left">
                                                                <?php if ($setting_details->is_date==1) { ?>
                                                                <?=print_r($invoice_report_details->job_completed_date)?>
                                                                <?=date("m/d/Y" ,strtotime( $invoice_report_details->job_completed_date))?>
                                                                <?php } ?>
                                                                <br>
                                                                <?php if ($setting_details->is_time==1) { ?>
                                                                <?= date("g:i A" ,strtotime( $invoice_report_details->job_completed_time)) ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td align="left">
                                                                <?php if ($setting_details->is_property_address==1) { ?>
                                                                    <?= $property_address_first.', '.$invoice_details->property_city.', '.$invoice_details->property_state.', '.$invoice_details->property_zip  ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td align="center">
                                                                <?php if ($setting_details->is_property_size==1) { ?>
                                                                    <?= $invoice_details->yard_square_feet  ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td align="center">
                                                                <?php if ($setting_details->is_applicator_name==1) { ?>
                                                                <?= $invoice_report_details->user_first_name.' '.$invoice_report_details->user_last_name ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td align="center">
                                                                <?php if ($setting_details->is_applicator_number==1) { ?>
                                                                <?= $invoice_report_details->applicator_number ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td align="center">
                                                            <?php if ($setting_details->is_wind_speed==1) {	?>
                                                                <?= $invoice_report_details->wind_speed ?>
                                                                <?php if ($setting_details->is_wind_direction==1) { ?>
                                                                <?= $invoice_report_details->direction ?>
                                                             <?php } } ?>
                                                            </td>
                                                            <td align="center">
                                                             <?php if ($setting_details->is_temperature==1) { ?>
                                                                <?= $invoice_report_details->temp ?>
                                                              <?php } ?>
                                                            </td>
                                                        </tr>
                                                  </tbody>
                                             </table>
                                          </td>
                                        </tr>

                                        <!-- START PRODUCT DETAILS SECTION -->

                                        <?php  if($product_details) {
                                        if ($setting_details->is_product_name || $setting_details->is_epa || $setting_details->is_active_ingredients || $setting_details->is_application_rate || $setting_details->is_estimated_chemical_used || $setting_details->is_chemical_type || $setting_details->is_re_entry_time || $setting_details->is_weed_pest_prevented || $setting_details->is_application_type   ) { ?>

                                        <tr>

                                            <td>
                                             <!-- START SINGLE APPLICATION PART -->
                                            <table width="100%" class="table table-condensed inside-tabel mannual application_tbl" style="font-size:10px;" cellspacing="0">
                                                  <thead>
                                                    <tr class="default-font-color">
                                                      <td></td>
                                                      <td class="border-bottom-blank-td default-font-color" align="center">PRODUCT NAME</td>
                                                      <td class="border-bottom-blank-td default-font-color" align="center" width="30">EPA #</td>
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

                                        <?php foreach($product_details as $key => $product_details_value) {

                                        $ingredientDatails = getActiveIngredient(array('product_id'=>$product_details_value->product_id));
                                        $ingredientarr = array();
                                        if ($ingredientDatails) {
                                            foreach ($ingredientDatails as $key2 => $value2) {
                                                $ingredientarr[] =  $value2->active_ingredient.' : '.$value2->percent_active_ingredient.' % ';
                                            }
                                        }


//                                        $estimated_chemical_used =estimateOfPesticideUsed($product_details_value,$invoice_details->yard_square_feet);
                                        $estimated_chemical_used = $product_details_value->estimate_of_pesticide_used;


                                        if  ($setting_details->is_product_name==1 || ($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber )  || ($setting_details->is_active_ingredients==1 && $ingredientDatails ) || ($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0 ) ||  ($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!='') || ($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0 ) ||  ($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='' ) || ($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!='') ||  ($setting_details->is_application_type==1 && $product_details_value->application_type!=0 )   ) {?>
                                                        <tr>
                                                            <td></td>
                                                            <td class="border-bottom-blank-td" align="center">
                                                                <?php if ($setting_details->is_product_name==1) { ?>
                                                                <?= $product_details_value->product_name?>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="border-bottom-blank-td" align="center">
                                                                <?php if ($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber ) { ?>
                                                                <?= $product_details_value->epa_reg_nunber ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="border-bottom-blank-td" align="center">
                                                                <?php if ($setting_details->is_active_ingredients==1 && $ingredientDatails ){ ?>
                                                                    <?= implode(', ',$ingredientarr)  ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="border-bottom-blank-td" align="center">
                                                                <?php if ($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0 )  {
                                                                $application_rate = '';
                                                                     if (!empty($product_details_value->application_rate) && $product_details_value->application_rate !=0) {
                                                                         $application_rate = $product_details_value->application_rate.' '.$product_details_value->application_unit.' / '.$product_details_value->application_per;
                                                                    }
                                                                    ?>
                                                                <?= $application_rate ?>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="border-bottom-blank-td" align="center">
                                                             <?php if ($setting_details->is_application_type==1 && $product_details_value->application_type!=0 ) {
                                                                $application_type ='';
                                                                if ($product_details_value->application_type==1) {
                                                                    $application_type = 'Broadcast';
                                                                } else if($product_details_value->application_type==2) {
                                                                    $application_type = 'Spot Spray';
                                                                } elseif ($product_details_value->application_type==3) {
                                                                    $application_type = 'Granular';
                                                                }
                                                                ?>
                                                                <?= $application_type ?>
                                                              <?php } ?>
                                                            </td>
                                                            <td class="border-bottom-blank-td" align="center">
                                                             <?php if ($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0 ) {
                                                                $chemical_type = '';
                                                                  if($product_details_value->chemical_type==1) {
                                                                      $chemical_type = 'Herbicide';
                                                                  }
                                                                   else if($product_details_value->chemical_type==2) {
                                                                      $chemical_type = 'Fungicide';
                                                                  }
                                                                   else if($product_details_value->chemical_type==3) {
                                                                      $chemical_type = 'Insecticide';
                                                                  }
                                                                   else if($product_details_value->chemical_type==4) {
                                                                      $chemical_type = 'Fertilizer';
                                                                  }
                                                                   else if($product_details_value->chemical_type==5) {
                                                                      $chemical_type = 'Wetting Agent';
                                                                  }
                                                                   else if($product_details_value->chemical_type==6) {
                                                                      $chemical_type = 'Surfactant/Tank Additive';
                                                                  }
                                                                   else if($product_details_value->chemical_type==7) {
                                                                      $chemical_type = 'Aquatics';
                                                                  }
                                                                   else if($product_details_value->chemical_type==8) {
                                                                      $chemical_type = 'Growth Regulator';
                                                                  }  else if($product_details_value->chemical_type==9) {
                                                                      $chemical_type = 'Biostimulants';
                                                                  }
                                                                ?>
                                                                <?= $chemical_type ?>
                                                              <?php } ?>
                                                            </td>
                                                              <td class="border-bottom-blank-td" align="center">
                                                                    <?php if ($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='' ) {	?>
                                                                    <?= $product_details_value->re_entry_time ?>
                                                                    <?php } ?>
                                                                </td>
                                                            <td class="border-bottom-blank-td" align="center">
                                                            <?php if ($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!='' ) {	?>
                                                                <?= $estimated_chemical_used ?>
                                                             <?php } ?>
                                                            </td>
                                                            <td class="border-bottom-blank-td" align="center">
                                                            <?php if ($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!='') {	?>
                                                                <?= $product_details_value->weed_pest_prevented  ?>
                                                             <?php } ?>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                      <?php  } } ?>
                                                  </tbody>
                                             </table>
                                          </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <br>
             <?php  $i++; } } } }  else {
            if($product_details) { ?>
                     <tr>
                    <td width="100%">
                        <table width="100%" class="table table-condensed mannual application_tbl" style="margin-bottom: 20px; margin-top:20px;">
                            <tr>
                              <td>
                                 <!-- START SINGLE APPLICATION PART -->
                                <table width="100%" class="table table-condensed inside-tabel mannual application_tbl" style="font-size:12px;" cellspacing="0">
                                      <thead>
                                        <tr class="first_tr" style="text-transform:uppercase;">
                                            <td colspan="4" valign="middle">&nbsp;&nbsp;APPLICATION & PRODUCT DETAILS</td>
                                            <td colspan="4" align="right" valign="middle"><?php if(isset($v['job_name'])) echo $v['job_name'];  ?>&nbsp;&nbsp;</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <!-- START PRODUCT DETAILS SECTION -->

                            <?php
                            if ($setting_details->is_product_name || $setting_details->is_epa || $setting_details->is_active_ingredients || $setting_details->is_application_rate || $setting_details->is_estimated_chemical_used || $setting_details->is_chemical_type || $setting_details->is_re_entry_time || $setting_details->is_weed_pest_prevented || $setting_details->is_application_type   ) { ?>




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
                            <?php foreach($product_details as $key => $product_details_value) {

                            $ingredientDatails = getActiveIngredient(array('product_id'=>$product_details_value->product_id));
                            $ingredientarr = array();
                            if ($ingredientDatails) {
                                foreach ($ingredientDatails as $key2 => $value2) {
                                    $ingredientarr[] =  $value2->active_ingredient.' : '.$value2->percent_active_ingredient.' % ';
                                }
                            }


//                                    $estimated_chemical_used = $product_details_value->amount_of_mixture_applied;
                                    $estimated_chemical_used = $product_details_value->estimate_of_pesticide_used;


                            if  ($setting_details->is_product_name==1 || ($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber )  || ($setting_details->is_active_ingredients==1 && $ingredientDatails ) || ($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0 ) ||  ($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!='') || ($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0 ) ||  ($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='' ) || ($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!='') ||  ($setting_details->is_application_type==1 && $product_details_value->application_type!=0 )   ) {


                            ?>

                                            <tr>

                                                <td class="border-bottom-blank-td" align="center">
                                                    <?php if ($setting_details->is_product_name==1) { ?>
                                                    <?= $product_details_value->product_name?>
                                                    <?php } ?>
                                                </td>
                                                <td class="border-bottom-blank-td" align="center">
                                                    <?php if ($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber ) { ?>
                                                    <?= $product_details_value->epa_reg_nunber ?>
                                                    <?php } ?>
                                                </td>
                                                <td class="border-bottom-blank-td" align="center">
                                                    <?php if ($setting_details->is_active_ingredients==1 && $ingredientDatails ){ ?>
                                                        <?= implode(', ',$ingredientarr)  ?>
                                                    <?php } ?>
                                                </td>
                                                <td class="border-bottom-blank-td" align="center">
                                                    <?php if ($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0 )  {
                                                    $application_rate = '';
                                                         if (!empty($product_details_value->application_rate) && $product_details_value->application_rate !=0) {
                                                             $application_rate = $product_details_value->application_rate.' '.$product_details_value->application_unit.' / '.$product_details_value->application_per;
                                                        }
                                                        ?>
                                                    <?= $application_rate ?>
                                                    <?php } ?>
                                                </td>
                                                <td class="border-bottom-blank-td" align="center">
                                                 <?php if ($setting_details->is_application_type==1 && $product_details_value->application_type!=0 ) {
                                                    $application_type ='';
                                                    if ($product_details_value->application_type==1) {
                                                        $application_type = 'Broadcast';
                                                    } else if($product_details_value->application_type==2) {
                                                        $application_type = 'Spot Spray';
                                                    } elseif ($product_details_value->application_type==3) {
                                                        $application_type = 'Granular';
                                                    }
                                                    ?>
                                                    <?= $application_type ?>
                                                  <?php } ?>
                                                </td>
                                                <td class="border-bottom-blank-td" align="center">
                                                 <?php if ($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0 ) {
                                                    $chemical_type = '';
                                                      if($product_details_value->chemical_type==1) {
                                                          $chemical_type = 'Herbicide';
                                                      }
                                                       else if($product_details_value->chemical_type==2) {
                                                          $chemical_type = 'Fungicide';
                                                      }
                                                       else if($product_details_value->chemical_type==3) {
                                                          $chemical_type = 'Insecticide';
                                                      }
                                                       else if($product_details_value->chemical_type==4) {
                                                          $chemical_type = 'Fertilizer';
                                                      }
                                                       else if($product_details_value->chemical_type==5) {
                                                          $chemical_type = 'Wetting Agent';
                                                      }
                                                       else if($product_details_value->chemical_type==6) {
                                                          $chemical_type = 'Surfactant/Tank Additive';
                                                      }
                                                       else if($product_details_value->chemical_type==7) {
                                                          $chemical_type = 'Aquatics';
                                                      }
                                                       else if($product_details_value->chemical_type==8) {
                                                          $chemical_type = 'Growth Regulator';
                                                      }  else if($product_details_value->chemical_type==9) {
                                                          $chemical_type = 'Biostimulants';
                                                      }
                                                    ?>
                                                    <?= $chemical_type ?>
                                                  <?php } ?>
                                                </td>
                                                  <td class="border-bottom-blank-td" align="center">
                                                        <?php if ($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='' ) {	?>
                                                        <?= $product_details_value->re_entry_time ?>
                                                        <?php } ?>
                                                    </td>
                                                <td class="border-bottom-blank-td" align="center">
                                                <?php if ($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!='' ) {	?>
                                                    <?=  $estimated_chemical_used ?>
                                                 <?php } ?>
                                                </td>
                                                <td class="border-bottom-blank-td" align="center">
                                                <?php if ($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!='') {	?>
                                                    <?= $product_details_value->weed_pest_prevented  ?>
                                                 <?php } ?>
                                                </td>

                                            </tr>



                                        <?php } }?>
                                    </tbody>
                                  </table>
                                </td>
                            </tr>
                        </table>
                      </td>
                    </tr>
                    <br>
            <?php $i++; } } } } }?>

		<!-- END PRODUCT DETAILS PART -->


</table>
<!-- END APPLICATION PRODTUCTS SECTION -->

  </div>
</body>
</html>
