<div  class="table-responsive table-spraye">
  <table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;">    
  <thead>  
    <tr>
      <th>Technician Name</th>
      <th>Total $ of Primary Services Completed</th>
      <th>Total Primary Services Bonuses</th>
      <th>Total $ of Secondary Services Completed</th>	
      <th>Total Secondary Bonuses</th>
      <th>Total $ Production</th>
      <th>Total Bonuses</th>
      <th>Source New Sales</th>
    </tr>  
  </thead>
  <tbody>
      <?php 
      if (!empty($bonus_summary)) { 
          $total_primarycompleted = 0;
          $total_primary_bonus = 0;
          $total_secondarycompleted = 0;
          $total_secondary_bonus = 0;
          $totalcompleted = 0;
          $total_bonus = 0;
          $total_new_sales = 0;
        foreach ($bonus_summary as $value) { ?>
      <tr>   
        <td style="text-transform: capitalize;"><?= $value['tech_name']?></td>
        <td>$ <?= number_format(($value['primary_service_total']) ,2)?></td>
        <td>$ <?= number_format(($value['primary_bonus']) ,2)?></td>
        <td>$ <?= number_format(($value['secondary_service_total']) ,2)?></td>
        <td>$ <?= number_format(($value['secondary_bonus']) ,2)?></td>
        <td>$ <?= number_format(($value['primary_service_total']+$value['secondary_service_total']) ,2)?></td>
        <td>$ <?= number_format(($value['primary_bonus']+$value['secondary_bonus']) ,2)?></td>
        <td><?= $value['sold_by'] ?></td>
      </tr>
      <?php  
          $total_primarycompleted += $value['primary_service_total'];
          $total_primary_bonus += $value['primary_bonus'];
          $total_secondarycompleted += $value['secondary_service_total'];
          $total_secondary_bonus += $value['secondary_bonus'];
          $totalcompleted += $value['primary_service_total']+$value['secondary_service_total'];
          $total_bonus += $value['primary_bonus']+$value['secondary_bonus'];
          $total_new_sales += $value['sold_by'];
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
      </tr>
      <?php 
        }
      ?>
    </tbody>
    <tfoot>
    <?php 
      if (!empty($bonus_summary)) { 
         ?>
      <tr>
        <td><b>TOTALS</b></td>
        <td><b>$<?= number_format($total_primarycompleted,2) ?></b></td>
        <td><b>$<?= number_format($total_primary_bonus,2) ?></b></td>
        <td><b>$<?= number_format($total_secondarycompleted,2) ?></b></td>
        <td><b>$<?= number_format($total_secondary_bonus,2) ?></b></td>
        <td><b>$<?= number_format($totalcompleted,2) ?></b></td>
        <td><b>$<?= number_format($total_bonus,2) ?></b></td>
        <td><b><?= number_format($total_new_sales) ?></b></td>
      </tr> 
      <?php
    } 
        
      ?>
    </tfoot>
  </table>
</div>

