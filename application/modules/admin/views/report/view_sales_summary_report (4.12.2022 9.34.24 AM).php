<style type="text/css">
  .toolbar {
    float: left;
    padding-left: 5px;
  }

  .form-control[readonly] {
    background-color: #ededed;
  }
  .row {
  margin-left:-5px;
  margin-right:-5px;
}
  
.column {
  float: left;
  width: 50%;
  padding: 5px;
}

#compare {
  display: none;
}
</style>

<div class="content">
  <div class="panel panel-flat">
    <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
      

      <div class="tabbable">
        <ul class="nav nav-tabs nav-tabs-solid nav-justified">
          <li class="liquick <?php echo $active_nav_link == '0' ? 'active' : ''  ?> "><a href="#highlighted-justified-tab0" data-toggle="tab">Total New Estimates</a></li>
          <li class="lione <?php echo $active_nav_link == '1' ? 'active' : ''  ?> "><a href="#highlighted-justified-tab1" data-toggle="tab">Total Accepted Estimates</a></li>
          <li class="litwo <?php echo $active_nav_link == '2' ? 'active' : ''  ?>"><a href="#highlighted-justified-tab2" data-toggle="tab">Total Declined Estimates</a></li>
        </ul>
        <div class="tab-content">
          <!-- New -->
          <div class="tab-pane <?php echo $active_nav_link=='0' ? 'active' : ''  ?>" id="highlighted-justified-tab0">
            <div class="row">
              <div class=" col-md-5">
                <div class="panel panel-body" style="background-color:#ededed;" >
                    <form id="searchform_new" action="<?= base_url('admin/reports/downloadSalesSummaryCsv') ?>" method="post">            
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Sales Rep</label>
                            <select class="bootstrap-select form-control" name="sales_rep_id"  id="sales_rep_id_new" data-live-search="true">
                              <option value="" >Select a Rep</option>
                              <?php if ($users) {
                                foreach ($users as $user) { ?>
                                  <option value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
                              <?php } } ?>
                            </select>
                          </div>
                        </div>

                      
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Date Range Start</label>
                            <input type="date" id="date_range_date_to_new" name="date_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Date Range End</label>
                            <input type="date" id="date_range_date_from_new" name="date_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                          </div>
                        </div>
                      </div>
                    
                      <div class="text-center">
                        <button type="button" class="btn btn-success" onClick="searchFilterNew()" ><i class="icon-search4 position-left"></i> Search</button>
                        <button type="button" class="btn btn-primary" onClick="resetformNew()" ><i class="icon-reset position-left"></i> Reset</button>
                        <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
                      </div>
                    </form>
                </div>
                <div class="loading_1" style="display: none;">
                  <center>
                    <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
                  </center>
                </div>
                <div class="post-list" id="postListNew"> 
                  <div class="table-responsive table-spraye">
                    <table  class="table datatable-colvis-state " id="new_estimates">
                      <thead>  
                        <tr>
                            <!-- <th><input type="checkbox" id="select_all" /></th> -->
                            <th>Sales Rep/Source</th>
                            <th># of estimates created in date range</th>
                            <!-- <th># of estimates created in comparison range</th> -->
                            <th> Close Rate %</th>
                            <th> Close Rate $</th>
                            
                        </tr>  
                      </thead>
                      <tbody id="new_estimates_tbody">
                      <?php 
                            if (!empty($report_summary)) {
                              $total_open = 0;
                              // $total_estimates = 0;
                              // $total_accepted = 0;
                              // $accepted_total = 0;
                              // $total_declined = 0;
                              // $declined_total = 0;
                              $closed_rate_total = 0;
                              $closed_rate_amt = 0;

                              foreach ($report_summary as $value) { 
                          ?>

                        <tr>
                          <!-- <td></td> -->
                          <td ><?= $value['rep_name'] ?></td>
                          <td><?= $value['total_estimates'] ?></td>
                          <!-- <td></td> -->
                          <td class='close-rate-1'><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                          <td class='close-dollar-1'>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
                        </tr>
                        <?php  
                              $total_open += $value['total_estimates'];
                              // $total_estimates += $value['total_estimates'];
                              // $total_accepted += $value['accepted'];
                              // $accepted_total += $value['accepted_total'];
                              // $total_declined += $value['declined'];
                              // $declined_total += $value['declined_total'];
                              $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                              $closed_rate_amt += number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2);
                              }
                            ?>
                            
                          <tr>
                            <td><b>TOTALS</b></td>
                            <td><b><?= number_format($total_open) ?></b></td>
                            <!-- <td><b><?= number_format($total_open) ?></b></td> -->
                            <td><b><?= number_format($closed_rate_total/count($report_summary)) ?>%</b></td>
                            <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>
                            <!-- <td></td> -->
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
              <div class=" col-md-2" >
                <div class="panel panel-body" style="background-color:#ededed;" >
                    <form id="searchform_new_a-1" action="<?= base_url('admin/reports/downloadSalesSummaryCsvA') ?>" method="post">            
                      <div class="row">
                        <!-- <div class="col-md-4">
                          <div class="form-group">
                            <label>Sales Rep</label>
                            <select class="bootstrap-select form-control" name="sales_rep_id"  id="sales_rep_id_new_a" data-live-search="true">
                              <option value="" >Select a Rep</option>
                              <?php if ($users) {
                                foreach ($users as $user) { ?>
                                  <option value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
                              <?php } } ?>
                            </select>
                          </div>
                        </div> -->
                        <div class="col-md-6">
                          <!-- <div class="form-group">
                            <label> Range Start</label>
                            <input type="date" id="comparision_range_date_to_new_1a" name="comparision_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                          </div> -->
                        </div>

                        <div class="col-md-6">
                          <!-- <div class="form-group">
                            <label> Range End</label>
                            <input type="date" id="comparision_range_date_from_new_1a" name="comparision_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                          </div> -->
                        </div>
                      </div>
                    
                      <div class="text-center">
                        <button type="button" id="comparison" class="btn btn-success" onClick="searchFilterNew1A()" ><i class="fa fa-arrows-h"></i> Compare</button>
                        <!-- <button type="button" class="btn btn-primary" onClick="resetformNewA()" ><i class="icon-reset position-left"></i> Reset</button>
                        <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button> -->
                      </div>
                    </form>
                </div>
                <div class="loading_1a" style="display: none;">
                  <center>
                    <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
                  </center>
                </div>
                <div class="post-list-a-1" id="postListNewA-1"> 
                  <div class="table-responsive table-spraye" id="compare">
                    <table  class="table datatable-colvis-state " id="new_estimates_a-1">
                      <thead>  
                        <tr>
                            <th> Close %</th>
                            <th> Close $</th>
                        </tr>  
                      </thead>
                      <tbody id="new_estimates_tbody_a-1">
                      <?php 
                            if (!empty($report_summary)) {
                              $total_open = 0;
                              // $total_estimates = 0;
                              // $total_accepted = 0;
                              // $accepted_total = 0;
                              // $total_declined = 0;
                              // $declined_total = 0;
                              $closed_rate_total = 0;
                              $closed_rate_amt = 0;

                              foreach ($report_summary as $value) { 
                          ?>

                        <tr>
                          <td class='close-rate-1a'><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                          <td class='close-dollar-1a'>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
                        </tr>
                        <?php  
                              $total_open += $value['total_estimates'];
                              // $total_estimates += $value['total_estimates'];
                              // $total_accepted += $value['accepted'];
                              // $accepted_total += $value['accepted_total'];
                              // $total_declined += $value['declined'];
                              // $declined_total += $value['declined_total'];
                              $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                              $closed_rate_amt += number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2);
                              }
                            ?>
                            
                          <tr>
                            <td><b><?= number_format($closed_rate_total/count($report_summary)) ?>%</b></td>
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
              <div class=" col-md-5">
                <div class="panel panel-body" style="background-color:#ededed;" >
                    <form id="searchform_new_a" action="<?= base_url('admin/reports/downloadSalesSummaryCsvA') ?>" method="post">            
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Sales Rep</label>
                            <select class="bootstrap-select form-control" name="sales_rep_id"  id="sales_rep_id_new_a" data-live-search="true">
                              <option value="" >Select a Rep</option>
                              <?php if ($users) {
                                foreach ($users as $user) { ?>
                                  <option value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
                              <?php } } ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Comparison Range Start</label>
                            <input type="date" id="comparision_range_date_to_new_a" name="comparision_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Comparison Range End</label>
                            <input type="date" id="comparision_range_date_from_new_a" name="comparision_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                          </div>
                        </div>
                      </div>
                    
                      <div class="text-center">
                        <button type="button" class="btn btn-success" onClick="searchFilterNewA()" ><i class="icon-search4 position-left"></i> Search</button>
                        <button type="button" class="btn btn-primary" onClick="resetformNewA()" ><i class="icon-reset position-left"></i> Reset</button>
                        <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
                      </div>
                    </form>
                </div>
                <div class="loading_1_a" style="display: none;">
                  <center>
                    <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
                  </center>
                </div>
                <div class="post-list-a" id="postListNewA"> 
                  <div class="table-responsive table-spraye">
                    <table  class="table datatable-colvis-state " id="new_estimates_a">
                      <thead>  
                        <tr>
                            <!-- <th><input type="checkbox" id="select_all" /></th> -->
                            <th>Sales Rep/Source</th>
                            <!-- <th># of estimates created in date range</th> -->
                            <th># of estimates created in comparison range</th>
                            <th> Close Rate %</th>
                            <th> Close Rate $</th>
                            
                        </tr>  
                      </thead>
                      <tbody id="new_estimates_tbody_a">
                      <?php 
                            if (!empty($report_summary)) {
                              $total_open = 0;
                              // $total_estimates = 0;
                              // $total_accepted = 0;
                              // $accepted_total = 0;
                              // $total_declined = 0;
                              // $declined_total = 0;
                              $closed_rate_total = 0;
                              $closed_rate_amt = 0;

                              foreach ($report_summary as $value) { 
                          ?>

                        <tr>
                          <!-- <td></td> -->
                          <td ><?= $value['rep_name'] ?></td>
                          <!-- <td></td> -->
                          <td><?= $value['total_estimates'] ?></td>
                          <td class='close-rate-1a'><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                          <td class='close-dollar-1a'>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
                        </tr>
                        <?php  
                              $total_open += $value['total_estimates'];
                              // $total_estimates += $value['total_estimates'];
                              // $total_accepted += $value['accepted'];
                              // $accepted_total += $value['accepted_total'];
                              // $total_declined += $value['declined'];
                              // $declined_total += $value['declined_total'];
                              $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                              $closed_rate_amt += number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2);
                              }
                            ?>
                            
                          <tr>
                            <td><b>TOTALS</b></td>
                            <td><b><?= number_format($total_open) ?></b></td>
                            <!-- <td><b><?= number_format($total_open) ?></b></td> -->
                            <td><b><?= number_format($closed_rate_total/count($report_summary)) ?>%</b></td>
                            <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>
                            <!-- <td></td> -->
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
            <class class="row">
              <class class="column">
                <div>
                  <button type="button" onclick="diffRate()" >
                    Comparison
                  </button>
                </div>
              </class>
            </class>
          </div>
          <!-- Start of jquery to add comparison column -->
            <script>
              $(document).ready(function () {

              //   $('#new_estimates tbody td').on('click', function  () {
              //       alert($(this).text());
              //   });
            
                
                $("#comparison").click(function () {
                  $("#compare").toggle();
                });
      

              });
              // Onclick to grab text to add to comparison columns
              function diffRate(){
              //  console.log('entering function');
                var arrData = [];
                var arr = {};
                var data = [];
                var arr1 = [];
                var arr2 = [];

                // $('#new_estimates tr').each(function( index ) {
                //   console.log( index + ": " + $( this ).text() );
                // });
                $('#new_estimates tr .close-rate-1').each(function( index ) {
                  // console.log( index + ": " + $( this ).text() );
                  var percent = index + ": " +$(this).text();
                  arr1.percent = percent;
                  // console.log(percent);
                  data.push(arr1);
                });
                $('#new_estimates tr .close-dollar-1').each(function( index ) {
                  // console.log( index + ": " + $( this ).text() );
                  var  dollar = index + ": " +$(this).text();
                  arr1.dollar = dollar;
                  // console.log( dollar);
                  data.push(arr1);
                  arr[index]= arr1;
                });
                console.log(arr1);

                $('#new_estimates tr').each(function(index, value){
                  // console.log('entering each');
                  var close_percent = $(this).parent().find('.close-rate-1').text();
                  var close_dollar = $(this).parent().find('.close-dollar-1').text();

                //  console.log(close_percent);
                    arr.close_percent =  `${index}:${close_percent}`;
                    arr.close_dollar = close_dollar;
                    // obj.close_percent1 = close_percent1;
                    // obj.close_dollar1 = close_dollar1;

                    arrData.push(arr);

                });
                // $('#new_estimates_a tr').each(function( index ) {
                //   console.log( index + ": " + $( this ).text() );
                // });
                $('#new_estimates_a tr .close-rate-1a').each(function( index ) {
                  // console.log( index + ": " + $( this ).text() );
                });
                $('#new_estimates_a tr .close-dollar-1a').each(function( index ) {
                  // console.log( index + ": " + $( this ).text() );
                });
                $('#new_estimates_a tr ').each(function(index){
                  // console.log('entering each');
                 
                  var close_percent1 = $(this).parent().find('.close-rate-1a').text();
                  var close_dollar1 = $(this).parent().find('.close-dollar-1a').text();

                    arr.close_percent1 = close_percent1;
                    arr.close_dollar1 = close_dollar1;

                    arrData.push(arr);

                  });
                  console.log(arr);
              }
            </script>
          <!-- End of jquery to add comparison column -->
          <!-- end NEW REPORT -->
          <!-- TOTAL ACCEPTED ESTIMATES -->
          <div class="tab-pane <?php echo $active_nav_link=='1' ? 'active' : ''  ?>" id="highlighted-justified-tab1">
            <div class="row">
              <div class="column">
                <div class="panel panel-body" style="background-color:#ededed;" >
                  <form id="searchform_accepted" action="<?= base_url('admin/reports/downloadSalesSummaryCsvAccepted') ?>" method="post">            
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Sales Rep</label>
                          <select class="bootstrap-select form-control" name="sales_rep_id"  id="sales_rep_id_accepted" data-live-search="true">
                            <option value="" >Select a Rep</option>
                            <?php if ($users) {
                              foreach ($users as $user) { ?>
                                <option value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
                            <?php } } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Date Range Start</label>
                          <input type="date" id="date_range_date_to_accepted" name="date_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                        </div>
                      </div>
    
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Date Range End</label>
                          <input type="date" id="date_range_date_from_accepted" name="date_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                        </div>
                      </div>
                    </div>
                  
                    <div class="text-center">
                      <button type="button" class="btn btn-success" onClick="searchFilterAccepted()" ><i class="icon-search4 position-left"></i> Search</button>
                      <button type="button" class="btn btn-primary" onClick="resetformAccepted()" ><i class="icon-reset position-left"></i> Reset</button>
                      <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
                    </div>
                  </form>
                </div>
                <div class="loading_2" style="display: none;">
                  <center>
                    <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
                  </center>
                </div>
                <div class="post-list" id="postListAccepted"> 
                  <div  class="table-responsive table-spraye">
                    <table  class="table datatable-colvis-state " id="accepted_estimates" >
                      <thead>  
                        <tr>
                            <!-- <th><input type="checkbox" id="select_all" /></th> -->
                            <th>Sales Rep/Source</th>
                            <th># of estimates accepted in date range</th>
                            <!-- <th># of estimates created in comparison range</th> -->
                            <th>Difference Close Rate %</th>
                            <th>Difference Close Rate $</th>
                        </tr>  
                      </thead>
                      <tbody id="accepted_estimates_tbody">
                        <?php 
                            if (!empty($report_summary)) {
                              // $total_open = 0;
                              // $total_estimates = 0;
                              $total_accepted = 0;
                              // $accepted_total = 0;
                              // $total_declined = 0;
                              // $declined_total = 0;
                              $closed_rate_total = 0;
                              $closed_rate_amt = 0;
    
                              foreach ($report_summary as $value) { 
                          ?>
    
                        <tr>
                          <!-- <td></td> -->
                          <td ><?= $value['rep_name'] ?></td>
                          <td><?= $value['accepted'] ?></td>
                          <!-- <td></td> -->
                          <td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                          <td>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
                        </tr>
                        <?php  
                              // $total_open += $value['total_estimates'];
                              // $total_estimates += $value['total_estimates'];
                              $total_accepted += $value['accepted'];
                              // $accepted_total += $value['accepted_total'];
                              // $total_declined += $value['declined'];
                              // $declined_total += $value['declined_total'];
                              $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                              $closed_rate_amt += number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2);
                              }
                            ?>
                            
                          <tr>
                            <td><b>TOTALS</b></td>
                            <td><b><?= number_format($total_accepted) ?></b></td>
                            <!-- <td><b><?= number_format($total_accepted) ?></b></td> -->
                            <td><b><?= number_format($closed_rate_total/count($report_summary)) ?>%</b></td>
                            <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>
                            <!-- <td></td> -->
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
              <div class="column">
                <div class="panel panel-body" style="background-color:#ededed;" >
                  <form id="searchform_accepted_a" action="<?= base_url('admin/reports/downloadSalesSummaryCsvAcceptedA') ?>" method="post">            
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Sales Rep</label>
                          <select class="bootstrap-select form-control" name="sales_rep_id"  id="sales_rep_id_accepted_a" data-live-search="true">
                            <option value="" >Select a Rep</option>
                            <?php if ($users) {
                              foreach ($users as $user) { ?>
                                <option value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
                            <?php } } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Comparison Range Start</label>
                          <input type="date" id="comparision_range_date_to_accepted_a" name="comparision_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                        </div>
                      </div>
    
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Comparison Range End</label>
                          <input type="date" id="comparision_range_date_from_accepted_a" name="comparision_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                        </div>
                      </div>
                    </div>
                  
                    <div class="text-center">
                      <button type="button" class="btn btn-success" onClick="searchFilterAcceptedA()" ><i class="icon-search4 position-left"></i> Search</button>
                      <button type="button" class="btn btn-primary" onClick="resetformAcceptedA()" ><i class="icon-reset position-left"></i> Reset</button>
                      <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
                    </div>
                  </form>
                </div>
                <div class="loading_2_a" style="display: none;">
                  <center>
                    <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
                  </center>
                </div>
                <div class="post-list" id="postListAcceptedA"> 
                  <div  class="table-responsive table-spraye">
                    <table  class="table datatable-colvis-state " id="accepted_estimates_a" >
                      <thead>  
                        <tr>
                            <!-- <th><input type="checkbox" id="select_all" /></th> -->
                            <th>Sales Rep/Source</th>
                            <!-- <th># of estimates created in date range</th> -->
                            <th># of estimates accepted in comparison range</th>
                            <th>Difference Close Rate %</th>
                            <th>Difference Close Rate $</th>
                        </tr>  
                      </thead>
                      <tbody id="accepted_estimates_tbody_a">
                        <?php 
                            if (!empty($report_summary)) {
                              // $total_open = 0;
                              // $total_estimates = 0;
                              $total_accepted = 0;
                              // $accepted_total = 0;
                              // $total_declined = 0;
                              // $declined_total = 0;
                              $closed_rate_total = 0;
                              $closed_rate_amt = 0;
    
                              foreach ($report_summary as $value) { 
                          ?>
    
                        <tr>
                          <!-- <td></td> -->
                          <td ><?= $value['rep_name'] ?></td>
                          <!-- <td></td> -->
                          <td><?= $value['accepted'] ?></td>
                          <td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                          <td>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
                        </tr>
                        <?php  
                              // $total_open += $value['total_estimates'];
                              // $total_estimates += $value['total_estimates'];
                              $total_accepted += $value['accepted'];
                              // $accepted_total += $value['accepted_total'];
                              // $total_declined += $value['declined'];
                              // $declined_total += $value['declined_total'];
                              $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                              $closed_rate_amt += number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2);
                              }
                            ?>
                            
                          <tr>
                            <td><b>TOTALS</b></td>
                            <!-- <td><b><?= number_format($total_accepted) ?></b></td> -->
                            <td><b><?= number_format($total_accepted) ?></b></td>
                            <td><b><?= number_format($closed_rate_total/count($report_summary)) ?>%</b></td>
                            <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>
                            <!-- <td></td> -->
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
          <!-- end TOTAL ACCEPTED ESTIMATES -->
          <!-- TOTAL DECLINED ESTIMATES -->
          <div class="tab-pane <?php echo $active_nav_link == '2' ? 'active' : ''  ?>" id="highlighted-justified-tab2">
          <div class="row">
            <div class="column">
              <div class="panel panel-body" style="background-color:#ededed;" >
                <form id="searchform_declined" action="<?= base_url('admin/reports/downloadSalesSummaryCsvDeclined') ?>" method="post">            
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Sales Rep</label>
                        <select class="bootstrap-select form-control" name="sales_rep_id"  id="sales_rep_id_declined" data-live-search="true">
                          <option value="" >Select a Rep</option>
                          <?php if ($users) {
                            foreach ($users as $user) { ?>
                              <option value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
                          <?php } } ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Date Range Start</label>
                        <input type="date" id="date_range_date_to_declined" name="date_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                      </div>
                    </div>
  
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Date Range End</label>
                        <input type="date" id="date_range_date_from_declined" name="date_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                      </div>
                    </div>
                  </div>
                
                  <div class="text-center">
                    <button type="button" class="btn btn-success" onClick="searchFilterDeclined()" ><i class="icon-search4 position-left"></i> Search</button>
                    <button type="button" class="btn btn-primary" onClick="resetformDeclined()" ><i class="icon-reset position-left"></i> Reset</button>
                    <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
                  </div>
                </form>
              </div>
              <div class="loading_3" style="display: none;">
                <center>
                  <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
                </center>
              </div>
              <div class="post-list" id="postListDeclined"> 
                <div  class="table-responsive table-spraye">
                  <table  class="table datatable-colvis-state " id="declined_estimates" >
                    <thead>  
                      <tr>
                          <!-- <th><input type="checkbox" id="select_all" /></th> -->
                          <th>Sales Rep/Source</th>
                          <th># of estimates declined in date range</th>
                          <!-- <th># of estimates declined in comparison range</th> -->
                          <th>Difference Close Rate %</th>
                          <th>Difference Close Rate $</th>
                          
                      </tr>  
                    </thead>
                    <tbody id="declined_estimates_tbody">
                      <?php 
                          if (!empty($report_summary)) {
                            // $total_open = 0;
                            // $total_estimates = 0;
                            // $total_accepted = 0;
                            // $accepted_total = 0;
                            $total_declined = 0;
                            // $declined_total = 0;
                            $closed_rate_total = 0;
                            $closed_rate_amt = 0;
  
                            foreach ($report_summary as $value) { 
                        ?>
  
                      <tr>
                        <!-- <td></td> -->
                        <td ><?= $value['rep_name'] ?></td>
                        <td><?= $value['declined'] ?></td>
                        <!-- <td></td> -->
                        <td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                        <td>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
                      </tr>
                      <?php  
                            // $total_open += $value['total_estimates'];
                            // $total_estimates += $value['total_estimates'];
                            // $total_accepted += $value['accepted'];
                            // $accepted_total += $value['accepted_total'];
                            $total_declined += $value['declined'];
                            // $declined_total += $value['declined_total'];
                            $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                            $closed_rate_amt += number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2);
                            }
                          ?>
                          
                        <tr>
                          <td><b>TOTALS</b></td>
                          <td><b><?= number_format($total_declined) ?></b></td>
                          <!-- <td><b><?= number_format($total_declined) ?></b></td> -->
                          <td><b><?= number_format($closed_rate_total/count($report_summary)) ?>%</b></td>
                          <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>
                          <!-- <td></td> -->
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
            <div class="column">
              <div class="panel panel-body" style="background-color:#ededed;" >
                <form id="searchform_declined_a" action="<?= base_url('admin/reports/downloadSalesSummaryCsvDeclinedA') ?>" method="post">            
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Sales Rep</label>
                        <select class="bootstrap-select form-control" name="sales_rep_id"  id="sales_rep_id_declined_a" data-live-search="true">
                          <option value="" >Select a Rep</option>
                          <?php if ($users) {
                            foreach ($users as $user) { ?>
                              <option value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
                          <?php } } ?>
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Comparison Range Start</label>
                        <input type="date" id="comparision_range_date_to_declined_a" name="comparision_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                      </div>
                    </div>
  
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Comparison Range End</label>
                        <input type="date" id="comparision_range_date_from_declined_a" name="comparision_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                      </div>
                    </div>
                  </div>
                
                  <div class="text-center">
                    <button type="button" class="btn btn-success" onClick="searchFilterDeclinedA()" ><i class="icon-search4 position-left"></i> Search</button>
                    <button type="button" class="btn btn-primary" onClick="resetformDeclinedA()" ><i class="icon-reset position-left"></i> Reset</button>
                    <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
                  </div>
                </form>
              </div>
              <div class="loading_3_a" style="display: none;">
                <center>
                  <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
                </center>
              </div>
              <div class="post-list" id="postListDeclinedA"> 
                <div  class="table-responsive table-spraye">
                  <table  class="table datatable-colvis-state " id="declined_estimates_a" >
                    <thead>  
                      <tr>
                          <!-- <th><input type="checkbox" id="select_all" /></th> -->
                          <th>Sales Rep/Source</th>
                          <!-- <th># of estimates declined in date range</th> -->
                          <th># of estimates declined in comparison range</th>
                          <th>Difference Close Rate %</th>
                          <th>Difference Close Rate $</th>
                          
                      </tr>  
                    </thead>
                    <tbody id="declined_estimates_tbody_a">
                      <?php 
                          if (!empty($report_summary)) {
                            // $total_open = 0;
                            // $total_estimates = 0;
                            // $total_accepted = 0;
                            // $accepted_total = 0;
                            $total_declined = 0;
                            // $declined_total = 0;
                            $closed_rate_total = 0;
                            $closed_rate_amt = 0;
  
                            foreach ($report_summary as $value) { 
                        ?>
  
                      <tr>
                        <!-- <td></td> -->
                        <td ><?= $value['rep_name'] ?></td>
                        <!-- <td></td> -->
                        <td><?= $value['declined'] ?></td>
                        <td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                        <td>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
                      </tr>
                      <?php  
                            // $total_open += $value['total_estimates'];
                            // $total_estimates += $value['total_estimates'];
                            // $total_accepted += $value['accepted'];
                            // $accepted_total += $value['accepted_total'];
                            $total_declined += $value['declined'];
                            // $declined_total += $value['declined_total'];
                            $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                            $closed_rate_amt += number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2);
                            }
                          ?>
                          
                        <tr>
                          <td><b>TOTALS</b></td>
                          <!-- <td><b><?= number_format($total_declined) ?></b></td> -->
                          <td><b><?= number_format($total_declined) ?></b></td>
                          <td><b><?= number_format($closed_rate_total/count($report_summary)) ?>%</b></td>
                          <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>
                          <!-- <td></td> -->
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
          <!-- end TOTAL ACCEPTED ESTIMATES -->
          
        </div>
      </div>
      
    </div>
  </div>
