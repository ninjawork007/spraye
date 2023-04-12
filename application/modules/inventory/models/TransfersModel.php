<?php 

class TransfersModel extends CI_Model {
	const TRANSFERTBL="transfers_tbl";
	protected $table = 'inventov2_transfers';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $allowedFields = [
		'id',
		'from_location_id',
		'to_location_id',
		'items',
		'notes',
		'created_by',
		'created_at',
		'updated_at'
	];

	protected $useTimestamps = true;
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';

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

	// To get all transfers -- Adapted to DataTables
	// Results will vary depending on the user that is requesting
	// them (if $limitByWarehouses is true, we'll limit results to the
	// warehouse IDs provided in $warehouseIds.. This check will only
	// apply to from_warehouse_id)
	// This is so that if a supervisor gets a quantity transfer FROM his warehouse ID,
	// he'll know about it
	public function dtGetAllTransfers(bool $limitByWarehouses = false, array $warehouseIds = []) {
		$recordsTotal = $this->select('inventov2_transfers.*');

		// Should we limit by warehouses? (If user is supervisor)
		if($limitByWarehouses)
			$recordsTotal = $this->restrictQueryByIds($recordsTotal, 'inventov2_transfers.from_warehouse_id', $warehouseIds);

		$recordsTotal = $recordsTotal->countAllResults();

		$transfers = $this
			->select('inventov2_transfers.id AS DT_RowId,
								_from_warehouse.name AS from_warehouse_name,
								_to_warehouse.name AS to_warehouse_name,
								_user.name AS created_by,
								inventov2_transfers.created_at')
			->groupStart()
			->orLike('_from_warehouse.name', $this->dtSearch)
			->orLike('_to_warehouse.name', $this->dtSearch)
			->groupEnd()
			->join('inventov2_warehouses AS _from_warehouse', '_from_warehouse.id = inventov2_transfers.from_warehouse_id', 'left')
			->join('inventov2_warehouses AS _to_warehouse', '_to_warehouse.id = inventov2_transfers.to_warehouse_id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_transfers.created_by', 'left')
			->orderBy($this->dtOrderBy, $this->dtOrderDir)
			->limit($this->dtLength, $this->dtStart)
			->groupBy('inventov2_transfers.id');

		// Should we limit by warehouse?
		if($limitByWarehouses)
			$transfers = $this->restrictQueryByIds($transfers, 'inventov2_transfers.from_warehouse_id', $warehouseIds);

		$recordsFiltered = $transfers->countAllResults(false);
		$data = $transfers->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}
	public function getAllTransfers($where_arr) {
		$this->db->select('transfers_tbl.*, from_location.sub_location_name, to_location.sub_location_name ',  false);

        $this->db->from('transfers_tbl');
        $this->db->join('sub_locations_tbl as from_location','from_location.sub_location_id = transfers_tbl.from_sub_location_id ','left');
        $this->db->join('sub_locations_tbl as to_location','to_location.sub_location_id = transfers_tbl.to_sub_location_id ','left');
        // $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');

        // $this->db->limit($limit, $start);

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }

        // $this->db->order_by($col,$dir);

        $result = $this->db->get();
        $data = $result->result();

        return $data;
	}

