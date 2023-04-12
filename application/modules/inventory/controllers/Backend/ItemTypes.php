<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class ItemTypes extends MY_Controller{

	// Define create and update rules
	private $rules = [
		'create' => [
			'name' => [
				'rules' => 'min_length[1]|max_length[100]',
				'errors' => [
					"min_length" => "Validation.categories.name_min_length",
					"max_length" => "Validation.categories.name_max_length"
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
					"min_length" => "Validation.categories.name_min_length",
					"max_length" => "Validation.categories.name_max_length"
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
		parent::__construct();

        if (!$this->session->userdata('email')) {

            $actual_link = $_SERVER[REQUEST_URI];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }

        $this->load->library('parser');

        $this->load->helper('text');

        $this->loadModel();
	}

	private function loadModel(){

        $this->load->model("Administrator");

        $this->load->model('ItemTypesModel', 'ItemTypes');

	}
	/**
	 * To get all categories
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

		$this->categories->setDtParameters($search, $orderBy, $orderDir, $length, $start);

		return $this->respond(array_merge(
			['draw' => $draw],
			$this->categories->dtGetAllCategories()
		));
	}

	/**
	 * To get a single category by ID
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function show($id) {
		$category = $this->categories->getCategory($id);

		if(!$category)
			return $this->failNotFound(lang('Errors.categories.not_found', ['id' => $id]));
		
		return $this->respond($category);
	}

	/**
	 * To create a new category
	 * 
	 * Method			POST
	 * Filter			auth
	 */
	public function create() {
		// die('create item type');
		$data = $this->input->post();
		$this->form_validation->set_rules('item_type_name', 'Item Type name', 'required');
        $this->form_validation->set_rules('item_type_description', 'Description', 'required');
		

		if ($this->form_validation->run() == FALSE) {

            $return_array =  array('status' => 400, 'msg' => validation_errors());
        } else {

            $data = $this->input->post();
		
			// Make sure item type name doesn't exist
			if($this->ItemTypes->getItemTypeByName($data['item_type_name'])){
				// echo 'item type exist';
			} else {
				// echo 'no existing item type';
			}
				// return $this->failResourceExists(lang('Errors.categories.already_exists', ['name' => $data['name']]));
			
			// Add extra values we might need
			// $data['created_by'] = $this->logged_user->id;
			$data['created_by'] = $this->session->userdata['id'];
			// die(print_r($data));

			// Insert and retrieve inserted
			// $category_id = $this->ItemTypes->insert($data);
			// $new_category = $this->ItemTypes->getCategory($category_id);
			// Inserts new item type into database
			$category_id = $this->db->insert('item_types',$data);
			// die(print_r($category_id));
			$new_category = $this->ItemTypes->getItemTypeByName($data['item_type_name']);
			// die(print_r($new_category));

			// Return newly created
			// return $this->respondCreated($new_category);
			if ($category_id) {
				$return_array =  array('status' => 200, 'msg' => 'Item type created successfully.', 'result' => $new_category);
			} else {
				$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'result' => array());
			}
		}
		echo json_encode($return_array);
		
	}

	/**
	 * To edit a category
	 * 
	 * Method			PUT
	 * Filter			auth:supervisor,admin
	 */
	public function update($id) {
		// Run validation according to the rules we've set
		if(!$this->validateRequestWithRules($this->rules->update))
			return $this->failWithValidationErrors();

		// Make sure category exists
		if(!$this->categories->find($id))
			return $this->failNotFound(lang('Errors.categories.not_found', ['id' => $id]));

		// Create data to update (sanitize html)
		$data = $this->buildUpdateArray(['name', 'description'], true);

		// If trying to edit category name, make sure it doesn't exist already
		if(isset($data['name'])) {
			$duplicateCategory = $this->categories->getCategoryByName($data['name']);
			if($duplicateCategory && $duplicateCategory->id != $id)
				return $this->failResourceExists(lang('Errors.categories.already_exists', ['name' => $data['name']]));
		}

		// Update
		$this->categories->update($id, $data);
		
		// Return updated info
		return $this->respondUpdated($this->categories->getCategory($id));
	}

	/**
	 * To delete a category
	 * 
	 * Method			DELETE
	 * Filter			auth:admin
	 */
	public function delete($id) {
		// Make sure the category exists
		if(!$this->categories->find($id))
			return $this->failNotFound(lang('Errors.categories.not_found', ['id' => $id]));

		// Remove category from all items
		$this->items->removeCategoryFromAll($id);

		// Delete category
		$this->categories->delete($id);

		// Respond
		return $this->respondDeleted([
			'id' => $id
		]);
	}

	/**
	 * To export a CSV file with all categories (admins only)
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function export() {
		// Get list of categories, with as much information as we can get
		$categories = $this->categories->getDetailedList();

		// Create a filename and export!
		$filename = date('Y_m_d__H_i_s');
		$filename = "categories__{$filename}.csv";

		helper('csv');

		die(offer_csv_download($categories, $filename));
	}
}