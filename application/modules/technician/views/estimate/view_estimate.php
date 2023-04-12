<style type="text/css">
   #loading {
   width: 100%;
   height: 100%;
   top: 0;
   left: 0;
   position: fixed;
   display: none;
   opacity: 0.7;
   background-color: #fff;
   z-index: 99;
   text-align: center;
   }
  .btn-group>.btn:first-child {
    margin-left: 7px;
}
   #loading-image {
   position: absolute;
   left: 50%;
   top: 50%;
   width: 10%;
   z-index: 100;
   }
   .btn-group {
   margin-left: -7px !important;
   margin-top: -1px !important;
   }
   .dropdown-menu {
   min-width: 80px !important;
   }
   .myspan {
   width: 55px;
   }
   .label-warning, .bg-warning {
   background-color :#A9A9A9;
   background-color: #A9A9AA;
   border-color: #A9A9A9;
   }
   .toolbar {
   float: left;
   padding-left: 5px;
   }
   .dataTables_filter {
   /*text-align: center !important;*/
   margin-left: 60px !important;
   }
   #invoicetablediv{
   padding-top: 20px;
   }
   .Invoices .dataTables_filter input {

    margin-left: 11px !important;
    margin-top: 8px !important;
    margin-bottom: 5px !important;
}
.tablemodal > tbody > tr > td, .tablemodal > tbody > tr > th, .tablemodal > tfoot > tr > td, .tablemodal > tfoot > tr > th, .tablemodal > thead > tr > td, .tablemodal > thead > tr > th {
  border-top: 1px solid #ddd;
}


.label-till , .bg-till  {
    background-color: #36c9c9;
    background-color: #36c9c9;
    border-color: #36c9c9;
}


