 <div  class="table-responsive table-spraye">
             <table  class="table datatable-button-init-custom" id="mytbl">    
                  <thead>  
                      <tr>
                          <th><input type="checkbox" id="select_all" <?php if (empty($invoice_details)) { echo 'disabled'; }  ?>    /></th>          
                          <th>Invoice</th>
                          <th>Customer Name</th>
                          <th>Email</th>
                          <th>Amount</th>
                          <th>Balance Due</th>
                          <th>Status</th>
                          <th>Date</th>
                          <th>Action</th>                     
                      </tr>  
                  </thead>
                  <tbody>
                  <?php if (!empty($invoice_details)) { 
                    foreach ($invoice_details as $value) { ?>      
                      <tr>
                        <td><input  name="group_id" type="checkbox"  value="<?=$value->invoice_id.':'.$value->customer_id ?>" invoice_id="<?=$value->invoice_id ?>" class="myCheckBox" /></td>            
                        <td><a href="<?= base_url('admin/Invoices/editInvoice/').$value->invoice_id ?>"><?= $value->invoice_id; ?></a></td>
                        <td><?= $value->first_name.' '.$value->last_name ?></td>
                        <td><?= $value->email ?></td>

                        <?php 
                          $total_tax_amount = getAllSalesTaxSumByInvoice($value->invoice_id)->total_tax_amount;
                        ?>

                        <td><?= '$ '.number_format($value->cost+$total_tax_amount,2) ?></td>

                         <?php $due = $value->cost+$total_tax_amount-$value->partial_payment; ?>
                        <td><?=  $due<=0 ? '$ 0.00' : '$ '.number_format($due,2)    ?></td>
                        
                        <td width="20%" ><?php switch ($value->status) {
                          case 0:
                            echo '<span  class="label label-warning myspan">Unsent</span>';
                            $bg= 'bg-warning';
                            break;
                          case 1:
                            echo '<span  class="label label-danger myspan">Sent</span>';
                            $bg= 'bg-danger';
                            break;
                          
                          case 2:
                            echo '<span  class="label label-success myspan">Paid</span>';
                           $bg= 'bg-success';
                           break;

                            case 3:
                             echo '<span  class="label label-till myspan">Partial</span>';
                              $bg= 'bg-till';
                            break;
                        } ?>                         
                       <div class="btn-group">
                        <a href="#" class="label <?= $bg  ?> dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right" >
                          <li class="changestatus"  invoice_id="<?= $value->invoice_id ?>" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li>
                          <li class="changestatus" invoice_id="<?= $value->invoice_id ?>" value="1" ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>
                          <li class="changestatus" invoice_id="<?= $value->invoice_id ?>" value="2" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>
 
                          <li class="changestatus" invoice_id="<?= $value->invoice_id ?>" value="3"  over_all_total="<?= floatval($value->cost+$total_tax_amount)  ?>" partial_payment= "<?= floatval($value->partial_payment) ?>"  ><a href="#"><span class="status-mark bg-till position-left" ></span> Partial</a></li>
                      
                        </ul>
                      </div> 
                     </td>
                     <td><?= $value->invoice_date ?></td>
                     <td>
                        <ul style="list-style-type: none; padding-left: 0px;">

                               <li style="display: inline; padding-right: 10px;">
                                  <a  class="email button-next" id="<?= $value->invoice_id ?>"  customer_id="<?= $value->customer_id ?>"    ><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a>
                                </li>

                                  <li style="display: inline; padding-right: 10px;">
                                     <a  class="" > <i class="icon-info22 position-center" style="color: #9a9797;" onclick="productDetailsGet(<?= $value->job_id ?>)"  ></i></a>
                                  </li>


                                <li style="display: inline; padding-right: 10px;">
                                   <a href="<?= base_url('admin/invoices/pdfInvoice/').$value->invoice_id ?>" target="_blank" class=" button-next"><i class=" icon-file-pdf position-center" style="color: #9a9797;"></i></a>
                                </li>
                                 <li style="display: inline; padding-right: 10px;">
                                   <a href="<?= base_url('admin/invoices/printInvoice/').$value->invoice_id ?>" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a>
                                </li>                             
                            </ul>
                        </td>
                       
                      </tr>                     
                  <?php  }  } ?>
                  </tbody>
              </table>
           </div>              
