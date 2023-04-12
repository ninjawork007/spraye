<?php
// $this->load->view('templates/master');
// $this->load->view('transfers/modals/transfer_modal');
// $this->load->view('components/error_modal') 

?>

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

		<div id="adjustmenttablediv">
			<div  class="table-responsive table-spraye">
				<table class="table" id="adjustmenttable">
					<thead>
						<tr>
							<th>Quantity Adjustment Id</th>
							<th>Item Name</th>
                            <th>Date</th>
							<th>Location</th>
							<th>Sub-Location</th>
							<th>Adjustment Amount</th>
                            <th>Adjustment Type</th>
                            <th>Created By</th>
                            <th>Units Lost</th>
                            <th>Value Lost</th>
							<th>Actions</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
  	</div>
	
</div>

<!-- Edit Adjustment Modal -->
<div id="modal_edit_adjustment" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Edit Adjustment</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Adjustments/editAdjustment') ?>" method="post" name="editadjustment" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">

        <div class="form-group">
        <div class="col-sm-12">
                <label>Sub-Location</label>
               <div id="edit_sub">

               </div>
              </div>
        </div>
            
              <div class="col-sm-12">
                <label>Item</label>
                <div id="edit_item_input">
                    
                </div>
                <hr>
                <div id="edit_item_fields">
                <table class="table table-spraye">
                    <thead>
                        <tr>
                            <td>
                                <strong>Item Name</strong>
                            </td>
                            <td>
                                <strong>Item #</strong>
                            </td>
                            <td>
                                <strong>Current Stock</strong>
                            </td>
                            <td>
                                <strong>Adjustment Type</strong>
                            </td>
                            <td>
                                <strong>Adjustment Quantity</strong>
                            </td>
                            <td>
                                <strong>Stock After Adjustment</strong>
                            </td>
                        </tr>
                    </thead>
                    <tbody id="edit_item_info">
                        
                    </tbody>
                </table>

                </div>
              </div>
            
            <input type="hidden" name="edit_item_id" id="edit_item_id"/>
            <input type="hidden" name="adjust_id" id="adjust_id"/>
            <input type="hidden" name="subloc" id="subloc"/>
            <input type="hidden" name="loc" id="loc"/>
            <input type="hidden" name="ad_type" id="ad_type">            
            <div class="row">
              <div class="col-sm-12">
                <label>Notes</label>
                <textarea class="form-control" name="edit_notes" id="edit_notes" placeholder="Notes"></textarea>
              </div>
            </div>
            <hr/>
         

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="saveeditadjustment" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /Edit Adjustment Modal -->

<!-- New Transfer Modal -->
<?php 
$subs_dd = $all_sublocations
?>
<div id="modal_new_adjustment" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary" style="background: #36c9c9;border-color: #36c9c9;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">New Adjustment</h6>
      </div>

      <form action="<?= base_url('inventory/Frontend/Adjustments/newAdjustment') ?>" method="post" name="newadjustment" enctype="multipart/form-data" form_ajax="ajax">

        <div class="modal-body">

        <div class="form-group">
        <div class="col-sm-12">
                <label>Sub-Location</label>
                <select class="form-control" name="sub_location_id" placeholder="Source Sub-Location" id="sub_location_id">
                <option value="">Choose a Sublocation</option>
                <?php foreach($subs_dd as $sub){ ?>
                        <option value="<?php echo $sub->location_id  . ':' . $sub->sub_location_id; ?>"><?php echo $sub->location_name . ' - ' . $sub->sub_location_name; ?></option>
                    <?php } ?>

                </select>
              </div>
        </div>

        <div class="row">
              <div class="col-sm-12">
              <label>Add Item</label>
              <br>
              <small>To add items, type the item name or item number of the item you'd like to add, To start adding select your sub-location</small>
              <input type="text" id="item_input" class="form-control" name="item_input" placeholder="Type Item Name or Item Number..." value="">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Item</label>
                <div id="item_dropdown">
                    
                </div>
                <div id="item_fields">

                </div>
              </div>
            </div>
            <input type="hidden" name="item_id" id="item_id"/>
            <div class="row">
              <div class="col-sm-12">
                <label>Notes</label>
                <textarea class="form-control" name="notes" id="notes" placeholder="Notes"></textarea>
              </div>
            </div>
            <hr/>
        

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="savenewadjustment" class="btn btn-success">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /New Transfer Modal -->



