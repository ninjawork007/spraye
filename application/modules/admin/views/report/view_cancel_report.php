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
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="post-list" id="cancel-report-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
                <div class="table-responsive table-spraye">
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

    $('.loading').css("display", "block");
	
	$('#cancel-report-list').html('');
	
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxDataForCancelReport',
        data:'date_from='+date_from+'&date_to='+date_to+'&user='+user,
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