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
				<form id="serchform" action="<?= base_url('admin/reports/downloadCancelReport') ?>" method="post">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Sales Rep/Technician</label>
								<select class="bootstrap-select form-control" name="user" id="user" data-live-search="true">
									<option value="all" selected>All</option>
									<?php if(!empty($user_details)) {
										foreach ($user_details as $user) { ?>
											<option value=<?= $user->id ?>> <?= $user->user_first_name . " " . $user->user_last_name ?> </option>
									<?php } } ?>
								</select>
							</div>
						</div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Service Area</label>
                                <select class="bootstrap-select form-control" name="service_area" id="service_area" data-live-search="true">
                                    <option value="" selected>All</option>
                                    <?php if(!empty($ServiceArea)) {
                                        foreach ($ServiceArea as $area) { ?>
                                            <option value=<?= $area->property_area_cat_id ?>> <?= $area->category_area_name ?> </option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    New/Existing
                                </label>
                                <span data-popup="tooltip-custom" title="" data-placement="top" data-original-title="'New' means that the customer was added within the past 12 months. 'Existing' means that the customer was added prior to the past 12 months.">  <i class=" icon-info22 tooltip-icon"></i> </span>
                                <select class="bootstrap-select form-control" name="newExisting" id="newExisting">
                                    <option value="" selected>All</option>
                                    <option value="1">New</option>
                                    <option value="0">Existing</option>
                                </select>
                            </div>
                        </div>

						 <div class="col-md-4">
							<div class="form-group">
							  <label>Start Date</label>
							  <input type="date" id="date_from" name="date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
							</div>
						  </div>

						  <div class="col-md-4">
							<div class="form-group">
							  <label>End Date</label>
							  <input type="date" id="date_to" name="date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d H:i:s'); ?>">
							</div>
						  </div>

                          <div class="col-md-4">
                            <div class="form-group">
                              <label>Cancel Reason</label>
                              <select id="reason" name="reason" class="form-control" placeholder="Enter reason for cancel">
                                <option value="">All</option>
                                <?php
                                foreach($cancel_reasons as $reason){
                                ?>
                                    <option><?php echo $reason->cancel_name ?></option>
                                <?php
                                }
                                ?>
                                <option>Other</option>
                              </select>
                            </div>
                          </div>
				  	</div>
					<div class="row">
						<div class="text-center">
							<button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
							<button type="button" class="btn btn-primary" onClick="resetform()" ><i class="icon-reset position-left"></i> Reset</button>
							<button type="submit" class="btn btn-info"><i class="icon-file-download position-left"></i> Download CSV</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

    <div id="cancel-report-list">
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="post-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
                <div class="table-responsive table-spraye" style="min-height: 0px">
                    <table class="table" style="border:1px solid #6eb1fd;">
                        <thead>
                            <tr>
                                <th>Total Cancelled Properties</th>
                                <th>Total Cancelled Services</th>
                                <th>Total Cancelled Revenue</th>
                                <th>New Customer Cancelled Properties</th>
                                <th>New Customer Cancelled Services</th>
                                <th>New Customer Revenue Lost</th>
                                <th>Total Sales</th>
                                <th>Total Sales Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($report_details)){?>
                            <tr>
                                <td><?= $report_details['total_cancelled_properties'] ?></td>
                                <td><?= $report_details['total_cancelled_services'] ?></td>
                                <td>$<?= $report_details['total_cancelled_revenue'] ?></td>
                                <td><?= $report_details['lost_total_new_cancelled_props'] ?></td>
                                <td><?= $report_details['lost_total_new_cancelled_servs'] ?></td>
                                <td>$<?= $report_details['total_new_revenue_lost'] ?></td>
                                <td><?= $report_details['total_sales'] ?></td>
                                <td>$<?= $report_details['total_sales_revenue'] ?></td>
                            </tr>
                            <?php }else{ ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center">No records found</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="post-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
                <div class="table-responsive table-spraye" style="min-height: 0px">
                    <table class="table" style="border:1px solid #6eb1fd;">
                        <thead>
                            <tr>
                                <th>Canceled Date</th>
                                <th>Canceled By</th>
                                <th>Customer Name</th>
                                <th>Customer Start Date</th>
                                <th>Property name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Lost Revenue</th>
                                <th>Sales Rep.</th>
                                <th>New Existing Customer</th>
                                <th>Service/Program</th>
                                <th>Cancel Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $TotalRevnueLost = 0;
                            foreach($AllCancelledProperty as $CanclPrprty){
                                $TotalRevnueLost += $CanclPrprty->job_cost;
                            ?>
                            <tr>
                                <td><?= date("d F, Y", strtotime($CanclPrprty->property_cancelled))?></td>
                                <td><?= $CanclPrprty->user_first_name ?> <?= $CanclPrprty->user_last_name ?></td>
                                <td><a href="<?php echo base_url() ?>/admin/editCustomer/<?= $CanclPrprty->customer_id ?>" target="_blank"><?= $CanclPrprty->first_name ?> <?= $CanclPrprty->last_name ?></a></td>
                                <td><?= date("d F, Y", strtotime($CanclPrprty->start_date))?></td>
                                <td><?= $CanclPrprty->property_title ?></td>
                                <td><?= $CanclPrprty->property_address ?></td>
                                <td><?= $CanclPrprty->email ?></td>
                                <td><?= $CanclPrprty->work_phone ?></td>
                                <td>$<?= $CanclPrprty->job_cost ?></td>
                                <td><?= $CanclPrprty->SalesRep ?></td>
                                <td>
                                    <?php
                                    if($CanclPrprty->start_date >= date("Y-m-d 00:00:00", strtotime("-1 year"))){
                                        echo "New";
                                    }else{
                                        echo "Existing";
                                    }
                                    ?>
                                </td>
                                <td><?= $CanclPrprty->service_cancelled ?></td>
                                <td><?= $CanclPrprty->cancel_reason ?></td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" align="right"><b>Total</b></td>
                                <td>$ <?php echo $TotalRevnueLost ?></td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="loading" style="display:none;">
	<center><img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/></center>
</div>
<script>
$(document).ready(function() {
      tableInitialize();
});
function tableInitialize(argument) {
// Setting Datatable Defaults
      $.extend( $.fn.dataTable.defaults, {
          autoWidth: false,
          dom: '<"datatable-header"><"datatable-scroll-wrap"t><"datatable-footer">',
          //dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
          language: {
              search: '<span>Filter:</span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
      });
// Basic Initialization
      $('.datatable-button-print-basic').DataTable({
          buttons: [
              {
                  extend: 'print',
                  text: '<i class="icon-printer position-left"></i> Print table',
                  className: 'btn bg-blue'
              },
			  
          ]
      });
}	
function searchFilter() {
	var date_from = $('#date_from').val();
	var date_to = $('#date_to').val();
	var user = $('#user').val();
    var serviceArea = $("#service_area").val();
    var newExisting = $("#newExisting").val();
    var reason = $("#reason").val();

    $('.loading').css("display", "block");
	
	$('#cancel-report-list').html('');
	
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxDataForCancelReport',
        data:'date_from='+date_from+'&date_to='+date_to+'&user='+user+"&serviceArea="+serviceArea+"&newExisting="+newExisting+"&reason="+reason,
        success: function (html) {
            $(".loading").css("display", "none");
            $('#cancel-report-list').html(html);
            tableInitialize();
        }
    });
}
function resetform(){
	location.reload();
}
</script>