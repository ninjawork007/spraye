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

<script type="text/javascript">
'use strict';
	
var openTransfer = {};
var table = {};

let arrowRight = `<i class="fas fa-long-arrow-alt-right"></i>`;

// (function($) {
// 	'use strict';

	$('document').ready(function() {
		// $('.main-loader').fadeOut(100)

		// Link table to the loader
		// $('table#transfers').on('processing.dt', (e, settings, processing) => {
		// 	if(processing)
		// 		$('.main-loader').fadeIn(100)
		// 	else
		// 		$('.main-loader').fadeOut(100)
		// })

		// Load table
		// table = $('table#transfers').DataTable({
		// 	serverSide: true,
		// 	ajax: "<?= base_url('api/transfers') ?>",
		// 	columns: [
		// 		{ data: "from_warehouse_name" },
		// 		{ data: "to_warehouse_name" },
		// 		{ data: "created_by" },
		// 		{ data: "created_at" }
		// 	],
		// 	order: [[2, 'desc']]
		// })

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
					.html('<button type="button" class="btn btn-success">Export CSV</button><a href="" data-toggle="modal" data-target="#new_transfer"><button type="button"  class="btn btn-primary" id="newtransfersbtn">New Transfer</button></a>');
			},

		});

		$('table#transfertable tbody').on('click', 'tr', function() {
			// let id = table.row(this).data().DT_RowId
			var id = $(this).data('id');
			// alert('clicked');
			// console.log(id);
			// loadTransfer(id)
		})

		$('#transferModal').on('hide.bs.modal', e => {
			window.history.pushState(null, '', `<?= base_url() ?>/transfers`)
		})

		<?php 
		// if($transferId != false) { 
			?>
		// loadTransfer()
		<?php
		//  } 
		 ?>
	})
// })(jQuery)

// function loadTransfer(id) {
// 	axios.get(`api/transfers/${id}`).then(response => {
// 		let transfer = response.data

// 		openTransfer = transfer

// 		window.history.pushState(null, '', `<?= base_url() ?>/transfers/${id}`)

// 		$('#transferModal').modal('show')

// 		$('#transferModal table#transferInformation[data-item-field="id"]').text(transfer.id)
// 		$('#transferModal table#transferInformation td[data-item-field="from_warehouse_id"]').text(transfer.from_warehouse.id)
// 		$('#transferModal table#transferInformation td[data-item-field="from_warehouse_name"]').text(transfer.from_warehouse.name)
// 		$('#transferModal table#transferInformation td[data-item-field="to_warehouse_id"]').text(transfer.to_warehouse.id)
// 		$('#transferModal table#transferInformation td[data-item-field="to_warehouse_name"]').text(transfer.to_warehouse.name)
// 		$('#transferModal table#transferInformation td[data-item-field="created_by"]').text(transfer.created_by.name)
// 		$('#transferModal table#transferInformation td[data-item-field="created_at"]').text(transfer.created_at)

// 		$('#transferModal table#items tbody').html('')

// 		transfer.items.forEach(item => {
// 			let originalFromQuantity = Utils.getInt(item.original_from_quantity)
// 			let originalToQuantity = Utils.getInt(item.original_to_quantity)
// 			let transferQuantity = Utils.getInt(item.transfer_quantity)
// 			let stockAfterTransferFrom = originalFromQuantity - transferQuantity
// 			let stockAfterTransferTo = originalToQuantity + transferQuantity

// 			/*
// 			<th><?= 'item_name' ?></th>
// 			<th><?= 'transfer_quantity' ?></th>
// 			<th><?= 'source_warehouse_change' ?></th>
// 			<th><?= 'target_warehouse_change' ?></th>
// 			 */

// 			/*
// 			let td4 = `${item.quantities.from.quantity} ${arrowRight} ${item.quantities.from.quantity}`
// 			let td5 = `${item.quantities.to.quantity} ${arrowRight} ${item.quantities.to.quantity}`
// 			 */

// 			let elem = '<tr>'
// 				+ '<td>'
// 				+ `<strong>${item.name}</strong><br />${item.code}`
// 				+ '</td>'
// 				+ `<td>${transferQuantity}</td>`
// 				+ `<td>${originalFromQuantity} ${arrowRight} ${stockAfterTransferFrom}</td>`
// 				+ `<td>${originalToQuantity} ${arrowRight} ${stockAfterTransferTo}</td>`

// 			$('#transferModal table#items tbody').append(elem)
// 		})

// 		$('#transferModal #notes').html(transfer.notes)
// 	})
// }

$(document).on('click', '.modal_trigger_transfer', function(e){
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

</script>