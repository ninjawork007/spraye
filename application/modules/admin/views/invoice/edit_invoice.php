<style type="text/css">
  
  .new_label_control {
    font-size: 16px;
  }
  @-moz-document url-prefix() {
label.control-label.col-lg-3 {
    font-size: 14px;
  }
}

.error{
  color: red;
}

.btn-paid-status{
  background-color: #4CAF50;
  color: white;
}
.btn-paid-status:hover, .btn-paid-status:active, .btn-paid-status:focus{
  color: white;
}

</style>
        <!-- Content area -->
        <div class="content">

          <!-- Form horizontal -->
          <div class="panel panel-flat">
          <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/Invoices') ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to All Invoices</a>
                        </div>
                   </h5>
              </div>
              
            
            <div class="panel-body">
              
               <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
       

              <form class="form-horizontal" id="edit_invoice_form" action="<?= base_url('admin/Invoices/editInvoiceData/').$invoice_details->invoice_id  ?>" method="post" name="addinvoice" enctype="multipart/form-data" >
                <fieldset class="content-group">
                 

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Select Customer</label>
                      <div class="col-lg-9" style="padding-left: 5px;">
             
               <input type ="hidden" class="form-control" name="customer_id" id="customer_id" value="<?=$invoice_details->customer_id?>">

                          <?php if (!empty($customer_details)) {
                             foreach ($customer_details as $key => $value) {

                              if ($invoice_details->customer_id==$value->customer_id) {

                  ?>
                  <input type ="text" class="form-control" name="customer" id="customer" value="<?php echo $value->first_name.$value->last_name; ?>" readonly>
                <?php
                              } else {
 
                
                              }


                             }
                          } ?>

                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label col-lg-3">Select Property Address</label>
                      <div class="col-lg-9" style="padding-left: 5px;">
            <input type="hidden" name="property_id" id="property_id" value="<?= $invoice_details->property_id ?>"> 
            


                       <?php 

                        if (!empty($property_address)) {

                          foreach ($property_address as $key => $value) {

                              if ($invoice_details->property_id==$value->property_id) {

                ?>  <input type="text" class="form-control" name="property" id="property" value="<?= $value->property_address ?>" readonly> <?php
                              } else {

                              }                            

                          }
                     
                        }


                        ?>


                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group" style="margin-bottom: 30px;">
                      <label class="control-label col-lg-3">Customer Email</label>
                      <div class="col-lg-9" style="padding-left: 5px;">
                        <input type="text" name="customer_email" class="form-control" value="<?= $invoice_details->email ?>" id="customer_email" readonly>
                      </div>
                    </div>
                  </div>
              
                  <div class="col-md-6">
                    <div class="form-group" style="margin-bottom: 30px;">
                      <label class="control-label col-lg-3">Invoice Date</label>
                      <div class="col-lg-9" style="padding-left: 5px;">
                      <input type="date" name="invoice_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?= $invoice_details->invoice_date ?>"  readonly>
                     </div>
                    </div>
                  </div>
              </div>
              
             

              <div class="row">
               
               <div class="col-md-6">
                  <div class="form-group"  style="margin-bottom: 35px;">
                    <label class="control-label col-lg-3">Service Name</label>
                    <div class="col-lg-9"  style="padding-left: 9px;padding-right: 4px;">
                     <select class="bootstrap-select form-control" data-live-search="true" name="job_id" id="job_list">
                     <option value="" >Select any Service </option> 
                    <?php 
                    if (!empty($job_details)) {
                      foreach ($job_details as $key => $value) {

                        if ($value->job_id==$invoice_details->job_id) {
                          $selected = 'selected';
                        }else{
                          $selected ='';
                        }
                        echo '<option value="'.$value->job_id.'" '.$selected.' >'.$value->job_name.'</option>';
                      }
                    }

                     ?>

                     </select>
            <input type="hidden" name="job_ids" id="job_id_order_array" value="">
                   </div>
                  </div>
                </div>
            
                <div class="col-md-6">
                  <div class="form-group"  style="margin-bottom: 35px;">
                    <label class="control-label col-lg-3">Program Name</label>
                    <div class="col-lg-9"  style="padding-left: 9px; padding-right: 4px;">
          <input type="hidden" name="program_id" id="program_id" value="<?=$invoice_details->program_id?>">

                    <?php 
                    if (!empty($program_details)) {


                      foreach ($program_details as $key => $value) {

                        if ($value->program_id==$invoice_details->program_id) {

            ?>  <input type="text" class="form-control" name="program" id="program" value="<?=$value->program_name?>" readonly> <?php
                        }else{

                        }
                        
                      }
                    }else{
            ?> <input type="text" class="form-control" name="program" id="program" value="No Program Assigned" readonly> <?php
          }

                     ?>

                   </div>
                  </div>
                </div>

                           
              </div>
                
              <div class="row">
                <div class="col-md-6">
                  <div class="prioritydivcontainer">
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
                          <?php 
                          if(!empty($invoice_details->jobs)){
                            $n=1;
                            foreach($invoice_details->jobs as $k=>$v){
                            
                            echo '<tr id="trid'.$n.'"><td class="index" >'.$n.'</td><td>'.$v['job_name'].'</td><td><input type="text" class="form-control job-cost" name="jobcost['.$v['job_id'].']" placeholder="Enter Cost" value="'.$v['job_cost'].'"></td><td class="removeclass" id="'.$n.'" optionValueRemove="'.$v['job_id'].'" optionTextRemove="'.$v['job_name'].'"><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></i></a></li></ul></td></tr>';
                              $n++;
                            }
                          }else{
                            
                            echo '<tr id="trid1"><td class="index" >1</td><td>'.$invoice_details->job_name.'</td><td><input type="text" class="form-control job-cost" name="jobcost['.$invoice_details->job_id.']" placeholder="Enter Cost" value="'.$invoice_details->cost.'"></td><td class="removeclass" id="1" optionValueRemove="'.$invoice_details->job_id.'" optionTextRemove="'.$invoice_details->job_name.'"><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></i></a></li></ul></td></tr>';
                          }
                          ?> 
                        </tbody>
                      </table>
                    </div>                    
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group"  style="margin-bottom: 35px;" >
                    <label class="control-label col-lg-3">Sent Status</label>
                    <div class="col-lg-9" style="padding-left: 4px;padding-right: 4px;">
                      <select class="form-control" name="sent_status" id="invoice_sent_status" >
                          <option value="0"  <?php if ($invoice_details->status==0) { echo "Selected";} ?> >Unsent</option>     
                          <option value="1"  <?php if ($invoice_details->status==1) { echo "Selected";} ?> >Sent</option>     
                          <option value="2"  <?php if ($invoice_details->status==2) { echo "Selected";} ?> >Opened</option>     
                      </select>
                    </div>
                  </div>
                  <div class="form-group"  style="margin-bottom: 35px;" >
                    <label class="control-label col-lg-3">Status</label>
                    <div class="col-lg-9" style="padding-left: 4px;padding-right: 4px;">
                     
                      <?php
                      if($invoice_details->payment_status==0){
                        ?>
                        <select class="form-control" name="payment_status" id="invoice_payment_status" >
                          <option value="0"  <?php if ($invoice_details->payment_status==0) { echo "Selected";} ?> >Unpaid</option>     
                          <option value="3"  <?php if ($invoice_details->payment_status==3) { echo "Selected";} ?> >Past Due</option>     
                          </select>
                      <?php } else if($invoice_details->payment_status==1) {?>
                        <select class="form-control" name="payment_status" id="invoice_payment_status" >
                        <option value="1"  <?php if ($invoice_details->payment_status==1) { echo "Selected";} ?> >Partial</option>     
                          </select>
                      <?php  } else if($invoice_details->payment_status==2) {?>
                        <select class="form-control" name="payment_status" id="invoice_payment_status" >
                        <option value="2"  <?php if ($invoice_details->payment_status==2) { echo "Selected";} ?> >Paid</option>  
                        </select>
                      <?php } else if($invoice_details->payment_status==3) { ?>
                        <select class="form-control" name="payment_status" id="invoice_payment_status" >
                          <option value="3"  <?php if ($invoice_details->payment_status==3) { echo "Selected";} ?> >Past Due</option>    
                          </select>
                      <?php } else if ($invoice_details->payment_status==4) { ?>
                        <select class="form-control" name="payment_status" id="invoice_payment_status" >
                        <option value="4"  <?php if ($invoice_details->payment_status==4) { echo "Selected";} ?> >Refunded</option>
                        </select>
                      <?php } ?>

                     
                    </div>
                  </div>
                  <?php 
                  $total_tax_amount = 0;
                  if ($all_sales_tax) {
                     
                    $total_tax_amount =   array_sum(array_column($all_sales_tax, 'tax_amount'));
                    $total_tax_amount =  number_format($total_tax_amount,2, '.', '');
                  } 
                 $total_amount =  $invoice_details->cost  +  $total_tax_amount ;
                 $total_amount = $total_actual_cost + $invoice_details->late_fee;
                 $due_amount = $total_amount - $invoice_details->partial_payment; 
                 $due_amount = $due_amount < 0 ? 0 : $due_amount;
                 ?>
                  <div class="form-group"  style="margin-bottom: 35px;" >
                    <label class="control-label col-lg-3">Payment Details</label>
                    <div class="col-lg-9" style="padding-left: 4px;padding-right: 4px;">
                      <div class="col-md-6">
                        <label class="control-label col-lg-5">Payments:</label>
                        <?php
                
                          if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {
                            foreach ($all_invoice_partials as $key=>$invoice_partial_payment) {

                        ?>
                        
                        <input type="text" class="form-control" name="new_partial_payment" placeholder="Enter Partial payment" value="<?= number_format($invoice_partial_payment->payment_amount,2, '.', '') ?>" style="margin-left: -4px; margin-bottom: 5px;">
                        <div style="height: 10px;"></div>
                        <?php
                            }
                          }

                        ?>
                      </div>
                      <div class="col-md-6">
                        <label class="control-label col-lg-9"> Payments Total:</label>
                        <input type="text" class="form-control" name="new_partial_payment" placeholder="Enter Partial payment" value="<?= number_format($invoice_details->partial_payment,2, '.', '') ?>" style="margin-left: -4px; margin-bottom: 5px;">
                        <div style="height: 10px;"></div>
                        <?php if($invoice_details->late_fee > 0): ?>
                        <label class="control-label col-lg-9">Late Fee:</label>
                        <input type="text" disabled class="form-control" name="late_fee" value="<?= number_format($invoice_details->late_fee,2, '.', '') ?>" style="margin-left: -4px; margin-bottom: 5px;">
                        <div style="height: 10px;"></div>
                      <?php endif; ?>
                      <?php if($invoice_details->credit_amount > 0): ?>
                        <label class="control-label col-lg-9">Available Credit Amount:</label>
                        <input type="text" disabled class="form-control" name="credit_amount" value="<?= number_format($invoice_details->credit_amount,2, '.', '') ?>" style="margin-left: -4px; margin-bottom: 5px;">
                        <div style="height: 10px;"></div>
                        <?php endif; ?>
                        <label class="control-label col-lg-9">Total Due:</label>
                        <input 
                          type="text" 
                          class="form-control" 
                          name="new_partial_payment"
                          id="due_balance" 
                          placeholder="Enter Partial payment" 
                          value="<?php echo ($invoice_details->payment_status==4 || $invoice_details->payment_status==2) ?number_format(0,2, '.', '') : number_format($due_amount ,2, '.', '')   ?>" 
                          style="margin-left: -4px; margin-bottom: 5px;">
                          <div style="height: 10px;"></div>
                          <label class="control-label col-lg-9">Refunded Amount:</label>
                        <input 
                          type="text" 
                          class="form-control" 
                          name="new_partial_payment" 
                          placeholder="Refunded Amount" 
                          value="<?= number_format( $invoice_details->refund_amount_total,2, '.', '') ?>" 
                          style="margin-left: -4px; margin-bottom: 5px;">
                          <div style="height: 10px;"></div>
                      </div>
                       
                    </div>
                    <div class="text-right">
                    <a data-toggle="modal" data-target="#modal_theme_primary_paid_payment"><button class="btn  btn-paid-status" type="button" id="total_due_payment" class="pay_button">Pay</button></a>
                    <a data-toggle="modal" data-target="#modal_theme_primary_partial_payment"><button class="btn btn-success" type="button" id="partial_payment" class="partial_button">Partial Payment</button></a>
                    <a data-toggle="modal" data-target="#modal_theme_primary_partial_payment_total_full_full" ><button class="btn btn-warning" type="button" id="full_refund" class="refund_button">Refund</button></a>
                    </div>
                  </div>

                </div>
              </div>


               <div class="row">
                <div class="col-md-6">
                  <div class="form-group"  style="margin-bottom: 35px;">
                    <label class="control-label col-lg-3">Subtotal Cost</label>
                    <div class="col-lg-9" style="padding-left: 4px;padding-right: 1px;">
                      <div class="input-group">  
                        <span class="input-group-btn">
                              <span class="btn btn-success">$</span>
                        </span>
                        <input type="text" class="form-control" name="cost" id="cost" placeholder="Enter Cost" value="<?=  number_format($invoice_details->cost,2, '.', '') ?>" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                 
              </div>
               
              <div class="row">

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Sales Tax</label>
                    <div class="col-lg-9" style="padding-left: 0;padding-right: 0;" >
                        <div class="input-group">  
                          <span class="input-group-btn">
                              <span class="btn btn-success">$</span>
                          </span>
                          <input type="text" class="form-control" name="sales_tax" id="sales_tax" placeholder="Enter Partial payment" value="<?php if (isset($invoice_total_tax) && !empty($invoice_total_tax)) { echo number_format($invoice_total_tax,2, '.', ''); } else { echo 0; } ?>" readonly >     
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group"  style="margin-bottom: 35px;">
                    <label class="control-label col-lg-3 new_label_control">Total Cost  </label>
                    <div class="col-lg-9" style="padding-left: 4px;padding-right: 1px;">
                      <div class="input-group">  
                        <span class="input-group-btn">
                              <span class="btn btn-success">$</span>
                        </span>
                          <input type="text" class="form-control" name="over_all_total" id="over_all_total" placeholder="Enter Cost" value="<?=  number_format($total_amount,2, '.', '') ?>" readonly  
                          >
                          
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <br>

               <!-- COUPON DISCOUNT ROW -->
               <?php
                  if (isset($total_coupon_discount) && !empty($total_coupon_discount)) {
                ?>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Total Coupon Discount</label>
                    <div class="col-lg-9" style="padding-left: 0;padding-right: 0;" >
                        <div class="input-group">  
                          <span class="input-group-btn">
                              <span class="btn btn-success">$</span>
                          </span>
                          <input type="text" class="form-control" name="" id="" placeholder="Enter Partial payment" value="<?= number_format($total_coupon_discount,2, '.', '') ?>" readonly >     
                        </div>
                    </div>
                  </div>
                </div>
              </div>

                <?php } ?>

                  <br>
                  <?php
             
                  if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {
                      echo '<label class="control-label col-lg-3" id="recorded_payment_label" style="position: absolute; margin-left: -10px; width: 100px;">Recorded Payments</label>';
                  }
              
                  foreach ($all_invoice_partials as $key=>$invoice_partial_payment) {

                    ?>
                    <div class="row" id="partial_payment_list_row<?= $invoice_partial_payment->payment_invoice_logs_id ?>">
                      <div class="col-md-6">
                        <div class="form-group">
                        <?php
                            if ($key == 0) {
                                echo '<label class="control-label col-lg-3"></label>';
                            } else {
                                echo '<label class="control-label col-lg-3"></label>';
                            }
                        ?>
                          <div class="col-lg-9" style="padding-left: 0;padding-right: 0;" >
                            <div class="input-group">  
                              <span class="input-group-btn">
                                  <span class="btn btn-success">$</span>
                              </span>
                              <input 
                              type="text" 
                              style="width: 20%;" 
                              class="form-control" 
                              id="partial_payment_list_item_<?= $invoice_partial_payment->payment_invoice_logs_id ?>" payment_method="<?= $invoice_partial_payment->payment_method ?>" 
                              value="<?= number_format($invoice_partial_payment->payment_amount,2, '.', '') ?>" readonly >
                              <input 
                              type="text" 
                              style="width: 50%; text-align:left !important;"
                              class="form-control" 
                              id="partial_payment_list_item_<?= $invoice_partial_payment->payment_invoice_logs_id ?>" payment_method="<?= $invoice_partial_payment->payment_method ?>" 
                              value="
                                <?php
                                  switch ($invoice_partial_payment->payment_method) {
                                    case "1":
                                      echo 'Check: '.$invoice_partial_payment->check_number;
                                      break;
                                    case "2":
                                      echo 'Card: '.$invoice_partial_payment->cc_number;
                                      break;
                                    case "3":
                                      echo 'Other: '.$invoice_partial_payment->payment_note;
                                      break;
                                      case "4":
                                        echo 'Card: '.$invoice_partial_payment->cc_number;
                                        break;
                                      case "5":
                                        echo 'Credit';
                                        break;
                                    case "0":
                                      echo 'Cash';
                                      break;
                                    default:
                                      echo $invoice_partial_payment->payment_method;
                                  }

                                ?>
                                " readonly >
                              <?php
                                if($invoice_partial_payment->payment_applied != 0){

                              ?>
                              <a 
                              onclick="remove_partial_payment_log(<?=$invoice_partial_payment->payment_invoice_logs_id?>)" style="position: absolute; margin-top: 5px; margin-left: 100px; z-index: 999;">
                                <i class="icon-trash text-warning" style="font-size:25px;"></i>
                              </a>
                              <a 
                              data-toggle="modal" 
                              data-target="#modal_theme_primary_partial_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>"  
                              style="position: absolute; margin-top: 0px; margin-left: 10px; z-index: 999;">
                                <button class="btn btn-warning" type="button">
                                  Refund
                                </button>
                              </a>
                              <?php
                              }
                              ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                   

                    <?php
                  }

                    if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {
                      echo "<br>";
                    }
                  ?>

              <input type="hidden" name="removed_payment_log_ids" value="" id="removed_payment_log_ids">

              <script>
                var total_num_payment_logs_init = <?= $num_all_invoice_partials ?>;
                var removed_payment_ids = [];
                var invoice_id = <?= $invoice_details->invoice_id ?>;
                function remove_partial_payment_log(payment_log_id) {
                  swal({
                    title: 'Are you sure?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#009402',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                  }).then(function(result) {
                      if (result.value) {
                        var doc_query = "#partial_payment_list_row"+payment_log_id;
                        $(doc_query).css('display', 'none');
                        total_num_payment_logs_init -= 1;
                        removed_payment_ids.push(payment_log_id);
                        $('#removed_payment_log_ids').val(removed_payment_ids);
                        // console.log(removed_payment_ids);
                        if (total_num_payment_logs_init <= 0) {
                          $("#recorded_payment_label").css('display', 'none');
                        }

                          $.ajax({
                            type: 'POST',
                            url: '<?php echo base_url(); ?>admin/Invoices/deletePaymentLog',
                            data: {
                              payment_log_id: payment_log_id,
                                  invoice_id: invoice_id
                              },
                            success: function (data) {
                              // console.log(invoice_id);
                                $("#loading").css("display","none");
                                swal(
                                    'Payment',
                                    'Successfully Deleted',
                                    'success'
                                    ).then(function() {
                                      console.log(data);
                                      location.reload();
                                    });
                            },
                            error: function (data) {
                                console.log(data);
                            }
                          });

                      }
                  });

                }
               
              </script>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Add New Partial Payment</label>
                    <div id="add_new_partial_input" class="col-lg-4" style="padding-left: 5px;padding-right: 0; display: none;" >

                          <input type="text" class="form-control" name="new_partial_payment" placeholder="Enter Partial payment" value="" style="margin-left: -4px; margin-bottom: 5px;">
                          <select id="payment_method" class="bootstrap-select form-control" name="payment_method" style="width: 95%; margin-left: 0 !important;">
                            <option value="">Select Payment Method</option> 
                            <option value="0">Cash</option>
                            <option value="1">Check</option>
                            <option value="2">Credit Card</option>
                            <option value="3">Other (QBO, Cash, Paypal, etc)</option>
                          </select>
                          <input id="payment_info" type="text" class="form-control" name="payment_info" placeholder="Enter #" value="" style="margin-left: -4px; margin-bottom: 5px;">

                    </div>
                    <a onclick="show_new_partial_input()" id="add_new_partial_btn"><i class="icon-add text-success" style="font-size:25px;"></i></a>
                  </div>
                </div> 
              </div>

              <script>
                function show_new_partial_input() {
                  $('#add_new_partial_btn').css('display', 'none');
                  $('#add_new_partial_input').css('display', 'unset');
                }
               // function for adding payment method extra info etc... (check #, last 4 digit of credit card, other notes) ####
                $(function() {
                  $('#payment_info').hide(); 
                  $('#payment_method').change(function(){
                      if($('#payment_method').val() == '1') {
                          $('#payment_info').show(); 
                          $('#payment_info').attr('placeholder', 'Enter Check number'); 
                      } else if ($('#payment_method').val() == '2') {
                          $('#payment_info').show();
                          $('#payment_info').attr('placeholder', 'Enter Last 4 Digits'); 
                      } else if ($('#payment_method').val() == '3') {
                        $('#payment_info').show();
                        $('#payment_info').attr('placeholder', 'Enter Payment Note');
                      } else {
                        $('#payment_info').hide();
                      }
                  });
              });

              </script>
    
                <br>
                
             <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-lg-3">Payment Total</label>


                        <?php 
                          if (isset($invoice_partial_payment) && !empty($invoice_partial_payment)) { 
                            ?>
                              <div class="col-lg-9" style="padding-left: 0;padding-right: 0;" >
                                  <div class="input-group">  
                                    <span class="input-group-btn">
                                        <span class="btn btn-success">$</span>
                                    </span>
                                    <input type="text" class="form-control" name="partial_payment_new" id="partial_payment_new" placeholder="Enter Partial payment" value="<?= number_format($invoice_details->partial_payment,2, '.', '') ?>" style="width: 70%;" readonly >

                                  </div>
                              </div>
                              <?php } else { ?>
                                <div class="col-lg-9" style="padding-left: 0;padding-right: 0;" >
                                    <div class="input-group">  
                                      <span class="input-group-btn">
                                          <span class="btn btn-success">$</span>
                                      </span>
                                      <input type="text" class="form-control" name="partial_payment_new" id="partial_payment_new" placeholder="Enter Partial payment" value="<?= number_format($invoice_details->partial_payment,2, '.', '') ?>" readonly >
                                    </div>
                                </div>
                            <?php 
                          } 
                        ?>

                       
                      </div>
                    </div>               
                    
                    <div class="col-md-6" style="padding-left: 0;padding-right: 0;">
                      <div class="form-group">
                        <label class="control-label col-lg-3">Balance Due </label>
                        <div class="col-lg-9" style="padding-left: 0;padding-right: 0;">

                          <div class="input-group">  
                            <span class="input-group-btn">
                                <span class="btn btn-success">$</span>
                            </span>                                  
                              <input type="text" class="form-control" name="balance_due" id="balance_due" readonly="" placeholder="Enter Partial payment"  value="<?= ($invoice_details->payment_status==4 || $invoice_details->payment_status==2) ?number_format(0,2, '.', '') : number_format($due_amount - $invoice_details->refund_amount_total,2, '.', '') 
                              ?>"  >
                          </div>
                        </div>
                      </div>
                    </div>
                </div>

 
              <div class="row">
                  <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label col-lg-3">Notes</label>
                    <div class="col-lg-9" style="padding-left: 4px;padding-right: 0px;">
                      <textarea rows="10" class="form-control" name="notes" placeholder="Enter Notes"  ><?= $invoice_details->notes ?></textarea>
                            
                    </div>
                  </div>
                </div>


                  <div class="col-md-6">
                        <div class="table-responsive  pre-scrollable" style="display: <?=  $all_sales_tax ? 'block' : 'none'  ?>;" >
                         <table class="table table-bordered">    
                              <thead>  
                                  <tr>
                                      <th>Sales Tax</th>                                                 
                                      <th>Percentage</th>                                                 
                                      <th>Amount</th>                                                 
                                  </tr>             
                              </thead>
                              <tbody id="tbl_body" >
                                <?php foreach ($all_sales_tax as $key => $value) { ?>
                                    <tr>
                                      <td><?= 'Sales Tax: '.$value['tax_name'] ?></td>
                                      <td><?= $value['tax_value'].'%' ?></td>
                                      <td><?= number_format($value['tax_amount'], 2) ?></td>
                                    </tr>
                                <?php } ?>
                              </tbody>
                         </table>
                       </div>                      
                  </div>

                  <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="<?= $total_tax_amount ?>"  > 
                  <textarea  id="sales_tax_Tbl" name="sales_tax_Tbl" style="display: none;" ><?= json_encode($all_sales_tax)  ?></textarea>
                
              </div>


              <div class="row">
               
               <div class="col-md-6">
                  <div class="form-group"  style="margin-bottom: 35px;">
                    <label class="control-label col-lg-3">Add Coupons</label>
                    <div class="col-lg-9"  style="padding-left: 9px;padding-right: 4px;">
                      <select class="bootstrap-select form-control" data-live-search="true" name="coupon_id" id="coupon_list">
                      <option value="" >Select Coupons </option>
                      <?php
                        if (!empty($customer_one_time_discounts)) {
                          foreach ($customer_one_time_discounts as $value) {

                            // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                            $expiration_pass = true;
                            if ($value->expiration_date != "0000-00-00 00:00:00") {
                                $coupon_expiration_date = strtotime( $value->expiration_date );

                                $now = time();
                                if($coupon_expiration_date < $now) {
                                      $expiration_pass = false;
                                      $expiration_pass_global = false;
                                }
                            }

                            if ($expiration_pass == true) {
                              echo '<option value="'.$value->coupon_id.'" data-discount="'.$value->amount.'" data-discount-type="'.$value->amount_calculation.'" data-coupon-type="'.$value->type.'">'.$value->code.'</option>';
                            }
                          }
                        }
                      ?>
                      </select>
                      <input type="hidden" name="assign_coupons_csv" id="coupon_id_order_array" value="">
                   </div>
                  </div>
                </div>
                           
              </div>


               <div class="row">

                <div class="col-md-6">
                    <div class="prioritydivcontainer">
                     <div  class="table-responsive  pre-scrollable" style="min-height: 0px !important">
                       <table  class="table table-bordered" id="couponListTable" >
                        <thead>
                          <tr>
                            <th>Coupon Name</th>
                            <th>Discount</th>
                            <th>Type</th>
                            <th>Remove</th>
                          </tr>
                        </thead>
                        <tbody class="prioritytbody2" >
                          <?php 
                            foreach($exising_coupon_invoice_data as $coupon){?>

                              <tr id="coupon_list_item_<?php echo $coupon->coupon_id ?>">
                                <td><?= $coupon->coupon_code ?></td>
                                <td><?php
                                    $coupon_amount_display = 0;
                                    if ($coupon->coupon_amount_calculation == 1) {
                                        $coupon_amount_display = $coupon->coupon_amount . '%';
                                    } else {
                                        $coupon_amount_display = '$' . $coupon->coupon_amount;
                                    }
                                    echo $coupon_amount_display;
                                ?></td>
                                <td><?php
                                    if ($coupon->coupon_type == 1) {
                                        echo "customer-wide";
                                    } else {
                                        echo "invoice";
                                    }
                                ?></td>
                                <td class="removeclass2" data-couponid="<?php echo $coupon->coupon_id ?>" couponRedrawName="<?php echo $coupon->coupon_code ?>" couponRedrawDiscount="<?php echo $coupon_amount_display ?>" couponRedrawType="<?php echo $coupon->coupon_type ?>" optionValueRemove="<?php echo $coupon->coupon_id ?>" couponRedrawOGDiscount="<?php echo $coupon->coupon_amount ?>" couponRedrawCALCType="<?php echo $coupon->coupon_type ?>" couponRedrawCouponType="<?php echo $coupon->coupon_amount_calculation ?>" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></li></i></a></ul></td>
                              </tr>

                              <?php
                            }?> 
                        </tbody>
                       </table>
                     </div>
                    </div>
                </div>

              </div>

              <h1>Payment Logs</h1>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Sr. No.</th>
                    <th>Log</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Created By</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach($AllInvoiceLogs as $Index => $LogInvs){
                  ?>

                    <tr>
                      <td><?php echo ($Index + 1) ?></td>
                      <td><?php echo $LogInvs->action ?></td>
                      <td>$<?php echo $LogInvs->amount ?></td>
                      <td><?php echo date("d F, Y h:i A", strtotime($LogInvs->created_at))?></td>
                      <td><?php echo $LogInvs->user_first_name." ".$LogInvs->user_last_name ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                </tbody>
              </table>


             </fieldset>

                <div class="text-right mt-15">
                  <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i></button>
                </div>
              </form>
            </div>
          </div>
          <!-- /form horizontal -->
          
      <!-- Main Modal - Recorded Payments section for partial payments -->
    <?php

if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {

}

foreach ($all_invoice_partials as $key=>$invoice_partial_payment) {
?>

<div id="modal_theme_primary_partial_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h6 class="modal-title">Select a Refund Payment</h6>
      </div>
      <form 
        id="refund_payment_form_<?=$invoice_partial_payment->payment_invoice_logs_id?>" 
        action="<?= base_url('admin/Invoices/refundPayment/') ?>"
        name="refund_partial_full" class="partial_class"   method="post" style="padding: 10px;" >
        <input type="hidden" name = "customer_id" value = "<?= $invoice_details->customer_id ?>">
        <input type="hidden" name = "invoice_id" value = "<?= $invoice_details->invoice_id ?>">
        <input type="hidden" name = "payment_log_id" value="<?=$invoice_partial_payment->payment_invoice_logs_id?>">
          <div class="modal-body">
              <div class="form-group">
                  <div class="row">
                      <div class="col-sm-6 col-md-6">
                        <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">
                          Refund 
                        </label>
                        <select id="refund_type_<?=$invoice_partial_payment->payment_invoice_logs_id?>" class="form-control" name="payment_type" style="border: 1px solid #12689b; margin-top: 5px;">
                          <option value="">Select Full or Partial Refund</option>
                          <option value="partial">Partial</option>
                          <option value="full_partial">Full</option>
                        </select>
                        <div class="refund_error" id="error_1">
                      </div>
                      </div>
                      <div class="col-sm-6 col-md-6">
                        <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">
                        Amount
                        </label>
                        <input type="text" class="form-control" id="refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>" name="partial_payment" placeholder="Enter Refund" value="" >
                        <h4 class="refund_error" id="error_2"></h4>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-6 col-md-6">
                        <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">
                        How to Issue Refund:
                        </label>
                        <div class="multi-select-full">
                        <select id="refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>" class="form-control required" name="payment_method" style="border: 1px solid #12689b; margin-top: 5px; font-size: 15px;">
                          <option value="">Select Payment Type</option>
                          <option value="2">Credit Card</option>
                        <option value="1">Check</option>
                        <option value="3">Other (QBO, Cash, Paypal, etc)</option>
                        </select>
                      </div>
                      </div>
                      <div class="refund_payment_error" >
                      </div>
                      <div class="col-sm-6 col-md-6" id="cc_number_<?=$invoice_partial_payment->payment_invoice_logs_id?>">
                        <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Enter Last 4 Digits of Card:</label>
                        <input type="text" class="form-control" id="refund_cc" name="cc_number" placeholder="Enter Last 4 Digits" value="" disabled=true>
                      </div>
                      <div class="col-sm-6 col-md-6" id="check_number_<?=$invoice_partial_payment->payment_invoice_logs_id?>">
                        <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Enter Check Number:</label>
                        <input type="text" class="form-control" id="refund_amount_input_full_full" name="check_number" placeholder="Enter Check #" value="" disabled=true>
                      </div>
                      <div class="col-sm-6 col-md-6" id="other_<?=$invoice_partial_payment->payment_invoice_logs_id?>">
                        <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Enter Notes:</label>
                        <input type="text" class="form-control" id="refund_other" name="other" placeholder="Enter Notes" value="" disabled=true>
                      </div> 
                    </div>
                  </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-success" id='refund_button_<?=$invoice_partial_payment->payment_invoice_logs_id?>' >Refund</button>
                </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $('#refund_type_<?=$invoice_partial_payment->payment_invoice_logs_id?>').on('change', function() {
    if ($(this).val() == 'partial') {
      $("#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>").prop('disabled', false);
      $("#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>").prop('readonly', false);
      $("#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>").val('');
    } else if ($(this).val() == 'full_partial') {
      $("#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>").prop('readonly', true);
      $("#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>").val('<?=($invoice_partial_payment->payment_applied)?>');
    } 
    else {
    $("#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>").prop('disabled', true);
    $("#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>").val('');
  }
  });

  // Check to see is cc# is needed
  $(function() {
      $('#cc_number_<?=$invoice_partial_payment->payment_invoice_logs_id?>').hide(); 
      $('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').change(function(){
          if($('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').val() == '2') {
              $('#cc_number_<?=$invoice_partial_payment->payment_invoice_logs_id?>').show(); 
          } else {
              $('#cc_number_<?=$invoice_partial_payment->payment_invoice_logs_id?>').hide(); 
          } 
      });
  });
  // Check to see is check# is needed
  $(function() {
      $('#check_number_<?=$invoice_partial_payment->payment_invoice_logs_id?>').hide(); 
      $('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').change(function(){
          if($('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').val() == '1') {
              $('#check_number_<?=$invoice_partial_payment->payment_invoice_logs_id?>').show(); 
          } else {
              $('#check_number_<?=$invoice_partial_payment->payment_invoice_logs_id?>').hide(); 
          } 
      });
  });
  // Check to see notes on other 
  $(function() {
        $('#other_<?=$invoice_partial_payment->payment_invoice_logs_id?>').hide(); 
          $('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').change(function(){
              if($('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').val() == '3') {
                  $('#other_<?=$invoice_partial_payment->payment_invoice_logs_id?>').show(); 
                } else {
                    $('#other_<?=$invoice_partial_payment->payment_invoice_logs_id?>').hide(); 
                } 
              });
      });
</script>

<script>

    $('#refund_payment_form_<?=$invoice_partial_payment->payment_invoice_logs_id?>').submit(function(e) {
      var error_check = 0;
      var refund_type = $('#refund_type_<?=$invoice_partial_payment->payment_invoice_logs_id?>').val();
      var refund_payment = $('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').val();
      var full_partial = <?=$invoice_partial_payment->payment_applied?>;
      var actual_partial = $("#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>").val();
     
      if(full_partial){
        full_partial = parseFloat(full_partial);
        //console.log("Full partial = " + full_partial);
      }
      if(actual_partial){
        actual_partial = parseFloat(actual_partial);
        //console.log("Actual Partial = " + actual_partial);
      }
      

      var refund_amount= $('#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>').val();
      var cc_number = $('#cc_number_<?=$invoice_partial_payment->payment_invoice_logs_id?> input#refund_cc');
      var check_number = $('#check_number_<?=$invoice_partial_payment->payment_invoice_logs_id?> input#refund_amount_input_full_full');
      var other = $('#other_<?=$invoice_partial_payment->payment_invoice_logs_id?> input#refund_other');
     
      $(".error").remove();

     
        if (refund_type !== 'partial' && refund_type !== 'full_partial') {
          error_check = 1;
        $('#refund_type_<?=$invoice_partial_payment->payment_invoice_logs_id?>').after('<span class="error">Please select refund type</span>');
       
        } else {
          $('#refund_type_<?=$invoice_partial_payment->payment_invoice_logs_id?>').remove('.error');
        }
        if (refund_payment !== '1' && refund_payment !== '2' && refund_payment !== '3') {
          error_check = 1;
          e.preventDefault();
        $('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').after('<span class="error">Please select payment type</span>');
       
        } else {
          $('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').remove('.error');
        }

        if (cc_number.val().length < 1 && cc_number.prop('disabled') == false ) {
        error_check = 1;
          e.preventDefault();
          cc_number.after('<span class="error">Please add last 4 of card</span>');
          } else {
              cc_number.remove('.error');
            }

        var check_input = check_number.val();
        //console.log("check number is "+check_input);
      if (check_number.val().length < 1 && check_number.prop('disabled') == false ) {
        error_check = 1;
            e.preventDefault();
            check_number.after('<span class="error">Please add check number</span>');
          } else {
            
            check_number.remove('.error');
          }

      if (other.val().length < 1 && other.prop('disabled') == false ) {
        error_check = 1;
          e.preventDefault();
          other.after('<span class="error">Please add notes about payment</span>');
          } else {
              other.remove('.error');
            }

      if (refund_amount.length < 1) {
        error_check = 1;
        e.preventDefault();
        $('#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>').after('<span class="error">This field is required</span>');
      }
      if (actual_partial > full_partial){
        error_check = 1;
        e.preventDefault();
        $('#refund_amount_input_<?=$invoice_partial_payment->payment_invoice_logs_id?>').after('<span class="error">Refund amount can not exceed payment amount</span>');
      }
      if (error_check !== 1){
        refund_modal_main(<?=$invoice_partial_payment->payment_invoice_logs_id?>);
      }

    });

  $('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').on('change', function() {
    var cc_number = $('#cc_number_<?=$invoice_partial_payment->payment_invoice_logs_id?> input#refund_cc');
    var check_number = $('#check_number_<?=$invoice_partial_payment->payment_invoice_logs_id?> input#refund_amount_input_full_full');
    var other = $('#other_<?=$invoice_partial_payment->payment_invoice_logs_id?> input#refund_other');
      if ($('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').val() == '2') {
        
        cc_number.prop('disabled', false);
        check_number.prop('disabled', true);
        other.prop('disabled', true);
        cc_number.val('');

      } else if ($('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').val() == '1') {

        cc_number.prop('disabled', true);
        check_number.prop('disabled', false);
        other.prop('disabled', true);
        check_number.val('');

      } else if ($('#refund_payment_<?=$invoice_partial_payment->payment_invoice_logs_id?>').val() == '3') {

        cc_number.prop('disabled', true); 
        check_number.prop('disabled', true);
        other.prop('disabled', false);
        other.val('');

      } else {

        cc_number.prop('disabled', true);
        cc_number.val('');
        check_number.prop('disabled', true);
        check_number.val('');
        other.prop('disabled', true);
        other.val('');
        }
    });


  function refund_modal_main(payment_log_id){
   

    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission
    
    //var form_id = $(this).closest("form").attr('id');
     console.log(form);
    //$('#refund_button').prop('disabled', true);

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      success: function (data) {
        // console.log(invoice_id);
        if(data.success == 'true'){
          
          $("#loading").css("display","none");
          swal(
              'Refund',
              'Successfully Issued',
              'success'
              ).then(function() {
                console.log(data);
                location.reload();
              });

        }
          
      },
      error: function (data) {
          console.log(data);
      },
    });
  }
