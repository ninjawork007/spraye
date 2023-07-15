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
            <form id="serchform" action="<?= base_url('admin/reports/downloadWorkReportCsv') ?>" method="post">
              <div class="row">

                     <div class="col-md-6">

                        <div class="multi-select-full col-md-10" id="service_ids_filter_parent" style="padding-left: 4px; margin-top: 10px; margin-bottom: 10px;">
                           <label for="service_ids_filter">Service(s)</label>
                           <select class="multiselect-select-all-filtering form-control" name="services_multi[]" id="services_multi" multiple="multiple">
                              <?php foreach ($service_details as $value): ?>

                                 <option value="<?= $value->job_id ?>"> <?= $value->job_name ?> </option>

                              <?php endforeach ?>
                           </select>
                           <span id="serviceError" style="color:red;display:none">Please Select a Service</span>
                        </div>

                     </div>

                     <div class="col-md-6">

                        <div class="multi-select-full col-md-10" id="service_ids_filter_parent" style="padding-left: 4px; margin-top: 10px; margin-bottom: 10px;">
                           <label for="service_ids_filter">Program(s)</label>
                           <select class="multiselect-select-all-filtering form-control" name="programs_multi[]" id="programs_multi" multiple="multiple">
                              <?php foreach ($program_details as $value): ?>

                                 <option value="<?= $value->program_id ?>"> <?= $value->program_name ?> </option>

                              <?php endforeach ?>
                           </select>
                        </div>

                     </div>                     
                
              </div>

              <hr>

               <div class="row">
                  <div class="text-center">
                     <button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
                     <button type="button" class="btn btn-primary" onClick="window.location.reload()" ><i class="icon-reset position-left"></i> Clear Report</button>
                     <button type="submit" class="btn btn-info" id="export-btn"><i class="icon-file-download position-left"></i> CSV Download</button>
                     <input type="hidden" name="csvData" id="csvData" value="[]">
                  </div>
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
                        <th>Service Area</th>
                        <th>Total Services Assigned</th>
                        <th>Total Services Scheduled</th>
                        <th>Total Services Completed</th>
                        <th>Total Service Outstanding</th>
                        <th>% Services Completed</th>
                        <th>Total Sqft</th>
                        <th>Total Sqft Scheduled</th>
                        <th>Total Sqft Completed</th>
                        <th>Total Sqft Outstanding</th>
                        <th>% Sqft Completed</th>
                        <th>Total Revenue Scheduled</th>
                        <th>Total Revenue Produced</th>
                        <th>Total Revenue Outstanding</th>
                        <th>% Revenue Produced</th>
                     </tr>
                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot align="right">
                    <tr>
                      <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                    </tr>
                  </tfoot>                  
              </table>
           </div>
         </div> 


        </div>
        
        
    </div>
</div>

