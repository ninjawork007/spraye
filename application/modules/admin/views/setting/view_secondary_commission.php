
    
           <div  class="table-responsive table-spraye">
             <table  class="table datatable-secondary-commission">    
                  <thead>  
                      <tr>
                          <th>Secondary Commission Id</th>
                          <th>Secondary Commission Name</th>
                          <th>Secondary Commission Percentage</th>                         
                          <th>Action</th>
                      </tr>  
                  </thead>
                  <tbody>
                  <?php if (!empty($tax_details)) { foreach ($tax_details as $value) { ?>

                      <tr>

                          <td><a  onclick="editSalesTaxArea(<?= $value->sale_tax_area_id ?>)" data-toggle="modal" data-target="#modal_sales_tax_area"  ><?= $value->sale_tax_area_id ?>   </a></td>

                          <td><?= $value->tax_name  ?></td>
                          <td><?= $value->tax_value.' %'   ?></td>
                        
                          <td>
                           <ul style="list-style-type: none; padding-left: 0px;">
                                    <li style="display: inline; padding-right: 10px;">
                                       <a  class="button-next" onclick="editSalesTaxArea(<?= $value->sale_tax_area_id ?>)" data-toggle="modal" data-target="#modal_sales_tax_area" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                                    </li>

                                    <li style="display: inline; padding-right: 10px;">
                                        <a  class="button-next" onclick="deleteSalesTaxArea(<?= $value->sale_tax_area_id ?>)" ><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                    </li>

                        </ul>
                       </td> 

                       
                      </tr>
                  
                  <?php } } ?>

                  </tbody>
              </table>
           </div>    
       