</script>

<?php
}
?>
<!-- Full Refund Modal -->
  <div id="modal_theme_primary_partial_payment_total_full_full" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h6 class="modal-title">Select a Refund Payment</h6>
        </div>

        <form 
          id="refund_payment_form_full_full" 
          action="<?= base_url('admin/Invoices/refundPayment/') ?>"
          name="refund_total_full" method="post" style="padding: 10px;" >
            <input type="hidden" name = "customer_id" value = "<?= $invoice_details->customer_id ?>">
            <input type="hidden" name = "invoice_id" value = "<?= $invoice_details->invoice_id ?>">
            <input type="hidden" name="payment_status" value="4">
            <input type="hidden" name = "payment_log_id" value="<?=$invoice_partial_payment->payment_invoice_logs_id?>"> 
              <div class="modal-body">
              <div class="form-group">
                  <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Refund </label>
                        <input type="text" class="form-control " id="refund_type_full_full" name="payment_type" placeholder="Full Refund" value="Full Refund" required>

                    </div>
                    <div class="col-sm-6 col-md-6">
                      <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Amount</label>
                      <input type="text" class="form-control" id="refund_amount_input_full_full" name="partial_payment" placeholder="Enter Amount" value="<?=number_format($invoice_details->partial_payment - $invoice_details->refund_amount_total,2)?>">
                    </div>
                  </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-sm-6 col-md-6"> 
                    <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">How to Issue Refund:</label>
                    <div class="multi-select-full">
                      <select id="refund_type_full_payment" class=" form-control" name="payment_method" style="border: 1px solid #12689b; margin-top: 5px; font-size: 15px;" >
                        <option value="">Select Payment Type</option>
                        <option value="2">Credit Card</option>
                        <option value="1">Check</option>
                        <option value="3">Other (QBO, Cash, Paypal, etc)</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6 col-md-6" id="cc_number_2">
                        <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Enter Last 4 Digits of Card:</label>
                        <input type="text" class="form-control" id="refund_cc" name="cc_number_2" placeholder="Enter Last 4 Digits" value="" >
                      </div> 
                  <div class="col-sm-6 col-md-6" id="check_number_2">
                    <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Enter Check #:</label>
                    <input type="text" class="form-control" id="refund_check_number" name="check_number_2" placeholder="Enter Check #" value="">
                  </div> 
                  <div class="col-sm-6 col-md-6" id="other_2">
                    <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Enter Notes:</label>
                    <input type="text" class="form-control" id="refund_other_2" name="other_2" placeholder="Enter Notes" value="">
                  </div> 
                </div>
              </div>
            <div class="modal-footer">

              <button type="submit" class="btn btn-warning" id="full_refund_modal"  >Refund</button>
            </div> 
          </div> 
        </form>
      </div>
    </div>
  </div>

  <script>
    $(function() {
      $('#cc_number_2').hide(); 
        $('#refund_type_full_payment').change(function(){
            if($('#refund_type_full_payment').val() == '2') {
                $('#cc_number_2').show(); 
              } else {
                  $('#cc_number_2').hide(); 
              } 
            });
    });
    $(function() {
      $('#check_number_2').hide(); 
        $('#refund_type_full_payment').change(function(){
            if($('#refund_type_full_payment').val() == '1') {
                $('#check_number_2').show(); 
              } else {
                  $('#check_number_2').hide(); 
              } 
            });
    });
    $(function() {
      $('#other_2').hide(); 
        $('#refund_type_full_payment').change(function(){
            if($('#refund_type_full_payment').val() == '3') {
                $('#other_2').show(); 
              } else {
                  $('#other_2').hide(); 
              } 
            });
    });
  </script>
