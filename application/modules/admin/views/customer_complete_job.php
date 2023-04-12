
<div class="content">
    <div class="panel panel-flat">
       <!--  <div class="panel-heading">
            <h5 class="panel-title">Users list</h5>
           
        </div> -->
        

        <div class="panel panel-flat">
             <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                         <div class="row">
                           <div  class="col-md-12">
                             
                                  <div class="btndiv" >
                                    
                                    <a href="<?= base_url ('admin/customerList') ?>"  id="save" class="btn btn-success" ><i class=" icon-arrow-left7"> </i>  Back to All Customers</a>
                                  </div>
                                  
                                </div>
                           
                           </div>
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
                       <th>TECHNICIAN NAME</th>
                       <th>JOB NAME</th>
                       <th>ASSIGN DATE</th>
                       
                       <th>ADDRESS</th>
                       <th>SERVICE AREA</th>
                       <th>PROGRAM</th>
                       
                    </tr>  
                </thead>
                <tbody>
                  <?php                  
                       if (!empty($job_details)) {
                       
                             foreach ($job_details as $value) {
                          ?>
                                  <tr>
                                      <td><?= $value->user_first_name.' '.$value->user_last_name; ?></td>
                                      <td><?=$value->job_name; ?></td>
                                      <td><?=$value->job_assign_date; ?></td>
                                      <td><?= $value->property_address ?></td>
                                      <td><?= $value->category_area_name ?></td>
                                      <td><?=$value->program_name ?></td>
                                  </tr>
                                <?php } } ?>

                </tbody>
            </table>
            </div>
        </div>    
    </div>
</div>


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

                              