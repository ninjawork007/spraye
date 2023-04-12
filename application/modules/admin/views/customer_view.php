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
			<div class="service-bols">
            	<h3 class="ser-head">On hold customers </h3>
            	<p class="text-danger ser-num"><?= getHold_Customer_Couts() ?></p>
          	</div>
        </div>
      </div>
      
      <div class="table-responsive table-spraye">
        <table class="table" id='customer_datatable'>
          <thead>
            <tr>
              <th><input type="checkbox" id="select_all" /></th>
              <th>ID</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Billing Address</th>
              <th>Properties</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          
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
<script>	
$(function() {	
	$('#toggle-one').bootstrapToggle();	
});	
</script>
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

<script type="text/javascript">
$(document).ready(function() {
    // DataTable
    var table =  $('#customer_datatable').DataTable({
        "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
        "processing": true,
        "serverSide": true,
        "paging":true,
        "pageLength":<?= $this ->session->userdata('compny_details')-> default_display_length?>,
        "order":[[1,"asc"]],
        "ajax":{
            "url": "<?= base_url('admin/ajaxGetCustomer/')?>",
            "dataType": "json",
            "type": "POST",
            "data":{
                
            }
        },
        "deferRender":false,
        "columnDefs": [
            {"targets": [0], "checkboxes":{"selectRow":true,"stateSave": true}},
        ],
        "select":"multi",
        "columns": [
            {"data": "checkbox", "checkboxes":true, "stateSave":true, "searchable":false, "orderable":false},
            {"data": "customer_id", "name":"ID", "orderable": true, "searchable": true },
            {"data": "customer_name", "name":"Name", "searchable":true, "orderable": true },
            {"data": "phone", "name":"Phone", "orderable": true, "searchable": true },
            {"data": "email", "name":"Email", "searchable":true, "orderable": true },
            {"data": "billing_street", "name":"Billing Street", "searchable":true, "orderable": true },
            {"data": "properties", "name":"Properties", "orderable": false },
            {"data": "customer_status", "name":"Status", "orderable": true },
            {"data": "action", "name":"Action", "orderable": false }
        ],
        language: {
            search: '<span></span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        dom: 'Bl<"toolbar">frtip',
        initComplete: function(){

        },
        buttons:[
        {
            extend: 'colvis',
            text: '<i class="icon-grid3"></i> <span class="caret"></span>',
            className: 'btn bg-indigo-400 btn-icon',
            // columns: [1,2,3,4,5,6,7],  <<--- This was commented out in merge code
            },
        ],
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
$('.myCheckBox').change(function() {
    //".checkbox" change
    // uncheck "select all", if one of the listed checkbox item is unchecked
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