<!-- Paid Modal -->
<div id="modal_theme_primary_paid_payment" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Pay Total Amount Due</h6>
      </div>
        <form id="add_paid_payment_form" action="<?= base_url('admin/Invoices/changePaymentStatus'); ?>" method="post" style="padding: 10px;" >
          
          <?php
          if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {
            ?>
            <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Recorded Payments:</label>
            
            <?php
            foreach($all_invoice_partials as $partial_instance) {
            ?>
              <input type="text" class="form-control" name="" placeholder="Enter Cost" value="<?= number_format($partial_instance->payment_amount,2, '.', '') ?>" style="margin-bottom: 5px;" readonly >

            <div style="height: 10px;"></div>

          <?php 
              } 
            }
          ?>
   
          <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Payments Total:</label>
          <input type="text" class="form-control" name="" placeholder="Enter Cost" value="<?= number_format($invoice_details->partial_payment,2, '.', '') ?>" style="margin-bottom: 5px;" readonly >
          <div style="height: 10px;"></div>

        
          <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Total Amount Due:</label>
          <input type="text" class="form-control" name="partial_payment" value="<?= number_format($due_amount + $invoice_details->late_fee,2, '.', '') ?>" readonly>
          <input type="hidden" name="invoice_id" value="<?= $invoice_details->invoice_id ?>">
          <input type="hidden" name="payment_status" value="2">
          <input type="hidden" name="total_due" value="<?= number_format($due_amount + $invoice_details->late_fee,2, '.', '') ?>">
          <div style="height: 10px;"></div>
          <select class="bootstrap-select form-control" name="payment_method" id="paid_modal_select" style="border: 1px solid #12689b; margin-top: 5px;">
            <option value="0">Select A Payment Method</option> 
            <option value="0">Cash</option>
            <option value="1">Check</option>
            <option value="2">Credit Card</option>
            <option value="3">Other</option>
          </select>
          <div style="height: 10px;"></div>
          <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Payment Info:</label>
          <input type="text" class="form-control" name="payment_info" id="payment_info" placeholder="" value="">

          <div style="height: 20px;"></div>
          <button type="submit" class="btn btn-paid-status">Pay</button>

        </form>
    </div>
  </div>
