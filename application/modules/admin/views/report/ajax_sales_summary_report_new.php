<!-- new estimates -->
<div  class="table-responsive table-spraye">
  <table  class="table datatable-colvis-state " id="new_estimates">
    <thead>  
      <tr>
          <th>Sales Rep/Source</th>
          <th>Estimates Created</th>
          <th>Estimate Close Rate</th>
          <th>Revenue Close Rate</th>
          <th>Comparison Dates Estimates</th>
          <th>Comparison Range Close Rate</th>
          <th>Comparison Range Revenue Close Rate</th>
          <th>Change in Close Rate</th>
          <th>Change in Revenue Close Rate</th>
          
      </tr>  
    </thead>
    <tbody id="new_estimates_tbody">
    <?php 
          if (!empty($report_results)) {

            $total_open = 0;
            $total_open_2 = 0;
            $closed_rate_total = 0;
            $closed_rate_amt = 0;
            $closed_rate_total_2 = 0;
            $closed_rate_amt_2 = 0;

            foreach ($report_results as $value) {
        ?>

      <tr>
        <td ><?= $value['rep_name'] ?></td>
        <td><?= $value['total_estimates_1'] ?></td>
        <td><?= (number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2)*100) ?>%</td>
        <td><?= (number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2)*100) ?>% </td>
       
        <!-- <td><?= (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100) ?>%</td> -->
        <td><?= $value['total_estimates_2'] ?></td>
        <td><?= (number_format((($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1))) ,2)*100) ?>%</td>
        <td><?= (number_format((($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1))) ,2)*100) ?>%</td>
        <td><?= (number_format(((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1)))-(($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1)))) ,2)*100) ?>%</td>
        <td><?= number_format(((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1)))-(($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1)))) ,2)*100 ?>%</td>
        
      </tr>
      <?php  
            $total_open += $value['total_estimates_1'];
            $total_open_2 += $value['total_estimates_2'];
            $closed_rate_total += (number_format((($value['accepted_1']/max(($value['accepted_1']+$value['declined_1']),1))) ,2)*100);
            $closed_rate_amt += (number_format((($value['accepted_total_1']/max(($value['accepted_total_1']+$value['declined_total_1']),1))) ,2)*100);
            $closed_rate_total_2 += (number_format((($value['accepted_2']/max(($value['accepted_2']+$value['declined_2']),1))) ,2)*100);
            $closed_rate_amt_2 += (number_format((($value['accepted_total_2']/max(($value['accepted_total_2']+$value['declined_total_2']),1))) ,2)*100);
            // }
          }
          // die(print_r( $closed_rate_amt));
        
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

        <?php }  ?>

    </tbody>
    <tfoot>
      <?php 
          if (!empty($report_results)) {

        ?>
      <tr>
          <td><b>TOTALS</b></td>
          <td><b><?= number_format($total_open) ?></b></td>
          <td><b><?= number_format($closed_rate_total/count($report_results)) ?>%</b></td>
          <td><b><?= number_format($closed_rate_amt/count($report_results)) ?>%</b></td>
          <td><b><?= number_format($total_open_2) ?></b></td>
          <td><b><?= number_format($closed_rate_total_2/max(count($report_results),1)) ?>%</b></td>
          
          <td><b><?= number_format($closed_rate_amt_2/count($report_results)) ?>%</b></td>
          <td><b><?= number_format(($closed_rate_total/max(count($report_results),1))-($closed_rate_total_2/max(count($report_results),1))) ?>%</b></td>
          
          <td><b><?= number_format(($closed_rate_amt/max(count($report_results),1))-($closed_rate_amt_2/max(count($report_results),1))) ?>%</b></td>
        </tr>   
        <?php }  ?>  
    </tfoot>
  </table>  
</div>
