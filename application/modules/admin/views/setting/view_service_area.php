
    
           <div  class="table-responsive spraye-table">
             <table  class="table datatable-basic" id="DataTables_Table_0">    
                  <thead>  
                      <tr>
                          <th>Service Area Id</th>
                          <th>Service Area</th>
                          <th>Description</th>
                          <th>Action</th>
                      </tr>  
                  </thead>
                  <tbody>
                  <?php if (!empty($area_details)) { foreach ($area_details as $value) { ?>

                      <tr>

                          <td><a  onclick="editServiceArea(<?= $value->property_area_cat_id ?>)" data-toggle="modal" data-target="#modal_edit_service_area"  ><?= $value->property_area_cat_id ?>   </a></td>

                          <td><?php if(isset($value->category_area_name)){ echo $value->category_area_name; }?></td>
                          <td><?php if(isset($value->category_description)){ echo $value->category_description; }?></td>

                          <td>
                           <ul style="list-style-type: none; padding-left: 0px;">
                                    <li style="display: inline; padding-right: 10px;">
                                       <a  class="button-next" onclick="editServiceArea(<?= $value->property_area_cat_id ?>)" data-toggle="modal" data-target="#modal_edit_service_area" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                                    </li>

                                    <li style="display: inline; padding-right: 10px;">
                                        <a  class="button-next" onclick="deleteServiceArea(<?= $value->property_area_cat_id ?>)" ><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                    </li>

                        </ul>
                       </td> 

                       
                      </tr>
                  
                  <?php } } ?>

                  </tbody>
              </table>
           </div>    
       