</div>





<script>



// function resetform(){

//   $('#searchform')[0].reset();
//   searchFilter();
// }
function resetformNew(){

  $('#searchform_new')[0].reset();
  searchFilterNew();
}
function resetformNew1A(){

  $('#searchform_new_a-1')[0].reset();
  searchFilterNew1A();
}
function resetformNewA(){

  $('#searchform_new_a')[0].reset();
  searchFilterNewA();
}
function resetformAccepted(){

  $('#searchform_accepted')[0].reset();
  searchFilterAccepted();
}
function resetformAcceptedA(){

  $('#searchform_accepted_a')[0].reset();
  searchFilterAcceptedA();
}
function resetformDeclined(){

  $('#searchform_declined')[0].reset();
  searchFilterDeclined();
}
function resetformDeclinedA(){

  $('#searchform_declined_a')[0].reset();
  searchFilterDeclinedA();
}


// function searchFilter() {
//     var sales_rep_id = $('#sales_rep_id').val();
//     // alert(sales_rep_id);
//     var estimate_created_date_to = $('#estimate_created_date_to').val();
//     var estimate_created_date_from = $('#estimate_created_date_from').val();
//     var date_range_date_to = $('#date_range_date_to').val();
//     var date_range_date_from = $('#date_range_date_from').val();
//     var comparision_range_date_to = $('#comparision_range_date_to').val();
//     var comparision_range_date_from = $('#comparision_range_date_from').val();
//     $('.loading').css("display", "block");
//    $('#postList').html('');
//     $.ajax({
//         type: 'POST',
//         url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryData/',
//         data:'sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
//         success: function (html) {
//             $(".loading").css("display", "none");
//             $('#postList').html(html);
//             tableintal();
          
