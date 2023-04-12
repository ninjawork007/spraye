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

    public function __construct(){

        parent::__construct();

        if (!$this->session->userdata('email')) {

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
    }

	public function index($location_id = false) {
		// $this->data['route'] = 'warehouses';
		// $this->data['warehouseId'] = $warehouseId;

        $company_id = $this->session->userdata['company_id'];

        $where_arr = array(
            'location_id !=' => 0,
            'company_id' => $company_id
        );
        $data['all_locations'] = $this->LocationsModel->getAllLocations($where_arr);

        $data['all_fleets'] = $this->LocationsModel->getCompanyFleetNumbers($company_id);
        // die(print_r($data['all_locations']));
        $data['sub_locations'] = $this->LocationsModel->getAllSubLocations($where_arr);
        // die(print_r($data['sub_locations']));
        $sub_locations = $data['sub_locations'];
        // die(print_r($sub_locations));
        $sub_group = [];
        function group_subs($sub_locations, $key){
            $sub_group = array();
            foreach($sub_locations as $sub){
                $sub_group[$sub->$key][] = $sub;
            } 
            return $sub_group;
        };
        // die(print_r(group_subs($data['sub_locations'], 'location_id')));
        $data['grouped_subs'] = group_subs($data['sub_locations'], 'location_id');
        // die(print_r($data['grouped_subs']));
		// return view('warehouses/warehouses', $this->data);
		$page["active_sidebar"] = "locations";
        $page["page_name"] = 'Locations';
        $page["page_content"] = $this->load->view("inventory/locations/locations", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
	}

	public function new() {
		// $this->data['route'] = 'warehouses';
        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $settings->references_purchase_return_prepend = '';
        $settings->references_purchase_return_append = '';
        $data['settings'] =  $settings;
        $data['purchaseId'] = 1;
        // die(print_r($data['brands']));

        $page["active_sidebar"] = "locations";
        $page["page_name"] = 'Locations';
        $page["page_content"] = $this->load->view("inventory/locations/new_location", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);


		// return view('warehouses/new_warehouse', $this->data);
	}

	public function editLocation($location_id) {

        $settings = new stdClass();
        $settings->currency_symbol = '$';
        $settings->references_purchase_return_prepend = '';
        $settings->references_purchase_return_append = '';
        $data['settings'] =  $settings;
        $data['purchaseId'] = 1;
        
        $where = array('location_id' => $location_id);
		// die(print_r($where));
		$data = $this->input->post();
		$data['location'] = $this->LocationsModel->getLocation($where);
        $data['sub_locations'] = $this->LocationsModel->getSubLocationsByLocationId($where);
        // die(print_r($data));
        $page["active_sidebar"] = "locations";
        $page["page_name"] = 'Update Location';
        $page["page_content"] = $this->load->view("inventory/locations/edit_location", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);


		// return view('warehouses/new_warehouse', $this->data);
	}

    public function ajaxGetLocations()
    {
        $tblColumns = array(
            0 => 'location_name',
            1 => 'location_street',
            2 => 'location_city',
            3 => 'location_state',
            4 => 'location_zip',
            5 => 'location_country',
            6 => 'location_phone',
            7 => 'action',
            // 4 => 'action'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'locations_tbl.company_id' => $company_id,
            'locations_tbl.is_archived' => 0
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
            $tempdata  = $this->LocationsModel->getLocationDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->LocationsModel->getLocationDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->LocationsModel->getLocationAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->LocationsModel->getLocationAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        //  $var_last_query = $this->db->last_query ();

        //  die(print_r($var_last_query));

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {

                // die(print_r($value));

                $created_by_name = $this->LocationsModel->getCreatedByName($value->created_by);

                // set row data
                $data[$i]['location_name'] = $value->location_name;
                $data[$i]['location_street'] = $value->location_street;
                $data[$i]['location_city'] = $value->location_city;
                $data[$i]['location_state'] = $value->location_state;
                $data[$i]['location_country'] = $value->location_country;
                $data[$i]['location_zip'] = $value->location_zip;
                $data[$i]['location_phone'] = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", str_replace(' ','', str_replace('-','', $value->location_phone)));
                $data[$i]['actions'] = '<span class="pr-5"><a href="#" data-toggle="modal" data-target="#edit_location" data-id="' . $value->location_id . '" data-name="' . $value->location_name . '" data-street="' . $value->location_street . '" data-city="'. $value->location_city .'" data-state="'. $value->location_state .'" data-zip="'. $value->location_zip .'" data-country="'. $value->location_country .'" data-phone="'. $value->location_phone .'" data-created="'. $value->created_by .'" data-date="'. $value->created_at .'" class="button-next modal_trigger"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a href="' .  base_url('inventory/Backend/Locations/delete/') . $value->location_id . '" data-url="' . $value->location_id . '"class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></span>';
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
    public function ajaxGetSubLocations()
    {
        $tblColumns = array(
            0 => 'sub_location_name',
            1 => 'locations_tbl.location_id',
            2 => 'total_inventory_value',
            3 => 'sub_location_fleet_no',
            4 => 'action',
            // 4 => 'action'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'sub_locations_tbl.company_id' => $company_id,
            'sub_locations_tbl.is_archived' => 0
        );

        $data  = array();

        $fleets = array();

        $fleet_arr = $this->LocationsModel->getCompanyFleetNumbers($company_id);

        if(!empty($fleet_arr)){
            foreach($fleet_arr as $fl)
            {
                $fl_str = $fl->fleet_number . ':' . $fl->v_name;
                if(!in_array($fl_str, $fleets)){
                    array_push($fleets, $fl_str);
                }
            }
        }

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
            $tempdata  = $this->LocationsModel->getSubLocationDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->LocationsModel->getSubLocationDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->LocationsModel->getSubLocationAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->LocationsModel->getSubLocationAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        //  $var_last_query = $this->db->last_query ();

        //  die(print_r($var_last_query));

        $locations = array();

        $all_locations = $this->LocationsModel->getCompanyLocations($company_id);      

        // die(print_r($all_locations));
        foreach($all_locations as $all_locs){
            $location_str = $all_locs->location_id . ':' . $all_locs->location_name;
            if(!in_array($location_str, $locations) && $all_locs->is_archived != 1){
                array_push($locations, $location_str);
            }
        }
        // die(print_r($tempdata));
       
        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {             
                
                $total_value = $this->LocationsModel->getSubLocationTotalInventoryValue($value->sub_location_id);

                // die(print_r($value));

                $fleet_num = 'N/A';

                if(isset($value->sub_location_fleet_no)){

                    if(!$value->sub_location_fleet_no == 0){
                        $fleet_num = $value->sub_location_fleet_no;
                    }
                    
                }

                // set row data
                $data[$i]['sub_location_name'] = $value->sub_location_name;
                $data[$i]['location_id'] = $value->location_name;
                $data[$i]['sub_location_fleet_no'] = $fleet_num;
                $data[$i]['total_inventory_value'] = '$ ' . number_format($total_value, 2);
                $data[$i]['actions'] = '<span class="pr-5"><a href="#" data-toggle="modal" data-target="#edit_sub_location" data-id="' . $value->sub_location_id . '" data-locs="'. implode("::", $locations) .'" data-name="' . $value->sub_location_name . '" data-location="' . $value->location_name . '" data-locid="' . $value->location_id . '" data-fleet="'. $value->sub_location_fleet_no .'" data-fleets="'. implode('::', $fleets) .'" class="button-next sub_modal_trigger"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a href="' .  base_url('inventory/Backend/Locations/deleteSub/') . $value->sub_location_id . '" data-url="' . $value->sub_location_id . '"class="confirm_delete_sub button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></span>';
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

    public function exportLocationsCSV($value=''){

        $company_id = $this->session->userdata['company_id'];

        $where = array(
            'locations_tbl.company_id' => $company_id,
        );

        $data = $this->LocationsModel->getAllLocations($where);
   
        if($data){
  
            $delimiter = ",";
            $filename = "locations_" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Location Name','Street Address','City','State', 'Zip', 'Country', 'Phone Number');
  
            fputcsv($f, $fields, $delimiter);
  
          foreach ($data as $key => $value) {

  
            $lineData = array($value->location_name, explode(',', $value->location_street)[0] , $value->location_city, $value->location_state, $value->location_zip, $value->location_country,formatPhoneNum($value->location_phone));
           
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
         redirect("inventory/Frontend/Locations/");
      }
  
  
    }

    public function exportSubLocationsCSV($value=''){

        $company_id = $this->session->userdata['company_id'];

        $where = array(
            'sub_locations_tbl.company_id' => $company_id,
        );

        $data = $this->LocationsModel->getAllSubLocations($where);
   
        if($data){
  
            $delimiter = ",";
            $filename = "sub_locations_" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Sub-Location Name','Location Name','Total Inventory Value','Fleet Number');
  
            fputcsv($f, $fields, $delimiter);
  
          foreach ($data as $key => $value) {

            $location_name = $this->LocationsModel->getLocationName($value->location_id);

            // die(print_r($location_name));

            $lineData = array($value->sub_location_name, $location_name->location_name, '$ ' . $value->total_inventory_value, $value->sub_location_fleet_no);
           
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
         redirect("inventory/Frontend/Locations/");
      }
  
  
    }


}

