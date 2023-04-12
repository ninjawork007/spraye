<div  class="table-responsive table-spraye">
   <table  class="table datatable-filter-custom">
      <thead>
         <tr>
         <th><input type="checkbox" id="select_all" <?php if (empty($all_purchases)) { echo 'disabled'; }  ?>    /></th>
            <th>Purchase Order #</th>
            <th>Purchase Order Date</th>
            <th>Sent Status</th>
            <th>PO Status</th>
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
            <td><?= date('m-d-Y', strtotime($purchase->estimated_delivery_date)) ?></td>
            <td><?= $purchase->location_name ?></td>
            <td><?= $purchase->sub_location_name ?></td>
            <td><?= $purchase->vendor_name ?></td>
               <?php 
                  $items = json_decode($purchase->items, true);
                  // die(print_r($items));
                  if (sizeof($items) > 1) {
               ?>
            <td>Multiple</td>
               <?php	} else {	?>
            <td ><?= $items[0]['name'] .'<br>'. $items[0]['item_number'] ?></td>
               <?php	}	?>
            <td>$<?= $purchase->grand_total?></td>
            <td class="table-action">
               <ul style="list-style-type: none; padding-left: 0px;">

               <li style="display: inline; padding-right: 10px;">
                  <a  class="email button-next" id="<?= $purchase->purchase_order_id ?>"  purchase_order_number="<?= $purchase->purchase_order_number ?>"    ><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a>
               </li>
               <li style="display: inline; padding-right: 10px;">
                  <a href="<?= base_url('admin/Estimates/pdfEstimate/').$purchase->purchase_order_id ?>" target="_blank" class=" button-next"><i class=" icon-file-pdf position-center" style="color: #9a9797;"></i></a>
               </li>
               <li style="display: inline; padding-right: 10px;">
                  <a href="<?= base_url('admin/Estimates/printEstimate/').$purchase->purchase_order_id ?>" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a>
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