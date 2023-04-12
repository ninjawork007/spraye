<script src="<?= base_url() ?>assets/popup/js/sweetalert2.all.js"></script>

<style type="text/css">
	#items_processing{
		top:85%!important;
	}
   #loading {
   width: 100%;
   height: 100%;
   top: 0;
   left: 0;
   position: fixed;
   display: none;
   opacity: 0.7;
   background-color: #fff;
   z-index: 99;
   text-align: center;
   }

   #loading-image {
   position: absolute;
   left: 50%;
   top: 50%;
   width: 10%;
   z-index: 100;
   }
   .btn-group {
   margin-left: -4px !important;
   margin-top: -1px !important;
   padding: 2px 2px;
   }
   .dropdown-menu {
   min-width: 80px !important;
   }
   .myspan {
   width: 55px;
   }
   .label-warning, .bg-warning {
   background-color :#A9A9A9;
   background-color: #A9A9AA;
   border-color: #A9A9A9;
   }
   .label-refunded, .bg-refunded {
      background-color : #fd7e14;
      border-color : #fd7e14;
   }
   .toolbar {
   float: left;
   padding-left: 5px;
	margin-bottom: 5px;
   }
   .dataTables_filter {
   margin-left: 60px !important;
   }
   #itemtablediv{
   padding-top: 20px;
   }
   .Invoices .dataTables_filter input {

    margin-left: 11px !important;
    margin-top: 8px !important;
    margin-bottom: 5px !important;
}
.tablemodal > tbody > tr > td, .tablemodal > tbody > tr > th, .tablemodal > tfoot > tr > td, .tablemodal > tfoot > tr > th, .tablemodal > thead > tr > td, .tablemodal > thead > tr > th {
  border-top: 1px solid #ddd;
}


.label-till , .bg-till  {
    background-color: #36c9c9;
    background-color: #36c9c9;
    border-color: #36c9c9;
}
#mytbl {
    border: 1px solid
    #6eb1fd;
    border-radius: 4px;
}
	.dt-buttons {
		display: inline-block;
		margin: 0 10px 20px 10px;
	}
</style>


<div class="content invoicessss">
   <div id="loading" >
      <img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
   </div>
   <div class="panel-body">
      <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

      <div id="itemtablediv">
         <div  class="table-responsive table-spraye">
            <table class="table" id="items">
               <thead>
                  <tr>
                        <th>Brand Name</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Brand Description</th>
                        <th>Items Registered</th>
                        <th>Actions</th>
                  </tr>
               </thead>

            </table>
         </div>
      </div>
   </div>
</div>
<!-- /form horizontal -->

<!-- Edit Item Type Modal -->
<div id="modal_edit_brand" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Edit Brand</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Brands/editBrand') ?>" method="post" name="editbrand" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">


          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Brand Name</label>
                <input type="text" class="form-control" name="brand_name" placeholder="Brand Name" id="brand_name">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Brand Description</label>
                <textarea class="form-control" name="brand_description" id="brand_description" placeholder="Brand Description"></textarea>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="savebrand" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /Edit Item Type Modal -->

<!-- New Item Type Modal -->
<div id="modal_new_brand" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">New Brand</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Brands/newBrand') ?>" method="post" name="newbrand" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">


          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Brand Name</label>
                <input type="text" class="form-control" name="brand_name" placeholder="Brand Name" id="brand_name">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Brand Description</label>
                <textarea class="form-control" name="brand_description" id="brand_description" placeholder="Brand Description"></textarea>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="savenewbrand" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /Edit Item Type Modal -->


<!-- /content area -->
<script type="text/javascript">
   $(document).ready(function(){

	// console.log("Ajax Call");

	 var table =  $('#items').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[2,'desc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Brands/ajaxGetBrands')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,

		   "select":"multi",
		   "columns": [
		            {"data": "brand_name", "name":"Brand Name", "searchable":true, "orderable": true },
			   	    {"data": "created_by", "name":"Created By", "searchable":true, "orderable": true },
		            {"data": "created_at", "name":"Created At", "searchable":true, "orderable": true },
			   	    {"data": "brand_description", "name":"Brand Description", "orderable": true, "searchable": true },
                    {"data": "items_registered", "name": "Items Registered", "searchable":false, "orderable":false},
			   	    {"data": "actions", "name":"Actions","class":"table-action", "searchable":false, "orderable":false}
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
		   dom: '<"toolbar">frtip',
		    buttons:[
				{
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon',
				columns: [0,1,2,3,4],


            },
				],
		   initComplete: function(){

           $("div.toolbar")
              .html('<a href="<?php echo base_url('inventory/Frontend/Brands/exportBrandsCSV'); ?>"><button type="button" class="btn btn-success">Export CSV</button></a><a href="" data-toggle="modal" data-target="#modal_new_brand"><button type="button"  class="btn btn-primary" id="newbrandbtn">New Brand</button></a>');
        },

	});

});

$(document).on('click', '.confirm_item_delete', function(e){
    e.preventDefault();
    console.log($('.confirm_item_delete').attr('href'));
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
});


});

$(document).on('click', '.modal_trigger', function(e){
    e.preventDefault();
    var name = $(this).data('name');
    var desc = $(this).data('desc');
    $('#brand_name').val(name);
    $('#brand_description').val(desc);
});

</script>