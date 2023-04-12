<html>
<head>

    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <title>SPRAYE</title>

<link href="<?= base_url('assets/admin/assets/css/bootstrap.min.pdf.css') ?>" rel="stylesheet" id="bootstrap-css">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<style type="text/css">
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

@page {
  size: A4;
  margin: 0;
}
@media print {
  html, body {
    width: 210mm;
    height: 297mm;
    -webkit-print-color-adjust: exact !important;
  }

}


.panel-body{
    padding: 0;
}

</style>



</head>
<body onload="myFunction()" >
<?php 
$setting_address_array = explode(',', $setting_details->company_address);
$setting_address_first = array_shift($setting_address_array);
$setting_address_last = implode(',',$setting_address_array);
 ?>


<br>
<div class="container" style="width: 20cm;height: 28.7cm;border :1px solid; " >
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                <!-- <h2>Invoice</h2><h3 class="pull-right">Order # 12345</h3> -->
            </div>
            <br>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                    <strong><?= $setting_details->company_name ?></strong><br>
                        <?= $setting_address_first ?><br>
                        <?= $setting_address_last ?><br>
                        <?= $setting_details->company_email ?><br>
                        <a href="<?= $setting_details->web_address ?>"><?= $setting_details->web_address ?></a>
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                    <img style="width:100px;height:auto;"  src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$setting_details->company_logo ?>" >

                </div>
            </div>

            <div class="invoice-title">
                <h2 style="color:<?= $setting_details->invoice_color; ?> !important" >INVOICE</h2></h3>
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <strong>BILL TO</strong><br>
                        <?= $invoice_details->first_name.' '.$invoice_details->last_name ?><br>
                        <?= $invoice_details->property_city.' , '.$invoice_details->property_state.' , '.$invoice_details->property_zip ?><br>
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>INVOICE # </strong><?= $invoice_details->invoice_id ?><br>
                        <strong>DATE</strong> <?= $invoice_details->invoice_date ?><br>
                        <strong>DUE DATE</strong> <?= $invoice_details->invoice_date ?><br>
                    </address>
                </div>
            </div>
        </div>
    </div>
    <hr style="border-color:#9794c7">
<br>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                
                <div class="panel-body">
                    
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                  <tr style="background-color:<?= $setting_details->invoice_color  ?>!important;color: #fff;">
                                    <td><strong>ACTIVITY</strong></td>


                                    <td class="text-center">
                                        Product Name
                                    </td>
                                    <td class="text-center">
                                        EPA #
                                    </td>        

                                    <td class="text-center">
                                        Active Ingredient
                                    </td>        
                                                        
                                    <td class="text-right">AMOUNT</td>
                                </tr>

                            </thead>
                            <tbody>

                                    <tr>
                                    <td><strong><?=$invoice_details->description  ?></strong></td>
                                    <td colspan="3">
                                        <table class="table table-condensed text-center">
                                            <tbody>
                                                <?php

                                                $product_details =  getProductByJob(array('job_id'=>$invoice_details->job_id));

                                                 if ($product_details) { foreach ($product_details as $key2 => $value2) { ?>
                                                    
                                                <tr>
                                                    <td><?= $value2->product_name ?></td>
                                                    <td><?= $value2->epa_reg_nunber ?></td>
                                                    <td>
                                                        <?php

                                                        $ingredientDatails = getActiveIngredient(array('product_id'=>$value2->product_id));

                                                         if ($ingredientDatails) { foreach ($ingredientDatails as $key3 => $value3) { ?>
                                                                                                                   
                                                            
                                                        <span><?= $value3->active_ingredient.' '.$value3->percent_active_ingredient.' % ' ?>s </span><br>

                                                        <?php } } ?>                                                
                                                    </td>
                                                </tr>
                                              
                                                <?php } } ?>

                                            </tbody>
                                        </table>
                                    </td> 

                                
                                    <td class="text-right"><?= number_format($invoice_details->cost,2);  ?></td>
                                </tr>




                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                               
                                
                               <tr>
                                    <td></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center">Subtotal</td>
                                    <td class="text-right"><?= number_format($invoice_details->cost,2);  ?></td>
                            </tr>

                                 <?php 

                                $tax_amount = 0;

                                 if ($invoice_details->tax_name!='') {
                                   $tax_amount =  $invoice_details->tax_amount;

                                  ?>

                                <tr>
                                    <td></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><?= $invoice_details->tax_name.' ('.floatval($invoice_details->tax_value).'%) '  ?></td>
                                    <td class="text-right"><?= number_format($tax_amount,2);  ?></td>
                                </tr>
 
                                      
                                <?php } ?>                               


                               

                                <tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line">PAYMENT</td>
                                    <td class="thick-line text-center"></td>
                                    <td class="thick-line text-right"><?= number_format($invoice_details->cost+$tax_amount,2);  ?></td>
                                </tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line">BALANCE DUE</td>
                                    <td class="no-line text-center"></td>
                                    <td class="no-line text-right"><b>$<?php 

                                    switch ($invoice_details->payment_status) {
                                        case 0:
                                            echo number_format($invoice_details->cost+$tax_amount,2);
                                            break;
                                        case 1:
                                            echo number_format($invoice_details->cost+$tax_amount,2);
                                            break;
                                        case 2:
                                            echo number_format(0,2);
                                            break;
                                        case 3:
                                            echo number_format($invoice_details->cost+$tax_amount,2);
                                            break;
                                        
                                    }

                                     ?></b></td>
                                </tr>

                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



</body>


<script>

function myFunction() {
 window.print();
}
</script>

</html>
