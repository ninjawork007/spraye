<?php

defined('BASEPATH') or exit('No direct script access allowed');

ini_set('memory_limit', '-1');
//ini_set('display_errors', '1');

require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require FCPATH . 'vendor/autoload.php';
include APPPATH ."libraries/dompdf/autoload.inc.php";
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Invoices extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->session->userdata('email')) {

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
     *         http://example.com/index.php/welcome
     *     - or -
     *         http://example.com/index.php/welcome/index
     *     - or -
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

        $this->load->model('Payment_logs_model', 'PaymentLogModel');
    }
    
    public function index(){
        $year = date("Y");
        $where_revenue_total = array(
            'company_id' => $this->session->userdata['company_id'],
            'payment_status >' => 0,
            'is_archived' => 0,
            'invoice_date >' => $year . '-01-01'
        );
        $result_revenue_total = $this->INV->getSumInvoive($where_revenue_total);
        $data['total'] = array(
            'total_revenue' => $result_revenue_total->total_partial-$result_revenue_total->refund_amount_total,
        );
        $page["active_sidebar"] = "invoicenav";
        $page["page_name"] = 'Invoices';
        $page["page_content"] = $this->load->view("admin/invoice/view_invoice", $data, true);
        $this->layout->superAdminInvoiceTemplateTable($page);
    }

    public function archived()
    {

        $data['invoice_details'] = $this->INV->getAllInvoive(array('invoice_tbl.company_id' => $this->session->userdata['company_id'], 'is_archived' => 1));
        // print_r($data['invoice_details']);
        // die();
        $count = 0;
        foreach ($data['invoice_details'] as $invoice_detail) {

            ////////////////////////////////////
            // START INVOICE CALCULATION COST //

            // vars
            $tmp_invoice_id = $invoice_detail->invoice_id;

            // invoice cost
            // $invoice_total_cost = $invoice->cost;

            // cost of all services (with price overrides) - service coupons
            $job_cost_total = 0;
            $where = array(
                'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
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
                                $coupon_job_amm_total = (float) $coupon_job_amm;
                            } else { // percentage
                                $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                            }

                            $job_cost = $job_cost - $coupon_job_amm_total;

                            if ($job_cost < 0) {
                                $job_cost = 0;
                            }
                        }
                    }

                    $job_cost_total += $job_cost;
                }
                $invoice_total_cost = $job_cost_total;
            } else {
                #account for old invoicing process
                $invoice_total_cost = $invoice_detail->cost;
            }

            // check price override -- any that are not stored in just that ^^.

            // - invoice coupons
            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
            foreach ($coupon_invoice_details as $coupon_invoice) {
                if (!empty($coupon_invoice)) {
                    $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                    $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                    if ($coupon_invoice_amm_calc == 0) { // flat amm
                        $invoice_total_cost -= (float) $coupon_invoice_amm;
                    } else { // percentage
                        $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                        $invoice_total_cost -= $coupon_invoice_amm;
                    }
                    if ($invoice_total_cost < 0) {
                        $invoice_total_cost = 0;
                    }
                }
            }

            // + tax cost
            $invoice_total_tax = 0;
            $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
            if (!empty($invoice_sales_tax_details)) {
                foreach ($invoice_sales_tax_details as $tax) {
                    if (array_key_exists("tax_value", $tax)) {
                        $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                        $invoice_total_tax += $tax_amm_to_add;
                    }
                }
            }
            $invoice_total_cost += $invoice_total_tax;
            $total_tax_amount = $invoice_total_tax;

            // END TOTAL INVOICE CALCULATION COST //
            ////////////////////////////////////////

            $data['invoice_details'][$count]->invoice_total_calculated_final_cost_minus_partial = $invoice_total_cost;
            $invoice_total_cost -= $invoice_detail->partial_payment;
            $data['invoice_details'][$count]->invoice_total_calculated_final_cost = $invoice_total_cost;
            $count += 1;
        }
        // print_r($data['invoice_details'][0]->invoice_id);
        // print_r($data['invoice_details']);
        // die();

        $page["active_sidebar"] = "ainvoicenav";

        $page["page_name"] = 'Archived Invoices';

        $page["page_content"] = $this->load->view("admin/invoice/view_archived_invoice", $data, true);

        $this->layout->superAdminInvoiceTemplateTable($page);
    }

    public function active()
    {

        $whereArr = array(

            'invoice_tbl.company_id' => $this->session->userdata['company_id'],

            'is_archived' => 0,

        );

        $data['invoice_details'] = $this->INV->getAllInvoive($whereArr);

        $where_unpaid = array('company_id' => $this->session->userdata['company_id'], 'status' => 1, 'is_archived' => 0);

        $result_unpaid = $this->INV->getSumInvoive($where_unpaid);

        $where_revenue = array('company_id' => $this->session->userdata['company_id'], 'payment_status' => 2, 'is_archived' => 0);

        $result_revenue = $this->INV->getSumInvoive($where_revenue);

        $where_partial = array('company_id' => $this->session->userdata['company_id'], 'payment_status' => 1, 'is_archived' => 0);

        $result_partial = $this->INV->getSumInvoive($where_partial);

        $refunds_total = $result_partial->refund_amount_total;
        $remaining_amount = $result_partial->remaning_amount;
        $adjusted_amount = ($remaining_amount - $refunds_total);
        $cost_total = $result_unpaid->cost;
        $unpaid_total = $cost_total + $adjusted_amount;

        $data['total'] = array(

            'total_unpaid' => ($result_unpaid->cost ? $result_unpaid->cost : 0) + $result_partial->remaning_amount,

            'total_billed' => $result_unpaid->cost + ($result_revenue->cost ? $result_revenue->cost : 0) + $result_partial->cost,

            'total_revenue' => ($result_revenue->cost ? $result_revenue->cost : 0) + $result_partial->total_partial,

        );

        //die(print_r($data));

        $page["active_sidebar"] = "acinvoicenav";

        $page["page_name"] = 'Active Invoices';

        $page["page_content"] = $this->load->view("admin/invoice/view_invoice_new", $data, true);

        $this->layout->superAdminInvoiceTemplateTable($page);
    }

    public function ajaxGetActive()
    {
        if (isset($_POST['aging']) && $_POST['aging'] == 1) {
            $aging = 1;
        } else {
            $aging = 0;
        }

        $tblColumns = array(
            0 => 'checkbox',
            1 => 'invoice_id',
            2 => 'invoice_tbl.customer_id',
            3 => 'email',
            4 => 'cost',
            5 => 'balance_due',
            6 => 'payment_method',
            7 => 'payment_info',
            8 => 'status',
            9 => 'payment_status',
            10 => 'invoice_date',
            11 => 'sent_date',
            12 => 'opened_date',
            13 => 'payment_created',
            14 => 'refund_date',

        );

        $limit = $this->input->post('length');

        $start = $this->input->post('start');

        $order = $tblColumns[$this->input->post('order')[0]['column']];

        $dir = $this->input->post('order')[0]['dir'];

        // WHERE:
        $whereArr = array(
            'invoice_tbl.company_id' => $this->session->userdata['company_id'],
            'is_archived' => 0,
        );
        if ($aging == 1) {
            $whereArr['payment_status !='] = 2;
            $whereArr['status !='] = 0;
        }

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

        if (is_array($this->input->post('columns'))) {

            $columns = $this->input->post('columns');

            foreach ($columns as $column) {
                if ($column['data'] == 'status' && $column['search']['value'] === '0') {
                    $whereArr['status'] = 0;
                }



                if ($column['data'] == 'payment_status' && $column['search']['value'] === '0') {
                    $orWhere['payment_status'] = array(0, 3); //include past due when filtering for unpaid
                }

                if (isset($column['search']['value']) && !empty($column['search']['value'])) {

                    $col = $column['data'];
                    $val = $column['search']['value'];

                    //filter status
//                    die($val);
                    if ($col == 'status' && $val != 4) {
                        $whereArr['status'] = $val;
                    }
                    if ($col == 'payment_status' && $val != 5) {
                        $whereArr[$col] = $val;
                    }
                }
            }
        }

       /* $allInv = $this->INV->getAllInvoive($whereArr);
        $totalData = count((array) $allInv);
        $filteredData = $totalData;*/

        if (empty($this->input->post('search')['value'])) {

            $invoices = $this->INV->ajaxActiveInvoicesTech($whereArr, $limit, $start, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, false);
            $var_total_item_count_for_pagination = $this->INV->ajaxActiveInvoicesTech($whereArr, $limit, $start, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {

            $search = $this->input->post('search')['value'];
            $invoices = $this->INV->ajaxActiveInvoicesSearchTech($whereArr, $limit, $start, $search, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, false);
            $var_total_item_count_for_pagination = $this->INV->ajaxActiveInvoicesSearchTech($whereArr, $limit, $start, $search, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }

        $data = array();
        if (!empty($invoices)) {

            foreach ($invoices as $invoice) {


                
                $nestedData['invoice_id'] = '<a href="' . base_url('admin/Invoices/editInvoice/') . $invoice->invoice_id . '">' . $invoice->invoice_id . '</a>';

                $nestedData['customer_id'] = '<a href="' . base_url('admin/editCustomer/') . $invoice->customer_id . '">' . $invoice->customer_name . '</a>';
                $nestedData['email'] = $invoice->email;

                $total_tax_amount = getAllSalesTaxSumByInvoice($invoice->invoice_id)->total_tax_amount;

                ////////////////////////////////////
                // START INVOICE CALCULATION COST //

                // cost of all services (with price overrides) - service coupons
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
                                    $coupon_job_amm_total = (float) $coupon_job_amm;
                                } else { // percentage
                                    $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
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
                            $invoice_total_cost -= (float) $coupon_invoice_amm;
                        } else { // percentage
                            $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
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
                            $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                            $invoice_total_tax += $tax_amm_to_add;
                        }
                    }
                }
                $late_fee = $this->INV->getLateFee($invoice->invoice_id);
                $invoice_total_cost += $invoice_total_tax + $late_fee;
                $total_tax_amount = $invoice_total_tax;


                // END TOTAL INVOICE CALCULATION COST //
                ////////////////////////////////////////

                $nestedData['cost'] = '$ ' . number_format($invoice_total_cost, 2);
                $due = $invoice_total_cost - $invoice->partial_payment;
                // Make sure the value takes into account all past partial payments
                $all_invoice_partials_total = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $invoice->invoice_id));
                $paid_already = 0;
                $actual_payment = 0;
                if (count($all_invoice_partials_total) >= 1) {

                    foreach ($all_invoice_partials_total as $paid_amount) {
                        if ($paid_amount->payment_amount > 0) {
                            $paid_already += $paid_amount->payment_amount;
                            $actual_payment += $paid_amount->payment_applied;
                        }
                    }

                }
                $refund_amount = $paid_already - $actual_payment;
                $due = $invoice_total_cost - $paid_already;
                // no negative due
                if ($due < 0) {
                    $due = 0;
                }

                // if invoice is paid, due = 0
                if ($invoice->payment_status == 2) {
                    $due = 0;
                    $paid_already = 0;
                }
                // if invoice is refunded, due = 0
                if ($invoice->payment_status == 4) {
                    $due = 0;
                    $paid_already = 0;
                }
                $nestedData['balance_due'] = 0 ? '$ 0.00' : '$ ' . number_format($due, 2);
                $nestedData['balance_due_unformated'] = $due;
                $nestedData['past_payments'] = 0 ? '$ 0.00' : '$ ' . number_format($paid_already, 2);

                $nestedData['checkbox'] = '<input  name="group_id" type="checkbox"  value="' . $invoice->invoice_id . ':' . $invoice->customer_id . '" invoice_id="' . $invoice->invoice_id . '" balance_due="' . $due . '" past_payments="' . $paid_already . '" class="myCheckBox" />';

                /**
                 * Changes made by Alvaro Muñoz
                 * adding type column to Active Invoices of payment method
                 */

                // Payment method //
                ////////////////////////
                switch ($invoice->payment_method){
                    case "":
                        $payment_method = "";
                        break;
                    case 0:
                        $payment_method = "Cash";
                        break;
                    case 1:
                        $payment_method = "Check";
                        break;
                    case 2:
                    case 4:
                        $payment_method = "Credit Card";
                        break;
                    case 3:
                        $payment_method = "Other";
                        break;
                    default:
                        $payment_method = $invoice->payment_method;
                }


                $nestedData['payment_method'] = $payment_method;

                // Payment Info //
                ////////////////////////
                ///
                /*
                 if ($data['payment_method'] == 1) {
                            $check_number = $data['payment_info'];
                        } else if ($data['payment_method'] == 2 || $data['payment_method'] == 4) {
                              $cc_number = $data['payment_info'];
                        } else if ($data['payment_method'] == 3) {
                            $other = $data['payment_info'];
                        }
                */

                if (isset($invoice->payment_method) && $invoice->payment_method == '1') {
                    $nestedData['payment_info'] = $invoice->check_number;
                } else if (isset($invoice->payment_method) && ($invoice->payment_method == '0' || $invoice->payment_method == '3')) {
                    $nestedData['payment_info'] = $invoice->other_note;
                } else {
                    $nestedData['payment_info'] = $invoice->cc_number;
                }


                $status = "";
                $sent_date = "";
                $open_date = "";

                $status = '<div class="dropdown">';
                switch ($invoice->status) {
                    case 0:
                        $status .= '<button class="btn btn-default dropdown-toggle label-warning statusCol" type="button" data-toggle="dropdown">Unsent
                                    <span class="caret"></span></button>';
                        $bg = 'bg-warning';
                        break;
                    case 1:
                        $status .= '<button class="btn btn-default dropdown-toggle label-danger statusCol" type="button" data-toggle="dropdown">Sent
                                    <span class="caret"></span></button>';
                        $bg = 'bg-danger';
                        $sent_date = isset($invoice->sent_date) ? date('Y-m-d', strtotime($invoice->sent_date)) : "";
                        break;
                    case 2:
                        $status .= '<button class="btn btn-default dropdown-toggle label-success statusCol" type="button" data-toggle="dropdown">Opened
                                    <span class="caret"></span></button>';
                        $bg = 'bg-success';
                        $sent_date = isset($invoice->sent_date) ? date('Y-m-d', strtotime($invoice->sent_date)) : "";
                        $open_date = isset($invoice->opened_date) ? date('Y-m-d', strtotime($invoice->opened_date)) : "";
                        break;
                    case 3: //the old status == 3 was for partial payments
                        $status .= '<button class="btn btn-default dropdown-toggle label-till statusCol" type="button" data-toggle="dropdown">Opened
                                    <span class="caret"></span></button>';
                        $bg = 'bg-till';
                        $sent_date = isset($invoice->sent_date) ? date('Y-m-d', strtotime($invoice->sent_date)) : "";
                        $open_date = isset($invoice->opened_date) ? date('Y-m-d', strtotime($invoice->opened_date)) : "";
                        break;
                }

                $status .= '<ul class="dropdown-menu">
                                <li class="changestatus"  invoice_id="' . $invoice->invoice_id . '" value="0" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li>
                                <li class="changestatus" invoice_id="' . $invoice->invoice_id . '" value="1" ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>
                                <li class="changestatus" invoice_id="' . $invoice->invoice_id . '" value="2" ><a href="#"><span class="status-mark bg-success position-left"></span> Opened</a></li>
                            </ul>';
                $status .='<div>';

                $nestedData['status'] = $status;
                $payStatus = "";
                $payment_date = "";
                $all_invoice_partials = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $invoice->invoice_id));
                $refund_date = "";
                // $all_refunds = $this->RefundPaymentModel->getAllPartialRefund(array('invoice_id'=>$invoice->invoice_id));
                // // foreach($all_refunds as $d => $date){
                // //     $refund_date[$d] = $date->refund_datetime;
                // // }
                // // die(print_r($refund_date));
                // if(!empty($all_refunds[0])){
                //     $refund_date = $all_refunds[0]->refund_datetime; // only getting most recent refund date
                // }

                $partial_amount_paid = 0;
                if (count($all_invoice_partials) > 0) {
                    foreach ($all_invoice_partials as $paid_amount) {
                        $partial_amount_paid += number_format($paid_amount->payment_amount, 2, '.', '');
                    }
                }
                $payStatus = '<div class="dropdown">';
                switch ($invoice->payment_status) {
                    case 0:
                        if ($partial_amount_paid > 0) {
                            if ($due <= 0) {
                                $payStatus .= '<button class="btn btn-default dropdown-toggle label-success statusCol" type="button" data-toggle="dropdown">Paid
                                    <span class="caret"></span></button>';
                                $bg = 'bg-success';
                                $payment_date = isset($invoice->payment_created) ? ($invoice->payment_created == '0000-00-00 00:00:00' ? ($invoice->last_modify != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($invoice->last_modify)) : '') : date('Y-m-d', strtotime($invoice->payment_created))) : "";
                                break;
                            } else if ($due > 0) {
                                $payStatus .= '<button class="btn btn-default dropdown-toggle label-till statusCol" type="button" data-toggle="dropdown">Partial
                                    <span class="caret"></span></button>';
                                $bg = 'bg-till';
                                $payment_date = isset($invoice->payment_created) ? ($invoice->payment_created == '0000-00-00 00:00:00' ? ($invoice->last_modify != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($invoice->last_modify)) : '') : date('Y-m-d', strtotime($invoice->payment_created))) : "";
                                break;
                            }
                        } else {
                            $payStatus .= '<button class="btn btn-default dropdown-toggle label-warning statusCol" type="button" data-toggle="dropdown">Unpaid
                                    <span class="caret"></span></button>';
                            $bg = 'bg-warning';
                        }

                        break;
                    case 1:
                        $payStatus .= '<button class="btn btn-default dropdown-toggle label-till statusCol" type="button" data-toggle="dropdown">Partial
                                    <span class="caret"></span></button>';
                        $bg = 'bg-till';
                        $payment_date = isset($invoice->payment_created) ? ($invoice->payment_created == '0000-00-00 00:00:00' ? ($invoice->last_modify != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($invoice->last_modify)) : '') : date('Y-m-d', strtotime($invoice->payment_created))) : "";

                        break;
                    case 2:
                        $payStatus .= '<button class="btn btn-default dropdown-toggle label-success statusCol" type="button" data-toggle="dropdown">Paid
                                    <span class="caret"></span></button>';
                        $bg = 'bg-success';
                        $payment_date = isset($invoice->payment_created) ? ($invoice->payment_created == '0000-00-00 00:00:00' ? ($invoice->last_modify != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($invoice->last_modify)) : '') : date('Y-m-d', strtotime($invoice->payment_created))) : "";
                        break;
                    case 3:
                        $payStatus .= '<button class="btn btn-default dropdown-toggle label-danger statusCol" type="button" data-toggle="dropdown">Past Due
                                    <span class="caret"></span></button>';
                        $bg = 'bg-danger';
                        break;
                    case 4:
                        $payStatus .= '<button class="btn btn-default dropdown-toggle label-refunded statusCol" type="button" data-toggle="dropdown">Refunded
                                    <span class="caret"></span></button>';
                        $bg = 'bg-refunded';
                        $payment_date = isset($invoice->payment_created) ? ($invoice->payment_created == '0000-00-00 00:00:00' ? ($invoice->last_modify != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($invoice->last_modify)) : '') : date('Y-m-d', strtotime($invoice->payment_created))) : "";
                        $refund_date = isset($invoice->refund_datetime) ? date('Y-m-d', strtotime($invoice->refund_datetime)) : "";
                        break;
                }

                if ($due < $invoice_total_cost && $due > 0) {
                    $payStatus .= '<ul class="dropdown-menu">
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="1"  over_all_total="' . floatval($invoice->cost + $total_tax_amount) . '" partial_payment="' . floatval($invoice->partial_payment) . '">
                                            <a href="#"><span class="status-mark bg-till position-left" ></span> Partial</a>
                                        </li>
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="2" >
                                            <a href="#"><span class="status-mark bg-success position-left"></span> Paid</a>
                                        </li>
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="3">
                                            <a href="#"><span class="status-mark bg-danger position-left"></span> Past Due</a>
                                        </li>
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="4">
                                        <a href="#"><span class="status-mark bg-refunded position-left"></span> Refund </a></li>
                                    </ul>';
                    // Paid Status
                } else if ($due < $invoice_total_cost && $due <= 0 && $invoice->payment_status != 4) {
                    $payStatus .= '<ul class="dropdown-menu dropdown-menu-right" >
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="4">
                                        <a href="#"><span class="status-mark bg-refunded position-left"></span> Refund </a></li>
                                    </ul>';
                    // Past Due Status
                } else if ($invoice->payment_status == 3) {
                    $payStatus .= '<ul class="dropdown-menu dropdown-menu-right" >
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="1"  over_all_total="' . floatval($invoice->cost + $total_tax_amount) . '" partial_payment="' . floatval($invoice->partial_payment) . '">
                                            <a href="#"><span class="status-mark bg-till position-left" ></span> Partial</a>
                                        </li>
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="2" >
                                            <a href="#"><span class="status-mark bg-success position-left"></span> Paid</a>
                                        </li>
                                    </ul>';
                    // Refund Status
                } else if ($invoice->payment_status == 4) {
                    $payStatus .= '<ul class="dropdown-menu dropdown-menu-right" >
                                        <li  total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="">
                                            <a href="#"><span class="status-mark bg-refunded position-left"></span> Refunded </a>
                                        </li>
                                    </ul>';
                } else {
                    $payStatus .= '<ul class="dropdown-menu dropdown-menu-right" >
                                        <li class="changepayment"  total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="0" >
                                            <a href="#"><span class="status-mark bg-warning position-left"></span> Unpaid</a>
                                        </li>
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="1"  over_all_total="' . floatval($invoice->cost + $total_tax_amount) . '" partial_payment="' . floatval($invoice->partial_payment) . '">
                                            <a href="#"><span class="status-mark bg-till position-left" ></span> Partial</a>
                                        </li>
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="2" >
                                            <a href="#"><span class="status-mark bg-success position-left"></span> Paid</a>
                                        </li>
                                        <li class="changepayment" total_due="' . $due . '" invoice_id="' . $invoice->invoice_id . '" value="3">
                                            <a href="#"><span class="status-mark bg-danger position-left"></span> Past Due</a>
                                        </li>
                                    </ul>';
                }
                $payStatus .='</div>';

                $payStatus .= '
                <button type="submit" data-toggle="modal" data-target="#modal_theme_primary_partial_payment_'.$invoice->invoice_id.'" class="btn btn-success" style="display: none;" id="modal_theme_primary_partial_payment_btn_'.$invoice->invoice_id.'">Open Modal</button>
                <button type="submit" data-toggle="modal" data-target="#modal_theme_primary_paid_payment_'.$invoice->invoice_id.'" class="btn btn-success" style="display: none;" id="modal_theme_primary_paid_payment_btn_'.$invoice->invoice_id.'">Open Modal</button>
                <button type="submit" data-toggle="modal" data-target="#modal_theme_primary_refund_payment_'.$invoice->invoice_id.'" class="btn btn-success" style="display: none;" id="modal_theme_primary_refund_payment_btn_'.$invoice->invoice_id.'">Open Modal</button>
                <!-- partial payment modal -->
                <button type="submit" data-toggle="modal" data-target="#modal_theme_primary_partial_payment_'.$invoice->invoice_id.'" class="btn btn-success" style="display: none;" id="modal_theme_primary_partial_payment_btn">Open Modal</button>
                <div id="modal_theme_primary_partial_payment_'.$invoice->invoice_id.'" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h6 class="modal-title">Add a new partial payment</h6>
                            </div>';
                $form_url = base_url('admin/Invoices/changePaymentStatus');
                $payStatus .= '<form id="add_partial_payment_form' . $invoice->invoice_id . '" action="' . $form_url . '" method="post" style="padding: 10px;" >';

                if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {

                    $payStatus .= '<label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Recorded Partial Payments:</label>';

                    foreach ($all_invoice_partials as $partial_instance) {

                        // display past partial payment in popup module
                        $payStatus .= '<input type="text" class="form-control" name="" placeholder="Enter Cost" value="$ ' . number_format($partial_instance->payment_amount, 2, '.', '') . '" style="margin-bottom: 5px;" readonly >';
                    }

                    $payStatus .= '<div style="height: 10px;"></div>';
                }

                // display sum of past partial payments (old way)
                $payStatus .= '<label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Partial Payments Total:</label>';
                $payStatus .= '<input type="text" class="form-control" name="" placeholder="Enter Cost" value="$ ' . number_format($partial_amount_paid, 2, '.', '') . '" style="margin-bottom: 5px;" readonly >';
                $payStatus .= '<div style="height: 10px;"></div>';
                //$payStatus .= "<h1>".json_encode($all_invoice_partials)."</h1>";

                // display form to add new partial payment
                $payStatus .= '<label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Add New Partial Payment:</label>';
                $payStatus .= '<input type="text" class="form-control" name="partial_payment" placeholder="Enter Cost" value="">';
                $payStatus .= '<input type="hidden" name="invoice_id" value="' . $invoice->invoice_id . '">';
                $payStatus .= '<input type="hidden" name="payment_status" value="1">';
                $payStatus .= '<input type="hidden" name="total_due" value="' . number_format($due, 2) . '">';
                $payStatus .= '<select class="bootstrap-select form-control" name="payment_method" style="border: 1px solid #12689b; margin-top: 5px;">
    <option value="3">Select A Payment Method</option>
    <option value="0">Cash</option>
    <option value="1">Check</option>
    <option value="2">Credit Card</option>
    <option value="3">Other</option>
</select>';
                $payStatus .= '<div style="height: 10px;"></div>';
                $payStatus .= '<input type="text" class="form-control" name="payment_info" value="" placeholder="Enter Payment Info" >';

                $payStatus .= '<div style="height: 20px;"></div>';
                $payStatus .= '<button type="submit" class="btn btn-success">Submit</button>';

                $payStatus .= '</form></div></div></div><!-- /partial payment modal --><!-- paid payment modal -->
<button type="submit" data-toggle="modal" data-target="#modal_theme_primary_paid_payment_'.$invoice->invoice_id.'" class="btn btn-success" style="display: none;" id="modal_theme_primary_paid_payment_btn">Open Modal</button>
<div id="modal_theme_primary_paid_payment_'.$invoice->invoice_id.'" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Full Payment</h6>
            </div>';
                $form_url = base_url('admin/Invoices/changePaymentStatus');
                $payStatus .= '<form id="add_paid_payment_form' . $invoice->invoice_id . '" action="' . $form_url . '" method="post" style="padding: 10px;" >';

                if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {

                    $payStatus .= '<label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Recorded Partial Payments:</label>';

                    foreach ($all_invoice_partials as $partial_instance) {

                        // display past partial payment in popup module
                        $payStatus .= '<input type="text" class="form-control" name="" placeholder="Enter Cost" value="$ ' . number_format($partial_instance->payment_amount, 2, '.', '') . '" style="margin-bottom: 5px;" readonly >';
                    }

                    $payStatus .= '<div style="height: 10px;"></div>';
                }

                // display sum of past partial payments (old way)
                $payStatus .= '<label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Partial Payments Total:</label>';
                $payStatus .= '<input type="text" class="form-control" name="" placeholder="Enter Cost" value="$ ' . number_format($partial_amount_paid, 2, '.', '') . '" style="margin-bottom: 5px;" readonly >';
                $payStatus .= '<div style="height: 10px;"></div>';
                //$payStatus .= "<h1>".json_encode($all_invoice_partials)."</h1>";

                // display form to add new partial payment
                $payStatus .= '<label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Total Amount Due:</label>';
                $payStatus .= '<input type="text" class="form-control" name="paid_payment" value="' . number_format($due, 2, '.', ',') . '" readonly>';
                $payStatus .= '<input type="hidden" name="invoice_id" value="' . $invoice->invoice_id . '">';
                $payStatus .= '<input type="hidden" name="payment_status" value="2">';
                $payStatus .= '<input type="hidden" name="total_due" value="' . number_format($due, 2, '.', ' ') . '">';
                $payStatus .= '<select class="bootstrap-select form-control" name="payment_method" style="border: 1px solid #12689b; margin-top: 5px;">
    <option value="3">Select A Payment Method</option>
    <option value="0">Cash</option>
    <option value="1">Check</option>
    <option value="2">Credit Card</option>
    <option value="3">Other</option>
</select>';
                $payStatus .= '<div style="height: 10px;"></div>';
                $payStatus .= '<input type="text" class="form-control" name="payment_info" value="" placeholder="Enter Payment Info" >';

                $payStatus .= '<div style="height: 20px;"></div>';
                $payStatus .= '<button type="submit" class="btn btn-success">Submit</button>';

                $payStatus .= '</form>
        </div>
    </div>
</div>
<!-- /partial payment modal -->
<!-- refund payment modal -->
<button type="submit" data-toggle="modal" data-target="#modal_theme_primary_refund_payment_'.$invoice->invoice_id.'" class="btn btn-success" style="display: none;" id="modal_theme_primary_refund_payment_btn">Open Modal</button>
<div id="modal_theme_primary_refund_payment_'.$invoice->invoice_id.'" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h6 class="modal-title">Refund Payment</h6>
            </div>';
                $form_url = base_url('admin/Invoices/changePaymentStatus');
                $payStatus .= '<form id="add_refund_payment_form' . $invoice->invoice_id . '" action="' . $form_url . '" method="post" style="padding: 10px;" class="refund_status_form">';

                if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {

                    $payStatus .= '<label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Recorded Partial Payments:</label>';

                    foreach ($all_invoice_partials as $partial_instance) {

                        // display past partial payment in popup module
                        $payStatus .= '<input type="text" class="form-control" name="" placeholder="Enter Cost" value="$ ' . number_format($partial_instance->payment_amount, 2, '.', '') . '" style="margin-bottom: 5px;" readonly >';
                    }

                    $payStatus .= '<div style="height: 10px;"></div>';
                }

                // display sum of past partial payments (old way)
                $payStatus .= '<label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Past Partial Payments Total:</label>';
                $payStatus .= '<input type="text" class="form-control" name="" placeholder="Enter Cost" value="$ ' . number_format($partial_amount_paid, 2, '.', '') . '" style="margin-bottom: 5px;" readonly >';
                $payStatus .= '<div style="height: 10px;"></div>';
                //$payStatus .= "<h1>".json_encode($all_invoice_partials)."</h1>";

                // display form to add new partial payment
                $payStatus .= '<label class="control-label new_label_control" style="font-size: 17px; margin-left: 5px;">Total Refund Due:</label>';
                $payStatus .= '<input type="text" class="form-control" name="refund_payment" value="' . number_format($partial_amount_paid, 2) . '" readonly>';
                $payStatus .= '<input type="hidden" name="invoice_id" value="' . $invoice->invoice_id . '">';

                $payStatus .= '<input type="hidden" name="payment_status" value="4">';
                $payStatus .= '<input type="hidden" name="total_due" value="' . number_format($due, 2) . '">';
                $payStatus .= '
<select class="bootstrap-select form-control" name="payment_method" style="border: 1px solid #12689b; margin-top: 5px;">
    <option value="3">Select A Payment Method</option>
    <option value="0">Cash</option>
    <option value="1">Check</option>
    <option value="2">Credit Card</option>
    <option value="3">Other</option>
</select>';
                $payStatus .= '<div style="height: 10px;"></div>';
                $payStatus .= '<input type="text" class="form-control" name="refund_note" value="" placeholder="Enter Payment Info" >';

                $payStatus .= '<div style="height: 20px;"></div>';
                $payStatus .= '<button type="submit" class="btn btn-success">Submit</button>';

                $payStatus .= '</form>
        </div>
    </div>
</div>
<!-- /refund payment modal -->
<script>
//   function refund_modal_main(){
//    $("#modal_theme_primary_paid_payment_'.$invoice->invoice_id.'").css("display", "none");
//      $.ajax({
//          type: "POST",
//          url: "'.$form_url.'",
//          data: $(this).serialize()
//      }).done(function(data){
//          $("#loading").css("display","none");
//          
//              if (data=="true") {
//                  swal(
//                      "Full Refund",
//                      "Successfully Issued",
//                      "success"
//                  ).then(function() {
//                      location.reload();
//                  });
//              } else {
//                  swal({
//                      type: "error",
//                      title: "Oops...",
//                      text: "Something went wrong!"
//                  })
//              }
//      });
// }
</script>
<script>
// AJAX partial payment form
$("#add_partial_payment_form'.$invoice->invoice_id.'").submit(function(e) {
    e.preventDefault();
    $("#modal_theme_primary_partial_payment_'.$invoice->invoice_id.'").css("display", "none");
    $.ajax({
        type: "POST",
        url: "'.$form_url.'",
        data: $(this).serialize()
    }).done(function(data){
        $("#loading").css("display","none");
        
            if (data=="true") {
                swal(
                    "Partial Payment",
                    "Added Successfully",
                    "success"
                ).then(function() {
                    location.reload();
                });
            } else if (data=="set to paid") {
                swal(
                    "Invoice set to paid",
                    "Partial Payment exceeded total cost",
                    "success"
                ).then(function() {
                    location.reload();
                });
            } else {
                swal({
                    type: "error",
                    title: "Oops...",
                    text: "Something went wrong!"
                })
            }
    });
})
</script>
<script>
// AJAX paid payment form
$("#add_paid_payment_form'.$invoice->invoice_id.'").submit(function(e) {
    e.preventDefault();
    $("#modal_theme_primary_paid_payment_'.$invoice->invoice_id.'").css("display", "none");
    $.ajax({
        type: "POST",
        url: "'.$form_url.'",
        data: $(this).serialize()
    }).done(function(data){
        $("#loading").css("display","none");
        
            if (data=="true") {
                swal(
                    "Full Payment",
                    "Added Successfully",
                    "success"
                ).then(function() {
                    location.reload();
                });
            } else {
                swal({
                    type: "error",
                    title: "Oops...",
                    text: "Something went wrong!"
                })
            }
    });
})
</script>
<script>
// AJAX refund payment form
$("#add_refund_payment_form'.$invoice->invoice_id.'").submit(function(e) {
    e.preventDefault();
    $("#modal_theme_primary_refund_payment_'.$invoice->invoice_id.'").css("display", "none");
    $.ajax({
        type: "POST",
        url: "'.$form_url.'",
        data: $(this).serialize()
    }).done(function(data){
        $("#loading").css("display","none");
        
            if (data=="true") {
                swal(
                    "Full Refund",
                    "Refunded Successfully",
                    "success"
                ).then(function() {
                    location.reload();
                });
            } else {
                swal({
                    type: "error",
                    title: "Oops...",
                    text: "Something went wrong!"
                })
            }
    });
})
</script>';

                $nestedData['payment_status'] = $payStatus;

                $nestedData['invoice_date'] = date('m-d-Y', strtotime($invoice->invoice_date));

                if (!empty($sent_date)) {
                    $sent_date = date('m-d-Y', strtotime($sent_date));
                }
                $nestedData['sent_date'] = $sent_date;

                if (!empty($open_date)) {
                    $open_date = date('m-d-Y', strtotime($open_date));
                }
                $nestedData['opened_date'] = $open_date;

                if (!empty($payment_date)) {
                    $payment_date = date('m-d-Y', strtotime($payment_date));
                }
                $nestedData['payment_created'] = $payment_date;

                if (!empty($refund_date)) {
                    $refund_date = date('m-d-Y', strtotime($refund_date));
                }
                $nestedData['refund_datetime'] = $refund_date;

                //check for invoice job id (only available for old invoice methods... will need to code for new invoice methods in later phase)

