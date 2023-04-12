<?php 

class PurchasesReceivingModel extends CI_Model {
	const RECEIVINGTBL="purchase_receiving_tbl";
	const PURCHASETBL="purchase_order_tbl";
	protected $table = 'purchase_receiving_tbl';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $allowedFields = [
		'id',
		'reference',
		'purchase_id',
		'items',
		'shipping_cost',
		'discount',
		'tax',
		'subtotal',
		'grand_total',
		'created_by',
		'created_at',
		'updated_at',
		'notes',
		'deleted_at'
	];

	protected $useTimestamps = true;
	protected $useSoftDeletes = true;
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
	protected $deletedField = 'deleted_at';

	// DataTables parameters
	private $dtSearch;
	private $dtOrderBy;
	private $dtOrderDir;
	private $dtLength;
	private $dtStart;

	// To load DataTables parameters
	public function setDtParameters($search, $orderBy, $orderDir, $length, $start) {
		$this->dtSearch = $search;
		$this->dtOrderBy = $orderBy;
		$this->dtOrderDir = $orderDir;
		$this->dtLength = $length;
		$this->dtStart = $start;
	}

	// To get all purchase returns -- Adapted to DataTables
	// Results will vary depending on the user that is requesting
	// them (if $limitByWarehouses is true, we'll limit results to the
	// warehosue IDs provided in $warehouseIds)
	public function dtGetAllReturns(bool $limitByWarehouses = false, array $warehouseIds = []) {
		$recordsTotal = $this->select('inventov2_purchases_returns.*');

		// Should we limit by warehouses? (If user is worker/supervisor)
		if($limitByWarehouses) {
			$recordsTotal = $recordsTotal
				->join('inventov2_purchases AS _purchase', '_purchase.id = inventov2_purchases_returns.purchase_id', 'left');
			$recordsTotal = $this->restrictQueryByIds($recordsTotal, '_purchase.warehouse_id', $warehouseIds);
		}

		$recordsTotal = $recordsTotal->countAllResults();

		$returns = $this
			->select('inventov2_purchases_returns.id AS DT_RowId,
								inventov2_purchases_returns.reference,
								_purchase.reference AS purchase_reference,
								_warehouse.name AS warehouse_name,
								inventov2_purchases_returns.created_at,
								_supplier.name AS supplier_name,
								inventov2_purchases_returns.grand_total')
			->groupStart()
			->orLike('inventov2_purchases_returns.reference', $this->dtSearch)
			->orLike('_purchase.reference', $this->dtSearch)
			->orLike('_warehouse.name', $this->dtSearch)
			->orLike('_supplier.name', $this->dtSearch)
			->groupEnd()
			->join('inventov2_purchases AS _purchase', '_purchase.id = inventov2_purchases_returns.purchase_id', 'left')
			->join('inventov2_warehouses AS _warehouse', '_warehouse.id = _purchase.warehouse_id', 'left')
			->join('inventov2_suppliers AS _supplier', '_supplier.id = _purchase.supplier_id')
			->orderBy($this->dtOrderBy, $this->dtOrderDir)
			->limit($this->dtLength, $this->dtStart)
			->groupBy('inventov2_purchases_returns.id');

		// Should we limit by warehouse?
		if($limitByWarehouses)
			$returns = $this->restrictQueryByIds($returns, '_purchase.warehouse_id', $warehouseIds);

		$recordsFiltered = $returns->countAllResults(false);
		$data = $returns->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}
	public function getAllReceiving($where_arr) {
		$this->db->select('purchase_receiving_tbl.*, purchase_order_tbl.*, vendor_name, location_name, sub_location_name', false);

		$this->db->from('purchase_receiving_tbl');
		$this->db->join('purchase_order_tbl', 'purchase_order_tbl.purchase_order_id = purchase_receiving_tbl.purchase_order_id', 'left');
		$this->db->join('vendors_tbl','vendors_tbl.vendor_id = purchase_receiving_tbl.vendor_id ','inner');
		$this->db->join('locations_tbl','locations_tbl.location_id = purchase_receiving_tbl.location_id ','inner');
		$this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_receiving_tbl.sub_location_id','left');

		if (is_array($where_arr)) {
			$this->db->where($where_arr);
		}

		$this->db->order_by('purchase_receiving_tbl.purchase_receiving_id','asc');
		$result = $this->db->get();
		$data = $result->result();

		// die(print_r($this->db->last_query()));
		return $data;
	}

	// To get a detailed list of all purchase returns
	public function getDetailedList() {
		$returns = $this
			->select('inventov2_purchases_returns.id,
								inventov2_purchases_returns.reference,
								inventov2_purchases_returns.purchase_id,
								inventov2_purchases_returns.shipping_cost,
								inventov2_purchases_returns.discount,
								inventov2_purchases_returns.tax,
								inventov2_purchases_returns.subtotal,
								inventov2_purchases_returns.grand_total,
								inventov2_purchases_returns.created_by,
								inventov2_purchases_returns.created_at,
								inventov2_purchases_returns.updated_at,
								inventov2_purchases_returns.notes,
								_supplier.id AS supplier_id,
								_supplier.name AS supplier_name,
								_warehouse.id AS warehouse_id,
								_warehouse.name AS warehouse_name,
								_user.username AS created_by_username,
								_user.name AS created_by_name')
			->join('inventov2_purchases AS _purchase', '_purchase.id = inventov2_purchases_returns.purchase_id', 'left')
			->join('inventov2_suppliers AS _supplier', '_supplier.id = _purchase.supplier_id', 'left')
			->join('inventov2_warehouses AS _warehouse', '_warehouse.id = _purchase.warehouse_id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_purchases_returns.created_by', 'left')
			->orderBy('inventov2_purchases_returns.id', 'ASC')
			->find();

		if(!$returns)
			return [];
		
		return $returns;
	}

	public function getReceiving($where) {
		$this->db->select("purchase_receiving_tbl.*,purchase_order_tbl.purchase_order_number, purchase_receiving_tbl.items, purchase_receiving_tbl.freight, purchase_receiving_tbl.discount, discount_type, tax, subtotal, grand_total, purchase_order_tbl.created_at, purchase_order_tbl.updated_at, purchase_order_status, purchase_order_tbl.notes, purchase_order_tbl.created_by AS created_by_id, CONCAT(user_first_name,' ', user_last_name) AS name, vendors_tbl.vendor_id, vendors_tbl.vendor_name, vendor_street_address, vendor_city, vendor_state, vendor_zip_code, vendor_country, locations_tbl.location_name,  sub_locations_tbl.sub_location_name");

        $this->db->from('purchase_receiving_tbl');
        $this->db->join('purchase_order_tbl', 'purchase_order_tbl.purchase_order_id = purchase_receiving_tbl.purchase_order_id', 'left');
        $this->db->join('users', 'users.id = purchase_order_tbl.created_by', 'left');
        $this->db->join('vendors_tbl', 'vendors_tbl.vendor_id = purchase_order_tbl.vendor_id', 'left');
        $this->db->join('locations_tbl', 'locations_tbl.location_id = purchase_receiving_tbl.location_id', 'left');
        $this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_receiving_tbl.sub_location_id','left');
    
        if (is_array($where)) {
            $this->db->where($where);
        }

        $result = $this->db->get();
        $data = $result->result();
		
		// die(print_r($this->db->last_query()));
       
        return $data;
		
	}

	public function getPurchaseReturn($purchaseId) {
		$return = $this
		->select('inventov2_purchases_returns.id,
							inventov2_purchases_returns.reference,
							inventov2_purchases_returns.purchase_id,
							inventov2_purchases_returns.items AS return_items,
							inventov2_purchases_returns.shipping_cost,
							inventov2_purchases_returns.discount,
							inventov2_purchases_returns.tax,
							inventov2_purchases_returns.subtotal,
							inventov2_purchases_returns.grand_total,
							inventov2_purchases_returns.created_at,
							inventov2_purchases_returns.updated_at,
							inventov2_purchases_returns.updated_at,
							inventov2_purchases_returns.notes,
							_warehouse.id AS warehouse_id,
							_warehouse.name AS warehouse_name,
							_supplier.id AS supplier_id,
							_supplier.name AS supplier_name,
							_supplier.address AS supplier_address,
							_supplier.city AS supplier_city,
							_supplier.state AS supplier_state,
							_supplier.zip_code AS supplier_zip_code,
							_supplier.country AS supplier_country,
							_user.id AS created_by_id,
							_user.name AS created_by_name,
							_purchase.id AS purchase_id,
							_purchase.reference AS purchase_reference,
							_purchase.items AS purchase_items')
		->join('inventov2_purchases AS _purchase', '_purchase.id = inventov2_purchases_returns.purchase_id', 'left')
		->join('inventov2_warehouses AS _warehouse', '_warehouse.id = _purchase.warehouse_id', 'left')
		->join('inventov2_suppliers AS _supplier', '_supplier.id = _purchase.supplier_id', 'left')
		->join('inventov2_users AS _user', '_user.id = inventov2_purchases_returns.created_by', 'left')
		->where('_purchase.id', $purchaseId)
		->first();

		if(!$return)
			return false;

		$grouper = new JsonGrouper(['warehouse', 'supplier', 'created_by', 'purchase'], $return);
		$grouped = $grouper->group();

		$purchase_items = json_decode($grouped->purchase->items);
		$return_items = json_decode($grouped->return_items);

		$items = [];
		
		foreach($purchase_items AS $purchase_item) {
			$newItem = $purchase_item;

			foreach($return_items AS $return_item) {
				if($return_item->id == $purchase_item->id)
					$newItem->qty_to_return = $return_item->qty_to_return;
			}

			$items[] = $newItem;
		}

		unset($grouped->purchase->items);
		unset($grouped->return_items);
		$grouped->items = $items;

		return $grouped;
	}

	public function getReturnByReference($reference) {
		return $this->where('reference', $reference)->first();
	}

	// To get stats for purchases returns
	// Results will vary depending on the user that is requesting
	// them (if $limitByWarehouses is true, we'll limit results to the
	// warehouse IDs provided in $warehouseIds)
	public function statPurchasesReturns($fromDate, $toDate, bool $limitByWarehouses = false, array $warehouseIds = []) {
		$returns = $this
			->selectSum('inventov2_purchases_returns.grand_total')
			->where("inventov2_purchases_returns.created_at BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'")
			->groupBy('inventov2_purchases_returns.id');

		if($limitByWarehouses) {
			$returns = $returns
				->join('inventov2_purchases AS _purchases', '_purchases.id = inventov2_purchases_returns.purchase_id', 'left');
			$returns = $this->restrictQueryByIds($returns, '_purchases.warehouse_id', $warehouseIds);
		}

		$returns = $returns->first();

		return (!$returns) ? 0 : $returns->grand_total;
	}

	// To get stats for purchases returns, to be graphed, with range (between date A and date B)
	public function statPurchasesReturnsForGraphWithRange($fromDate, $toDate, bool $limitByWarehouses = false, array $warehouseIds = []) {
		$returns = $this
			->select("SUM(inventov2_purchases_returns.grand_total) AS grand_total,
								DATE_FORMAT(inventov2_purchases_returns.created_at, '%Y-%m-%d') AS created_at")
			->where("inventov2_purchases_returns.created_at BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'")
			->groupBy('DAY(inventov2_purchases_returns.created_at)');

		if($limitByWarehouses) {
			$returns = $returns->join('inventov2_purchases AS _purchases', '_purchases.id = inventov2_purchases_returns.purchase_id', 'left');
			$returns = $this->restrictQueryByIds($returns, '_purchases.warehouse_id', $warehouseIds);
		}

		$returns = $returns->find();

		return (!$returns) ? [] : $returns;
	}

	// To get stats for purchases returns, to be graphed, with year
	public function statPurchasesReturnsForGraphWithYear($year, bool $limitByWarehouses = false, array $warehouseIds = []) {
		$returns = $this
			->select("SUM(inventov2_purchases_returns.grand_total) AS grand_total,
								DATE_FORMAT(inventov2_purchases_returns.created_at, '%Y-%m') AS created_at")
			->where("YEAR(inventov2_purchases_returns.created_at) = '{$year}'")
			->groupBy('MONTH(inventov2_purchases_returns.created_at)');

		if($limitByWarehouses) {
			$returns = $returns->join('inventov2_purchases AS _purchases', '_purchases.id = inventov2_purchases_returns', 'left');
			$returns = $this->restrictQueryByIds($returns, '_purchases.warehouse_id', $warehouseIds);
		}

		$returns = $returns->find();

		return (!$returns) ? [] : $returns;
	}

	/**
	 * This function will restrict a query, so that $column only has
	 * the values provided in the $ids array
	 */
	private function restrictQueryByIds($query, string $column, array $ids) {
		if(count($ids) == 0)
			$query->where('1=0', null, false);
		else{
			$query->groupStart();
			foreach($ids as $id)
				$query->orWhere($column, $id);
			$query->groupEnd();
		}

		return $query;
	}

	public function insert_purchase_receiving($post) {
		$query = $this->db->insert(self::RECEIVINGTBL, $post);
		return $this->db->insert_id();
	}

	public function updateReceivingOrder($where_arr, $update_arr) {

        // $this->db->where($where_arr);
        // $this->db->update(self::INVTBL, $update_arr);
        // return $a = $this->db->affected_rows();
        
        $this->db->where($where_arr);
        $this->db->update(self::RECEIVINGTBL, $update_arr);
        $this->db->where($where_arr);

        $result = $this->db->get(self::RECEIVINGTBL);

        $data = $result->row('purchase_receiving_id');

        // die(print_r($this->db->last_query()));

        return $data;

    }

	public function getOneReceiving($where) {
		$this->db->select("purchase_receiving_tbl.*,purchase_order_tbl.purchase_order_number, purchase_receiving_tbl.items, purchase_receiving_tbl.freight, purchase_receiving_tbl.discount, discount_type, tax, subtotal, grand_total, purchase_order_tbl.created_at, purchase_order_tbl.updated_at, purchase_order_status, purchase_order_tbl.notes, purchase_order_tbl.created_by AS created_by_id, CONCAT(user_first_name,' ', user_last_name) AS name, vendors_tbl.vendor_id, vendors_tbl.vendor_name, company_name, vendor_email_address, vendor_phone_number, vendor_street_address, vendor_city, vendor_state, vendor_zip_code, vendor_country, locations_tbl.location_name, location_street, location_city, location_state, location_zip, location_phone,  sub_locations_tbl.sub_location_name");

        $this->db->from('purchase_receiving_tbl');
        $this->db->join('purchase_order_tbl', 'purchase_order_tbl.purchase_order_id = purchase_receiving_tbl.purchase_order_id', 'left');
        $this->db->join('users', 'users.id = purchase_order_tbl.created_by', 'left');
        $this->db->join('vendors_tbl', 'vendors_tbl.vendor_id = purchase_order_tbl.vendor_id', 'left');
        $this->db->join('locations_tbl', 'locations_tbl.location_id = purchase_receiving_tbl.location_id', 'left');
        $this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_receiving_tbl.sub_location_id','left');
    
        if (is_array($where)) {
            $this->db->where($where);
        }


        $result = $this->db->get();
        $data = $result->row();
		
		// die(print_r($this->db->last_query()));
       
        return $data;
	}

    
    public function getAlreadyExistingQuantities($where)
    {
        $this->db->select('*');
        $this->db->from('quantities');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->row();
        return $data;
    }

    public function getAlreadyExistingQuantity($where)
    {
        $this->db->select('*');
        $this->db->from('quantities');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        $quant = 0;
        if(!empty($data)){
            foreach($data as $d){
                $quant += $d->quantity;
            }
        }
        return $quant;
    }



    public function getItemProductInfo($where)
    {
        $this->db->select('*');
        $this->db->from('item_product_tbl');
        $this->db->join('products', 'products.product_id = item_product_tbl.product_id');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getCurrentAverageCostPerUnit($item_id){
        $this->db->select('items_tbl.average_cost_per_unit');
        $this->db->from('items_tbl');
        $this->db->where('item_id', $item_id);
        $result = $this->db->get();
        $data = $result->row();

        // die(print_r($this->db->last_query()));
        return $data;
    }

    public function getUnitAmount($item_id){
        $this->db->select('*');
        $this->db->from('items_tbl');
        $this->db->where('item_id', $item_id);
        $result = $this->db->get();
        $data = $result->row();
        if(!empty($data)){
            return $data->unit_amount;
        }
    }

		public function getAllReceivingTable() {
		$this->db->select('purchase_receiving_tbl.*, purchase_order_tbl.*, vendor_name, location_name, sub_location_name', false);
		$this->db->from('purchase_receiving_tbl');
		$this->db->join('purchase_order_tbl', 'purchase_order_tbl.purchase_order_id = purchase_receiving_tbl.purchase_order_id', 'left');
		$this->db->join('vendors_tbl','vendors_tbl.vendor_id = purchase_receiving_tbl.vendor_id ','inner');
		$this->db->join('locations_tbl','locations_tbl.location_id = purchase_receiving_tbl.location_id ','inner');
		$this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_receiving_tbl.sub_location_id','left');

		$company_id = $this->session->userdata['company_id'];
		$where = "purchase_order_tbl.company_id= '$company_id' AND purchase_order_status= '1' OR purchase_order_status= '2'";
		
		$this->db->where($where);

		$this->db->order_by('purchase_receiving_tbl.purchase_receiving_id','asc');
		$result = $this->db->get();
		$data = $result->result();

		// die(print_r($this->db->last_query()));
		return $data;
	}

	public function deletePurchaseReceivingOrder($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::RECEIVINGTBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

}