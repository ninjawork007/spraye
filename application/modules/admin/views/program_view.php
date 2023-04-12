
<div class="content">
    <div class="panel panel-flat">
       <!--  <div class="panel-heading">
            <h5 class="panel-title">Users list</h5>
           
        </div> -->
         
 <!--        <div class="panel panel-flat">
         <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                          <a href="<?= base_url('admin/addProgram') ?>"  id="save" class="btn btn-success" > Add Program</a>
                        </div>
                   </h5>
              </div>
            </div> -->

        <div class="panel-body">
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
       
        
        <div  class="table-responsive table-spraye">
           <table  class="table datatable-program">    
                <thead>  
                    <tr>
                        <!-- <th>S. NO</th>-->
                        <th>Program Name</th>
                        <th>Pricing</th>
                        <!--<th>Program Notes</th>-->
                        <th>Services</th>
                        <!-- <th>Properties</th> -->
                        <th>Action</th>
                    </tr>  
                </thead>
                <tbody>
                <?php if (!empty($programData)) { $n=1; foreach ($programData as $value) { ?>

                    <tr>
                        <!-- <td><?= $n ?></td> -->
                        
                        <td><a href="<?=base_url("admin/editProgram/").$value->program_id ?>" class="button-next"><?= $value->program_name ?></a></td>
                        <td><?php 
                          switch ($value->program_price) {
                            case 1:
                              echo 'One-Time Project Invoicing';
                              break;
                            case 2:
                              echo 'Invoiced at Job Completion';
                              break;
                            case 3:
                              echo 'Manual Billing';
                              break;                           
                            
                          }
                         ?></td>
                        <!--<td><?= $value->program_notes ?></td>-->
                        <td><?php $data=array(); if (!empty($value->job_id)) {
                                    
                                 foreach ($value->job_id as $value2) {
                                  $data[] =  $value2->job_name;
                                 }
                             echo   implode(' , ',$data);
                             $data = '';

                              } ?>
                         </td> 
                         
                        <!--  <td><?php $data2=array(); if (!empty($value->property_details)) {
                                    
                                 foreach ($value->property_details as $value3) {
                                  $data2[] =  $value3->property_title;
                                 }
                             echo   implode(' , ',$data2);
                             $data2 = '';

                              } ?>
                         </td>  -->
                       

                        
                        <td class="table-action" width="10%">
                          <?php
                          if($value->ad_hoc !=1){
                          ?>
                         <ul style="list-style-type: none; padding-left: 0px;">

                            <li style="display: inline; padding-right: 10px;">
                              <a href="<?=base_url("admin/editProgram/").$value->program_id ?>" title="Edit" ><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                            </li>
                            
                            <li style="display: inline; padding-right: 10px;">
                              <a  href="<?=base_url("admin/addCopyProgram/").$value->program_id ?>" title="Copy" ><img src="<?= base_url('assets/img/duplicate.png') ?>"></a>
                            </li>
							 
                            <?php
                            if(strstr($_SERVER['REQUEST_URI'],'programListArchived'))
                            {
                              ?>
                              <li style="display: inline; padding-right: 10px;">
                                <a title="Activate" href="<?php echo base_url("admin/programActive/").$value->program_id ?>" class="button-next"><i class="icon-upload   position-center" style="color: #9a9797;"></i></a>
                            </li>
                            <?php
                            }
                            else
                            {
                            ?>

                                <li style="display: inline; padding-right: 10px;">
                                <a href="<?=base_url("admin/programDelete/").$value->program_id ?>" class="confirm_delete button-next" title="Delete" ><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                </li>
                                                
                              <?php
                              }
                                ?>
                          </ul>
                              <?php } ?>

                        </td>

                    </tr>
                
                <?php $n++;} } ?>

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

<script type="text/javascript">
var table =      $('.datatable-program').DataTable({
    "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
    "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
        autoWidth: false,
        columnDefs: [{ 
            orderable: false,
            width: '100px',
            // targets: [ 0 ]


        }],

        // lengthMenu : [[5, 10, 15, -1], [1, 2, 3, "All"]],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span></span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });

  $("input[type='search']").on( 'keyup', function () {
    table
        .columns( 0 )
        .search( this.value )
        .draw();
} );
</script>
