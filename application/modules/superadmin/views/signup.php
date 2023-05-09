<!DOCTYPE html>
<html lang="en">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>SPRAYE</title>
       <!-- signup -->

       <!-- Global stylesheets -->
      <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
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
         <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
      <script type="text/javascript" src="<?= base_url() ?>assets/signup/plugins/extensions/cookie.js"></script>



      <script type="text/javascript" src="<?= base_url() ?>assets/signup/core/app.js"></script>
      <script type="text/javascript" src="<?= base_url() ?>assets/signup/wizard/wizard_steps.js"></script>
      <script src="<?= base_url() ?>/assets/popup/js/sweetalert2.all.js"></script>
    
      <script src="<?= base_url() ?>assets/admin/assets/js/pages/jquery.sweet-modal.min.js"></script>
      <script src="https://js.stripe.com/v2/"></script>
      <script src="https://checkout.stripe.com/checkout.js"></script>
      <!-- /theme JS files -->
      <style type="text/css">
         .login_new{
         background: #01669a;
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
         padding-left: 18px;
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
         .form1 .form-control, .form2 .form-control, .form-control {
         border: 2px solid #11a5a5;
         }
         .form-control:focus {
         border-color : #11a5a5;
         }
         #is_count {
        /* width: 19%;
         height: 28px;
         float: right;
         margin-right: 50px;*/
         }
         .my_label {
            font-size: 14px;
            padding-left: 0px !important;
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
         h2.panel-title {
            font-size: 30px;
        }
        .wizard>.actions>ul>li>a {
            background: #28a5a5;
        }
        b#total_price1 {
            font-weight: 400;
        }
        .checker {
            margin-right:10px;            
        }

         .control-label[class*="col-lg-"] {
             padding-top: 8px;
        }
      </style>
   </head>
   <body class="login-container" style="overflow-x: hidden; font-family: Roboto, sans-serif !important;">
      <!-- Page container -->
      <div class="page-container">
         <!-- Page content -->
         <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
               <!-- Content area -->
               <div class="content margin-container">
                  <div class="">
                     <div class="col-md-3"></div>
                     <div class="col-md-6 register-form">
                        <!-- Wizard with validation -->
                        <div class="panel panel-white register-form">
                           <div id="loading" > 
                              <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> 
                           </div>
                           <?php 
                              if(empty($result)) { 
                              
                                  echo $this->session->flashdata('message');
                              }
                              else{
                              ?>  
                           <form class="steps-validation">
                              <h6 style="display: none">Company Details</h6>
                              <fieldset>
                                 <div class="panel-heading register-form">
                                    <h2 class="panel-title">Great! Let's create your account now</h2>
                                    <p style="font-size: 16px;color: #9f9f9f;">Information you share is totally private and non-disclosed</p>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label>First name <span class="text-muted">*</span></label>
                                          <input type="text" name="first_name" data-placeholder="First name"  class="form-control required"   >
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label>Last name <span class="text-muted">*</span></label>
                                          <input type="text" name="last_name"  data-placeholder="Last name" class="form-control required"   >
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-8">
                                       <div class="form-group">
                                          <label>Company name <span class="text-muted">*</span></label>
                                          <input type="text" name="company_name" id="company_name"  class="form-control required" placeholder="Please share your Company's full name"  >
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                 </div>
                                  <div class="row">
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label>Company address <span class="text-muted">*</span></label>
                                          <input type="text" name="company_address"  id="autocomplete" onFocus="geolocate()"  class="form-control required" placeholder="Please share your Company's address"  >
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label>E-mail <span class="text-muted">*</span></label>
                                          <input type="email" name="company_email"  class="form-control required" placeholder="Your contact email"   >
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label>Phone </label>
                                          <input type="text" name="phone"  class="form-control required" placeholder="Phone"  >
                                    </div>
                                 </div>
                              </div>
                               <div class="row">
                                
                                    <div class="col-md-6"  >
                                       <div class="form-group">
                                          <label>Create your password  <span class="text-muted">*</span></label>
                                          <input type="password" name="password"  class="form-control required" placeholder="Create your password" id="new_password"  >
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label>Repeat your password  <span class="text-muted">*</span></label>
                                          <input type="password" name="confirm_password"  class="form-control required" placeholder="Repeat your password" id="confirm_password"   >
                                       </div>
                                    </div>
                                 </div>

                                
                              </fieldset>
                              <h6 style="display: none;">Subscription</h6>
                              <fieldset>
                                 <div class="panel-heading register-form">
                                    <h2 class="panel-title">Nearly done!</h2>
                                    <p style="font-size: 17px;margin: 0 0 40px;color: #9f9f9f;">Just a couple more steps left</p>
                                    <h5 class="panel-title text-center">Plan type : <?php echo ucfirst($result['subscription_name']); ?>, recurring purchase.</h5>
                                    <p>All plans include one technician/admin user.<br> <a href="https://www.spraye.io/">Change your plan</a></p>
                                   
                                 </div>
                                 <h3 align="center" style="color: #607D8B;">Choose Additional Options:</h3>
                                 <div class="row">
                                    <div class="form-group">
                                       <div class="checkbox">
                                          <label>
                                             <input type="checkbox" name="is_additional_technition" class="styled" value="yes" id="is_additional">
                                             Additional technician: <b>$<?= $result['additional_technician_rate'] ?>/<?= ucfirst($result['type']) ?></b>
                                          </label>
                                          <label>
                                         
                                            <div class="form-group" >
                                               
                                              <label class="col-lg-8 col-md-8 col-sm-12 control-label my_label">Number of Additional Technician Accounts Needed: </label>
                                              <div class="col-lg-3 col-md-8 col-sm-12">
                                                 <input type="text" id="is_count" name="is_technician_count"  class="form-control">
                                              </div>
                                            </div>  
                                          </label>
                                          
                                       </div>
                                      </div> 


                                    <div class="form-group">
                                       <div class="checkbox">
                                          <label>
                                          <input type="checkbox" name="is_quickbooks_price" value="<?= $result['quickbooks_rate'] ?>" id="is_quickbooks" class="styled">
                                          Quickbooks online integration: <b>$<?= $result['quickbooks_rate'] ?>/<?= ucfirst($result['type']) ?></b>   
                                          </label>
                                       </div>
                                     </div>
                                     
                                    <div class="form-group">

                                       <h3 align="center" class="subcription">Total: $
                                          <b id="total_price1"><?php echo $result['subscription_price']; ?></b>
                                             per <?= $result['type'] ?>, recurring
                                        </h3>
                                    </div>   

                                    <input type="hidden" name="is_total_price" value="<?php echo $result['subscription_price']; ?>" id="total_price">
                                   
                                    <div class="form-group">
                                       <div class="checkbox">
                                          <label>
                                          <input type="checkbox" id="term" name="term" class="styled" required>
                                          I accept the <a style="color: #016699;" href="<?= base_url('superadmin/auth//tearmCondition')  ?>"  target="_blank" >terms of use </a> & <a style="color: #016699" href="<?= base_url('superadmin/auth//tearmCondition')  ?>"  target="_blank" >privacy policy</a>
                                          </label>
                                       </div>
                                    </div>   
                                    

                                
                                 </div>
                                 <input type="hidden" name="subscription_unique_id"  value="<?= $result['subscription_unique_id'] ?>" >
                              </fieldset>
                              <div id="buynow" style="display: none;">
                                 <button type="button" class="stripe-button" id="payButton" >Buy Now</button>
                                 <input type="hidden" id="payProcess" value="0"/>
                              </div>
                           </form>
                           <?php } ?>
                        </div>
                        <!-- /wizard with validation -->
                     </div>
                     <div class="col-md-3"></div>
                  </div>
               </div>
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
         
         
         var subscription_price = '<?= $result['subscription_price'] ?>';
         var additional_technician_rate = '<?= $result['additional_technician_rate'] ?>';
         var quickbooks_rate = '<?= $result['quickbooks_rate'] ?>';
         
         function counting() {
         
         g_total = Number(subscription_price);
         
          if($('#is_additional').prop("checked") == true){       
              $("#is_count").prop('readonly', false);
            
            var is_count_val =  $('#is_count').val();
                if (Number(is_count_val) > 0 && Number(is_count_val) < 10001 && Number.isInteger(Number(is_count_val)) ) {
             
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
      </script>   
      <script type="text/javascript">
         $(document).ready(function(){
             $('button.stripe-button-el').hide();
             var handler = StripeCheckout.configure({
             key: '<?php echo public_api_key; ?>',
             image: '',
             locale: 'auto',
             token: function(token) {
                 // You can access the token ID with `token.id`.
                 // Get the token ID to your server-side code for use.
              
                
                 $('#paymentDetails').hide();
                 $('#payProcess').val(1);
                 $("#loading").css("display","block");
         
                 $.ajax({
                     url: '<?php echo base_url('superadmin/auth/stripeCharges/'); ?>'+token.id,
                     type: 'POST',
                     data: $('form').serialize(),
                     dataType: "json",
                     beforeSend: function(){
                         $('#payButton').prop('disabled', true);
                         $('#payButton').html('Please wait...');
                     },
                     success: function(data){
                         $('#payProcess').val(0);
         
                         $("#loading").css("display","none");
                      
                         if(data.status == 1){
                         
                                swal(
                                   'SPRAYE SIGNUP !',
                                   data.msg,
                                   'success'
                               )
                                 setInterval(function() {

                                  window.location.href="https://go.spraye.io/welcome34668369"; 

                               }, 2000);
         
         
                         } else if (data.status==0)  { 
         
                            $('#payButton').prop('disabled', false);
                            $('#payButton').html('Buy Now');
         
                              swal({
                                 type: 'error',
                                 title: 'Oops...',
                                 text: data.msg
                              })
                         }else {
         
                             $('#payButton').prop('disabled', false);
                             $('#payButton').html('Buy Now');
         
                             swal({
                                 type: 'error',
                                 title: 'Oops...',
                                  text: 'Something went wrong!'
                             })
         
         
                         }
                     },
                     error: function(data) {
                         $('#payProcess').val(0);
                         $('#payButton').prop('disabled', false);
                         $('#payButton').html('Buy Now');
                         $("#loading").css("display","none");
         
                         swal({
                             type: 'error',
                             title: 'Oops...',
                              text: 'Something went wrong!'
                         })
         
                         console.log(data);
                     }
                 });
             }
         });
         
         var stripe_closed = function(){
             var processing = $('#payProcess').val();
             if (processing == 0){
                 $('#payButton').prop('disabled', false);
                 $('#payButton').html('Buy Now');
             }
         };
         
         var eventTggr = document.getElementById('payButton');
             if(eventTggr){
                 eventTggr.addEventListener('click', function(e) {
                     $('#payButton').prop('disabled', true);
                     $('#payButton').html('Please wait...');
                     
                     // Open Checkout with further options:
                     handler.open({
                         name: $('#company_name').val(),
                         description: 'Subscription',
                         amount:  100 * $("input[name=is_total_price]").val(),
                         currency: 'usd',
                         closed: stripe_closed
                     });
                     e.preventDefault();
                 });
             }
         
             // Close Checkout on page navigation:
             window.addEventListener('popstate', function() {
               handler.close();
             });
         });
      </script>
      <script src="https://maps.googleapis.com/maps/api/js?key=<?= GoogleMapKey ?>&libraries=places&callback=initAutocomplete"
         async defer></script>
      <script>
         // This example displays an address form, using the autocomplete feature
         // of the Google Places API to help users fill in the information.
         
         var placeSearch, autocomplete;
         var componentForm = {
         street_number: 'short_name',
         route: 'long_name',
         locality: 'long_name',
         administrative_area_level_1: 'short_name',
         country: 'long_name',
         postal_code: 'short_name'
         };
         
         function initAutocomplete() {
         // Create the autocomplete object, restricting the search to geographical
         // location types.
         autocomplete = new google.maps.places.Autocomplete(
          /** @type {!HTMLInputElement} */
          (document.getElementById('autocomplete')), {
            types: ['geocode']
          });
         
         // When the user selects an address from the dropdown, populate the address
         // fields in the form.
         autocomplete.addListener('place_changed', function() {
          fillInAddress(autocomplete, "");
         });
         
         
         
         
         }
         
         function fillInAddress(autocomplete, unique) {
         // Get the place details from the autocomplete object.
         var place = autocomplete.getPlace();
         
         for (var component in componentForm) {
          if (!!document.getElementById(component + unique)) {
            document.getElementById(component + unique).value = '';
            document.getElementById(component + unique).disabled = false;
          }
         }
         
         // Get each component of the address from the place details
         // and fill the corresponding field on the form.
         for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType] && document.getElementById(addressType + unique)) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType + unique).value = val;
         
            alert(val);
          }
         }
         
         
         }
         google.maps.event.addDomListener(window, "load", initAutocomplete);
         
         function geolocate() {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
              var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
              };
              var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
              });
         
              //alert(position.coords.latitude);
              autocomplete.setBounds(circle.getBounds());
            });
          }
         }
      </script>
   </body>
</html>
