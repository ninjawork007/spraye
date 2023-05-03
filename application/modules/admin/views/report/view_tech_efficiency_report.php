<style type="text/css">
.toolbar {
  float: left;
  padding-left: 5px;
}

.form-control[readonly] {
  background-color: #ededed;
}

td:nth-child(2) {
  white-space: nowrap;
}
</style>

<div class="content">
    <div class="panel panel-flat">
       <!--  <div class="panel-heading">
            <h5 class="panel-title">Users list</h5>
           
        </div> -->
         
   <!--      <div class="panel panel-flat">
             <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                    
                        </div>
                   </h5>
              </div>
        </div> -->
        <div class="panel-body">
             <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
        
           <div class="panel panel-body" style="background-color:#ededed;" >
            <form id="serchform" action="<?= base_url('admin/reports/downloadEfficiencyReportCsv') ?>" method="post">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group multi-select-full">
                         <label>User Name</label>

                         <?php
                          $TechIds = array();
                          if(isset($SavedFilter["id"])){
                            $TechIds = explode(",", $SavedFilter["techniciean_ids"]);
                          }
                          ?>

                          <select id="technician_id" name="technician_id[]" multiple class="multiselect-select-all-filtering" placeholder="Select Technician">
                           <?php 
                              if(!empty($tecnician_details)) 
                              {
                                foreach ($tecnician_details as $value) 
                                {
                                ?>
                                  <option <?php if(in_array($value->user_id, $TechIds)) { echo 'selected'; }?> value="<?php echo $value->user_id ?>"><?php echo $value->user_first_name.' '.$value->user_last_name?></option>
                                <?php
                                }
                              }
                           ?>                            
                          </select>
                      </div>
                  </div>

                  <?php
                  $FromDate = '';
                  if(isset($SavedFilter["id"])){
                    $FromDate = $SavedFilter["start_date"];
                  }
                  ?>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Start Date</label>
                      <input type="date" id="job_completed_date_from" name="job_completed_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?php echo $FromDate ?>">
                    </div>
                  </div>

                  <?php
                  $ToDate = date('Y-m-d');
                  if(isset($SavedFilter["id"])){
                    $ToDate = $SavedFilter["end_date"];
                  }
                  ?>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label>End Date</label>
                      <input type="date" id="job_completed_date_to" name="job_completed_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD" value="<?= $ToDate ?>">
                    </div>
                  </div>
              </div>
           
            <div class="text-center">
                <button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
                <button type="button" class="btn btn-primary" onClick="resetTable()" ><i class="icon-reset position-left"></i> Clear Report</button>
                <button type="submit" class="btn btn-info" id="export-btn"><i class="icon-file-download position-left"></i> CSV Download</button>
                <input type="hidden" name="csvData" id="csvData" value="[]">

                <button type="button" class="btn btn-success" onClick="saveSearchFilter()" ><i class="icon-search4 position-left"></i> Save Search</button>

            </div>
            
          </form>
             
           </div>




 <div class="loading" style="display: none;">
    <center>
          <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
    </center>
   
 </div>

        <div class="post-list" id="postList">   

           <div  class="table-responsive table-spraye" style="margin-top:1em;">
             <table  class="table datatable-colvis-state" id="report_table">    
                  <thead>  
                      <tr>
                        <th>Technician Name</th>
                        <th>Range</th>
                        <th># of Days Worked</th>
                        <th># of Services Completed</th>
                        <th>Sq Ft Completed</th>
                        <th>Revenue Produced</th>
                        <th># of Services Completed/Day (averaged)</th>
                        <th>Sq. Ft. Completed/Day (averaged)</th>
                        <th>Revenue Produced/Day (averaged)</th>
                        <th>Total Time on Property</th>
                        <th>Revenue per Hour</th>
                      </tr>  
                  </thead>
                  <tbody>

                     

                  </tbody>
                  <tfoot align="right">
                    <tr>
                      <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                    </tr>
                  </tfoot>                  
              </table>
           </div>
         </div> 


        </div>
        
        
    </div>
</div>


<script>

$('.loadingTable').css("display", "block");
/*
$.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/initialLoadTechEfficiencyReport',
        success: function(result) {
            console.log(result);
            if(result) {
                //console.log(result);
                let tableData = JSON.parse(result); 
            } else {
                //alert('no result');
                let tableData = []; 
            }
        
    
    //console.log( tableData );
    
    
    $('.loadingTable').css("display", "none");
        },
    error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
    }
});
*/
function stringStripAndConvert(string) {
  return parseFloat(string.replace(/\$|,/g, ''));
}

function resetTable(){
    $('#report_table').DataTable().clear().draw();
  $('#export-btn').prop("disabled", true);
}

