<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Items extends MY_Controller
{

	// Define create and update rules
	private $rules = [
		'create' => [
			'name' => [
				'rules' => 'min_length[1]|max_length[45]',
				'errors' => [
					'min_length' => 'Validation.items.name_min_length',
					'max_length' => 'Validation.items.name_max_length'
				]
			],
			'item_number' => [
				'rules' => 'min_length[1]',
				'errors' => [
					'min_length' => 'Validation.items.item_number_min_length'
				]
			],
			'item_type_id' => [
				'rules' => 'in_list[none,code39,code128,ean-8,ean-13,upc-a,qr]',
				'errors' => [
					'in_list' => 'Validation.items.item_type_id_invalid'
				]
			],
			'item_description' => [
				'rules' => 'permit_empty'
			],
			'min_alert' => [
				'rules' => 'permit_empty|numeric|greater_than_equal_to[0]',
				'errors' => [
					'numeric' => 'Validation.items.min_alert_numeric',
					'greater_than_equal_to' => 'Validation.items.min_alert_greater_than_equal_to'
				]
			],
			'max_alert' => [
				'rules' => 'permit_empty|numeric|greater_than_equal_to[1]',
				'errors' => [
					'numeric' => 'Validation.items.max_alert_numeric',
					'greater_than_equal_to' => 'Validation.items.max_alert_greater_than_equal_to'
				]
			],
			// 'notes' => [
			// 	'rules' => 'permit_empty'
			// ],
			// 'brand_id' => [
			// 	'rules' => 'permit_empty'
			// ]
		],

		'update' => [
			'name' => [
				'rules' => 'permit_empty|min_length[1]|max_length[45]',
				'errors' => [
					'min_length' => 'Validation.items.name_min_length',
					'max_length' => 'Validation.items.name_max_length'
				]
			],
			'item_number' => [
				'rules' => 'permit_empty|min_length[1]',
				'errors' => [
					'min_length' => 'Validation.items.item_number_min_length'
				]
			],
			'item_type_id' => [
				'rules' => 'permit_empty|in_list[none,code39,code128,ean-8,ean-13,upc-a,qr]',
				'errors' => [
					'in_list' => 'Validation.items.item_type_id_invalid'
				]
			],
			'item_description' => [
				'rules' => 'permit_empty'
			],
			'min_alert' => [
				'rules' => 'permit_empty|numeric|greater_than_equal_to[0]',
				'errors' => [
					'numeric' => 'Validation.items.min_alert_numeric',
					'greater_than_equal_to' => 'Validation.items.min_alert_greater_than_equal_to'
				]
			],
			'max_alert' => [
				'rules' => 'permit_empty|numeric|greater_than_equal_to[1]',
				'errors' => [
					'numeric' => 'Validation.items.max_alert_numeric',
					'greater_than_equal_to' => 'Validation.items.max_alert_greater_than_equal_to'
				]
			],
			// 'notes' => [
			// 	'rules' => 'permit_empty'
			// ],
			// 'brand_id' => [
			// 	'rules' => 'permit_empty'
			// ]
		],

		'add_supplier' => [
			'supplier_id' => [
				'rules' => 'numeric',
				'errors' => [
					'numeric' => 'Validation.items.suppliers.supplier_id_numeric'
				]
			],
			'part_number' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					'max_length' => 'Validation.items.suppliers.part_number_max_length'
				]
			],
			'price' => [
				'rules' => 'decimal',
				'errors' => [
					'Validation.items.suppliers.price_decimal'
				]
			],
			'tax' => [
				'rules' => 'decimal',
				'errors' => [
					'Validation.items.suppliers.tax_decimal'
				]
			]
		],

		'update_supplier' => [
			'part_number' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					'max_length' => 'Validation.items.suppliers.part_number_max_length'
				]
			],
			'price' => [
				'rules' => 'permit_empty|decimal',
				'errors' => [
					'Validation.items.suppliers.price_decimal'
				]
			],
			'tax' => [
				'rules' => 'permit_empty|decimal',
				'errors' => [
					'Validation.items.suppliers.tax_decimal'
				]
			]
		]
	];
    public function __construct(){

        parent::__construct();

		$this->rules = (object) $this->rules;

        if (!$this->session->userdata('email')) {

            $actual_link = $_SERVER[REQUEST_URI];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }

        $this->load->library('parser');

        $this->load->helper('text');

        $this->loadModel();
    }

	private function loadModel(){

		$this->load->model('ItemsModel', 'ItemModel');
		$this->load->model('LocationsModel', 'LocationsModel');
		$this->load->model('QuantitiesModel', 'QuantityModel');
		$this->load->model('ItemVendorsModel', 'ItemVendorModel');
	}


	/**
	 * To get all items
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function index() {
		die('I am the items controllers');
		
		$columns = [
			'item_number',
			'item_name',
			'item_description',
			'item_type_id',
			'unit_definition',
			// 'unit_on_hand',
			// 'average_cost'
			// 'value_of_units_on_hand'
			// 'ordering_timeframe'
		];
		
		$datatables = new DataTables($this->request, $columns);
		// die(print_r($datatables));

		if($datatables->isRequestValid() === false){

			return $this->failUnauthorized(lang('Errors.unauthorized'));
		}
		
		$draw = $datatables->getDraw();
		$length = $datatables->getLength();
		$start = $datatables->getStart();
		$search = $datatables->getSearchStr();
		$orderBy = $datatables->getOrderBy();
		$orderDir = $datatables->getOrderDir();

		if($orderBy === false || $orderDir === false){

			return $this->fail(lang('Errors.invalid_order'));
		}

		$this->items->setDtParameters($search, $orderBy, $orderDir, $length, $start);

		return $this->respond(array_merge(
			['draw' => $draw],
			$this->items->dtGetAllItems()
		));
	}

	/**
	 * To get a single item by ID
	 * 
	 * All users will have access to quantities in all warehouses,
	 * in case they don't have items in their warehouses but they
	 * do have on others.
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function show() {
		// die('show new item');
		$data = $this->input->post();
		$id = $data['itemId'];
		$vendor = $data['vendor'];
		// die(print_r($data));
		$item = $this->ItemModel->getItemArr(array('items_tbl.item_id' => $id, 'vendor_id' => $vendor));
		// die(print_r($item));

		if(!$item){

			return $this->failNotFound(lang('Errors.items.not_found', ['id' => $id]));
		}

		// Pending to add vendors and quantities
		$item[0]->quantities = $this->QuantityModel->getItemQuantities(array('quantity_item_id' => $id));
		// die(print_r($item[0]->quantities));
		$item[0]->vendors = $this->ItemVendorModel->getItemVendors(array('item_id' => $id));

		if ($item) {
			$return_array =  array('status' => 200, 'msg' => 'Item found successfully.', 'data' => $item);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	/**
	 * To create a new item
	 * 
	 * Method			POST
	 * Filter			auth:admin
	 */
	public function create() {
		// die('create new item');
		$data = $this->input->post();
		// die(print_r($data));
		
		// $data = $this->input->post();

		if($data['min_alert'] == '')
			$data['min_alert'] = null;
		if($data['max_alert'] == '')
			$data['max_alert'] = null;

		if($data['min_alert'] != null && $data['max_alert'] != null) {
			if($data['min_alert'] >= $data['max_alert'])
				return $this->failValidationErrors(lang('Validation.items.min_alert_greater_or_equal'));
		}

		// Validate code depending on the selected type
		$barcode_rules = [
			'none' => '/^[ -~]{1,500}$/',
			'code39' => '/^[A-Z0-9\s\-\.\$\/\+\%]+$/',
			'code128' => '/^[ -~]{1,128}$/',
			'ean-8' => '/^\d{8}$/',
			'ean-13' => '/^\d{13}$/',
			'upc-a' => '/^\d{12}$/',
			'qr' => '/^[ -~]{1,500}$/'
		];
		// if(!preg_match($barcode_rules[$data['code_type']], $data['code']))
		// 	return $this->failValidationErrors(lang('Validation.items.code_invalid', ['code' => $data['code'], 'code_type' => $data['code_type']]));

		// Make sure code doesn't exist already
		// if($this->items->getItemByCode($data['code']))
		// 	return $this->failResourceExists(lang('Errors.items.already_exists_code', ['code' => $data['code']]));

		// If brand ID set, make sure that brand exists
		// if(isset($data['brand_id']) && $data['brand_id'] != null) {
		// 	if(!$this->brands->getBrand($data['brand_id']))
		// 		return $this->failNotFound(lang('Errors.items.brand_not_found', ['id' => $data['brand_id']]));
		// }

		// If category ID set, make sure that category exists
		// if(isset($data['category_id']) && $data['category_id'] != null) {
		// 	if(!$this->categories->getCategory($data['category_id']))
		// 		return $this->failNotFound(lang('Errors.items.category_not_found', ['id' => $data['category_id']]));
		// }

		$data['created_by'] = $this->session->userdata['id'];

		// Insert
		$item = $this->db->insert('items_tbl',$data);
		// Get Item
		$new_item = $this->ItemModel->getItemByName(array('item_name'=>$data['item_name']));

		// die(print_r($new_item));
		$item_id = $new_item[0]->item_id;
		// die($item_id);
		// After inserting item, insert quantity records for all
		// existing warehouses
		$subLocations = $this->LocationsModel->getSubLocationIds();
		// die(print_r($subLocations));
		foreach($subLocations as $subLocation_id) {
			// die(print_r($warehouse_id));
			$this->db->insert('quantities',[
				'quantity_item_id' => $item_id,
				'quantity_sublocation_id' => $subLocation_id->sub_location_id,
				'quantity' => 0
			]);
			// $this->quantities->insert([
			// 	'item_id' => $item_id,
			// 	'warehouse_id' => $warehouse_id,
			// 	'quantity' => 0
			// ]);
		}

		
		// Done!
		// $new_item = $this->ItemModel->getItem(array('item_id'=>$item[0]->item_id));
		// die(print_r($new_item));

		if ($new_item) {
			$return_array =  array('status' => 200, 'msg' => 'Item created successfully.', 'result' => $new_item);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'result' => array());
		}
		
		echo json_encode($return_array);
		// return $this->respondCreated($new_item);
	}

	/**
	 * To edit an item
	 * 
	 * Method			PUT
	 * Filter			auth:admin
	 */
	public function update($id) {
		if(!$this->validateRequestWithRules($this->rules->update))
			return $this->failWithValidationErrors();

		if(!$this->items->find($id))
			return $this->failNotFound(lang('Errors.items.not_found', ['id' => $id]));
			
		$updateFields = [
			'name',
			'code',
			'code_type',
			'brand_id',
			'sale_price',
			'sale_tax',
			'description',
			'weight',
			'width',
			'height',
			'depth',
			'min_alert',
			'max_alert',
			'notes',
			'category_id',
			'brand_id'
		];

		$data = $this->buildUpdateArray($updateFields, true);

		if($data['min_alert'] == '')
			$data['min_alert'] = null;
		if($data['max_alert'] == '')
			$data['max_alert'] = null;

		if($data['min_alert'] != null && $data['max_alert'] != null) {
			if($data['min_alert'] >= $data['max_alert'])
				return $this->failValidationErrors(lang('Validation.items.min_alert_greater_or_equal'));
		}

		// If trying to edit code, we need to validate it according to
		// code type (existing or new one if updating too)
		if(isset($data['code'])) {
			$code_type = $data['code_type'] ?? null;
			if($code_type === null)
				$code_type = $this->items->getCodeType($id);

			$barcode_rules = [
				'none' => '/^[ -~]{1,500}$/',
				'code39' => '/^[A-Z0-9\s\-\.\$\/\+\%]+$/',
				'code128' => '/^[ -~]{1,128}$/',
				'ean-8' => '/^\d{8}$/',
				'ean-13' => '/^\d{13}$/',
				'upc-a' => '/^\d{12}$/',
				'qr' => '/^[ -~]{1,500}$/'
			];

			if(!preg_match($barcode_rules[$code_type], $data['code']))
				return $this->failValidationErrors(lang('Validation.items.code_invalid', ['code' => $data['code'], 'code_type' => $code_type]));
		}

		// If trying to edit code, make sure it doesn't exist already
		if(isset($data['code'])) {
			// Make sure code doesn't exist already
			$duplicateItem = $this->items->getItemByCode($data['code']);
			if($duplicateItem && $duplicateItem->id != $id)
				return $this->failResourceExists(lang('Errors.items.already_exists_code', ['code' => $data['code']]));
		}

		// If trying to edit brand, make sure it exists
		if(isset($data['brand_id']) && $data['brand_id'] != null) {
			if(!$this->brands->getBrand($data['brand_id']))
				return $this->failNotFound(lang('Errors.items.brand_not_found', ['id' => $data['brand_id']]));
		}

		// If trying to edit category, make sure it exists
		if(isset($data['category_id']) && $data['category_id'] != null) {
			if(!$this->categories->getCategory($data['category_id']))
				return $this->failNotFound(lang('Errors.items.category_not_found', ['id' => $data['category_id']]));
		}

		// Finally update quantity alerts
		$this->updateAlerts($id);

		$this->items->update($id, $data);

		return $this->respondUpdated($this->items->getItem($id));
	}

	/**
	 * To delete an item
	 * 
	 * Item will have to have 0 units in stock, to be able
	 * to be removed
	 * 
	 * Method			DELETE
	 * Filter			auth:admin
	 */
	public function delete($id) {
		if(!$this->items->find($id))
			return $this->failNotFound(lang('Errors.items.not_found', ['id' => $id]));

		// Make sure item has 0 units in stock
		$qty = $this->quantities->getItemTotalQuantities($id);
		if($qty > 0)
			return $this->fail(lang('Errors.items.quantities_left'));

		// Delete supplier relations for this item
		$this->item_suppliers->deleteItemSuppliers($id);

		// Delete quantity relations
		$this->quantities->deleteItemQuantities($id);

		// Delete item (soft), and we're done
		$this->items->delete($id);

		// Finally remove quantity alerts
		$this->alerts->deleteAlertsForItem($id);

		return $this->respondDeleted([
			'id' => $id
		]);
	}

	/**
	 * To get an item, by code, with information about specific warehouse
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function show_by_code_with_warehouse($warehouseId) {
		$itemCode = $this->request->getVar('code') ?? '';
		
		if(!$this->items->getItemByCode($itemCode))
			return $this->failNotFound(lang('Errors.items.not_found_with_code', ['code' => $itemCode]));

		if(!$this->warehouses->getWarehouse($warehouseId))
			return $this->failNotFound(lang('Errors.items.warehouse_not_found', ['id' => $warehouseId]));

		$item = $this->items->getItemByCodeWithWarehouse($itemCode, $warehouseId);

		if(!$item)
			return $this->failNotFound(lang('Errors.items.not_found_with_code_warehouse', ['code' => $itemCode, 'warehouse' => $warehouseId]));

		return $this->respond($item);
	}

	/**
	 * To get an item by ID, with information about specific warehouse
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function show_with_warehouse($itemId, $warehouseId) {
		if(!$this->items->find($itemId))
			return $this->failNotFound(lang('Errors.items.not_found', ['id' => $itemId]));

		if(!$this->warehouses->getWarehouse($warehouseId))
			return $this->failNotFound(lang('Errors.items.warehouse_not_found', ['id' => $warehouseId]));

		$item = $this->items->getItemWithWarehouse($itemId, $warehouseId);

		if(!$item)
			return $this->failNotFound(lang('Errors.items.not_found_with_id_warehouse', ['id' => $itemId, 'warehouse' => $warehouseId]));

		return $this->respond($item);
	}

	/**
	 * To get an item by code
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function show_by_code() {
		$itemCode = $this->request->getVar('code') ?? '';

		$item = $this->items->getItemByCode($itemCode);

		if(!$item)
			return $this->failNotFound(lang('Errors.items.not_found_with_code', ['code' => $itemCode]));

		return $this->respond($item);
	}

	/**
	 * To generate a unique code, with a specific $type, for
	 * an item
	 * 
	 * Method			GET
	 * Filter			auth:admin
	 */
	public function generate_code($type) {
		die('generate code');
		$allowed_types = ['none', 'code39', 'code128', 'ean-8', 'ean-13', 'upc-a', 'qr'];

		if(!in_array($type, $allowed_types))
			return $this->failValidationErrors(lang('Validation.items.code_type_invalid'));

		$generator = new \App\Libraries\CodeGenerator();

		// Keep generating codes until we find a unique one
		$code = null;
		while($code == null || $this->items->getItemByCode($code))
			$code = $generator->generateCode($type);

		return $this->respond([
			'type' => $type,
			'code' => $code
		]);
	}

	/**
	 * To get a supplier for a particular item
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function show_supplier($itemId, $supplierId) {
		if(!$this->items->find($itemId))
			return $this->failNotFound(lang('Errors.items.not_found', ['id' => $itemId]));

		if(!$this->suppliers->find($supplierId))
			return $this->failNotFound(lang('Errors.items.supplier_not_found', ['id' => $supplierId]));

		$relation = $this->item_suppliers->getItemSupplier($itemId, $supplierId);

		if(!$relation)
			return $this->failNotFound(lang('Errors.items.item_supplier_not_found', ['item_id' => $itemId, 'supplier_id' => $supplierId]));

		return $this->respond($relation);
	}

	/**
	 * To add a supplier to a particular item
	 * 
	 * Method			POST
	 * Filter			auth:supervisor,admin
	 */
	public function add_supplier($itemId) {
		if(!$this->validateRequestWithRules($this->rules->add_supplier))
			return $this->failWithValidationErrors();

		$createFields = ['supplier_id', 'part_number', 'price', 'tax'];

		$data = $this->buildCreateArray($createFields, true);

		// Make sure item exists
		if(!$this->items->find($itemId))
			return $this->failNotFound(lang('Errors.items.not_found', ['id' => $itemId]));

		// Make sure supplier exists
		if(!$this->suppliers->find($data['supplier_id']))
			return $this->failNotFound(lang('Errors.items.supplier_not_found', ['id' => $data['supplier_id']]));

		// Make sure this relation doesn't exist already
		if($this->item_suppliers->getItemSupplier($itemId, $data['supplier_id']))
			return $this->failResourceExists(lang('Errors.items.already_exists_supplier', ['item_id' => $itemId, 'supplier_id' => $data['supplier_id']]));

		$data['item_id'] = $itemId;

		$this->item_suppliers->insert($data);

		$new_relation = $this->item_suppliers->getItemSupplier($itemId, $data['supplier_id']);

		return $this->respondCreated($new_relation);
	}

	/**
	 * To edit a supplier-item relation
	 * 
	 * Method			PUT
	 * Filter			auth:admin
	 */
	public function update_supplier($itemId, $supplierId) {
		if(!$this->validateRequestWithRules($this->rules->update_supplier))
			return $this->failWithValidationErrors();

		$updateFields = ['part_number', 'price', 'tax'];

		$data = $this->buildUpdateArray($updateFields, true);

		// Make sure item exists
		if(!$this->items->find($itemId))
			return $this->failNotFound(lang('Errors.items.not_found', ['id' => $itemId]));

		// Make sure supplier exists
		if(!$this->suppliers->find($supplierId))
			return $this->failNotFound(lang('Errors.items.supplier_not_found', ['id' => $data['supplier_id']]));

		// Make sure the relation exists
		if(!$this->item_suppliers->getItemSupplier($itemId, $supplierId))
			return $this->failResourceExists(lang('Errors.items.item_supplier_not_found', ['item_id' => $itemId, 'supplier_id' => $supplierId]));

		$this->item_suppliers->updateItemSupplier($itemId, $supplierId, $data);

		$updated = $this->item_suppliers->getItemSupplier($itemId, $supplierId);

		return $this->respondUpdated($updated);
	}

	/**
	 * To delete a supplier-item relation
	 * 
	 * Method			DELETE
	 * Filter			auth:admin
	 */
	public function remove_supplier($itemId, $supplierId) {
		// Make sure item exists
		if(!$this->items->find($itemId))
			return $this->failNotFound(lang('Errors.items.not_found', ['id' => $itemId]));

		// Make sure supplier exists
		if(!$this->suppliers->find($supplierId))
			return $this->failNotFound(lang('Errors.items.supplier_not_found', ['id' => $supplierId]));

		// Make sure the relation exists
		if(!$this->item_suppliers->getItemSupplier($itemId, $supplierId))
			return $this->failResourceExists(lang('Errors.items.item_supplier_not_found', ['item_id' => $itemId, 'supplier_id' => $supplierId]));

		$this->item_suppliers->removeItemSupplier($itemId, $supplierId);

		return $this->respondDeleted([
			'item_id' => $itemId,
			'supplier_id' => $supplierId
		]);
	}

	/**
	 * To get a simple list of items, ID and name. Search is allowed, as well as limiting
	 * by supplier
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function list() {
		// die('item list');
		
		$search = $this->input->get('search', TRUE) ?? '';
		$vendor_id = $this->input->get('vendor', TRUE) ?? '';
        $company_id = $this->session->userdata['company_id'];
        // die(print_r($company_id));

		if($vendor_id == '') {
			$items = $this->ItemModel->getItemsList($search);
		}else{
			$items = $this->ItemModel->getItemsListLimitedByVendor($search, $vendor_id, $company_id);
		}
		$return_array =  array( 'result' => $items);
		
		echo json_encode($return_array);
	}
	
	/**
	 * To export a CSV file with all items and quantities (admins only)
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function export() {
		// Get list of items, with as much information as we can get
		$items = $this->items->getDetailedList();

		// Now, loop through every item, getting quantities and attaching them
		// to every one of them
		foreach($items as &$item) {
			$quantities = $this->quantities->getItemQuantities($item->id);

			foreach($quantities as $quantity)
				$item->{$quantity['warehouse']['name']} = $quantity['quantity'];
		}

		// Create a filename and export!
		$filename = date('Y_m_d__H_i_s');
		$filename = "items__{$filename}.csv";

		helper('csv');

		die(offer_csv_download($items, $filename));
	}

	/**
	 * To update quantity alerts for a given item
	 * 
	 * $itemIds can be a single ID, or an array of IDs
	 */
	private function updateAlerts($itemIds) {
		if(!is_array($itemIds))
			$itemIds = [$itemIds];

		foreach($itemIds as $itemId) {
			// Delete alerts (if they exist), because we'll create a new one
			// if we need to
			$this->alerts->deleteAlertsForItem($itemId);

			// Get item information
			$item = $this->items->getItem($itemId);
			$item->quantities = $this->quantities->getItemQuantities($itemId);

			// If there are no min/max alerts set, end here
			if($item->min_alert == null && $item->max_alert == null)
				return;

			// At this point we have alerts set.. Loop through quantities looking for
			// one that exceeds limits set
			foreach($item->quantities as $quantity) {
				if($item->min_alert != null && $quantity['quantity'] <= $item->min_alert) {
					// Minimum alert triggered! Save it and continue
					$this->alerts->triggerMinAlert($itemId, $quantity['warehouse']['id'], $quantity['quantity']);
				}else if($item->max_alert != null && $quantity['quantity'] >= $item->max_alert) {
					// Maximum alert triggered! Save it and continue
					$this->alerts->triggerMaxAlert($itemId, $quantity['warehouse']['id'], $quantity['quantity']);
				}
			}
		}
	}
}