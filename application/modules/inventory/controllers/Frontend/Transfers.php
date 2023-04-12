<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Transfers extends MY_Controller
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
        $this->load->model('TransfersModel', 'TransferModel');
         $this->load->model('LocationsModel', 'LocationsModel');
    }
	
	public function index($transferId = false) {
		
        $where_arr = array(
            'transfer_id !=' => 0
        );
        $data['all_tranfers'] = $this->TransferModel->getAllTransfers($where_arr);
        // die(print_r($data['all_tranfers']));

        $company_id = $this->session->userdata['company_id'];

        $data['all_sublocations'] = $this->TransferModel->getAllCompanySubLocations($company_id);

		// return view('transfers/transfers', $this->data);
		$page["active_sidebar"] = "tranfers";
        $page["page_name"] = 'Tranfers';
        $page["page_content"] = $this->load->view("inventory/transfers/view_transfers", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
	}

	public function new() {
		// $this->data['route'] = 'transfers';
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $settings->references_purchase_return_prepend = '';
        $settings->references_purchase_return_append = '';
        $data['settings'] =  $settings;
        $data['purchaseId'] = 1;
        // die(print_r($data['brands']));
         $data['list_sub_locations'] = $this->LocationsModel->getSubLocationsList();

        $page["active_sidebar"] = "tranfers";
        $page["page_name"] = 'Tranfers';
        $page["page_content"] = $this->load->view("inventory/transfers/new_transfer", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);

		// return view('transfers/new_transfer', $this->data);
	}

    public function ajaxGetTransfers()
    {
        $tblColumns = array(
            0 => 'transfer_id',
            1 => 'from_sub_location_id',
            2 => 'to_sub_location_id',
            3 => 'items',
            4 => 'notes',
            3 => 'created_by',
            4 => 'created_at',
            5 => 'action'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'transfers_tbl.company_id' => $company_id,
            'transfers_tbl.is_archived' => 0
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
            $tempdata  = $this->TransferModel->getTransferDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->TransferModel->getTransferDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->TransferModel->getTransferAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->TransferModel->getTransferAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        //  $var_last_query = $this->db->last_query ();

        //  die(print_r($var_last_query));
        // die(print_r($tempdata));

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {

                $from_name = $this->TransferModel->getSubLocationName($value->from_sub_location_id);
                $to_name = $this->TransferModel->getSubLocationName($value->to_sub_location_id);

                // die(print_r($value));

                $created_by_name = $this->TransferModel->getCreatedByName($value->created_by);

                $subs = $this->TransferModel->getAllCompanySubLocations($company_id);

                $subs_arr = array();

                foreach($subs as $sub){
                    $sub_str = $sub->sub_location_id . '::' . $sub->location_name . '::' . $sub->sub_location_name;
                    if(!in_array($sub_str, $subs_arr)){
                        array_push($subs_arr, $sub_str);
                    }
                }

                

                $subs_str = implode('<::>', $subs_arr);

                

                // set row data
                $data[$i]['transfer_id'] = $value->transfer_id;
                $data[$i]['from_sub_location_id'] = $from_name;
                $data[$i]['to_sub_location_id'] = $to_name;
                $data[$i]['created_by'] = $created_by_name;
                $data[$i]['created_at'] = $value->created_at;
                $data[$i]['actions'] = '<span class="pr-5"><a href="#" data-toggle="modal" data-target="#modal_edit_transfer" data-subs="'. $subs_str .'" data-id="' . $value->transfer_id . '" data-from="' . $value->from_sub_location_id . '" data-to="' . $value->to_sub_location_id . '" data-item="'. $value->items .'" data-amount="' . $value->amount_transferred . '" data-note="'. $value->notes .'" class="button-next modal_trigger"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a href="' .  base_url('inventory/Frontend/Transfers/transferDelete/') . $value->transfer_id . '" data-url="' . $value->transfer_id . '"class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></span>';
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

    public function transferDelete($transfer_id)
    {
        $param = array('is_archived' => 1);

        
            $result = $this->TransferModel->updateTransfersTbl($transfer_id, $param);

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("inventory/Frontend/Transfers/");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Transfer </strong>deleted successfully</div>');
                redirect("inventory/Frontend/Transfers/");
            }          
    }



    public function editTransfer()
    {
        // print($item_type_id);

        $data = $this->input->post();

        // die(print_r($data));

        $transfer_id = $data['transfer_id'];

        $item_ids = explode('::', $data['edit_item_ids']);

        // die(print_r($item_ids));

        $new_item_ids = explode('::', $data['new_edit_item_ids']);

        // die(print_r($new_item_ids));

        $company_id = $this->session->userdata['company_id'];
        $user_id = $this->session->userdata['id'];

        $items = array();

        foreach($item_ids as $it_id){
            $adjust = explode(':', $it_id)[2];
            $id_str = explode(':', $it_id)[0];
            if(!in_array($it_id, $items)){
                array_push($items, $it_id);
            }            

            $source_where_arr = array(
                'company_id' => $company_id,
                'quantity_item_id' => $id_str,
                'quantity_sublocation_id' => $data['edit_source']
            );

            $this->TransferModel->updateSourceQuantitiesTbl($source_where_arr, number_format(explode(' - ', $adjust)[1]));

            $target_where_arr = array(
                'company_id' => $company_id,
                'quantity_item_id' => $id_str,
                'quantity_sublocation_id' => $data['edit_target']
            );

            $this->TransferModel->updateTargetQuantitiesTbl($target_where_arr, number_format(explode(' - ', $adjust)[1]));
        }

        if(count($new_item_ids) > 0 && $new_item_ids[0] != ''){
            foreach($new_item_ids as $it_id){
                $adjust = explode(':', $it_id)[2];
                $id_str = explode(':', $it_id)[0];
                if(!in_array($it_id, $items)){
                    array_push($items, $it_id);
                }            
    
                $source_where_arr = array(
                    'company_id' => $company_id,
                    'quantity_item_id' => $id_str,
                    'quantity_sublocation_id' => $data['edit_source']
                );
    
                $this->TransferModel->updateSourceQuantitiesTbl($source_where_arr, number_format(explode(' - ', $adjust)[0]));
    
                $target_where_arr = array(
                    'company_id' => $company_id,
                    'quantity_item_id' => $id_str,
                    'quantity_sublocation_id' => $data['edit_target']
                );
    
                $this->TransferModel->updateTargetQuantitiesTbl($target_where_arr, number_format(explode(' - ', $adjust)[0]));
            }
        }

        $item_str = '';

        if(!empty($items)){
            if(count($items) > 1){
               $item_str = implode(', ', $items);
            } else {
                $item_str = implode('', $items);
            }
        }

        $data_arr = array(
            'from_sub_location_id'=> $data['edit_source'], 
            'to_sub_location_id' => $data['edit_target'],
            'items' => $item_str,
            'notes' => $data['edit_notes']
        );

            $result = $this->TransferModel->updateTransfersTbl($transfer_id, $data_arr);

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("inventory/Frontend/Transfers/");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Transfer </strong>updated successfully.</div>');
                redirect("inventory/Frontend/Transfers/");
            }                
    }

    public function newTransfer()
    {
        // print($item_type_id);

        $data = $this->input->post();

        $item_ids = explode('::', $data['item_ids']);

        // die(print_r($item_ids));

        $company_id = $this->session->userdata['company_id'];
        $user_id = $this->session->userdata['id'];

        $items = array();

        foreach($item_ids as $it_id){
            $adjust = explode(':', $it_id)[2];
            $id_str = explode(':', $it_id)[0];
            if(!in_array($it_id, $items)){
                array_push($items, $it_id);
            }            

            $source_where_arr = array(
                'company_id' => $company_id,
                'quantity_item_id' => $id_str,
                'quantity_sublocation_id' => $data['from_sub_location_id'],
            );

            $this->TransferModel->updateSourceQuantitiesTbl($source_where_arr, number_format(explode(' - ', $adjust)[0]));

            $target_where_arr = array(
                'company_id' => $company_id,
                'quantity_item_id' => $id_str,
                'quantity_sublocation_id' => $data['to_sub_location_id'],
            );

            $this->TransferModel->updateTargetQuantitiesTbl($target_where_arr, number_format(explode(' - ', $adjust)[0]));
        }

        $item_str = '';

        if(!empty($items)){
            if(count($items) > 1){
               $item_str = implode(', ', $items);
            } else {
                $item_str = implode('', $items);
            }
        }

        $data_arr = array(
            'from_sub_location_id'=> $data['from_sub_location_id'], 
            'to_sub_location_id' => $data['to_sub_location_id'],
            'items' => $item_str,
            'notes' => $data['notes'],
            'company_id' => $company_id,
            'created_by' => $user_id,
        );


            $result = $this->TransferModel->createNewTransfer($data_arr);

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("inventory/Frontend/Transfers/");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Transfer </strong>created successfully.</div>');
                redirect("inventory/Frontend/Transfers/");
            }     

        
    }

    public function exportTransfersCSV($value=''){

        $company_id = $this->session->userdata['company_id'];

        $where = array(
            'transfers_tbl.company_id' => $company_id,
            'transfers_tbl.is_archived' => 0
        );

        $data = $this->TransferModel->getAllTransfers($where);
   
        if($data){
  
            $delimiter = ",";
            $filename = "transfers_" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Transfer ID','Source Sub-Location','Target Sub-Location','Items','Notes', 'Created By', 'Created At');
  
            fputcsv($f, $fields, $delimiter);
  
          foreach ($data as $key => $value) {

            $items = explode(', ', $value->items);

            $items_arr = array();

            foreach($items as $ite){
                if(!in_array(explode(':', $ite)[1], $items_arr)){
                    array_push($items_arr, explode(':', $ite)[1]);
                }
            }

            $items_str = implode(", ", $items_arr);

            $from_name = $this->TransferModel->getSubLocationName($value->from_sub_location_id);
            $to_name = $this->TransferModel->getSubLocationName($value->to_sub_location_id);

            $created_by_name = $this->TransferModel->getCreatedByName($value->created_by);
  
            $lineData = array($value->transfer_id, $from_name, $to_name, $items_str, $value->notes, $created_by_name, $value->created_at);
           
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
         redirect("inventory/Frontend/Transfers/");
      }
  
  
    }

    public function getItemInput(){
        $data = $this->input->post();


        $company_id = $this->session->userdata['company_id'];
        // die(print_r($data));

        $items = $this->TransferModel->getItemListInput($data['item_input'], $company_id);

        // die(print_r($items));

        if($data['source'] != $data['target']){
          
            if(!empty($items) && $items[0] != ''){
                foreach($items as $key => $val){
                    $source = $this->TransferModel->getSubLocationItemQuantity($val->item_id, $data['source']);
                    $target = $this->TransferModel->getSubLocationItemQuantity($val->item_id, $data['target']);

                    // die(print_r($source[0]));

                    if(count($source) > 0){
                        $source_quant = (int)$source[0]->quantity;
                        $source_id = $source[0]->quantity_sublocation_id;
                        // die(print_r($source_quant));
                    } else {
                        $source_quant = 0;
                    }

                    if(count($target) > 0){
                        $target_quant = (int)$target[0]->quantity;
                        $target_id = $target[0]->quantity_sublocation_id;
                        // die(print_r($target_quant));
                    } else {
                        $target_quant = 0;
                    }

                    $result[$key]['item_name'] = $val->item_name;
                    $result[$key]['item_number'] = $val->item_number;
                    $result[$key]['item_id'] = $val->item_id;
                    $result[$key]['source_id'] = $data['source'];
                    $result[$key]['target_id'] = $data['target'];
                    $result[$key]['source_quantity'] = $source_quant;
                    $result[$key]['target_quantity'] = $target_quant;

                }
            }

                        

        } else {
            $result = "Failure!";
        }

        $json_data = array(
            "data" => $result
        );
        echo json_encode($json_data);

        
    }

    public function getEditItemInput(){
        $data = $this->input->post();

        $company_id = $this->session->userdata['company_id'];

        // die(print_r($data));

        $items_data = explode('<::>', $data['items']);

        $item_ids = array();

        foreach($items_data as $i_dat){
            if(!in_array(explode(':', $i_dat)[0], $item_ids)){
                array_push($item_ids, explode(':', $i_dat)[0]);
            }
        }

        $items = $this->TransferModel->getItemListInput($data['item_input'], $company_id);

        // die(print_r($items));

        if($data['source'] != $data['target']){
          
            if(!empty($items) && $items[0] != ''){
                $i = 0;
                foreach($items as $key => $val){

                    if(!in_array($val->item_id, $item_ids)){
                        $source = $this->TransferModel->getSubLocationItemQuantity($val->item_id, $data['source']);
                        $target = $this->TransferModel->getSubLocationItemQuantity($val->item_id, $data['target']);

                    // die(print_r($source[0]));

                        if(count($source) > 0){
                            $source_quant = (int)$source[0]->quantity;
                            $source_id = $source[0]->quantity_sublocation_id;
                        // die(print_r($source_quant));
                        } else {
                            $source_quant = 0;
                        }

                        if(count($target) > 0){
                            $target_quant = (int)$target[0]->quantity;
                            $target_id = $target[0]->quantity_sublocation_id;
                        // die(print_r($target_quant));
                        } else {
                            $target_quant = 0;
                        }

                        $result[$i]['item_name'] = $val->item_name;
                        $result[$i]['item_number'] = $val->item_number;
                        $result[$i]['item_id'] = $val->item_id;
                        $result[$i]['source_id'] = $data['source'];
                        $result[$i]['target_id'] = $data['target'];
                        $result[$i]['source_quantity'] = $source_quant;
                        $result[$i]['target_quantity'] = $target_quant;
                        $i++;
                    }
                }
            }

                        

        } else {
            $result = "Failure!";
        }

        $json_data = array(
            "data" => $result
        );
        echo json_encode($json_data);

        
    }
}