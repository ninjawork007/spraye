<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Adjustments extends MY_Controller
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

         $this->load->model('LocationsModel', 'LocationsModel');
         $this->load->model('AdjustmentsModel', 'AdjustmentModel');
    }


	
	public function index($adjustmentId = false) {
		// $this->data['route'] = 'adjustments';
		// $this->data['adjustmentId'] = $adjustmentId;
		$data['adjustmentId'] = $adjustmentId;
		$data = [
			// 'extend'        => 'templates/master',
			'header'        => 'adjustments/modals/adjustment_modal',
			'error'         => 'components/error_modal',
			'conformation'        => 'components/confirmation_modal',
			
		];
        $where_arr = array(
            '	quantity_adjustment_id !=' => 0
        );

        $company_id = $this->session->userdata['company_id'];

        $data['all_adjustments'] = $this->AdjustmentModel->getAllAdjustments($where_arr);

        $data['all_sublocations'] = $this->AdjustmentModel->getAllCompanySubLocations($company_id);
        // die(print_r($data['all_adjustments']));

		// return view('adjustments/adjustments', $this->data);
		$page["active_sidebar"] = "quantityAdjustments";
        $page["page_name"] = 'Quantity Adjustments';
        $page["page_content"] = $this->load->view("inventory/adjustments/view_adjustments", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
	}

	public function new() {
        // die('new adjustments');
		// $this->data['route'] = 'adjustments';
        // $data['vendors'] = $this->VendorModel->getVendorsList();
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $settings->references_purchase_return_prepend = '';
        $settings->references_purchase_return_append = '';
        $data['settings'] =  $settings;
        $data['purchaseId'] = 1;
        $data['list_sub_locations'] = $this->LocationsModel->getSubLocationsList();

        $page["active_sidebar"] = "quantityAdjustments";
        $page["page_name"] = 'Quantity Adjustments';
        $page["page_content"] = $this->load->view("inventory/adjustments/new_adjustment", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);


		// return view('adjustments/new_adjustment', $this->data);
	}

    public function ajaxGetAdjustments()
    {
        $tblColumns = array(

            0 => 'quantity_adjustment_id',
            1 => 'quantity_adjustments_tbl.item_id',
            2 => 'quantity_adjustments_tbl.created_at',
            3 => 'quantity_adjustments_tbl.location_id',
            4 => 'quantity_adjustments_tbl.sub_location_id',
            5 => 'quantity_adjustment_amount',
            6 => 'adjustment_type',
            7 => 'created_by',
            8 => 'units_lost',
            9 => 'value_lost',
            10 => 'actions'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'quantity_adjustments_tbl.company_id' => $company_id,
            'quantity_adjustments_tbl.is_archived' => 0
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
            $tempdata  = $this->AdjustmentModel->getAdjustmentDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->AdjustmentModel->getAdjustmentDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->AdjustmentModel->getAdjustmentAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->AdjustmentModel->getAdjustmentAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        //  $var_last_query = $this->db->last_query ();

        //  die(print_r($var_last_query));
        // die(print_r($tempdata));

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {


                // die(print_r($value));

                $created_by_name = $this->AdjustmentModel->getCreatedByName($value->created_by);
                $item_name = $this->AdjustmentModel->getItemName($value->item_id);

                $location_name = $this->AdjustmentModel->getLocationName($value->location_id);
                $sub_location_name = $this->AdjustmentModel->getSubLocationName($value->sub_location_id);

                $subs = $this->AdjustmentModel->getAllCompanySubLocations($company_id);

                $subs_arr = array();

                foreach($subs as $sub){
                    $sub_str = $sub->sub_location_id . '::' . $sub->location_name . '::' . $sub->sub_location_name;
                    if(!in_array($sub_str, $subs_arr)){
                        array_push($subs_arr, $sub_str);
                    }
                }

                

                $subs_str = implode('<::>', $subs_arr);

                $adjustment_type = '';

                if($value->adjustment_type == 0){
                    $adjustment_type = 'Add';
                } else if ($value->adjustment_type == 1){
                    $adjustment_type = 'Subtract';
                } else if($value->adjustment_type == 2){
                    $adjustment_type = 'Loss';
                }

                // set row data
                $data[$i]['quantity_adjustment_id'] = $value->quantity_adjustment_id;
                $data[$i]['item_name'] = $item_name;
                $data[$i]['adjustment_date'] = date("m/d/Y",strtotime($value->created_at));
                $data[$i]['location'] = $location_name;
                $data[$i]['sub_location'] = $sub_location_name;
                $data[$i]['quantity_adjustment_amount'] = $value->quantity_adjustment_amount;
                $data[$i]['adjustment_type'] = $adjustment_type;
                $data[$i]['created_by'] = $created_by_name;
                $data[$i]['units_lost'] = $value->units_lost;
                $data[$i]['value_lost'] = '$ ' . $value->value_lost;
                $data[$i]['actions'] = '<span class="pr-5"><a href="#" data-toggle="modal" data-target="#modal_edit_adjustment" data-edit_info="'. $value->edit_info .'" data-item_name="'. $item_name .'" data-loc="'. $value->location_id .'" data-subloc="'. $value->sub_location_id .'" data-subs="'. $subs_str .'" data-id="' . $value->quantity_adjustment_id . '" data-notes="'. $value->notes .'" class="button-next modal_trigger"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a href="' .  base_url('inventory/Frontend/Adjustments/adjustmentDelete/') . $value->quantity_adjustment_id . '" data-url="' . $value->quantity_adjustment_id . '"class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></span>';
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

    public function newAdjustment()
    {
        // print($item_type_id);

        $data = $this->input->post();

        

        // die(print_r($item_ids));

        $company_id = $this->session->userdata['company_id'];
        $user_id = $this->session->userdata['id'];

        $item = $data['item_id'];


            if($item != ''){

                $value_lost = 0.00;
                $units_lost = 0;

                if(explode(':', $item)[5] == 2){
                    $value_lost = number_format(explode(':', $item)[6], 2) * number_format(explode(':', $item)[4]);
                    $units_lost = number_format(explode(':', $item)[4]);
                }

                $data_arr = array(
                    'item_id'=> explode(':', $item)[0], 
                    'location_id' => explode(':', $data['sub_location_id'])[0],
                    'sub_location_id' => explode(':', $data['sub_location_id'])[1],
                    'quantity_adjustment_amount' => explode(':', $item)[4],
                    'company_id' => $company_id,
                    'created_by' => $user_id,
                    'adjustment_type' => explode(':', $item)[5],
                    'units_lost' => $units_lost,
                    'value_lost' => $value_lost,
                    'notes' => $data['notes'],
                    'edit_info' => $item
                );
        
        
                    $result = $this->AdjustmentModel->createNewAdjustment($data_arr);

                    if (!$result) {

                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');
        
                        redirect("inventory/Frontend/Adjustments/");
                    } else {

                        $quant = array(
                            'quantity_item_id' => explode(':', $item)[0],
                            'quantity_location_id' => explode(':', $data['sub_location_id'])[0],
                            'quantity_sublocation_id' => explode(':', $data['sub_location_id'])[1]
                        );

                        $adjusted = $this->AdjustmentModel->updateQuantitiesTbl($quant, explode(':', $item)[4], explode(':', $item)[5]);


                        if(!isset($adjusted)){
                            $quant_new = array(
                                'quantity_item_id' => explode(':', $item)[0],
                                'quantity_location_id' => explode(':', $data['sub_location_id'])[0],
                                'quantity_sublocation_id' => explode(':', $data['sub_location_id'])[1],
                                'quantity' => explode(':', $item)[4],
                                'company_id' => $company_id
                            );

                            $this->AdjustmentModel->createNewQuantity($quant_new);
                        }
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Adjustment </strong>created successfully.</div>');
                        redirect("inventory/Frontend/Adjustments/");
                    }      
            }
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');
                redirect("inventory/Frontend/Adjustments/");
            }

                   
    }

    public function editAdjustment()
    {
        // print($item_type_id);

        $data = $this->input->post();

        

        // die(print_r($item_ids));

        $company_id = $this->session->userdata['company_id'];
        $user_id = $this->session->userdata['id'];

        $item = $data['edit_item_id'];


            if($item != ''){

                $value_lost = 0.00;
                $units_lost = 0;

                if($data['ad_type'] == 2){
                    $value_lost = number_format(explode(':', $item)[6], 2) * number_format(explode(':', $item)[4]);
                    $units_lost = number_format(explode(':', $item)[4]);
                }

                $data_arr = array(
                    'quantity_adjustment_amount' => explode(':', $item)[4],
                    'adjustment_type' => $data['ad_type'],
                    'units_lost' => $units_lost,
                    'value_lost' => $value_lost,
                    'notes' => $data['edit_notes'],
                    'edit_info' => $item
                );
        
        
                    $result = $this->AdjustmentModel->updateAdjustmentsTbl($data['adjust_id'], $data_arr);

                    if (!$result) {

                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');
        
                        redirect("inventory/Frontend/Adjustments/");
                    } else {

                        $quant = array(
                            'quantity_item_id' => explode(':', $item)[0],
                            'quantity_location_id' =>  $data['loc'],
                            'quantity_sublocation_id' => $data['subloc'],
                        );

                        $adjusted = $this->AdjustmentModel->updateQuantitiesTbl($quant, explode(':', $item)[4], explode(':', $item)[5]);


                        if(!isset($adjusted)){
                            $quant_new = array(
                                'quantity_item_id' => explode(':', $item)[0],
                                'quantity_location_id' =>  $data['loc'],
                                'quantity_sublocation_id' => $data['subloc'],
                                'quantity' => explode(':', $item)[4],
                                'company_id' => $company_id
                            );

                            $this->AdjustmentModel->createNewQuantity($quant_new);
                        }
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Adjustment </strong>updated successfully.</div>');
                        redirect("inventory/Frontend/Adjustments/");
                    }      
            }
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');
                redirect("inventory/Frontend/Adjustments/");
            }

                   
    }

    public function getDropdownInput(){
        $data = $this->input->post();

        // die(print_r($data));
        $company_id = $this->session->userdata['company_id'];


        $items = $this->AdjustmentModel->getDropDownInput($data['item_input'], $company_id);


        // die(print_r($items));

          
            if(!empty($items) && $items[0] != ''){
                foreach($items as $key => $val){

                    // die(print_r($source[0]));

                    $result[$key]['item_name'] = $val->item_name;
                    $result[$key]['item_number'] = $val->item_number;
                    $result[$key]['item_id'] = $val->item_id;
                }
            }

        $json_data = array(
            "data" => $result
        );
        echo json_encode($json_data);

        
    }

    public function getItemInput(){
        $data = $this->input->post();

        // die(print_r($data));

        $items = $this->AdjustmentModel->getItemListInput($data['item_input']);
          
            if(!empty($items) && $items[0] != ''){
                foreach($items as $key => $val){
                    $sub = $this->AdjustmentModel->getSubLocationItemQuantity($val->item_id, $data['sub_location']);

                    // die(print_r($data['sub_location']));

                    if(count($sub) > 0){
                        $sub_quant = (int)$sub[0]->quantity;
                        $sub_id = $sub[0]->quantity_sublocation_id;
                        
                    } else {
                        $sub_quant = 0;
                    }


                    $result[$key]['item_name'] = $val->item_name;
                    $result[$key]['item_number'] = $val->item_number;
                    $result[$key]['item_id'] = $val->item_id;
                    $result[$key]['sub_id'] = isset($sub_id) ? $sub_id : '';
                    $result[$key]['sub_quantity'] = $sub_quant;
                    $result[$key]['average'] = $val->average_cost_per_unit;

                }
            }

        $json_data = array(
            "data" => $result
        );
        echo json_encode($json_data);

        
    }

    public function adjustmentDelete($adjustment_id){
         //print($item_type_id);

         $param = array('is_archived' => 1);

         $deleted = $this->AdjustmentModel->getAdjustmentById($adjustment_id)[0];

         $result = $this->AdjustmentModel->deleteAdjustmentRow(array('quantity_adjustment_id'=> $adjustment_id));

         if (!$result) {

             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

             redirect("inventory/Frontend/Adjustments/");

         } else {

            
            $type = 0;

            // die(print_r($deleted));

            if ($deleted->adjustment_type == 0){
                $type == 1;
            }

            $quant = array(
                'quantity_item_id' => $deleted->item_id,
                'quantity_location_id' => $deleted->location_id,
                'quantity_sublocation_id' => $deleted->sub_location_id,
            );

            $this->AdjustmentModel->updateQuantitiesTbl($quant, $deleted->quantity_adjustment_amount, $type);

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Quantity Adjustment </strong>deleted successfully</div>');
            redirect("inventory/Frontend/Adjustments/");
         }        
    }

    public function exportAdjustmentsCSV($value=''){

        $company_id = $this->session->userdata['company_id'];

        $where = array(
            'quantity_adjustments_tbl.company_id' => $company_id,
        );

        $data = $this->AdjustmentModel->getAllAdjustments($where);
   
        if($data){
  
            $delimiter = ",";
            $filename = "adjustments_" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Adjustment ID','Item Name','Location','Sub-Location', 'Adjustment Amount', 'Adjustment Type', 'Created By', 'Units Lost', 'Value Lost', 'Notes');
  
            fputcsv($f, $fields, $delimiter);
  
          foreach ($data as $key => $value) {

                $type = '';
                if($value->adjustment_type == 0){
                    $type = 'Add';
                } else if($value->adjustment_type == 1){
                    $type = 'Subtract';
                } else if($value->adjustment_type == 2){
                    $type = 'Loss';
                }

                $created_by_name = $this->AdjustmentModel->getCreatedByName($value->created_by);
                $item_name = $this->AdjustmentModel->getItemName($value->item_id);

                $location_name = $this->AdjustmentModel->getLocationName($value->location_id);
                $sub_location_name = $this->AdjustmentModel->getSubLocationName($value->sub_location_id);

  
            $lineData = array($value->quantity_adjustment_id, $item_name, $location_name, $sub_location_name, $value->quantity_adjustment_amount, $type, $created_by_name, $value->units_lost, '$ ' . $value->value_lost, $value->notes);
           
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
         redirect("inventory/Frontend/Adjustments/");
      }
  
  
    }



}