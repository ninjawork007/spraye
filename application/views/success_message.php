<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPRAYE</title>

    <!-- Global stylesheets -->
    <link href="../../../../../fonts.googleapis.com/css1381.css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/admin') ?>/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/admin') ?>/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/admin') ?>/assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/admin') ?>/assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/admin') ?>/assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/admin') ?>/assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

   <!-- Theme JS files -->
    <script type="text/javascript" src="<?= base_url() ?>assets/signup/plugins/forms/wizards/steps.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/signup/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/signup/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/signup/core/libraries/jasny_bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/signup/plugins/forms/validation/validate.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/signup/plugins/extensions/cookie.js"></script>
<style type="text/css">
    .register-form
{

  background-color: #eef5f8!important;  
}
.col-md-6.register-form {
    box-shadow: 1px 6px 1px 0 rgba(103, 151, 255, .11), 0 12px 16px 0 #9e9e9e75;
}
.margin-container {
    margin-bottom: 60px;
}
h2.panel-title, .panel-heading p, h3.panel-title{
    text-align: center;
}
.panel-heading a{
    padding-left: 71px;
}
label {
    font-size: 16px;
}
label#is_count-error {
    float: right;
    padding-right: 42px;
    margin-top: 0;
}
.wizard>.steps>ul>li {
    display: none;
}
.content {
    padding: 11px !important;
    margin-top: 15px;
}

h3.subcription{
    font-size: 28px;
    margin: 25px 0;
    color: #607D8B;
}
#btn-login {
    background: #37c9c9;
    color: #fff;
    font-size: 19px;
}
</style>
</head>
    <body class="login-container" style="overflow-x: hidden; font-family: Roboto, sans-serif !important;">

        <!-- Page container -->
        <div class="page-container" style="min-height:335px;">
            <!-- Page content -->
            <div class="page-content">
                <!-- Main content -->
                <div class="content-wrapper">

                    <!-- Content area -->
                    <div class="content margin-container">
                        <div class="">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 register-form" style="min-height: 497px;padding: 70px;">
                                <!-- Wizard with validation -->

                                <div class="login-form"><center>
                            
                                <?php 
                                 if (!empty($setting_details->company_logo)) { ?>

                                    <img height="50"  src="<?= CLOUDFRONT_URL.'uploads/company_logo/'.$setting_details->company_logo ?>">                                                  
                                
                                <?php }   ?>


                                </center>
                            </div>



                                <div class="panel panel-white register-form text-center">

                                    <h2 class="no-margin text-semibold"><?= $subject ?>!</h2>
                                    
                                    <h3 class="content-group-sm"><?= $description ?></h3>


                                    <?php 
                                        if ($status==200) {
                                            // print_r($basys_details);
                                    ?>      



                                    <?php  if (($basys_details && $estimate_details->status!=3) || ($cardconnect_details && $estimate_details->status!=3)) { ?>

                                        <tr class="button-tr" >
                                                <td></td>  
                                                <td></td>  
                                                <!-- <td class="text-left" colspan="4" ><button class="btn btn-success"><a href="<?= base_url('Welcome/estimatePayment/').base64_encode($estimate_details->estimate_id)  ?>" target="_blank" >Pay Now</a></button></td> -->
                                                <td class="text-right"></td>         
                                             
                                          </tr>    




                                    <?php } elseif ($setting_details->pay_now_btn==1 && $setting_details->pay_now_btn_link!='' && $estimate_details->status!=3) { ?>
                                           <tr class="button-tr" >
                                                <td></td>  
                                                <td></td>  
                                                <td class="text-left" colspan="4" ><button class="btn btn-success"><a href="<?= $setting_details->pay_now_btn_link  ?>" target="_blank" >Pay Now</a></button></td>
                                                <td class="text-right"></td>         
                                             
                                          </tr>    
                                    <?php } ?>




                                    <?php } ?>
                                  

                                </div>
                                 
                                <!-- /wizard with validation -->
                            </div>
                            <div class="col-md-3">
                                
                            </div>
                        </div>
                    </div>
                    <!-- /content area -->
                </div>
                <!-- /main content -->
            </div>
            <!-- /page content -->
        </div>
        <!-- /page container -->
    </body>
</html>
