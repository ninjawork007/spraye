<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>SPRAYE</title>
  <link href="<?=base_url('assets/admin/assets/css/bootstrap.min.pdf.css')?>" rel="stylesheet" id="bootstrap-css">
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
  <style type="text/css">
	* {
    font-family: Helvetica,Verdana, Arial, sans-serif;
  }
  table {
    font-size: 13px;
	 
  }
font-family: Helvetica,Verdana, Arial, sans-serif;
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
    width: 200px;
    height: auto;
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
  address {
    margin-bottom: 0 !important;
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
    line-height: 1.3 !important;
  }
  .application_tbl {
    background: #e5e2e3 !important;
  }
  .cl_50 {
    width: 50%;
  }
  .page_break {
    page-break-before: always;
  }
  </style>
</head>

<body>
  <?php
$setting_address_array = Get_Address_From_Google_Maps($setting_details->company_address);
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
    <br>
    <table width="100%" style="margin-bottom: 20px;">
      <tr>
        <td valign="top">
          <address>
            <strong><?= $setting_details->company_name ?></strong><br>
            <?php 
              if ($setting_address_array && is_array($setting_address_array) && !empty($setting_address_array) ) {
                if (trim($setting_address_array['street'])!='') {
                  echo $setting_address_array['street'].'<br>';
                } else {
                  echo explode(',',$setting_details->company_address)[0].'<br>';
                }
                echo $setting_address_array['city'].', '.$setting_address_array['state'].', '.$setting_address_array['postal_code'];
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
          <img class="logo" src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$setting_details->company_logo ?>">
        </td>
      </tr>



    </table>

    <table width="100%" class="table table-condensed">
      <tr class="first_tr">
        <td><strong>INVOICE NO : #<?= $invoice_detail->invoice_id ?></strong> </td>
        <td align="right"> <strong> <?=  date("m/d/Y" ,strtotime( $invoice_detail->invoice_date)) ?></strong></td>
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
          <?= $invoice_detail->first_name.' '.$invoice_detail->last_name ?><br>


          <?php 
            if ($billing_street_array && is_array($billing_street_array) && !empty($billing_street_array) ) {

                if (trim($billing_street_array['street'])!='') {
                    echo $billing_street_array['street'].'<br>';
                }


              echo $invoice_detail->billing_city.', '.$invoice_detail->billing_state.', '.$invoice_detail->billing_zipcode;

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
          <?= $invoice_detail->notes  ?>
        </td>

      </tr>

    </table>


    <table width="100%" class="table table-condensed" cellspacing="0">
      <thead>
        <tr class="first_tr">
          <td class="text-left">
            PROPERTY ADDRESS
          </td>  
          <td class="text-left">
            SERVICE NAME
          </td>
          <td class="text-left" width="40%">
            SERVICE DESCRIPTION
          </td>
          <td class="text-right">
            DATE OF SERVICE
          </td>

          <td class="text-right" width="15%">
            TOTAL
          </td>

        </tr>
      </thead>
      <tbody>
        <tr class="border-bottom-blank-td">
        <td class="text-left">
          <?php 
            if ($property_street_array && is_array($property_street_array) && !empty($property_street_array) ) {
              if (trim($property_street_array['street'])!='') {
                echo $property_street_array['street'].'<br>';
              }
              echo $invoice_detail->property_city.', '.$invoice_detail->property_state.', '.$invoice_detail->property_zip;
            }
          ?>
        </td>  
          <td class="text-left">
            <?= $invoice_detail->job_name ?>
          </td>
          <td class="text-left">
            <?= $invoice_detail->job_description ?>
          </td>
          <td class="text-right">
            <?= $invoice_detail->report_id!=0 ? date("m/d/Y" ,strtotime( $invoice_detail->invoice_created)) : '' ?>
          </td>

          <td class="text-right">
            $ <?= number_format($invoice_detail->cost,2);  ?>
          </td>

        </tr>

         

        <tr>
          <td colspan="2" class="paid_logo cl_50">
            <?php 
              if($invoice_detail->status==2)  {
             ?>
            <img class="logo" src="<?= base_url('assets/img/paid.png') ?>">
            <?php }   ?>
          </td>
          <td colspan="2" class="cl_50">
            <table class="table table-condensed">
              <tr>
                <td></td>
                <td></td>
                <td class="border-bottom-blank-td text-left default-font-color">SUBTOTAL</td>
                <td class="border-bottom-blank-td text-right">$ <?= number_format($invoice_detail->cost,2); ?></td>

              </tr>

              <?php 

                          $total_tax_amount = 0;
                          

                          if ($invoice_detail->all_sales_tax) {
                              foreach ($invoice_detail->all_sales_tax as  $invoice_sales_tax_details) {
                                 $total_tax_amount +=  $invoice_sales_tax_details['tax_amount'];

                                  ?>
              <tr>
                <td></td>
                <td></td>
                <td class="border-bottom-blank-td text-left default-font-color">
                  <?= 'Sales Tax: '.$invoice_sales_tax_details['tax_name'].' ('.floatval($invoice_sales_tax_details['tax_value']).'%) '  ?>
                </td>
                <td class="border-bottom-blank-td text-right">$
                  <?= number_format($invoice_sales_tax_details['tax_amount'],2);  ?></td>

              </tr>

              <?php } } ?>


              <?php 
                    if ($invoice_detail->all_sales_tax || $invoice_detail->status==2 ) {
                  ?>


              <tr>
                <td></td>
                <td></td>
                <td class="border-bottom-blank-last text-left default-font-color">PAYMENT
                <?= isset($invoice_detail->payment_created) ? ($invoice_detail->payment_created == '0000-00-00 00:00:00' ? ($invoice_detail->last_modify != '0000-00-00 00:00:00' ? date('m/d/Y', strtotime($invoice_detail->last_modify)) : "" ) : date('m/d/Y', strtotime($invoice_detail->payment_created)))  : "";  ?>
                </td>
                <td class="border-bottom-blank-last text-right">$
                  <?= number_format($invoice_detail->cost+$total_tax_amount,2);  ?></td>
              </tr>

              <?php } ?>





              <?php  if ($invoice_detail->status==3) { ?>

              <tr>
                <td></td>
                <td></td>
                <td class="border-bottom-blank-last text-left default-font-color">PARTIAL PAYMENT
                <?= isset($invoice_detail->payment_created) ? ($invoice_detail->payment_created == '0000-00-00 00:00:00' ? ($invoice_detail->last_modify != '0000-00-00 00:00:00' ? date('m/d/Y', strtotime($invoice_detail->last_modify)) : "" ) : date('m/d/Y', strtotime($invoice_detail->payment_created)))  : "";  ?>
                </td>
                <td class="border-bottom-blank-last text-right">$
                  <?= number_format($invoice_detail->partial_payment,2); ?></td>

              </tr>


              <?php  } ?>

              <tr>
                <td></td>
                <td></td>
                <td class="border-bottom-blank-last text-left default-font-color"><strong>TOTAL DUE BY DATE</strong>
                </td>
                <td class="border-bottom-blank-last text-right">$
                  <?php 
                            switch ($invoice_detail->status) {
                              case 0:
                                  echo number_format($invoice_detail->cost+$total_tax_amount,2);
                                  break;
                              case 1:
                                  echo number_format($invoice_detail->cost+$total_tax_amount,2);
                                  break;
                              case 2:
                                  echo number_format(0,2);
                                  break;
                              case 3:
                                  echo number_format($invoice_detail->cost+$total_tax_amount-$invoice_detail->partial_payment,2);
                              break;
                            }

                           ?>

                </td>

              </tr>


              <?php if ($cardconnect_details && $invoice_detail->payment_status != 2) { ?>
                    <tr class="button-tr">
                        <td></td>
                        <td></td>
                        <td class="text-left" colspan="4"><button class="btn btn-success"><a
                                    href="<?= base_url('Welcome/cardConnectPayment/').base64_encode($invoice_detail->invoice_id)  ?>"
                                    target="_blank">Pay Now</a></button></td>
                        <td class="text-right"></td>

                    </tr>

                    <?php } else if ($basys_details && $invoice_detail->payment_status!=2) { ?>

                    <tr class="button-tr">
                        <td></td>
                        <td></td>
                        <td class="text-left" colspan="4"><button class="btn btn-success"><a
                                    href="<?= base_url('Welcome/payment/').base64_encode($invoice_detail->invoice_id)  ?>"
                                    target="_blank">Pay Now</a></button></td>
                        <td class="text-right"></td>

                    </tr>



              <?php } elseif ($setting_details->pay_now_btn==1 && $setting_details->pay_now_btn_link!='' && $invoice_detail->status!=2) { ?>
              <tr class="button-tr">
                <td></td>
                <td></td>
                <td class="text-left" colspan="4"><button class="btn btn-success"><a
                      href="<?= $setting_details->pay_now_btn_link  ?>" target="_blank">Pay Now</a></button></td>
                <td class="text-right"></td>

              </tr>
              <?php } ?>


              <tr class="default-msg">
                <td></td>
                <td></td>
                <td class="text-left"><?= $setting_details->default_invoice_message ?></td>
                <td class="text-right"></td>

              </tr>
            </table>

          </td>
        </tr>





      </tbody>

    </table>

    <table width="100%" class="main table table-condensed">
      <tr>

        <?php
$i=0;

  $product_details =  getProductByJob(array('job_id'=>$invoice_detail->job_id));

// if (!empty($invoice_job_ids)) {
//      $product_details =  getProductByJobIds($invoice_job_ids);
//   } else {
//      $product_details =  false;

//   }


      $invoice_report_details =   $invoice_detail->report_details;

?>

        <?php   if ( $invoice_report_details && ($setting_details->is_wind_speed || $setting_details->is_wind_direction || $setting_details->is_temperature || $setting_details->is_applicator_name || $setting_details->is_applicator_number || $setting_details->is_applicator_phone || $setting_details->is_property_address || $setting_details->is_property_size || $setting_details->is_date || $setting_details->is_time )) { ?>


        <?php if ($setting_details->is_wind_speed==1 || $setting_details->is_wind_direction==1 || ($setting_details->is_temperature==1) || ($setting_details->is_applicator_name==1) || ($setting_details->is_applicator_number==1 && $invoice_report_details->applicator_number!='' ) ||  ($setting_details->is_applicator_phone==1 && $invoice_report_details->applicator_phone_number!='' ) || ($setting_details->is_property_address==1) || ($setting_details->is_property_size==1) || ($setting_details->is_date==1) || ($setting_details->is_time==1)    ) {

         ?>

        <td>

          <table width="100%" class="table table-condensed mannual application_tbl">
            <tr>
              <td>
                <table width="100%" class="table table-condensed inside-tabel mannual application_tbl">
                  <thead>
                    <tr>
                      <td class="default-font-color"><u><strong>APPLICATION DETAILS</strong></u></td>
                    </tr>
                  </thead>

                  <tbody>

                    <?php 
                              if ($setting_details->is_wind_speed==1) {
                            ?>

                    <tr>
                      <td align="left">
                        <b>Wind Speed:</b> <?=  $invoice_report_details->wind_speed   ?>
                      </td>
                    </tr>

                    <?php  } ?>

                    <?php 
                              if ($setting_details->is_wind_direction==1) {
                            ?>

                    <tr>
                      <td align="left">
                        <b>Wind Direction:</b> <?=  $invoice_report_details->direction   ?>
                      </td>
                    </tr>

                    <?php  } ?>

                    <?php 
                              if ($setting_details->is_temperature==1) {
                            ?>

                    <tr>
                      <td align="left">
                        <b>Temperature:</b> <?=  $invoice_report_details->temp   ?>
                      </td>
                    </tr>

                    <?php  } ?>

                    <?php 
                              if ($setting_details->is_applicator_name==1) {
                            ?>

                    <tr>
                      <td align="left">

                        <b>Applicator's Name:</b>
                        <?= $invoice_report_details->user_first_name.' '.$invoice_report_details->user_last_name  ?>
                      </td>
                    </tr>

                    <?php  } ?>


                    <?php 
                              if ($setting_details->is_applicator_number==1 && $invoice_report_details->applicator_number!='' ) {
                            ?>

                    <tr>
                      <td align="left">
                        <b>Applicator's #:</b> <?= $invoice_report_details->applicator_number  ?>
                      </td>
                    </tr>

                    <?php  } ?>



                    <?php 
                              if ($setting_details->is_applicator_phone==1 && $invoice_report_details->applicator_phone_number!='' ) {
                            ?>

                    <tr>
                      <td align="left">
                        <b>Applicator's Contact:</b> <?= $invoice_report_details->applicator_phone_number  ?>
                      </td>
                    </tr>

                    <?php  } ?>

                    <?php 
                              if ($setting_details->is_property_address==1) {
                            ?>

                    <tr>
                      <td align="left">
                        <b>Property Address:</b>
                        <?= $property_address_first.', '.$invoice_detail->property_city.', '.$invoice_detail->property_state.', '.$invoice_detail->property_zip  ?>


                      </td>
                    </tr>

                    <?php  } ?>


                    <?php 
                              if ($setting_details->is_property_size==1) {
                            ?>

                    <tr>
                      <td align="left">
                        <b>Property Size:</b> <?= $invoice_detail->yard_square_feet  ?>
                      </td>
                    </tr>

                    <?php  } ?>

                    <?php 
                              if ($setting_details->is_date==1) {
                            ?>

                    <tr>
                      <td align="left">
                        <b>Date of application:</b>
                        <?= date("m/d/Y" ,strtotime( $invoice_report_details->job_completed_date))  ?>
                      </td>
                    </tr>

                    <?php  } ?>


                    <?php 
                              if ($setting_details->is_time==1) {
                            ?>

                    <tr>
                      <td align="left">
                        <b>Time of application:</b>
                        <?= date("g:i A" ,strtotime( $invoice_report_details->job_completed_time))  ?>
                      </td>
                    </tr>

                    <?php  } ?>



                  </tbody>

                </table>
              </td>
            </tr>
          </table>

        </td>

        <?php $i++;  } } ?>


        <?php  if($product_details) {

      if ($setting_details->is_product_name || $setting_details->is_epa || $setting_details->is_active_ingredients || $setting_details->is_application_rate || $setting_details->is_estimated_chemical_used || $setting_details->is_chemical_type || $setting_details->is_re_entry_time || $setting_details->is_weed_pest_prevented || $setting_details->is_application_type   ) { 


    foreach($product_details as $key => $product_details_value) {

       $ingredientDatails = getActiveIngredient(array('product_id'=>$product_details_value->product_id));
                                      $ingredientarr = array();
                            if ($ingredientDatails) { foreach ($ingredientDatails as $key2 => $value2) { 
                             $ingredientarr[] =  $value2->active_ingredient.' : '.$value2->percent_active_ingredient.' % ';
                            } }

           $estimated_chemical_used =estimateOfPesticideUsed($product_details_value,$invoice_detail->yard_square_feet);  


        if  ($setting_details->is_product_name==1 || ($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber )  || ($setting_details->is_active_ingredients==1 && $ingredientDatails ) || ($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0 ) ||  ($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!='') || ($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0 ) ||  ($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='' ) || ($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!='') ||  ($setting_details->is_application_type==1 && $product_details_value->application_type!=0 )   ) {
          
        


    
       if ($i%2 == 0 && $i != 0){
        echo '</tr><tr>';
       }

    ?>

        <td>
          <table width="100%" class="table table-condensed mannual" style="border: 1px solid">
            <tr>
              <td>
                <table width="100%" class="table table-condensed inside-tabel mannual">

                  <?php 
                              if ($setting_details->is_product_name==1) {
                            ?>


                  <thead>
                    <tr class="default-font-color">
                      <td align="left">
                        PRODUCT NAME: <?= $product_details_value->product_name ?>
                      </td>
                    </tr>
                  </thead>

                  <?php  } ?>

                  <tbody>


                    <?php 
                              if ($setting_details->is_epa==1 && $product_details_value->epa_reg_nunber ) {
                            ?>
                    <tr>
                      <td align="left">
                        EPA #: <?= $product_details_value->epa_reg_nunber  ?>
                      </td>
                    </tr>

                    <?php  } ?>



                    <?php

                           
                              if ($setting_details->is_active_ingredients==1 && $ingredientDatails ) {
                            ?>


                    <tr>
                      <td align="left">

                        Active ingredients: <?= implode(', ',$ingredientarr)  ?>
                      </td>
                    </tr>

                    <?php  } ?>



                    <?php 
                              if ($setting_details->is_application_rate==1 && !empty($product_details_value->application_rate) && $product_details_value->application_rate !=0 ) {
                            ?>
                    <tr>
                      <td align="left">
                        <?php 
                                $application_rate = '';
                                  if (!empty($product_details_value->application_rate) && $product_details_value->application_rate !=0) {
                                         $application_rate = $product_details_value->application_rate.' '.$product_details_value->application_unit.' / '.$product_details_value->application_rate_per.' '.$product_details_value->application_per_unit;
                                    }
                                 ?>
                        Application Rate: <?= $application_rate  ?>
                      </td>
                    </tr>

                    <?php  } ?>



                    <?php
  
                              
                              if ($setting_details->is_estimated_chemical_used==1 && $estimated_chemical_used!='' ) {
                            ?>

                    <tr>
                      <td align="left">
                        Estimated Chemical Used: <?= $estimated_chemical_used?>
                      </td>
                    </tr>

                    <?php  } ?>



                    <?php 
                              if ($setting_details->is_chemical_type==1 && $product_details_value->chemical_type!=0 ) {
                            ?>

                    <tr>
                      <td align="left">
                        <?php 
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
                        Chemical Type: <?= $chemical_type  ?>
                      </td>
                    </tr>

                    <?php  } ?>



                    <?php 
                              if ($setting_details->is_re_entry_time==1 && $product_details_value->re_entry_time!='' ) {
                            ?>

                    <tr>
                      <td align="left">
                        Re-Entry Time: <?= $product_details_value->re_entry_time  ?>
                      </td>
                    </tr>

                    <?php  } ?>


                    <?php 
                              if ($setting_details->is_weed_pest_prevented==1 && $product_details_value->weed_pest_prevented!='') {
                            ?>
                    <tr>
                      <td align="left">
                        Weed/Pest Prevented: <?= $product_details_value->weed_pest_prevented   ?>
                      </td>
                    </tr>
                    <?php  } ?>




                    <?php 
                              if ($setting_details->is_application_type==1 && $product_details_value->application_type!=0 ) {
                            ?>
                    <tr>
                      <td align="left">
                        <?php
                                $application_type ='';
                                if ($product_details_value->application_type==1) {
                                        $application_type = 'Broadcast';
                                    } else if($product_details_value->application_type==2) {
                                        $application_type = 'Spot Spray';
                                    } elseif ($product_details_value->application_type==3) {
                                        $application_type = 'Granular';          
                                    }
                                 ?>
                        Application Type: <?= $application_type  ?>
                      </td>
                    </tr>

                    <?php  } ?>


                  </tbody>

                </table>
              </td>
            </tr>
          </table>

        </td>

        <?php  $i++; } } } } ?>

      </tr>
    </table>






  </div>
  <?php } ?>
</body>
</html>