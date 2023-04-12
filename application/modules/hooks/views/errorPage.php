<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>404 Page not found</title>

        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="<?= base_url('assets/') ?>css/animate.css">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="<?= base_url('assets/') ?>css/style.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>

    <body>
        <div class="container">
            <!--            <div class="top">
                            <h1 id="title" class="hidden"><span id="logo">Daily <span>UI</span></span></h1>
                        </div>-->
            <form action="" method="post" autocomplete="off">
                <div class="login-box animated fadeInUp">
                    <div class="box-header">
                        <h2>404 Page not found</h2>
                    </div>
                    <p>The page you are looking for does not exists.</p>
                    
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