<script>
  let tableData = [];
  // $('#report_table').DataTable();
  
  const resultsTable = $('#report_table').DataTable({
    data: tableData,
    columns: [
      { data:  'service_area'},
      { data:  't_serv_assgn'},
      { data:  't_serv_scheduled'},
      { data:  't_serv_comp'},
      { data:  't_serv_out'},
      { 
        data:  'perc_comp',
        render: function(data, type) {
          var number = $.fn.dataTable.render.number(',', '.', 0, '', '%').display(data);
          return number;
        }
      },
      { 
        data:  'total_sqft',
        render: function(data, type) {
          var number = $.fn.dataTable.render.number( ','). display(data);
          return number;
        }
      },
      {
        data:  'total_sqft_scheduled',
        render: function(data, type) {
            var number = $.fn.dataTable.render.number( ','). display(data);
            return number;
        }
      },
      { 
        data:  'total_sqft_comp',
        render: function(data, type) {
          var number = $.fn.dataTable.render.number( ','). display(data);
          return number;
        }
      },
      { 
        data:  'total_sqft_out',
        render: function(data, type) {
          var number = $.fn.dataTable.render.number( ','). display(data);
          return number;
        }
      },
      { 
        data:  'perc_sqft_comp',
        render: function(data, type) {
          var number = $.fn.dataTable.render.number(',', '.', 0, '', '%').display(data);
          return number;
        }      
      },
        {
            data:  'total_rev_scheduled',
            render: function(data, type) {
                var number = $.fn.dataTable.render.number( ',', '.', 2, '$'). display(data);
                return number;
            }
        },
      { 
        data:  'total_rev_prod',
        render: function(data, type) {
          var number = $.fn.dataTable.render.number( ',', '.', 2, '$'). display(data);
          return number;
        }
      },
      {
        data:  'total_rev_out',
        render: function(data, type) {
          var number = $.fn.dataTable.render.number( ',', '.', 2, '$'). display(data);
          return number;
        }
      },
      { 
        data:  'perc_rev_prod',
        render: function(data, type) {
          var number = $.fn.dataTable.render.number(',', '.', 0, '', '%').display(data);
          return number;
        }      
      }
    ],
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
      // # Total Service Assgn
      var col1 = api
        .column( 1 )
        .data()
        .reduce( function (a, b) {
            return intVal(a) + intVal(b);
        }, 0 );
      var col2 = api
        .column( 2 )
        .data()
        .reduce( function (a, b) {
            return intVal(a) + intVal(b);
        }, 0 );
      // # Total Services Compl
      var col3 = api
        .column( 3 )
        .data()
        .reduce( function (a, b) {
            return intVal(a) + intVal(b);
        }, 0 );
      // # Total Services Out
      var col4 = api
        .column( 4 )
        .data()
        .reduce( function (a, b) {
            return intVal(a) + intVal(b);
        }, 0 );
      // % Services Completed
      var col5 = ( isNaN(( intVal( col3 ) / intVal( col1 )) * 100 )) ? 0 + '%' : Math.round(( intVal( col3 ) / intVal( col1 )) * 100 ) + '%';
      // var col4 = api
      //   .column( 4 )
      //   .data()
      //   .reduce( function (a, b) {
      //       return intVal(a) + intVal(b);
      //   }, 0 );
      //   col4 = Math.round(((col4 / api.rows().count()) + Number.EPSILON) * 100) / 100 + '%';
      // # Total SqFt
      var col6 = api
        .column( 6 )
        .data()
        .reduce( function (a, b) {
            return (intVal(a) + intVal(b)).toLocaleString("en-US");
        }, 0 );
      // # Total SqFt Completed 
      var col7 = api
        .column( 7 )
        .data()
        .reduce( function (a, b) {
            return (intVal(a) + intVal(b)).toLocaleString("en-US");
        }, 0 );
      // Total SqFt Out
      var col8 = api
        .column( 8 )
        .data()
        .reduce( function (a, b) {
            return (intVal(a) + intVal(b)).toLocaleString("en-US");
        }, 0 );
      var col9 = api
        .column( 9 )
        .data()
        .reduce( function (a, b) {
            return (intVal(a) + intVal(b)).toLocaleString("en-US");
        }, 0 );
      // % SqFt Completed
      var col10 = (  intVal( col6 ) == 0) ? 0 + '%' : Math.round(( intVal( col7 ) / intVal( col6 )) * 100 ) + '%';
      // # Total Rev Schedule
      var col11 = api
        .column( 11 )
        .data()
        .reduce( function (a, b) {
            return '$' + (intVal(a) + intVal(b)).toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }, 0 );
      // # Total Rev Prod
      var col12 = api
        .column( 12 )
        .data()
        .reduce( function (a, b) {
            return '$' + (intVal(a) + intVal(b)).toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }, 0 );
      // Total Rev Out
      var col13 = api
        .column( 13 )
        .data()
        .reduce( function (a, b) {
            return '$' + (intVal(a) + intVal(b)).toLocaleString("en-US",{ minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }, 0 );        
      // % Rev Prod
      var col14 = ( isNaN( intVal( col12 ) / ( intVal( col12 ) + intVal( col13 )) * 100 )) ? 0 + '%' : Math.round(( intVal( col12 ) / ( intVal( col12 ) + intVal( col13 ))) * 100 ) + '%';
      // var col11 = api
      //   .column( 11 )
      //   .data()
      //   .reduce( function (a, b) {
      //       return intVal(a) + intVal(b);
      //   }, 0 );
      //   col11 = Math.round(((col11 / api.rows().count()) + Number.EPSILON) * 100) / 100 + '%';

      $( api.column( 0 ).footer() ).html('Totals');
      $( api.column( 1 ).footer() ).html(col1);
      $( api.column( 2 ).footer() ).html(col2);
      $( api.column( 3 ).footer() ).html(col3);
      $( api.column( 4 ).footer() ).html(col4);
      $( api.column( 5 ).footer() ).html(col5);
      $( api.column( 6 ).footer() ).html(col6);
      $( api.column( 7 ).footer() ).html(col7);
      $( api.column( 8 ).footer() ).html(col8);
      $( api.column( 9 ).footer() ).html(col9);
      $( api.column( 10 ).footer() ).html(col10);
      $( api.column( 11 ).footer() ).html(col11);
      $( api.column( 12 ).footer() ).html(col12);
      $( api.column( 13 ).footer() ).html(col13);
      $( api.column( 14 ).footer() ).html(col14);
    },
    
  });


function resetTable(){
  resultsTable.clear().draw();
  $('#export-btn').prop("disabled", false);
}


function searchFilter() {
  $('.loading').css("display", "block");
  var qServices = $('#services_multi').val();
  var qPrograms = $('#programs_multi').val();
  if(qServices == null) {
    $('#serviceError').show();
  } else {
    $('#serviceError').hide();
    $.ajax({
      type: 'POST',
      url: '<?php echo base_url(); ?>admin/reports/ajaxForAvailableWorkReport/',
      data:'services='+qServices+'&programs='+qPrograms,

      success: function (resp) {
        // $(".loading").css("display", "none");
        // console.log(resp);
        if(!resp.hasOwnProperty('Error')) {
          // let technician_name = $('#technician_id option:selected').text();
          let result = JSON.parse(resp);
          // console.log(result);
          // result.tech_name = technician_name;
          resultsTable.clear();
          resultsTable.rows.add(result).draw();
          // $('#export-btn').prop("disabled", false);
        }
        $('.loading').css("display", "none");
      },
      fail: function() {
        console.log('Query Failed!');
        $('.loading').css("display", "none");
      },
      always: function() {
        console.log('Query Finished');
        $('.loading').css("display", "none");
      }
    });
  }
}

$(document).ready(function() {
  // $('#export-btn').on('click', collectCsvData);
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
});



</script>
