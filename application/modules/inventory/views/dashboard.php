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
	.table-responsive {
    overflow-x: auto;
    /* min-height: .01%; */
    min-height: 101px;
	}
    .panel_row .panel {
   margin-bottom: 20px !important;
   border-radius: 5px !important;
   box-shadow: 1px 1px 1px 1px #cccccc8a;
   }
   .panel_row .text-size-small {
   font-size: 17px;
   }

</style>

<div class="content invoicessss">
    <div class="panel-body">
        <!-- Stat cards -->
        <div class="row panel_row"  id="initStats">
            <div class="col-lg-3">
                <!-- Current server load -->
                    <div class="panel">
                        <div class="panel-body">
                            <h5 class="no-margin" >Value of Units on Hand</h5>
                            <div class="text-muted text-size-small text-warning">$<?= number_format($value_on_hand,2) ?></div>
                        </div>
                    </div>
                <!-- /current server load -->
            </div>
            <div class="col-lg-3">
                <!-- Current server load -->
                    <div class="panel">
                        <div class="panel-body">
                            <h5 class="no-margin" ># of Unpaid Purchase Orders</h5>
                            <div class="text-muted text-size-small text-warning"><?= number_format($unpaid_ct) ?></div>
                        </div>
                    </div>
                <!-- /current server load -->
            </div>
            <div class="col-lg-3">
                <!-- Current server load -->
                    <div class="panel">
                        <div class="panel-body">
                            <h5 class="no-margin text-warning" >Unpaid Purchase Order Value</h5>
                            <div class="text-muted text-size-small text-warning">$ <?= number_format($unpaid, 2) ?></div>
                        </div>
                    </div>
                <!-- /current server load -->
            </div>
            <div class="col-lg-3">
                <!-- Current server load -->
                    <div class="panel">
                        <div class="panel-body">
                            <h5 class="no-margin " >Open Purchase Order Value</h5>
                            <div class="text-muted text-size-small text-warning">$ <?= number_format($open_value, 2) ?></div>
                        </div>
                    </div>
                <!-- /current server load -->
            </div>
        </div>
        <div class="row">
            <!-- Latest sales -->
            <div class="content invoicessss">
                <div class="panel-body">
                    <div id="itemtablediv">
                        <div class="px-2 py-1 col">
                            <div class="section variant-2">
                                <div class="header d-flex align-items-center justify-content-between">
                                    <div class="title">
                                        Items
                                    </div>
                                </div>
                        <!-- Content area -->
                                <div  class="table-responsive table-spraye">
                                    <table class="table" id="items">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Item #</th>
                                                <th>Item Description</th>
                                                <th>Item Type</th>
                                                <th>Unit Definition</th>
                                                <th>Products Associated</th>
                                                <th># of Units on Hand</th>
                                                <th>Average Cost Per Unit</th>
                                                <th>$ of Units on Hand</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>     
                    </div>
                    <input type="hidden" id="item_prods"/>
                    <input type="hidden" id="product_arr" name="product_arr" value="<?php echo $products_str; ?>"/>

                </div>
            </div>
        </div>
		<!-- Latest purchases -->
		<div class="row">
            <div class="content invoicessss">
                <div class="panel-body">
                    <div class="px-2 py-1 col">
                        <div class="section variant-2">
                            <div class="header d-flex align-items-center justify-content-between">
                                <div class="title">
                                    Purchase Orders
                                </div>
                            </div>
                            <!-- Start of Purchases -->
                            <div class="content" id="purchaseordertablediv">
                                <div  class="table-responsive table-spraye">
                                    <table  class="table datatable-filter-custom">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select_all" <?php if (empty($all_purchases)) { echo 'disabled'; }  ?>    /></th>
                                                <th>Purchase Order #</th>
                                                <th>Purchase Order Date</th>
                                                <th>Sent Status</th>
                                                <th>PO Status</th>
                                                <th>Sent Date</th>
                                                <th>Open Date</th>
                                                <th>Paid Status</th>
                                                <th>Estimated Delivery Date</th>
                                                <th>Location</th>
                                                <th>Sub Location</th>
                                                <th>Vendor</th>
                                                <th>Item #</th>
                                                <th>Total PO $</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if($all_purchases){
                                                    foreach($all_purchases as $purchase){
                                            ?>
                                            <tr>
                                                <td><input  name="group_id" type="checkbox"  value="<?=$purchase->purchase_order_id.':'.$purchase->purchase_order_number ?>" purchase_order_id="<?=$purchase->purchase_order_id ?>" class="myCheckBox" /></td>
                                                <td><a href="<?= base_url('inventory/Frontend/purchases/viewOrder/').$purchase->purchase_order_id ?>"><?= $purchase->purchase_order_number; ?></a></td> 
                                                <td><?= date('m-d-Y', strtotime($purchase->purchase_order_date)) ?></td>
                                                <td width="13%">
                                                <div class="dropdown">
											<?php switch ($purchase->purchase_sent_status) {
											case 0:
                                                echo '<button class="btn btn-default dropdown-toggle label-warning statusCol" type="button" data-toggle="dropdown">Draft
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-warning';
                                                break;
                                             case 1:
                                                echo '<button class="btn btn-default dropdown-toggle label-danger statusCol" type="button" data-toggle="dropdown">Sent
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-danger';
                                                break;
                                             
                                             case 2:
                                                echo '<button class="btn btn-default dropdown-toggle label-till statusCol" type="button" data-toggle="dropdown">Opened
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-till';
                                                break;
                                                
                                             } ?>
                                                <ul class="dropdown-menu dropdown-menu-right" >
                                                   <li class="changestatusSent"  purchase_order_id="<?= $purchase->purchase_order_id ?>" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Draft</a></li>
                              
                                                   <li class="changestatusSent" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="1" ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>
                              
                                                   <li class="changestatusSent" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="2" ><a href="#"><span class="status-mark bg-till position-left"></span> Opened</a></li>
                                                </ul>
                                            </div>
                                                </td>
                                                <td width="13%">
                                                <div class="dropdown">
                                            <?php switch ($purchase->purchase_order_status) {
                                            case 0:
                                                echo '<button class="btn btn-default dropdown-toggle label-warning statusCol" type="button" data-toggle="dropdown">Pending Vendor Approval
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-warning';
                                                break;
                                            case 1:
                                                echo '<button class="btn btn-default dropdown-toggle label-till statusCol" type="button" data-toggle="dropdown">Approved By Vendor
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-till';
                                                break;
                                            case 2:
                                                echo '<button class="btn btn-default dropdown-toggle label-danger statusCol" type="button" data-toggle="dropdown">Partial Received
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-danger';
                                                break;
                                            case 3:
                                                echo '<button class="btn btn-default dropdown-toggle label-success statusCol" type="button" data-toggle="dropdown">Received
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-success';
                                                break;
                                            case 4:
                                                echo '<button class="btn btn-default dropdown-toggle label-return statusCol" type="button" data-toggle="dropdown">Returned
                                                <span class="caret"></span></button>';
                                                $bg= 'bg-return';
                                                break;
                                            } ?>
                                            <ul class="dropdown-menu dropdown-menu-right" >
                                                <li class="changestatusPO"  purchase_order_id="<?= $purchase->purchase_order_id ?>" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Pending Vendor Approval</a></li>
                                                <li class="changestatusPO" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="1" ><a href="#"><span class="status-mark bg-till position-left"></span> Approved By Vendor</a></li>
                                                <li class="changestatusPO" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="2" ><a href="#"><span class="status-mark bg-danger position-left"></span> Partial Received</a></li>
                                                <li class="changestatusPO" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="3" ><a href="#"><span class="status-mark bg-success position-left"></span> Received</a></li>
                                                <li class="changestatusPO" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="4" ><a href="#"><span class="status-mark bg-return position-left"></span> Returned</a></li>
                                            </ul>
                                        </div>
                                                </td>
                                                <td><?= $purchase->sent_date != 0000-00-00  ? date('m-d-Y', strtotime($purchase->sent_date)) : '' ?></td>
                                                <td><?= $purchase->open_date != 0000-00-00  ? date('m-d-Y', strtotime($purchase->open_date)) : '' ?></td>
                                                <td width="13%">
                                                <div class="dropdown">
											<?php switch ($purchase->purchase_paid_status) {
											case 0:
												echo '<button class="btn btn-default dropdown-toggle label-warning statusCol" type="button" data-toggle="dropdown">Open
                                                <span class="caret"></span></button>';
												$bg= 'bg-warning';
												break;
											case 1:
												echo '<button class="btn btn-default dropdown-toggle label-till statusCol" type="button" data-toggle="dropdown">Ready For Payment
                                                <span class="caret"></span></button>';
												$bg= 'bg-till';
												break;
											
											case 2:
												echo '<button class="btn btn-default dropdown-toggle label-success statusCol" type="button" data-toggle="dropdown">Paid
                                                <span class="caret"></span></button>';
												$bg= 'bg-success';
												break;

											case 3:
												echo '<button class="btn btn-default dropdown-toggle label-danger statusCol" type="button" data-toggle="dropdown">Unmatched
                                                <span class="caret"></span></button>';
												$bg= 'bg-danger';
												break;

												
											} ?>
											<ul class="dropdown-menu dropdown-menu-right" >
												<li class="changestatusPaid"  purchase_order_id="<?= $purchase->purchase_order_id ?>" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Open</a></li>
												<li class="changestatusPaid" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="1" ><a href="#"><span class="status-mark bg-till position-left"></span> Ready For Payment</a></li>

												<li class="changestatusPaid" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="2" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>
												
												<li class="changestatusPaid" purchase_order_id="<?= $purchase->purchase_order_id ?>" value="3" ><a href="#"><span class="status-mark bg-danger position-left"></span> Unmatched</a></li>
					
											</ul>
										</div>
                                                </td>
                                                <td><?= $purchase->estimated_delivery_date != 0000-00-00  ? date('m-d-Y', strtotime($purchase->estimated_delivery_date)) : '' ?></td>
                                                <td><?= $purchase->location_name ?></td>
                                                <td><?= $purchase->sub_location_name ?></td>
                                                <td><?= $purchase->vendor_name ?></td>
                                                    <?php 
                                                        $items = json_decode($purchase->items, true);
                                                        // die(print_r($items));
                                                        if (sizeof($items) > 1) {
                                                    ?>
                                                <td>Multiple</td>
                                                    <?php   } else {    ?>
                                                <td ><?= $items[0]['name'] .'<br>'. $items[0]['item_number'] ?></td>
                                                    <?php   }   ?>
                                                <td>$<?= $purchase->grand_total?></td>
                                                <td class="table-action">
                                                    <ul style="list-style-type: none; padding-left: 0px;">

                                                    <li style="display: inline; padding-right: 10px;">
                                                        <a  class="email button-next" id="<?= $purchase->purchase_order_id ?>"  purchase_order_number="<?= $purchase->purchase_order_number ?>"    ><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a>
                                                    </li>
                                                    <li style="display: inline; padding-right: 10px;">
                                                        <a href="<?= base_url('inventory/Frontend/purchases/pdfPurchaseOrder/').$purchase->purchase_order_id ?>" target="_blank" class=" button-next"><i class=" icon-file-pdf position-center" style="color: #9a9797;"></i></a>
                                                    </li>
                                                    <li style="display: inline; padding-right: 10px;">
                                                        <a href="<?= base_url('inventory/Frontend/purchases/printPurchaseOrder/').$purchase->purchase_order_id ?>" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a>
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
                                        
                            <!-- End of Purchases -->
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">


$('document').ready(function() {
		
		// Load table
		var table =  $('#purchase_orders').DataTable({
		   "processing": true,
		   "serverSide": true,
		   "paging":true,
		    "pageLength":100,
			"order":[[0,'asc']],
		   "ajax":{
		     "url": "<?= base_url('inventory/Frontend/Purchases/ajaxGetPurchases')?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		   },
		   	 "deferRender":false,

		   "select":"multi",
		   "columns": [
		            {"data": "purchase_order_number", "name":"Purchase Order #", "searchable":true, "orderable": true },
					{"data": "location_id", "name":"Location", "searchable":true, "orderable": true },
			   	    {"data": "created_at", "name":"Created At", "searchable":true, "orderable": true },
			   	    {"data": "vendor_id", "name":"Vendor", "searchable":true, "orderable": true },
			   	    {"data": "purchase_order_total", "name":"Grand Total", "searchable":true, "orderable": true },
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
				columns: [0,1,2,3,4],


            },
				],
		   initComplete: function(){

				// $("div.toolbar-1")
				// 	.html('<button type="button" class="btn btn-success">Export CSV</button><a href="" data-toggle="modal" data-target="#new_purchase_order"><button type="button"  class="btn btn-primary" id="newpurchaseorderbtn">New Purchase Order</button></a>');
			},

		});

		$('table#purchases tbody').on('click', 'tr', function() {
			let id = table.row(this).data().DT_RowId
			loadPurchase(id)
		})

		$('#purchaseModal').on('hide.bs.modal', e => {
			window.history.pushState(null, '', `<?= base_url() ?>/users`)
		})

		// loadUnpaidStats()
	});

    function  filterSearch(status) {
     
     // alert(status);
     $.ajax({
     type: "GET",
     url: "<?= base_url('inventory/Frontend/Purchases/getAllPurchaseOrdersBySearch/')?>"+status,
     }).done(function(data){
     $('#purchaseordertablediv').html(data);
     
     $('#allPurchase').prop('disabled', true);
     $('#allPrint').prop('disabled', true);
 
     buildDataTable();    
     });
    }

    function  filterPO(status) {
     
     // alert(status);
     $.ajax({
     type: "GET",
     url: "<?= base_url('inventory/Frontend/Purchases/getAllPurchaseOrdersByPO/')?>"+status,
     }).done(function(data){
     $('#purchaseordertablediv').html(data);
     
     $('#allPurchase').prop('disabled', true);
     $('#allPrint').prop('disabled', true);
 
     buildDataTable();    
     });
 
 }
 
 function  filterPayment(status) {
      
     // alert(status);
     $.ajax({
     type: "GET",
     url: "<?= base_url('inventory/Frontend/Purchases/getAllPurchaseOrdersByPayment/')?>"+status,
     }).done(function(data){
     $('#purchaseordertablediv').html(data);
     
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
      
 
          dom: 'l<"toolbar-1">frtip',
          initComplete: function(){
   
         $("div.toolbar-1")
            .html('<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Sent <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-status" onclick="filterSearch(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-status" onclick="filterSearch(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Draft </a></li><li class="filter-status" onclick="filterSearch(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li class="filter-status" onclick="filterSearch(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Opened</a></li></ul></div>&nbsp;&nbsp;<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter PO Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-payment" onclick="filterPO(5)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-payment" onclick="filterPO(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Pending Vendor Approval </a></li><li class="filter-payment" onclick="filterPO(1)"  ><a href="#"><span class="status-mark bg-till position-left"></span> Approved By Vendor </a></li>  <li class="filter-payment" onclick="filterPO(2)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Partial Status</a></li> <li class="filter-payment" onclick="filterPO(3)"  ><a href="#"><span class="status-mark bg-success position-left"></span> Received </a></li> <li class="filter-payment" onclick="filterPO(4)"  ><a href="#"><span class="status-mark bg-return position-left"></span> Returned </a></li> </li> </ul></div>&nbsp;&nbsp;<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Paid Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li class="filter-payment" onclick="filterPayment(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li class="filter-payment" onclick="filterPayment(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Open </a></li><li class="filter-payment" onclick="filterPayment(1)"  ><a href="#"><span class="status-mark bg-till position-left"></span> Ready For Payment </a></li><li class="filter-payment" onclick="filterPayment(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid </a></li>  <li class="filter-payment" onclick="filterPayment(3)" ><a href="#"><span class="status-mark bg-danger position-left"></span> Unmatched </a></li>  </ul></div><button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allPurchase">Send By Email</button>&nbsp;&nbsp;<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>');      
      }       
   });
 
 }
 
     $(document).on("click",".changestatusSent", function () {
 
         var purchase_order_id = $(this).attr('purchase_order_id');
         var status = $(this).val();
         console.log(status);
         $("#loading").css("display","block");  
         $.ajax({
             type: 'POST',
             url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/changeStatusSent',
             data: {purchase_order_id: purchase_order_id, status: status},
             success: function (data) {
             $("#loading").css("display","none");
             location.reload();      
             }
         });
 
     });
     $(document).on("click",".changestatusPO", function () {
 
         var purchase_order_id = $(this).attr('purchase_order_id');
         var status = $(this).val();
         $("#loading").css("display","block");  
         $.ajax({
             type: 'POST',
             url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/changeStatusPO',
             data: {purchase_order_id: purchase_order_id, status: status},
             success: function (data) {
             $("#loading").css("display","none");
             location.reload();      
             }
         });
 
     });
     $(document).on("click",".changestatusPaid", function () {
 
         var purchase_order_id = $(this).attr('purchase_order_id');
         var status = $(this).val();
         $("#loading").css("display","block");  
         $.ajax({
             type: 'POST',
             url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/changeStatusPaid',
             data: {purchase_order_id: purchase_order_id, status: status},
             success: function (data) {
             $("#loading").css("display","none");
             location.reload();      
             }
         });
 
     });
 
     $(document).on("click",".email", function () {
    
         var purchase_order_id = $(this).attr('id');
         var purchase_order_number = $(this).attr('purchase_order_number');
         
         swal.mixin({
         input: 'text',
         confirmButtonText: 'Send',
         showCancelButton: true,
         progressSteps: 1
         }).queue([
             {
             title: 'Additional Purchase Order Message',
             text: 'Type a message to the vendor below to be included with the purchase order. Then click "Send" to email the purchase order to the vendor.'
             },
         ]).then((result) => {
             if (result.value) {
             var message  = result.value;
 
                 $("#loading").css("display","block");
 
                     $.ajax({
                     type: 'POST',
                     url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/sendPdfMail',
                     data: {purchase_order_id: purchase_order_id, purchase_order_number: purchase_order_number,message : message},
                     success: function (data)
                     {
 
                     $("#loading").css("display","none");
                         swal(
                             'Purchase Order !',
                             'Sent Successfully ',
                             'success'
                             ).then(function() {
                                 location.reload();
                             });
                             
                     }
                 });
             }
         })
     });
 
     $(document).on("change","#select_all", function () {
    
         // $("#select_all").change(function(){  //"select all" change 
         var status = this.checked; // "select all" checked status
         if (status) {
         $('#allPurchase').prop('disabled', false);
         $('#allPrint').prop('disabled', false);
         $('#bulk_status_change').prop('disabled', false);
         $('#deletebutton').prop('disabled', false);
     
         }
         else
         {
         $('#allPurchase').prop('disabled', true);
         $('#allPrint').prop('disabled', true);
         $('#bulk_status_change').prop('disabled', true);
         $('#deletebutton').prop('disabled', true);
         
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
         $('#allPurchase').prop('disabled', true);
         $('#allPrint').prop('disabled', true);
         $('#deletebutton').prop('disabled', true);
         $('#bulk_status_change').prop('disabled', true);
         }
         else {
         $('#allPurchase').prop('disabled', false);
         $('#allPrint').prop('disabled', false);
         $('#deletebutton').prop('disabled', false);
         $('#bulk_status_change').prop('disabled', false);
     
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
 
     $(document).on("click","#allPurchase", function () {
         swal.mixin({
             input: 'text',
             confirmButtonText: 'Send',
             showCancelButton: true,
             progressSteps: 1
         }).queue([
             {
             title: 'Purchase Message',
             text: 'Type a message to the Vendor below to be included with the purchase order. Then click "Send" to email the purchase order to the vendor.'
             },
         ]).then((result) => {
             
             if (result.value) {
                 var message  = result.value;
                 
                 var group_id_array = $("input:checkbox[name=group_id]:checked").map(function(){
                     return $(this).val();
                 }).get(); // <----
 
                 $("#loading").css("display","block");
                 $.ajax({
                     type: 'POST',
                     url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/sendPdfMailToSelected',
                     data: {group_id_array,message : message},
                     success: function (data)
                     {
 
                     $("#loading").css("display","none");
                         swal(
                             'Purchase Order !',
                             'Sent Successfully ',
                             'success'
                             ).then(function() {
                             location.reload();
                             });
                         
                     }
                 });
             }
         })
     });
 
     $(document).on("click","#allPrint", function () {
    
      
         var purchase_order_ids = $("input:checkbox[name=group_id]:checked").map(function(){
             return $(this).attr('purchase_order_id');
         }).get(); // <----
         
         var href ="<?= base_url('inventory/Frontend/Purchases/printPurchaseOrder/') ?>"+purchase_order_ids;
 
         var win = window.open(href, '_blank');
             win.focus();
 
     });
 
     function bulkStatusChange(status) {
 
     var purchase_order_ids = $("input:checkbox[name=group_id]:checked").map(function(){
             return $(this).attr('purchase_order_id');
         }).get(); //
 
       $("#loading").css("display","block");
        $.ajax({
                     type: 'POST',
                     url: '<?php echo base_url(); ?>inventory/Frontend/Purchases/bulkChangeStatus',
                     data: {purchase_order_ids: purchase_order_ids, status: status},
                     success: function (data){
                         $("#loading").css("display","none");
                     
                         location.reload();
                     }
         });
     }
 
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
                 $("input:checkbox[name=group_id]:checked").each(function(){
                     selectcheckbox.push($(this).attr('purchase_order_id'));
                 }); 
 
                 $.ajax({
                 type: "POST",
                 url: "<?= base_url('')  ?>inventory/Frontend/Purchases/deleteMultiplePurchaseOrders",
                 data: {purchase_order_ids : selectcheckbox }
                 }).done(function(data){
 
                     if (data==1) {
                         swal(
                             'Purchase Order !',
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

<!-- /content area -->
<script type="text/javascript">
    $(document).ready(function(){

	    // console.log("Ajax Call");

	    var table =  $('#items').DataTable({
		    "processing": true,
		    "serverSide": true,
		    "paging":true,
		    "pageLength":100,
			"order":[[1,'asc']],
		    "ajax":{
		        "url": "<?= base_url('inventory/Frontend/Items/ajaxGetItems')?>",
		        "dataType": "json",
		        "type": "POST",
		        "data":{ '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		    },
		   	"deferRender":false,


        	"columnDefs": [],

		    "select":"multi",
		    "columns": [
		        {"data": "item_name", "name":"Item Name", "searchable":true, "orderable": true },
			   	{"data": "item_number", "name":"Item #", "searchable":true, "orderable": true },
		        {"data": "item_description", "name":"Item Description", "searchable":true, "orderable": true },
			   	{"data": "item_type_name", "name":"Item Type", "orderable": true, "searchable": true },
			   	{"data": "unit_definition", "name":"Unit Definition", "orderable": true,  "searchable":false },
                {"data": "products_associated", "name": "Products Associated", "orderable": false, "searchable": false },
                {"data": "total_units_on_hand", "name":"# of Units on Hand", "orderable": true,  "searchable":false },
                {"data": "average_cost_per_unit", "name":"Average Cost Per Unit", "orderable": true,  "searchable":false },
                {"data": "value_of_unit_on_hand", "name":"$ of Units on Hand",  "searchable":false, "orderable":false },
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
                    .html('<a href="<?php echo base_url('inventory/Frontend/Items/exportItemsCSV'); ?>"><button type="button" class="btn btn-success">Export CSV</button></a>');
            },

	    });

    });

</script>

<script>
    $(document).ready(function(){
        
        var product_arr = $('#product_arr').val().split('<::>');
        
        $(document).on('change', '#unit_conversion_type', function(){

            $('#unit_type_conversion').val($(this).val());
            $('#new_products').multiselect('destroy');

            var prodsHTML = '';
  
            console.log($(this).val());
            if($(this).val() == 1){      
                
                var prods_arr = [];

                product_arr.forEach(prar => {
                    console.log(prar);
                    if(prar.split('::')[2] == 'Gallon' || prar.split('::')[2] == 'Quart' || prar.split('::')[2] == 'Pint' || prar.split('::')[2] == 'Fluid Ounce' || prar.split('::')[2] == 'Liter' || prar.split('::')[2] == 'Gallons' || prar.split('::')[2] == 'Quarts' || prar.split('::')[2] == 'Pints' || prar.split('::')[2] == 'Fluid Ounces' || prar.split('::')[2] == 'Liters' || (prar.split('::')[2]  == "Ounce" && (prar.split('::')[3] == 4 || prar.split('::')[3] == 8 || prar.split('::')[3] == 9 || prar.split('::')[3] == 10))  || (prar.split('::')[2] == "Ounces" && (prar.split('::')[3] == 4 || prar.split('::')[3] == 8 || prar.split('::')[3] == 9 || prar.split('::')[3] == 10)) || (prar.split('::')[2] == "Oz" && (prar.split('::')[3] == 4 || prar.split('::')[3] == 8 || prar.split('::')[3] == 9 || prar.split('::')[3] == 10)))
                    {
                        prodsHTML += '<option value="'+ prar.split('::')[0] +'">'+ prar.split('::')[1] +'</option>';
                    }
                });
                $('#new_products').html(prodsHTML);

                $('#new_products').multiselect({
      
                    includeSelectAllOption: true,
                    onInitialized: function(select, container) {
                        $(".styled, .multiselect-container input").uniform({
                            radioClass: 'checker'
                        });
                    },
                    onSelectAll: function() {
                        $.uniform.update();
                    },
                    onChange: function(option, checked, select){
                        if(!prods_arr.includes($(option).val())){
                            prods_arr.push($(option).val());
                        }
                        $('#options_checked_prods').val(prods_arr.join('::'));
                    }      
                });

                measurements = ['Gallon(s)', 'Fluid Ounce(s)', 'Liter(s)', 'Pint(s)', 'Quart(s)'];


                unitsHTML = '<label>Unit Definition</label><br><div class="row"><div class="col-sm-4"><input type="number" class="form-control" name="unit_amount" id="unit_amount"></div><div class="col-sm-8"><select class="form-control" id="unit_type" name="unit_type">';
                measurements.forEach(meas => {        
                    unitsHTML += '<option value="' + meas + '">'+ meas +'</option>';
                });
                unitsHTML += '</select></div></div>';

                $('#new_units').html(unitsHTML);
            } else if($(this).val() == 2){      
                
                var prods_arr = [];

                product_arr.forEach(prar => {
                    console.log(prar);
                    if(prar.split('::')[2] == 'Ton' || prar.split('::')[2] == 'Pound' || prar.split('::')[2] == 'Kilogram' || prar.split('::')[2] == 'Ounce' || prar.split('::')[2] == 'Gram' || prar.split('::')[2] == 'Kilograms' || prar.split('::')[2] == 'Pounds' || prar.split('::')[2] == 'Tons' || prar.split('::')[2] == 'Ounces' || prar.split('::')[2] == 'Lb' || prar.split('::')[2] == 'Kg' || prar.split('::')[2] == 'Oz' || prar.split('::')[2] == 'Grams')
                    {
                        prodsHTML += '<option value="'+ prar.split('::')[0] +'">'+ prar.split('::')[1] +'</option>';
                    }
                });
                $('#new_products').html(prodsHTML);

                $('#new_products').multiselect({
      
                    includeSelectAllOption: true,
                    onInitialized: function(select, container) {
                        $(".styled, .multiselect-container input").uniform({
                            radioClass: 'checker'
                        });
                    },
                    onSelectAll: function() {
                        $.uniform.update();
                    },
                    onChange: function(option, checked, select){
                        if(!prods_arr.includes($(option).val())){
                            prods_arr.push($(option).val());
                        }
                        $('#options_checked_prods').val(prods_arr.join('::'));
                    }      
                });

                measurements = ['Pound(s)', 'Ton(s)', 'Ounce(s)', 'Gram(s)', 'Kilogram(s)'];


                unitsHTML = '<label>Unit Definition</label><br><div class="row"><div class="col-sm-4"><input type="number" class="form-control" name="unit_amount" id="unit_amount"></div><div class="col-sm-8"><select class="form-control" id="unit_type" name="unit_type">';
                measurements.forEach(meas => {        
                    unitsHTML += '<option value="' + meas + '">'+ meas +'</option>';
                });
                unitsHTML += '</select></div></div>';

                $('#new_units').html(unitsHTML);
            }
        })
    });

</script>

