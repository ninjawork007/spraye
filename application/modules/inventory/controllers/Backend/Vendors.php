<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Vendors extends MY_Controller{

	// Define create and update rules
	private $rules = [
		'create' => [
			'name' => [
				'rules' => 'min_length[1]|max_length[100]',
				'errors' => [
					'min_length' => 'Validation.suppliers.name_min_length',
					'max_length' => 'Validation.suppliers.name_max_length'
				]
			],
			'internal_name' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					'max_length' => 'Validation.suppliers.internal_name_max_length'
				]
			],
			'company_name' => [
				'rules' => 'permit_empty|max_length[100]',
				'errors' => [
					'max_length' => 'Validation.suppliers.company_name_max_legnth'
				]
			],
			'vat' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					'max_length' => 'Validation.suppliers.vat_max_length'
				]
			],
			'email_address' => [
				'rules' => 'permit_empty|valid_email|max_length[255]',
				'errors' => [
					'valid_email' => 'Validation.suppliers.email_address_invalid',
					'max_length' => 'Validation.suppliers.email_address_max_length'
				]
			],
			'phone_number' => [
				'rules' => 'permit_empty|max_length[20]',
				'errors' => [
					'max_length' => 'Validation.suppliers.phone_number_max_length'
				]
			],
			'address' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					'max_length' => 'Validation.suppliers.address_max_length'
				]
			],
			'city' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					'max_length' => 'Validation.suppliers.city_max_length'
				]
			],
			'country' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					'max_length' => 'Validation.suppliers.country_max_length'
				]
			],
			'state' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					'max_length' => 'Validation.suppliers.state_max_length'
				]
			],
			'zip_code' => [
				'rules' => 'permit_empty|integer|max_length[12]',
				'errors' => [
					'integer' => 'Validation.suppliers.zip_code_invalid',
					'max_length' => 'Validation.suppliers.zip_code_max_length'
				]
			],
			'custom_field1' => [
				'rules' => 'permit_empty'
			],
			'custom_field2' => [
				'rules' => 'permit_empty'
			],
			'custom_field3' => [
				'rules' => 'permit_empty'
			],
			'notes' => [
				'rules' => 'permit_empty'
			]
		],

		'update' => [
			'name' => [
				'rules' => 'permit_empty|min_length[1]|max_length[100]',
				'errors' => [
					'min_length' => 'Validation.suppliers.name_min_length',
					'max_length' => 'Validation.suppliers.name_max_length'
				]
			],
			'internal_name' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					'max_length' => 'Validation.suppliers.internal_name_max_length'
				]
			],
			'company_name' => [
				'rules' => 'permit_empty|max_length[100]',
				'errors' => [
					'max_length' => 'Validation.suppliers.company_name_max_legnth'
				]
			],
			'vat' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					'max_length' => 'Validation.suppliers.vat_max_length'
				]
			],
			'email_address' => [
				'rules' => 'permit_empty|valid_email|max_length[255]',
				'errors' => [
					'valid_email' => 'Validation.suppliers.email_address_invalid',
					'max_length' => 'Validation.suppliers.email_address_max_length'
				]
			],
			'phone_number' => [
				'rules' => 'permit_empty|max_length[20]',
				'errors' => [
					'max_length' => 'Validation.suppliers.phone_number_max_length'
				]
			],
			'address' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					'max_length' => 'Validation.suppliers.address_max_length'
				]
			],
			'city' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					'max_length' => 'Validation.suppliers.city_max_length'
				]
			],
			'country' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					'max_length' => 'Validation.suppliers.country_max_length'
				]
			],
			'state' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					'max_length' => 'Validation.suppliers.state_max_length'
				]
			],
			'zip_code' => [
				'rules' => 'permit_empty|integer|max_length[12]',
				'errors' => [
					'integer' => 'Validation.suppliers.zip_code_invalid',
					'max_length' => 'Validation.suppliers.zip_code_max_length'
				]
			],
			'custom_field1' => [
				'rules' => 'permit_empty'
			],
			'custom_field2' => [
				'rules' => 'permit_empty'
			],
			'custom_field3' => [
				'rules' => 'permit_empty'
			],
			'notes' => [
				'rules' => 'permit_empty'
			]
		]
	];

	public function __construct() {
		parent::__construct();

        if (!$this->session->userdata('email')) {

            $actual_link = $_SERVER[REQUEST_URI];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }

        $this->load->library('parser');

        $this->load->helper('text');

        $this->loadModel();

		$this->rules = (object) $this->rules;
	}

	private function loadModel(){

        $this->load->model("Administrator");

        $this->load->model('Technician_model', 'Tech');

        $this->load->model('Invoice_model', 'INV');

        $this->load->library('form_validation');

        $this->load->model('AdminTbl_customer_model', 'CustomerModel');

        $this->load->model('AdminTbl_company_model', 'CompanyModel');

        $this->load->model('Job_model', 'JobModel');

        $this->load->model('Company_email_model', 'CompanyEmail');

        $this->load->model('Administratorsuper');

        $this->load->model('AdminTbl_program_model', 'ProgramModel');

        $this->load->model('AdminTbl_property_model', 'PropertyModel');

        $this->load->model('Job_model', 'JobModel');

        $this->load->model('Sales_tax_model', 'SalesTax');

        $this->load->model('AdminTbl_product_model', 'ProductModel');

        $this->load->model('Basys_request_modal', 'BasysRequest');

        $this->load->model('Cardconnect_model', 'CardConnectModel');

        $this->load->helper('invoice_helper');

        $this->load->helper('estimate_helper');

        $this->load->helper('report_helper');

        $this->load->model('Property_sales_tax_model', 'PropertySalesTax');

        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');

        $this->load->model('Reports_model', 'RP');

        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');

        $this->load->model('AdminTbl_coupon_model', 'CouponModel');

        $this->load->model('Payment_invoice_logs_model', 'PartialPaymentModel');

        $this->load->model('Refund_invoice_logs_model', 'RefundPaymentModel');

        $this->load->model('ItemVendorsModel', 'ItemVendors');
    }

	
	public function index() {
		$columns = [
			'name',
			'internal_name',
			'company_name',
			'email_address',
			'phone_number',
			'vat_number'
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

		$this->suppliers->setDtParameters($search, $orderBy, $orderDir, $length, $start);

		return $this->respond(array_merge(
			['draw' => $draw],
			$this->suppliers->dtGetAllSuppliers()
		));
	}

	/**
	 * To get a single supplier by ID
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function show($id) {
		$supplier = $this->suppliers->getSupplier($id);

		if(!$supplier)
			return $this->failNotFound(lang('Errors.suppliers.not_found', ['id' => $id]));

		return $this->respond($supplier);
	}

	/**
	 * To create a new supplier
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function create() {
		$data = $this->input->post();
		// die(print_r($data));
		if($data['vendor_zip_code'] == '')
			$data['vendor_zip_code'] = null;

		// if($this->suppliers->getSupplierByname($data['name']))
		// 	return $this->failResourceExists(lang('Errors.suppliers.already_exists_name', ['name' => $data['name']]));
		
		// if($this->suppliers->getSupplierByInternalName($data['internal_name']))
		// 	return $this->failResourceExists(lang('Errors.suppliers.already_exists_internal_name', ['internal_name' => $data['internal_name']]));

		$data['created_by'] = $this->session->userdata['id'];
		$data['company_id'] = $this->session->userdata['company_id'];

		$vendor = $this->db->insert('vendors_tbl',$data);
		if ($vendor) {
			$return_array =  array('status' => 200, 'msg' => 'Vendor created successfully.', 'data' => $vendor);
		} else {
			$return_array =  array('status' => 400, 'msg' => 'Something went wrong', 'data' => array());
		}
		
		echo json_encode($return_array);
	}

	/**
	 * To edit a supplier
	 * 
	 * Method			PUT
	 * Filter			auth:supervisor,admin
	 */
	public function update($id) {
		if(!$this->validateRequestWithRules($this->rules->update))
			return $this->failWithValidationErrors();

		if(!$this->suppliers->find($id))
			return $this->failNotFound(lang('Errors.suppliers.not_found', ['id' => $id]));

		$updateFields = [
			'name',
			'internal_name',
			'company_name',
			'vat',
			'email_address',
			'phone_number',
			'address',
			'city',
			'country',
			'state',
			'zip_code',
			'custom_field1',
			'custom_field2',
			'custom_field3',
			'notes'
		];

		$data = $this->buildUpdateArray($updateFields, true);

		if($data['zip_code'] == '')
			$data['zip_code'] = null;

		if(isset($data['name'])) {
			$duplicateSupplier = $this->suppliers->getSupplierByname($data['name']);
			if($duplicateSupplier && $duplicateSupplier->id != $id)
				return $this->failResourceExists(lang('Errors.suppliers.already_exists_name', ['name' => $data['name']]));
		}
		
		if(isset($data['internal_name'])) {
			$duplicateSupplier = $this->suppliers->getSupplierByInternalName($data['internal_name']);
			if($duplicateSupplier && $duplicateSupplier->id != $id)
				return $this->failResourceExists(lang('Errors.suppliers.already_exists_internal_name', ['internal_name' => $data['internal_name']]));
		}

		$this->suppliers->update($id, $data);

		return $this->respondUpdated($this->suppliers->getSupplier($id));
	}

	/**
	 * To get latest table -- Table with the 5 most recent suppliers
	 * No DataTables features will be allowed
	 * 
	 * Method			GET
	 * Filter			auth:supervisor,admin
	 */
	public function show_latest_table() {
		// If user is supervisor, get only records from warehouses that the supervisor has access to
		if($this->logged_user->role == 'supervisor') {
			$warehouseIds = $this->warehouse_relations->getWarehouseIdsByUser($this->logged_user->id);
			$result = $this->suppliers->dtGetLatest(true, $warehouseIds);
		}else{
			$result = $this->suppliers->dtGetLatest();
		}

		$draw = $this->request->getVar('draw') ?? false;

		return $this->respond(array_merge(
			['draw' => $draw],
			$result
		));
	}

	/**
	 * To delete a supplier
	 * 
	 * Method			DELETE
	 * Filter			auth:admin
	 */
	public function delete($id) {
		// die('delete');
		// if(!$this->suppliers->find($id))
		// 	return $this->failNotFound(lang('Errors.suppliers.not_found', ['id' => $id]));

		$vendor =$this->db->delete('vendors_tbl', array('vendor_id' => $id));
		
		// Delete supplier-item relations
		// $this->ItemVendors->deleteSupplierRelations($id);
		$relations =$this->db->delete('item_vendors', array('vendor_id' => $id));
		
		// We're done
		if (!$vendor == 1) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("inventory/Frontend/Vendors");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Vendor </strong> deleted successfully</div>');
            redirect("inventory/Frontend/Vendors");
        }
	}

	/**
	 * To get a list of suppliers, to be used in a select
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function list() {
		return $this->respond($this->suppliers->getSuppliersList());
	}

	/**
	 * To export a CSV file with all suppliers (admins only)
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function export() {
		// Get list of suppliers, with as much information as we can get
		$suppliers = $this->suppliers->getDetailedList();

		// Create a filename and export!
		$filename = date('Y_m_d__H_i_s');
		$filename = "suppliers__{$filename}.csv";

		helper('csv');

		die(offer_csv_download($suppliers, $filename));
	}
}