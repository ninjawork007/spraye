<style type="text/css">
  .toolbar {
    float: left;
    padding-left: 5px;
}

.form-control[readonly] {
  background-color: #ededed;
}
</style>

<div class="content">
  <div class="panel panel-flat">
    <!-- <div class="panel-heading">
      <h5 class="panel-title">Users list</h5>
    </div> -->
    
    <!-- <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">
          <div class="form-group">
      
          </div>
        </h5>
      </div>
    </div> -->
    <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
        
      <div class="panel panel-body" style="background-color:#ededed;" >
        <form id="serchform" action="<?= base_url('admin/reports/downloadPipelineSummaryCsv') ?>" method="post">            
          <div class="row">
            <div class="col-md-2">
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

            
            <div class="col-md-3">
              <div class="form-group">
                <label>Start Date</label>
                <input type="date" id="estimate_created_date_to" name="estimate_created_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>End Date</label>
                <input type="date" id="estimate_created_date_from" name="estimate_created_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
              </div>
            </div>
          </div>
        
          <div class="text-center">
            <button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
            <button type="button" class="btn btn-primary" onClick="resetform()" ><i class="icon-reset position-left"></i> Reset</button>
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
                <th>User</th>
                <th>Total # of Open Estimates</th>
                <th>Total $ of Open Estimates</th>
                <th>Total # of New Customer Estimates</th>	
                <th>Total $ of New Customer Estimates</th>
                <th>Total # of Existing Customer Estimates</th>
                <th>Total $ of Existing Customer Estimates</th>
              </tr>  
            </thead>
            <tbody>
              <?php 
                if (!empty($total_summary)) {
                  $total_open = 0;
                  $total_cost = 0;
                  $prospect_total = 0;
                  $prospect_total_amt = 0;
                  $customer_total = 0;
                  $customer_total_amt = 0;
                 
                  foreach ($total_summary as $value) { 
              ?>

              <tr>
                <td style="text-transform: capitalize;"><?= $value['rep_name'] ?></td>
                <td><?= $value['total_estimates'] ?></td>
                <td>$ <?= number_format(($value['total_cost']) ,2) ?></td>
                <td><?= $value['prospect'] ?></td>
                <td>$ <?= number_format(($value['prospect_total']) ,2) ?></td>
                <td><?= $value['customer'] ?></td>
                <td>$ <?= number_format(($value['customer_total']) ,2) ?></td>
              </tr>
                <?php  
                  $total_open += $value['total_estimates'];
                  $total_cost += $value['total_cost'];
                  $prospect_total += $value['prospect'];
                  $prospect_total_amt += $value['prospect_total'];
                  $customer_total += $value['customer'];
                  $customer_total_amt += $value['customer_total'];
                  }
              
                } else { 
                ?> 
              <tr>
                <td> No record found </td>
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
              <tr>
                <td><b>TOTALS</b></td>
                <td><b><?= (isset($total_open)?number_format($total_open):0) ?></b></td>
                <td><b>$<?= (isset($total_cost)?number_format($total_cost,2):0) ?></b></td>
                <td><b><?= (isset($prospect_total)?number_format($prospect_total):0) ?></b></td>
                <td><b>$<?= (isset($prospect_total_amt)?number_format($prospect_total_amt,2):0) ?></b></td>
                <td><b><?= (isset($customer_total)?number_format($customer_total):0) ?></b></td>
                <td><b>$<?= (isset($customer_total_amt)?number_format($customer_total_amt,2):0) ?></b></td>
              </tr>
            </tfoot>
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

    // var customer_name = $('#customer_name').val();
    var sales_rep_id = $('#sales_rep_id').val();
    // var product_name = $('#product_name').val();
    var estimate_created_date_to = $('#estimate_created_date_to').val();
    var estimate_created_date_from = $('#estimate_created_date_from').val();
    $('.loading').css("display", "block");
   $('#postList').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxPipelineSummaryData/',
        data:'sales_rep_id='+sales_rep_id+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from,
        
        
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
          "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
          "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
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
    // var customer_name = $('#customer_name').val();
    var sales_rep_id = $('#sales_rep_id').val();
    var estimate_created_date_to = $('#estimate_created_date_to').val();
    var estimate_created_date_from = $('#estimate_created_date_from').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/downloadPipelineSummaryCsv/',
        data:'sales_rep_id='+sales_rep_id+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from,

        success: function (response) {
      //    alert(response);
        }
    });
}


</script>
