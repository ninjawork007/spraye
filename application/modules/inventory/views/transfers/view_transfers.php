<style>
	.section.variant-1:not(.variant-2) .header {
		font-size: 1.2rem;
	}
	.section.variant-2 .header .title {
		font-size: 1.5rem;
	}
	.timeframe ul li a {
		font-size: 1rem;
	}
	.navigation li a {
		font-size: 14px;
	}
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
   .toolbar-1 {
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

<!-- Content area -->
<div class="content invoicessss">
	<div id="loading" >
		<img id="loading-image" src="<?= base_url('assets/loader.gif') ?>"  /> <!-- Loading Image -->
	</div>
  	<div class="panel-body">
    	<b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>

		<div id="transfertablediv">
			<div  class="table-responsive table-spraye">
				<table class="table" id="transfertable">
					<thead>
						<tr>
							<th>Transfer ID</th>
							<th>Source Sub Location Name (A)</th>
							<th>Target Sub Location Name (B)</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
  	</div>
	
</div>

<!-- Edit Transfer Modal -->
<div id="modal_edit_transfer" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Edit Transfer</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Transfers/editTransfer') ?>" method="post" name="edittransfer" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">


          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label>Source Sub-Location</label>
                <select class="form-control" name="edit_source" placeholder="Source Sub-Location" id="edit_source">
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Target Sub-Location</label>
                <select class="form-control" name="edit_target" placeholder="Target Sub-Location" id="edit_target">
                </select>
              </div>
            </div>
          </div>

          <div class="row">
              <div class="col-sm-12">
              <label>Add Items</label>
              <br>
              <small>To add items, type the item name or item number of the item you'd like to add, To start adding select your source and target sub-locations</small>
              <input type="text" id="edit_item_input" class="form-control" name="edit_item_input" placeholder="Type Item Name or Item Number..." value="">
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <label>New Items</label>
                <div id="new_item_fields">

                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-12">
                <label>Edit Items</label>
                <div id="edit_item_fields">

                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-12">
                <label>Notes</label>
                <textarea class="form-control" name="edit_notes" id="edit_notes" placeholder="Notes"></textarea>
              </div>
            </div>

            <input type="hidden" name="transfer_id" id="transfer_id"/>
            <input type="hidden" name="edit_item_ids" id="edit_item_ids"/>
            <input type="hidden" name="new_edit_item_ids" id="new_edit_item_ids"/>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="saveedittransfer" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /Edit Transfer Modal -->

<!-- New Transfer Modal -->
<?php 
$subs_dd = $all_sublocations
?>
<div id="modal_new_transfer" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">New Transfer</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Transfers/newTransfer') ?>" method="post" name="newtransfer" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">


        <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                  <input type="hidden" name="item_ids" id="item_ids"/>
                <label>Source Sub-Location</label>
                <select type="text" class="form-control" name="from_sub_location_id" placeholder="Source Sub-Location" id="from_sub_location_id">
                <option value="">Choose a Sublocation</option>
                <?php foreach($subs_dd as $sub){ ?>
                        <option value="<?php echo $sub->sub_location_id; ?>"><?php echo $sub->location_name . '::' . $sub->sub_location_name; ?></option>
                    <?php } ?>

                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Target Sub-Location</label>
                <select class="form-control" name="to_sub_location_id" placeholder="Target Sub-Location" id="to_sub_location_id">
                <option value="">Choose a Sublocation</option>    
                <?php foreach($subs_dd as $sub){ ?>
                        <option value="<?php echo $sub->sub_location_id; ?>"><?php echo $sub->location_name . '::' . $sub->sub_location_name; ?></option>
                    <?php } ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
              <label>Add Items</label>
              <br>
              <small>To add items, type the item name or item number of the item you'd like to add, To start adding select your source and target sub-locations</small>
              <input type="text" id="item_input" class="form-control" name="item_input" placeholder="Type Item Name or Item Number..." value="">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Items</label>
                <div id="item_fields">

                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Notes</label>
                <textarea class="form-control" name="notes" id="notes" placeholder="Notes"></textarea>
              </div>
            </div>
            
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="savenewtransfer" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /New Transfer Modal -->

<script type="text/javascript">
	
var openTransfer = {};
var table = {};

var source = 'default';
var target = 'default';
var edit_source = 'default';
var edit_target = 'default';
var edit_changes = [];
var editItemFieldHTML = '';
var items = [];

let arrowRight = `<i class="fas fa-long-arrow-alt-right"></i>`;


	$(document).ready(function() {

		var table =  $('#transfertable').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[2,'desc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Transfers/ajaxGetTransfers')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,

		   "select":"multi",
		   "columns": [
		            {"data": "transfer_id", "name":"Transfer Id", "searchable":true, "orderable": true },
		            {"data": "from_sub_location_id", "name":"Source Sub Location Name (A)", "searchable":true, "orderable": true },
			   	    {"data": "to_sub_location_id", "name":"Target Sub Location Name (B)", "searchable":true, "orderable": true },
			   	    {"data": "created_by", "name":"Created by", "searchable":true, "orderable": true },
			   	    {"data": "created_at", "name":"Created at", "searchable":true, "orderable": true },
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
				columns: [0,1,2,3,4,5],


            },
				],
		   initComplete: function(){

				$("div.toolbar")
					.html('<a href="<?php echo base_url('inventory/Frontend/Transfers/exportTransfersCSV'); ?>"><button type="button" class="btn btn-success">Export CSV</button></a><a href="" data-toggle="modal" data-target="#modal_new_transfer"><button type="button"  class="btn btn-primary" id="newtransfersbtn">New Transfer</button></a>');
			},

		});

        $(document).on('change', '#from_sub_location_id', function(){
            source = $(this).val();
        });

        $(document).on('change', '#to_sub_location_id', function(){
            target = $(this).val();
        });
        $(document).on('change', '#edit_source', function(){
            edit_source = $(this).val();
        });

        $(document).on('change', '#edit_target', function(){
            edit_target = $(this).val();
        });
	});

    $(document).on('click', '.confirm_delete', function(e){
        e.preventDefault();
        console.log($('.confirm_delete').attr('href'));
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
    var id = $(this).data('id');
    var to = $(this).data('to');
    var from = $(this).data('from');
    console.log(from);
    items = $(this).data('item').split(', ');
    var notes = $(this).data('note');
    var subs = $(this).data('subs').split('<::>') ? $(this).data('subs').split('<::>') : [];
    
    console.log(subs);

    var selectFrom = '';
    var selectTo = '';

    $('#edit_source').val(from);
    $('#edit_target').val(to);

    edit_source = from;
    edit_target = to;

    var edit_item_ids = items;
    


    subs.forEach(sub => {
        var sub_pieces = sub.split('::');
        console.log(sub_pieces);
        if(sub_pieces[0] == from){
            selectFrom += '<option value=" ' + sub_pieces[0] + ' " selected>' + sub_pieces[1] + ' - ' + sub_pieces[2] + '</option>';
        } else {
            selectFrom += '<option value=" ' + sub_pieces[0] + ' ">' + sub_pieces[1] + ' - ' + sub_pieces[2] + '</option>';
        }

        if(sub_pieces[0] == to){
            selectTo += '<option value=" ' + sub_pieces[0] + ' " selected>' + sub_pieces[1] + ' - ' + sub_pieces[2] + '</option>';
        } else {
            selectTo += '<option value=" ' + sub_pieces[0] + ' ">' + sub_pieces[1] + ' - ' + sub_pieces[2] + '</option>';
        }
    });

    editItemFieldHTML = '<table class="table table-spraye"><thead><tr><td><strong>Item Name</strong></td><td><strong>Item #</strong></td><td><strong>Current Stock in Source</strong></td><td><strong>Transfer Quantity</strong></td><td><strong>Stock Change in Source</strong></td><td><strong>Stock Change in Target</strong></td></tr></thead>';
    editItemFieldHTML += '<tbody>';

    items.forEach(item => {
        editItemFieldHTML += '<tr><td>' + item.split(':')[1] + '</td><td>' + item.split(':')[3] + '</td><td style="text-align: center;">' + item.split(':')[4].split(' - ')[0] + '</td><td  style="width: 30%;"><input type="number" step="1" min="0" value="' + item.split(':')[2].split(' - ')[0] + '" max="' + item.split(':')[4].split(' - ')[0] + '" placeholder="How many items are you transferring?" id="transfer_quantity' +  item.split(':')[0] + '" name="transfer_quantity" class="form-control transfer_quantity"/></td><td style="text-align: center;"><strong id="source-change' + item.split(':')[0] + '">'+ item.split(':')[4].split(' - ')[0] + ' → ' + (Number(item.split(':')[4].split(' - ')[0]) - Number(item.split(':')[2].split(' - ')[0])) +'</strong></td><td style="text-align: center;"><strong id="target-change' + item.split(':')[0] + '">'+ item.split(':')[4].split(' - ')[1] + ' → ' + (Number(item.split(':')[4].split(' - ')[1]) + Number(item.split(':')[2].split(' - ')[0])) +'</strong></td></tr>';
        $(document).on('input',`#transfer_quantity${item.split(':')[0]}`, function(){
                var source_switch  = item.split(':')[4].split(' - ')[0] + ' → ' + (Number(item.split(':')[4].split(' - ')[0]) - Number($(`#transfer_quantity${item.split(':')[0]}`).val()));
                var target_switch  = item.split(':')[4].split(' - ')[1] + ' → ' + (Number(item.split(':')[4].split(' - ')[1]) + Number($(`#transfer_quantity${item.split(':')[0]}`).val()))
                $(`#source-change${item.split(':')[0]}`).html(source_switch);
                $(`#target-change${item.split(':')[0]}`).html(target_switch);

               if(edit_item_ids.length > 0){
                edit_item_ids.forEach(id => {
                    var id_split = id.split(':');
                    if (id_split[0] == item.split(':')[0]){
                        id_ind = edit_item_ids.indexOf(id);
                        edit_item_ids[id_ind] = item.split(':')[0] + ':' + item.split(':')[1] + ':' + $(`#transfer_quantity${item.split(':')[0]}`).val() + ' - ' + (Number($(`#transfer_quantity${item.split(':')[0]}`).val()) - Number(item.split(':')[2].split(' - ')[0])) + ':' + item.split(':')[3] + ':' + item.split(':')[4].split(' - ')[0] + ' - ' + item.split(':')[4].split(' - ')[1];
                    }
                });

                if(!edit_item_ids.includes(item.split(':')[0] + ':' + item.split(':')[1] + ':' + $(`#transfer_quantity${item.split(':')[0]}`).val() + ' - ' + (Number($(`#transfer_quantity${item.split(':')[0]}`).val()) - Number(item.split(':')[2].split(' - ')[0])) + ':' + item.split(':')[3] + ':' + item.split(':')[4].split(' - ')[0] + ' - ' + item.split(':')[4].split(' - ')[1])){
                    edit_item_ids.push(item.split(':')[0] + ':' + item.split(':')[1] + ':' + $(`#transfer_quantity${item.split(':')[0]}`).val() + ' - ' + (Number($(`#transfer_quantity${item.split(':')[0]}`).val()) - Number(item.split(':')[2].split(' - ')[0])) + ':' + item.split(':')[3] + ':' + item.split(':')[4].split(' - ')[0] + ' - ' + item.split(':')[4].split(' - ')[1]);
                }
               } else {
                edit_item_ids.push(item.split(':')[0] + ':' + item.split(':')[1] + ':' + $(`#transfer_quantity${item.split(':')[0]}`).val() + ' - ' + (Number($(`#transfer_quantity${item.split(':')[0]}`).val()) - Number(item.split(':')[2].split(' - ')[0])) + ':' + item.split(':')[3] + ':' + item.split(':')[4].split(' - ')[0] + ' - ' + item.split(':')[4].split(' - ')[1]);
               }
                
            });
    });

    $(document).on('click', '#saveedittransfer', function(){
            $('#edit_item_ids').val(edit_item_ids.join('::'));
        });

    editItemFieldHTML += '<div id="new_edit_items"></div>';
    editItemFieldHTML += '</tbody></table>';

    $('#edit_item_fields').html(editItemFieldHTML);

    
    $('#transfer_id').val(id);
    $('#edit_source').html(selectFrom);
    $('#edit_target').html(selectTo);
    $('#edit_notes').val(notes);

	});

    $(document).on('input', '#edit_item_input', function(){

        console.log(source);

        var new_edit_item_ids = [];

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>inventory/Frontend/Transfers/getEditItemInput',
            data: {item_input: $('#edit_item_input').val(), source: edit_source, target: edit_target, items: items.join('<::>')},
            dataType: "JSON",
            success: function (result)
            {

            var newEditItemFieldHTML = '';

            newEditItemFieldHTML = '<table class="table table-spraye"><thead><tr><td><strong>Item Name</strong></td><td><strong>Item #</strong></td><td><strong>Current Stock in Source</strong></td><td><strong>Transfer Quantity</strong></td><td><strong>Stock Change in Source</strong></td><td><strong>Stock Change in Target</strong></td></tr></thead>';
            newEditItemFieldHTML += '<tbody>';
        
            result.data.forEach(dat => {
            newEditItemFieldHTML += '<tr><td>' + dat.item_name + '</td><td>' + dat.item_number + '</td><td style="text-align: center;">' + dat.source_quantity + '</td><td  style="width: 30%;"><input type="number" step="1" min="0" max="' + dat.source_quantity + '" value="0" placeholder="How many items are you transferring?" id="transfer_quantity' +  dat.item_id + '" name="transfer_quantity" class="form-control transfer_quantity"/></td><td style="text-align: center;"><strong id="source-change' + dat.item_id + '">' + dat.source_quantity + ' → ' + dat.source_quantity + '</strong></td><td style="text-align: center;"><strong id="target-change' + dat.item_id + '">' + dat.target_quantity + ' → ' + dat.target_quantity + '</strong></td></tr>';
            $(document).on('input',`#transfer_quantity${dat.item_id}`, function(){
                var source_switch  = dat.source_quantity + ' → ' + (Number(dat.source_quantity) - Number($(`#transfer_quantity${dat.item_id}`).val()));
                var target_switch  = dat.target_quantity + ' → ' + (Number(dat.target_quantity) + Number($(`#transfer_quantity${dat.item_id}`).val()))
                $(`#source-change${dat.item_id}`).html(source_switch);
                $(`#target-change${dat.item_id}`).html(target_switch);

               if(new_edit_item_ids.length > 0){
                new_edit_item_ids.forEach(id => {
                    var id_split = id.split(':');
                    if (id_split[0] == dat.item_id){
                        id_ind = new_edit_item_ids.indexOf(id);
                        new_edit_item_ids[id_ind] = dat.item_id + ':' + dat.item_name + ':' + $(`#transfer_quantity${dat.item_id}`).val() + ' - ' + 0 + ':' + dat.item_number + ':' + dat.source_quantity + ' - ' + dat.target_quantity;
                    }
                });

                if(!new_edit_item_ids.includes(dat.item_id + ':' + dat.item_name + ':' + $(`#transfer_quantity${dat.item_id}`).val() + ' - ' + 0 + ':' + dat.item_number + ':' + dat.source_quantity + ' - ' + dat.target_quantity)){
                    new_edit_item_ids.push(dat.item_id + ':' + dat.item_name + ':' + $(`#transfer_quantity${dat.item_id}`).val() + ' - ' + 0 + ':' + dat.item_number + ':' + dat.source_quantity + ' - ' + dat.target_quantity);
                }
               } else {
                new_edit_item_ids.push(dat.item_id + ':' + dat.item_name + ':' + $(`#transfer_quantity${dat.item_id}`).val() + ' - ' + 0 + ':' + dat.item_number + ':' + dat.source_quantity + ' - ' + dat.target_quantity);
               }
                
            });

            
        });

        $(document).on('click', '#saveedittransfer', function(){
            $('#new_edit_item_ids').val(new_edit_item_ids.join('::'));
        });

        newEditItemFieldHTML += '</tbody></table>';

        $('#new_item_fields').html(newEditItemFieldHTML);
       },
       error: function(err) {
        console.log("Something went wrong! " + JSON.stringify(err));
       }
   });
});

    

  $(document).on('input', '#item_input', function(){

    console.log(source);

    $.ajax({
           type: 'POST',
           url: '<?php echo base_url(); ?>inventory/Frontend/Transfers/getItemInput',
           data: {item_input: $('#item_input').val(), source: source, target: target},
           dataType: "JSON",
           success: function (result)
           {
            console.log("Success! " + JSON.stringify(result));

            var itemFieldHTML = '';

            var item_ids = [];

            itemFieldHTML = '<table class="table table-spraye"><thead><tr><td><strong>Item Name</strong></td><td><strong>Item #</strong></td><td><strong>Current Stock in Source</strong></td><td><strong>Transfer Quantity</strong></td><td><strong>Stock Change in Source</strong></td><td><strong>Stock Change in Target</strong></td></tr></thead>';
            itemFieldHTML += '<tbody>';
            
            result.data.forEach(dat => {
                var trans_quant = 0;
                itemFieldHTML += '<tr><td>' + dat.item_name + '</td><td>' + dat.item_number + '</td><td style="text-align: center;">' + dat.source_quantity + '</td><td  style="width: 30%;"><input type="number" step="1" min="0" max="' + dat.source_quantity + '" value="0" placeholder="How many items are you transferring?" id="transfer_quantity' +  dat.item_id + '" name="transfer_quantity" class="form-control transfer_quantity"/></td><td style="text-align: center;"><strong id="source-change' + dat.item_id + '">' + dat.source_quantity + ' → ' + dat.source_quantity + '</strong></td><td style="text-align: center;"><strong id="target-change' + dat.item_id + '">' + dat.target_quantity + ' → ' + dat.target_quantity + '</strong></td></tr>';
                $(document).on('input',`#transfer_quantity${dat.item_id}`, function(){
                    var source_switch  = dat.source_quantity + ' → ' + (Number(dat.source_quantity) - Number($(`#transfer_quantity${dat.item_id}`).val()));
                    var target_switch  = dat.target_quantity + ' → ' + (Number(dat.target_quantity) + Number($(`#transfer_quantity${dat.item_id}`).val()))
                    $(`#source-change${dat.item_id}`).html(source_switch);
                    $(`#target-change${dat.item_id}`).html(target_switch);

                   if(item_ids.length > 0){
                    item_ids.forEach(id => {
                        var id_split = id.split(':');
                        if (id_split[0] == dat.item_id){
                            id_ind = item_ids.indexOf(id);
                            item_ids[id_ind] = dat.item_id + ':' + dat.item_name + ':' + Number($(`#transfer_quantity${dat.item_id}`).val()) + ' - ' + 0 + ':' + dat.item_number + ':' + dat.source_quantity + ' - ' + dat.target_quantity;
                        }
                    });

                    if(!item_ids.includes(dat.item_id + ':' + dat.item_name + ':' + Number($(`#transfer_quantity${dat.item_id}`).val()) + ' - ' + 0 + ':' + dat.item_number + ':' + dat.source_quantity + ' - ' + dat.target_quantity)){
                        item_ids.push(dat.item_id + ':' + dat.item_name + ':' + Number($(`#transfer_quantity${dat.item_id}`).val()) + ' - ' + 0 + ':' + dat.item_number + ':' + dat.source_quantity + ' - ' + dat.target_quantity);
                    }
                   } else {
                    item_ids.push(dat.item_id + ':' + dat.item_name + ':' + Number($(`#transfer_quantity${dat.item_id}`).val()) + ' - ' + 0 + ':' + dat.item_number + ':' + dat.source_quantity + ' - ' + dat.target_quantity);
                   }
                    
                });

                
            });

            $(document).on('click', '#savenewtransfer', function(){
                $('#item_ids').val(item_ids.join('::'));
            });

            

            itemFieldHTML += '</tbody></table>';

            $('#item_fields').html(itemFieldHTML);
           },
           error: function(err) {
            console.log("Something went wrong! " + JSON.stringify(err));
           }
       });
  });

  

</script>
