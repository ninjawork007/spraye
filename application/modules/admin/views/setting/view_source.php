
    
<div  class="table-responsive table-spraye">
    <table  class="table datatable-source service-tbl">    
        <thead>  
            <tr>
                <!-- <th>Source Id</th> -->
                <th>Source Name</th>
                <th>Source Type</th>                         
                <th>Action</th>
            </tr>  
        </thead>
        <tbody>
        <?php if (!empty($source_details)) { foreach ($source_details as $value) { ?>

            <tr>

                <!-- <td><a  onclick="editSource(<?= $value->source_id ?>)" data-toggle="modal" data-target="#modal_source"  ><?= $value->source_id ?>   </a></td> -->

                <td><?= $value->source_name  ?></td>
                <td><?= $value->source_type  ?></td>
            
                <td>
                    <ul style="list-style-type: none; padding-left: 0px;">
                        <li style="display: inline; padding-right: 10px;">
                            <a  class="button-next" onclick="editSource(<?= $value->source_id ?>)" data-toggle="modal" data-target="#modal_source" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                        </li>

                        <li style="display: inline; padding-right: 10px;">
                            <a  class="button-next" onclick="deleteSource(<?= $value->source_id ?>)" ><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                        </li>

                    </ul>
                </td> 

            
            </tr>
        
        <?php } } ?>

        </tbody>
    </table>
</div>    
       
