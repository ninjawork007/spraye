<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require_once APPPATH . '/third_party/sms/Send_Text.php';
require FCPATH . 'vendor/autoload.php';
require_once APPPATH . '/third_party/stripe-php/init.php';

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Invoice;


class Admin extends MY_Controller
{

    const PER_PAGE_ARR = [10, 20, 50, 100, 200];

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email') && isset($_SERVER['REQUEST_URI'])) {
            $actual_link = $_SERVER['REQUEST_URI'];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }
        $this->load->library('parser');
        $this->load->library('aws_sdk');
        $this->load->helper('text');
        $this->loadModel();
        $this->load->helper(array('form', 'url'));
        $this->load->helper('job_helper');
        $this->load->helper('customer_helper');
        $this->load->helper('invoice_helper');
        $this->load->library('form_validation');
        $this->load->helper('estimate_helper');
        $this->load->helper('cardconnect_helper');
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('form_validation_rule_helper');
        $this->load->helper('available_days_helper');

        $this->load->library('pagination');

        if (!$this->session->userdata('spraye_technician_login') && isset($_SERVER['REQUEST_URI'])) {
            $actual_link = $_SERVER['REQUEST_URI'];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *http://example.com/index.php/welcome
     * - or -
     *http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *Filename: /opt/lampp/htdocs/spraye_new_design/system/libraries/Form_validation.php
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    private function loadModel()
    {

        $this->load->model('AdminTbl_property_model', 'PropertyModel');
        $this->load->model('AdminTbl_program_model', 'ProgramModel');
        $this->load->model('AdminTbl_customer_model', 'CustomerModel');
        $this->load->model('AdminTbl_product_model', 'ProductModel');
        $this->load->model('Dashboard_model', 'DashboardModel');
        $this->load->model("Administrator");
        $this->load->model('Job_model', 'JobModel');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('AdminTbl_servive_area_model', 'ServiceArea');
        $this->load->model('Company_email_model', 'CompanyEmail');
        $this->load->model('Administratorsuper');
        $this->load->model('Invoice_model', 'INV');
        $this->load->model('Unassign_job_delete_model', 'UnassignJobDeleteModal');
        $this->load->model('Sales_tax_model', 'SalesTax');
        $this->load->model('Help_message', 'HelpMessage');
        $this->load->model('Property_sales_tax_model', 'PropertySalesTax');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');
        $this->load->model('Invoice_job_model', 'invoiceJob');
        $this->load->model('Data_table_manage_model', 'DataTableModel');
        $this->load->model('Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
        $this->load->model('Basys_request_modal', 'BasysRequest');
        $this->load->model('Cardconnect_model', 'CardConnectModel');
        $this->load->model('AdminTbl_coupon_model', 'CouponModel');
        $this->load->model('Estimate_model', 'EstimateModel');
        $this->load->model('Source_model', 'SourceModel');
        $this->load->model('Commissions_model', 'CommissionModel');
        $this->load->model('Bonuses_model', 'BonusModel');
        $this->load->model('Service_type_model', 'ServiceTypeModel');
        $this->load->model('Cancelled_services_model', 'CST');
        $this->load->model('AdminTbl_tags_model', 'TagsModel');
        $this->load->model('Payment_invoice_logs_model', 'PartialPaymentModel');
        $this->load->model('Notes_default_filters_model', 'NotesDefaultFilterModel');
    }

    // TEMP USE FOR NO-MAP ROUTING VIEW
    public function ajaxGetRouting()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'priority',
            2 => 'job_name',
            3 => 'pre_service_notification',
            4 => 'customers.first_name',
            5 => 'property_title',
            6 => '`property_tbl`.`yard_square_feet`',
            7 => 'completed_date_property',
            8 => 'completed_date_property_program',
            9 => 'service_due',
            10 => 'property_address',
            11 => 'property_type',
            12 => 'property_notes',
            13 => 'category_area_name',
            14 => 'program_name',
            15 => 'reschedule_message',
            16 => 'tags',
            17 => 'action'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status !=' => 0,
            'property_status !=' => 0,
        );

        $data = array();

        $where_like = array();
        $where_in = array();
        $tagSearch = "";
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
                        if ($col == "tags") {
                            $col = "property_tbl.tags";
                            $val = (int)$val;
                            $tag = $this->TagsModel->getOneTag(array('id' => $val));
                            if (!empty($tag)) {
                                $where_like[$col] = $tag->id;
                                $tagSearch = $tag->id;
                            } else {
                                $where_like[$col] = $val;
                            }
                        } else {
                            $where_like[$col] = $val;
                        }
                    }
                }
                $colm_num++;
            }
        }

        // get data (2 separate fns for search and non search)
        if (empty($this->input->post('search')['value'])) {
            if (empty($where_in)) {
                $tempdata = $this->DashboardModel->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            } else {
                $tempdata = $this->DashboardModel->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, false, $where_in);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, true, $where_in);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            }

        } else {
            $search = $this->input->post('search')['value'];
            if (empty($where_in)) {
                $tempdata = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            } else {

                $tempdata = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false, $where_in);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true, $where_in);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            }
        }

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                //die(var_dump($tagSearch));
                if (isset($tagSearch) && $tagSearch != "") {
                    $property_tags = explode(',', $value->tags);
                    if (!in_array($tagSearch, $property_tags)) {
                        unset($tempdata[$key]);
                        continue;
                    }
                }
                // $generate_row = true;
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $value->property_id,
                );
                $assign_table_data = $this->Tech->GetOneRow($arrayName); // select * from technician_job_assign where __
                $value->mode = '';
                $value->reschedule_message = '';
                $concat_is_rescheduled = 0;
                if ($assign_table_data) {
                    if ($assign_table_data->is_job_mode == 2) {
                        $concat_is_rescheduled = 2;
                        $value->mode = 'Rescheduled';
                        $value->reschedule_message = $assign_table_data->reschedule_message;
                    }
                }

                // set property type
                switch ($value->property_type) {
                    case 'Commercial':
                        $value->property_type = 'Commercial';
                        break;
                    case 'Residential':
                        $value->property_type = 'Residential';
                        break;

                    default:
                        $value->property_type = 'Commercial';
                        break;
                }

                // if no resschedule message, set default
                if ($value->is_job_mode == 2) {
                    $concat_is_rescheduled = 2;
                    if (empty($value->reschedule_message)) {
                        $value->reschedule_message = "Unassigned by System";
                    }
                } else {
                    $value->reschedule_message = '';
                }

                // set row data
                $IsCustomerInHold = 0;
                if (isset($value->customer_status)) {
                    if ($value->customer_status == 2) {
                        $IsCustomerInHold = 1;
                    }
                }

                if ($IsCustomerInHold == 0) {  //print_r($data);die();
                    $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' value='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox' />";
                } else {
                    $data[$i]['checkbox'] = "<input title='Customer Account On Hold'  name='group_id' type='checkbox'  disabled data-row-job-mode='$concat_is_rescheduled' value='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox customer_in_hold' />";
                }


                $data[$i]['priority'] = $value->priority;
                $data[$i]['job_name'] = $value->job_name;
                $data[$i]['customer_name'] = '<a href="' . base_url("admin/editCustomer/") . $value->customer_id . '" style="color:#3379b7;">' . $value->first_name . ' ' . $value->last_name . '</a>';
                $data[$i]['property_name'] = $value->property_title;
                $data[$i]['square_feet'] = $value->yard_square_feet;
                $data[$i]['last_service_date'] = isset($value->completed_date_property) && $value->completed_date_property != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_property)) : '';
                $data[$i]['last_program_service_date'] = isset($value->completed_date_property_program) && $value->completed_date_property_program != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_property_program)) : '';
                //service due styling for datatable rendering
                switch ($value->service_due) {
                    case "Due":
                        $data[$i]['service_due'] = "<span class='label label-success myspan'>Due</span>";
                        break;

                    case "Overdue":
                        $data[$i]['service_due'] = "<span class='label label-danger myspan'>Overdue</span>";
                        break;

                    case "Not Due":
                    default:
                        $data[$i]['service_due'] = "<span class='label label-default myspan'>Not Due</span>";
                        break;
                }
                $data[$i]['address'] = $value->property_address;

                //customer notification flags
                $notify_array = $value->pre_service_notification ? json_decode($value->pre_service_notification) : [];
                $data[$i]['pre_service_notification'] = "";

                if (is_array($notify_array) && in_array(1, $notify_array)) {
                    $data[$i]['pre_service_notification'] = "<div class='label label-primary myspan m-y-1' style=' padding: 0 2px; margin-right: 0.5rem'>Call</div> ";
                }
                if (is_array($notify_array) && in_array(4, $notify_array)) {
                    $data[$i]['pre_service_notification'] .= "<div class='label label-success myspan ' style=' padding: 0 2px; margin-right: 0.5rem'>Text ETA</div>";
                }
                if (is_array($notify_array) && (in_array(2, $notify_array) || in_array(3, $notify_array))) {
                    $data[$i]['pre_service_notification'] .= "<div class='label label-info myspan' style=' padding: 0 2px; margin-right: 0.5rem'>Pre-Notified</div>";
                }
                $data[$i]['property_type'] = $value->property_type;
                $data[$i]['property_notes'] = isset($value->property_notes) ? $value->property_notes : '';
                $data[$i]['category_area_name'] = $value->category_area_name;
                $data[$i]['program'] = $value->program_name;
                $data[$i]['reschedule_message'] = $value->reschedule_message;


                $tags_list = "";
                $tags_list_array = [];
                if ($value->tags != null && !empty($value->tags)) {
                    $id_list = $value->tags;
                    $id_list_array = explode(',', $id_list);
                    foreach ($id_list_array as $tag) {

                        $where_arr = array(
                            // 'tags_title'=>'New Customer',
                            'id' => $tag

                        );

                        $tag = $this->TagsModel->getOneTag($where_arr);

                        if ($tag != null) {
                            $tags_list_array[] = $tag->tags_title;
                        }
                        // if($tag=null){
                        //     $tags_list_array[]=$tag->tags_title['New Customer'];
                        // }
                    }

                }
                $tag_html = "";
                if (count($tags_list_array) > 0) {
                    foreach ($tags_list_array as $tag) {
                        if ($tag == "New Customer") {
                            $tag_html .= '<span class="badge badge-success">' . $tag . '</span>';
                        } else {
                            $tag_html .= '<span class="badge badge-primary">' . $tag . '</span>';
                        }
                    }
                }

                $data[$i]['tags'] = $tag_html;


                // $data[$i]['service_note'] = $value->service_note;
                // $data[$i]['job_notes'] = $value->job_notes;

                $data[$i]['action'] = "<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='unassigned-services-element confirm_delete_unassign_job button-next' grd_ids='$value->customer_id:$value->job_id:$value->program_id:$value->property_id'  ><i class='icon-trash position-center' style='color: #9a9797;'></i></a></li></ul>";

                // easy way to console log out
                // $data[$i]['note'] = json_encode($_POST);
                // $data[$i]['note'] = json_encode($this->input->post('columns')[1]['search']['value']);
                // $data[$i]['note'] = json_encode($where);
                // $data[$i]['note'] = $value->is_job_mode.'-'.$value->unassigned_Job_delete_id;
                // $data[$i]['note'] = $var_last_query;

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

    public function ajaxGetCustomer()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'customer_id',
            2 => 'customer_name',
            3 => 'phone',
            4 => 'email',
            5 => 'billing_street',
            6 => 'billing_type',
            7 => 'properties',
            8 => 'property_type',
            9 => 'customer_status',
            10 => 'action'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = 'customers.' . $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'customers.company_id' => $company_id
        );

        $data = array();

        $where_like = array();
        $where_in = array();
        if (is_array($this->input->post('columns'))) {
            $columns = $this->input->post('columns');
            $colm_num = 0;
            foreach ($columns as $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
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
            $tempdata = $this->DashboardModel->getTableDataAjaxCustomer($where, $where_like, $limit, $start, $order, $dir, false, $where_in);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxCustomer($where, $where_like, $limit, $start, $order, $dir, true, $where_in);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata = $this->DashboardModel->getTableDataAjaxSearchCustomer($where, $where_like, $limit, $start, $order, $dir, $search, false, $where_in);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearchCustomer($where, $where_like, $limit, $start, $order, $dir, $search, true, $where_in);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }
        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                $data[$i]['checkbox'] = '<input type="checkbox" class="myCheckBox" value="' . $value->customer_id . '" name="selectcheckbox">';
                $data[$i]['customer_id'] = $value->customer_id;
                $data[$i]['customer_name'] = '<a href="' . base_url("admin/editCustomer/") . $value->customer_id . '" style="color:#3379b7;">' . $value->customer_name . '</a>';
                $data[$i]['phone'] = ($value->phone > 0 ? 'M: ' . formatPhoneNum($value->phone) : '') . ($value->home_phone > 0 ? ' <br> H: ' . formatPhoneNum($value->home_phone) : '') . ($value->work_phone > 0 ? ' <br> W: ' . formatPhoneNum($value->work_phone) : '');
                $data[$i]['email'] = $value->email;
                $data[$i]['billing_street'] = $value->billing_street;
                $props_to_send_array = array();
                $props_to_send = "";
                $props_type_array = array();
                $props_type = "";
                if (!empty($value->property_id)) {
                    foreach ($value->property_id as $value2) {
                        $props_to_send_array[] = $value2->property_title;
                        $props_type_array[] = $value2->property_type;
                    }
                    $props_to_send = implode(',', $props_to_send_array);
                    $props_type = implode(',', array_unique($props_type_array));
                }
                $data[$i]['properties'] = $props_to_send;
                $data[$i]['property_type'] = $props_type;

                $billing_type = '';
                if ($value->billing_type == 0) {
                    $billing_type = 'Standard';
                } else if ($value->billing_type == 1) {
                    $billing_type = 'Group Billing';
                }
                $data[$i]['billing_type'] = $billing_type;

                $status_return = '';
                if ($value->customer_status == 1) {
                    $status_return = '<span class="label label-success">Active</span>';
                } elseif ($value->customer_status == 7) {
                    $status_return = '<span class="label label-primary">Prospect</span>';
                } elseif ($value->customer_status == 3) {
                    $status_return = '<span class="label label-primary">Estimate</span>';
                } elseif ($value->customer_status == 4) {
                    $status_return = '<span class="label label-primary">Sales Call Scheduled</span>';
                } elseif ($value->customer_status == 5) {
                    $status_return = '<span class="label label-primary">Estimate Sent</span>';
                } elseif ($value->customer_status == 6) {
                    $status_return = '<span class="label label-primary">Estimate Declined</span>';
                } elseif ($value->customer_status == 2) {
                    $status_return = '<span class="label label-danger">Hold</span>';
                } else {
                    $status_return = '<span class="label label-danger">Non-Active</span>';
                }

                $data[$i]['customer_status'] = $status_return;

                $action_html = '
                    <ul style="list-style-type: none; padding-left: 0px;">
                    <li style="display: inline; padding-right: 10px;">
                    <a href="' . base_url("admin/editCustomer/") . $value->customer_id . '" title="Edit"><i
                        class="icon-pencil   position-center" style="color: #9a9797;"></i></a>
                    </li>
                    <li style="display: inline; padding-right: 10px;">
                    <a href="' . base_url("admin/customerDelete/") . $value->customer_id . '"
                        class="confirm_delete button-next" title="Delete"><i class="icon-trash   position-center"
                        style="color: #9a9797;"></i></a>
                    </li>
                </ul>
                ';
                $data[$i]['action'] = $action_html;

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

    public function ajaxGetProperty()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'property_title',
            2 => 'property_address',
            3 => 'program_text_for_display',
            4 => 'customer_name',
            5 => 'property_status',
            6 => 'action'
        );
        $limit = $this->input->post('length');
        if ($limit == -1) {
            $limit = 18446744073709551615;
        }
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'property_tbl.company_id' => $company_id
        );

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
                $tempdata = $this->DashboardModel->getTableDataAjaxProperty($where, $where_like, $limit, $start, $order, $dir, false);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxProperty($where, $where_like, $limit, $start, $order, $dir, true);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            } else {
                $tempdata = $this->DashboardModel->getTableDataAjaxProperty($where, $where_like, $limit, $start, $order, $dir, false, $where_in);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxProperty($where, $where_like, $limit, $start, $order, $dir, true, $where_in);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            }

        } else {
            $search = $this->input->post('search')['value'];
            if (empty($where_in)) {
                $tempdata = $this->DashboardModel->getTableDataAjaxSearchProperty($where, $where_like, $limit, $start, $order, $dir, $search, false);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearchProperty($where, $where_like, $limit, $start, $order, $dir, $search, true);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            } else {
                $tempdata = $this->DashboardModel->getTableDataAjaxSearchProperty($where, $where_like, $limit, $start, $order, $dir, $search, false, $where_in);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearchProperty($where, $where_like, $limit, $start, $order, $dir, $search, true, $where_in);
                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            }
        }

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                //die(var_dump($tagSearch));

                $data[$i]['checkbox'] = '<input type="checkbox" class="myCheckBox map" value="' . $key . '" property_id="' . $value->property_id . '" name="selectcheckbox" data-propname="' . $value->property_title . ' - ' . $value->first_name . ' ' . $value->last_name . '" >';
                $data[$i]['property_name'] = '<a href="' . base_url("admin/editProperty/") . $value->property_id . '">' . $value->property_title . '</a>';
                $data[$i]['property_address'] = $value->property_address;
                $data[$i]['program_text_for_display'] = $value->program_text_for_display;
                $data[$i]['customer_name'] = '<a href="' . base_url("admin/editCustomer/") . $value->customer_id . '" >' . $value->customer_name . '</a>';
                $status = "";

                if ($value->property_status == 1) {
                    $status = '<span class="label label-success">Active</span>';
                } elseif ($value->property_status == 0) {
                    $status = '<span class="label label-danger">Non-Active</span>';
                } elseif ($value->property_status == 2) {
                    $status = '<span class="label label-prospect">Prospect</span>';
                } elseif ($value->property_status == 3) {
                    $status = '<span class="label label-primary">Estimate</span>';
                } elseif ($value->property_status == 4) {
                    $status = '<span class="label label-prospect">Sales Call Scheduled</span>';
                } elseif ($value->property_status == 5) {
                    $status = '<span class="label label-prospect">Estimate Sent</span>';
                } elseif ($value->property_status == 6) {
                    $status = '<span class="label label-primary">Estimate Declined</span>';
                }
                $data[$i]['property_status'] = $status;

                $action_html = '
                        <ul style="list-style-type: none; padding-left: 0px;">
                            <li style="display: inline; padding-right: 10px;">
                                <a href="' . base_url("admin/editProperty/") . $value->property_id . '" class="button-next"><i class="fa fa-pencil   position-center" style="color: #9a9797;"></i>
                                </a>
                            </li>
                            <li style="display: inline; padding-right: 10px;">
                                <a href="' . base_url("admin/propertyDelete/") . $value->property_id . '" class="confirm_delete button-next"><i class="fa fa-trash   position-center" style="color: #9a9797;"></i></a>
                            </li>';
                if ($value->property_status != 0) {
                    $action_html .= '<li style="display: inline; padding-right: 10px;" >
                                    <a href="#" class="confirm_cancellation" onclick="cancelProperty(' . $value->property_id . ')" >
                                        <i class="fa fa-remove position-center"  title="Cancel Property" style="color: #9a9797; size: 16px"></i>
                                    </a>
                                </li>';
                }
                $action_html .= '</ul>';
                $data[$i]['action'] = $action_html;
                $marker = array();
                $marker[] = array(
                    "index" => $key,
                    "name" => $value->property_title,
                    "address" => $value->property_address,
                    "lat" => floatval($value->property_latitude),
                    "lng" => floatval($value->property_longitude),
                    "property_id" => $value->property_id
                );
                $data[$i]['marker'] = $marker;
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

    // public function add_Specific_Notes($company_id)
    // {
    //     $where = array(
    //         'property_tbl.company_id' => $company_id
    //     );

    //     $temp_all  = $this->Tech->getAllJobAssignCheck($where);
    //     echo "<pre/>";
    //     print_r($temp_all);die();
    // }

    public function add_remove_service_Note($property_id, $company_id)
    {
        $where = array(
            'property_tbl.company_id' => $company_id
        );

        $tempdata_alldata = $this->UnassignJobDeleteModal->getTableServiceNoteData($where);

        $is_job_mode = [];
        foreach ($tempdata_alldata as $_data) {


            if ($_data->is_job_mode == 1) {
                $Specific_notes = $_data->service_note;
                $param = array('service_note' => $Specific_notes);
                $update_result = $this->PropertyModel->updateAdminTbl($property_id, $param);
                //    echo"<pre>";
                //    print_r($update_result);die();
            } else {
                if ($_data->is_job_mode == 0) {
                    $Specific_notes = $_data->service_note;
                    $param = array('service_note' => $Specific_notes);
                    $update_result = $this->PropertyModel->updateAdminTbl($property_id, $param);

                }

            }
        }
    }


    // THIS IS THE CORRECT ONE FOR NEWER MAP CHANGES COMING
    public function ajaxGetRoutingFORMAPS()
    { // all I had to do to remove server-side to work with maps is change the last option in getTableDataAjax model to true. this removes the limits.
        ini_set('memory_limit', '2048M');
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'priority',
            2 => 'job_name',
            3 => 'pre_service_notification',
            4 => 'customers.first_name',
            5 => 'property_title',
            6 => '`property_tbl`.`yard_square_feet`',
            7 => 'completed_date_property',
            8 => 'completed_date_property_program',
            9 => 'completed_date_last_service_by_type',
            10 => 'service_due',
            11 => 'property_address',
            12 => 'property_type',
            13 => 'property_notes',
            14 => 'category_area_name',
            15 => 'program_name',
            16 => 'reschedule_message',
            17 => 'tags',
            18 => 'asap_reason',
            19 => 'available_days',
            20 => 'action',
            21 => 'program_services'
        );

        $limit = $this->input->post('length');

        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $northEastLng = $this->input->post('northEastLng');
        $northEastLat = $this->input->post('northEastLat');
        $northWestLng = $this->input->post('northWestLng');
        $northWestLat = $this->input->post('northWestLat');
        $southWestLng = $this->input->post('southWestLng');
        $southWestLat = $this->input->post('southWestLat');
        $southEastLng = $this->input->post('southEastLng');
        $southEastLat = $this->input->post('southEastLat');


        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status !=' => 0,
            'property_tbl.property_status !=' => 0,
        );

        $or_where = [];
        $draw = $this->input->post('draw');

        if ($this->input->post('markerarray')) {
            $markerdata = json_decode($this->input->post('markerarray'));


            /*
			$this->db->group_start();
			$this->db->where("wrk_dlvrd_sts","Delivered");
			$this->db->or_where("wrk_cl_sts","Success");
			$this->db->group_end();
				*/


            foreach ($markerdata as $markerset) {
                //print_r($markerset);
                $tempmarkers = explode(":", $markerset);
                $or_where['property_tbl.property_id'][] = $tempmarkers[3];
            }


            /*$wheresting = "(";


			foreach($markerdata as $markerset)
			{
				$tempmarkers = explode(":",$markerset);
				$wheresting .= "property_tbl.property_id = $tempmarkers[3] or ";// ;
			}

			//remove last or
			$wheresting = mb_substr($wheresting,0,-3);

			$wheresting .= ")";

			//die($wheresting);

			$where['property_tbl.property_id'] = $wheresting;*/

            //die(print_r($or_where));
        }


        $data = array();

        // seardch through each col, and if a search value, let's search for it
        $where_like = array();
        $where_in = array();
        $tagSearch = "";
        if (is_array($this->input->post('columns'))) {
            $columns = $this->input->post('columns');
            $colm_num = 0;
            foreach ($columns as $column) {
                // if($colm_num == 2) {
                //     die(print_r($columns));
                // }

                if (isset($column['search']['value']) && !empty($column['search']['value'])) {
                    if ($colm_num == 2) {
                        $col = 'jobs.job_name';
                        $val = $column['search']['value'];
                        if (strpos($column['search']['value'], ',') !== false) {
                            $where_in[$col] = explode(',', $val);
                        } else {
                            $where[$col] = $val;
                        }

                        // $dbg = explode(',', $val);
                    } else if ($colm_num == 18) {
                        $col = 'program_job_assigned_customer_property';
                        $val = $column['search']['value'];
                        $where[$col] = $val;
                    } else if ($colm_num == 19) {
                        // Available Days filtering
                        $col = 'available_days';
                        $val = $column['search']['value'];
                        if (strpos($column['search']['value'], ',') !== false) {
                            $where_in[$col] = explode(',', $val);
                        } else {
                            //$where_in[$col] = $val;
                            $where_in[$col] = explode(',', $val);
                        }

                    } else if ($colm_num == 21) {
                        $col = 'program_services';
                        $val = $column['search']['value'];
                        if (strpos($column['search']['value'], ',') !== false) {
                            $where_like[$col] = explode(',', $val);
                        } else {
                            $where[$col] = $val;
                        }

                        // $dbg = explode(',', $val);
                    } else if ($colm_num == 10) {
                        $col = 'service_due';
                        $val = $column['search']['value'];

                        $where_like[$col] = explode(',', $val);
                        // $dbg = explode(',', $val);
                    } else {
                        $col = $column['data'];
                        $val = $column['search']['value'];
                        if ($col == "tags") {
                            $col = "property_tbl.tags";
                            $val = (int)$val;
                            $tag = $this->TagsModel->getOneTag(array('id' => $val));
                            if (!empty($tag)) {
                                $where_like[$col] = $tag->id;
                                $tagSearch = $tag->id;
                            } else {
                                $where_like[$col] = $val;
                            }
                        } else if ($colm_num == 14) {
                            if (strpos($column['search']['value'], ',') !== false) {
                                $where_in[$col] = explode(',', $val);
                            } else {
                                $where[$col] = $val;
                            }
                        } else {
                            $where_like[$col] = $val;
                        }
                    }
                }
                $colm_num++;
            }
        }

        if ($draw == 1 && (empty($where_in) && empty($where_like) && count($where) == 4))
            $limit = 50;
        // get data (2 separate fns for search and non search)

        /*-----------------------------------------------------------------------------------

        if (empty($this->input->post('search')['value'])) {

            $tempdata  = $this->DashboardModel->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, false, $where_in, $or_where);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, true, $where_in, $or_where);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);

        } else {
            $search = $this->input->post('search')['value'];

            $tempdata  = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false, $where_in, $or_where);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true, $where_in, $or_where);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        */ //-----------------------------------------------------------------------------------

        $start_time = microtime(true);
        //-------------------------------------------------------------------------------
        if (empty($this->input->post('search')['value'])) {
//            $file = fopen("test.txt","w");
//            fwrite($file,"We are inside getTableDataAjax function");
//            fclose($file);


            if (isset($where['program_services']) || (isset($where_like['program_services']) && is_array($where_like['program_services']))) {

                $property_outstanding_services = $this->DashboardModel->getOutstandingServicesFromProperty_forTable($where, $where_like, $limit, $start, $order, $dir, false, $where_in, $or_where);

                $tempdata = $this->DashboardModel->getMapDataAjax($where, $where_like, $limit, $start, $order, $dir, false, $where_in, $or_where, $northEastLng, $northEastLat, $northWestLng, $northWestLat, $southWestLng, $southWestLat, $southEastLng, $southEastLat, $property_outstanding_services);
                $var_total_item_count_for_pagination = $this->DashboardModel->getMapDataAjax($where, $where_like, $limit, $start, $order, $dir, true, $where_in, $or_where, $northEastLng, $northEastLat, $northWestLng, $northWestLat, $southWestLng, $southWestLat, $southEastLng, $southEastLat, $property_outstanding_services);

            } else {
                $tempdata = $this->DashboardModel->getMapDataAjax($where, $where_like, $limit, $start, $order, $dir, false, $where_in, $or_where, $northEastLng, $northEastLat, $northWestLng, $northWestLat, $southWestLng, $southWestLat, $southEastLng, $southEastLat);
                $var_total_item_count_for_pagination = $this->DashboardModel->getMapDataAjax($where, $where_like, $limit, $start, $order, $dir, true, $where_in, $or_where, $northEastLng, $northEastLat, $northWestLng, $northWestLat, $southWestLng, $southWestLat, $southEastLng, $southEastLat);
            }


//            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);

        } else {
            $search = $this->input->post('search')['value'];
            if (empty($where_in)) {

                if (isset($where['program_services']) || (isset($where_like['program_services']) && is_array($where_like['program_services']))) {

                    $property_outstanding_services = $this->DashboardModel->getOutstandingServicesFromProperty_forTable($where, $where_like, $limit, $start, $order, $dir, false, $where_in, $or_where);
                    $tempdata = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false, $where_in, $or_where, $property_outstanding_services);
                    $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true, $where_in, $or_where, $property_outstanding_services);

                } else {
                    $tempdata = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false, $where_in, $or_where);
                    $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true, $where_in, $or_where);
                }
//                $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
                // $var_total_item_count_for_pagination = 600;
            }
        }
        //---------------------------------------------------------------------------------
        //  $var_last_query = $this->db->last_query ();

        if (!empty($tempdata)) {
            $i = 0;

            $property_id_array = array();

            // filter & mold data for frontend
            $start_time = microtime(true);
            foreach ($tempdata as $key => $value) {


                //die(var_dump($tagSearch));
                if (isset($tagSearch) && $tagSearch != "") {
                    $property_tags = explode(',', $value->tags);
                    if (!in_array($tagSearch, $property_tags)) {
                        unset($tempdata[$key]);
                        continue;
                    }
                }
                $prop_id = $value->property_id;


                // $generate_row = true;
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $prop_id,
                );
//                $assign_table_data = $this->Tech->GetOneRow($arrayName); // select * from technician_job_assign where __
//                $value->mode = '';
//                $value->reschedule_message = '';
//                $concat_is_rescheduled = 0;
//                if ($assign_table_data) {
//                    if ($assign_table_data->is_job_mode == 2) {
//                        $concat_is_rescheduled = 2;
//                        $value->mode = 'Rescheduled';
//                        $value->reschedule_message = $assign_table_data->reschedule_message;
//                    }
//                }
                $concat_is_rescheduled = 0;
                if ($value->is_job_mode == 2) {
                    //$concat_is_rescheduled = 2;
                    $value->mode = 'Rescheduled';
                }
                // set property type
                switch ($value->property_type) {
                    case 'Commercial':
                        $value->property_type = 'Commercial';
                        break;
                    case 'Residential':
                        $value->property_type = 'Residential';
                        break;

                    default:
                        $value->property_type = 'Commercial';
                        break;
                }

                // if no resschedule message, set default
                if ($value->is_job_mode == 2) {
                    $concat_is_rescheduled = 2;
                    if (empty($value->reschedule_message)) {
                        $value->reschedule_message = "Unassigned by System";
                    }
                } else {
                    $value->reschedule_message = '';
                }

                // set row data
                $IsCustomerInHold = 0;
                if (isset($value->customer_status)) {
                    if ($value->customer_status == 2) {
                        $IsCustomerInHold = 1;
                    }
                }

                $asapHighligth = 0;
                if ($value->asap == 1)
                    $asapHighligth = 1;
                if($IsCustomerInHold==0){  //print_r($data);die();
                $data[$i]['checkbox'] ="<input  name='group_id' type='checkbox' data-address='$value->property_address:$value->property_latitude:$value->property_longitude' data-row-asap='$asapHighligth' data-row-job-mode='$concat_is_rescheduled' id='$i' value='$i' data-realvalue='$value->customer_id:$value->job_id:$value->program_id:$prop_id' class='myCheckBox map' />";
                }
                else {
                $data[$i]['checkbox'] ="<input title='Customer Account On Hold' data-address='$value->property_address:$value->property_latitude:$value->property_longitude' data-row-asap='$asapHighligth' name='group_id' type='checkbox'  disabled data-row-job-mode='$concat_is_rescheduled' id='$i' value='$i' data-realvalue='$value->customer_id:$value->job_id:$value->program_id:$prop_id' class='myCheckBox customer_in_hold' />";
                }
                // $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' id=' $i ' value='$i' class='myCheckBox map' />";
                // $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' value='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' data-iter=$i class='myCheckBox' />";
                //dev-11 checkbox below
                //$data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' id=' $i ' value='$i' data-realvalue='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox map' />";
                $data[$i]['priority'] = $value->priority;
                $data[$i]['job_name'] = $value->job_name;
                $data[$i]['customer_name'] = '<a href="' . base_url("admin/editCustomer/") . $value->customer_id . '" style="color:#3379b7;">' . $value->first_name . ' ' . $value->last_name . '</a>';
                $data[$i]['property_name'] = $value->property_title;
                $data[$i]['square_feet'] = $value->yard_square_feet;
                $data[$i]['program_services'] = isset($value->program_services) ? $value->program_services : array();

                $data[$i]['last_service_date'] = isset($value->completed_date_property) && $value->completed_date_property != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_property)) : '';
                $data[$i]['last_program_service_date'] = isset($value->completed_date_property_program) && $value->completed_date_property_program != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_property_program)) : '';
                $data[$i]['completed_date_last_service_by_type'] = isset($value->completed_date_last_service_by_type) && $value->completed_date_last_service_by_type != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_last_service_by_type)) : '';
                $data[$i]['last_program_service_date'] = $value->completed_date_property_program;
                $data[$i]['asap'] = $value->asap;
                $data[$i]['asap_reason'] = $value->asap_reason;
                //service due styling for datatable rendering
                switch ($value->service_due) {
                    case "Due":
                        $data[$i]['service_due'] = "<span class='label label-success myspan'>Due</span>";
                        break;
                    case "Overdue":
                        $data[$i]['service_due'] = "<span class='label label-danger myspan'>Overdue</span>";
                        break;
                    case "Not Due":
                    default:
                        $data[$i]['service_due'] = "<span class='label label-default myspan'>Not Due</span>";
                        break;
                }
                $data[$i]['address'] = $value->property_address;
                //customer notification flags
                //$notify_array = json_decode($value->pre_service_notification);
                $notify_array = $value->pre_service_notification ? json_decode($value->pre_service_notification) : [];
                $data[$i]['pre_service_notification'] = "";
                if (is_array($notify_array) && in_array(1, $notify_array)) {
                    $data[$i]['pre_service_notification'] = "<div class='label label-primary myspan m-y-1' style=' padding: 0 2px; margin-right: 0.5rem'>Call</div> ";
                }
                if (is_array($notify_array) && in_array(4, $notify_array)) {
                    $data[$i]['pre_service_notification'] .= "<div class='label label-success myspan ' style=' padding: 0 2px; margin-right: 0.5rem'>Text ETA</div>";
                }
                if (is_array($notify_array) && (in_array(2, $notify_array) || in_array(3, $notify_array))) {
                    $data[$i]['pre_service_notification'] .= "<div class='label label-info myspan' style=' padding: 0 2px; margin-right: 0.5rem'>Pre-Notified</div>";
                }

                //$data[$i]['title'] = $value->property_address;


                // $data[$i]['property_state'] = $value->property_state;
                // $data[$i]['property_city'] = $value->property_city;
                // $data[$i]['property_zip'] = $value->property_zip;
                $data[$i]['property_type'] = $value->property_type;
                $data[$i]['property_notes'] = isset($value->property_notes) ? $value->property_notes : '';
                $data[$i]['category_area_name'] = $value->category_area_name;
                $data[$i]['program'] = $value->program_name;
                $data[$i]['reschedule_message'] = $value->reschedule_message;
                $tags_list = "";
                $tags_list_array = [];
                if ($value->tags != null && !empty($value->tags)) {
                    $id_list = $value->tags;
                    $id_list_array = explode(',', $id_list);
                    foreach ($id_list_array as $tag) {
                        $where_arr = array(
                            // 'tags_title'=>'New Customer',
                            'id' => $tag
                        );
                        $tag = $this->TagsModel->getOneTag($where_arr);
                        if ($tag != null) {
                            $tags_list_array[] = $tag->tags_title;
                        }
                        // if($tag=null){
                        //     $tags_list_array[]=$tag->tags_title['New Customer'];
                        // }
                    }
                }
                $tag_html = "";
                if (count($tags_list_array) > 0) {
                    foreach ($tags_list_array as $tag) {
                        if ($tag == "New Customer") {
                            $tag_html .= '<span class="badge badge-success">' . $tag . '</span>';
                        } else {
                            $tag_html .= '<span class="badge badge-primary">' . $tag . '</span>';
                        }
                    }
                }
                $data[$i]['tags'] = $tag_html;
                // $data[$i]['service_note'] = $value->service_note;
                // $data[$i]['job_notes'] = $value->job_notes;


                $data[$i]['property_longitude'] = $value->property_longitude;
                $data[$i]['property_latitude'] = $value->property_latitude;
                $data[$i]['action'] = "<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='unassigned-services-element confirm_delete_unassign_job button-next' grd_ids='$value->customer_id:$value->job_id:$value->program_id:$prop_id'  ><i class='icon-trash position-center' style='color: #9a9797;'></i></a></li></ul>";
                $data[$i]['index'] = $i;
                $data[$i]['lat'] = $value->property_latitude;
                $data[$i]['lng'] = $value->property_longitude;
                // Available days
                $available_days = formatAvailableDays($value->available_days);
                $data[$i]['available_days'] = implode(", ", $available_days);
                // easy way to console log out
                // $data[$i]['note'] = json_encode($_POST);
                // $data[$i]['note'] = json_encode($this->input->post('columns')[1]['search']['value']);
                // $data[$i]['note'] = json_encode($where);
                // $data[$i]['note'] = $value->is_job_mode.'-'.$value->unassigned_Job_delete_id;
                // $data[$i]['note'] = $var_last_query;

                $i++;
            }
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            //die(" Execution time of script = ".$execution_time." sec");
            // die(print_r($property_id_array));
        }

        $json_data = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $var_total_item_count_for_pagination, // "(filtered from __ total entries)"
            "recordsFiltered" => $var_total_item_count_for_pagination, // actual total that determines page counts
            "data" => $data
        );
        echo json_encode($json_data);
    }

    public function ajaxGetRoutingFULLYFUNCTIONALSERVERSIDEPROCESSINGwithoutmapchanges()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'priority',
            2 => 'job_name',
            3 => 'customers.first_name',
            4 => 'property_title',
            5 => '`property_tbl`.`yard_square_feet`',
            6 => 'completed_date_property',
            7 => 'completed_date_property_program',
            8 => 'property_address',
            9 => 'property_type',
            10 => 'category_area_name',
            11 => 'program_name',
            12 => 'reschedule_message',
            13 => '`property_tbl`.`property_longitude`',
            14 => '`property_tbl`.`property_latitude`',
            15 => '`property_tbl`.`property_state`',
            16 => '`property_tbl`.`property_city`',
            17 => '`property_tbl`.`property_zip`',
            18 => 'action'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status !=' => 0,
            'property_status' => 1
        );

        $data = array();

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
            $tempdata = $this->DashboardModel->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        //  $var_last_query = $this->db->last_query ();

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                // $generate_row = true;
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $value->property_id,
                );
                $assign_table_data = $this->Tech->GetOneRow($arrayName); // select * from technician_job_assign where __
                $value->mode = '';
                $value->reschedule_message = '';
                $concat_is_rescheduled = 0;
                if ($assign_table_data) {
                    if ($assign_table_data->is_job_mode == 2) {
                        $concat_is_rescheduled = 2;
                        $value->mode = 'Rescheduled';
                        $value->reschedule_message = $assign_table_data->reschedule_message;
                    }
                }

                // set property type
                switch ($value->property_type) {
                    case 'Commercial':
                        $value->property_type = 'Commercial';
                        break;
                    case 'Residential':
                        $value->property_type = 'Residential';
                        break;

                    default:
                        $value->property_type = 'Commercial';
                        break;
                }

                // if no resschedule message, set default
                if ($value->is_job_mode == 2) {
                    $concat_is_rescheduled = 2;
                    if (empty($value->reschedule_message)) {
                        $value->reschedule_message = "Unassigned by System";
                    }
                } else {
                    $value->reschedule_message = '';
                }

                // set row data
                // $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' id=' $i ' value='$i' class='myCheckBox map' />";
                // $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' value='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' data-iter=$i class='myCheckBox' />";
                $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' id=' $i ' value='$i' data-realvalue='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox map' />";
                $data[$i]['priority'] = $value->priority;
                $data[$i]['job_name'] = $value->job_name;
                $data[$i]['customer_name'] = $value->first_name . ' ' . $value->last_name;
                $data[$i]['property_name'] = $value->property_title;
                $data[$i]['square_feet'] = $value->yard_square_feet;
                $data[$i]['last_service_date'] = $value->completed_date_property;
                $data[$i]['last_program_service_date'] = $value->completed_date_property_program;
                $data[$i]['address'] = $value->property_address;
                $data[$i]['property_state'] = $value->property_state;
                $data[$i]['property_city'] = $value->property_city;
                $data[$i]['property_zip'] = $value->property_zip;
                $data[$i]['property_type'] = $value->property_type;
                $data[$i]['category_area_name'] = $value->category_area_name;
                $data[$i]['program'] = $value->program_name;
                $data[$i]['reschedule_message'] = $value->reschedule_message;
                $data[$i]['property_longitude'] = $value->property_longitude;
                $data[$i]['property_latitude'] = $value->property_latitude;
                $data[$i]['action'] = "<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='unassigned-services-element confirm_delete_unassign_job button-next' grd_ids='$value->customer_id:$value->job_id:$value->program_id:$value->property_id'  ><i class='icon-trash position-center' style='color: #9a9797;'></i></a></li></ul>";

                // easy way to console log out
                // $data[$i]['note'] = json_encode($_POST);
                // $data[$i]['note'] = json_encode($this->input->post('columns')[1]['search']['value']);
                // $data[$i]['note'] = json_encode($where);
                // $data[$i]['note'] = $value->is_job_mode.'-'.$value->unassigned_Job_delete_id;
                // $data[$i]['note'] = $var_last_query;

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

    public function ajaxGetRoutingArchived()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'priority',
            2 => 'job_name',
            3 => 'customers.first_name',
            4 => 'property_title',
            5 => '`property_tbl`.`yard_square_feet`',
            6 => 'completed_date_property',
            7 => 'completed_date_property_program',
            8 => 'property_address',
            9 => 'property_type',
            10 => 'category_area_name',
            11 => 'program_name',
            12 => 'reschedule_message',
            13 => 'note_contents',
            14 => 'action'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status !=' => 0,
            'property_status' => 1
        );

        $data = array();

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
            $tempdata = $this->UnassignJobDeleteModal->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->UnassignJobDeleteModal->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata = $this->UnassignJobDeleteModal->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->UnassignJobDeleteModal->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        //  $var_last_query = $this->db->last_query ();
        $notes_array = [];
        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                // $generate_row = true;
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $value->property_id,
                );
                $assign_table_data = $this->Tech->GetOneRow($arrayName); // select * from technician_job_assign where __
                $value->mode = '';
                $value->reschedule_message = '';
                $concat_is_rescheduled = 0;
                if ($assign_table_data) {
                    if ($assign_table_data->is_job_mode == 2) {
                        $concat_is_rescheduled = 2;
                        $value->mode = 'Rescheduled';
                        $value->reschedule_message = $assign_table_data->reschedule_message;
                    }
                }

                // set property type
                switch ($value->property_type) {
                    case 'Commercial':
                        $value->property_type = 'Commercial';
                        break;
                    case 'Residential':
                        $value->property_type = 'Residential';
                        break;

                    default:
                        $value->property_type = 'Commercial';
                        break;
                }

                // if no resschedule message, set default
                if ($value->is_job_mode == 2) {
                    $concat_is_rescheduled = 2;
                    if (empty($value->reschedule_message)) {
                        $value->reschedule_message = "Unassigned by System";
                    }
                } else {
                    $value->reschedule_message = '';
                }

                // set row data
                //$technician_job_assign_id_html= $technician_job_assign_id_html."<input type='hidden' value='".$value->technician_job_assign_id."'>"; commenting this out because its not being called anywhere and causing errors on page to break...doesnt seem to be needed
                $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' value='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox' />";
                $data[$i]['priority'] = $value->priority;
                $data[$i]['job_name'] = $value->job_name;
                $data[$i]['customer_name'] = '<a href="' . base_url("admin/editCustomer/") . $value->customer_id . '" style="color:#3379b7;">' . $value->first_name . ' ' . $value->last_name . '</a>';
                $data[$i]['property_name'] = $value->property_title;
                $data[$i]['square_feet'] = $value->yard_square_feet;
                $data[$i]['last_service_date'] = isset($value->completed_date_property) ? date('m-d-Y', strtotime($value->completed_date_property)) : '';
                $data[$i]['last_program_service_date'] = isset($value->completed_date_property_program) ? date('m-d-Y', strtotime($value->completed_date_property_program)) : '';
                $data[$i]['address'] = $value->property_address;
                $data[$i]['property_type'] = $value->property_type;
                $data[$i]['category_area_name'] = $value->category_area_name;
                $data[$i]['program'] = $value->program_name;
                $data[$i]['reschedule_message'] = $value->reschedule_message;
                //$data[$i]['property_notes'] = $property_notes;
                $where_array = array(
                    'note_property_id' => $value->property_id,
                );
                $property_notes = $this->UnassignJobDeleteModal->getPropertyTechViewNotes($where_array);
                $notes_html = "";
                if (count($property_notes) > 0) {
                    $notes_html = '<input type="button" data-toggle="modal" data-target="#modal_theme_success" onClick="open_notes(' . $value->technician_job_assign_id . ')"  value="Notes">';
                    $note['technician_job_assign_id'] = $value->technician_job_assign_id;
                    $note['property_notes'] = $property_notes;
                    $notes_array[] = $note;
                }
                $data[$i]['note_contents'] = $notes_html;
                $data[$i]['action'] = "<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='unassigned-services-element confirm_restore_unassign_job button-next' grd_ids='$value->customer_id:$value->job_id:$value->program_id:$value->property_id'  ><i class='icon-undo position-center' style='color: #9a9797;'></i></a></li></ul>";

                // easy way to console log out
                // $data[$i]['note'] = json_encode($_POST);
                // $data[$i]['note'] = json_encode($this->input->post('columns')[1]['search']['value']);
                // $data[$i]['note'] = json_encode($where);
                // $data[$i]['note'] = $value->is_job_mode.'-'.$value->unassigned_Job_delete_id;
                // $data[$i]['note'] = $var_last_query;

                $i++;
            }
        }

        $json_data = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $var_total_item_count_for_pagination, // "(filtered from __ total entries)"
            "recordsFiltered" => $var_total_item_count_for_pagination, // actual total that determines page counts
            "data" => $data,
            "notes_array" => $notes_array,
        );
        echo json_encode($json_data);
    }

    public function ajaxGetRoutingArchivedJamil()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'priority',
            2 => 'job_name',
            3 => 'customers.first_name',
            4 => 'property_title',
            5 => '`property_tbl`.`yard_square_feet`',
            6 => 'completed_date_property',
            7 => 'completed_date_property_program',
            8 => 'property_address',
            9 => 'property_type',
            10 => 'category_area_name',
            11 => 'program_name',
            12 => 'reschedule_message',
            13 => '`property_tbl`.`property_longitude`',
            14 => '`property_tbl`.`property_latitude`',
            15 => '`property_tbl`.`property_state`',
            16 => '`property_tbl`.`property_city`',
            17 => '`property_tbl`.`property_zip`',
            18 => 'action'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status !=' => 0,
            'property_status' => 1
        );

        $data = array();

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
            $tempdata = $this->UnassignJobDeleteModal->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->UnassignJobDeleteModal->getTableDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            $search = $this->input->post('search')['value'];
            $tempdata = $this->UnassignJobDeleteModal->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->UnassignJobDeleteModal->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        //  $var_last_query = $this->db->last_query ();

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                // $generate_row = true;
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $value->property_id,
                );
                $assign_table_data = $this->Tech->GetOneRow($arrayName); // select * from technician_job_assign where __
                $value->mode = '';
                $value->reschedule_message = '';
                $concat_is_rescheduled = 0;
                if ($assign_table_data) {
                    if ($assign_table_data->is_job_mode == 2) {
                        $concat_is_rescheduled = 2;
                        $value->mode = 'Rescheduled';
                        $value->reschedule_message = $assign_table_data->reschedule_message;
                    }
                }

                // set property type
                switch ($value->property_type) {
                    case 'Commercial':
                        $value->property_type = 'Commercial';
                        break;
                    case 'Residential':
                        $value->property_type = 'Residential';
                        break;

                    default:
                        $value->property_type = 'Commercial';
                        break;
                }

                // if no resschedule message, set default
                if ($value->is_job_mode == 2) {
                    $concat_is_rescheduled = 2;
                    if (empty($value->reschedule_message)) {
                        $value->reschedule_message = "Unassigned by System";
                    }
                } else {
                    $value->reschedule_message = '';
                }

                // set row data
                $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' id=' $i ' value='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox map' />";
                $data[$i]['priority'] = $value->priority;
                $data[$i]['job_name'] = $value->job_name;
                $data[$i]['customer_name'] = $value->first_name . ' ' . $value->last_name;
                $data[$i]['property_name'] = $value->property_title;
                $data[$i]['square_feet'] = $value->yard_square_feet;
                $data[$i]['last_service_date'] = $value->completed_date_property;
                $data[$i]['last_program_service_date'] = $value->completed_date_property_program;
                $data[$i]['address'] = $value->property_address;
                $data[$i]['property_state'] = $value->property_state;
                $data[$i]['property_city'] = $value->property_city;
                $data[$i]['property_zip'] = $value->property_zip;
                $data[$i]['property_type'] = $value->property_type;
                $data[$i]['category_area_name'] = $value->category_area_name;
                $data[$i]['program'] = $value->program_name;
                $data[$i]['reschedule_message'] = $value->reschedule_message;
                $data[$i]['property_longitude'] = $value->property_longitude;
                $data[$i]['property_latitude'] = $value->property_latitude;
                $data[$i]['action'] = "<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='unassigned-services-element confirm_restore_unassign_job button-next' grd_ids='$value->customer_id:$value->job_id:$value->program_id:$value->property_id'  ><i class='icon-undo position-center' style='color: #9a9797;'></i></a></li></ul>";

                // easy way to console log out
                // $data[$i]['note'] = json_encode($_POST);
                // $data[$i]['note'] = json_encode($this->input->post('columns')[1]['search']['value']);
                // $data[$i]['note'] = json_encode($where);
                // $data[$i]['note'] = $value->is_job_mode.'-'.$value->unassigned_Job_delete_id;
                // $data[$i]['note'] = $var_last_query;

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

    public function getUnassignedServiceList($ajax = true)
    {
        $company_id = $this->session->userdata['company_id'] ?? $this->session->userdata['spraye_technician_login']->company_id;
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status' => 1
        );
        $service_list = $this->DashboardModel->getUnassignedServiceList($where);
        if ($ajax == true) {
            echo json_encode($service_list);
        } else {
            return $service_list;
        }
    }

    public function basysAddCustomer()
    {
        $data = $this->input->post();
        //print_r($data);
        //get api key
        $company_id = $this->session->userdata('company_id');
        $basys_details = $this->BasysRequest->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
        $apiKey = $basys_details->api_key;

        $customer_details = $this->CustomerModel->getCustomerDetail($data['customer_id']);

        if ($customer_details) {
            $customer = array(
                "id_format" => "xid_type_last4",
                "default_payment" => array(
                    "card" => array(
                        "number" => $data['card_number'],
                        "expiration_date" => $data['card_exp']
                    )
                ),
                "default_billing_address" => array(
                    "first_name" => $customer_details['first_name'],
                    "last_name" => $customer_details['last_name'],

                ),
            );

            //$url = "https://sandbox.basysiqpro.com/api";   //test
            $url = BASYS_URL . "api";

            $curl = curl_init();
            $payload = json_encode($customer);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url . "/vault/customer",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: " . $apiKey,
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $result = json_decode($response, true);
            curl_close($curl);

            if (isset($result['data']['id'])) {
                //print_r($result);
                $basys_customer_id = $result['data']['id'];

                $update = $this->CustomerModel->updateCustomerData(array(
                    'basys_customer_id' => $basys_customer_id,
                    'basys_autocharge' => 1,
                    'customer_clover_token' => '',
                    'clover_autocharge' => 0
                ), array('customer_id' => $data['customer_id']));
            }

            echo $response;
        }
    }

    public function cloverAddCustomer()
    {
        $data = $this->input->post();
        //die(print_r($data));

        $company_id = $this->session->userdata('company_id');
        $cardconnect_details = $this->CardConnectModel->getOneCardConnect(array('company_id' => $company_id, 'status' => 1));

        $customer_details = $this->CustomerModel->getCustomerDetail($data['customer_id']);


        if ($customer_details) {

            $tokenAcct = array(
                'tokenData' => $data['tokenData']
            );

            // die(print_r($tokenAcct));

            $token = cardConnectTokenizeAccount($tokenAcct);

            // die(print_r($token));

            if ($token) {

                $cc_auth = array(
                    'username' => $cardconnect_details->username,
                    'password' => decryptPassword($cardconnect_details->password),
                    'merchid' => $cardconnect_details->merchant_id,
                    'requestData' => array(
                        'merchid' => $cardconnect_details->merchant_id,
                        'account' => $token['result']->token,
                        'email' => $customer_details['email'],
                        'ecomind' => 'R',
                        'cof' => 'M',
                        'cofpermission' => 'Y',
                        'cofscheduled' => 'N',
                        'name' => $customer_details['first_name'] . ' ' . $customer_details['last_name'],
                        'address' => $customer_details['billing_street'],
                        'city' => $customer_details['billing_city'],
                        'region' => $customer_details['billing_state'],
                        'postal' => $customer_details['billing_zipcode'],
                        'profile' => 'Y',
                        'amount' => number_format(0.00, 2)
                    )
                );

                $cc_authorize = cardConnectAuthorize($cc_auth);

                // die(print_r($cc_authorize));

                if ($cc_authorize['status'] == 200) {

                    if (strcmp($cc_authorize['result']->respstat, 'A') == 0){
                        $where = array('customer_id' => $data['customer_id']);
                        $param = array(
                            'customer_clover_token' => $cc_authorize['result']->profileid,
                            'clover_acct_id' => $cc_authorize['result']->acctid,
                            'clover_autocharge' => 1,
                            'basys_customer_id' => '',
                            'basys_autocharge' => 0
                        );
                        $this->CustomerModel->updateCustomerData($param, $where);

                        // die(print_r($cc_authorize));

                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"></div>');

                        $return_arr = array('status' => 200, 'msg' => 'Payment Credentials Added Successfully.', 'result' => $cc_authorize['result']);
                    } else {
                        $return_arr = array('status' => 400, 'msg' => $cc_authorize['result']->resptext, 'result' => $cc_authorize['result']);
                    }
                } else {
                    $return_arr = array('status' => 400, 'msg' => $cc_authorize['message']);
                }

            } else {
                $return_arr = array('status' => 400, 'msg' => $token['result']->message);
            }


        } else {
            $return_arr = array('status' => 400, 'msg' => 'Customer Not Found');
        }
        echo json_encode($return_arr);
    }

    public function basysGetCustomerRecord($basys_customer_id)
    {

        //get api key
        $company_id = $this->session->userdata('company_id');
        $basys_details = $this->BasysRequest->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
        $apiKey = $basys_details->api_key;

        //$url = "https://sandbox.basysiqpro.com/api";   //test
        $url = BASYS_URL . "api";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/vault/" . $basys_customer_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $apiKey
            ),
        ));

        $response = curl_exec($curl);
        $result = json_decode($response, true);
        curl_close($curl);

        return $response;
    }

    public function basysUpdateCustomerPayment()
    {
        $data = $this->input->post();
        //print_r($data);
        //get basys customer data
        $get_basys_customer = $this->basysGetCustomerRecord($data['basys_customer_id']);
        $basys_customer_details = json_decode($get_basys_customer, true);

        if ($basys_customer_details['status'] == 'success') {

            $payment_method_id = $basys_customer_details['data']['data']['customer']['payments']['cards'][0]['id'];

            //get api key
            $company_id = $this->session->userdata('company_id');
            $basys_details = $this->BasysRequest->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));
            $apiKey = $basys_details->api_key;

            if ($payment_method_id) {

                $card = array(
                    "number" => $data['card_number'],
                    "expiration_date" => $data['card_exp']
                );

                //$url = "https://sandbox.basysiqpro.com/api";   //test
                $url = BASYS_URL . "api";

                $curl = curl_init();
                $payload = json_encode($card);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url . "/vault/customer/" . $data['basys_customer_id'] . "/card/" . $payment_method_id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $payload,
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: " . $apiKey,
                        "Content-Type: application/json"
                    ),
                ));
                $response = curl_exec($curl);
                $result = json_decode($response, true);
                curl_close($curl);

                echo $response;
            }
        } else {
            echo $data['status'] = 'error';
        }
    }

    //table view
    public function assignJobs()
    {
        $ajax_call = $this->input->get('ajax-call');
        $company_id = $this->session->userdata['company_id'];
        $page["active_sidebar"] = "unass_serv_routing";
        $page["page_name"] = "Unassigned Services";
        $data['tecnician_details'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
        $data['service_list'] = $this->getUnassignedServiceList(false);
        // die(print_r($this->db->last_query()));
        // die(print_r($data['service_list']));

        $service_area_list = $this->ServiceArea->getAllServiceArea(array('company_id' => $this->session->userdata['company_id']));
        $data['service_area_list'] = $service_area_list;
//        $data['filter_service_area_list'] = '<select class="form-control dtatableInput " style="font-size:inherit!important;color:#9e9e9e;padding-top:8px;text-transform:uppercase;" id="service_area_filter" placeholder="SERVICE AREA""><option value="0" class="default-option" >-- SERVICE AREA</option>';
//
//        if(isset($service_area_list) && count($service_area_list)>0){
//            foreach($service_area_list as $sal){
//
//                $data['filter_service_area_list'].= '<option value="'.str_replace("'","&#39;",$sal->category_area_name).'">'.str_replace("'","&#39;",$sal->category_area_name).'</option>';
//            }
//        }
//        $data['filter_service_area_list'] .='</select>';
        //die(print_r($data['filter_service_area_list']));
        $data['all_jobs'] = $this->JobModel->getAllJob(array('jobs.company_id' => $this->session->userdata['company_id']));
        $data['filter_tags'] = '<select class="form-control dtatableInput" style="font-size:inherit!important;color:#9e9e9e;padding-top:8px;text-transform:uppercase;" id="tag_filter" placeholder="TAG"><option value="0" class="default-option">-- TAGS</option>';
        $company_tags = $this->TagsModel->get_all_tags(array('company_id' => $this->session->userdata['company_id']));
        if (isset($company_tags) && count($company_tags) > 0) {
            foreach ($company_tags as $tag) {
                $data['filter_tags'] .= '<option value=' . $tag->id . '>' . addslashes($tag->tags_title) . '</option>';
            }
        }
        $data['filter_tags'] .= '</select>';

        // Available days for filter list
        $data['available_days_list'] = availableDaysArrayForTableFilter();

        //$page["page_content"] = $this->load->view("admin/assign_job", $data, TRUE);
        $page["page_content"] = $this->load->view("admin/assign_job_clone", $data, TRUE);

        $this->layout->superAdminTemplateTable($page);
    }

    //map view
    public function assignJobsMap()
    {
        $ajax_call = $this->input->get('ajax-call');
        $company_id = $this->session->userdata['company_id'];
        $assign_job_view = $this->CompanyModel->getDefaultAssignJobsView($company_id);
        $page["assign_job_default"] = $assign_job_view;
        $page["active_sidebar"] = "unass_serv_routing";
        $page["page_name"] = "Assign Services";
        $page["assign_job_view"] = 1;
        $data['tecnician_details'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
        $data['service_list'] = $this->getUnassignedServiceList(false);

        $service_area_list = $this->ServiceArea->getAllServiceArea(array('company_id' => $this->session->userdata['company_id']));
        $data['service_area_list'] = $service_area_list;
        // die(print_r($this->db->last_query()));
        // die(print_r($data['service_list']));
        $data['all_jobs'] = $this->JobModel->getAllJob(array('jobs.company_id' => $this->session->userdata['company_id']));
        $data['filter_tags'] = '<select class="form-control dtatableInput" style="font-size:inherit!important;color:#9e9e9e;padding-top:8px;text-transform:uppercase;" id="tag_filter" placeholder="TAG"><option value="0" class="default-option">-- TAGS</option>';
        $company_tags = $this->TagsModel->get_all_tags(array('company_id' => $this->session->userdata['company_id']));
        if (isset($company_tags) && count($company_tags) > 0) {
            foreach ($company_tags as $tag) {
                $data['filter_tags'] .= '<option value=' . $tag->id . '>' . $tag->tags_title . '</option>';
            }
        }
        $data['filter_tags'] .= '</select>';
        // die(print_r($data['all_jobs']));
        // Available days for filter list
        $data['available_days_list'] = availableDaysArrayForTableFilter();
        // die(print_r($data['service_list']));
        $page["page_content"] = $this->load->view("admin/assign_job_map", $data, TRUE);
        //$page["page_content"] = $this->load->view("admin/assign_job_clone", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function assignJobsArchived()
    {
        $ajax_call = $this->input->get('ajax-call');
        $company_id = $this->session->userdata['company_id'];
        $page["active_sidebar"] = "arch_serv_routing";
        $page["page_name"] = "Archived Services";
        $data['tecnician_details'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
        $page["page_content"] = $this->load->view("admin/assign_jobs_archived", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function archivedJobs()
    {
        $company_id = $this->session->userdata['company_id'];
        $where_arr = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status !=' => 0,
            'property_status' => 1
        );
        $deleted_rows = $this->UnassignJobDeleteModal->getDeletedRows($where_arr);

        if ($deleted_rows) {
            echo json_encode(array('status' => 200, 'records' => $deleted_rows));
        } else {
            echo json_encode(array('status' => 200, 'records' => $deleted_rows));
        }
    }

    public function manageJobs()
    {
        $company_id = $this->session->userdata['company_id'];
        //$page["active_sidebar"] = "dashboardnav";
        $page["active_sidebar"] = "available_services";
        $page["page_name"] = "Scheduled Services";
        //$page["active_sidebar"] = "dashboardnav";
        //$page["page_name"] = "Manage Scheduled Services";
        $data['assign_data'] = $this->DashboardModel->getAssignTechnicianDisplay(array('technician_job_assign.company_id' => $company_id, 'is_job_mode' => 0));
        // die(print_r($data['assign_data']));
        $where_ar = array(
            'company_id' => $company_id
        );
        $data['tecnician_details'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
        $page["page_content"] = $this->load->view("admin/manage_jobs", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function index()
    {
        // call customer hold service scheduler
        $this->invoice_customer_hold();
        $this->customerHoldPayments();
        // end call customer hold service scheduler
        $company_id = $this->session->userdata['company_id'];
        $where_revenue = array('company_id' => $company_id, 'status' => 2);
        $data['result_revenue'] = $this->INV->getSumInvoive($where_revenue);
        $where_partial = array('company_id' => $this->session->userdata['company_id'], 'status' => 3);
        $result_partial = $this->INV->getSumInvoive($where_partial);
        //Gross revenue
        $year = date("Y-m");
        $where_revenue_total = array(
            'company_id' => $this->session->userdata['company_id'],
            'payment_status >' => 0,
            'is_archived' => 0,
            'payment_invoice_logs.payment_datetime >=' => $year . '-01'
        );
        $result_revenue_total = $this->INV->getSumInvoive($where_revenue_total);
        $data['result_revenue']->cost = $result_revenue_total->total_partial - $result_revenue_total->refund_amount_total;
        $data['OutstandingInvoiceCost'] = $this->getOutstandingInvoiceCost();
        $data['OutstandingInvoiceCost'] = number_format($data['OutstandingInvoiceCost'], 2);
        // end gross revenue
        // $data['result_revenue']->cost = $data['result_revenue']->cost + $result_partial->total_partial;
        $where_unpiad = array('company_id' => $company_id, 'status' => 1);
        $unpiad_data = $this->INV->getSumInvoive($where_unpiad);
        $data['result_revenue']->unpiad_amount = $unpiad_data->cost + $result_partial->remaning_amount;
        $d = new DateTime('first day of this month');
        $d2 = new DateTime('last day of this month');
        $date1 = strtotime($d->format('Y-m-d'));
        $date2 = strtotime($d2->format('Y-m-d'));

        $company_id = $this->session->userdata['company_id'];
        $page["active_sidebar"] = "dashboardnav";
        $page["page_name"] = "Dashboard";
        $currentdate = date("Y-m-d");
        $oneMonthdate = date('Y-m-d', strtotime("+1 month", strtotime($currentdate)));
        $where_unassign = array('technician_job_assign.company_id' => $company_id, 'is_job_mode' => 0, 'job_assign_date >=' => $currentdate, 'job_assign_date <=' => $oneMonthdate);
        $data['assign_data'] = $this->DashboardModel->getUnAssignJobsGroup($where_unassign);
        if ($data['assign_data']) {
            foreach ($data['assign_data'] as $key => $value) {
                $where_unassign['job_assign_date'] = $value->job_assign_date;
                $data['assign_data'][$key]->assign_data_result = $this->DashboardModel->getAssignTechnician($where_unassign);
            }
        }
        $data['technician_scoreboard'] = $this->DashboardModel->getTechnicianScoreboard(array('technician_job_assign.company_id' => $company_id, 'job_assign_date' => $currentdate));
        if ($data['technician_scoreboard']) {
            foreach ($data['technician_scoreboard'] as $key => $value) {
                $data['technician_scoreboard'][$key]->total_area = $value->total;
                $wherearr = array('technician_id' => $value->technician_id, 'job_assign_date' => $value->job_assign_date, 'is_job_mode' => 1, 'is_complete' => 1);
                $other_details = $this->DashboardModel->getTechnicianScoreboard($wherearr);
                if (count($other_details) > 0) {
                    $data['technician_scoreboard'][$key]->total = $other_details[0]->total;
                } else {
                    $data['technician_scoreboard'][$key]->total = 0;
                }
            }
        }

        $where_ar = array(
            'company_id' => $company_id
        );
        ##### Check if Sales Visit Program Exist ####
        $data['salesVisit'] = $this->ProgramModel->getOneProgramForCheck(array('company_id' => $company_id, 'program_name' => 'Sales Visit Standalone'));
        // die(print_r($data['salesVisit']));
        #### If "Sales Visit" program does not exist####
        if (!$data['salesVisit']) {
            // die('no program');
            $user_id = $this->session->userdata['user_id'];

            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'program_name' => 'Sales Visit Standalone',
                'program_price' => 1,
                'ad_hoc' => 1,
            );

            $check = $this->ProgramModel->checkProgram($param);
            //Create Program
            $program_created = $this->ProgramModel->insert_program($param);
            // die(print_r($program_created));

            #### Create JOB
            $param2 = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'job_name' => 'Sales Visit',
                'job_price' => 0,
                'job_description' => 'Prospect property',
                'job_notes' => 'Prospect 1st contact visit',
                'base_fee_override' => 0,
                'min_fee_override' => 0,
                'service_type_id' => 0,
                'commission_type' => 0,
                'bonus_type' => 0,
                'ad_hoc' => 1,
            );
            //print_r($param); die();

            $job_created = $this->JobModel->CreateOneJob($param2);
            // die(print_r($job_created));
            // $data['job_created'] = $job_created;
            // die(print_r($data['job_created']));

            #### Assign JOB to Program

            $param3 = array(
                'program_id' => $program_created,
                'job_id' => $job_created,
                'priority' => 1
            );
            //Assign jobs to program
            $result1 = $this->ProgramModel->assignProgramJobs($param3);
            //    die(print_r($result1));

            // $data['program_created'] = $program_created;
        } else {
            $data['program_created'] = $data['salesVisit']->program_id;
            // die(print_r($data['program_created']));

        }


        $data['company_details'] = $this->CompanyModel->getOneCompany($where_ar);
        $get_data = $this->getWeatherInfo($data['company_details']->company_address_lat, $data['company_details']->company_address_long);
        if ($get_data['status'] == 200) {
            $data['widget_data'] = $get_data['result'];
        } else {
            $data['widget_data'] = array();
        }

        $data['tecnician_details'] = $this->Administrator->getAllAdmin(array('role_id' => 4, 'company_id' => $this->session->userdata['company_id']));
        $page["page_content"] = $this->load->view("admin/dashboard2", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
        $this->load->helper('form'); //made by acap....not sure if needed
    }


    public function getCalederbyYnassignJobs()
    {

        $company_id = $this->session->userdata['company_id'];
        $param = $this->input->post();
        $param['tecnician_id_array'] = json_decode($param['tecnician_id_array']);

        $currentdate = date('Y-m-d', strtotime($param['datesting']));

        $oneMonthdate = date('Y-m-d', strtotime("+1 month", strtotime($currentdate)));


        $where_unassign = array('technician_job_assign.company_id' => $company_id, 'is_job_mode' => 0, 'job_assign_date >=' => $currentdate, 'job_assign_date <=' => $oneMonthdate);

        $data['assign_data'] = $this->DashboardModel->getUnAssignJobsGroup($where_unassign, $param['tecnician_id_array']);


        if ($data['assign_data']) {

            foreach ($data['assign_data'] as $key => $value) {
                $where_unassign['job_assign_date'] = $value->job_assign_date;
                $data['assign_data'][$key]->assign_data_result = $this->DashboardModel->getAssignTechnician($where_unassign, $param['tecnician_id_array']);
            }
        }

        $data['currentdate'] = $currentdate;


        $html = $this->load->view('caledr_data_ajax', $data, true);
        echo $html;
    }

    public function getTechnicianScoreboard()
    {
        $data = $this->input->post();
        $company_id = $this->session->userdata['company_id'];
        $where = array('technician_job_assign.company_id' => $company_id, 'is_job_mode' => 1, 'is_complete' => 1);
        $wherearr = array('technician_job_assign.company_id' => $company_id);
        if ($data['from_date'] != '') {
            $where['job_assign_date >='] = $data['from_date'];
            $wherearr['job_assign_date >='] = $data['from_date'];
        }
        if ($data['to_date'] != '') {
            $where['job_assign_date <='] = $data['to_date'];
            $wherearr['job_assign_date <='] = $data['to_date'];
        }
        $technician_scoreboard = $this->DashboardModel->getTechnicianScoreboard($wherearr);
        if ($technician_scoreboard) {
            foreach ($technician_scoreboard as $key => $value) {
                $technician_scoreboard[$key]->total_area = $value->total;
                $where['technician_id'] = $value->technician_id;
                $wherearr['technician_id'] = $value->technician_id;
                $other_details = $this->DashboardModel->getTechnicianScoreboard($where);
                if (count($other_details) > 0) {
                    $technician_scoreboard[$key]->total = $other_details[0]->total;
                } else {
                    $technician_scoreboard[$key]->total = 0;
                }
            }
            $array_return = array('status' => 200, 'result' => $technician_scoreboard);
        } else {
            $array_return = array('status' => 400, 'result' => array());
        }
        echo json_encode($array_return);
    }

    public function deleteRestoreMultiUnassignedJobs()
    {
        $group_ids = $this->input->post('group_id');
        $action = $this->input->post('action');
        $row_counter = 0;

        if (count($group_ids) > 0) {
            foreach ($group_ids as $group_id) {
                $group_id_parts = explode(':', $group_id);
                $customer_id = $group_id_parts[0];
                $job_id = $group_id_parts[1];
                $program_id = $group_id_parts[2];
                $property_id = $group_id_parts[3];
                if ($action == 'delete') {
                    $param_arr = array(
                        'customer_id' => $customer_id,
                        'job_id' => $job_id,
                        'program_id' => $program_id,
                        'property_id' => $property_id,
                    );
                    $result = $this->UnassignJobDeleteModal->createDeleteRow($param_arr);

                } else {
                    $where_arr = array(
                        'customer_id' => $customer_id,
                        'job_id' => $job_id,
                        'program_id' => $program_id,
                        'property_id' => $property_id,
                    );
                    #remove from unassign_job_delete
                    $result = $this->UnassignJobDeleteModal->removeDeleteRow($where_arr);
                }
                if ($result) {
                    $row_counter++;
                }
            }
        }
        if ($row_counter == count($group_ids)) {
            if ($action == 'delete') {
                $msg = 'Unassign Service(s) deleted successfully';
            } else {
                $msg = 'Unassign Service(s) restored successfully';
            }
            echo json_encode(array('status' => 200, 'msg' => $msg));
        } else {
            echo json_encode(array('status' => 400, 'msg' => 'Something went wrong'));
        }
    }


    public function deleteRestoreUnassignedJob($value = '')
    {
        $group_id = $this->input->post('group_id');
        $action = $this->input->post('action');
        $group_id = explode(':', $group_id);
        if ($action == "delete") {
            $param_arr = array(
                'customer_id' => $group_id[0],
                'job_id' => $group_id[1],
                'program_id' => $group_id[2],
                'property_id' => $group_id[3],
            );

            $result = $this->UnassignJobDeleteModal->createDeleteRow($param_arr);

        } else {
            $where_arr = array(
                'customer_id' => $group_id[0],
                'job_id' => $group_id[1],
                'program_id' => $group_id[2],
                'property_id' => $group_id[3],
            );
            #removed from unassign_job_delete
            $result = $this->UnassignJobDeleteModal->removeDeleteRow($where_arr);
        }

        if ($result) {
            if ($action == "delete") {
                $msg = "Unassign Service deleted successfully";
            } else {
                $msg = "Unassign Service restored successfully";
            }
            echo json_encode(array('status' => 200, 'msg' => $msg));
        } else {
            echo json_encode(array('status' => 400, 'msg' => 'Something went wrong'));
        }
    }

    public function scheduledJobsData()
    {
        $company_id = $this->session->userdata['company_id'];
        $data = $this->DashboardModel->getAssignTechnicianJson(array('technician_job_assign.company_id' => $company_id, 'is_job_mode' => 0));
        //        echo $this->db->last_query();
        echo json_encode($data);
    }


    public function getOneAssignJsonbData($technician_job_assign_id)
    {
        $data = $this->DashboardModel->getOneAssignTechnician(array('technician_job_assign_id' => $technician_job_assign_id));

        $return_array = array(
            'technician_job_assign_id' => $data->technician_job_assign_id,
            'technician_id' => $data->technician_id,
            'username' => $data->user_first_name . ' ' . $data->user_last_name,
            'job_assign_date' => $data->job_assign_date,
            'route_id' => $data->route_id,
            'is_time_check' => $data->is_time_check,
            'specific_time' => date("H:i", strtotime($data->specific_time)),
            'job_assign_notes' => $data->job_assign_notes,
        );
        echo json_encode($return_array);
    }


    public function getOneRouteDetails($route_id = '')
    {

        if ($route_id == '') {
            $return_array = array('status' => 400, 'msg' => 'route id empty', 'result' => '');
        } else {
            $result = $this->Tech->GetOneRoute(array('route_id' => $route_id));
            if ($result) {
                $result['specific_time'] = $result['specific_time'];
                $return_array = array('status' => 200, 'msg' => 'successfully', 'result' => $result);
            } else {
                $return_array = array('status' => 400, 'msg' => 'Something went wrong', 'result' => '');
            }
        }

        echo json_encode($return_array);
    }

    public function editTecnicianJobAssign()
    {
        $data = $this->input->post();
        //die(print_r($data));
        $this->form_validation->set_rules('technician_job_assign_id', 'technician_job_assign_id', 'trim|required');
        $this->form_validation->set_rules('technician_id', 'technician_id', 'trim|required');
        $this->form_validation->set_rules('job_assign_date', 'job_assign_date', 'trim|required');
        $this->form_validation->set_rules('job_assign_notes', 'job_assign_notes', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {

            $route_id = $this->manageRoute($data);
            $checkAlreadyTime = $this->checkAlreadySpecificTime(1, $data);

            if ($checkAlreadyTime) {
                //if job assign date changed, then update the invoice
                if (isset($data['old_job_assign_date']) && $data['old_job_assign_date'] != $data['job_assign_date']) {
                    //get tech assign job details
                    $details = $this->Tech->GetOneRow(array('technician_job_assign_id' => $data['technician_job_assign_id']));

                    if ($details) {
                        $customer_id = $details->customer_id;
                        $property_id = $details->property_id;
                        $program_id = $details->program_id;
                        $job_id = $details->job_id;
                        $property_program = $this->PropertyModel->getOnePropertyProgram(array('property_id' => $property_id, 'program_id' => $program_id));
                        $property_program_id = $property_program->property_program_id;

                        //Get Job Cost
                        $jobDetails = $this->JobModel->getOneJob(array('job_id' => $job_id));
                        //die(print_r($jobDetails));
                        $estimate_price_override = GetOneEstimateJobPriceOverride(array('customer_id' => $customer_id, 'property_id' => $property_id, 'program_id' => $program_id, 'job_id' => $job_id));
                        if ($estimate_price_override) {
                            $job_cost = $estimate_price_override->price_override;
                        } else {
                            $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $program_id));

                            if ($priceOverrideData->is_price_override_set == 1) {
                                $job_cost = $priceOverrideData->price_override;
                            } else {
                                $propertyDetails = $this->PropertyModel->getOnePropertyDetail($property_id);
                                $price = $jobDetails->job_price;

                                $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                                //get property difficulty level
                                if (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 2) {
                                    $difficulty_multiplier = $setting_details->dlmult_2;
                                } elseif (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 3) {
                                    $difficulty_multiplier = $setting_details->dlmult_3;
                                } else {
                                    $difficulty_multiplier = $setting_details->dlmult_1;
                                }

                                //get base fee
                                if (isset($jobDetails->base_fee_override)) {
                                    $base_fee = $jobDetails->base_fee_override;
                                } else {
                                    $base_fee = $setting_details->base_service_fee;
                                }

                                $cost_per_sqf = $base_fee + ($price * $propertyDetails->yard_square_feet * $difficulty_multiplier) / 1000;

                                //get min. service fee
                                if (isset($jobDetails->min_fee_override)) {
                                    $min_fee = $jobDetails->min_fee_override;
                                } else {
                                    $min_fee = $setting_details->minimum_service_fee;
                                }
                                // Compare cost per sf with min service fee
                                if ($cost_per_sqf > $min_fee) {
                                    $job_cost = $cost_per_sqf;
                                } else {
                                    $job_cost = $min_fee;
                                }
                            }
                        }

                        //get program invoice method
                        $checkInvMethod = $this->ProgramModel->getOneProgramForCheck(array('program_id' => $program_id));

                        //If Program Price = At Job Completion
                        if ($checkInvMethod->program_price == 2) {

                            //check if invoice has been created for this job
                            if (!empty($details->invoice_id)) {
                                //handle invoice from previous assign date...

                                $invoice_id = $details->invoice_id;

                                //delete row from PPJOBINV table for this property, program, job, invoice
                                $deletePPJOBINV = $this->PropertyProgramJobInvoiceModel->deletePropertyProgramJobInvoice(array('job_id' => $details->job_id, 'invoice_id' => $invoice_id));

                                //get all jobs with same invoice id
                                $PPJOBINV = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id' => $invoice_id));

                                //if jobs then update invoice
                                if ($PPJOBINV) {
                                    //calculate new invoice total
                                    $total_cost = 0;
                                    $description = "";
                                    foreach ($PPJOBINV as $invoicedJob) {
                                        $job_details = $this->JobModel->getOneJob(array('job_id' => $invoicedJob['job_id']));
                                        $total_cost += $invoicedJob['job_cost'];
                                        $description .= $job_details->job_name . " ";
                                    }
                                    //update invoice
                                    $updateArr = array(
                                        'cost' => $total_cost,
                                        'description' => $description,
                                        'invoice_updated' => date("Y-m-d H:i:s"),
                                    );
                                    $updateInv = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), $updateArr);


                                    //update sales tax
                                    $get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
                                    //die(print_r($get_invoice_tax));
                                    if (!empty($get_invoice_tax)) {

                                        foreach ($get_invoice_tax as $g_i_t) {
                                            $invoice_tax_details = array(
                                                'invoice_id' => $invoice_id,
                                                'tax_name' => $g_i_t['tax_name'],
                                                'tax_value' => $g_i_t['tax_value'],
                                                'tax_value' => $g_i_t['tax_value'],
                                                'tax_amount' => $total_cost * $g_i_t['tax_value'] / 100
                                            );
                                            //delete old sales tax record
                                            $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id, 'tax_name' => $g_i_t['tax_name']));
                                            //create new sales tax record
                                            $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                        }

                                    }
                                    //echo $total_cost;
                                } else {
                                    //if no other jobs with invoice id then delete(archive) invoice
                                    $archiveInv = $this->INV->deleteInvoice(array('invoice_id' => $invoice_id));
                                }
                            } //end if invoice

                            //check for other jobs that have been scheduled for this property program, on the NEW job assign date
                            $property_program_date_assigned_jobs = $this->DashboardModel->getAllTechAssignJobs(array('technician_job_assign.property_id' => $property_id, 'technician_job_assign.job_assign_date' => $data['job_assign_date'], 'technician_job_assign.program_id' => $program_id));

                            if ($property_program_date_assigned_jobs) {
                                //get invoice id
                                $invoice = 0;
                                foreach ($property_program_date_assigned_jobs as $job) {
                                    if (!empty($job['invoice_id'])) {
                                        //make sure invoice hasn't been paid
                                        $checkPaid = $this->INV->getOneRow($job['invoice_id']);
                                        if (isset($checkPaid->payment_status) && $checkPaid->payment_status != 2 && $checkPaid->payment_status != 1) {
                                            $invoice_id = $job['invoice_id'];
                                        }
                                    }
                                }
                                if (!empty($invoice)) {
                                    //store property_program_job_invoice data
                                    $newPPJOBINV = array(
                                        'customer_id' => $customer_id,
                                        'property_id' => $property_id,
                                        'program_id' => $program_id,
                                        'property_program_id' => $property_program_id,
                                        'job_id' => $job_id,
                                        'invoice_id' => $invoice,
                                        'job_cost' => $job_cost,
                                        'created_at' => date("Y-m-d H:i:s"),
                                        'updated_at' => date("Y-m-d H:i:s"),
                                    );
                                    $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);

                                    //get all jobs with same invoice id
                                    $ALL_PPJOBINV = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id' => $invoice));

                                    //if jobs then update invoice
                                    if ($ALL_PPJOBINV) {
                                        //calculate new invoice total
                                        $total_cost = 0;
                                        $description = "";
                                        foreach ($ALL_PPJOBINV as $invoice_job) {
                                            $job_details = $this->JobModel->getOneJob(array('job_id' => $invoice_job['job_id']));
                                            $total_cost += $invoice_job['job_cost'];
                                            $description .= $job_details->job_name . " ";
                                        }
                                        //update invoice
                                        $updateArr = array(
                                            'cost' => $total_cost,
                                            'description' => $description,
                                            'invoice_updated' => date("Y-m-d H:i:s"),
                                        );
                                        $updateInv = $this->INV->updateInvoive(array('invoice_id' => $invoice), $updateArr);


                                        //update sales tax
                                        $get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
                                        //die(print_r($get_invoice_tax));
                                        if (!empty($get_invoice_tax)) {

                                            foreach ($get_invoice_tax as $g_i_t) {
                                                $invoice_tax_details = array(
                                                    'invoice_id' => $invoice_id,
                                                    'tax_name' => $g_i_t['tax_name'],
                                                    'tax_value' => $g_i_t['tax_value'],
                                                    'tax_value' => $g_i_t['tax_value'],
                                                    'tax_amount' => $total_cost * $g_i_t['tax_value'] / 100
                                                );
                                                //delete old sales tax record
                                                $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id, 'tax_name' => $g_i_t['tax_name']));
                                                //create new sales tax record
                                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                            }

                                        }
                                    }
                                    //update tech_job_assign

                                    $where = array(
                                        'technician_job_assign_id' => $data['technician_job_assign_id'],
                                    );

                                    $update = $this->DashboardModel->updateAssignJob(array('technician_job_assign_id' => $data['technician_job_assign_id']), array('invoice_id' => $invoice));
                                }
                            } else {
                                // else no other jobs, then create new invoice, update tech_job_assign table, insert new row into PPJOBINV table
                                $invParams = array(
                                    'customer_id' => $customer_id,
                                    'property_id' => $property_id,
                                    'program_id' => $program_id,
                                    'description' => $jobDetails->job_name,
                                    'user_id' => $this->session->userdata['user_id'],
                                    'company_id' => $this->session->userdata['company_id'],
                                    'invoice_date' => date("Y-m-d"),
                                    'cost' => ($job_cost),
                                    'is_created' => 0,
                                    'invoice_created' => date("Y-m-d H:i:s"),
                                    //'json'=>json_encode($json),
                                );
                                $invoice = $this->INV->createOneInvoice($invParams);

                                if ($invoice) {
                                    //store property_program_job_invoice data
                                    $newPPJOBINV = array(
                                        'customer_id' => $customer_id,
                                        'property_id' => $property_id,
                                        'program_id' => $program_id,
                                        'property_program_id' => $property_program_id,
                                        'job_id' => $job_id,
                                        'invoice_id' => $invoice,
                                        'job_cost' => $job_cost,
                                        'created_at' => date("Y-m-d H:i:s"),
                                        'updated_at' => date("Y-m-d H:i:s"),
                                    );
                                    $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);

                                    //figure tax
                                    $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                                    if ($setting_details->is_sales_tax == 1) {
                                        $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $property_id));
                                        if ($property_assign_tax) {
                                            foreach ($property_assign_tax as $tax_details) {

                                                $invoice_tax_details = array(
                                                    'invoice_id' => $invoice,
                                                    'tax_name' => $tax_details['tax_name'],
                                                    'tax_value' => $tax_details['tax_value'],
                                                    'tax_amount' => $job_cost * $tax_details['tax_value'] / 100
                                                );

                                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                            }
                                        }
                                    }
                                    //update tech_job_assign

                                    $where = array(
                                        'technician_job_assign_id' => $data['technician_job_assign_id'],
                                    );

                                    $update = $this->DashboardModel->updateAssignJob(array('technician_job_assign_id' => $data['technician_job_assign_id']), array('invoice_id' => $invoice));

                                    #coupon
                                    $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $customer_id));
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
                                                $params = array(
                                                    'coupon_id' => $coupon_id,
                                                    'invoice_id' => $invoice,
                                                    'coupon_code' => $coupon_details->code,
                                                    'coupon_amount' => $coupon_details->amount,
                                                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                                    'coupon_type' => $coupon_details->type
                                                );
                                                $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                                            }
                                        }
                                    }
                                }
                            }
                        } //end if program price = at completion
                    }
                } //end if date change
                // die(print_r($data));
                $param = array(
                    'technician_id' => $data['technician_id'],
                    'job_assign_date' => $data['job_assign_date'],
                    'route_id' => $route_id,
                    'job_assign_notes' => $data['job_assign_notes'],
                    'job_assign_updated_date' => date("Y-m-d H:i:s")
                );

                if (array_key_exists('specific_time_check', $data)) {
                    $param['is_time_check'] = 1;
                    $param['specific_time'] = $data['specific_time'];
                } else {
                    $param['is_time_check'] = 0;
                    $param['specific_time'] = '00:00:00';
                }


                $where = array(
                    'technician_job_assign_id' => $data['technician_job_assign_id'],
                );

                $result = $this->DashboardModel->updateAssignJob($where, $param);

                if ($result) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Scheduled </strong> Service updated successfully</div>');
                    redirect("admin/index");
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Scheduled </strong>Service not updated. Please try again.</div>');
                    redirect("admin/index");
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Already exists specific time in this date please change time</div>');
                redirect("admin/index");
            }
        }
    }


    function editTecnicianJobAssignCalender()
    {

        $data = $this->input->post();
        $checkAlreadyTime = $this->checkAlreadySpecificTime(1, $data);

        if ($checkAlreadyTime) {
            $route_id = $this->manageRoute($data);
            $where = array(
                'technician_job_assign_id' => $data['technician_job_assign_id'],
            );

            $param = array(
                'job_assign_date' => $data['job_assign_date'],
                'route_id' => $route_id,
            );

            if (array_key_exists('specific_time_check', $data)) {
                $param['is_time_check'] = 1;
                $param['specific_time'] = $data['specific_time'];
            } else {
                $param['is_time_check'] = 0;
                $param['specific_time'] = '00:00:00';
            }

            $result = $this->DashboardModel->updateAssignJob($where, $param);

            if ($result) {
                $return_array = array('status' => 200, 'msg' => 'Service updated successfully');
            } else {
                $return_array = array('status' => 400, 'msg' => 'Something went wrong!');
            }

            echo json_encode($return_array);
        } else {
            echo json_encode(array('status' => 400, 'msg' => 'Already exists specific time in this date please change time'));
        }
    }

    public function updateMultipleAssignJob()
    {

        $data = $this->input->post();
        //die(print_r($data));
        if (!empty($data['multiple_technician_job_assign_id'])) {
            $route_id = $this->manageRoute($data);


            $multiple_technician_job_assign_id = explode(",", $data['multiple_technician_job_assign_id']);

            $data['technician_job_assign_id'] = $multiple_technician_job_assign_id[0];


            $checkAlreadyTime = $this->checkAlreadySpecificTime(count($multiple_technician_job_assign_id), $data);

            if ($checkAlreadyTime) {

                foreach ($multiple_technician_job_assign_id as $value) {
                    //if job assign date changed, then handle the invoice
                    $old_details = $this->Tech->GetOneRow(array('technician_job_assign_id' => $value));

                    if (isset($old_details->job_assign_date) && $old_details->job_assign_date != $data['job_assign_date']) {
                        $customer_id = $old_details->customer_id;
                        $property_id = $old_details->property_id;
                        $program_id = $old_details->program_id;
                        $job_id = $old_details->job_id;
                        $property_program = $this->PropertyModel->getOnePropertyProgram(array('property_id' => $property_id, 'program_id' => $program_id));
                        $property_program_id = $property_program->property_program_id;

                        //Get Job Cost
                        $jobDetails = $this->JobModel->getOneJob(array('job_id' => $job_id));
                        $estimate_price_override = GetOneEstimateJobPriceOverride(array('customer_id' => $customer_id, 'property_id' => $property_id, 'program_id' => $program_id, 'job_id' => $job_id));
                        if ($estimate_price_override) {
                            $job_cost = $estimate_price_override->price_override;
                        } else {
                            $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $program_id));

                            if ($priceOverrideData->is_price_override_set == 1) {
                                $job_cost = $priceOverrideData->price_override;
                            } else {
                                $propertyDetails = $this->PropertyModel->getOnePropertyDetail($property_id);
                                $price = $jobDetails->job_price;

                                $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                                //get property difficulty level
                                if (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 2) {
                                    $difficulty_multiplier = $setting_details->dlmult_2;
                                } elseif (isset($propertyDetails->difficulty_level) && $propertyDetails->difficulty_level == 3) {
                                    $difficulty_multiplier = $setting_details->dlmult_3;
                                } else {
                                    $difficulty_multiplier = $setting_details->dlmult_1;
                                }

                                //get base fee
                                if (isset($jobDetails->base_fee_override)) {
                                    $base_fee = $jobDetails->base_fee_override;
                                } else {
                                    $base_fee = $setting_details->base_service_fee;
                                }

                                $cost_per_sqf = $base_fee + ($price * $propertyDetails->yard_square_feet * $difficulty_multiplier) / 1000;

                                //get min. service fee
                                if (isset($jobDetails->min_fee_override)) {
                                    $min_fee = $jobDetails->min_fee_override;
                                } else {
                                    $min_fee = $setting_details->minimum_service_fee;
                                }
                                // Compare cost per sf with min service fee
                                if ($cost_per_sqf > $min_fee) {
                                    $job_cost = $cost_per_sqf;
                                } else {
                                    $job_cost = $min_fee;
                                }
                            }
                        }
                        //get program invoice method
                        $checkInvMethod = $this->ProgramModel->getOneProgramForCheck(array('program_id' => $program_id));

                        //If Program Price = At Job Completion
                        if ($checkInvMethod->program_price == 2) {

                            //check if invoice has been created for this job
                            if (!empty($old_details->invoice_id)) {
                                //handle invoice from previous assign date...

                                $invoice_id = $old_details->invoice_id;

                                //delete row from PPJOBINV table for this property, program, job, invoice
                                $deletePPJOBINV = $this->PropertyProgramJobInvoiceModel->deletePropertyProgramJobInvoice(array('job_id' => $job_id, 'invoice_id' => $invoice_id));

                                //get all jobs with same invoice id
                                $PPJOBINV = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id' => $invoice_id));

                                //if jobs then update invoice
                                if ($PPJOBINV) {
                                    //calculate new invoice total
                                    $total_cost = 0;
                                    $description = "";
                                    foreach ($PPJOBINV as $invoicedJob) {
                                        $job_details = $this->JobModel->getOneJob(array('job_id' => $invoicedJob['job_id']));
                                        $total_cost += $invoicedJob['job_cost'];
                                        $description .= $job_details->job_name . " ";
                                    }
                                    //update invoice
                                    $updateArr = array(
                                        'cost' => $total_cost,
                                        'description' => $description,
                                        'invoice_updated' => date("Y-m-d H:i:s"),
                                    );
                                    $updateInv = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), $updateArr);

                                    //update sales tax
                                    $get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
                                    //die(print_r($get_invoice_tax));
                                    if (!empty($get_invoice_tax)) {

                                        foreach ($get_invoice_tax as $g_i_t) {
                                            $invoice_tax_details = array(
                                                'invoice_id' => $invoice_id,
                                                'tax_name' => $g_i_t['tax_name'],
                                                'tax_value' => $g_i_t['tax_value'],
                                                'tax_value' => $g_i_t['tax_value'],
                                                'tax_amount' => $total_cost * $g_i_t['tax_value'] / 100
                                            );
                                            //delete old sales tax record
                                            $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id, 'tax_name' => $g_i_t['tax_name']));
                                            //create new sales tax record
                                            $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                        }

                                    }
                                    //echo $total_cost;
                                } else {
                                    //if no other jobs with invoice id then delete(archive) invoice
                                    $archiveInv = $this->INV->deleteInvoice(array('invoice_id' => $invoice_id));
                                }
                            } //end if invoice

                            //check for other jobs that have been scheduled for this property program, on the NEW job assign date
                            $property_program_date_assigned_jobs = $this->DashboardModel->getAllTechAssignJobs(array('technician_job_assign.property_id' => $property_id, 'technician_job_assign.job_assign_date' => $data['job_assign_date'], 'technician_job_assign.program_id' => $program_id));

                            if ($property_program_date_assigned_jobs) {
                                //get invoice id
                                $invoice = 0;
                                foreach ($property_program_date_assigned_jobs as $job) {
                                    if (!empty($job['invoice_id'])) {
                                        $invoice = $job['invoice_id'];
                                    }
                                }
                                if (!empty($invoice)) {
                                    //store property_program_job_invoice data
                                    $newPPJOBINV = array(
                                        'customer_id' => $customer_id,
                                        'property_id' => $property_id,
                                        'program_id' => $program_id,
                                        'property_program_id' => $property_program_id,
                                        'job_id' => $job_id,
                                        'invoice_id' => $invoice,
                                        'job_cost' => $job_cost,
                                        'created_at' => date("Y-m-d H:i:s"),
                                        'updated_at' => date("Y-m-d H:i:s"),
                                    );
                                    $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);

                                    //get all jobs with same invoice id
                                    $ALL_PPJOBINV = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id' => $invoice));

                                    //if jobs then update invoice
                                    if ($ALL_PPJOBINV) {
                                        //calculate new invoice total
                                        $total_cost = 0;
                                        $description = "";
                                        foreach ($ALL_PPJOBINV as $invoice_job) {
                                            $job_details = $this->JobModel->getOneJob(array('job_id' => $invoice_job['job_id']));
                                            $total_cost += $invoice_job['job_cost'];
                                            $description .= $job_details->job_name . " ";
                                        }
                                        //update invoice
                                        $updateArr = array(
                                            'cost' => $total_cost,
                                            'description' => $description,
                                            'invoice_updated' => date("Y-m-d H:i:s"),
                                        );
                                        $updateInv = $this->INV->updateInvoive(array('invoice_id' => $invoice), $updateArr);


                                        //update sales tax
                                        $get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
                                        //die(print_r($get_invoice_tax));
                                        if (!empty($get_invoice_tax)) {

                                            foreach ($get_invoice_tax as $g_i_t) {
                                                $invoice_tax_details = array(
                                                    'invoice_id' => $invoice_id,
                                                    'tax_name' => $g_i_t['tax_name'],
                                                    'tax_value' => $g_i_t['tax_value'],
                                                    'tax_value' => $g_i_t['tax_value'],
                                                    'tax_amount' => $total_cost * $g_i_t['tax_value'] / 100
                                                );
                                                //delete old sales tax record
                                                $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id, 'tax_name' => $g_i_t['tax_name']));
                                                //create new sales tax record
                                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                            }

                                        }
                                    }
                                    //update tech_job_assign

                                    $where = array(
                                        'technician_job_assign_id' => $value,
                                    );

                                    $update = $this->DashboardModel->updateAssignJob(array('technician_job_assign_id' => $value), array('invoice_id' => $invoice));
                                }
                            } else {
                                // else no other jobs, then create new invoice, update tech_job_assign table, insert new row into PPJOBINV table
                                $invParams = array(
                                    'customer_id' => $customer_id,
                                    'property_id' => $property_id,
                                    'program_id' => $program_id,
                                    'description' => $jobDetails->job_name,
                                    'user_id' => $this->session->userdata['user_id'],
                                    'company_id' => $this->session->userdata['company_id'],
                                    'invoice_date' => date("Y-m-d"),
                                    'cost' => ($job_cost),
                                    'is_created' => 0,
                                    'invoice_created' => date("Y-m-d H:i:s"),
                                    //'json'=>json_encode($json),
                                );
                                $invoice = $this->INV->createOneInvoice($invParams);

                                if ($invoice) {
                                    //store property_program_job_invoice data
                                    $newPPJOBINV = array(
                                        'customer_id' => $customer_id,
                                        'property_id' => $property_id,
                                        'program_id' => $program_id,
                                        'property_program_id' => $property_program_id,
                                        'job_id' => $job_id,
                                        'invoice_id' => $invoice,
                                        'job_cost' => $job_cost,
                                        'created_at' => date("Y-m-d H:i:s"),
                                        'updated_at' => date("Y-m-d H:i:s"),
                                    );
                                    $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);

                                    //figure tax
                                    $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                                    if ($setting_details->is_sales_tax == 1) {
                                        $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $property_id));
                                        if ($property_assign_tax) {
                                            foreach ($property_assign_tax as $tax_details) {

                                                $invoice_tax_details = array(
                                                    'invoice_id' => $invoice,
                                                    'tax_name' => $tax_details['tax_name'],
                                                    'tax_value' => $tax_details['tax_value'],
                                                    'tax_amount' => $job_cost * $tax_details['tax_value'] / 100
                                                );

                                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                            }
                                        }
                                    }
                                    //update tech_job_assign

                                    $where = array(
                                        'technician_job_assign_id' => $value,
                                    );

                                    $update = $this->DashboardModel->updateAssignJob(array('technician_job_assign_id' => $value), array('invoice_id' => $invoice));

                                    #coupon
                                    $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $customer_id));
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
                                                $params = array(
                                                    'coupon_id' => $coupon_id,
                                                    'invoice_id' => $invoice,
                                                    'coupon_code' => $coupon_details->code,
                                                    'coupon_amount' => $coupon_details->amount,
                                                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                                    'coupon_type' => $coupon_details->type
                                                );
                                                $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                                            }
                                        }
                                    }
                                }
                            }
                        } //end if program price = at completion

                    } //end if change job assign date

                    $param = array(
                        'technician_id' => $data['technician_id'],
                        'job_assign_date' => $data['job_assign_date'],
                        'job_assign_notes' => $data['job_assign_notes'],
                        'route_id' => $route_id

                    );

                    if (count($multiple_technician_job_assign_id) == 1 && array_key_exists('specific_time_check', $data)) {
                        $param['is_time_check'] = 1;
                        $param['specific_time'] = $data['specific_time'];
                    } else {
                        $param['is_time_check'] = 0;
                        $param['specific_time'] = '00:00:00';
                    }


                    $this->updateSingleAssignTableRow($param, $value);
                }
                echo json_encode(array('status' => 200, 'msg' => 'Services updated successfully'));
            } else {

                echo json_encode(array('status' => 400, 'msg' => 'Already exists specific time in this date please change time'));
            }
        } else {

            echo json_encode(array('status' => 400, 'msg' => 'Something went wrong!'));
        }
    }

    public function updateSingleAssignTableRow($param, $technician_job_assign_id)
    {

        $where = array(
            'technician_job_assign_id' => $technician_job_assign_id,
        );

        $result = $this->DashboardModel->updateAssignJob($where, $param);

        return $result;
    }


    public function ScheduledJobDetete($technician_job_assign_id)
    {
        //  echo $technician_job_assign_id;
        // die();

        $oneTimeInvoice = 0;
        $where = array('technician_job_assign_id' => $technician_job_assign_id);
        $details = $this->Tech->GetOneRow($where);
        if ($details) {
            $checkInvMethod = $this->ProgramModel->getOneProgramForCheck(array('program_id' => $details->program_id));
            if ($checkInvMethod->program_price == 1) {
                $oneTimeInvoice = 1;
            }
            if ($oneTimeInvoice != 1) {
                //check for existing invoice
                if ($details->invoice_id) {
                    $invoice_id = $details->invoice_id;


                    $deleted_job = $details->job_id;

                    //delete record from PropertyProgramJobInvoice table
                    $deletePPJOBINV = $this->PropertyProgramJobInvoiceModel->deletePropertyProgramJobInvoice(array('job_id' => $deleted_job, 'invoice_id' => $invoice_id));

                    //get all jobs with same invoice id
                    $PPJOBINV = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id' => $invoice_id));

                    //if jobs then update invoice
                    if ($PPJOBINV) {
                        //calculate new invoice total
                        $total_cost = 0;
                        $description = "";
                        foreach ($PPJOBINV as $invoicedJob) {
                            $job_details = $this->JobModel->getOneJob(array('job_id' => $invoicedJob['job_id']));
                            $total_cost += $invoicedJob['job_cost'];
                            $description .= $job_details->job_name . " ";
                        }
                        //update invoice
                        $updateArr = array(
                            'cost' => $total_cost,
                            'description' => $description,
                            'invoice_updated' => date("Y-m-d H:i:s"),
                        );
                        $updateInv = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), $updateArr);


                        //update sales tax
                        $get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
                        //die(print_r($get_invoice_tax));
                        if (!empty($get_invoice_tax)) {

                            foreach ($get_invoice_tax as $g_i_t) {
                                $invoice_tax_details = array(
                                    'invoice_id' => $invoice_id,
                                    'tax_name' => $g_i_t['tax_name'],
                                    'tax_value' => $g_i_t['tax_value'],
                                    'tax_value' => $g_i_t['tax_value'],
                                    'tax_amount' => $total_cost * $g_i_t['tax_value'] / 100
                                );
                                //delete old sales tax record
                                $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id, 'tax_name' => $g_i_t['tax_name']));
                                //create new sales tax record
                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                            }

                        }
                        //echo $total_cost;
                    } else {
                        //if no other jobs with invoice id then delete(archive) invoice
                        $archiveInv = $this->INV->deleteInvoice(array('invoice_id' => $invoice_id));
                    }
                    //$invoice_jobs = $this->Tech->getAllJobAssign(array('technician_job_assign.invoice_id'=> $invoice_id));

                }
            }


            $result = $this->DashboardModel->deleteAssignJob($where);

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("admin/index");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Scheduled </strong>Service deleted successfully</div>');
                redirect("admin/index");
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');
            redirect("admin/index");
        }
    }

    public function deletemultipleJobAssign()
    {
        $job_assign_ids = $this->input->post('job_assign_ids');
        // var_dump($job_assign_ids);
        //die(print_r($job_assign_ids));

        if (!empty($job_assign_ids)) {
            foreach ($job_assign_ids as $key => $value) {
                $where = $this->ScheduledJobDeteteFun($value);
            }
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ScheduledJobDeteteFun($technician_job_assign_id)
    {
        $oneTimeInvoice = 0;
        $where = array('technician_job_assign_id' => $technician_job_assign_id);
        $details = $this->Tech->GetOneRow($where);
        if ($details) {
            $checkInvMethod = $this->ProgramModel->getOneProgramForCheck(array('program_id' => $details->program_id));
            if ($checkInvMethod->program_price == 1) {
                $oneTimeInvoice = 1;
            }
            if ($oneTimeInvoice != 1) {
                //check for existing invoice
                if ($details->invoice_id) {
                    $invoice_id = $details->invoice_id;


                    $deleted_job = $details->job_id;

                    //delete record from PropertyProgramJobInvoice table
                    $deletePPJOBINV = $this->PropertyProgramJobInvoiceModel->deletePropertyProgramJobInvoice(array('job_id' => $deleted_job, 'invoice_id' => $invoice_id));

                    //get all jobs with same invoice id
                    $PPJOBINV = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id' => $invoice_id));

                    //if jobs then update invoice
                    if ($PPJOBINV) {
                        //calculate new invoice total
                        $total_cost = 0;
                        $description = "";
                        foreach ($PPJOBINV as $invoicedJob) {
                            $job_details = $this->JobModel->getOneJob(array('job_id' => $invoicedJob['job_id']));
                            $total_cost += $invoicedJob['job_cost'];
                            $description .= $job_details->job_name . " ";
                        }
                        //update invoice
                        $updateArr = array(
                            'cost' => $total_cost,
                            'description' => $description,
                            'invoice_updated' => date("Y-m-d H:i:s"),
                        );
                        $updateInv = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), $updateArr);


                        //update sales tax
                        $get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
                        //die(print_r($get_invoice_tax));
                        if (!empty($get_invoice_tax)) {

                            foreach ($get_invoice_tax as $g_i_t) {
                                $invoice_tax_details = array(
                                    'invoice_id' => $invoice_id,
                                    'tax_name' => $g_i_t['tax_name'],
                                    'tax_value' => $g_i_t['tax_value'],
                                    'tax_value' => $g_i_t['tax_value'],
                                    'tax_amount' => $total_cost * $g_i_t['tax_value'] / 100
                                );
                                //delete old sales tax record
                                $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id, 'tax_name' => $g_i_t['tax_name']));
                                //create new sales tax record
                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                            }

                        }
                        //echo $total_cost;
                    } else {
                        //if no other jobs with invoice id then delete(archive) invoice
                        $archiveInv = $this->INV->deleteInvoice(array('invoice_id' => $invoice_id));
                    }
                }
            }

            $result = $this->DashboardModel->deleteAssignJob($where);

            if (!$result) {

                return false;
            } else {


                return true;
            }
        } else {

            return false;
        }
    }


    public function getOneAssignData($technician_job_assign_id)
    {

        $data = $this->DashboardModel->getOneAssignTechnician(array('technician_job_assign_id' => $technician_job_assign_id));

        $specific_time = '';

        if ($data->is_time_check == 1) {
            $specific_time = '<span><b>Specific Time : </b>' . $data->specific_time . '</span><br><br>';
        }


        $html = '<div class="row">
         <div class="col-md-6">
         
          <span><b>TECHNICIAN NAME : </b></span>' . $data->user_first_name . ' ' . $data->user_last_name . '<br><br>
          <span><b>SERVICE NAME : </b>' . $data->job_name . '</span><br><br>
          <span><b>ASSIGN DATE : </b>' . date('m-d-Y', strtotime($data->job_assign_date)) . '</span><br><br>
          <span><b>CUSTOMER NAME : </b>' . $data->first_name . ' ' . $data->last_name . '</span><br><br>
          <span><b>PROPERTY NAME : </b>' . $data->property_title . '</span><br><br>
                  
         </div>
         
         <div class="col-md-6">
         
          <span><b>ADDRESS : </b>' . $data->property_address . '</span><br><br>
          <span><b>SERVICE AREA : </b>' . $data->category_area_name . '</span><br><br>
          <span><b>PROGRAM : </b>' . $data->program_name . '</span><br><br>         
          <span><b>Route : </b>' . $data->route_name . '</span><br><br>' . $specific_time . '         
             
         </div>         
        </div>';

        $btn = '<li style="display: inline; padding-right:10px;">
                             <a  data-toggle="modal" data-target="#modal_edit_assign_job" onclick="editAssignJob(' . $data->technician_job_assign_id . ')" ><i class="icon-pencil   position-center" style="color: #9a9797;"></i></a>
                            </li>
                             <li style="display: inline; padding-right: 10px;">
                                             <a href="' . base_url("admin/invoices/pendingJobInvoice/") . $data->technician_job_assign_id . '" title="invoice" target="_blank" ><i class="icon-printer2  position-center" style="color: #9a9797;"></i></a>
                                          </li>

                            <li style="display: inline; padding-right: 10px;">
                            <a href="' . base_url("admin/ScheduledJobDetete/") . $data->technician_job_assign_id . '" class="confirm_delete button-next"><i class="icon-trash   position-center" style="color: #9a9797;"></i></a>
                            </li>';

        echo json_encode(array('html' => $html, 'btn' => $btn));
    }


    public function getTexhnicianRoute($value = '')
    {

        $data = $this->input->post();

        $where_arr = array(
            'technician_id' => $data['technician_id'],
            'job_assign_date' => $data['job_assign_date'],
        );


        $result = $this->Tech->getNumberOfRoute($where_arr);
        if ($result) {
            
            foreach($result as $key => &$res)
            {
                $locations = [];
                $properties = $this->Tech->getRoutsLocationsByRoute(array('technician_job_assign.route_id'=>$res['route_id']));

                if ($properties) {
                    foreach($properties as $property)
                    {
                        $locations[] = [
                            'property_address' => $property['property_address'],
                            'property_latitude' => $property['property_latitude'],
                            'property_longitude' => $property['property_longitude'],
                        ];
                    }
                }
                $result[$key]['locations'] = $locations;
            }
            $return_array  = $result;

        } else {
            $return_array = array();
        }

        echo json_encode($return_array);
    }

    public function getMileageAndDriveTimeForRoute()
    {
        $data = $this->input->post();

        $locations = [];
        if (isset($data['locations'])) {
            $locations = json_decode($data['locations'], true);
        }

        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id'=> $this->session->userdata['company_id']));

        $data['currentaddress'] = $data['setting_details']->start_location;
        $data['currentlat'] =  $data['setting_details']->start_location_lat;
        $data['currentlong'] = $data['setting_details']->start_location_long;
        $alldata = array();
        $Locations = array();
        $statLocation = array(
            'Name' => $data['setting_details']->start_location,
            'Latitude' => $data['setting_details']->start_location_lat,
            'Longitude' => $data['setting_details']->start_location_long
        );
        $endLocation = array(
            'Name' => $data['setting_details']->end_location,
            'Latitude' => $data['setting_details']->end_location_lat,
            'Longitude' => $data['setting_details']->end_location_long
        );
        if (!empty($locations)) {
            foreach ($locations as $key => $value) {
                $Locations[$key]['Name'] = $value['property_address'];
                $Locations[$key]['Latitude'] = $value['property_latitude'];
                $Locations[$key]['Longitude'] = $value['property_longitude'];
            }
            array_unshift($Locations, $statLocation);
            array_push($Locations, $endLocation);
        }
        $OptimizeParameters = array(
            "AppId" => RootAppId,
            "OptimizeType" => "distance",
            "RouteType" => "realroadcar",
            "Avoid" => "none",
            "Departure" => "2020-05-23T17:00:00"
        );
        $alldata['Locations'] = $Locations;
        $alldata['OptimizeParameters'] = $OptimizeParameters;

        echo json_encode($alldata);

    }


    public function manageRoute($data)
    {

        $route_array = array(
            'technician_id' => $data['technician_id'],
            'job_assign_date' => $data['job_assign_date'],
            'route_name' => $data['route_input'],
        );


        if ($data['changerouteview'] == 1) {
            $route_id = $data['route_select'];
        } else {
            $route_id = $this->Tech->createRoute($route_array);
        }

        return $route_id;
    }


    public function checkAlreadySpecificTime($group_id, $data)
    {
        if ($group_id == 1 && array_key_exists('specific_time_check', $data)) {
            $check_specific_time_array = array(
                'is_time_check' => 1,
                'technician_id' => $data['technician_id'],
                'specific_time' => $data['specific_time'],
                'job_assign_date' => $data['job_assign_date'],
                'is_job_mode' => 0,

            );
            if (array_key_exists('technician_job_assign_id', $data)) {
                $check_specific_time_array['technician_job_assign_id !='] = $data['technician_job_assign_id'];
            }
            $existchecking = $this->Tech->GetOneRow($check_specific_time_array);
            if ($existchecking) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }


    public function tecnicianJobAssign()
    {

        ini_set('memory_limit', '-1');

        $data = $this->input->post();

        if (!empty($data['group_id_new'])) {
            $route_id = $this->manageRoute($data);
            $group_id = explode(",", $data['group_id_new']);

            //check if tech already scheduled
            $checkAlreadyTime = $this->checkAlreadySpecificTime(count($group_id), $data);

            if ($checkAlreadyTime) {

                $tech_assigned_jobs = array();

                foreach ($group_id as $value) {
                    $datagroup = explode(':', $value);
                    //print_r($datagroup);
                    //tech assign job params
                    $param = array(
                        'technician_id' => $data['technician_id'],
                        'user_id' => $this->session->userdata['user_id'],
                        'company_id' => $this->session->userdata['company_id'],
                        'customer_id' => $datagroup[0],
                        'job_id' => $datagroup[1],
                        'program_id' => $datagroup[2],
                        'property_id' => $datagroup[3],
                        'job_assign_date' => $data['job_assign_date'],
                        'job_assign_notes' => $data['job_assign_notes'],
                        'route_id' => $route_id,
                    );

                    //check for rescheduled/skipped assign job
                    $wherearr = array(
                        'customer_id' => $param['customer_id'],
                        'job_id' => $param['job_id'],
                        'program_id' => $param['program_id'],
                        'property_id' => $param['property_id'],
                        'is_job_mode' => 2
                    );

                    $check = $this->Tech->GetOneRow($wherearr);
                    //delete rescheduled assigned jobs
                    if ($check) {
                        $this->Tech->deleteJobAssign($wherearr);
                    }

                    if (count($group_id) == 1 && array_key_exists('specific_time_check', $data)) {
                        $param['is_time_check'] = 1;
                        $param['specific_time'] = $data['specific_time'];
                    }

                    // insert new technician_assign_job
                    $result = $this->DashboardModel->CreateOneTecnicianJob($param);

                    //pass new tech assign job id into array
                    $tech_assigned_jobs[] = $result;

                    if ($result) {
                        #email/text section
                        $tech_job_assign = $this->DashboardModel->getOneAssignTechnician(array('technician_job_assign_id' => $result));

                        $emaildata['job_details'] = $this->JobModel->getOneJob(array('job_id' => $tech_job_assign->job_id));

                        $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id' => $param['customer_id']));
                        $customer = $emaildata['customerData'];
                        $pre_service_notification_email = 0;
                        $pre_service_notification_text = 0;
                        if (strpos($emaildata['customerData']->pre_service_notification, '"2"') != 0) {
                            //die("Yes, it has a preservice notification to send an email");
                            $pre_service_notification_email = 1;
                        }
                        if (strpos($emaildata['customerData']->pre_service_notification, '"3"') != 0) {
                            //die("Yes, it has a preservice notification to send an email");
                            $pre_service_notification_text = 1;
                        }
                        //die(print_r($customer));
                        #check customer billing type
                        $checkGroupBilling = $this->CustomerModel->checkGroupBilling($tech_job_assign->customer_id);
                        $emaildata['propertyData'] = $this->PropertyModel->getOneProperty(array('property_id' => $tech_job_assign->property_id));

                        #if customer billing type = group billing, then we notify the property level contact info
                        if (isset($checkGroupBilling) && $checkGroupBilling == "true") {
                            $emaildata['contactData'] = $this->PropertyModel->getGroupBillingByProperty($tech_job_assign->property_id);
                            $emaildata['program_name'] = $tech_job_assign->program_name;
                            $emaildata['job_name'] = $tech_job_assign->job_name;

                            $where = array('company_id' => $this->session->userdata['company_id']);
                            $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);
                            $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

                            $emaildata['assign_date'] = $tech_job_assign->job_assign_date;

                            $body = $this->load->view('email/group_billing/tech_email', $emaildata, true);
                            $where['is_smtp'] = 1;
                            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                            if (!$company_email_details) {
                                $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                            }

                            if ($emaildata['company_email_details']->job_sheduled_status == 1 && $emaildata['contactData']['email_opt_in'] == 1) {
                                $res = Send_Mail_dynamic($company_email_details, $emaildata['contactData']['email'], array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Service is scheduled');
                            }

                            if ($emaildata['company_email_details']->job_sheduled_status_text == 1 && $emaildata['contactData']['phone_opt_in'] == 1) {
                                $newDate = date("m-d-Y", strtotime($emaildata['assign_date']));
                                $string = str_replace("{mm/dd/yyyy}", $newDate, $emaildata['company_email_details']->job_sheduled_text);
                                $text_res = Send_Text_dynamic($emaildata['contactData']['phone'], $string, 'Service is Scheduled');
                            }
                        } else {
                            $emaildata['email_data_details'] = $this->Tech->getjobTechEmailData(array('customer_id' => $param['customer_id'], 'is_email' => 1, 'job_id' => $param['job_id'], 'program_id' => $param['program_id'], 'property_id' => $param['property_id']));


                            if ($emaildata['email_data_details']) {

                                $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id' => $param['customer_id']));

                                $where = array('company_id' => $this->session->userdata['company_id']);
                                $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);
                                $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);
                                $emaildata['assign_date'] = $param['job_assign_date'];

                                if (isset($this->session->userdata['is_text_message']) && $this->session->userdata['is_text_message'] && $emaildata['company_email_details']->job_sheduled_status_text == 1 && $emaildata['customerData']->is_mobile_text == 1) {
                                    $newDate = date("m-d-Y", strtotime($emaildata['assign_date']));
                                    $string = str_replace("{mm/dd/yyyy}", $newDate, $emaildata['company_email_details']->job_sheduled_text);


                                    $text_res = Send_Text_dynamic($emaildata['customerData']->phone, $string, 'Service is Scheduled');
                                }


                                if ($emaildata['company_email_details']->job_sheduled_status == 1 && $pre_service_notification_email == 1) {


                                    $emaildata['assign_date'] = $param['job_assign_date'];

                                    $body = $this->load->view('email/tech_email', $emaildata, true);
                                    $where['is_smtp'] = 1;
                                    $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                                    if (!$company_email_details) {
                                        $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                                    }
                                    $res = Send_Mail_dynamic($company_email_details, $emaildata['customerData']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Service is scheduled', $emaildata['customerData']->secondary_email);
                                    //die(print_r($body));
                                }
                            }
                        }
                    }
                    // End Email Section

                } //end foreach
                // HANDLE INVOICES FOR ASSIGNED JOBS();
                foreach ($tech_assigned_jobs as $key => $tech_assigned_job_id) {

                    $assigned_data = $this->DashboardModel->getOneAssignTechnician(array('technician_job_assign_id' => $tech_assigned_job_id));
                    //set freq. used variables
                    $customer_id = $assigned_data->customer_id;
                    $property_id = $assigned_data->property_id;
                    $program_id = $assigned_data->program_id;
                    $job_id = $assigned_data->job_id;
                    $sched_date = $assigned_data->job_assign_date;
                    $invoice_id = 0;

                    //get program invoice method
                    $checkInvMethod = $this->ProgramModel->getOneProgramForCheck(array('program_id' => $program_id));

                    $programPrice = $checkInvMethod->program_price;

                    //If Program Price = One Time Program Invoicing
                    if ($programPrice == 1) {
                        //Check for existing invoice
                        $ppjobinv_details = $this->PropertyProgramJobInvoiceModel->getOnePropertyProgramJobInvoiceDetails(array('customer_id' => $customer_id, 'property_id' => $property_id, 'program_id' => $program_id, 'job_id' => $job_id));

                        if (!empty($ppjobinv_details->invoice_id)) {
                            //update tech job assign row
                            $update = $this->DashboardModel->updateAssignJob(array('technician_job_assign_id' => $tech_assigned_job_id), array('invoice_id' => $ppjobinv_details->invoice_id));
                        }
                    }

                    //If Program Price = At Job Completion
                    if ($programPrice == 2) {

                        //Get Job Cost
                        $estimate_price_override = GetOneEstimateJobPriceOverride(array('customer_id' => $customer_id, 'property_id' => $property_id, 'program_id' => $program_id, 'job_id' => $job_id));
                        if ($estimate_price_override && !empty($estimate_price_override->is_price_override_set)) {
                            $job_cost = $estimate_price_override->price_override;
                        } else {
                            $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $program_id));

                            if ($priceOverrideData->is_price_override_set == 1) {
                                $job_cost = $priceOverrideData->price_override;
                            } else {
                                //else no price overrides, then calculate job cost
                                $lawn_sqf = $assigned_data->yard_square_feet;
                                $job_price = $assigned_data->job_price;

                                //get property difficulty level
                                $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));

                                if (isset($assigned_data->difficulty_level) && $assigned_data->difficulty_level == 2) {
                                    $difficulty_multiplier = $setting_details->dlmult_2;
                                } elseif (isset($assigned_data->difficulty_level) && $assigned_data->difficulty_level == 3) {
                                    $difficulty_multiplier = $setting_details->dlmult_3;
                                } else {
                                    $difficulty_multiplier = $setting_details->dlmult_1;
                                }

                                //get base fee
                                if (isset($assigned_data->base_fee_override)) {
                                    $base_fee = $assigned_data->base_fee_override;
                                } else {
                                    $base_fee = $setting_details->base_service_fee;
                                }

                                $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                //get min. service fee
                                if (isset($assigned_data->min_fee_override)) {
                                    $min_fee = $assigned_data->min_fee_override;
                                } else {
                                    $min_fee = $setting_details->minimum_service_fee;
                                }

                                // Compare cost per sf with min service fee
                                if ($cost_per_sqf > $min_fee) {
                                    $job_cost = $cost_per_sqf;
                                } else {
                                    $job_cost = $min_fee;
                                }
                            }
                        }


                        // $property_assigned_jobs = '';
                        // $property_assigned_jobs = (object) array();

                        //Get All Tech Assigned Jobs for job_assign_date and property_id and program_id
                        $property_assigned_jobs = $this->DashboardModel->getAllTechAssignJobs(array('technician_job_assign.property_id' => $property_id, 'technician_job_assign.job_assign_date' => $assigned_data->job_assign_date, 'technician_job_assign.program_id' => $program_id));

                        // $this->DashboardModel->updateAssignJob(array('technician_job_assign_id'=>$tech_assigned_job_id), array('job_assign_notes'=>json_encode($property_assigned_jobs)));
                        // $property_assigned_jobs_sans_date = $this->DashboardModel->getAllTechAssignJobs(array('technician_job_assign.property_id'=>$property_id, 'technician_job_assign.program_id'=>$program_id));
                        //		$log = "\n\nproperty_assigned_jobs QRY: \n".$this->db->last_query(). "\n";
                        //		fwrite($errorLog,$log);

                        if (count($property_assigned_jobs) >= 1) {
                            //	$log = "property_assigned_job >= 1\n";
                            //	fwrite($errorLog,$log);

                            // $this->DashboardModel->updateAssignJob(array('technician_job_assign_id'=>$tech_assigned_job_id), array('job_assign_notes'=>json_encode($property_assigned_jobs)));

                            //if multiple property program jobs assigned that day
                            $keep_going = 1;
                            foreach ($property_assigned_jobs as $assigned) {

                                if ($keep_going == 1) {
                                    if (!empty($assigned['invoice_id'])) {
                                        //make sure invoice hasn't been paid
                                        $checkPaid = $this->INV->getOneRow($assigned['invoice_id']);
                                        if (isset($checkPaid->payment_status) && $checkPaid->payment_status != 2 && $checkPaid->payment_status != 1) {
                                            $invoice_id = $assigned['invoice_id'];
                                            $keep_going = 0;
                                        }
                                    }
                                }
                            }

                            // $this->DashboardModel->updateAssignJob(array('technician_job_assign_id'=>$tech_assigned_job_id), array('job_assign_notes'=>json_encode($property_assigned_jobs)));

                            if (!empty($invoice_id)) { // if this group of tech_job_assigns has at least one row with an invoice_id & not paid - assigned SAME DAY

                                //update technician_job_assign table
                                $updateTechAssign = $this->DashboardModel->updateAssignJob(array('technician_job_assign_id' => $assigned_data->technician_job_assign_id), array('invoice_id' => $invoice_id));

                                //$log = "\nupdate tech_job_assign QRY: \n".$this->db->last_query(). "\n";
                                //fwrite($errorLog,$log);

                                //get property_program_id
                                $propProg = $this->PropertyModel->getOnePropertyProgram(array('property_id' => $property_id, 'program_id' => $program_id));

                                //store property_program_job_invoice data
                                $newPPJOBINV = array(
                                    'customer_id' => $customer_id,
                                    'property_id' => $property_id,
                                    'program_id' => $program_id,
                                    'property_program_id' => $propProg->property_program_id,
                                    'job_id' => $job_id,
                                    'invoice_id' => $invoice_id,
                                    'job_cost' => $job_cost,
                                    'created_at' => date("Y-m-d H:i:s"),
                                    'updated_at' => date("Y-m-d H:i:s"),
                                );
                                $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);


                                // calculate correct sum and apply coupons
                                $where_estimate = array(
                                    'customer_id' => $customer_id,
                                    'property_id' => $property_id,
                                    'program_id' => $program_id,
                                    'job_id' => $job_id
                                );
                                // $estimate_obj = $this->EstimateModel->getJustOneEstimate($where_estimate);
                                // $estimate_job_details = $this->EstimateModel->getOneEstimateJobDetails($where_estimate);
                                $estimate_job_details = $this->EstimateModel->getAllEstimatePriceOveride($where_estimate);

                                $estimate_Invoice = 1;
                                if (isset($estimate_job_details) && !empty($estimate_job_details) && isset($estimate_job_details[0])) {

                                    // $estimate_id = $estimate_obj->estimate_id;

                                    // just get one estiamte id (these should all be the same) , but in case not, get the most recent (ordered by estimate_id desc)
                                    $estimate_id = $estimate_job_details[0]->estimate_id;

                                    if (isset($estimate_id) && !empty($estimate_id)) {

                                        // get all coupon_estimates where estimateid=
                                        $coupon_estimates = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));

                                        $where = array(
                                            "t_estimate.company_id" => $this->session->userdata['company_id'],
                                            'estimate_id' => $estimate_id
                                        );
                                        $estimate_details_all = $this->EstimateModel->getOneEstimate($where);

                                        $line_total = $job_cost;

                                        // calculate total estimate cost
                                        $total_estimate_cost = 0;
                                        $company_id = $this->session->userdata['company_id'];
                                        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
                                        $property_details = $this->PropertyModel->getOneProperty(array('property_id' => $property_id));
                                        $estimate_price_overide_data = $this->EstimateModel->getAllEstimatePriceOveridewJob(array('estimate_id' => $estimate_id));
                                        $num_services_in_estimate = 0;

                                        foreach ($estimate_price_overide_data as $es_job) {
                                            $num_services_in_estimate += 1;

                                            if (isset($es_job->is_price_override_set) && !empty($es_job->is_price_override_set)) {
                                                $job_cost = $es_job->price_override;
                                            } else {


                                                $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $program_id));

                                                if ($priceOverrideData->is_price_override_set == 1) {
                                                    $job_cost = $priceOverrideData->price_override;
                                                } else {

                                                    //else no price overrides, then calculate job cost
                                                    $lawn_sqf = $property_details->yard_square_feet;
                                                    $job_price = $es_job->job_price;

                                                    //get property difficulty level
                                                    $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

                                                    if (isset($property_details->difficulty_level) && $property_details->difficulty_level == 2) {
                                                        $difficulty_multiplier = $setting_details->dlmult_2;
                                                    } elseif (isset($property_details->difficulty_level) && $property_details->difficulty_level == 3) {
                                                        $difficulty_multiplier = $setting_details->dlmult_3;
                                                    } else {
                                                        $difficulty_multiplier = $setting_details->dlmult_1;
                                                    }

                                                    //get base fee
                                                    if (isset($es_job->base_fee_override)) {
                                                        $base_fee = $es_job->base_fee_override;
                                                    } else {
                                                        $base_fee = $setting_details->base_service_fee;
                                                    }

                                                    $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                                    //get min. service fee
                                                    if (isset($es_job->min_fee_override)) {
                                                        $min_fee = $es_job->min_fee_override;
                                                    } else {
                                                        $min_fee = $setting_details->minimum_service_fee;
                                                    }

                                                    // Compare cost per sf with min service fee
                                                    if ($cost_per_sqf > $min_fee) {
                                                        $job_cost = $cost_per_sqf;
                                                    } else {
                                                        $job_cost = $min_fee;
                                                    }
                                                }

                                                // $job_cost = $es_job->job_price * $property_details->yard_square_feet/1000;
                                            }
                                            $total_estimate_cost += $job_cost;
                                        }

                                        // calculate total coupon costs for estimate
                                        $total_cost = $total_estimate_cost;
                                        $total_coupon_discount_for_estimate = 0;
                                        $coup_arr = array();
                                        if (isset($coupon_estimates) && !empty($coupon_estimates)) {
                                            foreach ($coupon_estimates as $coupon) {
                                                if ($coupon->coupon_amount_calculation == 0) { // flat
                                                    $coupon_amm = $coupon->coupon_amount;
                                                } else { // perc
                                                    $coupon_amm = ($coupon->coupon_amount / 100) * $total_cost;
                                                }
                                                $total_cost -= $coupon_amm;
                                                $total_coupon_discount_for_estimate += $coupon_amm;
                                            }
                                        }

                                        // TOTAL JOB ASSIGN # (how many services belong to related estimate)
                                        $num_invoices_generated = count($estimate_job_details);

                                        // if some invoices are not all generated here - we might want to use a differnet divisor ? like just use how many are geenerated here?

                                        // GETS UNIQUE TECHJOBASSIGN #
                                        $where11 = array(
                                            'company_id' => $this->session->userdata['company_id'],
                                            'customer_id' => $customer_id,
                                            'property_id' => $property_id,
                                            'program_id' => $program_id,
                                        );
                                        $tech_job_assigned_total = $this->Tech->GetAllRow($where11);
                                        $unique_invoice_id = array();
                                        $num_duplicates = 0;
                                        foreach ($tech_job_assigned_total as $tech_job_assign_details) {
                                            if (!in_array($tech_job_assign_details['invoice_id'], $unique_invoice_id)) {
                                                array_push($unique_invoice_id, $tech_job_assign_details['invoice_id']);
                                            } else {
                                                $num_duplicates += 1;
                                            }
                                        }

                                        // get total services in the estimate
                                        // $unique_counter = count($tech_job_assigned_total);
                                        $unique_counter = $num_services_in_estimate;
                                        // $unique_null_counter = $unique_counter - $num_duplicates;


                                        ////////////////////////////////////
                                        // START INVOICE CALCULATION COST //

                                        // invoice cost
                                        // $invoice_total_cost = $invoice->cost;

                                        // cost of all services (with price overrides) - service coupons
                                        $job_cost_total = 0;
                                        $where = array(
                                            'property_program_job_invoice.invoice_id' => $invoice_id
                                        );
                                        $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);
                                        if (!empty($proprojobinv)) {
                                            foreach ($proprojobinv as $job) {

                                                $job_cost = $job['job_cost'];

                                                $job_where = array(
                                                    'job_id' => $job['job_id'],
                                                    'customer_id' => $job['customer_id'],
                                                    'property_id' => $job['property_id'],
                                                    'program_id' => $job['program_id']
                                                );
                                                $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                                                if (!empty($coupon_job_details)) {

                                                    foreach ($coupon_job_details as $coupon) {
                                                        // $nestedData['email'] = json_encode($coupon->coupon_amount);
                                                        $coupon_job_amm_total = 0;
                                                        $coupon_job_amm = $coupon->coupon_amount;
                                                        $coupon_job_calc = $coupon->coupon_amount_calculation;

                                                        if ($coupon_job_calc == 0) { // flat amm
                                                            $coupon_job_amm_total = (float)$coupon_job_amm;
                                                        } else { // percentage
                                                            $coupon_job_amm_total = ((float)$coupon_job_amm / 100) * $job_cost;
                                                        }

                                                        $job_cost = $job_cost - $coupon_job_amm_total;

                                                        if ($job_cost < 0) {
                                                            $job_cost = 0;
                                                        }
                                                    }
                                                }

                                                $job_cost_total += $job_cost;
                                            }
                                        } else {
                                            // $total_tax_amount = getAllSalesTaxSumByInvoice($invoice->invoice_id)->total_tax_amount;
                                            // $invoice_total_cost += $total_tax_amount;
                                            // $invoice_total_cost = $invoice->cost+$total_tax_amount;

                                            // IF none from that table, is old invoice, calculate old way
                                            $job_cost_total = $job_cost;
                                        }

                                        $invoice_total_cost = $job_cost_total;

                                        // - invoice coupons
                                        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));
                                        foreach ($coupon_invoice_details as $coupon_invoice) {
                                            if (!empty($coupon_invoice)) {
                                                $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                                                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                                                if ($coupon_invoice_amm_calc == 0) { // flat amm
                                                    $invoice_total_cost -= (float)$coupon_invoice_amm;
                                                } else { // percentage
                                                    $coupon_invoice_amm = ((float)$coupon_invoice_amm / 100) * $invoice_total_cost;
                                                    $invoice_total_cost -= $coupon_invoice_amm;
                                                }
                                                if ($invoice_total_cost < 0) {
                                                    $invoice_total_cost = 0;
                                                }
                                            }
                                        }

                                        // + tax cost
                                        $invoice_total_tax = 0;
                                        $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
                                        if (!empty($invoice_sales_tax_details)) {
                                            foreach ($invoice_sales_tax_details as $tax) {
                                                if (array_key_exists("tax_value", $tax)) {
                                                    $tax_amm_to_add = ((float)$tax['tax_value'] / 100) * $invoice_total_cost;
                                                    $invoice_total_tax += $tax_amm_to_add;
                                                }
                                            }
                                        }
                                        $invoice_total_cost += $invoice_total_tax;
                                        $total_tax_amount = $invoice_total_tax;

                                        // END TOTAL INVOICE CALCULATION COST //
                                        ////////////////////////////////////////

                                        (float)$coupon_discount_partition = (float)$total_coupon_discount_for_estimate / (float)$unique_counter;

                                        $prev_estimate_discount_leftover = $estimate_details_all->discount_leftover;
                                        if (!empty($prev_estimate_discount_leftover)) {
                                            $coupon_discount_partition += $prev_estimate_discount_leftover;
                                        }

                                        // if dscount would be greater than total, set as total & save leftover (coupon b4 sales tax)
                                        $invoice_total_cost -= $total_tax_amount;
                                        if ($coupon_discount_partition > $invoice_total_cost) {

                                            // save leftovers for next invoice
                                            $leftover_discount = $coupon_discount_partition - $invoice_total_cost;
                                            $this->EstimateModel->updateEstimate(array('estimate_id' => $estimate_id), array(
                                                'discount_leftover' => $leftover_discount
                                            ));

                                            // set max
                                            $coupon_discount_partition = $invoice_total_cost;
                                        } else {
                                            // reset leftovers
                                            $this->EstimateModel->updateEstimate(array('estimate_id' => $estimate_id), array(
                                                'discount_leftover' => 0
                                            ));
                                        }


                                        // duplicate them for coupon_invoices using invoice_id
                                        if (!empty($coupon_estimates)) {

                                            $coupon_invoice = $this->CouponModel->getOneCouponInvoice(array('invoice_id' => $invoice_id, 'coupon_code' => 'Estimate Coupon'));
                                            if (isset($coupon_invoice) && !empty($coupon_invoice)) {
                                                $coupon_new_cost = $coupon_invoice->coupon_amount + $coupon_discount_partition;
                                                $this->CouponModel->updateCouponInvoice(array('invoice_id' => $invoice_id, 'coupon_code' => 'Estimate Coupon'), array('coupon_amount' => $coupon_new_cost));
                                            } else {
                                                $coupon_params = array(
                                                    'coupon_id' => 0,
                                                    'invoice_id' => $invoice_id,
                                                    'coupon_code' => 'Estimate Coupon',
                                                    'coupon_amount' => $coupon_discount_partition,
                                                    'coupon_amount_calculation' => 0,
                                                    'coupon_type' => 0
                                                );
                                                $this->CouponModel->CreateOneCouponInvoice($coupon_params);
                                            }

                                            // $coupon_params = array(
                                            //     'coupon_id' => 0,
                                            //     'invoice_id' => $invoice_id,
                                            //     'coupon_code' => 'Estimate Coupon',
                                            //     'coupon_amount' => (string)number_format($coupon_discount_partition, 2),
                                            //     'coupon_amount_calculation' => 0,
                                            //     'coupon_type' => 0
                                            // );
                                            // $this->CouponModel->CreateOneCouponInvoice($coupon_params);
                                        }
                                    } else {
                                        $estimate_Invoice = 0;
                                    }
                                } else {
                                    $estimate_Invoice = 0;
                                }


                                //	$log = "\nCreate PPJOBINV QRY: \n".$this->db->last_query(). "\n";
                                //	fwrite($errorLog,$log);

                                $updateInv = $this->updateInvoiceByPPJOBINV($invoice_id);

                                //	$log = "\nUpdate Invoice QRY: \n".$this->db->last_query(). "\n";
                                //	fwrite($errorLog,$log);
                            } else {

                                //	$log = "\n\nNO INVOICE ID \n";
                                //		fwrite($errorLog,$log);
                                //create invoice
                                //if no other jobs assigned to property program for that assigned date then create invoice
                                $invParams = array(
                                    'customer_id' => $customer_id,
                                    'property_id' => $property_id,
                                    'program_id' => $program_id,
                                    'description' => $assigned_data->job_name,
                                    'user_id' => $this->session->userdata['user_id'],
                                    'company_id' => $this->session->userdata['company_id'],
                                    'invoice_date' => $sched_date,
                                    'cost' => ($job_cost),
                                    'is_created' => 0,
                                    'invoice_created' => date("Y-m-d H:i:s"),
                                    //'json'=>json_encode($json),
                                );
                                $invoice_id = $this->INV->createOneInvoice($invParams);
                                //$log = "\nCreate Invoice QRY: \n".$this->db->last_query(). "\n";
                                //fwrite($errorLog,$log);
                                if ($invoice_id) {
                                    //update technician_job_assign table
                                    $updateTechAssign = $this->DashboardModel->updateAssignJob(array('technician_job_assign_id' => $assigned_data->technician_job_assign_id), array('invoice_id' => $invoice_id));

                                    //	$log = "\nupdate tech_job_assign QRY: \n".$this->db->last_query(). "\n";
                                    //	fwrite($errorLog,$log);

                                    //get property_program_id
                                    $propProg = $this->PropertyModel->getOnePropertyProgram(array('property_id' => $property_id, 'program_id' => $program_id));

                                    //store property_program_job_invoice data
                                    $newPPJOBINV = array(
                                        'customer_id' => $customer_id,
                                        'property_id' => $property_id,
                                        'program_id' => $program_id,
                                        'property_program_id' => $propProg->property_program_id,
                                        'job_id' => $job_id,
                                        'invoice_id' => $invoice_id,
                                        'job_cost' => $job_cost,
                                        'created_at' => date("Y-m-d H:i:s"),
                                        'updated_at' => date("Y-m-d H:i:s"),
                                    );
                                    $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);

                                    //	$log = "\nCreate PPJOBINV QRY: \n".$this->db->last_query(). "\n";
                                    //	fwrite($errorLog,$log);

                                    //figure tax
                                    $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                                    if ($setting_details->is_sales_tax == 1) {
                                        $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $property_id));
                                        // die(print_r($property_assign_tax));
                                        if ($property_assign_tax) {
                                            foreach ($property_assign_tax as $key => $tax_details) {
                                                // die(print_r($tax_details));
                                                //check if sales tax already exists for inv
                                                // $get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));

                                                // if (!empty($get_invoice_tax)) {
                                                // //     // die(print_r($get_invoice_tax));
                                                // //     $invoice_tax_details =  array(
                                                // //         'invoice_id' => $invoice_id,
                                                // //         'tax_name' => $get_invoice_tax[0]['tax_name'],
                                                // //         'tax_value' => $get_invoice_tax[0]['tax_value'],
                                                // //         'tax_amount' => $job_cost * $get_invoice_tax[0]['tax_value'] / 100
                                                // //     );
                                                // //     //delete old sales tax record
                                                // //     $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id));
                                                // //     //create new sales tax record
                                                // //     $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                                // print_r('Index One: ' . $get_invoice_tax);
                                                // }
                                                $invoice_tax_details = array(
                                                    'invoice_id' => $invoice_id,
                                                    'tax_name' => $tax_details['tax_name'],
                                                    'tax_value' => $tax_details['tax_value'],
                                                    'tax_amount' => $job_cost * $tax_details['tax_value'] / 100
                                                );

                                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                                // }
                                            }
                                        }
                                    }


                                    //
                                    // NR NEW CHANGE
                                    //

                                    // calculate correct sum and apply coupons
                                    $where_estimate = array(
                                        'customer_id' => $customer_id,
                                        'property_id' => $property_id,
                                        'program_id' => $program_id,
                                        'job_id' => $job_id
                                    );
                                    // $estimate_obj = $this->EstimateModel->getJustOneEstimate($where_estimate);
                                    // $estimate_job_details = $this->EstimateModel->getOneEstimateJobDetails($where_estimate);
                                    $estimate_job_details = $this->EstimateModel->getAllEstimatePriceOveride($where_estimate);

                                    $estimate_Invoice = 1;
                                    if (isset($estimate_job_details) && !empty($estimate_job_details) && isset($estimate_job_details[0])) {

                                        // $estimate_id = $estimate_obj->estimate_id;

                                        // just get one estiamte id (these should all be the same) , but in case not, get the most recent (ordered by estimate_id desc)
                                        $estimate_id = $estimate_job_details[0]->estimate_id;

                                        if (isset($estimate_id) && !empty($estimate_id)) {

                                            // get all coupon_estimates where estimateid=
                                            $coupon_estimates = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $estimate_id));

                                            $where = array(
                                                "t_estimate.company_id" => $this->session->userdata['company_id'],
                                                'estimate_id' => $estimate_id
                                            );
                                            $estimate_details_all = $this->EstimateModel->getOneEstimate($where);

                                            $line_total = $job_cost;

                                            // calculate total estimate cost
                                            $total_estimate_cost = 0;
                                            $company_id = $this->session->userdata['company_id'];
                                            $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
                                            $estimate_price_overide_data = $this->EstimateModel->getAllEstimatePriceOveridewJob(array('estimate_id' => $estimate_id));
                                            $num_services_in_estimate = 0;

                                            foreach ($estimate_price_overide_data as $es_job) {
                                                $num_services_in_estimate += 1;

                                                if (isset($es_job->is_price_override_set) && !empty($es_job->is_price_override_set)) {
                                                    $job_cost = $es_job->price_override;
                                                } else {


                                                    $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $program_id));

                                                    if ($priceOverrideData->is_price_override_set == 1) {
                                                        $job_cost = $priceOverrideData->price_override;
                                                    } else {

                                                        //else no price overrides, then calculate job cost
                                                        $lawn_sqf = $property_details->yard_square_feet;
                                                        $job_price = $es_job->job_price;

                                                        //get property difficulty level
                                                        $setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

                                                        if (isset($property_details->difficulty_level) && $property_details->difficulty_level == 2) {
                                                            $difficulty_multiplier = $setting_details->dlmult_2;
                                                        } elseif (isset($property_details->difficulty_level) && $property_details->difficulty_level == 3) {
                                                            $difficulty_multiplier = $setting_details->dlmult_3;
                                                        } else {
                                                            $difficulty_multiplier = $setting_details->dlmult_1;
                                                        }

                                                        //get base fee
                                                        if (isset($es_job->base_fee_override)) {
                                                            $base_fee = $es_job->base_fee_override;
                                                        } else {
                                                            $base_fee = $setting_details->base_service_fee;
                                                        }

                                                        $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                                        //get min. service fee
                                                        if (isset($es_job->min_fee_override)) {
                                                            $min_fee = $es_job->min_fee_override;
                                                        } else {
                                                            $min_fee = $setting_details->minimum_service_fee;
                                                        }

                                                        // Compare cost per sf with min service fee
                                                        if ($cost_per_sqf > $min_fee) {
                                                            $job_cost = $cost_per_sqf;
                                                        } else {
                                                            $job_cost = $min_fee;
                                                        }
                                                    }

                                                    // $job_cost = $es_job->job_price * $property_details->yard_square_feet/1000;
                                                }
                                                $total_estimate_cost += $job_cost;
                                            }

                                            // calculate total coupon costs for estimate
                                            $total_cost = $total_estimate_cost;
                                            $total_coupon_discount_for_estimate = 0;
                                            $coup_arr = array();
                                            if (isset($coupon_estimates) && !empty($coupon_estimates)) {
                                                foreach ($coupon_estimates as $coupon) {
                                                    if ($coupon->coupon_amount_calculation == 0) { // flat
                                                        $coupon_amm = $coupon->coupon_amount;
                                                    } else { // perc
                                                        $coupon_amm = ($coupon->coupon_amount / 100) * $total_cost;
                                                    }
                                                    $total_cost -= $coupon_amm;
                                                    $total_coupon_discount_for_estimate += $coupon_amm;
                                                }
                                            }

                                            // TOTAL JOB ASSIGN # (how many services belong to related estimate)
                                            $num_invoices_generated = count($estimate_job_details);

                                            // if some invoices are not all generated here - we might want to use a differnet divisor ? like just use how many are geenerated here?

                                            // GETS UNIQUE TECHJOBASSIGN #
                                            $where11 = array(
                                                'company_id' => $this->session->userdata['company_id'],
                                                'customer_id' => $customer_id,
                                                'property_id' => $property_id,
                                                'program_id' => $program_id,
                                            );
                                            $tech_job_assigned_total = $this->Tech->GetAllRow($where11);
                                            $unique_invoice_id = array();
                                            $num_duplicates = 0;
                                            foreach ($tech_job_assigned_total as $tech_job_assign_details) {
                                                if (!in_array($tech_job_assign_details['invoice_id'], $unique_invoice_id)) {
                                                    array_push($unique_invoice_id, $tech_job_assign_details['invoice_id']);
                                                } else {
                                                    $num_duplicates += 1;
                                                }
                                            }

                                            // get total services in the estimate
                                            // $unique_counter = count($tech_job_assigned_total);
                                            $unique_counter = $num_services_in_estimate;
                                            // $unique_null_counter = $unique_counter - $num_duplicates;


                                            ////////////////////////////////////
                                            // START INVOICE CALCULATION COST //

                                            // invoice cost
                                            // $invoice_total_cost = $invoice->cost;

                                            // cost of all services (with price overrides) - service coupons
                                            $job_cost_total = 0;
                                            $where = array(
                                                'property_program_job_invoice.invoice_id' => $invoice_id
                                            );
                                            $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);
                                            if (!empty($proprojobinv)) {
                                                foreach ($proprojobinv as $job) {

                                                    $job_cost = $job['job_cost'];

                                                    $job_where = array(
                                                        'job_id' => $job['job_id'],
                                                        'customer_id' => $job['customer_id'],
                                                        'property_id' => $job['property_id'],
                                                        'program_id' => $job['program_id']
                                                    );
                                                    $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                                                    if (!empty($coupon_job_details)) {

                                                        foreach ($coupon_job_details as $coupon) {
                                                            // $nestedData['email'] = json_encode($coupon->coupon_amount);
                                                            $coupon_job_amm_total = 0;
                                                            $coupon_job_amm = $coupon->coupon_amount;
                                                            $coupon_job_calc = $coupon->coupon_amount_calculation;

                                                            if ($coupon_job_calc == 0) { // flat amm
                                                                $coupon_job_amm_total = (float)$coupon_job_amm;
                                                            } else { // percentage
                                                                $coupon_job_amm_total = ((float)$coupon_job_amm / 100) * $job_cost;
                                                            }

                                                            $job_cost = $job_cost - $coupon_job_amm_total;

                                                            if ($job_cost < 0) {
                                                                $job_cost = 0;
                                                            }
                                                        }
                                                    }

                                                    $job_cost_total += $job_cost;
                                                }
                                            } else {
                                                // $total_tax_amount = getAllSalesTaxSumByInvoice($invoice->invoice_id)->total_tax_amount;
                                                // $invoice_total_cost += $total_tax_amount;
                                                // $invoice_total_cost = $invoice->cost+$total_tax_amount;

                                                // IF none from that table, is old invoice, calculate old way
                                                $job_cost_total = $job_cost;
                                            }

                                            $invoice_total_cost = $job_cost_total;

                                            // - invoice coupons
                                            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));
                                            foreach ($coupon_invoice_details as $coupon_invoice) {
                                                if (!empty($coupon_invoice)) {
                                                    $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                                                    $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                                                    if ($coupon_invoice_amm_calc == 0) { // flat amm
                                                        $invoice_total_cost -= (float)$coupon_invoice_amm;
                                                    } else { // percentage
                                                        $coupon_invoice_amm = ((float)$coupon_invoice_amm / 100) * $invoice_total_cost;
                                                        $invoice_total_cost -= $coupon_invoice_amm;
                                                    }
                                                    if ($invoice_total_cost < 0) {
                                                        $invoice_total_cost = 0;
                                                    }
                                                }
                                            }

                                            // + tax cost
                                            $invoice_total_tax = 0;
                                            $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
                                            if (!empty($invoice_sales_tax_details)) {
                                                foreach ($invoice_sales_tax_details as $tax) {
                                                    if (array_key_exists("tax_value", $tax)) {
                                                        $tax_amm_to_add = ((float)$tax['tax_value'] / 100) * $invoice_total_cost;
                                                        $invoice_total_tax += $tax_amm_to_add;
                                                    }
                                                }
                                            }
                                            $invoice_total_cost += $invoice_total_tax;
                                            $total_tax_amount = $invoice_total_tax;

                                            // END TOTAL INVOICE CALCULATION COST //
                                            ////////////////////////////////////////

                                            (float)$coupon_discount_partition = (float)$total_coupon_discount_for_estimate / (float)$unique_counter;

                                            $prev_estimate_discount_leftover = $estimate_details_all->discount_leftover;
                                            if (!empty($prev_estimate_discount_leftover)) {
                                                $coupon_discount_partition += $prev_estimate_discount_leftover;
                                            }

                                            // if dscount would be greater than total, set as total & save leftover (coupon b4 sales tax)
                                            $invoice_total_cost -= $total_tax_amount;
                                            if ($coupon_discount_partition > $invoice_total_cost) {

                                                // save leftovers for next invoice
                                                $leftover_discount = $coupon_discount_partition - $invoice_total_cost;
                                                $this->EstimateModel->updateEstimate(array('estimate_id' => $estimate_id), array(
                                                    'discount_leftover' => $leftover_discount
                                                ));

                                                // set max
                                                $coupon_discount_partition = $invoice_total_cost;
                                            } else {
                                                // reset leftovers
                                                $this->EstimateModel->updateEstimate(array('estimate_id' => $estimate_id), array(
                                                    'discount_leftover' => 0
                                                ));
                                            }


                                            // duplicate them for coupon_invoices using invoice_id
                                            if (!empty($coupon_estimates)) {

                                                $coupon_invoice = $this->CouponModel->getOneCouponInvoice(array('invoice_id' => $invoice_id, 'coupon_code' => 'Estimate Coupon'));
                                                if (isset($coupon_invoice) && !empty($coupon_invoice)) {
                                                    $coupon_new_cost = $coupon_invoice->coupon_amount + $coupon_discount_partition;
                                                    $this->CouponModel->updateCouponInvoice(array('invoice_id' => $invoice_id, 'coupon_code' => 'Estimate Coupon'), array('coupon_amount' => $coupon_new_cost));
                                                } else {
                                                    $coupon_params = array(
                                                        'coupon_id' => 0,
                                                        'invoice_id' => $invoice_id,
                                                        'coupon_code' => 'Estimate Coupon',
                                                        'coupon_amount' => $coupon_discount_partition,
                                                        'coupon_amount_calculation' => 0,
                                                        'coupon_type' => 0
                                                    );
                                                    $this->CouponModel->CreateOneCouponInvoice($coupon_params);
                                                }
                                            }
                                        } else {
                                            $estimate_Invoice = 0;
                                        }
                                    } else {
                                        $estimate_Invoice = 0;
                                    }

                                    if ($estimate_Invoice == 0) {
                                        // check global coupons & assign if so -- only if not generated from an estimate
                                        $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $customer_id));
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
                                                    $params = array(
                                                        'coupon_id' => $coupon_id,
                                                        'invoice_id' => $invoice_id,
                                                        'coupon_code' => $coupon_details->code,
                                                        'coupon_amount' => $coupon_details->amount,
                                                        'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                                        'coupon_type' => $coupon_details->type
                                                    );
                                                    $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                                                }
                                            }
                                        }
                                    }
                                    //
                                    // END NR NEW CHANGE
                                    //


                                    // // check global coupons & assign if so
                                    // $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $customer_id));
                                    // if (!empty($coupon_customers)) {
                                    //     foreach($coupon_customers as $coupon_customer) {

                                    //         $coupon_id = $coupon_customer->coupon_id;
                                    //         $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

                                    //         // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                                    //         $expiration_pass = true;
                                    //         if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
                                    //             $coupon_expiration_date = strtotime( $coupon_details->expiration_date );

                                    //             $now = time();
                                    //             if($coupon_expiration_date < $now) {
                                    //                 $expiration_pass = false;
                                    //             }
                                    //         }

                                    //         if ($expiration_pass == true) {
                                    //             $params = array(
                                    //                 'coupon_id' => $coupon_id,
                                    //                 'invoice_id' => $invoice_id,
                                    //                 'coupon_code' => $coupon_details->code,
                                    //                 'coupon_amount' => $coupon_details->amount,
                                    //                 'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                    //                 'coupon_type' => $coupon_details->type
                                    //             );
                                    //             $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                                    //         }
                                    //     }
                                    // }


                                } //end if invoice
                            }
                        }
                    } //end if invoice at completion

                    //print_r($assigned_data);
                }

                echo json_encode(array('status' => 200, 'msg' => 'Assigned Successfully ', 'route_id' => $data['route_select'], 'technician_assigned' => $tech_assigned_jobs));
            } else {
                //$log = "Already exists specific time in this date please change time\n";
                //fwrite($errorLog,$log);
                echo json_encode(array('status' => 400, 'msg' => 'Already exists specific time in this date please change time'));
            }
        } else {
            //	$log = "Something went wrong!\n";
            //		fwrite($errorLog,$log);
            echo json_encode(array('status' => 400, 'msg' => 'Something went wrong!'));
            //  echo   json_encode(array('status'=>200,'msg'=>'Assigned Successfully '));


        }

    }

    public function updateInvoiceByPPJOBINV($invoice_id)
    {
        //get all jobs with same invoice id
        $PPJOBINV = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id' => $invoice_id));

        //if jobs then update invoice
        if ($PPJOBINV) {
            //calculate new invoice total
            $total_cost = 0;
            $description = "";
            foreach ($PPJOBINV as $invoicedJob) {
                $job_details = $this->JobModel->getOneJob(array('job_id' => $invoicedJob['job_id']));
                $total_cost += $invoicedJob['job_cost'];
                $description .= $job_details->job_name . " ";
            }
            //update invoice
            $updateArr = array(
                'cost' => $total_cost,
                'description' => $description,
                'invoice_updated' => date("Y-m-d H:i:s"),
            );
            $updateInv = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), $updateArr);

            //update sales tax
            $get_invoice_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
            //die(print_r($get_invoice_tax));
            if (!empty($get_invoice_tax)) {

                foreach ($get_invoice_tax as $g_i_t) {
                    $invoice_tax_details = array(
                        'invoice_id' => $invoice_id,
                        'tax_name' => $g_i_t['tax_name'],
                        'tax_value' => $g_i_t['tax_value'],
                        'tax_value' => $g_i_t['tax_value'],
                        'tax_amount' => $total_cost * $g_i_t['tax_value'] / 100
                    );
                    //delete old sales tax record
                    $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id, 'tax_name' => $g_i_t['tax_name']));
                    //create new sales tax record
                    $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                }

            }
        }
    }

    public function technicianMapView($technician_id, $job_assign_date, $route_id = '')
    {
        $user_id = $technician_id;
        $where = array('user_id' => $user_id);

        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));

        $job_assign_details_check = $this->Tech->getAllJobAssignCheck($technician_id, $job_assign_date);

        if ($job_assign_details_check) {

            $data['currentaddress'] = $job_assign_details_check->property_address;
            $data['currentlat'] = $job_assign_details_check->property_latitude;
            $data['currentlong'] = $job_assign_details_check->property_longitude;
        } else {
            if ($this->session->userdata['spraye_technician_login']->start_location != "") {
                $data['currentaddress'] = $this->session->userdata['spraye_technician_login']->start_location;
                $data['currentlat'] = $this->session->userdata['spraye_technician_login']->start_location_lat;
                $data['currentlong'] = $this->session->userdata['spraye_technician_login']->start_location_long;
            } else {
                $data['currentaddress'] = $data['setting_details']->start_location;
                $data['currentlat'] = $data['setting_details']->start_location_lat;
                $data['currentlong'] = $data['setting_details']->start_location_long;
            }
        }


        $where_arr = array(
            'technician_job_assign.technician_id' => $this->session->userdata['spraye_technician_login']->user_id,
            'technician_job_assign.job_assign_date' => $job_assign_date,
            'is_job_mode' => 0,
        );

        $data['routeDetails'] = $this->Tech->getRoutsByJobAssign($where_arr);


        if ($route_id == '') {

            if ($data['routeDetails']) {
                $where_arr['route_id'] = $data['routeDetails'][0]['route_id'];
                $data['current_route'] = $where_arr['route_id']; // blank
            } else {
                $data['current_route'] = $route_id;
            }
        } else {
            $where_arr['route_id'] = $route_id;
            $data['current_route'] = $route_id;
        }


        $data['job_assign_details'] = $this->Tech->getAllJobAssign($where_arr);


        $page["active_sidebar"] = "Dashboard";
        $page["page_name"] = "Day at a Glance";
        $page["page_content"] = $this->load->view("admin/tecnician_map_view", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }


    public function logout()
    {
        $this->session->sess_destroy();
        return redirect('admin/auth');
    }

    /*//////////////////  Customer Code Section Start Here   /////////////////////////*/
    public function customerList()
    {
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['customer'] = $this->CustomerModel->get_all_customer_ID_customerList($where);
        $data['total_customer'] = $this->CustomerModel->getNumberOfCustomers($where);
        $data['active_customer'] = $this->CustomerModel->getNumberOfCustomers(array('company_id' => $this->session->userdata['company_id'], 'customer_status' => 1));
        $data['non_active_customer'] = $this->CustomerModel->getNumberOfCustomers(array('company_id' => $this->session->userdata['company_id'], 'customer_status' => 0));
        $data['hold_customer'] = $this->CustomerModel->getNumberOfCustomers(array('company_id' => $this->session->userdata['company_id'], 'customer_status' => 2));

        $where = array(
            'company_id' => $this->session->userdata['company_id'],
            'table_name' => 'customer'
        );

        $data['table_details'] = $this->DataTableModel->getOneOneDataTable($where);

        $page["active_sidebar"] = "customer";
        $page["page_name"] = "Customers";

        $page["page_content"] = $this->load->view("admin/customer_view", $data, TRUE);
        //auto status update
        $this->CustomerModel->autoStatusCheck(0, $this->session->userdata['company_id']);
        $this->PropertyModel->autoStatusCheck();
        $this->layout->superAdminTemplateTable($page);
    }


    public function deletemultipleCustomers($value = '')
    {
        $customers = $this->input->post('customers');
        if (!empty($customers)) {
            foreach ($customers as $key => $value) {
                $where = array('customer_id' => $value);
                $result = $this->CustomerModel->deleteCustomer($where);
            }
            echo 1;
        } else {
            echo 0;
        }
    }


    public function addCustomer($opt = 0)
    {
        $where_comapny = array('company_id' => $this->session->userdata['company_id']);
        // $data['company_details'] = $this->CompanyModel->getOneCompany($where_comapny);

        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList($where);
        $data['propertylist'] = $this->CustomerModel->getPropertyList($where);
        // $data['program_details'] = $this->ProgramModel->get_all_program($where);
        $data['programlist'] = $this->PropertyModel->getProgramList($where);
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' => $this->session->userdata['company_id']));
        $data['send_daily_invoice_mail'] = isset($company_email_details['send_daily_invoice_mail']) ? $company_email_details['send_daily_invoice_mail'] : 0;
        $data['propertyconditionslist'] = $this->PropertyModel->getCompanyPropertyConditions(array('company_id' => $this->session->userdata['company_id']));

        $page["active_sidebar"] = "customer";
        $page["page_name"] = "Add Customer";
        $data["opt"] = $opt;
        $page["page_content"] = $this->load->view("admin/add_customer", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function addCustomerData()
    {
        $data = $this->input->post();
        $user_id = $this->session->userdata['user_id'];
        $company_id = $this->session->userdata['company_id'];

        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('customer_company_name', 'Customer Company Name', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');
        $this->form_validation->set_rules('billing_street', 'Billing Street', 'required');
        $this->form_validation->set_rules('billing_street_2', 'Billing Street 2', 'trim');
        $this->form_validation->set_rules('billing_city', 'City', 'required');
        $this->form_validation->set_rules('billing_state', 'State', 'required');
        $this->form_validation->set_rules('billing_zipcode', 'ZipCode', 'required');
        $this->form_validation->set_rules('assign_property[]', 'Assign Property', 'trim');
        //$this->form_validation->set_rules('customer_status', 'Customer Status', 'required');
        $this->form_validation->set_rules('billing_type', 'Billing Type', 'required');


        // TODO check for duplicates
        $where_arr = array(
            'company_id' => $company_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name']
        );

        $customer_lookup = $this->CustomerModel->getOneCustomer($where_arr);
//        if (!empty($customer_lookup))  {
//            echo 'empty';
//            $this->addCustomer();
//        }
        //die(print_r($customer_lookup));

        //$resultado = array('status'=>201,'msg'=>'Customer already exists ','result'=>$customer_lookup);
        if (($this->form_validation->run() == FALSE || !empty($customer_lookup)) && $data['confirmation'] == 0) {

            if (!empty($customer_lookup)) {
                $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000">Customer<strong> already exists </strong>  Are you sure you want to add it?
                <button id="confirmation-button" class="btn btn-success">Next <i class="icon-arrow-right14 position-right"></i></button></div>');
            }

            $this->addCustomer(1);
        } else {


            $tags = "";
            if (isset($data['tags'])) {
                $tags = implode(',', $data['tags']);
            }
            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'customer_company_name' => $data['customer_company_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'home_phone' => $data['home_phone'],
                'work_phone' => $data['work_phone'],
                'billing_street' => $data['billing_street'],
                'billing_street_2' => $data['billing_street_2'],
                'billing_city' => $data['billing_city'],
                'billing_state' => $data['billing_state'],
                'billing_zipcode' => $data['billing_zipcode'],
                'customer_status' => $data['customer_status'],
                'billing_type' => $data['billing_type'],
                'pre_service_notification' => json_encode(!empty($data['pre_service_notification']) ? $data['pre_service_notification'] : []),
                //'tags' => $tags,
            );


            $quickbook_customer = $this->createCustomerInQuickbook($param);

            if ($quickbook_customer['status'] == 201) {

                $param['quickbook_customer_id'] = $quickbook_customer['result'];
            }

            if (isset($data['is_email'])) {
                $param['is_email'] = 1;
            } else {
                $param['is_email'] = 0;
            }

            if (isset($data['is_mobile_text'])) {
                $param['is_mobile_text'] = 1;
            } else {
                $param['is_mobile_text'] = 0;
            }

            $param['secondary_email'] = $data['secondary_email_list_hid'];
            $result1 = $this->CustomerModel->insert_customer($param);

            

            //webhook_trigger
            $user_info = $this->Administrator->getOneAdmin(array("user_id" => $this->session->userdata('user_id')));
            if($user_info->webhook_customer_created){
                $this->load->model('api/Webhook');
                //die(print_r($this->CustomerModel->getCustomerDetail($result1)));
                $customerResult = $this->CustomerModel->getCustomerDetail($result1);

                //$object = (object) $customerResult;

                //$dataArray = [];

                //$dataArray[] = $object;
                $obj = new stdClass;
                foreach($customerResult as $key => $value){                    
                    $obj->$key = $value;
                    

                } 

                //$dataArray[] = $obj;

                //die(print_r($dataArray));

                $response = $this->Webhook->callTrigger($user_info->webhook_customer_created, $obj ); //$this->CustomerModel->getCustomerDetail($result1)
                //die(print_r($response));
            }
            

            if (!empty($data['assign_property'])) {

                foreach ($data['assign_property'] as $value) {
                    $param2 = array(
                        'property_id' => $value,
                        'customer_id' => $result1
                    );
                    $result = $this->CustomerModel->assignProperty($param2);
                }
            }          


            if ($result1) {

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> added successfully</div>');
                // redirect("admin/editcustomer/".$result1);
                redirect("admin/addProperty/" . $result1);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> not added.</div>');
                redirect("admin/customerList");
            }
        }
    }

    // Imports csv data in Customer, Property and Customer-Property relation table.
    public function addCustomerCsv()
    {
        $filename = $_FILES["csv_file"]["tmp_name"];
        if ($_FILES["csv_file"]["size"] > 0) {
            $company_id = $this->session->userdata('company_id');
            $user_id = $this->session->userdata('user_id');
            $row = 1;
            if (($handle = fopen($filename, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($row == 1) {
                        $row++;
                        continue;
                    }
                    $customer_param = array(
                        'user_id' => $user_id,
                        'company_id' => $company_id,
                        'first_name' => $data[0],
                        'last_name' => $data[1],
                        'customer_company_name' => $data[2],
                        'email' => $data[3],
                        'secondary_email' => $data[4],
                        'phone' => $data[5],
                        'home_phone' => $data[6],
                        'work_phone' => $data[7],
                        'billing_street' => $data[8],
                        'billing_street_2' => $data[9],
                        'billing_city' => $data[10],
                        'billing_state' => $data[11],
                        'billing_zipcode' => $data[12],
                        'customer_status' => $data[13],
                        'pre_service_notification' => '["2"]',
                    );
                    $customer_where = array(
                        'company_id' => $company_id,
                        'billing_street' => $customer_param['billing_street']
                    );
                    $property_param = array(
                        'user_id' => $user_id,
                        'company_id' => $company_id,
                        'property_title' => $data[14],
                        'property_address' => $data[15],
                        'property_address_2' => $data[16],
                        'property_city' => $data[17],
                        'property_state' => $data[18],
                        'property_zip' => $data[19],
                        'property_area' => $data[20],
                        'property_type' => $data[21],
                        'yard_square_feet' => $data[22],
                        'property_notes' => $data[23],
                    );
                    $sales_tax_param = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'company_id' => $this->session->userdata('company_id'),
                        'tax_name' => $data[24],
                        'tax_value' => $data[25],
                    );
                    $program_param = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'company_id' => $this->session->userdata('company_id'),
                        'program_name' => $data[26],
                        'program_price' => $data[27],
                        'program_notes' => $data[28],
                        'price_override' => $data[29],
                    );
                    if ($customer_param['customer_status'] != 0 && $customer_param['customer_status'] != 1 && $customer_param['customer_status'] != 2) {
                        $customer_param['customer_status'] = 0;
                    }
                    $customer_param = array_filter($customer_param);
                    $property_param = array_filter($property_param);
                    $sales_tax_param = array_filter($sales_tax_param);

                    if (array_key_exists("first_name", $customer_param) && array_key_exists("last_name", $customer_param) && array_key_exists("billing_street", $customer_param) && array_key_exists("billing_city", $customer_param) && array_key_exists("billing_state", $customer_param) && array_key_exists("billing_zipcode", $customer_param)) {
                        $customer_info = $this->CustomerModel->getOneCustomer($customer_where);
                        $sale_tax_area_id = $this->salesTaxManageForProperty($sales_tax_param);
                        $property_id = $this->PropertyAddByCustomer($property_param, $sale_tax_area_id, $program_param);
                        // Checks if customer exist or not
                        if (!$customer_info) {
                            // If customer not exist, creates new customer
                            $quickbook_customer = $this->createCustomerInQuickbook($customer_param);
                            if ($quickbook_customer['status'] == 201) {
                                $customer_param['quickbook_customer_id'] = $quickbook_customer['result'];
                            }
                            $result = $this->CustomerModel->insert_customer($customer_param);
                            if ($property_id) {
                                $param3 = array(
                                    'property_id' => $property_id,
                                    'customer_id' => $result
                                );
                                $already = $this->CustomerModel->getOnecustomerPropert($param3);
                                if (!$already) {
                                    $this->CustomerModel->assignProperty($param3);
                                }
                            }
                        } else {
                            // If already customer update customer information
                            $csv_customer_update_param = array();
                            $csv_customer_update_param['first_name'] = (isset($customer_param['first_name']) && $customer_info->first_name != $customer_param['first_name']) ? $customer_param['first_name'] : '';
                            $csv_customer_update_param['last_name'] = (isset($customer_param['last_name']) && $customer_info->last_name != $customer_param['last_name']) ? $customer_param['last_name'] : '';
                            $csv_customer_update_param['customer_company_name'] = (isset($customer_param['customer_company_name']) && $customer_info->customer_company_name != $customer_param['customer_company_name']) ? $customer_param['customer_company_name'] : '';
                            $csv_customer_update_param['email'] = (isset($customer_param['email']) && $customer_info->email != $customer_param['email']) ? $customer_param['email'] : '';
                            $csv_customer_update_param['secondary_email'] = (isset($customer_param['secondary_email']) && $customer_info->email != $customer_param['secondary_email']) ? $customer_param['secondary_email'] : '';
                            $csv_customer_update_param['phone'] = (isset($customer_param['phone']) && $customer_info->phone != $customer_param['phone']) ? $customer_param['phone'] : '';
                            $csv_customer_update_param['home_phone'] = (isset($customer_param['home_phone']) && $customer_info->home_phone != $customer_param['home_phone']) ? $customer_param['home_phone'] : '';
                            $csv_customer_update_param['work_phone'] = (isset($customer_param['work_phone']) && $customer_info->work_phone != $customer_param['work_phone']) ? $customer_param['work_phone'] : '';
                            $csv_customer_update_param['billing_street_2'] = (isset($customer_param['billing_street_2']) && $customer_info->billing_street_2 != $customer_param['billing_street_2']) ? $customer_param['billing_street_2'] : '';
                            $csv_customer_update_param['billing_city'] = ($customer_info->billing_city != $customer_param['billing_city']) ? $customer_param['billing_city'] : '';
                            $csv_customer_update_param['billing_state'] = (isset($customer_param['billing_state']) && $customer_info->billing_state != $customer_param['billing_state']) ? $customer_param['billing_state'] : '';
                            $csv_customer_update_param['billing_zipcode'] = (isset($customer_param['billing_zipcode']) && $customer_info->billing_zipcode != $customer_param['billing_zipcode']) ? $customer_param['billing_zipcode'] : '';
                            $csv_customer_update_param['customer_status'] = (isset($customer_param['customer_status']) && $customer_info->customer_status != $customer_param['customer_status']) ? $customer_param['customer_status'] : '';
                            $csv_customer_update_param = array_filter($csv_customer_update_param);
                            $csv_customer_update_where = array('customer_id' => $customer_info->customer_id);
                            if (count($csv_customer_update_param) > 0) {
                                // If csv row has any column value changed.
                                $this->CustomerModel->updateCustomerData($csv_customer_update_param, $csv_customer_update_where);
                            }
                            if ($property_id) {
                                $param3 = array(
                                    'property_id' => $property_id,
                                    'customer_id' => $customer_info->customer_id
                                );
                                $already = $this->CustomerModel->getOnecustomerPropert($param3);
                                if (!$already) {
                                    $this->CustomerModel->assignProperty($param3);
                                }
                            }
                        } // cudtomer found else
                    }  //  empty row check
                } // loop
                fclose($handle);
                if (isset($customer_info) && !isset($result)) {
                    echo 0;
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customers </strong> already exists.</div>');
                    //echo "already he add nahi";
                } else if (!isset($customer_info) && isset($result)) {
                    echo 1;
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customers </strong> added successfully</div>');
                    //echo "already nahi result he";
                } else if (isset($customer_info) && isset($result)) {
                    echo 3;
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customers </strong> added successfully</div>');
                } else if (isset($result)) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customers </strong> added successfully</div>');
                } else {
                    echo 4;
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Please check your csv file.</strong> This file is not valid.</div>');
                    //echo "swr";
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong> file</strong> can not read please check file.</div>');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong> Do</strong> not select black file.</div>');
        }
        redirect("admin/customerList");
    }

    public function PropertyAddByCustomer($property_param, $sale_tax_area_id, $program_param)
    {
        if (array_key_exists("property_title", $property_param) && array_key_exists("property_address", $property_param) && array_key_exists("property_city", $property_param) && array_key_exists("property_state", $property_param) && array_key_exists("property_zip", $property_param) && array_key_exists("property_type", $property_param) && array_key_exists("yard_square_feet", $property_param)) {
            if ($property_param['property_type'] == 1) {
                $property_param['property_type'] = 'Commercial';
            } elseif ($property_param['property_type'] == 2) {
                $property_param['property_type'] = 'Residential';
            } else {
                $property_param['property_type'] = 'Commercial';
            }
            if (isset($property_param['property_area']) && $property_param['property_area'] != '') {
                $checkarea = $this->ServiceArea->getOneServiceArea(array('company_id' => $property_param['company_id'], 'category_area_name' => $property_param['property_area']));
                if ($checkarea) {
                    $area = $checkarea->property_area_cat_id;
                    $property_param['property_area'] = $area;
                } else {
                    $area = $this->ServiceArea->CreateOneServiceArea(array('company_id' => $property_param['company_id'], 'user_id' => $property_param['user_id'], 'category_area_name' => $property_param['property_area']));
                    $property_param['property_area'] = $area;
                }
            }
            $property_where = array(
                'company_id' => $property_param['company_id'],
                'property_address' => $property_param['property_address']
            );
            $property_info = $this->PropertyModel->getOneProperty($property_where);
            $program_id = $this->programAddByProperty($program_param);

            if (!$property_info) {
                $geo = $this->getLatLongByAddress2($property_param['property_address']);
                if ($geo) {
                    $property_param['property_latitude'] = $geo['lat'];
                    $property_param['property_longitude'] = $geo['long'];
                    $result = $this->PropertyModel->insert_property($property_param);
                    if ($sale_tax_area_id > 0) {
                        $where_pr_tax = array(
                            'property_id' => $result,
                            'property_sales_tax.sale_tax_area_id' => $sale_tax_area_id
                        );
                        $ch_pr_tax = $this->PropertySalesTax->getOnePropertySalesTax($where_pr_tax);
                        if (!$ch_pr_tax) {
                            $pr_tax_ar = array(
                                'property_id' => $result,
                                'sale_tax_area_id' => $sale_tax_area_id
                            );
                            $this->PropertySalesTax->CreateOnePropertySalesTax($pr_tax_ar);
                        }
                    }
                    // Entry in program-property table.
                    $this->propertyProgramInsert(array("property_id" => $result, "program_id" => $program_id, "price_override" => $program_param['price_override']));
                    return $result;
                } else {
                    return false;
                }
            } else {
                // If already property update property information
                $csv_property_update_param = array();
                $csv_property_update_param['property_title'] = (isset($property_param['property_title']) && $property_info->property_title != $property_param['property_title']) ? $property_param['property_title'] : '';
                $csv_property_update_param['property_address_2'] = (isset($property_param['property_address_2']) && $property_info->property_address_2 != $property_param['property_address_2']) ? $property_param['property_address_2'] : '';
                $csv_property_update_param['property_city'] = (isset($property_param['property_city']) && $property_info->property_city != $property_param['property_city']) ? $property_param['property_city'] : '';
                $csv_property_update_param['property_state'] = (isset($property_param['property_state']) && $property_info->property_state != $property_param['property_state']) ? $property_param['property_state'] : '';
                $csv_property_update_param['property_zip'] = (isset($property_param['property_zip']) && $property_info->property_zip != $property_param['property_zip']) ? $property_param['property_zip'] : '';
                $csv_property_update_param['property_area'] = (isset($property_param['property_area']) && $property_info->property_area != $property_param['property_area']) ? $property_param['property_area'] : '';
                $csv_property_update_param['property_type'] = (isset($property_param['property_type']) && $property_info->property_type != $property_param['property_type']) ? $property_param['property_type'] : '';
                $csv_property_update_param['yard_square_feet'] = (isset($property_param['yard_square_feet']) && $property_info->yard_square_feet != $property_param['yard_square_feet']) ? $property_param['yard_square_feet'] : '';
                $csv_property_update_param['property_notes'] = (isset($property_param['property_notes']) && $property_info->property_notes != $property_param['property_notes']) ? $property_param['property_notes'] : '';
                $csv_property_update_param = array_filter($csv_property_update_param);
                $csv_property_update_where = array('property_id' => $property_info->property_id);
                if (count($csv_property_update_param) > 0) {
                    // If csv row has any column value change)
                    $this->PropertyModel->updatePropertyData($csv_property_update_param, $csv_property_update_where);
                }
                if ($sale_tax_area_id > 0) {
                    $where_pr_tax = array(
                        'property_id' => $property_info->property_id,
                        'property_sales_tax.sale_tax_area_id' => $sale_tax_area_id
                    );
                    $ch_pr_tax = $this->PropertySalesTax->getOnePropertySalesTax($where_pr_tax);
                    if (!$ch_pr_tax) {
                        $pr_tax_ar = array(
                            'property_id' => $property_info->property_id,
                            'sale_tax_area_id' => $sale_tax_area_id
                        );
                        $this->PropertySalesTax->CreateOnePropertySalesTax($pr_tax_ar);
                    }
                }
                // Entry in program-property table.
                $this->propertyProgramInsert(array("property_id" => $property_info->property_id, "program_id" => $program_id, "price_override" => $program_param['price_override']));
                return $property_info->property_id;
            }
        } else {
            return false;
        }
    }

    /**
     * Inserts entry in property_program_assign table if entry for property-program not exist.
     * @param array $property_program_data
     */
    public function propertyProgramInsert($property_program_data)
    {
        $property_program_where = array("program_id" => $property_program_data["program_id"], "property_id" => $property_program_data["property_id"]);
        $property_program_info = $this->PropertyModel->getOnePropertyProgram($property_program_where);
        if (!$property_program_info) {
            $this->PropertyModel->assignProgram($property_program_data);
        }
    }

    public function editCustomer($customerID = NULL, $propertyID = NULL, $active = 0)
    {

        if (!empty($customerID)) {
            $customerID = $customerID;
        } else {
            $customerID = $this->uri->segment(4);
        }
        if (!empty($propertyID)) {
            $propertyID = $propertyID;
        } else {
            $propertyID = $this->uri->segment(5);
        }
        $company_id = $this->session->userdata['company_id'];
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $data['customerData'] = $this->CustomerModel->getCustomerDetail($customerID);
        //die(print_r($data['customerData']));
        $data['servicelist'] = $this->JobModel->getJobList($where);
        $data['all_services'] = $this->DashboardModel->getCustomerAllServicesWithSalesRep(array('jobs.company_id' => $company_id, 'property_tbl.company_id' => $company_id, 'customers.customer_id' => $customerID));

        foreach ($data['all_services'] as $all_services) {
            $cost = 0;
            if ($all_services->job_cost == NULL) {
                // got this math from updateProgram - used to calculate price of job when not pulling it from an invoice
                $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $all_services->property_id, 'program_id' => $all_services->program_id));

                if ($priceOverrideData->is_price_override_set == 1) {
                    // $price = $priceOverrideData->price_override;
                    $cost = $priceOverrideData->price_override;
                } else {
                    //else no price overrides, then calculate job cost
                    $lawn_sqf = $all_services->yard_square_feet;
                    $job_price = $all_services->job_price;

                    //get property difficulty level
                    if (isset($all_services->difficulty_level) && $all_services->difficulty_level == 2) {
                        $difficulty_multiplier = $data['setting_details']->dlmult_2;
                    } elseif (isset($all_services->difficulty_level) && $all_services->difficulty_level == 3) {
                        $difficulty_multiplier = $data['setting_details']->dlmult_3;
                    } else {
                        $difficulty_multiplier = $data['setting_details']->dlmult_1;
                    }

                    //get base fee
                    if (isset($all_services->base_fee_override)) {
                        $base_fee = $all_services->base_fee_override;
                    } else {
                        $base_fee = $data['setting_details']->base_service_fee;
                    }

                    $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                    //get min. service fee
                    if (isset($all_services->min_fee_override)) {
                        $min_fee = $all_services->min_fee_override;
                    } else {
                        $min_fee = $data['setting_details']->minimum_service_fee;
                    }

                    // Compare cost per sf with min service fee
                    if ($cost_per_sqf > $min_fee) {
                        $cost = $cost_per_sqf;
                    } else {
                        $cost = $min_fee;
                    }
                }
                $all_services->job_cost = $cost;
            }
        }

        $data['alerts'] = json_decode($data['customerData']['alerts']);
        //$data['propertylist'] = $this->CustomerModel->getPropertyList($where);
        $data['taglist'] = $this->PropertyModel->getTagsList($where);
        // die(print_r($data['propertylist']));
        #properties for alerts dropdown
        $data['all_customer_properties'] = $this->PropertyModel->getAllCustomerProperties($customerID);
        // die(print_r($data['all_customer_properties']));
        /// GET ASSIGNED PROGRAMS
        $customerProperties = $this->PropertyModel->getAllActiveCustomerProperties($customerID);

        $prop_programs = array();
        foreach ($customerProperties as $k => $prop) {
            //get programs 
            $programs = $this->PropertyModel->getAssignedPrograms(array('property_tbl.property_id' => $prop->property_id, 'program_active' => 1, 'ad_hoc' => 0));

            //die(print_r($programs));
            foreach ($programs as $program) {
                $prop_programs[] = array(
                    'property_id' => $prop->property_id,
                    'property_title' => $prop->property_title,
                    'program_name' => $program->program_name,
                    'sales_rep' => $program->sales_rep_name,
                    'id_sales_rep' => $program->id_sales_rep
                );
            }
        }

        $data['prop_programs'] = $prop_programs;
        ////////////////////////

        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList($where);
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);

        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $data['company_name'] = $data['setting_details']->company_name;
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' => $company_id));
        $data['send_daily_invoice_mail'] = isset($company_email_details['send_daily_invoice_mail']) ? $company_email_details['send_daily_invoice_mail'] : 0;

        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where);

        $data['basys_details'] = $this->CompanyModel->getOneBasysRequest(array('company_id' => $company_id, 'status' => 1));

        $selecteddata = $this->CustomerModel->getSelectedProperty($customerID);


        $data['selectedpropertylist'] = array();
        $data['selectedPropertyDetailsList'] = array();


        if (!empty($selecteddata)) {
            foreach ($selecteddata as $value) {

                $selectedDataDetails = $this->CustomerModel->getSelectedPropertyDetails($value->property_id);

                foreach ($selectedDataDetails as $DetailsValue) {
                    $prop = new stdClass;
                    $prop->id = $value->property_id;
                    $prop->title = $DetailsValue->property_title;
                    $prop->address = $DetailsValue->property_address;
                    $prop->status = $DetailsValue->property_status;
                }


                $data['selectedPropertyDetailsList'][] = $prop;
            }
        }


        if (!empty($selecteddata)) {
            foreach ($selecteddata as $value) {
                $selectedDataDetails = $this->CustomerModel->getSelectedPropertyDetails($value->property_id);

                $data['selectedpropertylist'][] = $value->property_id;
            }
        }
        $data['propertyconditionslist'] = $this->PropertyModel->getCompanyPropertyConditions(array('company_id' => $this->session->userdata['company_id']));
        $data['program_details'] = $this->ProgramModel->get_all_program(array('company_id' => $this->session->userdata['company_id']));

        $data['propertyselectedconditions'] = $this->PropertyModel->getPropertyConditions(array('company_id' => $this->session->userdata['company_id'], 'property_condition_assign.property_id' => $propertyID));


        // $selectedprogramdata = $this->CustomerModel->getAssignProgramscustomer(array('customer_id'=>$customerID));

        //    $data['selectedprogramlist']  = array();

        // if (!empty($selectedprogramdata)) {
        //                 foreach ($selectedprogramdata as $value) {
        //                     $data['selectedprogramlist'][] = $value->program_id;
        //                 }

        //         }


        //$where_comapny = array('company_id' => $this->session->userdata['company_id']);
        //    $data['company_details'] = $this->CompanyModel->getOneCompany($where_comapny);


        $where = array(
            'technician_job_assign.company_id' => $this->session->userdata['company_id'],
            'technician_job_assign.customer_id' => $customerID,
        );

        $data['customer_all_jobs'] = $this->DashboardModel->getAssignTechnician($where);

        $coupon_where = array(
            'company_id' => $this->session->userdata['company_id'],
            'type' => 0
        );
        $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);

        $coupon_where = array(
            'company_id' => $this->session->userdata['company_id'],
            'type' => 1
        );
        $data['customer_perm_coupons'] = $this->CouponModel->getAllCoupon($coupon_where);

        $coupon_where = array(
            'customer_id' => $customerID
        );
        $data_temp_coupon = $this->CouponModel->getCouponCustomers($coupon_where);
        $data['customer_existing_perm_coupons'] = array();
        if (!empty($data_temp_coupon)) {
            foreach ($data_temp_coupon as $value) {
                $data['customer_existing_perm_coupons'][] = $value->coupon_id;
            }
        }

        // $data['customer_all_jobs'] = array_merge($data['customer_all_jobs'], $this->DashboardModel->getUnassignJobs($where));

        // $new_data = $this->DashboardModel->getUnassignJobs( array('jobs.company_id' => $this->session->userdata['company_id'], 'customers.customer_id' =>$customerID) );
        // $data['customer_all_jobs'] = $new_data;
        // echo "<pre>";
        // print_r($data['customer_one_time_discounts']);
        // die();

        $data['invoice_details'] = $this->INV->getAllInvoive(array('invoice_tbl.company_id' => $this->session->userdata['company_id'], 'invoice_tbl.customer_id' => $customerID, 'is_archived' => 0));

        //get payment terms
        // 1 = Due on Receipt, 2 = Net 7, 3 = Net 10, 4 = Net 14, 5 = Net 15, 6 = Net 20, 7 = Net 30, 8 = Net 45, 9 = Net 60, 10 = Net 90
        $payment_terms_id = $this->CompanyModel->getPaymentTerms(array('company_id' => $company_id));
        switch ($payment_terms_id->payment_terms) {
            case 1: // 1 = Due on Receipt
                $payment_terms = 0;
                break;
            case 2: // 2 = Net 7
                $payment_terms = 7;
                break;
            case 3: // 3 = Net 10
                $payment_terms = 10;
                break;
            case 4: // 4 = Net 14
                $payment_terms = 14;
                break;
            case 5: // 5 = Net 15
                $payment_terms = 15;
                break;
            case 6: // 6 = Net 20
                $payment_terms = 20;
                break;
            case 7: // 7 = Net 30
                $payment_terms = 30;
                break;
            case 8: // 8 = Net 45
                $payment_terms = 45;
                break;
            case 9: // 9 = Net 60
                $payment_terms = 60;
                break;
            case 10: // 10 = Net 90
                $payment_terms = 90;
                break;
        }

        $outstanding = array();

        // die(print_r($data['invoice_details']));

        foreach ($data['invoice_details'] as $k => $i) {

            $refund_date = $this->INV->getRefundDate($i->invoice_id);

            if (isset($refund_date)) {
                $data['invoice_details'][$k]->refund_datetime = $refund_date->refund_datetime;
            }
            // die(print_r($data['invoice_details']));
            //filter out incomplete services with program price == 2
            $assigned = $this->DashboardModel->getAssignTechnician(array('invoice_id' => $i->invoice_id, 'program_price' => 2));
            if ($assigned) {
                // die(print_r($assigned));
                foreach ($assigned as $key => $row) {
                    if ($row->is_complete != 1) {
                        $data['invoice_details'][$k]->is_complete = 0;
                        // Assign value returned for is_complete to new value in array to use in determining if Invoice should show up in list
                    } else if ($row->is_complete == 1) {
                        $data['invoice_details'][$k]->is_complete = 1;
                    }
                }
                // Listen for case where Invoice exists for Invoice at Job Completion Service but job is not assigned and not completed
            } else if (!$assigned && $data['invoice_details'][$k]->program_price == 2) {
                $data['invoice_details'][$k]->is_complete = 0;
            }
        }

        // If Invoice at Job Completion and job isn't completed exclude from list
        foreach ($data['invoice_details'] as $k => $i) {
            if ($data['invoice_details'][$k]->program_price == 2 && $data['invoice_details'][$k]->is_complete == 0) {
                unset($data['invoice_details'][$k]);
            }
        }


        // die(print_r($data['invoice_details']));

        foreach ($data['invoice_details'] as $k => $i) {
            //if invoice is NOT archived and invoice is NOT paid...
            if ($i->is_archived != 1 && $i->payment_status != 2 && $i->status !== 0) {
                if (isset($i->first_sent_date)) {
                    $invoiceDate = $i->first_sent_date;
                } else {
                    $invoiceDate = $i->invoice_date;
                }


                ////////////////////////////////////
                // START INVOICE CALCULATION COST //

                // invoice cost
                // $invoice_total_cost = $invoice->cost;

                // cost of all services (with price overrides) - service coupons
                $job_cost_total = 0;
                $where = array(
                    'property_program_job_invoice.invoice_id' => $i->invoice_id
                );
                $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);
                if (!empty($proprojobinv)) {
                    foreach ($proprojobinv as $job) {

                        $job_cost = $job['job_cost'];

                        $job_where = array(
                            'job_id' => $job['job_id'],
                            'customer_id' => $job['customer_id'],
                            'property_id' => $job['property_id'],
                            'program_id' => $job['program_id']
                        );
                        $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                        if (!empty($coupon_job_details)) {

                            foreach ($coupon_job_details as $coupon) {
                                // $nestedData['email'] = json_encode($coupon->coupon_amount);
                                $coupon_job_amm_total = 0;
                                $coupon_job_amm = $coupon->coupon_amount;
                                $coupon_job_calc = $coupon->coupon_amount_calculation;

                                if ($coupon_job_calc == 0) { // flat amm
                                    $coupon_job_amm_total = (float)$coupon_job_amm;
                                } else { // percentage
                                    $coupon_job_amm_total = ((float)$coupon_job_amm / 100) * $job_cost;
                                }

                                $job_cost = $job_cost - $coupon_job_amm_total;

                                if ($job_cost < 0) {
                                    $job_cost = 0;
                                }
                            }
                        }

                        $job_cost_total += $job_cost;
                    }
                } else {

                    // IF none from that table, is old invoice, calculate old way
                    $job_cost_total = $i->cost;
                }
                $invoice_total_cost = $job_cost_total;

                // die(print_r($invoice_total_cost));

                // check price override -- any that are not stored in just that ^^.

                // - invoice coupons
                $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $i->invoice_id));
                foreach ($coupon_invoice_details as $coupon_invoice) {
                    if (!empty($coupon_invoice)) {
                        $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                        $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                        if ($coupon_invoice_amm_calc == 0) { // flat amm
                            $invoice_total_cost -= (float)$coupon_invoice_amm;
                        } else { // percentage
                            $coupon_invoice_amm = ((float)$coupon_invoice_amm / 100) * $invoice_total_cost;
                            $invoice_total_cost -= $coupon_invoice_amm;
                        }
                        if ($invoice_total_cost < 0) {
                            $invoice_total_cost = 0;
                        }
                    }
                }

                // die(print_r($invoice_total_cost));

                // + tax cost
                $invoice_total_tax = 0;
                $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $i->invoice_id));
                if (!empty($invoice_sales_tax_details)) {
                    foreach ($invoice_sales_tax_details as $tax) {
                        if (array_key_exists("tax_value", $tax)) {
                            $tax_amm_to_add = ((float)$tax['tax_value'] / 100) * $invoice_total_cost;
                            $invoice_total_tax += $tax_amm_to_add;
                        }
                    }
                }
                $invoice_total_cost += $invoice_total_tax;
                $total_tax_amount = $invoice_total_tax;

                // die(print_r($invoice_total_cost));

                // END TOTAL INVOICE CALCULATION COST //
                ////////////////////////////////////////
                //late fee
                $late_fee = $this->INV->getLateFee($i->invoice_id);

                $cost = '$ ' . number_format($invoice_total_cost + $late_fee, 2);
                // $due = $invoice_total_cost - $i->partial_payment;
                if ($i->refund_amount_total == 0) {

                    $due = ($i->cost - $i->partial_payment == 0) ? 0 : $invoice_total_cost - $i->partial_payment;;
                } else {
                    $due = 0;
                }
                if ($due < 0) {
                    $due = 0;
                }
                $balance_due = 0 ? '$ 0.00' : '$ ' . number_format($due + $late_fee, 2);

                if ($i->payment_status != 2) {
                    $outstanding[] = array(
                        'invoice_id' => $i->invoice_id,
                        'amount_due' => $balance_due,
                        'due_date' => date('Y-m-d', strtotime($invoiceDate . '+ ' . $payment_terms . ' day')),
                    );
                }

                // die(print_r($invoice_total_cost));

                if (isset($data['invoice_details'][$k]) && !empty($data['invoice_details'][$k])) {
                    $data['invoice_details'][$k]->total_cost_actual = $cost;

                }

                // die(print_r($data['invoice_details'][$k]));


            } else {

                $invoice_total_cost = $i->cost;

                // + tax cost
                $invoice_total_tax = 0;
                $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $i->invoice_id));
                // die(print_r($invoice_sales_tax_details));
                if (!empty($invoice_sales_tax_details)) {
                    foreach ($invoice_sales_tax_details as $tax) {
                        if (array_key_exists("tax_value", $tax)) {
                            $tax_amm_to_add = ((float)$tax['tax_value'] / 100) * $invoice_total_cost;
                            $invoice_total_tax += $tax_amm_to_add;
                        }
                    }
                }
                $invoice_total_cost += $invoice_total_tax;

                $cost = '$ ' . number_format($invoice_total_cost, 2);


                if (isset($data['invoice_details'][$k]) && !empty($data['invoice_details'][$k])) {
                    $data['invoice_details'][$k]->total_cost_actual = $cost;
                }
            }
        }
        // echo "<pre>";
        // print_r($data['invoice_details']);
        // die();
        $data['outstanding'] = $outstanding;

        $data['programlist'] = $this->PropertyModel->getProgramList(array('company_id' => $this->session->userdata['company_id']));


        $data['active_nav_link'] = $active;

        ////// GET UNSCHEDULED SERVICES
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_id' => $customerID,
            'property_status !=' => 0
        );
        $unassignedServices = $this->DashboardModel->getCustomerUnschedServ($where);

        // cancelled services
        $data['cancel_reasons'] = $this->CustomerModel->getCancelReasons($this->session->userdata['company_id']);

        if (!empty($data['all_services'])) {
            foreach ($data['all_services'] as $key => $val) {
                $canc_arr = array(
                    'job_id' => $val->job_id,
                    'customer_id' => $val->customer_id,
                    'program_id' => $val->program_id,
                    'property_id' => $val->property_id
                );
                $data['all_services'][$key]->cancelled = $this->CST->getIsCancelledService($canc_arr);
            }
        }
        if (!empty($unassignedServices)) {

            foreach ($unassignedServices as $key => $value) {
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $value->property_id,
                );
                $assign_table_data = $this->Tech->GetOneRow($arrayName);
                // die(print_r($assign_table_data));
                if ($assign_table_data) {
                    if ($assign_table_data->is_job_mode != 1) {
                        unset($unassignedServices[$key]);
                    }
                }
                #check if cancelled and remove from unassigned service list if true
                $checkCancelled = $this->CST->getIsCancelledService($arrayName);
                if (!empty($checkCancelled)) {
                    unset($unassignedServices[$key]);
                }
            }
        }
        $data['unscheduled'] = $unassignedServices;

        $data['all_customers'] = $this->CustomerModel->get_all_customer(array('company_id' => $this->session->userdata['company_id']));
        /////////////////////////////////////
        /// GET SCHEDULED SERVICES
        $data['scheduled'] = $this->DashboardModel->getAssignTechnician(array('technician_job_assign.company_id' => $company_id, 'is_job_mode' => 0, 'technician_job_assign.customer_id' => $customerID));

        /// Get Notes
        $company_id = $this->session->userdata['company_id'];
        $where = array('company_id' => $company_id);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);
        $data['customer_properties'] = $customerProperties;
        $property_ids = array_column($customerProperties, 'property_id');

        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);

        $page_index = isset($filter['page']) ? $filter['page'] : 1;
        $config = $this->load_paginate_configuration();
        $config["base_url"] = base_url() . "admin/editCustomer/" . $customerID;
        $config['per_page'] = isset($filter['per_page']) ? $filter['per_page'] : 10;
        $config["total_rows"] = $this->CompanyModel->getCustomerNotes($customerID, [], $property_ids, true);

        $this->pagination->initialize($config);

        $data['combined_notes'] = $this->CompanyModel->getCustomerNotes($customerID, [], $property_ids, false, $config['per_page'], $page_index);
        $data["pagination_links"] = $this->pagination->create_links();
        $data['per_page_arr'] = self::PER_PAGE_ARR;

        if (!empty($data['combined_notes'])) {
            foreach ($data['combined_notes'] as $note) {
                $note->comments = $this->CompanyModel->getNoteComments($note->note_id);
                $note->files = $this->CompanyModel->getNoteFiles($note->note_id);
            }
        }

        $data['note_types'] = $this->CompanyModel->getNoteTypes($this->session->userdata['company_id']);

        // GET Property Details
        $where_arr = array(
            'property_id' => $propertyID
        );
        $customerProperty = $this->PropertyModel->getPropertySelected($where_arr);
        $data['customer_property'] = $customerProperty;
        $data['source_list'] = $this->SourceModel->getAllSource(array('company_id' => $this->session->userdata['company_id']));
        $data['users'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
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
        $data['note_types'] = $this->CompanyModel->getNoteTypes($this->session->userdata['company_id']);

        $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $customerID));
        $data['coupon_customers'] = $coupon_customers;
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

            }
        }


        $service_specific_id = "";
        foreach ($data['note_types'] as $type) {
            if ($type->type_name == "Service-Specific" && $type->type_company_id == 0) {
                $service_specific_id = $type->type_id;
            }
        }
        $data['service_specific_note_type_id'] = $service_specific_id;
        $data['service_areas'] = $this->ServiceArea->getAllServiceArea(['company_id' => $this->session->userdata['company_id']]);
        $data['polygon_bounds'] = [];
        foreach ($data['service_areas'] as $k => $v) {
            if ($v->service_area_polygon)
                $data['polygon_bounds'][] = ["latlng" => $v->service_area_polygon, "marker" => $v->category_area_name, "property_area_cat_id" => $v->property_area_cat_id];
        }

        $page["active_sidebar"] = "customer";
        $page["page_name"] = "Update Customer";
        $page["page_content"] = $this->load->view("admin/edit_customer", $data, TRUE);

        $this->layout->superAdminTemplateTable($page);
    }

    public function ajaxCustomerNotes()
    {
        $filter = $this->input->post();
        $customerID = $filter['customer_id'];
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['servicelist'] = $this->JobModel->getJobList($where);
        $customerProperties = $this->PropertyModel->getAllActiveCustomerProperties($customerID);
        $data['customer_properties'] = $customerProperties;
        $property_ids = array_column($customerProperties, 'property_id');

        $page_index = isset($filter['page']) ? $filter['page'] : 1;
        $config = $this->load_paginate_configuration();
        $config['uri_segment'] = $page_index;
        $config["base_url"] = base_url() . "admin/editCustomer/" . $customerID;
        $config['per_page'] = isset($filter['per_page']) ? $filter['per_page'] : 10;
        $config["total_rows"] = $this->CompanyModel->getCustomerNotes($customerID, $filter, $property_ids, true);
        $this->pagination->initialize($config);
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);

        $data['combined_notes'] = $this->CompanyModel->getCustomerNotes($customerID, $filter, $property_ids, false, $config['per_page'], $page_index);
        $data["pagination_links"] = $this->pagination->create_links();
        $data['per_page_arr'] = self::PER_PAGE_ARR;
        $data['filter'] = $filter;

        if (!empty($data['combined_notes'])) {
            foreach ($data['combined_notes'] as $note) {
                $note->comments = $this->CompanyModel->getNoteComments($note->note_id);
                $note->files = $this->CompanyModel->getNoteFiles($note->note_id);
            }
        }

        $data['note_types'] = $this->CompanyModel->getNoteTypes($this->session->userdata['company_id']);

        $service_specific_id = "";
        foreach ($data['note_types'] as $type) {
            if ($type->type_name == "Service-Specific" && $type->type_company_id == 0) {
                $service_specific_id = $type->type_id;
            }
        }
        $data['service_specific_note_type_id'] = $service_specific_id;

        echo $this->load->view("admin/ajax_to_view/customer_notes", $data, TRUE);
    }

    public function assignPropertyList()
    {

        $data = $this->CustomerModel->getPropertyListFromAutoComplete($this->session->userdata['company_id'], $_POST['keyword']);


        if (!empty($data)) {
            echo "<ul id='property-list'>";

            foreach ($data as $property) {
                //$property->property_status; - use to decide if line is disabled
                if ($property->property_status != 0) {
                    echo '<li class="PropertyListField" data-id="' . $property->property_id . '" onClick="selectProperty($(this),';

                    echo "'";
                    echo $property->property_id;
                    echo "'";

                    echo ", ";
                    echo "'";
                    echo $property->property_title;
                    echo "'";

                    echo ");";
                    echo '">';
                    echo $property->property_title;
                    echo "</li>";

                }

            }

            echo "</ul>";
        } else {
            return false;
        }


        //return $data;

    }


    public function assignCustomerListEditCustomer()
    {

        $data = $this->CustomerModel->getCustomerListFromAutoComplete($this->session->userdata['company_id'], $_POST['keyword']);


        if (!empty($data)) {
            echo "<ul id='customer-list'>";

            foreach ($data as $customer) {

                echo '<li class="customerListField" onClick="selectCustomer(';

                echo "'";
                echo $customer->customer_id;
                echo "'";

                echo ", ";
                echo "'";
                echo $customer->last_name . " " . $customer->first_name;
                echo "'";

                echo ");";
                echo '">';
                echo $customer->last_name . " " . $customer->first_name;
                echo "</li>";

            }

            echo "</ul>";
        } else {
            return false;
        }


        //return $data;

    }


    public function updateCustomer()
    {

        $user_id = $this->session->userdata['user_id'];

        $post_data = $this->input->post();

        $customerid = $this->input->post('customer_id');


        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim');
        $this->form_validation->set_rules('customer_company_name', 'customer_company_name', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');
        $this->form_validation->set_rules('billing_street', 'Billing Street', 'required');
        $this->form_validation->set_rules('billing_street_2', 'Billing Street 2', 'trim');
        $this->form_validation->set_rules('billing_city', 'City', 'required');
        $this->form_validation->set_rules('billing_state', 'State', 'required');
        $this->form_validation->set_rules('billing_zipcode', 'ZipCode', 'required');
        $this->form_validation->set_rules('assign_property[]', 'Assign Property', 'trim');
        $this->form_validation->set_rules('customer_status', 'Customer Status', 'required');
        $this->form_validation->set_rules('billing_type', 'Billing Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->editCustomer($customerid);
        } else {
            $post_data = $this->input->post();
            if (isset($post_data['pre_service_notification']) && !empty($post_data['pre_service_notification'])) {
                $pre_service_notification = $post_data['pre_service_notification'];
            } else {
                $pre_service_notification = [];
            }

            $param = array(
                'user_id' => $user_id,
                'first_name' => $post_data['first_name'],
                'last_name' => $post_data['last_name'],
                'customer_company_name' => $post_data['customer_company_name'],
                'email' => $post_data['email'],
                'phone' => $post_data['phone'],
                'home_phone' => $post_data['home_phone'],
                'work_phone' => $post_data['work_phone'],
                'billing_street' => $post_data['billing_street'],
                'billing_street_2' => $post_data['billing_street_2'],
                'billing_city' => $post_data['billing_city'],
                'billing_state' => $post_data['billing_state'],
                'billing_zipcode' => $post_data['billing_zipcode'],
                'customer_status' => $post_data['customer_status'],
                'billing_type' => $post_data['billing_type'],
                'pre_service_notification' => json_encode($pre_service_notification)
            );


            if (isset($post_data['clover_autocharge'])) {
                $param['clover_autocharge'] = 1;
            } else {
                $param['clover_autocharge'] = 0;
            }

            if (isset($post_data['basys_autocharge'])) {
                $param['basys_autocharge'] = 1;
            } else {
                $param['basys_autocharge'] = 0;
            }

            if (isset($post_data['is_email'])) {
                $param['is_email'] = 1;
            } else {
                $param['is_email'] = 0;
            }

            if (isset($post_data['is_mobile_text'])) {
                $param['is_mobile_text'] = 1;
            } else {
                $param['is_mobile_text'] = 0;
            }
            #only update customer auto-send settings if company settings are set to send daily invoices
            if (isset($post_data['send_daily_invoice_mail']) && $post_data['send_daily_invoice_mail'] == 1) {
                if (isset($post_data['autosend_invoices'])) {
                    $param['autosend_invoices'] = 1;
                } else {
                    $param['autosend_invoices'] = 0;
                }
                if (isset($post_data['autosend_frequency'])) {
                    $param['autosend_frequency'] = $post_data['autosend_frequency'];
                }
            }

            // $where = array();
            // $check = $this->CustomerModel->checkEmailonUpdate($this->input->post('email'), $customerid);

            //  if($check == "true"){

            //     $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> Email-ID All ready exist.</div>');
            //     $this->editCustomer($customerid)

            //  }else{

            //print_r($post_data); die();

            $customer_details = $this->CustomerModel->getCustomerDetail($customerid);
            if ($customer_details['quickbook_customer_id'] != 0) {
                $res = $this->updatCustomerInQickbook($customer_details['quickbook_customer_id'], $param);
                if ($res['status'] == 404) {
                    $param['quickbook_customer_id'] = 0;
                }
            }

            $param['secondary_email'] = $post_data['secondary_email_list_hid'];

            $result = $this->CustomerModel->updateAdminTbl($customerid, $param);

            $where = array('customer_id' => $customerid);
            $delete = $this->CustomerModel->deleteAssignProperty($where);

            if (!empty($post_data['assign_property'])) {
                $count = 0;
                foreach ($post_data['assign_property'] as $value) {
                    $param2 = array(
                        'property_id' => $value,
                        'customer_id' => $customerid
                    );
                    $assign = $this->CustomerModel->assignProperty($param2);
                    $count++;
                }
            }

            // APPLY GLOBAL PERMANENT & ONE-TIME COUPONS HERE

            // get all active & unpaid invoices to potentially apply coupons to
            $whereArr = array(
                'is_archived' => 0,
                'invoice_tbl.customer_id' => $customerid,
                'payment_status !=' => 2
            );
            // WHERE NOT: all of the below true
            $whereArrExclude = array(
                "programs.program_price" => 2,
                // "technician_job_assign.is_complete" => 0,
                "technician_job_assign.is_complete !=" => 1,
                "technician_job_assign.is_complete IS NOT NULL" => NULL,
            );
            // WHERE NOT: all of the below true
            $whereArrExclude2 = array(
                "programs.program_price" => 2,
                "technician_job_assign.invoice_id IS NULL" => NULL,
                "invoice_tbl.report_id" => 0,
                "property_program_job_invoice2.report_id IS NULL" => NULL,
            );
            $all_customer_active_invoices = $this->INV->ajaxActiveInvoicesTech($whereArr, "", "", "", "", $whereArrExclude, $whereArrExclude2, "", false);


            // remove all coupon_customer
            $result_coupon_delete = $this->CouponModel->deleteAllCouponCustomer($customerid);
            $expiration_pass_global = true;

            if (!empty($post_data['assign_coupons'])) {

                // set new coupon_customer
                foreach ($post_data['assign_coupons'] as $coupon_id) {

                    $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

                    // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
                    $expiration_pass = true;
                    if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
                        $coupon_expiration_date = strtotime($coupon_details->expiration_date);

                        $now = time();
                        if ($coupon_expiration_date < $now) {
                            $expiration_pass = false;
                            $expiration_pass_global = false;
                        }
                    }

                    $param_coupon = array(
                        'customer_id' => $customerid,
                        'coupon_id' => $coupon_id,
                    );
                    $already_exists = $this->CouponModel->getOneCouponCustomer($param_coupon);
                    if (empty($already_exists) && $expiration_pass == true) {
                        $this->CouponModel->CreateOneCouponCustomer($param_coupon);
                    }
                }

                foreach ($all_customer_active_invoices as $invoice_details) {
                    foreach ($post_data['assign_coupons'] as $coupon_id) {

                        $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

                        $params = array(
                            'coupon_id' => $coupon_id,
                            'invoice_id' => $invoice_details->invoice_id,
                        );
                        $coupon_invoice_exists = $this->CouponModel->getOneCouponInvoice($params);

                        if (empty($coupon_invoice_exists)) {
                            $params = array(
                                'coupon_id' => $coupon_id,
                                'invoice_id' => $invoice_details->invoice_id,
                                'coupon_code' => $coupon_details->code,
                                'coupon_amount' => $coupon_details->amount,
                                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                'coupon_type' => $coupon_details->type
                            );
                            $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                        }
                    }
                }
            }

            if (!empty($post_data['assign_onetime_coupons'])) {

                foreach ($all_customer_active_invoices as $invoice_details) {
                    foreach ($post_data['assign_onetime_coupons'] as $coupon_id) {

                        $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));

                        $params = array(
                            'coupon_id' => $coupon_id,
                            'invoice_id' => $invoice_details->invoice_id,
                        );
                        $coupon_invoice_exists = $this->CouponModel->getOneCouponInvoice($params);

                        if (empty($coupon_invoice_exists)) {
                            $params = array(
                                'coupon_id' => $coupon_id,
                                'invoice_id' => $invoice_details->invoice_id,
                                'coupon_code' => $coupon_details->code,
                                'coupon_amount' => $coupon_details->amount,
                                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                'coupon_type' => $coupon_details->type
                            );
                            $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                        }
                    }
                }
            }

            // END GLOBAL PERMANENT & ONE-TIME COUPON SECTION
            if ($result) {
                #send email to customer if account status changed to hold
                $previous_customer_status = $customer_details['customer_status'];
                $customer_details = $this->CustomerModel->getCustomerDetail($customerid);
                $updated_customer_status = isset($customer_details['customer_status']) ? $customer_details['customer_status'] : NULL;
                if (isset($updated_customer_status) && $updated_customer_status == 2 && isset($previous_customer_status) && $previous_customer_status != 2) {
                    $first = isset($customer_details['first_name']) ? $customer_details['first_name'] : "";
                    $last = isset($customer_details['last_name']) ? $customer_details['last_name'] : "";
                    $customer_name = $first . " " . $last;

                    $data_company_email = $this->CompanyEmail->getOneCompanyEmail(array('company_id' => $this->session->userdata['company_id']));

                    $is_email_hold_templete = $data_company_email->is_email_hold_templete;
                    $email_array = [];
                    if ($is_email_hold_templete) {
                        $email_hold_template = $data_company_email->email_hold_templete;
                        //$hold_notification= $data_company_email->hold_notification;

                        $email_hold_template = str_replace("{CUSTOMER_NAME}", $customer_name, $email_hold_template);
                        $email_array['email_body_text'] = $email_hold_template;
                        if ($customer_details['email']) {
                            $customer = $this->CustomerModel->getOneCustomer(array('customer_id' => $customerid));
                            $email_array['customer_details'] = $customer;
                            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' => $this->session->userdata['company_id'], 'is_smtp' => 1));
                            if (!$company_email_details) {
                                $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                            }

                            $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                            $email_array['company_details'] = $company_details;
                            $subject = "Your Account is On Hold";
                            $to_email = $customer->email;
                            $body = $this->load->view('email/customer_hold_email', $email_array, TRUE);
                            $res = Send_Mail_dynamic($company_email_details, $to_email, array("name" => $company_details->company_name, "email" => $company_details->company_email), $body, $subject);
                        }
                    }
                    #send email to admin
                    $admin_email = [];
                    $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                    $admin_email['company_details'] = $company_details;
                    $user_details = $this->CompanyModel->getOneAdminUser(array('company_id' => $this->session->userdata['company_id'], 'role_id' => 1));
                    $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' => $this->session->userdata['company_id'], 'is_smtp' => 1));
                    if (!$company_email_details) {
                        $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                    }
                    $customer_names = [$customer_name]; //putting into array in order to fit existing email template
                    $admin_email['customer_names'] = $customer_names;
                    $body = $this->load->view('email/customer_hold_admin_email', $admin_email, TRUE);
                    if ($company_email_details) {
                        $res = Send_Mail_dynamic($company_email_details, $user_details->email, array("name" => $company_details->company_name, "email" => $company_details->company_email), $body, 'Customer Acount On Hold');
                    }
                }
                if (isset($updated_customer_status) && $updated_customer_status == 0) {
                    // since we are setting the customer as non-active we want to do the same to ALL of the their properties
                    $all_props = $this->PropertyModel->getAllCustomerPropertiesMarketing($customer_details["customer_id"]);
                    $all_props_ids = array();
                    foreach ($all_props as $ap) {
                        $all_props_ids[] = $ap->property_id;
                    }
                    $this->PropertyModel->setAllPropertiesNonActive($all_props_ids);
                }

            }

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("admin/customerList");
            } else {

                if ($expiration_pass_global == true) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> updated successfully</div>');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> updated successfully - a selected coupon has an expired date and was not added.</div>');
                }
                redirect("admin/customerList");
            }
        }

        //}
    }


    public function customerDelete($id)
    {

        $where = array('customer_id' => $id);
        $result = $this->CustomerModel->deleteCustomer($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("admin/customerList");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer </strong> deleted successfully</div>');
            redirect("admin/customerList");
        }
    }

    public function addAlert($customer_id)
    {
        // get data
        $post_data = $this->input->post();
        // format alert
        $prefix = "Alert : ";
        if ($post_data["alert_type"] == "Payment") {
            $prefix = "Payment " . $prefix;
        }
        $newAlert = array(
            'text' => $prefix . $post_data["alert_text"],
            'show_tech' => $post_data["show_tech"] == "on"
        );
        // if no property specified
        if ($post_data["property"] == "") {
            $customer_details = $this->CustomerModel->getCustomerDetail($customer_id);
            if (isset($customer_details["alerts"])) {
                $alerts = json_decode($customer_details["alerts"], true);
                $alerts[] = $newAlert;
            } else {
                $alerts = array(
                    $newAlert
                );
            }
            $params = array(
                'alerts' => json_encode($alerts)
            );
            $result = $this->CustomerModel->updateAdminTbl($customer_id, $params);
        } else {
            // if property specified
            // get existing notifications for that property
            $property_details = $this->PropertyModel->getPropertyDetail($post_data["property"]);
            if (isset($property_details["alerts"])) {
                $alerts = json_decode($property_details["alerts"], true);
                // add new notification to array of notifications
                $alerts[] = $newAlert;
            } else {
                $alerts = array(
                    $newAlert
                );
            }
            $params = array(
                'alerts' => json_encode($alerts)
            );
            // post array to property record
            $result = $this->PropertyModel->updateAdminTbl($post_data["property"], $params);
        }
        // send us back where we came from
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/customerList';
        redirect($referer_path);
    }

    public function removeCustomerAlert($param)
    {
        // params pattern is {alert_index}-{customer_id}
        $params = preg_split("/-/", $param);
        $index = $params[0];
        $customer_id = $params[1];
        // get existing customer notifications
        $customer_details = $this->CustomerModel->getCustomerDetail($customer_id);
        if (isset($customer_details["alerts"])) {
            $alerts = json_decode($customer_details["alerts"], true);
            array_splice($alerts, $index, 1);
            $params = array(
                'alerts' => json_encode($alerts)
            );
            $result = $this->CustomerModel->updateAdminTbl($customer_id, $params);
        }
        // send us back where we came from
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/customerList';
        redirect($referer_path);
    }
    /*///////////////////////  Customer Section End  ////////////////////  */

    /*//////////////////////  Property Section Start ///////////////////   */
    public function removePropertyAlert($param)
    {
        // params patter is {alert_index}-{customer_id}
        $params = preg_split("/-/", $param);
        $index = $params[0];
        $property_id = $params[1];
        // get existing property notifications
        $property_details = $this->PropertyModel->getPropertyDetail($property_id);
        if (isset($property_details["alerts"])) {
            $alerts = json_decode($property_details["alerts"], true);
            array_splice($alerts, $index, 1);
            $params = array(
                'alerts' => json_encode($alerts)
            );
            $result = $this->PropertyModel->updateAdminTbl($property_id, $params);
        }
        // send us back where we came from
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';
        redirect($referer_path);
    }

    public function propertyList()
    {
        $company_id = $this->session->userdata['company_id'];
        // $where =  array('property_tbl.company_id' => $company_id);

        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

        /*
        $data['properties'] = $this->PropertyModel->get_all_list_properties(array('property_tbl.company_id' => $company_id));

        if (!empty($data['properties'])) {
            foreach ($data['properties'] as $key => $value) {

                $data['properties'][$key]->customer_id =  $this->PropertyModel->getAllcustomer(array('property_id' => $value->property_id));
            }

            foreach ($data['properties'] as $key => $value) {

                $data['properties'][$key]->program_id =  $this->PropertyModel->getAllprogram(array('property_id' => $value->property_id));
            }
        }
        */

        $where = array('company_id' => $company_id);
        $data['programlist'] = $this->PropertyModel->getProgramList(array('company_id' => $company_id, 'program_active' => 1));
        $data['service_areas'] = $this->ServiceArea->getAllServiceArea(['company_id' => $this->session->userdata['company_id']]);
        $data['polygon_bounds'] = [];
        foreach ($data['service_areas'] as $k => $v) {
            if ($v->service_area_polygon)
                $data['polygon_bounds'][] = ["latlng" => $v->service_area_polygon, "marker" => $v->category_area_name, "property_area_cat_id" => $v->property_area_cat_id];
        }

        $data['servicelist'] = $this->JobModel->getJobList($where);

        $data['cancel_reasons'] = $this->CustomerModel->getCancelReasons($company_id);

        $page["active_sidebar"] = "properties";
        $page["page_name"] = "Properties";
        $page["page_content"] = $this->load->view("admin/property_view", $data, TRUE);
        //auto status update
//         $this->PropertyModel->autoStatusCheck();

//        $this->CustomerModel->autoStatusCheck(0,$this->session->userdata['company_id']);

        $this->layout->superAdminTemplateTable($page);

    }

    public function assignProgramToProperies($value = '')
    {
        $data = $this->input->post();

        $company_id = $this->session->userdata['company_id'];
        $where = array('company_id' => $company_id);
        $user_id = $this->session->userdata['user_id'];

        $program = array();

        $setting_details = $this->CompanyModel->getOneCompany($where);
        $prog_details = $this->ProgramModel->getProgramDetail($data['program_id']);
        $jobs = $this->ProgramModel->getSelectedJobs($data['program_id']);

        if (!empty($data['property_ids'])) {
            foreach ($data['property_ids'] as $key => $value) {
                $param = array(
                    'property_id' => $value,
                    'program_id' => $data['program_id'],
                );

		$program['properties'] = array();
		$check = $this->PropertyModel->getOnePropertyProgram($param);

        $customer_details_by_prop = $this->CustomerModel->getAllCustomerByPropert(array('property_id'=>$value));

        $property_details_webhook =  $this->PropertyModel->getOneProperty(array('property_id'=>$data['property_ids'][0]));
        
        
        //die(print_r($property_details_webhook));

        //webhook_trigger
        $user_info = $this->Administrator->getOneAdmin(array("user_id" => $this->session->userdata('user_id')));
        if($user_info->webhook_program_assigned){
            $this->load->model('api/Webhook');
            $webhook_data = ['property_id' => $property_details_webhook->property_id, 'property_name' => $property_details_webhook->property_title, 'property_address' => $property_details_webhook->property_address, 'property_square_footage' => $property_details_webhook->yard_square_feet, 'program_id' => $data['program_id'], 'program_name' => $prog_details['program_name'], 'property_name'=>$property_details_webhook->property_title, 'customer_email' =>  $customer_details_by_prop[0]->email, 'customer_name' =>  $customer_details_by_prop[0]->first_name . " " . $customer_details_by_prop[0]->last_name, 'address' => $customer_details_by_prop[0]->billing_street . " " . $customer_details_by_prop[0]->billing_city . ", " . $customer_details_by_prop[0]->billing_state . " " . $customer_details_by_prop[0]->billing_zipcode, 'phone' => $customer_details_by_prop[0]->phone];
            //die(print_r($webhook_data));
            $response = $this->Webhook->callTrigger($user_info->webhook_program_assigned, $webhook_data);
        }


			  if(!$check){
				  $result = $this->PropertyModel->assignProgram($param);
		  if($result){
			##email/text notifications
			$property_details = $this->PropertyModel->getOneProperty(array('property_id'=>$value));
			$customer_details = $this->CustomerModel->getOnecustomerPropert(array('property_id'=>$value));
			  
			##check customer billing type
			$checkGroupBilling = $this->CustomerModel->checkGroupBilling($customer_details->customer_id);
			#if customer billing type = group billing, then we notify the property level contact info
			if($checkGroupBilling){
				$emaildata['contactData'] = $this->PropertyModel->getGroupBillingByProperty($value);
				$emaildata['propertyData'] = $property_details;
				$emaildata['programData'] = $this->ProgramModel->getProgramDetail($data['program_id']);
				$emaildata['assign_date'] = date("Y-m-d H:i:s");
				$emaildata['company_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
				$emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id' => $this->session->userdata['company_id']));
				$company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' => $this->session->userdata['company_id'],'is_smtp'=>1));
				$body = $this->load->view('email/group_billing/program_email', $emaildata, true);
				if(!$company_email_details){
					$company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
				}
				#send email
				if (isset($emaildata['company_email_details']->program_assigned_status) && $emaildata['company_email_details']->program_assigned_status == 1 && isset($emaildata['contactData']['email_opt_in']) && $emaildata['contactData']['email_opt_in'] == 1) {
					$sendEmail = Send_Mail_dynamic($company_email_details, $emaildata['contactData']['email'], array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Program Assigned');
				}
				#send text
				if(isset($emaildata['company_details']->is_text_message) && $emaildata['company_details']->is_text_message == 1 && isset($emaildata['company_email_details']->program_assigned_status_text) && $emaildata['company_email_details']->program_assigned_status_text == 1 && isset($emaildata['contactData']['phone_opt_in']) && $emaildata['contactData']['phone_opt_in'] == 1){
					$sendText = Send_Text_dynamic($emaildata['contactData']['phone'], $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
				}
			}else{
				$emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id'=>$customer_details->customer_id));
				$emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id'=>$customer_details->customer_id,'is_email'=>1, 'program_id'=>$data['program_id'],'property_id'=>$value));
				$emaildata['company_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
				$emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id'=>$this->session->userdata['company_id']));
				$emaildata['assign_date'] = date("Y-m-d H:i:s");
				$company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id'=>$this->session->userdata['company_id'],'is_smtp'=>1));
				$body  = $this->load->view('email/program_email', $emaildata, true);
				if(!$company_email_details){
							$company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
						  }
				#check if company setting for this notification are turned on AND check if customer is subscribed to email notifications
				if($emaildata['company_email_details']->program_assigned_status == 1 && isset($emaildata['customerData']->is_email) && $emaildata['customerData']->is_email ==1){
				  $sendEmail = Send_Mail_dynamic($company_email_details, $emaildata['customerData']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Program Assigned', $emaildata['customerData']->secondary_email);
				}
				#check if company has text message notifications and if setting for this notification are turned on AND check if customer is subscribed to text notifications
				if(isset($emaildata['company_details']->is_text_message) && $emaildata['company_details']->is_text_message == 1 && isset($emaildata['company_email_details']->program_assigned_status_text) && $emaildata['company_email_details']->program_assigned_status_text == 1 && isset($emaildata['customerData']->is_mobile_text) && $emaildata['customerData']->is_mobile_text == 1){
				  $sendText = Send_Text_dynamic($emaildata['customerData']->phone, $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
				}
			}
		  }
		  $program['properties'][$value] = array(
			'program_property_id' => $result,
		  );

                    // Generate Invoice if One-Time Invoicing Program
                    if ($prog_details['program_price'] == 1) {

                        //create jobs array
                        $ppjobinv = array();

                        //get customer property details
                        $customer_property_details = $this->CustomerModel->getAllProperty(array('customer_property_assign.property_id' => $value));

                        if ($customer_property_details) {
                            $QBO_description = array();
                            $actual_description_for_QBO = array();
                            $QBO_cost = 0;
                            foreach ($customer_property_details as $key2 => $value2) {

                                //get customer info
                                $cust_details = getOneCustomerInfo(array('customer_id' => $value2->customer_id));

                                $total_cost = 0;
                                $description = "";
                                $est_cost = 0;


                                // foreach program property job... calculate job cost
                                foreach ($jobs as $key3 => $value3) {
                                    $job_id = $value3->job_id;

                                    $job_details = $this->JobModel->getOneJob(array('job_id' => $job_id));

                                    $description = $job_details->job_name . " ";

                                    $QBO_description[] = $job_details->job_name;
                                    $actual_description_for_QBO[] = $job_details->job_description;

                                    $where2 = array(
                                        'property_id' => $value,
                                        'job_id' => $job_id,
                                        'program_id' => $data['program_id'],
                                        'customer_id' => $value2->customer_id
                                    );

                                    //CALCULATE JOB COST

                                    //check for price overrides
                                    $estimate_price_override = GetOneEstimateJobPriceOverride($where2);
                                    if ($estimate_price_override) {
                                        $cost = $estimate_price_override->price_override;

                                        $est_coup_param = array(
                                            'cost' => $cost,
                                            'estimate_id' => $estimate_price_override->estimate_id
                                        );

                                        $est_cost = $this->calculateEstimateCouponCost($est_coup_param);

                                    } else {
                                        $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $value, 'program_id' => $data['program_id']));

                                        if ($priceOverrideData && $priceOverrideData->is_price_override_set == 1) {
                                            $cost = $priceOverrideData->price_override;
                                        } else {
                                            //else no price overrides, then calculate job cost
                                            $lawn_sqf = $value2->yard_square_feet;
                                            $job_price = $job_details->job_price;

                                            //get property difficulty level
                                            if (isset($value2->difficulty_level) && $value2->difficulty_level == 2) {
                                                $difficulty_multiplier = $setting_details->dlmult_2;
                                            } elseif (isset($value2->difficulty_level) && $value2->difficulty_level == 3) {
                                                $difficulty_multiplier = $setting_details->dlmult_3;
                                            } else {
                                                $difficulty_multiplier = $setting_details->dlmult_1;
                                            }

                                            //get base fee
                                            if (isset($job_details->base_fee_override)) {
                                                $base_fee = $job_details->base_fee_override;
                                            } else {
                                                $base_fee = $setting_details->base_service_fee;
                                            }

                                            $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                            //get min. service fee
                                            if (isset($job_details->min_fee_override)) {
                                                $min_fee = $job_details->min_fee_override;
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
                                    $total_cost += $cost;
                                    $ppjobinv[] = array(
                                        'customer_id' => $value2->customer_id,
                                        'property_id' => $value,
                                        'program_id' => $data['program_id'],
                                        'job_id' => $job_id,
                                        'cost' => $cost,
                                    );

                                    if ($est_cost != 0) {
                                        $job_coup_param = array(
                                            'customer_id' => $value2->customer_id,
                                            'property_id' => $value,
                                            'program_id' => $data['program_id'],
                                            'cost' => $est_cost,
                                            'job_id' => $job_id
                                        );

                                        $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                    } else {
                                        $job_coup_param = array(
                                            'customer_id' => $value2->customer_id,
                                            'property_id' => $value,
                                            'program_id' => $data['program_id'],
                                            'cost' => $cost,
                                            'job_id' => $job_id
                                        );

                                        $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                    }
                                }

                                //format invoice data
                                $param = array(
                                    'customer_id' => $value2->customer_id,
                                    'property_id' => $value,
                                    'program_id' => $data['program_id'],
                                    'user_id' => $user_id,
                                    'company_id' => $company_id,
                                    'invoice_date' => date("Y-m-d"),
                                    'description' => $prog_details['program_notes'],
                                    'cost' => ($total_cost),
                                    'is_created' => 2,
                                    'invoice_created' => date("Y-m-d H:i:s"),
                                );
                                //create invoice
                                $invoice_id = $this->INV->createOneInvoice($param);

                                //if invoice id
                                if ($invoice_id) {
                                    $param['invoice_id'] = $invoice_id;
                                    //figure tax
                                    if ($setting_details->is_sales_tax == 1) {
                                        $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $value));
                                        if ($property_assign_tax) {
                                            foreach ($property_assign_tax as $tax_details) {
                                                $invoice_tax_details = array(
                                                    'invoice_id' => $invoice_id,
                                                    'tax_name' => $tax_details['tax_name'],
                                                    'tax_value' => $tax_details['tax_value'],
                                                    'tax_amount' => $total_cost * $tax_details['tax_value'] / 100
                                                );

                                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                            }
                                        }
                                    }

                                    //Quickbooks Invoice **

                                    $param['customer_email'] = $cust_details['email'];
                                    $param['job_name'] = $description;

                                    $QBO_description = implode(', ', $QBO_description);
                                    $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);
                                    $QBO_param = $param;
                                    $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
                                    $QBO_param['job_name'] = $QBO_description;


                                    $cust_coup_param = array(
                                        'cost' => $QBO_cost,
                                        'customer_id' => $QBO_param['customer_id']
                                    );

                                    $QBO_param['cost'] = $this->calculateCustomerCouponCost($cust_coup_param);

                                    $quickbook_invoice_id = $this->QuickBookInv($QBO_param);
                                    //if quickbooks invoice then update invoice table with id
                                    if ($quickbook_invoice_id) {
                                        $invoice_id = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('quickbook_invoice_id' => $quickbook_invoice_id));
                                    }

                                    foreach ($program['properties'] as $propID => $prop) {
                                        if ($propID == $value) {
                                            foreach ($ppjobinv as $i => $job) {
                                                //		echo "Property Program ID: ".$prop['program_property_id']."</br>";
                                                //		echo "Job ID: ".$job['job_id']."</br>";
                                                //		echo "Invoice ID: ".$invoice_id."</br>";
                                                //	echo "---------<br>";
                                                //store property program job invoice data
                                                $newPPJOBINV = array(
                                                    'customer_id' => $job['customer_id'],
                                                    'property_id' => $job['property_id'],
                                                    'program_id' => $job['program_id'],
                                                    'property_program_id' => $prop['program_property_id'],
                                                    'job_id' => $job['job_id'],
                                                    'invoice_id' => $invoice_id,
                                                    'job_cost' => $job['cost'],
                                                    'created_at' => date("Y-m-d"),
                                                    'updated_at' => date("Y-m-d"),
                                                );

                                                $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);
                                            }
                                        }
                                    }

                                    // assign coupon if global customer coupon exists
                                    $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $value2->customer_id));
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
                                                $params = array(
                                                    'coupon_id' => $coupon_id,
                                                    'invoice_id' => $invoice_id,
                                                    'coupon_code' => $coupon_details->code,
                                                    'coupon_amount' => $coupon_details->amount,
                                                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                                    'coupon_type' => $coupon_details->type
                                                );
                                                $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                                            }
                                        }
                                    }
                                } //end if invoice
                            } //end foreach customer property
                        }
                    }
                }
            }

            $return_array = array('status' => 200, 'msg' => "assigned successfully");
        } else {
            $return_array = array('status' => 400, 'msg' => "Property empty");
        }
        echo json_encode($return_array);
    }

    public function addProperty($customer_id = 0, $opt = 0)
    {


        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['customer_id'] = $customer_id;
        $data['opt'] = $opt;
        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList($where);
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
        $data['taglist'] = $this->PropertyModel->getTagsList($where);
        $data['source_list'] = $this->SourceModel->getAllSource(array('company_id' => $this->session->userdata['company_id']));
        $data['users'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
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
        $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
        $data['customerData'] = $this->CustomerModel->getCustomerDetail($customer_id);

        if (isset($data['customerData']))
            $data['customer_name'] = $data['customerData']['first_name'] . " " . $data['customerData']['last_name'];
        else
            $data['customer_name'] = '';
//		die(print_r($data['customerlist']));
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $data['propertyconditionslist'] = $this->PropertyModel->getCompanyPropertyConditions(array('company_id' => $this->session->userdata['company_id']));
        $page["active_sidebar"] = "properties";
        $page["page_name"] = "Add Property";
        $data['service_areas'] = $this->ServiceArea->getAllServiceArea(['company_id' => $this->session->userdata['company_id']]);
        $data['polygon_bounds'] = [];
        foreach ($data['service_areas'] as $k => $v) {
            if ($v->service_area_polygon)
                $data['polygon_bounds'][] = ["latlng" => $v->service_area_polygon, "marker" => $v->category_area_name, "property_area_cat_id" => $v->property_area_cat_id];
        }
        $page["page_content"] = $this->load->view("admin/add_property", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }


    public function getServiceAreaOption()
    {
        $where = array('company_id' => $this->session->userdata['company_id']);

        $data = $this->ServiceArea->getAllServiceArea($where);

        echo '<option value="">Select Area</option>';

        if ($data) {
            foreach ($data as $key => $value) {
                echo '<option value="' . $value->property_area_cat_id . '">' . $value->category_area_name . '</option>';
            }
        }
    }

    public function addPropertyCsv()
    {


        $filename = $_FILES["csv_file"]["tmp_name"];

        if ($_FILES["csv_file"]["size"] > 0) {

            $row = 1;
            if (($handle = fopen($filename, "r")) !== FALSE) {

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($row == 1) {
                        $row++;
                        continue;
                    }

                    $param = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'company_id' => $this->session->userdata('company_id'),
                        'property_title' => $data[0],
                        'property_address' => $data[1],
                        'property_address_2' => $data[2],
                        'property_city' => $data[3],
                        'property_state' => $data[4],
                        'property_zip' => $data[5],
                        'property_area' => $data[6],
                        'property_type' => $data[7],
                        'yard_square_feet' => $data[8],
                        'property_notes' => $data[9],

                    );
                    $sales_tax_param = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'company_id' => $this->session->userdata('company_id'),
                        'tax_name' => $data[10],
                        'tax_value' => $data[11],
                    );


                    $param2 = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'company_id' => $this->session->userdata('company_id'),
                        'program_name' => $data[12],
                        'program_price' => $data[13],
                        'program_notes' => $data[14],
                    );


                    $param = array_filter($param);
                    $sales_tax_param = array_filter($sales_tax_param);
                    $param2 = array_filter($param2);


                    if (array_key_exists("property_title", $param) && array_key_exists("property_address", $param) && array_key_exists("property_city", $param) && array_key_exists("property_state", $param) && array_key_exists("property_zip", $param) && array_key_exists("property_type", $param) && array_key_exists("yard_square_feet", $param)) {


                        if ($param['property_type'] == 1) {
                            $param['property_type'] = 'Commercial';
                        } elseif ($param['property_type'] == 2) {
                            $param['property_type'] = 'Residential';
                        } else {
                            $param['property_type'] = 'Commercial';
                        }

                        if (isset($param['property_area']) && $param['property_area'] != '') {

                            $checkarea = $this->ServiceArea->getOneServiceArea(array('company_id' => $param['company_id'], 'category_area_name' => $param['property_area']));

                            if ($checkarea) {
                                $area = $checkarea->property_area_cat_id;
                                $param['property_area'] = $area;
                            } else {
                                $area = $this->ServiceArea->CreateOneServiceArea(array('company_id' => $param['company_id'], 'user_id' => $param['user_id'], 'category_area_name' => $param['property_area']));

                                $param['property_area'] = $area;
                            }
                        }

                        $sale_tax_area_id = $this->salesTaxManageForProperty($sales_tax_param);

                        $check = $this->PropertyModel->getOneProperty($param);
                        $program_id = $this->programAddByProperty($param2);


                        if (!$check) {

                            $geo = $this->getLatLongByAddress($param['property_address']);

                            if ($geo) {

                                $param['property_latitude'] = $geo['lat'];
                                $param['property_longitude'] = $geo['long'];

                                $result = $this->PropertyModel->insert_property($param);


                                if ($sale_tax_area_id > 0) {

                                    $where_pr_tax = array(
                                        'property_id' => $result,
                                        'property_sales_tax.sale_tax_area_id' => $sale_tax_area_id
                                    );
                                    $ch_pr_tax = $this->PropertySalesTax->getOnePropertySalesTax($where_pr_tax);

                                    if (!$ch_pr_tax) {

                                        $pr_tax_ar = array(
                                            'property_id' => $result,
                                            'sale_tax_area_id' => $sale_tax_area_id
                                        );

                                        $this->PropertySalesTax->CreateOnePropertySalesTax($pr_tax_ar);
                                    }
                                }


                                if ($program_id) {

                                    $param3 = array(
                                        'program_id' => $program_id,
                                        'property_id' => $result,
                                    );

                                    $already = $this->PropertyModel->getOnePropertyProgram($param3);
                                    if (!$already) {

                                        $param3['price_override'] = $data[15];
                                        $this->PropertyModel->assignProgram($param3);
                                    }
                                }

                                //echo $result;
                            }
                        } else {


                            if ($sale_tax_area_id > 0) {

                                $where_pr_tax = array(
                                    'property_id' => $check->property_id,
                                    'property_sales_tax.sale_tax_area_id' => $sale_tax_area_id
                                );
                                $ch_pr_tax = $this->PropertySalesTax->getOnePropertySalesTax($where_pr_tax);
                                if (!$ch_pr_tax) {

                                    $pr_tax_ar = array(
                                        'property_id' => $check->property_id,
                                        'sale_tax_area_id' => $sale_tax_area_id
                                    );
                                    $this->PropertySalesTax->CreateOnePropertySalesTax($pr_tax_ar);
                                }
                            }


                            if ($program_id) {

                                $param3 = array(
                                    'program_id' => $program_id,
                                    'property_id' => $check->property_id,
                                );

                                $already = $this->PropertyModel->getOnePropertyProgram($param3);
                                if (!$already) {

                                    $param3['price_override'] = $data[15];

                                    $this->PropertyModel->assignProgram($param3);
                                }
                            }
                        }
                    }
                }
                fclose($handle);

                if (isset($check) && !isset($result)) {
                    echo 0;
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> already exists.</div>');
                    //echo "already he add nahi";
                } else if (isset($check) && isset($result)) {
                    echo 1;
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> added successfully</div>');
                    //echo "already nahi result he";
                } else if (isset($check) && isset($result)) {
                    echo 3;
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Some Property </strong> already exists and some added</div>');
                } else if (isset($result)) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> added successfully</div>');
                } else {
                    echo 4;
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Please check your csv file.</strong> This file is not valid.</div>');
                    //echo "swr";
                }
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong> file</strong> can not read please check file.</div>');
            }
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong> Do</strong> not select black file.</div>');
        }


        redirect("admin/propertyList");
    }

    /**
     * Create/Update record in sale_tax_area table based on provided argument data and filter criteria.
     * @param array $sales_tax_param
     * @return int  $sale_tax_area_id;
     *  */
    public function salesTaxManageForProperty($sales_tax_param)
    {
        $sale_tax_area_id = 0;
        if (isset($sales_tax_param['tax_name']) && $sales_tax_param['tax_name'] != '' && isset($sales_tax_param['tax_value']) && $sales_tax_param['tax_value'] != '') {
            $sales_tax_where = array(
                'company_id' => $sales_tax_param['company_id'],
                'tax_name' => $sales_tax_param['tax_name']
            );
            $sales_tax_info = $this->SalesTax->getOneSalesTaxArea($sales_tax_param);
            if ($sales_tax_info) {
                // If already sales tax update sales tax information
                $csv_sales_tax_update_param = array();
                $csv_sales_tax_update_param['tax_value'] = (isset($sales_tax_param['tax_value']) && $sales_tax_info->tax_value != $sales_tax_param['tax_value']) ? $sales_tax_param['tax_value'] : '';
                $csv_sales_tax_update_param = array_filter($csv_sales_tax_update_param);
                $csv_sales_tax_update_where = array('sale_tax_area_id' => $sales_tax_info->sale_tax_area_id);
                if (count($csv_sales_tax_update_param) > 0) {
                    // If csv row has any column value change)
                    $this->SalesTax->updateSalesTaxData($csv_sales_tax_update_param, $csv_sales_tax_update_where);
                }
                $sale_tax_area_id = $sales_tax_info->sale_tax_area_id;
                return $sale_tax_area_id;
            } else {
                $sales_tax_param['created_at'] = Date("Y-m-d H:i:s");
                $sale_tax_area_id = $this->SalesTax->CreateOneSalesTaxArea($sales_tax_param);
                return $sale_tax_area_id;
            }
        }
        return $sale_tax_area_id;
    }


    public function programAddByProperty($program_param)
    {
        if (array_key_exists("program_name", $program_param) && array_key_exists("program_price", $program_param)) {
            $program_param_where = array('company_id' => $program_param['company_id'], 'program_name' => $program_param['program_name']);
            $program_info = $this->ProgramModel->getOneProgramForCheck($program_param_where);
            if (!$program_info) {
                unset($program_param['price_override']);
                $result = $this->ProgramModel->insert_program($program_param);
                return $result;
            } else {
                // If already program update program information
                $csv_program_update_param = array();
                $csv_program_update_param['program_notes'] = (isset($program_param['program_notes']) && $program_info->program_notes != $program_param['program_notes']) ? $program_param['program_notes'] : '';
                $csv_program_update_param['program_job'] = (isset($program_param['program_job']) && $program_info->program_job != $program_param['program_job']) ? $program_param['program_job'] : '';
                $csv_program_update_param['program_price'] = (isset($program_param['program_price']) && $program_info->program_price != $program_param['program_price']) ? $program_param['program_price'] : '';
                $csv_program_update_param = array_filter($csv_program_update_param);
                $csv_program_update_where = array('program_id' => $program_info->program_id);
                if (count($csv_program_update_param) > 0) {
                    // If csv row has any column value changed.
                    $this->ProgramModel->updateProgramData($csv_program_update_param, $csv_program_update_where);
                }
                return $program_info->program_id;
            }
        } else {
            return false;
        }
    }

    public function addPropertyData($customer_id = 0)
    {

        $data = $this->input->post();
        $user_id = $this->session->userdata['user_id'];
        $company_id = $this->session->userdata['company_id'];
        // die(print_r($data));
        $this->form_validation->set_rules('property_title', 'Property Title', 'required');
        $this->form_validation->set_rules('property_address', 'Address', 'required');
        $this->form_validation->set_rules('property_address_2', 'Address 2', 'trim');
        $this->form_validation->set_rules('property_city', 'City', 'required');
        $this->form_validation->set_rules('property_state', 'State', 'required');
        $this->form_validation->set_rules('property_zip', 'Zipcode', 'required');
        $this->form_validation->set_rules('property_area', 'Area', 'trim');
        $this->form_validation->set_rules('property_type', 'Property Type', 'required');
        if ($data['property_status'] != 2) {
            $this->form_validation->set_rules('yard_square_feet', 'Squre Feet', 'required');
        }
        if ($data['property_status'] == 2) {
            $this->form_validation->set_rules('source', 'Source', 'required');
        }
        $this->form_validation->set_rules('property_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('assign_program[]', 'Assign Program', 'trim');
        $this->form_validation->set_rules('tags[]', 'Assign Tags');
        $this->form_validation->set_rules('total_yard_grass', 'Select Yard\'s Grass Type', 'trim');
        $this->form_validation->set_rules('front_yard_square_feet', 'Front Yard Square Feet', 'trim');
        $this->form_validation->set_rules('back_yard_square_feet', 'Back Yard Square Feet', 'trim');
        $this->form_validation->set_rules('front_yard_grass', 'Select Front Yard\'s Grass Type', 'trim');
        $this->form_validation->set_rules('back_yard_grass', 'Select Back Yard\'s Grass Type', 'trim');
        $this->form_validation->set_rules('measure_map_project_id', 'Measure Map ID', 'trim');

        // TODO check for duplicates
        $where_arr = array(
            'company_id' => $company_id,
            'property_address' => $data['property_address'],
            'property_city' => $data['property_city'],
            'property_state' => $data['property_state'],
            'property_zip' => $data['property_zip']
        );

        $property_lookup = $this->PropertyModel->getOneProperty($where_arr);


        if (($this->form_validation->run() == FALSE || !empty($property_lookup)) && $data['confirmation'] == 0) {
            if (!empty($property_lookup)) {
                $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000">Property<strong> already exists </strong>  Are you sure you want to add it?
                <button id="confirmation-button" class="btn btn-success">Next <i class="icon-arrow-right14 position-right"></i></button></div>');
            }
            $this->addProperty($customer_id, 1);
        } else {

            $tags = "";
            if (isset($data['tags'])) {
                $tags = implode(',', $data['tags']);
            }
            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'property_title' => $data['property_title'],
                'property_address' => $data['property_address'],
                'property_address_2' => $data['property_address_2'],
                'property_city' => $data['property_city'],
                'property_state' => $data['property_state'],
                'property_zip' => $data['property_zip'],
                'property_area' => $data['property_area'],
                'property_type' => $data['property_type'],
                'yard_square_feet' => $data['yard_square_feet'],
                'property_notes' => $data['property_notes'],
                'property_status' => $data['property_status'],
                'total_yard_grass' => $data['total_yard_grass'],
                'front_yard_square_feet' => $data['front_yard_square_feet'],
                'back_yard_square_feet' => $data['back_yard_square_feet'],
                'measure_map_project_id' => $data['measure_map_project_id'],
                'tags' => $tags,
            );

            if (isset($data['source']) && !empty($data['source'])) {
                $param['source'] = $data['source'];
            }
            if (isset($data['tags_title']) && !empty($data['tags_title'])) {
                $param['tags_title'] = $data['tags_title'];
            }
            if (isset($data['front_yard_grass']) && !empty($data['front_yard_grass'])) {
                $param['front_yard_grass'] = $data['front_yard_grass'];
            }
            if (isset($data['back_yard_grass']) && !empty($data['back_yard_grass'])) {
                $param['back_yard_grass'] = $data['back_yard_grass'];
            }
            if (isset($data['measure_map_project_id']) && !empty($data['measure_map_project_id'])) {
                $param['measure_map_project_id'] = $data['measure_map_project_id'];
            }
            if (isset($data['difficulty_level']) && !empty($data['difficulty_level'])) {
                $param['difficulty_level'] = $data['difficulty_level'];
            } else {
                $param['difficulty_level'] = 1;
            }
            $geo = $this->getLatLongByAddress($data['property_address']);

            // Determine Available Days
            $param['available_days'] = json_encode(determineAvailableDays($data));

            if (!$geo) {

                //    echo "0inva";

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">
                <strong>Error: </strong> Address not validated. Please choose a validated address from the dropdown or enter Latitude/Longitude coordinates</div>');

                $this->addProperty($data['customer_id']);
            } else {

                $param['property_latitude'] = $geo['lat'];

                $param['property_longitude'] = $geo['long'];
                $check = $this->PropertyModel->checkProperty($param);

                if ($check == "true") {
                    //   echo "0already";

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong>  Already exists.</div>');

                    $this->addProperty($data['customer_id']);
                } else {


                    $result1 = $this->PropertyModel->insert_property($param);

                    if ($result1) {
                        if (isset($data['property_conditions']) && is_array($data['property_conditions']) && !empty($data['property_conditions'])) {
                            #assign property conditions
                            foreach ($data['property_conditions'] as $condition) {
                                $handleAssignConditions = $this->PropertyModel->assignPropertyCondition(array('property_id' => $result1, 'property_condition_id' => $condition));
                            }
                        }
                    }

                    if (isset($data['assign_customer']) && !empty($data['assign_customer'])) {

                        foreach ($data['assign_customer'] as $value) {

                            $param2 = array(
                                'property_id' => $result1,
                                'customer_id' => $value

                            );
                            $result = $this->PropertyModel->assignCustomer($param2);
                        }
                    }

                    //webhook_trigger
                    $user_info = $this->Administrator->getOneAdmin(array("user_id" => $this->session->userdata('user_id')));
                    if($user_info->webhook_property_created){
                        $this->load->model('api/Webhook');
                        

                        //$dataProperty = $this->PropertyModel->getOnePropertyDetail($result1);
                        
                        $customer_id = $this->PropertyModel->getSelectedCustomer($result1);
                        //die(print_r($customer_id));
                        $customer_id_arr = array(
                            'customer_id' => $customer_id[0]->customer_id
                        );
                        $customer = $this->CustomerModel->getOneCustomer($customer_id_arr);
                        
                        $webhook_data = ['Property ID' => $result1, 'Customer Email'=>$customer->email, 'Property Name'=>$param['property_title'], 'Service Area'=>$param['property_area'], 'Property Address'=>$param['property_address'], 'Latitude'=>$param['property_latitude'], 'Longitude'=>$param['property_longitude'], 'Yard Square Feet'=>$param['yard_square_feet'], 'Grass Type'=>$param['total_yard_grass'] ];
                        
                        //die(print_r($webhook_data));
                        $response = $this->Webhook->callTrigger($user_info->webhook_property_created, $webhook_data);


                    }

                    //if tags then webhook
                    //webhook_trigger
                    if(!empty($param['tags'])){
                        
                        $user_info = $this->Administrator->getOneAdmin(array("user_id" => $this->session->userdata('user_id')));
                        // foreach($param['tags'] as $currTags){
                            // if (!$this->input->post('tags[]') == $currTags){
                                if($user_info->webhook_tag_created){
                                    $this->load->model('api/Webhook');
                                    $response = $this->Webhook->callTrigger($user_info->webhook_tag_created,  $result = ['property_id' => $result1, 'tags' =>  $param['tags']]);
                                }
                            // }
                        // }
                        
                    }

                    if($result1){
                        if(isset($data['property_conditions']) && is_array($data['property_conditions']) && !empty($data['property_conditions'])){
                            #assign property conditions
                            foreach($data['property_conditions'] as $condition){
                                $handleAssignConditions = $this->PropertyModel->assignPropertyCondition(array('property_id'=>$result1,'property_condition_id'=>$condition));
                            }
                        }
                    }

                    ##### ASSIGN SALE VISIT SERVICE (RG) #####
                    $salesVisit = $this->ProgramModel->get_all_program(array('program_name' => 'Sales Visit Standalone', 'company_id' => $company_id));
                    // die(print_r($salesVisit));
                    $salesVStandalone = $this->JobModel->getAllJob(array('job_name' => 'Sales Visit Standalone', 'jobs.company_id' => $company_id));
                    // die(print_r($salesVisit[0]->program_id));
                    // die(print_r($salesVStandalone));

                    if (isset($data['property_status']) && $data['property_status'] == 2) {
                        $prospect = array(
                            'property_id' => $result1,
                            'program_id' => $salesVisit[0]->program_id,
                        );
                        // THIS LINE COMMENTED OUT ON 4/28/23 AS PER A BASECAMP FROM BRIAN (https://basecamp.com/2362279/projects/17952987/messages/103539042)
                        //$result = $this->PropertyModel->assignProgram($prospect);
                        // die(print_r($result));
                    }
                    ####
                    if (isset($result1) && isset($data['is_group_billing']) && $data['is_group_billing']) {
                        if (isset($data['property_is_email']) && $data['property_is_email'] == 'on') {
                            $email_opt_in = 1;
                        } else {
                            $email_opt_in = 0;
                        }

                        if (isset($data['property_is_text']) && $data['property_is_text'] == 'on') {
                            $phone_opt_in = 1;
                        } else {
                            $phone_opt_in = 0;
                        }
                        $group_billing_params = array(
                            'property_id' => $result1,
                            'first_name' => $data['property_first_name'],
                            'last_name' => $data['property_last_name'],
                            'email' => $data['property_email'],
                            'secondary_email' => isset($data['secondary_email']) && !empty($data['secondary_email']) ? $data['secondary_email'] : '',
                            'email_opt_in' => $email_opt_in,
                            'phone' => $data['property_phone'],
                            'secondary_phone' => isset($data['secondary_phone']) && !empty($data['secondary_phone']) ? $data['secondary_phone'] : '',
                            'phone_opt_in' => $phone_opt_in,
                        );
                        $assignGroupBilling = $this->PropertyModel->assignGroupBilling($group_billing_params);
                    }
                    if (isset($data['assign_program']) && !empty($data['assign_program'])) {

                        foreach (json_decode($data['assign_program']) as $value) {

                            $param3 = array(
                                'property_id' => $result1,
                                'program_id' => $value->program_id,
                                'price_override' => $value->price_override,
                                'is_price_override_set' => $value->is_price_override_set
                            );

                            $result = $this->PropertyModel->assignProgram($param3);
                        }
                    }


                    if (isset($data['sale_tax_area_id']) && !empty($data['sale_tax_area_id'])) {

                        foreach ($data['sale_tax_area_id'] as $value) {

                            $param3 = array(
                                'property_id' => $result1,
                                'sale_tax_area_id' => $value
                            );

                            $result = $this->PropertySalesTax->CreateOnePropertySalesTax($param3);
                        }
                    }


                    if ($result1) {
                        //  echo "1";

                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> added successfully</div>');
                        redirect("admin/propertyList");
                    } else {
                        //  echo "0notinsert";
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> not added.</div>');
                        redirect("admin/propertyList");
                    }
                }
            }
        }
    }

    public function addPropertyDataJson()
    {

        $data = $this->input->post();
        $user_id = $this->session->userdata['user_id'];
        $company_id = $this->session->userdata['company_id'];
        //    die(print_r($data));
        // echo "<pre>";

        $this->form_validation->set_rules('property_title', 'Property Title', 'required');
        $this->form_validation->set_rules('property_address', 'Address', 'required');
        $this->form_validation->set_rules('property_address_2', 'Address 2', 'trim');
        $this->form_validation->set_rules('property_city', 'City', 'required');
        $this->form_validation->set_rules('property_state', 'State', 'required');
        $this->form_validation->set_rules('property_zip', 'Zipcode', 'required|isValidUSzipcodeCApostcode',
            array('isValidUSzipcodeCApostcode' => 'Invalid Postal Code.'));
        $this->form_validation->set_rules('property_area', 'Area', 'trim');
        $this->form_validation->set_rules('property_type', 'Property Type', 'required');
        if ($this->input->post("property_status") != 2) {
            $this->form_validation->set_rules('yard_square_feet', 'Squre Feet', 'required');
        }
        $this->form_validation->set_rules('property_notes', 'Notes', 'trim');
        //$this->form_validation->set_rules('difficulty_level', 'Difficulty Level', 'required');

        $this->form_validation->set_rules('total_yard_grass', 'Property Title', 'trim');
        $this->form_validation->set_rules('front_yard_square_feet', 'Squre Fee', 'trim');
        $this->form_validation->set_rules('back_yard_square_feet', 'Squre Fee', 'trim');
        $this->form_validation->set_rules('front_yard_grass', 'Property Title', 'trim');
        $this->form_validation->set_rules('back_yard_grass', 'Property Title', 'trim');
        $this->form_validation->set_rules('measure_map_project_id', 'Measure Map ID', 'trim');


        // TODO check for duplicates
        $where_arr = array(
            'company_id' => $company_id,
            'property_address' => $data['property_address'],
            'property_city' => $data['property_city'],
            'property_state' => $data['property_state'],
            'property_zip' => $data['property_zip']
        );

        $property_lookup = $this->PropertyModel->getOneProperty($where_arr);

        if ($this->form_validation->run() == FALSE) {
            $return_array = array('status' => 400, 'msg' => validation_errors());
        } else if (!empty($property_lookup) && $data['confirmation'] == 0) {

            $return_array = array('status' => 401, 'msg' => 'Property<strong> already exists </strong>  Are you sure you want to add it?</div>');

            //echo print_r(validation_errors()).$message;
        } else {

            $tags = "";
            if (isset($data['tags'])) {
                $tags = implode(',', $data['tags']);
            }
            $property_area = 0;
            if (isset($data['property_area']) && $data['property_area'] != "") {
                $property_area = $data['property_area'];
            }
            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'property_title' => $data['property_title'],
                'property_address' => $data['property_address'],
                'property_address_2' => $data['property_address_2'],
                'property_city' => $data['property_city'],
                'property_state' => $data['property_state'],
                'property_zip' => $data['property_zip'],
                'property_area' => $property_area,
                'property_type' => $data['property_type'],
                'yard_square_feet' => $data['yard_square_feet'],
                'property_notes' => $data['property_notes'],
                'property_status' => $data['property_status'],
                'source' => $data['source'],
                'total_yard_grass' => $data['total_yard_grass'],
                'front_yard_square_feet' => $data['front_yard_square_feet'],
                'back_yard_square_feet' => $data['back_yard_square_feet'],
                'tags' => $tags,
                // 'property_price' => $data['property_price'],
                //'assign_program' => 1,
                //'assign_customer' => 2
            );
            if (isset($data['measure_map_project_id']) && !empty($data['measure_map_project_id'])) {
                $param['measure_map_project_id'] = $data['measure_map_project_id'];
            }
            if (isset($data['front_yard_grass']) && !empty($data['front_yard_grass'])) {
                $param['front_yard_grass'] = $data['front_yard_grass'];
            }
            if (isset($data['back_yard_grass']) && !empty($data['back_yard_grass'])) {
                $param['back_yard_grass'] = $data['back_yard_grass'];
            }
            if (isset($data['measure_map_project_id']) && !empty($data['measure_map_project_id'])) {
                $param['measure_map_project_id'] = $data['measure_map_project_id'];
            }
            if (isset($data['difficulty_level']) && !empty($data['difficulty_level'])) {
                $param['difficulty_level'] = $data['difficulty_level'];
            } else {
                $param['difficulty_level'] = 1;
            }

            $geo = $this->getLatLongByAddress($data['property_address']);

            // Determine Available Days
            $param['available_days'] = json_encode(determineAvailableDays($data));

            if (!$geo) {
                $return_array = array('status' => 400, 'msg' => 'Invalid property address.');
            } else {

                $param['property_latitude'] = $geo['lat'];
                $param['property_longitude'] = $geo['long'];
                $check = $this->PropertyModel->checkProperty($param);

                if ($check == "true") {
                    $return_array = array('status' => 400, 'msg' => 'Property  Already exists.');
                } else {

                    $salesVisit = $this->ProgramModel->get_all_program(array('program_name' => 'Sales Visit Standalone', 'company_id' => $company_id));
                    // die(print_r($salesVisit));
                    $salesVStandalone = $this->JobModel->getAllJob(array('job_name' => 'Sales Visit Standalone', 'jobs.company_id' => $company_id));

                    // die(print_r($salesVisit[0]->program_id));
                    // die(print_r($salesVStandalone));

                    $result1 = $this->PropertyModel->insert_property($param);

                    if ($result1) {
                        if (isset($data['property_conditions']) && is_array($data['property_conditions']) && !empty($data['property_conditions'])) {
                            #assign property conditions
                            foreach ($data['property_conditions'] as $condition) {
                                $handleAssignConditions = $this->PropertyModel->assignPropertyCondition(array('property_id' => $result1, 'property_condition_id' => $condition));
                            }
                        }

                        if (isset($data['assign_customer']) && !empty($data['assign_customer'])) {

                            foreach ($data['assign_customer'] as $value) {

                                $param2 = array(
                                    'property_id' => $result1,
                                    'customer_id' => $value

                                );
                                $result = $this->PropertyModel->assignCustomer($param2);
                            }
                        }

                        ##### ASSIGN SALE VISIT SERVICE (RG) #####
                        if (isset($data['property_status']) && $data['property_status'] == 2) {
                            $prospect = array(
                                'property_id' => $result1,
                                'program_id' => $salesVisit[0]->program_id,
                                // 'price_override' => $value->price_override,
                                // 'is_price_override_set' => $value->is_price_override_set
                            );
                            // THIS LINE COMMENTED OUT ON 4/28/23 AS PER A BASECAMP FROM BRIAN (https://basecamp.com/2362279/projects/17952987/messages/103539042)
                            //$result = $this->PropertyModel->assignProgram($prospect);
                        }
                        ####

                        if (isset($data['is_group_billing']) && $data['is_group_billing'] == 1) {
                            if (isset($data['property_is_email']) && $data['property_is_email'] == 'on') {
                                $email_opt_in = 1;
                            } else {
                                $email_opt_in = 0;
                            }

                            if (isset($data['property_is_text']) && $data['property_is_text'] == 'on') {
                                $phone_opt_in = 1;
                            } else {
                                $phone_opt_in = 0;
                            }
                            $group_billing_params = array(
                                'property_id' => $result1,
                                'first_name' => $data['property_first_name'],
                                'last_name' => $data['property_last_name'],
                                'email' => $data['property_email'],
                                'email_opt_in' => $email_opt_in,
                                'phone' => $data['property_phone'],
                                'phone_opt_in' => $phone_opt_in,
                            );
                            $assignGroupBilling = $this->PropertyModel->assignGroupBilling($group_billing_params);
                        }


                        if (isset($data['assign_program']) && !empty($data['assign_program'])) {

                            foreach ($data['assign_program'] as $value) {

                                $param3 = array(
                                    'property_id' => $result1,
                                    'program_id' => $value,

                                );

                                $result = $this->PropertyModel->assignProgram($param3);
                            }
                        }


                        if (isset($data['sale_tax_area_id']) && !empty($data['sale_tax_area_id'])) {
                            foreach ($data['sale_tax_area_id'] as $value) {

                                $param3 = array(
                                    'property_id' => $result1,
                                    'sale_tax_area_id' => $value
                                );

                                $result = $this->PropertySalesTax->CreateOnePropertySalesTax($param3);
                            }
                        }


                        $return_array = array('status' => 200, 'msg' => 'Property  added successfully.', 'result' => $result1);
                    } else {
                        $return_array = array('status' => 200, 'msg' => 'Property not added.');
                    }
                }
            }
        }

        echo json_encode($return_array);
    }

    /**
     * Returns comma-seperated email address
     * @params post
     * @return string json_encoded string
     */
    public function addSecondaryEmailDataJson()
    {
        $data = $this->input->post();
        $this->form_validation->set_rules('secondary_email', 'Email', 'required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $return_array = array('status' => 400, 'msg' => validation_errors());
        } else {
            $data = $this->input->post();
            $emails_list = [];
            if ($data['already_added_emails'] != '') {
                // Converts string into array.
                $emails_list = explode(',', $data['already_added_emails']);
                // Checks to avoid duplicate email entry for customer.
                if (!in_array($data['secondary_email'], $emails_list)) {
                    array_push($emails_list, $data['secondary_email']);
                }
            } else {
                array_push($emails_list, $data['secondary_email']);
            }
            $result = implode(',', $emails_list);
            $return_array = array('status' => 200, 'msg' => 'Property  added successfully.', 'result' => $result);
        }
        echo json_encode($return_array);
    }


    public function editProperty($propertyID = NULL)
    {
        if (!empty($propertyID)) {
            $propertyID = $propertyID;
        } else {
            $propertyID = $this->uri->segment(4);
        }
        

        $data['service_areas'] = $this->ServiceArea->getAllServiceArea(['company_id' => $this->session->userdata['company_id']]);
        $data['polygon_bounds'] = [];
        foreach ($data['service_areas'] as $k => $v) {
            if ($v->service_area_polygon)
                $data['polygon_bounds'][] = ["latlng" => $v->service_area_polygon, "marker" => $v->category_area_name, "property_area_cat_id" => $v->property_area_cat_id];
        }

        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);
        $data['servicelist'] = $this->JobModel->getJobList($where);
        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList($where);
        $data['programlist'] = $this->PropertyModel->getProgramList(array('company_id' => $this->session->userdata['company_id'], 'program_active' => 1, 'ad_hoc' => 0));
        // foreach($data['programlist'] as $key => $val){
        //     if(strstr($val->program_name, '-Standalone Service')){
        //         print_r($data['programlist'][$key]);
        //     }
        //     if(strstr($val->program_name, '- Standalone')){
        //         print_r($data['programlist'][$key]);
        //     }
        // }

        $data['customerlist'] = $this->PropertyModel->getCustomerList($where);
        $data['taglist'] = $this->PropertyModel->getTagsList($where);
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
        $data['propertyData'] = $this->PropertyModel->getPropertyDetail($propertyID);
        $data['groupBilling'] = $this->PropertyModel->getGroupBillingByProperty($propertyID);
        $data['selectedprogramlist'] = $this->PropertyModel->getSelectedProgram($propertyID);
        $data['propertyconditionslist'] = $this->PropertyModel->getCompanyPropertyConditions(array('company_id' => $this->session->userdata['company_id']));

        $data['selectedpropertyconditions'] = array();
        $getAssignedConditions = $this->PropertyModel->getAssignedPropertyConditions(array('property_id' => $propertyID));
        if (!empty($getAssignedConditions)) {
            foreach ($getAssignedConditions as $condition) {
                $data['selectedpropertyconditions'][] = $condition->property_condition_id;
            }
        }
        $data['originalprogramlist'] = $this->PropertyModel->getSelectedProgram($propertyID);

        $selecteddata1 = $this->PropertyModel->getSelectedCustomer($propertyID);
        $data['selectedcustomerlist'] = array();
        $data['is_group_billing'] = 0;
        if (!empty($selecteddata1)) {
            foreach ($selecteddata1 as $value) {
                $data['selectedcustomerlist'][] = $value->customer_id;

                $checkGroupBilling = $this->CustomerModel->checkGroupBilling($value->customer_id);
                if ($checkGroupBilling == 'true') {
                    $data['is_group_billing'] = 1;
                }
            }
        }

        $data['property_alerts'] = json_decode($data['propertyData']['alerts']);

        if (isset($data['selectedcustomerlist'][0])) {
            $data['customer_id'] = $data['selectedcustomerlist'][0];
            $customer_details = $this->CustomerModel->getCustomerDetail($data['customer_id']);
            $data['customer_alerts'] = isset($customer_details['alert']) ? json_decode($customer_details['alert']) : array();
        }

        $data['selected_program_ids'] = array();
        $data['original_program_ids'] = array();
        $select_ids = [];
        if ($data['propertyData']['tags'] != null && $data['propertyData']['tags'] != "") {
            $tags = $data['propertyData']['tags'];
            $select_ids = explode(',', $tags);
        }
        $data['selected_tag_ids'] = $select_ids;
        if (!empty($data['selectedprogramlist'])) {
            foreach ($data['selectedprogramlist'] as $key => $value) {
                $data['selected_program_ids'][] = $value->program_id;
            }
        }
        if (!empty($data['selectedtaglist'])) {
            foreach ($data['selectedprogramlist'] as $key => $value) {
                $data['selected_program_ids'][] = $value->program_id;
            }
        }
        if (!empty($data['originalprogramlist'])) {
            foreach ($data['originalprogramlist'] as $key => $value) {
                $data['original_program_ids'][] = $value->program_id;
            }
        }

        // print_r($data['selected_program_ids']); die();
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

        $data['assign_sales_tax'] = array();

        $assingtax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $propertyID));

        if ($assingtax) {
            $data['assign_sales_tax'] = array_column($assingtax, 'sale_tax_area_id');
        }

        /* Get company users for note assignments */
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);


        $config = $this->load_paginate_configuration();
        $config["base_url"] = base_url() . "admin/editProperty/" . $propertyID;
        $config["total_rows"] = $this->CompanyModel->getPropertyNotes($propertyID, [], true);
        $this->pagination->initialize($config);
        $page_index = isset($filter['page']) ? $filter['page'] : 1;
        $data["pagination_links"] = $this->pagination->create_links();
        $data["per_page_arr"] = self::PER_PAGE_ARR;
        $data['property_notes'] = $this->CompanyModel->getPropertyNotes($propertyID, [], false, $config['per_page'], $page_index);
        if (!empty($data['property_notes'])) {
            foreach ($data['property_notes'] as $note) {
                $note->comments = $this->CompanyModel->getNoteComments($note->note_id);
                $note->files = $this->CompanyModel->getNoteFiles($note->note_id);
            }
        }

        #####Source #####
        $data['source_list'] = $this->SourceModel->getAllSource(array('company_id' => $this->session->userdata['company_id']));
        $data['users'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
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

        /* Get Note Categories */
        $data['note_types'] = $this->CompanyModel->getNoteTypes($this->session->userdata['company_id']);
        $service_specific_id = "";
        foreach ($data['note_types'] as $type) {
            if ($type->type_name == "Service-Specific" && $type->type_company_id == 0) {
                $service_specific_id = $type->type_id;
            }
        }
        $data['service_specific_note_type_id'] = $service_specific_id;

        $page["active_sidebar"] = "properties";
        $page["page_name"] = "Update Property";
        $page["page_content"] = $this->load->view("admin/edit_property", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function ajaxPropertyNotes()
    {
        $filter = $this->input->post();
        $propertyID = $filter['property_id'];
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['servicelist'] = $this->JobModel->getJobList($where);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);

        $page_index = isset($filter['page']) ? $filter['page'] : 1;
        $config = $this->load_paginate_configuration();
        $config['uri_segment'] = $page_index;
        $config["base_url"] = base_url() . "admin/editProperty/" . $propertyID;
        $config['per_page'] = isset($filter['per_page']) ? $filter['per_page'] : 10;
        $config["total_rows"] = $this->CompanyModel->getPropertyNotes($propertyID, $filter, true);
        $this->pagination->initialize($config);

        $data['property_notes'] = $this->CompanyModel->getPropertyNotes($propertyID, $filter, false, $config['per_page'], $page_index);
        $data['pagination_links'] = $this->pagination->create_links();
        $data['per_page_arr'] = self::PER_PAGE_ARR;
        $data['filter'] = $filter;
        if (!empty($data['property_notes'])) {
            foreach ($data['property_notes'] as $note) {
                $note->comments = $this->CompanyModel->getNoteComments($note->note_id);
                $note->files = $this->CompanyModel->getNoteFiles($note->note_id);
            }
        }
        $data['note_types'] = $this->CompanyModel->getNoteTypes($this->session->userdata['company_id']);
        $service_specific_id = "";
        foreach ($data['note_types'] as $type) {
            if ($type->type_name == "Service-Specific" && $type->type_company_id == 0) {
                $service_specific_id = $type->type_id;
            }
        }
        $data['service_specific_note_type_id'] = $service_specific_id;
        echo $this->load->view("admin/ajax_to_view/property_notes", $data, TRUE);

    }

    public function assignCustomerList()
    {
        $data = $this->PropertyModel->getCustomerListFromAutoComplete($this->session->userdata['company_id'], $_POST['keyword']);


        if (!empty($data)) {
            echo "<ul id='customer-list'>";

            foreach ($data as $customer) {

                echo '<li class="customerListField" data-id="' . $customer->customer_id . '" onClick="selectCustomer($(this),';

                echo "'";
                echo $customer->customer_id;
                echo "'";
                echo ", ";
                echo "'";
                echo $customer->billing_type;
                echo "'";
                echo ", ";
                echo "'";
                echo $customer->billing_street;
                echo "'";
                echo ", ";
                echo "'";
                echo $customer->last_name . " " . $customer->first_name;
                echo "'";

                echo ");";
                echo '">';
                echo $customer->last_name . " " . $customer->first_name;
                echo "</li>";

            }

            echo "</ul>";
        } else {
            return false;
        }


        //return $data;

    }

	public function updateProperty()
	{
        
		$post_data = $this->input->post();
		$property_id = $this->input->post('property_id');

        $data['propertyData'] = $this->PropertyModel->getPropertyDetail($property_id);

        //*******Get current tags before db update*****
        $select_ids=[];	
		if( $data['propertyData']['tags']!=null && $data['propertyData']['tags']!=""){	
			$tags=$data['propertyData']['tags'];	
			$select_ids=explode(',', $tags);	
		}	
        //********************************************

		$orig_progs = explode(',', $this->input->post('original_progs'));
		$company_id = $this->session->userdata['company_id'];
		$user_id = $this->session->userdata['user_id'];
		$setting_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));
		//print_r($property_id); die();
		$this->form_validation->set_rules('property_title', 'Property Title', 'required');
		$this->form_validation->set_rules('property_address', 'Address', 'required');
		$this->form_validation->set_rules('property_address_2', 'Address 2', 'trim');
		$this->form_validation->set_rules('property_city', 'City', 'required');
		$this->form_validation->set_rules('property_state', 'State', 'required');
		$this->form_validation->set_rules('property_zip', 'Zipcode', 'required');
		$this->form_validation->set_rules('property_area', 'Area', 'trim');
		$this->form_validation->set_rules('property_type', 'Property Type', 'required');
		$this->form_validation->set_rules('yard_square_feet', 'Squre Feet', 'required');
		$this->form_validation->set_rules('property_notes', 'Notes', 'trim');
		$this->form_validation->set_rules('assign_program[]', 'Assign Program', 'trim');
		$this->form_validation->set_rules('assign_customer[]', 'Assign Customer', 'trim');
		// $this->form_validation->set_rules('difficulty_level', 'Difficulty Level', 'required');
		$this->form_validation->set_rules('tags[]', 'Assign Tags');	
		$this->form_validation->set_rules('total_yard_grass', 'Squre Feet', 'trim');
		$this->form_validation->set_rules('front_yard_square_feet', 'Squre Feet', 'trim');
		$this->form_validation->set_rules('back_yard_square_feet', 'Squre Feet', 'trim');
		$this->form_validation->set_rules('front_yard_grass', 'Assign Customer', 'trim');
		$this->form_validation->set_rules('back_yard_grass', 'Assign Customer', 'trim');
		$this->form_validation->set_rules('measure_map_project_id', 'Measure Map ID', 'trim');

		if ($this->form_validation->run() == FALSE) {

			$this->addProperty();
		} else {
			$post_data = $this->input->post();
			#get program list from post data
			  $post_program_ids = [];
			  $existing_program_ids = [];
			  $new_program_ids = [];
			  $remove_program_ids = [];
			  if(isset($post_data['assign_program']) && !empty($post_data['assign_program'])){
				foreach(json_decode($post_data['assign_program']) as $prog){
				  $post_program_ids[]=$prog->program_id;
				}
			  }
		  	#get all previously assigned programs for this property
		  	$getAssignedPrograms = $this->PropertyModel->getAllprogram(array('property_id'=>$property_id));
		  	if(!empty($getAssignedPrograms)){
				foreach($getAssignedPrograms as $prev){
			  	if(is_array($post_program_ids) && in_array($prev->program_id,$post_program_ids)){
					$existing_program_ids[] = $prev->program_id;
				}elseif(is_array($post_program_ids) && !in_array($prev->program_id,$post_program_ids)){
					$remove_program_ids[$prev->property_program_id] = $prev->program_id;
			  	}
				}
		  	}
		  	foreach($post_program_ids as $post_program){
				if(is_array($existing_program_ids) && !in_array($post_program,$existing_program_ids)){
			  	$new_program_ids[] = $post_program;
				}
		  	}
			$tags ='';	
			if(isset($post_data['tags'])){	
				$tags= implode(',', $post_data['tags']);	
			}
			$param = array(
				'property_title' => $post_data['property_title'],
				'property_address' => $post_data['property_address'],
				'property_address_2' => $post_data['property_address_2'],
				'property_city' => $post_data['property_city'],
				'property_state' => $post_data['property_state'],
				'property_zip' => $post_data['property_zip'],
				'property_area' => $post_data['property_area'],
				'property_type' => $post_data['property_type'],
				'yard_square_feet' => $post_data['yard_square_feet'],
				'property_notes' => $post_data['property_notes'],
				'property_status' => $post_data['property_status'],
				'front_yard_square_feet' => $post_data['front_yard_square_feet'],
				'back_yard_square_feet' => $post_data['back_yard_square_feet'],
				'tags' => $tags,
                'source' => $post_data["source"]
			);

            $beforeSavedParams = $param['tags'];
            
            #check if property is already cancelled
            $checkIfCancelled = $this->PropertyModel->checkPropertyCancelled($property_id);
            
            if($checkIfCancelled){
                #if previously cancelled and status is being changed to active or prospect we need to remove cancelled date
                if(isset($post_data['property_status']) && $post_data['property_status'] != 0){
                    $param['property_cancelled'] = NULL;
                }
            }else{
                if(isset($post_data['property_status']) && $post_data['property_status'] == 0){
                   // $param['property_cancelled'] = date('Y-m-d H:i:s', strtotime('now'));
                    # do we need to call cancelProperty function here?  Not in scope but may need to clarify 
                }
            }
            if (!empty($post_data['difficulty_level'])) {
                $param['difficulty_level'] = $post_data['difficulty_level'];
            } else {
                $param['difficulty_level'] = 1;
            }
            if (!empty($post_data['total_yard_grass'])) {
                $param['total_yard_grass'] = $post_data['total_yard_grass'];
            }
            if (!empty($post_data['front_yard_grass'])) {
                $param['front_yard_grass'] = $post_data['front_yard_grass'];
            }
            if (!empty($post_data['back_yard_grass'])) {
                $param['back_yard_grass'] = $post_data['back_yard_grass'];
            }
            if (!empty($post_data['measure_map_project_id'])) {
                $param['measure_map_project_id'] = $post_data['measure_map_project_id'];
            }

            $geo = $this->getLatLongByAddress($post_data['property_address']);

            // Determine Available Days
            $param['available_days'] = json_encode(determineAvailableDays($post_data));

            if ($geo) {

                $param['property_latitude'] = $geo['lat'];
                $param['property_longitude'] = $geo['long'];

                $check = $this->PropertyModel->checkProperty($param, $property_id);

                if ($check == "true") {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong>  Already exists.</div>');

					redirect("admin/propertyList");
				} else {
					$result = $this->PropertyModel->updateAdminTbl($property_id, $param);
					

                    //if tags then trigger webhook
                    if(!empty($param['tags'])){ // need to check if new tag or existing tag. only trigger for new
                        //tags already in db before update
                        $currTags = $select_ids;

                       //check for values of posted (submitted) tags that are not in current tags (in db)
                       $differences = array_diff($post_data['tags'], $currTags);
                       $differenceString = implode(",", $differences);

						$user_info = $this->Administrator->getOneAdmin(array("user_id" => $this->session->userdata('user_id')));
                        if($user_info->webhook_tag_created){
                            if($differenceString) {
                                $this->load->model('api/Webhook');
                            $response = $this->Webhook->callTrigger($user_info->webhook_tag_created, $result = ['property_id' => $property_id, 'tags' =>  $param['tags']]);
                            }
                        }
                    }




					if(isset($post_data['is_group_billing']) && $post_data['is_group_billing'] == 1){
						if(isset($post_data['property_is_email']) && $post_data['property_is_email'] == 'on'){
							$email_opt_in = 1;
						}else{
							$email_opt_in = 0;
						}
						if(isset($post_data['property_is_text']) && $post_data['property_is_text'] == 'on'){
							$phone_opt_in = 1;
						}else{
							$phone_opt_in = 0;
						}
						$group_billing_params = array(
							  'property_id' => $property_id,
							  'first_name' => $post_data['property_first_name'],
							  'last_name' => $post_data['property_last_name'],
							  'email' => $post_data['property_email'],
                              'secondary_email' => isset($post_data['secondary_email']) && !empty($post_data['secondary_email']) ? $post_data['secondary_email'] : '',
							  'email_opt_in' => $email_opt_in,
							  'phone' => $post_data['property_phone'],
                              'secondary_phone' => isset($post_data['secondary_phone']) && !empty($post_data['secondary_phone']) ? $post_data['secondary_phone'] : '',
							  'phone_opt_in' => $phone_opt_in,
        				);
						if(isset($post_data['group_billing_id'])){
							$updateGroupBilling = $this->PropertyModel->updateGroupBilling($post_data['group_billing_id'],$group_billing_params);
						}else{
							$assignGroupBilling = $this->PropertyModel->assignGroupBilling($group_billing_params);
						}
					}
					#get existing property conditions
					$getAssignedConditions = $this->PropertyModel->getAssignedPropertyConditions(array('property_id'=>$property_id));
					if(!empty($getAssignedConditions)){
						#remove conditions from property
						$deleteAssignedConditions = $this->PropertyModel->deleteAssignedPropertyConditions(array('property_id'=>$property_id));
					}
					if(isset($post_data['property_conditions']) && is_array($post_data['property_conditions'])){
						#assign property conditions
						foreach($post_data['property_conditions'] as $condition){
							$handleAssignConditions = $this->PropertyModel->assignPropertyCondition(array('property_id'=>$property_id,'property_condition_id'=>$condition));
						}
					}
					$where = array('property_id' => $property_id);
					$delete = $this->PropertyModel->deleteAssignCustomer($where);
					$count = 0;
					if (isset($post_data['assign_customer']) && !empty($post_data['assign_customer']))	{
						foreach ($post_data['assign_customer'] as $value) {

                            $param2 = array(
                                'property_id' => $property_id,
                                'customer_id' => $value
                            );
                            $assigncustomer = $this->PropertyModel->assignCustomer($param2);
                            $count++;
                        }
                    }

                    if (!empty($remove_program_ids)) {
                        foreach ($remove_program_ids as $property_program_id => $program_id) {
                            $handleDelete = $this->PropertyModel->deleteAssignProgram(array('property_program_id' => $property_program_id));
                        }
                    }

                    if (isset($post_data['assign_program']) && !empty($post_data['assign_program'])) {
                        foreach (json_decode($post_data['assign_program']) as $value) {
                            if (is_array($new_program_ids) && in_array($value->program_id, $new_program_ids)) {
                                $programs = array();
                                $programs['properties'] = array();

                                $param3 = array(
                                    'property_id' => $property_id,
                                    'program_id' => $value->program_id,
                                    'price_override' => $value->price_override,
                                    'is_price_override_set' => $value->is_price_override_set,
                                );

                                $assignprogram = $this->PropertyModel->assignProgram($param3);
                                ##email/text notifications
                                if ($assignprogram) {
                                    $property_details = $this->PropertyModel->getOneProperty(array('property_id' => $property_id));
                                    $customer_details = $this->CustomerModel->getOnecustomerPropert(array('property_id' => $property_id));
                                    ##check customer billing type
                                    $checkGroupBilling = $this->CustomerModel->checkGroupBilling($customer_details->customer_id);
                                    #if customer billing type = group billing, then we notify the property level contact info
                                    if ($checkGroupBilling) {
                                        $emaildata['contactData'] = $this->PropertyModel->getGroupBillingByProperty($property_id);
                                        $emaildata['propertyData'] = $property_details;
                                        $emaildata['programData'] = $this->ProgramModel->getProgramDetail($value->program_id);
                                        $emaildata['assign_date'] = date("Y-m-d H:i:s");
                                        $emaildata['company_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                                        $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id' => $this->session->userdata['company_id']));
                                        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' => $this->session->userdata['company_id'], 'is_smtp' => 1));
                                        $body = $this->load->view('email/group_billing/program_email', $emaildata, true);
                                        if (!$company_email_details) {
                                            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                                        }
                                        #send email
                                        if (isset($emaildata['company_email_details']->program_assigned_status) && $emaildata['company_email_details']->program_assigned_status == 1 && isset($emaildata['contactData']['email_opt_in']) && $emaildata['contactData']['email_opt_in'] == 1) {
                                            $sendEmail = Send_Mail_dynamic($company_email_details, $emaildata['contactData']['email'], array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Program Assigned');
                                        }
                                        #send text
                                        if (isset($emaildata['company_details']->is_text_message) && $emaildata['company_details']->is_text_message == 1 && isset($emaildata['company_email_details']->program_assigned_status_text) && $emaildata['company_email_details']->program_assigned_status_text == 1 && isset($emaildata['contactData']['phone_opt_in']) && $emaildata['contactData']['phone_opt_in'] == 1) {
                                            $sendText = Send_Text_dynamic($emaildata['contactData']['phone'], $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
                                        }
                                    } else {
                                        $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id' => $customer_details->customer_id));
                                        $emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id' => $customer_details->customer_id, 'is_email' => 1, 'program_id' => $value->program_id, 'property_id' => $property_id));
                                        $emaildata['company_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                                        $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id' => $this->session->userdata['company_id']));
                                        $emaildata['assign_date'] = date("Y-m-d H:i:s");
                                        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' => $this->session->userdata['company_id'], 'is_smtp' => 1));
                                        $body = $this->load->view('email/program_email', $emaildata, true);
                                        if (!$company_email_details) {
                                            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                                        }
                                        #check if company setting for this notification are turned on AND check if customer is subscribed to email notifications
                                        if ($emaildata['company_email_details']->program_assigned_status == 1 && isset($emaildata['customerData']->is_email) && $emaildata['customerData']->is_email == 1) {
                                            $sendEmail = Send_Mail_dynamic($company_email_details, $emaildata['customerData']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Program Assigned', $emaildata['customerData']->secondary_email);
                                        }
                                        #check if company has text message notifications and if setting for this notification are turned on AND check if customer is subscribed to text notifications
                                        if (isset($emaildata['company_details']->is_text_message) && $emaildata['company_details']->is_text_message == 1 && isset($emaildata['company_email_details']->program_assigned_status_text) && $emaildata['company_email_details']->program_assigned_status_text == 1 && isset($emaildata['customerData']->is_mobile_text) && $emaildata['customerData']->is_mobile_text == 1) {
                                            $sendText = Send_Text_dynamic($emaildata['customerData']->phone, $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
                                        }
                                    }
                                }

                                $program['properties'][$property_id] = array(
                                    'program_property_id' => $assignprogram,
                                );

                                // Create Invoice if One-Time Invoicing Program Selected
                                $prog_details = $this->ProgramModel->getProgramDetail($value->program_id);
                                $jobs = $this->ProgramModel->getSelectedJobs($value->program_id);

                                if ($prog_details['program_price'] == 1) {
                                    if (!in_array($value->program_id, $orig_progs)) {
                                        //create jobs array
                                        $ppjobinv = array();

                                        //get customer property details
                                        $customer_property_details = $this->CustomerModel->getAllProperty(array('customer_property_assign.property_id' => $property_id));

                                        if ($customer_property_details) {
                                            $QBO_description = array();
                                            $actual_description_for_QBO = array();
                                            $QBO_cost = 0;
                                            foreach ($customer_property_details as $key2 => $value2) {

                                                //get customer info
                                                $cust_details = getOneCustomerInfo(array('customer_id' => $value2->customer_id));

                                                $total_cost = 0;
                                                $description = "";
                                                $est_cost = 0;


                                                // foreach program property job... calculate job cost
                                                foreach ($jobs as $key3 => $value3) {
                                                    $job_id = $value3->job_id;

                                                    $job_details = $this->JobModel->getOneJob(array('job_id' => $job_id));

                                                    $description = $job_details->job_name . " ";

                                                    $QBO_description[] = $job_details->job_name;
                                                    $actual_description_for_QBO[] = $job_details->job_description;

                                                    $where2 = array(
                                                        'property_id' => $property_id,
                                                        'job_id' => $job_id,
                                                        'program_id' => $value->program_id,
                                                        'customer_id' => $value2->customer_id
                                                    );

                                                    //CALCULATE JOB COST

                                                    //check for price overrides
                                                    $estimate_price_override = GetOneEstimateJobPriceOverride($where2);
                                                    if ($estimate_price_override) {
                                                        $cost = $estimate_price_override->price_override;

                                                        $est_coup_param = array(
                                                            'cost' => $cost,
                                                            'estimate_id' => $estimate_price_override->estimate_id
                                                        );

                                                        $est_cost = $this->calculateEstimateCouponCost($est_coup_param);

                                                    } else {
                                                        $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $property_id, 'program_id' => $value->program_id));

                                                        if ($priceOverrideData && $priceOverrideData->is_price_override_set == 1) {
                                                            $cost = $priceOverrideData->price_override;
                                                        } else {
                                                            //else no price overrides, then calculate job cost
                                                            $lawn_sqf = $value2->yard_square_feet;
                                                            $job_price = $job_details->job_price;

                                                            //get property difficulty level
                                                            if (isset($value2->difficulty_level) && $value2->difficulty_level == 2) {
                                                                $difficulty_multiplier = $setting_details->dlmult_2;
                                                            } elseif (isset($value2->difficulty_level) && $value2->difficulty_level == 3) {
                                                                $difficulty_multiplier = $setting_details->dlmult_3;
                                                            } else {
                                                                $difficulty_multiplier = $setting_details->dlmult_1;
                                                            }

                                                            //get base fee
                                                            if (isset($job_details->base_fee_override)) {
                                                                $base_fee = $job_details->base_fee_override;
                                                            } else {
                                                                $base_fee = $setting_details->base_service_fee;
                                                            }

                                                            $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                                            //get min. service fee
                                                            if (isset($job_details->min_fee_override)) {
                                                                $min_fee = $job_details->min_fee_override;
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
                                                    $total_cost += $cost;
                                                    $ppjobinv[] = array(
                                                        'customer_id' => $value2->customer_id,
                                                        'property_id' => $property_id,
                                                        'program_id' => $value->program_id,
                                                        'job_id' => $job_id,
                                                        'cost' => $cost,
                                                    );

                                                    if ($est_cost != 0) {
                                                        $job_coup_param = array(
                                                            'customer_id' => $value2->customer_id,
                                                            'property_id' => $property_id,
                                                            'program_id' => $value->program_id,
                                                            'cost' => $est_cost,
                                                            'job_id' => $job_id
                                                        );

                                                        $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                                    } else {
                                                        $job_coup_param = array(
                                                            'customer_id' => $value2->customer_id,
                                                            'property_id' => $property_id,
                                                            'program_id' => $value->program_id,
                                                            'cost' => $cost,
                                                            'job_id' => $job_id
                                                        );

                                                        $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                                    }
                                                }

                                                //format invoice data
                                                $param = array(
                                                    'customer_id' => $value2->customer_id,
                                                    'property_id' => $property_id,
                                                    'program_id' => $value->program_id,
                                                    'user_id' => $user_id,
                                                    'company_id' => $company_id,
                                                    'invoice_date' => date("Y-m-d"),
                                                    'description' => $prog_details['program_notes'],
                                                    'cost' => ($total_cost),
                                                    'is_created' => 2,
                                                    'invoice_created' => date("Y-m-d H:i:s"),
                                                );
                                                //create invoice
                                                $invoice_id = $this->INV->createOneInvoice($param);

                                                //if invoice id
                                                if ($invoice_id) {
                                                    $param['invoice_id'] = $invoice_id;
                                                    //figure tax
                                                    if ($setting_details->is_sales_tax == 1) {
                                                        $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $property_id));
                                                        if ($property_assign_tax) {
                                                            foreach ($property_assign_tax as $tax_details) {
                                                                $invoice_tax_details = array(
                                                                    'invoice_id' => $invoice_id,
                                                                    'tax_name' => $tax_details['tax_name'],
                                                                    'tax_value' => $tax_details['tax_value'],
                                                                    'tax_amount' => $total_cost * $tax_details['tax_value'] / 100
                                                                );

                                                                $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                                            }
                                                        }
                                                    }

                                                    //Quickbooks Invoice **

                                                    $param['customer_email'] = $cust_details['email'];
                                                    $param['job_name'] = $description;

                                                    $QBO_description = implode(', ', $QBO_description);
                                                    $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);
                                                    $QBO_param = $param;
                                                    $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
                                                    $QBO_param['job_name'] = $QBO_description;

                                                    $cust_coup_param = array(
                                                        'cost' => $QBO_cost,
                                                        'customer_id' => $QBO_param['customer_id']
                                                    );

                                                    $QBO_param['cost'] = $this->calculateCustomerCouponCost($cust_coup_param);
                                                    $quickbook_invoice_id = $this->QuickBookInv($QBO_param);
                                                    //if quickbooks invoice then update invoice table with id
                                                    if ($quickbook_invoice_id) {
                                                        $invoice_id = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('quickbook_invoice_id' => $quickbook_invoice_id));
                                                    }


                                                    foreach ($program['properties'] as $propID => $prop) {
                                                        if ($propID == $property_id) {
                                                            foreach ($ppjobinv as $i => $job) {
                                                                //		echo "Property Program ID: ".$prop['program_property_id']."</br>";
                                                                //		echo "Job ID: ".$job['job_id']."</br>";
                                                                //		echo "Invoice ID: ".$invoice_id."</br>";
                                                                //	echo "---------<br>";
                                                                //store property program job invoice data
                                                                $newPPJOBINV = array(
                                                                    'customer_id' => $job['customer_id'],
                                                                    'property_id' => $job['property_id'],
                                                                    'program_id' => $job['program_id'],
                                                                    'property_program_id' => $prop['program_property_id'],
                                                                    'job_id' => $job['job_id'],
                                                                    'invoice_id' => $invoice_id,
                                                                    'job_cost' => $job['cost'],
                                                                    'created_at' => date("Y-m-d"),
                                                                    'updated_at' => date("Y-m-d"),
                                                                );

                                                                $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);
                                                            }
                                                        }
                                                    }

                                                    // assign coupon if global customer coupon exists
                                                    $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $value2->customer_id));
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
                                                                $params = array(
                                                                    'coupon_id' => $coupon_id,
                                                                    'invoice_id' => $invoice_id,
                                                                    'coupon_code' => $coupon_details->code,
                                                                    'coupon_amount' => $coupon_details->amount,
                                                                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                                                    'coupon_type' => $coupon_details->type
                                                                );
                                                                $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                                                            }
                                                        }
                                                    }
                                                } //end if invoice
                                            } //end foreach customer property
                                        }
                                    }
                                }// End Create Invoice

                                $customerInformation = $this->CustomerModel->getCustomerDetail($customer_details->customer_id);
                                $programInformation = $this->ProgramModel->getProgramDetail($param3['program_id']);
                                
                                
                                //die(print_r($customerInformation)); 
                                //die(print_r($customer_details)); 
                                                                
                                //$property_details= stdClass Object ( [property_id] => 55164 [company_id] => 44 [user_id] => 752a2563acae4aa59f422661a66b3304 [property_title] => MikeSchleiffarthTestProperty11 [property_address] => 718 Messina Drive, Ballwin, MO, USA [property_latitude] => 38.5781529 [property_longitude] => -90.5301542 [property_address_2] => [property_city] => Ballwin [property_state] => MO [property_zip] => 63021 [property_area] => 337 [property_type] => Residential [yard_square_feet] => 1500 [property_notes] => [property_status] => 4 [cancel_reason] => [source] => 0 [property_created] => 2023-03-30 13:12:51 [property_cancelled] => [property_updated] => 2023-03-31 09:52:20 [difficulty_level] => 1 [total_yard_grass] => Bermuda [front_yard_square_feet] => 0 [back_yard_square_feet] => 0 [front_yard_grass] => [back_yard_grass] => [measure_map_project_id] => [alerts] => [tags] => 1 [service_note] => [program_text_for_display] => ) 
                                //$customer_details= stdClass Object ( [customer_assign_id] => 124484 [customer_id] => 48683 [property_id] => 55164 [assign_date] => 2023-04-17 13:23:27 )
                                                                
                               // customerInformation =  Array ( [customer_id] => 48683 [quickbook_customer_id] => 0 [company_id] => 44 [user_id] => 752a2563acae4aa59f422661a66b3304 [first_name] => michael [last_name] => schleiffarth [customer_company_name] => [email] => mschleiffarth@blayzer.com [secondary_email] => [password] => [is_email] => 0 [phone] => 3141111111 [home_phone] => 0 [work_phone] => 0 [billing_street] => 388 messina [customer_latitude] => [customer_longitude] => [billing_street_2] => [billing_city] => ballwin [billing_state] => MO [billing_zipcode] => 63021 [assign_property] => [customer_status] => 1 [billing_type] => 0 [autosend_invoices] => 1 [autosend_frequency] => daily [created_at] => 2023-02-16 07:45:15 [updated_at] => 2023-03-30 12:39:14 [basys_autocharge] => 0 [clover_autocharge] => 0 [basys_customer_id] => [is_mobile_text] => 0 [password_reset_link] => [reset_link_expire] => [customer_clover_token] => [clover_acct_id] => 0 [pre_service_notification] => [] [alerts] =>
                               // programInformation = Array ( [program_id] => 934 [company_id] => 44 [user_id] => 752a2563acae4aa59f422661a66b3304 [program_name] => BL Test [program_price] => 1 [program_notes] => [program_job] => [program_created] => 2021-03-25 11:01:25 [program_update] => 2022-05-13 17:31:41 [program_active] => 1 [ad_hoc] => 0 [program_schedule_window] => 30
                               
                               
                               //update property status
                               $this->PropertyModel->autoStatusCheck(0, $param3['property_id']);


                               
                               //webhook_trigger
                                
                                $user_info = $this->Administrator->getOneAdmin(array("user_id" => $this->session->userdata('user_id')));
                                if($user_info->webhook_program_assigned){
                                    $this->load->model('api/Webhook');
                                    $webhook_data = ['property_id' => $param3['property_id'], 'property_name' => $property_details->property_title, 'property_address' => $property_details->property_address, 'property_square_footage' => $property_details->yard_square_feet, 'program_id' => $param3['program_id'], 'program_name' => $programInformation['program_name'], 'property_name'=>$property_details->property_title, 'customer_email' =>  $customerInformation['email'], 'customer_name' =>  $customerInformation['first_name'] . " " . $customerInformation['last_name'], 'address' => $customerInformation['billing_street'] . " " . $customerInformation['billing_city'] . ", " . $customerInformation['billing_state'] . " " . $customerInformation['billing_zipcode'], 'phone' => $customerInformation['phone']];
                                    //die(print_r($webhook_data));
                                    $response = $this->Webhook->callTrigger($user_info->webhook_program_assigned, $webhook_data);
                                }
                                
                                


							}//end if new program

						}
					}

                    $delete1 = $this->PropertySalesTax->deletePropertySalesTax(array('property_id' => $property_id));
                    if (isset($post_data['sale_tax_area_id']) && !empty($post_data['sale_tax_area_id'])) {
                        foreach ($post_data['sale_tax_area_id'] as $value4) {
                            $param3 = array(
                                'property_id' => $property_id,
                                'sale_tax_area_id' => $value4
                            );
                            $this->PropertySalesTax->CreateOnePropertySalesTax($param3);
                        }
                    }

                    if (!$result) {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');
                        redirect("admin/editProperty/" . $property_id);
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> updated successfully</div>');
                        redirect("admin/editProperty/" . $property_id);
                    }
                    redirect("admin/editProperty/" . $property_id);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invalid </strong> Property address</div>');
                redirect("admin/editProperty/" . $property_id);
            }
            redirect("admin/editProperty/" . $property_id);
        }
    }

    public function propertyDelete($property_id)
    {

        $where = array('property_id' => $property_id);
        $result = $this->PropertyModel->deleteProperty($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("admin/propertyList");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> deleted successfully</div>');
            redirect("admin/propertyList");
        }
    }

    public function deletemultipleProperties($value = '')
    {
        $properties = $this->input->post('properties');
        if (!empty($properties)) {
            foreach ($properties as $key => $value) {
                $where = array('property_id' => $value);
                $result = $this->PropertyModel->deleteProperty($where);
            }
            echo 1;
        } else {
            echo 0;
        }
    }

    /*//////////////////////////////  Property Section End  ///////////////////////  */


    /*/////////////////////////////  Programm Section Start //////////////////////   */

    public function programListArchived()
    {

        $where = array('company_id' => $this->session->userdata['company_id'], 'program_active' => 0);

        $data['programData'] = $this->ProgramModel->get_all_program($where);
        if (!empty($data['programData'])) {
            foreach ($data['programData'] as $key => $value) {

                $data['programData'][$key]->job_id = $this->ProgramModel->getProgramAssignJobs(array('program_id' => $value->program_id));


                $data['programData'][$key]->property_details = $this->ProgramModel->getAllproperty(array('program_id' => $value->program_id));
            }
        }


        $page["active_sidebar"] = "programArchive";
        $page["page_name"] = "Programs";
        $page["page_content"] = $this->load->view("admin/program_view", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function programList()
    {

        $where = array('company_id' => $this->session->userdata['company_id'], 'program_active' => 1, 'ad_hoc' => 0);

        $data['programData'] = $this->ProgramModel->get_all_program($where);
        if (!empty($data['programData'])) {
            // die(print_r($data));
            foreach ($data['programData'] as $key => $value) {

                $data['programData'][$key]->job_id = $this->ProgramModel->getProgramAssignJobs(array('program_id' => $value->program_id));


                $data['programData'][$key]->property_details = $this->ProgramModel->getAllproperty(array('program_id' => $value->program_id));
                // die($value->program_name);
                if (strstr($value->program_name, '-Standalone Service')) {
                    unset($data['programData'][$key]);
                } else if (strstr($value->program_name, '- One Time Project Invoicing') && strstr($value->program_name, '+')) {
                    unset($data['programData'][$key]);
                } else if (strstr($value->program_name, '- Invoiced at Job Completion') && strstr($value->program_name, '+')) {
                    unset($data['programData'][$key]);
                } else if (strstr($value->program_name, '- Manual Billing') && strstr($value->program_name, '+')) {
                    unset($data['programData'][$key]);
                }
            }
            // die(print_r($data));
        }


        $page["active_sidebar"] = "program";
        $page["page_name"] = "Programs";
        $page["page_content"] = $this->load->view("admin/program_view", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    //chris g start
    public function programAssignMessages($program_id, $propertylistarray)
    {
        //	$the_program = $this->ProgramModel->getOneProgramForCheck(array('program_id'=>$program_id));
        //	log_message('info', '/*****************************************************************/');
        //	ob_start();
        //	var_dump($this->db->last_query());
        //	$output_resulter = ob_get_clean();
        //	log_message('info', $output_resulter);
        //	log_message('info', '/*****************************************************************/');
        //	die();
        //	return;
    }

    //chris g end
    public function addProgram()
    {

        $where = array('property_tbl.company_id' => $this->session->userdata['company_id']);

        $data['joblist'] = $this->ProgramModel->getJobList(array('company_id' => $this->session->userdata['company_id']));
        $data['propertylist'] = $this->PropertyModel->get_all_list_properties($where);
        $data['propertyarealist'] = $this->PropertyModel->getPropertyAreaList(array('company_id' => $this->session->userdata['company_id']));
        $data['propertyconditionslist'] = $this->PropertyModel->getCompanyPropertyConditions(array('company_id' => $this->session->userdata['company_id']));


        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea($where);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where);

        $data['customerlist'] = $this->PropertyModel->getCustomerList($where);


        $page["active_sidebar"] = "program";
        $page["page_name"] = "Add Program";
        $page["page_content"] = $this->load->view("admin/add_program", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }


    public function addProgramData()
    {
        $data = $this->input->post();

        $program = array();

        // die(print_r($data));
        //Validate Form Data
        $this->form_validation->set_rules('program_name', 'Name', 'required');
        $this->form_validation->set_rules('program_price', 'Price', 'required');
        $this->form_validation->set_rules('program_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('program_job', 'Service', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $this->addProgram();
        } else {

            $data = $this->input->post();
            // die(print_r($data));
            $user_id = $this->session->userdata['user_id'];
            $company_id = $this->session->userdata['company_id'];
            $where = array('company_id' => $this->session->userdata['company_id']);
            $setting_details = $this->CompanyModel->getOneCompany($where);

            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'program_name' => $data['program_name'],
                'program_price' => $data['program_price'],
                'program_notes' => $data['program_notes'],
                'program_schedule_window' => $data['program_schedule_window']
                //'program_job' => $data['program_job']
            );

            $check = $this->ProgramModel->checkProgram($param);
            //Create Program
            $result = $this->ProgramModel->insert_program($param);
            // die(print_r($result));
            //SET PROGRAM ID
            $program['program_id'] = $result;
            if (!empty($data['program_job'])) {
                $n = 1;

                if (!is_array($data['program_job'])) {
                    $data['program_job'] = explode(",", $data['program_job']);
                }

                foreach ($data['program_job'] as $k => $value) {
                    $param2 = array(
                        'program_id' => $result,
                        'job_id' => $value,
                        'priority' => $n
                    );
                    //Assign jobs to program
                    $result1 = $this->ProgramModel->assignProgramJobs($param2);

                    $n++;
                }
            }
            // if properties then assign program to properties
            if (isset($data['propertylistarray']) && !empty($data['propertylistarray'])) {
                $program['properties'] = array();
                foreach (json_decode($data['propertylistarray']) as $value) {

                    $param3 = array(
                        'program_id' => $result,
                        'property_id' => $value->property_id,
                        'price_override' => $value->price_override,
                        'is_price_override_set' => $value->is_price_override_set
                        ///add invoice_id here
                    );
                    //assign program to property
                    $result2 = $this->PropertyModel->assignProgram($param3);
                    $program['properties'][$value->property_id] = array(
                        'program_property_id' => $result2,
                    );
                }

                // Here we add email/text

                $the_program = $this->ProgramModel->getOneProgramForCheck(array('program_id' => $result));


                foreach (json_decode($data['propertylistarray']) as $val) {


                    $property = $this->PropertyModel->getOneProperty(array('property_id' => $val->property_id));

                    $customer_id = $this->CustomerModel->getOnecustomerPropert(array('property_id' => $val->property_id));

                    #check customer billing type
                    $checkGroupBilling = $this->CustomerModel->checkGroupBilling($customer_id->customer_id);

                    #if customer billing type = group billing, then we notify the property level contact info
                    if ($checkGroupBilling) {
                        $emaildata['contactData'] = $this->PropertyModel->getGroupBillingByProperty($val->property_id);

                        $emaildata['propertyData'] = $property;
                        $emaildata['programData'] = $this->ProgramModel->getProgramDetail($program['program_id']);
                        $emaildata['assign_date'] = date("Y-m-d H:i:s");

                        $where = array('company_id' => $this->session->userdata['company_id']);
                        $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

                        $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

                        $where['is_smtp'] = 1;
                        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                        $body = $this->load->view('email/group_billing/program_email', $emaildata, true);

                        if (!$company_email_details) {
                            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                        }

                        #send email
                        if ($emaildata['company_email_details']->program_assigned_status == 1 && isset($emaildata['contactData']['email_opt_in']) && $emaildata['contactData']['email_opt_in'] == 1) {
                            $res = Send_Mail_dynamic($company_email_details, $emaildata['contactData']['email'], array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Program Assigned');
                        }

                        #send text
                        if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->program_assigned_status_text == 1 && $emaildata['contactData']['phone_opt_in'] == 1) {

                            $text_res = Send_Text_dynamic($emaildata['contactData']['phone'], $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
                        }

                    } else {
                        #if not group billing then we notify the customer
                        $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id' => $customer_id->customer_id));
                        $emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id' => $customer_id->customer_id, 'is_email' => 1, 'program_id' => $result, 'property_id' => $val->property_id));

                        $where = array('company_id' => $this->session->userdata['company_id']);
                        $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

                        $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

                        $emaildata['assign_date'] = date("Y-m-d H:i:s");

                        $where['is_smtp'] = 1;
                        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                        $body = $this->load->view('email/program_email', $emaildata, true);

                        if (!$company_email_details) {
                            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                        }


                        //die(print_r($this->db->last_query()));
                        if ($emaildata['company_email_details']->program_assigned_status == 1) {
                            $res = Send_Mail_dynamic($company_email_details, $emaildata['customerData']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Program Assigned', $emaildata['customerData']->secondary_email);
                        }

                        if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->program_assigned_status_text == 1 && $emaildata['customerData']->is_mobile_text == 1) {

                            //$string = str_replace("{CUSTOMER_NAME}", $emaildata['customerData']->first_name . ' ' . $emaildata['customerData']->last_name,$emaildata['company_email_details']->program_assigned_text);

                            $text_res = Send_Text_dynamic($emaildata['customerData']->phone, $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
                        }
                    }
                } // End Email/Text
            }
            /// if One-Time Program Invoicing, Property and services...
            if ($data['program_price'] == 1 && isset($data['propertylistarray']) && !empty($data['propertylistarray'] && !empty($data['program_job']))) {

                //foreach property
                foreach (json_decode($data['propertylistarray']) as $key => $value) {
                    //create jobs array
                    $ppjobinv = array();
                    //get customer property details
                    $customer_property_details = $this->CustomerModel->getAllproperty(array('customer_property_assign.property_id' => $value->property_id));
                    if ($customer_property_details) {
                        $QBO_description = array();
                        $actual_description_for_QBO = array();
                        $QBO_cost = 0;
                        foreach ($customer_property_details as $key2 => $value2) {
                            //get customer info
                            $cust_details = getOneCustomerInfo(array('customer_id' => $value2->customer_id));
                            $total_cost = 0;
                            $description = "";
                            $est_cost = 0;


                            // foreach program property job... calculate job cost
                            foreach ($data['program_job'] as $key3 => $value3) {
                                $job_id = $value3;
                                //get job details
                                $job_details = $this->JobModel->getOneJob(array('job_id' => $job_id));

                                $description .= $job_details->job_name . " ";

                                $QBO_description[] = $job_details->job_name;
                                $actual_description_for_QBO[] = $job_details->job_description;

                                $where = array(
                                    'property_id' => $value->property_id,
                                    'job_id' => $job_id,
                                    'program_id' => $result,
                                    'customer_id' => $value2->customer_id,
                                );

                                ///////CALCULATE JOB COST

                                //check for price overrides
                                $estimate_price_override = GetOneEstimateJobPriceOverride($where);
                                if ($estimate_price_override) {
                                    $cost = $estimate_price_override->price_override;

                                    $est_coup_param = array(
                                        'cost' => $cost,
                                        'estimate_id' => $estimate_price_override->estimate_id
                                    );

                                    $est_cost = $this->calculateEstimateCouponCost($est_coup_param);

                                } else {
                                    $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $value->property_id, 'program_id' => $result));

                                    if ($priceOverrideData->is_price_override_set == 1) {
                                        $cost = $priceOverrideData->price_override;
                                    } else {
                                        //else no price overrides, then calculate job cost
                                        $lawn_sqf = $value2->yard_square_feet;
                                        $job_price = $job_details->job_price;

                                        //get property difficulty level
                                        if (isset($value2->difficulty_level) && $value2->difficulty_level == 2) {
                                            $difficulty_multiplier = $setting_details->dlmult_2;
                                        } elseif (isset($value2->difficulty_level) && $value2->difficulty_level == 3) {
                                            $difficulty_multiplier = $setting_details->dlmult_3;
                                        } else {
                                            $difficulty_multiplier = $setting_details->dlmult_1;
                                        }

                                        //get base fee
                                        if (isset($job_details->base_fee_override)) {
                                            $base_fee = $job_details->base_fee_override;
                                        } else {
                                            $base_fee = $setting_details->base_service_fee;
                                        }

                                        $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                        //get min. service fee
                                        if (isset($job_details->min_fee_override)) {
                                            $min_fee = $job_details->min_fee_override;
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
                                $total_cost += $cost;
                                $ppjobinv[] = array(
                                    'customer_id' => $value2->customer_id,
                                    'property_id' => $value->property_id,
                                    'program_id' => $result,
                                    'job_id' => $job_id,
                                    'cost' => $cost,
                                );

                                if ($est_cost != 0) {
                                    $job_coup_param = array(
                                        'customer_id' => $value2->customer_id,
                                        'property_id' => $value->property_id,
                                        'program_id' => $result,
                                        'cost' => $est_cost,
                                        'job_id' => $job_id,
                                        'program_id' => $result,
                                        'customer_id' => $value2->customer_id,
                                        'property_id' => $value->property_id,
                                    );

                                    $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                } else {
                                    $job_coup_param = array(
                                        'customer_id' => $value2->customer_id,
                                        'property_id' => $value->property_id,
                                        'program_id' => $result,
                                        'cost' => $cost,
                                        'job_id' => $job_id,
                                        'program_id' => $result,
                                        'customer_id' => $value2->customer_id,
                                        'property_id' => $value->property_id,
                                    );

                                    $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                }
                            } //end foreach job
                            //echo "total ".$total_cost."<br>";

                            //format invoice data
                            $param = array(
                                'customer_id' => $value2->customer_id,
                                'property_id' => $value->property_id,
                                'program_id' => $result,
                                'user_id' => $user_id,
                                'company_id' => $company_id,
                                'invoice_date' => date("Y-m-d"),
                                'description' => $data['program_notes'],
                                'cost' => ($total_cost),
                                'is_created' => 2,
                                'invoice_created' => date("Y-m-d H:i:s"),
                            );
                            //create invoice
                            $invoice_id = $this->INV->createOneInvoice($param);

                            //if invoice id
                            if ($invoice_id) {
                                $param['invoice_id'] = $invoice_id;
                                //figure tax
                                if ($setting_details->is_sales_tax == 1) {
                                    $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $value->property_id));
                                    if ($property_assign_tax) {
                                        foreach ($property_assign_tax as $tax_details) {
                                            $invoice_tax_details = array(
                                                'invoice_id' => $invoice_id,
                                                'tax_name' => $tax_details['tax_name'],
                                                'tax_value' => $tax_details['tax_value'],
                                                'tax_amount' => $total_cost * $tax_details['tax_value'] / 100
                                            );

                                            $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                        }
                                    }
                                }

                                //Quickbooks Invoice **

                                $param['customer_email'] = $cust_details['email'];
                                $param['job_name'] = $description;

                                $QBO_description = implode(', ', $QBO_description);
                                $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);
                                $QBO_param = $param;
                                $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
                                $QBO_param['job_name'] = $QBO_description;

                                $cust_coup_param = array(
                                    'cost' => $QBO_cost,
                                    'customer_id' => $QBO_param['customer_id']
                                );

                                $QBO_param['cost'] = $this->calculateCustomerCouponCost($cust_coup_param);

                                $quickbook_invoice_id = $this->QuickBookInv($QBO_param);
                                //if quickbooks invoice then update invoice table with id
                                if ($quickbook_invoice_id) {
                                    $invoice_id = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('quickbook_invoice_id' => $quickbook_invoice_id));
                                }

                                foreach ($program['properties'] as $propID => $prop) {
                                    if ($propID == $value->property_id) {
                                        foreach ($ppjobinv as $i => $job) {
                                            //		echo "Property Program ID: ".$prop['program_property_id']."</br>";
                                            //		echo "Job ID: ".$job['job_id']."</br>";
                                            //		echo "Invoice ID: ".$invoice_id."</br>";
                                            //	echo "---------<br>";
                                            //store property program job invoice data
                                            $newPPJOBINV = array(
                                                'customer_id' => $job['customer_id'],
                                                'property_id' => $job['property_id'],
                                                'program_id' => $job['program_id'],
                                                'property_program_id' => $prop['program_property_id'],
                                                'job_id' => $job['job_id'],
                                                'invoice_id' => $invoice_id,
                                                'job_cost' => $job['cost'],
                                                'created_at' => date("Y-m-d"),
                                                'updated_at' => date("Y-m-d"),
                                            );

                                            $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);
                                        }
                                    }
                                }

                                // assign coupon if global customer coupon exists
                                $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $value2->customer_id));
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
                                            $params = array(
                                                'coupon_id' => $coupon_id,
                                                'invoice_id' => $invoice_id,
                                                'coupon_code' => $coupon_details->code,
                                                'coupon_amount' => $coupon_details->amount,
                                                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                                'coupon_type' => $coupon_details->type
                                            );
                                            $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                                        }
                                    }
                                }
                            } //end if invoice
                        } //end foreach customer property
                    }
                }
            }

            if ($result) {


                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> added successfully</div>');
                redirect("admin/programList");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> not added.</div>');
                redirect("admin/programList");
            }

            // }

        }
    }

    public function addCopyProgram($programID = NULL)
    {

        if (!empty($programID)) {
            $programID = $programID;
        } else {
            $programID = $this->uri->segment(4);
        }

        $where = array('property_tbl.company_id' => $this->session->userdata['company_id']);
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea(array('company_id' => $this->session->userdata['company_id']));
        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));

        $data['joblist'] = $this->ProgramModel->getJobList(array('company_id' => $this->session->userdata['company_id']));
        $data['propertylist'] = $this->PropertyModel->get_all_list_properties(array('property_tbl.company_id' => $this->session->userdata['company_id'], 'property_tbl.property_status !=' => 0));


        $data['programData'] = $this->ProgramModel->getProgramDetail($programID);

        $selecteddata = $this->ProgramModel->getSelectedJobsAnother($programID);

        // $selecteddataproperty = $this->ProgramModel->getSelectedProperty($programID);
        $data['selectedpropertylist'] = $this->ProgramModel->getSelectedProperty($programID);


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
            foreach ($data['selectedpropertylist'] as $key => $value) {
                #check/remove cancelled properties
                if (isset($value->property_status) && $value->property_status != 0) {
                    $data['selectedproperties'][] = $value->property_id;
                } else {
                    unset($data['selectedpropertylist'][$key]);
                }
            }
        }

        $data['selecteddata'] = $selecteddata;


        $page["active_sidebar"] = "program";
        $page["page_name"] = "Add Copy Program";
        $page["page_content"] = $this->load->view("admin/add_copy_program", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }

    public function addCopyProgramData()
    {
        $data = $this->input->post();

        $program = array();

        // die(print_r($data));
        //Validate Form Data
        $this->form_validation->set_rules('program_name', 'Name', 'required');
        $this->form_validation->set_rules('program_price', 'Price', 'required');
        $this->form_validation->set_rules('program_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('program_job', 'Service', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $this->addCopyProgram();
        } else {

            $data = $this->input->post();
            $user_id = $this->session->userdata['user_id'];
            $company_id = $this->session->userdata['company_id'];
            $where = array('company_id' => $this->session->userdata['company_id']);
            $setting_details = $this->CompanyModel->getOneCompany($where);

            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'program_name' => $data['program_name'],
                'program_price' => $data['program_price'],
                'program_notes' => $data['program_notes']
                //'program_job' => $data['program_job']
            );

            //$check = $this->ProgramModel->checkProgram($param);
            //Create Program
            $result = $this->ProgramModel->insert_program($param);
            //SET PROGRAM ID
            $program['program_id'] = $result;
            if (!empty($data['program_job'])) {
                $n = 1;

                if (!is_array($data['program_job'])) {
                    $data['program_job'] = explode(",", $data['program_job']);
                }

                foreach ($data['program_job'] as $k => $value) {
                    $param2 = array(
                        'program_id' => $result,
                        'job_id' => $value,
                        'priority' => $n
                    );
                    //Assign jobs to program
                    $result1 = $this->ProgramModel->assignProgramJobs($param2);

                    $n++;
                }
            }
            // if properties then assign program to properties
            if (isset($data['propertylistarray']) && !empty($data['propertylistarray'])) {
                $program['properties'] = array();
                foreach (json_decode($data['propertylistarray']) as $value) {

                    $param3 = array(
                        'program_id' => $result,
                        'property_id' => $value->property_id,
                        'price_override' => $value->price_override,
                        'is_price_override_set' => $value->is_price_override_set
                        ///add invoice_id here
                    );
                    //assign program to property
                    $result2 = $this->PropertyModel->assignProgram($param3);
                    $program['properties'][$value->property_id] = array(
                        'program_property_id' => $result2,
                    );

                    // Handle email and text notifications
                    $property = $this->PropertyModel->getOneProperty(array('property_id' => $value->property_id));
                    $customer_id = $this->CustomerModel->getOnecustomerPropert(array('property_id' => $value->property_id));

                    #check customer billing type
                    $checkGroupBilling = $this->CustomerModel->checkGroupBilling($customer_id->customer_id);

                    #if customer billing type = group billing, then we notify the property level contact info
                    if ($checkGroupBilling) {
                        $emaildata['contactData'] = $this->PropertyModel->getGroupBillingByProperty($value->property_id);

                        $emaildata['propertyData'] = $property;
                        $emaildata['programData'] = $this->ProgramModel->getProgramDetail($program['program_id']);
                        $emaildata['assign_date'] = date("Y-m-d H:i:s");

                        $where = array('company_id' => $this->session->userdata['company_id']);
                        $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

                        $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

                        $where['is_smtp'] = 1;
                        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                        $body = $this->load->view('email/group_billing/program_email', $emaildata, true);

                        if (!$company_email_details) {
                            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                        }

                        #send email
                        if ($emaildata['company_email_details']->program_assigned_status == 1 && isset($emaildata['contactData']['email_opt_in']) && $emaildata['contactData']['email_opt_in'] == 1) {
                            $res = Send_Mail_dynamic($company_email_details, $emaildata['contactData']['email'], array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Program Assigned');
                        }

                        #send text
                        if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->program_assigned_status_text == 1 && $emaildata['contactData']['phone_opt_in'] == 1) {

                            $text_res = Send_Text_dynamic($emaildata['contactData']['phone'], $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
                        }

                    } else {

                        $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id' => $customer_id->customer_id));

                        $emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id' => $customer_id->customer_id, 'is_email' => 1, 'program_id' => $result, 'property_id' => $value->property_id));

                        $where = array('company_id' => $this->session->userdata['company_id']);

                        $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

                        $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

                        $emaildata['assign_date'] = date("Y-m-d H:i:s");

                        $where['is_smtp'] = 1;

                        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                        $body = $this->load->view('email/program_email', $emaildata, true);

                        if (!$company_email_details) {
                            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                        }
                        //die(print_r($this->db->last_query()));
                        if ($emaildata['company_email_details']->program_assigned_status == 1) {
                            $res = Send_Mail_dynamic($company_email_details, $emaildata['customerData']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Program Assigned', $emaildata['customerData']->secondary_email);
                        }

                        if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->program_assigned_status_text == 1 && $emaildata['customerData']->is_mobile_text == 1) {
                            //$string = str_replace("{CUSTOMER_NAME}", $emaildata['customerData']->first_name . ' ' . $emaildata['customerData']->last_name,$emaildata['company_email_details']->program_assigned_text);
                            $text_res = Send_Text_dynamic($emaildata['customerData']->phone, $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
                        }
                    }
                    // End Email/Text
                }
            }
            /// if One-Time Program Invoicing, Property and services...
            if ($data['program_price'] == 1 && isset($data['propertylistarray']) && !empty($data['propertylistarray'] && !empty($data['program_job']))) {

                //foreach property
                foreach (json_decode($data['propertylistarray']) as $key => $value) {
                    //create jobs array
                    $ppjobinv = array();
                    //get customer property details
                    $customer_property_details = $this->CustomerModel->getAllproperty(array('customer_property_assign.property_id' => $value->property_id));
                    if ($customer_property_details) {
                        $QBO_description = array();
                        $actual_description_for_QBO = array();
                        $QBO_cost = 0;
                        foreach ($customer_property_details as $key2 => $value2) {
                            //get customer info
                            $cust_details = getOneCustomerInfo(array('customer_id' => $value2->customer_id));
                            $total_cost = 0;
                            $description = "";
                            $est_cost = 0;

                            // foreach program property job... figure cost
                            foreach ($data['program_job'] as $key3 => $value3) {
                                $job_id = $value3;
                                //get job details
                                $job_details = $this->JobModel->getOneJob(array('job_id' => $value3));

                                $description .= $job_details->job_name . " ";

                                $QBO_description[] = $job_details->job_name;
                                $actual_description_for_QBO[] = $job_details->job_description;

                                $where = array(
                                    'property_id' => $value->property_id,
                                    'job_id' => $value3,
                                    'program_id' => $result,
                                    'customer_id' => $value2->customer_id,
                                );

                                $estimate_price_override = GetOneEstimateJobPriceOverride($where);
                                if ($estimate_price_override) {
                                    $cost = $estimate_price_override->price_override;

                                    $est_coup_param = array(
                                        'cost' => $cost,
                                        'estimate_id' => $estimate_price_override->estimate_id
                                    );

                                    $est_cost = $this->calculateEstimateCouponCost($est_coup_param);
                                } else {
                                    $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $value->property_id, 'program_id' => $result));

                                    if ($priceOverrideData->is_price_override_set == 1) {
                                        // $price = $priceOverrideData->price_override;
                                        $cost = $priceOverrideData->price_override;
                                    } else {
                                        //else no price overrides, then calculate job cost
                                        $lawn_sqf = $value2->yard_square_feet;
                                        $job_price = $job_details->job_price;

                                        //get property difficulty level
                                        if (isset($value2->difficulty_level) && $value2->difficulty_level == 2) {
                                            $difficulty_multiplier = $setting_details->dlmult_2;
                                        } elseif (isset($value2->difficulty_level) && $value2->difficulty_level == 3) {
                                            $difficulty_multiplier = $setting_details->dlmult_3;
                                        } else {
                                            $difficulty_multiplier = $setting_details->dlmult_1;
                                        }

                                        //get base fee
                                        if (isset($job_details->base_fee_override)) {
                                            $base_fee = $job_details->base_fee_override;
                                        } else {
                                            $base_fee = $setting_details->base_service_fee;
                                        }

                                        $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                        //get min. service fee
                                        if (isset($job_details->min_fee_override)) {
                                            $min_fee = $job_details->min_fee_override;
                                        } else {
                                            $min_fee = $setting_details->minimum_service_fee;
                                        }

                                        $emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id' => $customer_id->customer_id, 'is_email' => 1, 'program_id' => $result, 'property_id' => $value->property_id));

                                        $where = array('company_id' => $this->session->userdata['company_id']);

                                        $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

                                        $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);
                                        // Compare cost per sf with min service fee
                                        if ($cost_per_sqf > $min_fee) {
                                            $cost = $cost_per_sqf;
                                        } else {
                                            $cost = $min_fee;
                                        }
                                    }
                                }
                                $total_cost += $cost;
                                $ppjobinv[] = array(
                                    'customer_id' => $value2->customer_id,
                                    'property_id' => $value->property_id,
                                    'program_id' => $result,
                                    'job_id' => $value3,
                                    'cost' => $cost,
                                );

                                if ($est_cost != 0) {
                                    $job_coup_param = array(
                                        'customer_id' => $value2->customer_id,
                                        'property_id' => $value->property_id,
                                        'program_id' => $result,
                                        'cost' => $est_cost,
                                        'job_id' => $job_id
                                    );

                                    $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                } else {
                                    $job_coup_param = array(
                                        'customer_id' => $value2->customer_id,
                                        'property_id' => $value->property_id,
                                        'program_id' => $result,
                                        'cost' => $cost,
                                        'job_id' => $job_id
                                    );

                                    $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                }
                            } //end foreach job
                            //echo "total ".$total_cost."<br>";

                            //format invoice data
                            $param = array(
                                'customer_id' => $value2->customer_id,
                                'property_id' => $value->property_id,
                                'program_id' => $result,
                                'user_id' => $user_id,
                                'company_id' => $company_id,
                                'invoice_date' => date("Y-m-d"),
                                'description' => $data['program_notes'],
                                'cost' => ($total_cost),
                                'is_created' => 2,
                                'invoice_created' => date("Y-m-d H:i:s"),
                            );
                            //create invoice
                            $invoice_id = $this->INV->createOneInvoice($param);

                            //if invoice id
                            if ($invoice_id) {
                                $param['invoice_id'] = $invoice_id;
                                //figure tax
                                if ($setting_details->is_sales_tax == 1) {
                                    $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $value->property_id));
                                    if ($property_assign_tax) {
                                        foreach ($property_assign_tax as $tax_details) {
                                            $invoice_tax_details = array(
                                                'invoice_id' => $invoice_id,
                                                'tax_name' => $tax_details['tax_name'],
                                                'tax_value' => $tax_details['tax_value'],
                                                'tax_amount' => $total_cost * $tax_details['tax_value'] / 100
                                            );

                                            $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                        }
                                    }
                                }
                                //Quickbooks Invoice **

                                $param['customer_email'] = $cust_details['email'];
                                $param['job_name'] = $description;

                                $QBO_description2 = implode(', ', $QBO_description);
                                $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);
                                $QBO_param = $param;
                                $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
                                $QBO_param['job_name'] = $QBO_description2;

                                $cust_coup_param = array(
                                    'cost' => $QBO_cost,
                                    'customer_id' => $QBO_param['customer_id']
                                );

                                $QBO_param['cost'] = $this->calculateCustomerCouponCost($cust_coup_param);

                                $quickbook_invoice_id = $this->QuickBookInv($QBO_param);
                                //if quickbooks invoice then update invoice table with id
                                if ($quickbook_invoice_id) {
                                    $invoice_id = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('quickbook_invoice_id' => $quickbook_invoice_id));
                                }


                                foreach ($program['properties'] as $propID => $prop) {
                                    if ($propID == $value->property_id) {
                                        foreach ($ppjobinv as $i => $job) {
                                            //		echo "Property Program ID: ".$prop['program_property_id']."</br>";
                                            //		echo "Job ID: ".$job['job_id']."</br>";
                                            //		echo "Invoice ID: ".$invoice_id."</br>";
                                            //	echo "---------<br>";
                                            //store property program job invoice data
                                            $newPPJOBINV = array(
                                                'customer_id' => $job['customer_id'],
                                                'property_id' => $job['property_id'],
                                                'program_id' => $job['program_id'],
                                                'property_program_id' => $prop['program_property_id'],
                                                'job_id' => $job['job_id'],
                                                'invoice_id' => $invoice_id,
                                                'job_cost' => $job['cost'],
                                                'created_at' => date("Y-m-d"),
                                                'updated_at' => date("Y-m-d"),
                                            );

                                            $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($newPPJOBINV);
                                        }
                                    }
                                }
                            } //end if invoice
                        } //end foreach customer property
                    }
                }
            }

            if ($result) {


                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> added successfully</div>');
                redirect("admin/programList");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> not added.</div>');
                redirect("admin/programList");
            }

            // }

        }
    }

    public function editProgram($programID = NULL)
    {

        if (!empty($programID)) {
            $programID = $programID;
        } else {
            $programID = $this->uri->segment(4);
        }

        $where = array('property_tbl.company_id' => $this->session->userdata['company_id']);
        $data['sales_tax_details'] = $this->SalesTax->getAllSalesTaxArea(array('company_id' => $this->session->userdata['company_id']));
        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));

        $data['joblist'] = $this->ProgramModel->getJobList(array('company_id' => $this->session->userdata['company_id']));
        $data['propertylist'] = $this->PropertyModel->get_all_list_properties($where);

        $data['programData'] = $this->ProgramModel->getProgramDetail($programID);

        $selecteddata = $this->ProgramModel->getSelectedJobsAnother($programID);

        // $selecteddataproperty = $this->ProgramModel->getSelectedProperty($programID);
        $data['selectedpropertylist'] = $this->ProgramModel->getSelectedProperty($programID);


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
                $data['selectedproperties'][] = $value->property_id;
            }
        }

        $data['selecteddata'] = $selecteddata;
        /** Get PriceOverride from estimates table testing **/
        /** Will be replaced by permanent program job price overrides soon **/
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
                $results = $this->EstimateModel->getProgramPropertyJobPriceOverrides($where);
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

        $page["active_sidebar"] = "program";
        $page["page_name"] = "Update Program";
        $page["page_content"] = $this->load->view("admin/edit_program", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }


    public function updateProgram()
    {
        //die('in updateProgram');
        $user_id = $this->session->userdata['user_id'];
        $company_id = $this->session->userdata['company_id'];

        $post_data = $this->input->post();
        $program_id = $this->input->post('program_id');
        // die(print_r($post_data));
        $this->form_validation->set_rules('program_name', 'Name', 'required');
        $this->form_validation->set_rules('program_price', 'Price', 'required');
        $this->form_validation->set_rules('program_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('program_job[]', 'Jobs', 'trim');

        if ($this->form_validation->run() == FALSE) {

            $this->editProgram($program_id);
        } else {

            $where = array('company_id' => $this->session->userdata['company_id']);
            $setting_details = $this->CompanyModel->getOneCompany($where);

            $post_data = $this->input->post();

            $param = array(
                'program_name' => $post_data['program_name'],
                //'program_price' => $post_data['program_price'],
                'program_notes' => $post_data['program_notes'],
                'program_schedule_window' => $post_data['program_schedule_window']
            );

            $result = $this->ProgramModel->updateAdminTbl($program_id, $param);


            // remove this section because no longer allowed to add/remove jobs when updating program
            // $where = array('program_id'=>$program_id);
            // $delete = $this->ProgramModel->deleteAssignJobs($where);
            if (!empty($post_data['program_job'])) {
                $n = 1;

                if (!is_array($post_data['program_job'])) {
                    $post_data['program_job'] = explode(",", $post_data['program_job']);
                }

                foreach ($post_data['program_job'] as $value) {

                    $param2 = array(
                        'program_id' => $program_id,
                        'job_id' => $value,
                        'priority' => $n
                    );
                    // $result1 = $this->ProgramModel->assignProgramJobs($param2);
                    $n++;
                }
            }
            $newProperties = array();
            ///handle properties (currently deletes then reassigns...cant do this because we will lost the property program assign relationship)
            if (isset($post_data['propertylistarray']) && !empty($post_data['propertylistarray'])) {

                foreach (json_decode($post_data['propertylistarray']) as $value) {
                    //check if property is already assigned
                    $checkExists = $this->PropertyModel->getOnePropertyProgram(array('program_id' => $program_id, 'property_id' => $value->property_id));
                    //print_r($checkExists);
                    if ($checkExists) {
                        // echo "EXISTS <br>";
                        //if property program exists, then update
                        $where = array(
                            'property_program_id' => $checkExists->property_program_id,
                        );
                        $update = array(
                            'price_override' => $value->price_override,
                            'is_price_override_set' => $value->is_price_override_set,
                        );
                        $this->PropertyModel->updatePropertyPropgramData($update, $where);
                    } else {
                        // echo "NOT EXISTS <br>";
                        //if property program doesn't exist then create
                        $param3 = array(
                            'property_id' => $value->property_id,
                            'program_id' => $program_id,
                            'price_override' => $value->price_override,
                            'is_price_override_set' => $value->is_price_override_set,
                        );
                        $result2 = $this->PropertyModel->assignProgram($param3);

                        $newProperties[] = array(
                            'property_program_id' => $result2,
                            'property_id' => $value->property_id,
                            'program_id' => $program_id,
                            'price_override' => $value->price_override,
                            'is_price_override_set' => $value->is_price_override_set,
                        );
                        // Handle email and text notifications

                        $property = $this->PropertyModel->getOneProperty(array('property_id' => $value->property_id));
                        $customer_id = $this->CustomerModel->getOnecustomerPropert(array('property_id' => $value->property_id));

                        #check customer billing type
                        $checkGroupBilling = $this->CustomerModel->checkGroupBilling($customer_id->customer_id);

                        #if customer billing type = group billing, then we notify the property level contact info
                        if ($checkGroupBilling) {
                            $emaildata['contactData'] = $this->PropertyModel->getGroupBillingByProperty($value->property_id);

                            $emaildata['propertyData'] = $property;
                            $emaildata['programData'] = $this->ProgramModel->getProgramDetail($program_id);
                            $emaildata['assign_date'] = date("Y-m-d H:i:s");

                            $where = array('company_id' => $this->session->userdata['company_id']);
                            $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

                            $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

                            $where['is_smtp'] = 1;
                            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                            $body = $this->load->view('email/group_billing/program_email', $emaildata, true);

                            if (!$company_email_details) {
                                $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                            }

                            #send email
                            if ($emaildata['company_email_details']->program_assigned_status == 1 && isset($emaildata['contactData']['email_opt_in']) && $emaildata['contactData']['email_opt_in'] == 1) {
                                $res = Send_Mail_dynamic($company_email_details, $emaildata['contactData']['email'], array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Program Assigned');
                            }

                            #send text
                            if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->program_assigned_status_text == 1 && isset($emaildata['contactData']['phone_opt_in']) && $emaildata['contactData']['phone_opt_in'] == 1) {

                                $text_res = Send_Text_dynamic($emaildata['contactData']['phone'], $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
                            }

                        } else {

                            $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id' => $customer_id->customer_id));

                            $emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id' => $customer_id->customer_id, 'is_email' => 1, 'program_id' => $program_id, 'property_id' => $value->property_id));

                            $where = array('company_id' => $this->session->userdata['company_id']);

                            $emaildata['company_details'] = $this->CompanyModel->getOneCompany($where);

                            $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail($where);

                            $emaildata['assign_date'] = date("Y-m-d H:i:s");

                            $where['is_smtp'] = 1;

                            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

                            $body = $this->load->view('email/program_email', $emaildata, true);

                            if (!$company_email_details) {
                                $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                            }
                            //die(print_r($this->db->last_query()));
                            if ($emaildata['company_email_details']->program_assigned_status == 1) {
                                $res = Send_Mail_dynamic($company_email_details, $emaildata['customerData']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Program Assigned', $emaildata['customerData']->secondary_email);
                            }

                            if ($this->session->userdata['is_text_message'] && $emaildata['company_email_details']->program_assigned_status_text == 1 && $emaildata['customerData']->is_mobile_text == 1) {
                                //$string = str_replace("{CUSTOMER_NAME}", $emaildata['customerData']->first_name . ' ' . $emaildata['customerData']->last_name,$emaildata['company_email_details']->program_assigned_text);
                                $text_res = Send_Text_dynamic($emaildata['customerData']->phone, $emaildata['company_email_details']->program_assigned_text, 'Program Assigned');
                            }
                            // End Email/Text
                        }
                    }
                }
            }
            //die(print_r($newProperties));
            /// if One-Time Program Invoicing, Create invoice for new assigned properties...
            $newPPJOBINV = array();
            $inv = array();
            if ($post_data['program_price'] == 1 && isset($newProperties) && !empty($newProperties && !empty($post_data['program_job']))) {
                //foreach property
                $flag = 0;
                foreach ($newProperties as $key => $value) {


                    //get customer property details
                    $customer_property_details = $this->CustomerModel->getAllproperty(array('customer_property_assign.property_id' => $value['property_id']));
                    // die(print_r($customer_property_details));
                    if ($customer_property_details) {


                        $total_cost = 0;
                        $description = "";
                        $est_cost = 0;


                        foreach ($customer_property_details as $key2 => $value2) {

                            //get customer info	
                            $cust_details = getOneCustomerInfo(array('customer_id' => $value2->customer_id));

                            $QBO_description = array();
                            $actual_description_for_QBO = array();
                            $QBO_cost = 0;
                            //foreach program job get cost
                            foreach ($post_data['program_job'] as $key3 => $value3) {
                                $job_id = $value3;
                                //get job details
                                $job_details = $this->JobModel->getOneJob(array('job_id' => $job_id));

                                $description .= $job_details->job_name . " ";

                                $QBO_description[] = $job_details->job_name;
                                $actual_description_for_QBO[] = $job_details->job_description;

                                $where = array(
                                    'property_id' => $value['property_id'],
                                    'job_id' => $job_id,
                                    'program_id' => $program_id,
                                    'customer_id' => $value2->customer_id,
                                );

                                $estimate_price_override = GetOneEstimateJobPriceOverride($where);

                                if ($estimate_price_override) {
                                    $cost = $estimate_price_override->price_override;

                                    $est_coup_param = array(
                                        'cost' => $cost,
                                        'estimate_id' => $estimate_price_override->estimate_id
                                    );

                                    $est_cost = $this->calculateEstimateCouponCost($est_coup_param);

                                } else {
                                    $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $value['property_id'], 'program_id' => $program_id));

                                    if ($priceOverrideData->is_price_override_set == 1) {
                                        // $price = $priceOverrideData->price_override;
                                        $cost = $priceOverrideData->price_override;
                                    } else {
                                        //else no price overrides, then calculate job cost
                                        $lawn_sqf = $value2->yard_square_feet;
                                        $job_price = $job_details->job_price;

                                        //get property difficulty level
                                        if (isset($value2->difficulty_level) && $value2->difficulty_level == 2) {
                                            $difficulty_multiplier = $setting_details->dlmult_2;
                                        } elseif (isset($value2->difficulty_level) && $value2->difficulty_level == 3) {
                                            $difficulty_multiplier = $setting_details->dlmult_3;
                                        } else {
                                            $difficulty_multiplier = $setting_details->dlmult_1;
                                        }

                                        //get base fee
                                        if (isset($job_details->base_fee_override)) {
                                            $base_fee = $job_details->base_fee_override;
                                        } else {
                                            $base_fee = $setting_details->base_service_fee;
                                        }

                                        $cost_per_sqf = $base_fee + ($job_price * $lawn_sqf * $difficulty_multiplier) / 1000;

                                        //get min. service fee
                                        if (isset($job_details->min_fee_override)) {
                                            $min_fee = $job_details->min_fee_override;
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

                                $total_cost += $cost;

                                //store program property job data
                                $newPPJOBINV[] = array(
                                    'property_program_id' => $value['property_program_id'],
                                    'property_id' => $value['property_id'],
                                    'job_id' => $value3,
                                    'program_id' => $program_id,
                                    'customer_id' => $value2->customer_id,
                                    'job_cost' => $cost,
                                    'flag' => $flag,
                                );

                                if ($est_cost != 0) {
                                    $job_coup_param = array(
                                        'cost' => $est_cost,
                                        'job_id' => $job_id,
                                        'property_id' => $value['property_id'],
                                        'program_id' => $program_id,
                                        'customer_id' => $value2->customer_id,
                                    );

                                    $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                } else {
                                    $job_coup_param = array(
                                        'cost' => $cost,
                                        'job_id' => $job_id,
                                        'property_id' => $value['property_id'],
                                        'program_id' => $program_id,
                                        'customer_id' => $value2->customer_id,
                                    );

                                    $QBO_cost += $this->calculateServiceCouponCost($job_coup_param);
                                }
                            } //end foreach job

                            //format invoice data
                            $param = array(
                                'customer_id' => $value2->customer_id,
                                'property_id' => $value['property_id'],
                                'program_id' => $program_id,
                                'user_id' => $user_id,
                                'company_id' => $company_id,
                                'invoice_date' => date("Y-m-d"),
                                'description' => $post_data['program_notes'],
                                'cost' => ($total_cost),
                                'is_created' => 2,
                                'invoice_created' => date("Y-m-d H:i:s"),

                            );
                            //create invoice
                            $invoice_id = $this->INV->createOneInvoice($param);

                            if ($invoice_id) {
                                $param['invoice_id'] = $invoice_id;

                                //figure tax
                                if ($setting_details->is_sales_tax == 1) {
                                    $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $value['property_id']));
                                    if ($property_assign_tax) {
                                        foreach ($property_assign_tax as $tax_details) {
                                            $invoice_tax_details = array(
                                                'invoice_id' => $invoice_id,
                                                'tax_name' => $tax_details['tax_name'],
                                                'tax_value' => $tax_details['tax_value'],
                                                'tax_amount' => $total_cost * $tax_details['tax_value'] / 100
                                            );
                                            $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                                        }
                                    }
                                }
                                //Quickbooks Invoice **

                                $param['customer_email'] = $cust_details['email'];
                                $param['job_name'] = $description;

                                $QBO_description = implode(', ', $QBO_description);
                                $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);
                                $QBO_param = $param;
                                $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
                                $QBO_param['job_name'] = $QBO_description;

                                $cust_coup_param = array(
                                    'cost' => $QBO_cost,
                                    'customer_id' => $QBO_param['customer_id']
                                );

                                $QBO_param['cost'] = $this->calculateCustomerCouponCost($cust_coup_param);

                                $quickbook_invoice_id = $this->QuickBookInv($QBO_param);
                                //if quickbooks invoice then update invoice table with id
                                if ($quickbook_invoice_id) {
                                    $invoice_id = $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('quickbook_invoice_id' => $quickbook_invoice_id));
                                }


                                $inv[$flag] = $invoice_id;

                                // assign coupon if global customer coupon exists
                                $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $value2->customer_id));
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
                                            $params = array(
                                                'coupon_id' => $coupon_id,
                                                'invoice_id' => $invoice_id,
                                                'coupon_code' => $coupon_details->code,
                                                'coupon_amount' => $coupon_details->amount,
                                                'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                                'coupon_type' => $coupon_details->type
                                            );
                                            $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                                        }
                                    }
                                }
                            } //endif invoice
                        }
                    }


                    $flag++;
                }
            }
            // insert PPJOBINV data
            if (is_array($newPPJOBINV) && !empty($inv)) {
                foreach ($newPPJOBINV as $newRow) {
                    if (isset($newRow['flag'])) {
                        $invoiceID = $inv[$newRow['flag']];


                        $PPJOBINV = array(
                            'customer_id' => $newRow['customer_id'],
                            'property_id' => $newRow['property_id'],
                            'program_id' => $newRow['program_id'],
                            'property_program_id' => $newRow['property_program_id'],
                            'job_id' => $newRow['job_id'],
                            'invoice_id' => $invoiceID,
                            'job_cost' => $newRow['job_cost'],
                            'created_at' => date("Y-m-d"),
                            'updated_at' => date("Y-m-d"),
                        );

                        $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($PPJOBINV);
                    }
                }
            }
            //die();

            if (!$result) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                redirect("admin/programList");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong> updated successfully</div>');
                redirect("admin/programList");
            }
        }
    }

    public function programDelete($program_id)
    {
        $where = array('program_id' => $program_id);
        //$result = $this->ProgramModel->deleteProgram($where);

        $param = array(

            'program_active' => 0
        );

        $result = $this->ProgramModel->updateAdminTbl($program_id, $param);


        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("admin/programList");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong>deleted successfully</div>');
            redirect("admin/programList");
        }
    }

    public function programActive($program_id)
    {

        $where = array('program_id' => $program_id);
        //$result = $this->ProgramModel->deleteProgram($where);

        $param = array(

            'program_active' => 1
        );

        $result = $this->ProgramModel->updateAdminTbl($program_id, $param);


        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("admin/programListArchived");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Program </strong>activated successfully</div>');
            redirect("admin/programListArchived");
        }
    }

    /*//////////////////////  Programm Section eND /////////////////////////   */

    /*/////////////////////  Product Section Start ////////////////////////   */

    public function productList()
    {

        $where = array('company_id' => $this->session->userdata['company_id']);

        $data['productData'] = $this->ProductModel->get_all_product($where);
        if (!empty($data['productData'])) {
            foreach ($data['productData'] as $key => $value) {

                $data['productData'][$key]->job_id = $this->ProductModel->getAssignJobs(array('product_id' => $value->product_id));

                // $data['productData'][$key]->ingredients_details =  $this->ProductModel->getAllIngredient(array('product_id' =>$value->product_id));
            }
        }
        $page["active_sidebar"] = "product";
        $page["page_name"] = "Products";
        $page["page_content"] = $this->load->view("admin/product_view", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
    }

    public function addProduct()
    {

        $where = array('company_id' => $this->session->userdata['company_id']);

        $data['joblist'] = $this->ProductModel->getJobList($where);
        $page["active_sidebar"] = "product";
        $page["page_name"] = "Add Product";
        $page["page_content"] = $this->load->view("admin/add_product", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }


    public function addProductData()
    {
        $data = $this->input->post();

        $this->form_validation->set_rules('product_name', 'Name', 'required');
        $this->form_validation->set_rules('epa_reg_nunber', 'EPA Reg Number', 'trim');
        $this->form_validation->set_rules('product_cost', 'Product Cost', 'required');
        $this->form_validation->set_rules('product_cost_per', 'Cost per', 'trim');
        // $this->form_validation->set_rules('formulation', 'Formulation', 'trim');
        // $this->form_validation->set_rules('formulation_per', 'Formulation Per Value', 'trim');
        // $this->form_validation->set_rules('formulation_per_unit', 'Per Unit', 'required');
        $this->form_validation->set_rules('max_wind_speed', 'Wind Speed', 'trim');
        $this->form_validation->set_rules('application_rate', 'Application Rate', 'trim');
        $this->form_validation->set_rules('application_unit', 'Application Rate unit', 'trim');
        $this->form_validation->set_rules('application_per', 'Application Per', 'trim');
        $this->form_validation->set_rules('temperature_information', 'Temperature', 'trim');
        $this->form_validation->set_rules('temperature_unit', 'Temperature Unit', 'required');
        $this->form_validation->set_rules('product_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('assign_job[]', 'Assign to Job', 'trim');
        //$this->form_validation->set_rules('area_of_property_treated[]', 'Area of Property Treated', 'required');
        $this->form_validation->set_rules('weed_pest_prevented', 'Weed Pest Prevented', 'trim');

        if ($this->form_validation->run() == FALSE) {


            $this->addProduct();
        } else {

            //$uID = $userdata['user_id'];

            //print_r($uID);
            $user_id = $this->session->userdata['user_id'];
            $company_id = $this->session->userdata['company_id'];
            $data = $this->input->post();

            @$data["area_of_property_treated"] = implode(',', $data["area_of_property_treated"]);
            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'product_name' => $data['product_name'],
                'epa_reg_nunber' => $data['epa_reg_nunber'],
                'product_cost' => $data['product_cost'],
                'product_cost_per' => $data['product_cost_per'],
                'product_cost_unit' => $data['product_cost_unit'],
                // 'formulation' => $data['formulation'],
                // 'formulation_per' => $data['formulation_per'],
                // 'formulation_per_unit' => $data['formulation_per_unit'],
                'max_wind_speed' => $data['max_wind_speed'],

                'application_rate' => $data['application_rate'],
                'application_unit' => $data['application_unit'],
                'application_per' => $data['application_per'],

                'mixture_application_rate' => $data['mixture_application_rate'],
                'mixture_application_unit' => $data['mixture_application_unit'],
                'mixture_application_per' => $data['mixture_application_per'],

                'temperature_information' => $data['temperature_information'],
                'temperature_unit' => $data['temperature_unit'],
                'product_notes' => $data['product_notes'],
                'weed_pest_prevented' => $data['weed_pest_prevented'],
                'chemical_type' => $data['chemical_type'],
                'restricted_product' => $data['restricted_product'],
                'product_type' => $data['product_type'],
                'application_type' => $data['application_type'],
                're_entry_time' => $data['re_entry_time'],
                'area_of_property_treated' => $data['area_of_property_treated'],
                'application_method' => $data['application_method']
            );

            $where_pro_check = $param;

            $where_pro_check = array_filter($where_pro_check);


            $check = $this->ProductModel->getOneProduct($where_pro_check);


            if ($check) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> Already exists.</div>');
                $this->addProduct();
            } else {


                $result1 = $this->ProductModel->insert_product($param);


                if (!empty($data['active_ingredient'])) {

                    foreach ($data['active_ingredient'] as $key => $value) {

                        if ($value == "" || $data['percent_active_ingredient'][$key] == "") {
                        } else {

                            $this->ProductModel->insertActiveIngredient(array('product_id' => $result1, 'active_ingredient' => $value, 'percent_active_ingredient' => $data['percent_active_ingredient'][$key]));
                        }
                    }
                }


                $count = 0;
                if (isset($data['assign_job'])) {
                    foreach ($data['assign_job'] as $value) {

                        $param2 = array(
                            'job_id' => $value,
                            'product_id' => $result1

                        );
                        $result = $this->ProductModel->assignJobs($param2);

                        $count++;
                    }
                }


                if ($result1) {

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> added successfully</div>');
                    redirect("admin/productList");
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> not added.</div>');
                    redirect("admin/productList");
                }
            }
        }
    }

    public function addProductCsv()
    {


        $filename = $_FILES["csv_file"]["tmp_name"];

        if ($_FILES["csv_file"]["size"] > 0) {

            $row = 1;
            if (($handle = fopen($filename, "r")) !== FALSE) {

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($row == 1) {
                        $row++;
                        continue;
                    }

                    $param = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'company_id' => $this->session->userdata('company_id'),
                        'product_name' => $data[0],
                        'epa_reg_nunber' => $data[1],
                        'product_cost' => $data[2],
                        'product_cost_per' => $data[3],
                        'product_cost_unit' => $data[4],
                        'max_wind_speed' => $data[5],
                        'application_rate' => $data[6],
                        'application_unit' => $data[7],
                        'application_per' => $data[8],
                        'temperature_information' => $data[9],
                        'temperature_unit' => $data[10],
                        'product_notes' => $data[13],
                        'mixture_application_rate' => $data[14],
                        'mixture_application_unit' => $data[15],
                        'mixture_application_per' => $data[16],
                        'product_type' => $data[17],
                        'application_type' => $data[18],
                        're_entry_time' => $data[19],
                        'weed_pest_prevented' => $data[20],
                        'chemical_type' => $data[21],
                        'restricted_product' => $data[22]
                    );


                    $param2 = array(
                        'active_ingredient' => $data[11],
                        'percent_active_ingredient' => $data[12],
                    );
                    $param = array_filter($param);
                    $param2 = array_filter($param2);

                    if (array_key_exists("product_name", $param) && array_key_exists("product_cost", $param)) {

                        $check = $this->ProductModel->getOneProduct($param);

                        if (!$check) {

                            $result = $this->ProductModel->insert_product($param);
                            $param2['product_id'] = $result;
                            if (array_key_exists("active_ingredient", $param2) && array_key_exists("percent_active_ingredient", $param2)) {
                                $this->ProductModel->insertActiveIngredient($param2);
                            }
                        } else {

                            $param2['product_id'] = $check->product_id;

                            if (array_key_exists("active_ingredient", $param2) && array_key_exists("percent_active_ingredient", $param2)) {

                                $this->ProductModel->insertActiveIngredient($param2);
                            }
                        }
                    }
                }


                fclose($handle);

                if (isset($check) && !isset($result)) {
                    echo 0;
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> already exists.</div>');
                    //echo "already he add nahi";
                } else if (!isset($check) && isset($result)) {
                    echo 1;
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> added successfully</div>');
                    //echo "already nahi result he";
                } else if (isset($check) && isset($result)) {
                    echo 3;
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Some Product </strong> already exists and some added</div>');
                } else {
                    echo 4;
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');
                    //echo "swr";
                }
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong> file</strong> can not read please check file.</div>');
            }
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong> Do</strong> not select black file.</div>');
        }


        redirect("admin/productList");
    }

    public function editProduct($productID = NULL)
    {

        if (!empty($productID)) {

            $productID = $productID;
        } else {

            $productID = $this->uri->segment(4);
        }

        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['joblist'] = $this->ProductModel->getJobList($where);
        $data['productData'] = $this->ProductModel->getProductDetail($productID);

        $selecteddata = $this->ProductModel->getSelectedJobs($productID);
        $data['selectedjoblist'] = array();

        if (!empty($selecteddata)) {
            foreach ($selecteddata as $value) {
                $data['selectedjoblist'][] = $value->job_id;
            }
        }

        $data['ingredients_details'] = $this->ProductModel->getAllIngredient(array('product_id' => $productID));

        $page["active_sidebar"] = "product";
        $page["page_name"] = "Update Product";
        $page["page_content"] = $this->load->view("admin/edit_product", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }

    public function updateProduct()
    {

        $post_data = $this->input->post();

        $product_id = $this->input->post('product_id');

        $this->form_validation->set_rules('product_name', 'Name', 'required');
        $this->form_validation->set_rules('epa_reg_nunber', 'EPA Reg Number', 'trim');
        $this->form_validation->set_rules('product_cost', 'Product Cost', 'trim');
        $this->form_validation->set_rules('product_cost_per', 'Cost Per', 'trim');
        // $this->form_validation->set_rules('formulation', 'Formulation', 'trim');
        // $this->form_validation->set_rules('formulation_per', 'Formulation Per Value', 'trim');
        // $this->form_validation->set_rules('formulation_per_unit', 'Per Unit', 'required');
        $this->form_validation->set_rules('max_wind_speed', 'Wind Speed', 'trim');
        $this->form_validation->set_rules('application_rate', 'Application Rate', 'trim');
        $this->form_validation->set_rules('application_unit', 'Application Unit', 'trim');
        $this->form_validation->set_rules('application_per', 'Application Per', 'trim');
        $this->form_validation->set_rules('temperature_information', 'Temperature', 'trim');
        $this->form_validation->set_rules('temperature_unit', 'Temperature Unit', 'required');
        $this->form_validation->set_rules('product_notes', 'Notes', 'trim');
        $this->form_validation->set_rules('assign_job[]', 'Assign to Job', 'trim');
        $this->form_validation->set_rules('weed_pest_prevented', 'Weed Pest Prevented', 'trim');

        if ($this->form_validation->run() == FALSE) {

            $this->addProduct();
        } else {


            $post_data = $this->input->post();
            $post_data["area_of_property_treated"] = implode(',', $post_data["area_of_property_treated"]);

            $param = array(
                'product_name' => $post_data['product_name'],
                'epa_reg_nunber' => $post_data['epa_reg_nunber'],
                'product_cost' => $post_data['product_cost'],
                'product_cost_per' => $post_data['product_cost_per'],
                'product_cost_unit' => $post_data['product_cost_unit'],
                // 'formulation' => $post_data['formulation'],
                // 'formulation_per' => $post_data['formulation_per'],
                // 'formulation_per_unit' => $post_data['formulation_per_unit'],
                'max_wind_speed' => $post_data['max_wind_speed'],

                'application_rate' => $post_data['application_rate'],
                'application_unit' => $post_data['application_unit'],
                'application_per' => $post_data['application_per'],
                'mixture_application_rate' => $post_data['mixture_application_rate'],
                'mixture_application_unit' => $post_data['mixture_application_unit'],
                'mixture_application_per' => $post_data['mixture_application_per'],

                'temperature_information' => $post_data['temperature_information'],
                'temperature_unit' => $post_data['temperature_unit'],

                'product_notes' => $post_data['product_notes'],
                'weed_pest_prevented' => $post_data['weed_pest_prevented'],
                'chemical_type' => $post_data['chemical_type'],
                'restricted_product' => $post_data['restricted_product'],
                'product_type' => $post_data['product_type'],
                'application_type' => $post_data['application_type'],
                're_entry_time' => $post_data['re_entry_time'],
                'area_of_property_treated' => $post_data["area_of_property_treated"],
                'application_method' => $post_data["application_method"]

            );


            $where_pro_check = $param;

            $where_pro_check = array_filter($where_pro_check);

            $where_pro_check['product_id !='] = $product_id;


            $check = $this->ProductModel->getOneProduct($where_pro_check);


            if ($check) {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> Already exists.</div>');
                redirect("admin/productList");
            } else {

                $post_data['updated_at'] = date("Y-m-d H:i:s");

                $result = $this->ProductModel->updateAdminTbl($product_id, $param);

                $where = array('product_id' => $product_id);
                $delete = $this->ProductModel->deleteAssignJobs($where);

                $count = 0;
                if (!empty($post_data['assign_job'])) {

                    foreach ($post_data['assign_job'] as $value) {

                        $param2 = array(
                            'job_id' => $value,
                            'product_id' => $product_id

                        );
                        $result = $this->ProductModel->assignJobs($param2);

                        $count++;
                    }
                }


                $delete = $this->ProductModel->deleteActiveIngredient($where);
                if (!empty($post_data['active_ingredient'])) {
                    foreach ($post_data['active_ingredient'] as $key => $value) {
                        if ($value == "" || $post_data['percent_active_ingredient'][$key] == "") {
                        } else {

                            $this->ProductModel->insertActiveIngredient(array('product_id' => $product_id, 'active_ingredient' => $value, 'percent_active_ingredient' => $post_data['percent_active_ingredient'][$key]));
                        }
                    }
                }

                if (!$result) {

                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

                    redirect("admin/productList");
                } else {

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong> updated successfully</div>');
                    redirect("admin/productList");
                }
            }
        }
    }

    public function productDelete($productid)
    {

        $where = array('product_id' => $productid);
        $result = $this->ProductModel->deleteProduct($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("admin/productList");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Product </strong>deleted successfully</div>');
            redirect("admin/productList");
        }
    }

    /*////////////////////////  Product Section End ////////////////////   */

    /*///////////////////////   Ajax Code           ///////////////////    */

    public function productListAjax()
    {
        $where = array('company_id' => $this->session->userdata['company_id']);
        $productData = $this->ProductModel->get_all_product($where);
        if (!empty($productData)) {

            foreach ($productData as $value) {
                echo '<option value="' . $value->product_id . '">' . $value->product_name . '</option>';
            }
        }
    }

    public function propertyListAjax()
    {

        $selected_ids = array();
        $selectedPropertiesids = array();
        $current_added_id = '';


        if ($this->input->post()) {

            $proertyPriceOverRide = json_decode($this->input->post('proertyPriceOverRide'));


            if (!empty($proertyPriceOverRide)) {

                $selected_ids = array_map(function ($e) {
                    return is_object($e) ? $e->property_id : $e['property_id'];
                }, $proertyPriceOverRide);
            }

            if (!empty($this->input->post('selectedProperties'))) {
                $selectedPropertiesids = $this->input->post('selectedProperties');
            }

            $current_added_id = $this->input->post('current_added_id');
        }
        //  print_r($selectedPropertiesids);


        $where = array('property_tbl.company_id' => $this->session->userdata['company_id']);
        $propertyData = $this->PropertyModel->get_all_property($where);
        if (!empty($propertyData)) {

            foreach ($propertyData as $value) {

                if (in_array($value->property_id, $selected_ids)) {
                    $select1 = 'selected';
                } else {
                    $select1 = '';
                }

                if (in_array($value->property_id, $selectedPropertiesids)) {
                    $select2 = 'selected';
                    //      echo "he".$value->property_id;
                } else {
                    $select2 = '';
                    //    echo "no".$value->property_id;

                }

                if ($value->property_id == $current_added_id) {
                    $select3 = 'selected';
                } else {
                    $select3 = '';
                }

                if ($select1 == 'selected' || $select2 == 'selected' || $select3 == 'selected') {
                    $select = 'selected';
                } else {
                    $select = '';
                }


                echo '<option value="' . $value->property_id . '" ' . $select . ' >' . $value->property_title . '</option>';
            }
        }
    }

    public function propertyListAjaxSelctedByCustomer($customer_id)
    {
        $where = array('property_tbl.company_id' => $this->session->userdata['company_id']);

        $propertyData = $this->PropertyModel->get_all_property($where);

        $selecteddata = $this->CustomerModel->getSelectedProperty($customer_id);

        $selectedpropertylist = array();
        if (!empty($selecteddata)) {
            foreach ($selecteddata as $value) {
                $selectedpropertylist[] = $value->property_id;
            }
        }

        if (!empty($propertyData)) {

            foreach ($propertyData as $value) { ?>

                <option
                    value="<?php echo $value->property_id; ?>" <?php if (in_array($value->property_id, $selectedpropertylist)) { ?>
                    selected <?php } ?>> <?php echo $value->property_title; ?> </option>

            <?php }
        }
    }

    public function customerListAjax()
    {
        $where = array('company_id' => $this->session->userdata['company_id']);
        $customerData = $this->CustomerModel->get_all_customer($where);
        if (!empty($customerData)) {

            foreach ($customerData as $value) {
                echo '<option value="' . $value->customer_id . '" title="' . $value->billing_street . '"  >' . $value->first_name . ' ' . $value->last_name . '</option>';
            }
        }
    }

    public function programListAjax()
    {

        $selected_ids = array();

        if ($this->input->post()) {

            $programPriceOverRide = json_decode($this->input->post('programPriceOverRide'));


            if (!empty($programPriceOverRide)) {

                $selected_ids = array_map(function ($e) {
                    return is_object($e) ? $e->program_id : $e['program_id'];
                }, $programPriceOverRide);
            }
        }


        $where = array('company_id' => $this->session->userdata['company_id']);

        $programData = $this->ProgramModel->get_all_program($where);
        if (!empty($programData)) {

            foreach ($programData as $value) {

                if (in_array($value->program_id, $selected_ids)) {
                    $select = 'selected';
                } else {
                    $select = '';
                }

                echo '<option value="' . $value->program_id . '" ' . $select . ' >' . $value->program_name . '</option>';
            }
        }
    }

    public function HelpMessagesend($value = '')
    {
        $data = $this->input->post();

        if (trim($data['message']) != '') {

            $where = array(
                'company_id' => $this->session->userdata['company_id'],
                'is_smtp' => 1
            );

            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
            if (!$company_email_details) {

                $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
            }


            $body = $this->session->userdata['user_first_name'] . ' ' . $this->session->userdata['user_last_name'] . ' sent you help message :<br>' . trim($data['message']);

            $res = Send_Mail_dynamic($company_email_details, helpEmailTo, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Get Help From Spraye Web Admin Pannel');

            if ($res['status']) {

                $param = array(
                    'company_id' => $this->session->userdata['company_id'],
                    'user_id' => $this->session->userdata['user_id'],
                    'message' => trim($data['message']),
                    'create_at ' => date("Y-m-d H:i:s"),
                );

                $result = $this->HelpMessage->CreateOneHelpMessage($param);

                if ($result) {
                    $return_array = array('status' => 200, 'msg' => 'Help message sent successfully.', 'result' => $result);
                } else {
                    $return_array = array('status' => 400, 'msg' => 'Something went wrong', 'result' => array());
                }
            } else {
                $return_array = array('status' => 400, 'msg' => $res['message'], 'result' => array());
            }
        } else {

            $return_array = array('status' => 400, 'msg' => 'We are unable to send empty message', 'result' => array());
        }
        echo json_encode($return_array);
    }


    public function dataTableManage()
    {
        $data = $this->input->post();


        $where = array(
            'company_id' => $this->session->userdata['company_id'],
            'table_name' => $data['table_name']
        );

        $updatearr = array(
            'company_id' => $this->session->userdata['company_id'],
            'table_name' => $data['table_name']
        );

        if (array_key_exists('colmn_id', $data)) {
            $updatearr['colmn_id'] = $data['colmn_id'];
        }

        if (array_key_exists('colmn_order', $data)) {
            $updatearr['colmn_order'] = $data['colmn_order'];
        }

        if (array_key_exists('page_lenght', $data)) {
            $updatearr['page_lenght'] = $data['page_lenght'];
        }


        $chek = $this->DataTableModel->getOneOneDataTable($where);

        if ($chek) {

            $res = $this->DataTableModel->updateOneDataTable($where, $updatearr);
        } else {

            $res = $this->DataTableModel->CreateOneDataTable($updatearr);
        }

        if ($res) {
            $return_array = array('status' => 200, 'msg' => 'successfully', 'result' => array());
        } else {

            $return_array = array('status' => 400, 'msg' => 'somthing went wrong', 'result' => array());
        }

        echo json_encode($return_array);
    }

    function getLatLongByAddress($address)
    {
        // $address = str_replace(", ",",+",$address);
        // $address = str_replace(" ","%",$address);


        $address = urlencode($address);
        // 1017%Davis%Boulevard+Sikeston+MO+USA
        // die();

        $geocode = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=" . GoogleMapKey . "&address={$address}&sensor=false");

        $output = json_decode($geocode);

        if (!empty($output->results[0]->geometry->location->lat)) {

            $geolocation = array(
                'lat' => $output->results[0]->geometry->location->lat,
                'long' => $output->results[0]->geometry->location->lng
            );
            return $geolocation;
        } else {

            return false;
        }
    }

    function getLatLongByAddress2($address)
    {
        $address = urlencode($address);
        $url = "https://maps.google.com/maps/api/geocode/json?key=" . GoogleMapKey . "&address={$address}&sensor=false";
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
        ]);

        $data = curl_exec($curl);

        curl_close($curl);
        $output = json_decode($data);
        if (!empty($output->results[0]->geometry->location->lat)) {

            $geolocation = array(
                'lat' => $output->results[0]->geometry->location->lat,
                'long' => $output->results[0]->geometry->location->lng
            );
            return $geolocation;
        } else {

            return false;
        }
    }


    public function createCustomerInQuickbook($param)
    {

        $company_details = $this->checkQuickbook();

        if ($company_details) {


            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $company_details->quickbook_client_id,
                'ClientSecret' => $company_details->quickbook_client_secret,
                'accessTokenKey' => $company_details->access_token_key,
                'refreshTokenKey' => $company_details->refresh_token_key,
                'QBORealmID' => $company_details->qbo_realm_id,
                'baseUrl' => "Production"
            ));

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");


            // Add a customer


            $cust_email = isset($param['email']) ? trim($param['email']) : '';

            $quickbook_customer_id_check = $this->custCheckInQuickBook($dataService, $cust_email);


            if ($quickbook_customer_id_check) {

                return array('status' => 201, 'msg' => 'customer added successfully', 'result' => $quickbook_customer_id_check);
            } else {

                // Add a customer
                $customerObj = Customer::create([

                    "BillAddr" => [
                        "Line1" => trim($param['billing_street']),
                        "City" => trim($param['billing_city']),
                        "Country" => "",
                        "CountrySubDivisionCode" => "",
                        "PostalCode" => trim($param['billing_zipcode'])
                    ],
                    "Notes" => "",
                    "Title" => "",
                    "GivenName" => trim($param['first_name']),
                    "MiddleName" => "",
                    "FamilyName" => trim($param['last_name']),
                    "Suffix" => "",
                    "FullyQualifiedName" => trim($param['first_name']) . ' ' . trim($param['last_name']),
                    "CompanyName" => isset($param['customer_company_name']) ? trim($param['customer_company_name']) : '',
                    "DisplayName" => trim($param['first_name']) . ' ' . trim($param['last_name']),
                    "PrimaryPhone" => [
                        "FreeFormNumber" => isset($param['phone']) ? trim($param['phone']) : ''
                    ],
                    "PrimaryEmailAddr" => [
                        "Address" => isset($param['email']) ? trim($param['email']) : ''
                    ]


                ]);

                $resultingCustomerObj = $dataService->Add($customerObj);
                $error = $dataService->getLastError();
                if ($error) {
                    $return_error = '';
                    $return_error = "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                    return array('status' => 400, 'msg' => 'customer not added', 'result' => $return_error);
                } else {

                    return array('status' => 201, 'msg' => 'customer added successfully', 'result' => $resultingCustomerObj->Id);
                }
            }
        } else {

            return array('status' => 400, 'msg' => 'please intigrate quickbook account', 'result' => '');
        }
    }


    public function custCheckInQuickBook($dataService, $email = '')
    {

        if ($email != '') {

            try {

                $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

                $entities = $dataService->Query("SELECT * FROM Customer where PrimaryEmailAddr ='" . $email . "'");
                $error = $dataService->getLastError();

                if ($error) {

                    $return_error = '';
                    $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";
                    return false;
                } else {

                    if (!empty($entities)) {
                        return $entities[0]->Id;
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


    public function updatCustomerInQickbook($quickbook_customer_id, $param)
    {

        $company_details = $this->checkQuickbook();
        if ($company_details) {

            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $company_details->quickbook_client_id,
                'ClientSecret' => $company_details->quickbook_client_secret,
                'accessTokenKey' => $company_details->access_token_key,
                'refreshTokenKey' => $company_details->refresh_token_key,
                'QBORealmID' => $company_details->qbo_realm_id,
                'baseUrl' => "Production"
            ));

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

            $entities = $dataService->Query("SELECT * FROM Customer where Id='" . $quickbook_customer_id . "'");
            $error = $dataService->getLastError();
            if ($error) {
                $return_error = '';
                $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                return array('status' => 400, 'msg' => 'auth failed', 'result' => $return_error);
            } else {

                if (!empty($entities)) {

                    $theCustomer = reset($entities);

                    // var_dump($theCustomer);

                    $updateCustomer = Customer::update($theCustomer, [
                        //If you are going to do a full Update, set sparse to false


                        "BillAddr" => [
                            "Line1" => trim($param['billing_street']),
                            "City" => trim($param['billing_city']),
                            "Country" => "",
                            "CountrySubDivisionCode" => "",
                            "PostalCode" => trim($param['billing_zipcode'])
                        ],
                        "Notes" => "",
                        "Title" => "",
                        "GivenName" => trim($param['first_name']),
                        "MiddleName" => "",
                        "FamilyName" => trim($param['last_name']),
                        "Suffix" => "",
                        "FullyQualifiedName" => trim($param['first_name']) . ' ' . trim($param['last_name']),
                        "CompanyName" => isset($param['customer_company_name']) ? trim($param['customer_company_name']) : '',
                        "DisplayName" => trim($param['first_name']) . ' ' . trim($param['last_name']),
                        "PrimaryPhone" => [
                            "FreeFormNumber" => isset($param['phone']) ? trim($param['phone']) : ''
                        ],
                        "PrimaryEmailAddr" => [
                            "Address" => isset($param['email']) ? trim($param['email']) : ''
                        ]

                    ]);


                    $resultingCustomerUpdatedObj = $dataService->Update($updateCustomer);

                    if ($error) {
                        $return_error = '';
                        $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                        $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                        $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                        return array('status' => 400, 'msg' => 'customer not added', 'result' => $return_error);
                    } else {

                        $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingCustomerUpdatedObj, $urlResource);

                        return array('status' => 200, 'msg' => 'customer update successfully', 'result' => '');
                    }
                } else {

                    return array('status' => 404, 'msg' => 'customer not found', 'result' => '');
                }
            }
        } else {

            return array('status' => 400, 'msg' => 'please intigrate quickbook account', 'result' => '');
        }
    }


    public function getOneQuickBookCustomer($quickbook_customer_id)
    {

        $company_details = $this->checkQuickbook();
        if ($company_details) {

            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $company_details->quickbook_client_id,
                'ClientSecret' => $company_details->quickbook_client_secret,
                'accessTokenKey' => $company_details->access_token_key,
                'refreshTokenKey' => $company_details->refresh_token_key,
                'QBORealmID' => $company_details->qbo_realm_id,
                'baseUrl' => "Production"
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
        } else {

            return false;
        }
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


    public function createInvoiceInQuickBook($param)
    {


        $company_details = $this->checkQuickbook();

        if ($company_details) {

            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $company_details->quickbook_client_id,
                'ClientSecret' => $company_details->quickbook_client_secret,
                'accessTokenKey' => $company_details->access_token_key,
                'refreshTokenKey' => $company_details->refresh_token_key,
                'QBORealmID' => $company_details->qbo_realm_id,
                'baseUrl' => "Production"
            ));

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

            $dataService->throwExceptionOnError(true);
            //Add a new Invoice

            // var_dump($param);
            // die();

            $details = getVisIpAddr();

            $all_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $param['invoice_id']));

            $description = 'Service Name: ' . $param['job_name'] . '. Service Description: ' . $param['actual_description_for_QBO'];

            $line_ar[] = array(
                "Description" => $description,
                "Amount" => $param['cost'],
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => array(
                    "TaxCodeRef" => array(
                        "value" => $details->geoplugin_countryCode == 'IN' ? 3 : 'TAX'
                        // "value" =>  'TAX'
                    )
                )
            );

            if ($all_sales_tax) {

                foreach ($all_sales_tax as $key => $value) {
                    $line_ar[] = array(
                        "Description" => 'Sales Tax: ' . $value['tax_name'] . ' (' . floatval($value['tax_value']) . '%) ',
                        "Amount" => $value['tax_amount'],
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => array(
                            "TaxCodeRef" => array(
                                "value" => $details->geoplugin_countryCode == 'IN' ? 3 : 'TAX'
                                // "value" =>  'TAX'
                            )
                        )
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
                )
            );

            if (isset($param['email']) && $param['email'] != '') {

                $invoice_arr['BillEmail'] = array(
                    "Address" => $param['email']
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
                return array('status' => 400, 'msg' => 'Invoice not added successfully', 'result' => $return_error);
            } else {

                return array('status' => 201, 'msg' => 'Invoice added successfully', 'result' => $resultingObj->Id);
            }
        } else {

            return array('status' => 400, 'msg' => 'please intigrate quickbook account', 'result' => '');
        }
    }


    public function checkQuickbook()
    {
        $where = array(
            'company_id' => $this->session->userdata['company_id'],
            'is_quickbook' => 1,
            'quickbook_status' => 1
        );

        $company_details = $this->CompanyModel->getOneCompany($where);

        if ($company_details) {


            try {


                $oauth2LoginHelper = new OAuth2LoginHelper($company_details->quickbook_client_id, $company_details->quickbook_client_secret);  // clint id , clint sceter
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


    public function getWeatherInfo($lat, $long)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            //CURLOPT_URL => "https://api.darksky.net/forecast/" . DarkApiKey . "/" . $lat . "," . $long,
            CURLOPT_URL => "https://weatherkit.apple.com/api/v1/weather/en_US/".$lat."/".$long."?dataSets=currentWeather,forecastDaily",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.WeatherKitKey,
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($err) {

            return array('status' => 400, 'message' => $err, 'result' => array());
        } else {

            $result = json_decode($response);

            switch ($http_code) {

                case 200:  # OK

                    return array('status' => 200, 'message' => 'successfully', 'result' => $result);

                    break;

                case 403:

                    return array('status' => 400, 'message' => $result->error, 'result' => array());

                    break;

                default:
                    return array('status' => 400, 'message' => 'Unexpected HTTP code: ', $http_code, 'result' => array());
            }
        }
    }

    public function ajaxGetRoutes()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'technician_job_assign_id',
            2 => 'tech_name',
            3 => 'route_id',
            4 => 'route_name',
            5 => 'yard_square_feet',
            6 => 'job_assign_date',
            7 => 'property_title',
            8 => 'property_address',
            9 => 'property_type',
            10 => 'pre_service_notification',
            11 => 'property_notes',
            12 => 'front_yard_grass',
            13 => 'front_yard_square_ft',
            14 => 'back_yard_grass',
            15 => 'back_yard_square_ft',
            16 => 'program_name',
            17 => 'program_schedule_window',
            18 => 'tags',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            //'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'is_job_mode' => 0,
            // 'technician_job_assign.technician_id' => 1,
            //'property_status' => 1
        );

        $data = array();

        // seardch through each col, and if a search value, let's search for it
        $where_like = array();
        $tech_name = '';
        if (is_array($this->input->post('columns'))) {
            $columns = $this->input->post('columns');
            foreach ($columns as $column) {
                if (isset($column['search']['value']) && !empty($column['search']['value'])) {
                    if ($column['data'] == 'tech_name') {
                        $tech_name = $column['search']['value'];
                    } else {
                        $col = $column['data'];
                        $val = $column['search']['value'];
                        if ($col == "tags") {
                            $tag = $this->TagsModel->getOneTagStrartWith($val);
                            if ($tag != null) {
                                $where_like[$col] = $tag->id;
                            } else {
                                $where_like[$col] = $val;
                            }
                        } else {
                            $where_like[$col] = $val;
                        }
                    }
                }
            }
        }
        // get data (2 separate fns for search and non search)
        if ($this->input->post('search')['value'] == '') {
            //  die('empty search');
            $tempdata = $this->DashboardModel->getTableRouteDataAjax($where, $where_like, $limit, $start, $order, $dir, false, $tech_name);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableRouteDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {
            // die(' search');
            $search = $this->input->post('search')['value'];

            $tempdata = $this->DashboardModel->getTableRouteDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false, $search);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableRouteDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true, $search);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
            //$var_last_query = $this->db->last_query ();
            // return die("last query runs ". $var_last_query);
        }

        // $var_last_query = $this->db->last_query();
        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                // $generate_row = true;
                $arrayName = array(
                    'company_id' => $value->company_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $value->property_id,
                );
                $assign_table_data = $this->Tech->GetOneRow($arrayName); // select * from technician_job_assign where __
                $value->mode = '';
                $value->reschedule_message = '';
                $concat_is_rescheduled = 0;
                if ($assign_table_data) {
                    if ($assign_table_data->is_job_mode == 2) {
                        $concat_is_rescheduled = 2;
                        $value->mode = 'Rescheduled';
                        $value->reschedule_message = $assign_table_data->reschedule_message;
                    }
                }

                // set property type
                switch ($value->property_type) {
                    case 'Commercial':
                        $value->property_type = 'Commercial';
                        break;
                    case 'Residential':
                        $value->property_type = 'Residential';
                        break;

                    default:
                        $value->property_type = 'Commercial';
                        break;
                }

                // if no resschedule message, set default
                // if ($value->is_job_mode == 2) {
                //   $concat_is_rescheduled = 2;
                // if (empty($value->reschedule_message)) {
                //   $value->reschedule_message = "Unassigned by System";
                //}
                //} else {
                //  $value->reschedule_message = '';
                //}
                // $data['tech_name'] = $value->tech_name;
                // set row data
                $data[$i]['checkbox'] = "<input  name='group_id' route_ids='$value->route_id' technician_job_assign_ids='$value->technician_job_assign_id' type='checkbox' value='$i' data-realvalue='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox' />";
                //$data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' value='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox' />";
                $data[$i]['technician_job_assign_id'] = $value->technician_job_assign_id;
                // $data[$i]['technician_id']= $value->technician_id;
                $data[$i]['tech_name'] = $value->tech_name;
                // $data[$i]['user_last_name']= $value->user_last_name;
                $data[$i]['route_id'] = $value->route_id;
                $data[$i]['route_name'] = $value->route_name;
                $data[$i]['yard_square_feet'] = $value->yard_square_feet;
                $data[$i]['job_assign_date'] = $value->job_assign_date;
                $data[$i]['property_title'] = $value->property_title;
                $data[$i]['property_address'] = $value->property_address;
                $tags_list = "";
                $tags_list_array = [];
                if ($value->tags != null && !empty($value->tags)) {
                    $id_list = $value->tags;
                    $id_list_array = explode(',', $id_list);
                    foreach ($id_list_array as $tag) {
                        $where_arr = array(
                            'id' => $tag
                        );
                        $tag = $this->TagsModel->getOneTag($where_arr);
                        if ($tag != null) {
                            $tags_list_array[] = $tag->tags_title;
                        }
                    }
                }
                if (count($tags_list_array) > 0) {
                    $data[$i]['tags'] = implode(',', $tags_list_array);
                } else {
                    $data[$i]['tags'] = $tags_list_array;
                }
                $data[$i]['property_type'] = $value->property_type;

                //customer notification flags
                $notify_array = $value->pre_service_notification ? json_decode($value->pre_service_notification) : [];
                $data[$i]['pre_service_notification'] = "";
                if (is_array($notify_array) && in_array(1, $notify_array)) {
                    $data[$i]['pre_service_notification'] = "<div class='label label-primary myspan m-y-1' style=' padding: 0 2px; margin-right: 0.5rem'>Call</div> ";
                }
                if (is_array($notify_array) && in_array(4, $notify_array)) {
                    $data[$i]['pre_service_notification'] .= "<div class='label label-success myspan' style=' padding: 0 2px; margin-right: 0.5rem'>Text ETA</div>";
                }
                if (is_array($notify_array) && (in_array(2, $notify_array) || in_array(3, $notify_array))) {
                    $data[$i]['pre_service_notification'] .= "<div class='label label-info myspan' style=' padding: 0 2px; margin-right: 0.5rem'>Pre-Notified</div>";
                }

                $data[$i]['property_notes'] = $value->property_notes;
                $data[$i]['front_yard_grass'] = $value->front_yard_grass;
                $data[$i]['front_yard_square_ft'] = $value->front_yard_square_feet;
                $data[$i]['back_yard_grass'] = $value->back_yard_grass;
                $data[$i]['back_yard_square_ft'] = $value->back_yard_square_feet;
                $data[$i]['program_name'] = $value->program_name;
                $data[$i]['program_schedule_window'] = $value->program_schedule_window;
                $data[$i]['action'] = "<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='unassigned-services-element confirm_delete_unassign_job button-next' grd_ids='$value->company_id'  ><i class='icon-trash position-center' style='color: #9a9797;'></i></a></li></ul>";

                // easy way to console log out
                // $data[$i]['note'] = json_encode($_POST);
                // $data[$i]['note'] = json_encode($this->input->post('columns')[1]['search']['value']);
                // $data[$i]['note'] = json_encode($where);
                // $data[$i]['note'] = $value->is_job_mode.'-'.$value->unassigned_Job_delete_id;
                // $data[$i]['note'] = $var_last_query;

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

    public function availableRoutes()
    {
        $ajax_call = $this->input->get('ajax-call');
        $company_id = $this->session->userdata['company_id'];
        $page["active_sidebar"] = "available_routes";
        $page["page_name"] = "Scheduled Routes";
        $data['tecnician_details'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));

        $page["page_content"] = $this->load->view("admin/available_routes", $data, TRUE);

        $this->layout->superAdminTemplateTable($page);
    }

    /*//////////////////////// Ajax Code End Here  ///////////// */

    ##### ADDED 2/22/22 (RG) #####
    public function prospectProperty()
    {
        $company_id = $this->session->userdata['company_id'];
        $where = array('property_tbl.company_id' => $company_id, 'property_status' => '2');

        $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

        $data['prospects'] = $this->PropertyModel->getAllProspectsProperty($where);

        // die(print_r($data['prospects']));
        if (!empty($data['prospects'])) {
            foreach ($data['prospects'] as $key => $value) {

                $data['prospects'][$key]->customer_id = $this->PropertyModel->getAllcustomer(array('property_id' => $value->property_id));
            }

            foreach ($data['prospects'] as $key => $value) {

                $data['prospects'][$key]->program_id = $this->PropertyModel->getAllprogram(array('property_id' => $value->property_id));
            }
        }
        $data['source_list'] = $this->SourceModel->getAllSource(array('company_id' => $this->session->userdata['company_id']));
        $data['users'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
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
        // die(print_r($data['prospects']));
        $where = array('company_id' => $company_id);
        $data['programlist'] = $this->PropertyModel->getProgramList($where);

        $data['servicelist'] = $this->JobModel->getJobList($where);

        $page["active_sidebar"] = "prospect";
        $page["page_name"] = "Prospects";
        $page["page_content"] = $this->load->view("admin/prospect_view", $data, TRUE);
        $this->layout->superAdminTemplateTable($page);
        // $ajax_call = $this->input->get('ajax-call');
        // $company_id = $this->session->userdata['company_id'];
        // $page["active_sidebar"] = "prospect";
        // $page["page_name"] = "Prospect Properties";
        // $data['tecnician_details'] = $this->Administrator->getAllAdmin(array('company_id' => $this->session->userdata['company_id']));
        // $page["page_content"] = $this->load->view("admin/prospect_view", $data, TRUE);
        // $this->layout->superAdminTemplateTable($page);
    }

    public function prospectDelete($property_id)
    {

        $where = array('property_id' => $property_id);
        $result = $this->PropertyModel->deleteProperty($where);

        if (!$result) {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');

            redirect("admin/prospectProperty");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> deleted successfully</div>');
            redirect("admin/prospectProperty");
        }
    }

    public function deletemultipleProspects($value = '')
    {
        $properties = $this->input->post('properties');
        if (!empty($properties)) {
            foreach ($properties as $key => $value) {
                $where = array('property_id' => $value);
                $result = $this->PropertyModel->deleteProperty($where);
            }
            echo 1;
        } else {
            echo 0;
        }
    }

    public function ajaxGetProspects()
    {
        $tblColumns = array(
            0 => 'checkbox',
            1 => 'priority',
            2 => 'job_name',
            3 => 'customers.first_name',
            4 => 'property_title',
            5 => '`property_tbl`.`yard_square_feet`',
            6 => 'completed_date_property',
            7 => 'completed_date_property_program',
            8 => 'property_address',
            9 => 'property_type',
            10 => 'category_area_name',
            11 => 'program_name',
            12 => 'reschedule_message',
            13 => 'action'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status' => 1,
            'property_status' => 1,
            'job_name' => 'Sales Visit',
            // 'prospect_status' => 1
        );

        $data = array();
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
            $tempdata = $this->DashboardModel->getTableProspectDataAjax($where, $where_like, $limit, $start, $order, $dir, false);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableProspectDataAjax($where, $where_like, $limit, $start, $order, $dir, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);


        } else {
            $search = $this->input->post('search')['value'];
            $tempdata = $this->DashboardModel->getTableProspectDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false);
            $var_total_item_count_for_pagination = $this->DashboardModel->getTableProspectDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        $var_last_query = $this->db->last_query();
        // die(print_r($var_last_query));

        if (!empty($tempdata)) {
            $i = 0;

            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                // $generate_row = true;
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $value->property_id,
                );
                $assign_table_data = $this->Tech->GetOneRow($arrayName); // select * from technician_job_assign where __
                $value->mode = '';
                $value->reschedule_message = '';
                $concat_is_rescheduled = 0;
                if ($assign_table_data) {
                    if ($assign_table_data->is_job_mode == 2) {
                        $concat_is_rescheduled = 2;
                        $value->mode = 'Rescheduled';
                        $value->reschedule_message = $assign_table_data->reschedule_message;
                    }
                }

                // set property type
                switch ($value->property_type) {
                    case 'Commercial':
                        $value->property_type = 'Commercial';
                        break;
                    case 'Residential':
                        $value->property_type = 'Residential';
                        break;

                    default:
                        $value->property_type = 'Commercial';
                        break;
                }

                // if no resschedule message, set default
                if ($value->is_job_mode == 2) {
                    $concat_is_rescheduled = 2;
                    if (empty($value->reschedule_message)) {
                        $value->reschedule_message = "Unassigned by System";
                    }
                } else {
                    $value->reschedule_message = '';
                }

                // set row data
                $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' value='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox' />";
                $data[$i]['priority'] = $value->priority;
                $data[$i]['job_name'] = $value->job_name;
                $data[$i]['customer_name'] = '<a href="' . base_url("admin/editCustomer/") . $value->customer_id . '" style="color:#3379b7;">' . $value->first_name . ' ' . $value->last_name . '</a>';
                $data[$i]['property_name'] = $value->property_title;
                $data[$i]['square_feet'] = $value->yard_square_feet;
                $data[$i]['last_service_date'] = isset($value->completed_date_property) && $value->completed_date_property != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_property)) : '';
                $data[$i]['last_program_service_date'] = isset($value->completed_date_property_program) && $value->completed_date_property_program != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_property_program)) : '';
                $data[$i]['address'] = $value->property_address;
                $data[$i]['property_type'] = $value->property_type;
                $data[$i]['category_area_name'] = $value->category_area_name;
                $data[$i]['program'] = $value->program_name;
                $data[$i]['reschedule_message'] = $value->reschedule_message;
                $data[$i]['action'] = "<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='unassigned-services-element confirm_delete_unassign_job button-next' grd_ids='$value->customer_id:$value->job_id:$value->program_id:$value->property_id'  ><i class='icon-trash position-center' style='color: #9a9797;'></i></a></li></ul>";

                // easy way to console log out
                // $data[$i]['note'] = json_encode($_POST);
                // $data[$i]['note'] = json_encode($this->input->post('columns')[1]['search']['value']);
                // $data[$i]['note'] = json_encode($where);
                // $data[$i]['note'] = $value->is_job_mode.'-'.$value->unassigned_Job_delete_id;
                // $data[$i]['note'] = $var_last_query;

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

    public function cloverUpdateCustomerPayment()
    {
        $data = $this->input->post();

        $company_id = $this->session->userdata['company_id'];

        $where = array(
            'company_id' => $company_id,
            'status' => 1
        );

        $clover_details = $this->CardConnectModel->getOneCardConnect($where);

        if ($clover_details) {

            // die(print_r($data));

            $tokenAcct = array(
                'tokenData' => $data['tokenData']
            );

            $tokenize = cardConnectTokenizeAccount($tokenAcct);

            // die(print_r($tokenize['result']->token));

            // die(print_r($tokenize));

            if ($tokenize) {
                $param = array(
                    'username' => $clover_details->username,
                    'password' => decryptPassword($clover_details->password),
                    'proData' => array(
                        'merchid' => $clover_details->merchant_id,
                        'profile' => $data['clover_token'] . '/' . $data['clover_acct'],
                        'account' => $tokenize['result']->token,
                        'cof' => 'M',
                        'auoptout' => 'Y',
                        'cofpermission' => 'Y',
                        'profileupdate' => 'Y',
                        'expiry' => $data['tokenData']['expiry'],
                    )
                );


                $updated = updateCloverProfile($param);

                // die(print_r($updated));

                if ($updated['status'] == 200) {

                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"></div>');

                    $return_arr = array('status' => 200, 'msg' => 'Payment Credentials Updated Successfully.', 'result' => $updated['result']);
                } else {
                    $return_arr = array('status' => 400, 'msg' => $updated['result']->resptext, 'result' => $updated['result']);
                }
            } else {
                $return_arr = array('status' => 400, 'msg' => $tokenize['result']->message);
            }

        } else {
            $return_arr = array('status' => 400, 'msg' => 'Customer Not Found');
        }
        echo json_encode($return_arr);
    }

    /* Notes New */
    public function notesViewAll()
    {
        ini_set('memory_limit', -1);
        $filter = $this->input->get();

        // filter would be empty at the first time go to notes view all page
        // get default filter if any
        if (empty($filter)) {
            $user_id = $this->session->userdata['id'];
            $notes_default_filter = $this->NotesDefaultFilterModel->get_notes_default_filter_by_user_id($user_id);

            if (!empty($notes_default_filter)) {
                $filter = json_decode($notes_default_filter->filter_json, true);
            }
        }

        $data['company_id'] = $this->session->userdata['company_id'];
        $where = array('company_id' => $data['company_id']);
        $data['servicelist'] = $this->JobModel->getJobList($where);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);
        $data['note_types'] = $this->CompanyModel->getNoteTypes($data['company_id']);
        $service_specific_id = "";
        foreach ($data['note_types'] as $type) {
            if ($type->type_name == "Service-Specific" && $type->type_company_id == 0) {
                $service_specific_id = $type->type_id;
            }
        }
        $data['service_specific_note_type_id'] = $service_specific_id;

        $config = $this->load_paginate_configuration();
        $config["base_url"] = base_url() . "admin/notesViewAll";
        $config['per_page'] = isset($filter['per_page']) ? $filter['per_page'] : 10;
        $config["total_rows"] = $this->CompanyModel->getCompanyNotes($data['company_id'], $filter, true);

        $this->pagination->initialize($config);
        $page_index = isset($filter['page']) ? $filter['page'] : 1;
        $notes_all = $this->CompanyModel->getCompanyNotes($data['company_id'], $filter, false, $config['per_page'], $page_index);

        if (!empty($notes_all)) {
            foreach ($notes_all as $note) {
                $note->comments = $this->CompanyModel->getNoteComments($note->note_id);
                $note->files = $this->CompanyModel->getNoteFiles($note->note_id);
                if ($note->note_type == 3 && !empty($note->note_truck_id)) {
                    $note->vehicle_mainternance = $this->CompanyModel->getNoteVehicleMaintenanceInfoByNoteId($note->note_id);
                }
            }
        }

        $data["pagination_links"] = $this->pagination->create_links();

        $data['per_page_arr'] = self::PER_PAGE_ARR;

        $data['filter'] = $filter;
        $data['combined_notes'] = $notes_all;

        $page["active_sidebar"] = "all_notes";
        $page["page_name"] = "Notes";
        $page["page_content"] = $this->load->view("admin/notes_view", $data, TRUE);

        $this->layout->superAdminTemplateTable($page);
    }

    public function saveNoteDefaultFilter()
    {
        $response = array('error' => false);
        try {
            $filter = $this->input->get();

            $user_id = $this->session->userdata['id'];

            $notes_default_filter = $this->NotesDefaultFilterModel->get_notes_default_filter_by_user_id($user_id);

            if (empty($notes_default_filter)) {
                $notes_default_filters_id = $this->NotesDefaultFilterModel->create_notes_default_filter($user_id, json_encode($filter));
            } else {
                $notes_default_filters_id = $this->NotesDefaultFilterModel->update_notes_default_filter($user_id, json_encode($filter));
            }

            if ($notes_default_filters_id) {
                $response['message'] = 'Your filter has been saved.';
            } else {
                $response['message'] = 'Something went wrong, please try again';
            }
            die(json_encode($response));
        } catch (Exception $ex) {
            $response['error'] = true;
            $response['message'] = $ex->getMessage();

            die(json_encode($response));
        }

    }

    public function createNote($data = NULL)
    {
        $data = (empty($data)) ? $this->input->post() : $data;
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';
        if ($data['note_property_id'] == 0) {
            $data['note_property_id'] = NULL;
        }
        if (!empty($data['note_contents']) && $data['note_contents'] != '') {
            $params = array(
                'note_user_id' => $this->session->userdata['id'],
                'note_company_id' => $this->session->userdata['company_id'],
                'note_category' => (isset($data['note_property_id'])) ? 0 : 1,
                'note_property_id' => $data['note_property_id'] ?? NULL,
                'note_customer_id' => $data['note_customer_id'] ?? NULL,
                'note_contents' => nl2br($data['note_contents']),
                'note_due_date' => $data['note_due_date'] ?? NULL,
                'note_assigned_user' => $data['note_assigned_user'],
                'note_assigned_services' => $data['note_assigned_services'] ?? NULL,
                'assigned_service_note_duration' => $data['assigned_service_note_duration'] ?? NULL,
                'note_type' => $data['note_type'] ?? 0,
                'include_in_tech_view' => (isset($data['include_in_tech_view'])) ? 1 : 0,
                'is_urgent' => isset($data['is_urgent']) ? 1 : 0,
                'notify_me' => isset($data['notify_me']) ? 1 : 0,
                'is_enable_notifications' => isset($data['is_enable_notifications']) ? 1 : 0,
                'notification_to' => (isset($data['notification_to']) && isset($data['is_enable_notifications'])) ? implode(',', $data['notification_to']) : NULL,
            );

            if ($data['note_category'] == 2) {
                $params['note_category'] = 2;
            }
            $noteId = $this->CompanyModel->addNote($params);
            // die(print_r($noteId));
            if ($noteId && isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0])) {
                $fileStatusMsg = $this->addNoteFiles($noteId);
            }
            if ($noteId && isset($fileStatusMsg) && $fileStatusMsg) {

                $note_creator = $this->Administrator->getOneAdmin(array('id' => $params['note_user_id']));
                $note_type = $this->CompanyModel->getOneNoteTypeName($params['note_type']);
                $email_array = array(
                    'note_creator' => $note_creator->user_first_name . ' ' . $note_creator->user_last_name,
                    'note_type' => $note_type,
                    'note_due_date' => $params['note_due_date'] ?? 'None',
                    'note_contents' => $params['note_contents']
                );
                $where = array('company_id' => $this->session->userdata['company_id']);
                $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);

                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                $subject = 'New Note Assignment';
                if (!empty($params['note_assigned_user'])) {
                    // only send new note assignment email to assign user if they are not the one who created it
                    if ($this->session->userdata['id'] != $data['note_assigned_user']) {
                        $note_assigned_user = $this->Administrator->getOneAdmin(array('id' => $params['note_assigned_user']));
                        $email_array['name'] = $note_assigned_user->user_first_name . ' ' . $note_assigned_user->user_last_name;
                        $body = $this->load->view('email/note_email', $email_array, TRUE);
                        $res = Send_Mail_dynamic($company_email_details, $note_assigned_user->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
                    }
                }

                // email notification for relates user
                if ($params['is_enable_notifications'] && $params['notification_to']) {
                    $notification_to_user_ids = explode(',', $params['notification_to']);
                    $email_array['note_action'] = 'selected';
                    foreach ($notification_to_user_ids as $notification_to_user_id) {
                        $note_user_selected = $this->Administrator->getOneAdmin(array('id' => $notification_to_user_id));
                        $email_array['name'] = $note_user_selected->user_first_name . ' ' . $note_user_selected->user_last_name;
                        $body = $this->load->view('email/note_email_relates_users', $email_array, TRUE);
                        $res = Send_Mail_dynamic($company_email_details, $note_user_selected->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
                    }
                }

                $returnMessage = '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> added successfully</div>';
                $this->session->set_flashdata('message', $returnMessage);
                redirect($referer_path);
            } elseif ($noteId) {
                $note_creator = $this->Administrator->getOneAdmin(array('id' => $params['note_user_id']));
                $note_type = $this->CompanyModel->getOneNoteTypeName($params['note_type']);
                $email_array = array(
                    'note_creator' => $note_creator->user_first_name . ' ' . $note_creator->user_last_name,
                    'note_type' => $note_type,
                    'note_due_date' => $params['note_due_date'] ?? 'None',
                    'note_contents' => $params['note_contents']
                );
                $where = array('company_id' => $this->session->userdata['company_id']);
                $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);

                $subject = 'New Note Assignment';
                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                if (!empty($params['note_assigned_user'])) {
                    // only send new note assignment email to assign user if they are not the one who created it
                    if ($this->session->userdata['id'] != $data['note_assigned_user']) {
                        $note_assigned_user = $this->Administrator->getOneAdmin(array('id' => $params['note_assigned_user']));
                        $email_array['name'] = $note_assigned_user->user_first_name . ' ' . $note_assigned_user->user_last_name;
                        $body = $this->load->view('email/note_email', $email_array, TRUE);
                        $res = Send_Mail_dynamic($company_email_details, $note_assigned_user->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
                    }
                }

                // email notification for relates user
                if ($params['is_enable_notifications'] && $params['notification_to']) {
                    $notification_to_user_ids = explode(',', $params['notification_to']);
                    $email_array['note_action'] = 'selected';
                    foreach ($notification_to_user_ids as $notification_to_user_id) {
                        $note_user_selected = $this->Administrator->getOneAdmin(array('id' => $notification_to_user_id));
                        $email_array['name'] = $note_user_selected->user_first_name . ' ' . $note_user_selected->user_last_name;
                        $body = $this->load->view('email/note_email_relates_users', $email_array, TRUE);
                        $res = Send_Mail_dynamic($company_email_details, $note_user_selected->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
                    }
                }

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> added successfully</div>');
                redirect($referer_path);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> not added.</div>');
                redirect($referer_path);
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Something went really <strong>WRONG!</strong></div>');
            redirect($referer_path);
        }
    }

    public function getNote($noteId)
    {
        $note = $this->CompanyModel->getNote($noteId);
        return $note;
    }

    public function getUserNotes($userId)
    {
        $notes = $this->CompanyModel->getUserNotes($userId);
        return $notes;
    }

    public function getCustomerNotes($customerId)
    {
        $notes = $this->CompanyModel->getNotes($customerId);
        return $notes;
    }

    public function getPropertyNotes($propertyId)
    {
        $notes = $this->CompanyModel->getPropertyNotes($propertyId);
        return $notes;
    }

    public function getCompanyNotes($companyId)
    {
        $notes = $this->CompanyModel->getCompanyNotes($companyId);
        return $notes;
    }

    public function getNotesWhere($where)
    {
        if (is_array($where)) {
            $notes = $this->CompanyModel->getNotesWhere($where);
            return $notes;
        } else {
            return array(
                'message' => 'Warning: You must provide an array of column "where" arguments.'
            );
        }
    }

    public function markNoteComplete()
    {
        $id = $this->uri->segment('3');
        $result = $this->CompanyModel->closeNoteStatus($id);
        // close fleet maintenance as well if this note has fleet maintenance
        $this->CompanyModel->closeMaintenanceEntry($id);

        $this->CompanyModel->setNoteUrgentById($id, 0);
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';
        if ($result) {
            $this->notification_on_note($id, 'note_status_closed');
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Note status <strong>UPDATED</strong> successfully</div>');
            redirect($referer_path);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Note status did <strong>NOT</strong> update correctly.</div>');
            redirect($referer_path);
        }
    }

    public function toggleUrgentMarker()
    {
        $id = $this->uri->segment('3');
        $is_urgent = $this->uri->segment('4');
        $result = $this->CompanyModel->setNoteUrgentById($id, $is_urgent);
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';
        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Note status <strong>UPDATED</strong> successfully</div>');
            redirect($referer_path);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Note status did <strong>NOT</strong> update correctly.</div>');
            redirect($referer_path);
        }
    }

    public function deleteNote()
    {
        $id = $this->uri->segment('3');
        $result = $this->CompanyModel->deleteNote($id);
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/propertyList';
        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Note <strong>DELETED</strong> successfully</div>');
            redirect($referer_path);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Note did <strong>NOT</strong> delete correctly.</div>');
            redirect($referer_path);
        }
    }

    public function updateAssignUser()
    {
        $data = $this->input->post();
        $where = array(
            'note_id' => $data['noteId']
        );
        $updateData = array(
            'note_assigned_user' => ($data['userId'] == '') ? NULL : $data['userId']
        );
        $status = $this->CompanyModel->updateNoteData($updateData, $where);
        $note = $this->CompanyModel->getOneNote($where);
        if (!empty($updateData['note_assigned_user'])) {
            $note_creator = $this->Administrator->getOneAdmin(array('id' => $note->note_user_id));
            $note_type = ($note->note_type == 0 || !empty($note->note_type)) ? $this->CompanyModel->getOneNoteTypeName($note->note_type) : 'None';
            $email_array = array(
                'note_creator' => $note_creator->user_first_name . ' ' . $note_creator->user_last_name,
                'note_type' => $note_type,
                'note_due_date' => $note->note_due_date ?? 'None',
                'note_contents' => $note->note_contents
            );
            $where = array('company_id' => $this->session->userdata['company_id']);
            $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);

            $subject = 'New Note Assignment';
            $where['is_smtp'] = 1;
            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
            $note_assigned_user = $this->Administrator->getOneAdmin(array('id' => $note->note_assigned_user));
            $email_array['name'] = $note_assigned_user->user_first_name . ' ' . $note_assigned_user->user_last_name;

            $body = $this->load->view('email/note_email', $email_array, TRUE);
            $res = Send_Mail_dynamic($company_email_details, $note_assigned_user->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
        }
        $return_data = array(
            'status' => $status,
            'note' => $note
        );
        print_r(json_encode($return_data));
    }

    #for non-service-specific note-types
    public function updateAssignType()
    {
        $data = $this->input->post();
        $where = array(
            'note_id' => $data['noteId']
        );
        $updateData = array(
            'note_type' => ($data['typeId'] == '') ? NULL : $data['typeId'],
            'note_assigned_services' => NULL, //need to clear out old values if updating from service-specific to other note-type
            'assigned_service_note_duration' => NULL, //need to clear out old values if updating from service-specific to other note-type
        );
        $this->CompanyModel->updateNoteData($updateData, $where);
    }

    #for service-specific note-types
    public function updateAssignTypeForServiceSpecific()
    {
        $data = $this->input->post();
        $where = array(
            'note_id' => $data['noteId']
        );
        $updateData = array(
            'note_type' => ($data['typeId'] == '') ? NULL : $data['typeId'],
            'include_in_tech_view' => 1,
            'note_assigned_services' => ($data['assignedService'] == '') ? NULL : $data['assignedService'],
            'assigned_service_note_duration' => ($data['noteDuration'] == '') ? NULL : $data['noteDuration']
        );
        $this->CompanyModel->updateNoteData($updateData, $where);
    }

    public function updateNoteDueDate()
    {
        $data = $this->input->post();
        $where = array(
            'note_id' => $data['noteId']
        );
        $updateData = array(
            'note_due_date' => $data['dueDate']
        );
        $this->CompanyModel->updateNoteData($updateData, $where);
    }


    // Note Types

    public function getNoteTypes($companyId)
    {
        $note_types = $this->CompanyModel->getNoteTypes($companyId);
        return $note_types;
    }

    public function createNoteType()
    {
        $data = $this->input->post();

        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/setting';
        if (!empty($data['notetype_name']) && $data['notetype_name'] !== 'Task' && $data['notetype_name'] !== 'Vehicle General' && $data['notetype_name'] !== 'Vehicle Maintenance') {
            $params = array(
                'type_name' => $data['notetype_name'],
                'type_company_id' => $data['company_id']
            );
            $note_type_id = $this->CompanyModel->createNoteType($params);

            if ($note_type_id && $note_type_id > 0) {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Note type added successfully.</div>');
                redirect($referer_path);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Note type <strong>NOT</strong> added!</div>');
                redirect($referer_path);
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Note type <strong>CANNOT</strong> be empty!</div>');
            redirect($referer_path);
        }
    }

    public function editNoteType()
    {
        $data = $this->input->post();

        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/setting';
        if (!empty($data['edit_type_name']) && !empty($data['edit_type_id']) && $data['edit_type_id'] > 0) {
            $where = array(
                'type_id' => $data['edit_type_id']
            );
            $typeData = array(
                'type_name' => $data['edit_type_name']
            );
            $result = $this->CompanyModel->editNoteType($typeData, $where);
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Type <strong>EDITED</strong> successfully!</div>');
                redirect($referer_path);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Task edit <strong>NOT</strong> successful!</div>');
                redirect($referer_path);
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Task edit <strong>UNSUCCESSFUL</strong>!</div>');
            redirect($referer_path);
        }
    }

    public function deleteNoteType()
    {
        $data = $this->input->post();
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/setting';
        if (!empty($data['type_id']) && $data['type_id'] > 0) {
            $where = array(
                'type_id' => $data['type_id']
            );
            $result = $this->CompanyModel->deleteNoteType($where);
            if ($result) {
                $return_result = json_encode(array('status' => 'success', 'result' => $result));
                print_r($return_result);
            } else {
                $return_result = json_encode(array('status' => 'fail', 'result' => $result));
                print_r($return_result);
            }
        } else {
            $return_result = json_encode(array('status' => 'error'));
            print_r($return_result);
        }
    }

    // Note Comments
    public function addNoteComment()
    {
        $data = $this->input->post();
        $note_id = $data['comment-noteid'];
        $commentData = array(
            'note_id' => $note_id,
            'comment_user_id' => $data['comment-userid'],
            'comment_body' => $data['add-comment-input'],
        );
        $comment_id = $this->CompanyModel->addNoteComment($commentData);


        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
        if ($comment_id && $comment_id > 0) {
            $this->notification_on_note($note_id, 'commented', $comment_id);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Comment <strong>ADDED</strong> successfully!</div>');
            redirect($referer_path);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Comment <strong>NOT</strong> added!</div>');
            redirect($referer_path);
        }
    }

    public function addNoteCommentAjax()
    {
        $data = $this->input->post();
        $note_id = $data['comment-noteid'];
        $commentData = array(
            'note_id' => $note_id,
            'comment_user_id' => $data['comment-userid'],
            'comment_body' => $data['add-comment-input'],
        );
        $comment_id = $this->CompanyModel->addNoteComment($commentData);
        if ($comment_id && $comment_id > 0) {
            $this->notification_on_note($note_id, 'commented', $comment_id);
            $commentData['status'] = 'success';
            $commentData['user_first_name'] = $this->session->userdata('user_first_name');
            $commentData['user_last_name'] = $this->session->userdata('user_last_name');
            $commentData['comment_count'] = $this->CompanyModel->getNoteCommentCount($data['comment-noteid']);
            $commentData['timestamp'] = date("Y-m-d H:i:s");
            print_r(json_encode($commentData));
        } else {
            $commentData['status'] = 'failed';
            print_r(json_encode($commentData));
        }
    }

    // Note Files

// 'uploads/note_files/'
    public function addNoteFiles($noteId)
    {
        if (!empty($_FILES['files']['name'])) {
            $fileData = (array)[];
            $filesCount = count($_FILES['files']['name']);

            for ($i = 0; $i < $filesCount; $i++) {
                $fileData[$i] = (array)[];
                $file_name = $_FILES['files']['name'][$i];
                $tmp_name = $_FILES['files']['tmp_name'][$i];
                $key = 'uploads/note_files/' . $file_name;
                $res = $this->aws_sdk->saveObject($key, $tmp_name);
                $fileData[$i]['file_key'] = $key;
                $fileData[$i]['file_name'] = $file_name;
                $fileData[$i]['note_id'] = $noteId;
                $fileData[$i]['file_user_id'] = $this->session->userdata('id');
            }

            if (!empty($fileData)) {
                $fileResult = $this->CompanyModel->noteAddFiles($fileData);
                $fileStatusMsg = $fileResult;
            } else {
                $fileStatusMsg = "Sorry, there was an error uploading your file.";
            }

        } else {
            $fileStatusMsg = false;
        }
        return $fileStatusMsg;
    }

    public function updateNoteTechView()
    {
        $data = $this->input->post();
        $result = $this->CompanyModel->updateNoteTechView($data['tech_view'], $data['noteId']);
        print_r(json_encode($result));
    }


    /**
     * update list of user will be received the email notification on note changed
     *
     * @return void
     */
    public function updateNoteNotificationTo()
    {
        $response = array('error' => true, 'message' => 'Something went wrong, please try again');
        $data = $this->input->post();
        if (!isset($data['note_id'])) {
            die(json_encode($response));
        }
        if (!isset($data['notification_to'])) {
            $data['notification_to'] = '';
        }
        $notification_to_string = $data['notification_to'] ? implode(',', $data['notification_to']) : '';
        $update_value = [
            'notification_to' => $notification_to_string,
            'is_enable_notifications' => $notification_to_string ? 1 : 0
        ];

        $note_id = $data['note_id'];
        $note = $this->CompanyModel->getNoteById($note_id);
        $note_creator = $this->Administrator->getOneAdmin(array('id' => $note['note_user_id']));
        $note_type = $this->CompanyModel->getOneNoteTypeName($note['note_type']);
        $email_array = array(
            'note_creator' => $note_creator->user_first_name . ' ' . $note_creator->user_last_name,
            'note_type' => $note_type,
            'note_due_date' => $note['note_due_date'] ?? 'None',
            'note_contents' => $note['note_contents']
        );
        $where = array('company_id' => $this->session->userdata['company_id']);
        $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);

        $where['is_smtp'] = 1;
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
        $subject = 'New Note Assignment';

        // email notification for relates user
        if ($update_value['is_enable_notifications'] || $note['is_enable_notifications']) {
            // current user receive notification
            $notification_to_user_ids = $note['notification_to'] != '' ? explode(',', $note['notification_to']) : [];
            $new_notification_to_user_ids = $data['notification_to'] != '' ? $data['notification_to'] : [];
            // new user receive
            $added_users = array_diff($new_notification_to_user_ids, $notification_to_user_ids);
            $removed_users = array_diff($notification_to_user_ids, $new_notification_to_user_ids);
            if (count($added_users) > 0) {
                $email_array['note_action'] = 'selected';
                foreach ($added_users as $added_user) {
                    $note_user_selected = $this->Administrator->getOneAdmin(array('id' => $added_user));
                    $email_array['name'] = $note_user_selected->user_first_name . ' ' . $note_user_selected->user_last_name;
                    $body = $this->load->view('email/note_email_relates_users', $email_array, TRUE);
                    $res = Send_Mail_dynamic($company_email_details, $note_user_selected->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);

                }
            }
            if (count($removed_users) > 0) {
                $email_array['note_action'] = 'removed';
                foreach ($removed_users as $removed_user) {
                    $note_user_removed = $this->Administrator->getOneAdmin(array('id' => $removed_user));
                    $email_array['name'] = $note_user_removed->user_first_name . ' ' . $note_user_removed->user_last_name;
                    $body = $this->load->view('email/note_email_relates_users', $email_array, TRUE);
                    $res = Send_Mail_dynamic($company_email_details, $note_user_removed->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
                }
            }
        }

        $result = $this->CompanyModel->updateNoteData($update_value, ['note_id' => $data['note_id']]);
        if ($result) {
            $response = [
                'error' => false,
                'message' => 'Update note notification users successfully'
            ];
        }
        die(json_encode($response));
    }

    /* Add File to Existing Note */
    public function addToNoteFiles()
    {
        $data = $this->input->post();
        $referer_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? 'admin/customerList';
        if (!empty($_FILES['files']['name'])) {
            $fileData = (array)[];
            $filesCount = count($_FILES['files']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $fileData[$i] = (array)[];
                $file_name = $_FILES['files']['name'][$i];
                $tmp_name = $_FILES['files']['tmp_name'][$i];
                $key = 'uploads/note_files/' . $file_name;
                $res = $this->aws_sdk->saveObject($key, $tmp_name);
                $fileData[$i]['file_key'] = $key;
                $fileData[$i]['file_name'] = $file_name;
                $fileData[$i]['note_id'] = $data['note_id'];
                $fileData[$i]['file_user_id'] = $this->session->userdata('id');
            }

            if (!empty($fileData)) {
                $fileResult = $this->CompanyModel->noteAddFiles($fileData);
                $fileStatusMsg = 'Files uploaded successfully!';
            } else {
                $fileStatusMsg = "Sorry, there was an error uploading your file.";
            }

        } else {
            $fileStatusMsg = "Sorry, there was an error uploading your file.";
        }
        $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible" role="alert" data-auto-dismiss="4000">' . $fileStatusMsg . '</div>');
        redirect($referer_path);
    }

    // Fleet Vehicles
    public function allVehicles()
    {
        $company_id = $this->session->userdata['company_id'];
        $data['vehicles'] = $this->CompanyModel->getAllFleetVehicles($company_id);
        $where = array(
            'mnt_company_id' => $company_id,
            'mnt_status' => 1
        );
        $data['mnt_count'] = $this->CompanyModel->getFleetMaintenanceCount($where);

        $page["active_sidebar"] = "allVehicles";
        $page["page_name"] = 'Fleet Vehicles';
        $page["page_content"] = $this->load->view("admin/fleet/view_vehicles", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);
    }

    public function viewSingleVehicle($fleet_id = null)
    {
        if (!empty($fleet_id)) {
            $fleet_id = $fleet_id;
        } else {
            $fleet_id = $this->uri->segment(4);
        }

        $data['vehicle'] = $this->CompanyModel->getOneFleetVehicle($fleet_id);
        // Get Vehicle Notes

        /* Get company users for note assignments */
        $company_id = $this->session->userdata['company_id'];
        $filter['note_company_id'] = $company_id;

        $data['customerlist'] = $this->PropertyModel->getCustomerList(array('company_id' => $company_id));
        $config = $this->load_paginate_configuration();
        $config["base_url"] = base_url() . "admin/viewSingleVehicle/" . $fleet_id;
        $config["total_rows"] = $this->CompanyModel->getSingleVehicleNotes($fleet_id, $filter, true);
        $this->pagination->initialize($config);
        $page_index = isset($filter['page']) ? $filter['page'] : 1;
        $data["pagination_links"] = $this->pagination->create_links();
        $data['per_page_arr'] = self::PER_PAGE_ARR;

        $data['vehicle_notes'] = $this->CompanyModel->getSingleVehicleNotes($fleet_id, $filter, false, $config['per_page'], $page_index);
        if (!empty($data['vehicle_notes'])) {
            foreach ($data['vehicle_notes'] as $note) {
                $note->comments = $this->CompanyModel->getNoteComments($note->note_id);
                $note->files = $this->CompanyModel->getNoteFiles($note->note_id);
                $note->maintenance_entry = $this->CompanyModel->getNoteMaintenanceEntry($note->note_id);
                $note->inspection = $this->CompanyModel->getNoteInspectionId($note->note_id);
            }
        }
        /* Get Note Categories */
        $data['note_types'] = $this->CompanyModel->getNoteTypes($this->session->userdata['company_id']);

        // End Vehicle Notes

        $data['company_id'] = $company_id;
        $where = array('company_id' => $company_id);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);
        // die(print_r(json_encode($data)));


        $page["active_sidebar"] = "allVehicles";
        $page["page_name"] = 'Fleet Vehicle';
        $page["page_content"] = $this->load->view("admin/fleet/view_single_vehicle", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);
    }

    public function ajaxViewSingleVehicle()
    {

        $filter = $this->input->post();

        $fleet_id = $filter['fleet_id'];

        $data['vehicle'] = $this->CompanyModel->getOneFleetVehicle($fleet_id);
        // Get Vehicle Notes

        /* Get company users for note assignments */
        $company_id = $this->session->userdata['company_id'];
        $filter['note_company_id'] = $company_id;

        $page_index = isset($filter['page']) ? $filter['page'] : 1;
        $config = $this->load_paginate_configuration();
        $config['uri_segment'] = $page_index;
        $config["base_url"] = base_url() . "admin/viewSingleVehicle/" . $fleet_id;
        $config['per_page'] = isset($filter['per_page']) ? $filter['per_page'] : 10;
        $config["total_rows"] = $this->CompanyModel->getSingleVehicleNotes($fleet_id, $filter, true);
        $this->pagination->initialize($config);
        $data['vehicle_notes'] = $this->CompanyModel->getSingleVehicleNotes($fleet_id, $filter, false, $config['per_page'], $page_index);
        $data["pagination_links"] = $this->pagination->create_links();
        $data['per_page_arr'] = self::PER_PAGE_ARR;
        $data['filter'] = $filter;

        if (!empty($data['vehicle_notes'])) {
            foreach ($data['vehicle_notes'] as $note) {
                $note->comments = $this->CompanyModel->getNoteComments($note->note_id);
                $note->files = $this->CompanyModel->getNoteFiles($note->note_id);
                $note->maintenance_entry = $this->CompanyModel->getNoteMaintenanceEntry($note->note_id);
                $note->inspection = $this->CompanyModel->getNoteInspectionId($note->note_id);
            }
        }
        /* Get Note Categories */
        $data['note_types'] = $this->CompanyModel->getNoteTypes($company_id);


        // End Vehicle Notes

        $data['company_id'] = $company_id;
        $where = array('company_id' => $company_id);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);

        echo $this->load->view("admin/ajax_to_view/fleet_notes", $data, TRUE);
    }

    public function addVehiclePage()
    {
        $data = [];
        $page["active_sidebar"] = "addVehiclePage";
        $page["page_name"] = 'Add New Vehicle';
        $page["page_content"] = $this->load->view("admin/fleet/add_vehicle", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);
    }

    public function addVehicle()
    {
        $data = $this->input->post();
        $company_id = $this->session->userdata['company_id'];
        if (isset($data['v_vin']) && isset($data['v_plate']) && isset($data['v_type']) && isset($data['v_make']) && isset($data['v_model']) && isset($data['v_year']) && isset($data['fleet_number'])) {
            $vehicle = array(
                "v_company_id" => $company_id,
                "v_vin" => strtoupper($data['v_vin']),
                "v_plate" => strtoupper($data['v_plate']),
                "v_type" => $data['v_type'],
                "v_make" => $data['v_make'],
                "v_model" => $data['v_model'],
                "v_year" => $data['v_year'],
                "v_name" => $data['v_name'] ?? null,
                "fleet_number" => $data['fleet_number']
            );
            $id = $this->CompanyModel->addVehicle($vehicle);

            if ($id > 0) {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle added successfully</div>');
                redirect("admin/allVehicles");
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle <strong>NOT</strong> added!</div>');
                redirect("admin/allVehicles");
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle <strong>NOT</strong> added! Missing required information.</div>');
            redirect("admin/allVehicles");
        }
    }

    public function editVehiclePage($fleet_id = NULL)
    {
        if (!empty($fleet_id)) {
            $fleet_id = $fleet_id;
        } else {
            $fleet_id = $this->uri->segment(4);
        }
        $data['vehicle'] = $this->CompanyModel->getOneFleetVehicle($fleet_id);

        $data['company_id'] = $this->session->userdata['company_id'];
        $where = array('company_id' => $data['company_id']);
        $data['userdata'] = $this->Administrator->getAllAdmin($where);

        $data['available_drivers'] = $data['userdata'];

        $page["active_sidebar"] = "allVehicles";
        $page["page_name"] = 'Edit Vehicle';
        $page["page_content"] = $this->load->view("admin/fleet/edit_vehicle", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);
    }

    public function editVehicle()
    {
        $data = $this->input->post();
        $company_id = $this->session->userdata['company_id'];
        if (isset($data['v_vin']) && isset($data['v_plate']) && isset($data['v_type']) && isset($data['v_make']) && isset($data['v_model']) && isset($data['v_year'])) {

            if (isset($data['v_assigned_user'])) {
                $currentAssignedVehicle = $this->CompanyModel->getOneFleetVehicleAssigned($data['v_assigned_user']);
                // die( print_r( json_encode( $currentAssignedVehicle ),true ));
                if (!is_null($currentAssignedVehicle)) {
                    $currentAssignedVehicle->v_assigned_user = null;
                    $count = $this->CompanyModel->updateFleetVehicle($currentAssignedVehicle->fleet_id, $currentAssignedVehicle);
                }
            }

            $fleet_id = $data['fleet_id'];
            $vehicle = array(
                "v_company_id" => $company_id,
                "v_vin" => strtoupper($data['v_vin']),
                "v_plate" => strtoupper($data['v_plate']),
                "v_type" => $data['v_type'],
                "v_make" => $data['v_make'],
                "v_model" => $data['v_model'],
                "v_year" => $data['v_year'],
                "v_assigned_user" => $data['v_assigned_user'] ?? NULL,
                "v_name" => $data['v_name'] ?? null,
                "fleet_number" => $data['fleet_number']
            );
            $count = $this->CompanyModel->updateFleetVehicle($fleet_id, $vehicle);

            if ($count > 0) {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle updated successfully</div>');
                redirect("admin/viewSingleVehicle/" . $fleet_id);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle <strong>NOT</strong> updated!</div>');
                redirect("admin/addVehiclePage");
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle <strong>NOT</strong> added! Missing required information.</div>');
            redirect("admin/addVehiclePage");
        }
    }

    public function deleteVehiclePage($fleet_id = NULL)
    {
        if (!empty($fleet_id)) {
            $fleet_id = $fleet_id;
        } else {
            $fleet_id = $this->uri->segment(3);
        }
        $result = $this->CompanyModel->deleteFleetVehicle($fleet_id);

        if ($result > 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle deleted.</div>');
            redirect("admin/allVehicles");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle <strong>NOT</strong> deleted!</div>');
            redirect("admin/allVehicles");
        }
    }

    // Vehicle Notes
    public function createVehicleNote()
    {
        $data = $this->input->post();

        $this->load->library('user_agent');
        if ($this->agent->is_referral()) {
            $refer = $this->agent->referrer();
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $refer = $_SERVER['HTTP_REFERER'];
        } else {
            $refer = base_url('admin/allVehicles');
        }

        if (isset($data['note_contents'])) {
            $note = array(
                'note_user_id' => $this->session->userdata['id'],
                'note_company_id' => $this->session->userdata['company_id'],
                'note_category' => 3,
                'note_customer_id' => $data['note_customer_id'] ?? NULL,
                'note_truck_id' => $data['note_truck_id'],
                'note_contents' => nl2br($data['note_contents']),
                'note_due_date' => $data['note_due_date'] ?? NULL,
                'note_assigned_user' => $data['note_assigned_user'] ?? NULL,
                'note_type' => $data['note_type'] ?? 2,
                'include_in_tech_view' => (isset($data['include_in_tech_view'])) ? 1 : 0,
                'is_urgent' => isset($data['is_urgent']) ? 1 : 0,
                'notify_me' => isset($data['notify_me']) ? 1 : 0,
                'is_enable_notifications' => isset($data['is_enable_notifications']) ? 1 : 0,
                'notification_to' => (isset($data['notification_to']) && isset($data['is_enable_notifications'])) ? implode(',', $data['notification_to']) : NULL,
            );

            $noteId = $this->CompanyModel->addNote($note);

            if ($noteId && $data['note_type'] == '3') {
                $mnt_arr = array(
                    'mnt_truck_id' => $note['note_truck_id'],
                    'mnt_note_id' => $noteId,
                    'mnt_company_id' => $this->session->userdata['company_id']
                );
                $mnt_id = $this->CompanyModel->addMaintenanceEntry($mnt_arr);
            }

            if ($noteId && isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0])) {
                $fileStatusMsg = $this->addNoteFiles($noteId);
            }

            if ($noteId && isset($fileStatusMsg) && $fileStatusMsg) {
                $note_creator = $this->Administrator->getOneAdmin(array('id' => $note['note_user_id']));
                $note_type = $this->CompanyModel->getOneNoteTypeName($note['note_type']);
                $email_array = array(
                    'note_creator' => $note_creator->user_first_name . ' ' . $note_creator->user_last_name,
                    'note_type' => $note_type,
                    'note_due_date' => $note['note_due_date'] ?? 'None',
                    'note_contents' => $note['note_contents']
                );
                $where = array('company_id' => $this->session->userdata['company_id']);
                $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);

                $subject = 'New Note Assignment';
                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                if (!empty($note['note_assigned_user'])) {
                    // only send new note assignment email to assign user if they are not the one who created it
                    if ($this->session->userdata['id'] != $note['note_assigned_user']) {
                        $note_assigned_user = $this->Administrator->getOneAdmin(array('id' => $note['note_assigned_user']));
                        $email_array['name'] = $note_assigned_user->user_first_name . ' ' . $note_assigned_user->user_last_name;
                        $body = $this->load->view('email/note_email', $email_array, TRUE);
                        $res = Send_Mail_dynamic($company_email_details, $note_assigned_user->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
                    }
                }

                // email notification for relates user
                if ($note['is_enable_notifications'] && $note['notification_to']) {
                    $notification_to_user_ids = explode(',', $note['notification_to']);
                    $email_array['note_action'] = 'selected';
                    foreach ($notification_to_user_ids as $notification_to_user_id) {
                        $note_user_selected = $this->Administrator->getOneAdmin(array('id' => $notification_to_user_id));
                        $email_array['name'] = $note_user_selected->user_first_name . ' ' . $note_user_selected->user_last_name;
                        $body = $this->load->view('email/note_email_relates_users', $email_array, TRUE);
                        $res = Send_Mail_dynamic($company_email_details, $note_user_selected->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
                    }
                }
                $returnMessage = '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Note </strong> added successfully</div>';
                $this->session->set_flashdata('message', $returnMessage);
                redirect($refer);
            } elseif ($noteId) {
                $note_creator = $this->Administrator->getOneAdmin(array('id' => $note['note_user_id']));
                $note_type = $this->CompanyModel->getOneNoteTypeName($note['note_type']);
                $email_array = array(
                    'note_creator' => $note_creator->user_first_name . ' ' . $note_creator->user_last_name,
                    'note_type' => $note_type,
                    'note_due_date' => $note['note_due_date'] ?? 'None',
                    'note_contents' => $note['note_contents']
                );
                $where = array('company_id' => $this->session->userdata['company_id']);
                $email_array['setting_details'] = $this->CompanyModel->getOneCompany($where);

                $subject = 'New Note Assignment';
                $where['is_smtp'] = 1;
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                if (!empty($note['note_assigned_user'])) {
                    // only send new note assignment email to assign user if they are not the one who created it
                    if ($this->session->userdata['id'] != $note['note_assigned_user']) {
                        $note_assigned_user = $this->Administrator->getOneAdmin(array('id' => $note['note_assigned_user']));
                        $email_array['name'] = $note_assigned_user->user_first_name . ' ' . $note_assigned_user->user_last_name;
                        $body = $this->load->view('email/note_email', $email_array, TRUE);
                        $res = Send_Mail_dynamic($company_email_details, $note_assigned_user->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
                    }
                }

                // email notification for relates user
                if ($note['is_enable_notifications'] && $note['notification_to']) {
                    $notification_to_user_ids = explode(',', $note['notification_to']);
                    $email_array['note_action'] = 'selected';
                    foreach ($notification_to_user_ids as $notification_to_user_id) {
                        $note_user_selected = $this->Administrator->getOneAdmin(array('id' => $notification_to_user_id));
                        $email_array['name'] = $note_user_selected->user_first_name . ' ' . $note_user_selected->user_last_name;
                        $body = $this->load->view('email/note_email_relates_users', $email_array, TRUE);
                        $res = Send_Mail_dynamic($company_email_details, $note_user_selected->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject);
                    }
                }

                if ($noteId > 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Note added successfully</div>');
                    redirect($refer);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle note <strong>NOT</strong> added!</div>');
                    redirect($refer);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Vehicle note <strong>NOT</strong> added!</div>');
                redirect($refer);
            }
        }
    }

    public function viewVehicleInspection()
    {
        if (!empty($insp_id)) {
            $insp_id = $insp_id;
        } else {
            $insp_id = $this->uri->segment(3);
        }
        $data['inspection'] = $this->CompanyModel->getOneVehicleInspection($insp_id);
        $page["active_sidebar"] = "allVehicles";
        $page["page_name"] = 'Vehicle Inspection Report';
        $page["page_content"] = $this->load->view("admin/fleet/view_inspection_report", $data, TRUE);
        $this->layout->superAdminReportTemplateTable($page);

    }

    public function ajaxGetTotalUnassignedServices()
    {
        $unassigned_count = 0;

        $company_id = $this->session->userdata['company_id'];

        $d = new DateTime('first day of this month');
        $d2 = new DateTime('last day of this month');

        $date1 = strtotime($d->format('Y-m-d'));
        $date2 = strtotime($d2->format('Y-m-d'));

        $data['need_to_reschedule'] = 0;
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status !=' => 0,
            'property_status' => 1
        );
        $tempdata = $this->DashboardModel->getTableData($where);
        if (!empty($tempdata)) {
            foreach ($tempdata as $key => $value) {
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $value->property_id,
                );
                $assign_table_data = $this->Tech->GetOneRow($arrayName);
                $tempdata[$key]->mode = '';
                if ($assign_table_data) {
                    if ($assign_table_data->is_job_mode == 2) {
                        $tempdata[$key]->mode = 'Rescheduled';
                        if (strtotime($assign_table_data->job_assign_date) >= $date1 && strtotime($assign_table_data->job_assign_date) <= $date2) {
                            $data['need_to_reschedule']++;
                        }
                    } else {
                        unset($tempdata[$key]);
                    }
                }
                $deletedrow = $this->UnassignJobDeleteModal->getOneDeletedRow($arrayName);
                if ($deletedrow) {
                    unset($tempdata[$key]);
                }
            }
        }

        $data['table_data'] = array_values($tempdata);

        $unassigned_count = count($data['table_data']);

        echo json_encode(['status' => 'success', 'unassigned_count' => $unassigned_count]);
    }

#cancel reasons
    public function createCancelReason()
    {
        $company_id = $this->session->userdata['company_id'];
        $param['cancel_name'] = $_POST['cancel_name'];
        $param['company_id'] = $company_id;
        if ($company_id && $param['cancel_name'] && $param['company_id']) {
            $cancel_reasons = $this->CustomerModel->addCancelReasons($param);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Cancel </strong> Reason Added Successfully</div>');
            redirect('admin/setting');
        }
    }

    public function createRescheduleReason()
    {
        $company_id = $this->session->userdata['company_id'];
        $param['reschedule_name'] = $_POST['reschedule_name'];
        $param['company_id'] = $company_id;
        if ($company_id && $param['reschedule_name'] && $param['company_id']) {
            $cancel_reasons = $this->CustomerModel->addRescheduleReasons($param);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Reschedule </strong> Reason Added Successfully</div>');
            redirect('admin/setting');
        }
    }

    public function updateCustomerPortalPreference()
    {
        //$data = $this->input->post();
        // $sessiondata = $this->load->library('session');
        // print_r($sessiondata);
        $company_name = $this->session->userdata['compny_details']->company_name;
        // $slug = $this->session->userdata['compny_details']->slug;

        //die(print_r($_SESSION));
        //die(print_r($company_name));

        $checked = $this->input->post('toggle_customer_portal');


        //echo $checked;

        //if button post value is on, recreate the slug if not exists and put into db.
        if (isset($checked) == 'on') {

            //$city = $company_add;
            // die(print_r($city));
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $company_name)));
            //$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug.'-'.$city)));
            //    die(print_r($slug));
            $where = array('company_id' => $this->session->userdata['company_id']);

            $param = array(
                'slug' => $slug,
            );
            //    die(print_r($param));
            $result = $this->CompanyModel->updateSlug($where, $param);
            // }


            //else if customer portal button !$checked, delete the slug
        } else {
            // $no_cust_portal = (isset($data['slug'])) ? $data['slug'] : '';
            //echo $data['slug'];
            $where = array('company_id' => $this->session->userdata['company_id']);
            //$slugwhere = array('company_id'=>$this->session->userdata['slug']);
            //print_r($slugwhere);
            $param = array('slug' => '');

            $result = $this->CompanyModel->updateSlug($where, $param);
            // }
        }

        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer Portal settings</strong> updated successfully.</div>');
            redirect("admin/setting");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Customer Portal settings</strong> not updated. Please try again.</div>');
            redirect("admin/setting");
        }

        // $slugwhere = array('slug'=>$this->session->userdata['slug']);
        // print_r($slugwhere);
        // $where = array('company_id'=>$this->session->userdata['company_id']);
        // print_r($where);


    }

    public function updateAssignJobPreference()
    {
        //$data = $this->input->post();
        // $sessiondata = $this->load->library('session');
        // print_r($sessiondata);
        $company_name = $this->session->userdata['compny_details']->company_name;
        // $slug = $this->session->userdata['compny_details']->slug;

        //die(print_r($_SESSION));
        //die(print_r($company_name));

        $checked = $this->input->post('toggle_assign_job');


        //echo $checked;

        //if button post value is on default view is maps.
        if (isset($checked) == 'on') {

            $where = array('company_id' => $this->session->userdata['company_id']);

            $param = array(
                'assign_job_view' => 1,
            );
            //    die(print_r($param));
            $result = $this->CompanyModel->updateAssignJobView($where, $param);
            // }
            $this->session->set_userdata('assign_job_view', 1);

            //else if customer portal button !$checked, delete the slug
        } else {
            // $no_cust_portal = (isset($data['slug'])) ? $data['slug'] : '';
            //echo $data['slug'];
            $where = array('company_id' => $this->session->userdata['company_id']);
            //$slugwhere = array('company_id'=>$this->session->userdata['slug']);
            //print_r($slugwhere);
            $param = array('assign_job_view' => 0);

            $result = $this->CompanyModel->updateAssignJobView($where, $param);
            $this->session->set_userdata('assign_job_view', 0);
            // }
        }

        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Assign Services View settings</strong> updated successfully.</div>');
            redirect("admin/setting");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Assign Services View settings</strong> not updated. Please try again.</div>');
            redirect("admin/setting");
        }

    }

    public function invoice_customer_hold()
    {
        $company_id = $this->session->userdata['company_id'];
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data_company_email = $this->CompanyEmail->getOneCompanyEmail($where);

        @$Is_enable_hold_service = $data_company_email->is_email_scheduling_indays;
        if ($Is_enable_hold_service) {
            $curent_date = date("Y-m-d");
            $days = $data_company_email->email_scheduling_indays;
            $new_date = date('Y-m-d', strtotime('-' . $days . 'days', strtotime($curent_date)));

            $expire_paid = "";
            $year = date("Y");
            $where_unpaid = array(
                'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                'status !=' => 0,
                'is_archived' => 0,
                'payment_status !=' => 2,
                //'invoice_date >' => $year . '-01-01',
                'invoice_date <' => $new_date
            );
            $data_invoice_details = $this->INV->getAllInvoive($where_unpaid);
            $customer_ids = [];
            $customer_names = [];
            foreach ($data_invoice_details as $_data) {
                if (!in_array($_data->customer_id, $customer_ids))
                    $customer_ids[] = $_data->customer_id;
            }
            foreach ($customer_ids as $customer_id) {
                $customer = $this->CustomerModel->getOneCustomer(array('customer_id' => $customer_id));
                if ($customer->customer_status != 2 && $customer->customer_status != 0) {
                    $param = array(
                        'customer_status' => 2
                    );
                    $result = $this->CustomerModel->updateAdminTbl($customer_id, $param);
                    $first = isset($customer->first_name) ? $customer->first_name : "";
                    $last = isset($customer->last_name) ? $customer->last_name : "";
                    $customer_names[] = $first . " " . $last;
                    // send email account hold
                    $is_email_hold_templete = $data_company_email->is_email_hold_templete;
                    $email_array = [];
                    if ($is_email_hold_templete) {

                        $email_hold_template = $data_company_email->email_hold_templete;
                        $hold_notification = $data_company_email->hold_notification;


                        $email_hold_template = str_replace("{CUSTOMER_NAME}", $customer->first_name . ' ' . $customer->last_name, $email_hold_template);
                        $email_array['email_body_text'] = $email_hold_template;
                        if ($customer->email) {
                            $email_array['customer_details'] = $customer;
                            $where['is_smtp'] = 1;
                            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);
                            if (!$company_email_details) {
                                $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                            }

                            $where = array('company_id' => $company_id);
                            $company_details = $this->CompanyModel->getOneCompany($where);
                            $email_array['company_details'] = $company_details;
                            $subject = "Your Account is On Hold";
                            $to_email = $customer->email;
                            $body = $this->load->view('email/customer_hold_email', $email_array, TRUE);
                            $res = Send_Mail_dynamic($company_email_details, $to_email, array("name" => $company_details->company_name, "email" => $company_details->company_email), $body, $subject);

                        }

                    }

                }

            }
            $admin_email = [];
            if (count($customer_names) > 0) {
                #send email to admin
                $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                $admin_email['company_details'] = $company_details;
                $user_details = $this->CompanyModel->getOneAdminUser(array('company_id' => $this->session->userdata['company_id'], 'role_id' => 1));
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' => $this->session->userdata['company_id'], 'is_smtp' => 1));
                if (!$company_email_details) {
                    $company_email_details = $this->CompanyModel->getOneDefaultEmailArray();
                }
                $admin_email['customer_names'] = $customer_names;
                $body = $this->load->view('email/customer_hold_admin_email', $admin_email, TRUE);
                if ($company_email_details) {
                    $res = Send_Mail_dynamic($company_email_details, $user_details->email, array("name" => $company_details->company_name, "email" => $company_details->company_email), $body, 'Customer Acount Holds');
                }
            }
        }
    }

    public function customerHoldPayments()
    {
        $company_id = $this->session->userdata['company_id'];
        $data_company_email = $this->CompanyEmail->getOneCompanyEmail(array('company_id' => $this->session->userdata['company_id']));

        @$automatic_hold_enabled = $data_company_email->is_email_scheduling_indays;
        if ($automatic_hold_enabled) {
            #get all customers on hold
            $hold_customers = $this->CustomerModel->get_all_customer(array('company_id' => $this->session->userdata['company_id'], 'customer_status' => 2));
            if (count($hold_customers) > 0) {
                $today = strtotime('now');
                $hold_days_setting = $data_company_email->email_scheduling_indays;
                $hold_date_marker = date('Y-m-d', strtotime('-' . $hold_days_setting . 'days', $today));
                foreach ($hold_customers as $customer) {
                    #get customer unpaid customer invoices older than 45 days
                    $where_unpaid = array(
                        'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                        'invoice_tbl.customer_id' => $customer->customer_id,
                        'invoice_tbl.status !=' => 0,
                        'invoice_tbl.is_archived' => 0,
                        'invoice_tbl.payment_status !=' => 2,
                        'invoice_tbl.invoice_date <' => $hold_date_marker
                    );
                    $unpaid_invoices = $this->INV->getInvoices($where_unpaid);
                    #remove customer hold if customer has 0 unpaid invoices within date range
                    if (empty($unpaid_invoices)) {
                        $removeHold = $this->CustomerModel->updateAdminTbl($customer->customer_id, array('customer_status' => 1));
                    }
                }
            }
        }
    }

    public function editCancelReason()
    {
        $data = $this->input->post();
        $result = $this->CustomerModel->editCancelReason(array('cancel_name' => $data['edit_cancel_name']), array('cancel_id' => $data['edit_cancel_id']));

        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Cancel Reason <strong>Edited</strong> Successfully!</div>');
            redirect('admin/setting');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Cancel Reason Edit <strong>NOT</strong> Successful!</div>');
            redirect('admin/setting');
        }
    }

    public function deleteCancelReason()
    {
        $data = $this->input->post();
        $result = $this->CustomerModel->deleteCancelReason(array('cancel_id' => $data['delete_cancel_id']));

        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Cancel Reason <strong>Deleted</strong> Successfully!</div>');
            redirect('admin/setting');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Cancel Reason <strong>NOT DELETED</strong> Successfully!</div>');
            redirect('admin/setting');
        }
    }

    public function editRescheduleReason()
    {
        $data = $this->input->post();
        $result = $this->CustomerModel->editRescheduleReason(array('reschedule_name' => $data['edit_reschedule_name']), array('reschedule_id' => $data['edit_reschedule_id']));

        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Reschedule Reason <strong>Edited</strong> Successfully!</div>');
            redirect('admin/setting');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Reschedule Reason Edit <strong>NOT</strong> Successful!</div>');
            redirect('admin/setting');
        }
    }

    public function deleteRescheduleReason()
    {
        $data = $this->input->post();
        $result = $this->CustomerModel->deleteRescheduleReason(array('reschedule_id' => $data['delete_reschedule_id']));

        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Reschedule Reason <strong>Deleted</strong> Successfully!</div>');
            redirect('admin/setting');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Reschedule Reason <strong>NOT DELETED</strong> Successfully!</div>');
            redirect('admin/setting');
        }
    }

    public function cancelProperty()
    {
        $data = $this->input->post();
        $property_id = $data['property_id'];
        $cancel_reason = $data['cancel_reasons'];
        $other_reason = isset($data['other_reason']) ? $data['other_reason'] : "";
        if ($cancel_reason == 'other') {
            $cancel_reason = "Other: " . $other_reason;
        }
        $send_customer_email = isset($data['customer_email']) && $data['customer_email'] == 'on' ? 1 : 0;

        $email_data = array();
        $email_data['cancel_reason'] = $cancel_reason;
        $email_data['services'] = [];
        #update property status
        $handleCancelProperty = $this->PropertyModel->updateAdminTbl($property_id, array('property_status' => 0, 'cancel_reason' => $cancel_reason, 'property_cancelled' => date('Y-m-d H:i:s', strtotime('now'))));

        #outstanding services should be cancelled
        $outstandingServices = $this->PropertyModel->getUnassignJobsByProperty($property_id);
        if (count($outstandingServices) > 0) {
            foreach ($outstandingServices as $key => $service) {
                $email_data['services'][$key] = array(
                    'program_name' => $service->program_name,
                    'service_name' => $service->job_name,
                );
                $param_arr = array(
                    'customer_id' => $service->customer_id,
                    'job_id' => $service->job_id,
                    'program_id' => $service->program_id,
                    'property_id' => $property_id,
                );
                $createUnassignJobDelete = $this->UnassignJobDeleteModal->createDeleteRow($param_arr);
                $getCancelledService = $this->CST->getCancelledServiceInfo($param_arr);
                if (!empty($getCancelledService)) {
                    foreach ($getCancelledService as $canc) {
                        $this->CST->updateCancelledServicesTable(array('cancelled_service_id' => $canc->cancelled_service_id), array('is_cancelled' => 1));
                    }
                } else {
                    $param_arr['is_cancelled'] = 1;
                    $param_arr['user_id'] = $this->session->userdata['id'];
                    $param_arr['company_id'] = $this->session->userdata['company_id'];
                    $createCancelledService = $this->CST->createCancelledService($param_arr);
                }
                #for assigned, incomplete jobs mark as cancelled
                if (isset($service->technician_job_assign_id) && $service->technician_job_assign_id != "") {

                }
            }
        }//end if outstanding services

    //webhook_trigger
    $user_info = $this->Administrator->getOneAdmin(array("user_id" => $this->session->userdata('user_id')));
    if($user_info->webhook_account_cancelled){
        $this->load->model('api/Webhook');
        
        $webhook_data = ['Customer Name'=>$email_data['customer_name'], 'Customer Email'=>$user_details->email, 'Property Address'=>$email_data['property_address'], 'Service Area'=>$property_details['property_area']];
        
        //die(print_r($webhook_data));
        $response = $this->Webhook->callTrigger($user_info->webhook_account_cancelled, $webhook_data);


    }

    


	 if($handleCancelProperty){
		 $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Property </strong> cancelled successfully</div>');
		 redirect("admin/propertyList");
	 }else{
		 $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong.</div>');
		 redirect("admin/propertyList");
	 }
 }



    public function cancelService(){
        $this->load->model('Cancelled_services_model', 'CST');

        $data = $this->input->post();
        $property_id = $data['property_id'];
        $customer_id = $data['customer_id'];
        $cancel_reason = $data['cancel_reasons'];
        $other_reason = isset($data['other_reason']) ? $data['other_reason'] : "";
        if ($cancel_reason == 'other') {
            $cancel_reason = "Other: " . $other_reason;
        }
        $send_customer_email = isset($data['customer_email']) && $data['customer_email'] == 'on' ? 1 : 0;

        $param = array(
            'company_id' => $this->session->userdata['company_id'],
            'customer_id' => $customer_id,
            'property_id' => $property_id,
            'program_id' => $data['program_id'],
            'job_id' => $data['job_id'],
            'is_cancelled' => 1,
            'user_id' => $this->session->userdata['id'],
            'cancel_reason' => $cancel_reason,
        );

        $param2 = array(
            'customer_id' => $customer_id,
            'property_id' => $property_id,
            'program_id' => $data['program_id'],
            'job_id' => $data['job_id'],
            'is_cancelled' => 0,
        );

        $already_exists = $this->CST->getCancelledServiceInfo($param2);
        #handle cancel service
        if (!empty($already_exists)) {
            $handleCancelService = $this->CST->updateCancelledServicesTable($param2, array('is_cancelled' => 1));
        } else {
            $handleCancelService = $this->CST->createCancelledService($param);
        }


        if ($handleCancelService) {
            $param3 = array(
                'customer_id' => $customer_id,
                'property_id' => $property_id,
                'program_id' => $data['program_id'],
                'job_id' => $data['job_id']
            );

            $createUnassignJobDelete = $this->UnassignJobDeleteModal->createDeleteRow($param3);

            #Automatically create a note for that customer/property that says the service or property was cancelled.
            ##get job name
            $getJobDetails = $this->JobModel->getOneJob(array('job_id' => $data['job_id']));
            $job_name = isset($getJobDetails->job_name) ? $getJobDetails->job_name : "";

            $noteParams = array(
                'note_user_id' => $this->session->userdata['id'],
                'note_company_id' => $this->session->userdata['company_id'],
                'note_category' => 1,
                'note_property_id' => $property_id,
                'note_customer_id' => $customer_id,
                'note_contents' => 'This following Service has been cancelled: ' . $job_name,
                'note_type' => 0,
            );

            $note = $this->CompanyModel->addNote($noteParams);

            #handle email notifications
            $customer_info = $this->CST->getCustomerInfoForEmail($customer_id);
            if (!empty($customer_info)) {
                $email_array = array();
                $email_array['customer_details'] = $customer_info[0];
                $email_array['job_details'] = $this->CST->getCancelledServiceName($data['job_id']);
                $email_array['property_details'] = $this->CST->getCancelledPropertyName($property_id);
                $email_array['program_details'] = $this->CST->getCancelledProgramName($data['program_id']);
                $email_array['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
                $user_details = $this->CompanyModel->getOneAdminUser(array('company_id' => $this->session->userdata['company_id'], 'role_id' => 1));
                $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id' => $this->session->userdata['company_id']));
                if (!$company_email_details) {
                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                }
                $subject = "Service Cancelled for " . $email_array['customer_details']->first_name . " " . $email_array['customer_details']->last_name;
                $body = $this->load->view('email/cancel_service_admin_email', $email_array, TRUE);
                if ($company_email_details) {
                    #handle admin email
                    $sendAdminEmail = Send_Mail_dynamic($company_email_details, $user_details->email, array("name" => $email_array['setting_details']->company_name, "email" => $email_array['setting_details']->company_email), $body, $subject);
                }
                #handle customer email
                if ($send_customer_email == 1 && $customer_info[0]->is_email == 1) {
                    $subject = 'Service Cancelled at Your Property';
                    $body = $this->load->view('email/cancel_service_customer_email', $email_array, TRUE);
                    $sendCustomerEmail = Send_Mail_dynamic($company_email_details, $email_array['customer_details']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, $subject, $email_array['customer_details']->secondary_email);
                }

            }

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> cancelled successfully</div>');
            redirect("admin/customerList");
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong</div>');
            redirect("admin/customerList");
        }
    }

    public function markAsAsap()
    {
        $this->load->model('Program_job_assigned_customer_property_model', 'PJACPM');

        $data = $this->input->post();
        $property_id = $data['property_id'];
        $customer_id = $data['customer_id'];
        $reason = $data['reason'];
        $originalCustomer = $data['original_customer'];


        $param2 = array(
            'customer_id' => $customer_id,
            'property_id' => $property_id,
            'program_id' => $data['program_id'],
            'job_id' => $data['job_id'],
            'reason' => $reason,
        );

        if ($this->PJACPM->createProgramJobAssignedCustomerProperty($param2)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Service </strong> marked as ASAP successfully</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Something </strong> went wrong</div>');
        }
        redirect("/admin/editCustomer/" . $originalCustomer);

    }

    public function calculateInvoiceCouponValue($param)
    {
        $total_cost = $param['cost'];
        $coupon_invoices = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $param['invoice_id']));

        if (!empty($coupon_invoices)) {

            // die(print_r($coupon_invoices));
            foreach ($coupon_invoices as $coupon_invoice) {

                $coupon_id = $coupon_invoice->coupon_id;
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
                            // die(print_r("Coupon is Flat Rate: " . $total_cost));
                        }

                    } else {
                        $percentage = (float)$coupon_details->amount;
                        $discount_amm = (float)$total_cost * ($percentage / 100);

                        if (($total_cost - $discount_amm) < 0) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                            // die(print_r("Coupon is Percentage: " . $total_cost));
                        }

                    }
                }
            }
        }
        // die(print_r(number_format($total_cost, 2, '.', ',')));
        return number_format($total_cost, 2, '.', ',');
    }

    public function calculateCustomerCouponCost($param)
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

    public function teststatus($customer_id = 0)
    {
        $this->CustomerModel->autoStatusCheck($customer_id);
    }

    public function calculateServiceCouponCost($param)
    {
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

    public function calculateEstimateCouponCost($param = array())
    {
        $total_cost = $param['cost'];
        $coupon_estimate = $this->CouponModel->getAllCouponEstimate(array('estimate_id' => $param['estimate_id']));

        if (!empty($coupon_estimate)) {
            foreach ($coupon_estimate as $coupon_est) {

                $coupon_id = $coupon_est->coupon_id;
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

    public function sendMonthlyInvoice()
    {

        //die('chao');
        //$this->load->model('Invoice_model', 'Invoice');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');

        $all_customers = $this->CompanyModel->getAllCustomersToSendMonthlyStatement();
        //die(print_r($all_customers));
//        start_date
//        end_date
//        Email
//        email_statment_button
        $id_customer = '40100';
        foreach (array_slice($all_customers, 0, 3) as $key => $value) {

            $resp = $this->INV->sendMonthlyInvoice($value->customer_id, 'alvaro.mho2@gmail.com');
            $this->load->clear_vars();

        }

        die('chao');


    }

    public function ajaxGetRoutingFORTABLE()
    { // all I had to do to remove server-side to work with maps is change the last option in getTableDataAjax model to true. this removes the limits.

        ini_set('memory_limit', '2048M');

        $tblColumns = array(
            0 => 'checkbox',
            1 => 'priority',
            2 => 'job_name',
            3 => 'pre_service_notification',
            4 => 'customers.first_name',
            5 => 'property_title',
            6 => '`property_tbl`.`yard_square_feet`',
            7 => 'completed_date_property',
            8 => 'completed_date_property_program',
            9 => 'completed_date_last_service_by_type',
            10 => 'property_program_date',
            11 => 'service_due',
            12 => 'property_address',
            13 => 'property_type',
            14 => 'property_notes',
            15 => 'category_area_name',
            16 => 'program_name',
            17 => 'reschedule_message',
            18 => 'tags',
            19 => 'asap_reason',
            20 => 'available_days',
            21 => 'action',
            22 => 'program_services'
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $tblColumns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $company_id = $this->session->userdata['company_id'];
        $where = array(
            'jobs.company_id' => $company_id,
            'property_tbl.company_id' => $company_id,
            'customer_status !=' => 0,
            'property_status !=' => 0,
        );

        $or_where = [];

        $data = array();

        // seardch through each col, and if a search value, let's search for it
        $where_like = array();
        $where_in = array();
        $tagSearch = "";
        if (is_array($this->input->post('columns'))) {
            $columns = $this->input->post('columns');
            $colm_num = 0;
            foreach ($columns as $column) {

                if (isset($column['search']['value']) && !empty($column['search']['value'])) {
                    if ($colm_num == 2) {
                        $col = 'jobs.job_name';
                        $val = $column['search']['value'];
                        if (strpos($column['search']['value'], ',') !== false) {
                            $where_in[$col] = explode(',', $val);
                        } else {
                            $where[$col] = $val;
                        }

                        // $dbg = explode(',', $val);
                    } else if ($colm_num == 19) {
                        $col = 'program_job_assigned_customer_property';
                        $val = $column['search']['value'];
                        $where[$col] = $val;
                    } else if ($colm_num == 22) {
                        $col = 'program_services';
                        $val = $column['search']['value'];
                        if (strpos($column['search']['value'], ',') !== false) {
                            $where_like[$col] = explode(',', $val);
                        } else {
                            $where[$col] = $val;
                        }

                        // $dbg = explode(',', $val);
                    } else if ($colm_num == 15) {
                        $col = 'category_area_name';
                        $val = $column['search']['value'];
                        if (strpos($column['search']['value'], ',') !== false) {
                            $where_in[$col] = explode(',', $val);
                        } else {
                            $where[$col] = $val;
                        }

                        // $dbg = explode(',', $val);
                    } else if ($colm_num == 20) {
                        // Available Days filtering
                        $col = 'available_days';
                        $val = $column['search']['value'];
                        if (strpos($column['search']['value'], ',') !== false) {
                            $where_in[$col] = explode(',', $val);
                        } else {
                            //$where_in[$col] = $val;
                            $where_in[$col] = explode(',', $val);
                        }

                    } else {
                        $col = $column['data'];
                        $val = $column['search']['value'];
                        if ($col == "tags") {
                            $col = "property_tbl.tags";
                            $val = (int)$val;
                            $tag = $this->TagsModel->getOneTag(array('id' => $val));
                            if (!empty($tag)) {
                                $where_like[$col] = $tag->id;
                                $tagSearch = $tag->id;
                            } else {
                                $where_like[$col] = $val;
                            }
                        } else if ($colm_num == 14) {
                            if (strpos($column['search']['value'], ',') !== false) {
                                $where_in[$col] = explode(',', $val);
                            } else {
                                $where[$col] = $val;
                            }
                        } else {
                            $where_like[$col] = $val;
                        }
                    }

                }
                $colm_num++;
            }
        }


        if (empty($this->input->post('search')['value'])) {

            if (isset($where['program_services']) || (isset($where_like['program_services']) && is_array($where_like['program_services']))) {
                $property_outstanding_services = $this->DashboardModel->getOutstandingServicesFromProperty_forTable($where, $where_like, $limit, $start, $order, $dir, false, $where_in, $or_where);
                $tempdata = $this->DashboardModel->getTableDataAjax_new($where, $where_like, $limit, $start, $order, $dir, false, $where_in, $or_where, $property_outstanding_services);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjax_new($where, $where_like, $limit, $start, $order, $dir, true, $where_in, $or_where, $property_outstanding_services) / 3;
                //die('total ' .$var_total_item_count_for_pagination );
            } else {
                $tempdata = $this->DashboardModel->getTableDataAjax_new($where, $where_like, $limit, $start, $order, $dir, false, $where_in, $or_where);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjax_new($where, $where_like, $limit, $start, $order, $dir, true, $where_in, $or_where);
            }
        } else {
            $search = $this->input->post('search')['value'];
            if (isset($where['program_services']) || (isset($where_like['program_services']) && is_array($where_like['program_services']))) {
                $property_outstanding_services = $this->DashboardModel->getOutstandingServicesFromProperty_forTable($where, $where_like, $limit, $start, $order, $dir, false, $where_in, $or_where);
                $tempdata = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false, $where_in, $or_where, $property_outstanding_services);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true, $where_in, $or_where, $property_outstanding_services);

            } else {
                $tempdata = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, false, $where_in, $or_where);
                $var_total_item_count_for_pagination = $this->DashboardModel->getTableDataAjaxSearch($where, $where_like, $limit, $start, $order, $dir, $search, true, $where_in, $or_where);
            }

        }
        //---------------------------------------------------------------------------------


        if (!empty($tempdata)) {
            $i = 0;
            // filter & mold data for frontend
            foreach ($tempdata as $key => $value) {
                //die(var_dump($tagSearch));
                if (isset($tagSearch) && $tagSearch != "") {
                    $property_tags = explode(',', $value->tags);
                    if (!in_array($tagSearch, $property_tags)) {
                        unset($tempdata[$key]);
                        continue;
                    }
                }
                // $generate_row = true;
                $arrayName = array(
                    'customer_id' => $value->customer_id,
                    'job_id' => $value->job_id,
                    'program_id' => $value->program_id,
                    'property_id' => $value->property_id,
                );
//                $assign_table_data = $this->Tech->GetOneRow($arrayName); // select * from technician_job_assign where __
//                $assignedServices = $this->DashboardModel->getCustomerAllServicesWithSalesRep(array('jobs.company_id' => $company_id, 'property_tbl.company_id' => $company_id,'customers.customer_id' => 42190));
//                die(var_dump($services));
                $value->mode = '';
                $value->reschedule_message = '';
                $concat_is_rescheduled = 0;
//                if ($assign_table_data) {
//                    if ($assign_table_data->is_job_mode == 2) {
//                        $concat_is_rescheduled = 2;
//                        $value->mode = 'Rescheduled';
//                        $value->reschedule_message = $assign_table_data->reschedule_message;
//                    }
//                }
                if ($value->assign_table_data == 1) {
                    $concat_is_rescheduled = 2;
                    $value->mode = 'Rescheduled';
                    $value->reschedule_message = $value->assign_reschedule_message;
                }

                // set property type
                switch ($value->property_type) {
                    case 'Commercial':
                        $value->property_type = 'Commercial';
                        break;
                    case 'Residential':
                        $value->property_type = 'Residential';
                        break;

                    default:
                        $value->property_type = 'Commercial';
                        break;
                }

                // if no resschedule message, set default
                if ($value->is_job_mode == 2) {
                    $concat_is_rescheduled = 2;
                    $technicianName = '';
                    if (isset($value->user_first_name) && isset($value->user_last_name))
                        $technicianName = $value->user_first_name . ' ' . $value->user_last_name;
                    if (empty($value->reschedule_message)) {
                        if ($technicianName != '') {
                            $value->reschedule_message = $technicianName . " - Unassigned by System";
                        } else {
                            $value->reschedule_message = "Unassigned by System";
                        }
                    } else {
                        if ($technicianName != '') {
                            $value->reschedule_message = $technicianName . " - " . $value->reschedule_message;
                        }
                    }
                } else {
                    $value->reschedule_message = '';
                }

                // set row data
                $IsCustomerInHold = 0;
                if (isset($value->customer_status)) {
                    if ($value->customer_status == 2) {
                        $IsCustomerInHold = 1;
                    }
                }
                $asapHighligth = 0;
                if ($value->asap == 1)
                    $asapHighligth = 1;

                if($IsCustomerInHold==0){  //print_r($data);die();
                $data[$i]['checkbox'] ="<input  name='group_id' type='checkbox' data-address='$value->property_address:$value->property_latitude:$value->property_longitude' data-row-asap='$asapHighligth' data-row-job-mode='$concat_is_rescheduled' id='$i' value='$i' data-realvalue='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox map' />";
                }
                else {
                $data[$i]['checkbox'] ="<input title='Customer Account On Hold' data-address='$value->property_address:$value->property_latitude:$value->property_longitude' data-row-asap='$asapHighligth'  name='group_id' type='checkbox'  disabled data-row-job-mode='$concat_is_rescheduled' id='$i' value='$i' data-realvalue='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox customer_in_hold' />";
                }
                // $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' id=' $i ' value='$i' class='myCheckBox map' />";
                // $data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' value='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' data-iter=$i class='myCheckBox' />";
                //dev-11 checkbox below
                //$data[$i]['checkbox'] = "<input  name='group_id' type='checkbox' data-row-job-mode='$concat_is_rescheduled' id=' $i ' value='$i' data-realvalue='$value->customer_id:$value->job_id:$value->program_id:$value->property_id' class='myCheckBox map' />";
                $data[$i]['priority'] = $value->priority;
                $data[$i]['job_name'] = $value->job_name;
                $data[$i]['customer_name'] = '<a href="' . base_url("admin/editCustomer/") . $value->customer_id . '" style="color:#3379b7;">' . $value->first_name . ' ' . $value->last_name . '</a>';
                $data[$i]['property_name'] = $value->property_title;
                $data[$i]['square_feet'] = $value->yard_square_feet;
                $data[$i]['last_service_date'] = isset($value->completed_date_property) && $value->completed_date_property != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_property)) : '';
                $data[$i]['last_program_service_date'] = isset($value->completed_date_property_program) && $value->completed_date_property_program != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_property_program)) : '';
                $data[$i]['property_program_date'] = isset($value->property_program_date) && $value->property_program_date != '0000-00-00' ? date('m-d-Y', strtotime($value->property_program_date)) : '';
                $data[$i]['completed_date_last_service_by_type'] = isset($value->completed_date_last_service_by_type) && $value->completed_date_last_service_by_type != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_last_service_by_type)) : '';
                $data[$i]['last_program_service_date'] = isset($value->completed_date_property_program) && $value->completed_date_property_program != '0000-00-00' ? date('m-d-Y', strtotime($value->completed_date_property_program)) : '';
                //service due styling for datatable rendering
                switch ($value->service_due) {
                    case "Due":
                        $data[$i]['service_due'] = "<span class='label label-success myspan'>Due</span>";
                        break;
                    case "Overdue":
                        $data[$i]['service_due'] = "<span class='label label-danger myspan'>Overdue</span>";
                        break;
                    case "Not Due":
                    default:
                        $data[$i]['service_due'] = "<span class='label label-default myspan'>Not Due</span>";
                        break;
                }
                $data[$i]['address'] = $value->property_address;
                //customer notification flags
                //$notify_array = json_decode($value->pre_service_notification);
                $notify_array = $value->pre_service_notification ? json_decode($value->pre_service_notification) : [];
                $data[$i]['pre_service_notification'] = "";
                if (is_array($notify_array) && in_array(1, $notify_array)) {
                    $data[$i]['pre_service_notification'] = "<div class='label label-primary myspan m-y-1' style=' padding: 0 2px; margin-right: 0.5rem'>Call</div> ";
                }
                if (is_array($notify_array) && in_array(4, $notify_array)) {
                    $data[$i]['pre_service_notification'] .= "<div class='label label-success myspan ' style=' padding: 0 2px; margin-right: 0.5rem'>Text ETA</div>";
                }
                if (is_array($notify_array) && (in_array(2, $notify_array) || in_array(3, $notify_array))) {
                    $data[$i]['pre_service_notification'] .= "<div class='label label-info myspan' style=' padding: 0 2px; margin-right: 0.5rem'>Pre-Notified</div>";
                }


                //$data[$i]['title'] = $value->property_address;


                // $data[$i]['property_state'] = $value->property_state;
                // $data[$i]['property_city'] = $value->property_city;
                // $data[$i]['property_zip'] = $value->property_zip;
                $data[$i]['property_type'] = $value->property_type;
                $data[$i]['property_notes'] = isset($value->property_notes) ? $value->property_notes : '';
                $data[$i]['category_area_name'] = $value->category_area_name;
                $data[$i]['program'] = $value->program_name;
                $data[$i]['program_services'] = isset($value->program_services) ? $value->program_services : array();
                $data[$i]['reschedule_message'] = $value->reschedule_message;
                $data[$i]['asap'] = $value->asap;
                $data[$i]['asap_reason'] = $value->asap_reason;
                $tags_list = "";
                $tags_list_array = [];
                if ($value->tags != null && !empty($value->tags)) {
                    $id_list = $value->tags;
                    $id_list_array = explode(',', $id_list);
                    foreach ($id_list_array as $tag) {
                        $where_arr = array(
                            // 'tags_title'=>'New Customer',
                            'id' => $tag
                        );
                        $tag = $this->TagsModel->getOneTag($where_arr);
                        if ($tag != null) {
                            $tags_list_array[] = $tag->tags_title;
                        }
                        // if($tag=null){
                        //     $tags_list_array[]=$tag->tags_title['New Customer'];
                        // }
                    }
                }
                $tag_html = "";
                if (count($tags_list_array) > 0) {
                    foreach ($tags_list_array as $tag) {
                        if ($tag == "New Customer") {
                            $tag_html .= '<span class="badge badge-success">' . $tag . '</span>';
                        } else {
                            $tag_html .= '<span class="badge badge-primary">' . $tag . '</span>';
                        }
                    }
                }
                $data[$i]['tags'] = $tag_html;
                // $data[$i]['service_note'] = $value->service_note;
                // $data[$i]['job_notes'] = $value->job_notes;

                // Available days
                $available_days = formatAvailableDays($value->available_days);
                $data[$i]['available_days'] = implode(", ", $available_days);

                $data[$i]['action'] = "<ul style='list-style-type: none; padding-left: 0px;'><li style='display: inline; padding-right: 10px;'><a  class='unassigned-services-element confirm_delete_unassign_job button-next' grd_ids='$value->customer_id:$value->job_id:$value->program_id:$value->property_id'  ><i class='icon-trash position-center' style='color: #9a9797;'></i></a></li></ul>";
                $data[$i]['index'] = $i;
                $i++;
            }
        }

        $json_data = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => intval($var_total_item_count_for_pagination), // "(filtered from __ total entries)"
            "recordsFiltered" => intval($var_total_item_count_for_pagination), // actual total that determines page counts
            "data" => $data
        );
        echo json_encode($json_data);
    }

    public function updatePropertyData()
    {
        $data = $this->input->post();
        foreach ($data['data'] as $k => $v) {
            $result = $this->PropertyModel->updatePropertyData(['property_area' => $v['property_area']], ['property_id' => $v['property_id']]);
        }
        print $result ? 1 : 0;
    }

    public function getOutstandingInvoiceCost()
    {
        $limit = 0;

        $start = 0;
        $total_number = 0;
        $order = 'invoice_id';

        $dir = 'DESC';

        // WHERE:
        $whereArr = array(
            'invoice_tbl.company_id' => $this->session->userdata['company_id'],
            'is_archived' => 0
        );
        // WHERE NOT: all of the below true
        $whereArrExclude = array(
            "programs.program_price" => 2,
            // "technician_job_assign.is_complete" => 0,
            "technician_job_assign.is_complete !=" => 1,
            "technician_job_assign.is_complete IS NOT NULL" => null,
        );

        // WHERE NOT: all of the below true
        $whereArrExclude2 = array(
            "programs.program_price" => 2,
            "technician_job_assign.invoice_id IS NULL" => null,
            "invoice_tbl.report_id" => 0,
            "property_program_job_invoice2.report_id IS NULL" => null,
        );
        $orWhere = array();


        $invoices = $this->INV->ajaxActiveInvoicesTech($whereArr, $limit, $start, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, false);
        if (!empty($invoices)) {

            foreach ($invoices as $invoice) {

                // die(print_r($value));

                $status = 1;

                //////////////////////////////////
                // START INVOICE CALCULATION COST //

                //invoice cost
                $invoice_total_cost = $invoice->cost;

                //cost of all services (with price overrides) - service coupons
                $job_cost_total = 0;
                $where = array(
                    'property_program_job_invoice.invoice_id' => $invoice->invoice_id,
                );
                $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);
                if (!empty($proprojobinv)) {
                    foreach ($proprojobinv as $job) {

                        $job_cost = $job['job_cost'];

                        $job_where = array(
                            'job_id' => $job['job_id'],
                            'customer_id' => $job['customer_id'],
                            'property_id' => $job['property_id'],
                            'program_id' => $job['program_id'],
                        );
                        $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                        if (!empty($coupon_job_details)) {

                            foreach ($coupon_job_details as $coupon) {
                                // $nestedData['email'] = json_encode($coupon->coupon_amount);
                                $coupon_job_amm_total = 0;
                                $coupon_job_amm = $coupon->coupon_amount;
                                $coupon_job_calc = $coupon->coupon_amount_calculation;

                                if ($coupon_job_calc == 0) { // flat amm
                                    $coupon_job_amm_total = (float)$coupon_job_amm;
                                } else { // percentage
                                    $coupon_job_amm_total = ((float)$coupon_job_amm / 100) * $job_cost;
                                }

                                $job_cost = $job_cost - $coupon_job_amm_total;

                                if ($job_cost < 0) {
                                    $job_cost = 0;
                                }
                            }
                        }

                        $job_cost_total += $job_cost;
                    }
                } else {
                    // $total_tax_amount = getAllSalesTaxSumByInvoice($invoice->invoice_id)->total_tax_amount;
                    // $invoice_total_cost += $total_tax_amount;
                    // $invoice_total_cost = $invoice->cost+$total_tax_amount;

                    // IF none from that table, is old invoice, calculate old way
                    $job_cost_total = $invoice->cost;
                }
                $invoice_total_cost = $job_cost_total;

                // check price override -- any that are not stored in just that ^^.

                // - invoice coupons
                $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice->invoice_id));
                foreach ($coupon_invoice_details as $coupon_invoice) {
                    if (!empty($coupon_invoice)) {
                        $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                        $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                        if ($coupon_invoice_amm_calc == 0) { // flat amm
                            $invoice_total_cost -= (float)$coupon_invoice_amm;
                        } else { // percentage
                            $coupon_invoice_amm = ((float)$coupon_invoice_amm / 100) * $invoice_total_cost;
                            $invoice_total_cost -= $coupon_invoice_amm;
                        }
                        if ($invoice_total_cost < 0) {
                            $invoice_total_cost = 0;
                        }
                    }
                }

                // + tax cost
                $invoice_total_tax = 0;
                $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice->invoice_id));
                if (!empty($invoice_sales_tax_details)) {
                    foreach ($invoice_sales_tax_details as $tax) {
                        if (array_key_exists("tax_value", $tax)) {
                            $tax_amm_to_add = ((float)$tax['tax_value'] / 100) * $invoice_total_cost;
                            $invoice_total_tax += $tax_amm_to_add;
                        }
                    }
                }
                $invoice_total_cost += $invoice_total_tax;

                // END TOTAL INVOICE CALCULATION COST //
                ///////////////////////////////////////

                $due = $invoice_total_cost - $invoice->partial_payment;
                // Make sure the invoice takes into account all past partial payments
                $all_invoice_partials_total = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $invoice->invoice_id));

                if (count($all_invoice_partials_total) > 0) {
                    $paid_already = 0;
                    foreach ($all_invoice_partials_total as $paid_amount) {
                        if ($paid_amount->payment_amount > 0) {
                            $paid_already += $paid_amount->payment_amount;
                        }
                    }
                    $due = $invoice_total_cost - $paid_already;
                }

                // no negative due
                if ($due < 0) {
                    $due = 0;
                }

                // if invoice is paid, due = 0
                if ($invoice->payment_status == 2) {
                    $due = 0;
                }

                $balance_due = $due == 0 ? '$0.00' : '$' . number_format($due, 2);
                $total_number = $due + $total_number;
            }
        }
        return $total_number;
    }

    public function notification_on_note($note_id, $action = 'commented', $comment_id = '')
    {
        if ($action == 'note_on_due_date') {
            $company_data = $this->CompanyModel->getCompanyByNoteId($note_id);
            $company_id = $company_data->company_id;
        } else {
            $company_id = $this->session->userdata['company_id'];
        }
        $note = $this->CompanyModel->getNoteById($note_id);
        $where = array(
            'company_id' => $company_id,
            'is_smtp' => 1
        );
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where);

        $company_details = $this->CompanyModel->getOneCompany(array('company_id' => $company_id));

        if ($note['notify_me']) {
            $comment = null;
            if ($comment_id != '') {
                $comment = $this->CompanyModel->getSingleNoteComment($comment_id);
            }
            if ($action != 'commented' || (isset($comment->comment_user_id) && $note['note_user_id'] != $comment->comment_user_id)) {
                // email to note user created
                $note_user = $this->Administrator->getUserById($note['note_user_id']);

                $mail_data = [
                    'note' => $note,
                    'company_details' => $company_details,
                    'note_user' => $note_user,
                ];

                $this->_send_mail_on_note($company_email_details, $mail_data, $action, $comment_id);
            }

            if (!empty($note['note_assigned_user']) &&
                ($action != 'commented' ||
                    (isset($comment->comment_user_id) && $note['note_assigned_user'] != $comment->comment_user_id))) {
                // email to note assigned user
                $note_user = $this->Administrator->getUserById($note['note_assigned_user']);

                $mail_data = [
                    'note' => $note,
                    'company_details' => $company_details,
                    'note_user' => $note_user,
                ];

                $this->_send_mail_on_note($company_email_details, $mail_data, $action, $comment_id);
            }
        }

        if ($note['is_enable_notifications']) {
            if (!empty($note['notification_to'])) {
                $users = explode(',', $note['notification_to']);
                // email to specific users chosen for notification
                foreach ($users as $key => $user_id) {
                    $note_user = $this->Administrator->getUserById($user_id);

                    $mail_data = [
                        'note' => $note,
                        'company_details' => $company_details,
                        'note_user' => $note_user,
                    ];
                    $this->_send_mail_on_note($company_email_details, $mail_data, $action, $comment_id);
                }
            }
        }
    }

    private function _send_mail_on_note($company_email_details, $mail_data, $action, $comment_id)
    {
        $body = '';
        $subject = '';
        $mail_data['action'] = $action;
        if ($action == 'commented') {
            $comment = $this->CompanyModel->getSingleNoteComment($comment_id);
            $mail_data['comment'] = $comment;
            $body = $this->load->view('email/action_note_email', $mail_data, true);
            $subject = 'Commented on Note';
        } else if ($action == 'note_status_closed') {
            $body = $this->load->view('email/action_note_email', $mail_data, true);
            $subject = 'Note has been closed';
        } else if ($action == 'note_on_due_date') {
            $body = $this->load->view('email/action_note_email', $mail_data, true);
            $subject = 'Today is Due Date on Note';
        }


        if ($body != '') {
            Send_Mail_dynamic($company_email_details,
                $mail_data['note_user']['email'],
                array(
                    'name' => $mail_data['company_details']->company_name,
                    'email' => $mail_data['company_details']->company_email
                ),
                $body,
                $subject);
        }
    }

    /**
     * fixing all notes that doesn't exist customer name
     *
     * @return void
     */
    public function fixCustomerNameOnNotes()
    {
        $notes = $this->CompanyModel->getNotesEmptyCustomer();

        $count = 0;
        if (!empty($notes)) {
            foreach ($notes as $note) {
                if (!empty($note->note_property_id)) {
                    $customers = $this->PropertyModel->getSelectedCustomer($note->note_property_id);
                    if (count($customers) > 0) {
                        $where = array(
                            'note_id' => $note->note_id
                        );
                        $updateData = array(
                            'note_customer_id' => $customers[0]->customer_id // get first customer assign to property
                        );
                        $this->CompanyModel->updateNoteData($updateData, $where);
                        $count++;
                    }
                }
            }
        }

        echo 'There are ' . $count . ' notes has been updated customer info';
    }

    public function load_paginate_configuration()
    {
        return array(
            'per_page' => '10',
            'page_query_string' => true,
            'query_string_segment' => 'page',
            'use_page_numbers' => true,
            'reuse_query_string' => true,
            'full_tag_open' => '<ul class="pagination">',
            'full_tag_close' => '</ul>',
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><a class="page-link" href="#">',
            'cur_tag_close' => '</a></li>',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'attributes' => array('class' => 'page-link')
        );
    }
    public function testwebhook(){
        //webhook_trigger
        $user_info = $this->Administrator->getOneAdmin(["user_id" => $this->session->userdata('user_id')]);
        $result = $this->CustomerModel->getCustomerDetail(4599);
        print_r($result);
        if($user_info->webhook_customer_created){
            $this->load->model('api/Webhook');
            $response = $this->Webhook->callTrigger($user_info->webhook_customer_created, $result);

            print_r($response);
        }
    }
}
