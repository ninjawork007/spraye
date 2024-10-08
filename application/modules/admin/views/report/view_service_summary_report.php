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
</style>
<div class="content">
  <div class="panel panel-flat">
    <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
	<div class="panel panel-body" style="background-color:#ededed;" >
		<form id="serchform-new" action="<?= base_url('admin/reports/downloadServiceSummaryCsv') ?>" method="post">            
			<div class="row">
				<div class="col-md-4">
					<div class="row">
						<div class="form-group">
						<label>Service</label>
						<input type="text" id="job_name" name="job_name" class="form-control" placeholder="Enter Service Name">
						</div>
					</div>
					<div class="row">
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
				</div>
				<div class="col-md-8">
					<div class="row">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<label>Date Range Start</label>
								<input type="date" id="date_range_date_to" name="date_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label>Date Range End</label>
									<input type="date" id="date_range_date_from" name="date_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Comparison Range Start</label>
									<input type="date" id="comparision_range_date_to" name="comparision_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label>Comparison Range End</label>
									<input type="date" id="comparision_range_date_from" name="comparision_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
			</div>

			<div class="text-center">
			<button type="button" class="btn btn-success" onClick="searchFilterNew()" ><i class="icon-search4 position-left"></i> Search</button>
			<button type="button" class="btn btn-primary" onClick="resetFormNew()" ><i class="icon-reset position-left"></i> Reset</button>
			<button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
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
			<!-- NEW ESTIMATES -->
          <div class="tab-pane <?php echo $active_nav_link=='0' ? 'active' : ''  ?>" id="highlighted-justified-tab0">
			<div class="row">
				<div class="col-md-12">
					
					<div class="loading" style="display: none;">
						<center>
							<img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
						</center>
					</div>
					<div class="post-list" id="postListNew"> 
						<div  class="table-responsive table-spraye" id="total-new-estimates">
							<table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;"  id="total-new-estimates-table">
								<thead>  
									<tr>
										<th>Service123</th>
										<th>Estimates Created</th>
										<th>Estimate Close Rate</th>
										<th>Revenue Close Rate</th>
									</tr>  
								</thead>
								<tbody id="new_estimates_tbody">
									<?php 
										if (!empty($service_summary)) {
										// $total_estimates = 0;
										$closed_rate_total = 0;
										$closed_rate_amt = 0;

										foreach ($service_summary as $value) { 
									?>

									<tr>
										<td ><?= $value['job_name'] ?></td>
										<td><?= $value['total_estimates'] ?></td>
										<td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
										<td><?= (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100) ?>%</td>
									
									</tr>
									<?php
										// $total_estimates += $value['total_estimates'];
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
										<td><b>TOTALS</b><span data-popup="tooltip-custom" title="The total shown is the total number of estimates created and not totaln number of services." data-placement="right"> <i class=" icon-info22 tooltip-icon"></i> </span>
										</td>
										<td><b><?= (isset($total_open_estimate)?$total_open_estimate:0) ?></b></td>
										<td><b><?= (isset($closed_rate_total)?number_format($closed_rate_total/count($service_summary)):0) ?>%</b></td>
										<td><b><?= (isset($closed_rate_total)?number_format($closed_rate_amt/count($service_summary)):0) ?>%</b></td>
									</tr>
                                </tfoot>
							</table>  
						</div> 
					</div>
				</div>
			</div>
          </div>
			<!-- ACCEPTED ESTIMATES -->
          <div class="tab-pane <?php echo $active_nav_link=='1' ? 'active' : ''  ?>" id="highlighted-justified-tab1">
			<div class="row">
				<div class="col-md-12">
					
					<div class="loading_2" style="display: none;">
						<center>
							<img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
						</center>
					</div>
					<div class="post-list" id="postListAccepted"> 
						<div  class="table-responsive table-spraye" id="total-accepted-estimates">
							<table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;"  id="total-accepted-estimates-table">
								<thead>  
									<tr>
										<th>Service</th>
										<th>Estimates Accepted</th>
										<th>Estimate Close Rate</th>
										<th>Revenue Close Rate</th>
									</tr>  
								</thead>
								<tbody id="accepted_estimates_tbody">
									<?php 
										if (!empty($service_summary)) {
										$total_accepted = 0;
										$closed_rate_total = 0;
										$closed_rate_amt = 0;

										foreach ($service_summary as $value) { 
									?>

									<tr>
										<td ><?= $value['job_name'] ?></td>
									<td><?= $value['accepted'] ?></td>
									<td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
									<td><?= (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100) ?>%</td>
									<!-- <td></td> -->
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
										<td>
											<b>TOTALS</b>
											<span

											data-popup="tooltip-custom"
											title="The total shown is the total number of estimates created and not total number of services."
											data-placement="right"> <i class=" icon-info22 tooltip-icon"></i>

											</span>
										</td>
										<td><b><?= (isset($total_accepeted_estimate)?$total_accepeted_estimate:0) ?></b></td>
										<td><b><?= (isset($closed_rate_total)?number_format($closed_rate_total/count($service_summary)):0) ?>%</b></td>
										<td><b><?= (isset($closed_rate_amt)?number_format($closed_rate_amt/count($service_summary)):0) ?>%</b></td>
									</tr>
                                </tfoot>
							</table>  
						</div> 
					</div>
				</div>
			</div>
		  </div>
			<!-- DECLINED ESTIMATES -->
          <div class="tab-pane <?php echo $active_nav_link == '2' ? 'active' : ''  ?>" id="highlighted-justified-tab2">
			<div class="row">
				<div class="col-md-12">
					
					<div class="loading_3" style="display: none;">
						<center>
							<img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
						</center>
					</div>
					<div class="post-list" id="postListDeclined"> 
						<div  class="table-responsive table-spraye" id="total-declined-estimates">
							<table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;"  id="total-declined-estimates-table">
								<thead>  
									<tr>
										<th>Service</th>
										<th>Estimates Declined</th>
										<th>Estimate Close Rate</th>
										<th>Revenue Close Rate</th>

									</tr>  
								</thead>
								<tbody id="declined_estimates_tbody">
									<?php 
										if (!empty($service_summary)) {
										$total_declined = 0;
										$closed_rate_total = 0;
										$closed_rate_amt = 0;

										foreach ($service_summary as $value) { 
									?>

									<tr>
										<td ><?= $value['job_name'] ?></td>
									<td><?= $value['declined'] ?></td>
									<td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
									<td> <?= (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100) ?>%</td>
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
										<td><b>TOTALS</b>
											<span

											data-popup="tooltip-custom"
											title="The total shown is the total number of estimates created and not total number of services."
											data-placement="right"> <i class=" icon-info22 tooltip-icon"></i>

											</span>
										</td>
										<td><b><?= $total_declined_estimate ?></b></td>
										<td><b><?= (isset($closed_rate_total)?number_format($closed_rate_total/count($service_summary)):0) ?>%</b></td>
										<td><b><?= (isset($closed_rate_amt)?number_format($closed_rate_amt/count($service_summary)):0) ?>%</b></td>
									</tr>
                                </tfoot>
							</table>  
						</div>
					</div>
				</div>
			</div>
		  </div> 
				<!-- end decline estimates -->
			</div>
		</div>
		<!-- END TABBABLE -->
    </div>
  </div>
</div>
<script>
/*DOC READY*/
$(document).ready(function(){
	tableintalNew();
	tableintalAccepted();
	tableintalDeclined();
});
/*NEW ESTIMATE FUNCTIONS*/
function resetFormNew(){
  $('#serchform-new')[0].reset();
  searchFilterNew();
}

function searchFilterNew() {
    var job_name = $('#serchform-new #job_name').val();
    var sales_rep_id = $('#serchform-new #sales_rep_id').val();
    var date_range_date_to = $('#serchform-new #date_range_date_to').val();
    var date_range_date_from = $('#serchform-new #date_range_date_from').val();
	var comparision_range_date_to = $('#serchform-new #comparision_range_date_to').val();
    var comparision_range_date_from = $('#serchform-new #comparision_range_date_from').val();
    $('.loading').css("display", "block");
   $('#postListNew').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataNew/', ///// CHECK URL
        data:'job_name='+job_name+'&sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading").css("display", "none");
            $('#postListNew').html(html);
            tableintalNew(); ///CHECK FUNCTION
        }
    });

	$('.loading_2').css("display", "block");
   $('#postListAccepted').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataAccepted/', ///// CHECK URL
        data:'job_name='+job_name+'&sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_2").css("display", "none");
            $('#postListAccepted').html(html);
            tableintalAccepted(); ///CHECK FUNCTION
        }
    });

	$('.loading_3').css("display", "block");
   $('#postListDeclined').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataDeclined/', ///// CHECK URL
        data:'job_name='+job_name+'&sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_3").css("display", "none");
            $('#postListDeclined').html(html);
            tableintalDeclined(); ///CHECK FUNCTION
        }
    });
}

