
<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPRAYE</title>

    <!-- Global stylesheets -->
    <link href="../../../../../fonts.googleapis.com/css1381.css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/customers') ?>/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/customers') ?>/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/customers') ?>/assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/customers') ?>/assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/customers') ?>/assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="<?= base_url('assets/customers') ?>/assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/customers') ?>/assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/customers') ?>/assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/customers') ?>/assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
 
    <script type="text/javascript" src="<?= base_url('assets/customers') ?>/assets/js/plugins/forms/styling/uniform.min.js"></script>

    <script type="text/javascript" src="<?= base_url('assets/customers') ?>/assets/js/core/app.js"></script>
   

    <!-- /theme JS files -->
    <style type="text/css">
      
.login_new{
    background: <?= $this->session->userdata('invoice_color') ?  $this->session->userdata('invoice_color') :'#01669a'?>;
    /* background: #01669a; */
    color: #fff;
    margin-bottom: 0px !important;
    border: 0 !important;
    border-radius: 0PX !important;
}
.login_bg_color{
    background: #37c9c9;
    color: #fff;
    font-weight: bold;
}

.login_bg_color:hover{
    background: #37c9c9;
    color: #fff;
    font-weight: bold;
}

.signup_bg_link{   
    color: #37c9c9;
    text-decoration: underline;
    font-weight: bold;    
}
.signup_bg_link:hover{   
    color: #37c9c9;
    text-decoration: underline;
    font-weight: bold;    
}
.form-group.login-options {
margin-bottom: 5px !important;
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

    </style>


</head>

<body class="login-container login-cover" style="overflow: hidden;">


    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Content area -->
                <div class="content pb-20">                             

                    <!-- Form with validation -->
                    <form action="<?= base_url('customers/auth') ?>" method="POST" name="customerslogin" class="form-validate">
                  <!--    <div class="panel panel-body login-form" style="background-color: transparent;border: 0;border-radius: 0;">
                        <a class="navbar-brand" href="index.html"><img src="<?= base_url() ?>/assets/admin/image/logo.png" alt="" height="25px"></a>
                        </div>  -->
                        <div class="login-form" style="max-width= 200px;">
                            
                            <center>
                                <img src="https://assets-dashboard.spraye.io/uploads/company_logo/<?= $this->session->userdata('company_logo') ?>" alt="" style=" max-width: 180px; ">
                            </center></div>
                            
                        <div class="panel panel-body login-form login_new">Customer Login</div>
      
                        <div class="panel panel-body login-form">


                          <!--   <div class="text-center">
                                <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                                <h5 class="content-group">Login to your administrator <small class="display-block">Your credentials</small></h5>
                            </div> -->
                              <b><?php  if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>


                                 
                            <div class="form-group has-feedback has-feedback-left">
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted">
                                    </i>
                                </div>
                                <input type="text" class="form-control" placeholder="Email" name="email" required="required" value="<?php if(isset($_COOKIE["loginId"])) { echo $_COOKIE["loginId"]; } ?>" >
                                
                            </div>

                             <!--  <div class="form-group">
                                  
                                  <select class="form-control" id="sel1" name="user_type">
                                    <option value="1">Administrator</option>
                                    <option value="0">Center Leaders</option>
                                  </select>
                                  
                                </div> -->

                            <div class="form-group has-feedback has-feedback-left">
                                 <div class="form-control-feedback">
                                    <i class="icon-lock2 text-muted"></i>
                                </div>
                                <input type="password" class="form-control" placeholder="Password" name="password" required="required" value="<?php if(isset($_COOKIE["loginPass"])) { echo $_COOKIE["loginPass"]; } ?>" >
                              
                            </div>

                            <div class="form-group login-options">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" class="styled" name="remember" <?php if(isset($_COOKIE["loginId"])) { ?> checked="checked" <?php } ?> >
                                            Remember Me
                                        </label>
                                    </div>

                                    <div class="col-sm-6 text-right" style="float:right;">
                                       <input type="submit" id="btn-login" class="btn login_bg_color btn-block" value="Login">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group login-options">
                            <div class="row">
                                
                                <div class="col-md-3 col-sm-12 text-left">
                                     <a href="<?= base_url('customers/auth/createpasswordCustomers') ?>" >Sign Up</a>
                                </div>

                                <div class="col-md-4 col-sm-12 text-left">
                                    <a href="mailto:<?=$this->session->userdata('company_email')?>"><span class="" style="">Contact Us</span></a>
                                </div>
                                <div class="col-md-5 col-sm-12 text-left">
                                     <a href="<?= base_url('customers/auth/forgotpasswordCustomers') ?>" >Forgot password?</a>
                                </div>


                               <!-- <div class="col-md-6 col-sm-12 text-right">
                                     <a href="<?= base_url('technician') ?>" >Login as Technician</a>
                                </div> -->
                                

                                </div>        
                                
                            </div>

                           <!--  <div class="form-group">
                                 <input type="submit" id="btn-login" class="btn bg-blue btn-block" value="Login">
                              
                            </div> -->

                            
                            
                        </div>
                    </form>
                    <!-- /form with validation -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
     <script type="text/javascript" src="<?= base_url() ?>/assets/validation/form-validation.js"></script>
     <script src="<?= base_url() ?>/assets/popup/js/sweetalert2.all.js"></script>
    
          <script src="<?= base_url() ?>assets/validation/form-validation.js"></script>
          <script type="text/javascript">
            $(".alert-success").fadeTo(5000, 500).slideUp(500, function(){
                $(".alert-success").slideUp(500);
            });
        
           $(".alert-danger").fadeTo(5000, 500).slideUp(500, function(){
                $(".alert-danger").slideUp(500);
            }); 
            queryString = window.location.search;
            if(queryString == "?estimate_accepted=true") {
                swal(
                    'Signed!',
                    'Estimate Signed and accepted.',
                    'success'
                );
            }
            if(queryString == "?estimate_rejected=true") {
                swal(
                    'Rejected!',
                    'You rejected the estimate from signwell.',
                    'error'
                );
            }

        </script>   

</body>


</html>
