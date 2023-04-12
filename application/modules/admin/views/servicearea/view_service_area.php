
<div class="content">
    <div class="panel panel-flat">
       <!--  <div class="panel-heading">
            <h5 class="panel-title">Users list</h5>
           
        </div> -->
 
        <div class="panel panel-flat">
             <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/servicearea/addServicrArea') ?>"  id="save" class="btn btn-success" > Add Service Area</a>
                        </div>
                   </h5>
              </div>
        </div>

        <div class="panel-body">

        <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
          
           <div  class="table-responsive">
             <table  class="table datatable-basic">    
                  <thead>  
                      <tr>
                          <th>Service Area Id</th>
                          <th>Service Area</th>
                          <th>Action</th>
                      </tr>  
                  </thead>
                  <tbody>
                  <?php if ($area_details!=="") { $n=1; foreach ($area_details as $value) { ?>

                      <tr>

                          <td><a href="<?= base_url('admin/servicearea/editServiceArea/').$value->property_area_cat_id ?>"><?= $value->property_area_cat_id ?></a></td>

                          <td><?= $value->category_area_name ?></td>
                         
                          <td>
                           <ul style="list-style-type: none; padding-left: 0px;">
                                    <li style="display: inline; padding-right: 10px;">
                                       <a href="<?=base_url("admin/servicearea/editServiceArea/").$value->property_area_cat_id ?>" class="button-next "><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                                    </li>

                                    <li style="display: inline; padding-right: 10px;">
                                        <a href="<?= base_url('admin/servicearea/deleteServiceArea/').$value->property_area_cat_id ?>" class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                    </li>

                        </ul>
                       </td> 

                       
                      </tr>
                  
                  <?php $n++;} } ?>

                  </tbody>
              </table>
           </div>    
        </div>
        
        
    </div>
</div>




<script type="text/javascript">
  $('.confirm_delete').click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');
   swal({
  title: 'Are you sure?',
  text: "You won't be able to recover this !",
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#009402',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes',
  cancelButtonText: 'No'
}).then((result) => {

  if (result.value) {
   window.location = url;
  }
})


});
</script>   

