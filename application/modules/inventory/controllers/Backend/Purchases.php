<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Purchases extends MY_Controller{

	public function __construct() {
		$this->loadModel();
	}

	private function loadModel(){
		$this->load->model('LocationsModel', 'LocationsModel');
		$this->load->model('VendorsModel', 'VendorsModel');
		$this->load->model('ItemsModel', 'ItemsModel');
		$this->load->model('ItemVendorsModel', 'ItemVendorsModel');
		$this->load->model('QuantitiesModel', 'QuantitiesModel');
		$this->load->model('AlertsModel', 'Alerts');
		$this->load->model('PurchasesModel', 'PurchasesModel');
		$this->load->model('PurchasesReceivingModel', 'ReceivingsModel');
		$this->load->model('PurchasesReturnsModel', 'ReturnsModel');
		$this->load->model('AdminTbl_company_model', 'CompanyModel');
		$this->load->model('Company_email_model', 'CompanyEmail'); 
		$this->load->model('Administratorsuper');
    }

	public function index() {
		$columns = ['purchase_order_num', 'created_at', 'vendor', 'grand_total'];
		$datatables = new DataTables($this->request, $columns);

		if($datatables->isRequestValid() === false)
			return $this->failUnauthorized(lang('Errors.unauthorized'));

		$draw = $datatables->getDraw();
		$length = $datatables->getLength();
		$start = $datatables->getStart();
		$search = $datatables->getSearchStr();
		$orderBy = $datatables->getOrderBy();
		$orderDir = $datatables->getOrderDir();

		if($orderBy === false || $orderDir === false)
			return $this->fail(lang('Errors.invalid_order'));

		$this->purchases->setDtParameters($search, $orderBy, $orderDir, $length, $start);

		// Is user worker/supervisor? Let's limit by the warehouses he has access to
		if($this->logged_user->role == 'worker' || $this->logged_user->role == 'supervisor') {
			$warehouseIds = $this->warehouse_relations->getWarehouseIdsByUser($this->logged_user->id);
			$result = $this->purchases->dtGetAllPurchases(true, $warehouseIds);
		}else{
			$result = $this->purchases->dtGetAllPurchases();
		}

		return $this->respond(array_merge(
			['draw' => $draw],
			$result
		));
	}

	public function create() {
		$data = $this->input->post();
	
		$itemsArr = $data['items'];

		/* Now we'll loop through the items, making sure it's well formed and:
			- All items exist
			- All items have this supplier
			- All info matches (name, price, etc)
		*/
		for($i = 0; $i < count($data['items']); $i++) {
			// First, make sure all properties exist
			$itemProperties = array_keys($data['items'][$i]);
			$requiredProperties = ['item_id', 'item_number', 'name', 'received_qty', 'unit_price', 'quantity'];

			// Convert to item to work better
			$itemObj = (object) $data['items'][$i];
			
			// Make sure item exists
			$item = $this->ItemsModel->getOneItem(array('items_tbl.item_id' =>$itemObj->item_id, 'items_tbl.company_id' => $this->session->userdata['company_id']));
		
			if(!$item){
				return $this->failNotFound(lang('Errors.purchases.items.not_found', ['id' => $itemObj->item_id]));
			}
			
			// Add item # from items
			$data['items'][$i]['item_number'] = $item->item_number;

		}
		
		// Make sure there are no duplicate items
		// For this, we'll extract the IDs, and then remove duplicates and compare number of
		// items
		$item_ids = [];
		foreach($data['items'] as $itemArr){

			$item_ids[] = $itemArr['item_id'];
		}
		if(count(array_unique($item_ids)) < count($data['items'])){

			return $this->fail(lang('Validation.purchases.duplicate_items'));
		}

		// Calculate order's subtotal
		$data['subtotal'] = 0;
		foreach($data['items'] as $itemArr) {
			$itemSubtotal = round($itemArr['unit_price'] * $itemArr['quantity'], 2);
			$data['subtotal'] = round($data['subtotal'] + $itemSubtotal, 2);
		}

		// If discount is percentage, make sure it doesn't exceed 100%
		// If it's amount, make sure it doesn't exceed order's subtotal
		if($data['discount_type'] == 'percentage' && $data['discount'] > 100){

			return $this->fail(lang('Validation.purchases.discount_percentage_greater_than'));
		} else if($data['discount_type'] == 'amount' && $data['discount'] > $data['subtotal']){

			return $this->fail(lang('Validation.purchases.discount_amount_greater_than'));
		}

		/* To calculate total we'll do this to the subtotal:
			- Subtract discount
			- Add shipping cost
			- Add tax
		*/
		$discount = $data['discount'];
		
		// $discount = round($data['subtotal'] * $data['discount'] / 100, 2);

		$data['grand_total'] = $data['subtotal'];
		$data['grand_total'] = round($data['grand_total'] - $discount, 2);
		$data['grand_total'] = round($data['grand_total'] + $data['freight'], 2);
		$tax = round($data['tax'] * $data['grand_total'] / 100, 2);
		$data['grand_total'] = round($data['grand_total'] + $tax, 2);
		$data['company_id'] = $this->session->userdata['company_id'];
		$data['created_by'] = $this->session->userdata['id'];
		$data['purchase_order_date'] = date('Y-m-d H:i:s');
		$data['items'] = json_encode($data['items']);
		
		// Insert purchase
		$purchase_id = $this->PurchasesModel->insert_purchase_order($data);
		// die(print_r($purchase_id));

		#### ADDING PURCHASE ORDER TO RECEIVING TABLE ####
		$receive = array(
				'company_id' => $this->session->userdata['company_id'],
				'purchase_order_number' => $data['purchase_order_number'],
				'purchase_order_id' => $purchase_id,
				'vendor_id' => $data['vendor_id'],
				'freight' => $data['freight'],
				'discount' => $data['discount'],
				'location_id' => $data['location_id'],
                'sub_location_id' => $data['sub_location_id'],
				'items' => $data['items'],
				'created_at' => date('Y-m-d H:i:s'),
			);
			
		$receiving_id = $this->ReceivingsModel->insert_purchase_receiving($receive);

		#### END OF RECEIVING PURCHASE ORDER ####

		// Finally update quantity alerts
		$itemIds = [];
		foreach($itemsArr as $itemArr){
			$itemIds[] = $itemArr['item_id'];
		}
		
		// Done!
		$new_purchase = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_id));
		
		$purchase_order_id = $new_purchase[0]->purchase_order_id;
		
		if ($data['purchase_sent_status'] == 1 ){
			$where = array(
				'purchase_order_tbl.purchase_order_id' => $purchase_order_id
			);
			$param = array(
				'sent_date' => date("Y-m-d H:i:s")
			);

			$this->PurchasesModel->updatePurchaseOrder($where, $param);

            $where_receiving = array(
				'purchase_receiving_tbl.purchase_order_id' => $purchase_order_id
			);

			$received = array(
				'is_draft' => 0
			);

			$receiving_update = $this->ReceivingsModel->updateReceivingOrder($where_receiving, $received);


			$company_id = $this->session->userdata['company_id'];
			$vendor_id  = $data['vendor_id'];
			if(isset($data['notes']) && $data['notes'] != ''){
				$data['msgtext'] = $data['notes'];
			}else{
				$data['msgtext'] = '';
			}
			
			$data['vendor_details'] = $this->VendorsModel->getOneVendor($data['vendor_id']);
			$data['link'] =  base_url('welcome/pdfPurchaseOrder/').base64_encode($purchase_order_id);
			$data['link_acc'] =  base_url('welcome/PurchaseOrderAccept/').base64_encode($purchase_order_id);
			$where_company = array('company_id' =>$company_id);
			$data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
			$data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
			$body = $this->load->view('inventory/purchases/purchase_order_email',$data,true);
			$where_company['is_smtp'] = 1;
			$company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
			if (!$company_email_details) {
				$company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
			} 
			
			$res = Send_Mail_dynamic($company_email_details,$data['vendor_details']->vendor_email_address,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Purchase Order Details');
		}

		if ($new_purchase) {
			$return_array =  array('status' => 200, 'msg' => 'Purchase Order successful.', 'data' => $new_purchase);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);

	}

	public function generate_unique_reference() {
		$generator = new ReferenceGenerator();

		return $this->respond([
			'reference' => $generator->generate('purchase')
		]);
	}

	private function updateAlerts($itemIds, $location, $subLocation) {
		if(!is_array($itemIds)){

			$itemIds = [$itemIds];
		}
		// die(print_r($subLocation));
		foreach($itemIds as $itemId) {
			// Delete alerts (if they exist), because we'll create a new one
			// if we need to
			$this->Alerts->deleteAlertsForItem(array('alert_item_id' => $itemId, 'alert_location_id' => $location, 'alert_sub_location_id' => $subLocation));

			// Get item information
			$item = $this->ItemsModel->getItem(array('items_tbl.item_id' => $itemId));
			// die(print_r($item));
			$item->quantities = $this->QuantitiesModel->getItemQuantities(array('quantities.quantity_id' => $itemId));
			// die(print_r($item->quantities));

			// If there are no min/max alerts set, end here
			if($item->min_alert == null && $item->max_alert == null){

				return;
			}

			// At this point we have alerts set.. Loop through quantities looking for
			// one that exceeds limits set
			foreach($item->quantities as $quantity) {
				// if($item->min_alert != null && $quantity['quantity'] <= $item->min_alert) {
				if($item->min_alert != null && $quantity->quantity <= $item->min_alert) {
					// Minimum alert triggered! Save it and continue
					$alert_min = array(
						'alert_item_id' => $itemId,
						'alert_location_id' => $quantity->quantity_location_id,
						'alert_sub_location_id' => $quantity->quantity_sublocation_id,
						'alert_qty' => $quantity->quantity,
						'alert_type' => 1
					);
					$this->Alerts->triggerMinAlert($alert_min);
				}else if($item->max_alert != null && $quantity['quantity'] >= $item->max_alert) {
					// Maximum alert triggered! Save it and continue
					$alert_max = array(
						'alert_item_id' => $itemId,
						'alert_location_id' => $quantity->quantity_location_id,
						'alert_sub_location_id' => $quantity->quantity_sublocation_id,
						'alert_qty' => $quantity->quantity,
						'alert_type' => 2
					);
					$this->Alerts->triggerMaxAlert($alert_max);
				}
			}
		}
	}

	public function receivingOrder($purchase_order_id) {
		$data = $this->input->post();
        $data['purchase_order_id'] = $purchase_order_id;
        $purchase_order = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));

		if($purchase_order[0]->is_receiving == 1){
			$purchase_order = $this->ReceivingsModel->getReceiving(array('purchase_receiving_tbl.purchase_order_id' => $purchase_order_id));	
		} else {
			$purchase_order = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));
		}

		if ($purchase_order) {
			$return_array =  array('status' => 200, 'msg' => 'Purchase Order found successfully.', 'data' => $purchase_order);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	public function receivingItemsOrder($purchase_order_id) {
		$data = $this->input->post();

		if($data['status'] == 2 || $data['status'] == 0){
			$paidStatus = 0;
		} else {
			$paidStatus = 1;
		}

		$itemsArr = $data['items'];

		$purchase_order = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));
		
		$where = array(
			'purchase_order_tbl.purchase_order_id' => $purchase_order_id
		);
		if($purchase_order[0]->sent_date == '0000-00-00' || $purchase_order[0]->open_date == '0000-00-00'){

			$param = array(
				'purchase_sent_status' => 2,
				'sent_date' =>date("Y-m-d H:i:s"),
				'open_date' =>date("Y-m-d H:i:s"),
				'sub_location_id' =>$data['sub_location_id'],
				'purchase_order_status' =>$data['status'],
				'is_receiving' => 1,
				'is_complete' => $data['completed'],
				'updated_at' => date("Y-m-d H:i:s")
			);
		} else {

			$param = array(
				'purchase_sent_status' => 2,
				'sub_location_id' =>$data['sub_location_id'],
				'purchase_order_status' =>$data['status'],
				'is_receiving' => 1,
				'is_complete' => $data['completed'],
				'updated_at' => date("Y-m-d H:i:s")
			);
		}

		$result = $this->PurchasesModel->updatePurchaseOrder($where, $param);

		$where = array(
			'purchase_receiving_tbl.purchase_order_id' => $purchase_order_id
		);

		$new_received = $this->ReceivingsModel->getReceiving($where);

		$total_receipt = 0;
		if($data['total_received'] >= $data['total_units']){
			$total_receipt = $data['total_units'];
		} else {
			$total_receipt = $data['total_received'];
		}

		$received = array(
			'company_id' => $this->session->userdata['company_id'],
			'purchase_order_number' => $purchase_order[0]->purchase_order_number,
			'purchase_order_id' => $purchase_order[0]->purchase_order_id,
			'total_units' => $data['total_units'],
			'freight' => $data['freight'],
			'discount' => $data['discount'],
			'total_purchase_order_amount' => $purchase_order[0]->grand_total,
			'received_by' => $this->session->userdata['id'],
			'receiving_date' => date('Y-m-d H:i:s'),
			'location_id' => $data['location_id'],
			'sub_location_id' => $data['sub_location_id'],
			'items' => json_encode($data['items']),
			'subtotal_received' => ($new_received[0]->subtotal_received + $data['subtotal_received']),
			'total_received_amount' => ($new_received[0]->total_received_amount + $data['total_received_amount']),
			'total_received' => ($new_received[0]->total_received + $total_receipt),
			'updated_at' => date('Y-m-d H:i:s'),
		);

		$receiving_update = $this->ReceivingsModel->updateReceivingOrder($where, $received);

		foreach($itemsArr as $itemArr){

			$quantityArr = array(
				'quantity_item_id' => $itemArr['item_id'], 
				'quantity_location_id' => $data['location_id'], 
				'quantity_sublocation_id' => $data['sub_location_id'],
				'company_id' => $this->session->userdata['company_id']
			);

            $quantArr = array(
                'quantity_item_id' => $itemArr['item_id'], 
                'company_id' => $this->session->userdata['company_id']
            );

			$unit_amount = $this->ReceivingsModel->getUnitAmount($itemArr['item_id']);
			
			$alreadyExists = $this->ReceivingsModel->getAlreadyExistingQuantities($quantityArr);

            $quant = $this->ReceivingsModel->getAlreadyExistingQuantity($quantArr);

		
			if(!empty($alreadyExists)){
				
				$this->db->where('quantity_id', $alreadyExists->quantity_id);
				$this->db->update('quantities', array('quantity' => ($alreadyExists->quantity + ($itemArr['receiving_qty']))));

				$item_info = $this->ReceivingsModel->getCurrentAverageCostPerUnit($itemArr['item_id']);

				if($itemArr['receiving_qty'] != 0){
					$new_average = $this->calculateAverageCostPerUnit(
                        $item_info->average_cost_per_unit, 
                        $quant, 
                        $itemArr['unit_price'], 
                        ($itemArr['receiving_qty'])
                    );
				}

				$this->db->where('item_id', $itemArr['item_id']);
				$this->db->update('items_tbl', array('average_cost_per_unit' => number_format($new_average, 2)));
			} else {
				$quantityArr['quantity'] = ($itemArr['receiving_qty']);
				$this->db->insert('quantities',$quantityArr);

                $item_info = $this->ReceivingsModel->getCurrentAverageCostPerUnit($itemArr['item_id']);

				if($itemArr['receiving_qty'] != 0){
					$new_average = $this->calculateAverageCostPerUnit(
                        $item_info->average_cost_per_unit, 
                        $quant, 
                        $itemArr['unit_price'], 
                        ($itemArr['receiving_qty'])
                    );
				}

				$this->db->where('item_id', $itemArr['item_id']);
				$this->db->update('items_tbl', array('average_cost_per_unit' => number_format($new_average, 2)));
                
			}
		}

		// Done!
		$new_received = $this->ReceivingsModel->getReceiving(array('purchase_receiving_tbl.purchase_receiving_id' => $receiving_update));	

		if ($new_received) {
			$return_array =  array('status' => 200, 'msg' => 'Purchase Order received successfully.', 'data' => $new_received);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	public function receivedOrder($receiving_id) {
		$data = $this->input->post();

		$received_order = $this->ReceivingsModel->getReceiving(array('purchase_receiving_tbl.purchase_receiving_id' => $receiving_id));
	
		if ($received_order) {
			$return_array =  array('status' => 200, 'msg' => 'Purchase Order found successfully.', 'data' => $received_order);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	public function returnOrder($purchase_order_id) {
		$data = $this->input->post();
        $data['purchase_order_id'] = $purchase_order_id;
        $purchase_order = $this->PurchasesModel->checkPurchaseForReturnStatus(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));

		if($purchase_order->is_returned == 1){
			$purchase_return = $this->ReturnsModel->getReturn(array('purchase_return_tbl.purchase_order_id' => $purchase_order_id));	
		} else {
			$purchase_return = $this->ReceivingsModel->getReceiving(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));
		}

		if ($purchase_return) {
			$return_array =  array('status' => 200, 'msg' => 'Purchase Order found successfully.', 'data' => $purchase_return);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	public function returningItemsOrder($purchase_order_id) {
		$data = $this->input->post();
		$purchase_order = $this->PurchasesModel->checkPurchaseForReturnStatus(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));
	
		$where = array(
			'purchase_order_tbl.purchase_order_id' => $purchase_order_id
		);

		$itemsArr = $data['items'];

		if($purchase_order->is_returned == 1){

			$where_return = array(
				'purchase_return_tbl.return_id' => $purchase_order->return_id
			);

			$returned = array(
				'company_id' => $this->session->userdata['company_id'],
				'purchase_order_number' => $purchase_order->purchase_order_number,
				'purchase_order_id' => $purchase_order->purchase_order_id,
				'vendor_id' => $data['vendor_id'],
				'location_id' => $data['location_id'],
				'sub_location_id' => $data['sub_location_id'],
				'items' => json_encode($data['items']),
				'discount' => $data['discount'],
				'freight' => $data['freight'],
				'updated_at' => date('Y-m-d H:i:s'),
			);

			$returned_update = $this->ReturnsModel->updateReturnOrder($where_return, $returned);

			// Done!
			$new_returned = $this->ReturnsModel->getReturn(array('purchase_return_tbl.return_id' => $returned_update));	

			foreach($itemsArr as $itemArr){

				$quantityArr = array(
					'quantity_item_id' => $itemArr['item_id'], 
					'quantity_location_id' => $data['location_id'], 
					'quantity_sublocation_id' => $data['sub_location_id'],
					'company_id' => $this->session->userdata['company_id']
				);

                $quantArr = array(
                    'quantity_item_id' => $itemArr['item_id'], 
					'company_id' => $this->session->userdata['company_id']
                );

				$unit_amount = $this->ReceivingsModel->getUnitAmount($itemArr['item_id']);

				$alreadyExists = $this->ReceivingsModel->getAlreadyExistingQuantities($quantityArr);

                $quant = $this->ReceivingsModel->getAlreadyExistingQuantity($quantArr);

				if(!empty($alreadyExists)){
					$this->db->where('quantity_id', $alreadyExists->quantity_id);
					$this->db->update('quantities', array('quantity' => ($alreadyExists->quantity - ($itemArr['return_qty'] * $unit_amount))));

					$item_info = $this->ReceivingsModel->getCurrentAverageCostPerUnit($itemArr['item_id']);

					if($itemArr['return_qty'] != 0){
						$new_average = $this->calculateAverageCostPerUnitAfterReturn($item_info->average_cost_per_unit, 
                        $quant,
                        $itemArr['unit_price'],
                        ($itemArr['return_qty']));
					}

					$this->db->where('item_id', $itemArr['item_id']);
					$this->db->update('items_tbl', array('average_cost_per_unit' => number_format($new_average, 2)));
				} else {
					$quantityArr['quantity'] = ($itemArr['return_qty'] * $unit_amount);
					$this->db->insert('quantities',$quantityArr);

                    $item_info = $this->ReceivingsModel->getCurrentAverageCostPerUnit($itemArr['item_id']);

                    if($itemArr['return_qty'] != 0){
                        $new_average = $this->calculateAverageCostPerUnitAfterReturn(
                            $item_info->average_cost_per_unit, 
                            $quant, 
                            $itemArr['unit_price'], 
                            ($itemArr['return_qty'])
                        );
                    }

                    $this->db->where('item_id', $itemArr['item_id']);
                    $this->db->update('items_tbl', array('average_cost_per_unit' => number_format($new_average, 2)));
				}
			}

		} else {

			$returned = array(
				'company_id' => $this->session->userdata['company_id'],
				'purchase_order_number' => $purchase_order->purchase_order_number,
				'purchase_order_id' => $purchase_order->purchase_order_id,
				'vendor_id' => $data['vendor_id'],
				'location_id' => $data['location_id'],
				'sub_location_id' => $data['sub_location_id'],
				'items' => json_encode($data['items']),
				'discount' => $data['discount'],
				'freight' => $data['freight'],
				'created_at' => date('Y-m-d H:i:s'),
			);
			
			$return_id = $this->ReturnsModel->insert_purchase_return($returned);
	
			#### UPDATES ITEMS COLUMN WITH RETURN_QTY
			$param = array(
				'purchase_order_status' =>$data['status'],
				'items' => json_encode($data['items']),
				'is_returned' => 1,
				'updated_at' => date("Y-m-d H:i:s")
			);
	
			$result = $this->PurchasesModel->updatePurchaseOrder($where, $param);

			#### UPDATES ITEMS COLUMN WITH RETURN QTY
			$where_receiving = array(
				'purchase_receiving_tbl.purchase_order_id' => $purchase_order_id
			);

			$received = array(
				'items' => json_encode($data['items']),
				'updated_at' => date('Y-m-d H:i:s'),
			);

			$receiving_update = $this->ReceivingsModel->updateReceivingOrder($where_receiving, $received);

			// Done!
			$new_returned = $this->ReturnsModel->getReturn(array('purchase_return_tbl.return_id' => $return_id));
			// die(print_r($new_returned));

			foreach($itemsArr as $itemArr){

				$quantityArr = array(
					'quantity_item_id' => $itemArr['item_id'], 
					'quantity_location_id' => $data['location_id'], 
					'quantity_sublocation_id' => $data['sub_location_id'],
					'company_id' => $this->session->userdata['company_id']
				);

                $quantArr = array(
                    'quantity_item_id' => $itemArr['item_id'], 
					'company_id' => $this->session->userdata['company_id']
                );


				$unit_amount = $this->ReceivingsModel->getUnitAmount($itemArr['item_id']);

				$alreadyExists = $this->ReceivingsModel->getAlreadyExistingQuantities($quantityArr);

                $quant = $this->ReceivingsModel->getAlreadyExistingQuantity($quantArr);


				if(!empty($alreadyExists)){
					$this->db->where('quantity_id', $alreadyExists->quantity_id);
					$this->db->update('quantities', array('quantity' => ($alreadyExists->quantity - ($itemArr['return_qty']))));

					$item_info = $this->ReceivingsModel->getCurrentAverageCostPerUnit($itemArr['item_id']);

					if($itemArr['return_qty'] != 0){
						$new_average = $this->calculateAverageCostPerUnitAfterReturn(
                            $item_info->average_cost_per_unit, 
                            $quant, 
                            $itemArr['unit_price'], 
                            ($itemArr['return_qty'])
                        );
					}

					$this->db->where('item_id', $itemArr['item_id']);
					$this->db->update('items_tbl', array('average_cost_per_unit' => number_format($new_average, 2)));
				} else {
					$quantityArr['quantity'] = ($itemArr['return_qty']);
					$this->db->insert('quantities',$quantityArr);

                    $item_info = $this->ReceivingsModel->getCurrentAverageCostPerUnit($itemArr['item_id']);

                    if($itemArr['return_qty'] != 0){
                        $new_average = $this->calculateAverageCostPerUnitAfterReturn(
                            $item_info->average_cost_per_unit, 
                            $quant, 
                            $itemArr['unit_price'], 
                            ($itemArr['return_qty'])
                        );
                    }

                    $this->db->where('item_id', $itemArr['item_id']);
                    $this->db->update('items_tbl', array('average_cost_per_unit' => number_format($new_average, 2)));
				}
			}
		}

		if ($new_returned) {
			$return_array =  array('status' => 200, 'msg' => 'Purchase Order returned successfully.', 'data' => $new_returned);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	public function returnedOrder($return_id) {
		$data = $this->input->post();
		$returned_order = $this->ReturnsModel->getReturn(array('purchase_return_tbl.return_id' => $return_id));

		if ($returned_order) {
			$return_array =  array('status' => 200, 'msg' => 'Purchase Order found successfully.', 'data' => $returned_order);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	public function purchaseOrder($purchase_order_id) {
		$data = $this->input->post();
        $data['purchase_order_id'] = $purchase_order_id;
        $purchase_order = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));

		if($purchase_order[0]->is_receiving == 1 && $purchase_order[0]->is_returned == 1){
			$purchase_order = $this->ReturnsModel->getReturn(array('purchase_return_tbl.purchase_order_id' => $purchase_order_id));	
		} else if($purchase_order[0]->is_receiving == 1){
			$purchase_order = $this->ReceivingsModel->getReceiving(array('purchase_receiving_tbl.purchase_order_id' => $purchase_order_id));	
		} else {
			$purchase_order = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));
		}

		if ($purchase_order) {
			$return_array =  array('status' => 200, 'msg' => 'Purchase Order found successfully.', 'data' => $purchase_order);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	public function receivedInvoice($purchase_order_id) {
		$data = $this->input->post();
		$po_invoice = $this->PurchasesModel->getPOInvoice(array('po_invoice_tbl.purchase_order_id' => $purchase_order_id, 'po_invoice_tbl.company_id' => $this->session->userdata['company_id']));
		
		if ($po_invoice) {
			$return_array =  array('status' => 200, 'msg' => 'Purchase Order Invoice found successfully.', 'data' => $po_invoice);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	public function updatePO($purchase_order_id) {
		$data = $this->input->post();
		$purchase_order = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));
		
		$where = array(
			'purchase_order_tbl.purchase_order_id' => $purchase_order_id
		);

		$param = array(
			'location_id' => (isset($data['location_id']) && $data['location_id'] != '') ? $data['location_id'] : $purchase_order[0]->location_id,
			'sub_location_id' => (isset($data['sub_location_id']) && $data['sub_location_id'] != '') ? $data['sub_location_id'] : $purchase_order[0]->sub_location_id,
			'updated_at' => date("Y-m-d H:i:s")
		);

		$result = $this->PurchasesModel->updatePurchaseOrder($where, $param);

		if ($result) {
            echo "true";
        } else {
            echo "false";
        }
	}

    public function updateDraft($purchase_order_id) {
		$data = $this->input->post();

        // die(print_r($data));

        $itemsArr = $data['items'];

		/* Now we'll loop through the items, making sure it's well formed and:
			- All items exist
			- All items have this supplier
			- All info matches (name, price, etc)
		*/
		for($i = 0; $i < count($data['items']); $i++) {
			// First, make sure all properties exist
			$itemProperties = array_keys($data['items'][$i]);
			$requiredProperties = ['item_id', 'item_number', 'name', 'received_qty', 'unit_price', 'quantity'];

			// Convert to item to work better
			$itemObj = (object) $data['items'][$i];
			
			// Make sure item exists
			$item = $this->ItemsModel->getOneItem(array('items_tbl.item_id' =>$itemObj->item_id, 'items_tbl.company_id' => $this->session->userdata['company_id']));
		
			if(!$item){
				return $this->failNotFound(lang('Errors.purchases.items.not_found', ['id' => $itemObj->item_id]));
			}
			
			// Add item # from items
			$data['items'][$i]['item_number'] = $item->item_number;

		}

        // Make sure there are no duplicate items
		// For this, we'll extract the IDs, and then remove duplicates and compare number of
		// items
		$item_ids = [];
		foreach($data['items'] as $itemArr){

			$item_ids[] = $itemArr['item_id'];
		}
		if(count(array_unique($item_ids)) < count($data['items'])){

			return $this->fail(lang('Validation.purchases.duplicate_items'));
		}

		$purchase_order = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));
		
		$where = array(
			'purchase_order_tbl.purchase_order_id' => $purchase_order_id
		);

        // Calculate order's subtotal
		$data['subtotal'] = 0;
		foreach($data['items'] as $itemArr) {
			$itemSubtotal = round($itemArr['unit_price'] * $itemArr['quantity'], 2);
			$data['subtotal'] = round($data['subtotal'] + $itemSubtotal, 2);
		}

        $discount = $data['discount'];

        $data['grand_total'] = $data['subtotal'];
		$data['grand_total'] = round($data['grand_total'] - $discount, 2);
		$data['grand_total'] = round($data['grand_total'] + $data['freight'], 2);
		$tax = round($data['tax'] * $data['grand_total'] / 100, 2);
		$data['grand_total'] = round($data['grand_total'] + $tax, 2);

		$param = array(
			'location_id' => (isset($data['location_id']) && $data['location_id'] != '') ? $data['location_id'] : $purchase_order[0]->location_id,
			'sub_location_id' => (isset($data['sub_location_id']) && $data['sub_location_id'] != '') ? $data['sub_location_id'] : $purchase_order[0]->sub_location_id,
			'updated_at' => date("Y-m-d H:i:s"),
            'items' => json_encode($data['items']),
            'grand_total' => $data['grand_total']
            
		);

		$result = $this->PurchasesModel->updatePurchaseOrder($where, $param);

       #### UPDATES ITEMS COLUMN WITH RETURN QTY
			$where_receiving = array(
				'purchase_receiving_tbl.purchase_order_id' => $result
			);

			$received = array(
				'items' => json_encode($data['items']),
				'updated_at' => date('Y-m-d H:i:s'),
			);

			$receiving_update = $this->ReceivingsModel->updateReceivingOrder($where_receiving, $received);



        if ($data['purchase_sent_status'] == 1 ){
			$where = array(
				'purchase_order_tbl.purchase_order_id' => $purchase_order_id
			);
			$param = array(
				'sent_date' => date("Y-m-d H:i:s"),
                'purchase_sent_status' => 1
			);

			$this->PurchasesModel->updatePurchaseOrder($where, $param);

            $where_receiving = array(
				'purchase_receiving_tbl.purchase_order_id' => $purchase_order_id
			);

			$received = array(
				'is_draft' => 0
			);

			$receiving_update = $this->ReceivingsModel->updateReceivingOrder($where_receiving, $received);

			$company_id = $this->session->userdata['company_id'];
			$vendor_id  = $data['vendor_id'];
			if(isset($data['notes']) && $data['notes'] != ''){
				$data['msgtext'] = $data['notes'];
			}else{
				$data['msgtext'] = '';
			}
			
			$data['vendor_details'] = $this->VendorsModel->getOneVendor($data['vendor_id']);
			$data['link'] =  base_url('welcome/pdfPurchaseOrder/').base64_encode($purchase_order_id);
			$data['link_acc'] =  base_url('welcome/PurchaseOrderAccept/').base64_encode($purchase_order_id);
			$where_company = array('company_id' =>$company_id);
			$data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
			$data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
			$body = $this->load->view('inventory/purchases/purchase_order_email',$data,true);
			$where_company['is_smtp'] = 1;
			$company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
			if (!$company_email_details) {
				$company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
			} 
			
			$res = Send_Mail_dynamic($company_email_details,$data['vendor_details']->vendor_email_address,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Purchase Order Details');
		}

		if ($result) {
            echo "true";
        } else {
            echo "false";
        }
	}

    

	public function calculateAverageCostPerUnit($current_average, $current_quantity, $po_cost, $po_units){
		$average_cost = ((number_format($current_average, 2) * number_format($current_quantity, 2)) 
            + (number_format($po_cost, 2) * number_format($po_units, 2))) 
            / (number_format($current_quantity, 2) + number_format($po_units, 2));
		    return number_format($average_cost, 2);
	}

    public function calculateAverageCostPerUnitAfterReturn($current_average, $current_quantity, $po_cost, $po_units){
		$average_cost = ((number_format($current_average, 2) * number_format($current_quantity, 2)) 
            - (number_format($po_cost, 2) * number_format($po_units, 2))) 
            / (number_format($current_quantity, 2) - number_format($po_units, 2));
		    return number_format($average_cost, 2);
	}

}