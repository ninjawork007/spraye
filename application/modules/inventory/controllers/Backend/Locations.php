<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Locations extends MY_Controller
{

	// Define create and update rules
	private $rules = [
		'create' => [
			'name' => [
				'rules' => 'min_length[1]|max_length[100]',
				'errors' => [
					'min_length' => 'Validation.warehouses.name_min_length',
					'max_length' => 'Validation.warehouses.name_max_length'
				]
			],
			'address' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					'max_length' => 'Validation.warehouses.address_max_length'
				]
			],
			'city' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					'max_length' => 'Validation.warehouses.city_max_length'
				]
			],
			'country' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					'max_length' => 'Validation.warehouses.country_max_length'
				]
			],
			'state' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					'max_length' => 'Validation.warehouses.state_max_length'
				]
			],
			'zip_code' => [
				'rules' => 'permit_empty|integer|max_length[12]',
				'errors' => [
					'integer' => 'Validations.warehouses.zip_code_invalid',
					'max_length' => 'Validation.warehouses.zip_code_max_length'
				]
			],
			'phone_number' => [
				'rules' => 'permit_empty|max_length[20]',
				'errors' => [
					'max_length' => 'Validation.warehouses.phone_number_max_length'
				]
			]
		],

		'update' => [
			'name' => [
				'rules' => 'permit_empty|min_length[1]|max_length[100]',
				'errors' => [
					'min_length' => 'Validation.warehouses.name_min_length',
					'max_length' => 'Validation.warehouses.name_max_length'
				]
			],
			'address' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					'max_length' => 'Validation.warehouses.address_max_length'
				]
			],
			'city' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					'max_length' => 'Validation.warehouses.city_max_length'
				]
			],
			'country' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					'max_length' => 'Validation.warehouses.country_max_length'
				]
			],
			'state' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					'max_length' => 'Validation.warehouses.state_max_length'
				]
			],
			'zip_code' => [
				'rules' => 'permit_empty|integer|max_length[12]',
				'errors' => [
					'integer' => 'Validations.warehouses.zip_code_invalid',
					'max_length' => 'Validation.warehouses.zip_code_max_length'
				]
			],
			'phone_number' => [
				'rules' => 'permit_empty|max_length[20]',
				'errors' => [
					'max_length' => 'Validation.warehouses.phone_number_max_length'
				]
			]
		]
	];

	public function __construct() {
		$this->rules = (object) $this->rules;

		$this->load->library('parser');
		$this->load->helper('text');
		$this->loadModel();
	}
	private function loadModel(){

		$this->load->model('LocationsModel', 'LocationsModel');
		$this->load->model('ItemsModel', 'ItemsModel');
		$this->load->model('LocationRelationsModel', 'RelationsModel');
		$this->load->model('QuantitiesModel', 'QuantityModel');
	}
	/**
	 * To get all warehouses
	 * 
	 * Method			GET
	 * Filter			auth:supervisor,admin
	 */
	public function index() {
		$columns = [
			'name',
			'address',
			'phone_number',
			'total_quantity',
			'total_value'
		];
		$datatables = new DataTables($this->request, $columns);

		if($datatables->isRequestValid() === false)
			return $this->failUnauthorized(lang('Errors.unauthorized'));
		
		$draw = $datatables->getDraw();
		$length = $datatables->getLength();
		$start = $datatables->getStart();
		$search = $datatables->getSearchStr();
		$orderBy = $datatables->getOrderBy();
		$orderDir = $datatables->getOrderDir();

		if($orderBy === false || $orderDir === false)
			return $this->fail(lang('Errors.invalid_order'));

		$this->warehouses->setDtParameters($search, $orderBy, $orderDir, $length, $start);

		// Is user supervisor? Let's limit by the warehouses he has access to
		if($this->logged_user->role == 'supervisor') {
			$warehouseIds = $this->warehouse_relations->getWarehouseIdsByUser($this->logged_user->id);
			$result = $this->warehouses->dtGetAllWarehouses(true, $warehouseIds);
		}else{
			$result = $this->warehouses->dtGetAllWarehouses();
		}

		return $this->respond(array_merge(
			['draw' => $draw],
			$result
		));
	}

	/**
	 * To get a single warehouse by ID
	 * 
	 * Supervisors will be able to see all users responsible of
	 * this warehouse, but won't be able to modify anything
	 * 
	 * Method			GET
	 * Filter			auth:supervisor,admin
	 */
	public function show($id) {
		$warehouse = $this->warehouses->getWarehouse($id);

		if(!$warehouse)
			return $this->failNotFound(lang('Errors.warehouses.not_found', ['id' => $id]));

		$warehouse->workers = $this->warehouse_relations->getWorkersResponsibleOfWarehouse($id);
		$warehouse->supervisors = $this->warehouse_relations->getSupervisorsResponsibleOfWarehouse($id);

		return $this->respond($warehouse);
	}

	/**
	 * To create a new warehouse
	 * 
	 * Method			POST
	 * Filter			auth:admin
	 */
	public function create() {
        // die('create location');
        $data = $this->input->post();
        // die(print_r($data));
        $data['created_by'] = $this->session->userdata['id'];
        $data['company_id'] = $this->session->userdata['company_id'];
        $location = $this->db->insert('locations_tbl',$data);
        if ($location) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Location </strong> created successfully.</div>');
            redirect("inventory/Frontend/Locations/");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong</div>');
         redirect("inventory/Frontend/Locations/");
        }
    }

	public function createSub() {
        
        $data = $this->input->post();
        
        $fleet = $data['sub_location_fleet_no'];
        $data['company_id'] = $this->session->userdata['company_id'];
        $fleet_id =  $this->LocationsModel->getFleetIdByFleetNumber($fleet);
        $data['sub_location_fleet_id'] = $fleet_id;
        $sublocation = $this->db->insert('sub_locations_tbl',$data);
        $sub_id = $this->db->insert_id();
        $where_arr = array(
            'company_id' => $data['company_id'],
            'is_archived' => 0
        );
        $items = $this->ItemsModel->GetAllItems($where_arr);
        foreach($items as $item){
            $quantity = array(
                'quantity_item_id' => $item->item_id,
                'quantity_location_id' => $data['location_id'],
                'quantity_sublocation_id' => $sub_id,
                'quantity' => 0,
                'company_id' => $data['company_id']                
            );
            $quant = $this->db->insert('quantities', $quantity);
        }
        
        if ($sublocation) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Sub-Location </strong> created successfully.</div>');
            redirect("inventory/Frontend/Locations/");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong</div>');
         redirect("inventory/Frontend/Locations/");
        }
    }

	/**
	 * To edit a warehouse
	 * 
	 * Method			PUT
	 * Filter			auth:admin
	 */
	public function updateLocation() {
		$post_data = $this->input->post();
		// die(print_r($post_data));
		$where = array('location_id' => $post_data['location_id']);
		$data['sub_locations'] = $this->LocationsModel->getSubLocationsByLocationId($where);

		if($post_data['location_zip'] == ''){

			$post_data['location_zip'] = null;
		}

		$data['created_by'] = $this->session->userdata['id'];
		$where = array(
			// 'location_id' => $location->location_id,
			'location_name' => $post_data['location_name'],
			'location_phone' => $post_data['location_phone'],
			'location_street' => $post_data['location_street'],
			'location_city' => $post_data['location_city'],
			'location_state' => $post_data['location_state'],
			'location_zip' => $post_data['location_zip'],
			'location_country' => $post_data['location_country'],
			'created_by' => $data['created_by'],
			'created_at' => date('Y-m-d H:i:s'),
		);
		
		$location = $this->db->update('locations_tbl',$where, array('location_id' =>  $post_data['location_id']));
		
		foreach($data['sub_locations'] as $subs){
			// die(print_r($subs));
			$where_subs = array(
				'sub_location_name' => $subs->sub_location_name,
			);
			$sublocation = $this->db->update('sub_locations_tbl',$where_subs, array('sub_location_id' => $subs->sub_location_id));
		}
		// die(print_r($location));
		
		if ($location) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Location </strong> updated successfully.</div>');
            redirect("inventory/Frontend/Locations/");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong</div>');
         redirect("inventory/Frontend/Locations/");
        }
	}

	public function updateSub() {
		// die('create sub location');
		$data = $this->input->post();
		
		$fleet = $data['sub_location_fleet_no'];
		$data['company_id'] = $this->session->userdata['company_id'];
        $fleet_id =  $this->LocationsModel->getFleetIdByFleetNumber($fleet);
        $data['sub_location_fleet_id'] = $fleet_id;
		// die(print_r($data));
		$sublocation = $this->db->update('sub_locations_tbl',$data, array('sub_location_id' =>  $data['sub_location_id']));
		
		if ($sublocation) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Sub-Location </strong> updated successfully.</div>');
            redirect("inventory/Frontend/Locations/");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong</div>');
         redirect("inventory/Frontend/Locations/");
        }
		
	}
	
	/**
	 * To delete a warehouse (soft)
	 * 
	 * Method			DELETE
	 * Filter			auth:admin
	 */
	public function delete($id) {
		// die('delete');

		// Soft delete warehouse
		// $this->warehouses->delete($id);
		$location =$this->db->delete('locations_tbl', array('location_id' => $id));
		// die(print_r($location));

		// After deleting, let's remove it from each and every user that had access to it,
		// including soft-deleted users
		$this->RelationsModel->deleteLocationRelations(array('location_id' => $id));

		// Also, remove quantity rows
		$this->QuantityModel->deleteWarehouseQuantities(array('quantity_location_id' => $id));

		// We're done
		// return $this->respondDeleted(['id' => $id]);
		if (!$location == 1) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("inventory/Frontend/Locations");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Location </strong> deleted successfully</div>');
            redirect("inventory/Frontend/Locations");
        }
	}

	public function deleteSub($id) {
		// die('delete');
		
		$location =$this->db->delete('sub_locations_tbl', array('sub_location_id' => $id));
		// die(print_r($location));

		// After deleting, let's remove it from each and every user that had access to it,
		// including soft-deleted users
		$this->RelationsModel->deleteLocationRelations(array('location_id' => $id));

		// Also, remove quantity rows
		$this->QuantityModel->deleteWarehouseQuantities(array('quantity_location_id' => $id));

		// We're done
		// return $this->respondDeleted(['id' => $id]);
		if (!$location == 1) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("inventory/Frontend/Locations");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Sub Location </strong> deleted successfully</div>');
            redirect("inventory/Frontend/Locations");
        }
	}

	/**
	 * To get a list of workers not responsible of a warehouse, to
	 * be used in a select
	 * 
	 * Method			GET
	 * Filter			auth:admin
	 */
	public function pending_workers_list($warehouseId) {
		if(!$this->warehouses->find($warehouseId))
			return $this->failNotFound(lang('Errors.warehouses.not_found', ['id' => $warehouseId]));

		return $this->respond($this->users->getWorkersNotResponsibleOfWarehouse($warehouseId));
	}

	/**
	 * To get a list of supervisors not responsible of a warehouse, to
	 * be used in a select
	 * 
	 * Method			GET
	 * Filter			auth:admin
	 */
	public function pending_supervisors_list($warehouseId) {
		if(!$this->warehouses->find($warehouseId))
			return $this->failNotFound(lang('Errors.warehouses.not_found', ['id' => $warehouseId]));
		
		return $this->respond($this->users->getSupervisorsNotResponsibleOfWarehouse($warehouseId));
	}

	/**
	 * To get a list of warehouses a user is responsible of (if worker or supervisor),
	 * if admin we'll return all warehouses
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function list() {
		$warehouses = [];

		if($this->logged_user->role == 'worker' || $this->logged_user->role == 'supervisor')
			$warehouses = $this->warehouses->getWarehousesUserHasAccessTo($this->logged_user->id);
		else if($this->logged_user->role == 'admin')
			$warehouses = $this->warehouses->getWarehousesList();
		
		return $this->respond($warehouses);
	}

	/**
	 * To export a CSV file with all warehouses (admins only)
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function export() {
		// Get list of warehouses, with as much information as we can get
		$warehouses = $this->warehouses->getDetailedList();

		// Create a filename and export!
		$filename = date('Y_m_d__H_i_s');
		$filename = "warehouses__{$filename}.csv";

		helper('csv');

		die(offer_csv_download($warehouses, $filename));
	}

	public function subLocationlist() {
		$location = $this->input->get('location', TRUE) ?? '';
		// die($location);
		$where = array('location_id' => $location);
		$sublocations = $this->LocationsModel->getSubLocationsByLocationId($where);
		$return_array =  array( 'result' => $sublocations);
		// die(print_r($sublocations));
		echo json_encode($return_array);
	}
}