<style type="text/css">
  .toolbar {
    float: left;
    padding-left: 5px;
}
.form-control[readonly] {
  background-color: #ededed;
}
#invoice-age-list .dropdown-menu {
    min-width: 80px !important;
}
</style>
<div class="content">
	<div class="panel panel-flat">
		<div class="panel-body">
			<b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
			<div class="panel panel-body" style="background-color:#ededed;" >
				<form id="serchform" action="<?= base_url('admin/reports/downloadCancelServiceReportCsv') ?>" method="post">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Customers</label>
								<select class="bootstrap-select form-control" name="customer"  id="customer" data-live-search="true">
									<option value="" >Select a Customer</option>
									<?php if ($customers) {
										foreach ($customers as $customer) { ?>
											<option value=<?= $customer->customer_id ?>> <?= $customer->first_name . " " . $customer->last_name ?> </option>
									<?php } } ?>
								</select>
							</div>
						</div>
						<input type="hidden" name="interval" id="interval" value="30">
					</div>
					<div class="text-center">
						<button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
						<button type="button" class="btn btn-primary" onClick="resetform()" ><i class="icon-reset position-left"></i> Reset</button>
						<button type="submit" class="btn btn-info"><i class="icon-file-download position-left"></i> Download CSV</button>
					</div>
				</form>
			</div>
		

		<div class="row mt-5">
			<div class="col-lg-4">
				<div class="service-bols">
		        <h3 class="ser-head">Total Canceled Revenue (New)</h3>
		        <p class=" ser-num text-success">$<?php echo $TotlaNewRevenueLost ?></p>
		      </div>
			</div>

			<div class="col-lg-4">
				<div class="service-bols">
		        <h3 class="ser-head">Total Canceled Revenue (Existing)</h3>
		        <p class=" ser-num text-success">$<?php echo $TotalExistingRevenueLost ?></p>
		      </div>
			</div>

			<div class="col-lg-4">
				<div class="service-bols">
		        <h3 class="ser-head">Total Canceled Revenue (All)</h3>
		        <p class=" ser-num text-danger">$<?php echo $TotalRevenueLost ?></p>
		      </div>
			</div>
		</div>

		</div>
	</div>
</div>



<div class="loading" style="display:none;">
	<center><img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/></center>
</div>
<div class="post-list" id="invoice-age-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
	<div class="table-responsive table-spraye">
		<table class="table datatable-button-print-basic">
			<thead>
				<tr>
					<th>Customer</th>
					<th>Service Name</th>
					<th>Cost</th>
					<th>Reason</th>
					<th>Cancel Date</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($Services as $Invs){
					$CustomerData = $this->db->select('*')->from("customers")->where(array("customer_id" => $Invs->customer_id))->get()->row();
				?>
				<tr>
					<td><?php echo $CustomerData->first_name. " " . $CustomerData->last_name ?></td>
					<td><?php echo $Invs->job_name ?></td>
					<td>$<?php echo $Invs->job_cost ?></td>
					<td><?php echo $Invs->cancel_reason ?></td>
					<td><?php echo date("d F, Y", strtotime($Invs->created_at)) ?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<script>
$(document).ready(function() {
      tableInitialize();
});
function tableInitialize(argument) {
// Setting Datatable Defaults
      $.extend( $.fn.dataTable.defaults, {
          autoWidth: false,
          dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
          language: {
              search: '<span>Filter:</span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
      });
// Basic Initialization
      $('.datatable-button-print-basic').DataTable({
          "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
          "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
          buttons: [
              {
                  extend: 'print',
                  text: '<i class="icon-printer position-left"></i> Print table',
                  className: 'btn bg-blue'
              },
			  
          ]
      });
}	
function filterInterval(interval){
	$('input#interval').val(interval);
	searchFilter();
}
function searchFilter() {
	var customer = $('#customer').val();
	var service_area = $('#service_area').val();
    var tax_name = $('#tax_name').val();
    var property_type = $('#property_type').val();
	var interval = $('#interval').val();

    $('.loading').css("display", "block");
	
	$('#invoice-age-list').html('');
	
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxDataForCancelService',
        data:'customer='+customer,
        success: function (html) {
            $(".loading").css("display", "none");
            $('#invoice-age-list').html(html);
            tableInitialize();
        }
    });
}
function resetform(){
	location.reload();
}
</script>