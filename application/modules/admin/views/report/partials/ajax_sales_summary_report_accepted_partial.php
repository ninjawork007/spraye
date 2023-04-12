<!-- accepted estimates -->
<div  class="table-responsive table-spraye">
  <table  class="table datatable-colvis-state " id="accepted_estimates_partial" >
    <thead>  
      <tr>
          <!-- <th><input type="checkbox" id="select_all" /></th> -->
          <th>Sales Rep/Source Partials</th>
          <th># of estimates created in date range</th>
          <th># of estimates created in comparison range</th>
          <th>Difference Close Rate %</th>
          <th>Difference Close Rate $</th>
          
      </tr>  
    </thead>
    <tbody id="accepted_estimates_tbody_partial">
    <?php 
          if (!empty($report_summary)) {
            // $total_open = 0;
            // $total_estimates = 0;
            $total_accepted = 0;
            // $accepted_total = 0;
            // $total_declined = 0;
            // $declined_total = 0;
            $closed_rate_total = 0;
            $closed_rate_amt = 0;

            foreach ($report_summary as $value) { 
        ?>

      <tr>
        <!-- <td></td> -->
        <td ><?= $value['rep_name'] ?></td>
        <td><?= $value['accepted'] ?></td>
        <td></td>
        <td><?= number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2) ?>%</td>
        <td>$ <?= number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2) ?></td>
      </tr>
      <?php  
            // $total_open += $value['total_estimates'];
            // $total_estimates += $value['total_estimates'];
            $total_accepted += $value['accepted'];
            // $accepted_total += $value['accepted_total'];
            // $total_declined += $value['declined'];
            // $declined_total += $value['declined_total'];
            $closed_rate_total += number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2);
            $closed_rate_amt += number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2);
            }
          ?>
          
        <tr>
          <td><b>TOTALS</b></td>
          <td><b><?= number_format($total_accepted) ?></b></td>
          <td><b><?= number_format($total_accepted) ?></b></td>
          <td><b><?= number_format($closed_rate_total,2) ?>%</b></td>
          <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>
          <!-- <td></td> -->
        </tr>     
        <?php
          } else { 
          ?> 

        <tr>
          <td colspan="5"> No record found </td>
        </tr>

        <?php }  ?>

    </tbody>
  </table>  
</div>  