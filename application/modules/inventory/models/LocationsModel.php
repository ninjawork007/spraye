<?php 

class LocationsModel extends CI_Model {
	const LOCATIONTBL="locations_tbl";
	const SUBLOCATIONTBL = "sub_locations_tbl";
	protected $table = 'locations_tbl';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $allowedFields = [
		'id',
		'name',
		'address',
		'city',
		'country',
		'state',
		'zip_code',
		'phone_number',
		'created_by',
		'created_at',
		'updated_at',
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

	// To get all warehouses -- Adapted to DataTables
	// Results will vary depending on the user that is requesting
	// them (if $limitByWarehouses is true, we'll limit results to the
	// warehouse IDs provided in $warehouseIds
	public function dtGetAllWarehouses(bool $limitByWarehouses = false, array $warehouseIds = []) {
		$recordsTotal = $this->select('inventov2_warehouses.*');

		// Should we limit by warehouses? (If user is worker/supervisor)
		if($limitByWarehouses)
			$recordsTotal = $this->restrictQueryByIds($recordsTotal, 'inventov2_warehouses.id', $warehouseIds);
		
		$recordsTotal = $recordsTotal->countAllResults();

		$warehouses = $this
			->select('inventov2_warehouses.id AS DT_RowId,
								inventov2_warehouses.name AS name,
								inventov2_warehouses.address AS address,
								inventov2_warehouses.phone_number AS phone_number,
								_values.total_qty AS total_qty,
								_values.total_value AS total_value')
			->groupStart()
			->orLike('inventov2_warehouses.name', $this->dtSearch)
			->orLike('inventov2_warehouses.address', $this->dtSearch)
			->orLike('inventov2_warehouses.phone_number', $this->dtSearch)
			->groupEnd()
			->join('(SELECT
								warehouse_id,
								SUM(_quantities.quantity) AS total_qty,
								SUM(_quantities.quantity * _item.sale_price) AS total_value
							FROM
								inventov2_quantities AS _quantities
							INNER JOIN
								inventov2_items AS _item ON _item.id = _quantities.item_id
							GROUP BY warehouse_id) AS _values', '_values.warehouse_id = inventov2_warehouses.id', 'left')
			->orderBy($this->dtOrderBy, $this->dtOrderDir)
			->limit($this->dtLength, $this->dtStart)
			->groupBy('inventov2_warehouses.id');
		
		// Should we limit by warehouse?
		if($limitByWarehouses)
			$warehouses = $this->restrictQueryByIds($warehouses, 'inventov2_warehouses.id', $warehouseIds);
		
		$recordsFiltered = $warehouses->countAllResults(false);
		$data = $warehouses->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}
	public function getAllLocations($where_arr) {
		$this->db->select('locations_tbl.*', false);

        $this->db->from('locations_tbl');
        // $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
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
	public function getAllSubLocations($where_arr) {
		$this->db->select('sub_locations_tbl.*', false);

        $this->db->from('sub_locations_tbl');
        // $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        // $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');

        // $this->db->limit($limit, $start);

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }
		// $this->db->group_by('location_id');
        // $this->db->order_by($col,$dir);

        $result = $this->db->get();
        $data = $result->result();

        return $data;
	}

	// To get a detailed list of all warehouses
	public function getDetailedList() {
		$warehouse = $this
			->select('inventov2_warehouses.id,
								inventov2_warehouses.name,
								inventov2_warehouses.address,
								inventov2_warehouses.phone_number,
								inventov2_warehouses.created_by,
								_user.username AS created_by_username,
								_user.name AS created_by_name,
								inventov2_warehouses.created_at,
								inventov2_warehouses.updated_at,
								_values.total_qty AS total_quantity,
								_values.total_value AS total_sale_value')
			->join('inventov2_users AS _user', '_user.id = inventov2_warehouses.created_by', 'left')
			->join('(SELECT
								warehouse_id,
								SUM(_quantities.quantity) AS total_qty,
								SUM(_quantities.quantity * _item.sale_price) AS total_value
							FROM
								inventov2_quantities AS _quantities
							INNER JOIN
								inventov2_items AS _item ON _item.id = _quantities.item_id
							GROUP BY warehouse_id) AS _values', '_values.warehouse_id = inventov2_warehouses.id', 'left')
			->orderBy('inventov2_warehouses.id', 'ASC')
			->groupBy('inventov2_warehouses.id')
			->find();

		if(!$warehouse)
			return [];
		
		return $warehouse;
	}

	// To get a single location by ID
	public function getLocation($where_arr) {
		$this->db->select('locations_tbl.*', false);
		$this->db->where('company_id', $this->session->userdata['company_id']);
        $this->db->from('locations_tbl');

			if (is_array($where_arr)) {

				$this->db->where($where_arr);
	
			}
	
			// $this->db->order_by($col,$dir);
	
			$result = $this->db->get();
			$data = $result->row();
            return $data;
			
	}

	// To get a LOCATION by name
	public function getLocationByName($where_arr) {
		$this->db->select('locations_tbl.*', false);

        $this->db->from('locations_tbl');

			if (is_array($where_arr)) {

				$this->db->where($where_arr);
	
			}
	
			// $this->db->order_by($col,$dir);
	
			$result = $this->db->get();
			$data = $result->result();
	
			return $data;
	}

	// To get a SUB LOCATION by name
	public function getSubLocationByName($where_arr) {
		$this->db->select('sub_locations_tbl.*', false);

        $this->db->from('sub_locations_tbl');

			if (is_array($where_arr)) {

				$this->db->where($where_arr);
	
			}
	
			// $this->db->order_by($col,$dir);
	
			$result = $this->db->get();
			$data = $result->result();
	
			return $data;
	}

	// To get an array of sub-location IDs
	public function getSubLocationIds() {
		$this->db->select('sub_location_id');
		$this->db->from('sub_locations_tbl');
		
		$result = $this->db->get();
        $data = $result->result();

        return $data;
	}

	// To get a list of warehouses that a user doesn't have access to
	public function getWarehousesUserIsNotResponsible($userId) {
		$warehouses = $this
			->select('inventov2_warehouses.id AS id,
								inventov2_warehouses.name AS name')
			->join('inventov2_warehouse_relations AS _relation', "_relation.warehouse_id = inventov2_warehouses.id AND _relation.user_id = $userId", 'left')
			->join('inventov2_users AS _user', "_user.id = $userId", 'left')
			->where('_user.deleted_at is null')
			->where('_relation.user_id is null')
			->groupBy('inventov2_warehouses.id')
			->find();

		if(!$warehouses)
			return [];

		return $warehouses;
	}

	// To get a list of all locations (id and name)
	public function getLocationsList() {
		$this->db->select('location_id, location_name');
		$this->db->where('company_id', $this->session->userdata['company_id']);
		$this->db->from('locations_tbl');
	
		$result = $this->db->get();
        $data = $result->result();

        return $data;
	}

	// To get a list of all sublocations (id and name)
	public function getSubLocationsList() {
		$this->db->select('sub_location_id, sub_location_name');
		$this->db->where('company_id', $this->session->userdata['company_id']);
		$this->db->from('sub_locations_tbl');
	
		$result = $this->db->get();
        $data = $result->result();

        return $data;
	}
	#### GET SUB LOCATIONS BY LOCATION ID #####
	public function getSubLocationsByLocationId($where_arr) {
		$this->db->select('sub_locations_tbl.*');
		$this->db->where('company_id', $this->session->userdata['company_id']);
		$this->db->from('sub_locations_tbl');
		if (is_array($where_arr)) {

			$this->db->where($where_arr);

		}

		$result = $this->db->get();
        $data = $result->result();

        return $data;
	}

	// To get a list of warehouses that a worker/supervisor has access to (id and name)
	public function getWarehousesUserHasAccessTo($userId) {
		$warehouses = $this
			->select('inventov2_warehouses.id,
								inventov2_warehouses.name')
			->join('inventov2_warehouse_relations AS _relation', "_relation.warehouse_id = inventov2_warehouses.id AND _relation.user_id = $userId", 'inner')
			->groupBy('inventov2_warehouses.id')
			->find();

		if(!$warehouses)
			return [];

		return $warehouses;
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

	// DELETE LOCATION AND SUB LOCATION
	public function deleteLocation($wherearr) {
		$this->db->select('location_id, location_name');

		$this->db->from('locations_tbl');
		$this->db->join('sub_locations_tbl','sub_locations_tbl.location_id = locations_tbl.location_id ','left');
        $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');

		if (is_array($wherearr)) {
			$this->db->where($wherearr);
		}
		$this->db->delete('locations_tbl');
		$this->db->delete('sub_locations_tbl');
		// die(print_r($this->db->last_query()));
		$a = $this->db->affected_rows();
		if ($a) {
			return true;
		} else {
			return false;
		}
	}

	public function getLocationDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("*");

        $this->db->from('locations_tbl');

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

	public function getLocationAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count)
    {
        $this->db->select("*");
        
        $this->db->from('locations_tbl');

        // $this->db->join('item_types', 'item_types.item_type_id = items_tbl.item_type_id', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->group_start();
        $this->db->or_like('location_name',$search);
		$this->db->or_like('location_street',$search);
		$this->db->or_like('location_phone',$search);
		$this->db->group_end();

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

	public function getSubLocationDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("sub_locations_tbl.*, locations_tbl.location_name");

        $this->db->from('sub_locations_tbl');
		$this->db->join('locations_tbl','locations_tbl.location_id = sub_locations_tbl.location_id','left');
    
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        // take into account default Product type
        // $this->db->or_where('company_id', 0);

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

	public function getSubLocationAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count)
    {
        $this->db->select("sub_locations_tbl.*, locations_tbl.location_name");

        $this->db->from('sub_locations_tbl');
		$this->db->join('locations_tbl','locations_tbl.location_id = sub_locations_tbl.location_id','left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->group_start();
        $this->db->or_like('sub_location_name',$search);
        $this->db->or_like('location_name',$search);
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

	public function getCompanyLocations($company_id){
		$this->db->select('*');
		$this->db->from('locations_tbl');
		$this->db->where('company_id', $company_id);
		$this->db->order_by('location_id');
		$result = $this->db->get();
        $data = $result->result();

		// die(print_r($data));

        return $data;
	}

	public function getLocationName($loc_id){
		$this->db->select('location_name');
		$this->db->from('locations_tbl');
		$this->db->where('location_id', $loc_id);
		$result = $this->db->get();
		$data = $result->row();
		return $data;
	}

	public function getSubLocationTotalInventoryValue($sub_id){
		$this->db->select('quantity, items_tbl.average_cost_per_unit');
		$this->db->from('quantities');
		$this->db->join('items_tbl', 'items_tbl.item_id = quantities.quantity_item_id');
		$this->db->where('quantity_sublocation_id', $sub_id);
		$result = $this->db->get();
		$data = $result->result();
		$total_value = 0;
		if(!empty($data)){
			foreach($data as $val){
				$total_value += ($val->quantity * $val->average_cost_per_unit);
			}
		}
		// die(print_r($total_value));
		return $total_value;
	}

    public function getCompanyFleetNumbers($company_id)
    {
        $this->db->select('*');
        $this->db->from('fleet_vehicles');
        $this->db->where('v_company_id', $company_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getFleetIdByFleetNumber($fleet)
    {
        $this->db->select('fleet_id');
        $this->db->from('fleet_vehicles');
        $this->db->where('fleet_number', $fleet);
        $result = $this->db->get();
        $data = $result->row();
		if(!empty($data)){
            return $data->fleet_id;
        }
    }

}