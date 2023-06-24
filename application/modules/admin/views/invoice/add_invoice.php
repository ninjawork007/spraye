<!-- Content area -->
<style type="text/css">
  .form-group {
    margin-bottom: 24px;
  }
</style>

<style>
    .customerListField {
        cursor: pointer;
        width: 100%;
    }
    .customerListField:hover {
        background-color: #ccc;
    }
</style>

<div class="content form-pg ">
   <!-- Form horizontal -->
   <div class="panel panel-flat">
      <div class="panel-heading">
         <h5 class="panel-title">
            <div class="form-group">
               <a href="<?= base_url('admin/Invoices') ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to All Invoices</a>
            </div>
         </h5>
      </div>
      <br>
      <div class="panel-body">
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
         <form class="form-horizontal" action="<?= base_url('admin/Invoices/addInvoiceData')  ?>" method="post" name="addinvoice" id="addinvoice" enctype="multipart/form-data" >
            <fieldset class="content-group">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-md-3">Select Customer</label>
                        <div class="col-md-9">


                            <input autocomplete="off" type="text" class="form-control" id="customer_list_field" placeholder="Select any customer" />
                            <div style="z-index: 999; width: 100%; display: none; position: absolute; left: 0px; top: 40px; background-color: #ffffff; overflow-y: scroll; height: 25em; max-height: 25em;" id="suggestion-box"></div>
                                    
                            <input type="text"  style="display: none !important;"  id="customer_id" name="customer_id" />



                            
                            


                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mg-bt">
                        <label class="control-label col-md-3">Select Property Address</label>
                        <div class="col-md-9">
                           <select class="bootstrap-select form-control" data-live-search="true" name="property_id" id="property_id">
                           </select>       
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-md-3">Customer Email</label>
                        <div class="col-md-9" style="    padding-left: 6px;">
                           <input type="text" name="customer_email" class="form-control" value="" readonly="" id="customer_email" >
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-md-3">Invoice Date</label>
                        <div class="col-md-9" style="    padding-left: 6px;">
                           <input type="date" name="invoice_date" class="form-control pickaalldate" placeholder="MM-DD-YYYY" >
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row invoice-form">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-md-3">Program Name</label>
                        <div class="col-md-9" style="    padding-right: 3px;">
                           <select class="bootstrap-select form-control" data-live-search="true" name="program_id" id="program_id">
                              <option value="" >Select any program </option>
                              <?php 
                                 if (!empty($program_details)) {
                                   foreach ($program_details as $key => $value) {

										   echo '<option value="'.$value->program_id.'" >'.$value->program_name.'</option>';
                                     
                                   }
                                 }
                                 
                                  ?>
                           </select>
                        </div>
                     </div>
                  </div>
			
				 <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-md-3">Notes</label>
                        <div class="col-md-9" style="    padding-left: 6px;">
                           <input type="text" class="form-control" name="notes" placeholder="Enter Notes">       
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-md-3">Service List</label>
                        <div class="col-md-9 multi-select-full" style="padding-right: 3px;">
                           <select class="bootstrap-select form-control" data-live-search="true" name="job_id_temp" id="job_list">
                              <option value="">Select any Service </option>
                              <?php 
                                 if (!empty($job_details)) {
                                   foreach ($job_details as $key => $value) {
                                     echo '<option value="'.$value->job_id.'" >'.$value->job_name.'</option>';
                                   }
                                 }
                                 
                                  ?>
                           </select>
							
							<span style="color:red;"><?php echo form_error('job_id'); ?></span>
							<span style="color:red;" id="costError"></span>
						<input type="hidden" name="job_id" id="job_id_order_array" value="">
                        </div>
                     </div>
                  </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-lg-3 col-sm-12 col-xs-12">Apply Coupons</label>
                    <div class="multi-select-full col-lg-9" style="padding-left: 4px;">
                      <select class="multiselect-select-all-filtering form-control" name="assign_onetime_coupons[]" id="" multiple="multiple">
                        <?php foreach ($customer_one_time_discounts as $value): ?>

                           <?php
                           // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                           $expiration_pass = true;
                           if ($value->expiration_date != "0000-00-00 00:00:00" && $value->expiration_date != null) {
                              $coupon_expiration_date = strtotime( $value->expiration_date );

                              $now = time();
                              if($coupon_expiration_date < $now) {
                                    $expiration_pass = false;
                                    $expiration_pass_global = false;
                              }
                           }

                           if ($expiration_pass == true) {?>

                             <option value="<?= $value->coupon_id ?>"> <?= $value->code ?> </option>
                            <?php } ?>
                        <?php endforeach ?>
                      </select>
                    </div>
                  </div>
                </div>
                 
               </div>
			<!--- Service List Table ---> 
				<div class="row">
					<div class="col-md-6">
						  <div class="prioritydivcontainer" style="display: none;">
							 <div  class="table-responsive  pre-scrollable">
							   <table  class="table table-bordered" id="serviceListTable" >    
									<thead>  
										<tr>
											<th>Priority</th> 
											<th>Service Name</th>                                                 
											<th>Cost</th>                                                 
											<th>Remove</th>                                                 
										</tr>             
									</thead>
									<tbody class="prioritytbody" >

									</tbody>
							   </table>
							 </div>                    
						  </div>
					</div>
               </div>
		<!--- end service list table --->
            </fieldset>
            <div class="text-right">
              <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i>
              </button>
            </div>
          </form>
      </div>
   </div>
