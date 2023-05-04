<?php 


class ItemsModel extends CI_Model {
	const ITEMSTBL="items_tbl";

	// To load DataTables parameters
	public function setDtParameters($search, $orderBy, $orderDir, $length, $start) {
		$this->dtSearch = $search;
		$this->dtOrderBy = $orderBy;
		$this->dtOrderDir = $orderDir;
		$this->dtLength = $length;
		$this->dtStart = $start;
	}

	// To get all items -- Adapted to DataTables
	public function dtGetAllItems() {
		$recordsTotal = $this
			->select('items_tbl.*')
			->countAllResults();

		$items = $this
			->select('items_tbl.item.id AS DT_RowId,
								items_tbl.item_name AS item_name,
								items_tbl.item_number AS item_number,
								')
			->groupStart()
			->orLike('items_tbl.item_name', $this->dtSearch)
			->orLike('items_tbl.item_number', $this->dtSearch)
			// ->orLike('_brand.name', $this->dtSearch)
			// ->orLike('_category.name', $this->dtSearch)
			->groupEnd()
			// ->join('inventov2_brands AS _brand', '_brand.id = items_tbl.brand_id', 'left')
			// ->join('inventov2_categories AS _category', '_category.id = items_tbl.category_id', 'left')
			->orderBy($this->dtOrderBy, $this->dtOrderDir)
			->limit($this->dtLength, $this->dtStart)
			->groupBy('items_tbl.item_id');

		$recordsFiltered = $items->countAllResults(false);
		$data = $items->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}
	// To get all items -- Adapted to DataTables
	public function GetAllItems($where_arr) {
		$this->db->select('items_tbl.*', false);

        $this->db->from('items_tbl');
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

	// To get a detailed list of all items
	public function getDetailedList() {
		$items = $this
			->select('inventov2_items.*,
								_brand.name AS brand_name,
								_category.name AS category_name,
								_user.username AS created_by_username,
								_user.name AS created_by_name')
			->join('inventov2_brands AS _brand', '_brand.id = inventov2_items.brand_id', 'left')
			->join('inventov2_categories AS _category', '_category.id = inventov2_items.category_id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_items.created_by', 'left')
			->orderBy('inventov2_items.id', 'ASC')
			->find();

		if(!$items)
			return [];
		
		return $items;
	}
	
	// To get a single item by ID
	public function getItem($where_arr) {
		// $this->db->select('items_tbl.*', false);
		$this->db->select('*', false);
        $this->db->from('items_tbl');
		$this->db->join('item_vendors','item_vendors.item_id = items_tbl.item_id','left');

		if (is_array($where_arr)) {
			$this->db->where($where_arr);
		}
		// $this->db->order_by($col,$dir);

		$result = $this->db->get();
		$data = $result->row();
		// die(print_r($this->db->last_query()));

		return $data;
	
	}
	public function getItemArr($where_arr) {
		// $this->db->select('items_tbl.*', false);
		$this->db->select('*', false);
        $this->db->from('items_tbl');
		$this->db->join('item_vendors','item_vendors.item_id = items_tbl.item_id','left');

		if (is_array($where_arr)) {
			$this->db->where($where_arr);
		}
		// $this->db->order_by($col,$dir);

		$result = $this->db->get();
		$data = $result->result();
		// die(print_r($this->db->last_query()));

		return $data;
	
	}
	public function getOneItem($where_arr) {
		// $this->db->select('items_tbl.*', false);
		$this->db->select('*', false);

        $this->db->from('items_tbl');
				$this->db->join('item_vendors','item_vendors.item_id = items_tbl.item_id','left');

			if (is_array($where_arr)) {

				$this->db->where($where_arr);
	
			}
	
			// $this->db->order_by($col,$dir);
	
			$result = $this->db->get();
			$data = $result->row();
	
			return $data;
	
	}
	// To get a single item by NAME
	public function getItemByName($where_arr) {
		$this->db->select('items_tbl.*', false);

        $this->db->from('items_tbl');

			if (is_array($where_arr)) {

				$this->db->where($where_arr);
	
			}
	
			// $this->db->order_by($col,$dir);
	
			$result = $this->db->get();
			$data = $result->result();
	
			return $data;
	
	}

	// To get a single item by code
	public function getItemByCode($code) {
		$item = $this
			->select('inventov2_items.id,
								inventov2_items.name,
								inventov2_items.code,
								inventov2_items.code_type,
								inventov2_items.sale_price,
								inventov2_items.sale_tax,
								inventov2_items.description,
								inventov2_items.weight,
								inventov2_items.width,
								inventov2_items.height,
								inventov2_items.depth,
								inventov2_items.min_alert,
								inventov2_items.max_alert,
								inventov2_items.notes,
								inventov2_items.created_at,
								inventov2_items.updated_at,
								_brand.id AS brand_id,
								_brand.name AS brand_name,
								_category.id AS category_id,
								_category.name AS category_name,
								inventov2_items.created_by AS created_by_id,
								_user.name AS created_by_name')
			->join('inventov2_brands AS _brand', '_brand.id = inventov2_items.brand_id', 'left')
			->join('inventov2_categories AS _category', '_category.id = inventov2_items.category_id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_items.created_by', 'left')
			->where('inventov2_items.code', $code)
			->first();

	if(!$item)
		return false;

	$grouper = new JsonGrouper(['brand', 'category', 'created_by'], $item);

	return $grouper->group();
	}

	// To get a single item by id, with information of specific warehouse
	public function getItemWithWarehouse($itemId, $warehouseId) {
		$item = $this
			->select('inventov2_items.id,
								inventov2_items.name,
								inventov2_items.code,
								inventov2_items.code_type,
								inventov2_items.sale_price,
								inventov2_items.sale_tax,
								inventov2_items.description,
								inventov2_items.weight,
								inventov2_items.width,
								inventov2_items.height,
								inventov2_items.depth,
								inventov2_items.min_alert,
								inventov2_items.max_alert,
								inventov2_items.notes,
								inventov2_items.created_at,
								inventov2_items.updated_at,
								_brand.id AS brand_id,
								_brand.name AS brand_name,
								_category.id AS category_id,
								_category.name AS category_name,
								inventov2_items.created_by AS created_by_id,
								_user.name AS created_by_name,
								_quantities.quantity AS quantity')
			->join('inventov2_brands AS _brand', '_brand.id = inventov2_items.brand_id', 'left')
			->join('inventov2_categories AS _category', '_category.id = inventov2_items.category_id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_items.created_by', 'left')
			->join('inventov2_quantities AS _quantities', "_quantities.item_id = inventov2_items.id AND _quantities.warehouse_id = {$warehouseId}", 'left')
			->where('inventov2_items.id', $itemId)
			->groupBy('inventov2_items.id')
			->first();

		if(!$item)
			return false;

		$grouper = new JsonGrouper(['brand', 'category', 'created_by'], $item);

		return $grouper->group();
	}

	// To get a single item by code, with information of specific warehouse
	public function getItemByCodeWithWarehouse($itemCode, $warehouseId) {
		$item = $this
			->select('inventov2_items.id,
								inventov2_items.name,
								inventov2_items.code,
								inventov2_items.code_type,
								inventov2_items.sale_price,
								inventov2_items.sale_tax,
								inventov2_items.description,
								inventov2_items.weight,
								inventov2_items.width,
								inventov2_items.height,
								inventov2_items.depth,
								inventov2_items.min_alert,
								inventov2_items.max_alert,
								inventov2_items.notes,
								inventov2_items.created_at,
								inventov2_items.updated_at,
								_brand.id AS brand_id,
								_brand.name AS brand_name,
								_category.id AS category_id,
								_category.name AS category_name,
								inventov2_items.created_by AS created_by_id,
								_user.name AS created_by_name,
								_quantities.quantity AS quantity')
			->join('inventov2_brands AS _brand', '_brand.id = inventov2_items.brand_id', 'left')
			->join('inventov2_categories AS _category', '_category.id = inventov2_items.category_id', 'left')
			->join('inventov2_users AS _user', '_user.id = inventov2_items.created_by', 'left')
			->join('inventov2_quantities AS _quantities', "_quantities.item_id = inventov2_items.id AND _quantities.warehouse_id = {$warehouseId}", 'left')
			->where('inventov2_items.code', $itemCode)
			->groupBy('inventov2_items.id')
			->first();

		if(!$item)
			return false;

		$grouper = new JsonGrouper(['brand', 'category', 'created_by'], $item);

		return $grouper->group();
	}

	// To remove a brand from all items
	public function removeBrandFromAll($brandId) {
		return $this->set('brand_id', null)->where('brand_id', $brandId)->update();
	}

	// To remove a category from all items
	public function removeCategoryFromAll($categoryId) {
		return $this->set('category_id', null)->where('category_id', $categoryId)->update();
	}

	// To get all item IDs
	public function getItemIds() {
		
		$this->db->select('item_id');
		$this->db->from('items_tbl');
		
		$result = $this->db->get();
        $data = $result->result();

        return $data;
	}

	// To get code type of an item
	public function getCodeType($itemId) {
		return $this->select('code_type')->where('id', $itemId)->first()->code_type;
	}

	// To get stats for value in stock
	// Results will vary depending on the user that is requesting
	// them (if $limitByWarehouses is true, we'll limit results to the
	// warehouse IDs provided in $warehouseIds)
	public function statValueInStock(bool $limitByWarehouses = false, array $warehouseIds = []) {
		// Proxy cross join for non-limited queries
		$proxy_join = '(SELECT item_id, SUM(quantity) AS total_qty FROM inventov2_quantities GROUP BY item_id) AS _quantities';

		// Modify it if we need to limit by warehouse IDs
		if($limitByWarehouses) {
			$proxy_join = '(SELECT item_id, warehouse_id, SUM(quantity) AS total_qty FROM inventov2_quantities ';

			if(count($warehouseIds) == 0)
				$proxy_join .= 'WHERE 1=0 GROUP BY item_id) AS _quantities';
			else{
				$wheres = [];
				foreach($warehouseIds as $warehouseId)
					$wheres[] = "warehouse_id = {$warehouseId}";
				$wheres = implode(' OR ', $wheres);
				$proxy_join .= "WHERE {$wheres} GROUP BY item_id) AS _quantities";
			}
		}

		// Now build our query
		$value_in_stock = $this
			->select('SUM(inventov2_items.sale_price * _quantities.total_qty) AS value')
			->join($proxy_join, '_quantities.item_id = inventov2_items.id', 'left')
			->first()
			->value;

		return !$value_in_stock ? 0 : $value_in_stock;
	}

	// To get a list of items (id, name, code), primarily to be displayed in a select
	// Search is allowed
	public function getItemsList($search) {
		// $items = $this
		// 	->select('id, name, code')
		// 	->groupStart()
		// 	->orLike('id', $search)
		// 	->orLike('name', $search)
		// 	->orLike('code', $search)
		// 	->groupEnd()
		// 	->find();

		// if(!$items)
		// 	return [];

		// return $items;
		$this->db->select('*', false);
		$this->db->join('quantities','quantities.quantity_item_id = items_tbl.item_id','left');
        $this->db->from('items_tbl');
		$this->db->where(" `item_name` LIKE '%".$search."%' ");
			// if (is_array($where_arr)) {

			// 	$this->db->where($where_arr);
	
			// }
	
			// $this->db->order_by($col,$dir);
	
			$result = $this->db->get();
			$data = $result->result();
			// die(print($this->db->last_query()));
			return $data;
	}

	// To get a list of items (id, name, code), primarily to be displayed in a select
	// We'll limit them by supplier ID
	// Search is allowed
	public function getItemsListLimitedByVendor($search, $vendorId, $company_id) {
		 $this->db->select("items_tbl.item_id, items_tbl.item_name,  items_tbl.item_number");

		$this->db->from('items_tbl');

		$this->db->join('item_vendors','item_vendors.item_id = items_tbl.item_id AND item_vendors.vendor_id ='.$vendorId ,'inner');
		$this->db->where(array('items_tbl.is_archived' => '0', 'items_tbl.company_id' => $company_id));
		$this->db->group_start();
		$this->db->or_like('items_tbl.item_id',$search);
		$this->db->or_like('item_name',$search);
		$this->db->or_like('item_number',$search);
		$this->db->group_end();

        $this->db->group_by('items_tbl.item_id');
		
		$result = $this->db->get();
		$data = $result->result();
		//die(print_r($this->db->last_query()));
			return $data;
	}

	public function createNewItem($post) {
		$query = $this->db->insert(self::ITEMSTBL, $post);
		return $this->db->insert_id();
  	}

    public function getItemDataAjax($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $is_for_count) {
        $this->db->select("*, item_types.item_type_name, brands_tbl.brand_name");

        $this->db->from('items_tbl');

        $this->db->join('item_types','item_types.item_type_id = items_tbl.item_type_id','left');
				$this->db->join('brands_tbl', 'brands_tbl.brand_id = items_tbl.brand_id', 'left');
    
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

    public function getItemsDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $col, $dir, $search, $is_for_count)
    {
        $this->db->select("*, item_types.item_type_name, brands_tbl.brand_name");
        
        $this->db->from('items_tbl');

        $this->db->join('item_types', 'item_types.item_type_id = items_tbl.item_type_id', 'left');
		$this->db->join('brands_tbl', 'brands_tbl.brand_id = items_tbl.brand_id', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->group_start();
        $this->db->or_like('item_name',$search);
		$this->db->or_like('item_number',$search);
		$this->db->or_like('item_description',$search);
        $this->db->or_like('`item_types`.`item_type_name`',$search);
        $this->db->or_like('preferred_vendor',$search);
		$this->db->group_end();

        $this->db->order_by($col,$dir);

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

        $result = $this->db->get();
        $data = $result->result();

        return $data;
    }

    public function updateItemsTbl($item_id, $post_data) {
        $this->db->where('item_id',$item_id);
        return $this->db->update('items_tbl',$post_data);
    }

	public function getCompanyItemTypes($company_id){
		$this->db->select('*');
		$this->db->from('item_types');
		$this->db->where(array('company_id' => $company_id, 'is_archived' => 0));
		$this->db->or_where('company_id', 0);
		$this->db->order_by('item_type_id');
		$result = $this->db->get();
        $data = $result->result();

		// die(print_r($data));

        return $data;
	}

	public function getCompanyVendors($company_id){
		$this->db->select('*');
		$this->db->from('vendors_tbl');
		$this->db->where(array('company_id' => $company_id, 'is_archived' => 0));
		$this->db->order_by('vendor_id');
		$result = $this->db->get();
        $data = $result->result();

		// die(print_r($data));

        return $data;
	}

    public function getCompanyBrands($company_id){
		$this->db->select('*');
		$this->db->from('brands_tbl');
		$this->db->where(array('company_id' => $company_id, 'is_archived' => 0));
		$this->db->order_by('brand_id');
		$result = $this->db->get();
        $data = $result->result();

		// die(print_r($data));

        return $data;
	}



    public function getAvailableVendorsList($where_arr)
    {
        $this->db->select('*','item_vendors.vendor_id, vendor_name');
        $this->db->from('item_vendors');
        $this->db->join('vendors_tbl', 'vendors_tbl.vendor_id = item_vendors.vendor_id', 'left');
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        $this->db->order_by('item_vendor_id');
		$result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function addNewItemVendor($post){
        $query = $this->db->insert('item_vendors', $post);
		return $this->db->insert_id();
    }

    public function deleteItemVendor($where){
        if (is_array($where)) {
            $this->db->where($where);
        }
        
        $this->db->delete('item_vendors');
        
        $a = $this->db->affected_rows();
        if($a){
            return true;
        }
        else{
            return false;
        }
    }

    public function getItemTypeByID($type_id)
    {
        $this->db->select('*');
		$this->db->from('item_types');
		$this->db->where('item_type_id', $type_id);
		$result = $this->db->get();
        $data = $result->result();

		// die(print_r($data));

        return $data;
    }

	public function getCompanyProducts($company_id)
	{
		$this->db->select('*');
		$this->db->from('products');
		$this->db->where(array('company_id' => $company_id, 'is_archived' => 0));
		$this->db->order_by('product_id');
		$result = $this->db->get();
        $data = $result->result();

		// die(print_r($data));

        return $data;
	}

	public function addNewItemProducts($post)
	{
		$query = $this->db->insert('item_product_tbl', $post);
		return $this->db->insert_id();
	}

	public function getProductsByItemID($item_id, $company_id)
	{
		$this->db->select('*');
		$this->db->from('item_product_tbl');
		$this->db->join('products', 'products.product_id = item_product_tbl.product_id');
		$this->db->where(array('item_id'=>$item_id, 'item_product_tbl.company_id'=> $company_id, 'item_product_tbl.is_archived' => 0));
		$result = $this->db->get();
		$data = $result->result();

		// die(print_r($this->db->last_query()));

		return $data;		
	}

	public function updateItemProductTbl($item_product_id, $post_data) {
        $this->db->where('item_product_id',$item_product_id);
        return $this->db->update('item_product_tbl',$post_data);
    }

    public function updateItemVendor($vend_arr, $post_data) {
        $this->db->where($vend_arr);
        return $this->db->update('item_vendors',$post_data);
    }

	public function getItemProduct($where){
		$this->db->select('*');
		$this->db->from('item_product_tbl');
		$this->db->where($where);
		$result = $this->db->get();
		$data = $result->result();

		return $data;
	}

	public function getTotalItemAmount($where){
		$this->db->select('*');
		$this->db->from('quantities');
		$this->db->where($where);
		$result = $this->db->get();
		$data = $result->result();

		$total_amount = 0;

		foreach($data as $subtotal){
			$total_amount += $subtotal->quantity;
		}

		return $total_amount;
	}

    public function getAllCompanyLocations($company_id){
        $this->db->select('*');
        $this->db->from('locations_tbl');
        $this->db->where('company_id', $company_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

    public function getLocationQuantities($loc_id, $item_id){
        $this->db->select('*');
        $this->db->from('quantities');
        $this->db->where(array('quantity_location_id' => $loc_id, 'quantity_item_id' => $item_id));
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

	public function getSubLocationQuantities($sub_id, $item_id){
        $this->db->select('*');
        $this->db->from('quantities');
        $this->db->where(array('quantity_sublocation_id' => $sub_id, 'quantity_item_id' => $item_id));
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

	public function getItemLocationsDataAjax($where_arr = '', $where_like = '', $limit, $start, $is_for_count) {
        $this->db->select("*");

        $this->db->from('items_tbl');

		$this->db->join('quantities', 'quantities.quantity_item_id = items_tbl.item_id', 'left');
		$this->db->join('locations_tbl', 'locations_tbl.location_id = quantities.quantity_location_id', 'left');
    
        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

		

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

		

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

		



        // $this->db->order_by('programs.program_id','asc');
        // $this->db->order_by('jobs.job_id','asc');

		$this->db->group_by('items_tbl.item_id');

        $result = $this->db->get();
        $data = $result->result();


		// die(print_r($this->db->last_query()));
        // $data = array_map(array($this,"GetLastCompletedServiceDate"),$data);
		// $data = array_map(array($this,"GetLastCompletedServiceDateProgram"),$data);
        return $data;
    }

    public function getItemLocationsDataAjaxSearch($where_arr = '', $where_like = '', $limit, $start, $search, $is_for_count)
    {
        $this->db->select("*");
        
        $this->db->from('items_tbl');

		$this->db->join('quantities', 'quantities.quantity_item_id = items_tbl.item_id', 'left');
		$this->db->join('locations_tbl', 'locations_tbl.location_id = quantities.quantity_location_id', 'left');

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }

        if (is_array($where_like)) {
            $this->db->like($where_like);
        }

        $this->db->group_start();
        $this->db->or_like('item_name',$search);
		$this->db->or_like('item_number',$search);
		$this->db->group_end();

        if ($is_for_count == false) {
            $this->db->limit($limit, $start);
        }

		$this->db->group_by('items_tbl.item_id');

        $result = $this->db->get();
        $data = $result->result();

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

	public function getSubLocationName($sub_id){
		$this->db->select('sub_location_name');
		$this->db->from('sub_locations_tbl');
		$this->db->where('sub_location_id', $sub_id);
		$result = $this->db->get();
		$data = $result->row();
		return $data;
	}

	public function getAllCompanySubLocationsByLocation($loc_id){
        $this->db->select('*');
        $this->db->from('sub_locations_tbl');
        $this->db->where('location_id', $loc_id);
        $result = $this->db->get();
        $data = $result->result();
        return $data;
    }

	public function getSublocationsList($loc_id){
		$this->db->select('*');
		$this->db->from('sub_locations_tbl');
		$this->db->where(array('location_id' => $loc_id, 'is_archived' => 0));
		$result = $this->db->get();
		$data = $result->result();
		return $data;
	}

    public function getItemNumberCount($item_number)
    {
        $this->db->select('*');
        $this->db->from('items_tbl');
        $this->db->where('item_number', $item_number);
        $result = $this->db->get()->num_rows();
        return $result;
    }
}