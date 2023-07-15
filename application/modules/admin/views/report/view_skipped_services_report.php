<style type="text/css">
  .toolbar {
    float: left;
    padding-left: 5px;
}
.form-control[readonly] {
  background-color: #ededed;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<div class="content">
	<div class="panel panel-flat">
		<div class="panel-body">
			<b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
			<div class="panel panel-body" style="background-color:#ededed;" >
				<form id="serchform" action="<?= base_url('admin/reports/downloadSkippedReport') ?>" method="post">
					<div class="row">
                        <div class="col-md-3 multi-select-full">
                            <label>Users</label>
                            <select class="multiselect-select-all-filtering form-control" name="users_multi[]" id="users_multi" multiple="multiple">
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user->user_id ?>"> <?= $user->user_first_name ?> <?= $user->user_last_name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-3 multi-select-full">
                            <label>Service Area(s)</label>
                            <select class="multiselect-select-all-filtering form-control" name="service_areas_multi[]" id="service_areas_multi" multiple="multiple">
                                <?php foreach ($service_areas as $area): ?>
                                    <option value="<?= $area->property_area_cat_id ?>"> <?= $area->category_area_name ?> </option>
                                <?php endforeach ?>
                            </select>
                        </div>
						 <div class="col-md-3">
							<div class="form-group">
							  <label>Start Date</label>
							  <input type="date" id="date_from" name="date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
							</div>
						  </div>

						  <div class="col-md-3">
							<div class="form-group">
							  <label>End Date</label>
							  <input type="date" id="date_to" name="date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d H:i:s'); ?>">
							</div>
						  </div>
				  	</div>
                    <div class="row">
                        <div class="col-md-4 multi-select-full">
                            <label>Service Type(s)</label>
                            <select class="multiselect-select-all-filtering form-control" name="service_types_multi[]" id="service_types_multi" multiple="multiple">
                                <?php foreach ($service_types as $service_type): ?>
                                    <option value="<?= $service_type->service_type_id ?>"> <?= $service_type->service_type_name ?> </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-4 multi-select-full">
                            <label>Skip Reason(s)</label>
                            <select class="multiselect-select-all-filtering form-control" name="skip_reasons_multi[]" id="skip_reasons_multi" multiple="multiple">
                                <?php foreach ($skip_reasons as $skip_reason): ?>
                                    <option value="<?= $skip_reason->skip_id ?>"> <?= $skip_reason->skip_name ?> </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-4 multi-select-full">
                            <label>Service Name(s)</label>
                            <select class="multiselect-select-all-filtering form-control" name="service_name_multi[]" id="service_name_multi" multiple="multiple">
                                <?php foreach ($service_list as $service): ?>
                                    <option value="<?= $service['job_id'] ?>"> <?= $service['job_name'] ?> </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
					<div class="row pt-5">
						<div class="text-center">
							<button type="button" class="btn btn-success" onClick="searchFilterNew()" ><i class="icon-search4 position-left"></i> Search</button>
							<button type="button" class="btn btn-primary" onClick="resetform()" ><i class="icon-reset position-left"></i> Reset</button>
							<button type="button" class="btn btn-info" id="downloadCsv"><i class="icon-file-download position-left"></i> Download CSV</button>
						</div>
					</div>
				</form>
			</div>
            <div class="tabbable">
                <ul class="nav nav-tabs nav-tabs-solid nav-justified" id="tabs-selector">
                    <li class="liquick <?php echo $active_nav_link == '0' ? 'active' : ''  ?> "><a href="#highlighted-justified-tab0" data-toggle="tab">Summary</a></li>
                    <li class="lione <?php echo $active_nav_link == '1' ? 'active' : ''  ?> "><a href="#highlighted-justified-tab1" data-toggle="tab">Detail</a></li>
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
                                <div class="post-list" id="summary">
                                    <div class="table-responsive table-spraye">
                                        <table class="table datatable-colvis-state" id="summary_tbl">
                                            <thead>
                                            <tr>
                                                <th>Skip Reason</th>
                                                <th># Skipped Services</th>
                                                <th>Lost Revenue</th>
                                            </tr>
                                            </thead>
                                            <tbody id="new_estimates_tbody">
                                            <?php
                                            if (!empty($summary['summary'])) {
                                                $total_lost_revenue = 0;
                                                $total_services = 0;

                                                foreach ($summary['summary'] as $key => $value) {
                                                    ?>

                                                    <tr>
                                                        <td ><?= $key ?></td>
                                                        <td><?= $value['count'] ?></td>
                                                        <td><?= number_format($value['value'] ,2) ?></td>

                                                    </tr>
                                                    <?php
                                                    $total_lost_revenue += $value['value'];
                                                    $total_services += $value['count'];
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
                                                <td><b><?= isset($total_services)? $total_services: 0 ?></b></td>
                                                <td><b><?= (isset($total_lost_revenue)?number_format($total_lost_revenue,2):0) ?></b></td>

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
                                <div class="post-list" id="detailed">
                                    <div  class="table-responsive table-spraye">
                                        <table  class="table datatable-colvis-state " id="detailed_tbl" >
                                            <thead>
                                            <tr>
                                                <th>Service</th>
                                                <th>Customer</th>
                                                <th>Property Address</th>
                                                <th>Service Type</th>
                                                <th>Service Area</th>
                                                <th>Skip reason</th>
                                                <th>Date of Skip</th>
                                                <th>Person Responsible</th>
                                                <th>Lost revenue</th>
                                            </tr>
                                            </thead>
                                            <tbody id="accepted_estimates_tbody">
                                            <?php
                                            if (!empty($services)) {
                                                $total_lost_revenue = 0;
                                                $total_services = 0;

                                                foreach ($services as $value) {
                                                    ?>

                                                    <tr>
                                                        <td ><?= $value['service'] ?></td>
                                                        <td ><?= $value['customer'] ?></td>
                                                        <td ><?= $value['property_address'] ?></td>
                                                        <td ><?= $value['service_type'] ?></td>
                                                        <td ><?= $value['service_area'] ?></td>
                                                        <td ><?= $value['skip_reason'] ?></td>
                                                        <td ><?= $value['skipped_at'] ?></td>
                                                        <td><?= $value['responsible'] ?></td>
                                                        <td><?= number_format($value['lost_revenue'],2) ?></td>
                                                    </tr>
                                                    <?php

                                                    $total_lost_revenue += $value['lost_revenue'];
                                                    $total_services++;
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
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><b><?= (isset($total_lost_revenue)?number_format($total_lost_revenue,2):0) ?></b></td>

                                            </tr>
                                            </tfoot>
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
<div class="loading" style="display:none;">
	<center><img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/></center>
</div>
<script>
$(document).ready(function() {
    $( "#tabs" ).tabs();
    $("#downloadCsv").click(function (e) {
        // e.preventDefault();
        let id = $('.tab-pane.active:visible').attr('id');
        if (id === 'highlighted-justified-tab0')
        {
            $(".summary").click();
        } else {
            $(".detailed").click();
        }
    })
    tableInitialize();
});



function csvfile() {
    var customer_name = $('#customer_name').val();
    var technician_id = $('#technician_id').val();
    var estimate_created_date_to = $('#estimate_created_date_to').val();
    var job_completed_date_from = $('#job_completed_date_from').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/downloadBonusReportCsv/',
        data:'customer_name='+customer_name+'&technician_id='+technician_id+'&estimate_created_date_to='+estimate_created_date_to+'&job_completed_date_from='+job_completed_date_from,

        success: function (response) {
            //    alert(response);
        }
    });
}

function searchFilterNew() {
    var date_to = $('#date_to').val();
    var date_from = $('#date_from').val();
    var service_area = $('#service_areas_multi').val();
    var service_type = $('#service_types_multi').val();
    var job_name = $('#service_name_multi').val();
    var skip_reason = $('#skip_reasons_multi').val();
    var user_multi = $('#users_multi').val();
    var search = $('.dataTables_filter input[type=search]').val();

    if (search === 'undefined' || search === undefined)
        search = '';

    let tabActive = $('.tab-pane.active:visible').attr('id');

    $('.loading').css("display", "block");
    $('#summary').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSkippedData/', ///// CHECK URL
        data:'page=ajax_skipped_services_summary_report&users='+user_multi+'&search='+search+'&job_name='+job_name+'&date_range_date_to='+date_to+'&date_range_date_from='+date_from+'&service_area='+service_area+'&service_type='+service_type+'&skip_reason='+skip_reason,

        success: function (html) {
            $(".loading").css("display", "none");
            $('#summary').html(html);
            // tableInitialize(); ///CHECK FUNCTION
        }
    });

    $('.loading_2').css("display", "block");
    $('#detailed').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxServiceSkippedData/', ///// CHECK URL
        data:'page=ajax_skipped_services_detailed_report&users='+user_multi+'&search='+search+'&job_name='+job_name+'&date_range_date_to='+date_to+'&date_range_date_from='+date_from+'&service_area='+service_area+'&service_type='+service_type+'&skip_reason='+skip_reason,

        success: function (html) {
            $(".loading_2").css("display", "none");
            $('#detailed').html(html);
        }
    });

    tableInitialize(); ///CHECK FUNCTION
}


function tableInitialize(argument) {

    // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });


    // Basic initialization
    $('#summary_tbl').DataTable({
        "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
        "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
        buttons: [
            {
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn bg-blue'
            },
            {
                extend: 'csv',
                text: '<i class="icon-printer position-left"></i> Download CSV',
                className: 'btn bg-green summary',
                footer: true,
                filename: 'SkippedSummary.csv',
                exportOptions: {
                    modifier: {
                        search: 'none'
                    }
                }
            }
        ]
    });
    $('#detailed_tbl').DataTable({
        "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
        "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
        buttons: [
            {
                extend: 'print',
                text: '<i class="icon-printer position-left"></i> Print table',
                className: 'btn bg-blue'
            },
            {
                extend: 'csv',
                text: '<i class="icon-printer position-left"></i> Download CSV',
                className: 'btn bg-green detailed',
                footer: true,
                filename: 'SkippedDetails.csv',
                exportOptions: {
                    modifier: {
                        search: 'none'
                    }
                }
            }
        ]
    });

}


function resetform(){
	location.reload();
}
</script>