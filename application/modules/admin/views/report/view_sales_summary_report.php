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
      <div class="panel panel-body" style="background-color:#ededed;" >
        <form id="searchform_new" action="<?= base_url('admin/reports/downloadSalesSummaryCsv') ?>" method="post">            
          <div class="row">
            <div class="col-md-12">
              <div class="form-group multi-select-full">
                <?php
                $SelectedSalesRep = array();
                if(isset($SavedFilter['id'])){
                  $SelectedSalesRep = explode(",", $SavedFilter["techniciean_ids"]);
                }
                ?>
                <label>Sales Rep</label>
                <select id="sales_rep_id_new" name="sales_rep_id[]" multiple class="multiselect-select-all-filtering" placeholder="Select Rep">
                   <?php if ($users) {
                    foreach ($users as $user) { ?>
                      <option <?php if(in_array($user->id, $SelectedSalesRep)) { echo 'selected'; } ?> value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
                  <?php } } ?>                        
                  </select>
              </div>
              
            </div>

            <?php
            $StartDate = "";
            $EndDate = "";
            $CompareStart = "";
            $CompareEnd = "";

            if(isset($SavedFilter['id'])){
              $StartDate = $SavedFilter["start_date"];
            }

            if(isset($SavedFilter['id'])){
              $EndDate = $SavedFilter["end_date"];
            }

            if(isset($SavedFilter['id'])){
              $CompareStart = $SavedFilter["compare_start_date"];
            }

            if(isset($SavedFilter['id'])){
              $CompareEnd = $SavedFilter["compare_end_date"];
            }
            ?>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Date Range Start</label>
                  <input type="date" id="date_range_date_to_new" name="date_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $StartDate?>">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Date Range End</label>
                  <input type="date" id="date_range_date_from_new" name="date_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $EndDate?>">
                </div>
              </div>

            </div>
            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <label>Compare Start Date</label>
                  <input type="date" id="comparision_range_date_to_new" name="comparision_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $CompareStart?>">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Compare End Date</label>
                  <input type="date" id="comparision_range_date_from_new" name="comparision_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $CompareEnd?>">
                </div>
              </div>
            </div>
          </div>
        
          <div class="text-center">
            <button type="button" class="btn btn-success" onClick="searchFilterNew()" ><i class="icon-search4 position-left"></i> Search</button>
            <button type="button" class="btn btn-primary" onClick="resetformNew()" ><i class="icon-reset position-left"></i> Reset</button>
            <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
            <button type="button" class="btn btn-success" onClick="saveSearchFilter()" ><i class="icon-file-text2 position-left"></i> Save Search</button>
          </div>
        </form>
      </div>
      

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
              <div class=" col-md-12">
                
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
                          <th>Sales Rep/Source</th>
                          <th>Estimates Created</th>
                          <th>Estimate Close Rate</th>
                          <th>Revenue Close Rate</th>
                        </tr>  
                      </thead>
                      <tbody id="new_estimates_tbody">
                      <?php 
                            if (!empty($report_summary)) {
                              $total_open = 0;
                              $closed_rate_total = 0;
                              $closed_rate_amt = 0;

                              foreach ($report_summary as $value) {
                          ?>

                        <tr>
                          <td ><?= ($value['rep_name']!=" "&&$value['rep_name']!=""?$value['rep_name']:$value['source']) ?></td>
                          <td><?= $value['total_estimates'] ?></td>
                          <td class='close-rate-1'><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                          <td class='close-dollar-1'> <?= (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100) ?>%</td>
                        
                        </tr>
                        <?php  
                              $total_open += $value['total_estimates'];
                              $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                              $closed_rate_amt += (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100);
                              }
                            
                            } else { 
                            ?> 

                          <tr>
                            <td> No record found </td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>

                          <?php }  ?>

                      </tbody>
                      <tfoot>
                        <tr>
                            <td><b>TOTALS</b></td>
                            <td><b><?= (isset($total_open)?number_format($total_open):0) ?></b></td>
                            <td><b><?= (isset($closed_rate_total)?number_format($closed_rate_total/count($report_summary)):0) ?>%</b></td>
                            <td><b><?= (isset($closed_rate_total)?number_format($closed_rate_amt/count($report_summary)):0) ?>%</b></td>
                           
                          </tr>     
                      </tfoot>
                    </table>  
                  </div>
                </div>   
              </div>
            </div>
          </div>
          <!-- End of jquery to add comparison column -->
          <!-- end NEW REPORT -->
          <!-- TOTAL ACCEPTED ESTIMATES -->
          <div class="tab-pane <?php echo $active_nav_link=='1' ? 'active' : ''  ?>" id="highlighted-justified-tab1">
            <div class="row">
              <div class=" col-md-12">
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
                            <th>Sales Rep/Source</th>
                            <th>Estimates Accepted</th>
                            <th>Estimate Close Rate</th>
                            <th>Revenue Close Rate</th>
                        </tr>  
                      </thead>
                      <tbody id="accepted_estimates_tbody">
                        <?php 
                            if (!empty($report_summary)) {
                              $total_accepted = 0;
                              $closed_rate_total = 0;
                              $closed_rate_amt = 0;
    
                              foreach ($report_summary as $value) { 
                          ?>
    
                        <tr>
                          <td ><?= $value['rep_name'] ?></td>
                          <td><?= $value['accepted'] ?></td>
                          <td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                          <td><?= (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100) ?>%</td>
                        </tr>
                        <?php  
                              
                              $total_accepted += $value['accepted'];
                              $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                              $closed_rate_amt += (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100);
                              }
                            
                            } else { 
                            ?> 
    
                          <tr>
                            <td> No record found </td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
    
                          <?php }  ?>
    
                      </tbody>
                      <tfoot>
                        <tr>
                            <td><b>TOTALS</b></td>
                            <td><b><?= (isset($total_accepted)?number_format($total_accepted):0) ?></b></td>
                            <td><b><?= (isset($closed_rate_total)?number_format($closed_rate_total/count($report_summary)):0) ?>%</b></td>
                            <td><b><?= (isset($closed_rate_amt)?number_format($closed_rate_amt/count($report_summary)):0) ?>%</b></td>
                          </tr>     
                      </tfoot>
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
            <div class=" col-md-12">
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
                          <th>Sales Rep/Source</th>
                          <th>Estimates Declined</th>
                          <th>Estimate Close Rate</th>
                          <th>Revenue Close Rate</th>
                          
                      </tr>  
                    </thead>
                    <tbody id="declined_estimates_tbody">
                      <?php 
                          if (!empty($report_summary)) {
                            
                            $total_declined = 0;
                            $closed_rate_total = 0;
                            $closed_rate_amt = 0;
  
                            foreach ($report_summary as $value) { 
                        ?>
  
                      <tr>
                        <td ><?= $value['rep_name'] ?></td>
                        <td><?= $value['declined'] ?></td>
                        <td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
                        <td><?= (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100) ?>%</td>
                      </tr>
                      <?php  
                            $total_declined += $value['declined'];
                            $closed_rate_total += (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100);
                            $closed_rate_amt += (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100);
                            }
                          
                          } else { 
                          ?> 
  
                        <tr>
                            <td> No record found </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
  
                        <?php }  ?>
  
                    </tbody>
                    <tfoot>
                      <tr>
                          <td><b>TOTALS</b></td>
                          <td><b><?= (isset($total_declined)?number_format($total_declined):0) ?></b></td>
                          <td><b><?= (isset($closed_rate_total)?number_format($closed_rate_total/count($report_summary)):0) ?>%</b></td>
                          <td><b><?= (isset($closed_rate_amt)?number_format($closed_rate_amt/count($report_summary)):0) ?>%</b></td>
                        </tr> 
                    </tfoot>
                  </table>  
                </div>
              </div>  
            </div>
          </div>
          </div>
          <!-- end TOTAL DECLINED ESTIMATES -->
          
        </div>
      </div>
      
    </div>
  </div>
