<?php 
  if (!$this->session->userdata('email')) {
            return redirect('customers/auth');
    }
 ?>

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

                <!-- Main content -->
                <div class="content-wrapper">

                    <!-- Content area --> 
                    <?= $page_content ?>
                    <!-- /content area -->

                       <!-- <div class="footer cht-opn"><h5 class="qun"><span class="chtn"><a href="" data-toggle="modal" data-target="#help_message" >Have a question?</a></span></h5> -->
                         <!-- &copy; 2015. <a href="#">Limitless Web App Kit</a> by <a href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a> -->
                       <!-- </div> -->
                   </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

        </div>
        <!-- /page container -->



<div id="help_message" class="modal fade">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Contact Us</h6>
         </div>
         <form action="<?= base_url('customers/HelpMessagesend') ?>" name= "HelpMessageForm" method="post" >
            <div class="modal-body">
               <div class="form-group">
                  <div class="row">                    
                     <div class="col-sm-12">
                        <label>To</label>
                        <input type="text"  name="to_email" class="form-control" readonly="" value="support@spraye.io">
                     </div>
                  </div>
               </div>

                <div class="form-group">
                  <div class="row">                    
                     <div class="col-sm-12">
                        <label>Message</label>
                        <textarea class="form-control" name="message"></textarea>
                     </div>
                  </div>
               </div>
             
             
               <div class="modal-footer">
                  <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Save</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>





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
            url: "<?= base_url()?>"+"customers/productListAjax",
                 success: function (response) {
                    $("#product_list").html(response);
                    reinitMultiselect();

                 }
             });   
}


function propertyList(proertyPriceOverRide='',selectedProperties='',current_added_id='') {
     $.ajax({
                 type: "POST",
                 url: "<?= base_url()?>"+"customers/propertyListAjax",
                 data : {proertyPriceOverRide : proertyPriceOverRide , selectedProperties :  selectedProperties,current_added_id : current_added_id },
                 success: function (response) {
                    $("#property_list").html(response);
                    reinitMultiselect();

                 }
             });   
}

function propertyListSelectedByCustomer(customer_id) {
     $.ajax({
            type: "GET",
            url: "<?= base_url()?>"+"customers/propertyListAjaxSelctedByCustomer/"+customer_id,
                 success: function (response) {
                    $("#property_list").html(response);
                    reinitMultiselect();

                 }
             });   
}



function customerList() {
     $.ajax({
                 type: "GET",
            url: "<?= base_url()?>"+"customers/customerListAjax",
                 success: function (response) {
                    $("#customer_list").html(response);
                    reinitMultiselect();

                 }
             });   
}

function programList(programPriceOverRide=''){

    $.ajax({
                 type: "POST",
                 url: "<?= base_url()?>"+"customers/programListAjax",
                 data : {programPriceOverRide : programPriceOverRide},
                 success: function (response) {
                    $("#program_list").html(response);
                    reinitMultiselect();

                 }
             });  

}

function viewJobList(){

    $.ajax({
                 type: "GET",
            url: "<?= base_url()?>"+"customers/job/jobListAjax",
                 success: function (response) {
                    $("#job_list").html(response);
                    //alert(response);
                    reinitMultiselect();
                    // reinitOrderMultiselect();

                 }
             });  

}



 function getServiceAreaList() {
       $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>customers/setting/getServiceArea',
              success: function (data)
              {
               $("#loading").css("display","none");
                // alert(data);
                $(".serviceareadiv").html(data);
                $(".datatable-basic").dataTable().fnDestroy();
                $('.datatable-basic').DataTable();

                     // $('.datatable-colvis-state').DataTable({
                     //  buttons: [
                     //      {
                     //          extend: 'colvis',
                     //          text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                     //          className: 'btn bg-indigo-400 btn-icon'
                     //      }
                     //  ],
                     //  stateSave: true,
                     // });


               
              }
          });

 }

 function getCouponList() {
       $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>customers/setting/getCoupon',
              success: function (data)
              {
                  $("#loading").css("display","none");
                  $(".coupondiv").html(data);
                  $(".datatable-basic-coupon").dataTable().fnDestroy();
                  $('.datatable-basic-coupon').DataTable();
              }
          });

 }

  function getSalesTexAreaList() {
       $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>customers/setting/getSalesTaxArea',
              success: function (data)
              {
               $("#loading").css("display","none");
             // alert(data);
                $(".texareadiv").html(data);
                $(".datatable-basic2").dataTable().fnDestroy();
                $('.datatable-basic2').DataTable();

              }
          });

 }


 function getServiceAreaOption(){

      $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>customers/getServiceAreaOption',
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
        enableCaseInsensitiveFiltering: true,
        enableHTML : true,

        templates: {
            filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
        },

         onInitialized: function(select, container) {
           $(".styled, .multiselect-container input").uniform({ radioClass: 'checker'});
         },
        optionLabel: function(element) {
          if ($(element).attr('title')) {

                return $(element).html() + '<br><small class="text-muted">( ' + $(element).attr('title') + ' )</small>';

            }  else {
                return $(element).html();

            }
        },
         onSelectAll: function() {
            $.uniform.update();
         }
    });
  //  $('.multiselect-select-all-filtering').multiselect('refresh');

  
}
function reassignCheckboxAnTimePicker() {
      
      $(".styled, .multiselect-container input").uniform({
                radioClass: 'choice'
       });

       $('.clockpicker').clockpicker({

         default : 'now'
         
       });


}

function formatDate() {
    var d = new Date(),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

function formatTime() {
        var dt = new Date();
        var time = dt.getHours() + ":" + dt.getMinutes();
        return time;
}


</script>
<!-- 
<script src="//api.usersnap.com/load/74066d19-3d3d-475c-be6c-77be42aed183.js" async></script>

 -->

<script>
//   window.fwSettings={
//   'widget_id':47000003753
//   };
//   !function(){if("function"!=typeof window.FreshworksWidget){var n=function(){n.q.push(arguments)};n.q=[],window.FreshworksWidget=n}}() 
</script>

<!-- <script type='text/javascript' src='https://widget.freshworks.com/widgets/47000003753.js' async defer></script> -->

     

        </body>
        </html>

        
