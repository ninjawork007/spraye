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
 
</style>

<link href="<?= base_url('assets/card_payment/style.css') ?>" rel="stylesheet">
<script src="https://js.stripe.com/v2/"></script>
<script src="<?= base_url('assets/card_payment//creditCardValidator.js') ?>"></script>
<div class="content">

      <div id="loading" >
         <img id="loading-image" src="<?= base_url() ?>assets/loader.gif"  /> <!-- Loading Image -->
      </div>

    <div class="panel panel-flat">
     
      <div class="panel-body">
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

        <div class="row">


          <div class="col-md-6" >


            <div class="row">
                <div class="col-md-12" >

              
                <div class="panel panel-flat mypannel">
                  <div class="panel-heading">
                    <h5 class="panel-title">PLAN INFORMATION</h5>
                    <div class="heading-elements">
                      <button class="btn btn-warning" data-toggle="modal" data-target="#modal_change_plan" >CHANGE PLAN</button>
 
                     </div>
                  </div>

                  <div class="panel-body" style="padding-bottom: 10px;" >


                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                          
                          <label class="first_level" >PLAN LEVEL :</label>
                          <label class="second_level" ><?= !empty($subscription_details) ? ucfirst($subscription_details->type).'ly' : ''; ?></label>
                        </div>
                        <div class="col-md-6">
                          
                          <label class="first_level" >SUBSCRIPTION DATE :</label>
                          <label class="second_level" ><?= !empty($subscription_details) ? date("n/d/Y", strtotime($subscription_details->subscription_created_at)) : ''; ?></label>
                        </div>
                        
                      </div>
                    </div>
                
                  </div>

                </div>
            
                  
                </div> 
                <div class="col-md-12" >


            
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

                      <input type="button" id="cardSubmitBtn" value="ADD/EDIT PAYMENT METHOD" class="payment-btn btn btn-success" disabled="true" >

        
                    </form> 
                  
                
                  </div>

                </div>
                              

                </div>
              
            </div>


                      
          </div>

          <div class="col-md-6" >

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
            <h6 class="modal-title">Update Plan</h6>
         </div>
         <form action="<?= base_url('admin/Managesubscription/updatePlan') ?>" name= "updateplan" method="post" class="form-horizontal" >
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
<!--end edit assign job  -->


