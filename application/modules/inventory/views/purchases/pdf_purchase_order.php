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

        .main_table {
            margin-top: 15px;
        }

        .secondry_table {
            margin-top: 25px;
        }

        .main_table_statement {
            border: 1px solid #b2b2b2;
        }

        .mannul_border td {
            border: 1px solid #b2b2b2;
        }

        .main_table_statement,
        .main_table_statement tr {
            border-right: 1px solid #b2b2b2;
        }

        .none_border td {
            border-right: 1px solid <?=$setting_details->invoice_color?>;
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
            border-top: 1px solid <?=$setting_details->invoice_color?> !important;
        }

        .account-head,
        .account-dt {
            float: left;
        }

        /* COPY FROM PDF_INVOICE.php STYLE -- mimicing that here */

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
        </style>
        <link href="<?=base_url('assets/admin/assets/css/bootstrap.min.pdf.css')?>" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>

    </head>

    <body>

        <?php

        $setting_address_array = explode(',', $setting_details->company_address);
        $setting_address_first = array_shift($setting_address_array);
        $setting_address_last = implode(',', $setting_address_array);
        ?>
        <div class="container">
            <table width="100%" style="margin-bottom: 20px;">
                <!-- START TOP FOLD -->
                <tr id="top-fold">
                    <td valign="top">
                        <address>
                            <strong><?=$setting_details->company_name?></strong><br>
                            <?php
                            echo $setting_address_first . '<br>' . $setting_address_last;
                            ?>
                            <br>
                            <?php
                            if (isset($setting_details->company_phone_number)) {?>
                            <?= formatPhoneNum($setting_details->company_phone_number); ?><br>
                            <?php }
                            echo $setting_details->company_email?><br>
                            <?php if ($setting_details->web_address != '') {?>
                            <a href="<?=$setting_details->web_address?>"><?=$setting_details->web_address?></a>
                            <?php }?>
                        </address>
                    </td>
                    <td align="right" valign="top">
                        <br>
                        <img class="logo"
                            src="<?=CLOUDFRONT_URL . 'uploads/company_logo/' . $setting_details->company_logo?>">
                    </td>
                </tr>
            </table>
            <table width="100%" class="table table-condensed" cellspacing="0" cellpadding="10">
                <tr class="first_tr">
                    <td align="center"><strong>PURCHASE ORDER NO: #<br><?=$purchase_order->purchase_order_number?></strong></td>
                    <td align="center"> <strong>ORDER DATE <br><?=Date("m/d/Y", strtotime($purchase_order->created_at))?></strong></td>
                    <td align="center"><strong>ORDERED BY <br><?= $purchase_order->name ?></strong></td>
                    <td align="center"> <strong>ORDER DATE <br>
                        <?php
                        if($purchase_order->ordered_date != "" && $purchase_order->ordered_date != null && $purchase_order->ordered_date != "0000-00-00"){
                            echo date("m/d/Y", strtotime($purchase_order->ordered_date));
                        }?>
                    </strong></td>
                </tr>
            </table>
            <table width="100%" class="table table-condensed" style="margin-bottom: 20px;">
                <tr class="border-bottom default-font-color">
                    <td align="left" width="33%">
                        VENDOR INFO:
                    </td>
                    <td align="left" width="33%">
                        SHIP TO INFO:
                    </td>
                    <td align="left" width="33%">
                        BILL TO INFO:
                    </td>
                </tr>
                <tr>
                    <td align="left" width="33%">
                        <table width="100%">
                            <tr>
                                <td><?= $purchase_order->vendor_name ?></td>
                            </tr>
                            <tr>
                                <td><?= $purchase_order->vendor_street_address ?></td>
                            </tr>
                            <tr>
                                <td><?= $purchase_order->vendor_city . ', ' . $purchase_order->vendor_state . ', ' .
                                    $purchase_order->vendor_zip_code; ?></td>
                            </tr>
                            <tr>
                                <td><?= $purchase_order->company_name?></td>
                            </tr>
                            <tr>
                                <td><?= formatPhoneNum($purchase_order->vendor_phone_number) ?></td>
                            </tr>
                            <tr>
                                <td><?= $purchase_order->vendor_email_address?></td>
                            </tr>
                        </table>

                    </td>
                    <td align="left" width="33%">

                        <table width="100%">
                            <tr>
                                <td><?= $purchase_order->location_name ?></td>
                            </tr>
                            <tr>
                                <td><?= $purchase_order->location_street ?></td>
                            </tr>
                            <tr>
                                <td><?= $purchase_order->location_city . ', ' . $purchase_order->location_state . ', ' .
                                    $purchase_order->location_zip; ?></td>
                            </tr>
                            <tr>
                                <td><?= formatPhoneNum($purchase_order->location_phone) ?></td>
                            </tr>
                        </table>
                    </td>

                    <td align="left" width="33%">
                         <table width="100%">
                            <tr>
                                <td><?= $setting_details->company_name ?></td>
                            </tr>
                            <tr>
                                <td>Attn: Accounts Payable</td>
                            </tr>
                            <tr>
                                <td><?= $setting_details->company_address ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table width="100%" class="table table-condensed main_table" cellspacing="0">
                <tr><td><b>DELIVERY DATE : </b> <?=Date("m/d/Y", strtotime($purchase_order->estimated_delivery_date))?></td></tr>
                <tr><td><b>PAYMENT TERM : </b> <?= $purchase_order->payment_terms ?></td></tr>
                <tr><td><b>PLACE OF ORIGIN : </b> <?= $purchase_order->place_of_origin ?></td></tr>
                <tr><td><b>PLACE OF DESTINATION : </b> <?= $purchase_order->place_of_destination ?></td></tr>
            </table>

            <!-- END TOP FOLD -->

            <table width="100%" class="table table-condensed main_table" cellspacing="0">
                <thead>
                    <tr style="background-color:<?=$setting_details->invoice_color?>!important;color: #fff;">
                        <td class="text-left" style="padding-left: 8px;"><strong>AMOUNT ORDERED</strong></td>
                        <td class="text-left"><strong>UNIT OF MEASURE</strong></td>
                        <td class="text-left"><strong>ITEM NUMBER</strong></td>
                        <td class="text-left"><strong>ITEM NAME</strong></td>
                        <td class="text-right" style="text-align: right; padding-right: 16px;"><strong>UNIT PRICE</strong></td>
                        <td class="text-center"><strong>EXT PRICE</strong></td>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                        $line_total = 0;
                        
                        $items = json_decode($purchase_order->items, true);
                        if ($items) {
                            foreach ($items as $key => $value) {
                    ?>
                    <tr>
                        <td class="text-center" style="text-align: center; padding-right: 16px;"><?php echo $value['quantity'] ?></td>
                        <td><?=$value['unit_type']?></td>
                        <td class="text-left"><?=$value['item_number']?></td>
                        <td class="text-left"><?=$value['name']?></td>
                        <td class="text-right" style="text-align: right; padding-right: 16px;">
                            <?='$   ' . number_format($value['unit_price'], 2) ?>
                        </td>
                        <td class="text-right" style="text-align: right; padding-right: 16px;">
                         <?php
                            // $line_total += $cost;
                            $line_total += round(($value['quantity']*$value['unit_price'] ), 2);
                            // echo '$   ' . number_format(($line_total), 2);
                            ?>
                            <?='$   ' . number_format(($value['quantity']*$value['unit_price'] ), 2) ?>
                        </td>
                    </tr>

                    <?php }}?>
                </tbody>
            </table>

            <table width="100%" class="main_table none_border" style="border: 1px solid #ddd;">
                <tr class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right"
                        width="80%"><strong>Subtotal:</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right"
                        width="10%"><strong>$</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;"
                        class="text-center" width="10%"><strong><?= number_format($line_total,2)  ?></strong>
                    </td>
                </tr>
                <tr style="" class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="80%"><strong>Discount</strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="10%"><strong>$</strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;" class="text-center"
                        width="10%"><strong><?=number_format($purchase_order->discount, 2)?></strong>
                    </td>
                </tr>
                <tr style="" class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="80%"><strong>Shipping Cost</strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="10%"><strong>$</strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;" class="text-center"
                        width="10%"><strong><?=number_format($purchase_order->freight, 2)?></strong>
                    </td>
                </tr>
                <?php 
                    $discount = number_format($purchase_order->discount, 2);
                    $total_discounted = $line_total - $discount;
                    $shipping_cost = number_format($purchase_order->freight, 2);
                    $pretax_total = $total_discounted + $shipping_cost;
                   
                    $line_tax_amount = $pretax_total  * $purchase_order->tax/ 100;

                ?>
                <tr style="" class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="80%"><strong>Tax
                            <?php echo "(" . $purchase_order->tax . "%)"; ?></strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="10%"><strong>$</strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;" class="text-center"
                        width="10%"><strong><?=number_format($line_tax_amount, 2)?></strong>
                    </td>
                </tr>
                <?php 
                   
                    // $taxed_total = $pretax_total * $purchase_order->tax/ 100;
                    $taxed_total = $line_tax_amount + $pretax_total;
                ?>
                <tr style="background-color:<?= $setting_details->invoice_color  ?>!important;color: #fff;"
                    class="th-head">
                    <td style="text-align: right;" class="text-right" width="80%"><strong>Total Price:</strong></td>
                    <td style="text-align: right;" class="text-right" width="10%"><strong>$</strong></td>
                    <td class="text-center" width="10%"><strong><?= number_format($taxed_total,2)  ?></strong>
                    </td>
                </tr>
            </table>

            <table width="100%" class="main_table">
                <tr><td style="font-size: 20px;"><b>Invoices</b></td></tr>
            </table>

            <!-- END TOP FOLD -->

            <table width="100%" class="table table-condensed main_table" cellspacing="0">
                <thead>
                    <tr style="background-color:<?=$setting_details->invoice_color?>!important;color: #fff;">
                        <td class="text-left" style="padding-left: 8px;"><strong>INVOICE NUMBER</strong></td>
                        <td class="text-right"><strong>AMOUNT</strong></td>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    foreach($purchase_order_invoices as $key => $value) {
                    ?>
                    <tr>
                        <td class="text-left" style="padding-right: 16px;"><?php echo $value->po_invoice_id ?></td>
                        <td class="text-right" style="padding-right: 16px;"><?='$   ' . number_format($value->invoice_total_amt, 2) ?></td>
                    </tr>

                    <?php }?>
                </tbody>
            </table>

            <table width="100%" class="main_table">
                <tr>
                    <td class="text-left" style="font-size: 14px"><strong style="color:<?=$setting_details->invoice_color?> ;">Sign here to accept purchase order and agree to terms & conditions:</strong>________________________________</td>
                </tr>
            </table>

            <table width="100%" class="main_table" style="text-align: center">
                <tr>
                    <td class="text-center">Do you have questions concerning this purchase order?</td>
                </tr>
                <tr>
                    <td class="text-center">
                        Call <?= ucfirst($user_details->user_first_name).' '.ucfirst($user_details->user_last_name) ?>
                        at <?php echo formatPhoneNum($user_details->phone)   ?></td>
                </tr>
            </table>
            <hr>

            <table width="100%" style="text-align: center;">
                <tr>
                    <td class="text-center"><?=$setting_details->company_address?></td>
                </tr>
                <tr>
                    <td class="text-center">E-mail: <?=$setting_details->company_email?>
                        <?=$setting_details->web_address != '' ? 'Web: ' . $setting_details->web_address : '';?></td>
                </tr>
            </table>

        </div>


    </body>

</html>