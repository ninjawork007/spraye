<style type="text/css">
  .toolbar {
    float: left;
    padding-left: 5px;
}

.form-control[readonly] {
  background-color: #ededed;
}
.label-gray , .bg-gray  {
  background-color: #808080;
  background-color: #808080;
  border-color: #808080;
}
</style>

<div class="content">
  <div class="panel panel-flat">
    <!-- <div class="panel-heading">
      <h5 class="panel-title">Users list</h5>
    </div> -->
         
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">
        <div class="form-group">
        </div>
        </h5>
      </div>
    </div>
    <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
  
      <div class="panel panel-body" style="background-color:#ededed;" >
        <form id="serchform" action="<?= base_url('admin/reports/downloadPipelineDetailCsv') ?>" method="post">            
          <div class="row">
              <div class="col-md-12">

                <div class="form-group multi-select-full">
                <?php
                $SelectedSalesRep = array();
                if(isset($SavedFilter['id'])){
                  $SelectedSalesRep = explode(",", $SavedFilter["sales_rep_id"]);
                }
                ?>
                <label>Sales Rep</label>
                <select id="sales_rep_id" name="sales_rep_id[]" multiple class="multiselect-select-all-filtering" placeholder="Select Rep">
                   <?php if ($users) {
                    foreach ($users as $user) { ?>
                      <option <?php if(in_array($user->id, $SelectedSalesRep)) { echo 'selected'; } ?> value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
                  <?php } } ?>                        
                  </select>
              </div>
              </div>

              <div class="col-md-2">
                  <div class="form-group">
                      <label>Customer Name</label>
                      <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Enter Customer Name" value="<?php echo $SavedFilter["customer_name"] ?>">
                  </div>
              </div>

              <div class="col-md-2">
                  <div class="form-group">
                      <label>Property Address</label>
                      <input type="text" id="property_address" name="property_address" class="form-control" placeholder="Enter Property Address" value="<?php echo $SavedFilter["address"] ?>">
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group">
                      <label>Program/Service</label>
                      <input type="text" id="program_name" name="program_name" class="form-control" placeholder="Program/Service" value="<?php echo $SavedFilter["service"] ?>">
                  </div>
              </div>


              <div class="col-md-2">
                <div class="form-group">
                  <label>Start Date</label>
                  <input type="date" id="estimate_created_date_to" name="estimate_created_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $SavedFilter["start_date"] ?>">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label>End Date</label>
                  <input type="date" id="estimate_created_date_from" name="estimate_created_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $SavedFilter["end_date"] ?>">
                </div>
              </div>
          </div>
        
          <div class="text-center">
            <button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
            <button type="button" class="btn btn-primary" onClick="resetform()" ><i class="icon-reset position-left"></i> Reset</button>
            <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
            <button type="button" class="btn btn-success" onClick="saveSearchFilter()" ><i class="icon-search4 position-left"></i> Save Search</button>
          </div>
        </form>
      </div>
      <div class="loading" style="display: none;">
        <center>
              <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
        </center>
      </div>

      <div class="post-list" id="postList">  
        <div  class="table-responsive table-spraye">
          <table  class="table datatable-colvis-state">    
            <thead>  
              <tr>
                <th>Customer</th>
                <th>Property</th>
                <th>Estimate Program/Service</th>
                <th>Estimate #</th>
                <th>Estimate $</th>
                <th>Estimate Date</th>	
                <th>Property Status</th>
              </tr>  
            </thead>
            <tbody>
              <?php 
              if (!empty($pipeline_details)) { 
                foreach ($pipeline_details as $value) { ?>
              <tr>         
                <td style="text-transform: capitalize;">
                  <a target="_blank" href="<?php echo base_url(); ?>/admin/editCustomer/<?= $value->customer_id ?>"><?= $value->first_name.' '.$value->last_name ?></a>
                </td>
                <td><?= $value->property_address ?></td>
                <td><?= $value->program_name ?></td>
                <td><a target="_blank" href="<?php echo base_url(); ?>/admin/Estimates/editEstimate/<?= $value->estimate_id ?>"><?= $value->estimate_id ?></a></td>
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
                <td><?= date('m-d-Y', strtotime($value->estimate_created_date)) ?></td>
                <td width="13%">
                  <?php switch ($value->property_status) {
                    case 0:
                      echo '<span  class="label label-warning myspan">Non-Active</span>';
                      $bg= 'bg-warning';
                      break;
                    case 1:
                      echo '<span  class="label label-success myspan">Active</span>';
                      $bg= 'bg-danger';
                      break;
                    case 2:
                      echo '<span  class="label label-gray myspan">Prospect</span>';
                      $bg= 'bg-gray';
                    break;
                    } 
                  ?>
                  <!-- <?= $value->property_status ?> -->
                </td>
              </tr> 
              <?php 
                  }
                }
              ?>
            </tbody>
          </table>
        </div>
      </div> 
    </div>
  </div>
</div>


<script>



function resetform(){

  $('#serchform')[0].reset();
  searchFilter();
}


function searchFilter() {

    var customer_name = $('#customer_name').val();
    var sales_rep_id = $('#sales_rep_id').val();
    var property_address = $('#property_address').val();
    var program_name = $('#program_name').val();
    var estimate_created_date_to = $('#estimate_created_date_to').val();
    var estimate_created_date_from = $('#estimate_created_date_from').val();
    $('.loading').css("display", "block");
   $('#postList').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxPipelineDetailData/',
        data:'customer_name='+customer_name+'&sales_rep_id='+sales_rep_id+'&property_address='+property_address+'&program_name='+program_name+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from,
        
        success: function (html) {
            $(".loading").css("display", "none");
            $('#postList').html(html);
            tableintal();
          
        }
    });
}



   $(document).ready(function() {
      tableintal();
      searchFilter();
   })

   function tableintal(argument) {
      $('.datatable-colvis-state').DataTable({
        buttons: [
            {
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon'
            }
        ],
        stateSave: true,
        columnDefs: [
            {
                targets: -1,
                visible: false
            }
        ],

          
    });
   }


function csvfile() {
    var customer_name = $('#customer_name').val();
    var sales_rep_id = $('#sales_rep_id').val();
    var estimate_created_date_to = $('#estimate_created_date_to').val();
    var job_completed_date_from = $('#job_completed_date_from').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/downloadPipelineDetailCsv/',
        data:'customer_name='+customer_name+'&sales_rep_id='+sales_rep_id+'&estimate_created_date_to='+estimate_created_date_to+'&job_completed_date_from='+job_completed_date_from,

        success: function (response) {
      //    alert(response);
        }
    });
}


function saveSearchFilter(){
    var sales_rep_id = $('#sales_rep_id').val();
    var customer_name = $('#customer_name').val();
    var address = $('#property_address').val();
    var service = $('#program_name').val();
    var start_date = $('#estimate_created_date_to').val();
    var end_date = $('#estimate_created_date_from').val();

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/saveSalesPipelineFilters',
        data:'sales_rep_id='+sales_rep_id+'&customer_name='+customer_name+'&address='+address+'&service='+service+'&start_date='+start_date+'&end_date='+end_date,

        success: function (resp) {
            swal('Save','Filter Saved Successfully ','success')
        },
  });
}

</script>
