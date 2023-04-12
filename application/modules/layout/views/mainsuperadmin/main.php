<!DOCTYPE html>
<html lang="en">
    <?= $temelate_head ?>
    <body class="" >

        <!-- Main navbar -->
        <?= $temelate_header ?> 
        <!-- /main navbar -->


        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <!-- Main sidebar -->
                <?= $temelate_sidebar ?>
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
            $(".alert-success").fadeTo(5000, 500).slideUp(500, function(){
                $(".alert-success").slideUp(500);
            });
        
           $(".alert-danger").fadeTo(5000, 500).slideUp(500, function(){
                $(".alert-danger").slideUp(500);
            }); 
        </script>   
		
		<script type="text/javascript">
  
function productList() {
     $.ajax({
                 type: "GET",
            url: "<?= base_url()?>"+"admin/productListAjax",
                 success: function (response) {
                    $("#product_list").html(response);
                    reinitMultiselect();

                 }
             });   
}


function propertyList() {
     $.ajax({
                 type: "GET",
            url: "<?= base_url()?>"+"admin/propertyListAjax",
                 success: function (response) {
                    $("#property_list").html(response);
                    reinitMultiselect();

                 }
             });   
}


function customerList() {
     $.ajax({
                 type: "GET",
            url: "<?= base_url()?>"+"admin/customerListAjax",
                 success: function (response) {
                    $("#customer_list").html(response);
                    reinitMultiselect();

                 }
             });   
}

function programList(){

    $.ajax({
                 type: "GET",
            url: "<?= base_url()?>"+"admin/programListAjax",
                 success: function (response) {
                    $("#program_list").html(response);
                    reinitMultiselect();

                 }
             });  

}

function viewJobList(){

    $.ajax({
                 type: "GET",
            url: "<?= base_url()?>"+"admin/job/jobListAjax",
                 success: function (response) {
                    $("#job_list").html(response);
                    //alert(response);
                    reinitMultiselect();

                 }
             });  

}



 function getServiceAreaList() {
       $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>admin/setting/getServiceArea',
              success: function (data)
              {
               $("#loading").css("display","none");
             // alert(data);
                $(".serviceareadiv").html(data);
                $('.datatable-basic').DataTable();

              }
          });

 }


 function getServiceAreaOption(){

      $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>admin/getServiceAreaOption',
              success: function (data)
              {
               $("#loading").css("display","none");
              // alert(data);
                $("#serviceareaoption").html(data);

              }
          });

 }

</script>

<script type="text/javascript">
function reinitMultiselect() {

$(".multiselect-select-all-filtering").multiselect('destroy');
    $('.multiselect-select-all-filtering').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        templates: {
            filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
        },
        onSelectAll: function() {
            $.uniform.update();
        }
    });
}
</script>
		<!--
Usersnap Classic Widget
Add this code before the closing <body> tag.
-->
<!-- <script src="//api.usersnap.com/load/74066d19-3d3d-475c-be6c-77be42aed183.js" async></script> -->

<script>
        window.fwSettings={
        'widget_id':47000003753
        };
        !function(){if("function"!=typeof window.FreshworksWidget){var n=function(){n.q.push(arguments)};n.q=[],window.FreshworksWidget=n}}() 
      </script>

      <script type='text/javascript' src='https://widget.freshworks.com/widgets/47000003753.js' async defer></script>

</body>
</html>