//                if (isset($invoice->job_id)) {
//
//                    $actions = '<span class="pr-5"><a class="email button-next" id="' . $invoice->invoice_id . '" customer_id="' . $invoice->customer_id . '"><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a class=""><i class="icon-info22 position-center" style="color: #9a9797;" onclick="productDetailsGet(' . $invoice->job_id . ')"></i></a></span><span><a href="' . base_url('admin/Invoices/printInvoice/') . $invoice->invoice_id . '" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a></span>';
//                } else {
//
//                    $actions = '<span class="pr-5"><a class="email button-next" id="' . $invoice->invoice_id . '" customer_id="' . $invoice->customer_id . '"><i class="icon-envelop3 position-center" style="color: #9a9797;"></i></a></span><span class="pr-5"><a class=""><i class="icon-info22 position-center" style="color: #9a9797;" onclick="productDetailsGetByInvoice(' . $invoice->invoice_id . ')"></i></a></span><span><a href="' . base_url('admin/Invoices/printInvoice/') . $invoice->invoice_id . '" target="_blank" class=" button-next"><i class="icon-printer2 position-center" style="color: #9a9797;"></i></a></span>';
//                }
//
//                $nestedData['actions'] = $actions;

                $data[] = $nestedData;
            }
        }
//        die($order);
        if ($order == 'balance_due') {

            $key_values = array_column($data, 'balance_due_unformated');
            array_multisort($key_values, ($dir == 'asc')?SORT_ASC:SORT_DESC ,SORT_NUMERIC,  $data);

        }
        if ($order == 'payment_info') {

            $key_values = array_column($data, 'payment_info');
            array_multisort($key_values, ($dir == 'asc')?SORT_ASC:SORT_DESC ,SORT_STRING,  $data);

        }

        $json_data = array(

            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $var_total_item_count_for_pagination, // "(filtered from __ total entries)"
            "recordsFiltered" => $var_total_item_count_for_pagination, // actual total that determines page counts
            "data" => $data,

        );

        echo json_encode($json_data);
    }

    public function ajaxGetTotalOfDueInvoices()
    {
        $limit = 0;

        $start = 0;

        $order = 'invoice_id';

        $dir = 'DESC';

        // WHERE:
        $year = date("Y");
        $whereArr = array(
            'invoice_tbl.company_id' => $this->session->userdata['company_id'],
            'is_archived' => 0,
            'invoice_date >' => $year.'-01-01',
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
        $allInv = $this->INV->getAllInvoive($whereArr);
        $totalData = count((array) $allInv);
        $filteredData = $totalData;

        if (empty($this->input->post('search')['value'])) {

            $invoices = $this->INV->ajaxActiveInvoicesTech($whereArr, $limit, $start, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, false);
            $var_total_item_count_for_pagination = $this->INV->ajaxActiveInvoicesTech($whereArr, $limit, $start, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        } else {

            $search = $this->input->post('search')['value'];
            $invoices = $this->INV->ajaxActiveInvoicesSearchTech($whereArr, $limit, $start, $search, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, false);
            $var_total_item_count_for_pagination = $this->INV->ajaxActiveInvoicesSearchTech($whereArr, $limit, $start, $search, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, true);
            $var_total_item_count_for_pagination = count($var_total_item_count_for_pagination);
        }
        //function 2
        $due_amount =0;
        $total_billed = 0;
        if (!empty($invoices)) {
            foreach ($invoices as $invoice) {

                $nestedData['checkbox'] = '<input  name="group_id" type="checkbox"  value="' . $invoice->invoice_id . ':' . $invoice->customer_id . '" invoice_id="' . $invoice->invoice_id . '" class="myCheckBox" />';

                $nestedData['invoice_id'] = '<a href="' . base_url('admin/Invoices/editInvoice/') . $invoice->invoice_id . '">' . $invoice->invoice_id . '</a>';

                $nestedData['customer_id'] = '<a href="' . base_url('admin/editCustomer/') . $invoice->customer_id . '">' . $invoice->customer_name . '</a>';
                $nestedData['email'] = $invoice->email;

                $total_tax_amount = getAllSalesTaxSumByInvoice($invoice->invoice_id)->total_tax_amount;
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
                                    $coupon_job_amm_total = (float) $coupon_job_amm;
                                } else { // percentage
                                    $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
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
                            $invoice_total_cost -= (float) $coupon_invoice_amm;
                        } else { // percentage
                            $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
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
                            $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                            $invoice_total_tax += $tax_amm_to_add;
                        }
                    }
                }
                $invoice_total_cost += $invoice_total_tax;
                $total_tax_amount = $invoice_total_tax;
                $total_billed += $invoice_total_cost;
                // END TOTAL INVOICE CALCULATION COST //
                ////////////////////////////////////////

                $nestedData['cost'] = '$ ' . number_format($invoice_total_cost, 2);
                $due = $invoice_total_cost - $invoice->partial_payment;
                // Make sure the value takes into account all past partial payments
                $all_invoice_partials_total = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $invoice->invoice_id));

                if (count($all_invoice_partials_total) > 0) {
                    $paid_already = 0;
                    $actual_payment = 0;
                    foreach ($all_invoice_partials_total as $paid_amount) {
                        if ($paid_amount->payment_amount > 0) {
                            $paid_already += $paid_amount->payment_amount;
                            $actual_payment += $paid_amount->payment_applied;
                        }
                    }
                    $refund_amount = $paid_already - $actual_payment;
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
                // if invoice is refunded, due = 0
                if ($invoice->payment_status == 4) {
                    $due = 0;
                }
                $due_amount += $due;
            }
        }
        echo json_encode(['status'=>'success','due_amount_total'=>number_format($due_amount,2),'total_billed_amount'=> number_format($total_billed, 2)]);
    }

    public function getAllInvoiceBySearch($status)
    {

        $where = array('invoice_tbl.company_id' => $this->session->userdata['company_id']);

        if ($status != 4) {
            $where['status'] = $status;
        }

        $data['invoice_details'] = $this->INV->getAllInvoive($where);

        $body = $this->load->view('invoice/ajax_data', $data, true);

        echo $body;
    }

    public function GetProcuctDetails($job_id)
    {

        $product_details = $this->ProductModel->getAssignProducts(array('job_id' => $job_id));

        $html = '<div class="table-responsive"><table class="table tablemodal table-framed">
                    <thead>
                      <tr>
                        <td>Product Name</td>
                        <td>EPA #</td>
                        <td>Application Rate</td>
                        <td>Active Ingredient</td>
                      </tr>
                    </thead>
                    <tbody>';

        $html2 = '</tbody>
                  </table></div>';

        if ($product_details) {

            foreach ($product_details as $key => $value) {

                if (!empty($value->application_rate) && $value->application_rate != 0) {

                    $application_rate = $value->application_rate . ' ' . $value->application_unit . ' / ' . $value->application_rate_per . ' ' . $value->application_per_unit;
                } else {

                    $application_rate = '';
                }

                $ingredientDatails = $this->ProductModel->getAllIngredient(array('product_id' => $value->product_id));

                $html .= '<tr >
                                <td>' . $value->product_name . '</td>
                                <td>' . $value->epa_reg_nunber . '</td>
                                <td>' . $application_rate . ' </td>
                                <td>';

                if ($ingredientDatails) {

                    foreach ($ingredientDatails as $key2 => $value2) {

                        $html .= '<span>' . $value2->active_ingredient . ' : ' . $value2->percent_active_ingredient . ' % </span><br> ';
                    }
                }

                $html .= '</td>
                                    </tr>';
            }

            $html .= $html2;

            $return_array = array('status' => 200, 'msg' => "Data get successfully", 'result' => $html);
        } else {

            $html .= '<tr>
                  <td colspan="4" style="text-align : center" >No Data Found</td>
              </tr>';

            $html .= $html2;

            $return_array = array('status' => 400, 'msg' => "Data not fond", 'result' => $html);
        }

        echo json_encode($return_array);
    }

    public function GetProductDetailsByInvoice($invoice_id)
    {

        $product_details = $this->ProductModel->getAssignProductsByInvoice($invoice_id);

        //die(print_r($product_details));

        $html = '<div class="table-responsive"><table class="table tablemodal table-framed">
                    <thead>
                      <tr>
                        <td>Product Name</td>
                        <td>EPA #</td>
                        <td>Application Rate</td>
                        <td>Active Ingredient</td>
                      </tr>
                    </thead>
                    <tbody>';

        $html2 = '</tbody>
                  </table></div>';

        if ($product_details) {

            foreach ($product_details as $key => $value) {

                if (!empty($value->application_rate) && $value->application_rate != 0) {

                    $application_rate = $value->application_rate . ' ' . $value->application_unit . ' / ' . $value->application_rate_per . ' ' . $value->application_per_unit;
                } else {

                    $application_rate = '';
                }

                $ingredientDatails = $this->ProductModel->getAllIngredient(array('product_id' => $value->product_id));

                $html .= '<tr >
                                <td>' . $value->product_name . '</td>
                                <td>' . $value->epa_reg_nunber . '</td>
                                <td>' . $application_rate . ' </td>
                                <td>';

                if ($ingredientDatails) {

                    foreach ($ingredientDatails as $key2 => $value2) {

                        $html .= '<span>' . $value2->active_ingredient . ' : ' . $value2->percent_active_ingredient . ' % </span><br> ';
                    }
                }

                $html .= '</td>
                                    </tr>';
            }

            $html .= $html2;

            $return_array = array('status' => 200, 'msg' => "Data get successfully", 'result' => $html);
        } else {

            $html .= '<tr>
                  <td colspan="4" style="text-align : center" >No Data Found</td>
              </tr>';

            $html .= $html2;

            $return_array = array('status' => 400, 'msg' => "Data not fond", 'result' => $html);
        }

        echo json_encode($return_array);
    }






    public function assignCustomerList()
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
                echo"</li>";
        
            } 
            
            echo "</ul>";        
        } 
        else{
            return false;
        }

        
       //return $data;
        
    }








    public function addInvoice()
    {

        $where = array('company_id' => $this->session->userdata['company_id']);

        $data['customer_details'] = $this->CustomerModel->get_all_customer($where);
        $where = array('jobs.company_id' => $this->session->userdata['company_id']);
        $data['job_details'] = $this->JobModel->getAllJob($where);
        $where = array('company_id' => $this->session->userdata['company_id']);
        $data['program_details'] = $this->ProgramModel->get_all_program($where);
        $where = array('company_id' => $this->session->userdata['company_id']);
        $coupon_where = array(
            'company_id' => $this->session->userdata['company_id'],
            'type' => 0,
        );
        $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);

        $page["active_sidebar"] = "invoicenav";

        $page["page_name"] = 'Add Invoice';

        $page["page_content"] = $this->load->view("admin/invoice/add_invoice", $data, true);

        $this->layout->superAdminTemplateTable($page);
    }

    public function getPropertyAddress()
    {

        $customer_id = $this->input->post('customer_id');

        $data = $this->CustomerModel->getAllproperty(array('customer_id' => $customer_id));

        if (!empty($data)) {

            echo '<option value="">Select any property</option>';

            foreach ($data as $value) {

                echo '<option value="' . $value->property_id . '">' . $value->property_address . '</option>';
            }
        } else {

            echo '<option value="">No property assign</option>';
        }
    }

    public function getcutomerEmail(){
        $customer_id = $this->input->post('customer_id');
        $data = $this->CustomerModel->getCustomerDetail($customer_id);
        echo trim($data['email']);
    }

    public function getPropertyProgram()
    {

        $property_id = $this->input->post('property_id');

        
        $data = $this->PropertyModel->getAllprogram(array('property_id' => $property_id, 'programs.program_price' => 3));
        

        if (!empty($data)) {

            echo '<option value="">Select any program</option>';

            foreach ($data as $value) {

                echo '<option value="' . $value->program_id . '">' . $value->program_name . '</option>';
            }
        } else {

            echo '<option value="">No program assigned</option>';
        }
    }

    public function addInvoiceData()
    {

        $data = $this->input->post();

        //die(print_r($data));

        $this->form_validation->set_rules('customer_id', 'Property Title', 'required');

        $this->form_validation->set_rules('property_id', 'property_id', 'required');

        //$this->form_validation->set_rules('customer_email', 'customer_email', 'required');

        $this->form_validation->set_rules('invoice_date', 'invoice_date', 'required');

        $this->form_validation->set_rules('cost', 'cost', 'trim');

        $this->form_validation->set_rules('job_id', 'job_id', 'trim');

        //$this->form_validation->set_rules('job_id', 'job_id', 'required');

        $this->form_validation->set_rules('program_id', 'program_id', 'required');

        $this->form_validation->set_rules('notes', 'notes', 'trim');

        if ($this->form_validation->run() == false) {

            $this->addInvoice();
        } else {

            $data = $this->input->post();

            //die(print_r($data));

            $user_id = $this->session->userdata['user_id'];

            $company_id = $this->session->userdata['company_id'];

            $where = array('company_id' => $this->session->userdata['company_id']);

            $setting_details = $this->CompanyModel->getOneCompany($where);

            $PPJOBINVarr = array();

            $description = array();

            //create invoice description

            $jobs = explode(',', $data['job_id']);

            $cost_with_service_coupons = 0;

            foreach ($jobs as $key => $job) {

                //$prop_prog = $this->PropertyModel->getOnePropertyProgram(array('property_id'=>$data['property_id'], 'program_id'=>$data['program_id']));

                $PPJOBINVarr[$key] = array(

                    'customer_id' => $data['customer_id'],

                    'property_id' => $data['property_id'],

                    'program_id' => $data['program_id'],

                    'job_id' => $job,

                    'created_at' => date("Y-m-d"),

                    'updated_at' => date("Y-m-d"),

                );

                if (is_array($data['cost']) && array_key_exists($job, $data['cost'])) {

                    $PPJOBINVarr[$key]['job_cost'] = $data['cost'][$job];
                }

                //get job details

                $job_details = $this->JobModel->getOneJob(array('job_id' => $job));

                $coup_job_param = array(
                    'cost' => $job_details->job_price,
                    'job_id' => $job,
                    'customer_id' => $data['customer_id'],
                    'property_id' => $data['property_id'],
                    'program_id' => $data['program_id']
                );

                $cost_with_service_coupons += $this->calculateServiceCouponCost($coup_job_param);

                if ($job_details) {

                    $description[] = $job_details->job_name;
                    $actual_description_for_QBO[] = $job_details->job_description;
                }
            }

            //store job ids in json array

            $json = array(

                'jobs' => $PPJOBINVarr,

                'manual_invoice' => 1,

            );

            $total = array_sum($data['cost']);

            $coup_cust_param = array(
                'cost' => $cost_with_service_coupons,
                'customer_id' => $data['customer_id']
            );

            $cost_with_cust_coupon = $this->calculateCustomerCouponCost($coup_cust_param);


            //die(print_r($total));

            $description = implode(', ', $description);
            $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);

            //invoice params

            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'customer_id' => $data['customer_id'],
                'property_id' => $data['property_id'],
                'invoice_date' => $data['invoice_date'],
                'description' => $description,
                'notes' => $data['notes'],
                'cost' => $total,
                'program_id' => $data['program_id'],
                'is_created' => 1,
                'invoice_created' => date("Y-m-d H:i:s"),
                'json' => json_encode($json),
            );

            // echo '<br>';
            // $QBO_param = $param;
            // $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
            // print_r($QBO_param);
            // die();
            // description ==> is the product/service name
            // notes is the whole invoice notes - not the service notes.

            // get program details

            $property_details = $this->PropertyModel->getOneProperty(array('property_id' => $data['property_id']));

            //create invoice

            $invoice_id = $this->INV->createOneInvoice($param);

            if ($invoice_id) {

                //add invoice id to PPJOBINV array

                foreach ($PPJOBINVarr as $k => $j) {

                    $PPJOBINVarr[$k]['invoice_id'] = $invoice_id;

                    $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($PPJOBINVarr[$k]);
                }

                $param['invoice_id'] = $invoice_id;

                if ($setting_details->is_sales_tax == 1) {

                    $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $data['property_id']));

                    if ($property_assign_tax) {

                        foreach ($property_assign_tax as $tax_details) {

                            $invoice_tax_details = array(

                                'invoice_id' => $invoice_id,

                                'tax_name' => $tax_details['tax_name'],

                                'tax_value' => $tax_details['tax_value'],

                                'tax_amount' => $total * $tax_details['tax_value'] / 100,

                            );

                            $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
                        }
                    }
                }

                //die(print_r($PPJOBINVarr));

                $param['customer_email'] = $data['customer_email'];

                //$param['job_name'] = $job_details ? $job_details->job_name : '';

                $param['job_name'] = $description;

                $QBO_param = $param;
                $QBO_param['property_street'] = explode(',', $property_details->property_address)[0];
                $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;


                $QBO_param['cost'] = $cost_with_cust_coupon;

                $quickbook_invoice_id = $this->QuickBookInv($QBO_param);

                if ($quickbook_invoice_id) {

                    $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('quickbook_invoice_id' => $quickbook_invoice_id));
                }

                // apply assigned coupons
                if (array_key_exists("assign_onetime_coupons", $data)) {
                    $coupon_ids_arr = $data['assign_onetime_coupons'];
                    foreach ($coupon_ids_arr as $coupon_id) {

                        $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                        $params = array(
                            'coupon_id' => $coupon_id,
                            'invoice_id' => $invoice_id,
                            'coupon_code' => $coupon_details->code,
                            'coupon_amount' => $coupon_details->amount,
                            'coupon_amount_calculation' => $coupon_details->amount_calculation,
                            'coupon_type' => $coupon_details->type,
                        );
                        $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                    }
                }

                // check global coupons & assign if so
                $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $data['customer_id']));
                if (!empty($coupon_customers)) {
                    foreach ($coupon_customers as $coupon_customer) {

                        $coupon_id = $coupon_customer->coupon_id;
                        $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                        $params = array(
                            'coupon_id' => $coupon_id,
                            'invoice_id' => $invoice_id,
                            'coupon_code' => $coupon_details->code,
                            'coupon_amount' => $coupon_details->amount,
                            'coupon_amount_calculation' => $coupon_details->amount_calculation,
                            'coupon_type' => $coupon_details->type,
                        );
                        $resp = $this->CouponModel->CreateOneCouponInvoice($params);
                    }
                }
            }

            if ($invoice_id) {

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong>created successfully</div>');

                redirect("admin/Invoices");
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong> not added. Please try again</div>');

                redirect("admin/Invoices");
            }
        }
    }

    public function addInvoiceDatax100()
    {

        $data = $this->input->post();

        //die(print_r($data));

        $this->form_validation->set_rules('customer_id', 'Property Title', 'required');

        $this->form_validation->set_rules('property_id', 'property_id', 'required');

        //$this->form_validation->set_rules('customer_email', 'customer_email', 'required');

        $this->form_validation->set_rules('invoice_date', 'invoice_date', 'required');

        $this->form_validation->set_rules('cost', 'cost', 'trim');

        $this->form_validation->set_rules('job_id', 'job_id', 'trim');

        //$this->form_validation->set_rules('job_id', 'job_id', 'required');

        $this->form_validation->set_rules('program_id', 'program_id', 'required');

        $this->form_validation->set_rules('notes', 'notes', 'trim');

        if ($this->form_validation->run() == false) {

            $this->addInvoice();
        } else {

            $data = $this->input->post();

            //die(print_r($data));

            $user_id = $this->session->userdata['user_id'];

            $company_id = $this->session->userdata['company_id'];

            $where = array('company_id' => $this->session->userdata['company_id']);

            $setting_details = $this->CompanyModel->getOneCompany($where);

            $PPJOBINVarr = array();

            $description = array();

            //create invoice description

            $jobs = explode(',', $data['job_id']);

            foreach ($jobs as $key => $job) {

                //$prop_prog = $this->PropertyModel->getOnePropertyProgram(array('property_id'=>$data['property_id'], 'program_id'=>$data['program_id']));

                $PPJOBINVarr[$key] = array(

                    'customer_id' => $data['customer_id'],

                    'property_id' => $data['property_id'],

                    'program_id' => $data['program_id'],

                    'job_id' => $job,

                    'created_at' => date("Y-m-d"),

                    'updated_at' => date("Y-m-d"),

                );

                if (is_array($data['cost']) && array_key_exists($job, $data['cost'])) {

                    $PPJOBINVarr[$key]['job_cost'] = $data['cost'][$job];
                }

                //get job details

                $job_details = $this->JobModel->getOneJob(array('job_id' => $job));

                if ($job_details) {

                    $description[] = $job_details->job_name;
                    $actual_description_for_QBO[] = $job_details->job_description;
                }
            }

            //store job ids in json array

            $json = array(

                'jobs' => $PPJOBINVarr,

                'manual_invoice' => 1,

            );

            $total = array_sum($data['cost']);

            //die(print_r($total));

            $description = implode(', ', $description);
            $actual_description_for_QBO = implode(', ', $actual_description_for_QBO);

            //invoice params

            $param = array(
                'user_id' => $user_id,
                'company_id' => $company_id,
                'customer_id' => $data['customer_id'],
                'property_id' => $data['property_id'],
                'invoice_date' => $data['invoice_date'],
                'description' => $description,
                'notes' => $data['notes'],
                'cost' => $total,
                'program_id' => $data['program_id'],
                'is_created' => 1,
                'invoice_created' => date("Y-m-d H:i:s"),
                'json' => json_encode($json),
            );

            // echo '<br>';
            // $QBO_param = $param;
            // $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
            // print_r($QBO_param);
            // die();
            // description ==> is the product/service name
            // notes is the whole invoice notes - not the service notes.

            // get program details

            $program_details = $this->PropertyModel->getOneProperty(array('property_id' => $data['property_id']));

            //create invoice
            for ($x = 0; $x <= 100; $x++) {
                $invoice_id = $this->INV->createOneInvoice($param);
            }


//            if ($invoice_id) {
//
//                //add invoice id to PPJOBINV array
//
//                foreach ($PPJOBINVarr as $k => $j) {
//
//                    $PPJOBINVarr[$k]['invoice_id'] = $invoice_id;
//
//                    $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($PPJOBINVarr[$k]);
//                }
//
//                $param['invoice_id'] = $invoice_id;
//
//                if ($setting_details->is_sales_tax == 1) {
//
//                    $property_assign_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $data['property_id']));
//
//                    if ($property_assign_tax) {
//
//                        foreach ($property_assign_tax as $tax_details) {
//
//                            $invoice_tax_details = array(
//
//                                'invoice_id' => $invoice_id,
//
//                                'tax_name' => $tax_details['tax_name'],
//
//                                'tax_value' => $tax_details['tax_value'],
//
//                                'tax_amount' => array_sum($data['cost']) * $tax_details['tax_value'] / 100,
//
//                            );
//
//                            $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($invoice_tax_details);
//                        }
//                    }
//                }
//
//                //die(print_r($PPJOBINVarr));
//
//                $param['customer_email'] = $data['customer_email'];
//
//                //$param['job_name'] = $job_details ? $job_details->job_name : '';
//
//                $param['job_name'] = $description;
//
//                $QBO_param = $param;
//                $QBO_param['actual_description_for_QBO'] = $actual_description_for_QBO;
//
//                $quickbook_invoice_id = $this->QuickBookInv($QBO_param);
//
//                if ($quickbook_invoice_id) {
//
//                    $this->INV->updateInvoive(array('invoice_id' => $invoice_id), array('quickbook_invoice_id' => $quickbook_invoice_id));
//                }
//
//                // apply assigned coupons
//                if (array_key_exists("assign_onetime_coupons", $data)) {
//                    $coupon_ids_arr = $data['assign_onetime_coupons'];
//                    foreach ($coupon_ids_arr as $coupon_id) {
//
//                        $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
//                        $params = array(
//                            'coupon_id' => $coupon_id,
//                            'invoice_id' => $invoice_id,
//                            'coupon_code' => $coupon_details->code,
//                            'coupon_amount' => $coupon_details->amount,
//                            'coupon_amount_calculation' => $coupon_details->amount_calculation,
//                            'coupon_type' => $coupon_details->type,
//                        );
//                        $resp = $this->CouponModel->CreateOneCouponInvoice($params);
//                    }
//                }
//
//                // check global coupons & assign if so
//                $coupon_customers = $this->CouponModel->getAllCouponCustomerCouponDetails(array('customer_id' => $data['customer_id']));
//                if (!empty($coupon_customers)) {
//                    foreach ($coupon_customers as $coupon_customer) {
//
//                        $coupon_id = $coupon_customer->coupon_id;
//                        $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
//                        $params = array(
//                            'coupon_id' => $coupon_id,
//                            'invoice_id' => $invoice_id,
//                            'coupon_code' => $coupon_details->code,
//                            'coupon_amount' => $coupon_details->amount,
//                            'coupon_amount_calculation' => $coupon_details->amount_calculation,
//                            'coupon_type' => $coupon_details->type,
//                        );
//                        $resp = $this->CouponModel->CreateOneCouponInvoice($params);
//                    }
//                }
//            }

            if ($invoice_id) {

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong>created successfully</div>');

                redirect("admin/Invoices");
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong> not added. Please try again</div>');

                redirect("admin/Invoices");
            }
        }
    }

    public function managePartial($value = '')
    {

        $invoice_details = $this->INV->getAllInvoive(array('invoice_tbl.payment_status' => 1));

        if ($invoice_details) {

            foreach ($invoice_details as $key => $value) {

                $where = array(

                    // 'company_id' => $this->session->userdata['company_id'],

                    'invoice_id' => $value->invoice_id,

                );

                $param = array(

                    'partial_payment' => $value->cost + $value->tax_amount,

                );

                $this->INV->updateInvoive($where, $param);
            }
        }
    }

    public function editInvoice($invoice_id)
    {

        $data['customer_details'] = $this->CustomerModel->get_all_customer(array('company_id' => $this->session->userdata['company_id']));

        $data['job_details'] = $this->JobModel->getAllJob(array('jobs.company_id' => $this->session->userdata['company_id']));

        $where = array(

            'invoice_tbl.company_id' => $this->session->userdata['company_id'],

            'invoice_id' => $invoice_id,

        );

        $data['invoice_details'] = $this->INV->getOneInvoive($where);
        //late fee
        $late_fee = $this->INV->getLateFee($invoice_id);
        $data['invoice_details']->late_fee = $late_fee;

        $jobs = array();

        $programs = array();

        if (empty($data['invoice_details']->job_id) || empty($data['invoice_details']->program_id)) {

            //get invoice details from property_program_job_invoice

            $param = array(

                'property_program_job_invoice.invoice_id' => $invoice_id,

            );

            $details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram($param);

            if ($details) {

                foreach ($details as $detail) {

                    $jobs[] = array(

                        'job_id' => $detail['job_id'],

                        'job_name' => $detail['job_name'],

                        'job_description' => $detail['job_description'],

                        'job_cost' => $detail['job_cost'],

                    );

                    $programs[] = $detail['program_id'];
                }

                $data['invoice_details']->jobs = $jobs;

                $data['invoice_details']->programs = array_unique($programs);

                //print_r($details);

            }

            if (!$details && $data['invoice_details']->json) {

                $json = json_decode($data['invoice_details']->json, true);

                //die(print_r($json['jobs']));

                if (isset($json['manual_invoice']) && $json['manual_invoice'] == 1) {

                    $data['invoice_details']->manual_invoice = 1;
                }

                if (is_array($json['jobs'])) {

                    foreach ($json['jobs'] as $job) {

                        //print_r($job);

                        //get job details

                        $job_details = $this->JobModel->getOneJob(array('job_id' => $job['job_id']));

                        if (!empty($job_details)) {
                            $jobs[] = array(
                                'job_id' => $job['job_id'],
                                'job_name' => $job_details->job_name,
                                'job_description' => $job_details->job_description,
                                'job_cost' => $job['job_cost'],
                            );
                        } else {
                            $jobs[] = array(
                                'job_id' => $job['job_id'],
                                'job_name' => '',
                                'job_description' => '',
                                'job_cost' => $job['job_cost'],
                            );
                        }

                        if (isset($job['program_id'])) {

                            $programs[] = $job['program_id'];
                        }
                    }

                    $data['invoice_details']->jobs = $jobs;

                    $data['invoice_details']->programs = array_unique($programs);
                }
            }
        }

        ////////////////////////////////////
        // START INVOICE CALCULATION COST //

        // vars
        $tmp_invoice_id = $invoice_id;
        $total_coupon_discounts = 0;

        // invoice cost
        // $invoice_total_cost = $invoice->cost;

        // cost of all services (with price overrides) - service coupons
        $job_cost_total = 0;
        $where_alt = array(
            'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
        );
        $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where_alt);

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
                            $coupon_job_amm_total = (float) $coupon_job_amm;
                        } else { // percentage
                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                        }

                        $job_cost = $job_cost - $coupon_job_amm_total;
                        $total_coupon_discounts += $coupon_job_amm_total;

                        if ($job_cost < 0) {
                            $job_cost = 0;
                        }
                    }
                }

                $job_cost_total += $job_cost;
            }
            $invoice_total_cost = $job_cost_total;
        } else {
            $invoice_total_cost = $data['invoice_details']->cost;
        }

        // check price override -- any that are not stored in just that ^^.

        // - invoice coupons
        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
        foreach ($coupon_invoice_details as $coupon_invoice) {
            if (!empty($coupon_invoice)) {
                $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                if ($coupon_invoice_amm_calc == 0) { // flat amm
                    $invoice_total_cost -= (float) $coupon_invoice_amm;
                    $total_coupon_discounts += $coupon_invoice_amm;
                } else { // percentage
                    $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                    $invoice_total_cost -= $coupon_invoice_amm;
                    $total_coupon_discounts += $coupon_invoice_amm;
                }
                if ($invoice_total_cost < 0) {
                    $invoice_total_cost = 0;
                }
            }
        }

        // + tax cost
        $invoice_total_tax = 0;
        $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
        if (!empty($invoice_sales_tax_details)) {
            foreach ($invoice_sales_tax_details as $key => $tax) {
                if (array_key_exists("tax_value", $tax)) {
                    $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                    $invoice_total_tax += $tax_amm_to_add;
                    // $data['all_sales_tax'][$key] = $tax;
                    // $data['all_sales_tax'][$key]['tax_amount'] = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                    $update_arr = array(
                        'tax_amount' => $tax_amm_to_add
                    );

                    $where_arr = array(
                        'sales_tax_id' => $tax['sales_tax_id']
                    );

                    $updated = $this->InvoiceSalesTax->updateInvoiceSalesTax($where_arr, $update_arr);
                }
            }
        }

        // die(print_r($data['all_sales_tax']));
        $invoice_total_cost += $invoice_total_tax;
        $total_tax_amount = $invoice_total_tax;

        // END TOTAL INVOICE CALCULATION COST //
        ////////////////////////////////////////

        $data['total_actual_cost'] = $invoice_total_cost;
        $data['total_sub_and_coup'] = $invoice_total_cost - $invoice_total_tax;
        $data['total_coupon_discount'] = $total_coupon_discounts;
        $data['invoice_total_tax'] = $invoice_total_tax;
        // die(print_r($data['invoice_total_tax']));

        $coupon_where = array(
            'company_id' => $this->session->userdata['company_id'],
            'type' => 0,
        );
        $data['customer_one_time_discounts'] = $this->CouponModel->getAllCoupon($coupon_where);

        $data_temp_coupon = $this->CouponModel->getCouponInvoiceIDs(array('invoice_id' => $invoice_id));
        $data['existing_coupon_invoice'] = array();
        if (!empty($data_temp_coupon)) {
            foreach ($data_temp_coupon as $value) {
                $data['existing_coupon_invoice'][] = $value->coupon_id;
            }
        }

        $data['exising_coupon_invoice_data'] = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));
        $data['property_address'] = $this->CustomerModel->getAllproperty(array('customer_id' => $data['invoice_details']->customer_id));
        $data['program_details'] = $this->ProgramModel->get_all_program(array('company_id' => $this->session->userdata['company_id']));
        $data['all_sales_tax'] = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));


        $all_invoice_partials = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $invoice_id));
        $AllInvoiceLogs = $this->PaymentLogModel->getAllPaymentLogs(array('invoice_id' => $invoice_id));

        //die(print_r($this->db->last_query()));
        $data['num_all_invoice_partials'] = count($all_invoice_partials);
        if (isset($all_invoice_partials) && !empty($all_invoice_partials)) {
            $data['all_invoice_partials'] = $all_invoice_partials;
        } else {
            $data['all_invoice_partials'] = array();
        }

        $data['partial_payments_calc'] = 0;
        foreach ($all_invoice_partials as $partial_payment){
            $data['partial_payments_calc'] += $partial_payment->payment_amount;
        }
       //die(print_r( $data['partial_payments_calc']));

        $data["AllInvoiceLogs"] = $AllInvoiceLogs;
        
        $page["active_sidebar"] = "invoicenav";
        $page["page_name"] = 'Update Invoice';
        $page["page_content"] = $this->load->view("admin/invoice/edit_invoice", $data, true);
        $this->layout->superAdminTemplateTable($page);
    }

    public function getSalesTaxDetails($property_id = '')
    {

        $where = array('company_id' => $this->session->userdata['company_id']);

        $setting_details = $this->CompanyModel->getOneCompany($where);

        if ($setting_details->is_sales_tax == 1) {

            if ($property_id != '') {

                $tax_details = getAllSalesTaxByProperty($property_id);

                if ($tax_details) {

                    $return_detais = array('status' => 200, 'msg' => 'tax found successfully', 'result' => $tax_details);
                } else {

                    $return_detais = array('status' => 400, 'msg' => 'tax not found');
                }
            } else {

                $return_detais = array('status' => 400, 'msg' => 'tax not found');
            }
        } else {

            $return_detais = array('status' => 400, 'msg' => 'tax not found');
        }

        echo json_encode($return_detais);
    }

    public function editInvoiceData($invoice_id)
    {

        $data = $this->input->post();

        $company_id = $this->session->userdata['company_id'];
        $where_arr = array(
            'company_id' => $company_id,
            'status' => 1,
        );
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);

        $this->form_validation->set_rules('customer_id', 'Property Title', 'required');

        $this->form_validation->set_rules('property_id', 'property_id', 'required');

        //$this->form_validation->set_rules('customer_email', 'customer_email', 'required');

        $this->form_validation->set_rules('invoice_date', 'invoice_date', 'required');

        $this->form_validation->set_rules('program_id', 'program_id', 'required');

        $this->form_validation->set_rules('job_id', 'job_id', 'trim');

        $this->form_validation->set_rules('payment_status', 'payment_status', 'required');

        $this->form_validation->set_rules('sent_status', 'sent_status', 'required');

        $this->form_validation->set_rules('cost', 'cost', 'required');

        $this->form_validation->set_rules('notes', 'notes', 'trim');

        if ($this->form_validation->run() == false) {

            $this->editInvoice($invoice_id);
        } else {

            //die(print_r($data));

            $jobarray = array();

            $jobs = explode(',', $data['job_ids']);

            $description = array();

            $all_exist_ppjobinv = $this->PropertyProgramJobInvoiceModel->getAllRows(array('invoice_id' => $invoice_id));

            //delete removed services from prop prog job invoice table

            foreach ($all_exist_ppjobinv as $existRow) {

                if (!in_array($existRow['job_id'], $jobs)) {

                    $this->PropertyProgramJobInvoiceModel->deletePropertyProgramJobInvoice(array('property_program_job_invoice_id' => $existRow['property_program_job_invoice_id']));
                }
            }

            foreach ($jobs as $job) {

                if (is_array($data['jobcost']) && array_key_exists($job, $data['jobcost'])) {

                    //get property program job invoice data

                    $exist_ppjobinv = $this->PropertyProgramJobInvoiceModel->getOnePropertyProgramJobInvoiceDetails(array('property_id' => $data['property_id'], 'program_id' => $data['program_id'], 'job_id' => $job, 'invoice_id' => $invoice_id));

                    //create and update

                    if ($exist_ppjobinv) {

                        //update job cost

                        $this->PropertyProgramJobInvoiceModel->updatePropertyProgramJobInvoice(array('invoice_id' => $invoice_id, 'job_id' => $job), array('job_cost' => $data['jobcost'][$job], 'updated_at' => date("Y-m-d H:i:s")));
                    } else {

                        //get property_program_id

                        $propProg = $this->PropertyModel->getOnePropertyProgram(array('property_id' => $data['property_id'], 'program_id' => $data['program_id']));

                        //create new row

                        $PPJOBINVparams = array(

                            'customer_id' => $data['customer_id'],

                            'property_program_id' => $propProg->property_program_id,

                            'property_id' => $data['property_id'],

                            'program_id' => $data['program_id'],

                            'job_id' => $job,

                            'job_cost' => $data['jobcost'][$job],

                            'invoice_id' => $invoice_id,

                            'created_at' => date("Y-m-d H:i:s"),

                            'updated_at' => date("Y-m-d H:i:s"),

                        );

                        $PPJOBINV_ID = $this->PropertyProgramJobInvoiceModel->CreateOnePropertyProgramJobInvoice($PPJOBINVparams);
                    }
                }

                //get job details

                $job_details = $this->JobModel->getOneJob(array('job_id' => $job));

                if ($job_details) {

                    $description[] = $job_details->job_name;
                }

                $jobarray[] = array(

                    'job_id' => $job,

                    'job_cost' => $data['jobcost'][$job],

                    'job_name' => $job_details->job_name,

                );
            }

            //store job ids in json array

            $json = array(

                'jobs' => $jobarray,

            );

            $description = implode(', ', $description);

            $where = array(

                'invoice_id' => $invoice_id,

            );

            $param = array(

                'customer_id' => $data['customer_id'],

                'property_id' => $data['property_id'],

                'invoice_date' => $data['invoice_date'],

                'notes' => $data['notes'],

                'cost' => $data['cost'],

                'description' => $description,

                // 'cost' => $property_details->yard_square_feet * $job_details->job_price,

                //'job_id' => $data['job_id'],

                'program_id' => $data['program_id'],

                'status' => $data['sent_status'],

                'payment_status' => $data['payment_status'],

                'invoice_updated' => date("Y-m-d H:i:s"),

                'last_modify' => date("Y-m-d H:i:s"),

                'json' => json_encode($json),

            );

            $invoice_details = $this->INV->getOneInvoive($where);
            if ($invoice_details) {

                if ($data['cardconnect_details'] && $data['payment_method'] == '2') {
                    $data['payment_method'] = '4';
                }

                // calculate current recorded payment total
                $all_invoice_partials = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $invoice_details->invoice_id));
                $total_invoice_partial_logs = 0;
                foreach ($all_invoice_partials as $invoice_partial) {
                    $total_invoice_partial_logs += $invoice_partial->payment_amount;
                }
                if ($total_invoice_partial_logs == 0 && $invoice_details->partial_payment != 0) {
                    $total_invoice_partial_logs = $invoice_details->partial_payment;
                }

                if (isset($data['new_partial_payment']) && !empty($data['new_partial_payment'])) {

                    // calculate new total
                    $new_total_partial = $total_invoice_partial_logs + $data['new_partial_payment'];
                    $over_all_due = $data['over_all_total'];

                    // save partial records
                    if ($new_total_partial >= $over_all_due) {

                        $param['partial_payment'] = $over_all_due;
                        $param['payment_status'] = 2;
                        //KT
                        $param['status'] = 2;
                        if ($invoice_details->opened_date == '') {
                            $param['opened_date'] = date("Y-m-d H:i:s");
                        } else {
                            $param['opened_date'] = $invoice_details->opened_date;
                        }
                        if ($invoice_details->sent_date == '') {
                            $param['sent_date'] = date("Y-m-d H:i:s");
                        } else {
                            $param['sent_date'] = $invoice_details->sent_date;
                        }
                        //...
                        if (isset($data['payment_method']) && $data['payment_method'] == '1') {
                            $check_number = $data['payment_info'];
                        } else if (isset($data['payment_method']) && ($data['payment_method'] == '2' || $data['payment_method'] == '4')) {
                            $cc_number = $data['payment_info'];
                        } else {
                            $payment_note = $data['payment_info'];
                        }

                        $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
                        $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
                        $time = $date->format('Y-m-d H:i:s');

                        $result = $this->PaymentLogModel->createLogRecord(array(
                            'invoice_id' => $invoice_details->invoice_id,
                            'user_id' => $this->session->userdata['id'],
                            'amount' => $over_all_due - $total_invoice_partial_logs,
                            'action' => "Payment Added",
                            'created_at' => $time,
                        ));

                        $result = $this->PartialPaymentModel->createOnePartialPayment(array(
                            'invoice_id' => $invoice_details->invoice_id,
                            'payment_amount' => $over_all_due - $total_invoice_partial_logs,
                            'payment_applied' => $over_all_due - $total_invoice_partial_logs,
                            'payment_datetime' => date("Y-m-d H:i:s"),
                            'payment_method' => $data['payment_method'],
                            'check_number' => (isset($check_number) ? $check_number : ''),
                            'cc_number' => (isset($cc_number) ? $cc_number : ''),
                            'payment_note' => (isset($payment_note) ? $payment_note : ''),
                            'customer_id' => $invoice_details->customer_id,
                        ));

                        $err_msg = "set to paid";
                    } else { // if new total below total cost of invoice

                        $param['partial_payment'] = $new_total_partial;
                        $param['payment_created'] = date("Y-m-d H:i:s");
                        $param['payment_status'] = 1;
                        //KT
                        $param['status'] = 2;
                        if ($invoice_details->opened_date == '') {
                            $param['opened_date'] = date("Y-m-d H:i:s");
                        } else {
                            $param['opened_date'] = $invoice_details->opened_date;
                        }
                        if ($invoice_details->sent_date == '') {
                            $param['sent_date'] = date("Y-m-d H:i:s");
                        } else {
                            $param['sent_date'] = $invoice_details->sent_date;
                        }
                        //...
                        if (isset($data['payment_method']) && $data['payment_method'] == '1') {
                            $check_number = $data['payment_info'];
                        } else if (isset($data['payment_method']) && ($data['payment_method'] == '2' || $data['payment_method'] == '4')) {
                            $cc_number = $data['payment_info'];
                        } else {
                            $payment_note = $data['payment_info'];
                        }

                        $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
                        $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
                        $time = $date->format('Y-m-d H:i:s');

                        $result = $this->PaymentLogModel->createLogRecord(array(
                            'invoice_id' => $invoice_details->invoice_id,
                            'user_id' => $this->session->userdata['id'],
                            'amount' => $data['new_partial_payment'],
                            'action' => "Payment Added",
                            'created_at' => $time,
                        ));

                        $result = $this->PartialPaymentModel->createOnePartialPayment(array(
                            'invoice_id' => $invoice_details->invoice_id,
                            'payment_amount' => $data['new_partial_payment'],
                            'payment_applied' => $data['new_partial_payment'],
                            'payment_datetime' => date("Y-m-d H:i:s"),
                            'payment_method' => $data['payment_method'],
                            'check_number' => (isset($check_number) ? $check_number : ''),
                            'cc_number' => (isset($cc_number) ? $cc_number : ''),
                            'payment_note' => (isset($payment_note) ? $payment_note : ''),
                            'customer_id' => $invoice_details->customer_id,
                        ));
                    }

                    // set new total partial
                } else {
                    if ($data['payment_status'] == 0) {

                        $param['partial_payment'] = 0;
                        $param['payment_status'] = 0;
                    } else if ($data['payment_status'] == 1) {

                        $param['partial_payment'] = $total_invoice_partial_logs;
                        $param['payment_status'] = 1;
                        //KT
                        $param['status'] = 2;
                        if ($invoice_details->opened_date == '') {
                            $param['opened_date'] = date("Y-m-d H:i:s");
                        } else {
                            $param['opened_date'] = $invoice_details->opened_date;
                        }
                        if ($invoice_details->sent_date == '') {
                            $param['sent_date'] = date("Y-m-d H:i:s");
                        } else {
                            $param['sent_date'] = $invoice_details->sent_date;
                        }
                        //...
                    } else if ($data['payment_status'] == 2) {

                        $param['partial_payment'] = $data['over_all_total'];
                        $param['payment_created'] = date("Y-m-d H:i:s");
                        $param['payment_status'] = 2;
                        //KT
                        $param['status'] = 2;
                        if ($invoice_details->opened_date == '') {
                            $param['opened_date'] = date("Y-m-d H:i:s");
                        } else {
                            $param['opened_date'] = $invoice_details->opened_date;
                        }
                        if ($invoice_details->sent_date == '') {
                            $updateArr['sent_date'] = date("Y-m-d H:i:s");
                        } else {
                            $updateArr['sent_date'] = $invoice_details->sent_date;
                        }
                        //...
                    } else if ($data['payment_status'] == 4) {

                        $param['payment_status'] = 4;
                    } else {
                        $param['partial_payment'] = 0;
                        $param['payment_status'] = 3;
                    }
                }
            }
            // if ($invoice_details) {
            //     if ($data['payment_status'] != $invoice_details->payment_status) { // if we changed the status

            //         if ($data['payment_status'] == 0) {

            //             $param['partial_payment'] = 0;
            //             $param['payment_status'] = 0;

            //         } else if ($data['payment_status'] == 1) {

            //             $param['partial_payment'] = $data['partial_payment_new'];
            //             $param['payment_created'] = date("Y-m-d H:i:s");
            //             $param['payment_status'] = 1;
            //             if ($data['partial_payment_new']==$data['over_all_total']) {
            //                 $param['payment_status'] = 2;
            //             }
            //             if ($data['partial_payment_new'] == 0) {
            //                 $param['payment_status'] = 0;
            //             }

            //         } else if($data['payment_status']==2) {

            //             $param['partial_payment'] = $data['over_all_total'];
            //             $param['payment_created'] = date("Y-m-d H:i:s");

            //         } else {
            //             $param['partial_payment'] = 0;
            //             $param['payment_status'] = 3;
            //         }

            //     } else { // if we didn't change the status

            //         if ($data['partial_payment_new'] != $invoice_details->partial_payment) {
            //             $param['partial_payment'] = $data['partial_payment_new'];
            //             $param['payment_status'] = 1;
            //             $param['payment_created'] = date("Y-m-d H:i:s");
            //             if ($data['partial_payment_new']==$data['over_all_total']) {
            //                 $param['payment_status'] = 2;
            //             }
            //             if ($data['partial_payment_new'] == 0) {
            //                 $param['payment_status'] = 0;
            //             }
            //         }

            //     }
            // }

            $result = $this->INV->updateInvoive($where, $param);

            $this->InvoiceSalesTax->deleteInvoiceSalesTax(array('invoice_id' => $invoice_id));

            if (isset($data['sales_tax_Tbl']) && !empty($data['sales_tax_Tbl'])) {

                foreach (json_decode($data['sales_tax_Tbl']) as $value) {

                    $param3 = array(

                        'invoice_id' => $invoice_id,

                        'tax_name' => $value->tax_name,

                        'tax_value' => $value->tax_value,

                        'tax_amount' => $value->tax_amount,

                    );

                    $this->InvoiceSalesTax->CreateOneInvoiceSalesTax($param3);
                }
            }

            $invoice_details = $this->INV->getOneInvoive($where);

            if ($invoice_details->quickbook_invoice_id != 0) {

                $res = $this->QuickBookInvUpdate($invoice_details);
            }

            $expiration_pass_global = true;

            // UPDATE COUPON_INVOICES
            $new_coupons_csv = json_decode($data['assign_coupons_csv']);
            if (!empty($new_coupons_csv)) {

                // remove deleted coupons
                $all_coupon_invoices = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));
                foreach ($all_coupon_invoices as $existing_coupon_invoice) {
                    if (!in_array($existing_coupon_invoice->coupon_id, $new_coupons_csv)) {

                        // delete coupon if pre-existing coupon_invoice is not in the new list
                        $this->CouponModel->DeleteCouponInvoice(array("coupon_invoice_id" => $existing_coupon_invoice->coupon_invoice_id));
                    }
                }

                // set new coupon_customer
                foreach ($new_coupons_csv as $coupon_id) {

                    $coupon_details = $this->CouponModel->getOneCoupon(array('coupon_id' => $coupon_id));
                    if ($coupon_details) {

                        // only add coupon_invoice if the coupon exists & it's type is non perm
                        if ($coupon_details->type == 0) {

                            $coupon_invoice_exists = $this->CouponModel->getOneCouponInvoice(array('coupon_id' => $coupon_id, 'invoice_id' => $invoice_id));

                            // add coupon_invoice if it doesn't already exist
                            if (!$coupon_invoice_exists) {
                                $param_coupon = array(
                                    'coupon_id' => $coupon_id,
                                    'invoice_id' => $invoice_id,
                                    'coupon_code' => $coupon_details->code,
                                    'coupon_amount' => $coupon_details->amount,
                                    'coupon_amount_calculation' => $coupon_details->amount_calculation,
                                    'coupon_type' => 0,
                                );
                                $this->CouponModel->CreateOneCouponInvoice($param_coupon);
                            }
                        } else {
                            // cannot add perm coupons from invoices screen
                        }
                    } else {
                        // coupon doesn't exist anymore -- can't add
                    }
                }
            }

            if ($invoice_details->quickbook_invoice_id != 0) {

                // Assign value of invoice_details object to new variable
                $QBO_param = $invoice_details;

                // Declare array to be passed to coupon calculatiuon function
                $coup_inv_param = array(
                    'cost' => $QBO_param->cost,
                    'invoice_id' => $invoice_id
                );

                // Assign value of calculation function to new variable
                $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                // Assign value of variable as new cost to pass to QBO
                $QBO_param->cost = $cost_with_inv_coupon;

                // die(print($QBO_param->cost));

                // Update QBO Invoice with any new info
                $res = $this->QuickBookInvUpdate($QBO_param);
            }

            // if(!empty($data['assign_coupons'])){

            //     // remove all coupon_invoice
            //     $result_coupon_delete = $this->CouponModel->DeleteCouponInvoice(array("invoice_id" => $invoice_id));

            //     // set new coupon_customer
            //     foreach ($data['assign_coupons'] as $coupon_id) {

            //         $coupon_details = $this->CouponModel->getOneCoupon( array('coupon_id' => $coupon_id) );

            //         // CHECK THAT EXPIRATION DATE IS IN FUTURE OR 000000
            //         $expiration_pass = true;
            //         if ($coupon_details->expiration_date != "0000-00-00 00:00:00") {
            //             $coupon_expiration_date = strtotime( $coupon_details->expiration_date );

            //             $now = time();
            //             if($coupon_expiration_date < $now) {
            //                 $expiration_pass = false;
            //                 $expiration_pass_global = false;
            //             }
            //         }

            //         $param_coupon = array(
            //             'coupon_id' => $coupon_id,
            //             'invoice_id' => $invoice_id,
            //             'coupon_code' => $coupon_details->code,
            //             'coupon_amount' => $coupon_details->amount,
            //             'coupon_amount_calculation' => $coupon_details->amount_calculation,
            //             'coupon_type' => 0
            //         );
            //         $this->CouponModel->CreateOneCouponInvoice($param_coupon);
            //     }
            // }

            // if (isset($data['coupon_id']) && $data['coupon_id'] != '' && $data['coupon_id'] != 'REMOVE-ALL') {

            //     // ASSIGN COUPON TO INVOICE
            //     $coupon_data = $this->CouponModel->getOneCoupon(array('coupon_id' => $data['coupon_id']));

            //     $where = array(
            //         'invoice_id' => $invoice_id,
            //         'coupon_id' => $data['coupon_id'],
            //         'coupon_code' => $coupon_data->code,
            //         'coupon_amount' => $coupon_data->amount,
            //         'coupon_amount_calculation' => $coupon_data->amount_calculation
            //     );

            //     $coupon_data = $this->CouponModel->getOneCoupon(array('coupon_id' => $data['coupon_id']));

            //     $where = array(
            //         'coupon_id' => $data['coupon_id'],
            //         'job_id' => $job_job_id,
            //         'coupon_code' => $coupon_data->code,
            //         'coupon_amount' => $coupon_data->amount,
            //         'coupon_amount_calculation' => $coupon_data->amount_calculation,
            //         'customer_id' => $job_customer_id,
            //         'program_id' => $job_program_id,
            //         'property_id' => $job_property_id
            //     );
            //     $result = $this->CouponModel->CreateOneCouponJob($where);

            // } else if (isset($data['coupon_id']) && $data['coupon_id'] == 'REMOVE-ALL') {

            //     // REMOVE ALL COUPONS FROM SERVICES
            //     foreach($job_data_csv as $job) {

            //         // customer_id, job_id, program_id, property_id
            //         $data_arr = array();
            //         $data_arr[] = str_getcsv($job);

            //         $job_customer_id = $data_arr[0][0];
            //         $job_job_id = $data_arr[0][1];
            //         $job_program_id = $data_arr[0][2];
            //         $job_property_id = $data_arr[0][3];

            //         $where = array(
            //             'job_id' => $job_job_id,
            //             'customer_id' => $job_customer_id,
            //             'program_id' => $job_program_id,
            //             'property_id' => $job_property_id
            //         );

            //         $result = $this->CouponModel->DeleteCouponJob($where);

            //     }

            // }

            if ($result) {

                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Invoice </strong>updated successfully</div>');

                redirect("admin/Invoices");
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert"    data-auto-dismiss="4000"><strong>Invoice </strong> not updated. Please try again</div>');

                redirect("admin/Invoices");
            }
        }
    }

    public function pdfInvoicescheduled($invoice_id)
    {
        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where_arr = array(
            'company_id' => $this->session->userdata['company_id'],
            'status' => 1,
        );

        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);

        $where = array(
            "invoice_tbl.company_id" => $this->session->userdata['company_id'],
            'invoice_tbl.invoice_id' => $invoice_id,
        );
        $data['invoice_details'] = $this->INV->getOneInvoive($where);

        // die(print_r($data['invoice_details']->job_completed));
        // cost of all services (with price overrides) - service coupons
        $job_cost_total = 0;
        $jobs = array();
        if (empty($data['invoice_details']->job_id)) {

            //get invoice details from property_program_job_invoice
            $param = array(
                'property_program_job_invoice.invoice_id' => $invoice_id,
            );
            $details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram($param);

            #check for customer billing type
            $checkGroupBilling = $this->CustomerModel->checkGroupBilling($data['invoice_details']->customer_id);
            if(isset($checkGroupBilling) && $checkGroupBilling == "true"){
                $data['invoice_details']->is_group_billing = 1;
                $data['invoice_details']->group_billing_details = $this->PropertyModel->getGroupBillingByProperty($data['invoice_details']->property_id);
            }else{
                $data['invoice_details']->is_group_billing = 0;
            }

            if ($details) {
                foreach ($details as $detail) {
                    $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $data['invoice_details']->invoice_id));

                    // Take into account services without products
                    if (!isset($get_assigned_date)){
                        $get_assigned_date = $this->Tech->getOneTechJobAssignNoProduct(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $data['invoice_details']->invoice_id));
                    }
                    ### SERVICE WIDE COUPONS
                    $serviceCoupons = array(
                        'customer_id' => $data['invoice_details']->customer_id,
                        'program_id' => $data['invoice_details']->program_id,
                        'property_id' => $data['invoice_details']->property_id,
                        'job_id' => $detail['job_id'],
                    );
                    $coupon_job = $this->CouponModel->getOneCouponJob($serviceCoupons);
                    $coupon_job_amm = 0;
                    $coupon_job_amm_calc = 5;
                    $coupon_job_code = '';
                    if (!empty($coupon_job)) {
                        $coupon_job_amm = $coupon_job->coupon_amount;
                        $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                        $coupon_job_code = $coupon_job->coupon_code;
                    }

                    if (isset($detail['report_id'])) {
                        $report = $this->RP->getOneRepots(array('report_id' => $detail['report_id']));
                        $jobs[] = array(
                            'job_id' => $detail['job_id'],
                            'job_name' => $detail['job_name'],
                            'job_description' => $detail['job_description'],
                            'job_cost' => $detail['job_cost'],
                            'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                            'job_report' => isset($report) ? $report : '',
                            'coupon_job_amm' => $coupon_job_amm,
                            'coupon_job_amm_calc' => $coupon_job_amm_calc,
                            'coupon_job_code' => $coupon_job_code,
                        );
                    } else {
                        $report = '';
                        $jobs[] = array(
                            'job_id' => $detail['job_id'],
                            'job_name' => $detail['job_name'],
                            'job_description' => $detail['job_description'],
                            'job_cost' => $detail['job_cost'],
                            'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                            'job_report' => (object) array(
                                'report_id' => '',
                                'technician_job_assign_id' => '',
                                'company_id' => '',
                                'user_first_name' => '',
                                'user_last_name' => '',
                                'applicator_number' => '',
                                'applicator_phone_number' => '',
                                'first_name' => '',
                                'last_name' => '',
                                'property_title' => '',
                                'property_city' => '',
                                'yard_square_feet' => '',
                                'property_state' => '',
                                'property_zip' => '',
                                'property_address' => '',
                                'wind_speed' => '',
                                'direction' => '',
                                'temp' => '',
                                'cost' => '',
                                'tax_name' => '',
                                'tax_value' => '',
                                'tax_amount' => '',
                                'convenience_fee' => '',
                                'job_completed_date' => '',
                                'job_completed_time' => '',
                                'report_created_date' => '',
                            ),
                            'coupon_job_amm' => $coupon_job_amm,
                            'coupon_job_amm_calc' => $coupon_job_amm_calc,
                            'coupon_job_code' => $coupon_job_code,
                        );
                    }
                }
                $data['invoice_details']->jobs = $jobs;
                //print_r($details);
                // echo '<pre>';
                // print_r($data['invoice_details']);
                // die();
            }

            if (!$details && $data['invoice_details']->json) {
                $json = json_decode($data['invoice_details']->json, true);
                //die(print_r($json['jobs']));
                if (isset($json['manual_invoice']) && $json['manual_invoice'] == 1) {
                    $data['invoice_details']->manual_invoice = 1;
                }

                if (is_array($json['jobs'])) {
                    foreach ($json['jobs'] as $job) {
                        $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $job['job_id'], 'invoice_id' => $data['invoice_details']->invoice_id));

                        // Take into account services without products
                        if (!isset($get_assigned_date)){
                            $get_assigned_date = $this->Tech->getOneTechJobAssignNoProduct(array('technician_job_assign.job_id' => $job['job_id'], 'invoice_id' => $data['invoice_details']->invoice_id));
                        }
                        //print_r($job);
                        //get job details
                        $job_details = $this->JobModel->getOneJob(array('job_id' => $job['job_id']));
                        $j_report = new stdClass();
                        if (!empty($job_details)) {

                            ### SERVICE WIDE COUPONS
                            $serviceCoupons = array(
                                'customer_id' => $data['invoice_details']->customer_id,
                                'program_id' => $data['invoice_details']->program_id,
                                'property_id' => $data['invoice_details']->property_id,
                                'job_id' => $job_details->job_id,
                            );
                            $coupon_job = $this->CouponModel->getOneCouponJob($serviceCoupons);
                            $coupon_job_amm = 0;
                            $coupon_job_amm_calc = 5;
                            $coupon_job_code = '';
                            if (!empty($coupon_job)) {
                                $coupon_job_amm = $coupon_job->coupon_amount;
                                $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                                $coupon_job_code = $coupon_job->coupon_code;
                            }

                            $jobs[] = array(
                                'job_id' => $job['job_id'],
                                'job_name' => $job_details->job_name,
                                'job_description' => $job_details->job_description,
                                'job_cost' => $job['job_cost'],
                                'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                                'job_report' => (object) array(
                                    'report_id' => '',
                                    'technician_job_assign_id' => '',
                                    'company_id' => '',
                                    'user_first_name' => '',
                                    'user_last_name' => '',
                                    'applicator_number' => '',
                                    'applicator_phone_number' => '',
                                    'first_name' => '',
                                    'last_name' => '',
                                    'property_title' => '',
                                    'property_city' => '',
                                    'yard_square_feet' => '',
                                    'property_state' => '',
                                    'property_zip' => '',
                                    'property_address' => '',
                                    'wind_speed' => '',
                                    'direction' => '',
                                    'temp' => '',
                                    'cost' => '',
                                    'tax_name' => '',
                                    'tax_value' => '',
                                    'tax_amount' => '',
                                    'convenience_fee' => '',
                                    'job_completed_date' => '',
                                    'job_completed_time' => '',
                                    'report_created_date' => '',
                                ),
                                'coupon_job_amm' => $coupon_job_amm,
                                'coupon_job_amm_calc' => $coupon_job_amm_calc,
                                'coupon_job_code' => $coupon_job_code,
                            );
                        } else {
                            $jobs[] = array(
                                'job_id' => $job['job_id'],
                                'job_name' => '',
                                'job_description' => '',
                                'job_cost' => $job['job_cost'],
                                'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                                'job_report' => (object) array(
                                    'report_id' => '',
                                    'technician_job_assign_id' => '',
                                    'company_id' => '',
                                    'user_first_name' => '',
                                    'user_last_name' => '',
                                    'applicator_number' => '',
                                    'applicator_phone_number' => '',
                                    'first_name' => '',
                                    'last_name' => '',
                                    'property_title' => '',
                                    'property_city' => '',
                                    'yard_square_feet' => '',
                                    'property_state' => '',
                                    'property_zip' => '',
                                    'property_address' => '',
                                    'wind_speed' => '',
                                    'direction' => '',
                                    'temp' => '',
                                    'cost' => '',
                                    'tax_name' => '',
                                    'tax_value' => '',
                                    'tax_amount' => '',
                                    'convenience_fee' => '',
                                    'job_completed_date' => '',
                                    'job_completed_time' => '',
                                    'report_created_date' => '',
                                ),
                                'coupon_job_amm' => $coupon_job_amm,
                                'coupon_job_amm_calc' => $coupon_job_amm_calc,
                                'coupon_job_code' => $coupon_job_code,
                            );
                        }
                    }

                    $data['invoice_details']->jobs = $jobs;
                }
            }
        } else {
            $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $data['invoice_details']->job_id, 'invoice_id' => $data['invoice_details']->invoice_id));

            $job_details = $this->JobModel->getOneJob(array('job_id' => $data['invoice_details']->job_id));

            ### SERVICE WIDE COUPONS
            $serviceCoupons = array(
                'customer_id' => $data['invoice_details']->customer_id,
                'program_id' => $data['invoice_details']->program_id,
                'property_id' => $data['invoice_details']->property_id,
                'job_id' => $data['invoice_details']->job_id,
            );
            $coupon_job = $this->CouponModel->getAllCouponJob($serviceCoupons);
            $coupon_job_amm = 0;
            $coupon_job_amm_calc = 5;
            $coupon_job_code = '';
            if (!empty($coupon_job)) {
                $coupon_job_amm = $coupon_job->coupon_amount;
                $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                $coupon_job_code = $coupon_job->coupon_code;
            }
            $data['invoice_details']->coupon_job = $coupon_job;

            if (!empty($job_details)) {
                $jobs = array(
                    'job_id' => $job_details['job_id'],
                    'job_name' => $job_details->job_name,
                    'job_description' => $job_details->job_description,
                    'job_cost' => $job_details['job_cost'],
                    'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                    'coupon_job_amm' => $coupon_job_amm,
                    'coupon_job_amm_calc' => $coupon_job_amm_calc,
                    'coupon_job_code' => $coupon_job_code,
                );
            }

            $data['invoice_details']->jobs = $jobs;
        }
        //die(print_r($data));
        ////////////////////////////////////
        // START INVOICE CALCULATION COST //
        // vars
        $tmp_invoice_id = $data['invoice_details']->invoice_id;

        // cost of all services (with price overrides) - service coupons
        $job_cost_total = 0;
        $wherePPJI = array(
            'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
        );

        $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($wherePPJI);

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
                            $coupon_job_amm_total = (float) $coupon_job_amm;
                        } else { // percentage
                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                        }

                        $job_cost = $job_cost - $coupon_job_amm_total;

                        if ($job_cost < 0) {
                            $job_cost = 0;
                        }
                    }
                }

                $job_cost_total += $job_cost;
            }
            $invoice_total_cost = $job_cost_total;
        } else {
            $invoice_total_cost = $data['invoice_details']->cost;
        }

        // - invoice coupons
        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
        foreach ($coupon_invoice_details as $coupon_invoice) {
            if (!empty($coupon_invoice)) {
                $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                if ($coupon_invoice_amm_calc == 0) { // flat amm
                    $invoice_total_cost -= (float) $coupon_invoice_amm;
                } else { // percentage
                    $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                    $invoice_total_cost -= $coupon_invoice_amm;
                }
                if ($invoice_total_cost < 0) {
                    $invoice_total_cost = 0;
                }
            }
        }
        $data['invoice_details']->coupon_details = $coupon_invoice_details;
        // + tax cost
        $invoice_total_tax = 0;
        $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
        if (!empty($invoice_sales_tax_details)) {
            foreach ($invoice_sales_tax_details as $tax) {
                if (array_key_exists("tax_value", $tax)) {
                    $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                    $invoice_total_tax += $tax_amm_to_add;
                }
            }
        }
        //die(print_r($invoice_total_tax));
        $invoice_total_cost += $invoice_total_tax;
        $total_tax_amount = $invoice_total_tax;
        $total_invoice_cost_calc = $invoice_total_cost - $data['invoice_details']->partial_payment;
        $data['invoice_details']->total_invoice_cost_calc = $total_invoice_cost_calc;

        // END TOTAL INVOICE CALCULATION COST //
        ////////////////////////////////////////

        //figure cost if invoice_details->cost empty
        if (empty($data['invoice_details']->cost)) {
            $where = array(
                'property_id' => $data['invoice_details']->property_id,
                'job_id' => $data['invoice_details']->job_id,
                'program_id' => $data['invoice_details']->program_id,
                'customer_id' => $data['invoice_details']->customer_id,
            );

            $estimate_price_override = GetOneEstimateJobPriceOverride($where);
            if ($estimate_price_override && $estimate_price_override->price_override != 0) {
                $data['invoice_details']->cost = $estimate_price_override->price_override;
            } else {
                $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $data['invoice_details']->property_id, 'program_id' => $data['invoice_details']->program_id));
                if ($priceOverrideData && $priceOverrideData->price_override != 0) {
                    $data['invoice_details']->cost = $priceOverrideData->price_override;
                } else {
                    $price = $data['invoice_details']->job_price;
                    $data['invoice_details']->cost = ($data['invoice_details']->yard_square_feet * $price) / 1000;
                }
            }
        }
        //figure tax
        $data['invoice_details']->all_sales_tax = false;
        if ($data['setting_details']->is_sales_tax == 1) {
            $all_sales_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $data['invoice_details']->property_id));
            if ($all_sales_tax) {
                foreach ($all_sales_tax as $key3 => $all_sales_tax_details) {
                    $all_sales_tax[$key3]['tax_amount'] = $data['invoice_details']->cost * $all_sales_tax_details['tax_value'] / 100;
                }
            }
            $data['invoice_details']->all_sales_tax = $all_sales_tax;
        }

        $whereArrPaidEstimate = array(
            'property_id' => $data['invoice_details']->property_id,
            'program_id' => $data['invoice_details']->program_id,
            'customer_id' => $data['invoice_details']->customer_id,
            'status' => 3,
        );
        $estimate_paid = GetOneEstimateDetails($whereArrPaidEstimate);
        if ($estimate_paid) {
            $data['invoice_details']->payment_status = 2;
        } else {
            if (!isset($data['invoice_details']->payment_status)) {
                $data['invoice_details']->payment_status = 0;
            }
        }

        $data['invoice_details']->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $data['invoice_details']->invoice_id));

        if (!empty($data['invoice_details']->report_id)) {
            $data['report_details'] = $this->RP->getOneRepots(array('report_id' => $data['invoice_details']->report_id));
        }

        $address = explode(',', $data['invoice_details']->billing_street);

        if (is_array($address)) {
            foreach ($address as $k => $v) {
                $address[$k] = trim($v);
            }
            $findCity = array_search($data['invoice_details']->billing_city, $address);

            if ($findCity) {
                unset($address[$findCity]);
            }

            $findState = array_search($data['invoice_details']->billing_state, $address);

            if ($findState) {
                unset($address[$findState]);
            }

            $findZip = array_search($data['invoice_details']->billing_zipcode, $address);
            if ($findZip) {
                unset($address[$findZip]);
            }
            $findUSA = array_search('USA', $address);

            if ($findUSA) {
                unset($address[$findUSA]);
            }

            $data['invoice_details']->billing_street = implode(',', $address);
        }

        //die(print_r($data['invoice_details']));

        //$data['all_sales_tax'] =  $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id'=>$invoice_id));

        //$invoice_details = $this->INV->getOneRow(array("invoice_id" => $invoice_id));

        //$data['coupon_customer'] = array();

        // SERVICE WIDE COUPONS
        //$arry = array(
        //    'customer_id' => $invoice_details->customer_id,
        //    'program_id' => $invoice_details->program_id,
        //    'property_id' => $invoice_details->property_id
        //);
        //$data['coupon_job'] = $this->CouponModel->getAllCouponJob($arry);

        // INVOICE WIDE COUPONS
        //$data['coupon_invoice'] = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));

        $this->load->view('admin/invoice/pdf_invoice_scheduled', $data);
        $html = $this->output->get_output();
        // Load pdf library
        $this->load->library('pdf');
        // Load HTML content
        $this->dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');
        // Render the HTML as PDF
        $this->dompdf->render();
        $companyName = str_replace(" ", "", $this->session->userdata['compny_details']->company_name);
        $customerName = $data['invoice_details']->first_name . $data['invoice_details']->last_name;
        $fileName = $companyName . "_invoice_" . $customerName . "_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
    }

    public function pdfInvoice($invoice_id)
    {

        $where = array(
            "invoice_tbl.company_id" => $this->session->userdata['company_id'],
            'invoice_tbl.invoice_id' => $invoice_id,
        );
        $data['invoice_details'] = $this->INV->getOneInvoive($where);

        $jobs = array();


        //get invoice details from property_program_job_invoice
        $param = array(
            'property_program_job_invoice.invoice_id' => $invoice_id,
        );
        $details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram($param);
        if ($details) {

            if ($data['invoice_details']->report_id == 0) {
                $data['report_details'] = $this->RP->getOneRepots(array('invoice_id' => $data['invoice_details']->invoice_id));
            }
            if (!isset($data['invoice_details']->report_details))
            {
                $data['report_details'] = $this->RP->getOneRepots(array('invoice_id' => $data['invoice_details']->invoice_id));

            }

           // echo  $this->db->last_query();
            //die(print_r($data));
            foreach ($details as $detail) {
                $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $data['invoice_details']->invoice_id));

                if (isset($detail['report_id'])) {
                    $report = $data['report_details'];
                    $jobs[] = array(
                        'job_id' => $detail['job_id'],
                        'job_name' => $detail['job_name'],
                        'job_description' => $detail['job_description'],
                        'job_cost' => $detail['job_cost'],
                        'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                        'job_report' => isset($report) ? $report : '',
                    );
                } else {
                    $jobs[] = array(
                        'job_id' => $detail['job_id'],
                        'job_name' => $detail['job_name'],
                        'job_description' => $detail['job_description'],
                        'job_cost' => $detail['job_cost'],
                        'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                        'job_report' => (object) array(
                            'report_id' => '',
                            'technician_job_assign_id' => '',
                            'company_id' => '',
                            'user_first_name' => '',
                            'user_last_name' => '',
                            'applicator_number' => '',
                            'applicator_phone_number' => '',
                            'first_name' => '',
                            'last_name' => '',
                            'property_title' => '',
                            'property_city' => '',
                            'yard_square_feet' => '',
                            'property_state' => '',
                            'property_zip' => '',
                            'property_address' => '',
                            'wind_speed' => '',
                            'direction' => '',
                            'temp' => '',
                            'cost' => '',
                            'tax_name' => '',
                            'tax_value' => '',
                            'tax_amount' => '',
                            'convenience_fee' => '',
                            'job_completed_date' => '',
                            'job_completed_time' => '',
                            'report_created_date' => '',
                        ),
                    );
                }
            }
            $data['invoice_details']->jobs = $jobs;
            // echo '<pre>';
            // print_r($data['invoice_details']);
            // die();
        }
        if (!$details && $data['invoice_details']->json) {
                $json = json_decode($data['invoice_details']->json, true);
               // die(print_r($json['jobs']));
                if (isset($json['manual_invoice']) && $json['manual_invoice'] == 1) {
                    $data['invoice_details']->manual_invoice = 1;
                }

                if (is_array($json['jobs'])) {
                    foreach ($json['jobs'] as $job) {
                        $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $job['job_id'], 'invoice_id' => $data['invoice_details']->invoice_id));
                       // die(print_r($get_assigned_date));
                        //get job details
                        $job_details = $this->JobModel->getOneJob(array('job_id' => $job['job_id']));
                        $j_report = new stdClass();
                        if (!empty($job_details)) {
                            $jobs[] = array(
                                'job_id' => $job['job_id'],
                                'job_name' => $job_details->job_name,
                                'job_description' => $job_details->job_description,
                                'job_cost' => $job['job_cost'],
                                'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                                'job_report' => (object) array(
                                    'report_id' => '',
                                    'technician_job_assign_id' => '',
                                    'company_id' => '',
                                    'user_first_name' => '',
                                    'user_last_name' => '',
                                    'applicator_number' => '',
                                    'applicator_phone_number' => '',
                                    'first_name' => '',
                                    'last_name' => '',
                                    'property_title' => '',
                                    'property_city' => '',
                                    'yard_square_feet' => '',
                                    'property_state' => '',
                                    'property_zip' => '',
                                    'property_address' => '',
                                    'wind_speed' => '',
                                    'direction' => '',
                                    'temp' => '',
                                    'cost' => '',
                                    'tax_name' => '',
                                    'tax_value' => '',
                                    'tax_amount' => '',
                                    'convenience_fee' => '',
                                    'job_completed_date' => '',
                                    'job_completed_time' => '',
                                    'report_created_date' => '',
                                ),
                            );
                        } else {
                            $jobs[] = array(
                                'job_id' => $job['job_id'],
                                'job_name' => '',
                                'job_description' => '',
                                'job_cost' => $job['job_cost'],
                                'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                                'job_report' => (object) array(
                                    'report_id' => '',
                                    'technician_job_assign_id' => '',
                                    'company_id' => '',
                                    'user_first_name' => '',
                                    'user_last_name' => '',
                                    'applicator_number' => '',
                                    'applicator_phone_number' => '',
                                    'first_name' => '',
                                    'last_name' => '',
                                    'property_title' => '',
                                    'property_city' => '',
                                    'yard_square_feet' => '',
                                    'property_state' => '',
                                    'property_zip' => '',
                                    'property_address' => '',
                                    'wind_speed' => '',
                                    'direction' => '',
                                    'temp' => '',
                                    'cost' => '',
                                    'tax_name' => '',
                                    'tax_value' => '',
                                    'tax_amount' => '',
                                    'convenience_fee' => '',
                                    'job_completed_date' => '',
                                    'job_completed_time' => '',
                                    'report_created_date' => '',
                                ),
                            );
                        }
                    }

                    $data['invoice_details']->jobs = $jobs;
                }
            }
        if (!empty($data['invoice_details']->job_id)) {
            // SERVICE WIDE COUPONS
            $arry = array(
                'customer_id' => $data['invoice_details']->customer_id,
                'program_id' => $data['invoice_details']->program_id,
                'property_id' => $data['invoice_details']->property_id,
                'job_id' => $data['invoice_details']->job_id,
            );
            $data['invoice_details']->coupon_job = $this->CouponModel->getAllCouponJob($arry);
        }



        //die($data['invoice_details']->report_id);
        $address = explode(',', $data['invoice_details']->billing_street);

        if (is_array($address)) {
            foreach ($address as $k => $v) {
                $address[$k] = trim($v);
            }

            $findCity = array_search($data['invoice_details']->billing_city, $address);

            if ($findCity) {
                unset($address[$findCity]);
            }

            $findState = array_search($data['invoice_details']->billing_state, $address);

            if ($findState) {
                unset($address[$findState]);
            }

            $findZip = array_search($data['invoice_details']->billing_zipcode, $address);
            if ($findZip) {
                unset($address[$findZip]);
            }
            $findUSA = array_search('USA', $address);

            if ($findUSA) {
                unset($address[$findUSA]);
            }

            $data['invoice_details']->billing_street = implode(',', $address);
        }

        //die(print_r($data['invoice_details']));

        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where_arr = array(
            'company_id' => $this->session->userdata['company_id'],
            'status' => 1,
        );

        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);
        $data['all_sales_tax'] = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoice_id));
        //die(print_r($data['all_sales_tax']));
        //$invoice_details = $this->INV->getOneRow(array("invoice_id" => $invoice_id));

        $data['coupon_customer'] = array();

        // SERVICE WIDE COUPONS
        $arry = array(
            'customer_id' => $data['invoice_details']->customer_id,
            'program_id' => $data['invoice_details']->program_id,
            'property_id' => $data['invoice_details']->property_id,
        );
        $data['coupon_job'] = $this->CouponModel->getAllCouponJob($arry);

        // INVOICE WIDE COUPONS
        $data['coupon_invoice'] = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_id));

        $data['invoice_late_fee'] = $this->INV->getLateFee($invoice_id);

        $this->load->view('admin/invoice/pdf_invoice', $data);
        $html = $this->output->get_output();
        //die(print_r($html));
        // Load pdf library
        $this->load->library('pdf');

        // Load HTML content
        $this->dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');
        // Render the HTML as PDF

        //exit();
        $this->dompdf->render();
        $companyName = str_replace(" ", "", $this->session->userdata['compny_details']->company_name);
        $customerName = $data['invoice_details']->first_name . $data['invoice_details']->last_name;
        $fileName = $companyName . "_invoice_" . $customerName . "_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
        // Output the generated PDF (1 = download and 0 = preview)
        

        $this->dompdf->stream($fileName . ".pdf", array("Attachment" => false));
    }

    public function pendingJobInvoicescheduled($technician_job_assign_ids){
        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $where_arr = array(
            'company_id' => $this->session->userdata['company_id'],
            'status' => 1,
        );
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);
        $data['invoice_details'] = array();
        $technician_job_assign_ids = explode(",", $technician_job_assign_ids);
        // die(print_r($technician_job_assign_ids));

        foreach ($technician_job_assign_ids as $key => $technician_job_assign_id) {
            $total_invoice_cost_calc = 0;
            $assigned_job_details = $this->Tech->getOneTechJobAssign(array('technician_job_assign_id' => $technician_job_assign_id));

            if (!isset($assigned_job_details)){
                $assigned_job_details = $this->Tech->getOneTechJobAssignNoProduct(array('technician_job_assign_id' => $technician_job_assign_id));
            }
            // die(print_r($assigned_job_details));
            //check for invoice id
            if ($assigned_job_details->invoice_id) {
                $invoice_where = array(
                    'invoice_tbl.invoice_id' => $assigned_job_details->invoice_id,
                );
            } else {
                $invoice_where = array(
                    'invoice_tbl.property_id' => $assigned_job_details->property_id,
                    'invoice_tbl.job_id' => $assigned_job_details->job_id,
                    'program_id' => $assigned_job_details->program_id,
                    'invoice_tbl.customer_id' => $assigned_job_details->customer_id,
                    'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                );
            }

            if ($this->INV->getOneInvoive($invoice_where)) {
                $invoice_details = $this->INV->getOneInvoive($invoice_where);
            } else {
                $invoice_details = $assigned_job_details;
                $invoice_details->invoice_id = '';
            }
            #check for customer billing type
            $checkGroupBilling = $this->CustomerModel->checkGroupBilling($assigned_job_details->customer_id);
            if(isset($checkGroupBilling) && $checkGroupBilling == "true"){
                $invoice_details->is_group_billing = 1;
                $invoice_details->group_billing_details = $this->PropertyModel->getGroupBillingByProperty($assigned_job_details->property_id);
            }else{
                $invoice_details->is_group_billing = 0;
            }
            //get job data
            $jobs = array();
            // die(print_r($invoice_details));
            if ($invoice_details->invoice_id) {

                // $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $invoice_details->invoice_id, 'jobs.job_id' => $job_identification->job_id));
                $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $invoice_details->invoice_id));
                // die(print_r($job_details));
                if ($job_details) {
                    foreach ($job_details as $detail) {

                        $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $invoice_details->invoice_id));
                        // die(print_r($get_assigned_date));

                        // Take into account services without products
                        if (!isset($get_assigned_date)){
                            $get_assigned_date = $this->Tech->getOneTechJobAssignNoProduct(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $invoice_details->invoice_id));
                        }
                        if (isset($detail['report_id'])) {
                            $report = $this->RP->getOneRepots(array('report_id' => $detail['report_id']));
                        } else {
                            $report = '';
                        }

                        // SERVICE WIDE COUPONS
                        $arry = array(
                            'customer_id' => $invoice_details->customer_id,
                            'program_id' => $invoice_details->program_id,
                            'property_id' => $invoice_details->property_id,
                            'job_id' => $detail['job_id'],
                        );

                        $coupon_job = $this->CouponModel->getOneCouponJob($arry);
                        $coupon_job_amm = 0;
                        $coupon_job_amm_calc = 5;
                        $coupon_job_code = '';
                        if (!empty($coupon_job)) {
                            $coupon_job_amm = $coupon_job->coupon_amount;
                            $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                            $coupon_job_code = $coupon_job->coupon_code;
                        }

                        $jobs[] = array(
                            'job_id' => $detail['job_id'],
                            'job_name' => $detail['job_name'],
                            'job_description' => $detail['job_description'],
                            'job_cost' => $detail['job_cost'],
                            'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                            'program_name' => isset($detail['program_name']) ? $detail['program_name'] : '',
                            'job_report' => isset($report) ? $report : '',
                            'coupon_job_amm' => $coupon_job_amm,
                            'coupon_job_amm_calc' => $coupon_job_amm_calc,
                            'coupon_job_code' => $coupon_job_code,
                        );
                    }
                }
                $job_selected = [];
                foreach($technician_job_assign_ids as $iz){
                    $job_integration = $this->Tech->getAllJobAssignWhere( array('technician_job_assign_id' => $iz, 'property_tbl.property_id' => $assigned_job_details->property_id));
                    foreach($jobs as $projob){
                        foreach($job_integration as $selectedJob){
                            // die(print_r($selectedJob));
                            if($selectedJob['job_id'] == $projob['job_id'] && !in_array($projob, $job_selected)){
                                array_push($job_selected, $projob );
                            }
                        }
                    }
                }

                // die(print_r($this->db->last_query()));
                // die(print_r($technician_job_assign_ids));
                // die(print_r($job_integration));
                // die(print_r($proprojobinv));

                // die(print_r($jobs));


                // die(print_r($job_selected));
                $invoice_details->jobs = $job_selected;
                // die(print_r($invoice_details->jobs));

                ////////////////////////////////////
                // START INVOICE CALCULATION COST //

                // vars
                $tmp_invoice_id = $invoice_details->invoice_id;

                // cost of all services (with price overrides) - service coupons
                $job_cost_total = 0;
                $where = array(
                    'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
                );
                $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);


                // if (!empty($job_selected)) {
                //     foreach ($job_selected as $job) {
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
                                    $coupon_job_amm_total = (float) $coupon_job_amm;
                                } else { // percentage
                                    $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                                }

                                $job_cost = $job_cost - $coupon_job_amm_total;

                                if ($job_cost < 0) {
                                    $job_cost = 0;
                                }
                            }
                        }

                        $job_cost_total += $job_cost;
                    }
                    $invoice_total_cost = $job_cost_total;
                } else {
                    $invoice_total_cost = $invoice_details->cost;
                }

                // check price override -- any that are not stored in just that ^^.

                // - invoice coupons
                $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
                foreach ($coupon_invoice_details as $coupon_invoice) {
                    if (!empty($coupon_invoice)) {
                        $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                        $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                        if ($coupon_invoice_amm_calc == 0) { // flat amm
                            $invoice_total_cost -= (float) $coupon_invoice_amm;
                        } else { // percentage
                            $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                            $invoice_total_cost -= $coupon_invoice_amm;
                        }
                        if ($invoice_total_cost < 0) {
                            $invoice_total_cost = 0;
                        }
                    }
                }

                // + tax cost
                $invoice_total_tax = 0;
                $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
                if (!empty($invoice_sales_tax_details)) {
                    foreach ($invoice_sales_tax_details as $tax) {
                        if (array_key_exists("tax_value", $tax)) {
                            $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                            $invoice_total_tax += $tax_amm_to_add;
                        }
                    }
                }
                //die(print_r($invoice_total_tax));
                $invoice_total_cost += $invoice_total_tax;
                $total_tax_amount = $invoice_total_tax;
                $total_invoice_cost_calc = $invoice_total_cost - $invoice_details->partial_payment;
                $invoice_details->total_invoice_cost_calc = $total_invoice_cost_calc;

                // END TOTAL INVOICE CALCULATION COST //
                ////////////////////////////////////////

            }

            if (empty($invoice_details->cost)) {
                //figure cost
                $where = array(
                    'property_id' => $invoice_details->property_id,
                    'job_id' => $invoice_details->job_id,
                    'program_id' => $invoice_details->program_id,
                    'customer_id' => $invoice_details->customer_id,
                );

                $estimate_price_override = GetOneEstimateJobPriceOverride($where);
                if ($estimate_price_override && $estimate_price_override->price_override != 0) {
                    $invoice_details->cost = $estimate_price_override->price_override;
                } else {
                    $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $invoice_details->property_id, 'program_id' => $invoice_details->program_id));
                    if ($priceOverrideData && $priceOverrideData->price_override != 0) {
                        $invoice_details->cost = $priceOverrideData->price_override;
                    } else {
                        $price = $invoice_details->job_price;
                        $invoice_details->cost = ($invoice_details->yard_square_feet * $price) / 1000;
                    }
                }
            }
            //figure tax
            $invoice_details->all_sales_tax = false;
            if ($data['setting_details']->is_sales_tax == 1) {
                $all_sales_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $invoice_details->property_id));
                if ($all_sales_tax) {
                    foreach ($all_sales_tax as $key3 => $all_sales_tax_details) {
                        $all_sales_tax[$key3]['tax_amount'] = $invoice_details->cost * $all_sales_tax_details['tax_value'] / 100;
                    }
                }
                $invoice_details->all_sales_tax = $all_sales_tax;
            }
            $invoice_details->report_details = false;
            $invoice_details->invoice_date = $assigned_job_details->job_assign_date;
            $invoice_details->notes = '';
            $invoice_details->report_id = 0;

            $whereArrPaidEstimate = array(
                'property_id' => $invoice_details->property_id,
                'program_id' => $invoice_details->program_id,
                'customer_id' => $invoice_details->customer_id,
                'status' => 3,
            );
            $estimate_paid = GetOneEstimateDetails($whereArrPaidEstimate);
            if ($estimate_paid) {
                $invoice_details->payment_status = 2;
            } else {
                if (!isset($invoice_details->payment_status)) {
                    $invoice_details->payment_status = 0;
                }
            }

            $invoice_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_details->invoice_id));
            $data['invoice_details'][] = $invoice_details;
        }
        // die(print_r($data['invoice_details']));
        // die(print_r($assigned_job_details));

        //remove duplicate invoices
        $checkDupp = array();
        foreach ($data['invoice_details'] as $key => $invoice) {
            if (!empty($invoice->invoice_id) && !in_array($invoice->invoice_id, $checkDupp)) {
                $checkDupp[$key] = $invoice->invoice_id;
            } elseif (isset($invoice->invoice_id) && in_array($invoice->invoice_id, $checkDupp)) {
                unset($data['invoice_details'][$key]);
            }
        }
        if (count($data['invoice_details']) > 0) {
            $this->load->view('admin/invoice/multiple_pdf_invoice_print_scheduled', $data);
            $html = $this->output->get_output();
            // Load pdf library
            $this->load->library('pdf');
            // Load HTML content
            $this->dompdf->loadHtml($html);
            // (Optional) Setup the paper size and orientation
            $this->dompdf->setPaper('A4', 'portrate');
            ini_set('max_execution_time', '1800');
            // Render the HTML as PDF
            $this->dompdf->render();
            // Output the generated PDF (1 = download and 0 = preview)
            $companyName = str_replace(" ", "", $this->session->userdata['compny_details']->company_name);
            $fileName = $companyName . "_invoices_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
            $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
            exit;
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Invoice for service not available</div>');
            redirect("admin/manageJobs");
        }
    }

    public function pendingJobInvoice($technician_job_assign_ids)
    {
        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $where_arr = array(
            'company_id' => $this->session->userdata['company_id'],
            'status' => 1,
        );
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);
        $data['invoice_details'] = array();
        $technician_job_assign_ids = explode(",", $technician_job_assign_ids);
        foreach ($technician_job_assign_ids as $key => $technician_job_assign_id) {
            $assigned_job_details = $this->Tech->getOneTechJobAssign(array('technician_job_assign_id' => $technician_job_assign_id));
            //check for invoice id
            if ($assigned_job_details->invoice_id) {
                $invoice_where = array(
                    'invoice_tbl.invoice_id' => $assigned_job_details->invoice_id,
                );
            } else {
                $invoice_where = array(
                    'invoice_tbl.property_id' => $assigned_job_details->property_id,
                    'invoice_tbl.job_id' => $assigned_job_details->job_id,
                    'program_id' => $assigned_job_details->program_id,
                    'invoice_tbl.customer_id' => $assigned_job_details->customer_id,
                    'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                );
            }

            if ($this->INV->getOneInvoive($invoice_where)) {
                $invoice_details = $this->INV->getOneInvoive($invoice_where);
            } else {
                $invoice_details = $assigned_job_details;
                $invoice_details->invoice_id = '';
            }
            //get job data
            $jobs = array();
            if ($invoice_details->invoice_id) {
                $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $invoice_details->invoice_id));
                if ($job_details) {
                    foreach ($job_details as $detail) {

                        $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $invoice_details->invoice_id));

                        // Take into account services without products
                        if (!isset($get_assigned_date)){
                            $get_assigned_date = $this->Tech->getOneTechJobAssignNoProduct(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $invoice_details->invoice_id));
                        }
                        // die(print_r($get_assigned_date));
                        if (isset($detail['report_id'])) {
                            $report = $this->RP->getOneRepots(array('report_id' => $detail['report_id']));
                        } else {
                            $report = '';
                        }

                        // SERVICE WIDE COUPONS
                        $arry = array(
                            'customer_id' => $invoice_details->customer_id,
                            'program_id' => $invoice_details->program_id,
                            'property_id' => $invoice_details->property_id,
                            'job_id' => $detail['job_id'],
                        );

                        $coupon_job = $this->CouponModel->getOneCouponJob($arry);
                        $coupon_job_amm = 0;
                        $coupon_job_amm_calc = 5;
                        $coupon_job_code = '';
                        if (!empty($coupon_job)) {
                            $coupon_job_amm = $coupon_job->coupon_amount;
                            $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                            $coupon_job_code = $coupon_job->coupon_code;
                        }

                        $jobs[] = array(
                            'job_id' => $detail['job_id'],
                            'job_name' => $detail['job_name'],
                            'job_description' => $detail['job_description'],
                            'job_cost' => $detail['job_cost'],
                            'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                            'program_name' => isset($detail['program_name']) ? $detail['program_name'] : '',
                            'job_report' => isset($report) ? $report : '',
                            'coupon_job_amm' => $coupon_job_amm,
                            'coupon_job_amm_calc' => $coupon_job_amm_calc,
                            'coupon_job_code' => $coupon_job_code,
                        );
                    }
                }
                $invoice_details->jobs = $jobs;
            }

            if (empty($invoice_details->cost)) {
                //figure cost
                $where = array(
                    'property_id' => $invoice_details->property_id,
                    'job_id' => $invoice_details->job_id,
                    'program_id' => $invoice_details->program_id,
                    'customer_id' => $invoice_details->customer_id,
                );

                $estimate_price_override = GetOneEstimateJobPriceOverride($where);
                if ($estimate_price_override && $estimate_price_override->price_override != 0) {
                    $invoice_details->cost = $estimate_price_override->price_override;
                } else {
                    $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $invoice_details->property_id, 'program_id' => $invoice_details->program_id));
                    if ($priceOverrideData && $priceOverrideData->price_override != 0) {
                        $invoice_details->cost = $priceOverrideData->price_override;
                    } else {
                        $price = $invoice_details->job_price;
                        $invoice_details->cost = ($invoice_details->yard_square_feet * $price) / 1000;
                    }
                }
            }
            //figure tax
            $invoice_details->all_sales_tax = false;
            if ($data['setting_details']->is_sales_tax == 1) {
                $all_sales_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $invoice_details->property_id));
                if ($all_sales_tax) {
                    foreach ($all_sales_tax as $key3 => $all_sales_tax_details) {
                        $all_sales_tax[$key3]['tax_amount'] = $invoice_details->cost * $all_sales_tax_details['tax_value'] / 100;
                    }
                }
                $invoice_details->all_sales_tax = $all_sales_tax;
            }
            $invoice_details->report_details = false;
            $invoice_details->invoice_date = $assigned_job_details->job_assign_date;
            $invoice_details->notes = '';
            $invoice_details->report_id = 0;

            $whereArrPaidEstimate = array(
                'property_id' => $invoice_details->property_id,
                'program_id' => $invoice_details->program_id,
                'customer_id' => $invoice_details->customer_id,
                'status' => 3,
            );
            $estimate_paid = GetOneEstimateDetails($whereArrPaidEstimate);
            if ($estimate_paid) {
                $invoice_details->payment_status = 2;
            } else {
                if (!isset($invoice_details->payment_status)) {
                    $invoice_details->payment_status = 0;
                }
            }

            $invoice_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_details->invoice_id));
            $data['invoice_details'][] = $invoice_details;
        }

        //remove duplicate invoices
        $checkDupp = array();
        foreach ($data['invoice_details'] as $key => $invoice) {
            if (!empty($invoice->invoice_id) && !in_array($invoice->invoice_id, $checkDupp)) {
                $checkDupp[$key] = $invoice->invoice_id;
            } elseif (isset($invoice->invoice_id) && in_array($invoice->invoice_id, $checkDupp)) {
                unset($data['invoice_details'][$key]);
            }
        }
        //die(print_r($data));
        if (count($data['invoice_details']) > 0) {
            $this->load->view('admin/invoice/multiple_pdf_invoice_print', $data);
            $html = $this->output->get_output();
            // Load pdf library
            $this->load->library('pdf');
            // Load HTML content
            $this->dompdf->loadHtml($html);
            // (Optional) Setup the paper size and orientation
            $this->dompdf->setPaper('A4', 'portrate');
            ini_set('max_execution_time', '1800');
            // Render the HTML as PDF
            $this->dompdf->render();
            // Output the generated PDF (1 = download and 0 = preview)
            $companyName = str_replace(" ", "", $this->session->userdata['compny_details']->company_name);
            $fileName = $companyName . "_invoices_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
            $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
            exit;
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Invoice for service not available</div>');
            redirect("admin/manageJobs");
        }
    }

    public function printInvoice($invoice_ids)
    {
        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $where_arr = array(
            'company_id' => $this->session->userdata['company_id'],
            'status' => 1,
        );
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);
        $invoice_ids = explode(",", $invoice_ids);
        foreach ($invoice_ids as $key => $value) {
            $invoiceID = (int) $value;
            $where = array(
                'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                'invoice_tbl.invoice_id' => $invoiceID,
            );
            $invoice_details = $this->INV->getOneInvoive($where);

            //die(print_r($invoice_details));
            // echo '<br><br>';
            // echo $this->db->last_query();
            
            $invoice_details->all_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $invoiceID));

            $invoice_details->report= $this->RP->getOneRepots(array('report_id' => $invoice_details->report_id));

            //get job details
            $jobs = array();
            $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $invoiceID));
            if ($job_details) {
                foreach ($job_details as $detail) {

                    $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $invoiceID));

                    // SERVICE WIDE COUPONS
                    $arry = array(
                        'customer_id' => $invoice_details->customer_id,
                        'program_id' => $invoice_details->program_id,
                        'property_id' => $invoice_details->property_id,
                        'job_id' => $detail['job_id'],
                    );

                    $coupon_job = $this->CouponModel->getOneCouponJob($arry);
                    $coupon_job_amm = 0;
                    $coupon_job_amm_calc = 5;
                    $coupon_job_code = '';
                    if (!empty($coupon_job)) {
                        $coupon_job_amm = $coupon_job->coupon_amount;
                        $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                        $coupon_job_code = $coupon_job->coupon_code;
                    }

                    if (isset($detail['report_id'])) {
                        $report = $this->RP->getOneRepots(array('report_id' => $detail['report_id']));
                        $jobs[] = array(
                            'job_id' => $detail['job_id'],
                            'job_name' => $detail['job_name'],
                            'job_description' => $detail['job_description'],
                            'job_cost' => $detail['job_cost'],
                            'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                            'program_name' => isset($detail['program_name']) ? $detail['program_name'] : '',
                            'job_report' => isset($report) ? $report : "",
                            'coupon_job_amm' => $coupon_job_amm,
                            'coupon_job_amm_calc' => $coupon_job_amm_calc,
                            'coupon_job_code' => $coupon_job_code,
                        );
                    } else {
                        $jobs[] = array(
                            'job_id' => $detail['job_id'],
                            'job_name' => $detail['job_name'],
                            'job_description' => $detail['job_description'],
                            'job_cost' => $detail['job_cost'],
                            'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                            'program_name' => isset($detail['program_name']) ? $detail['program_name'] : '',
                            'job_report' => (object) array(
                                'report_id' => '',
                                'technician_job_assign_id' => '',
                                'company_id' => '',
                                'user_first_name' => '',
                                'user_last_name' => '',
                                'applicator_number' => '',
                                'applicator_phone_number' => '',
                                'first_name' => '',
                                'last_name' => '',
                                'property_title' => '',
                                'property_city' => '',
                                'yard_square_feet' => '',
                                'property_state' => '',
                                'property_zip' => '',
                                'property_address' => '',
                                'wind_speed' => '',
                                'direction' => '',
                                'temp' => '',
                                'cost' => '',
                                'tax_name' => '',
                                'tax_value' => '',
                                'tax_amount' => '',
                                'convenience_fee' => '',
                                'job_completed_date' => '',
                                'job_completed_time' => '',
                                'report_created_date' => '',
                            ),
                            'coupon_job_amm' => $coupon_job_amm,
                            'coupon_job_amm_calc' => $coupon_job_amm_calc,
                            'coupon_job_code' => $coupon_job_code,
                        );
                    }
                }
            }
            $invoice_details->jobs = $jobs;
            //late fee
            $late_fee = $this->INV->getLateFee($invoiceID);
            $invoice_details->late_fee = $late_fee;

            $invoice_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoiceID));
            $invoice_details->logs = $this->PaymentLogModel->getAllPaymentLogs(array('invoice_id' => $invoiceID));

            $invoice_details->invoice_partials_payments = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $invoiceID));

            $data['invoice_details'][] = $invoice_details;
            //die(print_r($data["invoice_details"]));
            // INVOICE WIDE COUPONS
            // $data['coupon_invoice'][] = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoiceID));
        }

        

        $this->load->view('admin/invoice/multiple_pdf_invoice_print', $data);
        $html = $this->output->get_output();

        // die(print_r($html));
        // Load pdf library
        $this->load->library('pdf');
        // Load HTML content
        $this->dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');
        ini_set('max_execution_time', '1800');
        // Render the HTML as PDF
        $this->dompdf->render();
        // Output the generated PDF (1 = download and 0 = preview)
        $companyName = str_replace(" ", "", $this->session->userdata['compny_details']->company_name);
        $fileName = $companyName . "_invoices_bulk_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
        $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
        exit;
    }

    public function getOpenInvoiceByCustomer($customer_id)
    {

        $post_data = $this->input->post();

        // die(print_r($post_data));

        // WHERE:
        $whereArr = array(
            'is_archived' => 0,
            'invoice_tbl.customer_id' => $customer_id,
            'invoice_tbl.status !=' => 0
            // 'payment_status !=' => 2
        );
        if (isset($post_data['start_date']) && !empty($post_data['start_date'])) {
            $whereArr['invoice_tbl.sent_date >='] = $post_data['start_date'];
        }
        if (isset($post_data['end_date']) && !empty($post_data['end_date'])) {

            $end_of_day = new DateTime($post_data['end_date'] . '+1 day');
            $end_of_day = $end_of_day->format('Y-m-d');
           
                
                // die(print_r($end_of_day));
                $whereArr['invoice_tbl.sent_date <='] = $end_of_day;

            
        }

        // WHERE NOT: all of the below true
        $whereArrExclude = array(
            "programs.program_price" => 2,
            // "technician_job_assign.is_complete" => 0,
            "technician_job_assign.is_complete !=" => 1,
            "technician_job_assign.is_complete IS NOT NULL" => null
        );

        // WHERE NOT: all of the below true
        $whereArrExclude2 = array(
            "programs.program_price" => 2,
            "technician_job_assign.invoice_id IS NULL" => null,
            "invoice_tbl.report_id" => 0,
            "property_program_job_invoice2.report_id IS NULL" => null,
        );

        $invoice_total_cost = 0;
        $previous_total = 0;
        $start_date = 0;
        $end_date = 0;

        if (isset($post_data['start_date']) && !empty($post_data['start_date'])) {
            $start_date = $post_data['start_date'];
        }
        if (isset($post_data['end_date']) && !empty($post_data['end_date'])) {
            $end_date = $post_data['end_date'];
        }

        if (isset($post_data['start_date']) && !empty($post_data['start_date'])) {

            $whereArrBefore = array(
                'is_archived' => 0,
                'invoice_tbl.customer_id' => $customer_id,
                // 'payment_status !=' => 2,
                'invoice_tbl.sent_date <' => $post_data['start_date'],
            );

            $data_before_period = $this->INV->ajaxActiveInvoicesTechWithSalesTax($whereArrBefore, "", "", "", "", $whereArrExclude, $whereArrExclude2, "", false);

            // die(print_r($data_before_period));

            if (!empty($data_before_period)) {
                foreach ($data_before_period as $invoice_details) {

                    ////////////////////////////////////
                    // START INVOICE CALCULATION COST //

                    // vars
                    $tmp_invoice_id = $invoice_details->invoice_id;

                    // cost of all services (with price overrides) - service coupons
                    $job_cost_total = 0;
                    $where = array(
                        'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
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
                                        $coupon_job_amm_total = (float) $coupon_job_amm;
                                    } else { // percentage
                                        $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                                    }

                                    $job_cost = $job_cost - $coupon_job_amm_total;

                                    if ($job_cost < 0) {
                                        $job_cost = 0;
                                    }
                                }
                            }

                            $job_cost_total += $job_cost;
                        }
                        $invoice_total_cost = $job_cost_total;
                        //die(print_r("Inside Conditional: " . $invoice_total_cost));
                    } else {
                        $invoice_total_cost = $invoice_details->cost;

                    }

                    // check price override -- any that are not stored in just that ^^.

                    // - invoice coupons
                    $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
                    foreach ($coupon_invoice_details as $coupon_invoice) {
                        if (!empty($coupon_invoice)) {
                            $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                            $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                            if ($coupon_invoice_amm_calc == 0) { // flat amm
                                $invoice_total_cost -= (float) $coupon_invoice_amm;
                            } else { // percentage
                                $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                                $invoice_total_cost -= $coupon_invoice_amm;
                            }
                            if ($invoice_total_cost < 0) {
                                $invoice_total_cost = 0;
                            }
                        }
                    }

                    // + tax cost
                    $invoice_total_tax = 0;
                    $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
                    if (!empty($invoice_sales_tax_details)) {
                        foreach ($invoice_sales_tax_details as $tax) {
                            if (array_key_exists("tax_value", $tax)) {
                                $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                                $invoice_total_tax += $tax_amm_to_add;
                            }
                        }
                    }
                    $invoice_total_cost += $invoice_total_tax;
                    $total_tax_amount = $invoice_total_tax;

                    // END TOTAL INVOICE CALCULATION COST //
                    ////////////////////////////////////////

                    // $total = $invoice_details->cost - $invoice_details->partial_payment + $invoice_details->tax_amm;
                    $total = $invoice_total_cost - $invoice_details->partial_payment;

                    // $total_log = fopen("total_logs.txt", "a+");                    
                    $total = number_format($total, 2, '.', '');
                    $previous_total += $total;
                    //die(print_r($previous_total));
                }
            }
        }
        // print_r($previous_total);
        // echo "<br>";
        // print_r(number_format($previous_total, 2));
        // die();
        $data['past_invoice_total'] = $previous_total;
        $data['statement_start_date'] = $start_date;
        $data['statement_end_date'] = $end_date;
        $data['invoice_details'] = $this->INV->ajaxActiveInvoicesTechWithSalesTax($whereArr, "", "", "", "", $whereArrExclude, $whereArrExclude2, "", false);

        $credit_arr = array(
            'customer_id' => $customer_id,
            'is_credit_balance' => 1
        );

        $data['credit_details'] = $this->INV->getAllCreditAmountsApplied($credit_arr);

        $data['customer_details'] = $this->CustomerModel->getCustomerDetail($customer_id);
        // die(print_r($data['customer_details']));
        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where = array('user_id' => $this->session->userdata['user_id']);
        $data['user_details'] = $this->Administrator->getOneAdmin($where);

        // die(print_r($data['invoice_details']));

        $count = 0;
        foreach ($data['invoice_details'] as $index => $inv_deets) {



            $property_details = $this->PropertyModel->getOneProperty(array('property_id'=>$inv_deets->property_id));

            $data['invoice_details'][$index]->property_address = $property_details->property_address;
            $data['invoice_details'][$index]->property_city = $property_details->property_city;
            $data['invoice_details'][$index]->property_state = $property_details->property_state;
            $data['invoice_details'][$index]->property_zip = $property_details->property_zip;
            $data['invoice_details'][$index]->late_fee = $this->INV->getLateFee($inv_deets->invoice_id);
            $data['invoice_details'][$index]->partial_payment = $inv_deets->partial_payment;
            // $tax_arr = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $inv_deets->invoice_id));
            // if (!empty($tax_arr)) {
            //     foreach ($tax_arr as $tax) {
            //         if (array_key_exists("tax_value", $tax)) {
            //             $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $inv_deets->partial_payment;
            //             $data['invoice_details'][$index]->partial_payment += $tax_amm_to_add;
            //         }
            //     }
            // }


            ##### WHERE FOR GETTING ALL PARTIALS AND REFUNDS PAYMENTS FOR INVOICE ID #####
            $where = array(
                'customer_id' => $customer_id,
                'invoice_id' => $inv_deets->invoice_id,
            );

            ##### GETTING ALL PARTIALS FOR INVOICE ID #####
            $inv_deets->partial_payments_logs = $this->PartialPaymentModel->getAllPartialPayment($where);
            ####
            ##### GETTING ALL REFUNDS FOR INVOICE ID #####

            $inv_deets->refund_payments_logs = $this->RefundPaymentModel->getAllPartialRefund($where);
            ####
            //die(print_r($inv_deets));
            // die(print_r($this->db->last_query()));
            //die(print_r($partial_payments));

            ////////////////////////////////////
            // START INVOICE CALCULATION COST //

            // vars
            $tmp_invoice_id = $inv_deets->invoice_id;

            // cost of all services (with price overrides) - service coupons
            $job_cost_total = 0;
            $where = array(
                'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
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
                                $coupon_job_amm_total = (float) $coupon_job_amm;
                            } else { // percentage
                                $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                            }

                            $job_cost = $job_cost - $coupon_job_amm_total;

                            if ($job_cost < 0) {
                                $job_cost = 0;
                            }
                        }
                    }

                    $job_cost_total += $job_cost;
                }
                $invoice_total_cost = (float) $job_cost_total;
            } else {
                $invoice_total_cost = (float) $inv_deets->cost;
            }

            // check price override -- any that are not stored in just that ^^.

            // - invoice coupons
            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
            foreach ($coupon_invoice_details as $coupon_invoice) {
                if (!empty($coupon_invoice)) {
                    $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                    $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                    if ($coupon_invoice_amm_calc == 0) { // flat amm
                        $invoice_total_cost -= (float) $coupon_invoice_amm;
                    } else { // percentage
                        $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                        $invoice_total_cost -= $coupon_invoice_amm;
                    }
                    if ($invoice_total_cost < 0) {
                        $invoice_total_cost = 0;
                    }
                }
            }

            // + tax cost
            $invoice_total_tax = 0;
            $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
            if (!empty($invoice_sales_tax_details)) {
                foreach ($invoice_sales_tax_details as $tax) {
                    if (array_key_exists("tax_value", $tax)) {
                        $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                        $invoice_total_tax += $tax_amm_to_add;
                    }
                }
            }
            $invoice_total_cost += $invoice_total_tax;
            $total_tax_amount = $invoice_total_tax;

            // END TOTAL INVOICE CALCULATION COST //
            ////////////////////////////////////////

            // $data['invoice_details'][$count]->coupon_invoice = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $inv_deets->invoice_id ));
            $data['invoice_details'][$count]->final_cost = $invoice_total_cost;
            $count += 1;
        }

        $this->load->view('admin/invoice/customer_all_pdf_invoice', $data);

        $html = $this->output->get_output();

        //  // Load pdf library
        $this->load->library('pdf');

        //  // Load HTML content
        $this->dompdf->loadHtml($html);

        //  // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrate');

        //  // Render the HTML as PDF
        $this->dompdf->render();

        if(@$post_data['email']){
            $body = ' ';
            $data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
            // die(print_r($data['customer_details']));
            $where_company = array('company_id' =>  $this->session->userdata['company_id']);
            $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
            if (!$company_email_details) {
                $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
            }
            $file = [
                'file' =>base64_encode($this->dompdf->output()),
                'file_name' => 'Statement.pdf',
                'encoding' => 'base64',
                'type' => 'application/pdf'
            ];
            $res = Send_Mail_dynamic(
                $company_email_details,
                $post_data['email'],
                array(
                    "name" => $this->session->userdata['compny_details']->company_name,
                    "email" => $this->session->userdata['compny_details']->company_email
                ),
                $body,
                'Customer Statement',
                $data['customer_details']->secondary_email,
                $file
            );

            if ($data['customer_details']->secondary_email !== ''){
                $secondary_email_list = explode(',',$data['customer_details']->secondary_email);
                foreach($secondary_email_list as $sel){
                    Send_Mail_dynamic(
                        $company_email_details,
                        $sel,
                        array(
                            "name" => $this->session->userdata['compny_details']->company_name,
                            "email" => $this->session->userdata['compny_details']->company_email
                        ),
                        $body,
                        'Customer Statement',
                        '',
                        $file
                    );
                }
            }
            if($res){
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Success! </strong> Statement sent successfully</div>');
                redirect('admin/editCustomer/' . $customer_id);
            }
        }

        //  // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment" => 0));
    }

    public function bulkChangeStatus($value = '')
    {

        $data = $this->input->post();
        // print_r($data);

        if (!empty($data['invoice_ids'])) {

            foreach ($data['invoice_ids'] as $key => $value) {

                $where = array(
                    'invoice_id' => $value,
                );

                $param = array(
                    'status' => $data['status'],
                    'last_modify' => date("Y-m-d H:i:s"),
                );

                $invoice_details = $this->INV->getOneInvoive($where);
                //  If invoice is already paid or partial payment and change status then to skip.

                if ($data['status'] == 2) {
                    $param['opened_date'] = date("Y-m-d H:i:s");
                    $credit_balance_check = 1;
                } elseif ($data['status'] == 1) {
                    $param['sent_date'] = date("Y-m-d H:i:s");
                    if (empty($invoice_details->first_sent_date)) {
                        $param['first_sent_date'] = date("Y-m-d H:i:s");
                    }
                    $credit_balance_check = 1;
                }

                $result = $this->INV->updateInvoive($where, $param);

                $invoice_details = $this->INV->getOneInvoive($where);

                if($credit_balance_check){
                    // ** PROCESS CREDIT BALANCE ON THIS INVOICE
                    //Check if invoiceCreditBalance available and process this invoice
                    //get unpaid invoices for customer
                            $customer_id = $invoice_details->customer_id;
                            $unpaid = $this->INV->getUnpaidInvoiceById($invoice_details->invoice_id);
                            // die(print_r($unpaid));
                            $customer_info = $this->CustomerModel->getCustomerDetail($customer_id);
                            $credit_amount = $customer_info['credit_amount'];
                            $invoice_id = $data['invoice_id'];
            
                            if(!empty($unpaid)){

                                  $invoice_amount  = $unpaid->unpaid_amount;
                                //   die(print_r($invoice_amount));
                                  if($credit_amount >= $invoice_amount){
                                    // die(print_r($credit_amount));
                                    $result = $this->INV->createOnePartialPayment(array(
                                                  'invoice_id' => $unpaid->unpaid_invoice,
                                                  'payment_amount' => $invoice_amount,
                                                  'payment_applied' => $invoice_amount,
                                                  'payment_datetime' => date("Y-m-d H:i:s"),
                                                  'payment_method' => 5,
                                                  'check_number' => null,
                                                  'cc_number' => null,
                                                  'payment_note' => "Payment made from credit amount {$credit_amount}",
                                                  'customer_id' => $customer_id,
                                              ));
            
                                    // die(print_r($result));
                                    
            
                                    //mark this invoice as paid
                                    $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 2, 'payment_status' => 2, 'partial_payment' => $invoice_amount, 'payment_created' => date('Y-m-d H:i:s')]);
                                
                                    $credit_amount -= $invoice_amount;
                                
                                } else if($credit_amount > 0 && $invoice_amount > 0){
                                    $result = $this->INV->createOnePartialPayment(array(
                                        'invoice_id' => $unpaid->unpaid_invoice,
                                        'payment_amount' => $credit_amount,
                                        'payment_applied' => $credit_amount,
                                        'payment_datetime' => date("Y-m-d H:i:s"),
                                        'payment_method' => 5,
                                        'check_number' => null,
                                        'cc_number' => null,
                                        'payment_note' => "Payment made from credit amount {$credit_amount}",
                                        'customer_id' => $customer_id,
                                    ));
            
                                    // die(print_r($result));
                                    
            
                                    //mark this invoice as paid
                                    $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 1, 'payment_status' => 1, 'partial_payment' => $credit_amount, 'payment_created' => date('Y-m-d H:i:s')]);  
                                
                                    $credit_amount = 0;
                                }
            
                              //update customers.credit_amount adjusted credit_amount balance
                              $this->INV->adjustCreditPayment($customer_id, $credit_amount);
                            }
                    }

                if ($invoice_details->quickbook_invoice_id != 0) {

                         // Assign value of invoice_details object to new variable
                $QBO_param = $invoice_details;
                
                // Declare array to be passed to coupon calculatiuon function
                $coup_inv_param = array(
                    'cost' => $QBO_param->cost,
                    'invoice_id' => $value
                );

                // Assign value of calculation function to new variable
                $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                // Assign value of variable as new cost to pass to QBO
                $QBO_param->cost = $cost_with_inv_coupon;

                // die(print($QBO_param->cost));

                // Update QBO Invoice with any new info

                    $res = $this->QuickBookInvUpdate($invoice_details);
                    //var_dump($res);
                }
            }

            echo 1;
        } else {
            echo 0;
        }
    }

    public function changeStatus()
    {

        $data = $this->input->post();

        $where = array(
            'invoice_id' => $data['invoice_id'],
        );

        $invoice = $this->INV->getOneInvoive($where);

        // die(print_r($invoice));

        $param = array(
            'status' => $data['status'],
            'last_modify' => date("Y-m-d H:i:s"),
        );

        if ($data['status'] == 2) {
            $param['opened_date'] = date("Y-m-d H:i:s");
            $credit_balance_check = 1;
        } elseif ($data['status'] == 1) {
            $param['sent_date'] = date("Y-m-d H:i:s");
            if (empty($invoice->first_sent_date)) {
                $param['first_sent_date'] = date("Y-m-d H:i:s");
            }
            $credit_balance_check = 1;
        }
        
        $result = $this->INV->updateInvoive($where, $param);

        $invoice_details = $this->INV->getOneInvoive($where);

        // die(print_r($invoice_details));

        if($credit_balance_check){
        // ** PROCESS CREDIT BALANCE ON THIS INVOICE
        //Check if invoiceCreditBalance available and process this invoice
        //get unpaid invoices for customer
                $customer_id = $invoice_details->customer_id;
                $unpaid = $this->INV->getUnpaidInvoiceById($invoice_details->invoice_id);
                // die(print_r($unpaid));
                $customer_info = $this->CustomerModel->getCustomerDetail($customer_id);
                $credit_amount = $customer_info['credit_amount'];
                $invoice_id = $data['invoice_id'];

                if(!empty($unpaid)){
                      $invoice_amount  = $unpaid->unpaid_amount;
                    //   die(print_r($invoice_amount));
                      if($credit_amount >= $invoice_amount){
                        // die(print_r($credit_amount));
                        $result = $this->INV->createOnePartialPayment(array(
                                      'invoice_id' => $unpaid->unpaid_invoice,
                                      'payment_amount' => $invoice_amount,
                                      'payment_applied' => $invoice_amount,
                                      'payment_datetime' => date("Y-m-d H:i:s"),
                                      'payment_method' => 5,
                                      'check_number' => null,
                                      'cc_number' => null,
                                      'payment_note' => "Payment made from credit amount {$credit_amount}",
                                      'customer_id' => $customer_id,
                                  ));

                        // die(print_r($result));
                        

                        //mark this invoice as paid
                        $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 2, 'payment_status' => 2, 'partial_payment' => $invoice_amount, 'payment_created' => date('Y-m-d H:i:s')]);
                    
                        $credit_amount -= $invoice_amount;

                    } else if($credit_amount > 0 && $invoice_amount > 0){
                        $result = $this->INV->createOnePartialPayment(array(
                            'invoice_id' => $unpaid->unpaid_invoice,
                            'payment_amount' => $credit_amount,
                            'payment_applied' => $credit_amount,
                            'payment_datetime' => date("Y-m-d H:i:s"),
                            'payment_method' => 5,
                            'check_number' => null,
                            'cc_number' => null,
                            'payment_note' => "Payment made from credit amount {$credit_amount}",
                            'customer_id' => $customer_id,
                        ));

                        // die(print_r($result));
                        

                        //mark this invoice as paid
                        $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 1, 'payment_status' => 1, 'partial_payment' => $credit_amount, 'payment_created' => date('Y-m-d H:i:s')]);  
                    
                        $credit_amount = 0;
                    }

                  //update customers.credit_amount adjusted credit_amount balance
                  $this->INV->adjustCreditPayment($customer_id, $credit_amount);
                  //update partial payment
                  //Disable this as it's add credit again to payment_invoice_logs table
                  /*$result = $this->INV->createOnePartialPayment(array(
                      'invoice_id' => $invoice_id,
                      'payment_amount' => $credit_amount,
                      'payment_applied' => $credit_amount,
                      'payment_datetime' => date("Y-m-d H:i:s"),
                      'payment_method' => 1,
                      'check_number' => null,
                      'cc_number' => null,
                      'payment_note' => "Adding Credit to customer's account",
                      'customer_id' => $customer_id,
                  ));*/
                }
        }
        // ** END PROCESS CREDIT BALANCE ON THIS INVOICE


        if ($invoice_details->quickbook_invoice_id != 0) {
            // Assign value of invoice_details object to new variable
            $QBO_param = $invoice_details;
                
            // Declare array to be passed to coupon calculatiuon function
            $coup_inv_param = array(
                'cost' => $QBO_param->cost,
                'invoice_id' => $data['invoice_id']
            );

            // Assign value of calculation function to new variable
            $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

            // Assign value of variable as new cost to pass to QBO
            $QBO_param->cost = $cost_with_inv_coupon;

            // die(print($QBO_param->cost));

            // Update QBO Invoice with any new info
            $res = $this->QuickBookInvUpdate($invoice_details);

            //var_dump($res);
        }

        if ($result) {
            echo "true";
        } else {
            echo "false";
        }
    }

    public function changePaymentStatus()
    {


        $data = $this->input->post();
        // die(print_r($data));

        $company_id = $this->session->userdata['company_id'];
        $where_arr = array(
            'company_id' => $company_id,
            'status' => 1,
        );

        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        if ($data['cardconnect_details'] && $data['payment_method'] == '2') {
            $data['payment_method'] = 4;
        }

        if (isset($data['total_due'])) {
            $due_balance = (float) str_replace(' ', '', $data['total_due']);
        } else {
            $due_balance = 0;
        }
        // die(print_r($data['payment_status']));

        $where = array(
            'invoice_id' => $data['invoice_id'],
        );

        $param = array(
            'payment_status' => $data['payment_status'],
            'last_modify' => date("Y-m-d H:i:s"),
        );

        $invoice_details = $this->INV->getOneInvoive($where);
        $total_tax_amount = getAllSalesTaxSumByInvoice($data['invoice_id'])->total_tax_amount;

        //    die(print_r($invoice_details));

        ////////////////////////////////////
        // START INVOICE CALCULATION COST //

        // vars
        $tmp_invoice_id = $data['invoice_id'];

        // invoice cost
        // $invoice_total_cost = $invoice->cost;

        // cost of all services (with price overrides) - service coupons
        $job_cost_total = 0;
        $where_alt = array(
            'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
        );
        $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where_alt);

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
                            $coupon_job_amm_total = (float) $coupon_job_amm;
                        } else { // percentage
                            $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                        }

                        $job_cost = $job_cost - $coupon_job_amm_total;

                        if ($job_cost < 0) {
                            $job_cost = 0;
                        }
                    }
                }

                $job_cost_total += $job_cost;
            }
            $invoice_total_cost = $job_cost_total;
        } else {
            $invoice_total_cost = $invoice_details->cost;
        }

        // check price override -- any that are not stored in just that ^^.

        // - invoice coupons
        $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
        foreach ($coupon_invoice_details as $coupon_invoice) {
            if (!empty($coupon_invoice)) {
                $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                if ($coupon_invoice_amm_calc == 0) { // flat amm
                    $invoice_total_cost -= (float) $coupon_invoice_amm;
                } else { // percentage
                    $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                    $invoice_total_cost -= $coupon_invoice_amm;
                }
                if ($invoice_total_cost < 0) {
                    $invoice_total_cost = 0;
                }
            }
        }

        // + tax cost
        $invoice_total_tax = 0;
        $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
        if (!empty($invoice_sales_tax_details)) {
            foreach ($invoice_sales_tax_details as $tax) {
                if (array_key_exists("tax_value", $tax)) {
                    $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                    $invoice_total_tax += $tax_amm_to_add;
                }
            }
        }
        $invoice_total_cost += $invoice_total_tax;
        $total_tax_amount = $invoice_total_tax;

        // END TOTAL INVOICE CALCULATION COST //
        ////////////////////////////////////////

        $over_all_due = $invoice_details->cost + $total_tax_amount;
        $over_all_due = $invoice_total_cost;

        // die(print_r($data));
        if ($data['payment_status'] == 1) {

            //KT
            $param['status'] = 2;
            if ($invoice_details->opened_date == '') {
                $param['opened_date'] = date("Y-m-d H:i:s");
            } else {
                $param['opened_date'] = $invoice_details->opened_date;
            }
            if ($invoice_details->sent_date == '') {
                $param['sent_date'] = date("Y-m-d H:i:s");
            } else {
                $param['sent_date'] = $invoice_details->sent_date;
            }
            //...

            $new_total_partial = $invoice_details->partial_payment + $data['partial_payment'];

            $total_cost_all_partial_payment_logs = 0;
            $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                'invoice_id' => $tmp_invoice_id,
            ));
            foreach ($all_partial_payments as $partial_payment) {
                $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
            }

            // $total_cost_all_partial_payment_logs += $data['partial_payment'];
            $new_total_partial = $total_cost_all_partial_payment_logs + $data['partial_payment'];

            // if greater or equal, set partial to total and set to paid status
            if ($new_total_partial >= $over_all_due) {

                $param['partial_payment'] = $over_all_due;
                $param['payment_status'] = 2;
                //KT
                $param['status'] = 2;
                if ($invoice_details->opened_date == '') {
                    $param['opened_date'] = date("Y-m-d H:i:s");
                } else {
                    $param['opened_date'] = $invoice_details->opened_date;
                }
                if ($invoice_details->sent_date == '') {
                    $param['sent_date'] = date("Y-m-d H:i:s");
                } else {
                    $param['sent_date'] = $invoice_details->sent_date;
                }
                //...
                $param['payment_created'] = date("Y-m-d H:i:s");

                if ($data['payment_method'] == 0) {
                    $check_number = $data['payment_info'];
                    $data['check_number'] = $check_number;
                } else if ($data['payment_method'] == 3 ) {
                    $cc_number = $data['payment_info'];
                    $data['cc_number'] = $cc_number;
                } else if ($data['payment_method'] == 1 || $data['payment_method'] == 4) {
                    $other = $data['payment_info'];
                    $data['other'] = $other;
                }
                $param['payment_method'] = $data['payment_method'];

                $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
                $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
                $time = $date->format('Y-m-d H:i:s');

                $result = $this->PaymentLogModel->createLogRecord(array(
                    'invoice_id' => $tmp_invoice_id,
                    'user_id' => $this->session->userdata['id'],
                    "amount" => $over_all_due - $total_cost_all_partial_payment_logs,
                    'action' => "Payment Added",
                    'created_at' => $time,
                ));


                $result = $this->PartialPaymentModel->createOnePartialPayment(array(
                    'invoice_id' => $tmp_invoice_id,
                    'payment_amount' => $over_all_due - $total_cost_all_partial_payment_logs,
                    'payment_applied' => $over_all_due - $total_cost_all_partial_payment_logs,
                    'payment_datetime' => date("Y-m-d H:i:s"),
                    'payment_method' => $data['payment_method'],
                    'check_number' => (isset($check_number) ? $check_number : ''),
                    'cc_number' => (isset($cc_number) ? $cc_number : ''),
                    'payment_note' => (isset($other) ? $other : ''),
                    'customer_id' => $invoice_details->customer_id,
                ));

                $err_msg = "set to paid";
            } else {

                // $param['partial_payment'] = $new_total_partial;
                $param['partial_payment'] = $new_total_partial;
                $param['payment_created'] = date("Y-m-d H:i:s");

                if ($total_cost_all_partial_payment_logs > $over_all_due) {
                    $param['partial_payment'] = $over_all_due;
                    $param['payment_status'] = 2;
                    //KT
                    $param['status'] = 2;
                    if ($invoice_details->opened_date == '') {
                        $param['opened_date'] = date("Y-m-d H:i:s");
                    } else {
                        $param['opened_date'] = $invoice_details->opened_date;
                    }
                    if ($invoice_details->sent_date == '') {
                        $param['sent_date'] = date("Y-m-d H:i:s");
                    } else {
                        $param['sent_date'] = $invoice_details->sent_date;
                    }
                    //...
                    $err_msg = "set to paid";
                } else {
                    if ($data['payment_method'] == 1) {
                        $check_number = $data['payment_info'];
                    } else if ($data['payment_method'] == 2 || $data['payment_method'] == 4) {
                        $cc_number = $data['payment_info'];
                    } else if ($data['payment_method'] == 3) {
                        $other = $data['payment_info'];
                    }

                    $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
                    $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
                    $time = $date->format('Y-m-d H:i:s');

                    $result = $this->PaymentLogModel->createLogRecord(array(
                        'invoice_id' => $tmp_invoice_id,
                        'user_id' => $this->session->userdata['id'],
                        "amount" => $data['partial_payment'],
                        'action' => "Payment Added",
                        'created_at' => $time,
                    ));

                    $result = $this->PartialPaymentModel->createOnePartialPayment(array(
                        'invoice_id' => $tmp_invoice_id,
                        'payment_amount' => $data['partial_payment'],
                        'payment_applied' => $data['partial_payment'],
                        'payment_datetime' => date("Y-m-d H:i:s"),
                        'payment_method' => $data['payment_method'],
                        'check_number' => (isset($check_number) ? $check_number : ''),
                        'cc_number' => (isset($cc_number) ? $cc_number : ''),
                        'payment_note' => (isset($other) ? $other : ''),
                        'customer_id' => $invoice_details->customer_id,
                    ));
                }
            }

            $result = $this->INV->updateInvoive($where, $param);
            $invoice_details = $this->INV->getOneInvoive($where);
            if ($invoice_details->quickbook_invoice_id != 0) {
                 // Assign value of invoice_details object to new variable
            $QBO_param = $invoice_details;
                
            // Declare array to be passed to coupon calculatiuon function
            $coup_inv_param = array(
                'cost' => $QBO_param->cost,
                'invoice_id' => $data['invoice_id']
            );

            // Assign value of calculation function to new variable
            $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

            // Assign value of variable as new cost to pass to QBO
            $QBO_param->cost = $cost_with_inv_coupon;

            // die(print($QBO_param->cost));

            // Update QBO Invoice with any new info
                $res = $this->QuickBookInvUpdate($invoice_details);
            }

            if ($result) {
                if (isset($err_msg)) {
                    echo $err_msg;
                    return;
                }
                echo "true";
            } else {
                echo "false";
            }
            return;
        } else if ($data['payment_status'] == 2 && $due_balance > 0) {

            $new_total_partial = $invoice_details->partial_payment + $due_balance;

            $total_cost_all_partial_payment_logs = 0;
            $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                'invoice_id' => $tmp_invoice_id,
            ));
            foreach ($all_partial_payments as $partial_payment) {
                $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
            }

            // $total_cost_all_partial_payment_logs += $data['partial_payment'];
            $new_total_partial = $total_cost_all_partial_payment_logs + $due_balance;
            // die(print_r(floatval($over_all_due)));
            $param['partial_payment'] = $new_total_partial;
            $param['payment_status'] = 2;
            //KT
            $param['status'] = 2;
            if ($invoice_details->opened_date == '') {
                $param['opened_date'] = date("Y-m-d H:i:s");
            } else {
                $param['opened_date'] = $invoice_details->opened_date;
            }
            if ($invoice_details->sent_date == '') {
                $param['sent_date'] = date("Y-m-d H:i:s");
            } else {
                $param['sent_date'] = $invoice_details->sent_date;
            }
            //...
            $param['payment_created'] = date("Y-m-d H:i:s");

            if ($data['payment_method'] == 1) {
                $check_number = $data['payment_info'];
                $param['check_number'] = $check_number;
            } else if ($data['payment_method'] == 2 || $data['payment_method'] == 4) {
                $cc_number = $data['payment_info'];
                $param['cc_number'] = $cc_number;
            } else if ($data['payment_method'] == 0 || $data['payment_method'] == 3) {
                $other = $data['payment_info'];
                $param['other_note'] = $other;
            }
            $param['payment_method'] = $data['payment_method'];

            $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
            $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
            $time = $date->format('Y-m-d H:i:s');

            $result = $this->PaymentLogModel->createLogRecord(array(
                'invoice_id' => $tmp_invoice_id,
                'user_id' => $this->session->userdata['id'],
                "amount" => $due_balance,
                'action' => "Payment Added",
                'created_at' => $time,
            ));
    
            $result = $this->PartialPaymentModel->createOnePartialPayment(array(
                'invoice_id' => $tmp_invoice_id,
                'payment_amount' => $due_balance,
                'payment_applied' => $due_balance,
                'payment_datetime' => date("Y-m-d H:i:s"),
                'payment_method' => $data['payment_method'],
                'check_number' => (isset($check_number) ? $check_number : ''),
                'cc_number' => (isset($cc_number) ? $cc_number : ''),
                'payment_note' => (isset($other) ? $other : ''),
                'customer_id' => $invoice_details->customer_id,
            ));

            $result = $this->INV->updateInvoive($where, $param);
            $invoice_details = $this->INV->getOneInvoive($where);

            if ($invoice_details->quickbook_invoice_id != 0) {
                 // Assign value of invoice_details object to new variable
            $QBO_param = $invoice_details;
                
            // Declare array to be passed to coupon calculatiuon function
            $coup_inv_param = array(
                'cost' => $QBO_param->cost,
                'invoice_id' => $data['invoice_id']
            );

            // Assign value of calculation function to new variable
            $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

            // Assign value of variable as new cost to pass to QBO
            $QBO_param->cost = $cost_with_inv_coupon;

            // die(print($QBO_param->cost));

            // Update QBO Invoice with any new info
                $res = $this->QuickBookInvUpdate($invoice_details);
            }

            if ($result) {
                if (isset($err_msg)) {
                    echo $err_msg;
                    return;
                }
                echo "true";
            } else {
                echo "false";
            }
            return;
        } else if ($data['payment_status'] == 4 && $due_balance > 0) {
            // die(print_r($data));
            $invoice_nums = $this->INV->getOneRow(array('invoice_id ' => $data['invoice_id']));
            $data['refund_total'] = $invoice_nums->refund_amount_total;
            $data['partial_payment'] = $invoice_nums->partial_payment;
            $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                'invoice_id' => $tmp_invoice_id,
            ));
            $payment_log_id = $all_partial_payments[0]->payment_invoice_logs_id;
            foreach ($all_partial_payments as $k => $payment) {
                $all_payments[$k] = $payment->payment_amount;

                $payments_total = array_sum($all_payments);
            }
            #### UPDATING INVOICE BY INVOICE ID SETTING PAYMENTS BACK TO $0.00 ####
            $where = array(
                'invoice_id' => $data['invoice_id'],
            );

            ##### CREATE A NEW REFUND PAYMENT LOG #####

            if ($data['payment_method'] == 1) {
                $check_number = $data['payment_info'];
                $param['check_number'] = $check_number;
            } else if ($data['payment_method'] == 2 ) {
                $cc_number = $data['payment_info'];
                $param['cc_number'] = $cc_number;
            } else if ($data['payment_method'] == 0 || $data['payment_method'] == 3) {
                $other = $data['payment_info'];
                $param['other_note'] = $other;
            } else {
                $refund_note = $data['refund_note'];
            }


            // die(print_r($other));
            // $refund = $data['partial_payment'] - $data['refund_total'];

            $param = array(
                // 'payment_invoice_logs_id' => $payment_log_id,
                'invoice_id' => $tmp_invoice_id,
                'refund_amount' => $data['refund_payment'],
                // 'refund_amount' => $refund,
                'refund_datetime' => date("Y-m-d H:i:s"),
                'refund_method' => $data['payment_method'],
                'check_number' => (isset($check_number) ? $check_number : ''),
                'cc_number' => (isset($cc_number) ? $cc_number : ''),
                'refund_note' => (isset($other) ? $other : ''),
                'customer_id' => $invoice_details->customer_id,
            );

            $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
            $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
            $time = $date->format('Y-m-d H:i:s');

            $result = $this->PaymentLogModel->createLogRecord(array(
                'invoice_id' => $tmp_invoice_id,
                'user_id' => $this->session->userdata['id'],
                "amount" => $data['refund_payment'],
                'action' => "Refund Given",
                'created_at' => $time,
            ));

            $refund_details = $this->RefundPaymentModel->createOnePartialRefund($param);
            ####
            // $refund = $data['partial_payment'] - $data['refund_total'];
            $param = array(
                // 'partial_payment' =>($payment_total - $payment_total),
                // 'refund_amount_total' => $data['refund_total'] + $refund
                'refund_amount_total' => $data['refund_total'] + $data['refund_payment'],
                'payment_status' => $data['payment_status'],
            );

            $result = $this->INV->updateInvoive($where, $param);

            ##### UPDATE PAYMENT INVOICE LOG #####
            $where1 = array(
                'invoice_id' => $data['invoice_id'],
            );

            $param1 = array(
                'payment_applied' => 0,
            );

            $update_details = $this->PartialPaymentModel->udpatePartialPayment($where1, $param1);
            // die();
            ###
            //     $new_total_partial = $invoice_details->partial_payment + $due_balance;

            //     $total_cost_all_partial_payment_logs = 0;
            //     $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
            //         'invoice_id' => $tmp_invoice_id,
            //     ));
            //     foreach($all_partial_payments as $partial_payment) {
            //         $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
            //     }

            // $total_cost_all_partial_payment_logs += $data['partial_payment'];
            //     $new_total_partial = $total_cost_all_partial_payment_logs + $due_balance;
            // die(print_r(floatval($over_all_due)));
            //     $param['partial_payment'] = $new_total_partial;
            //     $param['payment_status'] = 4;

            //     $result = $this->PartialPaymentModel->createOnePartialPayment(array(
            //         'invoice_id' => $tmp_invoice_id,
            //         'payment_amount' =>$due_balance,
            //         'payment_datetime' => date("Y-m-d H:i:s"),
            //         'payment_method' => $data['payment_method'],
            //         'customer_id' => $invoice_details->customer_id
            //     ));

            //     $result = $this->INV->updateInvoive($where,$param);
            //     $invoice_details = $this->INV->getOneInvoive($where);
            if ($result) {
                if (isset($err_msg)) {
                    echo $err_msg;
                    return;
                }
                echo "true";
            } else {
                echo "false";
            }
            return;
        } else if ($data['payment_status'] == 4 && $due_balance == 0) {
            // die(print_r($data));
            $invoice_nums = $this->INV->getOneRow(array('invoice_id ' => $data['invoice_id']));
            $data['refund_total'] = $invoice_nums->refund_amount_total;
            $data['partial_payment'] = $invoice_nums->partial_payment;
            $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                'invoice_id' => $tmp_invoice_id,
            ));
            // $payment_log_id = $all_partial_payments[0]->payment_invoice_logs_id;
            foreach ($all_partial_payments as $k => $payment) {
                $all_payments[$k] = $payment->payment_amount;

                $payments_total = array_sum($all_payments);
            }
            // die(print_r($payments_total));
            // die(print_r($all_payments));
            // die(print_r($all_partial_payments));
            #### UPDATING INVOICE BY INVOICE ID SETTING PAYMENTS BACK TO $0.00 ####
            $where = array(
                'invoice_id' => $data['invoice_id'],
            );

            // $refund = $data['partial_payment'] - $data['refund_total'];

            ##### CREATE A NEW REFUND PAYMENT LOG #####

            if ($data['payment_method'] == 1) {
                if(isst($data['payment_info'])){
                    $check_number = $data['payment_info'];
                }else{
                    $check_number = $data['refund_note'];
                }
                $param['check_number'] = $check_number;
            } else if ($data['payment_method'] == 2 ) {
                if(isst($data['payment_info'])){
                    $cc_number = $data['payment_info'];
                }else{
                    $cc_number = $data['refund_note'];
                }
                $param['cc_number'] = $cc_number;
            } else if ($data['payment_method'] == 0 || $data['payment_method'] == 3) {
                if(isst($data['payment_info'])){
                    $other = $data['payment_info'];
                }else{
                    $other = $data['refund_note'];
                }
                $param['other_note'] = $other;
            } else {
                $refund_note = $data['refund_note'];
            }

            // die(print_r($other));
            // $refund = $data['partial_payment'] - $data['refund_total'];

            $param = array(
                // 'payment_invoice_logs_id' => $payment_log_id,
                'invoice_id' => $tmp_invoice_id,
                'refund_amount' => $data['refund_payment'],
                // 'refund_amount' => $refund,
                'refund_datetime' => date("Y-m-d H:i:s"),
                'refund_method' => $data['payment_method'],
                'check_number' => (isset($check_number) ? $check_number : ''),
                'cc_number' => (isset($cc_number) ? $cc_number : ''),
                'refund_note' => (isset($other) ? $other : ''),
                'customer_id' => $invoice_details->customer_id,
            );

            $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
            $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
            $time = $date->format('Y-m-d H:i:s');

            $result = $this->PaymentLogModel->createLogRecord(array(
                'invoice_id' => $tmp_invoice_id,
                'user_id' => $this->session->userdata['id'],
                "amount" => $data['refund_payment'],
                'action' => "Refund Given",
                'created_at' => $time,
            ));

            $refund_details = $this->RefundPaymentModel->createOnePartialRefund($param);
            ####
            // $refund = $data['partial_payment'] - $data['refund_total'];
            $param = array(
                // 'partial_payment' =>($payment_total - $payment_total),
                // 'refund_amount_total' => $data['refund_total'] + $refund
                'refund_amount_total' => $data['refund_total'] + $data['refund_payment'],
                'payment_status' => $data['payment_status'],
            );

            $result = $this->INV->updateInvoive($where, $param);

            ##### UPDATE PAYMENT INVOICE LOG #####
            $where1 = array(
                'invoice_id' => $data['invoice_id'],
            );

            $param1 = array(
                'payment_applied' => 0,
            );

            $update_details = $this->PartialPaymentModel->udpatePartialPayment($where1, $param1);
            // die();
            ###
            //     $new_total_partial = $invoice_details->partial_payment + $due_balance;

            //     $total_cost_all_partial_payment_logs = 0;
            //     $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
            //         'invoice_id' => $tmp_invoice_id,
            //     ));
            //     foreach($all_partial_payments as $partial_payment) {
            //         $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
            //     }

            // $total_cost_all_partial_payment_logs += $data['partial_payment'];
            //     $new_total_partial = $total_cost_all_partial_payment_logs + $due_balance;
            // die(print_r(floatval($over_all_due)));
            //     $param['partial_payment'] = $new_total_partial;
            //     $param['payment_status'] = 4;

            //     $result = $this->PartialPaymentModel->createOnePartialPayment(array(
            //         'invoice_id' => $tmp_invoice_id,
            //         'payment_amount' =>$due_balance,
            //         'payment_datetime' => date("Y-m-d H:i:s"),
            //         'payment_method' => $data['payment_method'],
            //         'customer_id' => $invoice_details->customer_id
            //     ));

            //     $result = $this->INV->updateInvoive($where,$param);
            //     $invoice_details = $this->INV->getOneInvoive($where);
            if ($result) {
                if (isset($err_msg)) {
                    echo $err_msg;
                    return;
                }
                echo "true";
            } else {
                echo "false";
            }
            return;
        } else {
            $total_cost_all_partial_payment_logs = 0;
            $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                'invoice_id' => $tmp_invoice_id,
            ));
            foreach ($all_partial_payments as $partial_payment) {
                $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
            }
            $param['partial_payment'] = $total_cost_all_partial_payment_logs;
        }

        $result = $this->INV->updateInvoive($where, $param);

        $invoice_details = $this->INV->getOneInvoive($where);

        if ($invoice_details->quickbook_invoice_id != 0) {

            // Assign value of invoice_details object to new variable
           $QBO_param = $invoice_details;
                
           // Declare array to be passed to coupon calculatiuon function
           $coup_inv_param = array(
               'cost' => $QBO_param->cost,
               'invoice_id' => $data['invoice_id']
           );

           // Assign value of calculation function to new variable
           $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

           // Assign value of variable as new cost to pass to QBO
           $QBO_param->cost = $cost_with_inv_coupon;

           // die(print($QBO_param->cost));

           // Update QBO Invoice with any new info

            $res = $this->QuickBookInvUpdate($invoice_details);

            //var_dump($res);
        }

        if ($result) {
            echo "true";
        } else {
            echo "false";
        }
    }

    public function changeMultiplePaymentStatus()
    {


        $data = $this->input->post();
         $iteration = 0;
         $selected_amounts = json_decode($data['selected_amounts']);
        /** get company info */
        $company_id = $this->session->userdata['company_id'];
        $where_arr = array(
            'company_id' => $company_id,
            'status' => 1,
        );

        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        if ($data['cardconnect_details'] && $data['payment_method'] == '2') {
            $data['payment_method'] = 4;
        }


        // die(print_r($data));


        /**
         * Start loop for payments
         */

        $error = false;
        $error_message = '';

        foreach (json_decode($data['selected_invoices']) as $invoice){


            $where = array(
                'invoice_id' => $invoice,
            );

            $param = array(
                'payment_status' => $data['payment_status'],
                'last_modify' => date("Y-m-d H:i:s"),
            );

            $invoice_details = $this->INV->getOneInvoive($where);

            $total_tax_amount = getAllSalesTaxSumByInvoice($invoice)->total_tax_amount;

            //    die(print_r($invoice_details));

            ////////////////////////////////////
            // START INVOICE CALCULATION COST //

            // vars
            $tmp_invoice_id = $invoice;

            // invoice cost
            // $invoice_total_cost = $invoice->cost;

            // cost of all services (with price overrides) - service coupons
            $job_cost_total = 0;
            $where_alt = array(
                'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
            );
            $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where_alt);

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
                                $coupon_job_amm_total = (float) $coupon_job_amm;
                            } else { // percentage
                                $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                            }

                            $job_cost = $job_cost - $coupon_job_amm_total;

                            if ($job_cost < 0) {
                                $job_cost = 0;
                            }
                        }
                    }

                    $job_cost_total += $job_cost;
                }
                $invoice_total_cost = $job_cost_total;
            } else {
                $invoice_total_cost = $invoice_details->cost;
            }

            // check price override -- any that are not stored in just that ^^.

            // - invoice coupons
            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
            foreach ($coupon_invoice_details as $coupon_invoice) {
                if (!empty($coupon_invoice)) {
                    $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                    $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                    if ($coupon_invoice_amm_calc == 0) { // flat amm
                        $invoice_total_cost -= (float) $coupon_invoice_amm;
                    } else { // percentage
                        $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                        $invoice_total_cost -= $coupon_invoice_amm;
                    }
                    if ($invoice_total_cost < 0) {
                        $invoice_total_cost = 0;
                    }
                }
            }

            // + tax cost
            $invoice_total_tax = 0;
            $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
            if (!empty($invoice_sales_tax_details)) {
                foreach ($invoice_sales_tax_details as $tax) {
                    if (array_key_exists("tax_value", $tax)) {
                        $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                        $invoice_total_tax += $tax_amm_to_add;
                    }
                }
            }
            $invoice_total_cost += $invoice_total_tax;
            $total_tax_amount = $invoice_total_tax;

            // END TOTAL INVOICE CALCULATION COST //
            ////////////////////////////////////////

