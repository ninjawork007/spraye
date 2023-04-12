<div  class="table-responsive table-spraye" id="material-resources">
  <table  class="table" style="border: 1px solid #6eb0fe;
    border-radius: 12px;" id="material-resource-table">    
    <thead>  
      <tr>
          <th>Products Names</th>
          <th>Outstanding Services</th>
          <th>Outstanding Square Feet</th>
          <th>Estimate Amount of Product Needed</th>
          <th>Amount of Product on Hand</th>
          <th>Amount of Product Ordered</th>
          <th>Overage/Shortfall</th>
        </tr>    
    </thead>
    <tbody id="material_resource_tbody">
      <?php 
        if (!empty($product_objs)) {
          foreach ($product_objs as $value) { 
      ?>

      <tr>
        <td><?= $value->product_name ?></td>
        <td style="text-align:center;"><?= $value->outstanding_ct ?></td>
        <td style="text-align:center;"><?= $value->outstanding_sqft ?></td>
        <td style="text-align:center;"><?= $value->product_needed ?></td>
        <td style="text-align:center;"><?= $value->onhand ?></td>
        <td style="text-align:center;"><?= $value->ordered ?></td>
        <td style="text-align:center;"><?= $value->overage ?></td>
      </tr>
      <?php
          }
        
        } else { 
        ?> 

      <tr>
        <td colspan="5"> No record found </td>
      </tr>

      <?php }  ?>

    </tbody>           
  </table>
</div>