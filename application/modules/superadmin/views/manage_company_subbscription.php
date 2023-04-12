<style type="text/css">
   .mypannel  {
   box-shadow: 0px 1px 7px 4px #cccccc4a;
   margin-bottom: 20px !important;
   }
   .panel-flat > .panel-heading > .panel-title {
   color: #256391;
   text-transform: uppercase;
   font-size: 20px;
   }
   .mypannel .panel-heading {
   padding: 18px 21px;
   padding-bottom: 30px;
   border-bottom: 1px solid #D9E7F0;
   }
   .mypannel > .panel-heading + .panel-body {
   padding-top: 20px;
   }
   .first_level {
   color: #999;
   }
   .second_level {
   padding-left: 5px;
   font-weight: 600;
   }
   .icon-checkmark3::before {
   content: "\ed6e";
   background-color: 
   #7ec812;
   padding: 1px;
   border-radius: 50%;
   color:
   #fff;
   }
   .icon-cross2::before {
   content: "\ed6a";
   background-color: 
   #ef5350;
   padding: 1px;
   border-radius: 50%;
   color:
   #fff;
   }
   .table-responsive {
   min-height : 0px;
   }
   .last_td {
   text-align: center;
   color:#256391;
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
   .rounded-circle{
   border-radius: 50%;
   }
   .content-body h5, .content-body h6{
   margin-top: 0;
   margin-bottom: 0;
   }
   .data-head{
   font-size: 14px;
   font-weight: 500;
   color: #000;
   padding-bottom: 10px ;
   }
   .btn-days {
   background: transparent;
   border: 2px solid #F44336;
   }
   .main-info .panel-body {
   padding-top: 45px !important;
   }
   .m-0{
   margin: 0 !important;
   }
   .icon-bar .card.card-body.text-center {
   padding-bottom: 25px;
   }
   .icon-bar span.text-success {
    font-size: 12px;
   }
a.nav-link a:after{
   background: #256391;
}
.nav-tabs.nav-tabs-bottom > li.active > a::after {
    background-color: #256391;

}
.active a {
    color: #256391 !important;
    font-weight: 600;
}
.graph-head {
    font-size: 22px !important;
    color: #256291;
    font-family: roboto;
}
.session-txt {
    font-size: 12px;
    color: #333;
}
</style>
<script src="http://demo.interface.club/limitless/demo/Template/global_assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<!-- <script src="http://demo.interface.club/limitless/demo/Template/global_assets/js/demo_charts/echarts/light/bars/columns_basic.js"></script>
 -->
<link href="<?= base_url('assets/card_payment/style.css') ?>" rel="stylesheet">
<script src="https://js.stripe.com/v2/"></script>
<script src="<?= base_url('assets/card_payment//creditCardValidator.js') ?>"></script>
<div class="content">
   <div id="loading" > 
      <img id="loading-image" src="<?= base_url() ?>assets/loader.gif"  /> <!-- Loading Image -->
   </div>
   <div class="panel panel-flat">
      <input type="hidden" id="company_id" value="<?= $company_details->company_id ?>">
      <div class="panel-body">
                  <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

         <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
               <form action="#">
                  <div class="panel panel-flat mypannel main-info">
                     <div class="panel-heading">
                        <h5 class="panel-title">MAIN INFORMATION</h5>
                        <div class="heading-elements">
                           <button class="btn btn-days"  >7+ DAYS LATE</button>
                        </div>
                     </div>
                     <div class="panel-body" style="padding-bottom: 55px;">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="card-body text-center card-img-top" >
                                 <div class="card-img-actions d-inline-block mb-3">

                                    <img class="img-fluid rounded-circle" src="<?= CLOUDFRONT_URL.'uploads/profile_image/'.$user_details->user_pic  ?>" width="170" height="170" alt="">
                                 </div>
                                 <h3 class="font-weight-semibold mb-0"><?= $user_details->user_first_name.' '.$user_details->user_last_name  ?><i class="icon-circle-small text-success"></i></h3>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="media-body content-body">
                                 <h6 class="media-title text-muted ">PLAN LEVEL</h6>
                                 <h6 class="media-title data-head"><?= !empty($subscription_details) ? ucfirst($subscription_details->type).'ly' : ''; ?></h6>
                                 <h5 class="media-title text-muted ">MAIL</h5>

                                 <h6 class="media-title data-head"> <?= strlen($company_details->company_email)> 20  ? substr($company_details->company_email,0,20).'.....' : $company_details->company_email   ?> </h6>
                                 
                                 <h5 class="media-title text-muted ">COMPANY NAME</h5>
                                 <h6 class="media-title data-head"><?= $company_details->company_name  ?></h6>
                                 <h5 class="media-title text-muted ">SUBSCRIPTION DATE</h5>
                                 <h6 class="media-title data-head"><?= !empty($subscription_details) ? date("d/m/Y", strtotime($subscription_details->subscription_created_at)) : ''; ?></h6>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
               <div class="panel panel-flat mypannel">
                  <div class="panel-heading">
                     <h5 class="panel-title">BILLING INFORMATION</h5>
                  </div>
                  <div class="panel-body">
                     <div class="payment-status"></div>
                     <!-- Payment form -->
                     <form action="#" method="POST" id="paymentFrm">
                        <div class="form-group">
                           <div class="row">
                              <div class="col-md-9" >
                                 <label>CARD NUMBER </label>
                                 <input type="text" class="form-control" placeholder="1234 5678 9012 3456" id="card_number" >
                              </div>
                              <div class="col-md-3" >
                                 <label>CVV </label>
                                 <input type="text" class="form-control" name="cvv" id="cvv" placeholder="469">
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label>EXPIRATION DATE </label>
                           <div class="row">
                              <div class="col-md-6" >
                                 <input type="text"  class="form-control" placeholder="MM" maxlength="2" id="expiry_month" class="">
                              </div>
                              <div class="col-md-6" >
                                 <input type="text" placeholder="YYYY" class="form-control"  maxlength="2" id="expiry_year" class="">
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label>CARD HOLDER NAME </label>
                           <input type="text" class="form-control" name="format-credit-card" placeholder="HALEN MONICA JONSON" id="name_on_card">
                        </div>

                     </form>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
               <div class="panel panel-flat mypannel">
                  <div class="panel-heading">
                     <h5 class="panel-title">ACTIVITY</h5>
                  </div>
                  <!-- <div class="panel-body">
                     <div class="card card-body">
                        <div class="row ">
                           <div class="col-md-6 col-sm-6 col-xs-12 icon-bar" style="border-right: 1px solid #ccc6;">
                              <div class="card card-body text-center">
                                 <div class="svg-center position-relative" id="progress_icon_two">
                                    <svg width="64" height="64"></svg>
                                    <i class="icon-calendar counter-icon" style="color:#256391; top: 26px;"></i>
                                 </div>
                                 <h6 class="m-0 text-muted">LAST ACCESS DATE</h6>
                                 <h4 class="m-0">3 / 12 / 19</h4>
                              </div>
                           </div>
                           <div class="col-md-6 col-sm-6 col-xs-12 icon-bar">
                              <div class="card card-body text-center">
                                 <div class="svg-center position-relative" id="progress_icon_two">
                                    <svg width="64" height="64"></svg>
                                    <i class="icon-alarm counter-icon" style="color:#256391; top: 26px;"></i>
                                 </div>
                                 <h6 class="m-0 text-muted">AT TIME</h6>
                                 <h4 class="m-0">8 : 15 : 24 pm</h4>
                              </div>
                           </div>
                        </div>
                        <div class="row" style="border-top: 1px solid #ccc6;">
                           <div class="col-md-6 col-sm-6 col-xs-12 icon-bar" style="border-right: 1px solid #ccc6;">
                              <div class="card card-body text-center">
                                 <div class="svg-center position-relative" id="progress_icon_two">
                                    <svg width="64" height="64"></svg>
                                    <i class="icon-stats-bars2 counter-icon" style="color:#256391; top: 26px;"></i>
                                 </div>
                                 <h6 class="m-0 text-muted">LOGINS LAST WEEK</h6>
                                 <h4 class="m-0">4 <span class="text-success">+23% <i class="icon-arrow-up5" style="vertical-align: bottom;"></i></span></h4>
                              </div>
                           </div>
                           <div class="col-md-6 col-sm-6 col-xs-12 icon-bar">
                              <div class="card card-body text-center">
                                 <div class="svg-center position-relative" id="progress_icon_two">
                                    <svg width="64" height="64"></svg>
                                    <i class="icon-stats-bars2 counter-icon" style="color:#256391; top: 26px;"></i>
                                 </div>
                                 <h6 class="m-0 text-muted">LOGINS LAST MONTH</h6>
                                 <h4 class="m-0">14 <span class="text-success">+23% <i class="icon-arrow-up5" style="vertical-align: bottom;"></i></span></h4>
                              </div>
                           </div>
                        </div>
                        <div class="row graph-row">
                           <div class="col-md-12">
                              <div class="card">
                                 <div class="card-body">
                                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified" >
                                       <li class="nav-item"><a href="#top-tab1" class="nav-link " data-toggle="tab">TODAY</a></li>
                                       <li class="nav-item"><a href="#top-tab2" class="nav-link" data-toggle="tab">YESTERDAY</a></li>
                                       <li class="nav-item"><a href="#top-tab3" class="nav-link" data-toggle="tab">WEEK</a></li>
                                       <li class="nav-item"><a href="#top-tab4" class="nav-link" data-toggle="tab">MONTH</a></li>
                                       <li class="nav-item active"><a href="#top-tab5" class="nav-link " data-toggle="tab">YEAR</a></li>
                                    </ul>
                                    <div class="tab-content">
                                       <div class="tab-pane fade " id="top-tab1">
                                          Add <code>top</code> border to the active tab with <code>.nav-tabs-top</code> class.
                                       </div>

                                       <div class="tab-pane fade" id="top-tab2">
                                          Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid laeggin.
                                       </div>

                                       <div class="tab-pane fade" id="top-tab3">
                                          DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
                                       </div>

                                       <div class="tab-pane fade" id="top-tab4">
                                          Aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthet.
                                       </div>

                                         <div class="tab-pane fade active show" id="top-tab5">
                                          <div class="panel panel-flat">
                                          <h5 class="panel-title graph-head">This year  <span class="session-txt">Number of session</span></h5>
                                             <div class="chart-container">
                                                <div class="chart has-fixed-height" id="columns_basic"></div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
 -->                  

 <div class="panel-body text-center" >
   <h3>Coming Soon</h3>
 </div>
               </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
               <form action="#">
                  <div class="panel panel-flat mypannel">
                     <div class="panel-heading">
                        <h5 class="panel-title">PAYMENT HISTORY</h5>
                     </div>
                     <div class="panel-body">
                        <div class="table-responsive">
                           <table class="table text-nowrap">
                              <tbody id="payment_history">
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="panel panel-flat mypannel">
                  <div class="panel-heading">
                     <h5 class="panel-title">NOTES</h5>
                  </div>
                  <div class="panel-body text-center" style="padding-bottom: 10px;" >
                        <h3>Coming Soon</h3>

                     <!-- <div class="card card-body">
                        <div class="media">
                           <div class="media-body content-body">
                              <h6 class="media-title text-muted ">12 :35, 12 MARCH 2019</h6>
                              <h6 class="media-title data-head">The customer call to asks for the package upgrade</h6>
                              <h5 class="media-title text-muted ">1 :35, 12 JUNE 2019</h5>
                              <h6 class="media-title data-head">The customer call to asks for the package upgrade</h6>
                           </div>
                        </div>
                     </div> -->
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- /primary modal -->
<!--begin edit assign job  -->
<div id="modal_change_plan" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Manage subscription</h6>
         </div>
         <form action="<?= base_url('superadmin/managesubscription/updatePlan/').$subscription_details->company_id ?>" name= "updateplan" method="post" class="form-horizontal" >
            <div class="modal-body">
               
               <div class="form-group">
                  
                  <label class="control-label col-sm-9">
                  <input type="checkbox" class="styled" <?= $subscription_details->is_technician_count-1==0 ? '' : 'checked'  ?> id="is_additional" name="is_additional_technition" > 
                  Additional technician: <b>$<?= $subscription_details->additional_technician_rate ?>/<?= ucfirst($subscription_details->type) ?></b> Number of Additional Technician Accounts Needed:</label>
                  
                  <div class="col-sm-3">
                     <input type="text" class="form-control" id="is_count" name="is_technician_count"  placeholder="1-100" value="<?= $subscription_details->is_technician_count-1;  ?>" >
                  </div>

               </div>

               <div class="form-group">
                  <label class="control-label col-sm-12">
                  <input type="checkbox" class="styled"  <?= $subscription_details->is_quickbooks_price==0 ? '' : 'checked'  ?> id="is_quickbooks" name="is_quickbooks_price" value="<?= $subscription_details->quickbooks_rate  ?>"  >
                  Quickbooks online integration: <b>$<?= $subscription_details->quickbooks_rate ?>/<?= ucfirst($subscription_details->type) ?></b>
                  </label>
               </div>
               <hr>
              
               <input type="hidden" name="is_total_price" value="" id="total_price"> 
               <p>Total: $.<b id="total_price1"></b> per <?= $subscription_details->type ?>, recurring</p>
               <div class="modal-footer">
                  <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                  <button type="submit"  class="btn btn-primary" id="job_assign_bt">Save</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>


    <!-- Primary modal -->
 <div id="modal_add_note" class="modal fade">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h6 class="modal-title">Add Note</h6>
       </div>

     <form  name="addjob"  method="post" enctype="multipart/form-data"  >
           <div class="modal-body">           
               <div class="form-group">
                <div class="row">
                  <div class="col-md-12 col-sm-12">                 
                     <label>Note</label>
                     <textarea class="form-control" name="description" placeholder="Enter note here" ></textarea>
                     
                  </div>               
                </div>
              </div>
           </div>

           <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" id="assignjob" class="btn btn-success">Save</button>
           </div>
       </form>
     </div>
   </div>
 </div> 
 <!-- /primary modal -->

<!--end edit assign job  -->
<script type="text/javascript">
   $(document).ready(function(){
   
      counting();
   
      $('#is_additional').click(function(){
          counting();      
      });
   
      $('#is_count').keyup(function (){
          counting();        
      });
   
     $('#is_quickbooks').click(function(){
          counting();      
      });
   
   }); 
   
   
   var subscription_price = '<?= $subscription_details->subscription_price ?>';
   var additional_technician_rate = '<?= $subscription_details->additional_technician_rate ?>';
   var quickbooks_rate = '<?= $subscription_details->quickbooks_rate ?>';
          
    function counting() {
        
        g_total = Number(subscription_price);
        
         if($('#is_additional').prop("checked") == true){       
   
           $("#is_count").prop('readonly', false);
           
           var is_count_val =  $('#is_count').val();
               if (Number(is_count_val) > 0 && Number(is_count_val) < 10001 ) {
            
                   var is_count_mul = Number(additional_technician_rate) * Number(is_count_val);
                   g_total += Number(is_count_mul);
        
               }
        }

        if ($('#is_additional').prop("checked") == false) {
            $('#is_count').val(0);
            $("#is_count").prop('readonly', true);
        }
          
        if($('#is_quickbooks').prop("checked") == true){       
        
           g_total += Number(quickbooks_rate);
        
        }
          
      
       $('#total_price').val(g_total.toFixed(2));
       $('#total_price1').html(g_total.toFixed(2));
          
   } 
          
   
   $(document).ready(function(argument) {
       getAllPayment();    
   })  
   var limit = 5;
   
   $(document).on("click","#load_more",function (e) {
     e.preventDefault();
   
     limit +=5;
   
     getAllPayment();
     
   })
   
   
   function getAllPayment(argument) {
   
       stripe_customer_id = "<?= $subscription_details ? $subscription_details->stripe_customer_id : '' ?>";
       
          $("#loading").css("display","block");
          $.ajax({
             type: "POST",
             url: "<?= base_url('superadmin/managesubscription/getAllCharge') ?>",
             data : {limit : limit,stripe_customer_id : stripe_customer_id},
             dataType : "JSON",
             success: function (response) {
   
                 $("#loading").css("display","none");
                 $('#payment_history').html(response.result);
   
                  str = String(response.card_details.exp_year);
   
                  year =  String(str).split('');
                  $('#card_number').val('************'+response.card_details.last4);
                  $('#cvv').val('***');
                  $('#expiry_month').val(response.card_details.exp_month);
                  $('#expiry_year').val(year[2]+year[3]);
                  $('#name_on_card').val($.trim(response.card_details.name));

                 brand =  response.card_details.brand;
   
   
                 if(brand == 'Visa'){
                   var backPositionCard = '2px -163px, 260px -87px';
                 }else if(brand == 'visa_electron'){
                   var backPositionCard = '2px -205px, 260px -87px';
                 }else if(brand == 'MasterCard'){
                   var backPositionCard = '2px -247px, 260px -87px';
                 }else if(brand == 'Maestro'){
                   var backPositionCard = '2px -289px, 260px -87px';
                 }else if(brand == 'Discover'){
                   var backPositionCard = '2px -331px, 260px -87px';
                 }else if(brand == 'American Express'){
                   var backPositionCard = '2px -121px, 260px -87px';
                 }else{
                   var backPositionCard = '2px -121px, 260px -87px';
                 }

                 $('#card_number').css("background-position", backPositionCard);
              },
   
   
                error: function(data) {
               
                 $("#loading").css("display","none");
       
                  swal({
                      type: 'error',
                      title: 'Oops...',
                      text: 'Something went wrong to get payment history!'
                    })
   
                  console.log(data);
                 }
            });   
   
   }
</script>

<script type="text/javascript">
      /* ------------------------------------------------------------------------------
       *
       *  # Echarts - Basic column example
       *
       *  Demo JS code for basic column chart [light theme]
       *
       * ---------------------------------------------------------------------------- */
      // Setup module
      // ------------------------------
      var EchartsColumnsBasicLight = function() {
          //
          // Setup module components
          //
          // Basic column chart
          var _columnsBasicLightExample = function() {
              if (typeof echarts == 'undefined') {
                  console.warn('Warning - echarts.min.js is not loaded.');
                  return;
              }
              // Define element
              var columns_basic_element = document.getElementById('columns_basic');
              //
              // Charts configuration
              //
              if (columns_basic_element) {
                  // Initialize chart
                  var columns_basic = echarts.init(columns_basic_element);
                  //
                  // Chart config
                  //
                  // Options
                  columns_basic.setOption({
                   // Define colors
                    color: {
                      type: 'linear',
                      x: 0,
                      y: 0,
                      x2: 0,
                      y2: 1,
                      colorStops: [{
                          offset: 0, color: '#ccc' // color at 0% position
                      }, {
                          offset: 1, color: '#f5f5f5' // color at 100% position
                      }],
                      global: false // false by default
                  },
                      // Global text styles
                      textStyle: {
                          fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                          fontSize: 13
                      },
                      // Chart animation duration
                      animationDuration: 750,
                      // Setup grid
                      grid: {
                          left: 0,
                          right: 40,
                          top: 35,
                          bottom: 0,
                          containLabel: true
                      },
                      // Add tooltip
                      tooltip: {
                          backgroundColor: '#fff',
                          padding: [10, 15],
                          textStyle: {
                              fontSize: 13,
                              color:'#000',
                              fontFamily: 'Roboto, sans-serif'
                          }
                      },
                      // Horizontal axis
                      xAxis: [{
                          type: 'category',
                          data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                          axisLabel: {
                              color: '#333'
                          },
                      }],
                      // Vertical axis
                      yAxis: [{
                          type: 'value',
                          axisLabel: {
                              color: '#333'
                          },
                      }],
                      // Add series
                      series: [
                          {
                              name: 'Test',
                              type: 'bar',
                              data: [2.6, 5.9, 9.0, 26.4, 58.7, 70.7, 125.6, 102.2, 48.7, 18.8, 6.0, 2.3],
                              itemStyle: {
                                  normal: {
                                      label: {
                                          show: false,
                                          position: 'top',
                                          textStyle: {
                                              fontWeight: 500
                                          }
                                      }
                                  },
                                    emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#256291'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }

                              },
                          }
                      ]
                  });
              }
              //
              // Resize charts
              //
              // Resize function
              var triggerChartResize = function() {
                  columns_basic_element && columns_basic.resize();
              };
              // On sidebar width change
              var sidebarToggle = document.querySelector('.sidebar-control');
              sidebarToggle && sidebarToggle.addEventListener('click', triggerChartResize);
              // On window resize
              var resizeCharts;
              window.addEventListener('resize', function() {
                  clearTimeout(resizeCharts);
                  resizeCharts = setTimeout(function () {
                      triggerChartResize();
                  }, 200);
              });
          };
          //
          // Return objects assigned to module
          //
          return {
              init: function() {
                  _columnsBasicLightExample();
              }
          }
      }();
      // Initialize module
      // ------------------------------
      document.addEventListener('DOMContentLoaded', function() {
          EchartsColumnsBasicLight.init();
      });
</script>