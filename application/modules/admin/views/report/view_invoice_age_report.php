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
				<form id="serchform" action="<?= base_url('admin/reports/downloadInvoiceAgeCsv') ?>" method="post">
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
						<div class="col-md-3">
							<div class="form-group">
								<label>Service Area</label>
								<select class="bootstrap-select form-control" name="service_area" id="service_area" data-live-search="true">
									<option value="">Select Any Service Area</option>
									<?php if ($service_areas) {
										foreach ($service_areas as $area) { ?>
											<option value=<?= $area->property_area_cat_id ?>> <?= $area->category_area_name ?> </option>
									<?php } } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Sales Tax Area</label>
								<select class="bootstrap-select form-control" name="tax_name" id="tax_name" data-live-search="true">
									<option value="">Select Any Sales Tax Area</option>
									<?php if ($tax_details) {
										foreach ($tax_details as $tax_detail) { ?>
											<option value="<?= $tax_detail->tax_name ?>"> <?= $tax_detail->tax_name ?> </option>
									<?php } } ?>
								</select>
							</div>
						</div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Property Type</label>
                                <select class="bootstrap-select form-control" name="property_type" id="property_type" data-live-search="true">
                                    <option value="">Select Any Property Type</option>
                                            <option value="Residential">Residential</option>
                                            <option value="Commercial">Commercial</option>
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
					<th>0-30 Days</th>
					<th>31-60 Days</th>
					<th>61-90 Days</th>
					<th>90+ Days</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($report_details)){ 
					$total_current = 0;
					$total_30 = 0;
					$total_60 = 0;
					$total_90 = 0;
					$total_all = 0;
				?>
					<?php foreach($report_details as $report_detail) { ?>
					<tr>
						<td><a href="<?=base_url("admin/editCustomer/").$report_detail['customer_id'] ?>" class="button-next"><?= $report_detail['first_name'] . " " . $report_detail['last_name']?></a></td>
						<td>$<?= number_format($report_detail['current_total'],2) ?></td>
						<td>$<?= number_format($report_detail['30_total'],2) ?></td>
						<td>$<?= number_format($report_detail['60_total'],2) ?></td>
						<td>$<?= number_format($report_detail['90_total'],2) ?></td>
						<td>$<?= number_format($report_detail['customer_total_due'],2) ?></td>
					</tr>
					<?php 
						$total_current += $report_detail['current_total'];
						$total_30 += $report_detail['30_total'];
						$total_60 += $report_detail['60_total'];
						$total_90 += $report_detail['90_total'];
						$total_all += $report_detail['customer_total_due'];
					} ?> 
					<tr>
						<td><b>TOTALS</b></td>
						<td><b>$<?= number_format($total_current,2) ?></b></td>
						<td><b>$<?= number_format($total_30,2) ?></b></td>
						<td><b>$<?= number_format($total_60,2) ?></b></td>
						<td><b>$<?= number_format($total_90,2) ?></b></td>
						<td><b>$<?= number_format($total_all,2) ?></b></td>
					</tr>
					<tr>
						<td></td>
						<td><?php if(isset($current_invoices)){ ?><a href="<?=base_url("admin/Invoices/printInvoice/").$current_invoices; ?>" target="_blank" class="button-next">View Invoices</a><?php }?></td>
						<td><?php if(isset($aged30_invoices)){ ?><a href="<?=base_url("admin/Invoices/printInvoice/").$aged30_invoices ?>" target="_blank" class="button-next">View Invoices</a><?php }?></td>
						<td><?php if(isset($aged60_invoices)){ ?><a href="<?=base_url("admin/Invoices/printInvoice/").$aged60_invoices ?>" target="_blank" class="button-next">View Invoices</a><?php }?></td>
						<td><?php if(isset($aged90_invoices)){ ?><a href="<?=base_url("admin/Invoices/printInvoice/").$aged90_invoices ?>" target="_blank" class="button-next">View Invoices</a><?php }?></td>
						<td><a href="<?=base_url("admin/Invoices?aging=1")?>" target="_blank" class="button-next">View Invoices</a></td>
					</tr>
				
				<?php }else{ ?>
				<tr>
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
		  initComplete: function(){
     
           $("div.datatable-header")
              .prepend('<div class="btn-group"> <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Interval <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-interval" onclick="filterInterval(15)"><a href="#">15 Days</a></li><li class="filter-interval" onclick="filterInterval(30)" ><a href="#">30 Days</a></li></ul></div>');           
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
        url: '<?php echo base_url(); ?>admin/reports/ajaxDataForInvoiceAgeReport',
        data:'customer='+customer+'&service_area='+service_area+'&tax_name='+tax_name+'&interval='+interval+'&property_type='+property_type,
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