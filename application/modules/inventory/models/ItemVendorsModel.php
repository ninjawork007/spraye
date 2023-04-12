<?php 


class ItemVendorsModel extends CI_Model {
	const ITEMVENDORTBL="item_vendors";
	protected $table = 'item_vendors';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $allowedFields = [
		'id',
		'item_id',
		'supplier_id',
		'part_number',
		'price',
		'tax'
	];

	protected $useTimestams = false;
	protected $createField = '';
	protected $updateField = '';

	// Get suppliers for an item
	public function getItemVendors($itemId) {
		$this->db->select('item_vendors.vendor_id, vendors_tbl.vendor_name', false);
        $this->db->from('item_vendors');
        $this->db->join('vendors_tbl','vendors_tbl.vendor_id = item_vendors.vendor_id ','left');
		if (is_array($itemId)) {
            $this->db->where($itemId);
        }

        $this->db->group_by('item_vendors.vendor_id');

        $result = $this->db->get();
        $data = $result->result();
		// die(print_r($this->db->last_query()));
        return $data;
		
	}

	// Get supplier for a particular item
	// public function getItemSupplier($itemId, $vendorId) {
	public function getItemSupplier($itemId) {
		$this->db->select('item_vendors.vendor_id as id, item_vendor_part_number, item_vendor_price, item_vendor_tax, vendors_tbl.vendor_name', false);

        $this->db->from('item_vendors');
        $this->db->join('vendors_tbl','vendors_tbl.vendor_id = item_vendors.vendor_id ','left');
		if (is_array($itemId)) {

            $this->db->where($itemId);

        }

        $this->db->group_by('item_vendors.vendor_id');

        $result = $this->db->get();
        $data = $result->row();

        return $data;
		
	}

	// To update a item-supplier relation
	public function updateItemSupplier($itemId, $supplierId, $data) {
		return $this
			->where('item_id', $itemId)
			->where('supplier_id', $supplierId)
			->set($data)
			->update();
	}

	// To remove a item-supplier relation
	public function removeItemSupplier($itemId, $supplierId) {
		return $this->where('item_id', $itemId)->where('supplier_id', $supplierId)->delete();
	}

	// To delete all suplier relations of an item
	public function deleteItemSuppliers($itemId) {
		$this->where('item_id', $itemId)->delete();
	}

	// To delete all supplier relations for a supplier
	public function deleteSupplierRelations($supplierId) {
		$this->where('supplier_id', $supplierId)->delete();
	}
}