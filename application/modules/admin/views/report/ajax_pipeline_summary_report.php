      <div  class="table-responsive table-spraye">
             <table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe;
    border-radius: 12px;">    
                  <thead>  
                       <tr>
                          <th>User</th>
                          <th>Total # of Open Estimates</th>
                          <th>Total $ of Open Estimates</th>
                          <th>Total # of New Customer Estimates</th>	
                          <th>Total $ of New Customer Estimates</th>
                          <th>Total # of Existing Customer Estimates</th>
                          <th>Total $ of Existing Customer Estimates</th>
                      </tr>  
                  </thead>
                  <tbody>
                    <?php 
                        if (!empty($total_summary)) {
                          $total_open = 0;
                          $total_cost = 0;
                          $prospect_total = 0;
                          $prospect_total_amt = 0;
                          $customer_total = 0;
                          $customer_total_amt = 0;
                        
                          foreach ($total_summary as $value) { 
                      ?>

                        <tr>
                          <td style="text-transform: capitalize;"><?= $value['rep_name'] ?></td>
                          <td><?= $value['total_estimates'] ?></td>
                          <td>$ <?= number_format(($value['total_cost']) ,2) ?></td>
                          <td><?= $value['prospect'] ?></td>
                          <td>$ <?= number_format(($value['prospect_total']) ,2) ?></td>
                          <td><?= $value['customer'] ?></td>
                          <td>$ <?= number_format(($value['customer_total']) ,2) ?></td>
                        </tr>
                          <?php  
                            $total_open += $value['total_estimates'];
                            $total_cost += $value['total_cost'];
                            $prospect_total += $value['prospect'];
                            $prospect_total_amt += $value['prospect_total'];
                            $customer_total += $value['customer'];
                            $customer_total_amt += $value['customer_total'];
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
                        </tr>

                          <?php 
                            }  
                          ?>

                  </tbody>
                  <tfoot>
                    <?php
                  if (!empty($total_summary)) {
                          
                      ?>
                     <tr>
                          <td><b>TOTALS</b></td>
                          <td><b><?= number_format($total_open) ?></b></td>
                          <td><b>$<?= number_format($total_cost,2) ?></b></td>
                          <td><b><?= number_format($prospect_total) ?></b></td>
                          <td><b>$<?= number_format($prospect_total_amt,2) ?></b></td>
                          <td><b><?= number_format($customer_total) ?></b></td>
                          <td><b>$<?= number_format($customer_total_amt,2) ?></b></td>
                        </tr>
                        <?php 
                            }  
                          ?>
                  </tfoot>
              </table>
           </div>

          
