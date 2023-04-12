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
        <form id="searchform" action="<?= base_url('admin/reports/downloadCommissionReportCsv') ?>" method="post">            
          <div class="row">
              <div class="col-md-3">
                 <div class="form-group">
								<label>Sales Rep</label>
								<select class="bootstrap-select form-control" name="sales_rep_id"  id="sales_rep_id" data-live-search="true">
									<option value="" >Select a Rep</option>
									<?php if ($users) {
										foreach ($users as $user) { ?>
											<option value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
									<?php } } ?>
								</select>
							</div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label>Estimate Accepted Start Date</label>
                  <input type="date" id="estimate_created_date_to" name="estimate_created_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Estimate Accepted End Date</label>
                  <input type="date" id="estimate_created_date_from" name="estimate_created_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                </div>
              </div>


              <div class="col-md-2">
                <div class="form-group">
                  <label>Job Completed Start Date</label>
                  <input type="date" id="job_completed_date_to" name="job_completed_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label>Job Completed End Date</label>
                  <input type="date" id="job_completed_date_from" name="job_completed_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                </div>
              </div>
          </div>
        
          <div class="text-center">
            <button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
            <button type="button" class="btn btn-primary" id="resetbtn"  onClick="resetform()" ><i class="icon-reset position-left"></i> Reset</button>
            <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
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
                  <th>Sales Rep Name</th>
                  <th>Total $ of Produced Primary Services</th>
                  <th>Total Primary Commissions (Produced Primary Services $ * Primary Service Commission %</th>
                  <th>Total $ of Produced Secondary Services</th>	
                  <th>Total Secondary Commission (Produced Secondary $ * Secondary Commission %</th>
                  <th>Total Sales Produced</th>
                  <th>Total Commissions</th>
                  <th>New Sales Source</th>
              </tr>  
            </thead>
            <tbody>
              <?php 
              if (!empty($commission_summary)) {
                  $total_primary_produced = 0;
                  $total_primary_commission = 0;
                  $total_secondary_produced = 0;
                  $total_secondary_commission = 0;
                  $total_produced = 0;
                  $total_commission = 0;
                  $total_new_sales = 0;
                foreach ($commission_summary as $value) { ?>
              <tr>         
                <td style="text-transform: capitalize;"><?= $value['rep_name']?></td>
                <td>$ <?= number_format(($value['primary_service_total']) ,2)?></td>
                <td>$ <?= number_format(($value['primary_commission']) ,2)?></td>
                <td>$ <?= number_format(($value['secondary_service_total']) ,2)?></td>
                <td>$ <?= number_format(($value['secondary_commission']) ,2)?></td>
                <td>$ <?= number_format(($value['primary_service_total']+$value['secondary_service_total']) ,2)?></td>
                <td>$ <?= number_format(($value['primary_commission']+$value['secondary_commission']) ,2)?></td>
                <td><?= $value['sold_by'] ?></td>
              </tr>
              <?php  
                  $total_primary_produced += $value['primary_service_total'];
                  $total_primary_commission += $value['primary_commission'];
                  $total_secondary_produced += $value['secondary_service_total'];
                  $total_secondary_commission += $value['secondary_commission'];
                  $total_produced += $value['primary_service_total']+$value['secondary_service_total'];
                  $total_commission += $value['primary_commission']+$value['secondary_commission'];
                  $total_new_sales += $value['sold_by'];
                  }
                
                } else { 
                ?> 
              <tr>
                <td style="text-align:center;"> No record found </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <?php 
                }
              ?>
            </tbody>
            <tfoot>
            <?php 
              if (!empty($commission_summary)) { 
                  ?>
              <tr>
                <td><b>TOTALS</b></td>
                <td><b>$<?= number_format($total_primary_produced,2) ?></b></td>
                <td><b>$<?= number_format($total_primary_commission,2) ?></b></td>
                <td><b>$<?= number_format($total_secondary_produced,2) ?></b></td>
                <td><b>$<?= number_format($total_secondary_commission,2) ?></b></td>
                <td><b>$<?= number_format($total_produced,2) ?></b></td>
                <td><b>$<?= number_format($total_commission,2) ?></b></td>
                <td><b><?= number_format($total_new_sales) ?></b></td>
              </tr> 
              <?php 
                }
              ?>
            </tfoot>
          </table>
        </div>
      </div> 
    </div>
  </div>

<script>
// $("#resetbtn").click(function () {
//   $("#sales_rep_id").prop('selectedIndex',0);//Setting the value as '#'
//   // $("#sales_rep_id").val('');//Setting the value as '#'
 
// });
function resetform(){

  // $('#searchform')[0].reset();
 
  // searchFilter();
 
  location.reload();
return false;
}


function searchFilter() {

  var sales_rep_id = $('#sales_rep_id').val();
  var estimate_created_date_to = $('#estimate_created_date_to').val();
  var estimate_created_date_from = $('#estimate_created_date_from').val();
  var job_completed_date_to = $('#job_completed_date_to').val();
  var job_completed_date_from = $('#job_completed_date_from').val();
  $('.loading').css("display", "block");
   $('#postList').html('');
  $.ajax({
    type: 'POST',
    url: '<?php echo base_url(); ?>admin/reports/ajaxCommissionReportData/',
    data:'sales_rep_id='+sales_rep_id+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from+'&job_completed_date_to='+job_completed_date_to+'&job_completed_date_from='+job_completed_date_from,
    // data:'customer_name='+customer_name+'&sales_rep_id='+sales_rep_id+'&property_address='+property_address+'&program_name='+program_name+'&estimate_created_date_to='+estimate_created_date_to+'&job_completed_date_from='+job_completed_date_from,
    
    success: function (html) {
      $(".loading").css("display", "none");
      $('#postList').html(html);
      tableintal();
    }
  });
}



   $(document).ready(function() {
      tableintal();
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
        url: '<?php echo base_url(); ?>admin/reports/downloadCommissionReportCsv/',
        data:'customer_name='+customer_name+'&sales_rep_id='+sales_rep_id+'&estimate_created_date_to='+estimate_created_date_to+'&job_completed_date_from='+job_completed_date_from,

        success: function (response) {
      //    alert(response);
        }
    });
}


</script>
