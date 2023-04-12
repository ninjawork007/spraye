<div  class="table-responsive table-spraye">
  <table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe; border-radius: 12px;">    
    <thead>  
      <tr>
        <th>Sales Rep Name</th>
        <th>Total $ of Produced Primary Services</th>
        <th>Total Primary Commissions (Produced Primary Services $ * Primary Service Commission %</th>
        <th>Total $ of Produced Secondary Services</th>	
        <th>Total Secondary Commission (Produced Secondary $ * Secondary Commission %</th>
        <th>Total Sales Produced</th>
        <th>Total Commissions</th>
        <th>New Sales Source</th>
      </tr>  
    </thead>
    <tbody>
      <?php 
      if (!empty($commission_summary)) { 
        $total_primary_produced = 0;
        $total_primary_commission = 0;
        $total_secondary_produced = 0;
        $total_secondary_commission = 0;
        $total_produced = 0;
        $total_commission = 0;
        $total_new_sales = 0;
        foreach ($commission_summary as $value) { ?>
      <tr>         
        <td style="text-transform: capitalize;"><?= $value['rep_name']?></td>
        <td>$ <?= number_format(($value['primary_service_total']) ,2)?></td>
        <td>$ <?= number_format(($value['primary_commission']) ,2)?></td>
        <td>$ <?= number_format(($value['secondary_service_total']) ,2)?></td>
        <td>$ <?= number_format(($value['secondary_commission']) ,2)?></td>
        <td>$ <?= number_format(($value['primary_service_total']+$value['secondary_service_total']) ,2)?></td>
        <td>$ <?= number_format(($value['primary_commission']+$value['secondary_commission']) ,2)?></td>
        <td><?= $value['sold_by'] ?></td>
      </tr>
      <?php  
        $total_primary_produced += $value['primary_service_total'];
        $total_primary_commission += $value['primary_commission'];
        $total_secondary_produced += $value['secondary_service_total'];
        $total_secondary_commission += $value['secondary_commission'];
        $total_produced += $value['primary_service_total']+$value['secondary_service_total'];
        $total_commission += $value['primary_commission']+$value['secondary_commission'];
        $total_new_sales += $value['sold_by'];
        }
      
        } else { 
        ?> 
      <tr>
        <td style="text-align:center;"> No record found </td>
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
      if (!empty($commission_summary)) { 
        ?>
      <tr>
        <td><b>TOTALS</b></td>
        <td><b>$<?= number_format($total_primary_produced,2) ?></b></td>
        <td><b>$<?= number_format($total_primary_commission,2) ?></b></td>
        <td><b>$<?= number_format($total_secondary_produced,2) ?></b></td>
        <td><b>$<?= number_format($total_secondary_commission,2) ?></b></td>
        <td><b>$<?= number_format($total_produced,2) ?></b></td>
        <td><b>$<?= number_format($total_commission,2) ?></b></td>
        <td><b><?= number_format($total_new_sales) ?></b></td>
      </tr>  
      <?php
    } 
        
      ?>
    </tfoot>
  </table>
</div>


          
