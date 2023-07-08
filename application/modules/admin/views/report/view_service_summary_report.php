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

			<div class="panel panel-body" style="background-color:#ededed;">
				<form id="serchform-new" action="<?= base_url('admin/reports/downloadServiceSummaryCsv') ?>" method="post">            
					<div class="row">
						<div class="col-md-4 multi-select-full">
							<?php
							$SelectedProgramms = array();
							if(isset($SavedFilter['id'])){
								$SelectedSalesRep = explode(",", $SavedFilter["service_name"]);
							}
							?>

							<label>Service</label>
							<select id="job_name" name="job_name[]" multiple class="multiselect-select-all-filtering">
								<?php if ($service_details) {
									foreach ($service_details as $user) { ?>
										<option <?php if(in_array($user->job_id, $SelectedSalesRep)) { echo 'selected'; } ?> value=<?= $user->job_id ?>> <?= $user->job_name ?> </option>
									<?php } } ?>
							</select>
						</div>

						<div class="col-md-4">
							<label>Date Range Start</label>
							<input type="date" id="date_range_date_from" name="date_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $SavedFilter["end_date"] ?>">
						</div>
						<div class="col-md-4">
							<label>Date Range End</label>
							<input type="date" id="date_range_date_to" name="date_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $SavedFilter["start_date"] ?>">
						</div>
					</div>

					<div class="row" style="margin-top: 10px">
						<?php
						$SelectedProgramms = array();
						if(isset($SavedFilter['id'])){
							$SelectedSalesRep = explode(",", $SavedFilter["program_ids"]);
						}
						?>

						<div class="col-md-4 multi-select-full">
							<label>Program</label>
							<select id="program_ids" name="program_ids[]" multiple class="multiselect-select-all-filtering" placeholder="Select Rep">
								<?php if ($program_details) {
									foreach ($program_details as $user) { ?>
										<option <?php if(in_array($user->program_id, $SelectedSalesRep)) { echo 'selected'; } ?> value=<?= $user->program_id ?>> <?= $user->program_name ?> </option>
									<?php } } ?>
							</select>
						</div>

						<div class="col-md-4">
							<label>Comparison Range Start</label>
							<input type="date" id="comparision_range_date_to" name="comparision_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $SavedFilter["compare_start_date"] ?>">
						</div>

						<div class="col-md-4">
							<label>Comparison Range End</label>
							<input type="date" id="comparision_range_date_from" name="comparision_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $SavedFilter["compare_end_date"] ?>">
						</div>
					</div>

					<div class="row" style="margin-top: 10px">
						<div class="col-md-4 multi-select-full">
							<?php
							$SelectedSalesRep = array();
							if(isset($SavedFilter['id'])){
								$SelectedSalesRep = explode(",", $SavedFilter["techniciean_ids"]);
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
			      <div class="col-md-4">
			      	<label>Service Type</label>
			      	<select id="service_type" name="service_type" class="form-control" style="background: #FFF" placeholder="Service Type">
			      		<option value="">All</option>
			      		<?php
			      		foreach ($service_types as $user) { ?>
			      			<option <?php if($user->service_type_id == $SavedFilter['service_type']) { echo 'selected'; } ?> value=<?= $user->service_type_id ?>> <?= $user->service_type_name ?> </option>
			      		<?php } ?>
			      	</select>
			      </div>
			    </div>

			    <div class="text-center" style="margin-top: 15px;">
			    	<button type="button" class="btn btn-success" onClick="searchFilterNew()" ><i class="icon-search4 position-left"></i> Search</button>
			    	<button type="button" class="btn btn-primary" onClick="resetFormNew()" ><i class="icon-reset position-left"></i> Reset</button>
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
        <div class="tab-content" id="LoadNewReportData">
			<!-- NEW ESTIMATES -->
          <div class="tab-pane <?php echo $active_nav_link=='0' ? 'active' : ''  ?>" id="highlighted-justified-tab0">
			<div class="row">
				<div class="col-md-12">
					<div class="post-list" id="postListNew"> 
						<div  class="table-responsive table-spraye" id="total-new-estimates">
							<table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;"  id="total-new-estimates-table">
								<thead>  
									<tr>
										<th>Service</th>
										<th>Service Type</th>
										<th>Estimates Created</th>
										<th>Estimate Close Rate</th>
										<th>Revenue Close Rate</th>
									</tr>  
								</thead>
								<tbody id="new_estimates_tbody">
									<?php 
										if (!empty($service_summary)) {
										$closed_rate_total = 0;
										$closed_rate_amt = 0;
										foreach ($service_summary as $value){
									?>

									<tr>
										<td ><?= $value['job_name'] ?></td>
										<td ><?= $value['service_type_name'] ?></td>
										<td><?= $value['total_estimates'] ?></td>
										<td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
										<td><?= (number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2)*100) ?>%</td>
									
									</tr>
									<?php
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
										<td colspan="2"><b>TOTALS</b><span data-popup="tooltip-custom" title="The total shown is the total number of estimates created and not totaln number of services." data-placement="right"> <i class=" icon-info22 tooltip-icon"></i> </span>
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
										<th>Service Type</th>
										<th>Estimates Accepted</th>
										<th>Estimate Close Rate</th>
										<th>Revenue Close Rate</th>
									</tr>  
								</thead>
								<tbody>
									<?php 
										if (!empty($service_summary)) {
										$total_accepted = 0;
										$closed_rate_total = 0;
										$closed_rate_amt = 0;

										foreach ($service_summary as $value) { 
									?>

									<tr>
										<td ><?= $value['job_name'] ?></td>
										<td ><?= $value['service_type_name'] ?></td>
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
										<td colspan="2">
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
										<th>Service Type</th>
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
										<td ><?= $value['service_type_name'] ?></td>
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
										<td colspan="2"><b>TOTALS</b>
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
		<div class="loading" style="display: none;">
			<center>
				<img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
			</center>
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
    var program_ids = $("#program_ids").val();
    var service_type = $("#service_type").val();

    $('.loading').css("display", "block");
   $('#LoadNewReportData').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataNew/',
        data:'job_name='+job_name+'&sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from+"&program_ids="+program_ids+"&service_type="+service_type,
        
        success: function (html) {
            $(".loading").css("display", "none");
            $('#LoadNewReportData').html(html);
            tableintalNew();
            tableintalAccepted();
            tableintalDeclined();
        }
    });

	/*$('.loading_2').css("display", "block");
   $('#postListAccepted').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataAccepted/',
        data:'job_name='+job_name+'&sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from+"&program_ids="+program_ids+"&service_type="+service_type,
        
        success: function (html) {
            $(".loading_2").css("display", "none");
            $('#postListAccepted').html(html);
            tableintalAccepted(); ///CHECK FUNCTION
        }
    });*/

	/*$('.loading_3').css("display", "block");
   $('#postListDeclined').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataDeclined/', ///// CHECK URL
        data:'job_name='+job_name+'&sales_rep_id='+sales_rep_id+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from+"&program_ids="+program_ids+"&service_type="+service_type,
        
        success: function (html) {
            $(".loading_3").css("display", "none");
            $('#postListDeclined').html(html);
            tableintalDeclined(); ///CHECK FUNCTION
        }
    });*/
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




function saveSearchFilter(){
    var technician_id = $('#sales_rep_id').val();
    var start_date = $('#date_range_date_to').val();
    var end_date = $('#date_range_date_from').val();
    var compare_start_date = $('#comparision_range_date_to').val();
    var compare_end_date = $('#comparision_range_date_from').val();
    var service_name = $('#job_name').val();
    var program_ids = $("#program_ids").val();
    var service_type = $("#service_type").val();

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/saveServiceSummaryFilters',
        data:'techniciean_ids='+technician_id+'&start_date='+start_date+'&end_date='+end_date+'&compare_start_date='+compare_start_date+'&compare_end_date='+compare_end_date+'&service_name='+service_name+"&program_ids="+program_ids+"&service_type="+service_type,

        success: function (resp) {
            swal('Save','Filter Saved Successfully ','success')
        },
  });
}
</script>