<div  class="table-responsive table-spraye" id="total-declined-estimates">
  <table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe;
    border-radius: 12px;" id="total-declined-estimates-table">    
    <thead>  
      <tr>
        <th>Service</th>
        <th>Estimates Declined</th>
        <th>Estimate Close Rate</th>
        <th>Revenue Close Rate</th>
        <th>Comparison Dates Estimates</th>
        <th>Comparison Range Close Rate</th>
        <th>Comparison Range Revenue Close Rate</th>
        <th>Change in Close Rate</th>
        <th>Change in Revenue Close Rate</th>
      </tr>  
    </thead>
    <tbody>
      <?php 
        if (!empty($declined_results)) {
          // $total_declined = 0;
          // $total_declined_2 = 0;
          $closed_rate_total = 0;
          $closed_rate_amt = 0;
          $closed_rate_total_2 = 0;
          $closed_rate_amt_2 = 0;

          foreach ($declined_results as $value) { 
      ?>

      <tr>
        <td ><?= $value['job_name'] ?></td>
        <td><?= $value['declined_1'] ?></td>
        <td><?= (number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2)*100) ?>%</td>
        <td><?= (number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2)*100) ?>%</td>
        <td><?= $value['declined_2'] ?></td>
        <td><?= (number_format((($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1))) ,2)*100) ?>%</td>
        <td><?= (number_format((($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1))) ,2)*100) ?>%</td>
        <td><?= (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100) ?>%</td>
        <td><?= number_format(((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1)))-(($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1)))) ,2)*100 ?>%</td>
      </tr>
          <?php
            // $total_declined += $value['declined_1'];
            // $total_declined_2 += $value['declined_2'];
            $closed_rate_total += (number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2)*100);
            $closed_rate_amt += (number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2)*100);
            $closed_rate_total_2 += (number_format((($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1))) ,2)*100);
            $closed_rate_amt_2 += (number_format((($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1))) ,2)*100);
            }
          
        } else { 
        ?> 

      <tr>
        <td> No record found </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

        <?php 
          }  
        ?>
    </tbody>
    <tfoot>
      <?php 
        if (!empty($declined_results)) {
         
      ?>
      <tr>
        <td><b>TOTALS</b>
        <span

											data-popup="tooltip-custom"
											title="The total shown is the total number of estimates created and not total number of services."
											data-placement="right"> <i class=" icon-info22 tooltip-icon"></i>

											</span>
      </td>
        <td><b><?= $declined_estimates_1 ?></b></td>
        <td><b><?= number_format($closed_rate_total/count($declined_results)) ?>%</b></td>
        <td><b><?= number_format($closed_rate_amt/count($declined_results)) ?>%</b></td>
        <td><b><?= $declined_estimates_2 ?></b></td>
        <td><b><?= number_format($closed_rate_total_2/count($declined_results)) ?>%</b></td>
        <td><b><?= number_format($closed_rate_amt_2/count($declined_results)) ?>%</b></td>
        <td><b><?= number_format(($closed_rate_total/max(count($declined_results),1))-($closed_rate_total_2/max(count($declined_results),1))) ?>%</b></td>
        <td><b><?= number_format(($closed_rate_amt/max(count($declined_results),1))-($closed_rate_amt_2/max(count($declined_results),1))) ?>%</b></td>
      </tr>
      <?php 
          }  
        ?>
    </tfoot>
  </table>
</div>
<script>
    $('[data-popup=tooltip-custom]').tooltip({
        template: '<div class="tooltip"><div class="bg-teal"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div></div>'
    });
</script>

          
