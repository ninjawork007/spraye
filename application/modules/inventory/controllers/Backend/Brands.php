<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Brands extends MY_Controller
{

	// Define create and update rules
	private $rules = [
		'create' => [
			'name' => [
				'rules' => 'min_length[1]|max_length[100]',
				'errors' => [
					"min_length" => "Validation.brands.name_min_length",
					"max_length" => "Validation.brands.name_max_length"
				]
			],
			'description' => [
				'rules' => 'permit_empty',
				'errors' => []
			]
		],

		'update' => [
			'name' => [
				'rules' => 'permit_empty|min_length[1]|max_length[100]',
				'errors' => [
					"min_length" => "Validation.brands.name_min_length",
					"max_length" => "Validation.brands.name_max_length"
				]
			],
			'description' => [
				'rules' => 'permit_empty',
				'errors' => []
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

		$this->load->model('BrandsModel', 'BrandsModel');
	}

	/**
	 * To get all brands
	 * 
	 * Method			GET
	 * Filter			auth
	 * 
	 */
	public function index() {
		$columns = ['id', 'name', 'created_by_name', 'created_at', 'items'];
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

		$this->brands->setDtParameters($search, $orderBy, $orderDir, $length, $start);

		return $this->respond(array_merge(
			['draw' => $draw],
			$this->brands->dtGetAllBrands()
		));
	}

	/**
	 * To get a single brand by ID
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function show($id) {
		$brand = $this->brands->getBrand($id);

		if(!$brand)
			return $this->failNotFound(lang('Errors.brands.not_found', ['id' => $id]));
		
		return $this->respond($brand);
	}

	/**
	 * To create a new brand
	 * 
	 * Method			POST
	 * Filter			auth
	 */
	public function create() {
		$data = $this->input->post();
		// die(print_r($data));
		// // Run validation according to the rules we've set
		// if(!$this->validateRequestWithRules($this->rules->create))
		// 	return $this->failWithValidationErrors();

		// // Create data to insert (sanitize HTML)
		// $data = $this->buildCreateArray(['name', 'description'], true);
		
		// // Make sure brand name doesn't exist
		// if($this->brands->getBrandByName($data['name']))
		// 	return $this->failResourceExists(lang('Errors.brands.already_exists', ['name' => $data['name']]));

		// Add extra values we might need
		$data['created_by'] = $this->session->userdata['id'];

		// Insert and retrieve inserted
		$brand = $this->db->insert('brands_tbl',$data);
		// $new_brand = $this->brands->getBrand($brand_id);

		// // Return newly created
		// return $this->respondCreated($new_brand);
		if ($brand) {
			$return_array =  array('status' => 200, 'msg' => 'Item created successfully.', 'data' => $brand);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	/**
	 * To edit a brand
	 * 
	 * Method			PUT
	 * Filter			auth:supervisor,admin
	 */
	public function updateBrand() {
		$data = $this->input->post();
		$data['created_by'] = $this->session->userdata['id'];
		$data['updated_at'] = date('Y-m-d H:i:s');
		// die(print_r($data));
		$where = array(
			'brand_id' => $data['brand_id'],
		);
		// // Make sure brand exists
		// if(!$this->BrandsModel->getBrand($where)){

		// 	$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Brand </strong> does not exists.</div>');
		// 	redirect("inventory/Frontend/Brands/editBrand");
            
		// } else {
			
			// If trying to edit brand name, make sure it doesn't exist already
			// if(isset($data['brand_name'])) {
			// 	$duplicateBrand = $this->BrandsModel->getBrandByName($data['brand_name']);
			// 	// die(print_r($duplicateBrand));
			// 	if($duplicateBrand && $duplicateBrand->brand_id != $data['brand_id']){

			// 		$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Brand </strong> does not exists.</div>');
			// 		redirect("inventory/Frontend/Brands");
			// 	}
	
			// }
	
			// Update
			$brand = $this->db->update('brands_tbl', $data, array('brand_id' => $data['brand_id']));
			// die(print_r($brand));

			// $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Brand </strong> updated successfully</div>');
			// redirect("inventory/Frontend/Brands");
			if ($brand) {
				$return_array =  array('status' => 200, 'msg' => 'Brand updated successfully.', 'data' => $brand);
			} else {
				$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
			}
			
			echo json_encode($return_array);
		// }

		
	}

	/**
	 * To delete a brand
	 * 
	 * Method			DELETE
	 * Filter			auth:admin
	 */
	public function deleteBrand($id) {
		$data = $this->input->post();
		$data['created_by'] = $this->session->userdata['id'];
		$data['deleted_at'] = date('Y-m-d H:i:s');
		die(print_r($id));
		$where = array(
			'brand_id' => $data['brand_id'],
		);
		// Make sure the brand exists
		// if(!$this->brands->find($id))
		// 	return $this->failNotFound(lang('Errors.brands.not_found', ['id' => $id]));

		// Remove brand from all items
		$this->items->removeBrandFromAll($id);

		// Delete brand
		$this->brands->delete($id);

		// Respond
		return $this->respondDeleted([
			'id' => $id
		]);
	}

	/**
	 * To export a CSV file with all brands (admins only)
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function export() {
		// Get list of categories, with as much information as we can get
		$brands = $this->brands->getDetailedList();

		// Create a filename and export!
		$filename = date('Y_m_d__H_i_s');
		$filename = "brands__{$filename}.csv";

		helper('csv');

		die(offer_csv_download($brands, $filename));
	}
}