  <div  class="table-responsive table-spraye">
           <table  class="table datatable-button-print-basic" style="border: 1px solid #6eb0fe;
    border-radius: 12px;">    
                  <thead>  
                      <tr>
                          
                          
                          <th>Dates Collected</th>
                          <th>Sales Tax Area</th>
                          <th>Total Sales Tax Collected</th>
						  <th>Gross Revenue</th>
						  <th>Total Sales </th>
                         
                      </tr>  
                  </thead>
                  <tbody>

                  <?php if (!empty($new_report_details)) { foreach ($new_report_details as $value) {?>

                    

                      <tr>
                          <td><?= date('m/d/Y',strtotime($job_completed_date_from)). ' - '. date('m/d/Y',strtotime($job_completed_date_to)) ?></td>
                          <td><?= $value['tax_name'].' ('.floatval($value['tax_value']).'%) '  ?></td>
                          <td><?= number_format($value['total_tax'],2) ?></td>
						  <td><?= number_format(($value['gross_revenue']),2) ?></td>
						  <td><?= number_format(($value['total_sales']),2) ?></td>
                      </tr>

                  <?php  } } else { ?> 
 
                    <tr>
                        <td id="timeRange"></td>
                        <td></td>
                        <td class="text-center" > No record found </td>
                        <td></td>
                        <td></td>
                      
                    </tr>

                  <?php }  ?>

                  </tbody>
              </table>
           </div>

<script>
	var newDate = new Date();
	var event = '';
    if ($('#job_completed_date_from').val() == undefined || $('#job_completed_date_from').val() == '' || $('#job_completed_date_from').val() == null){
        date = '<span>All Time</span>';
    } else {
        event = new Date($('#job_completed_date_from').val()+' 00:00:00');
        var options = { dateStyle: 'short' };
	    var date = event.toLocaleString('en', options);
    }
	//alert(date);
	
	var event2 = '';
    var date2 = '';
    if ($('#job_completed_date_to').val() == undefined || $('#job_completed_date_to').val() == '' || $('#job_completed_date_to').val() == null){
        date2 = newDate.toLocaleString('en',{ dateStyle: 'short' });
    } else {
        event2 = new Date($('#job_completed_date_to').val()+' 00:00:00');
        var options2 = { dateStyle: 'short' };
	    date2 = event2.toLocaleString('en', options2);
    }
	
	
	

    if(date == '<span>All Time</span>'){
        $('#timeRange').html('<span>All Time</span>');
    } else {
        $('#timeRange').html(date + " - " + date2  );
    }
	

</script>
