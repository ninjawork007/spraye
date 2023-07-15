<!DOCTYPE html>
<html lang="en">

    <meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SPRAYE</title>

        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Global stylesheets -->
        <!-- <link href="fonts.googleapis.com/css1381.css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> -->
        <link href="<?=base_url('assets/admin')?>/assets/css/icons/icomoon/styles.css" rel="stylesheet"
            type="text/css">
        <link href="<?=base_url('assets/admin')?>/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url('assets/admin')?>/assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url('assets/admin')?>/assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url('assets/admin')?>/assets/css/colors.css" rel="stylesheet" type="text/css">
        <!-- /global stylesheets -->

        <!-- Core JS files -->
        <script src="https://code.jquery.com/jquery-3.4.1.js"
            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>

        <!-- Add tokenizer.js file to head -->
        <script language="javascript" src="<?=BASYS_URL?>tokenizer/tokenizer.js"></script>

        <script src="<?=base_url()?>/assets/popup/js/sweetalert2.all.js"></script>

        <script language="JavaScript">
        window.addEventListener('message', function(event) {
            var token = JSON.parse(event.data);
            var mytoken = document.getElementById('cc_token');
            mytoken.value = token.message;
        });
        </script>

        <!-- /theme JS files -->
        <style type="text/css">
        .login_new {
            background: #01669a;
            color: #fff;
            margin-bottom: 0px !important;
            border: 0 !important;
            border-radius: 0PX !important;
        }


        .error {
            color: rgb(221, 51, 51) !important;
            margin-bottom: 0px;
        }

        .login-container .page-container {
            padding-top: 25px !important;
            position: static;
        }

        a {
            font-weight: bold;
        }

        #loading {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            position: fixed;
            display: none;
            opacity: 0.7;
            background-color: #fff;
            z-index: 9999;
            text-align: center;
        }

        #loading-image {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 10%;
            z-index: 100;
        }

        .text-center.no-margin {
            display: block;
        }

        .panel.panel-body.login-form {
            border: 1px solid #ccc !important;
        }
        </style>


    </head>

    <body class="login-container" style="overflow: hidden;">

        <div id="loading">
            <img id="loading-image" src="<?=base_url()?>/assets/loader.gif" /> <!-- Loading Image -->
        </div>



        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <!-- Main content -->
                <div class="content-wrapper">

                    <!-- Content area -->
                    <div class="content pb-20">
                        <!-- Form with validation -->
                        <div class="login-form">
                            <center>

                                <!-- <?php
if (!empty($setting_details->company_logo)) {?>

                                <img height="50"
                                    src="<?=CLOUDFRONT_URL . 'uploads/company_logo/' . $setting_details->company_logo?>">

                                <?php } else {?> -->
                                <img src="<?=base_url()?>/assets/admin/image/Spraye_Logo_Web_White.png " alt="">
                                <!-- <?php }?> -->



                            </center>
                        </div>
                        <div class="panel panel-body login-form login_new"
                            style="background: <?=$setting_details->invoice_color?>;color: #fff;padding: 10px">Enter
                            Payment Details</div>

                        <div class="panel panel-body login-form">
                            <?php