</div>
<!-- Partial Modal -->
<div id="modal_theme_primary_partial_payment" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Pay Partial Payment</h6>
      </div>
        <form id="add_partial_payment_form" action="<?= base_url('admin/Invoices/changePaymentStatus'); ?>" method="post" style="padding: 10px;" >
         
          <?php
          if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {
            ?>
            <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Recorded Payments:</label>
            
            <?php
            foreach($all_invoice_partials as $partial_instance) {
            ?>
              <input type="text" class="form-control" name="" placeholder="Enter Cost" value="<?= number_format($partial_instance->payment_amount,2, '.', '') ?>" style="margin-bottom: 5px;" readonly >

            <div style="height: 10px;"></div>

          <?php 
              } 
            }
          ?>
   
          <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Payments Total:</label>;
          <input type="text" class="form-control" name="" placeholder="Enter Cost" value="<?= number_format($invoice_details->partial_payment,2, '.', '') ?>" style="margin-bottom: 5px;" readonly >
          <div style="height: 10px;"></div>

        
          <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Add New Partial Payment:</label>
          <input type="text" class="form-control" name="partial_payment" value="" >
          <input type="hidden" name="invoice_id" value="<?= $invoice_details->invoice_id ?>">
          <input type="hidden" name="payment_status" value="1">
          <input type="hidden" name="total_due" value="<?= number_format($due_amount - $invoice_details->refund_amount_total,2, '.', '') ?>">
          <div style="height: 10px;"></div>
          <select class="bootstrap-select form-control" name="payment_method" id="partial_modal_select" style="border: 1px solid #12689b; margin-top: 5px;">
            <option value="0">Select A Payment Method</option> 
            <option value="0">Cash</option>
            <option value="1">Check</option>
            <option value="2">Credit Card</option>
            <option value="3">Other</option>
          </select>
          <div style="height: 10px;"></div>
          <label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Payment Info:</label>
          <input type="text" class="form-control" name="payment_info" id="payment_info" placeholder="" value="">

          <div style="height: 20px;"></div>
          <button type="submit" class="btn btn-success">Partial Payment</button>

        </form>
    </div>
  </div>