</style>
<!-- Content area -->
<div class="content invoicessss">
   <!-- Form horizontal -->
   <!--   <div class="panel panel-flat">
      <div class="panel panel-flat">
           <div class="panel-heading">
                 <h5 class="panel-title">
                      <div class="form-group">
                        <a href="<?= base_url('admin/Invoices/addInvoice') ?>"  id="save" class="btn btn-success" > New Invoice</a>
                          
                      </div>
      
                 </h5>
            </div>
      </div>
      -->
   <div id="loading" >
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
   </div>
   <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
    
      <div class="row cx-dt">
         <div class="col-md-3 col-sm-3 col-12">
            <div class=" service-bols">
               <h3 class="ser-head">Total Pipeline </h3>
               <p class="text-warning ser-num "> $ <?= number_format($total_pipeline,2) ?></p>
            </div>
         </div>
         <div class="col-md-3 col-sm-3 col-12">
            <div class="service-bols">
               <h3 class="ser-head">Total Accepted</h3>
               <p class=" ser-num text-success">$ <?= number_format($total_accepted,2) ?></p>
            </div>
         </div>
         <div class="col-md-3 col-sm-3 col-12">
            <div class="service-bols">
               <h3 class="ser-head">Total Pending</h3>
               <p class="text-success ser-num">$ <?=  number_format($total_pending,2) ?></p>
            </div>
         </div>
         <div class="col-md-3 col-sm-3 col-12">
           
         </div>
      </div>
   
      <div id="invoicetablediv">
         <div  class="table-responsive table-spraye">
            <table  class="table datatable-filter-custom">
               <thead>
                  <tr>
                     <th><input type="checkbox" id="select_all" <?php if (empty($estimate_details)) { echo 'disabled'; }  ?>    /></th>
                     <th>Estimate #</th>
                     <th>Customer Name</th>
                     <th>Property</th>
                     <th>Total Estimate Cost</th>
                     <th>Status</th>
                     <th>Program</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($estimate_details)) { 
                     foreach ($estimate_details as $value) { ?>      
                  <tr>
                     <td><input  name="group_id" type="checkbox"  value="<?=$value->estimate_id.':'.$value->customer_id ?>" estimate_id="<?=$value->estimate_id ?>" class="myCheckBox" /></td>
                     <td><a href="<?= base_url('admin/Estimates/editEstimate/').$value->estimate_id ?>"><?= $value->estimate_id; ?></a></td> 
                     <td style="text-transform: capitalize;"><a href="<?= base_url('admin/editCustomer/').$value->customer_id ?>"><?= $value->first_name.' '.$value->last_name ?></a></td>
                     <td><?= $value->property_address ?></td>                     
                     <td>
    
                        <?php 
                        $line_total = 0; 
                        $job_details =  GetOneEstimatAllJobPrice(array('estimate_id'=>$value->estimate_id));
                    	
                          if ($job_details) {

                              foreach ($job_details as $key2 => $value2) {

                                

                              if ($value2['price_override'] != '' && $value2['price_override']!=0 && $value2['is_price_override_set'] == 1) {
                                 $cost =  $value2['price_override'];
                                 
                                } else if ($value2['price_override'] != '' && $value2['price_override'] == 0 && $value2['is_price_override_set'] == 1){
                                    $cost = number_format(0, 2);
                                    // die(print_r($job_details));
                                } else {

                                  $priceOverrideData = getOnePriceOverrideProgramProperty(array('property_id'=>$value->property_id,'program_id'=>$value->program_id)); 

                                  if ($priceOverrideData && $priceOverrideData->price_override!=0 && $priceOverrideData->is_price_override_set == 1) {
                                        // $price = $priceOverrideData->price_override;
                                        $cost =  $priceOverrideData->price_override;
                                    
                                  } else if ($priceOverrideData && $priceOverrideData->price_override == 0 && $priceOverrideData->is_price_override_set == 1){
                                        $cost = number_format(0, 2);
                                  } else {
                                     //else no price overrides, then calculate job cost
									$lawn_sqf = $value->yard_square_feet;
									$job_price = $value2['job_price'];
                                    
									//get property difficulty level
									if(isset($value->difficulty_level) && $value->difficulty_level == 2){
										$difficulty_multiplier = $setting_details->dlmult_2;
									}elseif(isset($value->difficulty_level) && $value->difficulty_level == 3){
										$difficulty_multiplier = $setting_details->dlmult_3;
									}else{
										$difficulty_multiplier = $setting_details->dlmult_1;
									}
                                     
									//get base fee 
									if(isset($value2['base_fee_override'])){
										$base_fee = $value2['base_fee_override'];
									}else{
										$base_fee = $setting_details->base_service_fee;
									}

									$cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier)/1000;

									//get min. service fee
									if(isset($value2['min_fee_override'])){
										$min_fee = $value2['min_fee_override'];
									}else{
										$min_fee = $setting_details->minimum_service_fee;
									}

									// Compare cost per sf with min service fee
									if($cost_per_sqf > $min_fee){
										$cost = $cost_per_sqf;
									}else{
										$cost = $min_fee;
									}
                                     
                                 
                                  } 
                              }

                           //  $line_total += $cost;
                           $line_total += round($cost, 2);
                              }
                           
                          }

                          // apply coupons if exists
                          $total_cost = $line_total;
                          if (isset($value->coupon_details) && !empty($value->coupon_details)){
                              foreach($value->coupon_details as $coupon) {
                                    if ($coupon->coupon_amount_calculation == 0) { // flat
                                          $coupon_amm = $coupon->coupon_amount;
                                    } else { // perc
                                          $coupon_amm = ($coupon->coupon_amount / 100) * $total_cost;
                                    }
                                    $total_cost -= $coupon_amm;
                                    if ($total_cost < 0) {
                                       $total_cost = 0;
                                    }
                              }
                          }
                          $line_total = $total_cost;

                          // apply sales tax
                          $line_tax_amount = 0;
                          if ($setting_details->is_sales_tax==1) {
                              $sales_tax_details =  getAllSalesTaxByProperty($value->property_id);
  
                              if ($sales_tax_details) {
                                 foreach ($sales_tax_details as  $property_sales_tax) {
                                    $line_tax_amount += $line_total * $property_sales_tax->tax_value /100;
                                 }           
                              }
                              $line_total += $line_tax_amount;
                          }

                          echo '$ '.number_format(($line_total) ,2); 
                         
                         ?>

                           
                        </td>
                     <td width="13%">
                        <?php switch ($value->status) {
                           case 0:
                             echo '<span  class="label label-warning myspan">Draft</span>';
                             $bg= 'bg-warning';
                             break;
                           case 1:
                             echo '<span  class="label label-danger myspan">Sent</span>';
                             $bg= 'bg-danger';
                             break;
                           
                           case 2:
                             echo '<span  class="label label-till myspan">Accepted</span>';
                            $bg= 'bg-till';
                            break;

                           case 3:
                             echo '<span  class="label label-success myspan">Paid</span>';
                            $bg= 'bg-success';
                            break;

                            
                           } ?>
                        <div class="btn-group">
                           <a href="#" class="label <?= $bg  ?> dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                           <ul class="dropdown-menu dropdown-menu-right" >
                              <li class="changestatus"  estimate_id="<?= $value->estimate_id ?>" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Draft</a></li>
                              <li class="changestatus" estimate_id="<?= $value->estimate_id ?>" value="1" ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>

                              <li class="changestatus" estimate_id="<?= $value->estimate_id ?>" value="2" ><a href="#"><span class="status-mark bg-till position-left"></span> Accepted</a></li>
                              
                              <li class="changestatus" estimate_id="<?= $value->estimate_id ?>" value="3" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>
 
                           </ul>
                        </div>
                     </td>
                     <td><?= $value->program_name ?></td>
                     <td class="table-action">
                        <ul style="list-style-type: none; padding-left: 0px;">

                           <li style="display: inline; padding-right: 10px;">
                              <a  class="email button-next" id="<?= $value->estimate_id ?>"  customer_id="<?= $value->customer_id ?>"    ><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a>
                           </li>


                           <li style="display: inline; padding-right: 10px;">
                              <a href="<?= base_url('admin/Estimates/pdfEstimate/').$value->estimate_id ?>" target="_blank" class=" button-next"><i class=" icon-file-pdf position-center" style="color: #9a9797;"></i></a>
                           </li>
                           <li style="display: inline; padding-right: 10px;">
                              <a href="<?= base_url('admin/Estimates/printEstimate/').$value->estimate_id ?>" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a>
                           </li>
                        </ul>
                     </td>
                  </tr>
                  <?php  }  } ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<!-- /form horizontal -->

