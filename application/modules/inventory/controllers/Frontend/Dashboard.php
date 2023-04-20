<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Dashboard extends MY_Controller{

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
        $this->load->model('ItemsModel', 'ItemModel');
        $this->load->model('PurchasesModel', 'PurchaseModel');
        $this->load->model('PurchasesReturnsModel', 'ReturnModel');
        $this->load->model('PurchasesReceivingModel', 'ReceivingModel');
    }

	public function index() {

    $company_id = $this->session->userdata['company_id'];

       $data['unpaid'] = $this->PurchaseModel->getUnpaidPOAmount($company_id);
       $data['unpaid_ct'] = $this->PurchaseModel->getUnpaidPOCount($company_id);
       $data['open_value'] = $this->PurchaseModel->getOpenPOAmount($company_id);
        // die(print_r($data['open_value']));

        $company_id = $this->session->userdata['company_id'];

        $where_arr = array(
            'company_id' => $company_id,
            'is_archived' => 0
        );
        $data['all_items'] = $this->ItemModel->GetAllItems($where_arr);

        $data['all_types'] = $this->ItemModel->getCompanyItemTypes($company_id);

        $data['all_brands'] = $this->ItemModel->getCompanyBrands($company_id);

        $data['all_vendors'] = $this->ItemModel->getCompanyVendors($company_id);

        $data['all_products'] = $this->ItemModel->getCompanyProducts($company_id);

        $products_arr = array();

        foreach($data['all_products'] as $prod){
            $prod_str = $prod->product_id . '::' . $prod->product_name . '::' . $prod->product_cost_unit . '::' . $prod->product_type;
            if(!in_array($prod_str, $products_arr)){
                array_push($products_arr, $prod_str);
            }
        }
        
        $data['products_str'] = implode('<::>', $products_arr);

        $value_on_hand = 0;
        foreach($data['all_items'] as $item){
            $item_where = array(
                'company_id' => $company_id,
                'quantity_item_id' => $item->item_id
            );
            // Grab total aount of items in all locations and sublocations
            $total_units_on_hand = $this->ItemModel->getTotalItemAmount($item_where);
           
            if($total_units_on_hand != ''){
                $value_on_hand += ($total_units_on_hand) * $item->average_cost_per_unit;
                // $value_on_hand += ($total_units_on_hand*$item->unit_amount) * $item->average_cost_per_unit;
            }
        }
        $data['value_on_hand'] = $value_on_hand;

        $where_arr = array(
            'purchase_order_tbl.company_id' => $company_id,
            "purchase_order_status" => "!=3"
        );
        $data['all_purchases'] = $this->PurchaseModel->getAllPurchases($where_arr);
        $page["active_sidebar"] = "dashboard";
        $page["page_name"] = 'Inventory Dashboard';
        $page["page_content"] = $this->load->view("inventory/dashboard", $data, true);

        $this->layout->inventoryTemplateTable($page);
	}
}