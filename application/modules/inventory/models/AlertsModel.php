<?php 

class AlertsModel extends CI_Model {
	const ALERTTBL="alerts_tbl";
	protected $table = 'alerts_tbl';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $allowedFields = [
		'id',
		'item_id',
		'warehouse_id',
		'type',
		'alert_qty',
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

	// To get all alerts -- Adapted to DataTables
	public function dtGetAllAlerts() {
		$recordsTotal = $this->select('inventov2_alerts.*')->countAllResults();

		$alerts = $this
			->select('inventov2_alerts.item_id AS DT_RowId,
								_item.name AS item_name,
								_warehouse.name AS warehouse_name,
								inventov2_alerts.type,
								IF(inventov2_alerts.type = "min", _item.min_alert, _item.max_alert) AS alert_qty,
								_quantity.quantity AS current_qty,
								inventov2_alerts.created_at')
			->groupStart()
			->orLike('_item.name', $this->dtSearch)
			->orLike('_warehouse.name', $this->dtSearch)
			->groupEnd()
			->join('inventov2_items AS _item', '_item.id = inventov2_alerts.item_id', 'left')
			->join('inventov2_warehouses AS _warehouse', '_warehouse.id = inventov2_alerts.warehouse_id', 'left')
			->join('inventov2_quantities AS _quantity', '_quantity.item_id = inventov2_alerts.item_id AND _quantity.warehouse_id = inventov2_alerts.warehouse_id', 'left')
			->orderBy($this->dtOrderBy, $this->dtOrderDir)
			->limit($this->dtLength, $this->dtStart)
			->groupBy('inventov2_alerts.id');

		$recordsFiltered = $alerts->countAllResults(false);
		$data = $alerts->find();

		return [
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		];
	}
	public function getAllAlerts($where_arr) {
		$this->db->select('alerts_tbl.*', false);

        $this->db->from('alerts_tbl');
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

	public function deleteAlertsForItem($itemId) {
		// return $this->where('item_id', $itemId)->delete();
		if (is_array($itemId)) {
			$this->db->where($itemId);
		}
		$this->db->delete('alerts_tbl');
		$a = $this->db->affected_rows();
		if ($a) {
			return true;
		} else {
			return false;
		}
	}

	public function triggerMinAlert($post) {
		$query = $this->db->insert(self::ALERTTBL, $post);
		return $this->db->insert_id();
		// return $this->insert([
		// 	'item_id' => $itemId,
		// 	'warehouse_id' => $warehouseId,
		// 	'type' => 'min',
		// 	'alert_qty' => $minMaxQty
		// ]);
	}

	public function triggerMaxAlert($post) {
		$query = $this->db->insert(self::ALERTTBL, $post);
		return $this->db->insert_id();
		// return $this->insert([
		// 	'item_id' => $itemId,
		// 	'warehouse_id' => $warehouseId,
		// 	'type' => 'max',
		// 	'alert_qty' => $minMaxQty
		// ]);
	}

	// Get latest alerts, to be shown in the header
	public function getLatestAlertsForHeader() {
		$alerts = $this
			->select('inventov2_alerts.item_id,
								inventov2_alerts.warehouse_id,
								inventov2_alerts.type,
								inventov2_alerts.alert_qty,
								inventov2_alerts.created_at,
								_item.name AS item_name,
								_item.min_alert AS item_min_alert,
								_item.max_alert AS item_max_alert,
								_warehouse.name AS warehouse_name,
								_quantity.quantity AS current_qty')
			->join('inventov2_items AS _item', '_item.id = inventov2_alerts.item_id', 'left')
			->join('inventov2_warehouses AS _warehouse', '_warehouse.id = inventov2_alerts.warehouse_id', 'left')
			->join('inventov2_quantities AS _quantity', '_quantity.item_id = inventov2_alerts.item_id AND _quantity.warehouse_id = inventov2_alerts.warehouse_id', 'left')
			->groupBy('inventov2_alerts.id')
			->limit(6)
			->find();

		if(!$alerts)
			return [];

		$grouper = new JsonGrouper(['item', 'warehouse'], $alerts);
		
		return $grouper->group();
	}
}