</div>

<script>

   $('#paid_modal_select').change(function() {
     if($(this).val() == "1"){
      $('input[name=payment_info]').prop('placeholder', "Add Check Number" );
     } else if ($(this).val() == "2"){
      $('input[name=payment_info]').prop('placeholder', "Add Last 4 Digits of Card Number" );
     } else if ($(this).val() == "3"){
      $('input[name=payment_info]').prop('placeholder', "Add Additional Payment Info" );
     } else if($(this).val() == "0"){
      $('input[name=payment_info]').prop('placeholder', "" );
     }
   
   });

    // AJAX paid payment form
  $('#add_paid_payment_form').submit(function(e) {
    e.preventDefault();
    $('#modal_theme_primary_paid_payment').css('display', 'none');
    $.ajax({
      type: "POST",
      url: "<?= base_url('admin/Invoices/changePaymentStatus'); ?>",
      data: $(this).serialize()
    }).done(function(data){
      $("#loading").css("display","none");
      
        if (data=='true') {
          swal(
            'Full Payment',
            'Added Successfully',
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


  })


   $('#partial_modal_select').change(function() {
     if($(this).val() == "1"){
      $('input[name=payment_info]').prop('placeholder', "Add Check Number" );
     } else if ($(this).val() == "2"){
      $('input[name=payment_info]').prop('placeholder', "Add Last 4 Digits of Card Number" );
     } else if ($(this).val() == "3"){
      $('input[name=payment_info]').prop('placeholder', "Add Additional Payment Info" );
     } else if($(this).val() == "0"){
      $('input[name=payment_info]').prop('placeholder', "" );
     }
   
   });

    // AJAX partial payment form
  $('#add_partial_payment_form').submit(function(e) {
    e.preventDefault();
    $('modal_theme_primary_partial_payment').css('display', 'none');
    $.ajax({
      type: "POST",
      url: "<?= base_url('admin/Invoices/changePaymentStatus'); ?>",
      data: $(this).serialize()
    }).done(function(data){
      $("#loading").css("display","none");
      
        if (data=='true') {
          swal(
            'Partial Payment',
            'Added Successfully',
            'success'
          ).then(function() {
            location.reload();
          });

        } else if (data=='set to paid') {
          swal(
            'Invoice set to paid',
            'Partial Payment exceeded total cost',
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


  })


$(document).ready(function() {

    console.log('Payment Status: onload ', $('#invoice_payment_status').val());

    var payment_status = $('#invoice_payment_status').val();
     console.log('This is th value selected: ', payment_status);
     if (payment_status == '0') {
      $("#full_refund").hide();
    }
    if (payment_status == '1') {
      $("#full_refund").hide();
    }
    if (payment_status == '2') {
      $("#partial_payment").hide();
      $("#total_due_payment").hide();
    }
    if (payment_status == '3') {
      $("#full_refund").hide();
    }
    if (payment_status == '4') {
      $("#partial_payment").hide();
      $("#total_due_payment").hide();
      $("#full_refund").hide();
    }
});

$('select[name=payment_status]').change(function() {
  console.log('Payment Status: onchange', this.value)
  if (this.value == '0') {
      $("#partial_payment").show()
      $("#total_due_payment").show()
      $("#full_refund").hide()
    }
  if (this.value == '1') {
      $("#partial_payment").show()
      $("#total_due_payment").show()
      $("#full_refund").show()
    }
  if (this.value == '2') {
      $("#partial_payment").hide()
      $("#total_due_payment").hide()
      $("#full_refund").show()
    }
  if (this.value == '3') {
      $("#partial_payment").show()
      $("#total_due_payment").show()
      $("#full_refund").hide()
    }
  if (this.value == '4') {
      $("#partial_payment").hide()
      $("#total_due_payment").hide()
      $("#full_refund").hide()
    }
});
$("select[name=payment_status] option:selected ").change(function(){
     if (this.value == '1') {
      $(".partial_button").show()
      $(".pay_button").hide()
      $(".refund_button").hide()
    }
  }).trigger("change");
$('.partial_button').click(function() {

  
});

$('.pay_button').click(function() {
  $("select").val('2');
});

$('.refund_button').click(function() {
  $("select").val('4');
});
</script>
  <!-- /Paid Modal -->

  </div>
<!-- /content area -->
<script type="text/javascript">
// SERVICE LIST TABLE 
   var selectedSortingValues = [];
   var selectedSortingTexts = [];
   var selectedValues = [];
   var selectedCosts = [];
   var selectedTexts = [];
   var optionValue = '';
   var optionText = '';
   var optionCost = '';
   var couponSelectedValues = [];
   var couponRedrawName = [];
   var couponRedrawDiscount = [];
   var couponRedrawType = [];
   var couponAllData = {};
   $n = 1;
//on load
  $(document).ready(function(){
    $('td.removeclass').each(function(){
       var id = $(this).attr('id');
       var optionValue = $(this).attr('optionValueRemove');
       var optionText = $(this).attr('optionTextRemove');
      
       selectedValues.push(optionValue);
       selectedTexts.push(optionText);
      $n = $n+1;
      $('#job_id_order_array').val(selectedValues);
    });
    $('td.removeclass2').each(function(){
       couponSelectedValues.push( $(this).attr('optionValueRemove') );
       couponRedrawName.push( $(this).attr('couponRedrawName') );
       couponRedrawDiscount.push( $(this).attr('couponRedrawDiscount') );
       couponRedrawType.push( $(this).attr('couponRedrawType') );
       couponAllData[$(this).attr('optionValueRemove')] = {
          'coupon_id': $(this).attr('optionValueRemove'),
          'coupon_code': $(this).attr('couponRedrawName'),
          'coupon_amount': $(this).attr('couponRedrawOGDiscount'),
          'coupon_amount_calculation': $(this).attr('couponRedrawCALCType'),
       };
      $('#coupon_id_order_array').val(JSON.stringify( couponSelectedValues ));
    });
  });
  
  
// ADD COUPON TO LIST
$(document).on("change","#coupon_list",function() { 
    optionValue = $(this).val();
    if (optionValue!='') {
     if ($.inArray(optionValue, couponSelectedValues)!='-1') {
      } else {

      $('.prioritydivcontainer').css("display","block");

      optionText = $("#coupon_list option:selected").text();

      var coupon_discount = $("#coupon_list option:selected").data('discount');
      var og_coupon_discount = coupon_discount;
      var coupon_discount_type = $("#coupon_list option:selected").data('discount-type');
      var coupon_name = $("#coupon_list option:selected").text();
      var coupon_type = $("#coupon_list option:selected").data('coupon-type');

      if (coupon_discount_type == 0) {
          var coupon_discount = "$" + coupon_discount;
      } else {
          var coupon_discount = coupon_discount + "%";
      }

      couponSelectedValues.push(optionValue);
      couponRedrawName.push( coupon_name );
      couponRedrawDiscount.push( coupon_discount );
      couponRedrawType.push( coupon_type );
      couponAllData[optionValue] = {
          'coupon_id': optionValue,
          'coupon_code': coupon_name,
          'coupon_amount': og_coupon_discount,
          'coupon_amount_calculation': coupon_discount_type,
      };
        
      var $row = $('<tr id="coupon_list_item_'+optionValue+'">'+
        '<td class="index" >'+optionText+'</td>'+
        '<td>'+coupon_discount+'</td>'+
        '<td>invoice</td>'+
        '<td class="removeclass2" data-couponid="'+optionValue+'" optionValueRemove="'+optionValue+'" optionTextRemove="" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></i></a></li></ul></td>' +
        '</tr>');

      $('.prioritytbody2:last').append($row);
     
      $('#coupon_id_order_array').val(JSON.stringify( couponSelectedValues ));
      }
   } 
});
// REMOVE COUPON FROM LIST
$(document).on("click",".removeclass2",function() {

  var id = $(this).data('couponid');
  var optionValueRemove = $(this).attr('optionValueRemove');
  var optionTextRemove = $(this).attr('optionTextRemove');
  
  // couponSelectedValues.splice($.inArray(optionValueRemove, couponSelectedValues),1);

  coupon_index = 0;
  for (let i = 0; i < couponSelectedValues.length; i++) {
      if (couponSelectedValues[i] == id) {
          coupon_index = i;
      }
  }
  couponSelectedValues.splice(coupon_index, 1);
  couponRedrawName.splice(coupon_index, 1);
  couponRedrawDiscount.splice(coupon_index, 1);
  couponRedrawType.splice(coupon_index, 1);
  delete couponAllData[id];
  

  $("#coupon_list_item_"+id).remove();
  $('#coupon_id_order_array').val(JSON.stringify( couponSelectedValues ));

  rearrangetable2();
});

function rearrangetable2() {
   var costs = [];
   $.each(couponSelectedValues, function(c, cost) {
     var optionCost = $('input[name="jobcost['+couponSelectedValues[c]+']"]').val();
     costs[c] = optionCost;
   });
    $('.prioritytbody2').empty();
     $n = 1;
     $.each(couponSelectedValues, function(i, item) {

        if (couponRedrawType[i] == 0) {
            coupon_type_display = 'invoice';
        } else {
            coupon_type_display = 'customer-wide';
        }

        var $row = $('<tr id="coupon_list_item_'+couponSelectedValues[i]+'">'+
        '<td class="index" >'+couponRedrawName[i]+'</td>'+
        '<td>'+couponRedrawDiscount[i]+'</td>'+
        '<td>'+coupon_type_display+'</td>'+
        '<td class="removeclass2" data-couponid="'+couponSelectedValues[i]+'" optionValueRemove="'+couponSelectedValues[i]+'" optionTextRemove="" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></i></a></li></ul></td>' +
        '</tr>');

        $('.prioritytbody2:last').append($row);

    $n = $n+1;        

    });
    return calcSubtotal();

}
  
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
        '<td><input type="text" class="form-control job-cost" name="jobcost['+optionValue+']" placeholder="Enter Cost" value="" ></td>'+       
        '<td class="removeclass" id="'+$n+'" optionValueRemove="'+optionValue+'" optionTextRemove="'+optionText+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></i></a></li></ul></td>'+
        '</tr>');    


      $('.prioritytbody:last').append($row);
      $n = $n+1;        
      $('#job_id_order_array').val(selectedValues);
      }
   } 
});
//CALC SUB TOTAL 
  $(document).on('keyup', '.job-cost', function (){
      return calcSubtotal();   
  });
function calcSubtotal(){
  var subtotal = 0;
  
   $('input.job-cost').each(function() {
    var jobcost = Number($(this).val());
    subtotal = subtotal + jobcost; 
   });
    subtotal = subtotal.toFixed(2);
  $('#cost').val(subtotal);
  
   manageBalance();  
} 
  
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
     var optionCost = $('input[name="jobcost['+selectedValues[c]+']"]').val();
     costs[c] = optionCost;
   });
    $('.prioritytbody').empty();
     $n = 1;
     $.each(selectedValues, function(i, item) {
          var $row = $('<tr id="trid'+$n+'">'+
          '<td class="index" >'+$n+'</td>'+
          '<td>'+selectedTexts[i]+'</td>'+
      '<td><input type="text" class="form-control job-cost" name="jobcost['+selectedValues[i]+']" placeholder="Enter Cost" value="'+costs[i]+'"></td>'+
          '<td class="removeclass" id="'+$n+'" optionValueRemove="'+selectedValues[i]+'" optionTextRemove="'+selectedTexts[i]+'" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"></i></a></li></ul></td>'+
          '</tr>');    

        $('.prioritytbody:last').append($row);
  
    $n = $n+1;        

    });
    return calcSubtotal();

  }



  var total_tax_amount = Number($('#total_tax_amount').val());
