<?php 


class QuantitiesModel extends CI_Model {
	const QUANTTBL="quantities";
	// protected $table = 'quantities';
	// protected $primaryKey = 'id';

	// protected $returnType = 'object';
	// protected $allowedFields = [
	// 	'id',
	// 	'item_id',
	// 	'warehouse_id',
	// 	'quantity'
	// ];

	// protected $useTimestamps = false;
	// protected $createdField = '';
	// protected $updatedField = '';

	// Get quantities for an item, per each warehouse
	public function getItemQuantities($itemId) {
		$this->db->select('quantities.*, sub_location_id AS sublocation_id, sub_location_name AS sublocation_name', false);

        $this->db->from('quantities');
        $this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = quantities.quantity_sublocation_id ','left');
		if (is_array($itemId)) {

            $this->db->where($itemId);

        }

        $this->db->group_by('quantity_sublocation_id');

        $result = $this->db->get();
        $data = $result->result();

		// die(print_r($this->db->last_query()));
        return $data;
		
	}

	// Get total amount of pieces (quantity) of an item, for all warehouses
	public function getItemTotalQuantities($itemId) {
		return $this
			->selectSum('quantity')
			->where('item_id', $itemId)
			->first()
			->quantity;
	}

	// Get quantities for an item, for a specific warehouse
	public function getItemQuantity($itemId, $warehouseId) {
		return $this
			->select('quantity')
			->where('item_id', $itemId)
			->where('warehouse_id', $warehouseId)
			->first()
			->quantity;
	}
	
	// Get total amount of items (quantity) in a warehouse
	public function getWarehouseTotalQty($warehouseId) {
		return $this
			->selectSum('quantity')
			->where('warehouse_id', $warehouseId)
			->first()
			->quantity;
	}

	// Delete all quantity records of a warehouse. Records have to be 0 qty
	public function deleteWarehouseQuantities($wherearr) {
		// return $this->where('warehouse_id', $warehouseId)->where('quantity = 0')->delete();
		if (is_array($wherearr)) {
			$this->db->where($wherearr);
		}
		$this->db->where('quantity = 0');
		$this->db->delete(self::QUANTTBL);
		$a = $this->db->affected_rows();
		// die(print_r($this->db->last_query()));
		if ($a) {
			return true;
		} else {
			return false;
		}
	}

	// Delete all quantity records of an item, for all warehouses
	// Records have to be 0 qty
	public function deleteItemQuantities($itemId) {
		return $this->where('item_id', $itemId)->where('quantity = 0')->delete();
	}

	// Add items to stock
	public function addStock($qtyToAdd, $itemId, $warehouseId) {
		$this->db->select('quantity, sub_location_id AS sublocation_id, sub_location_name AS sublocation_name', false);

        $this->db->from('quantities');
        $this->db->join('sub_locations_tbl','sub_locations_tbl.sub_location_id = quantities.quantity_sublocation_id ','left');
		if (is_array($itemId)) {

            $this->db->where($itemId);

        }

        $this->db->group_by('quantity_sublocation_id');

        $result = $this->db->get();
        $data = $result->result();

		// die(print_r($this->db->last_query()));
        return $data;
		return $this
			->set('quantity', "quantity + ${qtyToAdd}", false)
			->where('item_id', $itemId)
			->where('warehouse_id', $warehouseId)
			->update();
	}

	// Remove items from stock
	public function removeStock($qtyToRemove, $itemId, $warehouseId) {
		return $this
			->set('quantity', "quantity - ${qtyToRemove}", false)
			->where('item_id', $itemId)
			->where('warehouse_id', $warehouseId)
			->update();
	}
}