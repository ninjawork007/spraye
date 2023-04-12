<?php 
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';

require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Items extends MY_Controller
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
        $this->load->model('ItemsModel', 'ItemModel');
        $this->load->model('ItemTypesModel', 'ItemTypeModel');
        $this->load->model('BrandsModel', 'BrandModel');
    }


	public function index($itemId = false) {
		
        $where_arr = array(
            'item_id !=' => 0
        );
        $data['all_items'] = $this->ItemModel->GetAllItems($where_arr);

        $company_id = $this->session->userdata['company_id'];

        $data['all_types'] = $this->ItemModel->getCompanyItemTypes($company_id);

        $data['all_brands'] = $this->ItemModel->getCompanyBrands($company_id);

        $data['all_vendors'] = $this->ItemModel->getCompanyVendors($company_id);

        $vendor_arr = array();
        foreach($data['all_vendors'] as $v){
            $v_str = $v->vendor_id. ':' .$v->vendor_name;
            if(!in_array($v_str, $vendor_arr)){
                array_push($vendor_arr, $v_str);
            }
        }
        $data['vendor_str'] = implode('::',$vendor_arr);

        $data['all_products'] = $this->ItemModel->getCompanyProducts($company_id);

        $products_arr = array();

        foreach($data['all_products'] as $prod){
            $prod_str = $prod->product_id . '::' . $prod->product_name . '::' . $prod->product_cost_unit . '::' . $prod->product_type;
            if(!in_array($prod_str, $products_arr)){
                array_push($products_arr, $prod_str);
            }
        }
        
        $data['products_str'] = implode('<::>', $products_arr);
		$page["active_sidebar"] = "items";
        $page["page_name"] = 'Items';
        $page["page_content"] = $this->load->view("inventory/items/view_items", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
	}

	public function new() {
		$data['brands'] = $this->BrandModel->getBrandsList();
		$data['item_types'] = $this->ItemTypeModel->getItemTypesList();

        $page["active_sidebar"] = "items";
        $page["page_name"] = 'Items';
        $page["page_content"] = $this->load->view("inventory/items/new_item", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
	}

    public function ajaxGetItems()
    {
        $tblColumns = array(
            0 => 'item_name',
            1 => 'item_number',
            2 => 'item_description',
            3 => '`item_types`.`item_type_name`',
            4 => 'unit_definition',
            5 => 'total_units_on_hand',
            6 => 'average_cost_per_unit',
            7 => 'available_vendors',
            10 => 'preferred_vendor',
            11 => 'ideal_ordering_timeframe',
            12 => 'actions',
            13 => 'value_of_unit_on_hand'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'items_tbl.company_id' => $company_id,
            'items_tbl.is_archived' => 0
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
            $tempdata  = $this->ItemModel->getItemDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->ItemModel->getItemDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->ItemModel->getItemsDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->ItemModel->getItemsDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        $types = array();

        $types_data = $this->ItemModel->getCompanyItemTypes($company_id);

        foreach($types_data as $typ_dat){
            $type_str = $typ_dat->item_type_id . ':' . $typ_dat->item_type_name;
            if(!in_array($type_str, $types) && $typ_dat->is_archived != 1){
                array_push($types, $type_str);
            }
        }

        //Add default product type when not present in used Item Types for company
        if(!in_array('1:Product', $types)){
            array_unshift($types, '1:Product');
        }

        $prods = array();

        $prods_data = $this->ItemModel->getCompanyProducts($company_id);

        $prod_types = array();

        foreach($prods_data as $pro_dat){
            $prodtype = $pro_dat->product_id . '::' . $pro_dat->product_type;
            if(!in_array($prodtype, $prod_types)){
                array_push($prod_types, $prodtype);
            }
            $pro_str = $pro_dat->product_id . '::' . $pro_dat->product_name . '::' . $pro_dat->product_cost_per . ' ' . $pro_dat->product_cost_unit;
            if(!in_array($pro_str, $prods)){
                array_push($prods, $pro_str);
            }
        }

        $brands = array();

        $brands_data = $this->ItemModel->getCompanyBrands($company_id);      

        foreach($brands_data as $bran_dat){
            $brand_str = $bran_dat->brand_id . ':' . $bran_dat->brand_name;
            if(!in_array($brand_str, $brands) && $bran_dat->is_archived != 1){
                array_push($brands, $brand_str);
            }
        }

        // Grab all company Vendors to populate Available Vendors Dropdown
        $vendors_data = $this->ItemModel->getCompanyVendors($company_id);

        $vendors = array();

        // die(print_r($vendors_data));

        foreach($vendors_data as $ven => $dor){
            $ven_str = $dor->vendor_id . ':' . $dor->vendor_name;
            if(!in_array($ven_str, $vendors)){
                array_push($vendors, $ven_str);
            }
        }

            // die(print_r($vendors));



        if (!empty($tempdata)) {
            $i = 0;
            

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {

                $item_where = array(
                    'company_id' => $company_id,
                    'quantity_item_id' => $value->item_id
                );

                // Grab total aount of items in all locations and sublocations
                $total_units_on_hand = $this->ItemModel->getTotalItemAmount($item_where);


                $item_prods = array();

                $item_prods_data = array();

                $item_prod_names = array();

                // print_r($value->item_id);
                

                if($value->item_type_id == 1){
                    $item_prods_data = $this->ItemModel->getProductsByItemID($value->item_id, $company_id);
                }


                $unit_con_type = '';

                if(!empty($item_prods_data)){
                    foreach($item_prods_data as $ipd){
                        $unit_con_type = $ipd->unit_conversion_type;
                        $ipd_str = $ipd->product_id . '::' . $ipd->product_name . '::' . $ipd->product_cost_per . ' ' . $ipd->product_cost_unit;
                        if(!in_array($ipd_str, $item_prods)){
                            array_push($item_prods, $ipd_str);
                        }

                        if(!in_array($ipd->product_name, $item_prod_names)){
                            array_push($item_prod_names, $ipd->product_name);
                        }
                    }
                    // die(print_r($item_prods));
                }
                


                $item_vendors = array();
                $prices = array();


                $where = array(
                    'item_vendors.company_id' => $company_id,
                    'item_vendors.item_id' => $value->item_id,
                    'vendors_tbl.is_archived' => 0

                );

                $item_vendors_data = $this->ItemModel->getAvailableVendorsList($where);

                // die(print_r($item_vendors_data));
                if(count($item_vendors_data) > 0){
                    foreach($item_vendors_data as $ven => $dor){
                        $ven_str1 = $dor->vendor_id . ':' . $dor->vendor_name;
                        if(!in_array($ven_str1, $item_vendors)){
                            array_push($item_vendors, $ven_str1);
                        }
                        $ven_str2 = $dor->vendor_id . ':' . $dor->vendor_name  . ':' . $dor->price_per_unit .  ':' . $dor->vendor_notes;
                        if(!in_array($ven_str2, $prices)){
                            array_push($prices, $ven_str2);
                        }
                    }
                }
                
                $prices_str = isset($prices) ? implode('::', $prices) : '';
                $item_vendors_str = isset($item_vendors) ? implode('::', $item_vendors) : '';

                $ven_list = array();
                if (count($item_vendors_data)){
                    foreach($item_vendors_data as $j => $p){
                        if(!in_array($p->vendor_name, $ven_list)){
                            array_push($ven_list, $p->vendor_name);
                        }
                    }
                }

                $pieces = explode(':', $value->preferred_vendor);

                // set row data
                $data[$i]['item_name'] = $value->item_name;
                $data[$i]['item_number'] = $value->item_number;
                $data[$i]['item_description'] = $value->item_description;
                $data[$i]['item_type_name'] = $value->item_type_name;
                $data[$i]['unit_definition'] = $value->unit_amount . ' ' . $value->unit_type;
                $data[$i]['products_associated'] = !empty($item_prod_names) ? implode(', ', $item_prod_names) : 'N/A';
                $data[$i]['total_units_on_hand'] = number_format($total_units_on_hand, 2);
                $data[$i]['average_cost_per_unit'] = '$ ' . number_format($value->average_cost_per_unit, 2, '.', ',');
                $data[$i]['value_of_unit_on_hand'] = '$ ' . number_format(($total_units_on_hand)*$value->average_cost_per_unit, 2, '.', ',');
                $data[$i]['available_vendors'] = implode(', <br/> ', $ven_list);
                $data[$i]['preferred_vendor'] = isset($pieces[1]) ? $pieces[1] : '';
                $data[$i]['ideal_ordering_timeframe'] = $value->ideal_ordering_timeframe;
                $data[$i]['actions'] = '<span class="pr-5"><a href="#" data-prodtypes="'. implode('<::>', $prod_types) .'" data-contype="'. $unit_con_type .'" data-prices="' . $prices_str . '" data-prods="'.implode('<::>', $prods).'" data-item_prods="'. implode('<::>', $item_prods) .'" data-vendors="'. implode('::', $vendors) .'"data-item_vendors="'. $item_vendors_str .'" data-brands="'. implode("::", $brands) .'" data-types="'. implode("::", $types) .'"data-toggle="modal" data-target="#modal_edit_item" data-id="'.$value->item_id.'" data-name="'.$value->item_name.'" data-typeid="'.$value->item_type_id.'" data-typename="'.$value->item_type_name.'" data-desc="'.$value->item_description.'" data-num="'.$value->item_number.'" data-unit_amount="'.$value->unit_amount.'" data-unit_type="'.$value->unit_type.'" data-hand="'.$total_units_on_hand.'" data-aver="'.number_format($value->average_cost_per_unit, 2).'" data-pref="'.$value->preferred_vendor.'" data-ideal="'.$value->ideal_ordering_timeframe.'" data-brandid="'. $value->brand_id .'" data-brandname="'. $value->brand_name .'" data-notes="'. $value->notes .'"  class="button-next modal_trigger_item"><i class="icon-pencil position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a href="' .  base_url('inventory/Frontend/Items/itemDelete/') . $value->item_id . '" class="confirm_item_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a></span>';
                $i++;
            }
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => $var_total_item_count_for_pagination, // "(filtered from __ total entries)"
            "recordsFiltered" => $var_total_item_count_for_pagination, // actual total that determines page counts
            "data"            => $data,
            "types"           => implode("::", $types)
        );
        echo json_encode($json_data);
    }

    public function itemDelete($item_id)
    {
        $param = array('is_archived' => 1, 'deleted_at' => date("Y-m-d H:i:s"));

        $item_prod_delete = $this->db->delete('item_product_tbl', array('item_id' => $item_id));;

        $item_qty_delete = $this->db->delete('quantities', array('quantity_item_id' => $item_id));;

        $result = $this->ItemModel->updateItemsTbl($item_id, $param);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("inventory/Frontend/Items/");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Item </strong>deleted successfully</div>');
            redirect("inventory/Frontend/Items/");
        } 
    }

    public function editItem()
    {
        $data = $this->input->post();

        $company_id = $this->session->userdata['company_id'];

        $item_products = array();

        $item_products_data = $this->ItemModel->getProductsByItemID($data['item_id'], $company_id);

        if(!empty($item_products_data)){
            foreach($item_products_data as $idp){
                $idp_str = $idp->product_id . ':' . $idp->product_name;
                if(!in_array($idp_str, $item_products)){
                    array_push($item_products, $idp_str);
                }
            }
        } 

        $data_products = explode("::", $data['options_checked_editprods']);

        // die(print_r($data_products));

        $unchecked_prods = explode("::", $data['options_unchecked_editprods']);

        $new_prods = array();

        if (!empty($data_products)){
            foreach($data_products as $dp){
                if(!in_array($dp,$item_products)){
                    $existing = $this->ItemModel->getItemProduct(array(
                        'item_id' => $data['item_id'],
                        'product_id' => explode(":", $dp)[0],
                        'company_id' => $company_id
                    ));
                    if(!empty($existing)){
                        $this->ItemModel->updateItemProductTbl($existing[0]->item_product_id, array('is_archived' => 0));
                    } else {
                        array_push($new_prods, $dp);
                    }                   
                }
            }
        }

        if(!empty($new_prods)){
            foreach($new_prods as $np){
                $np_ex = explode(':', $np);

                $np_arr = array(
                    'company_id' => $company_id,
                    'item_id' => $data['item_id'],
                    'product_id' => $np_ex[0]
                );

                $this->ItemModel->addNewItemProducts($np_arr);

            }
        }

        if(!empty($unchecked_prods)){
            foreach($unchecked_prods as $up){
                if(in_array($up, $item_products)){
                    $ip = $this->ItemModel->getItemProduct(array(
                        'company_id' => $company_id,
                        'item_id' => $data['item_id'],
                        'product_id' => explode(":", $up)[0]
                    ));

                    // die(print_r($ip));

                    $this->ItemModel->updateItemProductTbl($ip[0]->item_product_id, array('is_archived' => 1));
                }
            }
        }

        $item_vendors = array();

        $where = array(
            'item_vendors.company_id' => $company_id,
            'item_vendors.item_id' => $data['item_id']
        );

        $item_vendors_data = $this->ItemModel->getAvailableVendorsList($where);

        foreach($item_vendors_data as $k => $v){
            $v_str = $v->vendor_id . ':' . $v->vendor_name;
            if(!in_array($v_str, $item_vendors)){
                array_push($item_vendors, $v_str);
            }
        }

        $data_vendors = explode("::", $data['options_checked']);

        $data_unchecked = explode("::", $data['options_unchecked']);

        $data_prices = explode("::", $data['edit_prices_per_unit']);

        

        $data_notes = explode('::', $data['edit_vendor_notes_input']);

        $del_vendors = array();

        $new_vendors = array();

        foreach($data_vendors as $key => $val){
            if (!in_array($val, $item_vendors)){
                array_push($new_vendors, $val);
            }
        }

        foreach($data_unchecked as $key => $val){
            if (in_array($val, $item_vendors)){
                array_push($del_vendors, $val);
            }
        }

        
        if(!empty($data_vendors)){
            foreach($data_vendors as $vend){
                $vend_ex = explode(':', $vend);
                // die(print_r($vend_ex));
                $vendor_price = '';
                $vendor_notes = '';
    
                if(!empty($data_prices)){
                    // die(print_r($data['edit_prices_per_unit']));
                    foreach($data_prices as $dpr){
                        // die(print_r($data_prices));
                        if(explode(':', $dpr)[0] == $vend_ex[0] && !empty(explode(':', $dpr)[1])){
                            // die(print_r($data['edit_prices_per_unit']));
                            $vendor_price = explode(':', $dpr)[1];
                        }
                    }
                }
                
    
                if(!empty($data_notes) && $data_notes[0] != 0){
                    foreach($data_notes as $dno){
                        if(explode(':', $dno)[0] == $vend_ex[0] && isset(explode(':', $dno)[1]) && !empty(explode(':', $dno)[1])){
                            $vendor_notes = explode(':', $dno)[1];
                        }
                    }
        
                }
                
                if($vend_ex[0] != ''){
                    $update_arr = array(
                        'price_per_unit' => $vendor_price,
                        'vendor_notes' => $vendor_notes
                    );
    
                    $vend_arr = array(
                        'company_id' => $company_id,
                        'item_id' => $data['item_id'],
                        'vendor_id' => $vend_ex[0],
                    );
        
                    $this->ItemModel->updateItemVendor($vend_arr, $update_arr);
                }
            }
        }


        foreach($new_vendors as $vend){
            $vend_ex = explode(':', $vend);
            $vendor_price = '';
            $vendor_notes = '';

            if(!empty($data_prices) && $data_prices[0] != ''){
                foreach($data_prices as $dpr){
                    // die(print_r($data_prices));
                    if(explode(':', $dpr)[0] == $vend_ex[0]){
                        $vendor_price = explode(':', $dpr)[1];
                    }
                }
            }

            
            if(!empty($data_notes)){
                foreach($data_notes as $dno){
                    if($dno != ''){
                        if(strlen($dno) == 1){
                            $dno = $dno . ':-';
                        }
                        
                        if(explode(':', $dno)[0] == $vend_ex[0] && isset($dno[1]) && $dno[1] != 'undefined'){
                            $vendor_notes = explode(':', $dno)[1];
                        } else {
                            $vendor_notes = ' ';
                        }
                    } 
                    
                }
            }

            if($vend_ex[0] != ''){
                $vend_arr = array(
                    'company_id' => $company_id,
                    'item_id' => $data['item_id'],
                    'vendor_id' => $vend_ex[0],
                    'price_per_unit' => $vendor_price,
                    'vendor_notes' => $vendor_notes
                );
    
                $this->ItemModel->addNewItemVendor($vend_arr);
            }
        }

        

        foreach($del_vendors as $vend){
            if($vend == $data['preferred_vendor']){
                $data['preferred_vendor'] = NULL;
            }
            $vend_ex = explode(':', $vend);

            if($vend_ex[0] != ''){
                $vend_arr = array(
                    'company_id' => $company_id,
                    'item_id' => $data['item_id'],
                    'vendor_id' => $vend_ex[0]
                );

                $this->ItemModel->deleteItemVendor($vend_arr);
            }
        }

        

        $data_arr = array(
            'item_name'=> $data['item_name'],
            'item_number' => $data['item_number'],  
            'item_description' => $data['item_description'],                      
            'item_type_id' => $data['item_type'],
            'unit_amount' => $data['unit_amount'],
            'unit_type' => $data['unit_type'],
            'average_cost_per_unit' => $data['average_cost_per_unit'],
            'preferred_vendor' => $data['preferred_vendor'],
            'ideal_ordering_timeframe' => $data['ideal_ordering_timeframe'],
            'brand_id' => $data['brand_id'],
            'notes' => $data['item_notes']
        );



        
        $result = $this->ItemModel->updateItemsTbl($data['item_id'], $data_arr);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("inventory/Frontend/Items/");
        
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Item </strong>updated successfully.</div>');
            
            redirect("inventory/Frontend/Items/");

        }          
    }

    public function newItem()
    {

        $data = $this->input->post();

        $company_id = $this->session->userdata['company_id'];
        $user_id = $this->session->userdata['id'];

        $data_arr = array(
            'item_name'=> $data['item_name'],
            'item_number' => $data['item_number'],  
            'item_description' => $data['item_description'],                      
            'item_type_id' => $data['new_item_type'],
            'unit_amount' => $data['unit_amount'],
            'unit_type' => $data['unit_type'],
            'average_cost_per_unit' => $data['average_cost_per_unit'],
            'preferred_vendor' => $data['preferred_vendor'],
            'ideal_ordering_timeframe' => $data['ideal_ordering_timeframe'],
            'created_by' => $user_id,
            'company_id' => $company_id,
            'brand_id' => $data['brand_id'],
            'notes' => $data['item_notes'],
            'chosen_average' => $data['average_cost_per_unit']

        );

        
        $result = $this->ItemModel->createNewItem($data_arr);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("inventory/Frontend/Items/");
        
        } else {

        $item_vendors = array();

        $where = array(
            'item_vendors.company_id' => $company_id,
            'item_vendors.item_id' => $result
        );

        $item_vendors_data = $this->ItemModel->getAvailableVendorsList($where);        

        foreach($item_vendors_data as $k => $v){
            $v_str = $v->vendor_id . ':' . $v->vendor_name;
            if(!in_array($v_str, $item_vendors)){
                array_push($item_vendors, $v_str);
            }
        }


        $data_vendors = explode("::", $data['options_checked_new']);

        $new_vendors = array();

        foreach($data_vendors as $key => $val){
            if (!in_array($val, $item_vendors)){
                array_push($new_vendors, $val);
            }
        }

        $data_prices = explode("::", $data['new_prices_per_unit']);
        $data_notes = explode('::', $data['new_vendor_notes']);

        foreach($new_vendors as $vend){
            $vend_ex = explode(':', $vend);

            $vendor_price = '';
            $vendor_notes = '';

            foreach($data_prices as $dpr){
                if(explode(':', $dpr)[0] == $vend_ex[0]){
                    $vendor_price = explode(':', $dpr)[1];
                }
            }
            if(!empty($data_notes)){
                // die(print_r($data_notes));
                foreach($data_notes as $dno){
                    // die(print_r($dno));
                    if($dno != ''){
                        
                        if(explode(':', $dno)[0] == $vend_ex[0] && isset($dno[1]) && $dno[1] != 'undefined'){
                            $vendor_notes = explode(':', $dno)[1];
                        }
                    } 
                    
                }
            }
            

            if($vend_ex[0] != ''){
                $vend_arr = array(
                    'company_id' => $company_id,
                    'item_id' => $result,
                    'vendor_id' => $vend_ex[0],
                    'price_per_unit' => $vendor_price,
                    'vendor_notes' => $vendor_notes
                );
    
                $this->ItemModel->addNewItemVendor($vend_arr);
            }
        }

        $data_prods = explode("::", $data['options_checked_prods']);

        if(!empty($data_prods)){
            foreach($data_prods as $prod){
                $prod_arr = array(
                    'company_id' => $company_id,
                    'item_id' => $result,
                    'product_id' => $prod,
                    'unit_conversion_type' => $data['unit_type_conversion']               
                );

                $this->ItemModel->addNewItemProducts($prod_arr);
            }
        }

        $locs = $this->ItemModel->getAllCompanyLocations($company_id);
          if(!empty($locs)){
            foreach($locs as $loc){
                $subs = $this->ItemModel->getAllCompanySubLocationsByLocation($loc->location_id);

                if(!empty($subs)){
                    foreach($subs as $sub){
                        $quant_arr = array(
                            'quantity_item_id' => $result,
                            'quantity_location_id' => $loc->location_id,
                            'quantity_sublocation_id' => $sub->sub_location_id,
                            'quantity' => 0,
                            'company_id' => $company_id
                        );

                        $quant = $this->db->insert('quantities', $quant_arr);
                    }
                }
            }
          }
        

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Item </strong>created successfully.</div>');
            
            redirect("inventory/Frontend/Items/");

        }          
    }

    public function exportItemsCSV($value=''){

        $company_id = $this->session->userdata['company_id'];

        $where = array(
            'items_tbl.company_id' => $company_id,
            'items_tbl.is_archived' => 0
        );

        $data = $this->ItemModel->getAllItems($where);
   
        if($data){
  
            $delimiter = ",";
            $filename = "items_" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Item Name','Item #','Item Description','Item Type','Unit Definition', '# of Units on Hand', 'Average Cost Per Unit', 'Available Vendors', 'Preferred Vendor', 'Ideal Ordering Timeframe');
  
            fputcsv($f, $fields, $delimiter);
  
          foreach ($data as $key => $value) {

            $type_name = $this->ItemModel->getItemTypeByID($value->item_type_id);

            // die(print_r($type_name));

            $where_list = array(
                'item_vendors.company_id' => $company_id,
                'item_vendors.item_id' => $value->item_id
            );

            $item_vendors_data = $this->ItemModel->getAvailableVendorsList($where_list);

            $vend_names = array();

            foreach($item_vendors_data as $vend){
                if(!in_array($vend->vendor_name, $vend_names)){
                    array_push($vend_names, $vend->vendor_name);
                }
            }

            $vends = implode(',', $vend_names);

            $pieces = explode(':', $value->preferred_vendor);

            $piece = isset($pieces[1]) ? $pieces[1] : '';
  
            $lineData = array($value->item_name, $value->item_number, $value->item_description, $type_name[0]->item_type_name, $value->unit_amount . ' ' . $value->unit_type, $value->total_units_on_hand, '$' . number_format($value->average_cost_per_unit, 2, '.', ','), $vends, $piece, $value->ideal_ordering_timeframe );
           
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
         redirect("inventory/Frontend/Items/");
      }
  
  
    }

    public function overall_item_quantity() {
		
        $company_id = $this->session->userdata['company_id'];
        $where_arr = array(
            'company_id' => $company_id,
            'is_archived' => 0
        );

        $data['all_items'] = $this->ItemModel->GetAllItems($where_arr);
        // die(print_r($data['all_items']));

        $data['all_locations'] = $this->ItemModel->getAllCompanyLocations($company_id);
        $items = $this->ItemModel->getAllItems(array('company_id' => $company_id, 'is_archived' => 0));

        $totals = array();
        $quanti = array();
        $total = 0;
        
        foreach($items as $item){
            $total_strings = array();
            $quants_str = array();
            $total = 0;
            foreach($data['all_locations'] as $location){
            
                $quants = $this->ItemModel->getLocationQuantities($location->location_id, $item->item_id);
                foreach($quants as $quant){
                    $total += $quant->quantity;
                }
            }
        }
        // die(print_r($totals));

        $data['quantities'] = $total;
        $data['quant_str'] = implode('<%%>', $quanti);

		$page["active_sidebar"] = "overall_item_quantity";
        $page["page_name"] = 'Item Quantity';
        $page["page_content"] = $this->load->view("inventory/items/overall_item_quantity", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);
	}

    public function exportItemLocationsCSV($value=''){

        // die(print_r($data['all_items']));

        $company_id = $this->session->userdata['company_id'];        
        
        $items = $this->ItemModel->getAllItems(array('company_id' => $company_id, 'is_archived' => 0));

        $locations = $this->ItemModel->getAllCompanyLocations($company_id);
   
        if(!EMPTY($items)){
  
            $delimiter = ",";
            $filename = "overall_item_quantity" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Item Name');

            foreach($locations as $location){
                if(!in_array($location->location_name, $fields)){
                    array_push($fields, $location->location_name);
                }
            }

            array_push($fields, 'Overall Total');
  
            fputcsv($f, $fields, $delimiter);
  
            foreach ($items as $key => $value) {

                $total_strings = array();

                $overall = 0;
                foreach($locations as $location){
                    $total = 0;
                    $quants = $this->ItemModel->getLocationQuantities($location->location_id, $value->item_id);
                    foreach($quants as $quant){
                        $total += $quant->quantity;
                        $overall += $quant->quantity;
                    }

                    $loc_str = $location->location_name . ':' . $total;
                    if(!in_array($loc_str, $total_strings)){
                        array_push($total_strings, $loc_str);
                    }
                }
  
                $lineData = array($value->item_name);

                foreach($total_strings as $tot){
                    array_push($lineData, number_format(explode(':', $tot)[1], 2));
                }

                array_push($lineData, number_format($overall, 2));

           
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
            redirect("inventory/Frontend/Items/overall_item_quantity");
        }
  
  
    }

    public function ajaxGetOverallItemQuantity()
    {
        $tblColumns = array(
            0 => 'item_name'
        );

        $company_id = $this->session->userdata['company_id'];

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $where = array(
            'items_tbl.company_id' => $company_id,
            'items_tbl.is_archived' => 0
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
            $tempdata  = $this->ItemModel->getItemLocationsDataAjax($where, $where_like, $limit, $start, false);
            $var_total_item_count_for_pagination = $this->ItemModel->getItemLocationsDataAjax($where, $where_like, $limit, $start, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->ItemModel->getItemLocationsDataAjaxSearch($where, $where_like, $limit, $start, $search, false);
            $var_total_item_count_for_pagination = $this->ItemModel->getItemLocationsDataAjaxSearch($where, $where_like, $limit, $start, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

            // die(print_r($vendors));
        
        $locations = $this->ItemModel->getAllCompanyLocations($company_id);
        

        if (!empty($tempdata)) {
            $i = 0;
            

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {

                $total = 0;
                foreach($locations as $location){
                    $quants = $this->ItemModel->getLocationQuantities($location->location_id, $value->item_id);
                    foreach($quants as $quant){
                        $total += $quant->quantity;
                    }
                }

                // set row data
                $data[$i]['item_name'] = $value->item_name;
                $data[$i]['quantity'] = number_format($total,2);
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

    public function chooseLocation(){

            $company_id = $this->session->userdata['company_id'];

            $data['location_id'] = $this->input->post('choose_loc');

            $data['location_name'] = $this->ItemModel->getLocationName($data['location_id'])->location_name;

            $data['all_locations'] = $this->ItemModel->getAllCompanyLocations($company_id);

            $page["active_sidebar"] = "overall_item_quantity";
            $page["page_name"] = 'Item Quantity - ' . $data['location_name'];
            $page["page_content"] = $this->load->view("inventory/items/item_quantity_by_location", $data, TRUE);
            $this->layout->inventoryTemplateTable($page);

    }

    public function ajaxGetItemQuantityByLocation()
    {
        
        $data = array();

        $location_id = $this->input->post('location_id');

        $tblColumns = array(
            0 => 'item_name'
        );

        $company_id = $this->session->userdata['company_id'];

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $where = array(
            'items_tbl.company_id' => $company_id,
            'items_tbl.is_archived' => 0
        );

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
            $tempdata  = $this->ItemModel->getItemLocationsDataAjax($where, $where_like, $limit, $start, false);
            $var_total_item_count_for_pagination = $this->ItemModel->getItemLocationsDataAjax($where, $where_like, $limit, $start, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->ItemModel->getItemLocationsDataAjaxSearch($where, $where_like, $limit, $start, $search, false);
            $var_total_item_count_for_pagination = $this->ItemModel->getItemLocationsDataAjaxSearch($where, $where_like, $limit, $start, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }
        

        if (!empty($tempdata)) {
            $i = 0;
            

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {

                $total = 0;
                    $quant = $this->ItemModel->getLocationQuantities($location_id, $value->item_id);
                    foreach($quant as $qu){
                        $total += $qu->quantity;
                    }
                        

                // set row data
                $data[$i]['item_name'] = $value->item_name;
                $data[$i]['quantity'] = number_format($total,2, '.', '');
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

    public function exportItemLocationCSV($value=''){

        $data = $this->input->get();

        $loc_id = '';

        foreach($data as $key => $val){
            $loc_id = $key;
        }

        $company_id = $this->session->userdata['company_id'];        
        
        $items = $this->ItemModel->getAllItems(array('company_id' => $company_id, 'is_archived' => 0));

        $subs = $this->ItemModel->getAllCompanySubLocationsByLocation($loc_id);
   
        if(!EMPTY($items)){
  
            $delimiter = ",";
            $filename = "item_quantity_by_location" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Item Name');

            foreach($subs as $sub){
                if(!in_array($sub->sub_location_name, $fields)){
                    array_push($fields, $sub->sub_location_name);
                }
            }

            array_push($fields, 'Total in Location');
  
            fputcsv($f, $fields, $delimiter);
  
            foreach ($items as $key => $value) {

                $total_strings = array();

                $overall = 0;
                foreach($subs as $sub){
                    $total = 0;
                    $quants = $this->ItemModel->getSubLocationQuantities($sub->sub_location_id, $value->item_id);
                    foreach($quants as $quant){
                        $total += $quant->quantity;
                        $overall += $quant->quantity;
                    }

                    $loc_str = $sub->sub_location_name . ':' . $total;
                    if(!in_array($loc_str, $total_strings)){
                        array_push($total_strings, $loc_str);
                    }
                }
  
                $lineData = array($value->item_name);

                foreach($total_strings as $tot){
                    array_push($lineData, number_format(explode(':', $tot)[1], 2));
                }

                array_push($lineData, number_format($overall, 2));

           
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
            redirect("inventory/Frontend/Items/overall_item_quantity");
        }
  
  
    }

    public function getSublocationListByLocationId(){
        $data = array();

        $loc_id = $this->input->post('location_id');

        $subs =  $this->ItemModel->getSublocationsList($loc_id);

        $data['subs'] = $subs;

        $json_data = array(
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function chooseSublocation(){

        $company_id = $this->session->userdata['company_id'];

        $data['location_id'] = $this->input->post('choose_loca');
        $data['sub_location_id'] = $this->input->post('choose_subloca');

        $data['location_name'] = $this->ItemModel->getLocationName($data['location_id'])->location_name;
        $data['sub_location_name'] = $this->ItemModel->getSubLocationName($data['sub_location_id'])->sub_location_name;

        $data['all_locations'] = $this->ItemModel->getAllCompanyLocations($company_id);

        
        $page["active_sidebar"] = "overall_item_quantity";
        $page["page_name"] = 'Item Quantity - ' . $data['location_name'] . ' - ' . $data['sub_location_name'];
        $page["page_content"] = $this->load->view("inventory/items/item_quantity_by_sublocation", $data, TRUE);
        $this->layout->inventoryTemplateTable($page);

    }

    public function ajaxGetItemQuantityBySubLocation()
    {
        

        $data = array();

        $location_id = $this->input->post('loc_id');

        $sub_location_id = $this->input->post('sub_id');

        $tblColumns = array(
            0 => 'item_name'
        );

        $company_id = $this->session->userdata['company_id'];

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $where = array(
            'items_tbl.company_id' => $company_id,
            'items_tbl.is_archived' => 0
        );

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
            $tempdata  = $this->ItemModel->getItemLocationsDataAjax($where, $where_like, $limit, $start, false);
            $var_total_item_count_for_pagination = $this->ItemModel->getItemLocationsDataAjax($where, $where_like, $limit, $start, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata  = $this->ItemModel->getItemLocationsDataAjaxSearch($where, $where_like, $limit, $start, $search, false);
            $var_total_item_count_for_pagination = $this->ItemModel->getItemLocationsDataAjaxSearch($where, $where_like, $limit, $start, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }
        

        if (!empty($tempdata)) {
            $i = 0;
            

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {

                $total = 0;
                    $quant = $this->ItemModel->getSubLocationQuantities($sub_location_id, $value->item_id);
                        $total += $quant[0]->quantity;

                // set row data
                $data[$i]['item_name'] = $value->item_name;
                $data[$i]['quantity'] = number_format($total,2);
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

    public function exportItemSubLocationCSV($value=''){

        $data = $this->input->get();

        $sub_id = '';

        foreach($data as $key => $val){
            $sub_id = explode(':', $key)[1];
        }        

        $company_id = $this->session->userdata['company_id'];        
        
        $items = $this->ItemModel->getAllItems(array('company_id' => $company_id, 'is_archived' => 0));
   
        if(!EMPTY($items)){
  
            $delimiter = ",";
            $filename = "item_quantity_by_sublocation" . date('Y-m-d') . ".csv";
            
            $f = fopen('php://memory', 'w');
            
            $fields = array('Item Name');

            array_push($fields, 'Total in Sub-Location');
  
            fputcsv($f, $fields, $delimiter);
  
            foreach ($items as $key => $value) {

                $total_strings = array();

                $overall = 0;
                
                    $quants = $this->ItemModel->getSubLocationQuantities($sub_id, $value->item_id);
                    foreach($quants as $quant){
                        $overall += $quant->quantity;
                    }                
  
                $lineData = array($value->item_name);

                array_push($lineData, number_format($overall,2));

           
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
            redirect("inventory/Frontend/Items/overall_item_quantity");
        }
  
  
    }

    public function checkItemNumberUniqueness(){
        $item_number = $this->input->post('item_number');

        $result = $this->ItemModel->getItemNumberCount($item_number);

        $json_data = array(
            "data" => $result
        );

        echo json_encode($json_data);
    }
    
}