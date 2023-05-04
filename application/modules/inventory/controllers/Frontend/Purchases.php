<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Purchases extends MY_Controller{

    public function __construct(){

        parent::__construct();

        if (!$this->session->userdata('email')) {
            $actual_link = $_SERVER[REQUEST_URI];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }

        $this->load->library('parser');
        $this->load->library('aws_sdk');
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
        $this->load->helper('inventory_helper');
        $this->load->model('Property_sales_tax_model', 'PropertySalesTax');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('Reports_model', 'RP');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('AdminTbl_coupon_model', 'CouponModel');
        $this->load->model('Payment_invoice_logs_model', 'PartialPaymentModel');
        $this->load->model('Refund_invoice_logs_model', 'RefundPaymentModel');
        $this->load->model('PurchasesModel', 'PurchasesModel');
        $this->load->model('PurchasesReturnsModel', 'ReturnsModel');
        $this->load->model('PurchasesReceivingModel', 'ReceivingsModel');
        $this->load->model('LocationsModel', 'LocationsModel');
        $this->load->model('VendorsModel', 'VendorsModel');
        $this->load->model('Job_product_model', 'JobAssignProduct');
    }


    public function index($purchaseId = false) {
        $where = array('purchase_order_tbl.company_id' =>$this->session->userdata['company_id'], "purchase_order_status" => "!=3");
        $data['all_purchases'] = $this->PurchasesModel->getAllPurchases($where);
        $page["active_sidebar"] = "purchases";
        $page["page_name"] = 'Purchases';
        $data['list_locations'] = $this->LocationsModel->getLocationsList();
        $page["page_content"] = $this->load->view("inventory/purchases/purchases", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
    }

    public function new() {
        $data['vendors'] = $this->VendorsModel->getVendorsList();
        $data['list_locations'] = $this->LocationsModel->getLocationsList();
        $data['last_purchase_order_id'] = $this->PurchasesModel->getLastIdPlusOne();
        $data['list_sub_locations'] = $this->LocationsModel->getSubLocationsList();
        $data['list_vendors'] = $this->VendorsModel->getVendorsList();
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $data['settings'] =  $settings;
        $page["active_sidebar"] = "purchases";
        $page["page_name"] = 'New Purchases';
        $page["page_content"] = $this->load->view("inventory/purchases/new_purchase", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
    }

    public function receiving($purchaseId = false) {

        $where_arr = array(
            'purchase_receiving_tbl.company_id =' => $this->session->userdata['company_id'],
        );
        
        $data['all_receiving'] = $this->ReceivingsModel->getAllReceivingTable($where_arr);
        $page["active_sidebar"] = "receiving";
        $page["page_name"] = 'Receiving';
        $page["page_content"] = $this->load->view("inventory/purchases/purchases_receiving", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
    }

    public function newReceiving($purchase_order_id) {
        $data['purchase_order_id'] = $purchase_order_id;
        $data['purchase_order'] = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_order_id));
        $data['list_locations'] = $this->LocationsModel->getLocationsList();
        $data['list_sub_locations'] = $this->LocationsModel->getSubLocationsList();
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $data['settings'] =  $settings;
        $page["active_sidebar"] = "receiving";
        $page["page_name"] = 'New Purchase Order Receiving';
        $page["page_content"] = $this->load->view("inventory/purchases/new_receiving", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
    }

    public function returns($returnId = false) {
        
        $where_arr = array(
            'return_id !=' => 0
        );

        $data['all_returns'] = $this->ReturnsModel->getAllReturns($where_arr);
        $page["active_sidebar"] = "returns";
        $page["page_name"] = 'Returns';
        $page["page_content"] = $this->load->view("inventory/purchases/purchases_returns", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
    }

    public function newReturn($purchase_order_id = false) {
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $settings->references_purchase_return_prepend = '';
        $settings->references_purchase_return_append = '';
        $data['list_locations'] = $this->LocationsModel->getLocationsList();
        $data['list_vendors'] = $this->VendorsModel->getVendorsList();
        $data['settings'] =  $settings;
        $data['purchaseId'] = 1;
        $page["active_sidebar"] = "returns";
        $page["page_name"] = 'New Purchase Order Return';
        $page["page_content"] = $this->load->view("inventory/purchases/new_purchase_return", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
    }

    public function ajaxGetPurchases(){
        $tblColumns = array(
            0 => 'purchase_order_number',
            1 => 'location_id',
            2 => 'created_at',
            3 => 'vendor_id',
            4 => 'grand_total',
            5 => 'action',
           
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'purchase_order_tbl.company_id' => $company_id,
            'purchase_order_tbl.is_archived' => 0
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
            $tempdata  = $this->PurchasesModel->getPurchaseDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->PurchasesModel->getPurchaseDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->PurchasesModel->getPurchaseAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->PurchasesModel->getPurchaseAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                // set row data
                $data[$i]['purchase_order_date'] = $value->purchase_order_date;
                $data[$i]['purchase_sent_status'] = $value->purchase_sent_status;
                $data[$i]['purchase_order_status'] = $value->purchase_order_status;
                $data[$i]['purchase_paid_status'] = $value->purchase_paid_status;
                $data[$i]['estimated_delivery_date'] = $value->estimated_delivery_date;
                $data[$i]['location_id'] = $value->location_name;
                $data[$i]['vendor_id'] = $value->vendor_name;
                $data[$i]['items'] = $value->items;
                $data[$i]['grand_total'] = $value->grand_total;
                $data[$i]['actions'] = '<span class="pr-5"><a href="#" data-poid="'.$value->purchase_order_id.'" data-pnum="'.$value->purchase_order_number.'" data-location="'.$value->location_id.'" data-location-name="'.$value->location_name.'" data-podate="'.$value->purchase_order_date.'" data-vendor="'.$value->vendor_id.'" data-vname="'.$value->vendor_name.'" data-vaddress="'.$value->vendor_street_address.'" data-vcity="'.$value->vendor_city.'" data-vstate="'.$value->vendor_state.'" data-vzip="'.$value->vendor_zip_code.'" data-vcountry="'.$value->vendor_country.'"  data-total="'.$value->grand_total.'" data-toggle="modal" data-target="#view_purchase_order" class="button-next modal_trigger_purchase_order"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a href="' .  base_url('inventory/Backend/Purchases/delete/') . $value->purchase_order_id . '" data-url="' . $value->purchase_order_id . '"class="confirm_delete_purchase_order button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></span>';
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

    public function ajaxGetReturns(){
        $tblColumns = array(
            0 => 'return_id',
            1 => 'purchase_order_number',
            2 => 'purchase_order_id',
            3 => 'return_items',
            4 => 'created_at',
            5 => 'updated_at',
            6 => 'action',
           
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'purchase_return_tbl.company_id' => $company_id,
            'purchase_return_tbl.is_archived' => 0
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
            $tempdata  = $this->ReturnsModel->getReturnDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->ReturnsModel->getReturnDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->ReturnsModel->getReturnAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->ReturnsModel->getReturnAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                // set row data
                $data[$i]['return_id'] = $value->return_id;
                $data[$i]['purchase_order_number'] = $value->purchase_order_number;
                $data[$i]['purchase_order_id'] = $value->purchase_order_id;
                $data[$i]['return_items'] = $value->return_items;
                $data[$i]['created_at'] = $value->created_at;
                $data[$i]['updated_at'] = $value->updated_at;
                $data[$i]['actions'] = '<span class="pr-5"><a href="#" data-rid="'.$value->return_id.'" data-poid="'.$value->purchase_order_id.'" data-pnum="'.$value->purchase_order_number.'" data-location="'.$value->location_id.'" data-location-name="'.$value->location_name.'" data-created="'.$value->created_at.'" data-vendor="'.$value->vendor_id.'" data-vname="'.$value->vendor_name.'" data-vaddress="'.$value->vendor_street_address.'" data-vcity="'.$value->vendor_city.'" data-vstate="'.$value->vendor_state.'" data-vzip="'.$value->vendor_zip_code.'" data-vcountry="'.$value->vendor_country.'" data-updated="'.$value->updated_at.'" data-pid="'.$value->purchase_order_id.'" data-items="'.$value->return_items.'" data-toggle="modal" data-target="#view_purchase_return" class="button-next modal_trigger_purchase_return"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a href="' .  base_url('inventory/Backend/Purchases/delete/') . $value->return_id . '" data-url="' . $value->return_id . '"class="confirm_delete_purchase_order button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></span>';
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

    public function getAllPurchaseOrdersBySearch($status){
 
        $where = array('purchase_order_tbl.company_id' =>$this->session->userdata['company_id']);
    
        if($status!=4) {
        $where['purchase_sent_status'] = $status;
        }
        
        $data['all_purchases'] = $this->PurchasesModel->getAllPurchases($where);
        $where = array('company_id' =>$this->session->userdata['company_id']);
    
         $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
    
         $body  =  $this->load->view('inventory/purchases/ajax_data',$data,TRUE);
         echo $body;
    
    } 

    public function getAllPurchaseOrdersByLocartion($status){
        $where = array('purchase_order_tbl.company_id' =>$this->session->userdata['company_id']);
        $where['purchase_order_tbl.location_id'] = $status;
        $data['all_purchases'] = $this->PurchasesModel->getAllPurchases($where);
        $where = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $body  =  $this->load->view('inventory/purchases/ajax_data',$data,TRUE);
        echo $body;
    } 

    public function getAllPurchaseOrdersByPO($status){
 
        $where = array('purchase_order_tbl.company_id' =>$this->session->userdata['company_id']);
    
        if($status!=5) {
        $where['purchase_order_status'] = $status;
        }
       
        $data['all_purchases'] = $this->PurchasesModel->getAllPurchases($where);
        $where = array('company_id' =>$this->session->userdata['company_id']);
    
         $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
    
         $body  =  $this->load->view('inventory/purchases/ajax_data',$data,TRUE);
         echo $body;
    
    } 

    public function getAllPurchaseOrdersByPayment($status){
 
        $where = array('purchase_order_tbl.company_id' =>$this->session->userdata['company_id']);
    
        if($status!=4) {
        $where['purchase_paid_status'] = $status;
        }
        
        $data['all_purchases'] = $this->PurchasesModel->getAllPurchases($where);
        $where = array('company_id' =>$this->session->userdata['company_id']);
    
         $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
    
         $body  =  $this->load->view('inventory/purchases/ajax_data',$data,TRUE);
         echo $body;
    
     } 

     public function getAllReturnOrdersBySearch($status){
 
        $where = array('purchase_order_tbl.company_id' =>$this->session->userdata['company_id']);
    
        if($status!=4) {
        $where['purchase_sent_status'] = $status;
        }
       
        $data['all_purchases'] = $this->PurchasesModel->getAllPurchases($where);
        $where = array('company_id' =>$this->session->userdata['company_id']);
    
         $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
    
         $body  =  $this->load->view('inventory/purchases/ajax_data',$data,TRUE);
         echo $body;
    } 

    public function viewOrder($purchase_id) {
        $data['new_purchase'] = $this->PurchasesModel->getPurchase(array('purchase_order_tbl.purchase_order_id' => $purchase_id));
        $data['purchase_id'] = $purchase_id;
        $data['vendors'] = $this->VendorsModel->getVendorsList();
        $data['list_locations'] = $this->LocationsModel->getLocationsList();
        $data['list_sub_locations'] = $this->LocationsModel->getSubLocationsList();
        $data['list_vendors'] = $this->VendorsModel->getVendorsList();
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $data['settings'] =  $settings;
        $page["active_sidebar"] = "purchases";
        $page["page_name"] = 'View Purchase Order';
        if($data['new_purchase'][0]->purchase_sent_status == 0){
            $page["page_content"] = $this->load->view("inventory/purchases/edit_purchase_order", $data, TRUE);
        } else {
            $page["page_content"] = $this->load->view("inventory/purchases/view_purchase", $data, TRUE);
        }
        
        $this->layout->inventoryTemplateTable($page);
    }

    public function viewReturn($return_id) {
        $data['purchase_return'] = $this->ReturnsModel->getReturn(array('purchase_return_tbl.return_id' => $return_id));
        $data['purchase_order_id'] = $data['purchase_return'][0]->purchase_order_id;
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $data['settings'] =  $settings;
        $page["active_sidebar"] = "returns";
        $page["page_name"] = 'Purchase Order Return';
        $page["page_content"] = $this->load->view("inventory/purchases/view_return", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
    }

    public function viewReceiving($receiving_id) {
        $data['purchase_receiving'] = $this->ReceivingsModel->getReceiving(array('purchase_receiving_tbl.purchase_receiving_id' => $receiving_id));
        $data['receiving_id'] = $receiving_id;
        $data['purchase_order_id'] = $data['purchase_receiving'][0]->purchase_order_id;
        $data['list_locations'] = $this->LocationsModel->getLocationsList();
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $data['settings'] =  $settings;
        $page["active_sidebar"] = "receiving";
        $page["page_name"] = 'Purchase Order Receiving';
        $page["page_content"] = $this->load->view("inventory/purchases/view_receiving", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
    }

    public function changeStatusSent() {

        $data =  $this->input->post();
       
        $where = array(
          'purchase_order_tbl.purchase_order_id' =>$data['purchase_order_id']
        );

        ##### if draft status
        if ($data['status'] == 0) {
            
            $purchase_order = $this->PurchasesModel->getOnePurchase($where);
            
            $param = array(
                'purchase_sent_status' =>$data['status'],
                'updated_at' => date("Y-m-d H:i:s"),
            );
      
        }

        ##### if opened status
        if ($data['status']== 2 ) {
          $purchase_order = $this->PurchasesModel->getOnePurchase($where);
        
            $param = array(
                'purchase_sent_status' =>$data['status'],
                'updated_at' => date("Y-m-d H:i:s"),
                'open_date' => date("Y-m-d H:i:s")
            );
            
        }
      
       ##### if sent status
        if ($data['status'] == 1) {
            
            $purchase_order = $this->PurchasesModel->getOnePurchase($where);
            
            $param = array(
                'purchase_sent_status' =>$data['status'],
                'updated_at' => date("Y-m-d H:i:s"),
                'sent_date' => date("Y-m-d H:i:s")
            );

            ##### Adding Email and text logic here
            $company_id = $this->session->userdata['company_id'];
            $purchase_order_id  = $purchase_order->purchase_order_id;
            $vendor_id  = $purchase_order->vendor_id;
            if(isset($purchase_order->notes) && $purchase_order->notes != ''){
                $data['msgtext'] = $purchase_order->notes;
            }else{
                $data['msgtext'] = '';
            }
            
            $data['vendor_details'] = $this->VendorsModel->getOneVendor($vendor_id);
            $data['link'] =  base_url('welcome/pdfPurchaseOrder/').base64_encode($purchase_order_id);
            $data['link_acc'] =  base_url('welcome/PurchaseOrderAccept/').base64_encode($purchase_order_id);
            $where_company = array('company_id' =>$company_id);
            $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
            $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
            $body = $this->load->view('inventory/purchases/purchase_order_email',$data,true);
            $where_company['is_smtp'] = 1;
            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
            if (!$company_email_details) {
                $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
            } 
            
            $res = Send_Mail_dynamic($company_email_details,$data['vendor_details']->vendor_email_address,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Purchase Order Details');
      
        }

        $result = $this->PurchasesModel->updatePurchaseOrder($where, $param);
        
        if ($result) {
            echo "true";
        } else {
            echo "false";
        }
          
        $purchase_order = $this->PurchasesModel->getOnePurchase($where);

    }

    public function changeStatusPO() {

        $data =  $this->input->post();
            
        $where = array(
          'purchase_order_tbl.purchase_order_id' =>$data['purchase_order_id']
        );
      
        if($data['status'] == 1){
            $param = array(
                'purchase_sent_status' =>2,
                'sent_date' =>date("Y-m-d H:i:s"),
                'open_date' =>date("Y-m-d H:i:s"),
                'purchase_order_status' =>$data['status'],
                'updated_at' => date("Y-m-d H:i:s")
            );
        } else {
            $param = array(
              'purchase_order_status' =>$data['status'],
              'updated_at' => date("Y-m-d H:i:s")
            );
        }
      
        $result = $this->PurchasesModel->updatePurchaseOrder($where, $param);

        if ($result) {
            echo "true";
        } else {
            echo "false";
        }
          
    }

    public function changeStatusPaid() {

        $data =  $this->input->post();
        
        $where = array(
          'purchase_order_tbl.purchase_order_id' =>$data['purchase_order_id']
        );
      
        $param = array(
          'purchase_paid_status' =>$data['status'],
          'updated_at' => date("Y-m-d H:i:s")
        );
      
        $result = $this->PurchasesModel->updatePurchaseOrder($where, $param);

        if($data["status"] == 1){
            $company_id = $this->session->userdata['company_id'];
            $purchase_order_id =   $data['purchase_order_id'];
              
             // get second message
            $message  = "PO Status changed to Ready for Payment";
            $data['msgtext'] =   $message[0];

            // get first message    
            $purchase_order = $this->PurchasesModel->getOnePurchase(['purchase_order_tbl.purchase_order_id' => $purchase_order_id]);    
            $data['msgtext_one'] = $purchase_order->notes;
            $data['vendor_details'] = $this->VendorsModel->getOneVendor($purchase_order->vendor_id);
            $data['link'] =  base_url('welcome/pdfPurchaseOrder/').base64_encode($purchase_order_id);
            $data['link_acc'] =  base_url('welcome/PurchaseOrderAccept/').base64_encode($purchase_order_id);
          
            $where = array('purchase_order_id' =>$purchase_order_id);    
            $param = array('purchase_sent_status' =>1,'updated_at' => date("Y-m-d H:i:s"),
            'sent_date' => date("Y-m-d H:i:s"));   
            $this->PurchasesModel->updatePurchaseOrder($where,$param);

            $where_company = array('company_id' =>$company_id);
          
            $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
            $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
          
            $body = $this->load->view('inventory/purchases/purchase_order_email',$data,true);
            
            $where_company['is_smtp'] = 1;
            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
                        
            if (!$company_email_details) {
                $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
            } 

            $res = Send_Mail_dynamic($company_email_details, $data['vendor_details']->vendor_email_address,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Purchase Order Details');
        }

        if ($result) {
            echo "true";
        } else {
            echo "false";
        }
          
    }

    public function sendPdfMail() {

        $company_id = $this->session->userdata['company_id'];
        $purchase_order_id =   $this->input->post('purchase_order_id');
        $purchase_order_number  = $this->input->post('purchase_order_number');
          
         // get second message
        $message  = $this->input->post('message');
        $data['msgtext'] =   $message[0];

        // get first message    
        $purchase_order = $this->PurchasesModel->getOnePurchase(['purchase_order_tbl.purchase_order_id' => $purchase_order_id]);    
        $data['msgtext_one'] = $purchase_order->notes;
        $data['vendor_details'] = $this->VendorsModel->getOneVendor($purchase_order->vendor_id);
        $data['link'] =  base_url('welcome/pdfPurchaseOrder/').base64_encode($purchase_order_id);
        $data['link_acc'] =  base_url('welcome/PurchaseOrderAccept/').base64_encode($purchase_order_id);
      
        $where = array('purchase_order_id' =>$purchase_order_id);    
        $param = array('purchase_sent_status' =>1,'updated_at' => date("Y-m-d H:i:s"),
        'sent_date' => date("Y-m-d H:i:s"));   
        $this->PurchasesModel->updatePurchaseOrder($where,$param);

        $where_company = array('company_id' =>$company_id);
      
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
      
        $body = $this->load->view('inventory/purchases/purchase_order_email',$data,true);
        
        $where_company['is_smtp'] = 1;
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
                    
        if (!$company_email_details) {
            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
        } 

        $res = Send_Mail_dynamic($company_email_details,$data['vendor_details']->vendor_email_address,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Purchase Order Details');
        
        print_r($res); 
          
    }
      
    public function sendPdfMailToSelected(){
        $company_id = $this->session->userdata['company_id'];
        $group_id_array = $this->input->post('group_id_array');
        $message  = $this->input->post('message');        
        $data['msgtext'] =   $message[0];

         if (!empty($group_id_array)) {
      
            foreach ($group_id_array as $key => $value) {
                $in_ct = explode(':', $value);
                $purchase_order_id =  $in_ct[0];
                $purchase_order_number =  $in_ct[1];
                $where = array('purchase_order_id' =>$purchase_order_id);    
                $param = array('purchase_sent_status' =>1,'updated_at' => date("Y-m-d H:i:s"));   
                $this->PurchasesModel->updatePurchaseOrder($where,$param);

                // get first message    
                $purchase_order = $this->PurchasesModel->getOnePurchase(['purchase_order_tbl.purchase_order_id' => $purchase_order_id]);    
                print_r($purchase_order);
                
                $data['msgtext_one'] = $purchase_order->notes;
                $data['vendor_details'] = $this->VendorsModel->getOneVendor($purchase_order->vendor_id);
                $data['link'] =  base_url('welcome/pdfPurchaseOrder/').base64_encode($purchase_order_id);
                $data['link_acc'] =  base_url('welcome/PurchaseOrderAccept/').base64_encode($purchase_order_id);

                $where_company = array('company_id' =>$company_id);

                $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
                $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
         
                $body = $this->load->view('inventory/purchases/purchase_order_email',$data,true);

                $where_company['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
                
                if (!$company_email_details) {
                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                } 

                $res = Send_Mail_dynamic($company_email_details,$data['vendor_details']->vendor_email_address,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Purchase Order Details');

            } 
        }

        if (isset($res)) {
        echo 1; 
        } else {
        echo 0;
        }
    }

    public function pdfPurchaseOrder($purchase_order_id) {

        $where = array(
            "purchase_order_tbl.company_id" => $this->session->userdata['company_id'],
            'purchase_order_tbl.purchase_order_id' => $purchase_order_id 
        );    

        $data['purchase_order'] = $this->PurchasesModel->getOnePurchase($where);
        $data['purchase_order_invoices'] = $this->PurchasesModel->getPOInvoice(array("purchase_order_id" => $purchase_order_id ));

        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $data['settings'] =  $settings;

        $where_company = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $data['user_details'] =  $this->Administrator->getOneAdmin(array('user_id' =>$this->session->userdata['user_id']));

        $this->load->view('inventory/purchases/pdf_purchase_order', $data);

        $html = $this->output->get_output();
        
       //  // Load pdf library
         $this->load->library('pdf');

       //  // Load HTML content
         $this->dompdf->loadHtml($html);
        
       //  // (Optional) Setup the paper size and orientation
         $this->dompdf->setPaper('A4', 'portrate');
        
       //  // Render the HTML as PDF
        $this->dompdf->render();
        
       //  // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));
    }

    public function pdfPurchaseOrderReceiving($purchase_receiving_id) {

        $where = array(
            "purchase_receiving_tbl.company_id" => $this->session->userdata['company_id'],
            'purchase_receiving_tbl.purchase_receiving_id' =>$purchase_receiving_id 
        );    

        $data['purchase_receiving'] = $this->ReceivingsModel->getOneReceiving($where);

        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $data['settings'] =  $settings;

        $where_company = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $data['user_details'] =  $this->Administrator->getOneAdmin(array('user_id' =>$this->session->userdata['user_id']));

        $this->load->view('inventory/purchases/pdf_purchase_order_receiving', $data);

        $html = $this->output->get_output();
        
       //  // Load pdf library
         $this->load->library('pdf');

       //  // Load HTML content
         $this->dompdf->loadHtml($html);
        
       //  // (Optional) Setup the paper size and orientation
         $this->dompdf->setPaper('A4', 'portrate');
        
       //  // Render the HTML as PDF
        $this->dompdf->render();
        
       //  // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));
    }

    public function pdfPurchaseOrderReturn($return_id) {

        $where = array(
            "purchase_return_tbl.company_id" => $this->session->userdata['company_id'],
            'purchase_return_tbl.return_id' =>$return_id 
        );    

        $data['purchase_return'] = $this->ReturnsModel->getOneReturn($where);

        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $data['settings'] =  $settings;

        $where_company = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $data['user_details'] =  $this->Administrator->getOneAdmin(array('user_id' =>$this->session->userdata['user_id']));

        $this->load->view('inventory/purchases/pdf_purchase_order_return', $data);

        $html = $this->output->get_output();
        
       // Load pdf library
        $this->load->library('pdf');

       // Load HTML content
        $this->dompdf->loadHtml($html);
        
       // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');
        
       // Render the HTML as PDF
        $this->dompdf->render();
        
       // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));
    }

    public function printPurchaseOrder($purchase_order_id){
        $where_company = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where = array('user_id' =>$this->session->userdata['user_id']);
        $data['user_details'] =  $this->Administrator->getOneAdmin($where);

        $purchase_order_ids = explode(",", $purchase_order_id);
        foreach ($purchase_order_ids as $key => $value) {
            
             $where = array(
            "purchase_order_tbl.company_id" => $this->session->userdata['company_id'],
            'purchase_order_tbl.purchase_order_id' =>$value 
            );    
    
             $purchase_order_data = $this->PurchasesModel->getOnePurchase($where);
             $data['purchase_order_invoices'] = $this->PurchasesModel->getPOInvoice(array("purchase_order_id" => $value ));
    
            $data['purchase_order_details'][] = $purchase_order_data;

        }
       
      $this->load->view('inventory/purchases/multiple_pdf_purchase_order_print',$data);
    }

    public function printPurchaseOrderReceiving($purchase_receiving_id){
        $where_company = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where = array('user_id' =>$this->session->userdata['user_id']);
        $data['user_details'] =  $this->Administrator->getOneAdmin($where);

        $purchase_order_ids = explode(",", $purchase_receiving_id);
        foreach ($purchase_order_ids as $key => $value) {
            
             $where = array(
            "purchase_receiving_tbl.company_id" => $this->session->userdata['company_id'],
            'purchase_receiving_tbl.purchase_receiving_id' =>$value 
            );    
    
             $purchase_receiving_data = $this->ReceivingsModel->getOneReceiving($where);
    
            $data['purchase_receiving_details'][] = $purchase_receiving_data;

        }
        
      $this->load->view('inventory/purchases/multiple_pdf_purchase_order_receiving_print',$data);
    }

    public function printPurchaseOrderReturn($return_id){
        $where_company = array('company_id' =>$this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where = array('user_id' =>$this->session->userdata['user_id']);
        $data['user_details'] =  $this->Administrator->getOneAdmin($where);

        $return_ids = explode(",", $return_id);
       
        foreach ($return_ids as $key => $value) {
            
             $where = array(
            "purchase_return_tbl.company_id" => $this->session->userdata['company_id'],
            'purchase_return_tbl.return_id' =>$value 
            );    
    
             $purchase_return_data = $this->ReturnsModel->getOneReturn($where);
    
            $data['purchase_return_details'][] = $purchase_return_data;

        }

      $this->load->view('inventory/purchases/multiple_pdf_purchase_order_return_print',$data);
    }

    public function deleteMultiplePurchaseOrders(){
        $purchase_order_ids = $this->input->post('purchase_order_ids');
        
        if (!empty($purchase_order_ids)) {
            foreach ($purchase_order_ids as $key => $value) {
                $where = array('purchase_order_id'=>$value);
                $this->PurchasesModel->deletePurchaseOrder($where); 
                $this->ReceivingsModel->deletePurchaseReceivingOrder($where); 
                $this->ReturnsModel->deletePurchaseReturnOrder($where); 
            }
            echo 1; 
        } else {
            echo 0;
        }
    }

    public function updateEstimatedDeliveryDatePO() {

        $data =  $this->input->post();
              
        $where = array(
          'purchase_order_tbl.purchase_order_id' =>$data['purchase_order_id']
        );
      
        $param = array(
          'estimated_delivery_date' => $data['estimated_delivery_date'],
          'updated_at' => date("Y-m-d H:i:s")
        );
      
        $result = $this->PurchasesModel->updatePurchaseOrder($where, $param);

        if ($result) {
            echo "true";
        } else {
            echo "false";
        }
          
    }

    public function downloadPurchaseCSV($value=''){
   
        $where =  array('purchase_order_tbl.company_id' => $this->session->userdata['company_id']);        
        $data = $this->PurchasesModel->getAllPurchases($where);
       
        if($data){
  
          $delimiter = ",";
          $filename = "purchases_" . date('Y-m-d') . ".csv";
          
          //create a file pointer
          $f = fopen('php://memory', 'w');
          //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
          
          //set column headers
          $fields = array('Purchase Order #','Purchase Order Date','Sent Status','PO Status','Sent Date','Open Date','Paid Status','Estimate Delivery Date','Location',' Sub Location','Vendor','Item #','Total PO $');
         
          fputcsv($f, $fields, $delimiter);
          
          //output each row of the data, format line as csv and write to file pointer
       
          foreach ($data as $key => $value) {
  
             $lineData = array($value->purchase_order_number,$value->purchase_order_date,$value->purchase_sent_status, $value->purchase_order_status,$value->sent_date,$value->open_date, $value->purchase_paid_status, $value->estimated_delivery_date, $value->location_name, $value->sub_location_name,$value->vendor_name, $value->items, $value->grand_total);
  
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
            redirect("admin/customerList");
      }  
    }

    public function downloadPurchaseReceivingCSV($value=''){

        $where =  array('purchase_receiving_tbl.company_id' => $this->session->userdata['company_id']);        
        $data = $this->ReceivingsModel->getAllReceiving($where);
        // die(print_r($data));
        if($data){

            $delimiter = ",";
            $filename = "purchase_receiving_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('PO#','Vendor','Total Units','Total PO $ Amount');
            
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
        
            
            foreach ($data as $key => $value) {

                $lineData = array($value->purchase_order_number,$value->vendor_name,$value->total_units, $value->grand_total);

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
            redirect("admin/customerList");
        }  
    }

    public function downloadPurchaseReturnsCSV($value=''){

        $where =  array('purchase_return_tbl.company_id' => $this->session->userdata['company_id']);        
        $data = $this->ReturnsModel->getAllReturns($where);
        
        if($data){

            $delimiter = ",";
            $filename = "purchase_return_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('PO#','Purchase Order ID','Return Items','Created At','Updated At');
            
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
        
            
            foreach ($data as $key => $value) {

                $lineData = array($value->purchase_order_number,$value->purchase_order_id,$value->items, $value->created_at,$value->updated_at);

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
            redirect("admin/customerList");
        }  
    }

    public function addInvoice(){
        $data = $this->input->post();
        $company_id = $this->session->userdata['company_id'];
        $po_invoice = $this->PurchasesModel->getPOInvoice(array('po_invoice_tbl.purchase_order_id' => $data['purchase_order_id'], 'po_invoice_tbl.company_id' => $this->session->userdata['company_id']));

        $purchase_received = $this->ReceivingsModel->getOneReceiving(array('purchase_receiving_tbl.purchase_order_id' => $data['purchase_order_id']));

        $purchase_order = $this->PurchasesModel->getOnePurchase(array('purchase_order_tbl.purchase_order_id' => $data['purchase_order_id']));

        $inv_total = 0;
        foreach($po_invoice as $inv){
            $amt_total = $inv->invoice_total_amt;
            $inv_total += $amt_total;
        }

        $invoiced = array(
            'company_id' => $this->session->userdata['company_id'],
            'purchase_order_id' => $data['purchase_order_id'],
            'invoice_id' => $data['invoice_id'],
            'invoice_total_amt' => $data['invoice_total_amt'],
            'freight' => $data['freight'],
            'discount' => $data['discount'],
            'tax' => $data['tax'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata['id'],
        );
        
        $where = array(
            'purchase_order_tbl.purchase_order_id' =>$data['purchase_order_id']
          );

        if(($inv_total + $data['invoice_total_amt']) == $purchase_order->subtotal && $purchase_order->purchase_order_status == 3){

            $invoiced = array(
                'company_id' => $this->session->userdata['company_id'],
                'purchase_order_id' => $data['purchase_order_id'],
                'invoice_id' => $data['invoice_id'],
                'invoice_total_amt' => $data['invoice_total_amt'],
                'freight' => $data['freight'],
                'discount' => $data['discount'],
                'pay_by_date' => $data['pay_by_date'],
                'tax' => $data['tax'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata['id'],
            );
            
            $result = $this->PurchasesModel->insert_purchase_invoice($invoiced);

            $update = array(
                'purchase_paid_status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata['id'],
            );
            
            $upo = $this->PurchasesModel->updatePurchaseOrder($where, $update);

        } else if(($inv_total + $data['invoice_total_amt']) == $purchase_order->subtotal && $purchase_order->purchase_order_status != 3) {

            $invoiced = array(
                'company_id' => $this->session->userdata['company_id'],
                'purchase_order_id' => $data['purchase_order_id'],
                'invoice_id' => $data['invoice_id'],
                'invoice_total_amt' => $data['invoice_total_amt'],
                'freight' => $data['freight'],
                'discount' => $data['discount'],
                'pay_by_date' => $data['pay_by_date'],
                'tax' => $data['tax'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata['id'],
            );
            
            $result = $this->PurchasesModel->insert_purchase_invoice($invoiced);

            $update = array(
                'purchase_paid_status' => 3,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata['id'],
            );
            
            $upo = $this->PurchasesModel->updatePurchaseOrder($where, $update);

            $vendor_id = $this->VendorsModel->getOneVendor($purchase_order->vendor_id);
            $emaildata['vendorData'] = $this->VendorsModel->getOneVendor($purchase_order->vendor_id);

            $emaildata['purchase_data_details'] = $this->PurchasesModel->getOnePurchase(array( 'purchase_order_tbl.purchase_order_id' => $purchase_order->purchase_order_id, 'purchase_order_tbl.vendor_id' => $vendor_id->vendor_id ));

            $where = array('company_id' => $company_id);
            $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);
            
            #send email to company admin
            $emaildata['user_details'] = $this->CompanyModel->getOneAdminUser(array('company_id' => $company_id, 'role_id' => 1));
            
            $emaildata['company_email_details'] = $this->CompanyModel->getOneCompanyEmail($where);
            
            $where['is_smtp'] = 1;
            $company_email_details = $this->CompanyModel->getOneCompanyEmailArray($where);

            if (!$company_email_details) {
                $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
            }

            $emaildata['is_admin_email'] = 1;
            $emaildata['purchase_order_id'] = $purchase_order->purchase_order_id;
            $emaildata['location_name'] = $purchase_order->location_name;

            $adminBody = $this->load->view('inventory/purchases/purchase_order_unmatched_email', $emaildata, true);

            #admin email
            $res = Send_Mail_dynamic($company_email_details, $emaildata['user_details']->email, array("name" => $emaildata['company_details']->company_name, "email" => $emaildata['company_details']->company_email), $adminBody, 'Purchase Order Unmatched');
            
        } else {

            $invoiced = array(
                'company_id' => $this->session->userdata['company_id'],
                'purchase_order_id' => $data['purchase_order_id'],
                'invoice_id' => $data['invoice_id'],
                'invoice_total_amt' => $data['invoice_total_amt'],
                'freight' => $data['freight'],
                'discount' => $data['discount'],
                'pay_by_date' => $data['pay_by_date'],
                'tax' => $data['tax'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata['id'],
            );
            
            $result = $this->PurchasesModel->insert_purchase_invoice($invoiced);
        }
        
        if ($result) {
            echo "true";
        } else {
            echo "false";
        }
        
    }

    public function deletePOInvoice($purchase_order_inv){
        if (!empty($purchase_order_inv)) {
            
            $where = array('po_invoice_id'=>$purchase_order_inv);
            $this->PurchasesModel->deletePurchaseInvoice($where); 
            
            echo 1; 
        } else {
            echo 0;
        }
    }

    public function downloadPOReceiptCsv(){
        $data =  $this->input->post();
        $conditions = array();
        //set conditions for search
       
        $status = 3;

        if(!empty($status)){
            $conditions['search']['purchase_order_status'] = $status;
        }
      
        $data = $this->PurchasesModel->getAllPurchaseOrdersSearch($conditions);
        
        if($data){
  
            $delimiter = ",";
            $filename = "purchases_received_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('Purchase Order #','Purchase Order Date','Sent Status','PO Status','Sent Date','Open Date','Paid Status','Estimate Delivery Date','Location',' Sub Location','Vendor','Item #','Total PO $');
           
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
           
            foreach ($data as $key => $value) {
    
               $lineData = array($value->purchase_order_number,$value->purchase_order_date,$value->purchase_sent_status, $value->purchase_order_status,$value->sent_date,$value->open_date, $value->purchase_paid_status, $value->estimated_delivery_date, $value->location_name, $value->sub_location_name,$value->vendor_name, $value->items, $value->grand_total);
    
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
            redirect("inventory/Frontend/Purchases/");
        }   
    }

    ## Material Resource Planning Report
    public function MaterialResourcePlanningReport(){  
        //get the posts data
        $company_id = $this->session->userdata['company_id'];

        $where = array('company_id' => $company_id);
        $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));
       
        $grass_type = '';
        $job_ids = [];
        foreach($joblist as $job){
            $job = $job->job_id;
            array_push($job_ids, $job);
        }
       
        #### Code from Ajax function below
        if($job_ids){
           
            $allProductIDs = []; 
            $allProductNames = []; 
            $allProductInfo = []; 
            foreach($job_ids as $jd){
                $product_arr = $this->JobModel->getUnassignJobsWhere($jd, $grass_type);
                
                if(!empty($product_arr)){
                    array_push($allProductInfo,$product_arr);
                }
            }
            
            if(!empty($allProductInfo)){
                $info_arr = array();
                $sqft_arr = array();
                $estimate_product_arr = array();
                $unit_arr = array();
                $qty_arr = array();
                foreach($allProductInfo as $info){
                    if(!empty($info)){
                        foreach($info as $inf){
                            if(isset($inf->product_id)){
                                $prod = $inf->product_id;
                                if(!isset($info_arr[$prod])){
                                    $info_arr[$prod] = 0;
                                }
                                $info_arr[$prod]++; 
                            }
                            if(isset($inf->yard_square_feet)){
                                
                                    if(!isset($sqft_arr[$prod])){
                                        $sqft_arr[$prod] = $inf->yard_square_feet;
                                        $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    } else {
                                        $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                        $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    }
                            }
                                
                            if(!isset($unit_arr[$prod])){
                                $unit_arr[$prod] = $inf->application_unit;
                            }
                            if(!isset($qty_arr[$prod])){
                                $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                            } else {
                                $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                            }

                            if(!in_array($inf->product_id, $allProductIDs)){
                                array_push($allProductIDs, $inf->product_id);
                                array_push($allProductNames, $inf->product_name);
                            }
                        }

                            
                    }
                }      
            }
            
            $data = [];
            $product_objs = [];
            if(!empty($allProductIDs)){
                foreach($allProductIDs as $pid){
                    $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                    $outstanding_ct = $info_arr[$pid];
                    $outstanding_sqft = $sqft_arr[$pid];
                    $unit_type = $this->RP->getItemUnitType($pid); 
                    $unit_amount = $this->RP->getItemUnitAmount($pid);
                    $amount_onhand = ($qty_arr[$pid] * $unit_amount);
                    $item_id = $this->RP->getItemIdByProductId($pid);
                    $items_ordered = $this->RP->getReceivingQtyByItemId($item_id, $company_id) * $unit_amount;
                    $overage = 0;
                    $item_con_info = $this->RP->getUnitConversionInfoByItemId($item_id);
                    $prod_con_info = $this->RP->getUnitConversionInfoByProductId($pid);
                    if($unit_type == ''){
                        $unit_type = $prod_con_info->application_unit;
                    }

                    
                    $converted_needed = unitConversion($estimate_product_arr[$pid], $unit_type, $prod_con_info->application_unit, $prod_con_info->product_type);
                    $ordered = (float)$items_ordered;
                    $overage_before_conversion =  ((float)$amount_onhand + (float)$ordered);
                    $total_minus_needed = $overage_before_conversion - $converted_needed;
                    $product_obj = new stdClass();
                    $product_obj->product_name = $product_name;
                    $product_obj->outstanding_ct = $outstanding_ct;
                    $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                    $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
                    $product_obj->onhand = $amount_onhand . ' ' . explode('s', $unit_type)[0] . '(s)';
                    $product_obj->ordered = $ordered . ' ' . explode('s', $unit_type)[0] . '(s)';
                    $product_obj->overage = $unit_type != '' ? number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_type)[0] . '(s)' : number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';

                    array_push($product_objs, $product_obj);
                }
            }
            $data['product_objs'] = $product_objs;
           
        }
        #### End Code from Ajax function below
       
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['joblist'] = $joblist;
        $page["active_sidebar"] = "materialResourcePlanningReportINV";
        $page["page_name"] = 'Material Resource Planning Report';
        $page["page_content"] = $this->load->view("inventory/report/view_material_resource_planning_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);

    }

    ## ajax data for Material Resource Planning Report
    function ajaxMaterialResourcePlanningData(){
       
        $company_id = $this->session->userdata['company_id'];
        //set conditions for search
        $job_list = $this->input->post('job_list');
        $grass_type = $this->input->post('grass_type');
       
        if(isset($job_list) && !empty($job_list) &&  explode(',', $job_list)[0] != 'null'){
            $job_arr = explode(',', $job_list);
       
            $joblist = $this->ProgramModel->getJobListWhereIn('job_id', $job_arr);
            
            if($joblist){
            
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($joblist as $jd){
                    $product_arr = $this->JobModel->getUnassignJobsWhere($jd->job_id, $grass_type);
                   
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if($grass_type != ''){
                                    
                                    if(isset($inf->front_yard_grass) || isset($inf->back_yard_grass)){
                                        if(isset($inf->front_yard_grass)){
                                            
                                            if($inf->front_yard_grass == $grass_type){
                                                if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->front_yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                                } else {
                                                $sqft_arr[$prod] += (int)$inf->front_yard_square_feet;
                                                $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                                }
                                            }
                                        }
        
                                        if(isset($inf->back_yard_grass)){
                                            
                                            if($inf->back_yard_grass == $grass_type){
                                                if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->back_yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                                } else {
                                                $sqft_arr[$prod] += (int)$inf->back_yard_square_feet;
                                                $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                                }
                                            }
                                        }
    
                                    }
                                    else if(isset($inf->total_yard_grass)){
                                        
                                                                 
                                        if($inf->total_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                            } else {
                                                $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                                $estimate_product_arr[$prod] += (float)$this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                            }
                                        }
                                    }
                                }
                                else{
                                    if(isset($inf->yard_square_feet)){
                                
                                        if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        } else {
                                            $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        }
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }      
                }
                
                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];
                        $unit_type = $this->RP->getItemUnitType($pid); 
                        $unit_amount = $this->RP->getItemUnitAmount($pid);
                        $amount_onhand = ($qty_arr[$pid] * $unit_amount);
                        $item_id = $this->RP->getItemIdByProductId($pid);
                        $items_ordered = $this->RP->getReceivingQtyByItemId($item_id, $company_id) * $unit_amount;
                        $overage = 0;
                        $item_con_info = $this->RP->getUnitConversionInfoByItemId($item_id);
                        $prod_con_info = $this->RP->getUnitConversionInfoByProductId($pid);
                        if($unit_type == ''){
                            $unit_type = $prod_con_info->application_unit;
                        }
    
                        
                        $converted_needed = unitConversion($estimate_product_arr[$pid], $unit_type, $prod_con_info->application_unit, $prod_con_info->product_type);
                        $ordered = (float)$items_ordered;
                        $overage_before_conversion =  ((float)$amount_onhand + (float)$ordered);
                        $total_minus_needed = $overage_before_conversion - $converted_needed;
                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
                        $product_obj->onhand = $amount_onhand . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->ordered = $ordered . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->overage = $unit_type != '' ? number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_type)[0] . '(s)' : number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            }
            
            $body =  $this->load->view('inventory/report/ajax_material_resource_planning_report', $data, false);

            echo $body;
        } else if($grass_type != '') {
            $where = array('company_id' => $company_id);
            $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));
            
            $job_ids = [];
            foreach($joblist as $job){
                $job = $job->job_id;
                array_push($job_ids, $job);
            }

            if($job_ids){
           
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($job_ids as $jd){
                    $product_arr = $this->JobModel->getUnassignJobsWhere($jd, $grass_type);
                    // die(print_r($this->db->last_query()));
                    // die(print_r($product_arr));
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
                // die(print_r($this->db->last_query()));
                // die(print_r($allProductInfo));
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if(isset($inf->front_yard_grass) || isset($inf->back_yard_grass)){
                                    if(isset($inf->front_yard_grass)){
                                        if($inf->front_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->front_yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                            } else {
                                            $sqft_arr[$prod] += (int)$inf->front_yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                            }
                                        }
                                    }
    
                                    if(isset($inf->back_yard_grass)){
                                        if($inf->back_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->back_yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                            } else {
                                            $sqft_arr[$prod] += (int)$inf->back_yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                            }
                                        }
                                    }

                                }
                                else if(isset($inf->total_yard_grass)){
                                    
                                                             
                                    if($inf->total_yard_grass == $grass_type){
                                        if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        } else {
                                            $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        }
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }     
                }
                // die(print_r($allProductInfo));
                
                $data = [];
                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];
                        $unit_type = $this->RP->getItemUnitType($pid); 
                        $unit_amount = $this->RP->getItemUnitAmount($pid);
                        $amount_onhand = ($qty_arr[$pid] * $unit_amount);
                        $item_id = $this->RP->getItemIdByProductId($pid);
                        $items_ordered = $this->RP->getReceivingQtyByItemId($item_id, $company_id) * $unit_amount;
                        $overage = 0;
                        $item_con_info = $this->RP->getUnitConversionInfoByItemId($item_id);
                        $prod_con_info = $this->RP->getUnitConversionInfoByProductId($pid);
                        if($unit_type == ''){
                            $unit_type = $prod_con_info->application_unit;
                        }
    
                        
                        $converted_needed = unitConversion($estimate_product_arr[$pid], $unit_type, $prod_con_info->application_unit, $prod_con_info->product_type);
                        $ordered = (float)$items_ordered;
                        $overage_before_conversion =  ((float)$amount_onhand + (float)$ordered);
                        $total_minus_needed = $overage_before_conversion - $converted_needed;
                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
                        $product_obj->onhand = $amount_onhand . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->ordered = $ordered . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->overage = $unit_type != '' ? number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_type)[0] . '(s)' : number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            }

            $where_arr = array('company_id' =>$this->session->userdata['company_id']);
            $data['joblist'] = $joblist;
            $body =  $this->load->view('inventory/report/ajax_material_resource_planning_report', $data, false);

            echo $body;

        } else {
            //get the posts data
        
            $where = array('company_id' => $company_id);
            $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));
            // die(print_r($data['joblist']));
            $grass_type = '';
            $job_ids = [];
            foreach($joblist as $job){
                $job = $job->job_id;
                array_push($job_ids, $job);
            }
            // die(print_r($job_ids));
            #### Code from Ajax function below
            if($job_ids){
            
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($job_ids as $jd){
                    $product_arr = $this->JobModel->getUnassignJobsWhere($jd, $grass_type);
                    // die(print_r($this->db->last_query()));
                    // die(print_r($product_arr));
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
                // die(print_r($this->db->last_query()));
                // die(print_r($allProductInfo));
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if(isset($inf->yard_square_feet)){
                                    
                                                             
                                    if(!isset($sqft_arr[$prod])){
                                        $sqft_arr[$prod] = $inf->yard_square_feet;
                                        $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    } else {
                                        $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                        $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }      
                }
                // die(print_r($allProductInfo));
                
                $data = [];
                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];
                        $unit_type = $this->RP->getItemUnitType($pid); 
                        $unit_amount = $this->RP->getItemUnitAmount($pid);
                        $amount_onhand = ($qty_arr[$pid] * $unit_amount);
                        $item_id = $this->RP->getItemIdByProductId($pid);
                        $items_ordered = $this->RP->getReceivingQtyByItemId($item_id, $company_id) * $unit_amount;
                        $overage = 0;
                        $item_con_info = $this->RP->getUnitConversionInfoByItemId($item_id);
                        $prod_con_info = $this->RP->getUnitConversionInfoByProductId($pid);
                        if($unit_type == ''){
                            $unit_type = $prod_con_info->application_unit;
                        }
    
                        
                        $converted_needed = unitConversion($estimate_product_arr[$pid], $unit_type, $prod_con_info->application_unit, $prod_con_info->product_type);
                        $ordered = (float)$items_ordered;
                        $overage_before_conversion =  ((float)$amount_onhand + (float)$ordered);
                        $total_minus_needed = $overage_before_conversion - $converted_needed;
                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
                        $product_obj->onhand = $amount_onhand . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->ordered = $ordered . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->overage = $unit_type != '' ? number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_type)[0] . '(s)' : number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            
            }
            // die(print_r($data['product_objs']));
            #### End Code from Ajax function below
       
        $where_arr = array('company_id' =>$this->session->userdata['company_id']);
        $data['joblist'] = $joblist;
        $body =  $this->load->view('inventory/report/ajax_material_resource_planning_report', $data, false);

        echo $body;
        }

    }

    public function calculateProductNeeded($per, $amount, $sqft){
        $calculation = 0;
        $footage = 0;
        if($per == '1 Acre'){
            $footage = (int)$sqft/43560;
        } else {
            $footage = (int)$sqft/1000;
        }

        $calculation = $amount * $footage;
         
        return number_format((float)$calculation, 2, '.', '');
    }

    public function downloadMaterialResourceCsv(){
        $data = $this->input->post();
       
        if(isset($data['material_job_tmp']) &&!empty($data['material_job_tmp'])){

            $job_arr = $data['material_job_tmp'];
        } 
       
        $grass_type = $data['grass_type'];
        $company_id = $this->session->userdata['company_id'];
       
        if(isset($job_arr) && !empty($job_arr)){
            $joblist = $this->ProgramModel->getJobListWhereIn('job_id', $job_arr);
           
            if($joblist){
            
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($joblist as $jd){
                    $product_arr = $this->JobModel->getUnassignJobsWhere($jd->job_id, $grass_type);
                   
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if($grass_type != ''){
                                    
                                    if(isset($inf->front_yard_grass) || isset($inf->back_yard_grass)){
                                        if(isset($inf->front_yard_grass)){
                                            print('Isset Front');
                                            if($inf->front_yard_grass == $grass_type){
                                                if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->front_yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                                } else {
                                                $sqft_arr[$prod] += (int)$inf->front_yard_square_feet;
                                                $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                                }
                                            }
                                        }
        
                                        if(isset($inf->back_yard_grass)){
                                            print('Isset Back');
                                            if($inf->back_yard_grass == $grass_type){
                                                if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->back_yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                                } else {
                                                $sqft_arr[$prod] += (int)$inf->back_yard_square_feet;
                                                $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                                }
                                            }
                                        }
    
                                    }
                                    else if(isset($inf->total_yard_grass)){
                                        
                                                                 
                                        if($inf->total_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                                $sqft_arr[$prod] = $inf->yard_square_feet;
                                                $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                            } else {
                                                $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                                $estimate_product_arr[$prod] += (float)$this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                            }
                                        }
                                    }
                                } else{
                                    if(isset($inf->yard_square_feet)){
                                
                                        if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        } else {
                                            $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        }
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }     
                }

                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];
                        $unit_type = $this->RP->getItemUnitType($pid); 
                        $unit_amount = $this->RP->getItemUnitAmount($pid);
                        $amount_onhand = ($qty_arr[$pid] * $unit_amount);
                        $item_id = $this->RP->getItemIdByProductId($pid);
                        $items_ordered = $this->RP->getReceivingQtyByItemId($item_id, $company_id) * $unit_amount;
                        $overage = 0;
                        $item_con_info = $this->RP->getUnitConversionInfoByItemId($item_id);
                        $prod_con_info = $this->RP->getUnitConversionInfoByProductId($pid);
                        if($unit_type == ''){
                            $unit_type = $prod_con_info->application_unit;
                        }
    
                        
                        $converted_needed = unitConversion($estimate_product_arr[$pid], $unit_type, $prod_con_info->application_unit, $prod_con_info->product_type);
                        $ordered = (float)$items_ordered;
                        $overage_before_conversion =  ((float)$amount_onhand + (float)$ordered);
                        $total_minus_needed = $overage_before_conversion - $converted_needed;
                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
                        $product_obj->onhand = $amount_onhand . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->ordered = $ordered . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->overage = $unit_type != '' ? number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_type)[0] . '(s)' : number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            }
            
        } else if($grass_type != '') {

           $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));

            $job_ids = [];
            foreach($joblist as $job){
                $job = $job->job_id;
                array_push($job_ids, $job);
            }
            
            $job_ids = [];
            foreach($joblist as $job){
                $job = $job->job_id;
                array_push($job_ids, $job);
            }

            if($job_ids){
           
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($job_ids as $jd){
                    $product_arr = $this->JobModel->getUnassignJobsWhere($jd, $grass_type);
                    
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
               
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if(isset($inf->front_yard_grass) || isset($inf->back_yard_grass)){
                                    if(isset($inf->front_yard_grass)){
                                        print('Isset Front');
                                        if($inf->front_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->front_yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                            } else {
                                            $sqft_arr[$prod] += (int)$inf->front_yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, (int)$inf->front_yard_square_feet);
                                            }
                                        }
                                    }
    
                                    if(isset($inf->back_yard_grass)){
                                        print('Isset Back');
                                        if($inf->back_yard_grass == $grass_type){
                                            if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->back_yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                            } else {
                                            $sqft_arr[$prod] += (int)$inf->back_yard_square_feet;
                                            $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->back_yard_square_feet);
                                            }
                                        }
                                    }

                                }
                                else if(isset($inf->total_yard_grass)){
                                    
                                                             
                                    if($inf->total_yard_grass == $grass_type){
                                        if(!isset($sqft_arr[$prod])){
                                            $sqft_arr[$prod] = $inf->yard_square_feet;
                                            $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        } else {
                                            $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                            $estimate_product_arr[$prod] += (float)$this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                        }
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }     
                }
                
                $data = [];
                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];
                        $unit_type = $this->RP->getItemUnitType($pid); 
                        $unit_amount = $this->RP->getItemUnitAmount($pid);
                        $amount_onhand = ($qty_arr[$pid] * $unit_amount);
                        $item_id = $this->RP->getItemIdByProductId($pid);
                        $items_ordered = $this->RP->getReceivingQtyByItemId($item_id, $company_id) * $unit_amount;
                        $overage = 0;
                        $item_con_info = $this->RP->getUnitConversionInfoByItemId($item_id);
                        $prod_con_info = $this->RP->getUnitConversionInfoByProductId($pid);
                        if($unit_type == ''){
                            $unit_type = $prod_con_info->application_unit;
                        }
    
                        
                        $converted_needed = unitConversion($estimate_product_arr[$pid], $unit_type, $prod_con_info->application_unit, $prod_con_info->product_type);
                        $ordered = (float)$items_ordered;
                        $overage_before_conversion =  ((float)$amount_onhand + (float)$ordered);
                        $total_minus_needed = $overage_before_conversion - $converted_needed;
                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
                        $product_obj->onhand = $amount_onhand . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->ordered = $ordered . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->overage = $unit_type != '' ? number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_type)[0] . '(s)' : number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            }

        } else {
          
            $joblist = $this->ProgramModel->getJobList(array('company_id' => $company_id));

            $grass_type = '';
            $job_ids = [];
            foreach($joblist as $job){
                $job = $job->job_id;
                array_push($job_ids, $job);
            }
            
            #### Code from Ajax function below
            if($job_ids){
            
                $allProductIDs = []; 
                $allProductNames = []; 
                $allProductInfo = []; 
                foreach($job_ids as $jd){
                    $product_arr = $this->JobModel->getUnassignJobsWhere($jd, $grass_type);
                    
                    if(!empty($product_arr)){
                        array_push($allProductInfo,$product_arr);
                    }
                }
                
                if(!empty($allProductInfo)){
                    $info_arr = array();
                    $sqft_arr = array();
                    $estimate_product_arr = array();
                    $unit_arr = array();
                    $qty_arr = array();
                    foreach($allProductInfo as $info){
                        if(!empty($info)){
                            foreach($info as $inf){
                                if(isset($inf->product_id)){
                                    $prod = $inf->product_id;
                                    if(!isset($info_arr[$prod])){
                                        $info_arr[$prod] = 0;
                                    }
                                    $info_arr[$prod]++; 
                                }
                                if(isset($inf->yard_square_feet)){
                                    
                                                             
                                    if(!isset($sqft_arr[$prod])){
                                        $sqft_arr[$prod] = $inf->yard_square_feet;
                                        $estimate_product_arr[$prod] = $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    } else {
                                        $sqft_arr[$prod] += (int)$inf->yard_square_feet;
                                        $estimate_product_arr[$prod] += $this->calculateProductNeeded($inf->application_per, $inf->application_rate, $inf->yard_square_feet);
                                    }
                                }
                                    
                                if(!isset($unit_arr[$prod])){
                                    $unit_arr[$prod] = $inf->application_unit;
                                }
                                if(!isset($qty_arr[$prod])){
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                } else {
                                    $qty_arr[$prod] = $this->RP->getAmountOfProductQty($inf->product_id);
                                }

                                if(!in_array($inf->product_id, $allProductIDs)){
                                    array_push($allProductIDs, $inf->product_id);
                                    array_push($allProductNames, $inf->product_name);
                                }
                            }
    
                                
                        }
                    }      
                }
                
                $data = [];
                $product_objs = [];
                if(!empty($allProductIDs)){
                    foreach($allProductIDs as $pid){
                        $product_name = $this->JobAssignProduct->getSingleProductName($pid);
                        $outstanding_ct = $info_arr[$pid];
                        $outstanding_sqft = $sqft_arr[$pid];
                        $unit_type = $this->RP->getItemUnitType($pid); 
                        $unit_amount = $this->RP->getItemUnitAmount($pid);
                        $amount_onhand = ($qty_arr[$pid] * $unit_amount);
                        $item_id = $this->RP->getItemIdByProductId($pid);
                        $items_ordered = $this->RP->getReceivingQtyByItemId($item_id, $company_id) * $unit_amount;
                        $overage = 0;
                        $item_con_info = $this->RP->getUnitConversionInfoByItemId($item_id);
                        $prod_con_info = $this->RP->getUnitConversionInfoByProductId($pid);
                        if($unit_type == ''){
                            $unit_type = $prod_con_info->application_unit;
                        }
    
                        
                        $converted_needed = unitConversion($estimate_product_arr[$pid], $unit_type, $prod_con_info->application_unit, $prod_con_info->product_type);
                        $ordered = (float)$items_ordered;
                        $overage_before_conversion =  ((float)$amount_onhand + (float)$ordered);
                        $total_minus_needed = $overage_before_conversion - $converted_needed;
                        $product_obj = new stdClass();
                        $product_obj->product_name = $product_name;
                        $product_obj->outstanding_ct = $outstanding_ct;
                        $product_obj->outstanding_sqft = number_format($outstanding_sqft);
                        $product_obj->product_needed = number_format($estimate_product_arr[$pid], 2, '.', ',') . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
                        $product_obj->onhand = $amount_onhand . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->ordered = $ordered . ' ' . explode('s', $unit_type)[0] . '(s)';
                        $product_obj->overage = $unit_type != '' ? number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_type)[0] . '(s)' : number_format($total_minus_needed, 2) . ' ' . explode('s', $unit_arr[$pid])[0] . '(s)';
    
                        array_push($product_objs, $product_obj);
                    }
                }
                $data['product_objs'] = $product_objs;
            
            }
        }
        
        if($product_objs){
  
            $delimiter = ",";
            $filename = "material_resource_" . date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');
            
            //set column headers
            $fields = array('Product Names','Outstanding Services','Outstanding Square Feet','Estimate Amount of Product Needed','Amount of Product on Hand','Amount of Product Ordered','Overage/Shortfall');
           
            fputcsv($f, $fields, $delimiter);
            
            //output each row of the data, format line as csv and write to file pointer
           
            foreach ($product_objs as $key => $value) {
               $lineData = array($value->product_name,$value->outstanding_ct,$value->outstanding_sqft, $value->product_needed,$value->onhand,$value->ordered,$value->overage);
    
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
            redirect("inventory/Frontend/Purchases/MaterialResourcePlanningReport");
        }   
    }

    public function save_paid_po(){
        $data = $this->input->post();
        $file_name = "";

        if (!empty($_FILES['paid_attachment']['name'])) {
            $file_name_array  = explode(".", $_FILES['paid_attachment']['name']);
            $fileext =  end($file_name_array);
            $tmp_name   = $_FILES['paid_attachment']['tmp_name'];
            $file_name  = $data['po_id'].'_'.date("ymdhis").'.'.$fileext ;
            $key = '/uploads/po_attachments/'.$file_name;
            $Retulst = $this->aws_sdk->saveFile($key, $tmp_name);
            $file_name = $Retulst->get("ObjectURL");
        }

        $where = array(
          'purchase_order_tbl.purchase_order_id' => $data['po_id']
        );
      
        $param = array(
          'purchase_paid_status' => 2,
          'paid_payment_method' => $data['paid_payment_method'],
          'paid_notes' => $data['paid_notes'],
          'paid_attachment' => $file_name,
          'updated_at' => date("Y-m-d H:i:s")
        );
      
        $result = $this->PurchasesModel->updatePurchaseOrder($where, $param);
        redirect("inventory/Frontend/Dashboard");
    }

}