</div>
<!-- /form horizontal -->
<!-- /content area -->
<script type="text/javascript">
/*   // get customer properties and email
    $("#customer_id").change(function(){
       var customer_id = $(this).val();
      // alert(customer_id);
       $.ajax({
            type: "POST",
            url: "",
            data: {customer_id : customer_id } 
        }).done(function(data){
          //alert(data);
          $("#property_id").html(data);
          reassign();
            $.ajax({
                type: "POST",
                url: "",
                data: {customer_id : customer_id } 
            }).done(function(data){
              $("#customer_email").val($.trim(data));
            });
        });
    }); */
    
  // get customer property assigned programs and email
    $("#property_id").change(function(){
        
       var property_id = $(this).val();	
		$.ajax({
            type: "POST",
            url: "<?= base_url('admin/Invoices/getPropertyProgram')  ?>",
            data: {property_id : property_id } 
        }).done(function(data){
          //alert(data);
          
          $("#program_id").html(data);
          reassign();
        });
	});
	

    function reassign() {
          $(".bootstrap-select").selectpicker('destroy');
          $('.bootstrap-select').selectpicker();
    }
	$(document).on('submit','form#addinvoice',function(event){
		event.preventDefault();
		var checkCost = $('.job-cost').is('input');
		if(!checkCost){
			$('span#costError').text("Please enter cost");
		}else{
			$('span#costError').text("");
			$('form#addinvoice').submit();
		}
	});
</script>
<script type="text/javascript">
// SERVICE LIST 	
   var selectedSortingValues = [];
   var selectedSortingTexts = [];
   var selectedValues = [];
   var selectedCosts = [];
   var selectedTexts = [];
   var optionValue = '';
   var optionText = '';
   var optionCost = '';
   $n = 1;
// ADD SELECTED SERVICE TO TABLE
   $(document).on("change","#job_list",function() { 
         
    optionValue = $(this).val();

    if (optionValue!='') {

		 if ($.inArray(optionValue, selectedValues)!='-1') {

		  } else {

			$('.prioritydivcontainer').css("display","block");

			optionText = $("#job_list option:selected").text();
		   // alert(optionValue);
		 //   alert(optionText);
			 

			selectedValues.push(optionValue);

			selectedTexts.push(optionText);  
			  
			  
			  

			var $row = $('<tr id="trid'+$n+'">'+
			  '<td class="index" >'+$n+'</td>'+
			  '<td>'+optionText+'</td>'+
			  '<td><input type="text" class="form-control job-cost" name="cost['+optionValue+']" placeholder="Enter Cost" value="" ></td>'+			 
			  '<td class="removeclass" id="'+$n+'" optionValueRemove="'+optionValue+'" optionTextRemove="'+optionText+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></i></a></li></ul></td>'+
			  '</tr>');    


			$('.prioritytbody:last').append($row);
			$n = $n+1;        
			$('#job_id_order_array').val(selectedValues);
		}
    } 
   });

    $("#program_id").change(function(){
        var program_id = $(this).val();
        var property_id = $('#property_id').val();

        $.ajax({
                type: "POST", 
                url: "<?= base_url('admin/Invoices/getAllServicesByProgram')  ?>",
                data: {program_id : program_id }, 
                dataType: 'JSON', 
            }).done(function(response){

                if (response.status==200) {
                 console.log(response);

                $.each( response.result, function( key, val ) {

                    optionValue = val.job_id;

                    if ($.inArray(optionValue, selectedValues)!='-1') {

                    } else {
                        $('.prioritydivcontainer').css("display","block");

                        optionText = val.job_name;

                        selectedValues.push(optionValue);

                        selectedTexts.push(optionText);

                        var $row = $('<tr id="trid'+$n+'">'+
                            '<td class="index" >'+$n+'</td>'+
                            '<td>'+optionText+'</td>'+
                            '<td><input type="text" class="form-control job-cost" name="cost['+optionValue+']" placeholder="Enter Cost" value="" ></td>'+			 
                            '<td class="removeclass" id="'+$n+'" optionValueRemove="'+optionValue+'" optionTextRemove="'+optionText+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></i></a></li></ul></td>'+
                            '</tr>');    


                            $('.prioritytbody:last').append($row);
                            $n = $n+1;        
                            $('#job_id_order_array').val(selectedValues);
                    }
                });
    
            }   

        });

    });

	
