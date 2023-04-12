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

   .invoice-title h2, .invoice-title h3 {
    display: inline-block;
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 1px dotted;
}
.main_table {
  margin-top: 15px;
}

.secondry_table {
  margin-top: 25px;
}
.main_table_statement {
  border : 1px solid #b2b2b2;
}

.mannul_border  td {
  border : 1px solid #b2b2b2;
}
.main_table_statement, .main_table_statement tr  {
  border-right: 1px solid #b2b2b2;
}
.none_border td {
  border-right: 1px solid <?= $setting_details->invoice_color  ?>;
}

.main_table_statement tr:nth-child(even) {
  background-color: #e6e6e6;
}
.blank_tr td {
  padding: 9px !important; 
}


.th-head td {
  padding: 5px !important;

}

hr {
  margin-top: 5px !important;
  margin-bottom: 10px !important;
border-top: 1px solid <?= $setting_details->invoice_color  ?>!important;
}

.account-head , .account-dt {
  float: left;
}

/* COPY FROM PDF_INVOICE.php STYLE -- mimicing that here */

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
  .text-center{
    text-align: center;
  }

</style>
<link href="<?= base_url('assets/admin/assets/css/bootstrap.min.pdf.css') ?>" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</head>
<body>
  <?php
    $setting_address_array = explode(',', $setting_details->company_address);
    $setting_address_first = array_shift($setting_address_array);
    $setting_address_last = implode(',',$setting_address_array);
 ?>
  <div class="container">

<?php 

$customer_billing_address = explode(',', $customer_details['billing_street']);
$customer_address_first = array_shift($customer_billing_address);
$customer_address_last = implode(',',$customer_billing_address);
$total_partial = 0;
$total_invoice_amount = 0;
$total_refund_amount = 0;
$total_late_fee = 0;
$available_credit = $customer_details['credit_amount'];
if ($invoice_details) {

   $total_partial_arr =   array_column($invoice_details, 'partial_payment');
   $total_partial =  array_sum($total_partial_arr);

   $invoice_id_arr =   array_column($invoice_details, 'invoice_id');
   $all_invoives_tax_amount  =   getAllSalesTaxSumByInvoices($invoice_id_arr);
  
  //  $total_cost_arr =   array_column($invoice_details, 'cost');
  $total_cost_arr =   array_column($invoice_details, 'final_cost');
  $total_late_fee_arr =   array_column($invoice_details, 'late_fee');
  $total_late_fee = array_sum($total_late_fee_arr);

  $total_refund_arr =   array_column($invoice_details, 'refund_amount_total');
  $total_refund_amount = array_sum($total_refund_arr);

  // $total_invoice_amount = array_sum($total_cost_arr) + $all_invoives_tax_amount;
  $total_invoice_amount = array_sum($total_cost_arr);

}


 ?>