// print_r($invoice_details);
if ($invoice_details) {
    if ($invoice_details->payment_status != 2) {

        if ($cardconnect_details) {
            ?>



                            <div class="form-group card-form">
                                <form name="tokenform" id="tokenform">

                                    <iframe id="tokenFrame" name="tokenFrame"
                                        src="<?=CARDCONNECT_TOKEN_URL?>?usecvv=true&useexpiry=true&css=input%5Bname%3Dccnumfield%5D%7Bwidth%3A252px%3B%7D
                                        input%7Bborder-radius%3A4px%3Bborder%3A1px%20solid%20%23dcdee2%3Bcolor%3A%23223666%3Boutline%3A0%3Bmargin-bottom%3A8px%3B%7D
                                        select%7Bborder-radius%3A4px%3Bborder%3A1px%20solid%20%23dcdee2%3Bcolor%3A%23223666%3Boutline%3A0%3Bmargin-bottom%3A8px%3B
                                        width%3A64px%3B%7D&placeholder=0000000000000000&placeholdercvv=000&maskfirsttwo=true"
                                        frameborder="0" scrolling="no"></iframe>
                                    <input type="hidden" name="cc_token" id="cc_token" />

                                    <!-- <span name="exp" class="expiration">
                                        <input type="number" min="01" max="12" step="1" name="month" placeholder="MM" maxlength="2" size="2" />
                                        <input type="number" min="20" max="60" step="1" name="year" placeholder="YY" maxlength="2" size="2" />
                                    </span> -->
                                    <input type="hidden" name="merchid" id="merchid"
                                        value="<?php echo $cardconnect_details->merchant_id ?>">
                                    <input type="hidden" name="invoiceid" id="invoiceid"
                                        value="<?php echo $invoice_details->invoice_id ?>">
                                </form>
                            </div>
                            <div class="form-group login-options">
                                <div class="row">
                                    <?php

            // $total_tax_amount = 0;

            // if ($tax_details) {

            //     $total_tax_amount = array_sum(array_column($tax_details, 'tax_amount'));
            // }

            // $convenience_fee = $setting_details->convenience_fee * (($invoice_details->cost + $total_tax_amount) - $invoice_details->partial_payment) / 100;
            // $total_payment_final = ($actual_total_cost_miunus_partial + $convenience_fee) - $invoice_details->partial_payment;

            ?>
                                    <span class="text-center no-margin">Total Amount :
                                        <b>$<?=number_format($total_payment_final, 2);?></b> 
                                        <?=$setting_details->company_currency?></span>

                                </div>
                            </div>


                            <div class="form-group login-options">
                                <div class="row">


                                    <div class="col-sm-12 text-right" style="float:right; ">
                                        <input onclick="$('#tokenform').submit()" type="submit" id="btn-login"
                                            class="btn  btn-block " style="background-color: #47a447;color: #fff"
                                            value="Pay">
                                    </div>

                                </div>
                            </div>


                            <div class="form-group login-options" style="text-align: center;">
                                <div class="row">
                                    <label for="fname"></label>
                                    <div class="icon-container">
                                        <i class="fa fa-cc-visa" style="color:navy;"></i>
                                        <i class="fa fa-cc-amex" style="color:blue;"></i>
                                        <i class="fa fa-cc-mastercard" style="color:red;"></i>
                                        <i class="fa fa-cc-discover" style="color:orange;"></i>
                                    </div>

                                </div>
                            </div>


                            <?php if ($convenience_fee != 0) {?>

                            <span class="text-center no-margin">Please note there will be a convenience fee of
                                $<?=number_format($convenience_fee, 2)?> for making a payment online.</span>

                            <?php }?>




                            <?php } else {

            echo '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Online </strong> payment not integrated. Please contact to support</div>';
        }
    } else {
        echo '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong> already paid</div>';
    }
} else {
    echo '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong> details not found</div>';
}

?>




                        </div>
                        <!-- /content area -->

                    </div>
                    <!-- /main content -->

                </div>
                <!-- /page content -->

            </div>
            <!-- /page container -->
            <script type="text/javascript"
                src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
            <script type="text/javascript" src="<?=base_url()?>/assets/validation/form-validation.js"></script>

            <script src="<?=base_url()?>assets/validation/form-validation.js"></script>
            <script type="text/javascript">
            $(".alert-success").fadeTo(5000, 500).slideUp(500, function() {
                $(".alert-success").slideUp(500);
            });

            $(".alert-danger").fadeTo(5000, 500).slideUp(500, function() {
                $(".alert-danger").slideUp(500);
            });
            </script>
            <!-- <script type="text/javascript">
                // var expir = ""
                // var ation = ""
                // $('#ccexpirymonth').change(function(e){
                //     if($(this).val() != "--"){
                //         if($(this).val() > 0 && $(this).val() < 10){
                //         var str = $(this).val().toString();
                //         expir = "0" + str;
                //         console.log('Less than 10: ' + expir);
                //     } else if ($(this).val() >= 10) {
                //         expir = $(this).val().toString();
                //         console.log(expir);
                //     } else {
                //         console.log("Expire Month = " + $(this).val());
                //     }
                //     }
                // });

                $('#ccexpiryyear').keyup(function(e){
                    if($(this).val() != "--"){
                        var stry = $(this).val().toString();
                        ation = `${stry[2]}${stry[3]}`;
                        console.log(ation);
                    }
                });

                $('#exp').val(`${expir}${ation}`);
            </script> -->

            <script>
            $('#tokenform').on('submit', function(e) {
                e.preventDefault();

                $("#loading").css("display", "block");
                setTimeout(function() {
                    $.ajax({
                        url: '<?php echo base_url('welcome/ccPaymentProcess/clover'); ?>',
                        type: 'POST',
                        data: {
                            requestData: {
                                account: $('#cc_token').val(),
                                merchid: $('#merchid').val(),
                            },
                            invoice_id: $('#invoiceid').val(),
                            merchid: $('#merchid').val(),
                        },
                        dataType: "JSON",
                        success: function(response) {
                            console.log(JSON.stringify(response));

                            $("#loading").css("display", "none");

                            if (response.status == 200) {

                                swal(
                                    'Thank You!',
                                    response.msg,
                                    'success'
                                );
                                setInterval(function() {

                                    window.location.href =
                                        "<?=base_url('welcome/paymentSuccess/success') . $setting_details->company_id?>";

                                }, 2000);


                            } else if (response.status == 400) {

                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: response.msg
                                })

                            } else {

                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: response.msg
                                })

                            }
                        },
                        error: function(response) {
                            console.log(response);
                            $("#loading").css("display", "none");

                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            })

                        }
                    });
                }, 1000);




            });
            </script>
    </body>
</html>