<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require_once APPPATH . '/third_party/sms/Send_Text.php';
require FCPATH . 'vendor/autoload.php';
ini_set('memory_limit', '-1');

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Invoice;


class Estimates extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            $actual_link = $_SERVER['REQUEST_URI'];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }
        $this->load->library('parser');
        $this->load->helper('text');
        $this->loadModel();
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    private function loadModel()
    {

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
        $this->load->model('Sales_tax_model', 'SalesTax');
        $this->load->model('Basys_request_modal', 'BasysRequest');
        $this->load->helper('estimate_helper');
        $this->load->helper('invoice_helper');
        $this->load->model('Estimate_model', 'EstimateModal');
        $this->load->model('AdminTbl_coupon_model', 'CouponModel');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Property_sales_tax_model', 'PropertySalesTax');

        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('Source_model', 'SourceModel');


    }

    function mamagePaidDate()
    {
        echo "<pre>";
        $estimate_details = $this->EstimateModal->getAllEstimate(array('status' => 3));
        print_r($estimate_details);

        foreach ($estimate_details as $key => $value) {

            $wherearr = array(
                'estimate_id' => $value->estimate_id,
            );

            $updatearr = array(
                'payment_created' => $value->estimate_update,
            );


            $this->EstimateModal->updateEstimate($wherearr, $updatearr);


        }


    }


    public function index()
    {

        $page["active_sidebar"] = "estimatenav";
       	$page["page_name"] = 'Estimates';

        $page["page_content"] = $this->load->view("admin/estimate/view_estimate", array(), TRUE);
        $this->layout->superAdminInvoiceTemplateTable($page);
    }

    public function ajaxGetEstimates()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'estimate_id',
            2 => 'customer_name',
            3 => 'property_address',
            4 => 'total_cost',
            5 => 'status',
            6 => 'program_name',
            7 => 'service_name',
            8 => 'estimate_created_date',
            9 => 'user_complete_name',
            10 => 'coupon',
            11 => 'pdf_link',
            12 => 'action'
        );
        $limit = $this->input->post('length');
        if ($limit == -1) {
            $limit = 1000000;
        }
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        if($order == "service_name") $order = "jobs.job_name";
        $dir = $this->input->post('order')[0]['dir'];
        $company_id = $this->session->userdata['company_id'];
        $search_input = $this->input->post('search');
        $search_input = $search_input['value'];
        $where = array('t_estimate.company_id' => $company_id);
        $where_in = array();
        $where_like = array();
        $columns = $this->input->post('columns');

        // die(print_r($columns));


        if ($search_input !== '') {
            foreach ($columns as $column) {
                if ($column['searchable'] !== 'false') {
                    $col = $column['data'];
                    switch ($col) {
                        case 'estimate_id_url':
                            $col = 't_estimate.estimate_id';
                            $where_like[$col] = $search_input;
                            break;
                        case 'customer_name_url':
                            $col = "concat(`customers`.`first_name`,' ', `customers`.`last_name`)";
                            $where_like[$col] = $search_input;
                            break;
                        case 'user_complete_name':
                            $col = "concat(`users`.`user_first_name`,' ', `users`.`user_last_name`)";
                            $where_like[$col] = $search_input;
                            break;
                        case 'cost':
                            break;
                        case 'total_cost':
                            break;
                        case 'status_html':
                            $col = 'status';
                            $where_like[$col] = $search_input;
                            break;
                        case 'coupon_details':
                            $col = 'coupon_estimate.coupon_code';
                            $where_like[$col] = $search_input;
                            break;
                        case 'coupon':
                            $col = 'coupon_estimate.coupon_code")';
                            $where_like[$col] = $search_input;
                            break;
                        case 'service_name':
                            $col = 'jobs.job_name';
                            $where_like[$col] = $search_input;
                            break;
                        default:
                            $where_like[$col] = $search_input;
                            break;
                    }
                }
            }
        } else {
            foreach ($columns as $column) {
                $col = $column['data'];
                if ($col == 'status_html' && $column['search']['value'] != ''){


                    //die(print_r($column['search']['value']));
                    if ($column['search']['value'] != 4) {
                        $col = 't_estimate.status';
                        $where[$col] = $column['search']['value'];
                    }

                }
            }
        }


        //die(print_r($where_like));
        $data = array();
        $var_total_item_count_for_pagination = $this->EstimateModal->getAllEstimate_for_table_new($where, $where_in, $where_like, $start, $limit, $order, $dir, true);

        $estimate_details = $this->EstimateModal->getAllEstimate_for_table_new($where, $where_in, $where_like, $start, $limit, $order, $dir, false);

        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
        if (!empty($estimate_details)) {
            $i = 0;
            foreach ($estimate_details as $value) {
                $data[$i]['checkbox'] = '<input  name="group_id" type="checkbox"  value="' . $value->estimate_id . ':' . $value->customer_id . '" estimate_id="' . $value->estimate_id . '" class="myCheckBox" />';
                $data[$i]['estimate_id'] = $value->estimate_id;
                $data[$i]['estimate_id_url'] = '<a href="' . base_url('admin/Estimates/editEstimate/') . $value->estimate_id . '">' . $value->estimate_id . '</a>';
                $data[$i]['customer_id'] = $value->customer_id;
                $data[$i]['customer_name'] = $value->first_name . ' ' . $value->last_name;
//                die(print_r($value));
                $data[$i]['user_complete_name'] = ($value->user_first_name != '') ? $value->user_first_name . ' ' . $value->user_last_name : '';
                $data[$i]['customer_name_url'] = '<a href="' . base_url('admin/editCustomer/') . $value->customer_id . '">' . $value->first_name . ' ' . $value->last_name . '</a>';
                $data[$i]['property_address'] = $value->property_address;
                $data[$i]['program_name'] = $this->EstimateModal->getAllJoinedPrograms(array('estimate_id' => $value->estimate_id, 'estimate_programs.ad_hoc' => 0));
                $data[$i]['service_name'] = $this->EstimateModal->getAllJoinedPrograms(array('estimate_id' => $value->estimate_id, 'estimate_programs.ad_hoc' => 1));
                $all_program_names = $all_service_names = array();
                foreach($data[$i]['program_name'] as $p) {
                  $all_program_names[] = $p->program_name;
                }
                foreach($data[$i]['service_name'] as $s) {
                  $all_service_names[] = $s->program_name;
                }
                $data[$i]['program_name'] = implode(', ', $all_program_names);
                if($data[$i]['program_name'] == "") {
                    // they want to see the old program names - so if this was blank just use the joined program name from the old way of doing things
                    $data[$i]['program_name'] = $value->program_name;
                }
                $data[$i]['service_name'] = implode(', ', $all_service_names);
                $data[$i]['estimate_created_date'] = $value->estimate_created_date;
                $data[$i]['user_complete_name'] = $value->user_first_name . ' ' . $value->user_last_name;


                // Estimate Costs
                $line_total = 0;
                $job_details = GetOneEstimatAllJobPrice(array('estimate_id' => $value->estimate_id));

                if ($job_details) {

                    foreach ($job_details as $key2 => $value2) {

                        if ($value2['price_override'] != '' && $value2['price_override'] != 0 && $value2['is_price_override_set'] == 1) {
                            $cost = $value2['price_override'];

                        } else if ($value2['price_override'] != '' && $value2['price_override'] == 0 && $value2['is_price_override_set'] == 1) {
                            $cost = number_format(0, 2);
                        } else {

                            $priceOverrideData = getOnePriceOverrideProgramProperty(array('property_id' => $value->property_id, 'program_id' => $value->program_id));

                            if ($priceOverrideData && $priceOverrideData->price_override != 0 && $priceOverrideData->is_price_override_set == 1) {
                                $cost = $priceOverrideData->price_override;

                            } else if ($priceOverrideData && $priceOverrideData->price_override == 0 && $priceOverrideData->is_price_override_set == 1) {
                                $cost = number_format(0, 2);
                            } else {
                                //else no price overrides, then calculate job cost
                                $lawn_sqf = $value->yard_square_feet;
                                $job_price = $value2['job_price'];

                                //get property difficulty level
                                if (isset($value->difficulty_level) && $value->difficulty_level == 2) {
                                    $difficulty_multiplier = $setting_details->dlmult_2;
                                } elseif (isset($value->difficulty_level) && $value->difficulty_level == 3) {
                                    $difficulty_multiplier = $setting_details->dlmult_3;
                                } else {
                                    $difficulty_multiplier = $setting_details->dlmult_1;
                                }

                                //get base fee
                                if (isset($value2['base_fee_override'])) {
                                    $base_fee = $value2['base_fee_override'];
                                } else {
                                    $base_fee = $setting_details->base_service_fee;
                                }

                                $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                //get min. service fee
                                if (isset($value2['min_fee_override'])) {
                                    $min_fee = $value2['min_fee_override'];
                                } else {
                                    $min_fee = $setting_details->minimum_service_fee;
                                }

                                // Compare cost per sf with min service fee
                                if ($cost_per_sqf > $min_fee) {
                                    $cost = $cost_per_sqf;
                                } else {
                                    $cost = $min_fee;
                                }
                            }
                        }

                        $line_total += round($cost, 2);
                    }
                }

                // apply coupons if exists
                if ($value->coupon != 0) {
                    $value->coupon_details = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $value->estimate_id));
                    $data[$i]['coupon_details'] = $value->coupon_details;
                }
                $total_cost = $line_total;
//
                if (isset($value->coupon_details) && !empty($value->coupon_details)) {
                    foreach ($value->coupon_details as $coupon) {
                        if ($coupon->coupon_amount_calculation == 0) { // flat
                            $coupon_amm = $coupon->coupon_amount;
                        } else { // perc
                            $coupon_amm = ($coupon->coupon_amount / 100) * $total_cost;
                        }
                        $total_cost -= $coupon_amm;
                        if ($total_cost < 0) {
                            $total_cost = 0;
                        }
                    }
                }
//                echo (print_r($total_cost));
//            die('hola');
                $line_total = $total_cost;

//                $counter = 0;
//                foreach($data['estimate_details'] as $key => $estiamte_detail) {
//                    //echo $key;
//                    $estimate_id = $estiamte_detail->estimate_id;

