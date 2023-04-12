<style type="text/css"> 
.toolbar {
  float: left;
  padding-left: 5px;
}
.dataTables_wrapper.no-footer {
  padding-top: 20px
}
</style>
<div class="content">
  <div class="panel panel-flat">
    
    <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
      <div class="row cx-dt">
        <div class="col-md-3 col-sm-3 col-12">
          <div class=" service-bols">
            <h3 class="ser-head">Total customers </h3>
            <p class="text-warning ser-num "><?= $total_customer ?></p>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-12">
          <div class="service-bols">
            <h3 class="ser-head">Active customers</h3>
            <p class=" ser-num text-success"><?= $active_customer ?></p>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-12">
          <div class="service-bols">
            <h3 class="ser-head">Inactive customers </h3>
            <p class="text-danger ser-num"><?= $non_active_customer ?></p>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-12">
          <!--  <form role="form " class="cx-src">
                  <div class="form-group">
                     <input type="text" class="form-control empty" id="iconified" placeholder=" Search">
                  </div>
               </form> -->
        </div>
      </div>
      <!--    <div class="row">
            <div class="col-md-4">
               <div class="panel panel-flat">
                  <div class="content-box-div">
                     <center>Total Customers</center>
                  </div>
                  <div class="panel-body content-pannel-body">
                     <div class="chart-container">
                        <b>
                           <h4>
                              <center><span id="total_unpaid"><?= $total_customer ?></span></center>
                           </h4>
                        </b>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="panel panel-flat">
                  <div class="content-box-div">
                     <center>Active Customers</center>
                  </div>
                  <div class="panel-body content-pannel-body">
                     <div class="chart-container">
                        <b>
                           <h4>
                              <center><span id="total_unpaid"><?= $active_customer ?></span></center>
                           </h4>
                        </b>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="panel panel-flat">
                  <div class="content-box-div">
                     <center>Inactive Customers</center>
                  </div>
                  <div class="panel-body content-pannel-body">
                     <div class="chart-container">
                        <b>
                           <h4>
                              <center><span id="total_unpaid"><?= $non_active_customer ?></span></center>
                           </h4>
                        </b>
                     </div>
                  </div>
               </div>
            </div>
         </div> -->
      <div class="table-responsive table-spraye">
        <table class="table datatable-basic">
          <thead>
            <tr>
              <th><input type="checkbox" id="select_all" <?php if (empty($customer)) { echo 'disabled'; }  ?> /></th>
              <!-- <th>S. NO</th> -->
              <th>Name</th>
              <!--<th>Phone Number</th>-->
              <th>Email</th>
              <th>Billing Address</th>
              <!-- <th>Billing Address 2</th>
                        <th>City</th>
                        <th>State</th>
                        <th>ZipCode</th> -->
              <th>Properties</th>
              <!-- <th>Programs</th> -->
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($customer)) { $n=1; foreach ($customer as $value) { ?>
            <tr>
              <td>
                <input type="checkbox" class="myCheckBox" value='<?php echo $value->customer_id?>'
                  name="selectcheckbox">
              </td>
              <!-- <td><?= $n ?></td> -->
              <td><a href="<?=base_url("admin/editCustomer/").$value->customer_id ?>"
                  class="button-next"><?= $value->first_name ?> <?= $value->last_name ?></a></td>
              <!--<td><?= $value->phone ?></td>-->
              <td><?= $value->email ?></td>
              <td><?= $value->billing_street ?></td>
              <!--  <td><?= $value->billing_street_2 ?></td> -->
              <!--  <td><?= $value->billing_city ?></td>
                        <td><?= $value->billing_state ?></td>
                        <td><?= $value->billing_zipcode ?></td> -->
               <td>
                  <?php 
                     $data=array();
                     if (!empty($value->property_id)) {                        
                        foreach ($value->property_id as $value2) {
                           $data[] =  $value2->property_title;
                        }            
                        echo implode(',',$data);
                        $data = '';                        
                     }
                  ?>
               </td>
              <!--  <td><?php 
                        $data2=array();
                          if (!empty($value->program_details)) {
                        
                             foreach ($value->program_details as $value2) {
                              $data2[] =  $value2->program_name;
                             }            
                              echo implode(',',$data2);
                            $data2 = '';
                        
                          }
                        ?></td> -->
              <td>
                <?php if ($value->customer_status==1) { ?>
                <span class="label label-success">Active</span>
                <?php } else { ?>
                <span class="label label-danger">Non-Active</span>
                <?php } ?>
              </td>
              <td class="table-action" width="10%">
                <ul style="list-style-type: none; padding-left: 0px;">
                  <li style="display: inline; padding-right: 10px;">
                    <a href="<?=base_url("admin/editCustomer/").$value->customer_id ?>" title="Edit"><i
                        class="icon-pencil   position-center" style="color: #9a9797;"></i></a>
                  </li>
                  <!--   <li style="display: inline; padding-right: 10px;">
                              <a href="<?=base_url("admin/invoices/getOpenInvoiceByCustomer/").$value->customer_id ?>" title="invoice" target="_blank" ><i class="icon-printer2  position-center" style="color: #9a9797;"></i></a>
                           </li> -->
                  <li style="display: inline; padding-right: 10px;">
                    <a href="<?=base_url("admin/customerDelete/").$value->customer_id ?>"
                      class="confirm_delete button-next" title="Delete"><i class="icon-trash   position-center"
                        style="color: #9a9797;"></i></a>
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
<!-- Primary modal -->
<div id="modal_add_csv" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Add Customer</h6>
      </div>
      <form name="csvfileimport" action="<?= base_url('admin/addCustomerCsv') ?>" method="post"
        enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Select csv file</label>
                <input type="file" name="csv_file">
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
<!-- /primary modal -->
<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>
<script type="text/javascript">
$('.confirm_delete').click(function(e) {
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
<?php
$order =   $table_details  ? $table_details->colmn_order!="" ? " [[ $table_details->colmn_id , '$table_details->colmn_order'   ]]   "  : " []  " : " [] " ;
// echo $order;
?>
<script type="text/javascript">
var table = $('.datatable-basic').DataTable({
    "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
  "order": <?= $order ?>,
  language: {
    search: '<span></span> _INPUT_',
    lengthMenu: '<span>Show:</span> _MENU_',
    paginate: {
      'first': 'First',
      'last': 'Last',
      'next': '&rarr;',
      'previous': '&larr;'
    }
  },
  dom: 'l<"toolbar">frtip',
  initComplete: function() {
    $("div.toolbar")
      .html('<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled ><i class=" icon-trash btn-del"></i> Delete</button>');
  }
});
$('.datatable-basic').on('order.dt', function() {
  var order = table.order();
  console.log('Ordering on column ' + order[0][0] + ' (' + order[0][1] + ')');
  $.ajax({
    type: "POST",
    url: "<?= base_url('admin/dataTableManage') ?>",
    data: {
      table_name: 'customer',
      colmn_id: order[0][0],
      colmn_order: order[0][1]
    }
  }).done(function(data) {
    console.log(data)
  });
});
$('.datatable-basic').on('length.dt', function(e, settings, len) {
  $.ajax({
    type: "POST",
    url: "<?= base_url('admin/dataTableManage') ?>",
    data: {
      table_name: 'customer',
      page_lenght: len
    }
  }).done(function(data) {
    console.log(data)
  });
});
$("#select_all").change(function() { //"select all" change 
  var status = this.checked; // "select all" checked status
  if (status) {
    $('#deletebutton').prop('disabled', false);
  } else {
    $('#deletebutton').prop('disabled', true);
  }
  $('.myCheckBox').each(function() { //iterate all listed checkbox items
    this.checked = status; //change ".checkbox" checked status
  });
});
$('.myCheckBox').change(function() { //".checkbox" change 
  //uncheck "select all", if one of the listed checkbox item is unchecked
  if (this.checked == false) { //if this item is unchecked
    $("#select_all")[0].checked = false; //change "select all" checked status to false
  }
  //check "select all" if all checkbox items are checked
  if ($('.myCheckBox:checked').length == $('.myCheckBox').length) {
    $("#select_all")[0].checked = true; //change "select all" checked status to true
  }
});
var checkBoxes = $('table .myCheckBox');
checkBoxes.change(function() {
  $('#deletebutton').prop('disabled', checkBoxes.filter(':checked').length < 1);
});
checkBoxes.change();
function deletemultiple() {
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
      var selectcheckbox = [];
      $("input:checkbox[name=selectcheckbox]:checked").each(function() {
        selectcheckbox.push($(this).val());
      });
      $.ajax({
        type: "POST",
        url: "<?= base_url('admin/deletemultipleCustomers') ?>",
        data: {
          customers: selectcheckbox
        }
      }).done(function(data) {
        if (data == 1) {
          swal(
            'Customers !',
            'Deleted Successfully ',
            'success'
          ).then(function() {
            location.reload();
          });
          
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }
      });
    }
  })
}
</script>