<script type="text/javascript">

let arrowRight = `<i class="fas fa-long-arrow-alt-right"></i>`;

var sub_location = '';

$(document).on('change', '#sub_location_id', function(){
    sub_location = $(this).val();
});


	$(document).ready(function() {

		var table =  $('#adjustmenttable').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[2,'desc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Adjustments/ajaxGetAdjustments')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,

		   "select":"multi",
		   "columns": [
		            {"data": "quantity_adjustment_id", "name":"Quantity Adjustment Id", "searchable":true, "orderable": true },
		            {"data": "item_name", "name":"Item Name", "searchable":true, "orderable": true },
                    {"data": "adjustment_date", "name": "Date", "searchable":false, "orderable": true  },
			   	    {"data": "location", "name":"Location", "searchable":true, "orderable": true },
			   	    {"data": "sub_location", "name":"Sub-location", "searchable":true, "orderable": true },
			   	    {"data": "quantity_adjustment_amount", "name":"Adjustment Amt.", "searchable":false, "orderable": false },
                    {"data": "adjustment_type", "name":"Adjustment Type", "searchable":true, "orderable": true },
                    {"data": "created_by", "name":"Created By", "searchable":true, "orderable": true },
                    {"data": "units_lost", "name":"Units Lost", "searchable":false, "orderable": false },
                    {"data": "value_lost", "name":"Value Lost", "searchable":false, "orderable": false },
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
				columns: [0,1,2,3,4,5,6],


            },
				],
		   initComplete: function(){

				$("div.toolbar")
					.html('<a href="<?php echo base_url('inventory/Frontend/Adjustments/exportAdjustmentsCSV'); ?>"><button type="button" class="btn btn-success">Export CSV</button></a><a href="" data-toggle="modal" data-target="#modal_new_adjustment"><button type="button"  class="btn btn-primary" id="newadjustmentbtn">New Adjustment</button></a>');
			},

		});

        // $(document).on('change', '#from_sub_location_id', function(){
        //     source = $(this).val();
        // });

        // $(document).on('change', '#to_sub_location_id', function(){
        //     target = $(this).val();
        // });
        // $(document).on('change', '#edit_source', function(){
        //     edit_source = $(this).val();
        // });

        // $(document).on('change', '#edit_target', function(){
        //     edit_target = $(this).val();
        // });
	});

    $(document).on('click', '.confirm_delete', function(e){
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
        });
    });

    $(document).on('click', '.modal_trigger', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#adjust_id').val(id);
    var subs = $(this).data('subs').split('<::>');
    var item_name = $(this).data('item_name');
    var notes = $(this).data('notes');
    var subloc = $(this).data('subloc');
    var loc = $(this).data('loc');
    var edit_info = $(this).data('edit_info');


    var subsHTML = '';

    subs.forEach(sub => {
        if(sub.split('::')[0] == subloc){
            subsHTML = '<strong>'+ sub.split('::')[1] + ' - ' + sub.split('::')[2] +'</strong>';
        }
    });

    $('#subloc').val(subloc);
    $('#loc').val(loc);
    $('#ad_type').val(edit_info.split(':')[5]);



    $('#edit_sub').html(subsHTML);

    $(document).on('change', '#edit_sub_location_id', function(){
        $('#subloc').val($(this).val());
    });

    var type_sel = '';

    if(edit_info.split(':')[5] == 0){
        type_sel = '<option value="0" selected>Add</option><option value="1" >Subtract</option><option value="2" >Loss</option>';
    } else if (edit_info.split(':')[5] == 1){

        type_sel = '<option value="0">Add</option><option value="1" selected>Subtract</option><option value="2" >Loss</option>';
    } else if (edit_info.split(':')[5] == 2){
 
        type_sel = '<option value="0">Add</option><option value="1" >Subtract</option><option value="2" selected>Loss</option>';
    }

    edit_item_id = edit_info.split(':')[0] + ':' + edit_info.split(':')[1] + ':' + edit_info.split(':')[2] + ':' + edit_info.split(':')[3] + ':' + edit_info.split(':')[4] + ':' + edit_info.split(':')[5] + ':' + edit_info.split(':')[6] + ':' + edit_info.split(':')[7];

    var itemNameHTML = '<strong>' + item_name +'</strong>';

    var itemInfoHTML = '<tr><td>' + edit_info.split(':')[1] + '</td><td>' + edit_info.split(':')[3] + '</td><td style="text-align: center;">' + edit_info.split(':')[2] + '</td><td  style="width: 20%;"><select id="edit_adjust_type" name="edit_adjust_type" class="form-control edit_adjust_type">' + type_sel + '</select></td><td  style="width: 20%;"><input type="number" step="1" min="0" value="'+ edit_info.split(':')[4] +'" placeholder="?" id="edit_adjust_quant" name="edit_adjust_quant" class="form-control adjust_quant"/></td><td style="text-align: center;"><strong id="edit_sub-change">' + edit_info.split(':')[7] + '</strong></td></tr>';


    var type = edit_info.split(':')[5];
    var sub_switch = edit_info.split(':')[7];
    var quant = edit_info.split(':')[4]

    $(document).on('change', '#edit_adjust_type', function(){
        type = $(this).val();
        if (type == 0){
            sub_switch  = (Number(edit_info.split(':')[2]) + Number(quant));
        } else if (type == 1){
            sub_switch  = (Number(edit_info.split(':')[2]) - Number(quant));
        } else if (type == 2){
            sub_switch  = (Number(edit_info.split(':')[2]) - Number(quant));
        }

        $(`#edit_sub-change`).html(sub_switch);

        edit_item_id = edit_info.split(':')[0] + ':' + edit_info.split(':')[1] + ':' + edit_info.split(':')[2] + ':' + edit_info.split(':')[3] + ':' + Number(quant) + ':' + type + ':' + edit_info.split(':')[6] + ':' + sub_switch;
        
        $('#ad_type').val($(this).val());
    });


    $(document).on('input',`#edit_adjust_quant`, function(){
        quant = $(this).val();
                    
        if (type == 0){
            sub_switch  = (Number(edit_info.split(':')[2]) + Number(quant));
        } else if (type == 1){
            sub_switch  = (Number(edit_info.split(':')[2]) - Number(quant));
        } else if (type == 2){
            sub_switch  = (Number(edit_info.split(':')[2]) - Number(quant));
        }
                    
        $(`#edit_sub-change`).html(sub_switch);

        edit_item_id = edit_info.split(':')[0] + ':' + edit_info.split(':')[1] + ':' + edit_info.split(':')[2] + ':' + edit_info.split(':')[3] + ':' + Number(quant) + ':' + type + ':' + edit_info.split(':')[6] + ':' + sub_switch;
                    
    });

    

    

    $('#edit_item_input').html(itemNameHTML);

    $('#edit_item_info').html(itemInfoHTML);

    $('#edit_notes').val(notes);

    $(document).on('click', '#saveeditadjustment', function(){
                $('#edit_item_id').val(edit_item_id);
                console.log($('#edit_item_id').val());
            });

    
    });  

    $(document).on('input', '#item_input', function(){
        $.ajax({
           type: 'POST',
           url: '<?php echo base_url(); ?>inventory/Frontend/Adjustments/getDropdownInput',
           data: {item_input: $('#item_input').val(), sub_location: $('#sub_location_id').val()},
           dataType: "JSON",
           success: function (result)
           {
            console.log("Success! " + JSON.stringify(result));

            var itemDropdownHTML = '';


            itemDropdownHTML = '<select id="item_drop" class="form-control" name="item_drop" placeholder="Choose Sub-Location">';
            itemDropdownHTML += '<option value="">Choose an Item</option>';
            result.data.forEach(item => {
                itemDropdownHTML += '<option value="' + item.item_id + '">' + item.item_name + ' - ' + item.item_number + '</option>';
            });

            itemDropdownHTML += '</select>';

            $('#item_dropdown').html(itemDropdownHTML);
           },
           error: function(err) {
            console.log("Something went wrong! " + JSON.stringify(err));
           }
       });
    });

    $(document).on('change', '#item_drop', function(){
        $.ajax({
           type: 'POST',
           url: '<?php echo base_url(); ?>inventory/Frontend/Adjustments/getItemInput',
           data: {item_input: $('#item_drop').val(), sub_location: sub_location},
           dataType: "JSON",
           success: function (result)
           {
            console.log("Success! " + JSON.stringify(result));

            var itemFieldHTML = '';


            var item_id = '';

            itemFieldHTML = '<table class="table table-spraye"><thead><tr><td><strong>Item Name</strong></td><td><strong>Item #</strong></td><td><strong>Current Stock</strong></td><td><strong>Adjustment Type</strong></td><td><strong>Adjustment Quantity</strong></td><td><strong>Stock After Adjustment</strong></td></tr></thead>';
            itemFieldHTML += '<tbody>';
            
            result.data.forEach(dat => {
                var average = 0.00;
                if(dat.average != null){
                    average = dat.average;
                }
                var trans_quant = 0;
                itemFieldHTML += '<tr><td>' + dat.item_name + '</td><td>' + dat.item_number + '</td><td style="text-align: center;">' + dat.sub_quantity + '</td><td  style="width: 20%;"><select id="adjust_type' +  dat.item_id + '" name="adjust_type" class="form-control adjust_type"><option value="0">Add</option><option value="1">Subtract</option><option value="2">Loss</option></select></td><td  style="width: 20%;"><input type="number" step="1" min="0" value="0" placeholder="?" id="adjust_quant' +  dat.item_id + '" name="adjust_quant" class="form-control adjust_quant"/></td><td style="text-align: center;"><strong id="sub-change' + dat.item_id + '">' + dat.sub_quantity + '</strong></td></tr>';
                $(document).on('input',`#adjust_quant${dat.item_id}`, function(){
                    var type = 0;
                    var sub_switch = 0;
                    if ($(`#adjust_type${dat.item_id}`).val() == 0){
                        type = 0;
                        sub_switch  = (Number(dat.sub_quantity) + Number($(`#adjust_quant${dat.item_id}`).val()));
                    } else if ($(`#adjust_type${dat.item_id}`).val() == 1){
                        type = 1;
                        sub_switch  = (Number(dat.sub_quantity) - Number($(`#adjust_quant${dat.item_id}`).val()));
                    } else if ($(`#adjust_type${dat.item_id}`).val() == 2){
                        type = 2;
                        sub_switch  = (Number(dat.sub_quantity) - Number($(`#adjust_quant${dat.item_id}`).val()));
                    }
                    
                    $(`#sub-change${dat.item_id}`).html(sub_switch);

                    item_id = dat.item_id + ':' + dat.item_name + ':' + dat.sub_quantity + ':' + dat.item_number + ':' + Number($(`#adjust_quant${dat.item_id}`).val()) + ':' + type + ':' + average + ':' + sub_switch;
                    
                });

                
            });

            $(document).on('click', '#savenewadjustment', function(){
                $('#item_id').val(item_id);
                console.log($('#item_id').val());
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
