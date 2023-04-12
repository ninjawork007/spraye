
<div class="content">
    <div class="panel panel-flat">
       <!--  <div class="panel-heading">
            <h5 class="panel-title">Users list</h5>
           
        </div> -->
 <!-- 
        <div class="panel panel-flat">
             <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/users/addUser') ?>"  id="save" class="btn btn-success" > Add User</a>
                        </div>
                   </h5>
              </div>
        </div> -->

        <div class="panel-body">

        <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
          
           <div  class="table-responsive table-spraye">
             <table  class="table datatable-basic">    
                  <thead>  
                      <tr>
                          <th>Name</th>
                          <th>Applicator Number</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Role</th>
                          <th>Date</th>
                          <th>Action</th>
                      </tr>  
                  </thead>
                  <tbody>
                  <?php if ($userdata!=="") { $n=1; foreach ($userdata as $value) { ?>

                      <tr>
                          
                          <td style="text-transform: capitalize;">
                          <a href="<?=base_url("admin/users/editUser/").$value->user_id ?>">
                            <?= $value->user_first_name.' '.$value->user_last_name ?>
                          </a>    
                            </td>
                          
                          <td><?= $value->applicator_number ?></td> 
                          <td><?= $value->email ?></td>
                          <td><?= $value->phone > 0 ? preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $value->phone): '' ?> </td>
                          <td>
                          <?php
                           
                           switch ($value->role_id) {
                            case 2:
                              echo "Account Owner";
                              break;
                            case 3:
                              echo "Account Admin";
                              break;
                            case 4:
                                echo "Technician";
                                break;
                            default:
                              echo "No Role";
                              break;
                           }


                            ?>
                            
                          </td>
                          <td><?php echo date('m-d-Y', strtotime($value->created_at)); ?></td>
                         
                          <td class="table-action">
                           <ul style="list-style-type: none; padding-left: 0px;">
                                    <li style="display: inline; padding-right: 10px;">
                          <a href="<?=base_url("admin/users/editUser/").$value->user_id ?>" class="button-next "><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                                    </li>

                                    <!--<li style="display: inline; padding-right: 10px;">
                                        <a href="<?= base_url('admin/Users/deleteUser/').$value->user_id ?>" class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                    </li>-->

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

