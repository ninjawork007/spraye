
    
<div  class="table-responsive table-spraye">
    <table  class="table datatable-bonus ">    
        <thead>  
            <tr>
                <!-- <th>Bonus Id</th> -->
                <th>Bonus Name</th>
                <th>Bonus Percentage</th>                         
                <th>Bonus Type</th>                         
                <th>Action</th>
            </tr>  
        </thead>
        <tbody>
        <?php if (!empty($bonuses)) { foreach ($bonuses as $value) { ?>

            <tr>

                <!-- <td><a  onclick="editBonus(<?= $value->bonus_id ?>)" data-toggle="modal" data-target="#modal_bonus"  ><?= $value->bonus_id ?>   </a></td> -->

                <td><?= $value->bonus_name  ?></td>
                <td><?= $value->bonus_value.' %'   ?></td>
                <td><?php
                            switch ($value->bonus_type) {
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
                            <a  class="button-next" onclick="editBonus(<?= $value->bonus_id ?>)" data-toggle="modal" data-target="#modal_bonus" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                        </li>

                        <li style="display: inline; padding-right: 10px;">
                            <a  class="button-next" onclick="deleteBonus(<?= $value->bonus_id ?>)" ><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                        </li>

            </ul>
            </td> 

            
            </tr>
        
        <?php } } ?>

        </tbody>
    </table>
</div>    