function tableintalNew(argument){
	$('#total-new-estimates-table').DataTable({
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

/*ACCEPTED ESTIMATE FUNCTIONS*/
function tableintalAccepted(argument){
	$('#total-accepted-estimates-table').DataTable({
        "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?> ,
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

/*DECLINED ESTIMATE FUNCTIONS*/ 
function tableintalDeclined(argument){
	
	$('#total-declined-estimates-table').DataTable({
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

// function csvfileDeclined() {
//   var job_name = $('#serchform-declined #job_name').val();
//   var estimate_created_date_to = $('#serchform-declined #estimate_created_date_to').val();
//   var estimate_created_date_from = $('#serchform-declined #estimate_created_date_from').val();
//   var date_range_date_to = $('#serchform-declined #date_range_date_to').val();
//   var date_range_date_from = $('#serchform-declined #date_range_date_from').val();
//   var comparision_range_date_to = $('#serchform-declined #comparision_range_date_to').val();
//   var comparision_range_date_from = $('#serchform-declined #comparision_range_date_from').val();
//   $.ajax({
//       type: 'POST',
//       url: '<?php echo base_url(); ?>admin/reports/downloadServiceSummaryCsv/', ////CHANGE URL
// 	  data:'job_name='+job_name+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
//       success: function (response) {
//     //    alert(response);
//       }
//   });
// }
</script>
<script>
    $('[data-popup=tooltip-custom]').tooltip({
        template: '<div class="tooltip"><div class="bg-teal"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div></div>'
    });
</script>