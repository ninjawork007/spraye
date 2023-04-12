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
        .text-primary {
            color:#2196F3;
        }
        .text-success {
            color:#4CAF50;
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
        .table>tbody>tr>td {
            padding: 1px!important ;
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
        .alert-flag-red{
            font-weight: bolder;
            color: red!important;
        }
        .alert-flag-blue{
            font-weight: bolder;
            color: blue!important;
        }
        .alert-flag-green{
            font-weight: bolder;
            color: darkgreen!important;
        }
    </style>
    <link href="<?= base_url('assets/admin/assets/css/bootstrap.min.pdf.css') ?>" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</head>
<body>
<!--get unique route count -->


<?php
$route_ids = array();
$total_sq_feet_by_type = array();
$total_sq_feet = 0;
$total_sq_feet_front = 0;
$total_sq_feet_back = 0;
$service_detail_array = array();
$properties = array();
$propertiesandservice = array();
$total_stops = 0;
$customers_details = array();

//var_dump($route_results);
foreach($route_results as $k=>$route){
    if (!in_array( array(
        "name" => $route['first_name'].' '.$route['last_name'],
        "phone"=> $route['phone'],
        "pre_service_notification" => $route['pre_service_notification']
        ), $customers_details)){
            array_push($customers_details,
                array(
                    "name" => $route['first_name'].' '.$route['last_name'],
                    "phone"=> $route['phone'],
                    "pre_service_notification" => $route['pre_service_notification']
                )
            );
        }


    if(isset($route['route_id'])){
        if(is_array($route_ids) && !in_array($route['route_id'],$route_ids)){
            $route_ids[$route['route_id']][]=$route;
        }
    }
    if (!isset($route['yard_square_feet']))
        //$total_sq_feet_by_type[$route['yard_square_feet']];

        $total_sq_feet_front += $route['front_yard_square_feet'];
    $total_sq_feet_back += $route['back_yard_square_feet'];
    if (!in_array($route['property_address'],$properties)){
        array_push($properties, $route['property_address']);
        $total_stops++;

    }
    $total_sq_feet += $route['yard_square_feet'];
    //if (!in_array($route['property_address'].$route['service_name'],$propertiesandservice)){
    if (!isset($service_detail_array[$route['service_name']])) {
        array_push($propertiesandservice, $route['property_address'].$route['service_name']);
        $service_detail_array[$route['service_name']] =
            array("properties"=> 1,
                "yard_square_feet" => (isset($route['yard_square_feet']))?$route['yard_square_feet']:0,
                "front_yard_square_feet"=> (isset($route['front_yard_square_feet']))?$route['front_yard_square_feet']:0,
                "back_yard_square_feet"=> (isset($route['back_yard_square_feet']))?$route['back_yard_square_feet']:0,
                "products" => $route['product_used']
            );
    } else {
        $service_detail_array[$route['service_name']]['properties'] += 1;
        $service_detail_array[$route['service_name']]['yard_square_feet'] += (isset($route['yard_square_feet']))?$route['yard_square_feet']:0;
        $service_detail_array[$route['service_name']]['front_yard_square_feet'] += (isset($route['front_yard_square_feet']))?$route['front_yard_square_feet']:0;
        $service_detail_array[$route['service_name']]['back_yard_square_feet'] += (isset($route['front_yard_square_feet']))?$route['front_yard_square_feet']:0;
//        foreach ($route['product_used'] as $prd_used){
//            array_push($service_detail_array[$route['service_name']]['products'], $prd_used);
//
//        }
    }




}

//echo '<br><br>';
//echo print_r($service_detail_array);

// Cover Sheet
$property_address_array = array();

?>
<!--<div class="container main-container" style="page-break-after:always;">
    <table width="100%" class="table table-borderless" style="margin: 0px!important; padding:2px!important;">
        <tr>
            <td colspan="2"  ><span class='default-font-color'>Technician Name: </span><strong><?php /*echo $route_results[0]['user_first_name'].' '.$route_results[0]['user_last_name'] */?></strong></td>

            <td colspan="2"  text-align="left"><span class='default-font-color'>Route Name: </span> <?/*= $route_results[0]['route_name']*/?> </td>
            <td colspan="2"  text-align="left"><span class='default-font-color' >Date: </span><?/*=  date("m/d/Y" ,strtotime( $route_results[0]['date']))*/?></td>

        </tr>
        <tr>
            <td colspan="2"> <span class='default-font-color' >Number of stops:  </span> <?/*= $total_stops */?></td>
            <td colspan="4" ><span class='default-font-color' >Route Notes: </span> <?/*= $route_results[0]['route_note'] */?>  </td>



        </tr>
    </table>-->
    <?php
    $products_details = array();
    foreach($service_detail_array as $service => $route_details){
        if(is_array($route_details)){
            $footerflag = count($route_details)-1;
            // foreach($route_details as $key=>$route){
            //var_dump($route_details);
            ?>

            <!--route data-->
           <!-- <table width="100%" class="table table-bordered border-primary" style=" page-break-inside:avoid;">
                <tbody style=" border: 2px solid;">
                <tr style="border-top: 2px solid;" >
                    <td colspan="2" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid; " ><strong >SERVICE NAME(S): <?/*= isset($service) ? $service : "" */?></strong></td>
                    <td colspan="2" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;">Number of properties: <?/*= $route_details['properties']*/?></td>

                </tr>
                <tr>
                    <td colspan="1" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;">Total Sq Feet: <?/*= $route_details['yard_square_feet']*/?> </td>
                    <td colspan="1" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;">Total Front Yard Sq Feet: <?/*= (!empty($route_details['front_yard_square_feet']))?$route_details['front_yard_square_feet']:0; */?> </td>
                    <td colspan="2" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;">Total Back Yard Sq Feet: <?/*=  (!empty($route_details['back_yard_square_feet']))?$route_details['back_yard_square_feet']:0; */?> </td>
                </tr>
                <tr>
                    <td colspan="1" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;"> <strong>Products: </strong></td>
                    <td colspan="1" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;"> <?php
/*                        //echo print_r($route_details['products']);
                        echo '<strong>Product Name: </strong><br>';
                        foreach ($route_details['products'] as $product){

                            echo $product->product_name;
                            echo '<br>';
                            if (!isset($products_details[$product->product_name])) $products_details[$product->product_name] = array();

                        }
                        //print_r($route_details['products'])*/?></td>
                    <td colspan="1" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;"> <?php
/*                        echo '<strong>Total Amount to Use: </strong><br>';
                        foreach ($route_details['products'] as $product){
                            if (!empty($product->application_rate) && $product->application_rate != 0) {
                                //amountOfChemicalUse($product_details, $tech_apply, $yard_square_feet);
                                $re = 0;
                                if ($product->application_per == '1 Acre') {
                                    $re = $product->application_rate / 43560;
                                } else {
                                    $re = $product->application_rate / 1000;
                                }
                                //echo '$re: '.$re.' - yard_square_feet:'.$route_details['yard_square_feet']. '<br>';

                                $product_used =  number_format($re * $route_details['yard_square_feet'], 2);
                                $product_used =  floatval($product_used);


                                echo   $product_used . ' ' . $product->application_unit.'<br>';
                                $products_details[$product->product_name]['total_amount_used'] += $product_used;//($product->mixture_application_rate/1000) *$route_details['yard_square_feet'];
                                $products_details[$product->product_name]['total_amount_used_unit'] = $product->application_unit;
                            } else {
                                echo '';
                            }


                        }
                        //print_r($route_details['products'])*/?>
                    </td>
                    <td colspan="1" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;"> <?php
/*                        //echo print_r($route_details['products']);
                        echo '<strong>Total Mixture to Apply: </strong><br>';
                        foreach ($route_details['products'] as $product){
                            // echo $product->estimate_use.'<br>';

                            echo ($product->mixture_application_rate/1000) *$route_details['yard_square_feet'].' '.$product->mixture_application_unit. '<br>';

                            $products_details[$product->product_name]['total_mixture_used'] += ($product->mixture_application_rate/1000) *$route_details['yard_square_feet'];
                            $products_details[$product->product_name]['total_mixture_used_unit'] = $product->mixture_application_unit;

                        }

                        */?></td>


                </tr>
                </tbody>
            </table>
-->
            <!--end route data-->
            <!--page footer-->
            <?php


            //}
        }

    }

    ?>
 <!--   <table width="100%" class="table table-bordered border-primary" style="border-spacing: 5px 0; page-break-inside:avoid;">
        <tbody style=" border: 2px solid;">
        <tr style="border-top: 2px solid;" >
            <td colspan="3" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid; " ><strong >PRODUCTS SUMMARY: </strong></td>
        </tr>
        <tr>
            <td colspan="1" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;"> <?php
/*                //echo print_r($route_details['products']);
                echo '<strong>Product Name: </strong><br>';
                foreach ($products_details as $key => $product){
                    echo $key.'<br>';
                }
                */?></td>
            <td colspan="1" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;"> <?php
/*                echo '<strong>Total Amount to Use: </strong><br>';
                foreach ($products_details as $key => $product) {
                    echo $product['total_amount_used'] . ' ' . $product['total_amount_used_unit'] . '<br>';
                }
                */?>
            </td>
            <td colspan="1" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;"> <?php
/*                echo '<strong>Total Mixture to Apply: </strong><br>';
                foreach ($products_details as $key => $product) {
                    echo $product['total_mixture_used'] . ' ' . $product['total_mixture_used_unit'] . '<br>';
                }
                */?></td>


        </tr>
        </tbody>
    </table>
    <table width="100%" class="table table-bordered border-primary" style="border-spacing: 5px 0; page-break-inside:avoid;">
        <tbody style=" border: 2px solid;">
        <tr style="border-top: 2px solid;" >
            <td colspan="2" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid; " ><strong >CUSTOMERS SUMMARY: </strong></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;"> <?php
/*                //echo print_r($route_details['products']);
                echo '<strong>Customer Name: </strong><br>';
                foreach ($customers_details as $key => $customer){
                    echo $customer['name'];
                    //echo strpos($customer['pre_service_notification'], 'Call');
                    if (strpos($customer['pre_service_notification'], 'Call') != 0 || strpos($customer['pre_service_notification'], 'Text ETA') != 0){
                        echo ' - '. formatPhoneNum($customer['phone']);
                    }
                    echo '<br>';

                }
                */?></td>



        </tr>
        </tbody>
    </table>
</div>-->

<?php



//Rote Details
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
            $property_alerts = json_decode($route['property_alerts']);
            $customer_alerts = json_decode($route['customer_alerts']);
            if($key == 0){?>
                <!-- page header  -->
                <div class="container main-container" style="page-break-after:always;">
                <table width="100%" class="table table-borderless" style="margin: 0px!important; padding:2px!important;">
                    <tr>
                        <td colspan="2"  ><span class='default-font-color'>Technician Name: </span><strong><?php echo $route_details[0]['user_first_name'].' '.$route_details[0]['user_last_name'] ?></strong></td>

                        <td colspan="2"  text-align="left"><span class='default-font-color'>Route Name: </span> <?php echo
                            isset($route_details[0]['route_name']) && !empty($route_details[0]['route_name']) ? $route_details[0]['route_name'] : ""
                            ?> </td>
                        <td colspan="2"  text-align="left"><span class='default-font-color' >Date: </span><?=
                            isset($route_details[0]['date']) && !empty($route_details[0]['date']) ? date("m/d/Y" ,strtotime( $route_details[0]['date'])) : "" ?></td>

                    </tr>
                    <tr>
                        <td colspan="2" ><span class='default-font-color' >Route Notes: </span> <?=
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
                    <!-- Customer Name -->
                    <td colspan="3" style="padding:2px;font-size: 8px; ">
                        <?php if(isset($is_group_billing) && $is_group_billing == 1 && is_array($group_billing_details)){?>
                            <strong><?php echo $group_billing_details['first_name'].' '.$group_billing_details['last_name']; ?></strong>
                        <?php }else{ ?>
                            <strong><?php echo $route['first_name'].' '.$route['last_name'] ?></strong>
                        <?php } ?>
                    </td>
                    <!-- Property Address -->
                    <td colspan="3" valign="top" style="padding:2px;font-size: 8px;">
                        <?php if ($property_street_array && is_array($property_street_array) && !empty($property_street_array) ) {
                            if (trim($property_street_array['street'])!='') {
                                echo $property_street_array['street'].'. ';
                            }
                            echo $route['property_city'].', '.$route['property_state'].', '.$route['property_zip'];
                        } ?>
                    </td>
                    <!-- Notify by -->
                    <td colspan="3" style="padding:2px; font-size: 10px; text-transform: uppercase; text-align: left"><?= $route['pre_service_notification'] ?></td>
                    <!-- Telephone -->
                    <?php if(isset($is_group_billing) && $is_group_billing == 1 && is_array($group_billing_details)){?>
                        <td colspan="4" valign="top" text-align="left" style="font-size: 10px;">
                            <strong><span>M: </span></strong> <?=$group_billing_details['phone'] > 0 ? formatPhoneNum($group_billing_details['phone']):'' ?>
                        </td>
                    <?php }else{
                        //var_dump($route);?>
                        <td colspan="3"   text-align="left" style="padding:2px;font-size: 8px;">
                            <strong><span>M: </span></strong> <?=$route['mobile'] > 0 ? formatPhoneNum($route['mobile']).' / ': '' ?>
                            <strong><span>H: </span></strong><?=$route['home_phone'] > 0 ? formatPhoneNum($route['home_phone']) : '' ?>
                        </td>
                    <?php } ?>
                </tr>
                <tr style="color:#000;background-color:#D5DBDB">
                    <!-- Service Name(s) -->
                    <td colspan="2" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid; " ><strong >SERVICE NAME(S)</strong></td>
                    <td colspan="2"style="font-size: 8px;border-bottom: 1px #e8e9e9 solid; text-align:left;border-right: 1px #E8E9E9 solid;"  ><?= isset($route['service_name']) && !empty($route['service_name']) ? $route['service_name'] : "" ?></td>
                    <!-- GRASS TYPE (FRONT/BACK)-->
                    <td  colspan="2" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;"><strong>GRASS TYPE (FRONT/BACK)</strong></td>
                    <td  colspan="2" style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;text-align:left"  >
                        <?= isset($route['front_yard_grass'])  && !empty($route['front_yard_grass']) ? $route['front_yard_grass'] : "" ?> / <?= isset($route['back_yard_grass']) && !empty($route['back_yard_grass']) ? $route['back_yard_grass'] : "" ?>
                    </td>
                    <td colspan="4"  style="font-size: 8px;border-bottom: 1px #e8e9e9 solid;text-align:left" >
                        <span style="color: red; font-weight: 400" > <?php
                                if(!empty($property_alerts)){
                                    foreach ($property_alerts as $value) {
                                        echo $value->text.'<br> ';
                                    }
                                }
                                if(!empty($customer_alerts)){
                                    foreach ($customer_alerts as $value2) {
                                        echo $value2->text.'<br> ';
                                    }
                                }?>
                        </span></strong>
                    </td>
                </tr>
                <tr  style="color:#000;background-color:#D5DBDB">
                    <!-- Service Notes -->
                    <td colspan="3" style="font-size: 8px;border-top: 1px #e8e9e9 solid;border-bottom: 0!important; border-right: 1px #E8E9E9 solid;" ><strong>SERVICE NOTES</strong></td>
                    <!-- PROGRAM NOTES -->
                    <td colspan="3" style="font-size: 8px;border-top: 1px #e8e9e9 solid;border-bottom: 0!important; border-right: 1px #E8E9E9 solid;" ><strong>PROGRAM NOTES</strong></td>
                    <!-- LAWN SIZE (FRONT/BACK) -->
                    <td colspan="3" style="font-size: 8px;border-top: 1px #e8e9e9 solid;border-bottom: 0!important; " ><strong>LAWN SIZE (FRONT/BACK)</strong></td>
                    <td colspan="3" style="font-size: 8px;border-top: 1px #e8e9e9 solid;border-bottom: 0!important; " ><strong>SERVICE-SPECIFIC NOTE:</strong></td>



                </tr>
                <tr style="color:#000;background-color:#D5DBDB">
                    <!-- Service Notes -->
                    <td colspan="3" style="font-size: 8px; text-align:left;border-top: 0!important; border-right: 1px #E8E9E9 solid;"><?= isset($route['notes']) && !empty($route['notes']) ? $route['notes'] : "" ?></td>
                    <!-- PROGRAM NOTES -->
                    <td colspan="3" style="font-size: 8px; text-align:left;border-top: 0!important; border-right: 1px #E8E9E9 solid;" ><?= isset($route['program_notes']) && !empty($route['program_notes']) ? $route['program_notes'] : "" ?></td>
                    <!-- LAWN SIZE (FRONT/BACK) -->
                    <td colspan="3" style="text-align:left;font-size: 8px;border-top: 0!important; " >
                        <?= isset($route['yard_square_feet']) && !empty($route['yard_square_feet']) ? $route['yard_square_feet'] : "" ?> <span>sq.ft</span>
                    </td>
                    <td colspan="3"  style="text-align:left;font-size: 8px;border-top: 0!important; "  ><?php echo $route['service_specific_notes_customer'] ?></strong></td>
                </tr>

                <tr class="" style="" >
                    <td colspan="4" style="padding:2px; font-size: 8px;border-bottom: 0" ><strong>PROPERTY INFO</strong></td>
                    <td colspan="4" style="padding:2px; font-size: 8px;border-bottom: 0" ><strong>PRODUCTS USED</strong></td>
                    <td colspan="4" style="padding:2px; font-size: 8px;border-bottom: 0" ><strong>ADD NOTE/LAWN CONDITION/SKIP REASON</strong></td>
                </tr>

                <tr class="border-bottom" style="" >
                    <td colspan="4" style="font-size: 8px;border-top: 0!important;padding:2px "><?= $route['property_notes'] ?></td>
                    <td colspan="4" text-align="left" style=" border-top: 0;padding:2px; font-size: 8px;" >
                        <?php foreach($route['product_used'] as $p => $product){ ?>
                            <?= isset($product->product_name) && !empty($product->product_name) ? $product->product_name : "" ?>
                            <!-- (Estimated Mixture Used:)______________<strong> </strong></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    (Amount Applied:)______________ <br> -->
                            <!--USE FOR ESTIMATED USED WHEN FUNCTION IS CORRECTED AND WORKING RG12/13/21-->
                            <span style="font-size: 8px "> (Estimated Mixture Used:)<strong> <?= isset($product->estimate_use) && !empty($product->estimate_use) ? $product->estimate_use : "" ?> </strong></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    (Amount Applied:)______________ <br>
                            <!-- END OF ESTIMATED USED-->
                            <!--<span> (Estimated Mixture Used: &nbsp; <?= $route['amount_used'] ?> &nbsp;)</span> -->
                        <?php } ?>
                    </td>
                    <td colspan="4" style="border-top: 0;padding:2px"></td>
                </tr>

                <tr>
                    <td colspan="2"style="border-right: 0;padding:2px; font-size: 8px; text-align:center; font-size: 8px; " ><strong>START/COMPLETE TIME:</strong></td>
                    <td colspan="2"style="border-left: 0;text-align:left;border: 0!important">  </td>
                    <td colspan="2"style="border-right: 0;padding:2px; font-size: 8px; text-align:center "><strong>WIND SPEED: </strong></td>
                    <td colspan="2"style="border-left: 0;: 0;text-align:left "></td>
                    <td colspan="2"style="border-right: 0;padding:2px; font-size: 8px; text-align:center " ><strong>TEMPERATURE:</strong></td>
                    <td colspan="2"style="border-left: 0;border-right: 0;text-align:left"> </td>
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
<!--page footer-->