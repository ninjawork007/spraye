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
	.label-return, .bg-return {
		background-color: #fd7e14;
		border-color: #fd7e14;
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
	.table-responsive {
    overflow-x: auto;
    /* min-height: .01%; */
    min-height: 101px;
	}

</style>

<!-- Content area -->
<div class="content invoicessss">
	<div class="panel-body">
		<!-- Start of Purchases returns -->
		<div class="row">
			<div class="px-2 py-1 col">
				<div class="section variant-2">

					<!-- <div class="content">
						<div class="table-responsive">
							<table id="returns" class="table table-striped table-bordered table-hover"> -->
					<div class="content" id="receivingordertablediv">
         				<div  class="table-responsive table-spraye">
            				<table  class="table datatable-filter-custom">
								<thead>
									<tr>
										<th><input type="checkbox" id="select_all" <?php if (empty($all_receiving)) { echo 'disabled'; }  ?>    /></th>
										<th>Purchase Order #</th>
										<th>Vendor </th>
										<th>Total Units</th>
										<th>Total PO $ Amount</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
										if($all_receiving){
											foreach($all_receiving as $receiving){
									?>
									<tr>
										<td><input  name="group_id" type="checkbox"  value="<?=$receiving->purchase_receiving_id.':'.$receiving->purchase_order_number ?>" purchase_receiving_id="<?=$receiving->purchase_receiving_id ?>" class="myCheckBox" /></td>
										<td><a href="<?= base_url('inventory/Frontend/purchases/viewReceiving/').$receiving->purchase_receiving_id ?>"><?= $receiving->purchase_order_number ?></a></td>
										<td><?= $receiving->vendor_name ?></td>
										<td  width="13%" style="text-align: center;">
											<?php if ($receiving->total_units == 0){ 
												echo '<span  class="label label-success myspan">New Purchase Order</span>'; ?>
											<?php } else { ?>

												<?= $receiving->total_units ?></td>
											<?php } ?>
										<td>$<?= $receiving->grand_total ?></td>
										<td class="table-action">
											<ul style="list-style-type: none; padding-left: 0px;">
												<li style="display: inline; padding-right: 10px;">
                                                <a href="<?= base_url('inventory/Frontend/purchases/viewReceiving//').$receiving->purchase_receiving_id ?>" target="_blank"><button class="btn btn-success">Receive Items</button></a>
												</li>
											</ul>
										</td>
									</tr>
								<?php
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End of Purchases returns -->
	</div>
</div>
<script type="text/javascript">
	function  filterSearch(status) {
     
	 // alert(status);
	 $.ajax({
	 type: "GET",
	 url: "<?= base_url('inventory/Frontend/Purchases/getAllReturnOrdersBySearch/')?>"+status,
	 }).done(function(data){
	 $('#receivingordertablediv').html(data);
	 
	 $('#allPurchase').prop('disabled', true);
	 $('#allPrint').prop('disabled', true);
 
	 buildDataTable();    
	 });
 
 	}

	buildDataTable();

	function buildDataTable(argument) {
		$('.datatable-filter-custom').DataTable({
			

			language: {
				search: '<span></span> _INPUT_',
				lengthMenu: '<span>Show:</span> _MENU_',
				paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
			},
		

			dom: 'l<"toolbar">frtip',
			initComplete: function(){
	
				$("div.toolbar")
				.html('<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>');      
			}       
		});

	}

	$(document).on("change","#select_all", function () {
   
   // $("#select_all").change(function(){  //"select all" change 
	   var status = this.checked; // "select all" checked status
	   if (status) {
	   $('#allPrint').prop('disabled', false);
   
	   } else {
	   $('#allPrint').prop('disabled', true);
	   
	   }
   
	   $('input:checkbox').not(this).prop('checked', this.checked);
   
	   // $(document).on("each",'.myCheckBox',function(){ //iterate all listed checkbox items
	   //     this.checked = status; //change ".checkbox" checked status
   
	   // });
   });

   $(document).on("change",".myCheckBox", function () {
  
	   // checkBoxes.change(function () {
	   // alert(checkBoxes);
	   if($('.myCheckBox').filter(':checked').length < 1) {
	   //  alert("if");
	   $('#allPrint').prop('disabled', true);
	   }
	   else {
	   
	   $('#allPrint').prop('disabled', false);
   
	   //  alert('else');
	   }
   
	   if(this.checked == false){ //if this item is unchecked
		   $("#select_all")[0].checked = false; //change "select all" checked status to false
	   }
	   
	   //check "select all" if all checkbox items are checked
	   if ($('.myCheckBox:checked').length == $('.myCheckBox').length ){ 
		   $("#select_all")[0].checked = true; //change "select all" checked status to true
	   }
   
	   // $('#allPurchase').prop('disabled', checkBoxes.filter(':checked').length < 1);
	   // $('#allPrint').prop('disabled', checkBoxes.filter(':checked').length < 1);
   });

   $(document).on("click","#allPrint", function () {
   
		var purchase_receiving_ids = $("input:checkbox[name=group_id]:checked").map(function(){
			return $(this).attr('purchase_receiving_id');
		}).get(); // <----

		var href ="<?= base_url('inventory/Frontend/Purchases/printPurchaseOrderReceiving/') ?>"+purchase_receiving_ids;

		var win = window.open(href, '_blank');
		win.focus();

	});
	
var currency = "";
var openReturn = {};
var table = {};

(function($) {
	'use strict';

	$('document').ready(function() {
		$('.main-loader').fadeOut(100)

		// Link table to the loader
		$('table#returns').on('processing.dt', (e, settings, processing) => {
			if(processing)
				$('.main-loader').fadeIn(100)
			else
				$('.main-loader').fadeOut(100)
		})

		// Load table
		table = $('table#returns').DataTable({
			serverSide: true,
			ajax: "<?= base_url('api/purchases/returns') ?>",
			columns: [
				{ data: "reference" },
				{ data: "purchase_reference" },
				{ data: "warehouse_name" },
				{ data: "created_at" },
				{ data: "supplier_name" },
				{
					data: "grand_total",
					render: (data, type) => {
						let finalData = data

						if(type == 'display')
							finalData = `${currency} ${data}`

						return finalData
					}
				}
			]
		})

		$('table#returns tbody').on('click', 'tr', function() {
			let id = table.row(this).data().DT_RowId
			loadReturn(id)
		})

		$('#returnModal').on('hide.bs.modal', e => {
			window.history.pushState(null, '', `<?= base_url() ?>/purchases/returns`)
		})

		
	})
})(jQuery)

function loadReturn(id) {
	axios.get(`api/purchases/returns/${id}`).then(response => {
		let purchaseReturn = response.data

		openReturn = purchaseReturn

		window.history.pushState(null, '', `<?= base_url() ?>/purchases/returns/${id}`)

		$('#returnModal').modal('show')

		$('#returnModal table#returnInformation td[data-item-field="created_at"]').text(purchaseReturn.created_at)
		$('#returnModal table#returnInformation td[data-item-field="reference"]').text(purchaseReturn.reference)
		$('#returnModal table#returnInformation td[data-item-field="purchase_reference"]').text(purchaseReturn.purchase.reference)
		$('#returnModal table#returnInformation td[data-item-field="warehouse"]').text(purchaseReturn.warehouse.name)

		$('#returnModal table#supplierInformation td[data-item-field="id"]').text(purchaseReturn.supplier.id)
		$('#returnModal table#supplierInformation td[data-item-field="name"]').text(purchaseReturn.supplier.name)
		$('#returnModal table#supplierInformation td[data-item-field="address"]').text(purchaseReturn.supplier.address)
		$('#returnModal table#supplierInformation td[data-item-field="city"]').text(purchaseReturn.supplier.city)
		$('#returnModal table#supplierInformation td[data-item-field="state"]').text(purchaseReturn.supplier.state)
		$('#returnModal table#supplierInformation td[data-item-field="zip_code"]').text(purchaseReturn.supplier.zip_code)
		$('#returnModal table#supplierInformation td[data-item-field="country"]').text(purchaseReturn.supplier.country)

		$('#returnModal table#items tbody').html('')

		purchaseReturn.items.forEach(item => {
			let unit_price = Utils.getFloat(item.unit_price)
			let quantity = Utils.getInt(item.qty_to_return)
			let tax = Utils.getFloat(item.tax)

			let subtotal = quantity * unit_price
			let total = Utils.applyTax(subtotal, tax)

			let elem = '<tr>'
				+ `<td>`
				+ `<strong>${item.name}</strong><br />${item.code}`
				+ `</td>`
				+ `<td>${currency} ${Utils.twoDecimals(unit_price)}</td>`
				+ `<td>${quantity}</td>`
				+ `<td>${currency} ${Utils.twoDecimals(subtotal)}</td>`
				+ `<td>${Utils.twoDecimals(tax)}%</td>`
				+ `<td>${currency} ${Utils.twoDecimals(total)}</td>`
				+ '</tr>'

			$('#returnModal table#items tbody').append(elem)
		})

		$('#returnModal #notes').html(purchaseReturn.notes)

		$('#returnModal table#summary td[data-summary-field="subtotal"]').html(`${currency} ${Utils.twoDecimals(purchaseReturn.subtotal)}`)
		$('#returnModal table#summary td[data-summary-field="discount"]').html(`${currency} ${Utils.twoDecimals(purchaseReturn.discount)}`)
		$('#returnModal table#summary td[data-summary-field="shipping"]').html(`${currency} ${Utils.twoDecimals(purchaseReturn.shipping_cost)}`)
		$('#returnModal table#summary td[data-summary-field="tax"]').html(`${Utils.twoDecimals(purchaseReturn.tax)}%`)
		$('#returnModal table#summary td[data-summary-field="total"]').html(`${currency} ${Utils.twoDecimals(purchaseReturn.grand_total)}`)
	})
}
</script>