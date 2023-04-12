<div  class="table-responsive table-spraye">
  <table  class="table datatable-colvis-state " id="overall" style="border: 1px solid #6eb0fe;
    border-radius: 12px;">    
    <thead>  
      <tr>
        <th>Sales Rep/Source</th>
        <th>Total New Estimates</th>
        <th>Total # of Accepted Estimates</th>
        <th>Total $ of Accepted Estimates</th>
        <th>Total # of Declined Estimates</th>
        <th>Total $ of Declined Estimates</th>
        <th>Close Rate - Accepted/(Declined + Accepted)</th>
        <th>$ Close Rate Accepted $/ (Declined $ + Accepted $)</th>
      </tr>  
    </thead>
    <tbody>
      <?php 
        if (!empty($report_summary)) {
          $total_open = 0;
          $total_estimates = 0;
          $total_accepted = 0;
          $accepted_total = 0;
          $total_declined = 0;
          $declined_total = 0;
          $closed_rate_total = 0;
          $closed_rate_amt = 0;

          foreach ($report_summary as $value) { 
      ?>

      <tr>
        <td ><?= $value['rep_name'] ?></td>
        <td><?= $value['total_estimates'] ?></td>
        <td><?= $value['accepted'] ?></td>
        <td>$ <?= number_format(($value['accepted_total']) ,2) ?></td>
        <td><?= $value['declined'] ?></td>
        <td>$ <?= number_format(($value['declined_total']) ,2) ?></td>
        <td><?= number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2) ?>%</td>
        <td>$ <?= number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2) ?></td>
      </tr>
          <?php  
            $total_open += $value['total_estimates'];
            $total_estimates += $value['total_estimates'];
            $total_accepted += $value['accepted'];
            $accepted_total += $value['accepted_total'];
            $total_declined += $value['declined'];
            $declined_total += $value['declined_total'];
            $closed_rate_total += number_format((($value['accepted']/($value['accepted']+$value['declined']))) ,2);
            $closed_rate_amt += number_format((($value['accepted_total']/($value['accepted_total']+$value['declined_total']))) ,2);
            }
          ?>
      <tr>
        <td><b>TOTALS</b></td>
        <td><b><?= number_format($total_estimates) ?></b></td>
        <td><b><?= number_format($total_accepted) ?></b></td>
        <td><b>$<?= number_format($accepted_total,2) ?></b></td>
        <td><b><?= number_format($total_declined) ?></b></td>
        <td><b>$<?= number_format($declined_total,2) ?></b></td>
        <td><b><?= number_format($closed_rate_total,2) ?>%</b></td>
        <td><b>$<?= number_format($closed_rate_amt,2) ?></b></td>
      </tr>
        <?php
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
      </tr>

        <?php 
          }  
        ?>
    </tbody>
  </table>
</div>