//                }

                // apply sales tax
                $line_tax_amount = 0;
                if ($setting_details->is_sales_tax == 1) {
                    $sales_tax_details = getAllSalesTaxByProperty($value->property_id);

                    if ($sales_tax_details) {
                        foreach ($sales_tax_details as $property_sales_tax) {
                            $line_tax_amount += $line_total * $property_sales_tax->tax_value / 100;
                        }
                    }
                    $line_total += $line_tax_amount;
                }

                $data[$i]['cost_unformated'] = $line_total;
                $data[$i]['cost'] = number_format(($line_total), 2);
                // end Estimate Costs

                $data[$i]['status'] = $value->status;
                $data[$i]['status_html'] = '';

                $bg = "";
                
                switch ($value->status) {
                    case 0:
                        $data[$i]['status_html'] = '<span  class="label label-warning myspan">Draft</span>';
                        $bg = 'bg-warning';
                        break;
                    case 1:
                        $data[$i]['status_html'] = '<span  class="label label-danger myspan">Sent</span>';
                        $bg = 'bg-danger';
                        break;

                    case 2:
                        $data[$i]['status_html'] = '<span  class="label label-till myspan">Accepted</span>';
                        $bg = 'bg-till';
                        break;
                    case 5:
                        $data[$i]['status_html'] = '<span  class="label label-orange myspan">Declined</span>';
                        $bg = 'bg-orange';
                        break;
                }

                // Estimate change status dropdown
                $data[$i]['status_html'] .= '<div class="btn-group">
                                        <a href="#" class="label ' . $bg . ' dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                                        <ul class="dropdown-menu dropdown-menu-right" >
                                            <li class="changestatus"  estimate_id="'. $value->estimate_id .'" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Draft</a></li>
                                            <li class="changestatus" estimate_id="'.$value->estimate_id .'" value="1" ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>
                                            <li class="changestatus" estimate_id="'.$value->estimate_id .'" value="2" ><a href="#"><span class="status-mark bg-till position-left"></span> Accepted</a></li>
                                            <li class="changestatus" estimate_id="'.$value->estimate_id .'" value="5" ><a href="#"><span class="status-mark bg-orange position-left"></span> Decline</a></li>

                                        </ul>
                                    </div>';
                // end Estimate change status dropdown
                // Coupon Span
                $coupons_span = '';

                //$value->coupon_details = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $value->estimate_id));

                if ($value->coupon != 0) {


                    $value->coupon_details = substr($value->coupon_name, 0, strlen($value->coupon_name) - 2);

                } else {
                    $value->coupon_details = "";
                }

                $data[$i]['coupon_details'] = $value->coupon_details;
                // end Coupon Span
                // PDF section
                $data[$i]['action'] = '<ul style="list-style-type: none; padding-left: 0px;">

                                        <li style="display: inline; padding-right: 10px;">
                                            <a  class="email button-next" id="' . $value->estimate_id . '"  customer_id="' . $value->customer_id . '"    ><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a>
                                        </li>


                                        <li style="display: inline; padding-right: 10px;">
                                            <a  onclick="pdf_signwell(' . $value->estimate_id . ')"  class=" button-next "><i class=" icon-file-pdf position-center" style="color: #9a9797;"></i></a>
                                        </li>
                                        <li style="display: inline; padding-right: 10px;">
                                            <a href="' . base_url('admin/Estimates/printEstimate/') . $value->estimate_id . '" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a>
                                        </li>
                                    </ul>';
                // end PDF section
                $i++;
            }
        }

        //die($order);
        if ($order == 'total_cost') {

            $key_values = array_column($data, 'cost_unformated');

            //die(print_r( $key_values));
            array_multisort($key_values, ($dir == 'asc') ? SORT_ASC : SORT_DESC, SORT_NUMERIC, $data);

        }


        // die(print_r($data));

        $json_data = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $var_total_item_count_for_pagination, // "(filtered from __ total entries)"
            "recordsFiltered" => $var_total_item_count_for_pagination, // actual total that determines page counts
            "data" => $data
        );
        echo json_encode($json_data);
    }

    public function get_signwell_status()
    {

        $estimate_details = $this->EstimateModal->getAllEstimate_for_table(array('t_estimate.estimate_id' => $_POST['estimate_id']))[0];
        $signwell_object = '';

        $where = array('company_id' => $this->session->userdata['company_id']);
        $setting_details = $this->CompanyModel->getOneCompany($where);
        if ($estimate_details->signwell_completed == 0) {

            if ($estimate_details->signwell_id != "") {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://www.signwell.com/api/v1/documents/" . $estimate_details->signwell_id . "/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "X-Api-Key: " . $setting_details->signwell_api_key,
                        "accept: application/json"
                    ],
                ]);

                $response = curl_exec($curl);
                // die(print_r($response));
                $err = curl_error($curl);

                curl_close($curl);
                $signwell_object = json_decode($response);
            } else {
                $signwell_object = new stdClass();
            }

            if (isset($signwell_object->status) && $signwell_object->status == "Completed") {
                // if the document is finished we can get a link for the PDF showing the signature
                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://www.signwell.com/api/v1/documents/" . $estimate_details->signwell_id . "/completed_pdf/?url_only=true",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "X-Api-Key: " . $setting_details->signwell_api_key,
                        "accept: application/json"
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);
                $completed_pdf_object = json_decode($response);
                $signwell_url = $completed_pdf_object->file_url;

                //Save signwell data to estimate table.
                $wherearr = array(
                    'estimate_id' => $estimate_id,
                );
                $updatearr = array(
                    'signwell_completed' => 1,
                    'signwell_url' => $estimate_details->signwell_url
                );

                $this->EstimateModal->updateEstimate($wherearr, $updatearr);
            } else {
                $signwell_url = base_url('admin/Estimates/pdfEstimate/') . $estimate_details->estimate_id;
            }
            echo $signwell_url;
            die();
        } else {
            echo $estimate_details->signwell_url;
            die();
        }
    }

    public function dataCalculate($res)
    {
        $where = array('company_id' => $this->session->userdata['company_id']);
        $setting_details = $this->CompanyModel->getOneCompany($where);

        if ($res) {
            //die(print_r($res));
            $line_total = 0;
            foreach ($res as $key => $value) {


                if ($value['price_override'] != 0) {
                    $cost = $value['price_override'];
                } else {

                    $priceOverrideData = getOnePriceOverrideProgramProperty(array('property_id' => $value['property_id'], 'program_id' => $value['program_id']));

                    if ($priceOverrideData && $priceOverrideData->price_override != 0) {
                        // $price = $priceOverrideData->price_override;
                        $cost = $priceOverrideData->price_override;

                        // die(print_r($cost));

                    } else {
                        //else no price overrides, then calculate job cost
                        $lawn_sqf = $value['yard_square_feet'];
                        $job_price = $value['job_price'];

                        //get property difficulty level
                        if (isset($value['difficulty_level']) && $value['difficulty_level'] == 2) {
                            $difficulty_multiplier = $setting_details->dlmult_2;
                        } elseif (isset($value['difficulty_level']) && $value['difficulty_level'] == 3) {
                            $difficulty_multiplier = $setting_details->dlmult_3;
                        } else {
                            $difficulty_multiplier = $setting_details->dlmult_1;
                        }

                        //get base fee
                        if (isset($value['base_fee_override'])) {
                            $base_fee = $value['base_fee_override'];
                        } else {
                            $base_fee = $setting_details->base_service_fee;
                        }

                        $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                        //get min. service fee
                        if (isset($value['min_fee_override'])) {
                            $min_fee = $value['min_fee_override'];
                        } else {
                            $min_fee = $setting_details->minimum_service_fee;
                        }

                        // Compare cost per sf with min service fee
                        if ($cost_per_sqf > $min_fee) {
                            $cost = $cost_per_sqf;
                        } else {
                            $cost = $min_fee;
                        }

                    }
                }


                $line_tax_amount = 0;
                if ($setting_details->is_sales_tax == 1) {

                    $sales_tax_details = getAllSalesTaxByProperty($value['property_id']);

                    if ($sales_tax_details) {
                        foreach ($sales_tax_details as $property_sales_tax) {
                            //   echo $property_sales_tax->tax_name. ' ('.$property_sales_tax->tax_value.'%)<br>';
                            // echo $cost * $property_sales_tax->tax_value /100 . '<br>';
                            $line_tax_amount += $cost * $property_sales_tax->tax_value / 100;

                        }

                    }

                }


                $line_total += $line_tax_amount + $cost;

            }
        } else {
            $line_total = 0;
        }

        return $line_total;


    }

    public function addServiceEstimate()
    {

        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['customer_details'] = $this->CustomerModel->get_all_customer($where);

        $data['service_details'] = $this->JobModel->getJobList($where);
        $data['propertylist'] = $this->CustomerModel->getPropertyList(array('company_id' => $this->session->userdata['company_id'], 'property_status' => 1));
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
        ##### ADDED 2/24/22 (RG) #####
        // $data['all_users'] = $this->SourceModel->getAllSource($where);
        $data['source_list'] = $this->SourceModel->getAllSource($where);
        $data['users'] = $this->Administrator->getAllAdmin($where);
        // die(print_r($data['users']));
        // $data['sources'] = array_merge($data['source_list'], $data['users']);
        $source = [];
        foreach ($data['users'] as $user) {
            $source = (object)array(
                'source_name' => $user->user_first_name . ' ' . $user->user_last_name,
                'user_id' => $user->user_id,
                'source_id' => $user->id,
            );
            array_push($data['source_list'], $source);
        }
        // die(print_r($data['source_list']));
        ####

        $coupon_where = array(
            'company_id' => $this->session->userdata['company_id'],
            'type' => 0
        );
        $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);

        $page["active_sidebar"] = "estimatenav";
        $page["page_name"] = 'Add Service Estimate';
        $page["page_content"] = $this->load->view("admin/estimate/add_service_estimate", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function getAllServicesByProgramServerSide($program_id = '')
    {
        $property_details = $this->ProgramModel->getProgramAssignJobs(array('program_id' => $program_id));
        return $property_details;
    }

    /** Can be removed in future updates. Only being used for Reference **/
    // public function addEstimateOld() {

    //   $where = array('company_id' =>$this->session->userdata['company_id']);
    //   $data['customer_details'] = $this->CustomerModel->get_all_customer($where);
    //   $data['program_details'] = $this->ProgramModel->get_all_program($where);
    //   $data['selectedprogramlist'] = array();
    //   $data['propertylist'] = $this->CustomerModel->getPropertyList($where);
    //   $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
    //   $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
    //   $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
    //   $data['service_details'] = $this->JobModel->getJobList($where);
    //   $data['programlist'] = $this->PropertyModel->getProgramList($where);
    //   $data['selectedjoblist'] = array();
    //   $data['program_job_assign'] = $this->ProgramModel->getProgramJobAssign();
    //   $data['program_details_ext'] = array();


    //   foreach($data['program_details'] as $program)
    //   {
    //     $program_jobs = $this->getAllServicesByProgramServerSide($program->program_id);
    //     $program->program_jobs = $program_jobs;
    //     array_push($data['program_details_ext'],$program);
    //   }

    //   $coupon_where = array(
    //       'company_id' => $this->session->userdata['company_id'],
    //       'type' => 0
    //   );
    //   $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);
    //   $page["active_sidebar"] = "estimatenav";
    // 	$page["page_name"] = 'Add Program Estimate';
    //   $page["page_content"] = $this->load->view("admin/estimate/add_estimate_old",$data, TRUE);
    //   $this->layout->superAdminTemplateTable($page);
    // }


    /**
     * add estimate function
     *
     * @param $customer_id integer specific estimation for customer if any
     * @return void
     */
    public function addEstimate()
    {
        // used as a trigger to select all properties of specific customer
        $customer_id = $this->input->get('customer_id') ?? 0;
        $data['customer_id_chosen'] = $customer_id;

        $where = array('company_id' => $this->session->userdata['company_id']);
        $whereSpecial = array('property_tbl.company_id' => $this->session->userdata['company_id'], 'property_status !=' => 0);
        $data['propertylist'] = $this->CustomerModel->getAllpropertyExt($whereSpecial, $customer_id);
        $oldPropList = $this->CustomerModel->getAllproperty($where);
        $data['customer_details'] = $this->CustomerModel->get_all_customer($where);
        $data['program_details'] = $this->ProgramModel->get_all_program(array('company_id' => $this->session->userdata['company_id'], 'program_active' => 1, 'ad_hoc' => 0));
        foreach ($data['program_details'] as $key => $val) {
            if (strstr($val->program_name, '-Standalone Service')) {
                unset($data['program_details'][$key]);
            } else if (strstr($val->program_name, '- One Time Project Invoicing') && strstr($val->program_name, '+')) {
                unset($data['program_details'][$key]);
            } else if (strstr($val->program_name, '- Invoiced at Job Completion') && strstr($val->program_name, '+')) {
                unset($data['program_details'][$key]);
            } else if (strstr($val->program_name, '- Manual Billing') && strstr($val->program_name, '+')) {
                unset($data['program_details'][$key]);
            }
        }
        $data['selectedprogramlist'] = array();
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
        $data['service_details'] = $this->JobModel->getJobList($where);
        $data['programlist'] = $this->PropertyModel->getProgramList(array('company_id' => $this->session->userdata['company_id'], 'program_active' => 1, 'ad_hoc' => 0));
        foreach ($data['programlist'] as $key => $val) {
            if (strstr($val->program_name, '-Standalone Service')) {
                unset($data['programlist'][$key]);
            } else if (strstr($val->program_name, '- One Time Project Invoicing') && strstr($val->program_name, '+')) {
                unset($data['programlist'][$key]);
            } else if (strstr($val->program_name, '- Invoiced at Job Completion') && strstr($val->program_name, '+')) {
                unset($data['programlist'][$key]);
            } else if (strstr($val->program_name, '- Manual Billing') && strstr($val->program_name, '+')) {
                unset($data['programlist'][$key]);
            }
        }
        $data['selectedjoblist'] = array();
        $data['program_job_assign'] = $this->ProgramModel->getProgramJobAssign();

        ##### ADDED 2/24/22 (RG) #####
        // $data['all_users'] = $this->SourceModel->getAllSource($where);
        $data['source_list'] = $this->SourceModel->getAllSource($where);
        $data['users'] = $this->Administrator->getAllAdmin($where);
        // die(print_r($data['users']));
        // $data['sources'] = array_merge($data['source_list'], $data['users']);
        $source = [];
        foreach ($data['users'] as $user) {
            $source = (object)array(
                'source_name' => $user->user_first_name . ' ' . $user->user_last_name,
                'user_id' => $user->user_id,
                'source_id' => $user->id,
            );
            array_push($data['source_list'], $source);
        }
        // die(print_r($data['source_list']));
        ####


			
      $data['program_details_ext'] = array();
      foreach($data['program_details'] as $program)
      {
        $program_jobs = $this->getAllServicesByProgramServerSide($program->program_id);
        $program->program_jobs = $program_jobs;
        array_push($data['program_details_ext'],$program);
      }

      $coupon_where = array(
          'company_id' => $this->session->userdata['company_id'],
          'type' => 0
      );
      $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);
      $page["active_sidebar"] = "estimatenav";
  		$page["page_name"] = 'Add Program Estimate';
      $page["page_content"] = $this->load->view("admin/estimate/add_estimate",$data, TRUE);
      $this->layout->superAdminTemplateTable($page);
    }

    public function GetAllCustomerByProperty($property_id) {
     $customers   =  $this->CustomerModel->getAllCustomerByPropert(array('property_id' =>$property_id));
     $property_details = $this->PropertyModel->getPropertyDetail($property_id);
     if ($customers) {

      $return_result = array('status'=>200,'msg'=>'successfully','result'=>$customers,'property_details'=>$property_details);               
     
     } else {
      
      $return_result = array('status'=>400,'msg'=>'Faild','result'=>array(),'property_details'=>$property_details);               
     }
     echo json_encode($return_result);
  }


public function getPropertyByCustomerID() {

    $customer_id = $this->input->post('customer_id');
    $data = $this->CustomerModel->getAllproperty(array('customer_id' =>$customer_id));

    if (!empty($data)) {
       echo '<option value="">Select any property</option>';
        foreach ($data as $value) {
            echo '<option value="'.$value->property_id.'">'.$value->property_address.'</option>';
    }
   
    } else {
        echo '<option value="">No property assign</option>';
    }
}
public function addBulkEstimateData($data = null){

  $backendCall = (isset($data)) ? true : false;
  $tmpData = (isset($data)) ? $data : $this->input->post();
  // die(print_r($tmpData));
  $company_id = $this->session->userdata['company_id'];
  $user_id = $this->session->userdata['user_id'];
  $tmpData['company_id'] = $company_id;
  $tmpData['user_id'] = $user_id;
  // $test = $this->ProgramModel->getSelectedJobsAnother('1038');
  // Parse the JSON strings
  $property_array = json_decode($tmpData['property_data_array']);
  $listarray = json_decode($tmpData['listarray']);
  $programs = $listarray->programs;
  // going to need all the program_ids from these programs as well as the new ones we are going to make below - so start that array here
  $program_ids_for_join_table = $job_ids_for_program_join = array();
  foreach($programs as $pro) {
    $program_ids_for_join_table[$pro->program_id] = 0;
  }
  $services = $listarray->services; 
  $priceoverridearray = (is_array($tmpData['priceoverridearray'])) ? $tmpData['priceoverridearray'] : json_decode($tmpData['priceoverridearray']);

  $unmodifiedProgram = false;
  
    $program_names = (array)[];
    $service_names = (array)[];
    foreach($listarray->programs as $p){
        $r = $this->ProgramModel->getProgramDetail($p->program_id)['program_name'];
        $r = trim(explode('-',$r)[0]);
        array_push($program_names, $r);
    }
    foreach($listarray->services as $s){
        $r = $this->JobModel->getOneJob(array('job_id' => $s->job_id))->job_name;
        array_push($service_names, $r);
    }

    $program_price = $tmpData['program_price'];
  
  $or = (array)[];
  $price_overrides = (array)[];
  foreach ($priceoverridearray as $ovr){
    $tmp = (object)[];
    $tmp->propertyId = $ovr->propertyId;
    $tmp->price_override = $ovr->price_override;
    $tmp->program_jobs = $ovr->jobIds;
    array_push($or,$tmp);
  }
  //die(print_r(json_encode($or)));
  foreach($or as $o){
    $tmp = (object)[];
    $tmp->propertyId = $o->propertyId;
    for($i=0; $i<count($o->price_override); $i++){
      $tmp = (object)[];
      $tmp->propertyId = $o->propertyId;
      $tmp->job_id = $o->program_jobs[$i];
      $tmp->price_override = ($o->price_override[$i] != '') ? $o->price_override[$i] : null;
      $tmp->is_price_override_set = ($tmp->price_override != '') ? 1 : null;
      array_push($price_overrides, $tmp);
    }
  }
  // die(print_r(json_encode($price_overrides)));
  $jobsAll = $job_id_to_program = array();
  //var_dump($programs);
  foreach($programs as $program){
    $jobsAll = array_unique(array_merge($jobsAll,$program->program_jobs));
    foreach($program->program_jobs as $p) {
        $job_id_to_program[$p] = $program->program_id;
    }
  }
    
  foreach($services as $service){
    $this_service_info = $this->JobModel->getOneJob(array('job_id' => $service->job_id));
    $programData = array();
    $programData['company_id'] = $company_id;
    $programData['user_id'] = $user_id;
    $programData['program_name'] = $this_service_info->job_name;
    $programData['jobs_all'] = array($this_service_info->job_id);
    $programData['program_price'] = $program_price;  
    $programResults = $this->createModifiedBundledProgram($programData);
    $job_id_to_program[$this_service_info->job_id] = $programResults["program_id"];
    $program_ids_for_join_table[$programResults["program_id"]] = 1;
    $job_ids_for_program_join[$programResults["program_id"]] = $this_service_info->job_id;
    $jobsAll = array_unique(array_merge($jobsAll,array($service->job_id)));
  }
  $data = (array)[];

  foreach($property_array as $property){
    $tmp = json_decode(json_encode(clone $property), true);
    $tmp['estimate_date'] = $tmpData['estimate_date'];
    $tmp['estimate_date_submit'] = $tmpData['estimate_date_submit'];
    $tmp['status'] = $tmpData['status'];
    $tmp["signwell_status"] = $tmpData["signwell_status"];
    $tmp['notes'] = $tmpData['notes'];
    $tmp['email_notes'] = $tmpData['email_notes'];
    $tmp["source"] = $tmpData["source"];
    $tmp["program_pricing"] = $tmpData["program_pricing"];
    // $tmp['property_status'] = $tmpData['property_status'];
    $tmp['sales_rep'] = $tmpData['sales_rep'];
    //$tmp['program_id'] = $programResults['program_id'];
    if (array_key_exists("assign_onetime_coupons",$tmpData)){
      $tmp['assign_onetime_coupons'] = $tmpData['assign_onetime_coupons'];
    }
    $tmp['joblistarray'] = (array)[];
    $jobs = $jobsAll;
    foreach($jobs as $job){
      $tmpJob = (object)[];
      $tmpJob->job_id = $job;
      foreach($price_overrides as $price_override){
        if(isset($price_override->propertyId)){
          if($tmp['property_id'] == $price_override->propertyId && $tmpJob->job_id == $price_override->job_id)
          {
            $tmpJob->price_override = $price_override->price_override;
            $tmpJob->is_price_override_set = 1;
          }
        }
      }
      $tmpJob->is_price_override_set = (isset($tmpJob->price_override)) ? $tmpJob->is_price_override_set : null;
        $tmpJob->price_override = (isset($tmpJob->price_override)) ? $tmpJob->price_override : "";
      array_push($tmp['joblistarray'], $tmpJob);
    }
    /* --> Insert Property Program Job Price Overrides Function HERE <-- */
    $priceOverrideResults = (array)[];
    foreach($tmp['joblistarray'] as $jobOverride){
      $arr = array(
        'program_id' => $job_id_to_program[$jobOverride->job_id],
        'job_id' => $jobOverride->job_id,
        'property_id' => $tmp['property_id'],
        'price_override' => $jobOverride->price_override,
        'is_price_override_set' => $jobOverride->is_price_override_set
      );
      $result = $this->ProgramModel->insert_price_override($arr);
      array_push($priceOverrideResults, $result);
    }
    $tmp['joblistarray'] = json_encode($tmp['joblistarray']);
    array_push($data, $tmp);
    // die(print_r(json_encode($priceOverrideResults)));
  }

  // die(print_r($data));
  // die(print_r(json_encode($data)));
  $return_messages = (array)[];
  foreach($data as $submission){
        $estimate_response = $this->addEstimateData($submission, true, $job_id_to_program,$program_ids_for_join_table);
        $message = $estimate_response['message'];
        //@TODO
        //here's where we need to stop creating new programs, 
        //instead get program ids and jobs ids for this estimate
        //
        $this->EstimateModal->CreateEstimatePrograms($program_ids_for_join_table,$estimate_response['estimate_id'], $job_ids_for_program_join);
        //$this->EstimateModal->CreateEstimateJobs($listarray->services,$estimate_response['estimate_id']);
        array_push($return_messages, $message);
    
  }
  if($backendCall == true){
    return $return_messages;
  } else {
    $success_count = 0;
    $fail_count = 0;
    $other_count = 0;
    foreach($return_messages as $return_message){
      if($return_message == '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>'){
        $success_count++;
      } elseif($return_message == '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>'){
        $fail_count++;
      } elseif($return_message != '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>'
          && $return_message != '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>'){
        $other_count++;
      }
    }

    $success_message = '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>'.$success_count.' Estimate(s) </strong>created successfully</div>';
    $fail_message = '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>'.$fail_count.' Estimate(s) </strong>were not added. Please review submissions and try again</div>';

    $final_message = '';

    if($other_count > 0){
      $final_message = implode("", $return_messages);
    } else {
      if($success_count > 0 && $fail_count > 0){
        $final_message = $success_message.'<br>'.$fail_message;
      } elseif ($success_count > 0 && $fail_count <= 0){
        $final_message = $success_message;
      } elseif ($success_count <= 0 && $fail_count > 0){
        $final_message = $fail_message;
      }
    }
    $this->session->set_flashdata('message', $final_message);
    redirect("admin/Estimates");
  }
}

//////



