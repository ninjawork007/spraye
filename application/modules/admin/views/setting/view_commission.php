
			<input type="hidden" name="disable_primary_commission" value="<?= $disable_primary ?>">
			<input type="hidden" name="disable_secondary_commission" value="<?= $disable_secondary ?>">  
<div  class="table-responsive table-spraye">
    <table  class="table datatable-commission ">    
        <thead>  
            <tr>
                <!-- <th>Commission Id</th> -->
                <th>Commission Name</th>
                <th>Commission Percentage</th>                         
                <th>Commission Type</th>                         
                <th>Action</th>
            </tr>  
        </thead>
        <tbody>
        <?php if (!empty($commissions)) { foreach ($commissions as $value) { ?>

            <tr>

                <!-- <td><a  onclick="editCommission(<?= $value->commission_id ?>)" data-toggle="modal" data-target="#modal_commission"  ><?= $value->commission_id ?>   </a></td> -->

                <td><?= $value->commission_name  ?></td>
                <td><?= $value->commission_value.' %'   ?></td>
                <td><?php
                            switch ($value->commission_type) {
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
                <ul style="list-style-type: none; padding-left: 0px;">
                        <li style="display: inline; padding-right: 10px;">
                            <a  class="button-next edit-commission-btn" onclick="editCommission(<?= $value->commission_id ?>)" data-toggle="modal" data-target="#modal_commission" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                        </li>

                        <li style="display: inline; padding-right: 10px;">
                            <a  class="button-next" onclick="deleteCommission(<?= $value->commission_id ?>)" ><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                        </li>

            </ul>
            </td> 

            
            </tr>
        
        <?php } } ?>

        </tbody>
    </table>
</div>