//            $over_all_due = $invoice_details->cost + $total_tax_amount;
//
//            $over_all_due = $invoice_total_cost;
//            $over_all_due = $data['selected_amounts'];
            $over_all_due= $selected_amounts[$iteration];
            //die($selected_amounts[$iteration]);

            if (isset($selected_amounts[$iteration])) {
                $due_balance = (float) str_replace(' ', '', $over_all_due);
            } else {
                $due_balance = 0;
            }
            $iteration +=1;
            // die(print_r($data));
            if ($data['payment_status'] == 1) {

                //KT
                $param['status'] = 2;
                if ($invoice_details->opened_date == '') {
                    $param['opened_date'] = date("Y-m-d H:i:s");
                } else {
                    $param['opened_date'] = $invoice_details->opened_date;
                }
                if ($invoice_details->sent_date == '') {
                    $param['sent_date'] = date("Y-m-d H:i:s");
                } else {
                    $param['sent_date'] = $invoice_details->sent_date;
                }
                //...

                $new_total_partial = $invoice_details->partial_payment + $data['partial_payment'];

                $total_cost_all_partial_payment_logs = 0;
                $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                    'invoice_id' => $tmp_invoice_id,
                ));
                foreach ($all_partial_payments as $partial_payment) {
                    $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
                }

                // $total_cost_all_partial_payment_logs += $data['partial_payment'];
                $new_total_partial = $total_cost_all_partial_payment_logs + $data['partial_payment'];

                // if greater or equal, set partial to total and set to paid status
                if ($new_total_partial >= $over_all_due) {

                    $param['partial_payment'] = $over_all_due;
                    $param['payment_status'] = 2;
                    //KT
                    $param['status'] = 2;
                    if ($invoice_details->opened_date == '') {
                        $param['opened_date'] = date("Y-m-d H:i:s");
                    } else {
                        $param['opened_date'] = $invoice_details->opened_date;
                    }
                    if ($invoice_details->sent_date == '') {
                        $param['sent_date'] = date("Y-m-d H:i:s");
                    } else {
                        $param['sent_date'] = $invoice_details->sent_date;
                    }
                    //...
                    $param['payment_created'] = date("Y-m-d H:i:s");

                    if ($data['payment_method'] == 1) {
                        $check_number = $data['payment_info'];
                        $data['check_number'] = $check_number;
                    } else if ($data['payment_method'] == 2 ) {
                        $cc_number = $data['payment_info'];
                        $data['cc_number'] = $cc_number;
                    } else if ( $data['payment_method'] == 0 || $data['payment_method'] == 3) {
                        $other = $data['payment_info'];
                        $data['other'] = $other;
                    }
                    $param['payment_method'] = $data['payment_method'];


                    $result = $this->PartialPaymentModel->createOnePartialPayment(array(
                        'invoice_id' => $tmp_invoice_id,
                        'payment_amount' => $over_all_due - $total_cost_all_partial_payment_logs,
                        'payment_applied' => $over_all_due - $total_cost_all_partial_payment_logs,
                        'payment_datetime' => date("Y-m-d H:i:s"),
                        'payment_method' => $data['payment_method'],
                        'check_number' => (isset($check_number) ? $check_number : ''),
                        'cc_number' => (isset($cc_number) ? $cc_number : ''),
                        'payment_note' => (isset($other) ? $other : ''),
                        'customer_id' => $invoice_details->customer_id,
                    ));

                    $err_msg = "set to paid";
                } else {

                    // $param['partial_payment'] = $new_total_partial;
                    $param['partial_payment'] = $new_total_partial;
                    $param['payment_created'] = date("Y-m-d H:i:s");

                    if ($total_cost_all_partial_payment_logs > $over_all_due) {
                        $param['partial_payment'] = $over_all_due;
                        $param['payment_status'] = 2;
                        //KT
                        $param['status'] = 2;
                        if ($invoice_details->opened_date == '') {
                            $param['opened_date'] = date("Y-m-d H:i:s");
                        } else {
                            $param['opened_date'] = $invoice_details->opened_date;
                        }
                        if ($invoice_details->sent_date == '') {
                            $param['sent_date'] = date("Y-m-d H:i:s");
                        } else {
                            $param['sent_date'] = $invoice_details->sent_date;
                        }
                        //...
                        $err_msg = "set to paid";
                    } else {
                        if ($data['payment_method'] == 1) {
                            $check_number = $data['payment_info'];
                        } else if ($data['payment_method'] == 2 ) {
                            $cc_number = $data['payment_info'];
                        } else if ($data['payment_method'] == 0 || $data['payment_method'] == 3 ) {
                            $other = $data['payment_info'];
                        }
                        $result = $this->PartialPaymentModel->createOnePartialPayment(array(
                            'invoice_id' => $tmp_invoice_id,
                            'payment_amount' => $data['partial_payment'],
                            'payment_applied' => $data['partial_payment'],
                            'payment_datetime' => date("Y-m-d H:i:s"),
                            'payment_method' => $data['payment_method'],
                            'check_number' => (isset($check_number) ? $check_number : ''),
                            'cc_number' => (isset($cc_number) ? $cc_number : ''),
                            'payment_note' => (isset($other) ? $other : ''),
                            'customer_id' => $invoice_details->customer_id,
                        ));
                    }
                }

                $result = $this->INV->updateInvoive($where, $param);
                $invoice_details = $this->INV->getOneInvoive($where);
                if ($invoice_details->quickbook_invoice_id != 0) {
                    $res = $this->QuickBookInvUpdate($invoice_details);
                }

                if ($result) {
                    if (isset($err_msg)) {
                        echo $err_msg;
                        return;
                    }
                    $error = false;
                } else {
                    $error = true;
                }
                //return;
            } else if ($data['payment_status'] == 2 && $due_balance > 0) {

                $new_total_partial = $invoice_details->partial_payment + $due_balance;

                $total_cost_all_partial_payment_logs = 0;
                $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                    'invoice_id' => $tmp_invoice_id,
                ));
                foreach ($all_partial_payments as $partial_payment) {
                    $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
                }

                // $total_cost_all_partial_payment_logs += $data['partial_payment'];
                $new_total_partial = $total_cost_all_partial_payment_logs + $due_balance;
                // die(print_r(floatval($over_all_due)));
                $param['partial_payment'] = $new_total_partial;
                $param['payment_status'] = 2;
                //KT
                $param['status'] = 2;
                if ($invoice_details->opened_date == '') {
                    $param['opened_date'] = date("Y-m-d H:i:s");
                } else {
                    $param['opened_date'] = $invoice_details->opened_date;
                }
                if ($invoice_details->sent_date == '') {
                    $param['sent_date'] = date("Y-m-d H:i:s");
                } else {
                    $param['sent_date'] = $invoice_details->sent_date;
                }
                //...
                $param['payment_created'] = date("Y-m-d H:i:s");

                if ($data['payment_method'] == 1) {
                    $check_number = $data['payment_info'];
                    $param['check_number'] = $check_number;
                } else if ($data['payment_method'] == 2 || $data['payment_method'] == 4) {
                    $cc_number = $data['payment_info'];
                    $param['cc_number'] = $cc_number;
                } else if ($data['payment_method'] == 0 || $data['payment_method'] == 3) {
                    $other = $data['payment_info'];
                    $param['other_note'] = $other;
                }
                $param['payment_method'] = $data['payment_method'];
                $result = $this->PartialPaymentModel->createOnePartialPayment(array(
                    'invoice_id' => $tmp_invoice_id,
                    'payment_amount' => $due_balance,
                    'payment_applied' => $due_balance,
                    'payment_datetime' => date("Y-m-d H:i:s"),
                    'payment_method' => $data['payment_method'],
                    'check_number' => (isset($check_number) ? $check_number : ''),
                    'cc_number' => (isset($cc_number) ? $cc_number : ''),
                    'payment_note' => (isset($other) ? $other : ''),
                    'customer_id' => $invoice_details->customer_id,
                ));

                $result = $this->INV->updateInvoive($where, $param);
                $invoice_details = $this->INV->getOneInvoive($where);

                if ($invoice_details->quickbook_invoice_id != 0) {
                    $res = $this->QuickBookInvUpdate($invoice_details);
                }

                if ($result) {
                    $error = false;
                } else {
                    $error = true;
                }
                //return;
            } else if ($data['payment_status'] == 4 && $due_balance > 0) {
                // die(print_r($data));
                $invoice_nums = $this->INV->getOneRow(array('invoice_id ' => $data['invoice_id']));
                $data['refund_total'] = $invoice_nums->refund_amount_total;
                $data['partial_payment'] = $invoice_nums->partial_payment;
                $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                    'invoice_id' => $tmp_invoice_id,
                ));
                $payment_log_id = $all_partial_payments[0]->payment_invoice_logs_id;
                foreach ($all_partial_payments as $k => $payment) {
                    $all_payments[$k] = $payment->payment_amount;

                    $payments_total = array_sum($all_payments);
                }
                #### UPDATING INVOICE BY INVOICE ID SETTING PAYMENTS BACK TO $0.00 ####
                $where = array(
                    'invoice_id' => $data['invoice_id'],
                );

                ##### CREATE A NEW REFUND PAYMENT LOG #####

                if ($data['payment_method'] == 1) {
                    $check_number = $data['payment_info'];
                    $param['check_number'] = $check_number;
                } else if ($data['payment_method'] == 2 ) {
                    $cc_number = $data['payment_info'];
                    $param['cc_number'] = $cc_number;
                } else if ($data['payment_method'] == 0 || $data['payment_method'] == 3) {
                    $other = $data['payment_info'];
                    $param['other_note'] = $other;
                } else {
                    $refund_note = $data['refund_note'];
                }


                // die(print_r($other));
                // $refund = $data['partial_payment'] - $data['refund_total'];

                $param = array(
                    // 'payment_invoice_logs_id' => $payment_log_id,
                    'invoice_id' => $tmp_invoice_id,
                    'refund_amount' => $data['refund_payment'],
                    // 'refund_amount' => $refund,
                    'refund_datetime' => date("Y-m-d H:i:s"),
                    'refund_method' => $data['payment_method'],
                    'check_number' => (isset($check_number) ? $check_number : ''),
                    'cc_number' => (isset($cc_number) ? $cc_number : ''),
                    'refund_note' => (isset($other) ? $other : ''),
                    'customer_id' => $invoice_details->customer_id,
                );

                $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
                $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
                $time = $date->format('Y-m-d H:i:s');

                $result = $this->PaymentLogModel->createLogRecord(array(
                    'invoice_id' => $tmp_invoice_id,
                    'user_id' => $this->session->userdata['id'],
                    "amount" => $data['refund_payment'],
                    'action' => "Refund Given",
                    'created_at' => $time,
                ));

                $refund_details = $this->RefundPaymentModel->createOnePartialRefund($param);
                ####
                // $refund = $data['partial_payment'] - $data['refund_total'];
                $param = array(
                    // 'partial_payment' =>($payment_total - $payment_total),
                    // 'refund_amount_total' => $data['refund_total'] + $refund
                    'refund_amount_total' => $data['refund_total'] + $data['refund_payment'],
                    'payment_status' => $data['payment_status'],
                );

                $result = $this->INV->updateInvoive($where, $param);

                ##### UPDATE PAYMENT INVOICE LOG #####
                $where1 = array(
                    'invoice_id' => $data['invoice_id'],
                );

                $param1 = array(
                    'payment_applied' => 0,
                );

                $update_details = $this->PartialPaymentModel->udpatePartialPayment($where1, $param1);
                // die();

                if ($result) {
                    if (isset($err_msg)) {
                        echo $err_msg;
                        return;
                    }
                    echo "true";
                } else {
                    echo "false";
                }
                return;
            } else if ($data['payment_status'] == 4 && $due_balance == 0) {
                // die(print_r($data));
                $invoice_nums = $this->INV->getOneRow(array('invoice_id ' => $data['invoice_id']));
                $data['refund_total'] = $invoice_nums->refund_amount_total;
                $data['partial_payment'] = $invoice_nums->partial_payment;
                $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                    'invoice_id' => $tmp_invoice_id,
                ));
                // $payment_log_id = $all_partial_payments[0]->payment_invoice_logs_id;
                foreach ($all_partial_payments as $k => $payment) {
                    $all_payments[$k] = $payment->payment_amount;

                    $payments_total = array_sum($all_payments);
                }
                // die(print_r($payments_total));
                // die(print_r($all_payments));
                // die(print_r($all_partial_payments));
                #### UPDATING INVOICE BY INVOICE ID SETTING PAYMENTS BACK TO $0.00 ####
                $where = array(
                    'invoice_id' => $data['invoice_id'],
                );

                // $refund = $data['partial_payment'] - $data['refund_total'];

                ##### CREATE A NEW REFUND PAYMENT LOG #####

                if ($data['payment_method'] == 1) {
                    $check_number = $data['payment_info'];
                    $param['check_number'] = $check_number;
                } else if ($data['payment_method'] == 2 ) {
                    $cc_number = $data['payment_info'];
                    $param['cc_number'] = $cc_number;
                } else if ($data['payment_method'] == 0 || $data['payment_method'] == 3) {
                    $other = $data['payment_info'];
                    $param['other_note'] = $other;
                } else {
                    $refund_note = $data['refund_note'];
                }

                // die(print_r($other));
                // $refund = $data['partial_payment'] - $data['refund_total'];

                $param = array(
                    // 'payment_invoice_logs_id' => $payment_log_id,
                    'invoice_id' => $tmp_invoice_id,
                    'refund_amount' => $data['refund_payment'],
                    // 'refund_amount' => $refund,
                    'refund_datetime' => date("Y-m-d H:i:s"),
                    'refund_method' => $data['payment_method'],
                    'check_number' => (isset($check_number) ? $check_number : ''),
                    'cc_number' => (isset($cc_number) ? $cc_number : ''),
                    'refund_note' => (isset($other) ? $other : ''),
                    'customer_id' => $invoice_details->customer_id,
                );

                $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
                $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
                $time = $date->format('Y-m-d H:i:s');

                $result = $this->PaymentLogModel->createLogRecord(array(
                    'invoice_id' => $tmp_invoice_id,
                    'user_id' => $this->session->userdata['id'],
                    "amount" => $data['refund_payment'],
                    'action' => "Refund Given",
                    'created_at' => $time,
                ));

                $refund_details = $this->RefundPaymentModel->createOnePartialRefund($param);
                ####
                // $refund = $data['partial_payment'] - $data['refund_total'];
                $param = array(
                    // 'partial_payment' =>($payment_total - $payment_total),
                    // 'refund_amount_total' => $data['refund_total'] + $refund
                    'refund_amount_total' => $data['refund_total'] + $data['refund_payment'],
                    'payment_status' => $data['payment_status'],
                );

                $result = $this->INV->updateInvoive($where, $param);

                ##### UPDATE PAYMENT INVOICE LOG #####
                $where1 = array(
                    'invoice_id' => $data['invoice_id'],
                );

                $param1 = array(
                    'payment_applied' => 0,
                );

                $update_details = $this->PartialPaymentModel->udpatePartialPayment($where1, $param1);

                if ($result) {
                    if (isset($err_msg)) {
                        echo $err_msg;
                        return;
                    }
                    $error = false;
                } else {
                    $error = true;
                }
                //return;
            } else {
                $total_cost_all_partial_payment_logs = 0;
                $all_partial_payments = $this->PartialPaymentModel->getAllPartialPayment(array(
                    'invoice_id' => $tmp_invoice_id,
                ));
                foreach ($all_partial_payments as $partial_payment) {
                    $total_cost_all_partial_payment_logs += $partial_payment->payment_amount;
                }
                $param['partial_payment'] = $total_cost_all_partial_payment_logs;
            }

            $result = $this->INV->updateInvoive($where, $param);

            $invoice_details = $this->INV->getOneInvoive($where);

            if ($invoice_details->quickbook_invoice_id != 0) {

                $res = $this->QuickBookInvUpdate($invoice_details);
            }


        }

        if ($error) {
            echo 'false';
        } else {
            echo 'true';
        }

    }

    public function sendPdfMail()
    {
        $company_id = $this->session->userdata['company_id'];

        $invoice_id = $this->input->post('invoice_id');
        $customer_id = $this->input->post('customer_id');
        $message = $this->input->post('message');
        $data['msgtext'] = $message[0];

        $where = array('invoice_id' => $invoice_id);
        $invoice_details = $this->INV->getOneInvoive($where);
        $param = array('last_modify' => date("Y-m-d H:i:s"));
        // Need to change status to sent for case other than paid or partial payment.
        if ($invoice_details->status == 0 ) {
            $param['status'] = 1;
            $param['sent_date'] = date("Y-m-d H:i:s");
            $credit_balance_check = 1;
            if (empty($invoice_details->first_sent_date)) {
                $param['first_sent_date'] = date("Y-m-d H:i:s");
            }
        } else if ($invoice_details->status == 1){
            $param['sent_date'] = date("Y-m-d H:i:s");
        }
        $this->INV->updateInvoive($where, $param);

        if($credit_balance_check){
            // ** PROCESS CREDIT BALANCE ON THIS INVOICE
            //Check if invoiceCreditBalance available and process this invoice
            //get unpaid invoices for customer
                    $customer_id = $invoice_details->customer_id;
                    $unpaid = $this->INV->getUnpaidInvoiceById($invoice_details->invoice_id);
                    // die(print_r($unpaid));
                    $customer_info = $this->CustomerModel->getCustomerDetail($customer_id);
                    $credit_amount = $customer_info['credit_amount'];
    
                    if(!empty($unpaid)){
                      
                          $invoice_amount  = $unpaid->unpaid_amount;
                        //   die(print_r($invoice_amount));
                          if($credit_amount >= $invoice_amount){
                            // die(print_r($credit_amount));
                            $result = $this->INV->createOnePartialPayment(array(
                                          'invoice_id' => $unpaid->unpaid_invoice,
                                          'payment_amount' => $invoice_amount,
                                          'payment_applied' => $invoice_amount,
                                          'payment_datetime' => date("Y-m-d H:i:s"),
                                          'payment_method' => 5,
                                          'check_number' => null,
                                          'cc_number' => null,
                                          'payment_note' => "Payment made from credit amount {$credit_amount}",
                                          'customer_id' => $customer_id,
                                      ));
    
                            // die(print_r($result));
                            
    
                            //mark this invoice as paid
                            $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 2, 'payment_status' => 2, 'partial_payment' => $invoice_amount, 'payment_created' => date('Y-m-d H:i:s')]);
                        
                            $credit_amount -= $invoice_amount;
                        
                        } else if($credit_amount > 0 && $invoice_amount > 0){
                            $result = $this->INV->createOnePartialPayment(array(
                                'invoice_id' => $unpaid->unpaid_invoice,
                                'payment_amount' => $credit_amount,
                                'payment_applied' => $credit_amount,
                                'payment_datetime' => date("Y-m-d H:i:s"),
                                'payment_method' => 5,
                                'check_number' => null,
                                'cc_number' => null,
                                'payment_note' => "Payment made from credit amount {$credit_amount}",
                                'customer_id' => $customer_id,
                            ));
    
                            // die(print_r($result));
                            
    
                            //mark this invoice as paid
                            $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 1, 'payment_status' => 1, 'partial_payment' => $credit_amount, 'payment_created' => date('Y-m-d H:i:s')]);  
                        
                            $credit_amount = 0;
                        }
    
                      //update customers.credit_amount adjusted credit_amount balance
                      $this->INV->adjustCreditPayment($customer_id, $credit_amount);
                      //update partial payment
                      //Disable this as it's add credit again to payment_invoice_logs table
                      /*$result = $this->INV->createOnePartialPayment(array(
                          'invoice_id' => $invoice_id,
                          'payment_amount' => $credit_amount,
                          'payment_applied' => $credit_amount,
                          'payment_datetime' => date("Y-m-d H:i:s"),
                          'payment_method' => 1,
                          'check_number' => null,
                          'cc_number' => null,
                          'payment_note' => "Adding Credit to customer's account",
                          'customer_id' => $customer_id,
                      ));*/
                    }
            }
        if ($invoice_details->quickbook_invoice_id != 0) {

            // Assign value of invoice_details object to new variable
            $QBO_param = $invoice_details;
                
            // Declare array to be passed to coupon calculatiuon function
            $coup_inv_param = array(
                'cost' => $QBO_param->cost,
                'invoice_id' => $invoice_id
            );

            // Assign value of calculation function to new variable
            $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

            // Assign value of variable as new cost to pass to QBO
            $QBO_param->cost = $cost_with_inv_coupon;

            // die(print($QBO_param->cost));

            // Update QBO Invoice with any new info

            $res = $this->QuickBookInvUpdate($invoice_details);

            //var_dump($res);
        }

        $data['customer_details'] = $this->CustomerModel->getOneCustomerDetail($customer_id);
        $data['link'] = base_url('welcome/printInvoice/') . $company_id . '/' . $invoice_id;
        $data['linkView'] = base_url('welcome/displayInvoice/') . $company_id . '/' . $invoice_id;
        $data['invoice_id'] = $invoice_id;
        $where_company = array('company_id' => $company_id);

        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;

        $body = $this->load->view('admin/invoice/email_pdf', $data, true);

        $where_company['is_smtp'] = 1;
        $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);

        if (!$company_email_details) {

            //  echo "defalt one";
            $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
        }
        
        $res = Send_Mail_dynamic($company_email_details, $data['customer_details']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Invoice Details', $data['customer_details']->secondary_email);
        echo 1;
    }

    public function sendPdfMailToSelected()
    {
        $company_id = $this->session->userdata['company_id'];
        $group_id_array = $this->input->post('group_id_array');
        $message = $this->input->post('message');
        $data['msgtext'] = $message[0];
        $customer_wise_data = [];
        if (!empty($group_id_array)) {
            foreach ($group_id_array as $key => $value) {
                $inv_cust_arr = explode(':', $value);
                $invoice_id = $inv_cust_arr[0];
                $customer_id = $inv_cust_arr[1];
                $where = array('invoice_id' => $invoice_id);
                $invoice_details = $this->INV->getOneInvoive($where);
                //if($invoice_details->basys_transaction_id == "") {
                if ($invoice_details->quickbook_invoice_id != 0) {
                    // Assign value of invoice_details object to new variable
                    $QBO_param = $invoice_details;
                        
                    // Declare array to be passed to coupon calculatiuon function
                    $coup_inv_param = array(
                        'cost' => $QBO_param->cost,
                        'invoice_id' => $data['invoice_id']
                    );

                    // Assign value of calculation function to new variable
                    $cost_with_inv_coupon = $this->calculateInvoiceCouponValue($coup_inv_param);

                    // Assign value of variable as new cost to pass to QBO
                    $QBO_param->cost = $cost_with_inv_coupon;

                    // die(print($QBO_param->cost));

                    // Update QBO Invoice with any new info
                    $res = $this->QuickBookInvUpdate($invoice_details);
                }

                $detail = array(
                    'invoice_id' => $invoice_id,
                    'invoice_status' => $invoice_details->status,
                );
                if (array_key_exists($customer_id, $customer_wise_data)) {
                    array_push($customer_wise_data[$customer_id], $detail);
                } else {
                    $customer_wise_data[$customer_id][] = $detail;
                }
                //}
            }
            if (!empty($customer_wise_data)) {
                $where_company = array('company_id' => $company_id);
                $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
                $data['setting_details']->company_logo = ($data['setting_details']->company_resized_logo != '') ? $data['setting_details']->company_resized_logo : $data['setting_details']->company_logo;
                //var_dump($customer_wise_data);
                foreach ($customer_wise_data as $customer_id => $customer_data) {
                    $customer_details_data = new stdClass();
                    $customer_details_data = $this->CustomerModel->getOneCustomerDetail($customer_id);
                    $email = $customer_details_data->email;
                    $secondary_email = (isset($customer_details_data->secondary_email)?$customer_details_data->secondary_email:"");
                    $invoice_id_list = array_column($customer_data, 'invoice_id');
                    $data['bulk_invoice_id'] = implode(',', $invoice_id_list);
                    $hashstring = md5($email . "-" . $customer_id . "-" . date("Y-m-d H:i:s"));
                    $data['link'] = base_url('welcome/pdfDailyInvoice/') . $hashstring;
                    $data['linkView'] = base_url('welcome/displayDailyInvoice/') . $hashstring;
                    $data["customer_details"] = $customer_details_data;
                    $body = $this->load->view('admin/invoice/email_pdf', $data, true);
                    $where_company['is_smtp'] = 1;
                    $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray($where_company);
                    if (!$company_email_details) {
                        $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                    }

                    $batch_insert_arr = array();
                    foreach ($customer_data as $value) {
                        $hash_tbl_arr = array();
                        $update_arr = array('last_modify' => date("Y-m-d H:i:s"));
                        $credit_balance_check = 0;
                        
                        if ($value['invoice_status'] == 0) {
                            $update_arr['status'] = 1;
                            $update_arr['sent_date'] = date("Y-m-d H:i:s");
                            $credit_balance_check = 1;
                            if (empty($invoice_details->first_sent_date)) {
                                $update_arr['first_sent_date'] = date("Y-m-d H:i:s");
                            }
                        } else if ($invoice_details->status == 1) {
                            $update_arr['sent_date'] = date("Y-m-d H:i:s");
                        }
                        $where_arr = array("invoice_id" => $value['invoice_id']);
                        $this->INV->updateInvoive($where_arr, $update_arr);

                        if($credit_balance_check){
                            // ** PROCESS CREDIT BALANCE ON THIS INVOICE
                            //Check if invoiceCreditBalance available and process this invoice
                            //get unpaid invoices for customer
                                    $unpaid = $this->INV->getUnpaidInvoiceById($value['invoice_id']);
                                    // die(print_r($unpaid));
                                    $customer_info = $this->CustomerModel->getCustomerDetail($customer_id);
                                    $credit_amount = $customer_info['credit_amount'];
                    
                                    if(!empty($unpaid)){

                                          $invoice_amount  = $unpaid->unpaid_amount;
                                        //   die(print_r($invoice_amount));
                                          if($credit_amount >= $invoice_amount){
                                            // die(print_r($credit_amount));
                                            $result = $this->INV->createOnePartialPayment(array(
                                                          'invoice_id' => $unpaid->unpaid_invoice,
                                                          'payment_amount' => $invoice_amount,
                                                          'payment_applied' => $invoice_amount,
                                                          'payment_datetime' => date("Y-m-d H:i:s"),
                                                          'payment_method' => 5,
                                                          'check_number' => null,
                                                          'cc_number' => null,
                                                          'payment_note' => "Payment made from credit amount {$credit_amount}",
                                                          'customer_id' => $customer_id,
                                                      ));
                    
                                            // die(print_r($result));
                                            
                    
                                            //mark this invoice as paid
                                            $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 2, 'payment_status' => 2, 'partial_payment' => $invoice_amount, 'payment_created' => date('Y-m-d H:i:s')]);
                                        
                                            $credit_amount -= $invoice_amount;
                                        
                                        } else if($credit_amount > 0 && $invoice_amount > 0){
                                            $result = $this->INV->createOnePartialPayment(array(
                                                'invoice_id' => $unpaid->unpaid_invoice,
                                                'payment_amount' => $credit_amount,
                                                'payment_applied' => $credit_amount,
                                                'payment_datetime' => date("Y-m-d H:i:s"),
                                                'payment_method' => 5,
                                                'check_number' => null,
                                                'cc_number' => null,
                                                'payment_note' => "Payment made from credit amount {$credit_amount}",
                                                'customer_id' => $customer_id,
                                            ));
                    
                                            // die(print_r($result));
                                            
                    
                                            //mark this invoice as paid
                                            $this->INV->updateInvoive(['invoice_id'=> $unpaid->unpaid_invoice], ['status' => 1, 'payment_status' => 1, 'partial_payment' => $credit_amount, 'payment_created' => date('Y-m-d H:i:s')]);  
                                        
                                            $credit_amount = 0;
                                        }
                    
                                      //update customers.credit_amount adjusted credit_amount balance
                                      $this->INV->adjustCreditPayment($customer_id, $credit_amount);
                                      //update partial payment
                                      //Disable this as it's add credit again to payment_invoice_logs table
                                      /*$result = $this->INV->createOnePartialPayment(array(
                                          'invoice_id' => $invoice_id,
                                          'payment_amount' => $credit_amount,
                                          'payment_applied' => $credit_amount,
                                          'payment_datetime' => date("Y-m-d H:i:s"),
                                          'payment_method' => 1,
                                          'check_number' => null,
                                          'cc_number' => null,
                                          'payment_note' => "Adding Credit to customer's account",
                                          'customer_id' => $customer_id,
                                      ));*/
                                    }
                            }

                        $hash_tbl_arr['invoice_id'] = $value['invoice_id'];
                        $hash_tbl_arr['company_id'] = $company_id;
                        $hash_tbl_arr['hashstring'] = $hashstring;
                        $hash_tbl_arr['created_at'] = date("Y-m-d H:i:s");
                        array_push($batch_insert_arr, $hash_tbl_arr);
                    }
                    // Added hash table concept to get rid of expired link issue.
                    $this->db->insert_batch('invoice_hash_tbl', $batch_insert_arr);
                    // Set the URL for downloading
                    $res = Send_Mail_dynamic($company_email_details, $email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email), $body, 'Invoice Details - ' . date('Y-m-d'), $data['customer_details']->secondary_email);
                }
            }
        }

        if (isset($res)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    // public function getCalCulation() {
    //     $where_unpaid = array('user_id' =>$this->session->userdata['user_id'],'status'=>1);
    //     $result_unpaid =   $this->INV->getSumInvoive($where_unpaid);
//
    //     $where_revenue = array('user_id' =>$this->session->userdata['user_id'],'status'=>2);
    //     $result_revenue =   $this->INV->getSumInvoive($where_revenue);
//
    //     $return_array = array(
    //         'total_unpaid' => number_format(($result_unpaid->cost ? $result_unpaid->cost : 0),2),
    //         'total_billed' =>number_format($result_unpaid->cost+($result_revenue->cost ? $result_revenue->cost : 0),2),
    //         'total_revenue' =>number_format(($result_revenue->cost ? $result_revenue->cost : 0),2),
//
    //      );
    //     echo (json_encode($return_array));
//
    // }

    public function deletemultipleInvoices($value = '')
    {
        $invoices = $this->input->post('invoices');
        if (!empty($invoices)) {
            foreach ($invoices as $key => $value) {

                $where = array('invoice_id' => $value);
                $this->INV->deleteInvoice($where);
            }
            echo 1;
        } else {
            echo 0;
        }
    }
    public function addLateFeeInvoices($value = '')
    {
        $invoice_ids = $this->input->post('invoices');

        if(!empty($invoice_ids)){
            $this->INV->updateInvoiceForInvoices($invoice_ids, ['is_late_fee' => 1]);
            print 1;
        }else{
            print 0;
        }
        //send late fee emails logic

        //get invoice data for all invoices in the list
        $invoices = $this->INV->ajaxActiveInvoices(0, 0, 0, 0, 0, $invoice_ids);
        //loop through the result
        foreach ($invoices as $key => $invoice) {

            if($invoice->is_late_fee /*&& !$invoice->late_fee_email_sent*/ && !empty($invoice->late_fee_email) && !empty($invoice->email)){
                $data['invoice'] = $invoice;
                //get the email template
                $body = $this->load->view('email/latefee_email', $data, true);
                //send email
                // $sendEmail = Send_Mail_dynamic(null, $invoice->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Invoice Late Fee Notice', );




            }
        }
        // check late_fee_email_status = 1 and late_fee_email is not empty

        //get the email address for invoice

        //get the email template

        // send email
        // $emaildata['customerData'] = $this->CustomerModel->getOneCustomer(array('customer_id'=>$customer_details->customer_id));
        // $emaildata['email_data_details'] = $this->Tech->getProgramPropertyEmailData(array('customer_id'=>$customer_details->customer_id,'is_email'=>1, 'program_id'=>$data['program_id'],'property_id'=>$value));
        // $emaildata['company_details'] = $this->CompanyModel->getOneCompany(array('company_id' => $this->session->userdata['company_id']));
        // $emaildata['company_email_details'] = $this->CompanyEmail->getOneCompanyEmail(array('company_id'=>$this->session->userdata['company_id']));
        // $emaildata['assign_date'] = date("Y-m-d H:i:s");
        // $company_email_details = $this->CompanyEmail->getOneCompanyEmailArray(array('company_id'=>$this->session->userdata['company_id'],'is_smtp'=>1));
        /*$body  = $this->load->view('email/program_email', $emaildata, true);
        if(!$company_email_details){
                    $company_email_details = $this->Administratorsuper->getOneDefaultEmailArray();
                  }
        #check if company setting for this notification are turned on AND check if customer is subscribed to email notifications
        if($emaildata['company_email_details']->program_assigned_status == 1 && isset($emaildata['customerData']->is_email) && $emaildata['customerData']->is_email ==1){
          $sendEmail = Send_Mail_dynamic($company_email_details, $emaildata['customerData']->email, array("name" => $this->session->userdata['compny_details']->company_name, "email" => $this->session->userdata['compny_details']->company_email),  $body, 'Program Assigned', $emaildata['customerData']->secondary_email);*/
        //

    }
    /**
     * Restores selected invoices
     */
    public function restoremultipleInvoices($value = '')
    {
        $invoices = $this->input->post('invoices');
        if (!empty($invoices)) {
            foreach ($invoices as $key => $value) {
                $where = array('invoice_id' => $value);
                $this->INV->restoreInvoice($where);
            }
            echo 1;
        } else {
            echo 0;
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

    public function QuickBookInvUpdate($param)
    {

        $customer_details = $this->CustomerModel->getCustomerDetail($param->customer_id);

        if ($customer_details['quickbook_customer_id'] != 0) {
            $quickBookCustomerDetails = $this->getOneQuickBookCustomer($customer_details['quickbook_customer_id']);

            // var_dump($quickBookCustomerDetails);

            if ($quickBookCustomerDetails) {

                $param->quickbook_customer_id = $customer_details['quickbook_customer_id'];

                $result = $this->updateInvoiceInQuickBook($param);

                // var_dump($result);
                // die();

                if ($result['status'] == 200) {
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

    public function updateInvoiceInQuickBook($param)
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

                $entities = $dataService->Query("SELECT * FROM Invoice where Id='" . $param->quickbook_invoice_id . "'");

                // $entities = $dataService->Query("SELECT * FROM Invoice where Id='".$invoiceId."'");
                $error = $dataService->getLastError();
                if ($error) {
                    $return_error = '';

                    $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                    return array('status' => 400, 'msg' => 'Invoice not added successfully', 'result' => $return_error);
                } else {

                    if (!empty($entities)) {

                        $theInvoice = reset($entities);

                        $details = getVisIpAddr();

                        $all_sales_tax = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $param->invoice_id));

                        $line_ar[] = array(
                            "Description" => $param->job_name,
                            "Amount" => $param->cost,
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
                            "DocNumber" => $param->invoice_id,
                            "TxnDate" => $param->invoice_date,
                            "Line" => $line_ar,
                            "CustomerRef" => array(
                                "value" => $param->quickbook_customer_id,
                            ),
                        );

                        if ($param->email != '') {

                            $invoice_arr['BillEmail'] = array(
                                "Address" => $param->email,
                            );
                            $invoice_arr['EmailStatus'] = "NeedToSend";
                        }

                        $updateInvoice = Invoice::update($theInvoice, $invoice_arr);

                        $resultingCustomerUpdatedObj = $dataService->Update($updateInvoice);

                        $this->invoicePaymentManage($dataService, $param);

                        if ($error) {
                            $return_error = '';
                            $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                            $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                            $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                            return array('status' => 400, 'msg' => 'Invoice not update successfully', 'result' => $return_error);
                        } else {

                            // var_dump($resultingCustomerUpdatedObj);

                            // print_r($resultingCustomerUpdatedObj);
                            // die();

                            if ($resultingCustomerUpdatedObj) {

                                return array('status' => 200, 'msg' => 'invoice update successfully', 'result' => $resultingCustomerUpdatedObj->Id);
                            } else {

                                return array('status' => 400, 'msg' => 'invoice not  update successfully', 'result' => '');
                            }
                        }
                    } else {

                        return array('status' => 404, 'msg' => 'invoice not found', 'result' => '');
                    }
                }
            } catch (Exception $ex) {
                return array('status' => 400, 'msg' => $ex->getMessage(), 'result' => '');

                // echo 'Message: ' .$ex->getMessage();
            }
        } else {

            return array('status' => 400, 'msg' => 'please  quickbook intigrate', 'result' => '');
        }
    }

    public function invoicePaymentManage($dataService, $param)
    {

        try {

            if ($param->quickbook_partial_payment_id != 0) {

                $entities = $dataService->Query("SELECT * FROM Payment where Id='" . $param->quickbook_partial_payment_id . "' ");

                $thePayment = reset($entities);

                $updatePayment = Payment::update($thePayment, [

                    "CustomerRef" => ["value" => $param->quickbook_customer_id],
                    "TotalAmt" => $param->partial_payment,
                    "Line" => [
                        "Amount" => $param->partial_payment,
                        "LinkedTxn" => ["TxnId" => $param->quickbook_invoice_id, "TxnType" => "Invoice"],
                    ],
                ]);

                $resultingPaymentObj = $dataService->Update($updatePayment);
            } else {
                $updatePayment = Payment::create([

                    "CustomerRef" => ["value" => $param->quickbook_customer_id],
                    "TotalAmt" => $param->partial_payment,
                    "Line" => [
                        "Amount" => $param->partial_payment,
                        "LinkedTxn" => ["TxnId" => $param->quickbook_invoice_id, "TxnType" => "Invoice"],
                    ],
                ]);

                $resultingPaymentObj = $dataService->Add($updatePayment);
            }
            if (!empty($resultingPaymentObj)) {

                $this->INV->updateInvoive(array('invoice_id' => $param->invoice_id), array('quickbook_partial_payment_id' => $resultingPaymentObj->Id));
            }

            // return true;
        } catch (Exception $ex) {
            // echo 'Message: ' .$e->getMessage();

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

    #### not same as refund. Reset payments to what it was before, like this payment never happened.
    #### UPDATES INVOICE AFTER DELETION OF PARTIAL PAYMENT #####
    public function deletePaymentLog($value = '')
    {
        $data = $this->input->post();
        // die(print_r($data));
        //$payment_log_id = $data['payment_invoice_log_id'];
        $payment_log_id = $data['payment_log_id'];
        //does deleting payment need to update Invoice?
        $invoice_id = $data['invoice_id'];

        #### GET ALL PARTIAL PAYMENTS  and SETTING DATA['partial_payment'] ####

        $all_invoice_partials = $this->INV->getOneRow(array('invoice_id' => $invoice_id));
        $data['partial_payments'] = $all_invoice_partials->partial_payment;

        ####
        #### GET PARTIAL TO BE DELETE  /  SET TO DATA 'PAYMENT AMOUNT'' ####
        $where_id = array(
            'payment_invoice_logs_id' => $payment_log_id,
        );

        $partial_payment_to_delete = $this->PartialPaymentModel->getAllPartialPayment($where_id);
        $data['payment_amount'] = $partial_payment_to_delete[0]->payment_amount;

        ####
        #### UPDATING INVOICE THEN DELETING PARTIAL PAYMENT ####
        $where = array(
            'invoice_id' => $data['invoice_id'],
        );

        $param = array(
            'partial_payment' => ($data['payment_amount'] - $data['partial_payments']),
            'payment_status' => 0,
        );

        $invoice_details = $this->INV->updateInvoive($where, $param);

        $where = array(
            'invoice_id' => $invoice_id,
            'payment_invoice_logs_id' => $payment_log_id,
        );

        $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
        $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
        $time = $date->format('Y-m-d H:i:s');

        $result = $this->PaymentLogModel->createLogRecord(array(
            'invoice_id' => $invoice_id,
            'user_id' => $this->session->userdata['id'],
            "amount" => $data['payment_amount'],
            'action' => "Partial Payment Delete",
            'created_at' => $time,
        ));

        $invoice_details = $this->PartialPaymentModel->deletePartialPayment($where);

        #### UPDATE REFUND PAYMENT LOG ####
        $where = array(
            'invoice_id' => $invoice_id,
            'payment_invoice_logs_id' => $payment_log_id,
        );

        $refund_details = $this->RefundPaymentModel->deletePartialRefund($where);
    }

    #### UPDATES INVOICE AFTER FULL OR PARTIAL REFUND #####
    public function refundPayment($value = '')
    {
        $data = $this->input->post();
        // die(print_r($data));

        $customer_id = $data['customer_id'];
        $invoice_id = $data['invoice_id'];
        $payment_log_id = $data['payment_log_id'];

        #### GET PARTIAL TO BE UPDATED  /  SET TO DATA 'PAYMENT AMOUNT'' ####

        $partial_payment_refund = $this->PartialPaymentModel->getAllPartialPayment(array('payment_invoice_logs_id' => $payment_log_id));
        $data['payment_amount'] = $partial_payment_refund[0]->payment_amount;

        if (isset($data['payment_type']) && $data['payment_type'] == 'partial') {

            #### GET REFUND_AMOUNT_TOTAL FROM INVOICE TABLE ######
            $payment_refund_total = $this->INV->getOneRow(array('invoice_id' => $invoice_id));
            $data['refund_amount_total'] = $payment_refund_total->refund_amount_total;
            #####

            #### GET TOTAL PARTIAL PAYMENT FROM INVOICE #####
            $where = array(
                'invoice_id' => $invoice_id,
            );

            $all_invoice_partials = $this->INV->getOneRow($where);
            $data['partial_payments'] = $all_invoice_partials->partial_payment;

            ####

            $data['updated_payment'] = $data['payment_amount'] - $data['partial_payment'];
            //  die(print_r($data));

            ##### CREATE A NEW REFUND PAYMENT LOG #####
            $param = array(
                'payment_invoice_logs_id' => $payment_log_id,
                'invoice_id' => $invoice_id,
                'refund_amount' => $data['partial_payment'],
                'refund_datetime' => date("Y-m-d H:i:s"),
                'refund_method' => $data['payment_method'],
                'check_number' => (isset($data['check_number']) ? $data['check_number'] : ''),
                'cc_number' => (isset($data['cc_number']) ? $data['cc_number'] : ''),
                'refund_note' => (isset($data['other']) ? $data['other'] : ''),
                'customer_id' => $customer_id,
            );

            $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
            $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
            $time = $date->format('Y-m-d H:i:s');

            $result = $this->PaymentLogModel->createLogRecord(array(
                'invoice_id' => $invoice_id,
                'user_id' => $this->session->userdata['id'],
                "amount" => $data['partial_payment'],
                'action' => "Refund Given",
                'created_at' => $time,
            ));

            $refund_details = $this->RefundPaymentModel->createOnePartialRefund($param);
            ####
            //die(print_r($this->db->last_query()));

            $data['refund_amount'] = $data['partial_payments'] - $data['partial_payment'];
            $refund_amount = $data['refund_amount'];
            //$refund_amount_total = array_sum($data['refund_amount_total']);

            $where = array(
                'invoice_id' => $data['invoice_id'],
            );

            $param = array(
                'refund_amount_total' => ($data['refund_amount_total'] + $data['partial_payment']),
                // 'partial_payment' => ($data['partial_payments'] - $data['partial_payment'] )

            );

            $invoice_details = $this->INV->updateInvoive($where, $param);

            #### GET ALL PARTIAL PAYMENTS  and SETTING DATA['payment_total'] ####

            $where_id = array(
                'invoice_id' => $invoice_id,
            );

            $partial_payment_refund_total = $this->PartialPaymentModel->getAllPartialPayment($where_id);
            //$data['payment_amount_total'] = $partial_payment_refund[0]->payment_amount;
            //die(print_r($data));
            // die(print_r($partial_payment_refund_total ));

            $payment_total = [];
            foreach ($partial_payment_refund_total as $key2 => $value2) {
                $data['payment_total'][$key2] = $value2->payment_amount;

                #### TOTAL OF ALL PARTIAL PAYMENTS
                $payment_total = array_sum($data['payment_total']);
            }
            ####

            #### GET PARTIAL TO BE UPDATED  /  SET TO DATA 'PAYMENT AMOUNT'' ####

            $partial_payment_refund = $this->PartialPaymentModel->getAllPartialPayment(array('payment_invoice_logs_id' => $payment_log_id));
            $data['payment_amount'] = $partial_payment_refund[0]->payment_amount;

            #### GET ALL PARTIAL PAYMENTS BY LOG and SETTING DATA['payment_log_total'] ####

            $where_id = array(
                'payment_invoice_logs_id' => $payment_log_id,
            );

            $payment_log_refund_total = $this->RefundPaymentModel->getAllPartialRefund($where_id);
            //$data['payment_amount_total'] = $partial_payment_refund[0]->payment_amount;
            //die(print_r($data));
            // die(print_r($payment_log_refund_total ));

            $log_total = [];
            foreach ($payment_log_refund_total as $key3 => $value3) {
                $data['log_total'][$key3] = $value3->refund_amount;

                #### TOTAL OF ALL PARTIAL PAYMENTS
                $log_total = array_sum($data['log_total']);
            }
            $data['log_total'] = $log_total;

            ####

            $data['payment_applied'] = $data['payment_amount'] - $data['log_total'];
            $wherePayLog = array(
                'payment_invoice_logs_id' => $payment_log_id,
            );

            $param = array(
                'payment_applied' => $data['payment_applied'],
            );

            $update_details = $this->PartialPaymentModel->udpatePartialPayment($wherePayLog, $param);

            //die(print_r($data));

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Success! </strong> Invoice updated successfully</div>');
            redirect('admin/invoices/editInvoice/' . $invoice_id);
        } else if (isset($data['payment_type']) && $data['payment_type'] == 'full_partial') {

            #### GET ALL REFUND PAYMENTS  and SETTING DATA['refund_total'] ####

            // $where_id = array(
            //     'invoice_id' => $invoice_id
            // );

            // $refund_payment_total = $this->RefundPaymentModel->getAllPartialRefund($where_id);
            // //$data['payment_amount_total'] = $partial_payment_refund[0]->payment_amount;
            // //die(print_r($data));
            // //die(print_r($partial_payment_refund_total ));

            // $refund_total = [];
            // foreach($refund_payment_total as $key => $value){
            //     $data['refund_total'][$key] = $value->refund_amount;

            //     #### TOTAL OF ALL PARTIAL PAYMENTS
            //     $refund_total = array_sum($data['refund_total']);

            // }
            // ####
            // $data['refund_total'] = $refund_total;
            // die(print_r($data['refund_total']));
            // die(print_r($data));
            #### GET REFUND_AMOUNT_TOTAL FROM INVOICE TABLE ######
            $payment_refund_total = $this->INV->getOneRow(array('invoice_id' => $invoice_id));
            $data['refund_amount_total'] = $payment_refund_total->refund_amount_total;
            $data['partial_payments'] = $payment_refund_total->partial_payment;
            #####

            $where = array(
                'invoice_id' => $data['invoice_id'],
            );

            $param = array(
                // 'refund_amount_total' => $data['payment_amount'],
                'refund_amount_total' => ($data['refund_amount_total'] + $data['partial_payment']),
                // 'partial_payment' => ($data['partial_payments'] - $data['partial_payment'] ),
            );

            $invoice_details = $this->INV->updateInvoive($where, $param);

            ##### CREATE A NEW REFUND PAYMENT LOG #####

            // $refunded_amount = ($data['payment_amount'] - $data['partial_payments'] );

            $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
            $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
            $time = $date->format('Y-m-d H:i:s');

            $result = $this->PaymentLogModel->createLogRecord(array(
                'invoice_id' => $invoice_id,
                'user_id' => $this->session->userdata['id'],
                "amount" => $data['partial_payment'],
                'action' => "Refund Given",
                'created_at' => $time,
            ));

            $param = array(
                'payment_invoice_logs_id' => $payment_log_id,
                'invoice_id' => $invoice_id,
                'refund_amount' => $data['partial_payment'],
                'refund_datetime' => date("Y-m-d H:i:s"),
                'refund_method' => $data['payment_method'],
                'check_number' => (isset($data['check_number']) ? $data['check_number'] : ''),
                'cc_number' => (isset($data['cc_number']) ? $data['cc_number'] : ''),
                'refund_note' => (isset($data['other']) ? $data['other'] : ''),
                'customer_id' => $customer_id,
            );

            $refund_details = $this->RefundPaymentModel->createOnePartialRefund($param);
            ####

            ##### UPDATE PAYMENT INVOICE LOG #####

            // $adjusted = $data['payment_amount'] - $data['partial_payment'] - $data['refund_total'];
            // if ($adjusted > 0){
            //     echo "adjusted > 0";
            //     var_dump($adjusted);
            //     die();
            //     $data['payment_applied'] = $data['payment_amount'] - $data['refund_total'];
            // } else {
            //     echo "adjusted < 0";
            //     var_dump($adjusted);
            //     die();
            //     $data['payment_applied'] = 0;
            // }
            $where = array(
                'payment_invoice_logs_id' => $payment_log_id,
            );

            $param = array(
                'payment_applied' => 0,
            );

            $update_details = $this->PartialPaymentModel->udpatePartialPayment($where, $param);

            ###

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Success! </strong> Invoice updated successfully</div>');
            redirect('admin/invoices/editInvoice/' . $invoice_id);
            #####

        } else if (isset($data['payment_type']) && $data['payment_type'] == 'Full Refund') {
            #### GET REFUND_AMOUNT_TOTAL FROM INVOICE TABLE ######
            $payment_refund_total = $this->INV->getOneRow(array('invoice_id' => $invoice_id));
            $data['refund_amount_total'] = $payment_refund_total->refund_amount_total;
            // die(print_r($data));
            #### UPDATING INVOICE BY INVOICE ID SETTING PAYMENTS BACK TO $0.00 ####
            $where = array(
                'invoice_id' => $data['invoice_id'],
            );

            if (isset($data['check_number'])) {
                $check_number = $data['check_number'];
            } else if (isset($data['check_number_2'])) {
                $check_number = $data['check_number_2'];
            } else {
                $check_number = '';
            }

            if (isset($data['cc_number'])) {
                $cc_number = $data['cc_number'];
            } else if (isset($data['cc_number_2'])) {
                $cc_number = $data['cc_number_2'];
            } else {
                $cc_number = '';
            }

            if (isset($data['other'])) {
                $other = $data['other'];
            } else if (isset($data['other_2'])) {
                $other = $data['other_2'];
            } else {
                $other = '';
            }
            $param = array(
                // 'partial_payment' =>($payment_total - $payment_total),
                'refund_amount_total' => ($data['refund_amount_total'] + $data['partial_payment']),
                'payment_status' => $data['payment_status'],
            );

            $invoice_details = $this->INV->updateInvoive($where, $param);

            $CompanyData = $this->CompanyModel->getOneCompany(array('company_id' =>$this->session->userdata['company_id']));
            $date = new DateTime("now", new DateTimeZone($CompanyData->time_zone));
            $time = $date->format('Y-m-d H:i:s');

            $result = $this->PaymentLogModel->createLogRecord(array(
                'invoice_id' => $invoice_id,
                'user_id' => $this->session->userdata['id'],
                "amount" => $data['partial_payment'],
                'action' => "Refund Given",
                'created_at' => $time,
            ));

            ##### CREATE A NEW REFUND PAYMENT LOG #####
            $param = array(
                'payment_invoice_logs_id' => $payment_log_id,
                'invoice_id' => $invoice_id,
                'refund_amount' => $data['partial_payment'],
                'refund_datetime' => date("Y-m-d H:i:s"),
                'refund_method' => $data['payment_method'],
                'check_number' => $check_number,
                'cc_number' => $cc_number,
                'refund_note' => $cc_number,
                'customer_id' => $customer_id,
            );

            $refund_details = $this->RefundPaymentModel->createOnePartialRefund($param);
            ####

            ##### UPDATE PAYMENT INVOICE LOG #####
            // $data['payment_applied'] = $data['payment_amount'] - $data['refund_total'];
            $where = array(
                'invoice_id' => $data['invoice_id'],
            );

            $param = array(
                'payment_applied' => 0,
            );

            $update_details = $this->PartialPaymentModel->udpatePartialPayment($where, $param);

            ###

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Success! </strong> Invoice updated successfully</div>');
            redirect('admin/invoices/editInvoice/' . $invoice_id);
            ####

        }
    }

    public function printTechWorksheet($technician_job_assign_ids)
    {
        $tech_ids = explode(",", $technician_job_assign_ids);
        // die(print_r($tech_ids));
        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        $where_arr = array(
            'company_id' => $this->session->userdata['company_id'],
            'status' => 1,
        );
        //$data['basys_details'] =  $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['invoice_details'] = array();
        $data['routeDetails'] = array();
        $data['job_assign_details'] = array();
        $data['assigned_job_details'] = array();
        // $technician_job_assign_ids = explode(",", $technician_job_assign_ids);
        // die(print_r($assigned_job_details));
        foreach ($tech_ids as $key => $technician_job_assign_id) {
            $assigned_job_details = $this->Tech->getOneTechJobAssign(array('technician_job_assign_id' => $technician_job_assign_id));

            if (!isset($assigned_job_details)){
                $assigned_job_details = $this->Tech->getOneTechJobAssignNoProduct(array('technician_job_assign_id' => $technician_job_assign_id));
            }
            // die(print_r($assigned_job_details));
            //check for invoice id
            if ($assigned_job_details->invoice_id) {

                $invoice_where = array(
                    'invoice_tbl.invoice_id' => $assigned_job_details->invoice_id,
                );
            } else {
                $invoice_where = array(
                    'invoice_tbl.property_id' => $assigned_job_details->property_id,
                    'invoice_tbl.job_id' => $assigned_job_details->job_id,
                    'programs.program_id' => $assigned_job_details->program_id,
                    'invoice_tbl.customer_id' => $assigned_job_details->customer_id,
                    'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                );
            }
            // $data['assigned_job_details'] =   $this->Tech->getOneTechJobAssign(array('technician_job_assign_id' => $technician_job_assign_id));
            // die(print_r($data['assigned_job_details']));

            if ($this->INV->getOneInvoive($invoice_where)) {
                $invoice_details = $this->INV->getOneInvoive($invoice_where);
            } else {
                $invoice_details = $assigned_job_details;
                $invoice_details->invoice_id = '';
            }

            // die(print_r($invoice_details));
            //get job data
            $jobs = array();
            if ($invoice_details->invoice_id) {
                $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $invoice_details->invoice_id));
                if ($job_details) {
                    foreach ($job_details as $detail) {

                        $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $invoice_details->invoice_id));
                        //die(print_r($get_assigned_date));
                        if (isset($detail['report_id'])) {
                            $report = $this->RP->getOneRepots(array('report_id' => $detail['report_id']));
                        } else {
                            $report = '';
                        }

                        // SERVICE WIDE COUPONS
                        $arry = array(
                            'customer_id' => $invoice_details->customer_id,
                            'program_id' => $invoice_details->program_id,
                            'property_id' => $invoice_details->property_id,
                            'job_id' => $detail['job_id'],
                        );

                        $coupon_job = $this->CouponModel->getOneCouponJob($arry);
                        $coupon_job_amm = 0;
                        $coupon_job_amm_calc = 5;
                        $coupon_job_code = '';
                        if (!empty($coupon_job)) {
                            $coupon_job_amm = $coupon_job->coupon_amount;
                            $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                            $coupon_job_code = $coupon_job->coupon_code;
                        }

                        $jobs[] = array(
                            'job_id' => $detail['job_id'],
                            'job_name' => $detail['job_name'],
                            'job_description' => $detail['job_description'],
                            'job_cost' => $detail['job_cost'],
                            'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                            'program_name' => isset($detail['program_name']) ? $detail['program_name'] : '',
                            'job_report' => isset($report) ? $report : '',
                            'coupon_job_amm' => $coupon_job_amm,
                            'coupon_job_amm_calc' => $coupon_job_amm_calc,
                            'coupon_job_code' => $coupon_job_code,
                        );
                    }
                }
                $invoice_details->jobs = $jobs;
            }
            //die(print_r($));

            if (empty($invoice_details->cost)) {
                //figure cost
                $where = array(
                    'property_id' => $invoice_details->property_id,
                    'job_id' => $invoice_details->job_id,
                    'program_id' => $invoice_details->program_id,
                    'customer_id' => $invoice_details->customer_id,
                );

                $estimate_price_override = GetOneEstimateJobPriceOverride($where);
                if ($estimate_price_override && $estimate_price_override->price_override != 0) {
                    $invoice_details->cost = $estimate_price_override->price_override;
                } else {
                    $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $invoice_details->property_id, 'program_id' => $invoice_details->program_id));
                    if ($priceOverrideData && $priceOverrideData->price_override != 0) {
                        $invoice_details->cost = $priceOverrideData->price_override;
                    } else {
                        $price = $invoice_details->job_price;
                        $invoice_details->cost = ($invoice_details->yard_square_feet * $price) / 1000;
                    }
                }
            }
            #check for customer billing type
            $checkGroupBilling = $this->CustomerModel->checkGroupBilling($invoice_details->customer_id);
            if(isset($checkGroupBilling) && $checkGroupBilling == "true"){
                $data['is_group_billing'] = 1;
                $data['group_billing_details'] = $this->PropertyModel->getGroupBillingByProperty($invoice_details->property_id);
            }else{
                $data['is_group_billing'] = 0;
            }
            //figure tax
            $invoice_details->all_sales_tax = false;
            if ($data['setting_details']->is_sales_tax == 1) {
                $all_sales_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $invoice_details->property_id));
                if ($all_sales_tax) {
                    foreach ($all_sales_tax as $key3 => $all_sales_tax_details) {
                        $all_sales_tax[$key3]['tax_amount'] = $invoice_details->cost * $all_sales_tax_details['tax_value'] / 100;
                    }
                }
                $invoice_details->all_sales_tax = $all_sales_tax;
            }
            $invoice_details->report_details = false;
            $invoice_details->invoice_date = $assigned_job_details->job_assign_date;
            $invoice_details->notes = '';
            $invoice_details->report_id = 0;

            $whereArrPaidEstimate = array(
                'property_id' => $invoice_details->property_id,
                'program_id' => $invoice_details->program_id,
                'customer_id' => $invoice_details->customer_id,
                'status' => 3,
            );
            $estimate_paid = GetOneEstimateDetails($whereArrPaidEstimate);
            if ($estimate_paid) {
                $invoice_details->payment_status = 2;
            } else {
                if (!isset($invoice_details->payment_status)) {
                    $invoice_details->payment_status = 0;
                }
            }

            $invoice_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_details->invoice_id));

            array_push($data['invoice_details'], $invoice_details);

            $where_arr = array(
                'technician_job_assign.technician_id' => $assigned_job_details->technician_id,
                'technician_job_assign.job_assign_date' => $assigned_job_details->job_assign_date,
                'is_job_mode' => 0,
                'technician_job_assign.technician_job_assign_id' => $technician_job_assign_id,
                // 'technician_job_assign.technician_job_assign_id' => 40662
            );
            // Route Details
            $where_arrRoute = array(
                'technician_job_assign.technician_id' => $assigned_job_details->technician_id,
                'technician_job_assign.job_assign_date' => $assigned_job_details->job_assign_date,
                'is_job_mode' => 0,
                'technician_job_assign.route_id' => $assigned_job_details->route_id,
            );
            // die(print_r($where_arr));
            $data_jads = $this->Tech->getAllJobAssign($where_arr);
            // $data_jads = $this->Tech->getOneTechJobAssign($where_arr);
            // die(print_r($this->db->last_query ()));
            //die(print_r($data_jads));
            if (!empty($data_jads)) {

                array_push($data['job_assign_details'], $data_jads);
            }

            array_push($data['routeDetails'], $this->Tech->getRoutsByJobAssign($where_arrRoute));

            // die(print_r($data['routeDetails']));
            array_push($data['assigned_job_details'], $assigned_job_details);
        }
        // array_push($data['job_assign_details'], $this->Tech->getAllJobAssign($where_arr));
        // die(print_r($data_jads));
        // die(print_r($data['routeDetails']));
        // die(print_r($data['invoice_details']));
        // die(print_r($data['job_assign_details']));
        // die(print_r($this->db->last_query ()));
        if (!empty($data['job_assign_details'])) {

            $data['technician_route_details'] = array();

            $products = [];
            //die(print_r($data['job_assign_details']));

            foreach ($data['job_assign_details'] as $key => $value) {

                ##### looping thru products by job_id
                $products_used = getProductByJobIds(array('job_id' => $value[0]['job_id']));
                if (count($products_used) > 0) {
                    ##### setting amounts estimate use
                    foreach ($products_used as $key2 => $value2) {
                        // $product_details = $this->Tech->getAllProductDetails(array('products.product_id' => $value2->product_id));
                        if ($value2->mixture_application_per != '') {
                            $re = 0;
                            $reduced = reduceToOneAcre($value2->mixture_application_rate, $value2->mixture_application_rate_per);
                            if ($value2->mixture_application_per == '1 Acre') {
                                $re = $reduced / 43560;
                            } else {
                                $re = ($value2->mixture_application_rate) / 1000;
                            }
                            $used_mixture = $re * $value[0]['yard_square_feet'];
                            $used_mixture = number_format($used_mixture, 2);
                            $used_mixture = floatval($used_mixture);
                        } else {
                            $used_mixture = 0;
                        }

                        ###### AMOUNT MIXTURE  FUNCTION
                        $used_mixture = $used_mixture . ' ' . $value2->mixture_application_unit;
                        #### ADD ESTIMATE_USE TO PRODUCT_USED OBJECT ####
                        $products_used[$key2]->estimate_use = $used_mixture;
                    }
                }
                ## get service-specific notes
                $serviceSpecificNotes = "";
                $notesParam = array(
                    'note_assigned_services'=> $value[0]['job_id'],
                    'note_property_id'=>$value[0]['property_id'],
                    'note_status'=>1
                );
                $getPropertyServiceNotes = $this->CompanyModel->getNotesWhere($notesParam);
                if(!empty($getPropertyServiceNotes)){
                    foreach($getPropertyServiceNotes as $note){
                        $serviceSpecificNotes .= $note->note_contents ."<br>";
                    }
                }

                $serviceSpecificNotesCustomer = "";
                $notesParam2 = array(
                    'note_assigned_services'=> $value[0]['job_id'],
                    'note_property_id'=>$value[0]['property_id'],
                    'note_status'=>1
                );
                $getPropertyServiceNotes = $this->CompanyModel->getNotesWhere($notesParam2);
                if(!empty($getPropertyServiceNotes)){
                    foreach($getPropertyServiceNotes as $note){
                        $serviceSpecificNotesCustomer .= $note->note_contents ."<br>";
                    }
                }
                
                //die(print_r($serviceSpecificNotesCustomer));
                
                $technician_route_details = array(
                    'route_id' => $value[0]['route_id'],
                    'date' => $value[0]['job_assign_date'],
                    'route_note' => $value[0]['job_assign_notes'],
                    'first_name' => $value[0]['first_name'],
                    'last_name' => $value[0]['last_name'],
                    'billing_street' => $value[0]['billing_street'],
                    'billing_street_2' => $value[0]['billing_street_2'],
                    'billing_city' => $value[0]['billing_city'],
                    'billing_state' => $value[0]['billing_state'],
                    'billing_zipcode' => $value[0]['billing_zipcode'],
                    'time' => $value[0]['specific_time'],
                    'phone' => $value[0]['phone'],
                    'mobile' => $value[0]['mobile'],
                    'home_phone' => $value[0]['home_phone'],
                    'work_phone' => $value[0]['work_phone'],
                    'property_title' => $value[0]['property_title'],
                    'property_address' => $value[0]['property_address'],
                    'property_address_2' => $value[0]['property_address_2'],
                    'property_city' => $value[0]['property_city'],
                    'property_state' => $value[0]['property_state'],
                    'property_zip' => $value[0]['property_zip'],
                    'property_notes' => $value[0]['property_notes'],
                    'notes' => $value[0]['job_notes'],
                    'service_name' => $value[0]['job_name'],
                    'service_notes' => $value[0]['job_description'],
                    'service_specific_notes' => $serviceSpecificNotes,
                    'service_specific_notes_customer' => $serviceSpecificNotesCustomer,
                    'program_name' => $value[0]['program_name'],
                    'program_notes' => $value[0]['program_notes'],
                    'total_yard_grass' => $value[0]['total_yard_grass'],
                    'front_yard_grass' => $value[0]['front_yard_grass'],
                    'back_yard_grass' => $value[0]['back_yard_grass'],
                    'yard_square_feet' => $value[0]['yard_square_feet'],
                    'front_yard_square_feet' => $value[0]['front_yard_square_feet'],
                    'back_yard_square_feet' => $value[0]['back_yard_square_feet'],
                    'customer_alerts' => $value[0]['customer_alerts'],
                    'property_alerts' => $value[0]['property_alerts'],
                    'product_used' => $products_used,
                    'wind_speed' => isset($data['assigned_job_details'][$key]->max_wind_speed) ? $data['assigned_job_details'][$key]->max_wind_speed : '',
                    'temp' => isset($data['assigned_job_details'][$key]->temperature_information) ? $data['assigned_job_details'][$key]->temperature_information : '',
                    'tech_job_assign_id' => isset($data['assigned_job_details'][$key]->technician_job_assign_id) ? $data['assigned_job_details'][$key]->technician_job_assign_id : '',
                    'product_id' => isset($data['assigned_job_details'][$key]->product_id) ? $data['assigned_job_details'][$key]->product_id : '',
                    'epa_reg_nunber' => isset($data['assigned_job_details'][$key]->epa_reg_nunber) ? $data['assigned_job_details'][$key]->epa_reg_nunber : '',
                    'weed_pest_prevented' => isset($data['assigned_job_details'][$key]->weed_pest_prevented) ? $data['assigned_job_details'][$key]->weed_pest_prevented : '',
                    're_entry_time' => isset($data['assigned_job_details'][$key]->re_entry_time) ? $data['assigned_job_details'][$key]->re_entry_time : '',
                    'route_name' => isset($data['assigned_job_details'][$key]->route_name) ? $data['assigned_job_details'][$key]->route_name : '',
                    'user_first_name' => $value[0]['user_first_name'],
                    'user_last_name' => $value[0]['user_last_name'],
                    'amount_used' => isset($used_mixture) ? $used_mixture : '',

                );

                //customer notification flags
                $notify_array = $value[0]['pre_service_notification'] ? json_decode($value[0]['pre_service_notification']):[];
                $technician_route_details['pre_service_notification'] = "";
                if(in_array(1,$notify_array)){
                    $technician_route_details['pre_service_notification'] = "<span class=' alert-flag-red'>Call</span> ";
                }
                if(in_array(4,$notify_array)){
                    $technician_route_details['pre_service_notification'] .= "<span class=' alert-flag-blue'>Text ETA</span>";
                }
                if(is_array($notify_array) && (in_array(2,$notify_array) || in_array(3,$notify_array))){
                    $technician_route_details['pre_service_notification'] .= "<div class=' alert-flag-green'>Pre-Notified</div>";
                }



                array_push($data['technician_route_details'], $technician_route_details);
            }

            // die(print_r($data['invoice_details']));
            //  $routeDetails = $data['routeDetails'];
            // die(print_r($data['routeDetails']));
            // die(print_r($data['technician_route_details']));
            // die(print_r($technician_route_details));
            ####

            #### Adding date to routeDetails
            foreach ($data['routeDetails'] as $key3 => $val3) {
                if (isset($val3['route_id'])) {
                    $routeID = $val3['route_id'];
                    if (is_array($data['technician_route_details'])) {
                        foreach ($data['technician_route_details'] as $technician_route_detail) {
                            if (isset($technician_route_detail['route_id']) && $technician_route_detail['route_id'] == $routeID) {
                                $data['routeDetails'][$key]['date'] = $technician_route_detail['date'];
                            }
                        }
                    }
                }
            }
            // die(print_r($data['routeDetails']));
            ### end of add date

            $alldata = array();
            $Locations = array();
            if($this->session->userdata['spraye_technician_login']->start_location != "") {
                $statLocation = array(
                    'Name' => $this->session->userdata['spraye_technician_login']->start_location,
                    'Latitude' => $this->session->userdata['spraye_technician_login']->start_location_lat,
                    'Longitude' => $this->session->userdata['spraye_technician_login']->start_location_long,
                );
            } else {
                $statLocation = array(
                    'Name' => $data['setting_details']->start_location,
                    'Latitude' => $data['setting_details']->start_location_lat,
                    'Longitude' => $data['setting_details']->start_location_long,
                );
            }

            if($this->session->userdata['spraye_technician_login']->end_location != "") {
                $endLocation = array(
                    'Name' => $this->session->userdata['spraye_technician_login']->end_location,
                    'Latitude' => $this->session->userdata['spraye_technician_login']->end_location_lat,
                    'Longitude' => $this->session->userdata['spraye_technician_login']->end_location_long,
                );
            } else {
                $endLocation = array(
                    'Name' => $data['setting_details']->end_location,
                    'Latitude' => $data['setting_details']->end_location_lat,
                    'Longitude' => $data['setting_details']->end_location_long,
                );
            }

            if (!empty($data['job_assign_details'])) {

                foreach ($data['job_assign_details'] as $key => $value) {
                    $Locations[$key]['Name'] = $value[0]['property_address'];
                    $Locations[$key]['Latitude'] = $value[0]['property_latitude'];
                    $Locations[$key]['Longitude'] = $value[0]['property_longitude'];
                    $technician_id = $value[0]['technician_id'];
                    $job_assign_date = $value[0]['job_assign_date'];
                }

                array_unshift($Locations, $statLocation);
                array_push($Locations, $endLocation);
                // die(print_r($Locations));
            }

            $OptimizeParameters = array(
                "AppId" => RootAppId,
                "OptimizeType" => "distance",
                "RouteType" => "realroadcar",
                "Avoid" => "none",
                "Departure" => "2020-05-23T17:00:00",
            );

            $alldata['Locations'] = $Locations;
            $data['Locations'] = $Locations;
            $alldata['OptimizeParameters'] = $OptimizeParameters;
            $url = "https://optimizer3.routesavvy.com/RSAPI.svc/POSTOptimize";
            $handle = curl_init($url);
            $query = $alldata;
            //die(print_r($query));
            $payload = json_encode($alldata);
            //Set the url
            curl_setopt($handle, CURLOPT_POSTFIELDS, $payload);
            //die(print_r($payload));
            //Set the result output to be a string.
            curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($handle);
            curl_close($handle);
            //echo $result;
            $result = json_decode($result, true);
            $OptimizedStops = $result['OptimizedStops'];

            //die(print_r($this->db->last_query ()));
            // die(print_r($OptimizedStops));

            ### Here we are merging the technician route details and optimized stops array if the addresses match
            $route_results = array();
            $service_stops = array();
            foreach ($OptimizedStops as $key => $stop) {
                $stopAddress = $stop['Name'];
                if (is_array($data['technician_route_details'])) {
                    foreach ($data['technician_route_details'] as $route) {
                        if (is_array($route) && array_key_exists('property_address', $route)) {

                            if ($stopAddress == $route['property_address']) {
                                $route_results[] = array_merge($stop, $route);
                                //echo "Stop Address: ".$stopAddress."==  Route Address: ".$route['property_address']."<br>";
                            }
                        }
                    }
                }
            }
            // die(print_r($route_results));

            ######################################
            $data['route_results'] = $route_results;

            // die(print_r($data['route_results']));
            $where_arr = array(
                'technician_job_assign.company_id' => $this->session->userdata['company_id'],
                'technician_job_assign.route_id' => $assigned_job_details->route_id,
            );

            $data['allRoutes'] = $this->Tech->getAllOptimizedRoutes($where_arr);

            // die(print_r($data['allRoutes']));
            //die(print_r($this->db->last_query ()));

            //remove duplicate invoices
            $checkDupp = array();
            // $service_stops[0] = $data['setting_details']->start_location;
            foreach ($data['route_results'] as $key => $route) {
                if (!empty($route['tech_job_assign_id']) && !in_array($route['tech_job_assign_id'], $checkDupp)) {
                    $checkDupp[$key] = $route['tech_job_assign_id'];
                } elseif (in_array($route['tech_job_assign_id'], $checkDupp)) {
                    unset($data['route_results'][$key]);
                }
            }

            foreach ($data['route_results'] as $route_stop) {
                array_push($service_stops, $route_stop);
            }

            $data['route_results'] = $service_stops;

            // die(print_r($service_stops));
            // die(print_r($checkDupp));
            // $allRouteIds = [];
            // foreach($data['route_results'] as $routes){
            //     foreach($routes as $k5 => $value){
            //         echo $k5. " - " .$value. "<br/ >";
            //         // $allRouteIds[$k5] = $routes->route_id;

            //     }
            // }
            // die(print_r($data['route_results']));
            // die(print_r($data['route_results'][0]['route_id']));
            if (count($data['route_results']) > 0) {
                $this->load->view('admin/invoice/multiple_pdf_technician_print', $data);
                $html = $this->output->get_output();
                //die($html);
                // To render to browser to see HTML to format layout
                // die(print_r($data['route_results']));
                // die(print_r($html));
                // Load pdf library
                $this->load->library('pdf');
                // Load HTML content
                $this->dompdf->loadHtml($html);
                // (Optional) Setup the paper size and orientation
                $this->dompdf->setPaper('A4', 'portrate');
                ini_set('max_execution_time', '1800');
                // Render the HTML as PDF
                $this->dompdf->render();
                // Output the generated PDF (1 = download and 0 = preview)
                $companyName = str_replace(" ", "", $this->session->userdata['compny_details']->company_name);
                $fileName = $companyName . "_route_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
                $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
                exit;

                // } else if(empty($data['job_assign_details'])) {

                //     echo 'empty Service';

            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000">Route for service not available</div>');
                redirect("admin/availableRoutes");
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible" role="alert" data-auto-dismiss="4000">Route Information for service not available</div>');
            redirect("admin/availableRoutes");
        }
    }
    public function getEstimatedMixtureUsed($customer_id, $property_id, $program_id, $job_id)
    {
        #get technician_job_assign_id from tech_job_assign table for $customer_id, $property_id,$program_id,$job_id

        #if rows returned from above then...
        #foreach technician_job_assign_id....

        #get report_id

        #get mixture data from report_product table by report_id

        #else return "No Previous Data Available"
    }

    ## Download CSV for Completed Service Log Report
    public function downloadInvoiceCSV()
    {
        $data = $this->input->post();
        //die(print_r($data));
        if (isset($_POST['aging']) && $_POST['aging'] == 1) {
            $aging = 1;
        } else {
            $aging = 0;
        }

        $tblColumns = array(
            0 => 'invoice_id',
            1 => 'customer_id',
            2 => 'email',
            3 => 'cost',
            4 => 'balance_due',
            5 => 'status',
            6 => 'payment_status',
            7 => 'invoice_date',
            8 => 'sent_date',
            9 => 'opened_date',
            10 => 'payment_created',
        );

        $limit = 0;

        $start = 0;

        $order = 'invoice_id';

        $dir = 'DESC';

        // WHERE:
        $whereArr = array(
            'invoice_tbl.company_id' => $this->session->userdata['company_id'],
            'is_archived' => 0,
            'invoice_date >=' =>$data['date_init'],
            'invoice_date <=' =>$data['date_end']

        );
        if ($aging == 1) {
            $whereArr['payment_status !='] = 2;
            $whereArr['status !='] = 0;
            $whereArr['invoice_date >='] = $data['date_init'];
            $whereArr['invoice_date <='] = $data['date_end'];
        }

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

        if (is_array($this->input->post('columns'))) {

            $columns = $this->input->post('columns');

            foreach ($columns as $column) {
                if ($column['data'] == 'status' && $column['search']['value'] === '0') {
                    $whereArr['status'] = 0;
                }
                if ($column['data'] == 'payment_status' && $column['search']['value'] === '0') {
                    //$whereArr['payment_status']= "0 OR 'payment_status' = 3";
                    $orWhere['payment_status'] = array(0, 4); //include refunded when filtering for unpaid
                }

                if (isset($column['search']['value']) && !empty($column['search']['value'])) {

                    $col = $column['data'];
                    $val = $column['search']['value'];

                    //filter status
                    if ($col == 'status' && $val != 4) {
                        $whereArr[$col] = $val;
                    }
                    if ($col == 'payment_status' && $val != 4) {
                        $whereArr[$col] = $val;
                    }
                }
            }
        }

        if (empty($this->input->post('search')['value'])) {
            $invoices = $this->INV->ajaxActiveInvoicesTech($whereArr, $limit, $start, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, false);
        } else {
            $search = $this->input->post('search')['value'];
            $invoices = $this->INV->ajaxActiveInvoicesSearchTech($whereArr, $limit, $start, $search, $order, $dir, $whereArrExclude, $whereArrExclude2, $orWhere, false);
        }
        if (!empty($invoices)) {

            $delimiter = ",";
            $filename = "invoices_" . date('Y-m-d') . ".csv";

            //create a file pointer
            $f = fopen('php://memory', 'w');
            //$fp = fopen($options['db_backup_path'] . '/' . $backup_file_name ,'w+');

            //set column headers
            $fields = array('Invoice Id', 'Customer Name', 'Customer Email','Cost', 'Sales Tax','Invoice Amount', 'Balance Due' , 'Sent Status', 'Payment Status', 'Invoice Date', 'Sent Date', 'Opened Date', 'Payment Date');
            fputcsv($f, $fields, $delimiter);

            //output each row of the data, format line as csv and write to file pointer

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
                                    $coupon_job_amm_total = (float) $coupon_job_amm;
                                } else { // percentage
                                    $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
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
                            $invoice_total_cost -= (float) $coupon_invoice_amm;
                        } else { // percentage
                            $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
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
                            $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
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

                $sent_status = "";
                $sent_date = "";
                $open_date = "";
                $payStatus = "";
                $payment_date = "";

                switch ($invoice->status) {
                    case 0:
                        $sent_status = 'Unsent';
                        break;
                    case 1:
                        $sent_status = 'Sent';
                        $sent_date = isset($invoice->sent_date) ? date('Y-m-d', strtotime($invoice->sent_date)) : "";
                        break;
                    case 2:
                        $sent_status = 'Opened';
                        $sent_date = isset($invoice->sent_date) ? date('Y-m-d', strtotime($invoice->sent_date)) : "";
                        $open_date = isset($invoice->opened_date) ? date('Y-m-d', strtotime($invoice->opened_date)) : "";
                        break;
                    case 3: //the old status == 3 was for partial payments
                        $sent_status .= 'Opened';
                        $sent_date = isset($invoice->sent_date) ? date('Y-m-d', strtotime($invoice->sent_date)) : "";
                        $open_date = isset($invoice->opened_date) ? date('Y-m-d', strtotime($invoice->opened_date)) : "";
                        break;
                }

                switch ($invoice->payment_status) {
                    case 0:
                        $payStatus .= 'Unpaid';
                        break;
                    case 1:
                        $payStatus .= 'Partial';
                        $payment_date = isset($invoice->payment_created) ? date('Y-m-d', strtotime($invoice->payment_created)) : "";
                        break;
                    case 2:
                        $payStatus .= 'Paid';
                        $payment_date = isset($invoice->payment_created) ? date('Y-m-d', strtotime($invoice->payment_created)) : "";
                        break;
                    case 3:
                        $payStatus .= 'Past Due';
                        break;
                    case 4:
                        $payStatus .= 'Refunded';
                }

                $lineData = array($invoice->invoice_id, $invoice->customer_name, $invoice->email, '$' . number_format($invoice->cost, 2),'$' . number_format($invoice_total_tax, 2), '$' . number_format($invoice_total_cost, 2), $balance_due, $sent_status, $payStatus, date('m-d-Y', strtotime($invoice->invoice_date)), $sent_date, $open_date, $payment_date);
                fputcsv($f, $lineData, $delimiter);
            }
            if ($status == 1) {

                //move back to beginning of file
                fseek($f, 0);

                //set headers to download file rather than displayed
                header('Content-Type: text/csv');
                //  $pathName =  "down/".$filename;
                header('Content-Disposition: attachment; filename="' . $filename . '";');

                //output all remaining data on a file pointer
                fpassthru($f);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found </div>');
                redirect("admin/Invoices");
            }
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>No </strong> record found</div>');
            redirect("admin/Invoices");
        }
    }

    public function pendingJobInvoiceBlankData($technician_job_assign_ids)
    {
        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);
        // die(print_r($data['setting_details']));
        $where_arr = array(
            'company_id' => $this->session->userdata['company_id'],
            'status' => 1,
        );
        $data['basys_details'] = $this->BasysRequest->getOneBasysRequest($where_arr);
        $data['cardconnect_details'] = $this->CardConnectModel->getOneCardConnect($where_arr);
        $data['invoice_details'] = array();
        $tech_details = array();
        $technician_job_assign_ids = explode(",", $technician_job_assign_ids);
        foreach ($technician_job_assign_ids as $key => $technician_job_assign_id) {
            $total_invoice_cost_calc = 0;
            $assigned_job_details = $this->Tech->getOneTechJobAssign(array('technician_job_assign_id' => $technician_job_assign_id));

            if (!isset($assigned_job_details)){
                $assigned_job_details = $this->Tech->getOneTechJobAssignNoProduct(array('technician_job_assign_id' => $technician_job_assign_id));
            }
            if ($assigned_job_details) {
                if ($assigned_job_details->invoice_id) {
                    $invoice_where = array(
                        'invoice_tbl.invoice_id' => $assigned_job_details->invoice_id,
                    );
                } else {
                    $invoice_where = array(
                        'invoice_tbl.property_id' => $assigned_job_details->property_id,
                        'invoice_tbl.job_id' => $assigned_job_details->job_id,
                        'program_id' => $assigned_job_details->program_id,
                        'invoice_tbl.customer_id' => $assigned_job_details->customer_id,
                        'invoice_tbl.company_id' => $this->session->userdata['company_id'],
                    );
                }

                if ($this->INV->getOneInvoive($invoice_where)) {
                    $invoice_details = $this->INV->getOneInvoive($invoice_where);
                } else {
                    $invoice_details = $assigned_job_details;
                    $invoice_details->invoice_id = '';
                }

                $tech_details = $this->Tech->getTechUserDetails(array('technician_id' => $assigned_job_details->technician_id, 'technician_job_assign_id' => $assigned_job_details->technician_job_assign_id));
                $invoice_details->tech_details = $tech_details;

                #check for customer billing type
                $checkGroupBilling = $this->CustomerModel->checkGroupBilling($invoice_details->customer_id);
                if(isset($checkGroupBilling) && $checkGroupBilling == "true"){
                    $invoice_details->is_group_billing = 1;
                    $invoice_details->group_billing_details = $this->PropertyModel->getGroupBillingByProperty($invoice_details->property_id);
                }else{
                    $invoice_details->is_group_billing = 0;
                }
                if ($invoice_details->invoice_id) {
                    //get job data
                    $jobs = array();
                    $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' => $invoice_details->invoice_id));
                    if ($job_details) {
                        foreach ($job_details as $detail) {
                            // die(print_r($detail));

                            $get_assigned_date = $this->Tech->getOneTechJobAssign(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $invoice_details->invoice_id));
                            // die(print_r($get_assigned_date));

                            // Take into account services without products
                            if (!isset($get_assigned_date)){
                                $get_assigned_date = $this->Tech->getOneTechJobAssignNoProduct(array('technician_job_assign.job_id' => $detail['job_id'], 'invoice_id' => $invoice_details->invoice_id));
                            }
                            $tech_details = $this->Tech->getTechUserDetails(array('technician_id' => $assigned_job_details->technician_id, 'technician_job_assign_id' => $assigned_job_details->technician_job_assign_id));
                            // die(print_r($tech_details));

                            $product_details = $this->ProductModel->getAssignProductsNyJobs(array('job_id' => $detail['job_id']));

                            // die(print_r($product_details));
                            // SERVICE WIDE COUPONS
                            $arry = array(
                                'customer_id' => $invoice_details->customer_id,
                                'program_id' => $invoice_details->program_id,
                                'property_id' => $invoice_details->property_id,
                                'job_id' => $detail['job_id'],
                            );

                            $coupon_job = $this->CouponModel->getOneCouponJob($arry);
                            $coupon_job_amm = 0;
                            $coupon_job_amm_calc = 5;
                            $coupon_job_code = '';
                            if (!empty($coupon_job)) {
                                $coupon_job_amm = $coupon_job->coupon_amount;
                                $coupon_job_amm_calc = $coupon_job->coupon_amount_calculation;
                                $coupon_job_code = $coupon_job->coupon_code;
                            }

                            $jobs[] = array(
                                'job_id' => isset($detail['job_id']) ? $detail['job_id'] : '',
                                'job_name' => isset($detail['job_name']) ? $detail['job_name'] : '',
                                'job_description' => isset($detail['job_description']) ? $detail['job_description'] : '',
                                'job_cost' => isset($detail['job_cost']) ? $detail['job_cost'] : '',
                                'job_assign_date' => isset($get_assigned_date) ? date('m/d/Y', strtotime($get_assigned_date->job_assign_date)) : '',
                                'program_name' => isset($detail['program_name']) ? $detail['program_name'] : '',
                                'products' => isset($product_details) ? $product_details : '',
                                'coupon_job_amm' => $coupon_job_amm,
                                'coupon_job_amm_calc' => $coupon_job_amm_calc,
                                'coupon_job_code' => $coupon_job_code,
                            );
                        }
                    }
                    $job_selected = [];
                    foreach($technician_job_assign_ids as $iz){
                        $job_integration = $this->Tech->getAllJobAssignWhere( array('technician_job_assign_id' => $iz, 'property_tbl.property_id' => $assigned_job_details->property_id));
                        foreach($jobs as $projob){
                            foreach($job_integration as $selectedJob){
                                // die(print_r($selectedJob));
                                if($selectedJob['job_id'] == $projob['job_id'] && !in_array($projob, $job_selected)){
                                    array_push($job_selected, $projob );
                                }
                            }
                        }
                    }

                    // die(print_r($this->db->last_query()));
                    // die(print_r($technician_job_assign_ids));
                    // die(print_r($job_integration));
                    // die(print_r($proprojobinv));

                    // die(print_r($jobs));


                    // die(print_r($job_selected));
                    $invoice_details->jobs = $job_selected;


                    ////////////////////////////////////
                    // START INVOICE CALCULATION COST //

                    // vars
                    $tmp_invoice_id = $invoice_details->invoice_id;

                    // cost of all services (with price overrides) - service coupons
                    $job_cost_total = 0;
                    $where = array(
                        'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
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
                                        $coupon_job_amm_total = (float) $coupon_job_amm;
                                    } else { // percentage
                                        $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                                    }

                                    $job_cost = $job_cost - $coupon_job_amm_total;

                                    if ($job_cost < 0) {
                                        $job_cost = 0;
                                    }
                                }
                            }

                            $job_cost_total += $job_cost;
                        }
                        $invoice_total_cost = $job_cost_total;
                    } else {
                        $invoice_total_cost = $invoice_details->cost;
                    }

                    // check price override -- any that are not stored in just that ^^.

                    // - invoice coupons
                    $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
                    foreach ($coupon_invoice_details as $coupon_invoice) {
                        if (!empty($coupon_invoice)) {
                            $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                            $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                            if ($coupon_invoice_amm_calc == 0) { // flat amm
                                $invoice_total_cost -= (float) $coupon_invoice_amm;
                            } else { // percentage
                                $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                                $invoice_total_cost -= $coupon_invoice_amm;
                            }
                            if ($invoice_total_cost < 0) {
                                $invoice_total_cost = 0;
                            }
                        }
                    }

                    // + tax cost
                    $invoice_total_tax = 0;
                    $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
                    if (!empty($invoice_sales_tax_details)) {
                        foreach ($invoice_sales_tax_details as $tax) {
                            if (array_key_exists("tax_value", $tax)) {
                                $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                                $invoice_total_tax += $tax_amm_to_add;
                            }
                        }
                    }
                    //die(print_r($invoice_total_tax));
                    $invoice_total_cost += $invoice_total_tax;
                    $total_tax_amount = $invoice_total_tax;
                    $total_invoice_cost_calc = $invoice_total_cost - $invoice_details->partial_payment;
                    $invoice_details->total_invoice_cost_calc = $total_invoice_cost_calc;

                    // END TOTAL INVOICE CALCULATION COST //
                    ////////////////////////////////////////

                }

                if (empty($invoice_details->cost)) {
                    //figure cost
                    $where = array(
                        'property_id' => $invoice_details->property_id,
                        'job_id' => $invoice_details->job_id,
                        'program_id' => $invoice_details->program_id,
                        'customer_id' => $invoice_details->customer_id,
                    );

                    $estimate_price_override = GetOneEstimateJobPriceOverride($where);
                    if ($estimate_price_override && $estimate_price_override->price_override != 0) {
                        $invoice_details->cost = $estimate_price_override->price_override;
                    } else {
                        $priceOverrideData = $this->Tech->getOnePriceOverride(array('property_id' => $invoice_details->property_id, 'program_id' => $invoice_details->program_id));
                        if ($priceOverrideData && $priceOverrideData->price_override != 0) {
                            $invoice_details->cost = $priceOverrideData->price_override;
                        } else {
                            $price = $invoice_details->job_price;
                            $invoice_details->cost = ($invoice_details->yard_square_feet * $price) / 1000;
                        }
                    }
                }
                //figure tax
                $invoice_details->all_sales_tax = false;
                if ($data['setting_details']->is_sales_tax == 1) {
                    $all_sales_tax = $this->PropertySalesTax->getAllPropertySalesTax(array('property_id' => $invoice_details->property_id));
                    if ($all_sales_tax) {
                        foreach ($all_sales_tax as $key3 => $all_sales_tax_details) {
                            $all_sales_tax[$key3]['tax_amount'] = $invoice_details->cost * $all_sales_tax_details['tax_value'] / 100;
                        }
                    }
                    $invoice_details->all_sales_tax = $all_sales_tax;
                }
                $invoice_details->report_details = false;
                $invoice_details->invoice_date = $assigned_job_details->job_assign_date;
                $invoice_details->notes = '';
                $invoice_details->report_id = 0;

                $whereArrPaidEstimate = array(
                    'property_id' => $invoice_details->property_id,
                    'program_id' => $invoice_details->program_id,
                    'customer_id' => $invoice_details->customer_id,
                    'status' => 3,
                );
                $estimate_paid = GetOneEstimateDetails($whereArrPaidEstimate);
                if ($estimate_paid) {
                    $invoice_details->payment_status = 2;
                } else {
                    if (!isset($invoice_details->payment_status)) {
                        $invoice_details->payment_status = 0;
                    }
                }

                $invoice_details->coupon_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $invoice_details->invoice_id));
                $data['invoice_details'][] = $invoice_details;
            }

            //remove duplicate invoices
            $checkDupp = array();
            foreach ($data['invoice_details'] as $key => $invoice) {
                if (!empty($invoice->invoice_id) && !in_array($invoice->invoice_id, $checkDupp)) {
                    $checkDupp[$key] = $invoice->invoice_id;
                } elseif (isset($invoice->invoice_id) && in_array($invoice->invoice_id, $checkDupp)) {
                    unset($data['invoice_details'][$key]);
                }
            }
        }
        //die(print_r($data['invoice_details']));
        if (count($data['invoice_details']) > 0) {
            $this->load->view('admin/invoice/multiple_pdf_invoice_print_blank_data', $data);
            $html = $this->output->get_output();
//          return $html;
            // Load pdf library
            $this->load->library('pdf');
            // Load HTML content
            $this->dompdf->loadHtml($html);
            // (Optional) Setup the paper size and orientation
            $this->dompdf->setPaper('A4', 'portrate');
            ini_set('max_execution_time', '1800');
            // Render the HTML as PDF
            $this->dompdf->render();
            // Output the generated PDF (1 = download and 0 = preview)
            $companyName = str_replace(" ", "", $this->session->userdata['compny_details']->company_name);
            $fileName = $companyName . "_invoices_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
            $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
            exit;
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Invoice for service not available</div>');
            redirect("admin/manageJobs");
        }
    }
    public function groupBillingPdf($invoiceId){

        if(isset($invoiceId) && !empty($invoiceId)){
            $invoice_details =  $this->INV->getOneInvoive(array('invoice_id'=>$invoiceId));
            if(!empty($invoice_details->property_id)){
                $invoice_details->group_billing_details = $this->PropertyModel->getGroupBillingByProperty($invoice_details->property_id);
            }
            if(!empty($invoice_details->company_id)){
                $data['setting_details'] = $this->CompanyModel->getOneCompany(array('company_id'=>$invoice_details->company_id));
            }
            if(empty($invoice_details->job_id)){
                $jobs = array();
                $job_details = $this->PropertyProgramJobInvoiceModel->getOneInvoiceByPropertyProgram(array('property_program_job_invoice.invoice_id' =>$invoiceId));
                if($job_details){
                    foreach($job_details as $detail){
                        $get_assigned_date = $this->Tech->getOneJobAssign(array('technician_job_assign.job_id'=>$detail['job_id'],'invoice_id'=>$invoiceId));
                        if(isset($detail['report_id'])){
                            $report = $this->RP->getOneRepots(array('report_id'=>$detail['report_id']));
                        } else {
                            $report = '';
                        }
                        $jobs[]=array(
                            'job_id'=>$detail['job_id'],
                            'job_name'=>$detail['job_name'],
                            'job_description'=>$detail['job_description'],
                            'job_cost'=>$detail['job_cost'],
                            'job_assign_date'=>isset($get_assigned_date->job_assign_date) ? $get_assigned_date->job_assign_date : '',
                            'program_name'=>isset($detail['program_name']) ? $detail['program_name'] : '',
                            'job_report'=>isset($report) ? $report : '',
                        );
                    }
                }
                $invoice_details->jobs = $jobs;
            }
            if(!empty($invoice_details->report_id)){
                $invoice_details->report_details =  $this->RP->getOneRepots(array('report_id'=>$invoice_details->report_id));
            }
            $data['invoice_details'][] = $invoice_details;
            $this->load->view('admin/invoice/multiple_pdf_invoice_print_group_billing',$data);
            $html = $this->output->get_output();
            $this->load->library('pdf');
            $this->dompdf->loadHtml($html);
            $this->dompdf->setPaper('A4', 'portrate');
            // ini_set('max_execution_time', '1800');
            $this->dompdf->render();
            $companyName = str_replace(" ", "", $data['setting_details']->company_name);
            $fileName = $companyName . "_work_order_" . date("Y") . "_" . date("m") . "_" . date("d") . "_" . date("h") . "_" . date("i") . "_" . date("s");
            $this->dompdf->stream($fileName . ".pdf", array("Attachment" => 0));
            exit;
        }

    }

    public function calculateInvoiceCouponValue($param = array()){
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
                        $discount_amm = (float) $coupon_details->amount;

                        if (($total_cost - $discount_amm) < 0 ) {
                            $total_cost = 0;
                        } else {
                            $total_cost -= $discount_amm;
                            // die(print_r("Coupon is Flat Rate: " . $total_cost));
                        }

                    } else {
                        $percentage = (float) $coupon_details->amount;
                        $discount_amm = (float) $total_cost * ($percentage / 100);

                        if (($total_cost - $discount_amm) < 0 ) {
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

    public function calculateCustomerCouponCost($param = array()){
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

    public function calculateServiceCouponCost($param = array()){
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

        $total_cost = floatval($total_cost);
        return number_format($total_cost, 2, '.', ',');
    }
}