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
				<form id="serchform" action="<?= base_url('admin/reports/downloadCustomerGrowthReport') ?>" method="post">
					<div class="row">
                        <div class="col-md-3">
                            <div class="form-group multi-select-full">
                              <label>Programs</label>
                              <select id="assignProgram" name="assignProgram[]" multiple class="multiselect-select-all-filtering" placeholder="None selected">
                                    <?php foreach ($programlist as $value) : ?>
                                        <?php if(!strstr($value->program_name, '- Standalone')){?>
                                            <option value="<?= $value->program_id ?>"> <?= $value->program_name ?> </option>
                                        <?php } ?>                                        
                                        <?php endforeach ?>
                                </select>
                            </div>
                          </div>

                          <div class="col-md-3">
                            <div class="form-group multi-select-full">
                              <label>Services</label>
                              <select id="assignService" name="assignService[]" multiple class="multiselect-select-all-filtering" placeholder="None selected">
                                    <?php foreach ($servicelist as $value) : ?>
                                        <option value="<?= $value->job_id ?>"><?= $value->job_name?></option>
                                        <?php endforeach ?>
                                </select>
                            </div>
                          </div>
						 <div class="col-md-3">
							<div class="form-group">
							  <label>Start Date</label>
							  <input type="date" id="start_date" name="start_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
							</div>
						  </div>
						  <div class="col-md-3">
							<div class="form-group">
							  <label>End Date</label>
							  <input type="date" id="end_date" name="end_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d H:i:s'); ?>">
							</div>
						  </div>
				  	</div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group multi-select-full">
                              <label>Programs</label>
                              <select id="assignProgramCompare" name="assignProgramCompare[]" multiple class="multiselect-select-all-filtering" placeholder="None selected">
                                    <?php foreach ($programlist as $value) : ?>
                                        <?php if(!strstr($value->program_name, '- Standalone')){?>
                                            <option value="<?= $value->program_id ?>"> <?= $value->program_name ?> </option>
                                        <?php } ?>                                        
                                        <?php endforeach ?>
                                </select>
                            </div>
                          </div>

                          <div class="col-md-3">
                            <div class="form-group multi-select-full">
                              <label>Services</label>
                              <select id="assignServiceCompare" name="assignServiceCompare[]" multiple class="multiselect-select-all-filtering" placeholder="None selected">
                                    <?php foreach ($servicelist as $value) : ?>
                                        <option value="<?= $value->job_id ?>"><?= $value->job_name?></option>
                                        <?php endforeach ?>
                                </select>
                            </div>
                          </div>
						 <div class="col-md-3">
							<div class="form-group">
							  <label>Comparison Start Date</label>
							  <input type="date" id="comparison_start_date" name="comparison_start_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
							</div>
						  </div>
						  <div class="col-md-3">
							<div class="form-group">
							  <label>Comparison End Date</label>
							  <input type="date" id="comparison_end_date" name="comparison_end_date" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d H:i:s'); ?>">
							</div>
						  </div>
				  	</div>


                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                              <label>Residential or Commercial</label>
                              <select class="form-control" name="res_or_com" id="res_or_com">
                                    <option value="">None selected</option>
                                    <option value="Residential"> Residential </option>
                                    <option value="Commercial"> Commercial </option>
                                </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group multi-select-full">
                              <label>Service Area</label>
                              <select id="serviceArea" name="serviceArea[]" multiple class="multiselect-select-all-filtering" placeholder="None selected">
                                <?php foreach ($service_areas as $area): ?>
                                    <option value="<?= $area->property_area_cat_id ?>"> <?= $area->category_area_name ?> </option>
                                <?php endforeach ?>
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
    <div class="panel">
        <div class="panel-body" style="border-top:none;">
            <div class="post-list" id="customer-growth-report-list" <?php if(empty($report_details)){ ?> style="padding-top:0px;" <?php } ?> >
                <div class="table-responsive table-spraye">
                    <table class="table" style="border:1px solid #6eb1fd;">
                        <thead>
                            <tr>
                                <th>Date Range</th>
                                <th>Total Starting Properties</th>
                                <th>Total New Properties</th>
                                <th>Total Cancels</th>
                                <th>Cancel %</th>
                                <th># of Cancels/Total # of new sales</th>
                                <th>Total Ending Properties Growth Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($report_details)){?>
                            <tr>
                                <td><?= $report_details['date_range'] ?></td>
                                <td><?= $report_details['total_starting_properties'] ?></td>
                                <td><?= $report_details['total_new_properties'] ?></td>
                                <td><?= $report_details['total_cancels'] ?></td>
                                <td><?= $report_details['total_cancelled_percent'] ?>%</td>
                                <td><?= $report_details['total_cancels_vs_sales'] ?></td>
                                <td><?= $report_details['total_ending_property_growth'] ?>%</td>
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
                    <div id="chartWrapper">
                        <canvas id="customerGrowthChart" height="200" width="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="loading" style="display:none;">
	<center><img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/></center>
</div>

<script src="<?= base_url('assets/admin') ?>/assets/js/plugins/charts/chart_js/dist/chart.js"></script>
<script> 
var LABELS = <?php echo json_encode($labels); ?>;
var DATA_SET = <?php echo json_encode($chart_data); ?>;
var CANCEL_DATA_SET = <?php echo json_encode($cancel_chart_data); ?>;
$(document).ready(function() {
      tableInitialize();
      createChart(LABELS,DATA_SET,CANCEL_DATA_SET);
});
function createChart(LABELS,DATA_SET,CANCEL_DATA_SET){
    var sum_properties = 0;
    var new_properties = 0;
    var cancelled_properties = 0;
    var chartData = [];
    var newData = [];
    var cancelledData = [];
    for(let i = 0; i < LABELS.length; ++i){
        let month = LABELS[i];
        //get growth counts
        let monthlyGrowth = DATA_SET[month];
        newData.push(monthlyGrowth);
        //new_properties += monthlyGrowth;

        //get cancel counts
        let monthlyCancels = CANCEL_DATA_SET[month];
        //cancelled_properties += monthlyCancels;
        cancelledData.push(monthlyCancels);
        //get total counts
        sum_properties += DATA_SET[month];
        sum_properties -= monthlyCancels;
        chartData.push(sum_properties);
    }

    var ctx = document.getElementById('customerGrowthChart').getContext('2d');
    var config = {
        type: 'line',
        data: {
            labels: LABELS,
            datasets: [{
                label: 'Total Properties',
                type: 'line',
                data: chartData,
                backgroundColor: ['#2196f3'],
                borderColor: ['#2196f3'],
                borderWidth: 1
                },
                {
                label: 'New Properties',
                type: 'bar',
                data: newData,
                backgroundColor: ['#74dada'],
                borderColor: ['#74dada'],
                borderWidth: 1
                },
                {
                label: 'Cancelled Properties',
                type: 'bar',
                data: cancelledData,
                backgroundColor: ['#ff5252'],
                borderColor: ['#ff5252'],
                borderWidth: 1
                },
            ]
        },
        options: {
            layout:{
                //padding:50,
            },
            plugins:{
                title: {
                    text: 'Property Growth Analysis',
                    display:true
                }
            },
            scales: {
                x:{
                    display:true,
                    title:{
                        display:true,
                        text: 'Date Range',
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    };   
    var customerGrowthChart = new Chart(ctx,config);

    
}

function createComparisonChart(LABELS,DATA_SET,CANCEL_DATA_SET, C_LABELS,C_DATA_SET,C_CANCEL_DATA_SET){

    var sum_properties = 0;
    var new_properties = 0;
    var cancelled_properties = 0;
    var chartData = [];
    var newData = [];
    var cancelledData = [];
    var c_sum_properties = 0;
    var c_new_properties = 0;
    var c_cancelled_properties = 0;
    var c_chartData = [];
    var c_newData = [];
    var c_cancelledData = [];

    let COMBINED_LABELS = LABELS.concat(C_LABELS);

    const orderArr = ['Starting', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    COMBINED_LABELS = [...new Set([...LABELS, ...C_LABELS])];

    COMBINED_LABELS = COMBINED_LABELS.sort((a, b) => {
        const [monthA, yearA] = a.split(' '),
            [monthB, yearB] = b.split(' ');
        if (yearA === yearB)
            return orderArr.indexOf(monthA) - orderArr.indexOf(monthB);
        return +yearA - +yearB;
    });

    console.log(COMBINED_LABELS);

    for(let i = 0; i < COMBINED_LABELS.length; ++i){
        let month = COMBINED_LABELS[i];
		let monthlyGrowth = 0;
		let c_monthlyGrowth = 0;
		let monthlyCancels = 0;
		let c_monthlyCancels = 0;
        //get growth counts
		if(typeof DATA_SET[month] !== 'undefined'){
			monthlyGrowth = DATA_SET[month];
		}
		if(typeof C_DATA_SET[month] !== 'undefined'){
			c_monthlyGrowth = C_DATA_SET[month];
		}
		newData.push(monthlyGrowth);
        c_newData.push(c_monthlyGrowth);
		sum_properties += monthlyGrowth;
        c_sum_properties += c_monthlyGrowth;
		
        //get cancel counts
		if(typeof CANCEL_DATA_SET[month] !== 'undefined'){
			monthlyCancels = CANCEL_DATA_SET[month];
		}
		if(typeof C_CANCEL_DATA_SET[month] !== 'undefined'){
			c_monthlyCancels = C_CANCEL_DATA_SET[month];
		}
        cancelledData.push(monthlyCancels);
        c_cancelledData.push(c_monthlyCancels);
        sum_properties -= monthlyCancels;
        c_sum_properties -= c_monthlyCancels;
		
		//console.log(month+" = "+sum_properties);
		//console.log(month+" compare = "+c_sum_properties);
        //push data to chart array
        chartData.push(sum_properties);
		c_chartData.push(c_sum_properties);
    }
    

    var ctx = document.getElementById('comparisonGrowthChart').getContext('2d');
    var config = {
        type: 'line',
        data: {
            labels: COMBINED_LABELS,
            datasets: [
                {
                label: 'Total Properties',
                type: 'line',
                data: chartData,
                backgroundColor: ['#2196f3'],
                borderColor: ['#2196f3'],
                borderWidth: 1
                },
                {
                label: 'New Properties',
                type: 'bar',
                data: newData,
                backgroundColor: ['#74dada'],
                borderColor: ['#74dada'],
                borderWidth: 1
                },
                {
                label: 'Cancelled Properties',
                type: 'bar',
                data: cancelledData,
                backgroundColor: ['#ff5252'],
                borderColor: ['#ff5252'],
                borderWidth: 1
                },
                {
                label: 'Total Compared Properties',
                type: 'line',
                data: c_chartData,
                backgroundColor: ['#ec407a'],
                borderColor: ['#ec407a'],
                borderWidth: 1
                },
                {
                label: 'New Compared Properties',
                type: 'bar',
                data: c_newData,
                backgroundColor: ['#7e57c2'],
                borderColor: ['#7e57c2'],
                borderWidth: 1
                },
                {
                label: 'Cancelled Compared Properties',
                type: 'bar',
                data: c_cancelledData,
                backgroundColor: ['#ffa726'],
                borderColor: ['#ffa726'],
                borderWidth: 1
                },
            ]
        },
        options: {
            layout:{
                //padding:50,
            },
            plugins:{
                title: {
                    text: 'Property Growth Comparison Analysis',
                    display:true
                }
            },
            scales: {
                x:{
                    display:true,
                    title:{
                        display:true,
                        text: 'Comparison Date Range',
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    };   
    var comparisonGrowthChart = new Chart(ctx,config);

    
}

function tableInitialize(argument) {
// Setting Datatable Defaults
      $.extend( $.fn.dataTable.defaults, {
          autoWidth: false,
         // dom: '<"datatable-header"B><"datatable-scroll-wrap"t><"datatable-footer">',
          dom: '<"datatable-header"><"datatable-scroll-wrap"t><"datatable-footer">',
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
	var start_date = $('#start_date').val();
	var end_date = $('#end_date').val();
    var comparison_start_date = $('#comparison_start_date').val();
	var comparison_end_date = $('#comparison_end_date').val();
	var user = $('#user').val();
    var rescom = $("#res_or_com").val();
    var serviceArea = $("#serviceArea").val();
    var assignProgram = $("#assignProgram").val();
    var assignProgramCompare = $("#assignProgramCompare").val();
    var assignService = $("#assignService").val();
    var assignServiceCompare = $("#assignServiceCompare").val();

    $('.loading').css("display", "block");
	
	$('#customer-growth-report-list').html('');
	
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxDataForCustomerGrowthReport',
        data:'start_date='+start_date+'&end_date='+end_date+'&comparison_start_date='+comparison_start_date+'&comparison_end_date='+comparison_end_date+'&user='+user+"&rescom="+rescom+"&serviceArea="+serviceArea+"&assignProgram="+assignProgram+"&assignService="+assignService+"&assignProgramCompare="+assignProgramCompare+"&assignServiceCompare="+assignServiceCompare,
        success: function (html) {
            $(".loading").css("display", "none");
            $('#customer-growth-report-list').html(html);
            tableInitialize();
        }
    });
}
function resetform(){
	location.reload();
}
</script>