<?php 

class BrandsModel extends CI_Model {
	const BRANDTBL="brands_tbl";
	protected $table = 'brands_tbl';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $allowedFields = [
		'id',
		'name',
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

	// To get all brands -- Adapted to DataTables
	public function dtGetAllBrands() {
		// First, count all results without filtering -- But include required
		// conditions
		$recordsTotal = $this
			->select('inventov2_brands.*')
			->countAllResults();

		// Now make our actual query
		$brands = $this
			->select('inventov2_brands.id AS DT_RowId,
								inventov2_brands.id AS id,
								inventov2_brands.name AS name,
								_user.name AS created_by_name,
								inventov2_brands.created_at AS created_at,
								COUNT(_items.id) AS items')
			->orLike('inventov2_brands.id', $this->dtSearch)
			->orLike('inventov2_brands.name', $this->dtSearch)
			->orLike('_user.name', $this->dtSearch)
			->orLike('inventov2_brands.description', $this->dtSearch)
			->join('inventov2_users AS _user', '_user.id = inventov2_brands.created_by', 'left')
			->join('inventov2_items AS _items', '_items.brand_id = inventov2_brands.id', 'left')
			->orderBy($this->dtOrderBy, $this->dtOrderDir)
			->limit($this->dtLength, $this->dtStart)
			->groupBy('inventov2_brands.id');

		// Count filtered results (without limit clause), and then get the data
		// False to avoid resetting previously made query
		$recordsFiltered = $brands->countAllResults(false);
		$data = $brands->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}
	public function getAllBrands($where_arr) {
		$this->db->select('brands_tbl.*', false);

        $this->db->from('brands_tbl');
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

	// To get a detailed list of all brands
	public function getDetailedList() {
		$brands = $this
			->select('inventov2_brands.id,
								inventov2_brands.name,
								inventov2_brands.created_by,
								_user.username AS created_by_username,
								_user.name AS created_by_name,
								inventov2_brands.created_at,
								inventov2_brands.updated_at,
								COUNT(_items.id) AS items_registered')
			->join('inventov2_items AS _items', '_items.brand_id = inventov2_brands.id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_brands.created_by', 'left')
			->orderBy('inventov2_brands.id', 'ASC')
			->find();

		if(!$brands)
			return [];
		
		return $brands;
	}

	// To get a single brand by ID
	public function getBrand($where_arr) {
		$this->db->select('brands_tbl.*', false);
        $this->db->from('brands_tbl');
       
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();
        $data = $result->row();

        return $data;
	}

	// To get a single brand by Name
	public function getBrandByName($where_arr) {
		$this->db->select('brands_tbl.*', false);
        $this->db->from('brands_tbl');
       
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        $result = $this->db->get();
        $data = $result->row();

        return $data;
	}

	// To get a list of brands (id and name), primarily to be displayed in a select
	public function getBrandsList() {
		// $items = $this->db->select('id, name');
		$this->db->select('brand_id, brand_name');

		$this->db->from('brands_tbl');
		// if(!$items)
		// 	return [];

		// return $items;
		$result = $this->db->get();
        $data = $result->result();

        return $data;
	}

    public function createNewBrand($post) {
		$query = $this->db->insert(self::BRANDTBL, $post);
		return $this->db->insert_id();
  	}

    public function getBrandDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("*");

        $this->db->from('brands_tbl');

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

    public function getBrandDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count)
    {
        $this->db->select("*");
        
        $this->db->from('brands_tbl');

        // $this->db->join('item_types', 'item_types.item_type_id = items_tbl.item_type_id', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->group_start();
        $this->db->or_like('brand_name',$search);
		$this->db->or_like('created_by',$search);
		$this->db->or_like('created_at',$search);
        $this->db->or_like('brand_description',$search);
		$this->db->group_end();

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

    public function updateBrandsTbl($brand_id, $post_data) {
        $this->db->where('brand_id',$brand_id);
        return $this->db->update('brands_tbl',$post_data);
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
}