var sales_tax_Tbl = <?= json_encode($all_sales_tax)  ?>;
  
    $("#customer_id").change(function(){
        var customer_id = $(this).val();
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
              //alert(data);
              $("#customer_email").val( $.trim(data));
              
            });

            $('#property_id').trigger("change");


        });

    });





$(document).on("change","#property_id",function (argument) {
    var property_id =   $(this).val();

    salesTaxDetails(property_id);
})




function salesTaxDetails(property_id) {

    $.ajax({
         type: "GET",
         url: "<?= base_url('admin/Invoices/getSalesTaxDetails/')  ?>"+property_id,          
         dataType : "JSON",
      }).done(function(response){

        console.log(response);

            if (response.status==200) {
      
               sales_tax_Tbl = response.result;
               $('.pre-scrollable').css("display","block");
               manageBalance();

            } else {
              sales_tax_Tbl = [];

              $('.pre-scrollable').css("display","none");

               manageBalance();

            }
      });

}


 $('#cost').keyup(function (){
    manageBalance();   
 });

$('#partial_payment').keyup(function (){
    manageBalance();   
 });



$(document).on("change","#invoice_payment_status",function (argument) {
   var invoice_status =  $(this).val();  
   if (invoice_status==1) {
    $('.sales_tax_div').css('display', 'block');

   } else {
    $('.sales_tax_div').css('display', 'none');
   }

})

  function manageBalance(argument) {

         var cost = Number($('#cost').val());
        
         total_tax_amount = 0;

         $('#tbl_body').html('');
         $.each(sales_tax_Tbl, function( index, value ) {

             var new_tax_amount = ((cost * value.tax_value)/100).toFixed(2) ;
             total_tax_amount += Number(new_tax_amount);
             // alert(new_tax_amount);
             sales_tax_Tbl[index].tax_amount = new_tax_amount;

             

             $('#tbl_body').append('<tr><td>'+'Sales Tax: '+value.tax_name+'</td><td>'+value.tax_value+'%</td><td>'+new_tax_amount+'</td></tr>');
          });

        
         $('#sales_tax_Tbl').val(JSON.stringify(sales_tax_Tbl));

         $('#total_tax_amount').val(total_tax_amount);

         $('#sales_tax').val(total_tax_amount.toFixed(2));

         var partial_payment =  Number($('#partial_payment').val());
         var over_all_total = cost + total_tax_amount ;
        //  var over_all_total = cost;
 
         $('#over_all_total').val(over_all_total.toFixed(2));        
         var balance_due =   over_all_total-partial_payment;
             balance_due = balance_due < 0 ? 0 : balance_due;
         $('#balance_due').val( balance_due.toFixed(2) );
         $('#due_balance').val( balance_due.toFixed(2) );
      
  }



    function reassign() {
          $(".bootstrap-select").selectpicker('destroy');
          $('.bootstrap-select').selectpicker();
    }





</script>