function searchFilter() {
  $('.loading').css("display", "block");
  $('#report_table').DataTable().clear().draw();
  var technician_id = $('#technician_id').val();
  // var technician_name = $('#technician_id option:selected').text();
  var job_completed_date_to = $('#job_completed_date_to').val();
  var job_completed_date_from = $('#job_completed_date_from').val();
  // $('#postList').html('');
  $.ajax({
    type: 'POST',
    url: '<?php echo base_url(); ?>admin/reports/ajaxForTechEfficiencyReport/',
    data:'technician_id='+technician_id+'&job_completed_date_to='+job_completed_date_to+'&job_completed_date_from='+job_completed_date_from,

    success: function (resp) {
      // $(".loading").css("display", "none");
    //   console.log(resp);
      if(!resp.hasOwnProperty('Error')) {
        let result = JSON.parse(resp);
        if(Array.isArray(result)) {
          result.forEach((tech) => {
            $('#report_table').DataTable().row.add(tech).draw();
          });
        } else {
          let technician_name = $('#technician_id option:selected').text();
        //   console.log(result);
          result.tech_name = technician_name;
          $('#report_table').DataTable().row.add(result).draw();
        }
        $('#export-btn').prop("disabled", false);
      }
      $('.loading').css("display", "none");
    },
    fail: function() {
      //console.log('Query Failed!');
      $('.loading').css("display", "none");
    },
    always: function() {
      //console.log('Query Finished');
      $('.loading').css("display", "none");
    }
  });
}

function saveSearchFilter(){
    var technician_id = $('#technician_id').val();
    var job_completed_date_to = $('#job_completed_date_to').val();
    var job_completed_date_from = $('#job_completed_date_from').val();

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/saveTechnicianFilter',
        data:'techniciean_ids='+technician_id+'&start_date='+job_completed_date_from+'&end_date='+job_completed_date_to,

        success: function (resp) {
            swal('Save','Filter Saved Successfully ','success')
        },
  });
}