//         }
//     });
// }
function searchFilterNew() {
    var sales_rep_id = $('#searchform_new #sales_rep_id_new').val();
    // alert(sales_rep_id);
    var date_range_date_to = $('#searchform_new #date_range_date_to_new').val();
    var date_range_date_from = $('#searchform_new #date_range_date_from_new').val();
    $('.loading_1').css("display", "block");
   $('#postListNew').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataNew/',
        data:'sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from,
        
        success: function (html) {
            $(".loading_1").css("display", "none");
            $('#postListNew').html(html);
            tableintalNew();
          
        }
    });
}
function searchFilterNew1A() {
  var sales_rep_id = $('#searchform_new #sales_rep_id_new').val();
    // alert(sales_rep_id);
    var date_range_date_to = $('#searchform_new #date_range_date_to_new').val();
    var date_range_date_from = $('#searchform_new #date_range_date_from_new').val();
    $('.loading_1a').css("display", "block");
   $('#postListNewA-1').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataNew1A/',
        data:'sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from,

        success: function (html) {
            $(".loading_1a").css("display", "none");
            $('#postListNewA-1').html(html);
            tableintalNew1A();
          
        }
    });
}
function searchFilterNewA() {
    var sales_rep_id = $('#searchform_new_a #sales_rep_id_new_a').val();
    var comparision_range_date_to = $('#searchform_new_a #comparision_range_date_to_new_a').val();
    var comparision_range_date_from = $('#searchform_new_a #comparision_range_date_from_new_a').val();
    $('.loading_1_a').css("display", "block");
   $('#postListNewA').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataNewA/',
        data:'sales_rep_id='+sales_rep_id+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_1_a").css("display", "none");
            $('#postListNewA').html(html);
            tableintalNewA();
          
        }
    });
}
function searchFilterAccepted() {
    var sales_rep_id = $('#sales_rep_id_accepted').val();
    // alert(sales_rep_id);
    var date_range_date_to = $('#searchform_accepted #date_range_date_to_accepted').val();
    var date_range_date_from = $('#searchform_accepted #date_range_date_from_accepted').val();
    
    $('.loading_2').css("display", "block");
   $('#postListAccepted').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataAccepted/',
        data:'sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from,
        
        success: function (html) {
            $(".loading_2").css("display", "none");
            $('#postListAccepted').html(html);
            tableintalAccepted();
          
        }
    });
}
function searchFilterAcceptedA() {
    var sales_rep_id = $('#sales_rep_id_accepted_a').val();
    // alert(sales_rep_id);
    var comparision_range_date_to = $('#searchform_accepted_a #comparision_range_date_to_accepted_a').val();
    var comparision_range_date_from = $('#searchform_accepted_a #comparision_range_date_from_accepted_a').val();
    $('.loading_2_a').css("display", "block");
   $('#postListAcceptedA').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataAcceptedA/',
        data:'sales_rep_id='+sales_rep_id+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_2_a").css("display", "none");
            $('#postListAcceptedA').html(html);
            tableintalAcceptedA();
          
        }
    });
}

