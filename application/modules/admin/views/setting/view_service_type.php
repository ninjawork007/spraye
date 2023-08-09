
    
<div  class="table-responsive table-spraye">
    <table  class="table datatable-service-type ">    
        <thead>  
            <tr>
                <!-- <th>Service Type Id</th> -->
                <th>Service Type Name</th>
                <th>Service  Type</th>
                <th>Color</th>
                <th>Action</th>
            </tr>  
        </thead>
        <tbody>
        <?php if (!empty($service_type)) { foreach ($service_type as $value) { ?>

            <tr>

                <!-- <td><a  onclick="editServiceType(<?= $value->service_type_id ?>)" data-toggle="modal" data-target="#modal_service_type"  ><?= $value->service_type_id ?>   </a></td> -->

                <td><?= $value->service_type_name  ?></td>
                <td><?php
                            switch ($value->service_type) {
                              case "1":
                                echo 'Primary ';
                                break;
                              case "2":
                                echo 'Secondary ';
                                break;
                              default:
                                echo 'Other';
                            }

                          ?></td>

                <td>
                    <b style="color: <?= $value->service_type_color ?>;" ><?= $serviceTypeAllowedColors[$value->service_type_color] ?></b>
                    <p style="background-color: <?= $value->service_type_color ?>; display: inline-block; width: 18px; height: 18px; margin: 0px;"></p>
                </td>

                <td>
                    <ul style="list-style-type: none; padding-left: 0px;">
                        <li style="display: inline; padding-right: 10px;">
                            <a  class="button-next" onclick="editServiceType(<?= $value->service_type_id ?>)" data-toggle="modal" data-target="#modal_service_type" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                        </li>

                        <li style="display: inline; padding-right: 10px;">
                            <a  class="button-next" onclick="deleteServiceType(<?= $value->service_type_id ?>)" ><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                        </li>

                    </ul>
                </td> 

            
            </tr>
        
        <?php } } ?>

        </tbody>
    </table>
</div>    
       