$(document).ready(function() {
  // $('#export-btn').on('click', collectCsvData);
  var data_already_grabbed = '<?php echo json_encode($initial_data); ?>';
  const resultsTable = $('#report_table').DataTable({
        data: JSON.parse(data_already_grabbed),
        columns: [
        { data:  'tech_name'},
        { data:  'date_range'},
        { data:  't_days_worked'},
        { data:  't_services'},
        { 
            data:  't_sqft',
            render: function(data, type) {
            var number = $.fn.dataTable.render.number( ','). display(data);
            return number;
            }
        },
        { 
            data:  't_revenue',
            render: function(data, type) {
            var number = $.fn.dataTable.render.number( ',', '.', 2, '$'). display(data);
            return number;
            }
        },
        { data:  'avg_services'},
        { 
            data:  'avg_sqft',
            render: function(data, type) {
            var number = $.fn.dataTable.render.number( ','). display(data);
            return number;
            }
        },
        { 
            data:  'avg_revenue',
            render: function(data, type) {
            var number = $.fn.dataTable.render.number( ',', '.', 2, '$'). display(data);
            return number;
            }
        },
        { data:  't_servce_time'},
        { 
            data:  'avg_revenue_hr',
            render: function(data, type, row) {
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            //console.log( row );
            //console.log( row.t_servce_time );
            let h = intVal( row.t_servce_time.split(':')[0] );


            // console.log(h);
            let m = intVal( row.t_servce_time.split(':')[1] ) / 60;
            //console.log( m );
            let t = h + m;
            let rph = '';
            if(t < 1){
                rph = intVal( row.t_revenue );
            } else {
                t = Math.round(t * 100) / 100;
                rph = ( isFinite( intVal( row.t_revenue ) / t )) ? intVal( row.t_revenue ) / t : row.t_revenue;
            }
            // console.log(row);
            // console.log(row.t_servce_time);
            // console.log(row.t_revenue);
            // var number = $.fn.dataTable.render.number( ',', '.', 2, '$'). display( rph );
            return '$' + rph.toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }
        ],
        "buttons": [],
        "paging":   false,
        "ordering": true,
        "info":     false,
        "searching": false,
        "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // converting to interger to find total
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        var toHoursMinsFormat = function(inputMins) {
            var minutes = inputMins % 60;
            var hours = (inputMins - minutes) / 60;
            return (hours < 10 ? "0" : "") + hours.toString() + ':' + (minutes < 10 ? "0" : "") + minutes.toString();
        }      

        // computing column Total of the complete result 
        // # worked days
        var col2 = api
            .column( 2 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // # services complete
        var col3 = api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // sqft completed
        var col4 = api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
                return (intVal(a) + intVal(b)).toLocaleString("en-US");
            }, 0 );
        // revenue produced
        var col5 = api
            .column( 5 )
            .data()
            .reduce( function (a, b) {
                return '$' + ( intVal(a) + intVal(b) ).toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }, 0 );
        // average services completed per day
        // var col6 = Math.round( col3 / col2 );
        var col6 = Math.round((( col3 / col2 ) + Number.EPSILON ) * 100 ) / 100;
        // var col6 = api
        //   .column( 6 )
        //   .data()
        //   .reduce( function (a, b) {
        //       return intVal(a) + intVal(b);
        //   }, 0 );
        //   col6 = (isNaN( Math.round(((col6 / api.rows().count()) + Number.EPSILON) * 100) / 100 )) ? 0 : Math.round(((col6 / api.rows().count()) + Number.EPSILON) * 100) / 100;
        // average sqft completed per day 
        var col7 = ( isNaN( Math.round( intVal( col4 ) / intVal( col2 )))) ? 0 : Math.round( intVal( col4 ) / intVal( col2 )).toLocaleString("en-US");
        // var col7 = api
        //   .column( 7 )
        //   .data()
        //   .reduce( function (a, b) {
        //       return intVal(a) + intVal(b);
        //   }, 0 );
        //   col7 = (isNaN( Math.round(((col7 / api.rows().count()) + Number.EPSILON) * 100) / 100 )) ?  0  : ( Math.round(((col7 / api.rows().count()) + Number.EPSILON) * 100) / 100 ).toLocaleString("en-US");
        // average revenue per day
        var col8 = ( isNaN( Math.round((( intVal( col5 ) / intVal( col2 )) + Number.EPSILON ) * 100 ) / 100 )) ? '$' + 0 : '$' + ( Math.round((( intVal( col5 ) / intVal( col2 )) + Number.EPSILON ) * 100 ) / 100 ).toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 });
        // var col8 = api
        //   .column( 8 )
        //   .data()
        //   .reduce( function (a, b) {
        //       return intVal(a) + intVal(b);
        //   }, 0 );
        //   col8 = (isNaN( Math.round(((col8 / api.rows().count()) + Number.EPSILON) * 100) / 100 )) ?  '$' + 0 : '$' + ( Math.round(((col8 / api.rows().count()) + Number.EPSILON) * 100) / 100 ).toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 });
        // total time on property  
        var col9 = api
            .column( 9 )
            .data()
            .reduce( function (a, b) {
            return a + intVal(b.split(':')[0]) * 60 + intVal(b.split(':')[1]);
            }, 0 );
            col9 = toHoursMinsFormat(col9);
        // revenue per hour
        let h = intVal( col9.split(':')[0] );
        // console.log(h);
        let m = intVal( col9.split(':')[1] ) / 60;
        // console.log(m);
        let t = h + m;
        t = Math.round(t * 100) / 100;
        // console.log( t );

        var col10 = ( isFinite( intVal( col5 ) / t )) ? '$' + ( intVal( col5 ) / t ).toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '$' + col5;
        // var col10 = ( isNaN( Math.round((( intVal( col5 ) / ( intVal( col9.split(':')[0] ) * 60 + intVal( col9.split(':')[1] )) / 60 ) + Number.EPSILON ) * 100 ) / 100 )) ? '$' + 0 : '$' + ( Math.round((( intVal( col5 ) / ( intVal( col9.split(':')[0] ) * 60 + intVal( col9.split(':')[1] )) / 60 ) + Number.EPSILON ) * 100 ) / 100 ).toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 });
        // var col10 = api
        //   .column( 10 )
        //   .data()
        //   .reduce( function (a, b) {
        //       return intVal(a) + intVal(b);
        //   }, 0 );
        //   col10 = (isNaN( Math.round(((col10 / api.rows().count()) + Number.EPSILON) * 100) / 100 )) ? '$' + 0 : '$' + ( Math.round(((col10 / api.rows().count()) + Number.EPSILON) * 100) / 100 ).toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Update footer by showing the total with the reference of the column index 
        $( api.column( 0 ).footer() ).html('Totals');
        $( api.column( 2 ).footer() ).html(col2);
        $( api.column( 3 ).footer() ).html(col3);
        $( api.column( 4 ).footer() ).html(col4);
        $( api.column( 5 ).footer() ).html(col5);
        $( api.column( 6 ).footer() ).html(col6);
        $( api.column( 7 ).footer() ).html(col7);
        $( api.column( 8 ).footer() ).html(col8);
        $( api.column( 9 ).footer() ).html(col9);
        $( api.column( 10 ).footer() ).html(col10);
        },
    });
  $('#serchform').submit(function() {
    let dataContainer = [];
    let header = Array.from($($(resultsTable.table().header()).get(0).children).get(0).children).map(th => $(th).text());
    dataContainer.push(header);
    resultsTable.rows().every(function(i) {
        let row = resultsTable.row(i);
        let rowEl = row.node();
        let cells = Array.from(rowEl.children)
        let valArray = cells.map(cell => $(cell).text());
        dataContainer.push(valArray);
    });
    let footer = Array.from($($(resultsTable.table().footer()).get(0).children).get(0).children).map(th => $(th).text());
    dataContainer.push(footer);
    $('#csvData').val(JSON.stringify(dataContainer));
  });


  searchFilter();
});
</script>
