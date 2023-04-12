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
            <table width="100%" class="table table-condensed">
                <tr class="first_tr">
                    <td><strong>ESTIMATE NO: #<?=$estimate_details->estimate_id?></strong></td>
                    <td align="right"> <strong><?=Date("m/d/Y", strtotime($estimate_details->estimate_date))?></strong>
                    </td>
                </tr>
            </table>
            <table width="100%" class="table table-condensed" style="margin-bottom: 20px;">
                <tr class="border-bottom default-font-color">
                    <td align="left" width="40%">
                        BILL TO
                    </td>
                    <td align="left" width="20%">
                        PAYMENT TERMS
                    </td>
                    <td align="left" width="40%">
                        MESSAGE TO CUSTOMER
                    </td>
                </tr>
                <tr>
                    <td align="left" width="40%">

                        <table width="100%">

                            <!-- first name last name
                            billing street
                            billing city, billing state, zip code -->

                            <?php
                            $customer_billing_address = explode(',', $customer_details->billing_street);
                            $customer_address_first = array_shift($customer_billing_address);
                            ?>

                            <tr>
                                <td><?=$customer_details->first_name . ' ' . $customer_details->last_name?></td>
                            </tr>
                            <tr>
                                <td><?php echo $customer_address_first ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $customer_details->billing_city . ', ' . $customer_details->billing_state . ', ' .
                                    $customer_details->billing_zipcode; ?></td>
                            </tr>
                        </table>

                    </td>
                    <td align="left" width="40%">

                        <?php switch ($estimate_details->program_price) {
                            case 1:
                                echo 'One-Time Project Invoicing';
                                break;
                            case 2:
                                echo 'Invoiced at Job Completion';
                                break;
                            case 3:
                                echo 'Manual Billing';
                                break;

                        }?>

                    </td>

                    <td align="left" width="40%">
                        <?=(isset($estimate_details->notes))?str_replace(array("\r\n", "\r", "\n"), "<br />", $estimate_details->notes):''?>
                    </td>

                </tr>
            </table>

            <!-- END TOP FOLD -->

            <table width="100%" class="table table-condensed main_table" cellspacing="0">
                <thead>

                    <tr style="background-color:<?=$setting_details->invoice_color?>!important;color: #fff;">
                        <td class="text-left" style="padding-left: 8px;"><strong>PROPERTY</strong></td>
                        <td class="text-left"><strong>SERVICE</strong></td>
                        <td class="text-left"><strong>DESCRIPTION</strong></td>
                        <td class="text-right" style="text-align: right; padding-right: 16px;"><strong>PRICE</strong>
                        </td>
                        <!-- <td class="text-center"><strong>Sales Tax</strong></td>                      -->
                        <td class="text-right" style="display:none;"><strong>Line Total</strong></td>


                    </tr>

                </thead>

                <tbody>
                    <?php $line_total = 0;

                        $sales_tax_details = getAllSalesTaxByProperty($estimate_details->property_id);

                        if ($job_details) {

                            foreach ($job_details as $key => $value) {

                                if ($value['price_override'] != '' && $value['price_override'] != 0 && $value['is_price_override_set'] == 1) {
                                    $cost = $value['price_override'];

                                } else if ($value['price_override'] != '' && $value['price_override'] == 0 && $value['is_price_override_set'] == 1) {
                                    $cost = number_format(0, 2);
                                } else {

                                    $priceOverrideData = getOnePriceOverrideProgramProperty(array('property_id' => $estimate_details->property_id, 'program_id' => $estimate_details->program_id));

                                    if ($priceOverrideData && $priceOverrideData->price_override != 0 && $priceOverrideData->is_price_override_set == 1) {
                                        // $price = $priceOverrideData->price_override;
                                        $cost = $priceOverrideData->price_override;

                                    } else if ($priceOverrideData && $priceOverrideData->price_override == 0 && $priceOverrideData->is_price_override_set == 1) {
                                        $cost = number_format(0, 2);
                                    } else {
                                        //else no price overrides, then calculate job cost
                                        $lawn_sqf = $estimate_details->yard_square_feet;
                                        $job_price = $value['job_price'];

                                        //get property difficulty level
                                        if (isset($estimate_details->difficulty_level) && $estimate_details->difficulty_level == 2) {
                                            $difficulty_multiplier = $setting_details->dlmult_2;
                                        } elseif (isset($estimate_details->difficulty_level) && $estimate_details->difficulty_level == 3) {
                                            $difficulty_multiplier = $setting_details->dlmult_3;
                                        } else {
                                            $difficulty_multiplier = $setting_details->dlmult_1;
                                        }

                                        //get base fee
                                        if (isset($value['base_fee_override'])) {
                                            $base_fee = $value['base_fee_override'];
                                            // die(print_r('<pre>Base Fee is: '
                                            // . $base_fee
                                            // . '</pre>'));

                                        } else {
                                            $base_fee = $setting_details->base_service_fee;
                                            // die(print_r('<pre>Base Fee is: $'
                                            // . $base_fee.'<br>Difficulty Multiplier is: '.
                                            // number_format($difficulty_multiplier, 1) . '<br>Job Price is: $'. $job_price . ' per 1,000 Sq Ft.<br>Yard Square Footage is: ' . $lawn_sqf . ' Sq. Ft.<br>Total Cost is: $' . number_format(($job_price * $lawn_sqf * $difficulty_multiplier)/1000 + $base_fee, 2)
                                            // . '</pre>'));

                                        }

                                        $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                        //get min. service fee
                                        if (isset($value['min_fee_override'])) {
                                            $min_fee = $value['min_fee_override'];
                                        } else {
                                            $min_fee = $setting_details->minimum_service_fee;
                                        }

                                        // Compare cost per sf with min service fee
                                        if ($cost_per_sqf > $min_fee) {
                                            $cost = $cost_per_sqf;
                                        } else {
                                            $cost = $min_fee;
                                        }
                                    }
                                }
                                ?>
                    <tr>
                        <td class="text-left">
                            <?php echo $estimate_details->property_address ? $estimate_details->property_address : ''; ?>
                        <td class="text-left"><?=$value['job_name']?>


                        </td>
                        <td class="text-left"><?=$value['job_description']?>


                        </td>

                        <td class="text-right" style="text-align: right; padding-right: 16px; white-space: nowrap;">
                            <?='$&nbsp;' . number_format($cost, 2)?></td>

                        <td class="text-right" style="display:none; white-space: nowrap;">
                            <?php
                            // $line_total += $cost;
                            $line_total += round($cost, 2);
                            echo '$&nbsp;' . number_format(($line_total), 2);
                            ?>
                        </td>
                    </tr>

                    <?php }}?>
                </tbody>
            </table>


            <table width="100%" class="main_table none_border" style="border: 1px solid #ddd;">

                <!-- COUPON SECITON -->
                <?php if (isset($coupon_estimate) && !empty($coupon_estimate)) {
                    $estimate_total_cost = $line_total;?>

                <tr style="" class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="80%"><strong>Total Price of Services:</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="10%"><strong>$</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;" class="text-center"
                        width="10%"><strong><?=number_format($line_total, 2)?></strong></td>
                </tr>

                <?php foreach ($coupon_estimate as $coupon_details) {
                        $is_expired = (strtotime(date("Y-m-d")) > strtotime($coupon_details->expiration_date));
                        if ($coupon_details->expiration_date !== '0000-00-00 00:00:00' && !$is_expired) {?>
                <tr style="" class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="80%"><strong><?php echo $coupon_details->coupon_code ?>:</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="10%"><strong>- $</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;" class="text-center"
                        width="10%"><strong>
                            <?php $coupon_amm = 0;
                                        if ($coupon_details->coupon_amount_calculation == 0) { // flat amm
                                            $coupon_amm = $coupon_details->coupon_amount;
                                        } else { // perc
                                            $coupon_amm = ($coupon_details->coupon_amount / 100) * $estimate_total_cost;
                                        }
                                        echo "(" . number_format($coupon_amm, 2) . ")"; // echo coupon ammount
                                        $estimate_total_cost -= $coupon_amm;
                                        if ($estimate_total_cost < 0) {
                                            $estimate_total_cost = 0;
                                        }
                                        ?>
                        </strong>
                    </td>
                </tr>

                <?php }
                    // check if coupon doesn't have an expiration
                            elseif ($coupon_details->expiration_date == '0000-00-00 00:00:00') {?>
                <tr style="" class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="80%"><strong><?php echo $coupon_details->coupon_code ?>:</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="10%"><strong>- $</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;" class="text-center"
                        width="10%"><strong>
                            <?php $coupon_amm = 0;
                if ($coupon_details->coupon_amount_calculation == 0) { // flat amm
                    $coupon_amm = $coupon_details->coupon_amount;
                } else { // perc
                    $coupon_amm = ($coupon_details->coupon_amount / 100) * $estimate_total_cost;
                }
                echo "(" . number_format($coupon_amm, 2) . ")"; // echo coupon ammount
                $estimate_total_cost -= $coupon_amm;
                if ($estimate_total_cost < 0) {
                    $estimate_total_cost = 0;
                }
                ?>
                        </strong>
                    </td>
                </tr>
                <?php }
                }?>
                <?php $line_tax_amount = 0;
                if ($sales_tax_details) {
                foreach ($sales_tax_details as $property_sales_tax) {
                $line_tax_amount += $estimate_total_cost * $property_sales_tax->tax_value / 100;?>
                <tr style="" class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="80%"><strong>Sales Tax
                            <?php echo "(" . $property_sales_tax->tax_value . "%)"; ?></strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="10%"><strong>$</strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;" class="text-center"
                        width="10%"><strong><?=number_format($line_tax_amount, 2)?></strong>
                    </td>
                </tr>
                <?php }
                $estimate_total_cost += $line_tax_amount;
                }?>
                <tr style="background-color:<?=$setting_details->invoice_color?>!important;color: #fff;"
                    class="th-head">
                    <td style="text-align: right;" class="text-right" width="80%"><strong>Total Price:</strong></td>
                    <td style="text-align: right;" class="text-right" width="10%"><strong>$</strong></td>
                    <td class="text-center" width="10%"><strong>
                            <?=number_format($estimate_total_cost, 2);?>
                        </strong>
                    </td>
                </tr>

                <?php } else {
                $line_tax_amount = 0;
                if ($sales_tax_details) {
                foreach ($sales_tax_details as $property_sales_tax) {
                // echo $property_sales_tax->tax_name. ' ('.$property_sales_tax->tax_value.'%)<br>';
                // echo $cost * $property_sales_tax->tax_value /100 . '<br>';
                $line_tax_amount += $line_total * $property_sales_tax->tax_value / 100;?>
                <tr style="" class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="80%"><strong>Sales Tax
                            <?php echo "(" . $property_sales_tax->tax_value . "%)"; ?></strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right" width="10%"><strong>$</strong>
                    </td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;" class="text-center"
                        width="10%"><strong><?=number_format($line_tax_amount, 2)?></strong>
                    </td>
                </tr>
                <?php }
                $line_total += $line_tax_amount;
                }?>
                <tr class="th-head">
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right"
                        width="80%"><strong>Total Price of Services:</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important; text-align: right;"
                        class="text-right"
                        width="10%"><strong>$</strong></td>
                    <td style="border-right: none; border-bottom: 1px solid #ddd !important;"
                        class="text-center" width="10%"><strong><?= number_format($line_total,2)  ?></strong>
                    </td>
                </tr>
                <tr style="background-color:<?= $setting_details->invoice_color  ?>!important;color: #fff;"
                    class="th-head">
                    <td style="text-align: right;" class="text-right" width="80%"><strong>Total Price:</strong></td>
                    <td style="text-align: right;" class="text-right" width="10%"><strong>$</strong></td>
                    <td class="text-center" width="10%"><strong><?= number_format($line_total,2)  ?></strong>
                    </td>
                </tr>
                <?php }?>
                <!-- END COUPON SECTION -->
            </table>


            <?php
            if ($setting_details->tearm_condition != '') {?>

                <table width="100%" class="main_table">
                    <tr>
                        <td class="text-center"><b>Terms & Conditions</b></td>
                    </tr>
                    <?php
                    $term_conditions_array = explode("\r\n",$setting_details->tearm_condition);
                     //echo print_r($term_conditions_array);
                    if (count($term_conditions_array) > 1 ) {
                        foreach ($term_conditions_array as $line){
                            ?>

                            <tr>
                                <td style="min-height: 2rem "> <?php echo ($line != '')?$line:'<br>';?></td>
                            </tr>

                        <?php }
                    } else { ?>
                        <tr><td><?=str_replace(array("\r\n", "\r", "\n"), "<br />", $setting_details->tearm_condition)?></td></tr>

                    <?php }?>

                </table>
            <?php }?>


            <table width="100%" class="main_table">
                <tr>
                    <td class="text-left" style="font-size: 14px"><strong
                            style="color:<?=$setting_details->invoice_color?> ;"><b>Sign here to accept estimate and
                                agree to terms & conditions:</b> </strong>________________________________</td>
                </tr>
            </table>

            <table width="100%" class="main_table" style="text-align: center">
                <tr>
                    <td class="text-center">Do you have questions concerning this estimate?</td>
                </tr>
                <tr>
                    <td class="text-center">
                        Call <?= ucfirst($user_details->user_first_name).' '.ucfirst($user_details->user_last_name) ?>
                        at <?php echo formatPhoneNum($user_details->phone)   ?></td>
                </tr>
            </table>
            <hr>

            <table width="100%" style="text-align: center;page-break-inside: avoid">
                <tr>
                    <td class="text-center"><?=print_r($setting_details->company_address)?></td>
                </tr>
                <tr >
                    <td class="text-center">E-mail: <?=$setting_details->company_email?>
                        <?=$setting_details->web_address != '' ? 'Web: ' . $setting_details->web_address : '';?></td>
                </tr>
            </table>

        </div>


    </body>

</html>