// REMOVE SELECTED SERVICE FROM LIST
  $(document).on("click",".removeclass",function() {    
    // alert(selectedValues);
    // alert(selectedTexts);

    var id = $(this).attr('id');
    var optionValueRemove = $(this).attr('optionValueRemove');
    var optionTextRemove = $(this).attr('optionTextRemove');
    
    selectedValues.splice($.inArray(optionValueRemove, selectedValues),1);
    selectedTexts.splice($.inArray(optionTextRemove, selectedTexts),1);

    $("#trid"+id).remove();

    $('#job_id_order_array').val(selectedValues);

    // alert(selectedValues);
    // alert(selectedTexts);
    rearrangetable();
  });

  function rearrangetable() {
	  var costs = [];
	 $.each(selectedValues, function(c, cost) {
		 var optionCost = $('input[name="cost['+selectedValues[c]+']"]').val();
		 costs[c] = optionCost;
	 });
    $('.prioritytbody').empty();
     $n = 1;
     $.each(selectedValues, function(i, item) {
          var $row = $('<tr id="trid'+$n+'">'+
          '<td class="index" >'+$n+'</td>'+
          '<td>'+selectedTexts[i]+'</td>'+
		  '<td><input type="text" class="form-control job-cost" name="cost['+selectedValues[i]+']" placeholder="Enter Cost" value="'+costs[i]+'"></td>'+
          '<td class="removeclass" id="'+$n+'" optionValueRemove="'+selectedValues[i]+'" optionTextRemove="'+selectedTexts[i]+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"></i></a></li></ul></td>'+
          '</tr>');    

        $('.prioritytbody:last').append($row);
  
    $n = $n+1;        

    });

  }
</script>




<script>

    $(document).ready(function() {
        $("#customer_list_field").keyup(function() {

            if( $("#customer_list_field").val() == "" || $("#customer_list_field").val() == null ){
                $("#suggestion-box").hide();
                return;
            }

            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/invoices/assignCustomerList') ?>",
                data: 'keyword=' + $(this).val(),
                /* beforeSend: function() {
                    $("#customer_list_field").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                }, */
                success: function(data) {
                    if(data != false){
                            $("#suggestion-box").show();
                            $("#suggestion-box").html(data);
                            //$("#customer_list_field").css("background", "#FFF");
                        }
                }
            });
        });

        $("#customer_list_field").focusout(function(){
            setTimeout(() => {
                $("#suggestion-box").hide();
            }, "300");
        });


        $("#customer_list_field").focusin(function(){

            if( $( "#customer_list_field").val() != "" && $("#customer_list_field").val() != null ){
                    $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('admin/invoices/assignCustomerList') ?>",
                    data: 'keyword=' + $(this).val(),
                    /* beforeSend: function() {
                        $("#customer_list_field").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                    }, */
                    success: function(data) {
                        if(data != false){
                            $("#suggestion-box").show();
                            $("#suggestion-box").html(data);
                            //$("#customer_list_field").css("background", "#FFF");
                        }
                    }
                });

                             
            }

        });


    });
    //To select a customer name
    function selectCustomer(val, name) {
        
        $("#customer_id").val(val);
        
        $("#customer_list_field").val(name);
        
        

                var customer_id = val;
            // alert(customer_id);
            $.ajax({
                    type: "POST",
                    url: "<?= base_url('admin/Invoices/getPropertyAddress')  ?>",
                    data: {customer_id : customer_id } 
                }).done(function(data){
                //alert(data);
                $("#property_id").html(data);
                reassign();
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('admin/Invoices/getcutomerEmail')  ?>",
                        data: {customer_id : customer_id } 
                    }).done(function(data){
                    $("#customer_email").val($.trim(data));
                    });
                });
        
        
      
        $("#suggestion-box").hide();
    }

</script>