<table width="100%" style="margin-bottom: 20px;">
<!-- START TOP FOLD -->		
      <tr id="top-fold">
        <td valign="top">

          <address>
            <strong><?= $setting_details->company_name ?></strong><br>
            <?php
                echo $setting_address_first.'<br>'.$setting_address_last;
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
        <td><strong>STATEMENT NO: #<?= $customer_details['customer_id'].$setting_details->company_id ?></strong>&#160;&#160;&#160;&#160;&#160;&#160;&#160;<strong>CUSTOMER ID: <?= $customer_details['customer_id'] ?></strong></td>
        <td align="right"> 
          <strong>
            <?php
                $reg_date = false;
                $first_date = 'N/A';
                $second_date = Date("m-d-Y");
            		if (isset($statement_start_date) && $statement_start_date != 0) {
                    $reg_date = true;
                    $first_date = date('m-d-Y', strtotime($statement_start_date));
                }
                if (isset($statement_end_date) && $statement_end_date != 0) {
                    $reg_date = true;
                    $second_date = date('m-d-Y', strtotime($statement_end_date));
                }
                if ($reg_date == false) {
                    echo Date("m-d-Y");
                } else {
                    echo "DATE RANGE: " . $first_date . " - " . $second_date;
                }
            ?>
          </strong>
        </td>
      </tr>
    </table>
    
    <table width="100%" class="table table-condensed" style="margin-bottom: 20px;">
      <tr class="border-bottom default-font-color">
        <td align="left" width="40%">
          BILL TO
        </td>
        <td align="left" width="20%">
          ACCOUNT SUMMARY
        </td>
      </tr>
      <tr>
        <td align="left" width="40%">

        <table width="100%" >

            <!-- first name last name
            billing street
            billing city, billing state, zip code -->
            <tr>
              <td><?=  $customer_details['first_name'].' '.$customer_details['last_name'] ?></td>    
            </tr>
            <tr>
              <td><?php echo $customer_address_first.'<br>'.$customer_details['billing_city'].', '.$customer_details['billing_state'].', '.$customer_details['billing_zipcode']; ?></td>   
            </tr>
            
        </table>

        </td>
        <td align="left" width="40%">

          <table width="100%" >
            <tr>
              <td>Previous Balance</td>    
              <td>$</td>    
              <td class="text-right" > <?= number_format((float)$past_invoice_total,2)  ?> </td>    
            </tr>
            <tr>
              <td>Credits </td>
              <td>$</td>    
              <td class="text-right" ><?= number_format($total_partial,2)  ?></td>       
            </tr>
            <tr>
              <td>New Charges </td> 
              <td>$</td>    
              <td class="text-right" ><?= number_format($total_invoice_amount,2)  ?></td>      
            </tr>
            <tr>
              <td>Refunds </td> 
              <td>$</td>    
              <td class="text-right" ><?= number_format($total_refund_amount,2)  ?></td>      
            </tr>
            <?php if(isset($available_credit)): ?>
            <tr>
              <td>Available Credit </td> 
              <td>$</td>    
              <td class="text-right" ><?= number_format($available_credit,2)  ?></td>      
            </tr>
            <?php endif; ?>
            <?php if($total_late_fee> 0): ?>
            <tr>
              <td>Late Fee </td> 
              <td>$</td>    
              <td class="text-right" ><?= number_format(@$total_late_fee,2)  ?></td>      
            </tr>
            <?php endif; ?>
            <tr>
              <td><b>Total Balance Due</b></td>   
              <td><b>$</b></td>    
              <!-- <td class="text-right"><b>
                <?php 
                  if($total_refund_amount > 0){
                    number_format($total_invoice_amount-$total_partial+$past_invoice_total-$total_refund_amount,2);  
                  } else {
                    number_format($total_invoice_amount-$total_partial+$past_invoice_total,2);  
                  }
                ?>
              </b>
            </td>       -->
              <td class="text-right"><b>
                <?php
                $total_balance_due = number_format($total_invoice_amount-$total_partial+$past_invoice_total + $total_late_fee,2);
                if( $total_balance_due > 0){
                  ?>
                  <?= $total_balance_due; ?> 
                  <?php
                }
                 else {
                  ?>
                  <?= number_format(0,2)?>
                  <?php
                }
                ?>

                <!-- <?= number_format($total_invoice_amount-$total_partial+$past_invoice_total-(($total_refund_amount >0 ?$total_refund_amount : 0)),2); ?> -->
              </b>
            </td>      
            </tr>
            <tr>
              <td style="border-top:1px solid #b2b2b2 ; " >Payment Due Date</td>   
              <td style="border-top:1px solid #b2b2b2; " ></td>   
              <td class="text-right"  style="border:1px solid #b2b2b2; text-align: center;" ><?= Date("m-d-Y")  ?></td>   
            </tr>
            <tr>
									
              <td class="text-left" colspan="4"><p class="button_tr"><a href="<?=base_url("/customers/dashboard/").$customer_details['customer_id'] ?>" target="_blank"><strong><?php echo 'Access Your Account' ?></strong></a></p></td>
              <td></td>
									<td class="text-right"></td>
								</tr>
            
            </table>
        
        </td>
      </tr>
    </table>

