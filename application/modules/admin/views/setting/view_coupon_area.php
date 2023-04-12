
    
           <div  class="table-responsive spraye-table">
             <table  class="table datatable-basic-coupon" id="DataTables_Table_0" style="border: 1px solid #6eb1fd; border-radius: 4px;">    
                  <thead style="background: #3379b740;">  
                      <tr>
                          <th>Coupon ID</th>
                          <th>Coupon Code</th>
                          <th>Amount</th>
                          <th>Type</th>
                          <th>Description</th>
                          <th>Expiration Date</th>
                          <th>Action</th>
                      </tr>  
                  </thead>
                  <tbody>
                  <?php if (!empty($coupon_details)) { foreach ($coupon_details as $value) { ?>

                      <tr>

                        <td><a  onclick="editCoupon(<?= $value->coupon_id ?>)" data-toggle="modal" data-target="#modal_edit_coupon"  ><?= $value->coupon_id ?>   </a></td>
                        <td><?= $value->code ?></td>
                        <td>
                            <?php
                                if ($value->amount_calculation == 0) {
                                    echo "$";
                                }
                            ?>
                            <?= $value->amount ?>
                            <?php
                                if ($value->amount_calculation == 1) {
                                    echo "%";
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if ($value->type == 0) {
                                    echo "one-time";
                                } else {
                                    echo "permanent";
                                }
                            ?>
                        </td>
                          <td>
                              <?php
                              if ($value->description == 0) {
                                  echo $value->description;
                              } 
                              ?>
                          </td>
                        <td><?php if(isset($value->expiration_date) && $value->expiration_date != "0000-00-00 00:00:00"){ echo date('m-d-Y',strtotime($value->expiration_date));} ?></td>
                        
                        <td>
                            <ul style="list-style-type: none; padding-left: 0px;">

                                <li style="display: inline; padding-right: 10px;">
                                    <a  class="button-next" onclick="editCoupon(<?= $value->coupon_id ?>)" data-toggle="modal" data-target="#modal_edit_coupon" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                                </li>

                                <li style="display: inline; padding-right: 10px;">
                                    <a  class="button-next" onclick="deleteCoupon(<?= $value->coupon_id ?>)" ><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                </li>

                            </ul>
                        </td> 

                       
                      </tr>
                  
                  <?php } } ?>

                  </tbody>
              </table>
           </div>    
       