<div id="modal_default" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title" style="float: left;">Product Details</h5>

         </div>
         <div class="modal-body" id="productdetails">

        
        

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

<!-- /content area -->


<!-- /content area -->
<script type="text/javascript">
   function  filterSearch(status) {
     
     // alert(status);
     $.ajax({
           type: "GET",
           url: "<?= base_url('admin/Estimates/getAllEstimateBySearch/')?>"+status,
     }).done(function(data){
       $('#invoicetablediv').html(data);
       
       $('#allMessage').prop('disabled', true);
       $('#allPrint').prop('disabled', true);
   
   bilidDataTable();    
     });
   
   }

   bilidDataTable();

   function bilidDataTable(argument) {
       $('.datatable-filter-custom').DataTable({
            
   
          language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
        
   
            dom: 'l<"toolbar">frtip',
            initComplete: function(){
     
           $("div.toolbar")
              .html('<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li onclick="filterSearch(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li onclick="filterSearch(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Draft</a></li><li onclick="filterSearch(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li onclick="filterSearch(2)" ><a href="#"><span class="status-mark bg-till position-left"></span> Accepted</a></li>  <li onclick="filterSearch(3)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>       </ul></div>&nbsp;&nbsp;<button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">Send By Email</button>&nbsp;&nbsp;<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>');           
        }       
     });

   }

  $(document).on("click",".changestatus", function () {

   var estimate_id = $(this).attr('estimate_id');
   var status = $(this).val();
   $("#loading").css("display","block");  
   $.ajax({
       type: 'POST',
       url: '<?php echo base_url(); ?>admin/Estimates/changeStatus',
       data: {estimate_id: estimate_id, status: status},
       success: function (data) {
        $("#loading").css("display","none");
        location.reload();      
       }
   });
  
 });

</script>




<script>
   $(document).on("click",".email", function () {
   
        // $('.email').click(function(){
   
          var estimate_id = $(this).attr('id');
          var customer_id = $(this).attr('customer_id');
           
          
              swal.mixin({
               input: 'text',
               confirmButtonText: 'Send',
               showCancelButton: true,
               progressSteps: 1
             }).queue([
               {
                 title: 'Additional Estimate Message',
                 text: 'Type a message to the customer below to be included with the estimate. Then click "Send" to email the estimate to the customer.'
               },
             ]).then((result) => {
               if (result.value) {
                var message  = result.value;
   
                  $("#loading").css("display","block");
   
   
                          $.ajax({
                           type: 'POST',
                           url: '<?php echo base_url(); ?>admin/Estimates/sendPdfMail',
                           data: {estimate_id: estimate_id, customer_id: customer_id,message : message},
                           success: function (data)
                           {
   
                            $("#loading").css("display","none");
                               swal(
                                    'Estimate !',
                                    'Sent Successfully ',
                                    'success'
                                    ).then(function() {
                                       location.reload();
                                    });
                                  
                           }
                         });
               }
             })
   
   
   
   
           
         
         });
   
</script>