	// To get a single transfer by ID
	public function getTransfer($id) {
		$transfer = $this
			->select('inventov2_transfers.id,
								inventov2_transfers.items,
								inventov2_transfers.notes,
								inventov2_transfers.created_at,
								_user.id AS created_by_id,
								_user.name AS created_by_name,
								_from_warehouse.id AS from_warehouse_id,
								_from_warehouse.name AS from_warehouse_name,
								_to_warehouse.id AS to_warehouse_id,
								_to_warehouse.name AS to_warehouse_name')
			->join('inventov2_users AS _user', '_user.id = inventov2_transfers.created_by', 'left')
			->join('inventov2_warehouses AS _from_warehouse', '_from_warehouse.id = inventov2_transfers.from_warehouse_id', 'left')
			->join('inventov2_warehouses AS _to_warehouse', '_to_warehouse.id = inventov2_transfers.to_warehouse_id', 'left')
			->where('inventov2_transfers.id', $id)
			->groupBy('inventov2_transfers.id')
			->first();

		if(!$transfer)
			return false;

		$grouper = new JsonGrouper(['created_by', 'from_warehouse', 'to_warehouse'], $transfer);
		$grouped = $grouper->group();

		$grouped->items = json_decode($grouped->items);

		return $grouped;
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

	public function getTransferDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select('transfers_tbl.*, from_location.sub_location_name as from_sub, to_location.sub_location_name as to_sub ', false);

        $this->db->from('transfers_tbl');
        $this->db->join('sub_locations_tbl as from_location','from_location.sub_location_id = transfers_tbl.from_sub_location_id ','left');
        $this->db->join('sub_locations_tbl as to_location','to_location.sub_location_id = transfers_tbl.to_sub_location_id ','left');

        // $this->db->join('item_types','item_types.item_type_id = items_tbl.item_type_id','left');
    
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

	public function getTransferAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count)
    {
        $this->db->select('transfers_tbl.*, from_location.sub_location_name as from_sub, to_location.sub_location_name as to_sub ', false);

        $this->db->from('transfers_tbl');
        $this->db->join('sub_locations_tbl as from_location','from_location.sub_location_id = transfers_tbl.from_sub_location_id ','left');
        $this->db->join('sub_locations_tbl as to_location','to_location.sub_location_id = transfers_tbl.to_sub_location_id ','left');

        $this->db->join('sub_locations_tbl as sub_from', 'sub_from.sub_location_id = transfers_tbl.from_sub_location_id', 'left');
        $this->db->join('sub_locations_tbl as sub_to', 'sub_to.sub_location_id = transfers_tbl.to_sub_location_id', 'left' );
        $this->db->join('locations_tbl as from', 'from.location_id = sub_from.location_id');
        $this->db->join('locations_tbl as to', 'to.location_id = sub_to.location_id');
        $this->db->join('users', 'users.id = transfers_tbl.created_by');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        // $this->db->distinct('transfers_tbl.transfer_id');

        $this->db->group_start();
        $this->db->or_like('CONCAT(from.location_name, " - ", sub_from.sub_location_name)',$search);
        $this->db->or_like('CONCAT(to.location_name, " - ", sub_to.sub_location_name)',$search);
        $this->db->or_like('CONCAT(users.user_first_name, " ", users.user_last_name)',$search);
		$this->db->or_like('transfer_id',$search);
		$this->db->group_end();

        $this->db->distinct('transfers_tbl.transfer_id');

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

    public function getSubLocationName($sub_id){
        $this->db->select('*');
        $this->db->from('sub_locations_tbl');
        $this->db->join('locations_tbl', 'locations_tbl.location_id = sub_locations_tbl.location_id');
        $this->db->where('sub_location_id', $sub_id);

        $result = $this->db->get();

        $data = $result->result();

        foreach($data as $name){
            return $name->location_name . ' - ' . $name->sub_location_name;
        }
    }

    public function createNewTransfer($post) {
		$query = $this->db->insert(self::TRANSFERTBL, $post);
		return $this->db->insert_id();
  	}

    public function updateTransfersTbl($transfer_id, $post_data) {
        $this->db->where('transfer_id',$transfer_id);
        return $this->db->update('transfers_tbl',$post_data);
    }

    public function getAllCompanySubLocations($company_id)
    {
        $this->db->select('sub_locations_tbl.sub_location_id, locations_tbl.location_name, sub_locations_tbl.sub_location_name');
        $this->db->from('sub_locations_tbl');
        $this->db->join('locations_tbl', 'locations_tbl.location_id = sub_locations_tbl.location_id');
        $this->db->where(array('sub_locations_tbl.company_id' => $company_id, 'sub_locations_tbl.is_archived' => 0));
        $result = $this->db->get();

        $data = $result->result();

        // die(print_r($data));

        return $data;
    }


	public function getSubLocationItemQuantity($item_id, $sub_id)
	{
		$this->db->select('quantities.*, items_tbl.item_name, sub_locations_tbl.sub_location_name');
		$this->db->from('quantities');
		$this->db->join('items_tbl', 'items_tbl.item_id = quantities.quantity_item_id');
		$this->db->join('sub_locations_tbl', 'sub_locations_tbl.sub_location_id = quantities.quantity_sublocation_id');
		$this->db->where(array('quantity_item_id' => $item_id, 'quantity_sublocation_id' => $sub_id));

		$result = $this->db->get();

        $data = $result->result();



        // die(print_r($this->db->last_query()));

		return $data;
	}

	public function getItemListInput($input, $company_id){
		$this->db->select('*');
		$this->db->from('items_tbl');

        $this->db->where('company_id', $company_id);
		
		$this->db->group_start();
        $this->db->or_like('item_name',$input);
        $this->db->or_like('item_number',$input);
		$this->db->group_end();



		$result = $this->db->get();

        $data = $result->result();
		
		// die(print_r($data));
		return $data;
		

	}

    public function getItemName($item_id, $company_id){
        $this->db->select('item_name');
        $this->db->from('items_tbl');
        $this->db->where(array('company_id' => $company_id, 'item_id' => $item_id));
        $result = $this->db->get();
        $data = $result->result();
		
		// die(print_r($data));
		return $data[0]->item_name;
        
    }

    public function updateSourceQuantitiesTbl($where, $quantity){
        $this->db->select('*');
        $this->db->from('quantities');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        if(count($data) > 0){
            $quant = $data[0]->quantity;

            $new_quant = $quant - $quantity;

            $this->db->where($where);
            $this->db->update('quantities', array('quantity' => $new_quant));
        }
        
    }

    public function updateTargetQuantitiesTbl($where, $quantity){
        $this->db->select('*');
        $this->db->from('quantities');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        if(count($data) > 0){
            $quant = $data[0]->quantity;

        $new_quant = $quant + $quantity;

        $this->db->where($where);
        $this->db->update('quantities', array('quantity' => $new_quant));
        }
    }
}