<!-- END TOP FOLD --> 


  <table width="100%" class="table table-condensed main_table main_table_statement" cellspacing="0">
    <thead>
    
      <tr style="background-color:<?= $setting_details->invoice_color  ?>!important;color: #fff;">
       <td class="text-center" style="padding-left: 8px;" ><strong>Date</strong></td>
       <td class="text-center" style="text-align: center;"><strong>Invoice #</strong></td>       
       <td class="text-center" style="width: 280px; text-align: center;"><strong>Description</strong></td>                     
       <td class="text-center" style="text-align: center;"><strong>Charges</strong></td>                                 
       <td class="text-center" style="text-align: center;"><strong>Credits</strong></td>
       <td class="text-center" style="text-align: center;"><strong>Statement Balance</strong></td>
     </tr>
    
    </thead>

    <tbody>
      <?php $line_total=0; if ($invoice_details) { foreach ($invoice_details as $key => $value) {

          $total_tax_amount =  getAllSalesTaxSumByInvoice($value->invoice_id)->total_tax_amount;
         
          // $line_total += $value->cost+$total_tax_amount;
          $line_total += $value->final_cost + $value->late_fee;;

       ?>
       <!-- invoice balance -->
        <tr>
          <td class="text-left" style="width: 70px; padding-left: 8px;"><?php echo date('m-d-Y',strtotime($value->invoice_date)) ?></td>
          <td class="text-center" style="text-align: center;"><p><a href="<?=base_url("welcome/printInvoice/").$setting_details->company_id."/".$value->invoice_id;?>" target="_blank"><?php echo $value->invoice_id ?></a></p></td>
          <td class="text-center" style="width: 260px;"><?= isset($value->description) && $value->description != '' ? $value->property_address ." " . $value->property_city . ", " . $value-> property_state . " " . $value->property_zip . " - " . $value->description : $value->property_address ." " . $value->property_city . ", " . $value-> property_state . " " . $value->property_zip;  ?></td>
          <!-- <td class="text-center"><?= number_format($value->cost+$total_tax_amount,2);  ?></td> -->
          <td class="text-center" style="text-align: center;"><?= number_format($value->final_cost+ $value->late_fee,2);  ?></td>
          <td class="text-center" style="text-align: center;"></td>
          <td class="text-center" style="text-align: center;"><?= number_format($line_total,2)  ?></td>
        </tr>
                <!-- partial payments made -->
                <?php
            if (isset($value->partial_payments_logs)) {
             // die(print_r($partial_payments));
              foreach($value->partial_payments_logs as $key => $partial){
                $line_total -= $partial->payment_amount;
                ?>
                    <tr>
                      <td class="text-left" style="width: 70px;  padding-left: 8px;"><?php echo date('m-d-Y',strtotime($partial->payment_datetime)) ?></td>
                      <td class="text-center" style="text-align: center;"><p><a href="<?=base_url("welcome/printInvoice/").$setting_details->company_id."/".$value->invoice_id;?>" target="_blank"><?php echo $partial->invoice_id ?></a></p></td>
                      <td class="text-center"><?= $value->property_address . " " . $value->property_city . ", " . $value-> property_state . " " . $value->property_zip; ?> - Payment made:
                        <span>
                        <?php
                                switch ($partial->payment_method) {
                                    case "1":
                                        echo 'Check: '.$partial->check_number;
                                    break;
                                    case "2":
                                        echo 'BASYS: '.$partial->cc_number;
                                    break;
                                    case "3":
                                        echo 'Other: '.$partial->payment_note;
                                    break;
                                    case "4":
                                        echo 'Clover: '.$partial->cc_number;
                                    break;
                                    case "5":
                                        echo 'Applied Account Credit';
                                    break;
                                    case "0":
                                        echo 'Cash';
                                    break;
                                    default:
                                        echo $partial->payment_method;
                                }

                            ?>
                           </span>
                      </td>
                      <!-- <td class="text-center"><?= number_format(0,2);  ?></td> -->
                      <td class="text-center" style="text-align: center;"></td>
                      <td class="text-center" style="text-align: center;"><?= number_format($partial->payment_amount,2) ?></td>
                      <!-- <td class="text-center"><?= number_format(0,2);  ?></td> -->
                      <td class="text-center" style="text-align: center;"><?= number_format($line_total,2)  ?></td>
                    </tr>
                <?php
              }
            }
        ?>

        <!-- refund  payment -->
        <?php
            if ($value->refund_payments_logs != 0) {
              foreach($value->refund_payments_logs as $key => $refund){
                $line_total += $refund->refund_amount;
                ?>
                    <tr>
                      <td class="text-left" style="width: 70px; padding-left: 8px;"><?php echo date('m-d-Y',strtotime($refund->refund_datetime)) ?></td>
                      <td class="text-center" style="text-align: center;"><p><a href="<?=base_url("welcome/printInvoice/").$setting_details->company_id."/".$value->invoice_id;?>" target="_blank"><?php echo $refund->invoice_id ?></a></p></td>
                      <td class="text-left"><?= $value->property_address . " " . $value->property_city . ", " . $value-> property_state . " " . $value->property_zip; ?> - Refund issued
                      </td>
                      <td class="text-center" style="text-align: center;"></td>
                      <td class="text-center" style="text-align: center;"><?= number_format($refund->refund_amount,2);  ?></td>
                      <td class="text-center" style="text-align: center;"><?= number_format($line_total,2)  ?></td>
                    </tr>
                <?php
              }
            }
        ?>


        <!-- refund   issued -->
        <?php
            if ($value->refund_payments_logs != 0) {
              foreach($value->refund_payments_logs as $key => $refund){
                $line_total -= $refund->refund_amount;
                ?>
                    <tr>
                      <td class="text-left" style="width: 70px;  padding-left: 8px;"><?php echo date('m-d-Y',strtotime($refund->refund_datetime)) ?></td>
                      <td class="text-center" style="text-align: center;"><p><a href="<?=base_url("welcome/printInvoice/").$setting_details->company_id."/".$value->invoice_id;?>" target="_blank"><?php echo $refund->invoice_id ?></a></p></td>
                      <td class="text-left"><?= $value->property_address . " " . $value->property_city . ", " . $value-> property_state . " " . $value->property_zip; ?> - Refund paid:
                        <span>
                          <?php
                            switch ($refund->refund_method) {
                              case "1":
                                echo 'Check: '.$refund->check_number;
                                break;
                              case "2":
                                echo 'Card: '.$refund->cc_number;
                                break;
                              case "3":
                                echo 'Other: '.$refund->refund_note;
                                break;
                              default:
                                echo $refund->refund_method;
                            }

                          ?>
                           </span>
                      </td>
                      <td class="text-center" style="text-align: center;"></td>
                      <td class="text-center" style="text-align: center;">- <?= number_format($refund->refund_amount,2);  ?></td>
                      <td class="text-center" style="text-align: center;">  <?= number_format($line_total,2)  ?></td>
                    </tr>
                <?php
              }
            }
        ?>
        
      <?php }  } else { ?>
        
      <?php } ?> 
    </tbody> 
  </table>


    <table width="100%" class="main_table none_border" >
      <tr style="background-color:<?= $setting_details->invoice_color  ?>!important;color: #fff;" class="th-head" >
          <td class="text-right"width="80%" ><strong>Account Current Balance<strong></td>
          <td class="text-right" width="10%" style="text-align: right;"><strong>$<strong></td>
          <td class="text-center" width="10%" ><strong>
            <!-- <?= number_format($line_total+$past_invoice_total-$total_refund_amount,2)  ?> -->
            <?php
                // $total_balance_due = number_format($total_invoice_amount-$total_partial+$past_invoice_total,2);
                if( $total_balance_due > 0){
                  ?>
                  <?= $total_balance_due; ?>
                  <?php
                } else {
                  ?>
                  <?= number_format(0,2)?>
                  <?php
                }
                ?>
                <strong>
            </td>

      </tr>
   </table>

  


  <table width="100%" class="main_table" >
      <tr>
           <td class="text-center"><b>Your account balance is
              <?php
                // $total_balance_due = number_format($total_invoice_amount-$total_partial+$past_invoice_total,2);
                if( $total_balance_due > 0){
                  ?>
                  <?= $total_balance_due; ?> 
                  <?php
                } else {
                  ?>
                  <?= number_format(0,2)?>
                  <?php
                }
                ?>
              dollars. Please make your payment to cover the balance by the due date.</b></td>
      </tr>
      <?php foreach ($credit_details as $value){
        // die(print_r($invoice_details));
        
          
    
              print "<tr><td class='text-center'><b>Credit amount of \${$value->payment_amount} was added on ".date('m-d-Y',strtotime($value->payment_datetime))."</b></td></tr>";
        
      } ?>
    </table>

  <table width="100%" class="main_table" >
      <tr>
           <td class="text-center">Make all checks payable to <?= $setting_details->company_name  ?></td>
      </tr>
   </table>

  <table width="100%" class="main_table" >
      <tr>
           <td class="text-center" style="font-size: 14px" ><strong><b>Thank you for your business!</b></strong></td>
      </tr>
   </table>
   
   <table width="100%" class="main_table" >
      <tr>
           <td class="text-center">Should you have any enquiries concerning this statement, please contact <?= $user_details->user_first_name.' '.$user_details->user_last_name  ?> on <?php echo formatPhoneNum($user_details->phone); ?></td>
      </tr>
   </table>
 <hr>

  <table width="100%" >
      <tr>
           <td class="text-center"><?= $setting_details->company_address  ?></td>        
      </tr>
      <tr>
          <td class="text-center">E-mail: <?= $setting_details->company_email  ?> Web: <?= $setting_details->web_address  ?></td>
      </tr>
   </table>

</div>
 

</body>
</html>
