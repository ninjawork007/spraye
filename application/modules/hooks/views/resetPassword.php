<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Reset Password</title>

        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="<?= base_url('assets/') ?>css/animate.css">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="<?= base_url('assets/') ?>css/style.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <style>
            .error{
                color: red;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <!--            <div class="top">
                            <h1 id="title" class="hidden"><span id="logo">Daily <span>UI</span></span></h1>
                        </div>-->
            <form action="" method="post" autocomplete="off">
                <div class="login-box animated fadeInUp">
                    <div class="box-header">
                        <h2>Reset Password</h2>
                    </div>
                    <label for="username">New Password</label>
                    <br/>
                    <input type="hidden" value="<?=$customer->customer_id?>" name="customer_id" id="username">
                    <input type="password" name="password" id="username">
                    <br/>
                    <?php echo form_error('password'); ?>
                    <br/>
                    <label for="password">Confirm New Password</label>
                    <br/>
                    <input type="password" name="cpassword" id="password">
                    <br/>
                    <?php echo form_error('cpassword'); ?>
                    <br/>
                    <br/>
                    <button type="submit">Continue</button>
                    <br/>
                    
                    <!--<a href="#"><p class="small">Forgot your password?</p></a>-->
                </div>
            </form>
        </div>
    </body>

    <script>
        $(document).ready(function () {
            $('#logo').addClass('animated fadeInDown');
            $("input:text:visible:first").focus();
        });
        $('#username').focus(function () {
            $('label[for="username"]').addClass('selected');
        });
        $('#username').blur(function () {
            $('label[for="username"]').removeClass('selected');
        });
        $('#password').focus(function () {
            $('label[for="password"]').addClass('selected');
        });
        $('#password').blur(function () {
            $('label[for="password"]').removeClass('selected');
        });
    </script>

</html>