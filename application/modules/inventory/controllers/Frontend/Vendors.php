<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Vendors extends MY_Controller
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

        $this->load->model('VendorsModel', 'VendorsModel');
    }


	public function index($supplierId = false) {
		// $this->data['route'] = 'suppliers';

		/*
		$this->data['categories'] = $this->categories->getCategoriesList();
		$this->data['brands'] = $this->brands->getBrandsList();
		$this->data['suppliers'] = $this->suppliers->getSuppliersList();
		$this->data['itemId'] = $itemId;
		*/
		// $this->data['supplierId'] = $supplierId;
		$data['supplierId'] = '';
		$data = [
			// 'extend'        => 'templates/master',
			'new'        => 'vendors/modals/vendor_modal',
			'edit'        => 'vendors/modals/edit_vendor_modal',
			'error'         => 'components/error_modal',
			'conformation'        => 'components/confirmation_modal',
			
		];
        $where_arr = array(
            'vendor_id !=' => 0
        );
        $data['all_vendors'] = $this->VendorsModel->getAllvendors($where_arr);
        // die(print_r($data['all_vendors']));


		// return view('suppliers/suppliers', $this->data);
		$page["active_sidebar"] = "vendors";
        $page["page_name"] = 'Vendors';
        $page["page_content"] = $this->load->view("inventory/vendors/view_vendors", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
	}

	public function new() {
		// $this->data['route'] = 'suppliers';

		/*
		$this->data['categories'] = $this->categories->getCategoriesList();
		$this->data['brands'] = $this->brands->getBrandsList();
		*/
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $settings->references_purchase_return_prepend = '';
        $settings->references_purchase_return_append = '';
        $data['settings'] =  $settings;
        $data['purchaseId'] = 1;
        // die(print_r($data['brands']));

        $page["active_sidebar"] = "vendors";
        $page["page_name"] = 'Vendors';
        $page["page_content"] = $this->load->view("inventory/vendors/new_vendor", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);

		// return view('suppliers/new_supplier', $this->data);
	}

    public function ajaxGetVendors()
    {
        $tblColumns = array(
            0 => 'vendor_name',
            1 => 'internal_name',
            2 => 'company_name',
            3 => 'vendor_email_address',
            4 => 'vendor_phone_number',
            5 => 'vendor_number',
            6 => 'actions'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'vendors_tbl.company_id' => $company_id,
            'vendors_tbl.is_archived' => 0
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
            $tempdata  = $this->VendorsModel->getVendorDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->VendorsModel->getVendorDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->VendorsModel->getVendorDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->VendorsModel->getVendorDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        //  $var_last_query = $this->db->last_query ();

        //  die(print_r($var_last_query));

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {

                // die(print_r($value));

                // set row data
                $data[$i]['vendor_name'] = $value->vendor_name;
                $data[$i]['internal_name'] = $value->internal_name;
                $data[$i]['company_name'] = $value->company_name;
                $data[$i]['vendor_email_address'] = $value->vendor_email_address;
                $data[$i]['vendor_phone_number'] = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", str_replace(' ','', str_replace('-','', $value->vendor_phone_number)));
                $data[$i]['vendor_number'] = $value->vendor_number;
                $data[$i]['actions'] = '<span class="pr-5"><a href="#" data-toggle="modal" data-target="#modal_edit_vendor" data-v_name="' . $value->vendor_name . '" data-int_name="' . $value->internal_name . '" data-id="'. $value->vendor_id .'" data-company="'. $value->company_name .'" data-email="'. $value->vendor_email_address .'" data-phone="'. formatPhoneNum($value->vendor_phone_number) .'" data-num="'. $value->vendor_number .'" data-street="'. $value->vendor_street_address .'" data-city="'. $value->vendor_city .'" data-state="'. $value->vendor_state .'" data-zip="'. $value->vendor_zip_code .'" data-country="'. $value->vendor_country .'" data-cust1="'. $value->custom_field1 .'" data-cust2="'. $value->custom_field2 .'" data-cust3="'. $value->custom_field3 .'" data-notes="'. $value->notes .'" data-terms="'. $value->terms .'" data-po_discount="'. $value->po_discount .'"
                 class="button-next modal_trigger"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a href="' .  base_url('inventory/Frontend/Vendors/vendorDelete/') . $value->vendor_id . '" data-url="' . $value->vendor_id . '"class="confirm_item_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></span>';
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

    public function vendorDelete($vendor_id)
    {
        //print($item_type_id);

        $param = array('is_archived' => 1);

        
            $result = $this->VendorsModel->updateVendorsTbl($vendor_id, $param);

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("inventory/Frontend/Vendors/");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Vendor </strong>deleted successfully</div>');
                redirect("inventory/Frontend/Vendors/");
            }        

        
    }

    public function editVendor()
    {

        $data = $this->input->post();
        
        // die(print_r($data));

        $data_arr = array(
            'vendor_name'=> $data['edit_vendor_name'], 
            'internal_name' => $data['edit_internal_name'],
            'company_name' => $data['edit_company_name'],
            'vendor_email_address' => $data['edit_vendor_email_address'],
            'vendor_phone_number' => $data['edit_vendor_phone_number'],
            'vendor_number' => $data['edit_vendor_number'],
            'vendor_street_address' => $data['edit_vendor_street_address'],
            'vendor_city' => $data['edit_vendor_city'],
            'vendor_state' => $data['edit_vendor_state'],
            'vendor_zip_code' => $data['edit_vendor_zip_code'],
            'vendor_country' => $data['edit_vendor_country'],
            'custom_field1' => $data['edit_custom_field1'],
            'custom_field2' => $data['edit_custom_field2'],
            'custom_field3' => $data['edit_custom_field3'],
            'notes' => $data['edit_notes'],
            'terms' => $data['terms'],
            'po_discount' => $data['po_discount'],
        );

        // die(print_r($data_arr));

        $result = $this->VendorsModel->updateVendorsTbl($data['edit_vendor_id'], $data_arr);

        // die(print_r($result));

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("inventory/Frontend/Vendors/");

        } else {

            // die(print_r($result));

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Vendor </strong>updated successfully.</div>');

            redirect("inventory/Frontend/Vendors/");

        }   

    }

    public function newVendor()
    {
        // print($item_type_id);

        $data = $this->input->post();

        $company_id = $this->session->userdata['company_id'];
        $user_id = $this->session->userdata['id'];

        $data_arr = array(
            'vendor_name'=> $data['vendor_name'], 
            'internal_name' => $data['internal_name'],
            'company_name' => $data['company_name'],
            'vendor_email_address' => $data['vendor_email_address'],
            'vendor_phone_number' => $data['vendor_phone_number'],
            'vendor_number' => $data['vendor_number'],
            'vendor_street_address' => $data['vendor_street_address'],
            'vendor_city' => $data['vendor_city'],
            'vendor_state' => $data['vendor_state'],
            'vendor_zip_code' => $data['vendor_zip_code'],
            'vendor_country' => $data['vendor_country'],
            'custom_field1' => $data['custom_field1'],
            'custom_field2' => $data['custom_field2'],
            'custom_field3' => $data['custom_field3'],
            'notes' => $data['notes'],
            'terms' => $data['terms'],
            'company_id' => $company_id,
            'created_by' => $user_id
        );


            $result = $this->VendorsModel->createNewVendor($data_arr);

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("inventory/Frontend/Vendors/");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Vendor </strong>created successfully.</div>');
                redirect("inventory/Frontend/Vendors/");
            }     

        
    }

    public function exportVendorsCSV($value=''){

        $company_id = $this->session->userdata['company_id'];

        $where = array(
            'company_id' => $company_id,
            'is_archived' => 0
        );

        $data = $this->VendorsModel->getAllVendors($where);
   
        if($data){
  
            $delimiter = ",";
            $filename = "vendors_" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Vendor Name','Internal Name','Company Name','Email Address','Phone Number', 'Vendor Number', 'Street Address', 'City', 'State', 'Zip Code', 'Country', 'Custom Field 1', 'Custom Field 2', 'Custom Field 3', 'Notes');
  
            fputcsv($f, $fields, $delimiter);
  
          foreach ($data as $key => $value) {
  
            $lineData = array($value->vendor_name, $value->internal_name, $value->company_name, $value->vendor_email_address, $value->vendor_phone_number, $value->vendor_number, $value->vendor_street_address, $value->vendor_city, $value->vendor_state, $value->vendor_zip_code, $value->vendor_country, $value->custom_field1, $value->custom_field2, $value->custom_field3, $value->notes);
           
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
         redirect("inventory/Frontend/Vendors/");
      }
  
  
    }
}