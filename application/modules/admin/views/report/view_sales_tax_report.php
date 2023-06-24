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
           <form id="serchform" action="<?= base_url('admin/reports/downloadTaxCsv') ?>" method="post">            
              <div class="row">

                  <div class="col-md-4">
                      <div class="form-group">
                         <label>Sales Tax Area</label>
                         <select name="tax_name" class="form-control" id="tax_name" >
                          <option value="">Select Any Sales Tax Area</option>
                          <?php if ($tax_details) {
                             foreach ($tax_details as $key => $value) { ?>   
                              <option value="<?= $value->tax_name ?>"> <?= $value->tax_name ?> </option>
                          <?php } } ?>
                         </select>
                      </div>
                  </div>
 
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Start Date</label>
                      <input type="date" id="job_completed_date_from" name="job_completed_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label>End Date</label>
                      <input type="date" id="job_completed_date_to" name="job_completed_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                    </div>
                  </div>
              </div>
           
            <div class="text-center">
                <button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
                <button type="button" class="btn btn-primary" onClick="resetform()" ><i class="icon-reset position-left"></i> Reset</button>
                <button type="submit" class="btn btn-info"><i class="icon-file-download position-left"></i> CSV Download</button>
              
            </div>
            
          </form>
             
           </div>




 <div class="loading" style="display: none;">
    <center>
          <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
    </center>
   
 </div>

        <div class="post-list" id="sales-tax-list"  <?php if(empty($new_report_details))  {  ?>  style="padding-top:20px" <?php } ?> >   

               
        <div  class="table-responsive table-spraye">
           <table  class="table datatable-button-print-basic">    
                  <thead>  
                      <tr>
                          <!--<th>Invoice ID</th>
						  
                          <th>property_id / program_id</th>
						   <th>Job Completed Date</th>
						   <th>Payment Created Date</th>
						   <th>Status</th>
						   <th>isComplete?</th>-->
                          <th>Dates Collected</th>
                          <th>Sales Tax Area</th>
                          <th>Total Sales Tax Collected</th>  
						  <th>Gross Revenue</th>
						  <th>Total Sales </th>
                         
                      </tr>  
                  </thead>
                  <tbody>

                  <?php if (!empty($new_report_details)) { foreach ($new_report_details as $value) { ?>

                      <tr>
                          <td><?= "Month to Date"//date('m-d-Y',strtotime($value->real_date)) ?></td>
                          <td><?= $value['tax_name'].' ('.floatval($value['tax_value']).'%) '  ?></td>
                          <td><?= number_format($value['total_tax'],2) ?></td>
						  <td><?= number_format(($value['gross_revenue']),2) ?></td>
						  <td><?= number_format(($value['total_sales']),2) ?></td>
                      </tr>

                  <?php  } } else { ?>
 
                    <tr>
                        <td class="text-center" > No record found </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                  <?php }  ?>


                  </tbody>
              </table>
           </div>
         </div> 


        </div>
        
        
    </div>
</div>


<script>




   $(document).ready(function() {
      tableInitialize();

   })


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
      $('.datatable-button-print-basic').DataTable({
          "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
          "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
          buttons: [
              {
                  extend: 'print',
                  text: '<i class="icon-printer position-left"></i> Print table',
                  className: 'btn bg-blue'
              }
          ]
      });

}


function resetform(){

  $('#serchform')[0].reset();
  searchFilter();
}




function searchFilter() {

    var tax_name = $('#tax_name').val();
    var firstDate = new Date('2019-01-01');
    var newDate = new Date();
    var job_completed_date_from = new Date(firstDate.getTime() - (firstDate.getTimezoneOffset() * 60000 )).toISOString().split("T")[0];
    var job_completed_date_to = new Date(newDate.getTime() - (newDate.getTimezoneOffset() * 60000 )).toISOString().split("T")[0];
    
    if($('#job_completed_date_from').val() !== undefined && $('#job_completed_date_from').val() !== '' && $('#job_completed_date_from').val() !== null){
    var job_completed_date_from = $('#job_completed_date_from').val();
    }
    if($('#job_completed_date_to').val() !== undefined && $('#job_completed_date_to').val() !== '' && $('#job_completed_date_to').val() !== null){
    var job_completed_date_to = $('#job_completed_date_to').val();
    }
    
    //console.log(job_completed_date_from);
    //console.log(job_completed_date_to);
    $('.loading').css("display", "block");
   $('#sales-tax-list').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxDataForSalesTaxReport',
        data:'tax_name='+tax_name+'&job_completed_date_to='+job_completed_date_to+'&job_completed_date_from='+job_completed_date_from,
        
        success: function (html) {
            // console.log(html);
            $(".loading").css("display", "none");
            $('#sales-tax-list').html(html);
            tableInitialize();
        }
    });
}

</script>