</div>

<script>

// var d = new Date();
//   var currMonth = d.getMonth();
//   var currYear = d.getFullYear();
//   var startDate = new Date(currYear, currMonth, 1);

//   $("#date_range_date_to_new").datepicker();
//   $("#datepicker").datepicker("setDate", startDate);

function resetformNew(){

  $('#searchform_new')[0].reset();
  searchFilterNew();
}

function searchFilterNew() {
    var sales_rep_id = $('#sales_rep_id_new').val();
    var date_range_date_to = $('#searchform_new #date_range_date_to_new').val();
    var date_range_date_from = $('#searchform_new #date_range_date_from_new').val();
    var comparision_range_date_to = $('#searchform_new #comparision_range_date_to_new').val();
    var comparision_range_date_from = $('#searchform_new #comparision_range_date_from_new').val();
    $('.loading_1').css("display", "block");
   $('#postListNew').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataNew/',
        data:'sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_1").css("display", "none");
            $('#postListNew').html(html);
            tableintalNew();
          
        }
    });

    // loads accepted tab
    $('.loading_2').css("display", "block");
   $('#postListAccepted').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataAccepted/',
        data:'sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_2").css("display", "none");
            $('#postListAccepted').html(html);
            tableintalAccepted();
          
        }
    });

    $('.loading_3').css("display", "block");
   $('#postListDeclined').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxSalesSummaryDataDeclined/',
        data:'sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_3").css("display", "none");
            $('#postListDeclined').html(html);
            tableintalDeclined();
          
        }
    });
}

   $(document).ready(function() {
      tableintalNew();
      tableintalAccepted();
      tableintalDeclined();
      searchFilterNew();
   })

   function tableintalNew(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#new_estimates').DataTable({
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

   function tableintalAccepted(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#accepted_estimates').DataTable({
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
  
   function tableintalDeclined(argument) {
      // $('.datatable-colvis-state').DataTable({
      $('#declined_estimates').DataTable({
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



function saveSearchFilter(){
    var technician_id = $('#sales_rep_id_new').val();
    var start_date = $('#date_range_date_to_new').val();
    var end_date = $('#date_range_date_from_new').val();
    var compare_start_date = $('#comparision_range_date_to_new').val();
    var compare_end_date = $('#comparision_range_date_from_new').val();

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/saveSalesSummaryFilters',
        data:'techniciean_ids='+technician_id+'&start_date='+start_date+'&end_date='+end_date+'&compare_start_date='+compare_start_date+'&compare_end_date='+compare_end_date,

        success: function (resp) {
            swal('Save','Filter Saved Successfully ','success')
        },
  });
}
</script>
