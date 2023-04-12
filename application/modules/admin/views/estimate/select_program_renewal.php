<div class="content">
    <div class="panel panel-flat">
        <div class="panel-heading">
          <h5 class="panel-title">
            <div class="form-group">
              <a href="<?= base_url('admin/Estimates/') ?>"  id="previous-page-btn" class="btn btn-success" ><i class=" icon-arrow-left7"> </i> Back to Estimates Main Menu</a>
            </div>
          </h5>
        </div>
        <br>
        <div class="panel-body">
         <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
       
         <div  class="table-responsive table-spraye">
          <h4 style="text-transform: capitalize;">Please select the program you would like to renew from the list below</h4>
           <table  class="table datatable-program">    
                <thead>  
                    <tr>
                        <th>Program Name</th>
                        <th>Pricing</th>
                        <th>Services</th>
                    </tr>  
                </thead>
                <tbody>
                <?php if (!empty($programData)) { $n=1; foreach ($programData as $value) { ?>

                    <tr>     
                        <td><a  href="<?=base_url("admin/estimates/addBulkRenewalProgram/").$value->program_id ?>" title="Copy" class="button-next"><?= $value->program_name ?></a></td>
                        <td><?php 
                          switch ($value->program_price) {
                            case 1:
                              echo 'One Time Project Invoicing';
                              break;
                            case 2:
                              echo 'Invoiced at Job Completion';
                              break;
                            case 3:
                              echo 'Manual Billing';
                              break;                           
                            
                          }
                         ?></td>

                        <td><?php $data=array(); if (!empty($value->job_id)) {
                                    
                                 foreach ($value->job_id as $value2) {
                                  $data[] =  $value2->job_name;
                                 }
                             echo   implode(' , ',$data);
                             $data = '';

                              } ?>
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