function searchFilterDeclined() {
    var sales_rep_id = $('#searchform_declined #sales_rep_id_declined').val();
    // alert(sales_rep_id);
    var date_range_date_to = $('#searchform_declined #date_range_date_to_declined').val();
    var date_range_date_from = $('#searchform_declined #date_range_date_from_declined').val();
    $('.loading_3').css("display", "block");
   $('#postListDeclined').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataDeclined/',
        data:'sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from,
        
        success: function (html) {
            $(".loading_3").css("display", "none");
            $('#postListDeclined').html(html);
            tableintalDeclined();
          
        }
    });
}
function searchFilterDeclinedA() {
    var sales_rep_id = $('#searchform_declined_a #sales_rep_id_declined_a').val();
    // alert(sales_rep_id);
    var comparision_range_date_to = $('#searchform_declined_a #comparision_range_date_to_declined_a').val();
    var comparision_range_date_from = $('#searchform_declined_a #comparision_range_date_from_declined_a').val();
    $('.loading_3_a').css("display", "block");
   $('#postListDeclinedA').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataDeclinedA/',
        data:'sales_rep_id='+sales_rep_id+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_3_a").css("display", "none");
            $('#postListDeclinedA').html(html);
            tableintalDeclinedA();
          
        }
    });
}



   $(document).ready(function() {
      // tableintal();
      tableintalNew();
      tableintalNew1A();
      tableintalNewA();
      tableintalAccepted();
      tableintalAcceptedA();
      tableintalDeclined();
      tableintalDeclinedA();

   })

  //  function tableintal(argument) {
  //     // $('.datatable-colvis-state').DataTable({
  //     $('#overall').DataTable({
  //       buttons: [
  //         {
  //           extend: 'colvis',
  //           text: '<i class="icon-grid3"></i> <span class="caret"></span>',
  //           className: 'btn bg-indigo-400 btn-icon'
  //         }
  //       ],
  //       stateSave: true,
  //       columnDefs: [
  //           {
  //               targets: -1,
  //               visible: false
  //           }
  //       ],

          
  //   });
  //  }

   function tableintalNew(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#new_estimates').DataTable({
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
   function tableintalNew1A(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#new_estimates_a-1').DataTable({
        buttons: [
          {
            // extend: 'colvis',
            dom: 'lrtip',
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
   function tableintalNewA(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#new_estimates_a').DataTable({
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

   function tableintalAccepted(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#accepted_estimates').DataTable({
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
   function tableintalAcceptedA(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#accepted_estimates_a').DataTable({
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
   function tableintalDeclined(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#declined_estimates').DataTable({
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
   function tableintalDeclinedA(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#declined_estimates_a').DataTable({
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
    var sales_rep_id = $('#sales_rep_id').val();
    var estimate_created_date_to = $('#estimate_created_date_to').val();
    var estimate_created_date_from = $('#estimate_created_date_from').val();
    var date_range_date_to = $('#date_range_date_to').val();
    var date_range_date_from = $('#date_range_date_from').val();
    var comparision_range_date_to = $('#comparision_range_date_to').val();
    var comparision_range_date_from = $('#comparision_range_date_from').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/downloadSalesSummaryCsv/',
        data:'sales_rep_id='+sales_rep_id+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,

        success: function (response) {
      //    alert(response);
        }
    });
}


</script>
