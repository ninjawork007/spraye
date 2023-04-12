<?php
if (!$this->session->userdata('email')) {     
    return redirect("customers/auth");
}
?>

<!DOCTYPE html>
<html lang="en">
<?= $temelate_head ?>

<body class="">
    <!-- Main navbar -->
    <?= $temelate_header ?>
    <!-- /main navbar -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main sidebar -->
            <!--<?= $temelate_sidebar ?> -->
            <!-- /main sidebar -->


            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Content area -->
                <?= $page_content ?>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

    <script type="text/javascript">
        $(".alert-success").fadeTo(5000, 500).slideUp(500, function() {
            $(".alert-success").slideUp(500);
            $(this).parent().parent().removeClass("techmessage");
        });

        $(".alert-danger").fadeTo(5000, 500).slideUp(500, function() {
            $(".alert-danger").slideUp(500);
            $(this).parent().removeClass("techmessage");
        });
    </script>
    <!-- <script src="//api.usersnap.com/load/74066d19-3d3d-475c-be6c-77be42aed183.js" async></script> -->

    <script>
        window.fwSettings = {
            'widget_id': 47000003753
        };
        ! function() {
            if ("function" != typeof window.FreshworksWidget) {
                var n = function() {
                    n.q.push(arguments)
                };
                n.q = [], window.FreshworksWidget = n
            }
        }()
    </script>

    <script type='text/javascript' src='https://widget.freshworks.com/widgets/47000003753.js' async defer></script>

</body>

</html>