// Create New Modified / Bundled Programs for Estimates
// Destructures multiple programs and services and rebundles -
// them into a single modified program for issuing a new estimate
    public function createModifiedBundledProgram($data)
    {

        //create new ad_hoc program based on selected program id
        $newProgram = array(
            'user_id' => $data['user_id'],
            'company_id' => $data['company_id'],
            'program_name' => $data['program_name'],
            'program_price' => $data['program_price'],
            'ad_hoc' => 1
        );

        $program_id = $this->ProgramModel->insert_program($newProgram);

  $program_jobs = $data['jobs_all'];
  foreach($program_jobs as $pj) {
    //Assign jobs to program
    $programJob = array(
        'program_id' => $program_id,
        'job_id' => $pj,
        'priority' =>1
    );
    $programJobAssignResult = $this->ProgramModel->assignProgramJobs($programJob);
  }

  $returnData = array(
    'program_id' => $program_id,
    'programjob_assign_result' => $programJobAssignResult
  );
// die(print_r($returnData));
  return $returnData;
}

public function addEstimateData($data = null, $bulk_call = false, $job_id_to_program = array(), $programs_added_to_estimate = array()) {
  if(!isset($data)){
    $this->form_validation->set_rules('customer_id', 'Customer', 'required');
    $this->form_validation->set_rules('property_id', 'Property', 'required');
    $this->form_validation->set_rules('customer_email', 'Customer Email', 'trim');
    $this->form_validation->set_rules('estimate_date', 'Estimate Date', 'required');
    //$this->form_validation->set_rules('program_id', 'Program', 'required');
    $this->form_validation->set_rules('joblistarray', 'job required', 'trim');
    $this->form_validation->set_rules('notes', 'notes', 'trim');
    $this->form_validation->set_rules('email_notes', 'email_notes', 'trim');
  }
  if (!isset($data) && $this->form_validation->run() == FALSE){
    // echo validation_errors();
    $this->addEstimate();
  } else {
    // die(print_r($data));
    $data = (isset($data)) ? $data : $this->input->post();

    $company_id = $this->session->userdata['company_id'];
    $user_id = $this->session->userdata['user_id'];
    $where = array('company_id' =>$this->session->userdata['company_id']);
    // $customer_id = $this->Customer->getOnecustomerPropert(array('property_id' => $data['property_id']));
    // die(print_r(json_encode($data)));
    // die(print_r($data));
        // die('through');
    //$program_id = $data['program_id'];
    //check for standalone service
    // if(isset($data['standalone_job_id']) && $data['standalone_job_id'] > 0){
    //   //get program details
    //   $program_details = $this->ProgramModel->getProgramDetail($data['program_id']);
    //   $program_jobs = $this->ProgramModel->getSelectedJobs($data['program_id']);
    
    //   //create new ad_hoc program based on selected program id
    //     $newProgram = array(
    //       'user_id' => $user_id,
    //       'company_id' => $company_id,
    //       'program_name' => $program_details['program_name'],
    //       'program_price' => $program_details['program_price'],
    //   );
    //   // die(print_r(json_encode($program_details)));
    //   $program_id = $this->ProgramModel->insert_program($newProgram);


    //   //Assign jobs to program
    //   foreach($program_jobs as $program_job){
    //     $programJob = array(
    //       'program_id' => $program_id,
    //       'job_id' => $program_job->job_id,
    //       'priority' =>1
    //     ); 
    //     $programJobAssignResult = $this->ProgramModel->assignProgramJobs($programJob); 
    //   }
    //   $programJob2 = array(
    //       'program_id' => $program_id,
    //       'job_id' => $data['standalone_job_id'],
    //       'priority' =>1
    //   ); 
    //   $programJobAssignResult2 = $this->ProgramModel->assignProgramJobs($programJob2);
    // }


    // we need to see if there is already an estimate for this person with this program and if there is we do not create a new estimate
    // get all estimates that are tied to this customer
    $estimate_ids_for_this_customer = $this->EstimateModal->getAllNotAcceptedEstimateIdsByCustomer(array("customer_id"=>$data['customer_id']));
    $estimate_ids_to_check = array();
    $all_new_programs = true;
    foreach($estimate_ids_for_this_customer as $eif) {
        $estimate_ids_to_check[] = $eif->estimate_id;
    }
    if(!empty($estimate_ids_to_check)) {
        $programs_on_estimate_for_customer = $this->EstimateModal->getAllJoinedProgramsForAllEstimates($estimate_ids_to_check);
        $programs_already_on_estimates = array();
        foreach($programs_on_estimate_for_customer as $poe) {
            $programs_already_on_estimates[] = $poe->program_id;
        }
        foreach($programs_already_on_estimates as $paoe) {
            if(in_array($paoe, array_keys($programs_added_to_estimate))) {
                $all_new_programs = false;
                $estimate_id = false;
            }
        }
    }
    if($all_new_programs == true) {
        $param = array(
            'company_id' => $company_id,
            'customer_id' => $data['customer_id'],
            'property_id' => $data['property_id'],
            'estimate_date' => $data['estimate_date'],
            //'program_id' => $program_id,
            'status' => $data['status'],
            // 'property_status' => $data['property_status'],
            'sales_rep' => $data['sales_rep'],
            'estimate_created_date' => date("Y-m-d H:i:s"),
            'estimate_update' => date("Y-m-d H:i:s"),
            'notes' => $data['notes'],
            'source' => $data['source'],
            'signwell_status' => $data['signwell_status'],
            'program_pricing' => $data['program_pricing'],
        );
        $estimate_id = $this->EstimateModal->CreateOneEstimate($param);
    }

    if($estimate_id){
        if(isset($data['joblistarray']) && !empty($data['joblistarray'])) {
            foreach (json_decode($data['joblistarray']) as $value) {
                $param3 = array(
                    'estimate_id' => $estimate_id,
                    'customer_id' => $data['customer_id'],
                    'property_id' => $data['property_id'],
                    'program_id' => $job_id_to_program[$value->job_id],
                    'job_id' => $value->job_id,
                    'price_override' => $value->price_override,
                    'is_price_override_set' => $value->is_price_override_set,
                    'created_at' => date("Y-m-d H:i:s")
                );
                $this->EstimateModal->CreateOneEstimatePriceOverRide($param3);
            }
        }

        // apply assigned coupons
        if (array_key_exists("assign_onetime_coupons",$data)) {
            $coupon_ids_arr = $data['assign_onetime_coupons'];
            foreach($coupon_ids_arr as $coupon_id) {
                $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                $params = array(
                    'coupon_id' => $coupon_id,
                    'estimate_id' => $estimate_id,
                    'coupon_code' => $coupon_details->code,
                    'coupon_amount' => $coupon_details->amount,
                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                    'coupon_type' => $coupon_details->type,
                    'expiration_date' => $coupon_details->expiration_date
                );
                $this->CouponModel->CreateOneCouponEstimate($params);
            }
        }
        // check global coupons & assign if so
        $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $data['customer_id']));
        if (!empty($coupon_customers)) {
            foreach($coupon_customers as $coupon_customer) {
                $coupon_id = $coupon_customer->coupon_id;
                $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                $params = array(
                    'coupon_id' => $coupon_id,
                    'estimate_id' => $estimate_id,
                    'coupon_code' => $coupon_details->code,
                    'coupon_amount' => $coupon_details->amount,
                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                    'coupon_type' => $coupon_details->type,
                    'expiration_date' => $coupon_details->expiration_date
                );
                $this->CouponModel->CreateOneCouponEstimate($params);
            }
        }

        ##### Creating an internal note #####
        $dataNotes = array(
            'note_property_id' => $data['property_id'],
            'note_category' => 0,
            'note_type' => 4,
            'note_assigned_user' => $data['sales_rep'],
            'note_due_date' => date("Y-m-d H:i:s"),
            'note_due_date_submit' => date("Y-m-d H:i:s"),
            'include_in_tech_view' => 1,
            'note_contents' => 'Sales Call has been assigned to you.',
        );
        $this->createNote($dataNotes);

        $response_object = json_decode('');
        if ($data['status']==1 && $data['customer_email']!='' ){
            // if are are in here then we also need to check for signwell being set - if its set we dont want to send the email this way but instead send it through signwell
            $company_id = $this->session->userdata['company_id'];
            $customer_id  = $data['customer_id'];
            $email_data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
            $where_company = array('company_id' =>$company_id);
            $email_data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
            if($data["signwell_status"] == "1") {
                $pdf_link_for_signwell = base_url('welcome/pdfEstimateSignWell/').base64_encode($estimate_id);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.signwell.com/api/v1/documents/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                                "test_mode": '.SIGNWELL_TEST_MODE.',
                                "name": "estimate_'.$estimate_id.'",
                                "files": [
                                    {
                                        "name": "estimate_' . $estimate_id . '.pdf",
                                        "file_url": "' . $pdf_link_for_signwell . '"
                                    }
                                ],
                                "recipients": [
                                    {
                                        "send_email": false,
                                        "id": "1",
                                        "name": "' . $email_data['customer_details']->first_name . ' ' . $email_data['customer_details']->last_name . '",
                                        "email": "' . $email_data['customer_details']->email . '"
                                    }
                                ],
                                "draft": false,
                                "reminders": true,
                                "apply_signing_order": false,
                                "embedded_signing": false,
                                "embedded_signing_notifications": false,
                                "text_tags": true,
                                "allow_decline": true,
                                "redirect_url": "'.base_url('welcome/set_signwell_estimate_accepted/'.$estimate_id).'",
                                "decline_redirect_url": "'.base_url('welcome/set_signwell_estimate_rejected/'.$estimate_id).'",
                                "message": "'.nl2br($data['email_notes']).'"
                            }',
                                CURLOPT_HTTPHEADER => array(
                                    'accept: application/json',
                                    'content-type: application/json',
                                    'X-Api-Key: ' . $email_data['setting_details']->signwell_api_key
                                ),
                            ));

                $response = curl_exec($curl);
                
                curl_close($curl);
                $response_object = json_decode($response);
                if($response_object->message == "") {
                    // we should now have an ID for this document within SignWell - need to save that to the estimate in the DB
                    $this->EstimateModal->updateEstimateSignWellID($estimate_id, $response_object->id);
                }
            } else {
                if(isset($data['email_notes']) && $data['email_notes'] != ''){
                    $email_data['msgtext'] = $data['email_notes'];
                }else{
                    $email_data['msgtext'] = '';
                }
                // $data['company_details'] = $this->CompanyModel->getOneCompany($where_company);
                $email_data['link'] =  base_url('welcome/pdfEstimate/').base64_encode($estimate_id);
                $email_data['link_acc'] =  base_url('welcome/estimateAccept/').base64_encode($estimate_id);
                $email_data['setting_details']->company_logo = ($email_data['setting_details']->company_resized_logo != '') ? $email_data['setting_details']->company_resized_logo : $email_data['setting_details']->company_logo;
                $body = $this->load->view('admin/estimate/estimate_email',$email_data,true);
                $where_company['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
                if (!$company_email_details) {
                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                }
                $res =   Send_Mail_dynamic($company_email_details, $email_data['customer_details']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Details',$email_data['customer_details']->secondary_email);

                            if ($data['sales_rep'] != '') {
///
                    $rep_data['sale_rep'] = $this->Administrator->getOneAdmin(array('id' => $data['sales_rep']));
                    // die(print_r($rep_data));
                    $body = $this->load->view('admin/estimate/assigned_email',$rep_data,true);
                    $rep =   Send_Mail_dynamic($company_email_details, $rep_data['sale_rep']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Assigned',$email_data['customer_details']->secondary_email);
                    // die(print_r($rep));
                }
            } 
        }

        if($bulk_call) {
            if(is_object($response_object) && $response_object->status != "Created" && (isset($response_object->message) && $response_object->message != "")) {
                // this means that the SignWell api got an error and nothing got sent over to them
                return array("message"=>'<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully in Spraye but not at SignWell. (SignWell error message: '.$response_object->message.')</div>', "estimate_id"=>$estimate_id);
            } else {
                return array("message"=>'<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>', "estimate_id"=>$estimate_id);
            }
        } else {
            if(is_object($response_object) && $response_object->status != "Created" && (isset($response_object->message) && $response_object->message != "")) {
                // this means that the SignWell api got an error and nothing got sent over to them
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully in Spraye but not at SignWell. (SignWell error message: '.$response_object->message.')</div>');
                redirect("admin/Estimates");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>');
                redirect("admin/Estimates");
            }
        }
    } else {
        if($bulk_call) {
            return array("message"=>'<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>', "estimate_id"=>$estimate_id);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>');
            redirect("admin/Estimates");
        }
    }            
  }
}


  public function addServiceEstimateData($data = null, $bulk_call = false) {
    // die(print_r($data));
    if(!isset($data)){
      $this->form_validation->set_rules('customer_id', 'Customer', 'required');
      $this->form_validation->set_rules('property_id', 'Property', 'required');
      $this->form_validation->set_rules('customer_email', 'Customer Email', 'trim');
      $this->form_validation->set_rules('estimate_date', 'Estimate Date', 'required');
      $this->form_validation->set_rules('standalone_job_id', 'Service', 'required');
      $this->form_validation->set_rules('program_price', 'Pricing', 'required');
      $this->form_validation->set_rules('notes', 'notes', 'trim');
    }

    if (!isset($data) && $this->form_validation->run() == FALSE) {
      $this->addServiceEstimate();
    } else {

      $data = (isset($data)) ? $data : $this->input->post();
      // die('here it is');
      // die(print_r($data));
      // error out if price override is incorrectly set
      if (isset($data['price_override_error']) && $data['price_override_error'] == 1) {
          $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Price override must be a non-zero positive price.</div>');
          redirect("admin/Estimates/addServiceEstimate");
      }
        // die(print_r($data));
        $job_id = $data['standalone_job_id'];
        $job_details = $this->JobModel->getOneJob(array('job_id' => $job_id));

        $company_id = $this->session->userdata['company_id'];
        $user_id = $this->session->userdata['user_id'];
        $property_id = $data['property_id'];
        $customer_id = $data['customer_id'];
        $job_name = $job_details->job_name;
        $job_price = $job_details->job_price;
        if(isset($data['price_override']) && $data['price_override'] > 0){
          $data['is_price_override_set'] = 1;
        }else{
          $data['is_price_override_set'] = 0;
        }
        //create program
          $param = array(
            'user_id' => $user_id,
            'company_id' => $company_id,
            'program_name' => $job_name,
            'program_price' => $data['program_price'] ??  1,
            'ad_hoc' => 1,
          );

        $program_id = $this->ProgramModel->insert_program($param);

        //Assign job to program
        $param2 = array(
          'program_id' => $program_id,
          'job_id' => $job_id,
          'priority' =>1
        );

        $result1 = $this->ProgramModel->assignProgramJobs($param2);

        //Create Estimate
        $estimateParam = array(
          'company_id' => $company_id,
          'customer_id' => $customer_id,
          'property_id' => $property_id,
          'estimate_date' => $data['estimate_date'],
          'program_id' => $program_id,
          'status' => $data['status'],
          'property_status' => $data['property_status'],
          'sales_rep' => $data['sales_rep'],
          'estimate_created_date' => date("Y-m-d H:i:s"),
          'estimate_update' => date("Y-m-d H:i:s"),
          'notes' => $data['notes'],
        );

        $estimate_id = $this->EstimateModal->CreateOneEstimate($estimateParam);

        //Store Estimate Price Override
      if($estimate_id) {
        $param3 = array(
        'estimate_id' => $estimate_id,
        'customer_id' => $customer_id,
        'property_id' => $property_id,
        'program_id' => $program_id,
        'job_id' => $job_id,
        'price_override' => $data['price_override'],
        'is_price_override_set' => $data['is_price_override_set'],
        'created_at' => date("Y-m-d H:i:s")
        );

        $this->EstimateModal->CreateOneEstimatePriceOverRide($param3);

			 //handle status if status == send estimate
              if ($data['status']==1 && $data['customer_email']!='' ) {
				  if(isset($data['notes']) && $data['notes'] != ''){
					  $email_data['msgtext'] = $data['notes'];
				  }else{
					 $email_data['msgtext'] = '';
				  }

				  $email_data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
				  $email_data['link'] =  base_url('welcome/pdfEstimate/').base64_encode($estimate_id);
				  $email_data['link_acc'] =  base_url('welcome/estimateAccept/').base64_encode($estimate_id);

                  $where_company = array('company_id' =>$company_id);

                  $email_data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

          $email_data['setting_details']->company_logo = ($email_data['setting_details']->company_resized_logo != '') ? $email_data['setting_details']->company_resized_logo : $email_data['setting_details']->company_logo;

          $body = $this->load->view('admin/estimate/estimate_email',$email_data,true);


          $where_company['is_smtp'] = 1;

          $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);

          if(!$company_email_details) {
            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
          }

          $res =   Send_Mail_dynamic($company_email_details, $email_data['customer_details']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Details',$email_data['customer_details']->secondary_email);

          $rep_data['sale_rep'] = $this->Administrator->getOneAdmin(array('id' => $data['sales_rep']));
          // die(print_r($rep_data));

          if(isset($rep_data['sale_rep'])){
            $body = $this->load->view('admin/estimate/assigned_email',$rep_data,true);
            $rep =   Send_Mail_dynamic($company_email_details, $rep_data['sale_rep']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Assigned',$email_data['customer_details']->secondary_email);
            // die(print_r($rep));
          }
        }


        // apply assigned coupons
        if (array_key_exists("assign_onetime_coupons",$data)) {
          $coupon_ids_arr = $data['assign_onetime_coupons'];
          foreach($coupon_ids_arr as $coupon_id) {

            $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
            $params = array(
                'coupon_id' => $coupon_id,
                'estimate_id' => $estimate_id,
                'coupon_code' => $coupon_details->code,
                'coupon_amount' => $coupon_details->amount,
                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                'coupon_type' => $coupon_details->type,
                'expiration_date' => $coupon_details->expiration_date
            );
            $resp = $this->CouponModel->CreateOneCouponEstimate($params);
          }
        }

        // check global coupons & assign if so
        $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $customer_id));
        if (!empty($coupon_customers)) {
          foreach($coupon_customers as $coupon_customer) {

            $coupon_id = $coupon_customer->coupon_id;
            $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
            $params = array(
                'coupon_id' => $coupon_id,
                'estimate_id' => $estimate_id,
                'coupon_code' => $coupon_details->code,
                'coupon_amount' => $coupon_details->amount,
                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                'coupon_type' => $coupon_details->type,
                'expiration_date' => $coupon_details->expiration_date
            );
            $resp = $this->CouponModel->CreateOneCouponEstimate($params);

            // $coupon_id = $coupon_customer->coupon_id;
            // $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
            // $params = array(
            //     'coupon_id' => $coupon_id,
            //     'invoice_id' => $invoice_id,
            //     'coupon_code' => $coupon_details->code,
            //     'coupon_amount' => $coupon_details->amount,
            //     'coupon_amount_calculation' => $coupon_details->amount_calculation,
            //     'coupon_type' => $coupon_details->type
            // );
            // $resp = $this->CouponModel->CreateOneCouponInvoice($params);
          }
        }

        if($bulk_call){
          return '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>';
        } else {
          $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>');
        }
          redirect("admin/Estimates");
      } else {
        if($bulk_call){
          return '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>';
        } else {
          $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>');
        }
        redirect("admin/Estimates");
      }
    }
  }

  public function getAllServicesByProgram($value=''){
    $program_id =  $this->input->post('program_id');
    $property_details =  $this->ProgramModel->getProgramAssignJobs(array('program_id' =>$program_id));

    if ($property_details) {
      $return_result =  array('status'=>200,'result'=>$property_details,'msg'=>'successfully');
    } else{
      $return_result =  array('status'=>400,'result'=>array(),'msg'=>'successfully');
    }
    echo json_encode($return_result);

  }

  public function getAllServicesByProgramMultiple($value=''){
    $program_ids =  $this->input->post('program_ids');
    $property_details = array();
    foreach($program_ids as $pid) {
        $property_details[] =  $this->ProgramModel->getProgramAssignJobs(array('program_id' =>$pid));
    }
    
    if ($property_details) {
      $return_result =  array('status'=>200,'result'=>$property_details,'msg'=>'successfully');
    } else{
      $return_result =  array('status'=>400,'result'=>array(),'msg'=>'successfully');
    }
    echo json_encode($return_result);

  }

    public function getAllEstimateBySearch($status){

    $where = array('t_estimate.company_id' =>$this->session->userdata['company_id']);

      if($status!=4) {
        $where['status'] = $status;
      }

     $data['estimate_details'] = $this->EstimateModal->getAllEstimate($where);

    $where = array('company_id' =>$this->session->userdata['company_id']);

    foreach($data['estimate_details'] as $key => $estiamte_detail) {
        $estimate_id = $estiamte_detail->estimate_id;
        $data['estimate_details'][$key]->coupon_details = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));
    }

     $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

     $body  =  $this->load->view('estimate/ajax_data',$data,TRUE);
     echo $body;

 }

 public function editEstimate($estimate_id) {

    $where = array('company_id' =>$this->session->userdata['company_id']);
    $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
    ##### ADDED 2/24/22 (RG) #####
      // $data['all_users'] = $this->SourceModel->getAllSource($where);
      $data['source_list'] = $this->SourceModel->getAllSource($where);
      $data['users'] = $this->Administrator->getAllAdmin($where);
      // die(print_r($data['users']));
      // $data['sources'] = array_merge($data['source_list'], $data['users']);
      $source = [];
      foreach($data['users'] as $user){
        $source = (object) array(
          'source_name' => $user->user_first_name.' '.$user->user_last_name,
          'user_id' => $user->user_id,
          'source_id' => $user->id,
        ) ;
        array_push( $data['source_list'], $source);
      }
      // die(print_r($data['source_list']));
      ####
	  $data['service_details'] = $this->JobModel->getJobList($where);
    // die(print_r($data['service_details']));
    $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
    $data['customer_details'] = $this->CustomerModel->get_all_customer(array('company_id' =>$this->session->userdata['company_id']));
    $where = array(
        't_estimate.company_id' =>$this->session->userdata['company_id'],
        'estimate_id' => $estimate_id,
    );

    $data['estimate_details'] = $this->EstimateModal->getOneEstimate($where);
    // die(print_r($data['estimate_details']));
    $data['property_details'] = $this->CustomerModel->getAllproperty(array('customer_id' =>$data['estimate_details']->customer_id));
    $data['program_details'] = $this->ProgramModel->get_all_program(array('company_id' =>$this->session->userdata['company_id'], 'program_price' =>$data['estimate_details']->program_pricing, 'ad_hoc'=>0, 'program_active' =>1));
    $data['joined_programs'] = $this->ProgramModel->getAllEstimateJoinedPrograms(array('estimate_id' =>$data['estimate_details']->estimate_id));
    $data['price_override_details'] = $this->EstimateModal->getOneEstimatePriceOverRide(array('estimate_id' =>$estimate_id, 'for_invoicing_only'=>NULL));

    $coupon_where = array(
        'company_id' => $this->session->userdata['company_id'],
        'type' => 0
    );
    $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);

    $data_temp_coupon = $this->CouponModel->getCouponEstimateIDs(array('estimate_id' => $estimate_id));
    $data['existing_coupon_estimate']  = array();
    if (!empty($data_temp_coupon)) {
        foreach ($data_temp_coupon as $value) {
            $data['existing_coupon_estimate'][] = $value->coupon_id;
        }
    }

    $data['existing_coupon_estimate_data'] = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));
    // print_r($data['customer_one_time_discounts']);
    // echo '<br><br>';
    // print_r($data['existing_coupon_estimate']);
    // echo '<br><br>';
    // print_r($data['existing_coupon_estimate_data']);
    // echo '<br><br>';
    // die();

    $page["active_sidebar"] = "estimatenav";
   	$page["page_name"] = 'Update Estimate';
    $page["page_content"] = $this->load->view("admin/estimate/edit_estimate",$data, TRUE);
    $this->layout->superAdminTemplateTable($page);
  }

 public function editEstimateData($estimate_id) {

    $data = $this->input->post();
    $jobsAll = array();
    $this->form_validation->set_rules('customer_id', 'Customer', 'required');
    $this->form_validation->set_rules('property_id', 'Property', 'required');
    $this->form_validation->set_rules('customer_email', 'Customer Email', 'trim');
    $this->form_validation->set_rules('estimate_date', 'Estimate Date', 'required');
    //$this->form_validation->set_rules('program_id', 'Program', 'required');
    $this->form_validation->set_rules('notes', 'notes', 'trim');



    if ($this->form_validation->run() == FALSE) {

        // echo validation_errors();
        $this->editEstimate($estimate_id);
    } else {
        $data = $this->input->post();
		$user_id = $this->session->userdata['user_id'];
        $company_id = $this->session->userdata['company_id'];
        
        //$program_id = $data['program_id']; this is now done with some joins so comment this line out for now
        // we need to check to see what programs/jobs were already joined to this estimate
        $joined_program_info = $this->ProgramModel->getAllEstimateJoinedPrograms(array('estimate_id'=>$estimate_id));
        $programs_on_this_estimate = $services_on_this_estimate = $job_ids_for_program_join = $program_ids_for_join_table = $service_to_program_id = array();
        foreach($joined_program_info as $jpi) {
            if($jpi->ad_hoc == 1) {
                $services_on_this_estimate[] = $jpi->service_id;
                $service_to_program_id[$jpi->service_id] = $jpi->program_id;
            } else {
                // ad_hoc being 0 here means that is a program
                $programs_on_this_estimate[] = $jpi->program_id;
            }
        }
        //check for standalone service
        if(isset($data["standalone_job_id_array"]) && count($data["standalone_job_id_array"]) > 0) {
            // we need to go through each of these and make a new program for the ones that dont have one
            foreach($data["standalone_job_id_array"] as $stand_alone) {
                if(!in_array($stand_alone, $services_on_this_estimate)) {
                    $this_service_info = $this->JobModel->getOneJob(array('job_id' => $stand_alone));
                    $programData = array();
                    $programData['company_id'] = $company_id;
                    $programData['user_id'] = $user_id;
                    $programData['program_name'] = $this_service_info->job_name;
                    $programData['jobs_all'] = array($this_service_info->job_id);
                    $programData['program_price'] = $data['program_pricing'];
                    $programResults = $this->createModifiedBundledProgram($programData);
                    $program_ids_for_join_table[$programResults["program_id"]] = 1;
                    $job_ids_for_program_join[$programResults["program_id"]] = $this_service_info->job_id;
                    $service_to_program_id[$this_service_info->job_id] = $programResults["program_id"];
                    $jobsAll = array_unique(array_merge($jobsAll,array($stand_alone)));
                }
            }
            // we also need to see if they removed any of the services and remove those from the join table
            foreach($services_on_this_estimate as $sote) {
                if(!in_array($sote, $data["standalone_job_id_array"])) {
                    // not only remove it the job from the join table - but also from the estimate price override table
                    $this->EstimateModal->deleteEstimatePriceOverRide(array('estimate_id'=>$estimate_id, 'program_id'=>$service_to_program_id[$sote], 'job_id'=>$sote, 'customer_id'=>$data["customer_id"], 'property_id'=>$data["property_id"]));
                    $this->EstimateModal->deleteEstimateProgram(array('estimate_id'=>$estimate_id, 'program_id'=>$service_to_program_id[$sote], 'service_id'=>$sote));
                }
            }
        } elseif(count($services_on_this_estimate) > 0 && count($data["standalone_job_id_array"]) == 0) {
            // also need a check to make sure they didn't remove all of the services and if they did we can delete them all
            foreach($services_on_this_estimate as $sote) {
                $this->EstimateModal->deleteEstimatePriceOverRide(array('estimate_id'=>$estimate_id, 'program_id'=>$service_to_program_id[$sote], 'job_id'=>$sote, 'customer_id'=>$data["customer_id"], 'property_id'=>$data["property_id"]));
                $this->EstimateModal->deleteEstimateProgram(array('estimate_id'=>$estimate_id, 'program_id'=>$service_to_program_id[$sote], 'service_id'=>$sote));
            }
        }


        // we also need to do the same kinda thing for the programs (but will not have to make any new programs now as we can just tie the id we have to the join table)
        if(isset($data["program_id_array"]) && count($data["program_id_array"]) > 0) {
            // first we need to see if we need to add any new programs that were checked into the join table
            foreach($data["program_id_array"] as $pida) {
                if(!in_array($pida, $programs_on_this_estimate)) {
                    $program_ids_for_join_table[$pida] = 0;
                }
            }
            // also need to check for removed programs and handle getting those out of the join and override table
            foreach($programs_on_this_estimate as $pote) {
                if(!in_array($pote, $data["program_id_array"])) {
                    $this->EstimateModal->deleteEstimatePriceOverRide(array('estimate_id'=>$estimate_id, 'program_id'=>$pote, 'customer_id'=>$data["customer_id"], 'property_id'=>$data["property_id"]));
                    $this->EstimateModal->deleteEstimateProgram(array('estimate_id'=>$estimate_id, 'program_id'=>$pote));
                }
            }
        } elseif(count($programs_on_this_estimate) > 0 && count($data["program_id_array"]) == 0) {
            // also need a check to make sure they didn't remove all of the services and if they did we can delete them all
            foreach($programs_on_this_estimate as $pote) {
                $this->EstimateModal->deleteEstimatePriceOverRide(array('estimate_id'=>$estimate_id, 'program_id'=>$pote, 'customer_id'=>$data["customer_id"], 'property_id'=>$data["property_id"]));
                $this->EstimateModal->deleteEstimateProgram(array('estimate_id'=>$estimate_id, 'program_id'=>$pote));
            }
        }
        if(count($program_ids_for_join_table) > 0 || count($job_ids_for_program_join) > 0) {
            $this->EstimateModal->CreateEstimatePrograms($program_ids_for_join_table,$estimate_id, $job_ids_for_program_join);
        }
        
        
        $wherearr = array(
            'estimate_id' => $estimate_id,
        );

        // need to do some logic on the source before we send that through
        $source_data = $this->EstimateModal->getSource($data['property_id']);
        $source_from_property_info = $source_data[0]->source;
        if($data["source"] == "") {
            // if they did not pick the source we need to deafult it to the property source
            $source_for_estimate = $source_from_property_info;
        } else {
            $source_for_estimate = $data["source"];
        }
        $updatearr = array(
            'customer_id' => $data['customer_id'],
            'property_id' => $data['property_id'],
            'estimate_date' => $data['estimate_date'],
            'sales_rep' => $data['sales_rep'],
            'estimate_update' => date("Y-m-d H:i:s"),
            'notes' => $data['notes'],
            'source' => $source_for_estimate,
        );



        $result = $this->EstimateModal->updateEstimate($wherearr, $updatearr);
        
        if ($result) {

            $this->EstimateModal->deleteEstimatePriceOverRide($wherearr);

            if ( isset($data['joblistarray']) &&  !empty($data['joblistarray']) ) {

                foreach (json_decode($data['joblistarray']) as $value) {
                    $param3 = array(
                        'estimate_id' => $estimate_id,
                        'customer_id' => $data['customer_id'],
                        'property_id' => $data['property_id'],
                        'program_id' => (isset($value->program_id)?$value->program_id:$service_to_program_id[$value->job_id]),
                        'job_id' => $value->job_id,
                        'price_override' => $value->price_override,
                        'is_price_override_set' => $value->is_price_override_set,
                        'created_at' => date("Y-m-d H:i:s")
                    );       

                    $this->EstimateModal->CreateOneEstimatePriceOverRide($param3);
                }
            }

            // UPDATE COUPON_ESTIMATES
            $new_coupons_csv = json_decode($data['assign_coupons_csv']);
            if(isset($new_coupons_csv)) {

                // remove deleted coupons
                $all_coupon_estimates = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));
                foreach ($all_coupon_estimates as $existing_coupon_estimate) {
                    if ( !in_array($existing_coupon_estimate->coupon_id, $new_coupons_csv) ) {
                        
                        // delete coupon if pre-existing coupon_estimate is not in the new list
                        $this->CouponModel->DeleteCouponEstimate(array("coupon_estimate_id" => $existing_coupon_estimate->coupon_estimate_id));

                                $rep_data['sale_rep'] = $this->Administrator->getOneAdmin(array('id' => $data['sales_rep']));
                                // die(print_r($rep_data));
                                $body = $this->load->view('admin/estimate/assigned_email', $rep_data, true);
                                $rep = Send_Mail_dynamic($company_email_details, $rep_data['sale_rep']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Estimate Assigned', $email_data['customer_details']->secondary_email);
                                // die(print_r($rep));
                            }
                        }
                    }

//        die(print_r($response_object->recipients));

                    if ($bulk_call) {

                        if (isset($error_message) && $error_message != "") {
                            // this means that the SignWell api got an error and nothing got sent over to them
                            return '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully in Spraye but not at SignWell. (SignWell error message: ' . $error_message . ')</div>';
                        } else {
                            return '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>';
                        }
                    } else {
                        if (isset($error_message) && $error_message != "") {
                            // this means that the SignWell api got an error and nothing got sent over to them
                            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully in Spraye but not at SignWell. (SignWell error message: ' . $error_message . ')</div>');
                            redirect("admin/Estimates");
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully</div>');
                            redirect("admin/Estimates");
                        }
                    }
                } else {
                    if ($bulk_call) {
                        return '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong> not added. Please try again</div>');
                        redirect("admin/Estimates");
                    }
                }
            }
 }



    public function pdfEstimate($estimate_id)
    {

        $where = array(
            "t_estimate.company_id" => $this->session->userdata['company_id'],
            'estimate_id' => $estimate_id
        );

        $data['estimate_details'] = $this->EstimateModal->getOneEstimate($where);
        $data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($data['estimate_details']->customer_id);
        $data['property_details'] = $this->PropertyModel->getOneProperty(array('property_id' => $data['estimate_details']->property_id));

        // $data['invoice_details'] = $this->INV->getOneInvoive($where);

        $data['job_details'] = GetOneEstimatAllJobPrice(array('estimate_id' => $estimate_id));

        $where = array('user_id' => $this->session->userdata['user_id']);
        $data['user_details'] = $this->Administrator->getOneAdmin($where);

        // this is how to get service wide for estimates -- but for now not using these - just coupon_estimates
        // SERVICE WIDE COUPONS
        // $arry = array(
        // 	'customer_id' => $data['estimate_details']->customer_id,
        // 	'program_id' => $data['estimate_details']->program_id,
        // 	'property_id' => $data['estimate_details']->property_id
        // );
        // $data['coupon_job'] = $this->CouponModel->getAllCouponJob($arry);

        // ESTIMATE COUPONS
        $data['coupon_estimate'] = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));

        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        // die(print_r($data));

        $this->load->view('admin/estimate/pdf_estimate', $data);


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
        $this->dompdf->stream("welcome.pdf", array("Attachment" => 0));
    }

    public function printEstimate($invoice_ids)
    {


        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where = array('user_id' => $this->session->userdata['user_id']);
        $data['user_details'] = $this->Administrator->getOneAdmin($where);

        $invoice_ids = explode(",", $invoice_ids);
        foreach ($invoice_ids as $key => $value) {

            $where = array(
                "t_estimate.company_id" => $this->session->userdata['company_id'],
                'estimate_id' => $value
            );

            $estimate_details_data = $this->EstimateModal->getOneEstimate($where);

            // ESTIMATE COUPONS
            $estimate_details_data->coupon_details = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $value));

            $data['estimate_details'][] = $estimate_details_data;

        }


        foreach ($data['estimate_details'] as $estimate) {

            $where = array(
                "t_estimate.company_id" => $this->session->userdata['company_id'],
                'estimate_id' => $estimate->estimate_id
            );
            $data['customer_details'][] = $this->CustomerModel->getOneCustomerDetail($estimate->customer_id);

            $data['job_details'][] = GetOneEstimatAllJobPrice(array('estimate_id' => $estimate->estimate_id));

            $where = array('user_id' => $this->session->userdata['user_id']);
            $data['user_details'] = $this->Administrator->getOneAdmin($where);

            // ESTIMATE COUPONS
            $data['coupon_estimate'][] = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate->estimate_id));

        }
        $this->load->view('admin/estimate/bulk_pdf_estimate_print.php', $data);

        $html = $this->output->get_output();
        // die(print_r($html));
        //  // Load pdf library
        $this->load->library('pdf');


        //  // Load HTML content
        $this->dompdf->loadHtml($html);

        //  // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');

        //  // Render the HTML as PDF
        $this->dompdf->render();

        //  // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment" => 0));
    }

    public function printEstimate_old($invoice_ids)
    {

        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where = array('user_id' => $this->session->userdata['user_id']);
        $data['user_details'] = $this->Administrator->getOneAdmin($where);

        $invoice_ids = explode(",", $invoice_ids);
        foreach ($invoice_ids as $key => $value) {

            $where = array(
                "t_estimate.company_id" => $this->session->userdata['company_id'],
                'estimate_id' => $value
            );

            $estimate_details_data = $this->EstimateModal->getOneEstimate($where);

            // ESTIMATE COUPONS
            $estimate_details_data->coupon_details = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $value));

            $data['estimate_details'][] = $estimate_details_data;

        }

        $this->load->view('admin/estimate/multiple_pdf_estimate_print', $data);


   }
    public function calculateServiceCouponCost($param){
        $total_cost = $param['cost'];
        $coupon_jobs = $this->CouponModel->getAllCouponJob(array(
            'job_id' => $param['job_id'],
            'program_id' => $param['program_id'],
            'property_id' => $param['property_id'],
            'customer_id' => $param['customer_id']
        ));

        if (!empty($coupon_jobs)) {
            foreach ($coupon_jobs as $coupon_job) {

                $coupon_id = $coupon_job->coupon_id;
                $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

                // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                $expiration_pass = true;
                if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
                    $coupon_expiration_date = strtotime($coupon_details->expiration_date);

                    $now = time();
                    if ($coupon_expiration_date < $now) {
                        $expiration_pass = false;
                    }
                }

                if ($expiration_pass == true) {
                    if ($coupon_details->amount_calculation == 0) {
                        $discount_amm = (float) $coupon_details->amount;

                        if (($total_cost - $discount_amm) < 0 ) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                        }

                    } else {
                        $percentage = (float) $coupon_details->amount;
                        $discount_amm = (float) $total_cost * ($percentage / 100);

                        if (($total_cost - $discount_amm) < 0 ) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                        }

                    }
                }
            }
        }

        return number_format($total_cost, 2, '.', ',');
    }

    public function changeStatus() {
        $data =  $this->input->post();
        $estimate_accepted = 0;
    
        // die(print_r($data));
        // $data = array(
        //   'status' => 2,
        //   'estimate_id' => 1541
        // );~
        // die(print_r($data));
      $where = array(
        'estimate_id' => $data['estimate_id']
      );
      if ($data['status']==3 ) {
    
        $estimate_details =  $this->EstimateModal->getOneEstimate($where);
    
        // we need to get all of the joined programs to the estimate now
        $program_ids = $this->EstimateModal->getAllJoinedPrograms(array('estimate_id' => $estimate_id));
        if ($estimate_details->program_pricing == 1 || $estimate_details->program_price == 1) {
            foreach($program_ids as $prid) {
                //assign/update property to program
                $param = array(
                    'program_id'=>$prid->program_id,
                    'property_id'=>$estimate_details->property_id
                );
            
                $check = $this->EstimateModal->getOneProgramProperty($param);
            
                if ($check) {
                    $result2 = $this->EstimateModal->updateProgramProperty(array('property_program_id'=>$check->property_program_id), $param);
            
                } else {
                    $result2 = $this->EstimateModal->assignProgramProperty($param);
                }
            }
    
        }
      }
    
      // if accpeting estimate
      // echo "<pre>";
      if ($data['status'] == 2) {
        $estimate_accepted = 1;
        $estimate_details =  $this->EstimateModal->getOneEstimate($where);
        if($estimate_details){
            ##### ADDED 3/10/22 #####
            $property_status = $this->PropertyModel->updateAdminTbl($estimate_details->property_id, array('property_status' => '1'));
            // die(print_r($property_status));
            // die(print_r($this->db->last_query()));
            ####
        }
        
        // if one time program invoiceing
        if ($estimate_details->program_pricing == 1 || $estimate_details->program_price == 1) {
    
          $user_id     = $this->session->userdata['user_id'];
          $company_id  = $estimate_details->company_id;
          $customer_id = $estimate_details->customer_id;
          $property_id = $estimate_details->property_id;
          $estimate_id = $data['estimate_id'];
          // we need to get all of the joined programs to the estimate now
          $program_ids = $this->EstimateModal->getAllJoinedPrograms(array('estimate_id' => $estimate_id));
          $program_id_to_ad_hoc = $program_id_to_service_name = $program_ids_to_services = $services_from_estimate = array();
          foreach($program_ids as $proid) {
            // we need to get info for the items that are services and not programs - so we can combine those and make them into a new program
            if($proid->ad_hoc == "1") {
                $program_id_to_ad_hoc[$proid->program_id] = $proid->ad_hoc;
                $program_ids_to_services[$proid->program_id] = $proid->service_id;
                $services_from_estimate[] = $proid->service_id;
            }
          }
          $date        = date('Y-m-d', time());
          $date_time   = date('Y-m-d H:m:s', time());
    
          $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
          $property_details = $this->PropertyModel->getOneProperty(array('property_id'=> $property_id));
    
          // get estimate total cost
          $total_estimate_cost = 0;
          $estimate_price_overide_data = $this->EstimateModal->getAllEstimatePriceOveridewJob(array('estimate_id' => $estimate_id));
          // echo "<pre>";
          // print_r($estimate_price_overide_data);
          // die();
          $invoice_total_per_program = array();
          $total_for_services = 0;
          // need to set all the keys for the above and set that to 0 so we can add to it in the loop below
          foreach ($estimate_price_overide_data as $es_job) {
            if(!in_array($es_job->program_id,array_keys($program_ids_to_services))) {
                $invoice_total_per_program[$es_job->program_id] = 0;
            }
          }
          
          foreach ($estimate_price_overide_data as $es_job) {
            if (isset($es_job->is_price_override_set) && !empty($es_job->is_price_override_set)) {
                $job_cost = $es_job->price_override;
            } else {
                $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id'=>$property_id,'program_id' => $es_job->program_id));
    
                if(isset($priceOverrideData->is_price_override_set) && $priceOverrideData->is_price_override_set == 1){
                    $job_cost =  $priceOverrideData->price_override;
                }else{
                    //else no price overrides, then calculate job cost
                    $lawn_sqf = $property_details->yard_square_feet;
                    $job_price = $es_job->job_price;
    
                    //get property difficulty level
                    $setting_details = $this->CompanyModel->getOneCompany(array('company_id' =>$company_id));
    
                    if(isset($property_details->difficulty_level) && $property_details->difficulty_level == 2){
                      $difficulty_multiplier = $setting_details->dlmult_2;
                    }elseif(isset($property_details->difficulty_level) && $property_details->difficulty_level == 3){
                      $difficulty_multiplier = $setting_details->dlmult_3;
                    }else{
                      $difficulty_multiplier = $setting_details->dlmult_1;
                    }
    
                    //get base fee
                    if(isset($es_job->base_fee_override)){
                      $base_fee = $es_job->base_fee_override;
                    }else{
                      $base_fee = $setting_details->base_service_fee;
                    }
    
                    $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier)/1000;
    
                    //get min. service fee
                    if(isset($es_job->min_fee_override)){
                      $min_fee = $es_job->min_fee_override;
                    }else{
                      $min_fee = $setting_details->minimum_service_fee;
                    }
    
                    // Compare cost per sf with min service fee
                    if($cost_per_sqf > $min_fee){
                      $job_cost = $cost_per_sqf;
                    }else{
                      $job_cost = $min_fee;
                    }
                }
    
                // $job_cost = $es_job->job_price * $property_details->yard_square_feet/1000;
            }
            if(array_key_exists($es_job->program_id, $invoice_total_per_program)) {
                $invoice_total_per_program[$es_job->program_id] += $job_cost;
            } else {
                $total_for_services += $job_cost;
            }
          }
          // we need to take the services that we have from the estimate, combine them into ONE single program - and assign that to an invoice
          $bundled_program_name = '';
          if(count($services_from_estimate) == 1){
            $bundled_program_name = $this->JobModel->getOneJob(array('job_id' => $services_from_estimate[0]))->job_name.'-Standalone Service';
          } elseif(count($services_from_estimate) > 1){
            $job_names = array_map(function($s) {
              $r = $this->JobModel->getOneJob(array('job_id' => $s));
              return $r->job_name;
            }, $services_from_estimate);
            $bundled_program_name = implode('+', $job_names);
          }
          if($bundled_program_name != '') {
            $jobsAll = array();
            foreach($services_from_estimate as $service){
                $jobsAll = array_unique(array_merge($jobsAll,array($service)));
            }
            $programData = array();
            $programData['company_id'] = $company_id;
            $programData['user_id'] = $user_id;
            $programData['program_name'] = $bundled_program_name;
            $programData['jobs_all'] = $jobsAll;
            $programData['program_price'] = $estimate_details->program_pricing;
            if($programData['program_price'] == "") {
                $programData['program_price'] = $estimate_details->program_price;
            }
            $programResults = $this->createModifiedBundledProgram($programData);
            // now that we have created the new program we can add it to the invoice total per program array and let that handle creating the invoice
            $invoice_total_per_program[$programResults['program_id']] = $total_for_services;
            foreach($services_from_estimate as $service) {
                $this_service_override_numbers = $this->EstimateModal->getAllEstimatePriceOveride(array('job_id'=>$service, 'estimate_id'=>$estimate_id));
                // we need to create new estimate overrides with the new program ID on it for each service
                $service_numbers = array(
                    'estimate_id' => $estimate_id,
                    'customer_id' => $this_service_override_numbers[0]->customer_id,
                    'property_id' => $this_service_override_numbers[0]->property_id,
                    'program_id' => $programResults['program_id'],
                    'job_id' => $this_service_override_numbers[0]->job_id,
                    'price_override' => $this_service_override_numbers[0]->price_override,
                    'is_price_override_set' => $this_service_override_numbers[0]->is_price_override_set,
                    'created_at' => date("Y-m-d H:i:s"),
                    'for_invoicing_only' => 1
                  );
                  $this->EstimateModal->CreateOneEstimatePriceOverRide($service_numbers);
            }
          }
    
    
          // $total_sales_tax = 0;
          // if ($setting_details->is_sales_tax==1) {
          //   $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id'=>$property_id));
          //   if ($property_assign_tax) {
          //     foreach ($property_assign_tax as  $tax_details) {
          //       $total_sales_tax += ($tax_details['tax_value'] * $total_estimate_cost);
          //     }
          //   }
          // }
          // we no longer need this total from below as we are doing the invoices program by program now and not total
          //$total = $total_estimate_cost;
            
          // now we want to foreach all of the invoice totals for the programs and set up the invoices for them
          // after this foreach is done we need to take the services and make them into their own program - then create an invoice for that program as well
          foreach($invoice_total_per_program as $program_id=>$itpp) {
            // create invoice for estimate
            $inv_param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'customer_id' => $customer_id,
                'property_id' => $property_id,
                'invoice_date' => $date,
                'description' => 'Invoice From Estimate',
                'cost' => $itpp,
                'program_id' => $program_id,
                'is_created' => 1,
                'invoice_created' => date("Y-m-d H:i:s"),
            );
            $invoice_id = $this->INV->createOneInvoice($inv_param);
    
            if ($invoice_id) {
                //figure sales tax
                $total_tax_amount = 0;
                if ($setting_details->is_sales_tax==1) {
                    $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id'=>$property_id));
                    if ($property_assign_tax) {
                        foreach ($property_assign_tax as  $tax_details) {
                        $invoice_tax_details =  array(
                            'invoice_id' => $invoice_id,
                            'tax_name' => $tax_details['tax_name'],
                            'tax_value' => $tax_details['tax_value'],
                            'tax_amount' => $itpp*$tax_details['tax_value']/100
                        );
                        $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                        $total_tax_amount +=  $invoice_tax_details['tax_amount'];
    
                        }
                    }
                }
    
                //Quickbooks Invoice **
    
                $property_deets = $this->PropertyModel->getOnePropertyDetail($inv_param['property_id']);
                $property_street = explode(',', $property_deets->property_address)[0];
    
                $cust_details = getOneCustomerInfo(array('customer_id' => $customer_id));
                $QBO_description = $actual_description_for_QBO = array();
                foreach($program_ids as $p) {
                    $jobs = $this->ProgramModel->getSelectedJobs($p->program_id);
    
                    foreach ($jobs as $key3 => $value3) {
                        $job_id = $value3->job_id;
    
    
                $job_details = $this->JobModel->getOneJob(array('job_id' => $job_id));
    
                $description = $job_details->job_name . " ";
    
                        $QBO_description[] = $job_details->job_name;
                        $actual_description_for_QBO[] = $job_details->job_description;
    
                    }
                }
            
    
                $inv_param['customer_email'] = $cust_details['email'];
                $inv_param['job_name'] = $description;
    
                $QBO_description = implode(', ', $QBO_description);
                $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);
                $QBO_param = $inv_param;
                $QBO_param['property_street'] = $property_street;
                $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
                $QBO_param['job_name'] = $QBO_description;
    
                // Subtract global customer coupon value from QBO total before it's passed to QBO
                $coup_cust_param = array(
                    'cost' => $QBO_param['cost'],
                    'customer_id' => $customer_id
                );
    
                $cost_with_cust_coupon = $this->calculateCustomerCouponCost($coup_cust_param);
    
                $QBO_param['cost'] = $cost_with_cust_coupon;
    
                //  die(print_r($QBO_param));
    
    
                $quickbook_invoice_id = $this->QuickBookInv($QBO_param);
    
    
                //if quickbooks invoice then update invoice table with id
                if ($quickbook_invoice_id) {
                    $invoice_id = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('quickbook_invoice_id' => $quickbook_invoice_id));
                }
    
                //  die(print_r($quickbook_invoice_id));
                
    
                // where estimate jobs are stored
                $estimate_price_overide_data = $this->EstimateModal->getAllEstimatePriceOveridewJob(array('estimate_id' => $estimate_id, 'program_id' => $program_id));
                // print_r($estimate_price_overide_data);
                $used_programs = array();
                foreach ($estimate_price_overide_data as $es_job) {
                    $assign_program_param = array(
                        'property_id'           => $property_id,
                        'program_id'            => $es_job->program_id,
                        'price_override'        => 0,
                        'is_price_override_set' => 0,
                    );
                    if(!in_array($es_job->program_id, $used_programs)) {
                      $property_program_id = $this->PropertyModel->assignProgram($assign_program_param);
                    }
                    $used_programs[] = $es_job->program_id;
                    if (isset($es_job->is_price_override_set) && !empty($es_job->is_price_override_set)) {
                        $job_cost = $es_job->price_override;
                    } else {
    
                        $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id'=>$property_id,'program_id' => $es_job->program_id));
            
                        if(isset($es_job->is_price_override_set) && $priceOverrideData->is_price_override_set == 1){
                            $job_cost =  $priceOverrideData->price_override;
                        }else{
    
                            //else no price overrides, then calculate job cost
                            $lawn_sqf = $property_details->yard_square_feet;
                            $job_price = $es_job->job_price;
    
                            //get property difficulty level
                            $setting_details = $this->CompanyModel->getOneCompany(array('company_id' =>$company_id));
    
                            if(isset($property_details->difficulty_level) && $property_details->difficulty_level == 2){
                                $difficulty_multiplier = $setting_details->dlmult_2;
                            }elseif(isset($property_details->difficulty_level) && $property_details->difficulty_level == 3){
                                $difficulty_multiplier = $setting_details->dlmult_3;
                            }else{
                                $difficulty_multiplier = $setting_details->dlmult_1;
                            }
    
                            //get base fee
                            if(isset($es_job->base_fee_override)){
                                $base_fee = $es_job->base_fee_override;
                            }else{
                                $base_fee = $setting_details->base_service_fee;
                            }
    
                            $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier)/1000;
    
                            //get min. service fee
                            if(isset($es_job->min_fee_override)){
                                $min_fee = $es_job->min_fee_override;
                            }else{
                                $min_fee = $setting_details->minimum_service_fee;
                            }
    
                            // Compare cost per sf with min service fee
                            if($cost_per_sqf > $min_fee){
                                $job_cost = $cost_per_sqf;
                            }else{
                                $job_cost = $min_fee;
                            }
                        }
            
                        // $job_cost = $es_job->job_price * $property_details->yard_square_feet/1000;
                    }
                    // $total_estimate_cost += $job_cost;
    
                    $job_id = $es_job->job_id;
                    $where = array(
                        'property_program_id' => $property_program_id,
                        'customer_id'         => $customer_id,
                        'property_id'         => $property_id,
                        'program_id'          => $es_job->program_id,
                        'job_id'              => $job_id,
                        'invoice_id'          => $invoice_id,
                        'job_cost'            => $job_cost,
                        'created_at'          => $date_time,
                        'updated_at'          => $date_time,
                    );
                    $proprojobinv = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($where);
    
                }
                // get all coupon_estimates where estimateid=
                $coupon_estimates = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));
    
                // duplicate them for coupon_invoices using invoice_id
                if (!empty($coupon_estimates)) {
                    foreach($coupon_estimates as $coupon_estimate) {
                        $coupon_params = array(
                            'coupon_id' => $coupon_estimate->coupon_id,
                            'invoice_id' => $invoice_id,
                            'coupon_code' => $coupon_estimate->coupon_code,
                            'coupon_amount' => $coupon_estimate->coupon_amount,
                            'coupon_amount_calculation' => $coupon_estimate->coupon_amount_calculation,
                            'coupon_type' => 0
                        );
                        $this->CouponModel->CreateOneCouponInvoice($coupon_params);
                    }
                }
    
            }
          }
        } else {
            $estimate_details =  $this->EstimateModal->getOneEstimate($where);
          
            // we need to get all of the joined programs to the estimate now
            $program_ids = $this->EstimateModal->getAllJoinedPrograms(array('estimate_id' => $estimate_details->estimate_id));
            foreach($program_ids as $prid) {
                //assign/update property to program
                $param = array(
                    'program_id'=>$prid->program_id,
                    'property_id'=>$estimate_details->property_id
                );
            
                $check = $this->EstimateModal->getOneProgramProperty($param);
                
                if ($check) {
                    $result2 = $this->EstimateModal->updateProgramProperty(array('property_program_id'=>$check->property_program_id), $param);
            
                } else {
                    $assign_program_param = array(
                        'property_id'           => $estimate_details->property_id,
                        'program_id'            => $prid->program_id,
                        'price_override'        => 0,
                        'is_price_override_set' => 0,
                    );
                    $result2 = $this->EstimateModal->assignProgramProperty($assign_program_param);
                }
            }
        }
      }
    
    
      $param = array(
        'status' =>$data['status'],
        'estimate_update' => date("Y-m-d H:i:s")
      );
     
      if ($data['status']==3) {
          $param['payment_created'] = date("Y-m-d H:i:s");
      }
      // if ($data['status']==5) {
      //     $param['status'] = 5;
      // }
    
      $where = array(
        'estimate_id' =>$data['estimate_id']
      );
    
      $result = $this->EstimateModal->updateEstimate($where,$param);  
     
      if($estimate_accepted == 1){
    
        $estimate_details =  $this->EstimateModal->getOneEstimate($where);
    
        // Adding Email and text logic here
    
        $property = $this->PropertyModel->getOneProperty(array('property_id'=>$estimate_details->property_id));
    
        $customer_id = $this->CustomerModel->getOnecustomerPropert(array('property_id'=>$estimate_details->property_id));
    
    //	#check customer billing type
    //	$checkGroupBilling = $this->CustomerModel->checkGroupBilling($customer_id->customer_id);
    //
    //	#if customer billing type = group billing, then we notify the property level contact info
    //	if(isset($checkGroupBilling) && $checkGroupBilling == "true"){
    //		$emaildata['contactData'] = $this->PropertyModel->getGroupBillingByProperty($estimate_details->property_id);
    //		$emaildata['propertyData'] = $property;
    //		$emaildata['programData'] = $this->ProgramModel->getProgramDetail($estimate_details->program_id);
    //		$where = array('company_id' =>$this->session->userdata['company_id']);
    //		$emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);
    //
    //		$emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);
    //		$emaildata['accepted_date'] = date("Y-m-d H:i:s");
    //		$where['is_smtp'] = 1;
    //		$company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
    //
    //		if (!$company_email_details) {
    //		   $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
    //		} 
    //		$body  = $this->load->view('email/group_billing/estimate_accepted_email',$emaildata,true);
    //
    //		if ($emaildata['company_email_details']->estimate_accepted_status==1 && isset($emaildata['contactData']['email_opt_in']) && $emaildata['contactData']['email_opt_in'] == 1) {
    //			$res =   Send_Mail_dynamic($company_email_details,$emaildata['contactData']['email'],array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Accepted');
    //		}
    //
    //		if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->estimate_accepted_status_text==1 && isset($emaildata['contactData']['phone_opt_in']) && $emaildata['contactData']['phone_opt_in'] ==1) {
    //			$text_res = Send_Text_dynamic($emaildata['contactData']['phone'],$emaildata['company_email_details']->estimate_accepted_text,'Estimate Accepted');
    //		}
    //
    //	}else{
            $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id'=>$customer_id->customer_id));
    
            $emaildata['email_data_details'] = $this->Tech->getPropertyEmailData(array('customer_id' =>$customer_id->customer_id,'is_email'=>1,'property_id' =>$estimate_details->property_id));
    
            $where = array('company_id' =>$this->session->userdata['company_id']);
            $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);
    
            $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);
            $emaildata['accepted_date'] = date("Y-m-d H:i:s");
            $joined_programs = $this->EstimateModal->getAllJoinedPrograms(array('estimate_id' => $estimate_details->estimate_id));
            $program_names_array = array();
            $service_names_array = array();
            foreach($joined_programs as $key=>$programs) {
                if($programs->ad_hoc == 0) {
                    $program_names_array[] = $programs->program_name;
                }else{
                    //add services to email
                    $where_arr = array('job_id' => $programs->service_id); 
                    $jobs = $this->JobModel->getJobList($where_arr);

                    foreach($jobs as $key=>$job) {
                        $service_names_array[] = $job->job_name;
                    }
                }
            }
            $emaildata['program_names'] = implode(", ", $program_names_array);
            if($emaildata['program_names'] == "") {
                // now we need to use the old way of doing this
                $emaildata['program_names'] = $estimate_details->old_program_name;
            }


            $emaildata['service_names'] = implode(", ", $service_names_array);







            $where['is_smtp'] = 1;
            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
    
            if (!$company_email_details) {
               $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
            } 
            $body  = $this->load->view('email/estimate_accepted_email',$emaildata,true);
    
            if ($emaildata['company_email_details']->estimate_accepted_status==1) {
                $res =   Send_Mail_dynamic($company_email_details,$emaildata['customerData']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Estimate Accepted',$emaildata['customerData']->secondary_email);
            }
    
            if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->estimate_accepted_status_text==1 && $emaildata['customerData']->is_mobile_text==1) {
                $text_res = Send_Text_dynamic($emaildata['customerData']->phone,$emaildata['company_email_details']->estimate_accepted_text,'Estimate Accepted');
            }
    //	}
        // End Adding Email and Text logic here
      }
        
     if ($result) {
          echo "true";
      } else {
          echo "false";
      }
    }


    public function sendPdfMail()
    {

        $company_id = $this->session->userdata['company_id'];

        $estimate_id = $this->input->post('estimate_id');
        $customer_id = $this->input->post('customer_id');

        // get second message
        $message = $this->input->post('message');
        $data['msgtext'] = $message[0];

        // get first message
        $estimate_estimate = $this->EstimateModal->getOneEstimate(['estimate_id' => $estimate_id]);
        $data['msgtext_one'] = $estimate_estimate->notes;

        $where = array('estimate_id' => $estimate_id);
        $param = array('status' => 1, 'estimate_update' => date("Y-m-d H:i:s"));
        $this->EstimateModal->updateEstimate($where, $param);


        $data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);

        $data['link'] = base_url('welcome/pdfEstimate/') . base64_encode($estimate_id);
        $data['link_acc'] = base_url('welcome/estimateAccept/') . base64_encode($estimate_id);

        $where_company = array('company_id' => $company_id);

        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;

        $body = $this->load->view('admin/estimate/estimate_email', $data, true);


        $where_company['is_smtp'] = 1;
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);

        if (!$company_email_details) {
            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
        }

        $res = Send_Mail_dynamic($company_email_details, $data['customer_details']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Estimate Details', $data['customer_details']->secondary_email);

        // echo 1;


    }

    public function sendPdfMailToSelected()
    {
        $company_id = $this->session->userdata['company_id'];
        $group_id_array = $this->input->post('group_id_array');


        $message = $this->input->post('message');
        // die(print_r($this->input->post()));
        $data['msgtext'] = $message[0];

        if (!empty($group_id_array)) {

            foreach ($group_id_array as $key => $value) {
                $in_ct = explode(':', $value);
                $estimate_id = $in_ct[0];
                $customer_id = $in_ct[1];
                $where = array('estimate_id' => $estimate_id);
                $param = array('status' => 1, 'estimate_update' => date("Y-m-d H:i:s"));
                $this->EstimateModal->updateEstimate($where, $param);


                $data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
                $data['link'] = base_url('welcome/pdfEstimate/') . base64_encode($estimate_id);
                $data['link_acc'] = base_url('welcome/estimateAccept/') . base64_encode($estimate_id);

                // get first message
                $estimate_estimate = $this->EstimateModal->getOneEstimate(['estimate_id' => $estimate_id]);

                $property = $this->PropertyModel->getOneProperty(array('property_id' => $estimate_estimate->property_id));
                if ($property->property_status == 2) {
                    $this->PropertyModel->updatePropertyStatus($estimate_estimate->property_id, 5);
                }
//              if ($data['customer_details']->customer_status == 7){
//                  $this->CustomerModel->updateCustomerStatus($estimate_estimate->property_id, 5);
//
//              }

                $data['msgtext_one'] = $estimate_estimate->notes;

                $where_company = array('company_id' => $company_id);

                $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
                $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;

                $body = $this->load->view('admin/estimate/estimate_email', $data, true);

                // echo $body;

                $where_company['is_smtp'] = 1;

                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);

                if (!$company_email_details) {


                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();

                }


                $res = Send_Mail_dynamic($company_email_details, $data['customer_details']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Estimate Details', $data['customer_details']->secondary_email);
                //var_dump($res);


            }
        }

        if (isset($res)) {
            echo 1;
        } else {
            echo 0;
        }
    }


    public function deletemultipleEstimates($value = '')
    {
        $estimates_ids = $this->input->post('estimates_ids');
        if (!empty($estimates_ids)) {
            foreach ($estimates_ids as $key => $value) {

                $where = array('estimate_id' => $value);
                $this->EstimateModal->deleteEstimate($where);
            }
            echo 1;
        } else {
            echo 0;
        }
    }

    public function bulkRenewalProgramsList()
    {
        $where = array('company_id' => $this->session->userdata['company_id'], 'program_active' => 1, 'ad_hoc' => 0);

        $data['programData'] = $this->ProgramModel->get_all_program($where);
        if (!empty($data['programData'])) {
            foreach ($data['programData'] as $key => $value) {

                $data['programData'][$key]->job_id = $this->ProgramModel->getProgramAssignJobs(array('program_id' => $value->program_id));

                $data['programData'][$key]->property_details = $this->ProgramModel->getAllproperty(array('program_id' => $value->program_id));

            }

        }


        $page["active_sidebar"] = "estimatenav";
        $page["page_name"] = 'Bulk Program Renewal Select';
        $page["page_content"] = $this->load->view("admin/estimate/select_program_renewal", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function addBulkRenewalProgram($programID = NULL)
    {
        if (empty($programID)) {
            $programID = $this->uri->segment(4);
        }
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['source_list'] = $this->SourceModel->getAllSource($where);
        $data['users'] = $this->Administrator->getAllAdmin($where);
        $source = [];
        foreach ($data['users'] as $user) {
            $source = (object)array(
                'source_name' => $user->user_first_name . ' ' . $user->user_last_name,
                'user_id' => $user->user_id,
                'source_id' => $user->id,
            );
            array_push($data['source_list'], $source);
        }

        // go ahead and override the where here so we get the correct info - we just needed the one above for the source list
        $where = array('property_tbl.company_id' => $this->session->userdata['company_id']);

        $data['joblist'] = $this->ProgramModel->getJobList(array('company_id' => $this->session->userdata['company_id']));
        $data['propertylist'] = $this->PropertyModel->get_all_list_properties(array('property_tbl.company_id' => $this->session->userdata['company_id'], 'property_status' => 1));
        $data['programData'] = $this->ProgramModel->getProgramDetail($programID);
        $selecteddata = $this->ProgramModel->getSelectedJobsAnother($programID);
        $data['selectedpropertylist'] = $this->ProgramModel->getSelectedPropertyForBulkEstimates($programID);
        $where2 = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where2);
        if (count($data['selectedpropertylist']) == 0) {
            $tmpPropertyIds = $this->EstimateModal->getEstimatePropertiesById($programID);
            $data['selectedpropertylist'] = array_map(function ($id) {
                $r = $this->PropertyModel->getPropertyDetail($id);
                return (object)$r;
            }, $tmpPropertyIds);
        }
        $data['selectedjobid'] = array();
        $data['selectedjobname'] = array();
        $data['selectedproperties'] = array();

        if (!empty($selecteddata)) {
            foreach ($selecteddata as $value) {
                $data['selectedjobid'][] = $value->job_id;
                $data['selectedjobname'][] = $value->job_name;
            }
        }
        if (!empty($data['selectedpropertylist'])) {
            foreach ($data['selectedpropertylist'] as $value) {
                $value->program_id = $programID;
                $data['selectedproperties'][] = $value->property_id;
            }

            foreach ($data['selectedproperties'] as $key => $value) {
                $customerId = $this->PropertyModel->getSelectedCustomer($value);
                if (!empty($customerId)) {
                    $customer = $this->CustomerModel->getCustomerDetail($customerId[0]->customer_id);

                } else {
                    $customer = array();
                }
                $data['selectedpropertylist'][$key]->customer_details = $customer;
            }
        }
        $coupon_where = array(
            'company_id' => $this->session->userdata['company_id'],
            'type' => 0
        );
        $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);
        $data['selecteddata'] = $selecteddata;

        // Code rewrite to match editProgram
        // foreach($data['selectedpropertylist'] as $prop)
        // {
        //   if(isset($prop->customer_details['customer_id']))
        //   {
        //     $where = array(
        //       'property_id' => $prop->property_id,
        //       'program_id' => $prop->program_id,
        //       'customer_id' => $prop->customer_details['customer_id']
        //     );
        //   } else {
        //     $where = array(
        //       'property_id' => $prop->property_id,
        //       'program_id' => $prop->program_id
        //     );
        //   }
        //   $prop->priceOverrideData = $this->EstimateModal->getProgramPropertyJobPriceOverrides($where);
        //   // die(print_r($prop->priceOverrideData));
        // }

        $propertyJobPriceOverrides = (array)[];
        if (!empty($selecteddata) && !empty($data['selectedpropertylist'])) {
            foreach ($data['selectedpropertylist'] as $prop) {

                $tmpProp = (object)[];
                $tmpProp->property_id = $prop->property_id;
                $tmpProp->program_id = $prop->program_id;
                $tmpProp->jobs = (array)[];
                $where = array(
                    'property_id' => $tmpProp->property_id,
                    'program_id' => $tmpProp->program_id
                );
                $results = $this->EstimateModal->getProgramPropertyJobPriceOverrides($where);
                $price_set_flag = null;
                if (!empty($results)) {
                    foreach ($selecteddata as $job) {
                        $jobDetails = (object)[];
                        $jobDetails->job_id = $job->job_id;
                        foreach ($results as $result) {
                            if ($result->job_id == $job->job_id && isset($result->is_price_override_set)) {
                                $jobDetails->is_price_override_set = $result->is_price_override_set;
                                $jobDetails->price_override = $result->price_override;
                                $price_set_flag = 1;
                            }
                        }
                        array_push($tmpProp->jobs, $jobDetails);
                    }
                }
                $tmpProp->is_job_price_override_set = $price_set_flag;
                array_push($propertyJobPriceOverrides, $tmpProp);
            }
        }
        $data['propertyJobPriceOverrides'] = $propertyJobPriceOverrides;

        //die(print_r(json_encode($data['selectedpropertylist'][0])));
        // die(print_r(json_encode($data['selecteddata'])));
        // die(print_r(json_encode($data['propertyJobPriceOverrides'][2])));

        $page["active_sidebar"] = "estimatenav";
        $page["page_name"] = "Bulk Renewal";
        $page["page_content"] = $this->load->view("admin/estimate/add_bulk_renewal", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }

    public function addBulkRenewalProgramData($program_id)
    {
        $data = $this->input->post();

        //Validate Form Data
        $this->form_validation->set_rules('program_name', 'Name', 'required');
        $this->form_validation->set_rules('program_price', 'Price', 'required');
        $this->form_validation->set_rules('program_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('program_job', 'Service', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->addBulkRenewalProgram($program_id);
        } else {
            if (isset($data['propertylistarray_temp']) && !is_array($data['propertylistarray_temp'])) {
                $data['propertylistarray_temp'] = explode(',', $data['propertylistarray_temp']);
            }
            $user_id = $this->session->userdata['user_id'];
            $company_id = $this->session->userdata['company_id'];
            $tmpProgData = $this->ProgramModel->getProgramDetail($program_id);
            //Set price strings array that will be used when setting program name
            $pricing_strs = array('One Time Project Invoicing', 'Invoiced at Job Completion', 'Manual Billing');
            //Create Program array
            $program = array();
            $jobsModded = ($data['program_job_original'] !== $data['program_job']) ? true : false;
            //$priceModded = ($tmpProgData['program_price'] !== $data['program_price']) ? true : false;
            $custom_name_set = ($data['program_name'] != $data['original_program_name']) ? true : false;
            if ($custom_name_set) {
                if (preg_match("/($pricing_strs[0]|$pricing_strs[1]|$pricing_strs[2])/i", $data['program_name'])) {
                    $program_name = $data['program_name'];
                } else {
                    $program_name = $data['program_name'] . ' - ' . $pricing_strs[$data['program_price'] - 1];
                }
            } elseif ($jobsModded) {
                $diffJobs = (array)[];
                //Get addtional added job names, if any
                $originalJobs = explode(',', $data['program_job_original']);
                $moddedJobs = explode(',', $data['program_job']);
                foreach ($originalJobs as $job) {
                    if (!(in_array($job, $moddedJobs))) {
                        array_push($diffJobs, $job);
                    }
                }
                foreach ($moddedJobs as $job) {
                    if (!(in_array($job, $originalJobs))) {
                        array_push($diffJobs, $job);
                    }
                }
                if (count($diffJobs) > 0) {
                    $jobNames = (array)[];
                    foreach ($diffJobs as $job) {
                        $r = $this->JobModel->getOneJob(array('job_id' => $job));
                        array_push($jobNames, $r->job_name);
                    }
                    $str_append = implode('+', $jobNames);
                    $program_name = (strpos($data['program_name'], ' - ') !== false) ? trim(explode(' - ', $data['program_name'])[0]) . '+' . $str_append . ' - ' . $pricing_strs[$data['program_price'] - 1] . ' - Copy' : $data['program_name'] . '+' . $str_append . ' - ' . $pricing_strs[$data['program_price'] - 1] . ' - Copy';
                } else {
                    $program_name = (strpos($data['program_name'], ' - ') !== false) ? trim(explode(' - ', $data['program_name'])[0]) . ' - ' . $pricing_strs[$data['program_price'] - 1] . ' - Copy' : $data['program_name'] . ' - ' . $pricing_strs[$data['program_price'] - 1] . ' - Copy';
                }
            } else {
                $program_name = $data['program_name'];
            }

            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'program_name' => $program_name,
                'program_price' => $data['program_price'],
                'program_notes' => $data['program_notes'],
                'parent_program_id' => $data['OriginaProgrammID']
                //'program_job' => $data['program_job']
            );
            //Create Program
            $program['program_id'] = $this->ProgramModel->insert_program($param);
            // create and add program jobs from joblistarray
            if (!empty($data['program_job'])) {
                $n = 1;
                if (!is_array($data['program_job'])) {
                    $data['program_job'] = explode(",", $data['program_job']);
                }
                foreach ($data['program_job'] as $k => $val) {
                    $param2 = array(
                        'program_id' => $program['program_id'],
                        'job_id' => $val,
                        'priority' => $n
                    );
                    //Assign jobs to program
                    $result1 = $this->ProgramModel->assignProgramJobs($param2);

                    $n++;
                }
            }
            // Remove unselected properties from propertylistarray
            $data['propertylistarray'] = json_decode($data['propertylistarray']);
            $propertyListArrayRebuild = (array)[];
            foreach ($data['propertylistarray'] as $property) {
                foreach ($data['propertylistarray_temp'] as $pTemp) {
                    if ($property->property_id == $pTemp) {
                        array_push($propertyListArrayRebuild, $property);
                    }
                }
            }
            $data['propertylistarray'] = $propertyListArrayRebuild;
            $data['propertylistarray'] = json_encode(array_values($data['propertylistarray']));
            // if properties then assign program to properties
            if (isset($data['propertylistarray']) && !empty($data['propertylistarray'])) {
                $program['properties'] = array();
                foreach (json_decode($data['propertylistarray']) as $value) {
                    // Create New Estimate and Service Level Price Overrides
                    $dtSelected = json_decode($data['dtSelectedRows']);
                    $propCustDetails = null;
                    for ($z = 0; $z < count($dtSelected); $z++) {
                        if ($dtSelected[$z]->property_id == $value->property_id) {
                            $propCustDetails = $dtSelected[$z];
                            break;
                        }
                    }
                    $data['customer_email'] = $propCustDetails->customer_details->email ?? '';
                    // need to do some logic on the source before we send that through
                    $source_data = $this->EstimateModal->getSource($data['property_id']);
                    $source_from_property_info = $source_data[0]->source;
                    if ($data["source"] == "") {
                        // if they did not pick the source we need to deafult it to the property source
                        $source_for_estimate = $source_from_property_info;
                    } else {
                        $source_for_estimate = $data["source"];
                    }
                    $estimateParam = array(
                        'company_id' => $company_id,
                        'customer_id' => $propCustDetails->customer_details->customer_id,
                        'property_id' => $value->property_id,
                        'estimate_date' => date("Y-m-d"),
                        'program_id' => $program['program_id'],
                        'status' => $data['status'],
                        'sales_rep' => '',
                        'estimate_created_date' => date("Y-m-d H:i:s"),
                        'estimate_update' => date("Y-m-d H:i:s"),
                        'notes' => $data['program_notes'],
                        'source' => $source_for_estimate,
                        'signwell_status' => $data['signwell_status'],
                    );
                    $estimate_id = $this->EstimateModal->CreateOneEstimate($estimateParam);
                    $messages = (array)[];
                    if ($estimate_id) {
                        if (isset($data['joblistarray']) && !empty($data['joblistarray'])) {
                            $prop_job_list = array_filter(json_decode($data['joblistarray']), function ($j) use ($estimateParam) {
                                if ($estimateParam['property_id'] == $j->property_id) {
                                    return $j;
                                }
                            });
                            foreach ($prop_job_list as $job) {
                                $estimateParam2 = array(
                                    'estimate_id' => $estimate_id,
                                    'customer_id' => $estimateParam['customer_id'],
                                    'property_id' => $job->property_id,
                                    'program_id' => $program['program_id'],
                                    'job_id' => $job->job_id,
                                    'price_override' => $job->price_override,
                                    'is_price_override_set' => $job->is_price_override_set,
                                    'created_at' => date("Y-m-d H:i:s")
                                );
                                $this->EstimateModal->CreateOneEstimatePriceOverRide($estimateParam2);
                                // Check if record exists
                                $where = array(
                                    'program_id' => $program['program_id'],
                                    'job_id' => $job->job_id,
                                    'property_id' => $job->property_id
                                );
                                $queryResult = $this->ProgramModel->getProgramPropertyJobsOverrides($where);
                                if (!empty($queryResult)) {
                                    $updateData = array(
                                        'price_override' => $job->price_override,
                                        'is_price_override_set' => $job->is_price_override_set,
                                    );
                                    $this->ProgramModel->updateProgramPropertyJobOverrides($updateData, $where);
                                } else {
                                    $createData = array(
                                        'program_id' => $program['program_id'],
                                        'job_id' => $job->job_id,
                                        'property_id' => $job->property_id,
                                        'price_override' => $job->price_override,
                                        'is_price_override_set' => $job->is_price_override_set,
                                    );
                                    $this->ProgramModel->insert_price_override($createData);
                                }
                            }
                        }

                        if (array_key_exists("assign_onetime_coupons", $data)) {
                            $coupon_ids_arr = $data['assign_onetime_coupons'];
                            foreach ($coupon_ids_arr as $coupon_id) {
                                $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                                $params = array(
                                    'coupon_id' => $coupon_id,
                                    'estimate_id' => $estimate_id,
                                    'coupon_code' => $coupon_details->code,
                                    'coupon_amount' => $coupon_details->amount,
                                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                    'coupon_type' => $coupon_details->type,
                                    'expiration_date' => $coupon_details->expiration_date
                                );
                                $this->CouponModel->CreateOneCouponEstimate($params);
                            }
                        }
                        // check global coupons & assign if so
                        $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $estimateParam['customer_id']));
                        if (!empty($coupon_customers)) {
                            foreach ($coupon_customers as $coupon_customer) {
                                $coupon_id = $coupon_customer->coupon_id;
                                $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                                $params = array(
                                    'coupon_id' => $coupon_id,
                                    'estimate_id' => $estimate_id,
                                    'coupon_code' => $coupon_details->code,
                                    'coupon_amount' => $coupon_details->amount,
                                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                    'coupon_type' => $coupon_details->type,
                                    'expiration_date' => $coupon_details->expiration_date
                                );
                                $this->CouponModel->CreateOneCouponEstimate($params);
                                // $coupon_id = $coupon_customer->coupon_id;
                                // $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                                // $params = array(
                                //     'coupon_id' => $coupon_id,
                                //     'invoice_id' => $invoice_id,
                                //     'coupon_code' => $coupon_details->code,
                                //     'coupon_amount' => $coupon_details->amount,
                                //     'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                //     'coupon_type' => $coupon_details->type
                                // );
                                // $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                            }
                        }
                        //handle status if status == send estimate
                        if ($data['status'] == 1 && $data['customer_email'] != '') {
                            if (isset($data['program_notes']) && $data['program_notes'] != '') {
                                $email_data['msgtext'] = $data['program_notes'];
                            } else {
                                $email_data['msgtext'] = '';
                            }

                            $email_data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($estimateParam['customer_id']);
                            $email_data['link'] = base_url('welcome/pdfEstimate/') . base64_encode($estimate_id);
                            $email_data['link_acc'] = base_url('welcome/estimateAccept/') . base64_encode($estimate_id);
                            $where_company = array('company_id' => $company_id);
                            $email_data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
                            $email_data['setting_details']->company_logo = ($email_data['setting_details']->company_resized_logo != '') ? $email_data['setting_details']->company_resized_logo : $email_data['setting_details']->company_logo;
                            if ($data["signwell_status"] == "1") {
                                $pdf_link_for_signwell = base_url('welcome/pdfEstimateSignWell/') . base64_encode($estimate_id);
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => 'https://www.signwell.com/api/v1/documents/',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => '{
                                    "test_mode": ' . SIGNWELL_TEST_MODE . ',
                                    "name": "estimate_' . $estimate_id . '",
                                    "files": [
                                        {
                                            "name": "estimate_' . $estimate_id . '.pdf",
                                            "file_url": "' . $pdf_link_for_signwell . '"
                                        }
                                    ],
                                    "recipients": [
                                        {
                                            "send_email": false,
                                            "id": "1",
                                            "name": "' . $email_data['customer_details']->first_name . ' ' . $email_data['customer_details']->last_name . '",
                                            "email": "' . $email_data['customer_details']->email . '"
                                        }
                                    ],
                                    "draft": false,
                                    "reminders": true,
                                    "apply_signing_order": false,
                                    "embedded_signing": false,
                                    "embedded_signing_notifications": false,
                                    "text_tags": true,
                                    "allow_decline": true,
                                    "redirect_url": "' . base_url('welcome/set_signwell_estimate_accepted/' . $estimate_id) . '",
                                    "decline_redirect_url": "' . base_url('welcome/set_signwell_estimate_rejected/' . $estimate_id) . '"
                                }',
                                    CURLOPT_HTTPHEADER => array(
                                        'accept: application/json',
                                        'content-type: application/json',
                                        'X-Api-Key: ' . $email_data['setting_details']->signwell_api_key
                                    ),
                                ));

                                $response = curl_exec($curl);

                                curl_close($curl);
                                $response_object = json_decode($response);
                                if ($response_object->message == "") {
                                    // we should now have an ID for this document within SignWell - need to save that to the estimate in the DB
                                    $this->EstimateModal->updateEstimateSignWellID($estimate_id, $response_object->id);
                                }
                            } else {
                                $body = $this->load->view('admin/estimate/estimate_email', $email_data, true);
                                $where_company['is_smtp'] = 1;
                                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);

                                if (!$company_email_details) {
                                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                                }

                                $res = Send_Mail_dynamic($company_email_details, $email_data['customer_details']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Estimate Details', $email_data['customer_details']->secondary_email);
                            }

                        }

                        // Estimate End
                        // if ($data['status'] == 1 && $propCustDetails->customer_details->email != '')
                        // {
                        //   // Handle email and text notifications
                        //   $customer_id = $this->CustomerModel->getOnecustomerPropert(array('property_id'=>$value->property_id));
                        //   $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id'=>$customer_id->customer_id));
                        //   $emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id' =>$customer_id->customer_id,'is_email'=>1,'program_id'=>$result,'property_id' =>$value->property_id));
                        //   $where = array('company_id' =>$this->session->userdata['company_id']);
                        //   $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);
                        //   $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);
                        //   $emaildata['assign_date'] = date("Y-m-d H:i:s");
                        //   $where['is_smtp'] = 1;
                        //   $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                        //   $body  = $this->load->view('email/program_email',$emaildata,true);
                        //   if (!$company_email_details)
                        //   {
                        //     $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                        //   }
                        //   if ($emaildata['company_email_details']->program_assigned_status==1)
                        //   {
                        //     $res =   Send_Mail_dynamic($company_email_details,$emaildata['customerData']->email,array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Program Assigned',$emaildata['customerData']->secondary_email);
                        //   }
                        //   if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->program_assigned_status_text==1 && $emaildata['customerData']->is_mobile_text==1)
                        //   {
                        //     $text_res = Send_Text_dynamic($emaildata['customerData']->phone,$emaildata['company_email_details']->program_assigned_text,'Program Assigned');
                        //   }
                        //   // End Email/Text
                        // }

                        // apply assigned coupons

                        if ($response_object->message != "") {
                            // this means that the SignWell api got an error and nothing got sent over to them
                            array_push($messages, '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Estimate </strong>created successfully in Spraye but not at SignWell. (SignWell error message: ' . $response_object->message . ')</div>');
                        } else {
                            array_push($messages, '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> added successfully</div>');
                        }
                    } else {
                        array_push($messages, '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> not added.</div>');
                    }
                }
                if (isset($messages) && count($messages) > 0) {
                    $final_message = implode('<br>', $messages);
                    $this->session->set_flashdata('message', $final_message);
                    redirect("admin/Estimates");
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> not added.</div>');
                    redirect("admin/Estimates");
                }
            }
        }
    } // Function End

    /** This is a test query to return program property job price override(s) **/
    /** the model will search based on any combination of columns specified **/
    /** the returned results may be viewed at: **/
    /** https://emerald-dev7.blayzer.com/admin/Estimates/testPriceOverrideReturn **/
    public function testPriceOverrideReturn()
    {
      $arr = array(
        'program_id' => '1472',
        'property_id' => '25785',
        // 'job_id' => '1186'
      );
      $results = $this->ProgramModel->getProgramPropertyJobsOverrides($arr);
      //die(print_r(json_encode($results)));
      return $results;
    }

    public function ajaxGetTotalPipeline()
    {
        $total_pipeline = 0;
        $company_id = $this->session->userdata['company_id'];
        $total_pipeline = $this->dataCalculate(getEstimateAmount(array('status' => 0, 't_estimate.company_id' => $company_id)));
        echo json_encode(['status' => 'success', 'total_pipeline' => number_format($total_pipeline, 2)]);

    }

    public function ajaxGetTotalAccepted()
    {
        $total_accepted = 0;
        $company_id = $this->session->userdata['company_id'];
        $total_accepted = $this->dataCalculate(getEstimateAmount(array('status' => 2, 't_estimate.company_id' => $company_id)));
        echo json_encode(['status' => 'success', 'total_accepted' => number_format($total_accepted, 2)]);

    }

    public function ajaxGetTotalPending()
    {
        $total_pending = 0;
        $company_id = $this->session->userdata['company_id'];
        $total_pending = $this->dataCalculate(getEstimateAmount(array('status' => 1, 't_estimate.company_id' => $company_id)));
        echo json_encode(['status' => 'success', 'total_pending' => number_format($total_pending, 2)]);

    }

    public function createNote($data = NULL){
        $data = (empty($data)) ? $this->input->post() : $data;
        // die(print_r($data));
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';
        if($data['note_property_id'] == 0){
            $data['note_property_id'] = NULL;
        }
        if(!empty($data['note_contents']) && $data['note_contents'] != ''){
            $params = array(
                'note_user_id' => $this->session->userdata['id'],
                'note_company_id' => $this->session->userdata['company_id'],
                'note_category' => (isset($data['note_property_id'])) ? 0 : 1,
                'note_property_id' => $data['note_property_id'] ?? NULL,
                'note_customer_id' => $data['note_customer_id'] ?? NULL,
                'note_contents' => nl2br($data['note_contents']),
                'note_due_date' => $data['note_due_date'] ?? NULL,
                'note_assigned_user' => $data['note_assigned_user'],
                'note_type' => $data['note_type'] ?? 0,
                'include_in_tech_view' => (isset($data['include_in_tech_view'])) ? 1 : 0,
            );
  
  
  
            if($data['note_category'] == 2)
            {
            $params['note_category'] = 2;
            }
            $noteId = $this->CompanyModel->addNote($params);
            if($noteId && isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0])) 
            {
                $fileStatusMsg = $this->addNoteFiles($noteId);
            }
            // if($noteId && isset($fileStatusMsg) && $fileStatusMsg){
            if($noteId){
  
              if(!empty($params['note_assigned_user'])){
                $note_creator = $this->Administrator->getOneAdmin(array('id' => $params['note_user_id']));
                $note_type = $this->CompanyModel->getOneNoteTypeName($params['note_type']);
                $email_array = array(
                'note_creator' => $note_creator->user_first_name.' '.$note_creator->user_last_name,
                'note_type' => $note_type,
                'note_due_date' => $params['note_due_date'] ?? 'None',
                'note_contents' => $params['note_contents']
                );
                $where = array('company_id' => $this->session->userdata['company_id']);
                $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);
  
                $subject =  'New Note Assignment';
                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                $note_assigned_user = $this->Administrator->getOneAdmin(array('id' => $params['note_assigned_user']));
                $email_array['name'] = $note_assigned_user->user_first_name.' '.$note_assigned_user->user_last_name;
                // die(print_r(json_encode($email_array)));
                $body  = $this->load->view('email/note_email',$email_array,TRUE);
                $res =   Send_Mail_dynamic( $company_email_details, $note_assigned_user->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, $subject);
              }
  
    
  
            //     $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> added successfully</div>');
            //     redirect($referer_path);
            // } else {
            //     $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> not added.</div>');
            //     redirect($referer_path);
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Something went really <strong>WRONG!</strong></div>');
            redirect($referer_path);
        }
    }

    public function calculateCustomerCouponCost($param = array())
    {
        $total_cost = $param['cost'];
        $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $param['customer_id']));

        if (!empty($coupon_customers)) {
            foreach ($coupon_customers as $coupon_customer) {

                $coupon_id = $coupon_customer->coupon_id;
                $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

                // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                $expiration_pass = true;
                if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
                    $coupon_expiration_date = strtotime($coupon_details->expiration_date);

                    $now = time();
                    if ($coupon_expiration_date < $now) {
                        $expiration_pass = false;
                    }
                }

                if ($expiration_pass == true) {
                    if ($coupon_details->amount_calculation == 0) {
                        $discount_amm = (float)$coupon_details->amount;

                        if (($total_cost - $discount_amm) < 0) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                        }

                    } else {
                        $percentage = (float)$coupon_details->amount;
                        $discount_amm = (float)$total_cost * ($percentage / 100);

                        if (($total_cost - $discount_amm) < 0) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                        }

                    }
                }
            }
        }

        return number_format($total_cost, 2, '.', ',');
    }

    public function QuickBookInv($param = array())
    {

        $customer_details = $this->CustomerModel->getCustomerDetail($param['customer_id']);

        if ($customer_details['quickbook_customer_id'] != 0) {
            $quickBookCustomerDetails = $this->getOneQuickBookCustomer($customer_details['quickbook_customer_id']);

            if ($quickBookCustomerDetails) {
                $param['quickbook_customer_id'] = $customer_details['quickbook_customer_id'];

                $result = $this->createInvoiceInQuickBook($param);

                if ($result['status'] == 201) {
                    return $result['result'];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkQuickbook()
    {
        $where = array(
            'company_id' => $this->session->userdata['company_id'],
            'is_quickbook' => 1,
            'quickbook_status' => 1,
        );

        $company_details = $this->CompanyModel->getOneCompany($where);

        if ($company_details) {

            try {
                $oauth2LoginHelper = new OAuth2LoginHelper($company_details->quickbook_client_id, $company_details->quickbook_client_secret); // clint id , clint sceter
                $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($company_details->refresh_token_key);
                $accessTokenValue = $accessTokenObj->getAccessToken();
                $refreshTokenValue = $accessTokenObj->getRefreshToken();

                $post_data = array(
                    'access_token_key' => $accessTokenValue,
                    'refresh_token_key' => $refreshTokenValue,

                );

                $this->CompanyModel->updateCompany($where, $post_data);

                $company_details->access_token_key = $accessTokenValue;

                $company_details->refresh_token_key = $refreshTokenValue;
                return $company_details;
            } catch (Exception $ex) {

                return false;
            }
        } else {
            return false;
        }
    }

    public function getOneQuickBookCustomer($quickbook_customer_id)
    {

        $company_details = $this->checkQuickbook();
        if ($company_details) {

            try {
                $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $company_details->quickbook_client_id,
                    'ClientSecret' => $company_details->quickbook_client_secret,
                    'accessTokenKey' => $company_details->access_token_key,
                    'refreshTokenKey' => $company_details->refresh_token_key,
                    'QBORealmID' => $company_details->qbo_realm_id,
                    'baseUrl' => "Production",
                ));

                $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

                $entities = $dataService->Query("SELECT * FROM Customer where Id='" . $quickbook_customer_id . "'");
                $error = $dataService->getLastError();
                if ($error) {
                    $return_error = '';
                    $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                    return false;
                } else {

                    if (!empty($entities)) {

                        $theCustomer = reset($entities);
                        return $theCustomer;
                    } else {
                        return false;
                    }
                }
            } catch (Exception $ex) {

                return false;
            }
        } else {

            return false;
        }
    }

    public function createInvoiceInQuickBook($param)
    {

        $company_details = $this->checkQuickbook();

        if ($company_details) {

            try {

                $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $company_details->quickbook_client_id,
                    'ClientSecret' => $company_details->quickbook_client_secret,
                    'accessTokenKey' => $company_details->access_token_key,
                    'refreshTokenKey' => $company_details->refresh_token_key,
                    'QBORealmID' => $company_details->qbo_realm_id,
                    'baseUrl' => "Production",
                ));

                $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

                $dataService->throwExceptionOnError(true);

                $details = getVisIpAddr();

                $all_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $param['invoice_id']));

                $all_invoice_coupons = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $param['invoice_id']));

                $description = 'Service Name: ' . $param['job_name'] . '. Service Description: ' . $param['actual_description_for_QBO'] . '. Service Address: ' . $param['property_street'] . '.';

                $line_ar[] = array(
                    "Description" => $description,
                    "Amount" => $param['cost'],
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => array(
                        "TaxCodeRef" => array(
                            "value" => $details->geoplugin_countryCode == 'IN' ? 3 : 'TAX',
                            // "value" =>  'TAX'
                        ),
                    ),
                );

                if ($all_sales_tax) {

                    foreach ($all_sales_tax as $key => $value) {
                        $line_ar[] = array(
                            "Description" => 'Sales Tax: ' . $value['tax_name'] . ' (' . floatval($value['tax_value']) . '%) ',
                            "Amount" => $value['tax_amount'],
                            "DetailType" => "SalesItemLineDetail",
                            "SalesItemLineDetail" => array(
                                "TaxCodeRef" => array(
                                    "value" => $details->geoplugin_countryCode == 'IN' ? 3 : 'TAX',
                                    // "value" =>  'TAX'
                                ),
                            ),
                        );
                    }
                }

                $invoice_arr = array(
                    "AllowOnlineCreditCardPayment" => true,
                    "DocNumber" => $param['invoice_id'],
                    "TxnDate" => $param['invoice_date'],
                    "Line" => $line_ar,
                    "CustomerRef" => array(
                        "value" => $param['quickbook_customer_id'],
                    ),
                );

                if ($param['customer_email'] != '') {

                    $invoice_arr['BillEmail'] = array(
                        "Address" => $param['customer_email'],
                    );
                    $invoice_arr['EmailStatus'] = "NeedToSend";
                }

                $theResourceObj = Invoice::create($invoice_arr);

                $resultingObj = $dataService->Add($theResourceObj);

                $error = $dataService->getLastError();
                if ($error) {
                    $return_error = '';
                    $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";
                    return array('status' => 400, 'msg' => 'Invoice NOT added successfully', 'result' => $return_error);
                } else {

                    return array('status' => 201, 'msg' => 'Invoice added successfully', 'result' => $resultingObj->Id);
                }
            } catch (Exception $ex) {

                return array('status' => 400, 'msg' => $ex->getMessage(), 'result' => '');
            }
        } else {

            return array('status' => 400, 'msg' => 'please intigrate quickbook account', 'result' => '');
        }
    }

    public function sendEstimateToSignWell($value = '')
    {

        $company_id = $this->session->userdata['company_id'];
        $group_id_array = $this->input->post('group_id_array');
        $customer_message = $this->input->post('customer_message');
        $customer_message = "Hi,<br/><br/> Please review and complete this document. You can click on the document below to get started.<br/><br/><br/>" . str_replace(array("\r\n", "\r", "\n"), "<br />", $customer_message[0]);
        //die($customer_message);
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $error_message = '';
        if (!empty($group_id_array)) {
          $status_array = array();
          foreach ($group_id_array as $key => $value) {
            $in_ct = explode(':', $value);
            $estimate_id =  $in_ct[0];
            $customer_id =  $in_ct[1];
            $data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);

            
            $pdf_link = base_url('welcome/pdfEstimateSignWell/').base64_encode($estimate_id);
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://www.signwell.com/api/v1/documents/',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                            "test_mode": '.SIGNWELL_TEST_MODE.',
                            "name": "estimate_'.$estimate_id.'",
                            "files": [
                                {
                                    "name": "estimate_' . $estimate_id . '.pdf",
                                    "file_url": "' . $pdf_link . '"
                                }
                            ],
                            "recipients": [
                                {
                                    "send_email": false,
                                    "id": "1",
                                    "name": "' . $data['customer_details']->first_name . ' ' . $data['customer_details']->last_name . '",
                                    "email": "' . $data['customer_details']->email . '"
                                }
                            ],
                            "draft": false,
                            "reminders": true,
                            "apply_signing_order": false,
                            "embedded_signing": false,
                            "embedded_signing_notifications": false,
                            "text_tags": true,
                            "allow_decline": true,
                            "redirect_url": "' . base_url('welcome/set_signwell_estimate_accepted/' . $estimate_id) . '",
                            "decline_redirect_url": "' . base_url('welcome/set_signwell_estimate_rejected/' . $estimate_id) . '",
                            "message": "' . $customer_message . '"
                        }',

              CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'content-type: application/json',
                'X-Api-Key: '.$data['setting_details']->signwell_api_key
              ),
            ));
            
            $response = curl_exec($curl);

            curl_close($curl);
            $response_object = json_decode($response);
            //var_dump($response_object->recipients[0]);
            //exit();

            if(isset($response_object->recipients)) {
                $error_message .= $response_object->recipients[0]->message;
            } else {
                $error_message .= $response_object->message;

                
            }
            if ($error_message == ''){
                //Update estimate info to mark as sent.
                $where = array('estimate_id' => $estimate_id);
                $param = array('status' => 1, 'estimate_update' => date("Y-m-d H:i:s"));
                $this->EstimateModal->updateEstimate($where, $param);
            }
            echo json_encode($status_array);
		  }
        } else {
            $status_array[] = array('status' => 'fail', 'message' => 'You must select at least one(1) estimate to send to SignWell', 'estimate_id' => '');
            return json_encode($status_array);
        }
    }

    public function ajaxGetEstimateProp()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'property_title',
            2 => 'property_address',
            3 => 'customer_name',
            4 => 'category_area_name',
            5 => 'property_type',
            6 => 'price_override_checkbox',
            7 => 'property_zip',
            8 => 'property_city',
            9 => 'property_status',
            10 => 'customer_id',
            11 => 'email',
            12 => 'source'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array('property_tbl.company_id' => $company_id, 'property_status !=' => 0);


        $data = array();

        $where_like = array();
        $where_in = array();
        if (is_array($this->input->post('columns'))) {
            $columns = $this->input->post('columns');
            $colm_num = 0;
            foreach ($columns as $column) {
                if (isset($column['search']['value']) && !empty($column['search']['value'])) {
                    if (strpos($column['search']['value'], ',') !== false && $colm_num == 2) {
                        $col = $column['data'];
                        $val = $column['search']['value'];
                        $where_in[$col] = explode(',', $val);
                        // $dbg = explode(',', $val);
                    } else {
                        $col = $column['data'];
                        $val = $column['search']['value'];
                        $where_like[$col] = $val;
                    }
                }
                $colm_num++;
            }
        }

        // get data (2 separate fns for search and non search)
        if (empty($this->input->post('search')['value'])) {
            if (empty($where_in)) {
                $tempdata = $this->EstimateModal->getTableDataAjaxEstimateProp($where, $where_like, $limit, $start, $order, $dir, false);
                $var_total_item_count_for_pagination = $this->EstimateModal->getTableDataAjaxEstimateProp($where, $where_like, $limit, $start, $order, $dir, true);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            } else {
                $tempdata = $this->EstimateModal->getTableDataAjaxEstimateProp($where, $where_like, $limit, $start, $order, $dir, false, $where_in);
                $var_total_item_count_for_pagination = $this->EstimateModal->getTableDataAjaxEstimateProp($where, $where_like, $limit, $start, $order, $dir, true, $where_in);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            }

        } else {
            $search = $this->input->post('search')['value'];
            if (empty($where_in)) {
                $tempdata = $this->EstimateModal->getTableDataAjaxSearchEstimateProp($where, $where_like, $limit, $start, $order, $dir, $search, false);
                $var_total_item_count_for_pagination = $this->EstimateModal->getTableDataAjaxSearchEstimateProp($where, $where_like, $limit, $start, $order, $dir, $search, true);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            } else {

                $tempdata = $this->EstimateModal->getTableDataAjaxSearchEstimateProp($where, $where_like, $limit, $start, $order, $dir, $search, false, $where_in);
                $var_total_item_count_for_pagination = $this->EstimateModal->getTableDataAjaxSearchEstimateProp($where, $where_like, $limit, $start, $order, $dir, $search, true, $where_in);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            }
        }

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                //die(var_dump($tagSearch));

                $data[$i]['checkbox'] = '<input type="checkbox" name="property_id" value="' . $value->property_id . '" class="row_select" />';
                $data[$i]['property_title'] = '<a href="' . base_url("admin/editProperty/") . $value->property_id . '">' . $value->property_title . '</a>';
                $data[$i]['property_address'] = $value->property_address;
                $data[$i]['customer_name'] = '<a href="' . base_url("admin/editCustomer/") . $value->customer_id . '" >' . $value->customer_name . '</a>';
                $data[$i]['category_area_name'] = $value->category_area_name;
                $data[$i]['property_type'] = $value->property_type;
                $data[$i]['price_override_checkbox'] = '<input name="price_override" type="checkbox" value="' . $value->property_id . '" class="price_override" disabled />';
                $data[$i]['property_zip'] = $value->property_zip;
                $data[$i]['property_city'] = $value->property_city;
                $data[$i]['property_status'] = $value->property_status;
                $data[$i]['customer_id'] = $value->customer_id;
                $data[$i]['email'] = $value->email;
                $data[$i]['source'] = '<input type="hidden" value="' . $value->source . '" id="source_' . $value->property_id . '" />';
                $i++;
            }
        }

        $json_data = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $var_total_item_count_for_pagination, // "(filtered from __ total entries)"
            "recordsFiltered" => $var_total_item_count_for_pagination, // actual total that determines page counts
            "data" => $data
        );
        echo json_encode($json_data);
    }

    public function getAllProgramsByPricingForSelect() {
        $where = array('company_id' =>$this->session->userdata['company_id'], 'program_price'=>$this->input->post('pricing'));
        $data['programs'] = $this->EstimateModal->getAllProgramsByPricing($where);
        foreach ($data['programs'] as $key => $value) {
            if(strstr($value->program_name, '-Standalone Service')){
                unset($data['programs'][$key]);
            } else if (strstr($value->program_name, '- One Time Project Invoicing') && strstr($value->program_name, '+')){
                unset($data['programs'][$key]);
            } else if (strstr($value->program_name, '- Invoiced at Job Completion') && strstr($value->program_name, '+')){
                unset($data['programs'][$key]);
            } else if (strstr($value->program_name, '- Manual Billing') && strstr($value->program_name, '+')){
                unset($data['programs'][$key]);
            }
        }
        // now that we have them all - we need to turn them into the options and pass those back into the multiselect
        $options = '';
        foreach($data['programs'] as $p) {
            $options .= '<option value="'.$p->program_id.'">'.$p->program_name.'</option>';
        }
        $json_data = array("data"=>$options);
        echo json_encode($json_data);
    }

}