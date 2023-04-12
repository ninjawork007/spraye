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

    public function __construct(){

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
        $this->load->model('BrandsModel', 'BrandModel');
    }


	public function index($brandId = false) {
		// $this->data['route'] = 'brands';
		// $this->data['brandId'] = $brandId;
		$data = [
			// 'extend'        => 'templates/master',
			'brand_modal' => 'brands/modals/brand_modal',
			'edit_brand_modal' => 'brands/modals/edit_brand_modal',
			'component_error_modal' => 'components/error_modal',
			'component_confirmation_modal' => 'components/confirmation_modal',
		];
        $where_arr = array(
            'brand_id !=' => 0
        );
        $data['all_brands'] = $this->BrandModel->getAllBrands($where_arr);
        // die(print_r($data['all_brands']));

		// return view('brands/brands', $this->data);
		$page["active_sidebar"] = "brands";
        $page["page_name"] = 'Brands';
        $page["page_content"] = $this->load->view("inventory/brands/view_brands", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
	}

	public function new() {
		// $this->data['route'] = 'brands';
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $settings->references_purchase_return_prepend = '';
        $settings->references_purchase_return_append = '';
        $data['settings'] =  $settings;
        $data['purchaseId'] = 1;
        // die(print_r($data['brands']));

        $page["active_sidebar"] = "brands";
        $page["page_name"] = 'Brands';
        $page["page_content"] = $this->load->view("inventory/brands/new_brand", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);

	}

    public function ajaxGetBrands()
    {
        $tblColumns = array(
            0 => 'brand_name',
            1 => 'created_by',
            2 => 'brands_tbl.created_at',
            3 => 'brand_description',
            4 => 'items_registered',
            5 => 'actions'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'brands_tbl.company_id' => $company_id,
            'brands_tbl.is_archived' => 0
        );

        $data  = array();

        // seardch through each col, and if a search value, let's search for it
        $where_like = array();
        if (is_array($this->input->post('columns'))) {
            $columns = $this->input->post('columns');
            foreach ($columns as $column) {
                if (isset($column['search']['value']) && !empty($column['search']['value'])) {
                    $col = $column['data'];
                    $val = $column['search']['value'];
                    $where_like[$col] = $val;
                }
            }
        }

        // get data (2 separate fns for search and non search)
        if (empty($this->input->post('search')['value'])) {
            $tempdata  = $this->BrandModel->getBrandDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->BrandModel->getBrandDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->BrandModel->getBrandDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->BrandModel->getBrandDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        //  $var_last_query = $this->db->last_query ();

        //  die(print_r($var_last_query));

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {

                // die(print_r($value));

                $created_by_name = $this->BrandModel->getCreatedByName($value->created_by);

                $items_registered = $this->BrandModel->getRegisteredItemsCount(array('brand_id' => $value->brand_id, 'company_id' => $company_id));

                // set row data
                $data[$i]['brand_name'] = $value->brand_name;
                $data[$i]['created_by'] = $created_by_name;
                $data[$i]['created_at'] = $value->created_at;
                $data[$i]['brand_description'] = $value->brand_description;
                $data[$i]['items_registered'] = $items_registered;
                $data[$i]['actions'] = '<span class="pr-5"><a href="#" data-toggle="modal" data-target="#modal_edit_brand" data-name="' . $value->brand_name . '" data-desc="' . $value->brand_description . '" data-id="'. $value->brand_id .'" class="button-next modal_trigger"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a href="' .  base_url('inventory/Frontend/Brands/brandDelete/') . $value->brand_id . '" data-url="' . $value->brand_id . '"class="confirm_item_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></span>';
                $i++;
            }
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => $var_total_item_count_for_pagination, // "(filtered from __ total entries)"
            "recordsFiltered" => $var_total_item_count_for_pagination, // actual total that determines page counts
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function brandDelete($brand_id)
    {
        //print($item_type_id);

        $param = array('is_archived' => 1);

            $result = $this->BrandModel->updateBrandsTbl($brand_id, $param);

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("inventory/Frontend/Brands/");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Brand </strong>deleted successfully</div>');
                redirect("inventory/Frontend/Brands/");
            }        
    }

    public function editBrand()
    {
        // print($item_type_id);

        $data = $this->input->post();

        $data_arr = array('brand_name'=> $data['brand_name'], 'brand_description' => $data['brand_description']);

            $result = $this->ItemTypesModel->updateItemTypesTbl($data['item_type_id'], $data_arr);

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("inventory/Frontend/Brands/");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Brand </strong>updated successfully.</div>');
                redirect("inventory/Frontend/Brands/");
            }        
        
    }

    public function newBrand()
    {
        // print($item_type_id);

        $data = $this->input->post();

        $company_id = $this->session->userdata['company_id'];
        $user_id = $this->session->userdata['id'];

        $data_arr = array(
            'brand_name'=> $data['brand_name'], 
            'brand_description' => $data['brand_description'],
            'company_id' => $company_id,
            'created_by' => $user_id
        );


            $result = $this->BrandModel->createNewBrand($data_arr);

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("inventory/Frontend/Brands/");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Brand </strong>created successfully.</div>');
                redirect("inventory/Frontend/Brands/");
            }          
    }

    public function exportBrandsCSV($value=''){

        $company_id = $this->session->userdata['company_id'];

        $where = array(
            'brands_tbl.company_id' => $company_id,
            'brands_tbl.is_archived' => 0
        );

        $data = $this->BrandModel->getAllBrands($where);
   
        if($data){
  
            $delimiter = ",";
            $filename = "brands_" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Brand Name','Created By','Created At','Brand Description','Items Registered');
  
            fputcsv($f, $fields, $delimiter);
  
          foreach ($data as $key => $value) {
   
            $created_by_name = $this->BrandModel->getCreatedByName($value->created_by);

            $items_registered = $this->BrandModel->getRegisteredItemsCount(array('brand_id' => $value->brand_id, 'company_id' => $company_id));
  
            $lineData = array($value->brand_name, $created_by_name, $value->created_at, $value->brand_description, $items_registered);
           
            fputcsv($f, $lineData, $delimiter);           
          }
  
          //move back to beginning of file
          fseek($f, 0);
          
          //set headers to download file rather than displayed
          header('Content-Type: text/csv');
            //  $pathName =  "down/".$filename;
          header('Content-Disposition: attachment; filename="' .$filename. '";');
          
          //output all remaining data on a file pointer
          fpassthru($f);
  
        } else {
         $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
         redirect("inventory/Frontend/Brands/");
      }
  
  
    }
      
}