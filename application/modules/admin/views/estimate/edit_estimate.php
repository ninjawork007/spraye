<style type="text/css">
   /* #hidden_source {
    display: none;
  } */
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
   #loading-image {
   position: absolute;
   left: 50%;
   top: 50%;
   width: 10%;
   z-index: 100;
   }
   .btn-outline {
    background-color: transparent;
    color: inherit;
    transition: all .5s;
}

.btn-primary.btn-outline {
    color: #428bca;
}

.btn-success.btn-outline {
    color: #36c9c9;
}

.btn-info.btn-outline {
    color: #5bc0de;
}

.btn-warning.btn-outline {
    color: #f0ad4e;
}

.btn-danger.btn-outline {
    color: #d9534f;
}

.btn-primary.btn-outline:hover,
.btn-success.btn-outline:hover,
.btn-info.btn-outline:hover,
.btn-warning.btn-outline:hover,
.btn-danger.btn-outline:hover {
    color: #fff;
}
</style>
<!-- Content area -->
<div class="content form-pg ">
    <div id="loading" >
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
   </div>
   <!-- Form horizontal -->
   <div class="panel panel-flat">
      <div class="panel-heading">
         <h5 class="panel-title">
            <div class="form-group">
               <a href="<?= base_url('admin/Estimates') ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to All Estimates</a>
               &nbsp;&nbsp;&nbsp;&nbsp;
               <?php if($setting_details->signwell_api_key != "") { ?>
                    <button type="button" onclick="send_to_signwell()" class="btn btn-success btn-outline" id="send_signwell">Send through SignWell</button>
                <?php } ?>
            </div>
         </h5>
      </div>
      <br>
      
      <?php
         if($estimate_details->signwell_id != "") {
            $curl = curl_init();
            
            curl_setopt_array($curl, [
            CURLOPT_URL => "https://www.signwell.com/api/v1/documents/".$estimate_details->signwell_id."/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
               "X-Api-Key: ".$setting_details->signwell_api_key,
               "accept: application/json"
            ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            $signwell_object = json_decode($response);
         } else {
            $signwell_object = new stdClass();
         }
      ?>

      <div class="panel-body">
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
         <form class="form-horizontal" action="<?= base_url('admin/Estimates/editEstimateData/').$estimate_details->estimate_id  ?>" method="post" name="addestimate" enctype="multipart/form-data" >
            <input type='hidden' id='program_pricing' name='program_pricing' val='<?php echo $estimate_details->program_pricing; ?>' />
            <fieldset class="content-group">
               <?php if($estimate_details->signwell_id != "" && $signwell_object->message != "Not found" && $setting_details->signwell_api_key != "") { ?>
                  <div class="row ">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label col-lg-3">SignWell Status</label>
                           <div class="col-lg-9">
                              <input type="text" name="signwell_status" class="form-control"  readonly="true" id="signwell_status" value="<?= $signwell_object->status ?>" placeholder="SignWell Status"  >
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <?php
                              if($signwell_object->status == "Completed") {
                                 // if the document is finished we can get a link for the PDF showing the signature
                                 $curl = curl_init();

                                 curl_setopt_array($curl, [
                                 CURLOPT_URL => "https://www.signwell.com/api/v1/documents/".$estimate_details->signwell_id."/completed_pdf/?url_only=true",
                                 CURLOPT_RETURNTRANSFER => true,
                                 CURLOPT_ENCODING => "",
                                 CURLOPT_MAXREDIRS => 10,
                                 CURLOPT_TIMEOUT => 30,
                                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                 CURLOPT_CUSTOMREQUEST => "GET",
                                 CURLOPT_HTTPHEADER => [
                                    "X-Api-Key: ".$setting_details->signwell_api_key,
                                    "accept: application/json"
                                 ],
                                 ]);

                                 $response = curl_exec($curl);
                                 $err = curl_error($curl);
                                 curl_close($curl);
                                 $completed_pdf_object = json_decode($response);
                           ?>
                                 <label class="control-label col-lg-3">SignWell Completed PDF</label>
                                 <div class="col-lg-9">
                                    <a href='<?= $completed_pdf_object->file_url ?>' style='font-size: larger;' target='_new'><button type='button' class='btn btn-primary'>View PDF</button></a>
                                 </div>
                           <?php
                              }
                           ?>
                        </div>
                     </div>
                  </div>
               <?php } ?>
               <div class="row ">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Select Customer</label>
                        
                        <div class="col-lg-9 search_sel">
                           <select class="bootstrap-select form-control" data-live-search="true" name="customer_id" id="customer_id">
                              <option value="">Select any customer</option>
                              <?php if (!empty($customer_details)) {
                                 foreach ($customer_details as $key => $value) {

                              if ($estimate_details->customer_id==$value->customer_id) {
                                $selected ='selected';
                              } else {
                                $selected = '';
                              }

                                   echo '<option value="'.$value->customer_id.'" '.$selected.' >'.$value->first_name.' '.$value->last_name.'</option>';
                                 }
                                 } ?>
                           </select>
                        </div>

                        

                    </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group row">
                        <label class="control-label col-lg-3">Select Property Address</label>
                        <div class="col-lg-9 search_sel">
                           <select class="bootstrap-select form-control" data-live-search="true" name="property_id" id="property_id">


                       <?php 
                        if (!empty($property_details)) {

                          foreach ($property_details as $key => $value) {

                              if ($estimate_details->property_id==$value->property_id) {
                                $selected ='selected';
                              } else {
                                $selected = '';
                              }                            
                              echo '<option value="'.$value->property_id.'" '.$selected.' >'.$value->property_address.'</option>';
                          }
                     
                        }


                        ?>



                           </select>       
                        </div>
                        
                     



                     </div>
                  </div>
               </div>
               <div class="row">

                   <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Customer Email</label>
                        <div class="col-lg-9">
                           <input type="text" name="customer_email" class="form-control"  readonly="" id="customer_email" value="<?= $estimate_details->email ?>" placeholder="Customer Email"  >
                        </div>
                     </div>
                  </div>

                 
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Estimate Date</label>
                        <div class="col-lg-9">
                           <input type="text" name="estimate_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?= $estimate_details->estimate_date ?>" >
                        </div>
                     </div>
                  </div>

               </div>
              
               <div class="row invoice-form">

                   <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Program Name</label>
                        <div class="col-lg-9 multi-select-full">
                           <select class="multiselect-select-all-filtering-edit form-control" multiple="multiple" name="program_id_array[]" id="program_list">
                              <?php
                                $joined_program_id_array = $services_tied_to_program = array();
                                foreach($joined_programs as $jp) {
                                    $joined_program_id_array[] = $jp->program_id;
                                    $services_tied_to_program[] = $jp->service_id;
                                }
                                 if (!empty($program_details)) {
                                   foreach ($program_details as $key => $value) {
                                  
                                    if (in_array($value->program_id, $joined_program_id_array)) {
                                    
										 echo '<option value="'.$value->program_id.'" selected >'.$value->program_name.'</option>';
                                    } else {
                                      if($value->ad_hoc != 1){
										  echo '<option value="'.$value->program_id.'" >'.$value->program_name.'</option>'; 
									   }
                                    }
                                   }
                                 }
                                 
                                  ?>
                           </select>
                        </div>
                     </div>
                  </div>
				   <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Message to Customer</label>
                        <div class="col-lg-9">
                           <input type="text" class="form-control" name="notes" placeholder="Enter Message" value="<?= $estimate_details->notes ?>" > 
							<p>* this message will be sent to the customer</p>
                        </div>
                     </div>
                  </div>
				
				</div>
				<div class="row invoice-form">
               <div class="col-md-6">
					  <div class="form-group" id="service_select">
                     <label class="control-label col-lg-3">Add Service</label>
                     <div class="col-lg-9 multi-select-full">
                        <select class="multiselect-select-all-filtering-edit form-control" multiple="multiple" name="standalone_job_id_array[]" id="service_list" >
                           <?php 
                              if (!empty($service_details)) {
                                 foreach ($service_details as $key => $value) {
                                    if(in_array($value->job_id, $services_tied_to_program)) {
                                        echo '<option value="'.$value->job_id.'" selected >'.$value->job_name.'</option>';
                                    } else {
                                        echo '<option value="'.$value->job_id.'"  >'.$value->job_name.'</option>';
                                    }
                                 }
                              }
                              
                           ?>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-lg-3">Sales Rep</label>
              <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
                <select class=" form-control" name="sales_rep"  id="sales_rep"  value="<?php echo set_value('sales_rep') ?>">
                  <option value="">Select Sales Rep</option>
                  <?php 
                     if(!empty($users)){
                        foreach ($users as $value){
                        if ($estimate_details->sales_rep==$value->id) {
                           $selected ='selected';
                        } else {
                           $selected = '';
                        }  
                        echo '<option value="'.$value->id.'" '.$selected.' >'.$value->user_first_name.' '.$value->user_last_name.' </option>';
                        }
                     }
                  ?> 
                </select>
                  <span style="color:red;"><?php echo form_error('sales_rep'); ?></span>
              </div>
            </div>
          </div>
          <div class="row invoice-form" id='source_select_row_id'>

                   <div class="col-md-6">
                     <div class="form-group">
                        <label class="control-label col-lg-3">Source</label>
                        <div class="col-lg-9 search_sel">
                            <select class="bootstrap-select form-control" data-live-search="true" name="source" id="select_source">
                                <option value="">Select Source</option>
                                <?php foreach ($source_list as $value) : ?>
                                <?php 
                                     if ($estimate_details->source==$value->source_id) {
                                        $selected ='selected';
                                     } else {
                                        $selected = '';
                                     }
                                ?>
                                    <option value="<?= $value->source_id ?>" <?= $selected; ?>><?= $value->source_name ?></option>
                                <?php endforeach ?>
                           </select>
                        </div>
                     </div>
                  </div>
				   <div class="col-md-6">
                     
                  </div>
				
				</div>
            </div>
            <!-- <div class="row invoice-form">
               <div class="col-md-6">
                  <div class="form-group">
                        <label class="control-label col-lg-3">Property Status</label>
                     <div class="col-lg-9">
                        <select class="form-control" name="property_status" id="property_status" onchange="showDiv('hidden_source', this)">
                           <option value="">Select Any Status</option>
                           
                           <option value="2" <?php echo  $estimate_details->property_status == 2 ? 'selected' : '' ?> >Prospect</option>
                           <option value="1"<?php echo  $estimate_details->property_status == 1 ? 'selected' : '' ?> >Active</option>
                           
                        </select>
                     </div>
                  </div>
               </div>

               <div class="col-md-6" id="hidden_source">
                  <div class="form-group">
                     <label class="control-label col-lg-3">Source</label>
                     <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
                        <select class=" form-control" name="source"  id="source"  value="<?php echo set_value('source') ?>">
                        <option value="">Select Source</option>
                        <?php 
                        if(!empty($source_list)){
                           foreach ($source_list as $value){
                           if ($estimate_details->source == $value->source_id) {
                              $selected ='selected';
                           } else {
                              $selected = '';
                           }  
                           echo '<option value="'.$value->source_id.'" '.$selected.' >'.$value->source_name.' </option>';
                        }
                     }
                        ?>
                           
                        </select>
                        <span style="color:red;"><?php echo form_error('source'); ?></span>
                     </div>
                  </div>
               </div>
            </div> -->
         
            <script>
            function showDiv(divId, element){
              document.getElementById(divId).style.display = element.value == 2 ? 'block' : 'none';
            }
            if($("#property_status").val() == 2){
                $("#hidden_source").show();
              } else{
                $("#hidden_source").hide();
              }
          //   $(document).ready(function() {
          //     $('#prospect_status option').each(function() {
          //       if($(this).is(':selected')) {
          //         $('#hidden_source').show();
          //         }
          //     });
          // }
          </script>
       

        <!-- <div class="row invoice-form">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-lg-3">Sales Rep</label>
              <div class="multi-select-full col-lg-9  col-sm-10 col-xs-10">
                <select class=" form-control" name="sales_rep"  id="sales_rep"  value="<?php echo set_value('sales_rep') ?>">
                  <option value="">Select Sales Rep</option>
                  <?php foreach ($users as $value) : ?>
                      <option value="<?= $value->id ?>"><?= $value->user_first_name.' '.$value->user_last_name.' : '.$value->id ?></option>
                  <?php endforeach ?>
                </select>
                  <span style="color:red;"><?php echo form_error('sales_rep'); ?></span>
              </div>
            </div>
          </div>
        </div>
                -->
                


               <div class="row" >
                  <div class="col-md-6">

                    <div class="job-price-over-ride-container" style="display: block;">
                       <div  class="table-responsive  pre-scrollable">
                         <table  class="table table-bordered">    
                              <thead>  
                                  <tr>
                                            
                                      <th>Service Name</th>                                                 
                                      <th>Price Override</th>                                           
                                  </tr>             
                              </thead>
                              <tbody class="priceoverridetbody">
                                
                                <?php if($price_override_details) { foreach ($price_override_details as $key => $value) {

                                   
                                                $keyIds[] = array(
                                                    'job_id' => $value->job_id, 
                                                    'price_override' => $value->price_override,
                                                    'is_price_override_set'  => $value->is_price_override_set,
                                                    'program_id' => $value->program_id
                                                );  


                                                
                                                $price_override = (isset($value->is_price_override_set) && $value->is_price_override_set == 1) ? floatval($value->price_override) : '' ;  


                                    echo    '<tr id="tridjob'.$value->job_id.'">'.
                                                    '<td>'.$value->job_name.'</td>'.
                                                    '<td> <input type="number" min="0" name="tmp'.$value->job_id.'" value="'.$price_override.'"  class="inpcl form-control" job_id="'.$value->job_id.'" program_id="'.$value->program_id.'" ></td>'.  
                                            '</tr>'
                                    
                                ?> 



                                <?php  } } else {

                                      $keyIds= array();

                                 ?>
                                  <tr>
                                    <td colspan="2" style="text-align:center" >No records found</td>
                                  </tr>

                                 <?php } ?>                                  

                              </tbody>
                         </table>
                       </div>                    
                    </div>
                   
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


                <div class="col-md-6">
                    <div class="prioritydivcontainer">
                     <div  class="table-responsive  pre-scrollable">
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
                            foreach($existing_coupon_estimate_data as $coupon){?>

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
                                        echo "estimate";
                                    }
                                ?></td>
                                <td class="removeclass2" data-couponid="<?php echo $coupon->coupon_id ?>" couponRedrawName="<?php echo $coupon->coupon_code ?>" couponRedrawDiscount="<?php echo $coupon_amount_display ?>" couponRedrawType="<?php echo $coupon->coupon_type ?>" optionValueRemove="<?php echo $coupon->coupon_id ?>" couponRedrawOGDiscount="<?php echo $coupon->coupon_amount ?>" couponRedrawCALCType="<?php echo $coupon->coupon_type ?>" couponRedrawCouponType="<?php echo $coupon->coupon_amount_calculation ?>" ><ul class="icons-list"><li><a href="#"><i class="icon-trash"><li></i></a></li></ul></td>
                              </tr>

                              <?php
                            }?> 
                        </tbody>
                       </table>
                     </div>
                    </div>
                </div>
              </div>

               </div>
               <textarea   name="joblistarray" id="assign_job_ids2" style="display: none;" ><?php echo json_encode($keyIds); ?></textarea>
               <br>


            </fieldset>
            <div class="text-center">
              <button type="submit" class="btn btn-success">Submit <i class="icon-arrow-right14 position-right"></i>
              </button>
            </div>
          </form>
      </div>
   </div>
</div>

        
   
<!-- /content area -->
<script type="text/javascript">

   var couponSelectedValues = [];
   var couponRedrawName = [];
   var couponRedrawDiscount = [];
   var couponRedrawType = [];
   var couponAllData = {};
  

$(document).ready(function(){
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
    
    $("#program_list").multiselect({
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
        },
        onChange: function(element, checked) {
            // need to get all of the IDs now since there may be more than one program
            var program_ids = $('#program_list option:selected');
            var selected_programs = [];
            var selected_services = [];
            $(program_ids).each(function(index, brand){
                selected_programs.push($(this).val());
            });

            keyIds = [];
            var items = [];
            var service_id = $('select#service_list option:selected');
            //var service_name = $('select#service_list option:selected').text();
            $(service_id).each(function(index, brand){
                selected_services[$(this).val()] = $(this)[0].innerHTML;
            });
            // alert(customer_id);
            $('.priceoverridetbody').html('');
            $.ajax({
                type: "POST", 
                url: "<?= base_url('admin/Estimates/getAllServicesByProgramMultiple')  ?>",
                data: {program_ids : selected_programs }, 
                dataType: 'JSON', 
            }).done(function(response){
                if (response.status==200) {
                    if(service_id != ''){
                        Object.keys(selected_services).forEach(key => {
                            keyIds.push({'job_id' : key, 'price_override' : 0,'is_price_override_set':null}); 
                        
                            items.push('<tr id="tridjob'+key+'">'+
                                '<td>'+selected_services[key]+'</td>'+
                                '<td> <input type="number" min="0" name="tmp'+key+'"  class="inpcl form-control" job_id="'+key+'"  ></td>'+                    
                                '</tr>'
                            );
                        });
                    }
                    
                    $.each( response.result, function( key, val ) {
                        keyIds.push({'job_id' : val[0].job_id, 'price_override' : 0,'is_price_override_set':null, 'program_id':val[0].program_id});

                        items.push( '<tr id="tridjob'+val[0].job_id+'">'+
                            '<td>'+val[0].job_name+'</td>'+
                            '<td> <input type="number" min="0" name="tmp'+val[0].job_id+'"  class="inpcl form-control" job_id="'+val[0].job_id+'"  ></td>'+                    
                            '</tr>'
                        );
                    });            
                    $('.priceoverridetbody').html(items.join( "" ));
                }else if(service_id != ''){
                    
                    Object.keys(selected_services).forEach(key => {
                        keyIds.push({'job_id' : key, 'price_override' : 0,'is_price_override_set':null}); 
                    
                        items.push('<tr id="tridjob'+key+'">'+
                            '<td>'+selected_services[key]+'</td>'+
                            '<td> <input type="number" min="0" name="tmp'+key+'"  class="inpcl form-control" job_id="'+key+'"  ></td>'+                    
                            '</tr>'
                        );
                    });
                    
                    $('.priceoverridetbody').html(items.join( "" ));
                } else {
                    $('.priceoverridetbody').html('<tr><td colspan="2" style="text-align:center" >No records found</td></tr>');
                } 

                $('#assign_job_ids2').val(JSON.stringify(keyIds));
            });
        }
    });

    $("#service_list").multiselect({
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
        },
        onChange: function(element, checked) {
            // need to get all of the IDs now since there may be more than one program
            var program_ids = $('#program_list option:selected');
            var selected_programs = [];
            var selected_services = [];
            $(program_ids).each(function(index, brand){
                selected_programs.push($(this).val());
            });

            keyIds = [];
            var items = [];
            var service_id = $('select#service_list option:selected');
            //var service_name = $('select#service_list option:selected').text();
            $(service_id).each(function(index, brand){
                selected_services[$(this).val()] = $(this)[0].innerHTML;
            });
            // alert(customer_id);
            $('.priceoverridetbody').html('');
            $.ajax({
                type: "POST", 
                url: "<?= base_url('admin/Estimates/getAllServicesByProgramMultiple')  ?>",
                data: {program_ids : selected_programs }, 
                dataType: 'JSON', 
            }).done(function(response){
                if (response.status==200) {
                    if(service_id != ''){
                        Object.keys(selected_services).forEach(key => {
                            keyIds.push({'job_id' : key, 'price_override' : 0,'is_price_override_set':null}); 
                        
                            items.push('<tr id="tridjob'+key+'">'+
                                '<td>'+selected_services[key]+'</td>'+
                                '<td> <input type="number" min="0" name="tmp'+key+'"  class="inpcl form-control" job_id="'+key+'"  ></td>'+                    
                                '</tr>'
                            );
                        });
                    }
                    
                    $.each( response.result, function( key, val ) {
                        keyIds.push({'job_id' : val[0].job_id, 'price_override' : 0,'is_price_override_set':null, 'program_id':val[0].program_id});

                        items.push( '<tr id="tridjob'+val[0].job_id+'">'+
                            '<td>'+val[0].job_name+'</td>'+
                            '<td> <input type="number" min="0" name="tmp'+val[0].job_id+'"  class="inpcl form-control" job_id="'+val[0].job_id+'"  ></td>'+                    
                            '</tr>'
                        );
                    });            
                    $('.priceoverridetbody').html(items.join( "" ));
                }else if(service_id != ''){
                    
                    keyIds.push({'job_id' : service_id, 'price_override' : 0,'is_price_override_set':null}); 
                    
                    items.push( '<tr id="tridjob'+service_id+'">'+
                        '<td>'+service_name+'</td>'+
                        '<td> <input type="number" min="0" name="tmp'+service_id+'"  class="inpcl form-control" job_id="'+service_id+'"  ></td>'+                    
                        '</tr>' );
                    
                    $('.priceoverridetbody').html(items.join( "" ));
                } else {
                    $('.priceoverridetbody').html('<tr><td colspan="2" style="text-align:center" >No records found</td></tr>');
                } 

                $('#assign_job_ids2').val(JSON.stringify(keyIds));
            });
        }
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

   var keyIds =  <?php echo json_encode($keyIds) ?>;
  
    $("#customer_id").change(function(){
       var customer_id = $(this).val();
      // alert(customer_id);
       $.ajax({
            type: "POST",
            url: "<?= base_url('admin/Estimates/getPropertyByCustomerID')  ?>",
            data: {customer_id : customer_id } 
        }).done(function(data){
          //alert(data);
          $("#property_id").html(data);
           reassign();
           getemail(customer_id)
        });

    });

    function reassign() {
          $(".bootstrap-select").selectpicker('destroy');
          $('.bootstrap-select').selectpicker();
    }

    function getemail(customer_id) {
        $.ajax({
                type: "POST",
                url: "<?= base_url('admin/Invoices/getcutomerEmail')  ?>",
                data: {customer_id : customer_id } 
            }).done(function(data){             
              $("#customer_email").val($.trim(data));              
            });

    }


   $(document).on("input",".inpcl",function() { 

      inputvalue  = $(this).val();
      job_id  = $(this).attr('job_id');
      // console.log(inputvalue);
      // console.log(keyIds);
        
        $.each( keyIds, function( key, value ) {
            // console.log(value);
           if (job_id == value.job_id) {
            keyIds[key].price_override = inputvalue;
            if(inputvalue != "") {
              keyIds[key].is_price_override_set = 1;
            } else {
              keyIds[key].is_price_override_set = null;
            }

           }
          // alert( key + ": " + value.property_id );
        }); 

    $('#assign_job_ids2').val(JSON.stringify(keyIds));


  }); 
    function send_to_signwell() {
        var customer_id = $("#customer_id").val();
        var group_id_array = ['<?php echo $estimate_details->estimate_id; ?>:'+customer_id+''];
        $("#loading").css("display","block");
        $.ajax({
            type: "POST",
            url: "<?= base_url('')  ?>admin/Estimates/sendEstimateToSignWell",
            data: {group_id_array }
        }).done(function(data){
            $("#loading").css("display","none");
            myArr = JSON.parse(data);
            error_string = "";
            for (let index = 0; index < myArr.length; ++index) {
                const element = myArr[index];
                error_string = error_string+"Estimate "+element["estimate_id"]+": Failed to send: "+element["message"]+'\n\n' ;
            }
            if (error_string=="") {
                swal(
                    'Estimates !',
                    'Sent Successfully ',
                    'success'
                ).then(function() {
                    location.reload();
                });
            } else {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: error_string
                });
            }
        });
    }

</script>



