<?php 

class ItemTypesModel extends CI_Model {
	const ITEMTYPETBL="item_types";
	protected $table = 'item_types';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $allowedFields = [
		'item_type_id',
		'item_type_name',
		'created_by',
		'created_at',
		'updated_at',
		'description'
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

	// To get all categories -- Adapted to DataTables
	public function dtGetAllCategories() {
		// First, count all results without filtering -- But include required
		// conditions
		$recordsTotal = $this
			->select('inventov2_categories.*')
			->countAllResults();

		// Now make our actual query
		$categories = $this
			->select('inventov2_categories.id AS DT_RowId,
								inventov2_categories.id AS id,
								inventov2_categories.name AS name,
								_user.name AS created_by_name,
								inventov2_categories.created_at AS created_at,
								COUNT(_items.id) AS items')
			->orLike('inventov2_categories.id', $this->dtSearch)
			->orLike('inventov2_categories.name', $this->dtSearch)
			->orLike('_user.name', $this->dtSearch)
			->orLike('inventov2_categories.description', $this->dtSearch)
			->join('inventov2_users AS _user', '_user.id = inventov2_categories.created_by', 'left')
			->join('inventov2_items AS _items', '_items.category_id = inventov2_categories.id', 'left')
			->orderBy($this->dtOrderBy, $this->dtOrderDir)
			->limit($this->dtLength, $this->dtStart)
			->groupBy('inventov2_categories.id');

		// Count filtered results (without limit clause), and then get the data
		// False to avoid resetting previously made query
		$recordsFiltered = $categories->countAllResults(false);
		$data = $categories->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}
	public function getAllItemTypes($where_arr) {
		$this->db->select('item_types.*', false);

        $this->db->from('item_types');
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

	// To get a detailed list of all categories
	public function getDetailedList() {
		$categories = $this
			->select('inventov2_categories.id,
								inventov2_categories.name,
								inventov2_categories.created_by,
								_user.username AS created_by_username,
								_user.name AS created_by_name,
								inventov2_categories.created_at,
								inventov2_categories.updated_at,
								COUNT(_items.id) AS items_registered')
			->join('inventov2_items AS _items', '_items.category_id = inventov2_categories.id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_categories.created_by', 'left')
			->orderBy('inventov2_categories.id', 'ASC')
			->find();

		if(!$categories)
			return [];
		
		return $categories;
	}

	// To get a single category by ID
	public function getCategory($id) {
		$category = $this
			->select('inventov2_categories.id,
								inventov2_categories.name,
								inventov2_categories.description,
								inventov2_categories.created_at,
								inventov2_categories.updated_at,
								inventov2_categories.created_by AS created_by_id,
								_user.name AS created_by_name')
			->join('inventov2_users AS _user', '_user.id = inventov2_categories.created_by', 'left')
			->where('inventov2_categories.id', $id)
			->first();

		if(!$category)
			return false;

		$grouper = new JsonGrouper('created_by', $category);

		return $grouper->group();
	}

	// To get a single category by Name
	public function getItemTypeByName($name) {
		$this->db->select('item_type_id, item_type_name, item_type_description, item_types.created_at, item_types.updated_at, created_by as created_by_id');
		$this->db->from('item_types');
		$this->db->where('item_type_name', $name);
		$this->db->join('users', 'users.id=item_types.created_by', 'left');

		// $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');
		// $category = $this
		// 	->select('inventov2_categories.id,
		// 						inventov2_categories.name,
		// 						inventov2_categories.description,
		// 						inventov2_categories.created_at,
		// 						inventov2_categories.updated_at,
		// 						inventov2_categories.created_by AS created_by_id,
		// 						_user.name AS created_by_name')
		// 	->join('inventov2_users AS _user', '_user.id = inventov2_categories.created_by', 'left')
		// 	->where('inventov2_categories.name', $name)
		// 	->first();

		// if(!$category)
		// 	return false;

		// $grouper = new JsonGrouper('created_by', $category);

		// return $grouper->group();
		$this->db->group_by('created_by');
        // $this->db->order_by($col,$dir);

        $result = $this->db->get();
        $data = $result->result();
		// die(print_r($this->db->last_query()));
        return $data;

	}

	// To get a list of categories (id and name), primarily to be displayed in a select
	public function getItemTypesList() {
		// $items = $this->db->select('id, name');
		$this->db->select('item_type_id, item_type_name');

		$this->db->from('item_types');
		// if(!$items)
		// 	return [];

		// return $items;
		$result = $this->db->get();
        $data = $result->result();

        return $data;
	}

    public function createNewItemType($post) {
		$query = $this->db->insert(self::ITEMTYPETBL, $post);
		return $this->db->insert_id();
  	}

    public function getItemTypeDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("*");

        $this->db->from('item_types');

        // $this->db->join('item_types','item_types.item_type_id = items_tbl.item_type_id','left');
    
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        // take into account default Product type
        $this->db->or_where('company_id', 0);

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

    public function getItemTypeDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count)
    {
        $this->db->select("*");
        
        $this->db->from('item_types');
        $this->db->join('users', 'users.id = item_types.created_by');

        // $this->db->join('item_types', 'item_types.item_type_id = items_tbl.item_type_id', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->group_start();
        $this->db->or_like('item_type_name',$search);
		$this->db->or_like('created_by',$search);
        $this->db->or_like('CONCAT(users.user_first_name, " ", users.user_last_name)',$search);
		$this->db->or_like('item_types.created_at',$search);
        $this->db->or_like('item_type_description',$search);
		$this->db->group_end();

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

    public function updateItemTypesTbl($item_type_id, $post_data) {
        $this->db->where('item_type_id',$item_type_id);
        return $this->db->update('item_types',$post_data);
      }
      
    public function getRegisteredItemsCount($where){
        $this->db->select('*');
        $this->db->from('items_tbl');

        if (is_array($where)) {
            $this->db->where($where);
        }

        $result = $this->db->get();

        $data = $result->result();

        return count($data);

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

    public function getAllItemTypesIncludingProduct($where_arr) {
		$this->db->select('item_types.*', false);

        $this->db->from('item_types');
        // $this->db->join('customers','customers.customer_id = invoice_tbl.customer_id ','inner');
        // $this->db->join('programs','programs.program_id = invoice_tbl.program_id','left');

        // $this->db->limit($limit, $start);

        if (is_array($where_arr)) {

            $this->db->where($where_arr);

        }

        
        // take into account default Product type
        $this->db->or_where('company_id', 0);

        $result = $this->db->get();
        $data = $result->result();

        return $data;
	}
}