<script type="text/javascript">


  $(document).ready(function(){

     counting();

     $('#is_additional').click(function(){
       if($(this).prop("checked") == false){
          $('#is_count').val(0);          
       }

         counting();      
     });
 
     $('#is_count').keyup(function (){
         counting();        
     });
 
    $('#is_quickbooks').click(function(){
         counting();      
     });
 
 }); 


 var subscription_price = '<?= (isset($subscription_details->subscription_price)?$subscription_details->subscription_price:0) ?>';
 var additional_technician_rate = '<?= (isset($subscription_details->additional_technician_rate)?$subscription_details->additional_technician_rate:0) ?>';
 var quickbooks_rate = '<?= (isset($subscription_details->quickbooks_rate)?$subscription_details->quickbooks_rate:0) ?>';
         
   function counting() {
       
       g_total = Number(subscription_price);
       
        if($('#is_additional').prop("checked") == true){       
          $("#is_count").prop('readonly', false);          
          var is_count_val =  $('#is_count').val();
              if (Number(is_count_val) > 0 && Number(is_count_val) < 10001 && Number.isInteger(Number(is_count_val))  ) {
           
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
            url: "<?= base_url('admin/Managesubscription/getAllCharge') ?>",
            data : {limit : limit,stripe_customer_id : stripe_customer_id},
            dataType : "JSON",
            success: function (response) {
  
                $("#loading").css("display","none");
                 $('#payment_history').html(response.result);
                 if(response.card_details != null) {
                    str = String(response.card_details.exp_year);

                    year =  String(str).split('');
                    $('#card_number').val('************'+response.card_details.last4);
                    $('#cvv').val('***');
                    $('#expiry_month').val(response.card_details.exp_month);
                    $('#expiry_year').val(year[2]+year[3]);
                    $('#name_on_card').val(response.card_details.name);

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
                 }
                         

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

<script>


  Stripe.setPublishableKey('<?php  echo public_api_key; ?>');

// Callback to handle the response from stripe
function stripeResponseHandler(status, response) {
    if (response.error) {
        // Enable the submit button 
        $('#cardSubmitBtn').removeAttr("disabled");
        // Display the errors on the form

                 swal({
                     type: 'error',
                     title: 'Oops...',
                     text: response.error.message
                   })
        // $(".payment-status").html('<p>'+response.error.message+'</p>');
    } else {
       
        var token = response.id;

         $("#loading").css("display","block");
         $.ajax({ 
            type: "POST",
            url: "<?= base_url('admin/Managesubscription/addCard/') ?>"+token,
            data: $('#paymentFrm').serialize(),
            dataType : "JSON",
             success: function (response) {
            
              $("#loading").css("display","none");
              $("#cardSubmitBtn").removeAttr('disabled');
            
              if (response.status==200) {

                 swal(
                            'Card !',
                            'Updated Successfully ',
                            'success'
                     )


              }  else if (response.status==400) {
                   swal({
                       type: 'error',
                       title: 'Oops...',
                       text: response.msg
                     })


              } else {

                 swal({
                     type: 'error',
                     title: 'Oops...',
                     text: 'Something went wrong!'
                   })

              }  

             },
               error: function(data) {

                 $("#cardSubmitBtn").removeAttr('disabled');
              
                $("#loading").css("display","none");
      
                 swal({
                     type: 'error',
                     title: 'Oops...',
                     text: 'Something went wrong!'
                   })

                 console.log(data);
                }
           });  

        // Insert the token into the form
        // form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        // Submit form to the server
        // form$.get(0).submit();
    }
}
function cardFormValidate(){
  var cardValid = 0;
    
  //card number validation
  $('#card_number').validateCreditCard(function(result){
     console.log(result);

    var cardType = (result.card_type == null)?'':result.card_type.name;

    if(cardType == 'Visa'){
      var backPosition = result.valid?'2px -163px, 260px -87px':'2px -163px, 260px -61px';
    }else if(cardType == 'visa_electron'){
      var backPosition = result.valid?'2px -205px, 260px -87px':'2px -163px, 260px -61px';
    }else if(cardType == 'MasterCard'){
      var backPosition = result.valid?'2px -247px, 260px -87px':'2px -247px, 260px -61px';
    }else if(cardType == 'Maestro'){
      var backPosition = result.valid?'2px -289px, 260px -87px':'2px -289px, 260px -61px';
    }else if(cardType == 'Discover'){
      var backPosition = result.valid?'2px -331px, 260px -87px':'2px -331px, 260px -61px';
    }else if(cardType == 'Amex'){     
      var backPosition = result.valid?'2px -121px, 260px -87px':'2px -121px, 260px -61px';
    }else{
      var backPosition = result.valid?'2px -121px, 260px -87px':'2px -121px, 260px -61px';
    }
    $('#card_number').css("background-position", backPosition);
    if(result.valid){
      $("#card_type").val(cardType);
      $("#card_number").removeClass('required');
      cardValid = 1;
    }else{
      $("#card_type").val('');
      $("#card_number").addClass('required');
      cardValid = 0;
    }
  });
    
  //card details validation
  var cardName = $("#name_on_card").val();
  var expMonth = $("#expiry_month").val();
  var expYear = $("#expiry_year").val();
  var cvv = $("#cvv").val();
  var regName = /^[a-z ,.'-]+$/i;
  var regMonth = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
  var regYear = /^19|20|21|22|23|24|25|26|27|28|29|30|31|32|333|34|35|36|37|38|39|40|41|42|43|45|46|47|48|49|50$/;
  var regCVV = /^[0-9]{3,3}$/;
  // alert(regYear.test(expYear));
  if (cardValid == 0) {
    $("#card_number").addClass('required');
    $("#card_number").focus();
    return false;
  }else if (!regCVV.test(cvv)) {
    $("#card_number").removeClass('required');
    $("#cvv").addClass('required');
    $("#cvv").focus();
    return false;
  }else if (!regMonth.test(expMonth)) {
    $("#card_number").removeClass('required');
    $("#cvv").removeClass('required');
    $("#expiry_month").addClass('required');
    $("#expiry_month").focus();
    return false;
  }else if (!regYear.test(expYear)) {
    $("#card_number").removeClass('required');
    $("#cvv").removeClass('required');
    $("#expiry_month").removeClass('required');
    $("#expiry_year").addClass('required');
    $("#expiry_year").focus();
    return false;
  }else if (!regName.test(cardName)) {
    $("#card_number").removeClass('required');
    $("#cvv").removeClass('required');
    $("#expiry_month").removeClass('required');
    $("#expiry_year").removeClass('required');
    $("#name_on_card").addClass('required');
    $("#name_on_card").focus();
    return false;
  }else{
    $("#card_number").removeClass('required');
    $("#expiry_month").removeClass('required');
    $("#expiry_year").removeClass('required');
    $("#cvv").removeClass('required');
    $("#name_on_card").removeClass('required');
    $("#cardSubmitBtn").removeAttr('disabled');
    return true;
  }
}
$(document).ready(function() {
  //Demo card numbers
  $('.card-payment .numbers li').wrapInner('<a href="javascript:void(0);"></a>').click(function(e) {
    e.preventDefault();
    $('.card-payment .numbers').slideUp(100);
    cardFormValidate();
    return $('#card_number').val($(this).text()).trigger('input');
  });
  $('body').click(function() {
    return $('.card-payment .numbers').slideUp(100);
  });
  $('#sample-numbers-trigger').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    return $('.card-payment .numbers').slideDown(100);
  });
  
  //Card form validation on input fields
  $('#paymentFrm input[type=text]').on('keyup',function(){
    cardFormValidate();
  });
  
  //Submit card form
  $("#cardSubmitBtn").on('click',function(){
    if(cardFormValidate()){
      var card_number = $('#card_number').val();
      var valid_thru = $('#expiry_month').val()+'/'+$('#expiry_year').val();
      var cvv = $('#cvv').val();
      var card_name = $('#name_on_card').val();


       
        // Disable the submit button to prevent repeated clicks
        $('#cardSubmitBtn').attr("disabled", "disabled");
    
        // Create single-use token to charge the user
        Stripe.createToken({
        
            number: $('#card_number').val(),
            exp_month: $('#expiry_month').val(),
            exp_year: $('#expiry_year').val(),
            cvc: $('#cvv').val(),
            name: $('#name_on_card').val(),
        }, stripeResponseHandler);
         
        return false;
    


    // 
    }else{
      // $('.cardInfo').slideDown('slow');
      // $('.cardInfo').html('<p>Wrong card details given, please try again.</p>');
    }
  });
});
</script>
