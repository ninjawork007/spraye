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
				<div class="column">
					<div class="panel panel-flat">
						<div class="panel-body">
							<div class="panel panel-body" style="background-color:#ededed;" >
								<form id="serchform-new" action="<?= base_url('admin/reports/downloadServiceSummaryCsvNew') ?>" method="post">            
									<div class="row">
									<div class="col-md-4">
										<div class="form-group">
										<label>Service</label>
										<input type="text" id="job_name" name="job_name" class="form-control" placeholder="Enter Service Name">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
										<label>Date Range Start</label>
										<input type="date" id="date_range_date_to" name="date_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
										<label>Date Range End</label>
										<input type="date" id="date_range_date_from" name="date_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
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
						</div>
					</div>
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
										<!-- <th><input type="checkbox" id="select_all" /></th> -->
										<th>Service</th>
										<th>Total New Estimates</th>
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
									<td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
									<td>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
									<!-- <td></td> -->
									</tr>
									<?php  
										// $total_open += $value['total_estimates'];
										$total_estimates += $value['total_estimates'];
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
										<td><b><?= number_format($total_estimates) ?></b></td>
										<td><b><?= number_format($closed_rate_total/count($service_summary)) ?>%</b></td>
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
				<div class="column">
					<div class="panel panel-flat">
						<div class="panel-body">
							<div class="panel panel-body" style="background-color:#ededed;" >
								<form id="serchform-new-a" action="<?= base_url('admin/reports/downloadServiceSummaryCsvNewA') ?>" method="post">            
									<div class="row">
									<div class="col-md-4">
										<div class="form-group">
										<label>Service</label>
										<input type="text" id="job_name_a" name="job_name" class="form-control" placeholder="Enter Service Name">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
										<label>Comparison Range Start</label>
										<input type="date" id="comparision_range_date_to_a" name="comparision_range_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
										<label>Comparison Range End</label>
										<input type="date" id="comparision_range_date_from_a" name="comparision_range_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
										</div>
									</div>
									</div>

									<div class="text-center">
									<button type="button" class="btn btn-success" onClick="searchFilterNewA()" ><i class="icon-search4 position-left"></i> Search</button>
									<button type="button" class="btn btn-primary" onClick="resetFormNewA()" ><i class="icon-reset position-left"></i> Reset</button>
									<button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="loading_a" style="display: none;">
						<center>
							<img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
						</center>
					</div>
					<div class="post-list" id="postListNewA"> 
						<div  class="table-responsive table-spraye" id="total-new-estimates-a">
							<table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;" id="total-new-estimates-table-a">
								<thead>  
									<tr>
										<!-- <th><input type="checkbox" id="select_all" /></th> -->
										<th>Service</th>
										<th>Total New Estimates</th>
										<th>Difference Close Rate %</th>
										<th>Difference Close Rate $</th>
									</tr>  
								</thead>
								<tbody id="new_estimates_tbody_a">
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
									<td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
									<td>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
									<!-- <td></td> -->
									</tr>
									<?php  
										// $total_open += $value['total_estimates'];
										$total_estimates += $value['total_estimates'];
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
										<td><b><?= number_format($total_estimates) ?></b></td>
										<td><b><?= number_format($closed_rate_total/count($service_summary)) ?>%</b></td>
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
			<!-- ACCEPTED ESTIMATES -->
          <div class="tab-pane <?php echo $active_nav_link=='1' ? 'active' : ''  ?>" id="highlighted-justified-tab1">
				<div class="row">
					<div class="column">
						<div class="panel panel-flat">
							<div class="panel-body">
								<div class="panel panel-body" style="background-color:#ededed;" >
									<form id="serchform-accepted" action="<?= base_url('admin/reports/downloadServiceSummaryCsvAccepted') ?>" method="post">            
										<div class="row">
										<div class="col-md-4">
											<div class="form-group">
											<label>Service</label>
											<input type="text" id="job_name_accepted" name="job_name" class="form-control" placeholder="Enter Service Name">
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
										<button type="button" class="btn btn-primary" onClick="resetFormAccepted()" ><i class="icon-reset position-left"></i> Reset</button>
										<button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="loading_1" style="display: none;">
							<center>
								<img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
							</center>
						</div>
						<div class="post-list" id="postListAccepted"> 
							<div  class="table-responsive table-spraye" id="total-accepted-estimates">
								<table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;"  id="total-accepted-estimates-table">
									<thead>  
										<tr>
											<!-- <th><input type="checkbox" id="select_all" /></th> -->
											<th>Service</th>
											<th># of estimates accepted in date range</th>
											<th>Difference Close Rate %</th>
											<th>Difference Close Rate $</th>
										</tr>  
									</thead>
									<tbody id="accepted_estimates_tbody">
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
										<td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
										<td>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
										<!-- <td></td> -->
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
											<td><b><?= number_format($closed_rate_total/count($service_summary)) ?>%</b></td>
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
					<div class="column">
						<div class="panel panel-flat">
							<div class="panel-body">
								<div class="panel panel-body" style="background-color:#ededed;" >
									<form id="serchform-accepted-a" action="<?= base_url('admin/reports/downloadServiceSummaryCsvAcceptedA') ?>" method="post">            
										<div class="row">
										<div class="col-md-4">
											<div class="form-group">
											<label>Service</label>
											<input type="text" id="job_name_accepted_a" name="job_name" class="form-control" placeholder="Enter Service Name">
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
										<button type="button" class="btn btn-primary" onClick="resetFormAcceptedA()" ><i class="icon-reset position-left"></i> Reset</button>
										<button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="loading_1_a" style="display: none;">
							<center>
								<img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
							</center>
						</div>
						<div class="post-list" id="postListAcceptedA"> 
							<div  class="table-responsive table-spraye" id="total-accepted-estimates-a">
								<table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;"  id="total-accepted-estimates-table-a">
									<thead>  
										<tr>
											<!-- <th><input type="checkbox" id="select_all" /></th> -->
											<th>Service</th>
											<th># of estimates accepted in comparison range</th>
											<th>Difference Close Rate %</th>
											<th>Difference Close Rate $</th>
										</tr>  
									</thead>
									<tbody id="accepted_estimates_tbody_a">
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
											<td><b><?= number_format($closed_rate_total/count($service_summary)) ?>%</b></td>
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
					<!-- DECLINED ESTIMATES -->
          <div class="tab-pane <?php echo $active_nav_link == '2' ? 'active' : ''  ?>" id="highlighted-justified-tab2">
				<div class="row">
					<div class="column">
						<div class="panel panel-flat">
							<div class="panel-body">
								<div class="panel panel-body" style="background-color:#ededed;" >
								<form id="serchform-declined" action="<?= base_url('admin/reports/downloadServiceSummaryCsvDeclined') ?>" method="post">            
									<div class="row">
									<div class="col-md-4">
										<div class="form-group">
										<label>Service</label>
										<input type="text" id="job_name_declined" name="job_name" class="form-control" placeholder="Enter Service Name">
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
									<button type="button" class="btn btn-primary" onClick="resetFormDeclined()" ><i class="icon-reset position-left"></i> Reset</button>
									<button type="submit" class="btn btn-info"><i class="icon-file-download position-left"></i> CSV Download</button>
									</div>
								</form>
								</div>
							</div>
						</div>
						<div class="loading_2" style="display: none;">
							<center>
								<img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
							</center>
						</div>
						<div class="post-list" id="postListDeclined"> 
							<div  class="table-responsive table-spraye" id="total-declined-estimates">
								<table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;"  id="total-declined-estimates-table">
									<thead>  
										<tr>
											<!-- <th><input type="checkbox" id="select_all" /></th> -->
											<th>Service</th>
											<th># of estimates decline in date range</th>
											<th>Difference Close Rate %</th>
											<th>Difference Close Rate $</th>

										</tr>  
									</thead>
									<tbody id="declined_estimates_tbody">
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
										<td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
										<td>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
										<!-- <td></td> -->
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
											<td><b><?= number_format($closed_rate_total/count($service_summary)) ?>%</b></td>
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
					<div class="column">
						<div class="panel panel-flat">
							<div class="panel-body">
								<div class="panel panel-body" style="background-color:#ededed;" >
								<form id="serchform-declined-a" action="<?= base_url('admin/reports/downloadServiceSummaryCsvDeclinedA') ?>" method="post">            
									<div class="row">
									<div class="col-md-4">
										<div class="form-group">
										<label>Service</label>
										<input type="text" id="job_name_declined_a" name="job_name" class="form-control" placeholder="Enter Service Name">
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
									<button type="button" class="btn btn-primary" onClick="resetFormDeclinedA()" ><i class="icon-reset position-left"></i> Reset</button>
									<button type="submit" class="btn btn-info"><i class="icon-file-download position-left"></i> CSV Download</button>
									</div>
								</form>
								</div>
							</div>
						</div>
						<div class="loading_2_a" style="display: none;">
							<center>
								<img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
							</center>
						</div>
						<div class="post-list" id="postListDeclinedA"> 
							<div  class="table-responsive table-spraye" id="total-declined-estimates-a">
								<table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;"  id="total-declined-estimates-table-a">
									<thead>  
										<tr>
											<!-- <th><input type="checkbox" id="select_all" /></th> -->
											<th>Service</th>
											<th># of estimates decline in comparison range</th>
											<th>Difference Close Rate %</th>
											<th>Difference Close Rate $</th>
										</tr>  
									</thead>
									<tbody id="declined_estimates_tbody_a">
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
										<td><?= (number_format((($value['accepted']/max(($value['accepted']+$value['declined']),1))) ,2)*100) ?>%</td>
										<td>$ <?= number_format((($value['accepted_total']/max(($value['accepted_total']+$value['declined_total']),1))) ,2) ?></td>
										<!-- <td></td> -->
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
											<td><b><?= number_format($closed_rate_total/count($service_summary)) ?>%</b></td>
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
	tableintalNewA();
	tableintalAccepted();
	tableintalAcceptedA();
	tableintalDeclined();
	tableintalDeclinedA();
});
/*NEW ESTIMATE FUNCTIONS*/
function resetFormNew(){
  $('#serchform-new')[0].reset();
  searchFilterNew();
}
function resetFormNewA(){
  $('#serchform-new-a')[0].reset();
  searchFilterNewA();
}
function searchFilterNew() {
    var job_name = $('#serchform-new #job_name').val();
    var date_range_date_to = $('#serchform-new #date_range_date_to').val();
    var date_range_date_from = $('#serchform-new #date_range_date_from').val();
    $('.loading').css("display", "block");
   $('#postListNew').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataNew/', ///// CHECK URL
        data:'job_name='+job_name+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from,
        
        success: function (html) {
            $(".loading").css("display", "none");
            $('#postListNew').html(html);
            tableintalNew(); ///CHECK FUNCTION
        }
    });
}
function searchFilterNewA() {
    var job_name = $('#serchform-new-a #job_name_a').val();
    var comparision_range_date_to = $('#serchform-new-a #comparision_range_date_to_a').val();
    var comparision_range_date_from = $('#serchform-new-a #comparision_range_date_from_a').val();
    $('.loading_a').css("display", "block");
   $('#postListNewA').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataNewA/', ///// CHECK URL
        data:'job_name='+job_name+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_a").css("display", "none");
            $('#postListNewA').html(html);
            tableintalNewA(); ///CHECK FUNCTION
        }
    });
}
function tableintalNew(argument){
	$('#total-new-estimates-table').DataTable({
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
function tableintalNewA(argument){
	$('#total-new-estimates-table-a').DataTable({
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
function csvfileNew() {
  var job_name = $('#serchform-new #job_name').val();
  var estimate_created_date_to = $('#serchform-new #estimate_created_date_to').val();
  var estimate_created_date_from = $('#serchform-new #estimate_created_date_from').val();
  var date_range_date_to = $('#serchform-new #date_range_date_to').val();
  var date_range_date_from = $('#serchform-new #date_range_date_from').val();
  var comparision_range_date_to = $('#serchform-new #comparision_range_date_to').val();
  var comparision_range_date_from = $('#serchform-new #comparision_range_date_from').val();
  $.ajax({
      type: 'POST',
      url: '<?php echo base_url(); ?>admin/reports/downloadServiceSummaryCsv/', ////CHANGE URL
	  data:'job_name='+job_name+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
      success: function (response) {
    //    alert(response);
      }
  });
}
/*ACCEPTED ESTIMATE FUNCTIONS*/
function resetFormAccepted(){
  $('#serchform-accepted')[0].reset();
  searchFilterAccepted();
}
function resetFormAcceptedA(){
  $('#serchform-accepted-a')[0].reset();
  searchFilterAcceptedA();
}
function searchFilterAccepted() {
    var job_name = $('#serchform-accepted #job_name_accepted').val();
    var date_range_date_to = $('#serchform-accepted #date_range_date_to_accepted').val();
    var date_range_date_from = $('#serchform-accepted #date_range_date_from_accepted').val();
    $('.loading_1').css("display", "block");
   $('#postListAccepted').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataAccepted/', ///// CHECK URL
        data:'job_name='+job_name+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from,
        
        success: function (html) {
            $(".loading_1").css("display", "none");
            $('#postListAccepted').html(html);
            tableintalAccepted(); ///CHECK FUNCTION
        }
    });
}
function searchFilterAcceptedA() {
    var job_name = $('#serchform-accepted-a #job_name_accepted_a').val();
    var comparision_range_date_to = $('#serchform-accepted-a #comparision_range_date_to_accepted_a').val();
    var comparision_range_date_from = $('#serchform-accepted-a #comparision_range_date_from_accepted_a').val();
    $('.loading_1_a').css("display", "block");
   $('#postListAcceptedA').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataAcceptedA/', ///// CHECK URL
        data:'job_name='+job_name+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_1_a").css("display", "none");
            $('#postListAcceptedA').html(html);
            tableintalAcceptedA(); ///CHECK FUNCTION
        }
    });
}
function tableintalAccepted(argument){
	$('#total-accepted-estimates-table').DataTable({
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
function tableintalAcceptedA(argument){
	$('#total-accepted-estimates-table-a').DataTable({
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
function csvfileAccepted() {
  var job_name = $('#serchform-accepted #job_name').val();
  var estimate_created_date_to = $('#serchform-accepted #estimate_created_date_to').val();
  var estimate_created_date_from = $('#serchform-accepted #estimate_created_date_from').val();
  var date_range_date_to = $('#serchform-accepted #date_range_date_to').val();
  var date_range_date_from = $('#serchform-accepted #date_range_date_from').val();
  var comparision_range_date_to = $('#serchform-accepted #comparision_range_date_to').val();
  var comparision_range_date_from = $('#serchform-accepted #comparision_range_date_from').val();
  $.ajax({
      type: 'POST',
      url: '<?php echo base_url(); ?>admin/reports/downloadServiceSummaryCsv/', ////CHANGE URL
	  data:'job_name='+job_name+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
      success: function (response) {
    //    alert(response);
      }
  });
}
/*DECLINED ESTIMATE FUNCTIONS*/ 
function resetFormDeclined(){
  $('#serchform-declined')[0].reset();
  searchFilterDeclined();
}
function resetFormDeclinedA(){
  $('#serchform-declined-a')[0].reset();
  searchFilterDeclinedA();
}
function searchFilterDeclined() {
    var job_name = $('#serchform-declined #job_name_declined').val();
    var date_range_date_to = $('#serchform-declined #date_range_date_to_declined').val();
    var date_range_date_from = $('#serchform-declined #date_range_date_from_declined').val();
    $('.loading_2').css("display", "block");
   $('#postListDeclined').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataDeclined/', ///// CHECK URL
        data:'job_name='+job_name+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from,
        
        success: function (html) {
            $(".loading_2").css("display", "none");
            $('#postListDeclined').html(html);
            tableintalDeclined(); ///CHECK FUNCTION
        }
    });
}
function searchFilterDeclinedA() {
    var job_name = $('#serchform-declined-a #job_name_declined_a').val();
    var comparision_range_date_to = $('#serchform-declined-a #comparision_range_date_to_declined_a').val();
    var comparision_range_date_from = $('#serchform-declined-a #comparision_range_date_from_declined_a').val();
    $('.loading_2_a').css("display", "block");
   $('#postListDeclinedA').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSummaryDataDeclinedA/', ///// CHECK URL
       data:'job_name='+job_name+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
        
        success: function (html) {
            $(".loading_2_a").css("display", "none");
            $('#postListDeclinedA').html(html);
            tableintalDeclinedA(); ///CHECK FUNCTION
        }
    });
}
function tableintalDeclined(argument){
	
	$('#total-declined-estimates-table').DataTable({
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
function tableintalDeclinedA(argument){
	
	$('#total-declined-estimates-table-a').DataTable({
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
function csvfileDeclined() {
  var job_name = $('#serchform-declined #job_name').val();
  var estimate_created_date_to = $('#serchform-declined #estimate_created_date_to').val();
  var estimate_created_date_from = $('#serchform-declined #estimate_created_date_from').val();
  var date_range_date_to = $('#serchform-declined #date_range_date_to').val();
  var date_range_date_from = $('#serchform-declined #date_range_date_from').val();
  var comparision_range_date_to = $('#serchform-declined #comparision_range_date_to').val();
  var comparision_range_date_from = $('#serchform-declined #comparision_range_date_from').val();
  $.ajax({
      type: 'POST',
      url: '<?php echo base_url(); ?>admin/reports/downloadServiceSummaryCsv/', ////CHANGE URL
	  data:'job_name='+job_name+'&estimate_created_date_to='+estimate_created_date_to+'&estimate_created_date_from='+estimate_created_date_from+'&date_range_date_to='+date_range_date_to+'&date_range_date_from='+date_range_date_from+'&comparision_range_date_to='+comparision_range_date_to+'&comparision_range_date_from='+comparision_range_date_from,
      success: function (response) {
    //    alert(response);
      }
  });
}
</script>