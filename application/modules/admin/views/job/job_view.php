<style type="text/css">
    .label-warning, .bg-warning {
        background-color :#A9A9A9;
        background-color: #A9A9AA;
        border-color: #A9A9A9;
   }
   .label-till , .bg-till  {
    background-color: #36c9c9;
    background-color: #36c9c9;
    border-color: #36c9c9;
}
</style >

<div class="content">
    <div class="panel panel-flat">
      
        <div class="panel-body">
<b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
         
           <div  class="table-responsive table-spraye ">
             <table  class="table datatable-basic">    
                  <thead>  
                      <tr>
                          <th>Service Name</th>
                          <th>Service Price</th>
                          <th>Service Type</th>
                          <th>Service Commission</th>
                          <th>Service Bonus</th>
                          <th>Service Notes</th>
                          <th>Selected Product</th>
                          <th>Selected Program</th>
                          <!-- <th>Date</th> -->
                          <th>Action</th>
                      </tr>  
                  </thead>
                  <tbody>


                  <?php if (!empty($job_details)) { $n=1; foreach ($job_details as $value) { ?>

                      <tr>
                        <?php
                            if($value->ad_hoc != 1){
                        ?>
                          <td style="text-transform: capitalize;"><a href="<?= base_url('admin/job/editJob/').$value->job_id ?>"><?= $value->job_name ?></a></td>
                        <?php
                            } else {
                        ?>
                        <td style="text-transform: capitalize;"><?= $value->job_name ?></td>
                        <?php } ?>
                          <td>
                            <?php
                            if($value->ad_hoc != 1){
                            ?>
                            <?= '$'.$value->job_price.'/1000 sq.ft.' ?></td>
                            <?php } ?>
                          <td>
                            <?php
                            if($value->ad_hoc != 1){
                                if($value->service_type_name != '' ){
                            ?>
                            <?= $value->service_type_name ?>
                                <?php
                                } else { 
                                    echo '<span  class="label label-warning myspan">None Selected</span>';
                                }
                            }
                            ?>
                            </td>
                          <td>
                            <?php if($value->ad_hoc != 1){
                            switch ($value->commission_type) {
                                case 0:
                                echo '<span  class="label label-warning myspan">None Selected</span>';
                                break;
                                case 1:
                                echo '<span  class="label label-success myspan">Primary</span>';
                                break;
                                case 2:
                                echo '<span  class="label label-till myspan">Secondary</span>';
                                break;
                             } 
                            }
                            ?>
                          </td>
                          <td>
                            <?php if($value->ad_hoc != 1){
                            switch ($value->bonus_type) {
                                case 0:
                                echo '<span  class="label label-warning myspan">None Selected</span>';
                                break;
                                case 1:
                                echo '<span  class="label label-success myspan">Primary</span>';
                                break;
                                case 2:
                                echo '<span  class="label label-till myspan">Secondary</span>';
                                break;
                            } 
                            }
                            ?>
                          </td>
                          <td><?= $value->job_notes ?></td>
                          <td>
                          <?php 
						                  $data= array();
                              if (!empty($value->product_id)) {
                                    
                                 foreach ($value->product_id as $value2) {
                                  $data[] =  $value2->product_name;
                                 }
                             echo   implode(' , ',$data);
                             $data = '';

                              }
                           ?>
                            
                          </td> 
                          <td>
                            <?php if($value->ad_hoc != 1){
                                $data2= array();
                                if (!empty($value->program_details)) { 
                                   foreach ($value->program_details as $value3) {
                                    $ad_hoc = false;
                                    if(strstr($value3->program_name, '-Standalone Service')){
                                        $ad_hoc = true;
                                    } else if (strstr($value3->program_name, '- One Time Project Invoicing') && strstr($value3->program_name, '+')){
                                        $ad_hoc = true;
                                    } else if (strstr($value3->program_name, '- Invoiced at Job Completion') && strstr($value3->program_name, '+')){
                                        $ad_hoc = true;
                                    } else if (strstr($value3->program_name, '- Manual Billing') && strstr($value3->program_name, '+')){
                                        $ad_hoc = true;
                                    } else {
                                        $ad_hoc = false;
                                    }

                                    if ($value3->ad_hoc == 1){
                                        $ad_hoc = true;
                                    }
                                    if ($value3->program_active == 0){
                                        $ad_hoc = true;
                                    }
                                    if ($ad_hoc == false){
                                        $data2[] = $value3->program_name;
                                    }
                                   }
                                $data2 = implode(' , ',$data2);
                                if(strlen($data2) > 255) {
                                    echo substr($data2,0,255).'...';
                                } else {
                                    echo $data2;
                                }
                               $data2 = '';

                                }
                            }
                             ?>
                            
                          </td>                                
                          <!-- <td><?= $value->created_at ?></td> -->
                          

                            <td class="table-action">
                        <?php
                        if($value->ad_hoc != 1){
                        ?>
                             <ul style="list-style-type: none; padding-left: 0px;">

                               <li style="display: inline; padding-right: 10px;">
                          <a href="<?= base_url('admin/job/editJob/').$value->job_id ?>" class="button-next"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a>
                                    </li>
                                   <!-- <li style="display: inline; padding-right: 10px;">
                                       <a href="<?/*= base_url('admin/job/jobDelete/').$value->job_id */?>" class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                                    </li>-->
                                   

                                </ul>
                        <?php } ?>
                        </td>

                      </tr>
                  
                  <?php $n++;} }  ?>

                  </tbody>
              </table>
           </div>    
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

