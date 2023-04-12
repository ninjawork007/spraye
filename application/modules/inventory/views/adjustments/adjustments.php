<?php
// $this->load->view('templates/master');
// <?= $this->section('content') 
// $this->load->view('adjustments/modals/adjustment_modal');
// $this->load->view('components/error_modal');
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
        <table class="table" id="adjustments">
          <thead>
            <tr>
				<th>Sub Location</th>
				<th>Created By</th>
				<th>Created at</th>
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
	
var openAdjustment = {};
var table = {};

// (function($) {
// 	'use strict';

	$('document').ready(function() {
		// $('.main-loader').fadeOut(100)

		// // Link table to the loader
		// $('table#adjustments').on('processing.dt', (e, settings, processing) => {
		// 	if(processing)
		// 		$('.main-loader').fadeIn(100)
		// 	else
		// 		$('.main-loader').fadeOut(100)
		// })

		// Load table
		// table = $('table#adjustments').DataTable({
		// 	serverSide: true,
		// 	ajax: "<?= base_url('api/adjustments') ?>",
		// 	columns: [
		// 		{ data: "warehouse_name" },
		// 		{ data: "created_by" },
		// 		{ data: "created_at" }
		// 	],
		// 	order: [[2, 'desc']]
		// })

		var table =  $('#adjustments').DataTable({
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
		            {"data": "sub_location_name", "name":"Name", "searchable":true, "orderable": true },
			   	    {"data": "created_by", "name":"Street", "searchable":true, "orderable": true },
			   	    {"data": "created_at", "name":"City", "searchable":true, "orderable": true },
			   	    {"data": "actions", "name":"Actions","class":"table-action", "searchable":false, "orderable":false}
		       ],
		   language: {
              search: '<span></span> _INPUT_',
              lengthMenu: '<span>Show:</span> _MENU_',
              paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
          },
		   dom: '<"toolbar-1">frtip',
		    buttons:[
				{
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon',
				columns: [0,1,2,3],


            },
				],
		   initComplete: function(){

				$("div.toolbar-1")
					.html('<button type="button" class="btn btn-success">Export CSV</button><a href="" data-toggle="modal" data-target="#new_location"><button type="button"  class="btn btn-primary" id="newlocationsbtn">New Adjustment</button></a>');
			},

		});

		// $('table#adjustments tbody').on('click', 'tr', function() {
		// 	let id = table.row(this).data().DT_RowId
		// 	loadAdjustment(id)
		// })

		// $('#adjustmentModal').on('hide.bs.modal', e => {
		// 	window.history.pushState(null, '', `<?= base_url() ?>/adjustments`)
		// })

		// <?php 
		// if(
		// 	// $adjustmentId != false
		// 	) { 
		// 	?>
		// loadAdjustment(
			
			// 	// $adjustmentId 
			// 	
				// )
		<?php
	//  } 
	 ?>
	})
// })(jQuery)

// function loadAdjustment(id) {
// 	axios.get(`api/adjustments/${id}`).then(response => {
// 		let adjustment = response.data

// 		openAdjustment = adjustment

// 		window.history.pushState(null, '', `<?= base_url() ?>/adjustments/${id}`)

// 		$('#adjustmentModal').modal('show')

// 		$('#adjustmentModal table#adjustmentInformation td[data-item-field="id"]').text(adjustment.id)
// 		$('#adjustmentModal table#adjustmentInformation td[data-item-field="warehouse_id"]').text(adjustment.warehouse.id)
// 		$('#adjustmentModal table#adjustmentInformation td[data-item-field="warehouse_name"]').text(adjustment.warehouse.name)
// 		$('#adjustmentModal table#adjustmentInformation td[data-item-field="created_by"]').text(adjustment.created_by.name)
// 		$('#adjustmentModal table#adjustmentInformation td[data-item-field="created_at"]').text(adjustment.created_at)

// 		$('#adjustmentModal table#items tbody').html('')

// 		adjustment.items.forEach(item => {
// 			let originalQuantity = Utils.getInt(item.quantity)
// 			let adjustmentQuantity = Utils.getInt(item.adjustment_quantity)

// 			let quantityAfterAdjustment = originalQuantity
// 			if(item.adjustment_type == 'add')
// 				quantityAfterAdjustment += adjustmentQuantity
// 			else
// 				quantityAfterAdjustment -= adjustmentQuantity

// 			let adjustmentType = ""
// 			if(item.adjustment_type == 'add')
// 				adjustmentType = "<?= 'adjustments.add' ?>"
// 			else
// 				adjustmentType = "<?= 'adjustments.subtract' ?>"

// 			let elem = '<tr>'
// 				+ '<td>'
// 				+ `<strong>${item.name}</strong><br />${item.code}`
// 				+ '</td>'
// 				+ `<td>${item.quantity}</td>`
// 				+ `<td>${adjustmentType}</td>`
// 				+ `<td>${item.adjustment_quantity}</td>`
// 				+ `<td>${quantityAfterAdjustment}</td>`

// 			$('#adjustmentModal table#items tbody').append(elem)
// 		})

// 		$('#adjustmentModal #notes').html(adjustment.notes)
// 	})
// }
</script>