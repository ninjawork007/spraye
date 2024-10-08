<div  class="table-responsive table-spraye" id="total-accepted-estimates">
  <table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe;
    border-radius: 12px;" id="total-accepted-estimates-table">    
    <thead>  
      <tr>
        <th>Service1</th>
        <th>Estimates Accepted</th>
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
        if (!empty($accepted_results)) {
          // $total_accepted = 0;
          // $total_accepted_2 = 0;
          $closed_rate_total = 0;
          $closed_rate_amt = 0;
          $closed_rate_total_2 = 0;
          $closed_rate_amt_2 = 0;

          foreach ($accepted_results as $value) { 
      ?>

      <tr>
        <td ><?= $value['job_name'] ?></td>
        <td><?= $value['accepted_1'] ?></td>
        <td><?= (number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2)*100) ?>%</td>
        <td><?= (number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2) *100) ?>%</td>
        <td><?= $value['accepted_2'] ?></td>
        <td><?= (number_format((($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1))) ,2)*100) ?>%</td>
        <td> <?= (number_format((($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1))) ,2)*100) ?>%</td>
        <td><?= (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100) ?>%</td>
        <td><?= number_format(((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1)))-(($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1)))) ,2)*100 ?>%</td>
      </tr>
          <?php  
            // $total_accepted += $value['accepted_1'];
            // $total_accepted_2 += $value['accepted_2'];
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
        if (!empty($accepted_results)) {
          
      ?>
      <tr>
        <td><b>TOTALS</b>
        <span data-popup="tooltip-custom" data-toggle="tooltip" title="The total shown is the total number of estimates created and not total number of services." data-placement="right"> <i class=" icon-info22 tooltip-icon"></i>

											</span>
      </td>
        <td><b><?= $accepted_estimates_1 ?></b></td>
        <td><b><?= number_format($closed_rate_total/count($accepted_results)) ?>%</b></td>
        <td><b><?= number_format($closed_rate_amt/count($accepted_results)) ?>%</b></td>
        <td><b><?= $accepted_estimates_1 ?></b></td>
        <td><b><?= number_format($closed_rate_total_2/count($accepted_results)) ?>%</b></td>
        <td><b><?= number_format($closed_rate_amt_2/count($accepted_results)) ?>%</b></td>
        <td><b><?= number_format(($closed_rate_total/max(count($accepted_results),1))-($closed_rate_total_2/max(count($accepted_results),1))) ?>%</b></td>
        <td><b><?= number_format(($closed_rate_amt/max(count($accepted_results),1))-($closed_rate_amt_2/max(count($accepted_results),1))) ?>%</b></td>
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

          
