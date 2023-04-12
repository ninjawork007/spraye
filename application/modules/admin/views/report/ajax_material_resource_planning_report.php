<div  class="table-responsive table-spraye" id="material-resources">
  <table  class="table datatable-colvis-state" style="border: 1px solid #6eb0fe;
    border-radius: 12px;" id="material-resource-table">    
    <thead>  
      <tr>
          <th>Products Names</th>
          <th>Outstanding Services</th>
          <th>Outstanding Square Feet</th>
          <th>Estimate Amount of Product Needed</th>
        </tr>    
    </thead>
    <tbody id="material_resource_tbody">
      <?php 
        if (!empty($product_objs)) {
        $total_outstanding = 0;
        $outstanding_sqft = 0;
        // $closed_rate_total = 0;
        // $closed_rate_amt = 0;

        
        foreach ($product_objs as $value) { 
      ?>

      <tr>
        
        <td><?= $value->product_name ?></td>
        <td style="text-align:center;"><?= $value->outstanding_ct ?></td>
        <td style="text-align:center;"><?= $value->outstanding_sqft ?></td>
        <td style="text-align:center;"><?= $value->product_needed ?></td>
      </tr>
                    <?php
                      
                      }
                      
                      } else { 
                      ?> 

                    <tr>
                      <td> No record found </td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>

                    <?php }  ?>

                  </tbody>
                  
  </table>
</div>
<script type="text/javascript">
    // $(document).ready(function(){
    //   var table = $('#material-resource-table').DataTable({
    //     "processing": true,
    //     "serverSide": true,
    //     "paging": true,
    //     "pageLength": true,
    //     "order":[[0, 'asc']],
    //     "ajax": {
    //       "url": "<?= base_url('admin/Reports/ajaxMaterialResourcePlanningData') ?>",
    //       "dataType": "json", 
    //       "type": "POST",
    //       "data":{
    //         '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
    //       }, 

    //     },
    //     "deferRender": false,
    //     "columnDefs":[
    //     {
    //       "targets": [1],"orderable":false
    //     },
    //     ],
    //     "columns":[
    //       {"data":"product_name", "name":"Product Name", "searchable":true,"orderable":true},
    //       {"data":"outstanding_ct", "name":"Outstanding Services", "searchable":false,"orderable":false},
    //     ],
    //     language: {
    //             search: '<span></span> _INPUT_',
    //             lengthMenu: '<span>Show:</span> _MENU_',
    //             paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
    //         },
    //     dom: '<"toolbar">frtip',
    //     buttons:[
    //    {
    //                 extend: 'colvis',
    //                 text: '<i class="icon-grid3"></i> <span class="caret"></span>',
    //                 className: 'btn bg-indigo-400 btn-icon',
    //        columns: [0,1],


    //             },
    //  ],
    //     initComplete: function(){

    //             $("div.toolbar")
    //                 .html('<a href="#"><button type="button" class="btn btn-success">Export CSV</button</a>');
    //         },
    //   });
    // });
</script>
