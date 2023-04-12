<?php 

class AdjustmentsModel extends CI_Model {
	const ADJUSTMENTTBL="quantity_adjustments_tbl";
	protected $table = 'quantity_adjustments_tbl';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $allowedFields = [
		'id',
		'warehouse_id',
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

	// To get all quantity adjustments -- Adapted to DataTables
	// Results will vary depending on the user that is requesting
	// them (if $limitByWarehouses is true, we'll limit results to the
	// warehouse IDs provided in $warehouseIds)
	public function dtGetAllAdjustments(bool $limitByWarehouses = false, array $warehouseIds = []) {
		$recordsTotal = $this->select('inventov2_adjustments.*');

		// Should we limit by warehouses? (If user is supervisor)
		if($limitByWarehouses)
			$recordsTotal = $this->restrictQueryByIds($recordsTotal, 'inventov2_adjustments.warehouse_id', $warehouseIds);

		$recordsTotal = $recordsTotal->countAllResults();

		$adjustments = $this
			->select('inventov2_adjustments.id AS DT_RowId,
								_warehouse.name AS warehouse_name,
								_user.name AS created_by,
								inventov2_adjustments.created_at')
			->groupStart()
			->orLike('_warehouse.name', $this->dtSearch)
			->groupEnd()
			->join('inventov2_warehouses AS _warehouse', '_warehouse.id = inventov2_adjustments.warehouse_id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_adjustments.created_by', 'left')
			->orderBy($this->dtOrderBy, $this->dtOrderDir)
			->limit($this->dtLength, $this->dtStart)
			->groupBy('inventov2_adjustments.id');

		// Should we limit by warehouse?
		if($limitByWarehouses)
			$adjustments = $this->restrictQueryByIds($adjustments, 'inventov2_adjustments.warehouse_id', $warehouseIds);

		$recordsFiltered = $adjustments->countAllResults(false);
		$data = $adjustments->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}
	// To get a single quantity adjustment by ID
	public function getAdjustment($id) {
		$adjustment = $this
			->select('inventov2_adjustments.id,
								inventov2_adjustments.items,
								inventov2_adjustments.notes,
								inventov2_adjustments.created_at,
								_user.id AS created_by_id,
								_user.name AS created_by_name,
								_warehouse.id AS warehouse_id,
								_warehouse.name AS warehouse_name')
			->join('inventov2_users AS _user', '_user.id = inventov2_adjustments.created_by', 'left')
			->join('inventov2_warehouses AS _warehouse', '_warehouse.id = inventov2_adjustments.warehouse_id', 'left')
			->where('inventov2_adjustments.id', $id)
			->groupBy('inventov2_adjustments.id')
			->first();

		if(!$adjustment)
			return false;

		$grouper = new JsonGrouper(['created_by', 'warehouse'], $adjustment);
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

    public function getAllCompanySubLocations($company_id)
    {
        $this->db->select('sub_locations_tbl.*, locations_tbl.location_name, sub_locations_tbl.sub_location_name');
        $this->db->from('sub_locations_tbl');
        $this->db->join('locations_tbl', 'locations_tbl.location_id = sub_locations_tbl.location_id');
        $this->db->where(array('sub_locations_tbl.company_id' => $company_id, 'sub_locations_tbl.is_archived' => 0));
        $result = $this->db->get();

        $data = $result->result();

        // die(print_r($data));

        return $data;
    }

    public function getAdjustmentDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("*");

        $this->db->from('quantity_adjustments_tbl');

        // $this->db->join('item_types','item_types.item_type_id = items_tbl.item_type_id','left');
    
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }


        $this->db->order_by('created_at', 'desc');

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

	public function getAdjustmentAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count)
    {
        $this->db->select("*, items_tbl.item_name");
        
        $this->db->from('quantity_adjustments_tbl');
        $this->db->join('items_tbl', 'items_tbl.item_id = quantity_adjustments_tbl.item_id', 'left');
        $this->db->join('sub_locations_tbl', 'sub_locations_tbl.sub_location_id = quantity_adjustments_tbl.sub_location_id', 'left' );
        $this->db->join('locations_tbl', 'locations_tbl.location_id = quantity_adjustments_tbl.location_id');
        $this->db->join('users', 'users.id = quantity_adjustments_tbl.created_by');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        // $this->db->distinct('transfers_tbl.transfer_id');

        $this->db->group_start();
        $this->db->or_like('items_tbl.item_name',$search);
        $this->db->or_like('locations_tbl.location_name',$search);
        $this->db->or_like('sub_locations_tbl.sub_location_name',$search);
        $this->db->or_like('CONCAT(users.user_first_name, " ", users.user_last_name)',$search);
        if($search == 'Add'){
            $this->db->or_like('quantity_adjustment_tbl.adjustment_type', 0);
        } else if($search == 'Subtract'){
            $this->db->or_like('quantity_adjustment_tbl.adjustment_type', 1);
        } else if($search == 'Loss'){
            $this->db->or_like('quantity_adjustments_tbl.adjustment_type', 2);
        }
		$this->db->group_end();

        $this->db->distinct('quantity_adjustments_tbl.quantity_adjustment_id');

        $this->db->order_by('items_tbl.created_at', 'desc');

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

    public function getItemName($item_id){
        $this->db->select('*');
        $this->db->from('items_tbl');

    
            $this->db->where('item_id', $item_id);

        $result = $this->db->get();

        $data = $result->result();

        foreach($data as $name){
            return $name->item_name;
        }

        
    }

    public function getLocationName($location_id){
        $this->db->select('*');
        $this->db->from('locations_tbl');

    
            $this->db->where('location_id', $location_id);

        $result = $this->db->get();

        $data = $result->result();

        foreach($data as $name){
            return $name->location_name;
        }

        
    }

    public function getSubLocationName($sub_location_id){
        $this->db->select('*');
        $this->db->from('sub_locations_tbl');

    
            $this->db->where('sub_location_id', $sub_location_id);

        $result = $this->db->get();

        $data = $result->result();

        foreach($data as $name){
            return $name->sub_location_name;
        }

        
    }

    public function createNewAdjustment($post) {
		$query = $this->db->insert(self::ADJUSTMENTTBL, $post);
		return $this->db->insert_id();
  	}

      public function getDropDownInput($input, $company_id){
		$this->db->select('*');
		$this->db->from('items_tbl');

        $this->db->where(array('is_archived' => 0, 'company_id' => $company_id));
		
		$this->db->group_start();
        $this->db->or_like('item_name',$input);
        $this->db->or_like('item_number',$input);
		$this->db->group_end();

        

		$result = $this->db->get();

        $data = $result->result();
		
		// die(print_r($data));
		return $data;
		

	}

    public function getItemListInput($input){
		$this->db->select('*');
		$this->db->from('items_tbl');
		
		$this->db->where('item_id', $input);

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
		$this->db->where(array('quantity_item_id' => $item_id, 'quantity_sublocation_id' => explode(':', $sub_id)[1]));

		$result = $this->db->get();

        $data = $result->result();



        // die(print_r($this->db->last_query()));

		return $data;
	}

    public function updateAdjustmentsTbl($adjustment_id, $post_data) {
        $this->db->where('quantity_adjustment_id',$adjustment_id);
        return $this->db->update('quantity_adjustments_tbl',$post_data);
      }

    public function updateQuantitiesTbl($where, $amount, $type){
        $this->db->select('*');
        $this->db->from('quantities');
        $this->db->where($where);
        $result = $this->db->get();
        $data = $result->result();
        if(!empty($data)){
            $current_quantity = $data[0]->quantity;
            $new_quantity = 0;

            if($type == 0){
                $new_quantity = $current_quantity + $amount;
            } else if($type == 1){
                $new_quantity = $current_quantity - $amount;
            } else if ($type == 2){
                $new_quantity = $current_quantity - $amount;
            }
            $this->db->where($where);
            return $this->db->update('quantities', array('quantity' => $new_quantity));
        }
       
    }

    public function getAdjustmentById($adjust_id){
        $this->db->select('*');
        $this->db->from('quantity_adjustments_tbl');
        $this->db->where('quantity_adjustment_id', $adjust_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function createNewQuantity($post){
        $query = $this->db->insert('quantities', $post);
		return $this->db->insert_id();
    }

    public function deleteAdjustmentRow($where){
        if (is_array($where)) {
			$this->db->where($where);
		}
        $this->db->delete(self::ADJUSTMENTTBL);
		$a = $this->db->affected_rows();
		// die(print_r($this->db->last_query()));
		if ($a) {
			return true;
		} else {
			return false;
		}
    }

    public function getAllAdjustments($where){
        $this->db->select('quantity_adjustments_tbl.*, sub_locations_tbl.sub_location_name, locations_tbl.location_name ',  false);

        $this->db->from('quantity_adjustments_tbl');
        $this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = quantity_adjustments_tbl.sub_location_id ','left');
        $this->db->join('locations_tbl','locations_tbl.location_id = quantity_adjustments_tbl.location_id ','left');
 

    

        if (is_array($where)) {

            $this->db->where($where);

        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
	}
}