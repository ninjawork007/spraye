<?php 
  if (!$this->session->userdata('email')) {
      $actual_link = $_SERVER[REQUEST_URI];
      $_SESSION['iniurl'] = $actual_link;
      return redirect('admin/auth');
    }
 ?>

<!DOCTYPE html>
<html lang="en">
    <?= $temelate_head ?>
<!--     <body class="sidebar-xs" onload="calcDistance()" >
 -->    <body class="" >

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

                       <div class="footer cht-opn"><h5 class="qun"><span class="chtn"><a href="" data-toggle="modal" data-target="#help_message" >Have a question?</a></span></h5>
                         <!-- &copy; 2015. <a href="#">Limitless Web App Kit</a> by <a href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a> -->
                       </div>
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
         <form action="<?= base_url('admin/HelpMessagesend') ?>" name= "HelpMessageForm" method="post" >
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
            url: "<?= base_url()?>"+"admin/productListAjax",
                 success: function (response) {
                    $("#product_list").html(response);
                    reinitMultiselect();

                 }
             });   
}


function propertyList(proertyPriceOverRide='',selectedProperties='',current_added_id='') {
     $.ajax({
                 type: "POST",
                 url: "<?= base_url()?>"+"admin/propertyListAjax",
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
            url: "<?= base_url()?>"+"admin/propertyListAjaxSelctedByCustomer/"+customer_id,
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

function programList(programPriceOverRide=''){

    $.ajax({
                 type: "POST",
                 url: "<?= base_url()?>"+"admin/programListAjax",
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
            url: "<?= base_url()?>"+"admin/job/jobListAjax",
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
              url: '<?php echo base_url(); ?>admin/setting/getServiceArea',
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
              url: '<?php echo base_url(); ?>admin/setting/getCoupon',
              success: function (data)
              {
                  $("#loading").css("display","none");
                  $(".coupondiv").html(data);
                  $(".datatable-basic-coupon").dataTable().fnDestroy();
                  $('.datatable-basic-coupon').DataTable();
              }
          });

 }
 function getTagList() {
       $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>admin/setting/getTags',
              success: function (data)
              {
                  $("#loading").css("display","none");
                  $(".tagdiv").html(data);
                  $(".datatable-basic-tag").dataTable().fnDestroy();
                  $('.datatable-basic-tag').DataTable();
              }
          });

 }
  function getSourceList() {
       $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>admin/setting/getSource',
              success: function (data)
              {
               $("#loading").css("display","none");
             // alert(data);
                $(".sourcediv").html(data);
                $(".datatable-source").dataTable().fnDestroy();
                $('.datatable-source').DataTable();

              }
          });

 }

  function getServiceTypeList() {
       $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>admin/setting/getServiceType',
              success: function (data)
              {
               $("#loading").css("display","none");
             // alert(data);
                $(".servicetypediv").html(data);
                $(".datatable-service-type").dataTable().fnDestroy();
                $('.datatable-service-type').DataTable();

              }
          });

 }

  function getCommissionList() {
       $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>admin/setting/getCommission',
              success: function (data)
              {
               $("#loading").css("display","none");
             // alert(data);
                $(".commissiondiv").html(data);
                $(".datatable-commission").dataTable().fnDestroy();
                $('.datatable-commission').DataTable();

              }
          });

 }

//   function getSecondaryCommissionList() {
//        $("#loading").css("display","block");
//           $.ajax({ 
//               type: 'POST',
//               url: '<?php echo base_url(); ?>admin/setting/getSecondaryCommission',
//               success: function (data)
//               {
//                $("#loading").css("display","none");
//              // alert(data);
//                 $(".s_commissiondiv").html(data);
//                 $(".datatable-secondary-commission").dataTable().fnDestroy();
//                 $('.datatable-secondary-commission').DataTable();

//               }
//           });

//  }

  function getBonusList() {
      $("#loading").css("display","block");
         $.ajax({ 
            type: 'POST',
            url: '<?php echo base_url(); ?>admin/setting/getBonus',
            success: function (data)
            {
            $("#loading").css("display","none");
            // alert(data);
               $(".bonusdiv").html(data);
               $(".datatable-bonus").dataTable().fnDestroy();
               $('.datatable-bonus').DataTable();

            }
         });

}  

//   function getSecondaryBonusList() {
//        $("#loading").css("display","block");
//           $.ajax({ 
//               type: 'POST',
//               url: '<?php echo base_url(); ?>admin/setting/getSecondaryBonus',
//               success: function (data)
//               {
//                $("#loading").css("display","none");
//              // alert(data);
//                 $(".s_bonusdiv").html(data);
//                 $(".datatable-secondary-bonus").dataTable().fnDestroy();
//                 $('.datatable-secondary-bonus').DataTable();

//               }
//           });

//  }

  function getSalesTexAreaList() {
       $("#loading").css("display","block");
          $.ajax({ 
              type: 'POST',
              url: '<?php echo base_url(); ?>admin/setting/getSalesTaxArea',
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
  window.fwSettings={
  'widget_id':47000003753
  };
  !function(){if("function"!=typeof window.FreshworksWidget){var n=function(){n.q.push(arguments)};n.q=[],window.FreshworksWidget=n}}() 
</script>

<script type='text/javascript' src='https://widget.freshworks.com/widgets/47000003753.js' async defer></script>

     

        </body>
        </html>

        
