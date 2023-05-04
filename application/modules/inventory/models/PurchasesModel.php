<?php 

class PurchasesModel extends CI_Model {
	const PURCHASETBL="purchase_order_tbl";
	const PURCHASEINV="po_invoice_tbl";
	protected $table = 'purchase_order_tbl';
	protected $primaryKey = 'purchase_order_id';

	protected $returnType = 'object';
	protected $allowedFields = [
		'purchase_order_id',
		'purchase_order_number',
		'purchase_order_date',
		'vendor_id',
		'location_id',
		'items',
		'created_date',
		'ordered_date',
		'expected_date',
		'unit_measrement',
		'shipping_point',
		'shipping_method_1',
		'destination',
		'place_of_origin',
		'place_of_destination',
		'payment_terms'
	];

	protected $useTimestamps = true;
	protected $useSoftDeletes = true;
	// protected $createdField = 'created_at';
	// protected $updatedField = 'updated_at';
	// protected $deletedField = 'deleted_at';

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

	// To get all purchases -- Adapted to DataTables
	// Results will vary depending on the user that is requesting
	// them (if $limitByWarehouses is true, we'll limit results to the
	// warehouse IDs provided in $warehouseIds)
	public function dtGetAllPurchases(bool $limitByWarehouses = false, array $warehouseIds = []) {
		$recordsTotal = $this->select('inventov2_purchases.*');

		// Should we limit by warehouses? (If user is worker/supervisor)
		if($limitByWarehouses)
			$recordsTotal = $this->restrictQueryByIds($recordsTotal, 'inventov2_purchases.warehouse_id', $warehouseIds);

		$recordsTotal = $recordsTotal->countAllResults();

		$purchases = $this
			->select('inventov2_purchases.id AS DT_RowId,
								inventov2_purchases.reference,
								_warehouse.name AS warehouse_name,
								inventov2_purchases.created_at,
								_supplier.name AS supplier_name,
								inventov2_purchases.grand_total')
			->groupStart()
			->orLike('inventov2_purchases.reference', $this->dtSearch)
			->orLike('_warehouse.name', $this->dtSearch)
			->orLike('_supplier.name', $this->dtSearch)
			->groupEnd()
			->join('inventov2_warehouses AS _warehouse', '_warehouse.id = inventov2_purchases.warehouse_id', 'left')
			->join('inventov2_suppliers AS _supplier', '_supplier.id = inventov2_purchases.supplier_id', 'left')
			->orderBy($this->dtOrderBy, $this->dtOrderDir)
			->limit($this->dtLength, $this->dtStart)
			->groupBy('inventov2_purchases.id');

		// Should we limit by warehouse?
		if($limitByWarehouses)
			$purchases = $this->restrictQueryByIds($purchases, 'inventov2_purchases.warehouse_id', $warehouseIds);

		$recordsFiltered = $purchases->countAllResults(false);
		$data = $purchases->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}
	public function getAllPurchases($where_arr = '') {
		$this->db->select('purchase_order_tbl.*, location_name, sub_location_name, vendor_name', false);
		$this->db->from('purchase_order_tbl');
		$this->db->join('locations_tbl','locations_tbl.location_id = purchase_order_tbl.location_id ','inner');
		$this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_order_tbl.sub_location_id','left');
		$this->db->join('vendors_tbl','vendors_tbl.vendor_id = purchase_order_tbl.vendor_id ','inner');

		// $this->db->limit($limit, $start);

		if (is_array($where_arr)) {

			$this->db->where($where_arr);

		}

		// $this->db->order_by($col,$dir);
		$this->db->order_by('purchase_order_tbl.purchase_order_id','asc');
        $result = $this->db->get();
        $data = $result->result();
		// die(print_r($this->db->last_query()));

		return $data;

	}

	// To get a detailed list of all purchases
	public function getDetailedList() {
		$purchases = $this
			->select('inventov2_purchases.id,
								inventov2_purchases.reference,
								inventov2_purchases.supplier_id,
								inventov2_purchases.warehouse_id,
								inventov2_purchases.shipping_cost,
								inventov2_purchases.discount,
								inventov2_purchases.discount_type,
								inventov2_purchases.tax,
								inventov2_purchases.subtotal,
								inventov2_purchases.grand_total,
								inventov2_purchases.created_by,
								inventov2_purchases.created_at,
								inventov2_purchases.updated_at,
								inventov2_purchases.notes,
								_supplier.name AS supplier_name,
								_warehouse.name AS warehouse_name,
								_user.username AS created_by_username,
								_user.name AS created_by_name')
			->join('inventov2_suppliers AS _supplier', '_supplier.id = inventov2_purchases.supplier_id', 'left')
			->join('inventov2_warehouses AS _warehouse', '_warehouse.id = inventov2_purchases.warehouse_id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_purchases.created_by', 'left')
			->orderBy('inventov2_purchases.id', 'ASC')
			->find();

		if(!$purchases)
			return [];
		
		return $purchases;
	}

	// To get 5 most recent purchases -- Without DataTables features
	// Results will vary depending on the user that is requesting
	// them (if $limitByWarehouses is true, we'll limit results to the
	// warehouse IDs provided in $warehouseIds)
	public function dtGetLatest(bool $limitByWarehouses = false, array $warehouseIds = []) {
		$data = $this
			->select('inventov2_purchases.id AS DT_RowId,
								inventov2_purchases.created_at,
								inventov2_purchases.reference,
								_supplier.name AS supplier_name,
								inventov2_purchases.grand_total')
			->join('inventov2_purchases_returns AS _return', '_return.purchase_id = inventov2_purchases.id', 'left')
			->join('inventov2_suppliers AS _supplier', '_supplier.id = inventov2_purchases.supplier_id', 'left')
			->groupBy('inventov2_purchases.id')
			->orderBy('inventov2_purchases.created_at', 'DESC')
			->limit(5);

		// Should we limit by warehouse?
		if($limitByWarehouses)
			$data = $this->restrictQueryByIds($data, 'inventov2_purchases.warehouse_id', $warehouseIds);

		$recordsFiltered = $data->countAllResults(false);
		$recordsTotal = $recordsFiltered;
		$data = $data->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}

	// To get a single purchase by ID
	public function getPurchase($where) {
		// $this->db->select("purchase_order_tbl.*, locations_tbl.location_name, vendors_tbl.vendor_name ");
        $this->db->select("purchase_order_tbl.purchase_order_id,purchase_order_tbl.purchase_order_number, purchase_order_tbl.items, purchase_order_tbl.sent_date, purchase_order_tbl.open_date, purchase_order_tbl.estimated_delivery_date, purchase_order_tbl.freight, purchase_order_tbl.discount, discount_type, purchase_order_tbl.tax, purchase_order_tbl.subtotal, purchase_order_tbl.grand_total, purchase_order_tbl.created_at, purchase_order_tbl.updated_at, purchase_order_status, purchase_order_tbl.notes, purchase_order_tbl.created_by AS created_by_id, is_receiving, is_returned, is_complete, CONCAT(user_first_name,' ', user_last_name) AS name, vendors_tbl.vendor_id, vendors_tbl.vendor_name, vendor_street_address, vendor_city, vendor_state, vendor_zip_code, vendor_country, company_name, vendor_email_address, vendor_phone_number, locations_tbl.location_id, locations_tbl.location_name, locations_tbl.location_street, locations_tbl.location_city, locations_tbl.location_state, locations_tbl.location_zip, locations_tbl.location_country, locations_tbl.location_phone, sub_locations_tbl.sub_location_id, sub_locations_tbl.sub_location_name, return_id, purchase_order_tbl.purchase_sent_status, purchase_order_tbl.created_date, purchase_order_tbl.ordered_date, purchase_order_tbl.expected_date, purchase_order_tbl.unit_measrement, purchase_order_tbl.shipping_point, purchase_order_tbl.payment_terms, purchase_order_tbl.shipping_method_1, fob, purchase_order_tbl.destination, purchase_order_tbl.place_of_origin, purchase_order_tbl.place_of_destination, purchase_order_tbl.paid_attachment");

        $this->db->from('purchase_order_tbl');
        $this->db->join('users', 'users.id = purchase_order_tbl.created_by', 'left');
        $this->db->join('vendors_tbl', 'vendors_tbl.vendor_id = purchase_order_tbl.vendor_id', 'left');
        $this->db->join('locations_tbl', 'locations_tbl.location_id = purchase_order_tbl.location_id', 'left');
        $this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_order_tbl.sub_location_id','left');
        $this->db->join('purchase_return_tbl', 'purchase_return_tbl.purchase_order_id = purchase_order_tbl.purchase_order_id', 'left');
    
        if (is_array($where)) {
            $this->db->where($where);
        }

        // if (is_array($where_like)) {
        //     $this->db->like($where_like);
        // }


        // $this->db->order_by($col,$dir);

        // if ($is_for_count == false) {
        //     $this->db->limit($limit, $start);
        // }

        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');

        $result = $this->db->get();
        $data = $result->result();
		// die(print_r($this->db->last_query()));
        
        return $data;

	}

	public function getPurchaseByReference($reference) {
		$purchase = $this
			->select('inventov2_purchases.id,
								inventov2_purchases.reference,
								inventov2_purchases.items,
								inventov2_purchases.shipping_cost,
								inventov2_purchases.discount,
								inventov2_purchases.discount_type,
								inventov2_purchases.tax,
								inventov2_purchases.subtotal,
								inventov2_purchases.grand_total,
								inventov2_purchases.created_at,
								inventov2_purchases.updated_at,
								inventov2_purchases.created_by AS created_by_id,
								_user.name AS created_by_name,
								_supplier.id AS supplier_id,
								_supplier.name AS supplier_name,
								_supplier.address AS supplier_address,
								_supplier.city AS supplier_city,
								_supplier.state AS supplier_state,
								_supplier.zip_code AS supplier_zip_code,
								_supplier.country AS supplier_country,
								_warehouse.id AS warehouse_id,
								_warehouse.name AS warehouse_name,
								_return.id AS return_id')
			->join('inventov2_users AS _user', '_user.id = inventov2_purchases.created_by', 'left')
			->join('inventov2_suppliers AS _supplier', '_supplier.id = inventov2_purchases.supplier_id', 'left')
			->join('inventov2_warehouses AS _warehouse', '_warehouse.id = inventov2_purchases.warehouse_id', 'left')
			->join('inventov2_purchases_returns AS _return', '_return.purchase_Id = inventov2_purchases.id', 'left')
			->where('inventov2_purchases.reference', $reference)
			->groupBy('inventov2_purchases.id')
			->first();

		if(!$purchase)
			return false;

		$grouper = new JsonGrouper(['created_by', 'supplier', 'warehouse'], $purchase);
		$grouped = $grouper->group();

		$grouped->items = json_decode($grouped->items);

		return $grouped;
	}

	// To get stats for purchases
	// Results will vary depending on the user that is requesting
	// them (if $limitByWarehouses is true, we'll limit results to the
	// warehouse IDs provided in $warehouseIds)
	public function statPurchases($fromDate, $toDate, bool $limitByWarehouses = false, array $warehouseIds = []) {
		$purchases = $this
			->selectSum('grand_total')
			->where("created_at BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'")
			->groupBy('inventov2_purchases.id');

		if($limitByWarehouses)
			$purchases = $this->restrictQueryByIds($purchases, 'inventov2_purchases.warehouse_id', $warehouseIds);

		$purchases = $purchases->first();

		return (!$purchases) ? 0 : $purchases->grand_total;
	}

	// To get stats for purchases, to be graphed, with range (between date A and date B)
	public function statPurchasesForGraphWithRange($fromDate, $toDate, bool $limitByWarehouses = false, array $warehouseIds = []) {
		$purchases = $this
			->select("SUM(inventov2_purchases.grand_total) AS grand_total,
								DATE_FORMAT(inventov2_purchases.created_at, '%Y-%m-%d') AS created_at")
			->where("inventov2_purchases.created_at BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'")
			->groupBy('DAY(inventov2_purchases.created_at)');

		if($limitByWarehouses)
			$purchases = $this->restrictQueryByIds($purchases, 'inventov2_purchases.warehouse_id', $warehouseIds);

		$purchases = $purchases->find();

		return (!$purchases) ? [] : $purchases;
	}

	// To get stats for purchases, to be graphed, with year
	public function statPurchasesForGraphWithYear($year, bool $limitByWarehouses = false, array $warehouseIds = []) {
		$purchases = $this
			->select("SUM(inventov2_purchases.grand_total) AS grand_total,
								DATE_FORMAT(inventov2_purchases.created_at, '%Y-%m') AS created_at")
			->where("YEAR(inventov2_purchases.created_at) = '{$year}'")
			->groupBy('MONTH(inventov2_purchases.created_at)');

		if($limitByWarehouses)
			$purchases = $this->restrictQueryByIds($purchases, 'inventov2_purchases.warehouse_id', $warehouseIds);

		$purchases = $purchases->find();

		return (!$purchases) ? [] : $purchases;
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

	public function getPurchaseDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        // $this->db->select("purchase_order_tbl.*, locations_tbl.location_name, vendors_tbl.vendor_name ");
        $this->db->select("*");

        $this->db->from('purchase_order_tbl');

        $this->db->join('locations_tbl','locations_tbl.location_id = purchase_order_tbl.location_id','left');
        $this->db->join('vendors_tbl','vendors_tbl.vendor_id = purchase_order_tbl.vendor_id','left');
    
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }


        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');

        $result = $this->db->get();
        $data = $result->result();

        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
		// $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        return $data;
    }

	public function getPurchaseAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count)
    {
		$this->db->select("purchase_order_tbl.*, locations_tbl.location_name, vendors_tbl.vendor_name ");

        $this->db->from('purchase_order_tbl');

        $this->db->join('locations_tbl','locations_tbl.location_id = purchase_order_tbl.location_id','left');
        $this->db->join('vendors_tbl','vendors_tbl.vendor_id = purchase_order_tbl.vendor_id','left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->group_start();
        $this->db->or_like('purchase_order_number',$search);
		$this->db->or_like('locations_tbl.location_name',$search);
		$this->db->or_like('vendor_name',$search);
		$this->db->group_end();

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

	public function getCreatedByName($user_id){
        $this->db->select('*');
        $this->db->from('users');

    
            $this->db->where('id', $user_id);

        $result = $this->db->get();

        $data = $result->result();

        foreach($data as $name){
            return $name->user_first_name . ' ' . $name->user_last_name;
        }

        
    }

	public function insert_purchase_order($post) {
		$query = $this->db->insert(self::PURCHASETBL, $post);
		return $this->db->insert_id();
	}

	public function getOnePurchase($where) {
        $this->db->select("purchase_order_tbl.purchase_order_id, purchase_order_tbl.ordered_date, purchase_order_tbl.company_id, purchase_order_tbl.purchase_order_number, purchase_order_tbl.purchase_order_date, purchase_order_tbl.items, purchase_order_tbl.freight, purchase_order_tbl.discount, discount_type, tax, subtotal, grand_total, purchase_order_tbl.created_at, purchase_order_tbl.updated_at, purchase_order_tbl.purchase_sent_status, purchase_order_tbl.purchase_order_status, purchase_order_tbl.purchase_paid_status, purchase_order_tbl.estimated_delivery_date, purchase_order_tbl.payment_terms, purchase_order_tbl.shipping_method, purchase_order_tbl.notes, purchase_order_tbl.created_by AS created_by_id, purchase_order_tbl.is_archived, CONCAT(user_first_name,' ', user_last_name) AS name, vendors_tbl.vendor_id, vendors_tbl.vendor_name, vendor_street_address, vendor_city, vendor_state, vendor_zip_code, vendor_country, company_name, vendor_email_address, vendor_phone_number, locations_tbl.location_id, locations_tbl.location_name, locations_tbl.location_street, locations_tbl.location_city, locations_tbl.location_state, locations_tbl.location_zip, locations_tbl.location_country, locations_tbl.location_phone, sub_locations_tbl.sub_location_id, sub_locations_tbl.sub_location_name, purchase_order_tbl.place_of_origin, purchase_order_tbl.place_of_destination");

        $this->db->from('purchase_order_tbl');
        $this->db->join('users', 'users.id = purchase_order_tbl.created_by', 'left');
        $this->db->join('vendors_tbl', 'vendors_tbl.vendor_id = purchase_order_tbl.vendor_id', 'left');
        $this->db->join('locations_tbl', 'locations_tbl.location_id = purchase_order_tbl.location_id', 'left');
        $this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_order_tbl.sub_location_id','left');
    
        if (is_array($where)) {
            $this->db->where($where);
        }

        $result = $this->db->get();
        $data = $result->row();
					
        return $data;

	}

	public function updatePurchaseOrder($wherearr, $updatearr) {
        $this->db->where($wherearr);
        $this->db->update(self::PURCHASETBL, $updatearr);
        $this->db->where($wherearr);
        $result = $this->db->get(self::PURCHASETBL);
        $data = $result->row('purchase_order_id');

        // die(print_r($this->db->last_query()));

        return $data; 
    }

	public function deletePurchaseOrder($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::PURCHASETBL);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

	public function checkPurchaseForReturnStatus($where) {
		// $this->db->select("purchase_order_tbl.*, locations_tbl.location_name, vendors_tbl.vendor_name ");
        $this->db->select("purchase_order_tbl.purchase_order_id, purchase_order_tbl.purchase_order_number, is_returned, return_id");

        $this->db->from('purchase_order_tbl');
        // $this->db->join('users', 'users.id = purchase_order_tbl.created_by', 'left');
        // $this->db->join('vendors_tbl', 'vendors_tbl.vendor_id = purchase_order_tbl.vendor_id', 'left');
        // $this->db->join('locations_tbl', 'locations_tbl.location_id = purchase_order_tbl.location_id', 'left');
        // $this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_order_tbl.sub_location_id','left');
        $this->db->join('purchase_return_tbl', 'purchase_return_tbl.purchase_order_id = purchase_order_tbl.purchase_order_id', 'left');
    
        if (is_array($where)) {
            $this->db->where($where);
        }

        $result = $this->db->get();
        $data = $result->row();
		// die(print_r($this->db->last_query()));
        
        return $data;

	}
	public function insert_purchase_invoice($post) {
		$query = $this->db->insert(self::PURCHASEINV, $post);
		return $this->db->insert_id();
	}

	public function getPOInvoice($where) {
		$this->db->select("po_invoice_tbl.*, po_invoice_tbl.created_by AS created_by_id, CONCAT(user_first_name,' ', user_last_name) AS name, ");

        $this->db->from('po_invoice_tbl');
        // $this->db->join('purchase_order_tbl', 'purchase_order_tbl.purchase_order_id = purchase_receiving_tbl.purchase_order_id', 'left');
        $this->db->join('users', 'users.id = po_invoice_tbl.created_by', 'left');
    
        if (is_array($where)) {
            $this->db->where($where);
        }

        $result = $this->db->get();
        $data = $result->result();
		
		// die(print_r($this->db->last_query()));
       
        return $data;
	}

	public function deletePurchaseInvoice($wherearr) {

        if (is_array($wherearr)) {
            $this->db->where($wherearr);
        }
        
        $this->db->delete(self::PURCHASEINV);
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

	public function getAllPurchaseOrdersSearch($params = array()){

		$this->db->select('purchase_order_tbl.*, location_name, sub_location_name, vendor_name', false);
		$this->db->from('purchase_order_tbl');
		$this->db->join('locations_tbl','locations_tbl.location_id = purchase_order_tbl.location_id ','inner');
		$this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_order_tbl.sub_location_id','left');
		$this->db->join('vendors_tbl','vendors_tbl.vendor_id = purchase_order_tbl.vendor_id ','inner');


        // $this->db->select("purchase_order_tbl.purchase_order_id,purchase_order_tbl.purchase_order_number, purchase_order_tbl.items, purchase_order_tbl.estimated_delivery_date, purchase_order_tbl.freight, purchase_order_tbl.discount, discount_type, tax, subtotal, grand_total, purchase_order_tbl.created_at, purchase_order_tbl.updated_at, purchase_order_status, purchase_order_tbl.notes, purchase_order_tbl.created_by AS created_by_id, is_receiving, is_returned, is_complete, CONCAT(user_first_name,' ', user_last_name) AS name, vendors_tbl.vendor_id, vendors_tbl.vendor_name, vendor_street_address, vendor_city, vendor_state, vendor_zip_code, vendor_country, company_name, vendor_email_address, vendor_phone_number, locations_tbl.location_id, locations_tbl.location_name, locations_tbl.location_street, locations_tbl.location_city, locations_tbl.location_state, locations_tbl.location_zip, locations_tbl.location_country, locations_tbl.location_phone, sub_locations_tbl.sub_location_id, sub_locations_tbl.sub_location_name, return_id");

        // $this->db->join('users', 'users.id = purchase_order_tbl.created_by', 'left');
        // $this->db->join('vendors_tbl', 'vendors_tbl.vendor_id = purchase_order_tbl.vendor_id', 'left');
        // // $this->db->join('locations_tbl', 'locations_tbl.location_id = purchase_order_tbl.location_id', 'left');
        // $this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = purchase_order_tbl.sub_location_id','left');
        // $this->db->join('purchase_return_tbl', 'purchase_return_tbl.purchase_order_id = purchase_order_tbl.purchase_order_id', 'left');
		
        $this->db->where('purchase_order_tbl.company_id',$this->session->userdata['company_id']);
        if (array_key_exists("where_condition",$params)) {
            $this->db->where($params['where_condition']);
         }
        
        if(!empty($params['search']['purchase_order_status'])){    
           $this->db->where("(`purchase_order_status` LIKE '%".$params['search']['purchase_order_status']."%' )");
        }
        
        $this->db->order_by('purchase_order_tbl.purchase_order_id','desc');
      
         //get records
           $query = $this->db->get();
		// die($this->db->last_query());
            //return fetched data
         return ($query->num_rows() > 0)?$query->result():FALSE;        
       
    }

	public function getUnpaidPOAmount($company_id){
		$this->db->select('subtotal, subtotal_received');
		$this->db->from('purchase_order_tbl');
		$this->db->join('purchase_receiving_tbl','purchase_receiving_tbl.purchase_order_id =purchase_order_tbl.purchase_order_id','inner');
		$this->db->where('purchase_order_tbl.company_id', $company_id);
		$this->db->where('purchase_order_status', 3);
		$this->db->where('purchase_paid_status', 1);
		$result = $this->db->get();
		$data = $result->result();
		// die( print_r($this->db->last_query()));  
		// die( print_r($data));  
		$p_total = 0;
		$r_total = 0;

		foreach($data as $sub){
			if($sub->subtotal == $sub->subtotal_received){
				$p_total += $sub->subtotal;
				$r_total += $sub->subtotal_received;
			} else {
				$p_total += $sub->subtotal - $sub->subtotal_received;
			}
		}
		
		return $p_total;
   }

	public function getUnpaidPOCount($company_id){
		$this->db->select('subtotal, subtotal_received');
		$this->db->from('purchase_order_tbl');
		$this->db->join('purchase_receiving_tbl','purchase_receiving_tbl.purchase_order_id =purchase_order_tbl.purchase_order_id','inner');
		$this->db->where('purchase_order_tbl.company_id', $company_id);
		$this->db->where('purchase_order_status', 3);
		$this->db->where('purchase_paid_status', 1);
		$result = $this->db->get();
		$data = $result->result();
		// die( print_r($this->db->last_query()));  
		// die( print_r($data));  
		$count = count($data);

		if(!empty($data)){
      		return $count;
		}
   }
	 
	 public function getOpenPOAmount($company_id){
		$this->db->select('subtotal');
		$this->db->from('purchase_order_tbl');
		$this->db->where('purchase_order_tbl.company_id',$company_id);
		$this->db->where('purchase_paid_status', 0);
		$this->db->where_in('purchase_order_status',array(1,2)); 
		$result = $this->db->get();
		$data = $result->result();
		// die( print_r($this->db->last_query()));  
		// die( print_r($data));  
		$p_total = 0;

		foreach($data as $sub){
					
			$p_total += $sub->subtotal;
					
		}
		
		return $p_total;
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

    public function getLastIdPlusOne(){
        $this->db->select('*');
		$this->db->where('purchase_order_tbl.company_id', $this->session->userdata['company_id']);
        $this->db->limit(1);
        $this->db->order_by('purchase_order_id',"DESC");
        $result = $this->db->get('purchase_order_tbl');
        $data = $result->row();
        if(!empty($data)){
            // return $data->purchase_order_id + 1;
            return $data->purchase_order_number + 1;
        }
   }

}