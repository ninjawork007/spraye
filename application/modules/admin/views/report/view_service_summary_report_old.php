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
    <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
      <div class="panel panel-body" style="background-color:#ededed;" >
        <form id="serchform" action="<?= base_url('admin/reports/downloadServiceSummaryCsv') ?>" method="post">            
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Service</label>
                <input type="text" id="job_name" name="job_name" class="form-control" placeholder="Enter Service Name">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label>Start Date</label>
                <input type="date" id="estimate_created_date_to" name="estimate_created_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label>End Date</label>
                <input type="date" id="estimate_created_date_from" name="estimate_created_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Date Range Start</label>
                <input type="date" id="date_range_date_to" name="date_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label>Date Range End</label>
                <input type="date" id="date_range_date_from" name="date_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Comparison Range Start</label>
                <input type="date" id="comparision_range_date_to" name="comparision_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label>Comparison Range End</label>
                <input type="date" id="comparision_range_date_from" name="comparision_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
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
      
      <div class="tabbable">
        <ul class="nav nav-tabs nav-tabs-solid nav-justified">
          <li class="liquick <?php echo $active_nav_link == '0' ? 'active' : ''  ?> "><a href="#highlighted-justified-tab0" data-toggle="tab">Quickview</a></li>
          <li class="lione <?php echo $active_nav_link == '1' ? 'active' : ''  ?> "><a href="#highlighted-justified-tab1" data-toggle="tab">Total New Estimates</a></li>
          <li class="litwo <?php echo $active_nav_link == '2' ? 'active' : ''  ?>"><a href="#highlighted-justified-tab2" data-toggle="tab">Total Accepted Estimates</a></li>
          <li class="lithree <?php echo $active_nav_link == '3' ? 'active' : ''  ?>"><a href="#highlighted-justified-tab3" data-toggle="tab">Total Declined Estimates</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane <?php echo $active_nav_link=='0' ? 'active' : ''  ?>" id="highlighted-justified-tab0">
            <div class="post-list" id="postList"> 
              <div  class="table-responsive table-spraye">
                <table  class="table datatable-colvis-state">    
                  <thead>  
                    <tr>
                      <th>Service</th>
                      <th>Total New Estimates</th>
                      
                      <th>Total # of Accepted Estimates</th>
                      
                      <th>Total $ of Accepted Estimates</th>
                      
                      <th>Total # of Declined Estimates</th>
                      
                      <th>Total $ of Declined Estimates</th>
                    
                      <th>Close Rate - Accepted/(Declined + Accepted)</th>
                      
                      <th>$ Close Rate Accepted $/ (Declined $ + Accepted $)</th>
                      
                    </tr>  
                  </thead>
                  <tbody>
                    <?php 
                      if (!empty($service_summary)) {
                        $total_open = 0;
                        $total_estimates = 0;
                        $total_accepted = 0;
                        $accepted_total = 0;
                        $total_declined = 0;
                        $declined_total = 0;
                        $closed_rate_total = 0;
                        $closed_rate_amt = 0;

                        foreach ($service_summary as $value) { 
                    ?>

                    <tr>
                      <td ><?= $value['job_name'] ?></td>
                      <td><?= $value['total_estimates'] ?></td>
                      
                      <td><?= $value['accepted'] ?></td>
                      
                      <td>$ <?= number_format(($value['accepted_total']) ,2) ?></td>
                      
                      <td><?= $value['declined'] ?></td>
                      
                      <td>$ <?= number_format(($value['declined_total']) ,2) ?></td>
                    
                      <td><?= number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2) ?>%</td>
                      
                      <td>$ <?= number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2) ?></td>
                      
                    </tr>
                      <?php 
                        $total_open += $value['total_estimates'];
                        $total_estimates += $value['total_estimates'];
                        $total_accepted += $value['accepted'];
                        $accepted_total += $value['accepted_total'];
                        $total_declined += $value['declined'];
                        $declined_total += $value['declined_total'];
                        $closed_rate_total += number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2);
                        $closed_rate_amt += number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2);
                        }
                      ?>
                      
                    <tr>
                      <td><b>TOTALS</b></td>
                      <td><b><?= number_format($total_estimates) ?></b></td>
                      
                      <td><b><?= number_format($total_accepted) ?></b></td>
                      
                      <td><b>$<?= number_format($accepted_total,2) ?></b></td>
                      
                      <td><b><?= number_format($total_declined) ?></b></td>
                      
                      <td><b>$<?= number_format($declined_total,2) ?></b></td>
                      
                      <td><b><?= number_format($closed_rate_total,2) ?>%</b></td>
                      
                      <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>
                      
                    </tr>
                      <?php
                      } else { 
                      ?> 

                    <tr>
                      <td colspan="5"> No record found </td>
                    </tr>

                    <?php }  ?>

                  </tbody>
                </table>
              </div>
            </div> 

          </div>
          <div class="tab-pane <?php echo $active_nav_link=='1' ? 'active' : ''  ?>" id="highlighted-justified-tab1">
            <div  class="table-responsive table-spraye">
              <table  class="table datatable-colvis-state" id="customer-services">
                <thead>  
                  <tr>
                      <!-- <th><input type="checkbox" id="select_all" /></th> -->
                      <th>Service</th>
                      <th>% New Estimates in date range</th>
                      <th>$ Accepted Estimates in comparison range</th>
                      <th>Difference Close Rate %</th>
                      <th>Difference Close Rate $</th>
                      
                  </tr>  
                </thead>
                <tbody id="new_estimates_tbody">
                <?php 
                      if (!empty($service_summary)) {
                        // $total_open = 0;
                        $total_estimates = 0;
                        // $total_accepted = 0;
                        // $accepted_total = 0;
                        // $total_declined = 0;
                        // $declined_total = 0;
                        $closed_rate_total = 0;
                        $closed_rate_amt = 0;

                        foreach ($service_summary as $value) { 
                    ?>

                    <tr>
                      <td ><?= $value['job_name'] ?></td>
                    <td><?= $value['total_estimates'] ?></td>
                    <td></td>
                    <td><?= number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2) ?>%</td>
                      
                    <td>$ <?= number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2) ?></td>
                    <!-- <td></td> -->
                  </tr>
                  <?php  
                        // $total_open += $value['total_estimates'];
                        $total_estimates += $value['total_estimates'];
                        // $total_accepted += $value['accepted'];
                        // $accepted_total += $value['accepted_total'];
                        // $total_declined += $value['declined'];
                        // $declined_total += $value['declined_total'];
                        $closed_rate_total += number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2);
                        $closed_rate_amt += number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2);
                        }
                      ?>
                      
                    <tr>
                      <td><b>TOTALS</b></td>
                      <td><b><?= number_format($total_estimates) ?></b></td>
                      <td></td>
                      <td><b><?= number_format($closed_rate_total,2) ?>%</b></td>
                      
                      <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>

                    </tr>
                      <?php
                      } else { 
                      ?> 

                    <tr>
                      <td colspan="5"> No record found </td>
                    </tr>

                    <?php }  ?>

                  </tbody>
              </table>  
            </div>   
          </div>
          <div class="tab-pane <?php echo $active_nav_link == '2' ? 'active' : ''  ?>" id="highlighted-justified-tab2">
          <div  class="table-responsive table-spraye">
              <table  class="table datatable-colvis-state" id="customer-services">
                <thead>  
                  <tr>
                      <!-- <th><input type="checkbox" id="select_all" /></th> -->
                      <th>Service</th>
                      <th># of estimates accepted in date range</th>
                      <th># of estimates accepted in comparison range</th>
                      <th>Difference Close Rate %</th>
                      <th>Difference Close Rate $</th>
                      
                  </tr>  
                </thead>
                <tbody id="new_estimates_tbody">
                <?php 
                      if (!empty($service_summary)) {
                        // $total_open = 0;
                        // $total_estimates = 0;
                        $total_accepted = 0;
                        // $accepted_total = 0;
                        // $total_declined = 0;
                        // $declined_total = 0;
                        $closed_rate_total = 0;
                        $closed_rate_amt = 0;

                        foreach ($service_summary as $value) { 
                    ?>

                    <tr>
                      <td ><?= $value['job_name'] ?></td>
                    <td><?= $value['accepted'] ?></td>
                    <td></td>
                    <td><?= number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2) ?>%</td>
                      
                    <td>$ <?= number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2) ?></td>
                    <!-- <td></td> -->
                  </tr>
                  <?php  
                        // $total_open += $value['total_estimates'];
                        // $total_estimates += $value['total_estimates'];
                        $total_accepted += $value['accepted'];
                        // $accepted_total += $value['accepted_total'];
                        // $total_declined += $value['declined'];
                        // $declined_total += $value['declined_total'];
                        $closed_rate_total += number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2);
                        $closed_rate_amt += number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2);
                        }
                      ?>
                      
                    <tr>
                      <td><b>TOTALS</b></td>
                      <td><b><?= number_format($total_accepted) ?></b></td>
                      <td></td>
                      <td><b><?= number_format($closed_rate_total,2) ?>%</b></td>
                      
                      <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>

                    </tr>
                      <?php
                      } else { 
                      ?> 

                    <tr>
                      <td colspan="5"> No record found </td>
                    </tr>

                    <?php }  ?>

                  </tbody>
              </table>  
            </div>   
          </div>
          <div class="tab-pane <?php echo $active_nav_link == '3' ? 'active' : ''  ?>" id="highlighted-justified-tab3">
          <div  class="table-responsive table-spraye">
              <table  class="table datatable-colvis-state" id="customer-services">
                <thead>  
                  <tr>
                      <!-- <th><input type="checkbox" id="select_all" /></th> -->
                      <th>Service</th>
                      <th># of estimates decline in date range</th>
                      <th># of estimates decline in comparison range</th>
                      <th>Difference Close Rate %</th>
                      <th>Difference Close Rate $</th>
                      
                  </tr>  
                </thead>
                <tbody id="new_estimates_tbody">
                <?php 
                      if (!empty($service_summary)) {
                        // $total_open = 0;
                        // $total_estimates = 0;
                        // $total_accepted = 0;
                        // $accepted_total = 0;
                        $total_declined = 0;
                        // $declined_total = 0;
                        $closed_rate_total = 0;
                        $closed_rate_amt = 0;

                        foreach ($service_summary as $value) { 
                    ?>

                    <tr>
                      <td ><?= $value['job_name'] ?></td>
                    <td><?= $value['declined'] ?></td>
                    <td></td>
                    <td><?= number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2) ?>%</td>
                      
                    <td>$ <?= number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2) ?></td>
                    <!-- <td></td> -->
                  </tr>
                  <?php  
                        // $total_open += $value['total_estimates'];
                        // $total_estimates += $value['total_estimates'];
                        // $total_accepted += $value['accepted'];
                        // $accepted_total += $value['accepted_total'];
                        $total_declined += $value['declined'];
                        // $declined_total += $value['declined_total'];
                        $closed_rate_total += number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2);
                        $closed_rate_amt += number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2);
                        }
                      ?>
                      
                    <tr>
                      <td><b>TOTALS</b></td>
                      <td><b><?= number_format($total_declined) ?></b></td>
                      <td></td>
                      <td><b><?= number_format($closed_rate_total,2) ?>%</b></td>
                      
                      <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>

                    </tr>
                      <?php
                      } else { 
                      ?> 

                    <tr>
                      <td colspan="5"> No record found </td>
                    </tr>

                    <?php }  ?>

                  </tbody>
              </table>  
            </div>   
          </div>   
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
    var job_name = $('#job_name').val();
    var estimate_created_date_to = $('#estimate_created_date_to').val();
    var estimate_created_date_from = $('#estimate_created_date_from').val();
    var date_range_date_to = $('#date_range_date_to').val();
    var date_range_date_from = $('#date_range_date_from').val();
    var comparision_range_date_to = $('#comparision_range_date_to').val();
    var comparision_range_date_from = $('#comparision_range_date_from').val();
    $('.loading').css("display", "block");
   $('#postList').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryData/',
        data:'job_name='+job_name+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
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
  var job_name = $('#job_name').val();
  var estimate_created_date_to = $('#estimate_created_date_to').val();
  var estimate_created_date_from = $('#estimate_created_date_from').val();
  var date_range_date_to = $('#date_range_date_to').val();
  var date_range_date_from = $('#date_range_date_from').val();
  var comparision_range_date_to = $('#comparision_range_date_to').val();
  var comparision_range_date_from = $('#comparision_range_date_from').val();
  $.ajax({
      type: 'POST',
      url: '<?php echo base_url(); ?>admin/reports/downloadServiceSummaryCsv/',
      data:'job_name='+job_name+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,

      success: function (response) {
    //    alert(response);
      }
  });
}


</script>