<script language="javascript" type="text/javascript">
   $(document).on("change","#select_all", function () {
   
     // $("#select_all").change(function(){  //"select all" change 
       var status = this.checked; // "select all" checked status
      if (status) {
       $('#allMessage').prop('disabled', false);
       $('#allPrint').prop('disabled', false);
       $('#deletebutton').prop('disabled', false);
   
      }
      else
      {
        $('#allMessage').prop('disabled', true);
        $('#allPrint').prop('disabled', true);
        $('#deletebutton').prop('disabled', true);
        
      }
   
       $('input:checkbox').not(this).prop('checked', this.checked);
   
       // $(document).on("each",'.myCheckBox',function(){ //iterate all listed checkbox items
       //     this.checked = status; //change ".checkbox" checked status
   
       // });
   });
   
   // $(document).on("change",".myCheckBox", function () {
   
   //   // $('.myCheckBox').change(function(){ //".checkbox" change 
   //     //uncheck "select all", if one of the listed checkbox item is unchecked
      
   // });
   
</script>
<script type="text/javascript">
   // var checkBoxes = $('.myCheckBox');
   $(document).on("change",".myCheckBox", function () {
   
   // checkBoxes.change(function () {
   // alert(checkBoxes);
      if($('.myCheckBox').filter(':checked').length < 1) {
    //  alert("if");
       $('#allMessage').prop('disabled', true);
       $('#allPrint').prop('disabled', true);
       $('#deletebutton').prop('disabled', true);
      }
      else {
       $('#allMessage').prop('disabled', false);
       $('#allPrint').prop('disabled', false);
       $('#deletebutton').prop('disabled', false);
   
      //  alert('else');
      }
   
       if(this.checked == false){ //if this item is unchecked
          $("#select_all")[0].checked = false; //change "select all" checked status to false
   
   
      }
      
      //check "select all" if all checkbox items are checked
      if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){ 
          $("#select_all")[0].checked = true; //change "select all" checked status to true
          
           
      }
   
      // $('#allMessage').prop('disabled', checkBoxes.filter(':checked').length < 1);
      // $('#allPrint').prop('disabled', checkBoxes.filter(':checked').length < 1);
   });
   
   //checkBoxes.change();  
   
</script>

<script type="text/javascript">
   $(document).on("click","#allMessage", function () {
   
   
       
            swal.mixin({
             input: 'text',
             confirmButtonText: 'Send',
             showCancelButton: true,
             progressSteps: 1
           }).queue([
             {
               title: 'Estimate Message',
               text: 'Type a message to the customer below to be included with the estimate. Then click "Send" to email the estimate to the customer.'
             },
           ]).then((result) => {
             
             if (result.value) {
                 var message  = result.value;
                 
                 var group_id_array = $("input:checkbox[name=group_id]:checked").map(function(){
                           return $(this).val();
                       }).get(); // <----
   
                $("#loading").css("display","block");
                        $.ajax({
                         type: 'POST',
                         url: '<?php echo base_url(); ?>admin/Estimates/sendPdfMailToSelected',
                         data: {group_id_array,message : message},
                         success: function (data)
                         {
   
                          $("#loading").css("display","none");
                             swal(
                                  'Estimate !',
                                  'Sent Successfully ',
                                  'success'
                                  ).then(function() {
                                    location.reload();
                                  });
                               
                         }
                       });
             }
           })
       });
   
</script>
<script type="text/javascript">
   $(document).on("click","#allPrint", function () {
   
     
       var estimates_ids = $("input:checkbox[name=group_id]:checked").map(function(){
                         return $(this).attr('estimate_id');
        }).get(); // <----
     
     var href ="<?= base_url('admin/Estimates/printEstimate/') ?>"+estimates_ids;
   
       var win = window.open(href, '_blank');
        win.focus();
   
   });
</script>
<script type="text/javascript">
   function deletemultiple() {
   
        swal({
         title: 'Are you sure?',
         text: "You won't be able to recover this !",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#009402',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes',
         cancelButtonText: 'No'
       }).then((result) => {
   
         if (result.value) {
   
          var selectcheckbox = [];
          $("input:checkbox[name=group_id]:checked").each(function(){
                selectcheckbox.push($(this).attr('estimate_id'));
           }); 
   
        
             $.ajax({
                type: "POST",
                url: "<?= base_url('')  ?>admin/Estimates/deletemultipleEstimates",
                data: {estimates_ids : selectcheckbox }
             }).done(function(data){
   
                     if (data==1) {
                       swal(
                          'Estimates !',
                          'Deleted Successfully ',
                          'success'
                      ).then(function() {
                        location.reload();
                      });

                        
   
                     } else {
                       swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        })
                     }
             });
         }
       })
     }


</script>
