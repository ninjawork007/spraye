<div class="content">
    <div class="panel panel-flat">
       <!--  <div class="panel-heading">
            <h5 class="panel-title">Users list</h5>
           
        </div> -->
      <!--    <div class="panel panel-flat">
             <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                        <div class="row">
                              <div class="col-md-12">
                                <div class="btndiv">
                                 <a href="<?= base_url('admin/addProduct') ?>"  id="save" class="btn btn-success" > Add Product</a>
                                  
                                </div>
                                <div class="btndiv">
                                  <a href="#" data-toggle="modal" data-target="#modal_add_csv" class="btn btn-info" ><i class=" icon-import"></i>  Bulk upload</a>                          
                                </div>
                                <div class="btndiv">
                                   <a href="<?= base_url('uploads/sample_file/spray_product.csv') ?>" class="btn btn-success"><i class="icon-download4"></i>   Sample file </a>
                                </div>
                              </div>
                        </div>

                        </div>                        
                   </h5>
              </div>
          </div>   -->  
      <div class="panel-body">
        
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>


       
        <div  class="table-responsive table-spraye">
           <table  class="table datatable-basic">    
                <thead>  
                    <tr>
                        <th>Name</th>                        
                        <!--<th>EPA Reg.</th>-->
                        <th  >Cost</th>
                        <!-- <th>Cost Unit</th> -->
                        <!-- <th>Formulation/Per/Unit</th> -->
                        <!-- <th>Formulation per</th>
                        <th>Formulation Unit</th> -->
                       <!--  <th>Max Wind Speed</th> -->
                        <!--<th>Application Rate</th>-->
                        <!-- <th>Application Rate Per</th>
                        <th>Application Unit</th> -->
                        <!-- <th>Temperature</th> -->
                        
						<!--<th>Active Ingredients</th>-->
                       <!--  <th>Notes</th> -->
                        <th>Services</th>
                        <th>Action</th>
                    </tr>  
                </thead>
                <tbody>
                <?php if (!empty($productData)) { $n=1; foreach ($productData as $value) { ?>

                    <tr>
                        <td style="text-transform: capitalize;">  <a href="<?=base_url("admin/editProduct/").$value->product_id ?>"><?= $value->product_name ?></a></td>
                        <!--<td><?= $value->epa_reg_nunber ?></td>-->
                        <td><?= '$'. floatval($value->product_cost) .' / '.floatval($value->product_cost_per).' '.  $value->product_cost_unit ?></td>
                        <!-- <td></td> -->
                        <!-- <td><?= $value->formulation ?>, <?= $value->formulation_per ?>/<?= $value->formulation_per_unit ?></td> -->
                       <!--  <td><?= $value->formulation_per ?></td>
                        <td><?= $value->formulation_per_unit ?></td> -->
                        <!-- <td><?= $value->max_wind_speed ?></td> -->
                        <!--<td><?= $value->application_rate ?>, <?= $value->application_rate_per ?>/<?= $value->application_per_unit ?></td>-->
                       <!--  <td><?= $value->application_rate_per ?></td>
                        <td><?= $value->application_per_unit ?></td> -->
                        <!-- <td><?= $value->temperature_information ?> <?= $value->temperature_unit ?></td> -->
                       <!--  <td><?= $value->temperature_unit ?></td> -->
						<!--<td><?php echo $value->activ_ingredients 

                //print_r($productIngredient); 
            //         $data=array(); if (!empty($value->product_id)) {
            //           //print_r($value->product_id); 

            // foreach ($value->product_id as $value3) {
                
            //       $data[] =  $value3->ingredient_name;

            //   }
            //   echo   implode(' , ',$data);
            //                  $data = ''; 
            // }

            ?></td>-->
                        <!-- <td><?= $value->product_notes ?></td> -->
                        <td><?php $data=array(); if (!empty($value->job_id)) {
                                    
                                 foreach ($value->job_id as $value2) {
                                  $data[] =  $value2->job_name;
                                 }
                             echo   implode(' , ',$data);
                             $data = '';

                              } ?></td>
                        
                         
                        
                        <td class="table-action">

                             <ul style="list-style-type: none; padding-left: 0px;">

                               <li style="display: inline; padding-right: 10px;">
                          <a href="<?=base_url("admin/editProduct/").$value->product_id ?>" class="button-next"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                                    </li>
                                    <li style="display: inline; padding-right: 10px;">
                                       <a href="<?php echo base_url("admin/productDelete/").$value->product_id ?>" class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                    </li>
                                   

                                </ul>


                        </td>

                    </tr>
                
                <?php $n++;} }  ?>

                </tbody>
            </table>
            </div>
        </div>
      </div>
</div>
<script type="text/javascript">

    $('.datatable-basic').DataTable({
        "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
        "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],

        language: {
            search: '<span></span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },


        dom: 'l<"toolbar">frtip',

    });


</script>

    <!-- Primary modal -->
          <div id="modal_add_csv" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title">Add Product</h6>
                </div>

              <form  name="csvfileimport"   action="<?= base_url('admin/addProductCsv') ?>" method="post" enctype="multipart/form-data" >

              <div class="modal-body">
                    
               
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-12">                   
                          <label>Select csv file</label>
                          <input type="file"  name="csv_file">
                        </div>
                      </div>
                    </div>
                        
                     <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" id="assignjob" class="btn btn-success">Save</button>
                     </div>
                   </div>
                </form>
              </div>
            </div>
          </div> 
          <!-- /primary modal -->



<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>
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


